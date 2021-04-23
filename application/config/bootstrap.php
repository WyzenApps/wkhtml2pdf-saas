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
use Symfony\Component\VarDumper\VarDumper;

/**
 * Load config file from Environment variable
 * dev - environnement local ou serveur de dev
 * staging - environnement de pre-production : Simulation avant la mise en prodcution
 * production - environnement de production
 */
$appConfig = false;

$dotenv = new Dotenv(false);

/** @var Dotenv */
try {
    $dotenv->overload(__DIR__ . '/.env.core', __DIR__ . '/.env');
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
if (empty(AssetsDotenv::getenv('HOST'))) {
    throw new \Exception("core.host_not_defined", 403);
}
