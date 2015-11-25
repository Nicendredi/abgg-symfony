<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		
        $builder
        ->add('gender', 'choice', array(
		    'choices' => array(
		        'm' => 'Male',
		        'f' => 'Female'
		    ),
		    'required'    => false,
		    'placeholder' => 'Choose your gender',
		    'empty_data'  => null
		));
		
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'search';
    }
}
