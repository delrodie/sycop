<?php

namespace App\Repository;

use App\Entity\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method District|null find($id, $lockMode = null, $lockVersion = null)
 * @method District|null findOneBy(array $criteria, array $orderBy = null)
 * @method District[]    findAll()
 * @method District[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DistrictRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, District::class);
    }

    /**
     * Liste sans les districts
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListWithoutDistrict()
    {
        return $this->createQueryBuilder('d')
            ->where('d.nom LIKE :district')
            ->setParameter('district', 'Equipe%')
            ;
    }

    /**
     * @param $regionID
     * @return mixed
     */
    public function findByRegionWithoutUser($regionID)
    {
        return $this->createQueryBuilder('d')
            ->where('d.region = :id')
            ->andWhere('d.user IS NULL')
            ->setParameter('id', $regionID)
            ->getQuery()->getResult()
            ;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function liste()
    {
        return $this->createQueryBuilder('d')->orderBy('d.nom', 'ASC');
    }

    public function findDistrict($district)
    {
        return $this->createQueryBuilder('d')->where('d.id = :district')->setParameter('district', $district);
    }

    /**
     * @param $region
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findListByRegion($region)
    {
        return $this->createQueryBuilder('d')
            ->join('d.region', 'r')
            ->where('r.id = :region')
            ->setParameter('region', $region)
            ;
    }

    // /**
    //  * @return District[] Returns an array of District objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?District
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
