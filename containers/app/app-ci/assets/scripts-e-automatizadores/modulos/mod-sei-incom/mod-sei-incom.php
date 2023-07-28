<?php

/*
Cadastra as configs do mod resposta
Verifique as variaveis abaixo com gentenv
*/

require_once '/opt/sei/web/SEI.php';

SessaoSEI::getInstance(false);
InfraDebug::getInstance()->setBolLigado(true);
InfraDebug::getInstance()->setBolDebugInfra(false);
InfraDebug::getInstance()->setBolEcho(true);

$MODULO_INCOM_VEICULOID = getenv('MODULO_INCOM_VEICULOID') ?: '2';
$MODULO_INCOM_SERIEID = getenv('MODULO_INCOM_SERIEID') ?: '10';
$MODULO_INCOM_SIORG = getenv('MODULO_INCOM_SIORG') ?: '235876';
$MODULO_INCOM_URLWS = getenv('MODULO_INCOM_URLWS') ?: 'https://seiwsincom2.in.gov.br/seiwsincom/services/servicoIN?wsdl';
$MODULO_INCOM_USERWS = getenv('MODULO_INCOM_USERWS') ?: 'XXX';
$MODULO_INCOM_PASSWS = getenv('MODULO_INCOM_PASSWS') ?: 'XXX';
$MODULO_INCOM_INCLUSAOPUBLICACAO = getenv('MODULO_INCOM_INCLUSAOPUBLICACAO') ?: 'S';

$conexao = BancoSEI::getInstance();
$conexao->abrirConexao();

$objMdIncomConfiguracaoDTO = new MdIncomConfiguracaoDTO();
$objMdIncomConfiguracaoDTO->setNumIdConfiguracao(1);
$objMdIncomConfiguracaoDTO->setNumIdVeiculoPublicacao($MODULO_INCOM_VEICULOID);
$objMdIncomConfiguracaoDTO->setNumIdSerie($MODULO_INCOM_SERIEID);
$objMdIncomConfiguracaoDTO->setNumIdSiorgOrgao($MODULO_INCOM_SIORG);
$objMdIncomConfiguracaoDTO->setStrWebService($MODULO_INCOM_URLWS);
$objMdIncomConfiguracaoDTO->setStrUsuarioWebservice($MODULO_INCOM_USERWS);
$objMdIncomConfiguracaoDTO->setStrSenhaWebservice($MODULO_INCOM_PASSWS);
$objMdIncomConfiguracaoDTO->setStrSinInclusaoPublicacao($MODULO_INCOM_INCLUSAOPUBLICACAO);

try{
    $objMdIncomConfiguracaoBD = new MdIncomConfiguracaoBD($conexao);
    $ret = $objMdIncomConfiguracaoBD->alterar($objMdIncomConfiguracaoDTO);
}catch(Exception $e){
    echo "Erro ao configurar modulo INCOM. Verifique. Nao vamos interromper a execucao.";
    echo "Erro: " . print_r($e, true);
    echo "";
}

?>