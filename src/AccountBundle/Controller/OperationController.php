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
     * new cash in operation.
     *
     * @Route("/cashin", name="new_cash_in_operation")
     * @Method("GET")
     */
    public function cashInOperationAction(){

        $em = $this->getDoctrine()->getManager();
        $members  = $em->getRepository('MemberBundle:Member')->findBy([],['memberNumber' => 'ASC',]);

        return $this->render('operation/cash_in_operation.html.twig', array(
            'members' => $members,
        ));
    }

    /**
     * other cash in operation.
     *
     * @Route("/other/cashin", name="other_cash_in_operations")
     * @Method("GET")
     */
    public function otherCashInOperationAction(){

        $entityManager = $this->getDoctrine()->getManager();
        $members  = $entityManager->getRepository('MemberBundle:Member')->findBy([],['memberNumber' => 'ASC',]);

        $internalAccounts = $entityManager->createQueryBuilder()
            ->select('ia')
            ->from('ClassBundle:InternalAccount', 'ia')
            ->innerJoin('ClassBundle:Classe', 'c', 'WITH','ia.classe = c.id')
            ->where('c.id = 3')
            ->orWhere('c.id = 4')
            ->orWhere('c.id = 5')
            ->orWhere('c.id = 7')
            ->getQuery()
            ->getResult();

        return $this->render('operation/other_cash_in_operation.html.twig', array(
            'members' => $members,
            'internalAccounts' => $internalAccounts,
        ));
    }


    /**
     * other cash in operation.
     *
     * @Route("/cashout/other", name="other_cash_out_operations")
     * @Method("GET")
     */
    public function otherCashOutAction(){

        $entityManager = $this->getDoctrine()->getManager();

        $internalAccounts = $entityManager->createQueryBuilder()
            ->select('ia')
            ->from('ClassBundle:InternalAccount', 'ia')
            ->innerJoin('ClassBundle:Classe', 'c', 'WITH','ia.classe = c.id')
            ->where('c.id = 2')
            ->orWhere('c.id = 3')
            ->orWhere('c.id = 6')
            ->getQuery()
            ->getResult();

        return $this->render('operation/other_cash_out_operation.html.twig', array(
            'internalAccounts' => $internalAccounts,
        ));
    }


    /**
     * other cash in operation.
     *
     * @Route("/cashout", name="cash_out_operations")
     * @Method("GET")
     */
    public function cashOutAction(){

        $entityManager = $this->getDoctrine()->getManager();
        $members  = $entityManager->getRepository('MemberBundle:Member')->findBy([],['memberNumber' => 'ASC',]);

        return $this->render('operation/cash_out_operation.html.twig', array(
            'members' => $members,
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
    public function indexAction(){
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
    public function showAction(Operation $operation){
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
        $html2pdf->pdf->SetTitle('RECEIPT_'.$operation->getTypeOperation());
        $response = new Response();
        $html2pdf->pdf->SetTitle('RECEIPT_'.$operation->getTypeOperation());
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
    function saveCashInOperation(Request $request){
        
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

            $member = $entityManager->getRepository('MemberBundle:Member')->find($accountMemberId);
            if($representative == ""){
                $representative = $member->getName();
            }
            if ($savings != 0) {//Saving Operation Member
                    $operation->setCurrentUser($currentUser);
                    $operation->setTypeOperation(Operation::TYPE_CASH_IN);
                    $operation->setAmount($savings);
                    $operation->setMember($member);
                    $operation->setRepresentative($representative);
                    $operation->setIsSaving(true);
                    $operation->setBalance($member->getSaving() + $savings);
                    $operation->setIsConfirmed(true);

                    $member->setSaving($member->getSaving() + $savings);

                    $totalTransaction += $savings;

                    $memberSavings  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(44);
                    $memberSavings->setCredit($memberSavings->getCredit() + $savings);
                    $memberSavings->setEndingBalance($memberSavings->getCredit() - $memberSavings->getDebit() + $memberSavings->getBeginingBalance());

                    $entityManager->persist($operation);
                    $operations[] = $operation;
            }
            if ($shares != 0) {//Shares operation for member          
                $operationShare = new Operation();
                $operationShare->setCurrentUser($currentUser);
                    $operationShare->setTypeOperation(Operation::TYPE_CASH_IN);
                    $operationShare->setAmount($shares);
                    $operationShare->setIsShare(true);
                    $operationShare->setMember($member);
                    $operationShare->setRepresentative($representative);
                    $operationShare->setBalance($member->getShare() + $shares);
                    $operationShare->setIsConfirmed(true);

                    $member->setShare($member->getShare() + $shares);
                    $totalTransaction += $shares;

                    $memberShares  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(1);
                    $memberShares->setCredit($memberShares->getCredit() + $shares);
                    $memberShares->setEndingBalance($memberShares->getCredit() - $memberShares->getDebit() + $memberShares->getBeginingBalance());

                    $entityManager->persist($operationShare);
                    $operations[] = $operationShare;
            }

            if ($deposits != 0) { //Deposit Operations for Member          
                    $operationDeposit = new Operation();
                    $operationDeposit->setCurrentUser($currentUser);
                    $operationDeposit->setTypeOperation(Operation::TYPE_CASH_IN);
                    $operationDeposit->setAmount($deposits);
                    $operationDeposit->setIsDeposit(true);
                    $operationDeposit->setMember($member);
                    $operationDeposit->setRepresentative($representative);
                    $operationDeposit->setBalance($member->getDeposit() + $deposits);
                    $operationDeposit->setIsConfirmed(true);

                    $member->setDeposit($member->getDeposit() + $deposits);

                    $totalTransaction += $deposits;

                    $memberDeposits  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(42);
                    $memberDeposits->setCredit($memberDeposits->getCredit() + $deposits);
                    $memberDeposits->setEndingBalance($memberDeposits->getCredit() - $memberDeposits->getDebit() + $memberDeposits->getBeginingBalance());

                    $entityManager->persist($operationDeposit);
                    $operations[] = $operationDeposit;
            }

            $loanhistory = new Loanhistory();
            if ($mainLoan != 0 || $loanInterest != 0) {//Loan Repayment for physical member

                $loan = $entityManager->getRepository('AccountBundle:Loan')->findOneBy(['physicalMember' => $member,
                                                                                        'status' => true]);
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
                    $entityManager->persist($loanhistory);
                    $totalTransaction += $mainLoan + $loanInterest;


                    $normalLoan  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(32);
                    $normalLoan->setCredit($normalLoan->getCredit() + $mainLoan);
                    $normalLoan->setEndingBalance($normalLoan->getCredit() - $normalLoan->getDebit() + $normalLoan->getBeginingBalance());

                    $operationNormalLoan = new Operation();
                    $operationNormalLoan->setCurrentUser($currentUser);
                    $operationNormalLoan->setTypeOperation(Operation::TYPE_CASH_IN);
                    $operationNormalLoan->setAmount($mainLoan);
                    $operationNormalLoan->setAccount($normalLoan);
                    $operationNormalLoan->setMember($member);
                    $operationNormalLoan->setRepresentative($representative);
                    $operationNormalLoan->setBalance($normalLoan->getEndingBalance());
                    $operationNormalLoan->setIsConfirmed(true);

                    $entityManager->persist($operationNormalLoan);


                    $LoanInterestAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(136);
                    $LoanInterestAccount->setCredit($LoanInterestAccount->getCredit() + $loanInterest);
                    $LoanInterestAccount->setEndingBalance($LoanInterestAccount->getCredit() - $LoanInterestAccount->getDebit() + $LoanInterestAccount->getBeginingBalance());

                    $operationLoanInterest = new Operation();
                    $operationLoanInterest->setCurrentUser($currentUser);
                    $operationLoanInterest->setTypeOperation(Operation::TYPE_CASH_IN);
                    $operationLoanInterest->setAmount($loanInterest);
                    $operationLoanInterest->setAccount($LoanInterestAccount);
                    $operationLoanInterest->setMember($member);
                    $operationLoanInterest->setRepresentative($representative);
                    $operationLoanInterest->setBalance($LoanInterestAccount->getEndingBalance());
                    $operationLoanInterest->setIsConfirmed(true);

                    $entityManager->persist($operationLoanInterest);

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
            $buildingFees = $request->get('Building');
            $registration = $request->get('Registration');

            if ($charges != 0) {
                $temp['name'] = "Charges";
                $temp['value'] = $charges;
                $others[] = $temp;
                $totalTransaction += $charges;

                $chargesAccount = $entityManager->getRepository('ClassBundle:InternalAccount')->find(141);
                $chargesAccount->setCredit($chargesAccount->getCredit() + $charges);
                $chargesAccount->setEndingBalance($chargesAccount->getCredit() - $chargesAccount->getDebit() + $chargesAccount->getBeginingBalance());

                $operationCharges = new Operation();
                $operationCharges->setCurrentUser($currentUser);
                $operationCharges->setTypeOperation(Operation::TYPE_CASH_IN);
                $operationCharges->setAmount($charges);
                $operationCharges->setAccount($chargesAccount);
                $operationCharges->setMember($member);
                $operationCharges->setRepresentative($representative);
                $operationCharges->setBalance($chargesAccount->getEndingBalance());
                $operationCharges->setIsConfirmed(true);

                $entityManager->persist($operationCharges);
            }
            if ($buildingFees != 0) {
                $temp['name'] = "Building fees";
                $temp['value'] = $buildingFees;
                $others[] = $temp;
                $totalTransaction += $buildingFees;

                $feesAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(6);
                $feesAccount->setCredit($feesAccount->getCredit() + $buildingFees);
                $feesAccount->setEndingBalance($feesAccount->getCredit() - $feesAccount->getDebit() + $feesAccount->getBeginingBalance());

                $operationFees = new Operation();
                $operationFees->setCurrentUser($currentUser);
                $operationFees->setTypeOperation(Operation::TYPE_CASH_IN);
                $operationFees->setAmount($buildingFees);
                $operationFees->setAccount($feesAccount);
                $operationFees->setMember($member);
                $operationFees->setRepresentative($representative);
                $operationFees->setBalance($feesAccount->getEndingBalance());
                $operationFees->setIsConfirmed(true);

                $entityManager->persist($operationFees);

                $member->setBuildingFees($member->getBuildingFees() + $buildingFees);
            }

            if ($registration != 0) {
                $temp['name'] = "Entrance fees";
                $temp['value'] = $registration;
                $others[] = $temp;
                $totalTransaction += $registration;

                $memberEntranceFees  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(151);
                $memberEntranceFees->setCredit($memberEntranceFees->getCredit() + $registration);
                $memberEntranceFees->setEndingBalance($memberEntranceFees->getCredit() - $memberEntranceFees->getDebit() + $memberEntranceFees->getBeginingBalance());

                $operationRegis = new Operation();
                $operationRegis->setCurrentUser($currentUser);
                $operationRegis->setTypeOperation(Operation::TYPE_CASH_IN);
                $operationRegis->setAmount($registration);
                $operationRegis->setAccount($memberEntranceFees);
                $operationRegis->setMember($member);
                $operationRegis->setRepresentative($representative);
                $operationRegis->setBalance($memberEntranceFees->getEndingBalance());
                $operationRegis->setIsConfirmed(true);

                $entityManager->persist($operationRegis);

                $member->setRegistrationFees($member->getRegistrationFees() + $registration);
            }

        }
        // \Doctrine\Common\Util\Debug::dump($member);
        // die();
        $entityManager->flush();
        $html =  $this->renderView('operation/cash_in_receipt_file.html.twig', array(
                'agency' => $agency,
                'member' => $member,
                'loanhistory' => $loanhistory,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'others' => $others,
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'currentDate' => $operation->getDateOperation(),
                'accountOperations' => $operations,
            ));

        $nomMember = str_replace(' ', '_', $member->getName());
        $operationType = str_replace(' ', '_', $operation->getTypeOperation());
        

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(2.5, 2, 2.5, 7));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('RECEIPT_'.$nomMember);
        $response = new Response();
        $html2pdf->pdf->SetTitle('RECEIPT_'.$nomMember);
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Receipt'.$operationType.'_'.$member->getMemberNumber().'.pdf');
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

    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/other/cashin/save", name="other_operation_cash_in_save")
     * @Method({"GET", "POST"})
     */
    function saveOtherCashInOperation(Request $request){
        
        $entityManager = $this->getDoctrine()->getManager();
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->find(1);

        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);

        if ($request->getMethod() == 'POST') {
            if ($request->get('amount') != $request->get('totalAnalytics')) {
                $this->addFlash('warning', 'The total value of the amount is not equal to the value of the cash Analytics');
                return $this->redirectToRoute('other_cash_in_operations');
            }

            $accountId = $request->get('accountNumber');
            $memberId = $request->get('memberNumber');
            $representative = $request->get('representative');
            $amount = $request->get('amount');

            $operations = [];
            $totalTransaction = 0;
            $operation = new Operation();

            $account = $entityManager->getRepository('ClassBundle:InternalAccount')->find($accountId);
            $operation->setCurrentUser($currentUser);
            $operation->setTypeOperation(Operation::TYPE_CASH_IN);
            $operation->setAmount($amount);
            $operation->setAccount($account);
            $operation->setIsConfirmed(true);
            $member = $entityManager->getRepository('MemberBundle:Member')->find($memberId);
            if ($member) {
                $operation->setMember($member);
                $operation->setRepresentative($representative);
            }

            $totalTransaction += $amount;
            $operations[] = $operation;

            $account->setCredit($account->getCredit() + $amount);
            $account->setEndingBalance($account->getCredit() - $account->getDebit() + $account->getBeginingBalance());

            $operation->setBalance($account->getEndingBalance());
            $entityManager->persist($operation);


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
        }
        $entityManager->flush();

        // \Doctrine\Common\Util\Debug::dump($member);
        // die();
        $html =  $this->renderView('operation/other_in_receipt_file.html.twig', array(
                'agency' => $agency,
                'member' => $member,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'currentDate' => $operation->getDateOperation(),
                'accountOperations' => $operations,
            ));

        $operationType = str_replace(' ', '_', $operation->getTypeOperation());
        

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(2.5, 2, 2.5, 7));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('RECEIPT_'.$account->getAccountNumber());
        $response = new Response();
        $html2pdf->pdf->SetTitle('RECEIPT_'.$account->getAccountNumber());
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Receipt'.$operationType.'_'.$account->getAccountNumber().'.pdf');
        return $response;
    }


    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/cashOut/save", name="operation_cash_out_save")
     * @Method({"GET", "POST"})
     */
    function saveCashOutOperation(Request $request){
        
        $entityManager = $this->getDoctrine()->getManager();
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->find(1);

        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);

        if ($request->getMethod() == 'POST') {

            if ($request->get('totalPurposes') != $request->get('totalAnalytics')) {
                $this->addFlash('warning', 'The total value of the purpose is not equal to the value of the cash Analytics');
                return $this->redirectToRoute('cash_out_operations');
            }
            $accountMemberId = $request->get('accountNumber');
            $representative = $request->get('representative');
            $savings = $request->get('savings');
            $shares = $request->get('shares');
            $deposits = $request->get('deposits');
            $operations = [];
            $totalTransaction = 0;
            $operation = new Operation();

            $member = $entityManager->getRepository('MemberBundle:Member')->find($accountMemberId);
            if($representative == ""){
                $representative = $member->getName();
            }
            if ($savings != 0) {//Saving Operation Member
                    $operation->setCurrentUser($currentUser);
                    $operation->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $operation->setAmount($savings);
                    $operation->setMember($member);
                    $operation->setRepresentative($representative);
                    $operation->setIsSaving(true);
                    $operation->setBalance($member->getSaving() - $savings);
                    $operation->setIsConfirmed(true);

                    $entityManager->persist($operation);
                    $member->setSaving($member->getSaving() - $savings);
                    $totalTransaction += $savings;

                    $memberSavings  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(44);
                    $memberSavings->setDebit($memberSavings->getDebit() + $savings);
                    $memberSavings->setEndingBalance($memberSavings->getCredit() - $memberSavings->getDebit() + $memberSavings->getBeginingBalance());

                    $operationSaving = new Operation();
                    $operationSaving->setCurrentUser($currentUser);
                    $operationSaving->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $operationSaving->setAmount($savings);
                    $operationSaving->setMember($member);
                    $operationSaving->setRepresentative($representative);
                    $operationSaving->setAccount($memberSavings);
                    $operationSaving->setBalance($memberSavings->getEndingBalance());
                    $operationSaving->setIsConfirmed(true);


                    $entityManager->persist($operationSaving);
                    $operations[] = $operation;
            }
            if ($shares != 0) {//Shares operation for member          
                    $operationShare = new Operation();
                    $operationShare->setCurrentUser($currentUser);
                    $operationShare->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $operationShare->setAmount($shares);
                    $operationShare->setIsShare(true);
                    $operationShare->setMember($member);
                    $operationShare->setRepresentative($representative);
                    $operationShare->setBalance($member->getShare() - $shares);
                    $operationShare->setIsConfirmed(true);
                    
                    $entityManager->persist($operationShare);

                    $member->setShare($member->getShare() - $shares);
                    $totalTransaction += $shares;

                    $memberShares  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(1);
                    $memberShares->setDebit($memberShares->getDebit() + $shares);
                    $memberShares->setEndingBalance($memberShares->getCredit() - $memberShares->getDebit() + $memberShares->getBeginingBalance());

                    $operationSha = new Operation();
                    $operationSha->setCurrentUser($currentUser);
                    $operationSha->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $operationSha->setAmount($shares);
                    $operationSha->setMember($member);
                    $operationSha->setRepresentative($representative);
                    $operationSha->setAccount($memberShares);
                    $operationSha->setBalance($memberShares->getEndingBalance());
                    $operationSha->setIsConfirmed(true);


                    $entityManager->persist($operationSha);
                    $operations[] = $operationShare;
            }

            if ($deposits != 0) { //Deposit Operations for Member          
                    $operationDeposit = new Operation();
                    $operationDeposit->setCurrentUser($currentUser);
                    $operationDeposit->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $operationDeposit->setAmount($deposits);
                    $operationDeposit->setIsDeposit(true);
                    $operationDeposit->setMember($member);
                    $operationDeposit->setRepresentative($representative);
                    $operationDeposit->setBalance($member->getDeposit() - $deposits);
                    $operationDeposit->setIsConfirmed(true);

                    $entityManager->persist($operationDeposit);
                    $member->setDeposit($member->getDeposit() - $deposits);

                    $totalTransaction += $deposits;

                    $memberDeposits  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(42);
                    $memberDeposits->setDebit($memberDeposits->getDebit() + $deposits);
                    $memberDeposits->setEndingBalance($memberDeposits->getCredit() - $memberDeposits->getDebit() + $memberDeposits->getBeginingBalance());

                    $operationDepo = new Operation();
                    $operationDepo->setCurrentUser($currentUser);
                    $operationDepo->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $operationDepo->setAmount($deposits);
                    $operationDepo->setMember($member);
                    $operationDepo->setRepresentative($representative);
                    $operationDepo->setAccount($memberDeposits);
                    $operationDepo->setBalance($memberDeposits->getEndingBalance());
                    $operationDepo->setIsConfirmed(true);

                    $entityManager->persist($operationDepo);
                    $operations[] = $operationDeposit;
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
        }

        // \Doctrine\Common\Util\Debug::dump($member);
        // die();
        $entityManager->flush();
        $html =  $this->renderView('operation/cash_out_receipt_file.html.twig', array(
                'agency' => $agency,
                'member' => $member,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'others' => $others,
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'currentDate' => $operation->getDateOperation(),
                'accountOperations' => $operations,
            ));

        $nomMember = str_replace(' ', '_', $member->getName());
        

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(2.5, 2, 2.5, 7));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('RECEIPT_CASH_OUT_'.$nomMember);
        $response = new Response();
        $html2pdf->pdf->SetTitle('RECEIPT_CASH_OUT_'.$nomMember);
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Receipt_Cash_Out_'.$member->getMemberNumber().'.pdf');
        return $response;
    }




    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/other/cashout/save", name="other_operation_cash_out_save")
     * @Method({"GET", "POST"})
     */
    function saveOtherCashOutOperation(Request $request){
        
        $entityManager = $this->getDoctrine()->getManager();
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->find(1);

        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);

        if ($request->getMethod() == 'POST') {

            if ($request->get('amount') != $request->get('totalAnalytics')) {
                $this->addFlash('warning', 'The total value of the amount is not equal to the value of the cash Analytics');
                return $this->redirectToRoute('other_cash_out_operations');
            }

            $accountId = $request->get('accountNumber');
            $representative = $request->get('representative');
            // if($representative == ""){
            //     $representative = $member->getName();
            // }
            $amount = $request->get('amount');

            $operations = [];
            $totalTransaction = 0;
            $operation = new Operation();

            $account = $entityManager->getRepository('ClassBundle:InternalAccount')->find($accountId);
            $operation->setCurrentUser($currentUser);
            $operation->setTypeOperation(Operation::TYPE_CASH_OUT);
            $operation->setAmount($amount);
            $operation->setAccount($account);
            $operation->setRepresentative($representative);
            $operation->setIsConfirmed(true);

            $account->setDebit($account->getDebit() + $amount);
            $account->setEndingBalance($account->getCredit() - $account->getDebit() + $account->getBeginingBalance());

            $operation->setBalance($account->getEndingBalance());

            $totalTransaction += $amount;
            $operations[] = $operation;

            $entityManager->persist($account);
            $entityManager->persist($operation);


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
        }
        $entityManager->flush();

        // \Doctrine\Common\Util\Debug::dump($member);
        // die();
        $html =  $this->renderView('operation/other_out_receipt_file.html.twig', array(
                'agency' => $agency,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'currentDate' => $operation->getDateOperation(),
                'accountOperations' => $operations,
            ));

        $operationType = str_replace(' ', '_', $operation->getTypeOperation());
        

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(2.5, 2, 2.5, 7));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('RECEIPT_'.$account->getAccountNumber());
        $response = new Response();
        $html2pdf->pdf->SetTitle('RECEIPT_'.$account->getAccountNumber());
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Receipt'.$operationType.'_'.$account->getAccountNumber().'.pdf');
        return $response;
    }

    public function convertNumberToWord($num = false){

        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }
}
