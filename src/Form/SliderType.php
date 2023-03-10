<?php

namespace App\Form;

use App\Entity\Slider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SliderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'attr'=>['class' => 'form-control']
            ])
            ->add('description',TextType::class,[
                'attr'=>['class' => 'form-control']
            ])
            ->add('btnurl',TextType::class,[
                'attr'=>['class' => 'form-control']
            ])
            ->add('image',FileType::class, [
                'attr'=>['class'=>'img-fluid rounded d-block'],
                'label'=> '',
                'data_class' => null,
                'required' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slider::class,
        ]);
    }
}
