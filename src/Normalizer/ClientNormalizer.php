<?php

namespace App\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Client;

class ClientNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        if (!$object instanceof Client) {
            return [];
        }

        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'surname' => $object->getSurname(),
            'age' => $object->getAge(),
            'address' => $object->getAddress(),
            'email' => $object->getEmail(),
            'ssn' => $object->getSsn(),
            'ficoRating' => $object->getFicoRating(),
            'phone' => $object->getPhone(),
            'salary' => $object->getSalary(),
            //todo add client credits
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Client;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Client::class => true,
        ];
    }
}
