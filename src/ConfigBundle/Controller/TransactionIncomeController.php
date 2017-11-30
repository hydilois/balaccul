<?php

namespace ConfigBundle\Controller;

use ConfigBundle\Entity\TransactionIncome;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Transactionincome controller.
 *
 * @Route("transactionincome")
 */
class TransactionIncomeController extends Controller
{
    /**
     * Lists all transactionIncome entities.
     *
     * @Route("/", name="transactionincome_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $transactionIncomes = $em->getRepository('ConfigBundle:TransactionIncome')->findAll();

        return $this->render('transactionincome/index.html.twig', array(
            'transactionIncomes' => $transactionIncomes,
        ));
    }

    /**
     * Creates a new transactionIncome entity.
     *
     * @Route("/new", name="transactionincome_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $transactionIncome = new Transactionincome();
        $form = $this->createForm('ConfigBundle\Form\TransactionIncomeType', $transactionIncome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transactionIncome);
            $em->flush();

            return $this->redirectToRoute('transactionincome_show', array('id' => $transactionIncome->getId()));
        }

        return $this->render('transactionincome/new.html.twig', array(
            'transactionIncome' => $transactionIncome,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a transactionIncome entity.
     *
     * @Route("/{id}", name="transactionincome_show")
     * @Method("GET")
     */
    public function showAction(TransactionIncome $transactionIncome)
    {
        $deleteForm = $this->createDeleteForm($transactionIncome);

        return $this->render('transactionincome/show.html.twig', array(
            'transactionIncome' => $transactionIncome,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing transactionIncome entity.
     *
     * @Route("/{id}/edit", name="transactionincome_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TransactionIncome $transactionIncome)
    {
        $deleteForm = $this->createDeleteForm($transactionIncome);
        $editForm = $this->createForm('ConfigBundle\Form\TransactionIncomeType', $transactionIncome);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transactionincome_edit', array('id' => $transactionIncome->getId()));
        }

        return $this->render('transactionincome/edit.html.twig', array(
            'transactionIncome' => $transactionIncome,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a transactionIncome entity.
     *
     * @Route("/{id}", name="transactionincome_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TransactionIncome $transactionIncome)
    {
        $form = $this->createDeleteForm($transactionIncome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transactionIncome);
            $em->flush();
        }

        return $this->redirectToRoute('transactionincome_index');
    }

    /**
     * Creates a form to delete a transactionIncome entity.
     *
     * @param TransactionIncome $transactionIncome The transactionIncome entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TransactionIncome $transactionIncome)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('transactionincome_delete', array('id' => $transactionIncome->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
