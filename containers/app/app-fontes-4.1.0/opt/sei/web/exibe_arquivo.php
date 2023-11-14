<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
*
* 17/07/2012 - criado por mkr
*
*
* Versгo do Gerador de Cуdigo:1.6.1
*/
try {
  require_once dirname(__FILE__).'/SEI.php';
  
  session_start(); 
  
  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////
      
  SessaoSEI::getInstance()->validarLink(); 
  
  SessaoSEI::getInstance()->validarAuditarPermissao($_GET['acao']);
  
  switch($_GET['acao']){ 
  	  	
    case 'exibir_arquivo':     

      if (isset($_GET['nome_download'])) {
        $strNomeDownload = $_GET['nome_download'];
        $strContentDisposition = 'attachment';
      }else{
        $strNomeDownload = null;
        $strContentDisposition = 'inline';
      }

      $bolOriginal = (isset($_GET['original']) && $_GET['original']=='1');

      SeiINT::download(null, null, $_GET['nome_arquivo'], $strNomeDownload, $strContentDisposition, null, null, $bolOriginal);

      break;
     
    default:
      throw new InfraException("Aзгo '".$_GET['acao']."' nгo reconhecida.");
  }
  
}catch(Exception $e){
  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  PaginaSEI::getInstance()->processarExcecao($e);
}
?>