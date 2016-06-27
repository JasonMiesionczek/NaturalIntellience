<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('dob', DateType::class)
            ->add('birthplace', TextType::class)
            ->add('currentResidence', TextType::class)
            ->add('height', IntegerType::class)
            ->add('weight', IntegerType::class)
            ->add('gender', ChoiceType::class, array(
                'choices' => array('1' => 'Male', '0' => 'Female')
            ))
            ->add('occupation', TextType::class)
            ->add('education', TextType::class)
            ->add('injuries', TextType::class)
            ->add('heritage', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'app_bundle_subject_type';
    }
}
