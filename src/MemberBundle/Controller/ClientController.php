<?php

namespace MemberBundle\Controller;

use MemberBundle\Entity\Client;
use ConfigBundle\Entity\TransactionIncome;
use MemberBundle\Entity\DailyServiceOperation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Client controller.
 *
 * @Route("client")
 */
class ClientController extends Controller{
    /**
     * Lists all client entities.
     *
     * @Route("/", name="client_index")
     * @Method("GET")
     */
    public function indexAction(){
        $entityManager = $this->getDoctrine()->getManager();
        $currentUserId      = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUserRoles   =  $this->get('security.token_storage')->getToken()->getUser()->getRoles();

        if(in_array("ROLE_COLLECTOR", $currentUserRoles)){
            // die("test");
            $query  = $entityManager->createQueryBuilder()
                    ->select('c, dso')
                    ->from('MemberBundle:Client', 'c')
                    ->leftJoin('MemberBundle:DailyServiceOperation', 'dso', 'WITH', 'c.id = dso.client')
                    ->where('dso.dateOperation IN
                                (   SELECT MAX(dso2.dateOperation)
                                FROM MemberBundle:DailyServiceOperation dso2
                                WHERE dso2.client = c.id
                                AND dso2.typeOperation = :type 
                                )'
                        )
                    ->andWhere('c.collector = '.$currentUserId)
                    ->setParameter('type', DailyServiceOperation::TYPE_DEPOSIT)
                    ->orderBy('c.name')
                    ->getQuery();
        }else {
            $query  = $entityManager->createQueryBuilder()
                    ->select('c, dso')
                    ->from('MemberBundle:Client', 'c')
                    ->leftJoin('MemberBundle:DailyServiceOperation', 'dso', 'WITH', 'c.id = dso.client')
                    ->andWhere('dso.dateOperation IN
                                (   SELECT MAX(dso2.dateOperation)
                                FROM MemberBundle:DailyServiceOperation dso2
                                WHERE dso2.client = c.id
                                AND dso2.typeOperation = :type 
                                )'
                        )
                    
                    ->setParameter('type', DailyServiceOperation::TYPE_DEPOSIT)
                    ->orderBy('c.name')
                    ->getQuery();
        }

        $clients = $query->getScalarResult();

        $client = new Client();
        $form = $this->createForm('MemberBundle\Form\ClientType', $client);

        return $this->render('client/index.html.twig', array(
            'clients' => $clients,
            'form' => $form->createView(),
        ));
    }


    /**
     * Lists all client entities.
     *
     * @Route("/dailyservice", name="client_daily_service_operations")
     * @Method("GET")
     */
    public function dailyServiceAction(){

        $em = $this->getDoctrine()->getManager();
        $currentUserId      = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $clients = $em->getRepository('MemberBundle:Client')->findBy(['collector' => $currentUserId ]);

        return $this->render('client/daily_service.html.twig', array(
            'clients' => $clients,
        ));
    }

    /**
     * Creates a new client entity.
     *
     * @Route("/new", name="client_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $client = new Client();
        $form = $this->createForm('MemberBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('client_new');
        }

        return $this->render('client/new.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
        ));
    }


    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/operation/save", name="dailyservice_operation_save")
     * @Method({"GET", "POST"})
     */
    function saveDailyServiceOperationFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');


        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);


        try{
            //first thing we get the classe with the JSON format
            $accountJSON = json_decode(json_encode($request->request->get('data')), true);

            $operation = new DailyServiceOperation();
            $operation->setCurrentUser($currentUser);
            $operation->setDateOperation(new \DateTime('now'));
            $operation->setFees($accountJSON["amount"]);
            $client = $entityManager->getRepository('MemberBundle:Client')->find($accountJSON["idClient"]);
            $operation->setClient($client);



            switch ($accountJSON["operationType"]) {
                case 1://Deposit 
                    $operation->setCurrentBalance($client->getBalance()  + $accountJSON["amount"]);
                    $operation->setTypeOperation(DailyServiceOperation::TYPE_DEPOSIT);
                    $client->setBalance($client->getBalance()  + $accountJSON["amount"]);

                    break;
                case 2://for WithDrawal Operation
                    $operation->setCurrentBalance($client->getBalance()  - $accountJSON["amount"]);
                    $operation->setTypeOperation(DailyServiceOperation::TYPE_WITHDRAWAL);
                    $client->setBalance($client->getBalance()  - $accountJSON["amount"]);
                    break;
                case 3:///Payment of Charges
                    $operation->setCurrentBalance($client->getBalance()  - $accountJSON["amount"]);
                    $operation->setTypeOperation(DailyServiceOperation::TYPE_CHARGES);
                    $client->setBalance($client->getBalance()  - $accountJSON["amount"]);


                    $income = new TransactionIncome();

                    $income->setAmount($accountJSON["amount"]);
                    $income->setDescription("Daily Service Charges. Client Name: ".$client->getName()." // Amount: ".$accountJSON['amount']);

                    $entityManager->persist($income);
                    break;
                default:
                    # code...
                    break;
            }

            /**
            *** Making record here
            **/
            // Update the current Account accord to the amount that had been added
            
            $entityManager->persist($operation);
            $entityManager->persist($client);
            $entityManager->flush();


            $response["data"]               = $accountJSON;
            $response["optionalData"]       = json_encode($operation->getId());
            $response["success"] = true;

            return new Response(json_encode($response));
       
        }catch(Exception $ex){

            $logger("AN ERROR OCCURED");
            $response["success"] = false;
            return new Response(json_encode($response));
        }
    }


    /**
     * Finds and displays a client entity.
     *
     * @Route("/{id}/details", name="client_show")
     * @Method("GET")
     */
    public function showAction(Client $client){

        $entityManager = $this->getDoctrine()->getManager();
        $dailyServiceOperation = $entityManager->getRepository('MemberBundle:DailyServiceOperation')->findBy(
            [
                'client' => $client,
            ]
            ); 
        return $this->render('client/show.html.twig', array(
            'client' => $client,
            'dailyServiceOperation' => $dailyServiceOperation,
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @Route("/{id}/edit", name="client_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Client $client)
    {
        // $deleteForm = $this->createDeleteForm($client);
        $editForm = $this->createForm('MemberBundle\Form\ClientType', $client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/edit.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * @Route("/collection/new", name="report_client_money")
     */
    public function reportClientsDepositAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $currentUserId      = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $clients = $em->getRepository('MemberBundle:Client')->findBy(['collector' => $currentUserId ]);

        // replace this example code with whatever you need
        return $this->render('client/client_collection.html.twig', [
            'items' => $clients,
        ]);
    }


    /**
     * @Route("/charges", name="client_charges")
     */
    public function reportClientsChargesAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $currentUserId      = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $clients = $em->getRepository('MemberBundle:Client')->findBy(['collector' => $currentUserId ]);

        // replace this example code with whatever you need
        return $this->render('client/client_charges.html.twig', [
            'items' => $clients,
        ]);
    }

    /**
     * Creates a new reportItem entity.
     *
     * @Route("/deposit/save", name="client_deposit_save")
     * @Method({"GET", "POST"})
     */
    public function clientDepositSaveAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {

            // Get the current user connected
            $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $currentUser    = $em->getRepository('UserBundle:Utilisateur')->find($currentUserId);
            
            $clients = $em->getRepository('MemberBundle:Client')->findBy(['collector' => $currentUserId ]);

            foreach ($clients as $client) {
                    $valeur = $request->get($client->getId());
                    if ($valeur >= 0) {
                    
                    // Save of the history

                    $operation = new DailyServiceOperation();
                    $operation->setCurrentUser($currentUser);
                    $operation->setDateOperation(new \DateTime('now'));
                    $operation->setFees($valeur);
                    $operation->setClient($client);
                    $operation->setTypeOperation(DailyServiceOperation::TYPE_DEPOSIT);
                    $operation->setCurrentBalance($client->getBalance()  + $valeur);

                    $client->setBalance($client->getBalance() + $valeur);

                    $em->persist($client);
                    $em->persist($operation);
                }
            }

            $em->flush();
            return $this->redirectToRoute('client_index');
        }
    }



    /**
     * Creates a new reportItem entity.
     *
     * @Route("/charges/save", name="client_charges_save")
     * @Method({"GET", "POST"})
     */
    public function clientChargesSaveAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        // $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

        if ($request->getMethod() == 'POST') {

            // Get the current user connected
            $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $currentUser    = $em->getRepository('UserBundle:Utilisateur')->find($currentUserId);
            
            $clients = $em->getRepository('MemberBundle:Client')->findBy(['collector' => $currentUserId ]);

            foreach ($clients as $client) {
                    $valeur = $request->get($client->getId());
                    if ($valeur >= 0) {
                    
                    // Save of the history

                    $operation = new DailyServiceOperation();
                    $operation->setCurrentUser($currentUser);
                    $operation->setFees($valeur);
                    $operation->setClient($client);
                    $operation->setTypeOperation(DailyServiceOperation::TYPE_CHARGES);
                    $operation->setCurrentBalance($client->getBalance()  - $valeur);

                    $client->setBalance($client->getBalance() - $valeur);
                    $client->setCharges($valeur);

                    $em->persist($client);
                    $em->persist($operation);
                }
            }

            $em->flush();
            return $this->redirectToRoute('client_index');
        }
    }



    /**
     * Deletes a client entity.
     *
     * @Route("/{id}", name="client_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush();
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * Creates a form to delete a client entity.
     *
     * @param Client $client The client entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_delete', array('id' => $client->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/new_json", name="client_new_json")
     * @Method({"GET", "POST"})
     */
    function addNewClientFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');

        $client = new Client();
        try {
            //first thing we get the mouvement with the JSON format
            $clientJSON = json_decode(json_encode($request->request->get('data')), true);

            $client->setName($clientJSON["clientName"]);
            
            $collector = $entityManager->getRepository('UserBundle:Utilisateur')->find($clientJSON["collectorId"]);
            $client->setCollector($collector);

        /**
         * records here
         * --------------------
         */
        
        $entityManager->persist($client);
        $entityManager->flush();

        $response["data"]               = $clientJSON;
        $response["optionalData"]       = $client->getName();

        //we say everything went well
        $response["status"] = true;
       
        } catch (Exception $ex) {
            $logger("An error has occured");
            $response["status"] = false;
        }

        return new Response(json_encode($response));
    }


    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/withdrawal", name="dailyservice_operation_withdrawal")
     * @Method({"GET", "POST"})
     */
    function saveDailyServiceWithdrawalFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');


        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);

        try{
            //first thing we get the classe with the JSON format
            $accountJSON = json_decode(json_encode($request->request->get('data')), true);

            $operation = new DailyServiceOperation();
            $operation->setCurrentUser($currentUser);
            $operation->setFees($accountJSON["amount"]);
            $client = $entityManager->getRepository('MemberBundle:Client')->find($accountJSON["clientId"]);
            $operation->setClient($client);
            $operation->setCurrentBalance($client->getBalance() + $client->getBalanceBF() - $accountJSON["amount"]);
            $client->setBalance($client->getBalanceBF() + $client->getBalance()  - $accountJSON["amount"]);
            $client->setBalanceBF(0);
            $operation->setTypeOperation(DailyServiceOperation::TYPE_WITHDRAWAL);



            switch ($accountJSON["type"]) {
                case 1://Withdrawal 1
                    $client->setWithdrawal1($accountJSON["amount"]);
                    break;
                case 2://Withdrawal 2
                    $client->setWithdrawal2($accountJSON["amount"]);
                    break;
                default:
                    # code...
                    break;
            }

            /**
            *** Making record here
            **/
            // Update the current Account accord to the amount that had been added
            
            $entityManager->persist($operation);
            $entityManager->persist($client);
            $entityManager->flush();


            $response["data"]               = $accountJSON;
            $response["optionalData"]       = json_encode($operation->getId());
            $response["success"] = true;

            return new Response(json_encode($response));
       
        }catch(Exception $ex){

            $logger("AN ERROR OCCURED");
            $response["success"] = false;
            return new Response(json_encode($response));
        }
    }

    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/refresh", name="dailyservice_operation_refresh")
     * @Method({"GET", "POST"})
     */
    function saveDailyServiceRefreshFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');


        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);
        
        $clients = $entityManager->getRepository('MemberBundle:Client')->findBy(
            ['collector' => $currentUser]
        );

        try{
            foreach ($clients as $client) {
                $client->setBalanceBF($client->getBalanceBF() + $client->getBalance());
                $client->setBalance(0);
                $client->setWithdrawal1(0);
                $client->setWithdrawal2(0);
                $client->setCharges(0);
                $entityManager->persist($client);
            }

            /**
            *** Making record here
            **/
            $entityManager->flush();
            $response["success"] = true;

            return new Response(json_encode($response));
       
        }catch(Exception $ex){
            $logger("AN ERROR OCCURED");
            $response["success"] = false;
            return new Response(json_encode($response));
        }
    }
}
