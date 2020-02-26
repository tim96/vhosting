<?php declare(strict_types=1);

namespace App\TimVhostingBundle\Handler;

use App\TimVhostingBundle\Entity\TagsRepository;
use App\TimVhostingBundle\Entity\Video;
use App\TimVhostingBundle\Entity\VideoRepository;
use App\TimVhostingBundle\Interfaces\YoutubeVideoInterface;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\ORM\EntityManagerInterface;

class VideoHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var VideoRepository */
    private $repository;

    /** @var TagsRepository */
    private $tagsRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Required
     */
    public function setTagsRepository(TagsRepository $tagsRepository): void
    {
        $this->tagsRepository = $tagsRepository;
    }

    /**
     * @Required
     */
    public function setVideoRepository(VideoRepository $videoRepository): void
    {
        $this->repository = $videoRepository;
    }

    public function get($id)
    {
        return $this->repository->find($id);
    }

    public function getList($options = array())
    {
        return $this->repository->findBy($options);
    }

    public function getVideoByName($name)
    {
        return $this->repository->findOneBy(array('name' => $name, 'isDeleted' => false, 'isPublic' => true));
    }

    public function getVideoBySlug($slug)
    {
        return $this->repository->findOneBy(['slug' => $slug, 'isDeleted' => false, 'isPublic' => true]);
    }

    public function updateYoutubeVideoInfo(YoutubeVideoInterface $serviceYoutube)
    {
        $count = 0;
        $videos = $this->repository->getVideosQuery()->getQuery()->getResult();
        $resultsTags = $this->tagsRepository->getTagsQuery()->getQuery()->getResult();

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
                $this->em->persist($object);
                $this->em->flush();
                $count++;
            }
        }

        return $count;
    }
}
