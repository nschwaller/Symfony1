<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * Requete qui me permet de récupérer les produits en fonction de la recherche de l'utilisateur
     * @return Product[]
     */
    public function findWithSearch(Search $search)
    {
        //permet de faire une requête sql
        $query = $this
            //on commence a crée un requete avec la table p (p = product)
            ->createQueryBuilder('p')
            //On slection c = categorie e p = produit
            ->select('c', 'p')
            //Il faut faireune jointure entre les categorie de notre produit et la table catégorie
            ->join('p.category', 'c');

        // si les categories dans search ne sont pas vide (si des checkbox sont coché car categorie correspond au checkbox
        if(!empty($search->categories)) {
            $query = $query
                //on récupère les id des catégories qui se trouve dans les categories coche
                ->andWhere('c.id IN (:categories)')
                //on dit a symfony que categories correspond au case cocher
                ->setParameter('categories', $search->categories);
        }

        //on test si l'utilisateur a rentrer quelque chose dans l'input text
        if(!empty($search->string)){
            $query = $query
                //on compare le nom récupère a tout les noms de produit
                ->andWhere('p.name LIKE :string')
                //On configure string qui correpond a %la recherche% pour que ca aille bien avec un LIKE
                ->setParameter('string', "%{$search->string}%");
        }

        //on retourne le résultat des query
        return $query->getQuery()->getResult();
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
