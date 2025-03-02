<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\Credit;
use App\Enum\StateEnum;
use App\Factory\ClientFactory;
use App\Helper\ClientHelper;
use App\Repository\ClientRepository;
use Symfony\Component\Config\Definition\Exception\Exception;

class ClientService
{
    private const ALLOWED_AGE_FOR_CREDIT_MIN = 18;
    private const ALLOWED_AGE_FOR_CREDIT_MAX = 60;
    private const ALLOWED_FICO_RATING_FOR_CREDIT = 500;
    private const ALLOWED_SALARY_FOR_CREDIT = 1000;

    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly NotificationService $notificationService,
    ) {}

    public function create(array $data): Client
    {
        $client = ClientFactory::create($data);
        $this->clientRepository->save($client, true);

        return $client;
    }

    public function update(array $data): Client
    {
        $currentEmail = $data['oldEmail'] ?? $data['email'] ?? '';

        if (!$currentEmail) {
            throw new \Exception('email is required');
        }

        $client = $this->clientRepository->findOneBy(['email' => $currentEmail]);

        if ($client === null) {
            throw new \Exception('Client not found');//TODO custom exception
        }
        $client = ClientFactory::create($data, $client);
        $this->clientRepository->save($client, true, true);

        return $client;
    }

    public function getList(): array
    {
        return $this->clientRepository->findAll();
    }

    public function findOne(int $id): Client
    {
        $client = $this->clientRepository->findOneBy(['id' => $id]);

        if ($client === null) {
            throw new \Exception('Client not found');
        }

        return $client;
    }

    public function checkPermissionForCredit(Client $client): bool
    {
        if (!(self::ALLOWED_AGE_FOR_CREDIT_MIN <= $client->getAge()
            && $client->getAge() < self::ALLOWED_AGE_FOR_CREDIT_MAX
            && $client->getFicoRating() > self::ALLOWED_FICO_RATING_FOR_CREDIT
            && $client->getSalary() >= self::ALLOWED_SALARY_FOR_CREDIT)) {
            return false;
        };

        $state = ClientHelper::getClientState($client->getAddress());

        if (!ClientHelper::isAllowedState($state)) {
            return false;
        }

        if ($state === StateEnum::NY->value) {
            return (bool) array_rand([true, false]);//TODO we can change possibility. now is 50%
        }

        return true;
    }

    public function giveCredit(Client $client, Credit $credit)
    {
        if (!$this->checkPermissionForCredit($client)) {
            throw new Exception('Client not allowed to get a credit');
        }

        $this->clientRepository->addCreditToClient($client, $credit);

//        $this->notificationService->sendEmail();//TODO we can add choosing of notification method in client settings
    }

}
