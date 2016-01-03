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

    public function getVideoByName($name)
    {
        return $this->getRepository()->findOneBy(array('name' => $name, 'isDeleted' => false, 'isPublic' => true));
    }

    public function updateYoutubeVideoInfo(YoutubeVideoInterface $serviceYoutube)
    {
        $count = 0;
        $videos = $this->getRepository()->getVideosQuery()->getQuery()->getResult();
        $repositoryTags = $this->om->getRepository('TimVhostingBundle:Tags');
        $resultsTags = $repositoryTags->getTagsQuery()->getQuery()->getResult();

        /** @var Video $object */
        foreach($videos as $object) {
            $isUpdate = false;

            /** @var \Google_Service_YouTube_Video $data */
            $data = $serviceYoutube->getYoutubeVideoInfo($object->getYoutubeVideoId());
            $duration = $serviceYoutube->getYoutubeVideoDurationFromData($data);

            $lang = $data->getLocalizations();
            /** @var \Google_Service_YouTube_VideoSnippet $snippet */
            $snippet = $data->getSnippet();

            $snippet->getDefaultLanguage();

            $tags = $snippet->getTags();
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    foreach ($resultsTags as $resultsTag) {
                        if (strtoupper($resultsTag->getName()) == strtoupper($tag)) {
                            if (!$object->getTags()->contains($resultsTag)) {
                                $object->addTag($resultsTag);
                                $meta = $object->getMeta();
                                $object->setMeta($meta . ' ' . $resultsTag->getName());
                                $isUpdate = true;
                            }
                        }
                    }
                }
            }

            if ($object->getDurationVideo() != $duration) {
                $object->setDurationVideo($duration);
                $isUpdate = true;
            }

            if ($object->getLanguageCode() != $snippet->getDefaultLanguage()) {
                $object->setLanguageCode($snippet->getDefaultLanguage());
                $isUpdate = true;
            }

            $statistics = $serviceYoutube->getYoutubeVideoStatisticsFromData($data);

            if ($object->getViewCount() != $duration) {
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