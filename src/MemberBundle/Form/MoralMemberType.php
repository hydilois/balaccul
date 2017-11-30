<?php

namespace MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MoralMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('socialReason')
        ->add('dateOfCreation', DateType::class, 
            [
                'widget' => 'single_text',
                'label' => "Date of the creation",
                'required' => false,
                'attr' => [
                    'data-type' => 'date'
                ]
            ]
        )
        ->add('address')
        ->add('proposedBy')
        // ->add('isAproved')
            ->add('isAproved', ChoiceType::class,
        [
            'expanded' => true,
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
                'label' => "Date of the membership creation",
                'required' => false,
                'attr' => [
                    'data-type' => 'date'
                ]
            ]
        )
        ->add('witnessName')
        ->add('registrationFees')
        ->add('phoneNumber');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MemberBundle\Entity\MoralMember'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'memberbundle_moralmember';
    }


}
