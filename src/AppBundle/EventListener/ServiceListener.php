<?php
namespace AppBundle\EventListener;
use AppBundle\Entity\Sentence;
use AppBundle\Entity\SentenceGroup;
use AppBundle\Service\SentenceService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Created by PhpStorm.
 * User: jason
 * Date: 4/22/16
 * Time: 11:10 AM
 */
class ServiceListener
{
    private $sentenceService;

    public function __construct(SentenceService $sentenceService)
    {
        $this->sentenceService = $sentenceService;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SentenceGroup) {
            $entity->setSentenceService($this->sentenceService);
        }
        if ($entity instanceof Sentence) {
            $entity->setSentenceService($this->sentenceService);
        }
    }
}