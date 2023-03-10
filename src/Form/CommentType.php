<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating',ChoiceType::class,[
                'attr'=> [
                    'class'=> 'form-select  js-choice',
                    'placeholder' =>"Rating" ,                 
                ],
                'choices'  => [
                    '★★★★★ (5/5)' => 5,
                    '★★★★☆ (4/5)' => 4,
                    '★★★☆☆ (3/5)' => 3,
                    '★★☆☆☆ (2/5)' => 2,
                    '★☆☆☆☆ (1/5)' => 1,
                ],
            ])
            ->add('content',TextareaType::class,[
                'attr'=>['class'=>'md-textarea form-control',
                'placeholder' =>"Write your comment..." ,
                'rows'=>5,
                ]])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
