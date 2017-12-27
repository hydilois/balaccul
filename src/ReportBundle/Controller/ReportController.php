<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ConfigBundle\Entity\TransactionIncome;

/**
 * Operation controller.
 *
 * @Route("report")
 */
class ReportController extends Controller{
    /**
     * @Route("/trialbalance", name="report_trial_balance")
     */
    public function indexAction(Request $request){
        
        // Test is the user does not have the default role
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get ('router')->generate ('fos_user_security_login'));
        }
        // replace this example code with whatever you need
        return $this->render('report/report.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * @Route("/general_ledger", name="report_general_ledger")
     */
    public function generalLedgerBalanceAction(Request $request){
        
        if ($request->getMethod() =="POST") {
            $em = $this->getDoctrine()->getManager();
            $agency = $em->getRepository('ConfigBundle:Agency')->find(1);
            $currentDate = new \DateTime('now');

            $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $currentUser    = $em->getRepository('UserBundle:Utilisateur')->find($currentUserId);
            $date  = $request->get('currentDate');
            $date = explode( "/" , substr($date,strrpos($date," ")));

            $today_enddatetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($date[2]."-".$date[1]."-".$date[0]." 23:59:59"));
            $date  = new \DateTime($date[2]."-".$date[1]."-".$date[0]);

            $operations = $em->createQueryBuilder()
                ->select('glb')
                ->from('ReportBundle:GeneralLedgerBalance', 'glb')
                ->where('glb.dateOperation <= :date')
                ->setParameters(
                    ['date' => $today_enddatetime]
                )->getQuery()
                 ->getResult();

            $html = $this->renderView('situation/general_ledger_pdf.html.twig', [
                'operations' => $operations,
                'agency' => $agency,
                'currentUser' => $currentUser,
                'date' => $currentDate,
                'endDate' => $date,
            ]);

            $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
            $html2pdf->pdf->SetAuthor('GreenSoft-Team');
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->pdf->SetTitle('General Ledger Balance');
            $response = new Response();
            $html2pdf->pdf->SetTitle('General Ledger Balance');
            $html2pdf->writeHTML($html);
            $content = $html2pdf->Output('', true);
            $response->setContent($content);
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-disposition', 'filename=GeneralLedger.pdf');
            return $response;
        }

        // replace this example code with whatever you need
        return $this->render('report/general_ledger.html.twig', [
        ]);
    }



    /**
     * member situation.
     *
     * @Route("/memberSituation", name="report_member_situation")
     * @Method("GET")
     */
    public function memberSituationAction(){

        $entityManager = $this->getDoctrine()->getManager();
        $members  = $entityManager->getRepository('MemberBundle:Member')->findBy([],['memberNumber' => 'ASC',]);

        return $this->render('report/member_situation.html.twig', array(
            'members' => $members,
        ));
    }


    /**
     * member situation on saving.
     *
     * @Route("/saving/{id}", name="member_situation_saving")
     * @Method("GET")
     */
    public function savingSituationAction($id){

        $entityManager = $this->getDoctrine()->getManager();
        $member  = $entityManager->getRepository('MemberBundle:Member')->find($id);
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->find(1);
        $currentDate = new \DateTime('now');

        $operations = $entityManager->getRepository('AccountBundle:Operation')->findBy([
            'member' => $member,
            'isSaving' => true
            ]);

        $nomMember = str_replace(' ', '_', $member->getName());
        $type = "Savings";
        $html =  $this->renderView('situation/saving_situation_file.html.twig', array(
            'agency' => $agency,
            'member' => $member,
            'type' => $type,
            'currentDate' => $currentDate,
            'operations' => $operations,
        ));

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(5, 10, 5, 10));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('Situation_'.$type.'_'.$nomMember);
        $response = new Response();
        $html2pdf->pdf->SetTitle('Situation_'.$type.'_'.$nomMember);
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Situation_'.$type.'_'.$nomMember.'.pdf');
        return $response;
    }


    /**
     * member situation on saving.
     *
     * @Route("/shares/{id}", name="member_situation_shares")
     * @Method("GET")
     */
    public function sharesSituationAction($id){

        $entityManager = $this->getDoctrine()->getManager();
        $member  = $entityManager->getRepository('MemberBundle:Member')->find($id);
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->find(1);
        $currentDate = new \DateTime('now');

        $operations = $entityManager->getRepository('AccountBundle:Operation')->findBy([
            'member' => $member,
            'isShare' => true
            ]);

        $nomMember = str_replace(' ', '_', $member->getName());
        $type = "Shares";
        $html =  $this->renderView('situation/saving_situation_file.html.twig', array(
            'agency' => $agency,
            'member' => $member,
            'type' => $type,
            'currentDate' => $currentDate,
            'operations' => $operations,
        ));

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(5, 10, 5, 10));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('Situation_'.$type.'_'.$nomMember);
        $response = new Response();
        $html2pdf->pdf->SetTitle('Situation_'.$type.'_'.$nomMember);
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Situation_'.$type.'_'.$nomMember.'.pdf');
        return $response;
    }

    /**
     * member situation on saving.
     *
     * @Route("/deposit/{id}", name="member_situation_deposit")
     * @Method("GET")
     */
    public function depositSituationAction($id){

        $entityManager = $this->getDoctrine()->getManager();
        $member  = $entityManager->getRepository('MemberBundle:Member')->find($id);
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->find(1);
        $currentDate = new \DateTime('now');

        $operations = $entityManager->getRepository('AccountBundle:Operation')->findBy([
            'member' => $member,
            'isDeposit' => true
            ]);

        $nomMember = str_replace(' ', '_', $member->getName());
        $type = "Deposits";
        $html =  $this->renderView('situation/saving_situation_file.html.twig', array(
            'agency' => $agency,
            'member' => $member,
            'type' => $type,
            'currentDate' => $currentDate,
            'operations' => $operations,
        ));

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(5, 10, 5, 10));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('Situation_'.$type.'_'.$nomMember);
        $response = new Response();
        $html2pdf->pdf->SetTitle('Situation_'.$type.'_'.$nomMember);
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Situation_'.$type.'_'.$nomMember.'.pdf');
        return $response;
    }


    /**
     * member situation on saving.
     *
     * @Route("/loans/{id}", name="member_situation_loan")
     * @Method("GET")
     */
    public function loanSituationAction($id){

        $entityManager = $this->getDoctrine()->getManager();
        $member  = $entityManager->getRepository('MemberBundle:Member')->find($id);
        $agency = $entityManager->getRepository('ConfigBundle:Agency')->find(1);
        $currentDate = new \DateTime('now');

        $loan = $entityManager->getRepository('AccountBundle:Loan')->findOneBy([
            'physicalMember' => $member,
            'status' => true
            ]);

        $loanSituations = $entityManager->getRepository('AccountBundle:LoanHistory')->findBy([
            'loan' => $loan,
            ]);

        $nomMember = str_replace(' ', '_', $member->getName());
        $type = "Loans";
        $html =  $this->renderView('situation/loan_situation_file.html.twig', array(
            'agency' => $agency,
            'member' => $member,
            'loan' => $loan,
            'type' => $type,
            'currentDate' => $currentDate,
            'loanSituations' => $loanSituations,
        ));

        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(5, 10, 5, 10));
        $html2pdf->pdf->SetAuthor('GreenSoft-Team');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->pdf->SetTitle('Situation_'.$type.'_'.$nomMember);
        $response = new Response();
        $html2pdf->pdf->SetTitle('Situation_'.$type.'_'.$nomMember);
        $html2pdf->writeHTML($html);
        $content = $html2pdf->Output('', true);
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=Situation_'.$type.'_'.$nomMember.'.pdf');
        return $response;
    }


    /**
     * @Route("/generate/trialbalance", name="trialbalance_report")
     * @Method({"GET", "POST"})
     */
    public function trialBalanceAction(Request $request){

        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $currentUser    = $em->getRepository('UserBundle:Utilisateur')->find($currentUserId);
            $date = new \DateTime('now');
            $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

            $internalAccounts = $em->getRepository('ClassBundle:InternalAccount')->findBy([], ['accountNumber' => 'ASC']);

            // $internalAccounts = $em->createQueryBuilder()
            //     ->select('ia')
            //     ->from('ClassBundle:InternalAccount', 'ia')
            //     ->where('ia.beginingBalance != :begining')
            //     ->orWhere('ia.endingBalance != :ending')
            //     ->orWhere('ia.debit != :debit')
            //     ->orWhere('ia.credit != :credit')
            //     ->setParameters(
            //         [
            //             'begining' => 0,
            //             'ending' => 0,
            //             'debit' => 0,
            //             'credit' => 0,
            //         ]
            //     )->getQuery()->getResult();

            $dateDebut = $request->get('start');
            $dateFin = $request->get('end');

            $newDateStart = explode( "/" , substr($dateDebut,strrpos($dateDebut," ")));
            $newDateEnd = explode( "/" , substr($dateFin,strrpos($dateFin," ")));

            $displayDateStart  = $newDateStart[1]."-".$newDateStart[0]."-".$newDateStart[2];
            $displayDateEnd  = $newDateEnd[1]."-".$newDateEnd[0]."-".$newDateEnd[2];

                $html =  $this->renderView('report/trialbalance_file.html.twig', array(
                    'displayDateStart' => $displayDateStart,
                    'displayDateEnd' => $displayDateEnd,
                    'currentUser' => $currentUser,
                    'date' => $date,
                    'agency' => $agency,
                    'internalAccounts' => $internalAccounts,
                ));

                $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 15));
                $html2pdf->pdf->SetAuthor('GreenSoft-Team');
                $html2pdf->pdf->SetDisplayMode('real');
                $html2pdf->pdf->SetTitle('Trial Balance');
                $response = new Response();
                $html2pdf->pdf->SetTitle('Trial Balance');
                $html2pdf->writeHTML($html);
                $content = $html2pdf->Output('', true);
                $response->setContent($content);
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-disposition', 'filename=Trial_Balance.pdf');
                return $response;
            }
        }


        /**
         * @Route("/account/history", name="accounthistoryreport")
         * @Method({"GET", "POST"})
         */
        public function accountHistoryAction(Request $request){
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

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

                $dateEnd11 = $dateEnd1->add(new \DateInterval('P1D'));

                $displayDateStart  = $newDateStart[1]."-".$newDateStart[0]."-".$newDateStart[2];
                $displayDateEnd  = $newDateEnd[1]."-".$newDateEnd[0]."-".$newDateEnd[2];
 
                switch ($accountType) {
                    case 1:
                        $qb->select('op')
                            ->from('AccountBundle:Operation', 'op')
                            ->innerJoin('AccountBundle:Saving', 'sa','WITH','op.savingAccount = sa.id')
                            ->where('op.dateOperation BETWEEN :date1 AND :date2')
                            ->andWhere('sa.id =:identifiant')
                            ->setParameters(
                                [
                                'date1' => $dateStart1->format('Y-m-d'),
                                'date2' => $dateEnd1->format('Y-m-d'),
                                'identifiant' => $accountNumber,
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
                            ->andWhere('sa.id =:identifiant')
                            ->setParameters(
                                [
                                'date1' => $dateStart1->format('Y-m-d'),
                                'date2' => $dateEnd1->format('Y-m-d'),
                                'identifiant' => $accountNumber,
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
                            ->andWhere('sa.id =:identifiant')
                            ->setParameters(
                                [
                                'date1' => $dateStart1->format('Y-m-d'),
                                'date2' => $dateEnd1->format('Y-m-d'),
                                'identifiant' => $accountNumber,
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
     * @Route("/daily/history", name="dailyreport")
     * @Method({"GET", "POST"})
     */
    public function dailyReportAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);


        if ($request->getMethod() == 'POST') {

            $dateDebut = $request->get('currentDate');

            $newDateStart = explode( "/" , substr($dateDebut,strrpos($dateDebut," ")));

            $today_startdatetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($newDateStart[2]."-".$newDateStart[1]."-".$newDateStart[0]." 00:00:00"));
            $today_enddatetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($newDateStart[2]."-".$newDateStart[1]."-".$newDateStart[0]." 23:59:59"));

            $operations = $em->createQueryBuilder()
                ->select('op')
                ->from('AccountBundle:Operation', 'op')
                ->where('op.dateOperation >= :start')
                ->andWhere('op.dateOperation <= :end')
                ->setParameters(
                    [
                        'start' => $today_startdatetime,
                        'end' => $today_enddatetime,
                    ]
                )->getQuery()->getResult();


            $loanHistory = $em->createQueryBuilder()
                ->select('lh')
                ->from('AccountBundle:LoanHistory', 'lh')
                ->where('lh.dateOperation >= :start')
                ->andWhere('lh.dateOperation <= :end')
                ->setParameters(
                    [
                        'start' => $today_startdatetime,
                        'end' => $today_enddatetime,
                    ]
                )->getQuery()->getResult();

            $transactionIncome = $em->createQueryBuilder()
                ->select('ti')
                ->from('ConfigBundle:TransactionIncome', 'ti')
                ->where('ti.transactionDate >= :start')
                ->andWhere('ti.transactionDate <= :end')
                ->setParameters(
                    [
                        'start' => $today_startdatetime,
                        'end' => $today_enddatetime,
                    ]
                )->getQuery()->getResult();

            $loans = $em->createQueryBuilder()
                ->select('l')
                ->from('AccountBundle:Loan', 'l')
                ->where('l.dateLoan >= :start')
                ->andWhere('l.dateLoan <= :end')
                ->setParameters(
                    [
                        'start' => $today_startdatetime,
                        'end' => $today_enddatetime,
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
                        'start' => $today_startdatetime,
                        'end' => $today_enddatetime,
                        'fees' => 0,
                    ]
                )->getQuery()->getResult();

                $physMemberRegist = $em->createQueryBuilder()
                    ->select('m')
                    ->from('MemberBundle:Member', 'm')
                    ->where('m.membershipDateCreation >= :start')
                    ->andWhere('m.membershipDateCreation <= :end')
                    ->setParameters(
                        [
                            'start' => $today_startdatetime,
                            'end' => $today_enddatetime,
                        ]
                    )->getQuery()->getResult();

                $morMemberRegist = $em->createQueryBuilder()
                    ->select('m')
                    ->from('MemberBundle:MoralMember', 'm')
                    ->where('m.membershipDateCreation >= :start')
                    ->andWhere('m.membershipDateCreation <= :end')
                    ->setParameters(
                        [
                            'start' => $today_startdatetime,
                            'end' => $today_enddatetime,
                        ]
                    )->getQuery()->getResult();




            $html =  $this->renderView('report/daily_history_file.html.twig', array(
                'agency' => $agency,
                'operations' => $operations,
                'loans' => $loans,
                'loanHistory' => $loanHistory,
                'dailyServices' => $dailyServices,
                'phyMember' => $physMemberRegist,
                'moMember' => $morMemberRegist,
                'currentDate' => $dateDebut,
                'transIncome' => $transactionIncome,
                'dateofDay' => new \DateTime('now'),
            ));


            $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(5, 10, 5, 10));
            $html2pdf->pdf->SetAuthor('GreenSoft-Team');
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->pdf->SetTitle('Daily_Report');
            $response = new Response();
            $html2pdf->pdf->SetTitle('DailyReport');
            $html2pdf->writeHTML($html);
            $content = $html2pdf->Output('', true);
            $response->setContent($content);
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-disposition', 'filename=Daily_report.pdf');
            return $response;
        }
    }

    /**
     * @Route("/daily/confirmation", name="operation_confirmation")
     * @Method({"GET", "POST"})
     */
    public function dailyReportConfirmationAction(Request $request){
        $em = $this->getDoctrine()->getManager();


        if ($request->getMethod() == 'POST') {

            $dateDebut = $request->get('dateOperation');

            $newDateStart = explode( "/" , substr($dateDebut,strrpos($dateDebut," ")));

            $today_startdatetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($newDateStart[2]."-".$newDateStart[1]."-".$newDateStart[0]." 00:00:00"));
            $today_enddatetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($newDateStart[2]."-".$newDateStart[1]."-".$newDateStart[0]." 23:59:59"));

            $operations = $em->createQueryBuilder()
                ->select('op')
                ->from('AccountBundle:Operation', 'op')
                ->where('op.dateOperation >= :start')
                ->andWhere('op.dateOperation <= :end')
                ->andWhere('op.isConfirmed = FALSE')
                ->setParameters(
                    [
                        'start' => $today_startdatetime,
                        'end' => $today_enddatetime,
                    ]
                )->getQuery()->getResult();


            $loanHistory = $em->createQueryBuilder()
                ->select('lh')
                ->from('AccountBundle:LoanHistory', 'lh')
                ->where('lh.dateOperation >= :start')
                ->andWhere('lh.dateOperation <= :end')
                ->setParameters(
                    [
                        'start' => $today_startdatetime,
                        'end' => $today_enddatetime,
                    ]
                )->getQuery()->getResult();

            $transactionIncome = $em->createQueryBuilder()
                ->select('ti')
                ->from('ConfigBundle:TransactionIncome', 'ti')
                ->where('ti.transactionDate >= :start')
                ->andWhere('ti.transactionDate <= :end')
                ->setParameters(
                    [
                        'start' => $today_startdatetime,
                        'end' => $today_enddatetime,
                    ]
                )->getQuery()->getResult();

            $loans = $em->createQueryBuilder()
                ->select('l')
                ->from('AccountBundle:Loan', 'l')
                ->where('l.dateLoan >= :start')
                ->andWhere('l.dateLoan <= :end')
                ->setParameters(
                    [
                        'start' => $today_startdatetime,
                        'end' => $today_enddatetime,
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
                        'start' => $today_startdatetime,
                        'end' => $today_enddatetime,
                        'fees' => 0,
                    ]
                )->getQuery()->getResult();

                $physMemberRegist = $em->createQueryBuilder()
                    ->select('m')
                    ->from('MemberBundle:Member', 'm')
                    ->where('m.membershipDateCreation >= :start')
                    ->andWhere('m.membershipDateCreation <= :end')
                    ->setParameters(
                        [
                            'start' => $today_startdatetime,
                            'end' => $today_enddatetime,
                        ]
                    )->getQuery()->getResult();

                $morMemberRegist = $em->createQueryBuilder()
                    ->select('m')
                    ->from('MemberBundle:MoralMember', 'm')
                    ->where('m.membershipDateCreation >= :start')
                    ->andWhere('m.membershipDateCreation <= :end')
                    ->setParameters(
                        [
                            'start' => $today_startdatetime,
                            'end' => $today_enddatetime,
                        ]
                    )->getQuery()->getResult();




            return $this->render('report/confirmation_operation.html.twig', array(
                // 'agency' => $agency,
                'operations' => $operations,
                'loans' => $loans,
                'loanHistory' => $loanHistory,
                'dailyServices' => $dailyServices,
                'phyMember' => $physMemberRegist,
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
     */
    function saveOperationFromJSON(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');

        // Get the current user connected
        $currentUserId  = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUser    = $entityManager->getRepository('UserBundle:Utilisateur')->find($currentUserId);


        try{
            //first thing we get the classe with the JSON format
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
                        case 3://Transfert Operation
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

            $logger("AN ERROR OCCURED");
            $response["success"] = false;
            return new Response(json_encode($response));
        }
    }
}
