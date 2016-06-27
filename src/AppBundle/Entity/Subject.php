<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubjectRepository")
 */
class Subject
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
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="date")
     */
    private $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="birthplace", type="string", length=255)
     */
    private $birthplace;

    /**
     * @var string
     *
     * @ORM\Column(name="currentResidence", type="string", length=255)
     */
    private $currentResidence;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer")
     */
    private $height;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     * @var int
     *
     * @ORM\Column(name="gender", type="integer")
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="occupation", type="string", length=255)
     */
    private $occupation;

    /**
     * @var string
     *
     * @ORM\Column(name="education", type="string", length=255)
     */
    private $education;

    /**
     * @var string
     *
     * @ORM\Column(name="injuries", type="string", length=255)
     */
    private $injuries;

    /**
     * @var string
     *
     * @ORM\Column(name="heritage", type="string", length=255)
     */
    private $heritage;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="subjects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Analysis", mappedBy="subject")
     */
    private $analysis;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Subject
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
     * @return Subject
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
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     * @return Subject
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime 
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set birthplace
     *
     * @param string $birthplace
     * @return Subject
     */
    public function setBirthplace($birthplace)
    {
        $this->birthplace = $birthplace;

        return $this;
    }

    /**
     * Get birthplace
     *
     * @return string 
     */
    public function getBirthplace()
    {
        return $this->birthplace;
    }

    /**
     * Set currentResidence
     *
     * @param string $currentResidence
     * @return Subject
     */
    public function setCurrentResidence($currentResidence)
    {
        $this->currentResidence = $currentResidence;

        return $this;
    }

    /**
     * Get currentResidence
     *
     * @return string 
     */
    public function getCurrentResidence()
    {
        return $this->currentResidence;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Subject
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return Subject
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     * @return Subject
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return integer 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set occupation
     *
     * @param string $occupation
     * @return Subject
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
     * Set education
     *
     * @param string $education
     * @return Subject
     */
    public function setEducation($education)
    {
        $this->education = $education;

        return $this;
    }

    /**
     * Get education
     *
     * @return string 
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * Set injuries
     *
     * @param string $injuries
     * @return Subject
     */
    public function setInjuries($injuries)
    {
        $this->injuries = $injuries;

        return $this;
    }

    /**
     * Get injuries
     *
     * @return string 
     */
    public function getInjuries()
    {
        return $this->injuries;
    }

    /**
     * Set heritage
     *
     * @param string $heritage
     * @return Subject
     */
    public function setHeritage($heritage)
    {
        $this->heritage = $heritage;

        return $this;
    }

    /**
     * Get heritage
     *
     * @return string 
     */
    public function getHeritage()
    {
        return $this->heritage;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Subject
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->analysis = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add analysis
     *
     * @param \AppBundle\Entity\Analysis $analysis
     * @return Subject
     */
    public function addAnalysis(\AppBundle\Entity\Analysis $analysis)
    {
        $this->analysis[] = $analysis;

        return $this;
    }

    /**
     * Remove analysis
     *
     * @param \AppBundle\Entity\Analysis $analysis
     */
    public function removeAnalysis(\AppBundle\Entity\Analysis $analysis)
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
     * Add analysis
     *
     * @param \AppBundle\Entity\Analysis $analysis
     * @return Subject
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
}
