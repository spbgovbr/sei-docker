<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/01/2007 - criado por mga
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

  switch ($_GET['acao']) {
    case 'hierarquia_excluir':
      try {
        $arrObjHierarquiaDTO = array();
        $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
        for ($i = 0; $i < count($arrStrId); $i++) {
          $objHierarquiaDTO = new HierarquiaDTO();
          $objHierarquiaDTO->setNumIdHierarquia($arrStrId[$i]);
          $arrObjHierarquiaDTO[] = $objHierarquiaDTO;
        }
        $objHierarquiaRN = new HierarquiaRN();
        $objHierarquiaRN->excluir($arrObjHierarquiaDTO);
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'hierarquia_desativar':
      try {
        $arrObjHierarquiaDTO = array();
        $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
        for ($i = 0; $i < count($arrStrId); $i++) {
          $objHierarquiaDTO = new HierarquiaDTO();
          $objHierarquiaDTO->setNumIdHierarquia($arrStrId[$i]);
          $arrObjHierarquiaDTO[] = $objHierarquiaDTO;
        }
        $objHierarquiaRN = new HierarquiaRN();
        $objHierarquiaRN->desativar($arrObjHierarquiaDTO);
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'hierarquia_reativar':
      $strTitulo = 'Reativar Hierarquia';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrObjHierarquiaDTO = array();
          $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
          for ($i = 0; $i < count($arrStrId); $i++) {
            $objHierarquiaDTO = new HierarquiaDTO();
            $objHierarquiaDTO->setNumIdHierarquia($arrStrId[$i]);
            $arrObjHierarquiaDTO[] = $objHierarquiaDTO;
          }
          $objHierarquiaRN = new HierarquiaRN();
          $objHierarquiaRN->reativar($arrObjHierarquiaDTO);
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
      }
      break;

    case 'hierarquia_listar':
      $strTitulo = 'Hierarquias';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  if (SessaoSip::getInstance()->verificarPermissao('hierarquia_cadastrar')) {
    $arrComandos[] = '<input type="button" id="btnNova" value="Nova" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=hierarquia_cadastrar&acao_retorno=' . $_GET['acao']) . '\';" class="infraButton" />';
  }
  $objHierarquiaDTO = new HierarquiaDTO();
  $objHierarquiaDTO->retTodos();


  if ($_GET['acao'] == 'hierarquia_reativar') {
    //Lista somente inativos
    $objHierarquiaDTO->setBolExclusaoLogica(false);
    $objHierarquiaDTO->setStrSinAtivo('N');
  }

  $objHierarquiaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

  $objHierarquiaRN = new HierarquiaRN();
  $arrObjHierarquiaDTO = $objHierarquiaRN->listar($objHierarquiaDTO);

  $numRegistros = count($arrObjHierarquiaDTO);

  if ($numRegistros > 0) {
    if ($_GET['acao'] == 'hierarquia_selecionar') {
      $bolCheck = true;
      $bolAcaoImprimir = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('hierarquia_consultar');
      $bolAcaoClonar = false;
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('hierarquia_alterar');
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolAcaoReativar = false;
    } else {
      if ($_GET['acao'] == 'hierarquia_reativar') {
        $bolAcaoImprimir = true;
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('hierarquia_consultar');
        $bolAcaoClonar = false;
        $bolAcaoAlterar = false;
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('hierarquia_excluir');
        $bolAcaoDesativar = false;
        $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('hierarquia_reativar');
      } else {
        $bolAcaoImprimir = true;
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('hierarquia_consultar');
        $bolAcaoClonar = SessaoSip::getInstance()->verificarPermissao('hierarquia_clonar');
        $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('hierarquia_alterar');
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('hierarquia_excluir');
        $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('hierarquia_desativar');
        $bolAcaoReativar = false;
      }
    }

    //Montar ações múltiplas
    $bolCheck = false;
    if ($bolAcaoExcluir) {
      $bolCheck = true;
      //$arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=hierarquia_excluir&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoDesativar) {
      $bolCheck = true;
      //$arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=hierarquia_desativar&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoReativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=hierarquia_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
    }

    $arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';


    if ($_GET['acao'] != 'hierarquia_reativar') {
      $strSumarioTabela = 'Tabela de Hierarquias.';
      $strCaptionTabela = 'Hierarquias';
    } else {
      $strSumarioTabela = 'Tabela de Hierarquias Inativas.';
      $strCaptionTabela = 'Hierarquias Inativas';
    }

    $strResultado = '';
    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>';
    }
    $strResultado .= '<th class="infraTh">Nome</th>';
    $strResultado .= '<th class="infraTh" width="15%">Data Inicial</th>';
    $strResultado .= '<th class="infraTh" width="15%">Data Final</th>';
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>';
    $strResultado .= '</tr>' . "\n";
    for ($i = 0; $i < $numRegistros; $i++) {
      if (($i + 2) % 2) {
        $strResultado .= '<tr class="infraTrEscura">';
      } else {
        $strResultado .= '<tr class="infraTrClara">';
      }
      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjHierarquiaDTO[$i]->getNumIdHierarquia(), $arrObjHierarquiaDTO[$i]->getStrNome()) . '</td>';
      }
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjHierarquiaDTO[$i]->getStrNome()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjHierarquiaDTO[$i]->getDtaDataInicio()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjHierarquiaDTO[$i]->getDtaDataFim()) . '</td>';
      $strResultado .= '<td align="center">';

      if ($bolAcaoClonar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=hierarquia_clonar&&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_hierarquia_origem=' . $arrObjHierarquiaDTO[$i]->getNumIdHierarquia()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeClonar() . '" title="Clonar Hierarquia" alt="Clonar Hierarquia" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=hierarquia_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_hierarquia=' . $arrObjHierarquiaDTO[$i]->getNumIdHierarquia()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Hierarquia" alt="Consultar Hierarquia" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=hierarquia_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_hierarquia=' . $arrObjHierarquiaDTO[$i]->getNumIdHierarquia()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Hierarquia" alt="Alterar Hierarquia" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjHierarquiaDTO[$i]->getNumIdHierarquia();
        $strDescricao = PaginaSip::formatarParametrosJavaScript($arrObjHierarquiaDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar) {
        $strResultado .= '<a onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeDesativar() . '" title="Desativar Hierarquia" alt="Desativar Hierarquia" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar) {
        $strResultado .= '<a onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeReativar() . '" title="Reativar Hierarquia" alt="Reativar Hierarquia" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir) {
        $strResultado .= '<a onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Hierarquia" alt="Excluir Hierarquia" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton" />';
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='hierarquia_selecionar'){
  infraReceberSelecao();
  }
  infraEfeitoTabelas();
  }

<?
if ($bolAcaoExcluir) { ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Hierarquia \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmHierarquiaLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmHierarquiaLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Hierarquia selecionada.');
  return;
  }
  if (confirm("Confirma exclusão das Hierarquias selecionadas?")){
  document.getElementById('frmHierarquiaLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmHierarquiaLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoDesativar) { ?>
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Hierarquia \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmHierarquiaLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmHierarquiaLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Hierarquia selecionada.');
  return;
  }
  if (confirm("Confirma desativação das Hierarquias selecionadas?")){
  document.getElementById('frmHierarquiaLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmHierarquiaLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoReativar) { ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Hierarquia \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmHierarquiaLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmHierarquiaLista').submit();
  }
  }

  function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Hierarquia selecionada.');
  return;
  }
  if (confirm("Confirma reativação das Hierarquias selecionadas?")){
  document.getElementById('frmHierarquiaLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmHierarquiaLista').submit();
  }
  }
  <?
} ?>

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmHierarquiaLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink(basename(__FILE__) . '?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao('Hierarquias');
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->abrirAreaDados('5em');
    //PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>