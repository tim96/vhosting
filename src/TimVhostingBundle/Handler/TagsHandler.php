<?php declare(strict_types=1);

namespace App\TimVhostingBundle\Handler;

use App\TimVhostingBundle\Entity\TagsRepository;

class TagsHandler
{
    /** @var TagsRepository */
    private $tagsRepository;

    public function __construct(TagsRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }

    public function get($id)
    {
        return $this->tagsRepository->find($id);
    }

    public function getList($options = array())
    {
        return $this->tagsRepository->findBy($options);
    }

    public function getOneByName($name, $isDeleted = false)
    {
        return $this->tagsRepository->findOneBy(array('name' => $name, 'isDeleted' => $isDeleted));
    }

    public function getTagsNotDeleted()
    {
        return $this->tagsRepository->getTagsQuery()->getQuery()->getResult();
    }
}
