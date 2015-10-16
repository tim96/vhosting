<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 10/16/2015
 * Time: 10:39 PM
 */

namespace TimVhostingBundle\Handler;

use TimConfigBundle\Handler\Base\BaseEntityHandler;
use TimVhostingBundle\EventListener\VideoSuggestEvent;

class VideoSuggestHandler extends BaseEntityHandler
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

        $event = new VideoSuggestEvent();
        $event->createVideoSuggest($data);

        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('tim_vhosting.video_suggest.create', $event);

        return $data;
    }
}