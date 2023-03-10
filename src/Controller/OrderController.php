<?php

namespace App\Controller;

use App\Entity\Order;
use App\classe\Panier;
use App\Form\OrderType;
use App\Entity\Orderdetails;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'order')]
    public function index(Panier $panier,Request $request): Response
    {   
        $form = $this->createForm(OrderType::class,null,[
            'user'=>$this->getUser(),
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

        }

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),'panier'=>$panier->getFull()
        ]);
    }

    #[Route('/order/valid', name: 'valid_order' , methods: ['POST'])] 
    public function add(panier $panier,Request $request): Response
    {  

        $form = $this->createForm(OrderType::class,null,[
            'user'=>$this->getUser(),
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

        $date = new \DateTime();
        $transporteur = $form->get('transporteurs')->getData();
        $address = $form->get('address')->getData();
        $phone = $form->get('phone')->getData();

        $order = new Order();
        $reference = $date->format('dmY').'-'.uniqid();
        $order->setReference($reference);
        $order->setPhone($phone);
        $order->setUser($this->getUser());  
        $order->setCreatedAt($date); 
        $order->setNomTrasnport($transporteur->getNom());  
        $order->setPrixTransport($transporteur->getPrice());  
        $order->setAddress($address);
        $order->setEtat(0);
        $order->setEtatcom(0);   
        $entityManager = $this->getDoctrine()->getManager();
        $sum =0;
        foreach($panier->getFull() as $val){ 
            $total= $val['product']->getPrice() * $val['quantity'];
            $sum = $sum + $total;
        }
        $order->setTotal($sum);
        $entityManager->persist($order);
        foreach($panier->getFull() as $val){  

            $orderdetails = new Orderdetails();
            $orderdetails->setMyorder($order);
            $orderdetails->setProduct($val['product']->getName());
            $orderdetails->setQuantity($val['quantity']);
            $orderdetails->setPrice($val['product']->getPrice());
            $orderdetails->setTotal($val['product']->getPrice() * $val['quantity']);
            $orderdetails->setImageprod($val['product']->getId());
            $entityManager->persist($orderdetails);

           }
         
           
           $entityManager->flush();


          return $this->render('order/add.html.twig',[
            'panier'=>$panier->getFull(),
            'transporteur'=>$transporteur,
            'address'=>$address,
            'reference'=> $order->getReference(),
            'phone'=>$phone,
            'order'=>$order,
            
        ]);
            
        }
           return $this->redirectToRoute('index_panier');
      
    }

    #[Route('/ordersuccess3', name: 'order_success3')]
    public function ordersuccess(Panier $panier,Request $request): Response
    {   
        

        return $this->render('order/ordersucess.html.twig');
    }

    #[Route('/ordersuccess2', name: 'order_success2')]
    public function ordersuccess2(Panier $panier,Request $request): Response
    {   
        

        return $this->render('order/ordersucess2.html.twig');
    }
}
