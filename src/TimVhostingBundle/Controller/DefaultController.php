<?php

namespace TimVhostingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TimVhostingBundle:Default:index.html.twig', array('name' => $name));
    }
}
