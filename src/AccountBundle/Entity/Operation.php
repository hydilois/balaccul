<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operation
 *
 * @ORM\Table(name="operation")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\OperationRepository")
 */
class Operation{
    
    const TYPE_CREDIT          = "CREDIT";
    const TYPE_DEBIT           = "DEBIT";
    const TYPE_TRANSFER        = "TRANSFER";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="bigint")
     */
    private $amount;


    /**
     * @var int
     *
     * @ORM\Column(name="transferFees", type="bigint")
     */
    private $transferFees;/*if it is a transfer*/


    /**
     * @var int
     *
     * @ORM\Column(name="debitFees", type="bigint")
     */
    private $debitFees;/*if it is a debit operation for daily saving */

    /**
     * @var string
     *
     * @ORM\Column(name="type_operation", type="string", length=50)
     */
    private $typeOperation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOperation", type="datetime")
     */
    private $dateOperation;


    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\Saving")
     * @ORM\JoinColumn(name="id_saving", referencedColumnName="id")
     */
    private $savingAccount;

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\Share")
     * @ORM\JoinColumn(name="id_share", referencedColumnName="id")
     */
    private $shareAccount;

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\Deposit")
     * @ORM\JoinColumn(name="id_deposit", referencedColumnName="id")
     */
    private $depositAccount;


    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\Saving")
     * @ORM\JoinColumn(name="id_receive_saving", referencedColumnName="id")
     */
    private $receiveSavingAccount;/*For transfer operations*/

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\Share")
     * @ORM\JoinColumn(name="id_receive_share", referencedColumnName="id")
     */
    private $receiveShareAccount;/*For transfer operations*/

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\Deposit")
     * @ORM\JoinColumn(name="id_receive_deposit", referencedColumnName="id")
     */
    private $receiveDepositAccount;/*For transfer operations*/

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="id_currentUser", referencedColumnName="id")
     */
    private $currentUser;


    /**
     * @var int
     *
     * @ORM\Column(name="currentBalance", type="bigint")
     */
    private $currentBalance;

    /**
     * @var int
     *
     * @ORM\Column(name="receiveAccountcurrentBalance", type="bigint")
     */
    private $receiveAccountcurrentBalance; /*For transfer operations*/


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_confirmed", type="boolean")
     */
    private $isConfirmed;


    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $userConfirmed;



    public function __construct(){

        //the default date of the loan is now
        $this->transferFees = 0;
        $this->receiveAccountcurrentBalance = 0;
        $this->debitFees = 0;
        $this->isConfirmed = false;
        $this->dateOperation = new \DateTime('now');
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Operation
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set dateOperation
     *
     * @param \DateTime $dateOperation
     *
     * @return Operation
     */
    public function setDateOperation($dateOperation)
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    /**
     * Get dateOperation
     *
     * @return \DateTime
     */
    public function getDateOperation()
    {
        return $this->dateOperation;
    }

    /**
     * Set savingAccount
     *
     * @param \AccountBundle\Entity\Saving $savingAccount
     *
     * @return Operation
     */
    public function setSavingAccount(\AccountBundle\Entity\Saving $savingAccount = null)
    {
        $this->savingAccount = $savingAccount;

        return $this;
    }

    /**
     * Get savingAccount
     *
     * @return \AccountBundle\Entity\Saving
     */
    public function getSavingAccount()
    {
        return $this->savingAccount;
    }

    /**
     * Set currentUser
     *
     * @param \UserBundle\Entity\Utilisateur $currentUser
     *
     * @return Operation
     */
    public function setCurrentUser(\UserBundle\Entity\Utilisateur $currentUser = null)
    {
        $this->currentUser = $currentUser;

        return $this;
    }

    /**
     * Get currentUser
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Set typeOperation
     *
     * @param string $typeOperation
     *
     * @return Operation
     */
    public function setTypeOperation($typeOperation)
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    /**
     * Get typeOperation
     *
     * @return string
     */
    public function getTypeOperation()
    {
        return $this->typeOperation;
    }

    /**
     * Set shareAccount
     *
     * @param \AccountBundle\Entity\Share $shareAccount
     *
     * @return Operation
     */
    public function setShareAccount(\AccountBundle\Entity\Share $shareAccount = null)
    {
        $this->shareAccount = $shareAccount;

        return $this;
    }

    /**
     * Get shareAccount
     *
     * @return \AccountBundle\Entity\Share
     */
    public function getShareAccount()
    {
        return $this->shareAccount;
    }

    /**
     * Set depositAccount
     *
     * @param \AccountBundle\Entity\Deposit $depositAccount
     *
     * @return Operation
     */
    public function setDepositAccount(\AccountBundle\Entity\Deposit $depositAccount = null)
    {
        $this->depositAccount = $depositAccount;

        return $this;
    }

    /**
     * Get depositAccount
     *
     * @return \AccountBundle\Entity\Deposit
     */
    public function getDepositAccount()
    {
        return $this->depositAccount;
    }

    /**
     * Set currentBalance
     *
     * @param integer $currentBalance
     *
     * @return Operation
     */
    public function setCurrentBalance($currentBalance)
    {
        $this->currentBalance = $currentBalance;

        return $this;
    }

    /**
     * Get currentBalance
     *
     * @return integer
     */
    public function getCurrentBalance()
    {
        return $this->currentBalance;
    }

    /**
     * Set receiveSavingAccount
     *
     * @param \AccountBundle\Entity\Saving $receiveSavingAccount
     *
     * @return Operation
     */
    public function setReceiveSavingAccount(\AccountBundle\Entity\Saving $receiveSavingAccount = null)
    {
        $this->receiveSavingAccount = $receiveSavingAccount;

        return $this;
    }

    /**
     * Get receiveSavingAccount
     *
     * @return \AccountBundle\Entity\Saving
     */
    public function getReceiveSavingAccount()
    {
        return $this->receiveSavingAccount;
    }

    /**
     * Set receiveShareAccount
     *
     * @param \AccountBundle\Entity\Share $receiveShareAccount
     *
     * @return Operation
     */
    public function setReceiveShareAccount(\AccountBundle\Entity\Share $receiveShareAccount = null)
    {
        $this->receiveShareAccount = $receiveShareAccount;

        return $this;
    }

    /**
     * Get receiveShareAccount
     *
     * @return \AccountBundle\Entity\Share
     */
    public function getReceiveShareAccount()
    {
        return $this->receiveShareAccount;
    }

    /**
     * Set receiveDepositAccount
     *
     * @param \AccountBundle\Entity\Deposit $receiveDepositAccount
     *
     * @return Operation
     */
    public function setReceiveDepositAccount(\AccountBundle\Entity\Deposit $receiveDepositAccount = null)
    {
        $this->receiveDepositAccount = $receiveDepositAccount;

        return $this;
    }

    /**
     * Get receiveDepositAccount
     *
     * @return \AccountBundle\Entity\Deposit
     */
    public function getReceiveDepositAccount()
    {
        return $this->receiveDepositAccount;
    }

    /**
     * Set transferFees
     *
     * @param integer $transferFees
     *
     * @return Operation
     */
    public function setTransferFees($transferFees)
    {
        $this->transferFees = $transferFees;

        return $this;
    }

    /**
     * Get transferFees
     *
     * @return integer
     */
    public function getTransferFees()
    {
        return $this->transferFees;
    }

    /**
     * Set receiveAccountcurrentBalance
     *
     * @param integer $receiveAccountcurrentBalance
     *
     * @return Operation
     */
    public function setReceiveAccountcurrentBalance($receiveAccountcurrentBalance)
    {
        $this->receiveAccountcurrentBalance = $receiveAccountcurrentBalance;

        return $this;
    }

    /**
     * Get receiveAccountcurrentBalance
     *
     * @return integer
     */
    public function getReceiveAccountcurrentBalance()
    {
        return $this->receiveAccountcurrentBalance;
    }

    /**
     * Set debitFees
     *
     * @param integer $debitFees
     *
     * @return Operation
     */
    public function setDebitFees($debitFees)
    {
        $this->debitFees = $debitFees;

        return $this;
    }

    /**
     * Get debitFees
     *
     * @return integer
     */
    public function getDebitFees()
    {
        return $this->debitFees;
    }

    /**
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     *
     * @return Operation
     */
    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    /**
     * Get isConfirmed
     *
     * @return boolean
     */
    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * Set userConfirmed
     *
     * @param \UserBundle\Entity\Utilisateur $userConfirmed
     *
     * @return Operation
     */
    public function setUserConfirmed(\UserBundle\Entity\Utilisateur $userConfirmed = null)
    {
        $this->userConfirmed = $userConfirmed;

        return $this;
    }

    /**
     * Get userConfirmed
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getUserConfirmed()
    {
        return $this->userConfirmed;
    }
}
