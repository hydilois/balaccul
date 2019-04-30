<?php

namespace ClassBundle\Form;

use ClassBundle\Entity\InternalAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InternalAccountEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('accountName')
            ->add('token', ChoiceType::class, [
                'choices'=> $this->getTokenChoices(),
                'required' => true,
                'label' => 'Type',
            ]);
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

    /**
     * @return array
     */
    private function getTokenChoices()
    {
        $choices = InternalAccount::TOKEN;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$k] = $v;
        }
        ksort($output);
        return $output;
    }
}
