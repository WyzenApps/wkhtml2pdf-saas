<?php

declare(strict_types=1);

use App\Application\Middleware\DefaultMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(DefaultMiddleware::class);
};
