<?php

namespace App\UseCases\Html2Pdf;

use App\Dto\ServiceScope\ResponseService;
use App\UseCases\UseCasesAbstract;

class GenerateHtmlToPdf extends UseCasesAbstract
{
    public $api = null;

    /**
     * Undocumented function
     *
     * @param string $uri URL or HTML
     * @param array $options Options of Snappy Pdf
     *
     * @return mixed|null
     */
    public function __invoke(string $html, array $options = [])
    {
        /** @var ResponseService */
        $response = $this->getPdfFromHtml($html, $options);
        return $response['data'];
    }
}
