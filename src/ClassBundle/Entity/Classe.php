<?php

namespace ClassBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Classe
 *
 * @ORM\Table(name="classe")
 * @ORM\Entity(repositoryClass="ClassBundle\Repository\ClasseRepository")
 * @UniqueEntity("name", message="This class's name has already been used")
 */
class Classe {
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
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var decimal
     *
     * @ORM\Column(name="balance", type="decimal")
     */
    private $balance;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="ClassBundle\Entity\InternalAccount", mappedBy="classe")
     */
    private $accounts;


    public function __toString(){
        return $this->name;
    }

    public function __construct(){
        // The default amount is 0
        $this->balance = 0;
        $this->name = "Class";
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
     * @return Classe
     */
    public function setName($name){
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Classe
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
     * Set balance
     *
     * @param string $balance
     *
     * @return Classe
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
     * @return mixed
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param mixed $accounts
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;
    }
}
