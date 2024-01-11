<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/05/2009 - criado por mga
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

  PaginaSip::getInstance()->prepararSelecao('recurso_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema', 'txtNomeRecurso'));

  //print_r($_SESSION['INFRA_PAGINA']);die;

  switch ($_GET['acao']) {
    case 'recurso_excluir':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjRecursoDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $arrStrIdComposto = explode('-', $arrStrIds[$i]);
          $objRecursoDTO = new RecursoDTO();
          $objRecursoDTO->setNumIdSistema($arrStrIdComposto[0]);
          $objRecursoDTO->setNumIdRecurso($arrStrIdComposto[1]);
          $arrObjRecursoDTO[] = $objRecursoDTO;
        }
        $objRecursoRN = new RecursoRN();
        $objRecursoRN->excluir($arrObjRecursoDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;


    case 'recurso_desativar':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjRecursoDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $arrStrIdComposto = explode('-', $arrStrIds[$i]);
          $objRecursoDTO = new RecursoDTO();
          $objRecursoDTO->setNumIdSistema($arrStrIdComposto[0]);
          $objRecursoDTO->setNumIdRecurso($arrStrIdComposto[1]);
          $arrObjRecursoDTO[] = $objRecursoDTO;
        }
        $objRecursoRN = new RecursoRN();
        $objRecursoRN->desativar($arrObjRecursoDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'recurso_reativar':
      $strTitulo = 'Reativar Recursos';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
          $arrObjRecursoDTO = array();
          for ($i = 0; $i < count($arrStrIds); $i++) {
            $arrStrIdComposto = explode('-', $arrStrIds[$i]);
            $objRecursoDTO = new RecursoDTO();
            $objRecursoDTO->setNumIdSistema($arrStrIdComposto[0]);
            $objRecursoDTO->setNumIdRecurso($arrStrIdComposto[1]);
            $arrObjRecursoDTO[] = $objRecursoDTO;
          }
          $objRecursoRN = new RecursoRN();
          $objRecursoRN->reativar($arrObjRecursoDTO);
          PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
      }
      break;


    case 'recurso_selecionar':
      $strTitulo = PaginaSip::getInstance()->getTituloSelecao('Selecionar Recurso', 'Selecionar Recursos');

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == 'recurso_cadastrar') {
        if (isset($_GET['id_sistema']) && isset($_GET['id_recurso'])) {
          PaginaSip::getInstance()->adicionarSelecionado($_GET['id_sistema'] . '-' . $_GET['id_recurso']);
        }
      }
      break;

    case 'recurso_listar':
      $strTitulo = 'Recursos';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<input type="submit" id="btnPesquisar" value="Pesquisar" class="infraButton" />';

  if (PaginaSip::getInstance()->isBolPaginaSelecao()) {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'recurso_listar') {
    $bolAcaoCadastrar = SessaoSip::getInstance()->verificarPermissao('recurso_cadastrar');
    if ($bolAcaoCadastrar) {
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }

    if (SessaoSip::getInstance()->verificarPermissao('recurso_gerar')) {
      $arrComandos[] = '<input type="button" id="btnGerar" value="Gerar Padrão" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_gerar&acao_origem=recurso_lista') . '\'" class="infraButton" />';
    }
  }

  $objRecursoDTO = new RecursoDTO(true);
  $objRecursoDTO->retNumIdSistema();
  $objRecursoDTO->retNumIdRecurso();
  $objRecursoDTO->retStrNome();
  $objRecursoDTO->retStrDescricao();
  //$objRecursoDTO->retStrCaminho();
  $objRecursoDTO->retNumIdOrgaoSistema();


  //die('#'.$numIdOrgao.'#');

  //ORGAO
  $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());

  if ($numIdOrgao !== '') {
    $objRecursoDTO->setNumIdOrgaoSistema($numIdOrgao);
    $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema', 'null');
  } else {
    $strDesabilitar = 'disabled="disabled"';
    $numIdSistema = '';
  }

  //SISTEMA
  if ($numIdSistema !== '') {
    $objRecursoDTO->setNumIdSistema($numIdSistema);
  }

  $strNomePesquisa = trim(PaginaSip::getInstance()->recuperarCampo('txtNomeRecurso'));
  if ($strNomePesquisa !== '') {
    $objRecursoDTO->setStrNome('%' . $strNomePesquisa . '%', InfraDTO::$OPER_LIKE);
  }


  if ($_GET['acao'] == 'recurso_reativar') {
    //Lista somente inativos
    $objRecursoDTO->setBolExclusaoLogica(false);
    $objRecursoDTO->setStrSinAtivo('N');
  }

  PaginaSip::getInstance()->prepararOrdenacao($objRecursoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSip::getInstance()->prepararPaginacao($objRecursoDTO);

  $objRecursoRN = new RecursoRN();
  $arrObjRecursoDTO = $objRecursoRN->listar($objRecursoDTO);

  PaginaSip::getInstance()->processarPaginacao($objRecursoDTO);
  $numRegistros = count($arrObjRecursoDTO);

  if ($numRegistros > 0) {
    $bolCheck = false;

    if ($_GET['acao'] == 'recurso_selecionar') {
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('recurso_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('recurso_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    } else {
      if ($_GET['acao'] == 'recurso_reativar') {
        $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('recurso_reativar');
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('recurso_consultar');
        $bolAcaoAlterar = false;
        $bolAcaoImprimir = true;
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('recurso_excluir');
        $bolAcaoDesativar = false;
      } else {
        $bolAcaoReativar = false;
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('recurso_consultar');
        $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('recurso_alterar');
        $bolAcaoImprimir = true;
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('recurso_excluir');
        $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('recurso_desativar');
      }
    }


    if ($bolAcaoDesativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_desativar&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoReativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_excluir&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoImprimir) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    $strResultado = '';

    if ($_GET['acao'] != 'recurso_reativar') {
      $strSumarioTabela = 'Tabela de Recursos.';
      $strCaptionTabela = 'Recursos';
    } else {
      $strSumarioTabela = 'Tabela de Recursos Inativos.';
      $strCaptionTabela = 'Recursos Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>' . "\n";
    }
    $strResultado .= '<th class="infraTh" width="20%">' . PaginaSip::getInstance()->getThOrdenacao($objRecursoDTO, 'Nome', 'Nome', $arrObjRecursoDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">Descrição</th>' . "\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objRecursoDTO,'Caminho','Caminho',$arrObjRecursoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {
      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjRecursoDTO[$i]->getNumIdSistema() . '-' . $arrObjRecursoDTO[$i]->getNumIdRecurso(), $arrObjRecursoDTO[$i]->getStrNome()) . '</td>';
      }
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjRecursoDTO[$i]->getStrNome()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjRecursoDTO[$i]->getStrDescricao()) . '</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSip::getInstance()->getAcaoTransportarItem($i, $arrObjRecursoDTO[$i]->getNumIdSistema() . '-' . $arrObjRecursoDTO[$i]->getNumIdRecurso());

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_sistema=' . $arrObjRecursoDTO[$i]->getNumIdSistema() . '&id_recurso=' . $arrObjRecursoDTO[$i]->getNumIdRecurso()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Recurso" alt="Consultar Recurso" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_sistema=' . $arrObjRecursoDTO[$i]->getNumIdSistema() . '&id_recurso=' . $arrObjRecursoDTO[$i]->getNumIdRecurso()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Recurso" alt="Alterar Recurso" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjRecursoDTO[$i]->getNumIdSistema() . '-' . $arrObjRecursoDTO[$i]->getNumIdRecurso();
        $strDescricao = PaginaSip::getInstance()->formatarParametrosJavaScript($arrObjRecursoDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar) {
        $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeDesativar() . '" title="Desativar Recurso" alt="Desativar Recurso" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar) {
        $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeReativar() . '" title="Reativar Recurso" alt="Reativar Recurso" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir) {
        $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Recurso" alt="Excluir Recurso" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  if (PaginaSip::getInstance()->isBolPaginaSelecao()) {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  } else {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null', '&nbsp;', $numIdOrgao);
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null', '&nbsp;', $numIdSistema, $numIdOrgao);
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
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
  #selOrgaoSistema {position:absolute;left:0%;top:20%;width:20%;}

  #lblSistema {position:absolute;left:0%;top:50%;width:20%;}
  #selSistema {position:absolute;left:0%;top:70%;width:20%;}

  #lblNomeRecurso {position:absolute;left:25%;top:0%;width:50%;}
  #txtNomeRecurso {position:absolute;left:25%;top:20%;width:50%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='recurso_selecionar'){
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
  if (confirm("Confirma desativação do Recurso \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmRecursoLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmRecursoLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Recurso selecionado.');
  return;
  }
  if (confirm("Confirma desativação dos Recursos selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmRecursoLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmRecursoLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoReativar) { ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Recurso \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmRecursoLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmRecursoLista').submit();
  }
  }

  function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Recurso selecionado.');
  return;
  }
  if (confirm("Confirma reativação dos Recursos selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmRecursoLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmRecursoLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoExcluir) { ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Recurso \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmRecursoLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmRecursoLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Recurso selecionado.');
  return;
  }
  if (confirm("Confirma exclusão dos Recursos selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmRecursoLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmRecursoLista').submit();
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
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmRecursoLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
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

    <label id="lblNomeRecurso" for="txtNomeRecurso" accesskey="N" class="infraLabelOpcional"><span
        class="infraTeclaAtalho">N</span>ome:</label>
    <input type="text" id="txtNomeRecurso" name="txtNomeRecurso" class="infraText" value="<?=$strNomePesquisa?>"
           maxlength="50" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

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