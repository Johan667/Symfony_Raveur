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

    public function TriBar(bool $nouveau = null, float $prixUn = null, float $prixDeux = null, int $limit = null, int $offset = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('App\Entity\Produit', 'p')
            ->where('p.nouveau = true');

        if ($nouveau === true) {
            $qb->andWhere('p.nouveau > 0');
        }
        if ($prixUn !== null) {
            $qb->andWhere('p.prix > :prix')
            ->setParameter(':prix', $prixUn);
        }
        if ($prixDeux !== null) {
            $qb->andWhere('p.prix < :prix')
            ->setParameter(':prix', $prixDeux);
        }

        $query = $qb->getQuery();

        return $query->execute();
    }

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
