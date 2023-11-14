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

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore','id_procedimento','id_documento','id_documento_edoc'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $arrComandos = array();

  $strLinkArquivo = '';
  $strNomeDownload = '';
  $strNomeArquivo = '';
  $strItensSelDocumentosApenas = '';
  $strDisplayFiltros = '';

  switch($_GET['acao']){

    case 'procedimento_gerar_pdf':

      $dblIdDocumento = (isset($_GET['id_documento']) && $_GET['id_documento']!='') ? $_GET['id_documento'] : null;

      if ($dblIdDocumento == null) {
        $strTitulo = 'Gerar Arquivo PDF do Processo';
      }else{
        $strTitulo = 'Gerar Arquivo PDF do Documento';
      }

      if ($dblIdDocumento!=null){
        $strStaTipo = 'A';
      }else {
        if (isset($_POST['rdoTipo'])) {
          $strStaTipo = $_POST['rdoTipo'];
        } else {
          $strStaTipo = 'T';
        }
      }

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retDblIdProcedimento();
      $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

      if ($objProcedimentoDTO==null){
        throw new InfraException('Processo não encontrado.');
      }

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setStrSinPdf('S');
      $objDocumentoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());

      if ($strStaTipo == 'A') {
        if ($dblIdDocumento!=null){
          $objDocumentoDTO->setDblIdDocumento($dblIdDocumento);
        }else{
          $objDocumentoDTO->setDblIdDocumento(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDocumentosApenas']));
        }
      }

      $objDocumentoRN = new DocumentoRN();
      $arrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarSelecao($objDocumentoDTO),'IdDocumento');

      if ($dblIdDocumento!=null){
        $strDisplayFiltros = 'display:none;';
        if (!isset($arrObjDocumentoDTO[$dblIdDocumento])){
          $objInfraException = new InfraException();
          $objInfraException->lancarValidacao('Não é possível gerar o PDF para este documento.');
        }else {
          $objDocumentoDTO = $arrObjDocumentoDTO[$dblIdDocumento];
          $strTitulo .= ' '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado();
          $strItensSelDocumentosApenas = InfraINT::montarSelectArray(null, null, null, array($objDocumentoDTO->getDblIdDocumento() => DocumentoINT::formatarIdentificacaoComProtocolo($objDocumentoDTO)));
        }
      }

      if ($strStaTipo == 'E') {
        $arrIdSecionados = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDocumentosExceto']);
        foreach ($arrIdSecionados as $dblIdDocumentoSelecionado) {
          if (isset($arrObjDocumentoDTO[$dblIdDocumentoSelecionado])) {
            unset($arrObjDocumentoDTO[$dblIdDocumentoSelecionado]);
          }
        }
      }

      if ($strStaTipo == 'A' && $dblIdDocumento==null) {

        $arrIdSecionados = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDocumentosApenas']);

        //manter ordem
        $arrTemp = array();
        foreach($arrIdSecionados as $dblIdDocumentoSelecionado){
          if (isset($arrObjDocumentoDTO[$dblIdDocumentoSelecionado])){
            $arrTemp[$dblIdDocumentoSelecionado] = $arrObjDocumentoDTO[$dblIdDocumentoSelecionado];
          }
        }
        $arrObjDocumentoDTO = $arrTemp;
        unset($arrTemp);
      }

      $arrComandos[] = '<button type="button" accesskey="G" name="btnGerar" value="Gerar" onclick="gerar();" class="infraButton"><span class="infraTeclaAtalho">G</span>erar</button>';

      if ($_POST['hdnFlagGerar']=='1'){
        try{

          if ($dblIdDocumento==null){
            $objAnexoDTO = $objDocumentoRN->gerarPdf($arrObjDocumentoDTO);
            $strNomeDownload = 'SEI-'.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().'.pdf';
          }else{
            $objAnexoDTO = $objDocumentoRN->gerarPdf($objDocumentoDTO);
            $strNomeDownload = 'SEI-'.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().'-'.$objDocumentoDTO->getStrNomeSerie().(!InfraString::isBolVazia($objDocumentoDTO->getStrNumero())?'-'.$objDocumentoDTO->getStrNumero():'').'.pdf';
          }

          $strLinkArquivo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=exibir_arquivo&nome_arquivo='.InfraUtil::formatarNomeArquivo($objAnexoDTO->getStrNome()).'&nome_download='.InfraUtil::formatarNomeArquivo($strNomeDownload).'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);

          //if ($dblIdDocumento!=null){
          //  header('Location: '.$strLinkArquivo);
          //  die;
          //}

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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
  #divGeral {<?=$strDisplayFiltros?>}

  #divDocumentosExceto {display:none;}
  #selDocumentosExceto {position:absolute;left:0%;top:0%;width:70%;}
  #divOpcoesDocumentosExceto {position:absolute;left:71%;top:0%;}

  #divDocumentosApenas {display:none;}
  #selDocumentosApenas {position:absolute;left:0%;top:0%;width:70%;}
  #divOpcoesDocumentosApenas {position:absolute;left:71%;top:0%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objLupaDocumentosExceto = null;
var objLupaDocumentosApenas = null;

function inicializar(){

  <?if(PaginaSEI::getInstance()->isBolArvore()){?>
	  parent.parent.parent.infraOcultarAviso();	  
	<?}?>

  <?if ($strLinkArquivo!=''){?>
    document.getElementById('ifrDownload').src = '<?=$strLinkArquivo?>';
  <?}?>

  objLupaDocumentosExceto	= new infraLupaSelect('selDocumentosExceto','hdnDocumentosExceto','<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_selecionar&tipo_selecao=2&id_object=objLupaDocumentosExceto&id_procedimento='.$_GET['id_procedimento'].'&tipo_selecao_documento='.DocumentoINT::$TSD_PDF)?>');
  objLupaDocumentosApenas	= new infraLupaSelect('selDocumentosApenas','hdnDocumentosApenas','<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_selecionar&tipo_selecao=2&id_object=objLupaDocumentosApenas&id_procedimento='.$_GET['id_procedimento'].'&tipo_selecao_documento='.DocumentoINT::$TSD_PDF)?>');

  tratarOpcao();
}

function gerar() {

  if (!document.getElementById('optTodos').checked && !document.getElementById('optExceto').checked && !document.getElementById('optApenas').checked){
    alert('Nenhuma opção selecionada.');
    return;
  }

  if (document.getElementById('optApenas').checked && document.getElementById('hdnDocumentosApenas').value==''){
    alert('Nenhum documento selecionado.');
    return;
  }

   parent.parent.parent.infraExibirAviso();

   document.getElementById('hdnFlagGerar').value = '1';
   document.getElementById('frmProcedimentoPdf').submit();
}

function OnSubmitForm() {
  return true;
}

function tratarOpcao(){
  if (document.getElementById('optTodos').checked){
    document.getElementById('divDocumentosExceto').style.display = 'none';
    document.getElementById('divDocumentosApenas').style.display = 'none';
  }else if (document.getElementById('optExceto').checked){
    document.getElementById('divDocumentosExceto').style.display = 'block';
    document.getElementById('divDocumentosApenas').style.display = 'none';
  }else if (document.getElementById('optApenas').checked){
    document.getElementById('divDocumentosExceto').style.display = 'none';
    document.getElementById('divDocumentosApenas').style.display = 'block';
  }
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<form id="frmProcedimentoPdf" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&acao_retorno='.PaginaSEI::getInstance()->getAcaoRetorno())?>">
<?
 //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
 PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
 //PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divGeral" class="infraAreaDados" style="height:15em;">

    <div id="divOptTodos" class="infraDivRadio">
      <input type="radio" name="rdoTipo" id="optTodos" value="T" onclick="tratarOpcao();" <?=($strStaTipo=='T'?'checked="checked"':'')?> class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblTodos" for="optTodos" class="infraLabelRadio">Todos os documentos disponíveis</label>
    </div>

    <div id="divOptExceto" class="infraDivRadio">
      <input type="radio" name="rdoTipo" id="optExceto" value="E" onclick="tratarOpcao();" <?=($strStaTipo=='E'?'checked="checked"':'')?> class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblExceto" for="optExceto" class="infraLabelRadio">Todos exceto selecionados</label>
    </div>

    <div id="divDocumentosExceto" class="infraAreaDados" style="height:11em;">
      <select id="selDocumentosExceto" name="selDocumentosExceto" multiple="multiple" size="6" class="infraSelect"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      </select>
      <div id="divOpcoesDocumentosExceto">
        <img id="imgLupaDocumentosExceto" onclick="objLupaDocumentosExceto.selecionar(700,550);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Documentos" title="Selecionar Documentos" class="infraImg"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgExcluirDocumentosExceto" onclick="objLupaDocumentosExceto.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Documentos Selecionados" title="Remover Documentos Selecionados" class="infraImgNormal"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
      <input type="hidden" id="hdnDocumentosExceto" name="hdnDocumentosExceto" value="<?=$_POST['hdnDocumentosExceto']?>" />
    </div>

    <div id="divOptApenas" class="infraDivRadio">
      <input type="radio" name="rdoTipo" id="optApenas" value="A" onclick="tratarOpcao();" <?=($strStaTipo=='A'?'checked="checked"':'')?> class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblApenas" for="optApenas" class="infraLabelRadio">Apenas selecionados</label>
    </div>

    <div id="divDocumentosApenas" class="infraAreaDados" style="height:30em;">

      <select id="selDocumentosApenas" name="selDocumentosApenas" multiple="multiple" size="20" class="infraSelect"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensSelDocumentosApenas?>
      </select>
      <div id="divOpcoesDocumentosApenas">
        <img id="imgLupaDocumentosApenas" onclick="objLupaDocumentosApenas.selecionar(700,550);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Documentos" title="Selecionar Documentos" class="infraImg"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgExcluirDocumentosApenas" onclick="objLupaDocumentosApenas.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Documentos Selecionados" title="Remover Documentos Selecionados" class="infraImgNormal"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <br />
        <img id="imgDocumentosApenasAcima" onclick="objLupaDocumentosApenas.moverAcima();" src="<?=PaginaSEI::getInstance()->getIconeMoverAcima()?>" alt="Mover Acima Documento Selecionado" title="Mover Acima Documento Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgDocumentosApenasAbaixo" onclick="objLupaDocumentosApenas.moverAbaixo();" src="<?=PaginaSEI::getInstance()->getIconeMoverAbaixo()?>" alt="Mover Abaixo Documento Selecionado" title="Mover Abaixo Documento Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
      <input type="hidden" id="hdnDocumentosApenas" name="hdnDocumentosApenas" value="<?=$_POST['hdnDocumentosApenas']?>" />
    </div>

  </div>

  <input type="hidden" id="hdnFlagGerar" name="hdnFlagGerar" value="0" />
</form>
<iframe id="ifrDownload" style="border:0;height:0;width:0;"></iframe>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>