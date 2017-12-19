<?php

namespace MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class MemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder
        ->add('name')
        ->add('sex', ChoiceType::class, [
            'choices' => [
                'Male' => 'Male',
                'Female' => 'Female',
                'Other' => 'Other'
            ],
            "required" =>'required'
        ])
        ->add('dateOfBirth', DateType::class, 
            [
                'widget' => 'single_text',
                'label' => "Date of birth",
                'required' => false,
                'attr' => [
                    'data-type' => 'date'
                ]
            ]
        )
        ->add('placeOfBirth')
        ->add('occupation')
        ->add('address')
        ->add('nicNumber')
        ->add('issuedOn', DateType::class, 
            [
                'widget' => 'single_text',
                'label' => "Issued On",
                'required' => false,
                'attr' => [
                    'data-type' => 'date'
                ]
            ]
        )
        ->add('issuedAt')
        ->add('proposedBy')
            ->add('isAproved', ChoiceType::class,
        [
            'expanded' => false,
            'multiple' => false,
            'choices'=>
                [
                    'Yes' => true,
                    'No' => false
                ],
            'attr'=>
                [
                    'checked' => "checked"
                ],
            'required' => true
        ])
        ->add('aprovedBy')
        ->add('memberNumber')
        ->add('doneAt')
        ->add('membershipDateCreation', DateType::class, 
            [
                'widget' => 'single_text',
                'label' => "Membership date creation",
                'required' => false,
                'attr' => [
                    'data-type' => 'date'
                ]
            ]
        )
        ->add('witnessName')

        ->add('phoneNumber')
        ->add('registrationFees')
        ->add('buildingFees')
        ->add('share')
        ->add('saving')
        ->add('deposit')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MemberBundle\Entity\Member'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'memberbundle_member';
    }
}