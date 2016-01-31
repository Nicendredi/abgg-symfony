<?php
namespace AppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Experience;
use AppBundle\Entity\Game;
use AppBundle\Form\ExperienceType;
use AppBundle\Services\RegistrationCompleteEvent;
use AppBundle\Services\BgfesEvents;

/**
 * Experience controller.
 *
 * @Route("/experience")
 */
class ExperienceController extends Controller
{
    /**
     * Lists all Experience entities.
     *
     * @Route("/", name="experience")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Experience')->findAll();
		return $this->redirect($this->generateUrl('player_show', array('id' => $this->getUser()->getId())));
    }
	
    /**
     * Creates a new Experience entity.
     *
     * @Route("/user", name="experience_create_user")
     * @Method("POST")
     * @Template("AppBundle:Experience:newUser.html.twig")
     */
    public function createUserAction(Request $request)
    {
        $entity = new Experience();
        $form = $this->createFormExperience($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $this->getUser();
            $user->setExperience($entity);
            $this->get('fos_user.user_manager')->updateUser($user, false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
			
            $em->flush();

            $event = new RegistrationCompleteEvent($this->getUser());

            $this->get('event_dispatcher')
            ->dispatch(BgfesEvents::onRegistrationComplete, $event)
            ;

            return $this->redirect($this->generateUrl('profil'));
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }
    /**
     * Creates a form to create a Experience entity.
     *
     * @param Experience $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createFormExperience(Experience $entity)
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
		
        $form = $this->createForm(new ExperienceType($gameId, $gameName), $entity, array(
            'action' => $this->generateUrl('experience_create_user'),
            'method' => 'POST'
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }
    /**
     * Displays a form to create a new Experience entity and sets it to the
     * current User.
     *
     * @Route("/new/user", name="experience_new_user")
     * @Method("GET")
     * @Template()
     */
    public function newUserAction()
    {
        $entity = new Experience();
        $form   = $this->createFormExperience($entity);
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Finds and displays a Experience entity.
     *
     * @Route("/{id}", name="experience_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Experience')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Experience entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Displays a form to edit an existing Experience entity.
     *
     * @Route("/{id}/edit", name="experience_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Experience')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Experience entity.');
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
    * Creates a form to edit a Experience entity.
    *
    * @param Experience $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Experience $entity)
    {
    	$gameId = $this->getUser()->getTournament()->getId();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:Game p
		    WHERE p.id = :id'
		)->setParameter('id', $gameId);
		$game = $query->getResult();

        $form = $this->createForm(new ExperienceType($gameId,($game[0]->getName())), $entity, array(
            'action' => $this->generateUrl('experience_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }
    /**
     * Edits an existing Experience entity.
     *
     * @Route("/{id}", name="experience_update")
     * @Method("PUT")
     * @Template("AppBundle:Experience:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Experience')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Experience entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
			$userId=$this->getUser()->getId();
            return $this->redirect($this->generateUrl('player_show', array('id' => $userId)));
        }
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Experience entity.
     *
     * @Route("/{id}", name="experience_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Experience')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Experience entity.');
            }
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('experience'));
    }
    /**
     * Creates a form to delete a Experience entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('experience_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}