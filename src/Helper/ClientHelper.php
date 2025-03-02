<?php

namespace App\Helper;

use App\Enum\StateEnum;

class ClientHelper
{
    private const STATE_IN_ADDRESS_PATTERN = '/\b([A-Z]{2})\b(?=\s+\d{5}\s*USA?$)/';

    public static function isAllowedState(string $stateCode): bool
    {
        return in_array(
            $stateCode,
            [StateEnum::CA->value, StateEnum::NY->value, StateEnum::NV->value]
        );
    }

    public static function getClientState(string $address): string
    {
        if (preg_match(self::STATE_IN_ADDRESS_PATTERN, $address, $matches)) {
            if (!empty($matches[1])) {
                return $matches[1];
            }
        }

        throw new \Exception('State not found in address');
    }
}
