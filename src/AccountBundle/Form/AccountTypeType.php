<?php

namespace AccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use ClassBundle\Entity\InternalAccount;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountTypeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')

            ->add('internalAccount')

            // ->add('internalAccount', EntityType::class, [
            //     'class' => InternalAccount::class,
            //     'multiple' => true,
            //     'expanded' => true,
            // ])
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\AccountType'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'accountbundle_accounttype';
    }


}
