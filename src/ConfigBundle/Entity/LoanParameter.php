<?php

namespace ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoanParameter
 *
 * @ORM\Table(name="loan_parameter")
 * @ORM\Entity(repositoryClass="ConfigBundle\Repository\LoanParameterRepository")
 */
class LoanParameter
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
     * @var int
     *
     * @ORM\Column(name="parameter", type="integer", unique=true)
     */
    private $parameter;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;


    /**
     * @var Datetime
     *
     * @ORM\Column(name="created_or_modified_at", type="datetime")
     */
    private $createdAt;



    public function __construct(){
        $this->createdAt = new \DateTime('now');
        $this->parameter = 3;
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
     * Set parameter
     *
     * @param integer $parameter
     *
     * @return LoanParameter
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * Get parameter
     *
     * @return int
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return LoanParameter
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LoanParameter
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
