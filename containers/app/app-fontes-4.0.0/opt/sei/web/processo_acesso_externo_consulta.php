<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/06/2010 - criado por fazenda_db
 *
 *
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

  SessaoSEIExterna::getInstance()->validarLink();

  PaginaSEIExterna::getInstance()->setTipoPagina(PaginaSEIExterna::$TIPO_PAGINA_SEM_MENU);

  global $SEI_MODULOS;

  //SessaoSEIExterna::getInstance()->validarPermissao($_GET['acao']);

  $strParametro = '';

  $objAcessoExternoDTO = new AcessoExternoDTO();

  if (isset($_GET['id_acesso_externo'])) {
    $objAcessoExternoDTO->setNumIdAcessoExterno($_GET['id_acesso_externo']);
    $strParametro .= 'id_acesso_externo='.$_GET['id_acesso_externo'];
  }

  if (isset($_GET['id_procedimento'])){
    $objAcessoExternoDTO->setDblIdProcedimento($_GET['id_procedimento']);
    if ($strParametro!=''){
      $strParametro .= '&';
    }
    $strParametro .= 'id_procedimento='.$_GET['id_procedimento'];
  }

  if (isset($_GET['id_procedimento_anexado'])){
    $objAcessoExternoDTO->setDblIdProtocoloConsulta($_GET['id_procedimento_anexado']);
  }

  $objAcessoExternoRN = new AcessoExternoRN();
  $objAcessoExternoDTO = $objAcessoExternoRN->consultarProcessoAcessoExterno($objAcessoExternoDTO);

  $strTitulo = 'Acesso Externo';

  if ($objAcessoExternoDTO->getStrSinParcial()=='S') {
    $strTitulo .= ' com Disponibilização Parcial de Documentos';
  }else{
    $strTitulo .= ' com Acompanhamento Integral do Processo';
  }

  $objProcedimentoDTO = $objAcessoExternoDTO->getObjProcedimentoDTO();

  //Carregar interessados no processo
  $objInteressadosParticipanteDTO = new ParticipanteDTO();
  $objInteressadosParticipanteDTO->retStrNomeContato();
  $objInteressadosParticipanteDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
  $objInteressadosParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);

  $objInteressadosParticipanteRN = new ParticipanteRN();

  $objInteressadosParticipanteDTO = $objInteressadosParticipanteRN->listarRN0189($objInteressadosParticipanteDTO);

  if (count($objInteressadosParticipanteDTO)==0){
    $strInteressados = '&nbsp;';
  }else{
    $strInteressados = '';
    foreach($objInteressadosParticipanteDTO as $objInteressadoParticipanteDTO){
      $strInteressados .= PaginaSEI::tratarHTML($objInteressadoParticipanteDTO->getStrNomeContato())."<br /> ";
    }
  }

  $strResultadoCabecalho = '';
  $strResultadoCabecalho .= '<table id="tblCabecalho" width="99.3%" class="infraTable" summary="Cabeçalho de Processo" >'."\n";
  $strResultadoCabecalho .= '<tr><th class="infraTh" colspan="2">Autuação</th></tr>'."\n";
  $strResultadoCabecalho .= '<tr class="infraTrClara"><td width="20%">Processo:</td><td>'.PaginaSEI::tratarHTML($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado()).'</td></tr>'."\n";
  $strResultadoCabecalho .= '<tr class="infraTrClara"><td width="20%">Tipo:</td><td>'.PaginaSEIExterna::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()).'</td></tr>'."\n";
  $strResultadoCabecalho .= '<tr class="infraTrClara"><td width="20%">Data de Geração:</td><td>'.PaginaSEI::tratarHTML($objProcedimentoDTO->getDtaGeracaoProtocolo()).'</td></tr>'."\n";
  $strResultadoCabecalho .= '<tr class="infraTrClara"><td width="20%">Interessados:</td><td> '.$strInteressados.'</td></tr>'."\n";
  $strResultadoCabecalho .= '</table>'."\n";


  $arrObjRelProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();

  $objDocumentoRN = new DocumentoRN();

  $numProtocolos = InfraArray::contar($arrObjRelProtocoloProtocoloDTO);
  $numDocumentosCheck = 0;

  $arrPdf = array();

  if ($numProtocolos) {
    $strCssMostrarAcoes = '.colunaAcoes {display:none;}' . "\n";

    $strResultado = '<table id="tblDocumentos" width="99.3%" class="infraTable" summary="Lista de Protocolos" >
  					  									<caption class="infraCaption" >' . PaginaSEIExterna::getInstance()->gerarCaptionTabela("Protocolos", $numProtocolos) . '</caption>' .
        "\n\n" . //auditoria
        '<tr>
                                  <th class="infraTh" width="1%">' . PaginaSEIExterna::getInstance()->getThCheck() . '</th>  					  									    
  					  										<th class="infraTh" width="15%">Processo / Documento</th>
  					  										<th class="infraTh" width="15%">Tipo</th>
  					  										<th class="infraTh" width="15%">Data</th>
  					  										<th class="infraTh" width="15%">Unidade</th>
  					  										<th class="infraTh colunaAcoes" width="5%">Ações</th>
  					  									</tr>' .
        "\n\n"; //auditoria

    //SessaoSEIExterna::getInstance()->configurarAcessoExterno($_GET['id_acesso_externo']);


    $arrObjDocumentoAPIAutorizado = array();
    $arrObjProcedimentoAPIAutorizado = array();

    $arrObjDocumentoAPINegado = array();
    $arrObjProcedimentoAPINegado = array();

    foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {

      if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO) {

        $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objDocumentoAPI->setNumeroProtocolo($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $objDocumentoAPI->setIdSerie($objDocumentoDTO->getNumIdSerie());
        $objDocumentoAPI->setIdUnidadeGeradora($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo());
        $objDocumentoAPI->setTipo($objDocumentoDTO->getStrStaProtocoloProtocolo());
        $objDocumentoAPI->setSinAssinado($objDocumentoDTO->getStrSinAssinado());
        $objDocumentoAPI->setSinPublicado($objDocumentoDTO->getStrSinPublicado());
        $objDocumentoAPI->setNivelAcesso($objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo());

        if ($objRelProtocoloProtocoloDTO->getStrSinAcessoBasico()=='S') {
          $arrObjDocumentoAPIAutorizado[] = $objDocumentoAPI;
        }else{
          $arrObjDocumentoAPINegado[] = $objDocumentoAPI;
        }

      } else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO) {

        $objProcedimentoDTOAnexado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTOAnexado->getDblIdProcedimento());
        $objProcedimentoAPI->setNumeroProtocolo($objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado());
        $objProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTOAnexado->getNumIdTipoProcedimento());
        $objProcedimentoAPI->setNomeTipoProcedimento($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento());
        $objProcedimentoAPI->setNivelAcesso($objProcedimentoDTOAnexado->getStrStaNivelAcessoGlobalProtocolo());

        if ($objRelProtocoloProtocoloDTO->getStrSinAcessoBasico()=='S') {
          $arrObjProcedimentoAPIAutorizado[] = $objProcedimentoAPI;
        }else{
          $arrObjProcedimentoAPINegado[] = $objProcedimentoAPI;
        }
      }
    }

    $arrIntegracaoAcoesProcedimentos = array();
    $arrIntegracaoAcoesDocumentos = array();

    foreach ($SEI_MODULOS as $seiModulo) {

      if (InfraArray::contar($arrObjDocumentoAPIAutorizado)) {
        if (($arr = $seiModulo->executar('montarAcaoDocumentoAcessoExternoAutorizado', $arrObjDocumentoAPIAutorizado))!=null){
          foreach($arr as $key => $arr) {
            if (!isset($arrIntegracaoAcoesDocumentos[$key])) {
              $arrIntegracaoAcoesDocumentos[$key] = $arr;
            }else {
              $arrIntegracaoAcoesDocumentos[$key] = array_merge($arrIntegracaoAcoesDocumentos[$key], $arr);
            }
          }
        }
      }

      if (InfraArray::contar($arrObjDocumentoAPINegado)) {
        if (($arr = $seiModulo->executar('montarAcaoDocumentoAcessoExternoNegado', $arrObjDocumentoAPINegado))!=null){
          foreach($arr as $key => $arr) {
            if (!isset($arrIntegracaoAcoesDocumentos[$key])) {
              $arrIntegracaoAcoesDocumentos[$key] = $arr;
            }else {
              $arrIntegracaoAcoesDocumentos[$key] = array_merge($arrIntegracaoAcoesDocumentos[$key], $arr);
            }
          }
        }
      }


      if (InfraArray::contar($arrObjProcedimentoAPIAutorizado)) {
        if (($arr = $seiModulo->executar('montarAcaoProcessoAnexadoAcessoExternoAutorizado', $arrObjProcedimentoAPIAutorizado))!=null){
          foreach($arr as $key => $arr) {
            if (!isset($arrIntegracaoAcoesProcedimentos[$key])) {
              $arrIntegracaoAcoesProcedimentos[$key] = $arr;
            }else {
              $arrIntegracaoAcoesProcedimentos[$key] = array_merge($arrIntegracaoAcoesProcedimentos[$key], $arr);
            }
          }
        }
      }

      if (InfraArray::contar($arrObjProcedimentoAPINegado)) {
        if (($arr = $seiModulo->executar('montarAcaoProcessoAnexadoAcessoExternoNegado', $arrObjProcedimentoAPINegado))!=null){
          foreach($arr as $key => $arr) {
            if (!isset($arrIntegracaoAcoesProcedimentos[$key])) {
              $arrIntegracaoAcoesProcedimentos[$key] = $arr;
            }else {
              $arrIntegracaoAcoesProcedimentos[$key] = array_merge($arrIntegracaoAcoesProcedimentos[$key], $arr);
            }
          }
        }
      }

    }

    $arrAcessoDocumento = array();

    foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {

      if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO) {

        $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

        $strLinkDocumento = '<a href="javascript:void(0);"';
        if ($objRelProtocoloProtocoloDTO->getStrSinAcessoBasico()=='S'){
          $strLinkDocumento .= ' class="ancoraPadraoAzul" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);window.open(\'' . SessaoSEIExterna::getInstance()->assinarLink('documento_consulta_externa.php?'.$strParametro.'&id_documento=' . $objDocumentoDTO->getDblIdDocumento()) . '\');"';
          $arrAcessoDocumento[] = $objDocumentoDTO->getDblIdDocumento();
        }else if ($objDocumentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_DOCUMENTO_CANCELADO) {
          $strLinkDocumento .= ' class="ancoraPadraoPreta" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);alert(\'Documento cancelado.\')" style="text-decoration: line-through"';
        }else{
          $strLinkDocumento .= ' class="ancoraPadraoPreta" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);alert(\'Sem acesso ao documento.\')"';
        }
        $strLinkDocumento .= ' alt="' . PaginaSEIExterna::tratarHTML($objDocumentoDTO->getStrNomeSerie()) . '" title="' . PaginaSEIExterna::tratarHTML($objDocumentoDTO->getStrNomeSerie()) . '">' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrProtocoloDocumentoFormatado()) . '</a>';


        $strLinkAssinar = '';
        $bolParaAssinatura = false;
        if ($objDocumentoDTO->getDblIdDocumento() == $objAcessoExternoDTO->getDblIdDocumento() && $objDocumentoDTO->getStrStaEstadoProtocolo() != ProtocoloRN::$TE_DOCUMENTO_CANCELADO) {

          $bolParaAssinatura = true;

          $arrAcessoDocumento[] = $objDocumentoDTO->getDblIdDocumento();

          $bolFlagAssinou = false;
          $arrObjAssinaturaDTO = $objDocumentoDTO->getArrObjAssinaturaDTO();
          foreach ($arrObjAssinaturaDTO as $objAssinaturaDTO) {
            if ($objAssinaturaDTO->getNumIdUsuario() == SessaoSEIExterna::getInstance()->getNumIdUsuarioExterno()) {
              $bolFlagAssinou = true;
              break;
            }
          }

          if (!$bolFlagAssinou) {
            $strLinkAssinar = '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);infraAbrirJanela(\'' . SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?'.$strParametro.'&acao=usuario_externo_assinar&id_documento=' . $objDocumentoDTO->getDblIdDocumento()) . '\',\'janelaAssinaturaExterna\',450,250,\'location=0,status=1,resizable=1,scrollbars=1\');" tabindex="' . PaginaSEIExterna::getInstance()->getProxTabTabela() . '"><img src="'.Icone::DOCUMENTO_ASSINAR.'" title="Assinar Documento" alt="Assinar Documento" class="infraImg" /></a>&nbsp;';
            $strCssMostrarAcoes = '';
          }
        }

        $strResultado .= '<tr class="infraTrClara">';

        $bolCheck = false;

        if (in_array($objDocumentoDTO->getDblIdDocumento(), $arrAcessoDocumento)) {
          if ($objDocumentoDTO->getStrSinPdf()=='S' || $bolParaAssinatura) {
            $bolCheck = true;
          } else {
            $arrPdf[] = $objDocumentoDTO->getDblIdDocumento();
          }

          if ($bolCheck || $objDocumentoDTO->getStrSinZip()=='S') {
            $bolCheck = true;
          }
        }

        if ($bolCheck) {
          $strResultado .= '<td align="center">' . PaginaSEIExterna::getInstance()->getTrCheck($numDocumentosCheck++, $objDocumentoDTO->getDblIdDocumento(), $objDocumentoDTO->getStrNomeSerie()) . '</td>';
        } else {
          $strResultado .= '<td>&nbsp;</td>';
        }

        $strResultado .= '<td align="center">'.$strLinkDocumento.'</td>
													<td align="center">' . PaginaSEIExterna::tratarHTML($objDocumentoDTO->getStrNomeSerie() . ' ' . $objDocumentoDTO->getStrNumero()) . '</td>
													<td align="center">' . PaginaSEI::tratarHTML($objDocumentoDTO->getDtaGeracaoProtocolo()) . '</td>
													<td align="center"><a alt="' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()) . '" title="' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrSiglaUnidadeGeradoraProtocolo()) . '</a></td>
													<td align="center" class="colunaAcoes">&nbsp;';

        $strResultado .= $strLinkAssinar;

        if (is_array($arrIntegracaoAcoesDocumentos) && isset($arrIntegracaoAcoesDocumentos[$objDocumentoDTO->getDblIdDocumento()])) {

          foreach ($arrIntegracaoAcoesDocumentos[$objDocumentoDTO->getDblIdDocumento()] as $strIconeIntegracao) {
            $strResultado .= '&nbsp;' . $strIconeIntegracao;
          }

          $strCssMostrarAcoes = '';
        }

        $strResultado .= '</td></tr>';

        //facilita visualização do texto auditado
        $strResultado .= "\n\n";

      } else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO) {

        $objProcedimentoDTOAnexado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();


        $strLinkProcessoAnexado = '<a href="javascript:void(0);"';
        if ($objRelProtocoloProtocoloDTO->getStrSinAcessoBasico()=='S'){
          $strLinkProcessoAnexado .= ' class="ancoraPadraoAzul" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);window.open(\'' . SessaoSEIExterna::getInstance()->assinarLink('processo_acesso_externo_consulta.php?'.$strParametro.'&id_procedimento_anexado=' . $objProcedimentoDTOAnexado->getDblIdProcedimento()) . '\');"';
        }else{
          $strLinkProcessoAnexado .= ' class="ancoraPadraoPreta" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);alert(\'Sem acesso ao processo anexado.\')"';
        }
        $strLinkProcessoAnexado .=  ' alt="' . PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()) . '" title="' . PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()) . '" >' . PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado()) . '</a>';


        $strResultado .= '<tr class="infraTrClara">';
        $strResultado .= '<td>&nbsp;</td>';
        $strResultado .= '<td align="center">'.$strLinkProcessoAnexado.'</td>
  																	<td align="center">' . PaginaSEIExterna::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()) . '</td>
  																	<td align="center">' . PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getDtaGeracaoProtocolo()) . '</td>
  																	<td align="center"><a alt="' . PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrDescricaoUnidadeGeradoraProtocolo()) . '" title="' . PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrDescricaoUnidadeGeradoraProtocolo()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrSiglaUnidadeGeradoraProtocolo()) . '</a></td>
  																	<td align="center" class="colunaAcoes">&nbsp;';

        if (is_array($arrIntegracaoAcoesProcedimentos) && isset($arrIntegracaoAcoesProcedimentos[$objProcedimentoDTOAnexado->getDblIdProcedimento()])) {

          foreach ($arrIntegracaoAcoesProcedimentos[$objProcedimentoDTOAnexado->getDblIdProcedimento()] as $strIconeIntegracao) {
            $strResultado .= '&nbsp;' . $strIconeIntegracao;
          }

          $strCssMostrarAcoes = '';
        }

        $strResultado .= '</td></tr>';

        //facilita visualização do texto auditado
        $strResultado .= "\n\n";
      }
    }

    $strResultado .= '</table>';

  }

  $arrComandos = array();


  $objProcedimentoAPI = new ProcedimentoAPI();
  $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
  $objProcedimentoAPI->setNumeroProtocolo($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
  $objProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
  $objProcedimentoAPI->setNomeTipoProcedimento($objProcedimentoDTO->getStrNomeTipoProcedimento());
  $objProcedimentoAPI->setNivelAcesso($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo());

  //montagem de lista de botões a serem exibidos no canto superior direito na tela de "Acesso Externo Autorizado" e variavel global, declarada e inicializada na classe SEI.php
  foreach ($SEI_MODULOS as $seiModulo) {
    if (($arrIntegracao = $seiModulo->executar('montarBotaoAcessoExternoAutorizado', $objProcedimentoAPI)) != null) {
      foreach ($arrIntegracao as $strIntegracao) {
        $arrComandos[] = $strIntegracao;
      }
    }
  }

  if ($numDocumentosCheck > 0){
    $arrComandos[] = '<button type="button" accesskey="P" name="btnGerarPdf" value="Gerar PDF" onclick="gerarPdf();" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>DF</button>';
    $arrComandos[] = '<button type="button" accesskey="Z" name="btnGerarZip" value="Gerar ZIP" onclick="gerarZip();" class="infraButton">Gerar <span class="infraTeclaAtalho">Z</span>IP</button>';
  }

  //Carregar histórico
  $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
  $objProcedimentoHistoricoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
  $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_EXTERNO);
  $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('N');

  $objProcedimentoRN = new ProcedimentoRN();
  $objProcedimentoDTORet = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
  $arrObjAtividadeDTO = $objProcedimentoDTORet->getArrObjAtividadeDTO();

  $numRegistrosAtividades = InfraArray::contar($arrObjAtividadeDTO);

  if ($numRegistrosAtividades > 0) {

    $strResultadoAndamentos = '';

    $strResultadoAndamentos .= '<table id="tblHistorico" width="99.3%" class="infraTable" summary="Histórico de Andamentos">' . "\n";
    $strResultadoAndamentos .= '<caption class="infraCaption">' . PaginaSEIExterna::getInstance()->gerarCaptionTabela('Andamentos', $numRegistrosAtividades) . '</caption>';
    $strResultadoAndamentos .= '<tr>';
    $strResultadoAndamentos .= '<th class="infraTh" width="20%">Data/Hora</th>';
    $strResultadoAndamentos .= '<th class="infraTh" width="10%">Unidade</th>';
    $strResultadoAndamentos .= '<th class="infraTh">Descrição</th>';
    $strResultadoAndamentos .= '</tr>' . "\n";

    $strQuebraLinha = '<span style="line-height:.5em"><br /></span>';


    foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {

      //InfraDebug::getInstance()->gravar($objAtividadeDTO->getNumIdAtividade());

      $strResultadoAndamentos .= "\n\n" . '<!-- ' . $objAtividadeDTO->getNumIdAtividade() . ' -->' . "\n";

      if ($objAtividadeDTO->getStrSinUltimaUnidadeHistorico() == 'S') {
        $strAbertas = 'class="andamentoAberto"';
      } else {
        $strAbertas = 'class="andamentoConcluido"';
      }

      $strResultadoAndamentos .= '<tr ' . $strAbertas . '>';
      $strResultadoAndamentos .= "\n" . '<td align="center">';
      $strResultadoAndamentos .= PaginaSEI::tratarHTML(substr($objAtividadeDTO->getDthAbertura(), 0, 16));
      $strResultadoAndamentos .= '</td>';

      $strResultadoAndamentos .= "\n" . '<td align="center">';
      $strResultadoAndamentos .= '<a alt="' . PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()) . '" title="' . PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUnidade()) . '</a>';
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

  $objProcedimentoDTOAuditoria = new ProcedimentoDTO();
  $objProcedimentoDTOAuditoria->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
  $objProcedimentoDTOAuditoria->setStrProtocoloProcedimentoFormatado($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());

  AuditoriaSEI::getInstance()->auditar('processo_consulta_externa', __FILE__, $objProcedimentoDTOAuditoria);

  if ($_POST['hdnFlagGerar']=='1' || $_POST['hdnFlagGerar']=='2'){

    $objDocumentoRN = new DocumentoRN();

    $arrIdDocumentosSelecionados = PaginaSEIExterna::getInstance()->getArrStrItensSelecionados();

    foreach($arrIdDocumentosSelecionados as $dblIdDocumentoSelecionado){
      if (!in_array($dblIdDocumentoSelecionado,$arrAcessoDocumento)){
        throw new InfraException('Usuário sem acesso ao documento para geração do '.(($_POST['hdnFlagGerar']=='1')?'PDF':'ZIP').'.');
      }
    }

    $arrObjDocumentoDTOSelecionados = InfraArray::gerarArrInfraDTO('DocumentoDTO', 'IdDocumento', $arrIdDocumentosSelecionados);

    $strExtensao = '';
    if ($_POST['hdnFlagGerar']=='1') {
      $objAnexoDTO = $objDocumentoRN->gerarPdf($arrObjDocumentoDTOSelecionados);
      $strExtensao = '.pdf';
    }else{
      $objAnexoDTO = $objDocumentoRN->gerarZip($arrObjDocumentoDTOSelecionados);
      $strExtensao = '.zip';
    }

    SeiINT::download(null, null, $objAnexoDTO->getStrNome(), 'SEI-'.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().$strExtensao, 'attachment');

    die;

  }

}catch(Exception $e){
  PaginaSEIExterna::getInstance()->processarExcecao($e);
}

PaginaSEIExterna::getInstance()->montarDocType();
  PaginaSEIExterna::getInstance()->abrirHtml();
  PaginaSEIExterna::getInstance()->abrirHead();
  PaginaSEIExterna::getInstance()->montarMeta();
  PaginaSEIExterna::getInstance()->montarTitle(PaginaSEIExterna::getInstance()->getStrNomeSistema().' - '.$strTitulo);
  PaginaSEIExterna::getInstance()->montarStyle();
  PaginaSEIExterna::getInstance()->abrirStyle();
  echo $strCssMostrarAcoes;
  ?>

  div.infraBarraSistemaE {width:90%}
  div.infraBarraSistemaD {width:5%}
  div.infraBarraComandos {width:99%}

  table caption {
  text-align:left !important;
  font-size: 1.2em;
  font-weight:bold;
  }

  .andamentoAberto {
  background-color:white;
  }

  .andamentoConcluido {
  background-color:white;
  }


  #tblCabecalho{margin-top:1;}
  #tblDocumentos {margin-top:1.5em;}
  #tblHistorico {margin-top:1.5em;}

<?
PaginaSEIExterna::getInstance()->fecharStyle();
PaginaSEIExterna::getInstance()->montarJavaScript();
PaginaSEIExterna::getInstance()->abrirJavaScript();
?>

  //<script>

  function inicializar(){
    infraEfeitoTabelas();
  }

  function gerarPdf() {

    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum documento selecionado.');
      return;
    }

    var pdf = document.getElementById('hdnPdf').value;

    var erro = 0;

    if (pdf!='') {

      selecionados = document.getElementById('hdnInfraItensSelecionados').value;

      if (selecionados!='') {

        pdf = pdf.split(',');
        selecionados = selecionados.split(',');

        for (var j = 0; j<<?=$numDocumentosCheck?>; j++) {

          box = document.getElementById('chkInfraItem'+j);

          if (!box.checked){

            infraFormatarTrDesmarcada(box.parentNode.parentNode);

          }else {

            for (var i = 0; i<pdf.length; i++) {
              if (pdf[i]==box.value) {
                box.checked = false;
                infraFormatarTrAcessada(box.parentNode.parentNode);
                erro += 1;
              }
            }
          }
        }
      }
    }

    if (erro) {

      var msg = '';
      if (erro==1){
        msg = 'Não é possível gerar o PDF para o documento destacado.';
      }else{
        msg = 'Não é possível gerar o PDF para os documentos destacados.';
      }

      msg += '\n\nDeseja continuar?';

      if (!confirm(msg)){
        return;
      }
    }

    infraSelecionarItens();

    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum documento selecionado.');
      return;
    }

    document.getElementById('hdnFlagGerar').value = '1';
    document.getElementById('frmProcessoAcessoExternoConsulta').target = '_blank';
    document.getElementById('frmProcessoAcessoExternoConsulta').submit();
  }

  function gerarZip() {

    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum documento selecionado.');
      return;
    }

    document.getElementById('hdnFlagGerar').value = '2';
    document.getElementById('frmProcessoAcessoExternoConsulta').target = '_blank';
    document.getElementById('frmProcessoAcessoExternoConsulta').submit();
  }

  //</script>

<?
PaginaSEIExterna::getInstance()->fecharJavaScript();
PaginaSEIExterna::getInstance()->fecharHead();
PaginaSEIExterna::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmProcessoAcessoExternoConsulta" method="post" onsubmit="return OnSubmitForm();">
    <?
    PaginaSEIExterna::getInstance()->montarBarraComandosSuperior($arrComandos);
    echo $strResultadoCabecalho;
    PaginaSEIExterna::getInstance()->montarAreaTabela($strResultado,$numProtocolos);
    echo $strResultadoAndamentos;
    ?>

    <input type="hidden" id="hdnPdf" name="hdnPdf" value="<?=implode(',',$arrPdf)?>" />
    <input type="hidden" id="hdnFlagGerar" name="hdnFlagGerar" value="0" />
  </form>
<?
PaginaSEIExterna::getInstance()->montarAreaDebug();
PaginaSEIExterna::getInstance()->fecharBody();
PaginaSEIExterna::getInstance()->fecharHtml();
?>