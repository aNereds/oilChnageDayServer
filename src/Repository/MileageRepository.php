<?php

namespace App\Repository;

use App\Entity\Mileage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mileage>
 *
 * @method Mileage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mileage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mileage[]    findAll()
 * @method Mileage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MileageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mileage::class);
    }

    public function save(Mileage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Mileage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
