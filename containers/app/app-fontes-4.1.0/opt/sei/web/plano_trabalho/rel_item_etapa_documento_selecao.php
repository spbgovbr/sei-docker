<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/09/2022 - criado por mgb29
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

  PaginaSEI::getInstance()->prepararSelecao('rel_item_etapa_documento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_procedimento', 'id_item_etapa'));

  switch ($_GET['acao']) {
    case 'rel_item_etapa_documento_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Documento da Unidade', 'Selecionar Documentos da Unidade');

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == 'rel_item_etapa_documento_cadastrar') {
        if (isset($_GET['id_documento']) && isset($_GET['id_item_etapa'])) {
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_documento'] . '-' . $_GET['id_item_etapa']);
        }
      }
      break;

    case 'rel_item_etapa_documento_listar':
      $strTitulo = 'Documentos da Unidade';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'rel_item_etapa_documento_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
  $objRelItemEtapaSerieDTO->retNumIdItemEtapa();
  $objRelItemEtapaSerieDTO->retNumIdSerie();
  $objRelItemEtapaSerieDTO->setNumIdItemEtapa($_GET['id_item_etapa']);

  $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();
  $arrObjRelItemEtapaSerieDTO = $objRelItemEtapaSerieRN->listar($objRelItemEtapaSerieDTO);

  $arrObjDocumentoDTO = array();

  if (count($arrObjRelItemEtapaSerieDTO)) {
    $objRelItemEtapaUnidadeDTO = new RelItemEtapaUnidadeDTO();
    $objRelItemEtapaUnidadeDTO->retNumIdItemEtapa();
    $objRelItemEtapaUnidadeDTO->retNumIdUnidade();
    $objRelItemEtapaUnidadeDTO->setNumIdItemEtapa($_GET['id_item_etapa']);

    $objRelItemEtapaUnidadeRN = new RelItemEtapaUnidadeRN();
    $arrObjRelItemEtapaUnidadeDTO = $objRelItemEtapaUnidadeRN->listar($objRelItemEtapaUnidadeDTO);

    if (count($arrObjRelItemEtapaUnidadeDTO) == 0 || in_array(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), InfraArray::converterArrInfraDTO($arrObjRelItemEtapaUnidadeDTO, 'IdUnidade'))) {
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrNomeSerie();
      $objDocumentoDTO->retStrNumero();
      $objDocumentoDTO->retStrNomeArvore();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
      $objDocumentoDTO->setNumIdSerie(InfraArray::converterArrInfraDTO($arrObjRelItemEtapaSerieDTO, 'IdSerie'), InfraDTO::$OPER_IN);
      $objDocumentoDTO->setNumIdUnidadeGeradoraProtocolo(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $objDocumentoRN = new DocumentoRN();
      $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);
    }
  }

  $numRegistros = count($arrObjDocumentoDTO);

  if ($numRegistros > 0) {
    $strResultado = '';

    $strSumarioTabela = 'Tabela de Documentos da Unidade.';
    $strCaptionTabela = 'Documentos da Unidade';

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="15%">Protocolo</th>' . "\n";
    $strResultado .= '<th class="infraTh">Tipo</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {
      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultado .= $strCssTr;
      $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjDocumentoDTO[$i]->getDblIdDocumento(), DocumentoINT::formatarIdentificacaoComProtocolo($arrObjDocumentoDTO[$i])) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjDocumentoDTO[$i]->getStrProtocoloDocumentoFormatado()) . '</td>';
      $strResultado .= '<td align="left">' . DocumentoINT::formatarIdentificacao($arrObjDocumentoDTO[$i]) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjDocumentoDTO[$i]->getDblIdDocumento()) . '</td>' . "\n";
      $strResultado .= '</tr>' . "\n";
    }
    $strResultado .= '</table>';
  }

  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
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
      infraReceberSelecao();
      document.getElementById('btnFecharSelecao').focus();
      infraEfeitoTabelas(true);
    }

    <? if (0){ ?></script><?
} ?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmRelItemEtapaDocumentoSelecao" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    //PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
