<?php

declare(strict_types=1);

namespace Tests\Classes;

use App\Classes\WkHtml2Image;
use App\Classes\WkHtml2Pdf;
use App\Factory\WkHtml2PdfFactory;
use Symfony\Component\VarDumper\VarDumper;
use Tests\TestCaseAbstract;

/**
 * @testdox Test du domaine Env.
 */
class WkHtml2PdfTest extends TestCaseAbstract
{
    /**
     * Data
     *
     * @return array
     */
    public function dataProvider(): array
    {
        return [];
    }

    /**
     * @testdox Classe exists
     *
     * @return void
     */
    public function testIfClassExists()
    {
        $this->assertTrue(class_exists(\App\Classes\WkHtml2Pdf::class));
    }

    /**
     * @testdox Create Object Instance Of WkHtml2Pdf
     *
     * @return void
     */
    public function testCreateObjectInstanceOfWkHtml2Pdf()
    {
        $h2p = WkHtml2PdfFactory::create();
        $this->assertInstanceOf(WkHtml2Pdf::class, $h2p);

        $h2p = WkHtml2PdfFactory::create('pdf');
        $this->assertInstanceOf(WkHtml2Pdf::class, $h2p);

        $h2p = WkHtml2PdfFactory::create('image');
        $this->assertInstanceOf(WkHtml2Image::class, $h2p);
    }

    public function testGetDefaultBinary()
    {
        /** @var WkHtml2Pdf */
        $h2p      = WkHtml2PdfFactory::create();
        $bin      = $h2p->getBinary();
        $basename = \basename($bin);
        $this->assertEquals('wkhtmltopdf', $basename);

        $h2p      = WkHtml2PdfFactory::create('image');
        $bin      = $h2p->getBinary();
        $basename = \basename($bin);
        $this->assertEquals('wkhtmltoimage', $basename);
    }

    public function testGetOptions()
    {
        /** @var WkHtml2Pdf */
        $h2p = WkHtml2PdfFactory::create();
        $h2p->setOption('orientation', 'landscape');
        $options = $h2p->getOptions();

        $this->assertEquals('landscape', $options['orientation']);
    }

    public function testFormatImage()
    {
        $h2p = WkHtml2PdfFactory::create('image');
        $h2p->setOptions(['format' => 'jpeg']);
        $options = $h2p->getOptions();
        $this->assertEquals('jpeg', $options['format']);

        $h2p->setOptions(['format' => 'png']);
        $options = $h2p->getOptions();
        $this->assertEquals('png', $options['format']);
    }
}
