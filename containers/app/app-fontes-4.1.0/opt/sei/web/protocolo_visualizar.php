<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/10/2013 - criado por mga
*
*
* Versão do Gerador de Código:1.6.1
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

  $strLinkProcedimento = '';
  $strLinkDocumento = '';

  switch($_GET['acao']){ 
  	  	
    case 'protocolo_visualizar':

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retStrStaProtocolo();
      $objProtocoloDTO->retDblIdProcedimentoDocumento();
      $objProtocoloDTO->setDblIdProtocolo($_GET['id_protocolo']);
      
      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

      if ($objProtocoloDTO==null){
        throw new InfraException('Protocolo não encontrado.');
      }
      
      if ($objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_PROCEDIMENTO){
        header('Location:'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=protocolo_visualizar&id_procedimento='.$_GET['id_protocolo']));
        die;
      }else{
        if ($_GET['id_procedimento_atual']==$objProtocoloDTO->getDblIdProcedimentoDocumento()) {
          header('Location:'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem=arvore_visualizar&id_documento='.$_GET['id_protocolo']));
          die;
        }else{

          $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
          $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
          $objPesquisaProtocoloDTO->setDblIdProtocolo($_GET['id_protocolo']);
          $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);

          $objProtocoloRN = new ProtocoloRN();
          $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

          if (count($arrObjProtocoloDTO)==0){
            throw new InfraException('Documento não encontrado.');
          }

          $objProtocoloDTO = $arrObjProtocoloDTO[0];

          if ($arrObjProtocoloDTO[0]->getNumCodigoAcesso() < 0){
            header('Location:'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=protocolo_visualizar&id_procedimento='.$_GET['id_protocolo']));
            die;
          }

          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->setStrProtocoloDocumentoFormatado($objProtocoloDTO->getStrProtocoloFormatado());
          $objDocumentoDTO->setStrNomeSerie($objProtocoloDTO->getStrNomeSerieDocumento());
          $strTitulo = DocumentoINT::montarTitulo($objDocumentoDTO);

          $strLinkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=protocolo_visualizar&id_procedimento='.$objProtocoloDTO->getDblIdProcedimentoDocumento().'&id_documento='.$_GET['id_protocolo']);
          $strLinkDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem=arvore_visualizar&id_documento='.$_GET['id_protocolo']);

          $strIdentificacao = '<a id="ancProcesso" href="'.$strLinkProcedimento.'" title="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoDocumento()).'">'.$objProtocoloDTO->getStrProtocoloFormatadoProcedimentoDocumento().'</a>';
          $strAcoes = '<a href="'.$strLinkProcedimento.'" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"><img id="imgArvore" src="'.Icone::ARVORE.'" width="40" height="40" alt="Visualizar Árvore do Processo" title="Visualizar Árvore do Processo"></a>';

          SeiINT::montarCabecalhoConteudo($strIdentificacao, $strAcoes, $strLinkDocumento, $strCss, $strJsInicializar, $strJsCorpo, $strHtml);

        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  
}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
echo '<meta name="viewport" content="width=980" />';
PaginaSEI::getInstance()->montarTitle($strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
echo $strCss;
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
<?=$strJsInicializar?>
}
<?
echo $strJsCorpo;
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
echo $strHtml;
PaginaSEI::getInstance()->fecharHtml();
?>