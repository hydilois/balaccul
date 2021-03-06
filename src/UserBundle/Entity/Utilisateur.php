<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UtilisateurRepository")
 */
class Utilisateur extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100, unique=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroTelephone", type="string", length=100, unique=true)
     */
    private $numeroTelephone;


    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Groupe", cascade={"persist"})
     * @ORM\JoinColumn(name="groupe_id", referencedColumnName="id",nullable=false)
     */
    private $groupe;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastActivity", type="datetime")
     */
    private $lastActivity;

    

    public function __toString(){
        return $this->nom;
    }

    public function __construct(){
        $this->setLastActivity(new \DateTime());
    }

    public function isActiveNow(){
        $this->setLastActivity(new \DateTime());
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set numeroTelephone
     *
     * @param string $numeroTelephone
     *
     * @return Utilisateur
     */
    public function setNumeroTelephone($numeroTelephone)
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }

    /**
     * Get numeroTelephone
     *
     * @return string
     */
    public function getNumeroTelephone()
    {
        return $this->numeroTelephone;
    }

    /**
     * Set groupe
     *
     * @param \UserBundle\Entity\Groupe $groupe
     *
     * @return Utilisateur
     */
    public function setGroupe(\UserBundle\Entity\Groupe $groupe)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return \UserBundle\Entity\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     *
     * @return Utilisateur
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }
}
