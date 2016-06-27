<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recording
 *
 * @ORM\Table(name="recording")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecordingRepository")
 */
class Recording
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
     * @ORM\Column(name="statement", type="string", length=255, nullable=true)
     */
    private $statement = null;

    /**
     * @var string
     *
     * @ORM\Column(name="waveFilename", type="string", length=255, nullable=true)
     */
    private $waveFilename = null;

    /**
     * @var string
     *
     * @ORM\Column(name="waveformImage", type="string", length=255, nullable=true)
     */
    private $waveformImage = null;

    /**
     * @var string
     *
     * @ORM\Column(name="spectrumImage", type="string", length=255, nullable=true)
     */
    private $spectrumImage = null;

    /**
     * @var string
     *
     * @ORM\Column(name="spectrographImage", type="string", length=255, nullable=true)
     */
    private $spectrographImage = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="datetime", nullable=true)
     */
    private $startTime = null;

    /**
     * @var string
     *
     * @ORM\Column(name="endTime", type="datetime", nullable=true)
     */
    private $endTime;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @var Analysis
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Analysis", inversedBy="recordings")
     * @ORM\JoinColumn(name="analysis_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $analysis;

    /**
     * @var int
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var int
     * @ORM\Column(name="workerPort", type="integer")
     */
    private $workerPort;

    /**
     * @var string
     * @ORM\Column(name="recordingError", type="string", length=255, nullable=true)
     */
    private $recordingError = null;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ProcessQueue")
     * @ORM\JoinColumn(name="process_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $processQueue;

    /**
     * [$userId description]
     * @var [type]
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="recordings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    public function __toString()
    {
        return $this->getAnalysis()->getSubject()->getFullName();
    }

    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'port' => $this->getWorkerPort(),
            'statement' => $this->getStatement(),
            'status' => $this->getStatus()
        );
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
     * Set statement
     *
     * @param string $statement
     * @return Recording
     */
    public function setStatement($statement)
    {
        $this->statement = $statement;

        return $this;
    }

    /**
     * Get statement
     *
     * @return string
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * Set waveFilename
     *
     * @param string $waveFilename
     * @return Recording
     */
    public function setWaveFilename($waveFilename)
    {
        $this->waveFilename = $waveFilename;

        return $this;
    }

    /**
     * Get waveFilename
     *
     * @return string
     */
    public function getWaveFilename()
    {
        return $this->waveFilename;
    }

    /**
     * Set waveformImage
     *
     * @param string $waveformImage
     * @return Recording
     */
    public function setWaveformImage($waveformImage)
    {
        $this->waveformImage = $waveformImage;

        return $this;
    }

    /**
     * Get waveformImage
     *
     * @return string
     */
    public function getWaveformImage()
    {
        return $this->waveformImage;
    }

    /**
     * Set spectrumImage
     *
     * @param string $spectrumImage
     * @return Recording
     */
    public function setSpectrumImage($spectrumImage)
    {
        $this->spectrumImage = $spectrumImage;

        return $this;
    }

    /**
     * Get spectrumImage
     *
     * @return string
     */
    public function getSpectrumImage()
    {
        return $this->spectrumImage;
    }

    /**
     * Set spectrographImage
     *
     * @param string $spectrographImage
     * @return Recording
     */
    public function setSpectrographImage($spectrographImage)
    {
        $this->spectrographImage = $spectrographImage;

        return $this;
    }

    /**
     * Get spectrographImage
     *
     * @return string
     */
    public function getSpectrographImage()
    {
        return $this->spectrographImage;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Recording
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
     * @return Recording
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
     * Set analysis
     *
     * @param Analysis $analysis
     * @return Recording
     */
    public function setAnalysis(Analysis $analysis = null)
    {
        $this->analysis = $analysis;

        return $this;
    }

    /**
     * Get analysis
     *
     * @return Analysis
     */
    public function getAnalysis()
    {
        return $this->analysis;
    }

    /**
     * Set status
     *
     * @param int $status
     * @return Recording
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set workerPort
     *
     * @param int $workerPort
     * @return Recording
     */
    public function setWorkerPort($workerPort)
    {
        $this->workerPort = $workerPort;

        return $this;
    }

    /**
     * Get workerPort
     *
     * @return int
     */
    public function getWorkerPort()
    {
        return $this->workerPort;
    }

    /**
     * Set recordingError
     *
     * @param string $recordingError
     * @return Recording
     */
    public function setRecordingError($recordingError)
    {
        $this->recordingError = $recordingError;

        return $this;
    }

    /**
     * Get recordingError
     *
     * @return string
     */
    public function getRecordingError()
    {
        return $this->recordingError;
    }

    /**
     * Set userId
     *
     * @param \AppBundle\Entity\User $userId
     * @return Recording
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get userId
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set processQueue
     *
     * @param \AppBundle\Entity\ProcessQueue $processQueue
     * @return Recording
     */
    public function setProcessQueue(\AppBundle\Entity\ProcessQueue $processQueue = null)
    {
        $this->processQueue = $processQueue;

        return $this;
    }

    /**
     * Get processQueue
     *
     * @return \AppBundle\Entity\ProcessQueue 
     */
    public function getProcessQueue()
    {
        return $this->processQueue;
    }
}
