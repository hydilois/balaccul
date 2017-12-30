<?php

namespace ConfigBundle\Controller;

use ConfigBundle\Entity\Agency;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Agency controller.
 *
 * @Route("agency")
 */
class AgencyController extends Controller
{
    /**
     * Lists all agency entities.
     *
     * @Route("/list", name="agency_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $agencies = $em->getRepository('ConfigBundle:Agency')->findAll();

        return $this->render('agency/index.html.twig', array(
            'agencies' => $agencies,
        ));
    }

    /**
     * Creates a new agency entity.
     *
     * @Route("/new", name="agency_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $agency = new Agency();
        $form = $this->createForm('ConfigBundle\Form\AgencyType', $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($agency);
            $em->flush();

            return $this->redirectToRoute('agency_index');
        }

        return $this->render('agency/new.html.twig', array(
            'agency' => $agency,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a agency entity.
     *
     * @Route("/{id}/show", name="agency_show")
     * @Method("GET")
     */
    public function showAction(Agency $agency)
    {
        $deleteForm = $this->createDeleteForm($agency);

        return $this->render('agency/show.html.twig', array(
            'agency' => $agency,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing agency entity.
     *
     * @Route("/{id}/edit", name="agency_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Agency $agency)
    {
        $deleteForm = $this->createDeleteForm($agency);
        $editForm = $this->createForm('ConfigBundle\Form\AgencyType', $agency);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agency_index');
        }

        return $this->render('agency/edit.html.twig', array(
            'agency' => $agency,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a agency entity.
     *
     * @Route("/{id}", name="agency_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Agency $agency)
    {
        $form = $this->createDeleteForm($agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($agency);
            $em->flush();
        }

        return $this->redirectToRoute('agency_index');
    }

    /**
     * Creates a form to delete a agency entity.
     *
     * @param Agency $agency The agency entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Agency $agency){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agency_delete', array('id' => $agency->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
