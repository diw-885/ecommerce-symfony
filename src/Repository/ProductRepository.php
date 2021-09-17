<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Récupérer les produits les moins chers à partir de 200 euros.
     *
     * @param  int  $price
     * @param  int  $limit
     * @return array
     */
    public function findByCheapPriceAtLeast(int $price = 200, $limit = null): array
    {
        $qb = $this->createQueryBuilder('p'); // SELECT p.id, p.name, p.description FROM product p

        if ($limit) {
            $qb->setMaxResults($limit); // LIMIT 4
        }

        return $qb->where('p.price > :price') // WHERE p.price > :price
            ->setParameter('price', $price) // bindValue('price', $price)
            ->orderBy('p.price', 'asc') // ORDER BY p.price ASC
            ->getQuery() // execute()
            ->getResult(); // fetchAll()
    }

    /**
     * Permet de récupérer les produits avec une jointure sur les catégories.
     * Pour optimiser le nombre de requêtes...
     *
     * @return array
     */
    public function findAllWithJoin()
    {
        return $this->createQueryBuilder('p') // SELECT p.name FROM product p
            ->addSelect('c') // // SELECT p.name, c.name FROM product p
            ->join('p.category', 'c') // INNER JOIN category c ON c.id = p.category_id
            ->getQuery()
            ->getResult();
    }

    public function findByColors($colors)
    {
        $qb = $this->createQueryBuilder('p');

        if (!empty($colors)) { // Si on a des couleurs, on join sur la table colors on ne sélectionne que les couleurs choisies dans le filtre
            $qb->join('p.colors', 'c')
                ->where('c.id IN (:colors)')
                ->setParameter('colors', implode(',', $colors)); // [11, 12, 13] => 11, 12, 13
        }

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
