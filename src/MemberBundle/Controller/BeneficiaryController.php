<?php

namespace MemberBundle\Controller;

use MemberBundle\Entity\Beneficiary;
use MemberBundle\Form\BeneficiaryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Beneficiary controller.
 *
 * @Route("beneficiary")
 */
class BeneficiaryController extends Controller
{
    /**
     * Lists all beneficiary entities.
     *
     * @Route("/", name="beneficiary_index")
     * @Method("GET")
     */
    public function index(){
        $entityManager = $this->getDoctrine()->getManager();
        $beneficiaries = $entityManager->getRepository(Beneficiary::class)->findAll();
        return $this->render('beneficiary/index.html.twig', [
            'beneficiaries' => $beneficiaries,
    ]);
    }

    /**
     * Creates a new beneficiary entity.
     *
     * @Route("/new", name="beneficiary_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $beneficiary = new Beneficiary();
        $form = $this->createForm(Beneficiary::class, $beneficiary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($beneficiary);
            $em->flush();

            return $this->redirectToRoute('beneficiary_index');
        }

        return $this->render('beneficiary/new.html.twig', [
            'beneficiary' => $beneficiary,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing beneficiary entity.
     *
     * @Route("/{id}/edit", name="beneficiary_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Beneficiary $beneficiary
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function update(Request $request, Beneficiary $beneficiary)
    {
        $form = $this->createForm(BeneficiaryType::class, $beneficiary);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('member_show', [
                'id' => $beneficiary->getIdMember()->getId()
            ]);
        }

        return $this->render('beneficiary/edit.html.twig', array(
            'beneficiary' => $beneficiary,
            'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a beneficiary entity.
     *
     * @Route("/{id}", name="beneficiary_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Beneficiary $beneficiary
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, Beneficiary $beneficiary){

        if ($request->getMethod() === 'DELETE') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($beneficiary);
            $em->flush();
        }
        return $this->redirectToRoute('beneficiary_index');
    }
}
