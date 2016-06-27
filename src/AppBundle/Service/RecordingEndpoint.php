<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 5/2/16
 * Time: 4:29 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\Recording;
use Doctrine\ORM\EntityManager;

class RecordingEndpoint
{
    /** @var EntityManager */
    private $em;

    /** @var RecordingService */
    private $service;

    public function __construct(EntityManager $em, RecordingService $service)
    {
        $this->em = $em;
        $this->service = $service;
    }

    public function start($recordingId, $apiToken)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('apiToken' => $apiToken));
        $repo = $this->em->getRepository('AppBundle:Recording');
        /** @var Recording $recording */
        $recording = $repo->findOneBy(array('id' => $recordingId, 'user' => $user));

        if (!$recording) {
            throw new \Exception("Could not find recording with id: $recordingId");
        }

        $this->service->startRecording($recording);
        return $recording->toArray();
    }

    public function stop($recordingId, $apiToken)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('apiToken' => $apiToken));
        $repo = $this->em->getRepository('AppBundle:Recording');
        /** @var Recording $recording */
        $recording = $repo->findOneBy(array('id' => $recordingId, 'user' => $user));

        if (!$recording) {
            throw new \Exception("Could not find recording with id: $recordingId");
        }

        $this->service->stopRecording($recording);
        return $recording->toArray();
    }
}