<?php 

namespace AccountBundle\Service;

use AccountBundle\Entity\Saving;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Request;


class AccountService extends Controller{

	/**
	 * get a single account
	 * @param  int $id id of the saving to show
	 * @return Saving     entity of Saving
	 */
	public function getSavingAccount($id){

		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('AccountBundle:Saving')->find($id);
	}


	/**
	 * get all saving account
	 * @return array collection of savings objects
	 */
	public function getSavingAccountList(){
		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('AccountBundle:Saving')->findAll();
	}


	/**
	 * get all shares accounts
	 * @return array collection of shares objects
	 */
	public function getShareAccountList(){
		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('AccountBundle:Share')->findAll();
	}


	/**
	 * get all shares accounts
	 * @return array collection of shares objects
	 */
	public function getDepositAccountList(){
		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('AccountBundle:Deposit')->findAll();
	}


	/**
	 * get a single account
	 * @param  int $id id of the share to show
	 * @return Share     entity of Share
	 */
	public function getShareAccount($id){

		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('AccountBundle:Share')->find($id);
	}


	/**
	 * get a single account
	 * @param  int $id id of the deposit to show
	 * @return Deposit     entity of Deposit
	 */
	public function getDepositAccount($id){

		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('AccountBundle:Deposit')->find($id);
	}


	/**
	 * get a single loan Instance
	 * @param  int $id id of the loan to show
	 * @return Loan     entity of Loan
	 */
	public function getLoan($id){

		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('AccountBundle:Loan')->find($id);
	}

	/**
	 * get a single Client
	 * @param  int $id id of the client to show
	 * @return Client     entity of Client
	 */
	public function getClient($id){

		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('MemberBundle:Client')->find($id);
	}

	/**
	 * get a single and the last history of the loan
	 * @param  int $id id of the loanhistory to show
	 * @return LoanHistory     entity of LoanHistory
	 */
	public function getLoanHistory($id){

		$entityManager = $this->getDoctrine()->getManager();

		$loan = $entityManager->getRepository('AccountBundle:Loan')->find($id);
		
		    //get the lowest amount of the loan history refere to the current loan
		$lowest_remain_amount_LoanHistory = $entityManager->createQueryBuilder()
		    ->select('MIN(lh.remainAmount)')
		    ->from('AccountBundle:LoanHistory', 'lh')
		    ->innerJoin('AccountBundle:Loan', 'l', 'WITH','lh.loan = l.id')
		    ->where('l.id = :loan')->setParameter('loan', $loan)
		    ->getQuery()
		    ->getSingleScalarResult();

		    $latestLoanHistory = $entityManager->getRepository('AccountBundle:LoanHistory')->findOneBy([
                        'remainAmount' => $lowest_remain_amount_LoanHistory,
                        'loan' => $loan
                ],
                [
                    'id' => 'DESC'
                ]
                );

		return $latestLoanHistory;
	}



	/**
	 * get a single account
	 * @param  int $id id of the saving to show
	 * @return Representant     entity of Representant
	 */
	public function getAccountRepresentant($id){

		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('MemberBundle:Representant')->findBy(
			[
				'idMember' => $id
			]);
	}



	/**
	 * get a single account
	 * @param  int $id id of the saving to show
	 * @return Beneficiary     entity of Beneficiary
	 */
	public function getAccountBeneficiary($id){

		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('MemberBundle:Beneficiary')->findBy(
			[
				'idMember' => $id
			]);
	}

	/**
	 * get a single account
	 * @param  int $id id of the internal to show
	 * @return InternalAccount     entity of InternalAccount
	 */
	public function getInternalAccount($id){

		$entityManager = $this->getDoctrine()->getManager();
		return $entityManager->getRepository('ClassBundle:InternalAccount')->find($id);
	}
}