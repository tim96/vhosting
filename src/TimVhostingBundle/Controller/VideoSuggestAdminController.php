<?php

namespace App\TimVhostingBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\TimConfigBundle\Controller\Base\BaseCrudController;
use App\TimVhostingBundle\Entity\Video;
use App\TimVhostingBundle\Entity\VideoSuggest;

class VideoSuggestAdminController extends BaseCrudController
{
    public function approveAction($id = null, Request $request = null)
    {
        $id     = $request->get($this->admin->getIdParameter());
        /** @var VideoSuggest $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->checkAccess('edit', $object);

        $preResponse = $this->preEdit($request, $object);
        if ($preResponse !== null) {
            return $preResponse;
        }

        $this->admin->setSubject($object);

        $this->getDoctrine()->getConnection()->beginTransaction();
        $manager = $this->getDoctrine()->getManager();

        try {

            if (!$object->isHold()) {
                throw new \Exception("You can't change this status");
            }

            $video = new Video();
            $video->setAuthor($this->getUser());
            $video->setLink($object->getLink());
            $video->setDescription($object->getDescription());
            $video->setName($object->getTitle());
            $video->setVideoSuggest($object);
            foreach($object->getTags() as $tag) {
                $video->addTag($tag);
            }

            $object->setStatus($object::STATUS_APPROVE);
            $object = $this->admin->update($object);

            $manager->persist($video);
            $manager->flush();

            $this->getDoctrine()->getConnection()->commit();

            $this->addFlash(
                'sonata_flash_success',
                $this->admin->trans('flash_approve_videosuggest_success', array(), 'TimVhostingBundle'
                )
            );
        }
        catch(\Exception $ex)
        {
            $this->getDoctrine()->getConnection()->rollback();

            $this->addFlash(
                'sonata_flash_error',
                $this->admin->trans('flash_approve_videosuggest_error', array(
                    '%error%' => $ex->getMessage()
                ), 'TimVhostingBundle'
                )
            );
        }

        $url = $this->admin->generateUrl('list');
        return new RedirectResponse($url);
    }

    public function rejectAction($id = null, Request $request = null)
    {
        $id     = $request->get($this->admin->getIdParameter());
        /** @var VideoSuggest $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->checkAccess('edit', $object);

        $preResponse = $this->preEdit($request, $object);
        if ($preResponse !== null) {
            return $preResponse;
        }

        $this->admin->setSubject($object);

        try {

            if (!$object->isHold()) {
                throw new \Exception("You can't change this status");
            }

            $object->setStatus($object::STATUS_REJECT);
            $object = $this->admin->update($object);

            $this->addFlash(
                'sonata_flash_success',
                $this->admin->trans('flash_reject_videosuggest_success', array(), 'TimVhostingBundle'
                )
            );
        }
        catch(\Exception $ex)
        {
            $this->addFlash(
                'sonata_flash_error',
                $this->admin->trans('flash_reject_videosuggest_error', array(
                    '%error%' => $ex->getMessage()
                ), 'TimVhostingBundle'
                )
            );
        }

        $url = $this->admin->generateUrl('list');
        return new RedirectResponse($url);
    }
}
