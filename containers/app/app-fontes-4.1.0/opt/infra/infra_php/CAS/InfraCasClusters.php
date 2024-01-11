<?php

class InfraCasClusters
{
    public $maincluster = null;
    public $readcluster = null;
    public $leituraInicialNoMain = false;

    public $domain = null;

    /**
     * Construtor da classe InfraCasCluster, ela permite manter uma referência a dois clusters:
     *
     *   $maincluster - Cluster principal onde são feitas todas as operações de escrita e em caso de falha ou não disponbilidade do $readcluster as de leitura também.
     *
     *   $readcluster - Cluster, quando existente, usado para ler os objetos. Caso não sejam encontrados os objeto neste cluster ele irá tentar a busca no $maincluster
     *
     **/
    function __construct($maincluster, $readcluster, $leituraInicialNoMain = false, $domain = null)
    {
        $this->maincluster = $maincluster;
        $this->readcluster = $readcluster;
        $this->leituraInicialNoMain = $leituraInicialNoMain;
        $this->domain = $domain;
    }
}
