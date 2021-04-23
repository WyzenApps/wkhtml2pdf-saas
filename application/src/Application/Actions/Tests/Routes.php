<?php

declare(strict_types=1);

namespace App\Application\Actions\Tests;

use Slim\Interfaces\RouteCollectorProxyInterface;

final class Routes
{
    /**
     * Manage Routes
     *
     * @param RouteCollectorProxyInterface $app
     * @return \Slim\Interfaces\RouteInterface
     */

    public static function create(RouteCollectorProxyInterface $app)
    {
        /**
         * Actions sur test
         */
        $app->group('/tests', function (RouteCollectorProxyInterface $routes) {
        });

        return $app;
    }
}
