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
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);


  PaginaSip::getInstance()->salvarCamposPost(array('selOrgao', 'selHierarquia'));

  switch ($_GET['acao']) {
    case 'sistema_excluir':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjSistemaDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objSistemaDTO = new SistemaDTO();
          $objSistemaDTO->setNumIdSistema($arrStrIds[$i]);
          $arrObjSistemaDTO[] = $objSistemaDTO;
        }
        $objSistemaRN = new SistemaRN();
        $objSistemaRN->excluir($arrObjSistemaDTO);

        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

      break;

    case 'sistema_desativar':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjSistemaDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objSistemaDTO = new SistemaDTO();
          $objSistemaDTO->setNumIdSistema($arrStrIds[$i]);
          $arrObjSistemaDTO[] = $objSistemaDTO;
        }
        $objSistemaRN = new SistemaRN();
        $objSistemaRN->desativar($arrObjSistemaDTO);

        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'sistema_reativar':
      $strTitulo = 'Reativar Sistemas';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
          $arrObjSistemaDTO = array();
          for ($i = 0; $i < count($arrStrIds); $i++) {
            $objSistemaDTO = new SistemaDTO();
            $objSistemaDTO->setNumIdSistema($arrStrIds[$i]);
            $arrObjSistemaDTO[] = $objSistemaDTO;
          }
          $objSistemaRN = new SistemaRN();
          $objSistemaRN->reativar($arrObjSistemaDTO);
          PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
      }
      break;

    case 'sistema_listar':
      $strTitulo = 'Sistemas';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  if (SessaoSip::getInstance()->verificarPermissao('sistema_cadastrar')) {
    $arrComandos[] = '<input type="button" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\';" class="infraButton" />';
  }
  $objSistemaDTO = new SistemaDTO(true);
  $objSistemaDTO->retTodos();

  $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgao');
  if ($numIdOrgao !== '') {
    $objSistemaDTO->setNumIdOrgao($numIdOrgao);
  }

  $numIdHierarquia = PaginaSip::getInstance()->recuperarCampo('selHierarquia');
  if ($numIdHierarquia !== '') {
    $objSistemaDTO->setNumIdHierarquia($numIdHierarquia);
  }

  if ($_GET['acao'] == 'sistema_reativar') {
    //Lista somente inativos
    $objSistemaDTO->setBolExclusaoLogica(false);
    $objSistemaDTO->setStrSinAtivo('N');
  }

  PaginaSip::getInstance()->prepararOrdenacao($objSistemaDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);
  $objSistemaRN = new SistemaRN();
  $arrObjSistemaDTO = $objSistemaRN->listarSip($objSistemaDTO);

  $numRegistros = count($arrObjSistemaDTO);

  if ($numRegistros > 0) {
    if ($_GET['acao'] == 'sistema_selecionar') {
      $bolAcaoAdministradores = false;
      $bolAcaoServicoListar = false;
      $bolAcaoGerarChave = false;
      $bolAcaoClonar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('sistema_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolAcaoReativar = false;
      $bolAcaoImprimir = false;
    } else {
      if ($_GET['acao'] == 'sistema_reativar') {
        $bolAcaoAdministradores = false;
        $bolAcaoServicoListar = false;
        $bolAcaoGerarChave = false;
        $bolAcaoClonar = false;
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('sistema_consultar');
        $bolAcaoAlterar = false;
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('sistema_excluir');
        $bolAcaoDesativar = false;
        $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('sistema_reativar');
        $bolAcaoImprimir = true;
      } else {
        $bolAcaoAdministradores = SessaoSip::getInstance()->verificarPermissao('administrador_sistema_listar');
        $bolAcaoServicoListar = SessaoSip::getInstance()->verificarPermissao('servico_listar');
        $bolAcaoGerarChave = SessaoSip::getInstance()->verificarPermissao('sistema_gerar_chave_acesso');
        $bolAcaoClonar = SessaoSip::getInstance()->verificarPermissao('sistema_clonar');
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('sistema_consultar');
        $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('sistema_alterar');
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('sistema_excluir');
        $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('sistema_desativar');
        $bolAcaoReativar = false;
        $bolAcaoImprimir = true;
      }
    }


    //Montar ações múltiplas
    $bolCheck = false;

    if ($bolAcaoDesativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_desativar&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoReativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
    }

    if ($bolAcaoExcluir) {
      //$bolCheck = true;
      //$arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_excluir&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoImprimir) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    $strResultado = '';

    if ($_GET['acao'] != 'sistema_reativar') {
      $strSumarioTabela = 'Tabela de Sistemas.';
      $strCaptionTabela = 'Sistemas';
    } else {
      $strSumarioTabela = 'Tabela de Sistemas Inativos.';
      $strCaptionTabela = 'Sistemas Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';


    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" valign="center" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>';
    }
    $strResultado .= '<th class="infraTh" width="6%">' . PaginaSip::getInstance()->getThOrdenacao($objSistemaDTO, 'ID', 'IdSistema', $arrObjSistemaDTO) . '</th>';
    $strResultado .= '<th class="infraTh" width="10%">' . PaginaSip::getInstance()->getThOrdenacao($objSistemaDTO, 'Sigla', 'Sigla', $arrObjSistemaDTO) . '</th>';
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objSistemaDTO, 'Descrição', 'Descricao', $arrObjSistemaDTO) . '</th>';
    $strResultado .= '<th class="infraTh" width="10%">' . PaginaSip::getInstance()->getThOrdenacao($objSistemaDTO, '2FA', 'Sta2Fatores', $arrObjSistemaDTO) . '</th>';
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objSistemaDTO,'Página Inicial','PaginaInicial',$arrObjSistemaDTO).'</th>';
    $strResultado .= '<th class="infraTh" width="6%">' . PaginaSip::getInstance()->getThOrdenacao($objSistemaDTO, 'Órgão', 'SiglaOrgao', $arrObjSistemaDTO) . '</th>';
    $strResultado .= '<th class="infraTh" width="6%">' . PaginaSip::getInstance()->getThOrdenacao($objSistemaDTO, 'Hierarquia', 'NomeHierarquia', $arrObjSistemaDTO) . '</th>';
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>';
    $strResultado .= '</tr>' . "\n";

    $arrObjInfraValorStaDTO = InfraArray::indexarArrInfraDTO($objSistemaRN->listarValores2Fatores(), 'StaValor');

    for ($i = 0; $i < $numRegistros; $i++) {
      $strId = $arrObjSistemaDTO[$i]->getNumIdSistema();
      $strDescricao = PaginaSip::formatarParametrosJavaScript($arrObjSistemaDTO[$i]->getStrSigla());

      if (($i + 2) % 2) {
        $strResultado .= '<tr class="infraTrEscura">';
      } else {
        $strResultado .= '<tr class="infraTrClara">';
      }
      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjSistemaDTO[$i]->getNumIdSistema(), $arrObjSistemaDTO[$i]->getStrSigla()) . '</td>';
      }
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjSistemaDTO[$i]->getNumIdSistema()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjSistemaDTO[$i]->getStrSigla()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjSistemaDTO[$i]->getStrDescricao()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjInfraValorStaDTO[$arrObjSistemaDTO[$i]->getStrSta2Fatores()]->getStrDescricao()) . '</td>';
      //$strResultado .= '<td>'.$arrObjSistemaDTO[$i]->getStrPaginaInicial().'</td>';
      $strResultado .= '<td align="center"><a alt="' . PaginaSip::tratarHTML($arrObjSistemaDTO[$i]->getStrDescricaoOrgao()) . '" title="' . PaginaSip::tratarHTML($arrObjSistemaDTO[$i]->getStrDescricaoOrgao()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjSistemaDTO[$i]->getStrSiglaOrgao()) . '</a></td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjSistemaDTO[$i]->getStrNomeHierarquia()) . '</td>';
      $strResultado .= '<td align="center">';

      if ($bolAcaoGerarChave) {
        $strIconeChave = 'chave_cinza.svg';
        if ($arrObjSistemaDTO[$i]->getStrChaveAcesso() != null) {
          $strIconeChave = 'chave_laranja.svg';
        }
        $strResultado .= '<a href="' . PaginaSip::getInstance()->montarAncora($strId) . '" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);gerarChave(\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_gerar_chave_acesso&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_sistema=' . $arrObjSistemaDTO[$i]->getNumIdSistema()) . '\')" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img id="imgChaveAcesso' . $arrObjSistemaDTO[$i]->getNumIdSistema() . '" src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/' . $strIconeChave . '" title="Gerar Chave de Acesso" alt="Gerar Chave de Acesso" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAdministradores) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=administrador_sistema_listar&acao_retorno=sistema_listar&id_orgao_sistema=' . $arrObjSistemaDTO[$i]->getNumIdOrgao() . '&id_sistema=' . $arrObjSistemaDTO[$i]->getNumIdSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeGrupo() . '" title="Administradores do Sistema" alt="Administradores do Sistema" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoClonar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_clonar&acao_retorno=sistema_listar&id_orgao_sistema_origem=' . $arrObjSistemaDTO[$i]->getNumIdOrgao() . '&id_sistema_origem=' . $arrObjSistemaDTO[$i]->getNumIdSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeClonar() . '" title="Clonar Sistema" alt="Clonar Sistema" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_sistema=' . $arrObjSistemaDTO[$i]->getNumIdSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Sistema" alt="Consultar Sistema" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_sistema=' . $arrObjSistemaDTO[$i]->getNumIdSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Sistema" alt="Alterar Sistema" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar) {
        $strResultado .= '<a onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeDesativar() . '" title="Desativar Sistema" alt="Desativar Sistema" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar) {
        $strResultado .= '<a onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeReativar() . '" title="Reativar Sistema" alt="Reativar Sistema" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir) {
        $strResultado .= '<a onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Sistema" alt="Excluir Sistema" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton" />';

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaTodos('', 'Todos', $numIdOrgao);
  $strItensSelHierarquia = HierarquiaINT::montarSelectNome('', 'Todos', $numIdHierarquia);
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
  #lblOrgao {position:absolute;left:0%;top:0%;width:20%;}
  #selOrgao {position:absolute;left:0%;top:20%;width:20%;}

  #lblHierarquia {position:absolute;left:0%;top:50%;width:40%;}
  #selHierarquia {position:absolute;left:0%;top:70%;width:40%;}

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
  if (confirm("Confirma exclusão do Sistema \""+desc+"\"?")){
  infraExibirAviso();
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmSistemaLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmSistemaLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Sistema selecionado.');
  return;
  }
  if (confirm("Confirma exclusão dos Sistemas selecionados?")){
  infraExibirAviso();
  document.getElementById('frmSistemaLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmSistemaLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoDesativar) { ?>
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Sistema \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmSistemaLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmSistemaLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Sistema selecionado.');
  return;
  }
  if (confirm("Confirma desativação dos Sistemas selecionados?")){
  document.getElementById('frmSistemaLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmSistemaLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoReativar) { ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Sistema \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmSistemaLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmSistemaLista').submit();
  }
  }

  function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Sistema selecionado.');
  return;
  }
  if (confirm("Confirma reativação dos Sistemas selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmSistemaLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmSistemaLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoGerarChave) { ?>
  function gerarChave(link){
  if (confirm('Confirma geração de uma nova chave de acesso para o sistema?')){
  infraAbrirJanelaModal(link,700,200);
  }
  }
  <?
} ?>

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmSistemaLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink(basename(__FILE__) . '?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao('Sistemas');
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados('10em');
    ?>
    <label id="lblOrgao" for="selOrgao" accesskey="o" class="infraLabelOpcional">Órgã<span
        class="infraTeclaAtalho">o</span>:</label>
    <select id="selOrgao" name="selOrgao" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgao?>
    </select>

    <label id="lblHierarquia" for="selHierarquia" accesskey="H" class="infraLabelOpcional"><span
        class="infraTeclaAtalho">H</span>ierarquia:</label>
    <select id="selHierarquia" name="selHierarquia" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelHierarquia?>
    </select>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros, true);
    PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>