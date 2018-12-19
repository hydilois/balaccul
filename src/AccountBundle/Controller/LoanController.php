<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Operation;
use AccountBundle\Entity\Loan;
use ClassBundle\Entity\Classe;
use ClassBundle\Entity\InternalAccount;
use ConfigBundle\Entity\LoanParameter;
use MemberBundle\Entity\Member;
use ReportBundle\Entity\GeneralLedgerBalance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\Utilisateur;

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
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $loans = $em->getRepository('AccountBundle:Loan')->findAll();

        return $this->render('loan/index.html.twig', array(
            'loans' => $loans,
        ));
    }


    /**
     * Finds and displays a loan entity.
     *
     * @Route("/{id}/receipt", name="loan_fees_receipt")
     * @Method("GET")
     * @param Loan $loan
     * @return Response
     */
    public function loanReceipt(Loan $loan){

        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->findOneBy([],['id' => 'ASC']);

        $loanName = str_replace(' ', '_', $loan->getLoanCode());

        $template =  $this->renderView('loan/pdf_files/loan_fees_receipt_file.html.twig', [
            'agency' => $agency,
            'loan' => $loan,
        ]);
        $title = 'Receipt_Loan_'.$loanName.'_'.$loan->getDateLoan()->format('d-m-Y');
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 5, 10, 10));
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'loans',$title, 'FI');
    }

    /**
     * Creates a new loan entity.
     *
     * @Route("/new", name="loan_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){
        $loan = new Loan();
        $form = $this->createForm('AccountBundle\Form\LoanType', $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Get the current user connected
            $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $currentUser    = $em->getRepository(Utilisateur::class)->find($currentUserId);
            $member = $loan->getPhysicalMember();

            if ($this->loanValidation($loan, $member)) {
                $this->addFlash('warning', $this->errors['message']);
            } else {
                $account = $em->getRepository(InternalAccount::class)->find(32);//Normal Loan Identification
                $account->setBalance($account->getBalance() - $loan->getLoanAmount());

                $classLoan = $em->getRepository(Classe::class)->find($account->getClasse()->getId());
                $classLoan->setBalance($classLoan->getBalance() - $loan->getLoanAmount());

                $em ->getRepository(Loan::class)->saveLoanOperation($currentUser, $loan, $member, $account);
                $em ->getRepository(Loan::class)->saveLoanInGeneralLedger($loan, $currentUser, $account, $member);
                $em->flush();

                $accountProcessing = $em->getRepository(InternalAccount::class)->find(140);//Processing Fees
                $accountProcessing->setBalance($accountProcessing->getBalance() + $loan->getLoanProcessingFees());

                $classProcessing = $em->getRepository(Classe::class)->find($accountProcessing->getClasse()->getId());
                $classProcessing->setBalance($classProcessing->getBalance() + $loan->getLoanProcessingFees());

                $em ->getRepository(Loan::class)->saveLoanProcessingFeesOperation($currentUser, $loan, $member, $accountProcessing);

                // first Step
                $em ->getRepository(Loan::class)->saveProcessingFeesInGeneralLedger($loan, $currentUser, $accountProcessing, $member);

                /*Make record*/
                $em->persist($loan);
                $em->flush();
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
     * @return Response
     */
    public function showAction(Loan $loan){
        $em = $this->getDoctrine()->getManager();
        $lowest_remain_amount_LoanHistory = $em->createQueryBuilder()
            ->select('MIN(lh.remainAmount)')
            ->from('AccountBundle:LoanHistory', 'lh')
            ->innerJoin('AccountBundle:Loan', 'l', 'WITH','lh.loan = l.id')
            ->where('l.id = :loan')->setParameter('loan', $loan)
            ->getQuery()
            ->getSingleScalarResult();

        $latestLoanHistory = $em->getRepository('AccountBundle:LoanHistory')->findOneBy([
                            'remainAmount' => $lowest_remain_amount_LoanHistory,
                            'loan' => $loan],
                            ['id' => 'DESC']);

        $loanHistories = $em->getRepository('AccountBundle:LoanHistory')->findBy(['loan' => $loan]);

        return $this->render('loan/show.html.twig', array(
            'loan' => $loan,
            'loanHistory' => $latestLoanHistory,
            'loanHistories' => $loanHistories,
        ));
    }

    /**
     * Displays a form to edit an existing loan entity.
     *
     * @Route("/{id}/edit", name="loan_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Loan $loan
     * @return Response
     */
    public function editAction(Request $request, Loan $loan)
    {
        $deleteForm = $this->createDeleteForm($loan);
        $editForm = $this->createForm('AccountBundle\Form\LoanType', $loan);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('loan_edit', array('id' => $loan->getId()));
        }

        return $this->render('loan/edit.html.twig', array(
            'loan' => $loan,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a loan entity.
     *
     * @Route("/{id}", name="loan_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Loan $loan
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Loan $loan)
    {
        $form = $this->createDeleteForm($loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($loan);
            $em->flush();
        }

        return $this->redirectToRoute('loan_index');
    }

    /**
     * Creates a form to delete a loan entity.
     *
     * @param Loan $loan The loan entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Loan $loan)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('loan_delete', array('id' => $loan->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Loan $loan
     * @param Member $member
     * @return bool
     */
    private function loanValidation(Loan $loan, Member $member)
    {
        $this->errors = [];
        $em = $this->getDoctrine()->getManager();
        $loanParameter = $em->getRepository(LoanParameter::class)->findOneBy([],['id' => 'ASC']);
        if (!$loanParameter){
            $this->errors['message'] = 'The value of the loan parameter is not yet set';
            return true;
        }
        $target = $loan->getLoanAmount()/$loanParameter->getParameter();
        if ($target >= ($member->getShare() + $member->getSaving())){
            $this->errors['message'] = 'The loan cannot be done!!!! check the amount of your share and savings accounts';
            return true;
        }
        $loanExist = $em->getRepository(Loan::class)->findOneBy([
            'physicalMember' => $member,
            'status' => true
        ]);
        if ($loanExist){
            $this->errors['message'] = 'This member has a loan which is not yet closed';
            return true;
        }
        return false;
    }
}
