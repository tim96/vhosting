<?php declare(strict_types = 1);

namespace App\TimVhostingBundle\Controller;

use App\TimVhostingBundle\Handler\VideoHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\TimVhostingBundle\Entity\Video;

/**
 * @Route("/video")
 */
class VideoController extends AbstractController
{
    /** @var VideoHandler */
    private $videoHandler;

    public function __construct(VideoHandler $videoHandler)
    {
        $this->videoHandler = $videoHandler;
    }

    /**
     * @Route("/{slug}", name="show_video_slug")
     */
    public function videoShowAction($slug)
    {
        /** @var Video $video */
        $video = $this->videoHandler->getVideoBySlug($slug);
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
