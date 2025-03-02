<?php
declare(strict_types=1);

use App\Controller\IndexController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
//    $routes->add('home', '/')
//        ->controller([ IndexController::class, 'index' ]);

    $routes->import('routes/client_routes.php');
//    $routes->import('routes/location_routes.php');
//    $routes->import('routes/book_routes.php');
};
