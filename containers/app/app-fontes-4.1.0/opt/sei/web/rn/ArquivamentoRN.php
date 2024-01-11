<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/12/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ArquivamentoRN extends InfraRN {

  //TA = Tipo Arquivamento (sta_arquivamento)
  public static $TA_NAO_ARQUIVADO = 'N';
  public static $TA_RECEBIDO = 'R';
  public static $TA_ARQUIVADO = 'A';
  public static $TA_SOLICITADO_DESARQUIVAMENTO = 'S';
  public static $TA_DESARQUIVADO = 'D';
  public static $TA_CANCELADO_ARQUIVAMENTO = 'C';

  public static $TE_NAO_ELIMINADO = 'N';
  public static $TE_PARA_ELIMINACAO = 'P';
  public static $TE_ELIMINADO = 'E';


  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function listarValoresTipoArquivamentoSituacao(){
    try {

      $objArrTipoArquivamentoSituacaoDTO = array();

      $objTipoArquivamentoSituacaoDTO = new TipoArquivamentoSituacaoDTO();
      $objTipoArquivamentoSituacaoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_NAO_ARQUIVADO);
      $objTipoArquivamentoSituacaoDTO->setStrDescricao('Não Arquivado');
      $objArrTipoArquivamentoSituacaoDTO[] = $objTipoArquivamentoSituacaoDTO;

      $objTipoArquivamentoSituacaoDTO = new TipoArquivamentoSituacaoDTO();
      $objTipoArquivamentoSituacaoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_RECEBIDO);
      $objTipoArquivamentoSituacaoDTO->setStrDescricao('Recebido');
      $objArrTipoArquivamentoSituacaoDTO[] = $objTipoArquivamentoSituacaoDTO;

      $objTipoArquivamentoSituacaoDTO = new TipoArquivamentoSituacaoDTO();
      $objTipoArquivamentoSituacaoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_ARQUIVADO);
      $objTipoArquivamentoSituacaoDTO->setStrDescricao('Arquivado');
      $objArrTipoArquivamentoSituacaoDTO[] = $objTipoArquivamentoSituacaoDTO;

      $objTipoArquivamentoSituacaoDTO = new TipoArquivamentoSituacaoDTO();
      $objTipoArquivamentoSituacaoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO);
      $objTipoArquivamentoSituacaoDTO->setStrDescricao('Solicitado Desarquivamento');
      $objArrTipoArquivamentoSituacaoDTO[] = $objTipoArquivamentoSituacaoDTO;

      $objTipoArquivamentoSituacaoDTO = new TipoArquivamentoSituacaoDTO();
      $objTipoArquivamentoSituacaoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_DESARQUIVADO);
      $objTipoArquivamentoSituacaoDTO->setStrDescricao('Desarquivado');
      $objArrTipoArquivamentoSituacaoDTO[] = $objTipoArquivamentoSituacaoDTO;

      return $objArrTipoArquivamentoSituacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Situação de Arquivamento.',$e);
    }
  }

  public function listarValoresTipoArquivamentoEliminacao(){
    try {

      $objArrTipoArquivamentoEliminacaoDTO = array();

      $objTipoArquivamentoEliminacaoDTO = new TipoArquivamentoEliminacaoDTO();
      $objTipoArquivamentoEliminacaoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_NAO_ELIMINADO);
      $objTipoArquivamentoEliminacaoDTO->setStrDescricao('Não Eliminado');
      $objArrTipoArquivamentoEliminacaoDTO[] = $objTipoArquivamentoEliminacaoDTO;

      $objTipoArquivamentoEliminacaoDTO = new TipoArquivamentoEliminacaoDTO();
      $objTipoArquivamentoEliminacaoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_PARA_ELIMINACAO);
      $objTipoArquivamentoEliminacaoDTO->setStrDescricao('Para Eliminação');
      $objArrTipoArquivamentoEliminacaoDTO[] = $objTipoArquivamentoEliminacaoDTO;

      $objTipoArquivamentoEliminacaoDTO = new TipoArquivamentoEliminacaoDTO();
      $objTipoArquivamentoEliminacaoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_ELIMINADO);
      $objTipoArquivamentoEliminacaoDTO->setStrDescricao('Eliminado');
      $objArrTipoArquivamentoEliminacaoDTO[] = $objTipoArquivamentoEliminacaoDTO;

      return $objArrTipoArquivamentoEliminacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Eliminação de Arquivamento.',$e);
    }
  }

  private function validarDblIdProtocolo(ArquivamentoDTO $objArquivamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objArquivamentoDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }
  }

  private function validarNumIdLocalizador(ArquivamentoDTO $objArquivamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objArquivamentoDTO->getNumIdLocalizador())){
      $objInfraException->adicionarValidacao('Localizador não informado.');
    }
  }

  private function validarNumIdAtividadeArquivamento(ArquivamentoDTO $objArquivamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objArquivamentoDTO->getNumIdAtividadeArquivamento())){
      $objArquivamentoDTO->setNumIdAtividadeArquivamento(null);
    }
  }

  private function validarNumIdAtividadeDesarquivamento(ArquivamentoDTO $objArquivamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objArquivamentoDTO->getNumIdAtividadeDesarquivamento())){
      $objArquivamentoDTO->setNumIdAtividadeDesarquivamento(null);
    }
  }

  private function validarNumIdAtividadeRecebimento(ArquivamentoDTO $objArquivamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objArquivamentoDTO->getNumIdAtividadeRecebimento())){
      $objArquivamentoDTO->setNumIdAtividadeRecebimento(null);
    }
  }

  private function validarNumIdAtividadeSolicitacao(ArquivamentoDTO $objArquivamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objArquivamentoDTO->getNumIdAtividadeSolicitacao())){
      $objArquivamentoDTO->setNumIdAtividadeSolicitacao(null);
    }
  }

  private function validarStrStaArquivamento(ArquivamentoDTO $objArquivamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objArquivamentoDTO->getStrStaArquivamento())){
      $objInfraException->adicionarValidacao('Situação do arquivamento não informada.');
    }else{
      if (!in_array($objArquivamentoDTO->getStrStaArquivamento(),InfraArray::converterArrInfraDTO($this->listarValoresTipoArquivamentoSituacao(),'StaArquivamento'))){
        $objInfraException->adicionarValidacao('Situação do arquivamento inválida.');
      }
    }
  }

  private function validarStrStaEliminacao(ArquivamentoDTO $objArquivamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objArquivamentoDTO->getStrStaEliminacao())){
      $objInfraException->adicionarValidacao('Eliminação do arquivamento não informada.');
    }else{
      if (!in_array($objArquivamentoDTO->getStrStaEliminacao(),InfraArray::converterArrInfraDTO($this->listarValoresTipoArquivamentoEliminacao(),'StaEliminacao'))){
        $objInfraException->adicionarValidacao('Eliminação do arquivamento inválida.');
      }
    }
  }

  protected function cadastrarControlado(ArquivamentoDTO $objArquivamentoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_cadastrar',__METHOD__,$objArquivamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProtocolo($objArquivamentoDTO, $objInfraException);
      $this->validarNumIdLocalizador($objArquivamentoDTO, $objInfraException);
      $this->validarNumIdAtividadeArquivamento($objArquivamentoDTO, $objInfraException);
      $this->validarNumIdAtividadeDesarquivamento($objArquivamentoDTO, $objInfraException);
      $this->validarNumIdAtividadeRecebimento($objArquivamentoDTO, $objInfraException);
      $this->validarNumIdAtividadeSolicitacao($objArquivamentoDTO, $objInfraException);
      $this->validarStrStaArquivamento($objArquivamentoDTO, $objInfraException);

      $objArquivamentoDTO->setStrStaEliminacao(self::$TE_NAO_ELIMINADO);

      $objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());
      $ret = $objArquivamentoBD->cadastrar($objArquivamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Arquivamento.',$e);
    }
  }

  protected function alterarControlado(ArquivamentoDTO $objArquivamentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_alterar',__METHOD__,$objArquivamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objArquivamentoDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocolo($objArquivamentoDTO, $objInfraException);
      }
      if ($objArquivamentoDTO->isSetNumIdLocalizador()){
        $this->validarNumIdLocalizador($objArquivamentoDTO, $objInfraException);
      }
      if ($objArquivamentoDTO->isSetNumIdAtividadeArquivamento()){
        $this->validarNumIdAtividadeArquivamento($objArquivamentoDTO, $objInfraException);
      }
      if ($objArquivamentoDTO->isSetNumIdAtividadeDesarquivamento()){
        $this->validarNumIdAtividadeDesarquivamento($objArquivamentoDTO, $objInfraException);
      }
      if ($objArquivamentoDTO->isSetNumIdAtividadeRecebimento()){
        $this->validarNumIdAtividadeRecebimento($objArquivamentoDTO, $objInfraException);
      }
      if ($objArquivamentoDTO->isSetNumIdAtividadeSolicitacao()){
        $this->validarNumIdAtividadeSolicitacao($objArquivamentoDTO, $objInfraException);
      }
      if ($objArquivamentoDTO->isSetStrStaArquivamento()){
        $this->validarStrStaArquivamento($objArquivamentoDTO, $objInfraException);
      }
      if ($objArquivamentoDTO->isSetStrStaEliminacao()){
        $this->validarStrStaEliminacao($objArquivamentoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());
      $objArquivamentoBD->alterar($objArquivamentoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Arquivamento.',$e);
    }
  }

  protected function excluirControlado($arrObjArquivamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_excluir',__METHOD__,$arrObjArquivamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjArquivamentoDTO);$i++){
        $objArquivamentoBD->excluir($arrObjArquivamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Arquivamento.',$e);
    }
  }

  protected function consultarConectado(ArquivamentoDTO $objArquivamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_consultar',__METHOD__,$objArquivamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());
      $ret = $objArquivamentoBD->consultar($objArquivamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Arquivamento.',$e);
    }
  }

  protected function listarConectado(ArquivamentoDTO $objArquivamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_listar',__METHOD__,$objArquivamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());
      $ret = $objArquivamentoBD->listar($objArquivamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Arquivamento.',$e);
    }
  }

  protected function contarConectado(ArquivamentoDTO $objArquivamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_listar',__METHOD__,$objArquivamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());
      $ret = $objArquivamentoBD->contar($objArquivamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Arquivamento.',$e);
    }
  }

  protected function listarParaArquivamentoRN1161Conectado(ArquivamentoDTO $parObjArquivamentoDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_listar',__METHOD__,$parObjArquivamentoDTO);

      $objInfraException = new InfraException();

      $objProtocoloRN = new ProtocoloRN();
      $objDocumentoRN = new DocumentoRN();

      if ($parObjArquivamentoDTO->isSetStrProtocoloFormatadoDocumento()){

        $objProtocoloDTO = new ProtocoloDTO();

        $objProtocoloDTO->setNumTipoFkDocumento(InfraDTO::$TIPO_FK_OBRIGATORIA);

        $objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retDblIdProcedimentoDocumento();
        $objProtocoloDTO->retStrNomeTipoProcedimentoDocumento();
        $objProtocoloDTO->retStrProtocoloFormatadoProcedimentoDocumento();
        $objProtocoloDTO->retStrProtocoloFormatado();
        $objProtocoloDTO->retStrNomeSerieDocumento();
        $objProtocoloDTO->retStrNumeroDocumento();
        $objProtocoloDTO->retStrSinArquivamentoDocumento();
        $objProtocoloDTO->retDtaInclusao();

        $arrProtocoloFormatado = explode(',',$parObjArquivamentoDTO->getStrProtocoloFormatadoDocumento());

        $arrPesquisa = array();
        foreach($arrProtocoloFormatado as $strProtocoloFormatado){
          $strProtocoloFormatado = InfraUtil::retirarFormatacao($strProtocoloFormatado);
          if (is_numeric($strProtocoloFormatado)){
            //$arrPesquisa[] = '%'.$strProtocoloFormatado;
            $arrPesquisa[] = $strProtocoloFormatado;
          }
        }

        if (count($arrPesquisa)==0){
          return array();
        }

        $objProtocoloDTO->unSetStrProtocoloFormatado();

        $objProtocoloDTO2 = new ProtocoloDTO();
        $objProtocoloDTO2->retDblIdProtocolo();
        $objProtocoloDTO2->retStrStaProtocolo();
        $objProtocoloDTO2->retStrSinEliminado();

        if (count($arrPesquisa)==1){

          $objProtocoloDTO2->setStrProtocoloFormatadoPesquisa($arrPesquisa[0]);

        }else{

          $objProtocoloDTO2->adicionarCriterio(array_fill(0,count($arrPesquisa),'ProtocoloFormatadoPesquisa'),
              array_fill(0,count($arrPesquisa),InfraDTO::$OPER_IGUAL),
              $arrPesquisa,
              array_fill(0,count($arrPesquisa)-1,InfraDTO::$OPER_LOGICO_OR));
        }

        $arrObjProtocoloDTO2 = $objProtocoloRN->listarRN0668($objProtocoloDTO2);

        $arrProcedimentos = array();
        $arrDocumentos = array();
        foreach($arrObjProtocoloDTO2 as $objProtocoloDTO2){
          if($objProtocoloDTO2->getStrSinEliminado() == "N") {
            if ($objProtocoloDTO2->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
              $arrProcedimentos[] = $objProtocoloDTO2->getDblIdProtocolo();
            } else {
              $arrDocumentos[] = $objProtocoloDTO2->getDblIdProtocolo();
            }
          }
        }


        if (count($arrProcedimentos)){
          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->retDblIdDocumento();
          $objDocumentoDTO->setDblIdProcedimento($arrProcedimentos,InfraDTO::$OPER_IN);
          $arrDocumentos = array_merge($arrDocumentos,InfraArray::converterArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO),'IdDocumento'));
        }

        if (count($arrDocumentos)){
          $objProtocoloDTO->setDblIdProtocolo(array_unique($arrDocumentos),InfraDTO::$OPER_IN);
        }else{
          return array();
        }

        $objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_DOCUMENTO_RECEBIDO);
        $objProtocoloDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_DESC);

        //paginação
        $objProtocoloDTO->setNumMaxRegistrosRetorno($parObjArquivamentoDTO->getNumMaxRegistrosRetorno());
        $objProtocoloDTO->setNumPaginaAtual($parObjArquivamentoDTO->getNumPaginaAtual());

        $objProtocoloRN = new ProtocoloRN();
        $arrObjProtocoloDTO = $objProtocoloRN->listarRN0668($objProtocoloDTO);

        //paginação
        $parObjArquivamentoDTO->setNumTotalRegistros($objProtocoloDTO->getNumTotalRegistros());
        $parObjArquivamentoDTO->setNumRegistrosPaginaAtual($objProtocoloDTO->getNumRegistrosPaginaAtual());

        if (count($arrObjProtocoloDTO)==0){
          return array();
        }

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $dtaDataCorteSinalizador = $objInfraParametro->getValor('SEI_DATA_CORTE_SINALIZADOR_PARA_ARQUIVAMENTO',false);

        if (InfraString::isBolVazia($dtaDataCorteSinalizador)){
          $dtaDataCorteSinalizador = null;
        }else{

          if (!InfraData::validarData($dtaDataCorteSinalizador)){
            throw new InfraException('Parâmetro SEI_DATA_CORTE_SINALIZADOR_PARA_ARQUIVAMENTO contém data inválida.');
          }

          if (InfraData::compararDatas(InfraData::getStrDataHoraAtual(),$dtaDataCorteSinalizador)>0){
            throw new InfraException('Parâmetro SEI_DATA_CORTE_SINALIZADOR_PARA_ARQUIVAMENTO contém data futura.');
          }
        }

        $arrProtocolosNaoSinalizados = array();
        foreach($arrObjProtocoloDTO as $objProtocoloDTO){
          if ($objProtocoloDTO->getStrSinArquivamentoDocumento()=='N' && ($dtaDataCorteSinalizador==null || InfraData::compararDatas($dtaDataCorteSinalizador,$objProtocoloDTO->getDtaInclusao())>=0)){
            $arrProtocolosNaoSinalizados[] = $objProtocoloDTO->getStrProtocoloFormatado();
          }
        }

        $numProtocolosNaoSinalizados = count($arrProtocolosNaoSinalizados);
        if ($numProtocolosNaoSinalizados) {
          if ($numProtocolosNaoSinalizados == 1) {
            $objInfraException->lancarValidacao('Documento não sinalizado para arquivamento: '.$arrProtocolosNaoSinalizados[0].'.');
          } else {
            $strMsg = '';
            for ($i = 0; $i < $numProtocolosNaoSinalizados; $i++) {
              if ($strMsg != '') {
                if ($i == ($numProtocolosNaoSinalizados - 1)) {
                  $strMsg .= ' e ';
                } else {
                  $strMsg .= ', ';
                }
              }
              $strMsg .= $arrProtocolosNaoSinalizados[$i];
            }
            $objInfraException->lancarValidacao('Documentos não sinalizados para arquivamento: '.$strMsg.'.');
          }
        }

        $objInfraException->lancarValidacoes();

        $objArquivamentoDTO = new ArquivamentoDTO();
        $objArquivamentoDTO->retDblIdProtocolo();
        $objArquivamentoDTO->retStrStaArquivamento();
        $objArquivamentoDTO->retStrStaEliminacao();
        $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
        $objArquivamentoDTO->retStrSiglaTipoLocalizador();
        $objArquivamentoDTO->retStrNomeTipoLocalizador();
        $objArquivamentoDTO->retNumIdUnidadeLocalizador();
        $objArquivamentoDTO->retNumIdTipoLocalizador();
        $objArquivamentoDTO->retNumIdLocalizador();
        $objArquivamentoDTO->retNumIdUnidadeRecebimento();
        $objArquivamentoDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjProtocoloDTO,'IdProtocolo'),InfraDTO::$OPER_IN);
        $objArquivamentoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_NAO_ELIMINADO);

        $arrObjArquivamentoDTOComplemento = InfraArray::indexarArrInfraDTO($this->listar($objArquivamentoDTO),'IdProtocolo');

        $arrObjArquivamentoDTO = array();
        foreach($arrObjProtocoloDTO as $objProtocoloDTO){

          $objArquivamentoDTO = new ArquivamentoDTO();

          $objArquivamentoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
          $objArquivamentoDTO->setDblIdProcedimentoDocumento($objProtocoloDTO->getDblIdProcedimentoDocumento());
          $objArquivamentoDTO->setStrNomeTipoProcedimento($objProtocoloDTO->getStrNomeTipoProcedimentoDocumento());
          $objArquivamentoDTO->setStrProtocoloFormatadoProcedimento($objProtocoloDTO->getStrProtocoloFormatadoProcedimentoDocumento());
          $objArquivamentoDTO->setStrProtocoloFormatadoDocumento($objProtocoloDTO->getStrProtocoloFormatado());
          $objArquivamentoDTO->setStrNomeSerieDocumento($objProtocoloDTO->getStrNomeSerieDocumento());
          $objArquivamentoDTO->setStrNumeroDocumento($objProtocoloDTO->getStrNumeroDocumento());

          if (isset($arrObjArquivamentoDTOComplemento[$objProtocoloDTO->getDblIdProtocolo()])){

            $objArquivamentoDTOComplemento = $arrObjArquivamentoDTOComplemento[$objProtocoloDTO->getDblIdProtocolo()];

            $objArquivamentoDTO->setStrStaArquivamento($objArquivamentoDTOComplemento->getStrStaArquivamento());
            $objArquivamentoDTO->setStrStaEliminacao($objArquivamentoDTOComplemento->getStrStaEliminacao());
            $objArquivamentoDTO->setNumSeqLocalizadorLocalizador($objArquivamentoDTOComplemento->getNumSeqLocalizadorLocalizador());
            $objArquivamentoDTO->setStrSiglaTipoLocalizador($objArquivamentoDTOComplemento->getStrSiglaTipoLocalizador());
            $objArquivamentoDTO->setStrNomeTipoLocalizador($objArquivamentoDTOComplemento->getStrNomeTipoLocalizador());
            $objArquivamentoDTO->setNumIdUnidadeLocalizador($objArquivamentoDTOComplemento->getNumIdUnidadeLocalizador());
            $objArquivamentoDTO->setNumIdTipoLocalizador($objArquivamentoDTOComplemento->getNumIdTipoLocalizador());
            $objArquivamentoDTO->setNumIdLocalizador($objArquivamentoDTOComplemento->getNumIdLocalizador());
            $objArquivamentoDTO->setNumIdUnidadeRecebimento($objArquivamentoDTOComplemento->getNumIdUnidadeRecebimento());

          }else{

            $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_NAO_ARQUIVADO);
            $objArquivamentoDTO->setStrStaEliminacao(null);
            $objArquivamentoDTO->setNumSeqLocalizadorLocalizador(null);
            $objArquivamentoDTO->setStrSiglaTipoLocalizador(null);
            $objArquivamentoDTO->setStrNomeTipoLocalizador(null);
            $objArquivamentoDTO->setNumIdUnidadeLocalizador(null);
            $objArquivamentoDTO->setNumIdTipoLocalizador(null);
            $objArquivamentoDTO->setNumIdLocalizador(null);
            $objArquivamentoDTO->setNumIdUnidadeRecebimento(null);

          }

          $arrObjArquivamentoDTO[] = $objArquivamentoDTO;
        }

      }else{

        $objArquivamentoDTO = new ArquivamentoDTO();
        $objArquivamentoDTO->retDblIdProtocolo();
        $objArquivamentoDTO->retDblIdProcedimentoDocumento();
        $objArquivamentoDTO->retStrNomeTipoProcedimento();
        $objArquivamentoDTO->retStrProtocoloFormatadoProcedimento();
        $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
        $objArquivamentoDTO->retStrNomeSerieDocumento();
        $objArquivamentoDTO->retStrNumeroDocumento();
        $objArquivamentoDTO->retStrStaArquivamento();
        $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
        $objArquivamentoDTO->retStrSiglaTipoLocalizador();
        $objArquivamentoDTO->retStrNomeTipoLocalizador();
        $objArquivamentoDTO->retNumIdUnidadeLocalizador();
        $objArquivamentoDTO->retNumIdTipoLocalizador();
        $objArquivamentoDTO->retNumIdLocalizador();
        $objArquivamentoDTO->retNumIdUnidadeRecebimento();

        $objArquivamentoDTO->setNumTipoFkRecebimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objArquivamentoDTO->setNumIdUnidadeRecebimento(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        if ($parObjArquivamentoDTO->isSetArrDblIdArquivados()){

          $objArquivamentoDTO->setNumTipoFkArquivamento(InfraDTO::$TIPO_FK_OPCIONAL);
          $objArquivamentoDTO->setNumIdUnidadeArquivamento(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

          $objArquivamentoDTO->adicionarCriterio(array('StaArquivamento','IdProtocolo'),
                                                 array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IN),
                                                 array(ArquivamentoRN::$TA_RECEBIDO, $parObjArquivamentoDTO->getArrDblIdArquivados()),
                                                 InfraDTO::$OPER_LOGICO_OR);
        }else {
          $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_RECEBIDO);
        }

        $objArquivamentoDTO->setOrdDthAberturaRecebimento(InfraDTO::$TIPO_ORDENACAO_DESC);


        //paginação
        $objArquivamentoDTO->setNumMaxRegistrosRetorno($parObjArquivamentoDTO->getNumMaxRegistrosRetorno());
        $objArquivamentoDTO->setNumPaginaAtual($parObjArquivamentoDTO->getNumPaginaAtual());

        $arrObjArquivamentoDTO = $this->listar($objArquivamentoDTO);

        //paginação
        $parObjArquivamentoDTO->setNumTotalRegistros($objArquivamentoDTO->getNumTotalRegistros());
        $parObjArquivamentoDTO->setNumRegistrosPaginaAtual($objArquivamentoDTO->getNumRegistrosPaginaAtual());

      }


      if (InfraArray::contar($arrObjArquivamentoDTO) > 0){

        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_RECEBIDOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
        $objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjArquivamentoDTO,'IdProtocolo'));

        $arrObjProtocoloDTOPesquisados = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO),'IdProtocolo');

        foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){
          if (isset($arrObjProtocoloDTOPesquisados[$objArquivamentoDTO->getDblIdProtocolo()])){
            $objArquivamentoDTO->setNumCodigoAcesso($arrObjProtocoloDTOPesquisados[$objArquivamentoDTO->getDblIdProtocolo()]->getNumCodigoAcesso());
          }
        }

        $objArquivamentoDTO = new ArquivamentoDTO();
        $objArquivamentoDTO->setDistinct(true);
        $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objArquivamentoDTO->retNumIdLocalizador();
        $objArquivamentoDTO->retDblIdProcedimentoDocumento();
        $objArquivamentoDTO->setDblIdProcedimentoDocumento(InfraArray::converterArrInfraDTO($arrObjArquivamentoDTO,'IdProcedimentoDocumento'),InfraDTO::$OPER_IN);
        $arrObjArquivamentoDTOPorProcesso = $this->listar($objArquivamentoDTO);


        $arrObjLocalizadorDTOPorProcesso = array();

        if (count($arrObjArquivamentoDTOPorProcesso) > 0){

          $objLocalizadorDTO = new LocalizadorDTO();
          $objLocalizadorDTO->retNumIdLocalizador();
          $objLocalizadorDTO->retStrNomeTipoLocalizador();
          $objLocalizadorDTO->retStrSiglaTipoLocalizador();
          $objLocalizadorDTO->retNumSeqLocalizador();
          $objLocalizadorDTO->retStrNomeLugarLocalizador();
          $objLocalizadorDTO->retStrStaEstado();
          $objLocalizadorDTO->retNumIdUnidade();
          $objLocalizadorDTO->retStrSiglaUnidadeLocalizador();

          $objLocalizadorDTO->setNumIdLocalizador(array_unique(InfraArray::converterArrInfraDTO($arrObjArquivamentoDTOPorProcesso,'IdLocalizador')),InfraDTO::$OPER_IN);

          $objLocalizadorRN = new LocalizadorRN();
          $arrObjLocalizadorDTO = InfraArray::indexarArrInfraDTO($objLocalizadorRN->listarRN0622($objLocalizadorDTO),'IdLocalizador');

          //gera array indexando pelo procedimento
          foreach($arrObjArquivamentoDTOPorProcesso as $objArquivamentoDTO){
            if (isset($arrObjLocalizadorDTO[$objArquivamentoDTO->getNumIdLocalizador()])) {
              if (!isset($arrObjLocalizadorDTOPorProcesso[$objArquivamentoDTO->getDblIdProcedimentoDocumento()])) {
                $arrObjLocalizadorDTOPorProcesso[$objArquivamentoDTO->getDblIdProcedimentoDocumento()] = array();
              }
              $arrObjLocalizadorDTOPorProcesso[$objArquivamentoDTO->getDblIdProcedimentoDocumento()][] = $arrObjLocalizadorDTO[$objArquivamentoDTO->getNumIdLocalizador()];
            }
          }
        }

        foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

          if ($objArquivamentoDTO->getStrStaArquivamento()==null){
            $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_NAO_ARQUIVADO);
          }

          if (isset($arrObjLocalizadorDTOPorProcesso[$objArquivamentoDTO->getDblIdProcedimentoDocumento()])){
            $objArquivamentoDTO->setArrObjLocalizadorDTO($arrObjLocalizadorDTOPorProcesso[$objArquivamentoDTO->getDblIdProcedimentoDocumento()]);
          }else{
            $objArquivamentoDTO->setArrObjLocalizadorDTO(array());
          }
        }

      }

      return $arrObjArquivamentoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando documentos para arquivamento.',$e);
    }
  }

  protected function receberControlado($arrObjArquivamentoDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_receber',__METHOD__,$arrObjArquivamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $arrIdProtocolo = InfraArray::converterArrInfraDTO($arrObjArquivamentoDTO,'IdProtocolo');

      if (InfraArray::contar($arrIdProtocolo)==0){
        $objInfraException->lancarValidacao('Nenhum protocolo informado para recebimento.');
      }

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrStaProtocoloProtocolo();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->setDblIdProtocolo($arrIdProtocolo,InfraDTO::$OPER_IN);

      $arrObjArquivamentoDTO = InfraArray::indexarArrInfraDTO($this->listar($objArquivamentoDTO),'IdProtocolo');

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

        if ($objArquivamentoDTO->getStrStaProtocoloProtocolo() != ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){
          $objInfraException->adicionarValidacao('O protocolo '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' não representa um documento externo.');
        }

        if ($objArquivamentoDTO->getStrStaArquivamento()==ArquivamentoRN::$TA_RECEBIDO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' já consta como recebido.');
        }

        if ($objArquivamentoDTO->getStrStaArquivamento()==ArquivamentoRN::$TA_ARQUIVADO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' já consta como arquivado.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objAtividadeRN = new AtividadeRN();

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->setDblIdDocumento($arrIdProtocolo,InfraDTO::$OPER_IN);

      $objDocumentoRN = new DocumentoRN();
      $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);

      $objArquivamentoBD = new ArquivamentoBD(BancoSEI::getInstance());

      foreach($arrObjDocumentoDTO as $objDocumentoDTO){

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_RECEBIMENTO_ARQUIVO);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
        $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        if (!isset($arrObjArquivamentoDTO[$objDocumentoDTO->getDblIdDocumento()])){

          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
          $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_RECEBIDO);
          $objArquivamentoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_NAO_ELIMINADO);
          $objArquivamentoDTO->setNumIdLocalizador(null);
          $objArquivamentoDTO->setNumIdAtividadeArquivamento(null);
          $objArquivamentoDTO->setNumIdAtividadeRecebimento($objAtividadeDTO->getNumIdAtividade());
          $objArquivamentoDTO->setNumIdAtividadeSolicitacao(null);
          $objArquivamentoDTO->setNumIdAtividadeDesarquivamento(null);
          $objArquivamentoBD->cadastrar($objArquivamentoDTO);

        }else{

          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_RECEBIDO);
          $objArquivamentoDTO->setNumIdAtividadeRecebimento($objAtividadeDTO->getNumIdAtividade());
          $objArquivamentoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
          $objArquivamentoBD->alterar($objArquivamentoDTO);

        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro recebendo documento no arquivo.',$e);
    }
  }

  protected function cancelarRecebimentoControlado($arrObjArquivamentoDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_cancelar_recebimento',__METHOD__,$arrObjArquivamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $arrIdProtocolo = InfraArray::converterArrInfraDTO($arrObjArquivamentoDTO,'IdProtocolo');

      if (InfraArray::contar($arrIdProtocolo)==0){
        $objInfraException->lancarValidacao('Nenhum protocolo informado para cancelamento de recebimento.');
      }

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrStaProtocoloProtocolo();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->retNumIdAtividadeArquivamento();
      $objArquivamentoDTO->retDblIdProcedimentoDocumento();
      $objArquivamentoDTO->setDblIdProtocolo($arrIdProtocolo,InfraDTO::$OPER_IN);

      $arrObjArquivamentoDTO = InfraArray::indexarArrInfraDTO($this->listar($objArquivamentoDTO),'IdProtocolo');

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

        if ($objArquivamentoDTO->getStrStaArquivamento()!=ArquivamentoRN::$TA_RECEBIDO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' não consta como recebido.');
        }

        if ($objArquivamentoDTO->getStrStaArquivamento()==ArquivamentoRN::$TA_ARQUIVADO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' já consta como arquivado.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objAtividadeRN = new AtividadeRN();

      $objArquivamentoBD = new ArquivamentoBD(BancoSEI::getInstance());

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrValor($objArquivamentoDTO->getStrProtocoloFormatadoDocumento());
        $objAtributoAndamentoDTO->setStrIdOrigem($objArquivamentoDTO->getDblIdProtocolo());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objArquivamentoDTO->getDblIdProcedimentoDocumento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_CANCELADO_RECEBIMENTO_ARQUIVO);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        if ($objArquivamentoDTO->getNumIdAtividadeArquivamento()==null){
          $dto = new ArquivamentoDTO();
          $dto->setDblIdProtocolo($objArquivamentoDTO->getDblIdProtocolo());
          $objArquivamentoBD->excluir($dto);
        }else {
          $dto = new ArquivamentoDTO();
          $dto->setNumIdAtividadeRecebimento(null);
          $dto->setStrStaArquivamento(ArquivamentoRN::$TA_DESARQUIVADO);
          $dto->setDblIdProtocolo($objArquivamentoDTO->getDblIdProtocolo());
          $objArquivamentoBD->alterar($dto);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro cancelando recebimento de documento no arquivo.',$e);
    }
  }

  protected function arquivarRN1133Controlado(ArquivamentoDTO $objArquivamentoDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_arquivar',__METHOD__, $objArquivamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $arrIdProtocolo = $objArquivamentoDTO->getDblIdProtocolo();

      if (InfraArray::contar($arrIdProtocolo)==0){
        $objInfraException->lancarValidacao('Nenhum protocolo informado para arquivamento.');
      }

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->retStrSigla();
      $objUnidadeDTO->retStrSiglaOrgao();
      $objUnidadeDTO->retStrSinArquivamento();
      $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

      if ($objUnidadeDTO->getStrSinArquivamento()=='N'){
        $objInfraException->lancarValidacao('Unidade '.$objUnidadeDTO->getStrSigla().'/'.$objUnidadeDTO->getStrSiglaOrgao().' não está configurada como unidade de arquivo.');
      }

      //Obter dados do localizador
      $objLocalizadorDTO = new LocalizadorDTO();
      $objLocalizadorDTO->retNumIdLocalizador();
      $objLocalizadorDTO->retNumIdUnidade();
      $objLocalizadorDTO->retStrStaEstado();
      $objLocalizadorDTO->retStrIdentificacao();
      $objLocalizadorDTO->retStrSiglaUnidadeLocalizador();
      $objLocalizadorDTO->setNumIdLocalizador($objArquivamentoDTO->getNumIdLocalizador());

      $objLocalizadorRN = new LocalizadorRN();
      $objLocalizadorDTO = $objLocalizadorRN->consultarRN0619($objLocalizadorDTO);

      if($objLocalizadorDTO->getNumIdUnidade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
        $objInfraException->lancarValidacao('Localizador pertence a unidade '.$objLocalizadorDTO->getStrSiglaUnidadeLocalizador().'.');
      }

      if($objLocalizadorDTO->getStrStaEstado() == LocalizadorRN::$EA_FECHADO){
        $objInfraException->lancarValidacao('Localizador fechado.');
      }

      //Obter os dados dos protocolos
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrStaEstadoProtocolo();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();

      $objDocumentoDTO->setDblIdDocumento($arrIdProtocolo,InfraDTO::$OPER_IN);

      $objDocumentoRN = new DocumentoRN();
      $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);

      foreach($arrObjDocumentoDTO as $objDocumentoDTO){

        /*
	  		if ($objDocumentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo()!==SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()){
	  			$objInfraException->lancarValidacao('Documento não foi gerado pelo órgão '.SessaoSEI::getInstance()->getStrSiglaOrgaoUnidadeAtual().'.');
	  		}
	  		*/

        if ($objDocumentoDTO->getStrStaEstadoProtocolo()==ProtocoloRN::$TE_DOCUMENTO_CANCELADO){
          $objInfraException->lancarValidacao('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' foi cancelado.');
        }

      }

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->setDblIdProtocolo($arrIdProtocolo,InfraDTO::$OPER_IN);

      $arrObjArquivamentoDTO = InfraArray::indexarArrInfraDTO($this->listar($objArquivamentoDTO),'IdProtocolo');

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){
        if ($objArquivamentoDTO->getStrStaArquivamento()==ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' possui solicitação de desarquivamento.');
        }

        if ($objArquivamentoDTO->getStrStaArquivamento()==ArquivamentoRN::$TA_ARQUIVADO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' já consta como arquivado.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());

      foreach($arrObjDocumentoDTO as $objDocumentoDTO){

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('LOCALIZADOR');
        $objAtributoAndamentoDTO->setStrValor($objLocalizadorDTO->getStrIdentificacao());
        $objAtributoAndamentoDTO->setStrIdOrigem($objLocalizadorDTO->getNumIdLocalizador());

        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ARQUIVAMENTO);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        if (!isset($arrObjArquivamentoDTO[$objDocumentoDTO->getDblIdDocumento()])){

          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
          $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_ARQUIVADO);
          $objArquivamentoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_NAO_ELIMINADO);
          $objArquivamentoDTO->setNumIdLocalizador($objLocalizadorDTO->getNumIdLocalizador());
          $objArquivamentoDTO->setNumIdAtividadeArquivamento($objAtividadeDTO->getNumIdAtividade());
          $objArquivamentoDTO->setNumIdAtividadeRecebimento(null);
          $objArquivamentoDTO->setNumIdAtividadeSolicitacao(null);
          $objArquivamentoDTO->setNumIdAtividadeDesarquivamento(null);
          $objArquivamentoBD->cadastrar($objArquivamentoDTO);

        }else{

          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_ARQUIVADO);
          $objArquivamentoDTO->setNumIdAtividadeArquivamento($objAtividadeDTO->getNumIdAtividade());
          $objArquivamentoDTO->setNumIdLocalizador($objLocalizadorDTO->getNumIdLocalizador());
          $objArquivamentoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
          $objArquivamentoBD->alterar($objArquivamentoDTO);

        }

      }

    }catch(Exception $e){
      throw new InfraException('Erro arquivando protocolo.',$e);
    }
  }

  protected function desarquivarRN1147Controlado(SolicitacaoDesarquivamentoDTO $objSolicitacaoDesarquivamentoDTO){
    try{

      $objSolicitacaoDesarquivamentoDTOAuditoria = clone($objSolicitacaoDesarquivamentoDTO);
      $objSolicitacaoDesarquivamentoDTOAuditoria->unSetStrSenha();
      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_desarquivar',__METHOD__,$objSolicitacaoDesarquivamentoDTOAuditoria);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $arrIdProtocolo = InfraArray::converterArrInfraDTO($objSolicitacaoDesarquivamentoDTO->getArrObjArquivamentoDTO(),'IdProtocolo');

      if (InfraArray::contar($arrIdProtocolo)==0){
        $objInfraException->lancarValidacao('Nenhum protocolo informado para desarquivamento.');
      }

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retNumIdOrgao();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->setNumIdUsuario($objSolicitacaoDesarquivamentoDTO->getNumIdUsuario());

      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

      $objInfraSip = new InfraSip(SessaoSEI::getInstance());
      $objInfraSip->autenticar($objUsuarioDTO->getNumIdOrgao(),
          null,
          $objUsuarioDTO->getStrSigla(),
          $objSolicitacaoDesarquivamentoDTO->getStrSenha());

      //Obter os dados dos protocolos
      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->retDblIdProcedimentoDocumento();
      $objArquivamentoDTO->retNumIdUnidadeLocalizador();

      $objArquivamentoDTO->setDblIdProtocolo($arrIdProtocolo,InfraDTO::$OPER_IN);

      $arrObjArquivamentoDTO = $this->listar($objArquivamentoDTO);

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

        if($objArquivamentoDTO->getStrStaArquivamento() != ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' não possui solicitação de desarquivamento.');
        }

        if($objArquivamentoDTO->getNumIdUnidadeLocalizador() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' não foi arquivado na unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().'.');
        }

      }

      $objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD(BancoSEI::getInstance());

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrValor($objArquivamentoDTO->getStrProtocoloFormatadoDocumento());
        $objAtributoAndamentoDTO->setStrIdOrigem($objArquivamentoDTO->getDblIdProtocolo());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('USUARIO');
        $objAtributoAndamentoDTO->setStrValor($objUsuarioDTO->getStrSigla().'¥'.$objUsuarioDTO->getStrNome());
        $objAtributoAndamentoDTO->setStrIdOrigem($objUsuarioDTO->getNumIdUsuario());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objArquivamentoDTO->getDblIdProcedimentoDocumento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_DESARQUIVAMENTO);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);


        $dto = new ArquivamentoDTO();
        $dto->setNumIdAtividadeDesarquivamento($objAtividadeDTO->getNumIdAtividade());
        $dto->setStrStaArquivamento(ArquivamentoRN::$TA_DESARQUIVADO);
        $dto->setDblIdProtocolo($objArquivamentoDTO->getDblIdProtocolo());
        $objArquivamentoBD->alterar($dto);

      }
    }catch(Exception $e){
      throw new InfraException('Erro desarquivando protocolo.',$e);
    }
  }

  protected function cancelarArquivamentoControlado($arrObjArquivamentoDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_cancelar',__METHOD__,$arrObjArquivamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $arrIdProtocolo = InfraArray::converterArrInfraDTO($arrObjArquivamentoDTO,'IdProtocolo');


      if (InfraArray::contar($arrIdProtocolo)==0){
        $objInfraException->lancarValidacao('Nenhum protocolo informado para cancelamento do arquivamento.');
      }

      //Obter os dados dos protocolos
      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->retDblIdProcedimentoDocumento();
      $objArquivamentoDTO->retNumIdLocalizador();
      $objArquivamentoDTO->retNumIdUnidadeLocalizador();

      $objArquivamentoDTO->setDblIdProtocolo($arrIdProtocolo,InfraDTO::$OPER_IN);

      $arrObjArquivamentoDTO = $this->listar($objArquivamentoDTO);

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

        if($objArquivamentoDTO->getStrStaArquivamento() != ArquivamentoRN::$TA_ARQUIVADO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' não está arquivado.');
        }

        if($objArquivamentoDTO->getNumIdUnidadeLocalizador() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' não foi arquivado na unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().'.');
        }

      }

      $objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD(BancoSEI::getInstance());

      //Obter dados do localizador
      $objLocalizadorDTO = new LocalizadorDTO();
      $objLocalizadorDTO->retNumIdLocalizador();
      $objLocalizadorDTO->retStrIdentificacao();
      $objLocalizadorDTO->setNumIdLocalizador($objArquivamentoDTO->getNumIdLocalizador());

      $objLocalizadorRN = new LocalizadorRN();
      $objLocalizadorDTO = $objLocalizadorRN->consultarRN0619($objLocalizadorDTO);

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrValor($objArquivamentoDTO->getStrProtocoloFormatadoDocumento());
        $objAtributoAndamentoDTO->setStrIdOrigem($objArquivamentoDTO->getDblIdProtocolo());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('LOCALIZADOR');
        $objAtributoAndamentoDTO->setStrValor($objLocalizadorDTO->getStrIdentificacao());
        $objAtributoAndamentoDTO->setStrIdOrigem($objLocalizadorDTO->getNumIdLocalizador());

        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objArquivamentoDTO->getDblIdProcedimentoDocumento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_CANCELAR_ARQUIVAMENTO);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);


        $dto = new ArquivamentoDTO();
        $dto->setNumIdAtividadeArquivamento(null);
        $dto->setNumIdLocalizador(null);
        $dto->setNumIdAtividadeCancelamento($objAtividadeDTO->getNumIdAtividade());
        $dto->setStrStaArquivamento(ArquivamentoRN::$TA_NAO_ARQUIVADO);
        $dto->setDblIdProtocolo($objArquivamentoDTO->getDblIdProtocolo());
        $objArquivamentoBD->alterar($dto);


      }
    }catch(Exception $e){
      throw new InfraException('Erro desarquivando protocolo.',$e);
    }
  }

  protected function solicitarDesarquivamentoControlado(ArquivamentoDTO $objArquivamentoDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_solicitar_desarquivamento',__METHOD__,$objArquivamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $arrIdProtocolo = $objArquivamentoDTO->getDblIdProtocolo();

      if (InfraArray::contar($arrIdProtocolo)==0){
        $objInfraException->lancarValidacao('Nenhum protocolo informado na solicitação de desarquivamento.');
      }

      //Obter os dados dos protocolos
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retDblIdProcedimento();

      $objDocumentoDTO->setDblIdDocumento($arrIdProtocolo,InfraDTO::$OPER_IN);

      $objDocumentoRN = new DocumentoRN();
      $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);

      foreach($arrObjDocumentoDTO as $objDocumentoDTO){

        if ($objDocumentoDTO->getStrStaProtocoloProtocolo() != ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){
          $objInfraException->adicionarValidacao('O protocolo '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' não representa um documento externo.');
        }
      }

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->setDblIdProtocolo($arrIdProtocolo,InfraDTO::$OPER_IN);

      $arrObjArquivamentoDTO = InfraArray::indexarArrInfraDTO($this->listar($objArquivamentoDTO),'IdProtocolo');

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){
        if ($objArquivamentoDTO->getStrStaArquivamento()==ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' já possui solicitação de desarquivamento.');
        }

        if ($objArquivamentoDTO->getStrStaArquivamento()!=ArquivamentoRN::$TA_ARQUIVADO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' não consta como arquivado.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD(BancoSEI::getInstance());

      foreach($arrObjDocumentoDTO as $objDocumentoDTO){

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_SOLICITADO_DESARQUIVAMENTO);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        $dto = new ArquivamentoDTO();
        $dto->setNumIdAtividadeSolicitacao($objAtividadeDTO->getNumIdAtividade());
        $dto->setStrStaArquivamento(ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO);
        $dto->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
        $objArquivamentoBD->alterar($dto);

      }

    }catch(Exception $e){
      throw new InfraException('Erro solicitando desarquivamento do documento.',$e);
    }
  }

  protected function cancelarSolicitacaoDesarquivamentoControlado($arrObjArquivamentoDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_cancelar_solicitacao_desarquivamento',__METHOD__,$arrObjArquivamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $arrIdProtocolo = InfraArray::converterArrInfraDTO($arrObjArquivamentoDTO,'IdProtocolo');

      if (InfraArray::contar($arrIdProtocolo)==0){
        $objInfraException->lancarValidacao('Nenhum protocolo informado no cancelamento de solicitação de desarquivamento.');
      }

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objArquivamentoDTO->setNumTipoFkSolicitacao(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retDblIdProcedimentoDocumento();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->retNumIdUnidadeLocalizador();
      $objArquivamentoDTO->retNumIdUnidadeSolicitacao();
      $objArquivamentoDTO->retStrSiglaUnidadeSolicitacao();
      $objArquivamentoDTO->retStrDescricaoUnidadeSolicitacao();

      $objArquivamentoDTO->setDblIdProtocolo($arrIdProtocolo,InfraDTO::$OPER_IN);

      $arrObjArquivamentoDTO = $this->listar($objArquivamentoDTO);

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

        if ($objArquivamentoDTO->getStrStaArquivamento()!=ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' não possui solicitação de desarquivamento.');
        }

        if ($objArquivamentoDTO->getNumIdUnidadeLocalizador()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' foi arquivado por outra unidade.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD(BancoSEI::getInstance());

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrValor($objArquivamentoDTO->getStrProtocoloFormatadoDocumento());
        $objAtributoAndamentoDTO->setStrIdOrigem($objArquivamentoDTO->getDblIdProtocolo());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('UNIDADE');
        $objAtributoAndamentoDTO->setStrValor($objArquivamentoDTO->getStrSiglaUnidadeSolicitacao().'¥'.$objArquivamentoDTO->getStrDescricaoUnidadeSolicitacao());
        $objAtributoAndamentoDTO->setStrIdOrigem($objArquivamentoDTO->getNumIdUnidadeSolicitacao());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objArquivamentoDTO->getDblIdProcedimentoDocumento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_CANCELADA_SOLICITACAO_DESARQUIVAMENTO);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        $dto = new ArquivamentoDTO();
        $dto->setNumIdAtividadeSolicitacao(null);
        $dto->setStrStaArquivamento(ArquivamentoRN::$TA_ARQUIVADO);
        $dto->setDblIdProtocolo($objArquivamentoDTO->getDblIdProtocolo());
        $objArquivamentoBD->alterar($dto);
      }

    }catch(Exception $e){
      throw new InfraException('Erro cancelando solicitação de desarquivamento do documento.',$e);
    }
  }

  protected function listarParaDesarquivamentoConectado(UnidadeDTO $objUnidadeDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_desarquivamento_listar',__METHOD__,$objUnidadeDTO);

      $objArquivamentoDTO = new ArquivamentoDTO();

      $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objArquivamentoDTO->setNumTipoFkSolicitacao(InfraDTO::$TIPO_FK_OBRIGATORIA);

      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retDblIdProcedimentoDocumento();
      $objArquivamentoDTO->retStrNomeTipoProcedimento();
      $objArquivamentoDTO->retStrProtocoloFormatadoProcedimento();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->retNumIdLocalizador();
      $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
      $objArquivamentoDTO->retStrSiglaTipoLocalizador();
      $objArquivamentoDTO->retStrNomeTipoLocalizador();
      $objArquivamentoDTO->retNumIdUnidadeLocalizador();
      $objArquivamentoDTO->retNumIdTipoLocalizador();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrNomeSerieDocumento();
      $objArquivamentoDTO->retStrNumeroDocumento();
      $objArquivamentoDTO->retStrStaEstadoLocalizador();

      $objArquivamentoDTO->retNumIdUnidadeSolicitacao();
      $objArquivamentoDTO->retStrSiglaUnidadeSolicitacao();
      $objArquivamentoDTO->retStrDescricaoUnidadeSolicitacao();
      $objArquivamentoDTO->retNumIdUsuarioSolicitacao();
      $objArquivamentoDTO->retStrSiglaUsuarioSolicitacao();
      $objArquivamentoDTO->retStrNomeUsuarioSolicitacao();

      $objArquivamentoDTO->setNumIdUnidadeLocalizador(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO);

      $objArquivamentoDTO->setNumIdUnidadeSolicitacao($objUnidadeDTO->getNumIdUnidade());

      $objArquivamentoDTO->setOrdDthAberturaSolicitacao(InfraDTO::$TIPO_ORDENACAO_DESC);

      $arrObjArquivamentoDTO = $this->listar($objArquivamentoDTO);

      return $arrObjArquivamentoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando documentos para desarquivamento.',$e);
    }
  }

  protected function listarParaEliminacaoConectado(ArquivamentoDTO $objArquivamentoDTO){
    try{

      //retornos
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retDblIdProcedimentoDocumento();
      $objArquivamentoDTO->retStrNomeTipoProcedimento();
      $objArquivamentoDTO->retStrProtocoloFormatadoProcedimento();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->retNumIdLocalizador();
      $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
      $objArquivamentoDTO->retStrSiglaTipoLocalizador();
      $objArquivamentoDTO->retStrNomeTipoLocalizador();
      $objArquivamentoDTO->retNumIdUnidadeLocalizador();
      $objArquivamentoDTO->retNumIdTipoLocalizador();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrNomeSerieDocumento();
      $objArquivamentoDTO->retStrNumeroDocumento();
      $objArquivamentoDTO->retStrStaEstadoLocalizador();
      $objArquivamentoDTO->retNumIdUnidadeRecebimento();

      $objArquivamentoDTO->retNumIdAtividadeRecebimento();
      $objArquivamentoDTO->retNumIdAtividadeArquivamento();
      $objArquivamentoDTO->retNumIdAtividadeSolicitacao();

      $this->configurarFiltroParaEliminacao($objArquivamentoDTO);

      //ordenamento
      $objArquivamentoDTO->setOrdStrSiglaTipoLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objArquivamentoDTO->setOrdNumSeqLocalizadorLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());

      $arrObjArquivamentoDTO = $objArquivamentoBD->listar($objArquivamentoDTO);

      return $arrObjArquivamentoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando documentos para eliminação.',$e);
    }
  }

  protected function listarLocalizadoresParaEliminacaoConectado(){
    try{

      //retornos
      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->setDistinct(true);
      $objArquivamentoDTO->retNumIdLocalizador();

      $this->configurarFiltroParaEliminacao($objArquivamentoDTO);

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());
      $arrObjArquivamentoDTO = $objArquivamentoBD->listar($objArquivamentoDTO);

      $arrObjLocalizadorDTO = array();
      if (count($arrObjArquivamentoDTO)){
        $objLocalizadorDTO = new LocalizadorDTO();
        $objLocalizadorDTO->retNumIdLocalizador();
        $objLocalizadorDTO->retStrSiglaTipoLocalizador();
        $objLocalizadorDTO->retNumSeqLocalizador();
        $objLocalizadorDTO->retStrIdentificacao();
        $objLocalizadorDTO->setNumIdLocalizador(InfraArray::converterArrInfraDTO($arrObjArquivamentoDTO,'IdLocalizador'),InfraDTO::$OPER_IN);
        $objLocalizadorDTO->setOrdStrSiglaTipoLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objLocalizadorDTO->setOrdNumSeqLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objLocalizadorRN = new LocalizadorRN();
        $arrObjLocalizadorDTO = $objLocalizadorRN->listarRN0622($objLocalizadorDTO);
      }

      return $arrObjLocalizadorDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando localizadores para eliminação.',$e);
    }
  }

  protected function listarTiposProcedimentoParaEliminacaoConectado(){
    try{

      //retornos
      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->setDistinct(true);
      $objArquivamentoDTO->retNumIdTipoProcedimentoProcedimento();
      $objArquivamentoDTO->retStrNomeTipoProcedimento();

      $this->configurarFiltroParaEliminacao($objArquivamentoDTO);

      $objArquivamentoDTO->setOrdStrNomeTipoProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());
      $arrObjArquivamentoDTO = $objArquivamentoBD->listar($objArquivamentoDTO);

      $arrObjTipoProcedimentoDTO = array();
      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){
        $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        $objTipoProcedimentoDTO->setNumIdTipoProcedimento($objArquivamentoDTO->getNumIdTipoProcedimentoProcedimento());
        $objTipoProcedimentoDTO->setStrNome($objArquivamentoDTO->getStrNomeTipoProcedimento());
        $arrObjTipoProcedimentoDTO[] = $objTipoProcedimentoDTO;
      }

      return $arrObjTipoProcedimentoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando tipos de processo para eliminação.',$e);
    }
  }

  protected function listarSeriesParaEliminacaoConectado(){
    try{

      //retornos
      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->setDistinct(true);
      $objArquivamentoDTO->retNumIdSerieDocumento();
      $objArquivamentoDTO->retStrNomeSerieDocumento();

      $this->configurarFiltroParaEliminacao($objArquivamentoDTO);

      $objArquivamentoDTO->setOrdStrNomeSerieDocumento(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());
      $arrObjArquivamentoDTO = $objArquivamentoBD->listar($objArquivamentoDTO);

      $arrObjSerieDTO = array();
      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){
        $objSerieDTO = new SerieDTO();
        $objSerieDTO->setNumIdSerie($objArquivamentoDTO->getNumIdSerieDocumento());
        $objSerieDTO->setStrNome($objArquivamentoDTO->getStrNomeSerieDocumento());
        $arrObjSerieDTO[] = $objSerieDTO;
      }

      return $arrObjSerieDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando tipos de documento para eliminação.',$e);
    }
  }

  private function configurarFiltroParaEliminacao(ArquivamentoDTO $objArquivamentoDTO){

    //documentos marcados para eliminacao
    $objArquivamentoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_PARA_ELIMINACAO);

    //apenas documentos que estao como recebidos ou arquivados nesta unidade
    $objArquivamentoDTO->adicionarCriterio(array('IdUnidadeLocalizador','IdUnidadeRecebimento'),
      array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
      array(SessaoSEI::getInstance()->getNumIdUnidadeAtual(),SessaoSEI::getInstance()->getNumIdUnidadeAtual()),
      InfraDTO::$OPER_LOGICO_OR);
  }

  protected function listarParaEditalEliminacaoConectado(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO){
    try{
      //busca os processos anexados
      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setDblIdProcedimento($objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental());
      $arrObjProcedimentosAnexadosDTO = (new ProcedimentoRN())->listarProcessosAnexados($objProcedimentoDTO);
      $idProtocolos = InfraArray::converterArrInfraDTO($arrObjProcedimentosAnexadosDTO, "IdProcedimento");
      //adiciona o id do proprio processo
      $idProtocolos[] = $objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental();

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrNomeSerieDocumento();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retDblIdProcedimentoDocumento();
      $objArquivamentoDTO->retNumIdLocalizador();
      $objArquivamentoDTO->retStrSiglaTipoLocalizador();
      $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
      $objArquivamentoDTO->retStrSiglaOrgaoUnidadeLocalizador();
      $objArquivamentoDTO->retStrSiglaUnidadeSolicitacao();
      $objArquivamentoDTO->retStrSiglaOrgaoUnidadeRecebimento();
      $objArquivamentoDTO->setStrStaArquivamento( array(ArquivamentoRN::$TA_ARQUIVADO, ArquivamentoRN::$TA_RECEBIDO), InfraDTO::$OPER_IN);
      $idProtocolos[] = $objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental();
      $objArquivamentoDTO->setDblIdProcedimentoDocumento($idProtocolos, InfraDTO::$OPER_IN);

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());

      $arrObjArquivamentoDTO = $objArquivamentoBD->listar($objArquivamentoDTO);

      return $arrObjArquivamentoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando documentos para edital de eliminação.',$e);
    }
  }

  protected function listarProcessosEliminadosConectado(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO){
    try{
      //busca os processos anexados
      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setDblIdProcedimento($objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental());
      $arrObjProcedimentosAnexadosDTO = (new ProcedimentoRN())->listarProcessosAnexados($objProcedimentoDTO);
      $idProtocolos = InfraArray::converterArrInfraDTO($arrObjProcedimentosAnexadosDTO, "IdProcedimento");
      //adiciona o id do proprio processo
      $idProtocolos[] = $objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental();

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrNomeSerieDocumento();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retDblIdProcedimentoDocumento();
      $objArquivamentoDTO->retNumIdLocalizador();
      $objArquivamentoDTO->retStrSiglaTipoLocalizador();
      $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
      $objArquivamentoDTO->retStrSiglaOrgaoUnidadeLocalizador();
      $objArquivamentoDTO->retStrSiglaUnidadeSolicitacao();
      $objArquivamentoDTO->retStrSiglaOrgaoUnidadeRecebimento();
      $objArquivamentoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_ELIMINADO);
      $idProtocolos[] = $objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental();
      $objArquivamentoDTO->setDblIdProcedimentoDocumento($idProtocolos, InfraDTO::$OPER_IN);

      $objArquivamentoBD = new ArquivamentoBD($this->getObjInfraIBanco());

      $arrObjArquivamentoDTO = $objArquivamentoBD->listar($objArquivamentoDTO);

      return $arrObjArquivamentoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando documentos para edital de eliminação.',$e);
    }
  }

  protected function migrarLocalizadorRN1163Controlado(ArquivamentoDTO $parObjArquivamentoDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_migrar_localizador',__METHOD__,$parObjArquivamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();


      if(InfraString::isBolVazia($parObjArquivamentoDTO->isSetNumIdLocalizador())){
        $objInfraException->lancarValidacao('Localizador de destino não informado.');
      }

      $objLocalizadorRN = new LocalizadorRN();


      //Obter dados do localizador de destino
      $objLocalizadorDTODestino = new LocalizadorDTO();
      $objLocalizadorDTODestino->retNumIdLocalizador();
      $objLocalizadorDTODestino->retNumIdUnidade();
      $objLocalizadorDTODestino->retStrStaEstado();
      $objLocalizadorDTODestino->retStrIdentificacao();
      $objLocalizadorDTODestino->setNumIdLocalizador($parObjArquivamentoDTO->getNumIdLocalizador());
      $objLocalizadorDTODestino = $objLocalizadorRN->consultarRN0619($objLocalizadorDTODestino);

      if($objLocalizadorDTODestino->getNumIdUnidade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
        $objInfraException->lancarValidacao('Localizador não pertence à unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().'.');
      }

      if($objLocalizadorDTODestino->getStrStaEstado() == LocalizadorRN::$EA_FECHADO){
        $objInfraException->lancarValidacao('Localizador de destino fechado.');
      }

      $arrIdProtocolos = $parObjArquivamentoDTO->getDblIdProtocolo();

      if(InfraArray::contar($arrIdProtocolos)==0){
        $objInfraException->lancarValidacao('Nenhum protocolo informado para migração.');
      }

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retDblIdProcedimentoDocumento();
      $objArquivamentoDTO->retStrStaArquivamento();
      $objArquivamentoDTO->retNumIdLocalizador();

      $objArquivamentoDTO->setDblIdProtocolo($arrIdProtocolos, InfraDTO::$OPER_IN);

      $arrObjArquivamentoDTO = $this->listar($objArquivamentoDTO);

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){
        if($objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_NAO_ARQUIVADO || $objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_DESARQUIVADO){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' não está arquivado.');
        }

        if($objArquivamentoDTO->getNumIdLocalizador() == $objLocalizadorDTODestino->getNumIdLocalizador()){
          $objInfraException->adicionarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' já pertence ao localizador de destino.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objArquivamentoBD = new ArquivamentoBD(BancoSEI::getInstance());

      foreach($arrObjArquivamentoDTO as $objArquivamentoDTO){

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrValor($objArquivamentoDTO->getStrProtocoloFormatadoDocumento());
        $objAtributoAndamentoDTO->setStrIdOrigem($objArquivamentoDTO->getDblIdProtocolo());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('LOCALIZADOR');
        $objAtributoAndamentoDTO->setStrValor($objLocalizadorDTODestino->getStrIdentificacao());
        $objAtributoAndamentoDTO->setStrIdOrigem($objLocalizadorDTODestino->getNumIdLocalizador());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objArquivamentoDTO->getDblIdProcedimentoDocumento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_MIGRACAO_LOCALIZADOR);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        $dto = new ArquivamentoDTO();
        $dto->setNumIdLocalizador($objLocalizadorDTODestino->getNumIdLocalizador());
        $dto->setDblIdProtocolo($objArquivamentoDTO->getDblIdProtocolo());
        $objArquivamentoBD->alterar($dto);

      }

    }catch(Exception $e){
      throw new InfraException('Erro migrando documento.',$e);
    }
  }

  protected function desarquivarParaEliminacaoControlado(ArquivamentoEliminacaoDTO $objArquivamentoEliminacaoDTO) {
    try{

      $objArquivamentoEliminacaoDTOAuditoria = clone($objArquivamentoEliminacaoDTO);
      $objArquivamentoEliminacaoDTOAuditoria->unSetStrSenha();
      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_eliminar',__METHOD__,$objArquivamentoEliminacaoDTOAuditoria);

      //valida a senha inserida na tela
      $objInfraSip = new InfraSip(SessaoSEI::getInstance());
      $objInfraSip->autenticar(SessaoSEI::getInstance()->getNumIdOrgaoUsuario(), null, SessaoSEI::getInstance()->getStrSiglaUsuario(), $objArquivamentoEliminacaoDTO->getStrSenha());
      //testa se foram passados (selecionados ou clicado) documentos para serem eliminados
      if(InfraArray::contar($objArquivamentoEliminacaoDTO->getArrObjArquivamentoDTO())){
        //objeto do BD
        $objArquivamentoBD = new ArquivamentoBD(BancoSEI::getInstance());
        //array que conterá os documentos a serem eliminados, após validacao
        $arrObjArquivoDTO_Eliminacao = array();

        $objDocumentoRN = new DocumentoRN();
        $objInfraException = new InfraException();
        foreach ($objArquivamentoEliminacaoDTO->getArrObjArquivamentoDTO() as $objArquivamentoDTO){
          $objArquivamentoDTO_Banco = new ArquivamentoDTO();
          $objArquivamentoDTO_Banco->retDblIdProtocolo();
          $objArquivamentoDTO_Banco->retStrStaArquivamento();
          $objArquivamentoDTO_Banco->retStrProtocoloFormatadoDocumento();
          $objArquivamentoDTO_Banco->setStrStaEliminacao(ArquivamentoRN::$TE_PARA_ELIMINACAO);

          //apenas documentos que estao como recebidos ou arquivados nesta unidade
          $objArquivamentoDTO_Banco->adicionarCriterio(array('IdUnidadeLocalizador','IdUnidadeRecebimento'),
                                                       array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
                                                       array(SessaoSEI::getInstance()->getNumIdUnidadeAtual(),SessaoSEI::getInstance()->getNumIdUnidadeAtual()),
                                                       InfraDTO::$OPER_LOGICO_OR);

          $objArquivamentoDTO_Banco->setDblIdProtocolo($objArquivamentoDTO->getDblIdProtocolo());
          $objArquivamentoDTO_Banco = $objArquivamentoBD->consultar($objArquivamentoDTO_Banco);
          if($objArquivamentoDTO_Banco == null){
            $objDocumentoDTO = new DocumentoDTO();
            $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
            $objDocumentoDTO->setDblIdDocumento($objArquivamentoDTO->getDblIdProtocolo());
            $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
            $objInfraException->adicionarValidacao("Documento ".$objDocumentoDTO->getStrProtocoloDocumentoFormatado()." não consta como para eliminação pela unidade atual. ");
          }else{

            if ($objArquivamentoDTO_Banco->getStrStaArquivamento() == ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO){
              $objInfraException->adicionarValidacao("Documento ".$objArquivamentoDTO_Banco->getStrProtocoloFormatadoDocumento()." possui solicitação de desarquivamento. ");
            }else {
              $arrObjArquivoDTO_Eliminacao[] = $objArquivamentoDTO;
            }
          }
        }

        $objInfraException->lancarValidacoes();

        if(InfraArray::contar($arrObjArquivoDTO_Eliminacao)) {
          $arrIdProtocolo = InfraArray::converterArrInfraDTO($arrObjArquivoDTO_Eliminacao, 'IdProtocolo');

          //libera os localizadores fechados antes da eliminação porque vai configurar id_localizador com null
          $objArquivamentoDTOLocalizadores = new ArquivamentoDTO();
          $objArquivamentoDTOLocalizadores->setDistinct(true);
          $objArquivamentoDTOLocalizadores->retNumIdLocalizador();
          $objArquivamentoDTOLocalizadores->setStrStaEstadoLocalizador(LocalizadorRN::$EA_FECHADO);
          $objArquivamentoDTOLocalizadores->setDblIdProtocolo($arrIdProtocolo, InfraDTO::$OPER_IN);
          $objArquivamentoDTOLocalizadores->setNumIdLocalizador(null,InfraDTO::$OPER_DIFERENTE);
          $arrIdLocalizadoresFechados = InfraArray::converterArrInfraDTO($this->listar($objArquivamentoDTOLocalizadores), 'IdLocalizador');

          if (InfraArray::contar($arrIdLocalizadoresFechados)) {
            $objLocalizadorRN = new LocalizadorRN();
            foreach ($arrIdLocalizadoresFechados as $idLocalizadoresFechados) {
              $objLocalizadorDTO = new LocalizadorDTO();
              $objLocalizadorDTO->setNumIdLocalizador($idLocalizadoresFechados);
              $objLocalizadorDTO->setStrStaEstado(LocalizadorRN::$EA_ABERTO);
              $objLocalizadorRN->alterarRN0618($objLocalizadorDTO);
            }
          }

          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->retDblIdProcedimento();
          $objDocumentoDTO->retDblIdDocumento();
          $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
          $objDocumentoDTO->setDblIdDocumento($arrIdProtocolo, InfraDTO::$OPER_IN);

          $objDocumentoRN = new DocumentoRN();
          $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);
          $arrObjDocumentoDTOPorProcedimento = InfraArray::indexarArrInfraDTO($arrObjDocumentoDTO, 'IdProcedimento', true);

          foreach ($arrObjDocumentoDTOPorProcedimento as $IdProcedimento => $arrObjDocumentoDTO) {
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('DOCUMENTOS');
            $objAtributoAndamentoDTO->setStrValor(null);
            $objAtributoAndamentoDTO->setStrIdOrigem(null);
            $arrObjAtributoAndamentoDTO = array($objAtributoAndamentoDTO);
            foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
              $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
              $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
              $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
            }
            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($IdProcedimento);
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_DESARQUIVAMENTO_PARA_ELIMINACAO);
            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
            $objAtividadeRN = new AtividadeRN();
            $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

            foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
              $objArquivamentoDTO = new ArquivamentoDTO();
              $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_NAO_ARQUIVADO);
              $objArquivamentoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_ELIMINADO);
              //$objArquivamentoDTO->setNumIdLocalizador(null);
              //$objArquivamentoDTO->setNumIdAtividadeArquivamento(null);
              //$objArquivamentoDTO->setNumIdAtividadeRecebimento(null);
              //$objArquivamentoDTO->setNumIdAtividadeCancelamento(null);
              //$objArquivamentoDTO->setNumIdAtividadeSolicitacao(null);
              //$objArquivamentoDTO->setNumIdAtividadeDesarquivamento(null);
              $objArquivamentoDTO->setNumIdAtividadeEliminacao($objAtividadeDTO->getNumIdAtividade());
              $objArquivamentoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
              $objArquivamentoBD->alterar($objArquivamentoDTO);
            }
          }
        }
      }
    }catch(Exception $e){
      throw new InfraException('Erro desarquivando documento para eliminação.',$e);
    }
  }

  protected function validarProtocoloArquivadoAlteracaoConectado(ProtocoloDTO $parObjProtocoloDTO){

    $objInfraException = new InfraException();

    $objArquivamentoDTO = new ArquivamentoDTO();
    $objArquivamentoDTO->retStrStaArquivamento();
    $objArquivamentoDTO->retStrSiglaUnidadeArquivamento();
    $objArquivamentoDTO->retStrSiglaUnidadeRecebimento();
    $objArquivamentoDTO->retStrSiglaUnidadeSolicitacao();
    $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
    $objArquivamentoDTO->setDblIdProtocolo($parObjProtocoloDTO->getDblIdProtocolo());

    $objArquivamentoDTO = $this->consultar($objArquivamentoDTO);

    if ($objArquivamentoDTO!=null) {
      if ($objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_RECEBIDO) {
        $objInfraException->lancarValidacao('Documento ' . $objArquivamentoDTO->getStrProtocoloFormatadoDocumento() . ' consta como recebido para arquivamento na unidade ' . $objArquivamentoDTO->getStrSiglaUnidadeRecebimento() . '.');
      } else if ($objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_ARQUIVADO) {
        $objInfraException->lancarValidacao('Documento ' . $objArquivamentoDTO->getStrProtocoloFormatadoDocumento() . ' consta como arquivado na unidade ' . $objArquivamentoDTO->getStrSiglaUnidadeArquivamento() . '.');
      } else if ($objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO) {
        $objInfraException->lancarValidacao('Documento ' . $objArquivamentoDTO->getStrProtocoloFormatadoDocumento() . ' possui solicitação de desarquivamento realizada pela unidade ' . $objArquivamentoDTO->getStrSiglaUnidadeSolicitacao() . '.');
      }
    }

    return $objArquivamentoDTO;
  }

  protected function validarProtocoloArquivadoExclusaoConectado(ProtocoloDTO $parObjProtocoloDTO){

    $objInfraException = new InfraException();

    $objArquivamentoDTO = $this->validarProtocoloArquivadoAlteracao($parObjProtocoloDTO);

    if ($objArquivamentoDTO!=null) {
      if ($objArquivamentoDTO->getStrStaArquivamento() == ArquivamentoRN::$TA_DESARQUIVADO) {
        $objInfraException->lancarValidacao('Documento '.$objArquivamentoDTO->getStrProtocoloFormatadoDocumento().' já teve arquivamento realizado pela unidade '.$objArquivamentoDTO->getStrSiglaUnidadeSolicitacao().'.');
      }
    }

    return $objArquivamentoDTO;
  }

  protected function sinalizarParaEliminacaoControlado(ArquivamentoDTO $parObjArquivamentoDTO){
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('arquivamento_eliminacao_sinalizar', __METHOD__, $parObjArquivamentoDTO);

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->setStrStaEliminacao(self::$TE_PARA_ELIMINACAO);
      $objArquivamentoDTO->setDblIdProtocolo($parObjArquivamentoDTO->getDblIdProtocolo());

      $objArquivamentoBD = new ArquivamentoBD(BancoSEI::getInstance());
      $objArquivamentoBD->alterar($objArquivamentoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro sinalizando documento para eliminação.',$e);
    }
  }
}
?>