<?php

declare(strict_types=1);

namespace App\Application\Actions\Html2PdfScope;

use App\Application\Actions\RootScope\RootAction;
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
        $app->group('/to-pdf', function (RouteCollectorProxyInterface $routes) {
            // Send
            $routes->get('', Html2PdfAction::class)->setName('html2pdf');
        });

        return $app;
    }
}
