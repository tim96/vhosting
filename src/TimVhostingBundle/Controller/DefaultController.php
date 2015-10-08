<?php

namespace TimVhostingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/name/{name}", name="Name")
     */
    public function indexAction($name)
    {
        return $this->render('TimVhostingBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * @Route("/", name="Home")
     */
    public function frontendAction()
    {
        return $this->render('TimVhostingBundle:Default:frontend.html.twig', array());
    }
}
