<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\Loan;
use AccountBundle\Entity\Operation;
use ClassBundle\Entity\Classe;
use ClassBundle\Entity\InternalAccount;
use ConfigBundle\Entity\Agency;
use AccountBundle\Entity\LoanHistory;
use MemberBundle\Entity\Member;
use ReportBundle\Entity\GeneralLedgerBalance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use UserBundle\Entity\Utilisateur;

/**
 * Operation controller.
 *
 * @Route("operation")
 */
class OperationController extends Controller
{
    /**
     * new cash in operation.
     *
     * @Route("/cash_in", name="new_cash_in_operation")
     * @Method("GET")
     */
    public function cashInOperationAction()
    {
        // Test is the user does not have the default role
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get ('router')->generate ('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $members  = $em->getRepository(Member::class)->findBy([],['memberNumber' => 'ASC',]);

        return $this->render('operation/cash_in_operation.html.twig', [
            'members' => $members,
        ]);
    }

    /**
     * other cash in operation.
     *
     * @Route("/other/cash_in", name="other_cash_in_operations")
     * @Method("GET")
     */
    public function otherCashInOperationAction()
    {
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
     * @Route("/cash_out/other", name="other_cash_out_operations")
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
     * @Route("/cash_out", name="cash_out_operations")
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
     * @param Operation $operation
     * @return Response
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
     * @param Operation $operation
     * @return Response
     */
    public function operationReceiptAction(Operation $operation) {

        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

        $template =  $this->renderView('operation/operation_receipt_file.html.twig', [
            'agency' => $agency,
            'operation' => $operation,
        ]);

        $title = 'Receipt_'.$operation->getTypeOperation().'_'.$operation->getDateOperation()->format('d-m-Y');
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
        if ($operation){
            return $html2PdfService->generatePdf($template, $title.'.pdf', 'operations',$title, 'FI');
        }
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'operations',$title, 'I');
    }




    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/cash_in/save", name="operation_cash_in_save")
     * @Method({"GET", "POST"})
     * @return Response
     */
    function saveCashInOperation(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $agency = $entityManager->getRepository(Agency::class)->findOneBy([],['id' => 'ASC']);
        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository(Utilisateur::class)->find($currentUserId);
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

            $member = $entityManager->getRepository(Member::class)->find($accountMemberId);
            if ($representative == "") {
                $representative = $member->getName();
            }

            if ($mainLoan != 0 || $loanInterest != 0) { //Loan Repayment for physical member
                $loan_history = new Loanhistory();
                $loan = $entityManager->getRepository(Loan::class)->findOneBy(['physicalMember' => $member, 'status' => true]);
                if ($loan) {
                    $loan_history->setCurrentUser($currentUser);
                    $loan_history->setDateOperation($dateOperation);
                    $loan_history->setCloseLoan(false);
                    $loan_history->setMonthlyPayement($mainLoan);
                    $loan_history->setInterest($loanInterest);

                    $lowest_remain_amount_LoanHistory = $entityManager->createQueryBuilder()
                        ->select('MIN(lh.remainAmount)')
                        ->from('AccountBundle:LoanHistory', 'lh')
                        ->innerJoin('AccountBundle:Loan', 'l', 'WITH', 'lh.loan = l.id')
                        ->where('l.id = :loan')->setParameter('loan', $loan)
                        ->getQuery()
                        ->getSingleScalarResult();

                    if ($lowest_remain_amount_LoanHistory && $mainLoan && $lowest_remain_amount_LoanHistory < $mainLoan ){
                        return $this->render('Exception/loan_amount_warning.html.twig');
                    }

                    $latestLoanHistory = $entityManager->getRepository('AccountBundle:LoanHistory')->findOneBy([
                        'remainAmount' => $lowest_remain_amount_LoanHistory,
                        'loan' => $loan],
                        ['id' => 'DESC']);

                    if ($latestLoanHistory) {
                        //set the unpaid to recover after in the next payment
                        $loan_history->setRemainAmount($latestLoanHistory->getRemainAmount() - $mainLoan);
                        $interest = ($latestLoanHistory->getRemainAmount() * $loan->getRate()) / 100;
                        $dailyInterestPayment = $interest / 30;
                        $date = strtotime($latestLoanHistory->getDateOperation()->format('Y-m-d'));

                        $interestToPay = round($dailyInterestPayment * floor((strtotime($dateOperation->format('Y-m-d')) - $date) / (60 * 60 * 24)));
                        if ($interestToPay + $latestLoanHistory->getUnpaidInterest() - $loanInterest < 0) {
                            return $this->render('Exception/loan_interest_warning.html.twig');
                        } else {
                            $loan_history->setUnpaidInterest($interestToPay + $latestLoanHistory->getUnpaidInterest() - $loanInterest);
                        }
                    } else {
                        $interest = ($loan->getLoanAmount() * $loan->getRate()) / 100;
                        $dailyInterestPayment = $interest / 30;
                        $date = strtotime($loan->getDateLoan()->format('Y-m-d'));
                        $dateNow = time();
                        $interestToPay = round($dailyInterestPayment * floor(($dateNow - $date) / (60 * 60 * 24)));
                        if ($interestToPay - $loanInterest < 0) {
                            return $this->render('Exception/loan_interest_warning.html.twig');
                        } else {
                            $loan_history->setUnpaidInterest($interestToPay - $loanInterest);
                        }
                        $loan_history->setRemainAmount($loan->getLoanAmount() - $mainLoan);
                    }

                    $loan_history->setLoan($loan);
                    $loan_history->setCurrentUser($currentUser);
                    $entityManager->persist($loan_history);
                    $totalTransaction += $mainLoan + $loanInterest;

                    $normalLoan = $entityManager->getRepository(InternalAccount::class)->find(32);
                    $normalLoan->setBalance($normalLoan->getBalance() + $mainLoan);

                    $classLoan = $entityManager->getRepository(Classe::class)->find($normalLoan->getClasse()->getId());
                    $classLoan->setBalance($classLoan->getBalance() + $mainLoan);

                    /*register the loan situation*/
                    $entityManager->getRepository(Member::class)->saveMemberLoanOperation($currentUser, $dateOperation, $member, $mainLoan, $normalLoan, $representative);

                    if ($loanInterest != 0) {
                        $LoanInterestAccount = $entityManager->getRepository(InternalAccount::class)->find(136);
                        $LoanInterestAccount->setBalance($LoanInterestAccount->getBalance() + $loanInterest);

                        $classInterest = $entityManager->getRepository(Classe::class)->find($LoanInterestAccount->getClasse()->getId());
                        $classInterest->setBalance($classInterest->getBalance() + $loanInterest);

                        $entityManager->getRepository(Member::class)->saveMemberLoanInterestOperation($currentUser, $dateOperation, $member, $loanInterest, $LoanInterestAccount, $representative);
                        $entityManager->getRepository(Member::class)->saveMemberLoanInterestInGeneralLedger($loanInterest, $currentUser, $dateOperation, $LoanInterestAccount, $member, $representative);
                        /*Make record*/
                        $entityManager->flush();
                    }
                    if ($mainLoan != 0) {
                        $entityManager->getRepository(Member::class)->saveMemberLoanInGeneralLedger($mainLoan, $currentUser, $dateOperation, $normalLoan, $member, $representative);
                        /*Make record*/
                        $entityManager->flush();
                    }
                }
            }
            if ($savings != 0 && $savings >= 1) { //Saving Operation Member

                /*History for member situation first step */
                $savingOperation = $entityManager->getRepository(Member::class)->saveMemberSavingsOperation($currentUser, $dateOperation, $member, $savings, $representative);

                /**Member situation updated second step **/
                $member->setSaving($member->getSaving() + $savings);

                /*Update the member saving account third step*/
                $memberSavings = $entityManager->getRepository(InternalAccount::class)->find(44);
                $memberSavings->setBalance($memberSavings->getBalance() + $savings);

                $classSav = $entityManager->getRepository(Classe::class)->find($memberSavings->getClasse()->getId());
                $classSav->setBalance($classSav->getBalance() + $savings);

                $entityManager->getRepository(Member::class)->saveMemberSavingsInGeneralLedger($savings, $currentUser, $dateOperation, $memberSavings, $member, $representative);
                /* Make record */
                $totalTransaction += $savings;
                $operations[] = $savingOperation;
                $entityManager->flush();
            }

            if ($shares != 0) {//Shares operation for member
                $operationShare = $entityManager->getRepository(Member::class)->saveMemberSharesOperation($currentUser, $dateOperation, $member, $shares, $representative);
                /*second step*/
                $member->setShare($member->getShare() + $shares);

                /*update the shares account in trialBalance*/
                $memberShares = $entityManager->getRepository(InternalAccount::class)->find(1);
                $memberShares->setBalance($memberShares->getBalance() + $shares);
                $classShares = $entityManager->getRepository(Classe::class)->find($memberShares->getClasse()->getId());
                $classShares->setBalance($classShares->getBalance() + $shares);

                $entityManager->getRepository(Member::class)->saveMemberSharesInGeneralLedger($shares, $currentUser, $dateOperation, $memberShares, $member, $representative);

                /* Make record */
                $totalTransaction += $shares;
                $operations[] = $operationShare;
                $entityManager->flush();
            }

            if ($deposits != 0) { //Deposit Operations for Member
                /*Member situation on deposit*/
                $operationDeposit = $entityManager->getRepository(Member::class)->saveMemberDepositsOperation($currentUser, $dateOperation, $member, $deposits, $representative);
                //Second Step
                $member->setDeposit($member->getDeposit() + $deposits);

                /*third step*/
                $memberDeposits = $entityManager->getRepository(InternalAccount::class)->find(42);
                $memberDeposits->setBalance($memberDeposits->getBalance() + $deposits);

                $classDep = $entityManager->getRepository(Classe::class)->find($memberDeposits->getClasse()->getId());
                $classDep->setBalance($classDep->getBalance() + $deposits);

                $entityManager->getRepository(Member::class)->saveMemberDepositInGeneralLedger($deposits, $currentUser, $dateOperation, $memberDeposits, $member, $representative);

                /*Make record*/
                $totalTransaction += $deposits;
                $operations[] = $operationDeposit;
                $entityManager->flush();
            }

            $analytics = $this->analyticsArray($request->get('10000'), $request->get('5000'), $request->get('2000'), $request->get('1000'), $request->get('500'), $request->get('100'), $request->get('50'), $request->get('25'), $request->get('10'), $request->get('5'), $request->get('1'));

            $others = [];
            $charges = $request->get('Charges');
            $buildingFees = $request->get('Building');
            $registration = $request->get('Registration');

            if ($charges != 0) {
                $temp['name'] = "Charges";
                $temp['value'] = $charges;
                $others[] = $temp;
                $totalTransaction += $charges;

                $chargesAccount = $entityManager->getRepository(InternalAccount::class)->find(141);
                $chargesAccount->setBalance($chargesAccount->getBalance() + $charges);

                $classCharges = $entityManager->getRepository(Classe::class)->find($chargesAccount->getClasse()->getId());
                $classCharges->setBalance($classCharges->getBalance() + $charges);

                $entityManager->getRepository(Member::class)->saveMemberChargesOperation($currentUser, $dateOperation, $member, $charges, $chargesAccount, $representative);

                $entityManager->getRepository(Member::class)->saveMemberChargesInGeneralLedger($charges, $currentUser, $dateOperation, $chargesAccount, $member, $representative);

                $entityManager->flush();
            }
            if ($buildingFees != 0) {
                $temp['name'] = "Building fees";
                $temp['value'] = $buildingFees;
                $others[] = $temp;
                $totalTransaction += $buildingFees;

                // update the account in the trial balance
                $feesAccount = $entityManager->getRepository(InternalAccount::class)->find(6);
                $feesAccount->setBalance($feesAccount->getBalance() + $buildingFees);

                $classFees = $entityManager->getRepository(Classe::class)->find($feesAccount->getClasse()->getId());
                $classFees->setBalance($classFees->getBalance() + $buildingFees);

                $entityManager->getRepository(Member::class)->saveMemberBuildingFeesOperation($currentUser, $dateOperation, $member, $buildingFees, $feesAccount, $representative);

                /*Update member account second step*/
                $member->setBuildingFees($member->getBuildingFees() + $buildingFees);

                /* Update the ledger card first step */
                $entityManager->getRepository(Member::class)->saveMemberBuildingFeesInGeneralLedger($buildingFees, $currentUser, $dateOperation, $feesAccount, $member, $representative);

                /* Make record */
                $entityManager->flush();
            }

            if ($registration != 0) {
                $temp['name'] = "Entrance fees";
                $temp['value'] = $registration;
                $others[] = $temp;
                $totalTransaction += $registration;

                // get the entrance fees account
                $memberEntranceFees = $entityManager->getRepository(InternalAccount::class)->find(151);
                $memberEntranceFees->setBalance($memberEntranceFees->getBalance() + $registration);

                /* Update the class account value*/
                $classRegistration = $entityManager->getRepository(Classe::class)->find($memberEntranceFees->getClasse()->getId());
                $classRegistration->setBalance($classRegistration->getBalance() + $registration);

                /* Register the Registration fees in the member transaction history */
                $entityManager->getRepository(Member::class)->saveMemberRegistrationOperation($currentUser, $dateOperation, $member, $registration, $memberEntranceFees, $representative);

                // update the member register fees
                $member->setRegistrationFees($member->getRegistrationFees() + $registration);

                // save registration fees operation in the general ledger
                $entityManager->getRepository(Member::class)->saveMemberRegistrationFeesInGeneralLedger($registration, $currentUser, $dateOperation, $memberEntranceFees, $member, $representative);
            }
            $entityManager->flush();
            $html = $this->renderView('operation/cash_in_receipt_file.html.twig', [
                'agency' => $agency,
                'member' => $member,
                'loanhistory' => $loan_history,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'others' => $others,
                'balanceStatus' => $balanceStatus,
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'currentDate' => $dateOperation,
                'accountOperations' => $operations,
            ]);

            $nomMember = str_replace(' ', '_', $member->getName());
            $operationType = str_replace(' ', '_', Operation::TYPE_CASH_IN);

            $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', [2.5, 2.5, 2.5, 10]);
            $html2pdf->pdf->SetAuthor('GreenSoft-Group');
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->pdf->SetTitle('RECEIPT_' . $operationType . '_' . $nomMember);
            $response = new Response();
            $html2pdf->pdf->SetTitle('RECEIPT_' . $operationType . '_' . $nomMember);
            $html2pdf->writeHTML($html);
            $content = $html2pdf->Output('', true);
            $response->setContent($content);
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-disposition', 'filename=Receipt' . $operationType . '_' . $member->getMemberNumber() . '.pdf');
            return $response;
        }else{
            return $this->render('Exception/error_404.html.twig');
        }
    }

    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/other/cash_in/save", name="other_operation_cash_in_save")
     * @Method({"GET", "POST"})
     * @return Response
     */
    function saveOtherCashInOperation(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->findAll()[0];

        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository(Utilisateur::class)->find($currentUserId);

        if ($request->getMethod() == 'POST') {
            $balanceStatus = $request->get('balance-display');
            $accountId = $request->get('accountNumber');
            $memberId = $request->get('memberNumber');
            $representative = $request->get('representative');
            $amount = $request->get('amount');
            $operations = [];
            $totalTransaction = 0;
            $dateOp = $request->get('dateOperation');
            $dateOperation = $this->dateConcatenation($dateOp);
            $member = $entityManager->getRepository(Member::class)->find($memberId);
            // Format the date of the operation to be synchronized
            /*Get the account by ID*/
            $account = $entityManager->getRepository(InternalAccount::class)->find($accountId);
            $class = $entityManager->getRepository(Classe::class)->find($account->getClasse()->getId());

            if ($accountId == 82 || $accountId == 76) {
                $account->setBalance($account->getBalance() - $amount);
                $class->setBalance($class->getBalance() - $amount);
            }else{
                $account->setBalance($account->getBalance() + $amount);
                $class->setBalance($class->getBalance() + $amount);
            }
            /*Function from repository*/
            $ledgerBalance = $entityManager->getRepository(GeneralLedgerBalance::class)->registerGBLCashIn($amount ,$currentUser, $dateOperation, $account, $representative, $member);
            $totalTransaction += $amount;
            $operations[] = $ledgerBalance;
            $analytics = $this->analyticsArray($request->get('10000'), $request->get('5000'), $request->get('2000'),$request->get('1000'), $request->get('500'), $request->get('100'), $request->get('50'), $request->get('25'), $request->get('10'), $request->get('5'), $request->get('1'));
        }
        $entityManager->flush();

        $html =  $this->renderView('operation/other_in_receipt_file.html.twig', [
                'agency' => $agency,
                'member' => $member,
                'balanceStatus' => $balanceStatus,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'currentDate' => $dateOperation,
                'accountOperations' => $operations,
    ]);

        $operationType = str_replace(' ', '_', $ledgerBalance->getTypeOperation());
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
     * @Route("/cash_out/save", name="operation_cash_out_save")
     * @Method({"GET", "POST"})
     * @return Response
     */
    function saveCashOutOperation(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $agency = $entityManager->getRepository(Agency::class)->findAll()[0];
        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository(Utilisateur::class)->find($currentUserId);

        if ($request->getMethod() == 'POST') {
            $data = $this->hydrateCashOut($request);
            $operations = [];
            $totalTransaction = 0;
            $member = $entityManager->getRepository(Member::class)->find($data['accountMemberId']);
            $representative = $data['representative'] == "" ? $member->getName() : $data['representative'];

            if ($data['savings'] != 0) {
                $operation = $entityManager->getRepository(Operation::class)->operationSaving($currentUser, $member, $representative, $data['dateOp'], $data['savings']);
                    /*Third Step*/ 
                $savingAccount  = $entityManager->getRepository(InternalAccount::class)->find(44);
                $savingAccount->setBalance($savingAccount->getBalance() - $data['savings']);

                $class = $entityManager->getRepository(Classe::class)->find($savingAccount->getClasse()->getId());
                $class->setBalance($class->getBalance() - $data['savings']);
                // first Step
                $entityManager->getRepository(GeneralLedgerBalance::class)->recordGeneraLB($currentUser, $member, $representative, $data['dateOp'], $data['savings'], $savingAccount);
                $operations[] = $operation;
                $entityManager->flush();
                $totalTransaction += $data['savings'] ;
            }
            if ($data['savingsCharges'] != 0) {
                /* Records of the savings withdrawals charges**/
                $account = $entityManager->getRepository(InternalAccount::class)->find(142);
                $account->setBalance($account->getBalance() + $data['savingsCharges']);
                $class = $entityManager->getRepository(Classe::class)->find($account->getClasse()->getId());
                $class->setBalance($class->getBalance() + $data['savingsCharges']);
                $entityManager->getRepository(GeneralLedgerBalance::class)->recordGeneraLBIn($currentUser, $member, $representative, $data['dateOp'], $data['savingsCharges'], $account);
                $operation = $entityManager->getRepository(Operation::class)->registerGeneralOperation($currentUser, $data, $member, $representative, $account);
                $operations[] = $operation;
            }
            if ($data['shares'] != 0) {//Shares operation for member
                $operationShare = $entityManager->getRepository(Operation::class)->operationShare($currentUser, $member, $representative, $data['dateOp'], $data['shares']);
                $sharesAccount  = $entityManager->getRepository(InternalAccount::class)->find(1);
                $sharesAccount->setBalance($sharesAccount->getBalance() - $data['shares']);
                $classShares = $entityManager->getRepository('ClassBundle:Classe')->find($sharesAccount->getClasse()->getId());
                $classShares->setBalance($classShares->getBalance() - $data['shares']);
                    // first Step
                $entityManager->getRepository(GeneralLedgerBalance::class)->recordGeneraLB($currentUser, $member, $representative, $data['dateOp'], $data['shares'], $sharesAccount);
                $totalTransaction += $data['shares'];
                $operations[] = $operationShare;
                $entityManager->flush();
            }
            if ($data['deposits'] != 0) { //Deposit Operations for Member
                $operationDeposit = $entityManager->getRepository(Operation::class)->operationDeposit($currentUser, $member, $representative, $data['dateOp'], $data['deposits']);
                $memberDeposits  = $entityManager->getRepository(InternalAccount::class)->find(42);
                $memberDeposits->setBalance($memberDeposits->getBalance() - $data['deposits']);

                $classDep = $entityManager->getRepository(Classe::class)->find($memberDeposits->getClasse()->getId());
                $classDep->setBalance($classDep->getBalance() - $data['deposits']);

                $entityManager->getRepository(GeneralLedgerBalance::class)->recordGeneraLB($currentUser, $member, $representative, $data['dateOp'], $data['deposits'], $memberDeposits);

                $entityManager->persist($operationDeposit);
                $totalTransaction += $data['deposits'];
                $operations[] = $operationDeposit;
            }
            $analytics = $this->analyticsArray($request->get('10000'), $request->get('5000'), $request->get('2000'),$request->get('1000'), $request->get('500'), $request->get('100'), $request->get('50'), $request->get('25'), $request->get('10'), $request->get('5'), $request->get('1')
                );
            $others = [];
        }
        $entityManager->flush();
        $template =  $this->renderView('operation/cash_out_receipt_file.html.twig', [
                'agency' => $agency,
                'member' => $member,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'others' => $others,
                'totalTransaction' => $totalTransaction,
                'representative' => $data['representative'] == "" ? $member->getName() : $data['representative'],
                'balanceStatus' => $data['balanceStatus'],
                'savingsCharges' => $data['savingsCharges'],
                'currentDate' => $data['dateOp'],
                'accountOperations' => $operations,
            ]);

        $memberName = str_replace(' ', '_', $member->getName());
        $title = 'Receipt_CASH_OUT_'.$memberName.'_'.$data['dateOp']->format('d-m-Y_H:i:s');
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
        if ($operations){
            return $html2PdfService->generatePdf($template, $title.'.pdf', 'operations',$title, 'FI');
        }
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'operations',$title, 'I');
    }


    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/other/cash_out/save", name="other_operation_cash_out_save")
     * @Method({"GET", "POST"})
     * @return Response
     */
    function saveOtherCashOutOperation(Request $request) {

        $entityManager = $this->getDoctrine()->getManager();
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->findAll()[0];
        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository(Utilisateur::class)->find($currentUserId);

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
            $account = $entityManager->getRepository(InternalAccount::class)->find($accountId);
            $class = $entityManager->getRepository(Classe::class)->find($account->getClasse()->getId());
            if ($accountId == 82 || $accountId == 76){
                $account->setBalance($account->getBalance() + $amount);
                $class->setBalance($class->getBalance() + $amount);
            }else{
                $account->setBalance($account->getBalance() - $amount);
                $class->setBalance($class->getBalance() - $amount);
            }
            $ledgerBalance = $entityManager->getRepository(GeneralLedgerBalance::class)->registerGBLCashOut($amount ,$currentUser, $dateOperation, $account, $representative);
            $totalTransaction += $amount;
            $operations[] = $ledgerBalance;

            $analytics = $this->analyticsArray($request->get('10000'), $request->get('5000'), $request->get('2000'),$request->get('1000'), $request->get('500'), $request->get('100'), $request->get('50'), $request->get('25'), $request->get('10'), $request->get('5'), $request->get('1')
                );

            $entityManager->flush();

            $template =  $this->renderView('operation/other_out_receipt_file.html.twig', array(
                'agency' => $agency,
                'analytics' => $analytics,
                'numberInWord' => $this->convertNumberToWord($totalTransaction),
                'totalTransaction' => $totalTransaction,
                'representative' => $representative,
                'balanceStatus' => $balanceStatus,
                'currentDate' => $dateOperation,
                'accountOperations' => $operations,
            ));

            $operationType = str_replace(' ', '_', $ledgerBalance->getTypeOperation());
            $accountName = str_replace(' ', '_', $account->getAccountName());

            $title = 'Receipt_'.$operationType.'_'.$accountName.'_'.$ledgerBalance->getDateOperation()->format('d-m-Y_H:i:s');
            $html2PdfService = $this->get('app.html2pdf');
            $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
            if ($operations){
                return $html2PdfService->generatePdf($template, $title.'.pdf', 'operations',$title, 'FI');
            }
            return $html2PdfService->generatePdf($template, $title.'.pdf', 'operations',$title, 'I');
        }
    }

    public function convertNumberToWord($num = false)
    {
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

    public function analyticsArray($tenThousands, $fiveThousands, $twoThousands, $oneThousands, $fiveHundred, $oneHundred, $fifty, $twentyFive, $ten, $five, $one)
    {
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

    /**
     * @param $date
     * @return \DateTime
     */
    public function dateConcatenation($date)
    {
        $dateExplode = explode( "/" , substr($date,strrpos($date," ")));
        $dateStart  = new \DateTime($dateExplode[2]."-".$dateExplode[1]."-".$dateExplode[0]);
        $currentDate = new \DateTime('now');
        $dateConcatenate = new \DateTime($dateStart->format('Y-m-d') .' ' .$currentDate->format('H:m:s'));
        return $dateConcatenate;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function hydrateCashOut(Request $request)
    {
        $data = [];
        $data['balanceStatus'] = $request->get('balance-display');
        $data['savingsCharges'] = $request->get('savings-charges');
        $data['accountMemberId'] = $request->get('accountNumber');
        $data['representative'] = $request->get('representative');
        $data['savings'] = $request->get('savings');
        $data['shares'] = $request->get('shares');
        $data['deposits'] = $request->get('deposits');
        $data['dateOp'] = $this->dateConcatenation($request->get('dateOperation'));
        $data['mainLoan'] = $request->get('mainLoan');
        $data['loanInterest'] = $request->get('loanInterest');

        return $data;
    }
}