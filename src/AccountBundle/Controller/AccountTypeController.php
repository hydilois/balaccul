<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Accounttype controller.
 *
 * @Route("accounttype")
 */
class AccountTypeController extends Controller
{
    /**
     * Lists all accountType entities.
     *
     * @Route("/", name="accounttype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $accountTypes = $em->getRepository('AccountBundle:AccountType')->findAll();

        return $this->render('accounttype/index.html.twig', array(
            'accountTypes' => $accountTypes,
        ));
    }

    /**
     * Creates a new accountType entity.
     *
     * @Route("/new", name="accounttype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $accountType = new Accounttype();
        $form = $this->createForm('AccountBundle\Form\AccountTypeType', $accountType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($accountType);
            $em->flush();

            return $this->redirectToRoute('accounttype_index');
        }

        return $this->render('accounttype/new.html.twig', array(
            'accountType' => $accountType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a accountType entity.
     *
     * @Route("/{id}", name="accounttype_show")
     * @Method("GET")
     */
    public function showAction(AccountType $accountType)
    {
        $deleteForm = $this->createDeleteForm($accountType);

        return $this->render('accounttype/show.html.twig', array(
            'accountType' => $accountType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing accountType entity.
     *
     * @Route("/{id}/edit", name="accounttype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AccountType $accountType)
    {
        $deleteForm = $this->createDeleteForm($accountType);
        $editForm = $this->createForm('AccountBundle\Form\AccountTypeType', $accountType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('accounttype_index');
        }

        return $this->render('accounttype/edit.html.twig', array(
            'accountType' => $accountType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a accountType entity.
     *
     * @Route("/{id}", name="accounttype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AccountType $accountType)
    {
        $form = $this->createDeleteForm($accountType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($accountType);
            $em->flush();
        }

        return $this->redirectToRoute('accounttype_index');
    }

    /**
     * Creates a form to delete a accountType entity.
     *
     * @param AccountType $accountType The accountType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AccountType $accountType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('accounttype_delete', array('id' => $accountType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
