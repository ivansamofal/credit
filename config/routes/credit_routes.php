<?php
declare(strict_types=1);

use App\Controller\Api\CreditController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add(
        'api_v1_credits_list',
        '/api/v1/credits'
    )
        ->controller([ CreditController::class, 'index' ])
        ->methods([ 'GET' ]);

    $routes->add(
        'api_v1_credit_get',
        '/api/v1/credits/{id}'
    )
        ->controller([ CreditController::class, 'get' ])
        ->methods([ 'GET' ]);

    $routes->add(
        'api_v1_client_create',
        '/api/v1/credits'
    )
        ->controller([ CreditController::class, 'create' ])
        ->methods([ 'POST' ]);
};
