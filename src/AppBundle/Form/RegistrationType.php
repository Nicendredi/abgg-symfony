<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName','text',array(
				'label'=> 'Nom'
			))
            ->add('lastName','text',array(
				'label'=> 'Prénom'
			))
            ->add('telephone','text',array(
				'label'=> 'Téléphone'
			))
			->add('birth','date', array(
				'label'=> 'Date de Naissance',
			    'input'  => 'datetime',
			    'widget' => 'choice',
			    'years' => range(1900,2015)
			))
			->add('manager','checkbox',array(
				'label'=> 'Cochez si vous êtes Manager d\'une équipe',
				'required' =>false
				))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    public function getParent()
    {
      return 'fos_user_registration';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_registration';
    }
}
