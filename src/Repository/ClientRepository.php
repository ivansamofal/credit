<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Credit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry, private readonly  EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Client::class);
    }

    public function save(Client $entity, bool $flush = false, bool $update = false): void
    {
        $criteria = ['email' => $entity->getEmail(), 'ssn' => $entity->getSsn()];

        if (!$update) {
            $criteria = ['ssn' => $entity->getSsn()];
        }

        $dbClient = $this->findOneBy($criteria);

        if ($dbClient) {
            throw new \Exception('Client with this email or SSN already registered');
        }

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Client[] Returns an array of Client objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function addCreditToClient(Client $client, Credit $credit): void
    {
        $client->addCredit($credit);
        $this->entityManager->flush();
    }
}
