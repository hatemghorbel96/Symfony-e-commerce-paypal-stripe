<?php

namespace App\Form;

use App\Entity\Transporteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address',TextType::class,[
                'attr'=>[
                    'class' =>'form-control',
                ]
            ])
            ->add('phone',TextType::class,[
                'attr'=>[
                    'class' =>'form-control',
                ]
            ])
            ->add('transporteurs',EntityType::class,[
                'label'=>false,
                'required'=> true,
                'class'=> Transporteur::class,
                'multiple'=>false,
                'expanded'=>true,
                'attr'=> [
                    'class'=>'badge bg-info',
                    'font-size'=> 80,
                ]
              
            ])
            ->add('submit',SubmitType::class,[
                'label'=>'Continuer',
                'attr'=>['class'=> 'btn btn-info d-block w-100 mt-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user'=> array(),
        ]);
    }
}
