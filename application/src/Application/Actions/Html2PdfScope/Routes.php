<?php

declare(strict_types=1);

namespace App\Application\Actions\Html2PdfScope;

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
            $routes->post('', Html2PdfAction::class)->setName('post_html2pdf');
        })->add(AuthMiddleware::class);

        $app->group('/to-image', function (RouteCollectorProxyInterface $routes) {
            // Send
            $routes->post('', Html2ImageAction::class)->setName('post_html2img');
        })->add(AuthMiddleware::class);
    }
}
