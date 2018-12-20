<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Job;
use App\Form\JobType;

class JobController extends AbstractController
{
    /**
     * @Route("/addjob", name="addjob")
     */
    public function addJob(Request $request)
    {
        /* Function to add a job in the database */
        $job = new Job();

        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        
        $job = $form->getData();

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            /* Add data from the form in the database */
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
            $this->addFlash('notice', 'Le métier a été ajouté.');
        } catch (Exception $e) {
            $this->addFlash('error', 'Le métier n\'a pas été ajouté.');
        }
        return $this->redirect($this->generateUrl("addcv"));
    }

        return $this->render('job/newJob.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/jobs", name="jobs")
     */
    public function displayAllJobs()
    {
        /* Function to display all the jobs in the database */
        $jobs = $this->getDoctrine()
            ->getRepository(Job::class)
            ->findAll();        

        if (!$jobs) {
            return $this->render('error/noJobFound.html.twig');
        }

        return $this->render('job/jobs.html.twig', ['jobs' => $jobs]);
    }
}