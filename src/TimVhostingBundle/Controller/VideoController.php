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
        return array('test' => $name);
    }
}