<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InternalOperation
 *
 * @ORM\Table(name="internal_operation")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\InternalOperationRepository")
 */
class InternalOperation{

    const TYPE_CREDIT              = "CREDIT";
    const TYPE_WITHDRAWAL           = "WITHDRAWAL";
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOperation", type="datetime")
     */
    private $dateOperation;


    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="id_currentUser", referencedColumnName="id")
     */
    private $currentUser;


    /**
     * @ORM\ManyToOne(targetEntity="ClassBundle\Entity\InternalAccount")
     * @ORM\JoinColumn(name="id_account", referencedColumnName="id")
     */
    private $internalAccount;


    /**
     * @var int
     *
     * @ORM\Column(name="currentBalance", type="bigint")
     */
    private $currentBalance;


    /**
     * @var int
     *
     * @ORM\Column(name="fees", type="bigint")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="type_operation", type="string", length=50)
     */
    private $typeOperation;


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
     * Set dateOperation
     *
     * @param \DateTime $dateOperation
     *
     * @return InternalOperation
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
     * Set currentBalance
     *
     * @param integer $currentBalance
     *
     * @return InternalOperation
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
     * Set amount
     *
     * @param integer $amount
     *
     * @return InternalOperation
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set typeOperation
     *
     * @param string $typeOperation
     *
     * @return InternalOperation
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
     * Set currentUser
     *
     * @param \UserBundle\Entity\Utilisateur $currentUser
     *
     * @return InternalOperation
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
     * Set internalAccount
     *
     * @param \ClassBundle\Entity\InternalAccount $internalAccount
     *
     * @return InternalOperation
     */
    public function setInternalAccount(\ClassBundle\Entity\InternalAccount $internalAccount = null)
    {
        $this->internalAccount = $internalAccount;

        return $this;
    }

    /**
     * Get internalAccount
     *
     * @return \ClassBundle\Entity\InternalAccount
     */
    public function getInternalAccount()
    {
        return $this->internalAccount;
    }
}
