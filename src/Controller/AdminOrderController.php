<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\ComEtatType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminOrderController extends AbstractController
{
    #[Route('/admin/order', name: 'admin_orders')]
    public function index(): Response
    {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findAll();
        return $this->render('admin_order/index.html.twig', [
            'orders' => $orders,
        ]);
    }


    #[Route('/admin/order/{id}', name: 'admin_show_order')]
    public function show($id,Request $request): Response
    {   
        $order = $this->getDoctrine()->getRepository(Order::class)->findOneById($id);
        $form = $this->createForm(ComEtatType::class,$order);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) { 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();  
            $this->addFlash(
             'success',
             "etat modifier avec succès"
         ); 
         return $this->redirectToRoute('admin_show_order', ['id' => $order->getId()]);
        }
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        
        return $this->render('admin_order/show.html.twig', [
            'order' => $order,'products'=>$products,'form'=>$form->createView()
        ]);
    }

    #[Route('/admin/order/print/{id}', name: 'print_order')]
    public function print($id): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $order = $this->getDoctrine()->getRepository(Order::class)->findOneById($id);
        return $this->render('admin_order/print.html.twig', [
            'order' => $order,'products'=>$products
        ]);
    }
}
