<?php

namespace TimVhostingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    /**
     * @Route("/about", name="About")
     */
    public function aboutAction()
    {
        return $this->render('TimVhostingBundle:Default:about.html.twig', array());
    }

    /**
     * @Route("/contribute", name="Contribute")
     */
    public function contributeAction()
    {
        return $this->render('TimVhostingBundle:Default:contribute.html.twig', array());
    }

    /**
     * @Route("/contact", name="Contact")
     */
    public function contactAction()
    {
        return $this->render('TimVhostingBundle:Default:contact.html.twig', array());
    }

    /**
     * @Route("/changeLanguage/{name}", name="ChangeLanguage")
     */
    public function changeLanguageAction($name, Request $request)
    {
        $locale = substr($name, 0, 2);
        $request->getSession()->set('_locale', $locale);
        $request->setLocale($locale);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
