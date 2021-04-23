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
        $app->get('/', RootAction::class)->setName('root');

        // TODO generate pdf
        // $app->post('/', RootAction::class)->setName('html2pdf');

        return $app;
    }
}
