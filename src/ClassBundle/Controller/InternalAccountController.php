<?php

namespace ClassBundle\Controller;

use ClassBundle\Entity\InternalAccount;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Internalaccount controller.
 *
 * @Route("internalaccount")
 */
class InternalAccountController extends Controller
{
    /**
     * Lists all internalAccount entities.
     *
     * @Route("/", name="internalaccount_index")
     * @Method("GET")
     */
    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        
        $internalAccount = new InternalAccount();
        $formIA = $this->createForm('ClassBundle\Form\InternalAccountType', $internalAccount);

        $internalAccounts = $em->getRepository('ClassBundle:InternalAccount')->findAll();

        return $this->render('internalaccount/index.html.twig', array(
            'internalAccounts' => $internalAccounts,
            'formIA' => $formIA->createView(),
        ));
    }

    /**
     * Creates a new internalAccount entity.
     *
     * @Route("/new", name="internalaccount_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $internalAccount = new Internalaccount();
        $form = $this->createForm('ClassBundle\Form\InternalAccountType', $internalAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($internalAccount);
            $em->flush();

            return $this->redirectToRoute('internalaccount_index');
        }

        return $this->render('internalaccount/new.html.twig', array(
            'internalAccount' => $internalAccount,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a internalAccount entity.
     *
     * @Route("/{id}", name="internalaccount_show")
     * @Method("GET")
     */
    public function showAction(InternalAccount $internalAccount)
    {
        $deleteForm = $this->createDeleteForm($internalAccount);

        return $this->render('internalaccount/show.html.twig', array(
            'internalAccount' => $internalAccount,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing internalAccount entity.
     *
     * @Route("/{id}/edit", name="internalaccount_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, InternalAccount $internalAccount){
        $deleteForm = $this->createDeleteForm($internalAccount);
        $editForm = $this->createForm('ClassBundle\Form\InternalAccountEditType', $internalAccount);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('internalaccount_index');
        }

        return $this->render('internalaccount/edit.html.twig', array(
            'internalAccount' => $internalAccount,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a internalAccount entity.
     *
     * @Route("/{id}", name="internalaccount_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, InternalAccount $internalAccount)
    {
        $form = $this->createDeleteForm($internalAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($internalAccount);
            $em->flush();
        }

        return $this->redirectToRoute('internalaccount_index');
    }

    /**
     * Creates a form to delete a internalAccount entity.
     *
     * @param InternalAccount $internalAccount The internalAccount entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(InternalAccount $internalAccount)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('internalaccount_delete', array('id' => $internalAccount->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * Get informations related to one subclasse
     * @param  Request $request - the request paramemter HTTP one
     * @return JSON           - a json representation of the classe
     * @Route("/subclass/list", name="get_list_subclass")
     */
    public function getlistSubClasse(Request $request){
        $requestParsed  = json_decode(json_encode($request->request->get("data")));

        $idClasse    = $requestParsed->idClasse;

        if($idClasse < 1){
            return json_encode([
                "message" => "Error the value given is less than 1", 
                "params" => $request, 
                "status" => "failed", 
                "data" => json_decode(json_encode([])),
            ]);
        }

        $entityManager  = $this->getDoctrine()->getManager();

        try{

            $classe = $entityManager->getRepository('ClassBundle:Classe')->find($idClasse);
            $query = $entityManager->createQueryBuilder()
                ->select('c')
                ->from('ClassBundle:Classe', 'c')
                ->where('c.classCategory = ' . $classe->getId())
                ->getQuery();

            $classes = $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        }catch(Exception $ex){

            return json_encode([
                "message" => "Error while pulling informations", 
                "params" => $request, 
                "status" => "failed", 
                "data" => json_decode(json_encode([])),
            ]);
        }

        $response ['message'] = 'Entite InternalAccount';
        $response ['status'] = 'success';
        $response ['data'] = json_decode(json_encode($classes));

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/new_json", name="internalaccount_new_json")
     * @Method({"GET", "POST"})
     */
    function addNewInternalAccountFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');

        $internalAccount = new InternalAccount();

        try{
            //first thing we get the classe with the JSON format
            $internalAccountJSON = json_decode(json_encode($request->request->get('data')), true);


            $internalAccount->setName($internalAccountJSON["name"]);
            $internalAccount->setAccountNumber($internalAccountJSON["accountNumber"]);
            $internalAccount->setAmount($internalAccountJSON["amount"]);
            $internalAccount->setDescription($internalAccountJSON["description"]);
            $internalAccount->setType($internalAccountJSON["type"]);

            $classe = $entityManager->getRepository('ClassBundle:Classe')->find($internalAccountJSON["classe"]);
            $internalAccount->setClasse($classe);


        /**
         * making recordds here
         * --------------------
         */
        
        $entityManager->persist($internalAccount);
        $entityManager->flush();
       
        }catch(Exception $ex){

            $logger("AN ERROR OCCURED");
            $response["success"] = false;
        }

        // $reponse["message"]             = 
        $response["data"]               = $internalAccountJSON;
        $response["optionalData"]       = json_encode((array)$internalAccount->getName());

        //we say everything went well
        $response["success"] = true;

        return new Response(json_encode($response));
    }
}
