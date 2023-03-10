<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ComEtatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('etat',ChoiceType::class, [
                'attr'=>['class'=>'form-control selectpicker mt-3 mb-3'],
                'label'=>'Payé/Non Payé',
                'choices'  => [
                    'Non payé'=>'0',
                    'Payé' => '1',
                               
                ] ])     
           
            ->add('etatcom',ChoiceType::class, [
                'label'=>'Etat du commande',
                'attr'=>['class'=>'form-control selectpicker mt-3 mb-3'],
                'choices'  => [                    
                    'Commande passée'=>'0','Confirmé'=>'6',
                    'Commande Expédié' => '1',
                    'En livraison' => '2',
                    'Livrée' => '3',
                    
                ],])
                ->add('submit',SubmitType::class,[
                    'label'=>'valide',
                    'attr'=>['class'=>'btn btn-info d-block w-100']
                ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
