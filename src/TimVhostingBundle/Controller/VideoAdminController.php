<?php

namespace TimVhostingBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use TimVhostingBundle\Entity\Video;

class VideoAdminController extends CRUDController
{
    public function publishAction($id, Request $request)
    {
        $id      = $request->get($this->admin->getIdParameter());
        /** @var Video $object */
        $object  = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        try {
            $object->setIsPublic(true);
            $this->admin->update($object);

            $this->addFlash(
                'sonata_flash_success',
                $this->admin->trans(
                    'flash_video_publish_success',
                    array(),
                    'TimVhostingBundle'
                )
            );
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);

            $this->addFlash(
                'sonata_flash_error',
                $this->admin->trans(
                    'flash_video_publish_error',
                    array(),
                    'TimVhostingBundle'
                )
            );
        }

        $url = $this->admin->generateUrl('list');
        return new RedirectResponse($url);
        // return $this->redirectTo($object, $request);
    }

    public function unpublishAction($id, Request $request)
    {
        $id      = $request->get($this->admin->getIdParameter());
        /** @var Video $object */
        $object  = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        try {
            $object->setIsPublic(false);
            $this->admin->update($object);

            $this->addFlash(
                'sonata_flash_success',
                $this->admin->trans(
                    'flash_video_unpublish_success',
                    array(),
                    'TimVhostingBundle'
                )
            );
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);

            $this->addFlash(
                'sonata_flash_error',
                $this->admin->trans(
                    'flash_video_unpublish_error',
                    array(),
                    'TimVhostingBundle'
                )
            );
        }

        $url = $this->admin->generateUrl('list');
        return new RedirectResponse($url);
        // return $this->redirectTo($object, $request);
    }
}
