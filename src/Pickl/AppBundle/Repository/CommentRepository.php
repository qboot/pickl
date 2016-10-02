<?php

namespace Pickl\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends EntityRepository
{
    public function findCommentsOfUser($username, $page, $nbPerPage) {

        $qb = $this->createQueryBuilder('c');

        $query = $qb
            ->leftJoin('c.user', 'u')
            ->addSelect('u')
            ->leftJoin('c.project', 'p')
            ->addSelect('p')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->orderBy('c.date', 'DESC')
        ;


        $query
            ->setFirstResult(($page - 1) * $nbPerPage)
            ->setMaxResults($nbPerPage);

        return new Paginator($query, true);
    }

    public function findCommentsOfUserWithLimit($username, $limit) {

        $qb = $this->createQueryBuilder('c');

        $qb
            ->leftJoin('c.user', 'u')
            ->addSelect('u')
            ->leftJoin('c.project', 'p')
            ->addSelect('p')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->orderBy('c.date', 'DESC')
            ->setMaxResults($limit)
        ;


        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByDate($slug){
        $qb = $this->createQueryBuilder('c');

        $qb
            ->leftJoin('c.project', 'p')
            ->addSelect('p')
            ->where('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('c.date', 'DESC')
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}
