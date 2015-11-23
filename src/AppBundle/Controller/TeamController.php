<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Team;
use AppBundle\Entity\TeamRepository;
use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use AppBundle\Entity\Player;
use AppBundle\Entity\Application;
use AppBundle\Form\TeamType;
use AppBundle\Form\PlayerType;
use AppBundle\Services\CheckDataServices;
use AppBundle\Validator\Constraints\HasDifferentPlayers;

/**
 * User controller.
 *
 * @Route("/team")
 */
class TeamController extends Controller
{
    /**
     * Lists all Team entities.
     *
     * @Route("/", name="team")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Team')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Lists all Team entities but only the information avaible to everyone.
     *
     * @Route("/search/{game}", name="search_team")
     * @Method("GET")
     * @Template()
     */
    public function searchAction($game)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Game p
		    WHERE p.systName = :name'
		)->setParameter('name', $game);
		$gaming = $query->getResult();
		$gameId = $gaming[0]->getId();
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Team p
		    WHERE p.tournament = :id'
		)->setParameter('id', $gameId);
		$teams = $query->getResult();
		
		if (($this->getUser()) != null)
		{
			$userId = $this->getUser()->getId();
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Application p
			    WHERE p.user = :id'
			)->setParameter('id', $userId);
			$userApp = $query->getResult();
			
			$i=0;
			if ($userApp!=null)
			{
				foreach ($userApp as $user)
				{
					$userAppTeams[$i] = $user->getTeam();
					$i++;
				}
			}
			else
			{
				$userAppTeams=0;
			}
		}
		else {
			$userAppTeams=0;
		}
		
        return array(
            'entities' => $teams,
            'appTeam'  => $userAppTeams,
        );
    }
	
    /**
     * Creates a new Experience entity.
     *
     * @Route("/team", name="team_create")
     * @Method("POST")
     * @Template("AppBundle:Team:newTeam.html.twig")
     */
    public function createTeamAction(Request $request)
    {
        $entity = new Team();
        $form = $this->createFormTeam($entity);
        $form->handleRequest($request);
		$data = $form->getData();
		
        if ($form->isValid()) {
            $user = $this->getUser();
	    	$game = $this->getUser()->getTournament();
			$player = $form->getData()->getPlayer();
			
			$entity->setTournament($game);
            $user->setTeam($entity);
			$user->setCapitain($entity);
            $this->get('fos_user.user_manager')->updateUser($user, false);
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
			
			$player = $entity->getPlayer();
			
			foreach ($player as $players)
			{
				$players -> setTeam($entity);
				if ($players->getRole() != null)
				{
					$players -> getUser()->setRole($players->getRole());
				}
			}

            $em->persist($entity);
            $em->flush();
			
	    	$game = $this->getUser()->getTournament();
			$id = $game->getId();
	        $em = $this->getDoctrine()->getManager();
	        $gameId = $em->getRepository('AppBundle:Game')->find($id);
			if ($game -> getSystName() == 'League of Legends')
			{
				$url='lol';
			}
			else
			{
				$url='csgo';
			}
            return $this->redirect($this->generateUrl($url));
        }
		elseif ((($data->getName())!=null) 
		&& ($data->getCaptain()->getRole()) != null ){
			$entity = new Team();
			$entity->setName($data->getName());
	    	$game = $this->getUser()->getTournament();
			
            $user = $this->getUser();
            $user->setTeam($entity);
			$user->setCapitain($entity);
			$user->setRole($data->getCaptain()->getRole());
            $this->get('fos_user.user_manager')->updateUser($user, false);
			
            $em = $this->getDoctrine()->getManager();
			$entity->setCaptain($user);
			$entity->setTournament($game);
            $em->persist($entity);
            $em->flush();
			
			if ($game->getName() == 'League of Legends')
			{
				$url='lol';
			}
			else
			{
				$url='csgo';
			}
            return $this->redirect($this->generateUrl($url));
		}
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a form to create a Team entity.
     *
     * @param Experience $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createFormTeam(Team $entity)
    {
    	$gameId = $this->getUser()->getTournament()->getId();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Game p
		    WHERE p.id = :id'
		)->setParameter('id', $gameId);

		$game = $query->getResult();
		$gameName = $game[0]->getName();
		
		$telephone = $this->getUser()->getTelephone();
		
        $form = $this->createForm(new TeamType($gameId,$gameName,$telephone), $entity, array(
            'action' => $this->generateUrl('team_create'),
            'method' => 'POST'
        ));
        return $form;
    }
    /**
     * Displays a form to create a new Team entity.
     *
     * @Route("/new", name="team_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Team();
        $form   = $this->createFormTeam($entity);
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="team_show")
     * @Method("GET")
     * @Template("AppBundle:Team:show.html.twig")
     */
    public function showAction()
    {
    	$id = $this->getUser()->getTeam()->getId();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Team p
		    WHERE p.id = :id'
		)->setParameter('id', $id);
		$team = $query->getResult();
		$checkData = $this -> container -> get('checkDataServices');

		$player = $checkData -> checkDataCollection($team[0], 'getPlayer', 'Player');

        if (!$team) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'team'      => $team[0],
            'players'   => $player, 
            'delete_form' => $deleteForm->createView(),
        );
    }
	
    /**
     * Deletes a Team entity.
     *
     * @Route("/{id}", name="team_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Team')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Team entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('team'));
    }

    /**
     * Creates a form to delete a Team entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('team_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
	
    /**
     * Finds and displays a User entity.
     *
     * @Route("/application/{id}", name="team_application")
     * @Method("GET")
     */
    public function applicationAction($id)
    {
        $entity = new Application();
		$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Team p
		    WHERE p.id = :id'
		)->setParameter('id', $id);
		$teams = $query->getResult();
		$team = $teams[0];
		$gameName = $team->getTournament()->getSystName();
		
		$entity->setTeam($team);
		$entity->setUser($user);

        $em->persist($entity);
        $em->flush();
		return $this->redirect($this->generateUrl('search_team', array('game'=> $gameName )));
    }
}