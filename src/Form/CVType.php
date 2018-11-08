<?php

namespace App\Form;

use App\Entity\CV;
use App\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, array('label' => 'Nom'))
            ->add('firstname', TextType::class, array('label' => 'Prénom'))
            ->add('motivation', TextType::class, array('label' => 'Motivation'))
            ->add('skills', TextType::class, array('label' => 'Compétences'))
            ->add('studies', TextType::class, array('label' => 'Expériences/Etudes'))            
            ->add('birthday', TextType::class, array('label' => 'Date de naissance (jj/mm/aaaa)'))
            ->add('email', TextType::class, array('label' => 'Email'))
            ->add('city', TextType::class, array('label' => 'Ville'))
            ->add('country', TextType::class, array('label' => 'Pays'))
            ->add('phone_number', TextType::class, array('label' => 'Numéro de téléphone'))
            ->add('job', EntityType::class, array(
                'label' => 'Job',
                'class' => Job::class,
                'choice_label' => 'job'
            ))
            ->add('save', SubmitType::class, array('label' => 'Créer mon CV'))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CV::class,
        ]);
    }
}
