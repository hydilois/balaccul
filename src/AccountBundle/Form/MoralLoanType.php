<?php

namespace AccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoralLoanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        // ->add('deadline')
        ->add('deadline', DateType::class, 
            [
                'widget' => 'single_text',
                'label' => "Date of birth",
                'required' => false,
                'attr' => [
                    'data-type' => 'date'
                ]
            ]
        )
        ->add('loanCode')
        ->add('rate')
        ->add('loanAmount')
        ->add('moralMember')
        ->add('monthlyPayment')
        ->add('loanProcessingFees')
        ->add('status');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\Loan'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'accountbundle_loan';
    }


}
