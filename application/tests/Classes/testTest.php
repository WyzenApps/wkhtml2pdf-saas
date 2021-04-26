<?php

declare(strict_types=1);

namespace Tests\Classes;

use Tests\TestCaseAbstract;

/**
 * @testdox Test du domaine Env.
 */
class TestTest extends TestCaseAbstract
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

    public function testIfClassExists()
    {
        $this->assertTrue(class_exists(\App\Classes\HtmlToPdf::class));
    }
}
