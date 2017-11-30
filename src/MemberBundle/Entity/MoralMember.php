<?php

namespace MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Member
 *
 * @ORM\Table(name="moralmember")
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\MoralMemberRepository")
 */
class MoralMember{
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
     * @ORM\Column(name="socialReason", type="string", length=255, nullable=true)
     */
    private $socialReason;

    /**
     * @var \Date
     *
     * @ORM\Column(name="dateOfCreation", type="date", nullable=true)
     */
    private $dateOfCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="proposedBy", type="string", length=255, nullable=true)
     */
    private $proposedBy;

    /**
     * @var bool
     *
     * @ORM\Column(name="isAproved", type="boolean", nullable=true)
     */
    private $isAproved;

    /**
     * @var string
     *
     * @ORM\Column(name="aprovedBy", type="string", length=255, nullable=true)
     */
    private $aprovedBy;

    /**
     * @var string
     *
     * @ORM\Column(name="memberNumber", type="string", length=255, nullable=true)
     */
    private $memberNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="doneAt", type="string", length=255, nullable=true)
     */
    private $doneAt;

    /**
     * @var \Date
     *
     * @ORM\Column(name="membershipDateCreation", type="date", nullable=true)
     */
    private $membershipDateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="witnessName", type="string", length=255, nullable=true)
     */
    private $witnessName;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=255, nullable=true)
     */
    private $phoneNumber;


    /**
     * @var int
     *
     * @ORM\Column(name="registration_fees", type="bigint")
     */
    private $registrationFees;


    public function __toString(){
        return $this->socialReason;
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

    public function __construct(){

        // The default date of the membership creation is now
        $this->membershipDateCreation = new \DateTime('now');
    }


    /**
     * Set socialReason
     *
     * @param string $socialReason
     *
     * @return MoralMember
     */
    public function setSocialReason($socialReason)
    {
        $this->socialReason = $socialReason;

        return $this;
    }

    /**
     * Get socialReason
     *
     * @return string
     */
    public function getSocialReason()
    {
        return $this->socialReason;
    }

    /**
     * Set dateOfCreation
     *
     * @param \DateTime $dateOfCreation
     *
     * @return MoralMember
     */
    public function setDateOfCreation($dateOfCreation)
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    /**
     * Get dateOfCreation
     *
     * @return \DateTime
     */
    public function getDateOfCreation()
    {
        return $this->dateOfCreation;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return MoralMember
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set proposedBy
     *
     * @param string $proposedBy
     *
     * @return MoralMember
     */
    public function setProposedBy($proposedBy)
    {
        $this->proposedBy = $proposedBy;

        return $this;
    }

    /**
     * Get proposedBy
     *
     * @return string
     */
    public function getProposedBy()
    {
        return $this->proposedBy;
    }

    /**
     * Set isAproved
     *
     * @param boolean $isAproved
     *
     * @return MoralMember
     */
    public function setIsAproved($isAproved)
    {
        $this->isAproved = $isAproved;

        return $this;
    }

    /**
     * Get isAproved
     *
     * @return boolean
     */
    public function getIsAproved()
    {
        return $this->isAproved;
    }

    /**
     * Set aprovedBy
     *
     * @param string $aprovedBy
     *
     * @return MoralMember
     */
    public function setAprovedBy($aprovedBy)
    {
        $this->aprovedBy = $aprovedBy;

        return $this;
    }

    /**
     * Get aprovedBy
     *
     * @return string
     */
    public function getAprovedBy()
    {
        return $this->aprovedBy;
    }

    /**
     * Set memberNumber
     *
     * @param string $memberNumber
     *
     * @return MoralMember
     */
    public function setMemberNumber($memberNumber)
    {
        $this->memberNumber = $memberNumber;

        return $this;
    }

    /**
     * Get memberNumber
     *
     * @return string
     */
    public function getMemberNumber()
    {
        return $this->memberNumber;
    }

    /**
     * Set doneAt
     *
     * @param string $doneAt
     *
     * @return MoralMember
     */
    public function setDoneAt($doneAt)
    {
        $this->doneAt = $doneAt;

        return $this;
    }

    /**
     * Get doneAt
     *
     * @return string
     */
    public function getDoneAt()
    {
        return $this->doneAt;
    }

    /**
     * Set membershipDateCreation
     *
     * @param \DateTime $membershipDateCreation
     *
     * @return MoralMember
     */
    public function setMembershipDateCreation($membershipDateCreation)
    {
        $this->membershipDateCreation = $membershipDateCreation;

        return $this;
    }

    /**
     * Get membershipDateCreation
     *
     * @return \DateTime
     */
    public function getMembershipDateCreation()
    {
        return $this->membershipDateCreation;
    }

    /**
     * Set witnessName
     *
     * @param string $witnessName
     *
     * @return MoralMember
     */
    public function setWitnessName($witnessName)
    {
        $this->witnessName = $witnessName;

        return $this;
    }

    /**
     * Get witnessName
     *
     * @return string
     */
    public function getWitnessName()
    {
        return $this->witnessName;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return MoralMember
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set registrationFees
     *
     * @param integer $registrationFees
     *
     * @return MoralMember
     */
    public function setRegistrationFees($registrationFees)
    {
        $this->registrationFees = $registrationFees;

        return $this;
    }

    /**
     * Get registrationFees
     *
     * @return integer
     */
    public function getRegistrationFees()
    {
        return $this->registrationFees;
    }
}
