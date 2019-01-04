<?php

namespace ReportBundle\Controller;

use AccountBundle\Entity\Loan;
use AccountBundle\Entity\LoanHistory;
use AccountBundle\Entity\Operation;
use ConfigBundle\Entity\Agency;
use MemberBundle\Entity\Member;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use UserBundle\Entity\Utilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Operation controller.
 *
 * @Route("report")
 */
class ReportController extends Controller{
    /**
     * @Route("/trial_balance", name="report_trial_balance")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function index()
    {
        // replace this example code with whatever you need
        return $this->render('report/report.html.twig');
    }

    /**
     * @Route("/situations", name="internal_account_balance")
     */
    public function internalAccountSituation(){
        // Test is the user does not have the default role
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_BOARD')) {
            return new RedirectResponse($this->container->get ('router')->generate ('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $internalAccounts = $em->createQueryBuilder()
            ->select('ia')
            ->from('ClassBundle:InternalAccount', 'ia')
            ->where('ia.balance != :balance')
            ->orderBy('ia.accountNumber', 'ASC')
            ->setParameters(
                [
                    'balance' => 0,
                ]
            )->getQuery()->getResult();
        // replace this example code with whatever you need
        return $this->render('report/internal_accounts_situation.html.twig', [
            'accounts' => $internalAccounts
        ]);
    }



    /**
     * @Route("/month", name="report_month")
     * @param Request $request
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function reportMonthAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == "POST") {

            $agency = $em->getRepository('ConfigBundle:Agency')->findOneBy([], ['id' => 'ASC']);
            $currentDate = new \DateTime('now');

            $currentUser  = $this->get('security.token_storage')->getToken()->getUser();

            $dateDebut = $request->get('start');
            $dateFin = $request->get('end');

            $newDateStart = explode( "/" , substr($dateDebut,strrpos($dateDebut," ")));
            $newDateEnd = explode( "/" , substr($dateFin,strrpos($dateFin," ")));

            $dateStart = \DateTime::createFromFormat("Y-m-d H:i:s", date($newDateStart[2]."-".$newDateStart[1]."-".$newDateStart[0]." 00:00:00"));
            $dateEnd = \DateTime::createFromFormat("Y-m-d H:i:s", date($newDateEnd[2]."-".$newDateEnd[1]."-".$newDateEnd[0]." 23:59:59"));

            $incomeOperations = $em->createQueryBuilder()
                ->select('op, SUM(op.debit) as amount, SUM(op.credit) as credit, ia')
                ->from('ReportBundle:GeneralLedgerBalance', 'op')
                ->innerJoin('ClassBundle:InternalAccount', 'ia','WITH','ia.id = op.account')
                ->innerJoin('ClassBundle:Classe', 'cl','WITH','cl.id = ia.classe')
                ->where('op.dateOperation BETWEEN :date1 AND :date2')
                ->andWhere('cl.id =:income')
                ->groupBy('op.account')
                ->setParameters(
                    [
                    'date1' => $dateStart,
                    'date2' => $dateEnd,
                    'income' => 7,
                    ]
                )
                ->getQuery()->getScalarResult(); 

            $expenditureOperations = $em->createQueryBuilder()
                    ->select('op, SUM(op.debit) as amount, SUM(op.credit) as credit, ia')
                    ->from('ReportBundle:GeneralLedgerBalance', 'op')
                    ->innerJoin('ClassBundle:InternalAccount', 'ia','WITH','ia.id = op.account')
                    ->innerJoin('ClassBundle:Classe', 'cl','WITH','cl.id = ia.classe')
                    ->where('op.dateOperation BETWEEN :date1 AND :date2')
                    ->andWhere('cl.id = :expenditure')
                    ->groupBy('op.account')
                    ->setParameters(
                        [
                        'date1' => $dateStart,
                        'date2' => $dateEnd,
                        'expenditure' => 6,
                        ]
                    )
                    ->getQuery()->getScalarResult();

            $template =  $this->renderView('pdf_files/monthly_report_file.html.twig', [
                'displayDateStart' => $dateDebut,
                'displayDateEnd' => $dateFin,
                'currentUser' => $currentUser,
                'date' => $currentDate,
                'agency' => $agency,
                'expenditureOp' => $expenditureOperations,
                'incomeOp' => $incomeOperations,
            ]);

            $title = 'Monthly_Report_'.$dateStart->format('d-m-Y').'_'.$dateEnd->format('d-m-Y');
            $html2PdfService = $this->get('app.html2pdf');
            $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
            return $html2PdfService->generatePdf($template, $title.'.pdf', 'ledgers',$title, 'FI');

        }
        return $this->render('report/report_month.html.twig', [

        ]);
    }


    /**
     * @param Request $request
     * @Route("/general_ledger", name="report_general_ledger")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function generalLedgerBalance(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() =="POST") {
            $agency = $em->getRepository(Agency::class)->findOneBy([], ['id' => 'ASC']);
            $currentDate = new \DateTime('now');

            $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $currentUser    = $em->getRepository(Utilisateur::class)->find($currentUserId);
            $date  = $request->get('currentDate');
            $date = explode( "/" , substr($date,strrpos($date," ")));

            $today_start_datetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($date[2]."-".$date[1]."-".$date[0]." 00:00:00"));
            $today_end_datetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($date[2]."-".$date[1]."-".$date[0]." 23:59:59"));

            $date  = new \DateTime($date[2]."-".$date[1]."-".$date[0]);
            $day_before = date( 'Y-m-d', strtotime( $date->format('Y-m-d') . ' -1 day' ) );
            $dayBefore_endDatetime = \DateTime::createFromFormat("Y-m-d H:i:s", $day_before." 23:59:59");

                /*Get the balance brod*ad forward for the new day*/
            $totalGLBDayBefore = $em->createQueryBuilder()
                ->select('glb')
                ->from('ReportBundle:GeneralLedgerBalance', 'glb')
                ->where('glb.dateOperation <= :date')
                ->setParameters(['date' => $dayBefore_endDatetime])
                ->getQuery()
                 ->getResult();
                 $lastElement = end($totalGLBDayBefore);
                 if ($lastElement) {
                     $lastOperation = $lastElement;
                     $lastElement = $lastElement->getBalance();
                    }else{
                        $lastElement = 0;
                        $lastOperation = NULL;
                 }

            $operations = $em->createQueryBuilder()
                ->select('glb')
                ->from('ReportBundle:GeneralLedgerBalance', 'glb')
                ->where('glb.dateOperation >= :date')
                ->andWhere('glb.dateOperation <= :date_end')
                ->setParameters([
                    'date' => $today_start_datetime,
                    'date_end' => $today_end_datetime
                    ])
                ->getQuery()
                ->getResult();

            $template = $this->renderView('situation/general_ledger_pdf.html.twig', [
                'operations' => $operations,
                'agency' => $agency,
                'currentUser' => $currentUser,
                'date' => $currentDate,
                'endDate' => $date,
                'balanceBF' => $lastElement,
                'lastOperation' => $lastOperation,
                'dayBefore' => $dayBefore_endDatetime,
            ]);

            $title = 'General_Ledger_'.$date->format('d-m-Y');
            $html2PdfService = $this->get('app.html2pdf');
            $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
            if ($operations){
                return $html2PdfService->generatePdf($template, $title.'.pdf', 'ledgers',$title, 'FI');
            }
            return $html2PdfService->generatePdf($template, $title.'.pdf', 'ledgers',$title, 'I');
        }

        $accounts = $em->getRepository('ClassBundle:InternalAccount')->findAll();
        // replace this example code with whatever you need
        return $this->render('report/general_ledger.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * @param Request $request
     * @Route("/individual_ledger", name="report_individual_ledger")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function individualLedgerBalance(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() =="POST") {
            $agency = $em->getRepository('ConfigBundle:Agency')->findOneBy([], ['id' => 'ASC']);
            $currentDate = new \DateTime('now');

            $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $currentUser    = $em->getRepository('UserBundle:Utilisateur')->find($currentUserId);

            $data = $request->request->all();
            $start = explode( "/" , substr($data['start'],strrpos($data['start']," ")));
            $end = explode( "/" , substr($data['end'],strrpos($data['end']," ")));

            $start_datetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($start[2]."-".$start[1]."-".$start[0]." 00:00:00"));
            $end_datetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($end[2]."-".$end[1]."-".$end[0]." 23:59:59"));

            $operations = $em->createQueryBuilder()
                ->select('glb')
                ->from('ReportBundle:GeneralLedgerBalance', 'glb')
                ->innerJoin('ClassBundle:InternalAccount', 'ia', 'WITH', 'ia.id = glb.account')
                ->where('glb.dateOperation >= :date')
                ->andWhere('glb.dateOperation <= :date_end')
                ->andWhere('ia.id = :accountId')
                ->setParameters([
                    'date' => $start_datetime,
                    'date_end' => $end_datetime,
                    'accountId' => $data['account_number']
                    ])
                ->getQuery()
                ->getResult();

            $account = $em->getRepository('ClassBundle:InternalAccount')->find($data['account_number']);
            $template = $this->renderView('situation/individual_ledger_pdf.html.twig', [
                'operations' => $operations,
                'agency' => $agency,
                'account' => $account,
                'currentUser' => $currentUser,
                'date' => $currentDate,
                'start_date' => $start_datetime,
                'end_date' => $end_datetime,
            ]);

            $accountName = str_replace(' ', '_', $account->getAccountName());
            $title = 'Individual_Ledger_'.$accountName.'_'.$currentDate->format('d-m-Y');
            $html2PdfService = $this->get('app.html2pdf');
            $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
            if ($operations){
                return $html2PdfService->generatePdf($template, $title.'.pdf', 'ledgers',$title, 'FI');
            }
            return $html2PdfService->generatePdf($template, $title.'.pdf', 'ledgers',$title, 'I');
        }

        $accounts = $em->getRepository('ClassBundle:InternalAccount')->findAll();
        // replace this example code with whatever you need
        return $this->render('report/general_ledger.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * member situation.
     *
     * @Route("/memberSituation", name="report_member_situation")
     * @Method("GET")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function memberSituationAction(){

        $entityManager = $this->getDoctrine()->getManager();
        $members  = $entityManager->getRepository('MemberBundle:Member')->findBy([],['memberNumber' => 'ASC',]);

        return $this->render('report/member_situation.html.twig', array(
            'members' => $members,
        ));
    }

    /**
     * member situation.
     * @param $status
     * @param $type
     *
     * @Route("/{status}/{type}/list", name="report_generate_document")
     * @Method("GET")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function generateDocumentAction($status, $type)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $agency = $entityManager->getRepository(Agency::class)->findOneBy([], ['id' => 'ASC']);

        $lists = [];
        $listLoanWithOutHistory = [];
        switch ($status) {
            case "allMembers":
                $lists  = $entityManager->getRepository(Member::class)->findBy([], ['memberNumber' => 'ASC']);
                break;
            case "activeMembers":
                $lists  = $entityManager->getRepository(Member::class)->getActiveMembers();
                break;
            case "inactiveMembers":
                $lists  = $entityManager->getRepository(Member::class)->getAllInActiveMembers();
                break;
            case "foundingMembers":
                $lists  = $entityManager->getRepository(Member::class)->getFoundingMembers();
                break;
            case "allLoans":
                $subQuery  = $entityManager->createQueryBuilder()
                    ->select('(lh.loan)')
                    ->from('AccountBundle:LoanHistory', 'lh')
                    ->innerJoin('AccountBundle:Loan', 'l', 'WITH', 'l.id = lh.loan')
                    ->getQuery()
                    ->getArrayResult();


                    $queryBuilder = $entityManager->createQueryBuilder();
                    $listLoanWithOutHistory = $entityManager->createQueryBuilder()
                        ->select('l')
                        ->from('AccountBundle:Loan', 'l')
                        ->where($queryBuilder->expr()->notIn('l.id', ':subQuery'))
                        ->setParameter('subQuery', $subQuery)
                        ->getQuery()
                        ->getResult();

                $lists  = $entityManager->createQueryBuilder()
                        ->select('l', 'lh')
                        ->from('AccountBundle:Loan', 'l')
                        ->innerJoin('AccountBundle:LoanHistory', 'lh', 'WITH', 'l.id = lh.loan')
                        ->where('lh.dateOperation IN
                                    (SELECT MAX(lh2.dateOperation)
                                    FROM AccountBundle:LoanHistory lh2
                                    WHERE lh2.loan = l.id
                                    ORDER BY lh2.id DESC
                                    )'
                            )
                        ->andWhere('l.status = true')
                        ->groupBy('lh.loan')
                        ->getQuery()
                        ->getScalarResult();
                break;
            case "activeLoans":
                # code...
                break;
            case "inactiveLoans":
                # code...
                break;
            default:
                die("NOoooooo");
                break;
        }

        $template =  $this->renderView('pdf_files/generate_document_file.html.twig', [
            'agency' => $agency,
            'lists' => $lists,
            'type' => $type,
            'status' => $status,
            'listLoanWithOutHistory' => $listLoanWithOutHistory,
        ]);


        $title = 'List_'.$type;
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'ledgers',$title, 'FI');
    }

    /**
     * member situation on saving.
     *
     * @Route("/saving/{id}", name="member_situation_saving")
     * @Method("GET")
     * @param $id
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function savingSituation($id)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $member  = $entityManager->getRepository('MemberBundle:Member')->find($id);
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->findOneBy([], ['id' => 'ASC']);
        $currentDate = new \DateTime('now');

        $operations = $entityManager->getRepository('AccountBundle:Operation')->findBy([
            'member' => $member,
            'isSaving' => true
            ]);

        $firstOp = $entityManager->getRepository('AccountBundle:Operation')->findOneBy([
            'member' => $member,
            'isSaving' => true],
            ['id' => 'ASC',]);

        $MemberName = str_replace(' ', '_', $member->getName());
        $type = "Savings";
        $template =  $this->renderView('situation/accounts_situation_file.html.twig', array(
            'agency' => $agency,
            'member' => $member,
            'firstOp' => $firstOp,
            'type' => $type,
            'currentDate' => $currentDate,
            'operations' => $operations,
        ));

        $title = 'Situation_'.$type.'_'.$MemberName;
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'savings',$title, 'FI');
    }


    /**
     * member situation on saving.
     *
     * @Route("/shares/{id}", name="member_situation_shares")
     * @Method("GET")
     * @param $id
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function sharesSituationAction($id){

        $entityManager = $this->getDoctrine()->getManager();
        $member  = $entityManager->getRepository('MemberBundle:Member')->find($id);
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->findOneBy([], ['id' => 'ASC']);
        $currentDate = new \DateTime('now');

        $operations = $entityManager->getRepository('AccountBundle:Operation')->findBy([
            'member' => $member,
            'isShare' => true
            ]);

        $firstOp = $entityManager->getRepository('AccountBundle:Operation')->findOneBy([
            'member' => $member,
            'isShare' => true],
            ['id' => 'ASC',]
            );

        $MemberName = str_replace(' ', '_', $member->getName());
        $type = "Shares";
        $template =  $this->renderView('situation/accounts_situation_file.html.twig', array(
            'agency' => $agency,
            'member' => $member,
            'type' => $type,
            'firstOp' => $firstOp,
            'currentDate' => $currentDate,
            'operations' => $operations,
        ));

        $title = 'Situation_'.$type.'_'.$MemberName;
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'shares',$title, 'FI');
    }

    /**
     * member situation on saving.
     * @param $id
     *
     * @Route("/deposit/{id}", name="member_situation_deposit")
     * @Method("GET")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function depositSituation($id){

        $entityManager = $this->getDoctrine()->getManager();
        $member  = $entityManager->getRepository('MemberBundle:Member')->find($id);
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->findOneBy([], ['id' => 'ASC']);
        $currentDate = new \DateTime('now');

        $operations = $entityManager->getRepository('AccountBundle:Operation')->findBy([
            'member' => $member,
            'isDeposit' => true
            ]);
        $firstOp = $entityManager->getRepository('AccountBundle:Operation')->findOneBy([
            'member' => $member,
            'isDeposit' => true],
            ['id' => 'ASC',]
            );


        $MemberName = str_replace(' ', '_', $member->getName());
        $type = "Deposits";
        $template =  $this->renderView('situation/accounts_situation_file.html.twig', array(
            'agency' => $agency,
            'member' => $member,
             'firstOp' => $firstOp,
            'type' => $type,
            'currentDate' => $currentDate,
            'operations' => $operations,
        ));

        $title = 'Situation_'.$type.'_'.$MemberName;
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'deposits',$title, 'FI');
    }


    /**
     * member situation on saving.
     *
     * @Route("/loans/{id}", name="member_situation_loan")
     * @Method("GET")
     * @param $id
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function loanSituation($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $member  = $entityManager->getRepository(Member::class)->find($id);
        $agency = $entityManager->getRepository(Agency::class)->findOneBy([], ['id' => 'ASC']);
        $currentDate = new \DateTime('now');

        $loan = $entityManager->getRepository(Loan::class)->findOneBy(['physicalMember' => $member, 'status' => true]);

        $loanSituations = $entityManager->getRepository(LoanHistory::class)->findBy(['loan' => $loan]);

        $MemberName = str_replace(' ', '_', $member->getName());
        $type = "Loans";
        $template =  $this->renderView('situation/loan_situation_file.html.twig', [
            'agency' => $agency,
            'member' => $member,
            'loan' => $loan,
            'type' => $type,
            'currentDate' => $currentDate,
            'loanSituations' => $loanSituations,
        ]);
        $title = 'Situation_'.$type.'_'.$MemberName;
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(5, 10, 5, 10));
        if ($loanSituations){
            return $html2PdfService->generatePdf($template, $title.'.pdf', 'loans',$title, 'FI');
        }
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'loans',$title, 'I');

    }


    /**
     * @Route("/generate/trial_balance", name="trialbalance_report")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function trialBalance(Request $request){

        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $currentUser    = $em->getRepository('UserBundle:Utilisateur')->find($currentUserId);
            $date = new \DateTime('now');
            $agency = $em->getRepository('ConfigBundle:Agency')->findOneBy([], ['id' => 'ASC']);

            $internalAccounts = $em->createQueryBuilder()
                ->select('ia')
                ->from('ClassBundle:InternalAccount', 'ia')
                ->where('ia.balance != :balance')
                ->orderBy('ia.accountNumber', 'ASC')
                ->setParameters(
                    [
                        'balance' => 0,
                    ]
                )->getQuery()->getResult();

            $dateDebut = $request->get('start');
            $dateFin = $request->get('end');

            $newDateStart = explode( "/" , substr($dateDebut,strrpos($dateDebut," ")));
            $newDateEnd = explode( "/" , substr($dateFin,strrpos($dateFin," ")));

            $displayDateStart  = $newDateStart[1]."-".$newDateStart[0]."-".$newDateStart[2];
            $displayDateEnd  = $newDateEnd[1]."-".$newDateEnd[0]."-".$newDateEnd[2];

                $template =  $this->renderView('report/trial_balance_file.html.twig', array(
                    'displayDateStart' => $displayDateStart,
                    'displayDateEnd' => $displayDateEnd,
                    'currentUser' => $currentUser,
                    'date' => $date,
                    'agency' => $agency,
                    'internalAccounts' => $internalAccounts,
                ));

                $title = 'Trial_Balance';
                $html2PdfService = $this->get('app.html2pdf');
                $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 15));
                    return $html2PdfService->generatePdf($template, $title.'.pdf', 'loans',$title, 'I');
            }
        }


    /**
     * @Route("/account/history", name="accounthistoryreport")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
        public function accountHistoryAction(Request $request){
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $agency = $em->getRepository('ConfigBundle:Agency')->findOneBy([], ['id' => 'ASC']);

            $currentDate = new \DateTime('now');

            if ($request->getMethod() == 'POST') {

                $dateDebut = $request->get('start');
                $dateFin = $request->get('end');
                $accountNumber = $request->get('accountNumber');
                $accountType = $request->get('accountType');

                $newDateStart = explode( "/" , substr($dateDebut,strrpos($dateDebut," ")));
                $newDateEnd = explode( "/" , substr($dateFin,strrpos($dateFin," ")));


                $dateStart1  = new \DateTime($newDateStart[2]."-".$newDateStart[0]."-".$newDateStart[1]);
                $dateEnd1  = new \DateTime($newDateEnd[2]."-".$newDateEnd[0]."-".$newDateEnd[1]);

//                $dateEnd11 = $dateEnd1->add(new \DateInterval('P1D'));

                $displayDateStart  = $newDateStart[1]."-".$newDateStart[0]."-".$newDateStart[2];
                $displayDateEnd  = $newDateEnd[1]."-".$newDateEnd[0]."-".$newDateEnd[2];
 
                switch ($accountType) {
                    case 1:
                        $qb->select('op')
                            ->from('AccountBundle:Operation', 'op')
                            ->innerJoin('AccountBundle:Saving', 'sa','WITH','op.savingAccount = sa.id')
                            ->where('op.dateOperation BETWEEN :date1 AND :date2')
                            ->andWhere('sa.id =:identify')
                            ->setParameters(
                                [
                                'date1' => $dateStart1->format('Y-m-d'),
                                'date2' => $dateEnd1->format('Y-m-d'),
                                'identify' => $accountNumber,
                                ]
                            );
                        $operations = $qb->getQuery()->getResult();

                        $account = $em->getRepository('AccountBundle:Saving')->find($accountNumber);
                        break;
                    case 2:
                        $qb->select('op')
                            ->from('AccountBundle:Operation', 'op')
                            ->innerJoin('AccountBundle:Share', 'sa','WITH','op.shareAccount = sa.id')
                            ->where('op.dateOperation BETWEEN :date1 AND :date2')
                            ->andWhere('sa.id =:identify')
                            ->setParameters(
                                [
                                'date1' => $dateStart1->format('Y-m-d'),
                                'date2' => $dateEnd1->format('Y-m-d'),
                                'identify' => $accountNumber,
                                ]
                            );
                        $operations = $qb->getQuery()->getResult();
                        $account = $em->getRepository('AccountBundle:Share')->find($accountNumber);
                        break;
                    case 3:
                        $qb->select('op')
                            ->from('AccountBundle:Operation', 'op')
                            ->innerJoin('AccountBundle:Deposit', 'sa','WITH','op.depositAccount = sa.id')
                            ->where('op.dateOperation BETWEEN :date1 AND :date2')
                            ->andWhere('sa.id =:identify')
                            ->setParameters(
                                [
                                'date1' => $dateStart1->format('Y-m-d'),
                                'date2' => $dateEnd1->format('Y-m-d'),
                                'identify' => $accountNumber,
                                ]
                            );
                        $operations = $qb->getQuery()->getResult();
                        $account = $em->getRepository('AccountBundle:Deposit')->find($accountNumber);
                        break;
                    default:
                        # code...
                        break;
                }

                $html =  $this->renderView('report/account_history_file.html.twig', array(
                    'operations' => $operations,
                    'agency' => $agency,
                    'displayDateStart' => $displayDateStart,
                    'displayDateEnd' => $displayDateEnd,
                    'account' => $account,
                    'currentDate' => $currentDate,
                ));

                $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(5, 10, 5, 10));
                $html2pdf->pdf->SetAuthor('GreenSoft-Team');
                $html2pdf->pdf->SetDisplayMode('real');
                $html2pdf->pdf->SetTitle('Account statement_'.$account->getAccountNumber());
                $response = new Response();
                $html2pdf->pdf->SetTitle('Account Statement_'.$account->getAccountNumber());
                $html2pdf->writeHTML($html);
                $content = $html2pdf->Output('', true);
                $response->setContent($content);
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-disposition', 'filename=Account_Statement_'.$account->getAccountNumber().'.pdf');
                return $response;
            }
        }


    /**
     * @Route("/daily/confirmation", name="operation_confirmation")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function dailyReportConfirmationAction(Request $request){
        $em = $this->getDoctrine()->getManager();


        if ($request->getMethod() == 'POST') {

            $dateDebut = $request->get('dateOperation');

            $newDateStart = explode( "/" , substr($dateDebut,strrpos($dateDebut," ")));

            $today_start_datetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($newDateStart[2]."-".$newDateStart[1]."-".$newDateStart[0]." 00:00:00"));
            $today_end_datetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($newDateStart[2]."-".$newDateStart[1]."-".$newDateStart[0]." 23:59:59"));

            $operations = $em->createQueryBuilder()
                ->select('op')
                ->from('AccountBundle:Operation', 'op')
                ->where('op.dateOperation >= :start')
                ->andWhere('op.dateOperation <= :end')
                ->andWhere('op.isConfirmed = FALSE')
                ->setParameters(
                    [
                        'start' => $today_start_datetime,
                        'end' => $today_end_datetime,
                    ]
                )->getQuery()->getResult();


            $loanHistory = $em->createQueryBuilder()
                ->select('lh')
                ->from('AccountBundle:LoanHistory', 'lh')
                ->where('lh.dateOperation >= :start')
                ->andWhere('lh.dateOperation <= :end')
                ->setParameters(
                    [
                        'start' => $today_start_datetime,
                        'end' => $today_end_datetime,
                    ]
                )->getQuery()->getResult();

            $transactionIncome = $em->createQueryBuilder()
                ->select('ti')
                ->from('ConfigBundle:TransactionIncome', 'ti')
                ->where('ti.transactionDate >= :start')
                ->andWhere('ti.transactionDate <= :end')
                ->setParameters(
                    [
                        'start' => $today_start_datetime,
                        'end' => $today_end_datetime,
                    ]
                )->getQuery()->getResult();

            $loans = $em->createQueryBuilder()
                ->select('l')
                ->from('AccountBundle:Loan', 'l')
                ->where('l.dateLoan >= :start')
                ->andWhere('l.dateLoan <= :end')
                ->setParameters(
                    [
                        'start' => $today_start_datetime,
                        'end' => $today_end_datetime,
                    ]
                )->getQuery()->getResult();

            $dailyServices = $em->createQueryBuilder()
                ->select('ds')
                ->from('MemberBundle:DailyServiceOperation', 'ds')
                ->where('ds.dateOperation >= :start')
                ->andWhere('ds.dateOperation <= :end')
                ->andWhere('ds.fees > :fees')
                ->setParameters(
                    [
                        'start' => $today_start_datetime,
                        'end' => $today_end_datetime,
                        'fees' => 0,
                    ]
                )->getQuery()->getResult();

                $memberRegist = $em->createQueryBuilder()
                    ->select('m')
                    ->from('MemberBundle:Member', 'm')
                    ->where('m.membershipDateCreation >= :start')
                    ->andWhere('m.membershipDateCreation <= :end')
                    ->setParameters(
                        [
                            'start' => $today_start_datetime,
                            'end' => $today_end_datetime,
                        ]
                    )->getQuery()->getResult();

                $morMemberRegist = $em->createQueryBuilder()
                    ->select('m')
                    ->from('MemberBundle:MoralMember', 'm')
                    ->where('m.membershipDateCreation >= :start')
                    ->andWhere('m.membershipDateCreation <= :end')
                    ->setParameters(
                        [
                            'start' => $today_start_datetime,
                            'end' => $today_end_datetime,
                        ]
                    )->getQuery()->getResult();




            return $this->render('report/confirmation_operation.html.twig', array(
                // 'agency' => $agency,
                'operations' => $operations,
                'loans' => $loans,
                'loanHistory' => $loanHistory,
                'dailyServices' => $dailyServices,
                'phyMember' => $memberRegist,
                'moMember' => $morMemberRegist,
                'currentDate' => $dateDebut,
                'transIncome' => $transactionIncome,
                'dateofDay' => new \DateTime('now'),
            ));
        }
    }

    /**
     * @param Request $request [contains the http request that is passed on]
     * 
     * @Route("/validate/operation", name="operation_validation")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    function saveOperationFromJSON(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);


        try{
            //first thing we get the class with the JSON format
            $accountJSON = json_decode(json_encode($request->request->get('data')), true);
            $operation = $entityManager->getRepository('AccountBundle:Operation')->find($accountJSON["idOperation"]);
            
            switch ($accountJSON["account"]) {
                case 1://Saving Account
                    $account = $operation->getSavingAccount();
                    switch ($accountJSON["type"]) {
                        case 1: //Credit operation
                            $account->setSolde($operation->getCurrentBalance());

                            //update the cash in hand
                            $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                            $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $operation->getAmount() + $operation->getDebitFees());
                            $entityManager->persist($cashInHandAccount);

                            $income = new TransactionIncome();

                            $income->setAmount($operation->getDebitFees());
                            $income->setDescription("Operation charges. Account Number: ".$account->getAccountNumber()." // Amount: ".$operation->getAmount());
                            $entityManager->persist($income);
                            break;
                        case 2://Debit operation
                            $account->setSolde($operation->getCurrentBalance());
                            //update the cash in hand
                            $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                            $cashInHandAccount->setAmount($cashInHandAccount->getAmount() - $operation->getAmount() + $operation->getDebitFees());
                            $entityManager->persist($cashInHandAccount);

                            $income = new TransactionIncome();

                            $income->setAmount($operation->getDebitFees());
                            $income->setDescription("Operation charges. Account Number: ".$account->getAccountNumber()." // Amount: ".$operation->getAmount());
                            $entityManager->persist($income);
                            break;
                        case 3://Transfer Operation
                            $account->setSolde($operation->getCurrentBalance());
                            break;
                        default:
                            break;
                    }
                    break;
                case 2://Share Account
                    $account = $operation->getShareAccount();

                    switch ($accountJSON["type"]) {
                        case 1://Credit Operation
                            $account->setSolde($operation->getCurrentBalance());
                            //update the internal account
                            $internalAccount = $entityManager->getRepository('ClassBundle:InternalAccount')->find($account->getNternalAccount()->getId());
                            $internalAccount->setAmount($internalAccount->getAmount()  + $operation->getAmount());

                            // Make records

                            $entityManager->persist($internalAccount);
                            
                            //Update the classe account
                            $classe = $entityManager->getRepository('ClassBundle:Classe')->find($internalAccount->getClasse()->getId());
                            $classe->setTotalAmount($classe->getTotalAmount() + $operation->getAmount());

                            $entityManager->persist($classe);


                            //Update the first level classe account
                            $motherClass = $entityManager->getRepository('ClassBundle:Classe')->find($classe->getClassCategory()->getId());
                            $motherClass->setTotalAmount($motherClass->getTotalAmount() + $operation->getAmount());

                            $entityManager->persist($motherClass);

                            //update the cash in hand
                            $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                            $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $operation->getAmount());

                            $entityManager->flush();
                            break;

                        case 2://Debit Operation
                            $account->setSolde($operation->getCurrentBalance());
                            //update the internal account
                            $internalAccount = $entityManager->getRepository('ClassBundle:InternalAccount')->find($account->getNternalAccount()->getId());
                            $internalAccount->setAmount($internalAccount->getAmount()  - $operation->getAmount());

                            // Make records

                            $entityManager->persist($internalAccount);
                            
                            //Update the classe account
                            $classe = $entityManager->getRepository('ClassBundle:Classe')->find($internalAccount->getClasse()->getId());
                            $classe->setTotalAmount($classe->getTotalAmount() - $operation->getAmount());

                            $entityManager->persist($classe);


                            //Update the first level classe account
                            $motherClass = $entityManager->getRepository('ClassBundle:Classe')->find($classe->getClassCategory()->getId());
                            $motherClass->setTotalAmount($motherClass->getTotalAmount() - $operation->getAmount());

                            $entityManager->persist($motherClass);

                            //update the cash in hand
                            $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                            $cashInHandAccount->setAmount($cashInHandAccount->getAmount() - $operation->getAmount());

                            $income = new TransactionIncome();

                            $income->setAmount($operation->getDebitFees());
                            $income->setDescription("Operation charges. Account Number: ".$account->getAccountNumber()." // Amount: ".$operation->getAmount());

                            $entityManager->persist($cashInHandAccount);
                            $entityManager->persist($income);
                            $entityManager->flush();
                            break;
                        case 3://Transfer Operation
                            $account->setSolde($operation->getCurrentBalance());
                            break;
                        default:
                            break;
                    }
                    break;
                case 3://Deposit Account
                    $account = $operation->getDepositAccount();
                    switch ($accountJSON["type"]) {
                        case 1://Credit Operation
                            $account->setSolde($operation->getCurrentBalance());
                            //update the cash in hand
                            $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                            $cashInHandAccount->setAmount($cashInHandAccount->getAmount() + $operation->getAmount() + $operation->getDebitFees());

                            $income = new TransactionIncome();

                            $income->setAmount($operation->getDebitFees());
                            $income->setDescription("Operation charges. Account Number: ".$account->getAccountNumber()." // Amount: ".$operation->getDebitFees());

                            $entityManager->persist($income);
                            $entityManager->persist($cashInHandAccount);
                            break;
                        case 2://Debit Operation
                            $account->setSolde($operation->getCurrentBalance());
                            //update the cash in hand
                            $cashInHandAccount  = $entityManager->getRepository('ClassBundle:InternalAccount')->find(9);
                            $cashInHandAccount->setAmount($cashInHandAccount->getAmount() - $operation->getAmount());

                            $income = new TransactionIncome();

                            $income->setAmount($operation->getDebitFees());
                            $income->setDescription("Operation charges. Account Number: ".$account->getAccountNumber()." // Amount: ".$operation->getDebitFees());
                            

                            $entityManager->persist($cashInHandAccount);
                            $entityManager->persist($income);
                            break;
                        case 3://Transfert Operation
                            $account->setSolde($operation->getCurrentBalance());
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }

            $operation->setIsConfirmed(true);
            $operation->setUserConfirmed($currentUser);
            

            /**
            *** Making record here
            **/
            
            $entityManager->persist($operation);
            $entityManager->persist($account);
            $entityManager->flush();


            $response["data"]               = $accountJSON;
            $response["optionalData"]       = json_encode($operation->getId());
            $response["success"] = true;

            return new Response(json_encode($response));
       
        }catch(Exception $ex){
            $response["success"] = false;
            return new Response(json_encode($response));
        }
    }

    /**
     * @Route("/all/situations", name="all_situations_pdf")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function generateAllSituations()
    {
        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->findOneBy([], ['id' => 'ASC']);
        $members = $em->getRepository('MemberBundle:Member')->findAll();

        $loans = $em->getRepository('AccountBundle:Loan')->findByStatus(true);

        $totalShares = $em->createQueryBuilder()
            ->select('SUM(m.share)')
            ->from('MemberBundle:Member', 'm')
            ->getQuery()
            ->getSingleScalarResult();

        $totalSavings = $em->createQueryBuilder()
            ->select('SUM(s.saving)')
            ->from('MemberBundle:Member', 's')
            ->getQuery()
            ->getSingleScalarResult();

        $totalDeposits = $em->createQueryBuilder()
            ->select('SUM(s.deposit)')
            ->from('MemberBundle:Member', 's')
            ->getQuery()
            ->getSingleScalarResult();

        $ds1 = $em->getRepository('ClassBundle:InternalAccount')->find(38);
        $ds2 = $em->getRepository('ClassBundle:InternalAccount')->find(39);
        $ds3 = $em->getRepository('ClassBundle:InternalAccount')->find(40);
        $ds4 = $em->getRepository('ClassBundle:InternalAccount')->find(41);
        $totalDailySavings = $ds1->getBalance() + $ds2->getBalance() + $ds3->getBalance() + $ds4->getBalance() ;

        $totalRegistrationFeesPM = $em->createQueryBuilder()
            ->select('SUM(m.registrationFees)')
            ->from('MemberBundle:Member', 'm')
            ->getQuery()
            ->getSingleScalarResult();

        $totalBuildingFees = $em->createQueryBuilder()
            ->select('SUM(m.buildingFees)')
            ->from('MemberBundle:Member', 'm')
            ->getQuery()
            ->getSingleScalarResult();

        //get the number of the daily collectors
        $totalCollectors = $em->createQueryBuilder()
            ->select('COUNT(u)')
            ->from('UserBundle:Utilisateur', 'u')
            ->innerJoin('UserBundle:Groupe', 'g', 'WITH','g.id = u.groupe')
            ->where('g.name = :name')
            ->orWhere('g.name = :name2')
            ->setParameters([
                'name' => 'COLLECTOR',
                'name2' => 'CASHER'
            ])
            ->getQuery()
            ->getSingleScalarResult();

        $unpaidInterest = 0;
        $loanUnpaid = 0;
        $loanPaid = 0;
        foreach ($loans as $loan) {
            //get the last element in loan history
            $lowest_remain_amount_LoanHistory = $em->createQueryBuilder()
                ->select('MIN(lh.remainAmount)')
                ->from('AccountBundle:LoanHistory', 'lh')
                ->innerJoin('AccountBundle:Loan', 'l', 'WITH','lh.loan = l.id')
                ->where('l.id = :loan')
                ->orderBy('lh.id', 'DESC')
                ->setParameter('loan', $loan)
                ->getQuery()
                ->getSingleScalarResult();

            if ($lowest_remain_amount_LoanHistory) {
                $latestLoanHistory = $em->getRepository('AccountBundle:LoanHistory')->findOneBy(
                    [
                        'remainAmount' => $lowest_remain_amount_LoanHistory,
                        'loan' => $loan
                    ],
                    ['id' => 'DESC']
                );
                $unpaidInterest += $latestLoanHistory->getUnpaidInterest();
                $loanUnpaid += $latestLoanHistory->getRemainAmount();

                $loanPaid += + ($loan->getLoanAmount() - $latestLoanHistory->getRemainAmount());
            }else{
                $loanUnpaid += $loan->getLoanAmount();
            }
        }

        $bayelleBalance = $em->getRepository('ClassBundle:InternalAccount')->find(82)->getBalance();

        $ubBalance = $em->getRepository('ClassBundle:InternalAccount')->find(76)->getBalance();
        /*Get the total cash on hand*/
        $cashOnHand= $em->getRepository('ReportBundle:GeneralLedgerBalance')
            ->findOneBy(
                [], ['id' => 'DESC' ]
            )->getBalance();
        /*total loan Interest*/
        $loanInterest = $em->getRepository('ClassBundle:InternalAccount')->find(136)->getBalance();

        $template =  $this->renderView('pdf_files/general_situation_file.html.twig', [
            'numberNumber' => count($members),
            'agency' => $agency,
            'members' => $members,
            'totalShares' => $totalShares,
            'totalSaving' => $totalSavings,
            'totalDeposit' => $totalDeposits,
            'buildingFees' => $totalBuildingFees,
            'totalRegistration' => $totalRegistrationFeesPM,
            'unpaidInterest' => $unpaidInterest,
            'loans' => count($loans),
            'loanUnpaid' => $loanUnpaid,
            'totalDailySavings' => $totalDailySavings,
            'totaCollectors' => $totalCollectors,
            'bayelleBalance' => $bayelleBalance,
            'ubBalance' => $ubBalance,
            'cashOnHand' => $cashOnHand,
            'loanInterest' => $loanInterest,
        ]);

        $title = 'General_Situation';
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'ledgers',$title, 'FI');
    }


    /**
     * @Route("/individual/ledger/pdf", name="members_individual_ledger_pdf")
     * @param Request $request
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function membersIndividualLedger(Request $request)
    {
        // Test is the user does not have the default role
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get ('router')->generate ('fos_user_security_login'));
        }

        $currentDate  = $request->get('currentDate');
        $date = explode( "/" , substr($currentDate,strrpos($currentDate," ")));

        $date  = new \DateTime($date[2]."-".$date[1]."-".$date[0]);

        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->findOneBy([], ['id' => 'ASC']);
        $members = $em->getRepository('MemberBundle:Member')->findBy([],['memberNumber' => 'ASC']);

        foreach ($members as $member) {
            $tmpLoan = $em->getRepository(Loan::class)->findOneBy([
                'physicalMember' => $member,
                'status' => true
            ]);

            $member = $em->getRepository(Operation::class)->getSituationAt($member, $date);


            if ($tmpLoan) {
                $loan = $em->getRepository(LoanHistory::class)->getActiveLoanPerMember($tmpLoan, $date);
                $member->setLoan($loan);
            }
        }

        $template =  $this->renderView('pdf_files/all_situation_file.html.twig', [
            'numberNumber' => count($members),
            'members' => $members,
            'agency' => $agency,
            'date' => $currentDate,
        ]);

        $title = 'All_Members_General_Situation';
        $html2PdfService = $this->get('app.html2pdf');
        $html2PdfService->create('L', 'A4', 'en', true, 'UTF-8', array(5, 10, 10, 10));
        return $html2PdfService->generatePdf($template, $title.'.pdf', 'ledgers',$title, 'FI');
    }
}
