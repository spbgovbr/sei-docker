<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/03/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
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

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }
  
  $arrComandos = array();
  $strBotaoNovo = '';

  switch($_GET['acao']){
    case 'documento_gerar_multiplo':
      $strTitulo = 'Incluir Documento em Processos';
      $arrComandos[] = '<button type="submit" accesskey="G" name="sbmGerar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">G</span>erar</button>';

      if (SessaoSEI::getInstance()->verificarPermissao('bloco_assinatura_cadastrar')){
        $strBotaoNovo = '<button type="button" accesskey="N" id="btnNovoAssinatura" value="Novo" onclick="novoBloco()" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
      }

      $arrProtocolosOrigem = array();

      if ($_GET['acao_origem']=='procedimento_controlar') {
        $arrProtocolosOrigem = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
      }else{
        $arrProtocolosOrigem = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnProcedimentos']);
      }

      if (count($arrProtocolosOrigem) == 0) {
        throw new InfraException('Nenhum processo selecionado.');
      }

      $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($arrProtocolosOrigem) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';

      $objProcedimentoRN = new ProcedimentoRN();

      $objDocumentoGeracaoMultiplaDTO = new DocumentoGeracaoMultiplaDTO();
      $objDocumentoGeracaoMultiplaDTO->setArrDblIdProcedimento($arrProtocolosOrigem);
      $objDocumentoGeracaoMultiplaDTO->setNumIdSerie($_POST['selSerie']);
      $objDocumentoGeracaoMultiplaDTO->setNumIdTextoPadraoInterno($_POST['hdnIdTextoPadrao']);
      $objDocumentoGeracaoMultiplaDTO->setStrProtocoloFormatadoDocumentoBase($_POST['txtProtocoloDocumentoTextoBase']);
      $objDocumentoGeracaoMultiplaDTO->setStrStaNivelAcessoLocal($_POST['rdoNivelAcesso']);
      $objDocumentoGeracaoMultiplaDTO->setNumIdHipoteseLegal($_POST['selHipoteseLegal']);
      $objDocumentoGeracaoMultiplaDTO->setStrStaGrauSigilo($_POST['selGrauSigilo']);
      $objDocumentoGeracaoMultiplaDTO->setNumIdBloco($_POST['selBloco']);

      if (isset($_POST['sbmGerar'])) {
        try{

          $objProcedimentoRN->gerarDocumentoMultiplo($objDocumentoGeracaoMultiplaDTO);

          //PaginaSEI::getInstance()->setStrMensagem('Documentos gerados com sucesso.');

          if (!InfraString::isBolVazia($objDocumentoGeracaoMultiplaDTO->getNumIdBloco())){
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_assinatura_listar&acao_origem='.$_GET['acao'].PaginaSEI::montarAncora($objDocumentoGeracaoMultiplaDTO->getNumIdBloco())));
          }else{
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem=' . $_GET['acao'] . PaginaSEI::montarAncora($arrProtocolosOrigem)));
          }

          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkAjaxTextoPadrao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=texto_padrao_auto_completar');
  $strLinkTextoPadraoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=texto_padrao_interno_selecionar&tipo_selecao=1&id_object=objLupaTextoPadrao');
  $strLinkDocumentoTextoBaseSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_modelo_selecionar&tipo_selecao=1&sta_documento='.DocumentoRN::$TD_EDITOR_INTERNO.'&id_object=objLupaDocumentoTextoBase');
  $strItensSelProcedimentos = ProcedimentoINT::conjuntoCompletoFormatadoRI0903($arrProtocolosOrigem);
  $strItensSelSerie = SerieINT::montarSelectNomeGerados('null','&nbsp;',$objDocumentoGeracaoMultiplaDTO->getNumIdSerie());
  $strLinkNovoBloco = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_assinatura_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
  $strLinkAjaxBloco = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=bloco_assinatura_montar_select');
  $strItensSelBloco = BlocoINT::montarSelectAssinatura('null','&nbsp;',$objDocumentoGeracaoMultiplaDTO->getNumIdBloco());


  $strCheckedTextoPadrao = '';
  $strCheckedProtocoloDocumentoTextoBase = '';
  if ($_POST['rdoTextoInicial']=='D'){
    $strCheckedProtocoloDocumentoTextoBase = 'checked="checked"';
  }else if ($_POST['rdoTextoInicial']=='T'){
    $strCheckedTextoPadrao = 'checked="checked"';
  }else{
    $strCheckedProtocoloDocumentoTextoBase = 'checked="checked"';
  }


  $objProcedimentoDTO = new ProcedimentoDTO();
  $objProcedimentoDTO->setDistinct(true);
  $objProcedimentoDTO->retNumIdTipoProcedimento();
  $objProcedimentoDTO->setDblIdProcedimento($arrProtocolosOrigem,InfraDTO::$OPER_IN);

  $objProcedimentoRN = new ProcedimentoRN();
  $arrNumIdTipoProcedimento = InfraArray::converterArrInfraDTO($objProcedimentoRN->listarRN0278($objProcedimentoDTO),'IdTipoProcedimento');

  ProtocoloINT::montarNivelAcesso($arrNumIdTipoProcedimento,
      $objDocumentoGeracaoMultiplaDTO,
      false,
      $strCssNivelAcesso,
      $strHtmlNivelAcesso,
      $strJsGlobalNivelAcesso,
      $strJsInicializarNivelAcesso,
      $strJsValidacoesNivelAcesso);



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

#divProcedimentos {height:10em;}
#lblProcedimentos {position:absolute;left:0%;top:0%;}
#selProcedimentos {position:absolute;left:0%;top:17%;width:85%;}
#imgExcluirProcedimentos {position:absolute;left:86%;top:18%;}

#divSerie {height:5em;}
#lblSerie {position:absolute;left:0%;top:0%;}
#selSerie {position:absolute;left:0%;top:40%;width:85%;}

#divTextoInicial {height:11em;}
#fldTextoInicial {position:absolute;left:0%;top:5%;height:80%;width:85%;}
#divOptProtocoloDocumentoTextoBase {position:absolute;left:13%;top:30%;}
#txtProtocoloDocumentoTextoBase {position:absolute;left:40%;top:30%;width:15%;visibility:hidden;}
#lblOuModeloFavorito {position:absolute;left:57%;top:35%;visibility:hidden;}
#btnEscolherDocumentoTextoBase {position:absolute;left:60.5%;top:28%;visibility:hidden;}
#divOptTextoPadrao {position:absolute;left:13%;top:60%;}
#txtTextoPadrao {position:absolute;left:40%;top:50%;width:50%;visibility:hidden;}
#imgPesquisarTextoPadrao {position:absolute;left:91%;top:63%;visibility:hidden;}


<?=$strCssNivelAcesso?>

#divBloco {height: 5em}
#lblBloco {position:absolute;left:0%;top:0%;}
#selBloco {position:absolute;left:0%;top:40%;width:74%;}
#btnNovoAssinatura {position:absolute;left:75%;top:40%;width:10%;}

<?
if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){
?>

#divOptProtocoloDocumentoTextoBase {top:20%;}
#txtProtocoloDocumentoTextoBase {top:20%;}
#lblOuModeloFavorito {top:25%;}
#btnEscolherDocumentoTextoBase {top:18%;}
#divOptTextoPadrao {top:55%;}
#txtTextoPadrao {top:50%;}
#imgPesquisarTextoPadrao {top:53%;}

<?
}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

<?=$strJsGlobalNivelAcesso?>

var objLupaProcedimentos = null;
var objLupaDocumentoTextoBase = null;
var objAjaxBloco = null;
var objAutoCompletarTextoPadrao = null;
var objLupaTextoPadrao = null;

function inicializar(){

  objLupaProcedimentos = new infraLupaSelect('selProcedimentos','hdnProcedimentos',null);

  objLupaDocumentoTextoBase = new infraLupaText('txtProtocoloDocumentoTextoBase','hdnIdDocumentoTextoBase','<?=$strLinkDocumentoTextoBaseSelecao?>');

  objAjaxBloco = new infraAjaxMontarSelect('selBloco','<?=$strLinkAjaxBloco?>');
  objAjaxBloco.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','Todos',document.getElementById('hdnIdBloco').value);
  }

  objAutoCompletarTextoPadrao = new infraAjaxAutoCompletar('hdnIdTextoPadrao','txtTextoPadrao','<?=$strLinkAjaxTextoPadrao?>');
  objAutoCompletarTextoPadrao.limparCampo = false;

  objAutoCompletarTextoPadrao.prepararExecucao = function(){
    return 'palavras_pesquisa='+encodeURIComponent(document.getElementById('txtTextoPadrao').value);
  };

  objAutoCompletarTextoPadrao.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      document.getElementById('hdnIdTextoPadrao').value = id;
      document.getElementById('txtTextoPadrao').value = descricao;
    }
  }
  objAutoCompletarTextoPadrao.selecionar('<?=$_POST['hdnIdTextoPadrao']?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($_POST['txtTextoPadrao'],false);?>');

  objLupaTextoPadrao = new infraLupaText('txtTextoPadrao','hdnIdTextoPadrao','<?=$strLinkTextoPadraoSelecao?>');
  objLupaTextoPadrao.finalizarSelecao = function(){
    objAutoCompletarTextoPadrao.selecionar(document.getElementById('hdnIdTextoPadrao').value,document.getElementById('txtTextoPadrao').value);
  }

  configurarTextoInicial();

  <?=$strJsInicializarNivelAcesso?>

  document.getElementById('selSerie').focus();

}

function OnSubmitForm() {

  if (infraTrim(document.getElementById('hdnProcedimentos').value)==''){
    alert('Nenhum processo informado.');
    document.getElementById('selProcedimentos').focus();
    return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selSerie'))){
    alert('Informe o Tipo do Documento.');
    document.getElementById('selSerie').focus();
    return false;
  }

  if (document.getElementById('optProtocoloDocumentoTextoBase').checked && infraTrim(document.getElementById('txtProtocoloDocumentoTextoBase').value)==''){
    alert('Informe ou selecione documento base.');
    document.getElementById('txtProtocoloDocumentoTextoBase').focus();
    return false;
  }

  if (document.getElementById('optTextoPadrao').checked && document.getElementById('hdnIdTextoPadrao').value==''){
    alert('Selecione um Texto Padrão.');
    document.getElementById('txtTextoPadrao').focus();
    return false;
  }

  <?=$strJsValidacoesNivelAcesso?>

  infraExibirAviso();

  return true;
}

function novoBloco(){
  infraAbrirJanelaModal('<?=$strLinkNovoBloco?>',700,450);
}

function atualizarBlocos(idBloco){
  document.getElementById('hdnIdBloco').value = idBloco;
  objAjaxBloco.executar();
}

function configurarTextoInicial(){
  if (document.getElementById('optTextoPadrao').checked){
    document.getElementById('txtTextoPadrao').style.visibility = 'visible';
    document.getElementById('imgPesquisarTextoPadrao').style.visibility = 'visible';
    document.getElementById('txtTextoPadrao').focus();
    document.getElementById('txtProtocoloDocumentoTextoBase').style.visibility = 'hidden';
    document.getElementById('lblOuModeloFavorito').style.visibility = 'hidden';
    document.getElementById('btnEscolherDocumentoTextoBase').style.visibility = 'hidden';
    document.getElementById('txtProtocoloDocumentoTextoBase').value = '';
  }else if (document.getElementById('optProtocoloDocumentoTextoBase').checked){
    document.getElementById('txtTextoPadrao').style.visibility = 'hidden';
    document.getElementById('imgPesquisarTextoPadrao').style.visibility = 'hidden';
    document.getElementById('txtTextoPadrao').value = '';
    document.getElementById('txtProtocoloDocumentoTextoBase').style.visibility = 'visible';
    document.getElementById('lblOuModeloFavorito').style.visibility = 'visible';
    document.getElementById('btnEscolherDocumentoTextoBase').style.visibility = 'visible';
    document.getElementById('txtProtocoloDocumentoTextoBase').focus();
  }
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmDocumentoGeracaoMultiplo" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('50em');
?>

  <div id="divProcedimentos" class="infraAreaDados">
    <label id="lblProcedimentos" for="selProcedimentos" class="infraLabelObrigatorio">Processos:</label>
    <select id="selProcedimentos" name="selProcedimentos" size="4" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelProcedimentos?>
    </select>
    <img id="imgExcluirProcedimentos" onclick="objLupaProcedimentos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Processos Selecionados" title="Remover Processos Selecionados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>

  <div id="divSerie" class="infraAreaDados">
    <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelObrigatorio">Tipo do Documento:</label>
    <select id="selSerie" name="selSerie" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
      <?=$strItensSelSerie?>
    </select>
  </div>

  <div id="divTextoInicial" class="infraAreaDados">
    <fieldset id="fldTextoInicial" class="infraFieldset">
      <legend class="infraLegend">Texto Inicial</legend>

      <div id="divOptProtocoloDocumentoTextoBase" class="infraDivRadio">
        <input type="radio" <?=$strCheckedProtocoloDocumentoTextoBase?> onclick="configurarTextoInicial();" name="rdoTextoInicial" id="optProtocoloDocumentoTextoBase" value="D" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        <span id="spnProtocoloDocumentoTextoBase"><label id="lblProtocoloDocumentoTextoBase" for="optProtocoloDocumentoTextoBase" class="infraLabelRadio">Documento Modelo</label></span>
      </div>

      <input type="text" id="txtProtocoloDocumentoTextoBase" name="txtProtocoloDocumentoTextoBase" onkeypress="return infraMascaraNumero(this, event)" maxlength="<?=DIGITOS_DOCUMENTO?>" class="infraText" value="<?=PaginaSEI::tratarHTML($_POST['txtProtocoloDocumentoTextoBase'])?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblOuModeloFavorito">ou</label>
      <button type="button" id="btnEscolherDocumentoTextoBase" name="btnEscolherDocumentoTextoBase" onclick="objLupaDocumentoTextoBase.selecionar(800,500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" class="infraButton">Selecionar nos Favoritos</button>

      <div id="divOptTextoPadrao" class="infraDivRadio">
        <input type="radio" <?=$strCheckedTextoPadrao?> onclick="configurarTextoInicial();" name="rdoTextoInicial" id="optTextoPadrao" value="T" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        <span id="spnTextoPadrao"><label id="lblTextoPadrao" for="optTextoPadrao" class="infraLabelRadio">Texto Padrão</label></span>
      </div>

      <input type="text" id="txtTextoPadrao" name="txtTextoPadrao" class="infraText" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdTextoPadrao" name="hdnIdTextoPadrao" value="" />
      <img id="imgPesquisarTextoPadrao" onclick="objLupaTextoPadrao.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Texto Padrão" title="Selecionar Texto Padrão" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <input type="hidden" id="hdnIdDocumentoTextoBase" name="hdnIdDocumentoTextoBase" value="<?=$_POST['hdnIdDocumentoTextoBase']?>" />

    </fieldset>
  </div>

  <br />

  <?=$strHtmlNivelAcesso?>

  <br />

  <div id="divBloco" class="infraAreaDados">
    <label id="lblBloco" for="selBloco" accesskey="" class="infraLabelOpcional">Bloco de Assinatura:</label>
    <select id="selBloco" name="selBloco" class="infraSelect" onchange="this.form.submit();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelBloco?>
    </select>
    <?=$strBotaoNovo?>
  </div>

  <input type="hidden" id="hdnProcedimentos" name="hdnProcedimentos" value="<?=$_POST['hdnProcedimentos']?>" />
  <input type="hidden" id="hdnIdHipoteseLegalSugestao" name="hdnIdHipoteseLegalSugestao" value="" />
  <input type="hidden" id="hdnIdTipoProcedimento" name="hdnIdTipoProcedimento" value="<?=implode(',',$arrNumIdTipoProcedimento)?>" />
  <input type="hidden" id="hdnIdBloco" name="hdnIdBloco" value="" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>