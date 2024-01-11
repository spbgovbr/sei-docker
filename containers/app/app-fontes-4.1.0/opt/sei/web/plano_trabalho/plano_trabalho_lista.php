<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('plano_trabalho_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('hdnPlanosTrabalhoFiltro'));

  switch ($_GET['acao']) {
    case 'plano_trabalho_excluir':
      try {
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjPlanoTrabalhoDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
          $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($arrStrIds[$i]);
          $arrObjPlanoTrabalhoDTO[] = $objPlanoTrabalhoDTO;
        }
        $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
        $objPlanoTrabalhoRN->excluir($arrObjPlanoTrabalhoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;


    case 'plano_trabalho_desativar':
      try {
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjPlanoTrabalhoDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
          $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($arrStrIds[$i]);
          $arrObjPlanoTrabalhoDTO[] = $objPlanoTrabalhoDTO;
        }
        $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
        $objPlanoTrabalhoRN->desativar($arrObjPlanoTrabalhoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'plano_trabalho_reativar':
      $strTitulo = 'Reativar Planos de Trabalho';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjPlanoTrabalhoDTO = array();
          for ($i = 0; $i < count($arrStrIds); $i++) {
            $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
            $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($arrStrIds[$i]);
            $arrObjPlanoTrabalhoDTO[] = $objPlanoTrabalhoDTO;
          }
          $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
          $objPlanoTrabalhoRN->reativar($arrObjPlanoTrabalhoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
      }
      break;


    case 'plano_trabalho_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Plano de Trabalho', 'Selecionar Planos de Trabalho');

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == 'plano_trabalho_cadastrar') {
        if (isset($_GET['id_plano_trabalho'])) {
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_plano_trabalho']);
        }
      }
      break;

    case 'plano_trabalho_listar':
      $strTitulo = 'Planos de Trabalho';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'plano_trabalho_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'plano_trabalho_listar') {
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_cadastrar');
    if ($bolAcaoCadastrar) {
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }


  $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
  $objPlanoTrabalhoDTO->setBolExclusaoLogica(false);
  $objPlanoTrabalhoDTO->retNumIdPlanoTrabalho();
  $objPlanoTrabalhoDTO->retStrNome();
  //$objPlanoTrabalhoDTO->retStrDescricao();
  $objPlanoTrabalhoDTO->retStrSinAtivo();

  //if ($_GET['acao'] == 'plano_trabalho_reativar'){
  //Lista somente inativos
  //$objPlanoTrabalhoDTO->setBolExclusaoLogica(false);
  //$objPlanoTrabalhoDTO->setStrSinAtivo('N');
  //}

  $strFiltroPlanosTrabalho = PaginaSEI::getInstance()->recuperarCampo('hdnPlanosTrabalhoFiltro', 'S');
  $objPlanoTrabalhoDTO->setStrSinAtivo($strFiltroPlanosTrabalho);

  PaginaSEI::getInstance()->prepararOrdenacao($objPlanoTrabalhoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objPlanoTrabalhoDTO);

  $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
  $arrObjPlanoTrabalhoDTO = $objPlanoTrabalhoRN->listar($objPlanoTrabalhoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objPlanoTrabalhoDTO);

  /** @var PlanoTrabalhoDTO[] $arrObjPlanoTrabalhoDTO */

  $numRegistros = count($arrObjPlanoTrabalhoDTO);

  if ($numRegistros > 0) {
    $bolCheck = false;

    if ($_GET['acao'] == 'plano_trabalho_selecionar') {
      $bolCheck = true;
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $boLAcaoPlanoTrabalhoConfigurar = false;
      $bolAcaoClonar = false;
    } else {
      if ($_GET['acao'] == 'plano_trabalho_reativar') {
        $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_reativar');
        $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_consultar');
        $bolAcaoAlterar = false;
        $bolAcaoImprimir = true;
        //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
        $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_excluir');
        $bolAcaoDesativar = false;
        $boLAcaoPlanoTrabalhoConfigurar = false;
        $bolAcaoClonar = false;
      } else {
        $bolAcaoReativar = ($strFiltroPlanosTrabalho == 'N') && SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_reativar');
        $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_consultar');
        $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_alterar');
        $bolAcaoImprimir = true;
        //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
        $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_excluir');
        $bolAcaoDesativar = ($strFiltroPlanosTrabalho == 'S') && SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_desativar');
        $boLAcaoPlanoTrabalhoConfigurar = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_configurar');
        $bolAcaoClonar = SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_clonar');
      }
    }


    if ($bolAcaoDesativar) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_desativar&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoReativar) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_excluir&acao_origem=' . $_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($objPlanoTrabalhoDTO->getStrSinAtivo() == 'S') {
      $strSumarioTabela = 'Tabela de Planos de Trabalho.';
      $strCaptionTabela = 'Planos de Trabalho';
    } else {
      $strSumarioTabela = 'Tabela de Planos de Trabalho Inativos.';
      $strCaptionTabela = 'Planos de Trabalho Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
    }
    $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objPlanoTrabalhoDTO, 'Nome', 'Nome', $arrObjPlanoTrabalhoDTO) . '</th>' . "\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPlanoTrabalhoDTO,'Descrição','Descricao',$arrObjPlanoTrabalhoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {
      if ($arrObjPlanoTrabalhoDTO[$i]->getStrSinAtivo() == 'S') {
        //$strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strCssTr = '<tr class="infraTrClara">';
      } else {
        $strCssTr = '<tr class="trVermelha">';
      }

      $strResultado .= $strCssTr;

      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho(), $arrObjPlanoTrabalhoDTO[$i]->getStrNome()) . '</td>';
      }
      $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjPlanoTrabalhoDTO[$i]->getStrNome()) . '</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjPlanoTrabalhoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho());

      if ($boLAcaoPlanoTrabalhoConfigurar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_configurar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_plano_trabalho=' . $arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . Icone::PLANO_TRABALHO_CONFIGURAR . '" title="Configurar" alt="Configurar" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_plano_trabalho=' . $arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeConsultar() . '" title="Consultar Plano de Trabalho" alt="Consultar Plano de Trabalho" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_plano_trabalho=' . $arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeAlterar() . '" title="Alterar Plano de Trabalho" alt="Alterar Plano de Trabalho" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoClonar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_clonar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_plano_trabalho=' . $arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeClonar() . '" title="Clonar Plano de Trabalho" alt="Clonar Plano de Trabalho" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjPlanoTrabalhoDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjPlanoTrabalhoDTO[$i]->getStrSinAtivo() == 'S') {
        $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeDesativar() . '" title="Desativar Plano de Trabalho" alt="Desativar Plano de Trabalho" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjPlanoTrabalhoDTO[$i]->getStrSinAtivo() == 'N') {
        $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeReativar() . '" title="Reativar Plano de Trabalho" alt="Reativar Plano de Trabalho" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir) {
        $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeExcluir() . '" title="Excluir Plano de Trabalho" alt="Excluir Plano de Trabalho" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'plano_trabalho_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  } else {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }
} catch (Exception $e) {
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<? if (0){ ?>
  <style><?}?>
    <? if (0){ ?></style><?
} ?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<? if (0){ ?>
  <script type="text/javascript"><?}?>

    function inicializar() {
      if ('<?=$_GET['acao']?>' == 'plano_trabalho_selecionar') {
        infraReceberSelecao();
        document.getElementById('btnFecharSelecao').focus();
      } else {
        document.getElementById('btnFechar').focus();
      }
      infraEfeitoTabelas(true);
    }

    <? if ($bolAcaoDesativar){ ?>
    function acaoDesativar(id, desc) {
      if (confirm("Confirma desativação do Plano de Trabalho \"" + desc + "\"?")) {
        document.getElementById('hdnInfraItemId').value = id;
        document.getElementById('frmPlanoTrabalhoLista').action = '<?=$strLinkDesativar?>';
        document.getElementById('frmPlanoTrabalhoLista').submit();
      }
    }

    function acaoDesativacaoMultipla() {
      if (document.getElementById('hdnInfraItensSelecionados').value == '') {
        alert('Nenhum Plano de Trabalho selecionado.');
        return;
      }
      if (confirm("Confirma desativação dos Planos de Trabalho selecionados?")) {
        document.getElementById('hdnInfraItemId').value = '';
        document.getElementById('frmPlanoTrabalhoLista').action = '<?=$strLinkDesativar?>';
        document.getElementById('frmPlanoTrabalhoLista').submit();
      }
    }
    <? } ?>

    <? if ($bolAcaoReativar){ ?>
    function acaoReativar(id, desc) {
      if (confirm("Confirma reativação do Plano de Trabalho \"" + desc + "\"?")) {
        document.getElementById('hdnInfraItemId').value = id;
        document.getElementById('frmPlanoTrabalhoLista').action = '<?=$strLinkReativar?>';
        document.getElementById('frmPlanoTrabalhoLista').submit();
      }
    }

    function acaoReativacaoMultipla() {
      if (document.getElementById('hdnInfraItensSelecionados').value == '') {
        alert('Nenhum Plano de Trabalho selecionado.');
        return;
      }
      if (confirm("Confirma reativação dos Planos de Trabalho selecionados?")) {
        document.getElementById('hdnInfraItemId').value = '';
        document.getElementById('frmPlanoTrabalhoLista').action = '<?=$strLinkReativar?>';
        document.getElementById('frmPlanoTrabalhoLista').submit();
      }
    }
    <? } ?>

    <? if ($bolAcaoExcluir){ ?>
    function acaoExcluir(id, desc) {
      if (confirm("Confirma exclusão do Plano de Trabalho \"" + desc + "\"?")) {
        document.getElementById('hdnInfraItemId').value = id;
        document.getElementById('frmPlanoTrabalhoLista').action = '<?=$strLinkExcluir?>';
        document.getElementById('frmPlanoTrabalhoLista').submit();
      }
    }

    function acaoExclusaoMultipla() {
      if (document.getElementById('hdnInfraItensSelecionados').value == '') {
        alert('Nenhum Plano de Trabalho selecionado.');
        return;
      }
      if (confirm("Confirma exclusão dos Planos de Trabalho selecionados?")) {
        document.getElementById('hdnInfraItemId').value = '';
        document.getElementById('frmPlanoTrabalhoLista').action = '<?=$strLinkExcluir?>';
        document.getElementById('frmPlanoTrabalhoLista').submit();
      }
    }
    <? } ?>

    function filtrar(sinAtivos) {
      document.getElementById('hdnPlanosTrabalhoFiltro').value = sinAtivos;
      document.getElementById('frmPlanoTrabalhoLista').action = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_listar&acao_origem=' . $_GET['acao'])?>';
      document.getElementById('frmPlanoTrabalhoLista').submit();
    }


    <? if (0){ ?></script><?
} ?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmPlanoTrabalhoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>
    <div id="divFiltro" class="infraAreaDados">
      <br>
      <a href="#" class="ancoraPadraoPreta" onclick="filtrar('<?=$strFiltroPlanosTrabalho == 'S' ? 'N' : 'S'?>')"><?=$strFiltroPlanosTrabalho == 'N' ? "Ver ativos" : "Ver inativos"?></a>
    </div>
    <input type="hidden" id="hdnPlanosTrabalhoFiltro" name="hdnPlanosTrabalhoFiltro" value="<?=$strFiltroPlanosTrabalho?>"/>
    <br>
    <?
    //PaginaSEI::getInstance()->abrirAreaDados('5em');
    //PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    //PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
