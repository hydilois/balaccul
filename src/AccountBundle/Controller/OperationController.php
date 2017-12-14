<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Operation;
use ConfigBundle\Entity\TransactionIncome;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Operation controller.
 *
 * @Route("operation")
 */
class OperationController extends Controller{

    /**
     * new credit operation.
     *
     * @Route("/credit", name="new_credit_operation")
     * @Method("GET")
     */
    public function creditOperationAction(){

        $em = $this->getDoctrine()->getManager();

        return $this->render('operation/credit_operation.html.twig', array(
        ));
    }


    /**
     * new debit operation.
     *
     * @Route("/debit", name="new_debit_operation")
     * @Method("GET")
     */
    public function debitOperationAction(){

        $em = $this->getDoctrine()->getManager();

        return $this->render('operation/debit_operation.html.twig', array(
        ));
    }


    /**
     * new debit operation.
     *
     * @Route("/transfer", name="new_transfert_operation")
     * @Method("GET")
     */
    public function transfertOperationAction(){

        $em = $this->getDoctrine()->getManager();

        return $this->render('operation/transfert_operation.html.twig', array(
        ));
    }


    /**
     * Lists all operation entities.
     *
     * @Route("/", name="operation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $operations = $em->getRepository('AccountBundle:Operation')->findBy(
            [],
            [
                'dateOperation' => 'DESC'
            ]
        );

        return $this->render('operation/index.html.twig', array(
            'operations' => $operations,
        ));
    }



    /**
     * Creates a new operation entity.
     *
     * @Route("/new", name="operation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $operation = new Operation();
        $form = $this->createForm('AccountBundle\Form\OperationType', $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($operation);
            $em->flush();

            return $this->redirectToRoute('operation_show', array('id' => $operation->getId()));
        }

        return $this->render('operation/new.html.twig', array(
            'operation' => $operation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a operation entity.
     *
     * @Route("/{id}", name="operation_show")
     * @Method("GET")
     */
    public function showAction(Operation $operation)
    {
        $deleteForm = $this->createDeleteForm($operation);

        return $this->render('operation/show.html.twig', array(
            'operation' => $operation,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Finds and displays a operation entity.
     *
     * @Route("/{id}/receipt", name="operation_receipt")
     * @Method("GET")
     */
    public function operationReceiptAction(Operation $operation){

        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

        $html =  $this->renderView('operation/operation_receipt_file.html.twig', array(
            'agency' => $agency,
            'operation' => $operation,
        ));

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(5, 10, 5, 10));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('Receipt_'.$operation->getTypeOperation());
        $response = new Response();
        $html2pdf->pdf->SetTitle('Receipt_'.$operation->getTypeOperation());
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Receipt'.$operation->getTypeOperation().'.pdf');
        return $response;
    }

    /**
     * Displays a form to edit an existing operation entity.
     *
     * @Route("/{id}/edit", name="operation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Operation $operation)
    {
        $deleteForm = $this->createDeleteForm($operation);
        $editForm = $this->createForm('AccountBundle\Form\OperationType', $operation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('operation_edit', array('id' => $operation->getId()));
        }

        return $this->render('operation/edit.html.twig', array(
            'operation' => $operation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a operation entity.
     *
     * @Route("/{id}", name="operation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Operation $operation)
    {
        $form = $this->createDeleteForm($operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($operation);
            $em->flush();
        }

        return $this->redirectToRoute('operation_index');
    }

    /**
     * Creates a form to delete a operation entity.
     *
     * @param Operation $operation The operation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Operation $operation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('operation_delete', array('id' => $operation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * Get informations related to one account category
     * @param  Request $request - the request paramemter HTTP one
     * @return JSON           - a json representation of the classe
     * @Route("/accounts/list", name="get_account_list")
     */
    public function getlistAccounts(Request $request){
        $requestParsed  = json_decode(json_encode($request->request->get("data")));

        $idCategory    = $requestParsed->idCategory;
        $entityManager  = $this->getDoctrine()->getManager();
        try{
            switch ($idCategory) {
                case 1:
                    $query = $entityManager->createQueryBuilder()
                        ->select('s')
                        ->from('AccountBundle:Saving', 's')
                        ->getQuery();

                    $accounts = $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
                    break;
                case 2:
                    $query = $entityManager->createQueryBuilder()
                        ->select('s')
                        ->from('AccountBundle:Share', 's')
                        ->getQuery();
                    $accounts = $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
                    break;
                case 3:
                    $query = $entityManager->createQueryBuilder()
                        ->select('d')
                        ->from('AccountBundle:Deposit', 'd')
                        ->getQuery();
                    $accounts = $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
                    break;
                default:
                    $emptyData = [];
                    $response ['message'] = 'Entite Accounts';
                    $response ['status'] = 'success';
                    $response ['data'] = json_decode(json_encode($emptyData));
                    return new Response(json_encode($response));
                    break;
            }
        }catch(Exception $ex){

                return json_encode([
                    "message" => "Error while pulling informations", 
                    "params" => $request, 
                    "status" => "failed", 
                    "data" => json_decode(json_encode([])),
                ]);
            }

        $response ['message'] = 'Entite Accounts';
        $response ['status'] = 'success';
        $response ['data'] = json_decode(json_encode($accounts));

        return new Response(json_encode($response));
    }



    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/credit/save", name="operation_save")
     * @Method({"GET", "POST"})
     */
    function saveCreditOperationFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');

        $operation = new Operation();

        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);


        try{
            //first thing we get the classe with the JSON format
            $accountJSON = json_decode(json_encode($request->request->get('data')), true);

            $operation->setCurrentUser($currentUser);
            $operation->setDateOperation(new \DateTime('now'));
            $operation->setTypeOperation(Operation::TYPE_CREDIT);
            $operation->setAmount($accountJSON["amount"]);
            $operation->setDebitFees($accountJSON["fees"]);

            switch ($accountJSON["accountCategory"]) {
                case 1://Saving Account
                    $account = $entityManager->getRepository('AccountBundle:Saving')->find($accountJSON["idAccount"]);
                    $operation->setSavingAccount($account);
                    $operation->setCurrentBalance($account->getSolde()  + $accountJSON["amount"]);

                    break;
                case 2://Share Account
                    $account = $entityManager->getRepository('AccountBundle:Share')->find($accountJSON["idAccount"]);
                    $operation->setShareAccount($account);
                    $operation->setCurrentBalance($account->getSolde()  + $accountJSON["amount"]);

                    break;
                case 3:
                    $account = $entityManager->getRepository('AccountBundle:Deposit')->find($accountJSON["idAccount"]);
                    $operation->setDepositAccount($account);
                    $operation->setCurrentBalance($account->getSolde()  + $accountJSON["amount"]);

                    break;
                default:
                    break;
            }

            /**
            *** Making record here
            **/
            
            $entityManager->persist($operation);
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
     * @Route("/debit/save", name="debit_operation_save")
     * @Method({"GET", "POST"})
     */
    function saveDebitOperationFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');

        $operation = new Operation();

        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);


        try{
            //first thing we get the classe with the JSON format
            $accountJSON = json_decode(json_encode($request->request->get('data')), true);

            $operation->setCurrentUser($currentUser);
            $operation->setDateOperation(new \DateTime('now'));
            $operation->setTypeOperation(Operation::TYPE_DEBIT);
            $operation->setAmount($accountJSON["amount"]);
            $operation->setDebitFees($accountJSON["fees"]);


            switch ($accountJSON["accountCategory"]) {
                case 1: //Saving Account
                    $account = $entityManager->getRepository('AccountBundle:Saving')->find($accountJSON["idAccount"]);
                    $operation->setSavingAccount($account);
                    $operation->setCurrentBalance($account->getSolde()  - $accountJSON["amount"]-$accountJSON["fees"]);

                    break;
                case 2:
                    $account = $entityManager->getRepository('AccountBundle:Share')->find($accountJSON["idAccount"]);
                    $operation->setShareAccount($account);
                    $operation->setCurrentBalance($account->getSolde()  - $accountJSON["amount"]-$accountJSON["fees"]);

                    // //update the internal account
                    // $internalAccount = $entityManager->getRepository('ClassBundle:InternalAccount')->find($account->getNternalAccount()->getId());
                    // $internalAccount->setAmount($internalAccount->getAmount()  - $accountJSON["amount"]-$accountJSON["fees"]);

                    // // Make records

                    // $entityManager->persist($internalAccount);
                    // $entityManager->flush();
                    
                    // //Update the classe account
                    // $classe = $entityManager->getRepository('ClassBundle:Classe')->find($internalAccount->getClasse()->getId());
                    // $classe->setTotalAmount($classe->getTotalAmount() - $accountJSON['amount']-$accountJSON["fees"]);

                    // $entityManager->persist($classe);
                    // $entityManager->flush();


                    // //Update the first level classe account
                    // $motherClass = $entityManager->getRepository('ClassBundle:Classe')->find($classe->getClassCategory()->getId());
                    // $motherClass->setTotalAmount($motherClass->getTotalAmount() - $accountJSON['amount']-$accountJSON["fees"]);

                    // $entityManager->persist($motherClass);
                    // $entityManager->flush();
                    break;
                case 3:
                    $account = $entityManager->getRepository('AccountBundle:Deposit')->find($accountJSON["idAccount"]);
                    $operation->setDepositAccount($account);
                    $operation->setCurrentBalance($account->getSolde()  - $accountJSON["amount"] - $accountJSON["fees"]);
                    break;
                default:
                    # code...
                    break;
            }



            // Update the current Account accord to the amount that had been added
            // $account->setSolde($account->getSolde() - $accountJSON["amount"] - $accountJSON["fees"]);

            // $income = new TransactionIncome();

            // $income->setAmount($accountJSON["fees"]);
            // $income->setDescription("Debit fees. Account Number: ".$account->getAccountNumber()." // Amount: ".$accountJSON['fees']);

            /**
            *** Making record here
            **/
            //update the cash in hand
            // $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
            // $cashInHandAccount->setAmount($cashInHandAccount->getAmount() - $accountJSON["amount"] + $accountJSON["fees"]);

            // $entityManager->persist($cashInHandAccount);

            // $entityManager->persist($account);
            $entityManager->persist($operation);
            // $entityManager->persist($income);
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
     * @Route("/transfert/save", name="transfert_operation_save")
     * @Method({"GET", "POST"})
     */
    function saveTransfertOperationFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');

        $operation = new Operation();

        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);


        try{
            //first thing we get the classe with the JSON format
            $accountJSON = json_decode(json_encode($request->request->get('data')), true);

            $operation->setCurrentUser($currentUser);
            $operation->setDateOperation(new \DateTime('now'));
            $operation->setTypeOperation(Operation::TYPE_TRANSFER);
            $operation->setAmount($accountJSON["amount"]);
            $operation->setTransferFees($accountJSON["fees"]);


            switch ($accountJSON["accountCategory"]) { //update the departure account 
                case 1:
                    $account = $entityManager->getRepository('AccountBundle:Saving')->find($accountJSON["idAccount"]);
                    $operation->setSavingAccount($account);
                    $operation->setCurrentBalance($account->getSolde()  - $accountJSON["amount"]);

                    break;
                case 2:
                    $account = $entityManager->getRepository('AccountBundle:Share')->find($accountJSON["idAccount"]);
                    $operation->setShareAccount($account);
                    $operation->setCurrentBalance($account->getSolde()  - $accountJSON["amount"]);

                    //update the internal account
                    $internalAccount = $entityManager->getRepository('ClassBundle:InternalAccount')->find($account->getNternalAccount()->getId());
                    $internalAccount->setAmount($internalAccount->getAmount()  - $accountJSON["amount"]);

                    // Make records

                    $entityManager->persist($internalAccount);
                    $entityManager->flush();
                    
                    //Update the classe account
                    $classe = $entityManager->getRepository('ClassBundle:Classe')->find($internalAccount->getClasse()->getId());
                    $classe->setTotalAmount($classe->getTotalAmount() - $accountJSON['amount']);

                    $entityManager->persist($classe);
                    $entityManager->flush();


                    //Update the first level classe account
                    $motherClass = $entityManager->getRepository('ClassBundle:Classe')->find($classe->getClassCategory()->getId());
                    $motherClass->setTotalAmount($motherClass->getTotalAmount() - $accountJSON['amount']);

                    $entityManager->persist($motherClass);
                    $entityManager->flush();

                    break;
                case 3:
                    $account = $entityManager->getRepository('AccountBundle:Deposit')->find($accountJSON["idAccount"]);
                    $operation->setDepositAccount($account);

                    $operation->setCurrentBalance($account->getSolde()  - $accountJSON["amount"]);

                    break;
                default:
                    # code...
                    break;
            }


            switch ($accountJSON["desAccountCategory"]) {
                case 1:
                    $accountDest = $entityManager->getRepository('AccountBundle:Saving')->find($accountJSON["idDestAccount"]);
                    $operation->setReceiveSavingAccount($accountDest);
                    $operation->setReceiveAccountcurrentBalance($accountDest->getSolde()  + $accountJSON["amount"]);

                    break;
                case 2:
                    $accountDest = $entityManager->getRepository('AccountBundle:Share')->find($accountJSON["idDestAccount"]);
                    $operation->setReceiveShareAccount($accountDest);
                    $operation->setReceiveAccountcurrentBalance($accountDest->getSolde()  + $accountJSON["amount"] - $accountJSON['fees']);

                    //update the internal account
                    $internalAccount = $entityManager->getRepository('ClassBundle:InternalAccount')->find($accountDest->getNternalAccount()->getId());
                    $internalAccount->setAmount($internalAccount->getAmount()  + $accountJSON["amount"] - $accountJSON['fees']);

                    // Make records

                    $entityManager->persist($internalAccount);
                    $entityManager->flush();
                    
                    //Update the classe account
                    $classe = $entityManager->getRepository('ClassBundle:Classe')->find($internalAccount->getClasse()->getId());
                    $classe->setTotalAmount($classe->getTotalAmount() + $accountJSON['amount'] - $accountJSON['fees']);

                    $entityManager->persist($classe);
                    $entityManager->flush();


                    //Update the first level classe account
                    $motherClass = $entityManager->getRepository('ClassBundle:Classe')->find($classe->getClassCategory()->getId());
                    $motherClass->setTotalAmount($motherClass->getTotalAmount() + $accountJSON['amount'] - $accountJSON['fees']);

                    $entityManager->persist($motherClass);
                    $entityManager->flush();

                    break;
                case 3:
                    $accountDest = $entityManager->getRepository('AccountBundle:Deposit')->find($accountJSON["idDestAccount"]);
                    $operation->setReceiveDepositAccount($accountDest);

                    $operation->setReceiveAccountcurrentBalance($accountDest->getSolde()  + $accountJSON["amount"] - $accountJSON['fees']);

                    break;
                default:
                    # code...
                    break;
            }

             // Update the departure Account accord to the amount that had been retrieve
            $account->setSolde($account->getSolde() - $accountJSON["amount"]);
            $entityManager->persist($account);
            // $entityManager->flush();

            // Update the current Account accord to the amount that had been added
            $accountDest->setSolde($accountDest->getSolde() + $accountJSON["amount"] - $accountJSON['fees']);
            $entityManager->persist($accountDest);

            $entityManager->flush();

            $income = new TransactionIncome();

            $income->setAmount($accountJSON["fees"]);
            $income->setDescription("Transfer fees. Departure Account Number: ".$account->getAccountNumber()." // Destination Account Number: ".$accountDest->getAccountNumber()." // Amount: ".$accountJSON['fees']);


            /**
            *** Making record here
            **/
            
            $entityManager->persist($income);
            $entityManager->persist($operation);
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
}
