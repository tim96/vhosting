<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 10/15/2015
 * Time: 8:48 PM
 */

namespace TimVhostingBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;

class FeedbackEvent extends Event
{
    private $feedback = null;

    public function createFeedback($subject)
    {
        $this->feedback = $subject;
    }

    public function getFeedback()
    {
        return $this->feedback;
    }
}