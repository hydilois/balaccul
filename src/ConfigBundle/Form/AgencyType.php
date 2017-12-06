<?php

namespace ConfigBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgencyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
        ->add('abreviation')
        ->add('code')
        ->add('address')
        ->add('branch')
        ->add('town')
        ->add('registrationNumber')
        ->add('poBox')
        ->add('phoneNumber')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ConfigBundle\Entity\Agency'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'configbundle_agency';
    }


}
