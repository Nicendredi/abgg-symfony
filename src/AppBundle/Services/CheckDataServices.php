<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CheckDataServices
{
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
	  $this->em = $em;
	}
	
	public function checkData($baseTable, $function, $entity)
	{
		if (($baseTable->$function()) !=null)
		{
			$id = $baseTable->$function()->getId();
			$data = $this ->em->getRepository('AppBundle:'.$entity)
                      ->findOneById($id);
		}
		else {
			$data = 0;
		}
		return $data;
	}
	
	public function checkDataCollection($baseTable, $function, $entity)
	{
		$i=0;
		
		$players = $baseTable->getPlayer()->toArray();
		
		foreach ($players as $element)
		{
			$id = $element->getId();
			$data = $this ->em->getRepository('AppBundle:'.$entity)
                      ->findOneById($id);
			$array[$i]=$data;
			$i++;
		}
		
		if(isset($array))
		{
			
		}
		else {
			$array=array();
		}
		
		return $array;
	}
	
	public function checkLolValidation($players)
	{
		$i=0;
		
		foreach($players as $player)
		{
			$roleId = $player->getRole()->getId();
			
	        $query = $this->em->createQuery(
			    'SELECT r
			    FROM AppBundle:Role r
			    WHERE r.id = '.$roleId
			);
			 $data = $query->getResult();
			 $roles[$i] = $data[0]->getName();
			$i++;
		}
		
		if ((in_array("Top Lane", $roles)) 
		&& (in_array("Middle Lane", $roles))
		&& (in_array("Bottom Carry", $roles))
		&& (in_array("Support ", $roles))
		&& (in_array("Jungle", $roles)))
		{
			$validation = true;
		}
		else
		{
			$validation = false;
		}
		
		return $validation;		
	}
	public function checkCsgoValidation($players)
	{
		$i=0;
		$team=count($players);
		
		for($i=0;$i<$team-1; $i++)
		{
			$manager = $players[$i]->getUser()->getManager();
			$managers[$i]=$manager;
		}

		if ((in_array(true, $managers)))
		{
			if($team>=6)
			{
				$validation = true;
			}
			else 
			{
				$validation = false;
			}
		}
		else
		{
			if($team>=5)
			{
				$validation = true;
			}
			else 
			{
				$validation = false;
			}
		}
		
		return $validation;
	}
}
