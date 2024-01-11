<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 31/01/2008 - criado por marcio_db
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../SEI.php';

class ProtocoloRN extends InfraRN {

  //TP = Tipo Procedimento (sta_protocolo)
  public static $TP_PROCEDIMENTO = 'P';
  public static $TP_DOCUMENTO_GERADO = 'G';
  public static $TP_DOCUMENTO_RECEBIDO = 'R';

  //NA = Nível de Acesso (sta_nivel_acesso_*)
  public static $NA_PUBLICO = '0';
  public static $NA_RESTRITO = '1';
  public static $NA_SIGILOSO = '2';

  //TE = Tipo Estado (sta_estado)
  public static $TE_NORMAL = '0';
  public static $TE_PROCEDIMENTO_SOBRESTADO = '1';
  public static $TE_DOCUMENTO_CANCELADO = '2';
  public static $TE_PROCEDIMENTO_ANEXADO = '3';
  public static $TE_PROCEDIMENTO_BLOQUEADO = '4';

  //CS = Grau Sigiloso (sta_grau_sigilo)
  public static $TGS_ULTRASSECRETO = 'U';
  public static $TGS_SECRETO = 'S';
  public static $TGS_RESERVADO = 'R';

  //TAP = Tipo Acesso Procedimento
  public static $TAP_TODOS = 'T';
  public static $TAP_TODOS_EXCETO_SIGILOSOS_SEM_ACESSO = 'E';
  public static $TAP_AUTORIZADO = 'A';

  //TMN =  Tipo Mudança de Nível
  public static $TMN_CADASTRO = '1';
  public static $TMN_ALTERACAO = '2';
  public static $TMN_EXCLUSAO = '3';
  public static $TMN_ANEXACAO = '4';
  public static $TMN_DESANEXACAO = '5';
  public static $TMN_MOVIMENTACAO = '6';

  //TPP = Tipo Pesquisa Protocolo
  public static $TPP_TODOS = 'T';
  public static $TPP_PROCEDIMENTOS = 'P';
  public static $TPP_DOCUMENTOS = 'D';
  public static $TPP_DOCUMENTOS_GERADOS = 'G';
  public static $TPP_DOCUMENTOS_RECEBIDOS = 'R';

  //TASU = Tipo Acesso Sigiloso Unidade
  public static $TASU_TODOS = 'T';
  public static $TASU_SIM = 'S';
  public static $TASU_NAO = 'N';

  //TCU = Tipo Credencial Unidade
  public static $TCU_ATIVA = 'A';
  public static $TCU_INATIVA = 'I';
  public static $TCU_FINALIZADA = 'F';

  //CA = Código de Acesso
  public static $CA_NEGADO = -1;

  public static $CA_SIGILOSO_NEGADO = -100;
  public static $CA_SIGILOSO_PROCESSO = 100;
  public static $CA_SIGILOSO_DOC_EXTERNO = 110;
  public static $CA_SIGILOSO_DOC_GERADO_UNIDADE = 120;
  public static $CA_SIGILOSO_CREDENCIAL_ASSINATURA = 130;
  public static $CA_SIGILOSO_ASSINADO = 140;
  public static $CA_SIGILOSO_FORMULARIO_AUTOMATICO = 150;
  public static $CA_SIGILOSO_MODULO = 160;

  public static $CA_RESTRITO_NEGADO = -200;
  public static $CA_RESTRITO_PROCESSO = 200;
  public static $CA_RESTRITO_DOC_EXTERNO = 210;
  public static $CA_RESTRITO_DOC_GERADO_UNIDADE = 220;
  public static $CA_RESTRITO_ASSINADO = 230;
  public static $CA_RESTRITO_FORMULARIO_AUTOMATICO = 240;
  public static $CA_RESTRITO_MODULO = 250;

  public static $CA_PUBLICO_NEGADO = -300;
  public static $CA_PUBLICO_PROCESSO = 300;
  public static $CA_PUBLICO_DOC_EXTERNO = 310;
  public static $CA_PUBLICO_DOC_GERADO_UNIDADE = 320;
  public static $CA_PUBLICO_ASSINADO = 330;
  public static $CA_PUBLICO_FORMULARIO_AUTOMATICO = 340;
  public static $CA_PUBLICO_MODULO = 350;

  public static $CA_UNIDADE_PROTOCOLO = 400;
  public static $CA_UNIDADE_ARQUIVAMENTO = 500;
  public static $CA_BLOCO = 600;
  public static $CA_OUVIDORIA_RESTRITO = -700;
  public static $CA_MODULO_NEGADO = -800;
  public static $CA_DOCUMENTO_CANCELADO = -900;
  public static $CA_DOCUMENTO_PUBLICADO = 1000;

  public static $CA_ELIMINADO = -9000;

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  protected function gerarRN0154Controlado(ProtocoloDTO $objProtocoloDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_gerar', __METHOD__, $objProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objProtocoloDTO->setDblIdProtocolo(null);

      if ($objProtocoloDTO->isSetDblIdProtocoloAgrupador()) {
        $objInfraException->adicionarValidacao('Número do protocolo agrupador não pode ser informado na geração.');
      }

      $this->validarStrStaProtocoloRN0212($objProtocoloDTO, $objInfraException);
      $this->validarArrRelProtocoloAssuntoRN0216($objProtocoloDTO, $objInfraException);
      $this->validarArrParticipanteRN0572($objProtocoloDTO, $objInfraException);
      $this->validarArrObjObservacaoRN0573($objProtocoloDTO, $objInfraException);
      $this->validarArrAnexoRN0227($objProtocoloDTO, $objInfraException);
      $this->validarArrObjRelProtocoloAtributoDTO($objProtocoloDTO, $objInfraException);
      $this->validarNumIdUnidadeGeradoraRN0213($objProtocoloDTO, $objInfraException);
      $this->validarNumIdUsuarioGeradorRN0214($objProtocoloDTO, $objInfraException);
      $this->validarDtaGeracaoRN0215($objProtocoloDTO, $objInfraException);
      $this->validarStrDescricaoRN1229($objProtocoloDTO, $objInfraException);

      if ($objProtocoloDTO->isSetStrStaEstado()) {
        $objInfraException->adicionarValidacao('Estado do protocolo não pode ser informado na geração.');
      }

      $this->validarStrStaNivelAcessoLocalRN0685($objProtocoloDTO, $objInfraException);

      if (!$objProtocoloDTO->isSetStrStaGrauSigilo()) {
        $objProtocoloDTO->setStrStaGrauSigilo(null);
      }
      $this->validarStrStaGrauSigilo($objProtocoloDTO, $objInfraException);

      if (!$objProtocoloDTO->isSetNumIdHipoteseLegal()) {
        $objProtocoloDTO->setNumIdHipoteseLegal(null);
      }
      $this->validarNumIdHipoteseLegal($objProtocoloDTO, $objInfraException);

      $objProtocoloDTO->setStrStaEstado(self::$TE_NORMAL);

      if ($objProtocoloDTO->isSetStrIdProtocoloFederacao()) {
        $this->validarStrIdProtocoloFederacao($objProtocoloDTO, $objInfraException);
      } else {
        $objProtocoloDTO->setStrIdProtocoloFederacao(null);
      }

      $objProtocoloDTO->setDtaInclusao(InfraData::getStrDataAtual());
      $objProtocoloDTO->setStrSinEliminado('N');

      if (!InfraString::isBolVazia($objProtocoloDTO->getStrProtocoloFormatado())) {
        if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
          $this->validarProtocoloInformado($objProtocoloDTO, $objInfraException);
        } else {
          $objInfraException->adicionarValidacao('Protocolo do documento não pode ser informado na geração.');
        }
      } else {
        if ($objProtocoloDTO->getStrStaProtocolo() == self::$TP_PROCEDIMENTO) {
          $objProtocoloDTO->setStrProtocoloFormatado($this->gerarNumeracaoProcesso());
        } else {
          $objProtocoloDTO->setStrProtocoloFormatado($this->gerarNumeracaoDocumento());
        }
      }

      $objProtocoloDTO->setDblIdProtocolo($this->gerarNumeracaoInterna());

      $this->validarStrProtocoloFormatadoRN0211($objProtocoloDTO, $objInfraException);

      $this->formatarCamposPesquisa($objProtocoloDTO);

      if ($objProtocoloDTO->getStrStaProtocolo() == self::$TP_PROCEDIMENTO) {
        $objProtocoloDTO->setStrStaNivelAcessoGlobal($objProtocoloDTO->getStrStaNivelAcessoLocal());
      } else {
        $objMudarNivelAcessoDTO = new MudarNivelAcessoDTO();
        $objMudarNivelAcessoDTO->setStrStaOperacao(self::$TMN_CADASTRO);
        $objMudarNivelAcessoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProcedimento());
        $objMudarNivelAcessoDTO->setStrStaNivel($objProtocoloDTO->getStrStaNivelAcessoLocal());
        $objProtocoloDTO->setStrStaNivelAcessoGlobal($this->mudarNivelAcesso($objMudarNivelAcessoDTO));
      }

      $objProtocoloDTO->setStrStaNivelAcessoOriginal(null);

      $objInfraException->lancarValidacoes();

      InfraCodigoBarras::gerar($objProtocoloDTO->getStrProtocoloFormatadoPesquisa(), DIR_SEI_TEMP, InfraCodigoBarras::$TIPO_CODE39, InfraCodigoBarras::$COR_PRETO, 1, 26, 0,
        13 * strlen($objProtocoloDTO->getStrProtocoloFormatadoPesquisa()) + 30, 30, InfraCodigoBarras::$FORMATO_PNG);
      $strArquivoCodigoBarras = DIR_SEI_TEMP . '/code39_' . $objProtocoloDTO->getStrProtocoloFormatadoPesquisa() . '.png';
      $fp = fopen($strArquivoCodigoBarras, "r");
      $imgCodigoBarras = fread($fp, filesize($strArquivoCodigoBarras));
      fclose($fp);
      unlink($strArquivoCodigoBarras);
      $objProtocoloDTO->setStrCodigoBarras(base64_encode($imgCodigoBarras));

      $objProtocoloDTO->setDblIdProtocoloAgrupador($objProtocoloDTO->getDblIdProtocolo());

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $objProtocoloBD->cadastrar($objProtocoloDTO);

      $arrObjObservacaoDTO = $objProtocoloDTO->getArrObjObservacaoDTO();
      foreach ($arrObjObservacaoDTO as $objObservacaoDTO) {
        if (!InfraString::isBolVazia($objObservacaoDTO->getStrDescricao())) {
          $objObservacaoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
          $objObservacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objObservacaoRN = new ObservacaoRN();
          $objObservacaoRN->cadastrarRN0222($objObservacaoDTO);
        }
      }

      $objParticipanteRN = new ParticipanteRN();
      $arrParticipantes = $objProtocoloDTO->getArrObjParticipanteDTO();
      for ($i = 0; $i < InfraArray::contar($arrParticipantes); $i++) {
        $arrParticipantes[$i]->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
        $arrParticipantes[$i]->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objParticipanteRN->cadastrarRN0170($arrParticipantes[$i]);
      }

      $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
      $arrAssuntos = $objProtocoloDTO->getArrObjRelProtocoloAssuntoDTO();
      for ($i = 0; $i < InfraArray::contar($arrAssuntos); $i++) {
        $arrAssuntos[$i]->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
        $arrAssuntos[$i]->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        if ($objProtocoloDTO->getStrStaProtocolo() == self::$TP_PROCEDIMENTO) {
          $arrAssuntos[$i]->setDblIdProtocoloProcedimento($objProtocoloDTO->getDblIdProtocolo());
        } else {
          $arrAssuntos[$i]->setDblIdProtocoloProcedimento($objProtocoloDTO->getDblIdProcedimento());
        }
        $objRelProtocoloAssuntoRN->cadastrarRN0171($arrAssuntos[$i]);
      }

      if ($objProtocoloDTO->isSetArrObjRelProtocoloAtributoDTO()) {
        $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
        foreach ($objProtocoloDTO->getArrObjRelProtocoloAtributoDTO() as $objRelProtocoloAtributoDTO) {
          $objRelProtocoloAtributoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
          $objRelProtocoloAtributoRN->cadastrar($objRelProtocoloAtributoDTO);
        }
      }

      if ($objProtocoloDTO->isSetArrObjAnexoDTO()) {
        $objAnexoRN = new AnexoRN();
        $arrAnexos = $objProtocoloDTO->getArrObjAnexoDTO();
        for ($i = 0; $i < InfraArray::contar($arrAnexos); $i++) {
          $arrAnexos[$i]->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
          $arrAnexos[$i]->setNumIdBaseConhecimento(null);
          $arrAnexos[$i]->setNumIdProjeto(null);
          $arrAnexos[$i]->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
          $arrAnexos[$i]->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $arrAnexos[$i]->setStrSinAtivo('S');

          if (!$arrAnexos[$i]->isSetNumIdAnexoOrigem()) {
            $objAnexoRN->cadastrarRN0172($arrAnexos[$i]);
          } else {
            $objAnexoRN->associar($arrAnexos[$i]);
          }
        }
      }

      //$objInfraException->lancarValidacao('FIM geracao');

      //Auditoria
      $ret = new ProtocoloDTO();
      $ret->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
      $ret->setStrProtocoloFormatado($objProtocoloDTO->getStrProtocoloFormatado());
      $ret->setStrStaNivelAcessoGlobal($objProtocoloDTO->getStrStaNivelAcessoGlobal());

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro gerando protocolo.', $e);
    }
  }

  protected function alterarRN0203Controlado(ProtocoloDTO $objProtocoloDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_alterar', __METHOD__, $objProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objProtocoloDTO->isSetDblIdProtocoloAgrupador()) {
        $objInfraException->adicionarValidacao('Número do protocolo agrupador não pode ser alterado.');
      }

      if (!$objProtocoloDTO->isSetStrSinLancarAndamento()) {
        $objProtocoloDTO->setStrSinLancarAndamento('S');
      }

      //busca dados usados em validações
      $objProtocoloDTOBanco = new ProtocoloDTO();
      $objProtocoloDTOBanco->retStrIdProtocoloFederacao();
      $objProtocoloDTOBanco->retStrStaProtocolo();
      $objProtocoloDTOBanco->retStrProtocoloFormatado();
      $objProtocoloDTOBanco->retStrStaNivelAcessoLocal();
      $objProtocoloDTOBanco->retStrStaNivelAcessoGlobal();
      $objProtocoloDTOBanco->retNumIdHipoteseLegal();
      $objProtocoloDTOBanco->retStrStaGrauSigilo();
      $objProtocoloDTOBanco->retDtaGeracao();
      $objProtocoloDTOBanco->retStrSinEliminado();
      $objProtocoloDTOBanco->retDblIdProcedimentoDocumento();
      $objProtocoloDTOBanco->retNumIdUnidadeGeradora();
      $objProtocoloDTOBanco->retStrSiglaUnidadeGeradora();
      $objProtocoloDTOBanco->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
      $objProtocoloDTOBanco = $this->consultarRN0186($objProtocoloDTOBanco);

      if ($objProtocoloDTO->isSetStrIdProtocoloFederacao() && $objProtocoloDTO->getStrIdProtocoloFederacao() != $objProtocoloDTOBanco->getStrIdProtocoloFederacao()) {
        $objInfraException->adicionarValidacao('Identificador do protocolo no SEI Federação não pode ser alterado.');
      }

      if ($objProtocoloDTO->isSetStrStaProtocolo() && $objProtocoloDTO->getStrStaProtocolo() != $objProtocoloDTOBanco->getStrStaProtocolo()) {
        $objInfraException->adicionarValidacao('Tipo do protocolo não pode ser alterado.');
      } else {
        //preenche para uso em validações
        $objProtocoloDTO->setStrStaProtocolo($objProtocoloDTOBanco->getStrStaProtocolo());
      }

      if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO || $objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
        $objProtocoloDTO->setDblIdProcedimentoDocumento($objProtocoloDTOBanco->getDblIdProcedimentoDocumento());
      }

      if ($objProtocoloDTO->isSetStrProtocoloFormatado() && $objProtocoloDTO->getStrProtocoloFormatado() != $objProtocoloDTOBanco->getStrProtocoloFormatado()) {
        if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
          $this->validarProtocoloInformado($objProtocoloDTO, $objInfraException);

          $this->validarStrProtocoloFormatadoRN0211($objProtocoloDTO, $objInfraException);

          $this->formatarCamposPesquisa($objProtocoloDTO);

          $arrObjAtributoAndamentoDTO = array();

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('PROTOCOLO_ANTERIOR');
          $objAtributoAndamentoDTO->setStrValor($objProtocoloDTOBanco->getStrProtocoloFormatado());
          $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getDblIdProtocolo());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('PROTOCOLO_ATUAL');
          $objAtributoAndamentoDTO->setStrValor($objProtocoloDTO->getStrProtocoloFormatado());
          $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getDblIdProtocolo());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_PROTOCOLO_PROCESSO);

          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
        } else {
          $objInfraException->adicionarValidacao('Protocolo do documento não pode ser alterado.');
        }
      } else {
        $objProtocoloDTO->setStrProtocoloFormatado($objProtocoloDTOBanco->getStrProtocoloFormatado());
        $this->formatarCamposPesquisa($objProtocoloDTO);
      }

      if ($objProtocoloDTO->isSetDtaInclusao()) {
        $objInfraException->adicionarValidacao('Data de inclusão do protocolo não pode ser alterada.');
      }

      if ($objProtocoloDTO->isSetNumIdUnidadeGeradora()) {
        $objInfraException->adicionarValidacao('Unidade geradora do protocolo não pode ser alterada.');
      }

      if ($objProtocoloDTO->isSetNumIdUsuarioGerador()) {
        $objInfraException->adicionarValidacao('Usuário gerador do protocolo não pode ser alterado');
      }

      if ($objProtocoloDTO->isSetDtaGeracao() && $objProtocoloDTO->getDtaGeracao() != $objProtocoloDTOBanco->getDtaGeracao()) {
        $this->validarDtaGeracaoRN0215($objProtocoloDTO, $objInfraException);

        if ($objProtocoloDTO->isSetStrSinEliminado()) {
          $objInfraException->adicionarValidacao('Sinalizador de eliminação do protocolo não pode ser alterado');
        }


        if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
          $arrObjAtributoAndamentoDTO = array();

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('DATA_ANTERIOR');
          $objAtributoAndamentoDTO->setStrValor($objProtocoloDTOBanco->getDtaGeracao());
          $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getDblIdProtocolo());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('DATA_ATUAL');
          $objAtributoAndamentoDTO->setStrValor($objProtocoloDTO->getDtaGeracao());
          $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getDblIdProtocolo());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_DATA_AUTUACAO_PROCESSO);

          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
        }
      }

      if ($objProtocoloDTO->isSetStrStaNivelAcessoGlobal()) {
        $objInfraException->adicionarValidacao('Nível de acesso global não pode ser alterado');
      }

      if ($objProtocoloDTO->isSetStrStaNivelAcessoLocal()) {
        $this->validarStrStaNivelAcessoLocalRN0685($objProtocoloDTO, $objInfraException);

        if ($objProtocoloDTO->getStrStaNivelAcessoLocal() != $objProtocoloDTOBanco->getStrStaNivelAcessoLocal()) {
          if ($objProtocoloDTO->getStrSinLancarAndamento() == 'S') {
            $arrObjAtributoAndamentoDTO = array();
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('NIVEL_ACESSO');
            $objAtributoAndamentoDTO->setStrValor(null);
            $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getStrStaNivelAcessoLocal());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            if ($objProtocoloDTOBanco->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO || $objProtocoloDTOBanco->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
              $objAtributoAndamentoDTO->setStrValor($objProtocoloDTO->getStrProtocoloFormatado());
              $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getDblIdProtocolo());
              $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
            }

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

            if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
              $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
              $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_NIVEL_ACESSO_PROCESSO);
            } else {
              $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProcedimentoDocumento());
              $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_NIVEL_ACESSO_DOCUMENTO);
            }

            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

            $objAtividadeRN = new AtividadeRN();
            $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
          }

          $objMudarNivelAcessoDTO = new MudarNivelAcessoDTO();
          $objMudarNivelAcessoDTO->setStrStaOperacao(self::$TMN_ALTERACAO);
          $objMudarNivelAcessoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
          $objMudarNivelAcessoDTO->setStrStaNivel($objProtocoloDTO->getStrStaNivelAcessoLocal());
          $objMudarNivelAcessoDTO->setStrSinLancarAndamento($objProtocoloDTO->getStrSinLancarAndamento());
          $objProtocoloDTO->setStrStaNivelAcessoGlobal($this->mudarNivelAcesso($objMudarNivelAcessoDTO));
        }
      } else {
        $objProtocoloDTO->setStrStaNivelAcessoLocal($objProtocoloDTOBanco->getStrStaNivelAcessoLocal());
      }

      if ($objProtocoloDTO->isSetStrStaGrauSigilo()) {
        $this->validarStrStaGrauSigilo($objProtocoloDTO, $objInfraException);

        if ($objProtocoloDTO->getStrStaGrauSigilo() != $objProtocoloDTOBanco->getStrStaGrauSigilo() && $objProtocoloDTO->getStrStaNivelAcessoLocal() == ProtocoloRN::$NA_SIGILOSO) {
          $arrObjAtributoAndamentoDTO = array();
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('GRAU_SIGILO');
          $objAtributoAndamentoDTO->setStrValor(null);
          $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getStrStaGrauSigilo());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO || $objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
            $objAtributoAndamentoDTO->setStrValor($objProtocoloDTO->getStrProtocoloFormatado());
            $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getDblIdProtocolo());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
          }

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

          if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
            $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_GRAU_SIGILO_PROCESSO);
          } else {
            $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProcedimentoDocumento());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_GRAU_SIGILO_DOCUMENTO);
          }

          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
        }
      } else {
        $objProtocoloDTO->setStrStaGrauSigilo($objProtocoloDTOBanco->getStrStaGrauSigilo());
      }

      if ($objProtocoloDTO->isSetNumIdHipoteseLegal()) {
        $this->validarNumIdHipoteseLegal($objProtocoloDTO, $objInfraException);

        if ($objProtocoloDTO->getNumIdHipoteseLegal() != $objProtocoloDTOBanco->getNumIdHipoteseLegal() && ($objProtocoloDTO->getStrStaNivelAcessoLocal() == ProtocoloRN::$NA_SIGILOSO || $objProtocoloDTO->getStrStaNivelAcessoLocal() == ProtocoloRN::$NA_RESTRITO)) {
          $arrObjAtributoAndamentoDTO = array();
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('HIPOTESE_LEGAL');
          $objAtributoAndamentoDTO->setStrValor(null);
          $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getNumIdHipoteseLegal());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO || $objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
            $objAtributoAndamentoDTO->setStrValor($objProtocoloDTO->getStrProtocoloFormatado());
            $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getDblIdProtocolo());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
          }

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

          if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
            $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_HIPOTESE_LEGAL_PROCESSO);
          } else {
            $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProcedimentoDocumento());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_HIPOTESE_LEGAL_DOCUMENTO);
          }

          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
        }
      } else {
        $objProtocoloDTO->setNumIdHipoteseLegal($objProtocoloDTOBanco->getNumIdHipoteseLegal());
      }


      if ($objProtocoloDTO->isSetArrObjRelProtocoloAssuntoDTO()) {
        $this->validarArrRelProtocoloAssuntoRN0216($objProtocoloDTO, $objInfraException);
      }

      if ($objProtocoloDTO->isSetArrObjRelProtocoloAtributoDTO()) {
        $this->validarArrObjRelProtocoloAtributoDTO($objProtocoloDTO, $objInfraException);
      }

      if ($objProtocoloDTO->isSetArrObjParticipanteDTO()) {
        $this->validarArrParticipanteRN0572($objProtocoloDTO, $objInfraException);
      }

      if ($objProtocoloDTO->isSetArrObjObservacaoDTO()) {
        $this->validarArrObjObservacaoRN0573($objProtocoloDTO, $objInfraException);
      }

      if ($objProtocoloDTO->isSetArrObjAnexoDTO()) {
        $this->validarArrAnexoRN0227($objProtocoloDTO, $objInfraException);
      }

      if ($objProtocoloDTO->isSetStrDescricao()) {
        $this->validarStrDescricaoRN1229($objProtocoloDTO, $objInfraException);
      }

      if ($objProtocoloDTO->isSetStrStaEstado()) {
        $this->validarStrStaEstadoRN1016($objProtocoloDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      if ($objProtocoloDTO->isSetArrObjObservacaoDTO()) {
        $objObservacaoRN = new ObservacaoRN();
        $arrObjObservacaoDTO = $objProtocoloDTO->getArrObjObservacaoDTO();

        foreach ($arrObjObservacaoDTO as $objObservacaoDTO) {
          $dto = new ObservacaoDTO();
          $dto->retStrDescricao();
          $dto->retNumIdObservacao();
          $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $dto->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
          $dto = $objObservacaoRN->consultarRN0221($dto);

          if ($dto !== null) {
            $objObservacaoDTO->setNumIdObservacao($dto->getNumIdObservacao());
          } else {
            $objObservacaoDTO->setNumIdObservacao(null);
          }
          $objObservacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objObservacaoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());

          if (InfraString::isBolVazia($objObservacaoDTO->getStrDescricao())) {
            if ($objObservacaoDTO->getNumIdObservacao() !== null) {
              $objObservacaoRN->excluirRN0220(array($objObservacaoDTO));
            }
          } else {
            if ($objObservacaoDTO->getNumIdObservacao() === null) {
              $objObservacaoRN->cadastrarRN0222($objObservacaoDTO);
            } else {
              if ($dto->getStrDescricao() != $objObservacaoDTO->getStrDescricao()) {
                $objObservacaoRN->alterarRN0749($objObservacaoDTO);
              }
            }
          }
        }
      }

      if ($objProtocoloDTO->isSetArrObjParticipanteDTO()) {
        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retNumIdParticipante();
        $objParticipanteDTO->retNumIdContato();
        $objParticipanteDTO->retNumIdUnidade();
        $objParticipanteDTO->retStrSiglaUnidade();
        $objParticipanteDTO->retStrStaParticipacao();
        $objParticipanteDTO->retStrNomeContato();
        $objParticipanteDTO->retNumSequencia();
        $objParticipanteDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());


        $objParticipanteRN = new ParticipanteRN();
        $arrParticipantesAntigos = $objParticipanteRN->listarRN0189($objParticipanteDTO);

        $arrParticipantesNovos = $objProtocoloDTO->getArrObjParticipanteDTO();

        $arrRemocao = array();
        foreach ($arrParticipantesAntigos as $participanteAntigo) {
          if ($participanteAntigo->getStrStaParticipacao() != ParticipanteRN::$TP_ACESSO_EXTERNO) {
            $flagRemover = true;
            foreach ($arrParticipantesNovos as $participanteNovo) {
              if ($participanteAntigo->getNumIdContato() == $participanteNovo->getNumIdContato() && $participanteAntigo->getStrStaParticipacao() == $participanteNovo->getStrStaParticipacao()) {
                $flagRemover = false;
                break;
              }
            }
            if ($flagRemover) {
              $arrRemocao[] = $participanteAntigo;
            }
          }
        }

        foreach ($arrRemocao as $participanteRemover) {
          if ($participanteRemover->getNumIdUnidade() <> SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
            $strPapel = '';
            if ($participanteRemover->getStrStaParticipacao() == ParticipanteRN::$TP_INTERESSADO) {
              $strPapel = 'interessado';
            } else {
              if ($participanteRemover->getStrStaParticipacao() == ParticipanteRN::$TP_DESTINATARIO) {
                $strPapel = 'destinatário';
              } else {
                if ($participanteRemover->getStrStaParticipacao() == ParticipanteRN::$TP_REMETENTE) {
                  $strPapel = 'remetente';
                } else {
                  $strPapel = 'assinante';
                }
              }
            }
            $objInfraException->lancarValidacao('O ' . $strPapel . ' "' . $participanteRemover->getStrNomeContato() . '" não pode ser excluído porque foi adicionado por outra unidade (' . $participanteRemover->getStrSiglaUnidade() . ').');
          }
        }

        if (InfraArray::contar($arrRemocao)) {
          $objParticipanteRN->excluirRN0223($arrRemocao);
        }

        foreach ($arrParticipantesNovos as $participanteNovo) {
          $flagCadastrar = true;
          $objParticipanteDTOAntigo = null;
          foreach ($arrParticipantesAntigos as $participanteAntigo) {
            if ($participanteNovo->getNumIdContato() == $participanteAntigo->getNumIdContato() && $participanteNovo->getStrStaParticipacao() == $participanteAntigo->getStrStaParticipacao()) {
              $objParticipanteDTOAntigo = $participanteAntigo;
              $flagCadastrar = false;
              break;
            }
          }
          if ($flagCadastrar) {
            $participanteNovo->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
            $participanteNovo->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objParticipanteRN->cadastrarRN0170($participanteNovo);
          } else {
            if ($participanteNovo->getNumSequencia() != $objParticipanteDTOAntigo->getNumSequencia()) {
              //altera sequencia
              $participanteNovo->setNumIdParticipante($participanteAntigo->getNumIdParticipante());
              //garante que não vai alterar a unidade
              $participanteNovo->unSetNumIdUnidade();
              $objParticipanteRN->alterarRN0889($participanteNovo);
            }
          }
        }
      }

      if ($objProtocoloDTO->isSetArrObjRelProtocoloAssuntoDTO()) {
        //Ao ativar uma nova tabela de assuntos um assunto pode estar ligado mais de uma vez ao mesmo protocolo (devido aos mapeamentos).
        //Na interface exibe apenas uma vez mas internamente existem varios assuntos proxy associados ao mesmo protocolo.
        //Os assuntos nao sao removidos na ativacao pois podem ter sido adicionados por unidades diferentes.

        $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
        $objRelProtocoloAssuntoDTO->retDblIdProtocolo();
        $objRelProtocoloAssuntoDTO->retNumIdAssuntoProxy();
        $objRelProtocoloAssuntoDTO->retNumIdAssunto();
        $objRelProtocoloAssuntoDTO->retNumIdUnidade();
        $objRelProtocoloAssuntoDTO->retStrSiglaUnidade();
        $objRelProtocoloAssuntoDTO->retNumSequencia();
        $objRelProtocoloAssuntoDTO->retStrCodigoEstruturadoAssunto();
        $objRelProtocoloAssuntoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());

        $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
        $arrAssuntosAntigos = InfraArray::indexarArrInfraDTO($objRelProtocoloAssuntoRN->listarRN0188($objRelProtocoloAssuntoDTO), 'IdAssuntoProxy');
        $arrAssuntosNovos = InfraArray::indexarArrInfraDTO($objProtocoloDTO->getArrObjRelProtocoloAssuntoDTO(), 'IdAssunto');

        $arrAssuntosPermitidosExclusao = array();
        foreach ($arrAssuntosAntigos as $assuntoAntigo) {
          if ($assuntoAntigo->getNumIdUnidade() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
            $arrAssuntosPermitidosExclusao[] = $assuntoAntigo->getNumIdAssunto();
          }
        }

        $arrRemocao = array();
        foreach ($arrAssuntosAntigos as $numIdAssuntoAntigoProxy => $assuntoAntigo) {
          $numIdAssuntoAntigo = $assuntoAntigo->getNumIdAssunto();
          if (!isset($arrAssuntosNovos[$numIdAssuntoAntigo])) {
            if (!in_array($numIdAssuntoAntigo, $arrAssuntosPermitidosExclusao)) {
              $objInfraException->lancarValidacao('O assunto "' . $assuntoAntigo->getStrCodigoEstruturadoAssunto() . '" não pode ser excluído porque foi adicionado por outra unidade (' . $assuntoAntigo->getStrSiglaUnidade() . ').');
            } else {
              $arrRemocao[] = $assuntoAntigo;
            }
          }
        }

        $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();
        foreach ($arrRemocao as $assuntoRemocao) {
          $objAvaliacaoDocumentalDTO_Avaliacao = new AvaliacaoDocumentalDTO();
          $objAvaliacaoDocumentalDTO_Avaliacao->setNumIdAssuntoProxy($assuntoRemocao->getNumIdAssuntoProxy());
          $objAvaliacaoDocumentalDTO_Avaliacao->setDblIdProcedimento($objProtocoloDTO->getDblIdProtocolo());
          if ($objAvaliacaoDocumentalRN->contar($objAvaliacaoDocumentalDTO_Avaliacao) > 0) {
            $objInfraException->lancarValidacao('O assunto "' . $assuntoAntigo->getStrCodigoEstruturadoAssunto() . '" não pode ser excluído porque foi adicionado na avaliação documental deste processo.');
          }
        }

        $objRelProtocoloAssuntoRN->excluirRN0224($arrRemocao);

        foreach ($arrAssuntosNovos as $numIdAssuntoNovo => $assuntoNovo) {
          $arrObjAssuntoAntigoDTO = array();
          foreach ($arrAssuntosAntigos as $assuntoAntigo) {
            if ($assuntoAntigo->getNumIdAssunto() == $numIdAssuntoNovo) {
              $arrObjAssuntoAntigoDTO[] = $assuntoAntigo;
            }
          }

          if (count($arrObjAssuntoAntigoDTO) == 0) {
            $assuntoNovo->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
            $assuntoNovo->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            if ($objProtocoloDTO->getStrStaProtocolo() == self::$TP_PROCEDIMENTO) {
              $assuntoNovo->setDblIdProtocoloProcedimento($objProtocoloDTO->getDblIdProtocolo());
            } else {
              $assuntoNovo->setDblIdProtocoloProcedimento($objProtocoloDTO->getDblIdProcedimentoDocumento());
            }
            $objRelProtocoloAssuntoRN->cadastrarRN0171($assuntoNovo);
          } else {
            foreach ($arrObjAssuntoAntigoDTO as $objAssuntoAntigoDTO) {
              if ($assuntoNovo->getNumSequencia() != $objAssuntoAntigoDTO->getNumSequencia()) {
                $objAssuntoAntigoDTO->setNumSequencia($assuntoNovo->getNumSequencia());
                $objRelProtocoloAssuntoRN->alterarRN1177($objAssuntoAntigoDTO);
              }
            }
          }
        }
      }

      if ($objProtocoloDTO->isSetArrObjRelProtocoloAtributoDTO()) {
        $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
        $objRelProtocoloAtributoDTO->retNumIdAtributo();
        $objRelProtocoloAtributoDTO->retStrValor();
        $objRelProtocoloAtributoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());

        $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
        $arrObjRelProtocoloAtributoDTOAntigos = InfraArray::indexarArrInfraDTO($objRelProtocoloAtributoRN->listar($objRelProtocoloAtributoDTO), 'IdAtributo');

        foreach ($objProtocoloDTO->getArrObjRelProtocoloAtributoDTO() as $objRelProtocoloAtributoDTONovo) {
          $objRelProtocoloAtributoDTONovo->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());

          if (!isset($arrObjRelProtocoloAtributoDTOAntigos[$objRelProtocoloAtributoDTONovo->getNumIdAtributo()])) {
            $objRelProtocoloAtributoRN->cadastrar($objRelProtocoloAtributoDTONovo);
          } else {
            if ($arrObjRelProtocoloAtributoDTOAntigos[$objRelProtocoloAtributoDTONovo->getNumIdAtributo()]->getStrValor() != $objRelProtocoloAtributoDTONovo->getStrValor()) {
              $objRelProtocoloAtributoRN->alterar($objRelProtocoloAtributoDTONovo);
            }
          }
        }
      }

      if ($objProtocoloDTO->isSetArrObjAnexoDTO()) {
        $objAnexoRN = new AnexoRN();
        $objAnexoDTO = new AnexoDTO();
        $objAnexoDTO->retNumIdAnexo();
        $objAnexoDTO->retNumIdUnidade();
        $objAnexoDTO->retStrNome();
        $objAnexoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
        $arrAnexosAntigos = $objAnexoRN->listarRN0218($objAnexoDTO);

        $arrAnexosNovos = $objProtocoloDTO->getArrObjAnexoDTO();

        $arrRemocao = array();
        foreach ($arrAnexosAntigos as $anexoAntigo) {
          $flagRemover = true;
          foreach ($arrAnexosNovos as $anexoNovo) {
            if ($anexoAntigo->getNumIdAnexo() == $anexoNovo->getNumIdAnexo()) {
              $flagRemover = false;
              break;
            }
          }
          if ($flagRemover) {
            $arrRemocao[] = $anexoAntigo;
          }
        }


        foreach ($arrRemocao as $anexoRemover) {
          if ($anexoRemover->getNumIdUnidade() <> SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
            $objUnidadeRN = new UnidadeRN();
            $objUnidadeDTO = new UnidadeDTO();
            $objUnidadeDTO->retStrSigla();
            $objUnidadeDTO->setNumIdUnidade($anexoRemover->getNumIdUnidade());
            $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);
            $objInfraException->lancarValidacao('O anexo "' . $anexoRemover->getStrNome() . '" não pode ser excluído porque foi adicionado por outra unidade (' . $objUnidadeDTO->getStrSigla() . ').');
          }
        }

        if (InfraArray::contar($arrRemocao)) {
          $objAnexoRN->excluirRN0226($arrRemocao);
        }

        foreach ($arrAnexosNovos as $anexoNovo) {
          if (!is_numeric($anexoNovo->getNumIdAnexo())) {
            if ($objProtocoloDTOBanco->getNumIdUnidadeGeradora() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
              $objInfraException->lancarValidacao('O anexo "' . $anexoNovo->getStrNome() . '" não pode ser adicionado porque o protocolo foi gerado por outra unidade (' . $objProtocoloDTOBanco->getStrSiglaUnidadeGeradora() . ').');
            }

            $anexoNovo->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
            $anexoNovo->setNumIdBaseConhecimento(null);
            $anexoNovo->setNumIdProjeto(null);
            $anexoNovo->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $anexoNovo->setStrSinAtivo('S');
            $objAnexoRN->cadastrarRN0172($anexoNovo);
          }
        }
      }

      $objInfraException->lancarValidacoes();

      //$objInfraException->lancarValidacao('FIM alteracao');

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $objProtocoloBD->alterar($objProtocoloDTO);
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando protocolo.', $e);
    }
  }

  protected function cancelarControlado(ProtocoloDTO $parObjProtocoloDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_cancelar', __METHOD__, $parObjProtocoloDTO);

      if ($parObjProtocoloDTO->getStrStaNivelAcessoLocal() != ProtocoloRN::$NA_PUBLICO) {
        $dto = new ProtocoloDTO();
        $dto->setStrStaNivelAcessoLocal(ProtocoloRN::$NA_PUBLICO);
        $dto->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());
        $this->alterarRN0203($dto);
      }

      $dto = new ProtocoloDTO();
      $dto->setStrStaEstado(ProtocoloRN::$TE_DOCUMENTO_CANCELADO);
      $dto->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $objProtocoloBD->alterar($dto);
    } catch (Exception $e) {
      throw new InfraException('Erro cancelando protocolo.', $e);
    }
  }

  protected function excluirRN0748Controlado(ProtocoloDTO $parObjProtocoloDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_excluir', __METHOD__, $parObjProtocoloDTO);

      //Regras de Negocio
      $objArquivamentoRN = new ArquivamentoRN();
      $objArquivamentoRN->validarProtocoloArquivadoExclusao($parObjProtocoloDTO);

      $objParticipanteDTO = new ParticipanteDTO();
      $objParticipanteDTO->retNumIdParticipante();
      $objParticipanteDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objParticipanteRN = new ParticipanteRN();
      $objParticipanteRN->excluirRN0223($objParticipanteRN->listarRN0189($objParticipanteDTO));

      $objObservacaoDTO = new ObservacaoDTO();
      $objObservacaoDTO->retNumIdObservacao();
      $objObservacaoDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objObservacaoRN = new ObservacaoRN();
      $objObservacaoRN->excluirRN0220($objObservacaoRN->listarRN0219($objObservacaoDTO));

      $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
      $objRelProtocoloAssuntoDTO->retTodos();
      $objRelProtocoloAssuntoDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
      $objRelProtocoloAssuntoRN->excluirRN0224($objRelProtocoloAssuntoRN->listarRN0188($objRelProtocoloAssuntoDTO));

      $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
      $objRelProtocoloAtributoDTO->retDblIdProtocolo();
      $objRelProtocoloAtributoDTO->retNumIdAtributo();
      $objRelProtocoloAtributoDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
      $objRelProtocoloAtributoRN->excluir($objRelProtocoloAtributoRN->listar($objRelProtocoloAtributoDTO));

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());
      $objAnexoDTO->setBolExclusaoLogica(false);

      $objAnexoRN = new AnexoRN();
      $objAnexoRN->excluirRN0226($objAnexoRN->listarRN0218($objAnexoDTO));

      $objAnotacaoDTO = new AnotacaoDTO();
      $objAnotacaoDTO->retNumIdAnotacao();
      $objAnotacaoDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objAnotacaoRN = new AnotacaoRN();
      $objAnotacaoRN->excluir($objAnotacaoRN->listar($objAnotacaoDTO));

      $objAcompanhamentoDTO = new AcompanhamentoDTO();
      $objAcompanhamentoDTO->retNumIdAcompanhamento();
      $objAcompanhamentoDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objAcompanhamentoRN = new AcompanhamentoRN();
      $objAcompanhamentoRN->excluir($objAcompanhamentoRN->listar($objAcompanhamentoDTO));

      $objControlePrazoDTO = new ControlePrazoDTO();
      $objControlePrazoDTO->retNumIdControlePrazo();
      $objControlePrazoDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objControlePrazoRN = new ControlePrazoRN();
      $objControlePrazoRN->excluir($objControlePrazoRN->listar($objControlePrazoDTO));

      $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
      $objRelBlocoProtocoloDTO->retTodos();
      $objRelBlocoProtocoloDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());
      $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
      $objRelBlocoProtocoloRN->excluirRN1289($objRelBlocoProtocoloRN->listarRN1291($objRelBlocoProtocoloDTO));

      $objProtocoloModeloDTO = new ProtocoloModeloDTO();
      $objProtocoloModeloDTO->retDblIdProtocoloModelo();
      $objProtocoloModeloDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objProtocoloModeloRN = new ProtocoloModeloRN();
      $objProtocoloModeloRN->excluir($objProtocoloModeloRN->listar($objProtocoloModeloDTO));

      $objRelAcessoExtProtocoloDTO = new RelAcessoExtProtocoloDTO();
      $objRelAcessoExtProtocoloDTO->retNumIdAcessoExterno();
      $objRelAcessoExtProtocoloDTO->retDblIdProtocolo();
      $objRelAcessoExtProtocoloDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objRelAcessoExtProtocoloRN = new RelAcessoExtProtocoloRN();
      $objRelAcessoExtProtocoloRN->excluir($objRelAcessoExtProtocoloRN->listar($objRelAcessoExtProtocoloDTO));


      //atualiza nível de acesso global, pode ser que o documento que vai ser excluído esteja influenciando
      $objMudarNivelAcessoDTO = new MudarNivelAcessoDTO();
      $objMudarNivelAcessoDTO->setStrStaOperacao(self::$TMN_EXCLUSAO);
      $objMudarNivelAcessoDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());
      $objMudarNivelAcessoDTO->setStrStaNivel(null);
      $this->mudarNivelAcesso($objMudarNivelAcessoDTO);


      //Remover associação dos processos e unidades com o protocolo
      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->retNumIdAcesso();
      $objAcessoDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

      $objAcessoRN = new AcessoRN();
      $objAcessoRN->excluir($objAcessoRN->listar($objAcessoDTO));

      //remove associação do documento com o processo e do documento com circular (se existir)
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjProtocoloDTO->getDblIdProtocolo());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(array(
          RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO, RelProtocoloProtocoloRN::$TA_DOCUMENTO_CIRCULAR
        ), InfraDTO::$OPER_IN);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objRelProtocoloProtocoloRN->excluirRN0842($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO));

      //remove atividades inclusive as geradas nas exclusões (blocos por exemplo)
      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retNumIdAtividade();
      $objAtividadeDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());
      $objAtividadeRN = new AtividadeRN();
      $objAtividadeRN->excluirRN0034($objAtividadeRN->listarRN0036($objAtividadeDTO));

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $objProtocoloBD->excluir($parObjProtocoloDTO);
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo protocolo.', $e);
    }
  }

  protected function consultarRN0186Conectado(ProtocoloDTO $objProtocoloDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_consultar', __METHOD__, $objProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloBD->consultar($objProtocoloDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando protocolo.', $e);
    }
  }

  protected function consultarProtocoloAvaliacaoDocumentalConectado(PesquisaAvaliacaoDocumentalDTO $objProtocoloDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_consultar', __METHOD__, $objProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloBD->consultar($objProtocoloDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando protocolo.', $e);
    }
  }

  protected function listarRN0668Conectado(ProtocoloDTO $objProtocoloDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_listar', __METHOD__, $objProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloBD->listar($objProtocoloDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando protocolos.', $e);
    }
  }

  protected function contarRN0667Conectado(ProtocoloDTO $objProtocoloDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_listar', __METHOD__, $objProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloBD->contar($objProtocoloDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando protocolos.', $e);
    }
  }

  protected function bloquearControlado(ProtocoloDTO $objProtocoloDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_consultar', __METHOD__, $objProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloBD->bloquear($objProtocoloDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro bloqueando Protocolo.', $e);
    }
  }

  /*
  protected function desativarControlado($arrObjProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjProtocoloDTO);$i++){
        $objProtocoloBD->desativar($arrObjProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando protocolo.',$e);
    }
  }

  protected function reativarControlado($arrObjProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjProtocoloDTO);$i++){
        $objProtocoloBD->reativar($arrObjProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando protocolo.',$e);
    }
  }

 */

  public function formatarCamposPesquisa(ProtocoloDTO $objProtocoloDTO) {
    $objProtocoloDTO->setStrProtocoloFormatadoPesquisa(InfraUtil::retirarFormatacao($objProtocoloDTO->getStrProtocoloFormatado(), false));
    $objProtocoloDTO->setStrProtocoloFormatadoPesqInv(strrev($objProtocoloDTO->getStrProtocoloFormatadoPesquisa()));
  }

  private function validarStrProtocoloFormatadoRN0211(
    ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProtocoloDTO->getStrProtocoloFormatado())) {
      $objInfraException->adicionarValidacao('Número do protocolo não informado.');
    } else {
      $objProtocoloDTO->setStrProtocoloFormatado(trim($objProtocoloDTO->getStrProtocoloFormatado()));

      if (strlen($objProtocoloDTO->getStrProtocoloFormatado()) > 40) {
        $objInfraException->adicionarValidacao('Número do protocolo possui tamanho superior a 40 caracteres.');
      }

      $objProtocoloDTOBanco = new ProtocoloDTO();
      $objProtocoloDTOBanco->retStrStaProtocolo();
      $objProtocoloDTOBanco->setStrProtocoloFormatado($objProtocoloDTO->getStrProtocoloFormatado());
      $objProtocoloDTOBanco = $this->consultarRN0186($objProtocoloDTOBanco);

      if ($objProtocoloDTOBanco != null) {
        if ($objProtocoloDTOBanco->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
          $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
          if (trim($objInfraParametro->getValor('SEI_FEDERACAO_NUMERO_PROCESSO')) == '1') {
            $objProtocoloDTOBanco = new ProtocoloDTO();
            $objProtocoloDTOBanco->retDblIdProtocolo();

            do {
              $objProtocoloDTOBanco->setStrProtocoloFormatado($this->gerarNumeracaoProcesso());
            } while ($this->consultarRN0186($objProtocoloDTOBanco) != null);

            $objProtocoloDTO->setStrProtocoloFormatado($objProtocoloDTOBanco->getStrProtocoloFormatado());

            return;
          }

          $objInfraException->adicionarValidacao('Já existe um processo utilizando o número de protocolo ' . $objProtocoloDTO->getStrProtocoloFormatado() . '.');
        } else {
          $objInfraException->adicionarValidacao('Já existe um documento utilizando o número de protocolo ' . $objProtocoloDTO->getStrProtocoloFormatado() . '.');
        }
      }
    }
  }

  private function validarDblIdProtocoloAgrupadorRN0747(
    ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    //
  }

  private function validarStrStaProtocoloRN0212(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProtocoloDTO->getStrStaProtocolo())) {
      $objInfraException->adicionarValidacao('Tipo do protocolo não informado.');
    } else {
      if (!in_array($objProtocoloDTO->getStrStaProtocolo(), InfraArray::converterArrInfraDTO($this->listarTiposRN0684(), 'StaTipo'))) {
        $objInfraException->adicionarValidacao('Tipo do protocolo inválido.');
      }
    }
  }

  private function validarNumIdUnidadeGeradoraRN0213(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProtocoloDTO->getNumIdUnidadeGeradora())) {
      $objInfraException->adicionarValidacao('Identificação da unidade geradora não informada.');
    }

    $objUnidadeDTO = new UnidadeDTO();
    $objUnidadeDTO->setBolExclusaoLogica(false);
    $objUnidadeDTO->retStrSinAtivo();
    $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objUnidadeRN = new UnidadeRN();
    $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

    if ($objUnidadeDTO == null) {
      throw new InfraException('Unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . ' não encontrada no SEI.');
    } else {
      if ($objUnidadeDTO->getStrSinAtivo() == 'N') {
        throw new InfraException('Unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . ' desativada no SEI.');
      }
    }
  }

  private function validarNumIdUsuarioGeradorRN0214(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProtocoloDTO->getNumIdUsuarioGerador())) {
      $objInfraException->adicionarValidacao('Identificação do usuário gerador não informada.');
    }
  }

  private function validarNumIdHipoteseLegal(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    //if (SessaoSEI::getInstance()->isBolHabilitada()){ //Não validar para Web-services
    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numHabilitarHipoteseLegal = $objInfraParametro->getValor('SEI_HABILITAR_HIPOTESE_LEGAL');

    if ($numHabilitarHipoteseLegal) {
      if ($objProtocoloDTO->getStrStaNivelAcessoLocal() == ProtocoloRN::$NA_SIGILOSO || $objProtocoloDTO->getStrStaNivelAcessoLocal() == ProtocoloRN::$NA_RESTRITO) {
        if ($numHabilitarHipoteseLegal == 2 && InfraString::isBolVazia($objProtocoloDTO->getNumIdHipoteseLegal())) {
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->retStrStaTipo();
          $objUsuarioDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

          $objUsuarioRN = new UsuarioRN();
          $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

          if ($objUsuarioDTO->getStrStaTipo() != UsuarioRN::$TU_EXTERNO) {
            $objInfraException->adicionarValidacao('Hipótese Legal não informada.');
          }
        }

        if (!InfraString::isBolVazia($objProtocoloDTO->getNumIdHipoteseLegal())) {
          $objHipoteseLegalDTO = new HipoteseLegalDTO();
          $objHipoteseLegalDTO->retStrStaNivelAcesso();
          $objHipoteseLegalDTO->setNumIdHipoteseLegal($objProtocoloDTO->getNumIdHipoteseLegal());

          $objHipoteseLegalRN = new HipoteseLegalRN();
          $objHipoteseLegalDTO = $objHipoteseLegalRN->consultar($objHipoteseLegalDTO);

          if ($objHipoteseLegalDTO == null) {
            $objInfraException->adicionarValidacao('Hipótese Legal não encontrada.');
          } else {
            if ($objHipoteseLegalDTO->getStrStaNivelAcesso() != $objProtocoloDTO->getStrStaNivelAcessoLocal()) {
              $objInfraException->adicionarValidacao('Hipótese Legal não aplicável ao Nível de Acesso do protocolo.');
            }
          }
        }
      } else {
        if (!InfraString::isBolVazia($objProtocoloDTO->getNumIdHipoteseLegal())) {
          $objInfraException->adicionarValidacao('Hipótese Legal não aplicável ao protocolo.');
        }
      }
    } else {
      $objProtocoloDTO->setNumIdHipoteseLegal(null);
    }
    //}
  }

  private function validarStrStaGrauSigilo(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    //if (SessaoSEI::getInstance()->isBolHabilitada()){ //Não validar para Web-services
    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numHabilitarGrauSigilo = $objInfraParametro->getValor('SEI_HABILITAR_GRAU_SIGILO');

    if ($numHabilitarGrauSigilo) {
      if ($objProtocoloDTO->getStrStaNivelAcessoLocal() == ProtocoloRN::$NA_SIGILOSO) {
        if ($numHabilitarGrauSigilo == 2 && InfraString::isBolVazia($objProtocoloDTO->getStrStaGrauSigilo())) {
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->retStrStaTipo();
          $objUsuarioDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

          $objUsuarioRN = new UsuarioRN();
          $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

          if ($objUsuarioDTO->getStrStaTipo() != UsuarioRN::$TU_EXTERNO) {
            $objInfraException->adicionarValidacao('Grau do sigilo não informado.');
          }
        }

        if (!InfraString::isBolVazia($objProtocoloDTO->getStrStaGrauSigilo()) && !in_array($objProtocoloDTO->getStrStaGrauSigilo(), InfraArray::converterArrInfraDTO(self::listarGrausSigiloso(), 'StaGrau'))) {
          $objInfraException->adicionarValidacao('Grau do sigilo inválido.');
        }
      } else {
        if (!InfraString::isBolVazia($objProtocoloDTO->getStrStaGrauSigilo())) {
          $objInfraException->adicionarValidacao('Grau do sigilo não aplicável ao protocolo.');
        }
      }
    } else {
      $objProtocoloDTO->setStrStaGrauSigilo(null);
    }
    //}
  }

  private function validarDtaGeracaoRN0215(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProtocoloDTO->getDtaGeracao())) {
      $objInfraException->adicionarValidacao('Data do protocolo não informada.');
    } else {
      if (!InfraData::validarData($objProtocoloDTO->getDtaGeracao())) {
        $objInfraException->adicionarValidacao('Data do protocolo inválida.');
      }
    }
    if (InfraData::compararDatas(InfraData::getStrDataHoraAtual(), $objProtocoloDTO->getDtaGeracao()) > 0) {
      $objInfraException->adicionarValidacao('Data do protocolo não pode estar no futuro.');
    }
  }

  private function validarArrRelProtocoloAssuntoRN0216(
    ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (($objProtocoloDTO->getArrObjRelProtocoloAssuntoDTO() === null || InfraArray::contar($objProtocoloDTO->getArrObjRelProtocoloAssuntoDTO()) == 0)) {
      if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
        if ($objProtocoloDTO->getDblIdProtocolo() == null) {
          $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
          if ($objInfraParametro->getValor('SEI_ID_TIPO_PROCEDIMENTO_FEDERACAO') == $objProtocoloDTO->getNumIdTipoProcedimentoProcedimento()) {
            return;
          }
        }

        $objInfraException->adicionarValidacao('Nenhum assunto informado para o processo.');
      }
    } else {
      $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO(InfraArray::distinctArrInfraDTO($objProtocoloDTO->getArrObjRelProtocoloAssuntoDTO(), 'IdAssunto'));

      $objAssuntoRN = new AssuntoRN();
      $objAssuntoDTO = new AssuntoDTO();
      $objAssuntoDTO->retNumIdAssunto();
      $objAssuntoDTO->setNumIdAssunto(InfraArray::converterArrInfraDTO($objProtocoloDTO->getArrObjRelProtocoloAssuntoDTO(), 'IdAssunto'), InfraDTO::$OPER_IN);
      $objAssuntoDTO->setStrSinEstrutural('N');
      $objAssuntoDTO->setNumMaxRegistrosRetorno(1);

      if ($objAssuntoRN->consultarRN0256($objAssuntoDTO) == null) {
        $objInfraException->adicionarValidacao('Assuntos não são suficientes para classificação.');
      }
    }
  }

  private function validarArrParticipanteRN0572(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    $arrParticipantes = $objProtocoloDTO->getArrObjParticipanteDTO();
    $arrDuplicados = array();

    if (InfraArray::contar($arrParticipantes) > 0) {
      //usando FOREACH para limpar
      foreach ($arrParticipantes as $objParticipanteDTO) {
        if (!$objParticipanteDTO->isSetNumIdContato()) {
          $objInfraException->lancarValidacao('Identificador do participante não informado.');
        }

        if (!$objParticipanteDTO->isSetStrStaParticipacao()) {
          $objInfraException->lancarValidacao('Tipo de participação do participante não informada.');
        }

        $arrDuplicados[] = $objParticipanteDTO->getNumIdContato() . '-' . $objParticipanteDTO->getStrStaParticipacao();
      }
    }

    if (InfraArray::contar($arrDuplicados) != InfraArray::contar(array_unique($arrDuplicados))) {
      $objInfraException->adicionarValidacao('Foram encontrados participantes duplicados.');
    }
  }

  private function validarArrObjObservacaoRN0573(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (InfraArray::contar($objProtocoloDTO->getArrObjObservacaoDTO()) > 1) {
      $objInfraException->adicionarValidacao('Mais de uma observação informada para a unidade.');
    }
  }

  private function validarArrObjRelProtocoloAtributoDTO(
    ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {}

  private function validarArrAnexoRN0227(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {}

  private function gerarNumeracaoInterna() {
    try {
      return $this->getObjInfraIBanco()->getValorSequencia('seq_protocolo');
    } catch (Exception $e) {
      throw new InfraException('Erro gerando numeração interna.', $e);
    }
  }

  public function gerarNumeracaoProcesso() {
    try {
      $ret = null;

      $objInfraSequencia = new InfraSequencia(BancoSEI::getInstance());

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrNumeracao();
      $objOrgaoDTO->retStrCodigoSei();
      $objOrgaoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());

      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

      $strNumeracao = $objOrgaoDTO->getStrNumeracao();

      if (InfraString::isBolVazia($strNumeracao)) {
        throw new InfraException('Formato da numeração não configurado para o órgão ' . $objOrgaoDTO->getStrSigla() . '.');
      }

      if (strpos($strNumeracao, '@cod_unidade_sei') !== false || strpos($strNumeracao, '@seq_anual_cod_unidade_sei') !== false) {
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrCodigoSei();
        $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objUnidadeRN = new UnidadeRN();
        $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);
      }

      //Padrao SEI
      //@ano_2d@.@cod_orgao_sip@.@seq_anual_cod_orgao_sip_09d@-@dv_mod11_1d@

      //Executivo Federal
      //@cod_orgao_sip_05d@.@seq_anual_cod_orgao_sip_06d@/@ano_4d@-@dv_mod11_executivo_federal_2d@

      //Executivo Federal - Novo NUP
      //http://www.comprasgovernamentais.gov.br/paginas/comunicacoes-administrativas/numero-unico-de-protocolo-nup
      //@cod_unidade_sei_07d@.@seq_anual_cod_unidade_sei_08d@/@ano_4d@-@dv_mod97_base10_executivo_federal_2d@

      //Padrao CNJ/Justica Federal - Quarta Regiao
      //@seq_anual_cod_orgao_sip_07d@-@dv_mod97_base10_cnj_2d@.@ano_4d@.4.04.8000

      $strNumeracao = str_replace('@ano_2d@', substr(InfraData::getStrDataAtual(), -2), $strNumeracao);
      $strNumeracao = str_replace('@ano_4d@', substr(InfraData::getStrDataAtual(), -4), $strNumeracao);

      if (strpos($strNumeracao, '@cod_orgao_sip') !== false) {
        $strNumeracao = str_replace('@cod_orgao_sip@', SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual(), $strNumeracao);
        $strNumeracao = str_replace('@cod_orgao_sip_02d@', sprintf("%02s", SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_orgao_sip_03d@', sprintf("%03s", SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_orgao_sip_04d@', sprintf("%04s", SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_orgao_sip_05d@', sprintf("%05s", SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()), $strNumeracao);
      }

      if (strpos($strNumeracao, '@cod_orgao_sei') !== false) {
        if (InfraString::isBolVazia($objOrgaoDTO->getStrCodigoSei())) {
          throw new InfraException('Código SEI não configurado para o órgão ' . $objOrgaoDTO->getStrSigla() . '.');
        }

        $strNumeracao = str_replace('@cod_orgao_sei@', $objOrgaoDTO->getStrCodigoSei(), $strNumeracao);
        $strNumeracao = str_replace('@cod_orgao_sei_02d@', sprintf("%02s", $objOrgaoDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_orgao_sei_03d@', sprintf("%03s", $objOrgaoDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_orgao_sei_04d@', sprintf("%04s", $objOrgaoDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_orgao_sei_05d@', sprintf("%05s", $objOrgaoDTO->getStrCodigoSei()), $strNumeracao);
      }

      if (strpos($strNumeracao, '@cod_unidade_sip') !== false) {
        $strNumeracao = str_replace('@cod_unidade_sip@', SessaoSEI::getInstance()->getNumIdUnidadeAtual(), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sip_02d@', sprintf("%02s", SessaoSEI::getInstance()->getNumIdUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sip_03d@', sprintf("%03s", SessaoSEI::getInstance()->getNumIdUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sip_04d@', sprintf("%04s", SessaoSEI::getInstance()->getNumIdUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sip_05d@', sprintf("%05s", SessaoSEI::getInstance()->getNumIdUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sip_06d@', sprintf("%06s", SessaoSEI::getInstance()->getNumIdUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sip_07d@', sprintf("%07s", SessaoSEI::getInstance()->getNumIdUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sip_08d@', sprintf("%08s", SessaoSEI::getInstance()->getNumIdUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sip_09d@', sprintf("%09s", SessaoSEI::getInstance()->getNumIdUnidadeAtual()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sip_010d@', sprintf("%010s", SessaoSEI::getInstance()->getNumIdUnidadeAtual()), $strNumeracao);
      }

      if (strpos($strNumeracao, '@cod_unidade_sei') !== false) {
        if (InfraString::isBolVazia($objUnidadeDTO->getStrCodigoSei())) {
          throw new InfraException('Código SEI não configurado para a unidade ' . $objUnidadeDTO->getStrSigla() . ' / ' . $objOrgaoDTO->getStrSigla() . '.');
        }

        $strNumeracao = str_replace('@cod_unidade_sei@', $objUnidadeDTO->getStrCodigoSei(), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sei_02d@', sprintf("%02s", $objUnidadeDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sei_03d@', sprintf("%03s", $objUnidadeDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sei_04d@', sprintf("%04s", $objUnidadeDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sei_05d@', sprintf("%05s", $objUnidadeDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sei_06d@', sprintf("%06s", $objUnidadeDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sei_07d@', sprintf("%07s", $objUnidadeDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sei_08d@', sprintf("%08s", $objUnidadeDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sei_09d@', sprintf("%09s", $objUnidadeDTO->getStrCodigoSei()), $strNumeracao);
        $strNumeracao = str_replace('@cod_unidade_sei_010d@', sprintf("%010s", $objUnidadeDTO->getStrCodigoSei()), $strNumeracao);
      }

      if (strpos($strNumeracao, '@seq_anual_cod_orgao_sip') !== false) {
        $strNomeSequencia = 'seq_' . substr(InfraData::getStrDataAtual(), 6) . '_org_sip_' . SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual();

        if (!$objInfraSequencia->verificarSequencia($strNomeSequencia)) {
          $objInfraSequencia->criarSequencia($strNomeSequencia, 1, 0, 9999999999);
        }
        $numSequencial = $objInfraSequencia->obterProximaSequencia($strNomeSequencia);

        $strNumeracao = str_replace('@seq_anual_cod_orgao_sip_05d@', sprintf("%05s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_orgao_sip_06d@', sprintf("%06s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_orgao_sip_07d@', sprintf("%07s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_orgao_sip_08d@', sprintf("%08s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_orgao_sip_09d@', sprintf("%09s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_orgao_sip_010d@', sprintf("%010s", $numSequencial), $strNumeracao);
      }

      if (strpos($strNumeracao, '@seq_anual_cod_orgao_sei') !== false) {
        if (InfraString::isBolVazia($objOrgaoDTO->getStrCodigoSei())) {
          throw new InfraException('Código SEI não configurado para o órgão ' . $objOrgaoDTO->getStrSigla() . '.');
        }

        $strNomeSequencia = 'seq_' . substr(InfraData::getStrDataAtual(), 6) . '_org_sei_' . $objOrgaoDTO->getStrCodigoSei();

        if (!$objInfraSequencia->verificarSequencia($strNomeSequencia)) {
          $objInfraSequencia->criarSequencia($strNomeSequencia, 1, 0, 9999999999);
        }
        $numSequencial = $objInfraSequencia->obterProximaSequencia($strNomeSequencia);

        $strNumeracao = str_replace('@seq_anual_cod_orgao_sei_05d@', sprintf("%05s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_orgao_sei_06d@', sprintf("%06s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_orgao_sei_07d@', sprintf("%07s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_orgao_sei_08d@', sprintf("%08s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_orgao_sei_09d@', sprintf("%09s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_orgao_sei_010d@', sprintf("%010s", $numSequencial), $strNumeracao);
      }

      if (strpos($strNumeracao, '@seq_anual_cod_unidade_sip') !== false) {
        $strNomeSequencia = 'seq_' . substr(InfraData::getStrDataAtual(), 6) . '_uni_sip_' . SessaoSEI::getInstance()->getNumIdUnidadeAtual();

        if (!$objInfraSequencia->verificarSequencia($strNomeSequencia)) {
          $objInfraSequencia->criarSequencia($strNomeSequencia, 1, 0, 9999999999);
        }
        $numSequencial = $objInfraSequencia->obterProximaSequencia($strNomeSequencia);

        $strNumeracao = str_replace('@seq_anual_cod_unidade_sip_05d@', sprintf("%05s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_unidade_sip_06d@', sprintf("%06s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_unidade_sip_07d@', sprintf("%07s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_unidade_sip_08d@', sprintf("%08s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_unidade_sip_09d@', sprintf("%09s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_unidade_sip_010d@', sprintf("%010s", $numSequencial), $strNumeracao);
      }

      if (strpos($strNumeracao, '@seq_anual_cod_unidade_sei') !== false) {
        if (InfraString::isBolVazia($objUnidadeDTO->getStrCodigoSei())) {
          throw new InfraException('Código SEI não configurado para a unidade ' . $objUnidadeDTO->getStrSigla() . ' / ' . $objOrgaoDTO->getStrSigla() . '.');
        }

        $strNomeSequencia = 'seq_' . substr(InfraData::getStrDataAtual(), 6) . '_uni_sei_' . $objUnidadeDTO->getStrCodigoSei();

        if (!$objInfraSequencia->verificarSequencia($strNomeSequencia)) {
          $objInfraSequencia->criarSequencia($strNomeSequencia, 1, 0, 9999999999);
        }
        $numSequencial = $objInfraSequencia->obterProximaSequencia($strNomeSequencia);

        $strNumeracao = str_replace('@seq_anual_cod_unidade_sei_05d@', sprintf("%05s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_unidade_sei_06d@', sprintf("%06s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_unidade_sei_07d@', sprintf("%07s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_unidade_sei_08d@', sprintf("%08s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_unidade_sei_09d@', sprintf("%09s", $numSequencial), $strNumeracao);
        $strNumeracao = str_replace('@seq_anual_cod_unidade_sei_010d@', sprintf("%010s", $numSequencial), $strNumeracao);
      }


      $strNumeracaoDv = $strNumeracao;
      $strNumeracaoDv = str_replace('@dv_mod97_base10_cnj_2d@', '', $strNumeracaoDv);
      $strNumeracaoDv = str_replace('@dv_mod11_1d@', '', $strNumeracaoDv);
      $strNumeracaoDv = str_replace('@dv_mod11_executivo_federal_2d@', '', $strNumeracaoDv);
      $strNumeracaoDv = str_replace('@dv_mod97_base10_executivo_federal_2d@', '', $strNumeracaoDv);
      $strNumeracaoDv = str_replace('@dv_mod97_base10_cnmp_2d@', '', $strNumeracaoDv);

      $strNumeracaoDv = InfraUtil::retirarFormatacao($strNumeracaoDv);

      if (strpos($strNumeracao, '@dv_mod11_1d@') !== false) {
        $strNumeracao = str_replace('@dv_mod11_1d@', InfraUtil::calcularModulo11($strNumeracaoDv), $strNumeracao);
      } else {
        if (strpos($strNumeracao, '@dv_mod11_executivo_federal_2d@') !== false) {
          $dv1 = $this->calcularMod11ExecutivoFederal($strNumeracaoDv);
          $dv2 = $this->calcularMod11ExecutivoFederal($strNumeracaoDv . $dv1);
          $strNumeracao = str_replace('@dv_mod11_executivo_federal_2d@', (string)$dv1 . (string)$dv2, $strNumeracao);
        } else {
          if (strpos($strNumeracao, '@dv_mod97_base10_cnj_2d@') !== false) {
            $strNumeracao = str_replace('@dv_mod97_base10_cnj_2d@', $this->calcularMod97Base10Cnj($strNumeracaoDv), $strNumeracao);
          } else {
            if (strpos($strNumeracao, '@dv_mod97_base10_executivo_federal_2d@') !== false) {
              $strNumeracao = str_replace('@dv_mod97_base10_executivo_federal_2d@', $this->calcularMod97Base10ExecutivoFederal($strNumeracaoDv), $strNumeracao);
            } else {
              if (strpos($strNumeracao, '@dv_mod97_base10_cnmp_2d@') !== false) {
                $strNumeracao = str_replace('@dv_mod97_base10_cnmp_2d@', $this->calcularMod97Base10Cnmp($strNumeracaoDv), $strNumeracao);
              }
            }
          }
        }
      }

      return $strNumeracao;
    } catch (Exception $e) {
      throw new InfraException('Erro gerando numeração de processo.', $e);
    }
  }

  public function gerarNumeracaoDocumento() {
    try {
      return str_pad($this->getObjInfraIBanco()->getValorSequencia('seq_documento'), DIGITOS_DOCUMENTO, '0', STR_PAD_LEFT);
    } catch (Exception $e) {
      throw new InfraException('Erro gerando numeração de documento.', $e);
    }
  }

  private function formatarSiglaOrgaoUnidadeAtualSequencia() {
    return strtolower(str_replace(' ', '_', str_replace('-', '', SessaoSEI::getInstance()->getStrSiglaOrgaoUnidadeAtual())));
  }

  private function calcularMod11ExecutivoFederal($strValor) {
    $soma = 0; // acumulador
    $peso = 2; // peso inicial

    for ($i = strlen($strValor) - 1; $i >= 0; $i--) {
      //InfraDebug::getInstance()->gravar(substr($strValor, $i, 1).' * '.$peso.' = '.intval(substr($strValor, $i, 1)) * $peso);
      $soma += intval(substr($strValor, $i, 1)) * $peso++;
    }

    //InfraDebug::getInstance()->gravar('SOMA='.$soma);

    $resto = $soma % 11;

    $dv = 11 - $resto;

    //11 - 10 =  1
    //11 -  9 =  2
    //11 -  8 =  3
    //11 -  7 =  4
    //11 -  6 =  5
    //11 -  5 =  6
    //11 -  4 =  7
    //11 -  3 =  8
    //11 -  2 =  9
    //11 -  1 = 10
    //11 -  0 = 11

    if ($dv == 10) {
      $dv = 0;
    } elseif ($dv > 10) {
      $dv = 1;
    }

    //InfraDebug::getInstance()->gravar('RESTO:'.$resto);

    return $dv;
  }

  public function calcularMod97Base10Cnj($strValor) {
    //0000008-98.2011.404.8000
    //0000008 2011 404 8000

    $n = substr($strValor, 0, 7);
    $a = substr($strValor, 7, 4);
    $jtr = substr($strValor, 11, 3);
    $o = substr($strValor, 14, 4);
    $dv = (98 - (((($n % 97) . $a . $jtr) % 97) . $o . "00") % 97);
    return str_pad($dv, 2, '0', STR_PAD_LEFT);
  }

  //@dv_mod97_base10_executivo_federal_2d@
  public function calcularMod97Base10ExecutivoFederal($strValor) {
    //0000001.00000001/2015-97
    //0000001000000012015

    $unidade = substr($strValor, 0, 7);
    $numero = substr($strValor, 7, 8);
    $ano = substr($strValor, 15, 4);

    $dv = 98 - ((($unidade . $numero . $ano) . "00") % 97);
    return str_pad($dv, 2, '0', STR_PAD_LEFT);
  }

  public function calcularMod97Base10Cnmp($strValor) {
    //19.00.0001.0000001/2017-01
    //19000001 0000001 201701

    $t = substr($strValor, 0, 8);
    $n = substr($strValor, 8, 7);
    $a = substr($strValor, 15, 4);

    $dv = (98 - (((($t % 97) . $n) % 97) . $a . '00') % 97);
    $dv = str_pad($dv, 2, '0', STR_PAD_LEFT);

    //$ok = (((($t%97).$n)%97).$a.$dv)%97;
    //die($strValor."\nT=[".$t."]\nN=[".$n."]\nA=[".$a."]\nDV=[".$dv."]\nOK=".$ok);

    return $dv;
  }

  public function listarTiposRN0684() {
    $objArrTipoDTO = array();
    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(ProtocoloRN::$TP_PROCEDIMENTO);
    $objTipoDTO->setStrDescricao('Processo');
    $objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(ProtocoloRN::$TP_DOCUMENTO_GERADO);
    $objTipoDTO->setStrDescricao('Documento Gerado');
    $objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(ProtocoloRN::$TP_DOCUMENTO_RECEBIDO);
    $objTipoDTO->setStrDescricao('Documento Externo');
    $objArrTipoDTO[] = $objTipoDTO;

    return $objArrTipoDTO;
  }

  private function validarStrIdProtocoloFederacao(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProtocoloDTO->getStrIdProtocoloFederacao())) {
      $objProtocoloDTO->setStrIdProtocoloFederacao(null);
    } else {
      if (!InfraULID::validar($objProtocoloDTO->getStrIdProtocoloFederacao())) {
        $objInfraException->lancarValidacao('Identificador do SEI Federação ' . $objProtocoloDTO->getStrIdProtocoloFederacao() . ' inválido.');
      }

      $dto = new ProtocoloDTO();
      $dto->retStrIdProtocoloFederacao();
      $dto->setNumMaxRegistrosRetorno(1);
      $dto->setStrIdProtocoloFederacao($objProtocoloDTO->getStrIdProtocoloFederacao());
      if ($this->consultarRN0186($dto) != null) {
        $objInfraException->adicionarValidacao('Já existe um protocolo cadastrado nesta instalação com o mesmo identificador do SEI Federação.');
      }
    }
  }

  private function validarStrStaEstadoRN1016(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProtocoloDTO->getStrStaEstado())) {
      $objInfraException->adicionarValidacao('Estado não informado.');
    } else {
      $arr = $this->listarValoresEstadosRN1015();
      foreach ($arr as $dto) {
        if ($dto->getStrStaEstado() == $objProtocoloDTO->getStrStaEstado()) {
          return;
        }
      }
      $objInfraException->adicionarValidacao('Estado inválido.');
    }
  }

  public function validarStrStaNivelAcessoLocalRN0685(
    ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProtocoloDTO->getStrStaNivelAcessoLocal())) {
      $objInfraException->adicionarValidacao('Nível de acesso local não informado.');
    } else {
      if (!in_array($objProtocoloDTO->getStrStaNivelAcessoLocal(), InfraArray::converterArrInfraDTO($this->listarNiveisAcessoRN0878(), 'StaNivel'))) {
        $objInfraException->adicionarValidacao('Nível de acesso local inválido.');
      }
    }
  }

  public function listarNiveisAcessoRN0878() {
    $arr = array();

    $objNivelAcessoDTO = new NivelAcessoDTO();
    $objNivelAcessoDTO->setStrStaNivel(ProtocoloRN::$NA_PUBLICO);
    $objNivelAcessoDTO->setStrDescricao('Público');
    $arr[] = $objNivelAcessoDTO;

    $objNivelAcessoDTO = new NivelAcessoDTO();
    $objNivelAcessoDTO->setStrStaNivel(ProtocoloRN::$NA_RESTRITO);
    $objNivelAcessoDTO->setStrDescricao('Restrito');
    $arr[] = $objNivelAcessoDTO;


    $objNivelAcessoDTO = new NivelAcessoDTO();
    $objNivelAcessoDTO->setStrStaNivel(ProtocoloRN::$NA_SIGILOSO);
    $objNivelAcessoDTO->setStrDescricao('Sigiloso');
    $arr[] = $objNivelAcessoDTO;

    return $arr;
  }

  public function listarValoresEstadosRN1015() {
    $arr = array();

    $objEstadoProtocoloDTO = new EstadoProtocoloDTO();
    $objEstadoProtocoloDTO->setStrStaEstado(ProtocoloRN::$TE_NORMAL);
    $objEstadoProtocoloDTO->setStrDescricao('Normal');
    $arr[] = $objEstadoProtocoloDTO;

    $objEstadoProtocoloDTO = new EstadoProtocoloDTO();
    $objEstadoProtocoloDTO->setStrStaEstado(ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO);
    $objEstadoProtocoloDTO->setStrDescricao('Sobrestado');
    $arr[] = $objEstadoProtocoloDTO;

    $objEstadoProtocoloDTO = new EstadoProtocoloDTO();
    $objEstadoProtocoloDTO->setStrStaEstado(ProtocoloRN::$TE_DOCUMENTO_CANCELADO);
    $objEstadoProtocoloDTO->setStrDescricao('Cancelado');
    $arr[] = $objEstadoProtocoloDTO;

    $objEstadoProtocoloDTO = new EstadoProtocoloDTO();
    $objEstadoProtocoloDTO->setStrStaEstado(ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO);
    $objEstadoProtocoloDTO->setStrDescricao('Anexado');
    $arr[] = $objEstadoProtocoloDTO;

    $objEstadoProtocoloDTO = new EstadoProtocoloDTO();
    $objEstadoProtocoloDTO->setStrStaEstado(ProtocoloRN::$TE_PROCEDIMENTO_BLOQUEADO);
    $objEstadoProtocoloDTO->setStrDescricao('Bloqueado');
    $arr[] = $objEstadoProtocoloDTO;

    return $arr;
  }

  public static function listarGrausSigiloso() {
    $arr = array();

    $objGrauSigiloDTO = new GrauSigiloDTO();
    $objGrauSigiloDTO->setStrStaGrau(ProtocoloRN::$TGS_ULTRASSECRETO);
    $objGrauSigiloDTO->setStrDescricao('Ultrassecreto');
    $arr[] = $objGrauSigiloDTO;

    $objGrauSigiloDTO = new GrauSigiloDTO();
    $objGrauSigiloDTO->setStrStaGrau(ProtocoloRN::$TGS_SECRETO);
    $objGrauSigiloDTO->setStrDescricao('Secreto');
    $arr[] = $objGrauSigiloDTO;

    $objGrauSigiloDTO = new GrauSigiloDTO();
    $objGrauSigiloDTO->setStrStaGrau(ProtocoloRN::$TGS_RESERVADO);
    $objGrauSigiloDTO->setStrDescricao('Reservado');
    $arr[] = $objGrauSigiloDTO;

    return $arr;
  }

  protected function pesquisarRN0967Conectado(PesquisaProtocoloDTO $objPesquisaProtocoloDTO) {
    try {
      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_listar', __METHOD__, $objPesquisaProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (!$objPesquisaProtocoloDTO->isSetStrStaTipo()) {
        throw new InfraException('Tipo da pesquisa interna não informado.');
      }

      $objProtocoloDTO = new ProtocoloDTO();

      if ($objPesquisaProtocoloDTO->isSetDblIdProtocolo()) {
        if (!is_array($objPesquisaProtocoloDTO->getDblIdProtocolo())) {
          $objProtocoloDTO->setDblIdProtocolo($objPesquisaProtocoloDTO->getDblIdProtocolo());
        } else {
          $objProtocoloDTO->setDblIdProtocolo($objPesquisaProtocoloDTO->getDblIdProtocolo(), InfraDTO::$OPER_IN);
        }
      }

      if ($objPesquisaProtocoloDTO->isSetStrIdProtocoloFederacao()) {
        if (!is_array($objPesquisaProtocoloDTO->getStrIdProtocoloFederacao())) {
          $objProtocoloDTO->setStrIdProtocoloFederacao($objPesquisaProtocoloDTO->getStrIdProtocoloFederacao());
        } else {
          $objProtocoloDTO->setStrIdProtocoloFederacao($objPesquisaProtocoloDTO->getStrIdProtocoloFederacao(), InfraDTO::$OPER_IN);
        }
      }

      if ($objPesquisaProtocoloDTO->isSetStrProtocolo() && !InfraString::isBolVazia($objPesquisaProtocoloDTO->getStrProtocolo())) {
        $objProtocoloDTO->setStrProtocoloFormatadoPesquisa(InfraUtil::retirarFormatacao($objPesquisaProtocoloDTO->getStrProtocolo(), false));
      }

      switch ($objPesquisaProtocoloDTO->getStrStaTipo()) {
        case self::$TPP_TODOS:
          $objProtocoloDTO->setNumTipoFkProcedimento(InfraDTO::$TIPO_FK_OPCIONAL);
          $objProtocoloDTO->setNumTipoFkDocumento(InfraDTO::$TIPO_FK_OPCIONAL);
          break;

        case self::$TPP_PROCEDIMENTOS:
          $objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_PROCEDIMENTO);
          $objProtocoloDTO->setNumTipoFkProcedimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
          $objProtocoloDTO->setNumTipoFkDocumento(InfraDTO::$TIPO_FK_OPCIONAL);
          break;

        case self::$TPP_DOCUMENTOS:
          $objProtocoloDTO->setStrStaProtocolo(array(ProtocoloRN::$TP_DOCUMENTO_GERADO, ProtocoloRN::$TP_DOCUMENTO_RECEBIDO), InfraDTO::$OPER_IN);
          $objProtocoloDTO->setNumTipoFkProcedimento(InfraDTO::$TIPO_FK_OPCIONAL);
          $objProtocoloDTO->setNumTipoFkDocumento(InfraDTO::$TIPO_FK_OBRIGATORIA);
          break;

        case self::$TPP_DOCUMENTOS_GERADOS:
          $objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_DOCUMENTO_GERADO);
          $objProtocoloDTO->setNumTipoFkProcedimento(InfraDTO::$TIPO_FK_OPCIONAL);
          $objProtocoloDTO->setNumTipoFkDocumento(InfraDTO::$TIPO_FK_OBRIGATORIA);
          break;

        case self::$TPP_DOCUMENTOS_RECEBIDOS:
          $objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_DOCUMENTO_RECEBIDO);
          $objProtocoloDTO->setNumTipoFkProcedimento(InfraDTO::$TIPO_FK_OPCIONAL);
          $objProtocoloDTO->setNumTipoFkDocumento(InfraDTO::$TIPO_FK_OBRIGATORIA);
          break;

        default:
          throw new InfraException('Tipo da pesquisa interna inválido.');
      }

      $objInfraException->lancarValidacoes();

      $objProtocoloDTO->setDistinct(true);

      $objProtocoloDTO->retDblIdProtocolo();
      $objProtocoloDTO->retStrIdProtocoloFederacao();
      $objProtocoloDTO->retStrProtocoloFormatado();
      $objProtocoloDTO->retNumIdUsuarioGerador();
      $objProtocoloDTO->retNumIdOrgaoUnidadeGeradora();
      $objProtocoloDTO->retNumIdUnidadeGeradora();
      $objProtocoloDTO->retStrSiglaUnidadeGeradora();
      $objProtocoloDTO->retStrDescricaoUnidadeGeradora();
      $objProtocoloDTO->retStrSiglaUsuarioGerador();
      $objProtocoloDTO->retStrNomeUsuarioGerador();
      $objProtocoloDTO->retDtaGeracao();
      $objProtocoloDTO->retStrStaProtocolo();
      $objProtocoloDTO->retStrStaEstado();
      $objProtocoloDTO->retStrDescricao();
      $objProtocoloDTO->retStrStaNivelAcessoGlobal();
      $objProtocoloDTO->retNumIdHipoteseLegal();
      $objProtocoloDTO->retStrStaGrauSigilo();
      $objProtocoloDTO->retStrSinEliminado();

      if ($objPesquisaProtocoloDTO->getStrStaTipo() == self::$TPP_TODOS || $objPesquisaProtocoloDTO->getStrStaTipo() == self::$TPP_PROCEDIMENTOS) {
        $objProtocoloDTO->retNumIdTipoProcedimentoProcedimento();
        $objProtocoloDTO->retStrNomeTipoProcedimentoProcedimento();
      }

      if ($objPesquisaProtocoloDTO->getStrStaTipo() == self::$TPP_TODOS || $objPesquisaProtocoloDTO->getStrStaTipo() != self::$TPP_PROCEDIMENTOS) {
        $objProtocoloDTO->retDblIdDocumentoEdocDocumento();
        $objProtocoloDTO->retStrStaDocumentoDocumento();
        $objProtocoloDTO->retNumIdSerieDocumento();
        $objProtocoloDTO->retNumIdTipoFormularioDocumento();
        $objProtocoloDTO->retStrNomeSerieDocumento();
        $objProtocoloDTO->retStrNumeroDocumento();
        $objProtocoloDTO->retDblIdProcedimentoDocumentoProcedimento();
        $objProtocoloDTO->retNumIdTipoProcedimentoDocumento();
        $objProtocoloDTO->retStrNomeTipoProcedimentoDocumento();
        $objProtocoloDTO->retStrProtocoloFormatadoProcedimentoDocumento();
        $objProtocoloDTO->retNumIdTipoConferenciaDocumento();
        $objProtocoloDTO->retStrSinArquivamentoDocumento();
        $objProtocoloDTO->retDblIdProcedimentoDocumento();
        $objProtocoloDTO->retStrNomeArvoreDocumento();
        $objProtocoloDTO->retStrSinBloqueadoDocumento();
      }

      $objProtocoloDTO->setOrdDtaGeracao(InfraDTO::$TIPO_ORDENACAO_DESC);

      //paginação
      $objProtocoloDTO->setNumMaxRegistrosRetorno($objPesquisaProtocoloDTO->getNumMaxRegistrosRetorno());
      $objProtocoloDTO->setNumPaginaAtual($objPesquisaProtocoloDTO->getNumPaginaAtual());

      $arrObjProtocoloDTO = $this->listarRN0668($objProtocoloDTO);

      //paginação
      $objPesquisaProtocoloDTO->setNumTotalRegistros($objProtocoloDTO->getNumTotalRegistros());
      $objPesquisaProtocoloDTO->setNumRegistrosPaginaAtual($objProtocoloDTO->getNumRegistrosPaginaAtual());

      $arrObjProcedimentoDTO = array();
      $arrObjDocumentoDTO = array();

      if (count($arrObjProtocoloDTO)) {
        $arrIdProcedimentos = array();
        $arrIdDocumentosGerados = array();

        $numModulos = count($SEI_MODULOS);

        //otimizacao
        if ($numModulos) {
          $arrObjProcedimentoAPI = array();
          $arrObjDocumentoAPI = array();
        }

        foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
          $objProtocoloDTO->setStrSinAberto('N');

          if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
            if ($numModulos) {
              $objProcedimentoAPI = new ProcedimentoAPI();
              $objProcedimentoAPI->setIdProcedimento($objProtocoloDTO->getDblIdProtocolo());
              $objProcedimentoAPI->setIdTipoProcedimento($objProtocoloDTO->getNumIdTipoProcedimentoProcedimento());
              $objProcedimentoAPI->setIdUnidadeGeradora($objProtocoloDTO->getNumIdUnidadeGeradora());
              $objProcedimentoAPI->setNivelAcesso($objProtocoloDTO->getStrStaNivelAcessoGlobal());
              $arrObjProcedimentoAPI[] = $objProcedimentoAPI;
            }

            $arrIdProcedimentos[] = $objProtocoloDTO->getDblIdProtocolo();
            $arrObjProcedimentoDTO[$objProtocoloDTO->getDblIdProtocolo()] = $objProtocoloDTO;
          } else {
            if ($numModulos) {
              $objDocumentoAPI = new DocumentoAPI();
              $objDocumentoAPI->setIdDocumento($objProtocoloDTO->getDblIdProtocolo());
              $objDocumentoAPI->setIdProcedimento($objProtocoloDTO->getDblIdProcedimentoDocumento());
              $objDocumentoAPI->setIdSerie($objProtocoloDTO->getNumIdSerieDocumento());
              $objDocumentoAPI->setIdUnidadeGeradora($objProtocoloDTO->getNumIdUnidadeGeradora());
              $objDocumentoAPI->setTipo($objProtocoloDTO->getStrStaProtocolo());
              $objDocumentoAPI->setSubTipo($objProtocoloDTO->getStrStaDocumentoDocumento());
              $objDocumentoAPI->setNivelAcesso($objProtocoloDTO->getStrStaNivelAcessoGlobal());
              $arrObjDocumentoAPI[] = $objDocumentoAPI;
            }

            $arrObjDocumentoDTO[] = $objProtocoloDTO;
            $arrIdProcedimentos[] = $objProtocoloDTO->getDblIdProcedimentoDocumento();

            if ($objProtocoloDTO->getStrStaProtocolo() == self::$TP_DOCUMENTO_GERADO) {
              $arrIdDocumentosGerados[] = $objProtocoloDTO->getDblIdProtocolo();
            }
          }
        }

        $arrIdProcedimentos = array_unique($arrIdProcedimentos);


        if (InfraArray::contar($arrIdDocumentosGerados)) {
          //busca assinaturas dos documentos
          $objAssinaturaDTO = new AssinaturaDTO();
          $objAssinaturaDTO->retDblIdDocumento();
          $objAssinaturaDTO->setDblIdDocumento($arrIdDocumentosGerados, InfraDTO::$OPER_IN);

          $objAssinaturaRN = new AssinaturaRN();
          $arrDocumentosAssinados = InfraArray::indexarArrInfraDTO($objAssinaturaRN->listarRN1323($objAssinaturaDTO), 'IdDocumento');

          $objPublicacaoDTO = new PublicacaoDTO();
          $objPublicacaoDTO->retDblIdDocumento();
          $objPublicacaoDTO->retStrStaEstado();
          $objPublicacaoDTO->setDblIdDocumento($arrIdDocumentosGerados, InfraDTO::$OPER_IN);

          $objPublicacaoRN = new PublicacaoRN();
          $arrObjPublicacaoDTO = InfraArray::indexarArrInfraDTO($objPublicacaoRN->listarRN1045($objPublicacaoDTO), 'IdDocumento');


          foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
            $dblIdProtocolo = $objProtocoloDTO->getDblIdProtocolo();

            if (isset($arrDocumentosAssinados[$dblIdProtocolo])) {
              $objProtocoloDTO->setStrSinAssinado('S');
            } else {
              $objProtocoloDTO->setStrSinAssinado('N');
            }

            //se o documento gerado tem registro de publicacao
            if (isset($arrObjPublicacaoDTO[$dblIdProtocolo]) && $arrObjPublicacaoDTO[$dblIdProtocolo]->getStrStaEstado() == PublicacaoRN::$TE_PUBLICADO) {
              $objProtocoloDTO->setStrSinPublicado('S');
            } else {
              $objProtocoloDTO->setStrSinPublicado('N');
            }
          }
        }

        if (InfraArray::contar($arrIdProcedimentos)) {
          //verifica andamentos abertos dos processos retornados na pesquisa
          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDistinct(true);
          $objAtividadeDTO->retDblIdProtocolo();
          $objAtividadeDTO->retNumIdUsuario();
          $objAtividadeDTO->retStrStaNivelAcessoGlobalProtocolo();
          $objAtividadeDTO->setDthConclusao(null);
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setDblIdProtocolo($arrIdProcedimentos, InfraDTO::$OPER_IN);

          $objAtividadeRN = new AtividadeRN();
          $arrAtividades = InfraArray::indexarArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO), 'IdProtocolo', true);

          //marca documentos dos processos abertos
          foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
            if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
              $dblIdProcesso = $objProtocoloDTO->getDblIdProtocolo();
            } else {
              $dblIdProcesso = $objProtocoloDTO->getDblIdProcedimentoDocumento();
            }

            if (isset($arrAtividades[$dblIdProcesso])) {
              if ($arrAtividades[$dblIdProcesso][0]->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
                $objProtocoloDTO->setStrSinAberto('S');
              } else {
                $arrAtividadesUsuario = InfraArray::indexarArrInfraDTO($arrAtividades[$dblIdProcesso], 'IdUsuario');
                if (isset($arrAtividadesUsuario[SessaoSEI::getInstance()->getNumIdUsuario()])) {
                  $objProtocoloDTO->setStrSinAberto('S');
                }
              }
            }
          }
        }

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->retStrSinArquivamento();
        $objUnidadeDTO->retStrSinProtocolo();
        $objUnidadeDTO->retStrSinOuvidoria();
        $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objUnidadeRN = new UnidadeRN();
        $objUnidadeDTOAtual = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $arrParametros = $objInfraParametro->listarValores(array('SEI_ACESSO_FORMULARIO_OUVIDORIA', 'ID_SERIE_OUVIDORIA', 'SEI_HABILITAR_MOVER_DOCUMENTO'));
        $bolAcessoRestritoOuvidoria = ($arrParametros['SEI_ACESSO_FORMULARIO_OUVIDORIA'] == '1');
        $numIdSerieOuvidoria = $arrParametros['ID_SERIE_OUVIDORIA'];


        if ($arrParametros['SEI_HABILITAR_MOVER_DOCUMENTO'] == '3' || ($arrParametros['SEI_HABILITAR_MOVER_DOCUMENTO'] == '4' && $objUnidadeDTOAtual != null && $objUnidadeDTOAtual->getStrSinProtocolo() == 'N')) {
          $arrIdUnidadeGeradoraRecebido = array();
          foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
            if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
              $arrIdUnidadeGeradoraRecebido[$objProtocoloDTO->getNumIdUnidadeGeradora()] = 0;
            }
          }

          if (count($arrIdUnidadeGeradoraRecebido)) {
            $objUnidadeDTO = new UnidadeDTO();
            $objUnidadeDTO->retNumIdUnidade();
            $objUnidadeDTO->setStrSinProtocolo('S');
            $objUnidadeDTO->setNumIdUnidade(array_keys($arrIdUnidadeGeradoraRecebido), InfraDTO::$OPER_IN);

            $objUnidadeRN = new UnidadeRN();
            $arrObjUnidadeDTO = InfraArray::indexarArrInfraDTO($objUnidadeRN->listarRN0127($objUnidadeDTO), 'IdUnidade');

            foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
              if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
                if (isset($arrObjUnidadeDTO[$objProtocoloDTO->getNumIdUnidadeGeradora()])) {
                  $objProtocoloDTO->setStrSinUnidadeGeradoraProtocolo('S');
                } else {
                  $objProtocoloDTO->setStrSinUnidadeGeradoraProtocolo('N');
                }
              }
            }
          }
        }

        $arrProcessosBlocos = array();
        $arrDocumentosBlocosAssinatura = array();

        if (count($arrIdProcedimentos) || count($arrIdDocumentosGerados)) {
          $objBlocoDTO = new BlocoDTO();
          $objBlocoDTO->setDistinct(true);
          $objBlocoDTO->retStrStaTipo();
          $objBlocoDTO->retDblIdProtocoloRelBlocoProtocolo();
          $objBlocoDTO->retDblIdProcedimentoDocumento();
          $objBlocoDTO->retNumIdUnidade();
          $objBlocoDTO->setStrStaEstado(BlocoRN::$TE_DISPONIBILIZADO);
          $objBlocoDTO->setStrStaTipo(BlocoRN::$TB_INTERNO, InfraDTO::$OPER_DIFERENTE);
          $objBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), InfraDTO::$OPER_DIFERENTE);
          $objBlocoDTO->setStrSinRetornadoRelBlocoUnidade('N');
          $objBlocoDTO->setNumIdUnidadeRelBlocoUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objBlocoDTO->adicionarCriterio(array('IdProtocoloRelBlocoProtocolo', 'IdProcedimentoDocumento'), array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IN),
            array(array_merge($arrIdProcedimentos, $arrIdDocumentosGerados), $arrIdProcedimentos), InfraDTO::$OPER_LOGICO_OR);

          $objBlocoRN = new BlocoRN();
          $arrObjBlocoDTO = $objBlocoRN->listarRN1277($objBlocoDTO);

          foreach ($arrObjBlocoDTO as $objBlocoDTO) {
            if ($objBlocoDTO->getStrStaTipo() == BlocoRN::$TB_ASSINATURA) {
              //acesso ao documento para edição e assinatura
              $arrDocumentosBlocosAssinatura[$objBlocoDTO->getDblIdProtocoloRelBlocoProtocolo()] = 0;

              //permite acesso aos documentos restritos
              $dblIdProcessoBloco = $objBlocoDTO->getDblIdProcedimentoDocumento();
              if (!isset($arrProcessosBlocos[$dblIdProcessoBloco])) {
                $arrProcessosBlocos[$dblIdProcessoBloco] = array();
              }
            } else {
              if ($objBlocoDTO->getStrStaTipo() == BlocoRN::$TB_REUNIAO) {
                //acesso ao processo do bloco de reunião e seus documentos
                $dblIdProcessoBloco = $objBlocoDTO->getDblIdProtocoloRelBlocoProtocolo();
                if (!isset($arrProcessosBlocos[$dblIdProcessoBloco])) {
                  $arrProcessosBlocos[$dblIdProcessoBloco] = array();
                }

                $arrProcessosBlocos[$dblIdProcessoBloco][$objBlocoDTO->getNumIdUnidade()] = 0;
              }
            }
          }
        }

        $arrDocumentosDisponibilizados = array();

        if (count($arrIdDocumentosGerados)) {
          //recupera documentos disponibilizados pela unidade atual
          $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
          $objRelBlocoProtocoloDTO->setDistinct(true);
          $objRelBlocoProtocoloDTO->retDblIdProtocolo();
          $objRelBlocoProtocoloDTO->setNumIdUnidadeBloco(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objRelBlocoProtocoloDTO->setStrStaTipoBloco(BlocoRN::$TB_ASSINATURA);
          $objRelBlocoProtocoloDTO->setStrStaEstadoBloco(BlocoRN::$TE_DISPONIBILIZADO);
          $objRelBlocoProtocoloDTO->setDblIdProtocolo($arrIdDocumentosGerados, InfraDTO::$OPER_IN);

          $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
          $arrDocumentosDisponibilizados = InfraArray::indexarArrInfraDTO($objRelBlocoProtocoloRN->listarRN1291($objRelBlocoProtocoloDTO), 'IdProtocolo');
        }

        //verifica se algum módulo fornece acesso aos protocolos
        $arrAcessoPermitidoModulos = array();
        $arrAcessoNegadoModulos = array();
        foreach ($SEI_MODULOS as $strModulo => $seiModulo) {
          if (($arr = $seiModulo->executar('verificarAcessoProtocolo', $arrObjProcedimentoAPI, $arrObjDocumentoAPI)) != null) {
            foreach ($arr as $dblIdProtocoloModulo => $numTipoAcessoModulo) {
              if ($numTipoAcessoModulo == SeiIntegracao::$TAM_PERMITIDO) {
                if (!isset($arrAcessoPermitidoModulos[$dblIdProtocoloModulo])) {
                  $arrAcessoPermitidoModulos[$dblIdProtocoloModulo] = array();
                }

                $arrAcessoPermitidoModulos[$dblIdProtocoloModulo][] = $strModulo;
              } else {
                if ($numTipoAcessoModulo == SeiIntegracao::$TAM_NEGADO) {
                  if (!isset($arrAcessoNegadoModulos[$dblIdProtocoloModulo])) {
                    $arrAcessoNegadoModulos[$dblIdProtocoloModulo] = array();
                  }

                  $arrAcessoNegadoModulos[$dblIdProtocoloModulo][] = $strModulo;
                } else {
                  throw new InfraException('Tipo de acesso [' . $numTipoAcessoModulo . '] retornado pelo módulo [' . $strModulo . '] inválido.');
                }
              }
            }
          }
        }

        $arrAcessoUnidade = null;
        $arrAcessoUsuario = null;
        $arrIdProtocoloCredencialProcesso = null;
        $arrIdProtocoloCredencialAssinaturaDocumento = null;

        foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
          $dblIdProtocolo = $objProtocoloDTO->getDblIdProtocolo(); //otimizacao
          $strStaProtocolo = $objProtocoloDTO->getStrStaProtocolo(); //otimizacao

          //marca documentos que foram colocados no bloco de assinatura
          if (isset($arrDocumentosBlocosAssinatura[$dblIdProtocolo])) {
            $objProtocoloDTO->setStrSinAcessoAssinaturaBloco('S');
          } else {
            $objProtocoloDTO->setStrSinAcessoAssinaturaBloco('N');
          }

          //marca documentos que foram disponibilizados em bloco de assinatura para outras unidades
          if (isset($arrDocumentosDisponibilizados[$dblIdProtocolo])) {
            $objProtocoloDTO->setStrSinDisponibilizadoParaOutraUnidade('S');
          } else {
            $objProtocoloDTO->setStrSinDisponibilizadoParaOutraUnidade('N');
          }

          //marca rascunhos acessíveis para as unidades que receberam blocos de assinatura ou reunião
          if ($strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
            $objProtocoloDTO->setStrSinAcessoRascunhoBloco('N');

            //se a unidade geradora do documento é a unidade que disponibilizou o processo
            if (isset($arrProcessosBlocos[$objProtocoloDTO->getDblIdProcedimentoDocumentoProcedimento()][$objProtocoloDTO->getNumIdUnidadeGeradora()])) {
              $objProtocoloDTO->setStrSinAcessoRascunhoBloco('S');
            }
          }

          $numCodigoAcesso = self::$CA_NEGADO;
          $objProtocoloDTO->setStrSinCredencialProcesso('N');
          $objProtocoloDTO->setStrSinCredencialAssinatura('N');
          $objProtocoloDTO->setArrAcessoModulos(null);
          $arrAcessoModulos = array();

          //documentos sigilosos
          if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {
            $numCodigoAcesso = self::$CA_SIGILOSO_NEGADO;

            if ($arrAcessoUsuario === null) {
              $arrAcessoUsuario = array();

              $objAcessoDTO = new AcessoDTO();
              $objAcessoDTO->retDblIdProtocolo();
              $objAcessoDTO->retStrStaTipo();
              $objAcessoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
              $objAcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
              $objAcessoDTO->setStrStaTipo(array(
                  AcessoRN::$TA_CREDENCIAL_PROCESSO, AcessoRN::$TA_CREDENCIAL_ASSINATURA_PROCESSO, AcessoRN::$TA_CREDENCIAL_ASSINATURA_DOCUMENTO
                ), InfraDTO::$OPER_IN);
              $objAcessoDTO->setDblIdProtocolo(array_merge($arrIdProcedimentos, $arrIdDocumentosGerados), InfraDTO::$OPER_IN);

              $objAcessoRN = new AcessoRN();
              $arrObjAcessoDTO = $objAcessoRN->listar($objAcessoDTO);

              $arrIdProtocoloCredencialProcesso = array();
              $arrIdProtocoloCredencialAssinaturaDocumento = array();

              foreach ($arrObjAcessoDTO as $objAcessoDTO) {
                if ($objAcessoDTO->getStrStaTipo() == AcessoRN::$TA_CREDENCIAL_PROCESSO) {
                  $arrIdProtocoloCredencialProcesso[$objAcessoDTO->getDblIdProtocolo()] = $objAcessoDTO->getDblIdProtocolo();
                } else {
                  if ($objAcessoDTO->getStrStaTipo() == AcessoRN::$TA_CREDENCIAL_ASSINATURA_DOCUMENTO) {
                    $arrIdProtocoloCredencialAssinaturaDocumento[$objAcessoDTO->getDblIdProtocolo()] = $objAcessoDTO->getDblIdProtocolo();
                  }
                }
                $arrAcessoUsuario[$objAcessoDTO->getDblIdProtocolo()] = true;
              }
            }

            if ($strStaProtocolo == ProtocoloRN::$TP_PROCEDIMENTO) {
              $dblIdProtocoloAcesso = $dblIdProtocolo;
            } else {
              $dblIdProtocoloAcesso = $objProtocoloDTO->getDblIdProcedimentoDocumento();
            }

            //se o usuario tem acesso a este sigiloso
            if (isset($arrAcessoUsuario[$dblIdProtocoloAcesso]) || isset($arrAcessoPermitidoModulos[$dblIdProtocoloAcesso])) {
              if (isset($arrIdProtocoloCredencialProcesso[$dblIdProtocoloAcesso])) {
                $objProtocoloDTO->setStrSinCredencialProcesso('S');
              }

              if (isset($arrIdProtocoloCredencialAssinaturaDocumento[$dblIdProtocolo])) {
                $objProtocoloDTO->setStrSinCredencialAssinatura('S');
              }

              if ($strStaProtocolo == ProtocoloRN::$TP_PROCEDIMENTO) {
                if (isset($arrAcessoUsuario[$dblIdProtocoloAcesso])) {
                  $numCodigoAcesso = self::$CA_SIGILOSO_PROCESSO;
                } else {
                  $numCodigoAcesso = self::$CA_SIGILOSO_MODULO;
                  $arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO] = $arrAcessoPermitidoModulos[$dblIdProtocoloAcesso];
                }
              } else {
                if ($strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
                  $numCodigoAcesso = self::$CA_SIGILOSO_DOC_EXTERNO;
                } else {
                  if ($strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
                    if ($objProtocoloDTO->getNumIdUnidadeGeradora() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
                      $numCodigoAcesso = self::$CA_SIGILOSO_DOC_GERADO_UNIDADE;
                    } else {
                      if ($arrIdProtocoloCredencialAssinaturaDocumento != null && isset($arrIdProtocoloCredencialAssinaturaDocumento[$dblIdProtocolo])) {
                        $numCodigoAcesso = self::$CA_SIGILOSO_CREDENCIAL_ASSINATURA;
                      } else {
                        if ($objProtocoloDTO->getStrSinAssinado() == 'S') {
                          $numCodigoAcesso = self::$CA_SIGILOSO_ASSINADO;
                        } else {
                          if ($objProtocoloDTO->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {
                            $numCodigoAcesso = self::$CA_SIGILOSO_FORMULARIO_AUTOMATICO;
                          } else {
                            if (isset($arrAcessoPermitidoModulos[$dblIdProtocolo])) {
                              $numCodigoAcesso = self::$CA_SIGILOSO_MODULO;
                              $arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO] = $arrAcessoPermitidoModulos[$dblIdProtocolo];
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            } else {
              if (isset($arrAcessoPermitidoModulos[$dblIdProtocolo])) {
                $numCodigoAcesso = self::$CA_SIGILOSO_MODULO;
                $arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO] = $arrAcessoPermitidoModulos[$dblIdProtocolo];
              }
            }
          } else {
            if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_RESTRITO) {
              $numCodigoAcesso = self::$CA_RESTRITO_NEGADO;

              //filtra protocolos restritos do resultado nos quais a unidade tem acesso
              //busca apenas uma vez e somente após encontrar o primeiro restrito
              if ($arrAcessoUnidade === null) {
                $objAcessoDTO = new AcessoDTO();
                $objAcessoDTO->setDistinct(true);
                $objAcessoDTO->retDblIdProtocolo();
                $objAcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                $objAcessoDTO->setDblIdProtocolo($arrIdProcedimentos, InfraDTO::$OPER_IN);
                //$objAcessoDTO->setNumIdUsuario(null);
                $objAcessoDTO->setStrStaTipo(array(AcessoRN::$TA_RESTRITO_UNIDADE, AcessoRN::$TA_CONTROLE_INTERNO), InfraDTO::$OPER_IN);

                $objAcessoRN = new AcessoRN();
                $arrAcessoUnidade = InfraArray::indexarArrInfraDTO($objAcessoRN->listar($objAcessoDTO), 'IdProtocolo');
              }

              if ($strStaProtocolo == ProtocoloRN::$TP_PROCEDIMENTO) {
                $dblIdProtocoloAcesso = $dblIdProtocolo;
              } else {
                $dblIdProtocoloAcesso = $objProtocoloDTO->getDblIdProcedimentoDocumento();
              }

              //se a unidade tem acesso a este restrito
              if (isset($arrAcessoUnidade[$dblIdProtocoloAcesso]) || isset($arrAcessoPermitidoModulos[$dblIdProtocoloAcesso])) {
                if ($strStaProtocolo == ProtocoloRN::$TP_PROCEDIMENTO) {
                  if (isset($arrAcessoUnidade[$dblIdProtocoloAcesso])) {
                    $numCodigoAcesso = self::$CA_RESTRITO_PROCESSO;
                  } else {
                    $numCodigoAcesso = self::$CA_RESTRITO_MODULO;
                    $arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO] = $arrAcessoPermitidoModulos[$dblIdProtocoloAcesso];
                  }
                } else {
                  if ($strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
                    $numCodigoAcesso = self::$CA_RESTRITO_DOC_EXTERNO;
                  } else {
                    if ($strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
                      if ($objProtocoloDTO->getNumIdUnidadeGeradora() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
                        $numCodigoAcesso = self::$CA_RESTRITO_DOC_GERADO_UNIDADE;
                      } else {
                        if ($objProtocoloDTO->getStrSinAssinado() == 'S') {
                          $numCodigoAcesso = self::$CA_RESTRITO_ASSINADO;
                        } else {
                          if ($objProtocoloDTO->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {
                            $numCodigoAcesso = self::$CA_RESTRITO_FORMULARIO_AUTOMATICO;
                          } else {
                            if (isset($arrAcessoPermitidoModulos[$dblIdProtocolo])) {
                              $numCodigoAcesso = self::$CA_RESTRITO_MODULO;
                              $arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO] = $arrAcessoPermitidoModulos[$dblIdProtocolo];
                            }
                          }
                        }
                      }
                    }
                  }
                }
              } else {
                if (isset($arrAcessoPermitidoModulos[$dblIdProtocolo])) {
                  $numCodigoAcesso = self::$CA_RESTRITO_MODULO;
                  $arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO] = $arrAcessoPermitidoModulos[$dblIdProtocolo];
                }
              }
            } else {
              if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_PUBLICO) {
                $numCodigoAcesso = self::$CA_PUBLICO_NEGADO;

                if ($strStaProtocolo == ProtocoloRN::$TP_PROCEDIMENTO) {
                  $numCodigoAcesso = self::$CA_PUBLICO_PROCESSO;
                } else {
                  if ($strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
                    $numCodigoAcesso = self::$CA_PUBLICO_DOC_EXTERNO;
                  } else {
                    if ($strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
                      if ($objProtocoloDTO->getNumIdUnidadeGeradora() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
                        $numCodigoAcesso = self::$CA_PUBLICO_DOC_GERADO_UNIDADE;
                      } else {
                        if ($objProtocoloDTO->getStrSinAssinado() == 'S') {
                          $numCodigoAcesso = self::$CA_PUBLICO_ASSINADO;
                        } else {
                          if ($objProtocoloDTO->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {
                            $numCodigoAcesso = self::$CA_PUBLICO_FORMULARIO_AUTOMATICO;
                          } else {
                            if (isset($arrAcessoPermitidoModulos[$dblIdProtocolo])) {
                              $numCodigoAcesso = self::$CA_PUBLICO_MODULO;
                              $arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO] = $arrAcessoPermitidoModulos[$dblIdProtocolo];
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }

          if ($objUnidadeDTOAtual != null && $numCodigoAcesso < 0 && $numCodigoAcesso != self::$CA_SIGILOSO_NEGADO) {
            if ($objUnidadeDTOAtual->getStrSinProtocolo() == 'S') {
              if ($strStaProtocolo == ProtocoloRN::$TP_PROCEDIMENTO) {
                $numCodigoAcesso = self::$CA_UNIDADE_PROTOCOLO;
              } elseif ($strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO && $objProtocoloDTO->getNumIdUnidadeGeradora() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
                $numCodigoAcesso = self::$CA_UNIDADE_PROTOCOLO;
              }
            }

            if ($numCodigoAcesso < 0 && $objUnidadeDTOAtual->getStrSinArquivamento() == 'S' && $strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO && $objProtocoloDTO->getStrSinArquivamentoDocumento() == 'S') {
              $numCodigoAcesso = self::$CA_UNIDADE_ARQUIVAMENTO;
            }
          }

          //verifica acesso através de blocos disponibilizados para a unidade exceto para sigilosos
          if ($numCodigoAcesso < 0 && $numCodigoAcesso != self::$CA_SIGILOSO_NEGADO) {
            if ($strStaProtocolo == ProtocoloRN::$TP_PROCEDIMENTO) {
              //se o processo restrito esta em um bloco disponibilizado para a unidade
              if (isset($arrProcessosBlocos[$dblIdProtocolo])) {
                $numCodigoAcesso = self::$CA_BLOCO;
              }
            } else {
              //documento esta em um bloco de assinatura disponibilizado para a unidade
              if (isset($arrDocumentosBlocosAssinatura[$dblIdProtocolo])) {
                $numCodigoAcesso = self::$CA_BLOCO;
              } else {
                if ($strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
                  if ($objProtocoloDTO->getStrSinAssinado() == 'S' || $objProtocoloDTO->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {
                    //o processo do documento esta em um bloco disponibilizado para a unidade OU é o mesmo processo de outro documento que esta em um bloco de assinatura que foi disponibilizado para a unidade
                    if (isset($arrProcessosBlocos[$objProtocoloDTO->getDblIdProcedimentoDocumentoProcedimento()])) {
                      $numCodigoAcesso = self::$CA_BLOCO;
                    }
                  } else {
                    if ($objProtocoloDTO->getStrSinAcessoRascunhoBloco() == 'S') {
                      $numCodigoAcesso = self::$CA_BLOCO;
                    }
                  }
                  //o processo do documento esta em um bloco disponibilizado para a unidade OU é o mesmo processo de outro documento que esta em um bloco de assinatura que foi disponibilizado para a unidade
                } else {
                  if (isset($arrProcessosBlocos[$objProtocoloDTO->getDblIdProcedimentoDocumentoProcedimento()])) {
                    $numCodigoAcesso = self::$CA_BLOCO;
                  }
                }
              }
            }
          }

          if ($numCodigoAcesso > 0 && $bolAcessoRestritoOuvidoria && $strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO && $objProtocoloDTO->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO && $objProtocoloDTO->getNumIdSerieDocumento() == $numIdSerieOuvidoria && ($objUnidadeDTOAtual->getStrSinOuvidoria() == 'N' || $objProtocoloDTO->getNumIdUnidadeGeradora() != SessaoSEI::getInstance()->getNumIdUnidadeAtual())) {
            $numCodigoAcesso = self::$CA_OUVIDORIA_RESTRITO;
          }

          if ($numCodigoAcesso > 0 && isset($arrAcessoNegadoModulos[$dblIdProtocolo])) {
            if (isset($arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO])) {
              unset($arrAcessoModulos[SeiIntegracao::$TAM_PERMITIDO]);
            }

            $arrAcessoModulos[SeiIntegracao::$TAM_NEGADO] = $arrAcessoNegadoModulos[$dblIdProtocolo];

            $numCodigoAcesso = self::$CA_MODULO_NEGADO;
          }

          if ($numCodigoAcesso > 0 && $objProtocoloDTO->getStrStaEstado() == ProtocoloRN::$TE_DOCUMENTO_CANCELADO && ($strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO || $strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO)) {
            $numCodigoAcesso = self::$CA_DOCUMENTO_CANCELADO;
          }

          //se documento publicado
          if ($numCodigoAcesso < 0 && $strStaProtocolo == ProtocoloRN::$TP_DOCUMENTO_GERADO && $objProtocoloDTO->getStrSinPublicado() == 'S') {
            $numCodigoAcesso = self::$CA_DOCUMENTO_PUBLICADO;
          }

          if ($objProtocoloDTO->getStrSinEliminado() == 'S') {
            $numCodigoAcesso = self::$CA_ELIMINADO;
          }

          $objProtocoloDTO->setNumCodigoAcesso($numCodigoAcesso);
          $objProtocoloDTO->setArrAcessoModulos($arrAcessoModulos);
        }

        if ($objPesquisaProtocoloDTO->getStrStaAcesso() == ProtocoloRN::$TAP_AUTORIZADO) {
          $arrTemp = array();
          foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
            if ($objProtocoloDTO->getNumCodigoAcesso() > 0) {
              $arrTemp[] = $objProtocoloDTO;
            }
          }
          $arrObjProtocoloDTO = $arrTemp;
        } else {
          if ($objPesquisaProtocoloDTO->getStrStaAcesso() == ProtocoloRN::$TAP_TODOS_EXCETO_SIGILOSOS_SEM_ACESSO) {
            $arrTemp = array();
            foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
              if ($objProtocoloDTO->getNumCodigoAcesso() != self::$CA_SIGILOSO_NEGADO) {
                $arrTemp[] = $objProtocoloDTO;
              }
            }
            $arrObjProtocoloDTO = $arrTemp;
          }
        }
      }

      return $arrObjProtocoloDTO;
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro pesquisando protocolo.', $e);
    }
  }

  private function validarStrDescricaoRN1229(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objProtocoloDTO->getStrDescricao())) {
      $objProtocoloDTO->setStrDescricao(null);
    }

    $objProtocoloDTO->setStrDescricao(trim($objProtocoloDTO->getStrDescricao()));

    if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
      if (strlen($objProtocoloDTO->getStrDescricao()) > 100) {
        $objInfraException->adicionarValidacao('Especificação possui tamanho superior a 100 caracteres.');
      }
    } else {
      if (strlen($objProtocoloDTO->getStrDescricao()) > 250) {
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  protected function mudarNivelAcessoControlado(MudarNivelAcessoDTO $objMudarNivelAcessoDTO) {
    try {
      $objInfraException = new InfraException();

      if (!$objMudarNivelAcessoDTO->isSetStrSinLancarAndamento()) {
        $objMudarNivelAcessoDTO->setStrSinLancarAndamento('S');
      }

      //descobrir tipo do protocolo
      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retStrStaNivelAcessoGlobal();
      $objProtocoloDTO->retStrStaProtocolo();
      $objProtocoloDTO->retStrStaEstado();
      $objProtocoloDTO->setDblIdProtocolo($objMudarNivelAcessoDTO->getDblIdProtocolo());

      $objProtocoloDTO = $this->consultarRN0186($objProtocoloDTO);

      if ($objProtocoloDTO == null) {
        throw new InfraException('Protocolo [' . $objMudarNivelAcessoDTO->getDblIdProtocolo() . '] não encontrado.');
      }

      $strStaNivelAcessoGlobalInicial = $objProtocoloDTO->getStrStaNivelAcessoGlobal();

      $objDocumentoRN = new DocumentoRN();
      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objProtocoloBD = new ProtocoloBD(BancoSEI::getInstance());

      $dblIdProcesso = null;
      $bolFlagAnexado = false;

      if ($objProtocoloDTO->getStrStaProtocolo() == self::$TP_PROCEDIMENTO) {
        if ($objMudarNivelAcessoDTO->getStrStaOperacao() == self::$TMN_EXCLUSAO) {
          return $objProtocoloDTO->getStrStaNivelAcessoGlobal(); //excluindo processo não causa alteração de nível
        }

        $dblIdProcesso = $objMudarNivelAcessoDTO->getDblIdProtocolo();

        if ($objProtocoloDTO->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
          $bolFlagAnexado = true;
        }
      } else {
        //recuperar processo do documento
        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
        $objRelProtocoloProtocoloDTO->retStrStaEstadoProtocolo1();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objMudarNivelAcessoDTO->getDblIdProtocolo());
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);

        $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

        $dblIdProcesso = $objRelProtocoloProtocoloDTO->getDblIdProtocolo1();

        if ($objRelProtocoloProtocoloDTO->getStrStaEstadoProtocolo1() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
          $bolFlagAnexado = true;
        }
      }

      if ($bolFlagAnexado) {
        if ($objMudarNivelAcessoDTO->getStrStaOperacao() != self::$TMN_ALTERACAO) {
          throw new InfraException('Operação [' . $objMudarNivelAcessoDTO->getStrStaOperacao() . '] inválida na alteração de Nível de Acesso.');
        }

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->setDblIdProcedimento($dblIdProcesso);
        $arrIdDocumentosAnexados = InfraArray::converterArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO), 'IdDocumento');

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retStrStaNivelAcessoLocal();
        $objProtocoloDTO->setDblIdProtocolo(array_merge(array($dblIdProcesso), $arrIdDocumentosAnexados), InfraDTO::$OPER_IN);
        $arrObjProtocoloDTO = $this->listarRN0668($objProtocoloDTO);

        $numMaiorNivelAnexado = 0;
        foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
          if (((int)$objProtocoloDTO->getStrStaNivelAcessoLocal()) > $numMaiorNivelAnexado && $objProtocoloDTO->getDblIdProtocolo() != $objMudarNivelAcessoDTO->getDblIdProtocolo()) {
            $numMaiorNivelAnexado = (int)$objProtocoloDTO->getStrStaNivelAcessoLocal();
          }
        }

        if (((int)$objMudarNivelAcessoDTO->getStrStaNivel()) > $numMaiorNivelAnexado) {
          $numMaiorNivelAnexado = (int)$objMudarNivelAcessoDTO->getStrStaNivel();
        }

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retStrStaNivelAcessoOriginal();
        $objProtocoloDTO->setDblIdProtocolo($dblIdProcesso);
        $objProtocoloDTOAnexado = $this->consultarRN0186($objProtocoloDTO);

        if (((int)$numMaiorNivelAnexado) != (int)$objProtocoloDTOAnexado->getStrStaNivelAcessoOriginal()) {
          $dto = new ProtocoloDTO();
          $dto->setStrStaNivelAcessoOriginal((string)$numMaiorNivelAnexado);
          $dto->setDblIdProtocolo($dblIdProcesso);
          $objProtocoloBD->alterar($dto);
        }

        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($dblIdProcesso);
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
        $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

        $dblIdProcesso = $objRelProtocoloProtocoloDTO->getDblIdProtocolo1();
      }

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($dblIdProcesso);
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
      $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

      $arrProcessos = InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO, 'IdProtocolo2');
      $arrProcessos[] = $dblIdProcesso;

      //obter documentos dos processos
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->setDblIdProcedimento($arrProcessos, InfraDTO::$OPER_IN);

      $arrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO), 'IdDocumento');
      $arrProtocolos = array_merge($arrProcessos, array_keys($arrObjDocumentoDTO));

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retDblIdProtocolo();
      $objProtocoloDTO->retStrStaNivelAcessoLocal();
      $objProtocoloDTO->retStrStaNivelAcessoGlobal();
      $objProtocoloDTO->setDblIdProtocolo($arrProtocolos, InfraDTO::$OPER_IN);

      $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($this->listarRN0668($objProtocoloDTO), 'IdProtocolo');


      $numMaiorNivel = 0;
      foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
        if (((int)$objProtocoloDTO->getStrStaNivelAcessoLocal()) > $numMaiorNivel) {
          //se não for alteracao ou exclusao do protocolo que esta sendo analisado
          if (!(($objMudarNivelAcessoDTO->getStrStaOperacao() == self::$TMN_ALTERACAO || $objMudarNivelAcessoDTO->getStrStaOperacao() == self::$TMN_EXCLUSAO) && $objProtocoloDTO->getDblIdProtocolo() == $objMudarNivelAcessoDTO->getDblIdProtocolo())) {
            $numMaiorNivel = (int)$objProtocoloDTO->getStrStaNivelAcessoLocal();
          }
        }
      }

      if ($objMudarNivelAcessoDTO->getStrStaOperacao() == self::$TMN_CADASTRO || $objMudarNivelAcessoDTO->getStrStaOperacao() == self::$TMN_ALTERACAO) {
        //verifica se o nível cadastrado ou alterado tem influencia
        if (((int)$objMudarNivelAcessoDTO->getStrStaNivel()) > $numMaiorNivel) {
          $numMaiorNivel = (int)$objMudarNivelAcessoDTO->getStrStaNivel();
        }
      }

      //atualiza o nível global de todos os protocolos
      $arrObjProtocoloDTOAlteracao = array();
      $arrIdProcessosAlteracao = array();

      foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
        if ((int)$objProtocoloDTO->getStrStaNivelAcessoGlobal() != $numMaiorNivel || $objMudarNivelAcessoDTO->getStrStaOperacao() == self::$TMN_ANEXACAO || $objMudarNivelAcessoDTO->getStrStaOperacao() == self::$TMN_DESANEXACAO) {
          $dto = new ProtocoloDTO();
          $dto->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
          $dto->setStrStaNivelAcessoGlobal((string)$numMaiorNivel);
          $arrObjProtocoloDTOAlteracao[] = $dto;

          if ((int)$objProtocoloDTO->getStrStaNivelAcessoGlobal() != $numMaiorNivel) {
            if (in_array($objProtocoloDTO->getDblIdProtocolo(), $arrProcessos)) {
              $arrIdProcessosAlteracao[$objProtocoloDTO->getDblIdProtocolo()] = 0;
            } else {
              if (isset($arrObjDocumentoDTO[$objProtocoloDTO->getDblIdProtocolo()])) {
                $arrIdProcessosAlteracao[$arrObjDocumentoDTO[$objProtocoloDTO->getDblIdProtocolo()]->getDblIdProcedimento()] = 0;
              }
            }
          }
        }
      }

      $objAtividadeRN = new AtividadeRN();

      if (InfraArray::contar($arrObjProtocoloDTOAlteracao)) {
        if ($objMudarNivelAcessoDTO->getStrSinLancarAndamento() == 'S') {
          foreach (array_keys($arrIdProcessosAlteracao) as $dblIdProcessoAlteracao) {
            $arrObjAtributoAndamentoDTO = array();
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('NIVEL_ACESSO');
            $objAtributoAndamentoDTO->setStrValor(null);
            $objAtributoAndamentoDTO->setStrIdOrigem((string)$numMaiorNivel);
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($dblIdProcessoAlteracao);
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_NIVEL_ACESSO_GLOBAL);
            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

            if ((string)$numMaiorNivel == ProtocoloRN::$NA_SIGILOSO) {
              $objAtividadeDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
            } else {
              $objAtividadeDTO->setNumIdUsuario(null);
            }

            $objAtividadeDTOAlteracaoNivel = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
          }
        }

        //IMPORTANTE: altera protocolos APÓS lançar andamento (se for uma alteração para sigiloso não encontrará andamentos abertos para o usuário)
        foreach ($arrObjProtocoloDTOAlteracao as $dto) {
          $objProtocoloBD->alterar($dto);
        }

        //Deixou de ser sigiloso
        if ((string)$numMaiorNivel != ProtocoloRN::$NA_SIGILOSO && $arrObjProtocoloDTO[$dblIdProcesso]->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {
          $objAtividadeDTO = new AtividadeDTO();
          if ($objMudarNivelAcessoDTO->getStrSinLancarAndamento() == 'S') {
            $objAtividadeDTO->setNumIdAtividade($objAtividadeDTOAlteracaoNivel->getNumIdAtividade());
          } else {
            $objAtividadeDTO->setNumIdAtividade(null);
          }
          $objAtividadeDTO->setDblIdProtocolo($dblIdProcesso);
          $objAtividadeRN->anularCredenciaisProcesso($objAtividadeDTO);
        }

        //Objetos para indexação
        $objIndexacaoRN = new IndexacaoRN();

        if ($numMaiorNivel == ProtocoloRN::$NA_PUBLICO) {
          //remove protocolos das unidades
          $objAcessoDTO = new AcessoDTO();
          $objAcessoDTO->retNumIdAcesso();
          $objAcessoDTO->setDblIdProtocolo($arrProtocolos, InfraDTO::$OPER_IN);

          $objAcessoRN = new AcessoRN();
          $objAcessoRN->excluir($objAcessoRN->listar($objAcessoDTO));

          $objIndexacaoDTO = new IndexacaoDTO();
          $objIndexacaoDTO->setArrIdProtocolos($arrProtocolos);
          $objIndexacaoDTO->setStrStaOperacao(($strStaNivelAcessoGlobalInicial == ProtocoloRN::$NA_SIGILOSO ? IndexacaoRN::$TO_PROTOCOLO_METADADOS_E_CONTEUDO : IndexacaoRN::$TO_PROTOCOLO_ACESSO));
          $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);
        } else {
          if ($numMaiorNivel == ProtocoloRN::$NA_RESTRITO) {
            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDistinct(true);
            $objAtividadeDTO->retNumIdUnidade();
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::getArrTarefasTramitacao(), InfraDTO::$OPER_IN);
            $objAtividadeDTO->setDblIdProtocolo($dblIdProcesso);

            $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

            if (count($arrObjAtividadeDTO)) {
              //remove credenciais de sigilosos (se houver)
              $objAcessoDTO = new AcessoDTO();
              $objAcessoDTO->retNumIdAcesso();
              //$objAcessoDTO->setNumIdUsuario(null,InfraDTO::$OPER_DIFERENTE);
              $objAcessoDTO->setStrStaTipo(array(
                AcessoRN::$TA_RESTRITO_UNIDADE, AcessoRN::$TA_CONTROLE_INTERNO, AcessoRN::$TA_CREDENCIAL_PROCESSO, AcessoRN::$TA_CREDENCIAL_ASSINATURA_PROCESSO, AcessoRN::$TA_CREDENCIAL_ASSINATURA_DOCUMENTO
              ), InfraDTO::$OPER_IN);
              $objAcessoDTO->setDblIdProtocolo($arrProtocolos, InfraDTO::$OPER_IN);

              $objAcessoRN = new AcessoRN();
              $objAcessoRN->excluir($objAcessoRN->listar($objAcessoDTO));

              foreach ($arrProcessos as $dblIdProcessoAcesso) {
                foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
                  $objAcessoDTO = new AcessoDTO();
                  $objAcessoDTO->setNumIdAcesso(null);
                  $objAcessoDTO->setDblIdProtocolo($dblIdProcessoAcesso);
                  $objAcessoDTO->setNumIdUnidade($objAtividadeDTO->getNumIdUnidade());
                  $objAcessoDTO->setNumIdUsuario(null);
                  $objAcessoDTO->setNumIdControleInterno(null);
                  $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_RESTRITO_UNIDADE);

                  $objAcessoRN->cadastrar($objAcessoDTO);
                }
              }
            }

            $objControleInternoDTO = new ControleInternoDTO();
            $objControleInternoDTO->setDblIdProcedimento($dblIdProcesso);
            $objControleInternoDTO->setArrIdProcessos($arrProcessos);
            $objControleInternoDTO->setArrIdProtocolos($arrProtocolos);
            $objControleInternoDTO->setStrStaNivelAcessoGlobal(ProtocoloRN::$NA_RESTRITO);
            $objControleInternoDTO->setStrStaOperacao(ControleInternoRN::$TO_MUDANCA_NIVEL_ACESSO);

            $objControleInternoRN = new ControleInternoRN();
            $objControleInternoRN->processar($objControleInternoDTO);

            $objIndexacaoDTO = new IndexacaoDTO();
            $objIndexacaoDTO->setArrIdProtocolos($arrProtocolos);
            $objIndexacaoDTO->setStrStaOperacao(($strStaNivelAcessoGlobalInicial == ProtocoloRN::$NA_SIGILOSO ? IndexacaoRN::$TO_PROTOCOLO_METADADOS_E_CONTEUDO : IndexacaoRN::$TO_PROTOCOLO_ACESSO));
            $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);
          } else {
            if ($numMaiorNivel == ProtocoloRN::$NA_SIGILOSO) {
              $objProtocoloDTO = new ProtocoloDTO();
              $objProtocoloDTO->retStrProtocoloFormatado();
              $objProtocoloDTO->setDblIdProtocolo($dblIdProcesso);
              $objProtocoloDTO = $this->consultarRN0186($objProtocoloDTO);

              //verifica se o processo esta aberto em outras unidades
              $objAtividadeDTO = new AtividadeDTO();
              $objAtividadeDTO->setDistinct(true);
              $objAtividadeDTO->retStrSiglaUnidade();
              $objAtividadeDTO->setDblIdProtocolo($dblIdProcesso);
              $objAtividadeDTO->setDthConclusao(null);
              $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), InfraDTO::$OPER_DIFERENTE);

              $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

              if (count($arrObjAtividadeDTO) == 1) {
                $objInfraException->lancarValidacao('Não é possível alterar o nível de acesso para sigiloso porque o processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' está aberto na unidade ' . $arrObjAtividadeDTO[0]->getStrSiglaUnidade() . '.');
              } else {
                if (count($arrObjAtividadeDTO) > 1) {
                  $objInfraException->lancarValidacao('Não é possível alterar o nível de acesso para sigiloso porque o processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' está aberto nas unidades:\n' . implode('\n',
                      InfraArray::converterArrInfraDTO($arrObjAtividadeDTO, 'SiglaUnidade')));
                }
              }

              if (InfraArray::contar($arrObjRelProtocoloProtocoloDTO)) {
                $objInfraException->lancarValidacao('Não é possível alterar o nível de acesso para sigiloso porque o processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' possui processos anexados.');
              }

              //remove protocolos das unidades
              $objAcessoDTO = new AcessoDTO();
              $objAcessoDTO->retNumIdAcesso();
              $objAcessoDTO->setStrStaTipo(array(AcessoRN::$TA_RESTRITO_UNIDADE, AcessoRN::$TA_CONTROLE_INTERNO), InfraDTO::$OPER_IN);
              $objAcessoDTO->setDblIdProtocolo($arrProtocolos, InfraDTO::$OPER_IN);

              $objAcessoRN = new AcessoRN();
              $objAcessoRN->excluir($objAcessoRN->listar($objAcessoDTO));


              foreach ($arrProcessos as $dblIdProcessoAcesso) {
                $objAcessoDTO = new AcessoDTO();
                $objAcessoDTO->setNumIdAcesso(null);
                $objAcessoDTO->setDblIdProtocolo($dblIdProcessoAcesso);
                $objAcessoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                $objAcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                $objAcessoDTO->setNumIdControleInterno(null);
                $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CREDENCIAL_PROCESSO);
                $objAcessoRN->cadastrar($objAcessoDTO);
              }

              $objIndexacaoDTO = new IndexacaoDTO();
              $objIndexacaoDTO->setArrIdProtocolos($arrProtocolos);
              $objIndexacaoRN->prepararRemocaoProtocolo($objIndexacaoDTO);
              FeedSEIProtocolos::getInstance()->indexarFeeds();
            } else {
              throw new InfraException('Nível de acesso [' . $numMaiorNivel . '] inválido.');
            }
          }
        }
      }

      return (string)$numMaiorNivel;
    } catch (Exception $e) {
      throw new InfraException('Erro mudando nível de acesso.', $e);
    }
  }

  protected function associarRN0982Controlado(AssociarDTO $objAssociarDTO) {
    try {
      //Regras de Negocio
      $objInfraException = new InfraException();

      switch ($objAssociarDTO->getStrStaNivelAcessoGlobal()) {
        case ProtocoloRN::$NA_PUBLICO:
          return;

        case ProtocoloRN::$NA_RESTRITO:

          $objAcessoRN = new AcessoRN();

          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
          $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objAssociarDTO->getDblIdProcedimento());

          $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
          $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

          $arrIdProcedimentos = InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO, 'IdProtocolo2');
          $arrIdProcedimentos[] = $objAssociarDTO->getDblIdProcedimento();

          foreach ($arrIdProcedimentos as $dblIdProcedimento) {
            //associando processo com uma unidade replicar para os documentos
            $objAcessoDTO = new AcessoDTO();
            $objAcessoDTO->retNumIdAcesso();
            $objAcessoDTO->setDblIdProtocolo($dblIdProcedimento);
            $objAcessoDTO->setNumIdUnidade($objAssociarDTO->getNumIdUnidade());
            $objAcessoDTO->setNumIdUsuario(null);
            $objAcessoDTO->setNumIdControleInterno(null);
            $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_RESTRITO_UNIDADE);
            $objAcessoDTO->setNumMaxRegistrosRetorno(1);

            //se a unidade ainda não possui acesso ao processo
            if ($objAcessoRN->consultar($objAcessoDTO) == null) {
              $objAcessoDTO->setNumIdAcesso(null);
              $objAcessoRN->cadastrar($objAcessoDTO);
            }
          }
          break;

        case ProtocoloRN::$NA_SIGILOSO:

          $objAcessoRN = new AcessoRN();

          //associando processo com um usuário replicar para os documentos
          $objAcessoDTO = new AcessoDTO();
          $objAcessoDTO->retNumIdAcesso();
          $objAcessoDTO->setDblIdProtocolo($objAssociarDTO->getDblIdProcedimento());
          $objAcessoDTO->setNumIdUsuario($objAssociarDTO->getNumIdUsuario());
          $objAcessoDTO->setNumIdUnidade($objAssociarDTO->getNumIdUnidade());
          $objAcessoDTO->setNumIdControleInterno(null);
          $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CREDENCIAL_PROCESSO);
          $objAcessoDTO->setNumMaxRegistrosRetorno(1);

          //se o usuário ainda não possui acesso ao processo
          if ($objAcessoRN->consultar($objAcessoDTO) == null) {
            $objAcessoDTO->setNumIdAcesso(null);
            $objAcessoRN->cadastrar($objAcessoDTO);
          }

          break;

        default:
          throw new InfraException('Nível de acesso inválido na associação entre protocolo e usuário.');
      }
    } catch (Exception $e) {
      throw new InfraException('Erro associando protocolo.', $e);
    }
  }

  protected function obterSequenciaConectado(ProtocoloDTO $parObjProtocoloDTO) {
    $ret = 0;

    $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
    $objRelProtocoloProtocoloDTO->retNumSequencia();
    $objRelProtocoloProtocoloDTO->setStrStaAssociacao(array(
        RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO, RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO, RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO, RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_DESANEXADO
      ), InfraDTO::$OPER_IN);
    $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjProtocoloDTO->getDblIdProcedimento());
    $objRelProtocoloProtocoloDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objRelProtocoloProtocoloDTO->setNumMaxRegistrosRetorno(1);

    $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
    $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

    if (count($arrObjRelProtocoloProtocoloDTO) > 0) {
      $ret = $arrObjRelProtocoloProtocoloDTO[0]->getNumSequencia() + 1;
    }

    return $ret;
  }

  protected function alterarOrdemControlado(ProcedimentoDTO $objProcedimentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('arvore_ordenar', __METHOD__, $objProcedimentoDTO);

      $arrOrdemNova = InfraArray::converterArrInfraDTO($objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO(), 'IdRelProtocoloProtocolo');

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTO->retNumSequencia();
      $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo($arrOrdemNova, InfraDTO::$OPER_IN);
      $objRelProtocoloProtocoloDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

      $arrOrdemBanco = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO), 'IdRelProtocoloProtocolo');

      if ($arrOrdemBanco !== $arrOrdemNova) {
        foreach ($objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO() as $objRelProtocoloProtocoloDTO) {
          $dto = new RelProtocoloProtocoloDTO();
          $dto->setNumSequencia($objRelProtocoloProtocoloDTO->getNumSequencia());
          $dto->setDblIdRelProtocoloProtocolo($objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo());
          $objRelProtocoloProtocoloRN->alterar($dto);
        }

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_ALTERACAO_ORDEM_ARVORE);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro alterando ordem dos protocolos.', $e);
    }
  }

  private function validarProtocoloInformado(ProtocoloDTO $objProtocoloDTO, InfraException $objInfraException) {
    //Não validar na RN - API e Web Services
    //$objProcedimentoDTO = new ProcedimentoDTO();
    //$objProcedimentoDTO->setDblIdProcedimento($objProtocoloDTO->getDblIdProtocolo());

    //$objProcedimentoRN = new ProcedimentoRN();
    //if (!$objProcedimentoRN->verificarLiberacaoNumeroProcesso($objProcedimentoDTO)){
    //  $objInfraException->lancarValidacao('Não é possível informar o número do processo.');
    //}

    if (SessaoSEI::getInstance()->isBolHabilitada()) {
      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $arrMascaras = explode('|', $objInfraParametro->getValor('SEI_MASCARA_NUMERO_PROCESSO_INFORMADO'));

      $bolValidou = false;
      $bolNumeroValido = false;
      foreach ($arrMascaras as $strMascara) {
        if (!InfraString::isBolVazia($strMascara)) {
          $bolValidou = true;
          if (InfraUtil::validarMascara($objProtocoloDTO->getStrProtocoloFormatado(), trim($strMascara))) {
            $bolNumeroValido = true;
            break;
          }
        }
      }

      if ($bolValidou && !$bolNumeroValido) {
        $objInfraException->adicionarValidacao("Número de processo informado inválido.");
      }
    }
  }

  protected function pesquisarProtocoloFormatadoConectado(ProtocoloDTO $parObjProtocoloDTO) {
    try {
      $strSinFederacao = 'N';

      //busca pelo numero do processo
      $objProtocoloDTOPesquisaBase = new ProtocoloDTO();
      $objProtocoloDTOPesquisaBase->retDblIdProtocolo();
      $objProtocoloDTOPesquisaBase->retStrProtocoloFormatado();
      $objProtocoloDTOPesquisaBase->retStrIdProtocoloFederacao();
      $objProtocoloDTOPesquisaBase->retStrStaProtocolo();
      $objProtocoloDTOPesquisaBase->retStrStaNivelAcessoGlobal();
      $objProtocoloDTOPesquisaBase->setNumMaxRegistrosRetorno(2);

      $objProtocoloDTOPesquisa = clone($objProtocoloDTOPesquisaBase);
      $objProtocoloDTOPesquisaInv = clone($objProtocoloDTOPesquisaBase);

      $strProtocoloPesquisa = InfraUtil::retirarFormatacao($parObjProtocoloDTO->getStrProtocoloFormatadoPesquisa(), false);

      $objProtocoloDTOPesquisa->setStrProtocoloFormatadoPesquisa($strProtocoloPesquisa);
      $arrObjProtocoloDTO = $this->listarRN0668($objProtocoloDTOPesquisa);
      if (count($arrObjProtocoloDTO) == 0) {
        $objProtocoloDTOPesquisa->setStrProtocoloFormatadoPesquisa($strProtocoloPesquisa . '%', InfraDTO::$OPER_LIKE);
        $arrObjProtocoloDTO = $this->listarRN0668($objProtocoloDTOPesquisa);

        if (count($arrObjProtocoloDTO) == 0) {
          $objProtocoloDTOPesquisaInv->setStrProtocoloFormatadoPesqInv(strrev($strProtocoloPesquisa) . '%', InfraDTO::$OPER_LIKE);
          $arrObjProtocoloDTO = $this->listarRN0668($objProtocoloDTOPesquisaInv);

          if (ConfiguracaoSEI::getInstance()->getValor('Federacao', 'Habilitado', false, false) == true) {
            $objProtocoloFederacaoDTOBase = new ProtocoloFederacaoDTO();
            $objProtocoloFederacaoDTOBase->retStrIdProtocoloFederacao();
            $objProtocoloFederacaoDTOBase->setNumMaxRegistrosRetorno(2);

            $objProtocoloFederacaoDTO = clone($objProtocoloFederacaoDTOBase);
            $objProtocoloFederacaoDTOPesqInv = clone($objProtocoloFederacaoDTOBase);

            $objProtocoloFederacaoDTO->setStrProtocoloFormatadoPesquisa($strProtocoloPesquisa);

            $objProtocoloFederacaoRN = new ProtocoloFederacaoRN();
            $arrObjProtocoloFederacaoDTO = $objProtocoloFederacaoRN->listar($objProtocoloFederacaoDTO);
            if (count($arrObjProtocoloFederacaoDTO) == 0) {
              $objProtocoloFederacaoDTO->setStrProtocoloFormatadoPesquisa($strProtocoloPesquisa . '%', InfraDTO::$OPER_LIKE);
              $arrObjProtocoloFederacaoDTO = $objProtocoloFederacaoRN->listar($objProtocoloFederacaoDTO);

              if (count($arrObjProtocoloFederacaoDTO) == 0) {
                $objProtocoloFederacaoDTOPesqInv->setStrProtocoloFormatadoPesqInv(strrev($strProtocoloPesquisa) . '%', InfraDTO::$OPER_LIKE);
                $arrObjProtocoloFederacaoDTO = $objProtocoloFederacaoRN->listar($objProtocoloFederacaoDTOPesqInv);
              }
            }

            if (count($arrObjProtocoloFederacaoDTO)) {
              $strSinFederacao = 'S';
              $objProtocoloDTO = clone($objProtocoloDTOPesquisaBase);
              $objProtocoloDTO->setStrIdProtocoloFederacao(InfraArray::converterArrInfraDTO($arrObjProtocoloFederacaoDTO, 'IdProtocoloFederacao'), InfraDTO::$OPER_IN);
              $arrObjProtocoloDTO = $this->listarRN0668($objProtocoloDTO);
            }
          }
        }
      }

      foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
        $objProtocoloDTO->setStrSinPesquisaFederacao($strSinFederacao);
      }

      return $arrObjProtocoloDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro pesquisando protocolo.', $e);
    }
  }

  //metodo para buscar os processos no popup de processos para serem adicionados em um edital de eliminacao
  protected function pesquisarProtocolosEditalEliminacaoConectado(
    PesquisaAvaliacaoDocumentalDTO $parObjPesquisaAvaliacaoDocumentalDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('avaliacao_documental_selecionar', __METHOD__, $parObjPesquisaAvaliacaoDocumentalDTO);

      //a paginacao nesse metodo/tela teve que ser feita manualmente, devido ao calculo de prazo para eliminacao, que nao pôde ser adicionado na 'query' diretamente
      //assim, primeiramente são buscados os registros conforme os filtros, e dos registros retornados sao filtrados apenas os com prazo (conforme calculo) de eliminacao que permite ser eliminado

      //clona o objeto/parametro, para nao interferir no que é usado na paginacao do php da tela (nos metodos processarPaginacao, prepararPaginacao, etc)
      $objPesquisaAvaliacaoDocumentalDTO = clone($parObjPesquisaAvaliacaoDocumentalDTO);
      //retornará todos os registros
      $objPesquisaAvaliacaoDocumentalDTO->setNumMaxRegistrosRetorno(null);
      $objPesquisaAvaliacaoDocumentalDTO->setNumPaginaAtual(null);
      //guarda os registros por pagina e a pagina atual, que serão usados para paginar manualmente depois do filtro manual
      $numRegistrosPagina = $parObjPesquisaAvaliacaoDocumentalDTO->getNumMaxRegistrosRetorno();
      $numPaginaAtual = $parObjPesquisaAvaliacaoDocumentalDTO->getNumPaginaAtual() + 1;
      //seta filtros da consulta
      $objPesquisaAvaliacaoDocumentalDTO->setStrStaNivelAcessoGlobal(ProtocoloRN::$NA_PUBLICO);
      $objPesquisaAvaliacaoDocumentalDTO->setStrStaAvaliacaoDocumental(AvaliacaoDocumentalRN::$TA_COMISSAO);
      $objPesquisaAvaliacaoDocumentalDTO->setStrStaDestinacaoAssuntoAvaliacaoDocumental(AssuntoRN::$TD_ELIMINACAO);
      $objPesquisaAvaliacaoDocumentalDTO->setNumIdEliminacaoDocumentalConteudo(null, InfraDTO::$OPER_IGUAL);
      $objPesquisaAvaliacaoDocumentalDTO->setNumIdOrgaoUnidadeGeradoraProtocolo(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
      //array que conterá os processos exibidos na tela
      $arrObjPesquisaAvaliacaoDocumental_Tela = array();
      //lista os processos, sem considerar a paginacao
      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $arrObjPesquisaAvaliacaoDocumental = $objProtocoloBD->listar($objPesquisaAvaliacaoDocumentalDTO);
      //total de registros
      $numTotalRegistros = InfraArray::contar($arrObjPesquisaAvaliacaoDocumental);
      if ($numTotalRegistros > 0) {
        //itera os processos encontrados no banco
        foreach ($arrObjPesquisaAvaliacaoDocumental as $objPesquisaAvaliacaoDocumental_Banco) {
          //data atual
          $dtaAtual = InfraData::getStrDataAtual();
          //data da conclusão do processo
          $dtaConclusao = $objPesquisaAvaliacaoDocumental_Banco->getDtaConclusaoProcedimento();
          //calcula o numero de dias entre a data da avaliacao documental e a data de hoje
          $numDiasDatas = InfraData::compararDatas($dtaConclusao, $dtaAtual);
          //busca os prazos do assunto (em anos, ou seja, um prazo_corrente = 1 significa 1 ano)
          $numPrazoCorrente = $objPesquisaAvaliacaoDocumental_Banco->getNumPrazoCorrenteAssuntoAvaliacaoDocumental();
          $numPrazoIntermediario = $objPesquisaAvaliacaoDocumental_Banco->getNumPrazoIntermediarioAssuntoAvaliacaoDocumental();
          //calcula quanto tempo o processo nao pode ser eliminado (ou a partir de quando pode ser eliminado), a partir da soma dos dois prazos (em anos, logo a soma é multiplicada por 365)
          $numDiasPrazos = ($numPrazoCorrente + $numPrazoIntermediario) * 365;
          //se a quantidade de dias desde a avaliacao documental ate hoje é maior ou igual ao numero de dias a partir do qual o processo pode ser eliminado, entao adiciona o processo no array de processos que serão exibidos na tela
          if ($numDiasDatas >= $numDiasPrazos) {
            $arrObjPesquisaAvaliacaoDocumental_Tela[] = $objPesquisaAvaliacaoDocumental_Banco;
          }
        }
        //calculo da paginacao manual
        $numTotalRegistros = InfraArray::contar($arrObjPesquisaAvaliacaoDocumental_Tela);
        $numTotalPaginas = intdiv($numTotalRegistros, $numRegistrosPagina) + 1;
        if ($numPaginaAtual == $numTotalPaginas) {
          $numRegistrosPaginaAtual = $numTotalRegistros - ($numRegistrosPagina * ($numPaginaAtual - 1));
        } else {
          $numRegistrosPaginaAtual = $numRegistrosPagina;
        }
        //seta as quantidades de registros conforme os processos retornados e filtrados, para exibição na tela
        $parObjPesquisaAvaliacaoDocumentalDTO->setNumTotalRegistros($numTotalRegistros);
        $parObjPesquisaAvaliacaoDocumentalDTO->setNumRegistrosPaginaAtual($numRegistrosPaginaAtual);
      }
      return $arrObjProtocoloDTOPagina = array_slice($arrObjPesquisaAvaliacaoDocumental_Tela, ($numPaginaAtual - 1) * $numRegistrosPagina, $numRegistrosPaginaAtual);
    } catch (Exception $e) {
      throw new InfraException('Erro listando protocolos.', $e);
    }
  }

  //método para busca e listagem de protocolos na tela de avaliacao documental
  //recebe um PesquisaAvaliacaoDocumentalDTO, há mais informacoes comentadas no dto
  // pode retornar tanto processos (concluidos) que podem ser avaliados, ou processos que já foram avaliados, conforme o filtro de SinAvaliacao
  protected function pesquisarProtocolosAvaliacaoDocumentalConectado(
    PesquisaAvaliacaoDocumentalDTO $objPesquisaAvaliacaoDocumentalDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('avaliacao_documental_listar', __METHOD__, $objPesquisaAvaliacaoDocumentalDTO);

      $objInfraException = new InfraException();
      //valida filtro de usuario avaliador (nao obrigatorio)
      $this->validarNumIdUsuarioAvaliacaoDocumental($objPesquisaAvaliacaoDocumentalDTO, $objInfraException);
      //valida filtros de data
      $this->validarPeriodoPesquisa($objPesquisaAvaliacaoDocumentalDTO, "GeracaoInicio", "GeracaoFim", "Geração", $objInfraException);
      $this->validarPeriodoPesquisa($objPesquisaAvaliacaoDocumentalDTO, "ConclusaoInicio", "ConclusaoFim", "Conclusão", $objInfraException);
      $this->validarPeriodoPesquisa($objPesquisaAvaliacaoDocumentalDTO, "PeriodoInicio", "PeriodoFim", "Avaliação", $objInfraException);
      //lanca validacoes
      $objInfraException->lancarValidacoes();

      //se informou filtro de geracao
      if ($objPesquisaAvaliacaoDocumentalDTO->getDtaGeracaoInicio() != null && $objPesquisaAvaliacaoDocumentalDTO->getDtaGeracaoFim() != null) {
        //adiciona criterio de periodo
        $objPesquisaAvaliacaoDocumentalDTO->adicionarCriterio(array('Geracao', 'Geracao'), array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL), array(
            $objPesquisaAvaliacaoDocumentalDTO->getDtaGeracaoInicio(), $objPesquisaAvaliacaoDocumentalDTO->getDtaGeracaoFim()
          ), array(InfraDTO::$OPER_LOGICO_AND));
      }
      //se informou filtro de periodo (data da avaliacao documental)
      if ($objPesquisaAvaliacaoDocumentalDTO->getDtaPeriodoInicio() != null && $objPesquisaAvaliacaoDocumentalDTO->getDtaPeriodoFim() != null) {
        //adiciona criterio de periodo
        $objPesquisaAvaliacaoDocumentalDTO->adicionarCriterio(array('AvaliacaoDocumental', 'AvaliacaoDocumental'), array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL), array(
            $objPesquisaAvaliacaoDocumentalDTO->getDtaPeriodoInicio(), $objPesquisaAvaliacaoDocumentalDTO->getDtaPeriodoFim()
          ), array(InfraDTO::$OPER_LOGICO_AND));
      }
      //se informou filtro de conclusao
      if ($objPesquisaAvaliacaoDocumentalDTO->getDtaConclusaoInicio() != null && $objPesquisaAvaliacaoDocumentalDTO->getDtaConclusaoFim() != null) {
        //adiciona criterio de periodo
        $objPesquisaAvaliacaoDocumentalDTO->adicionarCriterio(array('ConclusaoProcedimento', 'ConclusaoProcedimento'), array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL), array(
            $objPesquisaAvaliacaoDocumentalDTO->getDtaConclusaoInicio(), $objPesquisaAvaliacaoDocumentalDTO->getDtaConclusaoFim()
          ), array(InfraDTO::$OPER_LOGICO_AND));
      } else {
        //senao, filtra apenas pelos processos que tem data de conclusao diferente de null, que é requisito para poderem ser avaliados
        $objPesquisaAvaliacaoDocumentalDTO->setDtaConclusaoProcedimento(null, InfraDTO::$OPER_DIFERENTE);
      }
      //processos sem tramitacao no SEI Federacao
      $objPesquisaAvaliacaoDocumentalDTO->setStrIdProtocoloFederacao(null);

      //processo nao pode ser anexado ou outros estados, tem que ser normal
      $objPesquisaAvaliacaoDocumentalDTO->setStrStaEstado(self::$TE_NORMAL);

      //somente processos publicos
      $objPesquisaAvaliacaoDocumentalDTO->setStrStaNivelAcessoGlobal(ProtocoloRN::$NA_PUBLICO);
      //se foi escolhido o radio de apenas processos já avaliados
      if ($objPesquisaAvaliacaoDocumentalDTO->isSetStrSinAvaliacao() && $objPesquisaAvaliacaoDocumentalDTO->getStrSinAvaliacao() == "S") {
        //deve ter uma avaliacao documental (testa pelo id de avaliacao documental, que nao deve ser null)
        ////como é left join, vai trazer o processo mesmo se nao tiver avaliacao documental (ver comentarios nos joins do dto)
        $objPesquisaAvaliacaoDocumentalDTO->setStrStaAvaliacaoDocumental(AvaliacaoDocumentalRN::$TA_AVALIADO);
      } else {
        //nao tem uma avaliacao documental (testa pelo id de avaliacao documental, que deve ser null)
        //como é left join, vai trazer o processo mesmo se nao tiver avaliacao documental (ver comentarios nos joins do dto)
        $objPesquisaAvaliacaoDocumentalDTO->setNumIdAvaliacaoDocumental(null, InfraDTO::$OPER_IGUAL);
      }
      //testa se foram informados assuntos como filtros
      if ($objPesquisaAvaliacaoDocumentalDTO->isSetArrObjRelProtocoloAssuntoDTO() && count($objPesquisaAvaliacaoDocumentalDTO->getArrObjRelProtocoloAssuntoDTO())) {
        //retorna os ids dos assuntos da tela
        $arrIdAssunto = InfraArray::converterArrInfraDTO($objPesquisaAvaliacaoDocumentalDTO->getArrObjRelProtocoloAssuntoDTO(), 'IdAssunto');
        //filtra pelos assuntos do processo e de seus documentos ou dos processos anexados e de seus documentos
        $objPesquisaAvaliacaoDocumentalDTO->adicionarCriterio(array('IdAssuntoAssunto', 'IdAssuntoAssunto2'), array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IN), array($arrIdAssunto, $arrIdAssunto), array(InfraDTO::$OPER_LOGICO_OR));
        //filtra apenas por processos anexados
        //se for um assunto do proprio processo, nao tem problema ter o filtro, pois é opcional (ver dto)
        $objPesquisaAvaliacaoDocumentalDTO->setStrStaAssociacaoRelProtocoloProtocolo(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
      }
      //se foi marcado o check de apenas processos com avaliacoes cpad 'negado'
      if ($objPesquisaAvaliacaoDocumentalDTO->isSetStrSinDiscordancia() && $objPesquisaAvaliacaoDocumentalDTO->getStrSinDiscordancia() == "S") {
        //como é left join, vai trazer o processo mesmo se nao tiver avaliacao documental (ver comentarios nos joins do dto)
        //apenas avaliacoes cpad ativas importam, senao buscaria as nao ativas que sao historico
        $objPesquisaAvaliacaoDocumentalDTO->setStrSinAtivoCpadAvaliacao("S");
        //apenas negadas
        $objPesquisaAvaliacaoDocumentalDTO->setStrStaCpadAvaliacao(CpadAvaliacaoRN::$TA_CPAD_NEGADO);
        //aqui foi usado esse campo/tabela, mas poderia ser outro
        //fitlra apenas pelos registros nao nulos, pois quer retornar apenas os que tiveram avaliacao cpad com os filtros anteriores
        //lembrando que o join com a tabela cpad avaliacao é um left join e filtros no 'on', entao retornaria processo mesmo se nao encontrasse avaliacao cpad
        //entao filtra pelo cpad.id_cpad diferente de null, pois esse é no where
        $objPesquisaAvaliacaoDocumentalDTO->setNumIdCpad(null, InfraDTO::$OPER_DIFERENTE);
      }
      //ordenamento apenas para trazer senore na mesma ordem
      $objPesquisaAvaliacaoDocumentalDTO->setOrdNumIdAvaliacaoDocumental(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objProtocoloBD = new AvaliacaoDocumentalBD($this->getObjInfraIBanco());
      //listar padrao
      $arrObjPesquisaAvaliacaoDocumentalDTO = $objProtocoloBD->listar($objPesquisaAvaliacaoDocumentalDTO);
      //se encontrou processos
      if (InfraArray::contar($arrObjPesquisaAvaliacaoDocumentalDTO) > 0) {
        //busca a avaliacao cpad, para setar o atributo (nao vinculado a tabela) SinDiscordancia, para deixar em vermelho processos que tem avaliacoes cpad ativas negadas por pelo menos um componente da composicao da ultima versao
        $objCpadAvaliacaoRN = new CpadAvaliacaoRN();
        //converte para os ids
        $arrIdAvaliacaoDocumental = InfraArray::converterArrInfraDTO($arrObjPesquisaAvaliacaoDocumentalDTO, "IdAvaliacaoDocumental");
        $objCpadAvaliacaoDTO = new CpadAvaliacaoDTO();
        //retorna o id da avaliacao, que sera indexado em seguida
        $objCpadAvaliacaoDTO->retNumIdAvaliacaoDocumental();
        //busca avaliacoes cpad da avaliacao documental desse processo
        $objCpadAvaliacaoDTO->setNumIdAvaliacaoDocumental($arrIdAvaliacaoDocumental, InfraDTO::$OPER_IN);
        //apenas ativas, para nao trazer historico
        $objCpadAvaliacaoDTO->setStrSinAtivo("S");
        //apenas as negadas
        $objCpadAvaliacaoDTO->setStrStaCpadAvaliacao(CpadAvaliacaoRN::$TA_CPAD_NEGADO);
        //listar
        $arrObjCpadAvaliacaoNegadas = $objCpadAvaliacaoRN->listar($objCpadAvaliacaoDTO);
        //indexa pelo id da avaliacao, pois será a forma de setar a situacao no array de objetos retornado
        $arrObjCpadAvaliacaoNegadas_Indexado = InfraArray::indexarArrInfraDTO($arrObjCpadAvaliacaoNegadas, "IdAvaliacaoDocumental");
        //itera pelos processos
        foreach ($arrObjPesquisaAvaliacaoDocumentalDTO as $objPesquisaAvaliacaoDocumentalDTO) {
          $numIdAvaliacaoDocumental = $objPesquisaAvaliacaoDocumentalDTO->getNumIdAvaliacaoDocumental();
          //testa se existe registro no array de avaliacoes documentais negadas indexado
          if (isset($arrObjCpadAvaliacaoNegadas_Indexado[$numIdAvaliacaoDocumental])) {
            //se encontrou pelo menos uma avaliacao cpad negada ativa (pelo menos um dos componentes da composicao da ultima versao discordou), considera discordancia
            $objPesquisaAvaliacaoDocumentalDTO->setStrSinDiscordancia("S");
          } else {
            $objPesquisaAvaliacaoDocumentalDTO->setStrSinDiscordancia("N");
          }
        }
      }
      return $arrObjPesquisaAvaliacaoDocumentalDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro pesquisando Avaliação Documental.', $e);
    }
  }

  //metodo para buscar os processos que contem avaliacao documental na tela de listagem de avalicao cpad, para serem avaliados
  protected function pesquisarProtocolosCpadAvaliacaoConectado(
    PesquisaAvaliacaoDocumentalDTO $objPesquisaAvaliacaoDocumentalDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_avaliacao_listar', __METHOD__, $objPesquisaAvaliacaoDocumentalDTO);

      //testa se foram informados assuntos como filtros
      if ($objPesquisaAvaliacaoDocumentalDTO->isSetArrObjRelProtocoloAssuntoDTO() && count($objPesquisaAvaliacaoDocumentalDTO->getArrObjRelProtocoloAssuntoDTO())) {
        //retorna os ids dos assuntos da tela
        $arrIdAssunto = InfraArray::converterArrInfraDTO($objPesquisaAvaliacaoDocumentalDTO->getArrObjRelProtocoloAssuntoDTO(), 'IdAssunto');
        //filtra pelos assuntos do processo e de seus documentos ou dos processos anexados e de seus documentos
        $objPesquisaAvaliacaoDocumentalDTO->adicionarCriterio(array('IdAssuntoAssunto', 'IdAssuntoAssunto2'), array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IN), array($arrIdAssunto, $arrIdAssunto), array(InfraDTO::$OPER_LOGICO_OR));
        //filtra apenas por processos anexados
        //se for um assunto do proprio processo, nao tem problema ter o filtro, pois é opcional (ver dto)
        $objPesquisaAvaliacaoDocumentalDTO->setStrStaAssociacaoRelProtocoloProtocolo(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
      }

      //seta ordenamento, para trazer na mesma ordem
      $objPesquisaAvaliacaoDocumentalDTO->setOrdNumIdAvaliacaoDocumental(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objProtocoloBD = new AvaliacaoDocumentalBD($this->getObjInfraIBanco());
      $arrObjPesquisaAvaliacaoDocumentalDTO = $objProtocoloBD->listar($objPesquisaAvaliacaoDocumentalDTO);
      return $arrObjPesquisaAvaliacaoDocumentalDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro pesquisando protocolo.', $e);
    }
  }

  //validacao generica para filtros de um periodo de datas
  private function validarPeriodoPesquisa(
    PesquisaAvaliacaoDocumentalDTO $objProtocoloDTO, $strNomeCampoDtaInicio, $strNomeCampoDtaFim, $strDescricaoCampoDta, InfraException $objInfraException) {
    //bool que indica se ocorreu algum erro de validacao, para pular validacoes no metodo
    $bolSemErros = true;
    //valida as datas de inicio e de fim individualmente
    if (!$this->validarDtaPesquisa($objProtocoloDTO, $strNomeCampoDtaInicio, $strDescricaoCampoDta, $objInfraException) || !$this->validarDtaPesquisa($objProtocoloDTO, $strNomeCampoDtaFim, $strDescricaoCampoDta, $objInfraException)) {
      $bolSemErros = false;
    }
    //se nao ocorreu erro nas datas individualmente, segue para validar o periodo
    if ($bolSemErros) {
      //para filtro de periodo, os dois valores devem ser informados
      if (($objProtocoloDTO->get($strNomeCampoDtaInicio) != null && $objProtocoloDTO->get($strNomeCampoDtaFim) == null) || ($objProtocoloDTO->get($strNomeCampoDtaInicio) == null && $objProtocoloDTO->get($strNomeCampoDtaFim) != null)) {
        $objInfraException->adicionarValidacao('As duas datas de ' . $strDescricaoCampoDta . ' devem ser informadas.');
      }
      //para filtro de periodo passado, as datas nao podem estar no futuro
      if (InfraData::compararDatas(InfraData::getStrDataHoraAtual(), $objProtocoloDTO->get($strNomeCampoDtaInicio)) > 0 || InfraData::compararDatas(InfraData::getStrDataHoraAtual(), $objProtocoloDTO->get($strNomeCampoDtaFim)) > 0) {
        $objInfraException->adicionarValidacao('Datas de ' . $strDescricaoCampoDta . ' não podem estar no futuro.');
      }
      //data inicial nao pode ser depois da final
      if (InfraData::compararDatas($objProtocoloDTO->get($strNomeCampoDtaInicio), $objProtocoloDTO->get($strNomeCampoDtaFim)) < 0) {
        $objInfraException->adicionarValidacao('Data inicial de ' . $strDescricaoCampoDta . ' não pode ser posterior a data final.');
      }
    }
  }

  //valida uma data
  private function validarDtaPesquisa(
    PesquisaAvaliacaoDocumentalDTO $objProtocoloDTO, $strNomeCampoDta, $strDescricaoCampoDta, InfraException $objInfraException) {
    //bool que indica se retornou erro
    $bolSemErros = true;
    //testa se atributo foi setado e nao é vazio
    if (!$objProtocoloDTO->isSetAtributo($strNomeCampoDta) || InfraString::isBolVazia($objProtocoloDTO->get($strNomeCampoDta))) {
      //seta como null
      $objProtocoloDTO->set($strNomeCampoDta, null);
      //se tem valor, testa com metodo da infra
    } else {
      if (!InfraData::validarData($objProtocoloDTO->get($strNomeCampoDta))) {
        //adiciona validacao
        $objInfraException->adicionarValidacao('Data de ' . $strDescricaoCampoDta . ' inválida.');
        $bolSemErros = false;
      }
    }
    return $bolSemErros;
  }

  private function validarNumIdUsuarioAvaliacaoDocumental(
    PesquisaAvaliacaoDocumentalDTO $objPesquisaAvaliacaoDocumentalDTO, InfraException $objInfraException) {
    if ($objPesquisaAvaliacaoDocumentalDTO->isSetNumIdUsuarioAvaliacaoDocumental() && InfraString::isBolVazia($objPesquisaAvaliacaoDocumentalDTO->getNumIdUsuarioAvaliacaoDocumental())) {
      $objPesquisaAvaliacaoDocumentalDTO->unSetNumIdUsuarioAvaliacaoDocumental();
    }
  }

  protected function consultarPesquisaAvaliacaoDocumentalConectado(
    PesquisaAvaliacaoDocumentalDTO $objPesquisaAvaliacaoDocumentalDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_consultar', __METHOD__, $objPesquisaAvaliacaoDocumentalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloBD->consultar($objPesquisaAvaliacaoDocumentalDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando protocolo.', $e);
    }
  }

  protected function gerarIdentificadorFederacaoControlado(ProtocoloDTO $parObjProtocoloDTO) {
    try {
      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());
      $objProtocoloDTO = $this->bloquear($objProtocoloDTO);

      if ($objProtocoloDTO->getStrIdProtocoloFederacao() == null) {
        $strIdProtocoloFederacao = InfraULID::gerar();

        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();

        $objProtocoloFederacaoDTO = new ProtocoloFederacaoDTO();
        $objProtocoloFederacaoDTO->setStrIdProtocoloFederacao($strIdProtocoloFederacao);
        $objProtocoloFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal());
        $objProtocoloFederacaoDTO->setStrProtocoloFormatado($objProtocoloDTO->getStrProtocoloFormatado());

        $objProtocoloFederacaoRN = new ProtocoloFederacaoRN();
        $objProtocoloFederacaoRN->cadastrar($objProtocoloFederacaoDTO);

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setStrIdProtocoloFederacao($strIdProtocoloFederacao);
        $objProtocoloDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

        $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
        $objProtocoloBD->alterar($objProtocoloDTO);
      }

      $parObjProtocoloDTO->setStrIdProtocoloFederacao($objProtocoloDTO->getStrIdProtocoloFederacao());
    } catch (Exception $e) {
      throw new InfraException('Erro gerando identificador do SEI Federação para o Protocolo.', $e);
    }
  }
}

?>