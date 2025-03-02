<?php

namespace App\Tests\Service;

use App\Entity\Client;
use App\Service\ClientService;
use App\Repository\ClientRepository;
use App\Service\NotificationService;
use PHPUnit\Framework\TestCase;

class ClientServiceTest extends TestCase
{
    private $clientService;
    private $clientRepositoryMock;
    private $notificationService;

    protected function setUp(): void
    {
        // Create a mock of ClientRepository
        $this->clientRepositoryMock = $this->createMock(ClientRepository::class);
        $this->notificationService = $this->createMock(NotificationService::class);

        // Inject mock into the service
        $this->clientService = new ClientService($this->clientRepositoryMock, $this->notificationService);
    }

    public function testGetListReturnsClients()
    {
        $client1 = (new Client())->setSurname('Ivanov')->setName('Ivan');
        $client2 = (new Client())->setSurname('Smith')->setName('John');

        $this->clientRepositoryMock->method('findAll')
            ->willReturn([$client1, $client2]);

        $result = $this->clientService->getList();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(Client::class, $result[0]);
        $this->assertInstanceOf(Client::class, $result[1]);
        $this->assertEquals('Ivan', $result[0]->getName());
        $this->assertEquals('John', $result[1]->getName());
    }
}
