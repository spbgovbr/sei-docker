<?php


namespace Safe\PhpStanFunctions;

class PhpStanFunctionMapReader
{
    /**
     * @var array<string, array>
     */
    private $functionMap;

    public function __construct()
    {
        $this->functionMap = require __DIR__.'/../../vendor_57/phpstan/phpstan/src/Reflection/SignatureMap/functionMap.php';
    }

    public function hasFunction(string $functionName): bool
    {
        return isset($this->functionMap[$functionName]);
    }

    public function getFunction(string $functionName): PhpStanFunction
    {
        return new PhpStanFunction($this->functionMap[$functionName]);
    }
}
