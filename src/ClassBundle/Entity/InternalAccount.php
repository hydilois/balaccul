<?php

namespace ClassBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InternalAccount
 *
 * @ORM\Table(name="internalaccount")
 * @ORM\Entity(repositoryClass="ClassBundle\Repository\InternalAccountRepository")
 */
class InternalAccount{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="accountName", type="string", length=255)
     */
    private $accountName;

    /**
     * @var int
     *
     * @ORM\Column(name="accountNumber", type="bigint", unique=true)
     */
    private $accountNumber;

    /**
     * @ORM\ManyToOne(targetEntity="ClassBundle\Entity\Classe")
     * @ORM\JoinColumn(name="id_classe")
     */
    private $classe;

    /**
     * @var decimal
     *
     * @ORM\Column(name="beginingBalance", type="decimal")
     */
    private $beginingBalance;

    /**
     * @var decimal
     *
     * @ORM\Column(name="endingBalance", type="decimal")
     */
    private $endingBalance;

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
     * @var string
     *
     * @ORM\Column(name="beginBalanceCode", type="string", length=1, options={"default":"D"})
     */
    private $beginBalanceCode;

    /**
     * @var string
     *
     * @ORM\Column(name="endingBalanceCode", type="string", length=1, options={"default":"C"})
     */
    private $endingBalanceCode;





    public function __construct(){
        // The default amount is 0
        $this->beginingBalance = 0;
        $this->debit = 0;
        $this->credit = 0;
        $this->endingBalance = 0;
        $this->beginBalanceCode = 'D';
        $this->beginBalanceCode = 'C';
    }

    public function __toString(){
        return $this->accountName;
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
     * Set accountName
     *
     * @param string $accountName
     *
     * @return InternalAccount
     */
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;

        return $this;
    }

    /**
     * Get accountName
     *
     * @return string
     */
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * Set accountNumber
     *
     * @param integer $accountNumber
     *
     * @return InternalAccount
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return integer
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set beginingBalance
     *
     * @param string $beginingBalance
     *
     * @return InternalAccount
     */
    public function setBeginingBalance($beginingBalance)
    {
        $this->beginingBalance = $beginingBalance;

        return $this;
    }

    /**
     * Get beginingBalance
     *
     * @return string
     */
    public function getBeginingBalance()
    {
        return $this->beginingBalance;
    }

    /**
     * Set endingBalance
     *
     * @param string $endingBalance
     *
     * @return InternalAccount
     */
    public function setEndingBalance($endingBalance)
    {
        $this->endingBalance = $endingBalance;

        return $this;
    }

    /**
     * Get endingBalance
     *
     * @return string
     */
    public function getEndingBalance()
    {
        return $this->endingBalance;
    }

    /**
     * Set debit
     *
     * @param string $debit
     *
     * @return InternalAccount
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
     * @return InternalAccount
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
     * Set classe
     *
     * @param \ClassBundle\Entity\Classe $classe
     *
     * @return InternalAccount
     */
    public function setClasse(\ClassBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return \ClassBundle\Entity\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set beginBalanceCode
     *
     * @param string $beginBalanceCode
     *
     * @return InternalAccount
     */
    public function setBeginBalanceCode($beginBalanceCode)
    {
        $this->beginBalanceCode = $beginBalanceCode;

        return $this;
    }

    /**
     * Get beginBalanceCode
     *
     * @return string
     */
    public function getBeginBalanceCode()
    {
        return $this->beginBalanceCode;
    }

    /**
     * Set endingBalanceCode
     *
     * @param string $endingBalanceCode
     *
     * @return InternalAccount
     */
    public function setEndingBalanceCode($endingBalanceCode)
    {
        $this->endingBalanceCode = $endingBalanceCode;

        return $this;
    }

    /**
     * Get endingBalanceCode
     *
     * @return string
     */
    public function getEndingBalanceCode()
    {
        return $this->endingBalanceCode;
    }
}
