<?php

namespace App\Factory;

use App\Entity\Credit;

class CreditFactory
{
    public static function create(array $data, ?Credit $credit = null): Credit
    {
        $credit = $credit ?? new Credit();
        $credit->setName($data['name'] ?? '');
        $credit->setRate($data['rate'] ?? 0);
        $credit->setAmount($data['amount'] ?? 0);
        $credit->setTerm($data['term'] ?? 0);

        return $credit;
    }
}
