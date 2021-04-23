<?php

/**
 * Déclaration des repositories pour chaque objet
 * Copie ce fichier pour configurer différents repo suivant l'environnement.
 */

declare(strict_types=1);

use App\Domain\ArteliaScope\ArteliaRepositoryInterface;
use App\Domain\AuthentificationScope\AuthentificationRepositoryInterface;
use App\Domain\CommonScope\Liste\ListeRepositoryInterface;
use App\Domain\CommonScope\Utilisateur\UtilisateurRepositoryInterface;
use App\Domain\CommonScope\UtilisateurProfil\UtilisateurProfilRepositoryInterface;
use App\Domain\EnvScope\EnvRepositoryInterface;
use App\Domain\FileScope\Documents\DocumentsRepositoryInterface;
use App\Domain\FileScope\File\FileRepositoryInterface;
use App\Domain\IeScope\IeRepositoryInterface;
use App\Domain\InfraScope\InfraRepositoryInterface;
use App\Domain\Logger\LoggerRepositoryInterface;
use App\Dto\ArteliaScope\ArteliaDtoRepositoryInterface;
use App\Dto\AuthentificationScope\AuthentificationDtoRepositoryInterface;
use App\Dto\CriticiteScope\CriticiteDtoRepositoryInterface;
use App\Dto\EnvScope\EnvDtoRepositoryInterface;
use App\Dto\IeScope\IeDtoRepositoryInterface;
use App\Dto\InfraScope\InfraDtoRepositoryInterface;
use App\Dto\StationScope\StationDtoRepositoryInterface;
use App\Infrastructure\Persistence\Database\ArteliaScope\ArteliaDtoRepository;
use App\Infrastructure\Persistence\Database\ArteliaScope\ArteliaRepository;
use App\Infrastructure\Persistence\Database\AuthentificationScope\AuthentificationDtoRepository;
use App\Infrastructure\Persistence\Database\AuthentificationScope\AuthentificationRepository;
use App\Infrastructure\Persistence\Database\CommonScope\Liste\ListeRepository;
use App\Infrastructure\Persistence\Database\CommonScope\Utilisateur\UtilisateurRepository;
use App\Infrastructure\Persistence\Database\CommonScope\UtilisateurProfil\UtilisateurProfilRepository;
use App\Infrastructure\Persistence\Database\CriticiteScope\CriticiteDtoRepository;
use App\Infrastructure\Persistence\Database\EnvScope\EnvDtoRepository;
use App\Infrastructure\Persistence\Database\FileScope\Documents\DocumentsRepository;
use App\Infrastructure\Persistence\Database\FileScope\File\FileRepository;
use App\Infrastructure\Persistence\Database\EnvScope\EnvRepository;
use App\Infrastructure\Persistence\Database\IeScope\IeDtoRepository;
use App\Infrastructure\Persistence\Database\IeScope\IeRepository;
use App\Infrastructure\Persistence\Database\InfraScope\InfraDtoRepository;
use App\Infrastructure\Persistence\Database\InfraScope\InfraRepository;
use App\Infrastructure\Persistence\Database\Logger\LoggerRepository;
use App\Infrastructure\Persistence\Database\StationScope\StationDtoRepository;
use DI\ContainerBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions(
        [

        ]
    );
};
