<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app): void {
    $app->options('/{routes:.*}', fn(Request $request, Response $response): \Psr\Http\Message\ResponseInterface =>
        // CORS Pre-Flight OPTIONS Request Handler
        $response);

    $app->get('/', function (Request $request, Response $response): \Psr\Http\Message\ResponseInterface {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group): void {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
