<?php

namespace App\Http\View\DocsHelper;

use TRF4\UI\Unserialize;

/**
 * Class TestClass
 */
class FormTestClass extends TestClass
{
    public function getPhpServerCode(string $httpMethod)
    {
        return
            $this->buildCodePrefix() .
            $this->showcaser->getCodeFromMethod('retrieveValue' . $httpMethod);
    }

    private function buildCodePrefix(): string
    {
        return 'use ' . Unserialize::class . ';' . PHP_EOL . PHP_EOL;
    }
}