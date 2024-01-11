<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 **/

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

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array("id_edital_eliminacao_conteudo"));

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  switch($_GET['acao']){

    case 'edital_eliminacao_arquivados_listar':
      $strTitulo = "Arquivamentos Remanescentes do Processo";

      break;
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

  $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
  $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacaoConteudo($_GET['id_edital_eliminacao_conteudo']);
  $objEditalEliminacaoConteudoDTO->retDblIdProcedimentoAvaliacaoDocumental();
  $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
  $objEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->consultar($objEditalEliminacaoConteudoDTO);

  $arrIdProcedimento = array();
  $arrIdProcedimento[] = $objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental();

  $objProcedimentoDTO = new ProcedimentoDTO();
  $objProcedimentoDTO->setDblIdProcedimento($objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental());
  $arrObjProcedimentosAnexadosDTO = (new ProcedimentoRN())->listarProcessosAnexados($objProcedimentoDTO);
  if (InfraArray::contar($arrObjProcedimentosAnexadosDTO) > 0) {
    $arrIdProcedimento = array_merge($arrIdProcedimento, InfraArray::converterArrInfraDTO($arrObjProcedimentosAnexadosDTO, "IdProcedimento"));
  }

  $objArquivamentoDTO = new ArquivamentoDTO();
  $objArquivamentoDTO->retDblIdProtocolo();
  $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
  $objArquivamentoDTO->retStrNomeSerieDocumento();
  $objArquivamentoDTO->retNumIdLocalizador();
  $objArquivamentoDTO->retStrStaEstadoLocalizador();
  $objArquivamentoDTO->retStrNomeTipoLocalizador();
  $objArquivamentoDTO->retStrSiglaTipoLocalizador();
  $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
  $objArquivamentoDTO->retNumIdUnidadeLocalizador();
  $objArquivamentoDTO->retStrSiglaUnidadeLocalizador();
  $objArquivamentoDTO->retStrDescricaoUnidadeLocalizador();
  $objArquivamentoDTO->retStrStaArquivamento();

  $objArquivamentoDTO->setDblIdProcedimentoDocumento($arrIdProcedimento, InfraDTO::$OPER_IN);
  $objArquivamentoDTO->setStrStaArquivamento(array(ArquivamentoRN::$TA_RECEBIDO, ArquivamentoRN::$TA_ARQUIVADO, ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO), InfraDTO::$OPER_IN);

  //ordenamento
  $objArquivamentoDTO->setOrdStrSiglaTipoLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objArquivamentoDTO->setOrdNumSeqLocalizadorLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
  //paginacao
  PaginaSEI::getInstance()->prepararPaginacao($objArquivamentoDTO,500);
  $objArquivamentoRN = new ArquivamentoRN();
  $arrObjArquivamentoDTO = $objArquivamentoRN->listar($objArquivamentoDTO);
  //paginacao
  PaginaSEI::getInstance()->processarPaginacao($objArquivamentoDTO);
  //a tabela, links e acoes nao tem muitas especificidades
  $numRegistros = count($arrObjArquivamentoDTO);
  if ($numRegistros > 0){

    $strResultado = '';

    $strCaptionTabela = 'Documentos';
    $strSumarioTabela = 'Documentos Arquivados Remanescentes do Processo';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" >Documento</th>'."\n";
    $strResultado .= '<th class="infraTh" >Tipo</th>'."\n";
    $strResultado .= '<th class="infraTh" >Estado</th>'."\n";
    $strResultado .= '<th class="infraTh" >Localizador</th>'."\n";
    $strResultado .= '<th class="infraTh" >Unidade</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $arrObjTipoArquivamentoSituacaoDTO = InfraArray::indexarArrInfraDTO($objArquivamentoRN->listarValoresTipoArquivamentoSituacao(),'StaArquivamento');

    $n = 0;
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento());
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeSerieDocumento());
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjTipoArquivamentoSituacaoDTO[$arrObjArquivamentoDTO[$i]->getStrStaArquivamento()]->getStrDescricao());
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';

      if ($arrObjArquivamentoDTO[$i]->getNumIdLocalizador()!=null) {
        if ($arrObjArquivamentoDTO[$i]->getNumIdUnidadeLocalizador()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() && SessaoSEI::getInstance()->verificarPermissao('arquivamento_eliminacao_listar')) {
          $strCorLocalizador = '';
          if ($arrObjArquivamentoDTO[$i]->getStrStaEstadoLocalizador() == LocalizadorRN::$EA_ABERTO) {
            $strCorLocalizador = 'style="color:green;"';
          } else {
            $strCorLocalizador = 'style="color:red;"';
          }
          $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_eliminacao_listar&acao_origem='.$_GET['acao'].'&id_localizador='.$arrObjArquivamentoDTO[$i]->getNumIdLocalizador().PaginaSEI::montarAncora($arrObjArquivamentoDTO[$i]->getDblIdProtocolo())).'" target="_blank" class="linkFuncionalidade" '.$strCorLocalizador.' tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.$arrObjArquivamentoDTO[$i]->getStrNomeTipoLocalizador().'">'.LocalizadorINT::montarIdentificacaoRI1132($arrObjArquivamentoDTO[$i]->getStrSiglaTipoLocalizador(), $arrObjArquivamentoDTO[$i]->getNumSeqLocalizadorLocalizador()).'</a>';
        }else{
          $strResultado .= LocalizadorINT::montarIdentificacaoRI1132($arrObjArquivamentoDTO[$i]->getStrSiglaTipoLocalizador(), $arrObjArquivamentoDTO[$i]->getNumSeqLocalizadorLocalizador());
        }
      }else{
        $strResultado .= '&nbsp;';
      }
      $strResultado .= '</td>';

      $strResultado .= '<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrDescricaoUnidadeLocalizador()).'" title="'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrDescricaoUnidadeLocalizador()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrSiglaUnidadeLocalizador()).'</a>';
      $strResultado .= '</td>';


      $strResultado .= '</tr>';

    }
    $strResultado .= '</table>';
  }

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


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  infraEfeitoTabelas();

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

?>

  <form id="frmSelecao" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">

    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
    PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

  </form>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>