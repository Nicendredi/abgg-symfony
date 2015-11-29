<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Team;
use AppBundle\Entity\TeamRepository;
use AppBundle\Entity\Game;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$game = $em -> getRepository('AppBundle:Game')->findByName('League of Legends');
		$gameId = $game[0]->getId();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.tournament = :id
		    AND p.team is null
		    AND p.player is null'
		)->setParameter('id', $gameId);
		$users = $query->getResult();
		
      return $this->render('default/index.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          'searchUsers' => count($users),
      ));
    }

    /**
     * @Route("/lol", name="lol")
     */
    public function lolAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Team')->findAll(); 
		$game = $em -> getRepository('AppBundle:Game')->findByName('League of Legends');
		$gameId = $game[0]->getId();
		$listEntities=array();
		$i=0;

		foreach ($entities as $entity)
		{
			$test = $entity->getTournament()->getId();
			if($test == $gameId)
			{
				$listEntities[$i] =$entity;
				$i++;
			}
		}
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.tournament = :id
		    AND p.team is null
		    AND p.player is null'
		)->setParameter('id', $gameId);
		$users = $query->getResult();
		
		if(($this->getUser()) && ($this->getUser()->getTournament()))
		{
	        $em = $this->getDoctrine()->getManager();
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Game p
			    WHERE p.name = :name'
			)->setParameter('name', $this->getUser()->getTournament()->getName());
			$checkGame = $query->getResult();
			$checking=$checkGame[0]->getName();
		}
		else
		{
			$checking=0;
		}
		
      return $this->render('default/lol.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          'entities' => $listEntities,
          'game'     => $game,
          'games'   => $game[0],
          'searchUsers' => count($users),
          'checkGame' => $checking
      ));
    }

    /**
     * @Route("/csgo", name="csgo")
     */
    public function csgoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Team')->findAll(); 
		$game = $em -> getRepository('AppBundle:Game')->findByName('Counter Strike : Global Offensive');
		$gameId = $game[0]->getId();
		$listEntities=array();
		$i=0;
		
		foreach ($entities as $entity)
		{
			$test = $entity->getTournament()->getId();
			if($test == $gameId)
			{
				$listEntities[$i] =$entity;
				$i++;
			}
		}
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.tournament = :id
		    AND p.team is null
		    AND p.player is null'
		)->setParameter('id', $gameId);
		$users = $query->getResult();
		
		
		if(($this->getUser()) && ($this->getUser()->getTournament()))
		{
	        $em = $this->getDoctrine()->getManager();
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Game p
			    WHERE p.name = :name'
			)->setParameter('name', $this->getUser()->getTournament()->getName());
			$checkGame = $query->getResult();
			$checking=$checkGame[0]->getName();
		}
		else
		{
			$checking=0;
		}
		
      return $this->render('default/csgo.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          'entities' => $listEntities,
          'game'     => $game,
          'games'   => $game[0],
          'searchUsers' => count($users),
          'checkGame' => $checking
      ));
    }

    /**
    * @Route("/blog", name="blog")
    */
    public function blogAction(Request $request)
    {
      return $this->redirect($this->generateUrl('post_new'));
    }

    /**
    * @Route("/admin", name="admin")
    */
    public function adminAction(Request $request)
    {
      return $this->render('default/admin.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
      ));
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profilAction(Request $request)
    {
    	$user=$this->getUser();
	    $response = $this->forward('AppBundle:User:show', array(
	        'user'  => $user
	        
	    ));
	
	    return $response;
    }

    /**
     * @Route("/equipe", name="equipe")
     */
    public function equipeAction(Request $request)
    {
    	$user=$this->getUser();
	    $response = $this->forward('AppBundle:Team:show', array(
	        'user'  => $user
	        
	    ));
	
	    return $response;
    }

    /**
    * @Route("/invite/{id}", name="invite")
    */ 
    public function inviteAction(Request $request, $id)
    {

      $this->container->get('session')->set('invitation', $id);

      $response = $this->forward('FOSUserBundle:Registration:register', array(
        'invitation' => $id));

      return $response;
    }

    /**
     * @Route("/search/player", name="search_player")
     */
    public function searchPlayerAction($game)
    {
	    $response = $this->forward('AppBundle:User:search_player', array(
	        'game'  => $game
	        
	    ));
	
	    return $response;
    }

    /**
     * @Route("/search/team", name="search_team")
     */
    public function searchTeamAction($game)
    {
	    $response = $this->forward('AppBundle:Team:search_team', array(
	        'game'  => $game
	        
	    ));
	
	    return $response;
    }

}
