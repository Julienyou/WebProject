<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Job;
use App\Form\JobType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiJobController extends AbstractController
{
    /**
     * @Route("/api/addjob", name="api_addjob", methods={"POST", "OPTIONS"})
     */
    public function addJob(Request $request)
    {
        /* Function to add a job in the database which is given in a json */
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type', true);

            return $response;
        }

        $job = new Job();

        $json = $request->getContent();
        $content = json_decode($json, true);

        $job->setJob($content['job']);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        if (!$job) {
            $response->setStatusCode("500");
            return $response;
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
            $response->setStatusCode("200");
            return $response;
        }
    }

    /**
     * @Route("/api/jobs", name="api_jobs", methods={"GET"})
     */
    public function displayAllJobs()
    {
        /* Function which sends a json with all the jobs  */
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jobs = $this->getDoctrine()
            ->getRepository(Job::class)
            ->findAll();  
            
        if (!$jobs) {
            throw $this->createNotFoundException(
                'No job found'
            );
        }

        $jsonContent = $serializer->serialize($jobs, 'json');

        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode("200");
        
        $response->setContent($jsonContent);
        

        return $response;
    }
}