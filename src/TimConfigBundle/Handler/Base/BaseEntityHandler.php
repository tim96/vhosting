<?php

namespace App\TimConfigBundle\Handler\Base;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityRepository;

abstract class BaseEntityHandler extends BaseContainerEmHandler
{
    /** @var  string */
    protected $entityClass;
    /** @var  EntityRepository */
    protected $repository;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em, string $entityClass)
    {
        parent::__construct($container, $em);

        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
    }

    public function getRepository()
    {
        return $this->repository;
    }
}
