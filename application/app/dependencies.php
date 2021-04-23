<?php

declare(strict_types=1);

use App\Assets\CacheResults;
use DI\ContainerBuilder;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Monolog\Logger as MonologLogger;
use Monolog\Processor\PsrLogMessageProcessor;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions(
        [
            'logger' => function (ContainerInterface $c) {
                $settings = $c->get('settings');

                $loggerSettings = $settings['logger'];

                /** @var MonologLogger */
                $logger = new MonologLogger('html2pdflogger');
                $logger->info('message');

                $processor = new UidProcessor();
                $logger->pushProcessor(new PsrLogMessageProcessor());
                $logger->pushProcessor($processor);

                return $logger;
            },
            /**
             * Cache pour l'instance courante
             */
            'cache' => function (ContainerInterface $c) {
                return new CacheResults();
            },
        ]
    );
};
