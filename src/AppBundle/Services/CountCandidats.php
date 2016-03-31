<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CountCandidats
{
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
	  $this->em = $em;
	}
	
	public function getPayedTeams($game=null)
	{
		if($game!=null)
		{
	        $query = $this->em->createQuery(
			    'SELECT p
			    FROM AppBundle:Team p
			    inner join AppBundle:Validation v
			    with v.team=p.id
			    inner join AppBundle:Game g
				with g.id=p.tournament
			    WHERE v.payed=1
			    and g.systName=\''.$game.'\''
			);
		}
		else {
			
	        $query = $this->em->createQuery(
			    'SELECT p
			    FROM AppBundle:Team p
			    inner join AppBundle:Validation v
			    with v.team=p.id
			    WHERE v.payed=1'
			    );
		}
		$teams = count($query->getResult());
		return $teams;
	}
	
	
	public function getSearchingCandidats($teamId)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.team = :id
		    AND p.origin = \'player\'
		    and p.blocked is null'
		)->setParameter('id', $teamId);
		$users = count($query->getResult());
		return $users;
	}
	public function getAllSearchingCandidats($teamId)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.team = :id
		    AND p.origin = \'player\'
		    and p.blocked is null'
		)->setParameter('id', $teamId);
		$users = $query->getResult();
		return $users;
	}
	
	public function getApplicationFromTeam($teamId)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.team = :id
		    AND p.origin = \'team\'
		    and p.blocked is null'
		)->setParameter('id', $teamId);
		$users = count($query->getResult());
		return $users;
	}
	
	public function getAllApplicationFromTeam($teamId)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.team = :id
		    AND p.origin = \'team\'
		    and p.blocked is null'
		)->setParameter('id', $teamId);
		$users = $query->getResult();
		
		return $users;
	}
	
	public function getCandidatureFromTeam($user)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.user = :id
		    AND p.origin = \'team\'
		    and p.blocked is null'
		)->setParameter('id', $user);
		$users = count($query->getResult());
		return $users;
	}
	
	public function getCandidatureFromUser($user)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.user = :id
		    AND p.origin = \'player\'
		    and p.blocked is null'
		)->setParameter('id', $user);
		$users = count($query->getResult());
		return $users;
	}
}
