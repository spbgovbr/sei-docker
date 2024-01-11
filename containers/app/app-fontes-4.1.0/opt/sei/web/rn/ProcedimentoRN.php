<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 31/01/2008 - criado por marcio_db
 * 15/06/2018 - cjy - ícone de acompanhamento no controle de processos
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../SEI.php';

class ProcedimentoRN extends InfraRN {

  //TH = Tipo Histórico
  public static $TH_TOTAL = 'T';
  public static $TH_PARCIAL = 'P';
  public static $TH_RESUMIDO = 'R';
  public static $TH_EXTERNO = 'E';
  public static $TH_AUDITORIA = 'A';
  public static $TH_UNIDADE = 'U';
  public static $TH_PERSONALIZADO = 'Z';
  public static $TH_CONSULTA_PROCESSUAL = 'C';

  //TFO = Tipo Finalização Ouvidoria
  public static $TFO_NENHUM = '-';
  public static $TFO_SIM = 'S';
  public static $TFO_NAO = 'N';

  //DGM = Documento Geração Múltipla
  public static $DGM_BLOCO_NOVO = 'G';
  public static $DGM_BLOCO_INFORMADO = 'I';
  public static $DGM_BLOCO_NENHUM = 'N';

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  private function validarStrStaOuvidoria(ProcedimentoDTO $objProcedimentoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProcedimentoDTO->getStrStaOuvidoria())) {
      $objInfraException->adicionarValidacao('Tipo de finalização do processo de ouvidoria não informado.');
    } else {
      if ($objProcedimentoDTO->getStrStaOuvidoria() != ProcedimentoRN::$TFO_NENHUM && $objProcedimentoDTO->getStrStaOuvidoria() != ProcedimentoRN::$TFO_SIM && $objProcedimentoDTO->getStrStaOuvidoria() != ProcedimentoRN::$TFO_NAO) {
        $objInfraException->adicionarValidacao('Tipo de finalização do processo de ouvidoria inválido.');
      }
    }
  }

  public function gerarRN0156(ProcedimentoDTO $objProcedimentoDTO) {
    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $objProcedimentoDTO = $this->gerarRN0156Interno($objProcedimentoDTO);

    $objIndexacaoDTO = new IndexacaoDTO();
    $objIndexacaoDTO->setArrIdProtocolos(array($objProcedimentoDTO->getDblIdProcedimento()));
    $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_METADADOS);

    $objIndexacaoRN = new IndexacaoRN();
    $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);

    if (!$bolAcumulacaoPrevia) {
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }

    return $objProcedimentoDTO;
  }

  protected function gerarRN0156InternoControlado(ProcedimentoDTO $objProcedimentoDTO) {
    try {
      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_gerar', __METHOD__, $objProcedimentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objProtocoloDTO = $objProcedimentoDTO->getObjProtocoloDTO();

      $objTipoProcedimentoDTO = $this->validarNumIdTipoProcedimentoRN0204($objProcedimentoDTO, $objInfraException);
      $this->validarStrSinGerarPendenciaRN0901($objProcedimentoDTO, $objInfraException);
      $this->validarAnexosRN0751($objProcedimentoDTO, $objInfraException);
      $this->validarNivelAcesso($objProcedimentoDTO, $objInfraException);

      if (!$objProcedimentoDTO->isSetNumIdTipoPrioridade()) {
        $objProcedimentoDTO->setNumIdTipoPrioridade(null);
      }


      if ($objProtocoloDTO->isSetArrObjRelProtocoloAtributoDTO() && InfraArray::contar($objProtocoloDTO->getArrObjRelProtocoloAtributoDTO())) {
        throw new InfraException('Processo não pode receber atributos.');
      }

      if ($objProtocoloDTO->isSetArrObjAnexoDTO() && InfraArray::contar($objProtocoloDTO->getArrObjAnexoDTO())) {
        throw new InfraException('Processo não pode receber anexos.');
      }

      $objProtocoloDTO->setArrObjAnexoDTO(array());

      $objInfraException->lancarValidacoes();

      $objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_PROCEDIMENTO);
      $objProtocoloDTO->setNumIdTipoProcedimentoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
      $objProtocoloDTO->setNumIdUnidadeGeradora(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objProtocoloDTO->setNumIdUsuarioGerador(SessaoSEI::getInstance()->getNumIdUsuario());

      $bolProtocoloInformado = false;
      if (!$objProtocoloDTO->isSetStrProtocoloFormatado() || InfraString::isBolVazia($objProtocoloDTO->getStrProtocoloFormatado())) {
        $objProtocoloDTO->setStrProtocoloFormatado(null);
      } else {
        $bolProtocoloInformado = true;
      }

      if (!$objProtocoloDTO->isSetDtaGeracao() || InfraString::isBolVazia($objProtocoloDTO->getDtaGeracao())) {
        $objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());
      }


      $objProcedimentoDTO->setObjProtocoloDTO($objProtocoloDTO);

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTOGerado = $objProtocoloRN->gerarRN0154($objProcedimentoDTO->getObjProtocoloDTO());

      $objProcedimentoDTO->setDblIdProcedimento($objProtocoloDTOGerado->getDblIdProtocolo());

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('NIVEL_ACESSO');
      $objAtributoAndamentoDTO->setStrValor(null);
      $objAtributoAndamentoDTO->setStrIdOrigem($objProcedimentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      if (!InfraString::isBolVazia($objProcedimentoDTO->getObjProtocoloDTO()->getNumIdHipoteseLegal())) {
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('HIPOTESE_LEGAL');
        $objAtributoAndamentoDTO->setStrValor(null);
        $objAtributoAndamentoDTO->setStrIdOrigem($objProcedimentoDTO->getObjProtocoloDTO()->getNumIdHipoteseLegal());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      }

      if (!InfraString::isBolVazia($objProcedimentoDTO->getObjProtocoloDTO()->getStrStaGrauSigilo())) {
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('GRAU_SIGILO');
        $objAtributoAndamentoDTO->setStrValor(null);
        $objAtributoAndamentoDTO->setStrIdOrigem($objProcedimentoDTO->getObjProtocoloDTO()->getStrStaGrauSigilo());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      }

      if ($objProcedimentoDTO->getObjProtocoloDTO()->getDtaGeracao() != InfraData::getStrDataAtual()) {
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DATA_AUTUACAO');
        $objAtributoAndamentoDTO->setStrValor($objProcedimentoDTO->getObjProtocoloDTO()->getDtaGeracao());
        $objAtributoAndamentoDTO->setStrIdOrigem(null);
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      }

      if ($bolProtocoloInformado) {
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('PROTOCOLO_INFORMADO');
        $objAtributoAndamentoDTO->setStrValor($objProcedimentoDTO->getObjProtocoloDTO()->getStrProtocoloFormatado());
        $objAtributoAndamentoDTO->setStrIdOrigem(null);
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      }

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade($objProtocoloDTO->getNumIdUnidadeGeradora());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_GERACAO_PROCEDIMENTO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

      $objAtividadeRN = new AtividadeRN();
      $ret = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      //Associar o processo e seus documentos com esta unidade
      $objAssociarDTO = new AssociarDTO();
      $objAssociarDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objAssociarDTO->setNumIdUnidade($objProtocoloDTO->getNumIdUnidadeGeradora());
      $objAssociarDTO->setNumIdUsuario($objProtocoloDTO->getNumIdUsuarioGerador());
      $objAssociarDTO->setStrStaNivelAcessoGlobal($objProtocoloDTOGerado->getStrStaNivelAcessoGlobal());
      $objProtocoloRN->associarRN0982($objAssociarDTO);

      if ($objProcedimentoDTO->getStrSinGerarPendencia() == 'N') {
        $objAtividadeRN->concluirRN0726(array($ret));
      }


      $objProcedimentoDTO->setStrStaOuvidoria(ProcedimentoRN::$TFO_NENHUM);
      $objProcedimentoDTO->setStrSinCiencia('N');
      $objProcedimentoDTO->setDtaConclusao(null);
      $objProcedimentoDTO->setDtaEliminacao(null);

      if ($objTipoProcedimentoDTO->getStrSinAtivoPlanoTrabalho()=='S') {
        $objProcedimentoDTO->setNumIdPlanoTrabalho($objTipoProcedimentoDTO->getNumIdPlanoTrabalho());
      }else{
        $objProcedimentoDTO->setNumIdPlanoTrabalho(null);
      }

      $objProcedimentoBD = new ProcedimentoBD($this->getObjInfraIBanco());
      $objProcedimentoBD->cadastrar($objProcedimentoDTO);

      $objControleInternoDTO = new ControleInternoDTO();
      $objControleInternoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objControleInternoDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
      $objControleInternoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
      $objControleInternoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objControleInternoDTO->setStrStaNivelAcessoGlobal($objProtocoloDTOGerado->getStrStaNivelAcessoGlobal());
      $objControleInternoDTO->setStrStaOperacao(ControleInternoRN::$TO_GERAR_PROCEDIMENTO);

      $objControleInternoRN = new ControleInternoRN();
      $objControleInternoRN->processar($objControleInternoDTO);


      $objTipoProcedimentoEscolhaDTO = new TipoProcedimentoEscolhaDTO();
      $objTipoProcedimentoEscolhaDTO->retNumIdTipoProcedimento();
      $objTipoProcedimentoEscolhaDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
      $objTipoProcedimentoEscolhaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objTipoProcedimentoEscolhaDTO->setNumMaxRegistrosRetorno(1);

      $objTipoProcedimentoEscolhaRN = new TipoProcedimentoEscolhaRN();
      if ($objTipoProcedimentoEscolhaRN->consultar($objTipoProcedimentoEscolhaDTO) == null) {
        $objTipoProcedimentoEscolhaRN->cadastrar($objTipoProcedimentoEscolhaDTO);
      }

      if ($objProcedimentoDTO->isSetArrObjRelProtocoloProtocoloDTO()) {
        $arrObjProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();

        foreach ($arrObjProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProtocoloDTOGerado->getDblIdProtocolo());
          $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);
          $this->relacionarProcedimentoRN1020($objRelProtocoloProtocoloDTO);
        }
      }

      $objProcedimentoDTORet = new ProcedimentoDTO();
      $objProcedimentoDTORet->setDblIdProcedimento($objProtocoloDTOGerado->getDblIdProtocolo());
      $objProcedimentoDTORet->setStrProtocoloProcedimentoFormatado($objProtocoloDTOGerado->getStrProtocoloFormatado());

      if (count($SEI_MODULOS)) {
        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTORet->getDblIdProcedimento());
        $objProcedimentoAPI->setNumeroProtocolo($objProcedimentoDTORet->getStrProtocoloProcedimentoFormatado());
        $objProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
        $objProcedimentoAPI->setIdTipoPrioridade($objProcedimentoDTO->getNumIdTipoPrioridade());
        $objProcedimentoAPI->setNivelAcesso($objProcedimentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal());

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('gerarProcesso', $objProcedimentoAPI);
        }
      }

      //Auditoria

      return $objProcedimentoDTORet;
    } catch (Exception $e) {
      throw new InfraException('Erro gerando Processo.', $e);
    }
  }

  public function alterarRN0202(ProcedimentoDTO $objProcedimentoDTO) {
    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $ret = $this->alterarRN0202Interno($objProcedimentoDTO);

    $objIndexacaoDTO = new IndexacaoDTO();
    $objIndexacaoDTO->setArrIdProtocolos(array($objProcedimentoDTO->getDblIdProcedimento()));

    if ($ret) {
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROCESSO_COM_DOCUMENTOS_METADADOS);
    } else {
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_METADADOS);
    }

    $objIndexacaoRN = new IndexacaoRN();
    $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);

    if (!$bolAcumulacaoPrevia) {
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }
  }

  protected function alterarRN0202InternoControlado(ProcedimentoDTO $parObjProcedimentoDTO) {
    try {
      global $SEI_MODULOS;

      $bolAlterouTipoProcedimento = false;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_alterar', __METHOD__, $parObjProcedimentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($parObjProcedimentoDTO->isSetStrStaOuvidoria()) {
        $this->validarStrStaOuvidoria($parObjProcedimentoDTO, $objInfraException);
      }

      if ($parObjProcedimentoDTO->isSetStrSinCiencia()) {
        $this->validarStrSinCiencia($parObjProcedimentoDTO, $objInfraException);
      }

      $objProcedimentoDTOBanco = new ProcedimentoDTO();
      $objProcedimentoDTOBanco->retDblIdProcedimento();
      $objProcedimentoDTOBanco->retNumIdTipoPrioridade();
      $objProcedimentoDTOBanco->retStrNomeTipoPrioridade();
      $objProcedimentoDTOBanco->retNumIdTipoProcedimento();
      $objProcedimentoDTOBanco->retStrNomeTipoProcedimento();
      $objProcedimentoDTOBanco->retStrSinIndividualTipoProcedimento();
      $objProcedimentoDTOBanco->retStrStaNivelAcessoLocalProtocolo();
      $objProcedimentoDTOBanco->retStrStaNivelAcessoGlobalProtocolo();
      $objProcedimentoDTOBanco->retStrStaEstadoProtocolo();
      $objProcedimentoDTOBanco->retStrSinEliminadoProtocolo();
      $objProcedimentoDTOBanco->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTOBanco->retNumIdOrgaoUnidadeGeradoraProtocolo();
      $objProcedimentoDTOBanco->retDtaConclusao();
      $objProcedimentoDTOBanco->retDtaEliminacao();
      $objProcedimentoDTOBanco->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
      $objProcedimentoDTOBanco = $this->consultarRN0201($objProcedimentoDTOBanco);

      if ($objProcedimentoDTOBanco == null) {
        throw new InfraException('Processo [' . $parObjProcedimentoDTO->getDblIdProcedimento() . '] não encontrado.');
      }

      if ($objProcedimentoDTOBanco->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
        $this->verificarProcessoAnexadorAberto($objProcedimentoDTOBanco);
      } else {
        $this->verificarEstadoProcedimento($objProcedimentoDTOBanco);
      }

      if ($parObjProcedimentoDTO->isSetNumIdTipoProcedimento()) {
        $bolAlterouTipoProcedimento = ($parObjProcedimentoDTO->getNumIdTipoProcedimento() != $objProcedimentoDTOBanco->getNumIdTipoProcedimento());

        $objTipoProcedimentoDTO = $this->validarNumIdTipoProcedimentoRN0204($parObjProcedimentoDTO, $objInfraException, $bolAlterouTipoProcedimento);

        if ($bolAlterouTipoProcedimento) {
          if ($objTipoProcedimentoDTO->getStrSinIndividual() <> $objProcedimentoDTOBanco->getStrSinIndividualTipoProcedimento()) {
            $objParticipanteDTO = new ParticipanteDTO();
            $objParticipanteDTO->retNumIdParticipante();
            $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
            $objParticipanteDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());

            $objParticipanteRN = new ParticipanteRN();
            $objParticipanteRN->excluirRN0223($objParticipanteRN->listarRN0189($objParticipanteDTO));
          }

          $arrObjAtributoAndamentoDTO = array();

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('TIPO_PROCESSO_ANTERIOR');
          $objAtributoAndamentoDTO->setStrValor($objProcedimentoDTOBanco->getStrNomeTipoProcedimento());
          $objAtributoAndamentoDTO->setStrIdOrigem($objProcedimentoDTOBanco->getNumIdTipoProcedimento());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('TIPO_PROCESSO_ATUAL');
          $objAtributoAndamentoDTO->setStrValor($objTipoProcedimentoDTO->getStrNome());
          $objAtributoAndamentoDTO->setStrIdOrigem($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_TIPO_PROCESSO);

          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

          $parObjProcedimentoDTO->setNumIdPlanoTrabalho($objTipoProcedimentoDTO->getNumIdPlanoTrabalho());

        }
      } else {
        $parObjProcedimentoDTO->setNumIdTipoProcedimento($objProcedimentoDTOBanco->getNumIdTipoProcedimento());
      }

      if ($parObjProcedimentoDTO->isSetNumIdTipoPrioridade()) {
        $bolAlterouTipoPrioridade = ($parObjProcedimentoDTO->getNumIdTipoPrioridade() != $objProcedimentoDTOBanco->getNumIdTipoPrioridade());

        $objTipoPrioridadeDTO = $this->validarNumIdTipoPrioridade($parObjProcedimentoDTO, $objInfraException);

        if ($bolAlterouTipoPrioridade) {
          $arrObjAtributoAndamentoDTO = array();

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('TIPO_PRIORIDADE_ANTERIOR');
          if ($objProcedimentoDTOBanco->getNumIdTipoPrioridade() != null) {
            $objAtributoAndamentoDTO->setStrValor($objProcedimentoDTOBanco->getStrNomeTipoPrioridade());
            $objAtributoAndamentoDTO->setStrIdOrigem($objProcedimentoDTOBanco->getNumIdTipoPrioridade());
          } else {
            $objAtributoAndamentoDTO->setStrValor("Nenhuma");
            $objAtributoAndamentoDTO->setStrIdOrigem(null);
          }
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('TIPO_PRIORIDADE_ATUAL');
          if ($objTipoPrioridadeDTO->getNumIdTipoPrioridade() != null) {
            $objAtributoAndamentoDTO->setStrValor($objTipoPrioridadeDTO->getStrNome());
            $objAtributoAndamentoDTO->setStrIdOrigem($objTipoPrioridadeDTO->getNumIdTipoPrioridade());
          } else {
            $objAtributoAndamentoDTO->setStrValor("Nenhuma");
            $objAtributoAndamentoDTO->setStrIdOrigem(null);
          }
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_PRIORIDADE_PROCESSO);

          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
        }
      } else {
        $parObjProcedimentoDTO->setNumIdTipoPrioridade($objProcedimentoDTOBanco->getNumIdTipoPrioridade());
      }

      if ($parObjProcedimentoDTO->isSetDtaConclusao() && $parObjProcedimentoDTO->getDtaConclusao() != $objProcedimentoDTOBanco->getDtaConclusao()) {
        $objInfraException->adicionarValidacao('Data de conclusão não pode ser alterada.');
      }

      if ($parObjProcedimentoDTO->isSetDtaEliminacao() && $parObjProcedimentoDTO->getDtaEliminacao() != $objProcedimentoDTOBanco->getDtaEliminacao()) {
        $objInfraException->adicionarValidacao('Data de eliminação não pode ser alterada.');
      }

      if ($parObjProcedimentoDTO->isSetObjProtocoloDTO()) {
        $objProtocoloDTO = $parObjProcedimentoDTO->getObjProtocoloDTO();

        if ($objProtocoloDTO->isSetArrObjRelProtocoloAtributoDTO() && InfraArray::contar($objProtocoloDTO->getArrObjRelProtocoloAtributoDTO())) {
          throw new InfraException('Processo não pode receber atributos.');
        }

        if ($objProtocoloDTO->isSetArrObjAnexoDTO() && InfraArray::contar($objProtocoloDTO->getArrObjAnexoDTO())) {
          throw new InfraException('Processo não pode receber anexos.');
        }

        if ($objProtocoloDTO->isSetStrStaNivelAcessoLocal() && $objProtocoloDTO->getStrStaNivelAcessoLocal() != $objProcedimentoDTOBanco->getStrStaNivelAcessoGlobalProtocolo()) {
          $this->validarNivelAcesso($parObjProcedimentoDTO, $objInfraException);
        }
      }

      $objInfraException->lancarValidacoes();

      if ($parObjProcedimentoDTO->isSetObjProtocoloDTO()) {
        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloRN->alterarRN0203($parObjProcedimentoDTO->getObjProtocoloDTO());
      }

      $objProcedimentoBD = new ProcedimentoBD($this->getObjInfraIBanco());
      $objProcedimentoBD->alterar($parObjProcedimentoDTO);

      if ($bolAlterouTipoProcedimento) {
        $objControleInternoDTO = new ControleInternoDTO();
        $objControleInternoDTO->setDblIdProcedimento($objProcedimentoDTOBanco->getDblIdProcedimento());
        $objControleInternoDTO->setNumIdTipoProcedimento($parObjProcedimentoDTO->getNumIdTipoProcedimento());
        $objControleInternoDTO->setNumIdTipoProcedimentoAnterior($objProcedimentoDTOBanco->getNumIdTipoProcedimento());
        $objControleInternoDTO->setNumIdOrgao($objProcedimentoDTOBanco->getNumIdOrgaoUnidadeGeradoraProtocolo());
        $objControleInternoDTO->setStrStaOperacao(ControleInternoRN::$TO_ALTERAR_PROCEDIMENTO);

        $objControleInternoRN = new ControleInternoRN();
        $objControleInternoRN->processar($objControleInternoDTO);
      }

      if (count($SEI_MODULOS)) {
        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTOBanco->getDblIdProcedimento());
        $objProcedimentoAPI->setIdTipoProcedimento($parObjProcedimentoDTO->getNumIdTipoProcedimento());
        $objProcedimentoAPI->setIdTipoPrioridade($parObjProcedimentoDTO->getNumIdTipoPrioridade());

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('alterarProcesso', $objProcedimentoAPI);
        }
      }

      return $bolAlterouTipoProcedimento;
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Processo.', $e);
    }
  }

  public function listarProcessosAnexados(ProcedimentoDTO $objProcedimentoDTO) {
    $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
    $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
    $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo2();
    $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
    $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDTO->getDblIdProcedimento());
    $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
    $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);
    $arrObjProcedimentosAnexadosDTO = array();
    if (InfraArray::contar($arrObjRelProtocoloProtocoloDTO)) {
      foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setDblIdProcedimento($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
        $objProcedimentoDTO->setStrProtocoloProcedimentoFormatado($objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo2());
        $arrObjProcedimentosAnexadosDTO[] = $objProcedimentoDTO;
      }
    }
    return $arrObjProcedimentosAnexadosDTO;
  }

  public function listarRelProtocoloAnexados(array $arrObjProcedimentoDTO) {
    $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
    $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
    $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
    $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
    $objRelProtocoloProtocoloDTO->setDblIdProtocolo1(InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, "IdProcedimento"), InfraDTO::$OPER_IN);
    $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
    $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);
    return $arrObjRelProtocoloProtocoloDTO;
  }

  protected function eliminarInternoControlado(ProcedimentoDTO $objProcedimentoDTO, InfraException $objInfraException) {
    try {
      global $SEI_MODULOS;

      //busca processos anexados do processo para eliminacao
      $arrObjProcedimentoDTOAnexados = $this->listarProcessosAnexados($objProcedimentoDTO);

      //seta o atributo de processos anexados
      $objProcedimentoDTO->setArrObjProcedimentoAnexadoDTO($arrObjProcedimentoDTOAnexados);

      //valida o processo, os processos anexados, os documentos e os documentos dos processos anexados
      $this->validarEliminacao($objProcedimentoDTO);

      $arrIdProcedimentos = InfraArray::converterArrInfraDTO($arrObjProcedimentoDTOAnexados, "IdProcedimento");

      //adiciona o id do processo principal no inicio do array
      array_unshift($arrIdProcedimentos, $objProcedimentoDTO->getDblIdProcedimento());

      //lista documentos do processo e anexados
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrSinEliminadoProtocolo();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->setDblIdProcedimento($arrIdProcedimentos, InfraDTO::$OPER_IN);

      //permite rodar a eliminação novamente sobre documentos já eliminados
      //$objDocumentoDTO->setStrSinEliminadoProtocolo('N');

      $objArquivamentoRN = new ArquivamentoRN();
      $objPublicacaoRN = new PublicacaoRN();
      $objAtividadeRN = new AtividadeRN();
      $objDocumentoRN = new DocumentoRN();
      $objIndexacaoRN = new IndexacaoRN();
      $objProcedimentoBD = new ProcedimentoBD($this->getObjInfraIBanco());

      $arrObjDocumentoDTOPorProcesso = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO), 'IdProcedimento', true);

      $arrIdPublicados = array();

      foreach ($arrIdProcedimentos as $dblIdProcedimento) {
        if (!isset($arrObjDocumentoDTOPorProcesso[$dblIdProcedimento])) {
          $arrObjDocumentoDTO = array();
        } else {
          $arrObjDocumentoDTO = $arrObjDocumentoDTOPorProcesso[$dblIdProcedimento];
        }

        $objProcedimentoDTOEliminacao = new ProcedimentoDTO();
        $objProcedimentoDTOEliminacao->retDblIdProcedimento();
        $objProcedimentoDTOEliminacao->retStrStaEstadoProtocolo();
        $objProcedimentoDTOEliminacao->retStrSinEliminadoProtocolo();
        $objProcedimentoDTOEliminacao->retStrProtocoloProcedimentoFormatado();
        $objProcedimentoDTOEliminacao->setDblIdProcedimento($dblIdProcedimento);
        $objProcedimentoDTOEliminacao = $this->consultarRN0201($objProcedimentoDTOEliminacao);

        //se o processo tem documentos
        if (count($arrObjDocumentoDTO)) {
          //converte para ter os ids dos documentos do processo e dos processos anexados
          $arrIdDocumentos = InfraArray::converterArrInfraDTO($arrObjDocumentoDTO, "IdDocumento");
          //indexa pelos ids dos documentos
          $arrObjDocumentoDTO_Indexado = InfraArray::indexarArrInfraDTO($arrObjDocumentoDTO, "IdDocumento");
          //array com os documentos que serão eliminados
          $arrObjDocumentoDTO_Eliminar = array();

          //Busca documentos que estão publicados
          $objPublicacaoDTO = new PublicacaoDTO();
          $objPublicacaoDTO->retDblIdDocumento();
          $objPublicacaoDTO->retStrStaEstado();
          $objPublicacaoDTO->setDblIdDocumento($arrIdDocumentos, InfraDTO::$OPER_IN);
          $arrObjPublicacaoDTO = $objPublicacaoRN->listarRN1045($objPublicacaoDTO);

          $arrIdPublicadosProcesso = array();
          foreach ($arrObjPublicacaoDTO as $objPublicacaoDTO) {
            if ($objPublicacaoDTO->getStrStaEstado() == PublicacaoRN::$TE_PUBLICADO) {
              $arrIdPublicadosProcesso[] = $objPublicacaoDTO->getDblIdDocumento();
            }
          }
          $arrIdPublicados = array_merge($arrIdPublicados, $arrIdPublicadosProcesso);

          //dto que lista os arquivamentos de todos os documentos listados
          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->retDblIdProtocolo();
          $objArquivamentoDTO->retStrStaArquivamento();
          $objArquivamentoDTO->retStrStaEliminacao();
          $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
          $objArquivamentoDTO->retDblIdProtocoloDocumento();
          $objArquivamentoDTO->retStrSinEliminadoProtocolo();
          //filtra pelos id dos documentos do processo e dos processos anexados
          $objArquivamentoDTO->setDblIdProtocoloDocumento($arrIdDocumentos, InfraDTO::$OPER_IN);
          $objArquivamentoDTO->setStrStaArquivamento(array(
            ArquivamentoRN::$TA_RECEBIDO, ArquivamentoRN::$TA_ARQUIVADO, ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO
          ), InfraDTO::$OPER_IN);
          //permite rodar o processo novamente sobre documentos arquivados
          //$objArquivamentoDTO->setStrSinEliminadoProtocolo('N');

          //lista os arquivamentos
          $arrObjArquivamentoDTO = $objArquivamentoRN->listar($objArquivamentoDTO);

          $arrIdDocumentosArquivo = array();
          foreach ($arrObjArquivamentoDTO as $objArquivamentoDTO) {
            //se ainda não foi processado para eliminacao serao sinalizados para eliminacao (listados na tela Documentos para Eliminacao) e adicionados na lista de excecao
            if ($objArquivamentoDTO->getStrStaEliminacao() == ArquivamentoRN::$TE_NAO_ELIMINADO) {
              $objArquivamentoRN->sinalizarParaEliminacao($objArquivamentoDTO);

              $arrIdDocumentosArquivo[] = $objArquivamentoDTO->getDblIdProtocolo();
              //senao se já foi sinalizado para eliminacao adiciona na lista de excecao
            } else {
              if ($objArquivamentoDTO->getStrStaEliminacao() == ArquivamentoRN::$TE_PARA_ELIMINACAO) {
                $arrIdDocumentosArquivo[] = $objArquivamentoDTO->getDblIdProtocolo();
                //senao se já foram eliminados do arquivo são adicionados para eliminação
              } else {
                if ($objArquivamentoDTO->getStrStaEliminacao() == ArquivamentoRN::$TE_ELIMINADO) {
                  $objDocumentoDTO_Arquivo = new DocumentoDTO();
                  $objDocumentoDTO_Arquivo->setDblIdDocumento($objArquivamentoDTO->getDblIdProtocoloDocumento());
                  $objDocumentoDTO_Arquivo->setStrProtocoloDocumentoFormatado($objArquivamentoDTO->getStrProtocoloFormatadoDocumento());
                  $objDocumentoDTO_Arquivo->setStrSinEliminadoProtocolo($objArquivamentoDTO->getStrSinEliminadoProtocolo());

                  $arrObjDocumentoDTO_Eliminar[] = $objDocumentoDTO_Arquivo;
                }
              }
            }
          }

          //em principio vai eliminar tudo
          $arrIdDocumentoParaEliminar = $arrIdDocumentos;

          //retira da lista de documentos os que estao no arquivo
          $arrIdDocumentoParaEliminar = array_diff($arrIdDocumentoParaEliminar, $arrIdDocumentosArquivo);

          //retira da lista de documentos do processo aqueles que possuem publicacao
          $arrIdDocumentoParaEliminar = array_diff($arrIdDocumentoParaEliminar, $arrIdPublicadosProcesso);

          //testa se tem nao arquivados, que serao eliminados
          if (InfraArray::contar($arrIdDocumentoParaEliminar)) {
            foreach ($arrIdDocumentoParaEliminar as $dblIdDocumento) {
              //adiciona no array que serão eliminados
              $arrObjDocumentoDTO_Eliminar[] = $arrObjDocumentoDTO_Indexado[$dblIdDocumento];
            }
          }

          //elimina os documentos
          if (InfraArray::contar($arrObjDocumentoDTO_Eliminar)) {
            //elimina os documentos
            $objDocumentoRN->eliminar($arrObjDocumentoDTO_Eliminar);

            $arrIdDocumentosAndamento = array();
            foreach ($arrObjDocumentoDTO_Eliminar as $objDocumentoDTO) {
              if ($objDocumentoDTO->getStrSinEliminadoProtocolo() == 'N') {
                $arrIdDocumentosAndamento[] = $objDocumentoDTO->getDblIdDocumento();
              }
            }

            if (count($arrIdDocumentosAndamento)) {
              $arrAndamentosPartes = array_chunk($arrIdDocumentosAndamento, 100);

              foreach ($arrAndamentosPartes as $arrParte) {
                //cadastra atividade/andamento da eliminacao dos documentos do processo
                $arrObjAtributoAndamentoDTO = array();
                //atributo referente aos documentos
                $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                $objAtributoAndamentoDTO->setStrNome('DOCUMENTOS');
                $objAtributoAndamentoDTO->setStrValor(null);
                $objAtributoAndamentoDTO->setStrIdOrigem(null);
                $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
                //itera pelos documentos
                foreach ($arrParte as $dblIdDocumento) {
                  //atributo referente ao documento
                  $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                  $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
                  $objAtributoAndamentoDTO->setStrValor($arrObjDocumentoDTO_Indexado[$dblIdDocumento]->getStrProtocoloDocumentoFormatado());
                  $objAtributoAndamentoDTO->setStrIdOrigem($dblIdDocumento);
                  $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
                }
                //atividade referente a eliminacao dos documentos
                $objAtividadeDTO = new AtividadeDTO();
                $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTOEliminacao->getDblIdProcedimento());
                $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ELIMINACAO_ELETRONICO);
                $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
                $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
              }
            }

            //altera a indexacao para remocao
            $objIndexacaoDTO = new IndexacaoDTO();
            $objIndexacaoDTO->setArrIdProtocolos(InfraArray::converterArrInfraDTO($arrObjDocumentoDTO_Eliminar, 'IdDocumento'));
            $objIndexacaoRN->prepararRemocaoProtocolo($objIndexacaoDTO);
          }
        }

        //permite rodar a eliminação novamente sobre processos já eliminados
        //if ($objProcedimentoDTOEliminacao->getStrSinEliminadoProtocolo()!='S') {

        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setNumeroProtocolo($objProcedimentoDTOEliminacao->getStrProtocoloProcedimentoFormatado());
        $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTOEliminacao->getDblIdProcedimento());
        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('eliminarProcesso', $objProcedimentoAPI);
        }

        //eliminacao do processo em si
        $objProcedimentoBD->eliminar($objProcedimentoDTOEliminacao);

        //altera a indexacao para remocao
        $objIndexacaoDTO = new IndexacaoDTO();
        $objIndexacaoDTO->setArrIdProtocolos(array($dblIdProcedimento));
        $objIndexacaoRN->prepararRemocaoProtocolo($objIndexacaoDTO);
        //}
      }

      //dto da avaliacao documental do processo, que será atualizada após a eliminacao desse processo (pode ser o ultimo processo que faltava ser eliminado desse processo, ou o primeiro)
      //se eliminou todos os documentos, será eliminado; senao será eliminado parcialmente
      $objAvaliacaoDocumentalDTO = new AvaliacaoDocumentalDTO();
      $objAvaliacaoDocumentalDTO->retNumIdAvaliacaoDocumental();
      //filtra pelo id do processo
      $objAvaliacaoDocumentalDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();
      //consulta a avaliacao documental
      $objAvaliacaoDocumentalDTO = $objAvaliacaoDocumentalRN->consultar($objAvaliacaoDocumentalDTO);

      //lista todos os documentos que ainda estão associados com os processos
      $objDocumentoDTO_Restantes = new DocumentoDTO();
      $objDocumentoDTO_Restantes->retDblIdDocumento();
      $objDocumentoDTO_Restantes->setStrSinEliminadoProtocolo('N');
      $objDocumentoDTO_Restantes->setDblIdProcedimento($arrIdProcedimentos, InfraDTO::$OPER_IN);
      $arrIdDocumentosRestantes = InfraArray::converterArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO_Restantes), 'IdDocumento');

      //se tem pelo menos um
      if (count($arrIdDocumentosRestantes)) {
        //retira os documentos publicados da lista de documentos dos processos
        $arrIdDocumentosRestantes = array_diff($arrIdDocumentosRestantes, $arrIdPublicados);
      }

      //conta se tem documentos restantes
      if (count($arrIdDocumentosRestantes)) {
        //se tem, a eliminacao foi parcial
        $objAvaliacaoDocumentalDTO->setStrStaAvaliacao(AvaliacaoDocumentalRN::$TA_ELIMINACAO_PARCIAL);
      } else {
        //senao, é eliminado
        $objAvaliacaoDocumentalDTO->setStrStaAvaliacao(AvaliacaoDocumentalRN::$TA_ELIMINADO);
      }
      $objAvaliacaoDocumentalRN->alterar($objAvaliacaoDocumentalDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro eliminando Processo.', $e);
    }
  }
  //o metodo recebe uma InfraException como parametro e adiciona como validacao possiveis exceptions e validacoes recebidas, tratadas em um catch no metodo
  //assim a barra de progresso e a eliminacao de processos prossegue, no caso da eliminacao de um edital com varios processos (e nao na eliminacao de um unico processo)
  public function eliminar(ProcedimentoDTO $parObjProcedimentoDTO, InfraException $objInfraException) {
    try {
      //flag para acumulacao de feeds, necessario para nao dar look no solr
      $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

      $objEditalEliminacaoErroDTO = new EditalEliminacaoErroDTO();
      $objEditalEliminacaoErroDTO->retNumIdEditalEliminacaoErro();
      $objEditalEliminacaoErroDTO->setNumIdEditalEliminacaoConteudo($parObjProcedimentoDTO->getNumIdEditalEliminacaoConteudo());

      $objEditalEliminacaoErroRN = new EditalEliminacaoErroRN();
      $objEditalEliminacaoErroRN->excluir($objEditalEliminacaoErroRN->listar($objEditalEliminacaoErroDTO));

      //eliminar controlado, assim ocorre o rollback em caso de erro
      $this->eliminarInterno($parObjProcedimentoDTO, $objInfraException);

      //controle de feeds
      if (!$bolAcumulacaoPrevia) {
        FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
        FeedSEIProtocolos::getInstance()->indexarFeeds();
      }
    } catch (Exception $e) {
      $strErro = '';
      if ($e instanceof InfraException && $e->contemValidacoes()) {
        $strErro = $e->__toString();
      } else {
        $strErro = InfraException::inspecionar($e);
      }

      $objEditalEliminacaoErroDTO = new EditalEliminacaoErroDTO();
      $objEditalEliminacaoErroDTO->setNumIdEditalEliminacaoErro(null);
      $objEditalEliminacaoErroDTO->setNumIdEditalEliminacaoConteudo($parObjProcedimentoDTO->getNumIdEditalEliminacaoConteudo());
      $objEditalEliminacaoErroDTO->setDthErro(InfraData::getStrDataHoraAtual());
      $objEditalEliminacaoErroDTO->setStrTextoErro(substr($strErro, 0, 4000));
      $objEditalEliminacaoErroRN->cadastrar($objEditalEliminacaoErroDTO);

      $objInfraException->adicionarValidacao($strErro);
    }
  }

  public function excluirRN0280(ProcedimentoDTO $parObjProcedimentoDTO) {
    $objIndexacaoDTO = new IndexacaoDTO();
    $objIndexacaoDTO->setArrIdProtocolos(array($parObjProcedimentoDTO->getDblIdProcedimento()));

    $objIndexacaoRN = new IndexacaoRN();
    $objIndexacaoRN->prepararRemocaoProtocolo($objIndexacaoDTO);

    $this->excluirRN0280Interno($parObjProcedimentoDTO);

    FeedSEIProtocolos::getInstance()->indexarFeeds();
  }

  protected function excluirRN0280InternoControlado(ProcedimentoDTO $parObjProcedimentoDTO) {
    try {
      global $SEI_MODULOS;

      //Valida Permissao, deixar para auditar no final da execução
      SessaoSEI::getInstance()->validarPermissao('procedimento_excluir');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objProcedimentoDTO->retNumIdTipoProcedimento();
      $objProcedimentoDTO->retStrStaEstadoProtocolo();
      $objProcedimentoDTO->retStrSinEliminadoProtocolo();
      $objProcedimentoDTO->retStrIdProtocoloFederacaoProtocolo();
      $objProcedimentoDTO->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
      $objProcedimentoDTO = $this->consultarRN0201($objProcedimentoDTO);

      if ($objProcedimentoDTO == null) {
        $objInfraException->lancarValidacao('Processo não encontrado.');
      }

      if ($objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
        $objInfraException->lancarValidacao('Processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' somente pode ser excluído pela unidade geradora.');
      }

      $this->verificarEstadoProcedimento($objProcedimentoDTO);

      $objAtividadeRN = new AtividadeRN();

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retNumIdAtividade();
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setDthConclusao(null);
      $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

      if ($objAtividadeRN->consultarRN0033($objAtividadeDTO) == null) {
        $objInfraException->lancarValidacao('Unidade não possui andamento aberto no processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . '.');
      }

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retNumIdAtividade();
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), InfraDTO::$OPER_DIFERENTE);
      $objAtividadeDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

      if ($objAtividadeRN->consultarRN0033($objAtividadeDTO) != null) {
        $objInfraException->lancarValidacao('Processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' já tramitou em outra(s) unidade(s).');
      }

      //verifica documentos do procedimento
      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjProcedimentoDTO->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
      $objRelProtocoloProtocoloDTO->setNumMaxRegistrosRetorno(1);

      if ($objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO) != null) {
        $objInfraException->lancarValidacao('Processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' possui documentos.');
      }

      //verifica documentos do procedimento
      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjProcedimentoDTO->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO);
      $objRelProtocoloProtocoloDTO->setNumMaxRegistrosRetorno(1);

      if ($objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO) != null) {
        $objInfraException->lancarValidacao('Processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' possui documentos que foram movidos.');
      }

      //verifica se processo tem processos anexados
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjProcedimentoDTO->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
      $objRelProtocoloProtocoloDTO->setNumMaxRegistrosRetorno(1);

      if ($objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO) != null) {
        $objInfraException->lancarValidacao('Processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' possui processos anexados.');
      }

      //busca anexacao deste processo em outro procedimento
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo1();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjProcedimentoDTO->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);

      $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

      if ($objRelProtocoloProtocoloDTO != null) {
        $objInfraException->lancarValidacao('Processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' está anexado no processo ' . $objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo1() . '.');
      }

      if ($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo() != null) {
        $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
        $objAcessoFederacaoDTO->retStrIdAcessoFederacao();
        $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo());
        $objAcessoFederacaoDTO->setNumMaxRegistrosRetorno(1);

        $objAcessoFederacaoRN = new AcessoFederacaoRN();
        if ($objAcessoFederacaoRN->consultar($objAcessoFederacaoDTO) != null) {
          $objInfraException->lancarValidacao('Processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' possui tramitação no SEI Federação.');
        }
      }

      //busca dados para auditoria
      $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
      $objProcedimentoHistoricoDTO->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
      $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('N');
      $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_AUDITORIA);

      $objProcedimentoDTORet = $this->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
      $arrObjAtividadeDTO = $objProcedimentoDTORet->getArrObjAtividadeDTO();

      $strConteudoAuditoria = $objProcedimentoDTO->__toString();
      foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
        $strConteudoAuditoria .= "\n\n";
        $strConteudoAuditoria .= $objAtividadeDTO->__toString();
      }

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTO->adicionarCriterio(array('IdProtocolo1', 'IdProtocolo2'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
        array($parObjProcedimentoDTO->getDblIdProcedimento(), $parObjProcedimentoDTO->getDblIdProcedimento()), array(InfraDTO::$OPER_LOGICO_OR));
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);
      $objRelProtocoloProtocoloRN->excluirRN0842($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO));

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjProcedimentoDTO->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_DESANEXADO);
      $objRelProtocoloProtocoloRN->excluirRN0842($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO));

      $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();
      $objAndamentoSituacaoDTO->retNumIdAndamentoSituacao();
      $objAndamentoSituacaoDTO->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
      $objAndamentoSituacaoRN = new AndamentoSituacaoRN();
      $objAndamentoSituacaoRN->excluir($objAndamentoSituacaoRN->listar($objAndamentoSituacaoDTO));

      $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
      $objAndamentoMarcadorDTO->setBolExclusaoLogica(false);
      $objAndamentoMarcadorDTO->retNumIdAndamentoMarcador();
      $objAndamentoMarcadorDTO->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
      $objAndamentoMarcadorRN = new AndamentoMarcadorRN();
      $objAndamentoMarcadorRN->excluir($objAndamentoMarcadorRN->listar($objAndamentoMarcadorDTO));

      $objComentarioDTO = new ComentarioDTO();
      $objComentarioDTO->retNumIdComentario();
      $objComentarioDTO->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
      $objComentarioRN = new ComentarioRN();
      $objComentarioRN->excluir($objComentarioRN->listar($objComentarioDTO));

      $objReaberturaProgramadaDTO = new ReaberturaProgramadaDTO();
      $objReaberturaProgramadaDTO->retNumIdReaberturaProgramada();
      $objReaberturaProgramadaDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());
      $objReaberturaProgramadaRN = new ReaberturaProgramadaRN();
      $objReaberturaProgramadaRN->excluir($objReaberturaProgramadaRN->listar($objReaberturaProgramadaDTO));

      $objAndamentoPlanoTrabalhoDTO = new AndamentoPlanoTrabalhoDTO();
      $objAndamentoPlanoTrabalhoDTO->retNumIdAndamentoPlanoTrabalho();
      $objAndamentoPlanoTrabalhoDTO->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());

      $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
      $objAndamentoPlanoTrabalhoRN->excluir($objAndamentoPlanoTrabalhoRN->listar($objAndamentoPlanoTrabalhoDTO));

      $objEditalEliminacaoRN = new EditalEliminacaoRN();
      $objEditalEliminacaoRN->removerProcedimento($parObjProcedimentoDTO);

      $objProcedimentoAPI = new ProcedimentoAPI();
      $objProcedimentoAPI->setIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('excluirProcesso', $objProcedimentoAPI);
      }

      $this->excluirAvaliacaoDocumental($parObjProcedimentoDTO);

      $objProcedimentoBD = new ProcedimentoBD($this->getObjInfraIBanco());
      $objProcedimentoBD->excluir($parObjProcedimentoDTO);

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());
      $objProtocoloRN->excluirRN0748($objProtocoloDTO);

      $objProcedimentoDTOEscolha = new ProcedimentoDTO();
      $objProcedimentoDTOEscolha->retDblIdProcedimento();
      $objProcedimentoDTOEscolha->setNumIdUnidadeGeradoraProtocolo(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objProcedimentoDTOEscolha->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
      $objProcedimentoDTOEscolha->setNumMaxRegistrosRetorno(1);

      if ($this->consultarRN0201($objProcedimentoDTOEscolha) == null) {
        $objTipoProcedimentoEscolhaDTO = new TipoProcedimentoEscolhaDTO();
        $objTipoProcedimentoEscolhaDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
        $objTipoProcedimentoEscolhaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objTipoProcedimentoEscolhaRN = new TipoProcedimentoEscolhaRN();
        if ($objTipoProcedimentoEscolhaRN->contar($objTipoProcedimentoEscolhaDTO) == 1) {
          $objTipoProcedimentoEscolhaRN->excluir(array($objTipoProcedimentoEscolhaDTO));
        }
      }

      AuditoriaSEI::getInstance()->auditar('procedimento_excluir', __METHOD__, $strConteudoAuditoria);
      //$objInfraException->lancarValidacoes();

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Processo.', $e);
    }
  }

  protected function consultarRN0201Conectado(ProcedimentoDTO $objProcedimentoDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_consultar', __METHOD__, $objProcedimentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProcedimentoBD = new ProcedimentoBD($this->getObjInfraIBanco());
      $ret = $objProcedimentoBD->consultar($objProcedimentoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Processo.', $e);
    }
  }

  protected function listarRN0278Conectado(ProcedimentoDTO $objProcedimentoDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_listar', __METHOD__, $objProcedimentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      if ($objProcedimentoDTO->isRetStrSinAberto()) {
        $objProcedimentoDTO->retDblIdProcedimento();
      }

      $objProcedimentoBD = new ProcedimentoBD($this->getObjInfraIBanco());
      $arrObjProcedimentoDTO = $objProcedimentoBD->listar($objProcedimentoDTO);

      if ($objProcedimentoDTO->isRetStrSinAberto()) {
        $this->verificarProcessosControlados($arrObjProcedimentoDTO);
      }

      //Auditoria

      return $arrObjProcedimentoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Processos.', $e);
    }
  }

  protected function contarRN0279Conectado(ProcedimentoDTO $objProcedimentoDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_listar', __METHOD__, $objProcedimentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProcedimentoBD = new ProcedimentoBD($this->getObjInfraIBanco());
      $ret = $objProcedimentoBD->contar($objProcedimentoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Processos.', $e);
    }
  }

  private function validarNumIdTipoProcedimentoRN0204(ProcedimentoDTO $parObjProcedimentoDTO, InfraException $objInfraException, $bolValidarRestricao = true) {
    if (InfraString::isBolVazia($parObjProcedimentoDTO->getNumIdTipoProcedimento())) {
      $objInfraException->adicionarValidacao('Tipo do Processo não informado.');
    }

    $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
    $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
    $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
    $objTipoProcedimentoDTO->retNumIdPlanoTrabalho();
    $objTipoProcedimentoDTO->retStrSinAtivoPlanoTrabalho();
    $objTipoProcedimentoDTO->retStrNome();
    $objTipoProcedimentoDTO->retStrSinIndividual();
    $objTipoProcedimentoDTO->setNumIdTipoProcedimento($parObjProcedimentoDTO->getNumIdTipoProcedimento());

    $objTipoProcedimentoRN = new TipoProcedimentoRN();
    $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);

    if ($objTipoProcedimentoDTO == null) {
      throw new InfraException('Tipo de processo [' . $parObjProcedimentoDTO->getNumIdTipoProcedimento() . '] não encontrado.');
    }

    if ($bolValidarRestricao) {
      $strCache = 'SEI_TPR_' . $parObjProcedimentoDTO->getNumIdTipoProcedimento();
      $arrCache = CacheSEI::getInstance()->getAtributo($strCache);
      if ($arrCache == null) {
        $objTipoProcedRestricaoDTO = new TipoProcedRestricaoDTO();
        $objTipoProcedRestricaoDTO->retNumIdOrgao();
        $objTipoProcedRestricaoDTO->retNumIdUnidade();
        $objTipoProcedRestricaoDTO->setNumIdTipoProcedimento($parObjProcedimentoDTO->getNumIdTipoProcedimento());

        $objTipoProcedRestricaoRN = new TipoProcedRestricaoRN();
        $arrObjTipoProcedRestricaoDTO = $objTipoProcedRestricaoRN->listar($objTipoProcedRestricaoDTO);

        $arrCache = array();
        foreach ($arrObjTipoProcedRestricaoDTO as $objTipoProcedRestricaoDTO) {
          $arrCache[$objTipoProcedRestricaoDTO->getNumIdOrgao()][($objTipoProcedRestricaoDTO->getNumIdUnidade() == null ? '*' : $objTipoProcedRestricaoDTO->getNumIdUnidade())] = 0;
        }
        CacheSEI::getInstance()->setAtributo($strCache, $arrCache, CacheSEI::getInstance()->getNumTempo());
      }

      if (InfraArray::contar($arrCache) && !isset($arrCache[SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()]['*']) && !isset($arrCache[SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()][SessaoSEI::getInstance()->getNumIdUnidadeAtual()])) {
        $objInfraException->lancarValidacao('Tipo de processo "' . $objTipoProcedimentoDTO->getStrNome() . '" não está liberado para a unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '/' . SessaoSEI::getInstance()->getStrSiglaOrgaoUnidadeAtual() . '.');
      }
    }

    if ($objTipoProcedimentoDTO->getStrSinIndividual() == 'S') {
      if ($parObjProcedimentoDTO->isSetObjProtocoloDTO() && $parObjProcedimentoDTO->getObjProtocoloDTO()->isSetArrObjParticipanteDTO()) {
        $arrObjParticipanteDTO = $parObjProcedimentoDTO->getObjProtocoloDTO()->getArrObjParticipanteDTO();
      } else {
        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retNumIdContato();
        $objParticipanteDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());
        $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);

        $objParticipanteRN = new ParticipanteRN();
        $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);
      }

      $numInteressadosUsuario = 0;
      $objParticipanteDTOUsuario = null;
      foreach ($arrObjParticipanteDTO as $objParticipanteDTO) {
        if ($objParticipanteDTO->getStrStaParticipacao() == ParticipanteRN::$TP_INTERESSADO) {
          $objParticipanteDTOUsuario = $objParticipanteDTO;
          $numInteressadosUsuario++;
        }
      }

      if ($numInteressadosUsuario == 0) {
        $objInfraException->adicionarValidacao('Interessado não informado.');
      } else {
        if ($numInteressadosUsuario > 1) {
          $objInfraException->adicionarValidacao('Mais de um Interessado informado.');
        } else {
          //pode haver compartilhamento de contato
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->setBolExclusaoLogica(false);
          $objUsuarioDTO->setDistinct(true);
          $objUsuarioDTO->retStrIdOrigem();
          $objUsuarioDTO->retNumIdContato();
          $objUsuarioDTO->retStrSigla();
          $objUsuarioDTO->retStrNome();
          $objUsuarioDTO->setNumIdContato($objParticipanteDTOUsuario->getNumIdContato());
          $objUsuarioDTO->setOrdNumIdContato(InfraDTO::$TIPO_ORDENACAO_DESC);

          $objUsuarioRN = new UsuarioRN();
          $arrObjUsuarioDTO = $objUsuarioRN->listarRN0490($objUsuarioDTO);

          if (count($arrObjUsuarioDTO) == 0) {
            $objInfraException->lancarValidacao('Interessado não é um usuário.');
          }

          $arrIdOrigemUsuario = array();
          foreach ($arrObjUsuarioDTO as $objUsuarioDTOContato) {
            if ($objUsuarioDTOContato->getStrIdOrigem() != null && !in_array($objUsuarioDTOContato->getStrIdOrigem(), $arrIdOrigemUsuario)) {
              $arrIdOrigemUsuario[] = $objUsuarioDTOContato->getStrIdOrigem();
            }
          }

          if (count($arrIdOrigemUsuario) > 1) {
            throw new InfraException('Usuário ' . $arrObjUsuarioDTO[0]->getStrNome() . ' não contém identificador de origem único.');
            //Um ou mais ID PESSOA RH nulo
          } else {
            if (count($arrIdOrigemUsuario) == 0) {
              //pega primeiro
              $arrIdContatos = array($arrObjUsuarioDTO[0]->getNumIdContato());
              //ID PESSOA RH não nulo
            } else {
              //busca todos os contatos com o mesmo IdOrigem
              $objUsuarioDTOContatos = new UsuarioDTO();
              $objUsuarioDTOContatos->setBolExclusaoLogica(false);
              $objUsuarioDTOContatos->retNumIdContato();
              $objUsuarioDTOContatos->setStrIdOrigem($arrIdOrigemUsuario[0]);
              $arrIdContatos = InfraArray::converterArrInfraDTO($objUsuarioRN->listarRN0490($objUsuarioDTOContatos), 'IdContato');
            }
          }

          $objUsuarioDTO = $arrObjUsuarioDTO[0];

          if ($parObjProcedimentoDTO->getDblIdProcedimento() != null) {
            $objProtocoloDTO = new ProtocoloDTO();
            $objProtocoloDTO->retNumIdOrgaoUnidadeGeradora();
            $objProtocoloDTO->retStrSiglaOrgaoUnidadeGeradora();
            $objProtocoloDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());

            $objProtocoloRN = new ProtocoloRN();
            $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

            $numIdOrgaoProtocolo = $objProtocoloDTO->getNumIdOrgaoUnidadeGeradora();
            $strSiglaOrgaoProtocolo = $objProtocoloDTO->getStrSiglaOrgaoUnidadeGeradora();
          } else {
            $numIdOrgaoProtocolo = SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual();
            $strSiglaOrgaoProtocolo = SessaoSEI::getInstance()->getStrSiglaOrgaoUnidadeAtual();
          }

          $objProtocoloDTO = new ProtocoloDTO();
          $objProtocoloDTO->retDblIdProtocolo();
          $objProtocoloDTO->retStrProtocoloFormatado();
          $objProtocoloDTO->setNumTipoFkProcedimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
          $objProtocoloDTO->setNumIdContatoParticipante($arrIdContatos, InfraDTO::$OPER_IN);
          $objProtocoloDTO->setStrStaParticipacaoParticipante(ParticipanteRN::$TP_INTERESSADO);
          $objProtocoloDTO->setNumIdTipoProcedimentoProcedimento($parObjProcedimentoDTO->getNumIdTipoProcedimento());
          $objProtocoloDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento(), InfraDTO::$OPER_DIFERENTE);
          $objProtocoloDTO->setNumIdOrgaoUnidadeGeradora($numIdOrgaoProtocolo);
          $objProtocoloDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_DESC);

          $objProtocoloRN = new ProtocoloRN();
          $arrObjProtocoloDTOTemp = $objProtocoloRN->listarRN0668($objProtocoloDTO);

          $arrObjProtocoloDTO = array();
          $objParticipanteRN = new ParticipanteRN();
          foreach ($arrObjProtocoloDTOTemp as $objProtocoloDTO) {
            $objParticipanteDTO = new ParticipanteDTO();
            $objParticipanteDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
            $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);

            if ($objParticipanteRN->contarRN0461($objParticipanteDTO) == 1) {
              $arrObjProtocoloDTO[] = $objProtocoloDTO;
            }
          }

          $numProtocolos = count($arrObjProtocoloDTO);
          if ($numProtocolos == 1) {
            $objInfraException->adicionarValidacao('Já existe um processo no órgão "' . $strSiglaOrgaoProtocolo . '" do tipo "' . $objTipoProcedimentoDTO->getStrNome() . '" para o interessado "' . $objUsuarioDTO->getStrNome() . '" com o nº ' . $arrObjProtocoloDTO[0]->getStrProtocoloFormatado() . '.');
          } else {
            if ($numProtocolos > 1) {
              $strMsg = 'Existem ' . $numProtocolos . ' processos no órgão "' . $strSiglaOrgaoProtocolo . '" do tipo "' . $objTipoProcedimentoDTO->getStrNome() . '" para o interessado "' . $objUsuarioDTO->getStrNome() . '":\n';
              foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
                $strMsg .= $objProtocoloDTO->getStrProtocoloFormatado() . '\n';
              }
              $objInfraException->adicionarValidacao($strMsg);
            }
          }
        }
      }
    }

    return $objTipoProcedimentoDTO;
  }

  private function validarNumIdTipoPrioridade(ProcedimentoDTO $parObjProcedimentoDTO, InfraException $objInfraException) {
    $objTipoPrioridadeDTO = new TipoPrioridadeDTO();

    if (!InfraString::isBolVazia($parObjProcedimentoDTO->getNumIdTipoPrioridade())) {
      $objTipoPrioridadeDTO->setBolExclusaoLogica(false);
      $objTipoPrioridadeDTO->retNumIdTipoPrioridade();
      $objTipoPrioridadeDTO->retStrNome();
      $objTipoPrioridadeDTO->setNumIdTipoPrioridade($parObjProcedimentoDTO->getNumIdTipoPrioridade());

      $objTipoPrioridadeRN = new TipoPrioridadeRN();
      $objTipoPrioridadeDTO = $objTipoPrioridadeRN->consultar($objTipoPrioridadeDTO);

      if ($objTipoPrioridadeDTO == null) {
        throw new InfraException('Tipo de prioridade [' . $parObjProcedimentoDTO->getNumIdTipoPrioridade() . '] não encontrado.');
      }
    } else {
      $objTipoPrioridadeDTO->setNumIdTipoPrioridade(null);
      $objTipoPrioridadeDTO->setStrNome(null);
    }

    return $objTipoPrioridadeDTO;
  }

  private function validarStrSinGerarPendenciaRN0901(
    ProcedimentoDTO $objProcedimentoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProcedimentoDTO->getStrSinGerarPendencia())) {
      $objInfraException->adicionarValidacao('Sinalizador de geração de andamento automático não informado.');
    } else {
      if (!InfraUtil::isBolSinalizadorValido($objProcedimentoDTO->getStrSinGerarPendencia())) {
        $objInfraException->adicionarValidacao('Sinalizador de geração de andamento automático inválido.');
      }
    }
  }

  private function validarStrSinCiencia(ProcedimentoDTO $objProcedimentoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProcedimentoDTO->getStrSinCiencia())) {
      $objInfraException->adicionarValidacao('Sinalizador de ciência não informado.');
    } else {
      if (!InfraUtil::isBolSinalizadorValido($objProcedimentoDTO->getStrSinCiencia())) {
        $objInfraException->adicionarValidacao('Sinalizador de ciência inválido.');
      }
    }
  }

  private function validarAnexosRN0751(ProcedimentoDTO $objProcedimentoDTO, InfraException $objInfraException) {}

  private function validarNivelAcesso(ProcedimentoDTO $objProcedimentoDTO, InfraException $objInfraException) {
    $objProtocoloRN = new ProtocoloRN();
    $objProtocoloRN->validarStrStaNivelAcessoLocalRN0685($objProcedimentoDTO->getObjProtocoloDTO(), $objInfraException);

    $objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
    $objNivelAcessoPermitidoDTO->retNumIdNivelAcessoPermitido();
    $objNivelAcessoPermitidoDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
    $objNivelAcessoPermitidoDTO->setStrStaNivelAcesso($objProcedimentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal());
    $objNivelAcessoPermitidoDTO->setNumMaxRegistrosRetorno(1);

    $objNivelAcessoPermitidoRN = new NivelAcessoPermitidoRN();
    if ($objNivelAcessoPermitidoRN->consultar($objNivelAcessoPermitidoDTO) == null) {
      $objInfraException->adicionarValidacao('Nível de acesso não permitido para este tipo de processo.');
    }
  }

  //metodo para validar a eliminacao do processo, dos processos anexados, dos documentos e dos documentos dos processos anexados no sei e nos modulos do sei
  protected function validarEliminacaoConectado(ProcedimentoDTO $objProcedimentoDTO) {
    global $SEI_MODULOS;

    $objInfraException = new InfraException();

    //dto para consulta
    $objProtocoloRN = new ProtocoloRN();
    $objPesquisaAvaliacaoDocumentalDTO = new PesquisaAvaliacaoDocumentalDTO();
    $objPesquisaAvaliacaoDocumentalDTO->retDblIdProcedimento();
    $objPesquisaAvaliacaoDocumentalDTO->retStrIdProtocoloFederacao();
    $objPesquisaAvaliacaoDocumentalDTO->retStrStaEstado();
    $objPesquisaAvaliacaoDocumentalDTO->retStrProtocoloFormatado();
    $objPesquisaAvaliacaoDocumentalDTO->retStrStaNivelAcessoGlobal();
    $objPesquisaAvaliacaoDocumentalDTO->retStrStaDestinacaoAssuntoAvaliacaoDocumental();
    $objPesquisaAvaliacaoDocumentalDTO->retDtaConclusaoProcedimento();
    $objPesquisaAvaliacaoDocumentalDTO->retNumPrazoIntermediarioAssuntoAvaliacaoDocumental();
    $objPesquisaAvaliacaoDocumentalDTO->retNumPrazoCorrenteAssuntoAvaliacaoDocumental();
    $objPesquisaAvaliacaoDocumentalDTO->retStrProtocoloFormatado();
    $objPesquisaAvaliacaoDocumentalDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
    $objPesquisaAvaliacaoDocumentalDTO = $objProtocoloRN->consultarPesquisaAvaliacaoDocumental($objPesquisaAvaliacaoDocumentalDTO);
    //testes de validacao

    //nao pode ser restrito
    if ($objPesquisaAvaliacaoDocumentalDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_RESTRITO) {
      $objInfraException->adicionarValidacao('Processo ' . $objPesquisaAvaliacaoDocumentalDTO->getStrProtocoloFormatado() . ' possui nível de acesso restrito.');
    }

    if ($objPesquisaAvaliacaoDocumentalDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {
      $objInfraException->adicionarValidacao('Processo ' . $objPesquisaAvaliacaoDocumentalDTO->getStrProtocoloFormatado() . ' possui nível de acesso sigiloso.');
    }

    if ($objPesquisaAvaliacaoDocumentalDTO->getDtaConclusaoProcedimento() == null) {
      $objInfraException->adicionarValidacao('Processo ' . $objPesquisaAvaliacaoDocumentalDTO->getStrProtocoloFormatado() . ' não está concluído.');
    }

    if ($objPesquisaAvaliacaoDocumentalDTO->getStrIdProtocoloFederacao() != null) {
      $objInfraException->adicionarValidacao('Processo ' . $objPesquisaAvaliacaoDocumentalDTO->getStrProtocoloFormatado() . ' possui tramitação pelo SEI Federação.');
    }

    //nao pode ser sobrestado
    if ($objPesquisaAvaliacaoDocumentalDTO->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO) {
      $objInfraException->adicionarValidacao('Processo ' . $objPesquisaAvaliacaoDocumentalDTO->getStrProtocoloFormatado() . ' está sobrestado.');
    }
    //nao pode ser anexado
    if ($objPesquisaAvaliacaoDocumentalDTO->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
      $objInfraException->adicionarValidacao('Processo ' . $objPesquisaAvaliacaoDocumentalDTO->getStrProtocoloFormatado() . ' está anexado.');
    }
    //nao pode ser guarda permanente
    if ($objPesquisaAvaliacaoDocumentalDTO->getStrStaDestinacaoAssuntoAvaliacaoDocumental() == AssuntoRN::$TD_GUARDA_PERMANENTE) {
      $objInfraException->adicionarValidacao('Processo ' . $objPesquisaAvaliacaoDocumentalDTO->getStrProtocoloFormatado() . ' é de guarda permanente.');
    } else {
      //o prazo corrente do assunto original da avaliacao documental mais o prazo intermediario do assunto original da avaliacao documental desde a data de avaliacao documental devem ter passado para poder ser eliminado
      $dtaAtual = InfraData::getStrDataAtual();
      $dtaConclusao = $objPesquisaAvaliacaoDocumentalDTO->getDtaConclusaoProcedimento();
      $numDiasDatas = InfraData::compararDatas($dtaConclusao, $dtaAtual);
      $numPrazoCorrente = $objPesquisaAvaliacaoDocumentalDTO->getNumPrazoCorrenteAssuntoAvaliacaoDocumental();
      $numPrazoIntermediario = $objPesquisaAvaliacaoDocumentalDTO->getNumPrazoIntermediarioAssuntoAvaliacaoDocumental();
      $numDiasPrazos = ($numPrazoCorrente + $numPrazoIntermediario) * 365;
      if ($numDiasDatas < $numDiasPrazos) {
        $objInfraException->adicionarValidacao('Processo ' . $objPesquisaAvaliacaoDocumentalDTO->getStrProtocoloFormatado() . ' ainda não cumpriu o prazo de guarda (restam ' . ($numDiasPrazos - $numDiasDatas) . ' dias).');
      }
    }
    //validacao nos modulos
    $objProcedimentoAPI = new ProcedimentoAPI();
    $objProcedimentoAPI->setIdProcedimento($objPesquisaAvaliacaoDocumentalDTO->getDblIdProcedimento());
    $objProcedimentoAPI->setNumeroProtocolo($objPesquisaAvaliacaoDocumentalDTO->getStrProtocoloFormatado());
    foreach ($SEI_MODULOS as $seiModulo) {
      $seiModulo->executar('validarEliminacaoProcesso', $objProcedimentoAPI);
    }
    //validacao dos documentos
    $objDocumentoRN = new DocumentoRN();
    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoDTO->retDblIdDocumento();
    $objDocumentoDTO->setDblIdProcedimento($objPesquisaAvaliacaoDocumentalDTO->getDblIdProcedimento());
    //lista os documentos
    $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);
    //valida os documentos do processo
    $objDocumentoRN->validarEliminacao($arrObjDocumentoDTO, $objInfraException);
    //se tem processos anexados
    if (InfraArray::contar($objProcedimentoDTO->getArrObjProcedimentoAnexadoDTO()) > 0) {
      //converte para os ids
      $arrObjProcedimentoDTOAnexados = $objProcedimentoDTO->getArrObjProcedimentoAnexadoDTO();
      //itera pelos ids
      foreach ($arrObjProcedimentoDTOAnexados as $objProcedimentoDTOAnexado) {
        //chama as validacoes de eliminacao dos modulos passando os processos anexados
        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTOAnexado->getDblIdProcedimento());
        $objProcedimentoAPI->setNumeroProtocolo($objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado());
        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('validarEliminacaoProcesso', $objProcedimentoAPI);
        }
        //lista os documentos dos processos anexados
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->setDblIdProcedimento($objProcedimentoDTOAnexado->getDblIdProcedimento());
        $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);
        //valida os documentos anexados
        $objDocumentoRN->validarEliminacao($arrObjDocumentoDTO, $objInfraException);
      }
    }

    $objInfraException->lancarValidacoes();
  }

  protected function reabrirRN0966Controlado(ReabrirProcessoDTO $objReabrirProcessoDTO) {
    try {
      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_reabrir', __METHOD__, $objReabrirProcessoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retDblIdProcedimento();
      $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTO->retStrStaEstadoProtocolo();
      $objProcedimentoDTO->retStrSinEliminadoProtocolo();
      $objProcedimentoDTO->retStrStaNivelAcessoGlobalProtocolo();
      $objProcedimentoDTO->setDblIdProcedimento($objReabrirProcessoDTO->getDblIdProcedimento());
      $objProcedimentoDTO = $this->consultarRN0201($objProcedimentoDTO);

      if ($objProcedimentoDTO == null) {
        $objInfraException->lancarValidacao('Processo não encontrado.');
      }

      if ($objProcedimentoDTO->getStrSinEliminadoProtocolo() == 'S') {
        $objInfraException->lancarValidacao('Processo foi eliminado.');
      }

      if ($objProcedimentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO) {
        $objInfraException->lancarValidacao('Processo está sobrestado.');
      }

      if ($objProcedimentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
        $objInfraException->lancarValidacao('Processo está anexado.');
      }

      $objAcessoRN = new AcessoRN();
      $objAtividadeRN = new AtividadeRN();

      if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_SIGILOSO) {
        $objAcessoDTO = new AcessoDTO();
        $objAcessoDTO->setNumMaxRegistrosRetorno(1);
        $objAcessoDTO->retNumIdAcesso();
        $objAcessoDTO->setDblIdProtocolo($objReabrirProcessoDTO->getDblIdProcedimento());
        $objAcessoDTO->setNumIdUnidade($objReabrirProcessoDTO->getNumIdUnidade());
        $objAcessoDTO->setNumIdUsuario($objReabrirProcessoDTO->getNumIdUsuario());
        $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CREDENCIAL_PROCESSO);

        if ($objAcessoRN->consultar($objAcessoDTO) == null) {
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->setBolExclusaoLogica(false);
          $objUsuarioDTO->retStrStaTipo();
          $objUsuarioDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

          $objUsuarioRN = new UsuarioRN();
          $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

          if (SessaoSEI::getInstance()->isBolHabilitada() || $objUsuarioDTO->getStrStaTipo() != UsuarioRN::$TU_SISTEMA) {
            $objInfraException->lancarValidacao('Processo não encontrado para reabertura pelo usuário.');
          }
        }
      } else {
        if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_RESTRITO) {
          $objAcessoDTO = new AcessoDTO();
          $objAcessoDTO->setNumMaxRegistrosRetorno(1);
          $objAcessoDTO->retNumIdAcesso();
          $objAcessoDTO->setDblIdProtocolo($objReabrirProcessoDTO->getDblIdProcedimento());
          $objAcessoDTO->setNumIdUnidade($objReabrirProcessoDTO->getNumIdUnidade());
          $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_RESTRITO_UNIDADE);

          if ($objAcessoRN->consultar($objAcessoDTO) == null) {
            $objInfraException->lancarValidacao('Processo não encontrado para reabertura na unidade.');
          }
        } else {
          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
          $objAtividadeDTO->retNumIdAtividade();
          $objAtividadeDTO->setDblIdProtocolo($objReabrirProcessoDTO->getDblIdProcedimento());
          $objAtividadeDTO->setNumIdUnidade($objReabrirProcessoDTO->getNumIdUnidade());
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::getArrTarefasTramitacao(), InfraDTO::$OPER_IN);

          if ($objAtividadeRN->consultarRN0033($objAtividadeDTO) == null) {
            $objInfraException->lancarValidacao('Processo não tramitou pela unidade.');
          }
        }
      }

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
      $objAtividadeDTO->retNumIdAtividade();
      $objAtividadeDTO->setDblIdProtocolo($objReabrirProcessoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade($objReabrirProcessoDTO->getNumIdUnidade());

      if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_SIGILOSO) {
        $objAtividadeDTO->setNumIdUsuario($objReabrirProcessoDTO->getNumIdUsuario());
      }

      $objAtividadeDTO->setDthConclusao(null);
      $objAtividadeDTO = $objAtividadeRN->consultarRN0033($objAtividadeDTO);

      if ($objAtividadeDTO != null) {
        if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
          $objInfraException->lancarValidacao('Processo já está aberto na unidade atual.');
        } else {
          $objInfraException->lancarValidacao('Processo já está aberto com o usuário atual.');
        }
      }

      $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
      $objEditalEliminacaoConteudoDTO->retNumIdEditalEliminacao();
      $objEditalEliminacaoConteudoDTO->setDblIdProcedimentoAvaliacaoDocumental($objReabrirProcessoDTO->getDblIdProcedimento());

      $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
      $objEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->consultar($objEditalEliminacaoConteudoDTO);
      if ($objEditalEliminacaoConteudoDTO != null) {
        $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
        $objEditalEliminacaoDTO->retStrEspecificacao();
        $objEditalEliminacaoDTO->retStrSiglaUnidade();
        $objEditalEliminacaoDTO->setNumIdEditalEliminacao($objEditalEliminacaoConteudoDTO->getNumIdEditalEliminacao());

        $objEditalEliminacaoRN = new EditalEliminacaoRN();
        $objEditalEliminacaoDTO = $objEditalEliminacaoRN->consultar($objEditalEliminacaoDTO);

        $objInfraException->lancarValidacao('Processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' consta para eliminação no edital "' . $objEditalEliminacaoDTO->getStrEspecificacao() . '" da unidade ' . $objEditalEliminacaoDTO->getStrSiglaUnidade() . '.');
      }

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objReabrirProcessoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade($objReabrirProcessoDTO->getNumIdUnidade());

      if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE);
      } else {
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_REABERTURA_PROCESSO_USUARIO);
        $objAtividadeDTO->setNumIdUsuario($objReabrirProcessoDTO->getNumIdUsuario());
      }

      $ret = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      if (count($SEI_MODULOS)) {
        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objReabrirProcessoDTO->getDblIdProcedimento());
        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('reabrirProcesso', $objProcedimentoAPI);
        }
      }

      $objProcedimentoDTO_Concluido = new ProcedimentoDTO();
      $objProcedimentoDTO_Concluido->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objProcedimentoDTO_Concluido->setDtaConclusao(null);

      $objProcedimentoBD = new ProcedimentoBD(BancoSEI::getInstance());
      $objProcedimentoBD->alterar($objProcedimentoDTO_Concluido);


      $this->excluirAvaliacaoDocumental($objProcedimentoDTO);

      return $ret;
      //Auditoria
    } catch (Exception $e) {
      throw new InfraException('Erro reabrindo Processo.', $e);
    }
  }

  private function excluirAvaliacaoDocumental(ProcedimentoDTO $parObjProcedimentoDTO) {
    $objCpadAvaliacaoRN = new CpadAvaliacaoRN();
    $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();

    $objAvaliacaoDocumentalDTO = new AvaliacaoDocumentalDTO();
    $objAvaliacaoDocumentalDTO->retNumIdAvaliacaoDocumental();
    $objAvaliacaoDocumentalDTO->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
    $arrObjAvaliacaoDocumentalDTO = $objAvaliacaoDocumentalRN->listar($objAvaliacaoDocumentalDTO);
    if (InfraArray::contar($arrObjAvaliacaoDocumentalDTO) > 0) {
      $arrIdAvaliacaoDocumental = InfraArray::converterArrInfraDTO($arrObjAvaliacaoDocumentalDTO, "IdAvaliacaoDocumental");

      $objCpadAvaliacaoDTO = new CpadAvaliacaoDTO();
      $objCpadAvaliacaoDTO->retNumIdCpadAvaliacao();
      $objCpadAvaliacaoDTO->setBolExclusaoLogica(false);
      $objCpadAvaliacaoDTO->setNumIdAvaliacaoDocumental($arrIdAvaliacaoDocumental, InfraDTO::$OPER_IN);
      $objCpadAvaliacaoRN->excluir($objCpadAvaliacaoRN->listar($objCpadAvaliacaoDTO));

      $objAvaliacaoDocumentalRN->excluir($arrObjAvaliacaoDocumentalDTO);
    }
  }

  protected function listarCompletoConectado(ProcedimentoDTO $parObjProcedimentoDTO) {
    if (!$parObjProcedimentoDTO->isSetStrSinMontandoArvore()) {
      $parObjProcedimentoDTO->setStrSinMontandoArvore('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinDocTodos()) {
      $parObjProcedimentoDTO->setStrSinDocTodos('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinDocPublicavel()) {
      $parObjProcedimentoDTO->setStrSinDocPublicavel('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinDocPublicado()) {
      $parObjProcedimentoDTO->setStrSinDocPublicado('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinDocAnexos()) {
      $parObjProcedimentoDTO->setStrSinDocAnexos('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinConteudoEmail()) {
      $parObjProcedimentoDTO->setStrSinConteudoEmail('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinProcAnexados()) {
      $parObjProcedimentoDTO->setStrSinProcAnexados('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinDocCircular()) {
      $parObjProcedimentoDTO->setStrSinDocCircular('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinComentarios()) {
      $parObjProcedimentoDTO->setStrSinComentarios('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinAnotacoes()) {
      $parObjProcedimentoDTO->setStrSinAnotacoes('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinAcompanhamentos()) {
      $parObjProcedimentoDTO->setStrSinAcompanhamentos('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinObservacoes()) {
      $parObjProcedimentoDTO->setStrSinObservacoes('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinSituacoes()) {
      $parObjProcedimentoDTO->setStrSinSituacoes('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinArquivamento()) {
      $parObjProcedimentoDTO->setStrSinArquivamento('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinMarcadores()) {
      $parObjProcedimentoDTO->setStrSinMarcadores('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinControlePrazo()) {
      $parObjProcedimentoDTO->setStrSinControlePrazo('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinZip()) {
      $parObjProcedimentoDTO->setStrSinZip('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinPdf()) {
      $parObjProcedimentoDTO->setStrSinPdf('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinLinhaDireta()) {
      $parObjProcedimentoDTO->setStrSinLinhaDireta('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinFederacao()) {
      $parObjProcedimentoDTO->setStrSinFederacao('N');
    }

    if (!$parObjProcedimentoDTO->isSetStrSinSinalizacoes()) {
      $parObjProcedimentoDTO->setStrSinSinalizacoes('N');
    }

    if ($parObjProcedimentoDTO->getStrSinSinalizacoes() == 'S') {
      $parObjProcedimentoDTO->setStrSinAnotacoes('S');
      //$parObjProcedimentoDTO->setStrSinAcompanhamentos('S');
      $parObjProcedimentoDTO->setStrSinSituacoes('S');
      $parObjProcedimentoDTO->setStrSinMarcadores('S');
      $parObjProcedimentoDTO->setStrSinControlePrazo('S');
      $parObjProcedimentoDTO->setStrSinFederacao('S');
    }

    $parObjProcedimentoDTO->retDblIdProcedimento();
    $parObjProcedimentoDTO->retNumIdTipoProcedimento();
    $parObjProcedimentoDTO->retNumIdTipoPrioridade();
    $parObjProcedimentoDTO->retStrNomeTipoPrioridade();
    $parObjProcedimentoDTO->retNumIdPlanoTrabalho();
    $parObjProcedimentoDTO->retStrSinOuvidoriaTipoProcedimento();
    $parObjProcedimentoDTO->retStrSinInternoTipoProcedimento();
    $parObjProcedimentoDTO->retNumIdUnidadeGeradoraProtocolo();
    $parObjProcedimentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
    $parObjProcedimentoDTO->retStrDescricaoUnidadeGeradoraProtocolo();
    $parObjProcedimentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
    $parObjProcedimentoDTO->retStrSiglaOrgaoUnidadeGeradoraProtocolo();
    $parObjProcedimentoDTO->retStrDescricaoOrgaoUnidadeGeradoraProtocolo();
    $parObjProcedimentoDTO->retStrNomeTipoProcedimento();
    $parObjProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
    $parObjProcedimentoDTO->retStrStaNivelAcessoLocalProtocolo();
    $parObjProcedimentoDTO->retStrStaNivelAcessoGlobalProtocolo();
    $parObjProcedimentoDTO->retStrStaEstadoProtocolo();
    $parObjProcedimentoDTO->retStrSinEliminadoProtocolo();
    $parObjProcedimentoDTO->retStrSinCiencia();
    $parObjProcedimentoDTO->retStrStaOuvidoria();
    $parObjProcedimentoDTO->retNumIdHipoteseLegalProtocolo();
    $parObjProcedimentoDTO->retStrStaGrauSigiloProtocolo();
    $parObjProcedimentoDTO->retStrNomeHipoteseLegal();
    $parObjProcedimentoDTO->retStrBaseLegalHipoteseLegal();
    $parObjProcedimentoDTO->retDtaEliminacao();
    $parObjProcedimentoDTO->retDtaGeracaoProtocolo();

    $parObjProcedimentoDTO->retStrIdProtocoloFederacaoProtocolo();

    $arrProcedimentos = InfraArray::indexarArrInfraDTO($this->listarRN0278($parObjProcedimentoDTO), 'IdProcedimento');

    $numRegistros = count($arrProcedimentos);

    if ($numRegistros) {
      foreach ($arrProcedimentos as $objProcedimentoDTO) {
        $objProcedimentoDTO->setArrObjDocumentoDTO(array());
        $objProcedimentoDTO->setArrObjRelProtocoloProtocoloDTO(array());
      }

      if ($parObjProcedimentoDTO->getStrSinAnotacoes() == 'S') {
        $objAnotacaoRN = new AnotacaoRN();
        $objAnotacaoRN->complementar(array_values($arrProcedimentos));
      }

      if ($parObjProcedimentoDTO->getStrSinMontandoArvore() == 'S' || $parObjProcedimentoDTO->getStrSinAcompanhamentos() == 'S') {
        $objAcompanhamentoRN = new AcompanhamentoRN();
        $objAcompanhamentoRN->complementar(array_values($arrProcedimentos));
      }

      if ($parObjProcedimentoDTO->getStrSinMontandoArvore() == 'S' || $parObjProcedimentoDTO->getStrSinControlePrazo() == 'S') {
        $objControlePrazoRN = new ControlePrazoRN();
        $objControlePrazoRN->complementar(array_values($arrProcedimentos));
      }

      if ($parObjProcedimentoDTO->getStrSinObservacoes() == 'S') {
        $objObservacaoRN = new ObservacaoRN();
        $objObservacaoRN->complementar(array_values($arrProcedimentos));
      }

      if ($parObjProcedimentoDTO->getStrSinFederacao() == 'S') {
        $arrProtocoloFederacao = array();
        foreach ($arrProcedimentos as $dto) {
          if ($dto->getStrIdProtocoloFederacaoProtocolo() != null) {
            $arrProtocoloFederacao[$dto->getStrIdProtocoloFederacaoProtocolo()] = $dto;
          }
          $dto->setStrSinFederacao('N');
        }

        if (count($arrProtocoloFederacao)) {
          $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
          $objAcessoFederacaoDTO->setDistinct(true);
          $objAcessoFederacaoDTO->setBolExclusaoLogica(false);
          $objAcessoFederacaoDTO->retStrIdProcedimentoFederacao();
          $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao(array_keys($arrProtocoloFederacao), InfraDTO::$OPER_IN);

          $objAcessoFederacaoRN = new AcessoFederacaoRN();
          $arrAcessoFederacao = InfraArray::converterArrInfraDTO($objAcessoFederacaoRN->listar($objAcessoFederacaoDTO), 'IdProcedimentoFederacao');

          foreach ($arrAcessoFederacao as $strIdProtocoloFederacao) {
            $arrProtocoloFederacao[$strIdProtocoloFederacao]->setStrSinFederacao('S');
          }
        }
      }

      if ($parObjProcedimentoDTO->getStrSinMontandoArvore() == 'S' || $parObjProcedimentoDTO->getStrSinSituacoes() == 'S') {
        $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();
        $objAndamentoSituacaoDTO->retDblIdProcedimento();
        $objAndamentoSituacaoDTO->retStrNomeSituacao();
        $objAndamentoSituacaoDTO->retStrSinAtivoSituacao();
        $objAndamentoSituacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAndamentoSituacaoDTO->setDblIdProcedimento(array_keys($arrProcedimentos), InfraDTO::$OPER_IN);
        $objAndamentoSituacaoDTO->setStrSinUltimo('S');

        $objAndamentoSituacaoRN = new AndamentoSituacaoRN();
        $arrObjAndamentoSituacaoDTO = InfraArray::indexarArrInfraDTO($objAndamentoSituacaoRN->listar($objAndamentoSituacaoDTO), 'IdProcedimento');

        foreach ($arrProcedimentos as $dblIdProcedimento => $objProcedimentoDTO) {
          if (isset($arrObjAndamentoSituacaoDTO[$dblIdProcedimento])) {
            $objProcedimentoDTO->setObjAndamentoSituacaoDTO($arrObjAndamentoSituacaoDTO[$dblIdProcedimento]);
          } else {
            $objProcedimentoDTO->setObjAndamentoSituacaoDTO(null);
          }
        }
      }

      if ($parObjProcedimentoDTO->getStrSinMontandoArvore() == 'S' || $parObjProcedimentoDTO->getStrSinMarcadores() == 'S') {
        $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
        $objAndamentoMarcadorDTO->retDblIdProcedimento();
        $objAndamentoMarcadorDTO->retNumIdMarcador();
        $objAndamentoMarcadorDTO->retStrNomeMarcador();
        $objAndamentoMarcadorDTO->retStrTexto();
        $objAndamentoMarcadorDTO->retStrStaIconeMarcador();
        $objAndamentoMarcadorDTO->retStrSinAtivoMarcador();
        $objAndamentoMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAndamentoMarcadorDTO->setDblIdProcedimento(array_keys($arrProcedimentos), InfraDTO::$OPER_IN);
        $objAndamentoMarcadorDTO->setStrSinUltimo('S');

        $objAndamentoMarcadorRN = new AndamentoMarcadorRN();
        $arrObjAndamentoMarcadorDTO = InfraArray::indexarArrInfraDTO($objAndamentoMarcadorRN->listar($objAndamentoMarcadorDTO), 'IdProcedimento', true);

        if (count($arrObjAndamentoMarcadorDTO)) {
          $objMarcadorRN = new MarcadorRN();
          $arrObjIconeMarcadorDTO = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(), 'StaIcone');

          foreach ($arrObjAndamentoMarcadorDTO as $arrObjAndamentoMarcadorDTOProcedimento) {
            foreach ($arrObjAndamentoMarcadorDTOProcedimento as $objAndamentoMarcadorDTO) {
              $objAndamentoMarcadorDTO->setStrArquivoIconeMarcador($arrObjIconeMarcadorDTO[$objAndamentoMarcadorDTO->getStrStaIconeMarcador()]->getStrArquivo());
            }
          }
        }

        foreach ($arrProcedimentos as $dblIdProcedimento => $objProcedimentoDTO) {
          if (isset($arrObjAndamentoMarcadorDTO[$dblIdProcedimento])) {
            $objProcedimentoDTO->setArrObjAndamentoMarcadorDTO($arrObjAndamentoMarcadorDTO[$dblIdProcedimento]);
          } else {
            $objProcedimentoDTO->setArrObjAndamentoMarcadorDTO(null);
          }
        }
      }

      $arrTipoAssociacao = array();

      if ($parObjProcedimentoDTO->getStrSinMontandoArvore() == 'S' || $parObjProcedimentoDTO->getStrSinDocTodos() == 'S' || $parObjProcedimentoDTO->getStrSinDocPublicavel() == 'S' || $parObjProcedimentoDTO->getStrSinDocPublicado() == 'S') {
        $arrTipoAssociacao[] = RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO;
      }

      if ($parObjProcedimentoDTO->getStrSinMontandoArvore() == 'S' || $parObjProcedimentoDTO->getStrSinProcAnexados() == 'S') {
        $arrTipoAssociacao[] = RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO;
        $arrTipoAssociacao[] = RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_DESANEXADO;
        $arrTipoAssociacao[] = RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO;
      }

      if (count($arrTipoAssociacao)) {
        $arrIdUnidadesTramitacao = null;

        if ($parObjProcedimentoDTO->getStrSinMontandoArvore() == 'S' && $parObjProcedimentoDTO->getStrSinLinhaDireta() == 'S') {
          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDistinct(true);
          $objAtividadeDTO->retNumIdUnidade();
          $objAtividadeDTO->retNumIdUnidadeOrigem();
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::getArrTarefasTramitacao(), InfraDTO::$OPER_IN);
          $objAtividadeDTO->setDblIdProtocolo(array_keys($arrProcedimentos), InfraDTO::$OPER_IN);

          $objAtividadeDTO->adicionarCriterio(array('IdUnidade', 'IdUnidadeOrigem'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
            SessaoSEI::getInstance()->getNumIdUnidadeAtual(), SessaoSEI::getInstance()->getNumIdUnidadeAtual()
          ), InfraDTO::$OPER_LOGICO_OR);

          $objAtividadeRN = new AtividadeRN();
          $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

          $arrIdUnidadesTramitacao = array(SessaoSEI::getInstance()->getNumIdUnidadeAtual() => 1);

          foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
            if (!isset($arrIdUnidadesTramitacao[$objAtividadeDTO->getNumIdUnidade()])) {
              $arrIdUnidadesTramitacao[$objAtividadeDTO->getNumIdUnidade()] = 1;
            }

            if (!isset($arrIdUnidadesTramitacao[$objAtividadeDTO->getNumIdUnidadeOrigem()])) {
              $arrIdUnidadesTramitacao[$objAtividadeDTO->getNumIdUnidadeOrigem()] = 1;
            }
          }
        }

        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
        $objRelProtocoloProtocoloDTO->retStrStaAssociacao();
        $objRelProtocoloProtocoloDTO->retStrSinCiencia();
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao($arrTipoAssociacao, InfraDTO::$OPER_IN);
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1(array_keys($arrProcedimentos), InfraDTO::$OPER_IN);

        if ($arrIdUnidadesTramitacao != null) {
          $objRelProtocoloProtocoloDTO->setNumIdUnidade(array_keys($arrIdUnidadesTramitacao), InfraDTO::$OPER_IN);
        }

        $objRelProtocoloProtocoloDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

        if ($parObjProcedimentoDTO->isSetArrDblIdProtocoloAssociado()) {
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjProcedimentoDTO->getArrDblIdProtocoloAssociado(), InfraDTO::$OPER_IN);
        }

        if ($parObjProcedimentoDTO->isSetArrObjRelProtocoloProtocoloDTO()) {
          if (count($parObjProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO())) {
            $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo(InfraArray::converterArrInfraDTO($parObjProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO(), 'IdRelProtocoloProtocolo'), InfraDTO::$OPER_IN);
          } else {
            $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo(null);
          }
        }

        $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
        $arrObjRelProtocoloProtocoloDTO = InfraArray::indexarArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO), 'IdRelProtocoloProtocolo');

        /*
        foreach($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO){
          echo $objRelProtocoloProtocoloDTO->__toString();
        }
        die;
        */

        $arrIdDocumentos = array();
        $arrIdProcessos = array();
        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
          $objRelProtocoloProtocoloDTO->setStrSinComentarios('N');

          if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO || $objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO) {
            $arrIdDocumentos[] = $objRelProtocoloProtocoloDTO->getDblIdProtocolo2();
          } else {
            $arrIdProcessos[] = $objRelProtocoloProtocoloDTO->getDblIdProtocolo2();
          }
        }

        if ($parObjProcedimentoDTO->getStrSinComentarios() == 'S' && count($arrObjRelProtocoloProtocoloDTO)) {
          $objComentarioDTO = new ComentarioDTO();
          $objComentarioDTO->setDistinct(true);
          $objComentarioDTO->retNumIdComentario();
          $objComentarioDTO->retDblIdRelProtocoloProtocolo();
          $objComentarioDTO->setDblIdRelProtocoloProtocolo(InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO, 'IdRelProtocoloProtocolo'), InfraDTO::$OPER_IN);

          $objComentarioRN = new ComentarioRN();
          $arrObjComentarioDTO = $objComentarioRN->listar($objComentarioDTO);

          foreach ($arrObjComentarioDTO as $objComentarioDTO) {
            $arrObjRelProtocoloProtocoloDTO[$objComentarioDTO->getDblIdRelProtocoloProtocolo()]->setStrSinComentarios('S');
          }
        }

        if ($parObjProcedimentoDTO->getStrSinMontandoArvore() == 'N' && count($arrIdDocumentos)) {
          $arrDocSelecionados = array();

          //Busca dados dos documentos associados
          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->retDblIdProcedimento();
          $objDocumentoDTO->retDblIdDocumento();
          $objDocumentoDTO->retDblIdDocumentoEdoc();
          $objDocumentoDTO->retNumIdTipoFormulario();
          $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
          $objDocumentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
          $objDocumentoDTO->retNumIdUnidadeResponsavel();
          $objDocumentoDTO->retNumIdSerie();
          $objDocumentoDTO->retStrNomeSerie();
          $objDocumentoDTO->retStrSinDestinatarioSerie();
          $objDocumentoDTO->retDtaGeracaoProtocolo();
          $objDocumentoDTO->retStrStaProtocoloProtocolo();
          $objDocumentoDTO->retStrStaEstadoProtocolo();
          $objDocumentoDTO->retStrSinEliminadoProtocolo();
          $objDocumentoDTO->retStrNumero();
          $objDocumentoDTO->retStrNomeArvore();
          $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
          $objDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
          $objDocumentoDTO->retStrDescricaoUnidadeGeradoraProtocolo();
          $objDocumentoDTO->retDblIdProtocoloAgrupadorProtocolo();
          $objDocumentoDTO->retStrStaEstadoProtocolo();
          $objDocumentoDTO->retStrStaNivelAcessoLocalProtocolo();
          $objDocumentoDTO->retStrStaNivelAcessoGlobalProtocolo();
          $objDocumentoDTO->retStrStaDocumento();
          $objDocumentoDTO->retStrSinBloqueado();
          $objDocumentoDTO->retNumIdTipoConferencia();
          $objDocumentoDTO->retStrStaGrauSigiloProtocolo();
          $objDocumentoDTO->retStrNomeHipoteseLegal();
          $objDocumentoDTO->retStrBaseLegalHipoteseLegal();
          $objDocumentoDTO->retStrIdProtocoloFederacaoProtocolo();
          $objDocumentoDTO->retObjPublicacaoDTO();
          $objDocumentoDTO->retArrObjAssinaturaDTO();


          if ($parObjProcedimentoDTO->getStrSinArquivamento() == 'S') {
            $objDocumentoDTO->retObjArquivamentoDTO();
          }

          $objDocumentoDTO->setDblIdDocumento($arrIdDocumentos, InfraDTO::$OPER_IN);

          $objDocumentoRN = new DocumentoRN();
          $arr = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO), 'IdDocumento');

          //manter ordenacao
          $arrObjDocumentoDTO = array();
          foreach ($arrIdDocumentos as $dblIdDocumento) {
            if (isset($arr[$dblIdDocumento])) {
              $arrObjDocumentoDTO[$dblIdDocumento] = $arr[$dblIdDocumento];
            }
          }

          if (count($arrObjDocumentoDTO)) {
            $arrDocPublicavel = array();
            $arrDocPublicado = array();

            $objRelSerieVeiculoPublicacaoDTO = new RelSerieVeiculoPublicacaoDTO();
            $objRelSerieVeiculoPublicacaoDTO->setDistinct(true);
            $objRelSerieVeiculoPublicacaoDTO->retNumIdSerie();
            $objRelSerieVeiculoPublicacaoDTO->retStrSinAssinaturaPublicacaoSerie();
            $objRelSerieVeiculoPublicacaoDTO->setNumIdSerie(array_unique(InfraArray::converterArrInfraDTO($arrObjDocumentoDTO, 'IdSerie')), InfraDTO::$OPER_IN);

            $objRelSerieVeiculoPublicacaoRN = new RelSerieVeiculoPublicacaoRN();
            $arrObjRelSerieVeiculoPublicacaoDTO = InfraArray::indexarArrInfraDTO($objRelSerieVeiculoPublicacaoRN->listar($objRelSerieVeiculoPublicacaoDTO), 'IdSerie');

            $numIdSerieEmail = null;
            $arrIdEmail = array();
            if ($parObjProcedimentoDTO->getStrSinConteudoEmail() == 'S') {
              $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
              $numIdSerieEmail = $objInfraParametro->getValor('ID_SERIE_EMAIL');
            }

            $arrIdDocumentosGerados = array();

            foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
              $objDocumentoDTO->setObjProtocoloDTO(new ProtocoloDTO());

              $objDocumentoDTO->setStrSinAssinado('N');
              $objDocumentoDTO->setStrSinAssinadoPeloUsuarioAtual('N');
              $objDocumentoDTO->setStrSinAssinadoPelaUnidadeAtual('N');
              $objDocumentoDTO->setStrSinAssinadoPorOutraUnidade('N');
              $objDocumentoDTO->setStrSinPublicavel('N');
              $objDocumentoDTO->setStrSinPublicacaoAgendada('N');
              $objDocumentoDTO->setStrSinPublicado('N');
              $objDocumentoDTO->setStrSinCircular('N');
              $objDocumentoDTO->setStrSinZip('N');
              $objDocumentoDTO->setStrSinPdf('N');

              if (InfraArray::contar($objDocumentoDTO->getArrObjAssinaturaDTO()) > 0) {
                $objDocumentoDTO->setStrSinAssinado('S');
              }

              $arrObjAssinaturaDTO = $objDocumentoDTO->getArrObjAssinaturaDTO();
              foreach ($arrObjAssinaturaDTO as $objAssinaturaDTO) {
                if ($objAssinaturaDTO->getNumIdUsuario() == SessaoSEI::getInstance()->getNumIdUsuario()) {
                  $objDocumentoDTO->setStrSinAssinadoPeloUsuarioAtual('S');
                }

                if ($objAssinaturaDTO->getNumIdUnidade() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
                  $objDocumentoDTO->setStrSinAssinadoPelaUnidadeAtual('S');
                } else {
                  $objDocumentoDTO->setStrSinAssinadoPorOutraUnidade('S');
                }
              }

              if ($objDocumentoDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
                $arrIdDocumentosGerados[] = $objDocumentoDTO->getDblIdDocumento();

                //tem conteudo
                if ($objDocumentoRN->verificarConteudoGerado($objDocumentoDTO)) {
                  $objPublicacaoDTO = $objDocumentoDTO->getObjPublicacaoDTO();

                  //se não tem publicações
                  if ($objPublicacaoDTO == null) {
                    if (isset($arrObjRelSerieVeiculoPublicacaoDTO[$objDocumentoDTO->getNumIdSerie()]) && ($arrObjRelSerieVeiculoPublicacaoDTO[$objDocumentoDTO->getNumIdSerie()]->getStrSinAssinaturaPublicacaoSerie() == 'N' || InfraArray::contar($objDocumentoDTO->getArrObjAssinaturaDTO()))) {
                      $objDocumentoDTO->setStrSinPublicavel('S');
                      $arrDocPublicavel[] = $objDocumentoDTO->getDblIdDocumento();
                    }
                  } else {
                    if ($objPublicacaoDTO->getStrStaEstado() == PublicacaoRN::$TE_PUBLICADO) {
                      $objDocumentoDTO->setStrSinPublicado('S');
                      $arrDocPublicado[] = $objDocumentoDTO->getDblIdDocumento();
                    } else {
                      $objDocumentoDTO->setStrSinPublicacaoAgendada('S');
                    }
                  }
                }
              }

              if ($objDocumentoDTO->getStrStaEstadoProtocolo() != ProtocoloRN::$TE_DOCUMENTO_CANCELADO && $objDocumentoDTO->getNumIdSerie() == $numIdSerieEmail && $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {
                $arrIdEmail[] = $objDocumentoDTO->getDblIdDocumento();
              }
            }

            if ($parObjProcedimentoDTO->getStrSinPdf() == 'S' || $parObjProcedimentoDTO->getStrSinZip() == 'S') {
              $arrIdDocumentosExternos = array_diff(array_keys($arrObjDocumentoDTO), $arrIdDocumentosGerados);

              $arrObjAnexoDTO = array();

              if (count($arrIdDocumentosExternos)) {
                $objAnexoDTO = new AnexoDTO();
                $objAnexoDTO->retDblIdProtocolo();
                $objAnexoDTO->retNumIdAnexo();
                $objAnexoDTO->retStrNome();
                $objAnexoDTO->setDblIdProtocolo($arrIdDocumentosExternos, InfraDTO::$OPER_IN);

                $objAnexoRN = new AnexoRN();
                $arrObjAnexoDTO = InfraArray::indexarArrInfraDTO($objAnexoRN->listarRN0218($objAnexoDTO), 'IdProtocolo');
              }

              foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
                if (isset($arrObjAnexoDTO[$objDocumentoDTO->getDblIdDocumento()])) {
                  $objDocumentoDTO->setObjAnexoDTO($arrObjAnexoDTO[$objDocumentoDTO->getDblIdDocumento()]);
                } else {
                  $objDocumentoDTO->setObjAnexoDTO(null);
                }

                if ($parObjProcedimentoDTO->getStrSinZip() == 'S' && $objDocumentoRN->verificarSelecaoGeracaoZip($objDocumentoDTO)) {
                  $objDocumentoDTO->setStrSinZip('S');
                }

                if ($parObjProcedimentoDTO->getStrSinPdf() == 'S' && $objDocumentoRN->verificarSelecaoGeracaoPdf($objDocumentoDTO)) {
                  $objDocumentoDTO->setStrSinPdf('S');
                }
              }
            }

            if (InfraArray::contar($arrIdEmail)) {
              $objDocumentoDTO = new DocumentoDTO();
              $objDocumentoDTO->retDblIdDocumento();
              $objDocumentoDTO->retStrConteudo();
              $objDocumentoDTO->setDblIdDocumento($arrIdEmail, InfraDTO::$OPER_IN);
              $arrObjDocumentoDTOEmail = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO), 'IdDocumento');
              foreach ($arrObjDocumentoDTOEmail as $objDocumentoDTOEmail) {
                $arrObjDocumentoDTO[$objDocumentoDTOEmail->getDblIdDocumento()]->setStrConteudo($objDocumentoDTOEmail->getStrConteudo());
              }
              unset($arrObjDocumentoDTOEmail);
            }


            if ($parObjProcedimentoDTO->getStrSinDocCircular() == 'S' && InfraArray::contar($arrIdDocumentosGerados)) {
              $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
              $objRelProtocoloProtocoloDTO->setDistinct(true);
              $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
              $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($arrIdDocumentosGerados, InfraDTO::$OPER_IN);
              $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_CIRCULAR);

              $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
              $arrObjRelProtocoloProtocoloDTOCircular = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

              foreach ($arrObjRelProtocoloProtocoloDTOCircular as $objRelProtocoloProtocoloDTO) {
                $arrObjDocumentoDTO[$objRelProtocoloProtocoloDTO->getDblIdProtocolo1()]->setStrSinCircular('S');
              }
            }


            if ($parObjProcedimentoDTO->getStrSinDocTodos() == 'S') {
              $arrDocSelecionados = array_keys($arrObjDocumentoDTO);
            } else {
              if ($parObjProcedimentoDTO->getStrSinDocPublicavel() == 'S') {
                $arrDocSelecionados = array_merge($arrDocSelecionados, $arrDocPublicavel);
              }

              if ($parObjProcedimentoDTO->getStrSinDocPublicado() == 'S') {
                $arrDocSelecionados = array_merge($arrDocSelecionados, $arrDocPublicado);
              }
            }

            $arrDocSelecionados = array_unique($arrDocSelecionados);

            foreach ($arrProcedimentos as $objProcedimentoDTO) {
              //ArrDocumentos
              $arrSubConjunto = array();
              $numDocSelecionados = InfraArray::contar($arrDocSelecionados);
              if ($numDocSelecionados) {
                //Filtra os IDs dos documentos selecionados do processo atual
                for ($i = 0; $i < $numDocSelecionados; $i++) {
                  if ($arrObjDocumentoDTO[$arrDocSelecionados[$i]]->getDblIdProcedimento() == $objProcedimentoDTO->getDblIdProcedimento()) {
                    $arrSubConjunto[] = $arrObjDocumentoDTO[$arrDocSelecionados[$i]];
                  }
                }

                if ($parObjProcedimentoDTO->getStrSinDocAnexos() == 'S') {
                  $objAnexoRN = new AnexoRN();
                  $objAnexoDTO = new AnexoDTO;
                  $objAnexoDTO->retNumIdAnexo();
                  $objAnexoDTO->retDblIdProtocolo();
                  $objAnexoDTO->retNumIdUnidade();
                  $objAnexoDTO->retStrNome();
                  $objAnexoDTO->retDthInclusao();
                  $objAnexoDTO->retNumTamanho();
                  $objAnexoDTO->setDblIdProtocolo($arrDocSelecionados, InfraDTO::$OPER_IN);
                  $objAnexoDTO->setOrdNumIdAnexo(InfraDTO::$TIPO_ORDENACAO_DESC);

                  $arrObjAnexoDTO = InfraArray::indexarArrInfraDTO($objAnexoRN->listarRN0218($objAnexoDTO), 'IdProtocolo', true);

                  foreach ($arrSubConjunto as $objDocumentoDTO) {
                    if ($objDocumentoDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
                      if (isset($arrObjAnexoDTO[$objDocumentoDTO->getDblIdDocumento()])) {
                        $objDocumentoDTO->getObjProtocoloDTO()->setArrObjAnexoDTO($arrObjAnexoDTO[$objDocumentoDTO->getDblIdDocumento()]);
                      } else {
                        $objDocumentoDTO->getObjProtocoloDTO()->setArrObjAnexoDTO(array());
                      }
                    }
                  }
                }
              }
              $objProcedimentoDTO->setArrObjDocumentoDTO($arrSubConjunto);
            }
          }

          foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
            if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO || $objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO) {
              if (!isset($arrObjDocumentoDTO[$objRelProtocoloProtocoloDTO->getDblIdProtocolo2()])) {
                throw new InfraException('Documento incompleto.', null, $objRelProtocoloProtocoloDTO->__toString());
              }

              $objRelProtocoloProtocoloDTO->setObjProtocoloDTO2($arrObjDocumentoDTO[$objRelProtocoloProtocoloDTO->getDblIdProtocolo2()]);
            }
          }
        }

        if (InfraArray::contar($arrIdProcessos)) {
          $objProcedimentoDTO = new ProcedimentoDTO();
          $objProcedimentoDTO->retDblIdProcedimento();
          $objProcedimentoDTO->retStrIdProtocoloFederacaoProtocolo();
          $objProcedimentoDTO->retStrStaNivelAcessoLocalProtocolo();
          $objProcedimentoDTO->retStrStaNivelAcessoGlobalProtocolo();
          $objProcedimentoDTO->retStrStaNivelAcessoOriginalProtocolo();
          $objProcedimentoDTO->retStrStaEstadoProtocolo();
          $objProcedimentoDTO->retStrSinEliminadoProtocolo();
          $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
          $objProcedimentoDTO->retNumIdTipoProcedimento();
          $objProcedimentoDTO->retStrNomeTipoProcedimento();
          $objProcedimentoDTO->retDtaGeracaoProtocolo();
          $objProcedimentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
          $objProcedimentoDTO->retNumIdUnidadeGeradoraProtocolo();
          $objProcedimentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
          $objProcedimentoDTO->retStrDescricaoUnidadeGeradoraProtocolo();
          $objProcedimentoDTO->retStrStaGrauSigiloProtocolo();
          $objProcedimentoDTO->retStrNomeHipoteseLegal();
          $objProcedimentoDTO->retStrBaseLegalHipoteseLegal();
          $objProcedimentoDTO->setDblIdProcedimento($arrIdProcessos, InfraDTO::$OPER_IN);

          $arr = InfraArray::indexarArrInfraDTO($this->listarRN0278($objProcedimentoDTO), 'IdProcedimento');

          foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
            if (isset($arr[$objRelProtocoloProtocoloDTO->getDblIdProtocolo2()])) {
              $objRelProtocoloProtocoloDTO->setObjProtocoloDTO2($arr[$objRelProtocoloProtocoloDTO->getDblIdProtocolo2()]);
            }
          }
        }

        $arrAssociacoesPorProcesso = InfraArray::indexarArrInfraDTO($arrObjRelProtocoloProtocoloDTO, 'IdProtocolo1', true);
        foreach ($arrAssociacoesPorProcesso as $dblIdProcedimento => $arrAssociacoesProcesso) {
          $arrProcedimentos[$dblIdProcedimento]->setArrObjRelProtocoloProtocoloDTO($arrAssociacoesProcesso);
        }
      }
    }
    return array_values($arrProcedimentos);
  }

  protected function relacionarProcedimentoRN1020Controlado(
    RelProtocoloProtocoloDTO $objRelProtocoloProtocoloRecebidoDTO) {
    try {
      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_relacionar', __METHOD__, $objRelProtocoloProtocoloRecebidoDTO);


      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1() == $objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2()) {
        $objInfraException->lancarValidacao('Processo não pode ser relacionado a ele mesmo.');
      }

      //Recuperar dados do procedimento
      $objProtocoloDTODestino = new ProtocoloDTO();
      $objProtocoloDTODestino->retDblIdProtocolo();
      $objProtocoloDTODestino->retStrProtocoloFormatado();
      $objProtocoloDTODestino->retStrStaProtocolo();
      $objProtocoloDTODestino->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1());

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTODestino = $objProtocoloRN->consultarRN0186($objProtocoloDTODestino);

      if ($objProtocoloDTODestino == null) {
        $objInfraException->lancarValidacao('Processo [' . $objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1() . '] não encontrado.');
      }

      if ($objProtocoloDTODestino->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {
        $objInfraException->lancarValidacao('Protocolo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . ' não é um processo.');
      }

      /*
      $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
      $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
      $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS_EXCETO_SIGILOSOS_SEM_ACESSO);
      $objPesquisaProtocoloDTO->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1());


      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

      if (InfraArray::contar($arrObjProtocoloDTO)==0){
        $objInfraException->lancarValidacao('Processo destino não localizado.');
      }
      */

      //Recuperar dados do segundo protocolo  (
      $objProtocoloDTOAtual = new ProtocoloDTO();
      $objProtocoloDTOAtual->retDblIdProtocolo();
      $objProtocoloDTOAtual->retStrProtocoloFormatado();
      $objProtocoloDTOAtual->retStrStaProtocolo();
      $objProtocoloDTOAtual->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());

      $objProtocoloDTOAtual = $objProtocoloRN->consultarRN0186($objProtocoloDTOAtual);

      if ($objProtocoloDTOAtual == null) {
        $objInfraException->lancarValidacao('Processo [' . $objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2() . '] não encontrado.');
      }

      if ($objProtocoloDTOAtual->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {
        $objInfraException->lancarValidacao('Protocolo ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ' não é um processo.');
      }

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);
      $objRelProtocoloProtocoloDTO->setNumMaxRegistrosRetorno(1);

      if ($objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO) != null) {
        $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ' já relaciona o processo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . '.');
      }

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);
      $objRelProtocoloProtocoloDTO->setNumMaxRegistrosRetorno(1);

      if ($objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO) != null) {
        $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . ' já relaciona o processo ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . '.');
      }

      //Criar associação entre os processos
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo(null);
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
      $objRelProtocoloProtocoloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objRelProtocoloProtocoloDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);
      $objRelProtocoloProtocoloDTO->setNumSequencia(0);
      $objRelProtocoloProtocoloDTO->setDthAssociacao(InfraData::getStrDataHoraAtual());

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objRelProtocoloProtocoloRN->cadastrarRN0839($objRelProtocoloProtocoloDTO);

      if (count($SEI_MODULOS)) {
        $objProcedimentoAPI1 = new ProcedimentoAPI();
        $objProcedimentoAPI1->setIdProcedimento($objProtocoloDTODestino->getDblIdProtocolo());
        $objProcedimentoAPI1->setNumeroProtocolo($objProtocoloDTODestino->getStrProtocoloFormatado());

        $objProcedimentoAPI2 = new ProcedimentoAPI();
        $objProcedimentoAPI2->setIdProcedimento($objProtocoloDTOAtual->getDblIdProtocolo());
        $objProcedimentoAPI2->setNumeroProtocolo($objProtocoloDTOAtual->getStrProtocoloFormatado());

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('relacionarProcesso', $objProcedimentoAPI1, $objProcedimentoAPI2);
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro relacionando processo.', $e);
    }
  }

  protected function removerRelacionamentoProcedimentoRN1021Controlado(
    RelProtocoloProtocoloDTO $objRelProtocoloProtocoloRecebidoDTO) {
    try {
      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_excluir_relacionamento', __METHOD__, $objRelProtocoloProtocoloRecebidoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();

      $objRelProtocoloProtocoloDTOConsulta = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTOConsulta->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTOConsulta->retNumIdUnidade();
      $objRelProtocoloProtocoloDTOConsulta->retStrSiglaUnidade();
      $objRelProtocoloProtocoloDTOConsulta->retDblIdProtocolo1();
      $objRelProtocoloProtocoloDTOConsulta->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTOConsulta->retStrProtocoloFormatadoProtocolo1();
      $objRelProtocoloProtocoloDTOConsulta->retStrProtocoloFormatadoProtocolo2();

      $objRelProtocoloProtocoloDTOConsulta->adicionarCriterio(array('IdProtocolo1', 'IdProtocolo2'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
        $objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1(), $objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2()
      ), InfraDTO::$OPER_LOGICO_AND, 'p12');

      $objRelProtocoloProtocoloDTOConsulta->adicionarCriterio(array('IdProtocolo1', 'IdProtocolo2'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
        $objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2(), $objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1()
      ), InfraDTO::$OPER_LOGICO_AND, 'p21');

      $objRelProtocoloProtocoloDTOConsulta->agruparCriterios(array('p12', 'p21'), InfraDTO::$OPER_LOGICO_OR);

      $objRelProtocoloProtocoloDTOConsulta->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);

      $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTOConsulta);

      if (count($arrObjRelProtocoloProtocoloDTO) == 0) {
        $objInfraException->lancarValidacao('Relacionamento entre processos não encontrado.');
      }

      foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
        if ($objRelProtocoloProtocoloDTO->getNumIdUnidade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
          $objInfraException->lancarValidacao('Relacionamento foi cadastrado pela unidade ' . $objRelProtocoloProtocoloDTO->getStrSiglaUnidade() . '.');
        }
      }

      $objRelProtocoloProtocoloRN->excluirRN0842($arrObjRelProtocoloProtocoloDTO);

      if (count($SEI_MODULOS)) {
        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
          $objProcedimentoAPI1 = new ProcedimentoAPI();
          $objProcedimentoAPI1->setIdProcedimento($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
          $objProcedimentoAPI1->setNumeroProtocolo($objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo1());

          $objProcedimentoAPI2 = new ProcedimentoAPI();
          $objProcedimentoAPI2->setIdProcedimento($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
          $objProcedimentoAPI2->setNumeroProtocolo($objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo2());

          foreach ($SEI_MODULOS as $seiModulo) {
            $seiModulo->executar('removerRelacionamentoProcesso', $objProcedimentoAPI1, $objProcedimentoAPI2);
          }
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro removendo relacionamento de processo.', $e);
    }
  }

  protected function sobrestarRN1014Controlado($arrObjRelProtocoloProtocoloRecebidoDTO) {
    try {
      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_sobrestar', __METHOD__, $arrObjRelProtocoloProtocoloRecebidoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objProtocoloRN = new ProtocoloRN();
      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objAtividadeRN = new AtividadeRN();
      $objRetornoProgramadoRN = new RetornoProgramadoRN();

      foreach ($arrObjRelProtocoloProtocoloRecebidoDTO as $objRelProtocoloProtocoloRecebidoDTO) {
        if (InfraString::isBolVazia($objRelProtocoloProtocoloRecebidoDTO->getStrMotivo())) {
          $objInfraException->lancarValidacao('Motivo não informado.');
        }

        $objProtocoloDTOAtual = new ProtocoloDTO();
        $objProtocoloDTOAtual->retDblIdProtocolo();
        $objProtocoloDTOAtual->retStrStaProtocolo();
        $objProtocoloDTOAtual->retStrStaEstado();
        $objProtocoloDTOAtual->retStrSinEliminado();
        $objProtocoloDTOAtual->retStrStaNivelAcessoGlobal();
        $objProtocoloDTOAtual->retStrProtocoloFormatado();

        $objProtocoloDTOAtual->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());

        $objProtocoloDTOAtual = $objProtocoloRN->consultarRN0186($objProtocoloDTOAtual);

        if ($objProtocoloDTOAtual == null) {
          $objInfraException->lancarValidacao('Protocolo [' . $objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2() . '] não encontrado.');
        }

        if ($objProtocoloDTOAtual->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {
          $objInfraException->lancarValidacao('Protocolo ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ' não é um processo.');
        }

        if ($objProtocoloDTOAtual->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
          $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ' não pode estar anexado.');
        }

        if ($objProtocoloDTOAtual->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {
          $objInfraException->lancarValidacao('Processo sigiloso ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ' não pode ser sobrestado.');
        }

        $this->verificarEstadoProcedimento($objProtocoloDTOAtual);

        $objRetornoProgramadoDTO = new RetornoProgramadoDTO();
        $objRetornoProgramadoDTO->setDblIdProtocolo($objProtocoloDTOAtual->getDblIdProtocolo());
        $objRetornoProgramadoRN->validarExistencia($objRetornoProgramadoDTO, $objInfraException);
        $objInfraException->lancarValidacoes();

        // tramitação unificada
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setDblIdProcedimento($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
        $this->validarTramitacaoUnificada($objProcedimentoDTO, $objInfraException);

        //processo atual não pode estar sobrestado a outro processo
        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo2();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProtocoloDTOAtual->getDblIdProtocolo());
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_SOBRESTADO);

        $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);
        if (count($arrObjRelProtocoloProtocoloDTO) > 0) {
          $objInfraException->lancarValidacao('Os processos abaixo estão sobrestados com vinculação ao processo ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ':' . "\\n" . implode("\\n",
              InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO, 'ProtocoloFormatadoProtocolo2')));
        }

        $objProtocoloDTODestino = null;

        if (!InfraString::isBolVazia($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1())) {
          $objProtocoloDTODestino = new ProtocoloDTO();
          $objProtocoloDTODestino->retDblIdProtocolo();
          $objProtocoloDTODestino->retStrStaProtocolo();
          $objProtocoloDTODestino->retStrStaEstado();
          $objProtocoloDTODestino->retStrProtocoloFormatado();
          $objProtocoloDTODestino->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1());

          $objProtocoloDTODestino = $objProtocoloRN->consultarRN0186($objProtocoloDTODestino);

          if ($objProtocoloDTODestino == null) {
            $objInfraException->lancarValidacao('Protocolo [' . $objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1() . '] não encontrado.');
          }

          if ($objProtocoloDTODestino->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {
            $objInfraException->lancarValidacao('Protocolo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . ' não é um processo.');
          }

          if ($objProtocoloDTOAtual->getDblIdProtocolo() == $objProtocoloDTODestino->getDblIdProtocolo()) {
            $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ' não pode estar sobrestado com vinculação a ele mesmo.');
          }

          //processo destino não pode estar anexado
          if ($objProtocoloDTODestino->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
            $objInfraException->lancarValidacao('Processo de destino da vinculação ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ' não pode estar anexado.');
          }

          //processo destino não pode estar sobrestado
          if ($objProtocoloDTODestino->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO) {
            $objInfraException->lancarValidacao('Processo de destino da vinculação ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ' está sobrestado.');
          }
        }

        //muda estado do protocolo
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setStrStaEstado(ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO);
        $objProtocoloDTO->setDblIdProtocolo($objProtocoloDTOAtual->getDblIdProtocolo());
        $objProtocoloRN->alterarRN0203($objProtocoloDTO);

        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objProtocoloDTOAtual->getDblIdProtocolo());
        $objProcedimentoAPI->setNumeroProtocolo($objProtocoloDTOAtual->getStrProtocoloFormatado());

        $objProcedimentoAPIVinculado = null;

        //se não tem processo associado
        if ($objProtocoloDTODestino == null) {
          $arrObjAtributoAndamentoDTO = array();

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('MOTIVO');
          $objAtributoAndamentoDTO->setStrValor($objRelProtocoloProtocoloRecebidoDTO->getStrMotivo());
          $objAtributoAndamentoDTO->setStrIdOrigem(null);
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          //Lançar andamento de sobrestamento
          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTOAtual->getDblIdProtocolo());
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_SOBRESTAMENTO);
          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
        } else {
          $objProcedimentoAPIVinculado = new ProcedimentoAPI();
          $objProcedimentoAPIVinculado->setIdProcedimento($objProtocoloDTODestino->getDblIdProtocolo());
          $objProcedimentoAPIVinculado->setNumeroProtocolo($objProtocoloDTODestino->getStrProtocoloFormatado());

          //Criar associação entre os processos
          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo(null);
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProtocoloDTODestino->getDblIdProtocolo());
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProtocoloDTOAtual->getDblIdProtocolo());
          $objRelProtocoloProtocoloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
          $objRelProtocoloProtocoloDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_SOBRESTADO);
          $objRelProtocoloProtocoloDTO->setNumSequencia(0);
          $objRelProtocoloProtocoloDTO->setDthAssociacao(InfraData::getStrDataHoraAtual());

          $objRelProtocoloProtocoloRN->cadastrarRN0839($objRelProtocoloProtocoloDTO);

          $arrObjAtributoAndamentoDTO = array();
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('PROCESSO');
          $objAtributoAndamentoDTO->setStrValor($objProtocoloDTOAtual->getStrProtocoloFormatado());
          $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTOAtual->getDblIdProtocolo());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('MOTIVO');
          $objAtributoAndamentoDTO->setStrValor($objRelProtocoloProtocoloRecebidoDTO->getStrMotivo());
          $objAtributoAndamentoDTO->setStrIdOrigem(null);
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          //Gerar atividade de vinculação de procedimento
          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTODestino->getDblIdProtocolo());
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_SOBRESTANDO_PROCESSO);
          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

          /** **************************************************************** */

          $arrObjAtributoAndamentoDTO = array();
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('PROCESSO');
          $objAtributoAndamentoDTO->setStrValor($objProtocoloDTODestino->getStrProtocoloFormatado());
          $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTODestino->getDblIdProtocolo());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('MOTIVO');
          $objAtributoAndamentoDTO->setStrValor($objRelProtocoloProtocoloRecebidoDTO->getStrMotivo());
          $objAtributoAndamentoDTO->setStrIdOrigem(null);
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          //Gerar atividade de vinculação de procedimento
          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTOAtual->getDblIdProtocolo());
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_SOBRESTADO_AO_PROCESSO);
          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
        }

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('sobrestarProcesso', $objProcedimentoAPI, $objProcedimentoAPIVinculado);
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro sobrestando processo.', $e);
    }
  }

  protected function removerSobrestamentoRN1017Controlado($arrObjRelProtocoloProtocoloRecebidoDTO) {
    try {
      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_remover_sobrestamento', __METHOD__, $arrObjRelProtocoloProtocoloRecebidoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objProtocoloRN = new ProtocoloRN();
      $objAtividadeRN = new AtividadeRN();

      $arrObjAtividadeDTO = array();

      foreach ($arrObjRelProtocoloProtocoloRecebidoDTO as $objRelProtocoloProtocoloRecebidoDTO) {
        //Recuperar dados do processo
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retStrStaProtocolo();
        $objProtocoloDTO->retStrProtocoloFormatado();
        $objProtocoloDTO->retStrStaEstado();
        $objProtocoloDTO->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());

        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

        if ($objProtocoloDTO->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {
          $objInfraException->lancarValidacao('Protocolo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não é um processo.');
        }

        if ($objProtocoloDTO->getStrStaEstado() != ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO) {
          $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não está sobrestado.');
        }

        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objProtocoloDTO->getDblIdProtocolo());
        $objProcedimentoAPI->setNumeroProtocolo($objProtocoloDTO->getStrProtocoloFormatado());

        $objProcedimentoAPIVinculado = null;

        //Alterar estado do procedimento
        $objDTO = new ProtocoloDTO();
        $objDTO->setStrStaEstado(ProtocoloRN::$TE_NORMAL);
        $objDTO->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
        $objProtocoloRN->alterarRN0203($objDTO);

        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
        $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo1();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
        $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo2();
        $objRelProtocoloProtocoloDTO->retStrStaAssociacao();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_SOBRESTADO);

        $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
        $arrRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

        $objEntradaRemoverSobrestamentoProcessoAPI = new EntradaRemoverSobrestamentoProcessoAPI();
        $objEntradaRemoverSobrestamentoProcessoAPI->setIdProcedimento($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
        $objEntradaRemoverSobrestamentoProcessoAPI->setProtocoloProcedimento($objProtocoloDTO->getStrProtocoloFormatado());

        //sobrestado a mais de um
        if (count($arrRelProtocoloProtocoloDTO) > 1) {
          $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' está sobrestado com vinculação a mais de um processo.');
          //sobrestado sem vinculacao
        } else {
          if (count($arrRelProtocoloProtocoloDTO) == 0) {
            //Gerar atividade de remoção de sobrestamento
            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_REMOCAO_SOBRESTAMENTO);

            $arrObjAtividadeDTO[] = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
            //sobrestado vinculado a um
          } else {
            $objProcedimentoAPIVinculado = new ProcedimentoAPI();
            $objProcedimentoAPIVinculado->setIdProcedimento($arrRelProtocoloProtocoloDTO[0]->getDblIdProtocolo1());
            $objProcedimentoAPIVinculado->setNumeroProtocolo($arrRelProtocoloProtocoloDTO[0]->getStrProtocoloFormatadoProtocolo1());

            $arrObjAtributoAndamentoDTO = array();
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('PROCESSO');
            $objAtributoAndamentoDTO->setStrValor($arrRelProtocoloProtocoloDTO[0]->getStrProtocoloFormatadoProtocolo2());
            $objAtributoAndamentoDTO->setStrIdOrigem($arrRelProtocoloProtocoloDTO[0]->getDblIdProtocolo2());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            //Gerar atividade de vinculação de procedimento
            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($arrRelProtocoloProtocoloDTO[0]->getDblIdProtocolo1());
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_REMOCAO_SOBRESTANDO_PROCESSO);
            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

            $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);


            $arrObjAtributoAndamentoDTO = array();
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('PROCESSO');
            $objAtributoAndamentoDTO->setStrValor($arrRelProtocoloProtocoloDTO[0]->getStrProtocoloFormatadoProtocolo1());
            $objAtributoAndamentoDTO->setStrIdOrigem($arrRelProtocoloProtocoloDTO[0]->getDblIdProtocolo1());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            //Gerar atividade de vinculação de procedimento
            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($arrRelProtocoloProtocoloDTO[0]->getDblIdProtocolo2());
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_REMOCAO_SOBRESTADO_AO_PROCESSO);
            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);


            $arrObjAtividadeDTO[] = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

            $objRelProtocoloProtocoloRN->excluirRN0842($arrRelProtocoloProtocoloDTO);
          }
        }

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('removerSobrestamentoProcesso', $objProcedimentoAPI, $objProcedimentoAPIVinculado);
        }
      }

      return $arrObjAtividadeDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro removendo sobrestamento de processo.', $e);
    }
  }

  public function anexar(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloRecebidoDTO) {
    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $this->anexarInterno($objRelProtocoloProtocoloRecebidoDTO);

    if (!$bolAcumulacaoPrevia) {
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }
  }

  protected function anexarInternoControlado(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloRecebidoDTO) {
    try {
      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_anexar', __METHOD__, $objRelProtocoloProtocoloRecebidoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objProtocoloRN = new ProtocoloRN();
      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objAtividadeRN = new AtividadeRN();

      $objProtocoloDTOAtual = new ProtocoloDTO();
      $objProtocoloDTOAtual->retDblIdProtocolo();
      $objProtocoloDTOAtual->retStrStaProtocolo();
      $objProtocoloDTOAtual->retStrStaEstado();
      $objProtocoloDTOAtual->retStrSinEliminado();
      $objProtocoloDTOAtual->retStrStaNivelAcessoGlobal();
      $objProtocoloDTOAtual->retStrProtocoloFormatado();
      $objProtocoloDTOAtual->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1());

      $objProtocoloDTOAtual = $objProtocoloRN->consultarRN0186($objProtocoloDTOAtual);

      if ($objProtocoloDTOAtual == null) {
        throw new InfraException('Processo origem não encontrado.');
      }

      if ($objProtocoloDTOAtual->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {
        $objInfraException->lancarValidacao('Protocolo ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ' não é um processo.');
      }

      if ($objProtocoloDTOAtual->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {
        $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTOAtual->getStrProtocoloFormatado() . ' não pode ser sigiloso.');
      }

      $this->verificarEstadoProcedimento($objProtocoloDTOAtual);

      $objProtocoloDTODestino = new ProtocoloDTO();
      $objProtocoloDTODestino->retDblIdProtocolo();
      $objProtocoloDTODestino->retStrStaProtocolo();
      $objProtocoloDTODestino->retStrStaEstado();
      $objProtocoloDTODestino->retStrSinEliminado();
      $objProtocoloDTODestino->retStrProtocoloFormatado();
      $objProtocoloDTODestino->retStrStaNivelAcessoGlobal();
      $objProtocoloDTODestino->retStrIdProtocoloFederacao();
      $objProtocoloDTODestino->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());

      $objProtocoloDTODestino = $objProtocoloRN->consultarRN0186($objProtocoloDTODestino);

      if ($objProtocoloDTODestino == null) {
        throw new InfraException('Processo destino não encontrado.');
      }

      if ($objProtocoloDTODestino->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {
        $objInfraException->lancarValidacao('Protocolo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . ' não é um processo.');
      }

      if ($objProtocoloDTOAtual->getDblIdProtocolo() == $objProtocoloDTODestino->getDblIdProtocolo()) {
        $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . ' não pode ser anexado a ele mesmo.');
      }

      if ($objProtocoloDTODestino->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {
        $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . ' não pode ser sigiloso.');
      }

      if ($objProtocoloDTODestino->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
        $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . ' não pode estar anexado a outro processo.');
      }

      if ($objProtocoloDTODestino->getStrIdProtocoloFederacao() != null) {
        $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
        $objAcessoFederacaoDTO->retStrIdAcessoFederacao();
        $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($objProtocoloDTODestino->getStrIdProtocoloFederacao());
        $objAcessoFederacaoDTO->setNumMaxRegistrosRetorno(1);

        $objAcessoFederacaoRN = new AcessoFederacaoRN();
        if ($objAcessoFederacaoRN->consultar($objAcessoFederacaoDTO) != null) {
          $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . ' possui tramitação no SEI Federação.');
        }
      }

      $this->verificarEstadoProcedimento($objProtocoloDTODestino);

      $objRetornoProgramadoDTO = new RetornoProgramadoDTO();
      $objRetornoProgramadoDTO->setDblIdProtocolo($objProtocoloDTODestino->getDblIdProtocolo());
      $objRetornoProgramadoRN = new RetornoProgramadoRN();
      $objRetornoProgramadoRN->validarExistencia($objRetornoProgramadoDTO, $objInfraException);
      $objInfraException->lancarValidacoes();

      //verifica documentos do procedimento
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProtocoloDTODestino->getDblIdProtocolo());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
      $objRelProtocoloProtocoloDTO->setNumMaxRegistrosRetorno(1);

      if ($objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO) == null) {
        $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . ' não contém documentos.');
      }

      // tramitação unificada
      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setDblIdProcedimento($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
      $this->validarTramitacaoUnificada($objProcedimentoDTO, $objInfraException);

      //processo atual não pode ter processos anexados
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);

      $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);
      if (count($arrObjRelProtocoloProtocoloDTO) > 0) {
        $objInfraException->lancarValidacao('Os processos abaixo estão anexados ao processo ' . $objProtocoloDTODestino->getStrProtocoloFormatado() . ':' . "\\n" . implode("\\n",
            InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO, 'ProtocoloFormatadoProtocolo2')));
      }

      //muda estado do protocolo
      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setStrStaEstado(ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO);
      $objProtocoloDTO->setStrStaNivelAcessoOriginal($objProtocoloDTODestino->getStrStaNivelAcessoGlobal());
      $objProtocoloDTO->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
      $objProtocoloRN->alterarRN0203($objProtocoloDTO);

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProcedimento($objProtocoloDTOAtual->getDblIdProtocolo());
      $numSequencia = $objProtocoloRN->obterSequencia($objProtocoloDTO);

      //Criar associação entre os processos
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo(null);
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProtocoloDTOAtual->getDblIdProtocolo());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProtocoloDTODestino->getDblIdProtocolo());
      $objRelProtocoloProtocoloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objRelProtocoloProtocoloDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
      $objRelProtocoloProtocoloDTO->setNumSequencia($numSequencia);
      $objRelProtocoloProtocoloDTO->setDthAssociacao(InfraData::getStrDataHoraAtual());

      $objRelProtocoloProtocoloRN->cadastrarRN0839($objRelProtocoloProtocoloDTO);

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
      $objAtributoAndamentoDTO->setStrValor($objProtocoloDTODestino->getStrProtocoloFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTODestino->getDblIdProtocolo());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      //Gerar atividade de vinculação de procedimento
      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTOAtual->getDblIdProtocolo());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ANEXADO_PROCESSO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
      $objAtributoAndamentoDTO->setStrValor($objProtocoloDTOAtual->getStrProtocoloFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTOAtual->getDblIdProtocolo());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTODestino->getDblIdProtocolo());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ANEXADO_AO_PROCESSO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTODestino->getDblIdProtocolo());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_CONCLUSAO_AUTOMATICA_UNIDADE);
      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      $objMudarNivelAcessoDTO = new MudarNivelAcessoDTO();
      $objMudarNivelAcessoDTO->setStrStaOperacao(ProtocoloRN::$TMN_ANEXACAO);
      $objMudarNivelAcessoDTO->setDblIdProtocolo($objProtocoloDTOAtual->getDblIdProtocolo());
      $objMudarNivelAcessoDTO->setStrStaNivel(null);
      $objProtocoloRN->mudarNivelAcesso($objMudarNivelAcessoDTO);

      $objControleInternoDTO = new ControleInternoDTO();
      $objControleInternoDTO->setDblIdProcedimento($objProtocoloDTOAtual->getDblIdProtocolo());
      $objControleInternoDTO->setStrStaOperacao(ControleInternoRN::$TO_ANEXAR_PROCEDIMENTO);

      $objControleInternoRN = new ControleInternoRN();
      $objControleInternoRN->processar($objControleInternoDTO);

      if (count($SEI_MODULOS)) {
        $objProcedimentoAPIPrincipal = new ProcedimentoAPI();
        $objProcedimentoAPIPrincipal->setIdProcedimento($objProtocoloDTOAtual->getDblIdProtocolo());
        $objProcedimentoAPIPrincipal->setNumeroProtocolo($objProtocoloDTOAtual->getStrProtocoloFormatado());

        $objProcedimentoAPIAnexado = new ProcedimentoAPI();
        $objProcedimentoAPIAnexado->setIdProcedimento($objProtocoloDTODestino->getDblIdProtocolo());
        $objProcedimentoAPIAnexado->setNumeroProtocolo($objProtocoloDTODestino->getStrProtocoloFormatado());

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('anexarProcesso', $objProcedimentoAPIPrincipal, $objProcedimentoAPIAnexado);
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro anexando processo.', $e);
    }
  }

  public function desanexar(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloRecebidoDTO) {
    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $this->desanexarInterno($objRelProtocoloProtocoloRecebidoDTO);

    if (!$bolAcumulacaoPrevia) {
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }
  }

  protected function desanexarInternoControlado(RelProtocoloProtocoloDTO $parObjRelProtocoloProtocoloDTO) {
    try {
      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_desanexar', __METHOD__, $parObjRelProtocoloProtocoloDTO);


      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($parObjRelProtocoloProtocoloDTO->getStrMotivo())) {
        $objInfraException->lancarValidacao('Motivo não informado.');
      }

      $objAtividadeRN = new AtividadeRN();


      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo1();
      $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo2();
      $objRelProtocoloProtocoloDTO->retStrStaEstadoProtocolo2();
      $objRelProtocoloProtocoloDTO->retStrStaAssociacao();
      $objRelProtocoloProtocoloDTO->retNumIdUnidade();
      $objRelProtocoloProtocoloDTO->retStrSiglaUnidade();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);

      $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

      if ($objRelProtocoloProtocoloDTO == null) {
        $objInfraException->lancarValidacao('Relacionamento de anexação entre os protocolos não encontrado.');
      }

      /*
      if ($objRelProtocoloProtocoloDTO->getNumIdUnidade()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
        $objInfraException->lancarValidacao('Anexação foi realizada pela unidade '.$objRelProtocoloProtocoloDTO->getStrSiglaUnidade().'.');
      }
      */

      if ($objRelProtocoloProtocoloDTO->getStrStaEstadoProtocolo2() != ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
        $objInfraException->lancarValidacao('Processo ' . $objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo2() . ' não está anexado.');
      }

      $objProtocoloDTO1 = new ProtocoloDTO();
      $objProtocoloDTO1->retStrProtocoloFormatado();
      $objProtocoloDTO1->retStrStaEstado();
      $objProtocoloDTO1->retStrSinEliminado();
      $objProtocoloDTO1->setDblIdProtocolo($parObjRelProtocoloProtocoloDTO->getDblIdProtocolo1());

      $objProtocoloRN = new ProtocoloRN();
      $this->verificarEstadoProcedimento($objProtocoloRN->consultarRN0186($objProtocoloDTO1));

      $objRelProtocoloProtocoloDTO2 = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO2->setDblIdRelProtocoloProtocolo($objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo());
      $objRelProtocoloProtocoloDTO2->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_DESANEXADO);
      $objRelProtocoloProtocoloRN->alterar($objRelProtocoloProtocoloDTO2);

      //Alterar estado do processo
      $objDTO = new ProtocoloDTO();
      $objDTO->setStrStaEstado(ProtocoloRN::$TE_NORMAL);
      $objDTO->setStrStaNivelAcessoOriginal(null);
      $objDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $objProtocoloRN->alterarRN0203($objDTO);

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
      $objAtributoAndamentoDTO->setStrValor($objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo2());
      $objAtributoAndamentoDTO->setStrIdOrigem($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('MOTIVO');
      $objAtributoAndamentoDTO->setStrValor($parObjRelProtocoloProtocoloDTO->getStrMotivo());
      $objAtributoAndamentoDTO->setStrIdOrigem(null);
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_DESANEXADO_PROCESSO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);


      //só reabre se o processo anexado passou pela unidade atual (pode ser um Administrador desanexando)
      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retNumIdAtividade();
      $objAtividadeDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(array(TarefaRN::$TI_GERACAO_PROCEDIMENTO, TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE), InfraDTO::$OPER_IN);
      $objAtividadeDTO->setStrStaNivelAcessoGlobalProtocolo(array(ProtocoloRN::$NA_PUBLICO, ProtocoloRN::$NA_RESTRITO), InfraDTO::$OPER_IN);
      $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

      if ($objAtividadeRN->consultarRN0033($objAtividadeDTO) != null) {
        $objReabrirProcessoDTO = new ReabrirProcessoDTO();
        $objReabrirProcessoDTO->setDblIdProcedimento($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
        $objReabrirProcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objReabrirProcessoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $this->reabrirRN0966($objReabrirProcessoDTO);
      }

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
      $objAtributoAndamentoDTO->setStrValor($objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo1());
      $objAtributoAndamentoDTO->setStrIdOrigem($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('MOTIVO');
      $objAtributoAndamentoDTO->setStrValor($parObjRelProtocoloProtocoloDTO->getStrMotivo());
      $objAtributoAndamentoDTO->setStrIdOrigem($objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_DESANEXADO_DO_PROCESSO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      $objMudarNivelAcessoDTO = new MudarNivelAcessoDTO();
      $objMudarNivelAcessoDTO->setStrStaOperacao(ProtocoloRN::$TMN_DESANEXACAO);
      $objMudarNivelAcessoDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $objMudarNivelAcessoDTO->setStrStaNivel(null);
      $objProtocoloRN->mudarNivelAcesso($objMudarNivelAcessoDTO);

      $objMudarNivelAcessoDTO = new MudarNivelAcessoDTO();
      $objMudarNivelAcessoDTO->setStrStaOperacao(ProtocoloRN::$TMN_DESANEXACAO);
      $objMudarNivelAcessoDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $objMudarNivelAcessoDTO->setStrStaNivel(null);
      $objProtocoloRN->mudarNivelAcesso($objMudarNivelAcessoDTO);

      $objControleInternoRN = new ControleInternoRN();

      $objControleInternoDTO = new ControleInternoDTO();
      $objControleInternoDTO->setDblIdProcedimento($parObjRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $objControleInternoDTO->setStrStaOperacao(ControleInternoRN::$TO_DESANEXAR_PROCEDIMENTO);
      $objControleInternoRN->processar($objControleInternoDTO);

      $objControleInternoDTO = new ControleInternoDTO();
      $objControleInternoDTO->setDblIdProcedimento($parObjRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $objControleInternoDTO->setStrStaOperacao(ControleInternoRN::$TO_DESANEXAR_PROCEDIMENTO);
      $objControleInternoRN->processar($objControleInternoDTO);

      if (count($SEI_MODULOS)) {
        $objProcedimentoAPIPrincipal = new ProcedimentoAPI();
        $objProcedimentoAPIPrincipal->setIdProcedimento($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
        $objProcedimentoAPIPrincipal->setNumeroProtocolo($objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo1());

        $objProcedimentoAPIAnexado = new ProcedimentoAPI();
        $objProcedimentoAPIAnexado->setIdProcedimento($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
        $objProcedimentoAPIAnexado->setNumeroProtocolo($objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo2());

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('desanexarProcesso', $objProcedimentoAPIPrincipal, $objProcedimentoAPIAnexado);
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro desanexando processo.', $e);
    }
  }

  protected function listarAnexadosConectado(ProcedimentoDTO $parObjProcedimentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_listar_anexados', __METHOD__, $parObjProcedimentoDTO);

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();

      $objRelProtocoloProtocoloDTO->retStrSiglaUsuario();
      $objRelProtocoloProtocoloDTO->retStrNomeUsuario();
      $objRelProtocoloProtocoloDTO->retDthAssociacao();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->retNumIdUnidade();
      $objRelProtocoloProtocoloDTO->retStrSiglaUnidade();
      $objRelProtocoloProtocoloDTO->retStrDescricaoUnidade();
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjProcedimentoDTO->getDblIdProcedimento());

      $objRelProtocoloProtocoloDTO->setOrdDthAssociacao(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

      $ret = array();

      if (count($arrObjRelProtocoloProtocoloDTO) > 0) {
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->retDblIdProcedimento();
        $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
        $objProcedimentoDTO->retNumIdTipoProcedimento();
        $objProcedimentoDTO->retStrNomeTipoProcedimento();
        $objProcedimentoDTO->retStrSinAberto();

        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS_EXCETO_SIGILOSOS_SEM_ACESSO);
        $objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO, 'IdProtocolo2'));

        $objProtocoloRN = new ProtocoloRN();
        $arr = InfraArray::converterArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO), 'IdProtocolo');

        if (count($arr)) {
          $objProcedimentoDTO->setDblIdProcedimento($arr, InfraDTO::$OPER_IN);
          $arrObjProcedimentoDTO = $this->listarRN0278($objProcedimentoDTO);

          foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
            foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
              if ($objProcedimentoDTO->getDblIdProcedimento() == $objRelProtocoloProtocoloDTO->getDblIdProtocolo2()) {
                $objRelProtocoloProtocoloDTO->setObjProtocoloDTO2($objProcedimentoDTO);
                $ret[] = $objRelProtocoloProtocoloDTO;
                break;
              }
            }
          }
        }
      }

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando processos anexados.', $e);
    }
  }

  protected function bloquearControlado($arrObjProcedimentoDTO) {
    try {
      global $SEI_MODULOS;

      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_bloquear', __METHOD__, $arrObjProcedimentoDTO);

      $objInfraException = new InfraException();

      if (count($arrObjProcedimentoDTO) == 0) {
        throw new InfraException('Nenhum processo informado para bloqueio.');
      }

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retDblIdProtocolo();
      $objProtocoloDTO->retStrStaProtocolo();
      $objProtocoloDTO->retStrStaEstado();
      $objProtocoloDTO->retStrSinEliminado();
      $objProtocoloDTO->retStrStaNivelAcessoGlobal();
      $objProtocoloDTO->retStrProtocoloFormatado();
      $objProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento'), InfraDTO::$OPER_IN);

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarRN0668($objProtocoloDTO), 'IdProtocolo');

      $objRetornoProgramadoRN = new RetornoProgramadoRN();
      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        if (!isset($arrObjProtocoloDTO[$objProcedimentoDTO->getDblIdProcedimento()])) {
          throw new InfraException('Protocolo [' . $objProcedimentoDTO->getDblIdProcedimento() . '] não encontrado.');
        }

        $objProtocoloDTO = $arrObjProtocoloDTO[$objProcedimentoDTO->getDblIdProcedimento()];

        if ($objProtocoloDTO->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {
          $objInfraException->lancarValidacao('Protocolo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não é um processo.');
        }

        if ($objProtocoloDTO->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
          $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não pode estar anexado.');
        }

        if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {
          $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não pode ser sigiloso.');
        }

        $this->verificarEstadoProcedimento($objProtocoloDTO);

        $objRetornoProgramadoDTO = new RetornoProgramadoDTO();
        $objRetornoProgramadoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
        $objRetornoProgramadoRN->validarExistencia($objRetornoProgramadoDTO, $objInfraException);
        $objInfraException->lancarValidacoes();

        $this->validarTramitacaoUnificada($objProcedimentoDTO, $objInfraException);

        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo2();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProtocoloDTO->getDblIdProtocolo());
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_SOBRESTADO);

        $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);
        if (count($arrObjRelProtocoloProtocoloDTO) > 0) {
          $objInfraException->lancarValidacao('Os processos abaixo estão sobrestados com vinculação ao processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ':' . "\\n" . implode("\\n",
              InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO, 'ProtocoloFormatadoProtocolo2')));
        }
      }

      $objAtividadeRN = new AtividadeRN();

      $arrObjAtividadeDTO = array();

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setStrStaEstado(ProtocoloRN::$TE_PROCEDIMENTO_BLOQUEADO);
        $objProtocoloDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objProtocoloRN->alterarRN0203($objProtocoloDTO);

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_BLOQUEADO);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO(array());
        $arrObjAtividadeDTO[] = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
      }

      $arrObjProcedimentoAPI = array();
      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
        $objProcedimentoAPI->setNumeroProtocolo($arrObjProtocoloDTO[$objProcedimentoDTO->getDblIdProcedimento()]->getStrProtocoloFormatado());
        $arrObjProcedimentoAPI[] = $objProcedimentoAPI;
      }

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('bloquearProcesso', $arrObjProcedimentoAPI);
      }

      return $arrObjAtividadeDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro bloqueando processo.', $e);
    }
  }

  protected function desbloquearControlado($arrObjProcedimentoDTO) {
    try {
      global $SEI_MODULOS;

      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_desbloquear', __METHOD__, $arrObjProcedimentoDTO);

      $objInfraException = new InfraException();

      if (count($arrObjProcedimentoDTO) == 0) {
        throw new InfraException('Nenhum processo informado para desbloqueio.');
      }

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retDblIdProtocolo();
      $objProtocoloDTO->retStrStaProtocolo();
      $objProtocoloDTO->retStrStaEstado();
      $objProtocoloDTO->retStrStaNivelAcessoGlobal();
      $objProtocoloDTO->retStrProtocoloFormatado();
      $objProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento'), InfraDTO::$OPER_IN);

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarRN0668($objProtocoloDTO), 'IdProtocolo');


      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        if (!isset($arrObjProtocoloDTO[$objProcedimentoDTO->getDblIdProcedimento()])) {
          throw new InfraException('Protocolo [' . $objProcedimentoDTO->getDblIdProcedimento() . '] não encontrado.');
        }

        $objProtocoloDTO = $arrObjProtocoloDTO[$objProcedimentoDTO->getDblIdProcedimento()];

        if ($objProtocoloDTO->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {
          $objInfraException->lancarValidacao('Protocolo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não é um processo.');
        }

        if ($objProtocoloDTO->getStrStaEstado() != ProtocoloRN::$TE_PROCEDIMENTO_BLOQUEADO) {
          $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não está bloqueado.');
        }

        if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {
          $objInfraException->lancarValidacao('Processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não pode ser sigiloso.');
        }
      }

      $objAtividadeRN = new AtividadeRN();

      $arrObjAtividadeDTO = array();

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setStrStaEstado(ProtocoloRN::$TE_NORMAL);
        $objProtocoloDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objProtocoloRN->alterarRN0203($objProtocoloDTO);

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_DESBLOQUEADO);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO(array());
        $arrObjAtividadeDTO[] = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
      }

      $arrObjProcedimentoAPI = array();
      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
        $objProcedimentoAPI->setNumeroProtocolo($arrObjProtocoloDTO[$objProcedimentoDTO->getDblIdProcedimento()]->getStrProtocoloFormatado());
        $arrObjProcedimentoAPI[] = $objProcedimentoAPI;
      }

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('desbloquearProcesso', $arrObjProcedimentoAPI);
      }

      return $arrObjAtividadeDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro desbloqueando processo.', $e);
    }
  }

  protected function consultarHistoricoRN1025Conectado(ProcedimentoHistoricoDTO $parObjProcedimentoHistoricoDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_consultar_historico', __METHOD__, $parObjProcedimentoHistoricoDTO);

      //Regras de Negocio
      if (!$parObjProcedimentoHistoricoDTO->isSetStrSinGerarLinksHistorico()) {
        $parObjProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('S');
      }

      if (!$parObjProcedimentoHistoricoDTO->isSetStrSinRetornarAtributos()) {
        $parObjProcedimentoHistoricoDTO->setStrSinRetornarAtributos('N');
      }


      $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
      $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
      $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
      $objPesquisaProtocoloDTO->setDblIdProtocolo($parObjProcedimentoHistoricoDTO->getDblIdProcedimento());

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

      if (count($arrObjProtocoloDTO) == 0) {
        throw new InfraException('Processo não encontrado.', null, null, false);
      }

      $objProtocoloDTO = $arrObjProtocoloDTO[0];

      if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO && $objProtocoloDTO->getNumCodigoAcesso() < 0 && $parObjProcedimentoHistoricoDTO->getStrStaHistorico() != ProcedimentoRN::$TH_EXTERNO) {
        throw new InfraException('Processo não encontrado para exibição do histórico.');
      }

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setDblIdProcedimento($objProtocoloDTO->getDblIdProtocolo());
      $objProcedimentoDTO->setNumIdUnidadeGeradoraProtocolo($objProtocoloDTO->getNumIdUnidadeGeradora());
      $objProcedimentoDTO->setStrProtocoloProcedimentoFormatado($objProtocoloDTO->getStrProtocoloFormatado());
      $objProcedimentoDTO->setStrNomeTipoProcedimento($objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento());
      $objProcedimentoDTO->setDtaGeracaoProtocolo($objProtocoloDTO->getDtaGeracao());
      $objProcedimentoDTO->setStrSiglaUnidadeGeradoraProtocolo($objProtocoloDTO->getStrSiglaUnidadeGeradora());
      $objProcedimentoDTO->setStrStaNivelAcessoGlobalProtocolo($objProtocoloDTO->getStrStaNivelAcessoGlobal());

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retNumIdAtividade();
      $objAtividadeDTO->retDblIdProtocolo();
      $objAtividadeDTO->retNumIdUnidade();
      $objAtividadeDTO->retNumIdUsuario();
      $objAtividadeDTO->retNumIdOrgaoUnidade();
      //	$objAtividadeDTO->retStrSiglaUnidade();
      //	$objAtividadeDTO->retStrDescricaoUnidade();
      $objAtividadeDTO->retNumIdUnidadeOrigem();
      $objAtividadeDTO->retDthAbertura();
      $objAtividadeDTO->retDthConclusao();
      $objAtividadeDTO->retNumIdTarefa();
      $objAtividadeDTO->retStrNomeTarefa();
      $objAtividadeDTO->retStrIdTarefaModuloTarefa();
      $objAtividadeDTO->retNumIdUsuarioOrigem();
      $objAtividadeDTO->retStrSiglaUnidadeOrigem();
      $objAtividadeDTO->retStrSiglaUsuarioOrigem();
      $objAtividadeDTO->retStrNomeUsuarioOrigem();
      $objAtividadeDTO->retStrSiglaUsuarioAtribuicao();
      $objAtividadeDTO->retStrNomeUsuarioAtribuicao();
      $objAtividadeDTO->retStrSiglaUsuarioConclusao();
      $objAtividadeDTO->retStrNomeUsuarioConclusao();
      $objAtividadeDTO->retDtaPrazo();
      $objAtividadeDTO->retStrStaProtocoloProtocolo();
      $objAtividadeDTO->retStrSinInicial();

      if ($parObjProcedimentoHistoricoDTO->getStrStaHistorico() == ProcedimentoRN::$TH_RESUMIDO || $parObjProcedimentoHistoricoDTO->getStrStaHistorico() == ProcedimentoRN::$TH_EXTERNO) {
        $objAtividadeDTO->adicionarCriterio(array('IdTarefa', 'SinHistoricoResumidoTarefa'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(null, 'S'), InfraDTO::$OPER_LOGICO_OR);
      } else {
        if ($parObjProcedimentoHistoricoDTO->getStrStaHistorico() == ProcedimentoRN::$TH_PARCIAL) {
          $objAtividadeDTO->adicionarCriterio(array('IdTarefa', 'SinHistoricoCompletoTarefa'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(null, 'S'), InfraDTO::$OPER_LOGICO_OR);
        } else {
          if ($parObjProcedimentoHistoricoDTO->getStrStaHistorico() == ProcedimentoRN::$TH_CONSULTA_PROCESSUAL) {
            $objAtividadeDTO->adicionarCriterio(array('IdTarefa', 'SinConsultaProcessualTarefa'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(null, 'S'), InfraDTO::$OPER_LOGICO_OR);
          } else {
            if ($parObjProcedimentoHistoricoDTO->getStrStaHistorico() == ProcedimentoRN::$TH_PERSONALIZADO) {
              if (!$parObjProcedimentoHistoricoDTO->isSetDblIdProcedimentoAnexado() && !$parObjProcedimentoHistoricoDTO->isSetDblIdDocumento()) {
                if ($parObjProcedimentoHistoricoDTO->isSetNumIdAtividade()) {
                  if (!is_array($parObjProcedimentoHistoricoDTO->getNumIdAtividade())) {
                    $objAtividadeDTO->setNumIdAtividade($parObjProcedimentoHistoricoDTO->getNumIdAtividade());
                  } else {
                    $objAtividadeDTO->setNumIdAtividade($parObjProcedimentoHistoricoDTO->getNumIdAtividade(), InfraDTO::$OPER_IN);
                  }
                }

                if ($parObjProcedimentoHistoricoDTO->isSetNumIdTarefa()) {
                  if (!is_array($parObjProcedimentoHistoricoDTO->getNumIdTarefa())) {
                    $objAtividadeDTO->setNumIdTarefa($parObjProcedimentoHistoricoDTO->getNumIdTarefa());
                  } else {
                    $objAtividadeDTO->setNumIdTarefa($parObjProcedimentoHistoricoDTO->getNumIdTarefa(), InfraDTO::$OPER_IN);
                  }
                }

                if ($parObjProcedimentoHistoricoDTO->isSetStrIdTarefaModulo()) {
                  if (!is_array($parObjProcedimentoHistoricoDTO->getStrIdTarefaModulo())) {
                    $objAtividadeDTO->setStrIdTarefaModuloTarefa($parObjProcedimentoHistoricoDTO->getStrIdTarefaModulo());
                  } else {
                    $objAtividadeDTO->setStrIdTarefaModuloTarefa($parObjProcedimentoHistoricoDTO->getStrIdTarefaModulo(), InfraDTO::$OPER_IN);
                  }
                }
              } else {
                $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                $objAtributoAndamentoDTO->retNumIdAtividade();
                $objAtributoAndamentoDTO->setDblIdProtocoloAtividade($objProcedimentoDTO->getDblIdProcedimento());

                if ($parObjProcedimentoHistoricoDTO->isSetNumIdAtividade()) {
                  if (!is_array($parObjProcedimentoHistoricoDTO->getNumIdAtividade())) {
                    $objAtributoAndamentoDTO->setNumIdAtividade($parObjProcedimentoHistoricoDTO->getNumIdAtividade());
                  } else {
                    $objAtributoAndamentoDTO->setNumIdAtividade($parObjProcedimentoHistoricoDTO->getNumIdAtividade(), InfraDTO::$OPER_IN);
                  }
                }

                if ($parObjProcedimentoHistoricoDTO->isSetNumIdTarefa()) {
                  if (!is_array($parObjProcedimentoHistoricoDTO->getNumIdTarefa())) {
                    $objAtributoAndamentoDTO->setNumIdTarefaAtividade($parObjProcedimentoHistoricoDTO->getNumIdTarefa());
                  } else {
                    $objAtributoAndamentoDTO->setNumIdTarefaAtividade($parObjProcedimentoHistoricoDTO->getNumIdTarefa(), InfraDTO::$OPER_IN);
                  }
                }

                if ($parObjProcedimentoHistoricoDTO->isSetStrIdTarefaModulo()) {
                  if (!is_array($parObjProcedimentoHistoricoDTO->getStrIdTarefaModulo())) {
                    $objAtividadeDTO->setStrIdTarefaModuloTarefa($parObjProcedimentoHistoricoDTO->getStrIdTarefaModulo());
                  } else {
                    $objAtividadeDTO->setStrIdTarefaModuloTarefa($parObjProcedimentoHistoricoDTO->getStrIdTarefaModulo(), InfraDTO::$OPER_IN);
                  }
                }

                if ($parObjProcedimentoHistoricoDTO->isSetDblIdProcedimentoAnexado()) {
                  $objAtributoAndamentoDTO->setStrNome('PROCESSO');
                  $objAtributoAndamentoDTO->setStrIdOrigem($parObjProcedimentoHistoricoDTO->getDblIdProcedimentoAnexado());
                } else {
                  $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
                  $objAtributoAndamentoDTO->setStrIdOrigem($parObjProcedimentoHistoricoDTO->getDblIdDocumento());
                }

                $objAtributoAndamentoRN = new AtributoAndamentoRN();
                $arrObjAtributoAndamentoDTO = $objAtributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);

                if (count($arrObjAtributoAndamentoDTO)) {
                  $objAtividadeDTO->setNumIdAtividade(InfraArray::converterArrInfraDTO($arrObjAtributoAndamentoDTO, 'IdAtividade'), InfraDTO::$OPER_IN);
                } else {
                  $objAtividadeDTO->setNumIdAtividade(null);
                }
              }
            }
          }
        }
      }

      $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());

      $objAtividadeDTO->setOrdNumIdAtividade(InfraDTO::$TIPO_ORDENACAO_DESC);

      //paginação
      $objAtividadeDTO->setNumMaxRegistrosRetorno($parObjProcedimentoHistoricoDTO->getNumMaxRegistrosRetorno());
      $objAtividadeDTO->setNumPaginaAtual($parObjProcedimentoHistoricoDTO->getNumPaginaAtual());

      $objAtividadeRN = new AtividadeRN();
      $arrObjAtividadeDTO_Listar = $objAtividadeRN->listarRN0036($objAtividadeDTO);
      $arrObjAtividadeDTO = InfraArray::indexarArrInfraDTO($arrObjAtividadeDTO_Listar, 'IdAtividade');

      //paginação
      $parObjProcedimentoHistoricoDTO->setNumTotalRegistros($objAtividadeDTO->getNumTotalRegistros());
      $parObjProcedimentoHistoricoDTO->setNumRegistrosPaginaAtual($objAtividadeDTO->getNumRegistrosPaginaAtual());


      if (count($arrObjAtividadeDTO)) {
        $objHistoricoRN = new HistoricoRN();
        $objHistoricoRN->aplicar('Unidade', $arrObjAtividadeDTO_Listar, 'Abertura', 'IdUnidade', 'SiglaUnidade', 'DescricaoUnidade');
        $objHistoricoRN->aplicar('Orgao', $arrObjAtividadeDTO_Listar, 'Abertura', 'IdOrgaoUnidade', 'SiglaOrgao', 'DescricaoOrgao');

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->retTodos(true);
        $objAtributoAndamentoDTO->setNumIdAtividade(InfraArray::converterArrInfraDTO($arrObjAtividadeDTO, 'IdAtividade'), InfraDTO::$OPER_IN);

        $objAtributoAndamentoDTO->setOrdNumIdAtributoAndamento(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objAtributoAndamentoRN = new AtributoAndamentoRN();
        $arrObjAtributoAndamentoDTO = $objAtributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);

        if (count($arrObjAtributoAndamentoDTO) > 0) {
          if ($parObjProcedimentoHistoricoDTO->getStrSinGerarLinksHistorico() == 'N') {
            $bolAcaoDownload = false;
            $bolAcaoProcedimentoTrabalhar = false;
            $bolAcaoRelBlocoProtocoloListar = false;
            $bolAcaoDocumentoVisualizar = false;
            $bolAcaoLocalizadorProtocolosListar = false;
          } else {
            if ($objProtocoloDTO->getNumCodigoAcesso() < 0) {
              $bolAcaoDownload = false;
              $bolAcaoProcedimentoTrabalhar = false;
              $bolAcaoDocumentoVisualizar = false;
              $bolAcaoRelBlocoProtocoloListar = false;

              //monta link de arquivo mesmo se não tem acesso
              $bolAcaoLocalizadorProtocolosListar = SessaoSEI::getInstance()->verificarPermissao('localizador_protocolos_listar');
            } else {
              $bolAcaoDownload = SessaoSEI::getInstance()->verificarPermissao('documento_download_anexo');
              $bolAcaoProcedimentoTrabalhar = SessaoSEI::getInstance()->verificarPermissao('procedimento_trabalhar');
              $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');
              $bolAcaoRelBlocoProtocoloListar = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_listar');
              $bolAcaoLocalizadorProtocolosListar = SessaoSEI::getInstance()->verificarPermissao('localizador_protocolos_listar');
            }
          }

          $arrObjAtributoAndamentoDTOPorNome = InfraArray::indexarArrInfraDTO($arrObjAtributoAndamentoDTO, 'Nome', true);

          if (isset($arrObjAtributoAndamentoDTOPorNome['PROCESSO'])) {
            $dto = new ProcedimentoDTO();
            $dto->retDblIdProcedimento();
            $dto->retStrNomeTipoProcedimento();
            $dto->setDblIdProcedimento(InfraArray::converterArrInfraDTO($arrObjAtributoAndamentoDTOPorNome['PROCESSO'], 'IdOrigem'), InfraDTO::$OPER_IN);

            $arrObjProcedimentoDTO = InfraArray::indexarArrInfraDTO($this->listarRN0278($dto), 'IdProcedimento');
          }

          if (isset($arrObjAtributoAndamentoDTOPorNome['DOCUMENTO'])) {
            $dto = new DocumentoDTO();
            $dto->retDblIdDocumento();
            $dto->retStrProtocoloDocumentoFormatado();
            $dto->retStrNomeSerie();
            $dto->retStrNumero();
            $dto->retStrStaProtocoloProtocolo();
            $dto->setDblIdDocumento(InfraArray::converterArrInfraDTO($arrObjAtributoAndamentoDTOPorNome['DOCUMENTO'], 'IdOrigem'), InfraDTO::$OPER_IN);

            $objDocumentoRN = new DocumentoRN();
            $arrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarRN0008($dto), 'IdDocumento');
          }

          if (isset($arrObjAtributoAndamentoDTOPorNome['BLOCO'])) {
            $objBlocoDTO = new BlocoDTO();
            $objBlocoDTO->retNumIdBloco();
            $objBlocoDTO->setNumIdBloco(InfraArray::converterArrInfraDTO($arrObjAtributoAndamentoDTOPorNome['BLOCO'], 'IdOrigem'), InfraDTO::$OPER_IN);
            $objBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

            $objBlocoRN = new BlocoRN();
            $arrIdBloco = InfraArray::converterArrInfraDTO($objBlocoRN->listarRN1277($objBlocoDTO), 'IdBloco');

            $objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
            $objRelBlocoUnidadeDTO->retNumIdBloco();
            $objRelBlocoUnidadeDTO->setStrStaEstadoBloco(BlocoRN::$TE_DISPONIBILIZADO);
            $objRelBlocoUnidadeDTO->setNumIdBloco(InfraArray::converterArrInfraDTO($arrObjAtributoAndamentoDTOPorNome['BLOCO'], 'IdOrigem'), InfraDTO::$OPER_IN);
            $objRelBlocoUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

            $objRelBlocoUnidadeRN = new RelBlocoUnidadeRN();
            $arrIdBloco = array_unique(array_merge($arrIdBloco, InfraArray::converterArrInfraDTO($objRelBlocoUnidadeRN->listarRN1304($objRelBlocoUnidadeDTO), 'IdBloco')));
          }

          if ($bolAcaoLocalizadorProtocolosListar && isset($arrObjAtributoAndamentoDTOPorNome['LOCALIZADOR'])) {
            $dto = new LocalizadorDTO();
            $dto->retNumIdLocalizador();
            $dto->retNumIdUnidade();
            $dto->setNumIdLocalizador(InfraArray::converterArrInfraDTO($arrObjAtributoAndamentoDTOPorNome['LOCALIZADOR'], 'IdOrigem'), InfraDTO::$OPER_IN);

            $objLocalizadorRN = new LocalizadorRN();
            $arrObjLocalizadorDTO = InfraArray::indexarArrInfraDTO($objLocalizadorRN->listarRN0622($dto), 'IdLocalizador');
          } else {
            $arrObjLocalizadorDTO = array();
          }

          $arrObjNivelAcessoDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarNiveisAcessoRN0878(), 'StaNivel');
          foreach ($arrObjNivelAcessoDTO as $objNivelAcessoDTO) {
            $objNivelAcessoDTO->setStrDescricao(InfraString::transformarCaixaBaixa($objNivelAcessoDTO->getStrDescricao()));
          }

          $arrObjGrauSigiloDTO = InfraArray::indexarArrInfraDTO(ProtocoloRN::listarGrausSigiloso(), 'StaGrau');
          foreach ($arrObjGrauSigiloDTO as $objGrauSigiloDTO) {
            $objGrauSigiloDTO->setStrDescricao(InfraString::transformarCaixaBaixa($objGrauSigiloDTO->getStrDescricao()));
          }


          $objTipoConferenciaDTO = new TipoConferenciaDTO();
          $objTipoConferenciaDTO->setBolExclusaoLogica(false);
          $objTipoConferenciaDTO->retNumIdTipoConferencia();
          $objTipoConferenciaDTO->retStrDescricao();

          $objTipoConferenciaRN = new TipoConferenciaRN();
          $arrObjTipoConferenciaDTO = InfraArray::indexarArrInfraDTO($objTipoConferenciaRN->listar($objTipoConferenciaDTO), 'IdTipoConferencia');
          foreach ($arrObjTipoConferenciaDTO as $objTipoConferenciaDTO) {
            $objTipoConferenciaDTO->setStrDescricao(PaginaSEI::tratarHTML(InfraString::transformarCaixaBaixa($objTipoConferenciaDTO->getStrDescricao())));
          }

          $objHipoteseLegalDTO = new HipoteseLegalDTO();
          $objHipoteseLegalDTO->setBolExclusaoLogica(false);
          $objHipoteseLegalDTO->retNumIdHipoteseLegal();
          $objHipoteseLegalDTO->retStrNome();
          $objHipoteseLegalDTO->retStrBaseLegal();

          $objHipoteseLegalRN = new HipoteseLegalRN();
          $arrObjHipoteseLegalDTO = InfraArray::indexarArrInfraDTO($objHipoteseLegalRN->listar($objHipoteseLegalDTO), 'IdHipoteseLegal');
          foreach ($arrObjHipoteseLegalDTO as $objHipoteseLegalDTO) {
            $objHipoteseLegalDTO->setStrNome(PaginaSEI::tratarHTML($objHipoteseLegalDTO->getStrNome()));
            $objHipoteseLegalDTO->setStrBaseLegal(PaginaSEI::tratarHTML($objHipoteseLegalDTO->getStrBaseLegal()));
          }

          foreach ($arrObjAtributoAndamentoDTO as $objAtributoAndamentoDTO) {
            $objAtividadeDTO = $arrObjAtividadeDTO[$objAtributoAndamentoDTO->getNumIdAtividade()];

            $strNomeTarefa = $objAtividadeDTO->getStrNomeTarefa();

            $objAtributoAndamentoDTO->setStrValor(PaginaSEI::tratarHTML($objAtributoAndamentoDTO->getStrValor()));

            switch ($objAtributoAndamentoDTO->getStrNome()) {
              case 'DOCUMENTO':
                $this->substitutirAtributoMultiploDocumentos($objAtributoAndamentoDTO, $arrObjAtributoAndamentoDTOPorNome['DOCUMENTO'], $arrObjDocumentoDTO, $bolAcaoDocumentoVisualizar, $strNomeTarefa);
                break;

              case 'NIVEL_ACESSO':
                $strNomeTarefa = str_replace('@NIVEL_ACESSO@', $arrObjNivelAcessoDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrDescricao(), $strNomeTarefa);
                break;

              case 'GRAU_SIGILO':
                if ($objAtributoAndamentoDTO->getStrIdOrigem() == null) {
                  $strNomeTarefa = str_replace('@GRAU_SIGILO@', '"não informado"', $strNomeTarefa);
                } else {
                  if ($objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_GERACAO_PROCEDIMENTO || $objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_GERACAO_DOCUMENTO || $objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_RECEBIMENTO_DOCUMENTO || $objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_ALTERACAO_NIVEL_ACESSO_GLOBAL) {
                    $strNomeTarefa = str_replace('@GRAU_SIGILO@', ' (' . $arrObjGrauSigiloDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrDescricao() . ')', $strNomeTarefa);
                  } else {
                    $strNomeTarefa = str_replace('@GRAU_SIGILO@', ' ' . $arrObjGrauSigiloDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrDescricao(), $strNomeTarefa);
                  }
                }
                break;

              case 'HIPOTESE_LEGAL':
                if ($objAtributoAndamentoDTO->getStrIdOrigem() == null) {
                  $strNomeTarefa = str_replace('@HIPOTESE_LEGAL@', '"não informada"', $strNomeTarefa);
                } else {
                  if ($objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_ALTERACAO_NIVEL_ACESSO_PROCESSO || $objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_ALTERACAO_GRAU_SIGILO_PROCESSO || $objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_ALTERACAO_HIPOTESE_LEGAL_PROCESSO || $objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_ALTERACAO_NIVEL_ACESSO_DOCUMENTO || $objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_ALTERACAO_GRAU_SIGILO_DOCUMENTO || $objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_ALTERACAO_HIPOTESE_LEGAL_DOCUMENTO) {
                    $strNomeTarefa = str_replace('@HIPOTESE_LEGAL@',
                      HipoteseLegalINT::formatarHipoteseLegal($arrObjHipoteseLegalDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrNome(), $arrObjHipoteseLegalDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrBaseLegal()),
                      $strNomeTarefa);
                  } else {
                    $strNomeTarefa = str_replace('@HIPOTESE_LEGAL@',
                      ', ' . HipoteseLegalINT::formatarHipoteseLegal($arrObjHipoteseLegalDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrNome(), $arrObjHipoteseLegalDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrBaseLegal()),
                      $strNomeTarefa);
                  }
                }
                break;

              case 'VISUALIZACAO':
                if ($objAtributoAndamentoDTO->getStrIdOrigem() == null || $objAtributoAndamentoDTO->getStrIdOrigem() == AcessoExternoRN::$TV_INTEGRAL) {
                  $strNomeTarefa = str_replace('@VISUALIZACAO@', ' Com visualização integral do processo.', $strNomeTarefa);
                } else {
                  if ($objAtributoAndamentoDTO->getStrIdOrigem() == AcessoExternoRN::$TV_PARCIAL) {
                    if ($objAtividadeDTO->getNumIdTarefa() == TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO) {
                      $strNomeTarefa = str_replace('@VISUALIZACAO@', ' Para disponibilização de documentos.', $strNomeTarefa);
                    } else {
                      $strNomeTarefa = str_replace('@VISUALIZACAO@', ' Com visualização parcial do processo.', $strNomeTarefa);
                    }
                  } else {
                    if ($objAtributoAndamentoDTO->getStrIdOrigem() == AcessoExternoRN::$TV_NENHUM) {
                      $strNomeTarefa = str_replace('@VISUALIZACAO@', ' Sem acesso ao processo.', $strNomeTarefa);
                    }
                  }
                }
                break;

              case 'VALIDADE':
                $strNomeTarefa = str_replace('@VALIDADE@', ' ' . $objAtributoAndamentoDTO->getStrValor(), $strNomeTarefa);
                break;

              case 'DATA_AUTUACAO':
                if ($objAtributoAndamentoDTO->getStrValor() != null) {
                  $strNomeTarefa = str_replace('@DATA_AUTUACAO@', ' (autuado em ' . $objAtributoAndamentoDTO->getStrValor() . ')', $strNomeTarefa);
                }
                break;

              case 'TIPO_CONFERENCIA':
                if ($objAtributoAndamentoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_ALTERACAO_TIPO_CONFERENCIA_DOCUMENTO) {
                  if ($objAtributoAndamentoDTO->getStrIdOrigem() == null) {
                    $strNomeTarefa = str_replace('@TIPO_CONFERENCIA@', '"não informado"', $strNomeTarefa);
                  } else {
                    $strNomeTarefa = str_replace('@TIPO_CONFERENCIA@', $arrObjTipoConferenciaDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrDescricao(), $strNomeTarefa);
                  }
                } else {
                  $strNomeTarefa = str_replace('@TIPO_CONFERENCIA@', ', conferido com ' . $arrObjTipoConferenciaDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrDescricao(), $strNomeTarefa);
                }
                break;

              case 'PROCESSO':
                $this->substituirAtributoProcessoHistorico($objAtributoAndamentoDTO, $arrObjProcedimentoDTO, $bolAcaoProcedimentoTrabalhar, $strNomeTarefa);
                break;

              case 'USUARIO':
                $this->substitutirAtributoMultiploUsuarios($objAtributoAndamentoDTO, $arrObjAtributoAndamentoDTOPorNome['USUARIO'], $strNomeTarefa);
                break;

              case 'UNIDADE_REMETENTE':
              case 'UNIDADE_DESTINATARIA':
              case 'ORGAO':
              case 'ORGAO_REMETENTE':
              case 'ORGAO_DESTINATARIO':
              case 'USUARIO_ANULACAO':
              case 'INTERESSADO':
                $arrValor = explode('¥', $objAtributoAndamentoDTO->getStrValor());
                $strSubstituicao = '<a href="javascript:void(0);" alt="' . $arrValor[1] . '" title="' . $arrValor[1] . '" class="ancoraSigla">' . $arrValor[0] . '</a>';
                $strNomeTarefa = str_replace('@' . $objAtributoAndamentoDTO->getStrNome() . '@', $strSubstituicao, $strNomeTarefa);
                break;

              case 'UNIDADE':
                $this->substitutirAtributoMultiploUnidades($objAtributoAndamentoDTO, $arrObjAtributoAndamentoDTOPorNome['UNIDADE'], $strNomeTarefa);
                break;

              case 'BLOCO':
                $this->substituirAtributoBlocoHistorico($objAtributoAndamentoDTO, $arrIdBloco, $bolAcaoRelBlocoProtocoloListar, $strNomeTarefa);
                break;

              case 'DATA_HORA':
                $strNomeTarefa = str_replace('@DATA_HORA@', substr($objAtributoAndamentoDTO->getStrValor(), 0, 16), $strNomeTarefa);
                break;

              case 'LOCALIZADOR':
                $this->substituirAtributoLocalizadorHistorico($objAtributoAndamentoDTO, $arrObjLocalizadorDTO, $bolAcaoLocalizadorProtocolosListar, $strNomeTarefa);
                break;

              case 'ANEXO':

                $strSubstituicao = $objAtributoAndamentoDTO->getStrValor();

                if ($bolAcaoDownload) {
                  $objAnexoDTO = new AnexoDTO();
                  $objAnexoDTO->retNumIdAnexo();
                  $objAnexoDTO->setNumIdAnexo($objAtributoAndamentoDTO->getStrIdOrigem());
                  $objAnexoDTO->setNumMaxRegistrosRetorno(1);

                  $objAnexoRN = new AnexoRN();
                  if ($objAnexoRN->consultarRN0736($objAnexoDTO) != null) {
                    $strSubstituicao = '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_download_anexo&id_anexo=' . $objAtributoAndamentoDTO->getStrIdOrigem()) . '" target="_blank" class="ancoraHistoricoProcesso">' . $objAtributoAndamentoDTO->getStrValor() . '</a>';
                  } else {
                    $strSubstituicao = '<a href="javascript:void(0);" onclick="alert(\'Este anexo foi excluído.\');"  class="ancoraHistoricoProcesso">' . $objAtributoAndamentoDTO->getStrValor() . '</a>';
                  }
                }
                $strNomeTarefa = str_replace('@ANEXO@', $strSubstituicao, $strNomeTarefa);
                break;

              case 'MOTIVO':
                if (trim($objAtributoAndamentoDTO->getStrValor()) == '') {
                  $strNomeTarefa = str_replace("\n" . '@' . $objAtributoAndamentoDTO->getStrNome() . '@', '', $strNomeTarefa);
                } else {
                  $strNomeTarefa = str_replace('@' . $objAtributoAndamentoDTO->getStrNome() . '@', $objAtributoAndamentoDTO->getStrValor(), $strNomeTarefa);
                }
                break;

              default:
                $strNomeTarefa = str_replace('@' . $objAtributoAndamentoDTO->getStrNome() . '@', $objAtributoAndamentoDTO->getStrValor(), $strNomeTarefa);
            }

            if ($parObjProcedimentoHistoricoDTO->getStrStaHistorico() == ProcedimentoRN::$TH_AUDITORIA && $objAtributoAndamentoDTO->getStrNome() == 'USUARIO_EMULADOR') {
              $arrValor = explode('±', $objAtributoAndamentoDTO->getStrValor());
              $arrUsuario = explode('¥', $arrValor[0]);
              $arrOrgaoUsuario = explode('¥', $arrValor[1]);
              $strUsuario = '<a href="javascript:void(0);" alt="' . $arrUsuario[1] . '" title="' . $arrUsuario[1] . '" class="ancoraSigla">' . $arrUsuario[0] . '</a>';
              $strOrgaoUsuario = '<a href="javascript:void(0);" alt="' . $arrOrgaoUsuario[1] . '" title="' . $arrOrgaoUsuario[1] . '" class="ancoraSigla">' . $arrOrgaoUsuario[0] . '</a>';
              $strNomeTarefa .= ' (emulado por ' . $strUsuario . ' / ' . $strOrgaoUsuario . ')';
            }

            $objAtividadeDTO->setStrNomeTarefa(trim($strNomeTarefa));
          }
        }


        if ($parObjProcedimentoHistoricoDTO->getStrStaHistorico() == ProcedimentoRN::$TH_TOTAL) {
          foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
            if ($objAtividadeDTO->getDthConclusao() == null) {
              $objAtividadeDTO->setStrSinUltimaUnidadeHistorico('S');
            } else {
              $objAtividadeDTO->setStrSinUltimaUnidadeHistorico('N');
            }
          }
        } else {
          //buscar as unidades/usuarios que possuem andamento em aberto
          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDistinct(true);
          $objAtividadeDTO->retNumIdUnidade();
          $objAtividadeDTO->retNumIdUsuario();
          $objAtividadeDTO->setDthConclusao(null);
          $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());

          $objAtividadeRN = new AtividadeRN();
          $arrObjAtividadeDTOAbertas = $objAtividadeRN->listarRN0036($objAtividadeDTO);

          $arrNumIdUnidade = InfraArray::converterArrInfraDTO($arrObjAtividadeDTO_Listar, 'IdUnidade');
          $arrNumIdAtividade = InfraArray::converterArrInfraDTO($arrObjAtividadeDTO_Listar, 'IdAtividade');
          $arrNumIdUsuario = InfraArray::converterArrInfraDTO($arrObjAtividadeDTO_Listar, 'IdUsuario');
          foreach ($arrObjAtividadeDTOAbertas as $objAtividadeDTOAberta) {
            $numIdUnidade_AtividadeAberta = $objAtividadeDTOAberta->getNumIdUnidade();
            $numIdUsuario_AtividadeAberta = $objAtividadeDTOAberta->getNumIdUsuario();
            foreach ($arrNumIdAtividade as $key => $numIdAtividade) {
              if ($arrNumIdUnidade[$key] == $numIdUnidade_AtividadeAberta && ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO || $arrNumIdUsuario[$key] == $numIdUsuario_AtividadeAberta)) {
                $arrObjAtividadeDTO[$numIdAtividade]->setStrSinUltimaUnidadeHistorico('S');
                break;
              }
            }
          }

          foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
            if (!$objAtividadeDTO->isSetStrSinUltimaUnidadeHistorico()) {
              $objAtividadeDTO->setStrSinUltimaUnidadeHistorico('N');
            }
          }
        }

        if ($parObjProcedimentoHistoricoDTO->getStrSinRetornarAtributos() == 'S') {
          $arrObjAtributoAndamentoDTOPorAtividade = InfraArray::indexarArrInfraDTO($arrObjAtributoAndamentoDTO, 'IdAtividade', true);

          foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
            if (isset($arrObjAtributoAndamentoDTOPorAtividade[$objAtividadeDTO->getNumIdAtividade()])) {
              $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTOPorAtividade[$objAtividadeDTO->getNumIdAtividade()]);
            } else {
              $objAtividadeDTO->setArrObjAtributoAndamentoDTO(array());
            }
          }
        }
      }


      foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
        if (in_array($objAtividadeDTO->getNumIdTarefa(), array(
          TarefaRN::$TI_GERACAO_PROCEDIMENTO, TarefaRN::$TI_GERACAO_DOCUMENTO, TarefaRN::$TI_RECEBIMENTO_DOCUMENTO, TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO, TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO_CANCELADA, TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA, TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA_CANCELADA
        ))) {
          $objAtividadeDTO->setStrNomeTarefa(str_replace(array(
            '@NIVEL_ACESSO@', '@GRAU_SIGILO@', '@TIPO_CONFERENCIA@', '@DATA_AUTUACAO@', '@HIPOTESE_LEGAL@', '@VISUALIZACAO@', '@VALIDADE@'
          ), '', $objAtividadeDTO->getStrNomeTarefa()));
        }
      }

      $objProcedimentoDTO->setArrObjAtividadeDTO(array_values($arrObjAtividadeDTO));

      return $objProcedimentoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando histórico do processo.', $e);
    }
  }

  private function montarAtributoDocumentoHistorico(
    AtributoAndamentoDTO $objAtributoAndamentoDTO, $arrObjDocumentoDTO, $bolAcaoDocumentoVisualizar) {
    if (!isset($arrObjDocumentoDTO[$objAtributoAndamentoDTO->getStrIdOrigem()])) {
      $strSubstituicao = '<a href="javascript:void(0);" onclick="alert(\'Este documento foi excluído.\');" class="ancoraHistoricoProcesso">' . $objAtributoAndamentoDTO->getStrValor() . '</a>';
    } else {
      $objDocumentoDTO = $arrObjDocumentoDTO[$objAtributoAndamentoDTO->getStrIdOrigem()];
      $strIdentificacao = PaginaSEI::tratarHTML(trim($objDocumentoDTO->getStrNomeSerie() . ' ' . $objDocumentoDTO->getStrNumero()));
      if ($bolAcaoDocumentoVisualizar) {
        $strSubstituicao = '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento=' . $objAtributoAndamentoDTO->getStrIdOrigem()) . '" target="_blank" class="ancoraHistoricoProcesso">' . $objAtributoAndamentoDTO->getStrValor() . '</a> (' . $strIdentificacao . ')';
      } else {
        $strSubstituicao = $objAtributoAndamentoDTO->getStrValor() . ' (' . $strIdentificacao . ')';
      }
    }

    return $strSubstituicao;
  }

  private function substitutirAtributoMultiploDocumentos(
    $objAtributoAndamentoDTO, $arrObjAtributoAndamentoDTO, $arrObjDocumentoDTO, $bolAcaoDocumentoVisualizar, &$strNomeTarefa) {
    if (is_array($arrObjAtributoAndamentoDTO)) {
      $arr = array();

      $numAtributosTotal = InfraArray::contar($arrObjAtributoAndamentoDTO);
      for ($i = 0; $i < $numAtributosTotal; $i++) {
        if ($arrObjAtributoAndamentoDTO[$i]->getNumIdAtividade() == $objAtributoAndamentoDTO->getNumIdAtividade()) {
          $arr[] = $arrObjAtributoAndamentoDTO[$i];
        }
      }

      $n = count($arr);
      $strValorMultiplo = '';
      for ($i = 0; $i < $n; $i++) {
        if ($strValorMultiplo != '') {
          if ($i == ($n - 1)) {
            $strValorMultiplo .= ' e ';
          } else {
            $strValorMultiplo .= ', ';
          }
        }
        $strValorMultiplo .= $this->montarAtributoDocumentoHistorico($arr[$i], $arrObjDocumentoDTO, $bolAcaoDocumentoVisualizar);
      }

      $strNomeTarefa = str_replace('@DOCUMENTO@', $strValorMultiplo, $strNomeTarefa);
    }
  }

  private function substitutirAtributoMultiploUnidades(
    $objAtributoAndamentoDTO, $arrObjAtributoAndamentoDTO, &$strNomeTarefa) {
    if (is_array($arrObjAtributoAndamentoDTO)) {
      $arr = array();

      $numAtributosTotal = InfraArray::contar($arrObjAtributoAndamentoDTO);
      for ($i = 0; $i < $numAtributosTotal; $i++) {
        if ($arrObjAtributoAndamentoDTO[$i]->getNumIdAtividade() == $objAtributoAndamentoDTO->getNumIdAtividade()) {
          $arr[] = $arrObjAtributoAndamentoDTO[$i];
        }
      }

      $n = count($arr);
      $strValorMultiplo = '';
      for ($i = 0; $i < $n; $i++) {
        if ($strValorMultiplo != '') {
          if ($i == ($n - 1)) {
            $strValorMultiplo .= ' e ';
          } else {
            $strValorMultiplo .= ', ';
          }
        }
        $arrValor = explode('¥', $arr[$i]->getStrValor());
        $strValorMultiplo .= '<a href="javascript:void(0);" alt="' . $arrValor[1] . '" title="' . $arrValor[1] . '" class="ancoraSigla">' . $arrValor[0] . '</a>';
      }

      $strNomeTarefa = str_replace('@UNIDADE@', $strValorMultiplo, $strNomeTarefa);
    }
  }

  private function substitutirAtributoMultiploUsuarios(
    $objAtributoAndamentoDTO, $arrObjAtributoAndamentoDTO, &$strNomeTarefa) {
    if (is_array($arrObjAtributoAndamentoDTO)) {
      $arr = array();

      $numAtributosTotal = InfraArray::contar($arrObjAtributoAndamentoDTO);
      for ($i = 0; $i < $numAtributosTotal; $i++) {
        if ($arrObjAtributoAndamentoDTO[$i]->getNumIdAtividade() == $objAtributoAndamentoDTO->getNumIdAtividade()) {
          if ($objAtributoAndamentoDTO->getStrValor() != null) {
            $arr[] = $arrObjAtributoAndamentoDTO[$i];
          }
        }
      }

      $n = count($arr);
      $strValorMultiplo = '';
      for ($i = 0; $i < $n; $i++) {
        if ($strValorMultiplo != '') {
          if ($i == ($n - 1)) {
            $strValorMultiplo .= ' e ';
          } else {
            $strValorMultiplo .= ', ';
          }
        }

        $arrValor = explode('¥', $arr[$i]->getStrValor());
        $strValorMultiplo .= '<a href="javascript:void(0);" alt="' . $arrValor[1] . '" title="' . $arrValor[1] . '" class="ancoraSigla">' . $arrValor[0] . '</a>';
      }

      $strNomeTarefa = str_replace('@USUARIO@', $strValorMultiplo, $strNomeTarefa);
    }
  }

  private function substituirAtributoBlocoHistorico(
    AtributoAndamentoDTO $objAtributoAndamentoDTO, $arrIdBloco, $bolAcaoRelBlocoProtocoloListar, &$strNomeTarefa) {
    $strSubstituicao = $objAtributoAndamentoDTO->getStrValor();

    if ($bolAcaoRelBlocoProtocoloListar) {
      if (in_array($objAtributoAndamentoDTO->getStrIdOrigem(), $arrIdBloco)) {
        $strSubstituicao = '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_listar&id_bloco=' . $objAtributoAndamentoDTO->getStrIdOrigem()) . '" target="_blank" class="ancoraHistoricoProcesso">' . $objAtributoAndamentoDTO->getStrValor() . '</a>';
      }
    }

    $strNomeTarefa = str_replace('@BLOCO@', $strSubstituicao, $strNomeTarefa);
  }

  private function substituirAtributoProcessoHistorico(
    AtributoAndamentoDTO $objAtributoAndamentoDTO, $arrObjProcedimentoDTO, $bolAcaoProcedimentoTrabalhar, &$strNomeTarefa) {
    $strSubstituicao = $objAtributoAndamentoDTO->getStrValor();

    if ($bolAcaoProcedimentoTrabalhar) {
      if (isset($arrObjProcedimentoDTO[$objAtributoAndamentoDTO->getStrIdOrigem()])) {
        $strSubstituicao = '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_procedimento=' . $objAtributoAndamentoDTO->getStrIdOrigem()) . '" target="_blank"  alt="' . PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrNomeTipoProcedimento()) . '" title="' . PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$objAtributoAndamentoDTO->getStrIdOrigem()]->getStrNomeTipoProcedimento()) . '" class="ancoraHistoricoProcesso" >' . $objAtributoAndamentoDTO->getStrValor() . '</a>';
      } else {
        $strSubstituicao = '<a href="javascript:void(0);" onclick="alert(\'Este processo foi excluído.\');" class="ancoraHistoricoProcesso">' . $objAtributoAndamentoDTO->getStrValor() . '</a>';
      }
    }

    $strNomeTarefa = str_replace('@PROCESSO@', $strSubstituicao, $strNomeTarefa);
  }

  private function substituirAtributoLocalizadorHistorico(
    AtributoAndamentoDTO $objAtributoAndamentoDTO, $arrObjLocalizadorDTO, $bolAcaoLocalizadorProtocoloListar, &$strNomeTarefa) {
    $strIdOrigem = $objAtributoAndamentoDTO->getStrIdOrigem();

    //só mostra link se o localizador é da unidade atual
    if ($bolAcaoLocalizadorProtocoloListar && isset($arrObjLocalizadorDTO[$strIdOrigem]) && $arrObjLocalizadorDTO[$strIdOrigem]->getNumIdUnidade() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
      $strSubstituicao = '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_protocolos_listar&id_localizador=' . $strIdOrigem) . '" target="_blank" class="ancoraHistoricoProcesso">' . $objAtributoAndamentoDTO->getStrValor() . '</a>';
    } else {
      $strSubstituicao = $objAtributoAndamentoDTO->getStrValor();
    }

    $strNomeTarefa = str_replace('@LOCALIZADOR@', $strSubstituicao, $strNomeTarefa);
  }

  protected function verificarProcessosControladosConectado($arrObjProcedimentoDTO) {
    try {
      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retDblIdProtocolo();

      $objAtividadeDTO->setDistinct(true);
      $objAtividadeDTO->setDthConclusao(null);
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento'), InfraDTO::$OPER_IN);


      $objAtividadeRN = new AtividadeRN();
      $arrAbertosUnidade = InfraArray::indexarArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO), 'IdProtocolo');

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        if (isset($arrAbertosUnidade[$objProcedimentoDTO->getDblIdProcedimento()])) {
          $objProcedimentoDTO->setStrSinAberto('S');
        } else {
          $objProcedimentoDTO->setStrSinAberto('N');
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro verificando processos controlados.', $e);
    }
  }

  protected function listarRelacionadosConectado(ProcedimentoDTO $parObjProcedimentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_listar_relacionamentos', __METHOD__, $parObjProcedimentoDTO);

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTO->retStrSiglaUsuario();
      $objRelProtocoloProtocoloDTO->retStrNomeUsuario();
      $objRelProtocoloProtocoloDTO->retDthAssociacao();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->retNumIdUnidade();
      $objRelProtocoloProtocoloDTO->retStrSiglaUnidade();
      $objRelProtocoloProtocoloDTO->retStrDescricaoUnidade();

      $objRelProtocoloProtocoloDTO->adicionarCriterio(array('StaAssociacao', 'IdProtocolo1'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
        RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO, $parObjProcedimentoDTO->getDblIdProcedimento()
      ), InfraDTO::$OPER_LOGICO_AND, 'c1');

      $objRelProtocoloProtocoloDTO->adicionarCriterio(array('StaAssociacao', 'IdProtocolo2'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(
        RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO, $parObjProcedimentoDTO->getDblIdProcedimento()
      ), InfraDTO::$OPER_LOGICO_AND, 'c2');


      $objRelProtocoloProtocoloDTO->agruparCriterios(array('c1', 'c2'), InfraDTO::$OPER_LOGICO_OR);

      $objRelProtocoloProtocoloDTO->setOrdDthAssociacao(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();

      $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

      $ret = array();

      if (count($arrObjRelProtocoloProtocoloDTO) > 0) {
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->retDblIdProcedimento();
        $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
        $objProcedimentoDTO->retStrDescricaoProtocolo();
        $objProcedimentoDTO->retNumIdTipoProcedimento();
        $objProcedimentoDTO->retStrNomeTipoProcedimento();
        $objProcedimentoDTO->retStrSinAberto();

        $arr = array();
        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
          if ($objRelProtocoloProtocoloDTO->getDblIdProtocolo1() == $parObjProcedimentoDTO->getDblIdProcedimento()) {
            $arr[] = $objRelProtocoloProtocoloDTO->getDblIdProtocolo2();
          } else {
            $arr[] = $objRelProtocoloProtocoloDTO->getDblIdProtocolo1();
          }
        }

        if (count($arr)) {
          $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
          $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
          $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS_EXCETO_SIGILOSOS_SEM_ACESSO);
          $objPesquisaProtocoloDTO->setDblIdProtocolo(array_unique($arr));

          $objProtocoloRN = new ProtocoloRN();
          $arr = InfraArray::converterArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO), 'IdProtocolo');

          if (count($arr)) {
            $objProcedimentoDTO->setDblIdProcedimento($arr, InfraDTO::$OPER_IN);
            $arrObjProcedimentoDTO = $this->listarRN0278($objProcedimentoDTO);

            foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
              foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
                if ($objProcedimentoDTO->getDblIdProcedimento() == $objRelProtocoloProtocoloDTO->getDblIdProtocolo1()) {
                  $objRelProtocoloProtocoloDTO->setObjProtocoloDTO1($objProcedimentoDTO);
                  $objRelProtocoloProtocoloDTO->setObjProtocoloDTO2(null);
                  $ret[] = $objRelProtocoloProtocoloDTO;
                  break;
                } else {
                  if ($objProcedimentoDTO->getDblIdProcedimento() == $objRelProtocoloProtocoloDTO->getDblIdProtocolo2()) {
                    $objRelProtocoloProtocoloDTO->setObjProtocoloDTO1(null);
                    $objRelProtocoloProtocoloDTO->setObjProtocoloDTO2($objProcedimentoDTO);
                    $ret[] = $objRelProtocoloProtocoloDTO;
                    break;
                  }
                }
              }
            }
          }
        }
      }

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando processos relacionados.', $e);
    }
  }

  protected function concluirControlado(ConcluirProcessoDTO $objConcluirProcessoDTO) {
    try {
      global $SEI_MODULOS;

      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_concluir', __METHOD__, $objConcluirProcessoDTO);

      $objInfraException = new InfraException();

      $objAtividadeRN = new AtividadeRN();

      if (!is_array($objConcluirProcessoDTO->getDblIdProcedimento())) {
        $arrIdProcedimento = array($objConcluirProcessoDTO->getDblIdProcedimento());
      } else {
        $arrIdProcedimento = $objConcluirProcessoDTO->getDblIdProcedimento();
      }

      $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
      $objPesquisaPendenciaDTO->setDblIdProtocolo($arrIdProcedimento);
      $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $arrObjProcedimentoDTOPendencias = InfraArray::indexarArrInfraDTO($objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO), 'IdProcedimento');

      $objProtocoloRN = new ProtocoloRN();
      $objAcessoExternoRN = new AcessoExternoRN();

      $arrObjProcedimentoDTO = InfraArray::gerarArrInfraDTO('ProcedimentoDTO', 'IdProcedimento', $arrIdProcedimento);

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        if (!isset($arrObjProcedimentoDTOPendencias[$objProcedimentoDTO->getDblIdProcedimento()])) {
          $objProtocoloDTO = new ProtocoloDTO();
          $objProtocoloDTO->retStrProtocoloFormatado();
          $objProtocoloDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
          $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

          if ($objProtocoloDTO == null) {
            $objInfraException->lancarValidacao('Processo não encontrado para conclusão.');
          }

          $objInfraException->adicionarValidacao('Processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não está aberto na unidade.');
        } else {
          $objProcedimentoDTO->setStrStaEstadoProtocolo($arrObjProcedimentoDTOPendencias[$objProcedimentoDTO->getDblIdProcedimento()]->getStrStaEstadoProtocolo());
          $objProcedimentoDTO->setStrProtocoloProcedimentoFormatado($arrObjProcedimentoDTOPendencias[$objProcedimentoDTO->getDblIdProcedimento()]->getStrProtocoloProcedimentoFormatado());
          $objProcedimentoDTO->setStrStaNivelAcessoGlobalProtocolo($arrObjProcedimentoDTOPendencias[$objProcedimentoDTO->getDblIdProcedimento()]->getStrStaNivelAcessoGlobalProtocolo());
        }
      }
      $objInfraException->lancarValidacoes();

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        if ($objProcedimentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO) {
          $objInfraException->adicionarValidacao('Processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' está sobrestado.');
        } else {
          if ($objProcedimentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
            $objInfraException->adicionarValidacao('Processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . ' está anexado.');
          } else {
            $objAcessoExternoRN->validarExistenciaLiberacaoInclusao($objProcedimentoDTO, $objInfraException);
          }
        }
      }

      $objInfraException->lancarValidacoes();

      //verifica retornos programados
      $objRetornoProgramadoRN = new RetornoProgramadoRN();
      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        //não obriga retorno para sigilosos
        if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
          $objRetornoProgramadoDTO = new RetornoProgramadoDTO();
          $objRetornoProgramadoDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
          $objRetornoProgramadoRN->validarExistencia($objRetornoProgramadoDTO, $objInfraException);
        }
      }

      $objInfraException->lancarValidacoes();


      $objReaberturaProgramadaDTO = new ReaberturaProgramadaDTO();
      $objReaberturaProgramadaDTO->setNumIdReaberturaProgramada(null);

      if ($objConcluirProcessoDTO->isSetDtaPrazoReaberturaProgramada()) {
        $objReaberturaProgramadaDTO->setDtaPrazo($objConcluirProcessoDTO->getDtaPrazoReaberturaProgramada());
      } else {
        $objReaberturaProgramadaDTO->setDtaPrazo(null);
      }

      if ($objConcluirProcessoDTO->isSetNumDiasReaberturaProgramada()) {
        $objReaberturaProgramadaDTO->setNumDias($objConcluirProcessoDTO->getNumDiasReaberturaProgramada());
      } else {
        $objReaberturaProgramadaDTO->setNumDias(null);
      }

      if ($objConcluirProcessoDTO->isSetStrSinDiasUteisReaberturaProgramada()) {
        $objReaberturaProgramadaDTO->setStrSinDiasUteis($objConcluirProcessoDTO->getStrSinDiasUteisReaberturaProgramada());
      } else {
        $objReaberturaProgramadaDTO->setStrSinDiasUteis('N');
      }

      $objReaberturaProgramadaDTO->setDblIdProtocolo($arrIdProcedimento);

      $objReaberturaProgramadaRN = new ReaberturaProgramadaRN();
      $objReaberturaProgramadaRN->registrar($objReaberturaProgramadaDTO);

      $objDocumentoRN = new DocumentoRN();
      $objControlePrazoRN = new ControlePrazoRN();
      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        $objControlePrazoDTO = new ControlePrazoDTO();
        $objControlePrazoDTO->retNumIdControlePrazo();
        $objControlePrazoDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objControlePrazoRN->excluir($objControlePrazoRN->listar($objControlePrazoDTO));

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_CONCLUSAO_PROCESSO_UNIDADE);

          //bloqueia assinaturas dos documentos gerados e assinados na unidade
          $objDocumentoRN->bloquearTramitacaoConclusao($objProcedimentoDTO);
        } else {
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_CONCLUSAO_PROCESSO_USUARIO);
        }

        $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        $objAtividadeRN->concluirRN0726($arrObjProcedimentoDTOPendencias[$objProcedimentoDTO->getDblIdProcedimento()]->getArrObjAtividadeDTO());
      }

      $arrObjProcedimentoAPI = array();
      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
        $arrObjProcedimentoAPI[] = $objProcedimentoAPI;
      }

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('concluirProcesso', $arrObjProcedimentoAPI);
      }

      $objProcedimentoBD = new ProcedimentoBD(BancoSEI::getInstance());

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
        $objAtividadeDTO->retNumIdAtividade();
        $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setDthConclusao(null);
        if ($objAtividadeRN->consultarRN0033($objAtividadeDTO) == null) {
          $objProcedimentoDTO_Concluido = new ProcedimentoDTO();
          $objProcedimentoDTO_Concluido->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
          $objProcedimentoDTO_Concluido->setDtaConclusao(InfraData::getStrDataAtual());
          $objProcedimentoBD->alterar($objProcedimentoDTO_Concluido);
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro concluindo processo.', $e);
    }
  }

  protected function darCienciaControlado(ProcedimentoDTO $parObjProcedimentoDTO) {
    try {
      global $SEI_MODULOS;

      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_ciencia', __METHOD__, $parObjProcedimentoDTO);

      $objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retNumIdAtividade();
      $objAtividadeDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUsuarioOrigem(SessaoSEI::getInstance()->getNumIdUsuario());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_CIENCIA);
      $objAtividadeDTO->adicionarCriterio(array('Abertura', 'Abertura'), array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL), array(InfraData::getStrDataAtual() . ' 00:00:00', InfraData::getStrDataAtual() . ' 23:59:59'),
        InfraDTO::$OPER_LOGICO_AND);
      $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

      //die($objAtividadeDTO->__toString());

      $objAtividadeRN = new AtividadeRN();
      if ($objAtividadeRN->consultarRN0033($objAtividadeDTO) != null) {
        $objInfraException->lancarValidacao('Usuário já deu ciência no processo hoje.');
      }


      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($parObjProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_CIENCIA);

      $ret = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      $objProcedimentoDTOBanco = new ProcedimentoDTO();
      $objProcedimentoDTOBanco->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
      $this->marcarCiencia($objProcedimentoDTOBanco);


      if (count($SEI_MODULOS)) {
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
        $objProcedimentoDTO->retNumIdTipoProcedimento();
        $objProcedimentoDTO->retStrStaNivelAcessoGlobalProtocolo();
        $objProcedimentoDTO->retNumIdUnidadeGeradoraProtocolo();
        $objProcedimentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
        $objProcedimentoDTO->retNumIdUsuarioGeradorProtocolo();
        $objProcedimentoDTO->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
        $objProcedimentoDTO = $this->consultarRN0201($objProcedimentoDTO);

        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
        $objProcedimentoAPI->setNumeroProtocolo($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
        $objProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
        $objProcedimentoAPI->setIdUnidadeGeradora($objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo());
        $objProcedimentoAPI->setIdOrgaoUnidadeGeradora($objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
        $objProcedimentoAPI->setIdUsuarioGerador($objProcedimentoDTO->getNumIdUsuarioGeradorProtocolo());
        $objProcedimentoAPI->setNivelAcesso($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo());

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('darCienciaProcesso', $objProcedimentoAPI);
        }
      }


      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro dando ciência no processo.', $e);
    }
  }

  protected function darCienciaAnexadoControlado(RelProtocoloProtocoloDTO $parObjRelProtocoloProtocoloDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_anexado_ciencia', __METHOD__, $parObjRelProtocoloProtocoloDTO);

      $objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
      $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo1();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

      if ($objRelProtocoloProtocoloDTO == null) {
        throw new InfraException('Processo anexado não encontrado no processo.');
      }

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->retNumIdAtividade();
      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
      $objAtributoAndamentoDTO->setStrIdOrigem($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $objAtributoAndamentoDTO->setNumIdUsuarioOrigemAtividade(SessaoSEI::getInstance()->getNumIdUsuario());
      $objAtributoAndamentoDTO->setDblIdProtocoloAtividade($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $objAtributoAndamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_PROCESSO_ANEXADO_CIENCIA);

      $objAtributoAndamentoRN = new AtributoAndamentoRN();
      $arrObjAtributoAndamentoDTO = $objAtributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);

      if (count($arrObjAtributoAndamentoDTO)) {
        $objInfraException->lancarValidacao('Usuário já deu ciência neste processo anexado.');
      }


      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
      $objAtributoAndamentoDTO->setStrValor($objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo2());
      $objAtributoAndamentoDTO->setStrIdOrigem($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_ANEXADO_CIENCIA);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

      $objAtividadeRN = new AtividadeRN();
      $ret = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      $objRelProtocoloProtocoloDTOBanco = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTOBanco->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTOBanco->setDblIdProtocolo1($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $objRelProtocoloProtocoloDTOBanco->setDblIdProtocolo2($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
      $objRelProtocoloProtocoloDTOBanco->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
      $objRelProtocoloProtocoloDTOBanco = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTOBanco);

      $objRelProtocoloProtocoloDTOBanco->setStrSinCiencia('S');
      $objRelProtocoloProtocoloRN->alterar($objRelProtocoloProtocoloDTOBanco);

      $objProcedimentoDTOBanco = new ProcedimentoDTO();
      $objProcedimentoDTOBanco->setDblIdProcedimento($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $this->marcarCiencia($objProcedimentoDTOBanco);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro dando ciência no processo anexado.', $e);
    }
  }

  protected function marcarCienciaControlado(ProcedimentoDTO $parObjProcedimentoDTO) {
    try {
      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
      $objProcedimentoDTO->setStrSinCiencia('S');

      $objProcedimentoBD = new ProcedimentoBD($this->getObjInfraIBanco());
      $objProcedimentoBD->alterar($objProcedimentoDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro marcando ciência no processo.', $e);
    }
  }

  protected function duplicarControlado(ProcedimentoDuplicarDTO $objProcedimentoDuplicarDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_duplicar', __METHOD__, $objProcedimentoDuplicarDTO);


      //Regras de Negocio
      $objInfraException = new InfraException();

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTO->retStrStaNivelAcessoGlobalProtocolo();
      $objProcedimentoDTO->retStrSinInternoTipoProcedimento();
      $objProcedimentoDTO->retStrNomeTipoProcedimento();
      $objProcedimentoDTO->setDblIdProcedimento($objProcedimentoDuplicarDTO->getDblIdProcedimento());

      $objProcedimentoDTO = $this->consultarRN0201($objProcedimentoDTO);
      if ($objProcedimentoDTO == null) {
        $objInfraException->lancarValidacao('Processo original não encontrado.');
      }

      if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_SIGILOSO) {
        $objInfraException->lancarValidacao('Processo original possui nível de acesso Sigiloso.');
      }

      if ($objProcedimentoDTO->getStrSinInternoTipoProcedimento() == 'S') {
        $objInfraException->lancarValidacao('O tipo do processo original "' . $objProcedimentoDTO->getStrNomeTipoProcedimento() . '" não permite duplicação.');
      }

      $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
      $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
      $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
      $objPesquisaProtocoloDTO->setDblIdProtocolo(array($objProcedimentoDuplicarDTO->getDblIdProcedimento()));

      $objProtocoloRN = new ProtocoloRN();
      if (count($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO)) == 0) {
        $objInfraException->lancarValidacao('Unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . ' não possui acesso ao processo [' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . '].');
      }


      $objInfraException->lancarValidacoes();

      /////
      $objProcedimentoCloneDTO = new ProcedimentoDTO();
      $objProcedimentoCloneDTO->retDblIdProcedimento();
      $objProcedimentoCloneDTO->retNumIdTipoProcedimento();
      $objProcedimentoCloneDTO->retStrDescricaoProtocolo();
      $objProcedimentoCloneDTO->retStrStaNivelAcessoLocalProtocolo();
      $objProcedimentoCloneDTO->retStrStaGrauSigiloProtocolo();
      $objProcedimentoCloneDTO->retNumIdHipoteseLegalProtocolo();

      $objProcedimentoCloneDTO->setDblIdProcedimento($objProcedimentoDuplicarDTO->getDblIdProcedimento());
      $objProcedimentoCloneDTO = $this->consultarRN0201($objProcedimentoCloneDTO);

      $objProcedimentoCloneDTO->setStrSinGerarPendencia('S');

      //Recuperar em ArrAssuntos os assuntos
      $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
      $objRelProtocoloAssuntoDTO->setDistinct(true);
      $objRelProtocoloAssuntoDTO->retNumIdAssunto();
      $objRelProtocoloAssuntoDTO->retNumSequencia();
      $objRelProtocoloAssuntoDTO->setDblIdProtocolo($objProcedimentoDuplicarDTO->getDblIdProcedimento());

      $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
      $arrAssuntos = $objRelProtocoloAssuntoRN->listarRN0188($objRelProtocoloAssuntoDTO);

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setStrProtocoloFormatado(null);
      $objProtocoloDTO->setStrDescricao($objProcedimentoCloneDTO->getStrDescricaoProtocolo());
      $objProtocoloDTO->setStrStaNivelAcessoLocal($objProcedimentoCloneDTO->getStrStaNivelAcessoLocalProtocolo());
      $objProtocoloDTO->setStrStaGrauSigilo($objProcedimentoCloneDTO->getStrStaGrauSigiloProtocolo());
      $objProtocoloDTO->setNumIdHipoteseLegal($objProcedimentoCloneDTO->getNumIdHipoteseLegalProtocolo());

      $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO($arrAssuntos);

      $arrObjParticipanteDTO = array();
      if (!InfraString::isBolVazia($objProcedimentoDuplicarDTO->getNumIdInteressado())) {
        $objPartipanteDTO = new ParticipanteDTO();
        $objPartipanteDTO->setNumIdContato($objProcedimentoDuplicarDTO->getNumIdInteressado());
        $objPartipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
        $objPartipanteDTO->setNumSequencia(0);
        $arrObjParticipanteDTO[] = $objPartipanteDTO;
      }
      $objProtocoloDTO->setArrObjParticipanteDTO($arrObjParticipanteDTO);

      //Observacoes
      $objObservacaoDTO = new ObservacaoDTO();
      $objObservacaoDTO->retStrDescricao();
      $objObservacaoDTO->setDblIdProtocolo($objProcedimentoDuplicarDTO->getDblIdProcedimento());
      $objObservacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $objObservacaoRN = new ObservacaoRN();
      $objProtocoloDTO->setArrObjObservacaoDTO($objObservacaoRN->listarRN0219($objObservacaoDTO));

      $objProcedimentoCloneDTO->setObjProtocoloDTO($objProtocoloDTO);

      $objProcedimentoDTO = $this->gerarRN0156($objProcedimentoCloneDTO);

      if ($objProcedimentoDuplicarDTO->getStrSinProcessosRelacionados() == 'S') {
        $objProcedimentoDTORelacionado = new ProcedimentoDTO();
        $objProcedimentoDTORelacionado->setDblIdProcedimento($objProcedimentoDuplicarDTO->getDblIdProcedimento());

        $arrObjRelProtocoloProtocoloDTO = $this->listarRelacionados($objProcedimentoDTORelacionado);

        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
          if ($objRelProtocoloProtocoloDTO->getObjProtocoloDTO1() != null) {
            $objProcedimentoDTORelacionado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO1();
          } else {
            $objProcedimentoDTORelacionado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();
          }

          $objRelProtocoloProtocoloDTOClone = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTOClone->setDblIdProtocolo1($objProcedimentoDTO->getDblIdProcedimento());
          $objRelProtocoloProtocoloDTOClone->setDblIdProtocolo2($objProcedimentoDTORelacionado->getDblIdProcedimento());
          $this->relacionarProcedimentoRN1020($objRelProtocoloProtocoloDTOClone);
        }
      }

      if (InfraArray::contar($objProcedimentoDuplicarDTO->getArrIdDocumentosProcesso()) > 0) {
        $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
        $objRelProtocoloProtocoloDTO->retStrStaProtocoloProtocolo2();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDuplicarDTO->getDblIdProcedimento());
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProcedimentoDuplicarDTO->getArrIdDocumentosProcesso(), InfraDTO::$OPER_IN);
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
        $objRelProtocoloProtocoloDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

        $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);


        $objDocumentoRN = new DocumentoRN();
        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
          $objDocumentoClonarDTO = new DocumentoDTO();
          $objDocumentoClonarDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
          $objDocumentoClonarDTO->setDblIdDocumento($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
          $objDocumentoClonarDTO = $objDocumentoRN->prepararCloneRN1110($objDocumentoClonarDTO);

          $objProtocoloDTO = $objDocumentoClonarDTO->getObjProtocoloDTO();

          //coloca novo interessado
          $objProtocoloDTO->setArrObjParticipanteDTO($arrObjParticipanteDTO);

          $objDocumentoRN->cadastrarRN0003($objDocumentoClonarDTO);
        }
      }
      return $objProcedimentoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro duplicando processo.', $e);
    }
  }

  protected function listarSobrestadosConectado(PesquisaPendenciaDTO $objPesquisaPendenciaDTO) {
    try {
      $objAtividadeRN = new AtividadeRN();
      $arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);

      $arrObjProcessoSobrestadoDTO = array();

      if (count($arrObjProcedimentoDTO) > 0) {
        //busca atividades de sobrestamento dos processos
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->retDblIdProtocoloAtividade();
        $objAtributoAndamentoDTO->retDthAberturaAtividade();
        $objAtributoAndamentoDTO->retStrSiglaUsuarioOrigemAtividade();
        $objAtributoAndamentoDTO->retStrNomeUsuarioOrigemAtividade();
        $objAtributoAndamentoDTO->retStrValor();
        $objAtributoAndamentoDTO->setStrNome('MOTIVO');
        $objAtributoAndamentoDTO->setDblIdProtocoloAtividade(InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento'), InfraDTO::$OPER_IN);
        $objAtributoAndamentoDTO->setNumIdTarefaAtividade(array(
          TarefaRN::$TI_SOBRESTAMENTO, TarefaRN::$TI_SOBRESTADO_AO_PROCESSO, TarefaRN::$TI_SOBRESTANDO_PROCESSO
        ), InfraDTO::$OPER_IN);
        $objAtributoAndamentoDTO->setOrdNumIdAtividade(InfraDTO::$TIPO_ORDENACAO_DESC);

        $objAtributoAndamentoRN = new AtributoAndamentoRN();
        $arrObjAtributoAndamentoDTO = $objAtributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);


        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2(InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento'), InfraDTO::$OPER_IN);
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_SOBRESTADO);

        $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
        $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

        if (count($arrObjRelProtocoloProtocoloDTO) > 0) {
          $objProcedimentoDTOVinculado = new ProcedimentoDTO();
          $objProcedimentoDTOVinculado->retDblIdProcedimento();
          $objProcedimentoDTOVinculado->retStrProtocoloProcedimentoFormatado();
          $objProcedimentoDTOVinculado->retStrNomeTipoProcedimento();
          $objProcedimentoDTOVinculado->setDblIdProcedimento(InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO, 'IdProtocolo1'), InfraDTO::$OPER_IN);

          $arrObjProcedimentoVinculadoDTO = InfraArray::indexarArrInfraDTO($this->listarRN0278($objProcedimentoDTOVinculado), 'IdProcedimento');
        }

        foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
          $objProcessoSobrestadoDTO = new ProcessoSobrestadoDTO();
          $objProcessoSobrestadoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
          $objProcessoSobrestadoDTO->setStrProtocoloProcedimentoFormatado($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
          $objProcessoSobrestadoDTO->setStrNomeTipoProcedimento($objProcedimentoDTO->getStrNomeTipoProcedimento());

          foreach ($arrObjAtributoAndamentoDTO as $objAtributoAndamentoDTO) {
            if ($objAtributoAndamentoDTO->getDblIdProtocoloAtividade() == $objProcedimentoDTO->getDblIdProcedimento()) {
              $objProcessoSobrestadoDTO->setStrMotivo($objAtributoAndamentoDTO->getStrValor());
              $objProcessoSobrestadoDTO->setDthData($objAtributoAndamentoDTO->getDthAberturaAtividade());
              $objProcessoSobrestadoDTO->setStrSiglaUsuario($objAtributoAndamentoDTO->getStrSiglaUsuarioOrigemAtividade());
              $objProcessoSobrestadoDTO->setStrNomeUsuario($objAtributoAndamentoDTO->getStrNomeUsuarioOrigemAtividade());
              //pega motivo do último sobrestamento
              break;
            }
          }

          $objProcessoSobrestadoDTO->setDblIdProcedimentoVinculado(null);
          $objProcessoSobrestadoDTO->setStrProtocoloProcedimentoFormatadoVinculado(null);
          $objProcessoSobrestadoDTO->setStrNomeTipoProcedimentoVinculado(null);

          if (InfraArray::contar($arrObjRelProtocoloProtocoloDTO) > 0) {
            foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
              if ($objRelProtocoloProtocoloDTO->getDblIdProtocolo2() == $objProcedimentoDTO->getDblIdProcedimento()) {
                $objProcessoSobrestadoDTO->setDblIdProcedimentoVinculado($arrObjProcedimentoVinculadoDTO[$objRelProtocoloProtocoloDTO->getDblIdProtocolo1()]->getDblIdProcedimento());
                $objProcessoSobrestadoDTO->setStrProtocoloProcedimentoFormatadoVinculado($arrObjProcedimentoVinculadoDTO[$objRelProtocoloProtocoloDTO->getDblIdProtocolo1()]->getStrProtocoloProcedimentoFormatado());
                $objProcessoSobrestadoDTO->setStrNomeTipoProcedimentoVinculado($arrObjProcedimentoVinculadoDTO[$objRelProtocoloProtocoloDTO->getDblIdProtocolo1()]->getStrNomeTipoProcedimento());
                break;
              }
            }
          }


          $arrObjProcessoSobrestadoDTO[] = $objProcessoSobrestadoDTO;
        }
      }

      return $arrObjProcessoSobrestadoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro listando processos sobrestados.', $e);
    }
  }

  private function validarTramitacaoUnificada(ProcedimentoDTO $objProcedimentoDTO, InfraException $objInfraException) {
    $objAtividadeDTO = new AtividadeDTO();
    $objAtividadeDTO->setDistinct(true);
    $objAtividadeDTO->retNumIdAtividade();
    $objAtividadeDTO->retNumIdUnidade();
    $objAtividadeDTO->retStrProtocoloFormatadoProtocolo();
    $objAtividadeDTO->retStrSiglaUnidade();
    $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), InfraDTO::$OPER_DIFERENTE);
    $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());

    $objAtividadeDTO->setDthConclusao(null);

    $objAtividadeRN = new AtividadeRN();
    $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

    if (count($arrObjAtividadeDTO) > 0) {
      $objInfraException->lancarValidacao('Processo ' . $arrObjAtividadeDTO[0]->getStrProtocoloFormatadoProtocolo() . ' está aberto na(s) unidade(s): ' . implode(', ',
          array_unique(InfraArray::converterArrInfraDTO($arrObjAtividadeDTO, 'SiglaUnidade'))));
    }
  }

  protected function receberConectado(ProcedimentoDTO $objProcedimentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_receber', __METHOD__, $objProcedimentoDTO);

      $arrObjAtividadeDTO = $objProcedimentoDTO->getArrObjAtividadeDTO();

      if (InfraArray::contar($arrObjAtividadeDTO)) {
        if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
          $arrIdTarefaRemessa = array(TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE);
          $numIdTarefaRecebimento = TarefaRN::$TI_PROCESSO_RECEBIDO_UNIDADE;
        } else {
          $arrIdTarefaRemessa = TarefaRN::getArrTarefasConcessaoCredencial(false);
          $numIdTarefaRecebimento = TarefaRN::$TI_PROCESSO_RECEBIMENTO_CREDENCIAL;
        }

        $arr = array();

        $objAtividadeRN = new AtividadeRN();

        foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
          if (in_array($objAtividadeDTO->getNumIdTarefa(), $arrIdTarefaRemessa)) {
            $dto = new AtividadeDTO();
            $dto->setDblIdProtocolo($objAtividadeDTO->getDblIdProtocolo());
            $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $dto->setNumIdUsuarioVisualizacao(SessaoSEI::getInstance()->getNumIdUsuario());
            $dto->setNumIdTarefa($numIdTarefaRecebimento);
            $objAtividadeRN->gerarInternaRN0727($dto);
            return;
          }

          if ($objAtividadeDTO->getNumTipoVisualizacao() != AtividadeRN::$TV_VISUALIZADO) {
            $arr[] = $objAtividadeDTO;
          }
        }


        if (InfraArray::contar($arr)) {
          $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

          if ($arrObjAtividadeDTO[0]->getNumIdUsuarioAtribuicao() == null || $arrObjAtividadeDTO[0]->getNumIdUsuarioAtribuicao() == SessaoSEI::getInstance()->getNumIdUsuario() || $objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_SIGILOSO || $objInfraParametro->getValor('SEI_SINALIZACAO_PROCESSO') == '0') {
            $objSinalizacaoFederacaoRN = new SinalizacaoFederacaoRN();
            $objSinalizacaoFederacaoRN->configurarVisualizada($objProcedimentoDTO);

            $objReaberturaProgramadaRN = new ReaberturaProgramadaRN();
            $objReaberturaProgramadaRN->configurarVisualizada($objProcedimentoDTO);

            $objAtividadeRN = new AtividadeRN();
            $objAtividadeRN->configurarVisualizada($arr);
          }
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro recebendo processo.', $e);
    }
  }

  protected function verificarLiberacaoNumeroProcessoConectado(ProcedimentoDTO $objProcedimentoDTO) {
    try {
      //web services
      if (!SessaoSEI::getInstance()->isBolHabilitada()) {
        return true;
      }

      $objInfraParametro = new InfraParametro($this->getObjInfraIBanco());
      $numHabilitarNumeroProcessoInformado = $objInfraParametro->getValor('SEI_HABILITAR_NUMERO_PROCESSO_INFORMADO');

      $strSinUnidadeProtocolo = null;
      if ($numHabilitarNumeroProcessoInformado == 1 || $numHabilitarNumeroProcessoInformado == 3) {
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->retStrSinProtocolo();
        $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objUnidadeRN = new UnidadeRN();
        $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);
        $strSinUnidadeProtocolo = $objUnidadeDTO->getStrSinProtocolo();
      }

      if ($objProcedimentoDTO->getDblIdProcedimento() == null) {
        if (($numHabilitarNumeroProcessoInformado == 1 && $strSinUnidadeProtocolo == 'S') || $numHabilitarNumeroProcessoInformado == 2 || $numHabilitarNumeroProcessoInformado == 3) {
          return true;
        }
      } else {
        if ((($numHabilitarNumeroProcessoInformado == 1 || $numHabilitarNumeroProcessoInformado == 3) && $strSinUnidadeProtocolo == 'S') || $numHabilitarNumeroProcessoInformado == 2) {
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->retNumIdAtributoAndamento();
          $objAtributoAndamentoDTO->setDblIdProtocoloAtividade($objProcedimentoDTO->getDblIdProcedimento());
          $objAtributoAndamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_GERACAO_PROCEDIMENTO);
          $objAtributoAndamentoDTO->setStrNome('PROTOCOLO_INFORMADO');

          $objAtributoAndamentoRN = new AtributoAndamentoRN();
          if ($objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO) != null) {
            return true;
          }
        }
      }

      return false;
    } catch (Exception $e) {
      throw new InfraException('Erro verificando liberação para informar número de processo.', $e);
    }
  }

  protected function verificarAnexacaoConectado(ProcedimentoDTO $objProcedimentoDTO) {
    try {
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objDocumentoDTO->setStrStaDocumento(array(
        DocumentoRN::$TD_EDITOR_EDOC, DocumentoRN::$TD_EDITOR_INTERNO, DocumentoRN::$TD_FORMULARIO_GERADO
      ), InfraDTO::$OPER_IN);

      $objDocumentoRN = new DocumentoRN();
      $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);

      $numDocumentosAssinaveisProcesso = count($arrObjDocumentoDTO);

      if ($numDocumentosAssinaveisProcesso) {
        $objAssinaturaDTO = new AssinaturaDTO();
        $objAssinaturaDTO->setDistinct(true);
        $objAssinaturaDTO->retDblIdDocumento();
        $objAssinaturaDTO->setDblIdDocumento(InfraArray::converterArrInfraDTO($arrObjDocumentoDTO, 'IdDocumento'), InfraDTO::$OPER_IN);

        $objAssinaturaRN = new AssinaturaRN();
        $numDocumentosAssinadosProcesso = $objAssinaturaRN->contarRN1324($objAssinaturaDTO);

        return ($numDocumentosAssinaveisProcesso == $numDocumentosAssinadosProcesso);
      }

      return true;
    } catch (Exception $e) {
      throw new InfraException('Erro verificando possibilidade de anexação do processo.', $e);
    }
  }

  public function gerarDocumentoMultiplo(DocumentoGeracaoMultiplaDTO $objDocumentoGeracaoMultiplaDTO) {
    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $this->gerarDocumentoMultiploInterno($objDocumentoGeracaoMultiplaDTO);

    if (!$bolAcumulacaoPrevia) {
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }
  }

  protected function gerarDocumentoMultiploInternoControlado(
    DocumentoGeracaoMultiplaDTO $objDocumentoGeracaoMultiplaDTO) {
    try {
      $objInfraException = new InfraException();

      $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
      $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
      $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
      $objPesquisaProtocoloDTO->setDblIdProtocolo($objDocumentoGeracaoMultiplaDTO->getArrDblIdProcedimento());

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTOProcessos = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

      if (count($arrObjProtocoloDTOProcessos) == 0) {
        $objInfraException->lancarValidacao('Nenhum processo encontrado.');
      }

      $objSerieDTO = new SerieDTO();
      $objSerieDTO->retStrSinInteressado();
      $objSerieDTO->setNumIdSerie($objDocumentoGeracaoMultiplaDTO->getNumIdSerie());

      $objSerieRN = new SerieRN();
      $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);

      //sugestão de assuntos do tipo de documento
      $objRelSerieAssuntoDTO = new RelSerieAssuntoDTO();
      $objRelSerieAssuntoDTO->retNumIdAssunto();
      $objRelSerieAssuntoDTO->retNumSequencia();
      $objRelSerieAssuntoDTO->retStrCodigoEstruturadoAssunto();
      $objRelSerieAssuntoDTO->retStrDescricaoAssunto();
      $objRelSerieAssuntoDTO->setNumIdSerie($objDocumentoGeracaoMultiplaDTO->getNumIdSerie());
      $objRelSerieAssuntoDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objRelSerieAssuntoRN = new RelSerieAssuntoRN();
      $arrObjRelSerieAssuntoDTO = $objRelSerieAssuntoRN->listar($objRelSerieAssuntoDTO);

      $arrObjRelProtocoloAssuntoDTO = array();
      foreach ($arrObjRelSerieAssuntoDTO as $objRelSerieAssuntoDTO) {
        $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
        $objRelProtocoloAssuntoDTO->setNumIdAssunto($objRelSerieAssuntoDTO->getNumIdAssunto());
        $objRelProtocoloAssuntoDTO->setNumSequencia($objRelSerieAssuntoDTO->getNumSequencia());
        $arrObjRelProtocoloAssuntoDTO[] = $objRelProtocoloAssuntoDTO;
      }


      $objDocumentoRN = new DocumentoRN();
      $objParticipanteRN = new ParticipanteRN();
      $arrProtocolosBloco = array();

      foreach ($arrObjProtocoloDTOProcessos as $objProtocoloDTOProcesso) {
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setDblIdDocumento(null);
        $objDocumentoDTO->setDblIdProcedimento($objProtocoloDTOProcesso->getDblIdProtocolo());

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setDblIdProtocolo(null);
        $objDocumentoDTO->setNumIdSerie($objDocumentoGeracaoMultiplaDTO->getNumIdSerie());
        $objProtocoloDTO->setNumIdSerieDocumento($objDocumentoGeracaoMultiplaDTO->getNumIdSerie());
        $objDocumentoDTO->setDblIdDocumentoEdoc(null);
        $objDocumentoDTO->setDblIdDocumentoEdocBase(null);
        $objDocumentoDTO->setNumIdUnidadeResponsavel(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objDocumentoDTO->setStrNumero(null);
        $objDocumentoDTO->setStrNomeArvore(null);
        $objDocumentoDTO->setNumIdTipoConferencia(null);
        $objDocumentoDTO->setStrSinBloqueado('N');
        $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EDITOR_INTERNO);

        $objProtocoloDTO->setStrStaNivelAcessoLocal($objDocumentoGeracaoMultiplaDTO->getStrStaNivelAcessoLocal());
        $objProtocoloDTO->setNumIdHipoteseLegal($objDocumentoGeracaoMultiplaDTO->getNumIdHipoteseLegal());
        $objProtocoloDTO->setStrStaGrauSigilo($objDocumentoGeracaoMultiplaDTO->getStrStaGrauSigilo());

        $objProtocoloDTO->setStrDescricao(null);
        $objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());
        $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO($arrObjRelProtocoloAssuntoDTO);

        if ($objSerieDTO->getStrSinInteressado() == 'S') {
          //busca interessados do processo
          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->retNumIdContato();
          $objParticipanteDTO->retNumSequencia();
          $objParticipanteDTO->retStrStaParticipacao();
          $objParticipanteDTO->setDblIdProtocolo($objProtocoloDTOProcesso->getDblIdProtocolo());
          $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
          $objParticipanteDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);
          $objProtocoloDTO->setArrObjParticipanteDTO($objParticipanteRN->listarRN0189($objParticipanteDTO));
        } else {
          $objProtocoloDTO->setArrObjParticipanteDTO(array());
        }

        $objProtocoloDTO->setArrObjObservacaoDTO(array());

        $objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);

        $objDocumentoDTO->setNumIdTextoPadraoInterno($objDocumentoGeracaoMultiplaDTO->getNumIdTextoPadraoInterno());
        $objDocumentoDTO->setStrProtocoloDocumentoTextoBase($objDocumentoGeracaoMultiplaDTO->getStrProtocoloFormatadoDocumentoBase());

        $objDocumentoDTO = $objDocumentoRN->cadastrarRN0003($objDocumentoDTO);

        $arrProtocolosBloco[] = $objDocumentoDTO->getDblIdDocumento();
      }

      if (!InfraString::isBolVazia($objDocumentoGeracaoMultiplaDTO->getNumIdBloco())) {
        $arrObjRelBlocoProtocoloDTO = array();
        foreach ($arrProtocolosBloco as $dblIdProtocolo) {
          $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
          $objRelBlocoProtocoloDTO->setNumIdBloco($objDocumentoGeracaoMultiplaDTO->getNumIdBloco());
          $objRelBlocoProtocoloDTO->setDblIdProtocolo($dblIdProtocolo);
          $objRelBlocoProtocoloDTO->setStrAnotacao(null);
          $arrObjRelBlocoProtocoloDTO[] = $objRelBlocoProtocoloDTO;
        }

        $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
        $objRelBlocoProtocoloRN->cadastrarMultiplo($arrObjRelBlocoProtocoloDTO);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro gerando documentos múltiplos.', $e);
    }
  }

  public function verificarEstadoProcedimento(InfraDTO $dto) {
    $objInfraException = new InfraException();

    if ($dto instanceof ProtocoloDTO) {
      $strStaEstado = $dto->getStrStaEstado();
      $strSinEliminado = $dto->getStrSinEliminado();
      $strProtocoloFormatado = $dto->getStrProtocoloFormatado();
    } else {
      if ($dto instanceof ProcedimentoDTO) {
        $strStaEstado = $dto->getStrStaEstadoProtocolo();
        $strSinEliminado = $dto->getStrSinEliminadoProtocolo();
        $strProtocoloFormatado = $dto->getStrProtocoloProcedimentoFormatado();
      } else {
        if ($dto instanceof DocumentoDTO) {
          $strStaEstado = $dto->getStrStaEstadoProcedimento();
          $strSinEliminado = $dto->getStrSinEliminadoProcedimento();
          $strProtocoloFormatado = $dto->getStrProtocoloProcedimentoFormatado();
        } else {
          throw new InfraException('Objeto inválido verificando estado do processo: ' . get_class($dto));
        }
      }
    }

    if ($strSinEliminado == 'S') {
      $objInfraException->lancarValidacao('Processo ' . $strProtocoloFormatado . ' foi eliminado.');
    }

    if ($strStaEstado == ProtocoloRN::$TE_PROCEDIMENTO_BLOQUEADO) {
      $objInfraException->lancarValidacao('Processo ' . $strProtocoloFormatado . ' está bloqueado.');
    } else {
      if ($strStaEstado == ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO) {
        $objInfraException->lancarValidacao('Processo ' . $strProtocoloFormatado . ' está sobrestado.');
      } else {
        if ($strStaEstado == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
          $objInfraException->lancarValidacao('Processo ' . $strProtocoloFormatado . ' está anexado.');
        }
      }
    }
  }

  protected function verificarProcessoAnexadorAbertoConectado(InfraDTO $dto) {
    try {
      $objInfraException = new InfraException();

      $dblIdProcedimento = null;
      if ($dto instanceof ProcedimentoDTO || $dto instanceof DocumentoDTO) {
        $dblIdProcedimento = $dto->getDblIdProcedimento();
      } else {
        throw new InfraException('Objeto inválido verificando processo anexador aberto: ' . get_class($dto));
      }

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
      $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo1();
      $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($dblIdProcedimento);
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

      if ($objRelProtocoloProtocoloDTO != null) {
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->retNumIdAtividade();
        $objAtividadeDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setDthConclusao(null);
        $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

        $objAtividadeRN = new AtividadeRN();
        if ($objAtividadeRN->consultarRN0033($objAtividadeDTO) == null) {
          $objInfraException->lancarValidacao('Processo ' . $objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo2() . ' está anexado no processo ' . $objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo1() . ' que não possui andamento aberto na unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '.');
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro verificando processo anexador aberto.', $e);
    }
  }

  protected function pesquisarSigilososCredencialUnidadeConectado(PesquisaSigilosoDTO $parObjPesquisaSigilosoDTO) {
    try {
      $parObjPesquisaSigilosoDTO->setNumIdUsuarioAcesso(SessaoSEI::getInstance()->getNumIdUsuario());
      $parObjPesquisaSigilosoDTO->setNumIdUnidadeAcesso(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $parObjPesquisaSigilosoDTO->setStrStaTipoAcesso(AcessoRN::$TA_CREDENCIAL_PROCESSO);
      $arrObjProcedimentoDTO = $this->pesquisarSigilosos($parObjPesquisaSigilosoDTO);
      return $arrObjProcedimentoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro pesquisando processos com credencial na unidade.', $e);
    }
  }

  private function pesquisarSigilosos(PesquisaSigilosoDTO $parObjPesquisaSigilosoDTO) {
    try {
      if (!$parObjPesquisaSigilosoDTO->isSetStrSinAbertosFechados()) {
        $parObjPesquisaSigilosoDTO->setStrSinAbertosFechados('N');
      }

      if (!$parObjPesquisaSigilosoDTO->isSetStrSinAnotacoes()) {
        $parObjPesquisaSigilosoDTO->setStrSinAnotacoes('N');
      }

      if (!$parObjPesquisaSigilosoDTO->isSetStrSinObservacoes()) {
        $parObjPesquisaSigilosoDTO->setStrSinObservacoes('N');
      }

      if (!$parObjPesquisaSigilosoDTO->isSetStrSinAcompanhamentos()) {
        $parObjPesquisaSigilosoDTO->setStrSinAcompanhamentos('N');
      }

      $objPesquisaSigilosoDTO = clone($parObjPesquisaSigilosoDTO);
      $objPesquisaSigilosoDTO->setDistinct(true);
      $objPesquisaSigilosoDTO->retDblIdProtocolo();

      if ($objPesquisaSigilosoDTO->isSetStrProtocoloFormatadoPesquisa() && !InfraString::isBolVazia($objPesquisaSigilosoDTO->getStrProtocoloFormatadoPesquisa())) {
        $strProtocoloPesquisa = InfraUtil::retirarFormatacao($objPesquisaSigilosoDTO->getStrProtocoloFormatadoPesquisa(), false);

        $objPesquisaSigilosoDTO->unSetStrProtocoloFormatadoPesquisa();

        $objPesquisaSigilosoDTO->adicionarCriterio(array('ProtocoloFormatadoPesquisa', 'ProtocoloFormatadoPesquisa', 'ProtocoloFormatadoPesqInv'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_LIKE, InfraDTO::$OPER_LIKE),
          array($strProtocoloPesquisa, $strProtocoloPesquisa . '%', strrev($strProtocoloPesquisa) . '%'), array(InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR));
      }

      if ($objPesquisaSigilosoDTO->isSetStrIdxObservacao() && !InfraString::isBolVazia($objPesquisaSigilosoDTO->getStrIdxObservacao())) {
        $objPesquisaSigilosoDTO = InfraString::prepararPesquisaDTO($objPesquisaSigilosoDTO, "IdxObservacao");
        $objPesquisaSigilosoDTO->setNumIdUnidadeObservacao(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objPesquisaSigilosoDTO->setNumTipoFkObservacao(InfraDTO::$TIPO_FK_OBRIGATORIA);
      }

      if ($objPesquisaSigilosoDTO->isSetNumIdContatoParticipante() && !InfraString::isBolVazia($objPesquisaSigilosoDTO->getNumIdContatoParticipante())) {
        $objPesquisaSigilosoDTO->setStrStaParticipacaoParticipante(ParticipanteRN::$TP_INTERESSADO);
        $objPesquisaSigilosoDTO->setNumTipoFkParticipante(InfraDTO::$TIPO_FK_OBRIGATORIA);

        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->setNumIdContato($objPesquisaSigilosoDTO->getNumIdContatoParticipante());

        $objUsuarioRN = new UsuarioRN();
        $arrIdContato = array_unique(InfraArray::converterArrInfraDTO($objUsuarioRN->obterUsuariosRelacionados($objUsuarioDTO), 'IdContato'));

        if (count($arrIdContato)) {
          $objPesquisaSigilosoDTO->setNumIdContatoParticipante($arrIdContato, InfraDTO::$OPER_IN);
        }
      }


      if ($objPesquisaSigilosoDTO->isSetDtaInicio() && $objPesquisaSigilosoDTO->isSetDtaFim()) {
        $objInfraException = new InfraException();
        InfraData::validarPeriodo($objPesquisaSigilosoDTO->getDtaInicio(), $objPesquisaSigilosoDTO->getDtaFim(), $objInfraException);
        $objInfraException->lancarValidacoes();

        if (!InfraString::isBolVazia($objPesquisaSigilosoDTO->getDtaInicio())) {
          if ($objPesquisaSigilosoDTO->getDtaInicio() == $objPesquisaSigilosoDTO->getDtaFim()) {
            $objPesquisaSigilosoDTO->setDtaGeracao($objPesquisaSigilosoDTO->getDtaInicio());
          } else {
            $objPesquisaSigilosoDTO->adicionarCriterio(array('Geracao', 'Geracao'), array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL), array($objPesquisaSigilosoDTO->getDtaInicio(), $objPesquisaSigilosoDTO->getDtaFim()),
              InfraDTO::$OPER_LOGICO_AND);
          }
        }
      }

      $objPesquisaSigilosoDTO->setStrStaNivelAcessoGlobal(ProtocoloRN::$NA_SIGILOSO);

      //paginação
      $objPesquisaSigilosoDTO->setNumMaxRegistrosRetorno($parObjPesquisaSigilosoDTO->getNumMaxRegistrosRetorno());
      $objPesquisaSigilosoDTO->setNumPaginaAtual($parObjPesquisaSigilosoDTO->getNumPaginaAtual());

      $objProcedimentoBD = new ProcedimentoBD($this->getObjInfraIBanco());
      $arrObjPesquisaSigilosoDTO = $objProcedimentoBD->listar($objPesquisaSigilosoDTO);

      //paginação
      $parObjPesquisaSigilosoDTO->setNumTotalRegistros($objPesquisaSigilosoDTO->getNumTotalRegistros());
      $parObjPesquisaSigilosoDTO->setNumRegistrosPaginaAtual($objPesquisaSigilosoDTO->getNumRegistrosPaginaAtual());


      $arrRet = array();

      if (count($arrObjPesquisaSigilosoDTO)) {
        $bolDescricao = false;
        $bolDtaGeracao = false;
        $bolNomeTipoProcedimento = false;
        $bolProtocoloFormatado = false;

        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO);
        $objProcedimentoDTO->setDblIdProcedimento(null);

        if ($parObjPesquisaSigilosoDTO->isRetStrDescricao()) {
          $bolDescricao = true;
          $objProcedimentoDTO->setStrDescricaoProtocolo(null);
        }

        if ($parObjPesquisaSigilosoDTO->isRetDtaGeracao()) {
          $bolDtaGeracao = true;
          $objProcedimentoDTO->setDtaGeracaoProtocolo(null);
        }

        if ($parObjPesquisaSigilosoDTO->isRetStrNomeTipoProcedimento()) {
          $bolNomeTipoProcedimento = true;
          $objProcedimentoDTO->setStrNomeTipoProcedimento(null);
        }

        if ($parObjPesquisaSigilosoDTO->isRetStrProtocoloFormatado()) {
          $bolProtocoloFormatado = true;
          $objProcedimentoDTO->setStrProtocoloProcedimentoFormatado(null);
        }

        foreach ($arrObjPesquisaSigilosoDTO as $objPesquisaSigilosoDTO) {
          $dto = clone($objProcedimentoDTO);
          $dto->setDblIdProcedimento($objPesquisaSigilosoDTO->getDblIdProtocolo());

          if ($bolDescricao) {
            $dto->setStrDescricaoProtocolo($objPesquisaSigilosoDTO->getStrDescricao());
          }

          if ($bolDtaGeracao) {
            $dto->setDtaGeracaoProtocolo($objPesquisaSigilosoDTO->getDtaGeracao());
          }

          if ($bolNomeTipoProcedimento) {
            $dto->setStrNomeTipoProcedimento($objPesquisaSigilosoDTO->getStrNomeTipoProcedimento());
          }

          if ($bolProtocoloFormatado) {
            $dto->setStrProtocoloProcedimentoFormatado($objPesquisaSigilosoDTO->getStrProtocoloFormatado());
          }

          $arrRet[] = $dto;
        }

        if ($parObjPesquisaSigilosoDTO->getStrSinAbertosFechados() == 'S') {
          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDistinct(true);
          $objAtividadeDTO->retDblIdProtocolo();
          $objAtividadeDTO->setNumIdUsuario($parObjPesquisaSigilosoDTO->getNumIdUsuarioAcesso());
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setDthConclusao(null);
          $objAtividadeDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrRet, 'IdProcedimento'), InfraDTO::$OPER_IN);

          $objAtividadeRN = new AtividadeRN();
          $arrObjAtividadeDTO = InfraArray::indexarArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO), 'IdProtocolo');

          foreach ($arrRet as $objProcedimentoDTO) {
            if (isset($arrObjAtividadeDTO[$objProcedimentoDTO->getDblIdProcedimento()])) {
              $objProcedimentoDTO->setStrSinAberto('S');
            } else {
              $objProcedimentoDTO->setStrSinAberto('N');
            }
          }
        }

        if ($parObjPesquisaSigilosoDTO->getStrSinAnotacoes() == 'S') {
          $objAnotacaoRN = new AnotacaoRN();
          $objAnotacaoRN->complementar($arrRet);
        }

        if ($parObjPesquisaSigilosoDTO->getStrSinObservacoes() == 'S') {
          $objObservaoRN = new ObservacaoRN();
          $objObservaoRN->complementar($arrRet);
        }

        if ($parObjPesquisaSigilosoDTO->getStrSinAcompanhamentos() == 'S') {
          $objAcompanhamentoRN = new AcompanhamentoRN();
          $objAcompanhamentoRN->complementar($arrRet);
        }
      }

      return $arrRet;
    } catch (Exception $e) {
      throw new InfraException('Erro pesquisando processos sigilosos.', $e);
    }
  }

  protected function pesquisarAcervoSigilososGlobalConectado(PesquisaSigilosoDTO $objPesquisaSigilosoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_acervo_sigilosos_global', __METHOD__, $objPesquisaSigilosoDTO);

      $objPesquisaSigilosoDTO->setNumIdUsuarioAtividade(null, InfraDTO::$OPER_DIFERENTE);
      //$objPesquisaSigilosoDTO->setNumIdUnidadeAtividade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $arrObjProcedimentoDTO = $this->pesquisarSigilosos($objPesquisaSigilosoDTO);

      if (count($arrObjProcedimentoDTO)) {
        $arrIdProtocolo = InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento');

        //unidades por onde o processo passou como sigiloso
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDistinct(true);
        $objAtividadeDTO->retDblIdProtocolo();
        $objAtividadeDTO->retNumIdUnidade();
        $objAtividadeDTO->setDblIdProtocolo($arrIdProtocolo, InfraDTO::$OPER_IN);
        $objAtividadeDTO->setNumIdUsuario(null, InfraDTO::$OPER_DIFERENTE);

        $objAtividadeRN = new AtividadeRN();
        $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

        $arrIdUnidades = array_unique(InfraArray::converterArrInfraDTO($arrObjAtividadeDTO, 'IdUnidade'));

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrDescricao();
        $objUnidadeDTO->setNumIdUnidade($arrIdUnidades, InfraDTO::$OPER_IN);

        $objUnidadeRN = new UnidadeRN();
        $arrObjUnidadeDTO = InfraArray::indexarArrInfraDTO($objUnidadeRN->listarRN0127($objUnidadeDTO), 'IdUnidade');

        $objInfraSip = new InfraSip(SessaoSEI::getInstance());
        $arrSip = $objInfraSip->carregarUsuarios(SessaoSEI::getInstance()->getNumIdSistema(), null, 'procedimento_trabalhar');

        $arrPermissoes = array();
        foreach ($arrSip as $usu) {
          for ($i = InfraArray::contar($usu[InfraSip::$WS_USUARIO_UNIDADES]); $i > 0; $i--) {
            $arrPermissoes[$usu[InfraSip::$WS_USUARIO_UNIDADES][$i - 1]][$usu[InfraSip::$WS_USUARIO_ID]] = true;
          }
        }

        //print_r($arrPermissoes);
        //die;

        //busca credenciais atuais nos processos
        $objAcessoDTO = new AcessoDTO();
        $objAcessoDTO->setDistinct(true);
        $objAcessoDTO->retDblIdProtocolo();
        $objAcessoDTO->retNumIdUsuario();
        $objAcessoDTO->retNumIdUnidade();
        $objAcessoDTO->setDblIdProtocolo($arrIdProtocolo, InfraDTO::$OPER_IN);
        $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CREDENCIAL_PROCESSO);

        $objAcessoRN = new AcessoRN();
        $arrObjAcessoDTOPorProtocolo = InfraArray::indexarArrInfraDTO($objAcessoRN->listar($objAcessoDTO), 'IdProtocolo', true);

        //monta array indicando quais unidades tem credenciais ativas nos processos
        $arrAcessoProtocoloUnidade = array();
        foreach ($arrObjAcessoDTOPorProtocolo as $dblIdProtocolo => $arrObjAcessoDTO) {
          foreach ($arrObjAcessoDTO as $objAcessoDTO) {
            if (isset($arrPermissoes[$objAcessoDTO->getNumIdUnidade()][$objAcessoDTO->getNumIdUsuario()])) {
              $arrAcessoProtocoloUnidade[$dblIdProtocolo][$objAcessoDTO->getNumIdUnidade()] = true;
            }
          }
        }

        //print_r($arrAcessoProtocoloUnidade);
        //die;

        $arrObjAtividadeDTO = InfraArray::indexarArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO), 'IdProtocolo', true);

        $arrObjAcessoDTO = array();
        foreach ($arrObjAtividadeDTO as $dblIdProtocolo => $arrObjAtividadeDTOProtocolo) {
          $arrObjAcessoDTO[$dblIdProtocolo] = array();

          foreach ($arrObjAtividadeDTOProtocolo as $objAtividadeDTO) {
            $objAcessoDTO = new AcessoDTO();
            $objAcessoDTO->setDblIdProtocolo($objAtividadeDTO->getDblIdProtocolo());
            $objAcessoDTO->setNumIdUnidade($objAtividadeDTO->getNumIdUnidade());
            $objAcessoDTO->setStrSiglaUnidade($arrObjUnidadeDTO[$objAtividadeDTO->getNumIdUnidade()]->getStrSigla());
            $objAcessoDTO->setStrDescricaoUnidade($arrObjUnidadeDTO[$objAtividadeDTO->getNumIdUnidade()]->getStrDescricao());

            if (isset($arrAcessoProtocoloUnidade[$objAtividadeDTO->getDblIdProtocolo()][$objAtividadeDTO->getNumIdUnidade()])) {
              $objAcessoDTO->setStrStaCredencialUnidade(ProtocoloRN::$TCU_ATIVA);
            } else {
              $objAcessoDTO->setStrStaCredencialUnidade(ProtocoloRN::$TCU_INATIVA);
            }

            $arrObjAcessoDTO[$dblIdProtocolo][] = $objAcessoDTO;
          }
        }

        foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
          if (isset($arrObjAcessoDTO[$objProcedimentoDTO->getDblIdProcedimento()])) {
            $arr = $arrObjAcessoDTO[$objProcedimentoDTO->getDblIdProcedimento()];
            InfraArray::ordenarArrInfraDTO($arr, 'SiglaUnidade', InfraArray::$TIPO_ORDENACAO_ASC);
            $objProcedimentoDTO->setArrObjAcessoDTO($arr);
          } else {
            $objProcedimentoDTO->setArrObjAcessoDTO(array());
          }
        }
      }

      if ($objPesquisaSigilosoDTO->getStrSinCredencialInativa() == 'S') {
        $arrRet = array();
        foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
          $bolInativa = true;
          foreach ($objProcedimentoDTO->getArrObjAcessoDTO() as $objAcessoDTO) {
            if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_ATIVA) {
              $bolInativa = false;
              break;
            }
          }
          if ($bolInativa) {
            $arrRet[] = $objProcedimentoDTO;
          }
        }
        $arrObjProcedimentoDTO = $arrRet;
      }

      return $arrObjProcedimentoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro pesquisando acervo global de processos sigilosos.', $e);
    }
  }

  protected function pesquisarAcervoSigilososUnidadeConectado(PesquisaSigilosoDTO $objPesquisaSigilosoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_acervo_sigilosos_unidade', __METHOD__, $objPesquisaSigilosoDTO);

      $arrRet = array();

      if ($objPesquisaSigilosoDTO->isSetNumIdContatoUsuario()) {
        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->setNumIdContato($objPesquisaSigilosoDTO->getNumIdContatoUsuario());

        $objUsuarioRN = new UsuarioRN();
        $arrIdUsuario = array_unique(InfraArray::converterArrInfraDTO($objUsuarioRN->obterUsuariosRelacionados($objUsuarioDTO), 'IdUsuario'));

        if (count($arrIdUsuario) == 0) {
          throw new InfraException('Nenhum usuário relacionado encontrado.');
        }

        $objPesquisaSigilosoDTO->setNumIdUsuarioAtividade($arrIdUsuario, InfraDTO::$OPER_IN);
      } else {
        $objPesquisaSigilosoDTO->setNumIdUsuarioAtividade(null, InfraDTO::$OPER_DIFERENTE);
      }

      $objPesquisaSigilosoDTO->setNumIdUnidadeAtividade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $arrObjProcedimentoDTO = $this->pesquisarSigilosos($objPesquisaSigilosoDTO);

      if (count($arrObjProcedimentoDTO)) {
        $arrIdProtocolo = InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento');

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDistinct(true);
        $objAtividadeDTO->retDblIdProtocolo();
        $objAtividadeDTO->retNumIdUsuario();
        $objAtividadeDTO->retStrSiglaUsuario();
        $objAtividadeDTO->retStrNomeUsuario();

        $objAtividadeDTO->setDblIdProtocolo($arrIdProtocolo, InfraDTO::$OPER_IN);
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(array_merge(TarefaRN::getArrTarefasTramitacao(), TarefaRN::getArrTarefasConcessaoCredencial(false), TarefaRN::getArrTarefasRenunciaCredencial(), TarefaRN::getArrTarefasCassacaoCredencial(false),
          TarefaRN::getArrTarefasAnulacaoCredencial(false)), InfraDTO::$OPER_IN);

        $objAtividadeDTO->setOrdStrSiglaUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objAtividadeRN = new AtividadeRN();
        $arrObjAtividadeDTO = InfraArray::indexarArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO), 'IdProtocolo', true);

        $objAcessoDTO = new AcessoDTO();
        $objAcessoDTO->setDistinct(true);
        $objAcessoDTO->retDblIdProtocolo();
        $objAcessoDTO->retNumIdUsuario();
        $objAcessoDTO->retStrSiglaUsuario();
        $objAcessoDTO->retStrNomeUsuario();

        $objAcessoDTO->setDblIdProtocolo($arrIdProtocolo, InfraDTO::$OPER_IN);
        $objAcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CREDENCIAL_PROCESSO);

        $objAcessoDTO->setOrdStrSiglaUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objAcessoRN = new AcessoRN();
        $arrObjAcessoDTO = $objAcessoRN->listar($objAcessoDTO);

        $objInfraSip = new InfraSip(SessaoSEI::getInstance());
        $arrSip = $objInfraSip->carregarUsuarios(SessaoSEI::getInstance()->getNumIdSistema(), SessaoSEI::getInstance()->getNumIdUnidadeAtual(), 'procedimento_trabalhar');

        $arrTemp = array();
        foreach ($arrSip as $usu) {
          $arrTemp[$usu[InfraSip::$WS_USUARIO_ID]] = true;
        }
        $arrSip = $arrTemp;

        foreach ($arrObjAcessoDTO as $objAcessoDTO) {
          if (isset($arrSip[$objAcessoDTO->getNumIdUsuario()])) {
            $objAcessoDTO->setStrStaCredencialUnidade(ProtocoloRN::$TCU_ATIVA);
          } else {
            $objAcessoDTO->setStrStaCredencialUnidade(ProtocoloRN::$TCU_INATIVA);
          }
        }

        $arrObjAcessoDTO = InfraArray::indexarArrInfraDTO($arrObjAcessoDTO, 'IdProtocolo', true);

        if ($objPesquisaSigilosoDTO->getStrStaAcessoUnidade() == ProtocoloRN::$TASU_TODOS) {
          $arrRet = $arrObjProcedimentoDTO;
        } else {
          $arrObjProcedimentoDTO = InfraArray::indexarArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento');

          foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
            if (!isset($arrObjAcessoDTO[$objProcedimentoDTO->getDblIdProcedimento()])) {
              if ($objPesquisaSigilosoDTO->getStrStaAcessoUnidade() == ProtocoloRN::$TASU_NAO) {
                $arrRet[] = $objProcedimentoDTO;
              }
            } else {
              $bolCredencialAtiva = false;
              foreach ($arrObjAcessoDTO[$objProcedimentoDTO->getDblIdProcedimento()] as $objAcessoDTO) {
                if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_ATIVA) {
                  $bolCredencialAtiva = true;
                  break;
                }
              }

              if ($objPesquisaSigilosoDTO->getStrStaAcessoUnidade() == ProtocoloRN::$TASU_SIM && $bolCredencialAtiva) {
                $arrRet[] = $objProcedimentoDTO;
              } else {
                if ($objPesquisaSigilosoDTO->getStrStaAcessoUnidade() == ProtocoloRN::$TASU_NAO && !$bolCredencialAtiva) {
                  $arrRet[] = $objProcedimentoDTO;
                }
              }
            }
          }
        }

        foreach ($arrObjAtividadeDTO as $dblIdProtocolo => $arrObjAtividadeDTOProtocolo) {
          if (isset($arrObjAcessoDTO[$dblIdProtocolo])) {
            $arrObjAcessoDTOProtocolo = $arrObjAcessoDTO[$dblIdProtocolo];
          } else {
            $arrObjAcessoDTOProtocolo = array();
          }

          foreach ($arrObjAtividadeDTOProtocolo as $objAtividadeDTO) {
            $bolAcessoProcesso = false;

            foreach ($arrObjAcessoDTOProtocolo as $objAcessoDTO) {
              if ($objAcessoDTO->getNumIdUsuario() == $objAtividadeDTO->getNumIdUsuario()) {
                $bolAcessoProcesso = true;
                break;
              }
            }

            if (!$bolAcessoProcesso) {
              $objAcessoDTO = new AcessoDTO();
              $objAcessoDTO->setDblIdProtocolo($objAtividadeDTO->getDblIdProtocolo());
              $objAcessoDTO->setNumIdUsuario($objAtividadeDTO->getNumIdUsuario());
              $objAcessoDTO->setStrSiglaUsuario($objAtividadeDTO->getStrSiglaUsuario());
              $objAcessoDTO->setStrNomeUsuario($objAtividadeDTO->getStrNomeUsuario());
              $objAcessoDTO->setStrStaCredencialUnidade(ProtocoloRN::$TCU_FINALIZADA);
              $arrObjAcessoDTO[$dblIdProtocolo][] = $objAcessoDTO;
            }
          }
        }

        foreach ($arrRet as $objProcedimentoDTO) {
          if (isset($arrObjAcessoDTO[$objProcedimentoDTO->getDblIdProcedimento()])) {
            $objProcedimentoDTO->setArrObjAcessoDTO($arrObjAcessoDTO[$objProcedimentoDTO->getDblIdProcedimento()]);
          } else {
            $objProcedimentoDTO->setArrObjAcessoDTO(array());
          }
        }
      }

      return $arrRet;
    } catch (Exception $e) {
      throw new InfraException('Erro pesquisando acervo de processos sigilosos da unidade.', $e);
    }
  }


  protected function associarPlanoTrabalhoControlado(ProcedimentoDTO $parObjProcedimentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_plano_associar', __METHOD__, $parObjProcedimentoDTO);

      $objProcedimentoDTOBanco = new ProcedimentoDTO();
      $objProcedimentoDTOBanco->retDblIdProcedimento();
      $objProcedimentoDTOBanco->retNumIdPlanoTrabalho();
      $objProcedimentoDTOBanco->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());

      $objProcedimentoDTOBanco = $this->consultarRN0201($objProcedimentoDTOBanco);

      $objProcedimentoBD = new ProcedimentoBD(BancoSEI::getInstance());

      if ($parObjProcedimentoDTO->getNumIdPlanoTrabalho() == null) {

        if ($objProcedimentoDTOBanco->getNumIdPlanoTrabalho() != null) {

          $objAndamentoPlanoTrabalhoDTO = new AndamentoPlanoTrabalhoDTO();
          $objAndamentoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($objProcedimentoDTOBanco->getNumIdPlanoTrabalho());
          $objAndamentoPlanoTrabalhoDTO->setDblIdProcedimento($objProcedimentoDTOBanco->getDblIdProcedimento());
          $objAndamentoPlanoTrabalhoDTO->setNumIdTarefaPlanoTrabalho(TarefaPlanoTrabalhoRN::$TPT_REMOCAO_ASSOCIACAO_PLANO_TRABALHO);

          $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
          $objAtributoAndamPlanoTrabDTO->setStrChave('PLANO_TRABALHO');
          $objAtributoAndamPlanoTrabDTO->setStrValor(null);
          $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($objProcedimentoDTOBanco->getNumIdPlanoTrabalho());
          $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

          $objAndamentoPlanoTrabalhoDTO->setArrObjAtributoAndamPlanoTrabDTO($arrObjAtributoAndamPlanoTrabDTO);

          $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
          $objAndamentoPlanoTrabalhoRN->lancar($objAndamentoPlanoTrabalhoDTO);

          $objProcedimentoDTOBanco->setNumIdPlanoTrabalho(null);
          $objProcedimentoBD->alterar($objProcedimentoDTOBanco);
        }

      } else {

        if ($objProcedimentoDTOBanco->getNumIdPlanoTrabalho() == null || $objProcedimentoDTOBanco->getNumIdPlanoTrabalho() != $parObjProcedimentoDTO->getNumIdPlanoTrabalho()) {

          $objAndamentoPlanoTrabalhoDTO = new AndamentoPlanoTrabalhoDTO();
          $objAndamentoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($parObjProcedimentoDTO->getNumIdPlanoTrabalho());
          $objAndamentoPlanoTrabalhoDTO->setDblIdProcedimento($objProcedimentoDTOBanco->getDblIdProcedimento());
          $objAndamentoPlanoTrabalhoDTO->setNumIdTarefaPlanoTrabalho(TarefaPlanoTrabalhoRN::$TPT_ASSOCIACAO_PLANO_TRABALHO);

          $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
          $objAtributoAndamPlanoTrabDTO->setStrChave('PLANO_TRABALHO');
          $objAtributoAndamPlanoTrabDTO->setStrValor(null);
          $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($parObjProcedimentoDTO->getNumIdPlanoTrabalho());
          $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

          $objAndamentoPlanoTrabalhoDTO->setArrObjAtributoAndamPlanoTrabDTO($arrObjAtributoAndamPlanoTrabDTO);

          $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
          $objAndamentoPlanoTrabalhoRN->lancar($objAndamentoPlanoTrabalhoDTO);

          $objProcedimentoDTOBanco->setNumIdPlanoTrabalho($parObjProcedimentoDTO->getNumIdPlanoTrabalho());
          $objProcedimentoBD->alterar($objProcedimentoDTOBanco);

        }
      }

    } catch (Exception $e) {
      throw new InfraException('Erro associando Processo com Plano de Trabalho.', $e);
    }
  }
}

?>