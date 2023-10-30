<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
   
    public function searchBookByRef($searchTerm)
{
    return $this->createQueryBuilder('b')
        ->andWhere('b.ref = :searchTerm')
        ->setParameter('searchTerm', $searchTerm)
        ->getQuery()
        ->getResult();
}

public function booksListByAuthors()
{
    return $this->createQueryBuilder('b')
        ->select('b', 'a')
        ->join('b.authors', 'a')
        ->orderBy('a.username', 'ASC')
        ->getQuery()
        ->getResult();
}



//* public function searchBookByRef($searchTerm)  bi DQL kifeh
//{
//$entityManager = $this->getEntityManager();
    
   // $query = $entityManager->createQuery(
    //    'SELECT b
     //    FROM App\Entity\Book b
     //    WHERE b.ref = :searchTerm'
   // )->setParameter('searchTerm', $searchTerm);

   // return $query->getResult();
// }




//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
