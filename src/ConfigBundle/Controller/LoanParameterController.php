<?php

namespace ConfigBundle\Controller;

use ConfigBundle\Entity\LoanParameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Loanparameter controller.
 *
 * @Route("loanparameter")
 */
class LoanParameterController extends Controller{
    /**
     * Lists all loanParameter entities.
     *
     * @Route("/list", name="loanparameter_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $loanParameters = $em->getRepository('ConfigBundle:LoanParameter')->findAll();

        return $this->render('loanparameter/index.html.twig', array(
            'loanParameters' => $loanParameters,
        ));
    }

    /**
     * Creates a new loanParameter entity.
     *
     * @Route("/new", name="loanparameter_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $loanParameter = new Loanparameter();
        $form = $this->createForm('ConfigBundle\Form\LoanParameterType', $loanParameter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($loanParameter);
            $em->flush();

            return $this->redirectToRoute('loanparameter_index');
        }

        return $this->render('loanparameter/new.html.twig', array(
            'loanParameter' => $loanParameter,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a loanParameter entity.
     *
     * @Route("/{id}/show", name="loanparameter_show")
     * @Method("GET")
     */
    public function showAction(LoanParameter $loanParameter)
    {
        $deleteForm = $this->createDeleteForm($loanParameter);

        return $this->render('loanparameter/show.html.twig', array(
            'loanParameter' => $loanParameter,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing loanParameter entity.
     *
     * @Route("/{id}/edit", name="loanparameter_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LoanParameter $loanParameter)
    {
        $deleteForm = $this->createDeleteForm($loanParameter);
        $editForm = $this->createForm('ConfigBundle\Form\LoanParameterType', $loanParameter);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $loanParameter->setCreatedAt(new \Datetime('now'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('loanparameter_index');
        }

        return $this->render('loanparameter/edit.html.twig', array(
            'loanParameter' => $loanParameter,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a loanParameter entity.
     *
     * @Route("/{id}", name="loanparameter_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, LoanParameter $loanParameter)
    {
        $form = $this->createDeleteForm($loanParameter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($loanParameter);
            $em->flush();
        }

        return $this->redirectToRoute('loanparameter_index');
    }

    /**
     * Creates a form to delete a loanParameter entity.
     *
     * @param LoanParameter $loanParameter The loanParameter entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LoanParameter $loanParameter)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('loanparameter_delete', array('id' => $loanParameter->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
