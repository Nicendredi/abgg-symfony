<?php
namespace AppBundle\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Game;
use AppBundle\Entity\Experience;
use AppBundle\Services\invitationServices;
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
        dump($this->container->get('session')->getId());
        
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
		
        $invitation = $this->container->get('session')->get('invitation');
        if($invitation){
            $invit = $this->container->get('app.invitation_services');
            $invit->closeInvitation($user, $invitation);
        }
        $invitation = $this->container->get('session')->remove('invitation');
		$manager = $user->getManager();
		
		if($manager==null)
		{
			$response = $this->forward('AppBundle:Experience:newUser');
		}
		else 
		{
			$experience = new Experience;
			$user->setExperience($experience);
			$user->getExperience()->setUsername($user->getUsername());
        	$this->get('fos_user.user_manager')->updateUser($user, false);
        	$em->flush();
			$response = $this->forward('AppBundle:Default:profil');
		}
        
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