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


  //Pega dados do link adicionar subitem da tela de montagem de menu
  if (isset($_GET['id_menu_superior']) && isset($_GET['id_item_menu_superior']) & isset($_GET['id_sistema']) && isset($_GET['id_orgao_sistema'])) {
    PaginaSip::getInstance()->salvarCampo('selOrgaoSistema', $_GET['id_orgao_sistema']);
    PaginaSip::getInstance()->salvarCampo('selSistema', $_GET['id_sistema']);
    PaginaSip::getInstance()->salvarCampo('selMenu', $_GET['id_menu_superior']);

    $objItemMenuDTO = new ItemMenuDTO();
    $objItemMenuDTO->retNumSequencia();
    $objItemMenuDTO->setNumIdMenuPai($_GET['id_menu_superior']);
    $objItemMenuDTO->setNumIdItemMenuPai($_GET['id_item_menu_superior']);
    $objItemMenuRN = new ItemMenuRN();
    $arrObjItemMenuDTO = $objItemMenuRN->listar($objItemMenuDTO);
    $numMaxSequencia = 0;
    foreach ($arrObjItemMenuDTO as $objItemMenuDTO) {
      if ($numMaxSequencia < $objItemMenuDTO->getNumSequencia()) {
        $numMaxSequencia = $objItemMenuDTO->getNumSequencia();
      }
    }
    PaginaSip::getInstance()->salvarCampo('txtSequencia', $numMaxSequencia + 10);
  } else {
    PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema', 'selMenu'));
    if (!isset($_POST['txtSequencia'])) {
      PaginaSip::getInstance()->salvarCampo('txtSequencia', '');
    } else {
      PaginaSip::getInstance()->salvarCampo('txtSequencia', $_POST['txtSequencia']);
    }
  }

  $objItemMenuDTO = new ItemMenuDTO(true);

  $arrComandos = array();
  $strDesabilitar = '';
  $bolRaiz = 'false';

  switch ($_GET['acao']) {
    case 'item_menu_cadastrar':
      $strTitulo = 'Adicionar Item de Menu';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarItemMenu" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=item_menu_listar&acao_origem=item_menu_cadastrar' . PaginaSip::getInstance()->montarAncora($_GET['id_menu_superior'] . '-' . $_GET['id_item_menu_superior'])) . '\';" class="infraButton" />';

      $objItemMenuDTO->setNumIdItemMenu(null);

      $numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
      if ($numIdOrgaoSistema !== '') {
        $objItemMenuDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
      } else {
        $objItemMenuDTO->setNumIdOrgaoSistema(null);
      }

      $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
      if ($numIdSistema !== '') {
        $objItemMenuDTO->setNumIdSistema($numIdSistema);
      } else {
        $objItemMenuDTO->setNumIdSistema(null);
      }

      $numIdMenu = PaginaSip::getInstance()->recuperarCampo('selMenu');
      if ($numIdMenu !== '') {
        $objItemMenuDTO->setNumIdMenu($numIdMenu);
      } else {
        $objItemMenuDTO->setNumIdMenu(null);
      }

      if (isset($_GET['id_menu_superior']) && isset($_GET['id_item_menu_superior'])) {
        $objItemMenuDTO->setNumIdMenuPai($_GET['id_menu_superior']);
        $objItemMenuDTO->setNumIdItemMenuPai($_GET['id_item_menu_superior']);
      } else {
        if ($_POST['chkRaiz'] != '') {
          $bolRaiz = 'true';
          $objItemMenuDTO->setNumIdMenuPai(null);
          $objItemMenuDTO->setNumIdItemMenuPai(null);
        } else {
          $objItemMenuDTO->setNumIdMenuPai($_POST['selMenu']);
          $objItemMenuDTO->setNumIdItemMenuPai($_POST['selItemMenuSuperior']);
        }
      }


      $objItemMenuDTO->setNumIdRecurso($_POST['selRecurso']);
      $objItemMenuDTO->setStrRotulo($_POST['txtRotulo']);
      $objItemMenuDTO->setStrDescricao($_POST['txtDescricao']);
      $objItemMenuDTO->setStrIcone($_POST['txtIcone']);
      $objItemMenuDTO->setStrSinNovaJanela(PaginaSip::getInstance()->getCheckbox($_POST['chkSinNovaJanela']));

      $objItemMenuDTO->setNumSequencia(PaginaSip::getInstance()->recuperarCampo('txtSequencia'));

      $objItemMenuDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarItemMenu'])) {
        try {
          $objItemMenuRN = new ItemMenuRN();
          $objItemMenuDTO = $objItemMenuRN->cadastrar($objItemMenuDTO);
          PaginaSip::getInstance()->setStrMensagem('Item de Menu "' . $objItemMenuDTO->getStrRotulo() . '" cadastrado com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=item_menu_listar&acao_origem=item_menu_cadastrar' . PaginaSip::getInstance()->montarAncora($objItemMenuDTO->getNumIdMenu() . '-' . $objItemMenuDTO->getNumIdItemMenu())));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
        break;
      }
      break;

    case 'item_menu_alterar':
      $strTitulo = 'Alterar Item de Menu';

      $arrComandos[] = '<input type="submit" name="sbmAlterarItemMenu" value="Salvar" class="infraButton" />';
      $strDesabilitar = 'disabled="disabled"';

      $numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
      if ($numIdOrgaoSistema !== '') {
        $objItemMenuDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
      } else {
        $objItemMenuDTO->setNumIdOrgaoSistema(null);
      }

      $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
      if ($numIdSistema !== '') {
        $objItemMenuDTO->setNumIdSistema($numIdSistema);
      } else {
        $objItemMenuDTO->setNumIdSistema(null);
      }


      if (isset($_GET['id_menu']) && isset($_GET['id_item_menu'])) {
        $objItemMenuDTO->setNumIdMenu($_GET['id_menu']);
        $objItemMenuDTO->setNumIdItemMenu($_GET['id_item_menu']);

        $objItemMenuDTO->retTodos(true);
        $objItemMenuRN = new ItemMenuRN();
        $arr = $objItemMenuRN->listar($objItemMenuDTO);
        if (count($arr) == 0) {
          throw new InfraException("Registro não encontrado.");
        } else {
          $objItemMenuDTO = $arr[0];
        }

        if ($objItemMenuDTO->getNumIdMenuPai() == null && $objItemMenuDTO->getNumIdItemMenuPai() == null) {
          $bolRaiz = 'true';
        }
      } else {
        $objItemMenuDTO->setNumIdMenu($_POST['hdnIdMenu']);
        $objItemMenuDTO->setNumIdItemMenu($_POST['hdnIdItemMenu']);
        $objItemMenuDTO->setNumIdSistema($_POST['hdnIdSistema']);


        if ($_POST['chkRaiz'] != '') {
          $bolRaiz = 'true';
          $objItemMenuDTO->setNumIdMenuPai(null);
          $objItemMenuDTO->setNumIdItemMenuPai(null);
        } else {
          $objItemMenuDTO->setNumIdMenuPai($_POST['hdnIdMenu']);
          $objItemMenuDTO->setNumIdItemMenuPai($_POST['selItemMenuSuperior']);
        }

        $objItemMenuDTO->setNumIdRecurso($_POST['selRecurso']);
        $objItemMenuDTO->setStrRotulo($_POST['txtRotulo']);
        $objItemMenuDTO->setStrDescricao($_POST['txtDescricao']);
        $objItemMenuDTO->setStrIcone($_POST['txtIcone']);
        $objItemMenuDTO->setStrSinNovaJanela(PaginaSip::getInstance()->getCheckbox($_POST['chkSinNovaJanela']));
        $objItemMenuDTO->setNumSequencia($_POST['txtSequencia']);
        $objItemMenuDTO->setStrSinAtivo('S');
      }

      $strAncora = PaginaSip::getInstance()->montarAncora($objItemMenuDTO->getNumIdMenu() . '-' . $objItemMenuDTO->getNumIdItemMenu());

      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=item_menu_listar&acao_origem=item_menu_cadastrar' . $strAncora) . '\';" class="infraButton" />';

      if (isset($_POST['sbmAlterarItemMenu'])) {
        try {
          $objItemMenuRN = new ItemMenuRN();
          $objItemMenuRN->alterar($objItemMenuDTO);

          PaginaSip::getInstance()->setStrMensagem('Item de Menu "' . $objItemMenuDTO->getStrRotulo() . '" alterado com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=item_menu_listar&acao_origem=item_menu_cadastrar' . $strAncora));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }

      break;


    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null', '&nbsp;', $objItemMenuDTO->getNumIdOrgaoSistema());
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null', '&nbsp;', $objItemMenuDTO->getNumIdSistema(), $objItemMenuDTO->getNumIdOrgaoSistema());

  $strItensSelMenu = MenuINT::montarSelectNome('null', '&nbsp;', $objItemMenuDTO->getNumIdMenu(), $objItemMenuDTO->getNumIdSistema());

  if ($_GET['acao'] == 'item_menu_cadastrar') {
    //Carrega todos os itens do menu
    $strItensSelItemMenuSuperior = ItemMenuINT::montarSelectRamificacao('null', '&nbsp;', $objItemMenuDTO->getNumIdItemMenuPai(), $objItemMenuDTO->getNumIdMenu());
  } else {
    //Carrega todos os itens do menu EXCETO o que esta sendo alterado
    $strItensSelItemMenuSuperior = ItemMenuINT::montarSelectRamificacaoOutros('null', '&nbsp;', $objItemMenuDTO->getNumIdItemMenuPai(), $objItemMenuDTO->getNumIdMenu(), $objItemMenuDTO->getNumIdItemMenu());
  }

  $strItensSelRecurso = RecursoINT::montarSelectNome('null', '&nbsp;', $objItemMenuDTO->getNumIdRecurso(), $objItemMenuDTO->getNumIdSistema());
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Montar Menu');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
  #selOrgaoSistema {position:absolute;left:0%;top:40%;width:25%;}

  #lblSistema {position:absolute;left:0%;top:0%;width:25%;}
  #selSistema {position:absolute;left:0%;top:40%;width:25%;}

  #lblMenu {position:absolute;left:0%;top:0%;width:25%;}
  #selMenu {position:absolute;left:0%;top:40%;width:25%;}

  #divSinRaiz {position:absolute;left:0%;top:30%;}

  #lblItemMenuSuperior {position:absolute;left:0%;top:0%;width:75%;}
  #selItemMenuSuperior {position:absolute;left:0%;top:40%;width:75%;}

  #lblRecurso {position:absolute;left:0%;top:0%;width:50%;}
  #selRecurso {position:absolute;left:0%;top:40%;width:50%;}

  #lblRotulo {position:absolute;left:0%;top:0%;width:50%;}
  #txtRotulo {position:absolute;left:0%;top:40%;width:50%;}

  #lblDescricao {position:absolute;left:0%;top:0%;width:80%;}
  #txtDescricao {position:absolute;left:0%;top:40%;width:80%;}

  #lblIcone {position:absolute;left:0%;top:0%;width:50%;}
  #txtIcone {position:absolute;left:0%;top:40%;width:50%;}

  #lblSequencia {position:absolute;left:0%;top:0%;width:15%;}
  #txtSequencia {position:absolute;left:0%;top:40%;width:15%;}

  #divSinNovaJanela {position:absolute;left:0%;top:30%;}


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

  if (!infraSelectSelecionado(document.getElementById('selOrgaoSistema'))) {
  alert('Selecione um Órgão do Sistema.');
  document.getElementById('selOrgaoSistema').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selSistema'))) {
  alert('Selecione um Sistema.');
  document.getElementById('selSistema').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selMenu'))) {
  alert('Selecione um Menu.');
  document.getElementById('selMenu').focus();
  return false;
  }

  if (!document.getElementById('chkRaiz').checked){
  if (!infraSelectSelecionado(document.getElementById('selItemMenuSuperior'))) {
  alert('Selecione um Item de Menu Superior.');
  document.getElementById('selItemMenuSuperior').focus();
  return false;
  }
  }

  if (infraTrim(document.getElementById('txtRotulo').value)=='') {
  alert('Informe um Rótulo.');
  document.getElementById('txtRotulo').focus();
  return false;
  }


  if (infraTrim(document.getElementById('txtSequencia').value)=='') {
  alert('Informe uma Sequência.');
  document.getElementById('txtSequencia').focus();
  return false;
  }

  return true;
  }

  function formatarTela(bolRaiz){
  if (bolRaiz){
  document.getElementById('chkRaiz').checked=true;
  document.getElementById('lblItemMenuSuperior').style.visibility='hidden';
  document.getElementById('selItemMenuSuperior').className = 'infraSelectOculto';
  document.getElementById('selItemMenuSuperior').value = null;
  }else{
  document.getElementById('chkRaiz').checked=false;
  document.getElementById('lblItemMenuSuperior').style.visibility='visible';
  document.getElementById('selItemMenuSuperior').className = 'infraSelect';
  }
  }

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='item_menu_cadastrar'){
  document.getElementById('selOrgaoSistema').focus();
  } else if ('<?=$_GET['acao']?>'=='item_menu_consultar'){
  infraDesabilitarCamposAreaDados();
  }

  formatarTela(<?=$bolRaiz?>);
  }

  function trocarOrgaoSistema(obj){
  document.getElementById('selSistema').value='null';
  trocarSistema(obj);
  }

  function trocarSistema(obj){
  document.getElementById('selMenu').value='null';
  document.getElementById('selItemMenuSuperior').value='null';
  document.getElementById('selRecurso').value='null';
  obj.form.submit();
  }

  function trocarMenu(obj){
  document.getElementById('selItemMenuSuperior').value='null';
  obj.form.submit();
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmItemMenuCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink(basename(__FILE__) . '?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="o" class="infraLabelObrigatorio">Órgã<span
        class="infraTeclaAtalho">o</span> do Sistema:</label>
    <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelOrgaoSistema?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <select id="selSistema" name="selSistema" onchange="trocarSistema(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelSistema?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblMenu" for="selMenu" accesskey="M" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">M</span>enu:</label>
    <select id="selMenu" name="selMenu" onchange="trocarMenu(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelMenu?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <div id="divSinRaiz" class="infraDivCheckbox">
      <input type="checkbox" id="chkRaiz" name="chkRaiz" onclick='formatarTela(this.checked)' class="infraCheckbox"/>
      <label id="lblRaiz" accesskey="R" for="chkRaiz" class="infraLabelCheckbox">Raiz</label>
    </div>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblItemMenuSuperior" for="selItemMenuSuperior" accesskey="I" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">I</span>tem Menu Superior:</label>
    <select id="selItemMenuSuperior" name="selItemMenuSuperior" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelItemMenuSuperior?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblRecurso" for="selRecurso" accesskey="R" class="infraLabelOpcional"><span
        class="infraTeclaAtalho">R</span>ecurso:</label>
    <select id="selRecurso" name="selRecurso" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelRecurso?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblRotulo" for="txtRotulo" accesskey="u" class="infraLabelObrigatorio">Rót<span class="infraTeclaAtalho">u</span>lo:</label>
    <input type="text" id="txtRotulo" name="txtRotulo" class="infraText"
           value="<?=PaginaSip::tratarHTML($objItemMenuDTO->getStrRotulo())?>" maxlength="50"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblDescricao" for="txtDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
    <input type="text" id="txtDescricao" name="txtDescricao" class="infraText"
           value="<?=PaginaSip::tratarHTML($objItemMenuDTO->getStrDescricao())?>" maxlength="200"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblIcone" for="txtIcone" accesskey="" class="infraLabelOpcional">Ícone:</label>
    <input type="text" id="txtIcone" name="txtIcone" class="infraText"
           value="<?=PaginaSip::tratarHTML($objItemMenuDTO->getStrIcone())?>" maxlength="250"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblSequencia" for="txtSequencia" accesskey="c" class="infraLabelObrigatorio">Sequên<span
        class="infraTeclaAtalho">c</span>ia:</label>
    <input type="text" id="txtSequencia" name="txtSequencia" onkeypress="return infraMascaraNumero(this, event)"
           class="infraText" value="<?=PaginaSip::tratarHTML($objItemMenuDTO->getNumSequencia())?>" maxlength="10"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('3em');
    ?>

    <div id="divSinNovaJanela" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinNovaJanela" name="chkSinNovaJanela" <?=PaginaSip::getInstance()->setCheckbox($objItemMenuDTO->getStrSinNovaJanela())?> class="infraCheckbox"/>
      <label id="lblSinNovaJanela" accesskey="" for="chkSinNovaJanela" class="infraLabelCheckbox">Abrir em uma nova
        janela</label>
    </div>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    ?>

    <input type="hidden" name="hdnIdMenu" value="<?=$objItemMenuDTO->getNumIdMenu();?>"/>
    <input type="hidden" name="hdnIdItemMenu" value="<?=$objItemMenuDTO->getNumIdItemMenu();?>"/>
    <input type="hidden" name="hdnIdSistema" value="<?=$objItemMenuDTO->getNumIdSistema();?>"/>

    <?
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>