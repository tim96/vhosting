<?php

namespace App\TimVhostingBundle\Handler;

use App\TimConfigBundle\Handler\Base\BaseEntityHandler;
use App\TimVhostingBundle\Entity\TagsRepository;

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

    public function getTagsNotDeleted()
    {
        return $this->getRepository()->getTagsQuery()->getQuery()->getResult();
    }

    /**
     * @return TagsRepository
     */
    public function getRepository()
    {
        return parent::getRepository();
    }
}