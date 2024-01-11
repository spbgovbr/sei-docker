<?
try{

  require_once dirname(__FILE__).'/../web/Sip.php';

  session_start();

  SessaoSip::getInstance(false);

  $objScriptRN = new ScriptRN();
  $objScriptRN->atualizarSequencias();

}catch(Exception $e){
  echo(InfraException::inspecionar($e));
  try{LogSip::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
}
?>