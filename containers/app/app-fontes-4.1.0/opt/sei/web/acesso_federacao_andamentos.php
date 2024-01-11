<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/05/2012 - criado por mga
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore','id_instalacao_federacao', 'id_orgao_federacao', 'id_procedimento_federacao', 'id_procedimento_federacao_anexado'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $arrComandos = array();
  $objVisualizarProcessoFederacaoDTORet = null;
  $objProcedimentoDTO = null;
  $strConjuntoAndamentos = '';
  $strResultadoCabecalho = '';
  $strResultadoAndamentos = '';

  switch($_GET['acao']){

    case 'andamentos_consulta_federacao':

      $strTitulo = 'Consulta de Andamentos de Processo do SEI Federação';

      $objVisualizarProcessoFederacaoDTO = new VisualizarProcessoFederacaoDTO();
      $objVisualizarProcessoFederacaoDTO->setStrIdInstalacaoFederacao($_GET['id_instalacao_federacao']);
      $objVisualizarProcessoFederacaoDTO->setStrIdProcedimentoFederacao($_GET['id_procedimento_federacao']);

      if (!isset($_GET['id_procedimento_federacao_anexado'])) {
        $strConjuntoAndamentos = $_GET['id_procedimento_federacao'];
      }else{
        $objVisualizarProcessoFederacaoDTO->setStrIdProcedimentoFederacaoAnexado($_GET['id_procedimento_federacao_anexado']);
        $strConjuntoAndamentos = $_GET['id_procedimento_federacao_anexado'];
      }

      $objVisualizarProcessoFederacaoDTO->setStrSinProtocolos('N');

      $objVisualizarProcessoFederacaoDTO->setStrSinAndamentos('S');
      $objAtividadeDTOPaginacao = new AtividadeDTO();
      if (!isset($_POST['hdnMaxAndamentos'])) {
        $objAtividadeDTOPaginacao->setNumPaginaAtual(0);
        $objAtividadeDTOPaginacao->setNumMaxRegistrosRetorno(null);
      }else {
        PaginaSEI::getInstance()->prepararPaginacao($objAtividadeDTOPaginacao, $_POST['hdnMaxAndamentos'], false, null, $strConjuntoAndamentos);
      }
      $objVisualizarProcessoFederacaoDTO->setNumPagAndamentos($objAtividadeDTOPaginacao->getNumPaginaAtual());
      $objVisualizarProcessoFederacaoDTO->setNumMaxAndamentos($objAtividadeDTOPaginacao->getNumMaxRegistrosRetorno());

      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      $objVisualizarProcessoFederacaoDTORet = $objAcessoFederacaoRN->visualizarProcesso($objVisualizarProcessoFederacaoDTO);

      if (!isset($_POST['hdnMaxAndamentos'])) {
        PaginaSEI::getInstance()->prepararPaginacao($objAtividadeDTOPaginacao, $objVisualizarProcessoFederacaoDTORet->getNumMaxAndamentos(), false, null, $strConjuntoAndamentos);
      }

      $objAtividadeDTOPaginacao->setNumRegistrosPaginaAtual($objVisualizarProcessoFederacaoDTORet->getNumRegAndamentos());
      $objAtividadeDTOPaginacao->setNumTotalRegistros($objVisualizarProcessoFederacaoDTORet->getNumTotAndamentos());
      PaginaSEI::getInstance()->processarPaginacao($objAtividadeDTOPaginacao, $strConjuntoAndamentos);

      break;

	  default:
	    throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objProcedimentoDTO = $objVisualizarProcessoFederacaoDTORet->getObjProcedimentoDTO();

  $strResultadoCabecalho = ProcedimentoINT::montarTabelaAutuacao($objProcedimentoDTO);

  $arrObjAtividadeDTO = $objProcedimentoDTO->getArrObjAtividadeDTO();

  $numRegistrosAtividades = $objVisualizarProcessoFederacaoDTORet->getNumTotAndamentos();

  if ($numRegistrosAtividades > 0) {

    $strResultadoAndamentos .= '<table id="tblHistorico" width="99.3%" class="infraTable" summary="Histórico de Andamentos">'."\n";
    $strResultadoAndamentos .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Andamentos', $numRegistrosAtividades, 'Lista de ', $strConjuntoAndamentos).'</caption>';
    $strResultadoAndamentos .= '<tr>';
    $strResultadoAndamentos .= '<th class="infraTh" width="1%" style="display:none">'.PaginaSEI::getInstance()->getThCheck('',$strConjuntoAndamentos).'</th>';
    $strResultadoAndamentos .= '<th class="infraTh" width="20%">Data/Hora</th>';
    $strResultadoAndamentos .= '<th class="infraTh" width="10%">Unidade</th>';
    $strResultadoAndamentos .= '<th class="infraTh" width="10%">Usuário</th>';
    $strResultadoAndamentos .= '<th class="infraTh">Descrição</th>';
    $strResultadoAndamentos .= '</tr>'."\n";

    $strQuebraLinha = '<span style="line-height:.5em"><br /></span>';

    foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {

      if ($objAtividadeDTO->getStrSinUltimaUnidadeHistorico() == 'S') {
        $strAbertas = 'class="andamentoAberto"';
      } else {
        $strAbertas = 'class="andamentoConcluido"';
      }

      $strResultadoAndamentos .= '<tr '.$strAbertas.'>';
      $strResultadoAndamentos .= "\n".'<td style="display:none">&nbsp;</td>';
      $strResultadoAndamentos .= "\n".'<td align="center">';
      $strResultadoAndamentos .= PaginaSEI::tratarHTML(substr($objAtividadeDTO->getDthAbertura(), 0, 16));
      $strResultadoAndamentos .= '</td>';

      $strResultadoAndamentos .= "\n".'<td align="center">';
      $strResultadoAndamentos .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUnidade()).'</a>';
      $strResultadoAndamentos .= '</td>';

      $strResultadoAndamentos .= "\n".'<td align="center">';
      $strResultadoAndamentos .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioOrigem()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioOrigem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUsuarioOrigem()).'</a>';
      $strResultadoAndamentos .= '</td>';

      $strResultadoAndamentos .= "\n";
      $strResultadoAndamentos .= "\n".'<td>';

      if (!InfraString::isBolVazia($objAtividadeDTO->getStrNomeTarefa())) {
        $strResultadoAndamentos .= nl2br($objAtividadeDTO->getStrNomeTarefa()).$strQuebraLinha;
      }

      $strResultadoAndamentos .= '</td>';

      $strResultadoAndamentos .= '</tr>'."\n";
    }
    $strResultadoAndamentos .= '</table>';
  }

  $arrComandos[] = '<button type="button" accesskey="P" name="btnProtocolos" value="Protocolos" onclick="visualizarProtocolos()" class="infraButton"><span class="infraTeclaAtalho">P</span>rotocolos</button>';


}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

  .andamentoAberto {
  background-color:#ffff66;
  }

  .andamentoConcluido {
  background-color:white;
  }


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

  //<script>

  function inicializar(){
    infraEfeitoTabelas();
  }


  function OnSubmitForm(){
    return true;
  }

  function visualizarProtocolos(){
    if (typeof(parent.exibirAguarde) == 'function') {
      parent.exibirAguarde("ifrVisualizacao");
    }else{
      infraExibirAviso();
    }
    location.href='<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=processo_consulta_federacao&acao_origem='.$_GET['acao'])?>';
  }

  //</script>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcessoFederacaoAndamentos" method="post" onsubmit="return OnSubmitForm();">
<?
  if ($strResultadoCabecalho!='') {
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    echo $strResultadoCabecalho;
    PaginaSEI::getInstance()->montarAreaTabela($strResultadoAndamentos, $numRegistrosAtividades, false, '', null, $strConjuntoAndamentos);
  }
?>
  <input type="hidden" id="hdnMaxAndamentos" name="hdnMaxAndamentos" value="<?=$objVisualizarProcessoFederacaoDTORet->getNumMaxAndamentos()?>" />
</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>