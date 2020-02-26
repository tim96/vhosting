<?php declare(strict_types=1);

namespace App\TimVhostingBundle\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class VideoRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Video::class);
    }

    public function getList($isDeleted = false, $isPublic = true)
    {
        $qb = $this->repository->createQueryBuilder('v');

        if (is_bool($isDeleted)) {
            $qb->andWhere('v.isDeleted != :isDeleted')
                ->setParameter('isDeleted', !$isDeleted);
        }

        if (is_bool($isPublic)) {
            $qb->andWhere('v.isPublic = :isPublic')
                ->setParameter('isPublic', $isPublic);
        }


        // At this moment start order by from createdAt.
        // After need to change it to
        $qb->orderBy('v.createdAt', 'DESC');

        return $qb;
    }

    public function getTagsQuery($tagName = null)
    {
        $qb = $this->getList();

        if (!is_null($tagName)) {
            $qb->leftJoin('v.tags', 'tags')
                ->addSelect('tags')
                ->andWhere('tags.name = :tagName')
                ->setParameter('tagName', $tagName)
            ;
        }

        return $qb;
    }

    public function getSearch($search, $qb)
    {
        if (is_null($qb)) {
            $qb = $this->getList();
        }

        if (!is_null($search)) {
            /*$qb->andWhere('v.description LIKE :word')
                ->setParameter('word', '%'.$search.'%')
                ->andWhere('v.name LIKE :word1')
                ->setParameter('word1', '%'.$search.'%')
            ;*/
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('v.description', '?1'),
                    $qb->expr()->like('v.name', '?2')
                )
            )
            ->setParameter('1', '%'.$search.'%')
            ->setParameter('2', '%'.$search.'%')
            ;
        }

        return $qb;
    }

    public function getTopVideos($maxResults = 5)
    {
        $qb = $this->getList();

        $qb->setMaxResults($maxResults);
        $qb->orderBy('v.viewCount', 'DESC');

        return $qb;
    }

    /**
     * @param bool $isDeleted
     * @param null $youtubeVideoId
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getVideosQuery($isDeleted = false, $youtubeVideoId = null)
    {
        $qb = $this->repository->createQueryBuilder('v');

        if (is_bool($isDeleted)) {
            $qb->andWhere('v.isDeleted != :isDeleted')
                ->setParameter('isDeleted', !$isDeleted);
        }

        if (!is_null($youtubeVideoId)) {
            $qb->andWhere('v.youtubeVideoId = :youtubeVideoId')
                ->setParameter('youtubeVideoId', $youtubeVideoId);
        } else {
            $qb->andWhere('v.youtubeVideoId IS NOT NULL');
        }

        return $qb;
    }

    public function getVideoListCompareList($ids, $isDeleted = false)
    {
        $qb = $this->repository->createQueryBuilder('v');

        if (is_bool($isDeleted)) {
            $qb->andWhere('v.isDeleted != :isDeleted')
                ->setParameter('isDeleted', !$isDeleted);
        }

        $qb->andWhere('v.youtubeVideoId IN (:ids)')
            ->setParameter('ids', $ids)
        ;

        return $qb;
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findBy(array $criteria = []): array
    {
        return $this->repository->findBy($criteria);
    }

    public function findOneBy(array $criteria = [])
    {
        return $this->repository->findOneBy($criteria);
    }
}
