<?
try{

  require_once dirname(__FILE__).'/../web/SEI.php';

  session_start();

  SessaoSEI::getInstance(false);

  if ($argc != 2){
    die("USO: ".basename(__FILE__) ." [protocolos processos]\n");
  }

  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->setBolEcho(true);
  InfraDebug::getInstance()->limpar();

  $objIndexacaoDTO = new IndexacaoDTO();
  $objIndexacaoDTO->setStrProtocoloFormatadoPesquisa($argv[1]);

  $objIndexacaoRN = new IndexacaoRN();
  $objIndexacaoRN->gerarIndexacaoProcesso($objIndexacaoDTO);

}catch(Exception $e){
  if ($e instanceof InfraException && $e->contemValidacoes()){
    die(InfraString::excluirAcentos($e->__toString())."\n");
  }

  echo(InfraException::inspecionar($e));

  try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
}
?>