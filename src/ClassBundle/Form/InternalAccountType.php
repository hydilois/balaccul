<?php

namespace ClassBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class InternalAccountType extends AbstractType{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('accountName')
        ->add('accountNumber')
        // ->add('beginingBalance')
        // ->add('endingBalance')
        // ->add('credit')
        // ->add('debit')
        ->add('classe')
        // ->add('classe', EntityType::class,
        //     [
        //         'class' => 'ClassBundle:Classe',
        //         'query_builder' => function(EntityRepository $er){
        //             return $er->createQueryBuilder('c')
        //                 ->where('c.id = 7');
        //         }, 
        //         'required' => true,
        //         'attr' => ['data-type' => 'text'] 
        //         ])
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
