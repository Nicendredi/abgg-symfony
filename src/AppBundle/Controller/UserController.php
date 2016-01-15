<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\User;
use AppBundle\Entity\Role;
use AppBundle\Entity\Experience;
use AppBundle\Entity\Player;
use AppBundle\Entity\Application;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\SearchType;
use AppBundle\Form\ImageType;
use AppBundle\Form\TextType;
use AppBundle\Services\CheckDataServices;
use AppBundle\Services\SearchFormService;

/**
 * User controller.
 *
 * @Route("/player")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/", name="player")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:User')->findAll();

        return array(
            'entities' => $entities,
        );
    }
	
    /**
     * Lists all User entities but only the information avaible to everyone.
     *
     * @Route("/search/{game}", name="search_player")
     * @Method("GET")
     * @Template("AppBundle:User:search.html.twig")
     */
    public function searchAction(Request $request)
    {
    	$requestURL = $this->getRequest()->getRequestUri();
		$exploded = explode("/",$requestURL);

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Game p
		    WHERE p.systName = :name'
		)->setParameter('name', $exploded[6]);
		$gaming = $query->getResult();
		$gameId = $gaming[0]->getId();
		$games = $gaming[0]->getSystName();
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.tournament = :id'
		)->setParameter('id', $gameId);
		$users = $query->getResult();
		
		$function='createForm'.$games;
		$formArray = $this->get('searchFormService')->$function();
		
	    $formBuilder = $this->createFormBuilder($formArray);
	
	    foreach($formArray as $field) {
	        $formBuilder->add($field['name'], $field['type'],$field['array']);
	    }        
	
	    $form = $formBuilder->getForm();
	    
	    if (($this->getUser())&&($this->getUser()->getTeam()) != null)
		{
			$teamId = $this->getUser()->getTeam()->getId();
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Application p
			    WHERE p.team = :id
			    and p.origin=\'team\''
			)->setParameter('id', $teamId);
			$userApp = $query->getResult();
			
			$i=0;
			if ($userApp!=null)
			{
				foreach ($userApp as $user)
				{
					$userAppTeams[$i] = $user->getUser();
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
            'entities' => $users,
            'game'     => $games,
            'form'     => $form->createView(),
            'appTeam'  => $userAppTeams,
        );
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/search/{game}", name="search_player_by")
     * @Method("POST")
     * @Template("AppBundle:User:search.html.twig")
     */
    public function createSearchAction(Request $request)
    {
    	$requestURL = $this->getRequest()->getRequestUri();
		$exploded = explode("/",$requestURL);
		$games = $exploded[6];
		
		$function='createForm'.$exploded[6];
		$formArray = $this->get('searchFormService')->$function();
		
	    $formBuilder = $this->createFormBuilder($formArray);
	
	    foreach($formArray as $field) {
	        $formBuilder->add($field['name'], $field['type'],$field['array']);
	    }       
		
	    $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {

			$tradDataSearch = $this->get('traductionDataSearchService')->getTraductionData($form,$games);
			
			if($tradDataSearch=='error')
			{
				return $this->render("AppBundle:User:error.html.twig");
			}


			$function='createForm'.$games;
			$formArray = $this->get('searchFormService')->$function();
			
		    $formBuilder = $this->createFormBuilder($formArray);

		    foreach($formArray as $field) {
		        $formBuilder->add($field['name'], $field['type'],$field['array']);
		    }        

		    $form = $formBuilder->getForm();
			
	        return array(
	            'entities' => $tradDataSearch,
	            'game' => $games,
	            'form'   => $form->createView()
	        );
        }
		
		$data= $request->request->all();
		$forms = $data['form'];

		if($forms['role'])
		{
			$forms =$data['form'];
			$teamId = $forms['teamId'];
			$userId = $forms['userId'];
			$form = $forms['role'];
			$text = $forms['text'];
			
	        $em = $this->getDoctrine()->getManager();
	        $query = $em->createQuery(
			    'SELECT t
			    FROM AppBundle:Team t
			    WHERE t.id = :id'
			)->setParameter('id', $teamId);
			$team = $query->getResult();
			
	        $em = $this->getDoctrine()->getManager();
	        $query = $em->createQuery(
			    'SELECT t
			    FROM AppBundle:User t
			    WHERE t.id = :id'
			)->setParameter('id', $userId);
			$user = $query->getResult();
			
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
				$entity->setUser($user[0]);
				$entity->setTeam($team[0]);
				$entity->setRole($roleArray[0]);
				$entity->setOrigin('team');
				$entity->setText($text);
		        $em->persist($entity);
			}
		    $em->flush();
			
	    	$requestURL = $this->getRequest()->getRequestUri();
			$exploded = explode("/",$requestURL);
			
			return $this->redirect($this->generateUrl('search_player', array('game'=> $exploded[6] )));
		}

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/", name="player_create")
     * @Method("POST")
     * @Template("AppBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('player_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('player_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="player_show")
     * @Method("GET")
     * @Template(":default:profil.html.twig")
     */
    public function showAction()
    {
    	$id = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
		$checkData = $this -> container -> get('checkDataServices');
		
		$experience = $checkData -> checkData($user, 'getExperience', 'Experience');
		if ($experience != null)
		{
			$ranking = $checkData -> checkData($experience, 'getRanking', 'Ranking');
			$underRanking = $checkData -> checkData($experience, 'getUnderRanking', 'UnderRanking');
		}
		else {
			$ranking = 0;
			$underRanking = 0;
		}
		$game = $checkData -> checkData($user, 'getTournament', 'Game');
		$role = $checkData -> checkData($user, 'getRole', 'Role');
		$team = $checkData -> checkData($user, 'getTeam', 'Team');
		
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.user = :id
		    and p.origin = \'player\'
		    and p.blocked is null'
		)->setParameter('id', $id);
		$applications = $query->getResult();
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    WHERE p.user = :id
		    and p.origin = \'team\'
		    and p.blocked is null'
		)->setParameter('id', $id);
		$propositions = $query->getResult();
		
		
        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
		
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'user'         => $user,
            'experience'   => $experience,
            'ranking'      => $ranking,
            'underRanking' => $underRanking,
            'game'         => $game,
            'role'         => $role,
            'team'         => $team,
            'delete_form'  => $deleteForm->createView(),
            'applications' => $applications,
            'propositions' => $propositions
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="player_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

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
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new RegistrationType(), $entity, array(
            'action' => $this->generateUrl('player_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
	
    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="player_update")
     * @Method("PUT")
     * @Template("AppBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $user = $this->getUser();
			
			if((($user->getExperience()->getRanking())!=null)&&($user->getManager()!=null))
			{
				$firstExperience = $user->getExperience();
				$em->remove($firstExperience);
				$experience = new Experience;
				$user->setExperience($experience);
				$user->getExperience()->setUsername($user->getUsername());
	        	$this->get('fos_user.user_manager')->updateUser($user, false);
            	$em->persist($experience);
			}
			elseif((($user->getExperience()->getRanking())==null)&&($user->getManager()==null))
			{
				
	        	$this->get('fos_user.user_manager')->updateUser($user, false);
	            $em = $this->getDoctrine()->getManager();
	            $em->persist($user);
	            $em->flush();
				return $this->forward('AppBundle:Experience:newUser');
			}
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            
			return $this->redirect($this->generateUrl('player_show', array('id' => $this->getUser()->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="player_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('player_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
            	'label' => 'Supprimer votre profil/compte'))
            ->getForm()
        ;
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/delete/application", name="delete_application_player")
     * @Method("GET")
     * @Template()
     */
     public function deleteApplicationPlayerAction($id)
	 {
        $em = $this->getDoctrine()->getManager();
        $application = $em->getRepository('AppBundle:Application')->find($id);
		
		$em->remove($application);
		$em->flush();
		
		return $this->redirect($this->generateUrl('player_show', array('id' => $this->getUser()->getId())));
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
		return $this->redirect($this->generateUrl('player_show', array('id' => $this->getUser()->getId())));
    }
	
    /**
     * Finds and displays a User entity.
     *
     * @Route("/validate/application/{proposition}", name="validate_application")
     * @Method("GET")
     */
    public function validateApplicationAction($proposition)
    {
        $em = $this->getDoctrine()->getManager();
		$entity = $this->getDoctrine()->getRepository('AppBundle:Application')->find($proposition);
		
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
		

	    if($entity->getRole() != null)
	    {
	    	$phrase=' or p.team='.$teamId.' and p.role = '.$roleId ;
		}
		else
		{
			$phrase='';
		}
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Application p
		    where p.user= '.$userId.$phrase 
		);
		$applications = $query->getResult();
		
		foreach ($applications as $application) 
		{
			$application->setBlocked(true);
        	$em->persist($application);
		}
		
        $em->flush();
		return $this->redirect($this->generateUrl('player_show', array('id' => $this->getUser()->getId())));
    }
	
    /**
     * @Route("/edit/text/application/{id}", name="edit_text_application")
     * @Method("GET")
     * @Template("AppBundle:User:editText.html.twig")
     */
    public function editTextApplicationAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Application')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        $editForm = $this->createEditTextForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
	}

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditTextForm(Application $entity)
    {
        $form = $this->createForm(new TextType(), $entity, array(
            'action' => $this->generateUrl('update_text_application', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
	
    /**
     * @Route("/application/update/text/{id}", name="update_text_application")
     * @Method("PUT")
     */
    public function putTextApplicationAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Application')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
		
        $editForm = $this->createEditTextForm($entity);
        $editForm->handleRequest($request);
		
        if ($editForm->isValid()) {
        	$data = $editForm->getData();
			$text = $data->getText();
			
			$entity->setText($text);
			$em->persist($entity);

            $em->flush();
            return $this->redirect($this->generateUrl('player_show', array('id' => $entity->getId())));
        }
	}

    /**
     * Finds and displays a User entity.
     *
     * @Route("/user/{id}", name="user_show")
     * @Method("GET")
     * @Template("AppBundle:User:show.html.twig")
     */
    public function showUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
		
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    where p.id= '.$id
		);
		$users = $query->getResult();
		$user=$users[0];
		
		if($users[0]->getTournament())
		{
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Game p
			    where p.id= '.$users[0]->getTournament()->getId()
			);
			$games = $query->getResult();
			$game=$games[0];
		}else{$game=null;}
		
		if($users[0]->getExperience())
		{
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Experience p
			    where p.id= '.$users[0]->getExperience()->getId()
			);
			$experiences = $query->getResult();
			$experience=$experiences[0];
		
			if($experiences[0]->getRanking())
			{
		        $query = $em->createQuery(
				    'SELECT p
				    FROM AppBundle:Ranking p
				    where p.id= '.$experiences[0]->getRanking()->getId()
				);
				$rankings = $query->getResult();
				$ranking=$rankings[0];
			}else{$ranking=null;}
			
			if($experiences[0]->getUnderRanking())
			{
		        $query = $em->createQuery(
				    'SELECT p
				    FROM AppBundle:UnderRanking p
				    where p.id= '.$experiences[0]->getUnderRanking()->getId()
				);
				$underRankings = $query->getResult();
				$underRanking=$underRankings[0];
			}else{$underRanking=null;}
		}else{$experience=null;}
		
		if($users[0]->getTeam())
		{
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Team p
			    where p.id= '.$users[0]->getTeam()->getId()
			);
			$teams = $query->getResult();
			$team=$teams[0];
		}else{$team=null;}
			
		if($users[0]->getRole())
		{
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Role p
			    where p.id= '.$users[0]->getRole()->getId()
			);
			$roles = $query->getResult();
			$role=$roles[0];
		}else{$role=null;}

        return array(
            'user'         => $user,
            'game'         => $game,
            'experience'   => $experience,
            'ranking'      => $ranking,
            'underRanking' => $underRanking,
            'team'         => $team,
            'role'         => $role,
        );
    }
}
