<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Game;

/**
 * Game controller.
 *
 * @Route("/game")
 */
class GameController extends Controller
{

    /**
     * Lists all Game entities.
     *
     * @Route("/", name="game")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Game')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a choice between the games.
     *
     * @Route("/choose", name="game_choose")
     * @Method("GET")
     * @Template()
     */
    public function chooseAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Game')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Game entity.
     *
     * @Route("/selected/{id}", name="game_selected")
     * @Method("GET")
     * @Template()
     */
    public function selectedAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Game')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        $user = $this->getUser();
        $user->setTournament($entity);
        $this->get('fos_user.user_manager')->updateUser($user, false);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $response = $this->forward('AppBundle:Experience:newUser');
        return $response;
    }

    /**
     * Finds and displays a Game entity.
     *
     * @Route("/{id}", name="game_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Game')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
