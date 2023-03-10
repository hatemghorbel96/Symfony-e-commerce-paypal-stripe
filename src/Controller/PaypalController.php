<?php

namespace App\Controller;

use App\Entity\Order;
use App\classe\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaypalController extends AbstractController
{

    private $session;

public function __construct(SessionInterface $session) {
 
    $this->session = $session;
}

    #[Route('/verify_payment/{reference}/{stripeid}', name: 'app_paypal')]
    public function index(Panier $panier,$stripeid,$reference): Response
    {
        $this->session->set('stripeid',$stripeid);
        $this->session->set('reference',$reference);
        $entityManager = $this->getDoctrine()->getManager();
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
        $panier->remove();
        $order->setStripeId($stripeid);
        $order->setMode('Paypal');
        $order->setEtat(1);
        $entityManager->flush();
        return $this->render('paypal/index.html.twig', [
            'stripeid' => $stripeid,'reference'=>$reference,'order'=>$order
        ]);
    }

    
}
