<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReportItem
 *
 * @ORM\Table(name="report_item")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\ReportItemRepository")
 */
class ReportItem
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
     * @ORM\Column(name="amount", type="bigint")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="ReportItem")
     */
    private $parentItem;


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
     * @return ReportItem
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
     * Set parentItem
     *
     * @param \AccountBundle\Entity\ReportItem $parentItem
     *
     * @return ReportItem
     */
    public function setParentItem(\AccountBundle\Entity\ReportItem $parentItem = null)
    {
        $this->parentItem = $parentItem;

        return $this;
    }

    /**
     * Get parentItem
     *
     * @return \AccountBundle\Entity\ReportItem
     */
    public function getParentItem()
    {
        return $this->parentItem;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return ReportItem
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
}
