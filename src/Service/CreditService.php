<?php

namespace App\Service;

use App\Entity\Credit;
use App\Factory\CreditFactory;
use App\Repository\CreditRepository;

class CreditService
{
    public function __construct(private readonly CreditRepository $creditRepository) {}

    public function findOne(int $creditId): Credit
    {
        $credit = $this->creditRepository->findOneBy(['id' => $creditId]);

        if (!$credit) {
            throw new \Exception('Credit not found');
        }

        return $credit;
    }

    public function getList(): array
    {
        return $this->creditRepository->findAll();
    }

    public function create(array $data): Credit
    {
        $creditEntity = CreditFactory::create($data);
        $this->creditRepository->save($creditEntity, true);

        return $creditEntity;
    }
}
