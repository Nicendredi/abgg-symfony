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
	
	public function getRoleAvailable($teamId, $userId = null)
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
				$roleId[$i]= $role->getId();
				$phrase = $phrase.' and r.id != '.$roleId[$i];
				$i++;
			}
			
			if($roles[0]->getGame())
			{
				$gameId=$roles[0]->getGame()->getId();
				$game='r.game='.$gameId.$phrase.' or ';
			}
			else
			{
				$game='';
			}
			
	        $query = $this->em->createQuery(
			    'SELECT r
			    FROM AppBundle:Role r 
			    where '.$game.' r.game is null 
			    and r.name != \'Manager\''.$phrase
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
				
			if($userId != null)
			{
				$formBuilder ->add('userId','hidden', array('data'=> $userId));
			}
				
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


	public function getManagerAvailable($teamId)
	{
        $query = $this->em->createQuery(
		    'SELECT u
		    FROM AppBundle:User u
		    INNER JOIN AppBundle:Role r
		    WITH u.role=r.id
		    WHERE u.team = :id
		    and u.manager = 1'
		)->setParameter('id', $teamId);
		$roles = $query->getResult();
		
		if (empty($roles))
		{
	        $query = $this->em->createQuery(
			    'SELECT r
			    FROM AppBundle:Role r
			    WHERE r.name = \'Manager\''
			);
			$role = $query->getResult();
			
		    $formBuilder = $this->container->get('form.factory')->createBuilder()
				->add('role','choice', array(
					'label'    => 'Poste disponible',
		            'choices'  => array(($role[0]->getId()) => ($role[0]->getName())),
		            'expanded' => true,
		            'multiple' => true,
		            'required' => false,
					))
				->add('teamId','hidden', array('data'=> $teamId))
	            ->add('save', 'submit', array('label' => 'Postuler'));
			$form = $formBuilder->getForm();
		
			return ($form->createView());
		}
		else {
			return;
		}
	}
}
