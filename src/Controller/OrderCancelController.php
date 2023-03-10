<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCancelController extends AbstractController
{
    private $entityManager;

    public function __construct (EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    
    #[Route('/order/error/{stripeid}', name: 'order_cancel')]
    public function index($stripeid): Response
    {   
        
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeId($stripeid);

        if(!$order || $order->getUser() != $this->getUser()){

            return $this->redirectToRoute('home');
        }
        // send email to notif for cancel order
        return $this->render('order_cancel/index.html.twig',[
            'order'=>$order]);
    }
}
