<?php

declare(strict_types=1);

use App\Application\Middleware\AuthMiddleware;
use App\Application\Middleware\CorsMiddleware;
use App\Application\Middleware\DefaultMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(AuthMiddleware::class);
    $app->add(CorsMiddleware::class);
    $app->add(DefaultMiddleware::class);
};
