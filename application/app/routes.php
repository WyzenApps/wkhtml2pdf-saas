<?php

declare(strict_types=1);

use App\Application\Actions\ActionPreflight;
use Slim\App;

return function (App $app) {
    $app->options('/{routes:.+}', ActionPreflight::class);

    \App\Application\Actions\RootScope\Routes::create($app);
    \App\Application\Actions\Html2PdfScope\Routes::create($app);
    \App\Application\Actions\Tests\Routes::create($app);
};
