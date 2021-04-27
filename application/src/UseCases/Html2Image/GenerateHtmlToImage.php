<?php

namespace App\UseCases\Html2Image;

use App\Classes\WkHtml2Image;
use App\Factory\WkHtml2PdfFactory;
use App\UseCases\UseCasesAbstract;

class GenerateHtmlToImage extends UseCasesAbstract
{

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
        /** @var WkHtml2Image */
        $wk = WkHtml2PdfFactory::create('image');
        $this->setDefaultOptions($wk, 'image');
        if (count($options)) {
            $wk->setOptions($options);
        }
        if ($this->getConfig('general', 'debug') === true) {
            return '<pre>' . \print_r($wk->getOptions(), true) . '</pre>';
        }
        return $wk->getOutputFromHtml($html);
    }
}
