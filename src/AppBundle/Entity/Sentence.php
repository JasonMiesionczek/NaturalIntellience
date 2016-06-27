<?php

namespace AppBundle\Entity;

use AppBundle\Service\SentenceService;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sentence
 *
 * @ORM\Table(name="sentence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SentenceRepository")
 */
class Sentence
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
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var SentenceGroup[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\SentenceGroup", inversedBy="sentences")
     * @ORM\JoinTable(name="sentences_groups")
     */
    private $groups;

    /** @var SentenceService */
    private $sentenceService;

    public function setSentenceService(SentenceService $sentenceService)
    {
        $this->sentenceService = $sentenceService;
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
     * Set type
     *
     * @param int $type
     * @return Sentence
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    public function getTypeName()
    {
        return $this->sentenceService->getTypeName($this->getType());
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Sentence
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set responseType
     *
     * @param int $responseType
     * @return Sentence
     */
    public function setResponseType($responseType)
    {
        $this->responseType = $responseType;

        return $this;
    }

    /**
     * Get responseType
     *
     * @return int
     */
    public function getResponseType()
    {
        return $this->responseType;
    }

    public function getResponseTypeName()
    {
        return $this->sentenceService->getResponseTypeName($this->getResponseType());
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add groups
     *
     * @param \AppBundle\Entity\SentenceGroup $groups
     * @return Sentence
     */
    public function addGroup(\AppBundle\Entity\SentenceGroup $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \AppBundle\Entity\SentenceGroup $groups
     */
    public function removeGroup(\AppBundle\Entity\SentenceGroup $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return string
     */
    public function getGroupString()
    {
        return implode(', ', array_map(function($group) {
            /** @var SentenceGroup $group */
            return $group->getName();
        }, $this->groups->toArray()));
    }
}
