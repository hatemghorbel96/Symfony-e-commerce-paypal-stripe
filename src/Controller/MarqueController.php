<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarqueController extends AbstractController
{
    #[Route('/admin/marque', name: 'marques')]
    public function index(): Response
    {
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        return $this->render('marque/index.html.twig', [
            'marques' => $marques,
        ]);
    }

    #[Route('/admin/marque/add', name: 'add_marque')]
    public function add(Request $request): Response
    {
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class,$marque);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
            $marque->setImage($fichier);
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($marque);
            $entityManager->flush();
            return $this->redirectToRoute('marques');
        }
      
        return $this->render('marque/add.html.twig', [
            'form' => $form->createView() ,
        ]);
    }


    #[Route('/admin/marque/edit/{id}', name: 'edit_marque')]
    public function edit($id,Request $request): Response
    {
        $marque= $this->getDoctrine()->getrepository(Marque::class)->find($id);
        $form = $this->createForm(MarqueType::class,$marque);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
            $marque->setImage($fichier);
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($marque);
            $entityManager->flush();
            return $this->redirectToRoute('marques');
        }
      
        return $this->render('marque/edit.html.twig', [
            'form' => $form->createView() ,
        ]);
    }



 

}
