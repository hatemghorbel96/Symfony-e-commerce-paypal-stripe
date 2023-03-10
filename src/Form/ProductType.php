<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'attr'=>['class' => 'form-control']
            ])
            ->add('price',NumberType::class,[
                'label'=> false,
                'attr'=>['class' => 'form-control h-100']
            ],
            
            )
            ->add('marque',EntityType::class,['class' => Marque::class,
            'choice_label' => 'nom',
            'label' => 'Marque',
           
    
            'attr'=>['class' => 'form-select']]
            )

            ->add('category',EntityType::class,['class' => Category::class,
            'choice_label' => 'nom',
            'label' => 'Catégorie',
            'attr'=>['class' => 'form-select']]
            )
            ->add('stock',NumberType::class,[
                'label'=> false,
                'attr'=>['class' => 'form-control']
            ],
            
            )
         /*    ->add('remise',NumberType::class,[
                'label'=> false,
                'attr'=>['class' => 'form-control']
            ],
            
            ) */

           
            ->add('isbest',ChoiceType::class,[
                'label'=> false,
                'attr'=>['class' => 'form-control'],
                'choices' => array(
                    'Yes' => '1',
                    'No' => '0'
                 ),
            ],
            
            )
            ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
                ,'attr'=>['class' => 'img-fluid rounded d-block']
            ])
        ;
    }
  
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
