<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;

class CheckRoleTeamApplication
{
	public function __construct(\Doctrine\ORM\EntityManager $em,ContainerInterface $container)
	{
	  $this->em = $em;
	  $this->container = $container;
	}
	
	public function getRoleAvailable($teamId)
	{
        $query = $this->em->createQuery(
		    'SELECT r
		    FROM AppBundle:Role r
		    INNER JOIN AppBundle:User u
		    WITH u.role=r.id
		    WHERE u.team = :id'
		)->setParameter('id', $teamId);
		$roles = $query->getResult();
		
		if($roles[0]!=null)
		{
			$i=0;
			$phrase='';
			foreach($roles as $role)
			{
				if($phrase=='')
				{
					$roleId[$i]= $role->getId();
					$phrase ='WHERE r.id != '.$roleId[$i];
				}
				else 
				{
					$roleId[$i]= $role->getId();
					$phrase = $phrase.' and r.id != '.$roleId[$i];
				}
				$i++;
			}
			
	        $query = $this->em->createQuery(
			    'SELECT r
			    FROM AppBundle:Role r '.$phrase
			);
			$postes = $query->getResult();
			
			foreach($postes as $poste)
			{
				$choice[$poste->getId()]=$poste->getName();
			}
			
		    $formBuilder = $this->container->get('form.factory')->createBuilder()
				->add('role','choice', array(
					'label'    => 'Postes disponibles',
		            'choices'  => $choice,
		            'expanded' => true,
		            'multiple' => true,
		            'required' => false,
					))
				->add('teamId','hidden', array('data'=> $teamId))
	            ->add('save', 'submit', array('label' => 'Postuler'));
			$form = $formBuilder->getForm();
			
			return ($form->createView());
		}
		
		$query = $this->em->createQuery(
		    'SELECT r
		    FROM AppBundle:Role r'
		);
		$roles = $query->getResult();
		
		foreach($roles as $role)
		{
			$choice[$role->getId()]=$role->getName();
		}
		
		$formBuilder = $this->container->get('form.factory')->createBuilder()
			->add('role','choice', array(
	            'choices' => $choice
				))
            ->add('save', 'submit', array('label' => 'Postuler'));
		$form = $formBuilder->getForm();
		
		return ($form->createView());
	}
}
