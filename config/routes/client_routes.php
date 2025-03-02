<?php
declare(strict_types=1);

use App\Controller\Api\ClientController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add(
        'api_v1_clients_list',
        '/api/v1/clients'
    )
        ->controller([ ClientController::class, 'index' ])
        ->methods([ 'GET' ]);

    $routes->add(
        'api_v1_client_get',
        '/api/v1/clients/{id}'
    )
        ->controller([ ClientController::class, 'get' ])
        ->methods([ 'GET' ]);

    $routes->add(
        'api_v1_client_permission',
        '/api/v1/clients/{clientId}/permission'
    )
        ->controller([ ClientController::class, 'checkPermission' ])
        ->methods([ 'GET' ]);

    $routes->add(
        'api_v1_client_credit',
        '/api/v1/clients/{clientId}/credit/{creditId}'
    )
        ->controller([ ClientController::class, 'giveCredit' ])
        ->methods([ 'POST' ]);

    $routes->add(
        'api_v1_client_create',
        '/api/v1/clients'
    )
        ->controller([ ClientController::class, 'create' ])
        ->methods([ 'POST' ]);

    $routes->add(
        'api_v1_client_update',
        '/api/v1/clients/{id}'
    )
        ->controller([ ClientController::class, 'update' ])
        ->methods([ 'PUT' ]);

};
