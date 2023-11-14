<?
try{

  require_once dirname(__FILE__).'/../web/SEI.php';

  session_start();

  SessaoSEI::getInstance(false);

  if ($argc > 2){
    die("USO: ".basename(__FILE__) ." [data inicial opcional no formato dd/mm/aaaa]\n");
  }


  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->setBolEcho(true);
  InfraDebug::getInstance()->limpar();

  $objAnexoDTO = new AnexoDTO();

  if (isset($argv[1])) {
    $objAnexoDTO->setDthInclusao($argv[1]);
  }else{
    $objAnexoDTO->setDthInclusao(null);
  }

  $objAnexoRN = new AnexoRN();
  $objAnexoRN->verificarRepositorioArquivos($objAnexoDTO);

}catch(Exception $e){
  if ($e instanceof InfraException && $e->contemValidacoes()){
    die(InfraString::excluirAcentos($e->__toString())."\n");
  }

  echo(InfraException::inspecionar($e));

  try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
}
?>