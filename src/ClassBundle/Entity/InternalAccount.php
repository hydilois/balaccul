<?php

namespace ClassBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * InternalAccount
 *
 * @ORM\Table(name="internalaccount")
 * @ORM\Entity(repositoryClass="ClassBundle\Repository\InternalAccountRepository")
 * @UniqueEntity("accountName", message="This account's name has already been used")
 * @UniqueEntity("accountNumber", message="This account's number has already been used")
 */
class InternalAccount{

    const  TOKEN = [
        'SHARES' => 'SHARES',
        'SAVINGS' => 'SAVINGS',
        'RESERVES' => 'RESERVES',
        'CONTRIBUTION' => 'CONTRIBUTION',
        'SOLIDARITY' => 'SOLIDARITY',
        'DAILY_SAVING' => 'DAILY_SAVING',
        'LOAN' => 'LOAN',
        'BANK_ACCOUNT' => 'BANK_ACCOUNT',
        'PROCESSING_FEES' => 'PROCESSING_FEES',
    ];

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
     * @Assert\NotBlank()
     */
    private $accountName;

    /**
     * @var int
     *
     * @ORM\Column(name="accountNumber", type="bigint", unique=true)
     * @Assert\NotBlank()
     */
    private $accountNumber;

    /**
     * @ORM\ManyToOne(targetEntity="ClassBundle\Entity\Classe", inversedBy="accounts")
     * @ORM\JoinColumn(name="id_classe")
     */
    private $classe;

    /**
     * @var decimal
     *
     * @ORM\Column(name="balance", type="decimal")
     */
    private $balance;

    /**
     * @var string
     *
     * @ORM\Column(name="balanceCode", type="string", length=1, options={"default":"D"})
     */
    private $balanceCode;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     */
    private $token;

    /**
     * InternalAccount constructor.
     */
    public function __construct(){
        // The default amount is 0
        $this->balance = 0;
        $this->balanceCode = 'C';
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
     * Set balance
     *
     * @param string $balance
     *
     * @return InternalAccount
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set balanceCode
     *
     * @param string $balanceCode
     *
     * @return InternalAccount
     */
    public function setBalanceCode($balanceCode)
    {
        $this->balanceCode = $balanceCode;

        return $this;
    }

    /**
     * Get balanceCode
     *
     * @return string
     */
    public function getBalanceCode()
    {
        return $this->balanceCode;
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
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
}
