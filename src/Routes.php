<?php

use App\Domain\UseCase\GetMovementRanking;
use App\Infrastructure\Http\Controller\MovementController;
use App\Infrastructure\Repository\MovementRepository;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$repository = new MovementRepository();
$useCase = new GetMovementRanking($repository);
$movementController = new MovementController($useCase);

$routes->add('ranking', new Route('/ranking', [
    '_controller' => [$movementController, 'ranking'],
    '_route_params' => [
        '_method' => ['GET'],
    ],
]));

return $routes;