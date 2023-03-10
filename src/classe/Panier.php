<?php

namespace App\classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier {

private $session;

public function __construct(SessionInterface $session,EntityManagerInterface $entityManager) {
  $this->entityManager = $entityManager;
    $this->session = $session;
}

public function add($id) {


    $panier= $this->session->get('panier',[]);
        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

    $this->session->set('panier',$panier);
}

//affiche
public function get(){

    return $this->session->get('panier');
}

public function remove(){

    return $this->session->remove('panier');
}

public function delete($id) {
    $panier = $this->session->get('panier',[] );

    unset($panier[$id]); 
  return $this->session->set('panier',$panier);

 }

 public function decrease($id){
    
    $panier = $this->session->get('panier',[] ); 

    if ($panier[$id] > 1){
      $panier[$id]--; 
    }else{
      unset($panier[$id]);  
    }
    return $this->session->set('panier',$panier);

  }


  public function getFull(){
    $panierComplete=[];  

    if($this->get()){
      foreach($this->get() as $id=>$quantity){ 
       
        $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

        if(!$product_object){
          $this->delete($id);
          continue;
        }
          $panierComplete[]=[
            'product' =>$product_object,
            'quantity' =>$quantity
          ];
      }
    }

    return $panierComplete;

  }

}
   
   
   ?>