<?php

namespace TimVhostingBundle\Handler;

use TimConfigBundle\Handler\Base\BaseEntityHandler;

class TagsHandler extends BaseEntityHandler
{
    public function get($id)
    {
        return $this->getRepository()->find($id);
    }

    public function getList($options = array())
    {
        return $this->getRepository()->findBy($options);
    }

    public function getOneByName($name, $isDeleted = false)
    {
        return $this->getRepository()->findOneBy(array('name' => $name, 'isDeleted' => $isDeleted));
    }
}