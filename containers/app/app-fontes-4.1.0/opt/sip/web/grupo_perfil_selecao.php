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

  PaginaSip::getInstance()->prepararSelecao('grupo_perfil_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  SessaoSip::getInstance()->setArrParametrosRepasseLink(array('id_sistema'));

  switch ($_GET['acao']) {
    case 'grupo_perfil_selecionar':
      $strTitulo = PaginaSip::getInstance()->getTituloSelecao('Selecionar Grupo de Perfil', 'Selecionar Grupos de Perfis');

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == 'grupo_perfil_cadastrar') {
        if (isset($_GET['id_grupo_perfil']) && isset($_GET['id_sistema'])) {
          PaginaSip::getInstance()->adicionarSelecionado($_GET['id_grupo_perfil'] . '-' . $_GET['id_sistema']);
        }
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';

  if ($_GET['acao'] == 'grupo_perfil_listar' || $_GET['acao'] == 'grupo_perfil_selecionar') {
    $bolAcaoCadastrar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_cadastrar');
    if ($bolAcaoCadastrar) {
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=grupo_perfil_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objGrupoPerfilDTO = new GrupoPerfilDTO();
  $objGrupoPerfilDTO->retNumIdSistema();
  $objGrupoPerfilDTO->retNumIdGrupoPerfil();
  $objGrupoPerfilDTO->retStrNome();
  $objGrupoPerfilDTO->setNumIdSistema($_GET['id_sistema']);

  PaginaSip::getInstance()->prepararOrdenacao($objGrupoPerfilDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSip::getInstance()->prepararPaginacao($objGrupoPerfilDTO);

  $objGrupoPerfilRN = new GrupoPerfilRN();
  $arrObjGrupoPerfilDTO = $objGrupoPerfilRN->listar($objGrupoPerfilDTO);

  //PaginaSip::getInstance()->processarPaginacao($objGrupoPerfilDTO);

  /** @var GrupoPerfilDTO[] $arrObjGrupoPerfilDTO */

  $numRegistros = count($arrObjGrupoPerfilDTO);

  if ($numRegistros > 0) {
    $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('grupo_perfil_alterar');

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Grupos de Perfis do Sistema.';
    $strCaptionTabela = 'Grupos de Perfis do Sistema';

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objGrupoPerfilDTO, 'Nome', 'Nome', $arrObjGrupoPerfilDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {
      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';

      $strResultado .= $strCssTr;

      $strResultado .= '<td align="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjGrupoPerfilDTO[$i]->getNumIdGrupoPerfil() . '-' . $arrObjGrupoPerfilDTO[$i]->getNumIdSistema(),
          $arrObjGrupoPerfilDTO[$i]->getStrNome()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjGrupoPerfilDTO[$i]->getStrNome()) . '</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSip::getInstance()->getAcaoTransportarItem($i, $arrObjGrupoPerfilDTO[$i]->getNumIdGrupoPerfil() . '-' . $arrObjGrupoPerfilDTO[$i]->getNumIdSistema());

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=grupo_perfil_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_grupo_perfil=' . $arrObjGrupoPerfilDTO[$i]->getNumIdGrupoPerfil()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Grupo de Perfil" alt="Alterar Grupo de Perfil" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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
      infraReceberSelecao();
      //document.getElementById('btnFecharSelecao').focus();
      infraEfeitoTabelas(true);
    }

    <?
    if (0){ ?></script><?
} ?>
<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmGrupoPerfilSelecao" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
