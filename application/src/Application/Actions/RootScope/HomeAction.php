<?php

declare(strict_types=1);

namespace App\Application\Actions\RootScope;

use App\Application\Actions\ActionAbstract;
use App\Factory\WkHtml2PdfFactory;
use Psr\Http\Message\ResponseInterface as Response;

class HomeAction extends ActionAbstract
{
    /**
     * Création des datas pour generer doc api
     *
     * @return Response
     */
    protected function action(): Response
    {
        $html = <<<HTML
<h1>HTML to PDF service</h1>
Generate PDF from url or html code inline
HTML;
        return $this->respondHtml($html);
    }
}
