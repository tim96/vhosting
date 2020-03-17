<?php declare(strict_types=1);

namespace App\TimConfigBundle\Admin\Base;

use Psr\Log\LoggerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Monolog\Logger;

abstract class BaseAdmin extends AbstractAdmin
{
    /** @var Logger $logger */
    private $logger;

    /**
     * @Required
     *
     * @param LoggerInterface $logger
     * @return Logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
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
