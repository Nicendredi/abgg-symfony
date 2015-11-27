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
}
