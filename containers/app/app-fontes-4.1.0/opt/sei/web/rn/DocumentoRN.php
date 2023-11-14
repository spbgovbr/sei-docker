<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class DocumentoRN extends InfraRN {

  public static $TD_EXTERNO = 'X';
  public static $TD_EDITOR_EDOC = 'E';
  public static $TD_EDITOR_INTERNO = 'I';
  public static $TD_FORMULARIO_AUTOMATICO = 'A';
  public static $TD_FORMULARIO_GERADO = 'F';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function cadastrarRN0003(DocumentoDTO $objDocumentoDTO){

    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $objDocumentoDTO = $this->cadastrarRN0003Interno($objDocumentoDTO);

    $objIndexacaoDTO = new IndexacaoDTO();
    $objIndexacaoDTO->setArrIdProtocolos(array($objDocumentoDTO->getDblIdDocumento()));
    $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_METADADOS_E_CONTEUDO);

    $objIndexacaoRN = new IndexacaoRN();
    $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);

    if (!$bolAcumulacaoPrevia){
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }

    return $objDocumentoDTO;
  }

  protected function cadastrarRN0003Interno(DocumentoDTO $objDocumentoDTO) {
    try {

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objDocumentoDTO->setDblIdDocumento(null);
      $this->validarStrStaDocumento($objDocumentoDTO, $objInfraException);

      switch($objDocumentoDTO->getStrStaDocumento()){

        case DocumentoRN::$TD_EDITOR_INTERNO:
          SessaoSEI::getInstance()->validarAuditarPermissao('documento_gerar', __METHOD__, $objDocumentoDTO);
          break;

        case DocumentoRN::$TD_EXTERNO:
          SessaoSEI::getInstance()->validarAuditarPermissao('documento_receber', __METHOD__, $objDocumentoDTO);
          break;

        case DocumentoRN::$TD_FORMULARIO_AUTOMATICO:
        case DocumentoRN::$TD_FORMULARIO_GERADO:
          SessaoSEI::getInstance()->validarAuditarPermissao('formulario_gerar', __METHOD__, $objDocumentoDTO);
          break;
      }

      $objDocumentoDTO->setNumIdConjuntoEstilos(null);
      $objDocumentoDTO->setDblIdDocumentoEdoc(null);
      $objDocumentoDTO->setStrSinBloqueado('N');
      
      if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO){
        $objDocumentoDTO->setStrSinVersoes('S');
        $objDocumentoDTO->setStrStaEditor(EditorRN::$VE_CK4);
      }else{
        $objDocumentoDTO->setStrSinVersoes('N');
        $objDocumentoDTO->setStrStaEditor(EditorRN::$VE_NENHUM);
      }

      $this->validarDblIdProcedimento($objDocumentoDTO, $objInfraException);

      $objProtocoloDTOProcedimento = new ProtocoloDTO();
      $objProtocoloDTOProcedimento->retStrProtocoloFormatado();
      $objProtocoloDTOProcedimento->retStrStaEstado();
      $objProtocoloDTOProcedimento->retStrSinEliminado();
      $objProtocoloDTOProcedimento->retNumIdTipoProcedimentoProcedimento();
      $objProtocoloDTOProcedimento->retStrStaNivelAcessoGlobal();
      $objProtocoloDTOProcedimento->retStrProtocoloFormatado();
      $objProtocoloDTOProcedimento->retNumIdPlanoTrabalhoProcedimento();
      $objProtocoloDTOProcedimento->retStrNomePlanoTrabalhoProcedimento();
      $objProtocoloDTOProcedimento->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTOProcedimento = $objProtocoloRN->consultarRN0186($objProtocoloDTOProcedimento);

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->verificarEstadoProcedimento($objProtocoloDTOProcedimento);

      $this->validarNumIdUnidadeResponsavelRN0915($objDocumentoDTO, $objInfraException);
      $objSerieDTO = $this->validarNumIdSerieRN0009($objDocumentoDTO, $objInfraException);

      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_GERADO){
        $objDocumentoDTO->setNumIdTipoFormulario($objSerieDTO->getNumIdTipoFormulario());;
      }else {
        $objDocumentoDTO->setNumIdTipoFormulario(null);
      }

      if ($objDocumentoDTO->isSetNumIdTipoConferencia()) {
        $this->validarNumIdTipoConferencia($objDocumentoDTO, $objInfraException);
      }else{
        $objDocumentoDTO->setNumIdTipoConferencia(null);
      }

      if ($objDocumentoDTO->isSetStrSinArquivamento()) {
        $this->validarStrSinArquivamento($objDocumentoDTO, $objInfraException);
      }else{
        $objDocumentoDTO->setStrSinArquivamento('N');
      }

      if($objDocumentoDTO->isSetStrNomeArvore()) {
        $this->validarStrNomeArvore($objDocumentoDTO, $objInfraException);
      }else{
        $objDocumentoDTO->setStrNomeArvore(null);
      }

      if($objDocumentoDTO->isSetDinValor()) {
        $this->validarDinValor($objDocumentoDTO, $objInfraException);
      }else{
        $objDocumentoDTO->setDinValor(null);
      }

      if (!$objDocumentoDTO->isSetNumIdPlanoTrabalho()){
        $objDocumentoDTO->setNumIdPlanoTrabalho(null);
      }

      if (!$objDocumentoDTO->isSetNumIdEtapaTrabalho()){
        $objDocumentoDTO->setNumIdEtapaTrabalho(null);
      }

      if (!$objDocumentoDTO->isSetNumIdItemEtapa()){
        $objDocumentoDTO->setNumIdItemEtapa(null);
      }

      if (!$objDocumentoDTO->isSetStrIdOperacao()){
        $objDocumentoDTO->setStrIdOperacao(null);
      }

      //conteudo nao existe nas telas de cadastro, apenas em documentos gerados por servicos
      if ($objDocumentoDTO->isSetStrConteudo()){
        $this->validarStrConteudo($objDocumentoDTO, $objInfraException);
      }else{
        $objDocumentoDTO->setStrConteudo(null);
      }

      if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO) {
        if ($objDocumentoDTO->isSetStrProtocoloDocumentoTextoBase() && !InfraString::isBolVazia($objDocumentoDTO->getStrProtocoloDocumentoTextoBase())) {

          $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
          $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_GERADOS);
          $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
          $objPesquisaProtocoloDTO->setStrProtocolo($objDocumentoDTO->getStrProtocoloDocumentoTextoBase());

          $objProtocoloRN = new ProtocoloRN();
          $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

          if (count($arrObjProtocoloDTO) == 0) {
            $objInfraException->lancarValidacao('Documento Base não encontrado.');
          }

          if ($arrObjProtocoloDTO[0]->getStrStaDocumentoDocumento() != DocumentoRN::$TD_EDITOR_INTERNO) {
            $objInfraException->lancarValidacao('Documento Base não foi gerado pelo editor interno.');
          }

          $objDocumentoDTO->setDblIdDocumentoTextoBase($arrObjProtocoloDTO[0]->getDblIdProtocolo());
        }
      }

      $objProtocoloDTO = $objDocumentoDTO->getObjProtocoloDTO();

      /*
      //Em versão futura criar parâmetros para ativar estas validações dando tempo para uma adaptação dos Web Services existentes.

      if ($objProtocoloDTO->isSetArrObjParticipanteDTO() && InfraArray::contar($objProtocoloDTO->getArrObjParticipanteDTO())) {
        if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_GERADO) {
          throw new InfraException('Formulário não pode receber remetente, destinatários ou interessados.');
        }

        foreach($objProtocoloDTO->getArrObjParticipanteDTO() as $objParticipanteDTO){

          if ($objParticipanteDTO->getStrStaParticipacao()==ParticipanteRN::$TP_REMETENTE && $objDocumentoDTO->getStrStaDocumento() != DocumentoRN::$TD_EXTERNO){
            throw new InfraException('Somente documentos externos podem receber remetente.');
          }

          if ($objParticipanteDTO->getStrStaParticipacao()==ParticipanteRN::$TP_DESTINATARIO){

            if ($objDocumentoDTO->getStrStaDocumento() != DocumentoRN::$TD_EDITOR_INTERNO) {
              throw new InfraException('Somente documentos internos podem receber destinatários.');
            }

            if ($objSerieDTO->getStrSinDestinatario()=='N'){
              throw new InfraException('Tipo do documento "'.$objSerieDTO->getStrNome().'" não permite destinatários.');
            }
          }

          if ($objParticipanteDTO->getStrStaParticipacao()==ParticipanteRN::$TP_INTERESSADO){

            if ($objDocumentoDTO->getStrStaDocumento() != DocumentoRN::$TD_EDITOR_INTERNO && $objDocumentoDTO->getStrStaDocumento() != DocumentoRN::$TD_EXTERNO) {
              throw new InfraException('Documento não pode receber interessados.');
            }

            if ($objSerieDTO->getStrSinInteressado()=='N'){
              throw new InfraException('Tipo do documento "'.$objSerieDTO->getStrNome().'" não permite interessados.');
            }
          }
        }
      }
      */

      if ($objDocumentoDTO->getStrStaDocumento() != DocumentoRN::$TD_FORMULARIO_GERADO) {
        if ($objProtocoloDTO->isSetArrObjRelProtocoloAtributoDTO() && InfraArray::contar($objProtocoloDTO->getArrObjRelProtocoloAtributoDTO())) {
          throw new InfraException('Documento não pode receber atributos.');
        }
      }

      if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO || $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_GERADO) {
        if ($objProtocoloDTO->isSetArrObjAnexoDTO() && InfraArray::contar($objProtocoloDTO->getArrObjAnexoDTO())) {
          throw new InfraException('Documento não pode receber anexos.');
        }
      }else if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO){
        if ($objProtocoloDTO->isSetArrObjAnexoDTO() && InfraArray::contar($objProtocoloDTO->getArrObjAnexoDTO())>1){
          throw new InfraException('Mais de um anexo informado para documento recebido.');
        }

        //if ($objDocumentoDTO->getNumIdTipoConferencia()!=null && InfraArray::contar($objProtocoloDTO->getArrObjAnexoDTO())==0){
        //  $objInfraException->adicionarValidacao('Tipo de conferência não pode ser informado porque o documento não contém anexo.');
        //}
      }

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setStrProtocoloProcedimentoFormatado($objProtocoloDTOProcedimento->getStrProtocoloFormatado());
      $objProcedimentoDTO->setNumIdTipoProcedimento($objProtocoloDTOProcedimento->getNumIdTipoProcedimentoProcedimento());
      $objProcedimentoDTO->setStrStaNivelAcessoGlobalProtocolo($objProtocoloDTOProcedimento->getStrStaNivelAcessoGlobal());

      $this->validarNivelAcesso($objDocumentoDTO, $objProcedimentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->retNumIdOrgao();
      $objUnidadeDTO->retStrSigla();
      $objUnidadeDTO->setNumIdUnidade($objDocumentoDTO->getNumIdUnidadeResponsavel());

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

      $objDocumentoDTO->setNumIdOrgaoUnidadeResponsavel($objUnidadeDTO->getNumIdOrgao());
      $objDocumentoDTO->setStrSiglaUnidadeResponsavel($objUnidadeDTO->getStrSigla());

      if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO) {

        //numeracao - inicio
        if ($objSerieDTO->getStrStaNumeracao() == SerieRN::$TN_SEM_NUMERACAO) {
          // nao deve entrar nunca
          if (!InfraString::isBolVazia($objDocumentoDTO->getStrNumero())) {
            $objInfraException->lancarValidacao('Documento com número preenchido mas o tipo ' . $objSerieDTO->getStrNome() . ' não tem numeração.');
          }
          //if ($objDocumentoDTO->isSetStrNomeArvore() && !InfraString::isBolVazia($objDocumentoDTO->getStrNomeArvore())) {
          //  $objInfraException->lancarValidacao('Documento com nome árvore preenchido mas o tipo ' . $objSerieDTO->getStrNome() . ' não tem numeração.');
          //}
        } else if ($objSerieDTO->getStrStaNumeracao() == SerieRN::$TN_INFORMADA) {
          if (InfraString::isBolVazia($objDocumentoDTO->getStrNumero())) {
            $objInfraException->lancarValidacao('Tipo ' . $objSerieDTO->getStrNome() . ' requer preenchimento do número do documento.');
          } else {
            $this->validarTamanhoNumeroRN0993($objDocumentoDTO, $objInfraException);
          }
        } else if (InfraString::isBolVazia($objDocumentoDTO->getStrNumero())) {

          $objNumeracaoDTO = new NumeracaoDTO();
          $objNumeracaoDTO->setNumIdSerie($objSerieDTO->getNumIdSerie());
          $objNumeracaoDTO->setStrStaNumeracaoSerie($objSerieDTO->getStrStaNumeracao());
          $objNumeracaoDTO->setNumIdUnidade($objDocumentoDTO->getNumIdUnidadeResponsavel());
          $objNumeracaoDTO->setNumIdOrgao($objDocumentoDTO->getNumIdOrgaoUnidadeResponsavel());
          $objNumeracaoDTO = $this->gerarNumeracao($objNumeracaoDTO);

          $objDocumentoDTO->setStrNumero($objNumeracaoDTO->getNumSequencial());
        }
        //numeracao - fim

      }else if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO){
        $this->validarTamanhoNumeroRN0993($objDocumentoDTO, $objInfraException);
      }else if ($objDocumentoDTO->isSetStrNumero() && !InfraString::isBolVazia($objDocumentoDTO->getStrNumero())){
        $objInfraException->adicionarValidacao('Número não pode ser informado para formulários.');
      }

      if ($objProtocoloDTOProcedimento->getNumIdPlanoTrabalhoProcedimento()!=null) {

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        if (SessaoSEI::getInstance()->isBolHabilitada() || $objDocumentoDTO->getNumIdItemEtapa()!=null || $objInfraParametro->getValor('SEI_WS_PLANO_TRABALHO_INCLUSAO_DOCUMENTO')=='1') {

          $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
          $objRelItemEtapaSerieDTO->retNumIdItemEtapa();
          $objRelItemEtapaSerieDTO->retNumIdPlanoTrabalhoEtapaTrabalho();
          $objRelItemEtapaSerieDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
          $objRelItemEtapaSerieDTO->setNumIdPlanoTrabalhoEtapaTrabalho($objProtocoloDTOProcedimento->getNumIdPlanoTrabalhoProcedimento());

          $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();
          $arrObjRelItemEtapaSerieDTO = $objRelItemEtapaSerieRN->listar($objRelItemEtapaSerieDTO);

          if (count($arrObjRelItemEtapaSerieDTO) == 0) {
            if ($objDocumentoDTO->getNumIdItemEtapa() != null) {
              $objInfraException->lancarValidacao('Não é possível informar uma etapa porque o tipo de documento "' . $objSerieDTO->getStrNome() . '" não está associado com o Plano de Trabalho "' . $objProtocoloDTOProcedimento->getStrNomePlanoTrabalhoProcedimento() . '".');
            }
          } else {
            if ($objDocumentoDTO->getNumIdItemEtapa() == null) {
              $objInfraException->lancarValidacao('Tipo de documento "' . $objSerieDTO->getStrNome() . '" somente pode ser incluído por meio de uma etapa do Plano de Trabalho "' . $objProtocoloDTOProcedimento->getStrNomePlanoTrabalhoProcedimento() . '".');
            } else {
              if (!in_array($objDocumentoDTO->getNumIdItemEtapa(), InfraArray::converterArrInfraDTO($arrObjRelItemEtapaSerieDTO, 'IdItemEtapa'))) {
                $objInfraException->lancarValidacao('Etapa informada não está associada com o tipo de documento "' . $objSerieDTO->getStrNome() . '" no Plano de Trabalho "' . $objProtocoloDTOProcedimento->getStrNomePlanoTrabalhoProcedimento() . '".');
              }
            }
          }

          if ($objDocumentoDTO->getNumIdItemEtapa()!=null) {
            $objRelItemEtapaUnidadeDTO = new RelItemEtapaUnidadeDTO();
            $objRelItemEtapaUnidadeDTO->retNumIdUnidade();
            $objRelItemEtapaUnidadeDTO->setNumIdItemEtapa($objDocumentoDTO->getNumIdItemEtapa());

            $objRelItemEtapaUnidadeRN = new RelItemEtapaUnidadeRN();
            $arrObjRelItemEtapaUnidadeDTO = $objRelItemEtapaUnidadeRN->listar($objRelItemEtapaUnidadeDTO);

            if (count($arrObjRelItemEtapaUnidadeDTO) && !in_array(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), InfraArray::converterArrInfraDTO($arrObjRelItemEtapaUnidadeDTO,'IdUnidade'))) {
              $objInfraException->lancarValidacao('Não é possível incluir o tipo de documento "'.$objSerieDTO->getStrNome().'" porque a unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().' não está associada com a etapa do Plano de Trabalho "' . $objProtocoloDTOProcedimento->getStrNomePlanoTrabalhoProcedimento() . '".');
            }
          }

          $objRelSeriePlanoTrabalhoDTO = new RelSeriePlanoTrabalhoDTO();
          $objRelSeriePlanoTrabalhoDTO->retNumIdSerie();
          $objRelSeriePlanoTrabalhoDTO->retStrNomePlanoTrabalho();
          $objRelSeriePlanoTrabalhoDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
          $objRelSeriePlanoTrabalhoDTO->setNumIdPlanoTrabalho($objProtocoloDTOProcedimento->getNumIdPlanoTrabalhoProcedimento());

          $objRelSeriePlanoTrabalhoRN = new RelSeriePlanoTrabalhoRN();
          if (($objRelSeriePlanoTrabalhoDTO = $objRelSeriePlanoTrabalhoRN->consultar($objRelSeriePlanoTrabalhoDTO)) != null) {
            $objInfraException->lancarValidacao('O tipo de documento "' . $objSerieDTO->getStrNome() . '" não é permitido no processo por restrições do Plano de Trabalho "' . $objRelSeriePlanoTrabalhoDTO->getStrNomePlanoTrabalho() . '".');
          }
        }
      }

      $objInfraException->lancarValidacoes();

      $objDocumentoDTO->setNumIdModeloSerie($objSerieDTO->getNumIdModelo());

      $objDocumentoDTORet = $this->gravarDocumentoInterno($objDocumentoDTO);

      return $objDocumentoDTORet;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando documento.',$e);
    }
  }

  protected function gravarDocumentoInternoControlado(DocumentoDTO $objDocumentoDTO) {
    try {

      global $SEI_MODULOS;

      $objInfraException = new InfraException();

      $objProtocoloDTO = $objDocumentoDTO->getObjProtocoloDTO();

      $arrArquivosUpload = array();
      if ($objProtocoloDTO->isSetArrObjAnexoDTO()){
        $arrObjAnexoDTO = $objProtocoloDTO->getArrObjAnexoDTO();
        foreach($arrObjAnexoDTO as $objAnexoDTO){
          if (!$objAnexoDTO->isSetStrSinExclusaoAutomatica() || $objAnexoDTO->getStrSinExclusaoAutomatica()=='S') {
            $objAnexoDTO->setStrSinExclusaoAutomatica('N');
            $arrArquivosUpload[] = $objAnexoDTO->getNumIdAnexo();
          }
        }
      }

      $this->tratarProtocoloRN1164($objDocumentoDTO);

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTOGerado = $objProtocoloRN->gerarRN0154($objProtocoloDTO);

      //$objDocumentoDTO->setDblIdProcedimento($objProtocoloDTO->getDblIdProcedimento());
      $objDocumentoDTO->setDblIdDocumento($objProtocoloDTOGerado->getDblIdProtocolo());
      $objDocumentoDTO->setStrStaNivelAcessoGlobalProtocolo($objProtocoloDTOGerado->getStrStaNivelAcessoGlobal());

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo(null);
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProtocoloDTO->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objDocumentoDTO->getDblIdDocumento());
      $objRelProtocoloProtocoloDTO->setNumIdUsuario($objProtocoloDTO->getNumIdUsuarioGerador());
      $objRelProtocoloProtocoloDTO->setNumIdUnidade ($objProtocoloDTO->getNumIdUnidadeGeradora());
      $objRelProtocoloProtocoloDTO->setNumSequencia($objProtocoloRN->obterSequencia($objProtocoloDTO));
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao (RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
      $objRelProtocoloProtocoloDTO->setDthAssociacao(InfraData::getStrDataHoraAtual());

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objRelProtocoloProtocoloRN->cadastrarRN0839($objRelProtocoloProtocoloDTO);

      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_GERADO){
        if ($objProtocoloDTO->isSetArrObjRelProtocoloAtributoDTO()){
          $objDocumentoDTO->setStrConteudo(self::montarConteudoFormulario($objProtocoloDTO->getArrObjRelProtocoloAtributoDTO()));
        }
      }

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->cadastrar($objDocumentoDTO);

      if ($objDocumentoDTO->getStrStaDocumento() != DocumentoRN::$TD_EXTERNO){
        $objDocumentoConteudoDTO = new DocumentoConteudoDTO();
        $objDocumentoConteudoDTO->setStrConteudo($objDocumentoDTO->getStrConteudo());
        $objDocumentoConteudoDTO->setStrConteudoAssinatura(null);
        $objDocumentoConteudoDTO->setStrCrcAssinatura(null);
        $objDocumentoConteudoDTO->setStrQrCodeAssinatura(null);
        $objDocumentoConteudoDTO->setDblIdDocumento($objProtocoloDTOGerado->getDblIdProtocolo());

        $objDocumentoConteudoBD = new DocumentoConteudoBD(BancoSEI::getInstance());
        $objDocumentoConteudoBD->cadastrar($objDocumentoConteudoDTO);
      }

      $this->verificarSobrestamento($objDocumentoDTO);

      $objControleInternoDTO = new ControleInternoDTO();
      $objControleInternoDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
      $objControleInternoDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
      $objControleInternoDTO->setNumIdOrgao($objDocumentoDTO->getNumIdOrgaoUnidadeResponsavel());
      $objControleInternoDTO->setNumIdUnidade($objDocumentoDTO->getNumIdUnidadeResponsavel());
      $objControleInternoDTO->setStrStaNivelAcessoGlobal($objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo());
      $objControleInternoDTO->setStrStaOperacao(ControleInternoRN::$TO_GERAR_DOCUMENTO);

      $objControleInternoRN = new ControleInternoRN();
      $objControleInternoRN->processar($objControleInternoDTO);

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
      $objAtributoAndamentoDTO->setStrValor($objProtocoloDTOGerado->getStrProtocoloFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTOGerado->getDblIdProtocolo());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('NIVEL_ACESSO');
      $objAtributoAndamentoDTO->setStrValor(null);
      $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getStrStaNivelAcessoLocal());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      if (!InfraString::isBolVazia($objProtocoloDTO->getNumIdHipoteseLegal())){
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('HIPOTESE_LEGAL');
        $objAtributoAndamentoDTO->setStrValor(null);
        $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getNumIdHipoteseLegal());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      }

      if (!InfraString::isBolVazia($objProtocoloDTO->getStrStaGrauSigilo())){
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('GRAU_SIGILO');
        $objAtributoAndamentoDTO->setStrValor(null);
        $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getStrStaGrauSigilo());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      }

      if (!InfraString::isBolVazia($objDocumentoDTO->getNumIdTipoConferencia())){
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('TIPO_CONFERENCIA');
        $objAtributoAndamentoDTO->setStrValor(null);
        $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getNumIdTipoConferencia());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      }

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO){
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_RECEBIMENTO_DOCUMENTO);
      }else{
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_GERACAO_DOCUMENTO);
      }

      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

      $objAtividadeRN = new AtividadeRN();
      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      if ($objProtocoloDTO->isSetArrObjAnexoDTO()) {

        $arrAnexos = $objProtocoloDTO->getArrObjAnexoDTO();

        for ($i = 0; $i < InfraArray::contar($arrAnexos); $i++) {

          $arrObjAtributoAndamentoDTO = array();
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('ANEXO');
          $objAtributoAndamentoDTO->setStrValor($arrAnexos[$i]->getStrNome());
          $objAtributoAndamentoDTO->setStrIdOrigem($arrAnexos[$i]->getNumIdAnexo());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
          $objAtributoAndamentoDTO->setStrValor($objProtocoloDTOGerado->getStrProtocoloFormatado());
          $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTOGerado->getDblIdProtocolo());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProcedimento());
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ARQUIVO_ANEXADO);
          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
        }
      }

      if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO) {

        $objEditorDTO = new EditorDTO();
        $objEditorDTO->setDblIdDocumento($objProtocoloDTOGerado->getDblIdProtocolo());
        $objEditorDTO->setNumIdBaseConhecimento(null);
        $objEditorDTO->setNumIdModelo($objDocumentoDTO->getNumIdModeloSerie());

        if ($objDocumentoDTO->isSetDblIdDocumentoBase() && !InfraString::isBolVazia($objDocumentoDTO->getDblIdDocumentoBase())) {

          $objEditorDTO->setDblIdDocumentoBase($objDocumentoDTO->getDblIdDocumentoBase());

        } else if ($objDocumentoDTO->isSetDblIdDocumentoTextoBase() && !InfraString::isBolVazia($objDocumentoDTO->getDblIdDocumentoTextoBase())) {

          $objEditorDTO->setDblIdDocumentoTextoBase($objDocumentoDTO->getDblIdDocumentoTextoBase());

        } else if ($objDocumentoDTO->isSetDblIdDocumentoEdocBase() && !InfraString::isBolVazia($objDocumentoDTO->getDblIdDocumentoEdocBase())) {

          $objEditorDTO->setDblIdDocumentoEdocBase($objDocumentoDTO->getDblIdDocumentoEdocBase());

        } else if ($objDocumentoDTO->isSetArrSecaoConteudo() && is_array($objDocumentoDTO->getArrSecaoConteudo())) {
          $objEditorDTO->setArrConteudoInicialSecoes($objDocumentoDTO->getArrSecaoConteudo());
        }else if ($objDocumentoDTO->getStrConteudo() != null) {

          $objEditorDTO->setStrConteudoSecaoPrincipal($objDocumentoDTO->getStrConteudo());

        } else if ($objDocumentoDTO->isSetNumIdTextoPadraoInterno() && $objDocumentoDTO->getNumIdTextoPadraoInterno() != null) {
          $objEditorDTO->setNumIdTextoPadraoInterno($objDocumentoDTO->getNumIdTextoPadraoInterno());
        }

        $objEditorRN = new EditorRN();
        $objEditorRN->gerarVersaoInicial($objEditorDTO);
      }


      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EXTERNO){
        //Reabertura Automática
        if ($objDocumentoDTO->isSetArrObjUnidadeDTO() && InfraArray::contar($objDocumentoDTO->getArrObjUnidadeDTO()) > 0){

          if ($objProtocoloDTOGerado->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_SIGILOSO){
            $objInfraException->lancarValidacao('Não é possível reabrir automaticamente um processo sigiloso.');
          }

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setBolExclusaoLogica(false);
          $objUnidadeDTO->retStrSigla();
          $objUnidadeDTO->retStrSinProtocolo();
          $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

          $objUnidadeRN = new UnidadeRN();
          $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

          if ($objUnidadeDTO->getStrSinProtocolo()=='N'){
            $objInfraException->lancarValidacao('Unidade '.$objUnidadeDTO->getStrSigla().' não está sinalizada como protocolo.');
          }

          $arrIdUnidadesReabertura = InfraArray::converterArrInfraDTO($objDocumentoDTO->getArrObjUnidadeDTO(),'IdUnidade');

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDistinct(true);
          $objAtividadeDTO->retNumIdUnidade();
          $objAtividadeDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO, InfraDTO::$OPER_DIFERENTE);
          $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
          $objAtividadeDTO->setNumIdTarefa(array(TarefaRN::$TI_GERACAO_PROCEDIMENTO, TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE), InfraDTO::$OPER_IN);
          $objAtividadeDTO->setNumIdUnidade($arrIdUnidadesReabertura,InfraDTO::$OPER_IN);

          $arrIdUnidadeTramitacao = InfraArray::converterArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO),'IdUnidade');

          foreach($arrIdUnidadesReabertura as $numIdUnidadeReabertura){
            if (!in_array($numIdUnidadeReabertura, $arrIdUnidadeTramitacao)){

              $objUnidadeDTO = new UnidadeDTO();
              $objUnidadeDTO->setBolExclusaoLogica(false);
              $objUnidadeDTO->retStrSigla();
              $objUnidadeDTO->setNumIdUnidade($numIdUnidadeReabertura);

              $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

              if ($objUnidadeDTO==null){
                $objInfraException->adicionarValidacao('Unidade ['.$numIdUnidadeReabertura.'] não encontrada para reabertura do processo.');
              }else{
                $objInfraException->adicionarValidacao('Não é possível reabrir o processo na unidade '.$objUnidadeDTO->getStrSigla().' pois não ocorreu tramitação nesta unidade.');
              }
            }
          }

          $objInfraException->lancarValidacoes();

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDistinct(true);
          $objAtividadeDTO->retNumIdUnidade();
          $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
          $objAtividadeDTO->setNumIdUnidade($arrIdUnidadeTramitacao, InfraDTO::$OPER_IN);
          $objAtividadeDTO->setDthConclusao(null);

          $arrIdUnidadeAberto = InfraArray::converterArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO),'IdUnidade');

          $objProcedimentoRN = new ProcedimentoRN();
          foreach($arrIdUnidadesReabertura as $numIdUnidadeReabertura){
            if (!in_array($numIdUnidadeReabertura, $arrIdUnidadeAberto)){
              $objReabrirProcessoDTO = new ReabrirProcessoDTO();
              $objReabrirProcessoDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
              $objReabrirProcessoDTO->setNumIdUnidade($numIdUnidadeReabertura);
              $objReabrirProcessoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
              $objProcedimentoRN->reabrirRN0966($objReabrirProcessoDTO);
            }
          }
        }
      }

      if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO || $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_GERADO) {
        $objSerieEscolhaDTO = new SerieEscolhaDTO();
        $objSerieEscolhaDTO->retNumIdSerie();
        $objSerieEscolhaDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
        $objSerieEscolhaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objSerieEscolhaDTO->setNumMaxRegistrosRetorno(1);

        $objSerieEscolhaRN = new SerieEscolhaRN();
        if ($objSerieEscolhaRN->consultar($objSerieEscolhaDTO) == null) {
          $objSerieEscolhaRN->cadastrar($objSerieEscolhaDTO);
        }
      }

      if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO || $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {

        if ($objDocumentoDTO->isSetDblIdDocumentoBase() && !InfraString::isBolVazia($objDocumentoDTO->getDblIdDocumentoBase())) {
          $dblIdDocumentoModelo = $objDocumentoDTO->getDblIdDocumentoBase();
        }else  if ($objDocumentoDTO->isSetDblIdDocumentoTextoBase() && !InfraString::isBolVazia($objDocumentoDTO->getDblIdDocumentoTextoBase())) {
           $dblIdDocumentoModelo = $objDocumentoDTO->getDblIdDocumentoTextoBase();
        }else{
           $dblIdDocumentoModelo = null;
        }

        if ($objDocumentoDTO->isSetNumIdTextoPadraoInterno() && $objDocumentoDTO->getNumIdTextoPadraoInterno() != null) {
           $numIdTextoPadraoInterno = $objDocumentoDTO->getNumIdTextoPadraoInterno();
        }else{
           $numIdTextoPadraoInterno = null;
        }

        if ($dblIdDocumentoModelo!=null || $numIdTextoPadraoInterno!=null) {
          $objDocumentoGeracaoDTO = new DocumentoGeracaoDTO();
          $objDocumentoGeracaoDTO->setDblIdDocumentoModelo($dblIdDocumentoModelo);
          $objDocumentoGeracaoDTO->setNumIdTextoPadraoInterno($numIdTextoPadraoInterno);
          $objDocumentoGeracaoDTO->setDblIdDocumento($objProtocoloDTOGerado->getDblIdProtocolo());

          $objDocumentoGeracaoRN = new DocumentoGeracaoRN();
          $objDocumentoGeracaoRN->cadastrar($objDocumentoGeracaoDTO);
        }
      }

      if ($objDocumentoDTO->getNumIdItemEtapa()!=null) {
        $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
        $objRelItemEtapaDocumentoDTO->setNumIdItemEtapa($objDocumentoDTO->getNumIdItemEtapa());
        $objRelItemEtapaDocumentoDTO->setDblIdDocumento($objProtocoloDTOGerado->getDblIdProtocolo());

        $objRelItemEtapaDocumentoRN = new RelItemEtapaDocumentoRN();
        $objRelItemEtapaDocumentoRN->cadastrar($objRelItemEtapaDocumentoDTO);
      }

      $objDocumentoDTORet = new DocumentoDTO();
      $objDocumentoDTORet->setDblIdDocumento($objProtocoloDTOGerado->getDblIdProtocolo());
      $objDocumentoDTORet->setStrProtocoloDocumentoFormatado($objProtocoloDTOGerado->getStrProtocoloFormatado());

      if (count($SEI_MODULOS)){
        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($objDocumentoDTORet->getDblIdDocumento());
        $objDocumentoAPI->setNumeroProtocolo($objDocumentoDTORet->getStrProtocoloDocumentoFormatado());
        $objDocumentoAPI->setIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
        $objDocumentoAPI->setIdSerie($objDocumentoDTO->getNumIdSerie());
        $objDocumentoAPI->setNivelAcesso($objDocumentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal());
        $objDocumentoAPI->setSubTipo($objDocumentoDTO->getStrStaDocumento());
        $objDocumentoAPI->setNumero($objDocumentoDTO->getStrNumero());
        $objDocumentoAPI->setData($objProtocoloDTO->getDtaGeracao());
        $objDocumentoAPI->setDinValor($objDocumentoDTO->getDinValor());
        $objDocumentoAPI->setIdPlanoTrabalho($objDocumentoDTO->getNumIdPlanoTrabalho());
        $objDocumentoAPI->setIdEtapaTrabalho($objDocumentoDTO->getNumIdEtapaTrabalho());
        $objDocumentoAPI->setIdItemEtapa($objDocumentoDTO->getNumIdItemEtapa());
        $objDocumentoAPI->setIdOperacao($objDocumentoDTO->getStrIdOperacao());

        foreach($SEI_MODULOS as $seiModulo){
          $seiModulo->executar('gerarDocumento', $objDocumentoAPI);
        }
      }

      foreach($arrArquivosUpload as $strNomeUpload){
        unlink(DIR_SEI_TEMP.'/'.$strNomeUpload);
      }

      return $objDocumentoDTORet;

    }catch(Exception $e){
      throw new InfraException('Erro gravando documento.',$e);
    }
  }

  protected function gerarNumeracaoControlado(NumeracaoDTO $parObjNumeracaoDTO) {
    try {

      $objInfraException = new InfraException();

      $objNumeracaoDTO = new NumeracaoDTO();
      $objNumeracaoDTO->retNumIdNumeracao();
      $objNumeracaoDTO->retNumSequencial();
      $objNumeracaoDTO->setNumIdSerie($parObjNumeracaoDTO->getNumIdSerie());

      if ($parObjNumeracaoDTO->getStrStaNumeracaoSerie() == SerieRN::$TN_SEQUENCIAL_UNIDADE) {
        $objNumeracaoDTO->setNumIdUnidade($parObjNumeracaoDTO->getNumIdUnidade());
        $objNumeracaoDTO->setNumIdOrgao(null);
        $objNumeracaoDTO->setNumAno(null);
      } else if ($parObjNumeracaoDTO->getStrStaNumeracaoSerie() == SerieRN::$TN_SEQUENCIAL_ORGAO) {
        $objNumeracaoDTO->setNumIdUnidade(null);
        $objNumeracaoDTO->setNumIdOrgao($parObjNumeracaoDTO->getNumIdOrgao());
        $objNumeracaoDTO->setNumAno(null);
      } else if ($parObjNumeracaoDTO->getStrStaNumeracaoSerie() == SerieRN::$TN_SEQUENCIAL_ANUAL_UNIDADE) {
        $objNumeracaoDTO->setNumIdUnidade($parObjNumeracaoDTO->getNumIdUnidade());
        $objNumeracaoDTO->setNumIdOrgao(null);
        $objNumeracaoDTO->setNumAno(Date('Y'));
      } else if ($parObjNumeracaoDTO->getStrStaNumeracaoSerie() == SerieRN::$TN_SEQUENCIAL_ANUAL_ORGAO) {
        $objNumeracaoDTO->setNumIdUnidade(null);
        $objNumeracaoDTO->setNumIdOrgao($parObjNumeracaoDTO->getNumIdOrgao());
        $objNumeracaoDTO->setNumAno(Date('Y'));
      } else {
        $objInfraException->lancarValidacao('Tipo de numeração inválido.');
      }

      $objNumeracaoDTO->setOrdNumSequencial(InfraDTO::$TIPO_ORDENACAO_DESC);

      $objNumeracaoRN = new NumeracaoRN();
      $arrObjNumeracaoDTORet = $objNumeracaoRN->listar($objNumeracaoDTO);

      if (count($arrObjNumeracaoDTORet)==0) {
        try {
          $objNumeracaoDTONovo = clone($objNumeracaoDTO);
          $objNumeracaoDTONovo->setNumSequencial(0);
          $objNumeracaoDTORet = $objNumeracaoRN->cadastrar($objNumeracaoDTONovo);
        }catch(Exception $e){
          $objNumeracaoDTORet = $objNumeracaoRN->consultar($objNumeracaoDTO);
          if ($objNumeracaoDTORet==null){
            throw $e;
          }
        }
      }else{
        $objNumeracaoDTORet = $arrObjNumeracaoDTORet[0];
      }

      $objNumeracaoDTORet = $objNumeracaoRN->bloquear($objNumeracaoDTORet);

      $objNumeracaoDTO = new NumeracaoDTO();
      $objNumeracaoDTO->setNumSequencial($objNumeracaoDTORet->getNumSequencial() + 1);
      $objNumeracaoDTO->setNumIdNumeracao($objNumeracaoDTORet->getNumIdNumeracao());

      $objNumeracaoRN->alterar($objNumeracaoDTO);

      if (count($arrObjNumeracaoDTORet) > 1) {
        $objNumeracaoRN->excluir(array_slice($arrObjNumeracaoDTORet,1));
      }

      return $objNumeracaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro gerando numeração de documento.',$e);
    }
  }

  public function alterarRN0004(DocumentoDTO $parObjDocumentoDTO){

    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoDTO->retStrStaDocumento();
    $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
    $objDocumentoDTOBanco = $this->consultarRN0005($objDocumentoDTO);

    if ($objDocumentoDTOBanco==null){
      throw new InfraException('Documento não encontrado.');
    }

    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $objIndexacaoDTO 	= new IndexacaoDTO();
    $objIndexacaoDTO->setArrIdProtocolos(array($parObjDocumentoDTO->getDblIdDocumento()));

    $objIndexacaoRN	= new IndexacaoRN();

    if ($objDocumentoDTOBanco->getStrStaDocumento()==DocumentoRN::$TD_EXTERNO){
      $objIndexacaoRN->prepararRemocaoProtocolo($objIndexacaoDTO);
    }

    $this->alterarRN0004Interno($parObjDocumentoDTO);

    if ($objDocumentoDTOBanco->getStrStaDocumento()==DocumentoRN::$TD_EXTERNO){
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_METADADOS_E_CONTEUDO);
    }else {
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_METADADOS);
    }

    $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);

    if (!$bolAcumulacaoPrevia){
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }
  }

  protected function alterarRN0004InternoControlado(DocumentoDTO $parObjDocumentoDTO){
    try {

      global $SEI_MODULOS;

      $objInfraException = new InfraException();

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdDocumentoEdoc();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO->retStrStaEstadoProcedimento();
      $objDocumentoDTO->retStrSinEliminadoProcedimento();
      $objDocumentoDTO->retNumIdSerie();
      $objDocumentoDTO->retNumIdTipoConferencia();
      $objDocumentoDTO->retStrSinArquivamento();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrNumero();
      $objDocumentoDTO->retStrNomeArvore();
      $objDocumentoDTO->retDinValor();
      $objDocumentoDTO->retDtaGeracaoProtocolo();
      $objDocumentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retStrStaEditor();
      $objDocumentoDTO->retStrSinBloqueado();
      $objDocumentoDTO->retNumIdTipoFormulario();
      $objDocumentoDTO->retStrStaNivelAcessoGlobalProtocolo();
      $objDocumentoDTO->retStrStaNivelAcessoLocalProtocolo();
      $objDocumentoDTO->retNumIdTipoProcedimentoProcedimento();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTOBanco = $this->consultarRN0005($objDocumentoDTO);

      switch($objDocumentoDTOBanco->getStrStaDocumento()){

        case DocumentoRN::$TD_EDITOR_INTERNO:
          SessaoSEI::getInstance()->validarAuditarPermissao('documento_alterar', __METHOD__, $parObjDocumentoDTO);
          break;

        case DocumentoRN::$TD_EXTERNO:
          SessaoSEI::getInstance()->validarAuditarPermissao('documento_alterar_recebido', __METHOD__, $parObjDocumentoDTO);
          break;

        case DocumentoRN::$TD_FORMULARIO_AUTOMATICO:
        case DocumentoRN::$TD_FORMULARIO_GERADO:
          SessaoSEI::getInstance()->validarAuditarPermissao('formulario_alterar', __METHOD__, $parObjDocumentoDTO);
          break;
      }

      if ($parObjDocumentoDTO->isSetStrStaDocumento() && $parObjDocumentoDTO->getStrStaDocumento() != $objDocumentoDTOBanco->getStrStaDocumento()){
        $objInfraException->adicionarValidacao('Não é possível alterar o sinalizador interno do documento.');
      }else{
        $parObjDocumentoDTO->setStrStaDocumento($objDocumentoDTOBanco->getStrStaDocumento());
      }

      if ($parObjDocumentoDTO->isSetStrStaEditor() && $parObjDocumentoDTO->getStrStaEditor() != $objDocumentoDTOBanco->getStrStaEditor()){
        $objInfraException->adicionarValidacao('Não é possível alterar o tipo do editor do documento.');
      }else{
        $parObjDocumentoDTO->setStrStaEditor($objDocumentoDTOBanco->getStrStaEditor());
      }

      if ($parObjDocumentoDTO->isSetDblIdProcedimento() && $parObjDocumentoDTO->getDblIdProcedimento() != $objDocumentoDTOBanco->getDblIdProcedimento()){
        $objInfraException->adicionarValidacao('Não é possível alterar o processo onde o documento foi cadastrado.');
      }else{
        $objDocumentoDTO->setDblIdProcedimento($objDocumentoDTOBanco->getDblIdProcedimento());
      }

      $objProcedimentoRN = new ProcedimentoRN();
      if ($objDocumentoDTOBanco->getStrStaEstadoProcedimento()==ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
        $objProcedimentoRN->verificarProcessoAnexadorAberto($objDocumentoDTOBanco);
      }else{
        $objProcedimentoRN->verificarEstadoProcedimento($objDocumentoDTOBanco);
      }

      if ($parObjDocumentoDTO->isSetStrConteudoAssinatura()){
        $parObjDocumentoDTO->unSetStrConteudoAssinatura();
      }

      if ($parObjDocumentoDTO->isSetStrCrcAssinatura()){
        $parObjDocumentoDTO->unSetStrCrcAssinatura();
      }

      if ($parObjDocumentoDTO->isSetStrQrCodeAssinatura()){
        $parObjDocumentoDTO->unSetStrQrCodeAssinatura();
      }

      if ($parObjDocumentoDTO->isSetNumIdConjuntoEstilos()){
        $parObjDocumentoDTO->unSetNumIdConjuntoEstilos();
      }

      if ($parObjDocumentoDTO->isSetDblIdDocumentoEdoc()){
        $parObjDocumentoDTO->unSetDblIdDocumentoEdoc();
      }

      if ($parObjDocumentoDTO->isSetStrSinBloqueado() && $parObjDocumentoDTO->getStrSinBloqueado() != $objDocumentoDTOBanco->getStrSinBloqueado()){
        $objInfraException->adicionarValidacao('Não é possível alterar o sinalizador de bloqueio do documento.');
      }else{
        $parObjDocumentoDTO->setStrSinBloqueado($objDocumentoDTOBanco->getStrSinBloqueado());
      }

      if ($parObjDocumentoDTO->isSetNumIdUnidadeResponsavel()){
        $parObjDocumentoDTO->unSetNumIdUnidadeResponsavel();
      }

      $bolAlterouSerie = false;
      if ($parObjDocumentoDTO->isSetNumIdSerie() && $parObjDocumentoDTO->getNumIdSerie()!=$objDocumentoDTOBanco->getNumIdSerie()) {

        if ($objDocumentoDTOBanco->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO) {
          $this->validarNumIdSerieRN0009($parObjDocumentoDTO, $objInfraException);
          $bolAlterouSerie = true;
        } else {
          $objInfraException->adicionarValidacao('Não é possível alterar o tipo do documento.');
        }

        $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
        if (($arrObjRelItemEtapaSerieDTO = $objPlanoTrabalhoRN->obterEtapasDocumento($parObjDocumentoDTO)) != null) {
          $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
          $objRelItemEtapaDocumentoDTO->retNumIdItemEtapa();
          $objRelItemEtapaDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
          $objRelItemEtapaDocumentoDTO->setNumIdItemEtapa(InfraArray::converterArrInfraDTO($arrObjRelItemEtapaSerieDTO, 'IdItemEtapa'), InfraDTO::$OPER_IN);

          $objRelItemEtapaDocumentoRN = new RelItemEtapaDocumentoRN();
          if (count($objRelItemEtapaDocumentoRN->listar($objRelItemEtapaDocumentoDTO)) == 0) {
            $objInfraException->lancarValidacao('O tipo de documento "' . $arrObjRelItemEtapaSerieDTO[0]->getStrNomeSerie() . '" somente pode ser incluído no processo por meio do Plano de Trabalho.');
          }
        }

      }else{
        $parObjDocumentoDTO->setNumIdSerie($objDocumentoDTOBanco->getNumIdSerie());
      }

      if ($parObjDocumentoDTO->isSetNumIdTipoFormulario() && $parObjDocumentoDTO->getNumIdTipoFormulario() != $objDocumentoDTOBanco->getNumIdTipoFormulario()){
        $objInfraException->adicionarValidacao('Não é possível alterar o tipo do formulário do documento.');
      }

      if ($parObjDocumentoDTO->isSetStrNumero() && (string)$parObjDocumentoDTO->getStrNumero()!==(string)$objDocumentoDTOBanco->getStrNumero()) {
        if ($objDocumentoDTOBanco->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO) {
          $this->validarTamanhoNumeroRN0993($parObjDocumentoDTO, $objInfraException);
        } else {

          $objSerieDTO = new SerieDTO();
          $objSerieDTO->setBolExclusaoLogica(false);
          $objSerieDTO->retStrStaNumeracao();
          $objSerieDTO->setNumIdSerie($objDocumentoDTOBanco->getNumIdSerie());

          $objSerieRN = new SerieRN();
          $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);

          if ($objSerieDTO->getStrStaNumeracao()!=SerieRN::$TN_INFORMADA) {
            $objInfraException->adicionarValidacao('Não é possível alterar o número do documento.');
          }
        }
      }else{
        $parObjDocumentoDTO->setStrNumero($objDocumentoDTOBanco->getStrNumero());
      }

      if($parObjDocumentoDTO->isSetStrNomeArvore() && $parObjDocumentoDTO->getStrNomeArvore()!=$objDocumentoDTOBanco->getStrNomeArvore()) {
        $this->validarStrNomeArvore($parObjDocumentoDTO, $objInfraException);
      }

      if($parObjDocumentoDTO->isSetDinValor() && $parObjDocumentoDTO->getDinValor()!=$objDocumentoDTOBanco->getDinValor()) {
        $this->validarDinValor($parObjDocumentoDTO, $objInfraException);
      }else{
        $parObjDocumentoDTO->setDinValor($objDocumentoDTOBanco->getDinValor());
      }

      //o conteudo é alterado apenas por uma chamada separada
      if ($parObjDocumentoDTO->isSetStrConteudo()){
        $parObjDocumentoDTO->unSetStrConteudo();
      }

      $dtaGeracao = $objDocumentoDTOBanco->getDtaGeracaoProtocolo();

      if ($parObjDocumentoDTO->isSetObjProtocoloDTO()){

        $objProtocoloDTO = $parObjDocumentoDTO->getObjProtocoloDTO();

        if ($objProtocoloDTO->isSetDtaGeracao() && $objProtocoloDTO->getDtaGeracao() != $objDocumentoDTOBanco->getDtaGeracaoProtocolo()){

          if ($objDocumentoDTOBanco->getStrStaDocumento() != DocumentoRN::$TD_EXTERNO) {
            $objInfraException->adicionarValidacao('Não é possível alterar a data do documento.');
          }

          $dtaGeracao = $objProtocoloDTO->getDtaGeracao();
        }

        //if ($objDocumentoDTOBanco->getStrStaDocumento() != DocumentoRN::$TD_FORMULARIO_GERADO) {
          if ($objProtocoloDTO->isSetArrObjRelProtocoloAtributoDTO() && InfraArray::contar($objProtocoloDTO->getArrObjRelProtocoloAtributoDTO())) {
            throw new InfraException('Não é possível alterar os atributos do documento.');
          }
        //}

        if ($objProtocoloDTO->isSetArrObjAnexoDTO()){

          if ($objDocumentoDTOBanco->getStrStaDocumento()!=DocumentoRN::$TD_EXTERNO){

            $objProtocoloDTO->unSetArrObjAnexoDTO();

          }else {

            if (InfraArray::contar($objProtocoloDTO->getArrObjAnexoDTO()) > 1) {
              throw new InfraException('Mais de um anexo informado para documento recebido.');
            }

            //busca conjunto de anexos antes da alteração
            $objAnexoDTO = new AnexoDTO();
            $objAnexoDTO->retNumIdAnexo();
            $objAnexoDTO->retStrNome();
            $objAnexoDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

            $objAnexoRN = new AnexoRN();
            $arrObjAnexoDTOOriginal = $objAnexoRN->listarRN0218($objAnexoDTO);

          }
        }

        if ($objProtocoloDTO->isSetStrStaNivelAcessoLocal()){

          if ($objDocumentoDTOBanco->getNumIdUnidadeGeradoraProtocolo()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objProtocoloDTO->getStrStaNivelAcessoLocal()!=$objDocumentoDTOBanco->getStrStaNivelAcessoLocalProtocolo()) {

            $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
            $bolPermitirAlteracaoNivelAcesso = $objInfraParametro->getValor('SEI_ALTERACAO_NIVEL_ACESSO_DOCUMENTO',false);

            if ($bolPermitirAlteracaoNivelAcesso != '1') {
              $objInfraException->adicionarValidacao('Nível de acesso do documento somente pode ser alterado pela unidade '.$objDocumentoDTOBanco->getStrSiglaUnidadeGeradoraProtocolo().'.');
            }
          }

          $objProcedimentoDTO = new ProcedimentoDTO();
          $objProcedimentoDTO->setStrProtocoloProcedimentoFormatado($objDocumentoDTOBanco->getStrProtocoloProcedimentoFormatado());
          $objProcedimentoDTO->setNumIdTipoProcedimento($objDocumentoDTOBanco->getNumIdTipoProcedimentoProcedimento());
          $objProcedimentoDTO->setStrStaNivelAcessoGlobalProtocolo($objDocumentoDTOBanco->getStrStaNivelAcessoGlobalProtocolo());

          $this->validarNivelAcesso($parObjDocumentoDTO, $objProcedimentoDTO, $objInfraException);
        }


        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloRN->alterarRN0203($objProtocoloDTO);

        /*
        if ($objDocumentoDTOBanco->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_GERADO){
          if ($objProtocoloDTO->isSetArrObjRelProtocoloAtributoDTO()){
            $parObjDocumentoDTO->setStrConteudo(self::montarConteudoFormulario($objProtocoloDTO->getArrObjRelProtocoloAtributoDTO()));
          }
        }
        */
      }else{
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setStrStaNivelAcessoLocal($objDocumentoDTOBanco->getStrStaNivelAcessoLocalProtocolo());
        $parObjDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);
      }

      $objInfraException->lancarValidacoes();

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->alterar($parObjDocumentoDTO);

      if ($objDocumentoDTOBanco->getStrStaDocumento()==DocumentoRN::$TD_EXTERNO) {

        if ($parObjDocumentoDTO->isSetObjProtocoloDTO() && $parObjDocumentoDTO->getObjProtocoloDTO()->isSetArrObjAnexoDTO()) {

          //busca conjunto de anexos após a alteração
          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO->retNumIdAnexo();
          $objAnexoDTO->retStrNome();
          $objAnexoDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

          $objAnexoRN = new AnexoRN();
          $arrObjAnexoDTONovo = $objAnexoRN->listarRN0218($objAnexoDTO);

          $arrIdAnexoOriginal = InfraArray::converterArrInfraDTO($arrObjAnexoDTOOriginal, 'IdAnexo');
          $arrIdAnexoNovo = InfraArray::converterArrInfraDTO($arrObjAnexoDTONovo, 'IdAnexo');

          sort($arrIdAnexoOriginal);
          sort($arrIdAnexoNovo);

          //verifica se houve alteração no conteúdo (adicionou, removeu ou modificou)
          if ($arrIdAnexoOriginal != $arrIdAnexoNovo) {

            if ($objDocumentoDTOBanco->getStrStaEstadoProcedimento()==ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
              $objInfraException->lancarValidacao('Conteúdo do documento não pode ser alterado porque o processo está anexado.');
            }

            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloDTO->retStrSinCiencia();
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objDocumentoDTOBanco->getDblIdProcedimento());
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objDocumentoDTOBanco->getDblIdDocumento());
            $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);

            $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
            $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

            if ($objRelProtocoloProtocoloDTO->getStrSinCiencia() == 'S') {
              $objInfraException->lancarValidacao('Conteúdo do documento não pode ser alterado porque recebeu ciência.');
            }

            $objArquivamentoDTO = new ArquivamentoDTO();
            $objArquivamentoDTO->retStrStaArquivamento();
            $objArquivamentoDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

            $objArquivamentoRN = new ArquivamentoRN();
            $objArquivamentoDTO = $objArquivamentoRN->consultar($objArquivamentoDTO);

            if ($objArquivamentoDTO!=null) {
              if ($objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_ARQUIVADO || $objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO) {
                $objInfraException->lancarValidacao('Conteúdo do documento não pode ser alterado porque está arquivado.');
              }
            }

            if ($objDocumentoDTOBanco->getStrSinBloqueado() == 'S') {
              $objInfraException->lancarValidacao('Não é mais possível alterar o conteúdo do documento.');
            }
          }

          $arrObjAnexoDTOOriginal = InfraArray::indexarArrInfraDTO($arrObjAnexoDTOOriginal, 'IdAnexo');
          $arrObjAnexoDTONovo = InfraArray::indexarArrInfraDTO($arrObjAnexoDTONovo, 'IdAnexo');

          //verifica se removeu pelo menos um anexo
          foreach ($arrObjAnexoDTOOriginal as $objAnexoDTOOriginal) {
            if (!in_array($objAnexoDTOOriginal->getNumIdAnexo(), array_keys($arrObjAnexoDTONovo))) {

              $this->cancelarAssinatura($parObjDocumentoDTO);

              $objProtocoloDTO = new ProtocoloDTO();
              $objProtocoloDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

              $objArquivamentoRN = new ArquivamentoRN();
              $objArquivamentoRN->validarProtocoloArquivadoAlteracao($objProtocoloDTO);

              $objDocumentoAPI = new DocumentoAPI();
              $objDocumentoAPI->setIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
              
              foreach ($SEI_MODULOS as $seiModulo) {
                $seiModulo->executar('atualizarConteudoDocumento', $objDocumentoAPI);
              }

              break;
            }
          }

          //lança um andamento para cada anexo removido
          foreach ($arrObjAnexoDTOOriginal as $objAnexoDTOOriginal) {
            if (!in_array($objAnexoDTOOriginal->getNumIdAnexo(), array_keys($arrObjAnexoDTONovo))) {

              $arrObjAtributoAndamentoDTO = array();
              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->setStrNome('ANEXO');
              $objAtributoAndamentoDTO->setStrValor($objAnexoDTOOriginal->getStrNome());
              $objAtributoAndamentoDTO->setStrIdOrigem($objAnexoDTOOriginal->getNumIdAnexo());
              $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
              $objAtributoAndamentoDTO->setStrValor($objDocumentoDTOBanco->getStrProtocoloDocumentoFormatado());
              $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTOBanco->getDblIdDocumento());
              $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

              $objAtividadeDTO = new AtividadeDTO();
              $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTOBanco->getDblIdProcedimento());
              $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
              $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ARQUIVO_DESANEXADO);
              $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

              $objAtividadeRN = new AtividadeRN();
              $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
            }
          }

          //lança um andamento para cada anexo incluido
          foreach ($arrObjAnexoDTONovo as $objAnexoNovo) {
            if (!in_array($objAnexoNovo->getNumIdAnexo(), array_keys($arrObjAnexoDTOOriginal))) {

              $arrObjAtributoAndamentoDTO = array();
              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->setStrNome('ANEXO');
              $objAtributoAndamentoDTO->setStrValor($objAnexoNovo->getStrNome());
              $objAtributoAndamentoDTO->setStrIdOrigem($objAnexoNovo->getNumIdAnexo());
              $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
              $objAtributoAndamentoDTO->setStrValor($objDocumentoDTOBanco->getStrProtocoloDocumentoFormatado());
              $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTOBanco->getDblIdDocumento());
              $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

              $objAtividadeDTO = new AtividadeDTO();
              $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTOBanco->getDblIdProcedimento());
              $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
              $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ARQUIVO_ANEXADO);
              $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

              $objAtividadeRN = new AtividadeRN();
              $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
            }
          }
        }
      }

      //validar tipo de conferência após anexos pois a troca de anexo cancela as autenticações
      if ($parObjDocumentoDTO->isSetNumIdTipoConferencia() && $parObjDocumentoDTO->getNumIdTipoConferencia()!=$objDocumentoDTOBanco->getNumIdTipoConferencia()) {
        if ($objDocumentoDTOBanco->getNumIdUnidadeGeradoraProtocolo()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
          $objInfraException->adicionarValidacao('Tipo de conferência somente pode ser alterado pela unidade '.$objDocumentoDTOBanco->getStrSiglaUnidadeGeradoraProtocolo().'.');
        }else{

          $this->validarNumIdTipoConferencia($parObjDocumentoDTO, $objInfraException);

          if ($objDocumentoDTOBanco->getNumIdUnidadeGeradoraProtocolo()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
            $objInfraException->adicionarValidacao('Tipo de Conferência do documento somente pode ser alterado pela unidade '.$objDocumentoDTOBanco->getStrSiglaUnidadeGeradoraProtocolo().'.');
          }else {

            $objAssinaturaDTO = new AssinaturaDTO();
            $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTOBanco->getDblIdDocumento());

            $objAssinaturaRN = new AssinaturaRN();
            $numAssinaturas = $objAssinaturaRN->contarRN1324($objAssinaturaDTO);
            if ($numAssinaturas) {
              $objInfraException->adicionarValidacao('Tipo de conferência não pode ser alterado porque o documento contém '.($numAssinaturas == 1 ? 'autenticação' : 'autenticações').'.');
            } else {

              /*
              if ($parObjDocumentoDTO->getNumIdTipoConferencia() != null) {
                $objAnexoDTO = new AnexoDTO();
                $objAnexoDTO->retNumIdAnexo();
                $objAnexoDTO->setDblIdProtocolo($objDocumentoDTOBanco->getDblIdDocumento());
                $objAnexoDTO->setNumMaxRegistrosRetorno(1);

                $objAnexoRN = new AnexoRN();
                if ($objAnexoRN->consultarRN0736($objAnexoDTO) == null) {
                  $objInfraException->adicionarValidacao('Tipo de conferência não pode ser informado porque o documento não contém anexo.');
                }
              }
              */

              $arrObjAtributoAndamentoDTO = array();

              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
              $objAtributoAndamentoDTO->setStrValor($objDocumentoDTOBanco->getStrProtocoloDocumentoFormatado());
              $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTOBanco->getDblIdDocumento());
              $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->setStrNome('TIPO_CONFERENCIA');
              $objAtributoAndamentoDTO->setStrValor(null);
              $objAtributoAndamentoDTO->setStrIdOrigem($parObjDocumentoDTO->getNumIdTipoConferencia());
              $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

              $objAtividadeDTO = new AtividadeDTO();
              $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
              $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTOBanco->getDblIdProcedimento());
              $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ALTERACAO_TIPO_CONFERENCIA_DOCUMENTO);
              $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

              $objAtividadeRN = new AtividadeRN();
              $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
            }
          }
        }
      }

      if ($parObjDocumentoDTO->isSetStrSinArquivamento() && $parObjDocumentoDTO->getStrSinArquivamento()!=$objDocumentoDTOBanco->getStrSinArquivamento()) {

        $this->validarStrSinArquivamento($parObjDocumentoDTO, $objInfraException);

        if ($objDocumentoDTOBanco->getNumIdUnidadeGeradoraProtocolo()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
          $objInfraException->adicionarValidacao('Sinalizador de arquivamento do documento somente pode ser alterado pela unidade '.$objDocumentoDTOBanco->getStrSiglaUnidadeGeradoraProtocolo().'.');
        }else {
          if ($parObjDocumentoDTO->getStrSinArquivamento() == 'N') {
            $objArquivamentoDTO = new ArquivamentoDTO();
            $objArquivamentoDTO->retStrStaArquivamento();
            $objArquivamentoDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

            $objArquivamentoRN = new ArquivamentoRN();
            $objArquivamentoDTO = $objArquivamentoRN->consultar($objArquivamentoDTO);

            if ($objArquivamentoDTO != null) {
              if ($objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_ARQUIVADO || $objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO) {
                $objInfraException->lancarValidacao('Sinalizador de arquivamento não pode ser alterado porque o documento já está arquivado.');
              }
            }
          }
        }
      }

      $objInfraException->lancarValidacoes();

      if (count($SEI_MODULOS)){
        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
        $objDocumentoAPI->setNumeroProtocolo($objDocumentoDTOBanco->getStrProtocoloDocumentoFormatado());
        $objDocumentoAPI->setIdProcedimento($parObjDocumentoDTO->getDblIdProcedimento());
        $objDocumentoAPI->setIdSerie($parObjDocumentoDTO->getNumIdSerie());
        $objDocumentoAPI->setNivelAcesso($parObjDocumentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal());
        $objDocumentoAPI->setSubTipo($parObjDocumentoDTO->getStrStaDocumento());
        $objDocumentoAPI->setNumero($parObjDocumentoDTO->getStrNumero());
        $objDocumentoAPI->setData($dtaGeracao);
        $objDocumentoAPI->setDinValor($parObjDocumentoDTO->getDinValor());

        foreach($SEI_MODULOS as $seiModulo){
          $seiModulo->executar('alterarDocumento', $objDocumentoAPI);
        }
      }

      if ($bolAlterouSerie){
        $objControleInternoDTO = new ControleInternoDTO();
        $objControleInternoDTO->setDblIdProcedimento($objDocumentoDTOBanco->getDblIdProcedimento());
        $objControleInternoDTO->setNumIdSerie($parObjDocumentoDTO->getNumIdSerie());
        $objControleInternoDTO->setNumIdSerieAnterior($objDocumentoDTOBanco->getNumIdSerie());
        $objControleInternoDTO->setNumIdOrgao($objDocumentoDTOBanco->getNumIdOrgaoUnidadeGeradoraProtocolo());
        $objControleInternoDTO->setStrStaOperacao(ControleInternoRN::$TO_ALTERAR_DOCUMENTO);

        $objControleInternoRN = new ControleInternoRN();
        $objControleInternoRN->processar($objControleInternoDTO);
      }

      //Auditoria

      return $parObjDocumentoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro alterando documento.',$e);
    }
  }


  protected function eliminarControlado(array $arrObjDocumentoDTO){
    try {
      global $SEI_MODULOS;

      //testa se foram passados documentos
      if (InfraArray::contar($arrObjDocumentoDTO)) {

        $arrObjDocumentoAPI = array();
        foreach($arrObjDocumentoDTO as $objDocumentoDTO){
          $objDocumentoAPI = new DocumentoAPI();
          $objDocumentoAPI->setIdDocumento($objDocumentoDTO->getDblIdDocumento());
          $objDocumentoAPI->setNumeroProtocolo($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
          $arrObjDocumentoAPI[] = $objDocumentoAPI;
        }

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('eliminarDocumento', $arrObjDocumentoAPI);
        }

        //elimina os documentos
        $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
        $objDocumentoBD->eliminar($arrObjDocumentoDTO);
        //passa os ids dos documentos ao solr
        $objIndexacaoDTO = new IndexacaoDTO();
        $objIndexacaoDTO->setArrIdProtocolos(InfraArray::converterArrInfraDTO($arrObjDocumentoDTO, "IdDocumento"));
        //prepara para remocao do solr
        $objIndexacaoRN = new IndexacaoRN();
        $objIndexacaoRN->prepararRemocaoProtocolo($objIndexacaoDTO);
      }
    }catch(Exception $e){
      throw new InfraException('Erro eliminando Documento.', $e);
    }
  }

  public function excluirRN0006(DocumentoDTO $parObjDocumentoDTO){

    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $objIndexacaoDTO = new IndexacaoDTO();
    $objIndexacaoDTO->setArrIdProtocolos(array($parObjDocumentoDTO->getDblIdDocumento()));

    $objIndexacaoRN	= new IndexacaoRN();
    $objIndexacaoRN->prepararRemocaoProtocolo($objIndexacaoDTO);

    $this->excluirRN0006Interno($parObjDocumentoDTO);

    if (!$bolAcumulacaoPrevia){
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }
  }

  protected function excluirRN0006InternoControlado(DocumentoDTO $parObjDocumentoDTO){
    try {

      global $SEI_MODULOS;

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retNumIdSerie();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO->retStrStaEstadoProcedimento();
      $objDocumentoDTO->retStrSinEliminadoProcedimento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrStaNivelAcessoGlobalProtocolo();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retStrConteudo();
      $objDocumentoDTO->retStrSinBloqueado();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

      if ($objDocumentoDTO==null){
        //throw new InfraException('Registro não encontrado.');
        $objInfraException->lancarValidacao('Documento não encontrado.');
      }

      $parObjDocumentoDTO->setStrConteudo($this->obterConteudoAuditoriaExclusaoCancelamento($objDocumentoDTO));

      SessaoSEI::getInstance()->validarAuditarPermissao('documento_excluir',__METHOD__,$parObjDocumentoDTO);

      if($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()!= SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
        $objInfraException->lancarValidacao('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' somente pode ser excluído pela unidade geradora.');
      }

      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_AUTOMATICO){
        $objInfraException->lancarValidacao('Formulário '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' não pode ser excluído.');
      }

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->verificarEstadoProcedimento($objDocumentoDTO);

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjDocumentoDTO->getDblIdDocumento());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO);
      $objRelProtocoloProtocoloDTO->setNumMaxRegistrosRetorno(1);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      if ($objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO) != null){
        $objInfraException->lancarValidacao('Não foi possível excluir o documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' porque ele foi movimentado entre processos.');
      }

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjDocumentoDTO->getDblIdDocumento());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_CIRCULAR);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      if (($numCircular = $objRelProtocoloProtocoloRN->contarRN0843($objRelProtocoloProtocoloDTO))){
        $objInfraException->lancarValidacao('Não foi possível excluir o documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' porque ele é base para '.($numCircular==1?'um documento circular':$numCircular.' documentos circulares').'.');
      }

      if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){
        $this->validarDocumentoPublicadoRN1211($parObjDocumentoDTO);
      }

      if ($objDocumentoDTO->getStrSinBloqueado()=='S'){
        $objInfraException->lancarValidacao('Não é mais possível excluir o documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().'.');
      }

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->setBolExclusaoLogica(false);
      $objAcessoExternoDTO->retNumIdAcessoExterno();
      $objAcessoExternoDTO->retNumIdTarefaAtividade();
      $objAcessoExternoDTO->retDtaValidade();
      $objAcessoExternoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_ASSINATURA_EXTERNA);

      $objAcessoExternoRN = new AcessoExternoRN();
      $arrObjAcessoExternoDTO = $objAcessoExternoRN->listar($objAcessoExternoDTO);

      foreach($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {
        if ($objAcessoExternoDTO->getNumIdTarefaAtividade()==TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA) {
          if ($objAcessoExternoDTO->getDtaValidade()!=null && InfraData::compararDatas(InfraData::getStrDataAtual(), $objAcessoExternoDTO->getDtaValidade()) >= 0){
            $objInfraException->lancarValidacao('Não foi possível excluir o documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' porque foi dada liberação para assinatura externa.');
          }
        }
      }

      if ($objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_SIGILOSO) {
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->retNumIdAtributoAndamento();
        $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setDblIdProtocoloAtividade($objDocumentoDTO->getDblIdProcedimento());
        $objAtributoAndamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_CONCESSAO_CREDENCIAL_ASSINATURA);
        $objAtributoAndamentoDTO->setNumMaxRegistrosRetorno(1);

        $objAtributoAndamentoRN = new AtributoAndamentoRN();
        if ($objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO) != null){
          $objInfraException->lancarValidacao('Não foi possível excluir o documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' porque existe Credencial para Assinatura ativa.');
        }
      }

      $objEditalEliminacaoRN = new EditalEliminacaoRN();
      $objEditalEliminacaoRN->removerDocumento($objDocumentoDTO);

      $objInfraException->lancarValidacoes();

      $objDocumentoAPI = new DocumentoAPI();
      $objDocumentoAPI->setIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

      foreach($SEI_MODULOS as $seiModulo){
        $seiModulo->executar('excluirDocumento', $objDocumentoAPI);
      }


      $objAcessoExternoRN->excluir($arrObjAcessoExternoDTO);

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objDocumentoDTO->getDblIdDocumento());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->retNumIdAssinatura();
      $objAssinaturaDTO->setBolExclusaoLogica(false); //pode ter assinatura digital pendente de confirmação
      $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

      $objAssinaturaRN = new AssinaturaRN();
      $objAssinaturaRN->excluirRN1321($objAssinaturaRN->listarRN1323($objAssinaturaDTO));

      $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
      $objRelItemEtapaDocumentoDTO->retNumIdItemEtapa();
      $objRelItemEtapaDocumentoDTO->retDblIdDocumento();
      $objRelItemEtapaDocumentoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

      $objRelItemEtapaDocumentoRN = new RelItemEtapaDocumentoRN();
      $objRelItemEtapaDocumentoRN->excluir($objRelItemEtapaDocumentoRN->listar($objRelItemEtapaDocumentoDTO));

      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){
        $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
        $objSecaoDocumentoDTO->retNumIdSecaoDocumento();
        $objSecaoDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

        $objSecaoDocumentoRN = new SecaoDocumentoRN();
        $objSecaoDocumentoRN->excluir($objSecaoDocumentoRN->listar($objSecaoDocumentoDTO));
      }

      if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO || $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {
        $objDocumentoGeracaoDTO = new DocumentoGeracaoDTO();
        $objDocumentoGeracaoDTO->retDblIdDocumento();
        $objDocumentoGeracaoDTO->setNumMaxRegistrosRetorno(1);
        $objDocumentoGeracaoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objDocumentoGeracaoBD = new DocumentoGeracaoBD($this->getObjInfraIBanco());
        $objDocumentoGeracaoDTO = $objDocumentoGeracaoBD->consultar($objDocumentoGeracaoDTO);
        if ($objDocumentoGeracaoDTO!=null){
          $objDocumentoGeracaoBD->excluir($objDocumentoGeracaoDTO);
        }
      }

      if ($objDocumentoDTO->getStrStaDocumento() != DocumentoRN::$TD_EXTERNO) {
        $objDocumentoConteudoDTO = new DocumentoConteudoDTO();
        $objDocumentoConteudoDTO->retDblIdDocumento();
        $objDocumentoConteudoDTO->setNumMaxRegistrosRetorno(1);
        $objDocumentoConteudoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objDocumentoConteudoBD = new DocumentoConteudoBD($this->getObjInfraIBanco());
        $objDocumentoConteudoDTO = $objDocumentoConteudoBD->consultar($objDocumentoConteudoDTO);
        if ($objDocumentoConteudoDTO != null) {
          $objDocumentoConteudoBD->excluir($objDocumentoConteudoDTO);
        }
      }

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->excluir($parObjDocumentoDTO);

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloRN->excluirRN0748($objProtocoloDTO);
       
      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
      $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_EXCLUSAO_DOCUMENTO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

      $objAtividadeRN = new AtividadeRN();
      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);


      $objDocumentoDTOEscolha = new DocumentoDTO();
      $objDocumentoDTOEscolha->retDblIdDocumento();
      $objDocumentoDTOEscolha->setNumIdUnidadeGeradoraProtocolo(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objDocumentoDTOEscolha->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
      $objDocumentoDTOEscolha->setNumMaxRegistrosRetorno(1);

      if ($this->consultarRN0005($objDocumentoDTOEscolha) == null){
        $objSerieEscolhaDTO = new SerieEscolhaDTO();
        $objSerieEscolhaDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
        $objSerieEscolhaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
         
        $objSerieEscolhaRN = new SerieEscolhaRN();
        if ($objSerieEscolhaRN->contar($objSerieEscolhaDTO)==1){
          $objSerieEscolhaRN->excluir(array($objSerieEscolhaDTO));
        }
      }

      $objControleInternoDTO = new ControleInternoDTO();
      $objControleInternoDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
      $objControleInternoDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
      $objControleInternoDTO->setNumIdOrgao($objDocumentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
      $objControleInternoDTO->setStrStaOperacao(ControleInternoRN::$TO_EXCLUIR_DOCUMENTO);

      $objControleInternoRN = new ControleInternoRN();
      $objControleInternoRN->processar($objControleInternoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Documento.',$e);
    }
  }

  protected function darCienciaControlado(DocumentoDTO $parObjDocumentoDTO){
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('documento_ciencia',__METHOD__,$parObjDocumentoDTO);


      //Regras de Negocio
      $objInfraException = new InfraException();

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrSinBloqueado();

      if (count($SEI_MODULOS)) {
        $objDocumentoDTO->retNumIdSerie();
        $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
        $objDocumentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
        $objDocumentoDTO->retNumIdUsuarioGeradorProtocolo();
        $objDocumentoDTO->retStrStaNivelAcessoGlobalProtocolo();
      }

      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

      if ($objDocumentoDTO==null){
        $objInfraException->lancarValidacao('Documento não encontrado.');
      }

      $objInfraException->lancarValidacoes();

      
      $numVersaoCiencia = null;
      if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){
        if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){
          $objEditorRN = new EditorRN();
          $numVersaoCiencia = $objEditorRN->obterNumeroUltimaVersao($objDocumentoDTO);
        }else if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC){
          $numVersaoCiencia = 0;
        }else{
          $numVersaoCiencia = 0;
        }
      }else{
        $objAnexoDTO = new AnexoDTO();
        $objAnexoDTO->retNumIdAnexo();
        $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
        
        $objAnexoRN = new AnexoRN();
        $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);
        
        if ($objAnexoDTO!=null){
          $numVersaoCiencia = $objAnexoDTO->getNumIdAnexo();
        }else{
          $objInfraException->lancarValidacao('Documento não possui conteúdo para ciência.');
        }
      }

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->retNumIdAtividade();
      $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
      $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
      $objAtributoAndamentoDTO->setNumIdUsuarioOrigemAtividade(SessaoSEI::getInstance()->getNumIdUsuario());
      $objAtributoAndamentoDTO->setDblIdProtocoloAtividade($objDocumentoDTO->getDblIdProcedimento());
      $objAtributoAndamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_DOCUMENTO_CIENCIA);

      $objAtributoAndamentoRN = new AtributoAndamentoRN();
      $arrObjAtributoAndamentoDTO = $objAtributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);
      
      if (count($arrObjAtributoAndamentoDTO)){
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->retNumIdAtributoAndamento();
        $objAtributoAndamentoDTO->setStrNome('VERSAO_CIENCIA');
        $objAtributoAndamentoDTO->setStrValor($numVersaoCiencia);
        $objAtributoAndamentoDTO->setNumIdAtividade(InfraArray::converterArrInfraDTO($arrObjAtributoAndamentoDTO,'IdAtividade'),InfraDTO::$OPER_IN);
        $objAtributoAndamentoDTO->setNumMaxRegistrosRetorno(1);
        
        if ($objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO) != null){
          $objInfraException->lancarValidacao('Usuário já deu ciência neste documento.');
        }
      }
      
      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
      $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('VERSAO_CIENCIA');
      $objAtributoAndamentoDTO->setStrValor($numVersaoCiencia);
      $objAtributoAndamentoDTO->setStrIdOrigem(null);
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      
      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_DOCUMENTO_CIENCIA);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

      $objAtividadeRN = new AtividadeRN();
      $ret = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);


      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objDocumentoDTO->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objDocumentoDTO->getDblIdDocumento());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);
      
      $objRelProtocoloProtocoloDTO->setStrSinCiencia('S');
      $objRelProtocoloProtocoloRN->alterar($objRelProtocoloProtocoloDTO);

      $objProcedimentoDTOBanco = new ProcedimentoDTO();
      $objProcedimentoDTOBanco->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
      
      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->marcarCiencia($objProcedimentoDTOBanco);      
      
      $this->bloquearProcessado($objDocumentoDTO);

      if (count($SEI_MODULOS)) {

        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objDocumentoAPI->setIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
        $objDocumentoAPI->setNumeroProtocolo($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $objDocumentoAPI->setIdSerie($objDocumentoDTO->getNumIdSerie());
        $objDocumentoAPI->setIdUnidadeGeradora($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo());
        $objDocumentoAPI->setIdOrgaoUnidadeGeradora($objDocumentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
        $objDocumentoAPI->setIdUsuarioGerador($objDocumentoDTO->getNumIdUsuarioGeradorProtocolo());
        $objDocumentoAPI->setTipo($objDocumentoDTO->getStrStaProtocoloProtocolo());
        $objDocumentoAPI->setSubTipo($objDocumentoDTO->getStrStaDocumento());
        $objDocumentoAPI->setNivelAcesso($objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo());

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('darCienciaDocumento', $objDocumentoAPI);
        }
      }


      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro dando ciência no documento.',$e);
    }
  }

  protected function consultarRN0005Conectado(DocumentoDTO $objDocumentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('documento_consultar',__METHOD__,$objDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      if ($objDocumentoDTO->isRetObjPublicacaoDTO()){
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retStrStaProtocoloProtocolo();
      }

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $ret = $objDocumentoBD->consultar($objDocumentoDTO);

      if ($ret !== null){

        if ($objDocumentoDTO->isRetObjPublicacaoDTO()){
          if ($ret->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){

            $objPublicacaoDTO = new PublicacaoDTO();
            $objPublicacaoDTO->retNumIdPublicacao();
            $objPublicacaoDTO->retDblIdDocumento();
            $objPublicacaoDTO->retStrStaEstado();
            $objPublicacaoDTO->retNumIdVeiculoPublicacao();
            $objPublicacaoDTO->retStrStaTipoVeiculoPublicacao();
            $objPublicacaoDTO->retStrNomeVeiculoPublicacao();
            $objPublicacaoDTO->retDtaDisponibilizacao();
            $objPublicacaoDTO->retDtaPublicacao();
            $objPublicacaoDTO->retNumNumero();
            $objPublicacaoDTO->retNumIdVeiculoIO();
            $objPublicacaoDTO->retDtaPublicacaoIO();
            $objPublicacaoDTO->retStrPaginaIO();
            $objPublicacaoDTO->retStrSiglaVeiculoImprensaNacional();
            $objPublicacaoDTO->retStrNomeSecaoImprensaNacional();

            $objPublicacaoDTO->setDblIdDocumento($ret->getDblIdDocumento());

            $objPublicacaoRN = new PublicacaoRN();
            $objPublicacaoDTO = $objPublicacaoRN->consultarRN1044($objPublicacaoDTO);

            $ret->setObjPublicacaoDTO($objPublicacaoDTO);
          }
        }
      }

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Documento.',$e);
    }
  }

  protected function listarRN0008Conectado(DocumentoDTO $parObjDocumentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('documento_listar',__METHOD__,$parObjDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      if ($parObjDocumentoDTO->isRetObjPublicacaoDTO() || $parObjDocumentoDTO->isRetArrObjAssinaturaDTO() || $parObjDocumentoDTO->isRetObjArquivamentoDTO()){
        $parObjDocumentoDTO->retDblIdDocumento();
        $parObjDocumentoDTO->retStrStaProtocoloProtocolo();
      }

      if ($parObjDocumentoDTO->isRetStrSiglaUnidadeGeradoraProtocolo() || $parObjDocumentoDTO->isRetStrDescricaoUnidadeGeradoraProtocolo()){
        $parObjDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
        $parObjDocumentoDTO->retDtaInclusaoProtocolo();
        $parObjDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
        $parObjDocumentoDTO->retStrDescricaoUnidadeGeradoraProtocolo();
      }

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $arrObjDocumentoDTO = $objDocumentoBD->listar($parObjDocumentoDTO);

      if (count($arrObjDocumentoDTO)){
        if ($parObjDocumentoDTO->isRetObjPublicacaoDTO() || $parObjDocumentoDTO->isRetArrObjAssinaturaDTO() || $parObjDocumentoDTO->isRetObjArquivamentoDTO()){
  
          $arrIdDocumentosGerados = array();
          $arrIdDocumentosRecebidos = array();
          foreach($arrObjDocumentoDTO as $objDocumentoDTO){
            if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){
              $arrIdDocumentosGerados[] = $objDocumentoDTO->getDblIdDocumento();
            }else{
              $arrIdDocumentosRecebidos[] = $objDocumentoDTO->getDblIdDocumento();
            }
          }

          if (count($arrIdDocumentosGerados)) {

            if ($parObjDocumentoDTO->isRetObjPublicacaoDTO()) {

              $objPublicacaoDTO = new PublicacaoDTO();
              $objPublicacaoDTO->retDblIdDocumento();
              $objPublicacaoDTO->retNumIdPublicacao();
              $objPublicacaoDTO->retStrStaEstado();
              $objPublicacaoDTO->retNumIdVeiculoPublicacao();
              $objPublicacaoDTO->retStrStaTipoVeiculoPublicacao();
              $objPublicacaoDTO->retStrNomeVeiculoPublicacao();
              $objPublicacaoDTO->retDtaDisponibilizacao();
              $objPublicacaoDTO->retDtaPublicacao();
              $objPublicacaoDTO->retNumNumero();
              $objPublicacaoDTO->retNumIdVeiculoIO();
              $objPublicacaoDTO->retDtaPublicacaoIO();
              $objPublicacaoDTO->retStrPaginaIO();
              $objPublicacaoDTO->retStrSiglaVeiculoImprensaNacional();
              $objPublicacaoDTO->retStrNomeSecaoImprensaNacional();

              $objPublicacaoDTO->setDblIdDocumento($arrIdDocumentosGerados, InfraDTO::$OPER_IN);

              $objPublicacaoRN = new PublicacaoRN();
              $arrObjPublicacaoDTO = InfraArray::indexarArrInfraDTO($objPublicacaoRN->listarRN1045($objPublicacaoDTO), 'IdDocumento');

              foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
                if (isset($arrObjPublicacaoDTO[$objDocumentoDTO->getDblIdDocumento()])) {
                  $objDocumentoDTO->setObjPublicacaoDTO($arrObjPublicacaoDTO[$objDocumentoDTO->getDblIdDocumento()]);
                } else {
                  $objDocumentoDTO->setObjPublicacaoDTO(null);
                }
              }
            }

          }

          if (count($arrIdDocumentosRecebidos)) {

            if ($parObjDocumentoDTO->isRetObjArquivamentoDTO()) {

              $objArquivamentoDTO = new ArquivamentoDTO();
              $objArquivamentoDTO->retDblIdProtocolo();
              $objArquivamentoDTO->retStrStaArquivamento();
              $objArquivamentoDTO->retStrStaEliminacao();
              $objArquivamentoDTO->retNumIdLocalizador();
              $objArquivamentoDTO->retStrSiglaTipoLocalizador();
              $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
              $objArquivamentoDTO->retNumIdUnidadeLocalizador();
              $objArquivamentoDTO->setDblIdProtocolo($arrIdDocumentosRecebidos, InfraDTO::$OPER_IN);

              $objArquivamentoRN = new ArquivamentoRN();
              $arrObjArquivamentoDTO = InfraArray::indexarArrInfraDTO($objArquivamentoRN->listar($objArquivamentoDTO), 'IdProtocolo');

              foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
                if (isset($arrObjArquivamentoDTO[$objDocumentoDTO->getDblIdDocumento()])) {
                  $objDocumentoDTO->setObjArquivamentoDTO($arrObjArquivamentoDTO[$objDocumentoDTO->getDblIdDocumento()]);
                } else {
                  $objDocumentoDTO->setObjArquivamentoDTO(null);
                }
              }
            }
          }

          if ($parObjDocumentoDTO->isRetArrObjAssinaturaDTO()){
            $objAssinaturaDTO = new AssinaturaDTO();
            $objAssinaturaDTO->setBolExclusaoLogica(false);
            $objAssinaturaDTO->retDblIdDocumento();
            $objAssinaturaDTO->retNumIdUsuario();
            $objAssinaturaDTO->retNumIdUnidade();
            $objAssinaturaDTO->retStrNome();
            $objAssinaturaDTO->retStrTratamento();
            $objAssinaturaDTO->retStrSiglaUnidade();
            $objAssinaturaDTO->retNumIdAtividade();
            $objAssinaturaDTO->retStrSinAtivo();

            $objAssinaturaDTO->setDblIdDocumento(InfraArray::converterArrInfraDTO($arrObjDocumentoDTO,'IdDocumento'), InfraDTO::$OPER_IN);
            $objAssinaturaDTO->setNumIdAtividade(null, InfraDTO::$OPER_DIFERENTE);

            $objAssinaturaRN = new AssinaturaRN();
            $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);

            foreach($arrObjDocumentoDTO as $objDocumentoDTO){
              $arrTemp = array();
              foreach($arrObjAssinaturaDTO as $objAssinaturaDTO){
                if ($objDocumentoDTO->getDblIdDocumento()==$objAssinaturaDTO->getDblIdDocumento() &&
                    ($objAssinaturaDTO->getStrSinAtivo()=='S' || $objAssinaturaDTO->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual())){
                  $arrTemp[] = $objAssinaturaDTO;
                }
              }
              $objDocumentoDTO->setArrObjAssinaturaDTO($arrTemp);
            }
          }
        }

        if ($parObjDocumentoDTO->isRetStrSiglaUnidadeGeradoraProtocolo() || $parObjDocumentoDTO->isRetStrDescricaoUnidadeGeradoraProtocolo()){
          $objHistoricoRN = new HistoricoRN();
          $objHistoricoRN->aplicar('Unidade', $arrObjDocumentoDTO, 'InclusaoProtocolo', 'IdUnidadeGeradoraProtocolo', 'SiglaUnidadeGeradoraProtocolo', 'DescricaoUnidadeGeradoraProtocolo');
        }
      }
      //Auditoria

      return $arrObjDocumentoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando Documentos.',$e);
    }
  }

  protected function contarRN0007Conectado(DocumentoDTO $objDocumentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('documento_listar',__METHOD__,$objDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $ret = $objDocumentoBD->contar($objDocumentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Documentos.',$e);
    }
  }

  protected function bloquearControlado(DocumentoDTO $objDocumentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('documento_consultar',__METHOD__,$objDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $ret = $objDocumentoBD->bloquear($objDocumentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Documento.',$e);
    }
  }

  private function validarNivelAcesso(DocumentoDTO $objDocumentoDTO, ProcedimentoDTO $objProcedimentoDTO, InfraException $objInfraException)
  {

    $objProtocoloRN = new ProtocoloRN();
    $objProtocoloRN->validarStrStaNivelAcessoLocalRN0685($objDocumentoDTO->getObjProtocoloDTO(), $objInfraException);

    if ((int)$objDocumentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal() > (int)$objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo()) {
      $objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
      $objNivelAcessoPermitidoDTO->retNumIdNivelAcessoPermitido();
      $objNivelAcessoPermitidoDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
      $objNivelAcessoPermitidoDTO->setStrStaNivelAcesso($objDocumentoDTO->getObjProtocoloDTO()->getStrStaNivelAcessoLocal());
      $objNivelAcessoPermitidoDTO->setNumMaxRegistrosRetorno(1);

      $objNivelAcessoPermitidoRN = new NivelAcessoPermitidoRN();
      if ($objNivelAcessoPermitidoRN->consultar($objNivelAcessoPermitidoDTO) == null) {
        $objInfraException->adicionarValidacao('Nível de acesso não permitido para o tipo do processo '.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().'.');
      }
    }
  }

  private function validarStrStaDocumento(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getStrStaDocumento())){
      $objInfraException->adicionarValidacao('Tipo do documento não informado.');
    }
        
    if (//$objDocumentoDTO->getStrStaDocumento()!=DocumentoRN::$TD_EDITOR_EDOC &&
        $objDocumentoDTO->getStrStaDocumento()!=DocumentoRN::$TD_EDITOR_INTERNO &&
        $objDocumentoDTO->getStrStaDocumento()!=DocumentoRN::$TD_EXTERNO &&
        $objDocumentoDTO->getStrStaDocumento()!=DocumentoRN::$TD_FORMULARIO_AUTOMATICO &&
        $objDocumentoDTO->getStrStaDocumento()!=DocumentoRN::$TD_FORMULARIO_GERADO
    ){
      $objInfraException->adicionarValidacao('Tipo do documento ['.$objDocumentoDTO->getStrStaDocumento().'] inválido.');
    }
  }

  private function validarDblIdProcedimento(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getDblIdProcedimento())){
      $objInfraException->adicionarValidacao('Processo não informado.');
    }
  }

  private function validarNumIdUnidadeResponsavelRN0915(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getNumIdUnidadeResponsavel ())){
      $objInfraException->adicionarValidacao('Unidade Responsável não informada.');
    }
  }

  private function validarNumIdTipoFormulario(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getNumIdTipoFormulario ())){
      $objInfraException->adicionarValidacao('Tipo do formulário não informado.');
    }
  }

  private function validarStrCrcAssinatura(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getStrCrcAssinatura())){
      $objDocumentoDTO->setStrCrcAssinatura(null);
    }else{
      $objDocumentoDTO->setStrCrcAssinatura(strtoupper(trim($objDocumentoDTO->getStrCrcAssinatura())));
      if (strlen($objDocumentoDTO->getStrCrcAssinatura())>8){
        $objInfraException->lancarValidacao('Tamanho do código CRC inválido.');
      }
    }
  }

  private function validarStrCodigoVerificador(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getStrCodigoVerificador())){
      $objDocumentoDTO->setStrCodigoVerificador(null);
    }else{
      $objDocumentoDTO->setStrCodigoVerificador(strtoupper(trim($objDocumentoDTO->getStrCodigoVerificador())));
      if (!is_numeric($objDocumentoDTO->getStrCodigoVerificador())){
        $objInfraException->lancarValidacao('Código Verificador inválido.');
      }
    }
  }
  
  private function prepararCodigoVerificador($strCodigoVerificador){    
    if (strpos($strCodigoVerificador,'v') !== false){
      $arrCodigoVerificador = explode('v',$strCodigoVerificador);
      $strCodigoVerificador = $arrCodigoVerificador[0];
    }else if (strpos($strCodigoVerificador,'V') !== false){
      $arrCodigoVerificador = explode('V',$strCodigoVerificador);
      $strCodigoVerificador = $arrCodigoVerificador[0];
    }    
    return $strCodigoVerificador;
  }

  private function validarNumIdSerieRN0009(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){

    $objSerieDTO = null;

    if (InfraString::isBolVazia($objDocumentoDTO->getNumIdSerie())){
      $objInfraException->lancarValidacao('Tipo do documento não informado.');
    }else{

    	$objSerieDTO = new SerieDTO();
    	$objSerieDTO->setBolExclusaoLogica(false);
      $objSerieDTO->retNumIdSerie();
    	$objSerieDTO->retStrStaAplicabilidade();
      $objSerieDTO->retNumIdTipoFormulario();
    	$objSerieDTO->retStrNome();
      $objSerieDTO->retNumIdModelo();
      $objSerieDTO->retStrStaNumeracao();
      $objSerieDTO->retStrSinInteressado();
      $objSerieDTO->retStrSinDestinatario();
    	$objSerieDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());

    	$objSerieRN = new SerieRN();
    	$objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);

      if ($objSerieDTO==null){
        throw new InfraException('Tipo do documento ['.$objDocumentoDTO->getNumIdSerie().'] não encontrado.');
      }

      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO) {
        $strCache = 'SEI_TDR_'.$objDocumentoDTO->getNumIdSerie();
        $arrCache = CacheSEI::getInstance()->getAtributo($strCache);
        if ($arrCache == null) {
          $objSerieRestricaoDTO = new SerieRestricaoDTO();
          $objSerieRestricaoDTO->retNumIdOrgao();
          $objSerieRestricaoDTO->retNumIdUnidade();
          $objSerieRestricaoDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());

          $objSerieRestricaoRN = new SerieRestricaoRN();
          $arrObjSerieRestricaoDTO = $objSerieRestricaoRN->listar($objSerieRestricaoDTO);

          $arrCache = array();
          foreach ($arrObjSerieRestricaoDTO as $objSerieRestricaoDTO) {
            $arrCache[$objSerieRestricaoDTO->getNumIdOrgao()][($objSerieRestricaoDTO->getNumIdUnidade() == null ? '*' : $objSerieRestricaoDTO->getNumIdUnidade())] = 0;
          }
          CacheSEI::getInstance()->setAtributo($strCache, $arrCache, CacheSEI::getInstance()->getNumTempo());
        }

        if (InfraArray::contar($arrCache) && !isset($arrCache[SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()]['*']) && !isset($arrCache[SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()][SessaoSEI::getInstance()->getNumIdUnidadeAtual()])){
          $objInfraException->lancarValidacao('Tipo de documento "' . $objSerieDTO->getStrNome() . '" não está liberado para a unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '/' . SessaoSEI::getInstance()->getStrSiglaOrgaoUnidadeAtual() . '.');
        }
      }

      switch($objDocumentoDTO->getStrStaDocumento()){

        case DocumentoRN::$TD_EDITOR_INTERNO:
          if ($objSerieDTO->getStrStaAplicabilidade()!=SerieRN::$TA_INTERNO_EXTERNO && $objSerieDTO->getStrStaAplicabilidade()!=SerieRN::$TA_INTERNO){
            $objInfraException->adicionarValidacao('Tipo de documento "'.$objSerieDTO->getStrNome().'" não aplicável para documentos internos.');
          }

          if ($objSerieDTO->getNumIdModelo()==null){
            $objInfraException->adicionarValidacao('Tipo de documento "'.$objSerieDTO->getStrNome().'" não possui modelo associado.');
          }
          break;

        case DocumentoRN::$TD_EXTERNO:
          if ($objSerieDTO->getStrStaAplicabilidade()!=SerieRN::$TA_INTERNO_EXTERNO && $objSerieDTO->getStrStaAplicabilidade()!=SerieRN::$TA_EXTERNO){
            $objInfraException->adicionarValidacao('Tipo de documento "'.$objSerieDTO->getStrNome().'" não aplicável para documentos externos.');
          }
          break;

        case DocumentoRN::$TD_FORMULARIO_GERADO:
          if ($objSerieDTO->getStrStaAplicabilidade()!=SerieRN::$TA_FORMULARIO){
            $objInfraException->adicionarValidacao('Tipo de documento "'.$objSerieDTO->getStrNome().'" não aplicável para formulários.');
          }

          if ($objSerieDTO->getNumIdTipoFormulario()==null){
            $objInfraException->adicionarValidacao('Tipo de documento "'.$objSerieDTO->getStrNome().'" não possui modelo de formulário associado.');
          }
          break;

        case DocumentoRN::$TD_FORMULARIO_AUTOMATICO:
          //pode usar qualquer tipo de documento
          break;

        default:
          throw new InfraException('Sinalizador interno do documento inválido.');
      }
    }
    return $objSerieDTO;
  }

  private function validarTamanhoNumeroRN0993(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getStrNumero())){
      $objDocumentoDTO->setStrNumero(null);
    }else {
      $objDocumentoDTO->setStrNumero(trim($objDocumentoDTO->getStrNumero()));
      if (strlen($objDocumentoDTO->getStrNumero()) > 50) {
        $objInfraException->adicionarValidacao('Número possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrNomeArvore(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getStrNomeArvore())){
      $objDocumentoDTO->setStrNomeArvore(null);
    }else {
      $objDocumentoDTO->setStrNomeArvore(trim($objDocumentoDTO->getStrNomeArvore()));
      if (strlen($objDocumentoDTO->getStrNomeArvore()) > 50) {
        $objInfraException->adicionarValidacao('Nome na Árvore possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarDinValor(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getDinValor())){
      $objDocumentoDTO->setDinValor(null);
    }else{

      $objDocumentoDTO->setDinValor(trim($objDocumentoDTO->getDinValor()));

      if (!InfraUtil::validarDin($objDocumentoDTO->getDinValor())){
        $objInfraException->lancarValidacao('Valor monetário inválido.');
      }

      if (InfraUtil::prepararDin($objDocumentoDTO->getDinValor()) > 9999999999999.99){
        $objInfraException->lancarValidacao('Valor monetário excede o limite.');
      }
    }
  }

  private function validarStrConteudo(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getStrConteudo())){
      $objDocumentoDTO->setStrConteudo(null);
    }
  }

  private function validarNumIdTipoConferencia(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objDocumentoDTO->getNumIdTipoConferencia())){

      $objDocumentoDTO->setNumIdTipoConferencia(null);

    }else {

      if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO) {

        $objTipoConferenciaDTO = new TipoConferenciaDTO();
        $objTipoConferenciaDTO->setBolExclusaoLogica(false);
        $objTipoConferenciaDTO->retNumIdTipoConferencia();
        $objTipoConferenciaDTO->setNumIdTipoConferencia($objDocumentoDTO->getNumIdTipoConferencia());

        $objTipoConferenciaRN = new TipoConferenciaRN();
        if ($objTipoConferenciaRN->consultar($objTipoConferenciaDTO) == null) {
          $objInfraException->adicionarValidacao('Tipo de Conferência não encontrada.');
        }

      } else {
        $objInfraException->adicionarValidacao('Tipo de Conferência não aplicável ao documento.');
      }
    }
  }

  private function validarStrSinArquivamento(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDocumentoDTO->getStrSinArquivamento())){
      $objInfraException->adicionarValidacao('Sinalizador de Arquivamento não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objDocumentoDTO->getStrSinArquivamento())){
        $objInfraException->adicionarValidacao('Sinalizador de Arquivamento inválido.');
      }

      if ($objDocumentoDTO->getStrSinArquivamento()=='S' && $objDocumentoDTO->getStrStaDocumento()!=self::$TD_EXTERNO){
        $objInfraException->adicionarValidacao('Sinalizador de arquivamento permitido apenas para documentos externos.');
      }

      if ($objDocumentoDTO->getStrSinArquivamento()=='S' && $objDocumentoDTO->getNumIdTipoConferencia()==null){

        $bolExisteArquivamento = false;
        if ($objDocumentoDTO->getDblIdDocumento()!=null) {
          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->retDblIdProtocolo();
          $objArquivamentoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

          $objArquivamentoRN = new ArquivamentoRN();
          $bolExisteArquivamento = ($objArquivamentoRN->consultar($objArquivamentoDTO) != null);
        }

        if (!$bolExisteArquivamento) {
          $objInfraException->adicionarValidacao('Sinalizador de arquivamento permitido apenas para documentos digitalizados.');
        }
      }
    }
  }

  protected function prepararCloneRN1110Conectado(DocumentoDTO $objDocumentoDTO){
    try{
      
      //Recuperar os dados do documento para clonagem
      $objDocumentoCloneDTO = new DocumentoDTO();
      $objDocumentoCloneDTO->retNumIdUnidadeResponsavel();
      $objDocumentoCloneDTO->retDblIdDocumentoEdoc();
      $objDocumentoCloneDTO->retNumIdSerie();
      $objDocumentoCloneDTO->retNumIdTipoConferencia();
      $objDocumentoCloneDTO->retStrSinArquivamento();
      $objDocumentoCloneDTO->retStrNumero();
      $objDocumentoCloneDTO->retStrNomeArvore();
      $objDocumentoCloneDTO->retStrDescricaoProtocolo();
      $objDocumentoCloneDTO->retStrConteudo();
      $objDocumentoCloneDTO->retStrStaProtocoloProtocolo();
      $objDocumentoCloneDTO->retDtaGeracaoProtocolo();
      $objDocumentoCloneDTO->retStrStaDocumento();
      $objDocumentoCloneDTO->retStrStaNivelAcessoLocalProtocolo();
      $objDocumentoCloneDTO->retNumIdHipoteseLegalProtocolo();

      $objDocumentoCloneDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $objDocumentoCloneDTO = $this->consultarRN0005($objDocumentoCloneDTO);

      $objDocumentoCloneDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());

      $objDocumentoCloneDTO->setNumIdUnidadeResponsavel(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      if ($objDocumentoCloneDTO->getStrSinArquivamento()=='S' && $objDocumentoCloneDTO->getNumIdTipoConferencia()==null){
        $objDocumentoCloneDTO->setStrSinArquivamento('N');
      }

      if ($objDocumentoCloneDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO){
        
        $objSerieDTO = new SerieDTO();
        $objSerieDTO->setBolExclusaoLogica(false);
        $objSerieDTO->retStrStaNumeracao();
        $objSerieDTO->setNumIdSerie($objDocumentoCloneDTO->getNumIdSerie());
        
        $objSerieRN = new SerieRN();
        $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);
        
        if ($objSerieDTO->getStrStaNumeracao()==SerieRN::$TN_SEQUENCIAL_ANUAL_ORGAO ||
            $objSerieDTO->getStrStaNumeracao()==SerieRN::$TN_SEQUENCIAL_ANUAL_UNIDADE ||
            $objSerieDTO->getStrStaNumeracao()==SerieRN::$TN_SEQUENCIAL_ORGAO ||
            $objSerieDTO->getStrStaNumeracao()==SerieRN::$TN_SEQUENCIAL_UNIDADE){
          $objDocumentoCloneDTO->setStrNumero(null);
        }
      }
      
      
      $objDocumentoCloneDTO->setDblIdDocumentoEdocBase(null);
      $objDocumentoCloneDTO->setDblIdDocumentoBase(null);
      
      //usa o documento original como base
      if ($objDocumentoCloneDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO){
        if ($objDocumentoCloneDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC){
          $objDocumentoCloneDTO->setDblIdDocumentoEdocBase($objDocumentoCloneDTO->getDblIdDocumentoEdoc());
          $objDocumentoCloneDTO->setStrStaDocumento(DocumentoRN::$TD_EDITOR_INTERNO);
        }else if ($objDocumentoCloneDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){
          $objDocumentoCloneDTO->setDblIdDocumentoBase($objDocumentoDTO->getDblIdDocumento());
        }
      }else{
        $objDocumentoCloneDTO->setDblIdDocumentoEdoc(null);
      }

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());
      $objProtocoloDTO->setStrDescricao($objDocumentoCloneDTO->getStrDescricaoProtocolo());
      $objProtocoloDTO->setStrStaNivelAcessoLocal($objDocumentoCloneDTO->getStrStaNivelAcessoLocalProtocolo());

      if ($objDocumentoCloneDTO->getStrStaNivelAcessoLocalProtocolo()!=ProtocoloRN::$NA_PUBLICO) {
        $objProtocoloDTO->setNumIdHipoteseLegal($objDocumentoCloneDTO->getNumIdHipoteseLegalProtocolo());
      }

      //Recuperar em ArrAssuntos os assuntos
      $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
      $objRelProtocoloAssuntoDTO->setDistinct(true);
      $objRelProtocoloAssuntoDTO->retNumIdAssunto();
      $objRelProtocoloAssuntoDTO->retNumSequencia();
      $objRelProtocoloAssuntoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
      $arrAssuntos = $objRelProtocoloAssuntoRN->listarRN0188($objRelProtocoloAssuntoDTO);
      $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO($arrAssuntos);

      //Recuperar em ArrParticipantes os participantes
      $objPartipantesDTO = new ParticipanteDTO();
      $objPartipantesDTO->retNumIdContato();
      $objPartipantesDTO->retStrStaParticipacao();
      $objPartipantesDTO->retNumSequencia();
      $objPartipantesDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      $objPartipantesRN = new ParticipanteRN();
      $arrParticipantes = $objPartipantesRN->listarRN0189($objPartipantesDTO);
      $objProtocoloDTO->setArrObjParticipanteDTO($arrParticipantes);

      $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
      $objRelProtocoloAtributoDTO->retNumIdAtributo();
      $objRelProtocoloAtributoDTO->retStrValor();
      $objRelProtocoloAtributoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
      $arrAtributos = $objRelProtocoloAtributoRN->listar($objRelProtocoloAtributoDTO);

      $objProtocoloDTO->setArrObjRelProtocoloAtributoDTO($arrAtributos);

      //Recuperar em ArrObservacoes as observacoes desta unidade
      $objObservacaoDTO = new ObservacaoDTO();
      $objObservacaoDTO->retStrDescricao();

      $objObservacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objObservacaoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      $objObservacaoRN = new ObservacaoRN();
      $arrObservacoes = $objObservacaoRN->listarRN0219($objObservacaoDTO);

      $objProtocoloDTO->setArrObjObservacaoDTO($arrObservacoes);

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->retStrNome();
      $objAnexoDTO->retNumTamanho();
      $objAnexoDTO->retDthInclusao();
      $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      $objAnexoRN = new AnexoRN();
      $arrObjAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);

      foreach($arrObjAnexoDTO as $objAnexoDTO){

        $strNomeUpload = $objAnexoRN->gerarNomeArquivoTemporario();

        $strNomeUploadCompleto = DIR_SEI_TEMP.'/'.$strNomeUpload;
        copy($objAnexoRN->obterLocalizacao($objAnexoDTO), $strNomeUploadCompleto);

        $objAnexoDTO->setNumIdAnexo($strNomeUpload);
        $objAnexoDTO->setDthInclusao(InfraData::getStrDataHoraAtual());
        $objAnexoDTO->setStrSinDuplicando('S');
      }

      $objProtocoloDTO->setArrObjAnexoDTO($arrObjAnexoDTO);

      $objDocumentoCloneDTO->setObjProtocoloDTO($objProtocoloDTO);

      $objDocumentoCloneDTO->setDblIdDocumento(null);

      return $objDocumentoCloneDTO;

    }catch(Exception $e){
      throw new InfraException('Erro preparando clone do documento.',$e);
    }
  }

  protected function agruparRN1116Controlado(ProtocoloDTO $objProtocoloRecebidoDTO) {
    try{

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //Obter dados da publicação através da Publicacao
      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProtocolo($objProtocoloRecebidoDTO->getDblIdProtocolo());
      $objProtocoloDTO->setDblIdProtocoloAgrupador($objProtocoloRecebidoDTO->getDblIdProtocoloAgrupador());

      $objProtocoloBD = new ProtocoloBD($this->getObjInfraIBanco());
      $objProtocoloBD->alterar($objProtocoloDTO);


    }catch(Exception $e){
      throw new InfraException('Erro agrupando Protocolo.',$e);
    }
  }

  private function tratarProtocoloRN1164(DocumentoDTO $objDocumentoDTO) {
    try{

      $objProtocoloDTO = $objDocumentoDTO->getObjProtocoloDTO();

      $objProtocoloDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());

      $objProtocoloDTO->setStrProtocoloFormatado(null);

      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EXTERNO) {
        $objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_DOCUMENTO_RECEBIDO);
      }else{
        $objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_DOCUMENTO_GERADO);
      }

      if (!$objProtocoloDTO->isSetNumIdUnidadeGeradora()){
        $objProtocoloDTO->setNumIdUnidadeGeradora(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      }

      if (!$objProtocoloDTO->isSetNumIdUsuarioGerador()){
        $objProtocoloDTO->setNumIdUsuarioGerador(SessaoSEI::getInstance()->getNumIdUsuario());
      }

      if (!$objProtocoloDTO->isSetDtaGeracao()){
        $objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());
      }

      if (!$objProtocoloDTO->isSetArrObjRelProtocoloAssuntoDTO()){
        $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO(array());
      }

      $objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);

    }catch(Exception $e){
      throw new InfraException('Erro tratando protocolo do documento.',$e);
    }
  }

  protected function gerarPublicacaoRelacionadaRN1207Controlado(DocumentoDTO $parObjDocumentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_gerar_relacionada',__METHOD__,$parObjDocumentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retObjPublicacaoDTO();
      $objDocumentoDTO->retDtaGeracaoProtocolo();
      $objDocumentoDTO->retStrNumero();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

      $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

      $objPublicacaoDTO = $objDocumentoDTO->getObjPublicacaoDTO();

      if ($objPublicacaoDTO==null){
        $objInfraException->lancarValidacao('Documento não foi publicado.');
      }

      if ($objPublicacaoDTO->getStrStaEstado()==PublicacaoRN::$TE_AGENDADO){
        $objInfraException->lancarValidacao('Documento ainda não foi publicado.');
      }

      //Clonar o documento
      $objDocumentoClonarDTO = new DocumentoDTO();
      $objDocumentoClonarDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $objDocumentoClonarDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
      $objDocumentoClonarDTO = $this->prepararCloneRN1110($objDocumentoClonarDTO);

      if ($objDocumentoDTO->getStrNumero()!=null && $objDocumentoClonarDTO->getStrNumero()==null){
        $objDocumentoClonarDTO->setStrNumero($objDocumentoDTO->getStrNumero());
      }

      $parObjDocumentoDTO->getObjProtocoloDTO()->setDtaGeracao($objDocumentoDTO->getDtaGeracaoProtocolo());
      $objDocumentoClonarDTO->setObjProtocoloDTO($parObjDocumentoDTO->getObjProtocoloDTO());

      $ret = $this->cadastrarRN0003($objDocumentoClonarDTO);

      //Recuperar o protocolo agrupador
      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retDblIdProtocoloAgrupador();
      $objProtocoloDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

      // Agrupar os protocolos
      $dto = new ProtocoloDTO();
      $dto->setDblIdProtocolo($ret->getDblIdDocumento());
      $dto->setDblIdProtocoloAgrupador($objProtocoloDTO->getDblIdProtocoloAgrupador());
      $this->agruparRN1116($dto);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro gerando publicação relacionada.',$e);
    }
  }

  public function atualizarConteudoRN1205(DocumentoDTO $objDocumentoDTO){

    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $this->atualizarConteudoRN1205Interno($objDocumentoDTO);

    if ($objDocumentoDTO->isSetDblIdDocumento()){

      $objIndexacaoDTO = new IndexacaoDTO();
      $objIndexacaoDTO->setArrIdProtocolos(array($objDocumentoDTO->getDblIdDocumento()));
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_METADADOS_E_CONTEUDO);

      $objIndexacaoRN = new IndexacaoRN();
      $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);
    }

    if (!$bolAcumulacaoPrevia){
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }
  }

  protected function configurarEstilosControlado(DocumentoDTO $parObjDocumentoDTO){
    try {

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTO->setNumIdConjuntoEstilos($parObjDocumentoDTO->getNumIdConjuntoEstilos());

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->alterar($objDocumentoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro configurando estilos do documento.',$e);
    }
  }

  protected function bloquearConteudoControlado(DocumentoDTO $parObjDocumentoDTO){
    try {

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTO->setStrSinBloqueado('S');

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->alterar($objDocumentoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro bloqueando documento.',$e);
    }
  }

  protected function desbloquearConteudoControlado(DocumentoDTO $parObjDocumentoDTO){
    try {

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTO->setStrSinBloqueado('N');

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->alterar($objDocumentoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro desbloqueando documento.',$e);
    }
  }

  protected function bloquearConsultadoConectado(DocumentoDTO $parObjDocumentoDTO){
    try {

      $bolBloquear = false;

  		if ($parObjDocumentoDTO->getStrSinBloqueado()=='N' && SessaoSEI::getInstance()->getNumIdUnidadeAtual()!=$parObjDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()){

        if ($parObjDocumentoDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO) {

          if ($parObjDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_AUTOMATICO){

            //formulários automáticos
            $bolBloquear = true;

          }else{

            $objAssinaturaDTO = new AssinaturaDTO();
            $objAssinaturaDTO->setNumMaxRegistrosRetorno(1);
            $objAssinaturaDTO->retNumIdAssinatura();
            $objAssinaturaDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

            $objAssinaturaRN = new AssinaturaRN();

            if ($objAssinaturaRN->consultarRN1322($objAssinaturaDTO) != null) {

              //somente bloquear se o documento não foi disponibilizado em bloco para a unidade
              $objBlocoDTO = new BlocoDTO();
              $objBlocoDTO->setNumMaxRegistrosRetorno(1);
              $objBlocoDTO->retNumIdBloco();
              $objBlocoDTO->setStrStaTipo(BlocoRN::$TB_ASSINATURA);
              $objBlocoDTO->setStrStaEstado(BlocoRN::$TE_DISPONIBILIZADO);
              $objBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), InfraDTO::$OPER_DIFERENTE);
              $objBlocoDTO->setNumIdUnidadeRelBlocoUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
              $objBlocoDTO->setStrSinRetornadoRelBlocoUnidade('N');
              $objBlocoDTO->setDblIdProtocoloRelBlocoProtocolo($parObjDocumentoDTO->getDblIdDocumento());

              $objBlocoRN = new BlocoRN();

              if ($objBlocoRN->consultarRN1276($objBlocoDTO) == null) {
                $bolBloquear = true;
              }

            }else{

              $bolAcesso = true;

              if (SessaoSEI::getInstance()->isBolHabilitada()){

                $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
                $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
                $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
                $objPesquisaProtocoloDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

                $objProtocoloRN = new ProtocoloRN();
                $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

                if (count($arrObjProtocoloDTO) == 0) {
                  $bolAcesso = false;
                }
              }

              if (!$bolAcesso){

                if (!$parObjDocumentoDTO->isSetStrProtocoloDocumentoFormatado()){
                  $objProtocoloDTO = new ProtocoloDTO();
                  $objProtocoloDTO->retStrProtocoloFormatado();
                  $objProtocoloDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

                  $objProtocoloRN = new ProtocoloRN();
                  $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
                  $parObjDocumentoDTO->setStrProtocoloDocumentoFormatado($objProtocoloDTO->getStrProtocoloFormatado());
                }

                $objInfraException = new InfraException();
                $objInfraException->lancarValidacao('Documento '.$parObjDocumentoDTO->getStrProtocoloDocumentoFormatado().' não está mais disponível.');
              }
            }
          }
        }else if ($parObjDocumentoDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {

          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO->retNumIdAnexo();
          $objAnexoDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());
          $objAnexoDTO->setNumMaxRegistrosRetorno(1);

          $objAnexoRN = new AnexoRN();
          if ($objAnexoRN->consultarRN0736($objAnexoDTO) != null) {

            //externo com conteúdo
            $bolBloquear = true;
          }
        }

        if ($bolBloquear) {
          $this->bloquearConteudo($parObjDocumentoDTO);
        }
 		  }
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro bloqueando documento por consulta.',$e);
    }
  }

  protected function bloquearProcessadoConectado(DocumentoDTO $parObjDocumentoDTO){
    try {

      $bolBloquear = false;

      if ($parObjDocumentoDTO->getStrSinBloqueado()=='N') {

        if ($parObjDocumentoDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO) {

          if ($parObjDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {

            //formulários automáticos
            $bolBloquear = true;

          } else {
            $objAssinaturaDTO = new AssinaturaDTO();
            $objAssinaturaDTO->retNumIdAssinatura();
            $objAssinaturaDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
            $objAssinaturaDTO->setNumMaxRegistrosRetorno(1);

            $objAssinaturaRN = new AssinaturaRN();
            if ($objAssinaturaRN->consultarRN1322($objAssinaturaDTO) != null) {

              //gerado com assinatura
              $bolBloquear = true;
            }
          }
        } else if ($parObjDocumentoDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {

          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO->retNumIdAnexo();
          $objAnexoDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());
          $objAnexoDTO->setNumMaxRegistrosRetorno(1);

          $objAnexoRN = new AnexoRN();
          if ($objAnexoRN->consultarRN0736($objAnexoDTO) != null) {

            //externo com conteúdo
            $bolBloquear = true;
          }
        }

        if ($bolBloquear) {
          $this->bloquearConteudo($parObjDocumentoDTO);
        }
      }
        //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro bloqueando documento por processamento.',$e);
    }
  }

  protected function bloquearTramitacaoConclusaoConectado(ProcedimentoDTO $objProcedimentoDTO){
    try {

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrSinBloqueado();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->setNumIdUnidadeGeradoraProtocolo(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objDocumentoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objDocumentoDTO->setStrSinBloqueado('N');

      $arrObjDocumentoDTO = $this->listarRN0008($objDocumentoDTO);

      foreach($arrObjDocumentoDTO as $objDocumentoDTO){
        $this->bloquearProcessado($objDocumentoDTO);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro bloqueando documento por tramitação ou conclusão do processo.',$e);
    }
  }

  protected function bloquearPublicadoConectado(DocumentoDTO $parObjDocumentoDTO){
    try {
      //consulta o documento que está sendo publicado
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrSinBloqueado();
      $objDocumentoDTO->retNumIdSerie();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

      //retorna o parametro que indica que o tipo de documento é um edital de eliminacao
      $objInfraParametro = new InfraParametro($this->getObjInfraIBanco());
      //se o documento é um edital de eliminacao
      if($objDocumentoDTO->getNumIdSerie() == $objInfraParametro->getValor('ID_SERIE_EDITAL_ELIMINACAO')){
        //seta o dto para consultar o edital de eliminacao com base no documento que está sendo publicado
        $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
        $objEditalEliminacaoDTO->retNumIdEditalEliminacao();
        $objEditalEliminacaoDTO->retStrStaEditalEliminacao();
        $objEditalEliminacaoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
        //consulta
        $objEditalEliminacaoRN = new EditalEliminacaoRN();
        $objEditalEliminacaoDTO = $objEditalEliminacaoRN->consultar($objEditalEliminacaoDTO);
        //se encontrou o edital
        if ($objEditalEliminacaoDTO!=null) {
          //consulta a publicacao, para buscar a sua data de publicacao
          $objPublicacaoDTO = new PublicacaoDTO();
          $objPublicacaoDTO->retDtaPublicacao();
          $objPublicacaoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
          //consulta
          $objPublicacaoRN = new PublicacaoRN();
          $objPublicacaoDTO = $objPublicacaoRN->consultarRN1044($objPublicacaoDTO);
          //seta a data de publucacao do edital, com base na data de publicacao do documento
          $objEditalEliminacaoDTO->setDtaPublicacao($objPublicacaoDTO->getDtaPublicacao());
          $objEditalEliminacaoDTO->setStrStaEditalEliminacao(EditalEliminacaoRN::$TE_PUBLICADO);
          $objEditalEliminacaoRN->alterar($objEditalEliminacaoDTO);

          //busca o conteudo (os processos) do edital, para lancar os andamentos
          $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
          $objEditalEliminacaoConteudoDTO->retDblIdProcedimentoAvaliacaoDocumental();
          $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($objEditalEliminacaoDTO->getNumIdEditalEliminacao());
          //lista
          $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
          $arrObjEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->listar($objEditalEliminacaoConteudoDTO);

          $objProcedimentoRN = new ProcedimentoRN();
          //array com processos para lancar andamentos
          $arrObjProcessos = array();
          //itera pelos processos
          foreach ($arrObjEditalEliminacaoConteudoDTO as $objEditalEliminacaoConteudoDTO_Banco){
            //dto do processo
            $objProcedimentoDTO = new ProcedimentoDTO();
            $objProcedimentoDTO->setDblIdProcedimento($objEditalEliminacaoConteudoDTO_Banco->getDblIdProcedimentoAvaliacaoDocumental());
            $arrObjProcessos[] = $objProcedimentoDTO;
            //busca os processos anexados (se existirem), para lancar os andamentos neles tambem
            $arrObjProcedimentosDTO_Anexados = $objProcedimentoRN->listarProcessosAnexados($objProcedimentoDTO);
            if(InfraArray::contar($arrObjProcedimentosDTO_Anexados) > 0){
              $arrObjProcessos = array_merge($arrObjProcessos, $arrObjProcedimentosDTO_Anexados);
            }
          }
          $this->lancarAndamentoPublicacaoEditalEliminacao($objDocumentoDTO, $arrObjProcessos);
        }
      }

      if ($objDocumentoDTO->getStrSinBloqueado()=='N'){
        $this->bloquearConteudo($parObjDocumentoDTO);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro bloqueando documento por publicação.',$e);
    }
  }

  private function lancarAndamentoPublicacaoEditalEliminacao(DocumentoDTO $objDocumentoDTO, array $arrObjProcedimentoDTO){
    $objAtividadeRN = new AtividadeRN();
    foreach($arrObjProcedimentoDTO as $objProcedimentoDTO) {

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
      $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_INCLUSAO_EDITAL_ELIMINACAO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
    }
  }

  protected function atualizarConteudoRN1205InternoControlado(DocumentoDTO $parObjDocumentoDTO){
    try {

      global $SEI_MODULOS;

      //Regras de Negocio
      $objInfraException = new InfraException();

      //Edoc
      if ($parObjDocumentoDTO->isSetDblIdDocumentoEdoc()){

        $objInfraException->lancarValidacao('A atualização de conteúdo para documentos do e-Doc não está mais disponível.');

      }else{

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retStrStaDocumento();
        $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

        $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

        if ($objDocumentoDTO == null){
          $objInfraException->lancarValidacao('Documento não encontrado para atualização do conteúdo.');
        }

        if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_GERADO){
          //gera conteudo com base nos atributos recebidos
          $parObjDocumentoDTO->setStrConteudo(self::montarConteudoFormulario($parObjDocumentoDTO->getObjProtocoloDTO()->getArrObjRelProtocoloAtributoDTO()));
        }

      }

      $this->validarDocumentoPublicadoRN1211($parObjDocumentoDTO);

      $this->cancelarAssinatura($parObjDocumentoDTO);

      $objDocumentoAPI = new DocumentoAPI();
      $objDocumentoAPI->setIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      
      foreach($SEI_MODULOS as $seiModulo){
        $seiModulo->executar('atualizarConteudoDocumento', $objDocumentoAPI);
      }

      //criar novas instancias para evitar modificar outros dados
      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_GERADO){
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());
        $objProtocoloDTO->setArrObjRelProtocoloAtributoDTO($parObjDocumentoDTO->getObjProtocoloDTO()->getArrObjRelProtocoloAtributoDTO());

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloRN->alterarRN0203($objProtocoloDTO);
      }

      $objDocumentoConteudoDTO = new DocumentoConteudoDTO();
      $objDocumentoConteudoDTO->setStrConteudo($parObjDocumentoDTO->getStrConteudo());
      $objDocumentoConteudoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

      $objDocumentoConteudoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoConteudoBD->alterar($objDocumentoConteudoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro atualizando conteúdo do documento.',$e);
    }
  }

  public function assinar(AssinaturaDTO $objAssinaturaDTO){

    $arrObjAssinaturaDTO = $this->assinarInterno($objAssinaturaDTO);

    if ($objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_SENHA || $objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_MODULO){

      $objIndexacaoDTO = new IndexacaoDTO();
      $objIndexacaoDTO->setArrIdProtocolos(InfraArray::converterArrInfraDTO($objAssinaturaDTO->getArrObjDocumentoDTO(),'IdDocumento'));
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_ASSINATURA);

      $objIndexacaoRN = new IndexacaoRN();
      $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);
    }

    return $arrObjAssinaturaDTO;
  }

  protected function assinarInternoControlado(AssinaturaDTO $objAssinaturaDTO) {
    try{

      global $SEI_MODULOS;
      
      //Valida Permissao
      $objAssinaturaDTOAuditoria = clone($objAssinaturaDTO);
      $objAssinaturaDTOAuditoria->unSetStrSenhaUsuario();

      SessaoSEI::getInstance()->validarAuditarPermissao('documento_assinar',__METHOD__,$objAssinaturaDTOAuditoria);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

      $objUsuarioDTOPesquisa = new UsuarioDTO();
      $objUsuarioDTOPesquisa->setBolExclusaoLogica(false);
      $objUsuarioDTOPesquisa->retNumIdUsuario();
      $objUsuarioDTOPesquisa->retStrSigla();
      $objUsuarioDTOPesquisa->retStrNomeRegistroCivil();
      $objUsuarioDTOPesquisa->retStrNomeSocial();
      $objUsuarioDTOPesquisa->retDblCpfContato();
      $objUsuarioDTOPesquisa->retStrStaTipo();
      $objUsuarioDTOPesquisa->retStrSenha();
      $objUsuarioDTOPesquisa->retNumIdContato();
      $objUsuarioDTOPesquisa->setNumIdUsuario($objAssinaturaDTO->getNumIdUsuario());

      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTOPesquisa);

      if ($objUsuarioDTO==null){
        throw new InfraException('Assinante não cadastrado como usuário do sistema.');
      }

      if ($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_EXTERNO_PENDENTE){
        $objInfraException->lancarValidacao('Usuário externo '.$objUsuarioDTO->getStrSigla().' não foi liberado.');
      }

      if ($objUsuarioDTO->getStrStaTipo()!=UsuarioRN::$TU_SIP && $objUsuarioDTO->getStrStaTipo()!=UsuarioRN::$TU_EXTERNO){
        throw new InfraException('Tipo do usuário ['.$objUsuarioDTO->getStrStaTipo().'] inválido para assinatura.');
      }

      $objUsuarioDTO->setStrNome(SeiINT::formatarNomeSocial($objUsuarioDTO->getStrNomeRegistroCivil(), $objUsuarioDTO->getStrNomeSocial()));

      if ($objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_CERTIFICADO_DIGITAL &&
          InfraString::isBolVazia($objUsuarioDTO->getDblCpfContato()) &&
          $objInfraParametro->getValor('SEI_HABILITAR_VALIDACAO_CPF_CERTIFICADO_DIGITAL')=='1'){
        $objInfraException->lancarValidacao('Assinante não possui CPF cadastrado.');
      }

      if (InfraString::isBolVazia($objAssinaturaDTO->getStrCargoFuncao())){
        $objInfraException->lancarValidacao('Cargo/Função não informado.');
      }

      if (!in_array($objAssinaturaDTO->getStrCargoFuncao(), InfraArray::converterArrInfraDTO($objUsuarioRN->listarCargoFuncao($objUsuarioDTO),'CargoFuncao'))){
        $objInfraException->lancarValidacao('Cargo/Função "'.$objAssinaturaDTO->getStrCargoFuncao().'" não está associado com este usuário.');
      }

      if (SessaoSEI::getInstance()->getNumIdUsuario()==$objAssinaturaDTO->getNumIdUsuario()){
        $objUsuarioDTOLogado = clone($objUsuarioDTO);
      }else{
        $objUsuarioDTOPesquisa->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objUsuarioDTOLogado = $objUsuarioRN->consultarRN0489($objUsuarioDTOPesquisa);
      }

      $objAssinaturaRN = new AssinaturaRN();
      $objSecaoDocumentoRN = new SecaoDocumentoRN();

      $arrObjDocumentoDTO=$objAssinaturaDTO->getArrObjDocumentoDTO();
      $arrIdDocumentoAssinatura=array();
      $arrIdBlocoDocumento=array();

      foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
        $arrIdDocumentoAssinatura[$objDocumentoDTO->getDblIdDocumento()]=1;
        if($objDocumentoDTO->isSetNumIdBloco() && $objDocumentoDTO->getNumIdBloco()!=null){
          $idBloco=$objDocumentoDTO->getNumIdBloco();
          if(!isset($arrIdBlocoDocumento[$idBloco])){
            $arrIdBlocoDocumento[$idBloco]=array($objDocumentoDTO->getDblIdDocumento());
          } else {
            $arrIdBlocoDocumento[$idBloco][]=$objDocumentoDTO->getDblIdDocumento();
          }
        }
      }
      $arrIdDocumentoAssinatura=array_keys($arrIdDocumentoAssinatura);

      //verifica se documentos continuam nos blocos informados
      $objRelBlocoProtocoloRN=new RelBlocoProtocoloRN();
      foreach ($arrIdBlocoDocumento as $idBloco=>$arrIdDocumento) {
        $arrTmp=array_unique($arrIdDocumento);
        $objRelBlocoProtocoloDTO=new RelBlocoProtocoloDTO();
        $objRelBlocoProtocoloDTO->setNumIdBloco($idBloco);
        $objRelBlocoProtocoloDTO->setDblIdProtocolo($arrTmp,InfraDTO::$OPER_IN);
        if($objRelBlocoProtocoloRN->contarRN1292($objRelBlocoProtocoloDTO)!=count($arrTmp)){
          $objInfraException->adicionarValidacao("Bloco $idBloco foi modificado.");
        }
      }
      $objInfraException->lancarValidacoes();

      //verifica permissão de acesso ao documento
      $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
      $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
      $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
      $objPesquisaProtocoloDTO->setDblIdProtocolo($arrIdDocumentoAssinatura);

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

      $numDocOrigem = InfraArray::contar($arrIdDocumentoAssinatura);
      $numDocEncontrado = count($arrObjProtocoloDTO);
      $n = $numDocOrigem - $numDocEncontrado;

      if ($n == 1){
        if ($numDocOrigem == 1){
          $objInfraException->lancarValidacao('Documento não encontrado para assinatura.');
        }else{
          $objInfraException->lancarValidacao('Um documento não está mais disponível para assinatura.');
        }
      }else if ($n > 1){
        $objInfraException->lancarValidacao($n.' documentos não estão mais disponíveis para assinatura.');
      }


      $objProtocoloDTOProcedimento = new ProtocoloDTO();
      $objProtocoloDTOProcedimento->retStrProtocoloFormatado();
      $objProtocoloDTOProcedimento->retStrStaEstado();
      $objProtocoloDTOProcedimento->retStrSinEliminado();
      $objProtocoloDTOProcedimento->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjProtocoloDTO,'IdProcedimentoDocumento'),InfraDTO::$OPER_IN);

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTOProcedimentos = $objProtocoloRN->listarRN0668($objProtocoloDTOProcedimento);

      $objProcedimentoRN = new ProcedimentoRN();
      foreach($arrObjProtocoloDTOProcedimentos as $objProtocoloDTOProcedimento){
        $objProcedimentoRN->verificarEstadoProcedimento($objProtocoloDTOProcedimento);
      }


      $objAcessoExternoRN = new AcessoExternoRN();

      foreach($arrObjProtocoloDTO as $objProtocoloDTO){

        if ($objProtocoloDTO->getStrStaEstado()==ProtocoloRN::$TE_DOCUMENTO_CANCELADO){

          $objInfraException->adicionarValidacao('Documento '.$objProtocoloDTO->getStrProtocoloFormatado().' foi cancelado.');

        }else if ($objUsuarioDTOLogado->getStrStaTipo()==UsuarioRN::$TU_SIP && $objProtocoloDTO->getNumCodigoAcesso() < 0){

          $objInfraException->adicionarValidacao('Usuário '.$objUsuarioDTOLogado->getStrSigla().' não possui acesso ao documento '.$objProtocoloDTO->getStrProtocoloFormatado().' na unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().'.');

        //só valida se o usuário externo estiver logado pois ele pode estar na instituição para assinar através do login de outro usuário
        }elseif ($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_EXTERNO && $objUsuarioDTO->getNumIdUsuario()==$objUsuarioDTOLogado->getNumIdUsuario()){

          $objAcessoExternoDTO = new AcessoExternoDTO();
          $objAcessoExternoDTO->retNumIdAcessoExterno();
          $objAcessoExternoDTO->retDblIdProtocoloAtividade();
          $objAcessoExternoDTO->retNumIdUnidade();
          $objAcessoExternoDTO->retStrSiglaUnidade();
          $objAcessoExternoDTO->retDtaValidade();
          $objAcessoExternoDTO->setNumIdContatoParticipante($objUsuarioDTO->getNumIdContato());
          $objAcessoExternoDTO->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
          $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_ASSINATURA_EXTERNA);
          $objAcessoExternoDTO->setNumMaxRegistrosRetorno(1);
          $objAcessoExternoDTO = $objAcessoExternoRN->consultar($objAcessoExternoDTO);

          if ($objAcessoExternoDTO == null){
            $objInfraException->adicionarValidacao('Usuário externo '.$objUsuarioDTO->getStrSigla().' não recebeu liberação para assinar o documento '.$objProtocoloDTO->getStrProtocoloFormatado().'.');
          }

          if ($objAcessoExternoDTO->getDtaValidade()!=null && InfraData::compararDatas(InfraData::getStrDataAtual(),$objAcessoExternoDTO->getDtaValidade())<0){
            $objInfraException->adicionarValidacao('Liberação para assinatura externa expirou em '.$objAcessoExternoDTO->getDtaValidade().'.');
          }

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
          $objAtividadeDTO->setDblIdProtocolo($objAcessoExternoDTO->getDblIdProtocoloAtividade());
          $objAtividadeDTO->setNumIdUnidade($objAcessoExternoDTO->getNumIdUnidade());
          $objAtividadeDTO->setDthConclusao(null);

          $objAtividadeRN = new AtividadeRN();
          if ($objAtividadeRN->contarRN0035($objAtividadeDTO)==0){
            $objInfraException->adicionarValidacao('Não é possível assinar o documento '.$objProtocoloDTO->getStrProtocoloFormatado().' porque o processo já foi concluído na unidade '.$objAcessoExternoDTO->getStrSiglaUnidade().' que liberou o acesso externo.');
          }
        }

        if ($objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){

          if ($objProtocoloDTO->getStrSinPublicado()=='S'){
            $objInfraException->adicionarValidacao('Documento '.$objProtocoloDTO->getStrProtocoloFormatado().' já foi publicado.');
          }

          if ($objProtocoloDTO->getStrSinDisponibilizadoParaOutraUnidade()=='S' && $objUsuarioDTO->getStrStaTipo()!=UsuarioRN::$TU_EXTERNO){
            $objInfraException->adicionarValidacao('Documento '.$objProtocoloDTO->getStrProtocoloFormatado().' foi disponibilizado para assinatura em outra unidade.');
          }

          if ($objProtocoloDTO->getStrStaDocumentoDocumento()==DocumentoRN::$TD_FORMULARIO_AUTOMATICO){
            $objInfraException->adicionarValidacao('Formulário '.$objProtocoloDTO->getStrProtocoloFormatado().' não pode receber assinatura.');
          }

          if ($objProtocoloDTO->getStrStaDocumentoDocumento()==DocumentoRN::$TD_EDITOR_EDOC){
            $objInfraException->adicionarValidacao('Não é possível assinar documentos gerados pelo e-Doc ('.$objProtocoloDTO->getStrProtocoloFormatado().').');
          }

          if ($objUsuarioDTO->getStrStaTipo()!=UsuarioRN::$TU_EXTERNO) {
            if (!($objProtocoloDTO->getNumIdUnidadeGeradora()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() || $objProtocoloDTO->getStrSinAcessoAssinaturaBloco()=='S' || $objProtocoloDTO->getStrSinCredencialAssinatura()=='S')) {
              $objInfraException->adicionarValidacao('Documento '.$objProtocoloDTO->getStrProtocoloFormatado().' não pode ser assinado pelo usuário '.$objUsuarioDTO->getStrSigla().' na unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().'.');
            }
          }
        }

        $dto = new AssinaturaDTO();
        $dto->retStrNome();
        $dto->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
        $dto->setNumIdUsuario($objAssinaturaDTO->getNumIdUsuario());
        $dto = $objAssinaturaRN->consultarRN1322($dto);

        if ($dto != null){
          $objInfraException->adicionarValidacao('Documento '.$objProtocoloDTO->getStrProtocoloFormatado().' já foi assinado por "'.$dto->getStrNome().'".');
        }

        if ($objProtocoloDTO->getStrStaDocumentoDocumento()==DocumentoRN::$TD_EDITOR_INTERNO) {
          $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
          $objSecaoDocumentoDTO->retNumIdSecaoDocumento();
          $objSecaoDocumentoDTO->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
          $objSecaoDocumentoDTO->setStrSinAssinatura('S');
          $objSecaoDocumentoDTO->setNumMaxRegistrosRetorno(1);

          if ($objSecaoDocumentoRN->consultar($objSecaoDocumentoDTO) == null) {
            $objInfraException->adicionarValidacao('Documento ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não contém seção de assinatura.');
          }
        }

        if ($objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO && $objProtocoloDTO->getNumIdTipoConferenciaDocumento()==null){
          $objInfraException->adicionarValidacao('Documento ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não possui Tipo de Conferência informada.');
        }
      }

      $objInfraException->lancarValidacoes();

      /*
       foreach($arrObjProtocoloDTO as $objProtocoloDTO){
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->bloquear($objDocumentoDTO);
      }
      */

      $objInfraException->lancarValidacoes();

      if ($objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_SENHA){

        if ($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_SIP){
          $objInfraSip = new InfraSip(SessaoSEI::getInstance());
          $objInfraSip->autenticar($objAssinaturaDTO->getNumIdOrgaoUsuario(),
              null,
              $objUsuarioDTO->getStrSigla(),
              $objAssinaturaDTO->getStrSenhaUsuario());
        }else{
          $bcrypt = new InfraBcrypt();
          if (!$bcrypt->verificar(md5($objAssinaturaDTO->getStrSenhaUsuario()), $objUsuarioDTO->getStrSenha())) {
            $objInfraException->lancarValidacao('Senha inválida.');
          }
        }
      }

      foreach($arrObjProtocoloDTO as $objProtocoloDTO){
        if ($objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){
          if ($objProtocoloDTO->getStrSinAssinado()=='N'){

            if ($objProtocoloDTO->getStrStaDocumentoDocumento()==DocumentoRN::$TD_EDITOR_INTERNO) {
              /*
              //gerar nova versao igual a anterior para substituição de dados dinâmicos (ex.: datas)
              $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
              $objVersaoSecaoDocumentoDTO->retNumIdSecaoModeloSecaoDocumento();
              $objVersaoSecaoDocumentoDTO->retStrConteudo();
              $objVersaoSecaoDocumentoDTO->setDblIdDocumentoSecaoDocumento($objProtocoloDTO->getDblIdProtocolo());
              $objVersaoSecaoDocumentoDTO->setNumIdBaseConhecimentoSecaoDocumento(null);
              $objVersaoSecaoDocumentoDTO->setStrSinUltima('S');
              $objVersaoSecaoDocumentoDTO->setOrdNumOrdemSecaoDocumento(InfraDTO::$TIPO_ORDENACAO_ASC);

              $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
              $arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);

              $arrObjSecaoDocumentoDTO = array();
              foreach($arrObjVersaoSecaoDocumentoDTO as $objVersaoSecaoDocumentoDTO){
                $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
                $objSecaoDocumentoDTO->setNumIdSecaoModelo($objVersaoSecaoDocumentoDTO->getNumIdSecaoModeloSecaoDocumento());
                $objSecaoDocumentoDTO->setStrConteudo($objVersaoSecaoDocumentoDTO->getStrConteudo());
                $arrObjSecaoDocumentoDTO[] = $objSecaoDocumentoDTO;
              }
              $objEditorDTO = new EditorDTO();
              $objEditorDTO->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
              $objEditorDTO->setNumIdBaseConhecimento(null);
              $objEditorDTO->setArrObjSecaoDocumentoDTO($arrObjSecaoDocumentoDTO);

              $objEditorRN = new EditorRN();
              $objEditorRN->adicionarVersao($objEditorDTO);
              */

              /////////
              $objEditorDTO = new EditorDTO();
              $objEditorDTO->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
              $objEditorDTO->setNumIdBaseConhecimento(null);
              $objEditorDTO->setStrSinCabecalho('S');
              $objEditorDTO->setStrSinRodape('S');
              $objEditorDTO->setStrSinCarimboPublicacao('N');
              $objEditorDTO->setStrSinIdentificacaoVersao('N');

              $objEditorRN = new EditorRN();
              $strDocumentoHTML = $objEditorRN->consultarHtmlVersao($objEditorDTO);

            }else if ($objProtocoloDTO->getStrStaDocumentoDocumento()==DocumentoRN::$TD_FORMULARIO_GERADO){

              $dto = new DocumentoDTO();
              $dto->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
              $strDocumentoHTML = $this->consultarHtmlFormulario($dto);

            }

            $objDocumentoConteudoDTO = new DocumentoConteudoDTO();
            $objDocumentoConteudoDTO->setStrConteudoAssinatura($strDocumentoHTML);
            $objDocumentoConteudoDTO->setStrCrcAssinatura(strtoupper(hash('crc32b', $strDocumentoHTML)));
            $this->gerarQrCode($objProtocoloDTO, $objDocumentoConteudoDTO);
            $objDocumentoConteudoDTO->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());

            $objDocumentoConteudoBD = new DocumentoConteudoBD($this->getObjInfraIBanco());
            $objDocumentoConteudoBD->alterar($objDocumentoConteudoDTO);

          }

        }else{

          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO->retNumIdAnexo();
          $objAnexoDTO->retDthInclusao();
          $objAnexoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());

          $objAnexoRN = new AnexoRN();
          $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);

          if ($objAnexoDTO==null){
            $objInfraException->lancarValidacao('Documento '.$objProtocoloDTO->getStrProtocoloFormatado().' não possui anexo associado.');
          }

          $objDocumentoConteudoBD = new DocumentoConteudoBD($this->getObjInfraIBanco());

          $objDocumentoConteudoDTO = new DocumentoConteudoDTO();
          $objDocumentoConteudoDTO->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());

          if ($objDocumentoConteudoBD->contar($objDocumentoConteudoDTO) == 0){
            $objDocumentoConteudoDTO->setStrConteudo(null);
            $objDocumentoConteudoDTO->setStrConteudoAssinatura(null);
            $objDocumentoConteudoDTO->setStrCrcAssinatura(strtoupper(hash_file('crc32b', $objAnexoRN->obterLocalizacao($objAnexoDTO))));
            $this->gerarQrCode($objProtocoloDTO, $objDocumentoConteudoDTO);
            $objDocumentoConteudoBD->cadastrar($objDocumentoConteudoDTO);
          }else{
            $objDocumentoConteudoDTO->setStrCrcAssinatura(strtoupper(hash_file('crc32b', $objAnexoRN->obterLocalizacao($objAnexoDTO))));
            $this->gerarQrCode($objProtocoloDTO, $objDocumentoConteudoDTO);
            $objDocumentoConteudoBD->alterar($objDocumentoConteudoDTO);
          }
        }
      }

      $objTarjaAssinaturaDTO = new TarjaAssinaturaDTO();
      $objTarjaAssinaturaDTO->retNumIdTarjaAssinatura();

      if ($objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO) {
        if ($objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_SENHA || $objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_MODULO) {
          $objTarjaAssinaturaDTO->setStrStaTarjaAssinatura(TarjaAssinaturaRN::$TT_ASSINATURA_SENHA);
        } else {
          $objTarjaAssinaturaDTO->setStrStaTarjaAssinatura(TarjaAssinaturaRN::$TT_ASSINATURA_CERTIFICADO_DIGITAL);
        }
      }else{
        if ($objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_SENHA || $objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_MODULO) {
          $objTarjaAssinaturaDTO->setStrStaTarjaAssinatura(TarjaAssinaturaRN::$TT_AUTENTICACAO_SENHA);
        } else {
          $objTarjaAssinaturaDTO->setStrStaTarjaAssinatura(TarjaAssinaturaRN::$TT_AUTENTICACAO_CERTIFICADO_DIGITAL);
        }
      }

      $objTarjaAssinaturaRN = new TarjaAssinaturaRN();
      $objTarjaAssinaturaDTO = $objTarjaAssinaturaRN->consultar($objTarjaAssinaturaDTO);

      $strAgrupador = null;
      if ($objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_CERTIFICADO_DIGITAL || $objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_MODULO){
        $strAgrupador = InfraULID::gerar();
      }

      $objAtividadeRN = new AtividadeRN();
      $arrObjAssinaturaDTO = array();
      $arrObjDocumentoDTOCredencialAssinatura = array();
      foreach($arrObjProtocoloDTO as $objProtocoloDTO){

        $numIdAtividade = null;
        if ($objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_SENHA){

          //lança tarefa de assinatura
          $arrObjAtributoAndamentoDTO = array();
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
          $objAtributoAndamentoDTO->setStrValor($objProtocoloDTO->getStrProtocoloFormatado());
          $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getDblIdProtocolo());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('USUARIO');
          $objAtributoAndamentoDTO->setStrValor($objUsuarioDTO->getStrSigla().'¥'.$objUsuarioDTO->getStrNome());
          $objAtributoAndamentoDTO->setStrIdOrigem($objUsuarioDTO->getNumIdUsuario());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          //Define se o propósito da operação é assinar ou autenticar o documento
          $numIdTarefaAssinatura = TarefaRN::$TI_ASSINATURA_DOCUMENTO;
          if($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
            $numIdTarefaAssinatura = TarefaRN::$TI_AUTENTICACAO_DOCUMENTO;
          }

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProcedimentoDocumento());
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setNumIdTarefa($numIdTarefaAssinatura);
          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

          $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
          $numIdAtividade = $objAtividadeDTO->getNumIdAtividade();
        }

        //remove ocorrência pendente, se existir
        $dto = new AssinaturaDTO();
        $dto->retNumIdAssinatura();
        $dto->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
        $dto->setNumIdUsuario($objAssinaturaDTO->getNumIdUsuario());
        $dto->setBolExclusaoLogica(false);
        $dto->setStrSinAtivo('N');
        $dto = $objAssinaturaRN->consultarRN1322($dto);

        if ($dto!=null){
          $objAssinaturaRN->excluirRN1321(array($dto));
        }

        $dto = new AssinaturaDTO();
        $dto->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
        $dto->setStrProtocoloDocumentoFormatado($objProtocoloDTO->getStrProtocoloFormatado());
        $dto->setNumIdUsuario($objAssinaturaDTO->getNumIdUsuario());
        $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $dto->setNumIdAtividade($numIdAtividade);
        $dto->setNumIdTarjaAssinatura($objTarjaAssinaturaDTO->getNumIdTarjaAssinatura());
        $dto->setStrSiglaUsuario($objUsuarioDTO->getStrSigla());
        $dto->setStrNome($objUsuarioDTO->getStrNome());
        $dto->setStrTratamento($objAssinaturaDTO->getStrCargoFuncao());
        $dto->setDblCpf($objUsuarioDTO->getDblCpfContato());
        $dto->setStrStaFormaAutenticacao($objAssinaturaDTO->getStrStaFormaAutenticacao());
        $dto->setStrP7sBase64(null);
        $dto->setStrAgrupador($strAgrupador);

        if ($objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_CERTIFICADO_DIGITAL || $objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_MODULO){
          $dto->setStrSinAtivo('N');
        }else{
          $dto->setStrSinAtivo('S');
        }

        $arrObjAssinaturaDTO[] = $objAssinaturaRN->cadastrarRN1319($dto);

        if ($objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_SENHA && $objProtocoloDTO->getStrSinCredencialAssinatura()=='S'){
          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
          $arrObjDocumentoDTOCredencialAssinatura[] = $objDocumentoDTO;
        }
      }

      if (count($arrObjDocumentoDTOCredencialAssinatura)){
        $objAtividadeRN->concluirCredencialAssinatura($arrObjDocumentoDTOCredencialAssinatura);
      }

      //força bloqueio de conteúdo quando usuario externo
      if ($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_EXTERNO){
        foreach($arrObjProtocoloDTO as $objProtocoloDTO){
          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
          $this->bloquearConteudo($objDocumentoDTO);
        }
      }

      if (count($SEI_MODULOS)){

        if ($objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_SENHA) {

          $arrObjDocumentoAPI = array();
          foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
            $objDocumentoAPI = new DocumentoAPI();
            $objDocumentoAPI->setIdDocumento($objProtocoloDTO->getDblIdProtocolo());
            $objDocumentoAPI->setIdProcedimento($objProtocoloDTO->getDblIdProcedimentoDocumento());
            $objDocumentoAPI->setNumeroProtocolo($objProtocoloDTO->getStrProtocoloFormatado());
            $objDocumentoAPI->setIdSerie($objProtocoloDTO->getNumIdSerieDocumento());
            $objDocumentoAPI->setIdUnidadeGeradora($objProtocoloDTO->getNumIdUnidadeGeradora());
            $objDocumentoAPI->setIdOrgaoUnidadeGeradora($objProtocoloDTO->getNumIdOrgaoUnidadeGeradora());
            $objDocumentoAPI->setIdUsuarioGerador($objProtocoloDTO->getNumIdUsuarioGerador());
            $objDocumentoAPI->setTipo($objProtocoloDTO->getStrStaProtocolo());
            $objDocumentoAPI->setSubTipo($objProtocoloDTO->getStrStaDocumentoDocumento());
            $objDocumentoAPI->setNivelAcesso($objProtocoloDTO->getStrStaNivelAcessoGlobal());
            $arrObjDocumentoAPI[] = $objDocumentoAPI;
          }

          foreach ($SEI_MODULOS as $seiModulo) {
            $seiModulo->executar('assinarDocumento', $arrObjDocumentoAPI);
          }

        }else{
          $objAssinaturaAPI = new AssinaturaAPI();
          $objAssinaturaAPI->setIdUsuario($objAssinaturaDTO->getNumIdUsuario());
          $objAssinaturaAPI->setSigla($objUsuarioDTO->getStrSigla());
          $objAssinaturaAPI->setNome($objUsuarioDTO->getStrNome());
          $objAssinaturaAPI->setCargoFuncao($objAssinaturaDTO->getStrCargoFuncao());
          $objAssinaturaAPI->setCpf($objUsuarioDTO->getDblCpfContato());
          $objAssinaturaAPI->setStaFormaAutenticacao($objAssinaturaDTO->getStrStaFormaAutenticacao());
          $objAssinaturaAPI->setAgrupador($strAgrupador);
          foreach ($SEI_MODULOS as $seiModulo) {
            $seiModulo->executar('prepararAssinaturaDocumento', $objAssinaturaAPI);
          }
        }
      }


      return $arrObjAssinaturaDTO;

    }catch(Exception $e){
      throw new InfraException('Erro assinando documento.',$e);
    }
  }

  private function gerarQrCode(ProtocoloDTO $objProtocoloDTO, DocumentoConteudoDTO $objDocumentoConteudoDTO){
    try{

      $objAnexoRN = new AnexoRN();
      $strArquivoQRCaminhoCompleto = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario();
      $strUrlVerificacao = ConfiguracaoSEI::getInstance()->getValor('SEI','URL').'/controlador_externo.php?acao=documento_conferir&id_orgao_acesso_externo='.$objProtocoloDTO->getNumIdOrgaoUnidadeGeradora().'&cv='.$objProtocoloDTO->getStrProtocoloFormatado().'&crc='.$objDocumentoConteudoDTO->getStrCrcAssinatura();

      InfraQRCode::gerar($strUrlVerificacao, $strArquivoQRCaminhoCompleto,'L',2,1);

      $objInfraException = new InfraException();


      if (!file_exists($strArquivoQRCaminhoCompleto)){
        $objInfraException->lancarValidacao('Arquivo do QRCode não encontrado.');
      }

      if (filesize($strArquivoQRCaminhoCompleto)==0){
        $objInfraException->lancarValidacao('Arquivo do QRCode vazio.');
      }

      if (($binQrCode = file_get_contents($strArquivoQRCaminhoCompleto))===false){
        $objInfraException->lancarValidacao('Não foi possível ler o arquivo do QRCode.');
      }

      $objDocumentoConteudoDTO->setStrQrCodeAssinatura(base64_encode($binQrCode));

      unlink($strArquivoQRCaminhoCompleto);

    }catch(Exception $e){
      throw new InfraException('Erro gerando QRCode da assinatura.',$e);
    }
  }

  public function confirmarAssinatura(AssinaturaDTO $objAssinaturaDTO){

    $objDocumentoDTO = $this->confirmarAssinaturaInterno($objAssinaturaDTO);

    if ($objDocumentoDTO!=null){

      $objIndexacaoDTO = new IndexacaoDTO();
      $objIndexacaoDTO->setArrIdProtocolos(array($objDocumentoDTO->getDblIdDocumento()));
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_ASSINATURA);

      $objIndexacaoRN = new IndexacaoRN();
      $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);
    }
  }

  protected function confirmarAssinaturaInternoControlado(AssinaturaDTO $parObjAssinaturaDTO) {
    try{

      global $SEI_MODULOS;

      //Regras de Negocio
      $objInfraException = new InfraException();
      //$objInfraException->lancarValidacoes();

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrStaDocumento();

      $objAssinaturaRN = new AssinaturaRN();
      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->retDblIdDocumento();
      $objAssinaturaDTO->retNumIdAssinatura();
      $objAssinaturaDTO->retStrSinAtivo();
      $objAssinaturaDTO->retNumIdUnidade();
      $objAssinaturaDTO->retNumIdUsuario();
      $objAssinaturaDTO->retStrSiglaUsuario();
      $objAssinaturaDTO->retStrSiglaOrgaoUsuario();
      $objAssinaturaDTO->retStrNomeUsuario();
      $objAssinaturaDTO->retDblCpf();
      $objAssinaturaDTO->setBolExclusaoLogica(false);

      if ($parObjAssinaturaDTO->isSetNumIdAssinatura()){
        // editor interno
        $objAssinaturaDTO->setNumIdAssinatura($parObjAssinaturaDTO->getNumIdAssinatura());
        $objAssinaturaDTO = $objAssinaturaRN->consultarRN1322($objAssinaturaDTO);

        if ($objAssinaturaDTO==null){
          $objInfraException->lancarValidacao('Assinatura '.$parObjAssinaturaDTO->getNumIdAssinatura().' não localizada no SEI.');
        }

        $objDocumentoDTO->setDblIdDocumento($objAssinaturaDTO->getDblIdDocumento());
        $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

        $parObjAssinaturaDTO->setDblCpf($objAssinaturaDTO->getDblCpf());
        $parObjAssinaturaDTO->setStrSiglaUsuario($objAssinaturaDTO->getStrSiglaUsuario());
        $parObjAssinaturaDTO->setStrSiglaOrgaoUsuario($objAssinaturaDTO->getStrSiglaOrgaoUsuario());
        $parObjAssinaturaDTO->setDblIdDocumento($objAssinaturaDTO->getDblIdDocumento());
        $parObjAssinaturaDTO->setNumIdAssinatura($objAssinaturaDTO->getNumIdAssinatura());
        $objAssinaturaRN->validarAssinaturaDocumento($parObjAssinaturaDTO, $objInfraException);

      }else if ($parObjAssinaturaDTO->isSetDblIdDocumentoEdoc()){
        // editor edoc
        $objDocumentoDTO->setDblIdDocumentoEdoc($parObjAssinaturaDTO->getDblIdDocumentoEdoc());
        $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

        if ($objDocumentoDTO==null){
          $objInfraException->lancarValidacao('Documento '.$parObjAssinaturaDTO->getDblIdDocumentoEdoc().' não possui correspondência no SEI.');
        }

        $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objAssinaturaDTO->setStrSiglaUsuario($parObjAssinaturaDTO->getStrSiglaUsuario());
        $objAssinaturaDTO->setDblCpf($parObjAssinaturaDTO->getDblCpf());
        $objAssinaturaDTO = $objAssinaturaRN->consultarRN1322($objAssinaturaDTO);

        $parObjAssinaturaDTO->setNumIdAssinatura($objAssinaturaDTO->getNumIdAssinatura());

      }else{
        $objInfraException->lancarValidacao('Documento para confirmação de assinatura não informado.');
      }

      if ($objAssinaturaDTO->getStrSinAtivo()=='S'){
        $objInfraException->lancarValidacao('Não existe assinatura pendente para o documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().'/'.$objDocumentoDTO->getDblIdDocumento().' no SEI.');
      }

      SessaoSEI::getInstance()->setNumIdUsuario($objAssinaturaDTO->getNumIdUsuario());
      SessaoSEI::getInstance()->setNumIdUnidadeAtual($objAssinaturaDTO->getNumIdUnidade());

      $objAtividadeRN = new AtividadeRN();


      //verifica permissão de acesso ao documento
      $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
      $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
      $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
      $objPesquisaProtocoloDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

      if (count($arrObjProtocoloDTO)==0){
        $objInfraException->lancarValidacao('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' não está disponível para assinatura.');
      }

      if ($arrObjProtocoloDTO[0]->getStrSinCredencialAssinatura()=='S'){
        $objAtividadeRN->concluirCredencialAssinatura(array($objDocumentoDTO));
      }

      $objProtocoloDTOProcedimento = new ProtocoloDTO();
      $objProtocoloDTOProcedimento->retStrProtocoloFormatado();
      $objProtocoloDTOProcedimento->retStrStaEstado();
      $objProtocoloDTOProcedimento->retStrSinEliminado();
      $objProtocoloDTOProcedimento->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());

      $objProtocoloRN = new ProtocoloRN();
      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->verificarEstadoProcedimento($objProtocoloRN->consultarRN0186($objProtocoloDTOProcedimento));

      //lança tarefa de assinatura
      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
      $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('USUARIO');
      $objAtributoAndamentoDTO->setStrValor($objAssinaturaDTO->getStrSiglaUsuario().'¥'.$objAssinaturaDTO->getStrNomeUsuario());
      $objAtributoAndamentoDTO->setStrIdOrigem($objAssinaturaDTO->getNumIdUsuario());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      //Define se o propósito da operação é assinar ou autenticar o documento
      $numIdTarefaAssinatura = TarefaRN::$TI_ASSINATURA_DOCUMENTO;
      if($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO) {
        $numIdTarefaAssinatura = TarefaRN::$TI_AUTENTICACAO_DOCUMENTO;
      }

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidadeOrigem($objAssinaturaDTO->getNumIdUnidade());
      $objAtividadeDTO->setNumIdUnidade($objAssinaturaDTO->getNumIdUnidade());
      $objAtividadeDTO->setNumIdTarefa($numIdTarefaAssinatura);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);


      $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      $dto = new AssinaturaDTO();
      $dto->setStrSinAtivo('S');
      $dto->setNumIdAtividade($objAtividadeDTO->getNumIdAtividade());
      $bolAssinaturaModulo=false;
      if($parObjAssinaturaDTO->isSetBolAssinaturaModulo() && $parObjAssinaturaDTO->getBolAssinaturaModulo()){
        $bolAssinaturaModulo=true;
      }
      if ($objDocumentoDTO->getStrStaDocumento()!=DocumentoRN::$TD_EDITOR_EDOC && !$bolAssinaturaModulo){
        $dto->setStrNumeroSerieCertificado($parObjAssinaturaDTO->getStrNumeroSerieCertificado());
        $dto->setStrP7sBase64($parObjAssinaturaDTO->getStrP7sBase64());
      }
      $dto->setNumIdAssinatura($parObjAssinaturaDTO->getNumIdAssinatura());
      $objAssinaturaRN->alterarRN1320($dto);


      if (count($SEI_MODULOS)){

        $objProtocoloDTO = $arrObjProtocoloDTO[0];

        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($objProtocoloDTO->getDblIdProtocolo());
        $objDocumentoAPI->setIdProcedimento($objProtocoloDTO->getDblIdProcedimentoDocumento());
        $objDocumentoAPI->setNumeroProtocolo($objProtocoloDTO->getStrProtocoloFormatado());
        $objDocumentoAPI->setIdSerie($objProtocoloDTO->getNumIdSerieDocumento());
        $objDocumentoAPI->setIdUnidadeGeradora($objProtocoloDTO->getNumIdUnidadeGeradora());
        $objDocumentoAPI->setIdOrgaoUnidadeGeradora($objProtocoloDTO->getNumIdOrgaoUnidadeGeradora());
        $objDocumentoAPI->setIdUsuarioGerador($objProtocoloDTO->getNumIdUsuarioGerador());
        $objDocumentoAPI->setTipo($objProtocoloDTO->getStrStaProtocolo());
        $objDocumentoAPI->setSubTipo($objProtocoloDTO->getStrStaDocumentoDocumento());
        $objDocumentoAPI->setNivelAcesso($objProtocoloDTO->getStrStaNivelAcessoGlobal());

        $arrObjDocumentoAPI = array($objDocumentoAPI);

        foreach($SEI_MODULOS as $seiModulo){
          $seiModulo->executar('assinarDocumento', $arrObjDocumentoAPI);
        }
      }

      return $objDocumentoDTO;

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro confirmando assinatura.',$e);
    }
  }

  protected function validarDocumentoPublicadoRN1211Controlado(DocumentoDTO $parObjDocumentoDTO){

    $objInfraException = new InfraException();

    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoDTO->retObjPublicacaoDTO();
    $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
    $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

    $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

    $objPublicacaoDTO = $objDocumentoDTO->getObjPublicacaoDTO();

    if ($objPublicacaoDTO != null){
      if ($objPublicacaoDTO->getStrStaEstado()==PublicacaoRN::$TE_AGENDADO){
        $objInfraException->lancarValidacao('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' agendado para publicação em '.$objPublicacaoDTO->getDtaDisponibilizacao().'.');
      }else{
        $objInfraException->lancarValidacao('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' foi publicado em '.$objPublicacaoDTO->getDtaPublicacao().'.');
      }
    }
  }

  private function validarStrNumeroRN0010(DocumentoDTO $objDocumentoDTO, InfraException $objInfraException){

    $objSerieDTO = new SerieDTO();
    $objSerieDTO->setBolExclusaoLogica(false);
    $objSerieDTO->retStrNome();
    $objSerieDTO->retStrStaNumeracao();
    $objSerieDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());

    $objSerieRN = new SerieRN();
    $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);

    $strStaNumeracao = $objSerieDTO->getStrStaNumeracao();
    $strNomeSerie = $objSerieDTO->getStrNome();

    if ($strStaNumeracao == SerieRN::$TN_INFORMADA){
      if (!InfraString::isBolVazia($objDocumentoDTO->getStrNumero())){
        $objInfraException->adicionarValidacao('Número não informado.');
      }else{

        $this->validarTamanhoNumeroRN0993($objDocumentoDTO, $objInfraException);
      }
    }else{

      $dto = new DocumentoDTO();
      $dto->retStrNumero();
      $dto->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $dto = $this->consultarRN0005($dto);

      if ($dto->getStrNumero() != $objDocumentoDTO->getStrNumero()){
        $objInfraException->adicionarValidacao('Não é possível alterar a numeração porque o tipo '.$strNomeSerie.' não aceita que o número seja informado.');
      }
    }
  }

  public function cancelarAssinatura(DocumentoDTO $objDocumentoDTO){

    if ($this->cancelarAssinaturaInterno($objDocumentoDTO)) {

      $objIndexacaoDTO = new IndexacaoDTO();
      $objIndexacaoDTO->setArrIdProtocolos(array($objDocumentoDTO->getDblIdDocumento()));
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_ASSINATURA);

      $objIndexacaoRN = new IndexacaoRN();
      $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);
    }

  }

  protected function cancelarAssinaturaInternoControlado(DocumentoDTO $parObjDocumentoDTO){
    try {

      $objInfraException = new InfraException();

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->setBolExclusaoLogica(false);
      $objAssinaturaDTO->retNumIdAssinatura();
      $objAssinaturaDTO->retNumIdUnidade();
      $objAssinaturaDTO->retStrStaTipoUsuario();
      $objAssinaturaDTO->retStrSiglaUsuario();
      $objAssinaturaDTO->retStrSinAtivo();
      $objAssinaturaDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

      $objAssinaturaRN = new AssinaturaRN();
      $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);

      if (count($arrObjAssinaturaDTO)>0){

        foreach($arrObjAssinaturaDTO as $objAssinaturaDTO){
          if ($objAssinaturaDTO->getStrSinAtivo()=='S' && ($objAssinaturaDTO->getStrStaTipoUsuario()==UsuarioRN::$TU_EXTERNO_PENDENTE || $objAssinaturaDTO->getStrStaTipoUsuario()==UsuarioRN::$TU_EXTERNO)){
            $objInfraException->adicionarValidacao('Documento foi assinado pelo usuário externo "'.$objAssinaturaDTO->getStrSiglaUsuario().'".');
          }
        }

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retDblIdProcedimento();
        $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
        $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
        $objDocumentoDTO->retStrSinBloqueado();
        $objDocumentoDTO->retStrStaDocumento();
        $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

        $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

        if ($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
          foreach($arrObjAssinaturaDTO as $objAssinaturaDTO){
            if ($objAssinaturaDTO->getStrSinAtivo()=='S' && $objAssinaturaDTO->getNumIdUnidade()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
              $objInfraException->lancarValidacao('Documento foi assinado por outra unidade.');
            }
          }
        }

        if ($objDocumentoDTO->getStrSinBloqueado()=='S'){
          $objInfraException->lancarValidacao('A assinatura do documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' não pode mais ser cancelada.');
        }

        $objInfraException->lancarValidacoes();

        $dto = new DocumentoDTO();
        $dto->setStrSinBloqueado('N');
        $dto->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

        $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
        $objDocumentoBD->alterar($dto);

        $dto = new DocumentoConteudoDTO();
        $dto->setStrConteudoAssinatura(null);
        $dto->setStrCrcAssinatura(null);
        $dto->setStrQrCodeAssinatura(null);
        $dto->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

        $objDocumentoConteudoBD = new DocumentoConteudoBD($this->getObjInfraIBanco());
        $objDocumentoConteudoBD->alterar($dto);


        $objAssinaturaRN->excluirRN1321($arrObjAssinaturaDTO);


        //lança tarefa de cancelamento de assinatura
        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $objAtributoAndamentoDTO->setStrIdOrigem($parObjDocumentoDTO->getDblIdDocumento());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $numIdTarefaCancelamentoAssinatura = TarefaRN::$TI_CANCELAMENTO_ASSINATURA;
        if($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO) {
          $numIdTarefaCancelamentoAssinatura = TarefaRN::$TI_CANCELAMENTO_AUTENTICACAO;
        }

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa($numIdTarefaCancelamentoAssinatura);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        return true;
      }

      return false;

    }catch(Exception $e){
      throw new InfraException('Erro cancelando assinatura.',$e);
    }
  }

  public function verificarSelecaoEmail(DocumentoDTO $objDocumentoDTO){

    //exclui cancelados
    //documento externos
    //formulários automáticos
    //gerados/formulários assinados ou publicados

    return ($objDocumentoDTO->getStrStaEstadoProtocolo() != ProtocoloRN::$TE_DOCUMENTO_CANCELADO
             &&
             (
              $objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EXTERNO
                 ||
              $objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_AUTOMATICO
                 ||
              (
                  ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO  || $objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_GERADO || ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC && $objDocumentoDTO->getDblIdDocumentoEdoc()!=null))
                  &&
                  ($objDocumentoDTO->getStrSinAssinado()=='S' || $objDocumentoDTO->getStrSinPublicado()=='S')
              )
             )
           );
  }

  public function verificarSelecaoDuplicacao(DocumentoDTO $objDocumentoDTO){

    //exclui cancelados
    //documento externos
    //gerados/formulários/edoc (da unidade atual ou assinados ou publicados)

    return ($objDocumentoDTO->getStrStaEstadoProtocolo() != ProtocoloRN::$TE_DOCUMENTO_CANCELADO
            &&
             ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EXTERNO ||
               (
                   $this->verificarConteudoGerado($objDocumentoDTO)
                   &&
                   ($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() || $objDocumentoDTO->getStrSinAssinado()=='S' || $objDocumentoDTO->getStrSinPublicado()=='S')
               )
             )
           );
  }

  public function verificarSelecaoGeracaoPdf(DocumentoDTO $objDocumentoDTO){

    //exclui cancelados
    //documento externos (pdf, text, html)
    //formularios
    //gerados pela unidade atual [inclui rascunhos]
    //gerados assinados
    //gerados publicados

    if ($objDocumentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_DOCUMENTO_CANCELADO){
      return false;
    }

    if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){

      if ($objDocumentoDTO->isSetObjAnexoDTO()){
        $objAnexoDTO = $objDocumentoDTO->getObjAnexoDTO();
      }else {
        $objAnexoDTO = new AnexoDTO();
        $objAnexoDTO->retNumIdAnexo();
        $objAnexoDTO->retStrNome();
        $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
        $objAnexoRN = new AnexoRN();
        $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);
      }

      if ($objAnexoDTO!=null){
        if (InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'application/pdf' ||
            InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'text/plain' ||
            InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'text/html' ||
            $this->ehUmaExtensaoDeImagemPermitida($objAnexoDTO->getStrNome())){
          return true;
        }
        if ($this->processarArquivoOpenOffice($objAnexoDTO->getStrNome())){
          return true;
        }
      }
    }

    if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){

      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_AUTOMATICO){
        return true;
      }

      if ($this->verificarConteudoGerado($objDocumentoDTO)){
        if ($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() ||
            $objDocumentoDTO->getStrSinAssinado()=='S' ||
            $objDocumentoDTO->getStrSinPublicado()=='S'){
          return true;
        }

        if ($objDocumentoDTO->isSetNumCodigoAcesso() && $objDocumentoDTO->getNumCodigoAcesso() > 0){
          return true;
        }
      }
    }

    return false;
  }

  public function verificarSelecaoGeracaoZip(DocumentoDTO $objDocumentoDTO){
    //exclui cancelados
    //documento externos
    //formularios
    //gerados pela unidade atual [inclui rascunhos]
    //gerados assinados
    //gerados publicados

    if ($objDocumentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_DOCUMENTO_CANCELADO){
      return false;
    }

    if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){

      if ($objDocumentoDTO->isSetObjAnexoDTO()) {
        $objAnexoDTO = $objDocumentoDTO->getObjAnexoDTO();
      }else{
        $objAnexoDTO = new AnexoDTO();
        $objAnexoDTO->retNumIdAnexo();
        $objAnexoDTO->retStrNome();
        $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
        $objAnexoRN = new AnexoRN();
        $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);
      }

      if ($objAnexoDTO!=null) {
        return true;
      }
    }

    if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){

      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_AUTOMATICO){
        return true;
      }

      if ($this->verificarConteudoGerado($objDocumentoDTO)){
        if ($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() ||
            $objDocumentoDTO->getStrSinAssinado()=='S' ||
            $objDocumentoDTO->getStrSinPublicado()=='S'){
          return true;
        }

        if ($objDocumentoDTO->isSetNumCodigoAcesso() && $objDocumentoDTO->getNumCodigoAcesso() > 0){
          return true;
        }
      }
    }

    return false;
  }

  public function verificarSelecaoBlocoAssinatura(DocumentoDTO $objDocumentoDTO){

    //exclui sigilosos
    //exclui cancelados
    //documentos/formulários gerados
    //unidade geradora igual a unidade atual

    return ($objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO &&
            $objDocumentoDTO->getStrStaEstadoProtocolo() != ProtocoloRN::$TE_DOCUMENTO_CANCELADO  &&
           ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO || $objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_GERADO) &&
            $objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()==SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  }

  public function verificarSelecaoAcessoBasico(DocumentoDTO $objDocumentoDTO){

    //exclui cancelados
    //documentos externos
    //formularios
    //documentos gerados assinados ou publicados

    return ($objDocumentoDTO->getStrStaEstadoProtocolo() != ProtocoloRN::$TE_DOCUMENTO_CANCELADO
             &&
             (
                 $objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EXTERNO
                 ||
                 $objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_AUTOMATICO
                 ||
                 ($this->verificarConteudoGerado($objDocumentoDTO) && ($objDocumentoDTO->getStrSinAssinado()=='S' || $objDocumentoDTO->getStrSinPublicado()=='S'))
             )
           );
  }

  public function verificarSelecaoAssinaturaExterna(DocumentoDTO $objDocumentoDTO){

    //exclui cancelados
    //documentos assinados
    //documentos não publicados
    //internos ou edoc com conteudo

    return ($objDocumentoDTO->getStrStaEstadoProtocolo()!=ProtocoloRN::$TE_DOCUMENTO_CANCELADO &&
        //$objDocumentoDTO->getStrSinAssinado()=='S' &&
        $objDocumentoDTO->getStrSinPublicado()=='N' &&
        $this->verificarConteudoGerado($objDocumentoDTO));
  }

  public function verificarConteudoGerado(DocumentoDTO $objDocumentoDTO){
    //editor interno
    //editor edoc com conteudo
    return ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO ||
           ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_GERADO) ||
           ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC && $objDocumentoDTO->getDblIdDocumentoEdoc()!=null));
  }

  public function verificarSelecaoNotificacao(DocumentoDTO $objDocumentoDTO){

    //exclui cancelados
    //documento externos
    //gerados assinados ou publicados

    return ($objDocumentoDTO->getStrStaEstadoProtocolo() != ProtocoloRN::$TE_DOCUMENTO_CANCELADO  &&
        ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO || ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO && ($objDocumentoDTO->getStrSinAssinado()=='S' || $objDocumentoDTO->getStrSinPublicado()=='S'))));
  }

  protected function obterLinkAcessoControlado(LinkAcessoDTO $objLinkAcessoDTO){
    try{

      $objInfraException = new InfraException();

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retDblIdProtocolo();
      $objProtocoloDTO->retStrProtocoloFormatado();
      $objProtocoloDTO->retStrStaNivelAcessoGlobal();
      $objProtocoloDTO->setStrProtocoloFormatado($objLinkAcessoDTO->getStrProtocoloDocumentoFormatado());
      $objProtocoloDTO->setStrStaProtocolo(array(ProtocoloRN::$TP_DOCUMENTO_GERADO,ProtocoloRN::$TP_DOCUMENTO_RECEBIDO),InfraDTO::$OPER_IN);
      $objProtocoloDTO->setStrStaNivelAcessoGlobal(ProtocoloRN::$NA_SIGILOSO, InfraDTO::$OPER_DIFERENTE);

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

      if ($objProtocoloDTO==null){
        $objInfraException->lancarValidacao('Documento '.$objLinkAcessoDTO->getStrProtocoloDocumentoFormatado().' não encontrado.');
      }

      //if ($objProtocoloDTO->getStrStaNivelAcessoGlobal()!=ProtocoloRN::$NA_PUBLICO){
      //  $objInfraException->lancarValidacao('Documento '.$objLinkAcessoDTO->getStrProtocoloDocumentoFormatado().' não é público.');
      //}

      //obtem processo do documento
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
      $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo1();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProtocoloDTO->getDblIdProtocolo());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

      $dblIdProcedimento = $objRelProtocoloProtocoloDTO->getDblIdProtocolo1();
      $strProtocoloProcedimentoFormatado = $objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo1();

      $dblIdDocumento = $objProtocoloDTO->getDblIdProtocolo();
      $strProtocoloDocumentoFormatado = $objProtocoloDTO->getStrProtocoloFormatado();


      $objLinkAcessoDTO = new $objLinkAcessoDTO();
      $objLinkAcessoDTO->setDblIdProcedimento($dblIdProcedimento);
      $objLinkAcessoDTO->setStrProtocoloProcedimentoFormatado($strProtocoloProcedimentoFormatado);
      $objLinkAcessoDTO->setStrLinkProcesso(ConfiguracaoSEI::getInstance()->getValor('SEI','URL').'/controlador.php?acao=procedimento_trabalhar&id_procedimento='.$dblIdProcedimento);
      $objLinkAcessoDTO->setDblIdDocumento($dblIdDocumento);
      $objLinkAcessoDTO->setStrProtocoloDocumentoFormatado($strProtocoloDocumentoFormatado);
      $objLinkAcessoDTO->setStrLinkDocumento(ConfiguracaoSEI::getInstance()->getValor('SEI','URL').'/controlador.php?acao=procedimento_trabalhar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento);

      return $objLinkAcessoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro obtendo link de acesso.',$e);
    }
  }

  protected function obterDocumentoAutenticidadeConectado(DocumentoDTO $parObjDocumentoDTO){
    try{

      $objInfraException = new InfraException();

      $this->validarStrCodigoVerificador($parObjDocumentoDTO, $objInfraException);
      $this->validarStrCrcAssinatura($parObjDocumentoDTO, $objInfraException);

      $strCodigoVerificador = $this->prepararCodigoVerificador($parObjDocumentoDTO->getStrCodigoVerificador());

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrNomeSerie();
      $objDocumentoDTO->retStrNumero();
      $objDocumentoDTO->retStrCrcAssinatura();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retStrStaEstadoProtocolo();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrConteudoAssinatura();
      $objDocumentoDTO->retStrSinBloqueado();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrStaNivelAcessoGlobalProtocolo();
      $objDocumentoDTO->setStrProtocoloDocumentoFormatado($strCodigoVerificador);

      $objDocumentoDTO2 = $this->consultarRN0005($objDocumentoDTO);

      if ($objDocumentoDTO2==null){
        $objDocumentoDTO->unSetStrProtocoloDocumentoFormatado();
        $objDocumentoDTO->setDblIdDocumentoEdoc($strCodigoVerificador);
        $objDocumentoDTO2 = $this->consultarRN0005($objDocumentoDTO);
      }

      $objDocumentoDTO = $objDocumentoDTO2;

      if ($objDocumentoDTO==null){
        $objInfraException->lancarValidacao('Nenhum documento encontrado para o código verificador informado.');
      }

      if ($objDocumentoDTO->getStrStaEstadoProtocolo()==ProtocoloRN::$TE_DOCUMENTO_CANCELADO){
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->retDthAberturaAtividade();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
        $objAtributoAndamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_CANCELAMENTO_DOCUMENTO);
        $objAtributoAndamentoDTO->setDblIdProtocoloAtividade($objDocumentoDTO->getDblIdProcedimento());

        $objAtributoAndamentoRN = new AtributoAndamentoRN();
        $objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);

        $objInfraException->lancarValidacao('Este documento foi cancelado em '.$objAtributoAndamentoDTO->getDthAberturaAtividade().'.');
      }

      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){

        if (strtoupper(hash('crc32b',$objDocumentoDTO->getStrConteudoAssinatura())) != $parObjDocumentoDTO->getStrCrcAssinatura()){
          $objInfraException->lancarValidacao('O código CRC informado não confere com a última versão do documento.');
        }

        $objEditorDTO = new EditorDTO();
        $objEditorDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objEditorDTO->setNumIdBaseConhecimento(null);
        $objEditorDTO->setStrSinCabecalho('S');
        $objEditorDTO->setStrSinRodape('S');
        $objEditorDTO->setStrSinCarimboPublicacao('N');
        $objEditorDTO->setStrSinIdentificacaoVersao('N');
        $objEditorRN = new EditorRN();
        $objDocumentoDTO->setStrConteudo($objEditorRN->consultarHtmlVersao($objEditorDTO));

      }else if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC){


        if ($objDocumentoDTO->getStrCrcAssinatura() != $parObjDocumentoDTO->getStrCrcAssinatura()) {
          $objInfraException->lancarValidacao('O código CRC informado não confere com a última versão do documento.');
        }
        $objEDocRN = new EDocRN();
        $objDocumentoDTO->setDblIdDocumentoEdoc($strCodigoVerificador);
        $objDocumentoDTO->setStrConteudo($objEDocRN->consultarHTMLDocumentoRN1204($objDocumentoDTO));

      }else if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {

        $objAnexoDTO = new AnexoDTO();
        $objAnexoDTO->retNumIdAnexo();
        $objAnexoDTO->retDthInclusao();
        $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

        $objAnexoRN = new AnexoRN();
        $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);

        if ($objAnexoDTO==null){
          throw new InfraException('Documento não possui anexo associado.');
        }

        if (strtoupper(hash_file('crc32b', $objAnexoRN->obterLocalizacao($objAnexoDTO))) != $parObjDocumentoDTO->getStrCrcAssinatura()) {
          $objInfraException->lancarValidacao('O código CRC informado não confere com a última versão do documento.');
        }

      }else if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_GERADO){

        if (strtoupper(hash('crc32b',$objDocumentoDTO->getStrConteudoAssinatura())) != $parObjDocumentoDTO->getStrCrcAssinatura()) {
          $objInfraException->lancarValidacao('O código CRC informado não confere com a última versão do documento.');
        }

        $objDocumentoDTO->setStrConteudo($this->consultarHtmlFormulario($objDocumentoDTO));

      }else{
        $objInfraException->lancarValidacao('Nenhum documento encontrado para o código verificador informado.');
      }


      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->retNumIdAssinatura();
      $objAssinaturaDTO->retDblIdDocumento();
      $objAssinaturaDTO->retDblIdProcedimentoDocumento();
      $objAssinaturaDTO->retStrNome();
      $objAssinaturaDTO->retStrTratamento();
      $objAssinaturaDTO->retDblCpf();
      $objAssinaturaDTO->retStrNumeroSerieCertificado();
      $objAssinaturaDTO->retDthAberturaAtividade();
      $objAssinaturaDTO->retStrStaFormaAutenticacao();
      $objAssinaturaDTO->retStrSiglaUnidade();
      $objAssinaturaDTO->retStrDescricaoUnidade();
      $objAssinaturaDTO->retStrStaProtocoloProtocolo();
      $objAssinaturaDTO->retStrStaDocumentoDocumento();
      $objAssinaturaDTO->retStrP7sBase64();
      $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $objAssinaturaDTO->setOrdStrNomeUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objAssinaturaRN = new AssinaturaRN();
      $objDocumentoDTO->setArrObjAssinaturaDTO($objAssinaturaRN->listarRN1323($objAssinaturaDTO));

      return $objDocumentoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro obtendo documento para conferência de autentidade.',$e);
    }
  }

  protected function obterHashDocumentoAssinaturaConectado(AssinaturaDTO $parObjAssinaturaDTO){
    try{

      $ret = null;

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->setBolExclusaoLogica(false);
      $objAssinaturaDTO->retNumIdAssinatura();
      $objAssinaturaDTO->setNumIdAssinatura($parObjAssinaturaDTO->getNumIdAssinatura());
      $objAssinaturaDTO->setDblIdDocumento($parObjAssinaturaDTO->getDblIdDocumento());
      $objAssinaturaDTO->setStrSinAtivo('N');
      $objAssinaturaDTO->setNumMaxRegistrosRetorno(1);

      $objAssinaturaRN = new AssinaturaRN();
      if ($objAssinaturaRN->consultarRN1322($objAssinaturaDTO) == null){
        throw new InfraException('Assinatura pendente não encontrada.');
      }

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrConteudoAssinatura();
      $objDocumentoDTO->setDblIdDocumento($parObjAssinaturaDTO->getDblIdDocumento());

      $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

      if ($objDocumentoDTO==null){
        throw new InfraException('Documento para assinatura não encontrado.');
      }

      if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){

        $ret = hash('sha512', $objDocumentoDTO->getStrConteudoAssinatura(), true);

      }else{

        $objAnexoDTO = new AnexoDTO();
        $objAnexoDTO->retNumIdAnexo();
        $objAnexoDTO->retStrNome();
        $objAnexoDTO->retDblIdProtocolo();
        $objAnexoDTO->retDthInclusao();
        $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

        $objAnexoRN = new AnexoRN();
        $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);

        if ($objAnexoDTO==null){
          throw new InfraException('Anexo do documento para assinatura não encontrado.');
        }

        $ret = hash_file('sha512', $objAnexoRN->obterLocalizacao($objAnexoDTO), true);
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro obtendo documento para assinatura.',$e);
    }
  }

  private function ehUmaExtensaoDeImagemPermitida($strNomeArquivo){
    switch(InfraUtil::getStrMimeType($strNomeArquivo)){
      case 'image/jpeg':
        return true;
        break;
      case 'image/png':
        return true;
        break;
      case 'image/gif':
        return true;
        break;
      case 'image/bmp':
        return true;
        break;
      default:
        return false;

    }
  }

  private function processarArquivoOpenOffice($strNomeArquivo){

    if (!ConfiguracaoSEI::getInstance()->isSetValor('JODConverter','Servidor') || ConfiguracaoSEI::getInstance()->getValor('JODConverter','Servidor')==''){
      return false;
    }

    switch(InfraUtil::getStrMimeType($strNomeArquivo)){
      case 'text/csv':
      case 'application/msword':
      case 'application/vnd.oasis.opendocument.spreadsheet':
      case 'application/vnd.oasis.opendocument.text':
      case 'application/vnd.ms-powerpoint':
      case 'text/rtf':
      case 'application/vnd.ms-excel':
      case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
      case 'application/vnd.openxmlformats-officedocument.spreadsheetml.template':
      case 'application/vnd.openxmlformats-officedocument.presentationml.template':
      case 'application/vnd.openxmlformats-officedocument.presentationml.slideshow':
      case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
      case 'application/vnd.openxmlformats-officedocument.presentationml.slide':
      case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
      case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
      case 'application/vnd.ms-excel.addin.macroEnabled.12':
      case 'application/vnd.ms-excel.sheet.binary.macroEnabled.12':
      case 'application/vnd.oasis.opendocument.text-template':
      case 'application/vnd.oasis.opendocument.presentation':
        return true;
        break;
      default:
        return false;
    }
  }

  protected function gerarPdfConectado($varArrObjDocumentoDTO) {
    try {

      LimiteSEI::getInstance()->configurarNivel2();

      $objInfraException = new InfraException();

      if (!is_array($varArrObjDocumentoDTO)) {
        $varArrObjDocumentoDTO = array($varArrObjDocumentoDTO);
      }

      if (count($varArrObjDocumentoDTO)==0){
        $objInfraException->lancarValidacao('Nenhum documento selecionado para geração do PDF.');
      }

      $numMaxTempoPdfSeg = LimiteSEI::getInstance()->getNumTempo();
      $numMaxMemoriaPdfMb = LimiteSEI::getInstance()->getNumMemoria();


      $varArrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($varArrObjDocumentoDTO, 'IdDocumento');

      $arrIdDocumentos = array_keys($varArrObjDocumentoDTO);

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrNumero();
      $objDocumentoDTO->retStrNomeArvore();
      $objDocumentoDTO->retStrNomeSerie();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retDblIdDocumentoEdoc();
      //$objDocumentoDTO->retStrConteudo();
      $objDocumentoDTO->setDblIdDocumento($arrIdDocumentos, InfraDTO::$OPER_IN);

      $arrObjDocumentoDTO = $this->listarRN0008($objDocumentoDTO);

      if (count($arrObjDocumentoDTO)==0){
        throw new InfraException('Nenhum documento encontrado para geração de PDF.');
      }

      $arrObjDocumentoDTOAuditoria = array();
      foreach($arrObjDocumentoDTO as $objDocumentoDTO){
        $objDocumentoDTOAuditoria = new DocumentoDTO();
        $objDocumentoDTOAuditoria->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
        $objDocumentoDTOAuditoria->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objDocumentoDTOAuditoria->setStrProtocoloDocumentoFormatado($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $arrObjDocumentoDTOAuditoria[] = $objDocumentoDTOAuditoria;
      }

      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_gerar_pdf', __METHOD__, $arrObjDocumentoDTOAuditoria);

      unset($arrObjDocumentoDTOAuditoria);

      $strProtocoloProcedimentoFormatado = $arrObjDocumentoDTO[0]->getStrProtocoloProcedimentoFormatado();

      $arrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($arrObjDocumentoDTO,'IdDocumento');

      $strDocumentosGeracaoPdf = '';
      $arrComandoExecucao = array();
      $numParteArquivoPdf = 1;
      $arrArquivoPdfParcial = array();
      $arrArquivoTemp = array();
      $objAnexoRN = new AnexoRN();

      foreach($arrIdDocumentos as $dblIdDocumento){
        $objDocumentoDTO = $arrObjDocumentoDTO[$dblIdDocumento];

        if ($strDocumentosGeracaoPdf != ''){
          $strDocumentosGeracaoPdf .= ',';
        }

        $strProtocoloDocumento = $objDocumentoDTO->getStrProtocoloDocumentoFormatado();
        $strIdentificacaoDocumento = DocumentoINT::montarIdentificacaoArvore($objDocumentoDTO);

        //PDFBox não suporta alguns caracteres
        $numTamanhoIdentificacao = strlen($strIdentificacaoDocumento);
        for($i=0;$i<$numTamanhoIdentificacao;$i++){
          $numCodigoCaracter = ord($strIdentificacaoDocumento[$i]);
          if ($numCodigoCaracter==150 || $numCodigoCaracter==151){
            $strIdentificacaoDocumento[$i] = '-';
          }else if ($numCodigoCaracter==145 || $numCodigoCaracter==146){
            $strIdentificacaoDocumento[$i] = "'";
          }else if ($numCodigoCaracter==147 || $numCodigoCaracter==148){
            $strIdentificacaoDocumento[$i] = '"';
          }else if ($numCodigoCaracter==149){
          $strIdentificacaoDocumento[$i] = '*';
          }
        }

        $bolEscalaCinza = ($varArrObjDocumentoDTO[$dblIdDocumento]->isSetStrSinPdfEscalaCinza() && $varArrObjDocumentoDTO[$dblIdDocumento]->getStrSinPdfEscalaCinza()=='S');

        $strDocumentosGeracaoPdf .= base64_encode($strIdentificacaoDocumento);

        $strDocumento = '';
        if ($objDocumentoDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO){
          if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC){
            if ($objDocumentoDTO->getDblIdDocumentoEdoc()==null){
              $strDocumento .= 'Documento e-Doc '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado() .' não encontrado.';
              $objInfraException->adicionarValidacao('Documento e-Doc '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado() .' não encontrado.');
            }else{
              $strDocumento .= EDocINT::montarVisualizacaoDocumento($objDocumentoDTO->getDblIdDocumentoEdoc());
            }
          }else if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){

            $objEditorDTO = new EditorDTO();
            $objEditorDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
            $objEditorDTO->setNumIdBaseConhecimento(null);
            $objEditorDTO->setStrSinCabecalho('S');
            $objEditorDTO->setStrSinRodape('S');
            $objEditorDTO->setStrSinCarimboPublicacao('S');
            $objEditorDTO->setStrSinIdentificacaoVersao('N');

            $objEditorRN = new EditorRN();
            $strDocumento .= $objEditorRN->consultarHtmlVersao($objEditorDTO);
          }else{

            // email, por exemplo
            $strDocumento .= $this->consultarHtmlFormulario($objDocumentoDTO);
          }

          $strDocumento = preg_replace_callback('/(<table[^>^{]*width:[\'\"]?\s*)(\d+)\s*(px)?([^<^}]*>)/is', array($this, "ajustarLarguraTabela"), $strDocumento);

          $strArquivoHtmlTemp = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('.html');
          $arrArquivoTemp[] = $strArquivoHtmlTemp;
          if (file_put_contents($strArquivoHtmlTemp,$strDocumento) === false){
            throw new InfraException('Erro criando arquivo html temporário para criação de pdf.');
          }
          $strArquivoPdfParcial = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('-parte'.$numParteArquivoPdf++.'.pdf');
          $arrArquivoPdfParcial[] = $strArquivoPdfParcial;
          $arrComandoExecucao[] = array($strProtocoloDocumento, DocumentoRN::montarComandoGeracaoPdf($strArquivoHtmlTemp, $strArquivoPdfParcial, 'PDF '. $objDocumentoDTO->getStrProtocoloProcedimentoFormatado(), $bolEscalaCinza));

        }else if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){
          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO->retNumIdAnexo();
          $objAnexoDTO->retStrNome();
          $objAnexoDTO->retDthInclusao();
          $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
          $objAnexoRN = new AnexoRN();
          $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);
          if (InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'application/pdf' || InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'text/html' || InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'text/plain' || $this->ehUmaExtensaoDeImagemPermitida($objAnexoDTO->getStrNome()) || $this->processarArquivoOpenOffice($objAnexoDTO->getStrNome())){
            if ($objAnexoDTO==null){
              $objInfraException->adicionarValidacao('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado() .' não encontrado.');
            }else{

              if (InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'application/pdf'){
                $strArquivoPdfParcial = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('-parte'.$numParteArquivoPdf++.'.pdf');
                if (copy($objAnexoRN->obterLocalizacao($objAnexoDTO), $strArquivoPdfParcial) === false){
                  throw new InfraException('Erro criando arquivo pdf temporário para criação de pdf.');
                }
                $arrArquivoPdfParcial[] = $strArquivoPdfParcial;
              }else if (InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'text/html'){
                $strArquivoHtmlTemp = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('.html');
                $arrArquivoTemp[] = $strArquivoHtmlTemp;
                if (copy($objAnexoRN->obterLocalizacao($objAnexoDTO), $strArquivoHtmlTemp) === false){
                  throw new InfraException('Erro criando arquivo html temporário para criação de pdf.');
                }
                $this->prepararHtmlToPdf($strArquivoHtmlTemp);
                $strArquivoPdfParcial = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('-parte'.$numParteArquivoPdf++.'.pdf');
                $arrArquivoPdfParcial[] = $strArquivoPdfParcial;
                $arrComandoExecucao[] = array($strProtocoloDocumento, DocumentoRN::montarComandoGeracaoPdf($strArquivoHtmlTemp, $strArquivoPdfParcial, 'PDF '.$objDocumentoDTO->getStrProtocoloProcedimentoFormatado(), $bolEscalaCinza));

              }else if (InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'text/plain'){
                $strCaminhoCompletoArquivoTxt = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('.txt');
                if (copy($objAnexoRN->obterLocalizacao($objAnexoDTO), $strCaminhoCompletoArquivoTxt) === false){
                  throw new InfraException('Erro criando arquivo txt temporário para criação de pdf.');
                }
                $strArquivoPdfParcial = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('-parte'.$numParteArquivoPdf++.'.pdf');
                $arrArquivoPdfParcial[] = $strArquivoPdfParcial;
                $arrComandoExecucao[] = array($strProtocoloDocumento, DocumentoRN::montarComandoGeracaoPdf($strCaminhoCompletoArquivoTxt, $strArquivoPdfParcial, 'PDF '.$objDocumentoDTO->getStrProtocoloProcedimentoFormatado(), $bolEscalaCinza));

              }else if ($this->ehUmaExtensaoDeImagemPermitida($objAnexoDTO->getStrNome())){
                $ext = explode('.',$objAnexoDTO->getStrNome());
                $ext = strtolower($ext[count($ext)-1]);

                // criar html que contenha a imagem
                $strDocumentoHTML = "<html>\n<head>\n<title>Anexo Imagem</title>\n";
                $strDocumentoHTML .= "</head>\n<body>\n";
                //$strDocumentoHTML .= "<img src=\"". $strCaminhoCompletoArquivoImagem . "\">";
                $strDocumentoHTML .= "<img src=\"". 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($objAnexoRN->obterLocalizacao($objAnexoDTO))) . "\">";
                $strDocumentoHTML .= "</body>\n</html>";
                $strArquivoHtmlTemp = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('.html');
                $arrArquivoTemp[] = $strArquivoHtmlTemp;
                if (file_put_contents($strArquivoHtmlTemp,$strDocumentoHTML) === false){
                  throw new InfraException('Erro criando arquivo html com imagem temporário para criação de pdf.');
                }
                $strArquivoPdfParcial = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('-parte'.$numParteArquivoPdf++.'.pdf');
                $arrArquivoPdfParcial[] = $strArquivoPdfParcial;
                $arrComandoExecucao[] = array($strProtocoloDocumento, DocumentoRN::montarComandoGeracaoPdf($strArquivoHtmlTemp, $strArquivoPdfParcial, 'PDF '.$objDocumentoDTO->getStrProtocoloProcedimentoFormatado(), $bolEscalaCinza));

              }else if ($this->processarArquivoOpenOffice($objAnexoDTO->getStrNome())){
                $strCaminhoCompletoArquivoOpenOffice = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('.oo');
                $arrArquivoTemp[] = $strCaminhoCompletoArquivoOpenOffice;
                if (copy($objAnexoRN->obterLocalizacao($objAnexoDTO), $strCaminhoCompletoArquivoOpenOffice) === false){
                  throw new InfraException('Erro criando arquivo openoffice temporário para criação de pdf.');
                }
                $strArquivoPdfParcial = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario('-parte'.$numParteArquivoPdf++.'.pdf');
                $arrArquivoPdfParcial[] = $strArquivoPdfParcial;
                $arrComandoExecucao[] = array($strProtocoloDocumento, 'wget --no-proxy --quiet ' .ConfiguracaoSEI::getInstance()->getValor('JODConverter','Servidor') .' --post-file=' .$strCaminhoCompletoArquivoOpenOffice .' --header="Content-Type: ' .InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) .'" --header="Accept: application/pdf" --output-document=' .$strArquivoPdfParcial .' 2>&1');
              }
            }
          }
        }else{
          $objInfraException->adicionarValidacao('Não foi possível detectar o tipo do documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        }
      }

      $spec = array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w'));

      foreach($arrComandoExecucao as $arrComando){

        $objRecurso = proc_open($arrComando[1], $spec, $pipes);

        if (is_resource($objRecurso)) {

          $strSaidaPadrao = stream_get_contents($pipes[1]);
          $strSaidaErro = stream_get_contents($pipes[2]);

          fclose($pipes[1]);
          fclose($pipes[2]);

          $numRetorno = proc_close($objRecurso);

          if ($numRetorno !== 0) {
            throw new InfraException("Erro gerando PDF.\n\nNão foi possível converter o documento ".$arrComando[0].'.', null, "Comando: ".$arrComando[1]."\n\nValor Retorno: ".$numRetorno . "\n\nSaída padrão: $strSaidaPadrao\n\nSaída de erro: $strSaidaErro");
          }
        } else {
          throw new InfraException("Erro gerando PDF.\n\nNão foi possível converter o documento ".$arrComando[0].'.', null, "Comando: ".$arrComando[1]."\n\nErro gerando processo para o comando.");
        }
      }

      $objInfraException->lancarValidacoes();

      $strCaminhoCompletoArquivoPdfTotal = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario();

      $strComandoExecucao = 'LANG=pt_BR.iso-8859-1 ';

      if ($numMaxTempoPdfSeg > 0 && strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') {
        $strComandoExecucao .= ' timeout '.$numMaxTempoPdfSeg.'s';
      }

      $strComandoExecucao .= ' java -Dpdfbox.fontcache='.DIR_SEI_TEMP.' '.($numMaxMemoriaPdfMb > 0 ? '-Xmx'.$numMaxMemoriaPdfMb.'m' : '').' -jar '.DIR_SEI_BIN.'/pdfboxmerge.jar -p '.$strProtocoloProcedimentoFormatado.' -o '.$strCaminhoCompletoArquivoPdfTotal.' -d '.$strDocumentosGeracaoPdf.' -i '.implode(',', $arrArquivoPdfParcial).' 2>&1';

      $numSegPdf = InfraUtil::verificarTempoProcessamento();
      $ret = shell_exec($strComandoExecucao);
      $numSegPdf = InfraUtil::verificarTempoProcessamento($numSegPdf);

      if ($ret != '') {
        if (preg_match('/<INFRA_VALIDACAO>(.*)<\/INFRA_VALIDACAO>/', $ret, $matches)) {
          LogSEI::getInstance()->gravar("Erro gerando PDF.\n\nComando:\n".$strComandoExecucao."\n\nRetorno:\n".$ret);
          $objInfraException = new InfraException();
          $objInfraException->lancarValidacao('Erro gerando PDF.\n\n'.$matches[1].'\n\nTente regerar o PDF sem o documento que apresentou problema.');
        } else {
          //LogSEI::getInstance()->gravar("Erro gerando PDF.\n\nComando:\n".$strComandoExecucao."\n\nRetorno:\n".$ret);
          throw new InfraException('Erro gerando PDF.', null, $strComandoExecucao."\n\nRetorno:\n".$ret);
        }
      }

      foreach($arrArquivoPdfParcial as $strArquivoPdfParcial){
        unlink($strArquivoPdfParcial);
      }

      if (!file_exists($strCaminhoCompletoArquivoPdfTotal)){
        if ((int)$numSegPdf >= $numMaxTempoPdfSeg){
          throw new InfraException('Tempo limite para geração do PDF esgotado.');
        }else{
          throw new InfraException('Não foi possível gerar o PDF.');
        }
      }

      foreach($arrArquivoTemp as $strArquivoTemp){
        unlink($strArquivoTemp);
      }

      if (file_exists($strCaminhoCompletoArquivoPdfTotal.'-watermarked.pdf')){
        unlink($strCaminhoCompletoArquivoPdfTotal.'-watermarked.pdf');
      }

      $objAnexoDTO = new AnexoDTO();
      $arrNomeArquivo = explode('/',$strCaminhoCompletoArquivoPdfTotal);
      $objAnexoDTO->setStrNome($arrNomeArquivo[count($arrNomeArquivo)-1]);

      return $objAnexoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro gerando pdf.',$e);
    }
  }

  private function prepararHtmlToPdf($strNomeArquivo){
    //contorna erro do wkhtmltopdf removendo numeros apos referencias para css e js
    $strHtml = file_get_contents($strNomeArquivo);
    $strHtml = preg_replace('/(<link href=\".*.css)\?.*?\"/','$1"', $strHtml);
    $strHtml = preg_replace('/(<script .*?src=\".*.js)\?.*?\"/','$1"', $strHtml);
    $strHtml = preg_replace_callback('/(<table[^>^{]*width:[\'\"]?\s*)(\d+)\s*(px)?([^<^}]*>)/is', array($this, "ajustarLarguraTabela"), $strHtml);
    file_put_contents($strNomeArquivo,$strHtml);
  }

  private function ajustarLarguraTabela($matches){
    if ($matches[2] > 660){
      return $matches[1].'100%'.$matches[4];
    }else{
      return $matches[0];
    }
  }

  protected function gerarZipConectado($parArrObjDocumentoDTO) {
    try{

      ini_set('max_execution_time','300');

      $objInfraException = new InfraException();

      $arrIdDocumentos = InfraArray::converterArrInfraDTO($parArrObjDocumentoDTO,'IdDocumento');

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrNumero();
      $objDocumentoDTO->retStrNomeSerie();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      //$objDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retDblIdDocumentoEdoc();
      //$objDocumentoDTO->retStrConteudo();
      $objDocumentoDTO->setDblIdDocumento($arrIdDocumentos, InfraDTO::$OPER_IN);

      $arrObjDocumentoDTO = $this->listarRN0008($objDocumentoDTO);

      if (count($arrObjDocumentoDTO)==0){
        throw new InfraException('Nenhum documento informado.');
      }

      $arrObjDocumentoDTOAuditoria = array();
      foreach($arrObjDocumentoDTO as $objDocumentoDTO){
        $objDocumentoDTOAuditoria = new DocumentoDTO();
        $objDocumentoDTOAuditoria->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
        $objDocumentoDTOAuditoria->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objDocumentoDTOAuditoria->setStrProtocoloDocumentoFormatado($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $arrObjDocumentoDTOAuditoria[] = $objDocumentoDTOAuditoria;
      }

      SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_gerar_zip', __METHOD__, $arrObjDocumentoDTOAuditoria);


      $objAnexoRN = new AnexoRN();
      $strCaminhoCompletoArquivoZip = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario();

      $zipFile= new ZipArchive();
      $zipFile->open($strCaminhoCompletoArquivoZip, ZIPARCHIVE::CREATE);

      $arrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($arrObjDocumentoDTO,'IdDocumento');
      $numCasas=floor(log10(count($arrObjDocumentoDTO)))+1;
      $numSequencial = 0;

      foreach($arrIdDocumentos as $dblIdDocumento){
        $numSequencial++;
        $numDocumento=str_pad($numSequencial, $numCasas, "0", STR_PAD_LEFT);
        $objDocumentoDTO = $arrObjDocumentoDTO[$dblIdDocumento];
        $strDocumento = '';
        if ($objDocumentoDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO){
          if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC){
            if ($objDocumentoDTO->getDblIdDocumentoEdoc()==null){
              $strDocumento .= 'Documento e-Doc '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado() .' não encontrado.';
              $objInfraException->adicionarValidacao('Documento e-Doc '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado() .' não encontrado.');
            }else{
              $strDocumento .= EDocINT::montarVisualizacaoDocumento($objDocumentoDTO->getDblIdDocumentoEdoc());
            }
          }else if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){
            $objEditorDTO = new EditorDTO();
            $objEditorDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
            $objEditorDTO->setNumIdBaseConhecimento(null);
            $objEditorDTO->setStrSinCabecalho('S');
            $objEditorDTO->setStrSinRodape('S');
            $objEditorDTO->setStrSinCarimboPublicacao('S');
            $objEditorDTO->setStrSinIdentificacaoVersao('N');

            $objEditorRN = new EditorRN();
            $strDocumento .= $objEditorRN->consultarHtmlVersao($objEditorDTO);
          }else{
            // email, por exemplo
            $strDocumento .= $this->consultarHtmlFormulario($objDocumentoDTO);
          }

          $strNomeArquivo = $objDocumentoDTO->getStrProtocoloDocumentoFormatado().'-'.$objDocumentoDTO->getStrNomeSerie();
          if (!InfraString::isBolVazia($objDocumentoDTO->getStrNumero())){
            $strNomeArquivo .= '-'.$objDocumentoDTO->getStrNumero();
          }
          $strNomeArquivo .='.html';

          if ($zipFile->addFromString('['.$numDocumento.']-'.InfraUtil::formatarNomeArquivo($strNomeArquivo),$strDocumento) === false){
            throw new InfraException('Erro adicionando conteúdo html ao zip.');
          }
        }else if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){
          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO->retNumIdAnexo();
          $objAnexoDTO->retStrNome();
          $objAnexoDTO->retDthInclusao();
          $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
          $objAnexoRN = new AnexoRN();
          $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);
          if ($objAnexoDTO==null){
            $objInfraException->adicionarValidacao('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado() .' não encontrado.');
          }else{
            $ext = explode('.',$objAnexoDTO->getStrNome());
            $ext = strtolower($ext[count($ext)-1]);
            $strNomeArquivo = $objDocumentoDTO->getStrProtocoloDocumentoFormatado().'-'.$objDocumentoDTO->getStrNomeSerie();
            if (!InfraString::isBolVazia($objDocumentoDTO->getStrNumero())){
              $strNomeArquivo .= '-'.$objDocumentoDTO->getStrNumero();
            }
            $strNomeArquivo .='.'.$ext;
            if ($zipFile->addFile($objAnexoRN->obterLocalizacao($objAnexoDTO),'['.$numDocumento.']-'.InfraUtil::formatarNomeArquivo($strNomeArquivo)) === false){
              throw new InfraException('Erro adicionando documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' ao zip.');
            }
          }
        }else{
          $objInfraException->adicionarValidacao('Não foi possível detectar o tipo do documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().'.');
        }
      }
      $objInfraException->lancarValidacoes();

      if ($zipFile->close() === false) {
        throw new InfraException('Não foi possível fechar arquivo zip.');
      }

      $objAnexoDTO = new AnexoDTO();
      $arrNomeArquivo = explode('/',$strCaminhoCompletoArquivoZip);
      $objAnexoDTO->setStrNome($arrNomeArquivo[count($arrNomeArquivo)-1]);

      return $objAnexoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro gerando zip.',$e);
    }
  }

  public function mover(MoverDocumentoDTO $objMoverDocumentoDTO){

    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $objRelProtocoloProtodoloDTO = $this->moverInterno($objMoverDocumentoDTO);

    $objIndexacaoDTO = new IndexacaoDTO();
    $objIndexacaoDTO->setArrIdProtocolos(array($objMoverDocumentoDTO->getDblIdDocumento()));
    $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_METADADOS);
    
    $objIndexacaoRN = new IndexacaoRN();
    $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);

    if (!$bolAcumulacaoPrevia){
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }

    return $objRelProtocoloProtodoloDTO;
        
  }
  
  protected function moverInternoControlado(MoverDocumentoDTO $objMoverDocumentoDTO){
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('documento_mover',__METHOD__,$objMoverDocumentoDTO);
  
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
      $objProtocoloDTOAtual->setDblIdProtocolo($objMoverDocumentoDTO->getDblIdProcedimentoOrigem());
  
      $objProtocoloDTOAtual = $objProtocoloRN->consultarRN0186($objProtocoloDTOAtual);
      
      if ($objProtocoloDTOAtual==null){
        throw new InfraException('Processo origem não encontrado.');
      }
  
      if($objProtocoloDTOAtual->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO){
        $objInfraException->lancarValidacao('Protocolo '.$objProtocoloDTOAtual->getStrProtocoloFormatado().' não é um processo.');
      }
  
      if($objProtocoloDTOAtual->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO){
        $objInfraException->lancarValidacao('Processo '.$objProtocoloDTOAtual->getStrProtocoloFormatado().' não pode ser sigiloso.');
      }

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->verificarEstadoProcedimento($objProtocoloDTOAtual);


      $objProtocoloDTODestino = new ProtocoloDTO();
      $objProtocoloDTODestino->retDblIdProtocolo();
      $objProtocoloDTODestino->retStrStaProtocolo();
      $objProtocoloDTODestino->retStrStaEstado();
      $objProtocoloDTODestino->retStrSinEliminado();
      $objProtocoloDTODestino->retStrProtocoloFormatado();
      $objProtocoloDTODestino->retStrStaNivelAcessoGlobal();
      $objProtocoloDTODestino->setDblIdProtocolo($objMoverDocumentoDTO->getDblIdProcedimentoDestino());
      	
      $objProtocoloDTODestino = $objProtocoloRN->consultarRN0186($objProtocoloDTODestino);
      	
      if ($objProtocoloDTODestino==null){
        throw new InfraException('Processo destino não encontrado.');
      }
      
      if($objProtocoloDTODestino->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO){
        $objInfraException->lancarValidacao('Protocolo '.$objProtocoloDTODestino->getStrProtocoloFormatado().' não é um processo.');
      }
      	
      if ($objProtocoloDTOAtual->getDblIdProtocolo() == $objProtocoloDTODestino->getDblIdProtocolo()){
        $objInfraException->lancarValidacao('Processo destino deve ser diferente do processo de origem.');
      }
      	
      if($objProtocoloDTODestino->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO){
        $objInfraException->lancarValidacao('Processo '.$objProtocoloDTODestino->getStrProtocoloFormatado().' não pode ser sigiloso.');
      }
      	
      if($objProtocoloDTODestino->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO){
        $objInfraException->lancarValidacao('Processo '.$objProtocoloDTODestino->getStrProtocoloFormatado().' está sobrestado.');
      }
      	
      if($objProtocoloDTODestino->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO){
        $objInfraException->lancarValidacao('Processo '.$objProtocoloDTODestino->getStrProtocoloFormatado().' não pode estar anexado a outro processo.');
      }

      $objProcedimentoRN->verificarEstadoProcedimento($objProtocoloDTODestino);
  
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->setDblIdDocumento($objMoverDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);
      
      if ($objDocumentoDTO==null){
        throw new InfraException('Documento não encontrado.');
      }
      
      if($objDocumentoDTO->getStrStaProtocoloProtocolo() != ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){
        $objInfraException->lancarValidacao('Somente documentos externos podem ser movidos.');
      }
      
      //muda o processo do documento
      $objDocumentoDTO2 = new DocumentoDTO();
      $objDocumentoDTO2->setDblIdProcedimento($objMoverDocumentoDTO->getDblIdProcedimentoDestino());
      $objDocumentoDTO2->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      
      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->alterar($objDocumentoDTO2);

      //muda o tipo da associacao do documento com o processo antigo
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objMoverDocumentoDTO->getDblIdProcedimentoOrigem());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objMoverDocumentoDTO->getDblIdDocumento());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
      $objRelProtocoloProtocoloDTOAtual = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

      if ($objRelProtocoloProtocoloDTOAtual==null){
        $objInfraException->lancarValidacao('Documento não está associado com o processo origem.');
      }

      $objRelProtocoloProtocoloDTOAtual->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO);
      $objRelProtocoloProtocoloRN->alterar($objRelProtocoloProtocoloDTOAtual);

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProcedimento($objMoverDocumentoDTO->getDblIdProcedimentoDestino());
      $numSequencia = $objProtocoloRN->obterSequencia($objProtocoloDTO);
      
      //Criar associação entre o documento e o processo novo
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo(null);
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objMoverDocumentoDTO->getDblIdProcedimentoDestino());
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objMoverDocumentoDTO->getDblIdDocumento());
      $objRelProtocoloProtocoloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objRelProtocoloProtocoloDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
      $objRelProtocoloProtocoloDTO->setNumSequencia($numSequencia);
      $objRelProtocoloProtocoloDTO->setDthAssociacao(InfraData::getStrDataHoraAtual());
      $objRelProtocoloProtocoloDTODestino = $objRelProtocoloProtocoloRN->cadastrarRN0839($objRelProtocoloProtocoloDTO);
      
      //recalcular nível de acesso do processo origem
      $objMudarNivelAcessoDTO = new MudarNivelAcessoDTO();
      $objMudarNivelAcessoDTO->setStrStaOperacao(ProtocoloRN::$TMN_MOVIMENTACAO);
      $objMudarNivelAcessoDTO->setDblIdProtocolo($objMoverDocumentoDTO->getDblIdProcedimentoOrigem());
      $objMudarNivelAcessoDTO->setStrStaNivel(null);
      $objProtocoloRN->mudarNivelAcesso($objMudarNivelAcessoDTO);      

      //recalcular nível de acesso do processo destino
      $objMudarNivelAcessoDTO = new MudarNivelAcessoDTO();
      $objMudarNivelAcessoDTO->setStrStaOperacao(ProtocoloRN::$TMN_MOVIMENTACAO);
      $objMudarNivelAcessoDTO->setDblIdProtocolo($objMoverDocumentoDTO->getDblIdProcedimentoDestino());
      $objMudarNivelAcessoDTO->setStrStaNivel(null);
      $objProtocoloRN->mudarNivelAcesso($objMudarNivelAcessoDTO);

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
      $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
      $objAtributoAndamentoDTO->setStrValor($objProtocoloDTODestino->getStrProtocoloFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTODestino->getDblIdProtocolo());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('MOTIVO');
      $objAtributoAndamentoDTO->setStrValor($objMoverDocumentoDTO->getStrMotivo());
      $objAtributoAndamentoDTO->setStrIdOrigem($objRelProtocoloProtocoloDTOAtual->getDblIdRelProtocoloProtocolo());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      
      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTOAtual->getDblIdProtocolo());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_DOCUMENTO_MOVIDO_PARA_PROCESSO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
      $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
      $objAtributoAndamentoDTO->setStrValor($objProtocoloDTOAtual->getStrProtocoloFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTOAtual->getDblIdProtocolo());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
       
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('MOTIVO');
      $objAtributoAndamentoDTO->setStrValor($objMoverDocumentoDTO->getStrMotivo());
      $objAtributoAndamentoDTO->setStrIdOrigem($objRelProtocoloProtocoloDTODestino->getDblIdRelProtocoloProtocolo());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
      
      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTODestino->getDblIdProtocolo());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_DOCUMENTO_MOVIDO_DO_PROCESSO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);


      if (count($SEI_MODULOS)) {
        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objDocumentoAPI->setNumeroProtocolo($objDocumentoDTO->getStrProtocoloDocumentoFormatado());

        $objProcedimentoAPIOrigem = new ProcedimentoAPI();
        $objProcedimentoAPIOrigem->setIdProcedimento($objProtocoloDTOAtual->getDblIdProtocolo());
        $objProcedimentoAPIOrigem->setNumeroProtocolo($objProtocoloDTOAtual->getStrProtocoloFormatado());

        $objProcedimentoAPIDestino = new ProcedimentoAPI();
        $objProcedimentoAPIDestino->setIdProcedimento($objProtocoloDTODestino->getDblIdProtocolo());
        $objProcedimentoAPIDestino->setNumeroProtocolo($objProtocoloDTODestino->getStrProtocoloFormatado());

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('moverDocumento', $objDocumentoAPI, $objProcedimentoAPIOrigem, $objProcedimentoAPIDestino);
        }
      }

      return $objRelProtocoloProtocoloDTOAtual;

    }catch(Exception $e){
      throw new InfraException('Erro movendo documento.',$e);
    }
  }

  protected function consultarHtmlFormularioConectado(DocumentoDTO $parObjDocumentoDTO){

    if (!$parObjDocumentoDTO->isSetObjInfraSessao()){
      $parObjDocumentoDTO->setObjInfraSessao(null);
    }

    if (!$parObjDocumentoDTO->isSetStrLinkDownload()){
      $parObjDocumentoDTO->setStrLinkDownload(null);
    }

    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoDTO->retDblIdDocumento();
    $objDocumentoDTO->retDblIdProcedimento();
    $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
    $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
    $objDocumentoDTO->retStrStaProtocoloProtocolo();
    $objDocumentoDTO->retStrNomeSerie();
    $objDocumentoDTO->retStrStaDocumento();
    $objDocumentoDTO->retStrSinBloqueado();
    $objDocumentoDTO->retStrConteudo();
    $objDocumentoDTO->retStrCrcAssinatura();
    $objDocumentoDTO->retStrQrCodeAssinatura();
    $objDocumentoDTO->retNumIdTipoFormulario();
    $objDocumentoDTO->retStrDescricaoTipoConferencia();
    $objDocumentoDTO->retStrStaNivelAcessoGlobalProtocolo();
    $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

    $objDocumentoRN = new DocumentoRN();
    $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

    if ($objDocumentoDTO==null){
      throw new InfraException('Documento não encontrado.');
    }

    if ($objDocumentoDTO->getStrStaDocumento()!=DocumentoRN::$TD_FORMULARIO_AUTOMATICO && $objDocumentoDTO->getStrStaDocumento()!=DocumentoRN::$TD_FORMULARIO_GERADO){
      throw new InfraException('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' não é um formulário.');
    }

    $objDocumentoRN->bloquearConsultado($objDocumentoDTO);

    $strHtml = '';
    $strHtml .= '<!DOCTYPE html>'."\n";
    $strHtml .= '<html lang="pt-br" >'."\n";
    $strHtml .= '<head>'."\n";
    $strHtml .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />'."\n";
    $strHtml .= '<title>'.DocumentoINT::montarTitulo($objDocumentoDTO).'</title>'."\n";
    $strHtml .= '<style type="text/css" >'."\n";
    $strHtml .= '*{ '."\n";
    $strHtml .= ' font-style:normal;'."\n";
    $strHtml .= ' font-weight:normal;'."\n";
    $strHtml .= ' color:black;'."\n";
    $strHtml .= '}'."\n\n";

    $strHtml .= 'body{'."\n";
    $strHtml .= ' font-size:10pt;'."\n";
    $strHtml .= ' font-family:Arial,Verdana,Helvetica,Sans-serif;'."\n";
    $strHtml .= ' text-align:left;'."\n";
    $strHtml .= ' overflow-y:scroll;'."\n";
    $strHtml .= '}'."\n\n";

    $strHtml .= '#titulo {'."\n";
    $strHtml .= ' padding: 2px 0;'."\n";
    $strHtml .= ' text-align:center;'."\n";
    $strHtml .= ' vertical-align:middle;'."\n";
    $strHtml .= ' width:100%;'."\n";
    $strHtml .= ' background-color:#dfdfdf;'."\n";
    $strHtml .= ' border-bottom: 4px solid white;'."\n";
    $strHtml .= ' overflow:hidden;'."\n";
    $strHtml .= '}'."\n\n";

    $strHtml .= '#titulo label {'."\n";
    $strHtml .= ' font-size:11pt;'."\n";
    $strHtml .= ' font-weight:bold;'."\n";
    $strHtml .= ' color:#666;'."\n";
    $strHtml .= ' background-color:#dfdfdf;'."\n";
    $strHtml .= '}'."\n\n";

    $strHtml .= 'div b {font-weight:bold;}'."\n";
    $strHtml .= 'div i {font-style:italic;}'."\n";

    $strHtml .= '</style>'."\n";
    $strHtml .= '</head>'."\n";
    $strHtml .= '<body>'."\n";
    $strHtml .= '<div id="titulo">'."\n";
    $strHtml .= '<label>'.$objDocumentoDTO->getStrNomeSerie().' - '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().'</label>'."\n";
    $strHtml .= '</div>'."\n";
    $strHtml .= '<div id="conteudo">'."\n";
    $strHtml .= DocumentoINT::formatarExibicaoConteudo(DocumentoINT::$TV_HTML, $objDocumentoDTO->getStrConteudo(), $parObjDocumentoDTO->getObjInfraSessao(), $parObjDocumentoDTO->getStrLinkDownload());
    $strHtml .= '</div>'."\n";
    $strHtml .= '<br />'."\n";

    if ($objDocumentoDTO->getNumIdTipoFormulario()!=null) {

      $objAssinaturaRN = new AssinaturaRN();
      $strHtmlAssinaturas = $objAssinaturaRN->montarTarjas($objDocumentoDTO);

      if ($strHtmlAssinaturas != ''){
        $strHtml .= '<div id="assinaturas">'."\n";
        $strHtml .= $strHtmlAssinaturas;
        $strHtml .= '</div>'."\n";
      }

    }

    $strHtml .= '</body>'."\n";
    $strHtml .= '</html>'."\n";

    if ($parObjDocumentoDTO->isSetStrSinValidarXss() && $parObjDocumentoDTO->getStrSinValidarXss()=='S') {
      SeiINT::validarXss($strHtml, false, false, $objDocumentoDTO->getStrProtocoloDocumentoFormatado(), $objDocumentoDTO->getDblIdDocumento());
    }

    return $strHtml;
  }

  private function montarConteudoFormulario($arrObjRelProtocoloAtributoDTO){
    try{

      $ret = null;

      if (InfraArray::contar($arrObjRelProtocoloAtributoDTO)) {

        $arrIdAtributos = InfraArray::converterArrInfraDTO($arrObjRelProtocoloAtributoDTO, 'IdAtributo');

        $objAtributoDTO = new AtributoDTO();
        $objAtributoDTO->setBolExclusaoLogica(false);
        $objAtributoDTO->retNumIdAtributo();
        $objAtributoDTO->retStrNome();
        $objAtributoDTO->retStrRotulo();
        $objAtributoDTO->retNumOrdem();
        $objAtributoDTO->retStrStaTipo();
        $objAtributoDTO->setNumIdAtributo($arrIdAtributos, InfraDTO::$OPER_IN);
        $objAtributoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objAtributoDTO->setOrdStrRotulo(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objAtributoRN = new AtributoRN();
        $arrObjAtributoDTO = $objAtributoRN->listarRN0165($objAtributoDTO);

        $objDominioDTO = new DominioDTO();
        $objDominioDTO->setBolExclusaoLogica(false);
        $objDominioDTO->retNumIdDominio();
        $objDominioDTO->retNumIdAtributo();
        $objDominioDTO->retStrRotulo();
        $objDominioDTO->retStrValor();
        $objDominioDTO->setNumIdAtributo($arrIdAtributos, InfraDTO::$OPER_IN);

        $objDominioRN = new DominioRN();
        $arrObjDominioDTO = InfraArray::indexarArrInfraDTO($objDominioRN->listarRN0199($objDominioDTO),'IdAtributo',true);

        $ret = '';
        $ret .= '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
        $ret .= '<formulario>' . "\n";

        foreach($arrObjAtributoDTO as $objAtributoDTO){

          foreach ($arrObjRelProtocoloAtributoDTO as $objRelProtocoloAtributoDTO) {

            if ($objAtributoDTO->getNumIdAtributo()==$objRelProtocoloAtributoDTO->getNumIdAtributo()) {

              $ret .= '<atributo id="' . $objAtributoDTO->getNumIdAtributo() . '" nome="' . InfraString::formatarXML($objAtributoDTO->getStrNome()) . '" tipo="'.$objAtributoDTO->getStrStaTipo().'">'."\n";

              $ret .= '<rotulo>' . InfraString::formatarXML($objAtributoDTO->getStrRotulo()) . '</rotulo>'."\n";

              if ($objRelProtocoloAtributoDTO->getStrValor() != null) {

                if ($objAtributoDTO->getStrStaTipo() == AtributoRN::$TA_LISTA || $objAtributoDTO->getStrStaTipo() == AtributoRN::$TA_OPCOES) {

                  $objDominioDTOUtilizado = null;
                  foreach ($arrObjDominioDTO[$objAtributoDTO->getNumIdAtributo()] as $objDominioDTO) {
                    if ($objDominioDTO->getStrValor() == $objRelProtocoloAtributoDTO->getStrValor()) {
                      $objDominioDTOUtilizado = $objDominioDTO;
                      break;
                    }
                  }

                  if ($objDominioDTOUtilizado!=null){
                    $ret .= '<dominio id="'.$objDominioDTOUtilizado->getNumIdDominio().'" valor="'.InfraString::formatarXML($objDominioDTOUtilizado->getStrValor()).'">'.InfraString::formatarXML($objDominioDTOUtilizado->getStrRotulo()).'</dominio>'."\n";
                  }

                } else {
                  $ret .= '<valor>'.InfraString::formatarXML($objRelProtocoloAtributoDTO->getStrValor()).'</valor>'."\n";
                }
              }
              $ret .= '</atributo>' . "\n";

              break;
            }
          }
        }
        $ret .= '</formulario>';

        $ret = InfraUtil::filtrarISO88591($ret);
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro montando conteúdo do Formulário.',$e);
    }
  }

  protected function verificarSobrestamento(DocumentoDTO $objDocumentoDTO){
    try{

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retStrStaEstado();
      $objProtocoloDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

      if ($objProtocoloDTO->getStrStaEstado()==ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO){
        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objDocumentoDTO->getDblIdProcedimento());

        $objProcedimentoRN = new ProcedimentoRN();
        $objProcedimentoRN->removerSobrestamentoRN1017(array($objRelProtocoloProtocoloDTO));
      }

    }catch(Exception $e){
      throw new InfraException('Erro verificando sobrestamento do processo.',$e);
    }
  }

  protected function configurarDocumentoEdocRN1175Controlado(DocumentoDTO $parObjDocumentoDTO){
    try{

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retDblIdDocumentoEdoc();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

      $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

      if ($objDocumentoDTO->getDblIdDocumentoEdoc()!=null){
        throw new InfraException('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' já possui documento associado no eDoc.');
      }


      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setDblIdDocumentoEdoc($parObjDocumentoDTO->getDblIdDocumentoEdoc());
      $objDocumentoDTO->setStrConteudo($parObjDocumentoDTO->getStrConteudo());
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->alterar($objDocumentoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro configurando documento do eDoc.',$e);
    }
  }

  public function gerarDocumentoCircular(DocumentoCircularDTO $objDocumentoCircularDTO){

    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $ret = $this->gerarDocumentoCircularInterno($objDocumentoCircularDTO);

    if (!$bolAcumulacaoPrevia){
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }

    return $ret;
  }

  protected function gerarDocumentoCircularInternoControlado(DocumentoCircularDTO $objDocumentoCircularDTO){
    try{

      $ret = array();

      $objInfraException = new InfraException();

      $objDocumentoDTO = new DocumentoDTO();
      //$objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retNumIdSerie();
      $objDocumentoDTO->retStrSinDestinatarioSerie();
      $objDocumentoDTO->retStrNumero();
      $objDocumentoDTO->retStrNomeArvore();
      $objDocumentoDTO->setDblIdDocumento($objDocumentoCircularDTO->getDblIdDocumento());

      $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

      //if ($objDocumentoDTO->getStrStaDocumento() != DocumentoRN::$TD_EDITOR_INTERNO) {
      //  $objInfraException->lancarValidacao(('Somente documentos internos podem ser usados na geração de circular.');
      //}

      if ($objDocumentoDTO->getStrSinDestinatarioSerie()=='N'){
        $objInfraException->lancarValidacao('Tipo do documento não permite destinatários.');
      }

      $arrIdDestinatario = $objDocumentoCircularDTO->getArrNumIdDestinatario();

      if (InfraArray::contar($arrIdDestinatario)==0){
        $objInfraException->lancarValidacao('Nenhum destinatário informado.');
      }

      $objInfraException->lancarValidacoes();

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setBolExclusaoLogica(false);
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retStrExpressaoTratamentoCargo();
      $objContatoDTO->retStrExpressaoCargo();
      $objContatoDTO->retStrNome();
      $objContatoDTO->setNumIdContato($arrIdDestinatario,InfraDTO::$OPER_IN);

      $objContatoRN = new ContatoRN();
      $arrObjContatoDTO = InfraArray::indexarArrInfraDTO($objContatoRN->listarRN0325($objContatoDTO),'IdContato');

      $arrObjRelBlocoProtocoloDTO = array();

      foreach($arrIdDestinatario as $numIdDestinatario){

        $objDocumentoClonarDTO = new DocumentoDTO();
        $objDocumentoClonarDTO->setDblIdDocumento($objDocumentoCircularDTO->getDblIdDocumento());
        $objDocumentoClonarDTO->setDblIdProcedimento($objDocumentoCircularDTO->getDblIdProcedimento());
        $objDocumentoClonarDTO = $this->prepararCloneRN1110($objDocumentoClonarDTO);

        if ($objDocumentoDTO->getStrNumero()!=null && $objDocumentoClonarDTO->getStrNumero()==null){
          $objDocumentoClonarDTO->setStrNumero($objDocumentoDTO->getStrNumero());
        }
        if ($objDocumentoDTO->getStrNomeArvore()!=null && $objDocumentoClonarDTO->getStrNomeArvore()==null){
          $objDocumentoClonarDTO->setStrNomeArvore($objDocumentoDTO->getStrNomeArvore());
        }

        $arrObjParticipanteDTOOriginal = $objDocumentoClonarDTO->getObjProtocoloDTO()->getArrObjParticipanteDTO();
        $arrObjParticipanteDTOFiltrado = array();
        foreach($arrObjParticipanteDTOOriginal as $objParticipanteDTO){
          if ($objParticipanteDTO->getStrStaParticipacao()==ParticipanteRN::$TP_INTERESSADO || $objParticipanteDTO->getStrStaParticipacao()==ParticipanteRN::$TP_REMETENTE){
            $arrObjParticipanteDTOFiltrado[] = $objParticipanteDTO;
          }
        }

        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->setNumIdContato($numIdDestinatario);
        $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_DESTINATARIO);
        $objParticipanteDTO->setNumSequencia(0);

        $arrObjParticipanteDTOFiltrado[] = $objParticipanteDTO;

        $objDocumentoClonarDTO->getObjProtocoloDTO()->setArrObjParticipanteDTO($arrObjParticipanteDTOFiltrado);

        $objDocumentoDTONovo = $this->cadastrarRN0003($objDocumentoClonarDTO);

        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo(null);
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objDocumentoCircularDTO->getDblIdDocumento());
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objDocumentoDTONovo->getDblIdDocumento());
        $objRelProtocoloProtocoloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objRelProtocoloProtocoloDTO->setNumIdUnidade (SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objRelProtocoloProtocoloDTO->setNumSequencia(0);
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_CIRCULAR);
        $objRelProtocoloProtocoloDTO->setDthAssociacao(InfraData::getStrDataHoraAtual());

        $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
        $objRelProtocoloProtocoloRN->cadastrarRN0839($objRelProtocoloProtocoloDTO);

        if (!InfraString::isBolVazia($objDocumentoCircularDTO->getNumIdBloco())) {

          $objContatoDTO = $arrObjContatoDTO[$numIdDestinatario];

          $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
          $objRelBlocoProtocoloDTO->setNumIdBloco($objDocumentoCircularDTO->getNumIdBloco());
          $objRelBlocoProtocoloDTO->setDblIdProtocolo($objDocumentoDTONovo->getDblIdDocumento());
          $objRelBlocoProtocoloDTO->setStrAnotacao(($objContatoDTO->getStrExpressaoTratamentoCargo()!=null?$objContatoDTO->getStrExpressaoTratamentoCargo().' ':'').$objContatoDTO->getStrNome().($objContatoDTO->getStrExpressaoCargo()!=null?' ('.$objContatoDTO->getStrExpressaoCargo().')':''));
          $arrObjRelBlocoProtocoloDTO[] = $objRelBlocoProtocoloDTO;
        }

        $ret[] = $objDocumentoDTONovo;
      }

      if (count($arrObjRelBlocoProtocoloDTO)) {
        $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
        $objRelBlocoProtocoloRN->cadastrarMultiplo($arrObjRelBlocoProtocoloDTO);
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro gerando documento circular.',$e);
    }
  }

  protected function listarDocumentoCircularConectado(DocumentoDTO $parObjDocumentoDTO){
    try{

      $ret = array();

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_CIRCULAR);
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjDocumentoDTO->getDblIdDocumento());

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $arrIdDocumentosCirculares = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO),'IdProtocolo2');

      if (count($arrIdDocumentosCirculares)){

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retStrNomeSerie();
        $objDocumentoDTO->retStrNumero();
        $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
        $objDocumentoDTO->setDblIdDocumento($arrIdDocumentosCirculares,InfraDTO::$OPER_IN);

        $objDocumentoRN = new DocumentoRN();
        $arrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO),'IdDocumento');

        $arrObjDocumentoCircularDTO = array();
        foreach($arrObjDocumentoDTO as $objDocumentoDTO){
          $objDocumentoCircularDTO = new DocumentoCircularDTO();
          $objDocumentoCircularDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
          $objDocumentoCircularDTO->setStrNomeSerie($objDocumentoDTO->getStrNomeSerie());
          $objDocumentoCircularDTO->setStrNumero($objDocumentoDTO->getStrNumero());
          $objDocumentoCircularDTO->setStrProtocoloDocumentoFormatado($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
          $arrObjDocumentoCircularDTO[$objDocumentoDTO->getDblIdDocumento()] = $objDocumentoCircularDTO;
        }


        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->retStrValor();
        $objAtributoAndamentoDTO->retStrIdOrigem();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO_CIRCULAR');
        $objAtributoAndamentoDTO->setStrIdOrigem($arrIdDocumentosCirculares,InfraDTO::$OPER_IN);

        $objAtributoAndamentoRN = new AtributoAndamentoRN();
        $arrObjAtributoAndamentoDTO = $objAtributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);

        if (count($arrObjAtributoAndamentoDTO)){
          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->retDblIdDocumento();
          $objDocumentoDTO->retStrNomeSerie();
          $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
          $objDocumentoDTO->setDblIdDocumento(InfraArray::converterArrInfraDTO($arrObjAtributoAndamentoDTO,'Valor'),InfraDTO::$OPER_IN);
          $arrObjDocumentoDTOEmail = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO),'IdDocumento');
        }else{
          $arrObjDocumentoDTOEmail = array();
        }

        $arrObjAtributoAndamentoDTO = InfraArray::indexarArrInfraDTO($arrObjAtributoAndamentoDTO, 'IdOrigem', true);

        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retDblIdProtocolo();
        $objParticipanteDTO->retStrNomeContato();
        $objParticipanteDTO->retStrEmailContato();
        $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_DESTINATARIO);
        $objParticipanteDTO->setDblIdProtocolo($arrIdDocumentosCirculares, InfraDTO::$OPER_IN);
        $objParticipanteDTO->setOrdStrNomeContato(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objParticipanteRN = new ParticipanteRN();
        $arrObjParticipanteDTO = InfraArray::indexarArrInfraDTO($objParticipanteRN->listarRN0189($objParticipanteDTO), 'IdProtocolo', true);

        //ordena retorno pelo nome do destinatário
        foreach($arrObjParticipanteDTO as $dblIdProtocolo => $arrObjParticipanteDTOProtocolo){

          $arrObjDocumentoCircularDTO[$dblIdProtocolo]->setArrObjParticipanteDTO($arrObjParticipanteDTOProtocolo);
          $ret[$dblIdProtocolo] = $arrObjDocumentoCircularDTO[$dblIdProtocolo];
        }


        $objAssinaturaDTO = new AssinaturaDTO();
        $objAssinaturaDTO->retDblIdDocumento();
        $objAssinaturaDTO->setDblIdDocumento($arrIdDocumentosCirculares, InfraDTO::$OPER_IN);

        $objAssinaturaRN = new AssinaturaRN();
        $arrObjAssinaturaDTO = InfraArray::indexarArrInfraDTO($objAssinaturaRN->listarRN1323($objAssinaturaDTO),'IdDocumento',true);


        //adiciona documentos sem destinatarios cadastrados no fim (se existirem)
        foreach($arrObjDocumentoCircularDTO as $objDocumentoCircularDTO){

          $dblIdDocumentoCircular = $objDocumentoCircularDTO->getDblIdDocumento();

          if (!$objDocumentoCircularDTO->isSetArrObjParticipanteDTO()){
            $objDocumentoCircularDTO->setArrObjParticipanteDTO(array());
            $ret[$dblIdDocumentoCircular] = $objDocumentoCircularDTO;
          }

          $arr = array();
          if (isset($arrObjAtributoAndamentoDTO[$dblIdDocumentoCircular])) {
            foreach($arrObjAtributoAndamentoDTO[$dblIdDocumentoCircular] as $objAtributoAndamentoDTO){
              $arr[] = $arrObjDocumentoDTOEmail[$objAtributoAndamentoDTO->getStrValor()];
            }
          }
          $objDocumentoCircularDTO->setArrObjDocumentoDTOEmail($arr);

          if (isset($arrObjAssinaturaDTO[$dblIdDocumentoCircular])){
            $objDocumentoCircularDTO->setStrSinAssinado('S');
          }else{
            $objDocumentoCircularDTO->setStrSinAssinado('N');
          }
        }
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando documento circular.',$e);
    }
  }

  protected function listarSelecaoConectado(DocumentoDTO $parObjDocumentoDTO){
    try{

      $ret = array();

      if (!$parObjDocumentoDTO->isSetStrSinEmail()){
        $parObjDocumentoDTO->setStrSinEmail('N');
      }

      if (!$parObjDocumentoDTO->isSetStrSinPdf()){
        $parObjDocumentoDTO->setStrSinPdf('N');
      }

      if (!$parObjDocumentoDTO->isSetStrSinZip()){
        $parObjDocumentoDTO->setStrSinZip('N');
      }

      $numPaginaAtual = $parObjDocumentoDTO->getNumPaginaAtual();
      $numRegistrosPagina = $parObjDocumentoDTO->getNumMaxRegistrosRetorno();

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->retStrStaProtocoloProtocolo2();
      $objRelProtocoloProtocoloDTO->retNumSequencia();
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjDocumentoDTO->getDblIdProcedimento());
      $objRelProtocoloProtocoloDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

      if ($parObjDocumentoDTO->isSetDblIdDocumento()){
        if (!is_array($parObjDocumentoDTO->getDblIdDocumento())){
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjDocumentoDTO->getDblIdDocumento());
        }else{
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjDocumentoDTO->getDblIdDocumento(), InfraDTO::$OPER_IN);
        }
      }

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $arrObjRelProtocoloProtocoloDTO = InfraArray::indexarArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO),'IdProtocolo2');

      if (count($arrObjRelProtocoloProtocoloDTO)) {

        if ($parObjDocumentoDTO->isSetStrProtocoloDocumentoFormatado() || $parObjDocumentoDTO->isSetNumIdSerie() || $parObjDocumentoDTO->isSetNumIdUnidadeGeradoraProtocolo()) {

          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->retDblIdDocumento();

          if ($parObjDocumentoDTO->isSetStrProtocoloDocumentoFormatado()) {

            $arrProtocoloFormatado = explode(',', $parObjDocumentoDTO->getStrProtocoloDocumentoFormatado());

            $arrPesquisa = array();
            foreach ($arrProtocoloFormatado as $strProtocoloFormatado) {
              $strProtocoloFormatado = InfraUtil::retirarFormatacao($strProtocoloFormatado);
              if (is_numeric($strProtocoloFormatado)) {
                $arrPesquisa[] = $strProtocoloFormatado;
              }
            }

            if (count($arrPesquisa)) {
              if (count($arrPesquisa) == 1) {
                $objDocumentoDTO->setStrProtocoloDocumentoFormatado($arrPesquisa[0]);
              } else {
                $objDocumentoDTO->adicionarCriterio(array_fill(0, count($arrPesquisa), 'ProtocoloDocumentoFormatado'),
                  array_fill(0, count($arrPesquisa), InfraDTO::$OPER_IGUAL),
                  $arrPesquisa,
                  array_fill(0, count($arrPesquisa) - 1, InfraDTO::$OPER_LOGICO_OR));
              }
            }
          }

          if ($parObjDocumentoDTO->isSetNumIdSerie()) {
            $objDocumentoDTO->setNumIdSerie($parObjDocumentoDTO->getNumIdSerie(), InfraDTO::$OPER_IN);
          }

          if ($parObjDocumentoDTO->isSetNumIdUnidadeGeradoraProtocolo()) {
            $objDocumentoDTO->setNumIdUnidadeGeradoraProtocolo($parObjDocumentoDTO->getNumIdUnidadeGeradoraProtocolo(), InfraDTO::$OPER_IN);
          }

          $objDocumentoRN = new DocumentoRN();

          $arrIdDocumentosTotal = array_chunk(array_keys($arrObjRelProtocoloProtocoloDTO), 500);
          $arrIdDocumentosProcesso = array();
          foreach ($arrIdDocumentosTotal as $arrIdDocumentosPartes) {
            $objDocumentoDTO->setDblIdDocumento($arrIdDocumentosPartes, InfraDTO::$OPER_IN);
            $arrIdDocumentosProcesso = array_merge($arrIdDocumentosProcesso, InfraArray::converterArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO), 'IdDocumento'));
          }

        }else {
          $arrIdDocumentosProcesso = array_keys($arrObjRelProtocoloProtocoloDTO);
        }

        $arrIdDocumentosRecebidos = array();
        foreach ($arrIdDocumentosProcesso as $dblIdDocumento) {
          if ($arrObjRelProtocoloProtocoloDTO[$dblIdDocumento]->getStrStaProtocoloProtocolo2() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
            $arrIdDocumentosRecebidos[] = $arrObjRelProtocoloProtocoloDTO[$dblIdDocumento]->getDblIdProtocolo2();
          }
        }

        $arrObjAnexoDTO = array();
        if (count($arrIdDocumentosRecebidos)) {
          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO->retDblIdProtocolo();
          $objAnexoDTO->retStrNome();
          $objAnexoDTO->setDblIdProtocolo($arrIdDocumentosRecebidos, InfraDTO::$OPER_IN);

          $objAnexoRN = new AnexoRN();
          $arrObjAnexoDTO = InfraArray::indexarArrInfraDTO($objAnexoRN->listarRN0218($objAnexoDTO), 'IdProtocolo');

          $arrIdDocumentosComAnexo = array_keys($arrObjAnexoDTO);
          $arrIdDocumentosSemAnexo = array_diff($arrIdDocumentosRecebidos, $arrIdDocumentosComAnexo);
          $arrIdDocumentosProcesso = array_diff($arrIdDocumentosProcesso, $arrIdDocumentosSemAnexo);

          unset($arrIdDocumentosComAnexo);
          unset($arrIdDocumentosSemAnexo);
        }

        $arrObjDocumentoDTOSelecionados = array();

        if (count($arrIdDocumentosProcesso)) {

          $arrIdDocumentosProcesso = array_chunk($arrIdDocumentosProcesso, 500);

          $objProtocoloRN = new ProtocoloRN();

          foreach ($arrIdDocumentosProcesso as $arrPaginaProcesso) {

            $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
            $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
            $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
            $objPesquisaProtocoloDTO->setDblIdProtocolo($arrPaginaProcesso);
            $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

            foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {

              $objDocumentoDTO = new DocumentoDTO();
              $objDocumentoDTO->setDblIdDocumento($objProtocoloDTO->getDblIdProtocolo());
              $objDocumentoDTO->setStrStaProtocoloProtocolo($objProtocoloDTO->getStrStaProtocolo());
              $objDocumentoDTO->setStrStaEstadoProtocolo($objProtocoloDTO->getStrStaEstado());
              $objDocumentoDTO->setStrStaDocumento($objProtocoloDTO->getStrStaDocumentoDocumento());
              $objDocumentoDTO->setDblIdDocumentoEdoc($objProtocoloDTO->getDblIdDocumentoEdocDocumento());
              $objDocumentoDTO->setStrProtocoloDocumentoFormatado($objProtocoloDTO->getStrProtocoloFormatado());
              $objDocumentoDTO->setStrNomeSerie($objProtocoloDTO->getStrNomeSerieDocumento());
              $objDocumentoDTO->setStrNumero($objProtocoloDTO->getStrNumeroDocumento());
              $objDocumentoDTO->setStrNomeArvore($objProtocoloDTO->getStrNomeArvoreDocumento());
              $objDocumentoDTO->setDtaGeracaoProtocolo($objProtocoloDTO->getDtaGeracao());
              $objDocumentoDTO->setNumIdUnidadeGeradoraProtocolo($objProtocoloDTO->getNumIdUnidadeGeradora());
              $objDocumentoDTO->setStrSiglaUnidadeGeradoraProtocolo($objProtocoloDTO->getStrSiglaUnidadeGeradora());
              $objDocumentoDTO->setStrDescricaoUnidadeGeradoraProtocolo($objProtocoloDTO->getStrDescricaoUnidadeGeradora());
              $objDocumentoDTO->setStrSinBloqueado($objProtocoloDTO->getStrSinBloqueadoDocumento());

              if ($objProtocoloDTO->isSetStrSinAssinado()) {
                $objDocumentoDTO->setStrSinAssinado($objProtocoloDTO->getStrSinAssinado());
              }

              if ($objProtocoloDTO->isSetStrSinPublicado()) {
                $objDocumentoDTO->setStrSinPublicado($objProtocoloDTO->getStrSinPublicado());
              }

              if (isset($arrObjAnexoDTO[$objProtocoloDTO->getDblIdProtocolo()])){
                $objDocumentoDTO->setObjAnexoDTO($arrObjAnexoDTO[$objProtocoloDTO->getDblIdProtocolo()]);
              }else{
                $objDocumentoDTO->setObjAnexoDTO(null);
              }

              $objDocumentoDTO->setNumCodigoAcesso($objProtocoloDTO->getNumCodigoAcesso());

              if (($parObjDocumentoDTO->getStrSinEmail()=='S' && $this->verificarSelecaoEmail($objDocumentoDTO)) ||
                ($parObjDocumentoDTO->getStrSinPdf()=='S' && $this->verificarSelecaoGeracaoPdf($objDocumentoDTO)) ||
                ($parObjDocumentoDTO->getStrSinZip()=='S' && $this->verificarSelecaoGeracaoZip($objDocumentoDTO))){

                $arrObjDocumentoDTOSelecionados[$objProtocoloDTO->getDblIdProtocolo()] = $objDocumentoDTO;
              }
            }
          }
        }

        $numTotalSelecionados = count($arrObjDocumentoDTOSelecionados);

        if ($numTotalSelecionados) {

          $n = 0;

          if ($numPaginaAtual !== null && $numRegistrosPagina !== null) {
            $inicio = $numPaginaAtual * $numRegistrosPagina;
            $fim = $inicio + $numRegistrosPagina;
          }else{
            $inicio = 0;
            $fim = $numTotalSelecionados;
          }

          foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {

            if (isset($arrObjDocumentoDTOSelecionados[$objRelProtocoloProtocoloDTO->getDblIdProtocolo2()])){

              if ($n >= $inicio && $n < $fim) {
                $ret[] = $arrObjDocumentoDTOSelecionados[$objRelProtocoloProtocoloDTO->getDblIdProtocolo2()];
              }

              $n++;
            }
          }
        }

        if ($numPaginaAtual !== null && $numRegistrosPagina !== null) {
          $parObjDocumentoDTO->setNumTotalRegistros($numTotalSelecionados);
          $parObjDocumentoDTO->setNumRegistrosPaginaAtual(count($ret));
        }
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando documentos para seleção.',$e);
    }
  }

  public function cancelar(DocumentoDTO $objDocumentoDTO){
    
    $bolAcumulacaoPrevia = FeedSEIProtocolos::getInstance()->isBolAcumularFeeds();

    FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

    $objIndexacaoDTO = new IndexacaoDTO();
    $objIndexacaoDTO->setArrIdProtocolos(array($objDocumentoDTO->getDblIdDocumento()));

    $objIndexacaoRN	= new IndexacaoRN();
    $objIndexacaoRN->prepararRemocaoProtocolo($objIndexacaoDTO);

    $this->cancelarInterno($objDocumentoDTO);

    if (!$bolAcumulacaoPrevia){
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();
    }
  }
  //validacao dos documentos para eliminacao
  protected function validarEliminacaoConectado(array $arrObjDocumentoDTO, InfraException $objInfraException){
    //variavel que contem os modulos
    global $SEI_MODULOS;
    //testa se tem documentos
    if(InfraArray::contar($arrObjDocumentoDTO)){
      //converte para os ids dos documentos
      $arrIdDocumento = InfraArray::converterArrInfraDTO($arrObjDocumentoDTO,"IdDocumento");
      //dto que contem mais informacoes para serem buscadas dos documentos
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->setDblIdDocumento($arrIdDocumento,InfraDTO::$OPER_IN);
      //array que conterá os documentos que serao passados aos modulos
      //sera passados apenas os que forem validados com sucesso no sei
      $arrObjDocumentoAPI = array();
      //lista os documentos
      $objDocumentoBD = new DocumentoBD(BancoSEI::getInstance());
      $arrObjDocumentoDTO = $objDocumentoBD->listar($objDocumentoDTO);

      $objProtocoloRN = new ProtocoloRN();
      $objPublicacaoRN = new PublicacaoRN();
      $objArquivamentoRN = new ArquivamentoRN();
      foreach ($arrObjDocumentoDTO as $objDocumentoDTO_Banco){

        $bolAdicionarAPI = true;

        /* adicionados tratamentos para permitir estas situações
        //se foi gerado
        if($objDocumentoDTO_Banco->getStrStaProtocoloProtocolo() == ProtocoloRN::$TPP_DOCUMENTOS_GERADOS) {
          //consulta se o documento foi publicado
          $objPublicacaoDTO = new PublicacaoDTO();
          $objPublicacaoDTO->setNumMaxRegistrosRetorno(1);
          $objPublicacaoDTO->retDblIdDocumento();
          $objPublicacaoDTO->setDblIdDocumento($objDocumentoDTO_Banco->getDblIdDocumento());

          //se foi publicado, nao pode ser eliminado
          if($objPublicacaoRN->consultarRN1044($objPublicacaoDTO)!=null){
            $objInfraException->adicionarValidacao("Documento " . $objDocumentoDTO_Banco->getStrProtocoloDocumentoFormatado() . " do processo ". $objDocumentoDTO_Banco->getStrProtocoloProcedimentoFormatado() . " foi publicado.");
            //esse documento nao será adicionado no array
            $bolAdicionarAPI = false;
          }
          //se foi recebido
        }else if($objDocumentoDTO_Banco->getStrStaProtocoloProtocolo() == ProtocoloRN::$TPP_DOCUMENTOS_RECEBIDOS) {
          //boolean para adicionar na API
          $bolAdicionarAPI = true;
          //consulta no arquivamento, para ver se foi solicitado desarquivamento
          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO);
          $objArquivamentoDTO->setDblIdProtocoloDocumento($objDocumentoDTO_Banco->getDblIdDocumento());
          //se foi solicitado desarquivamento, nao pode ser eliminado
          if($objArquivamentoRN->contar($objArquivamentoDTO)){
            $objInfraException->adicionarValidacao("Documento " . $objDocumentoDTO_Banco->getStrProtocoloDocumentoFormatado() . " do processo ".$objDocumentoDTO_Banco->getStrProtocoloProcedimentoFormatado() . " possui solicitação de desarquivamento.");
            //esse documento nao será adicionado no array
            $bolAdicionarAPI = false;
          }
        }
        */

        if($bolAdicionarAPI) {
          //cria objeto e adiciona
          $objDocumentoAPI = new DocumentoAPI();
          $objDocumentoAPI->setNumeroProtocolo($objDocumentoDTO_Banco->getStrProtocoloDocumentoFormatado());
          $objDocumentoAPI->setIdDocumento($objDocumentoDTO_Banco->getDblIdDocumento());
          $arrObjDocumentoAPI[] = $objDocumentoAPI;
        }
      }
      //chama validacao dos modulos existentes no sei
      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('validarEliminacaoDocumento', $arrObjDocumentoAPI);
      }
    }
  }

  protected function cancelarInternoControlado(DocumentoDTO $parObjDocumentoDTO){
    try {

      global $SEI_MODULOS;

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($parObjDocumentoDTO->getStrMotivoCancelamento())){
        $objInfraException->lancarValidacao('Motivo não informado.');
      }

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTO = $this->bloquear($objDocumentoDTO);

      if ($objDocumentoDTO==null){
        $objInfraException->lancarValidacao('Documento não encontrado.');
      }

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $numIdSerieEmail = $objInfraParametro->getValor('ID_SERIE_EMAIL');

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO->retStrStaEstadoProcedimento();
      $objDocumentoDTO->retStrSinEliminadoProcedimento();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retDblIdProtocoloProtocolo();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrStaNivelAcessoLocalProtocolo();
      $objDocumentoDTO->retStrStaNivelAcessoGlobalProtocolo();
      $objDocumentoDTO->retStrStaEstadoProtocolo();
      $objDocumentoDTO->retStrConteudo();
      $objDocumentoDTO->retNumIdSerie();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retObjPublicacaoDTO();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTO = $this->consultarRN0005($objDocumentoDTO);

      $parObjDocumentoDTO->setStrConteudo($this->obterConteudoAuditoriaExclusaoCancelamento($objDocumentoDTO));

      SessaoSEI::getInstance()->validarAuditarPermissao('documento_cancelar', __METHOD__, $parObjDocumentoDTO);

      if ($objDocumentoDTO->getStrStaEstadoProtocolo()==ProtocoloRN::$TE_DOCUMENTO_CANCELADO){
        $objInfraException->lancarValidacao('Documento já foi cancelado.');
      }

      if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_FORMULARIO_AUTOMATICO && $objDocumentoDTO->getNumIdSerie()!=$numIdSerieEmail){
        $objInfraException->lancarValidacao('Formulário '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' não pode ser cancelado.');
      }

      if ($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
        $objInfraException->lancarValidacao('Documento não foi '.($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO?'gerado':'recebido').' pela unidade atual.');
      }

      if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){
        if ($objDocumentoDTO->getObjPublicacaoDTO()!=null ){

          if ($objDocumentoDTO->getObjPublicacaoDTO()->getStrStaEstado()==PublicacaoRN::$TE_PUBLICADO){
            $objInfraException->lancarValidacao('Não é possível cancelar um documento publicado.');
          }

          if ($objDocumentoDTO->getObjPublicacaoDTO()->getStrStaEstado()==PublicacaoRN::$TE_AGENDADO){
            $objInfraException->lancarValidacao('Não é possível cancelar um documento agendado para publicação.');
          }
        }
      }

      if ($objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo()==ProtocoloRN::$NA_SIGILOSO){
        $objAtividadeRN = new AtividadeRN();
        $arrObjAtividadeDTO = $objAtividadeRN->listarCredenciaisAssinatura($objDocumentoDTO);
        foreach($arrObjAtividadeDTO as $objAtividadeDTO){
          if ($objAtividadeDTO->getNumIdTarefa()==TarefaRN::$TI_CONCESSAO_CREDENCIAL_ASSINATURA){
            $objInfraException->lancarValidacao('Documento possui credencial para assinatura ativa.');
            break;
          }
        }
      }

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoRN->verificarEstadoProcedimento($objDocumentoDTO);

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      $objArquivamentoRN = new ArquivamentoRN();
      $objArquivamentoDTO = $objArquivamentoRN->consultar($objArquivamentoDTO);

      if ($objArquivamentoDTO!=null) {
        if ($objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_ARQUIVADO) {
          $objInfraException->lancarValidacao('Não é possível cancelar um documento arquivado.');
        } else if ($objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO) {
          $objInfraException->lancarValidacao('Não é possível cancelar um documento com solicitação de desarquivamento.');
        }
      }

      $objEditalEliminacaoRN = new EditalEliminacaoRN();
      $objEditalEliminacaoRN->removerDocumento($objDocumentoDTO);

      $objInfraException->lancarValidacoes();

      if (count($SEI_MODULOS)) {

        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objDocumentoAPI->setNumeroProtocolo($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $objDocumentoAPI->setIdSerie($objDocumentoDTO->getNumIdSerie());
        $objDocumentoAPI->setIdUnidadeGeradora($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo());
        $objDocumentoAPI->setTipo($objDocumentoDTO->getStrStaProtocoloProtocolo());
        $objDocumentoAPI->setNivelAcesso($objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo());
        $objDocumentoAPI->setSubTipo($objDocumentoDTO->getStrStaDocumento());
        
        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('cancelarDocumento', $objDocumentoAPI);
        }
      }

      $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
      $objRelBlocoProtocoloDTO->retNumIdBloco();
      $objRelBlocoProtocoloDTO->retDblIdProtocolo();
      $objRelBlocoProtocoloDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

      $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
      $objRelBlocoProtocoloRN->excluirRN1289($objRelBlocoProtocoloRN->listarRN1291($objRelBlocoProtocoloDTO));

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
      $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($parObjDocumentoDTO->getDblIdDocumento());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('MOTIVO');
      $objAtributoAndamentoDTO->setStrValor($parObjDocumentoDTO->getStrMotivoCancelamento());
      $objAtributoAndamentoDTO->setStrIdOrigem($parObjDocumentoDTO->getDblIdDocumento());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_CANCELAMENTO_DOCUMENTO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

      $objAtividadeRN  = new AtividadeRN();
      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setStrStaNivelAcessoLocal($objDocumentoDTO->getStrStaNivelAcessoLocalProtocolo());
      $objProtocoloDTO->setDblIdProtocolo($parObjDocumentoDTO->getDblIdDocumento());

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloRN->cancelar($objProtocoloDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro cancelando documento.',$e);
    }
  }

  private function obterConteudoAuditoriaExclusaoCancelamento(DocumentoDTO $objDocumentoDTO){
    try{

      $ret = null;

      if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){

        $objAnexoDTO = new AnexoDTO();
        $objAnexoDTO->retNumIdAnexo();
        $objAnexoDTO->retDthInclusao();
        $objAnexoDTO->retStrNome();
        $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

        $objAnexoRN = new AnexoRN();
        $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);

        if ($objAnexoDTO!=null) {
          $ret = '[Nome do Anexo] => '.$objAnexoDTO->getStrNome()."\n".
              '[Inclusão] => '.$objAnexoDTO->getDthInclusao()."\n".
              '[Localização] => '.str_replace(ConfiguracaoSEI::getInstance()->getValor('SEI','RepositorioArquivos'), '', $objAnexoRN->obterLocalizacao($objAnexoDTO));
        }else{
          $ret = '[Documento sem anexo]';
        }

      }else{
        $ret = $objDocumentoDTO->getStrConteudo();
      }

      return "\n\n".$ret."\n\n";

    }catch(Exception $e){
      throw new InfraException('Erro preenchendo conteúdo para auditoria.');
    }
  }

  public static function montarComandoGeracaoPdf($strArquivoOrigem, $strArquivoPdf, $strTitulo = null, $bolEscalaCinza = false){

    try{

      $arrPermissaoLocal = ConfiguracaoSEI::getInstance()->getValor('SEI','PermitirAcessoLocalPdf', false, array());

      $strPermissaoLocal = InfraArray::contar($arrPermissaoLocal) ? ' --allow '.implode(' --allow ', $arrPermissaoLocal) : '';

      $strOpcaoEscalaCinza =  $bolEscalaCinza ? '--grayscale' : '';

      $strOpcaoTitulo = $strTitulo != '' ? '--title '.escapeshellarg($strTitulo) : '';

      return 'wkhtmltopdf '.$strOpcaoEscalaCinza.' --quiet --enable-smart-shrinking --zoom 1.25 --disable-javascript --disable-local-file-access '.$strPermissaoLocal.' '.$strOpcaoTitulo.' ' .$strArquivoOrigem.' ' .$strArquivoPdf .' 2>&1';

    }catch(Exception $e){
      throw new InfraException('Erro montando comando para geração de PDF.');
    }
  }

  protected function removerVersoesControlado(DocumentoDTO $parObjDocumentoDTO){
    try{

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $this->bloquear($objDocumentoDTO);

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setStrSinBloqueado('S');
      $objDocumentoDTO->setStrSinVersoes('N');
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->alterar($objDocumentoDTO);

      $objDocumentoBD->removerVersoes($objDocumentoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro removendo versões do documento.',$e);
    }
  }
}
?>