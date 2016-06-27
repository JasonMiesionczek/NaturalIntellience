<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Subject", mappedBy="user")
     */
    private $subjects;

    /**
     * @var
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName = null;

    /**
     * @var
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName = null;

    /**
     * [$analysis description]
     * @var [type]
     * @ORM\OneToMany(targetEntity="Analysis", mappedBy="user")
     */
    private $analysis;

    /**
     * [$recordings description]
     * @var [type]
     * @ORM\OneToMany(targetEntity="Recording", mappedBy="user")
     */
    private $recordings;

    /**
     * @var
     * @ORM\Column(name="apiToken", type="string", length=255, nullable=true)
     */
    private $apiToken = null;

    public function __construct()
    {
        parent::__construct();
        // your own logic

        $this->subjects = new ArrayCollection();
        $this->analysis = new ArrayCollection();
        $this->recordings = new ArrayCollection();
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * Add subjects
     *
     * @param \AppBundle\Entity\Subject $subjects
     * @return User
     */
    public function addSubject(\AppBundle\Entity\Subject $subjects)
    {
        $this->subjects[] = $subjects;

        return $this;
    }

    /**
     * Remove subjects
     *
     * @param \AppBundle\Entity\Subject $subjects
     */
    public function removeSubject(\AppBundle\Entity\Subject $subjects)
    {
        $this->subjects->removeElement($subjects);
    }

    /**
     * Get subjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Add analysis
     *
     * @param \AppBundle\Entity\Analysis $analysis
     * @return User
     */
    public function addAnalysi(\AppBundle\Entity\Analysis $analysis)
    {
        $this->analysis[] = $analysis;

        return $this;
    }

    /**
     * Remove analysis
     *
     * @param \AppBundle\Entity\Analysis $analysis
     */
    public function removeAnalysi(\AppBundle\Entity\Analysis $analysis)
    {
        $this->analysis->removeElement($analysis);
    }

    /**
     * Get analysis
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnalysis()
    {
        return $this->analysis;
    }

    /**
     * Add recordings
     *
     * @param \AppBundle\Entity\Recording $recordings
     * @return User
     */
    public function addRecording(\AppBundle\Entity\Recording $recordings)
    {
        $this->recordings[] = $recordings;

        return $this;
    }

    /**
     * Remove recordings
     *
     * @param \AppBundle\Entity\Recording $recordings
     */
    public function removeRecording(\AppBundle\Entity\Recording $recordings)
    {
        $this->recordings->removeElement($recordings);
    }

    /**
     * Get recordings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecordings()
    {
        return $this->recordings;
    }

    /**
     * Set apiToken
     *
     * @param string $apiToken
     * @return User
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * Get apiToken
     *
     * @return string 
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }
}
