<?

try {
  require_once dirname(__FILE__) . '/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoConsultaProcessual::getInstance()->validarLink();

  $numRegistros = 0;
  $strResultado = '';

  switch ($_GET['acao']) {
    case 'consulta_processual_resultado':
      $strTitulo = 'Consulta Processual';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="button"  name="btnVoltarPesquisa" id="btnVoltarPesquisa" value="Voltar à Pesquisa" onclick="location.href=\'' . SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao=consulta_processual_voltar') . '\';" class="infraButton">Voltar à Pesquisa</button>';
  $arrComandos[] = '<button type="button"  name="btnNovaPesquisa" id="btnNovaPesquisa" value="Nova Pesquisa" onclick="location.href=\'' . SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao=consulta_processual_pesquisar') . '\';" class="infraButton">Nova Pesquisa</button>';

  $objConsultaProcessualDTO = new ConsultaProcessualDTO();
  $objConsultaProcessualDTO->setStrStaCriterioPesquisa(SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_CRITERIO_TIPO'));
  $objConsultaProcessualDTO->setStrValorPesquisa(SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_CRITERIO_VALOR'));
  $objConsultaProcessualDTO->setNumIdOrgaoUnidadeGeradora(SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_ORGAOS'));

  PaginaConsultaProcessual::getInstance()->prepararOrdenacao($objConsultaProcessualDTO, 'IdProcedimento', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaConsultaProcessual::getInstance()->prepararPaginacao($objConsultaProcessualDTO, 20);

  try {
    $objConsultaProcessualRN = new ConsultaProcessualRN();
    $arrObjProcedimentoDTO = $objConsultaProcessualRN->pesquisar($objConsultaProcessualDTO);
    $numRegistros = count($arrObjProcedimentoDTO);
  } catch (Exception $e) {
    PaginaConsultaProcessual::getInstance()->processarExcecao($e);
  }

  PaginaConsultaProcessual::getInstance()->processarPaginacao($objConsultaProcessualDTO);

  if ($numRegistros) {
    $strResultado .= '<table id="tblProcessos" width="99%" class="infraTable" summary="Lista de Processos">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaConsultaProcessual::getInstance()->gerarCaptionTabela("Processos", $numRegistros) . '</caption>';
    $strResultado .= '
                            <tr>
                              <th class="infraTh" width="1%" style="display:none;">' . PaginaConsultaProcessual::getInstance()->getThCheck() . '</th>
                              <th class="infraTh" width="20%">Processo</th>
                              <th class="infraTh"  width="20%">Tipo</th>
                              <th class="infraTh" >Interessados</th>
                               <th class="infraTh" width="5%">Autuação</th>
                              <th class="infraTh" width="10%">Unidade</th>
                              <th class="infraTh" width="10%">Órgão</th>
                            </tr>';


    $strCssTr = '';

    $i = 0;
    foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
      $strCssTr = ($strCssTr == 'class="infraTrClara"') ? 'class="infraTrEscura"' : 'class="infraTrClara"';
      $strResultado .= '<tr ' . $strCssTr . '>';

      $strLinkProcedimento = SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao=consulta_processual_processo&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $objProcedimentoDTO->getDblIdProcedimento());
      $strResultado .= '<td valign="top" style="display:none;">' . PaginaConsultaProcessual::getInstance()->getTrCheck($i++, $objProcedimentoDTO->getDblIdProcedimento(), $objProcedimentoDTO->getStrNomeTipoProcedimento()) . '</td>';
      $strResultado .= '<td align="center" valign="top"><a href="' . $strLinkProcedimento . '"  onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" alt="' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()) . '" title="' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()) . '" class="ancoraPadraoAzul">' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado()) . '</a></td>' . "\n";
      $strResultado .= '<td align="center" valign="top">' . $objProcedimentoDTO->getStrNomeTipoProcedimento() . '</td>';
      $strResultado .= '<td align="left" style="white-space: nowrap">';
      if ($objProcedimentoDTO->getArrObjParticipanteDTO() == null) {
        $strResultado .= '&nbsp;';
      } else {
        $strResultado .= nl2br(PaginaConsultaProcessual::tratarHTML(implode("\n", InfraArray::converterArrInfraDTO($objProcedimentoDTO->getArrObjParticipanteDTO(), 'NomeContato'))));
      }
      $strResultado .= '</td>';
      $strResultado .= '<td align="center" valign="top">' . PaginaConsultaProcessual::tratarHTML(substr($objProcedimentoDTO->getDtaGeracaoProtocolo(), 0, 10)) . '</td>';
      $strResultado .= '<td align="center" valign="top"><a alt="' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()) . '" title="' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()) . '" class="ancoraSigla">' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTO->getStrSiglaUnidadeGeradoraProtocolo()) . '</a></td>';
      $strResultado .= '<td align="center" valign="top"><a alt="' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTO->getStrDescricaoOrgaoUnidadeGeradoraProtocolo()) . '" title="' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTO->getStrDescricaoOrgaoUnidadeGeradoraProtocolo()) . '" class="ancoraSigla">' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTO->getStrSiglaOrgaoUnidadeGeradoraProtocolo()) . '</a></td>';
      $strResultado .= '</tr>';
    }
    $strResultado .= '</table>';
  }
} catch (Exception $e) {
  PaginaConsultaProcessual::getInstance()->processarExcecao($e);
}
PaginaConsultaProcessual::getInstance()->montarDocType();
PaginaConsultaProcessual::getInstance()->abrirHtml();
PaginaConsultaProcessual::getInstance()->abrirHead();
PaginaConsultaProcessual::getInstance()->montarMeta();
PaginaConsultaProcessual::getInstance()->montarTitle(PaginaConsultaProcessual::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaConsultaProcessual::getInstance()->montarStyle();
CaptchaSEI::getInstance()->montarStyle();
PaginaConsultaProcessual::getInstance()->abrirStyle();
?>

<?
PaginaConsultaProcessual::getInstance()->fecharStyle();
PaginaConsultaProcessual::getInstance()->montarJavaScript();
CaptchaSEI::getInstance()->montarJavascript();
PaginaConsultaProcessual::getInstance()->abrirJavaScript();
?>

<? if (0){ ?> <script type="text/javascript"> <?} ?>

  function inicializar() {
    infraEfeitoTabelas();
  }

<? if (0){ ?></script> <? } ?>

<?
PaginaConsultaProcessual::getInstance()->fecharJavaScript();
PaginaConsultaProcessual::getInstance()->fecharHead();
PaginaConsultaProcessual::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmConsultaProcessualResultado" method="post" action="<?=SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaConsultaProcessual::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaConsultaProcessual::getInstance()->montarAreaTabela($strResultado, $numRegistros, true);
    ?>
  </form>
<?
PaginaConsultaProcessual::getInstance()->montarAreaDebug();
PaginaConsultaProcessual::getInstance()->fecharBody();
PaginaConsultaProcessual::getInstance()->fecharHtml();
