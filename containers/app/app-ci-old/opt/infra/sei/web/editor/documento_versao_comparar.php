<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4Њ REGIУO
*
* 13/11/2015 - criado por bcu
*/

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  PaginaSEI::getInstance()->setBolAutoRedimensionar(false);

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']) {

    case 'documento_versao_comparar':

      $objEditorDTO = new EditorDTO();
      $objEditorDTO->setDblIdDocumento($_GET['id_documento']);
      $objEditorDTO->setNumIdBaseConhecimento(null);
      $objEditorDTO->setStrSinCabecalho('S');
      $objEditorDTO->setStrSinRodape('S');
      $objEditorDTO->setStrSinCarimboPublicacao('N');
      $objEditorDTO->setStrSinIdentificacaoVersao('N');
      $objEditorDTO->setStrSinProcessarLinks('N');

      $arr=PaginaSEI::getInstance()->getArrStrItensSelecionados();
      if(count($arr)!=2){
        throw new InfraException('Versѕes para comparaчуo nуo informadas.');
      }
      if($arr[0]>$arr[1]){
        $arr=array_reverse($arr);
      }

      $objEditorDTO->setNumVersao($arr[0]);
      $objEditorDTO->setNumVersaoComparacao($arr[1]);

      $objEditorRN = new EditorRN();
      echo($objEditorRN->compararHtmlVersao($objEditorDTO));

      die;

    default:
      throw new InfraException("Aчуo '" . $_GET['acao'] . "' nуo reconhecida.");
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}
?>