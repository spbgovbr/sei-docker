<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/05/2009 - criado por mga
 *
 * Versão do Gerador de Código: 1.26.0
 *
 * Versão no CVS: $Id$
 */

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  PaginaSip::getInstance()->prepararSelecao('unidade_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoUnidade', 'txtSiglaUnidade', 'txtDescricaoUnidade', 'txtIdOrigemUnidade'));

  switch ($_GET['acao']) {
    case 'unidade_excluir':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjUnidadeDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setNumIdUnidade($arrStrIds[$i]);
          $arrObjUnidadeDTO[] = $objUnidadeDTO;
        }
        $objUnidadeRN = new UnidadeRN();
        $objUnidadeRN->excluir($arrObjUnidadeDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;


    case 'unidade_desativar':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjUnidadeDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setNumIdUnidade($arrStrIds[$i]);
          $arrObjUnidadeDTO[] = $objUnidadeDTO;
        }
        $objUnidadeRN = new UnidadeRN();
        $objUnidadeRN->desativar($arrObjUnidadeDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'unidade_reativar':
      $strTitulo = 'Reativar Unidades';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
          $arrObjUnidadeDTO = array();
          for ($i = 0; $i < count($arrStrIds); $i++) {
            $objUnidadeDTO = new UnidadeDTO();
            $objUnidadeDTO->setNumIdUnidade($arrStrIds[$i]);
            $arrObjUnidadeDTO[] = $objUnidadeDTO;
          }
          $objUnidadeRN = new UnidadeRN();
          $objUnidadeRN->reativar($arrObjUnidadeDTO);
          PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
      }
      break;


    case 'unidade_selecionar':
      $strTitulo = PaginaSip::getInstance()->getTituloSelecao('Selecionar Unidade', 'Selecionar Unidades');

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == 'unidade_cadastrar') {
        if (isset($_GET['id_unidade'])) {
          PaginaSip::getInstance()->adicionarSelecionado($_GET['id_unidade']);
        }
      }
      break;

    case 'unidade_listar':
      $strTitulo = 'Unidades';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<input type="submit" id="sbmPesquisar" value="Pesquisar" class="infraButton" />';

  if ($_GET['acao'] == 'unidade_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'unidade_listar' || $_GET['acao'] == 'unidade_selecionar') {
    $bolAcaoCadastrar = SessaoSip::getInstance()->verificarPermissao('unidade_cadastrar');
    if ($bolAcaoCadastrar) {
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=unidade_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  }

  $objUnidadeDTO = new UnidadeDTO(true);
  $objUnidadeDTO->retNumIdUnidade();
  $objUnidadeDTO->retStrIdOrigem();
  $objUnidadeDTO->retStrSigla();
  $objUnidadeDTO->retStrDescricao();
  $objUnidadeDTO->retStrSiglaOrgao();
  $objUnidadeDTO->retStrDescricaoOrgao();
  $objUnidadeDTO->retStrSinGlobal();
  $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoUnidade');
  if ($numIdOrgao !== '') {
    $objUnidadeDTO->setNumIdOrgao($numIdOrgao);
  }

  $strSiglaPesquisa = trim(PaginaSip::getInstance()->recuperarCampo('txtSiglaUnidade'));
  if ($strSiglaPesquisa !== '') {
    $objUnidadeDTO->setStrSigla($strSiglaPesquisa);
  }

  $strDescricaoPesquisa = PaginaSip::getInstance()->recuperarCampo('txtDescricaoUnidade');
  if ($strDescricaoPesquisa !== '') {
    $objUnidadeDTO->setStrDescricao($strDescricaoPesquisa);
  }

  $strIdOrigemPesquisa = PaginaSip::getInstance()->recuperarCampo('txtIdOrigemUnidade');
  if ($strIdOrigemPesquisa !== '') {
    $objUnidadeDTO->setStrIdOrigem($strIdOrigemPesquisa);
  }


  if ($_GET['acao'] == 'unidade_reativar') {
    //Lista somente inativos
    $objUnidadeDTO->setBolExclusaoLogica(false);
    $objUnidadeDTO->setStrSinAtivo('N');
  }

  PaginaSip::getInstance()->prepararOrdenacao($objUnidadeDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSip::getInstance()->prepararPaginacao($objUnidadeDTO, 50);

  $objUnidadeRN = new UnidadeRN();
  $arrObjUnidadeDTO = $objUnidadeRN->pesquisar($objUnidadeDTO);

  PaginaSip::getInstance()->processarPaginacao($objUnidadeDTO);
  $numRegistros = count($arrObjUnidadeDTO);

  if ($numRegistros > 0) {
    $bolCheck = false;

    if ($_GET['acao'] == 'unidade_selecionar') {
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('unidade_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('unidade_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    } else {
      if ($_GET['acao'] == 'unidade_reativar') {
        $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('unidade_reativar');
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('unidade_consultar');
        $bolAcaoAlterar = false;
        $bolAcaoImprimir = true;
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('unidade_excluir');
        $bolAcaoDesativar = false;
      } else {
        $bolAcaoReativar = false;
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('unidade_consultar');
        $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('unidade_alterar');
        $bolAcaoImprimir = true;
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('unidade_excluir');
        $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('unidade_desativar');
      }
    }


    if ($bolAcaoDesativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=unidade_desativar&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoReativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=unidade_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=unidade_excluir&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoImprimir) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    $strResultado = '';

    if ($_GET['acao'] != 'unidade_reativar') {
      $strSumarioTabela = 'Tabela de Unidades.';
      $strCaptionTabela = 'Unidades';
    } else {
      $strSumarioTabela = 'Tabela de Unidades Inativas.';
      $strCaptionTabela = 'Unidades Inativas';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>' . "\n";
    }
    $strResultado .= '<th class="infraTh" width="8%">' . PaginaSip::getInstance()->getThOrdenacao($objUnidadeDTO, 'ID&nbsp;SIP', 'IdUnidade', $arrObjUnidadeDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="8%">' . PaginaSip::getInstance()->getThOrdenacao($objUnidadeDTO, 'ID&nbsp;Origem', 'IdOrigem', $arrObjUnidadeDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="15%">' . PaginaSip::getInstance()->getThOrdenacao($objUnidadeDTO, 'Sigla', 'Sigla', $arrObjUnidadeDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objUnidadeDTO, 'Descrição', 'Descricao', $arrObjUnidadeDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">' . PaginaSip::getInstance()->getThOrdenacao($objUnidadeDTO, 'Órgão', 'SiglaOrgao', $arrObjUnidadeDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {
      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjUnidadeDTO[$i]->getNumIdUnidade(), $arrObjUnidadeDTO[$i]->getStrSigla()) . '</td>';
      }
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjUnidadeDTO[$i]->getNumIdUnidade()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjUnidadeDTO[$i]->getStrIdOrigem()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjUnidadeDTO[$i]->getStrSigla()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricao()) . '</td>';
      $strResultado .= '<td align="center"><a alt="' . PaginaSip::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricaoOrgao()) . '" title="' . PaginaSip::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricaoOrgao()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjUnidadeDTO[$i]->getStrSiglaOrgao()) . '</a></td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSip::getInstance()->getAcaoTransportarItem($i, $arrObjUnidadeDTO[$i]->getNumIdUnidade());

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=unidade_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_unidade=' . $arrObjUnidadeDTO[$i]->getNumIdUnidade()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Unidade" alt="Consultar Unidade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar && $arrObjUnidadeDTO[$i]->getStrSinGlobal() == 'N') {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=unidade_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_unidade=' . $arrObjUnidadeDTO[$i]->getNumIdUnidade()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Unidade" alt="Alterar Unidade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjUnidadeDTO[$i]->getNumIdUnidade();
        $strDescricao = PaginaSip::getInstance()->formatarParametrosJavaScript($arrObjUnidadeDTO[$i]->getStrSigla());
      }

      if ($bolAcaoDesativar && $arrObjUnidadeDTO[$i]->getStrSinGlobal() == 'N') {
        $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeDesativar() . '" title="Desativar Unidade" alt="Desativar Unidade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjUnidadeDTO[$i]->getStrSinGlobal() == 'N') {
        $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeReativar() . '" title="Reativar Unidade" alt="Reativar Unidade" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir && $arrObjUnidadeDTO[$i]->getStrSinGlobal() == 'N') {
        $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Unidade" alt="Excluir Unidade" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'unidade_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  } else {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaTodos('', 'Todos', $numIdOrgao);
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
  #lblOrgaoUnidade {position:absolute;left:0%;top:0%;width:20%;}
  #selOrgaoUnidade {position:absolute;left:0%;top:40%;width:20%;}

  #lblSiglaUnidade {position:absolute;left:22%;top:0%;width:15%;}
  #txtSiglaUnidade {position:absolute;left:22%;top:40%;width:15%;}

  #lblDescricaoUnidade {position:absolute;left:39%;top:0%;width:40%;}
  #txtDescricaoUnidade {position:absolute;left:39%;top:40%;width:40%;}

  #lblIdOrigemUnidade {position:absolute;left:81%;top:0%;width:15%;}
  #txtIdOrigemUnidade {position:absolute;left:81%;top:40%;width:15%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='unidade_selecionar'){
  infraReceberSelecao();
  document.getElementById('btnFecharSelecao').focus();
  }else{
  document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
  }

<?
if ($bolAcaoDesativar) { ?>
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Unidade \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmUnidadeLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Unidade selecionada.');
  return;
  }
  if (confirm("Confirma desativação das Unidades selecionadas?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmUnidadeLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoReativar) { ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Unidade \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmUnidadeLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }

  function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Unidade selecionada.');
  return;
  }
  if (confirm("Confirma reativação das Unidades selecionadas?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmUnidadeLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoExcluir) { ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Unidade \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmUnidadeLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Unidade selecionada.');
  return;
  }
  if (confirm("Confirma exclusão das Unidades selecionadas?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmUnidadeLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }
  <?
} ?>

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmUnidadeLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblOrgaoUnidade" for="selOrgaoUnidade" accesskey="o" class="infraLabelOpcional">Órgã<span
        class="infraTeclaAtalho">o</span>:</label>
    <select id="selOrgaoUnidade" name="selOrgaoUnidade" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgao?>
    </select>

    <label id="lblSiglaUnidade" for="txtSiglaUnidade" class="infraLabelOpcional">Sigla:</label>
    <input type="text" id="txtSiglaUnidade" name="txtSiglaUnidade" class="infraText"
           value="<?=PaginaSip::tratarHTML($strSiglaPesquisa)?>" maxlength="30"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblDescricaoUnidade" for="txtDescricaoUnidade" class="infraLabelOpcional">Descrição:</label>
    <input type="text" id="txtDescricaoUnidade" name="txtDescricaoUnidade" class="infraText"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"
           value="<?=PaginaSip::tratarHTML($strDescricaoPesquisa)?>"/>

    <label id="lblIdOrigemUnidade" for="txtIdOrigemUnidade" accesskey="" class="infraLabelOpcional">ID Origem:</label>
    <input type="text" id="txtIdOrigemUnidade" name="txtIdOrigemUnidade" class="infraText"
           value="<?=PaginaSip::tratarHTML($strIdOrigemPesquisa);?>" maxlength="50"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>