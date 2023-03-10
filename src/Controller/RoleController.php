<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RoleType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RoleController extends AbstractController
{
    #[Route('/role', name: 'app_role')]
    public function index(): Response
    {
        return $this->render('role/index.html.twig', [
            'controller_name' => 'RoleController',
        ]);
    }

    #[Route('/admin/role', name: 'role_user')]
    public function role(UserRepository $userRepository)
    {
        return $this->render('role/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/admin/editrole/{id}', name: 'edit_role')]
    public function editrole(Request $request,$id)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(RoleType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('role_user');
        }
        return $this->render('role/edit.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

}


