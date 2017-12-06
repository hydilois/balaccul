<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        $physicalMembers = $em->getRepository('MemberBundle:Member')->findAll();
        $moralMembers = $em->getRepository('MemberBundle:MoralMember')->findAll();

        $savings = $em->getRepository('AccountBundle:Saving')->findAll();
        $shares = $em->getRepository('AccountBundle:Share')->findAll();
        $deposits = $em->getRepository('AccountBundle:Deposit')->findAll();
        $clients = $em->getRepository('MemberBundle:Client')->findAll();

        $reserves = $em->getRepository('ClassBundle:InternalAccount')->find(3);
        $cashInhand = $em->getRepository('ClassBundle:InternalAccount')->find(9);
        $buildingFees = $em->getRepository('ClassBundle:InternalAccount')->find(10);


        $totalShares = $em->createQueryBuilder()
            ->select('SUM(s.solde)')
            ->from('AccountBundle:Share', 's')
            ->getQuery()
            ->getSingleScalarResult();

        $totalSavings = $em->createQueryBuilder()
            ->select('SUM(s.solde)')
            ->from('AccountBundle:Saving', 's')
            ->getQuery()
            ->getSingleScalarResult();

        $totalDeposits = $em->createQueryBuilder()
            ->select('SUM(s.solde)')
            ->from('AccountBundle:Deposit', 's')
            ->getQuery()
            ->getSingleScalarResult();

        $totalDailyCollections = $em->createQueryBuilder()
            ->select('SUM(s.balance)')
            ->from('MemberBundle:Client', 's')
            ->getQuery()
            ->getSingleScalarResult();


        $totalIncome = $em->createQueryBuilder()
            ->select('SUM(t.amount)')
            ->from('ConfigBundle:TransactionIncome', 't')
            ->getQuery()
            ->getSingleScalarResult();

        $totalRegistrationFeesPM = $em->createQueryBuilder()
            ->select('SUM(m.registrationFees)')
            ->from('MemberBundle:Member', 'm')
            ->getQuery()
            ->getSingleScalarResult();

        $totalRegistrationFeesMM = $em->createQueryBuilder()
            ->select('SUM(m.registrationFees)')
            ->from('MemberBundle:MoralMember', 'm')
            ->getQuery()
            ->getSingleScalarResult();


        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'physicalMembersNumber' => count($physicalMembers),
            'moralMembersNumber' => count($moralMembers),
            'agency' => $agency,
            'clients' => $clients,
            'savingsNumber' => count($savings),
            'sharesNumber' => count($shares),
            'depositsNumber' => count($deposits),
            'totalShares' => $totalShares,
            'totalSaving' => $totalSavings,
            'totalDeposit' => $totalDeposits,
            'totalDailyCollections' => $totalDailyCollections,
            'totalIncome' => $totalIncome,
            'reserves' => $reserves,
            'cashInHand' => $cashInhand,
            'buildingFees' => $buildingFees,
            'totalRegistration' => $totalRegistrationFeesMM + $totalRegistrationFeesPM,
        ]);
    }

    /**
     * @Route("/garde", name="page_garde")
     */
    public function pageGardeAction(Request $request){

        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('ConfigBundle:Agency')->find(1);

        return $this->render('default/lock_screen.html.twig', [
            'agency' => $agency,
        ]);
    }
}