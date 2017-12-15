<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Operation;
use ConfigBundle\Entity\TransactionIncome;
use AccountBundle\Entity\LoanHistory;
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
     * new cash in operation.
     *
     * @Route("/cashin", name="new_cash_in_operation")
     * @Method("GET")
     */
    public function cashInOperationAction(){

        $em = $this->getDoctrine()->getManager();
        $members  = $em->getRepository('MemberBundle:Member')->findBy([],
            
            ['memberNumber' => 'ASC',]);

        $moralMembers  = $em->getRepository('MemberBundle:MoralMember')->findAll();

        return $this->render('operation/cash_in_operation.html.twig', array(
            'members' => $members,
            'moralMembers' => $moralMembers,
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

        $operations = $em->getRepository('AccountBundle:Operation')->findBy([],[ 'dateOperation' => 'DESC']);

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
     * @Route("/cashIn/save", name="operation_cash_in_save")
     * @Method({"GET", "POST"})
     */
    function saveCashInOperationFromJSON(Request $request){
        
        $entityManager = $this->getDoctrine()->getManager();
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->find(1);

                // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);

        if ($request->getMethod() == 'POST') {

            if ($request->get('totalPurposes') != $request->get('totalAnalytics')) {
                $this->addFlash('warning', 'The total value of the purpose is not equal to the value of the cash Analytics');
                return $this->redirectToRoute('new_cash_in_operation');
            }        

            $accountMemberId = $request->get('accountNumber');
            $representative = $request->get('representative');
            $savings = $request->get('savings');
            $shares = $request->get('shares');
            $deposits = $request->get('deposits');
            $mainLoan = $request->get('mainLoan');
            $loanInterest = $request->get('loanInterest');
            $operations = [];
            $totalTransaction = 0;
            $operation = new Operation();

            $member = $entityManager->getRepository('MemberBundle:Member')->findOneByMemberNumber($accountMemberId);
            if ($member) {
                $testMember = true;
            if ($savings != 0) {//Saving Operation physicalMember
                $accountSaving = $entityManager->getRepository('AccountBundle:Saving')->findOneBy(['physicalMember' => $member]);
                if ($accountSaving) {
                    $operation->setCurrentUser($currentUser);
                    $operation->setTypeOperation(Operation::TYPE_CREDIT);
                    $operation->setAmount($savings);
                    $operation->setSavingAccount($accountSaving);
                    $operation->setCurrentBalance($accountSaving->getSolde() + $savings);
                    $operation->setIsConfirmed(true);

                    $accountSaving->setSolde($accountSaving->getSolde() + $savings);

                    $totalTransaction += $savings;

                    $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                    $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $savings);
                    
                    $entityManager->persist($cashInHandAccount);
                    $entityManager->persist($operation);
                    $entityManager->persist($accountSaving);
                    $operations[] = $operation;
                }
            }
            if ($shares != 0) {//Shares operation physical member
                $accountShare = $entityManager->getRepository('AccountBundle:Share')->findOneBy(['physicalMember' => $member]);
                if ($accountShare) {            
                    $operationShare = new Operation();
                    $operationShare->setCurrentUser($currentUser);
                    $operationShare->setTypeOperation(Operation::TYPE_CREDIT);
                    $operationShare->setAmount($shares);
                    $operationShare->setShareAccount($accountShare);
                    $operationShare->setCurrentBalance($accountShare->getSolde() + $shares);
                    $operationShare->setIsConfirmed(true);

                    $accountShare->setSolde($accountShare->getSolde() + $shares);

                    $totalTransaction += $shares;

                    $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                    $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $shares);
                    
                    $entityManager->persist($cashInHandAccount);
                    $entityManager->persist($operationShare);
                    $entityManager->persist($accountShare);
                    $operations[] = $operationShare;
                }
            }

            if ($deposits != 0) { //Deposit Operations for physical Member
                $accountDeposit = $entityManager->getRepository('AccountBundle:Deposit')->findOneBy(['physicalMember' => $member]);
                if ($accountDeposit) {            
                    $operationDeposit = new Operation();
                    $operationDeposit->setCurrentUser($currentUser);
                    $operationDeposit->setTypeOperation(Operation::TYPE_CREDIT);
                    $operationDeposit->setAmount($deposits);
                    $operationDeposit->setDepositAccount($accountDeposit);
                    $operationDeposit->setCurrentBalance($accountDeposit->getSolde() + $deposits);
                    $operationDeposit->setIsConfirmed(true);

                    $accountDeposit->setSolde($accountDeposit->getSolde() + $deposits);

                    $totalTransaction += $deposits;

                    $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                    $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $deposits);
                    
                    $entityManager->persist($cashInHandAccount);
                    $entityManager->persist($operationDeposit);
                    $entityManager->persist($accountDeposit);
                    $operations[] = $operationDeposit;
                }
            }

            $loanhistory = new Loanhistory();
            if ($mainLoan != 0 || $loanInterest != 0) {//Loan Repayment for physical member

                $loan = $entityManager->getRepository('AccountBundle:Loan')->findOneBy([
                                                                                        'physicalMember' => $member,
                                                                                        'status' => true
                                                                                        ]);
                if ($loan) {
                    $loanhistory->setCurrentUser($currentUser);
                    $loanhistory->setCloseLoan(false);
                    $loanhistory->setMonthlyPayement($mainLoan);
                    $loanhistory->setInterest($loanInterest);
                    
                    $lowest_remain_amount_LoanHistory = $entityManager->createQueryBuilder()
                        ->select('MIN(lh.remainAmount)')
                        ->from('AccountBundle:LoanHistory', 'lh')
                        ->innerJoin('AccountBundle:Loan', 'l', 'WITH','lh.loan = l.id')
                        ->where('l.id = :loan')->setParameter('loan', $loan)
                        ->getQuery()
                        ->getSingleScalarResult();

                    $latestLoanHistory = $entityManager->getRepository('AccountBundle:LoanHistory')->findOneBy([
                            'remainAmount' => $lowest_remain_amount_LoanHistory,
                            'loan' => $loan],
                            ['id' => 'DESC']);


                    if ($latestLoanHistory) {
                        //set the unpaid to recover after in the next payment
                        $loanhistory->setRemainAmount($latestLoanHistory->getRemainAmount() - $mainLoan);

                        $interest = ($latestLoanHistory->getRemainAmount() * $loan->getRate())/100;
                        $dailyInterestPayment = $interest/30;
                        
                        $date = strtotime($latestLoanHistory->getDateOperation()->format('Y-m-d'));
                        $dateNow = time();

                        $interestToPay = $dailyInterestPayment * floor(($dateNow - $date)/(60*60*24));

                        if($interestToPay + $latestLoanHistory->getUnpaidInterest() - $loanInterest < 0){
                            $loanhistory->setUnpaidInterest(0);
                        }else{
                            $loanhistory->setUnpaidInterest($interestToPay + $latestLoanHistory->getUnpaidInterest() - $loanInterest);
                        }
                    }else{

                        $interest = ($loan->getLoanAmount() * $loan->getRate())/100;
                        $dailyInterestPayment = $interest/30;
                        
                        $date = strtotime($loan->getDateLoan()->format('Y-m-d'));
                        $dateNow = time();

                        $interestToPay = $dailyInterestPayment * floor(($dateNow - $date)/(60*60*24));
                        if ($interestToPay - $loanInterest < 0 ) {
                            $loanhistory->setUnpaidInterest(0);
                        }else{
                            $loanhistory->setUnpaidInterest($interestToPay- $loanInterest);
                        }
                        $loanhistory->setRemainAmount($loan->getLoanAmount() - $mainLoan);
                    }
                    $loanhistory->setLoan($loan);

                    // $income  = new TransactionIncome();

                    // $income->setAmount($loanHistoryJSON["interest"]);
                    // $income->setDescription("Loan Interest payment. Loan Code: ".$loan->getLoanCode()." // Amount: ".$loanHistoryJSON["interest"]);


                    //update the cash in hand
                    $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                    $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $loanInterest + $mainLoan);

                    
                    /**
                    *** Making record here
                    **/
                    
                    $entityManager->persist($cashInHandAccount);
                    $entityManager->persist($loanhistory);
                    // $entityManager->persist($income);

                    $entityManager->flush();
                    $totalTransaction += $mainLoan + $loanInterest;
                }
            }
        }else{
            $member = $entityManager->getRepository('MemberBundle:MoralMember')->findOneByMemberNumber($accountMemberId);
            $testMember = false;

            if ($savings != 0) {//Saving Operation for moral member
                $accountSaving = $entityManager->getRepository('AccountBundle:Saving')->findOneBy(['moralMember' => $member]);
                if ($accountSaving) {                   
                    $operation = new Operation();
                    $operation->setCurrentUser($currentUser);
                    $operation->setTypeOperation(Operation::TYPE_CREDIT);
                    $operation->setAmount($savings);
                    $operation->setSavingAccount($accountSaving);
                    $operation->setCurrentBalance($accountSaving->getSolde() + $savings);
                    $operation->setIsConfirmed(true);

                    $accountSaving->setSolde($accountSaving->getSolde() + $savings);

                    $totalTransaction += $savings;

                    $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                    $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $savings);
                    
                    $entityManager->persist($cashInHandAccount);
                    $entityManager->persist($operation);
                    $entityManager->persist($accountSaving);
                    $operations[] = $operation;
                }
            }
            if ($shares != 0) {//Shares operation for moral member
                $accountShare = $entityManager->getRepository('AccountBundle:Share')->findOneBy(['moralMember' => $member]);
                if ($accountShare) {
                    $operationShare = new Operation();
                    $operationShare->setCurrentUser($currentUser);
                    $operationShare->setTypeOperation(Operation::TYPE_CREDIT);
                    $operationShare->setAmount($shares);
                    $operationShare->setShareAccount($accountShare);
                    $operationShare->setCurrentBalance($accountShare->getSolde() + $shares);
                    $operationShare->setIsConfirmed(true);


                    $accountShare->setSolde($accountShare->getSolde() + $shares);

                    $totalTransaction += $shares;

                    $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                    $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $shares);
                    
                    $entityManager->persist($cashInHandAccount);
                    $entityManager->persist($operationShare);
                    $entityManager->persist($accountShare);
                    $operations[] = $operationShare;
                }
            }

            if ($deposits != 0) { //Deposit Operations
                $accountDeposit = $entityManager->getRepository('AccountBundle:Deposit')->findOneBy(['moralMember' => $member]);
                if ($accountDeposit) {            
                    $operationDeposit = new Operation();
                    $operationDeposit->setCurrentUser($currentUser);
                    $operationDeposit->setTypeOperation(Operation::TYPE_CREDIT);
                    $operationDeposit->setAmount($deposits);
                    $operationDeposit->setDepositAccount($accountDeposit);
                    $operationDeposit->setCurrentBalance($accountDeposit->getSolde() + $deposits);
                    $operationDeposit->setIsConfirmed(true);

                    $accountDeposit->setSolde($accountDeposit->getSolde() + $deposits);

                    $totalTransaction += $deposits;

                    $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                    $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $deposits);
                    
                    $entityManager->persist($cashInHandAccount);
                    $entityManager->persist($operationDeposit);
                    $entityManager->persist($accountDeposit);
                    $operations[] = $operationDeposit;
                }
            }

            $loanhistory = new Loanhistory();
            if ($mainLoan != 0 || $loanInterest != 0) {//Loan Repayment for moral member

                $loan = $entityManager->getRepository('AccountBundle:Loan')->findOneBy(['moralMember' => $member,
                                                                                        'status' => true
                                                                                        ]);
                if ($loan) {
                    $loanhistory->setCurrentUser($currentUser);
                    $loanhistory->setCloseLoan(false);
                    $loanhistory->setMonthlyPayement($mainLoan);
                    $loanhistory->setInterest($loanInterest);
                    
                    $lowest_remain_amount_LoanHistory = $entityManager->createQueryBuilder()
                        ->select('MIN(lh.remainAmount)')
                        ->from('AccountBundle:LoanHistory', 'lh')
                        ->innerJoin('AccountBundle:Loan', 'l', 'WITH','lh.loan = l.id')
                        ->where('l.id = :loan')->setParameter('loan', $loan)
                        ->getQuery()
                        ->getSingleScalarResult();

                    $latestLoanHistory = $entityManager->getRepository('AccountBundle:LoanHistory')->findOneBy([
                            'remainAmount' => $lowest_remain_amount_LoanHistory,
                            'loan' => $loan],
                            ['id' => 'DESC']);


                    if ($latestLoanHistory) {
                        //set the unpaid to recover after in the next payment
                        $loanhistory->setRemainAmount($latestLoanHistory->getRemainAmount() - $mainLoan);

                        $interest = ($latestLoanHistory->getRemainAmount() * $loan->getRate())/100;
                        $dailyInterestPayment = $interest/30;
                        
                        $date = strtotime($loanhistory->getDateOperation()->format('Y-m-d'));
                        $dateNow = time();

                        $interestToPay = $dailyInterestPayment * floor(($dateNow - $date)/(60*60*24));

                        if($interestToPay + $latestLoanHistory->getUnpaidInterest() - $loanInterest < 0){
                            $loanhistory->setUnpaidInterest(0);
                        }else{
                            $loanhistory->setUnpaidInterest($interestToPay + $latestLoanHistory->getUnpaidInterest() - $loanInterest);
                        }
                    }else{

                        $interest = ($loan->getLoanAmount() * $loan->getRate())/100;
                        $dailyInterestPayment = $interest/30;
                        
                        $date = strtotime($loan->getDateLoan()->format('Y-m-d'));
                        $dateNow = time();

                        $interestToPay = $dailyInterestPayment * floor(($dateNow - $date)/(60*60*24));
                        if ($interestToPay - $loanInterest < 0 ) {
                            $loanhistory->setUnpaidInterest(0);
                        }else{
                            $loanhistory->setUnpaidInterest($interestToPay- $loanInterest);
                        }
                        $loanhistory->setRemainAmount($loan->getLoanAmount() - $mainLoan);
                    }
                    
                    $loanhistory->setLoan($loan);
                    //update the cash in hand
                    $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                    $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $loanInterest + $mainLoan);

                    
                    /**
                    *** Making record here
                    **/
                    $entityManager->persist($cashInHandAccount);
                    $entityManager->persist($loanhistory);
                    // $entityManager->persist($income);

                    $entityManager->flush();
                    $totalTransaction += $mainLoan + $loanInterest;
                }
            }
        }

            $tenThousands = $request->get('10000');
            $fiveThousands = $request->get('5000');
            $twoThousands = $request->get('2000');
            $oneThousands = $request->get('1000');
            $fiveHundred = $request->get('500');
            $oneHundred = $request->get('100');
            $fifty = $request->get('50');
            $twentyFive = $request->get('25');
            $ten = $request->get('10');
            $five = $request->get('5');
            $one = $request->get('1');

            $analytics = [];

            if ($tenThousands != 0) {
                $temp['name'] = 10000;
                $temp['value'] = $tenThousands;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 10000;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }

            if ($fiveThousands != 0) {
                $temp['name'] = 5000;
                $temp['value'] = $fiveThousands;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 5000;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }

            if ($twoThousands != 0) {
                $temp['name'] = 2000;
                $temp['value'] = $twoThousands;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 2000;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }

            if ($oneThousands != 0) {
                $temp['name'] = 1000;
                $temp['value'] = $oneThousands;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 1000;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }

            if ($fiveHundred != 0) {
                $temp['name'] = 500;
                $temp['value'] = $fiveHundred;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 500;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }

            if ($oneHundred != 0) {
                $temp['name'] = 100;
                $temp['value'] = $oneHundred;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 100;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }

            if ($fifty != 0) {
                $temp['name'] = 50;
                $temp['value'] = $fifty;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 50;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }

            if ($twentyFive != 0) {
                $temp['name'] = 25;
                $temp['value'] = $twentyFive;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 25;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }

            if ($ten != 0) {
                $temp['name'] = 10;
                $temp['value'] = $ten;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 10;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }

            if ($five != 0) {
                $temp['name'] = 5;
                $temp['value'] = $five;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 5;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }

            if ($one != 0) {
                $temp['name'] = 1;
                $temp['value'] = $one;
                $analytics[] = $temp;
            }else{
                $temp['name'] = 1;
                $temp['value'] = 0;
                $analytics[] = $temp;
            }


            $others = [];
            $charges = $request->get('Charges');
            $passBook = $request->get('PassBook');

            if ($charges != 0) {
                $temp['name'] = "Charges";
                $temp['value'] = $charges;
                $others[] = $temp;
                $totalTransaction += $charges;

                $income  = new TransactionIncome();
                $income->setAmount($charges);
                $income->setDescription("Deposit Charges. Amount: ".$charges);

                //update the cash in hand
                $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $charges);
                $entityManager->persist($cashInHandAccount);
                $entityManager->persist($income);
            }
            if ($passBook != 0) {
                $temp['name'] = "PassBook";
                $temp['value'] = $passBook;
                $others[] = $temp;
                $totalTransaction += $passBook;

                $income  = new TransactionIncome();
                $income->setAmount($passBook);
                $income->setDescription("Sale of passBook. Amount: ".$passBook);

                //update the cash in hand
                $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $passBook);
                $entityManager->persist($cashInHandAccount);
                $entityManager->persist($income);
            }

        }

        // \Doctrine\Common\Util\Debug::dump($member);
        // die();
        
        $entityManager->flush();

        $html =  $this->renderView('operation/operations_receipt_file.html.twig', array(
                'agency' => $agency,
                'member' => $member,
                'testMember' => $testMember,
                'loanhistory' => $loanhistory,
                'analytics' => $analytics,
                'others' => $others,
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'currentDate' => $operation->getDateOperation(),
                'accountOperations' => $operations,
            ));

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(2.5, 2, 2.5, 7));
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
