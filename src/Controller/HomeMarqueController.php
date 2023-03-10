<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeMarqueController extends AbstractController
{
   

    #[Route('/home/marque/{id}/{page<\d+>?1}', name: 'marque_prod')]
    public function marqprod(ProductRepository $productRepository,$id,$page): Response
    {   
        $limit =6;
        $start =$page*$limit-$limit;
        $total =count($productRepository->findAll());
        $pages=ceil($total /$limit);        
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(['marque'=>$id]);
        return $this->render('product/index.html.twig', [
            'products' => $products,'categories'=>$categories,'pages' => $pages,
            'page'=>$page,'marques'=>$marques
        ]);
    }
}
