<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/10/2019 - criado por mga
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

  PaginaSip::getInstance()->prepararSelecao('sistema_servico_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  switch ($_GET['acao']) {
    case 'sistema_servico_selecionar':
      $strTitulo = PaginaSip::getInstance()->getTituloSelecao('Selecionar Serviço', 'Selecionar Serviços');
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';

  $objSistemaRN = new SistemaRN();
  $arrObjTipoServicoDTO = $objSistemaRN->listarValoresServico();

  $numRegistros = count($arrObjTipoServicoDTO);

  if ($numRegistros > 0) {
    $strResultado = '';

    $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Serviços">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela('Serviços', $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">Identificação</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';

    for ($i = 0; $i < $numRegistros; $i++) {
      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultado .= $strCssTr;
      $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjTipoServicoDTO[$i]->getStrStaServico(), $arrObjTipoServicoDTO[$i]->getStrDescricao()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjTipoServicoDTO[$i]->getStrDescricao()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::getInstance()->getAcaoTransportarItem($i, $arrObjTipoServicoDTO[$i]->getStrStaServico()) . '</td>';
      $strResultado .= '</tr>' . "\n";
    }
    $strResultado .= '</table>';
  }

  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
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
  infraReceberSelecao();
  document.getElementById('btnFecharSelecao').focus();
  infraEfeitoTabelas();
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmSistemaServicoSelecao" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    //PaginaSip::getInstance()->montarAreaDebug();
    //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>