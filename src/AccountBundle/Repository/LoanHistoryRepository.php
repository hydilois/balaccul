<?php

namespace AccountBundle\Repository;

use AccountBundle\Entity\Loan;
use AccountBundle\Entity\LoanHistory;
use Doctrine\ORM\EntityRepository;
use MemberBundle\Entity\Member;

/**
 * LoanHistoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LoanHistoryRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getAllActiveLoans()
    {
        $em = $this->getEntityManager();
        $loans = $em->getRepository(Loan::class)->getLoansOrderByAccountName();

        foreach ($loans as $loan) {
            //get the last element in loan history
            $lowest_remain_amount_LoanHistory = $em->createQueryBuilder()
                ->select('MIN(lh.remainAmount)')
                ->from('AccountBundle:LoanHistory', 'lh')
                ->innerJoin('AccountBundle:Loan', 'l', 'WITH','lh.loan = l.id')
                ->where('l.id = :loan')
                ->setParameter('loan', $loan)
                ->getQuery()
                ->getSingleScalarResult();

            if ($lowest_remain_amount_LoanHistory) {
                $latestLoanHistory = $em->getRepository(LoanHistory::class)->findOneBy(
                    [
                        'remainAmount' => $lowest_remain_amount_LoanHistory,
                        'loan' => $loan
                    ],
                    ['id' => 'DESC']
                );

                $loan->setLoanHistory($latestLoanHistory);
            }
        }
        return $loans;
    }

    /**
     * @param $loan
     * @param $dateOperation
     * @return  Loan|null
     */
    public function getActiveLoanPerMember($loan, $dateOperation)
    {
        $em = $this->getEntityManager();
            //get the last element in loan history
            $loanHistories = $em->createQueryBuilder()
                ->select('lh')
                ->from('AccountBundle:LoanHistory', 'lh')
                ->innerJoin('AccountBundle:Loan', 'l', 'WITH','lh.loan = l.id')
                ->where('l.id = :loan')
                ->andWhere('lh.dateOperation <= :date')
                ->setParameters([
                    'loan' => $loan,
                    'date' => $dateOperation
                ])
                ->getQuery()
                ->getResult();

            if ($loanHistories) {
                $latestLoanHistory = array_values(array_slice($loanHistories, -1))[0];
                if ($latestLoanHistory) {
                    //set the unpaid to recover after in the next payment
                    $interest = ($latestLoanHistory->getRemainAmount() * $loan->getRate()) / 100;
                    $dailyInterestPayment = $interest / 30;
                    $date = strtotime($latestLoanHistory->getDateOperation()->format('Y-m-d'));

                    $interestToPay = round($dailyInterestPayment * floor((strtotime($dateOperation->format('Y-m-d')) - $date) / (60 * 60 * 24)));
                    $loan->setInterestToPayAt($interestToPay + $latestLoanHistory->getUnpaidInterest());
                } else {
                    $interest = ($loan->getLoanAmount() * $loan->getRate()) / 100;
                    $dailyInterestPayment = $interest / 30;
                    $date = strtotime($loan->getDateLoan()->format('Y-m-d'));
                    $interestToPay = round($dailyInterestPayment * floor((strtotime($dateOperation->format('Y-m-d')) - $date) / (60 * 60 * 24)));
                    $loan->setInterestToPayAt($interestToPay);
                }
                $numberOfDays = floor((strtotime($dateOperation->format('Y-m-d')) - $date) / (60 * 60 * 24)) - 30;
                $numberOfDays <= 0 ? $loan->setNumberOfDays(0) : $loan->setNumberOfDays($numberOfDays);
                $loan->setNumberOfDelinquent(floor((strtotime($dateOperation->format('Y-m-d')) - $date) / (60 * 60 * 24 * 30)));
                $loan->setLoanHistory($latestLoanHistory);
            }
        return $loan;
    }
}
