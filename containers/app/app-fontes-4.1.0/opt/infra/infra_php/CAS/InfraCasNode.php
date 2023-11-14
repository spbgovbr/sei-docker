<?php

class InfraCasNode
{
    public $active = true;
    private $lastfailtime;
    private $timeout = 1;

    /** @var string Variável contendo uma representação no formato URI de acesso a um nó Sawrm ou um SCSPProxy. */
    public $url;

    /**
     * Construtor indicando a URL para este nó assim como o timeout (minutos) antes de tentar de tirar do estado de falha.
     *
     * @param string $url - URL indicando um nó do Swarm ou SCSPProxy
     *
     * @param int $timeout - Número de minutos antes que o nó em estado de falha possa ser reavaliado para entrar em operação
     *
     **/
    function __construct($url, $timeout = 1)
    {
        $this->url = $url;
        $this->timeout = $timeout;
    }

    /**
     * Função que detecta se este nó está válido ou não. Caso o nó esteja marcado como inativo ele irá verificar se já passou o 'timeout' e colocar-lo como ativo.
     *
     * @return bool True indica que o nó é válido
     *
     **/
    public function isValid()
    {
        if ($this->active == false) {
            $now = new DateTime();
            $diff = $now->getTimestamp() - $this->lastfailtime->getTimestamp();

            if ($diff >= $this->timeout * 60) {
                $this->active = true;
            }
        }

        return $this->active;
    }

    /**
     * Marcar o nó como não ativo, normalmente associado a um erro na gravação na URL informada.
     *
     **/
    public function fail()
    {
        $this->active = false;
        $this->lastfailtime = new DateTime();
    }
}
