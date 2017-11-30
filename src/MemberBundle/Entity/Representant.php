<?php

namespace MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Representant
 *
 * @ORM\Table(name="representant")
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\RepresentantRepository")
 */
class Representant
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
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\MoralMember")
     * @ORM\JoinColumn(name="id_moralmember")
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
     * @ORM\Column(name="nicNumber", type="string", length=255)
     */
    private $nicNumber;


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
     * @return Representant
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
     * Set nicNumber
     *
     * @param string $nicNumber
     *
     * @return Representant
     */
    public function setNicNumber($nicNumber)
    {
        $this->nicNumber = $nicNumber;

        return $this;
    }

    /**
     * Get nicNumber
     *
     * @return string
     */
    public function getNicNumber()
    {
        return $this->nicNumber;
    }

    /**
     * Set idMember
     *
     * @param \MemberBundle\Entity\MoralMember $idMember
     *
     * @return Representant
     */
    public function setIdMember(\MemberBundle\Entity\MoralMember $idMember = null)
    {
        $this->idMember = $idMember;

        return $this;
    }

    /**
     * Get idMember
     *
     * @return \MemberBundle\Entity\MoralMember
     */
    public function getIdMember()
    {
        return $this->idMember;
    }
}
