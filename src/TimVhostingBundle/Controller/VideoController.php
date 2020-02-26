<?php declare(strict_types = 1);

namespace App\TimVhostingBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\TimVhostingBundle\Entity\Video;

/**
 * @Route("/video")
 */
class VideoController extends Controller
{
    /**
     * @Route("/{slug}", name="show_video_slug")
     */
    public function videoShowAction($slug)
    {
        $serviceVideo = $this->container->get('tim_vhosting.video.handler');
        /** @var Video $video */
        $video = $serviceVideo->getVideoBySlug($slug);
        if (null === $video) {
            $url = $this->generateUrl('Home');
            return new RedirectResponse($url);
        }

        $tags = $video->getTags();
        // $carousel = $serviceVideo->getRepository()->getTopVideos($maxVideos = 4)->getQuery()->getResult();

        // todo: Testing new layout
        // return $this->render('TimVhostingBundle:Video:index.html.twig',
        return $this->render('TimVhostingBundle:DefaultBootstrapV4:video.html.twig',
            array('tags' => $tags, 'video' => $video));
    }
}
