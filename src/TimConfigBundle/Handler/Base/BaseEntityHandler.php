<?php

namespace TimConfigBundle\Handler\Base;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityRepository;

abstract class BaseEntityHandler extends BaseContainerEmHandler
{
    /** @var  string */
    protected $entityClass;
    /** @var  EntityRepository */
    protected $repository;

    public function __construct(ContainerInterface $container, ObjectManager $om, $entityClass)
    {
        parent::__construct($container, $om);

        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
    }

    public function getRepository()
    {
        return $this->repository;
    }
}