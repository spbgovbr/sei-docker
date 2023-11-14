<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 16/09/2011 - criado por mga
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../SEI.php';

class SeiRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }


  protected function gerarProcedimentoConectado(EntradaGerarProcedimentoAPI $objEntradaGerarProcedimentoAPI) {
    try {
      $objProcedimentoAPI = $objEntradaGerarProcedimentoAPI->getProcedimento();

      $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
      $objTipoProcedimentoDTO->retStrNome();
      $objTipoProcedimentoDTO->setNumIdTipoProcedimento($objProcedimentoAPI->getIdTipoProcedimento());

      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);

      if ($objTipoProcedimentoDTO == null) {
        throw new InfraException('Tipo de procedimento [' . $objProcedimentoAPI->getIdTipoProcedimento() . '] não encontrado.');
      }

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null) {
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setNumIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
        $objProcedimentoDTO->setStrNomeTipoProcedimento($objTipoProcedimentoDTO->getStrNome());
        $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_GERAR_PROCEDIMENTO, $objProcedimentoDTO);
      }

      if (!InfraString::isBolVazia($objProcedimentoAPI->getNumeroProtocolo()) && InfraString::isBolVazia($objProcedimentoAPI->getDataAutuacao())) {
        throw new InfraException('Número do Protocolo somente deve ser informado em conjunto com a Data de Autuação.');
      }

      if (InfraString::isBolVazia($objProcedimentoAPI->getNumeroProtocolo()) && !InfraString::isBolVazia($objProcedimentoAPI->getDataAutuacao())) {
        throw new InfraException('Data de Autuação somente deve ser informada em conjunto com o Número do Protocolo.');
      }

      $objTipoPrioridadeDTO = new TipoPrioridadeDTO();

      if (!InfraString::isBolVazia($objProcedimentoAPI->getIdTipoPrioridade())) {
        $objTipoPrioridadeDTO->setBolExclusaoLogica(false);
        $objTipoPrioridadeDTO->retNumIdTipoPrioridade();
        $objTipoPrioridadeDTO->retStrNome();
        $objTipoPrioridadeDTO->setNumIdTipoPrioridade($objProcedimentoAPI->getIdTipoPrioridade());

        $objTipoPrioridadeRN = new TipoPrioridadeRN();
        $objTipoPrioridadeDTO = $objTipoPrioridadeRN->consultar($objTipoPrioridadeDTO);

        if ($objTipoPrioridadeDTO == null) {
          throw new InfraException('Tipo de prioridade [' . $objProcedimentoAPI->getIdTipoPrioridade() . '] não encontrado.');
        }
      }

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null) {
        if (InfraString::isBolVazia($objProcedimentoAPI->getNumeroProtocolo())) {
          $objProtocoloRN = new ProtocoloRN();
          $objProcedimentoAPI->setNumeroProtocolo($objProtocoloRN->gerarNumeracaoProcesso());
        }
      }

      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

      $ret = $this->gerarProcedimentoInterno($objEntradaGerarProcedimentoAPI);

      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando geração de processo.', $e);
    }
  }

  protected function gerarProcedimentoInternoControlado(EntradaGerarProcedimentoAPI $objEntradaGerarProcedimentoAPI) {
    try {
      $objInfraException = new InfraException();

      $objProcedimentoAPI = $objEntradaGerarProcedimentoAPI->getProcedimento();

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setDblIdProcedimento(null);
      $objProcedimentoDTO->setNumIdTipoProcedimento($objProcedimentoAPI->getIdTipoProcedimento());
      $objProcedimentoDTO->setStrSinGerarPendencia('S');

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProtocolo(null);


      $objProtocoloDTO->setStrProtocoloFormatado($objProcedimentoAPI->getNumeroProtocolo());
      $objProtocoloDTO->setDtaGeracao($objProcedimentoAPI->getDataAutuacao());
      $objProtocoloDTO->setStrDescricao($objProcedimentoAPI->getEspecificacao());
      $objProcedimentoDTO->setStrSinGerarPendencia('S');
      $objProtocoloDTO->setStrStaNivelAcessoLocal($objProcedimentoAPI->getNivelAcesso());
      $objProtocoloDTO->setNumIdHipoteseLegal($objProcedimentoAPI->getIdHipoteseLegal());

      $arrObjParticipantesDTO = array();
      $arrObjInteressadoAPI = $objProcedimentoAPI->getInteressados();
      if ($arrObjInteressadoAPI != null) {
        $numInteressados = InfraArray::contar($arrObjInteressadoAPI);
        for ($i = 0; $i < $numInteressados; $i++) {
          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->setNumIdContato($arrObjInteressadoAPI[$i]->getIdContato());
          $objParticipanteDTO->setDblCpfContato($arrObjInteressadoAPI[$i]->getCpf());
          $objParticipanteDTO->setDblCnpjContato($arrObjInteressadoAPI[$i]->getCnpj());
          $objParticipanteDTO->setStrSiglaContato($arrObjInteressadoAPI[$i]->getSigla());
          $objParticipanteDTO->setStrNomeContato($arrObjInteressadoAPI[$i]->getNome());
          $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
          $objParticipanteDTO->setNumSequencia($i);
          $arrObjParticipantesDTO[] = $objParticipanteDTO;
        }
      }
      $objProtocoloDTO->setArrObjParticipanteDTO($arrObjParticipantesDTO);

      $arrObjRelProtocoloAssuntoDTO = array();
      $arrAssuntosAPI = $objProcedimentoAPI->getAssuntos();
      if ($arrAssuntosAPI != null) {
        $numAssuntos = InfraArray::contar($arrAssuntosAPI);
        $objAssuntoRN = new AssuntoRN();
        for ($i = 0; $i < $numAssuntos; $i++) {
          $objAssuntoDTO = new AssuntoDTO();
          $objAssuntoDTO->retNumIdAssunto();
          $objAssuntoDTO->setStrCodigoEstruturado($arrAssuntosAPI[$i]->getCodigoEstruturado());
          $objAssuntoDTO->setStrSinAtualTabelaAssuntos('S');
          $objAssuntoDTO = $objAssuntoRN->consultarRN0256($objAssuntoDTO);

          if ($objAssuntoDTO == null) {
            throw new InfraException('Assunto [' . $arrAssuntosAPI[$i]->getCodigoEstruturado() . '] não encontrado.');
          }

          $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
          $objRelProtocoloAssuntoDTO->setNumIdAssunto($objAssuntoDTO->getNumIdAssunto());
          $objRelProtocoloAssuntoDTO->setNumSequencia($i);
          $arrObjRelProtocoloAssuntoDTO[] = $objRelProtocoloAssuntoDTO;
        }
      }
      $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO($arrObjRelProtocoloAssuntoDTO);

      $objObservacaoDTO = new ObservacaoDTO();
      $objObservacaoDTO->setStrDescricao($objProcedimentoAPI->getObservacao());
      $objProtocoloDTO->setArrObjObservacaoDTO(array($objObservacaoDTO));

      $objProcedimentoDTO->setObjProtocoloDTO($objProtocoloDTO);

      if (!InfraString::isBolVazia($objProcedimentoAPI->getIdTipoPrioridade())) {
        $objProcedimentoDTO->setNumIdTipoPrioridade($objProcedimentoAPI->getIdTipoPrioridade());
      }

      $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      $objTipoProcedimentoDTO->retStrStaNivelAcessoSugestao();
      $objTipoProcedimentoDTO->retStrStaGrauSigiloSugestao();
      $objTipoProcedimentoDTO->retNumIdHipoteseLegalSugestao();
      $objTipoProcedimentoDTO->setNumIdTipoProcedimento($objProcedimentoAPI->getIdTipoProcedimento());

      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);

      if (InfraString::isBolVazia($objProcedimentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal()) || $objProcedimentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal() == $objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao()) {
        $objProcedimentoDTO->getObjProtocoloDTO()->setStrStaNivelAcessoLocal($objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao());
        $objProcedimentoDTO->getObjProtocoloDTO()->setStrStaGrauSigilo($objTipoProcedimentoDTO->getStrStaGrauSigiloSugestao());
        $objProcedimentoDTO->getObjProtocoloDTO()->setNumIdHipoteseLegal($objTipoProcedimentoDTO->getNumIdHipoteseLegalSugestao());
      }

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null) {
        if ($objProcedimentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal() == ProtocoloRN::$NA_SIGILOSO) {
          $objInfraException->lancarValidacao('Não é permitida a geração de processo sigiloso através de Web Services porque nenhum usuário terá credencial de acesso.');
        }
      }

      //Busca e adiciona os assuntos sugeridos para o tipo informado
      $objRelTipoProcedimentoAssuntoDTO = new RelTipoProcedimentoAssuntoDTO();
      $objRelTipoProcedimentoAssuntoDTO->retNumIdAssunto();
      $objRelTipoProcedimentoAssuntoDTO->retNumSequencia();
      $objRelTipoProcedimentoAssuntoDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());

      $objRelTipoProcedimentoAssuntoRN = new RelTipoProcedimentoAssuntoRN();
      $arrObjRelTipoProcedimentoAssuntoDTO = $objRelTipoProcedimentoAssuntoRN->listarRN0192($objRelTipoProcedimentoAssuntoDTO);
      $arrObjRelProtocoloAssuntoDTO = $objProcedimentoDTO->getObjProtocoloDTO()->getArrObjRelProtocoloAssuntoDTO();
      foreach ($arrObjRelTipoProcedimentoAssuntoDTO as $objRelTipoProcedimentoAssuntoDTO) {
        $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
        $objRelProtocoloAssuntoDTO->setNumIdAssunto($objRelTipoProcedimentoAssuntoDTO->getNumIdAssunto());
        $objRelProtocoloAssuntoDTO->setNumSequencia($objRelTipoProcedimentoAssuntoDTO->getNumSequencia());
        $arrObjRelProtocoloAssuntoDTO[] = $objRelProtocoloAssuntoDTO;
      }
      $objProcedimentoDTO->getObjProtocoloDTO()->setArrObjRelProtocoloAssuntoDTO($arrObjRelProtocoloAssuntoDTO);

      $arrObjParticipanteDTO = $this->prepararParticipantes($objProcedimentoDTO->getObjProtocoloDTO()->getArrObjParticipanteDTO());

      $objProcedimentoDTO->getObjProtocoloDTO()->setArrObjParticipanteDTO($arrObjParticipanteDTO);

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTOGerado = $objProcedimentoRN->gerarRN0156($objProcedimentoDTO);

      $arrProcedimentosRelacionados = $objEntradaGerarProcedimentoAPI->getProcedimentosRelacionados();
      if ($arrProcedimentosRelacionados != null) {
        foreach ($arrProcedimentosRelacionados as $dblIdProcedimentoRelacionado) {
          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDTOGerado->getDblIdProcedimento());
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($dblIdProcedimentoRelacionado);
          $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);
          $objRelProtocoloProtocoloDTO->setStrMotivo(null);
          $objProcedimentoRN->relacionarProcedimentoRN1020($objRelProtocoloProtocoloDTO);
        }
      }

      if ($objEntradaGerarProcedimentoAPI->getIdMarcador() != null) {
        $objDefinicaoMarcadorAPI = new DefinicaoMarcadorAPI();
        $objDefinicaoMarcadorAPI->setIdProcedimento($objProcedimentoDTOGerado->getDblIdProcedimento());
        $objDefinicaoMarcadorAPI->setIdMarcador($objEntradaGerarProcedimentoAPI->getIdMarcador());
        $objDefinicaoMarcadorAPI->setTexto($objEntradaGerarProcedimentoAPI->getTextoMarcador());
        $this->definirMarcador(array($objDefinicaoMarcadorAPI));
      }

      if ($objEntradaGerarProcedimentoAPI->getDataControlePrazo() != null || $objEntradaGerarProcedimentoAPI->getDiasControlePrazo() != null) {
        $objControlePrazoAPI = new EntradaDefinirControlePrazoAPI();
        $objControlePrazoAPI->setIdProcedimento($objProcedimentoDTOGerado->getDblIdProcedimento());
        $objControlePrazoAPI->setDataPrazo($objEntradaGerarProcedimentoAPI->getDataControlePrazo());
        $objControlePrazoAPI->setDias($objEntradaGerarProcedimentoAPI->getDiasControlePrazo());
        $objControlePrazoAPI->setSinDiasUteis($objEntradaGerarProcedimentoAPI->getSinDiasUteisControlePrazo());
        $objControlePrazoAPI->setIdProcedimento($objProcedimentoDTOGerado->getDblIdProcedimento());
        $this->definirControlePrazo(array($objControlePrazoAPI));
      }

      $objSaidaGerarProcedimentoAPI = new SaidaGerarProcedimentoAPI();
      $objSaidaGerarProcedimentoAPI->setIdProcedimento($objProcedimentoDTOGerado->getDblIdProcedimento());
      $objSaidaGerarProcedimentoAPI->setProcedimentoFormatado($objProcedimentoDTOGerado->getStrProtocoloProcedimentoFormatado());

      $arrObjSaidaIncluirDocumentoAPI = array();
      $arrObjDocumentoAPI = $objEntradaGerarProcedimentoAPI->getDocumentos();
      if ($arrObjDocumentoAPI != null) {
        foreach ($arrObjDocumentoAPI as $objDocumentoAPI) {
          $objDocumentoAPI->setIdProcedimento($objProcedimentoDTOGerado->getDblIdProcedimento());
          $arrObjSaidaIncluirDocumentoAPI[] = $this->incluirDocumento($objDocumentoAPI);
        }
      }
      $objSaidaGerarProcedimentoAPI->setRetornoInclusaoDocumentos($arrObjSaidaIncluirDocumentoAPI);

      //muda para vermelha a visualizacao
      $objAtividadeDTOVisualizacao = new AtividadeDTO();
      $objAtividadeDTOVisualizacao->setDblIdProtocolo($objProcedimentoDTOGerado->getDblIdProcedimento());
      $objAtividadeDTOVisualizacao->setNumTipoVisualizacao(AtividadeRN::$TV_NAO_VISUALIZADO);

      $objAtividadeRN = new AtividadeRN();
      $objAtividadeRN->atualizarVisualizacao($objAtividadeDTOVisualizacao);

      $arrIdUnidadesEnvio = $objEntradaGerarProcedimentoAPI->getUnidadesEnvio();

      if ($arrIdUnidadesEnvio != null && InfraArray::contar($arrIdUnidadesEnvio)) {
        $objEntradaEnviarProcessoAPI = new EntradaEnviarProcessoAPI();
        $objEntradaEnviarProcessoAPI->setIdProcedimento($objProcedimentoDTOGerado->getDblIdProcedimento());
        $objEntradaEnviarProcessoAPI->setUnidadesDestino($arrIdUnidadesEnvio);
        $objEntradaEnviarProcessoAPI->setSinManterAbertoUnidade($objEntradaGerarProcedimentoAPI->getSinManterAbertoUnidade());
        $objEntradaEnviarProcessoAPI->setSinRemoverAnotacao('N');
        $objEntradaEnviarProcessoAPI->setSinEnviarEmailNotificacao($objEntradaGerarProcedimentoAPI->getSinEnviarEmailNotificacao());
        $objEntradaEnviarProcessoAPI->setDataRetornoProgramado($objEntradaGerarProcedimentoAPI->getDataRetornoProgramado());
        $objEntradaEnviarProcessoAPI->setDiasRetornoProgramado($objEntradaGerarProcedimentoAPI->getDiasRetornoProgramado());
        $objEntradaEnviarProcessoAPI->setSinDiasUteisRetornoProgramado($objEntradaGerarProcedimentoAPI->getSinDiasUteisRetornoProgramado());
        $objEntradaEnviarProcessoAPI->setSinReabrir('N');
        $this->enviarProcesso($objEntradaEnviarProcessoAPI);
      }

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null && SessaoSEI::getInstance()->getObjServicoDTO()->getStrSinLinkExterno() == 'S') {
        $objAcessoExternoDTO = $this->obterAcessoExternoSistema($objProcedimentoDTOGerado->getDblIdProcedimento());
        $objSaidaGerarProcedimentoAPI->setLinkAcesso(SessaoSEIExterna::getInstance($objAcessoExternoDTO->getNumIdAcessoExterno())->assinarLink(ConfiguracaoSEI::getInstance()->getValor('SEI',
            'URL') . '/processo_acesso_externo_consulta.php?id_acesso_externo=' . $objAcessoExternoDTO->getNumIdAcessoExterno()));
      } else {
        $objSaidaGerarProcedimentoAPI->setLinkAcesso(ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL') . '/controlador.php?acao=procedimento_trabalhar&id_procedimento=' . $objProcedimentoDTOGerado->getDblIdProcedimento());
      }

      return $objSaidaGerarProcedimentoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando geração de processo.', $e);
    }
  }

  protected function incluirDocumentoControlado(DocumentoAPI $objDocumentoAPI) {
    try {
      $objInfraException = new InfraException();

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retDblIdProcedimento();
      $objProcedimentoDTO->retNumIdUsuarioGeradorProtocolo();
      $objProcedimentoDTO->retNumIdTipoProcedimento();
      $objProcedimentoDTO->retStrStaNivelAcessoGlobalProtocolo();
      $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTO->retNumIdTipoProcedimento();
      $objProcedimentoDTO->retStrNomeTipoProcedimento();

      if (InfraString::isBolVazia($objDocumentoAPI->getIdProcedimento()) && InfraString::isBolVazia($objDocumentoAPI->getProtocoloProcedimento())) {
        $objInfraException->lancarValidacao('Processo não informado.');
      }

      if (!InfraString::isBolVazia($objDocumentoAPI->getIdProcedimento())) {
        $objProcedimentoDTO->adicionarCriterio(array('IdProcedimento', 'ProtocoloProcedimentoFormatado', 'ProtocoloProcedimentoFormatadoPesquisa'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
            $objDocumentoAPI->getIdProcedimento(), $objDocumentoAPI->getIdProcedimento(), InfraUtil::retirarFormatacao($objDocumentoAPI->getIdProcedimento(), false)
          ), array(InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR));
      } else {
        $objProcedimentoDTO->adicionarCriterio(array('ProtocoloProcedimentoFormatado', 'ProtocoloProcedimentoFormatadoPesquisa'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
            $objDocumentoAPI->getProtocoloProcedimento(), InfraUtil::retirarFormatacao($objDocumentoAPI->getProtocoloProcedimento(), false)
          ), array(InfraDTO::$OPER_LOGICO_OR));
      }

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

      if ($objProcedimentoDTO == null) {
        if (!InfraString::isBolVazia($objDocumentoAPI->getIdProcedimento()) && InfraString::isBolVazia($objDocumentoAPI->getProtocoloProcedimento())) {
          throw new InfraException('Processo [' . $objDocumentoAPI->getIdProcedimento() . '] não encontrado.');
        } else {
          if (InfraString::isBolVazia($objDocumentoAPI->getIdProcedimento()) && !InfraString::isBolVazia($objDocumentoAPI->getProtocoloProcedimento())) {
            throw new InfraException('Processo [' . $objDocumentoAPI->getProtocoloProcedimento() . '] não encontrado.');
          } else {
            throw new InfraException('Processo [' . $objDocumentoAPI->getIdProcedimento() . '/' . $objDocumentoAPI->getProtocoloProcedimento() . '] não encontrado.');
          }
        }
      }

      if (trim($objDocumentoAPI->getIdSerie()) == '') {
        throw new InfraException('Tipo de documento não informado.');
      } else {
        $objSerieDTO = new SerieDTO();
        $objSerieDTO->retNumIdSerie();
        $objSerieDTO->retStrNome();
        $objSerieDTO->retStrStaAplicabilidade();
        $objSerieDTO->retNumIdTipoFormulario();
        $objSerieDTO->setNumIdSerie($objDocumentoAPI->getIdSerie());

        $objSerieRN = new SerieRN();
        $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);

        if ($objSerieDTO == null) {
          throw new InfraException('Tipo de documento [' . $objDocumentoAPI->getIdSerie() . '] não encontrado.');
        }
      }

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null) {
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setNumIdTipoProcedimentoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
        $objDocumentoDTO->setStrNomeTipoProcedimentoProcedimento($objProcedimentoDTO->getStrNomeTipoProcedimento());
        $objDocumentoDTO->setNumIdSerie($objSerieDTO->getNumIdSerie());
        $objDocumentoDTO->setStrNomeSerie($objSerieDTO->getStrNome());
        $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_INCLUIR_DOCUMENTO, $objDocumentoDTO);
      }

      if ($objDocumentoAPI->getTipo() != ProtocoloRN::$TP_DOCUMENTO_GERADO && $objDocumentoAPI->getTipo() != ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
        $objInfraException->lancarValidacao('Tipo do protocolo [' . $objDocumentoAPI->getTipo() . '] inválido.');
      }

      $dtaGeracao = $objDocumentoAPI->getData();
      if ($objDocumentoAPI->getTipo() == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
        if (!InfraString::isBolVazia($dtaGeracao)) {
          $objInfraException->lancarValidacao('Não é possível informar a data para um documento gerado.');
        } else {
          $dtaGeracao = InfraData::getStrDataAtual();
        }
      } else {
        if (InfraString::isBolVazia($dtaGeracao)) {
          $objInfraException->lancarValidacao('Data do documento não informada.');
        }
      }

      if ($objDocumentoAPI->getTipo() == ProtocoloRN::$TP_DOCUMENTO_GERADO && $objDocumentoAPI->getNomeArquivo() != null) {
        throw new InfraException('Não é possível informar o nome do arquivo para documento gerado.');
      }

      if ($objDocumentoAPI->getTipo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO && $objDocumentoAPI->getNomeArquivo() == null && (strlen($objDocumentoAPI->getConteudoMTOM()) > 0 || strlen($objDocumentoAPI->getConteudo()) > 0)) {
        throw new InfraException('Nome do arquivo não informado para o conteúdo do documento externo.');
      }

      //Permitir externos sem anexo da mesma forma que a interface
      //if ($objDocumentoAPI->getTipo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO && $objDocumentoAPI->getNomeArquivo()==null){
      //  throw new InfraException('Nome do arquivo não informado para o documento externo.');
      //}

      if ($objDocumentoAPI->getTipo() == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
        if (strlen($objDocumentoAPI->getConteudoMTOM()) > 0) {
          throw new InfraException ('Para documento gerado não é possível informar o elemento ConteudoMTOM. Utilizar os elementos Conteudo (Base64) ou ConteudoSecoes (Base64).');
        }

        if (strlen($objDocumentoAPI->getConteudo()) > 0 && InfraArray::contar($objDocumentoAPI->getConteudoSecoes())) {
          throw new InfraException ('Não é possível enviar documento gerado utilizando os elementos Conteudo (Base64) e ConteudoSecoes (Base64) ao mesmo tempo.');
        }
      } else {
        if (InfraArray::contar($objDocumentoAPI->getConteudoSecoes())) {
          throw new InfraException ('Para documento externo não é possível informar o elemento ConteudoSecoes. Utilizar os elementos Conteudo (Base64) ou ConteudoMTOM (Binário).');
        }

        if (strlen($objDocumentoAPI->getConteudoMTOM()) > 0 && strlen($objDocumentoAPI->getConteudo()) > 0) {
          throw new InfraException ('Não é possível enviar documento externo utilizando os elementos Conteudo (Base64) e ConteudoMTOM (Binário) ao mesmo tempo.');
        }
      }

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setDblIdDocumento(null);
      $objDocumentoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProtocolo(null);
      $objProtocoloDTO->setStrStaProtocolo($objDocumentoAPI->getTipo());
      $objProtocoloDTO->setDtaGeracao($dtaGeracao);

      $objDocumentoDTO->setNumIdSerie($objDocumentoAPI->getIdSerie());
      $objDocumentoDTO->setStrNomeSerie($objSerieDTO->getStrNome());

      $objDocumentoDTO->setDblIdDocumentoEdoc(null);
      $objDocumentoDTO->setDblIdDocumentoEdocBase(null);
      $objDocumentoDTO->setNumIdUnidadeResponsavel(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objDocumentoDTO->setStrNumero($objDocumentoAPI->getNumero());
      $objDocumentoDTO->setStrNomeArvore($objDocumentoAPI->getNomeArvore());
      $objDocumentoDTO->setDinValor($objDocumentoAPI->getDinValor());
      $objDocumentoDTO->setNumIdTipoConferencia($objDocumentoAPI->getIdTipoConferencia());

      if (!InfraString::isBolVazia($objDocumentoAPI->getSinArquivamento())) {
        $objDocumentoDTO->setStrSinArquivamento($objDocumentoAPI->getSinArquivamento());
      } else {
        $objDocumentoDTO->setStrSinArquivamento('N');
      }

      $objProtocoloDTO->setStrStaNivelAcessoLocal($objDocumentoAPI->getNivelAcesso());
      $objProtocoloDTO->setNumIdHipoteseLegal($objDocumentoAPI->getIdHipoteseLegal());
      $objProtocoloDTO->setStrDescricao($objDocumentoAPI->getDescricao());

      $arrObjParticipantesDTO = array();
      if ($objDocumentoAPI->getRemetente() != null) {
        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->setNumIdContato($objDocumentoAPI->getRemetente()->getIdContato());
        $objParticipanteDTO->setDblCpfContato($objDocumentoAPI->getRemetente()->getCpf());
        $objParticipanteDTO->setDblCnpjContato($objDocumentoAPI->getRemetente()->getCnpj());
        $objParticipanteDTO->setStrSiglaContato($objDocumentoAPI->getRemetente()->getSigla());
        $objParticipanteDTO->setStrNomeContato($objDocumentoAPI->getRemetente()->getNome());
        $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_REMETENTE);
        $objParticipanteDTO->setNumSequencia(0);
        $arrObjParticipantesDTO[] = $objParticipanteDTO;
      }

      $arrObjInteressadoAPI = $objDocumentoAPI->getInteressados();
      if ($arrObjInteressadoAPI != null) {
        $numInteressados = InfraArray::contar($arrObjInteressadoAPI);
        for ($i = 0; $i < $numInteressados; $i++) {
          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->setNumIdContato($arrObjInteressadoAPI[$i]->getIdContato());
          $objParticipanteDTO->setDblCpfContato($arrObjInteressadoAPI[$i]->getCpf());
          $objParticipanteDTO->setDblCnpjContato($arrObjInteressadoAPI[$i]->getCnpj());
          $objParticipanteDTO->setStrSiglaContato($arrObjInteressadoAPI[$i]->getSigla());
          $objParticipanteDTO->setStrNomeContato($arrObjInteressadoAPI[$i]->getNome());
          $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
          $objParticipanteDTO->setNumSequencia($i);
          $arrObjParticipantesDTO[] = $objParticipanteDTO;
        }
      }

      $arrObjDestinatarioAPI = $objDocumentoAPI->getDestinatarios();
      if ($arrObjDestinatarioAPI != null) {
        $numDestinatarios = InfraArray::contar($arrObjDestinatarioAPI);
        for ($i = 0; $i < $numDestinatarios; $i++) {
          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->setNumIdContato($arrObjDestinatarioAPI[$i]->getIdContato());
          $objParticipanteDTO->setDblCpfContato($arrObjDestinatarioAPI[$i]->getCpf());
          $objParticipanteDTO->setDblCnpjContato($arrObjDestinatarioAPI[$i]->getCnpj());
          $objParticipanteDTO->setStrSiglaContato($arrObjDestinatarioAPI[$i]->getSigla());
          $objParticipanteDTO->setStrNomeContato($arrObjDestinatarioAPI[$i]->getNome());
          $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_DESTINATARIO);
          $objParticipanteDTO->setNumSequencia($i);
          $arrObjParticipantesDTO[] = $objParticipanteDTO;
        }
      }

      $objProtocoloDTO->setArrObjParticipanteDTO($arrObjParticipantesDTO);

      //OBSERVACOES
      $objObservacaoDTO = new ObservacaoDTO();
      $objObservacaoDTO->setStrDescricao($objDocumentoAPI->getObservacao());
      $objProtocoloDTO->setArrObjObservacaoDTO(array($objObservacaoDTO));

      if ($objDocumentoAPI->getNomeArquivo() != null) {
        $objAnexoDTO = new AnexoDTO();
        $objAnexoDTO->setStrNome($objDocumentoAPI->getNomeArquivo());
        $objProtocoloDTO->setArrObjAnexoDTO(array($objAnexoDTO));
      } else {
        if ($objDocumentoAPI->getIdArquivo() != null) {
          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO->setNumIdAnexoOrigem($objDocumentoAPI->getIdArquivo());
          $objProtocoloDTO->setArrObjAnexoDTO(array($objAnexoDTO));
        } else {
          $objProtocoloDTO->setArrObjAnexoDTO(array());
        }
      }

      $objDocumentoDTO->setStrConteudo(null);

      $arrObjRelProtocoloAtributoDTO = array();
      $arrObjCampoAPI = $objDocumentoAPI->getCampos();
      if ($arrObjCampoAPI != null) {
        foreach ($arrObjCampoAPI as $objCampoAPI) {
          $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
          $objRelProtocoloAtributoDTO->setStrNomeAtributo($objCampoAPI->getNome());
          $objRelProtocoloAtributoDTO->setStrValor($objCampoAPI->getValor());
          $arrObjRelProtocoloAtributoDTO[] = $objRelProtocoloAtributoDTO;
        }
      }
      $objProtocoloDTO->setArrObjRelProtocoloAtributoDTO($arrObjRelProtocoloAtributoDTO);

      $objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);

      $objDocumentoDTO->setStrSinBloqueado($objDocumentoAPI->getSinBloqueado());
      $objDocumentoDTO->setNumIdItemEtapa($objDocumentoAPI->getIdItemEtapa());

      $bolReabriuAutomaticamente = false;
      if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_PUBLICO || $objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_RESTRITO) {
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
        $objAtividadeDTO->retNumIdAtividade();
        $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::getArrTarefasTramitacao(), InfraDTO::$OPER_IN);

        $objAtividadeRN = new AtividadeRN();
        if ($objAtividadeRN->consultarRN0033($objAtividadeDTO) == null) {
          throw new InfraException('Unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . ' não possui acesso ao processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . '.', null, null, false);
        }

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->retNumIdAtividade();
        $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
        $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setDthConclusao(null);

        if ($objAtividadeRN->consultarRN0033($objAtividadeDTO) == null) {
          $objReabrirProcessoDTO = new ReabrirProcessoDTO();
          $objReabrirProcessoDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
          $objReabrirProcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objReabrirProcessoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
          $objProcedimentoRN->reabrirRN0966($objReabrirProcessoDTO);
          $bolReabriuAutomaticamente = true;
        }
      }

      $objInfraException->lancarValidacoes();

      $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
      $objTipoProcedimentoDTO->retStrStaNivelAcessoSugestao();
      $objTipoProcedimentoDTO->retStrStaGrauSigiloSugestao();
      $objTipoProcedimentoDTO->retNumIdHipoteseLegalSugestao();
      $objTipoProcedimentoDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());

      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);

      if (InfraString::isBolVazia($objDocumentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal()) || $objDocumentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal() == $objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao()) {
        $objDocumentoDTO->getObjProtocoloDTO()->setStrStaNivelAcessoLocal($objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao());
        $objDocumentoDTO->getObjProtocoloDTO()->setStrStaGrauSigilo($objTipoProcedimentoDTO->getStrStaGrauSigiloSugestao());
        $objDocumentoDTO->getObjProtocoloDTO()->setNumIdHipoteseLegal($objTipoProcedimentoDTO->getNumIdHipoteseLegalSugestao());
      }

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null) {
        if ($objDocumentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal() == ProtocoloRN::$NA_SIGILOSO && $objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
          $objInfraException->lancarValidacao('A inclusão de documento sigiloso através de Web Services só é permitida se o processo também for sigiloso.');
        }
      }

      $objDocumentoDTO->getObjProtocoloDTO()->setArrObjParticipanteDTO($this->prepararParticipantes($objDocumentoDTO->getObjProtocoloDTO()->getArrObjParticipanteDTO()));

      $objDocumentoDTO->getObjProtocoloDTO()->setArrObjRelProtocoloAtributoDTO($this->prepararAtributos($objDocumentoDTO->getObjProtocoloDTO()->getArrObjRelProtocoloAtributoDTO(), $objSerieDTO));

      $objDocumentoRN = new DocumentoRN();

      $strSinBloqueado = $objDocumentoDTO->getStrSinBloqueado();


      if ($objDocumentoDTO->getObjProtocoloDTO()->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
        if ($objSerieDTO->getStrStaAplicabilidade() == SerieRN::$TA_FORMULARIO) {
          $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_FORMULARIO_GERADO);
        } else {
          $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EDITOR_INTERNO);
        }

        if (strlen($objDocumentoAPI->getConteudo()) > 0) {
          $objDocumentoDTO->setStrConteudo(utf8_decode(base64_decode($objDocumentoAPI->getConteudo())));
        } else {
          if ($objDocumentoAPI->getConteudoSecoes() != null) {
            $arrObjSecaoDocumentoAPI = $objDocumentoAPI->getConteudoSecoes();
            $arrSecaoConteudo = array();
            foreach ($arrObjSecaoDocumentoAPI as $objSecaoDocumentoAPI) {
              $arrSecaoConteudo[$objSecaoDocumentoAPI->getNome()] = utf8_decode(base64_decode($objSecaoDocumentoAPI->getConteudo()));
            }
            $objDocumentoDTO->setArrSecaoConteudo($arrSecaoConteudo);
          }
        }

        $objDocumentoDTOGerado = $objDocumentoRN->cadastrarRN0003($objDocumentoDTO);
      } else {
        if ($objDocumentoDTO->getObjProtocoloDTO()->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
          $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EXTERNO);

          $arrObjAnexoDTO = $objDocumentoDTO->getObjProtocoloDTO()->getArrObjAnexoDTO();

          if (InfraArray::contar($arrObjAnexoDTO) == 1) {
            if (!$arrObjAnexoDTO[0]->isSetNumIdAnexoOrigem()) {
              $objAnexoRN = new AnexoRN();
              $strNomeArquivoUpload = $objAnexoRN->gerarNomeArquivoTemporario();

              $fp = fopen(DIR_SEI_TEMP . '/' . $strNomeArquivoUpload, 'w');

              if (strlen($objDocumentoAPI->getConteudoMTOM()) > 0) {
                fwrite($fp, $objDocumentoAPI->getConteudoMTOM());
              } else {
                fwrite($fp, base64_decode($objDocumentoAPI->getConteudo()));
              }

              fclose($fp);

              $arrObjAnexoDTO[0]->setNumIdAnexo($strNomeArquivoUpload);
              $arrObjAnexoDTO[0]->setDthInclusao(InfraData::getStrDataHoraAtual());
              $arrObjAnexoDTO[0]->setNumTamanho(filesize(DIR_SEI_TEMP . '/' . $strNomeArquivoUpload));
              $arrObjAnexoDTO[0]->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
            }
          }

          $objDocumentoDTOGerado = $objDocumentoRN->cadastrarRN0003($objDocumentoDTO);
        }
      }

      if ($strSinBloqueado == 'S') {
        $objDocumentoRN->bloquearConteudo($objDocumentoDTOGerado);
      }

      $objAtividadeDTOVisualizacao = new AtividadeDTO();
      $objAtividadeDTOVisualizacao->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
      $objAtividadeDTOVisualizacao->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      if (!$bolReabriuAutomaticamente) {
        $objAtividadeDTOVisualizacao->setNumTipoVisualizacao(AtividadeRN::$TV_ATENCAO);
      } else {
        $objAtividadeDTOVisualizacao->setNumTipoVisualizacao(AtividadeRN::$TV_NAO_VISUALIZADO | AtividadeRN::$TV_ATENCAO);
      }

      $objAtividadeRN = new AtividadeRN();
      $objAtividadeRN->atualizarVisualizacaoUnidade($objAtividadeDTOVisualizacao);

      $objSaidaIncluirDocumentoAPI = new SaidaIncluirDocumentoAPI();
      $objSaidaIncluirDocumentoAPI->setIdDocumento($objDocumentoDTOGerado->getDblIdDocumento());
      $objSaidaIncluirDocumentoAPI->setDocumentoFormatado($objDocumentoDTOGerado->getStrProtocoloDocumentoFormatado());

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null && SessaoSEI::getInstance()->getObjServicoDTO()->getStrSinLinkExterno() == 'S') {
        $objAcessoExternoDTO = $this->obterAcessoExternoSistema($objDocumentoDTO->getDblIdProcedimento());
        $objSaidaIncluirDocumentoAPI->setLinkAcesso(SessaoSEIExterna::getInstance($objAcessoExternoDTO->getNumIdAcessoExterno())->assinarLink(ConfiguracaoSEI::getInstance()->getValor('SEI',
            'URL') . '/documento_consulta_externa.php?id_acesso_externo=' . $objAcessoExternoDTO->getNumIdAcessoExterno() . '&id_documento=' . $objDocumentoDTOGerado->getDblIdDocumento()));
      } else {
        $objSaidaIncluirDocumentoAPI->setLinkAcesso(ConfiguracaoSEI::getInstance()->getValor('SEI',
            'URL') . '/controlador.php?acao=procedimento_trabalhar&id_procedimento=' . $objDocumentoDTO->getDblIdProcedimento() . '&id_documento=' . $objDocumentoDTOGerado->getDblIdDocumento());
      }

      return $objSaidaIncluirDocumentoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando inclusão de documento.', $e);
    }
  }

  protected function listarExtensoesPermitidasConectado(EntradaListarExtensoesPermitidasAPI $objEntradaListarExtensoesPermitidasAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_EXTENSOES_PERMITIDAS);

      $objArquivoExtensaoDTO = new ArquivoExtensaoDTO();
      $objArquivoExtensaoDTO->retNumIdArquivoExtensao();
      $objArquivoExtensaoDTO->retStrExtensao();
      $objArquivoExtensaoDTO->retStrDescricao();

      if ($objEntradaListarExtensoesPermitidasAPI->getIdArquivoExtensao() != null) {
        $objArquivoExtensaoDTO->setNumIdArquivoExtensao($objEntradaListarExtensoesPermitidasAPI->getIdArquivoExtensao());
      }

      if (SessaoSEI::getInstance()->isBolHabilitada()) {
        $objArquivoExtensaoDTO->setStrSinInterface('S');
      } else {
        $objArquivoExtensaoDTO->setStrSinServico('S');
      }

      $objArquivoExtensaoDTO->setOrdStrExtensao(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objArquivoExtensaoRN = new ArquivoExtensaoRN();
      $arrObjArquivoExtensaoDTO = $objArquivoExtensaoRN->listar($objArquivoExtensaoDTO);

      $ret = array();
      foreach ($arrObjArquivoExtensaoDTO as $objArquivoExtensaoDTO) {
        $objArquivoExtensaoAPI = new ArquivoExtensaoAPI();
        $objArquivoExtensaoAPI->setIdArquivoExtensao($objArquivoExtensaoDTO->getNumIdArquivoExtensao());
        $objArquivoExtensaoAPI->setExtensao($objArquivoExtensaoDTO->getStrExtensao());
        $objArquivoExtensaoAPI->setDescricao($objArquivoExtensaoDTO->getStrDescricao());
        $ret[] = $objArquivoExtensaoAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de extensões permitidas.', $e);
    }
  }

  protected function listarHipotesesLegaisConectado(EntradaListarHipotesesLegaisAPI $objEntradaListarHipotesesLegaisAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_HIPOTESES_LEGAIS);

      $objHipoteseLegalDTO = new HipoteseLegalDTO();
      $objHipoteseLegalDTO->retNumIdHipoteseLegal();
      $objHipoteseLegalDTO->retStrNome();
      $objHipoteseLegalDTO->retStrBaseLegal();
      $objHipoteseLegalDTO->retStrStaNivelAcesso();

      if ($objEntradaListarHipotesesLegaisAPI->getNivelAcesso() != null) {
        $objHipoteseLegalDTO->setStrStaNivelAcesso($objEntradaListarHipotesesLegaisAPI->getNivelAcesso());
      }

      $objHipoteseLegalDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objHipoteseLegalRN = new HipoteseLegalRN();
      $arrObjHipoteseLegalDTO = $objHipoteseLegalRN->listar($objHipoteseLegalDTO);

      $ret = array();
      foreach ($arrObjHipoteseLegalDTO as $objHipoteseLegalDTO) {
        $objHipoteseLegalAPI = new HipoteseLegalAPI();
        $objHipoteseLegalAPI->setIdHipoteseLegal($objHipoteseLegalDTO->getNumIdHipoteseLegal());
        $objHipoteseLegalAPI->setNome($objHipoteseLegalDTO->getStrNome());
        $objHipoteseLegalAPI->setBaseLegal($objHipoteseLegalDTO->getStrBaseLegal());
        $objHipoteseLegalAPI->setNivelAcesso($objHipoteseLegalDTO->getStrStaNivelAcesso());
        $ret[] = $objHipoteseLegalAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de hipóteses legais.', $e);
    }
  }

  protected function listarTiposConferenciaConectado() {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_TIPOS_CONFERENCIA);

      $objTipoConferenciaDTO = new TipoConferenciaDTO();
      $objTipoConferenciaDTO->retNumIdTipoConferencia();
      $objTipoConferenciaDTO->retStrDescricao();
      $objTipoConferenciaDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objTipoConferenciaRN = new TipoConferenciaRN();
      $arrObjTipoConferenciaDTO = $objTipoConferenciaRN->listar($objTipoConferenciaDTO);

      $ret = array();

      foreach ($arrObjTipoConferenciaDTO as $objTipoConferenciaDTO) {
        $objTipoConferenciaAPI = new TipoConferenciaAPI();
        $objTipoConferenciaAPI->setIdTipoConferencia($objTipoConferenciaDTO->getNumIdTipoConferencia());
        $objTipoConferenciaAPI->setDescricao($objTipoConferenciaDTO->getStrDescricao());
        $ret[] = $objTipoConferenciaAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de tipos de conferência.', $e);
    }
  }

  protected function listarUsuariosConectado(EntradaListarUsuariosAPI $objEntradaListarUsuariosAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_USUARIOS);

      $objUsuarioRN = new UsuarioRN();

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $arrObjUsuarioDTO = $objUsuarioRN->listarPorUnidadeRN0812($objUnidadeDTO);

      $numIdUsuario = $objEntradaListarUsuariosAPI->getIdUsuario();

      if ($numIdUsuario != null) {
        $arrObjUsuarioDTO = InfraArray::indexarArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario');
        if (isset($arrObjUsuarioDTO[$numIdUsuario])) {
          $arrObjUsuarioDTO = array($arrObjUsuarioDTO[$numIdUsuario]);
        } else {
          $arrObjUsuarioDTO = array();
        }
      }

      $ret = array();

      foreach ($arrObjUsuarioDTO as $objUsuarioDTO) {
        $objUsuarioAPI = new UsuarioAPI();
        $objUsuarioAPI->setIdUsuario($objUsuarioDTO->getNumIdUsuario());
        $objUsuarioAPI->setSigla($objUsuarioDTO->getStrSigla());
        $objUsuarioAPI->setNome($objUsuarioDTO->getStrNome());
        $ret[] = $objUsuarioAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de usuários.', $e);
    }
  }

  protected function listarPaisesConectado() {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_PAISES);

      $objPaisDTO = new PaisDTO();
      $objPaisDTO->retNumIdPais();
      $objPaisDTO->retStrNome();
      $objPaisDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objPaisRN = new PaisRN();
      $arrObjPaisDTO = $objPaisRN->listar($objPaisDTO);

      $ret = array();

      foreach ($arrObjPaisDTO as $objPaisDTO) {
        $objPaisAPI = new PaisAPI();
        $objPaisAPI->setIdPais($objPaisDTO->getNumIdPais());
        $objPaisAPI->setNome($objPaisDTO->getStrNome());
        $ret[] = $objPaisAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de países.', $e);
    }
  }

  protected function listarEstadosConectado(EntradaListarEstadosAPI $objEntradaListarEstadosAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_ESTADOS);

      $objUfDTO = new UfDTO();
      $objUfDTO->retNumIdPais();
      $objUfDTO->retNumIdUf();
      $objUfDTO->retStrSigla();
      $objUfDTO->retStrNome();
      $objUfDTO->retNumCodigoIbge();

      if ($objEntradaListarEstadosAPI->getIdPais() != null) {
        $objUfDTO->setNumIdPais($objEntradaListarEstadosAPI->getIdPais());
      }

      $objUfDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objUfRN = new UfRN();
      $arrObjUfDTO = $objUfRN->listarRN0401($objUfDTO);

      $ret = array();

      foreach ($arrObjUfDTO as $objUfDTO) {
        $objEstadoAPI = new EstadoAPI();
        $objEstadoAPI->setIdEstado($objUfDTO->getNumIdUf());
        $objEstadoAPI->setIdPais($objUfDTO->getNumIdPais());
        $objEstadoAPI->setSigla($objUfDTO->getStrSigla());
        $objEstadoAPI->setNome($objUfDTO->getStrNome());
        $objEstadoAPI->setCodigoIbge($objUfDTO->getNumCodigoIbge());
        $ret[] = $objEstadoAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de estados.', $e);
    }
  }

  protected function listarCidadesConectado(EntradaListarCidadesAPI $objEntradaListarCidadesAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_CIDADES);

      $objCidadeDTO = new CidadeDTO();
      $objCidadeDTO->retNumIdCidade();
      $objCidadeDTO->retNumIdUf();
      $objCidadeDTO->retNumIdPais();
      $objCidadeDTO->retStrNome();
      $objCidadeDTO->retNumCodigoIbge();
      $objCidadeDTO->retStrSinCapital();
      $objCidadeDTO->retDblLatitude();
      $objCidadeDTO->retDblLongitude();

      if ($objEntradaListarCidadesAPI->getIdPais() != null) {
        $objCidadeDTO->setNumIdPais($objEntradaListarCidadesAPI->getIdPais());
      }

      if ($objEntradaListarCidadesAPI->getIdEstado() != null) {
        $objCidadeDTO->setNumIdUf($objEntradaListarCidadesAPI->getIdEstado());
      }

      $objCidadeDTO->setOrdStrSinCapital(InfraDTO::$TIPO_ORDENACAO_DESC);
      $objCidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objCidadeRN = new CidadeRN();
      $arrObjCidadeDTO = $objCidadeRN->listarRN0410($objCidadeDTO);

      $ret = array();

      foreach ($arrObjCidadeDTO as $objCidadeDTO) {
        $objCidadeAPI = new CidadeAPI();
        $objCidadeAPI->setIdCidade($objCidadeDTO->getNumIdCidade());
        $objCidadeAPI->setIdEstado($objCidadeDTO->getNumIdUf());
        $objCidadeAPI->setIdPais($objCidadeDTO->getNumIdPais());
        $objCidadeAPI->setNome($objCidadeDTO->getStrNome());
        $objCidadeAPI->setCodigoIbge($objCidadeDTO->getNumCodigoIbge());
        $objCidadeAPI->setSinCapital($objCidadeDTO->getStrSinCapital());
        $objCidadeAPI->setLatitude($objCidadeDTO->getDblLatitude());
        $objCidadeAPI->setLongitude($objCidadeDTO->getDblLongitude());
        $ret[] = $objCidadeAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de cidades.', $e);
    }
  }

  protected function listarCargosConectado(EntradaListarCargosAPI $objEntradaListarCargosAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_CARGOS);

      $objCargoDTO = new CargoDTO();
      $objCargoDTO->retNumIdCargo();
      $objCargoDTO->retStrExpressao();
      $objCargoDTO->retStrExpressaoTratamento();
      $objCargoDTO->retStrExpressaoVocativo();
      $objCargoDTO->retStrExpressaoTitulo();
      $objCargoDTO->retStrAbreviaturaTitulo();

      if ($objEntradaListarCargosAPI->getIdCargo() != null) {
        $objCargoDTO->setNumIdCargo($objEntradaListarCargosAPI->getIdCargo());
      }

      $objCargoDTO->setOrdStrExpressao(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objCargoRN = new CargoRN();
      $arrObjCargoDTO = $objCargoRN->listarRN0302($objCargoDTO);

      $ret = array();

      foreach ($arrObjCargoDTO as $objCargoDTO) {
        $objCargoAPI = new CargoAPI();
        $objCargoAPI->setIdCargo($objCargoDTO->getNumIdCargo());
        $objCargoAPI->setExpressaoCargo($objCargoDTO->getStrExpressao());
        $objCargoAPI->setExpressaoTratamento($objCargoDTO->getStrExpressaoTratamento());
        $objCargoAPI->setExpressaoVocativo($objCargoDTO->getStrExpressaoVocativo());
        $objCargoAPI->setExpressaoTitulo($objCargoDTO->getStrExpressaoTitulo());
        $objCargoAPI->setAbreviaturaTitulo($objCargoDTO->getStrAbreviaturaTitulo());
        $ret[] = $objCargoAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de cargos.', $e);
    }
  }

  protected function listarContatosConectado(EntradaListarContatosAPI $objEntradaListarContatosAPI) {
    try {
      $objInfraException = new InfraException();

      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_CONTATOS);

      $numPaginaRegistros = $objEntradaListarContatosAPI->getPaginaRegistros();
      $numPaginaAtual = $objEntradaListarContatosAPI->getPaginaAtual();

      if (is_array($objEntradaListarContatosAPI->getIdContatos())) {
        $arrIdContatos = $objEntradaListarContatosAPI->getIdContatos();
      } else {
        if (!InfraString::isBolVazia($objEntradaListarContatosAPI->getIdContatos())) {
          $arrIdContatos = array($objEntradaListarContatosAPI->getIdContatos());
        } else {
          $arrIdContatos = array();
        }
      }

      if (!InfraString::isBolVazia($objEntradaListarContatosAPI->getIdTipoContato())) {
        $objTipoContatoDTO = new TipoContatoDTO();
        $objTipoContatoDTO->setBolExclusaoLogica(false);
        $objTipoContatoDTO->setNumIdTipoContato($objEntradaListarContatosAPI->getIdTipoContato());

        $objTipoContatoRN = new TipoContatoRN();
        if ($objTipoContatoRN->contarRN0353($objTipoContatoDTO) == 0) {
          $objInfraException->lancarValidacao('Tipo de contato [' . $objEntradaListarContatosAPI->getIdTipoContato() . '] não encontrado.');
        }
      }

      if (InfraString::isBolVazia($numPaginaRegistros)) {
        $numPaginaRegistros = 1;
      } else {
        if (!is_numeric($numPaginaRegistros) || $numPaginaRegistros <= 0 || $numPaginaRegistros > 1000) {
          $objInfraException->lancarValidacao('O número de registros por página deve ser um valor entre 1 e 1000.');
        }
      }

      if (InfraString::isBolVazia($numPaginaAtual)) {
        $numPaginaAtual = 0;
      } else {
        if (!is_numeric($numPaginaAtual) || $numPaginaAtual < 1) {
          $objInfraException->lancarValidacao('Identificador da página atual inválido.');
        }
        $numPaginaAtual--;
      }

      $objContatoDTO = new ContatoDTO();

      $objContatoDTO->setBolExclusaoLogica(false);
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retNumIdTipoContato();
      $objContatoDTO->retNumIdTipoContatoAssociado();
      $objContatoDTO->retStrNomeTipoContato();
      $objContatoDTO->retNumIdContatoAssociado();
      $objContatoDTO->retStrSinEnderecoAssociado();
      $objContatoDTO->retStrSinEnderecoAssociadoAssociado();
      $objContatoDTO->retStrNomeContatoAssociado();
      $objContatoDTO->retDblCnpjContatoAssociado();
      $objContatoDTO->retNumIdCargo();
      $objContatoDTO->retStrExpressaoCargo();
      $objContatoDTO->retStrExpressaoTratamentoCargo();
      $objContatoDTO->retStrExpressaoVocativoCargo();
      $objContatoDTO->retStrStaNatureza();
      $objContatoDTO->retStrNome();
      $objContatoDTO->retStrNomeSocial();
      $objContatoDTO->retStrSigla();
      $objContatoDTO->retStrStaGenero();
      $objContatoDTO->retDblCpf();
      $objContatoDTO->retDblRg();
      $objContatoDTO->retStrOrgaoExpedidor();
      $objContatoDTO->retDtaNascimento();
      $objContatoDTO->retStrMatricula();
      $objContatoDTO->retStrMatriculaOab();
      $objContatoDTO->retStrTelefoneComercial();
      $objContatoDTO->retStrTelefoneResidencial();
      $objContatoDTO->retStrTelefoneCelular();
      $objContatoDTO->retDblCnpj();
      $objContatoDTO->retStrEmail();
      $objContatoDTO->retStrSitioInternet();
      $objContatoDTO->retStrObservacao();
      $objContatoDTO->retStrSinAtivo();
      $objContatoDTO->retStrNumeroPassaporte();
      $objContatoDTO->retNumIdPaisPassaporte();
      $objContatoDTO->retStrNomePaisPassaporte();
      $objContatoDTO->retStrConjuge();
      $objContatoDTO->retStrFuncao();
      $objContatoDTO->retNumIdTitulo();
      $objContatoDTO->retStrAbreviaturaTituloContato();
      $objContatoDTO->retStrExpressaoTituloContato();
      $objContatoDTO->retNumIdCategoria();
      $objContatoDTO->retStrNomeCategoria();

      $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      if (InfraArray::contar($arrIdContatos)) {
        $objContatoDTO->setNumIdContato($arrIdContatos, InfraDTO::$OPER_IN);
      }

      if (!InfraString::isBolVazia($objEntradaListarContatosAPI->getSigla())) {
        $objContatoDTO->setStrSigla($objEntradaListarContatosAPI->getSigla());
      }

      if (!InfraString::isBolVazia($objEntradaListarContatosAPI->getNome())) {
        $objContatoDTO->setStrNome($objEntradaListarContatosAPI->getNome());
      }

      if (!InfraString::isBolVazia($objEntradaListarContatosAPI->getCpf())) {
        $objContatoDTO->setDblCpf($objEntradaListarContatosAPI->getCpf());
      }

      if (!InfraString::isBolVazia($objEntradaListarContatosAPI->getCnpj())) {
        $objContatoDTO->setDblCnpj($objEntradaListarContatosAPI->getCnpj());
      }

      if (!InfraString::isBolVazia($objEntradaListarContatosAPI->getMatricula())) {
        $objContatoDTO->setStrMatricula($objEntradaListarContatosAPI->getMatricula());
      }

      if (!InfraString::isBolVazia($objEntradaListarContatosAPI->getIdTipoContato())) {
        $objContatoDTO->setNumIdTipoContato($objEntradaListarContatosAPI->getIdTipoContato());
      }

      if ($numPaginaAtual) {
        $objContatoDTO->setNumPaginaAtual($numPaginaAtual);
      }

      $objContatoDTO->setNumMaxRegistrosRetorno($numPaginaRegistros);

      $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objContatoRN = new ContatoRN();
      $arrObjContatoDTO = $objContatoRN->listarComEndereco($objContatoDTO);

      $ret = array();


      foreach ($arrObjContatoDTO as $objContatoDTO) {
        $objContatoAPI = new ContatoAPI();
        $objContatoAPI->setIdContato($objContatoDTO->getNumIdContato());
        $objContatoAPI->setIdTipoContato($objContatoDTO->getNumIdTipoContato());
        $objContatoAPI->setNomeTipoContato($objContatoDTO->getStrNomeTipoContato());
        $objContatoAPI->setSigla($objContatoDTO->getStrSigla());
        $objContatoAPI->setNome($objContatoDTO->getStrNome());
        $objContatoAPI->setNomeSocial($objContatoDTO->getStrNomeSocial());
        $objContatoAPI->setStaNatureza($objContatoDTO->getStrStaNatureza());
        $objContatoAPI->setSinEnderecoAssociado($objContatoDTO->getStrSinEnderecoAssociado());
        $objContatoAPI->setIdContatoAssociado($objContatoDTO->getNumIdContatoAssociado());
        $objContatoAPI->setNomeContatoAssociado($objContatoDTO->getStrNomeContatoAssociado());
        $objContatoAPI->setCnpjAssociado($objContatoDTO->getDblCnpjContatoAssociado());
        $objContatoAPI->setEndereco($objContatoDTO->getStrEndereco());
        $objContatoAPI->setComplemento($objContatoDTO->getStrComplemento());
        $objContatoAPI->setBairro($objContatoDTO->getStrBairro());
        $objContatoAPI->setIdCidade($objContatoDTO->getNumIdCidade());
        $objContatoAPI->setNomeCidade($objContatoDTO->getStrNomeCidade());
        $objContatoAPI->setIdEstado($objContatoDTO->getNumIdUf());
        $objContatoAPI->setSiglaEstado($objContatoDTO->getStrSiglaUf());
        $objContatoAPI->setIdPais($objContatoDTO->getNumIdPais());
        $objContatoAPI->setNomePais($objContatoDTO->getStrNomePais());
        $objContatoAPI->setCep($objContatoDTO->getStrCep());
        $objContatoAPI->setStaGenero($objContatoDTO->getStrStaGenero());
        $objContatoAPI->setIdCargo($objContatoDTO->getNumIdCargo());
        $objContatoAPI->setExpressaoCargo($objContatoDTO->getStrExpressaoCargo());
        $objContatoAPI->setExpressaoTratamento($objContatoDTO->getStrExpressaoTratamentoCargo());
        $objContatoAPI->setExpressaoVocativo($objContatoDTO->getStrExpressaoVocativoCargo());
        $objContatoAPI->setCpf($objContatoDTO->getDblCpf());
        $objContatoAPI->setCnpj($objContatoDTO->getDblCnpj());
        $objContatoAPI->setRg($objContatoDTO->getDblRg());
        $objContatoAPI->setOrgaoExpedidor($objContatoDTO->getStrOrgaoExpedidor());
        $objContatoAPI->setMatricula($objContatoDTO->getStrMatricula());
        $objContatoAPI->setMatriculaOab($objContatoDTO->getStrMatriculaOab());
        $objContatoAPI->setTelefoneComercial($objContatoDTO->getStrTelefoneComercial());
        $objContatoAPI->setTelefoneResidencial($objContatoDTO->getStrTelefoneResidencial());
        $objContatoAPI->setTelefoneCelular($objContatoDTO->getStrTelefoneCelular());
        $objContatoAPI->setDataNascimento($objContatoDTO->getDtaNascimento());
        $objContatoAPI->setEmail($objContatoDTO->getStrEmail());
        $objContatoAPI->setSitioInternet($objContatoDTO->getStrSitioInternet());
        $objContatoAPI->setObservacao($objContatoDTO->getStrObservacao());
        $objContatoAPI->setSinAtivo($objContatoDTO->getStrSinAtivo());
        $objContatoAPI->setNumeroPassaporte($objContatoDTO->getStrNumeroPassaporte());
        $objContatoAPI->setIdPaisPassaporte($objContatoDTO->getNumIdPaisPassaporte());
        $objContatoAPI->setNomePaisPassaporte($objContatoDTO->getStrNomePaisPassaporte());
        $objContatoAPI->setConjuge($objContatoDTO->getStrConjuge());
        $objContatoAPI->setFuncao($objContatoDTO->getStrFuncao());
        $objContatoAPI->setIdTitulo($objContatoDTO->getNumIdTitulo());
        $objContatoAPI->setAbreviaturaTitulo($objContatoDTO->getStrAbreviaturaTituloContato());
        $objContatoAPI->setExpressaoTitulo($objContatoDTO->getStrExpressaoTituloContato());
        $objContatoAPI->setIdCategoria($objContatoDTO->getNumIdCategoria());
        $objContatoAPI->setNomeCategoria($objContatoDTO->getStrNomeCategoria());

        $ret[] = $objContatoAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de contatos.', $e);
    }
  }

  protected function listarMarcadoresUnidadeConectado() {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_MARCADORES_UNIDADE);

      $objMarcadorDTO = new MarcadorDTO();
      $objMarcadorDTO->setBolExclusaoLogica(false);
      $objMarcadorDTO->retNumIdMarcador();
      $objMarcadorDTO->retStrNome();
      $objMarcadorDTO->retStrStaIcone();
      $objMarcadorDTO->retStrSinAtivo();
      $objMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objMarcadorDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objMarcadorRN = new MarcadorRN();
      $arrObjMarcadorDTO = $objMarcadorRN->listar($objMarcadorDTO);

      $ret = array();

      if (count($arrObjMarcadorDTO)) {
        $arrIcones = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(), 'StaIcone');

        foreach ($arrObjMarcadorDTO as $objMarcadorDTO) {
          $objMarcadorAPI = new MarcadorAPI();
          $objMarcadorAPI->setIdMarcador($objMarcadorDTO->getNumIdMarcador());
          $objMarcadorAPI->setNome($objMarcadorDTO->getStrNome());
          $objMarcadorAPI->setIcone('data:image/png;base64,' . base64_encode(file_get_contents(dirname(__FILE__) . '/../' . $arrIcones[$objMarcadorDTO->getStrStaIcone()]->getStrArquivo())));
          $objMarcadorAPI->setSinAtivo($objMarcadorDTO->getStrSinAtivo());
          $ret[] = $objMarcadorAPI;
        }
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de marcadores da unidade.', $e);
    }
  }

  protected function definirMarcadorControlado($arrObjDefinicaoMarcadorAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_DEFINIR_MARCADOR);

      $objAndamentoMarcadorRN = new AndamentoMarcadorRN();

      foreach ($arrObjDefinicaoMarcadorAPI as $objDefinicaoMarcadorAPI) {
        $objProcedimentoDTO = $this->obterProcesso($objDefinicaoMarcadorAPI->getIdProcedimento(), $objDefinicaoMarcadorAPI->getProtocoloProcedimento());

        $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
        $objAndamentoMarcadorDTO->setDblIdProcedimento(array($objProcedimentoDTO->getDblIdProcedimento()));
        $objAndamentoMarcadorDTO->setNumIdMarcador($objDefinicaoMarcadorAPI->getIdMarcador());
        $objAndamentoMarcadorDTO->setStrTexto($objDefinicaoMarcadorAPI->getTexto());
        $objAndamentoMarcadorRN->cadastrar($objAndamentoMarcadorDTO);
      }
    } catch (Throwable $e) {
      throw new InfraException('Erro processando definição de marcador.', $e);
    }
  }

  protected function definirControlePrazoControlado($arrObjEntradaDefinirControlePrazoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_DEFINIR_CONTROLE_PRAZO);


      $objControlePrazoRN = new ControlePrazoRN();

      foreach ($arrObjEntradaDefinirControlePrazoAPI as $objEntradaDefinirControlePrazoAPI) {
        $objProcedimentoDTO = $this->obterProcesso($objEntradaDefinirControlePrazoAPI->getIdProcedimento(), $objEntradaDefinirControlePrazoAPI->getProtocoloProcedimento());

        $objControlePrazoDTO = new ControlePrazoDTO();
        $objControlePrazoDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objControlePrazoDTO->setDtaPrazo($objEntradaDefinirControlePrazoAPI->getDataPrazo());
        $objControlePrazoDTO->setNumDias($objEntradaDefinirControlePrazoAPI->getDias());
        $objControlePrazoDTO->setStrSinDiasUteis($objEntradaDefinirControlePrazoAPI->getSinDiasUteis());

        $objControlePrazoRN->definir(array($objControlePrazoDTO));
      }
    } catch (Throwable $e) {
      throw new InfraException('Erro processando definição de controle de prazo.', $e);
    }
  }

  protected function registrarAnotacaoControlado($arrObjEntradaRegistrarAnotacaoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_REGISTRAR_ANOTACAO);

      $objAnotacaoRN = new AnotacaoRN();

      foreach ($arrObjEntradaRegistrarAnotacaoAPI as $objEntradaRegistrarAnotacaoAPI) {
        $objProcedimentoDTO = $this->obterProcesso($objEntradaRegistrarAnotacaoAPI->getIdProcedimento(), $objEntradaRegistrarAnotacaoAPI->getProtocoloProcedimento());

        $objAnotacaoDTO = new AnotacaoDTO();
        $objAnotacaoDTO->setStrDescricao($objEntradaRegistrarAnotacaoAPI->getDescricao());
        $objAnotacaoDTO->setStrSinPrioridade($objEntradaRegistrarAnotacaoAPI->getSinPrioridade());
        $objAnotacaoDTO->setDblIdProtocolo(array($objProcedimentoDTO->getDblIdProcedimento()));
        $objAnotacaoRN->registrar($objAnotacaoDTO);
      }
    } catch (Throwable $e) {
      throw new InfraException('Erro processando registro de anotação.', $e);
    }
  }

  protected function concluirControlePrazoControlado($arrObjEntradaConcluirControlePrazoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_CONCLUIR_CONTROLE_PRAZO);

      $arrConclusao = array();

      $objControlePrazoRN = new ControlePrazoRN();

      foreach ($arrObjEntradaConcluirControlePrazoAPI as $objEntradaConcluirControlePrazoAPI) {
        $objProcedimentoDTO = $this->obterProcesso($objEntradaConcluirControlePrazoAPI->getIdProcedimento(), $objEntradaConcluirControlePrazoAPI->getProtocoloProcedimento());

        $objControlePrazoDTO = new ControlePrazoDTO();
        $objControlePrazoDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $arrConclusao[] = $objControlePrazoDTO;
      }

      $objControlePrazoRN->concluir($arrConclusao);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando conclusão de controle de prazo.', $e);
    }
  }

  protected function removerControlePrazoControlado($arrObjEntradaRemoverControlePrazoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_REMOVER_CONTROLE_PRAZO);

      $arrExclusao = array();

      $objControlePrazoRN = new ControlePrazoRN();

      foreach ($arrObjEntradaRemoverControlePrazoAPI as $objEntradaRemoverControlePrazoAPI) {
        $objProcedimentoDTO = $this->obterProcesso($objEntradaRemoverControlePrazoAPI->getIdProcedimento(), $objEntradaRemoverControlePrazoAPI->getProtocoloProcedimento());

        $objControlePrazoDTO = new ControlePrazoDTO();
        $objControlePrazoDTO->retNumIdControlePrazo();
        $objControlePrazoDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objControlePrazoDTO_Consulta = $objControlePrazoRN->consultar($objControlePrazoDTO);

        if ($objControlePrazoDTO_Consulta == null) {
          throw new InfraException('Controle de Prazo para o processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' não encontrado na unidade.');
        }

        $arrExclusao[] = $objControlePrazoDTO_Consulta;
      }

      $objControlePrazoRN->excluir($arrExclusao);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando remoção de controle de prazo.', $e);
    }
  }

  protected function listarAndamentosMarcadoresConectado(EntradaListarAndamentosMarcadoresAPI $objEntradaListarAndamentosMarcadoresAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_ANDAMENTOS_MARCADORES);

      $objProcedimentoDTO = $this->obterProcesso($objEntradaListarAndamentosMarcadoresAPI->getIdProcedimento(), $objEntradaListarAndamentosMarcadoresAPI->getProtocoloProcedimento());

      $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
      $objAndamentoMarcadorDTO->retNumIdMarcador();
      $objAndamentoMarcadorDTO->retStrNomeMarcador();
      $objAndamentoMarcadorDTO->retStrSinAtivoMarcador();
      $objAndamentoMarcadorDTO->retStrTexto();
      $objAndamentoMarcadorDTO->retDthExecucao();
      $objAndamentoMarcadorDTO->retNumIdUsuario();
      $objAndamentoMarcadorDTO->retStrSiglaUsuario();
      $objAndamentoMarcadorDTO->retStrNomeUsuario();
      $objAndamentoMarcadorDTO->retNumIdAndamentoMarcador();
      $objAndamentoMarcadorDTO->retStrStaIconeMarcador();
      $objAndamentoMarcadorDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objAndamentoMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      if ($objEntradaListarAndamentosMarcadoresAPI->getMarcadores() != null) {
        $arr = array_unique($objEntradaListarAndamentosMarcadoresAPI->getMarcadores());

        if (($posNull = array_search(null, $arr)) !== false) {
          unset($arr[$posNull]);

          $objAndamentoMarcadorDTO->adicionarCriterio(array('IdMarcador', 'IdMarcador'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IN), array(null, $arr), InfraDTO::$OPER_LOGICO_OR);
        } else {
          $objAndamentoMarcadorDTO->setNumIdMarcador($arr, InfraDTO::$OPER_IN);
        }
      }

      $objAndamentoMarcadorDTO->setOrdNumIdAndamentoMarcador(InfraDTO::$TIPO_ORDENACAO_DESC);

      $objAndamentoMarcadorRN = new AndamentoMarcadorRN();
      $arrObjAndamentoMarcadorDTO = $objAndamentoMarcadorRN->listar($objAndamentoMarcadorDTO);

      $ret = array();

      if (count($arrObjAndamentoMarcadorDTO)) {
        $objMarcadorRN = new MarcadorRN();

        $arrIcones = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(), 'StaIcone');
        $arrCacheIcones = array();

        foreach ($arrObjAndamentoMarcadorDTO as $objAndamentoMarcadorDTO) {
          $objAndamentoMarcadorAPI = new AndamentoMarcadorAPI();
          $objAndamentoMarcadorAPI->setIdAndamentoMarcador($objAndamentoMarcadorDTO->getNumIdAndamentoMarcador());
          $objAndamentoMarcadorAPI->setTexto($objAndamentoMarcadorDTO->getStrTexto());
          $objAndamentoMarcadorAPI->setDataHora($objAndamentoMarcadorDTO->getDthExecucao());

          $objUsuarioAPI = new UsuarioAPI();
          $objUsuarioAPI->setIdUsuario($objAndamentoMarcadorDTO->getNumIdUsuario());
          $objUsuarioAPI->setSigla($objAndamentoMarcadorDTO->getStrSiglaUsuario());
          $objUsuarioAPI->setNome($objAndamentoMarcadorDTO->getStrNomeUsuario());
          $objAndamentoMarcadorAPI->setUsuario($objUsuarioAPI);;

          if ($objAndamentoMarcadorDTO->getNumIdMarcador() != null) {
            $objMarcadorAPI = new MarcadorAPI();
            $objMarcadorAPI->setIdMarcador($objAndamentoMarcadorDTO->getNumIdMarcador());
            $objMarcadorAPI->setNome($objAndamentoMarcadorDTO->getStrNomeMarcador());

            if (!isset($arrCacheIcones[$objAndamentoMarcadorDTO->getStrStaIconeMarcador()])) {
              $arrCacheIcones[$objAndamentoMarcadorDTO->getStrStaIconeMarcador()] = 'data:image/png;base64,' . base64_encode(file_get_contents(dirname(__FILE__) . '/../' . $arrIcones[$objAndamentoMarcadorDTO->getStrStaIconeMarcador()]->getStrArquivo()));
            }

            $objMarcadorAPI->setIcone($arrCacheIcones[$objAndamentoMarcadorDTO->getStrStaIconeMarcador()]);
            $objMarcadorAPI->setSinAtivo($objAndamentoMarcadorDTO->getStrSinAtivoMarcador());

            $objAndamentoMarcadorAPI->setMarcador($objMarcadorAPI);
          } else {
            $objAndamentoMarcadorAPI->setMarcador(null);
          }

          $ret[] = $objAndamentoMarcadorAPI;
        }
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de marcadores da unidade.', $e);
    }
  }

  protected function atualizarContatosControlado($arrObjContatoAPI) {
    try {
      $ret = 0;

      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_ATUALIZAR_CONTATOS);

      if (InfraArray::contar($arrObjContatoAPI)) {
        $objInfraException = new InfraException();

        $arrObjContatoDTO = array();

        foreach ($arrObjContatoAPI as $objContatoAPI) {
          $objContatoDTO = new ContatoDTO();
          $objContatoDTO->setStrStaOperacao($objContatoAPI->getStaOperacao());
          $objContatoDTO->setNumIdContato($objContatoAPI->getIdContato());
          $objContatoDTO->setNumIdTipoContato($objContatoAPI->getIdTipoContato());
          $objContatoDTO->setStrSigla($objContatoAPI->getSigla());
          $objContatoDTO->setStrNome($objContatoAPI->getNome());
          $objContatoDTO->setStrNomeSocial($objContatoAPI->getNomeSocial());
          $objContatoDTO->setStrStaNatureza($objContatoAPI->getStaNatureza());
          $objContatoDTO->setNumIdContatoAssociado($objContatoAPI->getIdContatoAssociado());
          $objContatoDTO->setStrSinEnderecoAssociado($objContatoAPI->getSinEnderecoAssociado());
          $objContatoDTO->setStrEndereco($objContatoAPI->getEndereco());
          $objContatoDTO->setStrComplemento($objContatoAPI->getComplemento());
          $objContatoDTO->setStrBairro($objContatoAPI->getBairro());
          $objContatoDTO->setNumIdCidade($objContatoAPI->getIdCidade());
          $objContatoDTO->setNumIdUf($objContatoAPI->getIdEstado());
          $objContatoDTO->setNumIdPais($objContatoAPI->getIdPais());
          $objContatoDTO->setStrCep($objContatoAPI->getCep());
          $objContatoDTO->setStrStaGenero($objContatoAPI->getStaGenero());
          $objContatoDTO->setNumIdCargo($objContatoAPI->getIdCargo());
          $objContatoDTO->setDblCpf($objContatoAPI->getCpf());
          $objContatoDTO->setDblCnpj($objContatoAPI->getCnpj());
          $objContatoDTO->setDblRg($objContatoAPI->getRg());
          $objContatoDTO->setStrOrgaoExpedidor($objContatoAPI->getOrgaoExpedidor());
          $objContatoDTO->setStrMatricula($objContatoAPI->getMatricula());
          $objContatoDTO->setStrMatriculaOab($objContatoAPI->getMatriculaOab());
          $objContatoDTO->setStrTelefoneComercial($objContatoAPI->getTelefoneComercial());
          $objContatoDTO->setStrTelefoneResidencial($objContatoAPI->getTelefoneResidencial());
          $objContatoDTO->setStrTelefoneCelular($objContatoAPI->getTelefoneCelular());
          $objContatoDTO->setDtaNascimento($objContatoAPI->getDataNascimento());
          $objContatoDTO->setStrEmail($objContatoAPI->getEmail());
          $objContatoDTO->setStrSitioInternet($objContatoAPI->getSitioInternet());
          $objContatoDTO->setStrObservacao($objContatoAPI->getObservacao());
          $objContatoDTO->setStrSinAtivo($objContatoAPI->getSinAtivo());
          $objContatoDTO->setStrNumeroPassaporte($objContatoAPI->getNumeroPassaporte());
          $objContatoDTO->setNumIdPaisPassaporte($objContatoAPI->getIdPaisPassaporte());
          $objContatoDTO->setStrFuncao($objContatoAPI->getFuncao());
          $objContatoDTO->setStrConjuge($objContatoAPI->getConjuge());
          $objContatoDTO->setNumIdTitulo($objContatoAPI->getIdTitulo());
          $objContatoDTO->setStrAbreviaturaTituloContato($objContatoAPI->getAbreviaturaTitulo());
          $objContatoDTO->setStrExpressaoTituloContato($objContatoAPI->getExpressaoTitulo());
          $objContatoDTO->setNumIdCategoria($objContatoAPI->getIdCategoria());
          $objContatoDTO->setStrNomeCategoria($objContatoAPI->getNomeCategoria());

          $arrObjContatoDTO[] = $objContatoDTO;
        }

        $objContatoRN = new ContatoRN();

        $n = 0;
        foreach ($arrObjContatoDTO as $objContatoDTO) {
          try {
            $objInfraExceptionContato = new InfraException();

            $StaOperacao = $objContatoDTO->getStrStaOperacao();

            if (InfraString::isBolVazia($StaOperacao)) {
              $objInfraExceptionContato->lancarValidacao('Operação não informada.');
            }

            $SinAtivo = $objContatoDTO->getStrSinAtivo();

            $objContatoDTOBanco = null;

            if ($objContatoDTO->getNumIdContato() != null) {
              $objContatoDTOBanco = new ContatoDTO();
              $objContatoDTOBanco->setBolExclusaoLogica(false);
              $objContatoDTOBanco->retNumIdContato();
              $objContatoDTOBanco->retStrStaNatureza();
              $objContatoDTOBanco->retStrSinAtivo();
              $objContatoDTOBanco->setNumIdContato($objContatoDTO->getNumIdContato());
              $objContatoDTOBanco = $objContatoRN->consultarRN0324($objContatoDTOBanco);

              if ($objContatoDTOBanco != null && $SinAtivo != null) {
                if ($objContatoDTOBanco->getStrSinAtivo() == 'S' && $SinAtivo == 'N' && $StaOperacao != 'D') {
                  $objContatoRN->desativarRN0451(array($objContatoDTOBanco));
                } else {
                  if ($objContatoDTOBanco->getStrSinAtivo() == 'N' && $SinAtivo == 'S' && $StaOperacao != 'R') {
                    $objContatoRN->reativarRN0452(array($objContatoDTOBanco));
                  }
                }
              }
            }

            if ($StaOperacao == 'A') {
              if ($objContatoDTOBanco == null) {
                $objContatoDTO->setNumIdContato(null);
                $objContatoRN->cadastrarRN0322($objContatoDTO);
              } else {
                $objContatoRN->alterarRN0323($objContatoDTO);
              }
            } else {
              if ($StaOperacao == 'E') {
                if ($objContatoDTOBanco != null) {
                  try {
                    $objContatoRN->excluirRN0326(array($objContatoDTOBanco));
                  } catch (Throwable $e) {
                    //erro de integridade então desativa
                    $objContatoRN->desativarRN0451(array($objContatoDTOBanco));
                  }
                }
              } else {
                if ($StaOperacao == 'D') {
                  if ($objContatoDTOBanco != null) {
                    $objContatoRN->desativarRN0451(array($objContatoDTOBanco));
                  }
                } else {
                  if ($StaOperacao == 'R') {
                    if ($objContatoDTOBanco != null) {
                      $objContatoRN->reativarRN0452(array($objContatoDTOBanco));
                    }
                  } else {
                    throw new InfraException('Operação ' . $StaOperacao . ' inválida.');
                  }
                }
              }
            }

            $ret++;
          } catch (Throwable $e) {
            if (SessaoSEI::getInstance()->getObjServicoDTO() != null) {
              $objInfraException->adicionarValidacao("\n * [POSICAO " . $n . "] " . $objContatoDTO->getStrNome() . ($objContatoDTO->getNumIdContato() != null ? ' (ID ' . $objContatoDTO->getNumIdContato() . ')' : '') . ": " . $e->__toString());

              if (!($e instanceof InfraException && $e->contemValidacoes())) {
                try {
                  LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
                } catch (Throwable $e2) {
                }
              }
            }

            throw $e;
          }

          $n++;
        }

        if ($objInfraException->contemValidacoes()) {
          $objInfraException->lancarValidacoes();
        }
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando atualização de contatos.', $e);
    }
  }

  protected function consultarProcedimentoConectado(EntradaConsultarProcedimentoAPI $objEntradaConsultarProcedimentoAPI) {
    try {

      $objInfraException = new InfraException();

      $objProcedimentoDTO = $this->obterProcesso($objEntradaConsultarProcedimentoAPI->getIdProcedimento(), $objEntradaConsultarProcedimentoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_CONSULTAR_PROCEDIMENTO, $objProcedimentoDTO);;

      if (InfraString::isBolVazia($objEntradaConsultarProcedimentoAPI->getSinRetornarAssuntos())) {
        $objEntradaConsultarProcedimentoAPI->setSinRetornarAssuntos('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarProcedimentoAPI->getSinRetornarAssuntos())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para assuntos inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarProcedimentoAPI->getSinRetornarInteressados())) {
        $objEntradaConsultarProcedimentoAPI->setSinRetornarInteressados('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarProcedimentoAPI->getSinRetornarInteressados())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para interessados inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarProcedimentoAPI->getSinRetornarObservacoes())) {
        $objEntradaConsultarProcedimentoAPI->setSinRetornarObservacoes('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarProcedimentoAPI->getSinRetornarObservacoes())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para observações inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarProcedimentoAPI->getSinRetornarAndamentoGeracao())) {
        $objEntradaConsultarProcedimentoAPI->setSinRetornarAndamentoGeracao('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarProcedimentoAPI->getSinRetornarAndamentoGeracao())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para andamento geração inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarProcedimentoAPI->getSinRetornarAndamentoConclusao())) {
        $objEntradaConsultarProcedimentoAPI->setSinRetornarAndamentoConclusao('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarProcedimentoAPI->getSinRetornarAndamentoConclusao())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para andamento de conclusão inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarProcedimentoAPI->getSinRetornarUltimoAndamento())) {
        $objEntradaConsultarProcedimentoAPI->setSinRetornarUltimoAndamento('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarProcedimentoAPI->getSinRetornarUltimoAndamento())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para último andamento inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarProcedimentoAPI->getSinRetornarUnidadesProcedimentoAberto())) {
        $objEntradaConsultarProcedimentoAPI->setSinRetornarUnidadesProcedimentoAberto('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarProcedimentoAPI->getSinRetornarUnidadesProcedimentoAberto())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para unidades com procedimento aberto inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarProcedimentoAPI->getSinRetornarProcedimentosRelacionados())) {
        $objEntradaConsultarProcedimentoAPI->setSinRetornarProcedimentosRelacionados('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarProcedimentoAPI->getSinRetornarProcedimentosRelacionados())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para processos relacionados inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarProcedimentoAPI->getSinRetornarProcedimentosAnexados())) {
        $objEntradaConsultarProcedimentoAPI->setSinRetornarProcedimentosAnexados('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarProcedimentoAPI->getSinRetornarProcedimentosAnexados())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para processos anexados inválido.');
        }
      }

      $objAtividadeRN = new AtividadeRN();
      $objProcedimentoRN = new ProcedimentoRN();


      if (SessaoSEI::getInstance()->isBolHabilitada() || (SessaoSEI::getInstance()->getObjServicoDTO() != null && SessaoSEI::getInstance()->getObjServicoDTO()->getNumIdUnidade() != null)) {
        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
        $objPesquisaProtocoloDTO->setDblIdProtocolo(array($objProcedimentoDTO->getDblIdProcedimento()));

        $objProtocoloRN = new ProtocoloRN();
        if (count($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO)) == 0) {
          $objInfraException->lancarValidacao('Unidade [' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '] não possui acesso ao processo [' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . '].');
        }
      }

      $objInfraException->lancarValidacoes();

      $objSaidaConsultarProcedimentoAPI = new SaidaConsultarProcedimentoAPI();
      $objSaidaConsultarProcedimentoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objSaidaConsultarProcedimentoAPI->setProcedimentoFormatado($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
      $objSaidaConsultarProcedimentoAPI->setEspecificacao($objProcedimentoDTO->getStrDescricaoProtocolo());
      $objSaidaConsultarProcedimentoAPI->setDataAutuacao($objProcedimentoDTO->getDtaGeracaoProtocolo());
      $objSaidaConsultarProcedimentoAPI->setNivelAcessoLocal($objProcedimentoDTO->getStrStaNivelAcessoLocalProtocolo());
      $objSaidaConsultarProcedimentoAPI->setNivelAcessoGlobal($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo());

      $objTipoProcedimentoAPI = new TipoProcedimentoAPI();
      $objTipoProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
      $objTipoProcedimentoAPI->setNome($objProcedimentoDTO->getStrNomeTipoProcedimento());
      $objSaidaConsultarProcedimentoAPI->setTipoProcedimento($objTipoProcedimentoAPI);

      if ($objProcedimentoDTO->getNumIdTipoPrioridade() != null) {
        $objTipoPrioridadeAPI = new TipoPrioridadeAPI();
        $objTipoPrioridadeAPI->setIdTipoPrioridade($objProcedimentoDTO->getNumIdTipoPrioridade());
        $objTipoPrioridadeAPI->setNome($objProcedimentoDTO->getStrNomeTipoPrioridade());
        $objSaidaConsultarProcedimentoAPI->setTipoPrioridade($objTipoPrioridadeAPI);
      }

      $arrObjAssuntoAPI = array();
      if ($objEntradaConsultarProcedimentoAPI->getSinRetornarAssuntos() == 'S') {
        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDTO->getDblIdProcedimento());

        $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
        $arrIdProcedimentos = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO), 'IdProtocolo2');
        $arrIdProcedimentos[] = $objProcedimentoDTO->getDblIdProcedimento();

        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($arrIdProcedimentos, InfraDTO::$OPER_IN);

        $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
        $arrIdDocumentos = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO), 'IdProtocolo2');

        $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
        $objRelProtocoloAssuntoDTO->setDistinct(true);
        $objRelProtocoloAssuntoDTO->retStrCodigoEstruturadoAssunto();
        $objRelProtocoloAssuntoDTO->retStrDescricaoAssunto();
        $objRelProtocoloAssuntoDTO->setDblIdProtocolo(array_merge($arrIdProcedimentos, $arrIdDocumentos), InfraDTO::$OPER_IN);
        $objRelProtocoloAssuntoDTO->setOrdStrCodigoEstruturadoAssunto(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
        $arrObjRelProtocoloAssuntoDTO = $objRelProtocoloAssuntoRN->listarRN0188($objRelProtocoloAssuntoDTO);

        foreach ($arrObjRelProtocoloAssuntoDTO as $objRelProtocoloAssuntoDTO) {
          $objAssuntoAPI = new AssuntoAPI();
          $objAssuntoAPI->setCodigoEstruturado($objRelProtocoloAssuntoDTO->getStrCodigoEstruturadoAssunto());
          $objAssuntoAPI->setDescricao($objRelProtocoloAssuntoDTO->getStrDescricaoAssunto());
          $arrObjAssuntoAPI[] = $objAssuntoAPI;
        }
      }
      $objSaidaConsultarProcedimentoAPI->setAssuntos($arrObjAssuntoAPI);

      $arrObjInteressadoAPI = array();
      if ($objEntradaConsultarProcedimentoAPI->getSinRetornarInteressados() == 'S') {
        if (OuvidoriaRN::verificarAcessoInteressado($objProcedimentoDTO)) {
          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->retNumIdParticipante();
          $objParticipanteDTO->retStrSiglaContato();
          $objParticipanteDTO->retStrNomeContato();
          $objParticipanteDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
          $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
          $objParticipanteDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

          $objParticipanteRN = new ParticipanteRN();
          $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);

          foreach ($arrObjParticipanteDTO as $objParticipanteDTO) {
            $objInteressadoAPI = new InteressadoAPI();
            $objInteressadoAPI->setSigla($objParticipanteDTO->getStrSiglaContato());
            $objInteressadoAPI->setNome($objParticipanteDTO->getStrNomeContato());
            $arrObjInteressadoAPI[] = $objInteressadoAPI;
          }
        }
      }
      $objSaidaConsultarProcedimentoAPI->setInteressados($arrObjInteressadoAPI);

      $arrObjObservacaoAPI = array();
      if ($objEntradaConsultarProcedimentoAPI->getSinRetornarObservacoes() == 'S') {
        $objObservacaoDTO = new ObservacaoDTO();
        $objObservacaoDTO->retNumIdUnidade();
        $objObservacaoDTO->retStrSiglaUnidade();
        $objObservacaoDTO->retStrDescricaoUnidade();
        $objObservacaoDTO->retStrDescricao();
        $objObservacaoDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objObservacaoDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objObservacaoRN = new ObservacaoRN();
        $arrObjObservacaoDTO = $objObservacaoRN->listarRN0219($objObservacaoDTO);

        foreach ($arrObjObservacaoDTO as $objObservacaoDTO) {
          $objObservacaoAPI = new ObservacaoAPI();
          $objObservacaoAPI->setDescricao($objObservacaoDTO->getStrDescricao());

          $objUnidadeAPI = new UnidadeAPI();
          $objUnidadeAPI->setIdUnidade($objObservacaoDTO->getNumIdUnidade());
          $objUnidadeAPI->setSigla($objObservacaoDTO->getStrSiglaUnidade());
          $objUnidadeAPI->setDescricao($objObservacaoDTO->getStrDescricaoUnidade());
          $objObservacaoAPI->setUnidade($objUnidadeAPI);

          $arrObjObservacaoAPI[] = $objObservacaoAPI;
        }
      }
      $objSaidaConsultarProcedimentoAPI->setObservacoes($arrObjObservacaoAPI);

      $objAndamentoAPIGeracao = null;
      if ($objEntradaConsultarProcedimentoAPI->getSinRetornarAndamentoGeracao() == 'S') {
        $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
        $objProcedimentoHistoricoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
        $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_PERSONALIZADO);
        $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('N');
        $objProcedimentoHistoricoDTO->setNumIdTarefa(TarefaRN::$TI_GERACAO_PROCEDIMENTO);
        $objProcedimentoHistoricoDTO->setNumMaxRegistrosRetorno(1);
        $objProcedimentoDTOHistorico = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
        $arrObjAtividadeDTOHistorico = $objProcedimentoDTOHistorico->getArrObjAtividadeDTO();
        $objAtividadeDTO = $arrObjAtividadeDTOHistorico[0];

        if ($objAtividadeDTO != null) {
          $objAndamentoAPIGeracao = new AndamentoAPI();
          //$objAndamentoAPIGeracao->setIdAndamento($objAtividadeDTO->getNumIdAtividade());
          //$objAndamentoAPIGeracao->setIdTarefa($objAtividadeDTO->getNumIdTarefa());
          $objAndamentoAPIGeracao->setDescricao($objAtividadeDTO->getStrNomeTarefa());
          $objAndamentoAPIGeracao->setDataHora($objAtividadeDTO->getDthAbertura());

          $objUsuarioAPI = new UsuarioAPI();
          $objUsuarioAPI->setIdUsuario($objAtividadeDTO->getNumIdUsuarioOrigem());
          $objUsuarioAPI->setSigla($objAtividadeDTO->getStrSiglaUsuarioOrigem());
          $objUsuarioAPI->setNome($objAtividadeDTO->getStrNomeUsuarioOrigem());
          $objAndamentoAPIGeracao->setUsuario($objUsuarioAPI);

          $objUnidadeAPI = new UnidadeAPI();
          $objUnidadeAPI->setIdUnidade($objAtividadeDTO->getNumIdUnidade());
          $objUnidadeAPI->setSigla($objAtividadeDTO->getStrSiglaUnidade());
          $objUnidadeAPI->setDescricao($objAtividadeDTO->getStrDescricaoUnidade());
          $objAndamentoAPIGeracao->setUnidade($objUnidadeAPI);
        }
      }
      $objSaidaConsultarProcedimentoAPI->setAndamentoGeracao($objAndamentoAPIGeracao);

      $arrObjAtividadeDTOAbertas = array();
      if ($objEntradaConsultarProcedimentoAPI->getSinRetornarAndamentoConclusao() == 'S' || $objEntradaConsultarProcedimentoAPI->getSinRetornarUnidadesProcedimentoAberto() == 'S') {
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDistinct(true);
        $objAtividadeDTO->retNumIdUnidade();
        $objAtividadeDTO->retStrSiglaUnidade();
        $objAtividadeDTO->retStrDescricaoUnidade();
        $objAtividadeDTO->retNumIdUsuarioAtribuicao();
        $objAtividadeDTO->retStrSiglaUsuarioAtribuicao();
        $objAtividadeDTO->retStrNomeUsuarioAtribuicao();
        $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setDthConclusao(null);
        $arrObjAtividadeDTOAbertas = $objAtividadeRN->listarRN0036($objAtividadeDTO);
      }

      $objAndamentoAPIConclusao = null;
      if ($objEntradaConsultarProcedimentoAPI->getSinRetornarAndamentoConclusao() == 'S') {
        if (count($arrObjAtividadeDTOAbertas) == 0) {
          $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
          $objProcedimentoHistoricoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
          $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_PERSONALIZADO);
          $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('N');

          if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_SIGILOSO) {
            $objProcedimentoHistoricoDTO->setNumIdTarefa(TarefaRN::$TI_CONCLUSAO_PROCESSO_USUARIO);
          } else {
            $objProcedimentoHistoricoDTO->setNumIdTarefa(TarefaRN::$TI_CONCLUSAO_PROCESSO_UNIDADE);
          }

          $objProcedimentoHistoricoDTO->setNumMaxRegistrosRetorno(1);
          $objProcedimentoDTOHistorico = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
          $arrObjAtividadeDTOHistorico = $objProcedimentoDTOHistorico->getArrObjAtividadeDTO();

          $objAtividadeDTO = $arrObjAtividadeDTOHistorico[0];

          if ($objAtividadeDTO != null) {
            $objAndamentoAPIConclusao = new AndamentoAPI();
            //$objAndamentoAPIConclusao->setIdAndamento($objAtividadeDTO->getNumIdAtividade());
            //$objAndamentoAPIConclusao->setIdTarefa($objAtividadeDTO->getNumIdTarefa());
            $objAndamentoAPIConclusao->setDescricao($objAtividadeDTO->getStrNomeTarefa());
            $objAndamentoAPIConclusao->setDataHora($objAtividadeDTO->getDthAbertura());

            $objUsuarioAPI = new UsuarioAPI();
            $objUsuarioAPI->setIdUsuario($objAtividadeDTO->getNumIdUsuarioOrigem());
            $objUsuarioAPI->setSigla($objAtividadeDTO->getStrSiglaUsuarioOrigem());
            $objUsuarioAPI->setNome($objAtividadeDTO->getStrNomeUsuarioOrigem());
            $objAndamentoAPIConclusao->setUsuario($objUsuarioAPI);

            $objUnidadeAPI = new UnidadeAPI();
            $objUnidadeAPI->setIdUnidade($objAtividadeDTO->getNumIdUnidade());
            $objUnidadeAPI->setSigla($objAtividadeDTO->getStrSiglaUnidade());
            $objUnidadeAPI->setDescricao($objAtividadeDTO->getStrDescricaoUnidade());
            $objAndamentoAPIConclusao->setUnidade($objUnidadeAPI);
          }
        }
      }
      $objSaidaConsultarProcedimentoAPI->setAndamentoConclusao($objAndamentoAPIConclusao);

      $objAndamentoAPIUltimo = null;
      if ($objEntradaConsultarProcedimentoAPI->getSinRetornarUltimoAndamento() == 'S') {
        $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
        $objProcedimentoHistoricoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
        $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_RESUMIDO);
        $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('N');
        $objProcedimentoHistoricoDTO->setNumMaxRegistrosRetorno(1);
        $objProcedimentoDTOHistorico = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
        $arrObjAtividadeDTOHistorico = $objProcedimentoDTOHistorico->getArrObjAtividadeDTO();
        $objAtividadeDTO = $arrObjAtividadeDTOHistorico[0];

        if ($objAtividadeDTO != null) {
          $objAndamentoAPIUltimo = new AndamentoAPI();
          //$objAndamentoAPIUltimo->setIdAndamento($objAtividadeDTO->getNumIdAtividade());
          //$objAndamentoAPIUltimo->setIdTarefa($objAtividadeDTO->getNumIdTarefa());
          $objAndamentoAPIUltimo->setDescricao($objAtividadeDTO->getStrNomeTarefa());
          $objAndamentoAPIUltimo->setDataHora($objAtividadeDTO->getDthAbertura());

          $objUsuarioAPI = new UsuarioAPI();
          $objUsuarioAPI->setIdUsuario($objAtividadeDTO->getNumIdUsuarioOrigem());
          $objUsuarioAPI->setSigla($objAtividadeDTO->getStrSiglaUsuarioOrigem());
          $objUsuarioAPI->setNome($objAtividadeDTO->getStrNomeUsuarioOrigem());
          $objAndamentoAPIUltimo->setUsuario($objUsuarioAPI);

          $objUnidadeAPI = new UnidadeAPI();
          $objUnidadeAPI->setIdUnidade($objAtividadeDTO->getNumIdUnidade());
          $objUnidadeAPI->setSigla($objAtividadeDTO->getStrSiglaUnidade());
          $objUnidadeAPI->setDescricao($objAtividadeDTO->getStrDescricaoUnidade());
          $objAndamentoAPIUltimo->setUnidade($objUnidadeAPI);
        }
      }
      $objSaidaConsultarProcedimentoAPI->setUltimoAndamento($objAndamentoAPIUltimo);

      $arrUnidadesProcedimentoAbertoAPI = array();
      if ($objEntradaConsultarProcedimentoAPI->getSinRetornarUnidadesProcedimentoAberto() == 'S') {
        $arrUnidades = array();
        foreach ($arrObjAtividadeDTOAbertas as $objAtividadeDTO) {
          if ($objAtividadeDTO->getNumIdUsuarioAtribuicao() != null) {
            $arrUnidades[$objAtividadeDTO->getNumIdUnidade()] = $objAtividadeDTO;
          }
        }
        foreach ($arrObjAtividadeDTOAbertas as $objAtividadeDTO) {
          if ($objAtividadeDTO->getNumIdUsuarioAtribuicao() == null && !isset($arrUnidades[$objAtividadeDTO->getNumIdUnidade()])) {
            $arrUnidades[$objAtividadeDTO->getNumIdUnidade()] = $objAtividadeDTO;
          }
        }
        $arrObjAtividadeDTO = array_values($arrUnidades);

        foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
          $objUnidadeProcedimentoAbertoAPI = new UnidadeProcedimentoAbertoAPI();

          $objUnidadeAPI = new UnidadeAPI();
          $objUnidadeAPI->setIdUnidade($objAtividadeDTO->getNumIdUnidade());
          $objUnidadeAPI->setSigla($objAtividadeDTO->getStrSiglaUnidade());
          $objUnidadeAPI->setDescricao($objAtividadeDTO->getStrDescricaoUnidade());
          $objUnidadeProcedimentoAbertoAPI->setUnidade($objUnidadeAPI);

          $objUsuarioAPI = null;
          if ($objAtividadeDTO->getNumIdUsuarioAtribuicao() != null) {
            $objUsuarioAPI = new UsuarioAPI();
            $objUsuarioAPI->setIdUsuario($objAtividadeDTO->getNumIdUsuarioAtribuicao());
            $objUsuarioAPI->setSigla($objAtividadeDTO->getStrSiglaUsuarioAtribuicao());
            $objUsuarioAPI->setNome($objAtividadeDTO->getStrNomeUsuarioAtribuicao());
          }
          $objUnidadeProcedimentoAbertoAPI->setUsuarioAtribuicao($objUsuarioAPI);

          $arrUnidadesProcedimentoAbertoAPI[] = $objUnidadeProcedimentoAbertoAPI;
        }
      }
      $objSaidaConsultarProcedimentoAPI->setUnidadesProcedimentoAberto($arrUnidadesProcedimentoAbertoAPI);

      $arrObjProcedimentoResumidoAPIRelacionados = array();
      if ($objEntradaConsultarProcedimentoAPI->getSinRetornarProcedimentosRelacionados() == 'S') {
        $objProcedimentoDTORelacionado = new ProcedimentoDTO();
        $objProcedimentoDTORelacionado->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
        $arrObjRelProtocoloProtocoloDTO = $objProcedimentoRN->listarRelacionados($objProcedimentoDTORelacionado);

        $arrObjProcedimentoDTORelacionados = array();
        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
          if ($objRelProtocoloProtocoloDTO->getObjProtocoloDTO1() != null) {
            $arrObjProcedimentoDTORelacionados[] = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO1();
          } else {
            $arrObjProcedimentoDTORelacionados[] = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();
          }
        }

        foreach ($arrObjProcedimentoDTORelacionados as $objProcedimentoDTORelacionado) {
          $objProcedimentoResumidoAPI = new ProcedimentoResumidoAPI();
          $objProcedimentoResumidoAPI->setIdProcedimento($objProcedimentoDTORelacionado->getDblIdProcedimento());
          $objProcedimentoResumidoAPI->setProcedimentoFormatado($objProcedimentoDTORelacionado->getStrProtocoloProcedimentoFormatado());

          $objTipoProcedimentoAPI = new TipoProcedimentoAPI();
          $objTipoProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTORelacionado->getNumIdTipoProcedimento());
          $objTipoProcedimentoAPI->setNome($objProcedimentoDTORelacionado->getStrNomeTipoProcedimento());
          $objProcedimentoResumidoAPI->setTipoProcedimento($objTipoProcedimentoAPI);

          $arrObjProcedimentoResumidoAPIRelacionados[] = $objProcedimentoResumidoAPI;
        }
      }
      $objSaidaConsultarProcedimentoAPI->setProcedimentosRelacionados($arrObjProcedimentoResumidoAPIRelacionados);

      $arrObjProcedimentoResumidoAPIAnexados = array();
      if ($objEntradaConsultarProcedimentoAPI->getSinRetornarProcedimentosAnexados() == 'S') {
        $objProcedimentoDTOAnexado = new ProcedimentoDTO();
        $objProcedimentoDTOAnexado->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
        $arrObjRelProtocoloProtocoloDTO = $objProcedimentoRN->listarAnexados($objProcedimentoDTOAnexado);

        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
          $objProcedimentoDTOAnexado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

          $objProcedimentoResumidoAPI = new ProcedimentoResumidoAPI();
          $objProcedimentoResumidoAPI->setIdProcedimento($objProcedimentoDTOAnexado->getDblIdProcedimento());
          $objProcedimentoResumidoAPI->setProcedimentoFormatado($objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado());

          $objTipoProcedimentoAPI = new TipoProcedimentoAPI();
          $objTipoProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTOAnexado->getNumIdTipoProcedimento());
          $objTipoProcedimentoAPI->setNome($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento());
          $objProcedimentoResumidoAPI->setTipoProcedimento($objTipoProcedimentoAPI);

          $arrObjProcedimentoResumidoAPIAnexados[] = $objProcedimentoResumidoAPI;
        }
      }
      $objSaidaConsultarProcedimentoAPI->setProcedimentosAnexados($arrObjProcedimentoResumidoAPIAnexados);

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null && SessaoSEI::getInstance()->getObjServicoDTO()->getStrSinLinkExterno() == 'S') {
        $objAcessoExternoDTO = $this->obterAcessoExternoSistema($objProcedimentoDTO->getDblIdProcedimento());
        $objSaidaConsultarProcedimentoAPI->setLinkAcesso(SessaoSEIExterna::getInstance($objAcessoExternoDTO->getNumIdAcessoExterno())->assinarLink(ConfiguracaoSEI::getInstance()->getValor('SEI',
            'URL') . '/processo_acesso_externo_consulta.php?id_acesso_externo=' . $objAcessoExternoDTO->getNumIdAcessoExterno()));
      } else {
        $objSaidaConsultarProcedimentoAPI->setLinkAcesso(ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL') . '/controlador.php?acao=procedimento_trabalhar&id_procedimento=' . $objProcedimentoDTO->getDblIdProcedimento());
      }

      return $objSaidaConsultarProcedimentoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando consulta de processo.', $e);
    }
  }

  protected function listarFeriadosConectado(EntradaListarFeriadosAPI $objEntradaListarFeriadosAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_FERIADOS);

      if (InfraString::isBolVazia($objEntradaListarFeriadosAPI->getIdOrgao())) {
        throw new InfraException('Órgão não informado.');
      }

      if (InfraString::isBolVazia($objEntradaListarFeriadosAPI->getDataInicial())) {
        throw new InfraException('Data inicial não informada.');
      }

      if (InfraString::isBolVazia($objEntradaListarFeriadosAPI->getDataFinal())) {
        throw new InfraException('Data final não informada.');
      }

      if (InfraData::compararDatas($objEntradaListarFeriadosAPI->getDataInicial(), $objEntradaListarFeriadosAPI->getDataFinal()) < 0) {
        throw new InfraException("Período de datas inválido.");
      }


      $objFeriadoDTO = new FeriadoDTO();
      $objFeriadoDTO->setNumIdOrgao($objEntradaListarFeriadosAPI->getIdOrgao());
      $objFeriadoDTO->setDtaInicial($objEntradaListarFeriadosAPI->getDataInicial());
      $objFeriadoDTO->setDtaFinal($objEntradaListarFeriadosAPI->getDataFinal());

      $objPublicacaoRN = new PublicacaoRN();
      $arrFeriados = $objPublicacaoRN->listarFeriados($objFeriadoDTO);

      $arrObjFeriadoAPI = array();
      foreach ($arrFeriados as $feriado) {
        $objFeriadoAPI = new FeriadoAPI();
        $objFeriadoAPI->setData($feriado['Data']);
        $objFeriadoAPI->setDescricao($feriado['Descricao']);
        $arrObjFeriadoAPI[] = $objFeriadoAPI;
      }

      return $arrObjFeriadoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de feriados.', $e);
    }
  }

  protected function consultarPublicacaoConectado(EntradaConsultarPublicacaoAPI $objEntradaConsultarPublicacaoAPI) {
    try {
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objEntradaConsultarPublicacaoAPI->getSinRetornarAndamento())) {
        $objEntradaConsultarPublicacaoAPI->setSinRetornarAndamento('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarPublicacaoAPI->getSinRetornarAndamento())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para andamento de publicação inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarPublicacaoAPI->getSinRetornarAssinaturas())) {
        $objEntradaConsultarPublicacaoAPI->setSinRetornarAssinaturas('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarPublicacaoAPI->getSinRetornarAssinaturas())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para assinaturas da publicação inválido.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objPublicacaoDTOConsulta = new PublicacaoDTO();
      $objPublicacaoDTOConsulta->retNumIdPublicacao();
      $objPublicacaoDTOConsulta->retDblIdDocumento();
      $objPublicacaoDTOConsulta->retDtaDisponibilizacao();
      $objPublicacaoDTOConsulta->retStrStaMotivo();
      $objPublicacaoDTOConsulta->retNumIdVeiculoIO();
      $objPublicacaoDTOConsulta->retDtaPublicacaoIO();
      $objPublicacaoDTOConsulta->retStrPaginaIO();
      $objPublicacaoDTOConsulta->retDtaPublicacao();
      $objPublicacaoDTOConsulta->retNumNumero();
      $objPublicacaoDTOConsulta->retStrResumo();
      $objPublicacaoDTOConsulta->retNumIdVeiculoPublicacao();
      $objPublicacaoDTOConsulta->retStrNomeVeiculoPublicacao();
      $objPublicacaoDTOConsulta->retStrDescricaoVeiculoImprensaNacional();
      $objPublicacaoDTOConsulta->retStrStaEstado();
      $objPublicacaoDTOConsulta->retStrSiglaVeiculoImprensaNacional();
      $objPublicacaoDTOConsulta->retNumIdSecaoIO();
      $objPublicacaoDTOConsulta->retStrNomeSecaoImprensaNacional();
      $objPublicacaoDTOConsulta->retStrStaTipoVeiculoPublicacao();

      $objPublicacaoDTO = null;

      if ($objEntradaConsultarPublicacaoAPI->getIdPublicacao() != null) {
        $objPublicacaoDTO = clone($objPublicacaoDTOConsulta);
        $objPublicacaoDTO->setNumIdPublicacao($objEntradaConsultarPublicacaoAPI->getIdPublicacao());

        $objPublicacaoRN = new PublicacaoRN();
        $objPublicacaoDTO = $objPublicacaoRN->consultarRN1044($objPublicacaoDTO);

        if ($objPublicacaoDTO == null) {
          return null;
        }

        $objEntradaConsultarPublicacaoAPI->setIdDocumento($objPublicacaoDTO->getDblIdDocumento());
      }

      $objDocumentoDTO = $this->obterDocumento($objEntradaConsultarPublicacaoAPI->getIdDocumento(), $objEntradaConsultarPublicacaoAPI->getProtocoloDocumento());

      $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_CONSULTAR_PUBLICACAO, $objDocumentoDTO);

      if ($objPublicacaoDTO == null) {
        $objPublicacaoDTO = clone($objPublicacaoDTOConsulta);
        $objPublicacaoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

        $objPublicacaoRN = new PublicacaoRN();
        $objPublicacaoDTO = $objPublicacaoRN->consultarRN1044($objPublicacaoDTO);

        if ($objPublicacaoDTO == null) {
          return null;
        }
      }

      $objPublicacaoAPI = new PublicacaoAPI();
      $objPublicacaoAPI->setIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
      $objPublicacaoAPI->setIdDocumento($objPublicacaoDTO->getDblIdDocumento());
      $objPublicacaoAPI->setStaMotivo($objPublicacaoDTO->getStrStaMotivo());
      $objPublicacaoAPI->setDataDisponibilizacao($objPublicacaoDTO->getDtaDisponibilizacao());
      $objPublicacaoAPI->setIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
      $objPublicacaoAPI->setStaTipoVeiculo($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao());
      $objPublicacaoAPI->setNomeVeiculo($objPublicacaoDTO->getStrNomeVeiculoPublicacao());
      $objPublicacaoAPI->setNumero($objPublicacaoDTO->getNumNumero());
      $objPublicacaoAPI->setResumo($objPublicacaoDTO->getStrResumo());

      $objPublicacaoAPI->setDataPublicacao($objPublicacaoDTO->getDtaPublicacao());
      $objPublicacaoAPI->setEstado($objPublicacaoDTO->getStrStaEstado());

      $objPublicacaoAPI->setImprensaNacional(null);

      if (!InfraString::isBolVazia($objPublicacaoDTO->getNumIdVeiculoIO())) {
        $objPublicacaoImprensaNacionalAPI = new PublicacaoImprensaNacionalAPI();
        $objPublicacaoImprensaNacionalAPI->setIdVeiculo($objPublicacaoDTO->getNumIdVeiculoIO());
        $objPublicacaoImprensaNacionalAPI->setSiglaVeiculo($objPublicacaoDTO->getStrSiglaVeiculoImprensaNacional());
        $objPublicacaoImprensaNacionalAPI->setDescricaoVeiculo($objPublicacaoDTO->getStrDescricaoVeiculoImprensaNacional());
        $objPublicacaoImprensaNacionalAPI->setPagina($objPublicacaoDTO->getStrPaginaIO());
        $objPublicacaoImprensaNacionalAPI->setIdSecao($objPublicacaoDTO->getNumIdSecaoIO());
        $objPublicacaoImprensaNacionalAPI->setSecao($objPublicacaoDTO->getStrNomeSecaoImprensaNacional());
        $objPublicacaoImprensaNacionalAPI->setData($objPublicacaoDTO->getDtaPublicacaoIO());
        $objPublicacaoAPI->setImprensaNacional($objPublicacaoImprensaNacionalAPI);
      }

      $objSaidaConsultarPublicacaoAPI = new SaidaConsultarPublicacaoAPI();
      $objSaidaConsultarPublicacaoAPI->setPublicacao($objPublicacaoAPI);

      $objAndamentoAPI = null;
      if ($objEntradaConsultarPublicacaoAPI->getSinRetornarAndamento() == 'S') {
        $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
        $objProcedimentoHistoricoDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
        $objProcedimentoHistoricoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_PERSONALIZADO);
        $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('N');
        $objProcedimentoHistoricoDTO->setNumIdTarefa(TarefaRN::$TI_PUBLICACAO);
        $objProcedimentoHistoricoDTO->setNumMaxRegistrosRetorno(1);

        $objProcedimentoRN = new ProcedimentoRN();
        $objProcedimentoDTOHistorico = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
        $arrObjAtividadeDTOHistorico = $objProcedimentoDTOHistorico->getArrObjAtividadeDTO();
        $objAtividadeDTO = $arrObjAtividadeDTOHistorico[0];

        $objAndamentoAPI = new AndamentoAPI();
        //$objAndamentoAPI->setIdAndamento($objAtividadeDTO->getNumIdAtividade());
        //$objAndamentoAPI->setIdTarefa($objAtividadeDTO->getNumIdTarefa());
        $objAndamentoAPI->setDescricao($objAtividadeDTO->getStrNomeTarefa());
        $objAndamentoAPI->setDataHora($objAtividadeDTO->getDthAbertura());

        $objUsuarioAPI = new UsuarioAPI();
        $objUsuarioAPI->setIdUsuario($objAtividadeDTO->getNumIdUsuarioOrigem());
        $objUsuarioAPI->setSigla($objAtividadeDTO->getStrSiglaUsuarioOrigem());
        $objUsuarioAPI->setNome($objAtividadeDTO->getStrNomeUsuarioOrigem());
        $objAndamentoAPI->setUsuario($objUsuarioAPI);

        $objUnidadeAPI = new UnidadeAPI();
        $objUnidadeAPI->setIdUnidade($objAtividadeDTO->getNumIdUnidade());
        $objUnidadeAPI->setSigla($objAtividadeDTO->getStrSiglaUnidade());
        $objUnidadeAPI->setDescricao($objAtividadeDTO->getStrDescricaoUnidade());
        $objAndamentoAPI->setUnidade($objUnidadeAPI);
      }
      $objSaidaConsultarPublicacaoAPI->setAndamento($objAndamentoAPI);


      $arrObjAssinaturaAPI = array();
      if ($objEntradaConsultarPublicacaoAPI->getSinRetornarAssinaturas() == 'S') {
        $objAssinaturaDTO = new AssinaturaDTO();
        $objAssinaturaDTO->retNumIdAssinatura();
        $objAssinaturaDTO->retNumIdUsuario();
        $objAssinaturaDTO->retStrIdOrigemUsuario();
        $objAssinaturaDTO->retNumIdOrgaoUsuario();
        $objAssinaturaDTO->retStrSiglaUsuario();
        $objAssinaturaDTO->retStrNome();
        $objAssinaturaDTO->retStrTratamento();
        $objAssinaturaDTO->retDthAberturaAtividade();
        $objAssinaturaDTO->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());
        $objAssinaturaDTO->setOrdNumIdAssinatura(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objAssinaturaRN = new AssinaturaRN();
        $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);

        foreach ($arrObjAssinaturaDTO as $objAssinaturaDTO) {
          $objAssinaturaAPI = new AssinaturaAPI();
          $objAssinaturaAPI->setNome($objAssinaturaDTO->getStrNome());
          $objAssinaturaAPI->setCargoFuncao($objAssinaturaDTO->getStrTratamento());
          $objAssinaturaAPI->setDataHora($objAssinaturaDTO->getDthAberturaAtividade());
          $objAssinaturaAPI->setIdUsuario($objAssinaturaDTO->getNumIdUsuario());
          $objAssinaturaAPI->setIdOrigem($objAssinaturaDTO->getStrIdOrigemUsuario());
          $objAssinaturaAPI->setIdOrgao($objAssinaturaDTO->getNumIdOrgaoUsuario());
          $objAssinaturaAPI->setSigla($objAssinaturaDTO->getStrSiglaUsuario());
          $arrObjAssinaturaAPI[] = $objAssinaturaAPI;
        }
      }
      $objSaidaConsultarPublicacaoAPI->setAssinaturas($arrObjAssinaturaAPI);


      return $objSaidaConsultarPublicacaoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando consulta de publicação.', $e);
    }
  }

  protected function agendarPublicacaoControlado(EntradaAgendarPublicacaoAPI $objEntradaAgendarPublicacaoAPI) {
    try {
      $objDocumentoDTO = $this->obterDocumento($objEntradaAgendarPublicacaoAPI->getIdDocumento(), $objEntradaAgendarPublicacaoAPI->getProtocoloDocumento());

      $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_AGENDAR_PUBLICACAO, $objDocumentoDTO);

      $objPublicacaoDTO = new PublicacaoDTO();
      $objPublicacaoDTO->setNumIdPublicacao(null);
      $objPublicacaoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $objPublicacaoDTO->setStrStaMotivo($objEntradaAgendarPublicacaoAPI->getStaMotivo());
      $objPublicacaoDTO->setNumIdVeiculoPublicacao($objEntradaAgendarPublicacaoAPI->getIdVeiculoPublicacao());
      $objPublicacaoDTO->setDtaDisponibilizacao($objEntradaAgendarPublicacaoAPI->getDataDisponibilizacao());
      $objPublicacaoDTO->setStrResumo($objEntradaAgendarPublicacaoAPI->getResumo());

      if ($objEntradaAgendarPublicacaoAPI->getImprensaNacional() != null) {
        $objPublicacaoImprensaNacionalAPI = $objEntradaAgendarPublicacaoAPI->getImprensaNacional();
        $objPublicacaoDTO->setNumIdVeiculoIO($objPublicacaoImprensaNacionalAPI->getIdVeiculo());
        $objPublicacaoDTO->setNumIdSecaoIO($objPublicacaoImprensaNacionalAPI->getIdSecao());
        $objPublicacaoDTO->setStrPaginaIO($objPublicacaoImprensaNacionalAPI->getPagina());
        $objPublicacaoDTO->setDtaPublicacaoIO($objPublicacaoImprensaNacionalAPI->getData());
      } else {
        $objPublicacaoDTO->setNumIdVeiculoIO(null);
        $objPublicacaoDTO->setNumIdSecaoIO(null);
        $objPublicacaoDTO->setStrPaginaIO(null);
        $objPublicacaoDTO->setDtaPublicacaoIO(null);
      }

      $objPublicacaoRN = new PublicacaoRN();
      $objPublicacaoDTO = $objPublicacaoRN->agendarRN1041($objPublicacaoDTO);

      $objPublicacaoAPI = new PublicacaoAPI();
      $objPublicacaoAPI->setIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());

      return $objPublicacaoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando agendamento de publicação.', $e);
    }
  }

  protected function alterarPublicacaoControlado(EntradaAlterarPublicacaoAPI $objEntradaAlterarPublicacaoAPI) {
    try {
      $objPublicacaoDTO = null;

      if ($objEntradaAlterarPublicacaoAPI->getIdPublicacao() != null) {
        $objPublicacaoDTO = new PublicacaoDTO();
        $objPublicacaoDTO->retNumIdPublicacao();
        $objPublicacaoDTO->retDblIdDocumento();
        $objPublicacaoDTO->setNumIdPublicacao($objEntradaAlterarPublicacaoAPI->getIdPublicacao());

        $objPublicacaoRN = new PublicacaoRN();
        $objPublicacaoDTO = $objPublicacaoRN->consultarRN1044($objPublicacaoDTO);

        if ($objPublicacaoDTO == null) {
          throw new InfraException('Publicação não encontrada.', null, null, false);
        }

        $objEntradaAlterarPublicacaoAPI->setIdDocumento($objPublicacaoDTO->getDblIdDocumento());
      }

      $objDocumentoDTO = $this->obterDocumento($objEntradaAlterarPublicacaoAPI->getIdDocumento(), $objEntradaAlterarPublicacaoAPI->getProtocoloDocumento());

      $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_ALTERAR_PUBLICACAO, $objDocumentoDTO);

      if ($objPublicacaoDTO == null) {
        $objPublicacaoDTO = new PublicacaoDTO();
        $objPublicacaoDTO->retNumIdPublicacao();
        $objPublicacaoDTO->retDblIdDocumento();
        $objPublicacaoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

        $objPublicacaoRN = new PublicacaoRN();
        $objPublicacaoDTO = $objPublicacaoRN->consultarRN1044($objPublicacaoDTO);

        if ($objPublicacaoDTO == null) {
          throw new InfraException('Publicação não encontrada.', null, null, false);
        }
      }

      if ($objEntradaAlterarPublicacaoAPI->getStaMotivo() != null) {
        $objPublicacaoDTO->setStrStaMotivo($objEntradaAlterarPublicacaoAPI->getStaMotivo());
      }

      if ($objEntradaAlterarPublicacaoAPI->getIdVeiculoPublicacao() != null) {
        $objPublicacaoDTO->setNumIdVeiculoPublicacao($objEntradaAlterarPublicacaoAPI->getIdVeiculoPublicacao());
      }

      if ($objEntradaAlterarPublicacaoAPI->getDataDisponibilizacao() != null) {
        $objPublicacaoDTO->setDtaDisponibilizacao($objEntradaAlterarPublicacaoAPI->getDataDisponibilizacao());
      }

      if ($objEntradaAlterarPublicacaoAPI->getResumo() != null) {
        $objPublicacaoDTO->setStrResumo($objEntradaAlterarPublicacaoAPI->getResumo());
      }

      if ($objEntradaAlterarPublicacaoAPI->getImprensaNacional() != null) {
        $objPublicacaoImprensaNacionalAPI = $objEntradaAlterarPublicacaoAPI->getImprensaNacional();
        $objPublicacaoDTO->setNumIdVeiculoIO($objPublicacaoImprensaNacionalAPI->getIdVeiculo());
        $objPublicacaoDTO->setStrPaginaIO($objPublicacaoImprensaNacionalAPI->getPagina());
        $objPublicacaoDTO->setNumIdSecaoIO($objPublicacaoImprensaNacionalAPI->getIdSecao());
        $objPublicacaoDTO->setDtaPublicacaoIO($objPublicacaoImprensaNacionalAPI->getData());
      } else {
        $objPublicacaoDTO->setNumIdVeiculoIO(null);
        $objPublicacaoDTO->setStrPaginaIO(null);
        $objPublicacaoDTO->setNumIdSecaoIO(null);
        $objPublicacaoDTO->setDtaPublicacaoIO(null);
      }

      $objPublicacaoRN = new PublicacaoRN();
      $objPublicacaoRN->alterarAgendamentoRN1042($objPublicacaoDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando alteração de publicação.', $e);
    }
  }

  protected function cancelarAgendamentoPublicacaoControlado(EntradaCancelarAgendamentoPublicacaoAPI $objEntradaCancelarAgendamentoPublicacaoAPI) {
    try {
      $objPublicacaoDTO = null;

      if ($objEntradaCancelarAgendamentoPublicacaoAPI->getIdPublicacao() != null) {
        $objPublicacaoDTO = new PublicacaoDTO();
        $objPublicacaoDTO->retNumIdPublicacao();
        $objPublicacaoDTO->retDblIdDocumento();
        $objPublicacaoDTO->setNumIdPublicacao($objEntradaCancelarAgendamentoPublicacaoAPI->getIdPublicacao());

        $objPublicacaoRN = new PublicacaoRN();
        $objPublicacaoDTO = $objPublicacaoRN->consultarRN1044($objPublicacaoDTO);

        if ($objPublicacaoDTO == null) {
          throw new InfraException('Publicação não encontrada.', null, null, false);
        }

        $objEntradaCancelarAgendamentoPublicacaoAPI->setIdDocumento($objPublicacaoDTO->getDblIdDocumento());
      }

      $objDocumentoDTO = $this->obterDocumento($objEntradaCancelarAgendamentoPublicacaoAPI->getIdDocumento(), $objEntradaCancelarAgendamentoPublicacaoAPI->getProtocoloDocumento());

      $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_CANCELAR_PUBLICACAO, $objDocumentoDTO);

      if ($objPublicacaoDTO == null) {
        $objPublicacaoDTO = new PublicacaoDTO();
        $objPublicacaoDTO->retNumIdPublicacao();
        $objPublicacaoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

        $objPublicacaoRN = new PublicacaoRN();
        $objPublicacaoDTO = $objPublicacaoRN->consultarRN1044($objPublicacaoDTO);

        if ($objPublicacaoDTO == null) {
          throw new InfraException('Publicação não encontrada.', null, null, false);
        }
      }

      $objPublicacaoRN->cancelarAgendamentoRN1043($objPublicacaoDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando cancelamento de publicação.', $e);
    }
  }

  protected function confirmarDisponibilizacaoPublicacaoControlado(EntradaconfirmarDisponibilizacaoPublicacaoAPI $objEntradaConfirmarDisponibilizacaoPublicacaoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_CONFIRMAR_DISPONIBILIZACAO_PUBLICACAO);

      $arrIdDocumentos = $objEntradaConfirmarDisponibilizacaoPublicacaoAPI->getIdDocumentos();

      $arrObjPublicacaoDTO = array();

      foreach ($arrIdDocumentos as $dblIdDocumento) {
        $objPublicacaoDTO = new PublicacaoDTO();
        $objPublicacaoDTO->setDtaDisponibilizacao($objEntradaConfirmarDisponibilizacaoPublicacaoAPI->getDataDisponibilizacao());
        $objPublicacaoDTO->setDtaPublicacao($objEntradaConfirmarDisponibilizacaoPublicacaoAPI->getDataPublicacao());
        $objPublicacaoDTO->setNumNumero($objEntradaConfirmarDisponibilizacaoPublicacaoAPI->getNumero());
        $objPublicacaoDTO->setDblIdDocumento($dblIdDocumento);
        $arrObjPublicacaoDTO[] = $objPublicacaoDTO;
      }

      $objVeiculoPublicacao = new VeiculoPublicacaoDTO();
      $objVeiculoPublicacao->setArrObjPublicacaoDTO($arrObjPublicacaoDTO);
      $objVeiculoPublicacao->setNumIdVeiculoPublicacao($objEntradaConfirmarDisponibilizacaoPublicacaoAPI->getIdVeiculoPublicacao());

      $objPublicacaoRN = new PublicacaoRN();
      $objPublicacaoRN->confirmarDisponibilizacaoRN1115($objVeiculoPublicacao);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando confirmação de disponibilização de publicação.', $e);
    }
  }

  protected function consultarProcedimentoIndividualConectado(EntradaConsultarProcedimentoIndividualAPI $objConsultaProcedimentoIndividualAPI) {
    try {
      $objInfraException = new InfraException();

      $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
      $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
      $objTipoProcedimentoDTO->retStrNome();
      $objTipoProcedimentoDTO->retStrSinIndividual();
      $objTipoProcedimentoDTO->setNumIdTipoProcedimento($objConsultaProcedimentoIndividualAPI->getIdTipoProcedimento());

      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);

      if ($objTipoProcedimentoDTO == null) {
        $objInfraException->lancarValidacao('Tipo de processo [' . $objConsultaProcedimentoIndividualAPI->getIdTipoProcedimento() . '] não encontrado.');
      }

      if ($objTipoProcedimentoDTO->getStrSinIndividual() == 'N') {
        $objInfraException->lancarValidacao('Tipo de processo [' . $objTipoProcedimentoDTO->getStrNome() . '] não é individual.');
      }

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setNumIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
      $objProcedimentoDTO->setStrNomeTipoProcedimento($objTipoProcedimentoDTO->getStrNome());
      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_CONSULTAR_PROCEDIMENTO_INDIVIDUAL, $objProcedimentoDTO);;

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->setNumIdOrgao(array(
          $objConsultaProcedimentoIndividualAPI->getIdOrgaoProcedimento(), $objConsultaProcedimentoIndividualAPI->getIdOrgaoUsuario()
        ), InfraDTO::$OPER_IN);

      $objOrgaoRN = new OrgaoRN();
      $arrObjOrgaoDTO = InfraArray::indexarArrInfraDTO($objOrgaoRN->listarRN1353($objOrgaoDTO), 'IdOrgao');

      if (!isset($arrObjOrgaoDTO[$objConsultaProcedimentoIndividualAPI->getIdOrgaoProcedimento()])) {
        $objInfraException->lancarValidacao('Órgão do processo [' . $objConsultaProcedimentoIndividualAPI->getIdOrgaoProcedimento() . '] não encontrado.');
      }

      if (!isset($arrObjOrgaoDTO[$objConsultaProcedimentoIndividualAPI->getIdOrgaoUsuario()])) {
        $objInfraException->lancarValidacao('Órgão do usuário [' . $objConsultaProcedimentoIndividualAPI->getIdOrgaoUsuario() . '] não encontrado.');
      }

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->retStrIdOrigem();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->setNumIdOrgao($objConsultaProcedimentoIndividualAPI->getIdOrgaoUsuario());
      $objUsuarioDTO->setStrSigla(InfraString::transformarCaixaBaixa($objConsultaProcedimentoIndividualAPI->getSiglaUsuario()));

      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

      if ($objUsuarioDTO == null) {
        $objInfraException->lancarValidacao('Usuário [' . $objConsultaProcedimentoIndividualAPI->getSiglaUsuario() . '] não encontrado no órgao [' . $arrObjOrgaoDTO[$objConsultaProcedimentoIndividualAPI->getIdOrgaoUsuario()]->getStrSigla() . '].');
      }

      if ($objUsuarioDTO->getStrIdOrigem() == null) {
        $arrIdContatos = array($objUsuarioDTO->getNumIdContato());
      } else {
        //busca todos os contatos com o mesmo IdOrigem
        $objUsuarioDTOContatos = new UsuarioDTO();
        $objUsuarioDTOContatos->setBolExclusaoLogica(false);
        $objUsuarioDTOContatos->retNumIdContato();
        $objUsuarioDTOContatos->setStrIdOrigem($objUsuarioDTO->getStrIdOrigem());
        $arrIdContatos = InfraArray::converterArrInfraDTO($objUsuarioRN->listarRN0490($objUsuarioDTOContatos), 'IdContato');
      }

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setNumTipoFkProcedimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objProtocoloDTO->retDblIdProtocolo();
      $objProtocoloDTO->retStrProtocoloFormatado();
      //$objProtocoloDTO->retStrStaNivelAcessoGlobal();
      $objProtocoloDTO->setNumIdTipoProcedimentoProcedimento($objConsultaProcedimentoIndividualAPI->getIdTipoProcedimento());
      $objProtocoloDTO->setNumIdOrgaoUnidadeGeradora($objConsultaProcedimentoIndividualAPI->getIdOrgaoProcedimento());
      $objProtocoloDTO->setNumIdContatoParticipante($arrIdContatos, InfraDTO::$OPER_IN);
      $objProtocoloDTO->setStrStaParticipacaoParticipante(ParticipanteRN::$TP_INTERESSADO);

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null) {
        $objProcedimentoDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO, InfraDTO::$OPER_DIFERENTE);
      }

      $objProtocoloDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_DESC);

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTO = $objProtocoloRN->listarRN0668($objProtocoloDTO);

      if (count($arrObjProtocoloDTO)) {
        $objParticipanteRN = new ParticipanteRN();

        foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
          $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);

          if ($objParticipanteRN->contarRN0461($objParticipanteDTO) == 1) {
            if (SessaoSEI::getInstance()->isBolHabilitada() || (SessaoSEI::getInstance()->getObjServicoDTO() != null && SessaoSEI::getInstance()->getObjServicoDTO()->getNumIdUnidade() != null)) {
              $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
              $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
              $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
              $objPesquisaProtocoloDTO->setDblIdProtocolo(array($objProtocoloDTO->getDblIdProtocolo()));

              $objProtocoloRN = new ProtocoloRN();
              if (count($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO)) == 0) {
                $objInfraException->lancarValidacao('Unidade [' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '] não possui acesso ao processo [' . $objProtocoloDTO->getStrProtocoloFormatado() . '].');
              }
            }

            $objProcedimentoResumidoAPI = new ProcedimentoResumidoAPI();
            $objProcedimentoResumidoAPI->setIdProcedimento($objProtocoloDTO->getDblIdProtocolo());
            $objProcedimentoResumidoAPI->setProcedimentoFormatado($objProtocoloDTO->getStrProtocoloFormatado());

            $objTipoProcedimentoAPI = new TipoProcedimentoAPI();
            $objTipoProcedimentoAPI->setIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
            $objTipoProcedimentoAPI->setNome($objTipoProcedimentoDTO->getStrNome());
            $objProcedimentoResumidoAPI->setTipoProcedimento($objTipoProcedimentoAPI);

            return $objProcedimentoResumidoAPI;
          }
        }
      }

      return null;
      //LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

    } catch (Throwable $e) {
      throw new InfraException('Erro processando consulta de processo individual.', $e);
    }
  }

  protected function consultarDocumentoConectado(EntradaConsultarDocumentoAPI $objEntradaConsultarDocumentoAPI) {
    try {
      $objInfraException = new InfraException();

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrSinBloqueado();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retStrStaEstadoProtocolo();
      $objDocumentoDTO->retStrDescricaoProtocolo();
      $objDocumentoDTO->retNumIdSerie();
      $objDocumentoDTO->retStrNomeSerie();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retNumIdTipoProcedimentoProcedimento();
      $objDocumentoDTO->retStrNomeTipoProcedimentoProcedimento();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO->retStrNumero();
      $objDocumentoDTO->retStrNomeArvore();
      $objDocumentoDTO->retDinValor();
      $objDocumentoDTO->retDtaGeracaoProtocolo();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrStaNivelAcessoLocalProtocolo();
      $objDocumentoDTO->retStrStaNivelAcessoGlobalProtocolo();
      $objDocumentoDTO->retStrDescricaoUnidadeGeradoraProtocolo();

      if ($objEntradaConsultarDocumentoAPI->getIdDocumento() != null) {
        $objDocumentoDTO->setDblIdDocumento($objEntradaConsultarDocumentoAPI->getIdDocumento());
      } else {
        if ($objEntradaConsultarDocumentoAPI->getProtocoloDocumento() != null) {
          $objDocumentoDTO->setStrProtocoloDocumentoFormatadoPesquisa($objEntradaConsultarDocumentoAPI->getProtocoloDocumento());
        } else {
          throw new InfraException('Documento não informado.');
        }
      }

      $objDocumentoDTO->setStrStaProtocoloProtocolo(array(ProtocoloRN::$TP_DOCUMENTO_GERADO, ProtocoloRN::$TP_DOCUMENTO_RECEBIDO), InfraDTO::$OPER_IN);
      $objDocumentoDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO, InfraDTO::$OPER_DIFERENTE);

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

      if ($objDocumentoDTO == null) {
        $objInfraException->lancarValidacao('Documento ' . $objEntradaConsultarDocumentoAPI->getProtocoloDocumento() . ' não encontrado.');
      }

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null) {
        $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_CONSULTAR_DOCUMENTO, $objDocumentoDTO);;
      }

      if (InfraString::isBolVazia($objEntradaConsultarDocumentoAPI->getSinRetornarAndamentoGeracao())) {
        $objEntradaConsultarDocumentoAPI->setSinRetornarAndamentoGeracao('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarDocumentoAPI->getSinRetornarAndamentoGeracao())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para andamento de geração inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarDocumentoAPI->getSinRetornarAssinaturas())) {
        $objEntradaConsultarDocumentoAPI->setSinRetornarAssinaturas('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarDocumentoAPI->getSinRetornarAssinaturas())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para assinaturas inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarDocumentoAPI->getSinRetornarPublicacao())) {
        $objEntradaConsultarDocumentoAPI->setSinRetornarPublicacao('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarDocumentoAPI->getSinRetornarPublicacao())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para publicação inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarDocumentoAPI->getSinRetornarCampos())) {
        $objEntradaConsultarDocumentoAPI->setSinRetornarCampos('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarDocumentoAPI->getSinRetornarCampos())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para campos do formulário inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaConsultarDocumentoAPI->getSinRetornarBlocos())) {
        $objEntradaConsultarDocumentoAPI->setSinRetornarBlocos('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarDocumentoAPI->getSinRetornarBlocos())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno para blocos do documento inválido.');
        }
      }

      if ($objDocumentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_DOCUMENTO_CANCELADO) {
        $objInfraException->lancarValidacao('Documento ' . $objEntradaConsultarDocumentoAPI->getProtocoloDocumento() . ' foi cancelado.');
      }

      if (SessaoSEI::getInstance()->isBolHabilitada() || (SessaoSEI::getInstance()->getObjServicoDTO() != null && SessaoSEI::getInstance()->getObjServicoDTO()->getNumIdUnidade() != null)) {
        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
        $objPesquisaProtocoloDTO->setDblIdProtocolo(array($objDocumentoDTO->getDblIdDocumento()));

        $objProtocoloRN = new ProtocoloRN();
        if (count($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO)) == 0) {
          $objInfraException->lancarValidacao('Unidade [' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '] não possui acesso ao documento [' . $objEntradaConsultarDocumentoAPI->getProtocoloDocumento() . '].');
        }

        $objDocumentoRN->bloquearConsultado($objDocumentoDTO);
      }

      $objInfraException->lancarValidacoes();

      $objSaidaConsultarDocumentoAPI = new SaidaConsultarDocumentoAPI();
      $objSaidaConsultarDocumentoAPI->setIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
      $objSaidaConsultarDocumentoAPI->setProcedimentoFormatado($objDocumentoDTO->getStrProtocoloProcedimentoFormatado());
      $objSaidaConsultarDocumentoAPI->setIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $objSaidaConsultarDocumentoAPI->setDocumentoFormatado($objDocumentoDTO->getStrProtocoloDocumentoFormatado());

      $objSerieAPI = new SerieAPI();
      $objSerieAPI->setIdSerie($objDocumentoDTO->getNumIdSerie());
      $objSerieAPI->setNome($objDocumentoDTO->getStrNomeSerie());
      $objSaidaConsultarDocumentoAPI->setSerie($objSerieAPI);

      $objSaidaConsultarDocumentoAPI->setDescricao($objDocumentoDTO->getStrDescricaoProtocolo());
      $objSaidaConsultarDocumentoAPI->setNumero($objDocumentoDTO->getStrNumero());
      $objSaidaConsultarDocumentoAPI->setNomeArvore($objDocumentoDTO->getStrNomeArvore());
      $objSaidaConsultarDocumentoAPI->setDinValor($objDocumentoDTO->getDinValor());
      $objSaidaConsultarDocumentoAPI->setData($objDocumentoDTO->getDtaGeracaoProtocolo());
      $objSaidaConsultarDocumentoAPI->setNivelAcessoLocal($objDocumentoDTO->getStrStaNivelAcessoLocalProtocolo());
      $objSaidaConsultarDocumentoAPI->setNivelAcessoGlobal($objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo());

      $objUnidadeAPI = new UnidadeAPI();
      $objUnidadeAPI->setIdUnidade($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo());
      $objUnidadeAPI->setSigla($objDocumentoDTO->getStrSiglaUnidadeGeradoraProtocolo());
      $objUnidadeAPI->setDescricao($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo());
      $objSaidaConsultarDocumentoAPI->setUnidadeElaboradora($objUnidadeAPI);

      $objAndamentoAPI = null;
      if ($objEntradaConsultarDocumentoAPI->getSinRetornarAndamentoGeracao() == 'S') {
        $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
        $objProcedimentoHistoricoDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
        $objProcedimentoHistoricoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_PERSONALIZADO);
        $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('N');
        $objProcedimentoHistoricoDTO->setNumIdTarefa(array(
            TarefaRN::$TI_GERACAO_DOCUMENTO, TarefaRN::$TI_RECEBIMENTO_DOCUMENTO, TarefaRN::$TI_DOCUMENTO_MOVIDO_DO_PROCESSO
          ), InfraDTO::$OPER_IN);
        $objProcedimentoHistoricoDTO->setNumMaxRegistrosRetorno(1);

        $objProcedimentoRN = new ProcedimentoRN();
        $objProcedimentoDTOHistorico = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
        $arrObjAtividadeDTOHistorico = $objProcedimentoDTOHistorico->getArrObjAtividadeDTO();
        $objAtividadeDTO = $arrObjAtividadeDTOHistorico[0];

        $objAndamentoAPI = new AndamentoAPI();
        //$objAndamentoAPI->setIdAndamento($objAtividadeDTO->getNumIdAtividade());
        //$objAndamentoAPI->setIdTarefa($objAtividadeDTO->getNumIdTarefa());
        $objAndamentoAPI->setDescricao($objAtividadeDTO->getStrNomeTarefa());
        $objAndamentoAPI->setDataHora($objAtividadeDTO->getDthAbertura());

        $objUsuarioAPI = new UsuarioAPI();
        $objUsuarioAPI->setIdUsuario($objAtividadeDTO->getNumIdUsuarioOrigem());
        $objUsuarioAPI->setSigla($objAtividadeDTO->getStrSiglaUsuarioOrigem());
        $objUsuarioAPI->setNome($objAtividadeDTO->getStrNomeUsuarioOrigem());
        $objAndamentoAPI->setUsuario($objUsuarioAPI);

        $objUnidadeAPI = new UnidadeAPI();
        $objUnidadeAPI->setIdUnidade($objAtividadeDTO->getNumIdUnidade());
        $objUnidadeAPI->setSigla($objAtividadeDTO->getStrSiglaUnidade());
        $objUnidadeAPI->setDescricao($objAtividadeDTO->getStrDescricaoUnidade());
        $objAndamentoAPI->setUnidade($objUnidadeAPI);
      }
      $objSaidaConsultarDocumentoAPI->setAndamentoGeracao($objAndamentoAPI);

      $arrObjAssinaturaAPI = array();
      if ($objEntradaConsultarDocumentoAPI->getSinRetornarAssinaturas() == 'S') {
        $objAssinaturaDTO = new AssinaturaDTO();
        $objAssinaturaDTO->retNumIdAssinatura();
        $objAssinaturaDTO->retNumIdUsuario();
        $objAssinaturaDTO->retStrIdOrigemUsuario();
        $objAssinaturaDTO->retNumIdOrgaoUsuario();
        $objAssinaturaDTO->retStrSiglaUsuario();
        $objAssinaturaDTO->retStrNome();
        $objAssinaturaDTO->retStrTratamento();
        $objAssinaturaDTO->retDthAberturaAtividade();
        $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objAssinaturaDTO->setOrdNumIdAssinatura(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objAssinaturaRN = new AssinaturaRN();
        $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);

        foreach ($arrObjAssinaturaDTO as $objAssinaturaDTO) {
          $objAssinaturaAPI = new AssinaturaAPI();
          $objAssinaturaAPI->setNome($objAssinaturaDTO->getStrNome());
          $objAssinaturaAPI->setCargoFuncao($objAssinaturaDTO->getStrTratamento());
          $objAssinaturaAPI->setDataHora($objAssinaturaDTO->getDthAberturaAtividade());
          $objAssinaturaAPI->setIdUsuario($objAssinaturaDTO->getNumIdUsuario());
          $objAssinaturaAPI->setIdOrigem($objAssinaturaDTO->getStrIdOrigemUsuario());
          $objAssinaturaAPI->setIdOrgao($objAssinaturaDTO->getNumIdOrgaoUsuario());
          $objAssinaturaAPI->setSigla($objAssinaturaDTO->getStrSiglaUsuario());
          $arrObjAssinaturaAPI[] = $objAssinaturaAPI;
        }
      }
      $objSaidaConsultarDocumentoAPI->setAssinaturas($arrObjAssinaturaAPI);

      $objPublicacaoAPI = null;
      if ($objEntradaConsultarDocumentoAPI->getSinRetornarPublicacao() == 'S') {
        $objEntradaConsultarPublicaoAPI = new EntradaConsultarPublicacaoAPI();
        $objEntradaConsultarPublicaoAPI->setIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objSaidaConsultarPublicacaoAPI = $this->consultarPublicacao($objEntradaConsultarPublicaoAPI);
        if ($objSaidaConsultarPublicacaoAPI != null) {
          $objPublicacaoAPI = $objSaidaConsultarPublicacaoAPI->getPublicacao();
        }
      }
      $objSaidaConsultarDocumentoAPI->setPublicacao($objPublicacaoAPI);

      $arrObjCampoAPI = array();
      if ($objEntradaConsultarDocumentoAPI->getSinRetornarCampos() == 'S') {
        $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
        $objRelProtocoloAtributoDTO->retStrNomeAtributo();
        $objRelProtocoloAtributoDTO->retStrValor();
        $objRelProtocoloAtributoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
        $objRelProtocoloAtributoDTO->setOrdNumOrdemAtributo(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objRelProtocoloAtributoDTO->setOrdStrRotuloAtributo(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
        $arrObjRelProtocoloAtributoDTO = $objRelProtocoloAtributoRN->listar($objRelProtocoloAtributoDTO);

        foreach ($arrObjRelProtocoloAtributoDTO as $objRelProtocoloAtributoDTO) {
          $objCampoAPI = new CampoAPI();
          $objCampoAPI->setNome($objRelProtocoloAtributoDTO->getStrNomeAtributo());
          $objCampoAPI->setValor($objRelProtocoloAtributoDTO->getStrValor());
          $arrObjCampoAPI[] = $objCampoAPI;
        }
      }
      $objSaidaConsultarDocumentoAPI->setCampos($arrObjCampoAPI);

      $arrObjBlocoAPI = array();
      if ($objEntradaConsultarDocumentoAPI->getSinRetornarBlocos() == 'S') {
        $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
        $objRelBlocoProtocoloDTO->retNumIdBloco();
        $objRelBlocoProtocoloDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

        $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
        $arrObjRelBlocoProtocoloDTO = $objRelBlocoProtocoloRN->listarRN1291($objRelBlocoProtocoloDTO);

        if (count($arrObjRelBlocoProtocoloDTO)) {
          $objBlocoRN = new BlocoRN();

          $objBlocoDTO = new BlocoDTO();
          $objBlocoDTO->retNumIdBloco();
          $objBlocoRN->configurarFiltroBlocosUnidade($objBlocoDTO);
          $objBlocoDTO->setNumIdBloco(InfraArray::converterArrInfraDTO($arrObjRelBlocoProtocoloDTO, 'IdBloco'), InfraDTO::$OPER_IN);
          $arrObjBlocoDTO = $objBlocoRN->listarRN1277($objBlocoDTO);

          foreach ($arrObjBlocoDTO as $objBlocoDTO) {
            $objEntradaConsultarBlocoAPI = new EntradaConsultarBlocoAPI();
            $objEntradaConsultarBlocoAPI->setIdBloco($objBlocoDTO->getNumIdBloco());
            $objSaidaConsultarBlocoAPI = $this->consultarBloco($objEntradaConsultarBlocoAPI);
            if ($objSaidaConsultarBlocoAPI != null) {
              $objBlocoAPI = new BlocoAPI();
              $objBlocoAPI->setIdBloco($objSaidaConsultarBlocoAPI->getIdBloco());
              $objBlocoAPI->setDescricao($objSaidaConsultarBlocoAPI->getDescricao());
              $objBlocoAPI->setTipo($objSaidaConsultarBlocoAPI->getTipo());
              $objBlocoAPI->setEstado($objSaidaConsultarBlocoAPI->getEstado());
              $objBlocoAPI->setUnidade($objSaidaConsultarBlocoAPI->getUnidade());
              $objBlocoAPI->setUsuario($objSaidaConsultarBlocoAPI->getUsuario());
              $objBlocoAPI->setUnidadesDisponibilizacao($objSaidaConsultarBlocoAPI->getUnidadesDisponibilizacao());
              $objBlocoAPI->setSinPrioridade($objSaidaConsultarBlocoAPI->getSinPrioridade());
              $objBlocoAPI->setSinRevisao($objSaidaConsultarBlocoAPI->getSinRevisao());
              $objBlocoAPI->setUsuarioAtribuicao($objSaidaConsultarBlocoAPI->getUsuarioAtribuicao());
              $arrObjBlocoAPI[] = $objBlocoAPI;
            }
          }
        }
      }
      $objSaidaConsultarDocumentoAPI->setBlocos($arrObjBlocoAPI);

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null && SessaoSEI::getInstance()->getObjServicoDTO()->getStrSinLinkExterno() == 'S') {
        $objAcessoExternoDTO = $this->obterAcessoExternoSistema($objDocumentoDTO->getDblIdProcedimento());
        $objSaidaConsultarDocumentoAPI->setLinkAcesso(SessaoSEIExterna::getInstance($objAcessoExternoDTO->getNumIdAcessoExterno())->assinarLink(ConfiguracaoSEI::getInstance()->getValor('SEI',
            'URL') . '/documento_consulta_externa.php?id_acesso_externo=' . $objAcessoExternoDTO->getNumIdAcessoExterno() . '&id_documento=' . $objDocumentoDTO->getDblIdDocumento()));
      } else {
        $objSaidaConsultarDocumentoAPI->setLinkAcesso(ConfiguracaoSEI::getInstance()->getValor('SEI',
            'URL') . '/controlador.php?acao=procedimento_trabalhar&id_procedimento=' . $objDocumentoDTO->getDblIdProcedimento() . '&id_documento=' . $objDocumentoDTO->getDblIdDocumento());
      }

      return $objSaidaConsultarDocumentoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando consulta de documento.', $e);
    }
  }

  protected function cancelarDocumentoControlado(EntradaCancelarDocumentoAPI $objEntradaCancelarDocumentoAPI) {
    try {
      $objDocumentoDTO = $this->obterDocumento($objEntradaCancelarDocumentoAPI->getIdDocumento(), $objEntradaCancelarDocumentoAPI->getProtocoloDocumento());

      $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_CANCELAR_DOCUMENTO, $objDocumentoDTO);

      $dto = new DocumentoDTO();
      $dto->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $dto->setStrMotivoCancelamento($objEntradaCancelarDocumentoAPI->getMotivo());

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoRN->cancelar($dto);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando cancelamento de documento.', $e);
    }
  }

  protected function bloquearDocumentoControlado(EntradaBloquearDocumentoAPI $objEntradaBloquearDocumentoAPI) {
    try {
      $objDocumentoDTO = $this->obterDocumento($objEntradaBloquearDocumentoAPI->getIdDocumento(), $objEntradaBloquearDocumentoAPI->getProtocoloDocumento());

      $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_BLOQUEAR_DOCUMENTO, $objDocumentoDTO);

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoRN->bloquearConteudo($objDocumentoDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando bloqueio de documento.', $e);
    }
  }

  protected function gerarBlocoControlado(EntradaGerarBlocoAPI $objEntradaGerarBlocoAPI) {
    try {
      $objInfraException = new InfraException();

      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_GERAR_BLOCO);

      if (!InfraUtil::isBolSinalizadorValido($objEntradaGerarBlocoAPI->getSinDisponibilizar())) {
        $objInfraException->adicionarValidacao('Sinalizador de Disponibilização de bloco inválido.');
      }

      $objInfraException->lancarValidacoes();

      $objBlocoDTO2 = new BlocoDTO();
      $objBlocoDTO2->setNumIdBloco(null);
      $objBlocoDTO2->setStrStaTipo($objEntradaGerarBlocoAPI->getTipo());
      $objBlocoDTO2->setStrDescricao($objEntradaGerarBlocoAPI->getDescricao());
      $objBlocoDTO2->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objBlocoDTO2->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objBlocoDTO2->setStrIdxBloco(null);
      $objBlocoDTO2->setStrStaEstado(BlocoRN::$TE_ABERTO);

      $arrObjRelBlocoUnidadeDTO = array();
      if ($objEntradaGerarBlocoAPI->getUnidadesDisponibilizacao() != null) {
        foreach ($objEntradaGerarBlocoAPI->getUnidadesDisponibilizacao() as $numIdUnidade) {
          $objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
          $objRelBlocoUnidadeDTO->setNumIdUnidade($numIdUnidade);
          $arrObjRelBlocoUnidadeDTO[] = $objRelBlocoUnidadeDTO;
        }
      }
      $objBlocoDTO2->setArrObjRelBlocoUnidadeDTO($arrObjRelBlocoUnidadeDTO);

      $objBlocoRN = new BlocoRN();
      $objBlocoDTORet = $objBlocoRN->cadastrarRN1273($objBlocoDTO2);

      if ($objEntradaGerarBlocoAPI->getDocumentos() != null) {
        foreach ($objEntradaGerarBlocoAPI->getDocumentos() as $strProtocoloDocumento) {
          $objEntradaIncluirDocumentoBlocoAPI = new EntradaIncluirDocumentoBlocoAPI();
          $objEntradaIncluirDocumentoBlocoAPI->setIdBloco($objBlocoDTORet->getNumIdBloco());
          $objEntradaIncluirDocumentoBlocoAPI->setProtocoloDocumento($strProtocoloDocumento);
          $objEntradaIncluirDocumentoBlocoAPI->setAnotacao(null);
          $this->incluirDocumentoBloco($objEntradaIncluirDocumentoBlocoAPI);
        }
      }

      if ($objEntradaGerarBlocoAPI->getIdDocumentos() != null) {
        foreach ($objEntradaGerarBlocoAPI->getIdDocumentos() as $dblIdDocumento) {
          $objEntradaIncluirDocumentoBlocoAPI = new EntradaIncluirDocumentoBlocoAPI();
          $objEntradaIncluirDocumentoBlocoAPI->setIdBloco($objBlocoDTORet->getNumIdBloco());
          $objEntradaIncluirDocumentoBlocoAPI->setIdDocumento($dblIdDocumento);
          $objEntradaIncluirDocumentoBlocoAPI->setAnotacao(null);
          $this->incluirDocumentoBloco($objEntradaIncluirDocumentoBlocoAPI);
        }
      }

      if ($objEntradaGerarBlocoAPI->getSinDisponibilizar() == 'S') {
        $objEntradaDisponibilizarBlocoAPI = new EntradaDisponibilizarBlocoAPI();
        $objEntradaDisponibilizarBlocoAPI->setIdBloco($objBlocoDTORet->getNumIdBloco());
        $this->disponibilizarBloco($objEntradaDisponibilizarBlocoAPI);
      }

      return $objBlocoDTORet->getNumIdBloco();
    } catch (Throwable $e) {
      throw new InfraException('Erro processando geração de bloco.', $e);
    }
  }

  protected function consultarBlocoConectado(EntradaConsultarBlocoAPI $objEntradaConsultarBlocoAPI) {
    try {
      $objInfraException = new InfraException();

      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_CONSULTAR_BLOCO);

      if (InfraString::isBolVazia($objEntradaConsultarBlocoAPI->getSinRetornarProtocolos())) {
        $objEntradaConsultarBlocoAPI->setSinRetornarProtocolos('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaConsultarBlocoAPI->getSinRetornarProtocolos())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno de protocolos inválido.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objBlocoDTO2 = new BlocoDTO();
      $objBlocoDTO2->retNumIdBloco();
      $objBlocoDTO2->retNumIdUnidade();
      $objBlocoDTO2->retStrSiglaUnidade();
      $objBlocoDTO2->retStrDescricaoUnidade();
      $objBlocoDTO2->retNumIdUsuario();
      $objBlocoDTO2->retStrSiglaUsuario();
      $objBlocoDTO2->retStrNomeUsuario();
      $objBlocoDTO2->retStrDescricao();
      $objBlocoDTO2->retStrStaTipo();
      $objBlocoDTO2->retStrStaEstado();
      $objBlocoDTO2->retObjRelBlocoUnidadeDTO();
      $objBlocoDTO2->retArrObjRelBlocoUnidadeDTO();

      $objBlocoDTO2->setNumIdBloco($objEntradaConsultarBlocoAPI->getIdBloco());

      $objBlocoRN = new BlocoRN();
      $arrObjBlocoDTORet = $objBlocoRN->pesquisar($objBlocoDTO2);

      if (count($arrObjBlocoDTORet) == 0) {
        throw new InfraException('Bloco ' . $objEntradaConsultarBlocoAPI->getIdBloco() . ' não encontrado.');
      }

      $objBlocoDTORet = $arrObjBlocoDTORet[0];

      if ($objBlocoDTORet->getNumIdUnidade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objBlocoDTORet->getStrStaEstado() != BlocoRN::$TE_RECEBIDO) {
        $objInfraException->lancarValidacao('Unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . ' não têm acesso ao Bloco ' . $objEntradaConsultarBlocoAPI->getIdBloco() . '.');
      }

      $objSaidaConsultarBlocoAPI = new SaidaConsultarBlocoAPI();
      $objSaidaConsultarBlocoAPI->setIdBloco($objBlocoDTORet->getNumIdBloco());
      $objSaidaConsultarBlocoAPI->setDescricao($objBlocoDTORet->getStrDescricao());
      $objSaidaConsultarBlocoAPI->setTipo($objBlocoDTORet->getStrStaTipo());
      $objSaidaConsultarBlocoAPI->setEstado($objBlocoDTORet->getStrStaEstado());

      $objUnidadeAPI = new UnidadeAPI();
      $objUnidadeAPI->setIdUnidade($objBlocoDTORet->getNumIdUnidade());
      $objUnidadeAPI->setSigla($objBlocoDTORet->getStrSiglaUnidade());
      $objUnidadeAPI->setDescricao($objBlocoDTORet->getStrDescricaoUnidade());
      $objSaidaConsultarBlocoAPI->setUnidade($objUnidadeAPI);

      $objUsuarioAPI = new UsuarioAPI();
      $objUsuarioAPI->setIdUsuario($objBlocoDTORet->getNumIdUsuario());
      $objUsuarioAPI->setSigla($objBlocoDTORet->getStrSiglaUsuario());
      $objUsuarioAPI->setNome($objBlocoDTORet->getStrNomeUsuario());
      $objSaidaConsultarBlocoAPI->setUsuario($objUsuarioAPI);

      $arrObjRelBlocoUnidadeDTO = $objBlocoDTORet->getArrObjRelBlocoUnidadeDTO();
      $arrUnidadesDisponibilizacao = array();
      foreach ($arrObjRelBlocoUnidadeDTO as $objRelBlocoUnidadeDTO) {
        $objUnidadeAPI = new UnidadeAPI();
        $objUnidadeAPI->setIdUnidade($objRelBlocoUnidadeDTO->getNumIdUnidade());
        $objUnidadeAPI->setSigla($objRelBlocoUnidadeDTO->getStrSiglaUnidade());
        $objUnidadeAPI->setDescricao($objRelBlocoUnidadeDTO->getStrDescricaoUnidade());
        $arrUnidadesDisponibilizacao[] = $objUnidadeAPI;
      }
      $objSaidaConsultarBlocoAPI->setUnidadesDisponibilizacao($arrUnidadesDisponibilizacao);


      $objSaidaConsultarBlocoAPI->setSinPrioridade('N');
      $objSaidaConsultarBlocoAPI->setSinRevisao('N');
      $objSaidaConsultarBlocoAPI->setUsuarioAtribuicao(null);

      $objRelBlocoUnidadeDTO = $objBlocoDTORet->getObjRelBlocoUnidadeDTO();

      if ($objRelBlocoUnidadeDTO != null) {
        $objSaidaConsultarBlocoAPI->setSinRevisao($objRelBlocoUnidadeDTO->getStrSinRevisao());
        $objSaidaConsultarBlocoAPI->setSinPrioridade($objRelBlocoUnidadeDTO->getStrSinPrioridade());

        if ($objRelBlocoUnidadeDTO->getNumIdUsuarioAtribuicao() != null) {
          $objUsuarioAPI = new UsuarioAPI();
          $objUsuarioAPI->setIdUsuario($objRelBlocoUnidadeDTO->getNumIdUsuarioAtribuicao());
          $objUsuarioAPI->setSigla($objRelBlocoUnidadeDTO->getStrSiglaUsuarioAtribuicao());
          $objUsuarioAPI->setNome($objRelBlocoUnidadeDTO->getStrNomeUsuarioAtribuicao());
          $objSaidaConsultarBlocoAPI->setUsuarioAtribuicao($objUsuarioAPI);
        }
      }

      $arrProtocolos = array();
      if ($objEntradaConsultarBlocoAPI->getSinRetornarProtocolos() == 'S') {
        $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
        $objRelBlocoProtocoloDTO->setNumIdBloco($objBlocoDTORet->getNumIdBloco());
        $objRelBlocoProtocoloDTO->retObjProtocoloDTO();
        $objRelBlocoProtocoloDTO->retArrObjAssinaturaDTO();

        $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
        $arrObjRelBlocoProtocoloDTO = $objRelBlocoProtocoloRN->listarProtocolosBloco($objRelBlocoProtocoloDTO);
        foreach ($arrObjRelBlocoProtocoloDTO as $objRelBlocoProtocoloDTO) {
          $objProtocoloBlocoAPI = new ProtocoloBlocoAPI();
          $objProtocoloBlocoAPI->setProtocoloFormatado($objRelBlocoProtocoloDTO->getObjProtocoloDTO()->getStrProtocoloFormatado());
          $objProtocoloBlocoAPI->setIdentificacao(ProtocoloINT::formatarIdentificacao($objRelBlocoProtocoloDTO->getObjProtocoloDTO()));

          $arrObjAssinaturaDTO = $objRelBlocoProtocoloDTO->getArrObjAssinaturaDTO();
          $arrAssinaturas = array();
          foreach ($arrObjAssinaturaDTO as $objAssinaturaDTO) {
            $objAssinaturaAPI = new AssinaturaAPI();
            $objAssinaturaAPI->setNome($objAssinaturaDTO->getStrNome());
            $objAssinaturaAPI->setCargoFuncao($objAssinaturaDTO->getStrTratamento());
            $objAssinaturaAPI->setDataHora($objAssinaturaDTO->getDthAberturaAtividade());
            $objAssinaturaAPI->setIdUsuario($objAssinaturaDTO->getNumIdUsuario());
            $objAssinaturaAPI->setIdOrigem($objAssinaturaDTO->getStrIdOrigemUsuario());
            $objAssinaturaAPI->setIdOrgao($objAssinaturaDTO->getNumIdOrgaoUsuario());
            $objAssinaturaAPI->setSigla($objAssinaturaDTO->getStrSiglaUsuario());
            $arrAssinaturas[] = $objAssinaturaAPI;
          }
          $objProtocoloBlocoAPI->setAssinaturas($arrAssinaturas);
          $arrProtocolos[] = $objProtocoloBlocoAPI;
        }
      }
      $objSaidaConsultarBlocoAPI->setProtocolos($arrProtocolos);

      return $objSaidaConsultarBlocoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando consulta de bloco.', $e);
    }
  }

  protected function excluirBlocoControlado(EntradaExcluirBlocoAPI $objEntradaExcluirBlocoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_EXCLUIR_BLOCO);

      $objBlocoDTO = $this->obterBloco($objEntradaExcluirBlocoAPI->getIdBloco());

      $objBlocoRN = new BlocoRN();
      $objBlocoRN->excluirRN1275(array($objBlocoDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando exclusão de bloco.', $e);
    }
  }

  protected function concluirBlocoControlado(EntradaConcluirBlocoAPI $objEntradaConcluirBlocoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_CONCLUIR_BLOCO);

      $objBlocoDTO = $this->obterBloco($objEntradaConcluirBlocoAPI->getIdBloco());

      $objBlocoRN = new BlocoRN();
      $objBlocoRN->concluir(array($objBlocoDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando conclusão de bloco.', $e);
    }
  }

  protected function reabrirBlocoControlado(EntradaReabrirBlocoAPI $objEntradaReabrirBlocoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_REABRIR_BLOCO);

      $objBlocoDTO = $this->obterBloco($objEntradaReabrirBlocoAPI->getIdBloco());

      $objBlocoRN = new BlocoRN();
      $objBlocoRN->reabrir($objBlocoDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando reabertura de bloco.', $e);
    }
  }

  protected function devolverBlocoControlado(EntradaDevolverBlocoAPI $objEntradaDevolverBlocoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_DEVOLVER_BLOCO);

      $objBlocoDTO = $this->obterBloco($objEntradaDevolverBlocoAPI->getIdBloco());

      $objBlocoRN = new BlocoRN();
      $objBlocoRN->retornar(array($objBlocoDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando devolução de bloco.', $e);
    }
  }

  protected function excluirProcessoControlado(EntradaExcluirProcessoAPI $objEntradaExcluirProcessoAPI) {
    try {
      $objProcedimentoDTO = $this->obterProcesso($objEntradaExcluirProcessoAPI->getIdProcedimento(), $objEntradaExcluirProcessoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_EXCLUIR_PROCEDIMENTO, $objProcedimentoDTO);

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->excluirRN0280($objProcedimentoDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando exclusão de processo.', $e);
    }
  }

  protected function excluirDocumentoControlado(EntradaExcluirDocumentoAPI $objEntradaExcluirDocumentoAPI) {
    try {
      $objDocumentoDTO = $this->obterDocumento($objEntradaExcluirDocumentoAPI->getIdDocumento(), $objEntradaExcluirDocumentoAPI->getProtocoloDocumento());

      $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_EXCLUIR_DOCUMENTO, $objDocumentoDTO);

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoRN->excluirRN0006($objDocumentoDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando exclusão de documento.', $e);
    }
  }

  protected function disponibilizarBlocoControlado(EntradaDisponibilizarBlocoAPI $objEntradaDisponibilizarBlocoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_DISPONIBILIZAR_BLOCO);

      $objBlocoDTO = $this->obterBloco($objEntradaDisponibilizarBlocoAPI->getIdBloco());

      $objBlocoRN = new BlocoRN();
      $objBlocoRN->disponibilizar(array($objBlocoDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando disponibilização de bloco.', $e);
    }
  }

  protected function cancelarDisponibilizacaoBlocoControlado(EntradaCancelarDisponibilizacaoBlocoAPI $objEntradaCancelarDisponibilizacaoBlocoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_CANCELAR_DISPONIBILIZACAO_BLOCO);

      $objBlocoDTO = $this->obterBloco($objEntradaCancelarDisponibilizacaoBlocoAPI->getIdBloco());

      $objBlocoRN = new BlocoRN();
      $objBlocoRN->cancelarDisponibilizacao(array($objBlocoDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando cancelamento de disponibilização de bloco.', $e);
    }
  }

  protected function incluirDocumentoBlocoControlado(EntradaIncluirDocumentoBlocoAPI $objEntradaIncluirDocumentoBlocoAPI) {
    try {
      $objDocumentoDTO = $this->obterDocumento($objEntradaIncluirDocumentoBlocoAPI->getIdDocumento(), $objEntradaIncluirDocumentoBlocoAPI->getProtocoloDocumento());

      $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_INCLUIR_DOCUMENTO_BLOCO, $objDocumentoDTO);

      $objBlocoDTO = $this->obterBloco($objEntradaIncluirDocumentoBlocoAPI->getIdBloco());

      $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
      $objRelBlocoProtocoloDTO->setNumIdBloco($objBlocoDTO->getNumIdBloco());
      $objRelBlocoProtocoloDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
      $objRelBlocoProtocoloDTO->setStrAnotacao($objEntradaIncluirDocumentoBlocoAPI->getAnotacao());

      $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
      $objRelBlocoProtocoloRN->cadastrarMultiplo(array($objRelBlocoProtocoloDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando inclusão de documento em bloco.', $e);
    }
  }

  protected function retirarDocumentoBlocoControlado(EntradaRetirarDocumentoBlocoAPI $objEntradaRetirarDocumentoBlocoAPI) {
    try {
      $objDocumentoDTO = $this->obterDocumento($objEntradaRetirarDocumentoBlocoAPI->getIdDocumento(), $objEntradaRetirarDocumentoBlocoAPI->getProtocoloDocumento());

      $this->validarCriteriosUnidadeProcessoDocumento(OperacaoServicoRN::$TS_RETIRAR_DOCUMENTO_BLOCO, $objDocumentoDTO);

      $objBlocoDTO = $this->obterBloco($objEntradaRetirarDocumentoBlocoAPI->getIdBloco());

      $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
      $objRelBlocoProtocoloDTO->setNumIdBloco($objBlocoDTO->getNumIdBloco());
      $objRelBlocoProtocoloDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
      $objRelBlocoProtocoloRN->excluirRN1289(array($objRelBlocoProtocoloDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando retirada de documento de bloco.', $e);
    }
  }

  protected function incluirProcessoBlocoControlado(EntradaIncluirProcessoBlocoAPI $objEntradaIncluirProcessoBlocoAPI) {
    try {
      $objProcedimentoDTO = $this->obterProcesso($objEntradaIncluirProcessoBlocoAPI->getIdProcedimento(), $objEntradaIncluirProcessoBlocoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_INCLUIR_PROCEDIMENTO_BLOCO, $objProcedimentoDTO);

      $objBlocoDTO = $this->obterBloco($objEntradaIncluirProcessoBlocoAPI->getIdBloco());

      $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
      $objRelBlocoProtocoloDTO->setNumIdBloco($objBlocoDTO->getNumIdBloco());
      $objRelBlocoProtocoloDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
      $objRelBlocoProtocoloDTO->setStrAnotacao($objEntradaIncluirProcessoBlocoAPI->getAnotacao());

      $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
      $objRelBlocoProtocoloRN->cadastrarMultiplo(array($objRelBlocoProtocoloDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando inclusão de processo em bloco.', $e);
    }
  }

  protected function retirarProcessoBlocoControlado(EntradaRetirarProcessoBlocoAPI $objEntradaRetirarProcessoBlocoAPI) {
    try {
      $objProcedimentoDTO = $this->obterProcesso($objEntradaRetirarProcessoBlocoAPI->getIdProcedimento(), $objEntradaRetirarProcessoBlocoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_RETIRAR_PROCEDIMENTO_BLOCO, $objProcedimentoDTO);

      $objBlocoDTO = $this->obterBloco($objEntradaRetirarProcessoBlocoAPI->getIdBloco());

      $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
      $objRelBlocoProtocoloDTO->setNumIdBloco($objBlocoDTO->getNumIdBloco());
      $objRelBlocoProtocoloDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());

      $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
      $objRelBlocoProtocoloRN->excluirRN1289(array($objRelBlocoProtocoloDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando retirada de processo de bloco.', $e);
    }
  }

  protected function reabrirProcessoControlado(EntradaReabrirProcessoAPI $objEntradaReabrirProcessoAPI) {
    try {
      $objProcedimentoDTO = $this->obterProcesso($objEntradaReabrirProcessoAPI->getIdProcedimento(), $objEntradaReabrirProcessoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_REABRIR_PROCEDIMENTO, $objProcedimentoDTO);

      $objReabrirProcessoDTO = new ReabrirProcessoDTO();
      $objReabrirProcessoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objReabrirProcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objReabrirProcessoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->reabrirRN0966($objReabrirProcessoDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando reabertura de processo.', $e);
    }
  }

  protected function concluirProcessoControlado(EntradaConcluirProcessoAPI $objEntradaConcluirProcessoAPI) {
    try {
      $objProcedimentoDTO = $this->obterProcesso($objEntradaConcluirProcessoAPI->getIdProcedimento(), $objEntradaConcluirProcessoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_CONCLUIR_PROCEDIMENTO, $objProcedimentoDTO);

      $objConcluirProcessoDTO = new ConcluirProcessoDTO();
      $objConcluirProcessoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->concluir($objConcluirProcessoDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando conclusão de processo.', $e);
    }
  }

  protected function enviarProcessoControlado(EntradaEnviarProcessoAPI $objEntradaEnviarProcessoAPI) {
    try {
      $objInfraException = new InfraException();

      $objProcedimentoDTO = $this->obterProcesso($objEntradaEnviarProcessoAPI->getIdProcedimento(), $objEntradaEnviarProcessoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_ENVIAR_PROCEDIMENTO, $objProcedimentoDTO);

      if (InfraString::isBolVazia($objEntradaEnviarProcessoAPI->getSinManterAbertoUnidade())) {
        $objEntradaEnviarProcessoAPI->setSinManterAbertoUnidade('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaEnviarProcessoAPI->getSinManterAbertoUnidade())) {
          $objInfraException->adicionarValidacao('Sinalizador de manutenção do processo aberto na unidade inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaEnviarProcessoAPI->getSinRemoverAnotacao())) {
        $objEntradaEnviarProcessoAPI->setSinRemoverAnotacao('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaEnviarProcessoAPI->getSinRemoverAnotacao())) {
          $objInfraException->adicionarValidacao('Sinalizador de remoção da anotação do processo inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaEnviarProcessoAPI->getSinEnviarEmailNotificacao())) {
        $objEntradaEnviarProcessoAPI->setSinEnviarEmailNotificacao('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaEnviarProcessoAPI->getSinEnviarEmailNotificacao())) {
          $objInfraException->adicionarValidacao('Sinalizador de envio de e-mail de notificação inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaEnviarProcessoAPI->getSinDiasUteisRetornoProgramado())) {
        $objEntradaEnviarProcessoAPI->setSinDiasUteisRetornoProgramado('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaEnviarProcessoAPI->getSinDiasUteisRetornoProgramado())) {
          $objInfraException->adicionarValidacao('Sinalizador de dias úteis para retorno programado inválido.');
        }
      }

      if (InfraString::isBolVazia($objEntradaEnviarProcessoAPI->getSinReabrir())) {
        $objEntradaEnviarProcessoAPI->setSinReabrir('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaEnviarProcessoAPI->getSinReabrir())) {
          $objInfraException->adicionarValidacao('Sinalizador de reabertura automática de processo inválido.');
        }
      }


      $objInfraException->lancarValidacoes();

      $objAtividadeRN = new AtividadeRN();

      $arrObjUnidadeDTO = array();
      if ($objEntradaEnviarProcessoAPI->getUnidadesDestino() != null) {
        foreach ($objEntradaEnviarProcessoAPI->getUnidadesDestino() as $numIdUnidade) {
          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setNumIdUnidade($numIdUnidade);
          $arrObjUnidadeDTO[] = $objUnidadeDTO;
        }
      }

      if (count($arrObjUnidadeDTO) == 0) {
        $objInfraException->adicionarValidacao('Nenhuma unidade informada para envio.');
      }

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retNumIdAtividade();
      $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setDthConclusao(null);

      $arrObjAtividadeDTOAbertas = $objAtividadeRN->listarRN0036($objAtividadeDTO);

      if (count($arrObjAtividadeDTOAbertas) == 0) {
        if ($objEntradaEnviarProcessoAPI->getSinReabrir() == 'N') {
          $objInfraException->adicionarValidacao('Processo [' . $objEntradaEnviarProcessoAPI->getProtocoloProcedimento() . '] não está aberto na unidade [' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '].');
        } else {
          $objEntradaReabrirProcessoAPI = new EntradaReabrirProcessoAPI();
          $objEntradaReabrirProcessoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
          $this->reabrirProcesso($objEntradaReabrirProcessoAPI);

          $arrObjAtividadeDTOAbertas = $objAtividadeRN->listarRN0036($objAtividadeDTO);
        }
      }

      $objInfraException->lancarValidacoes();

      $objEnviarProcessoDTO = new EnviarProcessoDTO();
      $objEnviarProcessoDTO->setArrAtividadesOrigem($arrObjAtividadeDTOAbertas);

      $arrObjAtividadeDTO = array();
      foreach ($arrObjUnidadeDTO as $objUnidadeDTODestino) {
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUsuario(null);
        $objAtividadeDTO->setNumIdUsuarioOrigem(SessaoSEI::getInstance()->getNumIdUsuario());
        $objAtividadeDTO->setNumIdUnidade($objUnidadeDTODestino->getNumIdUnidade());
        $objAtividadeDTO->setNumIdUnidadeOrigem(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $arrObjAtividadeDTO[] = $objAtividadeDTO;
      }
      $objEnviarProcessoDTO->setArrAtividades($arrObjAtividadeDTO);

      $objEnviarProcessoDTO->setStrSinManterAberto($objEntradaEnviarProcessoAPI->getSinManterAbertoUnidade());
      $objEnviarProcessoDTO->setStrSinEnviarEmailNotificacao($objEntradaEnviarProcessoAPI->getSinEnviarEmailNotificacao());
      $objEnviarProcessoDTO->setStrSinRemoverAnotacoes($objEntradaEnviarProcessoAPI->getSinRemoverAnotacao());
      $objEnviarProcessoDTO->setDtaPrazoRetornoProgramado($objEntradaEnviarProcessoAPI->getDataRetornoProgramado());
      $objEnviarProcessoDTO->setNumDiasRetornoProgramado($objEntradaEnviarProcessoAPI->getDiasRetornoProgramado());
      $objEnviarProcessoDTO->setStrSinDiasUteisRetornoProgramado($objEntradaEnviarProcessoAPI->getSinDiasUteisRetornoProgramado());

      $objAtividadeRN->enviarRN0023($objEnviarProcessoDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando envio de processo.', $e);
    }
  }

  protected function enviarEmailControlado(EntradaEnviarEmailAPI $objEntradaEnviarEmailAPI) {
    try {
      $objInfraException = new InfraException();

      $objProcedimentoDTO = $this->obterProcesso($objEntradaEnviarEmailAPI->getIdProcedimento(), $objEntradaEnviarEmailAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_ENVIAR_EMAIL, $objProcedimentoDTO);

      $objInfraException->lancarValidacoes();

      $objEmailDTO = new EmailDTO();
      $objEmailDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
      $objEmailDTO->setStrDe($objEntradaEnviarEmailAPI->getDe());
      $objEmailDTO->setStrPara($objEntradaEnviarEmailAPI->getPara());
      $objEmailDTO->setStrCCO($objEntradaEnviarEmailAPI->getCCO());
      $objEmailDTO->setStrAssunto($objEntradaEnviarEmailAPI->getAssunto());
      $objEmailDTO->setStrMensagem($objEntradaEnviarEmailAPI->getMensagem());

      $arrArquivos = array();
      if (is_array($objEntradaEnviarEmailAPI->getArquivos())) {
        foreach ($objEntradaEnviarEmailAPI->getArquivos() as $strNome => $strNomeUpload) {
          if (!file_exists(DIR_SEI_TEMP . '/' . $strNomeUpload)) {
            throw new InfraException('Arquivo ' . $strNomeUpload . ' não encontrado no diretório temporário do SEI.');
          }

          $numTamanhoArquivo = filesize(DIR_SEI_TEMP . '/' . $strNomeUpload);

          $arrArquivos[] = array(
            $strNomeUpload, $strNome, InfraData::getStrDataHoraAtual(), $numTamanhoArquivo, InfraUtil::formatarTamanhoBytes($numTamanhoArquivo)
          );
        }
      }

      $objEmailDTO->setArrArquivosUpload($arrArquivos);
      $objEmailDTO->setArrIdDocumentosProcesso($objEntradaEnviarEmailAPI->getIdDocumentos());

      $objEmailRN = new EmailRN();
      $objDocumentoDTOGerado = $objEmailRN->enviar($objEmailDTO);

      $objSaidaEnviarEmailAPI = new SaidaEnviarEmailAPI();
      $objSaidaEnviarEmailAPI->setIdDocumento($objDocumentoDTOGerado->getDblIdDocumento());
      $objSaidaEnviarEmailAPI->setDocumentoFormatado($objDocumentoDTOGerado->getStrProtocoloDocumentoFormatado());

      if (SessaoSEI::getInstance()->getObjServicoDTO() != null && SessaoSEI::getInstance()->getObjServicoDTO()->getStrSinLinkExterno() == 'S') {
        $objAcessoExternoDTO = $this->obterAcessoExternoSistema($objProcedimentoDTO->getDblIdProcedimento());
        $objSaidaEnviarEmailAPI->setLinkAcesso(SessaoSEIExterna::getInstance($objAcessoExternoDTO->getNumIdAcessoExterno())->assinarLink(ConfiguracaoSEI::getInstance()->getValor('SEI',
            'URL') . '/documento_consulta_externa.php?id_acesso_externo=' . $objAcessoExternoDTO->getNumIdAcessoExterno() . '&id_documento=' . $objDocumentoDTOGerado->getDblIdDocumento()));
      } else {
        $objSaidaEnviarEmailAPI->setLinkAcesso(ConfiguracaoSEI::getInstance()->getValor('SEI',
            'URL') . '/controlador.php?acao=procedimento_trabalhar&id_procedimento=' . $objProcedimentoDTO->getDblIdProcedimento() . '&id_documento=' . $objDocumentoDTOGerado->getDblIdDocumento());
      }

      return $objSaidaEnviarEmailAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando envio de e-mail.', $e);
    }
  }

  protected function lancarAndamentoControlado(EntradaLancarAndamentoAPI $objEntradaLancarAndamentoAPI) {
    try {
      $objInfraException = new InfraException();

      $objProcedimentoDTO = $this->obterProcesso($objEntradaLancarAndamentoAPI->getIdProcedimento(), $objEntradaLancarAndamentoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_LANCAR_ANDAMENTO, $objProcedimentoDTO);

      if (InfraString::isBolVazia($objEntradaLancarAndamentoAPI->getIdTarefa()) && InfraString::isBolVazia($objEntradaLancarAndamentoAPI->getIdTarefaModulo())) {
        throw new InfraException('Nenhum identificador de tarefa informado.');
      }

      if (!InfraString::isBolVazia($objEntradaLancarAndamentoAPI->getIdTarefa()) && !is_numeric($objEntradaLancarAndamentoAPI->getIdTarefa())) {
        throw new InfraException('Identificador de tarefa [' . $objEntradaLancarAndamentoAPI->getIdTarefa() . '] inválido.');
      }

      if (!InfraString::isBolVazia($objEntradaLancarAndamentoAPI->getIdTarefaModulo())) {
        $objTarefaDTO = new TarefaDTO();
        $objTarefaDTO->retNumIdTarefa();
        $objTarefaDTO->setStrIdTarefaModulo($objEntradaLancarAndamentoAPI->getIdTarefaModulo());

        $objTarefaRN = new TarefaRN();
        $arrObjTarefaDTO = $objTarefaRN->listar($objTarefaDTO);

        if (count($arrObjTarefaDTO) == 0) {
          throw new InfraException('Tarefa associada com o identificador [' . $objEntradaLancarAndamentoAPI->getIdTarefaModulo() . '] não encontrada.');
        } else {
          if (count($arrObjTarefaDTO) > 1) {
            throw new InfraException('Mais de uma tarefa associada com o identificador [' . $objEntradaLancarAndamentoAPI->getIdTarefaModulo() . '] encontrada.');
          } else {
            $objTarefaDTO = $arrObjTarefaDTO[0];

            if (!InfraString::isBolVazia($objEntradaLancarAndamentoAPI->getIdTarefa())) {
              if ($objEntradaLancarAndamentoAPI->getIdTarefa() != $objTarefaDTO->getNumIdTarefa()) {
                throw new InfraException('Identificador da tarefa [' . $objEntradaLancarAndamentoAPI->getIdTarefa() . '] não corresponde ao identificador do módulo [' . $objEntradaLancarAndamentoAPI->getIdTarefaModulo() . '].');
              }
            }

            $objEntradaLancarAndamentoAPI->setIdTarefa($objTarefaDTO->getNumIdTarefa());
          }
        }
      }

      if ($objEntradaLancarAndamentoAPI->getIdTarefa() < 1000 && $objEntradaLancarAndamentoAPI->getIdTarefa() != TarefaRN::$TI_ATUALIZACAO_ANDAMENTO) {
        throw new InfraException('Identificador de tarefa [' . $objEntradaLancarAndamentoAPI->getIdTarefa() . '] reservado.');
      }


      $objInfraException->lancarValidacoes();

      $arrObjAtributoAndamentoDTO = array();
      if ($objEntradaLancarAndamentoAPI->getAtributos() != null) {
        foreach ($objEntradaLancarAndamentoAPI->getAtributos() as $objAtributoAndamentoAPI) {
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome($objAtributoAndamentoAPI->getNome());
          $objAtributoAndamentoDTO->setStrValor($objAtributoAndamentoAPI->getValor());
          $objAtributoAndamentoDTO->setStrIdOrigem($objAtributoAndamentoAPI->getIdOrigem());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
        }
      }

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa($objEntradaLancarAndamentoAPI->getIdTarefa());
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

      $objAtividadeRN = new AtividadeRN();
      $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);


      $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
      $objProcedimentoHistoricoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_PERSONALIZADO);
      $objProcedimentoHistoricoDTO->setNumIdAtividade($objAtividadeDTO->getNumIdAtividade());
      $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('N');
      $objProcedimentoHistoricoDTO->setStrSinRetornarAtributos('S');

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTOHistorico = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
      $arrObjAtividadeDTOHistorico = $objProcedimentoDTOHistorico->getArrObjAtividadeDTO();
      $objAtividadeDTO = $arrObjAtividadeDTOHistorico[0];

      $objAndamentoAPI = new AndamentoAPI();
      $objAndamentoAPI->setIdAndamento($objAtividadeDTO->getNumIdAtividade());
      $objAndamentoAPI->setIdTarefa($objAtividadeDTO->getNumIdTarefa());
      $objAndamentoAPI->setIdTarefaModulo($objAtividadeDTO->getStrIdTarefaModuloTarefa());
      $objAndamentoAPI->setDescricao($objAtividadeDTO->getStrNomeTarefa());
      $objAndamentoAPI->setDataHora($objAtividadeDTO->getDthAbertura());

      $objUsuarioAPI = new UsuarioAPI();
      $objUsuarioAPI->setIdUsuario($objAtividadeDTO->getNumIdUsuarioOrigem());
      $objUsuarioAPI->setSigla($objAtividadeDTO->getStrSiglaUsuarioOrigem());
      $objUsuarioAPI->setNome($objAtividadeDTO->getStrNomeUsuarioOrigem());
      $objAndamentoAPI->setUsuario($objUsuarioAPI);

      $objUnidadeAPI = new UnidadeAPI();
      $objUnidadeAPI->setIdUnidade($objAtividadeDTO->getNumIdUnidade());
      $objUnidadeAPI->setSigla($objAtividadeDTO->getStrSiglaUnidade());
      $objUnidadeAPI->setDescricao($objAtividadeDTO->getStrDescricaoUnidade());
      $objAndamentoAPI->setUnidade($objUnidadeAPI);

      $arrObjAtributoAndamentoAPI = array();
      foreach ($objAtividadeDTO->getArrObjAtributoAndamentoDTO() as $objAtributoAndamentoDTO) {
        $objAtributoAndamentoAPI = new AtributoAndamentoAPI();
        $objAtributoAndamentoAPI->setNome($objAtributoAndamentoDTO->getStrNome());
        $objAtributoAndamentoAPI->setValor($objAtributoAndamentoDTO->getStrValor());
        $objAtributoAndamentoAPI->setIdOrigem($objAtributoAndamentoDTO->getStrIdOrigem());
        $arrObjAtributoAndamentoAPI[] = $objAtributoAndamentoAPI;
      }
      $objAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);

      return $objAndamentoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando lançamento de andamento.', $e);
    }
  }

  protected function listarAndamentosConectado(EntradaListarAndamentosAPI $objEntradaListarAndamentosAPI) {
    try {
      $objInfraException = new InfraException();

      $objProcedimentoDTO = $this->obterProcesso($objEntradaListarAndamentosAPI->getIdProcedimento(), $objEntradaListarAndamentosAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_LISTAR_ANDAMENTOS, $objProcedimentoDTO);

      if ($objEntradaListarAndamentosAPI->getAndamentos() == null && $objEntradaListarAndamentosAPI->getTarefas() == null && $objEntradaListarAndamentosAPI->getTarefasModulos() == null) {
        throw new InfraException('Nenhum critério informado para pesquisa de andamentos.');
      }

      if (InfraString::isBolVazia($objEntradaListarAndamentosAPI->getSinRetornarAtributos())) {
        $objEntradaListarAndamentosAPI->setSinRetornarAtributos('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaListarAndamentosAPI->getSinRetornarAtributos())) {
          $objInfraException->adicionarValidacao('Sinalizador de retorno de atributos inválido.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
      $objProcedimentoHistoricoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_PERSONALIZADO);

      if ($objEntradaListarAndamentosAPI->getAndamentos() != null) {
        $objProcedimentoHistoricoDTO->setNumIdAtividade($objEntradaListarAndamentosAPI->getAndamentos());
      }

      if ($objEntradaListarAndamentosAPI->getTarefas() != null) {
        $objProcedimentoHistoricoDTO->setNumIdTarefa($objEntradaListarAndamentosAPI->getTarefas());
      }

      if ($objEntradaListarAndamentosAPI->getTarefasModulos() != null) {
        $objProcedimentoHistoricoDTO->setStrIdTarefaModulo($objEntradaListarAndamentosAPI->getTarefasModulos());
      }

      $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('N');
      $objProcedimentoHistoricoDTO->setStrSinRetornarAtributos('S');

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTOHistorico = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
      $arrObjAtividadeDTOHistorico = $objProcedimentoDTOHistorico->getArrObjAtividadeDTO();

      $arrObjAndamentoAPI = array();
      foreach ($arrObjAtividadeDTOHistorico as $objAtividadeDTO) {
        $objAndamentoAPI = new AndamentoAPI();
        $objAndamentoAPI->setIdAndamento($objAtividadeDTO->getNumIdAtividade());
        $objAndamentoAPI->setIdTarefa($objAtividadeDTO->getNumIdTarefa());
        $objAndamentoAPI->setIdTarefaModulo($objAtividadeDTO->getStrIdTarefaModuloTarefa());
        $objAndamentoAPI->setDescricao($objAtividadeDTO->getStrNomeTarefa());
        $objAndamentoAPI->setDataHora($objAtividadeDTO->getDthAbertura());

        $objUsuarioAPI = new UsuarioAPI();
        $objUsuarioAPI->setIdUsuario($objAtividadeDTO->getNumIdUsuarioOrigem());
        $objUsuarioAPI->setSigla($objAtividadeDTO->getStrSiglaUsuarioOrigem());
        $objUsuarioAPI->setNome($objAtividadeDTO->getStrNomeUsuarioOrigem());
        $objAndamentoAPI->setUsuario($objUsuarioAPI);

        $objUnidadeAPI = new UnidadeAPI();
        $objUnidadeAPI->setIdUnidade($objAtividadeDTO->getNumIdUnidade());
        $objUnidadeAPI->setSigla($objAtividadeDTO->getStrSiglaUnidade());
        $objUnidadeAPI->setDescricao($objAtividadeDTO->getStrDescricaoUnidade());
        $objAndamentoAPI->setUnidade($objUnidadeAPI);

        if ($objEntradaListarAndamentosAPI->getSinRetornarAtributos() == 'S') {
          $arrObjAtributoAndamentoAPI = array();
          foreach ($objAtividadeDTO->getArrObjAtributoAndamentoDTO() as $objAtributoAndamentoDTO) {
            $objAtributoAndamentoAPI = new AtributoAndamentoAPI();
            $objAtributoAndamentoAPI->setNome($objAtributoAndamentoDTO->getStrNome());
            $objAtributoAndamentoAPI->setValor($objAtributoAndamentoDTO->getStrValor());
            $objAtributoAndamentoAPI->setIdOrigem($objAtributoAndamentoDTO->getStrIdOrigem());
            $arrObjAtributoAndamentoAPI[] = $objAtributoAndamentoAPI;
          }
          $objAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);
        } else {
          $objAndamentoAPI->setAtributos(null);
        }

        $arrObjAndamentoAPI[] = $objAndamentoAPI;
      }

      return $arrObjAndamentoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de andamentos.', $e);
    }
  }

  protected function atribuirProcessoControlado(EntradaAtribuirProcessoAPI $objEntradaAtribuirProcessoAPI) {
    try {
      $objInfraException = new InfraException();

      $objProcedimentoDTO = $this->obterProcesso($objEntradaAtribuirProcessoAPI->getIdProcedimento(), $objEntradaAtribuirProcessoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_ATRIBUIR_PROCEDIMENTO, $objProcedimentoDTO);;

      $objAtividadeRN = new AtividadeRN();

      if (InfraString::isBolVazia($objEntradaAtribuirProcessoAPI->getSinReabrir())) {
        $objEntradaAtribuirProcessoAPI->setSinReabrir('N');
      } else {
        if (!InfraUtil::isBolSinalizadorValido($objEntradaAtribuirProcessoAPI->getSinReabrir())) {
          $objInfraException->adicionarValidacao('Sinalizador de reabertura automática de processo inválido.');
        }
      }

      $objInfraException->lancarValidacoes();

      if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_RESTRITO) {
        $objAcessoDTO = new AcessoDTO();
        $objAcessoDTO->retNumIdAcesso();
        $objAcessoDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objAcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_RESTRITO_UNIDADE);
        $objAcessoDTO->setNumMaxRegistrosRetorno(1);

        $objAcessoRN = new AcessoRN();
        if ($objAcessoRN->consultar($objAcessoDTO) == null) {
          $objInfraException->adicionarValidacao('Unidade [' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '] não possui acesso ao processo [' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . '].');
        }
      }

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->setNumIdUsuario($objEntradaAtribuirProcessoAPI->getIdUsuario());

      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

      if ($objUsuarioDTO == null) {
        throw new InfraException('Usuário [' . $objEntradaAtribuirProcessoAPI->getIdUsuario() . '] não encontrado.');
      }

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $arrObjUsuarioDTO = InfraArray::indexarArrInfraDTO($objUsuarioRN->listarPorUnidadeRN0812($objUnidadeDTO), 'IdUsuario');

      if (!isset($arrObjUsuarioDTO[$objUsuarioDTO->getNumIdUsuario()])) {
        $objInfraException->adicionarValidacao('Usuário [' . $objUsuarioDTO->getStrSigla() . '] não possui permissão na unidade [' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '].');
      }

      $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
      $objPesquisaPendenciaDTO->setDblIdProtocolo(array($objProcedimentoDTO->getDblIdProcedimento()));
      $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);

      if (count($arrObjProcedimentoDTO) == 0) {
        if ($objEntradaAtribuirProcessoAPI->getSinReabrir() == 'N') {
          $objInfraException->adicionarValidacao('Processo [' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . '] não está aberto na unidade [' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '].');
        } else {
          $objEntradaReabrirProcessoAPI = new EntradaReabrirProcessoAPI();
          $objEntradaReabrirProcessoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
          $this->reabrirProcesso($objEntradaReabrirProcessoAPI);
        }
      }

      $objInfraException->lancarValidacoes();

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retNumIdUsuarioAtribuicao();
      $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setDthConclusao(null);
      $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
      $objAtividadeDTO->setOrdNumIdAtividade(InfraDTO::$TIPO_ORDENACAO_DESC);
      $objAtividadeDTO = $objAtividadeRN->consultarRN0033($objAtividadeDTO);

      if ($objAtividadeDTO->getNumIdUsuarioAtribuicao() != $objUsuarioDTO->getNumIdUsuario()) {
        $objAtribuirDTO = new AtribuirDTO();
        $objAtribuirDTO->setNumIdUsuarioAtribuicao($objUsuarioDTO->getNumIdUsuario());
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objAtribuirDTO->setArrObjProtocoloDTO(array($objProtocoloDTO));
        $objAtividadeRN->atribuirRN0985($objAtribuirDTO);
      }
    } catch (Throwable $e) {
      throw new InfraException('Erro processando atribuição de processo.', $e);
    }
  }

  protected function bloquearProcessoControlado(EntradaBloquearProcessoAPI $objEntradaBloquearProcessoAPI) {
    try {
      $objProcedimentoDTO = $this->obterProcesso($objEntradaBloquearProcessoAPI->getIdProcedimento(), $objEntradaBloquearProcessoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_BLOQUEAR_PROCEDIMENTO, $objProcedimentoDTO);

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->bloquear(array($objProcedimentoDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando bloqueio de processo.', $e);
    }
  }

  protected function desbloquearProcessoControlado(EntradaDesbloquearProcessoAPI $objEntradaDesbloquearProcessoAPI) {
    try {
      $objProcedimentoDTO = $this->obterProcesso($objEntradaDesbloquearProcessoAPI->getIdProcedimento(), $objEntradaDesbloquearProcessoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_DESBLOQUEAR_PROCEDIMENTO, $objProcedimentoDTO);

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->desbloquear(array($objProcedimentoDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando desbloqueio de processo.', $e);
    }
  }

  protected function relacionarProcessoControlado(EntradaRelacionarProcessoAPI $objEntradaRelacionarProcessoAPI) {
    try {
      $objProcedimentoDTO1 = $this->obterProcesso($objEntradaRelacionarProcessoAPI->getIdProcedimento1(), $objEntradaRelacionarProcessoAPI->getProtocoloProcedimento1());
      $objProcedimentoDTO2 = $this->obterProcesso($objEntradaRelacionarProcessoAPI->getIdProcedimento2(), $objEntradaRelacionarProcessoAPI->getProtocoloProcedimento2());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_RELACIONAR_PROCEDIMENTO, $objProcedimentoDTO1);
      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_RELACIONAR_PROCEDIMENTO, $objProcedimentoDTO2);

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDTO1->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProcedimentoDTO2->getDblIdProcedimento());

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->relacionarProcedimentoRN1020($objRelProtocoloProtocoloDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando relacionamento entre processos.', $e);
    }
  }

  protected function removerRelacionamentoProcessoControlado(EntradaRemoverRelacionamentoProcessoAPI $objEntradaRemoverRelacionamentoProcessoAPI) {
    try {
      $objProcedimentoDTO1 = $this->obterProcesso($objEntradaRemoverRelacionamentoProcessoAPI->getIdProcedimento1(), $objEntradaRemoverRelacionamentoProcessoAPI->getProtocoloProcedimento1());
      $objProcedimentoDTO2 = $this->obterProcesso($objEntradaRemoverRelacionamentoProcessoAPI->getIdProcedimento2(), $objEntradaRemoverRelacionamentoProcessoAPI->getProtocoloProcedimento2());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_REMOVER_RELACIONAMENTO_PROCEDIMENTO, $objProcedimentoDTO1);
      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_REMOVER_RELACIONAMENTO_PROCEDIMENTO, $objProcedimentoDTO2);

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDTO1->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProcedimentoDTO2->getDblIdProcedimento());

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->removerRelacionamentoProcedimentoRN1021($objRelProtocoloProtocoloDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando remoção de relacionamento entre processos.', $e);
    }
  }

  protected function sobrestarProcessoControlado(EntradaSobrestarProcessoAPI $objEntradaSobrestarProcessoAPI) {
    try {
      $objProcedimentoDTO = $this->obterProcesso($objEntradaSobrestarProcessoAPI->getIdProcedimento(), $objEntradaSobrestarProcessoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_SOBRESTAR_PROCEDIMENTO, $objProcedimentoDTO);

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProcedimentoDTO->getDblIdProcedimento());

      if ($objEntradaSobrestarProcessoAPI->getIdProcedimentoVinculado() != null || $objEntradaSobrestarProcessoAPI->getProtocoloProcedimentoVinculado() != null) {
        $objProcedimentoDTOVinculado = $this->obterProcesso($objEntradaSobrestarProcessoAPI->getIdProcedimentoVinculado(), $objEntradaSobrestarProcessoAPI->getProtocoloProcedimentoVinculado());
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDTOVinculado->getDblIdProcedimento());
      } else {
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1(null);
      }

      $objRelProtocoloProtocoloDTO->setStrMotivo($objEntradaSobrestarProcessoAPI->getMotivo());

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->sobrestarRN1014(array($objRelProtocoloProtocoloDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando sobrestamento de processo.', $e);
    }
  }

  protected function removerSobrestamentoProcessoControlado(EntradaRemoverSobrestamentoProcessoAPI $objEntradaRemoverSobrestamentoProcessoAPI) {
    try {
      $objProcedimentoDTO = $this->obterProcesso($objEntradaRemoverSobrestamentoProcessoAPI->getIdProcedimento(), $objEntradaRemoverSobrestamentoProcessoAPI->getProtocoloProcedimento());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_REMOVER_SOBRESTAMENTO_PROCEDIMENTO, $objProcedimentoDTO);

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProcedimentoDTO->getDblIdProcedimento());

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->removerSobrestamentoRN1017(array($objRelProtocoloProtocoloDTO));
    } catch (Throwable $e) {
      throw new InfraException('Erro processando remoção de sobrestamento de processo.', $e);
    }
  }

  protected function anexarProcessoControlado(EntradaAnexarProcessoAPI $objEntradaAnexarProcessoAPI) {
    try {
      $objProcedimentoDTOPrincipal = $this->obterProcesso($objEntradaAnexarProcessoAPI->getIdProcedimentoPrincipal(), $objEntradaAnexarProcessoAPI->getProtocoloProcedimentoPrincipal());
      $objProcedimentoDTOAnexado = $this->obterProcesso($objEntradaAnexarProcessoAPI->getIdProcedimentoAnexado(), $objEntradaAnexarProcessoAPI->getProtocoloProcedimentoAnexado());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_ANEXAR_PROCEDIMENTO, $objProcedimentoDTOAnexado);

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDTOPrincipal->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProcedimentoDTOAnexado->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setStrMotivo(null);

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->anexar($objRelProtocoloProtocoloDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando anexação de processo.', $e);
    }
  }

  protected function desanexarProcessoControlado(EntradaDesanexarProcessoAPI $objEntradaDesanexarProcessoAPI) {
    try {
      $objProcedimentoDTOPrincipal = $this->obterProcesso($objEntradaDesanexarProcessoAPI->getIdProcedimentoPrincipal(), $objEntradaDesanexarProcessoAPI->getProtocoloProcedimentoPrincipal());
      $objProcedimentoDTOAnexado = $this->obterProcesso($objEntradaDesanexarProcessoAPI->getIdProcedimentoAnexado(), $objEntradaDesanexarProcessoAPI->getProtocoloProcedimentoAnexado());

      $this->validarCriteriosUnidadeProcesso(OperacaoServicoRN::$TS_DESANEXAR_PROCEDIMENTO, $objProcedimentoDTOAnexado);

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDTOPrincipal->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProcedimentoDTOAnexado->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setStrMotivo($objEntradaDesanexarProcessoAPI->getMotivo());

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->desanexar($objRelProtocoloProtocoloDTO);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando desanexação de processo.', $e);
    }
  }

  protected function adicionarArquivoConectado(EntradaAdicionarArquivoAPI $objEntradaAdicionarArquivoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_ADICIONAR_ARQUIVO);

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->setStrNome($objEntradaAdicionarArquivoAPI->getNome());
      $objAnexoDTO->setNumTamanho($objEntradaAdicionarArquivoAPI->getTamanho());
      $objAnexoDTO->setStrHash($objEntradaAdicionarArquivoAPI->getHash());

      $objAnexoRN = new AnexoRN();
      $strNomeArquivoUpload = $objAnexoRN->gerarNomeArquivoTemporario();

      $fp = fopen(DIR_SEI_TEMP . '/' . $strNomeArquivoUpload, 'w');
      fwrite($fp, base64_decode($objEntradaAdicionarArquivoAPI->getConteudo()));
      fclose($fp);

      $objAnexoDTO->setNumIdAnexo($strNomeArquivoUpload);
      $objAnexoDTO->setDthInclusao(InfraData::getStrDataHoraAtual());
      $objAnexoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAnexoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      $objAnexoDTO = $objAnexoRN->adicionar($objAnexoDTO);

      return $objAnexoDTO->getNumIdAnexo();
    } catch (Throwable $e) {
      throw new InfraException('Erro processando inclusão de arquivo.', $e);
    }
  }

  protected function adicionarConteudoArquivoConectado(EntradaAdicionarConteudoArquivoAPI $objEntradaAdicionarConteudoArquivoAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_ADICIONAR_CONTEUDO_ARQUIVO);

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->setNumIdAnexo($objEntradaAdicionarConteudoArquivoAPI->getIdArquivo());

      $objAnexoRN = new AnexoRN();
      $strNomeArquivoUpload = $objAnexoRN->gerarNomeArquivoTemporario();

      $fp = fopen(DIR_SEI_TEMP . '/' . $strNomeArquivoUpload, 'w');
      fwrite($fp, base64_decode($objEntradaAdicionarConteudoArquivoAPI->getConteudo()));
      fclose($fp);

      $objAnexoDTO->setNumIdAnexoOrigem($objAnexoDTO->getNumIdAnexo());
      $objAnexoDTO->setNumIdAnexo($strNomeArquivoUpload);
      $objAnexoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAnexoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      $ret = $objAnexoRN->adicionarConteudo($objAnexoDTO);

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando inclusão de conteúdo em arquivo.', $e);
    }
  }

  protected function registrarOuvidoriaControlado(EntradaRegistrarOuvidoriaAPI $objEntradaRegistrarOuvidoriaAPI) {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_REGISTRAR_OUVIDORIA);

      $objProcedimentoOuvidoriaDTO = new ProcedimentoOuvidoriaDTO();
      $objProcedimentoOuvidoriaDTO->setNumIdOrgao($objEntradaRegistrarOuvidoriaAPI->getIdOrgao());
      $objProcedimentoOuvidoriaDTO->setStrNome($objEntradaRegistrarOuvidoriaAPI->getNome());
      $objProcedimentoOuvidoriaDTO->setStrNomeSocial($objEntradaRegistrarOuvidoriaAPI->getNomeSocial());
      $objProcedimentoOuvidoriaDTO->setStrEmail($objEntradaRegistrarOuvidoriaAPI->getEmail());
      $objProcedimentoOuvidoriaDTO->setDblCpf($objEntradaRegistrarOuvidoriaAPI->getCpf());
      $objProcedimentoOuvidoriaDTO->setDblRg($objEntradaRegistrarOuvidoriaAPI->getRg());
      $objProcedimentoOuvidoriaDTO->setStrOrgaoExpedidor($objEntradaRegistrarOuvidoriaAPI->getOrgaoExpedidor());
      $objProcedimentoOuvidoriaDTO->setStrTelefone($objEntradaRegistrarOuvidoriaAPI->getTelefone());
      $objProcedimentoOuvidoriaDTO->setStrEstado($objEntradaRegistrarOuvidoriaAPI->getEstado());
      $objProcedimentoOuvidoriaDTO->setStrCidade($objEntradaRegistrarOuvidoriaAPI->getCidade());
      $objProcedimentoOuvidoriaDTO->setNumIdTipoProcedimento($objEntradaRegistrarOuvidoriaAPI->getIdTipoProcedimento());
      $objProcedimentoOuvidoriaDTO->setStrProcessos($objEntradaRegistrarOuvidoriaAPI->getProcessos());
      $objProcedimentoOuvidoriaDTO->setStrSinRetorno($objEntradaRegistrarOuvidoriaAPI->getSinRetorno());
      $objProcedimentoOuvidoriaDTO->setStrMensagem($objEntradaRegistrarOuvidoriaAPI->getMensagem());

      $arrObjAtributoOuvidoriaDTO = array();
      if (is_array($objEntradaRegistrarOuvidoriaAPI->getAtributosAdicionais())) {
        foreach ($objEntradaRegistrarOuvidoriaAPI->getAtributosAdicionais() as $objAtributoOuvidoriaAPI) {
          $objAtributoOuvidoriaDTO = new AtributoOuvidoriaDTO();
          $objAtributoOuvidoriaDTO->setStrId($objAtributoOuvidoriaAPI->getId());
          $objAtributoOuvidoriaDTO->setStrNome($objAtributoOuvidoriaAPI->getNome());
          $objAtributoOuvidoriaDTO->setStrTitulo($objAtributoOuvidoriaAPI->getTitulo());
          $objAtributoOuvidoriaDTO->setStrValor($objAtributoOuvidoriaAPI->getValor());
          $arrObjAtributoOuvidoriaDTO[] = $objAtributoOuvidoriaDTO;
        }
      }
      $objProcedimentoOuvidoriaDTO->setArrObjAtributoOuvidoriaDTO($arrObjAtributoOuvidoriaDTO);

      $objOuvidoriaRN = new OuvidoriaRN();
      $objProcedimentoDTO = $objOuvidoriaRN->registrarOuvidoriaRN1148($objProcedimentoOuvidoriaDTO);

      $objProcedimentoResumidoAPI = new ProcedimentoResumidoAPI();
      $objProcedimentoResumidoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objProcedimentoResumidoAPI->setProcedimentoFormatado($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());

      return $objProcedimentoResumidoAPI;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando registro de ouvidoria.', $e);
    }
  }

  protected function listarUnidadesConectado(EntradaListarUnidadesAPI $objEntradaListarUnidadesAPI) {
    try {
      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->retNumIdUnidade();
      $objUnidadeDTO->retStrSigla();
      $objUnidadeDTO->retStrDescricao();
      $objUnidadeDTO->retStrSinProtocolo();
      $objUnidadeDTO->retStrSinArquivamento();
      $objUnidadeDTO->retStrSinOuvidoria();

      if (!InfraString::isBolVazia($objEntradaListarUnidadesAPI->getIdUnidade())) {
        $objUnidadeDTO->setNumIdUnidade($objEntradaListarUnidadesAPI->getIdUnidade());
      }

      if (!InfraString::isBolVazia($objEntradaListarUnidadesAPI->getIdOrgao())) {
        $objUnidadeDTO->setNumIdOrgao($objEntradaListarUnidadesAPI->getIdOrgao());
      }

      if (!InfraString::isBolVazia($objEntradaListarUnidadesAPI->getPalavrasPesquisa())) {
        $objUnidadeDTO->setStrPalavrasPesquisa($objEntradaListarUnidadesAPI->getPalavrasPesquisa());
      }

      $objUnidadeDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objUnidadeRN = new UnidadeRN();
      $arrObjUnidadeDTO = $objUnidadeRN->pesquisar($objUnidadeDTO);

      $ret = array();

      foreach ($arrObjUnidadeDTO as $objUnidadeDTO) {
        $objUnidadeAPI = new UnidadeAPI();
        $objUnidadeAPI->setIdUnidade($objUnidadeDTO->getNumIdUnidade());
        $objUnidadeAPI->setSigla($objUnidadeDTO->getStrSigla());
        $objUnidadeAPI->setDescricao($objUnidadeDTO->getStrDescricao());
        $objUnidadeAPI->setSinProtocolo($objUnidadeDTO->getStrSinProtocolo());
        $objUnidadeAPI->setSinArquivamento($objUnidadeDTO->getStrSinArquivamento());
        $objUnidadeAPI->setSinOuvidoria($objUnidadeDTO->getStrSinOuvidoria());
        $ret[] = $objUnidadeAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de unidades.', $e);
    }
  }

  protected function listarTiposProcedimentoConectado() {
    try {
      $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      $objTipoProcedimentoDTO->setStrSinSomenteUtilizados('N');

      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarTiposUnidade($objTipoProcedimentoDTO);

      $ret = array();

      foreach ($arrObjTipoProcedimentoDTO as $objTipoProcedimentoDTO) {
        $objTipoProcedimentoAPI = new TipoProcedimentoAPI();
        $objTipoProcedimentoAPI->setIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
        $objTipoProcedimentoAPI->setNome($objTipoProcedimentoDTO->getStrNome());
        $ret[] = $objTipoProcedimentoAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de tipos de processo.', $e);
    }
  }

  protected function listarTiposPrioridadeConectado() {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_TIPOS_PRIORIDADE);

      $objTipoPrioridadeDTO = new TipoPrioridadeDTO();
      $objTipoPrioridadeDTO->retStrNome();
      $objTipoPrioridadeDTO->retNumIdTipoPrioridade();
      $objTipoPrioridadeDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_ASC);


      $objTipoPrioridadeRN = new TipoPrioridadeRN();
      $arrObjTipoPrioridadeDTO = $objTipoPrioridadeRN->listar($objTipoPrioridadeDTO);

      $ret = array();

      foreach ($arrObjTipoPrioridadeDTO as $objTipoPrioridadeDTO) {
        $objTipoPrioridadeAPI = new TipoPrioridadeAPI();
        $objTipoPrioridadeAPI->setIdTipoPrioridade($objTipoPrioridadeDTO->getNumIdTipoPrioridade());
        $objTipoPrioridadeAPI->setNome($objTipoPrioridadeDTO->getStrNome());
        $ret[] = $objTipoPrioridadeAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de tipos de prioridade.', $e);
    }
  }

  protected function listarTiposProcedimentoOuvidoriaConectado() {
    try {
      $this->validarCriteriosUnidade(OperacaoServicoRN::$TS_LISTAR_TIPOS_PROCEDIMENTO_OUVIDORIA);

      $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
      $objTipoProcedimentoDTO->retStrNome();
      $objTipoProcedimentoDTO->setStrSinOuvidoria('S');

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $numIdTipoEquivoco = $objInfraParametro->getValor('ID_TIPO_PROCEDIMENTO_OUVIDORIA_EQUIVOCO', false);
      if ($numIdTipoEquivoco != null) {
        $objTipoProcedimentoDTO->setNumIdTipoProcedimento($numIdTipoEquivoco, InfraDTO::$OPER_DIFERENTE);
      }

      $objTipoProcedimentoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);

      $ret = array();

      foreach ($arrObjTipoProcedimentoDTO as $objTipoProcedimentoDTO) {
        $objTipoProcedimentoAPI = new TipoProcedimentoAPI();
        $objTipoProcedimentoAPI->setIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
        $objTipoProcedimentoAPI->setNome($objTipoProcedimentoDTO->getStrNome());
        $ret[] = $objTipoProcedimentoAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de tipos de processo da ouvidoria.', $e);
    }
  }

  protected function listarSeriesConectado() {
    try {
      $objSerieDTO = new SerieDTO();
      $objSerieDTO->setStrSinSomenteUtilizados('N');

      $objSerieRN = new SerieRN();
      $arrObjSerieDTO = $objSerieRN->listarTiposUnidade($objSerieDTO);

      $ret = array();

      foreach ($arrObjSerieDTO as $objSerieDTO) {
        $objSerieAPI = new SerieAPI();
        $objSerieAPI->setIdSerie($objSerieDTO->getNumIdSerie());
        $objSerieAPI->setNome($objSerieDTO->getStrNome());
        $objSerieAPI->setAplicabilidade($objSerieDTO->getStrStaAplicabilidade());
        $ret[] = $objSerieAPI;
      }

      return $ret;
    } catch (Throwable $e) {
      throw new InfraException('Erro processando listagem de tipos de documento.', $e);
    }
  }

  private function prepararParticipantes($arrObjParticipanteDTO) {
    $objInfraException = new InfraException();

    $objContatoRN = new ContatoRN();
    $objUsuarioRN = new UsuarioRN();
    foreach ($arrObjParticipanteDTO as $objParticipanteDTO) {
      $strTipoParticipante = '';
      if ($objParticipanteDTO->getStrStaParticipacao() == ParticipanteRN::$TP_INTERESSADO) {
        $strTipoParticipante = 'Interessado';
      } else {
        if ($objParticipanteDTO->getStrStaParticipacao() == ParticipanteRN::$TP_REMETENTE) {
          $strTipoParticipante = 'Remetente';
        } else {
          if ($objParticipanteDTO->getStrStaParticipacao() == ParticipanteRN::$TP_DESTINATARIO) {
            $strTipoParticipante = 'Destinatário';
          }
        }
      }

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->retNumIdContato();

      if (!InfraString::isBolVazia($objParticipanteDTO->getNumIdContato())) {
        $objContatoDTO->setNumIdContato($objParticipanteDTO->getNumIdContato());
      } else {
        if (!InfraString::isBolVazia($objParticipanteDTO->getDblCpfContato())) {
          if (!InfraUtil::validarCpf($objParticipanteDTO->getDblCpfContato())) {
            $objInfraException->lancarValidacao('CPF do ' . $strTipoParticipante . ' inválido.');
          }
          $objContatoDTO->setDblCpf(InfraUtil::retirarFormatacao($objParticipanteDTO->getDblCpfContato()));
        } else {
          if (!InfraString::isBolVazia($objParticipanteDTO->getDblCnpjContato())) {
            if (!InfraUtil::validarCnpj($objParticipanteDTO->getDblCnpjContato())) {
              $objInfraException->lancarValidacao('CNPJ do ' . $strTipoParticipante . ' inválido.');
            }
            $objContatoDTO->setDblCnpj(InfraUtil::retirarFormatacao($objParticipanteDTO->getDblCnpjContato()));
          } else {
            if (!InfraString::isBolVazia($objParticipanteDTO->getStrSiglaContato()) && !InfraString::isBolVazia($objParticipanteDTO->getStrNomeContato())) {
              $objContatoDTO->setStrSigla($objParticipanteDTO->getStrSiglaContato());
              $objContatoDTO->setStrNome($objParticipanteDTO->getStrNomeContato());
            } else {
              if (!InfraString::isBolVazia($objParticipanteDTO->getStrSiglaContato())) {
                $objContatoDTO->setStrSigla($objParticipanteDTO->getStrSiglaContato());
              } else {
                if (!InfraString::isBolVazia($objParticipanteDTO->getStrNomeContato())) {
                  $objContatoDTO->setStrNome($objParticipanteDTO->getStrNomeContato());
                } else {
                  throw new InfraException($strTipoParticipante . ' vazio ou nulo.');
                }
              }
            }
          }
        }
      }

      $arrObjContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);

      if (count($arrObjContatoDTO)) {
        $objContatoDTO = null;

        //preferencia para contatos que representam usuarios
        foreach ($arrObjContatoDTO as $dto) {
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->setBolExclusaoLogica(false);
          $objUsuarioDTO->retNumIdUsuario();
          $objUsuarioDTO->setNumIdContato($dto->getNumIdContato());
          $objUsuarioDTO->setNumMaxRegistrosRetorno(1);

          if ($objUsuarioRN->consultarRN0489($objUsuarioDTO) != null) {
            $objContatoDTO = $dto;
            break;
          }
        }

        //nao achou contato de usuario pega o primeiro retornado
        if ($objContatoDTO == null) {
          $objContatoDTO = $arrObjContatoDTO[0];
        }
      } else {
        $objContatoDTO->setStrSigla($objParticipanteDTO->getStrSiglaContato());

        if (InfraString::isBolVazia($objParticipanteDTO->getStrNomeContato())) {
          throw new InfraException('Nome do ' . $strTipoParticipante . ' vazio ou nulo.');
        }

        $objContatoDTO->setStrNome($objParticipanteDTO->getStrNomeContato());

        $objContatoDTO = $objContatoRN->cadastrarContextoTemporario($objContatoDTO);
      }

      $objParticipanteDTO->setNumIdContato($objContatoDTO->getNumIdContato());
    }

    return $arrObjParticipanteDTO;
  }

  private function prepararAtributos($arrObjRelProtocoloAtributoDTO, SerieDTO $objSerieDTO) {
    $objAtributoRN = new AtributoRN();

    foreach ($arrObjRelProtocoloAtributoDTO as $objRelProtocoloAtributoDTO) {
      $objAtributoDTO = new AtributoDTO();
      $objAtributoDTO->retNumIdAtributo();
      $objAtributoDTO->setNumIdTipoFormulario($objSerieDTO->getNumIdTipoFormulario());

      if (!InfraString::isBolVazia($objRelProtocoloAtributoDTO->getStrNomeAtributo())) {
        $objAtributoDTO->setStrNome($objRelProtocoloAtributoDTO->getStrNomeAtributo());
      } else {
        throw new InfraException('Nome do campo vazio ou nulo.');
      }

      $objAtributoDTO = $objAtributoRN->consultarRN0115($objAtributoDTO);

      if ($objAtributoDTO == null) {
        throw new InfraException('Campo "' . $objRelProtocoloAtributoDTO->getStrNomeAtributo() . '" não encontrado no formulário.');
      }

      $objRelProtocoloAtributoDTO->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());
    }

    return $arrObjRelProtocoloAtributoDTO;
  }

  private function obterBloco($IdBloco) {
    $objBlocoDTO = new BlocoDTO();
    $objBlocoDTO->retNumIdBloco();
    $objBlocoDTO->retNumIdUnidade();
    $objBlocoDTO->retStrStaTipo();
    $objBlocoDTO->setNumIdBloco($IdBloco);

    $objBlocoRN = new BlocoRN();
    $objBlocoDTO = $objBlocoRN->consultarRN1276($objBlocoDTO);

    if ($objBlocoDTO == null) {
      throw new InfraException('Bloco [' . $IdBloco . '] não encontrado.');
    }
    return $objBlocoDTO;
  }

  private function validarCriteriosUnidade($strStaOperacaoServico) {
    $objServicoDTO = SessaoSEI::getInstance()->getObjServicoDTO();

    if ($objServicoDTO != null) {
      $objOperacaoServicoDTO = new OperacaoServicoDTO();

      $objOperacaoServicoDTO->adicionarCriterio(array('IdUnidade', 'IdUnidade'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), null), InfraDTO::$OPER_LOGICO_OR);

      $objOperacaoServicoDTO->setNumStaOperacaoServico($strStaOperacaoServico);
      $objOperacaoServicoDTO->setNumIdServico($objServicoDTO->getNumIdServico());

      $objOperacaoServicoRN = new OperacaoServicoRN();

      if ($objOperacaoServicoRN->contar($objOperacaoServicoDTO) == 0) {
        $arrObjOperacaoServicoDTO = InfraArray::indexarArrInfraDTO($objOperacaoServicoRN->listarValoresOperacaoServico(), 'StaOperacaoServico');
        $objInfraException = new InfraException();
        $objInfraException->lancarValidacao('Nenhuma operação configurada para [' . $arrObjOperacaoServicoDTO[$strStaOperacaoServico]->getStrOperacao() . '] no serviço [' . $objServicoDTO->getStrIdentificacao() . '] na unidade [' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '].');
      }
    }
  }

  private function validarCriteriosUnidadeProcessoDocumento($strStaOperacaoServico, DocumentoDTO $objDocumentoDTO) {
    $objServicoDTO = SessaoSEI::getInstance()->getObjServicoDTO();

    if ($objServicoDTO != null) {
      $objOperacaoServicoDTO = new OperacaoServicoDTO();

      //qualquer série em qualquer unidade em qualquer tipo de procedimento
      $objOperacaoServicoDTO->adicionarCriterio(array('IdSerie', 'IdUnidade', 'IdTipoProcedimento'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(null, null, null),
        array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND), 'c1');

      //esta série em qualquer unidade em qualquer tipo de procedimento
      $objOperacaoServicoDTO->adicionarCriterio(array('IdSerie', 'IdUnidade', 'IdTipoProcedimento'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array($objDocumentoDTO->getNumIdSerie(), null, null),
        array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND), 'c2');

      //qualquer série nesta unidade em qualquer tipo de procedimento
      $objOperacaoServicoDTO->adicionarCriterio(array('IdSerie', 'IdUnidade', 'IdTipoProcedimento'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
        array(null, SessaoSEI::getInstance()->getNumIdUnidadeAtual(), null), array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND), 'c3');

      //qualquer série em qualquer unidade neste tipo de procedimento
      $objOperacaoServicoDTO->adicionarCriterio(array('IdSerie', 'IdUnidade', 'IdTipoProcedimento'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
        array(null, null, $objDocumentoDTO->getNumIdTipoProcedimentoProcedimento()), array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND), 'c4');

      //esta série nesta unidade em qualquer tipo de procedimento
      $objOperacaoServicoDTO->adicionarCriterio(array('IdSerie', 'IdUnidade', 'IdTipoProcedimento'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
        array($objDocumentoDTO->getNumIdSerie(), SessaoSEI::getInstance()->getNumIdUnidadeAtual(), null), array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND), 'c5');

      //esta série em qualquer unidade neste tipo de procedimento
      $objOperacaoServicoDTO->adicionarCriterio(array('IdSerie', 'IdUnidade', 'IdTipoProcedimento'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
          $objDocumentoDTO->getNumIdSerie(), null, $objDocumentoDTO->getNumIdTipoProcedimentoProcedimento()
        ), array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND), 'c6');

      //qualquer série nesta unidade neste tipo de procedimento
      $objOperacaoServicoDTO->adicionarCriterio(array('IdSerie', 'IdUnidade', 'IdTipoProcedimento'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
          null, SessaoSEI::getInstance()->getNumIdUnidadeAtual(), $objDocumentoDTO->getNumIdTipoProcedimentoProcedimento()
        ), array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND), 'c7');

      //esta série nesta unidade neste tipo de procedimento
      $objOperacaoServicoDTO->adicionarCriterio(array('IdSerie', 'IdUnidade', 'IdTipoProcedimento'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
          $objDocumentoDTO->getNumIdSerie(), SessaoSEI::getInstance()->getNumIdUnidadeAtual(), $objDocumentoDTO->getNumIdTipoProcedimentoProcedimento()
        ), array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND), 'c8');

      $objOperacaoServicoDTO->agruparCriterios(array('c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8'), array(
          InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR
        ));

      $objOperacaoServicoDTO->setNumStaOperacaoServico($strStaOperacaoServico);
      $objOperacaoServicoDTO->setNumIdServico($objServicoDTO->getNumIdServico());

      $objOperacaoServicoRN = new OperacaoServicoRN();

      if ($objOperacaoServicoRN->contar($objOperacaoServicoDTO) == 0) {
        $arrObjOperacaoServicoDTO = InfraArray::indexarArrInfraDTO($objOperacaoServicoRN->listarValoresOperacaoServico(), 'StaOperacaoServico');

        $objInfraException = new InfraException();

        $strMsg = 'Nenhuma operação configurada para [' . $arrObjOperacaoServicoDTO[$strStaOperacaoServico]->getStrOperacao() . '] no serviço [' . $objServicoDTO->getStrIdentificacao() . '] com tipo de processo [' . $objDocumentoDTO->getStrNomeTipoProcedimentoProcedimento() . '] e tipo de documento [' . $objDocumentoDTO->getStrNomeSerie() . '] na unidade ';

        if ($objServicoDTO->getNumIdUnidade() != null) {
          $strMsg .= '[' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '].';
        } else {
          $strMsg .= '[Todas].';
        }

        $objInfraException->lancarValidacao($strMsg);
      }
    }
  }

  private function validarCriteriosUnidadeProcesso($strStaOperacaoServico, ProcedimentoDTO $objProcedimentoDTO) {
    $objServicoDTO = SessaoSEI::getInstance()->getObjServicoDTO();

    if ($objServicoDTO != null) {
      $objOperacaoServicoDTO = new OperacaoServicoDTO();

      $objOperacaoServicoDTO->adicionarCriterio(array('IdTipoProcedimento', 'IdUnidade'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(null, null), InfraDTO::$OPER_LOGICO_AND, 'c1');

      //este tipo em qualquer unidade
      $objOperacaoServicoDTO->adicionarCriterio(array('IdTipoProcedimento', 'IdUnidade'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array($objProcedimentoDTO->getNumIdTipoProcedimento(), null), InfraDTO::$OPER_LOGICO_AND, 'c2');

      //qualquer tipo nesta unidade
      $objOperacaoServicoDTO->adicionarCriterio(array('IdTipoProcedimento', 'IdUnidade'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(null, SessaoSEI::getInstance()->getNumIdUnidadeAtual()), InfraDTO::$OPER_LOGICO_AND, 'c3');

      //este tipo nesta unidade
      $objOperacaoServicoDTO->adicionarCriterio(array('IdTipoProcedimento', 'IdUnidade'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
          $objProcedimentoDTO->getNumIdTipoProcedimento(), SessaoSEI::getInstance()->getNumIdUnidadeAtual()
        ), InfraDTO::$OPER_LOGICO_AND, 'c4');

      $objOperacaoServicoDTO->agruparCriterios(array('c1', 'c2', 'c3', 'c4'), array(InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR));

      $objOperacaoServicoDTO->setNumStaOperacaoServico($strStaOperacaoServico);
      $objOperacaoServicoDTO->setNumIdServico($objServicoDTO->getNumIdServico());

      $objOperacaoServicoRN = new OperacaoServicoRN();

      if ($objOperacaoServicoRN->contar($objOperacaoServicoDTO) == 0) {
        $arrObjOperacaoServicoDTO = InfraArray::indexarArrInfraDTO($objOperacaoServicoRN->listarValoresOperacaoServico(), 'StaOperacaoServico');

        $objInfraException = new InfraException();

        $strMsg = 'Nenhuma operação configurada para [' . $arrObjOperacaoServicoDTO[$strStaOperacaoServico]->getStrOperacao() . '] no serviço [' . $objServicoDTO->getStrIdentificacao() . '] com tipo de processo [' . $objProcedimentoDTO->getStrNomeTipoProcedimento() . '] na unidade ';

        if ($objServicoDTO->getNumIdUnidade() != null) {
          $strMsg .= '[' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '].';
        } else {
          $strMsg .= '[Todas].';
        }

        $objInfraException->lancarValidacao($strMsg);
      }
    }
  }

  private function obterAcessoExternoSistema($dlbIdProcedimento) {
    try {
      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->retNumIdAcessoExterno();
      $objAcessoExternoDTO->setDblIdProtocoloAtividade($dlbIdProcedimento);
      $objAcessoExternoDTO->setNumIdContatoParticipante(SessaoSEI::getInstance()->getObjServicoDTO()->getNumIdContatoUsuario());
      $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_SISTEMA);
      $objAcessoExternoDTO->setNumMaxRegistrosRetorno(1);

      $objAcessoExternoRN = new AcessoExternoRN();
      $objAcessoExternoDTO = $objAcessoExternoRN->consultar($objAcessoExternoDTO);

      if ($objAcessoExternoDTO == null) {
        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retNumIdParticipante();
        $objParticipanteDTO->setNumIdContato(SessaoSEI::getInstance()->getObjServicoDTO()->getNumIdContatoUsuario());
        $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_ACESSO_EXTERNO);
        $objParticipanteDTO->setDblIdProtocolo($dlbIdProcedimento);

        $objParticipanteRN = new ParticipanteRN();
        $objParticipanteDTO = $objParticipanteRN->consultarRN1008($objParticipanteDTO);

        if ($objParticipanteDTO == null) {
          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->setDblIdProtocolo($dlbIdProcedimento);
          $objParticipanteDTO->setNumIdContato(SessaoSEI::getInstance()->getObjServicoDTO()->getNumIdContatoUsuario());
          $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_ACESSO_EXTERNO);
          $objParticipanteDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objParticipanteDTO->setNumSequencia(0);

          $objParticipanteDTO = $objParticipanteRN->cadastrarRN0170($objParticipanteDTO);
        }

        $objAcessoExternoDTO = new AcessoExternoDTO();
        $objAcessoExternoDTO->setNumIdParticipante($objParticipanteDTO->getNumIdParticipante());
        $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_SISTEMA);

        $objAcessoExternoDTO->setStrSinInclusao('N');

        $objAcessoExternoRN = new AcessoExternoRN();
        $objAcessoExternoDTO = $objAcessoExternoRN->cadastrar($objAcessoExternoDTO);
      }

      return $objAcessoExternoDTO;
    } catch (Throwable $e) {
      throw new InfraException('Erro obtendo acesso externo no processo para o sistema.', $e);
    }
  }

  private function obterProcesso($dblIdProcedimento, $strProtoloProcedimento) {
    $objProcedimentoDTO = new ProcedimentoDTO();
    $objProcedimentoDTO->retDblIdProcedimento();
    $objProcedimentoDTO->retNumIdTipoPrioridade();
    $objProcedimentoDTO->retStrNomeTipoPrioridade();
    $objProcedimentoDTO->retNumIdTipoProcedimento();
    $objProcedimentoDTO->retStrNomeTipoProcedimento();
    $objProcedimentoDTO->retStrSinOuvidoriaTipoProcedimento();
    $objProcedimentoDTO->retNumIdUnidadeGeradoraProtocolo();
    $objProcedimentoDTO->retStrStaNivelAcessoLocalProtocolo();
    $objProcedimentoDTO->retStrStaNivelAcessoGlobalProtocolo();
    $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
    $objProcedimentoDTO->retStrDescricaoProtocolo();
    $objProcedimentoDTO->retDtaGeracaoProtocolo();

    if (SessaoSEI::getInstance()->getObjServicoDTO() != null) {
      $objProcedimentoDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO, InfraDTO::$OPER_DIFERENTE);
    }

    if ($dblIdProcedimento != null) {
      $objProcedimentoDTO->setDblIdProcedimento($dblIdProcedimento);
    } else {
      if ($strProtoloProcedimento != null) {
        $objProcedimentoDTO->setStrProtocoloProcedimentoFormatadoPesquisa(InfraUtil::retirarFormatacao($strProtoloProcedimento, false));
      } else {
        throw new InfraException('Processo não informado.', null, null, false);
      }
    }

    $objProcedimentoRN = new ProcedimentoRN();
    $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

    if ($objProcedimentoDTO == null) {
      if ($dblIdProcedimento != null) {
        throw new InfraException('Processo [' . $dblIdProcedimento . '] não encontrado.', null, null, false);
      } else {
        throw new InfraException('Processo [' . $strProtoloProcedimento . '] não encontrado.', null, null, false);
      }
    }
    return $objProcedimentoDTO;
  }

  private function obterDocumento($dblIdDocumento, $strProtocoloDocumento) {
    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoDTO->retDblIdDocumento();
    $objDocumentoDTO->retNumIdSerie();
    $objDocumentoDTO->retStrNomeSerie();
    $objDocumentoDTO->retDblIdProcedimento();
    $objDocumentoDTO->retNumIdTipoProcedimentoProcedimento();
    $objDocumentoDTO->retStrNomeTipoProcedimentoProcedimento();

    if ($dblIdDocumento != null) {
      $objDocumentoDTO->setDblIdDocumento($dblIdDocumento);
    } else {
      if ($strProtocoloDocumento != null) {
        $objDocumentoDTO->setStrProtocoloDocumentoFormatadoPesquisa($strProtocoloDocumento);
      } else {
        throw new InfraException('Documento não informado.', null, null, false);
      }
    }

    if (SessaoSEI::getInstance()->getObjServicoDTO() != null) {
      $objDocumentoDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO, InfraDTO::$OPER_DIFERENTE);
    }

    $objDocumentoRN = new DocumentoRN();
    $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

    if ($objDocumentoDTO == null) {
      if ($dblIdDocumento != null) {
        throw new InfraException('Documento [' . $dblIdDocumento . '] não encontrado.', null, null, false);
      } else {
        throw new InfraException('Documento [' . $strProtocoloDocumento . '] não encontrado.', null, null, false);
      }
    }

    return $objDocumentoDTO;
  }
}

?>