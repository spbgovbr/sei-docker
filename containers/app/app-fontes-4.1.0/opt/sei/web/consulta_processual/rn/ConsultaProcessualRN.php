<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 19/03/2020 - criado por cjy
 *
 * Versão do Gerador de Código: 1.42.0
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class ConsultaProcessualRN extends InfraRN {

  //CP = Consulta Processual
  public static $CP_DESABILITADA = '0';
  public static $CP_DOCUMENTOS_PUBLICADOS = '1';

  //TC = Tipo Criterio Consulta Processual
  public static $TC_NUMERO_PROCESSO = 'P';
  public static $TC_NOME_INTERESSADO = 'N';
  public static $TC_CPF_INTERESSADO = 'F';
  public static $TC_CNPJ_INTERESSADO = 'J';

  public static $QUANTIDADE_PALAVRAS_MINIMA = 2;
  public static $QUANTIDADE_LETRAS_MINIMA = 2;

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  public function listarValoresCriterios() {
    $arr = array();

    $objInfraValorStaDTO = new InfraValorStaDTO();
    $objInfraValorStaDTO->setStrStaValor(self::$TC_NUMERO_PROCESSO);
    $objInfraValorStaDTO->setStrDescricao('Nº do Processo');
    $arr[] = $objInfraValorStaDTO;

    $objInfraValorStaDTO = new InfraValorStaDTO();
    $objInfraValorStaDTO->setStrStaValor(self::$TC_NOME_INTERESSADO);
    $objInfraValorStaDTO->setStrDescricao('Nome do Interessado');
    $arr[] = $objInfraValorStaDTO;

    $objInfraValorStaDTO = new InfraValorStaDTO();
    $objInfraValorStaDTO->setStrStaValor(self::$TC_CPF_INTERESSADO);
    $objInfraValorStaDTO->setStrDescricao('CPF do Interessado');
    $arr[] = $objInfraValorStaDTO;

    $objInfraValorStaDTO = new InfraValorStaDTO();
    $objInfraValorStaDTO->setStrStaValor(self::$TC_CNPJ_INTERESSADO);
    $objInfraValorStaDTO->setStrDescricao('CNPJ do Interessado');
    $arr[] = $objInfraValorStaDTO;

    return $arr;
  }

  private function validarStrStaCriterio(ConsultaProcessualDTO $objConsultaProcessualDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objConsultaProcessualDTO->getStrStaCriterioPesquisa())) {
      $objInfraException->lancarValidacao('Critério de Pesquisa não informado.');
    }

    if (!in_array($objConsultaProcessualDTO->getStrStaCriterioPesquisa(), InfraArray::converterArrInfraDTO($this->listarValoresCriterios(), 'StaValor'))) {
      $objInfraException->lancarValidacao('Critério de Pesquisa inválido.');
    }
  }

  private function validarStrValorPesquisa(ConsultaProcessualDTO $objConsultaProcessualDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objConsultaProcessualDTO->getStrValorPesquisa())) {
      $objInfraException->lancarValidacao('Valor do Critério de pesquisa não informado.');
    }

    $objConsultaProcessualDTO->setStrValorPesquisa(trim($objConsultaProcessualDTO->getStrValorPesquisa()));

    if (strlen($objConsultaProcessualDTO->getStrValorPesquisa()) > 100) {
      $objInfraException->lancarValidacao('Tamanho do Valor do Critério de pesquisa é superior a 100 caracteres.');
    }

    $strStaCriterioPesquisa = $objConsultaProcessualDTO->getStrStaCriterioPesquisa();

    if ($strStaCriterioPesquisa == self::$TC_NOME_INTERESSADO) {
      $bolErro = false;

      $strValorPesquisa = preg_replace('/[^0-9A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑªº°\-]/', ' ', $objConsultaProcessualDTO->getStrValorPesquisa());

      if ($strValorPesquisa != $objConsultaProcessualDTO->getStrValorPesquisa()){
        $objInfraException->lancarValidacao('Nome do interessado possui caractere inválido.');
      }

      //$objConsultaProcessualDTO->setStrValorPesquisa(preg_replace('/[^0-9A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑªº°\-]/', ' ', $objConsultaProcessualDTO->getStrValorPesquisa()));

      $arrParticulas = array_unique(explode(' ', $objConsultaProcessualDTO->getStrValorPesquisa()));

      if (InfraArray::contar($arrParticulas) < self::$QUANTIDADE_PALAVRAS_MINIMA) {
        $bolErro = true;
      } else {
        foreach ($arrParticulas as $strParticula) {
          if (strlen($strParticula) < self::$QUANTIDADE_LETRAS_MINIMA) {
            $bolErro = true;
            break;
          }
        }
      }

      if ($bolErro) {
        $objInfraException->lancarValidacao('Nome do Interessado deve ser composto ao menos de ' . self::$QUANTIDADE_PALAVRAS_MINIMA . ' partículas diferentes com ' . self::$QUANTIDADE_LETRAS_MINIMA . ' caracteres cada uma.');
      }

    } else {

      $strValorPesquisa = preg_replace('/[^0-9]/', '', $objConsultaProcessualDTO->getStrValorPesquisa());

      if (!is_numeric($strValorPesquisa)) {
        $objInfraException->lancarValidacao('Valor do critério de consulta inválido.');
      }

      if ($strStaCriterioPesquisa == self::$TC_CPF_INTERESSADO) {
        if (!InfraUtil::validarCpf($strValorPesquisa)) {
          $objInfraException->lancarValidacao('CPF inválido.');
        }
      } else {
        if ($strStaCriterioPesquisa == self::$TC_CNPJ_INTERESSADO) {
          if (!InfraUtil::validarCnpj($strValorPesquisa)) {
            $objInfraException->lancarValidacao('CNPJ inválido.');
          }
        }
      }
    }
  }

  private function validarNumIdOrgaoUnidadeGeradora(ConsultaProcessualDTO $objConsultaProcessualDTO, InfraException $objInfraException) {
    if ($objConsultaProcessualDTO->getNumIdOrgaoUnidadeGeradora() == null || InfraArray::contar($objConsultaProcessualDTO->getNumIdOrgaoUnidadeGeradora()) == 0) {
      $objInfraException->lancarValidacao('Nenhum Órgão para consulta informado.');
    }
  }

  public function validarCriterios(ConsultaProcessualDTO $objConsultaProcessualDTO){

    $objInfraException = new InfraException();

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numTipoConsultaProcessual = $objInfraParametro->getValor('SEI_HABILITAR_CONSULTA_PROCESSUAL');

    if (!in_array($numTipoConsultaProcessual,array(self::$CP_DESABILITADA, self::$CP_DOCUMENTOS_PUBLICADOS))) {
      $objInfraException->lancarValidacao('Valor do parâmetro SEI_HABILITAR_CONSULTA_PROCESSUAL inválido.');
    }

    if ($numTipoConsultaProcessual == self::$CP_DESABILITADA) {
      $objInfraException->lancarValidacao('Consulta processual desabilitada.');
    }

    $this->validarStrStaCriterio($objConsultaProcessualDTO, $objInfraException);
    $this->validarStrValorPesquisa($objConsultaProcessualDTO, $objInfraException);
    $this->validarNumIdOrgaoUnidadeGeradora($objConsultaProcessualDTO, $objInfraException);

    $objInfraException->lancarValidacoes();

  }

  protected function pesquisarConectado(ConsultaProcessualDTO $parObjConsultaProcessualDTO) {
    try {

      $ret = array();

      if ($parObjConsultaProcessualDTO->isSetStrStaCriterioPesquisa() && $parObjConsultaProcessualDTO->isSetStrValorPesquisa() && $parObjConsultaProcessualDTO->isSetNumIdOrgaoUnidadeGeradora()) {
        $this->validarCriterios($parObjConsultaProcessualDTO);
      }

      $objConsultaProcessualDTO = new ConsultaProcessualDTO();
      $objConsultaProcessualDTO->retDblIdProtocolo();
      $objConsultaProcessualDTO->retStrProtocoloFormatado();
      $objConsultaProcessualDTO->retStrNomeTipoProcedimento();
      $objConsultaProcessualDTO->retDtaGeracao();
      $objConsultaProcessualDTO->retStrStaNivelAcessoGlobal();

      $objConsultaProcessualDTO->retStrSinEliminado();

      $objConsultaProcessualDTO->retNumIdUnidadeGeradora();
      $objConsultaProcessualDTO->retStrSiglaUnidadeGeradora();
      $objConsultaProcessualDTO->retStrDescricaoUnidadeGeradora();

      $objConsultaProcessualDTO->retNumIdOrgaoUnidadeGeradora();
      $objConsultaProcessualDTO->retStrSiglaOrgaoUnidadeGeradora();
      $objConsultaProcessualDTO->retStrDescricaoOrgaoUnidadeGeradora();

      $objConsultaProcessualDTO->setStrStaProtocolo(ProtocoloRN::$TP_PROCEDIMENTO);
      $objConsultaProcessualDTO->setStrStaNivelAcessoGlobal(ProtocoloRN::$NA_PUBLICO);
      $objConsultaProcessualDTO->setStrSinConsultaProcessualOrgaoUnidadeGeradora('S');

      if ($parObjConsultaProcessualDTO->isSetStrStaCriterioPesquisa() && $parObjConsultaProcessualDTO->isSetStrValorPesquisa() && $parObjConsultaProcessualDTO->isSetNumIdOrgaoUnidadeGeradora()) {

        switch ($parObjConsultaProcessualDTO->getStrStaCriterioPesquisa()) {
          case self::$TC_NOME_INTERESSADO:

            $objContatoDTO = new ContatoDTO();
            $objContatoDTO->retNumIdContato();
            $objContatoDTO->setStrNome($parObjConsultaProcessualDTO->getStrValorPesquisa());
            InfraString::tratarPalavrasPesquisaDTO($objContatoDTO, 'Nome');
            $objContatoDTO->setNumMaxRegistrosRetorno(51);

            $objContatoRN = new ContatoRN();
            $arrObjContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);

            $numContatos = count($arrObjContatoDTO);

            if ($numContatos == 0) {
              return $ret;
            } else {
              if ($numContatos > 50){
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacao('Foram encontrado mais de 50 interessados para o nome informado.');
              }

              //$objConsultaProcessualDTO->setNumTipoFkParticipante(InfraDTO::$TIPO_FK_OBRIGATORIA);
              //$objConsultaProcessualDTO->setNumIdContato(InfraArray::converterArrInfraDTO($arrObjContatoDTO, 'IdContato'), InfraDTO::$OPER_IN);
            }

            $objConsultaProcessualDTO->setStrNomeContato($parObjConsultaProcessualDTO->getStrValorPesquisa());
            InfraString::tratarPalavrasPesquisaDTO($objConsultaProcessualDTO, 'NomeContato');
            $objConsultaProcessualDTO->setNumTipoFkParticipante(InfraDTO::$TIPO_FK_OBRIGATORIA);

            break;

          case self::$TC_CPF_INTERESSADO:
            $objConsultaProcessualDTO->setDblCpfContato(InfraUtil::retirarFormatacao($parObjConsultaProcessualDTO->getStrValorPesquisa()));
            $objConsultaProcessualDTO->setNumTipoFkParticipante(InfraDTO::$TIPO_FK_OBRIGATORIA);
            break;

          case self::$TC_CNPJ_INTERESSADO:
            $objConsultaProcessualDTO->setDblCnpjContato(InfraUtil::retirarFormatacao($parObjConsultaProcessualDTO->getStrValorPesquisa()));
            $objConsultaProcessualDTO->setNumTipoFkParticipante(InfraDTO::$TIPO_FK_OBRIGATORIA);
            break;

          case self::$TC_NUMERO_PROCESSO:
            $objConsultaProcessualDTO->setStrProtocoloFormatadoPesquisa(InfraUtil::retirarFormatacao($parObjConsultaProcessualDTO->getStrValorPesquisa()));
            break;

          default:
            throw new InfraException('Critério de consulta não encontrado.');
        }

        $objConsultaProcessualDTO->setNumIdOrgaoUnidadeGeradora($parObjConsultaProcessualDTO->getNumIdOrgaoUnidadeGeradora(), InfraDTO::$OPER_IN);
      }

      if ($parObjConsultaProcessualDTO->isSetDblIdProcedimento()){
        $objConsultaProcessualDTO->setDblIdProcedimento($parObjConsultaProcessualDTO->getDblIdProcedimento());
      }

      $objConsultaProcessualDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_DESC);

      //paginação
      $objConsultaProcessualDTO->setNumMaxRegistrosRetorno($parObjConsultaProcessualDTO->getNumMaxRegistrosRetorno());
      $objConsultaProcessualDTO->setNumPaginaAtual($parObjConsultaProcessualDTO->getNumPaginaAtual());

      $objConsultaProcessualBD = new ConsultaProcessualBD($this->getObjInfraIBanco());
      $arrObjConsultaProcessualDTO = $objConsultaProcessualBD->listar($objConsultaProcessualDTO);

      //paginação
      $parObjConsultaProcessualDTO->setNumTotalRegistros($objConsultaProcessualDTO->getNumTotalRegistros());
      $parObjConsultaProcessualDTO->setNumRegistrosPaginaAtual($objConsultaProcessualDTO->getNumRegistrosPaginaAtual());

      if (count($arrObjConsultaProcessualDTO)) {
        foreach ($arrObjConsultaProcessualDTO as $objConsultaProcessualDTO) {
          $objProcedimentoDTO = new ProcedimentoDTO();
          $objProcedimentoDTO->setDblIdProcedimento($objConsultaProcessualDTO->getDblIdProtocolo());
          $objProcedimentoDTO->setStrProtocoloProcedimentoFormatado($objConsultaProcessualDTO->getStrProtocoloFormatado());
          $objProcedimentoDTO->setStrNomeTipoProcedimento($objConsultaProcessualDTO->getStrNomeTipoProcedimento());
          $objProcedimentoDTO->setDtaGeracaoProtocolo($objConsultaProcessualDTO->getDtaGeracao());
          $objProcedimentoDTO->setStrStaNivelAcessoGlobalProtocolo($objConsultaProcessualDTO->getStrStaNivelAcessoGlobal());

          $objProcedimentoDTO->setNumIdUnidadeGeradoraProtocolo($objConsultaProcessualDTO->getNumIdUnidadeGeradora());
          $objProcedimentoDTO->setStrSiglaUnidadeGeradoraProtocolo($objConsultaProcessualDTO->getStrSiglaUnidadeGeradora());
          $objProcedimentoDTO->setStrDescricaoUnidadeGeradoraProtocolo($objConsultaProcessualDTO->getStrDescricaoUnidadeGeradora());

          $objProcedimentoDTO->setNumIdOrgaoUnidadeGeradoraProtocolo($objConsultaProcessualDTO->getNumIdOrgaoUnidadeGeradora());
          $objProcedimentoDTO->setStrSiglaOrgaoUnidadeGeradoraProtocolo($objConsultaProcessualDTO->getStrSiglaOrgaoUnidadeGeradora());
          $objProcedimentoDTO->setStrDescricaoOrgaoUnidadeGeradoraProtocolo($objConsultaProcessualDTO->getStrDescricaoOrgaoUnidadeGeradora());

          $ret[] = $objProcedimentoDTO;
        }

        if (!$parObjConsultaProcessualDTO->isSetDblIdProcedimento() && !$parObjConsultaProcessualDTO->isSetDblIdProtocoloConsulta()) {
          $this->complementarParticipantes($ret);
        }

        /*
        $arrObjOrgaoDTO = array();
        $arrObjUnidadeDTO = array();
        foreach ($ret as $objProcedimentoDTO) {

          $strChaveHistoricoOrgao = $objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo().'_'.$objProcedimentoDTO->getDtaGeracaoProtocolo();
          if (!isset($arrObjOrgaoDTO[$strChaveHistoricoOrgao])) {
            $objOrgaoDTO = new OrgaoDTO();
            $objOrgaoDTO->setNumIdOrgao($objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
            $objOrgaoDTO->setDtaHistorico($objProcedimentoDTO->getDtaGeracaoProtocolo());
            $arrObjOrgaoDTO[$strChaveHistoricoOrgao] = $objOrgaoDTO;
          }


          $strChaveHistoricoUnidade = $objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo().'_'.$objProcedimentoDTO->getDtaGeracaoProtocolo();
          if (!isset($arrObjUnidadeDTO[$strChaveHistoricoUnidade])) {
            $objUnidadeDTO = new UnidadeDTO();
            $objUnidadeDTO->setNumIdUnidade($objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo());
            $objUnidadeDTO->setDtaHistorico($objProcedimentoDTO->getDtaGeracaoProtocolo());
            $arrObjUnidadeDTO[$strChaveHistoricoUnidade] = $objUnidadeDTO;
          }
        }

        $objHistoricoRN = new HistoricoRN();
        $objHistoricoRN->aplicar('Orgao', $arrObjOrgaoDTO, 'Historico', 'IdOrgao', 'Sigla', 'Descricao');
        $objHistoricoRN->aplicar('Unidade', $arrObjUnidadeDTO, 'Historico', 'IdUnidade', 'Sigla', 'Descricao');

        foreach ($ret as $objProcedimentoDTO) {

          $strChaveHistoricoOrgao = $objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo().'_'.$objProcedimentoDTO->getDtaGeracaoProtocolo();
          $objProcedimentoDTO->setStrSiglaOrgaoUnidadeGeradoraProtocolo($arrObjOrgaoDTO[$strChaveHistoricoOrgao]->getStrSigla());
          $objProcedimentoDTO->setStrDescricaoOrgaoUnidadeGeradoraProtocolo($arrObjOrgaoDTO[$strChaveHistoricoOrgao]->getStrDescricao());

          $strChaveHistoricoUnidade = $objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo().'_'.$objProcedimentoDTO->getDtaGeracaoProtocolo();
          $objProcedimentoDTO->setStrSiglaUnidadeGeradoraProtocolo($arrObjUnidadeDTO[$strChaveHistoricoUnidade]->getStrSigla());
          $objProcedimentoDTO->setStrDescricaoUnidadeGeradoraProtocolo($arrObjUnidadeDTO[$strChaveHistoricoUnidade]->getStrDescricao());
        }
        */
      }

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro no processamento da Consulta Processual.', $e);
    }
  }

  protected function consultarProcessoConsultaProcessualConectado(ConsultaProcessualDTO $parObjConsultaProcessualDTO) {
    try {
      $ret = array();

      $arrObjProcedimentoDTO = $this->pesquisar($parObjConsultaProcessualDTO);

      if (count($arrObjProcedimentoDTO) != 1) {
        throw new InfraException('Processo não encontrado.');
      }

      $objProcedimentoDTO = $arrObjProcedimentoDTO[0];

      if ($parObjConsultaProcessualDTO->isSetDblIdProtocoloConsulta()) {

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retStrStaProtocolo();
        $objProtocoloDTO->setDblIdProtocolo($parObjConsultaProcessualDTO->getDblIdProtocoloConsulta());

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

        if ($objProtocoloDTO == null) {
          throw new InfraException('Protocolo não encontrado.', null, null, false);
        }

        $dblIdProcessoAnexado = null;

        if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
          $dblIdProcessoAnexado = $parObjConsultaProcessualDTO->getDblIdProtocoloConsulta();
        } else {
          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
          $objDocumentoDTO->retDblIdProcedimento();
          $objDocumentoDTO->setDblIdDocumento($parObjConsultaProcessualDTO->getDblIdProtocoloConsulta());

          $objDocumentoRN = new DocumentoRN();
          $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

          if ($objDocumentoDTO->getDblIdProcedimento() != $objProcedimentoDTO->getDblIdProcedimento()) {
            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDTO->getDblIdProcedimento());
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjConsultaProcessualDTO->getDblIdProtocoloConsulta());
            $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO);

            $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
            if ($objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO) != null) {
              throw new InfraException('Documento movido para o processo ' . $objDocumentoDTO->getStrProtocoloProcedimentoFormatado() . '.', null, null, false);
            }

            $dblIdProcessoAnexado = $objDocumentoDTO->getDblIdProcedimento();

          }else{
            $objProcedimentoDTO->setArrDblIdProtocoloAssociado(array($parObjConsultaProcessualDTO->getDblIdProtocoloConsulta()));
          }
        }

        if ($dblIdProcessoAnexado != null) {
          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoDTO->getDblIdProcedimento());
          $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);

          $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
          $arrIdProcedimentosAnexados = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO), 'IdProtocolo2');

          if (!in_array($dblIdProcessoAnexado, $arrIdProcedimentosAnexados)) {
            throw new InfraException('Processo solicitado não está anexado ao processo original.');
          }

          $objProcedimentoDTO = new ProcedimentoDTO();
          $objProcedimentoDTO->setDblIdProcedimento($dblIdProcessoAnexado);

          if ($dblIdProcessoAnexado!=$parObjConsultaProcessualDTO->getDblIdProtocoloConsulta()) {
            $objProcedimentoDTO->setArrDblIdProtocoloAssociado(array($parObjConsultaProcessualDTO->getDblIdProtocoloConsulta()));
          }
        }
      }

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $numTipoConsultaProcessual = $objInfraParametro->getValor('SEI_HABILITAR_CONSULTA_PROCESSUAL');

      if ($numTipoConsultaProcessual == self::$CP_DOCUMENTOS_PUBLICADOS) {
        $objProcedimentoDTO->setStrSinDocPublicado('S');
      } else {
        return null;
      }

      $objProcedimentoRN = new ProcedimentoRN();
      $arrObjProcedimentoDTO = $objProcedimentoRN->listarCompleto($objProcedimentoDTO);

      if (count($arrObjProcedimentoDTO) == 0) {
        throw new InfraException('Processo não encontrado.');
      }

      $objProcedimentoDTO = $arrObjProcedimentoDTO[0];

      $arrObjRelProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();

      if (InfraArray::contar($arrObjRelProtocoloProtocoloDTO)) {
        $objDocumentoRN = new DocumentoRN();

        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {

          if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO) {

            $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

            if ($numTipoConsultaProcessual == self::$CP_DOCUMENTOS_PUBLICADOS && $objDocumentoDTO->getStrSinPublicado() == 'S' && $objDocumentoRN->verificarSelecaoAcessoBasico($objDocumentoDTO)) {
              $ret[] = $objRelProtocoloProtocoloDTO;
            }
          }
        }
      }

      $objProcedimentoDTO->setArrObjRelProtocoloProtocoloDTO($ret);

      if (!$parObjConsultaProcessualDTO->isSetDblIdProtocoloConsulta()) {
        $this->complementarParticipantes(array($objProcedimentoDTO));
      }

      return $objProcedimentoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro acessando protocolo na Consulta Processual.', $e);
    }
  }

  protected function complementarParticipantes($arrObjProcedimentoDTO) {
    try {
      $objParticipanteDTO = new ParticipanteDTO();
      $objParticipanteDTO->retDblIdProtocolo();
      $objParticipanteDTO->retNumIdContato();
      $objParticipanteDTO->retStrNomeContato();
      $objParticipanteDTO->retDblCpfContato();
      $objParticipanteDTO->retDblCnpjContato();
      $objParticipanteDTO->retStrStaNaturezaContato();
      $objParticipanteDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento'), InfraDTO::$OPER_IN);
      $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
      $objParticipanteDTO->setOrdStrNomeContato(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objParticipanteRN = new ParticipanteRN();
      $arrObjParticipanteDTO = InfraArray::indexarArrInfraDTO($objParticipanteRN->listarRN0189($objParticipanteDTO), 'IdProtocolo', true);

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        $objProcedimentoDTO->setArrObjParticipanteDTO(array());

        if (isset($arrObjParticipanteDTO[$objProcedimentoDTO->getDblIdProcedimento()])) {
          $arrObjParticipanteDTOProtocolo = $arrObjParticipanteDTO[$objProcedimentoDTO->getDblIdProcedimento()];

          foreach ($arrObjParticipanteDTOProtocolo as $objParticipanteDTO) {
            $strMascara = null;
            if ($objParticipanteDTO->getStrStaNaturezaContato() == ContatoRN::$TN_PESSOA_JURIDICA && !InfraString::isBolVazia($objParticipanteDTO->getDblCnpjContato())) {
              $strMascara = ".***.***/****-**";
              $numValor = strval($objParticipanteDTO->getDblCnpjContato());
              $numTamanho = 14;
              $numSemMascara = 2;
            } else {
              if ($objParticipanteDTO->getStrStaNaturezaContato() == ContatoRN::$TN_PESSOA_FISICA && !InfraString::isBolVazia($objParticipanteDTO->getDblCpfContato())) {
                $strMascara = ".***.***-**";
                $numValor = strval($objParticipanteDTO->getDblCpfContato());
                $numTamanho = 11;
                $numSemMascara = 3;
              }
            }
            if ($strMascara != null) {
              if ($numTamanho - strlen($numValor)) {
                $numValor = str_pad($numValor, $numTamanho, '0', STR_PAD_LEFT);
              }
              $strMascara = " (" . substr($numValor, 0, $numSemMascara) . $strMascara . ")";
            }
            $objParticipanteDTO->setStrNomeContato($objParticipanteDTO->getStrNomeContato() . $strMascara);
          }

          $objProcedimentoDTO->setArrObjParticipanteDTO($arrObjParticipanteDTOProtocolo);
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro complementando participantes.', $e);
    }
  }
}
