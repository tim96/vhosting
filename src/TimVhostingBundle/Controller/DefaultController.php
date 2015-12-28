<?php

namespace TimVhostingBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TimVhostingBundle\Entity\Feedback;
use TimVhostingBundle\Entity\VideoSuggest;
use TimVhostingBundle\Form\FeedbackType;
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
     * @Route("/{page}", requirements={"page" = "\d+"}, name="Home", defaults={"page" = null})
     *
     * @param null $page
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function frontendAction($page = null, Request $request)
    {
        $maxVideoOnPage = 10;

        $serviceTags = $this->container->get('tim_vhosting.tags.handler');
        $tags = $serviceTags->getList(array('isDeleted' => false));

        $serviceVideo = $this->container->get('tim_vhosting.video.handler');
        $videos = $serviceVideo->getList(array('isPublic' => true, 'isDeleted' => false));

        return $this->render('TimVhostingBundle:Default:frontend.html.twig', array('tags' => $tags, 'videos' => $videos));
    }

    /**
     * @Route("/new/{page}", requirements={"page" = "\d+"}, name="Frontend_new", defaults={"page" = null})
     *
     * @param null $page
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function frontendNewAction($page = null, Request $request)
    {
        $maxVideoOnPage = 10;

        $serviceTags = $this->container->get('tim_vhosting.tags.handler');
        $tags = $serviceTags->getList(array('isDeleted' => false));

        $serviceVideo = $this->container->get('tim_vhosting.video.handler');
        $videos = $serviceVideo->getList(array('isPublic' => true, 'isDeleted' => false));

        return $this->render('TimVhostingBundle:Default:frontendNew.html.twig', array('tags' => $tags, 'videos' => $videos));
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
        $form->add('save', 'submit', array('label' => 'save.button.label'));

        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $record = $this->container->get('tim_vhosting.video_suggest.handler')
                    ->create($form->getData());

                $this->addFlash('notice', 'Thank you, for your contribution!');
            }
            catch(\Exception $ex)
            {
                $this->addFlash('error', 'Sorry, something wrong');
            }

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
        $form->add('save', 'submit', array('label' => 'submit.button.label'));

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

    /**
     * @Route("/tags/{tag}", name="ShowVideoByTag")
     */
    public function showVideoByTagAction($tag, Request $request)
    {
        $serviceTags = $this->container->get('tim_vhosting.tags.handler');
        $tags = $serviceTags->getList(array('isDeleted' => false));

        $tag = $serviceTags->getOneByName($tag);
        $serviceVideo = $this->container->get('tim_vhosting.video.handler');
        $videos = $serviceVideo->getList();

        return $this->render('TimVhostingBundle:Default:frontend.html.twig', array('tags' => $tags, 'selectedTag' => $tag,
            'videos' => $videos));
    }
}
