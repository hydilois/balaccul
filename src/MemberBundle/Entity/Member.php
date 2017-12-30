<?php

namespace MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Member
 *
 * @ORM\Table(name="member")
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\MemberRepository")
 */
class Member{
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=255)
     */
    private $sex;

    /**
     * @var \Date
     *
     * @ORM\Column(name="dateOfBirth", type="date", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="placeOfBirth", type="string", length=255, nullable=true)
     */
    private $placeOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="occupation", type="string", length=255, nullable=true)
     */
    private $occupation;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="nicNumber", type="string", length=255, nullable=true)
     */
    private $nicNumber;

    /**
     * @var \Date
     *
     * @ORM\Column(name="issuedOn", type="date", nullable=true)
     */
    private $issuedOn;

    /**
     * @var string
     *
     * @ORM\Column(name="issuedAt", type="string", length=255, nullable=true)
     */
    private $issuedAt;

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
     * @var int
     *
     * @ORM\Column(name="memberNumber", type="bigint", nullable=true, unique=true)
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
     * @var int
     *
     * @ORM\Column(name="registration_fees", type="bigint")
     */
    private $registrationFees;


    /**
     * @var int
     *
     * @ORM\Column(name="building_fees", type="bigint")
     */
    private $buildingFees;

    /**
     * @var int
     *
     * @ORM\Column(name="share", type="bigint")
     */
    private $share;

    /**
     * @var int
     *
     * @ORM\Column(name="saving", type="bigint")
     */
    private $saving;

    /**
     * @var int
     *
     * @ORM\Column(name="deposit", type="bigint")
     */
    private $deposit;



    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=255, nullable=true)
     */
    private $phoneNumber;

    public function __toString(){
        return $this->name;
        // return $this->name." -- ".$this->memberNumber;
    }


    public function __construct(){
        // The default date of the membership creation is now
        $this->membershipDateCreation = new \DateTime('now');
        $this->issuedOn = new \DateTime('now');
        $this->dateOfBirth = new \DateTime('now');
        $this->buildingFees = 0;
        $this->registrationFees = 0;
        $this->share = 0;
        $this->saving = 0;
        $this->deposit = 0;
        $this->isAproved = true;
        $this->aprovedBy = "BOARD OF DIRECTORS";
        $this->doneAt = "BAMENDA";
        $this->occupation = "TRADER";
        $this->witnessName = "//";
        $this->proposedBy = "//";
        $this->phoneNumber = "//";
        $this->address = "//";
        $this->placeOfBirth = "//";
        $this->issuedAt = "//";
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Member
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
     * Set sex
     *
     * @param string $sex
     *
     * @return Member
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set dateOfBirth
     *
     * @param \Date $dateOfBirth
     *
     * @return Member
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \Date
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set placeOfBirth
     *
     * @param string $placeOfBirth
     *
     * @return Member
     */
    public function setPlaceOfBirth($placeOfBirth)
    {
        $this->placeOfBirth = $placeOfBirth;

        return $this;
    }

    /**
     * Get placeOfBirth
     *
     * @return string
     */
    public function getPlaceOfBirth()
    {
        return $this->placeOfBirth;
    }

    /**
     * Set occupation
     *
     * @param string $occupation
     *
     * @return Member
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;

        return $this;
    }

    /**
     * Get occupation
     *
     * @return string
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Member
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
     * Set nicNumber
     *
     * @param string $nicNumber
     *
     * @return Member
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
     * Set issuedOn
     *
     * @param \Date $issuedOn
     *
     * @return Member
     */
    public function setIssuedOn($issuedOn)
    {
        $this->issuedOn = $issuedOn;

        return $this;
    }

    /**
     * Get issuedOn
     *
     * @return \Date
     */
    public function getIssuedOn()
    {
        return $this->issuedOn;
    }

    /**
     * Set issuedAt
     *
     * @param string $issuedAt
     *
     * @return Member
     */
    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }

    /**
     * Get issuedAt
     *
     * @return string
     */
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    /**
     * Set proposedBy
     *
     * @param string $proposedBy
     *
     * @return Member
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
     * @return Member
     */
    public function setIsAproved($isAproved)
    {
        $this->isAproved = $isAproved;

        return $this;
    }

    /**
     * Get isAproved
     *
     * @return bool
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
     * @return Member
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
     * Set doneAt
     *
     * @param string $doneAt
     *
     * @return Member
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
     * @param \Date $membershipDateCreation
     *
     * @return Member
     */
    public function setMembershipDateCreation($membershipDateCreation)
    {
        $this->membershipDateCreation = $membershipDateCreation;

        return $this;
    }

    /**
     * Get membershipDateCreation
     *
     * @return \Date
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
     * @return Member
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
     * @return Member
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
     * @return Member
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

    /**
     * Set memberNumber
     *
     * @param integer $memberNumber
     *
     * @return Member
     */
    public function setMemberNumber($memberNumber)
    {
        $this->memberNumber = $memberNumber;

        return $this;
    }

    /**
     * Get memberNumber
     *
     * @return integer
     */
    public function getMemberNumber()
    {
        return $this->memberNumber;
    }

    /**
     * Set buildingFees
     *
     * @param integer $buildingFees
     *
     * @return Member
     */
    public function setBuildingFees($buildingFees)
    {
        $this->buildingFees = $buildingFees;

        return $this;
    }

    /**
     * Get buildingFees
     *
     * @return integer
     */
    public function getBuildingFees()
    {
        return $this->buildingFees;
    }

    /**
     * Set share
     *
     * @param integer $share
     *
     * @return Member
     */
    public function setShare($share)
    {
        $this->share = $share;

        return $this;
    }

    /**
     * Get share
     *
     * @return integer
     */
    public function getShare()
    {
        return $this->share;
    }

    /**
     * Set saving
     *
     * @param integer $saving
     *
     * @return Member
     */
    public function setSaving($saving)
    {
        $this->saving = $saving;

        return $this;
    }

    /**
     * Get saving
     *
     * @return integer
     */
    public function getSaving()
    {
        return $this->saving;
    }

    /**
     * Set deposit
     *
     * @param integer $deposit
     *
     * @return Member
     */
    public function setDeposit($deposit)
    {
        $this->deposit = $deposit;

        return $this;
    }

    /**
     * Get deposit
     *
     * @return integer
     */
    public function getDeposit()
    {
        return $this->deposit;
    }
}
