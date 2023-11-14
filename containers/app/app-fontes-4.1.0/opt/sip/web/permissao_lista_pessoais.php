<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
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


  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema'));

  switch ($_GET['acao']) {
    case 'permissao_listar_pessoais':
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $objPermissaoDTO = new PermissaoDTO();
  $objPermissaoDTO->retNumIdPerfil();
  $objPermissaoDTO->retNumIdSistema();
  $objPermissaoDTO->retNumIdUsuario();
  $objPermissaoDTO->retNumIdUnidade();
  $objPermissaoDTO->retNumIdTipoPermissao();
  $objPermissaoDTO->retStrSiglaSistema();
  $objPermissaoDTO->retStrDescricaoSistema();
  $objPermissaoDTO->retStrDescricaoOrgaoSistema();
  $objPermissaoDTO->retStrSiglaOrgaoSistema();
  $objPermissaoDTO->retStrNomeUsuario();
  $objPermissaoDTO->retStrSiglaUsuario();
  $objPermissaoDTO->retStrSiglaOrgaoUsuario();
  $objPermissaoDTO->retStrDescricaoOrgaoUsuario();
  $objPermissaoDTO->retStrSiglaSistema();
  $objPermissaoDTO->retStrSiglaOrgaoSistema();
  $objPermissaoDTO->retStrSiglaUnidade();
  $objPermissaoDTO->retStrDescricaoUnidade();
  $objPermissaoDTO->retStrSiglaOrgaoUnidade();
  $objPermissaoDTO->retStrDescricaoOrgaoUnidade();
  $objPermissaoDTO->retStrNomePerfil();
  $objPermissaoDTO->retDtaDataFim();

  //ORGAO SISTEMA
  $numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
  if ($numIdOrgaoSistema !== '') {
    $objPermissaoDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
    $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
  } else {
    $strDesabilitarSistema = 'disabled="disabled"';
    $numIdSistema = '';
  }

  //SISTEMA
  if ($numIdSistema !== '') {
    $objPermissaoDTO->setNumIdSistema($numIdSistema);
  }

  PaginaSip::getInstance()->prepararOrdenacao($objPermissaoDTO, 'SiglaUsuario', InfraDTO::$TIPO_ORDENACAO_ASC);

  $objPermissaoRN = new PermissaoRN();
  $arrObjPermissaoDTO = $objPermissaoRN->listarPessoais($objPermissaoDTO);

  $numRegistros = count($arrObjPermissaoDTO);

  if ($numRegistros > 0) {
    //$bolAcaoDelegar = SessaoSip::getInstance()->verificarPermissao('permissao_delegar');
    $bolAcaoDelegar = false;
    $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('permissao_consultar');

    //Montar ações múltiplas
    $bolCheck = true;

    if ($bolAcaoDelegar) {
      $arrComandos[] = '<input type="button" id="btnDelegar" value="Delegar" onclick="acaoDelegacaoMultipla();" class="infraButton" />';
      $strLinkDelegar = SessaoSip::getInstance()->assinarLink('permissao_delegar.php?acao=permissao_delegar&acao_origem=permissao_listar_pessoais&acao_retorno=permissao_listar_pessoais');
    }

    $arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';

    $strResultado = '';
    $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Permissões pessoais cadastradas">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela('Permissões Pessoais', $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>';
    }

    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objPermissaoDTO, 'Sistema', 'SiglaSistema', $arrObjPermissaoDTO) . '</th>';
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objPermissaoDTO, 'Usuário', 'SiglaUsuario', $arrObjPermissaoDTO) . '</th>';
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objPermissaoDTO, 'Unidade', 'SiglaUnidade', $arrObjPermissaoDTO) . '</th>';
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objPermissaoDTO, 'Perfil', 'NomePerfil', $arrObjPermissaoDTO) . '</th>';
    $strResultado .= '<th class="infraTh">Ações</th>';
    $strResultado .= '</tr>' . "\n";


    $objInfraParametro = new InfraParametro(BancoSip::getInstance());
    $arrPerfisReservados = $objInfraParametro->listarValores(array(
        'ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA', 'ID_PERFIL_SIP_COORDENADOR_PERFIL', 'ID_PERFIL_SIP_COORDENADOR_UNIDADE', 'ID_PERFIL_SIP_ADMINISTRADOR_SIP'
      ));

    $n = 0;

    $dtaAtual = InfraData::getStrDataAtual();

    for ($i = 0; $i < $numRegistros; $i++) {
      if ($arrObjPermissaoDTO[$i]->getDtaDataFim() == null || InfraData::compararDatas($dtaAtual, $arrObjPermissaoDTO[$i]->getDtaDataFim()) >= 0) {
        if (($i + 2) % 2) {
          $strResultado .= '<tr class="infraTrEscura">';
        } else {
          $strResultado .= '<tr class="infraTrClara">';
        }
      } else {
        $strResultado .= '<tr class="trVermelha">';
      }

      //if ($bolCheck && !in_array($arrObjPermissaoDTO[$i]->getNumIdPerfil(),$arrPerfisReservados)){
      $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($n++,
          $arrObjPermissaoDTO[$i]->getNumIdPerfil() . '-' . $arrObjPermissaoDTO[$i]->getNumIdSistema() . '-' . $arrObjPermissaoDTO[$i]->getNumIdUsuario() . '-' . $arrObjPermissaoDTO[$i]->getNumIdUnidade(),
          $arrObjPermissaoDTO[$i]->getStrSiglaSistema() . '/' . $arrObjPermissaoDTO[$i]->getStrSiglaOrgaoSistema() . ' - ' . $arrObjPermissaoDTO[$i]->getStrSiglaUsuario() . '/' . $arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUsuario() . ' - ' . $arrObjPermissaoDTO[$i]->getStrSiglaUnidade() . '/' . $arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUnidade() . ' - ' . $arrObjPermissaoDTO[$i]->getStrNomePerfil()) . '</td>';
      //}else{
      //  $strResultado .= '<td valign="top">&nbsp;</td>';
      //}
      $strResultado .= '<td align="center">';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoSistema()) . '" title="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoSistema()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaSistema()) . '</a>';
      $strResultado .= '&nbsp;/&nbsp;';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoSistema()) . '" title="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoSistema()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaOrgaoSistema()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td align="center">';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrNomeUsuario()) . '" title="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaUsuario()) . '</a>';
      $strResultado .= '&nbsp;/&nbsp;';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUsuario()) . '" title="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUsuario()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUsuario()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td align="center">';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoUnidade()) . '" title="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoUnidade()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaUnidade()) . '</a>';
      $strResultado .= '&nbsp;/&nbsp;';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUnidade()) . '" title="' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUnidade()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUnidade()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrNomePerfil()) . '</td>';
      $strResultado .= '<td align="center">';

      if ($bolAcaoDelegar && $arrObjPermissaoDTO[$i]->getNumIdTipoPermissao() != PermissaoRN::$TIPO_NAO_DELEGAVEL && !in_array($arrObjPermissaoDTO[$i]->getNumIdPerfil(), $arrPerfisReservados)) {
        $strResultado .= '<a onclick="acaoDelegar(\'' . $arrObjPermissaoDTO[$i]->getNumIdPerfil() . '-' . $arrObjPermissaoDTO[$i]->getNumIdSistema() . '-' . $arrObjPermissaoDTO[$i]->getNumIdUsuario() . '-' . $arrObjPermissaoDTO[$i]->getNumIdUnidade() . '\',\'' . PaginaSip::formatarParametrosJavaScript($arrObjPermissaoDTO[$i]->getStrSiglaUsuario() . ' / ' . $arrObjPermissaoDTO[$i]->getStrSiglaSistema() . ' / ' . $arrObjPermissaoDTO[$i]->getStrNomePerfil()) . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/permissao_delegar.svg" title="Delegar Permissão" alt="Delegar Permissão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=permissao_consultar&origem=permissao_listar_pessoais&acao_retorno=permissao_listar_pessoais&id_perfil=' . $arrObjPermissaoDTO[$i]->getNumIdPerfil() . '&id_sistema=' . $arrObjPermissaoDTO[$i]->getNumIdSistema() . '&id_usuario=' . $arrObjPermissaoDTO[$i]->getNumIdUsuario() . '&id_unidade=' . $arrObjPermissaoDTO[$i]->getNumIdUnidade()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Permissão" alt="Consultar Permissão" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton" />';


  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaPessoais('', 'Todos', $numIdOrgaoSistema);
  $strItensSelSistema = SistemaINT::montarSelectSiglaPessoais('', 'Todos', $numIdSistema, $numIdOrgaoSistema);
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Permissões Pessoais');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
  #selOrgaoSistema {position:absolute;left:0%;top:20%;width:25%;}

  #lblSistema {position:absolute;left:0%;top:50%;width:25%;}
  #selSistema {position:absolute;left:0%;top:70%;width:25%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  infraEfeitoTabelas();
  }

<?
if ($bolAcaoDelegar) { ?>
  function acaoDelegar(id,desc){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmPermissaoListaPessoais').action='<?=$strLinkDelegar?>';
  document.getElementById('frmPermissaoListaPessoais').submit();
  }

  function acaoDelegacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Permissão selecionada.');
  return;
  }
  document.getElementById('frmPermissaoListaPessoais').action='<?=$strLinkDelegar?>';
  document.getElementById('frmPermissaoListaPessoais').submit();
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
PaginaSip::getInstance()->abrirBody('Permissões Pessoais', 'onload="inicializar();"');
?>
  <form id="frmPermissaoListaPessoais" method="post" action="<?=SessaoSip::getInstance()->assinarLink('permissao_lista_pessoais.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao('Permissões Pessoais');
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados('10em');
    ?>

    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="o" class="infraLabelObrigatorio">Ó<span
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
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros, true);
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>