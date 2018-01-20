<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoanHistory
 *
 * @ORM\Table(name="loan_history")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\LoanHistoryRepository")
 */
class LoanHistory{
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
     * @ORM\Column(name="monthlyPayement", type="bigint")
     */
    private $monthlyPayement;

    /**
     * @var int
     *
     * @ORM\Column(name="interest", type="bigint")
     */
    private $interest;

    /**
     * @var int
     *
     * @ORM\Column(name="remainAmount", type="bigint")
     */
    private $remainAmount;

    /**
     * @var int
     *
     * @ORM\Column(name="newInterest", type="bigint")
     */
    private $newInterest;

    /**
     * @var int
     *
     * @ORM\Column(name="unpaidInterest", type="bigint")
     */
    private $unpaidInterest;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOperation", type="date")
     */
    private $dateOperation;

    /**
     * @var bool
     *
     * @ORM\Column(name="closeLoan", type="boolean", nullable=true)
     */
    private $closeLoan;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="id_currentUser", referencedColumnName="id")
     */
    private $currentUser;


    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\Loan")
     * @ORM\JoinColumn(name="id_loan", referencedColumnName="id")
     */
    private $loan;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_confirmed", type="boolean")
     */
    private $isConfirmed;


    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $userConfirmed;


    public function __construct(){
        // $this->dateOperation = new \DateTime('now');
        $this->monthlyPayement = 0;
        $this->interest = 0;
        $this->unpaidInterest = 0;
        $this->newInterest = 0;
        $this->isConfirmed = false;
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
     * Set monthlyPayement
     *
     * @param integer $monthlyPayement
     *
     * @return LoanHistory
     */
    public function setMonthlyPayement($monthlyPayement)
    {
        $this->monthlyPayement = $monthlyPayement;

        return $this;
    }

    /**
     * Get monthlyPayement
     *
     * @return int
     */
    public function getMonthlyPayement()
    {
        return $this->monthlyPayement;
    }

    /**
     * Set interest
     *
     * @param integer $interest
     *
     * @return LoanHistory
     */
    public function setInterest($interest)
    {
        $this->interest = $interest;

        return $this;
    }

    /**
     * Get interest
     *
     * @return int
     */
    public function getInterest()
    {
        return $this->interest;
    }

    /**
     * Set remainAmount
     *
     * @param integer $remainAmount
     *
     * @return LoanHistory
     */
    public function setRemainAmount($remainAmount)
    {
        $this->remainAmount = $remainAmount;

        return $this;
    }

    /**
     * Get remainAmount
     *
     * @return int
     */
    public function getRemainAmount()
    {
        return $this->remainAmount;
    }

    /**
     * Set newInterest
     *
     * @param integer $newInterest
     *
     * @return LoanHistory
     */
    public function setNewInterest($newInterest)
    {
        $this->newInterest = $newInterest;

        return $this;
    }

    /**
     * Get newInterest
     *
     * @return int
     */
    public function getNewInterest()
    {
        return $this->newInterest;
    }

    /**
     * Set dateOperation
     *
     * @param \DateTime $dateOperation
     *
     * @return LoanHistory
     */
    public function setDateOperation($dateOperation)
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    /**
     * Get dateOperation
     *
     * @return \DateTime
     */
    public function getDateOperation()
    {
        return $this->dateOperation;
    }

    /**
     * Set closeLoan
     *
     * @param boolean $closeLoan
     *
     * @return LoanHistory
     */
    public function setCloseLoan($closeLoan)
    {
        $this->closeLoan = $closeLoan;

        return $this;
    }

    /**
     * Get closeLoan
     *
     * @return bool
     */
    public function getCloseLoan()
    {
        return $this->closeLoan;
    }

    /**
     * Set currentUser
     *
     * @param \UserBundle\Entity\Utilisateur $currentUser
     *
     * @return LoanHistory
     */
    public function setCurrentUser(\UserBundle\Entity\Utilisateur $currentUser = null)
    {
        $this->currentUser = $currentUser;

        return $this;
    }

    /**
     * Get currentUser
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Set loan
     *
     * @param \AccountBundle\Entity\Loan $loan
     *
     * @return LoanHistory
     */
    public function setLoan(\AccountBundle\Entity\Loan $loan = null)
    {
        $this->loan = $loan;

        return $this;
    }

    /**
     * Get loan
     *
     * @return \AccountBundle\Entity\Loan
     */
    public function getLoan()
    {
        return $this->loan;
    }

    /**
     * Set unpaidInterest
     *
     * @param integer $unpaidInterest
     *
     * @return LoanHistory
     */
    public function setUnpaidInterest($unpaidInterest)
    {
        $this->unpaidInterest = $unpaidInterest;

        return $this;
    }

    /**
     * Get unpaidInterest
     *
     * @return integer
     */
    public function getUnpaidInterest()
    {
        return $this->unpaidInterest;
    }

    /**
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     *
     * @return LoanHistory
     */
    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    /**
     * Get isConfirmed
     *
     * @return boolean
     */
    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * Set userConfirmed
     *
     * @param \UserBundle\Entity\Utilisateur $userConfirmed
     *
     * @return LoanHistory
     */
    public function setUserConfirmed(\UserBundle\Entity\Utilisateur $userConfirmed = null)
    {
        $this->userConfirmed = $userConfirmed;

        return $this;
    }

    /**
     * Get userConfirmed
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getUserConfirmed()
    {
        return $this->userConfirmed;
    }
}
