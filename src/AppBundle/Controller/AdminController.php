<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sentence;
use AppBundle\Entity\SentenceGroup;
use AppBundle\Form\SentenceGroupType;
use AppBundle\Form\SentenceType;
use AppBundle\Service\RecordingService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends BaseController
{
    public function indexAction(Request $request)
    {
        return $this->render('admin/admin_base.html.twig', $this->getCommonParameters(array('title' => 'Dashboard')));
    }

    public function sentencesAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $groups = $doctrine->getRepository('AppBundle:SentenceGroup')->findAll();
        $sentences = $doctrine->getRepository('AppBundle:Sentence')->findAll(array('id' => 'ASC'));

        return $this->render('admin/sentences.html.twig',
            $this->getCommonParameters(array(
                'title' => 'Terms',
                'groups' => $groups,
                'sentences' => $sentences
            )));
    }

    public function newSentenceGroupAction(Request $request)
    {
        $group = new SentenceGroup();
        $em = $this->getDoctrine()->getEntityManager();

        $form = $this->createForm(SentenceGroupType::class, $group);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($group);
            $em->flush();
            return $this->redirectToRoute('admin_sentences');
        }

        return $this->render('admin/new_sentence_group.html.twig',
            $this->getCommonParameters(array(
                'title' => 'New Term Group',
                'form' => $form->createView()
            )));

    }

    public function newSentenceAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $sentence = new Sentence();

        $form = $this->createForm(SentenceType::class, $sentence);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($sentence);
            $em->flush();
            return $this->redirectToRoute('admin_sentences');
        }

        return $this->render('admin/edit_sentence_group.html.twig',
            $this->getCommonParameters(array(
                'title' => 'Create Term',
                'form' => $form->createView()
            )));
    }

    public function editSentenceAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $this->getDoctrine()->getRepository('AppBundle:Sentence');
        $sentence = $repo->findOneById($request->get('id'));

        $form = $this->createForm(SentenceType::class, $sentence);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($sentence);
            $em->flush();
            return $this->redirectToRoute('admin_sentences');
        }

        return $this->render('admin/edit_sentence_group.html.twig',
            $this->getCommonParameters(array(
                'title' => 'Edit Term',
                'form' => $form->createView()
            )));
    }

    public function deleteSentenceAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $this->getDoctrine()->getRepository('AppBundle:Sentence');
        $group = $repo->findOneById($request->get('id'));

        if ($group) {
            $em->remove($group);
            $em->flush();
        }

        return $this->redirectToRoute("admin_sentences");
    }

    public function editSentenceGroupAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $this->getDoctrine()->getRepository('AppBundle:SentenceGroup');
        $group = $repo->findOneById($request->get('id'));

        $form = $this->createForm(SentenceGroupType::class, $group);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($group);
            $em->flush();
            return $this->redirectToRoute('admin_sentences');
        }

        return $this->render('admin/edit_sentence_group.html.twig',
            $this->getCommonParameters(array(
                'title' => 'Edit Term Group',
                'form' => $form->createView()
            )));
    }

    public function deleteSentenceGroupAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $this->getDoctrine()->getRepository('AppBundle:SentenceGroup');
        $group = $repo->findOneById($request->get('id'));

        if ($group) {
            $em->remove($group);
            $em->flush();
        }

        return $this->redirectToRoute("admin_sentences");
    }

    public function usersAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:User');
        $users = $repo->findAll();

        return $this->render('admin/users.html.twig',
            $this->getCommonParameters(array(
                'users' => $users,
                'title' => 'Users'
            ))
            );
    }

    public function subjectsAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:Subject');
        $subjects = $repo->findAll();

        return $this->render('admin/subjects.html.twig',
            $this->getCommonParameters(array(
                'subjects' => $subjects,
                'title' => 'Subjects'
            )));
    }

    public function analysisAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:Analysis');
        $analysis = $repo->findAll();

        return $this->render('admin/analysis.html.twig',
            $this->getCommonParameters(array(
                'title' => 'Analysis',
                'analysis' => $analysis
            )));
    }

    public function processQueueAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:ProcessQueue');
        $processQueues = $repo->findBy(array('dateProcessed' => null));

        return $this->render('admin/process.html.twig',
            $this->getCommonParameters(array(
                'title' => 'Process Queue',
                'queue' => $processQueues
            )));
    }

    public function recordingsAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:Recording');
        $analysis = $em->getRepository('AppBundle:Analysis')->findOneById($request->get('analysis'));
        $recordings = $repo->findBy(array('analysis' => $request->get('analysis')));

        return $this->render('admin/recordings.html.twig',
            $this->getCommonParameters(array(
                'title' => 'Recordings',
                'recordings' => $recordings,
                'analysis' => $analysis,
                'parentPage' => 'Analysis',
                'parentPageUrl' => $this->generateUrl('admin_analysis')
            )));
    }

    public function analysisQueueAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:Recording');
        $pendingAnalysis = $repo->findBy(array('status' => RecordingService::STATUS_PENDING_ANALYSIS));

        return $this->render('admin/pendingAnalysis.html.twig',
            $this->getCommonParameters(array(
                'title' => 'Pending Analysis',
                'pending' => $pendingAnalysis
            )));
    }

    public function analysisQueueViewAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:Recording');
        $pendingAnalysis = $repo->findOneBy(array('id' => $request->get('id')));

        return $this->render('admin/analysisView.html.twig',
            $this->getCommonParameters(array(
                'title' => 'Analysis View',
                'recording' => $pendingAnalysis
            )));
    }
}
