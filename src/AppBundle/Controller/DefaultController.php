<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Yaml\Yaml;

class DefaultController extends Controller{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request){
        // Test is the user does not have the default role
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get ('router')->generate ('fos_user_security_login'));
        }
        
        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);
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
        $totaCollectors = $em->createQueryBuilder()
            ->select('COUNT(u)')
            ->from('UserBundle:Utilisateur', 'u')
            ->innerJoin('UserBundle:Groupe', 'g', 'WITH','g.id = u.groupe')
            ->where('g.name = :name')
            ->setParameter('name', 'COLLECTOR')
            ->getQuery()
            ->getSingleScalarResult();
        
        $unpaidInterest = 0;
        $loanUnpaid = 0;
        $loanPaid = 0;
        $count = 0;
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
                $count = $count + 1;
            }else{
                $loanUnpaid += $loan->getLoanAmount();
            }
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'numberNumber' => count($members),
            'agency' => $agency,
            'members' => $members,
            'totalShares' => $totalShares,
            'totalSaving' => $totalSavings,
            'totalDeposit' => $totalDeposits,
            'buildingFees' => $totalBuildingFees,
            'totalRegistration' => $totalRegistrationFeesPM,
            'unpaidInterest' => $unpaidInterest,
            'loans' => $loans,
            'loanUnpaid' => $loanUnpaid,
            'totalDailySavings' => $totalDailySavings,
            'totaCollectors' => $totaCollectors,
        ]);
    }

    /**
     * @Route("/lock_screen", name="lock_screen")
     */
    public function pageGardeAction(Request $request){

        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

        return $this->render('default/lock_screen.html.twig', [
            'agency' => $agency,
        ]);
    }

    /**
     * @Route("/backup", name="database_backup")
     * @Method({"GET", "POST"})
     */
    public function databaseBackupAction(Request $request){
        $parametersYml = Yaml::parse(file_get_contents($this->container->get('kernel')->getRootDir() .'/config/parameters.yml'));
        $dbHost = $parametersYml['parameters']['database_host'];
        $dbUsername = $parametersYml['parameters']['database_user'];
        $dbPassword = $parametersYml['parameters']['database_password'];
        $dbName = $parametersYml['parameters']['database_name'];
        $logger = $this->get('logger');
        try{
            // $loanJSON = json_decode(json_encode($request->request->get('data')), true);
                $this->backupDatabaseTables($dbHost, $dbUsername, $dbPassword, $dbName);
                return json_encode([
                    "message" => "The backup of the the system is done successfully", 
                    // "params" => $loanJSON,
                    "status" => "success"
                ]);
           
            }catch(Exception $ex){
                $logger("AN ERROR OCCURED");
                return json_encode([
                    "status" => "failed"
                ]);
            }
        }



    public function backupDatabaseTables($dbHost,$dbUsername,$dbPassword,$dbName,$tables = '*'){
        //connect & select the database
        $db = new \mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 

        //get all of the tables
        if($tables == '*'){
            $tables = array();
            $result = $db->query("SHOW TABLES");
            while($row = $result->fetch_row()){
                $tables[] = $row[0];
            }
        }else{
            $tables = is_array($tables)?$tables:explode(',',$tables);
        }

        //loop through the tables
            $return = "";
        foreach($tables as $table){
            $result = $db->query("SELECT * FROM $table");
            $numColumns = $result->field_count;
            $return .= "DROP TABLE $table;";

            $result2 = $db->query("SHOW CREATE TABLE $table");
            $row2 = $result2->fetch_row();

            $return .= "\n\n".$row2[1].";\n\n";

            for($i = 0; $i < $numColumns; $i++){
                while($row = $result->fetch_row()){
                    $return .= "INSERT INTO $table VALUES(";
                    for($j=0; $j < $numColumns; $j++){
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
                        if (isset($row[$j])) { $return .= '"'.$row[$j].'"' ; } else { $return .= '""'; }
                        if ($j < ($numColumns-1)) { $return.= ','; }
                    }
                    $return .= ");\n";
                }
            }
            $return .= "\n\n\n";
        }

        $date = new \Datetime('now');
        $path = 'assets/database/db-backup-'.$date->format('d-m-Y-H:m:s').'.sql';

        //save file
        $handle = fopen($path,'w+');
        fwrite($handle,$return);
        chmod($path, 0777);
        fclose($handle);
    }
}