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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function addCV(Request $request, ValidatorInterface $validator)
    {
        /* Function to add a CV in the database which is given in a json */
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type', true);

            return $response;
        }

        $cv = new CV();
        $job = new Job();

        $json = $request->getContent();
        $content = json_decode($json, true);
        
        /* Fill the entities with the informations in the json */
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

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');

        //Verify asserts which are in the entities
        $errors = $validator->validate($cv);
        $array = array();

        if (count($errors) > 0) {
            /*
            * Uses a __toString method on the $errors variable which is a
            * ConstraintViolationList object. This gives us a nice string
            * for debugging.
            */

	        foreach ($errors as $error) {
                // On récupère le nom du champ sur lequel a été levé l'erreur
                $champ = $error->getPropertyPath();
                
                // On récupère le message d'erreur
                $array[$champ] = $error->getMessage();
            }
            $response->setStatusCode("500");
            $json = json_encode($array);
            $response->setContent($json);
            return $response;
        }
        else {
            $response->setStatusCode("200");
            $em = $this->getDoctrine()->getManager();
            $em->persist($cv);
            $em->flush();
            return $response;
        }
    }

    /**
     * @Route("/api/updatecv/{id}", name="api_updatecv", methods={"PUT", "OPTIONS"})
     */
    public function updateCV(Request $request, $id, ValidatorInterface $validator)
    {
        /* Function to update a CV which is already in the database */   
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type', true);

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

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');

        //Verify asserts which are in the entities
        $errors = $validator->validate($cv);
        $array = array();

        if (count($errors) > 0) {
            /*
            * Uses a __toString method on the $errors variable which is a
            * ConstraintViolationList object. This gives us a nice string
            * for debugging.
            */

	        foreach ($errors as $error) {
                // On récupère le nom du champ sur lequel a été levé l'erreur
                $champ = $error->getPropertyPath();
                
                // On récupère le message d'erreur
                $array[$champ] = $error->getMessage();
            }
            $response->setStatusCode("500");
            $json = json_encode($array);
            $response->setContent($json);
            return $response;
        }
        else {
            $response->setStatusCode("200");
            $em = $this->getDoctrine()->getManager();
            $em->persist($cv);
            $em->flush();
            return $response;
        }
    }

    /**
     * @Route("/api/cv/{id}", name="api_cv", methods={"GET", "OPTIONS"})
     */
    public function getCV($id)
    {
        /* Function to get a CV shaped like a json thanks to the id */   
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type', true);

            return $response;
        }

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $cv = $this->getDoctrine()
            ->getRepository(CV::class)
            ->find($id);

        if (!$cv) {
            throw $this->createNotFoundException(
                'No CV found'
            );
        }

        $jsonContent = $serializer->serialize($cv, 'json');

        $response = new JsonResponse();
        $response->setContent($jsonContent);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');

        if (!$cv) {
            $response->setStatusCode("500");
            return $response;
        }
        else {
            $response->setStatusCode("200");
            return $response;
        }
    }

    /**
     * @Route("/api/cvs", name="api_cvs", methods={"GET"})
     */
    public function displayAllCvs()
    {
        /* Function which sends a json with all the CVs  */
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
        /* Function which sends a json with all the CVs for a particular job */
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
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode("200");
        

        return $response;
    }

    /**
     * @Route("/api/deletecv/{id}", name="api_deletecv", methods={"DELETE", "OPTIONS"})
     */
    public function deleteCv($id)
    {
        /* Function to delete a CV in the database */
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type', true);

            return $response;
        }

        $entityManager = $this->getDoctrine()->getManager();

        $cv = $this->getDoctrine()
            ->getRepository(CV::class)
            ->find($id);      
        
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');

        if (!$cv) {
            $response->setStatusCode("500");
            return $response;
        }
        
        $response->setStatusCode("200");

        $entityManager->remove($cv);
        $entityManager->flush();

        return $response;
    }
}
