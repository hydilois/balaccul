<?php

namespace AccountBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShareType extends AccountType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        parent::buildForm($builder,$options);
        $builder
                // ->add('nternalAccount')
                ->add('nternalAccount', EntityType::class,
                    [
                        'class' => 'ClassBundle:InternalAccount',
                        'query_builder' => function(EntityRepository $er){
                            return $er->createQueryBuilder('i')
                                // ->innerJoin('ClassBundle:');
                                ->where('i.classe = 8');
                        }, 
                        'required' => true, 
                        'attr' => 
                            [
                                'data-type' => 'text'
                            ] 
                        ]
                    )
                ->add('physicalMember')
                // ->add('moralMember')
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\Share'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'accountbundle_share';
    }


}
