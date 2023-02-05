<?php

namespace App\Repository;

use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Seance>
 *
 * @method Seance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seance[]    findAll()
 * @method Seance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seance::class);
    }

    public function save(Seance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Seance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Seance[] Returns an array of Seance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Seance
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

   /**
     * @return Seance[] Returns an array of Seance objects
     */
    public function getAll()
    {
        return $this->getEntityManager()
          ->createQuery(
            'SELECT 1 FROM App:Seance 1'
          )
          ->getResult
       ;
    }

    
/**
     * 
     */
    public function getAllCharKid()
    {
    
        return $this->createQueryBuilder('s')
          ->where("s.categorie = 3 ")
          ->getQuery()
          ->getResult()
       ;
    }



 /**
     * 
     */
    public function getAllCatamaran()
    {
        return $this->createQueryBuilder('s')
          ->where("s.categorie = 2 ")
          
          ->getQuery()
          ->getResult()
       ;
    }

 /**
     * 
     */
    public function getAllChar()
    {
        return $this->createQueryBuilder('s')
          ->where("s.categorie = 1 ")
          
          ->getQuery()
          ->getResult()
       ;
    }


/**
     * 
     */
    public function getDescribCharKid()
    {
        $limit= 1;
        return $this->createQueryBuilder('s')
          
          ->where("s.categorie = 3 ")
          ->setMaxResults( $limit )
          ->getQuery()
          ->getResult()
       ;
    }


/**
     * 
     */
    public function getDescribChar()
    {
        $limit= 1;
        return $this->createQueryBuilder('s')
          
          ->where("s.categorie = 1 ")
          ->setMaxResults( $limit )
          ->getQuery()
          ->getResult()
       ;
    }


    /**
     * 
     */
    public function getDescribCata()
    {
        $limit= 1;
        return $this->createQueryBuilder('s')
          
          ->where("s.categorie = 2 ")
          ->setMaxResults( $limit )
          ->getQuery()
          ->getResult()
       ;
    }

}
