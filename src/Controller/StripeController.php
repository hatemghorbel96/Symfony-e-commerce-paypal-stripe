<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\classe\Panier;
use App\Entity\Product;

use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{

  
    #[Route('/order/create-session/{reference}', name: 'stripe_session')]
    public function index(Panier $panier, $reference , EntityManagerInterface $entityManager)
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $entityManager = $this->getDoctrine()->getManager();
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order){

             new JsonResponse(['error'=>'order']);           

        }
      
        foreach($order->getOrderDetails()->getValues() as $val){
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($val->getProduct());
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',                  
                    'unit_amount' => $val->getPrice(),                 
                    'product_data' => [
                      'name' => $val->getProduct(),
                    
                    ],
                ],
                  'quantity' => $val->getQuantity(),
            ];

        }

        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',                  
                'unit_amount' => $order->getPrixTransport() ,                 
                'product_data' => [
                  'name' => $order->getNomTrasnport(),
                 
                ],
            ],
              'quantity' => 1,
        ];

        
            
        Stripe::setApiKey('sk_test_51LC0eQFiDr85NP9qMbPMGwVexke1E5xafpWOddetC3MFSZNE2QBLgsrolEimWfgBTMbTCLpzA1YfJVsrupFSikjh009BON2aRG');

      
       
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
             'line_items' => [
                 $products_for_stripe
           ],
           'mode' => 'payment',
           'success_url' => $YOUR_DOMAIN.'/order/thankyou/{CHECKOUT_SESSION_ID}',
           'cancel_url' => $YOUR_DOMAIN.'/order/error/{CHECKOUT_SESSION_ID}',
         ]);

         $order->setStripeid($checkout_session->id);
         $entityManager->flush();
         $response = new JsonResponse(['id'=>$checkout_session->id]);
         return $response;
    }
}
