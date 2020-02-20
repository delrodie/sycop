<?php

namespace App\Form;

use App\Entity\District;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistrictType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('region', EntityType::class,[
                'attr'=>['class'=>'form-control select2', 'style'=>"width:100%"],
                'class'=> 'App:Region',
                'query_builder'=> function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label' =>'nom',
                'label'=>'district.labelRegion'
            ])
            ->add('nom',TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off'],'label'=>'district.labelName'])
            ->add('localite', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off'],'label'=>'district.labelLocalite','required'=>false])
            //->add('user')
            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => District::class,
        ]);
    }
}
