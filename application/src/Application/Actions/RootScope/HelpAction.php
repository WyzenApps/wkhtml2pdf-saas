<?php

declare(strict_types=1);

namespace App\Application\Actions\RootScope;

use App\Application\Actions\ActionAbstract;
use App\Factory\WkHtml2PdfFactory;
use Psr\Http\Message\ResponseInterface as Response;

class HelpAction extends ActionAbstract
{
    /**
     * Création des datas pour generer doc api
     *
     * @return Response
     */
    protected function action(): Response
    {

        $wk = WkHtml2PdfFactory::create('pdf');

        $options_pdf   = \implode("\n", \array_map(function ($val) {
            return '<li>' . $val;
        }, \array_keys($wk->getOptions())));
        $wk            = WkHtml2PdfFactory::create('image');
        $options_image = \implode("\n", \array_map(function ($val) {
            return '<li>' . $val;
        }, \array_keys($wk->getOptions())));

        $html = <<<HTML
<h1>HTML to PDF service</h1>
Service based to wkhtmltox. See parameters
<h2>Usage</h2>
Use POST method.
<p>Usage: [POST] https://myservicePDF</p>
<p>Header: Authorization Bearer token JWT</p>

<p>POST parameters (JSON):
<pre><code>
{
    "url": "https://www.google.com",
    "html": "<strong>PDF from html code inline</strong>",
    "options":{
        "common":{
        "javascript-delay" : 1000,
        "no-stop-slow-scripts": true
        },
    "pdf":{
        "title": "Html to Pdf Generator",
        "orientation": "Portrait",
        "header-center" : "From Html to Pdf Generator",
        "footer-center" : "from Wyzen"
        },
    "image":{
        "format": "png"
        }
    }
}
</code></pre>
</p>
<h2>PDF parameters</h2>
<ul>$options_pdf</ul>
<h2>IMAGE parameters</h2>
<ul>$options_pdf</ul>
HTML;

        return $this->respondHtml($html);
    }
}
