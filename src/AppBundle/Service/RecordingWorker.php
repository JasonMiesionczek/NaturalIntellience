<?php
namespace AppBundle\Service;

use AppBundle\Entity\Recording;
use Symfony\Component\Process\ProcessBuilder;

class RecordingWorker
{
    /**
     * @var Recording
     */
    private $recording;
    private $port;
    /** @var ProcessBuilder  */
    private $builder;
    private $filename;

    public function __construct(Recording $recording, $port)
    {
        $this->recording = $recording;
        $this->port = $port;
        $this->builder = new ProcessBuilder();
        $recordingId = $recording->getId();
        $analysisId = $recording->getAnalysis()->getId();
        $subject = $recording->getAnalysis()->getSubject()->getFullName();
        $this->filename = sprintf('%s-%s-%s', str_replace(' ', '_', $subject), $analysisId, $recordingId);
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function start()
    {
        $pathToScreen = trim(shell_exec("which screen"));
        $pathToNode = trim(shell_exec("which node"));

        $waitCount = 0;
        $waitMax = 10;
        $waitDelay = 20000;
        $proc = $this->builder
            ->setPrefix($pathToScreen)
            ->add('-dmS')
            ->add(sprintf('worker-%s', $this->port))
            ->add($pathToNode)
            ->add('/worker/server.js')
            ->add($this->port)
            ->add($this->filename)
            ->getProcess();
        file_put_contents('/tmp/cmd.log', $proc->getCommandLine());
        $proc->run();
        if ($proc->isSuccessful()) {
            while (!$this->workerIsRunning() && $waitCount < $waitMax) {
                usleep($waitDelay);
                $waitCount++;
            }
        } else {
            throw new \Exception($proc->getErrorOutput());
        }
    }

    /**
     * @return bool
     */
    public function stop()
    {
        if ($this->workerIsRunning()) {
            $builder = new ProcessBuilder();

            $proc = $builder->setPrefix('screen')
                ->add('-X')
                ->add('-S')
                ->add(sprintf('worker-%s', $this->port))
                ->add('quit')
                ->getProcess();

            $proc->run();

            return $proc->isSuccessful();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function workerIsRunning()
    {
        $builder = new ProcessBuilder();

        $proc = $builder
            ->setPrefix('fuser')
            ->add(sprintf('%s/tcp', $this->port))
            ->getProcess();

        $proc->run();
        return ($proc->getExitCode() === 0);
    }
}