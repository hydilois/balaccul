<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\AccountRepository")
 */

/**
 * @ORM\MappedSuperclass
 */
class Account{

    /**
     * @var int
     *
     * @ORM\Column(name="accountNumber", type="bigint", unique=true)
     */
    private $accountNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="solde", type="bigint")
     */
    private $solde;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;


    public function __construct(){
        //The default status of the account is true
        $this->status = true;
        
        // The default amount is 0
        $this->solde = 0;
    }

    public function __toString(){
        return $this->accountNumber;
    }


    /**
     * Set accountNumber
     *
     * @param integer $accountNumber
     *
     * @return Account
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return int
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Account
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set solde
     *
     * @param integer $solde
     *
     * @return Account
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return integer
     */
    public function getSolde()
    {
        return $this->solde;
    }
}
