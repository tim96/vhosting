<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 10/16/2015
 * Time: 10:41 PM
 */

namespace TimVhostingBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;

class VideoSuggestEvent extends Event
{
    private $videoSuggest = null;

    public function createVideoSuggest($subject)
    {
        $this->videoSuggest = $subject;
    }

    public function getVideoSuggest()
    {
        return $this->videoSuggest;
    }
}