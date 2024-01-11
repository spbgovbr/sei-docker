<?
try{

  require_once dirname(__FILE__).'/../web/SEI.php';

  session_start();

  SessaoSEI::getInstance(false);

  if ($argc != 1 && $argc != 2 && $argc != 4){
    die("USO: ".basename(__FILE__) ." [A ou B] [data inicial opcional no formato dd/mm/aaaa] [data final opcional no formato dd/mm/aaaa]\n");
  }


  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->setBolEcho(true);
  InfraDebug::getInstance()->limpar();

  SeiINT::rotinaVerificaoXss($argv[1], $argv[2], $argv[3]);

}catch(Exception $e){
  if ($e instanceof InfraException && $e->contemValidacoes()){
    die(InfraString::excluirAcentos($e->__toString())."\n");
  }

  echo(InfraException::inspecionar($e));

  try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
}
?>