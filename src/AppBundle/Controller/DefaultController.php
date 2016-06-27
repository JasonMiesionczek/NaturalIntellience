<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Analysis;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', $this->getCommonParameters(array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        )));
    }

    public function aboutAction(Request $request)
    {
        return $this->render('default/about.html.twig', $this->getCommonParameters());
    }

    public function mySubjectsAction(Request $request)
    {
        $subjects = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Subject')
            ->findBy(array('user' => $this->getUser()));
        return $this->render('default/mySubjects.html.twig', $this->getCommonParameters(array('subjects' => $subjects)));
    }

    public function mySessionsAction(Request $request)
    {
        $sessions = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Analysis')
            ->findBy(array('user' => $this->getUser()));
        $sessions = array_filter($sessions, function($session) {
            /** @var Analysis $session */
            return ($session->getCurrentRecordingNum() <= count($session->getRecordings()));
        });
        return $this->render('default/mySessions.html.twig', $this->getCommonParameters(array('sessions' => $sessions)));
    }

    public function cancelSessionAction(Request $request)
    {
        $id = $request->get('id');
        /** @var Analysis $session */
        $session = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Analysis')
            ->findOneBy(array('id' => $id, 'user' => $this->getUser()));
        $em = $this->getDoctrine()->getManager();

        $em->remove($session);
        $em->flush();
        return $this->redirectToRoute('my_sessions');
    }

    public function myResultsAction(Request $request)
    {
        $results = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Analysis')
            ->findBy(array('user' => $this->getUser()));
        $results = array_filter($results, function($result) {
            /** @var Analysis $result */
            return ($result->getCurrentRecordingNum() >= count($result->getRecordings()));
        });
        return $this->render('default/myResults.html.twig', $this->getCommonParameters(array('sessions' => $results)));
    }
}
