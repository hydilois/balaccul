<?php

namespace MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\ClientRepository")
 */
class Client{
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="idNumber", type="string", length=50, nullable=true, unique=true)
     */
    private $idNumber;



    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="id_collector")
     */
    private $collector;


    /**
     * @var int
     *
     * @ORM\Column(name="balance", type="integer")
     */
    private $balance;

    /**
     * @var int
     *
     * @ORM\Column(name="balanceBF", type="integer")
     */
    private $balanceBF;

    /**
     * @var int
     *
     * @ORM\Column(name="withdrawal1", type="integer")
     */
    private $withdrawal1;

    /**
     * @var int
     *
     * @ORM\Column(name="withdrawal2", type="integer")
     */
    private $withdrawal2;


     /**
     * @var int
     *
     * @ORM\Column(name="charges", type="bigint")
     */
    private $charges;


    public function __toString(){
        return $this->name;
    }

    public function __construct(){        
        // The default amount is 0
        $this->balance = 0;
        $this->balanceBF = 0;
        $this->withdrawal1 = 0;
        $this->withdrawal2 = 0;
        $this->charges = 0;
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
     * Set name
     *
     * @param string $name
     *
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set idNumber
     *
     * @param string $idNumber
     *
     * @return Client
     */
    public function setIdNumber($idNumber)
    {
        $this->idNumber = $idNumber;

        return $this;
    }

    /**
     * Get idNumber
     *
     * @return string
     */
    public function getIdNumber()
    {
        return $this->idNumber;
    }

    /**
     * Set collector
     *
     * @param \UserBundle\Entity\Utilisateur $collector
     *
     * @return Client
     */
    public function setCollector(\UserBundle\Entity\Utilisateur $collector = null)
    {
        $this->collector = $collector;

        return $this;
    }

    /**
     * Get collector
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getCollector()
    {
        return $this->collector;
    }

    /**
     * Set balance
     *
     * @param integer $balance
     *
     * @return Client
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
     * Set balanceBF
     *
     * @param integer $balanceBF
     *
     * @return Client
     */
    public function setBalanceBF($balanceBF)
    {
        $this->balanceBF = $balanceBF;

        return $this;
    }

    /**
     * Get balanceBF
     *
     * @return integer
     */
    public function getBalanceBF()
    {
        return $this->balanceBF;
    }

    /**
     * Set withdrawal1
     *
     * @param integer $withdrawal1
     *
     * @return Client
     */
    public function setWithdrawal1($withdrawal1)
    {
        $this->withdrawal1 = $withdrawal1;

        return $this;
    }

    /**
     * Get withdrawal1
     *
     * @return integer
     */
    public function getWithdrawal1()
    {
        return $this->withdrawal1;
    }

    /**
     * Set withdrawal2
     *
     * @param integer $withdrawal2
     *
     * @return Client
     */
    public function setWithdrawal2($withdrawal2)
    {
        $this->withdrawal2 = $withdrawal2;

        return $this;
    }

    /**
     * Get withdrawal2
     *
     * @return integer
     */
    public function getWithdrawal2()
    {
        return $this->withdrawal2;
    }

    /**
     * Set charges
     *
     * @param integer $charges
     *
     * @return Client
     */
    public function setCharges($charges)
    {
        $this->charges = $charges;

        return $this;
    }

    /**
     * Get charges
     *
     * @return integer
     */
    public function getCharges()
    {
        return $this->charges;
    }
}
