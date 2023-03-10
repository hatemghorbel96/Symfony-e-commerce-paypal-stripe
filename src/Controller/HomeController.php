<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Slider;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $sliders = $this->getDoctrine()->getrepository(Slider::class)->findAll();
        $categories = $this->getDoctrine()->getrepository(Category::class)->findAll();
        $marques = $this->getDoctrine()->getrepository(Marque::class)->findAll();
        return $this->render('home/index.html.twig', [
            'sliders' => $sliders,'categories'=>$categories,'marques'=>$marques
        ]);
    }
}
