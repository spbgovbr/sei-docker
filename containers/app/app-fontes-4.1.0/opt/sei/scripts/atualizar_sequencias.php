<?
try{

  require_once dirname(__FILE__).'/../web/SEI.php';

  session_start();

  SessaoSEI::getInstance(false);

  InfraScriptVersao::solicitarAutenticacao(BancoSEI::getInstance());

  $objScriptRN = new ScriptRN();
  $objScriptRN->atualizarSequencias();

}catch(Exception $e){
  echo(InfraException::inspecionar($e));
  try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));      }catch (Exception $e){}
}
?>