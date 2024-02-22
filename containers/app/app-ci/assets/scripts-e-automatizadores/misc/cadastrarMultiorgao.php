<?php

/*
cadastra multiorgaos
*/

require_once '/opt/sip/web/Sip.php';

SessaoSip::getInstance(false);
InfraDebug::getInstance()->setBolLigado(true);
InfraDebug::getInstance()->setBolDebugInfra(false);
InfraDebug::getInstance()->setBolEcho(true);

$conexao = BancoSip::getInstance();

$siglas = getenv('APP_ORGAOS_ADICIONAIS_SIGLA');
$nomes =  getenv('APP_ORGAOS_ADICIONAIS_NOME');


if( $siglas == "" || $nomes == "" ){
	
	echo "Siglas ou Nomes dos Orgaos nao informados completamente, pulando etapa de configuracao multiorgaos";
	exit(0);
	
}

$arrSiglas = explode("/", $siglas);
$arrNomes = explode("/", $nomes);

if( count($arrSiglas) != count($arrNomes) ){

	echo "Siglas ou Nomes dos Orgaos nao informados corretamente. Erro na correlacao de sigla/nome.";
	echo "Pulando etapa de configuracao de multiorgaos";
	exit(0);

}

for ($i=0; $i < count($arrSiglas) ; $i++) {
    
	$sigla = $arrSiglas[$i];
	$nome = $arrNomes[$i];
	$ordem = $i + 1;
	
	echo $sigla;
	echo $nome;
	
	$objOrgaoDTO = new OrgaoDTO();
	$objOrgaoDTO->setNumIdOrgao(null);
	$objOrgaoDTO->setStrSigla($sigla);
	$objOrgaoDTO->setStrDescricao($nome);
	$objOrgaoDTO->setNumOrdem($ordem);

	$objOrgaoDTO->setStrSinAutenticar('N');
	$objOrgaoDTO->setStrSinAtivo('S');

	$arrObjRelOrgaoAutenticacaoDTO = array();
	$objOrgaoDTO->setArrObjRelOrgaoAutenticacaoDTO($arrObjRelOrgaoAutenticacaoDTO);

	$objOrgaoRN = new OrgaoRN();
	$objOrgaoDTO = $objOrgaoRN->cadastrar($objOrgaoDTO);
	
}



?>