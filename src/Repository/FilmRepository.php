<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    /**
     * @return int|mixed|string
     */
    public function getFilms()
    {
        $query = $this->createQueryBuilder('f');
        $query->select('f.id', 'f.nom', 'f.synopsis', 'f.date');

        return $query->getQuery()->getResult();
    }

    /**
     * @param $id
     * @return int|mixed|string
     */
    public function getById($id)
    {
        $query = $this->createQueryBuilder('f');
        $query->select('f.id', 'f.nom', 'f.synopsis', 'f.date')
            ->andWhere('f.id = :val')
            ->setParameter('val', $id);

        return $query->getQuery()->getResult();
    }
}
