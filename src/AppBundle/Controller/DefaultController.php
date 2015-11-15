<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
      return $this->render('default/index.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
      ));
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

}
