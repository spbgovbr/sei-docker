<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
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

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema'));

  switch ($_GET['acao']) {
    case 'menu_excluir':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjMenuDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objMenuDTO = new MenuDTO();
          $objMenuDTO->setNumIdMenu($arrStrIds[$i]);
          $arrObjMenuDTO[] = $objMenuDTO;
        }
        $objMenuRN = new MenuRN();
        $objMenuRN->excluir($arrObjMenuDTO);
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'menu_desativar':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjMenuDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objMenuDTO = new MenuDTO();
          $objMenuDTO->setNumIdMenu($arrStrIds[$i]);
          $arrObjMenuDTO[] = $objMenuDTO;
        }
        $objMenuRN = new MenuRN();
        $objMenuRN->desativar($arrObjMenuDTO);
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'menu_listar':
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  if (SessaoSip::getInstance()->verificarPermissao('menu_cadastrar')) {
    $arrComandos[] = '<input type="button" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=menu_cadastrar') . '\';" class="infraButton" />';
  }
  $objMenuDTO = new MenuDTO(true);
  $objMenuDTO->retTodos();

  //ORGAO
  $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
  if ($numIdOrgao !== '') {
    $objMenuDTO->setNumIdOrgaoSistema($numIdOrgao);
  }

  //SISTEMA
  $numIdSistema = '';
  if ($numIdOrgao !== '') {
    $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
    if ($numIdSistema !== '') {
      $objMenuDTO->setNumIdSistema($numIdSistema);
    }
  } else {
    //Para todos os orgãos os sistemas podem se repetir então não possibilita
    //escolha (desabilita combo)
    $strDesabilitar = 'disabled="disabled"';
  }

  PaginaSip::getInstance()->prepararOrdenacao($objMenuDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  $objMenuRN = new MenuRN();
  $arrObjMenuDTO = $objMenuRN->listarAdministrados($objMenuDTO);

  $numRegistros = count($arrObjMenuDTO);

  if ($numRegistros > 0) {
    //$bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('menu_consultar');
    $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('menu_alterar');
    $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('menu_excluir');
    $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('menu_desativar');
    $bolAcaoListarItens = SessaoSip::getInstance()->verificarPermissao('item_menu_listar');

    //Montar ações múltiplas
    $bolCheck = true;

    if ($bolAcaoExcluir) {
      //$bolCheck = true;
      //$arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('menu_lista.php?acao=menu_excluir&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoDesativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('menu_lista.php?acao=menu_desativar&acao_origem=' . $_GET['acao']);
    }

    $arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';

    $strResultado = '';
    $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Menus cadastrados">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela('Menus', $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>' . "\n";
    }
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objMenuDTO, 'Nome', 'Nome', $arrObjMenuDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objMenuDTO, 'Descrição', 'Descricao', $arrObjMenuDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objMenuDTO, 'Sistema', 'SiglaSistema', $arrObjMenuDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    for ($i = 0; $i < $numRegistros; $i++) {
      if (($i + 2) % 2) {
        $strResultado .= '<tr class="infraTrEscura">';
      } else {
        $strResultado .= '<tr class="infraTrClara">';
      }
      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjMenuDTO[$i]->getNumIdMenu(), $arrObjMenuDTO[$i]->getStrNome()) . '</td>';
      }
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjMenuDTO[$i]->getStrNome()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjMenuDTO[$i]->getStrDescricao()) . '</td>';

      $strResultado .= '<td align="center">';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjMenuDTO[$i]->getStrDescricaoSistema()) . '" title="' . PaginaSip::tratarHTML($arrObjMenuDTO[$i]->getStrDescricaoSistema()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjMenuDTO[$i]->getStrSiglaSistema()) . '</a>';
      $strResultado .= '&nbsp;/&nbsp;';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjMenuDTO[$i]->getStrDescricaoOrgaoSistema()) . '" title="' . PaginaSip::tratarHTML($arrObjMenuDTO[$i]->getStrDescricaoOrgaoSistema()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjMenuDTO[$i]->getStrSiglaOrgaoSistema()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td align="center">';

      //if ($bolAcaoConsultar){
      //  $strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=menu_consultar&id_menu='.$arrObjMenuDTO[$i]->getNumIdMenu())).'" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeConsultar().'" title="Consultar Menu" alt="Consultar Menu" class="infraImg" /></a>&nbsp;';
      //}

      if ($bolAcaoListarItens) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=item_menu_listar&acao_retorno=menu_listar&id_menu=' . $arrObjMenuDTO[$i]->getNumIdMenu() . '&id_sistema=' . $arrObjMenuDTO[$i]->getNumIdSistema() . '&id_orgao_sistema=' . $arrObjMenuDTO[$i]->getNumIdOrgaoSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/menu.svg" title="Listar Itens de Menu" alt="Listar Itens de Menu" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=menu_alterar&id_menu=' . $arrObjMenuDTO[$i]->getNumIdMenu()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Menu" alt="Alterar Menu" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoExcluir) {
        $strId = $arrObjMenuDTO[$i]->getNumIdMenu();
        $strDescricao = $arrObjMenuDTO[$i]->getStrNome();
      }

      if ($bolAcaoDesativar) {
        $strResultado .= '<a onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeDesativar() . '" title="Desativar Menu" alt="Desativar Menu" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir) {
        $strResultado .= '<a onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Menu" alt="Excluir Menu" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton" />';

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('', 'Todos', $numIdOrgao);
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('', 'Todos', $numIdSistema, $numIdOrgao);
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Menus');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
  #selOrgaoSistema {position:absolute;left:0%;top:20%;width:20%;}

  #lblSistema {position:absolute;left:0%;top:50%;width:20%;}
  #selSistema {position:absolute;left:0%;top:70%;width:20%;}
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
  function inicializar(){
  if ('<?=$_GET['acao']?>'=='menu_selecionar'){
  infraReceberSelecao();
  }
  infraEfeitoTabelas();
  }

<?
if ($bolAcaoExcluir) { ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Menu \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmMenuLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmMenuLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Menu selecionado.');
  return;
  }
  if (confirm("Confirma exclusão dos Menus selecionados?")){
  document.getElementById('frmMenuLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmMenuLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoDesativar) { ?>
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Menu \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmMenuLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmMenuLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Menu selecionado.');
  return;
  }
  if (confirm("Confirma desativação dos Menus selecionados?")){
  document.getElementById('frmMenuLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmMenuLista').submit();
  }
  }
  <?
} ?>

  function trocarOrgaoSistema(obj){
  document.getElementById('selSistema').value='null';
  obj.form.submit();
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody('Menus', 'onload="inicializar();"');
?>
  <form id="frmMenuLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink(basename(__FILE__) . '?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao('Menus');
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados('10em');
    ?>

    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="o" class="infraLabelOpcional">Órgã<span
        class="infraTeclaAtalho">o</span> do Sistema:</label>
    <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgaoSistema?>
    </select>

    <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelOpcional"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <select id="selSistema" name="selSistema" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> >
      <?=$strItensSelSistema?>
    </select>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros, true);
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>