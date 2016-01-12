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
     * @Route("/{page}/{tag}", requirements={"page" = "\d+"}, name="Home", defaults={"page" = 1, "tag" = null})
     *
     * @param int $page
     * @param null $tag
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function frontendAction($page = 1, $tag = null, Request $request)
    {
        $serach = $request->query->get('search');

        $maxVideoOnPage = 10;
        $paginator = $this->get('knp_paginator');

        $serviceTags = $this->container->get('tim_vhosting.tags.handler');
        if (is_null($tag)) {
            $tags = $serviceTags->getList(array('isDeleted' => false));
        } else {
            $tags = $serviceTags->getList(array('isDeleted' => false, 'name' => $tag));
        }

        $serviceVideo = $this->container->get('tim_vhosting.video.handler');
        $carousel = $serviceVideo->getRepository()->getTopVideos($maxVideos = 4)->getQuery()->getResult();

        $videos = array();
        // $videos = $serviceVideo->getList(array('isPublic' => true, 'isDeleted' => false));

        // $query = $serviceVideo->getRepository()->getList()->getQuery();
        $query = $serviceVideo->getRepository()->getTagsQuery($tag);
        $query = $serviceVideo->getRepository()->getSearch($serach, $query)->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $page /*page number*/,
            $maxVideoOnPage /*limit per page*/
        );

        return $this->render('TimVhostingBundle:Default:frontend.html.twig',
            array('tags' => $tags, 'videos' => $videos, 'pagination' => $pagination, 'carousel' => $carousel));
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
     * @Route("/angular", name="angular")
     * @Template("TimVhostingBundle:Default:angular.html.twig")
     */
    public function angularAction(Request $request)
    {
        // todo: rewrite frontend using angularjs

        return array();
    }

    /**
     * @Route("/angularTest", name="angular_test")
     * @Template("TimVhostingBundle:Default:angularTest.html.twig")
     */
    public function angularTestAction(Request $request)
    {
        // guess the number
        return array();
    }

    /**
     * @Route("/angularTest1", name="angular_test1")
     * @Template("TimVhostingBundle:Default:angularTest1.html.twig")
     */
    public function angularTest1Action(Request $request)
    {
        // 12 minutes workout
        return array();
    }

    /**
     * @Route("/angularTest2", name="angular_test2")
     * @Template("TimVhostingBundle:Default:angularTest2.html.twig")
     */
    public function angularTest1Action(Request $request)
    {
        // zero game
        return array();
    }

    /**
     * @Route("/angular2Test", name="angular2_test")
     * @Template("TimVhostingBundle:Default:angular2Test.html.twig")
     */
    public function angular2TestAction(Request $request)
    {
        return array();
    }
}
