<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/12/2006 - criado por mga
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

  //Pega dados do link da tela de listagem de menu
  if (isset($_GET['id_menu']) && isset($_GET['id_sistema']) && isset($_GET['id_orgao_sistema'])) {
    PaginaSip::getInstance()->salvarCampo('selOrgaoSistema', $_GET['id_orgao_sistema']);
    PaginaSip::getInstance()->salvarCampo('selSistema', $_GET['id_sistema']);
    PaginaSip::getInstance()->salvarCampo('selMenu', $_GET['id_menu']);
  } else {
    PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema', 'selMenu'));
  }
  switch ($_GET['acao']) {
    case 'item_menu_excluir':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjItemMenuDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $arrStrIdComposto = explode('-', $arrStrIds[$i]);
          $objItemMenuDTO = new ItemMenuDTO();
          $objItemMenuDTO->setNumIdMenu($arrStrIdComposto[0]);
          $objItemMenuDTO->setNumIdItemMenu($arrStrIdComposto[1]);
          $arrObjItemMenuDTO[] = $objItemMenuDTO;
        }


        $objItemMenuRN = new ItemMenuRN();
        $objItemMenuRN->excluir($arrObjItemMenuDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'item_menu_desativar':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjItemMenuDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $arrStrIdComposto = explode('-', $arrStrIds[$i]);
          $objItemMenuDTO = new ItemMenuDTO();
          $objItemMenuDTO->setNumIdMenu($arrStrIdComposto[0]);
          $objItemMenuDTO->setNumIdItemMenu($arrStrIdComposto[1]);
          $arrObjItemMenuDTO[] = $objItemMenuDTO;
        }
        $objItemMenuRN = new ItemMenuRN();
        $objItemMenuRN->desativar($arrObjItemMenuDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;


    case 'item_menu_listar':
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  if (SessaoSip::getInstance()->verificarPermissao('item_menu_cadastrar')) {
    $arrComandos[] = '<input type="button" id="btnNovo" value="Novo Item" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=item_menu_cadastrar&acao_retorno=item_menu_listar&acao_origem=item_menu_listar') . '\';" class="infraButton" />';
  }

  $objItemMenuDTO = new ItemMenuDTO();

  $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());

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

  $bolListar = false;
  //Verifica se o menu pertence ao sistema
  if ($objItemMenuDTO->getNumIdSistema() != null && $objItemMenuDTO->getNumIdMenu() != null) {
    $dto = new MenuDTO();
    $dto->retStrNome();
    $dto->setNumIdSistema($objItemMenuDTO->getNumIdSistema());
    $dto->setNumIdMenu($objItemMenuDTO->getNumIdMenu());
    $objMenuRN = new MenuRN();
    if ($objMenuRN->consultar($dto) != null) {
      $bolListar = true;
    }
  }

  if ($bolListar) {
    $objItemMenuRN = new ItemMenuRN();
    $arrObjItemMenuDTO = $objItemMenuRN->listarHierarquia($objItemMenuDTO);

    $numRegistros = count($arrObjItemMenuDTO);

    if ($numRegistros > 0) {
      $bolAcaoCadastrar = SessaoSip::getInstance()->verificarPermissao('item_menu_cadastrar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('item_menu_alterar');
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('item_menu_excluir');
      //$bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('item_menu_desativar');
      $bolAcaoDesativar = false;

      //Montar ações múltiplas
      $bolCheck = true;

      if ($bolAcaoExcluir) {
        //$bolCheck = true;
        //$arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
        $strLinkExcluir = SessaoSip::getInstance()->assinarLink('item_menu_lista.php?acao=item_menu_excluir&acao_origem=item_menu_listar');
      }

      if ($bolAcaoDesativar) {
        //$bolCheck = true;
        //$arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
        $strLinkDesativar = SessaoSip::getInstance()->assinarLink('item_menu_lista.php?acao=item_menu_desativar&acao_origem=item_menu_listar');
      }

      $arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';

      $strResultado = '';
      $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Itens de Menu cadastrados">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela('Itens de Menu', $numRegistros) . '</caption>';
      $strResultado .= '<tr>';
      if ($bolCheck) {
        $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>';
      }
      $strResultado .= '<th class="infraTh">Ramificação</th>';
      $strResultado .= '<th class="infraTh">Sequência</th>';
      $strResultado .= '<th class="infraTh">Recurso</th>';
      //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objItemMenuDTO,'Rótulo','Rotulo',$arrObjItemMenuDTO).'</th>';
      //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objItemMenuDTO,'Descrição','Descricao',$arrObjItemMenuDTO).'</th>';
      //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objItemMenuDTO,'Menu','NomeMenu',$arrObjItemMenuDTO).'</th>';
      $strResultado .= '<th class="infraTh">Ações</th>';
      $strResultado .= '</tr>' . "\n";
      for ($i = 0; $i < $numRegistros; $i++) {
        if (($i + 2) % 2) {
          $strResultado .= '<tr class="infraTrEscura">';
        } else {
          $strResultado .= '<tr class="infraTrClara">';
        }
        if ($bolCheck) {
          $strResultado .= '<td>' . PaginaSip::getInstance()->getTrCheck($i, $arrObjItemMenuDTO[$i]->getNumIdMenu() . '-' . $arrObjItemMenuDTO[$i]->getNumIdItemMenu(), $arrObjItemMenuDTO[$i]->getStrRotulo()) . '</td>';
        }
        $strResultado .= '<td align="left" width="70%">' . PaginaSip::tratarHTML($arrObjItemMenuDTO[$i]->getStrRamificacao()) . '</td>';
        $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjItemMenuDTO[$i]->getNumSequencia()) . '</td>';
        $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjItemMenuDTO[$i]->getStrNomeRecurso()) . '</td>';
        //$strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjItemMenuDTO[$i]->getStrRotulo()).'</td>';
        //$strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjItemMenuDTO[$i]->getStrDescricao()).'</td>';
        //$strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjItemMenuDTO[$i]->getStrNomeMenu()).'</td>';
        $strResultado .= '<td align="center" width="12%">';

        if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
          $strId = $arrObjItemMenuDTO[$i]->getNumIdMenu() . '-' . $arrObjItemMenuDTO[$i]->getNumIdItemMenu();
          $strDescricao = PaginaSip::getInstance()->formatarParametrosJavaScript($arrObjItemMenuDTO[$i]->getStrRotulo());
        }

        if ($bolAcaoCadastrar) {
          $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=item_menu_cadastrar&acao_origem=item_menu_listar&id_orgao_sistema=' . $numIdOrgao . '&id_sistema=' . $arrObjItemMenuDTO[$i]->getNumIdSistema() . '&id_menu_superior=' . $arrObjItemMenuDTO[$i]->getNumIdMenu() . '&id_item_menu_superior=' . $arrObjItemMenuDTO[$i]->getNumIdItemMenu()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeMais() . '" title="Adicionar Subitem" alt="Adicionar Subitem" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoAlterar) {
          $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=item_menu_alterar&&acao_origem=item_menu_listar&acao_retorno=item_menu_listar&id_menu=' . $arrObjItemMenuDTO[$i]->getNumIdMenu() . '&id_item_menu=' . $arrObjItemMenuDTO[$i]->getNumIdItemMenu()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Item de Menu" alt="Alterar Item de Menu" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoDesativar) {
          $strResultado .= '<a onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeDesativar() . '" title="Desativar Item de Menu" alt="Desativar Item de Menu" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoExcluir) {
          $strResultado .= '<a onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Item de Menu" alt="Excluir Item de Menu" class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '</td></tr>' . "\n";
      }
      $strResultado .= '</table>';
    }
  }
  $arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton" />';

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null', '&nbsp;', $numIdOrgao);
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null', '&nbsp;', $numIdSistema, $numIdOrgao);
  $strItensSelMenu = MenuINT::montarSelectNome('null', '&nbsp;', $numIdMenu, $numIdSistema);
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

  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
  #selOrgaoSistema {position:absolute;left:0%;top:12%;width:20%;}

  #lblSistema {position:absolute;left:0%;top:30%;width:20%;}
  #selSistema {position:absolute;left:0%;top:42%;width:20%;}

  #lblMenu {position:absolute;left:0%;top:60%;width:20%;}
  #selMenu {position:absolute;left:0%;top:72%;width:20%;}
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
  function inicializar(){
  if ('<?=$_GET['acao']?>'=='item_menu_selecionar'){
  infraReceberSelecao();
  }
  infraEfeitoTabelas();
  }

<?
if ($bolAcaoExcluir) { ?>

  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Item de Menu \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmItemMenuLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmItemMenuLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Item de Menu selecionado.');
  return;
  }
  if (confirm("Confirma exclusão dos Itens de Menu selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmItemMenuLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmItemMenuLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoDesativar) { ?>

  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Item de Menu \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmItemMenuLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmItemMenuLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Item de Menu selecionado.');
  return;
  }
  if (confirm("Confirma desativação dos Itens de Menu selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmItemMenuLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmItemMenuLista').submit();
  }
  }
  <?
} ?>

  function trocarOrgaoSistema(obj){
  document.getElementById('selSistema').value='null';
  trocarSistema(obj);
  }

  function trocarSistema(obj){
  document.getElementById('selMenu').value='null';
  obj.form.submit();
  }


<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody('Montar Menu', 'onload="inicializar();"');
?>
  <form id="frmItemMenuLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink(basename(__FILE__) . '?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao('Montar Menu');
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados('16em');
    ?>

    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="o" class="infraLabelOpcional">Órgã<span
        class="infraTeclaAtalho">o</span> do Sistema:</label>
    <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgaoSistema?>
    </select>

    <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelOpcional"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <select id="selSistema" name="selSistema" onchange="trocarSistema(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelSistema?>
    </select>

    <label id="lblMenu" for="selMenu" accesskey="H" class="infraLabelOpcional"><span class="infraTeclaAtalho">M</span>enu:</label>
    <select id="selMenu" name="selMenu" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelMenu?>
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