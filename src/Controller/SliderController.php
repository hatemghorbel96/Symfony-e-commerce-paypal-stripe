<?php

namespace App\Controller;

use App\Entity\Slider;
use App\Form\SliderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SliderController extends AbstractController
{
    

    #[Route('/admin/slider', name: 'sliders')]
    public function index(): Response
    {
        $sliders = $this->getDoctrine()->getRepository(Slider::class)->findAll();
        return $this->render('slider/index.html.twig', [
            'sliders' => $sliders,
        ]);
    }

    

    #[Route('/admin/slider/add', name: 'add_slider')]
    public function add(Request $request): Response
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class,$slider);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
            $slider->setImage($fichier);
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($slider);
            $entityManager->flush();
            return $this->redirectToRoute('sliders');
        }
      
        return $this->render('slider/add.html.twig', [
            'form' => $form->createView() ,
        ]);
    }

}
