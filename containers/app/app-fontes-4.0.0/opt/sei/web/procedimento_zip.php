<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/06/2012 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';       
  
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])){
    $strParametros .= "&id_procedimento=".$_GET['id_procedimento'];
  }

  if (isset($_GET['id_documento'])){
    $strParametros .= "&id_documento=".$_GET['id_documento'];
  }

  if (isset($_GET['id_documento_edoc'])){
    $strParametros .= "&id_documento_edoc=".$_GET['id_documento_edoc'];
  }

  $arrComandos = array();

  $bolGeracaoOK = false;

  switch($_GET['acao']){

    case 'procedimento_gerar_zip':

      $strTitulo = 'Gerar Arquivo ZIP do Processo';

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retNumIdTipoProcedimento();
      $objProcedimentoDTO->retStrDescricaoProtocolo();
      $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
      $objProcedimentoDTO->setStrSinDocTodos('S');
      $objProcedimentoDTO->setStrSinZip('S');

      $objProcedimentoRN = new ProcedimentoRN();

      $arrObjProcedimentoDTO = $objProcedimentoRN->listarCompleto($objProcedimentoDTO);

      if(count($arrObjProcedimentoDTO) == 1){
        $objProcedimentoDTO = $arrObjProcedimentoDTO[0];
      }

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

      $objDocumentoRN = new DocumentoRN();

      $strThCheck = PaginaSEI::getInstance()->getThCheck();

      $numDocumentos = 0;

      if (InfraArray::contar($objProcedimentoDTO->getArrObjDocumentoDTO())){

        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
        $objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($objProcedimentoDTO->getArrObjDocumentoDTO(), 'IdDocumento'));

        $objProtocoloRN = new ProtocoloRN();
        $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO), 'IdProtocolo');

        $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');

        foreach($objProcedimentoDTO->getArrObjDocumentoDTO() as $objDocumentoDTO){

          $strResultadoDocumentos .= '<tr class="infraTrClara">';
          $strResultadoDocumentos .= '<td align="center" class="infraTd">';

          $strMarcado = 'N';
          if (!isset($_POST['hdnFlagGerar']) || in_array($objDocumentoDTO->getDblIdDocumento(),PaginaSEI::getInstance()->getArrStrItensSelecionados())){
            $strMarcado = 'S';
          }

          if(isset($arrObjProtocoloDTO[$objDocumentoDTO->getDblIdDocumento()]) && $objDocumentoDTO->getStrSinZip()=='S'){
            $strResultadoDocumentos .= PaginaSEI::getInstance()->getTrCheck($numDocumentos++, $objDocumentoDTO->getDblIdDocumento(), $objDocumentoDTO->getStrNomeSerie(),$strMarcado);
          }else{
            $strResultadoDocumentos .= '&nbsp;';
          }
          $strResultadoDocumentos .= '</td>';

          $strResultadoDocumentos .= '<td align="center" class="infraTd">';
          if ($bolAcaoDocumentoVisualizar){
            $strResultadoDocumentos .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento='.$objDocumentoDTO->getDblIdDocumento()) .'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="protocoloNormal" style="font-size:1em !important;">'.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().'</a>';
          }else{
            $strResultadoDocumentos .= $objDocumentoDTO->getStrProtocoloDocumentoFormatado();
          }
          $strResultadoDocumentos .= '</td>';

          $strResultadoDocumentos .= '<td  class="infraTd">';
          $strResultadoDocumentos .= PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie().' '.$objDocumentoDTO->getStrNumero());
          $strResultadoDocumentos .= '</td>';

          $strResultadoDocumentos .= '<td align="center" class="infraTd">';
          $strResultadoDocumentos .= $objDocumentoDTO->getDtaGeracaoProtocolo();
          $strResultadoDocumentos .= '</td>';

          $strResultadoDocumentos .= '</tr>';
        }

        if ($numDocumentos){

          $arrComandos[] = '<button type="button" accesskey="G" name="btnGerar" value="Gerar" onclick="gerar();" class="infraButton"><span class="infraTeclaAtalho">G</span>erar</button>';

          $strResultadoDocumentos = '<table id="tblDocumentos" width="85%" class="infraTable" summary="Lista de documentos disponíveis para geração">
          <caption class="infraCaption" >'.PaginaSEI::getInstance()->gerarCaptionTabela("documentos disponíveis para geração", $numDocumentos).'</caption>
          <tr>
          <th class="infraTh" width="10%">'.$strThCheck.'</th>
          <th class="infraTh" width="15%">Nº SEI</th>
          <th class="infraTh">Documento</th>
          <th class="infraTh" width="15%">Data</th>
          </tr>'.
          $strResultadoDocumentos.
          '</table>';
        }
      }

      if ($_POST['hdnFlagGerar']=='1'){
        try{
          
          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO = $objDocumentoRN->gerarZip(InfraArray::gerarArrInfraDTO('DocumentoDTO','IdDocumento',PaginaSEI::getInstance()->getArrStrItensSelecionados()));
          $bolGeracaoOK = true;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.$strAncora).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){   
  <?if(PaginaSEI::getInstance()->isBolArvore()){?>
	  parent.parent.parent.infraOcultarAviso();	  
	<?}?>
   
  <?if ($bolGeracaoOK){ ?>  
    window.open('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=exibir_arquivo&nome_arquivo='.InfraUtil::formatarNomeArquivo($objAnexoDTO->getStrNome()).'&nome_download='.InfraUtil::formatarNomeArquivo('SEI-'.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().'.zip').'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);?>');
  <?}?>

 infraEfeitoTabelas();
}

function gerar() {

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return;
  }

 <?if(PaginaSEI::getInstance()->isBolArvore()){?>
   parent.parent.parent.infraExibirAviso(false);
 <?}else{?>
   infraExibirAviso(false);
 <?}?>
  
 document.getElementById('hdnFlagGerar').value = '1';
 document.getElementById('frmProcedimentoPdf').submit();
}

function OnSubmitForm() {
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<form id="frmProcedimentoPdf" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&acao_retorno='.PaginaSEI::getInstance()->getAcaoRetorno().$strParametros)?>">
<?
 //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
 PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
 //PaginaSEI::getInstance()->montarAreaValidacao();
 if ($numDocumentos){
   PaginaSEI::getInstance()->montarAreaTabela($strResultadoDocumentos, $numDocumentos);
   PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
 }else{
 	 echo '<label>Nenhum documento disponível para geração.</label>';
 }
?>
 <input type="hidden" id="hdnFlagGerar" name="hdnFlagGerar" value="0" />
</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>