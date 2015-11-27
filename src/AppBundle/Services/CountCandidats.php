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
	
	public function getSearchingCandidats($teamId)
	{
        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.team = :id
		    AND p.origin = \'player\''
		)->setParameter('id', $teamId);
		$users = count($query->getResult());
		return $users;
	}
}
