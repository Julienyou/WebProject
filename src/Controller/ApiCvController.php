<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\CV;
use App\Entity\Job;
use App\Form\CVType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiCvController extends AbstractController
{
    /**
     * @Route("/api/cv", name="api_cv")
     */
    public function index()
    {
        return $this->render('cv/index.html.twig', [
            'controller_name' => 'CvController',
        ]);
    }

    /**
     * @Route("/api/addcv", name="api_addcv", methods={"POST", "OPTIONS"})
     */
    public function addCV(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');

            return $response;
        }

        $cv = new CV();
        $job = new Job();

        $json = $request->getContent();
        $content = json_decode($json, true);

        $cv->setMotivation($content["motivation"]);
        $cv->setSkills($content["skills"]);
        $cv->setStudies($content["studies"]);

        $job = $this->getDoctrine()
            ->getRepository(Job::class)
            ->find($content['id_job']);

        $cv->setJob($job);
        $cv->setLastname($content["lastname"]);
        $cv->setFirstname($content["firstname"]);
        $cv->setBirthday($content["birthday"]);
        $cv->setEmail($content["email"]);
        $cv->setCity($content["city"]);
        $cv->setCountry($content["country"]);
        $cv->setPhone_Number($content["phone_Number"]);
        $cv->setPhoneNumber($content["phone_Number"]);

        if (!$cv) {
            return new Response("Erreur lors de la création du Cv");
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cv);
            $em->flush();
            return new Response("Cv crée avec succès");
        }
    }

    /**
     * @Route("/api/updatecv/{id}", name="api_updatecv", methods={"PUT", "OPTIONS"})
     */
    public function updateCV(Request $request, $id)
    {   
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');

            return $response;
        }

        $json = $request->getContent();
        $content = json_decode($json, true);

        $cv = $this->getDoctrine()
            ->getRepository(CV::class)
            ->find($id); 

        $job = new Job();

        $cv->setMotivation($content["motivation"]);
        $cv->setSkills($content["skills"]);
        $cv->setStudies($content["studies"]);

        $job = $this->getDoctrine()
            ->getRepository(Job::class)
            ->find($content['id_job']);

        $cv->setJob($job);
        $cv->setLastname($content["lastname"]);
        $cv->setFirstname($content["firstname"]);
        $cv->setBirthday($content["birthday"]);
        $cv->setEmail($content["email"]);
        $cv->setCity($content["city"]);
        $cv->setCountry($content["country"]);
        $cv->setPhone_Number($content["phone_Number"]);
        $cv->setPhoneNumber($content["phone_Number"]);

        if (!$cv) {
            return new Response("Erreur lors de la création du Cv");
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cv);
            $em->flush();
            return new Response("Cv modifié avec succès");
        }
    }

    /**
     * @Route("/api/cvs", name="api_cvs", methods={"GET"})
     */
    public function displayAllCvs()
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $cvs = $this->getDoctrine()
            ->getRepository(CV::class)
            ->findAll();  
            
        if (!$cvs) {
            throw $this->createNotFoundException(
                'No CV found'
            );
        }

        $jsonContent = $serializer->serialize($cvs, 'json');

        $response = new JsonResponse();
        $response->setContent($jsonContent);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode("200");
        

        return $response;
    }

    /**
     * @Route("/api/cvs/{job_id}", name="api_cvsjob", methods={"GET"})
     */
    public function displayCvsJob($job_id)
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $cvs = $this->getDoctrine()
            ->getRepository(CV::class)
            ->findBy(['job' => $job_id]);   
            
        if (!$cvs) {
            throw $this->createNotFoundException(
                'No CV found'
            );
        }

        $jsonContent = $serializer->serialize($cvs, 'json');

        $response = new JsonResponse();
        $response->setContent($jsonContent);
        

        return $response;
    }

    /**
     * @Route("/api/deletecv/{id}", name="api_deletecv", methods={"DELETE", "OPTIONS"})
     */
    public function deleteCv($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');

            return $response;
        }

        $entityManager = $this->getDoctrine()->getManager();

        $cv = $this->getDoctrine()
            ->getRepository(CV::class)
            ->find($id);        

        if (!$cv) {
            return new Response("Cv non trouvé");
        }

        $entityManager->remove($cv);
        $entityManager->flush();

        return new Response("Cv supprimé avec succès");
    }
}
