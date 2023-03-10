<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'attr'=>['class' => 'form-control']
            ])
          
            ->add('nom',TextType::class,[
                'attr'=>['class' => 'form-control']
            ])
            ->add('prenom',TextType::class,[
                'attr'=>['class' => 'form-control']
            ])
            ->add('phone',TextType::class,[
                'attr'=>['class' => 'form-control']
            ])
            ->add('photo',FileType::class, [
                'attr'=>['class'=>'btn btn-light btn-shadow btn-sm mb-2'],
                'label'=> '',
                'data_class' => null,
                'required' => true])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
