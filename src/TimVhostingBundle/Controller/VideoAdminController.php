<?php

namespace App\TimVhostingBundle\Controller;

use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\TimVhostingBundle\Entity\Video;

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

    public function batchActionPublish(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        $this->admin->checkAccess('edit');

        $modelManager = $this->admin->getModelManager();

        /** @var Video[] $selectedModels */
        $selectedModels = $selectedModelQuery->execute();

        try {
            foreach ($selectedModels as $selectedModel) {
                $selectedModel->setIsPublic(true);
                $modelManager->update($selectedModel);
            }
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return new RedirectResponse(
                $this->admin->generateUrl('list', [
                    'filter' => $this->admin->getFilterParameters()
                ])
            );
        }

        $this->addFlash('sonata_flash_success', 'flash_batch_merge_success');

        return new RedirectResponse(
            $this->admin->generateUrl('list', [
                'filter' => $this->admin->getFilterParameters()
            ])
        );
    }

    public function batchActionUnpublish(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        $this->admin->checkAccess('edit');

        $modelManager = $this->admin->getModelManager();

        /** @var Video[] $selectedModels */
        $selectedModels = $selectedModelQuery->execute();

        try {
            foreach ($selectedModels as $selectedModel) {
                $selectedModel->setIsPublic(false);
                $modelManager->update($selectedModel);
            }
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return new RedirectResponse(
                $this->admin->generateUrl('list', [
                    'filter' => $this->admin->getFilterParameters()
                ])
            );
        }

        $this->addFlash('sonata_flash_success', 'flash_batch_merge_success');

        return new RedirectResponse(
            $this->admin->generateUrl('list', [
                'filter' => $this->admin->getFilterParameters()
            ])
        );
    }
}
