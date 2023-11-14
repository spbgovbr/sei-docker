<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/06/2006 - criado por MGA
 *
 * @package infra_php
 */


abstract class InfraRelatorio
{

    public function __construct()
    {
    }

    public function gerarTelaRelatorio($objInfraPagina, $objInfraSessao, $objInfraIBanco)
    {
        infraAdicionarPath(dirname(__FILE__) . '/relatorio');
        PaginaRelatorio::setObjInfraPagina($objInfraPagina);
        SessaoRelatorio::setObjInfraSessao($objInfraSessao);
        BancoRelatorio::setObjInfraIBanco($objInfraIBanco);
        require_once dirname(__FILE__) . '/relatorio/relatorio_lista.php';
    }
}

