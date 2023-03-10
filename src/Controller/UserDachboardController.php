<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Comment;
use App\Entity\Product;
use App\Form\EditProfileType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDachboardController extends AbstractController
{
    #[Route('/user/commende', name: 'user_commende')]
    public function index(): Response
    {
        $myorders = $this->getDoctrine()->getRepository(Order::class)->findby(['user'=>$this->getUser()]);
        return $this->render('user_dachboard/index.html.twig',[
            'myorders'=> $myorders
        ]);
    }


    #[Route('/user/transactions', name: 'user_transactions')]
    public function transactions(): Response
    {
        $myorders = $this->getDoctrine()->getRepository(Order::class)->findby(['user'=>$this->getUser()]);
        return $this->render('user_dachboard/transactions.html.twig',[
            'myorders'=> $myorders
        ]);
    }

    #[Route('/user/profile', name: 'user_profile')]
    public function profile(Request $request,EntityManagerInterface $manager): Response
    {
        $user =$this->getUser();

        $form =$this->createForm(EditProfileType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $file = $form->get('photo')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
            }
            $user->setPhoto($fileName);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "les données du profil ont éte enregister avec succés"
            );
        }
        return $this->render('user_dachboard/profile.html.twig',[
            'form'=> $form->createView(),
        ]);
    }


    #[Route('/user/favorit', name: 'user_favorits')]
    public function favorit(): Response
    {
        $user = $this->getUser();
        return $this->render('user_dachboard/favorit.html.twig',[
            'user'=> $user
        ]);
    }

    #[Route('/user/commentes', name: 'user_comments')]
    public function comments(): Response
    {
        
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(['user'=>$this->getUser()]);
        return $this->render('user_dachboard/comments.html.twig',[
            'comments'=>$comments
        ]);
    }

    #[Route('/product/dashfavorit/{id}', name: 'dash_fav')]
    public function dashfavorit(Product $product):Response {
       
    
        $product->removeFavorit($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return $this->redirectToRoute('user_favorits');
         }

    #[Route('/user/commende/{id}', name: 'details_user_commende')]
    public function details($id): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $order = $this->getDoctrine()->getRepository(Order::class)->findOneById($id);
        return $this->render('user_dachboard/show.html.twig',[
            'order'=> $order,'products'=>$products,
        ]);
    }


    
    #[Route('/user/updatepass/', name: 'compte_password')]
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager){
      
        $user = $this->getUser();
        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class,$passwordUpdate);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            if(!password_verify($passwordUpdate->getOldPassword(),$user->getPassword())) {
                $form->get('oldPassword')->addError(new FormError("le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            }else{
                $newPassword =$passwordUpdate->getNewPassword();

                $hash = $encoder->encodePassword($user,$newPassword);
                $user->setPassword($hash);

                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "votre mot de passe a bien ete modifier !"
                );
                return $this->redirectToRoute('compte_password');
            }
        }
        return $this->render('user_dachboard/password.html.twig',[
            'form'=>$form->createView()
        ]);

    }
 


   
    public function menu()
    {
        $user = $this->getUser();
        return $this->render('user_dachboard/menu.html.twig',[
            'user'=> $user
        ]);
    }


}
