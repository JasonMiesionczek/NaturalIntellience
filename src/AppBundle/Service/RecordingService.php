<?php

namespace AppBundle\Service;

use AppBundle\Entity\Analysis;
use AppBundle\Entity\ProcessQueue;
use AppBundle\Entity\Recording;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 *
 */
class RecordingService
{
    /**
     * @var int
     */
    const STATUS_PENDING_RECORD = 0;
    /**
     * @var int
     */
    const STATUS_IN_PROGRESS = 1;
    /**
     * @var int
     */
    const STATUS_COMPLETE = 2;
    /**
     * @var int
     */
    const STATUS_PENDING_PROCESS = 3;
    /**
     * @var int
     */
    const STATUS_PENDING_ANALYSIS = 5;
    /**
     * @var int
     */
    const STATUS_PROCESSING = 4;
    /**
     * @var int
     */
    const STATUS_ERROR = -1;

    /**
     * @var int
     */
    const DEFAULT_PORT = 1111;
    /**
     * @var string
     */
    const WORKER_URL = 'http://localhost:<port>';

    /** @var EntityManager */
    private $em;

    private $tokenStorage;

    /**
     * constructor
     * @param EntityManager $em [description]
     */
    public function __construct(EntityManager $em, TokenStorage $token)
    {
        $this->em = $em;
        $this->tokenStorage = $token;
    }

    /**
     * @param Analysis $analysis
     * @param $statement
     * @param bool $autoFlush
     * @return Recording
     */
    public function createRecording(Analysis $analysis, $statement, $autoFlush = false)
    {
        $recording = new Recording();
        $recording->setStatus(self::STATUS_PENDING_RECORD);
        $recording->setWorkerPort(0);
        $recording->setStatement($statement);
        $recording->setAnalysis($analysis);
        $recording->setUser($this->tokenStorage->getToken()->getUser());

        $analysis->addRecording($recording);

        $this->em->persist($recording);
        $this->em->persist($analysis);
        if ($autoFlush) {
            $this->em->flush();
        }

        return $recording;
    }

    /**
     * @param Recording $recording
     * @return Recording
     */
    public function startRecording(Recording $recording)
    {
        $recording->setStartTime(new DateTime());

        $port = $this->findAvailablePort();
        $recording->setWorkerPort($port);
        $recording->setStatus(self::STATUS_IN_PROGRESS);
        $worker = new RecordingWorker($recording, $port);
        $this->em->persist($recording);
        $this->em->flush();

        $worker->start();
        return $recording;
    }

    /**
     * @param Recording $recording
     */
    public function stopRecording(Recording $recording)
    {
        $recording->setEndTime(new DateTime());
        $recording->setStatus(self::STATUS_PENDING_PROCESS);
        /** @var RecordingWorker $worker */
        $port = $recording->getWorkerPort();
        $worker = new RecordingWorker($recording, $port);
        if ($worker->stop()) {
            // add this recording to the processing queue
            $queue = new ProcessQueue();
            $queue->setRecording($recording);
            $queue->setDateAdded(new DateTime());
            $queue->setFilename($worker->getFilename());
            $recording->setProcessQueue($queue);
            $this->em->persist($queue);
        }
        $this->em->persist($recording);
        $this->em->flush();
    }

    /**
     * Scan through all in progress recordings and get the highest port number in use
     */
    private function findAvailablePort()
    {
        $repo = $this->em->getRepository('AppBundle:Recording');
        $inProgress = $repo->findByStatus(self::STATUS_IN_PROGRESS);
        $maxPort = self::DEFAULT_PORT;
        foreach ($inProgress as $inprog) {
            /** @var Recording $inprog */
            if ($inprog->getWorkerPort() > $maxPort) {
                $maxPort = $inprog->getWorkerPort();
            }
        }

        return $maxPort + 1;
    }
}
