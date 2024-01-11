<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class RelItemEtapaSerieRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  private function validarNumIdItemEtapa(RelItemEtapaSerieDTO $objRelItemEtapaSerieDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelItemEtapaSerieDTO->getNumIdItemEtapa())) {
      $objInfraException->adicionarValidacao('Item não informado.');
    }
  }

  private function validarNumIdSerie(RelItemEtapaSerieDTO $objRelItemEtapaSerieDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelItemEtapaSerieDTO->getNumIdSerie())) {
      $objInfraException->adicionarValidacao('Tipo de Documento não informado.');
    }
  }

  protected function cadastrarControlado(RelItemEtapaSerieDTO $objRelItemEtapaSerieDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_serie_cadastrar', __METHOD__, $objRelItemEtapaSerieDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdItemEtapa($objRelItemEtapaSerieDTO, $objInfraException);
      $this->validarNumIdSerie($objRelItemEtapaSerieDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelItemEtapaSerieBD = new RelItemEtapaSerieBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaSerieBD->cadastrar($objRelItemEtapaSerieDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Tipo de Documento do Item.', $e);
    }
  }

  protected function alterarControlado(RelItemEtapaSerieDTO $objRelItemEtapaSerieDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_serie_alterar', __METHOD__, $objRelItemEtapaSerieDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelItemEtapaSerieDTO->isSetNumIdItemEtapa()) {
        $this->validarNumIdItemEtapa($objRelItemEtapaSerieDTO, $objInfraException);
      }
      if ($objRelItemEtapaSerieDTO->isSetNumIdSerie()) {
        $this->validarNumIdSerie($objRelItemEtapaSerieDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelItemEtapaSerieBD = new RelItemEtapaSerieBD($this->getObjInfraIBanco());
      $objRelItemEtapaSerieBD->alterar($objRelItemEtapaSerieDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Tipo de Documento do Item.', $e);
    }
  }

  protected function excluirControlado($arrObjRelItemEtapaSerieDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_serie_excluir', __METHOD__, $arrObjRelItemEtapaSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaSerieBD = new RelItemEtapaSerieBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRelItemEtapaSerieDTO); $i++) {
        $objRelItemEtapaSerieBD->excluir($arrObjRelItemEtapaSerieDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Tipo de Documento do Item.', $e);
    }
  }

  protected function consultarConectado(RelItemEtapaSerieDTO $objRelItemEtapaSerieDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_serie_consultar', __METHOD__, $objRelItemEtapaSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaSerieBD = new RelItemEtapaSerieBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaSerieBD->consultar($objRelItemEtapaSerieDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Tipo de Documento do Item.', $e);
    }
  }

  protected function listarConectado(RelItemEtapaSerieDTO $objRelItemEtapaSerieDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_serie_listar', __METHOD__, $objRelItemEtapaSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaSerieBD = new RelItemEtapaSerieBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaSerieBD->listar($objRelItemEtapaSerieDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Tipos de Documentos do Item.', $e);
    }
  }

  protected function contarConectado(RelItemEtapaSerieDTO $objRelItemEtapaSerieDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_serie_listar', __METHOD__, $objRelItemEtapaSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaSerieBD = new RelItemEtapaSerieBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaSerieBD->contar($objRelItemEtapaSerieDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Tipos de Documentos do Item.', $e);
    }
  }
  /*
    protected function desativarControlado($arrObjRelItemEtapaSerieDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_serie_desativar', __METHOD__, $arrObjRelItemEtapaSerieDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelItemEtapaSerieBD = new RelItemEtapaSerieBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelItemEtapaSerieDTO);$i++){
          $objRelItemEtapaSerieBD->desativar($arrObjRelItemEtapaSerieDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro desativando Tipo de Documento do Item.',$e);
      }
    }

    protected function reativarControlado($arrObjRelItemEtapaSerieDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_serie_reativar', __METHOD__, $arrObjRelItemEtapaSerieDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelItemEtapaSerieBD = new RelItemEtapaSerieBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelItemEtapaSerieDTO);$i++){
          $objRelItemEtapaSerieBD->reativar($arrObjRelItemEtapaSerieDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro reativando Tipo de Documento do Item.',$e);
      }
    }

    protected function bloquearControlado(RelItemEtapaSerieDTO $objRelItemEtapaSerieDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_serie_consultar', __METHOD__, $objRelItemEtapaSerieDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelItemEtapaSerieBD = new RelItemEtapaSerieBD($this->getObjInfraIBanco());
        $ret = $objRelItemEtapaSerieBD->bloquear($objRelItemEtapaSerieDTO);

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando Tipo de Documento do Item.',$e);
      }
    }

   */
}
