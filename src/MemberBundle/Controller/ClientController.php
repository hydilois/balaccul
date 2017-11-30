<?php

namespace MemberBundle\Controller;

use MemberBundle\Entity\Client;
use AccountBundle\Entity\DailySavingAccount;
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
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     * @Route("/", name="client_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('MemberBundle:Client')->findAll();

        return $this->render('client/index.html.twig', array(
            'clients' => $clients,
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

        $clients = $em->getRepository('MemberBundle:Client')->findAll();

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
            
            $dailySavingAccount = new DailySavingAccount();
            $dailySavingAccount->setAmount(0);

            $em->persist($dailySavingAccount);
            
            $client->setAmount($dailySavingAccount);

            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('client_index');
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
            $account = $entityManager->getRepository('AccountBundle:DailySavingAccount')->find($client->getAmount()->getId());
            $operation->setClient($client);
            $operation->setDailyAmount($account);



            switch ($accountJSON["operationType"]) {
                case 1://Deposit 
                    $operation->setCurrentBalance($account->getAmount()  + $accountJSON["amount"]);
                    $operation->setTypeOperation(DailyServiceOperation::TYPE_DEPOSIT);
                    $account->setAmount($account->getAmount()  + $accountJSON["amount"]);

                    break;
                case 2://for WithDrawal Operation
                    $operation->setCurrentBalance($account->getAmount()  - $accountJSON["amount"]);
                    $operation->setTypeOperation(DailyServiceOperation::TYPE_WITHDRAWAL);
                    $account->setAmount($account->getAmount()  - $accountJSON["amount"]);
                    break;
                case 3:///Payment of Charges
                    $operation->setCurrentBalance($account->getAmount()  - $accountJSON["amount"]);
                    $operation->setTypeOperation(DailyServiceOperation::TYPE_CHARGES);
                    $account->setAmount($account->getAmount()  - $accountJSON["amount"]);


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
            $entityManager->persist($account);
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
     * @Route("/{id}", name="client_show")
     * @Method("GET")
     */
    public function showAction(Client $client){

        $entityManager = $this->getDoctrine()->getManager();
        $dailyServiceOperation = $entityManager->getRepository('MemberBundle:DailyServiceOperation')->findBy(
            [
                'client' => $client,
            ],
            [
                'dateOperation' => 'DESC',
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
        $deleteForm = $this->createDeleteForm($client);
        $editForm = $this->createForm('MemberBundle\Form\ClientType', $client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_edit', array('id' => $client->getId()));
        }

        return $this->render('client/edit.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
    private function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_delete', array('id' => $client->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
