<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image controller.
 *
 * @Route("/image")
 */
class ImageController extends Controller
{

    /**
     * Creates a new Image entity.
     *
     * @Route("/{origin}", name="image_create")
     * @Method("POST")
     * @Template("AppBundle:Image:new.html.twig")
     */
    public function createAction(Request $request, $origin)
    {
        $entity = new Image();
        $form = $this->createCreateForm($entity, $origin);
        $form->handleRequest($request);

        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
			
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $entity->getFile();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
			
            $imageDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/images';
            $file->move($imageDir, $fileName);

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $entity->setFile($fileName);
			$entity->setName($fileName);
			
            $em->persist($entity);
            $em->flush();
			
			if($origin=='player')
			{
				$user=$this->getUser();
				$user->setImage($entity);
	        	$this->get('fos_user.user_manager')->updateUser($user, false);
	            $em->flush();
				
				return $this->redirect($this->generateUrl('player_show', array('id' => $this->getUser()->getId())));
			}
			else
			{
				$team=$this->getUser()->getTeam();
				$team->setImage($entity);
	            $em->persist($team);
	            $em->flush();
				return $this->redirect($this->generateUrl('team_show', array('id' => $this->getUser()->getTeam()->getId())));
			}
        }
		
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Image entity.
     *
     * @param Image $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Image $entity, $origin)
    {
        $form = $this->createForm(new ImageType(), $entity, array(
            'action' => $this->generateUrl('image_create',array('origin'=>$origin)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer Image'));

        return $form;
    }

    /**
     * Displays a form to create a new Image entity.
     *
     * @Route("/new/{origin}", name="image_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($origin)
    {
        $entity = new Image();
        $form   = $this->createCreateForm($entity, $origin);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Image entity.
     *
     * @Route("/{id}", name="image_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity
        );
    }

    /**
     * Displays a form to edit an existing Image entity.
     *
     * @Route("/{id}/edit", name="image_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
    * Creates a form to edit a Image entity.
    *
    * @param Image $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Image $entity)
    {
        $form = $this->createForm(new ImageType(), $entity, array(
            'action' => $this->generateUrl('image_update', array('id' => $entity->id)),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Image entity.
     *
     * @Route("/{id}", name="image_update")
     * @Method("PUT")
     * @Template("AppBundle:Image:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
			
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $entity->getFile();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
			
            $imageDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/images';
            $file->move($imageDir, $fileName);

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $entity->setFile($fileName);
			$entity->setName($fileName);
			
            $em->persist($entity);
            $em->flush();
		
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:User p
			    where p.image= '.$entity->id
			);
			$users = $query->getResult();
		
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Team p
			    where p.image= '.$entity->id
			);
			$teams = $query->getResult();
			
			if(empty($users)==false)
			{
				$user=$this->getUser();
				$user->setImage($entity);
	        	$this->get('fos_user.user_manager')->updateUser($user, false);
            	$em->flush();
				
				return $this->redirect($this->generateUrl('profil'));
			}
			elseif(empty($teams)==false)
			{
				$team=$this->getUser()->getTeam();
				$team->setImage($entity);
	            $em->persist($team);
            	$em->flush();
				
				return $this->redirect($this->generateUrl('team_show', array('id' => $team->getId())));
			}
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }
	
    /**
     * Deletes a Image entity.
     *
     * @Route("/del/{id}/{originId}/{origin}", name="image_delete")
     * @Method("GET")
     */
    public function deleteAction($id, $originId, $origin)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Image')->find($id);
		
		if($origin=='user')
		{
			$from = $em->getRepository('AppBundle:User')->find($originId);
		}
		elseif($origin=='team')
		{
			$from = $em->getRepository('AppBundle:Team')->find($originId);
		}
		
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }
		
		$from->setImage(null);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('profil'));
    }

    /**
     * Creates a form to delete a Image entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('image_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
