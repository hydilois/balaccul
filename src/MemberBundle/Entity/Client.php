<?php

namespace MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\ClientRepository")
 */
class Client
{
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
     * @ORM\OneToOne(targetEntity="AccountBundle\Entity\DailySavingAccount")
     * @ORM\JoinColumn(name="id_amount")
     */
    private $amount;


    public function __toString(){
        return $this->name;
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
     * Set amount
     *
     * @param \AccountBundle\Entity\DailySavingAccount $amount
     *
     * @return Client
     */
    public function setAmount(\AccountBundle\Entity\DailySavingAccount $amount = null)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return \AccountBundle\Entity\DailySavingAccount
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
