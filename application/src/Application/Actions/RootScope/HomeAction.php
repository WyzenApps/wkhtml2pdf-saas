<?php

declare(strict_types=1);

namespace App\Application\Actions\RootScope;

use App\Application\Actions\ActionAbstract;
use Parsedown;
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
        $content  = \file_get_contents(__ROOT_APP__ . '/docs/home.md');
        $markdown = new Parsedown();
        $markdown->setSafeMode(true);
        $markdown->setMarkupEscaped(true);

        $html  = \file_get_contents(__ROOT_APP__ . '/src/Views/html_header.html');
        $html .= "<body>";
        $html .= "<style>" . \file_get_contents(__ROOT_APP__ . '/docs/modest.css') . "</style>";
        $html .= $markdown->parse($content);
        $html .= "</body>";
        $html .= \file_get_contents(__ROOT_APP__ . '/src/Views/html_footer.html');

        return $this->respondHtml($html);
    }
}
