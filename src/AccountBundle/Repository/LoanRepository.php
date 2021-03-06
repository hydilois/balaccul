<?php

namespace AccountBundle\Repository;

use AccountBundle\Entity\Loan;
use AccountBundle\Entity\Operation;
use ClassBundle\Entity\InternalAccount;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use MemberBundle\Entity\Member;
use ReportBundle\Entity\GeneralLedgerBalance;
use UserBundle\Entity\Utilisateur;

/**
 * LoanRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LoanRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getLoansOrderByAccountName()
    {
        $loans = $this->createQueryBuilder('l')
            ->innerJoin(Member::class, 'm', 'WITH', 'l.physicalMember = m.id')
            ->andWhere('l.status = :status')
            ->orderBy('m.name', 'ASC')
            ->setParameters([
                'status' => true
                ]
            )
            ->getQuery()
            ->getResult();
        return $loans;
    }

    /**
     * @param Member $member
     * @param $date
     * @return array
     */
    public function getMemberLoans(Member $member, $date)
    {
        $loan = $this->createQueryBuilder('l')
            ->innerJoin(Member::class, 'm', 'WITH', 'l.physicalMember = m.id')
            ->where('l.status = :status')
            ->andWhere('l.dateLoan <= :date')
            ->andWhere('l.physicalMember = :member')
            ->setParameters([
                    'status' => true,
                    'date' => $date,
                    'member' => $member,
                ]
            )
            ->getQuery()
            ->getOneOrNullResult();
        return $loan;
    }

    /**
     * @param Utilisateur $currentUser
     * @param Loan $loan
     * @param Member $member
     * @param InternalAccount $internalAccount
     * @param ObjectManager $manager
     */
    public function saveLoanOperation(Utilisateur $currentUser, Loan $loan, Member $member, InternalAccount $internalAccount, ObjectManager $manager)
    {
        $operation = new Operation();
        $operation->setTypeOperation(Operation::TYPE_CASH_OUT);
        $operation->setCurrentUser($currentUser);
        $operation->setDateOperation($loan->getDateLoan());
        $operation->setAmount($loan->getLoanAmount());
        $operation->setMember($member);
        $operation->setBalance($internalAccount->getBalance());
        $operation->setRepresentative($member->getName());
        $manager->persist($operation);
    }

    /**
     * @param Loan $loan
     * @param Utilisateur $currentUser
     * @param InternalAccount $internalAccount
     * @param Member $member
     * @param ObjectManager $manager
     */
    public function saveLoanInGeneralLedger(Loan $loan, Utilisateur $currentUser, InternalAccount $internalAccount, Member $member, ObjectManager $manager)
    {
        $ledgerBalanceLoan = new GeneralLedgerBalance();
        $ledgerBalanceLoan->setTypeOperation(Operation::TYPE_CASH_OUT);
        $ledgerBalanceLoan->setCredit($loan->getLoanAmount());
        $ledgerBalanceLoan->setCurrentUser($currentUser);
        $ledgerBalanceLoan->setDateOperation($loan->getDateLoan());
        $manager = $this->getEntityManager();
        $latestEntryGBL = $manager->getRepository(GeneralLedgerBalance::class)->findOneBy([],['id' => 'DESC']);
        if ($latestEntryGBL) {
            $ledgerBalanceLoan->setBalance($latestEntryGBL->getBalance() - $loan->getLoanAmount());
        }else{
            $ledgerBalanceLoan->setBalance($loan->getLoanAmount());
        }
        $ledgerBalanceLoan->setAccount($internalAccount);
        $ledgerBalanceLoan->setRepresentative($member->getName());
        $ledgerBalanceLoan->setAccountBalance($internalAccount->getBalance());
        $ledgerBalanceLoan->setAccountTitle($internalAccount->getAccountName()." A/C_".$member->getMemberNumber());
        $ledgerBalanceLoan->setMember($loan->getPhysicalMember());
        $manager->persist($ledgerBalanceLoan);
        $manager->flush();
    }

    /**
     * @param Utilisateur $currentUser
     * @param Loan $loan
     * @param Member $member
     * @param InternalAccount $accountProcessing
     * @param ObjectManager $manager
     */
    public function saveLoanProcessingFeesOperation(Utilisateur $currentUser, Loan $loan, Member $member, InternalAccount $accountProcessing, ObjectManager $manager)
    {
        $operationProcessing = new Operation();
        $operationProcessing->setTypeOperation(Operation::TYPE_CASH_IN);
        $operationProcessing->setCurrentUser($currentUser);
        $operationProcessing->setDateOperation($loan->getDateLoan());
        $operationProcessing->setAmount($loan->getLoanProcessingFees());
        $operationProcessing->setMember($loan->getPhysicalMember());
        $operationProcessing->setRepresentative($member->getName());
        $operationProcessing->setBalance($accountProcessing->getBalance());

        $manager->persist($operationProcessing);
    }

    /**
     * @param Loan $loan
     * @param Utilisateur $currentUser
     * @param InternalAccount $accountProcessing
     * @param Member $member
     * @param ObjectManager $manager
     */
    public function saveProcessingFeesInGeneralLedger(Loan $loan, Utilisateur $currentUser, InternalAccount $accountProcessing, Member $member, ObjectManager $manager)
    {
        $ledgerBalanceProcessingFees = new GeneralLedgerBalance();
        $ledgerBalanceProcessingFees->setTypeOperation(Operation::TYPE_CASH_IN);
        $ledgerBalanceProcessingFees->setDebit($loan->getLoanProcessingFees());
        $ledgerBalanceProcessingFees->setCurrentUser($currentUser);
        $ledgerBalanceProcessingFees->setDateOperation($loan->getDateLoan());
        $latestEntryGBL = $manager->getRepository(GeneralLedgerBalance::class)->findOneBy([],['id' => 'DESC']);
        if ($latestEntryGBL) {
            $ledgerBalanceProcessingFees->setBalance($latestEntryGBL->getBalance() + $loan->getLoanProcessingFees());
        }else{
            $ledgerBalanceProcessingFees->setBalance($loan->getLoanProcessingFees());
        }
        $ledgerBalanceProcessingFees->setAccount($accountProcessing);
        $ledgerBalanceProcessingFees->setRepresentative($member->getName());
        $ledgerBalanceProcessingFees->setAccountBalance($accountProcessing->getBalance());
        $ledgerBalanceProcessingFees->setAccountTitle($accountProcessing->getAccountName()." A/C_".$member->getMemberNumber());
        $ledgerBalanceProcessingFees->setMember($loan->getPhysicalMember());
        $manager->persist($ledgerBalanceProcessingFees);
    }
}
