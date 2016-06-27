<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subject;
use AppBundle\Entity\Analysis;
use AppBundle\Form\SubjectType;
use AppBundle\Form\AnalysisType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AnalysisController extends BaseController
{

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Subject');
        $subjects = $repo->findByUser($this->getUser());
        $subject = new Subject();

        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $subject->setUser($this->getUser());
            $em->persist($subject);
            $em->flush();
            return $this->redirectToRoute('new_analysis');
        }

        return $this->render('analysis/new_analysis_step1.html.twig',
            $this->getCommonParameters(array(
                'form' => $form->createView(),
                'subjects' => $subjects
            ))
        );
    }

    public function stepTwoAction(Request $request)
    {
        $id = $request->get('id');
        $analysisService = $this->get('analysis.service');
        $em = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository('AppBundle:Subject');
        /** @var Subject **/
        $subject = $repo->findOneBy(array('id' => $id, 'user' => $this->getUser()));
        if (!$subject) {
            return $this->createNotFoundException("Subject not found");
        }
        $analysis = new Analysis();
        $form = $this->createForm(AnalysisType::class, $analysis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $subject->addAnalysis($analysis);
            $analysis->setSubject($subject);
            $analysis->setStartTime(new \DateTime());
            $analysis->setUser($this->getUser());
            $em->persist($analysis);
            $em->flush();

            $analysisService->generateRecordings($analysis);
            return $this->redirectToRoute('new_analysis_step_3', array('analysis' => $analysis->getId()));
        }

        return $this->render('analysis/new_analysis_step2.html.twig',
            $this->getCommonParameters(array(
                    'subject' => $subject,
                    'id' => $id,
                    'form' => $form->createView()
                )
            )
        );
    }

    public function stepThreeAction(Request $request)
    {
        $id = $request->get('analysis');
        $repo = $this->getDoctrine()->getRepository('AppBundle:Analysis');
        $analysis = $repo->findOneBy(array('id' => $id, 'user' => $this->getUser()));
        if (!$analysis) {
            return $this->createNotFoundException("Analysis not found");
        }

        return $this->render('analysis/recording.html.twig',
            $this->getCommonParameters(
                array(
                    'subject' => $analysis->getSubject(),
                    'id' => $analysis->getId(),
                    'apiToken' => $analysis->getUser()->getApiToken(),
                    'currentRecording' => $analysis->getCurrentRecording()
                )
            )
        );
    }

}
