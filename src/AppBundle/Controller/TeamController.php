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
use AppBundle\Form\CaptainType;
use AppBundle\Services\CheckDataServices;
use AppBundle\Validator\Constraints\HasDifferentPlayers;
use Symfony\Component\Validator\Constraints\DateTime;

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
     * @Route("/search/{game}", name="search_team_get")
     * @Method("GET")
     * @Template()
     */
    public function searchAction($game)
    {
        $em = $this->getDoctrine()->getManager();
		if ($game==null)
		{
			$game=$this->getUser()->getTournament()->getSystName();
		}

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
			    WHERE p.user = :id
			    and p.origin = \'player\'
			    and p.blocked is null'
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
     * Lists all Team entities but only the information avaible to everyone.
     *
     * @Route("/search/{game}", name="search_team")
     * @Method("POST")
     * @Template()
     */
     public function searchPostAction(Request $request)
     {
     	$data=$request->request->all();
		$forms =$data['form'];
		$teamId = $forms['teamId'];
		$form = $forms['role'];
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT t
		    FROM AppBundle:Team t
		    WHERE t.id = :id'
		)->setParameter('id', $teamId);
		$team = $query->getResult();
		
		foreach($form as $role)
		{
	        $em = $this->getDoctrine()->getManager();
	        $query = $em->createQuery(
			    'SELECT r
			    FROM AppBundle:Role r
			    WHERE r.id = :id'
			)->setParameter('id', intval($role));
			$roleArray = $query->getResult();
			
			$entity = new Application();
			$entity->setUser($this->getUser());
			$entity->setTeam($team[0]);
			$entity->setRole($roleArray[0]);
			$entity->setOrigin('player');
	        $em->persist($entity);
		}
	    $em->flush();
		
		return $this->redirect($this->generateUrl('search_team', array('game'=> $this->getUser()->getTournament()->getSystName() )));
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
        $check = $request->request->all();
        $team = $check['team'];
		if(array_key_exists('application', $team))
		{
        	$application = $team['application'];
		}
        $entity = new Team();
        $form = $this->createFormTeam($entity);
        $form->handleRequest($request);
		$data = $form->getData();
		
        if ($form->isValid()) {
            $user = $this->getUser();
	    	$game = $this->getUser()->getTournament();
			$player = $form->getData()->getPlayer();
			
			$entity->setTournament($game);
			$entity->setCaptain($user);
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

            $application = $data->getApplication();
            foreach ($application as $appli) {
                $appli->setTeam($entity);
                $appli->setOrigin("team");

                $em->persist($appli);
                $em->flush();
                $mailer = $this->container->get('invitation_services');
                $mailer->sendInvitation($appli, $user);

            }

            $em->persist($entity);
            $em->flush();
			
	    	$game = $this->getUser()->getTournament();
			$id = $game->getId();
	        $em = $this->getDoctrine()->getManager();
	        $gameId = $em->getRepository('AppBundle:Game')->find($id);
			if ($game -> getSystName() == 'lol')
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
            
            $em = $this->getDoctrine()->getManager();
            $game = $this->getUser()->getTournament();
            
            $user = $this->getUser();
            
            $id = $game->getId();
            $gameId = $em->getRepository('AppBundle:Game')->find($id);
            if ($game -> getSystName() == 'lol')
            {
                $totalrequest =$request->request->all();
                $team=$totalrequest['team'];
                $captain = $team['captain'];
                $role = $captain['role'];
            
                $em = $this->getDoctrine()->getManager();
                $roleEntity = $em->getRepository('AppBundle:Role')->find($role);
                $user->setRole($roleEntity);
            }
            
            $user->setTeam($entity);
            $user->setCapitain($entity);
            
            $this->get('fos_user.user_manager')->updateUser($user, false);
            
            $application = $data->getApplication();
            foreach ($application as $appli) {
                $appli->setTeam($entity);
                $appli->setOrigin("team");

                $em->persist($appli);
                $em->flush();
                $mailer = $this->container->get('invitation_services');
                $mailer->sendInvitation($appli, $user);

            }

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
		    WHERE p.id = '.$id
		);
		$team = $query->getResult();
		$checkData = $this -> container -> get('checkDataServices');

		$player = $checkData -> checkDataCollection($team[0], 'getPlayer', 'Player');

        if (!$team) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }
		
		if($player!=null)
		{
			$roles = $player;
			$roles[count($roles)] = $team[0]->getCaptain();
		
			$game = $this->getUser() -> getTournament()-> getSystName();
			
			if($team[0]->getValidation() == null)
			{
				if ($game == 'lol')
				{
					$validation = $checkData -> checkLolValidation($roles);
				}
				else 
				{
					$validation = $checkData -> checkCsgoValidation($roles);
				}
			}
			else
			{
				$validation = false;
			}
		}
		else
		{
			$validation = false;
		}
		
        return array(
            'team'        => $team[0],
            'players'     => $player, 
            'validation'  => $validation,
        );
    }
	
    /**
     * Deletes a Team entity.
     *
     * @Route("/delete/team/{id}", name="team_delete")
     * @Method("GET")
     * @Template()
     */
    public function deleteAction($id)
    {
    	$gameName = $this->getUser()->getTournament()->getSystName();
		
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getUser()->getTeam();

        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Team p
		    WHERE p.id = '.$id
		);
		$team = $query->getResult();
		
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.team = '.$id
		);
		$users = $query->getResult();
		
		foreach ($users as $user)
		{
			$user->setRole(null);
			$user->setTeam(null);
			
			if($user->getCapitain()!=null)
			{
				$user->setCapitain(null);
			}
			if($user->getPlayer()!=null)
			{
				$user->setPlayer(null);
			}
			
			$em->persist($user);
		}
		
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Player p
		    WHERE p.team = '.$id
		);
		$players = $query->getResult();
		
		foreach ($players as $player)
		{
			$em->remove($player);
		}
		
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.team = '.$id
		);
		$applications = $query->getResult();
		
		foreach ($applications as $application)
		{
			$em->remove($application);
		}

        $em->remove($entity);
        $em->flush();
		return $this->redirect($this->generateUrl('search_team', array('game'=> $gameName )));
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
     * Displays a form to edit an existing Team entity.
     *
     * @Route("/{id}/edit", name="team_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Team')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
		
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Team $entity)
    {
        $form = $this->createForm(new TeamType($entity->getTournament()->getId(),$entity->getTournament(), $this->getUser()->getTelephone()), $entity, array(
            'action' => $this->generateUrl('player_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="team_update")
     * @Method("PUT")
     * @Template("AppBundle:Team:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Team')->find($id);
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Player p
		    WHERE p.team = :id'
		)->setParameter('id', $id);
		$players = $query->getResult();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Team entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            
			return $this->redirect($this->generateUrl('team_show', array('id' => $this->getUser()->getTeam()->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
		$entity->setOrigin('player');

        $em->persist($entity);
        $em->flush();
		return $this->redirect($this->generateUrl('search_team', array('game'=> $gameName )));
    }
	
    /**
     * Finds and displays a User entity.
     *
     * @Route("/application/{teamId}/{userId}/{origin}", name="user_recruit")
     * @Method("GET")
     */
    public function userRecruitAction($teamId,$userId,$origin)
    {
        $entity = new Application();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Team p
		    WHERE p.id = '.$teamId
		);
		$team = $query->getResult();
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.id = '.$userId
		);
		$user = $query->getResult();
		
		$gameName = $team[0]->getTournament()->getSystName();
		
		$entity->setTeam($team[0]);
		$entity->setUser($user[0]);
		$entity->setOrigin($origin);

        $em->persist($entity);
        $em->flush();
		
		if($origin=='player')
		{
			return $this->redirect($this->generateUrl('search_team', array('game'=> $gameName )));
		}
		elseif ($origin=='team') 
		{
			return $this->redirect($this->generateUrl('search_player', array('game'=> $gameName )));
		}
    }
	
    /**
     * Finds and displays a User entity.
     *
     * @Route("/delete/application/{candidats}", name="delete_application")
     * @Method("GET")
     */
    public function deleteApplicationAction($candidats)
    {
        $em = $this->getDoctrine()->getManager();
		$entity = $this->getDoctrine()->getRepository('AppBundle:Application')->find($candidats);
        $em->remove($entity);
        $em->flush();
		return $this->redirect($this->generateUrl('team_show', array('id'=> ($this->getUser()->getTeam()->getId()) )));
    }
	
    /**
     * Finds and displays a User entity.
     *
     * @Route("/validate/application/{candidats}", name="validate_application_team")
     * @Method("GET")
     */
    public function validateApplicationAction($candidats)
    {
        $em = $this->getDoctrine()->getManager();
		$entity = $this->getDoctrine()->getRepository('AppBundle:Application')->find($candidats);
		
		$userId=$entity->getUser()->getId();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.id = :id'
		)->setParameter('id', $userId);
		$user = $query->getResult();
		
		$teamId=$entity->getTeam()->getId();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Team p
		    WHERE p.id = :id'
		)->setParameter('id', $teamId);
		$team = $query->getResult();
			
		if ($entity->getRole() != null)
		{
			$roleId=$entity->getRole()->getId();
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Role p
			    WHERE p.id = :id'
			)->setParameter('id', $roleId);
			$role = $query->getResult();
		}
		
		$player = new Player;
		$player->setUser($user[0]);
		$player->setTeam($team[0]);	
		
		if ($entity->getRole() != null)
		{
			$player->setRole($role[0]);
		}
		
		$em->persist($player);
		$users=$user[0];
        $users->setTeam($team[0]);
        $users->setPlayer($player);
		
		if ($entity->getRole() != null)
		{
			$users->setRole($role[0]);
		}
		
        $this->get('fos_user.user_manager')->updateUser($users, false);
		$em->flush();
		
		return $this->redirect($this->generateUrl('team_show', array('id'=> ($this->getUser()->getTeam()->getId()) )));
    }
	
    /**
     * Finds and displays a User entity.
     *
     * @Route("/validate/{team}", name="validate_team")
     * @Method("GET")
     */
    public function validateTeamAction($team)
    {
    	
        $em = $this->getDoctrine()->getManager();
		$entity = $this->getDoctrine()->getRepository('AppBundle:Team')->find($team);
		$entity->setValidation(new \DateTime('now'));
		$em->persist($entity);
		
		$query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    where p.team= '.$team
		);
		$applications = $query->getResult();
		
		foreach ($applications as $application) 
		{
        	$em->remove($application);
		}
		
        $em->flush();
		return $this->redirect($this->generateUrl('team_show', array('id'=> ($this->getUser()->getTeam()->getId()) )));
	}
	

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/delete/application", name="delete_application_recrutement")
     * @Method("GET")
     * @Template()
     */
     public function deleteApplicationPlayerAction($id)
	 {
        $em = $this->getDoctrine()->getManager();
        $application = $em->getRepository('AppBundle:Application')->find($id);
		
		$em->remove($application);
		$em->flush();
		
		return $this->redirect($this->generateUrl('team_show', array('id' => $id)));
	 }
	
    /**
     * @Route("/quit/{playerId}", name="quit_team")
     * @Method("GET")
     */
    public function quitTeamAction($playerId)
    {
        $em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Player p
		    where p.id= '.$playerId
		);
		$player = $query->getResult();
		
		$user = $player[0]->getUser();
		$user->setPlayer(null);
		$user->setRole(null);
		$user->setTeam(null);
		$em->persist($user);
		
		$teamId = $player[0]->getTeam()->getId();
		$userId = $player[0]->getUser()->getId();
		if($player[0]->getRole())
		{
			 $roleId = $player[0]->getRole()->getId();
		}
		
		$query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    where p.user= '.$userId.
		    ' and p.team!='.$teamId
		);
		$blockedAppUser = $query->getResult();
		
		foreach ($blockedAppUser as $appliUser)
		{
			$appliUser->setBlocked(null);
			$em->persist($appliUser);
		}
		
		$query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    where p.user= '.$userId.
		    ' and p.team='.$teamId
		);
		$deleteAppUser = $query->getResult();
		
		foreach ($deleteAppUser as $delAppliUser)
		{
			$em->remove($delAppliUser);
		}
		
		$query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Player p
		    where p.team= '.$teamId
		);
		$playerTeam = $query->getResult();
		
		$phraseFilter='';
		
		foreach ($playerTeam as $teamPlayer) 
		{
			$userId=$teamPlayer->getUser()->getId();
			$phraseFilter=$phraseFilter.' and p.user!='.$userId;
		}
		
		$query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    where p.team= '.$teamId.
		    ' and p.role='.$roleId.$phraseFilter
		);
		$blockedAppTeam = $query->getResult();
		
		foreach ($blockedAppTeam as $appliTeam)
		{
			$appliTeam->setBlocked(null);
			$em->persist($appliTeam);
		}
		
		$em->remove($player[0]);
		$em->flush();

		if($this->getUser()->getCapitain() != null)
		{
			return $this->redirect($this->generateUrl('team_show', array('id' => $teamId)));
		}
		else 
		{
			return $this->redirect($this->generateUrl('search_team', array('game' => $this->getUser()->getTournament()->getSystName())));
		}
	}

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditRoleForm(User $entity)
    {
    	$gameId = $entity->getTournament()->getId();
		
        $em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Game p
		    where p.id= '.$gameId
		);
		$game = $query->getResult();
		$gameName = $game[0]->getName();
		
        $form = $this->createForm(new CaptainType(
        $gameId,  $gameName, $this->getUser()->getTelephone(),$entity, $this->container
		), $entity, array(
            'action' => $this->generateUrl('player_update_role_team', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
	
    /**
     * @Route("/edit/capitain/role/{id}", name="edit_capitain_role_team")
     * @Method("GET")
     * @Template("AppBundle:Team:editRole.html.twig")
     */
    public function editCapitainRoleTeamAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditRoleForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
	}
	
    /**
     * @Route("/player/update/role/{id}", name="player_update_role_team")
     * @Method("PUT")
     */
    public function putCaptainRoleTeamAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);
		$firstRole = $entity->getRole()->getId();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
		
        $editForm = $this->createEditRoleForm($entity);
        $editForm->handleRequest($request);
		
        if ($editForm->isValid()) {
        	$data = $editForm->getData();
			$roleId = $data->getRole()->getId();
			$teamId = $data->getTeam()->getId();
			
			$query = $em->createQuery(
			    'SELECT a
			    FROM AppBundle:Application a
			    where a.role= '.$roleId.
			    'and a.team= '.$teamId
			);
			$applicationsBlock = $query->getResult();
			
			foreach ($applicationsBlock as $application)
			{
				$application->setBlocked(true);
				$em->persist($application);
			}
			
			$query = $em->createQuery(
			    'SELECT a
			    FROM AppBundle:Application a
			    where a.role= '.$firstRole.
			    'and a.team= '.$teamId
			);
			$applicationsUnblock = $query->getResult();
			
			foreach ($applicationsUnblock as $application)
			{
				$application->setBlocked(null);
				$em->persist($application);
			}

            $em->flush();
			return $this->redirect($this->generateUrl('team_show', array('id' => $this->getUser()->getTeam()->getId())));
        }
	}
}