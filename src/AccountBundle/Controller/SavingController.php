<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Saving;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Saving controller.
 *
 * @Route("saving")
 */
class SavingController extends Controller
{
    /**
     * Lists all saving entities.
     *
     * @Route("/", name="saving_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $savings = $em->getRepository('AccountBundle:Saving')->findBy(
            [],
            [
                'accountNumber' => 'ASC',
            ]);

        return $this->render('saving/index.html.twig', array(
            'savings' => $savings,
        ));
    }

    /**
     * Creates a new saving entity.
     *
     * @Route("/new", name="saving_new")
     * @Method({"GET", "POST"})
     */
    public function newPhyicalSavingAction(Request $request)
    {
        $saving = new Saving();
        $form = $this->createForm('AccountBundle\Form\SavingType', $saving);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($saving);
            $em->flush();

            return $this->redirectToRoute('saving_index');
        }

        return $this->render('saving/new.html.twig', array(
            'saving' => $saving,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a saving entity.
     *
     * @Route("/{id}", name="saving_show")
     * @Method("GET")
     */
    public function showAction(Saving $saving)
    {
        $deleteForm = $this->createDeleteForm($saving);

        return $this->render('saving/show.html.twig', array(
            'saving' => $saving,
            'delete_form' => $deleteForm->createView(),
        ));
    }



     /**
     * Creates a new account entity.
     *
     * @Route("/moral/new", name="moralsavingaccount_new")
     * @Method({"GET", "POST"})
     */
    public function newMoralSavingAccountAction(Request $request){
        $account = new Saving();
        $form = $this->createForm('AccountBundle\Form\MoralSavingType', $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            return $this->redirectToRoute('saving_index');
        }

        return $this->render('saving/new_moral_saving.html.twig', array(
            'account' => $account,
            'form' => $form->createView(),
        ));
    }



    /**
     * Displays a form to edit an existing saving entity.
     *
     * @Route("/{id}/edit", name="saving_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Saving $saving)
    {
        $deleteForm = $this->createDeleteForm($saving);
        $editForm = $this->createForm('AccountBundle\Form\SavingType', $saving);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('saving_edit', array('id' => $saving->getId()));
        }

        return $this->render('saving/edit.html.twig', array(
            'saving' => $saving,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a saving entity.
     *
     * @Route("/{id}", name="saving_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Saving $saving)
    {
        $form = $this->createDeleteForm($saving);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($saving);
            $em->flush();
        }

        return $this->redirectToRoute('saving_index');
    }

    /**
     * Creates a form to delete a saving entity.
     *
     * @param Saving $saving The saving entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Saving $saving)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('saving_delete', array('id' => $saving->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
