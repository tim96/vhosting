<?php

namespace TimVhostingBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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

        // todo: Testing new layout
        // $maxVideoOnPage = 10;
        $maxVideoOnPage = 9;
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

        // todo: Testing new layout
        // return $this->render('TimVhostingBundle:Default:frontend.html.twig',
        //     array('tags' => $tags, 'videos' => $videos, 'pagination' => $pagination, 'carousel' => $carousel));
        return $this->render('TimVhostingBundle:DefaultBootstrapV4:frontend.html.twig',
            ['tags' => $tags, 'videos' => $videos, 'pagination' => $pagination]);
    }

    /**
     * @Route("/about", name="About")
     */
    public function aboutAction()
    {
        // todo: Testing new layout
        // return $this->render('TimVhostingBundle:Default:about.html.twig', array());
        return $this->render('TimVhostingBundle:DefaultBootstrapV4:about.html.twig', array());
    }

    /**
     * @Route("/contribute", name="Contribute")
     *
     * @param Request $request
     * @return Response
     */
    public function contributeAction(Request $request): Response
    {
        $videoSuggest = new VideoSuggest();
        $form = $this->createForm(VideoSuggestType::class, $videoSuggest);
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

        // todo: Testing new layout
        // return $this->render('TimVhostingBundle:Default:contribute.html.twig', [
        return $this->render('TimVhostingBundle:DefaultBootstrapV4:contribute.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contact", name="Contact")
     *
     * @param Request $request
     * @return array|RedirectResponse|Response
     */
    public function contactAction(Request $request): Response
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
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

        // todo: Testing new layout
        // return $this->render('TimVhostingBundle:Default:contact.html.twig', [
        return $this->render('TimVhostingBundle:DefaultBootstrapV4:contact.html.twig', [
            'form' => $form->createView()
        ]);
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
