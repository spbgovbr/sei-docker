<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 31/01/2008 - criado por marcio_db
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../SEI.php';

class ProtocoloINT extends InfraINT
{
  public static function buscarProtocoloFormatadoRI1010($dblIdProtocolo)
  {

    $ret = '';

    if ($dblIdProtocolo != null) {
      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retStrProtocoloFormatado();
      $objProtocoloDTO->setDblIdProtocolo($dblIdProtocolo);

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

      $ret = $objProtocoloDTO->getStrProtocoloFormatado();

    }

    return $ret;
  }

  public static function pesquisarLinkEditor($dblIdProcedimento, $dlbIdDocumento, $strIdProtocolo)
  {

    $objInfraException = new InfraException();

    if (InfraString::isBolVazia($strIdProtocolo)) {
      $objInfraException->lancarValidacao('Protocolo para pesquisa não informado.');
    }

    $strIdProtocolo = InfraUtil::retirarFormatacao(trim($strIdProtocolo), false);

    $objProtocoloDTO=new ProtocoloDTO();
    $objProtocoloDTO->setStrProtocoloFormatadoPesquisa($strIdProtocolo);
    $objProtocoloRN = new ProtocoloRN();
    $arrObjProtocoloDTOPesquisado = $objProtocoloRN->pesquisarProtocoloFormatado($objProtocoloDTO);

    if (count($arrObjProtocoloDTOPesquisado)==0) {
      $objInfraException->lancarValidacao('Protocolo não encontrado.');
    }

    $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();

    if ($arrObjProtocoloDTOPesquisado[0]->getStrStaProtocolo()==ProtocoloRN::$TP_PROCEDIMENTO){
      $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
    }else if ($arrObjProtocoloDTOPesquisado[0]->getStrStaProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){
      $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_GERADOS);
    }else if ($arrObjProtocoloDTOPesquisado[0]->getStrStaProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){
      $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_RECEBIDOS);
    }

    $objPesquisaProtocoloDTO->setDblIdProtocolo($arrObjProtocoloDTOPesquisado[0]->getDblIdProtocolo());
    $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);

    $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

    if (count($arrObjProtocoloDTO) == 0) {
      $objInfraException->lancarValidacao('Protocolo não encontrado.');
    }

    $objProtocoloDTO = $arrObjProtocoloDTO[0];

    if ($objProtocoloDTO->getNumCodigoAcesso() < 0) {
      if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_RESTRITO) {
        $objInfraException->lancarValidacao('Unidade atual não possui acesso ao protocolo restrito.');
      }else{
        $objInfraException->lancarValidacao('Protocolo não encontrado.');
      }
    }

    return array('IdProtocolo' => $objProtocoloDTO->getDblIdProtocolo(), 'ProtocoloFormatado' => $objProtocoloDTO->getStrProtocoloFormatado(), 'Identificacao' => self::formatarIdentificacao($objProtocoloDTO));
  }

  public static function formatarIdentificacao($objProtocoloDTO){
    if ($objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_PROCEDIMENTO){
      return $objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento();
    }else{
      return $objProtocoloDTO->getStrNomeSerieDocumento().' '.$objProtocoloDTO->getStrNumeroDocumento();
    }
  }

  public static function formatarEliminado($strTexto){
    return $strTexto.' - Eliminado';
  }

  public static function montarSelectStaNivelAcesso($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
  {
    $objProtocoloRN = new ProtocoloRN();
    $arrObjNivelAcessoDTO = $objProtocoloRN->listarNiveisAcessoRN0878();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjNivelAcessoDTO, 'StaNivel', 'Descricao');
  }

  public static function montarStaNivelAcesso($strValor)
  {
    $objProtocoloRN = new ProtocoloRN();
    $arrObjNivelAcessoDTO = $objProtocoloRN->listarNiveisAcessoRN0878();

    foreach ($arrObjNivelAcessoDTO as $objNivelAcessoDTO) {
      if ($objNivelAcessoDTO->getStrStaNivel() == $strValor) {
        return $objNivelAcessoDTO->getStrDescricao();
      }

    }

  }

  public static function calcularDataInicial($numDias)
  {
    return date("d/m/Y", mktime(0, 0, 0, date('m'), date('d') - $numDias, date('Y')));
  }

  public static function montarSelectUnidadesSolicitantesDesarquivamento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){

    $objArquivamentoDTO = new ArquivamentoDTO();
    $objArquivamentoDTO->setNumTipoFkSolicitacao(InfraDTO::$TIPO_FK_OBRIGATORIA);
    $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
    $objArquivamentoDTO->setDistinct(true);
    $objArquivamentoDTO->retNumIdUnidadeSolicitacao();
    $objArquivamentoDTO->retStrSiglaUnidadeSolicitacao();
    $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO);
    $objArquivamentoDTO->setNumIdUnidadeLocalizador(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objArquivamentoRN = new ArquivamentoRN();
    $arrObjArquivamentoDTO = $objArquivamentoRN->listar($objArquivamentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjArquivamentoDTO, 'IdUnidadeSolicitacao', 'SiglaUnidadeSolicitacao');
  }

  public static function montarSelectGrauSigilo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
  {
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, ProtocoloRN::listarGrausSigiloso(), 'StaGrau', 'Descricao');
  }

  public static function montarAcoesArvore($dblIdProcedimento,
                                           $numIdUnidadeAtual,
                                           $bolFlagAberto,
                                           $bolFlagAnexado,
                                           $bolFlagAbertoAnexado,
                                           $bolFlagProtocolo,
                                           $bolFlagArquivo,
                                           $bolFlagTramitacao,
                                           $bolFlagSobrestado,
                                           $bolFlagBloqueado,
                                           $bolFlagEliminado,
                                           $numCodigoAcessoProcedimento,
                                           $strNoPai,
                                           $arrIdRelProtocoloProtocolo,
                                           &$numNo,
                                           &$strNos,
                                           &$numNoAcao,
                                           &$strNosAcao)
  {

    try {

      global $SEI_MODULOS;

      //if (InfraArray::contar($arrIdRelProtocoloProtocolo)) {

        $objSessaoSEI = SessaoSEI::getInstance();
        $objPaginaSEI = PaginaSEI::getInstance();

        $bolAcaoEscolherBloco = $objSessaoSEI->verificarPermissao('bloco_escolher');
        $bolAcaoDefinirAtividade = $objSessaoSEI->verificarPermissao('procedimento_atualizar_andamento');
        $bolAcaoProcedimentoEnviar = $objSessaoSEI->verificarPermissao('procedimento_enviar');
        $bolAcaoAcompanhamentoGerenciar = $objSessaoSEI->verificarPermissao('acompanhamento_gerenciar');
        $bolAcaoAssinarDocumento = $objSessaoSEI->verificarPermissao('documento_assinar');
        $bolAcaoListarPublicacoes = $objSessaoSEI->verificarPermissao('publicacao_listar');
        $bolAcaoAgendarPublicacao = $objSessaoSEI->verificarPermissao('publicacao_agendar');
        $bolAcaoAlterarDocumento = $objSessaoSEI->verificarPermissao('documento_alterar');
        $bolAcaoAlterarDocumentoRecebido = $objSessaoSEI->verificarPermissao('documento_alterar_recebido');
        $bolAcaoAlterarFormulario = $objSessaoSEI->verificarPermissao('formulario_alterar');
        $bolAcaoImprimirDocumentoWeb = $objSessaoSEI->verificarPermissao('documento_imprimir_web');
        $bolAcaoProcedimentoGerarPdf = $objSessaoSEI->verificarPermissao('procedimento_gerar_pdf');
        $bolAcaoComentarioCadastrar = $objSessaoSEI->verificarPermissao('comentario_cadastrar');
        $bolAcaoGerarPublicacaoRelacionada = $objSessaoSEI->verificarPermissao('publicacao_gerar_relacionada');
        $bolAcaoConsultarDocumento = $objSessaoSEI->verificarPermissao('documento_consultar');
        $bolAcaoConsultarDocumentoRecebido = $objSessaoSEI->verificarPermissao('documento_consultar_recebido');
        $bolAcaoDocumentoEnviarEmail = $objSessaoSEI->verificarPermissao('documento_enviar_email');
        $bolAcaoResponderFormularioOuvidoria = $objSessaoSEI->verificarPermissao('responder_formulario_ouvidoria');
        $bolAcaoDownload = $objSessaoSEI->verificarPermissao('documento_download_anexo');
        $bolAcaoDocumentoVersaoListar = $objSessaoSEI->verificarPermissao('documento_versao_listar');
        $bolAcaoExcluirDocumento = $objSessaoSEI->verificarPermissao('documento_excluir');
        $bolAcaoDocumentoCancelar = $objSessaoSEI->verificarPermissao('documento_cancelar');
        $bolAcaoProtocoloSolicitarDesarquivamento = $objSessaoSEI->verificarPermissao('arquivamento_solicitar_desarquivamento');
        $bolAcaoCredencialAssinaturaGerenciar = $objSessaoSEI->verificarPermissao('credencial_assinatura_gerenciar');
        $bolAcaoDocumentoCiencia = $objSessaoSEI->verificarPermissao('documento_ciencia');
        $bolAcaoDocumentoMover = $objSessaoSEI->verificarPermissao('documento_mover');
        $bolAcaoAssinaturaExternaGerenciar = $objSessaoSEI->verificarPermissao('assinatura_externa_gerenciar');
        $bolAcaoAssinaturaVerificar = $objSessaoSEI->verificarPermissao('assinatura_verificar');
        $bolAcaoConcluirProcedimento = $objSessaoSEI->verificarPermissao('procedimento_concluir');
        $bolAcaoReabrirProcedimento = $objSessaoSEI->verificarPermissao('procedimento_reabrir');
        $bolAcaoProtocoloModeloGerenciar = $objSessaoSEI->verificarPermissao('protocolo_modelo_gerenciar');
        $bolAcaoEmailEncaminhar = $objSessaoSEI->verificarPermissao('email_encaminhar');
        $bolAcaoAlterarProcedimento = $objSessaoSEI->verificarPermissao('procedimento_alterar');
        $bolAcaoConsultarProcedimento = $objSessaoSEI->verificarPermissao('procedimento_consultar');
        $bolAcaoProcedimentoDesanexar = $objSessaoSEI->verificarPermissao('procedimento_desanexar');
        $bolAcaoProcedimentoAnexadoCiencia = $objSessaoSEI->verificarPermissao('procedimento_anexado_ciencia');
        $bolAcaoLocalizadorListar = $objSessaoSEI->verificarPermissao('localizador_protocolos_listar');
        $bolAcaoDocumentoGerarCircular = $objSessaoSEI->verificarPermissao('documento_gerar_circular');
        $bolAcaoEscolherTipo = $objSessaoSEI->verificarPermissao('documento_escolher_tipo');
        $bolAcaoDocumentoReceber = $objSessaoSEI->verificarPermissao('documento_receber');
        $bolAcaoEditalEliminacaoEliminar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_eliminar');
        $bolAcaoPlanoTrabalhoDetalhar = $objSessaoSEI->verificarPermissao('plano_trabalho_detalhar');

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $arrParametros = $objInfraParametro->listarValores(array('SEI_HABILITAR_AUTENTICACAO_DOCUMENTO_EXTERNO',
                                                                 'SEI_HABILITAR_MOVER_DOCUMENTO',
                                                                 'SEI_ACESSO_FORMULARIO_OUVIDORIA'));

        $bolHabilitarAutenticacaoDocumentoExterno = $arrParametros['SEI_HABILITAR_AUTENTICACAO_DOCUMENTO_EXTERNO'];
        $bolHabilitarMoverDocumento = $arrParametros['SEI_HABILITAR_MOVER_DOCUMENTO'];
        $bolAcessoRestritoOuvidoria = ($arrParametros['SEI_ACESSO_FORMULARIO_OUVIDORIA']=='1');

        $arrProtocolosVisitados = SessaoSEI::getInstance()->getAtributo('PROTOCOLOS_VISITADOS_'.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());

        $arrSeriesFormularios = $objInfraParametro->listarValores(array('ID_SERIE_EMAIL','ID_SERIE_OUVIDORIA'), false);

        $numIdSerieEmail = isset($arrSeriesFormularios['ID_SERIE_EMAIL']) ? $arrSeriesFormularios['ID_SERIE_EMAIL'] : null;
        $numIdSerieOuvidoria = isset($arrSeriesFormularios['ID_SERIE_OUVIDORIA']) ? $arrSeriesFormularios['ID_SERIE_OUVIDORIA'] : null;

        $arrExtensoes = array('html' => 0, 'htm' => 0, 'txt' => 0, 'png' => 0, 'jpeg' => 0, 'jpg' => 0, 'gif' => 0 );

        if (!$objPaginaSEI->isBolIpad() && !$objPaginaSEI->isBolIphone() && !$objPaginaSEI->isBolAndroid()) {
          $arrExtensoes = array_merge($arrExtensoes, array('pdf' => 0, 'xls' => 0, 'xlsx' => 0, 'doc' => 0, 'docx' => 0, 'mht' => 0, 'bmp' => 0));
        }

        $objProtocoloRN = new ProtocoloRN();

        $dto = new ProcedimentoDTO();
        $dto->setStrSinDocTodos('S');
        $dto->setStrSinDocAnexos('S');
        $dto->setStrSinConteudoEmail('S');
        $dto->setStrSinProcAnexados('S');
        $dto->setStrSinDocCircular('S');
        $dto->setStrSinArquivamento('S');
        $dto->setStrSinComentarios('S');

        $dto->setDblIdProcedimento($dblIdProcedimento);
        $dto->setArrObjRelProtocoloProtocoloDTO(InfraArray::gerarArrInfraDTO('RelProtocoloProtocoloDTO', 'IdRelProtocoloProtocolo', $arrIdRelProtocoloProtocolo));

        if ($bolFlagArquivo || $bolAcaoEditalEliminacaoEliminar){
          $objArquivamentoRN = new ArquivamentoRN();
          $arrObjTipoArquivamentoSituacaoDTO = InfraArray::indexarArrInfraDTO($objArquivamentoRN->listarValoresTipoArquivamentoSituacao(),'StaArquivamento');
        }

        $objProcedimentoRN = new ProcedimentoRN();
        $arrObjProcedimentoDTO = $objProcedimentoRN->listarCompleto($dto);

        if (count($arrObjProcedimentoDTO) == 0) {
          $objInfraException = new InfraException();
          $objInfraException->lancarValidacao('Processo não encontrado.');
        }

        $objProcedimentoDTO = $arrObjProcedimentoDTO[0];

        $arrObjRelProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();

        $arrDocumentoIntegracao = array();

        if (count($arrObjRelProtocoloProtocoloDTO)) {

          $arrObjGrauSigiloDTO = InfraArray::indexarArrInfraDTO(ProtocoloRN::listarGrausSigiloso(), 'StaGrau');

          $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
          $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
          $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
          $objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO, 'IdProtocolo2'));

          $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO), 'IdProtocolo');

          $objOrgaoDTO = new OrgaoDTO();
          $objOrgaoDTO->retStrSinPublicacao();
          $objOrgaoDTO->setNumIdOrgao($objSessaoSEI->getNumIdOrgaoUnidadeAtual());

          $objOrgaoRN = new OrgaoRN();
          $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

          $numTabBotao = $objPaginaSEI->getProxTabBarraComandosSuperior();

          foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {

            if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO) {

              $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();
              $dblIdDocumento = $objDocumentoDTO->getDblIdDocumento();

              //documento excluído durante montagem da árvore
              if (!isset($arrObjProtocoloDTO[$dblIdDocumento])) {
                continue;
              }

              $objProtocoloDTODocumento = $arrObjProtocoloDTO[$dblIdDocumento];

              $strStaDocumento = $objDocumentoDTO->getStrStaDocumento();
              $numIdSerie = $objDocumentoDTO->getNumIdSerie();
              $strNomeSerie = $objDocumentoDTO->getStrNomeSerie();
              $strStaProtocoloProtocolo = $objDocumentoDTO->getStrStaProtocoloProtocolo();
              $numIdUnidadeGeradoraProtocolo = $objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo();
              $numIdOrgaoUnidadeGeradoraProtocolo = $objDocumentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo();
              $strStaNivelAcessoGlobalProtocolo = $objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo();
              $strSinAssinado = $objDocumentoDTO->getStrSinAssinado();
              $strSinPublicado = $objDocumentoDTO->getStrSinPublicado();
              $strSinDocBloqueado = $objDocumentoDTO->getStrSinBloqueado();
              $strSinDocEliminado = $objDocumentoDTO->getStrSinEliminadoProtocolo();
              $strSinAssinadoPorOutraUnidade = $objDocumentoDTO->getStrSinAssinadoPorOutraUnidade();
              $strProtocoloDocumentoFormatado = $objDocumentoDTO->getStrProtocoloDocumentoFormatado();

              $numCodigoAcessoDocumento = $objProtocoloDTODocumento->getNumCodigoAcesso();
              $strSinAcessoAssinaturaBloco = $objProtocoloDTODocumento->getStrSinAcessoAssinaturaBloco();
              $strSinCredencialAssinatura = $objProtocoloDTODocumento->getStrSinCredencialAssinatura();
              $strSinDisponibilizadoParaOutraUnidade = $objProtocoloDTODocumento->getStrSinDisponibilizadoParaOutraUnidade();

              $objArquivamentoDTO = null;
              $strStaArquivamento = null;
              if ($strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO && $objDocumentoDTO->getObjArquivamentoDTO() != null) {
                $objArquivamentoDTO = $objDocumentoDTO->getObjArquivamentoDTO();
                $strStaArquivamento = $objArquivamentoDTO->getStrStaArquivamento();
              }

              $strIdentificacaoDocumento = DocumentoINT::montarIdentificacaoArvore($objDocumentoDTO);

              $strTooltipDocumento = '';
              $bolFlagCCO = false;

              if ($strSinDocEliminado == 'S'){
                $strTooltipDocumento = self::formatarEliminado($strIdentificacaoDocumento);
              }else {
                if ($objDocumentoDTO->getStrStaEstadoProtocolo() != ProtocoloRN::$TE_DOCUMENTO_CANCELADO) {
                  if ($strStaDocumento == DocumentoRN::$TD_FORMULARIO_AUTOMATICO && $numIdSerie == $numIdSerieEmail) {
                    $strTooltipDocumento = $objPaginaSEI->formatarParametrosJavaScript(DocumentoINT::montarTooltipEmail($objDocumentoDTO, $bolFlagCCO), false);
                  } else {
                    $strTooltipDocumento = $objPaginaSEI->formatarParametrosJavaScript($strIdentificacaoDocumento, false);
                  }
                }
              }

              $strIdentificacaoDocumento = $objPaginaSEI->formatarParametrosJavaScript($strIdentificacaoDocumento);

              $flagAnexo = false;

              $strLinkDocumento = 'about:blank';

              if ($strSinDocEliminado == 'S'){

                $strIcone = Icone::AVALIACAO_ELIMINADO;

              }else {

                if ($strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {

                  $arrObjAnexoDTO = $objDocumentoDTO->getObjProtocoloDTO()->getArrObjAnexoDTO();

                  if (InfraArray::contar($arrObjAnexoDTO) > 1) {
                    throw new InfraException('Encontrado mais de um anexo associado ao documento.');
                  }

                  if (InfraArray::contar($arrObjAnexoDTO) == 1) {

                    $strIcone = DocumentoINT::selecionarIconeAnexo($arrObjAnexoDTO[0]->getStrNome());

                    if ($strIcone != null) {

                      if (strpos($strIcone,'/')===false){
                        $strIcone = $objPaginaSEI->getDiretorioImagensGlobal().'/'.$strIcone;
                      }

                    } else {
                      $strIcone = Icone::DOCUMENTO_NAO_IDENTIFICADO;
                    }

                    $flagAnexo = true;

                  } else {
                    $strIcone = Icone::DOCUMENTO_SEM_CONTEUDO;
                  }

                } else {

                  $strIcone = Icone::DOCUMENTO_NAO_IDENTIFICADO;
                  if ($strStaDocumento == DocumentoRN::$TD_EDITOR_EDOC) {
                    if ($objDocumentoDTO->getDblIdDocumentoEdoc() != null) {
                      $strIcone = Icone::DOCUMENTO_WORD;
                    }
                  } else if ($strStaDocumento == DocumentoRN::$TD_EDITOR_INTERNO) {

                    if ($objDocumentoDTO->getStrSinCircular() == 'N') {
                      $strIcone = Icone::DOCUMENTO_INTERNO;
                    } else {
                      $strIcone = Icone::DOCUMENTO_CIRCULAR;
                    }

                  } else if ($numIdSerie == $numIdSerieEmail) {
                    if (!$bolFlagCCO) {
                      $strIcone = Icone::DOCUMENTO_EMAIL;
                    } else {
                      $strIcone = Icone::DOCUMENTO_EMAIL_CCO;
                    }
                  } else {

                    if ($strStaDocumento == DocumentoRN::$TD_FORMULARIO_GERADO) {
                      $strIcone = Icone::DOCUMENTO_FORMULARIO1;
                    } else if ($strStaDocumento == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {
                      $strIcone = Icone::DOCUMENTO_FORMULARIO2;
                    }
                  }
                }
              }

              if ($numCodigoAcessoDocumento > 0) {

                $strBuscarTarjas = '';
                if ($strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO && $strSinAssinado == 'S') {
                  $strBuscarTarjas = '&buscar_tarjas=S';
                }

                $strLinkDocumento = $objSessaoSEI->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.$strBuscarTarjas);
              }

              if ($objDocumentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_DOCUMENTO_CANCELADO) {

                if ($strSinDocEliminado == 'S'){
                  $strIcone = Icone::AVALIACAO_ELIMINADO;
                  $strTooltipCancelado = self::formatarEliminado('Documento Cancelado');
                }else {
                  $strIcone = Icone::DOCUMENTO_CANCELADO;

                  $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                  $objAtributoAndamentoDTO->retStrValor();
                  $objAtributoAndamentoDTO->setStrIdOrigem($dblIdDocumento);
                  $objAtributoAndamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_CANCELAMENTO_DOCUMENTO);
                  $objAtributoAndamentoDTO->setStrNome("MOTIVO");
                  $objAtributoAndamentoDTO->setNumMaxRegistrosRetorno(1);

                  $objAtributoAndamentoRN = new AtributoAndamentoRN();
                  $objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);

                  $strTooltipCancelado = DocumentoINT::montarTooltipAndamento('Documento Cancelado: '.$objAtributoAndamentoDTO->getStrValor());
                }

                $strNos .= "\n\n".'//CA='.$numCodigoAcessoDocumento."\n";
                $strNos .= 'Nos['.$numNo.'] = new infraArvoreNo("DOCUMENTO",'.
                  '"'.$dblIdDocumento.'",'.
                  '"'.$strNoPai.'",'.
                  '"'.$strLinkDocumento.'",'.
                  '"ifrConteudoVisualizacao",'.
                  '"'.$strIdentificacaoDocumento.'",'.
                  '"'.$strTooltipCancelado.'",'.
                  '"'.$strIcone.'",'.
                  '"'.$strIcone.'",'.
                  '"'.$strIcone.'",'.
                  'true,'.
                  (($strLinkDocumento != 'about:blank') ? 'true,' : 'false,').
                  'null,'.
                  'null,'.
                  'null,'.
                  '"'.$strProtocoloDocumentoFormatado.'");'."\n";

              } else {
                $strNos .= "\n\n".'//CA='.$numCodigoAcessoDocumento."\n";
                $strNos .= 'Nos['.$numNo.'] = new infraArvoreNo("DOCUMENTO",'.
                    '"'.$dblIdDocumento.'",'.
                    '"'.$strNoPai.'",'.
                    '"'.$strLinkDocumento.'",'.
                    '"ifrConteudoVisualizacao",'.
                    '"'.$strIdentificacaoDocumento.'",'.
                    '"'.$strTooltipDocumento.'",'.
                    '"'.$strIcone.'",'.
                    '"'.$strIcone.'",'.
                    '"'.$strIcone.'",'.
                    'true,'.
                    (($strLinkDocumento != 'about:blank') ? 'true,' : 'false,').
                    (isset($arrProtocolosVisitados[$dblIdDocumento]) ? '"noVisitado"' : 'null').','.
                    'null,'.
                    '"noVisitado",'.
                    '"'.$strProtocoloDocumentoFormatado.'");'."\n";
              }

              $strSiglaUnidadeGeradora = $objPaginaSEI->formatarParametrosJavaScript($objDocumentoDTO->getStrSiglaUnidadeGeradoraProtocolo());
              $strTitleUnidadeGeradora = $objPaginaSEI->formatarParametrosJavaScript($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo(),false);

              $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("UNIDADE_GERADORA",'.
                  '"UG'.$dblIdDocumento.'",'.
                  '"'.$dblIdDocumento.'",'.
                  '"#",'.
                  'null,'.
                  '"'.$strTitleUnidadeGeradora.'",'.
                  'null,'.
                  'true,'.
                  '"'.$strSiglaUnidadeGeradora.'");'."\n";


              if ($objDocumentoDTO->getStrStaNivelAcessoLocalProtocolo() != ProtocoloRN::$NA_PUBLICO) {
                $strNosAcao .= ProtocoloINT::montarNoAcaoAcesso($dblIdDocumento, $numNoAcao++, $objDocumentoDTO->getStrStaNivelAcessoLocalProtocolo(), $objDocumentoDTO->getStrStaGrauSigiloProtocolo(), $objDocumentoDTO->getStrNomeHipoteseLegal(), $objDocumentoDTO->getStrBaseLegalHipoteseLegal(), $arrObjGrauSigiloDTO);
              }

              if ($strSinCredencialAssinatura == 'S') {
                $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("PARA_ASSINATURA",'.
                    '"PA'.$dblIdDocumento.'",'.
                    '"'.$dblIdDocumento.'",'.
                    '"javascript:alert(\'Documento com Credencial de Assinatura\');",'.
                    'null,'.
                    '"Documento com Credencial de Assinatura",'.
                    '"'.Icone::CREDENCIAL_ASSINATURA.'",'.
                    'true);'."\n";
              }

              if ($objProtocoloDTODocumento->getArrAcessoModulos() != null) {
                $strNosAcao .= ProtocoloINT::montarNoAcaoAcessoModulos($dblIdDocumento, $numNoAcao++, $objProtocoloDTODocumento->getArrAcessoModulos());
              }

              if ($bolAcessoRestritoOuvidoria && $strStaDocumento == DocumentoRN::$TD_FORMULARIO_AUTOMATICO && $numIdSerie == $numIdSerieOuvidoria) {
                $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("ACESSO_OUVIDORIA",'.
                    '"AO'.$dblIdDocumento.'",'.
                    '"'.$dblIdDocumento.'",'.
                    '"javascript:alert(\'Somente para Ouvidoria\');",'.
                    'null,'.
                    '"Somente para Ouvidoria",'.
                    '"'.Icone::OUVIDORIA_ACESSO_RESTRITO .'",'.
                    'true);'."\n";
              }

              if ($strSinAssinado == 'S') {
                $strTextoAssinatura = DocumentoINT::montarTooltipAssinatura($objDocumentoDTO);

                if ($strSinDocBloqueado == 'N' && ($numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual || $strSinAcessoAssinaturaBloco == 'S')) {
                  $strImagemAssinatura = ($strStaDocumento == DocumentoRN::$TD_EXTERNO) ? Icone::AUTENTICACAO1 : Icone::ASSINATURA1;
                } else {
                  $strImagemAssinatura = ($strStaDocumento == DocumentoRN::$TD_EXTERNO) ? Icone::AUTENTICACAO2 : Icone::ASSINATURA2;
                }

                $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("ASSINATURA",'.
                    '"A'.$dblIdDocumento.'",'.
                    '"'.$dblIdDocumento.'",'.
                    '"javascript:alert(\''.str_replace('\n', '\\\n', $objPaginaSEI->formatarParametrosJavaScript($strTextoAssinatura)).'\');",'.
                    'null,'.
                    '"'.str_replace("\n", '\n', $strTextoAssinatura).'",'.
                    '"'.$strImagemAssinatura.'",'.
                    'true);'."\n";
              }

              if ($strSinPublicado == 'S') {

                $strTextoPublicacao = PublicacaoINT::obterTextoInformativoPublicacao($objDocumentoDTO);
                $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("PUBLICACAO",'.
                    '"P'.$dblIdDocumento.'",'.
                    '"'.$dblIdDocumento.'",'.
                    '"javascript:alert(\''.str_replace('\n', '\\\n', $objPaginaSEI->formatarParametrosJavaScript($strTextoPublicacao)).'\');",'.
                    'null,'.
                    '"'.str_replace("\n", '\n', $strTextoPublicacao).'",'.
                    '"'.Icone::PUBLICACAO.'",'.
                    'true);'."\n";
              }

              if ($numCodigoAcessoDocumento > 0) {
                if ($objRelProtocoloProtocoloDTO->getStrSinCiencia() == 'S') {

                  $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("CIENCIAS",'.
                      '"CD'.$dblIdDocumento.'",'.
                      '"'.$dblIdDocumento.'",'.
                      '"'.$objSessaoSEI->assinarLink('controlador.php?acao=protocolo_ciencia_listar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'",'.
                      '"ifrVisualizacao",'.
                      '"Visualizar Ciências no Documento",'.
                      '"'.Icone::CIENCIA.'",'.
                      'true);'."\n";
                }
              }

            if ($bolAcaoLocalizadorListar &&
              $bolFlagArquivo &&
              $strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO &&
                $objArquivamentoDTO!=null &&
                (($bolFlagEliminado && $bolAcaoEditalEliminacaoEliminar) || $bolFlagArquivo )) {

                $strTooltipLocalizadorOutraUnidade = "";
                $strIconeArquivo = Icone::ARQUIVO;
                if ($objArquivamentoDTO->getNumIdUnidadeLocalizador() != null) {

                  if ($objArquivamentoDTO->getNumIdUnidadeLocalizador() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
                    $objUnidadeDTO = new UnidadeDTO();
                    $objUnidadeDTO->setBolExclusaoLogica(false);
                    $objUnidadeDTO->retStrSigla();
                    $objUnidadeDTO->retStrSiglaOrgao();
                    $objUnidadeDTO->setNumIdUnidade($objArquivamentoDTO->getNumIdUnidadeLocalizador());

                    $objUnidadeRN = new UnidadeRN();
                    $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

                    $strTooltipLocalizadorOutraUnidade .= ' em ' . $objUnidadeDTO->getStrSigla() . '/' . $objUnidadeDTO->getStrSiglaOrgao();
                    $strIconeArquivo = Icone::ARQUIVO_OUTRA_UNIDADE;

                  }
                }

                if ($objArquivamentoDTO->getStrStaEliminacao() == ArquivamentoRN::$TE_ELIMINADO){
                  $strTooltipLocalizador = 'Eliminado do Arquivo'.$strTooltipLocalizadorOutraUnidade;
                  $strIconeArquivo = Icone::ARQUIVO_ELIMINADO;

                }else {
                  if ($objArquivamentoDTO->getNumIdUnidadeLocalizador() != null) {
                    $strTooltipLocalizador = 'Localizador '.LocalizadorINT::montarIdentificacaoRI1132($objArquivamentoDTO->getStrSiglaTipoLocalizador(), $objArquivamentoDTO->getNumSeqLocalizadorLocalizador());
                    $strTooltipLocalizador .= $strTooltipLocalizadorOutraUnidade;
                    $strTooltipLocalizador .= ' ('.PaginaSEI::tratarHTML($arrObjTipoArquivamentoSituacaoDTO[$objArquivamentoDTO->getStrStaArquivamento()]->getStrDescricao()).')';
                  } else {
                    $strTooltipLocalizador = PaginaSEI::tratarHTML($arrObjTipoArquivamentoSituacaoDTO[$objArquivamentoDTO->getStrStaArquivamento()]->getStrDescricao());
                  }
                }


                $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("LOCALIZADOR",'.
                    '"LD'.$dblIdDocumento.'",'.
                    '"'.$dblIdDocumento.'",'.
                    '"javascript:alert(\''.$objPaginaSEI->formatarParametrosJavaScript($strTooltipLocalizador, false).'\');",'.
                    '"ifrVisualizacao",'.
                    '"'.$strTooltipLocalizador.'",'.
                    '"'.$strIconeArquivo.'",'.
                    'true);'."\n";
              }

              if ($numCodigoAcessoDocumento > 0) {
                if ($objRelProtocoloProtocoloDTO->getStrSinComentarios() == 'S') {
                  $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("COMENTARIOS",'.
                      '"COM_P_'.$dblIdDocumento.'",'.
                      '"'.$dblIdDocumento.'",'.
                      '"'.$objSessaoSEI->assinarLink('controlador.php?acao=comentario_listar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento.'&id_rel_protocolo_protocolo='.$objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo().'&arvore=1').'",'.
                      '"ifrVisualizacao",'.
                      '"Visualizar Comentários",'.
                      '"'.Icone::COMENTARIO.'",'.
                      'true);'."\n";
                }
              }

              $strAcoesDocumento = '';
              $strSrc = '';
              $strHtml = '';

              //não monta ações e links por segurança
              if ($strLinkDocumento != 'about:blank') {

                if (!$bolFlagBloqueado) {
                  if ($bolFlagAberto) {
                    if ($bolAcaoEscolherTipo) {
                      $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_escolher_tipo&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img   src="'.Icone::DOCUMENTO_INCLUIR.'" alt="Incluir Documento" title="Incluir Documento"/></a>';
                    }
                  } else {
                    if ($bolFlagProtocolo && $bolAcaoDocumentoReceber && !$bolFlagAnexado && !$bolFlagSobrestado) {
                      $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_receber&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&arvore=1&flag_protocolo=S').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_INCLUIR.'" alt="Registrar Documento Externo" title="Registrar Documento Externo"/></a>';
                    }
                  }
                }

                if ($strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO) {

                  if ($bolAcaoAlterarDocumento && !$bolFlagBloqueado &&
                      (($bolFlagAberto || $bolFlagAbertoAnexado || ($bolFlagProtocolo && $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual)) ||
                          (($strSinAcessoAssinaturaBloco == 'S' || $strSinCredencialAssinatura == 'S') && $strSinAssinadoPorOutraUnidade == 'N')) &&
                      $strSinPublicado == 'N'
                  ) {
                    $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_alterar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_ALTERAR.'" alt="Consultar/Alterar Documento" title="Consultar/Alterar Documento"/></a>';
                  } else if ($bolAcaoConsultarDocumento) {
                    $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_consultar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_ALTERAR.'" alt="Consultar Documento" title="Consultar Documento" /></a>';
                  }
                }

                if ($strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {

                  if ($bolAcaoAlterarDocumentoRecebido && !$bolFlagBloqueado && ($bolFlagAberto || $bolFlagAbertoAnexado || ($bolFlagProtocolo && $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual))) {
                    $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_alterar_recebido&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_ALTERAR.'" alt="Consultar/Alterar Documento Externo" title="Consultar/Alterar Documento Externo" /></a>';
                  } else if ($bolAcaoConsultarDocumentoRecebido) {
                    $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_consultar_recebido&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_ALTERAR.'" alt="Consultar Documento Externo" title="Consultar Documento Externo" /></a>';
                  }
                }

              if ($bolAcaoAcompanhamentoGerenciar /* && $strStaNivelAcessoGlobalProtocolo!=ProtocoloRN::$NA_SIGILOSO */){
                $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=acompanhamento_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::ACOMPANHAMENTO_ESPECIAL_CADASTRO.'" alt="Acompanhamento Especial" title="Acompanhamento Especial"/></a>';
              }

                if ($bolFlagAberto && $bolAcaoDocumentoCiencia && ($strStaDocumento == DocumentoRN::$TD_EXTERNO || $strStaDocumento == DocumentoRN::$TD_FORMULARIO_AUTOMATICO || $strSinAssinado == 'S')) {
                  $strAcoesDocumento .= '<a href="#" onclick="cienciaDocumento();" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::CIENCIA.'" alt="Ciência" title="Ciência" /></a>';
                }

                if ($bolFlagAberto && !$bolFlagBloqueado && $bolAcaoProcedimentoEnviar && $strStaNivelAcessoGlobalProtocolo != ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=procedimento_enviar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.(($strSinAssinado == 'S' && $strSinDocBloqueado == 'N') ? '&id_documento_assinado='.$dblIdDocumento : '').'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::PROCESSO_ENVIAR.'" alt="Enviar Processo" title="Enviar Processo" /></a>';
                }

                if ($bolFlagAberto && $bolAcaoDefinirAtividade) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=procedimento_atualizar_andamento&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::PROCESSO_ATUALIZAR_ANDAMENTO.'" alt="Atualizar Andamento" title="Atualizar Andamento" /></a>';
                }


                if ($strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO) {

                  if ($bolAcaoAlterarDocumento && !$bolFlagBloqueado && $strStaDocumento == DocumentoRN::$TD_EDITOR_INTERNO &&
                      $strSinDocBloqueado == 'N' &&
                      (($bolFlagAberto && $numIdUnidadeAtual == $numIdUnidadeGeradoraProtocolo && $strSinDisponibilizadoParaOutraUnidade == 'N') ||
                          (($strSinAcessoAssinaturaBloco == 'S' || $strSinCredencialAssinatura == 'S') && $strSinAssinadoPorOutraUnidade == 'N')) &&
                      $strSinPublicado == 'N') {
                    $strAcoesDocumento .= '<a href="#" onclick="editarConteudo(\\\''.$strSinAssinado.'\\\');" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_EDITAR_CONTEUDO.'" alt="Editar Conteúdo" title="Editar Conteúdo" /></a>';
                  }

                  if ($strStaDocumento == DocumentoRN::$TD_FORMULARIO_GERADO) {
                    if ($bolAcaoAlterarFormulario && !$bolFlagBloqueado && $strSinDocBloqueado == 'N' &&
                        (($bolFlagAberto && $numIdUnidadeAtual == $numIdUnidadeGeradoraProtocolo && $strSinDisponibilizadoParaOutraUnidade == 'N') ||
                            (($strSinAcessoAssinaturaBloco == 'S' || $strSinCredencialAssinatura == 'S') && $strSinAssinadoPorOutraUnidade == 'N')) &&
                        $strSinPublicado == 'N') {
                      $strAcoesDocumento .= '<a href="#" onclick="alterarFormulario(\\\''.$strSinAssinado.'\\\');" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_FORMULARIO1.'" alt="Alterar Formulário" title="Alterar Formulário" /></a>';
                    }
                  }

                  if (($bolFlagAberto || $bolFlagAnexado) && !$bolFlagBloqueado && $bolAcaoDocumentoEnviarEmail && ($strSinAssinado == 'S' || $strSinPublicado == 'S')) {
                    $strAcoesDocumento .= '<a href="#" onclick="enviarEmailDocumento();" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::EMAIL_ENVIAR.'" alt="Enviar Documento por Correio Eletrônico" title="Enviar Documento por Correio Eletrônico"/></a>';
                  }

                  if ($bolFlagAberto && !$bolFlagBloqueado && $strStaDocumento == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {
                    if ($bolAcaoResponderFormularioOuvidoria && $numIdSerie == $numIdSerieOuvidoria) {
                      $strAcoesDocumento .= '<a href="#" onclick="abrirJanela(\\\'janelaEmailOuvidoria_'.SessaoSEI::getInstance()->getNumIdUsuario().'_'.$dblIdDocumento.'\\\',\\\''.$objSessaoSEI->assinarLink('controlador.php?acao=responder_formulario&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'\\\')" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::EMAIL_RESPONDER.'" alt="Responder Formulário" title="Responder Formulário"/></a>';
                    }

                    if ($bolAcaoEmailEncaminhar && $numIdSerie == $numIdSerieEmail) {
                      $strAcoesDocumento .= '<a href="#" onclick="encaminharEmail();" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::EMAIL_ENCAMINHAR.'" alt="Encaminhar / Reenviar Correspondência Eletrônica" title="Encaminhar / Reenviar Correspondência Eletrônica"/></a>';
                    }

                  }

                  if ($bolFlagAberto && $bolAcaoListarPublicacoes && $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual && ($objDocumentoDTO->getStrSinPublicacaoAgendada() == 'S' || $strSinPublicado == 'S')) {
                    $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=publicacao_listar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::PUBLICACAO_CONSULTAR.'" alt="Visualizar Publicações/Agendamentos" title="Visualizar Publicações/Agendamentos" /></a>';
                  }

                  if ($bolFlagAberto && !$bolFlagBloqueado && $bolAcaoAgendarPublicacao && $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual && $objOrgaoDTO->getStrSinPublicacao() == 'S' && $objDocumentoDTO->getStrSinPublicavel() == 'S') {
                    $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=publicacao_agendar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::PUBLICACAO_AGENDAR.'" alt="Agendar Publicação" title="Agendar Publicação"/></a>';
                  }

                  if ($bolFlagAberto && !$bolFlagBloqueado && $bolAcaoGerarPublicacaoRelacionada && $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual && $strSinPublicado == 'S') {
                    $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=publicacao_gerar_relacionada&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::PUBLICACAO_GERAR_RELACIONADA.'" alt="Gerar Publicação Relacionada" title="Gerar Publicação Relacionada"/></a>';
                  }

                  if ($bolFlagAberto &&
                      !$bolFlagBloqueado &&
                      $bolAcaoCredencialAssinaturaGerenciar &&
                      $strStaNivelAcessoGlobalProtocolo == ProtocoloRN::$NA_SIGILOSO &&
                      ($strStaDocumento == DocumentoRN::$TD_EDITOR_INTERNO || $strStaDocumento == DocumentoRN::$TD_FORMULARIO_GERADO) &&
                      $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual &&
                      $strSinPublicado == 'N'
                  ) {
                    $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=credencial_assinatura_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::CREDENCIAL_CONCESSAO_ASSINATURA.'" alt="Gerenciar Credenciais de Assinatura" title="Gerenciar Credenciais de Assinatura" /></a>';
                  }


                  if ($bolAcaoAssinarDocumento &&
                      !$bolFlagBloqueado &&
                      ($strStaDocumento == DocumentoRN::$TD_EDITOR_INTERNO || $strStaDocumento == DocumentoRN::$TD_FORMULARIO_GERADO) &&
                      (($bolFlagAberto && $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual)
                          || $strSinAcessoAssinaturaBloco == 'S' || $strSinCredencialAssinatura == 'S') &&
                      $strSinPublicado == 'N') {
                    $strAcoesDocumento .= '<a href="#" onclick="assinarDocumento();" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_ASSINAR.'" alt="Assinar Documento" title="Assinar Documento"/></a>';
                  }

                  if ($bolAcaoAssinaturaExternaGerenciar && $bolFlagAberto && !$bolFlagBloqueado &&
                      ($strStaDocumento == DocumentoRN::$TD_EDITOR_INTERNO || $strStaDocumento == DocumentoRN::$TD_FORMULARIO_GERADO) &&
                      $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual &&
                      $strSinPublicado == 'N'
                  ) {
                    $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=assinatura_externa_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_ASSINATURA_EXTERNA.'" alt="Gerenciar Liberações para Assinatura Externa" title="Gerenciar Liberações para Assinatura Externa" /></a>';
                  }

                }

                if ($strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {

                  if ($bolAcaoAssinarDocumento && !$bolFlagBloqueado &&
                      ($bolFlagAberto || $bolFlagProtocolo) &&
                      $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual &&
                      $objDocumentoDTO->getNumIdTipoConferencia() != null &&
                      (($bolHabilitarAutenticacaoDocumentoExterno == '1' && $bolFlagProtocolo) || $bolHabilitarAutenticacaoDocumentoExterno == '2')) {
                    $strAcoesDocumento .= '<a href="#" onclick="assinarDocumento();" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_AUTENTICAR.'" alt="Autenticar Documento" title="Autenticar Documento"/></a>';
                  }

                  if (($bolFlagAberto || $bolFlagAnexado) && !$bolFlagBloqueado && $bolAcaoDocumentoEnviarEmail) {
                    $strAcoesDocumento .= '<a href="#" onclick="enviarEmailDocumento();" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::EMAIL_ENVIAR.'" alt="Enviar Documento por Correio Eletrônico" title="Enviar Documento por Correio Eletrônico"/></a>';
                  }

                  if (($bolFlagAberto || $bolFlagProtocolo) && !$bolFlagBloqueado &&
                      $bolAcaoDocumentoMover &&
                      //$numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual &&
                      ((($bolHabilitarMoverDocumento == '1' || $bolHabilitarMoverDocumento == '4') && $bolFlagProtocolo) || $bolHabilitarMoverDocumento == '2' || (($bolHabilitarMoverDocumento == '3' || $bolHabilitarMoverDocumento == '4') && $objProtocoloDTODocumento->getStrSinUnidadeGeradoraProtocolo() == 'S'))) {
                    $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_mover&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_MOVER.'" alt="Mover Documento para outro Processo" title="Mover Documento para outro Processo" /></a>';
                  }
                }

                if ($bolFlagAberto &&
                    !$bolFlagBloqueado &&
                    $bolAcaoEscolherBloco &&
                    $strStaNivelAcessoGlobalProtocolo != ProtocoloRN::$NA_SIGILOSO &&
                    $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual &&
                    ($strStaDocumento == DocumentoRN::$TD_EDITOR_INTERNO || $strStaDocumento == DocumentoRN::$TD_FORMULARIO_GERADO) &&
                    $strSinPublicado == 'N'
                ) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=bloco_escolher&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::BLOCO_INCLUIR_PROTOCOLO.'"  alt="Incluir em Bloco de Assinatura" title="Incluir em Bloco de Assinatura"/></a>';
                }

                if ($bolAcaoDocumentoCancelar && !$bolFlagBloqueado &&
                    $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual &&
                    ($bolFlagAberto || $bolFlagAnexado || ($bolFlagProtocolo && $strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO)) &&
                    ($strSinDocBloqueado == 'S' || ($numIdSerie==$numIdSerieEmail && $strStaDocumento == DocumentoRN::$TD_FORMULARIO_AUTOMATICO)) &&
                    ($strStaDocumento != DocumentoRN::$TD_FORMULARIO_AUTOMATICO || $numIdSerie==$numIdSerieEmail) &&
                    $strStaArquivamento != ArquivamentoRN::$TA_ARQUIVADO &&
                    $strStaArquivamento != ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO &&
                    $strSinCredencialAssinatura == 'N' &&
                    $strSinPublicado == 'N'
                ) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_cancelar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_CANCELAR.'" alt="Cancelar Documento" title="Cancelar Documento"/></a>';
                }

                if ($bolAcaoProtocoloModeloGerenciar && $strStaNivelAcessoGlobalProtocolo != ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=protocolo_modelo_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_protocolo='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_MODELO.'" alt="Adicionar aos Favoritos" title="Adicionar aos Favoritos"/></a>';
                }

                if ($bolAcaoProtocoloSolicitarDesarquivamento &&
                    $strStaArquivamento == ArquivamentoRN::$TA_ARQUIVADO &&
                    $strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO
                ) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=arquivamento_solicitar_desarquivamento&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::ARQUIVO_DESARQUIVAR.'" alt="Solicitar Desarquivamento" title="Solicitar Desarquivamento"/></a>';
                }


                if ($bolAcaoDocumentoVersaoListar &&
                    ((($bolFlagAberto || $bolFlagAnexado) && $numIdUnidadeAtual == $numIdUnidadeGeradoraProtocolo) ||
                        (($strSinAcessoAssinaturaBloco == 'S' || $strSinCredencialAssinatura == 'S') && $strSinAssinadoPorOutraUnidade == 'N')) &&
                    ($strSinPublicado == 'N' || $numIdUnidadeAtual == $numIdUnidadeGeradoraProtocolo) &&
                    $strStaDocumento == DocumentoRN::$TD_EDITOR_INTERNO
                ) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_versao_listar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_VERSOES.'" alt="Versões do Documento" title="Versões do Documento"/></a>';
                }


                if ($bolAcaoDocumentoGerarCircular && !$bolFlagBloqueado &&
                    $bolFlagAberto && $numIdUnidadeAtual == $numIdUnidadeGeradoraProtocolo &&
                    $objDocumentoDTO->getStrSinDestinatarioSerie() == 'S' &&
                    $strStaDocumento == DocumentoRN::$TD_EDITOR_INTERNO) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_gerar_circular&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_CIRCULAR.'" alt="Gerar Circular" title="Gerar Circular"/></a>';
                }

                if ($bolAcaoImprimirDocumentoWeb && $strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
                  $strAcoesDocumento .= '<a target="_blank" href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_imprimir_web&acao_origem=arvore_visualizar&id_documento='.$dblIdDocumento).'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_IMPRIMIR.'" alt="Imprimir Web" title="Imprimir Web" /></a>';
                }

                if ($bolAcaoProcedimentoGerarPdf && $strStaProtocoloProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=procedimento_gerar_pdf&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_GERAR_PDF.'" alt="Gerar Arquivo PDF do Documento" title="Gerar Arquivo PDF do Documento"/></a>';
                }

                if ($bolAcaoComentarioCadastrar && ($bolFlagTramitacao || (($strSinAcessoAssinaturaBloco == 'S' || $strSinCredencialAssinatura == 'S')))) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=comentario_listar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_rel_protocolo_protocolo='.$objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo().'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::COMENTARIO.'" alt="Comentários" title="Comentários"/></a>';
                }

                if ($bolAcaoExcluirDocumento && !$bolFlagBloqueado && $strSinDocBloqueado == 'N' &&
                    ($bolFlagAberto || $bolFlagProtocolo) &&
                    $strStaDocumento != DocumentoRN::$TD_FORMULARIO_AUTOMATICO &&
                    $numIdUnidadeGeradoraProtocolo == $numIdUnidadeAtual &&
                    $strSinPublicado == 'N' &&
                    $objDocumentoDTO->getStrSinPublicacaoAgendada() == 'N' &&
                    $strStaArquivamento == null
                ) {
                  $strAcoesDocumento .= '<a href="#" onclick="excluirDocumento();" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::PROTOCOLO_EXCLUIR.'" alt="Excluir" title="Excluir" /></a>';
                }

                if ($bolAcaoAssinaturaVerificar && $strSinAssinado == 'S') {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=assinatura_verificar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::DOCUMENTO_ASSINATURAS_CONSULTAR.'" alt="Consultar Assinaturas" title="Consultar Assinaturas" /></a>';
                }

                if (!$bolFlagAberto && $bolAcaoReabrirProcedimento && $bolFlagTramitacao && !$bolFlagSobrestado && !$bolFlagAnexado) {
                  $strAcoesDocumento .= '<a href="#" onclick="reabrirProcesso();" tabindex="'.$numTabBotao.'" ><img src="'.Icone::PROCESSO_REABRIR.'" alt="Reabrir Processo" title="Reabrir Processo" />';
                }

                if ($bolFlagAberto && $bolAcaoConcluirProcedimento) {
                  $strAcoesDocumento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=procedimento_concluir&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::PROCESSO_CONCLUIR.'" alt="Concluir Processo" title="Concluir Processo"/></a>';
                }

                if ($bolAcaoPlanoTrabalhoDetalhar && $objProcedimentoDTO->getNumIdPlanoTrabalho() != null){
                  $strAcoesDocumento .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_detalhar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento .'&id_documento='.$dblIdDocumento. '&arvore=1') . '" tabindex="' . PaginaSEI::getInstance()->getProxTabBarraComandosSuperior() . '" ><img  src="' . Icone::PLANO_TRABALHO . '" alt="Plano de Trabalho" title="Plano de Trabalho" /></a>';
                }

                if (!$flagAnexo) {
                  $strSrc = $objSessaoSEI->assinarLink('controlador.php?acao=documento_visualizar&acao_origem=procedimento_visualizar&id_documento='.$dblIdDocumento.'&arvore=1');
                } else if ($bolAcaoDownload) {
                  $arrExtensaoAnexo = explode('.', $arrObjAnexoDTO[0]->getStrNome());

                  $strExtensaoAnexo = null;

                  if (count($arrExtensaoAnexo) > 1) {
                    $strExtensaoAnexo = strtolower($arrExtensaoAnexo[count($arrExtensaoAnexo) - 1]);
                  }

                  if ($strSinAssinado == 'S') {
                    $strNos .= 'Nos['.$numNo.'].assinatura = \'<button type="button" id="btnVisualizarAssinaturas" onclick="parent.visualizarAssinaturas();" class="infraButton" value="Visualizar Autenticações">Visualizar Autenticações</button>\';'."\n";
                  } else {
                    $strNos .= 'Nos['.$numNo.'].assinatura = \'\';'."\n";
                  }

                  $strTagDestino = 'target="_blank"';
                  /*
                  if ($objPaginaSEI->isBolAndroid()){
                    $strTagDestino = 'download="'.InfraUtil::formatarNomeArquivo($arrObjAnexoDTO[0]->getStrNome()).'"';
                  }
                  */

                  if (isset($arrExtensoes[$strExtensaoAnexo])) {
                    $strSrc = $objSessaoSEI->assinarLink('controlador.php?acao=documento_download_anexo&acao_origem=procedimento_visualizar&id_anexo='.$arrObjAnexoDTO[0]->getNumIdAnexo().'&arvore=1');
                    $strHtml = 'Clique <a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_download_anexo&acao_origem=procedimento_visualizar&id_anexo='.$arrObjAnexoDTO[0]->getNumIdAnexo()).'" '.$strTagDestino.' class="ancoraVisualizacaoArvore">aqui</a> para visualizar o conteúdo deste documento em uma nova janela.';
                  } else {
                    $strHtml = 'Clique <a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=documento_download_anexo&acao_origem=procedimento_visualizar&id_anexo='.$arrObjAnexoDTO[0]->getNumIdAnexo().'&download=1').'" '.$strTagDestino.' class="ancoraVisualizacaoArvore">aqui</a> para visualizar o conteúdo deste documento ('.InfraUtil::formatarTamanhoBytes($arrObjAnexoDTO[0]->getNumTamanho()).').';
                  }
                }
              }

              $strNos .= 'Nos['.$numNo.'].acoes = \''.$strAcoesDocumento.'\';'."\n";
              $strNos .= 'Nos['.$numNo.'].src = \''.$strSrc.'\';'."\n";
              $strNos .= 'Nos['.$numNo.'].html = \''.$strHtml.'\';'."\n";

              if (count($SEI_MODULOS)) {
                $objDocumentoAPI = new DocumentoAPI();
                $objDocumentoAPI->setIdDocumento($dblIdDocumento);
                $objDocumentoAPI->setIdSerie($numIdSerie);
                $objDocumentoAPI->setNomeSerie($strNomeSerie);
                $objDocumentoAPI->setIdUnidadeGeradora($numIdUnidadeGeradoraProtocolo);
                $objDocumentoAPI->setIdOrgaoUnidadeGeradora($numIdOrgaoUnidadeGeradoraProtocolo);
                $objDocumentoAPI->setTipo($strStaProtocoloProtocolo);
                $objDocumentoAPI->setSinAssinado($strSinAssinado);
                $objDocumentoAPI->setSinPublicado($strSinPublicado);
                $objDocumentoAPI->setSinBloqueado($strSinDocBloqueado);
                $objDocumentoAPI->setCodigoAcesso($numCodigoAcessoDocumento);
                $objDocumentoAPI->setSubTipo($strStaDocumento);
                $objDocumentoAPI->setNumeroProtocolo($strProtocoloDocumentoFormatado);

                $arrDocumentoIntegracao[$dblIdDocumento] = array();
                $arrDocumentoIntegracao[$dblIdDocumento][0] = $numNo;
                $arrDocumentoIntegracao[$dblIdDocumento][1] = $objDocumentoAPI;
              }

              $numNo++;

            } else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO) {

              $objProcedimentoDTOAnexado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

              $dblIdProcedimentoAnexado = $objProcedimentoDTOAnexado->getDblIdProcedimento();
              $strIdentificacaoProcedimentoAnexado = $objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado();
              $strTooltipProcedimentoAnexado = $objProcedimentoDTOAnexado->getStrNomeTipoProcedimento();

              if ($objProcedimentoDTOAnexado->getStrSinEliminadoProtocolo()=='S'){
                $strTooltipProcedimentoAnexado = self::formatarEliminado($strTooltipProcedimentoAnexado);
                $strIcone = Icone::AVALIACAO_ELIMINADO;
              }else{
                $strIcone = Icone::PROCESSO_ANEXADO;
              }

              $strLinkProcedimentoAnexado = 'about:blank';
              $strSrc = '';
              $strHtml = '';
              if ($arrObjProtocoloDTO[$dblIdProcedimentoAnexado]->getNumCodigoAcesso() > 0 || $bolFlagProtocolo) {
                $strLinkProcedimentoAnexado = $objSessaoSEI->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado);
                $strHtml = 'Processo <a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimentoAnexado).'" target="_blank" class="ancoraVisualizacaoArvore">'.$objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado().'</a> anexado.';
              }

              $strNos .= "\n\n".'//CA='.$arrObjProtocoloDTO[$dblIdProcedimentoAnexado]->getNumCodigoAcesso()."\n";
              $strNos .= 'Nos['.$numNo.'] = new infraArvoreNo("PROCESSO_ANEXADO",'.
                  '"'.$dblIdProcedimentoAnexado.'",'.
                  '"'.$strNoPai.'",'.
                  '"'.$strLinkProcedimentoAnexado.'",'.
                  '"ifrConteudoVisualizacao",'.
                  '"'.$strIdentificacaoProcedimentoAnexado.'",'.
                  '"'.$strTooltipProcedimentoAnexado.'",'.
                  '"'.$strIcone.'",'.
                  '"'.$strIcone.'",'.
                  '"'.$strIcone.'",'.
                  'true,'.
                  (($strLinkProcedimentoAnexado != 'about:blank') ? 'true,' : 'false,').
                  (isset($arrProtocolosVisitados[$dblIdProcedimentoAnexado]) ? '"noVisitado"' : 'null').','.
                  'null,'.
                  'null,'.
                  '"'.$strIdentificacaoProcedimentoAnexado.'");'."\n";

              $strSiglaUnidadeGeradora = $objPaginaSEI->formatarParametrosJavaScript($objProcedimentoDTOAnexado->getStrSiglaUnidadeGeradoraProtocolo());
              $strTitleUnidadeGeradora = $objPaginaSEI->formatarParametrosJavaScript($objProcedimentoDTOAnexado->getStrDescricaoUnidadeGeradoraProtocolo(),false);

              $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("UNIDADE_GERADORA",'.
                  '"UG'.$dblIdProcedimentoAnexado.'",'.
                  '"'.$dblIdProcedimentoAnexado.'",'.
                  '"javascript:void(0);",'.
                  'null,'.
                  '"'.$strTitleUnidadeGeradora.'",'.
                  'null,'.
                  'true,'.
                  '"'.$strSiglaUnidadeGeradora.'");'."\n";

              if ($objProcedimentoDTOAnexado->getStrStaNivelAcessoOriginalProtocolo() != ProtocoloRN::$NA_PUBLICO) {
                $strNosAcao .= ProtocoloINT::montarNoAcaoAcesso($dblIdProcedimentoAnexado, $numNoAcao++, $objProcedimentoDTOAnexado->getStrStaNivelAcessoOriginalProtocolo(), $objProcedimentoDTOAnexado->getStrStaGrauSigiloProtocolo(), $objProcedimentoDTOAnexado->getStrNomeHipoteseLegal(), $objProcedimentoDTOAnexado->getStrBaseLegalHipoteseLegal(), $arrObjGrauSigiloDTO);
              }

              if ($arrObjProtocoloDTO[$dblIdProcedimentoAnexado]->getNumCodigoAcesso() > 0){
                if ($objRelProtocoloProtocoloDTO->getStrSinCiencia() == 'S') {
                  $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("CIENCIAS",'.
                      '"CP'.$dblIdProcedimentoAnexado.'",'.
                      '"'.$dblIdProcedimentoAnexado.'",'.
                      '"'.$objSessaoSEI->assinarLink('controlador.php?acao=protocolo_ciencia_listar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado.'&arvore=1').'",'.
                      '"ifrVisualizacao",'.
                      '"Visualizar Ciências no Processo Anexado",'.
                      '"'.Icone::CIENCIA.'",'.
                      'true);'."\n";
                }

                if ($objRelProtocoloProtocoloDTO->getStrSinComentarios() == 'S') {
                  $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("COMENTARIOS",'.
                      '"COM_P_'.$dblIdProcedimentoAnexado.'",'.
                      '"'.$dblIdProcedimentoAnexado.'",'.
                      '"'.$objSessaoSEI->assinarLink('controlador.php?acao=comentario_listar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento.'&id_rel_protocolo_protocolo='.$objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo().'&arvore=1').'",'.
                      '"ifrVisualizacao",'.
                      '"Visualizar Comentários",'.
                      '"'.Icone::COMENTARIO.'",'.
                      'true);'."\n";
                }
              }


              $strAcoesProcedimento = '';

              if ($bolAcaoAlterarProcedimento && !$bolFlagBloqueado && ($bolFlagAberto || $bolFlagAbertoAnexado || ($bolFlagProtocolo && $objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo() == $numIdUnidadeAtual))) {
                $strAcoesProcedimento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=procedimento_alterar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimentoAnexado.'&id_procedimento_retorno='.$dblIdProcedimento.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::PROCESSO_ALTERAR.'" alt="Consultar/Alterar Processo Anexado" title="Consultar/Alterar Processo Anexado"/></a>';
              } else if ($bolAcaoConsultarProcedimento) {
                $strAcoesProcedimento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=procedimento_consultar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimentoAnexado.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::PROCESSO_ALTERAR.'" alt="Consultar Processo Anexado" title="Consultar Processo Anexado"/></a>';
              }

              if ($bolFlagAberto && $bolAcaoProcedimentoAnexadoCiencia) {
                $strAcoesProcedimento .= '<a href="#" onclick="cienciaProcessoAnexado();" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::CIENCIA.'" alt="Ciência" title="Ciência" /></a>';
              }

              if ($bolFlagAberto && $bolAcaoComentarioCadastrar) {
                $strAcoesProcedimento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=comentario_listar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_rel_protocolo_protocolo='.$objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo().'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::COMENTARIO.'" alt="Comentários" title="Comentários"/></a>';
              }

              if ($bolFlagAberto && !$bolFlagBloqueado && $bolAcaoProcedimentoDesanexar) {
                $strAcoesProcedimento .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=procedimento_desanexar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado.'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::PROCESSO_DESANEXAR.'" alt="Desanexar Processo" title="Desanexar Processo"/></a>';
              }


              $strNos .= 'Nos['.$numNo.'].acoes = \''.$strAcoesProcedimento.'\';'."\n";
              $strNos .= 'Nos['.$numNo.'].src = \''.$strSrc.'\';'."\n";
              $strNos .= 'Nos['.$numNo.'].html = \''.$strHtml.'\';'."\n";
              $numNo++;

            } else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_DESANEXADO) {

              $objProcedimentoDTODesanexado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

              $dblIdProcedimentoDesanexado = $objProcedimentoDTODesanexado->getDblIdProcedimento();
              $strIdentificacaoProcedimentoDesanexado = $objProcedimentoDTODesanexado->getStrProtocoloProcedimentoFormatado();

              if ($objProcedimentoDTODesanexado->getStrSinEliminadoProtocolo()=='S'){
                $strIcone = Icone::AVALIACAO_ELIMINADO;
                $strTooltipDesanexado = self::formatarEliminado('Processo Desanexado');
              }else {
                $strIcone = Icone::PROCESSO_DESANEXADO;

                $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                $objAtributoAndamentoDTO->retStrValor();
                $objAtributoAndamentoDTO->setDblIdProtocoloAtividade($dblIdProcedimentoDesanexado);
                $objAtributoAndamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_DESANEXADO_DO_PROCESSO);
                $objAtributoAndamentoDTO->setStrNome("MOTIVO");
                $objAtributoAndamentoDTO->setStrIdOrigem($objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo());

                $objAtributoAndamentoRN = new AtributoAndamentoRN();
                $objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);

                $strTooltipDesanexado = DocumentoINT::montarTooltipAndamento('Processo desanexado: '.$objAtributoAndamentoDTO->getStrValor());
              }

              $strLinkProcessoDesanexado = 'about:blank';
              $strSrc = '';
              $strHtml = '';
              $strAcoesProcedimento = '';

              /*
              //adiciona também acesso ao protocolo para permitir inclusão de documentos
              if ($arrObjProtocoloDTO[$dblIdProcedimentoDesanexado]->getNumCodigoAcesso() > 0 || $bolFlagProtocolo){
                $strLinkProcessoDesanexado = $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_trabalhar&id_procedimento='.$dblIdProcedimentoDesanexado);
              }
              */

              $strIdProcessoDesanexado = $dblIdProcedimentoDesanexado.'-'.$objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo();
              $strNos .= "\n\n".'//CA='.$arrObjProtocoloDTO[$dblIdProcedimentoDesanexado]->getNumCodigoAcesso()."\n";
              $strNos .= 'Nos['.$numNo.'] = new infraArvoreNo("PROCESSO_DESANEXADO",'.
                  '"'.$strIdProcessoDesanexado.'",'.
                  '"'.$strNoPai.'",'.
                  '"'.$strLinkProcessoDesanexado.'",'.
                  '"_blank",'.
                  '"'.$strIdentificacaoProcedimentoDesanexado.'",'.
                  '"'.$strTooltipDesanexado.'",'.
                  '"'.$strIcone.'",'.
                  '"'.$strIcone.'",'.
                  '"'.$strIcone.'",'.
                  'true,'.
                  (($strLinkProcessoDesanexado != 'about:blank') ? 'true,' : 'false,').
                  (isset($arrProtocolosVisitados[$dblIdProcedimentoDesanexado]) ? '"noVisitado"' : 'null').','.
                  'null,'.
                  'null,'.
                  '"'.$strIdentificacaoProcedimentoDesanexado.'");'."\n";

              $strNos .= 'Nos['.$numNo.'].acoes = \''.$strAcoesProcedimento.'\';'."\n";
              $strNos .= 'Nos['.$numNo.'].src = \''.$strSrc.'\';'."\n";
              $strNos .= 'Nos['.$numNo.'].html = \''.$strHtml.'\';'."\n";
              $numNo++;

              $strSiglaUnidadeGeradora = $objPaginaSEI->formatarParametrosJavaScript($objProcedimentoDTODesanexado->getStrSiglaUnidadeGeradoraProtocolo());
              $strTitleUnidadeGeradora = $objPaginaSEI->formatarParametrosJavaScript($objProcedimentoDTODesanexado->getStrDescricaoUnidadeGeradoraProtocolo(),false);

              $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("UNIDADE_GERADORA",'.
                  '"UG'.$strIdProcessoDesanexado.'",'.
                  '"'.$strIdProcessoDesanexado.'",'.
                  '"javascript:void(0);",'.
                  'null,'.
                  '"'.$strTitleUnidadeGeradora.'",'.
                  'null,'.
                  'true,'.
                  '"'.$strSiglaUnidadeGeradora.'");'."\n";


              if ($objRelProtocoloProtocoloDTO->getStrSinComentarios() == 'S') {
                $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("COMENTARIOS",'.
                    '"COM_P_'.$strIdProcessoDesanexado.'",'.
                    '"'.$strIdProcessoDesanexado.'",'.
                    '"'.$objSessaoSEI->assinarLink('controlador.php?acao=comentario_listar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento.'&id_rel_protocolo_protocolo='.$objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo().'&arvore=1').'",'.
                    '"ifrVisualizacao",'.
                    '"Visualizar Comentários",'.
                    '"'.Icone::COMENTARIO.'",'.
                    'true);'."\n";
              }

            } else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO) {


              $objDocumentoMovido = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

              $dblIdDocumentoMovido = $objDocumentoMovido->getDblIdDocumento();
              $strIdentificacaoDocumentoMovido = DocumentoINT::montarIdentificacaoArvore($objDocumentoMovido);
              $strIdentificacaoDocumentoMovido = InfraString::formatarXML($strIdentificacaoDocumentoMovido);

              $strIcone = Icone::DOCUMENTO_MOVIDO;

              $objAtributoAndamentoRN = new AtributoAndamentoRN();

              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->retNumIdAtividade();
              $objAtributoAndamentoDTO->retStrValor();
              $objAtributoAndamentoDTO->setDblIdProtocoloAtividade($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
              $objAtributoAndamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_DOCUMENTO_MOVIDO_PARA_PROCESSO);
              $objAtributoAndamentoDTO->setStrNome("MOTIVO");
              $objAtributoAndamentoDTO->setStrIdOrigem($objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo());

              $objAtributoAndamentoDTOMotivo = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);

              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->retStrValor();
              $objAtributoAndamentoDTO->retStrIdOrigem();
              $objAtributoAndamentoDTO->setNumIdAtividade($objAtributoAndamentoDTOMotivo->getNumIdAtividade());
              $objAtributoAndamentoDTO->setStrNome("PROCESSO");

              $objAtributoAndamentoDTOProcesso = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);


              $strLinkDocumentoMovido = 'about:blank';
              $strSrc = '';
              $strHtml = '';
              $strAcoesDocumentoMovido = '';

              $strToolTipDocumentoMovido = '';
              if ($arrObjProtocoloDTO[$dblIdDocumentoMovido]->getNumCodigoAcesso() > 0) {
                $strLinkDocumentoMovido = $objSessaoSEI->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento);
                $strHtml = 'Documento movido para o processo <a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=arvore_visualizar&id_procedimento='.$objAtributoAndamentoDTOProcesso->getStrIdOrigem().'&id_documento='.$dblIdDocumentoMovido).'" target="_blank" class="ancoraVisualizacaoArvore">'.$objAtributoAndamentoDTOProcesso->getStrValor().'</a>.';
                $strToolTipDocumentoMovido = 'Documento movido para o processo '.$objAtributoAndamentoDTOProcesso->getStrValor().': '.$objAtributoAndamentoDTOMotivo->getStrValor();
              } else {
                $strToolTipDocumentoMovido = 'Documento movido para outro processo';
              }

                $strIdNoDocumentoMovido = $dblIdDocumentoMovido.'-'.$objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo();

                if ($objDocumentoMovido->getStrSinEliminadoProtocolo()=='S'){
                  $strToolTipDocumentoMovido = self::formatarEliminado($strToolTipDocumentoMovido);
                }

              $strNos .= "\n\n".'//CA='.$arrObjProtocoloDTO[$dblIdDocumentoMovido]->getNumCodigoAcesso()."\n";
              $strNos .= 'Nos['.$numNo.'] = new infraArvoreNo("DOCUMENTO_MOVIDO",'.
                  '"'.$strIdNoDocumentoMovido.'",'.
                  '"'.$strNoPai.'",'.
                  '"'.$strLinkDocumentoMovido.'",'.
                  '"ifrConteudoVisualizacao",'.
                  '"'.$strIdentificacaoDocumentoMovido.'",'.
                  '"'.DocumentoINT::montarTooltipAndamento($strToolTipDocumentoMovido).'",'.
                  '"'.$strIcone.'",'.
                  '"'.$strIcone.'",'.
                  '"'.$strIcone.'",'.
                  'true,'.
                  (($strLinkDocumentoMovido != 'about:blank') ? 'true,' : 'false,').
                  (isset($arrProtocolosVisitados[$dblIdDocumentoMovido]) ? '"noVisitado"' : 'null').','.
                  'null,'.
                  '"noVisitado",'.
                  '"'.$objDocumentoMovido->getStrProtocoloDocumentoFormatado().'");'."\n";

              $strAcoesDocumentoMovido = '';

              if ($bolFlagAberto && $bolAcaoComentarioCadastrar) {
                $strAcoesDocumentoMovido .= '<a href="'.$objSessaoSEI->assinarLink('controlador.php?acao=comentario_listar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_rel_protocolo_protocolo='.$objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo().'&arvore=1').'" tabindex="'.$numTabBotao.'" ><img  src="'.Icone::COMENTARIO.'" alt="Comentários" title="Comentários"/></a>';
              }

              $strNos .= 'Nos['.$numNo.'].acoes = \''.$strAcoesDocumentoMovido.'\';'."\n";
              $strNos .= 'Nos['.$numNo.'].src = \''.$strSrc.'\';'."\n";
              $strNos .= 'Nos['.$numNo.'].html = \''.$strHtml.'\';'."\n";
              $numNo++;


              $strSiglaUnidadeGeradora = $objPaginaSEI->formatarParametrosJavaScript($objDocumentoMovido->getStrSiglaUnidadeGeradoraProtocolo());
              $strTitleUnidadeGeradora = $objPaginaSEI->formatarParametrosJavaScript($objDocumentoMovido->getStrDescricaoUnidadeGeradoraProtocolo(),false);

              $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("UNIDADE_GERADORA",'.
                  '"UG'.$strIdNoDocumentoMovido.'",'.
                  '"'.$strIdNoDocumentoMovido.'",'.
                  '"javascript:void(0);",'.
                  'null,'.
                  '"'.$strTitleUnidadeGeradora.'",'.
                  'null,'.
                  'true,'.
                  '"'.$strSiglaUnidadeGeradora.'");'."\n";


              if ($objRelProtocoloProtocoloDTO->getStrSinComentarios() == 'S') {
                $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("COMENTARIOS",'.
                    '"COM_P_'.$strIdNoDocumentoMovido.'",'.
                    '"'.$strIdNoDocumentoMovido.'",'.
                    '"'.$objSessaoSEI->assinarLink('controlador.php?acao=comentario_listar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento.'&id_rel_protocolo_protocolo='.$objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo().'&arvore=1').'",'.
                    '"ifrVisualizacao",'.
                    '"Visualizar Comentários",'.
                    '"'.Icone::COMENTARIO.'",'.
                    'true);'."\n";
              }

            } else {
              throw new InfraException('Tipo de associação do protocolo inválido.');
            }
          }
        }


        if (count($SEI_MODULOS)) {

          $objProcedimentoAPI = new ProcedimentoAPI();
          $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
          $objProcedimentoAPI->setNumeroProtocolo($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
          $objProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
          $objProcedimentoAPI->setNomeTipoProcedimento($objProcedimentoDTO->getStrNomeTipoProcedimento());
          $objProcedimentoAPI->setIdTipoPrioridade($objProcedimentoDTO->getNumIdTipoProcedimento());
          $objProcedimentoAPI->setNivelAcesso($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo());
          $objProcedimentoAPI->setIdUnidadeGeradora($objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo());
          $objProcedimentoAPI->setIdOrgaoUnidadeGeradora($objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
          $objProcedimentoAPI->setIdHipoteseLegal($objProcedimentoDTO->getNumIdHipoteseLegalProtocolo());
          $objProcedimentoAPI->setGrauSigilo($objProcedimentoDTO->getStrStaGrauSigiloProtocolo());
          $objProcedimentoAPI->setCodigoAcesso($numCodigoAcessoProcedimento);
          $objProcedimentoAPI->setSinAberto($bolFlagAberto?'S':'N');

          $arrObjDocumentoAPIIntegracao = array();
          foreach($arrDocumentoIntegracao as $arrItemDocumentoIntegracao){
            $arrObjDocumentoAPIIntegracao[] = $arrItemDocumentoIntegracao[1];
          }

          $strNosAcao .= "\n\n";
          $strNos .= "\n\n";

          foreach ($SEI_MODULOS as $seiModulo) {

            $strIcone = null;
            if (($arrRetIntegracao = $seiModulo->executar('alterarIconeArvoreDocumento', $objProcedimentoAPI, $arrObjDocumentoAPIIntegracao)) != null) {
              foreach ($arrRetIntegracao as $dblIdDocumento => $strIcone) {
                $strNos .= 'Nos[' . $arrDocumentoIntegracao[$dblIdDocumento][0] . '].icone = \''.$strIcone.'\';' . "\n";
              }
            }

            $strNos .= "\n";

            if (($arrRetIntegracao = $seiModulo->executar('montarBotaoDocumento', $objProcedimentoAPI, $arrObjDocumentoAPIIntegracao)) != null) {
              foreach ($arrRetIntegracao as $dblIdDocumento => $arrAcoesDocumento) {
                $strNos .= 'Nos[' . $arrDocumentoIntegracao[$dblIdDocumento][0] . '].acoes = Nos[' . $arrDocumentoIntegracao[$dblIdDocumento][0] . '].acoes.concat(\'' . implode('',$arrAcoesDocumento) . '\');' . "\n";
              }
            }

            $strNos .= "\n";

            if (($arrRetIntegracao = $seiModulo->executar('montarIconeDocumento', $objProcedimentoAPI, $arrObjDocumentoAPIIntegracao)) != null) {
              foreach ($arrRetIntegracao as $dblIdDocumento => $arrObjArvoreAcaoItemAPI) {
                foreach($arrObjArvoreAcaoItemAPI as $objArvoreAcaoItemAPI) {
                  $strNosAcao .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("' . $objArvoreAcaoItemAPI->getTipo() . '",' .
                      '"' . $objArvoreAcaoItemAPI->getId() . '",' .
                      '"' . $objArvoreAcaoItemAPI->getIdPai() . '",' .
                      '"' . $objArvoreAcaoItemAPI->getHref() . '",' .
                      '"' . $objArvoreAcaoItemAPI->getTarget() . '",' .
                      '"' . $objArvoreAcaoItemAPI->getTitle() . '",' .
                      '"' . $objArvoreAcaoItemAPI->getIcone() . '",' .
                      ($objArvoreAcaoItemAPI->getSinHabilitado()=='S' ? 'true' : 'false') . ');' . "\n";
                }
              }
            }
          }
        }
      //}
    } catch (Exception $e) {
      throw new InfraException('Erro montando ações para documentos.', $e);
    }
  }

  public static function montarNivelAcesso($arrIdTipoProcedimento, $objProtocoloDTO, $bolConsultar, &$strCss, &$strHtml, &$strJsGlobal, &$strJsInicializar, &$strJsValidacoes){

    $bolHabilitarSigiloso = false;
    $bolHabilitarRestrito = false;
    $bolHabilitarPublico = false;
    $bolMarcarSigiloso = false;
    $bolMarcarRestrito = false;
    $bolMarcarPublico = false;
    $strCss = '';
    $strHtml = '';
    $strJsGlobal = '';
    $strJsInicializar = '';
    $strJsValidacoes = '';
    $strLabelHipoteseLegal = '';

    $strStaNivelAcesso =  $objProtocoloDTO->getStrStaNivelAcessoLocal();

    if ($bolConsultar){
      if ($strStaNivelAcesso==ProtocoloRN::$NA_SIGILOSO){
        $bolMarcarSigiloso = true;
      }else if ($strStaNivelAcesso==ProtocoloRN::$NA_RESTRITO){
        $bolMarcarRestrito = true;
      }else if ($strStaNivelAcesso==ProtocoloRN::$NA_PUBLICO){
        $bolMarcarPublico = true;
      }
    }else{

      if ($strStaNivelAcesso==ProtocoloRN::$NA_SIGILOSO){
        $bolHabilitarSigiloso = true;
      }else if ($strStaNivelAcesso==ProtocoloRN::$NA_RESTRITO){
        $bolHabilitarRestrito = true;
      }else if ($strStaNivelAcesso==ProtocoloRN::$NA_PUBLICO){
        $bolHabilitarPublico = true;
      }

      $objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
      $objNivelAcessoPermitidoDTO->setDistinct(true);
      $objNivelAcessoPermitidoDTO->retStrStaNivelAcesso();
      $objNivelAcessoPermitidoDTO->setNumIdTipoProcedimento($arrIdTipoProcedimento,InfraDTO::$OPER_IN);

      $objNivelAcessoPermitidoRN = new NivelAcessoPermitidoRN();
      $arrObjNivelAcessoPermitidoDTO = $objNivelAcessoPermitidoRN->listar($objNivelAcessoPermitidoDTO);

      foreach($arrObjNivelAcessoPermitidoDTO as $objNivelAcessoPermitidoDTO){
        if ($objNivelAcessoPermitidoDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_SIGILOSO){
          $bolHabilitarSigiloso = true;
        }else if ($objNivelAcessoPermitidoDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_RESTRITO){
          $bolHabilitarRestrito = true;
        }else if ($objNivelAcessoPermitidoDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_PUBLICO){
          $bolHabilitarPublico = true;
        }
      }

      if ($bolHabilitarSigiloso && ($strStaNivelAcesso==ProtocoloRN::$NA_SIGILOSO || (!$bolHabilitarRestrito && !$bolHabilitarPublico))){
        $bolMarcarSigiloso = true;

        if ($strStaNivelAcesso!=ProtocoloRN::$NA_SIGILOSO){
          $objProtocoloDTO->unSetStrStaGrauSigilo();
          $objProtocoloDTO->unSetNumIdHipoteseLegal();
        }

        $strStaNivelAcesso = ProtocoloRN::$NA_SIGILOSO;

      }else if ($bolHabilitarRestrito && ($strStaNivelAcesso==ProtocoloRN::$NA_RESTRITO || (!$bolHabilitarSigiloso && !$bolHabilitarPublico))){
        $bolMarcarRestrito = true;

        if ($strStaNivelAcesso!=ProtocoloRN::$NA_RESTRITO){
          $objProtocoloDTO->unSetStrStaGrauSigilo();
          $objProtocoloDTO->unSetNumIdHipoteseLegal();
        }

        $strStaNivelAcesso = ProtocoloRN::$NA_RESTRITO;

      }else if ($bolHabilitarPublico && ($strStaNivelAcesso==ProtocoloRN::$NA_PUBLICO || (!$bolHabilitarSigiloso && !$bolHabilitarRestrito))){
        $bolMarcarPublico = true;

        if ($strStaNivelAcesso!=ProtocoloRN::$NA_PUBLICO){
          $objProtocoloDTO->unSetStrStaGrauSigilo();
          $objProtocoloDTO->unSetNumIdHipoteseLegal();
        }

        $strStaNivelAcesso = ProtocoloRN::$NA_PUBLICO;
      }
    }

    if (!$objProtocoloDTO->isSetStrStaGrauSigilo() || !$objProtocoloDTO->isSetNumIdHipoteseLegal()){

      $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
      $objTipoProcedimentoDTO->setDistinct(true);
      $objTipoProcedimentoDTO->retStrStaGrauSigiloSugestao();
      $objTipoProcedimentoDTO->retNumIdHipoteseLegalSugestao();
      $objTipoProcedimentoDTO->setNumIdTipoProcedimento($arrIdTipoProcedimento,InfraDTO::$OPER_IN);

      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);

      if (count($arrObjTipoProcedimentoDTO)==1) {

        $objTipoProcedimentoDTO = $arrObjTipoProcedimentoDTO[0];

        if (!$objProtocoloDTO->isSetStrStaGrauSigilo()) {
          $objProtocoloDTO->setStrStaGrauSigilo($objTipoProcedimentoDTO->getStrStaGrauSigiloSugestao());
        }

        if (!$objProtocoloDTO->isSetNumIdHipoteseLegal()) {
          $objProtocoloDTO->setNumIdHipoteseLegal($objTipoProcedimentoDTO->getNumIdHipoteseLegalSugestao());
        }
      }
    }

    $strItensSelGrauSigilo = ProtocoloINT::montarSelectGrauSigilo('null','&nbsp;', $objProtocoloDTO->getStrStaGrauSigilo());
    $strItensSelHipoteseLegal = HipoteseLegalINT::montarSelectNomeBaseLegal('null','&nbsp;', $objProtocoloDTO->getNumIdHipoteseLegal(),$strStaNivelAcesso);

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $arrParametros = $objInfraParametro->listarValores(array('SEI_HABILITAR_HIPOTESE_LEGAL','SEI_HABILITAR_GRAU_SIGILO'));
    $numHabilitarHipoteseLegal = $arrParametros['SEI_HABILITAR_HIPOTESE_LEGAL'];
    $numHabilitarGrauSigilo = $arrParametros['SEI_HABILITAR_GRAU_SIGILO'];

    $strTopOptionsNivelAcesso = '0';
    $strTopSelectGrauSigilo = '0';
    $strTopLabelHipoteseLegal = '0';
    $strTopSelectHipoteseLegal = '0';

    if ($strStaNivelAcesso==ProtocoloRN::$NA_PUBLICO){
      $strHeightDivNivelAcesso = '8em';
      $strTopOptionsNivelAcesso = (PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'25':'45');
      $strDisplayGrauSigilo = 'display:none';
      $strDisplayHipoteseLegal = 'display:none';
    }else if ($strStaNivelAcesso==ProtocoloRN::$NA_RESTRITO || $strStaNivelAcesso==ProtocoloRN::$NA_SIGILOSO){

      if ($numHabilitarHipoteseLegal){
        $strHeightDivNivelAcesso = '13em';
        $strTopOptionsNivelAcesso = (PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'14':'26');
        $strDisplayHipoteseLegal = '';
        $strTopLabelHipoteseLegal = (PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'40':'50');
        $strTopSelectHipoteseLegal = (PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'60':'67');
      }else{
        $strHeightDivNivelAcesso = '8em';
        $strTopOptionsNivelAcesso = (PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'25':'45');
        $strDisplayHipoteseLegal = 'display:none';
      }

      if ($numHabilitarGrauSigilo && $strStaNivelAcesso==ProtocoloRN::$NA_SIGILOSO){
        $strDisplayGrauSigilo = '';
        $strTopSelectGrauSigilo = (PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'13':'24');
      }else{
        $strDisplayGrauSigilo = 'display:none';
      }
    }else{
      $strHeightDivNivelAcesso = '8em';
      $strTopOptionsNivelAcesso = (PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'25':'45');
      $strDisplayGrauSigilo = 'display:none';
      $strDisplayHipoteseLegal = 'display:none';
    }

    $strCss = '';
    $strCss .= '#divNivelAcesso {height:'.$strHeightDivNivelAcesso.';}'."\n";
    $strCss .= '#fldNivelAcesso {position:absolute;left:0%;top:0%;height:80%;width:85%;}'."\n";
    $strCss .= '#divOptSigiloso  {position:absolute;left:13%;top:'.$strTopOptionsNivelAcesso.'%;}'."\n";
    $strCss .= '#selGrauSigilo {position:absolute;left:25%;top:'.$strTopSelectGrauSigilo.'%;'.$strDisplayGrauSigilo.'}'."\n";
    $strCss .= '#divOptRestrito {position:absolute;left:43%;top:'.$strTopOptionsNivelAcesso.'%;}'."\n";
    $strCss .= '#divOptPublico   {position:absolute;left:73%;top:'.$strTopOptionsNivelAcesso.'%;}'."\n";
    $strCss .= '#lblHipoteseLegal {position:absolute;left:5%;width:90%;top:'.$strTopLabelHipoteseLegal.'%;'.$strDisplayHipoteseLegal.'}'."\n";
    $strCss .= '#selHipoteseLegal {position:absolute;left:5%;width:90%;top:'.$strTopSelectHipoteseLegal.'%;'.$strDisplayHipoteseLegal.'}';

    if ($numHabilitarHipoteseLegal==1){
      $strLabelHipoteseLegal = 'infraLabelOpcional';
    }else if ($numHabilitarHipoteseLegal==2){
      $strLabelHipoteseLegal = 'infraLabelObrigatorio';
    }

    $strHtml = '';
    $strHtml .= '<div id="divNivelAcesso" class="infraAreaDados">'."\n";
    $strHtml .= '<fieldset id="fldNivelAcesso" class="infraFieldset">'."\n";
    $strHtml .= '<legend class="infraLegend">Nível de Acesso</legend>'."\n\n";

    $strHtml .= '<div id="divOptSigiloso" class="infraDivRadio">'."\n";
    $strHtml .= '  <input '.($bolHabilitarSigiloso?'':'disabled="disabled"').' type="radio" name="rdoNivelAcesso" id="optSigiloso" onchange="alterarNivelAcesso()" value="'.ProtocoloRN::$NA_SIGILOSO.'" '.($bolMarcarSigiloso?'checked="checked"':'').' class="infraRadio" tabindex="1000" />'."\n";
    $strHtml .= '  <span '.($bolHabilitarSigiloso?'':'disabled="disabled"').' id="spnSigiloso"><label id="lblSigiloso" for="optSigiloso" class="infraLabelRadio">Sigiloso</label><label>&nbsp;</label></span>'."\n";
    $strHtml .= '</div>'."\n\n";

    $strHtml .= '  <select id="selGrauSigilo" name="selGrauSigilo" class="infraSelect">'."\n";
    $strHtml .= $strItensSelGrauSigilo;
    $strHtml .= '  </select>'."\n";

    $strHtml .= '<div id="divOptRestrito" class="infraDivRadio">'."\n";
    $strHtml .= '  <input '.($bolHabilitarRestrito?'':'disabled="disabled"').' type="radio" name="rdoNivelAcesso" id="optRestrito" onchange="alterarNivelAcesso()" value="'.ProtocoloRN::$NA_RESTRITO.'" '.($bolMarcarRestrito?'checked="checked"':'').' class="infraRadio" tabindex="1000" />'."\n";
    $strHtml .= '  <span '.($bolHabilitarRestrito?'':'disabled="disabled"').' id="spnRestrito"><label id="lblRestrito" for="optRestrito" class="infraLabelRadio">Restrito</label></span>'."\n";
    $strHtml .= '</div>'."\n\n";

    $strHtml .= '<div id="divOptPublico" class="infraDivRadio">'."\n";
    $strHtml .= '  <input '.($bolHabilitarPublico?'':'disabled="disabled"').' type="radio" name="rdoNivelAcesso" id="optPublico" onchange="alterarNivelAcesso()" value="'.ProtocoloRN::$NA_PUBLICO.'" '.($bolMarcarPublico?'checked="checked"':'').' class="infraRadio" tabindex="1000" />'."\n";
    $strHtml .= '  <span '.($bolHabilitarPublico?'':'disabled="disabled"').' id="spnPublico"><label id="lblPublico" for="optPublico" class="infraLabelRadio">Público</label></span>'."\n";
    $strHtml .= '</div>'."\n\n";

    $strHtml .= '<label id="lblHipoteseLegal" for="selHipoteseLegal" accesskey="" class="'.$strLabelHipoteseLegal.'">Hipótese Legal:</label>'."\n";
    $strHtml .= '<select id="selHipoteseLegal" name="selHipoteseLegal" class="infraSelect">'."\n";
    $strHtml .= $strItensSelHipoteseLegal;
    $strHtml .= '</select>'."\n\n";
    $strHtml .=  "\n\n";

    $strHtml .= '</fieldset>'."\n";
    $strHtml .= '</div>'."\n\n";

    $strJsValidacoes = '';

    $strJsValidacoes .= 'if (!document.getElementById(\'optSigiloso\').checked && !document.getElementById(\'optRestrito\').checked && !document.getElementById(\'optPublico\').checked) {'."\n";
    $strJsValidacoes .= '  alert(\'Informe o nível de acesso.\');'."\n";
    $strJsValidacoes .= '  return false;'."\n";
    $strJsValidacoes .= '}'."\n\n";

    if ($numHabilitarGrauSigilo==2){
      $strJsValidacoes .= 'if (document.getElementById(\'optSigiloso\').checked){'."\n";
      $strJsValidacoes .= '  if (!infraSelectSelecionado(\'selGrauSigilo\')){'."\n";
      $strJsValidacoes .= '    alert(\'Informe o grau de sigilo.\');'."\n";
      $strJsValidacoes .= '    document.getElementById(\'selGrauSigilo\').focus();'."\n";
      $strJsValidacoes .= '    return false;'."\n";
      $strJsValidacoes .= '  }'."\n";
      $strJsValidacoes .= '}'."\n\n";
    }


    if ($numHabilitarHipoteseLegal==2){
      $strJsValidacoes .= 'if (document.getElementById(\'optSigiloso\').checked || document.getElementById(\'optRestrito\').checked){'."\n";
      $strJsValidacoes .= '  if (!infraSelectSelecionado(\'selHipoteseLegal\')){'."\n";
      $strJsValidacoes .= '    alert(\'Informe a Hipótese Legal.\');'."\n";
      $strJsValidacoes .= '    document.getElementById(\'selHipoteseLegal\').focus();'."\n";
      $strJsValidacoes .= '    return false;'."\n";
      $strJsValidacoes .= '  }'."\n";
      $strJsValidacoes .= '}'."\n\n";
    }

    $strJsGlobal = '';

    $strJsGlobal .= 'var objAjaxHipoteseLegal = null;'."\n";
    $strJsGlobal .= 'var objAjaxTipoProcedimentoSugestoes = null;'."\n\n";


    $strJsGlobal .= 'function alterarNivelAcesso(){'."\n";

    $strJsGlobal .= '  infraSelectSelecionarItem(\'selGrauSigilo\',\'null\');'."\n";
    $strJsGlobal .= '  infraSelectSelecionarItem(\'selHipoteseLegal\',\'null\');'."\n\n";

    $strJsGlobal .= '  if (document.getElementById(\'optPublico\').checked){'."\n";
    $strJsGlobal .= '    document.getElementById(\'divNivelAcesso\').style.height = \'8em\';'."\n";
    $strJsGlobal .= '    document.getElementById(\'divOptSigiloso\').style.top = \''.(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'25%':'45%').'\';'."\n";
    $strJsGlobal .= '    document.getElementById(\'divOptRestrito\').style.top = \''.(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'25%':'45%').'\';'."\n";
    $strJsGlobal .= '    document.getElementById(\'divOptPublico\').style.top = \''.(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'25%':'45%').'\';'."\n";
    $strJsGlobal .= '    document.getElementById(\'lblHipoteseLegal\').style.display = \'none\';'."\n";
    $strJsGlobal .= '    document.getElementById(\'selHipoteseLegal\').style.display = \'none\';'."\n";
    $strJsGlobal .= '    document.getElementById(\'selGrauSigilo\').style.display = \'none\';'."\n";
    $strJsGlobal .= '  }else if (document.getElementById(\'optRestrito\').checked || document.getElementById(\'optSigiloso\').checked){'."\n";
    if ($numHabilitarHipoteseLegal){
      $strJsGlobal .= '    document.getElementById(\'divNivelAcesso\').style.height = \'13em\';'."\n";
      $strJsGlobal .= '    document.getElementById(\'divOptSigiloso\').style.top = \''.(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'14%':'26%').'\';'."\n";
      $strJsGlobal .= '    document.getElementById(\'divOptRestrito\').style.top = \''.(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'14%':'26%').'\';'."\n";
      $strJsGlobal .= '    document.getElementById(\'divOptPublico\').style.top = \''.(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'14%':'26%').'\';'."\n";
      $strJsGlobal .= '    document.getElementById(\'lblHipoteseLegal\').style.top = \''.(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'40%':'50%').'\';'."\n";
      $strJsGlobal .= '    document.getElementById(\'selHipoteseLegal\').style.top = \''.(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'60%':'67%').'\';'."\n";
      $strJsGlobal .= '    document.getElementById(\'lblHipoteseLegal\').style.display = \'block\';'."\n";
      $strJsGlobal .= '    document.getElementById(\'selHipoteseLegal\').style.display = \'block\';'."\n";
      $strJsGlobal .= "\n";
    }

    if ($numHabilitarGrauSigilo){
      $strJsGlobal .= '    if (document.getElementById(\'optSigiloso\').checked){'."\n";
      $strJsGlobal .= '      document.getElementById(\'selGrauSigilo\').style.top = \''.(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'13%':'24%').'\';'."\n";
      $strJsGlobal .= '      document.getElementById(\'selGrauSigilo\').style.display = \'block\';'."\n";
      $strJsGlobal .= '    }else{'."\n";
      $strJsGlobal .= '      document.getElementById(\'selGrauSigilo\').style.display = \'none\';'."\n";
      $strJsGlobal .= '    }'."\n";
      $strJsGlobal .= "\n";
    }

    if ($numHabilitarHipoteseLegal || $numHabilitarGrauSigilo){
      $strJsGlobal .= '    objAjaxTipoProcedimentoSugestoes.executar();'."\n";
    }

    $strJsGlobal .= '  }'."\n";
    $strJsGlobal .= '}'."\n\n";

    $strJsInicializar = '';

    $strJsInicializar .= 'objAjaxHipoteseLegal = new infraAjaxMontarSelect(\'selHipoteseLegal\',\''.SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=hipotese_legal_select_nome_base_legal').'\');'."\n";
    $strJsInicializar .= 'objAjaxHipoteseLegal.prepararExecucao = function(){'."\n";
    $strJsInicializar .= '  if (document.getElementById(\'optSigiloso\').checked){'."\n";
    $strJsInicializar .= '    staNivelAcesso = \''.ProtocoloRN::$NA_SIGILOSO.'\';'."\n";
    $strJsInicializar .= '  }else if (document.getElementById(\'optRestrito\').checked){'."\n";
    $strJsInicializar .= '    staNivelAcesso = \''.ProtocoloRN::$NA_RESTRITO.'\';'."\n";
    $strJsInicializar .= '  }else if (document.getElementById(\'optPublico\').checked){'."\n";
    $strJsInicializar .= '    staNivelAcesso = \''.ProtocoloRN::$NA_PUBLICO.'\';'."\n";
    $strJsInicializar .= '  }'."\n";
    $strJsInicializar .= '  return infraAjaxMontarPostPadraoSelect(\'null\',\'\',document.getElementById(\'hdnIdHipoteseLegalSugestao\').value) + \'&staNivelAcesso=\' + staNivelAcesso;'."\n";
    $strJsInicializar .= '};'."\n\n";

    $strJsInicializar .= 'objAjaxTipoProcedimentoSugestoes = new infraAjaxComplementar(null,\''.SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=tipo_procedimento_obter_sugestoes').'\');'."\n";
    $strJsInicializar .= 'objAjaxTipoProcedimentoSugestoes.prepararExecucao = function(){'."\n";
    $strJsInicializar .= '  return \'idTipoProcedimento=\'+document.getElementById(\'hdnIdTipoProcedimento\').value;'."\n";
    $strJsInicializar .= '}'."\n";
    $strJsInicializar .= 'objAjaxTipoProcedimentoSugestoes.processarResultado = function(arr){'."\n";

    $strJsInicializar .= '  if(arr!=null){'."\n";
    $strJsInicializar .= '    if (document.getElementById(\'optSigiloso\').checked){'."\n";
    $strJsInicializar .= '      for(var i=0; i < document.getElementById(\'selGrauSigilo\').options.length;i++){'."\n";
    $strJsInicializar .= '        if (document.getElementById(\'selGrauSigilo\').options[i].value == arr[\'StaGrauSigiloSugestao\']){'."\n";
    $strJsInicializar .= '          document.getElementById(\'selGrauSigilo\').options[i].selected = true;'."\n";
    $strJsInicializar .= '          break;'."\n";
    $strJsInicializar .= '        }'."\n";
    $strJsInicializar .= '      }'."\n";
    $strJsInicializar .= '    }'."\n";

    $strJsInicializar .= '    if (arr[\'IdHipoteseLegalSugestao\']!=undefined){'."\n";
    $strJsInicializar .= '      document.getElementById(\'hdnIdHipoteseLegalSugestao\').value = arr[\'IdHipoteseLegalSugestao\'];'."\n";
    $strJsInicializar .= '    }'."\n";
    $strJsInicializar .= '  }'."\n";
    $strJsInicializar .= '  objAjaxHipoteseLegal.executar();'."\n";
    $strJsInicializar .= '}'."\n\n";
  }

  public static function montarNoAcaoAcesso($dblIdProtocolo, $numNoAcao, $strStaNivelAcesso, $staGrauSigilo, $strNomeHipoteseLegal, $strBaseLegalHipoteseLegal, $arrObjGrauSigiloDTO){

    $strTexto = '';
    $strImagem = '';

    if ($strStaNivelAcesso==ProtocoloRN::$NA_RESTRITO) {
      $strTexto = 'Acesso Restrito';
      $strImagem = Icone::PROCESSO_RESTRITO;
    }else if ($strStaNivelAcesso==ProtocoloRN::$NA_SIGILOSO) {
      $strTexto = 'Acesso Sigiloso';
      $strImagem = Icone::PROCESSO_SIGILOSO;

      if ($staGrauSigilo!=''){
        $strTexto .= ' ('. $arrObjGrauSigiloDTO[$staGrauSigilo]->getStrDescricao().')';
      }
    }

    if ($strNomeHipoteseLegal!=''){
      $strTexto .= '\n'.$strNomeHipoteseLegal.' ('.$strBaseLegalHipoteseLegal.')';
      $strTexto = PaginaSEI::formatarParametrosJavaScript($strTexto, false);
    }

    return 'NosAcoes['.$numNoAcao.'] = new infraArvoreAcao("NIVEL_ACESSO",'.
                                                            '"NA'.$dblIdProtocolo.'",'.
                                                            '"'.$dblIdProtocolo.'",'.
                                                            '"javascript:alert(\''.str_replace('\n','\\\n',$strTexto).'\');",'.
                                                            'null,'.
                                                            '"'.$strTexto.'",'.
                                                            '"'.$strImagem.'",'.
                                                            'true);'."\n";
  }

  public static function montarNoAcaoAcessoModulos($dblIdProtocolo, $numNoAcao, $arrAcessoModulos){

    global $SEI_MODULOS;

    if (isset($arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO])) {
      $arrModulos = $arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO];
      $strTipo = 'concedido';
      $strIcone = Icone::MODULO_ACESSO_CONCEDIDO;
    }else {
      $arrModulos = $arrAcessoModulos[SeiIntegracao::$TAM_NEGADO];
      $strTipo = 'negado';
      $strIcone = Icone::MODULO_ACESSO_NEGADO;
    }

    if (InfraArray::contar($arrModulos) == 1) {
      $strAcessoModulos = 'Acesso '.$strTipo.' pelo módulo "' . $SEI_MODULOS[$arrModulos[0]]->getNome() . '"';
    } else {
      $strAcessoModulos = '';
      foreach ($arrModulos as $strModulo) {

        if ($strAcessoModulos != '') {
          $strAcessoModulos .= ',\n';
        }

        $strAcessoModulos .= '"' . $SEI_MODULOS[$strModulo]->getNome() . '"';
      }
      $strAcessoModulos = 'Acesso '.$strTipo.' pelos módulos:\n' . $strAcessoModulos;
    }

    $strAcessoModulos = PaginaSEI::formatarParametrosJavaScript($strAcessoModulos, false);

    return 'NosAcoes[' . $numNoAcao . '] = new infraArvoreAcao("ACESSO_MODULO",' .
                                          '"AM' . $dblIdProtocolo . '",' .
                                          '"' . $dblIdProtocolo . '",' .
                                          '"javascript:alert(\'' . str_replace('\n', '\\\n', $strAcessoModulos) . '\');",' .
                                          'null,' .
                                          '"' . $strAcessoModulos . '",' .
                                          '"'.$strIcone.'",' .
                                          'true);' . "\n";
  }

  public static function adicionarProtocoloVisitado($dblIdProtocolo){
    $arr = SessaoSEI::getInstance()->getAtributo('PROTOCOLOS_VISITADOS_'.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());
    if (!is_array($arr)){
      $arr = array($dblIdProtocolo => 0);
    }else{
      if (!isset($arr[$dblIdProtocolo])){
        $arr[$dblIdProtocolo] = 0;
      }
    }
    SessaoSEI::getInstance()->setAtributo('PROTOCOLOS_VISITADOS_'.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual(), $arr);
  }

  public static function removerProtocoloVisitado($dblIdProtocolo){
    $arr = SessaoSEI::getInstance()->getAtributo('PROTOCOLOS_VISITADOS_'.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());
    if (is_array($arr)){
      if (isset($arr[$dblIdProtocolo])){
        unset($arr[$dblIdProtocolo]);
      }
      SessaoSEI::getInstance()->setAtributo('PROTOCOLOS_VISITADOS_'.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual(), $arr);
    }
  }

  public static function obterCssProtocolo(ProcedimentoDTO $objProcedimentoDTO, $arrProcessosVisitados){

    $strCssProcesso = '';

    if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo()!=ProtocoloRN::$NA_SIGILOSO){
      $strCssProcesso .= 'processoVisualizado';
      if ($arrProcessosVisitados != null && isset($arrProcessosVisitados[$objProcedimentoDTO->getDblIdProcedimento()])){
        $strCssProcesso .= ' processoVisitado';
      }
    }else {
      $strCssProcesso .= 'processoVisualizadoSigiloso';
      if ($arrProcessosVisitados != null && isset($arrProcessosVisitados[$objProcedimentoDTO->getDblIdProcedimento()])) {
        $strCssProcesso .= ' processoVisitadoSigiloso';
      }
    }
    return $strCssProcesso;
  }
}
?>