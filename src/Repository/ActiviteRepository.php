<?php

namespace App\Repository;

use App\Entity\Activite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Mixed_;

/**
 * @method Activite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activite[]    findAll()
 * @method Activite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activite::class);
    }

    /**
     * Liste globales des activites non encore executées
     *
     * @return mixed
     */
    public function findGlobal($annee)
    {
        return $this->createQueryBuilder('a')
            ->where('a.dateDebut >= :debut')
            ->andWhere('a.statut = 1')
            ->andWhere('a.annee = :annee')
            ->orderBy('a.dateDebut', 'ASC')
            ->setParameters([
                'debut' => date('Y-m-d', time()),
                'annee' => $annee
            ])
            ->getQuery()->getResult()
            ;
    }

    /**
     * @param $district
     * @return mixed
     */
    public function findByDistrict($district)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.district', 'd')
            ->where('d.id = :district')
            ->andWhere('a.dateDebut >= :debut')
            ->setParameters([
                'district' => $district,
                'debut' => date('Y-m-d', time())
            ])
            ->getQuery()->getResult()
            ;
    }

    /**
     * Liste des activités relativement à la region
     *
     * @param $region
     * @return mixed
     */
    public function findByRegion($region)
    {
        return $this->createQueryBuilder('a')
            ->join('a.district', 'd')
            ->join('d.region', "r")
            ->where('r.id = :region')
            ->andWhere('a.dateDebut >= :debut')
            ->orderBy('a.dateDebut', 'ASC')
            ->setParameters([
                'region' => $region,
                'debut' => date('Y-m-d',time())
            ])
            ->getQuery()->getResult()
            ;
    }

    /**
     * Nombre d'activité selon la structure
     *
     * @param $structre
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNombreByStructure($annee, $structre)
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->where('a.flag = :structure')
            ->andWhere('a.annee = :annee')
            ->setParameters([
                'structure' => $structre,
                'annee' => $annee
            ])
            ->getQuery()->getSingleScalarResult()
            ;
    }

    /**
     * Nombre d'activité concerné par le statut des participantc (jeune ou adulte)
     *
     * @param null $participant
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNombreByParticipant($annee, $participant = null)
    {
        if (!$participant){
            return $this->createQueryBuilder('a')
                ->select('count(a.id)')
                ->where('a.statut = 1')
                ->andWhere('a.annee = :annee')
                ->setParameter('annee', $annee)
                ->getQuery()->getSingleScalarResult()
                ;
        }else{
            return $this->createQueryBuilder('a')
                ->select('count(DISTINCT a.id)')
                ->leftJoin('a.participant','p')
                //->innerJoin('a.participant', 'p')
                ->where('a.statut = 1')
                ->andWhere('a.annee = :annee')
                ->andWhere('p.statut = :statut')
                ->setParameters([
                    'statut'=> $participant,
                    'annee' => $annee
                ])
                ->getQuery()->getSingleScalarResult()
                ;
        }
    }

    // ================== REGION ========//

    /**
     * Nombre d'activité selon la structure au niveau de regional
     *
     * @param $annee
     * @param $region
     * @param $flag
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNombreByStructureNiveauRegion($annee, $region, $flag)
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->leftJoin('a.district','d')
            ->leftJoin('d.region', 'r')
            ->where('a.flag = :flag')
            ->andWhere('r.id = :region')
            ->andWhere('a.statut = 1')
            ->andWhere('a.annee = :annee')
            ->setParameters([
                'region'=> $region,
                'annee' => $annee,
                'flag' => $flag
            ])
            ->getQuery()->getSingleScalarResult()
            ;
    }

    public function findNombreByParticipantNiveauRegiona($annee, $region, $flag, $participant = null)
    {
        if (!$participant){
            return $this->createQueryBuilder('a')
                ->select('count(a.id)')
                ->leftJoin('a.district','d')
                ->leftJoin('d.region', 'r')
                ->where('a.statut = 1')
                ->andWhere('a.annee = :annee')
                ->andWhere('a.flag = :flag')
                ->andWhere('r.id = :region')
                ->setParameters([
                    'annee'=> $annee,
                    'flag'=> $flag,
                    'region' => $region
                ])
                ->getQuery()->getSingleScalarResult()
                ;
        }else{
            return $this->createQueryBuilder('a')
                ->select('count(DISTINCT a.id)')
                ->leftJoin('a.participant','p')
                ->leftJoin('a.district','d')
                ->leftJoin('d.region', 'r')
                ->where('a.statut = 1')
                ->andWhere('a.annee = :annee')
                ->andWhere('p.statut = :statut')
                ->andWhere('a.flag = :flag')
                ->andWhere('r.id = :region')
                ->setParameters([
                    'statut'=> $participant,
                    'annee' => $annee,
                    'flag'=> $flag,
                    'region' => $region
                ])
                ->getQuery()->getSingleScalarResult()
                ;
        }
    }

    // /**
    //  * @return Activite[] Returns an array of Activite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Activite
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
