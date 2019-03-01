<?php

namespace ClassBundle\Controller;

use ClassBundle\Entity\InternalAccount;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Route("/list", name="internalaccount_index")
     * @Method("GET")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $internalAccounts = $em->getRepository('ClassBundle:InternalAccount')->findBy([], ['accountName' => 'ASC']);

        return $this->render('internalaccount/index.html.twig', array(
            'internalAccounts' => $internalAccounts,
        ));
    }


    /**
     * new credit operation.
     *
     * @Route("/cashIn", name="new_cashin_operation")
     * @Method("GET")
     */
    public function cashInOperationAction(){

        $em = $this->getDoctrine()->getManager();
        $internalAccounts = $em->getRepository('ClassBundle:InternalAccount')->findAll();

        return $this->render('internalaccount/cash_in_operation.html.twig', array(
            'internalAccounts' => $internalAccounts,
        ));
    }

    /**
     * new credit operation.
     *
     * @Route("/cashOut", name="new_cashout_operation")
     * @Method("GET")
     */
    public function cashOutOperationAction(){

        $em = $this->getDoctrine()->getManager();
        $internalAccounts = $em->getRepository('ClassBundle:InternalAccount')->findAll();

        return $this->render('internalaccount/cash_out_operation.html.twig', array(
            'internalAccounts' => $internalAccounts,
        ));
    }


    /**
     * Creates a new internalAccount entity.
     *
     * @Route("/new", name="internalaccount_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request)
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
     * @Route("/{id}/show", name="internalaccount_show")
     * @Method("GET")
     * @param InternalAccount $internalAccount
     * @return Response
     */
    public function show(InternalAccount $internalAccount)
    {

        return $this->render('internalaccount/show.html.twig', array(
            'internalAccount' => $internalAccount,
        ));
    }

    /**
     * Displays a form to edit an existing internalAccount entity.
     *
     * @Route("/{id}/edit", name="internalaccount_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param InternalAccount $internalAccount
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function update(Request $request, InternalAccount $internalAccount)
    {
        $editForm = $this->createForm('ClassBundle\Form\InternalAccountEditType', $internalAccount);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('internalaccount_index');
        }

        return $this->render('internalaccount/edit.html.twig', array(
            'internalAccount' => $internalAccount,
            'edit_form' => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Get information related to one subclasse
     * @param  Request $request - the request paramemter HTTP one
     * @return Response           - a json representation of the classe
     * @Route("/subclass/list", name="get_list_subclass")
     */
    public function getListSubClass(Request $request){
        $requestParsed  = json_decode(json_encode($request->request->get("data")));

        $idClass    = $requestParsed->idClasse;

        if($idClass < 1){
            return json_encode([
                "message" => "Error the value given is less than 1", 
                "params" => $request, 
                "status" => "failed", 
                "data" => json_decode(json_encode([])),
            ]);
        }

        $entityManager  = $this->getDoctrine()->getManager();

        try{

            $class = $entityManager->getRepository('ClassBundle:Classe')->find($idClass);
            $query = $entityManager->createQueryBuilder()
                ->select('c')
                ->from('ClassBundle:Classe', 'c')
                ->where('c.classCategory = ' . $class->getId())
                ->getQuery();

            $classes = $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        }catch(\Exception $ex){

            return json_encode([
                "message" => "Error while pulling informations", 
                "params" => $request, 
                "status" => "failed", 
                "data" => json_decode(json_encode([])),
            ]);
        }

        $response ['message'] = 'Entity InternalAccount';
        $response ['status'] = 'success';
        $response ['data'] = json_decode(json_encode($classes));

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request [contains the http request that is passed on]
     *
     * @Route("/new_json", name="internalaccount_new_json")
     * @Method({"GET", "POST"})
     * @return Response
     */
    function addNewInternalAccountFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $internalAccount = new InternalAccount();

        try{
            //first thing we get the class with the JSON format
            $internalAccountJSON = json_decode(json_encode($request->request->get('data')), true);


            $internalAccount->setName($internalAccountJSON["name"]);
            $internalAccount->setAccountNumber($internalAccountJSON["accountNumber"]);
            $internalAccount->setAmount($internalAccountJSON["amount"]);
            $internalAccount->setDescription($internalAccountJSON["description"]);
            $internalAccount->setType($internalAccountJSON["type"]);

            $class = $entityManager->getRepository('ClassBundle:Classe')->find($internalAccountJSON["classe"]);
            $internalAccount->setClasse($class);


        /**
         * making recordds here
         * --------------------
         */
        
        $entityManager->persist($internalAccount);
        $entityManager->flush();
       
        }catch(\Exception $ex){
            $response["success"] = false;
        }

        $response["data"]               = $internalAccountJSON;
        $response["optionalData"]       = json_encode((array)$internalAccount->getName());

        //we say everything went well
        $response["success"] = true;

        return new Response(json_encode($response));
    }

    /**
     * member situation.
     *
     * @Route("/transfert", name="account_tranfer")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return Response
     */
    public function accountTransfer(Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();

        $accounts = $entityManager->getRepository(InternalAccount::class)->findBy([], ['accountNumber' => 'ASC']);

        return $this->render('internalaccount/accounts_transfer.html.twig', array(
            'accounts' => $accounts,
        ));
    }
}