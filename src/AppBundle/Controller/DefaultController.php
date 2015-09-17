<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
      $response = $this->forward('AppBundle:Blog:recentArticles');
        return $response;
    }

    /**
     * @Route("/lol", name="lol")
     */
    public function lolAction(Request $request)
    {
      return $this->render('default/lol.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
      ));
    }

    /**
     * @Route("/csgo", name="csgo")
     */
    public function csgoAction(Request $request)
    {
      return $this->render('default/csgo.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
      ));
    }

    /**
    * @Route("/blog", name="blog")
    */
    public function blogAction(Request $request)
    {
      $twig->addExtension(new Twig_Extensions_Extension_Text());
      return $this->redirect($this->generateUrl('post_new'));
    }

}
