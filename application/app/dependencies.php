<?php

declare(strict_types=1);

use App\Assets\CacheResults;
use App\Assets\Dotenv;
use App\Assets\YamlConfig;
use DI\ContainerBuilder;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Monolog\Logger as MonologLogger;
use Monolog\Processor\PsrLogMessageProcessor;
use Symfony\Component\VarDumper\VarDumper;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions(
        [
            'config' => function (ContainerInterface $c) {
                $config = new YamlConfig(Dotenv::getenv('DATA_DIR') . '/config.yml');
                return $config;
            },
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
