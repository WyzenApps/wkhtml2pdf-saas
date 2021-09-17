<?php

declare(strict_types=1);

namespace App\Application\Actions\Html2PdfScope;

use App\Application\Actions\ActionAbstract;
use Psr\Http\Message\ResponseInterface as Response;
use Wyzen\Php\Helper;

class Html2PdfAction extends ActionAbstract
{
    /**
     * Création des datas pour generer doc api
     *
     * @return Response
     */
    protected function action(): Response
    {
        $data           = $this->getFormData();
        $url            = Helper::findInArrayByTag($data, 'url');
        $html           = Helper::findInArrayByTag($data, 'html');
        $html64         = Helper::findInArrayByTag($data, 'html64');
        $options_common = Helper::findInArrayByKeys($data, 'options', 'common') ?: [] ;
        $options_type   = Helper::findInArrayByKeys($data, 'options', 'pdf') ?: [];

        $options = $options_common + $options_type;

        $content = $url ?: $html ?: $html64 ?: null;
        if (!$content) {
            throw new \Exception("Bad parameter. Need url or html parameter");
        }

        if ($html64) {
            $content = \base64_decode($html64);
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
            }
            if ($html) {
                $uc = new \App\UseCases\Html2Pdf\GenerateHtmlToPdf($this->getContainer());
            }
            $result = $uc("$content", $options);
        } catch (\Exception $ex) {
            throw new \Exception("Convert error, verify your source");
            die($ex->getMessage());
        }
        return $this->respondPdf($result);
    }
}
