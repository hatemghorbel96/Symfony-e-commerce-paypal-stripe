<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Marque;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\CommentType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product/{page<\d+>?1}', name: 'products')]
    public function index(ProductRepository $productRepository,$page): Response
    {
        $limit =6;
        $start =$page*$limit-$limit;
        $total =count($productRepository->findAll());
        $pages=ceil($total /$limit);
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy([],[],$limit,$start);    
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,'categories'=>$categories,'pages' => $pages,
            'page'=>$page,'marques'=>$marques
        ]);
    }

    
  

    #[Route('/product/favorit/{id}/', name: 'add_fav')]
    public function Ajoutfavorit(Product $product):Response {
       
    
        $product->addFavorit($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return $this->redirectToRoute('show_pro', ['id' => $product->getId()]);
         }
    
    
    #[Route('/product/Annulefavorit/{id}', name: 'annule_fav')]
    public function annulefavorit(Product $product):Response {
       
    
        $product->removeFavorit($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return $this->redirectToRoute('show_pro', ['id' => $product->getId()]);
         }


         #[Route('/product/upfavorit/{id}', name: 'up_fav')]
    public function upfavorit(Product $product):Response {
       
    
        $product->addFavorit($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return $this->redirectToRoute('products');
         }
    
    
    #[Route('/product/downfavorit/{id}', name: 'down_fav')]
    public function downfavorit(Product $product):Response {
       
    
        $product->removeFavorit($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return $this->redirectToRoute('products');
         }


         #[Route('/product/show/{id}', name: 'show_pro')]
    public function show($id,Request $request,EntityManagerInterface $manager): Response
    {   

        /* $five= $manager->createQuery('SELECT COUNT(c) FROM App\Entity\Convention c WHERE c.etat=0')->getSingleScalarResult(); */
        $five = $this->getDoctrine()->getRepository(Comment::class)->findBy(['product' => $id , 'rating' => 5]);
        $four = $this->getDoctrine()->getRepository(Comment::class)->findBy(['product' => $id , 'rating' => 4]);
        $three = $this->getDoctrine()->getRepository(Comment::class)->findBy(['product' => $id , 'rating' => 3]);
        $two = $this->getDoctrine()->getRepository(Comment::class)->findBy(['product' => $id , 'rating' => 2]);
        $one = $this->getDoctrine()->getRepository(Comment::class)->findBy(['product' => $id , 'rating' => 1]);
        $totalrating = $this->getDoctrine()->getRepository(Comment::class)->findBy(['product' => $id ]);
        $best = $this->getDoctrine()->getRepository(Product::class)->findBy(['isBest' => true]);
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $comment = new Comment();
        $form =$this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()  ) {
            
            $comment->setProduct($product);
            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash(
            'success',
            "Thanks for rating");
            return $this->redirectToRoute('show_pro', ['id' => $product->getId()]);
        
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,'form'=> $form->createView(),'best'=>$best, 'five' =>$five,
            'four' =>$four,'three'=>$three,'two'=>$two,'one'=>$one,'total'=>$totalrating
        ]);
    }

  /*   #[Route('/product/add', name: 'add_product')]
    public function add(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $images = $form->get('images')->getData();
            foreach($images as $image){
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new Image();
                $img->setNom($fichier);
                $product->addImage($img);
            }
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('products', [], Response::HTTP_SEE_OTHER);
        }
      
        return $this->render('product/add.html.twig', [
            'form' => $form->createView() ,
        ]);
    } */

  /*   #[Route('/product/edit/{id}', name: 'edit_img', methods: ['GET','POST'])]
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                // On récupère les images transmises
                $images = $form->get('images')->getData();
                
                // On boucle sur les images
                foreach($images as $image){
                    // On génère un nouveau nom de fichier
                    $fichier = md5(uniqid()).'.'.$image->guessExtension();
                    
                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                    
                    // On crée l'image dans la base de données
                    $img = new Image();
                    $img->setNom($fichier);
                    $product->addImage($img);
                }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('products', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    } */

    

 /*    #[Route('/product/delete/{id}', name: 'delete_img')]
    public function deleteimage(Image $image, Request $request)
    {
    $data = json_decode($request->getContent(), true);

   
    if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
        $nom = $image->getNom();

        unlink($this->getParameter('images_directory').'/'.$nom);

        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();

        return new JsonResponse(['success' => 1]);
    }else{
        return new JsonResponse(['error' => 'Token Invalide'], 400);
    }
} */

}
