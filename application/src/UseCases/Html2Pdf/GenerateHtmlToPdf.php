<?php

namespace App\UseCases\Html2Pdf;

use App\Classes\WkHtml2Pdf;
use App\Factory\WkHtml2PdfFactory;
use App\UseCases\UseCasesAbstract;

class GenerateHtmlToPdf extends UseCasesAbstract
{
    /** @var WkHtml2Pdf */
    public $wk = null;

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
        /** @var WkHtml2Pdf */
        $this->wk = WkHtml2PdfFactory::create();
        $this->setDefaultOptions($this->wk, 'pdf');
        if (count($options)) {
            $this->wk->setOptions($options);
        }
        if ($this->getConfig('general', 'debug') === true) {
            return '<pre>' . \print_r($this->wk->getOptions(), true) . '</pre>';
        }
        return $this->wk->getOutputFromHtml($html);
    }
}
