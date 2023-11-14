<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
*
* 24/08/2007 - criado por mga
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
  
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']) {

    case 'documento_download_anexo':
    case 'procedimento_download_anexo':
    case 'base_conhecimento_download_anexo':
    case 'projeto_download_anexo':
    case 'anexo_download':

      $objAnexoRN = new AnexoRN();
      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->retDblIdProtocolo();
      $objAnexoDTO->retStrNome();
      $objAnexoDTO->retDblIdProtocolo();
      $objAnexoDTO->retDthInclusao();
      $objAnexoDTO->retNumTamanho();
      $objAnexoDTO->retStrHash();

      $objAnexoDTO->setNumIdAnexo($_GET['id_anexo']);

      $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);

      if ($objAnexoDTO == null) {
        throw new InfraException('Anexo nгo encontrado.',null, null, false);
      }

      if ($_GET['acao'] == 'anexo_download' && $objAnexoDTO->getDblIdProtocolo()!=null){
        throw new InfraException('Aзгo invбlida para este anexo.');
      }

      $dblIdDocumento = '';
      $strIdentificacao = '';

      if ($_GET['acao'] == 'documento_download_anexo') {

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retDblIdProcedimento();
        $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
        $objDocumentoDTO->retStrStaProtocoloProtocolo();
        $objDocumentoDTO->retStrSinBloqueado();
        $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
        $objDocumentoDTO->retStrStaDocumento();
        $objDocumentoDTO->setDblIdDocumento($objAnexoDTO->getDblIdProtocolo());

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
        $objDocumentoRN->bloquearConsultado($objDocumentoDTO);

        $objAuditoriaProtocoloDTO = new AuditoriaProtocoloDTO();
        $objAuditoriaProtocoloDTO->setStrRecurso($_GET['acao']);
        $objAuditoriaProtocoloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objAuditoriaProtocoloDTO->setDblIdProtocolo($objAnexoDTO->getDblIdProtocolo());
        $objAuditoriaProtocoloDTO->setNumIdAnexo($objAnexoDTO->getNumIdAnexo());
        $objAuditoriaProtocoloDTO->setNumVersao(null);
        $objAuditoriaProtocoloDTO->setDtaAuditoria(InfraData::getStrDataAtual());

        $objAuditoriaProtocoloRN = new AuditoriaProtocoloRN();
        $objAuditoriaProtocoloRN->auditarVisualizacao($objAuditoriaProtocoloDTO);

        $dblIdDocumento = $objDocumentoDTO->getDblIdDocumento();
        $strIdentificacao = $objDocumentoDTO->getStrProtocoloDocumentoFormatado();

        ProtocoloINT::adicionarProtocoloVisitado($objAnexoDTO->getDblIdProtocolo());

      } else {
        AuditoriaSEI::getInstance()->auditar($_GET['acao']);
      }

      //vindo de qualquer outro ponto que nгo seja a бrvore e acessando documentos do processo
      if ($_GET['acao_origem'] != 'procedimento_visualizar' && ($_GET['acao'] == 'procedimento_download_anexo' || $_GET['acao'] == 'documento_download_anexo')) {

        //verifica permissгo de acesso ao documento
        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
        $objPesquisaProtocoloDTO->setDblIdProtocolo($objAnexoDTO->getDblIdProtocolo());

        $objProtocoloRN = new ProtocoloRN();
        $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

        if (count($arrObjProtocoloDTO) == 0) {
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $objAnexoDTO->getDblIdProtocolo()));
          die;
        }

        $dblIdDocumento = $arrObjProtocoloDTO[0]->getDblIdProtocolo();
        $strIdentificacao = $arrObjProtocoloDTO[0]->getStrProtocoloFormatado();
      }

      $strContentDisposition = 'inline';

      if ((isset($_GET['download']) && $_GET['download']=='1')) {

        $strContentDisposition = 'attachment';

      }else if (PaginaSEI::getInstance()->isBolIpad() || PaginaSEI::getInstance()->isBolIphone() || PaginaSEI::getInstance()->isBolAndroid()){

        $arrStrNome = explode(".", $objAnexoDTO->getStrNome());

        if (count($arrStrNome) > 1){
          $strExtensao = str_replace(' ','',InfraString::transformarCaixaBaixa($arrStrNome[count($arrStrNome)-1]));
          if (!in_array($strExtensao, array('htm', 'html', 'png', 'jpg', 'jpeg', 'gif', 'txt'))){
            $strContentDisposition = 'attachment';
          }
        }else{
          $strContentDisposition = 'attachment';
        }
      }

      $bolOriginal = false;
      if (isset($_GET['original']) && $_GET['original']=='1'){
        $bolOriginal = true;
      }

      SeiINT::download($objAnexoDTO, null, null, null, $strContentDisposition, $strIdentificacao, $dblIdDocumento, $bolOriginal);

      break;
     
    default:
      throw new InfraException("Aзгo '".$_GET['acao']."' nгo reconhecida.");
  }
  
}catch(Throwable $e){
  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  PaginaSEI::getInstance()->processarExcecao($e);
}
?>