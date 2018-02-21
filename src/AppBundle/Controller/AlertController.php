<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * this call manager everything that is related to alerts
 * @Route("/alert")
 */
class AlertController extends Controller{

    /**
     * 
     * @param  \Symfony\Component\HttpFoundation\Request  $request  The request
     * @return JSON
     * 
     * @Route("/list")
     * @Method("POST")
     */
    public function getAlerts(){
        $currentUserRoles   =  $this->get('security.token_storage')->getToken()->getUser()->getRoles();

        $em = $this->getDoctrine()->getManager();

        $emptyLoan = [];

        if(in_array("ROLE_ADMINISTRATOR", $currentUserRoles)
            || in_array("ROLE_MANAGER", $currentUserRoles)
            || in_array("ROLE_CASHER", $currentUserRoles)
            || in_array("ROLE_BOARD", $currentUserRoles)){
            $subQueryBuilder = $em->createQueryBuilder();
            $subQuery = $subQueryBuilder
                ->select('(lh.loan)')
                ->from('AccountBundle:LoanHistory', 'lh')
                ->getQuery()
                ->getScalarResult();

            if($subQuery){
                $queryBuilder = $em->createQueryBuilder();
                $query = $queryBuilder
                    ->select('l')
                    ->from('AccountBundle:Loan', 'l')
                    ->where($queryBuilder->expr()->notIn('l.id', ':subQuery'))
                    ->andWhere('DATE_DIFF(CURRENT_DATE(), l.dateLoan) >= :days')
                    ->andWhere('l.status = :status')
                    ->setParameter('subQuery', $subQuery)
                    ->setParameter('days', 30)
                    ->setParameter('status', true)
                    ->getQuery();
            }else{
                $queryBuilder1 = $em->createQueryBuilder();
                $query = $queryBuilder1
                    ->select('l')
                    ->from('AccountBundle:Loan', 'l')
                    ->andWhere('DATE_DIFF(CURRENT_DATE(), l.dateLoan) >= :days')
                    ->andWhere('l.status = :status')
                    ->setParameter('days', 30)
                    ->setParameter('status', true)
                    ->getQuery();

            }
                $loans = $query->getScalarResult();

            return json_encode(
                [
                    "status"    => "success",
                    "message"   => "everything went well",
                    "data"      => $loans,
                ]
            );
        }else{
            return json_encode(
                [
                    "status"    => "success",
                    "message"   => "everything went well",
                    "data"      => $emptyLoan,
                ]
                );
        }
    }


    /**
     * 
     * @param  \Symfony\Component\HttpFoundation\Request  $request  The request
     * @return JSON
     * 
     * @Route("/chart")
     * @Method("POST")
     */
    public function getChart(Request $request){

        $em = $this->getDoctrine()->getManager();
        $subQueryBuilder = $em->createQueryBuilder();
        $classes = $subQueryBuilder
            ->select('cl')
            ->from('ClassBundle:Classe', 'cl')
            ->getQuery()
            ->getScalarResult();
        return json_encode(
                [
                    "status"    => "success",
                    "message"   => "everything went well",
                    "data"      => $classes,
                ]);
    }
}