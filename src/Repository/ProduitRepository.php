<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }
    /*hedhi 
    public function searchByAttributes($attributes)
{
        $queryBuilder = $this->createQueryBuilder('e');

    // Boucle sur les attributs pour ajouter des conditions de recherche dynamiquement
        foreach ($attributes as $key => $value) {
            if (!empty($value)) {
                $queryBuilder->andWhere("e.$key LIKE :$key")
                    ->setParameter($key, "%$value%");
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }
*/

    /*
    public function findByCriteria($nom, $categorie)
{
    $qb = $this->createQueryBuilder('p');

    if ($nom) {
        $qb->andWhere('p.nom LIKE :nom')
           ->setParameter('nom', '%' . $nom . '%');
    }

    if ($categorie) {
        $qb->andWhere('p.categorie = :categorie')
           ->setParameter('categorie', $categorie);
    }

    return $qb->getQuery()->getResult();
}
*/


}
