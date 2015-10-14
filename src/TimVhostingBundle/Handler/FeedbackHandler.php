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

        return $data;
    }
}