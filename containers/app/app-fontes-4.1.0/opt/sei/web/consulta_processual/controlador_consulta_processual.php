<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
*/
try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoConsultaProcessual::getInstance()->validarLink();

  infraTratarErroFatal(SessaoConsultaProcessual::getInstance(),'controlador_consulta_processual.php?acao=infra_erro_fatal_logar');


  switch($_GET['acao']) {

    case 'consulta_processual_pesquisar';
    case 'consulta_processual_voltar';
      require_once 'consulta_processual_pesquisar.php';
      break;

    case 'consulta_processual_resultado':
      require_once 'consulta_processual_resultado.php';
      break;

    case 'consulta_processual_processo':
      require_once 'processo_consulta_processual.php';
      break;

    case 'consulta_processual_documento':
      require_once 'documento_consulta_processual.php';
      break;

    default:
      throw new InfraException('Ação \''.$_GET['acao'].'\' não reconhecida pelo controlador.');
  }

}catch(Throwable $e){
  PaginaConsultaProcessual::getInstance()->processarExcecao($e);
}
