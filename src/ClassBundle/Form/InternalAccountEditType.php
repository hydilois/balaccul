<?php

namespace ClassBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class InternalAccountEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
        // ->add('accountNumber')
        ->add('description')
        ->add('amount')
        ->add('type', ChoiceType::class, [
            'choices' => [
                'CREDIT' => 'CREDIT',
                'DEBIT' => 'DEBIT',
                'UNDEFINED' => 'UNDEFINED'
            ],
            "required" =>'required'
        ])
        ->add('classe', EntityType::class,
            [
                'class' => 'ClassBundle:Classe',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->where('c.classCategory is NULL');
                }, 
                'required' => true,
                'attr' => 
                    [
                        'data-type' => 'text'
                    ] 
                ]
            )
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ClassBundle\Entity\InternalAccount'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'classbundle_internalaccount';
    }


}
