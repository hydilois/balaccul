<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Loan;
use ConfigBundle\Entity\TransactionIncome;
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
    public function newAction(Request $request)
    {
        $loan = new Loan();
        $form = $this->createForm('AccountBundle\Form\LoanType', $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $loanParameter = $em->getRepository('ConfigBundle:LoanParameter')->find(1)->getParameter();
            
            $shares = $em->getRepository('AccountBundle:Share')->findBy(
                    [
                        'physicalMember' => $loan->getPhysicalMember(),
                    ]
                );

            $savings = $em->getRepository('AccountBundle:Saving')->findBy(
                    [
                        'physicalMember' => $loan->getPhysicalMember(),
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
                $income->setDescription("Loan processing fees. Loan Code: ".$loan->getLoanCode()." // Loan Owner: ".$loan->getPhysicalMember()." // Amount: ".$loan->getLoanProcessingFees());

                $em->persist($loan);
                $em->persist($income);

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
    public function showAction(Loan $loan)
    {
        $deleteForm = $this->createDeleteForm($loan);

        return $this->render('loan/show.html.twig', array(
            'loan' => $loan,
            'delete_form' => $deleteForm->createView(),
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
