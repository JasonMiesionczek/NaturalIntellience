<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Analysis
 *
 * @ORM\Table(name="analysis")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnalysisRepository")
 */
class Analysis
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
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="datetime", nullable=true)
     */
    private $startTime = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endTime", type="datetime", nullable=true)
     */
    private $endTime = null;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="analysis")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $subject;

    /**
     * @var Recording[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Recording", mappedBy="analysis")
     */
    private $recordings;

    /**
     * the issue
     * @var string
     * @ORM\Column(name="issue", type="string", length=255, nullable=true)
     */
    private $issue;

    /**
     * [$user description]
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="analysis")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @var
     * @ORM\Column(name="currentRecording", type="integer", nullable=true)
     */
    private $currentRecording = null;

    /**
     * @var int
     * @ORM\Column(name="currentRecordingNum", type="integer", nullable=true)
     */
    private $currentRecordingNum = 0;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Analysis
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return Analysis
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set subject
     *
     * @param \AppBundle\Entity\Subject $subject
     * @return Analysis
     */
    public function setSubject(\AppBundle\Entity\Subject $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return \AppBundle\Entity\Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recordings = new ArrayCollection();
        $this->recordings = new ArrayCollection();
    }

    /**
     * Add recordings
     *
     * @param \AppBundle\Entity\Recording $recordings
     * @return Analysis
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
     * Set issue
     *
     * @param string $issue
     * @return Analysis
     */
    public function setIssue($issue)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue
     *
     * @return string
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Analysis
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
     * Set currentRecording
     *
     * @param integer $currentRecording
     * @return Analysis
     */
    public function setCurrentRecording($currentRecording)
    {
        $this->currentRecording = $currentRecording;

        return $this;
    }

    /**
     * Get currentRecording
     *
     * @return integer 
     */
    public function getCurrentRecording()
    {
        return $this->currentRecording;
    }

    /**
     * Set currentRecordingNum
     *
     * @param integer $currentRecordingNum
     * @return Analysis
     */
    public function setCurrentRecordingNum($currentRecordingNum)
    {
        $this->currentRecordingNum = $currentRecordingNum;

        return $this;
    }

    /**
     * Get currentRecordingNum
     *
     * @return integer 
     */
    public function getCurrentRecordingNum()
    {
        return $this->currentRecordingNum;
    }
}
