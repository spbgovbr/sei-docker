<?

try {
  require_once dirname(__FILE__) . '/../SEI.php';

  LimiteSEI::getInstance()->configurarNivel2();

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoConsultaProcessual::getInstance()->validarLink();

  if (SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_HASH')==null){
    die;
  }

  $strResultadoCabecalho = '';
  $strResultado = '';
  $numProtocolos = 0;
  $strResultadoAndamentos = '';
  $numAndamentos = 0;

  switch ($_GET['acao']) {
    case 'consulta_processual_processo':

      $strTitulo = 'Consulta Processual';

      $arrComandos = array();
      $arrComandos[] = '<button type="button"  name="btnVoltarListar" id="btnVoltarLista" value="Voltar à Lista" onclick="location.href=\'' . SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao=consulta_processual_resultado').PaginaConsultaProcessual::montarAncora($_GET['id_procedimento']) . '\';" class="infraButton">Voltar à Lista</button>';
      $arrComandos[] = '<button type="button"  name="btnVoltarPesquisa" id="btnVoltarPesquisa" value="Voltar à Pesquisa" onclick="location.href=\'' . SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao=consulta_processual_voltar') . '\';" class="infraButton">Voltar à Pesquisa</button>';
      $arrComandos[] = '<button type="button"  name="btnNovaPesquisa" id="btnNovaPesquisa" value="Nova Pesquisa" onclick="location.href=\'' . SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao=consulta_processual_pesquisar') . '\';" class="infraButton">Nova Pesquisa</button>';


      $objConsultaProcessualDTO = new ConsultaProcessualDTO();
      //$objConsultaProcessualDTO->setStrStaCriterioPesquisa(SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_CRITERIO_TIPO'));
      //$objConsultaProcessualDTO->setStrValorPesquisa(SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_CRITERIO_VALOR'));
      //$objConsultaProcessualDTO->setNumIdOrgaoUnidadeGeradora(SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_ORGAOS'));
      $objConsultaProcessualDTO->setDblIdProcedimento($_GET['id_procedimento']);

      if (isset($_GET['id_procedimento_anexado'])){
        $objConsultaProcessualDTO->setDblIdProtocoloConsulta($_GET['id_procedimento_anexado']);
      }

      $objConsultaProcessualRN = new ConsultaProcessualRN();
      $objProcedimentoDTO = $objConsultaProcessualRN->consultarProcessoConsultaProcessual($objConsultaProcessualDTO);

      if ($objProcedimentoDTO == null){
        throw new InfraException('Processo não encontrado.');
      }

      if ($objProcedimentoDTO->getStrSinEliminadoProtocolo()=='S') {
        $strTitulo = 'Processo Eliminado em ' . $objProcedimentoDTO->getDtaEliminacao();
      }

      $strResultadoCabecalho = ProcedimentoINT::montarTabelaAutuacao($objProcedimentoDTO);

      $arrObjRelProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();

      $objDocumentoRN = new DocumentoRN();

      $numProtocolos = InfraArray::contar($arrObjRelProtocoloProtocoloDTO);
      $numDocumentosCheck = 0;

      $arrPdf = array();

      if ($numProtocolos) {

        $strResultado .= '<table id="tblDocumentos" width="99.3%" class="infraTable" summary="Lista de Protocolos" >
  					  									<caption class="infraCaption" >' . PaginaConsultaProcessual::getInstance()->gerarCaptionTabela("Protocolos", $numProtocolos) . '</caption>' .
          "\n\n" . //auditoria
          '<tr>
                                  <th class="infraTh" width="1%" style="display:none;">' . PaginaConsultaProcessual::getInstance()->getThCheck('','Protocolos') . '</th>  					  									    
  					  										<th class="infraTh" width="15%">Processo / Documento</th>
  					  										<th class="infraTh" width="15%">Tipo</th>
  					  										<th class="infraTh" width="15%">Data</th>
  					  										<th class="infraTh" width="15%">Unidade</th>
  					  									</tr>' .
          "\n\n"; //auditoria

        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {

          if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO) {

            $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

            $strResultado .= '<tr class="infraTrClara">';
            $strResultado .= '<td style="display:none;">&nbsp;</td>';
            $strResultado .= '<td align="center"><a class="ancoraPadraoAzul" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="' . SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao=consulta_processual_documento&id_procedimento='.$_GET['id_procedimento'].'&id_documento=' . $objDocumentoDTO->getDblIdDocumento()) . '" target="_blank" alt="' . PaginaConsultaProcessual::tratarHTML($objDocumentoDTO->getStrNomeSerie()) . '" title="' . PaginaConsultaProcessual::tratarHTML($objDocumentoDTO->getStrNomeSerie()) . '">' . PaginaConsultaProcessual::tratarHTML($objDocumentoDTO->getStrProtocoloDocumentoFormatado()) . '</a></td>
													<td align="center">' . PaginaConsultaProcessual::tratarHTML($objDocumentoDTO->getStrNomeSerie() . ' ' . $objDocumentoDTO->getStrNumero()) . '</td>
													<td align="center">' . PaginaConsultaProcessual::tratarHTML($objDocumentoDTO->getDtaGeracaoProtocolo()) . '</td>
													<td align="center"><a alt="' . PaginaConsultaProcessual::tratarHTML($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()) . '" title="' . PaginaConsultaProcessual::tratarHTML($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()) . '" class="ancoraSigla">' . PaginaConsultaProcessual::tratarHTML($objDocumentoDTO->getStrSiglaUnidadeGeradoraProtocolo()) . '</a></td>
                           </tr>';

            //facilita visualização do texto auditado
            $strResultado .= "\n\n";

          } else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO) {

            $objProcedimentoDTOAnexado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

            $strResultado .= '<tr class="infraTrClara">';
            $strResultado .= '<td style="display:none;">&nbsp;</td>';
            $strResultado .= '<td align="center"><a class="ancoraPadraoAzul" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="' . SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao=consulta_processual_processo&id_procedimento='.$objProcedimentoDTO->getDblIdProcedimento().'&id_procedimento_anexado=' . $objProcedimentoDTOAnexado->getDblIdProcedimento()) . '" target="_blank" alt="' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()) . '" title="' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()) . '" >' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado()) . '</a></td>
  																	<td align="center">' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()) . '</td>
  																	<td align="center">' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTOAnexado->getDtaGeracaoProtocolo()) . '</td>
  																	<td align="center"><a alt="' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTOAnexado->getStrDescricaoUnidadeGeradoraProtocolo()) . '" title="' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTOAnexado->getStrDescricaoUnidadeGeradoraProtocolo()) . '" class="ancoraSigla">' . PaginaConsultaProcessual::tratarHTML($objProcedimentoDTOAnexado->getStrSiglaUnidadeGeradoraProtocolo()) . '</a></td>
                              </tr>';

            //facilita visualização do texto auditado
            $strResultado .= "\n\n";
          }
        }

        $strResultado .= '</table>';
      }

      $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
      $objProcedimentoHistoricoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_CONSULTA_PROCESSUAL);
      $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('N');

      PaginaConsultaProcessual::getInstance()->prepararPaginacao($objProcedimentoHistoricoDTO, 50, false, null, 'Andamentos');

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTORet = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);

      PaginaConsultaProcessual::getInstance()->processarPaginacao($objProcedimentoHistoricoDTO,'Andamentos');

      $arrObjAtividadeDTO = $objProcedimentoDTORet->getArrObjAtividadeDTO();

      $numAndamentos = InfraArray::contar($arrObjAtividadeDTO);

      if ($numAndamentos > 0) {

        $strResultadoAndamentos = '';
        $strResultadoAndamentos .= '<table id="tblHistorico" width="99.3%" class="infraTable my-2" summary="Histórico de Andamentos">' . "\n";
        $strResultadoAndamentos .= '<caption class="infraCaption">' . PaginaConsultaProcessual::getInstance()->gerarCaptionTabela('Andamentos', $numAndamentos, 'Lista de ', 'Andamentos') . '</caption>';
        $strResultadoAndamentos .= '<tr>';
        $strResultadoAndamentos .= '<th class="infraTh" width="12%">Data/Hora</th>';
        $strResultadoAndamentos .= '<th class="infraTh" width="12%">Unidade</th>';
        $strResultadoAndamentos .= '<th class="infraTh">Descrição</th>';
        $strResultadoAndamentos .= '</tr>' . "\n";

        $strQuebraLinha = '<span style="line-height:.5em"><br /></span>';

        foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
          $strResultadoAndamentos .= '<tr class="infraTrClara">';
          $strResultadoAndamentos .= "\n" . '<td align="center">';
          $strResultadoAndamentos .= PaginaConsultaProcessual::tratarHTML(substr($objAtividadeDTO->getDthAbertura(), 0, 16));
          $strResultadoAndamentos .= '</td>';
          $strResultadoAndamentos .= "\n" . '<td align="center">';
          $strResultadoAndamentos .= '<a alt="' . PaginaConsultaProcessual::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()) . '" title="' . PaginaConsultaProcessual::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()) . '" class="ancoraSigla">' . PaginaConsultaProcessual::tratarHTML($objAtividadeDTO->getStrSiglaUnidade()) . '</a>';
          $strResultadoAndamentos .= '</td>';
          $strResultadoAndamentos .= "\n";
          $strResultadoAndamentos .= "\n" . '<td>';
          if (!InfraString::isBolVazia($objAtividadeDTO->getStrNomeTarefa())) {
            $strResultadoAndamentos .= nl2br($objAtividadeDTO->getStrNomeTarefa()) . $strQuebraLinha;
          }
          $strResultadoAndamentos .= '</td>';
          $strResultadoAndamentos .= '</tr>' . "\n";
        }
        $strResultadoAndamentos .= '</table><br />';
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }
} catch (Exception $e) {
  PaginaConsultaProcessual::getInstance()->processarExcecao($e);
}
PaginaConsultaProcessual::getInstance()->montarDocType();
PaginaConsultaProcessual::getInstance()->abrirHtml();
PaginaConsultaProcessual::getInstance()->abrirHead();
PaginaConsultaProcessual::getInstance()->montarMeta();
PaginaConsultaProcessual::getInstance()->montarTitle(PaginaConsultaProcessual::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaConsultaProcessual::getInstance()->montarStyle();
CaptchaSEI::getInstance()->montarStyle();
PaginaConsultaProcessual::getInstance()->abrirStyle();
?>
  .infraCaption{text-align: left !important;}
<?
PaginaConsultaProcessual::getInstance()->fecharStyle();
PaginaConsultaProcessual::getInstance()->montarJavaScript();
CaptchaSEI::getInstance()->montarJavascript();
PaginaConsultaProcessual::getInstance()->fecharHead();
PaginaConsultaProcessual::getInstance()->abrirBody($strTitulo);
?>
  <form id="frmConsultaProcessualProcesso" method="post">
    <?
    PaginaConsultaProcessual::getInstance()->montarBarraComandosSuperior($arrComandos);

    echo $strResultadoCabecalho;

    if ($numProtocolos) {
      echo '<br>';
      PaginaConsultaProcessual::getInstance()->montarAreaTabela($strResultado, $numProtocolos);
    }

    if ($numAndamentos) {
      echo '<br>';
      PaginaConsultaProcessual::getInstance()->montarAreaTabela($strResultadoAndamentos, $numAndamentos, false, '', null, 'Andamentos');
    }
    ?>

  </form>
<?
PaginaConsultaProcessual::getInstance()->montarAreaDebug();
PaginaConsultaProcessual::getInstance()->fecharBody();
PaginaConsultaProcessual::getInstance()->fecharHtml();
