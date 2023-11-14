<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/10/2011 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////


  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema'));

  switch ($_GET['acao']) {
    case 'regra_auditoria_excluir':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjRegraAuditoriaDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
          $objRegraAuditoriaDTO->setNumIdRegraAuditoria($arrStrIds[$i]);
          $arrObjRegraAuditoriaDTO[] = $objRegraAuditoriaDTO;
        }
        $objRegraAuditoriaRN = new RegraAuditoriaRN();
        $objRegraAuditoriaRN->excluir($arrObjRegraAuditoriaDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      break;

    case 'regra_auditoria_desativar':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjRegraAuditoriaDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
          $objRegraAuditoriaDTO->setNumIdRegraAuditoria($arrStrIds[$i]);
          $arrObjRegraAuditoriaDTO[] = $objRegraAuditoriaDTO;
        }
        $objRegraAuditoriaRN = new RegraAuditoriaRN();
        $objRegraAuditoriaRN->desativar($arrObjRegraAuditoriaDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'regra_auditoria_reativar':
      $strTitulo = 'Reativar Regras de Auditoria';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
          $arrObjRegraAuditoriaDTO = array();
          for ($i = 0; $i < count($arrStrIds); $i++) {
            $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
            $objRegraAuditoriaDTO->setNumIdRegraAuditoria($arrStrIds[$i]);
            $arrObjRegraAuditoriaDTO[] = $objRegraAuditoriaDTO;
          }
          $objRegraAuditoriaRN = new RegraAuditoriaRN();
          $objRegraAuditoriaRN->reativar($arrObjRegraAuditoriaDTO);
          PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
      }
      break;

    case 'regra_auditoria_listar':
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" id="sbmPesquisar" name="sbmPesquisar" class="infraButton">Pesquisar</button>';

  if (SessaoSip::getInstance()->verificarPermissao('regra_auditoria_cadastrar')) {
    $arrComandos[] = '<input type="button" id="btnNova" value="Nova" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=regra_auditoria_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\';" class="infraButton" />';
  }

  $objRegraAuditoriaDTO = new RegraAuditoriaDTO(true);
  $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
  $objRegraAuditoriaDTO->retStrDescricao();
  $objRegraAuditoriaDTO->retStrSinAtivo();

  //ORGAO SISTEMA
  $numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
  if ($numIdOrgaoSistema !== '') {
    $objRegraAuditoriaDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
    $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema', 'null');
  } else {
    $numIdSistema = '';
  }

  if ($objRegraAuditoriaDTO->getNumIdOrgaoSistema() === null) {
    $strDesabilitarSistema = 'disabled="disabled"';
  }

  if ($numIdSistema !== '') {
    $objRegraAuditoriaDTO->setNumIdSistema($numIdSistema);
  } else {
    $objRegraAuditoriaDTO->setNumIdSistema(null);
  }


  //if ($_GET['acao'] == 'regra_auditoria_reativar'){
  //Lista somente inativos
  $objRegraAuditoriaDTO->setBolExclusaoLogica(false);
  //$objRegraAuditoriaDTO->setStrSinAtivo('N');
  //}

  $objRegraAuditoriaDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_ASC);

  PaginaSip::getInstance()->prepararPaginacao($objRegraAuditoriaDTO);

  $objRegraAuditoriaRN = new RegraAuditoriaRN();
  $arrObjRegraAuditoriaDTO = $objRegraAuditoriaRN->listar($objRegraAuditoriaDTO);

  PaginaSip::getInstance()->processarPaginacao($objRegraAuditoriaDTO);

  $numRegistros = count($arrObjRegraAuditoriaDTO);

  if ($numRegistros > 0) {
    if ($_GET['acao'] == 'regra_auditoria_reativar') {
      $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('regra_auditoria_reativar');
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('regra_auditoria_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('regra_auditoria_excluir');
      $bolAcaoDesativar = false;
    } else {
      $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('regra_auditoria_reativar');
      $bolAcaoImprimir = true;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('regra_auditoria_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('regra_auditoria_alterar');
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('regra_auditoria_excluir');
      $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('regra_auditoria_desativar');
    }


    //Montar ações múltiplas
    $bolCheck = false;

    if ($bolAcaoExcluir) {
      $bolCheck = true;
      $arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=regra_auditoria_excluir&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoDesativar) {
      $bolCheck = true;
      //$arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=regra_auditoria_desativar&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoReativar) {
      $bolCheck = true;
      //$arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=regra_auditoria_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
    }


    $arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';

    $strResultado = '';
    $strResultado .= '<table width="70%" class="infraTable" summary="Tabela de Regras de Auditoria cadastradas">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela('Regras de Auditoria', $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>';
    }

    //$strResultado .= '<th class="infraTh" width="15%">ID</th>';
    $strResultado .= '<th class="infraTh" width="70%">Regra</th>';
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objRegraAuditoriaDTO,'Usuário','SiglaUsuario',$arrObjRegraAuditoriaDTO).'</th>';
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objRegraAuditoriaDTO,'Unidade','SiglaUnidade',$arrObjRegraAuditoriaDTO).'</th>';
    $strResultado .= '<th class="infraTh">Ações</th>';
    $strResultado .= '</tr>' . "\n";
    for ($i = 0; $i < $numRegistros; $i++) {
      if ($arrObjRegraAuditoriaDTO[$i]->getStrSinAtivo() == 'S') {
        $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        $strResultado .= $strCssTr;
      } else {
        $strCssTr = '<tr class="trVermelha">';
        $strResultado .= $strCssTr;
      }

      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjRegraAuditoriaDTO[$i]->getNumIdRegraAuditoria(), $arrObjRegraAuditoriaDTO[$i]->getNumIdRegraAuditoria()) . '</td>';
      }
      //$strResultado .= '<td align="center">'.$arrObjRegraAuditoriaDTO[$i]->getNumIdRegraAuditoria().'</td>';
      $strResultado .= '<td align="left">' . PaginaSip::tratarHTML($arrObjRegraAuditoriaDTO[$i]->getStrDescricao()) . '</td>';

      /*
            $strResultado .= '<td align="center">';
            if ($arrObjRegraAuditoriaDTO[$i]->getStrSiglaUsuario()!=''){
              $strResultado .= $arrObjRegraAuditoriaDTO[$i]->getStrSiglaUsuario().' / '.$arrObjRegraAuditoriaDTO[$i]->getStrSiglaOrgaoUsuario();
            }else{
              $strResultado .= '&nbsp;';
            }
            $strResultado .= '</td>';

            $strResultado .= '<td align="center">';
            if ($arrObjRegraAuditoriaDTO[$i]->getStrSiglaUnidade()!=''){
              $strResultado .= $arrObjRegraAuditoriaDTO[$i]->getStrSiglaUnidade().' / '.$arrObjRegraAuditoriaDTO[$i]->getStrSiglaOrgaoUnidade();
            }else{
              $strResultado .= '&nbsp;';
            }
            $strResultado .= '</td>';
            */
      $strResultado .= '<td align="center">';

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=regra_auditoria_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_auditoria=' . $arrObjRegraAuditoriaDTO[$i]->getNumIdRegraAuditoria()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Regra de Auditoria" alt="Consultar Regra de Auditoria" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=regra_auditoria_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_auditoria=' . $arrObjRegraAuditoriaDTO[$i]->getNumIdRegraAuditoria()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Regra de Auditoria" alt="Alterar Regra de Auditoria" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjRegraAuditoriaDTO[$i]->getNumIdRegraAuditoria();
        $strDescricao = $arrObjRegraAuditoriaDTO[$i]->getStrDescricao();
      }

      if ($bolAcaoDesativar && $arrObjRegraAuditoriaDTO[$i]->getStrSinAtivo() == 'S') {
        $strResultado .= '<a onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeDesativar() . '" title="Desativar Regra de Auditoria" alt="Desativar Regra de Auditoria" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjRegraAuditoriaDTO[$i]->getStrSinAtivo() == 'N') {
        $strResultado .= '<a onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeReativar() . '" title="Reativar Regra de Auditoria" alt="Reativar Regra de Auditoria" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir) {
        $strResultado .= '<a onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Regra de Auditoria" alt="Excluir Regra de Auditoria" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton" />';

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAutorizados('null', '&nbsp;', $numIdOrgaoSistema);
  if ($numIdOrgaoSistema !== 'null') {
    $strItensSelSistema = SistemaINT::montarSelectSiglaAutorizados('null', '&nbsp;', $numIdSistema, $numIdOrgaoSistema);
  }
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Regras de Auditoria');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:22%;}
  #selOrgaoSistema {position:absolute;left:0%;top:18%;width:22%;}

  #lblSistema {position:absolute;left:0%;top:50%;width:22%;}
  #selSistema {position:absolute;left:0%;top:68%;width:22%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  infraEfeitoTabelas();
  }

<?
if ($bolAcaoExcluir) { ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Regra de Auditoria \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmRegraAuditoriaLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmRegraAuditoriaLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Regra de Auditoria selecionada.');
  return;
  }
  if (confirm("Confirma exclusão das Regras de Auditoria selecionadas?")){
  document.getElementById('frmRegraAuditoriaLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmRegraAuditoriaLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoDesativar) { ?>
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Regra de Auditoria \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmRegraAuditoriaLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmRegraAuditoriaLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Regra de Auditoria selecionada.');
  return;
  }
  if (confirm("Confirma desativação das Regras de Auditoria selecionadas?")){
  document.getElementById('frmRegraAuditoriaLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmRegraAuditoriaLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoReativar) { ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Regra de Auditoria \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmRegraAuditoriaLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmRegraAuditoriaLista').submit();
  }
  }

  function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Regra de Auditoria selecionada.');
  return;
  }
  if (confirm("Confirma reativação das Regras de Auditoria selecionadas?")){
  document.getElementById('frmRegraAuditoriaLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmRegraAuditoriaLista').submit();
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
PaginaSip::getInstance()->abrirBody('Regras de Auditoria', 'onload="inicializar();"');
?>
  <form id="frmRegraAuditoriaLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('regra_auditoria_lista.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados('10em');
    ?>

    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span
        class="infraTeclaAtalho">r</span>gão do Sistema:</label>
    <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgaoSistema?>
    </select>

    <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <select id="selSistema" name="selSistema" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitarSistema?>>
      <?=$strItensSelSistema?>
    </select>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>