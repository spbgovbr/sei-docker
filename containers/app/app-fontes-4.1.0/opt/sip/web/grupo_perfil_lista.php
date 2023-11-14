<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/07/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
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

  //PaginaSip::getInstance()->prepararSelecao('grupo_perfil_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema'));

  switch ($_GET['acao']) {
    case 'grupo_perfil_excluir':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoPerfilDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $arrStrIdComposto = explode('-', $arrStrIds[$i]);
          $objGrupoPerfilDTO = new GrupoPerfilDTO();
          $objGrupoPerfilDTO->setNumIdGrupoPerfil($arrStrIdComposto[0]);
          $objGrupoPerfilDTO->setNumIdSistema($arrStrIdComposto[1]);
          $arrObjGrupoPerfilDTO[] = $objGrupoPerfilDTO;
        }
        $objGrupoPerfilRN = new GrupoPerfilRN();
        $objGrupoPerfilRN->excluir($arrObjGrupoPerfilDTO);
        PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;


    case 'grupo_perfil_desativar':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoPerfilDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $arrStrIdComposto = explode('-', $arrStrIds[$i]);
          $objGrupoPerfilDTO = new GrupoPerfilDTO();
          $objGrupoPerfilDTO->setNumIdGrupoPerfil($arrStrIdComposto[0]);
          $objGrupoPerfilDTO->setNumIdSistema($arrStrIdComposto[1]);
          $arrObjGrupoPerfilDTO[] = $objGrupoPerfilDTO;
        }
        $objGrupoPerfilRN = new GrupoPerfilRN();
        $objGrupoPerfilRN->desativar($arrObjGrupoPerfilDTO);
        PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'grupo_perfil_reativar':
      $strTitulo = 'Reativar Grupos de Perfis';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
          $arrObjGrupoPerfilDTO = array();
          for ($i = 0; $i < count($arrStrIds); $i++) {
            $arrStrIdComposto = explode('-', $arrStrIds[$i]);
            $objGrupoPerfilDTO = new GrupoPerfilDTO();
            $objGrupoPerfilDTO->setNumIdGrupoPerfil($arrStrIdComposto[0]);
            $objGrupoPerfilDTO->setNumIdSistema($arrStrIdComposto[1]);
            $arrObjGrupoPerfilDTO[] = $objGrupoPerfilDTO;
          }
          $objGrupoPerfilRN = new GrupoPerfilRN();
          $objGrupoPerfilRN->reativar($arrObjGrupoPerfilDTO);
          PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
      }
      break;


    case 'grupo_perfil_selecionar':
      $strTitulo = PaginaSip::getInstance()->getTituloSelecao('Selecionar Grupo de Perfil', 'Selecionar Grupos de Perfis');

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == 'grupo_perfil_cadastrar') {
        if (isset($_GET['id_grupo_perfil']) && isset($_GET['id_sistema'])) {
          PaginaSip::getInstance()->adicionarSelecionado($_GET['id_grupo_perfil'] . '-' . $_GET['id_sistema']);
        }
      }
      break;

    case 'grupo_perfil_listar':
      $strTitulo = 'Grupos de Perfis';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'grupo_perfil_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'grupo_perfil_listar' || $_GET['acao'] == 'grupo_perfil_selecionar') {
    $bolAcaoCadastrar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_cadastrar');
    if ($bolAcaoCadastrar) {
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=grupo_perfil_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objGrupoPerfilDTO = new GrupoPerfilDTO();
  $objGrupoPerfilDTO->setBolExclusaoLogica(false);
  $objGrupoPerfilDTO->retNumIdGrupoPerfil();
  $objGrupoPerfilDTO->retNumIdSistema();
  $objGrupoPerfilDTO->retStrNome();
  $objGrupoPerfilDTO->retStrSinAtivo();

  //ORGAO
  $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
  if ($numIdOrgao !== '') {
    $objGrupoPerfilDTO->setNumIdOrgaoSistema($numIdOrgao);
  }

  //SISTEMA
  $numIdSistema = '';
  if ($numIdOrgao !== '') {
    $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema', 'null');
    if ($numIdSistema !== '') {
      $objGrupoPerfilDTO->setNumIdSistema($numIdSistema);
    }
  } else {
    //Para todos os orgãos os sistemas podem se repetir então não possibilita
    //escolha (desabilita combo)
    $strDesabilitar = 'disabled="disabled"';
  }


  if ($_GET['acao'] == 'grupo_perfil_reativar') {
    //Lista somente inativos
    $objGrupoPerfilDTO->setBolExclusaoLogica(false);
    $objGrupoPerfilDTO->setStrSinAtivo('N');
  }

  PaginaSip::getInstance()->prepararOrdenacao($objGrupoPerfilDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSip::getInstance()->prepararPaginacao($objGrupoPerfilDTO);

  $objGrupoPerfilRN = new GrupoPerfilRN();
  $arrObjGrupoPerfilDTO = $objGrupoPerfilRN->listar($objGrupoPerfilDTO);

  //PaginaSip::getInstance()->processarPaginacao($objGrupoPerfilDTO);

  /** @var GrupoPerfilDTO[] $arrObjGrupoPerfilDTO */

  $numRegistros = count($arrObjGrupoPerfilDTO);

  if ($numRegistros > 0) {
    $bolCheck = false;

    if ($_GET['acao'] == 'grupo_perfil_selecionar') {
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
      /* }else if ($_GET['acao']=='grupo_perfil_reativar'){
        $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_reativar');
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_consultar');
        $bolAcaoAlterar = false;
        $bolAcaoImprimir = true;
        //$bolAcaoGerarPlanilha = SessaoSip::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_excluir');
        $bolAcaoDesativar = false;
      */
    } else {
      $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_reativar');
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSip::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_excluir');
      $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_desativar');
    }


    if ($bolAcaoDesativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=grupo_perfil_desativar&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoReativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=grupo_perfil_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=grupo_perfil_excluir&acao_origem=' . $_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    //if ($_GET['acao']!='grupo_perfil_reativar'){
    $strSumarioTabela = 'Tabela de Grupos de Perfis.';
    $strCaptionTabela = 'Grupos de Perfis';
    //}else{
    //  $strSumarioTabela = 'Tabela de Grupos de Perfis Inativos.';
    //  $strCaptionTabela = 'Grupos de Perfis Inativos';
    //}

    $strResultado .= '<table width="80%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>' . "\n";
    }
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objGrupoPerfilDTO, 'Nome', 'Nome', $arrObjGrupoPerfilDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="20%">Perfis</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';

    for ($i = 0; $i < $numRegistros; $i++) {
      if ($arrObjGrupoPerfilDTO[$i]->getStrSinAtivo() == 'S') {
        $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      } else {
        $strCssTr = '<tr class="trVermelha">';
      }

      $strResultado .= $strCssTr;

      if ($bolCheck) {
        $strResultado .= '<td align="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjGrupoPerfilDTO[$i]->getNumIdGrupoPerfil() . '-' . $arrObjGrupoPerfilDTO[$i]->getNumIdSistema(),
            $arrObjGrupoPerfilDTO[$i]->getStrNome()) . '</td>';
      }
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjGrupoPerfilDTO[$i]->getStrNome()) . '</td>';


      $objRelGrupoPerfilPerfilDTO = new RelGrupoPerfilPerfilDTO();
      $objRelGrupoPerfilPerfilDTO->setNumIdSistema($arrObjGrupoPerfilDTO[$i]->getNumIdSistema());
      $objRelGrupoPerfilPerfilDTO->setNumIdGrupoPerfil($arrObjGrupoPerfilDTO[$i]->getNumIdGrupoPerfil());
      $objRelGrupoPerfilPerfilDTO->setStrSinAtivoPerfil('S');

      $objRelGrupoPerfilPerfilRN = new RelGrupoPerfilPerfilRN();
      $numPerfis = $objRelGrupoPerfilPerfilRN->contar($objRelGrupoPerfilPerfilDTO);

      $strResultado .= '<td align="center">' . InfraUtil::formatarMilhares($numPerfis) . '</td>';

      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSip::getInstance()->getAcaoTransportarItem($i, $arrObjGrupoPerfilDTO[$i]->getNumIdGrupoPerfil() . '-' . $arrObjGrupoPerfilDTO[$i]->getNumIdSistema());

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=grupo_perfil_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_grupo_perfil=' . $arrObjGrupoPerfilDTO[$i]->getNumIdGrupoPerfil() . '&id_sistema=' . $arrObjGrupoPerfilDTO[$i]->getNumIdSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Grupo de Perfil" alt="Consultar Grupo de Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=grupo_perfil_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_grupo_perfil=' . $arrObjGrupoPerfilDTO[$i]->getNumIdGrupoPerfil() . '&id_sistema=' . $arrObjGrupoPerfilDTO[$i]->getNumIdSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Grupo de Perfil" alt="Alterar Grupo de Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjGrupoPerfilDTO[$i]->getNumIdGrupoPerfil() . '-' . $arrObjGrupoPerfilDTO[$i]->getNumIdSistema();
        $strDescricao = PaginaSip::getInstance()->formatarParametrosJavaScript($arrObjGrupoPerfilDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjGrupoPerfilDTO[$i]->getStrSinAtivo() == 'S') {
        $strResultado .= '<a href="' . PaginaSip::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeDesativar() . '" title="Desativar Grupo de Perfil" alt="Desativar Grupo de Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjGrupoPerfilDTO[$i]->getStrSinAtivo() == 'N') {
        $strResultado .= '<a href="' . PaginaSip::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeReativar() . '" title="Reativar Grupo de Perfil" alt="Reativar Grupo de Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir) {
        $strResultado .= '<a href="' . PaginaSip::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Grupo de Perfil" alt="Excluir Grupo de Perfil" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'grupo_perfil_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  } else {
    $arrComandos[] = '<button type="button" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton">Voltar</button>';
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
<?
if (0){ ?>
  <style><?}?>

    #lblOrgaoSistema {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 20%;
    }

    #selOrgaoSistema {
      position: absolute;
      left: 0%;
      top: 20%;
      width: 20%;
    }

    #lblSistema {
      position: absolute;
      left: 0%;
      top: 50%;
      width: 20%;
    }

    #selSistema {
      position: absolute;
      left: 0%;
      top: 70%;
      width: 20%;
    }

    <?
    if (0){ ?></style><?
} ?>
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
<?
if (0){ ?>
  <script type="text/javascript"><?}?>

    function inicializar() {
      if ('<?=$_GET['acao']?>' == 'grupo_perfil_selecionar') {
        infraReceberSelecao();
        document.getElementById('btnFecharSelecao').focus();
      } else {
        document.getElementById('btnVoltar').focus();
      }
      infraEfeitoTabelas(true);
    }

    <? if ($bolAcaoDesativar){ ?>
    function acaoDesativar(id, desc) {
      if (confirm("Confirma desativação do Grupo de Perfil \"" + desc + "\"?")) {
        document.getElementById('hdnInfraItemId').value = id;
        document.getElementById('frmGrupoPerfilLista').action = '<?=$strLinkDesativar?>';
        document.getElementById('frmGrupoPerfilLista').submit();
      }
    }

    function acaoDesativacaoMultipla() {
      if (document.getElementById('hdnInfraItensSelecionados').value == '') {
        alert('Nenhum Grupo de Perfil selecionado.');
        return;
      }
      if (confirm("Confirma desativação dos Grupos de Perfis selecionados?")) {
        document.getElementById('hdnInfraItemId').value = '';
        document.getElementById('frmGrupoPerfilLista').action = '<?=$strLinkDesativar?>';
        document.getElementById('frmGrupoPerfilLista').submit();
      }
    }
    <? } ?>

    <? if ($bolAcaoReativar){ ?>
    function acaoReativar(id, desc) {
      if (confirm("Confirma reativação do Grupo de Perfil \"" + desc + "\"?")) {
        document.getElementById('hdnInfraItemId').value = id;
        document.getElementById('frmGrupoPerfilLista').action = '<?=$strLinkReativar?>';
        document.getElementById('frmGrupoPerfilLista').submit();
      }
    }

    function acaoReativacaoMultipla() {
      if (document.getElementById('hdnInfraItensSelecionados').value == '') {
        alert('Nenhum Grupo de Perfil selecionado.');
        return;
      }
      if (confirm("Confirma reativação dos Grupos de Perfis selecionados?")) {
        document.getElementById('hdnInfraItemId').value = '';
        document.getElementById('frmGrupoPerfilLista').action = '<?=$strLinkReativar?>';
        document.getElementById('frmGrupoPerfilLista').submit();
      }
    }
    <? } ?>

    <? if ($bolAcaoExcluir){ ?>
    function acaoExcluir(id, desc) {
      if (confirm("Confirma exclusão do Grupo de Perfil \"" + desc + "\"?")) {
        document.getElementById('hdnInfraItemId').value = id;
        document.getElementById('frmGrupoPerfilLista').action = '<?=$strLinkExcluir?>';
        document.getElementById('frmGrupoPerfilLista').submit();
      }
    }

    function acaoExclusaoMultipla() {
      if (document.getElementById('hdnInfraItensSelecionados').value == '') {
        alert('Nenhum Grupo de Perfil selecionado.');
        return;
      }
      if (confirm("Confirma exclusão dos Grupos de Perfis selecionados?")) {
        document.getElementById('hdnInfraItemId').value = '';
        document.getElementById('frmGrupoPerfilLista').action = '<?=$strLinkExcluir?>';
        document.getElementById('frmGrupoPerfilLista').submit();
      }
    }
    <? } ?>

    <?
    if (0){ ?></script><?
} ?>
<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmGrupoPerfilLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
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
