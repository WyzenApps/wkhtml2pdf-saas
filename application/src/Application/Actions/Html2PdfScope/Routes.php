<?php

declare(strict_types=1);

namespace App\Application\Actions\Html2PdfScope;

use App\Application\Actions\RootScope\RootAction;
use App\Application\Middleware\AuthMiddleware;
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
         * Actions
         */
        $app->group('/to-pdf', function (RouteCollectorProxyInterface $routes) {
            // Send
            $routes->get('', Html2PdfAction::class)->setName('html2pdf');
            $routes->post('', Html2PdfAction::class)->setName('html2pdf');
        });

        $app->group('/to-image', function (RouteCollectorProxyInterface $routes) {
            // Send
            $routes->get('', Html2ImageAction::class)->setName('html2img');
            $routes->post('', Html2ImageAction::class)->setName('html2img');
        });

        return $app;
    }
}
