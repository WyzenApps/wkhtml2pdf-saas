<?php

declare(strict_types=1);

namespace App\Application\Actions\RootScope;

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
        // Help page
        $app->get('/', HomeAction::class)->setName('home');
        $app->get('/parameters', HelpAction::class)->setName('help.parameters');
        return $app;
    }
}
