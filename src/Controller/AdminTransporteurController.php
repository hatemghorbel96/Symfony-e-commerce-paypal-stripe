<?php

namespace App\Controller;

use App\Entity\Transporteur;
use App\Form\TransporteurType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTransporteurController extends AbstractController
{
    #[Route('/admin/transporteur', name: 'transporteurs')]
    public function index(): Response
    {
        $transporteurs = $this->getDoctrine()->getRepository(Transporteur::class)->findAll();
        return $this->render('admin_transporteur/index.html.twig', [
            'transporteurs' => $transporteurs,
        ]);
    }
    #[Route('/admin/transporteur/add', name: 'add_transporteur')]
    public function add(Request $request): Response
    {
        $transporteur = new Transporteur();
        $form = $this->createForm(TransporteurType::class,$transporteur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($transporteur);
            $entityManager->flush();
            return $this->redirectToRoute('transporteurs');
        }
      
        return $this->render('admin_transporteur/add.html.twig', [
            'form' => $form->createView() ,
        ]);
    }


    #[Route('/admin/marque/edit/{id}', name: 'edit_transporteur')]
    public function edit($id,Request $request): Response
    {
        $transporteur= $this->getDoctrine()->getrepository(Transporteur::class)->find($id);
        $form = $this->createForm(TransporteurType::class,$transporteur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($transporteur);
            $entityManager->flush();
            return $this->redirectToRoute('transporteurs');
        }
      
        return $this->render('admin_transporteur/edit.html.twig', [
            'form' => $form->createView() ,
        ]);
    }

}

