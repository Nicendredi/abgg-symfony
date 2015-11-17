<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExperienceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lookingForTeam')
            ->add('rankActual')
            ->add('username')
            ->add('role_1')
            ->add('role_2')
            ->add('role_3')
            ->add('role_4')
            ->add('role_5')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Experience'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_experience';
    }
}
