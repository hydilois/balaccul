<?php

namespace MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Beneficiary
 *
 * @ORM\Table(name="beneficiary")
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\BeneficiaryRepository")
 */
class Beneficiary
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
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\Member")
     * @ORM\JoinColumn(name="id_member")
     */
    private $idMember;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="relation", type="string", length=255)
     */
    private $relation;

    /**
     * @var int
     *
     * @ORM\Column(name="ratio", type="integer")
     */
    private $ratio;

    public function __toString(){
        return $this->name;
        // return $this->name." -- ".$this->memberNumber;
    }

    public function __construct(){
        $this->ratio = 0;
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
     * @return Beneficiary
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
     * Set relation
     *
     * @param string $relation
     *
     * @return Beneficiary
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * Get relation
     *
     * @return string
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Set ratio
     *
     * @param integer $ratio
     *
     * @return Beneficiary
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;

        return $this;
    }

    /**
     * Get ratio
     *
     * @return int
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * Set idMember
     *
     * @param \MemberBundle\Entity\Member $idMember
     *
     * @return Beneficiary
     */
    public function setIdMember(\MemberBundle\Entity\Member $idMember = null)
    {
        $this->idMember = $idMember;

        return $this;
    }

    /**
     * Get idMember
     *
     * @return \MemberBundle\Entity\Member
     */
    public function getIdMember()
    {
        return $this->idMember;
    }
}
