<?php declare(strict_types=1);

namespace App\TimConfigBundle\Handler\Base;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseContainerEmHandler extends BaseContainerHandler
{
    /** @var  ContainerInterface */
    protected $container;
    /** @var  EntityManagerInterface */
    protected $om;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        parent::__construct($container);

        $this->om = $em;
    }

    public function getManager()
    {
        return $this->om;
    }
}
