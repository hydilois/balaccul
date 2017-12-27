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
        // Test is the user does not have the default role
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get ('router')->generate ('fos_user_security_login'));
        }
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
             /*Getting the principal information data from the form*/ 
            $balanceStatus = $request->get('balance-display');
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
                    /*Hisory for member situation first step */ 
                    $operation->setCurrentUser($currentUser);
                    $operation->setTypeOperation(Operation::TYPE_CASH_IN);
                    $operation->setAmount($savings);
                    $operation->setMember($member);
                    $operation->setRepresentative($representative);
                    $operation->setIsSaving(true);
                    $operation->setBalance($member->getSaving() + $savings);
                    $operation->setIsConfirmed(true);

                    /**Member situation updated second step **/ 
                    $member->setSaving($member->getSaving() + $savings);


                    /*Update the member saving account third step*/
                    $memberSavings  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(44);
                    $memberSavings->setCredit($memberSavings->getCredit() + $savings);
                    $memberSavings->setEndingBalance($memberSavings->getCredit() - $memberSavings->getDebit() + $memberSavings->getBeginingBalance());

                    /**Update the cash in  hand fourth step **/ 
                    $cashOnHandAccountSavings  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                    $cashOnHandAccountSavings->setDebit($cashOnHandAccountSavings->getDebit() + $savings);
                    $cashOnHandAccountSavings->setEndingBalance(abs($cashOnHandAccountSavings->getCredit() - $cashOnHandAccountSavings->getDebit() + $cashOnHandAccountSavings->getBeginingBalance()));

                    $entityManager->persist($operation);
                    $totalTransaction += $savings;
                    $operations[] = $operation;

                    /*First step*/ 
                    $ledgerBalance = new GeneralLedgerBalance();
                    $ledgerBalance->setDebit($savings);
                    $ledgerBalance->setCurrentUser($currentUser);
                    $ledgerBalance->setBalance($savings);
                    $ledgerBalance->setTypeOperation(Operation::TYPE_CASH_IN);
                    $ledgerBalance->setAccount($memberSavings);
                    $ledgerBalance->setRepresentative("SAVINGS A/C ".$member->getMemberNumber());
                    /*Make record*/ 
                    $entityManager->persist($ledgerBalance);
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

                /*second step*/
                $member->setShare($member->getShare() + $shares);
                /*update the shares account in trialBalance*/ 
                $memberShares  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(1);
                $memberShares->setCredit($memberShares->getCredit() + $shares);
                $memberShares->setEndingBalance($memberShares->getCredit() - $memberShares->getDebit() + $memberShares->getBeginingBalance());

                $totalTransaction += $shares;
                $entityManager->persist($operationShare);
                $operations[] = $operationShare;

                /**Update the cash in  hand**/ 
                $cashOnHandAccountSha  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                $cashOnHandAccountSha->setDebit($cashOnHandAccountSha->getDebit() + $shares);
                $cashOnHandAccountSha->setEndingBalance(abs($cashOnHandAccountSha->getCredit() - $cashOnHandAccountSha->getDebit() + $cashOnHandAccountSha->getBeginingBalance()));

                $ledgerBalanceSha = new GeneralLedgerBalance();
                $ledgerBalanceSha->setDebit($shares);
                $ledgerBalanceSha->setCurrentUser($currentUser);
                $ledgerBalanceSha->setBalance($shares);
                $ledgerBalanceSha->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceSha->setAccount($memberShares);
                $ledgerBalanceSha->setRepresentative("SHARES A/C ".$member->getMemberNumber());
                    /*Make record*/ 
                $entityManager->persist($ledgerBalanceSha);
            }

            if ($deposits != 0) { //Deposit Operations for Member
                    /*Member sitution on deposit*/ 
                    $operationDeposit = new Operation();
                    $operationDeposit->setCurrentUser($currentUser);
                    $operationDeposit->setTypeOperation(Operation::TYPE_CASH_IN);
                    $operationDeposit->setAmount($deposits);
                    $operationDeposit->setIsDeposit(true);
                    $operationDeposit->setMember($member);
                    $operationDeposit->setRepresentative($representative);
                    $operationDeposit->setBalance($member->getDeposit() + $deposits);
                    $operationDeposit->setIsConfirmed(true);

                    //Second Step 
                    $member->setDeposit($member->getDeposit() + $deposits);
                    
                    /*third step*/ 
                    $memberDeposits  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(42);
                    $memberDeposits->setCredit($memberDeposits->getCredit() + $deposits);
                    $memberDeposits->setEndingBalance($memberDeposits->getCredit() - $memberDeposits->getDebit() + $memberDeposits->getBeginingBalance());

                    $entityManager->persist($operationDeposit);
                    $totalTransaction += $deposits;
                    $operations[] = $operationDeposit;

                    /**Update the cash in  hand**/ 
                    $cashOnHandAccountDep  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                    $cashOnHandAccountDep->setDebit($cashOnHandAccountDep->getDebit() + $deposits);
                    $cashOnHandAccountDep->setEndingBalance(abs($cashOnHandAccountDep->getCredit() - $cashOnHandAccountDep->getDebit() + $cashOnHandAccountDep->getBeginingBalance()));

                    $ledgerBalanceDep = new GeneralLedgerBalance();
                    $ledgerBalanceDep->setDebit($deposits);
                    $ledgerBalanceDep->setCurrentUser($currentUser);
                    $ledgerBalanceDep->setBalance($deposits);
                    $ledgerBalanceDep->setTypeOperation(Operation::TYPE_CASH_IN);
                    $ledgerBalanceDep->setAccount($memberDeposits);
                    $ledgerBalanceDep->setRepresentative("DEPOSIT A/C ".$member->getMemberNumber());
                    /*Make record*/ 
                    $entityManager->persist($ledgerBalanceDep);
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

                        /**Update the cash in  hand**/ 
                    $cashOnHandAccountLoan  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                    $cashOnHandAccountLoan->setDebit($cashOnHandAccountLoan->getDebit() + $mainLoan);
                    $cashOnHandAccountLoan->setEndingBalance(abs($cashOnHandAccountLoan->getCredit() - $cashOnHandAccountLoan->getDebit() + $cashOnHandAccountLoan->getBeginingBalance()));

                    /*register the loan situation*/ 
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
                    if ($loanInterest != 0) {
                        $ledgerBalanceInterest = new GeneralLedgerBalance();
                        $ledgerBalanceInterest->setDebit($loanInterest);
                        $ledgerBalanceInterest->setCurrentUser($currentUser);
                        $ledgerBalanceInterest->setBalance($loanInterest);
                        $ledgerBalanceInterest->setTypeOperation(Operation::TYPE_CASH_IN);
                        $ledgerBalanceInterest->setAccount($LoanInterestAccount);
                        $ledgerBalanceInterest->setRepresentative("LOAN INTEREST ".$loan->getLoanCode());
                        /*Make record*/ 
                        $entityManager->persist($ledgerBalanceInterest);

                        /**Update the cash in  hand**/ 
                    $cashOnHandAccountInterest  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                    $cashOnHandAccountInterest->setDebit($cashOnHandAccountInterest->getDebit() + $loanInterest);
                    $cashOnHandAccountInterest->setEndingBalance(abs($cashOnHandAccountInterest->getCredit() - $cashOnHandAccountInterest->getDebit() + $cashOnHandAccountInterest->getBeginingBalance()));
                    }

                    if ($mainLoan != 0) {
                        $ledgerBalanceLoan = new GeneralLedgerBalance();
                        $ledgerBalanceLoan->setDebit($mainLoan);
                        $ledgerBalanceLoan->setCurrentUser($currentUser);
                        $ledgerBalanceLoan->setBalance($mainLoan);
                        $ledgerBalanceLoan->setAccount($normalLoan);
                        $ledgerBalanceLoan->setTypeOperation(Operation::TYPE_CASH_IN);
                        $ledgerBalanceLoan->setRepresentative("LOAN PAYMENT ".$loan->getLoanCode());
                        /*Make record*/ 
                        $entityManager->persist($ledgerBalanceLoan);
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


                    /**Update the cash in  hand**/ 
                $cashOnHandAccountCharges  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                $cashOnHandAccountCharges->setDebit($cashOnHandAccountCharges->getDebit() + $charges);
                $cashOnHandAccountCharges->setEndingBalance(abs($cashOnHandAccountCharges->getCredit() - $cashOnHandAccountCharges->getDebit() + $cashOnHandAccountCharges->getBeginingBalance()));

                $ledgerBalanceCharges = new GeneralLedgerBalance();
                $ledgerBalanceCharges->setDebit($charges);
                $ledgerBalanceCharges->setCurrentUser($currentUser);
                $ledgerBalanceCharges->setBalance($charges);
                $ledgerBalanceCharges->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceCharges->setAccount($chargesAccount);
                $ledgerBalanceCharges->setRepresentative("DEPOSIT CHARGES A/C ".$member->getMemberNumber());
                /*Make record*/ 
                $entityManager->persist($ledgerBalanceCharges);
            }
            if ($buildingFees != 0) {
                $temp['name'] = "Building fees";
                $temp['value'] = $buildingFees;
                $others[] = $temp;
                $totalTransaction += $buildingFees;

                // update the account in the trial balance
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

                /*Update member account second step*/
                $member->setBuildingFees($member->getBuildingFees() + $buildingFees);

                    /**Update the cash in  hand fourth step**/ 
                $cashOnHandAccountFees  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                $cashOnHandAccountFees->setDebit($cashOnHandAccountFees->getDebit() + $buildingFees);
                $cashOnHandAccountFees->setEndingBalance(abs($cashOnHandAccountFees->getCredit() - $cashOnHandAccountFees->getDebit() + $cashOnHandAccountFees->getBeginingBalance()));

                /*Update the ledger card first step*/ 
                $ledgerBalanceBuildingFees = new GeneralLedgerBalance();
                $ledgerBalanceBuildingFees->setDebit($buildingFees);
                $ledgerBalanceBuildingFees->setCurrentUser($currentUser);
                $ledgerBalanceBuildingFees->setBalance($buildingFees);
                $ledgerBalanceBuildingFees->setAccount($feesAccount);
                $ledgerBalanceBuildingFees->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceBuildingFees->setRepresentative("BUILDING FEES A/C ".$member->getMemberNumber());
                /*Make record*/ 
                $entityManager->persist($ledgerBalanceBuildingFees);
            }

            if ($registration != 0) {
                $temp['name'] = "Entrance fees";
                $temp['value'] = $registration;
                $others[] = $temp;
                $totalTransaction += $registration;
                // Third step
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
                // Second step
                $member->setRegistrationFees($member->getRegistrationFees() + $registration);


                    /**Update the cash in  hand fourth step**/ 
                $cashOnHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                $cashOnHandAccount->setDebit($cashOnHandAccount->getDebit() + $registration);
                $cashOnHandAccount->setEndingBalance(abs($cashOnHandAccount->getCredit() - $cashOnHandAccount->getDebit() + $cashOnHandAccount->getBeginingBalance()));
                // first Step
                $ledgerBalanceRegistration = new GeneralLedgerBalance();
                $ledgerBalanceRegistration->setDebit($registration);
                $ledgerBalanceRegistration->setCurrentUser($currentUser);
                $ledgerBalanceRegistration->setBalance($registration);
                $ledgerBalanceRegistration->setTypeOperation(Operation::TYPE_CASH_IN);
                $ledgerBalanceRegistration->setAccount($memberEntranceFees);
                $ledgerBalanceRegistration->setRepresentative("REGISTRATION FEES A/C ".$member->getMemberNumber());
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
                'currentDate' => $operation->getDateOperation(),
                'accountOperations' => $operations,
            ));

        $nomMember = str_replace(' ', '_', $member->getName());
        $operationType = str_replace(' ', '_', $operation->getTypeOperation());
        

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
            if ($request->get('amount') != $request->get('totalAnalytics')) {
                $this->addFlash('warning', 'The total value of the amount is not equal to the value of the cash Analytics');
                return $this->redirectToRoute('other_cash_in_operations');
            }
            $balanceStatus = $request->get('balance-display');
            $accountId = $request->get('accountNumber');
            $memberId = $request->get('memberNumber');
            $representative = $request->get('representative');
            $amount = $request->get('amount');

            $operations = [];
            $totalTransaction = 0;
            $operation = new Operation();
            /*Second step*/ 
            $account = $entityManager->getRepository('ClassBundle:InternalAccount')->find($accountId);
            $account->setCredit($account->getCredit() + $amount);
            $account->setEndingBalance($account->getCredit() - $account->getDebit() + $account->getBeginingBalance());

            $operation->setCurrentUser($currentUser);
            $operation->setTypeOperation(Operation::TYPE_CASH_IN);
            $operation->setAmount($amount);
            $operation->setAccount($account);
            $operation->setIsConfirmed(true);
            $operation->setBalance($account->getEndingBalance());
            $operation->setRepresentative($representative);

            $member = $entityManager->getRepository('MemberBundle:Member')->find($memberId);
            if($member) {
                $operation->setMember($member);
            }

            $totalTransaction += $amount;
            $operations[] = $operation;
            $entityManager->persist($operation);

                /**Update the cash in  hand  third step**/ 
            $cashOnHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
            $cashOnHandAccount->setDebit($cashOnHandAccount->getDebit() + $amount);
            $cashOnHandAccount->setEndingBalance(abs($cashOnHandAccount->getCredit() - $cashOnHandAccount->getDebit() + $cashOnHandAccount->getBeginingBalance()));
            // first Step
            $ledgerBalanceOther = new GeneralLedgerBalance();
            $ledgerBalanceOther->setDebit($amount);
            $ledgerBalanceOther->setCurrentUser($currentUser);
            $ledgerBalanceOther->setBalance($amount);
            $ledgerBalanceOther->setTypeOperation(Operation::TYPE_CASH_IN);
            $ledgerBalanceOther->setAccount($account);
            $ledgerBalanceOther->setRepresentative($account->getAccountName());
            /*Make record*/
            $entityManager->persist($ledgerBalanceOther);

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

        $html =  $this->renderView('operation/other_in_receipt_file.html.twig', array(
                'agency' => $agency,
                'member' => $member,
                'balanceStatus' => $balanceStatus,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'currentDate' => $operation->getDateOperation(),
                'accountOperations' => $operations,
            ));

        $operationType = str_replace(' ', '_', $operation->getTypeOperation());
        

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(2.5, 2.5, 2.5, 10));
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
            $balanceStatus = $request->get('balance-display');
            $optionCharges = $request->get('payment-option');
            $savingsCharges = $request->get('savings-charges');
            // die("fdjkkfg    ".$optionCharges);
            $accountMemberId = $request->get('accountNumber');
            $representative = $request->get('representative');
            $savings = $request->get('savings');
            $shares = $request->get('shares');
            $deposits = $request->get('deposits');
            $operations = [];
            $totalTransaction = 0;
            $operation = new Operation();
            if ($savingsCharges != 0) {
                die("Enfin");

            }else{

            $member = $entityManager->getRepository('MemberBundle:Member')->find($accountMemberId);
            if($representative == ""){
                $representative = $member->getName();
            }
            if ($savings != 0) {//Saving Operation Member
                /*Member situation*/ 
                $operation->setCurrentUser($currentUser);
                $operation->setTypeOperation(Operation::TYPE_CASH_OUT);
                $operation->setAmount($savings);
                $operation->setMember($member);
                $operation->setRepresentative($representative);
                $operation->setIsSaving(true);
                $operation->setBalance($member->getSaving() - $savings);
                $operation->setIsConfirmed(true);

                    /**Second Step**/
                    $member->setSaving($member->getSaving() - $savings);

                    /*Third Step*/ 
                    $memberSavingsAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(44);
                    $memberSavingsAccount->setDebit($memberSavingsAccount->getDebit() + $savings);
                    $memberSavingsAccount->setEndingBalance(abs($memberSavingsAccount->getCredit() - $memberSavingsAccount->getDebit() + $memberSavingsAccount->getBeginingBalance()));

                        /**Update the cash in  hand  third step fourth step**/ 
                    $cashOnHandAccountSaving  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                    $cashOnHandAccountSaving->setCredit($cashOnHandAccountSaving->getCredit() + $savings);
                    $cashOnHandAccountSaving->setEndingBalance(abs($cashOnHandAccountSaving->getCredit() - $cashOnHandAccountSaving->getDebit() + $cashOnHandAccountSaving->getBeginingBalance()));

                    $operationSaving = new Operation();
                    $operationSaving->setCurrentUser($currentUser);
                    $operationSaving->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $operationSaving->setAmount($savings);
                    $operationSaving->setMember($member);
                    $operationSaving->setRepresentative($representative);
                    $operationSaving->setAccount($memberSavingsAccount);
                    $operationSaving->setBalance($memberSavingsAccount->getEndingBalance());
                    $operationSaving->setIsConfirmed(true);

                    // first Step
                    $ledgerBalanceSavings = new GeneralLedgerBalance();
                    $ledgerBalanceSavings->setCredit($savings);
                    $ledgerBalanceSavings->setCurrentUser($currentUser);
                    $ledgerBalanceSavings->setBalance($savings);
                    $ledgerBalanceSavings->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $ledgerBalanceSavings->setAccount($memberSavingsAccount);
                    $ledgerBalanceSavings->setRepresentative("SAVINGS WITHDRAWAL ".$member->getMemberNumber());
                    
                    /*Make record*/
                    $entityManager->persist($ledgerBalanceSavings);
                    $entityManager->persist($operationSaving);
                    $entityManager->persist($operation);
                    $totalTransaction += $savings;
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
                    
                    /*Second step*/ 
                    $member->setShare($member->getShare() - $shares);

                    $memberSharesAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(1);
                    $memberSharesAccount->setDebit($memberSharesAccount->getDebit() + $shares);
                    $memberSharesAccount->setEndingBalance($memberSharesAccount->getCredit() - $memberSharesAccount->getDebit() + $memberSharesAccount->getBeginingBalance());

                        /**Update the cash in  hand  third step fourth step**/ 
                    $cashOnHandAccountShare  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                    $cashOnHandAccountShare->setCredit($cashOnHandAccountShare->getCredit() + $shares);
                    $cashOnHandAccountShare->setEndingBalance(abs($cashOnHandAccountShare->getCredit() - $cashOnHandAccountShare->getDebit() + $cashOnHandAccountShare->getBeginingBalance()));

                    $operationSha = new Operation();
                    $operationSha->setCurrentUser($currentUser);
                    $operationSha->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $operationSha->setAmount($shares);
                    $operationSha->setMember($member);
                    $operationSha->setRepresentative($representative);
                    $operationSha->setAccount($memberSharesAccount);
                    $operationSha->setBalance($memberSharesAccount->getEndingBalance());
                    $operationSha->setIsConfirmed(true);

                    // first Step
                    $ledgerBalanceSavings = new GeneralLedgerBalance();
                    $ledgerBalanceSavings->setCredit($shares);
                    $ledgerBalanceSavings->setCurrentUser($currentUser);
                    $ledgerBalanceSavings->setBalance($shares);
                    $ledgerBalanceSavings->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $ledgerBalanceSavings->setAccount($memberSharesAccount);
                    $ledgerBalanceSavings->setRepresentative("SHARES WITHDRAWAL ".$member->getMemberNumber());

                    $entityManager->persist($ledgerBalanceSavings);
                    $entityManager->persist($operationShare);
                    $entityManager->persist($operationSha);
                    $totalTransaction += $shares;
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

                    $member->setDeposit($member->getDeposit() - $deposits);


                    $memberDeposits  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(42);
                    $memberDeposits->setDebit($memberDeposits->getDebit() + $deposits);
                    $memberDeposits->setEndingBalance($memberDeposits->getCredit() - $memberDeposits->getDebit() + $memberDeposits->getBeginingBalance());

                        /**Update the cash in  hand  third step fourth step**/ 
                    $cashOnHandAccountDeposit  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
                    $cashOnHandAccountDeposit->setCredit($cashOnHandAccountDeposit->getCredit() + $deposits);
                    $cashOnHandAccountDeposit->setEndingBalance(abs($cashOnHandAccountDeposit->getCredit() - $cashOnHandAccountDeposit->getDebit() + $cashOnHandAccountDeposit->getBeginingBalance()));

                    $operationDepo = new Operation();
                    $operationDepo->setCurrentUser($currentUser);
                    $operationDepo->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $operationDepo->setAmount($deposits);
                    $operationDepo->setMember($member);
                    $operationDepo->setRepresentative($representative);
                    $operationDepo->setAccount($memberDeposits);
                    $operationDepo->setBalance($memberDeposits->getEndingBalance());
                    $operationDepo->setIsConfirmed(true);

                    // first Step
                    $ledgerBalanceDeposit = new GeneralLedgerBalance();
                    $ledgerBalanceDeposit->setCredit($deposits);
                    $ledgerBalanceDeposit->setCurrentUser($currentUser);
                    $ledgerBalanceDeposit->setBalance($deposits);
                    $ledgerBalanceDeposit->setTypeOperation(Operation::TYPE_CASH_OUT);
                    $ledgerBalanceDeposit->setAccount($memberDeposits);
                    $ledgerBalanceDeposit->setRepresentative("DEPOSITS WITHDRAWAL ".$member->getMemberNumber());

                    $entityManager->persist($ledgerBalanceDeposit);
                    $entityManager->persist($operationDeposit);
                    $entityManager->persist($operationDepo);
                    $totalTransaction += $deposits;
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
                'currentDate' => $operation->getDateOperation(),
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
            if ($request->get('amount') != $request->get('totalAnalytics')) {
                $this->addFlash('warning', 'The total value of the amount is not equal to the value of the cash Analytics');
                return $this->redirectToRoute('other_cash_out_operations');
            }

            $accountId = $request->get('accountNumber');
            $balanceStatus = $request->get('balance-display');
            $representative = $request->get('representative');
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

            /*Second Step*/ 
            $account->setDebit($account->getDebit() + $amount);
            $account->setEndingBalance($account->getCredit() - $account->getDebit() + $account->getBeginingBalance());
            $operation->setBalance($account->getEndingBalance());

                /**Update the cash in  hand  third step fourth step**/ 
            $cashOnHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(87);
            $cashOnHandAccount->setCredit($cashOnHandAccount->getCredit() + $amount);
            $cashOnHandAccount->setEndingBalance(abs($cashOnHandAccount->getCredit() - $cashOnHandAccount->getDebit() + $cashOnHandAccount->getBeginingBalance()));

            // first Step
            $ledgerBalanceOther = new GeneralLedgerBalance();
            $ledgerBalanceOther->setCredit($amount);
            $ledgerBalanceOther->setCurrentUser($currentUser);
            $ledgerBalanceOther->setBalance($amount);
            $ledgerBalanceOther->setTypeOperation(Operation::TYPE_CASH_OUT);
            $ledgerBalanceOther->setAccount($account);
            $ledgerBalanceOther->setRepresentative($account->getAccountName());

            $entityManager->persist($ledgerBalanceOther);
            $entityManager->persist($operation);
            $operations[] = $operation;
            $totalTransaction += $amount;


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
        
        $html =  $this->renderView('operation/other_out_receipt_file.html.twig', array(
                'agency' => $agency,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'balanceStatus' => $balanceStatus,
                'currentDate' => $operation->getDateOperation(),
                'accountOperations' => $operations,
            ));

        $operationType = str_replace(' ', '_', $operation->getTypeOperation());
        

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(2.5, 2.5, 2.5, 10));
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
