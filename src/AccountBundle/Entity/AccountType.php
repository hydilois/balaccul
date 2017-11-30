<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountType
 *
 * @ORM\Table(name="account_type")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\AccountTypeRepository")
 */
class AccountType
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
     * @var int
     *
     * @ORM\ManyToMany(targetEntity="ClassBundle\Entity\InternalAccount", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\JoinTable(name="internal_account_type")
     */
    private $internalAccount;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

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
     * @return AccountType
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
     * Set description
     *
     * @param string $description
     *
     * @return AccountType
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
    public function getDescription(){
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->internalAccount = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add internalAccount
     *
     * @param \ClassBundle\Entity\InternalAccount $internalAccount
     *
     * @return AccountType
     */
    public function addInternalAccount(\ClassBundle\Entity\InternalAccount $internalAccount)
    {
        $this->internalAccount[] = $internalAccount;

        return $this;
    }

    /**
     * Remove internalAccount
     *
     * @param \ClassBundle\Entity\InternalAccount $internalAccount
     */
    public function removeInternalAccount(\ClassBundle\Entity\InternalAccount $internalAccount)
    {
        $this->internalAccount->removeElement($internalAccount);
    }

    /**
     * Get internalAccount
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInternalAccount()
    {
        return $this->internalAccount;
    }
}
