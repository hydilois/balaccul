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
     * @ORM\Column(name="amount", type="bigint")
     */
    private $amount;

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
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\Member")
     * @ORM\JoinColumn(name="member")
     */
    private $member;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_confirmed", type="boolean")
     */
    private $isConfirmed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_share", type="boolean")
     */
    private $isShare;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_saving", type="boolean")
     */
    private $isSaving;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deposit", type="boolean")
     */
    private $isDeposit;

    /**
     * @var string
     *
     * @ORM\Column(name="representative", type="string", length=50, nullable=true)
     */
    private $representative;


    public function __construct(){
        $this->amount = 0;
        $this->balance = 0;
        $this->isConfirmed = true;
        $this->isShare = false;
        $this->isSaving = false;
        $this->isDeposit = false;
        // $this->dateOperation = new \DateTime('now');
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId(){
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
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set balance
     *
     * @param integer $balance
     *
     * @return Operation
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
     * Set isShare
     *
     * @param boolean $isShare
     *
     * @return Operation
     */
    public function setIsShare($isShare)
    {
        $this->isShare = $isShare;

        return $this;
    }

    /**
     * Get isShare
     *
     * @return boolean
     */
    public function getIsShare()
    {
        return $this->isShare;
    }

    /**
     * Set isSaving
     *
     * @param boolean $isSaving
     *
     * @return Operation
     */
    public function setIsSaving($isSaving)
    {
        $this->isSaving = $isSaving;

        return $this;
    }

    /**
     * Get isSaving
     *
     * @return boolean
     */
    public function getIsSaving()
    {
        return $this->isSaving;
    }

    /**
     * Set isDeposit
     *
     * @param boolean $isDeposit
     *
     * @return Operation
     */
    public function setIsDeposit($isDeposit)
    {
        $this->isDeposit = $isDeposit;

        return $this;
    }

    /**
     * Get isDeposit
     *
     * @return boolean
     */
    public function getIsDeposit()
    {
        return $this->isDeposit;
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
     * Set member
     *
     * @param \MemberBundle\Entity\Member $member
     *
     * @return Operation
     */
    public function setMember(\MemberBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \MemberBundle\Entity\Member
     */
    public function getMember()
    {
        return $this->member;
    }


    /**
     * Set representative
     *
     * @param string $representative
     *
     * @return Operation
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
}
