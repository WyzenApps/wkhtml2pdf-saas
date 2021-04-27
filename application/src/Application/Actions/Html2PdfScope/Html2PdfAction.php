<?php

declare(strict_types=1);

namespace App\Application\Actions\Html2PdfScope;

use App\Application\Actions\ActionAbstract;
use Psr\Http\Message\ResponseInterface as Response;

class Html2PdfAction extends ActionAbstract
{
    /**
     * Création des datas pour generer doc api
     *
     * @return Response
     */
    protected function action(): Response
    {
        $url     = $this->resolveData('url', false);
        $html    = $this->resolveData('html', false);
        $options = $this->resolveData('options') ?: [];

        if (!$url && !$html) {
            throw new \Exception("Bad parameter. Need url or html parameter");
        }

        /**
         * [
         *   url: <url>
         *   html: <html>
         *   params: [<params>]
         * ]
         */

        try {
            if ($url) {
                $uc = new \App\UseCases\Html2Pdf\GenerateUriToPdf($this->getContainer());
            } elseif ($html) {
                $uc = new \App\UseCases\Html2Pdf\GenerateHtmlToPdf($this->getContainer());
            }
            $result = $uc($url, $options);
        } catch (\Exception $ex) {
            die($ex->getMessage());
        }
        return $this->respondPdf($result);
    }
}
