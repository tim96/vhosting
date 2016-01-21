<?php

namespace SymfonyBestPracticesTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/best")
 *
 * Class DefaultController
 * @package SymfonyBestPracticesTestBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="best_practices_home")
     *
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($name = null)
    {
        return $this->render('SymfonyBestPracticesTestBundle:Default:index.html.twig', array('name' => $name));
    }
}
