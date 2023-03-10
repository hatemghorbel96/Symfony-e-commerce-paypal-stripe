<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'categories')]
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

   
    public function lista(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('layout/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/product/{id}/{page<\d+>?1}', name: 'category_prod')]
    public function findprod(ProductRepository $productRepository,$id,$page): Response
    {   
        $limit =6;
        $start =$page*$limit-$limit;
        $total =count($productRepository->findAll());
        $pages=ceil($total /$limit);        
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(['category'=>$id]);
        return $this->render('product/index.html.twig', [
            'products' => $products,'categories'=>$categories,'pages' => $pages,
            'page'=>$page,'marques'=>$marques
        ]);
    }
}
