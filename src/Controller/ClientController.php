<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    #[Route('/admin/client', name: 'clients')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('clientss/index.html.twig', [
            'clients' => $userRepository->findAll(),
        ]);
    }

    #[Route('/admin/client/{id}', name: 'show_clients')]
    public function show($id): Response
    {
        $client = $this->getDoctrine()->getRepository(User::class)->find($id);
        $commendes= $this->getDoctrine()->getRepository(Order::class)->findBy(['user'=>$this->getUser()]);
        return $this->render('clientss/show.html.twig', [
            'client' => $client,'commendes'=>$commendes
        ]);
    }


 


}
