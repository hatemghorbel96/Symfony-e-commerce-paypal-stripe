<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    #[Route('/admin/comment', name: 'app_admin_comment')]
    public function index(): Response
    {   
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();
        return $this->render('admin_comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }
}
