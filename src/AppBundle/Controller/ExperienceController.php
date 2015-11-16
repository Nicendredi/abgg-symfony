<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Experience;
use AppBundle\Form\ExperienceType;

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

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Experience entity.
     *
     * @Route("/", name="experience_create")
     * @Method("POST")
     * @Template("AppBundle:Experience:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Experience();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('experience_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Experience entity.
     *
     * @param Experience $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Experience $entity)
    {
        $form = $this->createForm(new ExperienceType(), $entity, array(
            'action' => $this->generateUrl('experience_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Experience entity.
     *
     * @Route("/new", name="experience_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Experience();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
        $form = $this->createCreateFormUser($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();
            $user->setExperience($entity);
            $this->get('fos_user.user_manager')->updateUser($user, false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Experience entity.
     *
     * @param Experience $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateFormUser(Experience $entity)
    {
        $form = $this->createForm(new ExperienceType(), $entity, array(
            'action' => $this->generateUrl('experience_create_user'),
            'method' => 'POST',
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
        $form   = $this->createCreateFormUser($entity);

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
        $form = $this->createForm(new ExperienceType(), $entity, array(
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

            return $this->redirect($this->generateUrl('experience_edit', array('id' => $id)));
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
