<?php

namespace AccountBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReportItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
        ->add('parentItem', EntityType::class,
                [
                    'class' => 'AccountBundle:ReportItem',
                    'query_builder' => function(EntityRepository $er){
                        return $er->createQueryBuilder('c')
                            ->where('c.parentItem is NULL');

                    }, 
                    'required' => false, 
                    'attr' => 
                        [
                            'data-type' => 'text'
                        ] 
                ]
            );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\ReportItem'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'accountbundle_reportitem';
    }


}
