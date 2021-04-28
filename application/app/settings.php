<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;
use App\Assets\Dotenv;
use App\Assets\YamlConfig;

return function (ContainerBuilder $containerBuilder) {
    $isDebugMode = Dotenv::isDebugMode();
    // Global Settings Object
    $containerBuilder->addDefinitions([

        /**
         * Permet de récupérer le $app de l'application
         */
        'app' => function ($c) {
            global $app;
            return $app;
        },

        /**A
         * Settings
         */
        'isDebugMode' => $isDebugMode,
        'settings' => [
            'envir' => Dotenv::getenv('ENVIR'),
            'debug' => $isDebugMode,
            'appPath' => realpath(__DIR__ . '/../'),

            // Should be set to false in production
            'displayErrorDetails' => $isDebugMode,
            'logger' => [
                'name' => Dotenv::getenv('APP_CODE'),
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../var/logs',
                'filename' => Dotenv::getenv('APP_CODE') . '.log',
                'level' => $isDebugMode ? Logger::DEBUG : Logger::ERROR
            ],
        ],
    ]);
};
