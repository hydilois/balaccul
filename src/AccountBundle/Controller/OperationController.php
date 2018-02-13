<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Operation;
use ConfigBundle\Entity\TransactionIncome;
use AccountBundle\Entity\LoanHistory;
use ReportBundle\Entity\GeneralLedgerBalance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        // Test is the user does not have the default role
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get ('router')->generate ('fos_user_security_login'));
        }

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
        // Test is the user does not have the default role
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get ('router')->generate ('fos_user_security_login'));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $members  = $entityManager->getRepository('MemberBundle:Member')->findBy(['isAproved' => true],['memberNumber' => 'ASC',]);

        $internalAccounts = $entityManager->createQueryBuilder()
            ->select('ia')
            ->from('ClassBundle:InternalAccount', 'ia')
            ->innerJoin('ClassBundle:Classe', 'c', 'WITH','ia.classe = c.id')
            ->where('c.id = 3')
            ->orWhere('c.id = 1')
            ->orWhere('c.id = 2')
            ->orWhere('c.id = 4')
            ->orWhere('c.id = 5')
            ->orWhere('c.id = 6')
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
        // Test is the user does not have the default role
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get ('router')->generate ('fos_user_security_login'));
        }
        $entityManager = $this->getDoctrine()->getManager();
        $internalAccounts = $entityManager->createQueryBuilder()
            ->select('ia')
            ->from('ClassBundle:InternalAccount', 'ia')
            ->innerJoin('ClassBundle:Classe', 'c', 'WITH','ia.classe = c.id')
            ->where('c.id = 1')
            ->orWhere('c.id = 2')
            ->orWhere('c.id = 3')
            ->orWhere('c.id = 4')
            ->orWhere('c.id = 5')
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
        // Test is the user does not have the default role
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get ('router')->generate ('fos_user_security_login'));
        }
        $entityManager = $this->getDoctrine()->getManager();
        $members  = $entityManager->getRepository('MemberBundle:Member')->findBy([],['memberNumber' => 'ASC',]);

        return $this->render('operation/cash_out_operation.html.twig', array(
            'members' => $members,
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
    public function editAction(Request $request, Operation $operation){
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
    public function deleteAction(Request $request, Operation $operation){
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
    private function createDeleteForm(Operation $operation){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('operation_delete', array('id' => $operation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
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
             /*Getting the principal information data from the form*/ 
            $balanceStatus = $request->get('balance-display');
            $accountMemberId = $request->get('accountNumber');
            $representative = $request->get('representative');
            $savings = $request->get('savings');
            $shares = $request->get('shares');
            $deposits = $request->get('deposits');
            $mainLoan = $request->get('mainLoan');
            $loanInterest = $request->get('loanInterest');
            $dateOp = $request->get('dateOperation');
            // Format the date of the operation to be synchronized
            $dateOperation = $this->dateConcatenation($dateOp);
            $operations = [];
            $totalTransaction = 0;


            $member = $entityManager->getRepository('MemberBundle:Member')->find($accountMemberId);
            if($representative == ""){
                $representative = $member->getName();
            }
            if ($savings != 0) {//Saving Operation Member
                /*Hisory for member situation first step */ 
                $operation = new Operation();
                $operation->setCurrentUser($currentUser);
                $operation->setDateOperation($dateOperation);
                $operation->setTypeOperation(Operation::TYPE_CASH_IN);
                $operation->setAmount($savings);
                $operation->setMember($member);
                $operation->setRepresentative($representative);
                $operation->setIsSaving(true);
                $operation->setBalance($member->getSaving() + $savings);

                /**Member situation updated second step **/ 
                $member->setSaving($member->getSaving() + $savings);

                /*Update the member saving account third step*/
                $memberSavings  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(44);
                $memberSavings->setBalance($memberSavings->getBalance() + $savings);

                $classeSav = $entityManager->getRepository('ClassBundle:Classe')->find($memberSavings->getClasse()->getId());
                $classeSav->setBalance($classeSav->getBalance() + $savings);

                    /*First step*/ 
                $ledgerBalance = new GeneralLedgerBalance();
                $ledgerBalance->setDebit($savings);
                $ledgerBalance->setCurrentUser($currentUser);
                $ledgerBalance->setDateOperation($dateOperation);
                $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                        [],
                        ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalance->setBalance($latestEntryGBL->getBalance() + $savings);
                }else{
                    $ledgerBalance->setBalance($savings);
                }
                $ledgerBalance->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalance->setAccount($memberSavings);
                $ledgerBalance->setRepresentative($representative);
                $ledgerBalance->setAccountBalance($memberSavings->getBalance());
                $ledgerBalance->setAccountTitle($memberSavings->getAccountName()." A/C ".$member->getMemberNumber());
                $ledgerBalance->setMember($member);
                    
                    /*Make record*/ 
                $entityManager->persist($operation);
                $totalTransaction += $savings;
                $operations[] = $operation;
                $entityManager->persist($ledgerBalance);
                $entityManager->flush();
            }
            if ($shares != 0) {//Shares operation for member          
                $operationShare = new Operation();
                $operationShare->setCurrentUser($currentUser);
                $operationShare->setDateOperation($dateOperation);
                $operationShare->setTypeOperation(Operation::TYPE_CASH_IN);
                $operationShare->setAmount($shares);
                $operationShare->setIsShare(true);
                $operationShare->setMember($member);
                $operationShare->setRepresentative($representative);
                $operationShare->setBalance($member->getShare() + $shares);

                /*second step*/
                $member->setShare($member->getShare() + $shares);

                /*update the shares account in trialBalance*/ 
                $memberShares  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(1);
                $memberShares->setBalance($memberShares->getBalance() + $shares);
                $classeSha = $entityManager->getRepository('ClassBundle:Classe')->find($memberShares->getClasse()->getId());
                    $classeSha->setBalance($classeSha->getBalance() + $shares);

                $ledgerBalanceSha = new GeneralLedgerBalance();
                $ledgerBalanceSha->setDebit($shares);
                $ledgerBalanceSha->setCurrentUser($currentUser);
                $ledgerBalanceSha->setDateOperation($dateOperation);
                $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                    [],
                    ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalanceSha->setBalance($latestEntryGBL->getBalance() + $shares);
                }else{
                    $ledgerBalanceSha->setBalance($shares);
                }
                $ledgerBalanceSha->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceSha->setAccount($memberShares);
                $ledgerBalanceSha->setRepresentative($representative);
                $ledgerBalanceSha->setAccountBalance($memberShares->getBalance());
                $ledgerBalanceSha->setAccountTitle($memberShares->getAccountName()." A/C ".$member->getMemberNumber());
                $ledgerBalanceSha->setMember($member);

                    /*Make record*/ 
                $entityManager->persist($ledgerBalanceSha);
                $totalTransaction += $shares;
                $entityManager->persist($operationShare);
                $operations[] = $operationShare;
                $entityManager->flush();
            }

            if ($deposits != 0) { //Deposit Operations for Member
                    /*Member sitution on deposit*/ 
                $operationDeposit = new Operation();
                $operationDeposit->setCurrentUser($currentUser);
                $operationDeposit->setDateOperation($dateOperation);
                $operationDeposit->setTypeOperation(Operation::TYPE_CASH_IN);
                $operationDeposit->setAmount($deposits);
                $operationDeposit->setIsDeposit(true);
                $operationDeposit->setMember($member);
                $operationDeposit->setRepresentative($representative);
                $operationDeposit->setBalance($member->getDeposit() + $deposits);

                    //Second Step 
                $member->setDeposit($member->getDeposit() + $deposits);
                    
                    /*third step*/ 
                $memberDeposits  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(42);
                $memberDeposits->setBalance($memberDeposits->getBalance() + $deposits);

                $classeDep = $entityManager->getRepository('ClassBundle:Classe')->find($memberDeposits->getClasse()->getId());
                $classeDep->setBalance($classeDep->getBalance() + $deposits);

                $ledgerBalanceDep = new GeneralLedgerBalance();
                $ledgerBalanceDep->setDebit($deposits);
                $ledgerBalanceDep->setCurrentUser($currentUser);
                $ledgerBalanceDep->setDateOperation($dateOperation);
                $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                        [],
                        ['id' => 'DESC']);
                    if ($latestEntryGBL) {
                        $ledgerBalanceDep->setBalance($latestEntryGBL->getBalance() + $deposits);
                    }else{
                        $ledgerBalanceDep->setBalance($deposits);
                    }
                $ledgerBalanceDep->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceDep->setAccount($memberDeposits);
                $ledgerBalanceDep->setRepresentative($representative);
                $ledgerBalanceDep->setAccountBalance($memberDeposits->getBalance());
                $ledgerBalanceDep->setAccountTitle($memberDeposits->getAccountName()." A/C ".$member->getMemberNumber());
                $ledgerBalanceDep->setMember($member);
                    
                    /*Make record*/ 
                $entityManager->persist($ledgerBalanceDep);
                $entityManager->persist($operationDeposit);
                $totalTransaction += $deposits;
                $operations[] = $operationDeposit;
                $entityManager->flush();
            }

            $loanhistory = new Loanhistory();
            if ($mainLoan != 0 || $loanInterest != 0) {//Loan Repayment for physical member
                $loan = $entityManager->getRepository('AccountBundle:Loan')->findOneBy(['physicalMember' => $member,
                                                                                        'status' => true]);
                if ($loan) {
                    $loanhistory->setCurrentUser($currentUser);
                    $loanhistory->setDateOperation($dateOperation);
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

                        $interestToPay = round($dailyInterestPayment * floor((strtotime($dateOperation->format('Y-m-d')) - $date)/(60*60*24)));
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
                        $interestToPay = round($dailyInterestPayment * floor(($dateNow - $date)/(60*60*24)));
                        if ($interestToPay - $loanInterest < 0 ) {
                            $loanhistory->setUnpaidInterest(0);
                        }else{
                            $loanhistory->setUnpaidInterest($interestToPay- $loanInterest);
                        }
                        $loanhistory->setRemainAmount($loan->getLoanAmount() - $mainLoan);
                    }

                    $loanhistory->setLoan($loan);
                    $loanhistory->setCurrentUser($currentUser);
                    $entityManager->persist($loanhistory);
                    $totalTransaction += $mainLoan + $loanInterest;

                    $normalLoan  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(32);
                    $normalLoan->setBalance($normalLoan->getBalance() + $mainLoan);

                    $classeLoan = $entityManager->getRepository('ClassBundle:Classe')->find($normalLoan->getClasse()->getId());
                    $classeLoan->setBalance($classeLoan->getBalance() + $mainLoan);

                    /*register the loan situation*/ 
                    $operationNormalLoan = new Operation();
                    $operationNormalLoan->setCurrentUser($currentUser);
                    $operationNormalLoan->setDateOperation($dateOperation);
                    $operationNormalLoan->setTypeOperation(Operation::TYPE_CASH_IN);
                    $operationNormalLoan->setAmount($mainLoan);
                    $operationNormalLoan->setMember($member);
                    $operationNormalLoan->setRepresentative($representative);
                    $operationNormalLoan->setBalance($normalLoan->getBalance());
                    $entityManager->persist($operationNormalLoan);

                    if ($loanInterest != 0) {
                        $LoanInterestAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(136);
                        $LoanInterestAccount->setBalance($LoanInterestAccount->getBalance() + $loanInterest);

                        $classeInterest = $entityManager->getRepository('ClassBundle:Classe')->find($LoanInterestAccount->getClasse()->getId());
                        $classeInterest->setBalance($classeInterest->getBalance() + $loanInterest);

                        $operationLoanInterest = new Operation();
                        $operationLoanInterest->setCurrentUser($currentUser);
                        $operationLoanInterest->setDateOperation($dateOperation);
                        $operationLoanInterest->setTypeOperation(Operation::TYPE_CASH_IN);
                        $operationLoanInterest->setAmount($loanInterest);
                        $operationLoanInterest->setMember($member);
                        $operationLoanInterest->setRepresentative($representative);
                        $operationLoanInterest->setBalance($LoanInterestAccount->getBalance());

                        $entityManager->persist($operationLoanInterest);

                        $ledgerBalanceInterest = new GeneralLedgerBalance();
                        $ledgerBalanceInterest->setDebit($loanInterest);
                        $ledgerBalanceInterest->setCurrentUser($currentUser);
                        $ledgerBalanceInterest->setDateOperation($dateOperation);
                        $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                        [],
                        ['id' => 'DESC']);
                        if ($latestEntryGBL) {
                            $ledgerBalanceInterest->setBalance($latestEntryGBL->getBalance() + $loanInterest);
                        }else{
                            $ledgerBalanceInterest->setBalance($loanInterest);
                        }
                        $ledgerBalanceInterest->setTypeOperation(Operation::TYPE_CASH_IN);
                        $ledgerBalanceInterest->setAccount($LoanInterestAccount);
                        $ledgerBalanceInterest->setRepresentative($representative);
                        $ledgerBalanceInterest->setAccountBalance($LoanInterestAccount->getBalance());
                        $ledgerBalanceInterest->setAccountTitle($LoanInterestAccount->getAccountName()." A/C ".$member->getMemberNumber());
                        $ledgerBalanceInterest->setMember($member);
                        /*Make record*/ 
                        $entityManager->persist($ledgerBalanceInterest);
                        $entityManager->flush();
                    }

                    if ($mainLoan != 0) {
                        $ledgerBalanceLoan = new GeneralLedgerBalance();
                        $ledgerBalanceLoan->setDebit($mainLoan);
                        $ledgerBalanceLoan->setCurrentUser($currentUser);
                        $ledgerBalanceLoan->setDateOperation($dateOperation);
                        $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                            [],
                            ['id' => 'DESC']);
                        if ($latestEntryGBL) {
                            $ledgerBalanceLoan->setBalance($latestEntryGBL->getBalance() + $mainLoan);
                        }else{
                            $ledgerBalanceLoan->setBalance($mainLoan);
                        }
                        $ledgerBalanceLoan->setAccount($normalLoan);
                        $ledgerBalanceLoan->setTypeOperation(Operation::TYPE_CASH_IN);
                        $ledgerBalanceLoan->setRepresentative($representative);
                        $ledgerBalanceLoan->setAccountBalance($normalLoan->getBalance());
                        $ledgerBalanceLoan->setAccountTitle($normalLoan->getAccountName()."REPAYMENT A/C ".$member->getMemberNumber());
                        $ledgerBalanceLoan->setMember($member);
                        /*Make record*/ 
                        $entityManager->persist($ledgerBalanceLoan);
                        $entityManager->flush();
                    }
                }
            }

            $analytics = $this->analyticsArray($request->get('10000'), $request->get('5000'), $request->get('2000'),$request->get('1000'), $request->get('500'), $request->get('100'), $request->get('50'), $request->get('25'), $request->get('10'), $request->get('5'), $request->get('1')
                );


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
                $chargesAccount->setBalance($chargesAccount->getBalance() + $charges);

                $classeCharges = $entityManager->getRepository('ClassBundle:Classe')->find($chargesAccount->getClasse()->getId());
                $classeCharges->setBalance($classeCharges->getBalance() + $charges);

                $operationCharges = new Operation();
                $operationCharges->setCurrentUser($currentUser);
                $operationCharges->setDateOperation($dateOperation);
                $operationCharges->setTypeOperation(Operation::TYPE_CASH_IN);
                $operationCharges->setAmount($charges);
                $operationCharges->setMember($member);
                $operationCharges->setRepresentative($representative);
                $operationCharges->setBalance($chargesAccount->getBalance());

                $entityManager->persist($operationCharges);

                $ledgerBalanceCharges = new GeneralLedgerBalance();
                $ledgerBalanceCharges->setDebit($charges);
                $ledgerBalanceCharges->setCurrentUser($currentUser);
                $ledgerBalanceCharges->setDateOperation($dateOperation);
                $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                    [],
                    ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalanceCharges->setBalance($latestEntryGBL->getBalance() + $charges);
                }else{
                    $ledgerBalanceCharges->setBalance($charges);
                }
                $ledgerBalanceCharges->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceCharges->setAccount($chargesAccount);
                $ledgerBalanceCharges->setRepresentative($representative);
                $ledgerBalanceCharges->setAccountBalance($chargesAccount->getBalance());
                $ledgerBalanceCharges->setAccountTitle($chargesAccount->getAccountName()." A/C_".$member->getMemberNumber());
                $ledgerBalanceCharges->setMember($member);
                $entityManager->persist($ledgerBalanceCharges);
                $entityManager->flush();
                
                /*Make record*/ 
            }
            if ($buildingFees != 0) {
                $temp['name'] = "Building fees";
                $temp['value'] = $buildingFees;
                $others[] = $temp;
                $totalTransaction += $buildingFees;

                // update the account in the trial balance
                $feesAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(6);
                $feesAccount->setBalance($feesAccount->getBalance() + $buildingFees);

                $classeFees = $entityManager->getRepository('ClassBundle:Classe')->find($feesAccount->getClasse()->getId());
                $classeFees->setBalance($classeFees->getBalance() + $buildingFees);

                $operationFees = new Operation();
                $operationFees->setCurrentUser($currentUser);
                $operationFees->setDateOperation($dateOperation);
                $operationFees->setTypeOperation(Operation::TYPE_CASH_IN);
                $operationFees->setAmount($buildingFees);
                $operationFees->setMember($member);
                $operationFees->setRepresentative($representative);
                $operationFees->setBalance($feesAccount->getBalance());

                $entityManager->persist($operationFees);

                /*Update member account second step*/
                $member->setBuildingFees($member->getBuildingFees() + $buildingFees);

                /*Update the ledger card first step*/ 
                $ledgerBalanceBuildingFees = new GeneralLedgerBalance();
                $ledgerBalanceBuildingFees->setDebit($buildingFees);
                $ledgerBalanceBuildingFees->setCurrentUser($currentUser);
                $ledgerBalanceBuildingFees->setDateOperation($dateOperation);
                $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                    [],
                    ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalanceBuildingFees->setBalance($latestEntryGBL->getBalance() + $buildingFees);
                }else{
                    $ledgerBalanceBuildingFees->setBalance($buildingFees);
                }
                $ledgerBalanceBuildingFees->setAccount($feesAccount);
                $ledgerBalanceBuildingFees->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceBuildingFees->setRepresentative($representative);
                $ledgerBalanceBuildingFees->setAccountBalance($feesAccount->getBalance());
                $ledgerBalanceBuildingFees->setAccountTitle($feesAccount->getAccountName()." A/C_".$member->getMemberNumber());
                $ledgerBalanceBuildingFees->setMember($member);
                /*Make record*/ 
                $entityManager->persist($ledgerBalanceBuildingFees);
                $entityManager->flush();
            }

            if ($registration != 0) {
                $temp['name'] = "Entrance fees";
                $temp['value'] = $registration;
                $others[] = $temp;
                $totalTransaction += $registration;
                // Third step
                $memberEntranceFees  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(151);
                $memberEntranceFees->setBalance($memberEntranceFees->getBalance() + $registration);

                $classeRegis = $entityManager->getRepository('ClassBundle:Classe')->find($memberEntranceFees->getClasse()->getId());
                $classeRegis->setBalance($classeRegis->getBalance() + $registration);

                $operationRegis = new Operation();
                $operationRegis->setCurrentUser($currentUser);
                $operationRegis->setDateOperation($dateOperation);
                $operationRegis->setTypeOperation(Operation::TYPE_CASH_IN);
                $operationRegis->setAmount($registration);
                $operationRegis->setMember($member);
                $operationRegis->setRepresentative($representative);
                $operationRegis->setBalance($memberEntranceFees->getBalance());

                $entityManager->persist($operationRegis);
                // Second step
                $member->setRegistrationFees($member->getRegistrationFees() + $registration);

                // first Step
                $ledgerBalanceRegistration = new GeneralLedgerBalance();
                $ledgerBalanceRegistration->setDebit($registration);
                $ledgerBalanceRegistration->setCurrentUser($currentUser);
                $ledgerBalanceRegistration->setDateOperation($dateOperation);
                $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                    [],
                    ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalanceRegistration->setBalance($latestEntryGBL->getBalance() + $registration);
                }else{
                    $ledgerBalanceRegistration->setBalance($registration);
                }
                $ledgerBalanceRegistration->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceRegistration->setAccount($memberEntranceFees);
                $ledgerBalanceRegistration->setRepresentative($representative);

                $ledgerBalanceRegistration->setAccountBalance($memberEntranceFees->getBalance());
                $ledgerBalanceRegistration->setAccountTitle($memberEntranceFees->getAccountName()." A/C_".$member->getMemberNumber());
                $ledgerBalanceRegistration->setMember($member);
                /*Make record*/ 
                $entityManager->persist($ledgerBalanceRegistration);
            }

        }
        $entityManager->flush();
        $html =  $this->renderView('operation/cash_in_receipt_file.html.twig', array(
                'agency' => $agency,
                'member' => $member,
                'loanhistory' => $loanhistory,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'others' => $others,
                'balanceStatus' => $balanceStatus,
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'currentDate' => $dateOperation,
                'accountOperations' => $operations,
            ));

        $nomMember = str_replace(' ', '_', $member->getName());
        $operationType = str_replace(' ', '_', Operation::TYPE_CASH_IN);
        

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(2.5, 2.5, 2.5, 10));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('RECEIPT_'.$operationType.'_'.$nomMember);
        $response = new Response();
        $html2pdf->pdf->SetTitle('RECEIPT_'.$operationType.'_'.$nomMember);
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

            $balanceStatus = $request->get('balance-display');
            $accountId = $request->get('accountNumber');
            $memberId = $request->get('memberNumber');
            $representative = $request->get('representative');
            $amount = $request->get('amount');
            $operations = [];
            $totalTransaction = 0;
            $dateOp = $request->get('dateOperation');
            // Format the date of the operation to be synchronized
            $dateOperation = $this->dateConcatenation($dateOp);


            /*Get the account by ID*/ 
            $account = $entityManager->getRepository('ClassBundle:InternalAccount')->find($accountId);
            $account->setBalance($account->getBalance() + $amount);


            $classe = $entityManager->getRepository('ClassBundle:Classe')->find($account->getClasse()->getId());
            $classe->setBalance($classe->getBalance() + $amount);


            // Update the general Ledger
            $ledgerBalanceOther = new GeneralLedgerBalance();
            $ledgerBalanceOther->setDebit($amount);
            $ledgerBalanceOther->setCurrentUser($currentUser);
            $ledgerBalanceOther->setDateOperation($dateOperation);
            $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                [],
                ['id' => 'DESC']);
            if ($latestEntryGBL) {
                $ledgerBalanceOther->setBalance($latestEntryGBL->getBalance() + $amount);
            }else{
                $ledgerBalanceOther->setBalance($amount);
            }
            $ledgerBalanceOther->setTypeOperation(Operation::TYPE_CASH_IN);
            $ledgerBalanceOther->setAccount($account);
            $ledgerBalanceOther->setAccountTitle($account->getAccountName());
            $ledgerBalanceOther->setRepresentative($representative);
            $member = $entityManager->getRepository('MemberBundle:Member')->find($memberId);
            if($member) {
                $ledgerBalanceOther->setMember($member);
            }
            $ledgerBalanceOther->setAccountBalance($account->getBalance());

            /*Make record*/
            $entityManager->persist($ledgerBalanceOther);
            $totalTransaction += $amount;
            $operations[] = $ledgerBalanceOther;            

            $analytics = $this->analyticsArray($request->get('10000'), $request->get('5000'), $request->get('2000'),$request->get('1000'), $request->get('500'), $request->get('100'), $request->get('50'), $request->get('25'), $request->get('10'), $request->get('5'), $request->get('1')
                );
        }

        $entityManager->flush();

        $html =  $this->renderView('operation/other_in_receipt_file.html.twig', array(
                'agency' => $agency,
                'member' => $member,
                'balanceStatus' => $balanceStatus,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'currentDate' => $dateOperation,
                'accountOperations' => $operations,
            ));

        $operationType = str_replace(' ', '_', $ledgerBalanceOther->getTypeOperation());
        $accountName = str_replace(' ', '_', $account->getAccountName());
        

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 15));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('RECEIPT_CASH_IN_'.$account->getAccountName());
        $response = new Response();
        $html2pdf->pdf->SetTitle('RECEIPT_CASH_IN_'.$account->getAccountName());
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Receipt'.$operationType.'_'.$accountName.'.pdf');
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
            $balanceStatus = $request->get('balance-display');
            $savingsCharges = $request->get('savings-charges');

            $accountMemberId = $request->get('accountNumber');
            $representative = $request->get('representative');
            $savings = $request->get('savings');
            $shares = $request->get('shares');
            $deposits = $request->get('deposits');
            $operations = [];
            $totalTransaction = 0;
            
            $dateOp = $request->get('dateOperation');
            // Format the date of the operation to be synchronized
            $dateOperation = $this->dateConcatenation($dateOp);

            $member = $entityManager->getRepository('MemberBundle:Member')->find($accountMemberId);
            
            if($representative == ""){
                $representative = $member->getName();
            }
            if ($savings != 0) {
                /*Member situation updated*/ 
                $operation = new Operation();
                $operation->setTypeOperation(Operation::TYPE_CASH_OUT);
                $operation->setCurrentUser($currentUser);
                $operation->setDateOperation($dateOperation);
                $operation->setAmount($savings);
                $operation->setMember($member);
                $operation->setRepresentative($representative);
                $operation->setIsSaving(true);
                $operation->setBalance($member->getSaving() - $savings);
                    /**Second Step**/
                $member->setSaving($member->getSaving() - $savings);

                    /*Third Step*/ 
                $memberSavingsAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(44);
                $memberSavingsAccount->setBalance($memberSavingsAccount->getBalance() - $savings);

                $classeSav = $entityManager->getRepository('ClassBundle:Classe')->find($memberSavingsAccount->getClasse()->getId());
                $classeSav->setBalance($classeSav->getBalance() - $savings);

                        // first Step
                $ledgerBalanceSavings = new GeneralLedgerBalance();
                $ledgerBalanceSavings->setTypeOperation(Operation::TYPE_CASH_OUT);
                $ledgerBalanceSavings->setCurrentUser($currentUser);
                $ledgerBalanceSavings->setDateOperation($dateOperation);
                $ledgerBalanceSavings->setCredit($savings);
                $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                        [],
                        ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalanceSavings->setBalance($latestEntryGBL->getBalance() - $savings);
                }else{
                    $ledgerBalanceSavings->setBalance($savings);
                }
                $ledgerBalanceSavings->setAccount($memberSavingsAccount);
                $ledgerBalanceSavings->setRepresentative($representative);
                $ledgerBalanceSavings->setAccountBalance($memberSavingsAccount->getBalance());
                $ledgerBalanceSavings->setAccountTitle($memberSavingsAccount->getAccountName()." A/C_".$member->getMemberNumber());
                $ledgerBalanceSavings->setMember($member);
                        
                $entityManager->persist($ledgerBalanceSavings);
                $entityManager->persist($operation);
                $operations[] = $operation;
                $entityManager->flush();
                $totalTransaction += $savings ;

            }

            if ($savingsCharges != 0) {
                /* Records of the savings withdrawals charges**/
                $account = $entityManager->getRepository('ClassBundle:InternalAccount')->find(142);
                $account->setBalance($account->getBalance() + $savingsCharges);
                
                $classeChar = $entityManager->getRepository('ClassBundle:Classe')->find($account->getClasse()->getId());
                $classeChar->setBalance($classeChar->getBalance() + $savingsCharges);
                
                
                // first Step
                $ledgerBalanceOther = new GeneralLedgerBalance();
                $ledgerBalanceOther->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceOther->setDebit($savingsCharges);
                $ledgerBalanceOther->setCurrentUser($currentUser);
                $ledgerBalanceOther->setDateOperation($dateOperation);
                $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                    [],
                    ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalanceOther->setBalance($latestEntryGBL->getBalance() + $savingsCharges);
                }else{
                    $ledgerBalanceOther->setBalance($savingsCharges);
                }
                    
                $ledgerBalanceOther->setAccount($account);
                $ledgerBalanceOther->setRepresentative($representative);
                $ledgerBalanceOther->setAccountBalance($account->getBalance());
                $ledgerBalanceOther->setAccountTitle($account->getAccountName()." A/C_".$member->getMemberNumber());
                $ledgerBalanceOther->setMember($member);
                $entityManager->persist($ledgerBalanceOther);

                $operationCharges2 = new Operation();
                $operationCharges2->setTypeOperation(Operation::TYPE_CASH_IN);
                $operationCharges2->setCurrentUser($currentUser);
                $operationCharges2->setDateOperation($dateOperation);
                $operationCharges2->setAmount($savingsCharges);
                $operationCharges2->setMember($member);
                $operationCharges2->setRepresentative($representative);
                $operationCharges2->setBalance($account->getBalance());
                $entityManager->persist($operationCharges2);
                $entityManager->flush();
                $operations[] = $operationCharges2;

            }

            if ($shares != 0) {//Shares operation for member
                $operationShare = new Operation();
                $operationShare->setCurrentUser($currentUser);
                $operationShare->setDateOperation($dateOperation);
                $operationShare->setTypeOperation(Operation::TYPE_CASH_OUT);
                $operationShare->setAmount($shares);
                $operationShare->setIsShare(true);
                $operationShare->setMember($member);
                $operationShare->setRepresentative($representative);
                $operationShare->setBalance($member->getShare() - $shares);
                    
                    /*Second step*/ 
                $member->setShare($member->getShare() - $shares);

                $memberSharesAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(1);
                $memberSharesAccount->setBalance($memberSharesAccount->getBalance() - $shares);

                $classeShares = $entityManager->getRepository('ClassBundle:Classe')->find($memberSharesAccount->getClasse()->getId());
                $classeShares->setBalance($classeShares->getBalance() - $shares);

                    // first Step
                $ledgerBalanceShares = new GeneralLedgerBalance();
                $ledgerBalanceShares->setTypeOperation(Operation::TYPE_CASH_OUT);
                $ledgerBalanceShares->setCredit($shares);
                $ledgerBalanceShares->setCurrentUser($currentUser);
                $ledgerBalanceShares->setDateOperation($dateOperation);
                $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                    [],
                    ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalanceShares->setBalance($latestEntryGBL->getBalance() - $shares);
                }else{
                    $ledgerBalanceShares->setBalance($shares);
                }
                $ledgerBalanceShares->setAccount($memberSharesAccount);
                $ledgerBalanceShares->setRepresentative($representative);
                $ledgerBalanceShares->setAccountBalance($memberSharesAccount->getBalance());
                $ledgerBalanceShares->setAccountTitle($memberSharesAccount->getAccountName()." A/C_".$member->getMemberNumber());
                $ledgerBalanceShares->setMember($member);
                $entityManager->persist($ledgerBalanceShares);

                $entityManager->persist($operationShare);
                $totalTransaction += $shares;
                $operations[] = $operationShare;
                $entityManager->flush();
            }

            if ($deposits != 0) { //Deposit Operations for Member
                $operationDeposit = new Operation();
                $operationDeposit->setCurrentUser($currentUser);
                $operationDeposit->setDateOperation($dateOperation);
                $operationDeposit->setTypeOperation(Operation::TYPE_CASH_OUT);
                $operationDeposit->setAmount($deposits);
                $operationDeposit->setIsDeposit(true);
                $operationDeposit->setMember($member);
                $operationDeposit->setRepresentative($representative);
                $operationDeposit->setBalance($member->getDeposit() - $deposits);

                $member->setDeposit($member->getDeposit() - $deposits);

                $memberDeposits  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(42);
                $memberDeposits->setBalance($memberDeposits->getBalance() - $deposits);

                $classeDep = $entityManager->getRepository('ClassBundle:Classe')->find($memberDeposits->getClasse()->getId());
                $classeDep->setBalance($classeDep->getBalance() - $deposits);

                    // first Step
                $ledgerBalanceDeposit = new GeneralLedgerBalance();
                $ledgerBalanceDeposit->setCredit($deposits);
                $ledgerBalanceDeposit->setCurrentUser($currentUser);
                $ledgerBalanceDeposit->setDateOperation($dateOperation);
                $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy(
                    [],
                    ['id' => 'DESC']);
                if ($latestEntryGBL) {
                    $ledgerBalanceDeposit->setBalance($latestEntryGBL->getBalance() - $deposits);
                }else{
                    $ledgerBalanceDeposit->setBalance($deposits);
                }
                $ledgerBalanceDeposit->setTypeOperation(Operation::TYPE_CASH_OUT);
                $ledgerBalanceDeposit->setAccount($memberDeposits);
                $ledgerBalanceDeposit->setRepresentative($representative);
                $ledgerBalanceDeposit->setAccountBalance($memberDeposits->getBalance());
                $ledgerBalanceDeposit->setAccountTitle($memberDeposits->getAccountName()." A/C_".$member->getMemberNumber());
                $ledgerBalanceDeposit->setMember($member);
                $entityManager->persist($ledgerBalanceDeposit);
                
                $entityManager->persist($operationDeposit);
                $totalTransaction += $deposits;
                $operations[] = $operationDeposit;
            }

            $analytics = $this->analyticsArray($request->get('10000'), $request->get('5000'), $request->get('2000'),$request->get('1000'), $request->get('500'), $request->get('100'), $request->get('50'), $request->get('25'), $request->get('10'), $request->get('5'), $request->get('1')
                );
            $others = [];
        }
        $entityManager->flush();
        $html =  $this->renderView('operation/cash_out_receipt_file.html.twig', array(
                'agency' => $agency,
                'member' => $member,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'others' => $others,
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'balanceStatus' => $balanceStatus,
                'savingsCharges' => $savingsCharges,
                'currentDate' => $dateOperation,
                'accountOperations' => $operations,
            ));

        $nomMember = str_replace(' ', '_', $member->getName());
        

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(2.5, 2.5, 2.5, 10));
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

            $accountId = $request->get('accountNumber');
            $balanceStatus = $request->get('balance-display');
            $representative = $request->get('representative');
            $amount = $request->get('amount');
            $operations = [];
            $totalTransaction = 0;

            $dateOp = $request->get('dateOperation');
            // Format the date of the operation to be synchronized
            $dateOperation = $this->dateConcatenation($dateOp);

            /*Get the account by ID*/ 
            $account = $entityManager->getRepository('ClassBundle:InternalAccount')->find($accountId);
            $account->setBalance($account->getBalance() - $amount);


            $classe = $entityManager->getRepository('ClassBundle:Classe')->find($account->getClasse()->getId());
            $classe->setBalance($classe->getBalance() - $amount);

            // Update the general Ledger
            $ledgerBalanceOther = new GeneralLedgerBalance();
            $ledgerBalanceOther->setCredit($amount);
            $ledgerBalanceOther->setCurrentUser($currentUser);
            $ledgerBalanceOther->setDateOperation($dateOperation);
            $latestEntryGBL = $entityManager->getRepository('ReportBundle:GeneralLedgerBalance')->findOneBy([],
                ['id' => 'DESC']);

            if ($latestEntryGBL) {
                $ledgerBalanceOther->setBalance($latestEntryGBL->getBalance() - $amount);
            }else{
                $ledgerBalanceOther->setBalance($amount);
            }
            $ledgerBalanceOther->setTypeOperation(Operation::TYPE_CASH_OUT);
            $ledgerBalanceOther->setAccount($account);
            $ledgerBalanceOther->setAccountTitle($account->getAccountName());
            $ledgerBalanceOther->setRepresentative($representative);
            $ledgerBalanceOther->setAccountBalance($account->getBalance());

            /*Make record*/
            $entityManager->persist($ledgerBalanceOther);
            $totalTransaction += $amount;
            $operations[] = $ledgerBalanceOther;



            $analytics = $this->analyticsArray($request->get('10000'), $request->get('5000'), $request->get('2000'),$request->get('1000'), $request->get('500'), $request->get('100'), $request->get('50'), $request->get('25'), $request->get('10'), $request->get('5'), $request->get('1')
                );
        }
        $entityManager->flush();
        
        $html =  $this->renderView('operation/other_out_receipt_file.html.twig', array(
                'agency' => $agency,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'balanceStatus' => $balanceStatus,
                'currentDate' => $dateOperation,
                'accountOperations' => $operations,
            ));

        $operationType = str_replace(' ', '_', $ledgerBalanceOther->getTypeOperation());
        $accountName = str_replace(' ', '_', $account->getAccountName());
        

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 15));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('RECEIPT_CASH_OUT_'.$accountName);
        $response = new Response();
        $html2pdf->pdf->SetTitle('RECEIPT_CASH_OUT_'.$accountName);
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Receipt'.$operationType.'_'.$accountName.'.pdf');
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

    public function analyticsArray($tenThousands, $fiveThousands, $twoThousands, $oneThousands, $fiveHundred, $oneHundred, $fifty, $twentyFive, $ten, $five, $one){

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

        return $analytics;
    }

    public function dateConcatenation($date){
        $dateExplode = explode( "/" , substr($date,strrpos($date," ")));
        $dateStart  = new \DateTime($dateExplode[2]."-".$dateExplode[1]."-".$dateExplode[0]);

        $currentDate = new \DateTime('now');

        $dateConcatenate = new \DateTime($dateStart->format('Y-m-d') .' ' .$currentDate->format('H:m:s'));

        return $dateConcatenate;
    }
}