<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Deposit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Deposit controller.
 *
 * @Route("deposit")
 */
class DepositController extends Controller
{
    /**
     * Lists all deposit entities.
     *
     * @Route("/", name="deposit_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $deposits = $em->getRepository('AccountBundle:Deposit')->findAll();

        return $this->render('deposit/index.html.twig', array(
            'deposits' => $deposits,
        ));
    }

    /**
     * Creates a new deposit entity.
     *
     * @Route("/new/moral", name="deposit_new_moral")
     * @Method({"GET", "POST"})
     */
    public function newMoralDepositAccountAction(Request $request)
    {
        $deposit = new Deposit();
        $form = $this->createForm('AccountBundle\Form\MoralDepositType', $deposit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deposit);
            $em->flush();

            return $this->redirectToRoute('deposit_index');
        }

        return $this->render('deposit/new_moral.html.twig', array(
            'deposit' => $deposit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new deposit entity.
     *
     * @Route("/new", name="deposit_new")
     * @Method({"GET", "POST"})
     */
    public function newPhyicalDepositAccountAction(Request $request)
    {
        $deposit = new Deposit();
        $form = $this->createForm('AccountBundle\Form\DepositType', $deposit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deposit);
            $em->flush();

            return $this->redirectToRoute('deposit_index');
        }

        return $this->render('deposit/new.html.twig', array(
            'deposit' => $deposit,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a deposit entity.
     *
     * @Route("/{id}", name="deposit_show")
     * @Method("GET")
     */
    public function showAction(Deposit $deposit)
    {
        $deleteForm = $this->createDeleteForm($deposit);

        return $this->render('deposit/show.html.twig', array(
            'deposit' => $deposit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing deposit entity.
     *
     * @Route("/{id}/edit", name="deposit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Deposit $deposit)
    {
        $deleteForm = $this->createDeleteForm($deposit);
        $editForm = $this->createForm('AccountBundle\Form\DepositType', $deposit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('deposit_edit', array('id' => $deposit->getId()));
        }

        return $this->render('deposit/edit.html.twig', array(
            'deposit' => $deposit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a deposit entity.
     *
     * @Route("/{id}", name="deposit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Deposit $deposit)
    {
        $form = $this->createDeleteForm($deposit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deposit);
            $em->flush();
        }

        return $this->redirectToRoute('deposit_index');
    }

    /**
     * Creates a form to delete a deposit entity.
     *
     * @param Deposit $deposit The deposit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Deposit $deposit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('deposit_delete', array('id' => $deposit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
