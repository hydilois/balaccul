<?php

namespace ReportBundle\Repository;

use AccountBundle\Entity\Operation;
use ClassBundle\Entity\InternalAccount;
use \Doctrine\ORM\EntityRepository;
use MemberBundle\Entity\Member;
use ReportBundle\Entity\GeneralLedgerBalance;
use UserBundle\Entity\Utilisateur;

/**
 * GeneralLedgerBalanceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GeneralLedgerBalanceRepository extends EntityRepository
{
    /**
     * @param $amount
     * @param Utilisateur $currentUser
     * @param $dateOperation
     * @param InternalAccount $account
     * @param $representative
     * @param Member $member
     * @return GeneralLedgerBalance
     */
    public function registerGBLCashIn($amount ,Utilisateur $currentUser, $dateOperation, InternalAccount $account, $representative, Member $member = null)
    {
        // Update the general Ledger
        $entityManager = $this->getEntityManager();
        $ledgerBalance = new GeneralLedgerBalance();
        $ledgerBalance->setDebit($amount);
        $ledgerBalance->setCurrentUser($currentUser);
        $ledgerBalance->setDateOperation($dateOperation);
        $latestEntryGBL = $this->findLastRecord($dateOperation);
        if ($latestEntryGBL) {
            $ledgerBalance->setBalance($latestEntryGBL->getBalance() + $amount);
        }else{
            $ledgerBalance->setBalance($amount);
        }
        $ledgerBalance->setTypeOperation(Operation::TYPE_CASH_IN);
        $ledgerBalance->setAccount($account);
        $ledgerBalance->setAccountTitle($account->getAccountName());
        $ledgerBalance->setRepresentative($representative);
        if($member) {
            $ledgerBalance->setMember($member);
        }
        $ledgerBalance->setAccountBalance($account->getBalance());
        /*Make record*/
        $entityManager->persist($ledgerBalance);

        return $ledgerBalance;
    }

    /**
     * @param $amount
     * @param Utilisateur $currentUser
     * @param $dateOperation
     * @param InternalAccount $account
     * @param $representative
     * @return GeneralLedgerBalance
     */
    public function registerGBLCashOut($amount ,Utilisateur $currentUser, $dateOperation, InternalAccount $account, $representative)
    {
        $entityManager = $this->getEntityManager();
        $ledgerBalance = new GeneralLedgerBalance();
        $ledgerBalance->setCredit($amount);
        $ledgerBalance->setCurrentUser($currentUser);
        $ledgerBalance->setDateOperation($dateOperation);
        $latestEntryGBL = $this->findLastRecord($dateOperation);
        if ($latestEntryGBL) {
            $ledgerBalance->setBalance($latestEntryGBL->getBalance() - $amount);
        }else{
            $ledgerBalance->setBalance($amount);
        }
        $ledgerBalance->setTypeOperation(Operation::TYPE_CASH_OUT);
        $ledgerBalance->setAccount($account);
        $ledgerBalance->setAccountTitle($account->getAccountName());
        $ledgerBalance->setRepresentative($representative);
        $ledgerBalance->setAccountBalance($account->getBalance());
        /*Make record*/
        $entityManager->persist($ledgerBalance);

        return $ledgerBalance;
    }

    /**
     * @param $amount
     * @param Utilisateur $currentUser
     * @param $dateOperation
     * @param InternalAccount $account
     * @param $representative
     * @param $type
     * @return GeneralLedgerBalance
     */
    public function registerSpecialGBLTransaction($amount ,Utilisateur $currentUser, $dateOperation, InternalAccount $account, $representative, $type)
    {
        $entityManager = $this->getEntityManager();
        $ledgerBalance = new GeneralLedgerBalance();
        $ledgerBalance->setCurrentUser($currentUser);
        $ledgerBalance->setDateOperation($this->formatDate($dateOperation));

        if ($type == "cr"){
            $ledgerBalance->setCredit($amount);
            $latestEntryGBL = $this->findLastGBLRecordOfDay($dateOperation);
            if ($latestEntryGBL) {
                $ledgerBalance->setBalance($latestEntryGBL->getBalance() - $amount);
            }else{
                $ledgerBalance->setBalance($amount);
            }
            $ledgerBalance->setTypeOperation(Operation::TYPE_CASH_OUT);
        }else{
            $ledgerBalance->setDebit($amount);
            $latestEntryGBL = $this->findLastGBLRecordOfDay($dateOperation);
            if ($latestEntryGBL) {
                $ledgerBalance->setBalance($latestEntryGBL->getBalance() + $amount);
            }else{
                $ledgerBalance->setBalance($amount);
            }
            $ledgerBalance->setTypeOperation(Operation::TYPE_CASH_IN);
        }
        $ledgerBalance->setAccount($account);
        $ledgerBalance->setAccountTitle($account->getAccountName());


        $ledgerBalance->setRepresentative($representative);
        $ledgerBalance->setAccountBalance($account->getBalance());
        /*Make record*/
        $entityManager->persist($ledgerBalance);

        return $ledgerBalance;
    }

    /**
     * @param $amount
     * @param $date_operation
     * @param $type
     */
    public function updateGLB($amount, $date_operation, $type)
    {
        $date = $this->formatDate($date_operation);
        $operations = $this->createQueryBuilder('glb')
                    ->where('glb.dateOperation > :date')
                    ->setParameters(['date' => $date])
                    ->orderBy('glb.id', 'ASC')
                    ->getQuery()
                    ->getResult();
        if ($operations) {
            if ($type == "cr"){
                foreach ($operations as $operation){
                    $operation->setBalance($operation->getBalance() - $amount);
                }
            }else{
                foreach ($operations as $operation){
                    $operation->setBalance($operation->getBalance() + $amount);
                }
            }
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @return mixed
     */
    public function findLastGBLRecord() {
        $qb = $this->createQueryBuilder('gbl');
        $qb->setMaxResults( 1 );
        $qb->orderBy('gbl.id', 'DESC');

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param $dateOperation
     * @return mixed
     */
    private function findLastGBLRecordOfDay($dateOperation) {
        $date = explode("/", substr($dateOperation, strrpos($dateOperation, " ")));

        $today_start_datetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($date[2] . "-" . $date[1] . "-" . $date[0] . " 00:00:00"));
        $today_end_datetime = \DateTime::createFromFormat("Y-m-d H:i:s", date($date[2] . "-" . $date[1] . "-" . $date[0] . " 23:59:59"));
        $operations = $this->createQueryBuilder('glb')
            ->where('glb.dateOperation >= :date')
            ->andWhere('glb.dateOperation <= :date_end')
            ->setParameters([
                'date' => $today_start_datetime,
                'date_end' => $today_end_datetime
            ])
            ->getQuery()
            ->getResult();

        if ($operations){
            $lastOperation = end($operations);
            return $lastOperation;
        }else{
            $operations1 = $this->getEntityManager()->createQueryBuilder()
                ->select('glb')
                ->from('ReportBundle:GeneralLedgerBalance', 'glb')
                ->andWhere('glb.dateOperation <= :date_end')
                ->setParameters([
                    'date_end' => $today_end_datetime
                ])
                ->orderBy('glb.dateOperation', 'ASC')
                ->getQuery()
                ->getResult();
            if ($operations1){
                $lastOperation = end($operations1);
                return $lastOperation;
            }
        }
        return null;
    }

    /**
     * @param $dateOperation
     * @return mixed
     */
    private function findLastRecord($dateOperation)
    {
        $today_start_datetime = new \DateTime($dateOperation->format('Y-m-d') .' 00:00:00');
        $today_end_datetime = new \DateTime($dateOperation->format('Y-m-d') .' 23:59:59');
        $operations = $this->createQueryBuilder('glb')
            ->where('glb.dateOperation >= :date')
            ->andWhere('glb.dateOperation <= :date_end')
            ->setParameters([
                'date' => $today_start_datetime,
                'date_end' => $today_end_datetime
            ])
            ->getQuery()
            ->getResult();

        if ($operations){
            $lastOperation = end($operations);
            return $lastOperation;
        }else{
            $operations1 = $this->getEntityManager()->createQueryBuilder()
                ->select('glb')
                ->from('ReportBundle:GeneralLedgerBalance', 'glb')
                ->andWhere('glb.dateOperation <= :date_end')
                ->setParameters([
                    'date_end' => $today_end_datetime
                ])
                ->orderBy('glb.id', 'ASC')
                ->getQuery()
                ->getResult();
            if ($operations1){
                $lastOperation = end($operations1);
                return $lastOperation;
            }
        }
        return null;
    }

    /**
     * @param $dateOperation
     * @return mixed
     */
    private function formatDate($dateOperation) {
        $date = explode("/", substr($dateOperation, strrpos($dateOperation, " ")));
        $date = \DateTime::createFromFormat("Y-m-d H:i:s", date($date[2] . "-" . $date[1] . "-" . $date[0] . " 00:00:00"));
        return $date;
    }

    /**
     * @param Utilisateur $currentUser
     * @param Member $member
     * @param $representative
     * @param $date
     * @param $amount
     * @param InternalAccount $savingAccount
     * @return bool
     */
    public function recordGeneraLB(Utilisateur $currentUser, Member $member, $representative, $date, $amount, InternalAccount $savingAccount)
    {
        $entityManager = $this->getEntityManager();
        $ledgerBalance = new GeneralLedgerBalance();
        $ledgerBalance->setTypeOperation(Operation::TYPE_CASH_OUT);
        $ledgerBalance->setCurrentUser($currentUser);
        $ledgerBalance->setDateOperation($date);
        $ledgerBalance->setCredit($amount);
        $latestEntryGBL = $this->findLastRecord($date);
        if ($latestEntryGBL) {
            $ledgerBalance->setBalance($latestEntryGBL->getBalance() - $amount);
        }else{
            $ledgerBalance->setBalance($amount);
        }
        $ledgerBalance->setAccount($savingAccount);
        $ledgerBalance->setRepresentative($representative);
        $ledgerBalance->setAccountBalance($savingAccount->getBalance());
        $ledgerBalance->setAccountTitle($savingAccount->getAccountName()." A/C_".$member->getMemberNumber());
        $ledgerBalance->setMember($member);

        $entityManager->persist($ledgerBalance);
        $entityManager->flush();
        return true;
    }

    /**
     * @param Utilisateur $currentUser
     * @param Member $member
     * @param $representative
     * @param $date
     * @param $amount
     * @param InternalAccount $account
     * @return bool
     */
    public function recordGeneraLBIn(Utilisateur $currentUser, Member $member, $representative, $date, $amount, InternalAccount $account)
    {
        $entityManager = $this->getEntityManager();
        $ledgerBalance = new GeneralLedgerBalance();
        $ledgerBalance->setTypeOperation(Operation::TYPE_CASH_IN);
        $ledgerBalance->setCurrentUser($currentUser);
        $ledgerBalance->setDateOperation($date);
        $ledgerBalance->setDebit($amount);
        $latestEntryGBL = $this->findLastRecord($date);
        if ($latestEntryGBL) {
            $ledgerBalance->setBalance($latestEntryGBL->getBalance() + $amount);
        }else{
            $ledgerBalance->setBalance($amount);
        }
        $ledgerBalance->setAccount($account);
        $ledgerBalance->setRepresentative($representative);
        $ledgerBalance->setAccountBalance($account->getBalance());
        $ledgerBalance->setAccountTitle($account->getAccountName()." A/C_".$member->getMemberNumber());
        $ledgerBalance->setMember($member);

        $entityManager->persist($ledgerBalance);

        $entityManager->flush();
        return true;
    }

    /**
     * @param $dateOperation
     * @return int
     */
    public function getGLBHistoryCashOnHand($dateOperation)
    {
        $em = $this->getEntityManager();
        $operationCashOnHand = $em->createQueryBuilder()
            ->select('gl')
            ->from('ReportBundle:GeneralLedgerBalance', 'gl')
            ->where('gl.dateOperation <= :date')
            ->setParameters([
                'date' => $dateOperation,
            ])
            ->getQuery()
            ->getResult();

        if ($operationCashOnHand) {
            $lastElement = array_values(array_slice($operationCashOnHand, -1))[0];
            return $lastElement->getBalance();
        } else {
            return 0;
        }
    }

    /**
     * @param $dateOperation
     * @param InternalAccount $account
     * @return int
     */
    public function getGLBHistoryUB($dateOperation, InternalAccount $account)
    {
        $em = $this->getEntityManager();
        $operationCashUB = $em->createQueryBuilder()
            ->select('gl')
            ->from('ReportBundle:GeneralLedgerBalance', 'gl')
            ->innerJoin('ClassBundle:InternalAccount', 'ia', 'WITH', 'ia.id = gl.account')
            ->where('gl.dateOperation <= :date')
            ->andWhere('ia.id = :accountId')
            ->setParameters([
                'date' => $dateOperation,
                'accountId' => $account->getId(),
            ])
            ->getQuery()
            ->getResult();

        if ($operationCashUB) {
            $lastElement = array_values(array_slice($operationCashUB, -1))[0];
            return $lastElement->getBalance();
        } else {
            return $account->getBalance();
        }
    }


    /**
     * @param $dateOperation
     * @param InternalAccount $account
     * @return int
     */
    public function getGLBHistoryBayelle($dateOperation, InternalAccount $account)
    {
        $em = $this->getEntityManager();
        $operationCashBayelle = $em->createQueryBuilder()
            ->select('gl')
            ->from('ReportBundle:GeneralLedgerBalance', 'gl')
            ->innerJoin('ClassBundle:InternalAccount', 'ia', 'WITH', 'ia.id = gl.account')
            ->where('gl.dateOperation <= :date')
            ->andWhere('ia.id = :accountId')
            ->setParameters([
                'date' => $dateOperation,
                'accountId' => $account->getId(),
            ])
            ->getQuery()
            ->getResult();

        if ($operationCashBayelle) {
            $lastElement = array_values(array_slice($operationCashBayelle, -1))[0];
            return $lastElement->getAccountBalance();
        } else {
            return $account->getBalance();
        }
    }

    /**
     * @param $dateOperation
     * @param InternalAccount $account
     * @return int
     */
    public function getGLBHistoryInternalAccount($dateOperation, InternalAccount $account)
    {
        $em = $this->getEntityManager();
        $operationGeneralReserve = $em->createQueryBuilder()
            ->select('gl')
            ->from('ReportBundle:GeneralLedgerBalance', 'gl')
            ->innerJoin('ClassBundle:InternalAccount', 'ia', 'WITH', 'ia.id = gl.account')
            ->where('gl.dateOperation <= :date')
            ->andWhere('ia.id = :accountId')
            ->setParameters([
                'date' => $dateOperation,
                'accountId' => $account->getId(),
            ])
            ->getQuery()
            ->getResult();

        if ($operationGeneralReserve) {
            $lastElement = array_values(array_slice($operationGeneralReserve, -1))[0];
            return $lastElement->getAccountBalance();
        } else {
            return $account->getBalance();
        }
    }

    /**
     * @param $id
     * @return array GeneralLedgerBalance|null
     */
    public function getGLBFromId($id)
    {
        $em = $this->getEntityManager();
        $operations = $em->createQueryBuilder()
            ->select('gl')
            ->from('ReportBundle:GeneralLedgerBalance', 'gl')
            ->where('gl.id > :id')
            ->setParameters([
                'id' => $id,
            ])
            ->getQuery()
            ->getResult();

        return $operations;
    }

    /**
     * @param $id
     * @return array GeneralLedgerBalance|null
     */
    public function getGLBListFromId($id)
    {
        $em = $this->getEntityManager();
        $operations = $em->createQueryBuilder()
            ->select('gl')
            ->from('ReportBundle:GeneralLedgerBalance', 'gl')
            ->where('gl.id >= :id')
            ->setParameters([
                'id' => $id,
            ])
            ->getQuery()
            ->getResult();

        return $operations;
    }
}
