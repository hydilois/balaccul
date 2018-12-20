<?php

namespace AccountBundle\Repository;
use AccountBundle\Entity\Operation;
use MemberBundle\Entity\Member;
use UserBundle\Entity\Utilisateur;
use \Doctrine\ORM\EntityRepository;
/**
 * OperationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OperationRepository extends EntityRepository
{
    /**
     * @param Utilisateur $currentUser
     * @param Member $member
     * @param $representative
     * @param $date
     * @param $amount
     * @return Operation
     */
    public function operationSaving(Utilisateur $currentUser, Member $member, $representative, $date, $amount)
    {
        $entityManager = $this->getEntityManager();
        $operation = new Operation();
        $operation->setTypeOperation(Operation::TYPE_CASH_OUT);
        $operation->setCurrentUser($currentUser);
        $operation->setDateOperation($date);
        $operation->setAmount($amount);
        $operation->setMember($member);
        $operation->setRepresentative($representative);
        $operation->setIsSaving(true);
        $operation->setBalance($member->getSaving() - $amount);
        $member->setSaving($member->getSaving() - $amount);

        $entityManager->persist($operation);
        $entityManager->flush();
        return $operation;
    }

    /**
     * @param Utilisateur $currentUser
     * @param Member $member
     * @param $representative
     * @param $date
     * @param $amount
     * @return Operation
     */
    public function operationShare(Utilisateur $currentUser, Member $member, $representative, $date, $amount)
    {
        $entityManager = $this->getEntityManager();
        $operation = new Operation();
        $operation->setTypeOperation(Operation::TYPE_CASH_OUT);
        $operation->setCurrentUser($currentUser);
        $operation->setDateOperation($date);
        $operation->setAmount($amount);
        $operation->setMember($member);
        $operation->setRepresentative($representative);
        $operation->setIsShare(true);
        $operation->setBalance($member->getShare() - $amount);
        $member->setShare($member->getShare() - $amount);

        $entityManager->persist($operation);
        $entityManager->flush();
        return $operation;
    }

    /**
     * @param Utilisateur $currentUser
     * @param Member $member
     * @param $representative
     * @param $date
     * @param $amount
     * @return Operation
     */
    public function operationDeposit(Utilisateur $currentUser, Member $member, $representative, $date, $amount)
    {
        $entityManager = $this->getEntityManager();
        $operation = new Operation();
        $operation->setTypeOperation(Operation::TYPE_CASH_OUT);
        $operation->setCurrentUser($currentUser);
        $operation->setDateOperation($date);
        $operation->setAmount($amount);
        $operation->setMember($member);
        $operation->setRepresentative($representative);
        $operation->setIsDeposit(true);
        $operation->setBalance($member->getDeposit() - $amount);
        $member->setDeposit($member->getDeposit() - $amount);

        $entityManager->persist($operation);
        $entityManager->flush();
        return $operation;
    }

    /**
     * @param Utilisateur $currentUser
     * @param $data
     * @param Member $member
     * @param $representative
     * @param $account
     * @return Operation
     */
    public function registerGeneralOperation(Utilisateur $currentUser, $data, Member $member, $representative, $account)
    {
        $entityManager = $this->getEntityManager();
        $operation = new Operation();
        $operation->setTypeOperation(Operation::TYPE_CASH_IN);
        $operation->setCurrentUser($currentUser);
        $operation->setDateOperation($data['dateOp']);
        $operation->setAmount($data['savingsCharges']);
        $operation->setMember($member);
        $operation->setRepresentative($representative);
        $operation->setBalance($account->getBalance());
        $entityManager->persist($operation);
        $entityManager->flush();

        $entityManager->persist($operation);
        $entityManager->flush();
        return $operation;
    }

    /**
     * @param $member
     * @param $dateOperation
     * @return mixed
     */
    public function getSituationAt($member, $dateOperation)
    {
        $em = $this->getEntityManager();
        $operationShare = $em->createQueryBuilder()
            ->select('op')
            ->from('AccountBundle:Operation', 'op')
            ->innerJoin('MemberBundle:Member', 'm', 'WITH', 'op.member=m.id')
            ->where('op.dateOperation <= :date')
            ->andWhere('op.isShare = true')
            ->andWhere('m.id = :id')
            ->setParameters([
                'date' => $dateOperation,
                'id' => $member->getId()
            ])
            ->getQuery()
            ->getResult();

        if ($operationShare){
            $lastElement = array_values(array_slice($operationShare, -1))[0];
            $member->setShare($lastElement->getBalance());
        } else {
            if ($member->getMembershipDateCreation() > $dateOperation){
                $member->setShare(0);
            }
        }

        $operationSavings = $em->createQueryBuilder()
            ->select('op')
            ->from('AccountBundle:Operation', 'op')
            ->innerJoin('MemberBundle:Member', 'm', 'WITH', 'op.member=m.id')
            ->where('op.dateOperation <= :date')
            ->andWhere('op.isSaving = true')
            ->andWhere('m.id = :id')
            ->setParameters([
                'date' => $dateOperation,
                'id' => $member->getId()
            ])
            ->getQuery()
            ->getResult();

        if ($operationSavings){
            $lastElement = array_values(array_slice($operationSavings, -1))[0];
            $member->setSaving($lastElement->getBalance());
        } else {
            if ($member->getMembershipDateCreation() > $dateOperation){
                $member->setSaving(0);
            }
        }

        $operationDeposit = $em->createQueryBuilder()
            ->select('op')
            ->from('AccountBundle:Operation', 'op')
            ->innerJoin('MemberBundle:Member', 'm', 'WITH', 'op.member=m.id')
            ->where('op.dateOperation <= :date')
            ->andWhere('op.isDeposit = true')
            ->andWhere('m.id = :id')
            ->setParameters([
                'date' => $dateOperation,
                'id' => $member->getId()
            ])
            ->getQuery()
            ->getResult();

        if ($operationDeposit){
            $lastElement = array_values(array_slice($operationDeposit, -1))[0];
            $member->setDeposit($lastElement->getBalance());
        } else {
            if ($member->getMembershipDateCreation() > $dateOperation){
                $member->setDeposit(0);
            }
        }

        return $member;
    }
}
