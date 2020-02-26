<?php

namespace App\TimConfigBundle\Handler\Base;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Monolog\Logger;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class BaseContainerHandler
{
    /** @var  ContainerInterface */
    protected $container;
    /** @var  boolean */
    protected $isDebug;
    /** @var  Logger */
    protected $logger;
    /** @var  UserInterface */
    protected $user;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->isDebug = false;
        $this->logger = null;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function isDebug()
    {
        return $this->isDebug;
    }

    public function setIsDebug($value)
    {
        if (!is_bool($value)) throw new \Exception('Error. Parameter isDebug must be boolean.');

        $this->isDebug = $value;
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function getLogger()
    {
        return $this->logger;
    }
}