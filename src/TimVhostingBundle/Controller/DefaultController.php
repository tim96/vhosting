<?php

namespace TimVhostingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TimVhostingBundle\Entity\Feedback;
use TimVhostingBundle\Form\FeedbackType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     * @Template()
     */
    public function contactAction(Request $request)
    {
        $feedback = new Feedback();
        $form = $this->createForm(new FeedbackType(), $feedback);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // todo: add form save
        }

        return array(
            'form' => $form->createView()
        );
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
