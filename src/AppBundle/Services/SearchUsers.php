<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SearchUsers
{
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
	  $this->em = $em;
	}
	
	public function getSearchingUsers($gameId)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.tournament = :id
		    AND p.team is null
		    AND p.player is null'
		)->setParameter('id', $gameId);
		$users = count($query->getResult());
		return $users;
	}
	
	public function getSearchingTeams($gameId)
	{
        $query = $this->em->createQuery(
		    'SELECT t
		    FROM AppBundle:Team t
		    WHERE t.validation is null'
		);
		$teams = count($query->getResult());
		return $teams;
	}
	
	public function getSearchingUsersSystName($game)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    inner join AppBundle:Game g
		    with p.tournament=g.id
		    WHERE g.systName = :id
		    AND p.team is null
		    AND p.player is null'
		)->setParameter('id', $game);
		$users = count($query->getResult());
		return $users;
	}
	
	public function getSearchingTeamSystName($game)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Team p
		    inner join AppBundle:Game g
		    with p.tournament=g.id
		    WHERE g.systName = :id
		    AND p.validation is null'
		)->setParameter('id', $game);
		$users = count($query->getResult());
		return $users;
	}
	
	public function getSearchingTeamValidation($game)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Team p
		    inner join AppBundle:Game g
		    with p.tournament=g.id
		    WHERE g.systName = :id
		    AND p.validation is not null'
		)->setParameter('id', $game);
		$users = count($query->getResult());
		return $users;
	}
}
