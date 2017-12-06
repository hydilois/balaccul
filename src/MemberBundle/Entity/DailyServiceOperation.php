<?php

namespace MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DailyServiceOperation
 *
 * @ORM\Table(name="daily_service_operation")
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\DailyServiceOperationRepository")
 */
class DailyServiceOperation{

    const TYPE_DEPOSIT              = "DEPOSIT";
    const TYPE_WITHDRAWAL           = "WITHDRAWAL";
    const TYPE_CHARGES              = "CHARGES";

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
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\Client")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;


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
    private $fees;

    /**
     * @var string
     *
     * @ORM\Column(name="type_operation", type="string", length=50)
     */
    private $typeOperation;



    public function __construct(){

        //the default date of the loan is now
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
     * Set dateOperation
     *
     * @param \DateTime $dateOperation
     *
     * @return DailyServiceOperation
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
     * @return DailyServiceOperation
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
     * Set fees
     *
     * @param integer $fees
     *
     * @return DailyServiceOperation
     */
    public function setFees($fees)
    {
        $this->fees = $fees;

        return $this;
    }

    /**
     * Get fees
     *
     * @return integer
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * Set typeOperation
     *
     * @param string $typeOperation
     *
     * @return DailyServiceOperation
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
     * @return DailyServiceOperation
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
     * Set client
     *
     * @param \MemberBundle\Entity\Client $client
     *
     * @return DailyServiceOperation
     */
    public function setClient(\MemberBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \MemberBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
