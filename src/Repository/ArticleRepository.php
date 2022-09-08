<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Recherche des articles en fonction d'un formulaire.
     *
     * @return void
     */
    public function search($mots = null, $categorie = null)
    {
        $query = $this->createQueryBuilder('a');
        if ($mots != null) {
            $query->where('MATCH_AGAINST(a.denomination, a.description) AGAINST (:mots boolean) > 0')
            ->setParameter('mots', $mots);
        }
        if ($categorie != null) {
            $query->leftJoin('a.categorie', 'c');
            $query->andWhere('c.id = :id')
            ->setParameter('id', $categorie);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * Récupere les produits en lien avec une recherche.
     *
     * @return Product[]
     */
    public function findSearch(bool $nouveau = null, bool $tendance = null, int $idCategorie = null)
    {
        $query = $this->createQueryBuilder('a');
        // Crée une requête avec l'entité Article
        if ($idCategorie !== null) {
            $query->andWhere('a.categorie = :categorie')
            ->setParameter(':categorie', $idCategorie);
        }

        if ($nouveau != null) {
            // Si nouveau est différent de null une fois le form soumis, on le cherche içi :
            $query->andWhere('a.nouveau > 0');
            // ->setParameter('nouveau', $nouveau);
        }
        if ($tendance != null) {
            $query->andWhere('a.tendance > 0');
            // ->setParameter('tendance', $tendance);
        }

        return $query->getQuery()->getResult(); // On récupere les resultats
    }

    // public function searchCount(bool $nouveau = null, bool $tendance = null)
    // {
    //     $qb = $this->getEntityManager()->createQueryBuilder();
    //     $qb->select('COUNT(a.id)')
    //         ->from('App\Entity\Article', 'a');

    //     if ($nouveau === true) {
    //         $qb->andWhere('p.promo > 0');
    //         // ->setParameter(':promo', $promo);
    //     }
    //     if ($tendance === true) {
    //         $qb->andWhere('p.promo > 0');
    //     }

    //     $qb->orderBy('a.id', 'DESC');
    //     $query = $qb->getQuery();

    //     return $query->getOneOrNullResult();
    // }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
