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
		//var_dump($baseTable->$function()->getId());exit;
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
}
