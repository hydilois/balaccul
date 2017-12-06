<?php

namespace ClassBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InternalAccount
 *
 * @ORM\Table(name="internalaccount")
 * @ORM\Entity(repositoryClass="ClassBundle\Repository\InternalAccountRepository")
 */
class InternalAccount
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
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
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var decimal
     *
     * @ORM\Column(name="amount", type="decimal")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;




    public function __construct(){
        // The default amount is 0
        $this->amount = 0;
    }


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
     * @return InternalAccount
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
     * Set amount
     *
     * @param string $amount
     *
     * @return InternalAccount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
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
     * Set description
     *
     * @param string $description
     *
     * @return InternalAccount
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return InternalAccount
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
