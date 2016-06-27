<?php
namespace AppBundle\Service;

use AppBundle\Entity\Recording;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class AnalysisEndpoint
{
    private $analysis;

    public function __construct(AnalysisService $analysis)
    {
        $this->analysis = $analysis;
    }

    public function getAnalysis($id, $apiToken)
    {
        return $this->analysis->getAnalysis($id, $apiToken);
    }

    public function getRecordings($analysisId, $apiToken)
    {
        $recordings = $this->analysis->getRecordings($analysisId, $apiToken);
        return array_map(function($recording) {
            /** @var Recording $recording */
            return $recording->toArray();
        }, $recordings);
    }

    public function getRecording($analysisId, $recordingId, $apiToken)
    {
        $recordings = $this->getRecordings($analysisId, $apiToken);
        foreach ($recordings as $recording) {
            /** @var Recording $recording */
            if ($recording->getId() === $recordingId) {
                return $recording->toArray();
            }
        }

        throw new NotFoundResourceException();
    }

    public function setCurrentRecording($analysisId, $recordingId, $apiToken, $num)
    {
        $analysis = $this->getAnalysis($analysisId, $apiToken);
        $this->analysis->setCurrentRecording($analysis, $recordingId, $num);
    }
}
