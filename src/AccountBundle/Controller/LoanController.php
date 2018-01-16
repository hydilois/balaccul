<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Operation;
use AccountBundle\Entity\Loan;
use ReportBundle\Entity\GeneralLedgerBalance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Loan controller.
 *
 * @Route("loan")
 */
class LoanController extends Controller{
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
     */
    public function memberRegistrationReceiptAction(Loan $loan){

        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

        $loanName = str_replace(' ', '_', $loan->getLoanCode());


        $html =  $this->renderView('loan/loan_fees_receipt_file.html.twig', array(
            'agency' => $agency,
            'loan' => $loan,
        ));

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(10, 5, 10, 10));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('Receipt_registration_'.$loanName);
        $response = new Response();
        $html2pdf->pdf->SetTitle('Registration_Receipt_'.$loanName);
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Registration_Receipt_'.$loanName.'.pdf');
        return $response;
    }

    /**
     * Creates a new loan entity.
     *
     * @Route("/new", name="loan_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request){
        $loan = new Loan();
        $form = $this->createForm('AccountBundle\Form\LoanType', $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Get the current user connected
            $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $currentUser    = $em->getRepository('UserBundle:Utilisateur')->find($currentUserId);

            $loanParameter = $em->getRepository('ConfigBundle:LoanParameter')->find(1)->getParameter();
            $member = $loan->getPhysicalMember();
            $target = $loan->getLoanAmount()/$loanParameter;
            
            if ($target >= ($member->getShare() + $member->getSaving())) {
                $this->addFlash('warning', 'The loan cannot be done!!!! check the amount of your share and savings accounts');
            }else{
                $account = $em->getRepository('ClassBundle:InternalAccount')->find(32);//Normal Loan Identification
                $account->setBalance($account->getBalance() - $loan->getLoanAmount());

                $classeLoan = $em->getRepository('ClassBundle:Classe')->find($account->getClasse()->getId());
                $classeLoan->setBalance($classeLoan->getBalance() - $loan->getLoanAmount());
                
                $operation = new Operation();
                $operation->setTypeOperation(Operation::TYPE_CASH_OUT);
                $operation->setCurrentUser($currentUser);
                $operation->setAmount($loan->getLoanAmount());
                $operation->setMember($member);
                $operation->setBalance($account->getBalance());
                $operation->setRepresentative($member->getName());
                $em->persist($operation);

                // first Step
                $ledgerBalanceOther = new GeneralLedgerBalance();
                $ledgerBalanceOther->setTypeOperation(Operation::TYPE_CASH_OUT);
                $ledgerBalanceOther->setCredit($loan->getLoanAmount());
                $ledgerBalanceOther->setCurrentUser($currentUser);
                $latestEntryGBL = $em->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                    [],
                    ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalanceOther->setBalance($latestEntryGBL->getBalance() - $loan->getLoanAmount());
                }else{
                    $ledgerBalanceOther->setBalance($loan->getLoanAmount());
                }
                $ledgerBalanceOther->setAccount($account);
                $ledgerBalanceOther->setRepresentative($member->getName());
                $ledgerBalanceOther->setAccountBalance($account->getBalance());
                $ledgerBalanceOther->setAccountTitle($account->getAccountName()." A/C_".$member->getMemberNumber());
                $ledgerBalanceOther->setMember($loan->getPhysicalMember());
                $em->persist($ledgerBalanceOther);
                $em->flush();


                $accountProcessing = $em->getRepository('ClassBundle:InternalAccount')->find(140);//Processing Fees
                $accountProcessing->setBalance($accountProcessing->getBalance() + $loan->getLoanProcessingFees());

                $classeProcessing = $em->getRepository('ClassBundle:Classe')->find($accountProcessing->getClasse()->getId());
                $classeProcessing->setBalance($classeProcessing->getBalance() + $loan->getLoanProcessingFees());
                
                $operationProcessing = new Operation();
                $operationProcessing->setTypeOperation(Operation::TYPE_CASH_IN);
                $operationProcessing->setCurrentUser($currentUser);
                $operationProcessing->setAmount($loan->getLoanProcessingFees());
                $operationProcessing->setMember($loan->getPhysicalMember());
                $operationProcessing->setRepresentative($member->getName());
                $operationProcessing->setBalance($accountProcessing->getBalance());

                // first Step
                $ledgerBalanceProc = new GeneralLedgerBalance();
                $ledgerBalanceProc->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceProc->setDebit($loan->getLoanProcessingFees());
                $ledgerBalanceProc->setCurrentUser($currentUser);
                $latestEntryGBL = $em->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                    [],
                    ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalanceProc->setBalance($latestEntryGBL->getBalance() + $loan->getLoanProcessingFees());
                }else{
                    $ledgerBalanceProc->setBalance($loan->getLoanProcessingFees());
                }
                $ledgerBalanceProc->setAccount($accountProcessing);
                $ledgerBalanceProc->setRepresentative($member->getName());
                $ledgerBalanceProc->setAccountBalance($accountProcessing->getBalance());
                $ledgerBalanceProc->setAccountTitle($accountProcessing->getAccountName()." A/C_".$member->getMemberNumber());
                $ledgerBalanceProc->setMember($loan->getPhysicalMember());
                $em->persist($ledgerBalanceProc);
                
                /*Make record*/
                $em->persist($operationProcessing);
                $em->persist($loan);
                $em->flush();
                return $this->redirectToRoute('loan_index');
            }
        }

        return $this->render('loan/new.html.twig', array(
            'loan' => $loan,
            'form' => $form->createView(),
        ));
    }


    /**
     * Creates a new loan entity.
     *
     * @Route("/new/moral", name="moral_loan_new")
     * @Method({"GET", "POST"})
     */
    public function newMoralLoanAction(Request $request)
    {
        $loan = new Loan();
        $form = $this->createForm('AccountBundle\Form\MoralLoanType', $loan);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $loanParameter = $em->getRepository('ConfigBundle:LoanParameter')->find(1)->getParameter();
            
            $shares = $em->getRepository('AccountBundle:Share')->findBy(
                    [
                        'moralMember' => $loan->getMoralMember(),
                    ]
                );

            $savings = $em->getRepository('AccountBundle:Saving')->findBy(
                    [
                        'moralMember' => $loan->getMoralMember(),
                    ]
                );

            $accountsAmount = 0;

            foreach ($shares as $share) {
                $accountsAmount += $share->getSolde();
            }

            foreach ($savings as $saving) {
                $accountsAmount += $saving->getSolde();
            }

            $target = $loan->getLoanAmount()/$loanParameter;
            if ($target > $accountsAmount) {
                $this->addFlash('warning', 'The loan cannot be done!!!! check the amount of your share and savings accounts');
            }else{

                $income = new TransactionIncome();

                $income->setAmount($loan->getLoanProcessingFees());
                $income->setDescription("Loan processing fees. Loan Code: ".$loan->getLoanCode()." // Loan Owner: ".$loan->getMoralMember());

                $em->persist($loan);
                $em->persist($income);


                $em->flush();
                return $this->redirectToRoute('loan_index');
            }
        }

        return $this->render('loan/new_moral.html.twig', array(
            'loan' => $loan,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a loan entity.
     *
     * @Route("/{id}", name="loan_show")
     * @Method("GET")
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
            ->getForm()
        ;
    }
}
