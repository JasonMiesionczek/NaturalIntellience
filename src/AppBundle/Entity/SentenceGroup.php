<?php

namespace AppBundle\Entity;

use AppBundle\Service\SentenceService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SentenceGroup
 *
 * @ORM\Table(name="sentence_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SentenceGroupRepository")
 */
class SentenceGroup
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Sentence", mappedBy="groups")
     */
    private $sentences;

    /** @var SentenceService */
    private $sentService;

    public function __construct()
    {
        $this->sentences = new ArrayCollection();
    }

    /**
     * @param SentenceService $sentenceService
     */
    public function setSentenceService(SentenceService $sentenceService)
    {
        $this->sentService = $sentenceService;
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
     * Set name
     *
     * @param string $name
     * @return SentenceGroup
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
     * Get count of terms for this group
     *
     * @return int
     */
    public function getSentenceCount()
    {
        return $this->getSentences()->count();
    }

    /**
     * Add sentences
     *
     * @param \AppBundle\Entity\Sentence $sentences
     * @return SentenceGroup
     */
    public function addSentence(\AppBundle\Entity\Sentence $sentences)
    {
        $this->sentences[] = $sentences;

        return $this;
    }

    /**
     * Remove sentences
     *
     * @param \AppBundle\Entity\Sentence $sentences
     */
    public function removeSentence(\AppBundle\Entity\Sentence $sentences)
    {
        $this->sentences->removeElement($sentences);
    }

    /**
     * Get sentences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSentences()
    {
        return $this->sentences;
    }
}
