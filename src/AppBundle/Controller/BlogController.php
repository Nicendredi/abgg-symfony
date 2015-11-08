<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Blog;
use AppBundle\Form\BlogType;

/**
* Blog controller
*
* @Route("/blog")
*/
class BlogController extends Controller
{
    /**
     * @Route("/new", name="post_new")
     * @Template("AppBundle:Blog:newArticle.html.twig")
     */
    public function newArticleAction(Request $request){
        $entity = new Blog();
        $form = $this->createForm(new BlogType(), $entity);

        $form->handleRequest($request);
        $entity->setDate(date("j-m-Y"));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('post_show', array('id' => $entity->getId())));
        }


        return array(
      'form'   => $form->createView(),);
    }

    /**
    * Create a new Blog Post
    *
    * @Route("/", name="post_create")
    * @Method("POST")
    * @Template("AppBundle:Blog:newArticle.html.twig")
    */
    public function writeAction(BlogType $form){

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('post_show', array('id' => $entity->getId())));
        }

    /**
     * Finds and displays a Blog article.
     *
     * @Route("/article/{id}", name="post_show")
     * @Method("GET")
     * @Template("AppBundle:Blog:showArticle.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Blog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog entity.');
        }



        return $this->render(
            'AppBundle:blog:showArticle.html.twig', array('article'=>$entity));
    }

    public function recentArticlesAction(){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT MAX(b.id)
            FROM AppBundle:Blog b
            WHERE b.id > 0'
        );
        $max = $query->setMaxResults(1)->getOneOrNullResult();
        $max = $max[1];
        $ids = array($max);

        $entity = $em->getRepository('AppBundle:Blog')->find($max);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog entity.');
        }

        $articles = array($entity);

        for($i = 1; $i <=2; $i++){
            $entity = $em->getRepository('AppBundle:Blog')->find($max-$i);
            array_push($articles,$entity);
            array_push($ids, ($max-$i));
        }

        return $this->render(
            'default/index.html.twig', array('articles'=> $articles, 'ids'=> $ids));
    }
}
