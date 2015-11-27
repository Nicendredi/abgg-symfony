<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AppBundle\Entity\RankingRepository;
use AppBundle\Entity\RoleRepository;
use AppBundle\Form\PostesType;

class PostesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		
        $builder
            ->add('top', 'choice', array(
				'label'=> 'Top Lane',
	            'choices' => array(
	            	1 => 1,
	            	2 => 2,
	            	3 => 3,
	            	4 => 4,
	            	5 => 5),
				'expanded'=>false,
				'multiple'=>false))
            ->add('mid', 'choice', array(
				'label'=> 'Middle Lane',
	            'choices' => array(
	            	1 => 1,
	            	2 => 2,
	            	3 => 3,
	            	4 => 4,
	            	5 => 5),
				'expanded'=>false,
				'multiple'=>false))
            ->add('bot', 'choice', array(
				'label'=> 'Bottom Carry',
	            'choices' => array(
	            	1 => 1,
	            	2 => 2,
	            	3 => 3,
	            	4 => 4,
	            	5 => 5),
				'expanded'=>false,
				'multiple'=>false))
            ->add('sup', 'choice', array(
				'label'=> 'Support',
	            'choices' => array(
	            	1 => 1,
	            	2 => 2,
	            	3 => 3,
	            	4 => 4,
	            	5 => 5),
				'expanded'=>false,
				'multiple'=>false))
            ->add('jungle', 'choice', array(
				'label'=> 'Jungle',
	            'choices' => array(
	            	1 => 1,
	            	2 => 2,
	            	3 => 3,
	            	4 => 4,
	            	5 => 5),
				'expanded'=>false,
				'multiple'=>false));
    }
	
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Postes'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'postes';
    }
}
