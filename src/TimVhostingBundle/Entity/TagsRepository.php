<?php

namespace TimVhostingBundle\Entity;

/**
 * TagsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagsRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param bool $isDeleted
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getTagsQuery($isDeleted = false)
    {
        $qb = $this->createQueryBuilder('t');

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
}
