<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
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

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema', 'selGrupoPerfil'));

  switch ($_GET['acao']) {
    case 'perfil_excluir':
      try {
        $arrObjPerfilDTO = array();
        $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
        for ($i = 0; $i < count($arrStrId); $i++) {
          $arrStrIdComposto = explode('-', $arrStrId[$i]);
          $objPerfilDTO = new PerfilDTO();
          $objPerfilDTO->setNumIdPerfil($arrStrIdComposto[0]);
          $objPerfilDTO->setNumIdSistema($arrStrIdComposto[1]);
          $arrObjPerfilDTO[] = $objPerfilDTO;
        }
        $objPerfilRN = new PerfilRN();
        $objPerfilRN->excluir($arrObjPerfilDTO);
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'perfil_desativar':
      try {
        $arrObjPerfilDTO = array();
        $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
        for ($i = 0; $i < count($arrStrId); $i++) {
          $arrStrIdComposto = explode('-', $arrStrId[$i]);
          $objPerfilDTO = new PerfilDTO();
          $objPerfilDTO->setNumIdPerfil($arrStrIdComposto[0]);
          $objPerfilDTO->setNumIdSistema($arrStrIdComposto[1]);
          $arrObjPerfilDTO[] = $objPerfilDTO;
        }
        $objPerfilRN = new PerfilRN();
        $objPerfilRN->desativar($arrObjPerfilDTO);
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'perfil_reativar':
      $strTitulo = 'Reativar Perfis';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrObjPerfilDTO = array();
          $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
          for ($i = 0; $i < count($arrStrId); $i++) {
            $arrStrIdComposto = explode('-', $arrStrId[$i]);
            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->setNumIdPerfil($arrStrIdComposto[0]);
            $objPerfilDTO->setNumIdSistema($arrStrIdComposto[1]);
            $arrObjPerfilDTO[] = $objPerfilDTO;
          }
          $objPerfilRN = new PerfilRN();
          $objPerfilRN->reativar($arrObjPerfilDTO);
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
      }
      break;

    case 'perfil_listar':
      $strTitulo = 'Perfis';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  if (SessaoSip::getInstance()->verificarPermissao('perfil_cadastrar')) {
    $arrComandos[] = '<input type="button" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_cadastrar&acao_origem=perfil_listar&acao_retorno=perfil_listar') . '\';" class="infraButton" />';
  }

  if (SessaoSip::getInstance()->verificarPermissao('grupo_perfil_listar')) {
    $arrComandos[] = '<input type="button" id="btnGrupos" value="Grupos" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=grupo_perfil_listar&acao_origem=perfil_listar&acao_retorno=perfil_listar') . '\';" class="infraButton" />';
  }

  $objPerfilDTO = new PerfilDTO();
  $objPerfilDTO->retTodos(true);

  //ORGAO
  $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
  if ($numIdOrgao !== '') {
    $objPerfilDTO->setNumIdOrgaoSistema($numIdOrgao);
  }

  //SISTEMA
  $numIdSistema = '';
  if ($numIdOrgao !== '') {
    $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema', 'null');
    if ($numIdSistema !== '') {
      $objPerfilDTO->setNumIdSistema($numIdSistema);
    }
  } else {
    //Para todos os orgãos os sistemas podem se repetir então não possibilita
    //escolha (desabilita combo)
    $strDesabilitar = 'disabled="disabled"';
  }

  //Grupo PERFIL
  $numIdGrupoPerfil = PaginaSip::getInstance()->recuperarCampo('selGrupoPerfil', 'null');
  if ($numIdGrupoPerfil != '' && $numIdGrupoPerfil != 'null') {
    $objPerfilDTO->setNumIdGrupoPerfil($numIdGrupoPerfil);
  }

  if ($_GET['acao'] == 'perfil_reativar') {
    //Lista somente inativos
    $objPerfilDTO->setBolExclusaoLogica(false);
    $objPerfilDTO->setStrSinAtivo('N');
  }

  PaginaSip::getInstance()->prepararOrdenacao($objPerfilDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);

  $objPerfilRN = new PerfilRN();
  $arrObjPerfilDTO = $objPerfilRN->listarAdministrados($objPerfilDTO);

  $numRegistros = count($arrObjPerfilDTO);

  if ($numRegistros > 0) {
    $objInfraParametro = new InfraParametro(BancoSip::getInstance());
    $arrReservados = $objInfraParametro->listarValores(array(
      'ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA', 'ID_PERFIL_SIP_ADMINISTRADOR_SIP', 'ID_PERFIL_SIP_COORDENADOR_PERFIL', 'ID_PERFIL_SIP_COORDENADOR_UNIDADE'
    ));


    if ($_GET['acao'] == 'perfil_reativar') {
      $bolAcaoCoordenadores = false;
      $bolAcaoClonar = false;
      $bolAcaoMontar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('perfil_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('perfil_excluir');
      $bolAcaoDesativar = false;
      $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('perfil_reativar');
    } else {
      $bolAcaoCoordenadores = SessaoSip::getInstance()->verificarPermissao('coordenador_perfil_listar');
      $bolAcaoClonar = SessaoSip::getInstance()->verificarPermissao('perfil_clonar');
      $bolAcaoMontar = SessaoSip::getInstance()->verificarPermissao('perfil_montar');
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('perfil_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('perfil_alterar');
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('perfil_excluir');
      $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('perfil_desativar');
      $bolAcaoReativar = false;
    }


    //Montar ações múltiplas
    $bolCheck = true;
    if ($bolAcaoExcluir) {
      //$bolCheck = true;
      //$arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_excluir&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);
    }

    if ($bolAcaoDesativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_desativar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);
    }

    if ($bolAcaoReativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_reativar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&acao_confirmada=sim');
    }

    $arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';

    $strResultado = '';
    if ($_GET['acao'] != 'perfil_reativar') {
      $strSumarioTabela = 'Tabela de Perfis.';
      $strCaptionTabela = 'Perfis';
    } else {
      $strSumarioTabela = 'Tabela de Perfis Inativos.';
      $strCaptionTabela = 'Perfis Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';

    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>';
    }
    $strResultado .= '<th class="infraTh" width="10%">' . PaginaSip::getInstance()->getThOrdenacao($objPerfilDTO, 'ID', 'IdPerfil', $arrObjPerfilDTO) . '</th>';
    $strResultado .= '<th class="infraTh" width="20%">' . PaginaSip::getInstance()->getThOrdenacao($objPerfilDTO, 'Nome', 'Nome', $arrObjPerfilDTO) . '</th>';
    $strResultado .= '<th class="infraTh">Descrição</th>';
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objPerfilDTO, '2FA', 'Sin2Fatores', $arrObjPerfilDTO) . '</th>';
    $strResultado .= '<th class="infraTh" width="20%">' . PaginaSip::getInstance()->getThOrdenacao($objPerfilDTO, 'Sistema', 'SiglaSistema', $arrObjPerfilDTO) . '</th>';
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>';
    $strResultado .= '</tr>' . "\n";
    for ($i = 0; $i < $numRegistros; $i++) {
      if (($i + 2) % 2) {
        $strResultado .= '<tr class="infraTrEscura">';
      } else {
        $strResultado .= '<tr class="infraTrClara">';
      }
      if ($bolCheck) {
        $strResultado .= '<td>' . PaginaSip::getInstance()->getTrCheck($i, $arrObjPerfilDTO[$i]->getNumIdPerfil() . '-' . $arrObjPerfilDTO[$i]->getNumIdSistema(), $arrObjPerfilDTO[$i]->getStrNome()) . '</td>';
      }
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getNumIdPerfil()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrNome()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrDescricao()) . '</td>';

      //$strResultado .= '<td align="center">'.$arrObjPerfilDTO[$i]->getStrSiglaSistema().' / '.$arrObjPerfilDTO[$i]->getStrSiglaOrgaoSistema().'</td>';

      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrSin2Fatores()) . '</td>';

      $strResultado .= '<td align="center">';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrDescricaoSistema()) . '" title="' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrDescricaoSistema()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrSiglaSistema()) . '</a>';
      $strResultado .= '&nbsp;/&nbsp;';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrDescricaoOrgaoSistema()) . '" title="' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrDescricaoOrgaoSistema()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrSiglaOrgaoSistema()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td align="center">';


      if ($bolAcaoMontar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_montar&acao_retorno=' . $_GET['acao'] . '&id_perfil=' . $arrObjPerfilDTO[$i]->getNumIdPerfil() . '&id_sistema=' . $arrObjPerfilDTO[$i]->getNumIdSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/perfil_montar.svg?2" title="Montar Perfil" alt="Montar Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoCoordenadores) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=coordenador_perfil_listar&acao_retorno=' . $_GET['acao'] . '&id_perfil=' . $arrObjPerfilDTO[$i]->getNumIdPerfil() . '&id_orgao_sistema=' . $arrObjPerfilDTO[$i]->getNumIdOrgaoSistema() . '&id_sistema=' . $arrObjPerfilDTO[$i]->getNumIdSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeGrupo() . '" title="Coordenadores do Perfil" alt="Coordenadores do Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoClonar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_clonar&acao_retorno=' . $_GET['acao'] . '&id_orgao_sistema=' . $arrObjPerfilDTO[$i]->getNumIdOrgaoSistema() . '&id_sistema=' . $arrObjPerfilDTO[$i]->getNumIdSistema() . '&id_perfil_origem=' . $arrObjPerfilDTO[$i]->getNumIdPerfil()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeClonar() . '" title="Clonar Perfil" alt="Clonar Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_consultar&acao_origem=perfil_listar&acao_retorno=' . $_GET['acao'] . '&id_perfil=' . $arrObjPerfilDTO[$i]->getNumIdPerfil() . '&id_sistema=' . $arrObjPerfilDTO[$i]->getNumIdSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Perfil" alt="Consultar Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_alterar&&acao_origem=perfil_listar&acao_retorno=' . $_GET['acao'] . '&id_perfil=' . $arrObjPerfilDTO[$i]->getNumIdPerfil() . '&id_sistema=' . $arrObjPerfilDTO[$i]->getNumIdSistema()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Perfil" alt="Alterar Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjPerfilDTO[$i]->getNumIdPerfil() . '-' . $arrObjPerfilDTO[$i]->getNumIdSistema();
        $strDescricao = PaginaSip::formatarParametrosJavaScript($arrObjPerfilDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && !in_array($arrObjPerfilDTO[$i]->getNumIdPerfil(), $arrReservados)) {
        $strResultado .= '<a onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeDesativar() . '" title="Desativar Perfil" alt="Desativar Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar) {
        $strResultado .= '<a onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeReativar() . '" title="Reativar Perfil" alt="Reativar Perfil" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir && !in_array($arrObjPerfilDTO[$i]->getNumIdPerfil(), $arrReservados)) {
        $strResultado .= '<a onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Perfil" alt="Excluir Perfil" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton" />';

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null', '&nbsp;', $numIdOrgao);
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null', '&nbsp;', $numIdSistema, $numIdOrgao);

  if ($numIdSistema !== 'null') {
    $strItensSelGrupoPerfil = GrupoPerfilINT::montarSelectNome('null', '&nbsp;', $numIdGrupoPerfil, $numIdSistema);
  }
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Perfis');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
  #selOrgaoSistema {position:absolute;left:0%;top:20%;width:20%;}

  #lblSistema {position:absolute;left:0%;top:50%;width:20%;}
  #selSistema {position:absolute;left:0%;top:70%;width:20%;}

  #lblGrupoPerfil {position:absolute;left:22%;top:0%;width:40%;}
  #selGrupoPerfil {position:absolute;left:22%;top:20%;width:40%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='perfil_selecionar'){
  infraReceberSelecao();
  }
  infraEfeitoTabelas();
  }


<?
if ($bolAcaoExcluir) { ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Perfil \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmPerfilLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmPerfilLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Perfil selecionado.');
  return;
  }
  if (confirm("Confirma exclusão dos Perfis selecionados?")){
  document.getElementById('frmPerfilLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmPerfilLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoDesativar) { ?>
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Perfil \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmPerfilLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmPerfilLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Perfil selecionado.');
  return;
  }
  if (confirm("Confirma desativação dos Perfis selecionados?")){
  document.getElementById('frmPerfilLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmPerfilLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoReativar) { ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Perfil \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmPerfilLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmPerfilLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Perfil selecionado.');
  return;
  }
  if (confirm("Confirma desativação dos Perfis selecionados?")){
  document.getElementById('frmPerfilLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmPerfilLista').submit();
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
  <form id="frmPerfilLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('perfil_lista.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao('Perfis');
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

    <label id="lblGrupoPerfil" for="selGrupoPerfil" class="infraLabelOpcional">Grupo:</label>
    <select id="selGrupoPerfil" name="selGrupoPerfil" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> >
      <?=$strItensSelGrupoPerfil?>
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