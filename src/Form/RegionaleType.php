<?php

namespace App\Form;

use App\Entity\Activite;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->region = $options['region'];
        $region = $this->region;

        $builder
            ->add('titre', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Le titre de l'actulaité", 'autocomplete'=>"off"],
                'label' => 'activite.labelTitre'
                ])
            ->add('description', TextareaType::class,[
                'attr'=>['class'=>'form-control textarea'],
                'label' => 'activite.labelDescription'
            ])
            ->add('objectif', TextareaType::class,[
                'attr'=>['class'=>'form-control textarea'],
                'label' => 'activite.labelObjectif'
            ])
            ->add('resultat', TextareaType::class,[
                'attr'=>['class'=>'form-control textarea'],
                'label' => 'activite.labelResultat'
            ])
            //->add('annee')
            ->add('dateDebut', TextType::class,[
                'attr'=>['class'=>'form-control pull-right', 'placeholder'=>'Date debut période', 'autocomplete'=>'off'],
                'label' => 'activite.labelDebut'
            ])
            ->add('dateFin', TextType::class,[
                'attr'=>['class'=>'form-control pull-right', 'placeholder'=>'Date fin période', 'autocomplete'=>'off'],
                'label' => 'activite.labelFin'
            ])
            ->add('lieu', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Lieu d'exécution", 'autocomplete'=>'off'],
                'label' => 'activite.labelLieu'
            ])
            ->add('rmo', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Responsable de Mise en Oeuvre", 'autocomplete'=>'off'],
                'label' => 'activite.labelRMO'
            ])
            //->add('slug')
            ->add('statut', CheckboxType::class,['attr'=>['class'=>'custom-control-input'],'required'=>false])
            ->add('participant', EntityType::class,[
                'attr'=>['class'=>'form-control select2 multiple', 'style'=>"width:100%"],
                'class'=> 'App:Participant',
                'query_builder'=> function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label' =>'libelle',
                'label'=>'activite.labelParticipant',
                'multiple' => true
            ])
            ->add('departement', EntityType::class,[
                'attr'=>['class'=>'form-control select2', 'style'=>"width:100%"],
                'class'=> 'App:Departement',
                'query_builder'=> function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label' =>'libelle',
                'label'=>'activite.labelDepatement',
                'multiple' => true,
                'required' => false
            ])
            ->add('district', EntityType::class,[
                'attr'=>['class'=>'form-control select2', 'style'=>"width:100%"],
                'class'=> 'App:District',
                'query_builder'=> function(EntityRepository $er) use($region){
                    return $er->findListByRegion($region);
                },
                'choice_label' =>'nom',
                'label'=>'activite.labelDistrict',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
            'region' => null
        ]);
    }
}
