<?php

namespace App\TimConfigBundle\Handler\Base;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseContainerEmHandler extends BaseContainerHandler
{
    /** @var  ContainerInterface */
    protected $container;
    /** @var  EntityManager */
    protected $om;

    public function __construct(ContainerInterface $container, ObjectManager $om)
    {
        parent::__construct($container);

        $this->om = $om;
    }

    public function getManager()
    {
        return $this->om;
    }
}