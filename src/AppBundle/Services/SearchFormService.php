<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SearchFormService
{
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
	  $this->em = $em;
	}
	
	public function createFormLol()
	{
    	$formArray = array(
        0 => array(
            'name' => 'ranking',
            'type' =>'choice',
            'array' => $array = array(
            	'label'    => 'Par Niveau',
			    'choices'  => array('desc' => '+ au - élevé', 'asc' => '- au + élevé'),
			    'required' => false,
			    'expanded' => true,
			    'multiple' => true
				)
            ),
        1 => array(
            'name' => 'manager', 
            'type' =>'choice',
            'array' => $array = array(
            	'label'    => 'Par Niveau',
			    'choices'  => array('man' => 'Manager'),
			    'required' => false,
			    'expanded' => true,
			    'multiple' => true
			    )
            ),
        2 => array(
            'name' => 'main', 
            'type' =>'choice',
            'array' => $array =  array(
            	'label'    => 'Par Niveau',
			    'choices'  => array(
				    'top' => 'Top Lane',
				    'mid' => 'Middle Lane',
				    'bot' => 'Bottom Carry',
				    'sup' => 'Support ',
				    'jungle' => 'Jungle',
				    ),
			    'required' => false,
			    'expanded' => true,
			    'multiple' => true
            )),
        3 => array(
            'name' => 'pseudo', 
            'type' =>'text',
            'array' => $array = array(
            	'label'    => 'Chercher par pseudo',
			    'required' => false,
			    )
            ),
        4 => array(
            'name' => 'save', 
            'type' =>'submit',
            'array' => $array = array(
            	'label' => 'Rechercher'
			    )
            ),
        ); 
		return $formArray;
	}
	
	public function createFormCsgo()
	{
    	$formArray = array(
        0 => array(
            'name' => 'ranking',
            'type' =>'choice',
            'array' => $array = array(
            	'label'    => 'Par Niveau',
			    'choices'  => array('desc' => '+ au - élevé', 'asc' => '- au + élevé'),
			    'required' => false,
			    'expanded' => true,
			    'multiple' => true
				)
            ),
        1 => array(
            'name' => 'manager', 
            'type' =>'choice',
            'array' => $array = array(
            	'label'    => 'Par Niveau',
			    'choices'  => array('man' => 'Manager'),
			    'required' => false,
			    'expanded' => true,
			    'multiple' => true
			    )
            ),
        2 => array(
            'name' => 'pseudo', 
            'type' =>'text',
            'array' => $array = array(
            	'label'    => 'Chercher par pseudo',
			    'required' => false,
			    )
            ),
        3 => array(
            'name' => 'save', 
            'type' =>'submit',
            'array' => $array = array(
            	'label' => 'Rechercher'
			    )
            ),
        ); 
		return $formArray;
	}
}
