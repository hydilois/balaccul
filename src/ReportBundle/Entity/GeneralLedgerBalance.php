<?php

namespace ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GeneralLedgerBalance
 *
 * @ORM\Table(name="general_ledger_balance")
 * @ORM\Entity(repositoryClass="ReportBundle\Repository\GeneralLedgerBalanceRepository")
 */
class GeneralLedgerBalance{

    const TYPE_CASH_IN     = "CASH IN";
    const TYPE_CASH_OUT    = "CASH OUT";
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
     * @ORM\Column(name="balance", type="bigint")
     */
    private $balance;

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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $currentUser;

    /**
     * @ORM\ManyToOne(targetEntity="ClassBundle\Entity\InternalAccount")
     * @ORM\JoinColumn(name="id_internalAccount", referencedColumnName="id")
     */
    private $account;

    /**
     * @var decimal
     *
     * @ORM\Column(name="debit", type="decimal")
     */
    private $debit;

    /**
     * @var decimal
     *
     * @ORM\Column(name="credit", type="decimal")
     */
    private $credit;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_confirmed", type="boolean")
     */
    private $isConfirmed;

    /**
     * @var string
     *
     * @ORM\Column(name="representative", type="string", length=50, nullable=true)
     */
    private $representative;

    public function __construct(){
        $this->credit = 0;
        $this->debit = 0;
        $this->balance = 0;
        $this->isConfirmed = true;
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
     * Set balance
     *
     * @param integer $balance
     *
     * @return GeneralLedgerBalance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return integer
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set typeOperation
     *
     * @param string $typeOperation
     *
     * @return GeneralLedgerBalance
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
     * Set dateOperation
     *
     * @param \DateTime $dateOperation
     *
     * @return GeneralLedgerBalance
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
     * Set debit
     *
     * @param string $debit
     *
     * @return GeneralLedgerBalance
     */
    public function setDebit($debit)
    {
        $this->debit = $debit;

        return $this;
    }

    /**
     * Get debit
     *
     * @return string
     */
    public function getDebit()
    {
        return $this->debit;
    }

    /**
     * Set credit
     *
     * @param string $credit
     *
     * @return GeneralLedgerBalance
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return string
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     *
     * @return GeneralLedgerBalance
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
     * Set representative
     *
     * @param string $representative
     *
     * @return GeneralLedgerBalance
     */
    public function setRepresentative($representative)
    {
        $this->representative = $representative;

        return $this;
    }

    /**
     * Get representative
     *
     * @return string
     */
    public function getRepresentative()
    {
        return $this->representative;
    }

    /**
     * Set currentUser
     *
     * @param \UserBundle\Entity\Utilisateur $currentUser
     *
     * @return GeneralLedgerBalance
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
     * Set account
     *
     * @param \ClassBundle\Entity\InternalAccount $account
     *
     * @return GeneralLedgerBalance
     */
    public function setAccount(\ClassBundle\Entity\InternalAccount $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \ClassBundle\Entity\InternalAccount
     */
    public function getAccount()
    {
        return $this->account;
    }
}
