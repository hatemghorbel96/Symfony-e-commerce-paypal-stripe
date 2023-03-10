<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCategoryController extends AbstractController
{
   

    #[Route('/admin/category', name: 'app_admin_category')]
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('admin_category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/category/add', name: 'add_category')]
    public function add(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $image = $form->get('img')->getData();
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
            $category->setImg($fichier);
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_category');
        }
      
        return $this->render('admin_category/add.html.twig', [
            'form' => $form->createView() ,
        ]);
    }


    #[Route('/admin/category/edit/{id}', name: 'edit_category')]
    public function edit($id,Request $request): Response
    {
        $categorie= $this->getDoctrine()->getrepository(category::class)->find($id);
        $form = $this->createForm(CategoryType::class,$categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $image = $form->get('img')->getData();
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
            $categorie->setImg($fichier);
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_category');
        }
      
        return $this->render('admin_category/edit.html.twig', [
            'form' => $form->createView() ,
        ]);
    }

}
