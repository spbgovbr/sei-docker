<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class RelItemEtapaDocumentoRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  private function validarDblIdDocumento(RelItemEtapaDocumentoDTO $objRelItemEtapaDocumentoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelItemEtapaDocumentoDTO->getDblIdDocumento())) {
      $objInfraException->adicionarValidacao('Documento não informado.');
    }
  }

  private function validarNumIdItemEtapa(RelItemEtapaDocumentoDTO $objRelItemEtapaDocumentoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelItemEtapaDocumentoDTO->getNumIdItemEtapa())) {
      $objInfraException->adicionarValidacao('Item não informado.');
    }
  }

  protected function cadastrarControlado(RelItemEtapaDocumentoDTO $parObjRelItemEtapaDocumentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_documento_cadastrar', __METHOD__, $parObjRelItemEtapaDocumentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdDocumento($parObjRelItemEtapaDocumentoDTO, $objInfraException);
      $this->validarNumIdItemEtapa($parObjRelItemEtapaDocumentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelItemEtapaDocumentoBD = new RelItemEtapaDocumentoBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaDocumentoBD->cadastrar($parObjRelItemEtapaDocumentoDTO);

      $objItemEtapaDTO = new ItemEtapaDTO();
      $objItemEtapaDTO->retNumIdPlanoTrabalhoEtapaTrabalho();
      $objItemEtapaDTO->retNumIdEtapaTrabalho();
      $objItemEtapaDTO->retNumIdItemEtapa();
      $objItemEtapaDTO->setNumIdItemEtapa($parObjRelItemEtapaDocumentoDTO->getNumIdItemEtapa());

      $objItemEtapaRN = new ItemEtapaRN();
      $objItemEtapaDTO = $objItemEtapaRN->consultar($objItemEtapaDTO);

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->setDblIdDocumento($parObjRelItemEtapaDocumentoDTO->getDblIdDocumento());

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

      $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
      $objAtributoAndamPlanoTrabDTO->setNumMaxRegistrosRetorno(1);
      $objAtributoAndamPlanoTrabDTO->retNumIdAtributoAndamPlanoTrab();
      $objAtributoAndamPlanoTrabDTO->setStrChave('ITEM_ETAPA');
      $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($parObjRelItemEtapaDocumentoDTO->getNumIdItemEtapa());
      $objAtributoAndamPlanoTrabDTO->setDblIdProcedimentoAndamentoPlanoTrabalho($objDocumentoDTO->getDblIdProcedimento());
      $objAtributoAndamPlanoTrabDTO->setStrStaSituacaoAndamentoPlanoTrabalho(null, InfraDTO::$OPER_DIFERENTE);

      $objAtributoAndamPlanoTrabRN = new AtributoAndamPlanoTrabRN();
      if ($objAtributoAndamPlanoTrabRN->consultar($objAtributoAndamPlanoTrabDTO) == null) {
        $strStaSituacao = AndamentoPlanoTrabalhoRN::$SA_EM_ANDAMENTO;
      } else {
        $strStaSituacao = null;
      }

      $objAndamentoPlanoTrabalhoDTO = new AndamentoPlanoTrabalhoDTO();
      $objAndamentoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($objItemEtapaDTO->getNumIdPlanoTrabalhoEtapaTrabalho());
      $objAndamentoPlanoTrabalhoDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
      $objAndamentoPlanoTrabalhoDTO->setNumIdTarefaPlanoTrabalho(TarefaPlanoTrabalhoRN::$TPT_ASSOCIACAO_DOCUMENTO_ITEM_ETAPA);
      $objAndamentoPlanoTrabalhoDTO->setStrStaSituacao($strStaSituacao);

      $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
      $objAtributoAndamPlanoTrabDTO->setStrChave('ITEM_ETAPA');
      $objAtributoAndamPlanoTrabDTO->setStrValor(null);
      $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($objItemEtapaDTO->getNumIdItemEtapa());
      $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

      $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
      $objAtributoAndamPlanoTrabDTO->setStrChave('ETAPA_TRABALHO');
      $objAtributoAndamPlanoTrabDTO->setStrValor(null);
      $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($objItemEtapaDTO->getNumIdEtapaTrabalho());
      $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

      $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
      $objAtributoAndamPlanoTrabDTO->setStrChave('DOCUMENTO');
      $objAtributoAndamPlanoTrabDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
      $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
      $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

      $objAndamentoPlanoTrabalhoDTO->setArrObjAtributoAndamPlanoTrabDTO($arrObjAtributoAndamPlanoTrabDTO);

      $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
      $objAndamentoPlanoTrabalhoRN->lancar($objAndamentoPlanoTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Documento do Item.', $e);
    }
  }

  protected function alterarControlado(RelItemEtapaDocumentoDTO $objRelItemEtapaDocumentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_documento_alterar', __METHOD__, $objRelItemEtapaDocumentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelItemEtapaDocumentoDTO->isSetDblIdDocumento()) {
        $this->validarDblIdDocumento($objRelItemEtapaDocumentoDTO, $objInfraException);
      }
      if ($objRelItemEtapaDocumentoDTO->isSetNumIdItemEtapa()) {
        $this->validarNumIdItemEtapa($objRelItemEtapaDocumentoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelItemEtapaDocumentoBD = new RelItemEtapaDocumentoBD($this->getObjInfraIBanco());
      $objRelItemEtapaDocumentoBD->alterar($objRelItemEtapaDocumentoDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Documento do Item.', $e);
    }
  }

  protected function excluirControlado($arrObjRelItemEtapaDocumentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_documento_excluir', __METHOD__, $arrObjRelItemEtapaDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaDocumentoBD = new RelItemEtapaDocumentoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRelItemEtapaDocumentoDTO); $i++) {
        $objRelItemEtapaDocumentoDTO = $arrObjRelItemEtapaDocumentoDTO[$i];

        $objItemEtapaDTO = new ItemEtapaDTO();
        $objItemEtapaDTO->retNumIdPlanoTrabalhoEtapaTrabalho();
        $objItemEtapaDTO->retNumIdEtapaTrabalho();
        $objItemEtapaDTO->retNumIdItemEtapa();
        $objItemEtapaDTO->setNumIdItemEtapa($objRelItemEtapaDocumentoDTO->getNumIdItemEtapa());

        $objItemEtapaRN = new ItemEtapaRN();
        $objItemEtapaDTO = $objItemEtapaRN->consultar($objItemEtapaDTO);

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retDblIdProcedimento();
        $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
        $objDocumentoDTO->setDblIdDocumento($objRelItemEtapaDocumentoDTO->getDblIdDocumento());

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

        $objAndamentoPlanoTrabalhoDTO = new AndamentoPlanoTrabalhoDTO();
        $objAndamentoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($objItemEtapaDTO->getNumIdPlanoTrabalhoEtapaTrabalho());
        $objAndamentoPlanoTrabalhoDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
        $objAndamentoPlanoTrabalhoDTO->setNumIdTarefaPlanoTrabalho(TarefaPlanoTrabalhoRN::$TPT_REMOCAO_ASSOCIACAO_DOCUMENTO_ITEM_ETAPA);
        $objAndamentoPlanoTrabalhoDTO->setStrStaSituacao(null);

        $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
        $objAtributoAndamPlanoTrabDTO->setStrChave('ITEM_ETAPA');
        $objAtributoAndamPlanoTrabDTO->setStrValor(null);
        $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($objItemEtapaDTO->getNumIdItemEtapa());
        $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

        $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
        $objAtributoAndamPlanoTrabDTO->setStrChave('ETAPA_TRABALHO');
        $objAtributoAndamPlanoTrabDTO->setStrValor(null);
        $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($objItemEtapaDTO->getNumIdEtapaTrabalho());
        $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

        $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
        $objAtributoAndamPlanoTrabDTO->setStrChave('DOCUMENTO');
        $objAtributoAndamPlanoTrabDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
        $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

        $objAndamentoPlanoTrabalhoDTO->setArrObjAtributoAndamPlanoTrabDTO($arrObjAtributoAndamPlanoTrabDTO);

        $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
        $objAndamentoPlanoTrabalhoRN->lancar($objAndamentoPlanoTrabalhoDTO);


        $objRelItemEtapaDocumentoBD->excluir($objRelItemEtapaDocumentoDTO);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Documento do Item.', $e);
    }
  }

  protected function consultarConectado(RelItemEtapaDocumentoDTO $objRelItemEtapaDocumentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_documento_consultar', __METHOD__, $objRelItemEtapaDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaDocumentoBD = new RelItemEtapaDocumentoBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaDocumentoBD->consultar($objRelItemEtapaDocumentoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Documento do Item.', $e);
    }
  }

  protected function listarConectado(RelItemEtapaDocumentoDTO $objRelItemEtapaDocumentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_documento_listar', __METHOD__, $objRelItemEtapaDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaDocumentoBD = new RelItemEtapaDocumentoBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaDocumentoBD->listar($objRelItemEtapaDocumentoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Documentos do Item.', $e);
    }
  }

  protected function contarConectado(RelItemEtapaDocumentoDTO $objRelItemEtapaDocumentoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_documento_listar', __METHOD__, $objRelItemEtapaDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaDocumentoBD = new RelItemEtapaDocumentoBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaDocumentoBD->contar($objRelItemEtapaDocumentoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Documentos do Item.', $e);
    }
  }
  /*
    protected function desativarControlado($arrObjRelItemEtapaDocumentoDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_documento_desativar', __METHOD__, $arrObjRelItemEtapaDocumentoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelItemEtapaDocumentoBD = new RelItemEtapaDocumentoBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelItemEtapaDocumentoDTO);$i++){
          $objRelItemEtapaDocumentoBD->desativar($arrObjRelItemEtapaDocumentoDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro desativando Documento do Item.',$e);
      }
    }

    protected function reativarControlado($arrObjRelItemEtapaDocumentoDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_documento_reativar', __METHOD__, $arrObjRelItemEtapaDocumentoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelItemEtapaDocumentoBD = new RelItemEtapaDocumentoBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelItemEtapaDocumentoDTO);$i++){
          $objRelItemEtapaDocumentoBD->reativar($arrObjRelItemEtapaDocumentoDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro reativando Documento do Item.',$e);
      }
    }

    protected function bloquearControlado(RelItemEtapaDocumentoDTO $objRelItemEtapaDocumentoDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_documento_consultar', __METHOD__, $objRelItemEtapaDocumentoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelItemEtapaDocumentoBD = new RelItemEtapaDocumentoBD($this->getObjInfraIBanco());
        $ret = $objRelItemEtapaDocumentoBD->bloquear($objRelItemEtapaDocumentoDTO);

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando Documento do Item.',$e);
      }
    }

   */
}
