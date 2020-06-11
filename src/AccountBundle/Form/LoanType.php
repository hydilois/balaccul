<?php

namespace AccountBundle\Form;

use AccountBundle\Entity\Loan;
use MemberBundle\Entity\Member;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('deadline', DateType::class, 
            [
                'widget' => 'single_text',
                'label' => "Deadline",
                // 'widget' => 'choice',
                'required' => false,
                'attr' => [
                    'data-type' => 'date'
                ]
            ]
        )
        ->add('dateLoan', DateType::class, 
            [
                'widget' => 'single_text',
                'label' => "Date of the loan",
                'required' => false,
                'attr' => [
                    'data-type' => 'date'
                ]
            ]
        )
        ->add('loanCode')
        ->add('rate')
        ->add('loanAmount')
        ->add('physicalMember', EntityType::class,[
             'class' => Member::class,
            'required' => true
             ])
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
            'data_class' => Loan::class
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
