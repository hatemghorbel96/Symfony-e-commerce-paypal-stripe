<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Form\RemiseType;
use App\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProductController extends AbstractController
{
    #[Route('/admin/product', name: 'admin_product')]
    public function index(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render('admin_product/index.html.twig', [
            'products' => $products,
        ]);
    }

 

    #[Route('/admin/product/add', name: 'add_admin_product')]
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
            return $this->redirectToRoute('admin_product', [], Response::HTTP_SEE_OTHER);
        }
      
        return $this->render('admin_product/add.html.twig', [
            'form' => $form->createView() ,
        ]);
    }

    #[Route('/admin/product/edit/{id}', name: 'admin_edit_img', methods: ['GET','POST'])]
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

            return $this->redirectToRoute('admin_product', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/admin/product/remise/{id}', name: 'remise', methods: ['GET','POST'])]
    public function remise(Request $request, Product $product): Response
    {
        $form = $this->createForm(RemiseType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();
                   
               

           

            return $this->redirectToRoute('admin_product', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_product/remise.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/product/delete/{id}', name: 'delete_img')]
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
}


}
