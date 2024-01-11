<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class RelItemEtapaUnidadeRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  protected function cadastrarControlado(RelItemEtapaUnidadeDTO $objRelItemEtapaUnidadeDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_unidade_cadastrar', __METHOD__, $objRelItemEtapaUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();


      $objInfraException->lancarValidacoes();

      $objRelItemEtapaUnidadeBD = new RelItemEtapaUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaUnidadeBD->cadastrar($objRelItemEtapaUnidadeDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Unidade do Item.', $e);
    }
  }

  protected function alterarControlado(RelItemEtapaUnidadeDTO $objRelItemEtapaUnidadeDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_unidade_alterar', __METHOD__, $objRelItemEtapaUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();


      $objInfraException->lancarValidacoes();

      $objRelItemEtapaUnidadeBD = new RelItemEtapaUnidadeBD($this->getObjInfraIBanco());
      $objRelItemEtapaUnidadeBD->alterar($objRelItemEtapaUnidadeDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Unidade do Item.', $e);
    }
  }

  protected function excluirControlado($arrObjRelItemEtapaUnidadeDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_unidade_excluir', __METHOD__, $arrObjRelItemEtapaUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaUnidadeBD = new RelItemEtapaUnidadeBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRelItemEtapaUnidadeDTO); $i++) {
        $objRelItemEtapaUnidadeBD->excluir($arrObjRelItemEtapaUnidadeDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Unidade do Item.', $e);
    }
  }

  protected function consultarConectado(RelItemEtapaUnidadeDTO $objRelItemEtapaUnidadeDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_unidade_consultar', __METHOD__, $objRelItemEtapaUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaUnidadeBD = new RelItemEtapaUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaUnidadeBD->consultar($objRelItemEtapaUnidadeDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Unidade do Item.', $e);
    }
  }

  protected function listarConectado(RelItemEtapaUnidadeDTO $objRelItemEtapaUnidadeDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_unidade_listar', __METHOD__, $objRelItemEtapaUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaUnidadeBD = new RelItemEtapaUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaUnidadeBD->listar($objRelItemEtapaUnidadeDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Unidades do Item.', $e);
    }
  }

  protected function contarConectado(RelItemEtapaUnidadeDTO $objRelItemEtapaUnidadeDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_unidade_listar', __METHOD__, $objRelItemEtapaUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelItemEtapaUnidadeBD = new RelItemEtapaUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelItemEtapaUnidadeBD->contar($objRelItemEtapaUnidadeDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Unidades do Item.', $e);
    }
  }
  /*
    protected function desativarControlado($arrObjRelItemEtapaUnidadeDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_unidade_desativar', __METHOD__, $arrObjRelItemEtapaUnidadeDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelItemEtapaUnidadeBD = new RelItemEtapaUnidadeBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelItemEtapaUnidadeDTO);$i++){
          $objRelItemEtapaUnidadeBD->desativar($arrObjRelItemEtapaUnidadeDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro desativando Unidade do Item.',$e);
      }
    }

    protected function reativarControlado($arrObjRelItemEtapaUnidadeDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_unidade_reativar', __METHOD__, $arrObjRelItemEtapaUnidadeDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelItemEtapaUnidadeBD = new RelItemEtapaUnidadeBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelItemEtapaUnidadeDTO);$i++){
          $objRelItemEtapaUnidadeBD->reativar($arrObjRelItemEtapaUnidadeDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro reativando Unidade do Item.',$e);
      }
    }

    protected function bloquearControlado(RelItemEtapaUnidadeDTO $objRelItemEtapaUnidadeDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('rel_item_etapa_unidade_consultar', __METHOD__, $objRelItemEtapaUnidadeDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelItemEtapaUnidadeBD = new RelItemEtapaUnidadeBD($this->getObjInfraIBanco());
        $ret = $objRelItemEtapaUnidadeBD->bloquear($objRelItemEtapaUnidadeDTO);

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando Unidade do Item.',$e);
      }
    }

   */
}
