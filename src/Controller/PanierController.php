<?php

namespace App\Controller;


use App\classe\Panier;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{

    private $entitymanager;

    public function __construct (EntityManagerInterface $entitymanager)
    {
        $this->entitymanager = $entitymanager;
    }
    
    #[Route('/panier', name: 'index_panier')]
    public function index(Panier $panier): Response
    {
        $totalpanier = [];
        if ($panier->get()){
            foreach($panier->get() as $id => $quantity){
                $totalpanier[]= [
                    'product' => $this->entitymanager->getRepository(Product::class)->findOneById($id),
                    'quantity' => $quantity,
                ];
            }
        }

        return $this->render('panier/index.html.twig',[
            'panier' => $totalpanier
        ]);
    }

    #[Route('/panier/add/{id}', name: 'add_topanier')]
    public function add(Panier $panier,$id): Response
    {
        $panier->add($id);

        return $this->redirectToRoute('index_panier');
    }

    #[Route('/panier/remove/{id}', name: 'remove_panier')]

    public function remove(Panier $panier): Response
    {
        $panier->remove();
        return $this->redirectToRoute('produits');
    }

    #[Route('/panier/delete/{id}', name: 'delete_panier')]

    public function delete(Panier $panier ,$id): Response
    {
        $panier->delete($id);
        return $this->redirectToRoute('index_panier', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/panier/decrease/{id}', name: 'decrease_panier')]

    public function decrease(Panier $panier ,$id): Response
    {
        $panier->decrease($id);
        return $this->redirectToRoute('index_panier');
    }


    public function panier (Panier $panier): Response
    {

        $totalpanier = [];
        if ($panier->get()){
            foreach($panier->get() as $id => $quantity){
                $totalpanier[]= [
                    'product' => $this->entitymanager->getRepository(Product::class)->findOneById($id),
                    'quantity' => $quantity,
                ];
            }
        }
       
         
        return $this->render('panier/navindex.html.twig',[
            'panier' => $totalpanier
        ]);
    }

    public function sum (Panier $panier): Response
    {
        $sum =0;
        $total_quantity = 0;
        $totalp = 0;
        $totalcart = [];
        if ($panier->get()){
            foreach($panier->get() as $id => $quantity){
                $totalcart[]= [
                    'product' => $this->entitymanager->getRepository(Product::class)->findOneById($id),
                    'quantity' => $quantity,
                   
                    $p = $this->entitymanager->getRepository(Product::class)->findOneById($id), 
                ];
                  
                $total_price = $p->getPrice() * $quantity;
                    $totalp +=$total_price; 
                $total_quantity = $total_quantity + $quantity;
                
            }
            
        }
       
         
        return $this->render('panier/sum.html.twig',[
            'total' => $total_quantity,'totalp'=> $totalp
        ]);
    }


    public function total (Panier $panier): Response
    {
        $sum =0;
        $total_quantity = 0;
        $totalp = 0;
        $totalcart = [];
        if ($panier->get()){
            foreach($panier->get() as $id => $quantity){
                $totalcart[]= [
                    'product' => $this->entitymanager->getRepository(Product::class)->findOneById($id),
                    'quantity' => $quantity,
                   
                    $p = $this->entitymanager->getRepository(Product::class)->findOneById($id), 
                ];
                  
                $total_price = $p->getPrice() * $quantity;
                    $totalp +=$total_price; 
                $total_quantity = $total_quantity + $quantity;
                
            }
            
        }
       
         
        return $this->render('panier/total.html.twig',[
            'total' => $total_quantity,'totalp'=> $totalp
        ]);
    }


}
