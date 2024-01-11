<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
* 17/03/2011 - CRIADO POR mkr@trf4.gov.br
* Indireção para que o SEI não peça autenticação
*/
try {
  require_once dirname(__FILE__).'/../SEI.php';
  session_start();
  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();

  SessaoPublicacoes::getInstance()->validarLink();

  SessaoPublicacoes::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'publicacao_visualizar':

      $strConteudo = '';
      $objPublicacaoRN = new PublicacaoRN();
      if ($_GET['id_publicacao_legado'] != null){
        $arrIdPublicacoesLegado = explode(",",$_GET['id_publicacao_legado']);
        $objPublicacaoLegadoRN = new PublicacaoLegadoRN();
        $objPublicacaoLegadoDTO = new PublicacaoLegadoDTO();

        $objPublicacaoLegadoDTO->retStrConteudoDocumento();
        $objPublicacaoLegadoDTO->setNumIdPublicacaoLegado($arrIdPublicacoesLegado, InfraDTO::$OPER_IN);
                
        $arrObjPublicacaoLegadoDTO = $objPublicacaoLegadoRN->listar($objPublicacaoLegadoDTO);
        
        foreach($arrObjPublicacaoLegadoDTO as $objPublicacaoLegadoDTO) {
          if ($strConteudo != "") {
            $strConteudo .= "<p class=\"infraQuebraPagina\"></p><br/>";
          }
          if ($objPublicacaoLegadoDTO->isSetStrConteudoDocumento() && $objPublicacaoLegadoDTO->getStrConteudoDocumento() != null) {
            $strConteudo .= str_replace("<p align=\"center\">&nbsp;</p>\r\n<p align=\"center\">&nbsp;</p>\r\n<p align=\"center\">&nbsp;</p>","<br/>", $objPublicacaoLegadoDTO->getStrConteudoDocumento());
          }else{
            echo 'Documento sem conteúdo.';
          }
        }
      }else {
        $arrIdProtocolos = explode(",", $_GET['id_documento']);
        foreach ($arrIdProtocolos as $numIdProtocolo) {
          if ($strConteudo != "") {
            $strConteudo .= "<p class=\"infraQuebraPagina\"></p><br/>";
          }
          $objDocumentoRN = new DocumentoRN();
          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->setDblIdDocumento($numIdProtocolo);
          $objDocumentoDTO->retDblIdDocumentoEdoc();
          $objDocumentoDTO->retNumIdOrgaoUnidadeResponsavel();
          $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
          $objDocumentoDTO->retStrStaDocumento();
          $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
          if ($objDocumentoDTO != null) {
            $objPublicacaoDTO = new PublicacaoDTO();
            $objPublicacaoDTO->retNumIdPublicacao();
            $objPublicacaoDTO->retStrStaEstado();
            $objPublicacaoDTO->setDblIdDocumento($numIdProtocolo);
            $objPublicacaoDTO = $objPublicacaoRN->consultarRN1044($objPublicacaoDTO);
            if ($objPublicacaoDTO != null && $objPublicacaoDTO->getStrStaEstado() == PublicacaoRN::$TE_PUBLICADO) {
              if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_EDOC) {
                if ($objDocumentoDTO->getDblIdDocumentoEdoc() != null) {
                  $strConteudo .= str_replace("&nbsp;", " ", EDocINT::montarVisualizacaoDocumento($objDocumentoDTO->getDblIdDocumentoEdoc())).'<br/><br/>';
                }
              } else if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO) {
                $objEditorRN = new EditorRN();
                $objEditorDTO = new EditorDTO();
                $objEditorDTO->setDblIdDocumento($numIdProtocolo);
                $objEditorDTO->setNumIdBaseConhecimento(null);
                $objEditorDTO->setStrSinCabecalho('S');
                $objEditorDTO->setStrSinRodape('S');
                $objEditorDTO->setStrSinCarimboPublicacao('S');
                $objEditorDTO->setStrSinIdentificacaoVersao('N');
                $strConteudo .= $objEditorRN->consultarHtmlVersao($objEditorDTO).'<br/><br/>';
              }
            } else {
              die('Documento inválido para esta operação.');
            }
          } else {
            die('Documento inválido para esta operação.');
          }
        }
      }

      if ($strConteudo != null) {
        SeiINT::download(null, $strConteudo, null, 'publicacao.html', 'inline', 'publicacao');
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
} catch(Exception $e) {
  PaginaPublicacoes::getInstance()->processarExcecao($e);
}
?>