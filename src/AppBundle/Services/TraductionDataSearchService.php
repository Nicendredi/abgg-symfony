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

class TraductionDataSearchService
{
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
	  $this->em = $em;
	}
	
	public function getTraductionData($basearray,$game)
	{
		$array = $basearray->getData();
		$manager = $array['manager'];
		$pseudo = $array['pseudo'];
		$players = $array['players'];
		if($game=='lol')
		{
			$main = $array['main'];
		}
		else 
		{
			$main='';
		}

		if($manager)
		{
	        $query = $this->em->createQuery(
			    'SELECT p
			    FROM AppBundle:User p
			    inner join AppBundle:Game g
			    with p.tournament=g.id
		    	where g.systName=\''.$game.'\'
			    and p.manager=1'
			);
			$users = $query->getResult();
			
			return $users;
		}
		else
		{
			$manager='';
		}

		if($game=='lol')
		{
			if($main)
			{
				$phrase=array();
				$i=0;
				if(in_array("top", $main))
				{
					$phrase[$i]=' po.top ASC ';
					$i++;
				}
				if(in_array("mid", $main))
				{
					$phrase[$i]=' po.mid ASC ';
					$i++;
				}
				if(in_array("bot", $main))
				{
					$phrase[$i]=' po.bot ASC ';
					$i++;
				}
				if(in_array("sup", $main))
				{
					$phrase[$i]=' po.sup ASC ';
					$i++;
				}
				if(in_array("jungle", $main))
				{
					$phrase[$i]=' po.jungle ASC ';
					$i++;
				}
				foreach($phrase as $key=>$role)
				{
					if ($key==0)
					{
						$mainRole=' ORDER BY '.$role;
					}
					else 
					{
						$mainRole=$mainRole.' , '.$role;
					}
				}
			}
			else
			{
				$mainRole='';
			}
		}
		else {
			$mainRole='';
		}
		
		if($pseudo)
		{
	        $query = $this->em->createQuery(
			    'SELECT p
			    FROM AppBundle:User p
			    inner join AppBundle:Experience e
			    with p.experience=e.id
			    inner join AppBundle:Game g
			    with p.tournament=g.id
		    	where g.systName=\''.$game.'\'
			    and e.username= :pseudo'
			)->setParameter('pseudo', $pseudo);
			$users = $query->getResult();
			
			return $users;
		}
		else
		{
			$pseudo='';
		}

		if($players)
		{
			switch($players)
			{
				case ((count($players))==2):
					$joueurs='';
			        break;
				case (in_array("inscrit", $players)):
					$joueurs=' and p.team is not null ';
			        break;
				case (in_array("noninscrit", $players)):
					$joueurs=' and p.team is null ';
			        break;
				default:
					$joueurs='';
			        break;
			}
		}
		else {
			$joueurs='';
		}

		if($mainRole !='')
		{
			$innerJoin =' inner join AppBundle:Postes po with e.postes=po.id';
		}
		else {
			$innerJoin='';
		}

        $query = $this->em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    inner join AppBundle:Experience e
		    with p.experience=e.id 
		    inner join AppBundle:Game g
		    with p.tournament=g.id '.$innerJoin.' 
		    where g.systName=\''.$game.'\' '.$mainRole.$joueurs
		);
		$users = $query->getResult();
		
		return $users;
	}
}
