<?php

namespace MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
        ->add('idNumber')
        ->add('collector', EntityType::class,[
                'class' => 'UserBundle:Utilisateur',
                'query_builder' => function(EntityRepository $er){
                         return $er->createQueryBuilder('u')
                            ->innerJoin('UserBundle:Groupe','gr','WITH','u.groupe = gr.id')
                            ->where('gr.id = 3');
                        }
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MemberBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'memberbundle_client';
    }


}
