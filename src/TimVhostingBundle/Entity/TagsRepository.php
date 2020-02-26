<?php declare(strict_types=1);

namespace App\TimVhostingBundle\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TagsRepository
{
     /**
      * @var EntityRepository
      */
     private $repository;

     public function __construct(EntityManagerInterface $entityManager)
     {
         $this->repository = $entityManager->getRepository(Tags::class);
     }

    /**
     * @param bool $isDeleted
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getTagsQuery($isDeleted = false)
    {
        $qb = $this->repository->createQueryBuilder('t');

        if (is_bool($isDeleted)) {
            $qb->andWhere('t.isDeleted != :isDeleted')
                ->setParameter('isDeleted', !$isDeleted);
        }

        return $qb;
    }

    public function getJoinVideoQuery($isDeleted = false)
    {
        $qb = $this->getTagsQuery($isDeleted);

        $qb->leftJoin($qb->getRootAlias().'.videos', 'v')
            ->addSelect('v');

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
