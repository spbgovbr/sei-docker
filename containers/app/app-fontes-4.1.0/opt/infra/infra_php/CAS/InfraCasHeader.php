<?php

class InfraCasHeader
{
    private $key;
    private $value;

    /**
     * Contrustor usado para informar uma chave e valor para ser utilizado quando for enviar metadados para Swarm.
     *
     * @param string $key - Nome da chave, exemplo 'content-type'.
     * @param string $value - Valor da chave informada seguindo os padrões de HTTP 1.1
     *
     **/

    function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
