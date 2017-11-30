<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deposit
 *
 * @ORM\Table(name="deposit")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\DepositRepository")
 */
class Deposit extends Account
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
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\Member")
     * @ORM\JoinColumn(name="id_physical_member")
     */
    private $physicalMember;

    /**
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\MoralMember")
     * @ORM\JoinColumn(name="id_moral_member")
     */
    private $moralMember;


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
     * Set physicalMember
     *
     * @param \MemberBundle\Entity\Member $physicalMember
     *
     * @return Deposit
     */
    public function setPhysicalMember(\MemberBundle\Entity\Member $physicalMember = null)
    {
        $this->physicalMember = $physicalMember;

        return $this;
    }

    /**
     * Get physicalMember
     *
     * @return \MemberBundle\Entity\Member
     */
    public function getPhysicalMember()
    {
        return $this->physicalMember;
    }

    /**
     * Set moralMember
     *
     * @param \MemberBundle\Entity\MoralMember $moralMember
     *
     * @return Deposit
     */
    public function setMoralMember(\MemberBundle\Entity\MoralMember $moralMember = null)
    {
        $this->moralMember = $moralMember;

        return $this;
    }

    /**
     * Get moralMember
     *
     * @return \MemberBundle\Entity\MoralMember
     */
    public function getMoralMember()
    {
        return $this->moralMember;
    }
}
