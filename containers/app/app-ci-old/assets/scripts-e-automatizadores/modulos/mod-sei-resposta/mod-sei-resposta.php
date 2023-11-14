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

//$conexao = BancoSEI::getInstance();
//$conexao->setBolScript(true);

$MODULO_RESPOSTA_SISTEMA_ID = getenv(' MODULO_RESPOSTA_SISTEMA_ID') ?: 'a:1:{i:0;s:1:"8";}';
$MODULO_RESPOSTA_DOCUMENTO_ID = getenv('MODULO_RESPOSTA_DOCUMENTO_ID') ?: "153";

$objMdRespostaParametroDTO = new MdRespostaParametroDTO();
$objMdRespostaParametroDTO->setStrNome("PARAM_TIPO_DOCUMENTO");
//$objMdRespostaParametroDTO->setStrNome(MDRespostaParametroRN::PARAM_TIPO_DOCUMENTO);
$objMdRespostaParametroDTO->setStrValor($MODULO_RESPOSTA_DOCUMENTO_ID);
$arrObjMdRespostaParametroDTO[] = $objMdRespostaParametroDTO;

$objMdRespostaParametroDTO = new MdRespostaParametroDTO();
//$objMdRespostaParametroDTO->setStrNome(MDRespostaParametroRN::PARAM_SISTEMA);
$objMdRespostaParametroDTO->setStrNome('PARAM_SISTEMA');
$objMdRespostaParametroDTO->setStrValor($MODULO_RESPOSTA_SISTEMA_ID);
$arrObjMdRespostaParametroDTO[] = $objMdRespostaParametroDTO;

$objMdRespostaParametroRN = new MdRespostaParametroRN();
try{
    $objMdRespostaParametroDTO = $objMdRespostaParametroRN->atribuir($arrObjMdRespostaParametroDTO); 
}catch(Exception $e){
    echo "Erro ao configurar modulo de resposta. Verifique. Nao vamos interromper a execucao.";
    echo "Erro: " . print_r($e, true);
    echo "";
}


?>