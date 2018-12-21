<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Loan
 *
 * @ORM\Table(name="loan")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\LoanRepository")
 * @UniqueEntity(fields="loanCode", message="A loan with this code already exist.")
 */
class Loan{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="date")
     * @Assert\NotBlank(message="This field cannot be nul")
     */
    private $deadline;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateLoan", type="date")
     * @Assert\NotBlank(message="This field cannot be nul")
     */
    private $dateLoan;


    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="float")
     * @Assert\NotBlank(message="This field cannot be nul")
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "The min value should be  {{ limit }}",
     *      maxMessage = "The max value should be {{ limit }}"
     * )
     */
    private $rate;


    /**
     * @var int
     *
     * @ORM\Column(name="loanAmount", type="bigint")
     * @Assert\NotBlank(message="This field cannot be nul")
     * @Assert\Range(
     *      min = 1,
     *      max = 15000000,
     *      minMessage = "The min value should be  {{ limit }}",
     *      maxMessage = "The max value should be {{ limit }}"
     * )
     */
    private $loanAmount;


    /**
     * @var int
     *
     * @ORM\Column(name="monthly_payment", type="bigint")
     * @Assert\NotBlank(message="This field cannot be nul")
     * @Assert\Range(
     *      min = 1,
     *      max = 1000000,
     *      minMessage = "The min value should be  {{ limit }}",
     *      maxMessage = "The max value should be {{ limit }}"
     * )
     */
    private $monthlyPayment;

    /**
     * @var int
     *
     * @ORM\Column(name="loan_processing_fees", type="bigint")
     * @Assert\NotBlank(message="This field cannot be nul")
     * @Assert\Range(
     *      min = 0,
     *      max = 500000,
     *      minMessage = "The min value should be  {{ limit }}",
     *      maxMessage = "The max value should be {{ limit }}"
     * )
     */
    private $loanProcessingFees;



    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;



    /**
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\Member")
     * @ORM\JoinColumn(name="id_physical_member")
     */
    private $physicalMember;


    /**
     * @var string
     *
     * @ORM\Column(name="loan_code", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="This field cannot be nul")
     */
    private $loanCode;


    private $loanHistory;

    /**
     * @var int
     */
    private $interestToPayAt;

    /**
     * @var int
     */
    private $numberOfDays;

    /**
     * @var int
     */
    private $numberOfDelinquent;




    public function __construct(){
        //The default status of the account is true
        $this->status = true;

        //the default date of the loan is now
        $this->dateLoan = new \DateTime('now');

        $this->loanProcessingFees = 0;
        $this->loanAmount = 0;
        
        $this->rate = 2;

        $this->monthlyPayment = 0;
        $this->loanCode = "BL-";

        $this->deadline = new \DateTime('now') ;
        $this->deadline->setTimestamp(strtotime('+1 month'));


    }


    public function __toString(){
        return $this->loanCode;
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
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return Loan
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }


    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Loan
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateLoan
     *
     * @param \DateTime $dateLoan
     *
     * @return Loan
     */
    public function setDateLoan($dateLoan)
    {
        $this->dateLoan = $dateLoan;

        return $this;
    }

    /**
     * Get dateLoan
     *
     * @return \DateTime
     */
    public function getDateLoan()
    {
        return $this->dateLoan;
    }

    /**
     * Set loanAmount
     *
     * @param integer $loanAmount
     *
     * @return Loan
     */
    public function setLoanAmount($loanAmount)
    {
        $this->loanAmount = $loanAmount;

        return $this;
    }

    /**
     * Get loanAmount
     *
     * @return integer
     */
    public function getLoanAmount()
    {
        return $this->loanAmount;
    }

    /**
     * Set monthlyPayment
     *
     * @param integer $monthlyPayment
     *
     * @return Loan
     */
    public function setMonthlyPayment($monthlyPayment)
    {
        $this->monthlyPayment = $monthlyPayment;

        return $this;
    }

    /**
     * Get monthlyPayment
     *
     * @return integer
     */
    public function getMonthlyPayment()
    {
        return $this->monthlyPayment;
    }

    /**
     * Set physicalMember
     *
     * @param \MemberBundle\Entity\Member $physicalMember
     *
     * @return Loan
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
     * Set loanCode
     *
     * @param string $loanCode
     *
     * @return Loan
     */
    public function setLoanCode($loanCode)
    {
        $this->loanCode = $loanCode;

        return $this;
    }

    /**
     * Get loanCode
     *
     * @return string
     */
    public function getLoanCode()
    {
        return $this->loanCode;
    }

    /**
     * Set loanProcessingFees
     *
     * @param integer $loanProcessingFees
     *
     * @return Loan
     */
    public function setLoanProcessingFees($loanProcessingFees)
    {
        $this->loanProcessingFees = $loanProcessingFees;

        return $this;
    }

    /**
     * Get loanProcessingFees
     *
     * @return integer
     */
    public function getLoanProcessingFees()
    {
        return $this->loanProcessingFees;
    }

    /**
     * Set rate
     *
     * @param float $rate
     *
     * @return Loan
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }


    public function setLoanHistory($loanHistory)
    {
        $this->loanHistory = $loanHistory;

        // return $this;
    }

    public function getLoanHistory()
    {
        return $this->loanHistory;
    }

    /**
     * @return integer
     */
    public function getInterestToPayAt()
    {
        return $this->interestToPayAt;
    }

    /**
     * @param integer $interestToPayAt
     */
    public function setInterestToPayAt($interestToPayAt)
    {
        $this->interestToPayAt = $interestToPayAt;
    }

    /**
     * @return mixed
     */
    public function getNumberOfDays()
    {
        return $this->numberOfDays;
    }

    /**
     * @param mixed $numberOfDays
     */
    public function setNumberOfDays($numberOfDays)
    {
        $this->numberOfDays = $numberOfDays;
    }

    /**
     * @return int
     */
    public function getNumberOfDelinquent()
    {
        return $this->numberOfDelinquent;
    }

    /**
     * @param int $numberOfDelinquent
     */
    public function setNumberOfDelinquent($numberOfDelinquent)
    {
        $this->numberOfDelinquent = $numberOfDelinquent;
    }

}
