<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Team;
use AppBundle\Entity\TeamRepository;
use AppBundle\Entity\Game;
use AppBundle\Entity\Validation;
use AppBundle\Services\EmailRegisterProcessor;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$game = $em -> getRepository('AppBundle:Game')->findByName('League of Legends');
		$gameId = $game[0]->getId();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.tournament = :id
		    AND p.team is null
		    AND p.player is null'
		)->setParameter('id', $gameId);
		$users = $query->getResult();
		
      return $this->render('default/index.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          'searchUsers' => count($users),
      ));
    }

    /**
     * @Route("/lol", name="lol")
     */
    public function lolAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Team')->findAll(); 
		$game = $em -> getRepository('AppBundle:Game')->findByName('League of Legends');
		$gameId = $game[0]->getId();
		$listEntities=array();
		$i=0;

		foreach ($entities as $entity)
		{
			$test = $entity->getTournament()->getId();
			if($test == $gameId)
			{
				$listEntities[$i] =$entity;
				$i++;
			}
		}
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.tournament = :id
		    AND p.team is null
		    AND p.player is null'
		)->setParameter('id', $gameId);
		$users = $query->getResult();
		
		if(($this->getUser()) && ($this->getUser()->getTournament()))
		{
	        $em = $this->getDoctrine()->getManager();
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Game p
			    WHERE p.name = :name'
			)->setParameter('name', $this->getUser()->getTournament()->getName());
			$checkGame = $query->getResult();
			$checking=$checkGame[0]->getName();
		}
		else
		{
			$checking=0;
		}
		
      return $this->render('default/lol.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          'entities' => $listEntities,
          'game'     => $game,
          'games'   => $game[0],
          'searchUsers' => count($users),
          'checkGame' => $checking
      ));
    }

    /**
     * @Route("/csgo", name="csgo")
     */
    public function csgoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Team')->findAll(); 
		$game = $em -> getRepository('AppBundle:Game')->findByName('Counter Strike : Global Offensive');
		$gameId = $game[0]->getId();
		$listEntities=array();
		$i=0;
		
		foreach ($entities as $entity)
		{
			$test = $entity->getTournament()->getId();
			if($test == $gameId)
			{
				$listEntities[$i] =$entity;
				$i++;
			}
		}
		
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
		    'SELECT p
		    FROM AppBundle:User p
		    WHERE p.tournament = :id
		    AND p.team is null
		    AND p.player is null'
		)->setParameter('id', $gameId);
		$users = $query->getResult();
		
		
		if(($this->getUser()) && ($this->getUser()->getTournament()))
		{
	        $em = $this->getDoctrine()->getManager();
	        $query = $em->createQuery(
			    'SELECT p
			    FROM AppBundle:Game p
			    WHERE p.name = :name'
			)->setParameter('name', $this->getUser()->getTournament()->getName());
			$checkGame = $query->getResult();
			$checking=$checkGame[0]->getName();
		}
		else
		{
			$checking=0;
		}
		
      return $this->render('default/csgo.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          'entities' => $listEntities,
          'game'     => $game,
          'games'   => $game[0],
          'searchUsers' => count($users),
          'checkGame' => $checking
      ));
    }

    /**
    * @Route("/blog", name="blog")
    */
    public function blogAction(Request $request)
    {
      return $this->redirect($this->generateUrl('post_new'));
    }

    /**
    * @Route("/admin", name="admin")
    */
    public function adminAction(Request $request)
    {
      return $this->render('default/admin.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
      ));
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profilAction(Request $request)
    {
    	$user=$this->getUser();
	    $response = $this->forward('AppBundle:User:show', array(
	        'user'  => $user
	        
	    ));
	
	    return $response;
    }

    /**
     * @Route("/equipe", name="equipe")
     */
    public function equipeAction(Request $request)
    {
    	$user=$this->getUser();
	    $response = $this->forward('AppBundle:Team:show', array(
	        'user'  => $user
	        
	    ));
	
	    return $response;
    }

    /**
    * @Route("/invite/{id}", name="invite")
    */ 
    public function inviteAction(Request $request, $id)
    {

      $this->container->get('session')->set('invitation', $id);

      $response = $this->forward('FOSUserBundle:Registration:register', array(
        'invitation' => $id));

      return $response;
    }

    /**
     * @Route("/search/player", name="search_player")
     */
    public function searchPlayerAction($game)
    {
	    $response = $this->forward('AppBundle:User:search_player', array(
	        'game'  => $game
	        
	    ));
	
	    return $response;
    }

    /**
     * @Route("/search/team", name="search_team")
     */
    public function searchTeamAction($game)
    {
	    $response = $this->forward('AppBundle:Team:search_team', array(
	        'game'  => $game
	        
	    ));
	
	    return $response;
    }

    /**
    * @Route("/faq", name="faq")
    */
    public function faqAction()
    {
      return $this->render('AppBundle:FAQ:faq.html.twig');
    }

    /**
     * @Route("/mail", name="mail")
     * @Method("GET")
     * @Template("AppBundle:FAQ:mailAdmin.html.twig")
     */
    public function mailAdminAction(Request $request)
    {
    	$formArray = array(
	    		0=>array('tournament', 'choice', $array = array(
	            	'label'    => 'A quel propos voulez vous envoyer ce mail?',
				    'choices'  => array(
				    	'lol' => 'League of Legends', 
				    	'csgo' => 'Counter Strike Global Offensive',
						'else' => 'Autre'),
				    'required' => true,
				    'expanded' => true,
				    'multiple' => false
					)),
				1=>array(
					'name'=>'email',
					'type'=>'email',
					'array' => $array = array('label'=>'Votre adresse mail')
				),
				2=>array(
					'name'=>'object',
					'type'=>'text'
				),
				3=>array(
					'name'=>'mail',
					'type'=>'textarea'
				),
				4=> array(
		            'name' => 'save', 
		            'type' =>'submit',
		            'array' => $array = array(
		            	'label' => 'Envoyer'
					    )
		            ),
		        );
    				
	    $formBuilder = $this->createFormBuilder($formArray)
	    	->add('tournament', 'choice', $array = array(
            	'label'    => 'A quel propos voulez vous envoyer ce mail?',
			    'choices'  => array(
			    	'lol'  => 'League of Legends', 
			    	'csgo' => 'Counter Strike Global Offensive',
					'else' => 'Autre'),
			    'required' => true,
			    'expanded' => true,
			    'multiple' => false
			))
			->add('email','email',array('label'=>'Votre adresse mail'))
			->add('object','text')
			->add('mail','textarea')
			->add('save','submit',array(
            	'label'  => 'Envoyer'
			));
			
	    $form = $formBuilder->getForm();
        $form->handleRequest($request);
			
      return $this->render('AppBundle:FAQ:mailAdmin.html.twig', array(
            'form'     => $form->createView(),));
    }

    /**
     * @Route("/mail", name="mail_post")
     * @Method("POST")
     * @Template("AppBundle:FAQ:mailAdmin.html.twig")
     */
    public function mailAdminPostAction(Request $request)
    {
    				
	    $formBuilder = $this->createFormBuilder()
	    	->add('tournament', 'choice', $array = array(
            	'label'    => 'A quel propos voulez vous envoyer ce mail?',
			    'choices'  => array(
			    	'lol'  => 'League of Legends', 
			    	'csgo' => 'Counter Strike Global Offensive',
					'else' => 'Autre'),
			    'required' => true,
			    'expanded' => true,
			    'multiple' => false
			))
			->add('email','email',array('label'=>'Votre adresse mail'))
			->add('object','text')
			->add('mail','textarea')
			->add('save','submit',array(
            	'label'  => 'Envoyer'
			));
			
	    $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
        	$form=$form->getData();
			$tournament = $form['tournament'];
			$email = $form['email'];
			$object = $form['object'];
			$mail = $form['mail'];
			
			if($tournament=='lol')
			{
				$responsable = 'liard.au@gmail.com';
			}
			elseif($tournament=='csgo')
			{
				$responsable = 'sch.laurine@gmail.com';
			}
			else 
			{
				$responsable[0]='liard.au@gmail.com';
				$responsable[1]='sch.laurine@gmail.com';
			}

        	$em = $this->getDoctrine()->getManager();
			$mailer = $this->container->get('app.registration_email_processor');
			$sendMail = $mailer -> sendMail($responsable,$email,$object,$mail);
			
      		return $this->redirect($this->generateUrl('mail'));
		}
			
      return $this->render('AppBundle:FAQ:mailAdmin.html.twig', array(
            'form'     => $form->createView(),));
    }

    /**
     * @Route("/auth_parentale", name="auth_parentale")
     */
	public function authParentaleAction()
	{
		$fichier = "BGFES Authorisation Parentale.pdf";
	    $chemin = "bundles/app/documents/"; // emplacement de votre fichier .pdf
	         
	    $response = new Response();
	    $response->setContent(file_get_contents($chemin.$fichier));
	    $response->headers->set('Content-Type', 'application/force-download'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
	    $response->headers->set('Content-disposition', 'filename='. $fichier);
	         
	    return $response;
	}

    /**
     * @Route("/droit_image", name="droit_image")
     */
	public function droitImageAction()
	{
		$fichier = "BGFES Autorisation utilisation image.pdf";
	    $chemin = "bundles/app/documents/"; // emplacement de votre fichier .pdf
	         
	    $response = new Response();
	    $response->setContent(file_get_contents($chemin.$fichier));
	    $response->headers->set('Content-Type', 'application/force-download'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
	    $response->headers->set('Content-disposition', 'filename='. $fichier);
	         
	    return $response;
	}

    /**
     * @Route("/droit_image_mineur", name="droit_image_mineur")
     */
	public function droitImageMineurAction()
	{
		$fichier = "BGFES Autorisation droit image mineurs.pdf";
	    $chemin = "bundles/app/documents/"; // emplacement de votre fichier .pdf
	         
	    $response = new Response();
	    $response->setContent(file_get_contents($chemin.$fichier));
	    $response->headers->set('Content-Type', 'application/force-download'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
	    $response->headers->set('Content-disposition', 'filename='. $fichier);
	         
	    return $response;
	}
}
