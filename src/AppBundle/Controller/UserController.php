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
use AppBundle\Form\RegistrationType;
use AppBundle\Form\SearchType;
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
		
        return array(
            'entities' => $users,
            'game' => $games,
            'form'   => $form->createView()
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
		var_dump('out');exit;

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
		    and p.origin = \'player\''
		)->setParameter('id', $id);
		$applications = $query->getResult();
		
		
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
            'applications' => $applications
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
			if(($user->getManager())&&(($user->getExperience())==null))
			{
				$experience = new Experience;
				$user->setExperience($experience);
				$user->getExperience()->setUsername($user->getUsername());
	        	$this->get('fos_user.user_manager')->updateUser($user, false);
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
}
