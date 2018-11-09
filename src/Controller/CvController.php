<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\CV;
use App\Form\CVType;

class CvController extends AbstractController
{
    /**
     * @Route("/cv", name="cv")
     */
    public function index()
    {
        return $this->render('cv/index.html.twig', [
            'controller_name' => 'CvController',
        ]);
    }

    /**
     * @Route("/addcv", name="addcv")
     */
    public function addCV(Request $request)
    {
        $cv = new CV();

        $form = $this->createForm(CVType::class, $cv);
        $form->handleRequest($request);
        
        $cv = $form->getData();

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cv);
            $em->flush();
            $this->addFlash('notice', 'Le Cv est bien enregistré.');
        } catch (Exception $e) {
            $this->addFlash('error', 'Le Cv n\'est pas bien enregistré.');
        }

        //return new Response('Le CV a été crée avec succès!');
        return $this->redirect($this->generateUrl("cvs"));
    }

        return $this->render('cv/newCV.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/updatecv/{id}", name="updatecv")
     */
    public function updateCV(Request $request, $id)
    {        
        $cvID = $this->getDoctrine()
            ->getRepository(CV::class)
            ->find($id);        

        if (!$cvID) {
            return $this->render('error/noCvFound.html.twig');
        }

        $form = $this->createForm(CVType::class, $cvID);
        $form->handleRequest($request);
        
        $cvID = $form->getData();

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cvID);
            $em->flush();
            $this->addFlash('notice', 'Le Cv a été modifié.');
        } catch (Exception $e) {
            $this->addFlash('error', 'Le Cv n\'a pas été modifié.');
        }
        return $this->redirect($this->generateUrl("cvs"));
    }

        return $this->render('cv/updateCV.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/cvs", name="cvs")
     */
    public function displayAllCvs()
    {
        $cvs = $this->getDoctrine()
            ->getRepository(CV::class)
            ->findAll();        

        if (!$cvs) {
            return $this->render('error/noCvFound.html.twig');
        }

        return $this->render('cv/CVs.html.twig', ['cvs' => $cvs]);
    }

    /**
     * @Route("/cvs/{job_id}", name="cvsjob")
     */
    public function displayCvsJob($job_id)
    {
        $cvs = $this->getDoctrine()
            ->getRepository(CV::class)
            ->findBy(['job' => $job_id]);        

        if (!$cvs) {
            return $this->render('error/noCvFound.html.twig');
        }

        return $this->render('cv/CVs.html.twig', ['cvs' => $cvs]);
    }

    /**
     * @Route("/deletecv/{id}", name="deletecv")
     */
    public function deleteCv($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $cv = $this->getDoctrine()
            ->getRepository(CV::class)
            ->find($id);        

        try {
            $entityManager->remove($cv);
            $entityManager->flush();
            $this->addFlash('notice', 'Le Cv a bien été supprimé.');
        } catch (Exception $e) {
            $this->addFlash('error', 'Le Cv n\'a pas été supprimé.');
        }

        return $this->redirect($this->generateUrl("cvs"));
    }
}
