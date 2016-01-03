<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 12/15/2015
 * Time: 8:58 PM
 */

namespace TimVhostingBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TimVhostingBundle\Entity\Video;

/**
 * @Route("/video")
 */
class VideoController extends Controller
{
    /**
     * @Route("/{name}", name="show_video_name")
     */
    public function videoShowAction($name)
    {
        $serviceVideo = $this->container->get('tim_vhosting.video.handler');
        /** @var Video $video */
        $video = $serviceVideo->getVideoByName($name);
        if (is_null($video)) {
            $url = $this->generateUrl('Home');
            return new RedirectResponse($url);
        }

        $tags = $video->getTags();
        $carousel = $serviceVideo->getRepository()->getTopVideos($maxVideos = 4)->getQuery()->getResult();

        return $this->render('TimVhostingBundle:Video:index.html.twig',
            array('tags' => $tags, 'video' => $video, 'carousel' => $carousel));
    }
}