<?php

namespace App\Form;

use App\Entity\Gestionnaire;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GestionnaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('nom')
            //->add('statut')
            ->add('user', EntityType::class,[
                'attr'=>['class'=>'form-control select2', 'style'=>"width:100%"],
                'class'=> 'App:User',
                'query_builder'=> function(EntityRepository $er){
                    return $er->getListWithoutUser();
                },
                'choice_label' =>'username',
                'label'=>'gestionnaire.username'
            ])
            ->add('district', EntityType::class,[
                'attr'=>['class'=>'form-control select2', 'style'=>"width:100%"],
                'class'=> 'App:District',
                'query_builder'=> function(EntityRepository $er){
                    return $er->getListWithoutDistrict();
                },
                'choice_label' =>'nom',
                'label'=>'gestionnaire.district'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gestionnaire::class,
        ]);
    }
}
