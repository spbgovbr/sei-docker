<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 12/12/2011 - criado por mga
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 */

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();


  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array(
    'arvore', 'id_procedimento', 'id_plano_trabalho', 'id_etapa_trabalho', 'id_item_etapa'
  ));

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'plano_trabalho_consultar_historico':
      //Título
      $strTitulo = 'Histórico do Plano de Trabalho';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $objHistoricoPlanoTrabalhoDTO = new HistoricoPlanoTrabalhoDTO();
  $objHistoricoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($_GET['id_plano_trabalho']);
  $objHistoricoPlanoTrabalhoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  $objHistoricoPlanoTrabalhoDTO->setStrStaHistorico(PlanoTrabalhoRN::$TH_PLANO_TRABALHO);

  PaginaSEI::getInstance()->prepararPaginacao($objHistoricoPlanoTrabalhoDTO, 100);
  $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
  $objPlanoTrabalhoDTOHistorico = $objPlanoTrabalhoRN->consultarHistorico($objHistoricoPlanoTrabalhoDTO);
  PaginaSEI::getInstance()->processarPaginacao($objHistoricoPlanoTrabalhoDTO);

  $arrObjAndamentoPlanoTrabalhoDTO = $objPlanoTrabalhoDTOHistorico->getArrObjAndamentoPlanoTrabalhoDTO();

  $numRegistrosAndamentoPlanoTrabalho = count($arrObjAndamentoPlanoTrabalhoDTO);

  if ($numRegistrosAndamentoPlanoTrabalho > 0) {
    $bolCheck = false;

    $strResultado = '';

    $strResultado .= '<table id="tblHistorico" width="99%" class="infraTable" summary="Histórico de Andamentos">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('Andamentos', $numRegistrosAndamentoPlanoTrabalho) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="10%">Situação</th>';
    $strResultado .= '<th class="infraTh" width="10%">Data/Hora</th>';
    $strResultado .= '<th class="infraTh" width="10%">Unidade</th>';
    $strResultado .= '<th class="infraTh" width="10%">Usuário</th>';
    $strResultado .= '<th class="infraTh">Descrição</th>';
    $strResultado .= '</tr>' . "\n";

    $strQuebraLinha = '<span style="line-height:.5em"><br /></span>';


    foreach ($arrObjAndamentoPlanoTrabalhoDTO as $objAndamentoPlanoTrabalhoDTO) {
      //InfraDebug::getInstance()->gravar($objAndamentoPlanoTrabalhoDTO->getNumIdAndamentoPlanoTrabalho());

      $strResultado .= "\n\n" . '<!-- ' . $objAndamentoPlanoTrabalhoDTO->getNumIdAndamentoPlanoTrabalho() . ' -->' . "\n";

      $strResultado .= '<tr class="infraTrClara">';

      if ($objAndamentoPlanoTrabalhoDTO->getObjSituacaoAndamentoPlanoTrabalhoDTO() != null) {
        $objSituacaoAndamentoPlanoTrabalhoDTO = $objAndamentoPlanoTrabalhoDTO->getObjSituacaoAndamentoPlanoTrabalhoDTO();
        $strResultado .= '<td align="center"><a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip($objSituacaoAndamentoPlanoTrabalhoDTO->getStrDescricao()) . '><img src="' . $objSituacaoAndamentoPlanoTrabalhoDTO->getStrIcone() . '" class="imagemStatus" /></a></td>';
      } else {
        $strResultado .= '<td>&nbsp;</td>';
      }

      $strResultado .= "\n" . '<td align="center" valign="top">';
      $strResultado .= substr($objAndamentoPlanoTrabalhoDTO->getDthExecucao(), 0, 16);

      $strResultado .= '</td>';
      $strResultado .= "\n" . '<td align="center"  valign="top">';
      $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTO->getStrDescricaoUnidadeOrigem()) . '" title="' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTO->getStrDescricaoUnidadeOrigem()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTO->getStrSiglaUnidadeOrigem()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= "\n" . '<td align="center"  valign="top">';
      $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTO->getStrNomeUsuarioOrigem()) . '" title="' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTO->getStrNomeUsuarioOrigem()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTO->getStrSiglaUsuarioOrigem()) . '</a>';
      $strResultado .= '</td>';
      $strResultado .= "\n" . '<td valign="top">';

      if (!InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTO->getStrNomeTarefaPlanoTrabalho())) {
        $strResultado .= nl2br($objAndamentoPlanoTrabalhoDTO->getStrNomeTarefaPlanoTrabalho()) . $strQuebraLinha;
      } else {
        $strResultado .= '&nbsp;';
      }

      $strResultado .= '</td>';

      $strResultado .= '</tr>';
    }
    $strResultado .= '</table>';
  }

  $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
} catch (Exception $e) {
  PaginaSEI::getInstance()->processarExcecao($e);
}

//$objPlanoTrabalhoDTO = PlanoTrabalhoINT::montarLinkIdentificacao($_GET['id_plano_trabalho'], null, $strJavascriptPlanoTrabalho, $strHtmlPlanoTrabalho);

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

  #tblHistorico td{
  padding:.2em;
  }

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  infraEfeitoTabelas();
  }

<?=$strJavascriptPlanejamento?>
<?=$strJavascriptPlanoTrabalho?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmPlanoTrabalhoHistorico" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    echo $strHtmlPlanejamento;
    echo $strHtmlPlanoTrabalho;
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistrosAndamentoPlanoTrabalho);
    PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>