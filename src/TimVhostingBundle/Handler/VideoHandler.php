<?php

namespace TimVhostingBundle\Handler;

use TimConfigBundle\Handler\Base\BaseEntityHandler;
use TimVhostingBundle\Entity\Video;
use TimVhostingBundle\Entity\VideoRepository;
use TimVhostingBundle\Interfaces\YoutubeVideoInterface;

class VideoHandler extends BaseEntityHandler
{
    /**
     * @return VideoRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    public function get($id)
    {
        return $this->getRepository()->find($id);
    }

    public function getList($options = array())
    {
        return $this->getRepository()->findBy($options);
    }

    public function updateYoutubeVideoInfo(YoutubeVideoInterface $serviceYoutube)
    {
        $count = 0;
        $videos = $this->getRepository()->getVideosQuery()->getQuery()->getResult();

        /** @var Video $object */
        foreach($videos as $object) {
            $data = $serviceYoutube->getYoutubeVideoInfo($object->getYoutubeVideoId());
            $duration = $serviceYoutube->getYoutubeVideoDurationFromData($data);

            $isUpdate = false;
            if ($object->getDurationVideo() != $duration) {
                $object->setDurationVideo($duration);
                $isUpdate = true;
            }

            $statistics = $serviceYoutube->getYoutubeVideoStatisticsFromData($data);

            if ($object->getDurationVideo() != $duration) {
                $object->setViewCount($statistics->getViewCount());
                $isUpdate = true;
            }
            if ($object->getLikeCount() != $statistics->getLikeCount()) {
                $object->setLikeCount($statistics->getLikeCount());
                $isUpdate = true;
            }
            if ($object->getDislikeCount() != $statistics->getDislikeCount()) {
                $object->setDislikeCount($statistics->getDislikeCount());
                $isUpdate = true;
            }
            if ($object->getFavoriteCount() != $statistics->getFavoriteCount()) {
                $object->setFavoriteCount($statistics->getFavoriteCount());
                $isUpdate = true;
            }

            if ($isUpdate) {
                $this->om->persist($object);
                $this->om->flush();
                $count++;
            }
        }

        return $count;
    }
}