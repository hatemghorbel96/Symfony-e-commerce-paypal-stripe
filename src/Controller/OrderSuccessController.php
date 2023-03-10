<?php

namespace App\Controller;

use App\Entity\Order;
use App\classe\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct (EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/order/thankyou/{stripeid}', name: 'order_payee')]
    public function index(Panier $panier,$stripeid): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeid($stripeid);

        if(!$order || $order->getUser() != $this->getUser()){

            return $this->redirectToRoute('app_home');
        }
       
        if($order->getEtat() == 0){

        // modifier le statut isPaid
        //vider la session
        $panier->remove();
        $order->setEtat(1);
        $order->setMode('Stripe');
        $this->entityManager->flush();
        // envoyer un email à notre client pour lui confirmer sa commande
       
        }
       // afficher les information de la commande de user
       
        return $this->render('order_success/index.html.twig', [
        'order'=>$order ]);
       
    }

    #[Route('/order/thankyou/index2/{id}', name: 'order_domicile')]
    public function index2(Panier $panier,$id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneById($id);

        if($order->getUser() != $this->getUser()){

            return $this->redirectToRoute('app_home');
        }
       
        if($order->getEtat() == 0){

        // modifier le statut isPaid
        //vider la session
        $panier->remove();
       
        $order->setMode('a la livraison');
        $this->entityManager->flush();
        // envoyer un email à notre client pour lui confirmer sa commande
       
        }
       // afficher les information de la commande de user
       
        return $this->render('order_success/livraison.html.twig', [
        'order'=>$order ]);
       
    }
}
