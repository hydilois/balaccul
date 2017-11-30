<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Share;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Share controller.
 *
 * @Route("share")
 */
class ShareController extends Controller
{
    /**
     * Lists all share entities.
     *
     * @Route("/", name="share_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shares = $em->getRepository('AccountBundle:Share')->findAll();

        return $this->render('share/index.html.twig', array(
            'shares' => $shares,
        ));
    }

    /**
     * Creates a new share entity.
     *
     * @Route("/new", name="share_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $share = new Share();
        $form = $this->createForm('AccountBundle\Form\ShareType', $share);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($share);
            $em->flush();

            return $this->redirectToRoute('share_index');
        }

        return $this->render('share/new.html.twig', array(
            'share' => $share,
            'form' => $form->createView(),
        ));
    }


    /**
     * Creates a new share entity.
     *
     * @Route("/new/moral", name="share_new_moral")
     * @Method({"GET", "POST"})
     */
    public function newMoralShareAction(Request $request){
        $share = new Share();
        $form = $this->createForm('AccountBundle\Form\MoralShareType', $share);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($share);
            $em->flush();

            return $this->redirectToRoute('share_index');
        }

        return $this->render('share/new_moral.html.twig', array(
            'share' => $share,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a share entity.
     *
     * @Route("/{id}", name="share_show")
     * @Method("GET")
     */
    public function showAction(Share $share)
    {
        $deleteForm = $this->createDeleteForm($share);

        return $this->render('share/show.html.twig', array(
            'share' => $share,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing share entity.
     *
     * @Route("/{id}/edit", name="share_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Share $share)
    {
        $deleteForm = $this->createDeleteForm($share);
        $editForm = $this->createForm('AccountBundle\Form\ShareType', $share);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('share_edit', array('id' => $share->getId()));
        }

        return $this->render('share/edit.html.twig', array(
            'share' => $share,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a share entity.
     *
     * @Route("/{id}", name="share_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Share $share)
    {
        $form = $this->createDeleteForm($share);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($share);
            $em->flush();
        }

        return $this->redirectToRoute('share_index');
    }

    /**
     * Creates a form to delete a share entity.
     *
     * @param Share $share The share entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Share $share)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('share_delete', array('id' => $share->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
