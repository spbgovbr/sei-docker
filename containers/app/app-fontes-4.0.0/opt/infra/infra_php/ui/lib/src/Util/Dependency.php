<?php


namespace TRF4\UI\Util;


class Dependency
{

    /**
     * Dependency constructor.
     * @param string $name
     * @param string $inputId
     * @param string $placeholderIfNull
     */
    public function __construct(string $name, string $inputId, string $placeholderIfNull)
    {
        $this->name = $name;
        $this->inputId = $inputId;
        $this->placeholderIfNull = $placeholderIfNull;
    }
}
