<?php

namespace AppBundle\Controller;

use AccountBundle\Service\DatabaseBackupManager;
use AccountBundle\Service\FileUploader;
use ReportBundle\Entity\GeneralLedgerBalance;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->findOneBy([],['id' => 'ASC']);
        $members = $em->getRepository('MemberBundle:Member')->findAll();

        $loans = $em->getRepository('AccountBundle:Loan')->findByStatus(true);

        $fullyPaidShares = $em->createQueryBuilder()
            ->select('SUM(m.share)')
            ->from('MemberBundle:Member', 'm')
            ->where('m.share >= 20000')
            ->getQuery()
            ->getSingleScalarResult();

        $fullyPaidSharesMembers = $em->createQueryBuilder()
            ->select('m')
            ->from('MemberBundle:Member', 'm')
            ->where('m.share >= 20000')
            ->getQuery()
            ->getResult();

        $partialPaidShares = $em->createQueryBuilder()
            ->select('SUM(m.share)')
            ->from('MemberBundle:Member', 'm')
            ->where('m.share < 20000')
            ->getQuery()
            ->getSingleScalarResult();

        $partialPaidSharesMembers = $em->createQueryBuilder()
            ->select('m')
            ->from('MemberBundle:Member', 'm')
            ->where('m.share < 20000')
            ->getQuery()
            ->getResult();

        $savings = $em->createQueryBuilder()
            ->select('SUM(s.saving)')
            ->from('MemberBundle:Member', 's')
            ->getQuery()
            ->getSingleScalarResult();

        $deposits = $em->createQueryBuilder()
            ->select('SUM(s.deposit)')
            ->from('MemberBundle:Member', 's')
            ->getQuery()
            ->getSingleScalarResult();

        $depositsMembers = $em->createQueryBuilder()
            ->select('m')
            ->from('MemberBundle:Member', 'm')
            ->where('m.deposit >= 10000')
            ->getQuery()
            ->getResult();
            
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
        $collectors = $em->createQueryBuilder()
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

        if (intval($lowest_remain_amount_LoanHistory) >= 0 && $lowest_remain_amount_LoanHistory != null) {
            $latestLoanHistory = $em->getRepository('AccountBundle:LoanHistory')->findOneBy(
                [
                    'remainAmount' => intval($lowest_remain_amount_LoanHistory),
                    'loan' => $loan
                ],
                ['id' => 'DESC']
            );
            if ($latestLoanHistory){
                $unpaidInterest += $latestLoanHistory->getUnpaidInterest();
                $loanUnpaid += $latestLoanHistory->getRemainAmount();
                $loanPaid += ($loan->getLoanAmount() - $latestLoanHistory->getRemainAmount());
            }
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

        return $this->render('default/index.html.twig', [
            'numberNumber' => count($members),
            'agency' => $agency,
            'members' => $members,
            'fullyPaidShares' => $fullyPaidShares,
            'partialPaidShares' => $partialPaidShares,
            'totalSaving' => $savings,
            'totalDeposit' => $deposits,
            'depositMembers' => count($depositsMembers),
            'buildingFees' => $totalBuildingFees,
            'totalRegistration' => $totalRegistrationFeesPM,
            'unpaidInterest' => $unpaidInterest,
            'loans' => $loans,
            'loanUnpaid' => $loanUnpaid,
            'totalDailySavings' => $totalDailySavings,
            'totaCollectors' => $collectors,
            'bayelleBalance' => $bayelleBalance,
            'ubBalance' => $ubBalance,
            'cashOnHand' => $cashOnHand,
            'loanInterest' => $loanInterest,
            'fullyPaidSharesMembers' => count($fullyPaidSharesMembers),
            'partialPaidSharesMembers' => count($partialPaidSharesMembers),
        ]);
    }

    /**
     * @Route("/lock_screen", name="lock_screen")
     * @return Response
     */
    public function lockScreen(){

        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

        return $this->render('default/lock_screen.html.twig', [
            'agency' => $agency,
        ]);
    }


    /**
     * @Route("/dump", name="database_dump")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param DatabaseBackupManager $manager
     * @param FileUploader $fileUploader
     * @return string
     */
    public function databaseDump(DatabaseBackupManager $manager, FileUploader $fileUploader){
        try{
            $date = date("Y-m-d_H:i:s");
            $db_user = $this->getParameter('database_user');
            $db_pass = $this->getParameter('database_password');
            $db_name = $this->getParameter('database_name');
            $manager->backup($db_user, $db_pass, $db_name, $fileUploader);

                return json_encode([
                "message" => "The backup of the the system is done successfully",
                "status" => "success",
                "optionalDate" => $date
            ]);

            }catch(\Exception $ex){
                return json_encode([
                    "status" => "failed",
                ]);
            }
    }

    /**
     * @param Request $request [contains the http request that is passed on]
     *
     * @param DatabaseBackupManager $manager
     * @param FileUploader $fileUploader
     * @return Response
     * @Route("/back/operation/operation", name="back_office_operation")
     * @Method({"GET", "POST"})
     */
    function updateGeneralLedgerBalance(Request $request, DatabaseBackupManager $manager, FileUploader $fileUploader)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $db_user = $this->getParameter('database_user');
            $db_pass = $this->getParameter('database_password');
            $db_name = $this->getParameter('database_name');
            $manager->backup($db_user, $db_pass, $db_name, $fileUploader,  'Back Office Operation');

            $data = $request->request->all();

            $operations = $entityManager->getRepository(GeneralLedgerBalance::class)->getGLBListFromId(intval($data['reference']));
            foreach ($operations as $operation) {
                /* @var $operation GeneralLedgerBalance */
                $operation->setBalance($operation->getBalance() + intval($data['amount']));
                $entityManager->flush();
            }

            return $this->redirectToRoute('report_general_ledger');
        }
            return $this->render('operation/admin/back_office_transactions.html.twig', [

            ]);
    }
}