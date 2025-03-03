<?php

namespace App\Tests\Service;

use App\Entity\Client;
use App\Entity\Credit;
use App\Enum\StateEnum;
use App\Helper\ClientHelper;
use App\Service\ClientService;
use App\Repository\ClientRepository;
use App\Service\NotificationService;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class ClientServiceTest extends TestCase
{
    private $clientService;
    private $clientRepositoryMock;
    private $notificationService;
    private $logger;

    protected function setUp(): void
    {
        $this->clientRepositoryMock = $this->createMock(ClientRepository::class);
        $this->notificationService = $this->createMock(NotificationService::class);
        $this->logger = $this->createMock(Logger::class);

        $this->clientService = new ClientService($this->clientRepositoryMock, $this->notificationService, $this->logger);
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

    public function testGiveCredit()
    {
        $client = (new Client())
            ->setSurname('Ivanov')
            ->setName('Ivan')
            ->setFicoRating(700)
            ->setAge(20)
            ->setSalary(2000)
            ->setAddress('New York, some street CA 42452 USA');

        $credit = (new Credit())
            ->setRate(10)
            ->setTerm(5)
            ->setAmount(1000);

        $this->clientService = $this->getMockBuilder(ClientService::class)
            ->setConstructorArgs([$this->clientRepositoryMock, $this->notificationService, $this->logger])
            ->onlyMethods(['checkPermissionForCredit'])
            ->getMock();

        $this->clientService->method('checkPermissionForCredit')->willReturn(true);

        $this->clientRepositoryMock->expects($this->once())
            ->method('addCreditToClient')
            ->with($client, $credit);

        $this->notificationService->expects($this->once())
            ->method('sendEmail')
            ->with($client)
            ->willThrowException(new \Exception('SMTP Error'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Error during sending email: SMTP Error'));

        $this->clientService->giveCredit($client, $credit);
    }

    public function testCheckPermissionForCredit_ValidClient_ReturnsTrue()
    {
        $client = $this->createClient(30, 750, 50000, StateEnum::CA->value);
        $this->assertTrue($this->clientService->checkPermissionForCredit($client));
    }

    public function testCheckPermissionForCredit_TooYoung_ReturnsFalse()
    {
        $client = $this->createClient(17, 750, 50000, StateEnum::CA->value);
        $this->assertFalse($this->clientService->checkPermissionForCredit($client));
    }

    public function testCheckPermissionForCredit_TooOld_ReturnsFalse()
    {
        $client = $this->createClient(70, 750, 50000, StateEnum::CA->value);
        $this->assertFalse($this->clientService->checkPermissionForCredit($client));
    }

    public function testCheckPermissionForCredit_LowFicoRating_ReturnsFalse()
    {
        $client = $this->createClient(30, 500, 50000, StateEnum::CA->value);
        $this->assertFalse($this->clientService->checkPermissionForCredit($client));
    }

    public function testCheckPermissionForCredit_LowSalary_ReturnsFalse()
    {
        $client = $this->createClient(30, 750, 200, StateEnum::CA->value);
        $this->assertFalse($this->clientService->checkPermissionForCredit($client));
    }

    public function testCheckPermissionForCredit_NotAllowedState_ReturnsFalse()
    {
        $client = $this->createClient(30, 750, 50000, StateEnum::TX->value);

        $this->mockClientHelper($client, false);

        $this->assertFalse($this->clientService->checkPermissionForCredit($client));
    }

    public function testCheckPermissionForCredit_ClientFromNY_ReturnsRandomBoolean()
    {
        $client = $this->createClient(30, 750, 50000, StateEnum::NY->value);

        $this->mockClientHelper($client, true);

        $result = $this->clientService->checkPermissionForCredit($client);
        $this->assertIsBool($result);
    }

    private function createClient(int $age, int $ficoRating, int $salary, string $state): Client
    {
        $client = $this->createMock(Client::class);
        $client->method('getAge')->willReturn($age);
        $client->method('getFicoRating')->willReturn($ficoRating);
        $client->method('getSalary')->willReturn($salary);
        $client->method('getAddress')->willReturn("New York, some street $state 42452 USA");

        return $client;
    }

    private function mockClientHelper(Client $client, bool $isAllowed)
    {
        $state = ClientHelper::getClientState($client->getAddress());

        $this->assertSame($isAllowed, ClientHelper::isAllowedState($state));
    }
}
