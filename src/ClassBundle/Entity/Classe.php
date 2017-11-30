<?php

namespace ClassBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classe
 *
 * @ORM\Table(name="classe")
 * @ORM\Entity(repositoryClass="ClassBundle\Repository\ClasseRepository")
 */
class Classe{
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
     * @ORM\ManyToOne(targetEntity="ClassBundle\Entity\Classe")
     * @ORM\JoinColumn(name="id_classe")
     */
    private $classCategory;

    private $listeAccounts;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var decimal
     *
     * @ORM\Column(name="totalAmount", type="decimal")
     */
    private $totalAmount;


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
     * @return Classe
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
     * Set totalAmount
     *
     * @param string $totalAmount
     *
     * @return Classe
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return string
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set classCategory
     *
     * @param \ClassBundle\Entity\Classe $classCategory
     *
     * @return Classe
     */
    public function setClassCategory(\ClassBundle\Entity\Classe $classCategory = null)
    {
        $this->classCategory = $classCategory;

        return $this;
    }

    /**
     * Get classCategory
     *
     * @return \ClassBundle\Entity\Classe
     */
    public function getClassCategory()
    {
        return $this->classCategory;
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

    function getListeAccounts() {
        return $this->listeAccounts;
    }

    function setListeAccounts($listeAccounts) {
        $this->listeAccounts = $listeAccounts;
    }
}
