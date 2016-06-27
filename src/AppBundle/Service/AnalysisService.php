<?php
namespace AppBundle\Service;
use AppBundle\Entity\Sentence;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Analysis;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class to handle generating a new analysis
 */
class AnalysisService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var RecordingService
     */
    private $recording;

    private $token;

    /**
     * default constructor
     * @param EntityManager $em
     * @param RecordingService $recording
     */
    public function __construct(EntityManager $em, RecordingService $recording, TokenStorage $token)
    {
        $this->em = $em;
        $this->recording = $recording;
        $this->token = $token;
    }

    /**
     * [getAnalysis description]
     * @param $id
     * @param $apiToken
     * @return Analysis [description]
     * @throws EntityNotFoundException
     * @throws \Exception
     */
    public function getAnalysis($id, $apiToken)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('apiToken' => $apiToken));
        if (!$user) {
            throw new EntityNotFoundException();
        }
        $repo = $this->em->getRepository('AppBundle:Analysis');
        $analysis = $repo->findOneBy(array('id' => $id, 'user' => $user));
        if (!$analysis) {
            throw new \Exception("Could not find analysis with id: $id");
        }
        return $analysis;
    }

    public function getRecordings($analysisId, $apiToken)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('apiToken' => $apiToken));
        if (!$user) {
            throw new EntityNotFoundException();
        }
        $repo = $this->em->getRepository('AppBundle:Recording');
        $recordings = $repo->findBy(array('analysis' => $analysisId, 'user' => $user));
        return $recordings;
    }

    /**
     * Generates a series of Recording objects for the specified analysis
     *
     * @param  Analysis $analysis the analysis to generate the recordings
     * @return bool returns true if all recordings were created
     */
    public function generateRecordings(Analysis $analysis)
    {
        $firstRecording = true;
        $groups = $this->em->getRepository('AppBundle:SentenceGroup')->findAll();
        foreach ($groups as $group) {
            $terms = $group->getSentences();
            foreach ($terms as $term) {
                $pair = $this->generateTermPair($analysis->getIssue(), $term);
                if ($firstRecording) {
                    $recording = $this->recording->createRecording($analysis, $pair['positive'], true);
                    $analysis->setCurrentRecording($recording->getId());
                    $this->em->persist($analysis);
                    $firstRecording = false;
                } else {
                    $this->recording->createRecording($analysis, $pair['positive']);
                }
                $this->recording->createRecording($analysis, $pair['negative']);
            }
        }
        $this->em->flush();
        return true;
    }

    /**
     * @param Analysis $analysis
     * @param $recordingId
     */
    public function setCurrentRecording(Analysis $analysis, $recordingId, $num)
    {
        $analysis->setCurrentRecording($recordingId);
        $analysis->setCurrentRecordingNum($num);
        $this->em->persist($analysis);
        $this->em->flush();
    }

    /**
     * Generates positive and negative statements
     *
     * @param  string $issue the issue being analyzed
     * @param  Sentence $term  the term
     * @return array associative array with positive and negative
     */
    private function generateTermPair($issue, Sentence $term)
    {
        $content = $term->getContent();

        return array(
            'positive' => "The $issue is one of {$content}",
            'negative' => "The $issue is not one of {$content}"
        );
    }
}
