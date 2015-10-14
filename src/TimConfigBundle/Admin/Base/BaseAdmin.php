<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 10/8/2015
 * Time: 9:25 PM
 */

namespace TimConfigBundle\Admin\Base;

use Sonata\AdminBundle\Admin\Admin as SonataAdmin;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Sonata\UserBundle\Entity\User;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Monolog\Logger;

abstract class BaseAdmin extends SonataAdmin
{
    /** @var Logger $logger */
    private $logger;
    /** @var ContainerInterface */
    protected $container;

    public function __construct($code, $class, $baseControllerName, ContainerInterface $container)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->container = $container;
        $this->logger = $this->container->get('logger');
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getContainer()
    {
        return $this->container;
    }

    protected function getUser()
    {
        /** @var User $user */
        return $this->container->get('security.token_storage')->getToken()->getUser();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        unset($this->listModes['mosaic']);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        parent::prePersist($object);

        $user = $this->getUser();
        if (method_exists($object, 'setCreatedAt')) {
            $object->setCreatedAt(new \DateTime('now'));
        }
        if (method_exists($object, 'setUpdatedAt')) {
            $object->setUpdatedAt(new \DateTime('now'));
        }
        if (method_exists($object, 'setAuthor')) {
            $object->setAuthor($user);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        parent::preUpdate($object);

        $user = $this->getUser();
        if (method_exists($object, 'setUpdatedAt')) {
            $object->setUpdatedAt(new \DateTime('now'));
        }
        if (method_exists($object, 'setAuthor')) {
            $object->setAuthor($user);
        }
    }

    public function postPersist($object)
    {
        $user = $this->getUser();
        if (method_exists($object, 'setUpdatedAt')) {
            $object->setUpdatedAt(new \DateTime('now'));
        }
        if (method_exists($object, 'setCreatedAt')) {
            $object->setCreatedAt(new \DateTime('now'));
        }
        if (method_exists($object, 'setAuthor')) {
            $object->setAuthor($user);
        }
        $this->logger->addCritical("{$this->getUser()} #{$this->getUser()->getId()}. Create {$this->getClass()} #{$object->getId()}.");
    }

    public function postUpdate($object)
    {
        $user = $this->getUser();
        if (method_exists($object, 'setUpdatedAt')) {
            $object->setUpdatedAt(new \DateTime('now'));
        }
        if (method_exists($object, 'setAuthor')) {
            $object->setAuthor($user);
        }
        $this->logger->addCritical("{$this->getUser()} #{$this->getUser()->getId()}. Update {$this->getClass()} #{$object->getId()}.");
    }
}