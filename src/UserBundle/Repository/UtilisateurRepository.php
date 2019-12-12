<?php

namespace UserBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * UtilisateurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UtilisateurRepository extends EntityRepository
{
	public function getActive(){

	    $delay = new \DateTime();
	    $delay->setTimestamp(strtotime('2 minutes ago'));
	 
	    $qb = $this->createQueryBuilder('u')
	        ->where('u.lastActivity > :delay')
	        ->setParameter('delay', $delay);
	 
	    return $qb->getQuery()->getResult();
	}
}
