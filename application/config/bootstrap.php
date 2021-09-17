<?php

/***
 *    ██╗███╗   ██╗██╗████████╗██╗ █████╗ ██╗     ██╗███████╗ █████╗ ████████╗██╗ ██████╗ ███╗   ██╗
 *    ██║████╗  ██║██║╚══██╔══╝██║██╔══██╗██║     ██║██╔════╝██╔══██╗╚══██╔══╝██║██╔═══██╗████╗  ██║
 *    ██║██╔██╗ ██║██║   ██║   ██║███████║██║     ██║███████╗███████║   ██║   ██║██║   ██║██╔██╗ ██║
 *    ██║██║╚██╗██║██║   ██║   ██║██╔══██║██║     ██║╚════██║██╔══██║   ██║   ██║██║   ██║██║╚██╗██║
 *    ██║██║ ╚████║██║   ██║   ██║██║  ██║███████╗██║███████║██║  ██║   ██║   ██║╚██████╔╝██║ ╚████║
 *    ╚═╝╚═╝  ╚═══╝╚═╝   ╚═╝   ╚═╝╚═╝  ╚═╝╚══════╝╚═╝╚══════╝╚═╝  ╚═╝   ╚═╝   ╚═╝ ╚═════╝ ╚═╝  ╚═══╝
 *
 */

use App\Assets\Dotenv as AssetsDotenv;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Load config file from Environment variable
 * dev - environnement local ou serveur de dev
 * staging - environnement de pre-production : Simulation avant la mise en prodcution
 * production - environnement de production
 */
$appConfig = false;

$dotenv = new Dotenv(false);

define('__ROOT_APP__', realpath(__DIR__ . '/..'));

/** @var Dotenv */
try {
    if (\file_exists(__DIR__ . '/.env')) {
        $dotenv->overload(__DIR__ . '/.env.core', __DIR__ . '/.env');
    } else {
        $dotenv->overload(__DIR__ . '/.env.core');
    }

    /**
     * SI ENVIR FORCED, ON ECRASE ENVIR <- ENVIR_FORCED
     */
    if (AssetsDotenv::getenv('ENVIR_FORCED') != '') {
        $_ENV['ENVIR'] = AssetsDotenv::getenv('ENVIR_FORCED');
    }
    AssetsDotenv::required([
        'ENVIR',
        'APP_NAME',
        'APP_CODE'
    ]);
} catch (\Exception $ex) {
    throw new \Exception("core.invalid_config_file: " . $ex->getMessage(), 403);
}

/**
 * Test si la variable d'environnement est définie
 */
if (false === in_array(AssetsDotenv::getenv('ENVIR') ?: '', ['test', 'local', 'staging', 'production'])) {
    throw new \Exception("core.environment_not_defined", 403);
}
