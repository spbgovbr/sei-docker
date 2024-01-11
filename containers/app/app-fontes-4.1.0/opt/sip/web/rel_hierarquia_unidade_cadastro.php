<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  //Pega dados do link adicionar subunidade da tela montagem de hierarquia
  if (isset($_GET['id_hierarquia_superior']) && isset($_GET['id_unidade_superior'])) {
    PaginaSip::getInstance()->salvarCampo('selHierarquia', $_GET['id_hierarquia_superior']);
  } else {
    PaginaSip::getInstance()->salvarCamposPost(array('selHierarquia', 'selOrgao'));
  }

  $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO(true);

  $arrComandos = array();
  $strDesabilitar = '';
  $bolRaiz = 'false';


  switch ($_GET['acao']) {
    case 'rel_hierarquia_unidade_cadastrar':
      $strTitulo = 'Adicionar Unidade na Hierarquia';
      $arrComandos[] = '<button type="submit" id="sbmSalvar" name="sbmSalvar" value="Salvar" class="infraButton">Salvar</button>';
      $arrComandos[] = '<button type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=rel_hierarquia_unidade_listar&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($_GET['id_unidade_superior'] . '-' . $_GET['id_hierarquia_superior'])) . '\';" class="infraButton">Cancelar</button>';

      $numIdHierarquia = PaginaSip::getInstance()->recuperarCampo('selHierarquia');
      if ($numIdHierarquia !== '') {
        $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($numIdHierarquia);
      } else {
        $objRelHierarquiaUnidadeDTO->setNumIdHierarquia(null);
      }

      if (isset($_GET['id_hierarquia_superior']) && isset($_GET['id_unidade_superior'])) {
        $objRelHierarquiaUnidadeDTO->setNumIdHierarquiaPai($_GET['id_hierarquia_superior']);
        $objRelHierarquiaUnidadeDTO->setNumIdUnidadePai($_GET['id_unidade_superior']);
      } else {
        if (PaginaSip::getInstance()->getCheckbox($_POST['chkRaiz']) == 'S') {
          $objRelHierarquiaUnidadeDTO->setNumIdHierarquiaPai(null);
          $objRelHierarquiaUnidadeDTO->setNumIdUnidadePai(null);
        } else {
          $objRelHierarquiaUnidadeDTO->setNumIdHierarquiaPai($_POST['selHierarquia']);
          $objRelHierarquiaUnidadeDTO->setNumIdUnidadePai($_POST['selUnidadeSuperior']);
        }
      }

      $numIdOrgaoUnidade = PaginaSip::getInstance()->recuperarCampo('selOrgao');
      if ($numIdOrgaoUnidade !== '') {
        $objRelHierarquiaUnidadeDTO->setNumIdOrgaoUnidade($numIdOrgaoUnidade);
      } else {
        $objRelHierarquiaUnidadeDTO->setNumIdOrgaoUnidade(null);
      }

      $objRelHierarquiaUnidadeDTO->setNumIdUnidade($_POST['selUnidade']);
      $objRelHierarquiaUnidadeDTO->setDtaDataInicio($_POST['txtDataInicio']);
      $objRelHierarquiaUnidadeDTO->setDtaDataFim($_POST['txtDataFim']);
      $objRelHierarquiaUnidadeDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmSalvar'])) {
        try {
          $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
          $objRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->cadastrar($objRelHierarquiaUnidadeDTO);
          PaginaSip::getInstance()->setStrMensagem('Unidade adicionada na hierarquia com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=rel_hierarquia_unidade_listar&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($objRelHierarquiaUnidadeDTO->getNumIdUnidade() . '-' . $objRelHierarquiaUnidadeDTO->getNumIdHierarquia())));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
        break;
      }
      break;

    case 'rel_hierarquia_unidade_alterar':
      $strTitulo = 'Alterar Unidade na Hierarquia';
      $arrComandos[] = '<button type="submit" id="sbmSalvar" name="sbmSalvar" value="Salvar" class="infraButton">Salvar</button>';

      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_hierarquia']) && isset($_GET['id_unidade'])) {
        $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($_GET['id_hierarquia']);
        $objRelHierarquiaUnidadeDTO->setNumIdUnidade($_GET['id_unidade']);

        $objRelHierarquiaUnidadeDTO->retTodos();
        $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
        $objRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->consultar($objRelHierarquiaUnidadeDTO);
        if ($objRelHierarquiaUnidadeDTO === null) {
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($_POST['hdnIdHierarquia']);
        $objRelHierarquiaUnidadeDTO->setNumIdOrgaoUnidade($_POST['hdnIdOrgaoUnidade']);
        $objRelHierarquiaUnidadeDTO->setNumIdUnidade($_POST['hdnIdUnidade']);

        if (PaginaSip::getInstance()->getCheckbox($_POST['chkRaiz']) == 'S') {
          $objRelHierarquiaUnidadeDTO->setNumIdHierarquiaPai(null);
          $objRelHierarquiaUnidadeDTO->setNumIdUnidadePai(null);
        } else {
          $objRelHierarquiaUnidadeDTO->setNumIdHierarquiaPai($_POST['hdnIdHierarquia']);
          $objRelHierarquiaUnidadeDTO->setNumIdUnidadePai($_POST['selUnidadeSuperior']);
        }

        $objRelHierarquiaUnidadeDTO->setDtaDataInicio($_POST['hdnDataInicio']);
        $objRelHierarquiaUnidadeDTO->setDtaDataFim($_POST['txtDataFim']);
        $objRelHierarquiaUnidadeDTO->setStrSinAtivo('S');
      }

      $strAncora = PaginaSip::getInstance()->montarAncora($objRelHierarquiaUnidadeDTO->getNumIdUnidade() . '-' . $objRelHierarquiaUnidadeDTO->getNumIdHierarquia());


      $arrComandos[] = '<button type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=rel_hierarquia_unidade_listar&acao_origem=' . $_GET['acao'] . $strAncora) . '\';" class="infraButton">Cancelar</button>';

      if (isset($_POST['sbmSalvar'])) {
        try {
          $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
          $objRelHierarquiaUnidadeRN->alterar($objRelHierarquiaUnidadeDTO);
          PaginaSip::getInstance()->setStrMensagem('Unidade alterada na hierarquia com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=rel_hierarquia_unidade_listar&acao_origem=' . $_GET['acao'] . $strAncora));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;


    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelHierarquia = HierarquiaINT::montarSelectNome('null', '&nbsp;', $objRelHierarquiaUnidadeDTO->getNumIdHierarquia());

  if ($_GET['acao'] == 'rel_hierarquia_unidade_cadastrar') {
    //carrega unidades da hierarquia
    $strItensSelUnidadeSuperior = RelHierarquiaUnidadeINT::montarSelectSiglaUnidade('null', '&nbsp;', $objRelHierarquiaUnidadeDTO->getNumIdUnidadePai(), $objRelHierarquiaUnidadeDTO->getNumIdHierarquia());
  } else {
    //carrega unidades da hierarquia EXCETO a unidade que esta sendo alterada
    $strItensSelUnidadeSuperior = RelHierarquiaUnidadeINT::montarSelectSiglaUnidadeOutras('null', '&nbsp;', $objRelHierarquiaUnidadeDTO->getNumIdUnidadePai(), $objRelHierarquiaUnidadeDTO->getNumIdHierarquia(),
      $objRelHierarquiaUnidadeDTO->getNumIdUnidade());
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaTodos('null', '&nbsp;', $objRelHierarquiaUnidadeDTO->getNumIdOrgaoUnidade());

  if ($_GET['acao'] == 'rel_hierarquia_unidade_cadastrar') {
    //carrega unidades que ainda não estão na hierarquia
    $strItensSelUnidade = RelHierarquiaUnidadeINT::montarSelectSiglaUnidadeNova('null', '&nbsp;', $objRelHierarquiaUnidadeDTO->getNumIdUnidade(), $objRelHierarquiaUnidadeDTO->getNumIdOrgaoUnidade(),
      $objRelHierarquiaUnidadeDTO->getNumIdHierarquia());
  } else {
    //carrega somente a unidade que esta sendo alterada
    $strItensSelUnidade = RelHierarquiaUnidadeINT::montarSelectSiglaUnidade('null', '&nbsp;', $objRelHierarquiaUnidadeDTO->getNumIdUnidade(), $objRelHierarquiaUnidadeDTO->getNumIdHierarquia(),
      $objRelHierarquiaUnidadeDTO->getNumIdUnidade());
  }
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Montar Hierarquia');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblHierarquia {position:absolute;left:0%;top:0%;width:40%;}
  #selHierarquia {position:absolute;left:0%;top:40%;width:40%;}

  #divRaiz {position:absolute;left:0%;top:20%;}

  #lblUnidadeSuperior {position:absolute;left:0%;top:0%;width:40%;visibility:hidden;}
  #selUnidadeSuperior {position:absolute;left:0%;top:40%;width:40%;}

  #lblOrgao {position:absolute;left:0%;top:0%;width:25%;}
  #selOrgao {position:absolute;left:0%;top:40%;width:25%;}

  #lblUnidade {position:absolute;left:0%;top:0%;width:40%;}
  #selUnidade {position:absolute;left:0%;top:40%;width:40%;}

  #lblDataInicio {position:absolute;left:0%;top:0%;width:18%;}
  #txtDataInicio {position:absolute;left:0%;top:40%;width:18%;}
  #imgCalDataInicio {position:absolute;left:19%;top:40%;}

  #lblDataFim {position:absolute;left:0%;top:0%;width:18%;}
  #txtDataFim {position:absolute;left:0%;top:40%;width:18%;}
  #imgCalDataFim {position:absolute;left:19%;top:40%;}

  #lblAvisoDatas {position:absolute;left:0%;top:20%;}

  sup {top:-.2rem;font-size:.825rem;}
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function OnSubmitForm() {

  if (!validarForm()){
  return false;
  }

  return true;
  }

  function validarForm(){

  if (!infraSelectSelecionado(document.getElementById('selHierarquia'))) {
  alert('Selecione uma Hierarquia.');
  document.getElementById('selHierarquia').focus();
  return false;
  }

  if (!document.getElementById('chkRaiz').checked){
  if (!infraSelectSelecionado(document.getElementById('selUnidadeSuperior'))) {
  alert('Selecione uma Unidade Superior na Hierarquia.');
  document.getElementById('selUnidadeSuperior').focus();
  return false;
  }

  if (document.getElementById('selUnidade').value==document.getElementById('selUnidadeSuperior').value){
  alert('Unidade superior não pode ser a própria unidade.');
  return false;
  }
  }

  if (!infraSelectSelecionado(document.getElementById('selOrgao'))) {
  alert('Selecione um Órgão.');
  document.getElementById('selOrgao').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selUnidade'))) {
  alert('Selecione uma Unidade.');
  document.getElementById('selUnidade').focus();
  return false;
  }

  if (infraTrim(document.getElementById('txtDataInicio').value)=='') {
  alert('Informe uma Data de Início.');
  document.getElementById('txtDataInicio').focus();
  return false;
  }

  if (!infraValidaData(document.getElementById('txtDataInicio'))){
  return false;
  }

  if (!infraValidaData(document.getElementById('txtDataFim'))){
  return false;
  }

  /*
  if ('<?=$_GET['acao']?>'=='rel_hierarquia_unidade_cadastrar'){
  if (infraCompararDatas(infraDataAtual(),document.getElementById('txtDataInicio').value)<0){
  alert('Data Inicial não pode estar no passado.');
  document.getElementById('txtDataInicio').focus();
  return false;
  }
  }
  */

  if (infraCompararDatas(infraDataAtual(),document.getElementById('txtDataFim').value)<0){
  alert('Data Final não pode estar no passado.');
  document.getElementById('txtDataFim').focus();
  return false;
  }

  if(infraCompararDatas(document.getElementById('txtDataInicio').value,document.getElementById('txtDataFim').value)<0){
  alert('Data Final deve ser igual ou superior a Data Inicial.');
  document.getElementById('txtDataFim').focus();
  return false;
  }

  return true;
  }


  function formatarTela(bolRaiz){
  if (bolRaiz){
  document.getElementById('chkRaiz').checked=true;
  document.getElementById('lblUnidadeSuperior').style.visibility='hidden';
  document.getElementById('selUnidadeSuperior').className = 'infraSelectOculto';
  }else{
  document.getElementById('chkRaiz').checked=false;
  document.getElementById('lblUnidadeSuperior').style.visibility='visible';
  document.getElementById('selUnidadeSuperior').className = 'infraSelect';
  }
  }

  function inicializar(){
  formatarTela('<?=($objRelHierarquiaUnidadeDTO->getNumIdHierarquiaPai() == null && $objRelHierarquiaUnidadeDTO->getNumIdUnidadePai() == null)?>'=='1');

  if (document.getElementById('sbmSalvar')!=null){
  document.getElementById('sbmSalvar').focus();
  }
  }


  function trocarHierarquia(obj){
  document.getElementById('selUnidadeSuperior').value='null';
  document.getElementById('selUnidade').value='null';
  obj.form.submit();

  }

  function trocarOrgaoUnidade(obj){
  document.getElementById('selUnidade').value='null';
  obj.form.submit();
  }


<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmMontarHierarquia" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink(basename(__FILE__) . '?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblHierarquia" for="selHierarquia" accesskey="H" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">H</span>ierarquia:</label>
    <select id="selHierarquia" name="selHierarquia" onchange="trocarHierarquia(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelHierarquia?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();

    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <div id="divRaiz" class="infraDivCheckbox">
      <input type="checkbox" id="chkRaiz" name="chkRaiz" onclick="formatarTela(this.checked);" class="infraCheckbox"
             tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
      <label id="lblRaiz" accesskey="R" for="chkRaiz" class="infraLabelCheckbox">Raiz</label>
    </div>
    <?
    PaginaSip::getInstance()->fecharAreaDados();

    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblUnidadeSuperior" for="selUnidadeSuperior" accesskey="S" class="infraLabelObrigatorio">Unidade <span
        class="infraTeclaAtalho">S</span>uperior na Hierarquia:</label>
    <select id="selUnidadeSuperior" name="selUnidadeSuperior" class="infraSelect infraSelectOculto"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelUnidadeSuperior?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();

    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblOrgao" for="selOrgao" accesskey="o" class="infraLabelObrigatorio">Órgã<span
        class="infraTeclaAtalho">o</span> da Unidade:</label>
    <select id="selOrgao" name="selOrgao" onchange="trocarOrgaoUnidade(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelOrgao?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();

    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblUnidade" for="selUnidade" accesskey="U" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">U</span>nidade:</label>
    <select id="selUnidade" name="selUnidade" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelUnidade?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();

    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblDataInicio" for="txtDataInicio" accesskey="I" class="infraLabelObrigatorio">Data <span
        class="infraTeclaAtalho">I</span>nicial:&nbsp;<sup>*</sup></label>
    <input type="text" id="txtDataInicio" name="txtDataInicio" onkeypress="return infraMascaraData(this, event)"
           class="infraText" value="<?=$objRelHierarquiaUnidadeDTO->getDtaDataInicio()?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <img src="<?=PaginaSip::getInstance()->getIconeCalendario()?>" id="imgCalDataInicio"
         title="Selecionar Data Inicial " alt="Selecionar Data Inicial" class="infraImg"
         onclick="infraCalendario('txtDataInicio',this);"
         tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();

    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblDataFim" for="txtDataFim" accesskey="F" class="infraLabelOpcional">Data <span
        class="infraTeclaAtalho">F</span>inal:&nbsp;<sup>*</sup></label>
    <input type="text" id="txtDataFim" name="txtDataFim" onkeypress="return infraMascaraData(this, event)"
           class="infraText" value="<?=$objRelHierarquiaUnidadeDTO->getDtaDataFim()?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <img src="<?=PaginaSip::getInstance()->getIconeCalendario()?>" id="imgCalDataFim" title="Selecionar Data Final"
         alt="Selecionar Data Final" class="infraImg" onclick="infraCalendario('txtDataFim',this);"
         tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();

    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblAvisoDatas"><sup>*</sup>&nbsp;Datas apenas para registro sem efeito sobre permissões</label>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    ?>
    <input type="hidden" name="hdnIdHierarquia" value="<?=$objRelHierarquiaUnidadeDTO->getNumIdHierarquia();?>"/>
    <input type="hidden" name="hdnIdOrgaoUnidade" value="<?=$objRelHierarquiaUnidadeDTO->getNumIdOrgaoUnidade();?>"/>
    <input type="hidden" name="hdnIdUnidade" value="<?=$objRelHierarquiaUnidadeDTO->getNumIdUnidade();?>"/>
    <input type="hidden" name="hdnDataInicio" value="<?=$objRelHierarquiaUnidadeDTO->getDtaDataInicio();?>"/>
    <?
    //PaginaSip::getInstance()->montarAreaDebug();
    //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>