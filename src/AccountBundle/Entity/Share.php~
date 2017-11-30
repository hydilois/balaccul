<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Share
 *
 * @ORM\Table(name="share")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\ShareRepository")
 */
class Share extends Account
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
     * @ORM\ManyToOne(targetEntity="ClassBundle\Entity\InternalAccount")
     * @ORM\JoinColumn(name="id_internal_account")
     */
    private $nternalAccount;


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
     * Set nternalAccount
     *
     * @param \ClassBundle\Entity\InternalAccount $nternalAccount
     *
     * @return Share
     */
    public function setNternalAccount(\ClassBundle\Entity\InternalAccount $nternalAccount = null)
    {
        $this->nternalAccount = $nternalAccount;

        return $this;
    }

    /**
     * Get nternalAccount
     *
     * @return \ClassBundle\Entity\InternalAccount
     */
    public function getNternalAccount()
    {
        return $this->nternalAccount;
    }

    /**
     * Set physicalMember
     *
     * @param \MemberBundle\Entity\Member $physicalMember
     *
     * @return Share
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
     * @return Share
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
