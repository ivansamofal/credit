<?php

namespace App\Factory;

use App\Entity\Client;

class ClientFactory
{
    public static function create(array $data, ?Client $client = null): Client
    {
        $client = $client ?? new Client();
        $client->setEmail($data['email'] ?? '');
        $client->setSurname($data['surname'] ?? '');
        $client->setName($data['name'] ?? '');
        $client->setAge($data['age'] ?? '');
        $client->setSsn($data['ssn'] ?? '');
        $client->setAddress($data['address'] ?? '');
        $client->setFicoRating($data['fico_rating'] ?? '');
        $client->setPhone($data['phone'] ?? '');
        $client->setSalary($data['salary'] ?? '');
        $client->setPassword($data['password'] ?? '');

        return $client;
    }
}
