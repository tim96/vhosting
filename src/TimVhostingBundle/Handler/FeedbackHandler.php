<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 31.07.2015
 * Time: 23:21
 */

namespace TimVhostingBundle\Handler;

use TimConfigBundle\Handler\Base\BaseEntityHandler;
use TimVhostingBundle\Entity\Feedback;
use TimVhostingBundle\EventListener\FeedbackEvent;

class FeedbackHandler extends BaseEntityHandler
{
    public function get($id)
    {
        return $this->getRepository()->find($id);
    }

    public function getList($options = array())
    {
        return $this->getRepository()->findBy($options);
    }

    public function create($data)
    {
        $this->om->persist($data);
        $this->om->flush();

        $event = new FeedbackEvent();
        $event->createFeedback($data);

        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('tim_vhosting.feedback.create', $event);

        return $data;
    }
}