<?php

declare(strict_types=1);

namespace App\Application\Actions\RootScope;

use App\Application\Actions\ActionAbstract;
use Psr\Http\Message\ResponseInterface as Response;

class RootAction extends ActionAbstract
{
    /**
     * Création des datas pour generer doc api
     *
     * @return Response
     */
    protected function action(): Response
    {
        $html = <<<HTML
<h2>HTML to PDF service</h2>
<p>Usage: [POST] https://localhost</p>
<p>
Params:
<ul style="">
    <li>url : Site url
    <li>url_queries:
    <li>url_data:
</ul>
</p>
HTML;
        return $this->respondHtml($html);
    }
}
