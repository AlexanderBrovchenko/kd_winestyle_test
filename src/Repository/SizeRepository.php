<?php

namespace App\Repository;

use App\Entity\Size;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SizeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Size::class);
    }

    public function getAll()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.width', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getAllForDesktop()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.fordesktop = :val')
            ->setParameter('val', true)
            ->orderBy('s.width', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getAllForMobile()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.formobile = :val')
            ->setParameter('val', true)
            ->orderBy('s.width', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    
    public function findOneByCode($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.code = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
