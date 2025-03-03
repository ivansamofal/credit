<?php

namespace App\Normalizer;

use App\Entity\Credit;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CreditNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        if (!$object instanceof Credit) {
            return [];
        }

        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'amount' => $object->getAmount(),
            'rate' => $object->getRate(),
            'term' => $object->getTerm(),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Credit;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Credit::class => true,
        ];
    }
}
