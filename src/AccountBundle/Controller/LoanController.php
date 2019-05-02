<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Loan;
use AccountBundle\Repository\LoanHistoryRepository;
use AccountBundle\Service\DatabaseBackupManager;
use AccountBundle\Service\FileUploader;
use ClassBundle\Entity\Classe;
use ClassBundle\Entity\InternalAccount;
use ConfigBundle\Entity\LoanParameter;
use Doctrine\Common\Persistence\ObjectManager;
use MemberBundle\Entity\Member;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Loan controller.
 *
 * @Route("loan")
 */
class LoanController extends Controller
{
    private $errors = [];

    /**
     * Lists all loan entities.
     *
     * @Route("/", name="loan_index")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $loans = $em->getRepository('AccountBundle:Loan')->findBy([],['loanCode' => 'ASC']);

        return $this->render('loan/index.html.twig', array(
            'loans' => $loans,
        ));
    }


    /**
     * print the loan receipt
     *
     * @Route("/{id}/receipt", name="loan_fees_receipt")
     * @Method("GET")
     * @param Loan $loan
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function receipt(Loan $loan)
    {

        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->findOneBy([], ['id' => 'ASC']);

        $loanName = str_replace(' ', '_', $loan->getLoanCode());

        $template = $this->renderView('loan/pdf_files/loan_fees_receipt_file.html.twig', [
            'agency' => $agency,
            'loan' => $loan,
        ]);
        $title = 'Receipt_Loan_' . $loanName . '_' . $loan->getDateLoan()->format('d-m-Y');
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 5, 10, 10));
        return $html2PdfService->generatePdf($template, $title . '.pdf', 'loans', $title, 'FI');
    }

    /**
     * Creates a new loan entity.
     *
     * @Route("/new", name="loan_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param DatabaseBackupManager $databaseBackupManager
     * @param FileUploader $fileUploader
     * @param ObjectManager $manager
     * @return Response
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function create(Request $request, DatabaseBackupManager $databaseBackupManager, FileUploader $fileUploader, ObjectManager $manager)
    {
        $loan = new Loan();
        $form = $this->createForm('AccountBundle\Form\LoanType', $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $db_user = $this->getParameter('database_user');
            $db_pass = $this->getParameter('database_password');
            $db_name = $this->getParameter('database_name');
            $databaseBackupManager->backup($db_user, $db_pass, $db_name, $fileUploader, 'Add New Loan');

            // Get the current user connected
            $currentUser = $this->getUser();
            $member = $loan->getPhysicalMember();

            if ($this->loanValidation($loan, $member)) {
                $this->addFlash('warning', $this->errors['message']);
            } else {
                $account = $manager->getRepository(InternalAccount::class)->findOneBy(
                    ['token' => 'LOAN'],
                    ['id' => 'ASC']
                );//Normal Loan Identification
                $account->setBalance($account->getBalance() - $loan->getLoanAmount());

                $classRepo = $manager->getRepository(Classe::class);
                $loanRepo = $manager->getRepository(Loan::class);

                $classLoan = $classRepo->find($account->getClasse());
                $classLoan->setBalance($classLoan->getBalance() - $loan->getLoanAmount());

                $loanRepo->saveLoanOperation($currentUser, $loan, $member, $account, $manager);
                $loanRepo->saveLoanInGeneralLedger($loan, $currentUser, $account, $member, $manager);

                if ($loan->getLoanProcessingFees() != 0) {
                    $accountProcessing = $manager->getRepository(InternalAccount::class)->findOneBy(
                        ['token' => 'PROCESSING_FEES'],
                        ['id' => 'ASC']
                    );//Processing Fees
                    $accountProcessing->setBalance($accountProcessing->getBalance() + $loan->getLoanProcessingFees());

                    $classProcessing = $classRepo->find($accountProcessing->getClasse());
                    $classProcessing->setBalance($classProcessing->getBalance() + $loan->getLoanProcessingFees());

                    /*Save the processing fees in the account situation*/
                    $loanRepo->saveLoanProcessingFeesOperation($currentUser, $loan, $member, $accountProcessing, $manager);

                    /*Save the processing fees in the general ledger*/
                    $loanRepo->saveProcessingFeesInGeneralLedger($loan, $currentUser, $accountProcessing, $member, $manager);
                }

                /*Make record*/
                $manager->persist($loan);
                $manager->flush();
                return $this->redirectToRoute('loan_index');
            }
        }

        return $this->render('loan/new.html.twig', [
            'loan' => $loan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a loan entity.
     *
     * @Route("/{id}", name="loan_show")
     * @Method("GET")
     * @param Loan $loan
     * @param ObjectManager $manager
     * @return Response
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function show(Loan $loan, ObjectManager $manager)
    {
        $loanHistoryRepo = $manager->getRepository('AccountBundle:LoanHistory');
        $lowest_remain_amount_LoanHistory = intval($loanHistoryRepo->lowestLoanAmount($loan));
        $latestLoanHistory = $loanHistoryRepo->findOneBy([
            'remainAmount' => $lowest_remain_amount_LoanHistory,
            'loan' => $loan],
            ['id' => 'DESC']);

        $loanHistories = $loanHistoryRepo->findBy(['loan' => $loan]);

        return $this->render('loan/show.html.twig', [
            'loan' => $loan,
            'loanHistory' => $latestLoanHistory,
            'loanHistories' => $loanHistories,
        ]);
    }

    /**
     * @param Loan $loan
     * @param Member $member
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return bool
     */
    private function loanValidation(Loan $loan, Member $member)
    {
        $this->errors = [];
        $em = $this->getDoctrine()->getManager();
        $loanParameter = $em->getRepository(LoanParameter::class)->findOneBy([], ['id' => 'ASC']);
        if (!$loanParameter) {
            $this->errors['message'] = 'The value of the loan parameter is not yet set';
            return true;
        }
        $target = $loan->getLoanAmount() / $loanParameter->getParameter();
        if ($target >= ($member->getShare() + $member->getSaving())) {
            $this->errors['message'] = 'The loan cannot be done!!!! check the amount of your share and savings accounts';
            return true;
        }
        $loanExist = $em->getRepository(Loan::class)->findOneBy([
            'physicalMember' => $member,
            'status' => true
        ]);
        if ($loanExist) {
            $this->errors['message'] = 'This member has a loan which is not yet closed';
            return true;
        }
        return false;
    }
}
