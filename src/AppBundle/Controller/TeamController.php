<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Team;
use AppBundle\Entity\Game;
use AppBundle\Form\TeamType;

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
     * @Route("/search/{needs_posts}", name="search_team")
     * @Method("GET")
     * @Template()
     */
    public function searchAction($needs_posts)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Team')->findAll();

        return array(
            'entities' => $entities,
            'needs_posts' => $needs_posts,
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
        if ($form->isValid()) {
            $user = $this->getUser();
	    	$game = $this->getUser()->getTournament();
			$entity->setTournament($game);
			$entity->setCaptain($user);
            $user->setTeam($entity);
            $this->get('fos_user.user_manager')->updateUser($user, false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
			
			$id = $game->getId();
	        $em = $this->getDoctrine()->getManager();
	        $gameId = $em->getRepository('AppBundle:Game')->find($id);
			if ($game == $gameId)
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
        $form = $this->createForm(new TeamType($gameId), $entity, array(
            'action' => $this->generateUrl('team_create'),
            'method' => 'POST'
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
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
}