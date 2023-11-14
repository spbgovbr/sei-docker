<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/08/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
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

  $bolRecarregar = false;

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples', 'id_procedimento','id_acompanhamento'));
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $arrComandos = array();

  $bolAcaoExcluir = false;

  switch($_GET['acao']){

    case 'controle_prazo_definir':

      $strTitulo = 'Definir Controle de Prazo';
      $arrComandos[] = '<button type="submit" accesskey="S" id="sbmDefinirControlePrazo" name="sbmDefinirControlePrazo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      $objControlePrazoDTO = new ControlePrazoDTO();
      $objControlePrazoDTO->setNumIdControlePrazo(null);

      if (isset($_GET['id_controle_prazo'])) {

        $objControlePrazoDTO = new ControlePrazoDTO();
        $objControlePrazoDTO->retNumIdControlePrazo();
        $objControlePrazoDTO->retDblIdProtocolo();
        $objControlePrazoDTO->retDtaPrazo();
        $objControlePrazoDTO->setNumIdControlePrazo($_GET['id_controle_prazo']);

        $objControlePrazoRN = new ControlePrazoRN();
        $objControlePrazoDTO = $objControlePrazoRN->consultar($objControlePrazoDTO);

        if ($objControlePrazoDTO == null) {
          throw new InfraException("Registro não encontrado.");
        }

        $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('controle_prazo_excluir');

        if ($bolAcaoExcluir){
          $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExcluir();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
          $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_prazo_excluir&acao_origem='.$_GET['acao'].'&acao_retorno='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_controle_prazo='.$objControlePrazoDTO->getNumIdControlePrazo());
        }


        $arrIdProtocolo = array($objControlePrazoDTO->getDblIdProtocolo());
        $_POST['rdoPrazo'] = '1';
        $_POST['txtDias'] = '';
        $_POST['chkSinDiasUteis'] = '';

      }else if ($_GET['acao_origem'] == 'procedimento_controlar') {
        $arrIdProtocolo = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
      }else if ($_GET['acao_origem'] == 'procedimento_visualizar'){
        $arrIdProtocolo = array($_GET['id_procedimento']);
      }else{
        $arrIdProtocolo = explode(',', $_POST['hdnIdProcedimentos']);
        $objControlePrazoDTO->setNumIdControlePrazo($_POST['hdnIdControlePrazo']);
      }

      $objControlePrazoDTO->setDtaPrazo($_POST['txtPrazo']);
      $objControlePrazoDTO->setNumDias($_POST['txtDias']);
      $objControlePrazoDTO->setStrSinDiasUteis(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinDiasUteis']));

      if(!PaginaSEI::getInstance()->isBolArvore()) {

        if (PaginaSEI::getInstance()->getAcaoRetorno()=='procedimento_controlar'){
          $strAncora = $arrIdProtocolo;
        }else if (PaginaSEI::getInstance()->getAcaoRetorno()=='acompanhamento_listar'){
          $strAncora = $_GET['id_acompanhamento'];
        }elseif (PaginaSEI::getInstance()->getAcaoRetorno()=='controle_prazo_listar'){
          $strAncora = isset($_POST['hdnIdControlePrazo']) ? $_POST['hdnIdControlePrazo'] : $_GET['id_controle_prazo'];
        }

        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }

      if (isset($_POST['sbmDefinirControlePrazo'])) {

        try{

          $arrObjControlePrazoDTO = array();
          foreach ($arrIdProtocolo as $idProtocolo) {
            $objControlePrazoDTODefinir = clone($objControlePrazoDTO);
            $objControlePrazoDTODefinir->setDblIdProtocolo($idProtocolo);
            $arrObjControlePrazoDTO[] = $objControlePrazoDTODefinir;
          }

          $objControlePrazoRN = new ControlePrazoRN();

          if($_POST['rdoPrazo'] != "3") {
            $objControlePrazoRN->definir($arrObjControlePrazoDTO);
          }else{
            $objControlePrazoRN->concluir($arrObjControlePrazoDTO);
          }

          PaginaSEI::getInstance()->adicionarMensagem('Controle de Prazos "'.$objControlePrazoDTO->getNumIdControlePrazo().'" alterado com sucesso.');
          $acao = PaginaSEI::getInstance()->getAcaoRetorno();
          if(PaginaSEI::getInstance()->isBolArvore()){
            $bolRecarregar = true;
            $strTitulo = '';
          }else {
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $acao . '&acao_origem=' . $_GET['acao']  . PaginaSEI::montarAncora($strAncora)));
            die;
          }
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'controle_prazo_excluir':
      try{

        $objControlePrazoDTO = new ControlePrazoDTO();
        $objControlePrazoDTO->setNumIdControlePrazo($_GET['id_controle_prazo']);

        $objControlePrazoRN = new ControlePrazoRN();
        $objControlePrazoRN->excluir(array($objControlePrazoDTO));
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&atualizar_arvore=1'));
        die;

      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_prazo_definir&acao_origem='.$_GET['acao'].'&id_controle_prazo='.$_GET['id_controle_prazo']));
      die;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&montar_visualizacao=1');

  $bolAcaoConcluir = SessaoSEI::getInstance()->verificarPermissao('controle_prazo_concluir');

  $arrObjControlePrazoDTOBanco = array();

  if (!$bolRecarregar) {
    $objControlePrazoDTOBanco = new ControlePrazoDTO();
    $objControlePrazoDTOBanco->retDtaPrazo();
    $objControlePrazoDTOBanco->retDtaConclusao();
    $objControlePrazoDTOBanco->setDblIdProtocolo($arrIdProtocolo, InfraDTO::$OPER_IN);
    $objControlePrazoDTOBanco->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objControlePrazoRN = new ControlePrazoRN();
    $arrObjControlePrazoDTOBanco = $objControlePrazoRN->listar($objControlePrazoDTOBanco);


    $arrDtaPrazo = array();
    foreach ($arrObjControlePrazoDTOBanco as $objControlePrazoDTOBanco) {
      if (!isset($_POST['hdnIdControlePrazo']) && $objControlePrazoDTOBanco->getDtaConclusao() != null) {
        $_POST['rdoPrazo'] = '3';
      }

      if ($objControlePrazoDTOBanco->getDtaPrazo() != null) {
        $arrDtaPrazo[$objControlePrazoDTOBanco->getDtaPrazo()] = $objControlePrazoDTOBanco->getDtaPrazo();
      }
    }

    if (!isset($_POST['hdnIdControlePrazo']) && $_POST['rdoPrazo'] != '3' && count($arrDtaPrazo) == 1) {
      $_POST['rdoPrazo'] = '1';
      $objControlePrazoDTO->setDtaPrazo(array_pop($arrDtaPrazo));
    }
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?
if (0){
?>
<style><?}?>

  #sbmDefinirControlePrazo{
    visibility: hidden;
  }

  #divOptDataCerta {
    position: absolute;
    left: 0%;
    top: 0%;
    visibility: hidden;
  }

  #divOptDias {
    position: absolute;
    left: 0%;
    top: 20%;
    visibility: hidden;
  }

  #divOptConcluir {
    position: absolute;
    left: 0%;
    top: 40%;
    visibility: hidden;
  }

  #txtPrazo {
    position: absolute;
    left: 13%;
    top: 0%;
    width: 10%;
    visibility: hidden;
  }

  #imgCalData {
    position: absolute;
    left: 24%;
    top: 2%;
    visibility: hidden;
  }

  #txtDias {
    position: absolute;
    left: 16%;
    top: 16%;
    width: 5%;
    visibility: hidden;
  }

  #divSinDiasUteis {
    position: absolute;
    left: 22%;
    top: 18%;
    width: 25%;
    visibility: hidden;
  }

  <? if (0){ ?></style><?} ?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?
if (0){
?>
<script type="text/javascript"><?}?>

  function inicializar() {

    <?if ($bolRecarregar) { ?>
    parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
    return;
    <?}?>

    if ('<?=$_GET['acao']?>' == 'controle_prazo_consultar') {
      infraDesabilitarCamposAreaDados();
    }

    configurarPrazo();

  }

  function configurarPrazo() {

    document.getElementById('sbmDefinirControlePrazo').style.visibility = 'visible';

    document.getElementById('divOptDataCerta').style.visibility = 'visible';
    document.getElementById('divOptDias').style.visibility = 'visible';

    if (document.getElementById('divOptConcluir')!=null) {
      document.getElementById('divOptConcluir').style.visibility = 'visible';
    }

    if (document.getElementById('optDataCerta').checked) {
      document.getElementById('txtPrazo').style.visibility = 'visible';
      document.getElementById('imgCalData').style.visibility = 'visible';
      document.getElementById('txtDias').value = '';
      document.getElementById('txtDias').style.visibility = 'hidden';
      document.getElementById('divSinDiasUteis').style.visibility = 'hidden';
    } else if (document.getElementById('optDias').checked) {
      document.getElementById('txtPrazo').value = '';
      document.getElementById('txtPrazo').style.visibility = 'hidden';
      document.getElementById('imgCalData').style.visibility = 'hidden';
      document.getElementById('txtDias').style.visibility = 'visible';
      document.getElementById('divSinDiasUteis').style.visibility = 'visible';
    } else {
      document.getElementById('txtPrazo').value = '';
      document.getElementById('txtPrazo').style.visibility = 'hidden';
      document.getElementById('imgCalData').style.visibility = 'hidden';
      document.getElementById('txtDias').value = '';
      document.getElementById('txtDias').style.visibility = 'hidden';
      document.getElementById('divSinDiasUteis').style.visibility = 'hidden';
      document.getElementById('chkSinDiasUteis').checked = false;
    }
  }

  function validarCadastro() {
    if (!document.getElementById('optDataCerta').checked && !document.getElementById('optDias').checked && (document.getElementById('optConcluir')==null || !document.getElementById('optConcluir').checked)){
      alert('Selecione uma opção.');
      return false;
    }

    if (document.getElementById('optDataCerta').checked && infraTrim(document.getElementById('txtPrazo').value)==''){
      alert('Informe o prazo.');
      document.getElementById('txtPrazo').focus();
      return false;
    }

    if (document.getElementById('optDias').checked && infraTrim(document.getElementById('txtDias').value)==''){
      alert('Informe o número de dias.');
      document.getElementById('txtDias').focus();
      return false;
    }

    return true;
  }

  function OnSubmitForm() {
    return validarCadastro();
  }

  <? if ($bolAcaoExcluir){ ?>
  function acaoExcluir(){
    if (confirm("Confirma exclusão do Controle de Prazo?")){
      document.getElementById('frmControlePrazoCadastro').action='<?=$strLinkExcluir?>';
      document.getElementById('frmControlePrazoCadastro').submit();
    }
  }
  <?}?>

  <?
  if (0){
  ?></script><?
} ?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmControlePrazoCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] ) ?>">
  <?

  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->montarAreaValidacao();
  PaginaSEI::getInstance()->abrirAreaDados('15em');
    ?>

    <div id="divOptDataCerta" class="infraDivRadio">
      <input type="radio" name="rdoPrazo" id="optDataCerta"  onclick="configurarPrazo();" <?= $_POST['rdoPrazo'] == '1' ? 'checked="checked"' : '' ?> value="1" class="infraRadio"/>
      <span id="spnDataCerta"><label id="lblDataCerta" for="optDataCerta" class="infraLabelRadio"  tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Data certa</label></span>
    </div>

    <input type="text" id="txtPrazo" name="txtPrazo" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?= PaginaSEI::tratarHTML($objControlePrazoDTO->getDtaPrazo()) ?>" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <img src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" id="imgCalData" title="Selecionar Prazo" alt="Selecionar Prazo" class="infraImg" onclick="infraCalendario('txtPrazo',this);" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

    <div id="divOptDias" class="infraDivRadio">
      <input type="radio" name="rdoPrazo" id="optDias" onclick="configurarPrazo();" <?= $_POST['rdoPrazo'] == '2' ? 'checked="checked"' : '' ?> value="2" class="infraRadio"/>
      <span id="spnDias"><label id="lblDias" for="optDias" class="infraLabelRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Prazo em dias</label></span>
    </div>

    <input type="text" id="txtDias" name="txtDias" class="infraText" value="<?= PaginaSEI::tratarHTML($objControlePrazoDTO->getNumDias()) ?>" onkeypress="return infraMascaraNumero(this,event);" maxlength="3" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

    <div id="divSinDiasUteis" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinDiasUteis" name="chkSinDiasUteis" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objControlePrazoDTO->getStrSinDiasUteis()) ?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
      <label id="lblSinDiasUteis" for="chkSinDiasUteis" accesskey="" class="infraLabelCheckbox">Úteis</label>
    </div>

    <?
    if ($bolAcaoConcluir) {
    ?>

    <div id="divOptConcluir" class="infraDivRadio">
      <input type="radio" name="rdoPrazo" id="optConcluir"  onclick="configurarPrazo();" <?= $_POST['rdoPrazo'] == '3' ? 'checked="checked"' : '' ?> value="3" class="infraRadio"/>
      <span id="spnConcluir"><label id="lblConcluir" for="optConcluir" class="infraLabelRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Concluir</label></span>
    </div>

    <?
  }
  PaginaSEI::getInstance()->fecharAreaDados();
?>

  <input type="hidden" id="hdnIdControlePrazo" name="hdnIdControlePrazo" value="<?= $objControlePrazoDTO->getNumIdControlePrazo(); ?>"/>
  <input type="hidden" id="hdnIdProcedimentos" name="hdnIdProcedimentos" value="<?= implode(',', $arrIdProtocolo); ?>"/>
<?
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
