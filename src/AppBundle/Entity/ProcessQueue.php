<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProcessQueue
 *
 * @ORM\Table(name="process_queue")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProcessQueueRepository")
 */
class ProcessQueue
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
     * @ORM\Column(name="dateAdded", type="datetime", nullable=true)
     */
    private $dateAdded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateProcessed", type="datetime", nullable=true)
     */
    private $dateProcessed;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Recording")
     * @ORM\JoinColumn(name="recording_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $recording = null;

    /**
     * @var null
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename = null;
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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return ProcessQueue
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set dateProcessed
     *
     * @param \DateTime $dateProcessed
     * @return ProcessQueue
     */
    public function setDateProcessed($dateProcessed)
    {
        $this->dateProcessed = $dateProcessed;

        return $this;
    }

    /**
     * Get dateProcessed
     *
     * @return \DateTime 
     */
    public function getDateProcessed()
    {
        return $this->dateProcessed;
    }

    /**
     * Set recording
     *
     * @param \AppBundle\Entity\Recording $recording
     * @return ProcessQueue
     */
    public function setRecording(\AppBundle\Entity\Recording $recording = null)
    {
        $this->recording = $recording;

        return $this;
    }

    /**
     * Get recording
     *
     * @return \AppBundle\Entity\Recording 
     */
    public function getRecording()
    {
        return $this->recording;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return ProcessQueue
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }
}
