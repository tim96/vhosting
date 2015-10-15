<?php

namespace TimVhostingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TimVhostingBundle\Entity\Feedback;
use TimVhostingBundle\Entity\VideoSuggest;
use TimVhostingBundle\Form\FeedbackType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TimVhostingBundle\Form\VideoSuggestType;

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
     * @Template()
     * @param Request $request
     * @return array
     */
    public function contributeAction(Request $request)
    {
        $videoSuggest = new VideoSuggest();
        $form = $this->createForm(new VideoSuggestType(), $videoSuggest);

        $form->handleRequest($request);

        if ($form->isValid()) {

            return $this->redirectToRoute('Contribute');
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/contact", name="Contact")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function contactAction(Request $request)
    {
        $feedback = new Feedback();
        $form = $this->createForm(new FeedbackType(), $feedback);

        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $record = $this->container->get('tim_vhosting.feedback.handler')
                    ->create($form->getData());

                $this->addFlash('notice', 'Thank you, for your feedback!');
            }
            catch(\Exception $ex)
            {
                $this->addFlash('error', 'Sorry, something wrong');
            }

            return $this->redirectToRoute('Contact');
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
