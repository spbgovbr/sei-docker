<?
try{

  require_once dirname(__FILE__).'/../web/SEI.php';

  ini_set('max_execution_time','0');
  ini_set('memory_limit','-1');

  session_start();

  SessaoSEI::getInstance(false);

  if ($argc > 2){
    die("USO: ".basename(__FILE__) ." [data inicial opcional no formato dd/mm/aaaa]\n");
  }


  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->setBolEcho(true);
  InfraDebug::getInstance()->limpar();

  $objEDocRN=new EDocRN();
  $objDocumentoDTO=new DocumentoDTO();

  if (isset($argv[1])) {
    $objDocumentoDTO->setDtaGeracaoProtocolo($argv[1],InfraDTO::$OPER_MENOR_IGUAL);
  }

  echo '['.InfraData::getInstance()->getStrHoraAtual().'] ---- Iniciando migraчуo de dados EDOC -> SEI -----'."\n";
  $objEDocRN->migrar($objDocumentoDTO);







}catch(Exception $e){
  if ($e instanceof InfraException && $e->contemValidacoes()){
    die(InfraString::excluirAcentos($e->__toString())."\n");
  }

  echo(InfraException::inspecionar($e));

  try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
}

?>