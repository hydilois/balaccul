<?php

namespace ClassBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
        ->add('description')
        // ->add('totalAmount')
        ->add('classCategory', EntityType::class,
                [
                    'class' => 'ClassBundle:Classe',
                    'query_builder' => function(EntityRepository $er){
                        return $er->createQueryBuilder('c')
                            ->where('c.classCategory is NULL');

                    }, 
                    'required' => false, 
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
            'data_class' => 'ClassBundle\Entity\Classe'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'classbundle_classe';
    }


}
