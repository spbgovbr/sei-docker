<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 25/10/2011 - criado por mga
 *
 * Versão do Gerador de Código: 1.32.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../Sip.php';

class RelRegraAuditoriaRecursoRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  private function validarNumIdSistema(
    RelRegraAuditoriaRecursoDTO $objRelRegraAuditoriaRecursoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelRegraAuditoriaRecursoDTO->getNumIdSistema())) {
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
  }

  private function validarNumIdRecurso(
    RelRegraAuditoriaRecursoDTO $objRelRegraAuditoriaRecursoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelRegraAuditoriaRecursoDTO->getNumIdRecurso())) {
      $objInfraException->adicionarValidacao('Recurso não informado.');
    }
  }

  private function validarNumIdRegraAuditoria(
    RelRegraAuditoriaRecursoDTO $objRelRegraAuditoriaRecursoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelRegraAuditoriaRecursoDTO->getNumIdRegraAuditoria())) {
      $objInfraException->adicionarValidacao('Regra de Auditoria não informada.');
    }
  }

  protected function cadastrarControlado(RelRegraAuditoriaRecursoDTO $objRelRegraAuditoriaRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_regra_auditoria_recurso_cadastrar', __METHOD__, $objRelRegraAuditoriaRecursoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSistema($objRelRegraAuditoriaRecursoDTO, $objInfraException);
      $this->validarNumIdRecurso($objRelRegraAuditoriaRecursoDTO, $objInfraException);
      $this->validarNumIdRegraAuditoria($objRelRegraAuditoriaRecursoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelRegraAuditoriaRecursoBD = new RelRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $ret = $objRelRegraAuditoriaRecursoBD->cadastrar($objRelRegraAuditoriaRecursoDTO);

      //RegraAuditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Recurso Auditado.', $e);
    }
  }

  protected function alterarControlado(RelRegraAuditoriaRecursoDTO $objRelRegraAuditoriaRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_regra_auditoria_recurso_alterar', __METHOD__, $objRelRegraAuditoriaRecursoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelRegraAuditoriaRecursoDTO->isSetNumIdSistema()) {
        $this->validarNumIdSistema($objRelRegraAuditoriaRecursoDTO, $objInfraException);
      }
      if ($objRelRegraAuditoriaRecursoDTO->isSetNumIdRecurso()) {
        $this->validarNumIdRecurso($objRelRegraAuditoriaRecursoDTO, $objInfraException);
      }
      if ($objRelRegraAuditoriaRecursoDTO->isSetNumIdRegraAuditoria()) {
        $this->validarNumIdRegraAuditoria($objRelRegraAuditoriaRecursoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelRegraAuditoriaRecursoBD = new RelRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $objRelRegraAuditoriaRecursoBD->alterar($objRelRegraAuditoriaRecursoDTO);
      //RegraAuditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando Recurso Auditado.', $e);
    }
  }

  protected function excluirControlado($arrObjRelRegraAuditoriaRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_regra_auditoria_recurso_excluir', __METHOD__, $arrObjRelRegraAuditoriaRecursoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelRegraAuditoriaRecursoBD = new RelRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRelRegraAuditoriaRecursoDTO); $i++) {
        $objRelRegraAuditoriaRecursoBD->excluir($arrObjRelRegraAuditoriaRecursoDTO[$i]);
      }
      //RegraAuditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Recurso Auditado.', $e);
    }
  }

  protected function consultarConectado(RelRegraAuditoriaRecursoDTO $objRelRegraAuditoriaRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_regra_auditoria_recurso_consultar', __METHOD__, $objRelRegraAuditoriaRecursoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelRegraAuditoriaRecursoBD = new RelRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $ret = $objRelRegraAuditoriaRecursoBD->consultar($objRelRegraAuditoriaRecursoDTO);

      //RegraAuditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Recurso Auditado.', $e);
    }
  }

  protected function listarConectado(RelRegraAuditoriaRecursoDTO $objRelRegraAuditoriaRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_regra_auditoria_recurso_listar', __METHOD__, $objRelRegraAuditoriaRecursoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelRegraAuditoriaRecursoBD = new RelRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $ret = $objRelRegraAuditoriaRecursoBD->listar($objRelRegraAuditoriaRecursoDTO);

      //RegraAuditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Recursos Auditados.', $e);
    }
  }

  protected function contarConectado(RelRegraAuditoriaRecursoDTO $objRelRegraAuditoriaRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_regra_auditoria_recurso_listar', __METHOD__, $objRelRegraAuditoriaRecursoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelRegraAuditoriaRecursoBD = new RelRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $ret = $objRelRegraAuditoriaRecursoBD->contar($objRelRegraAuditoriaRecursoDTO);

      //RegraAuditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Recursos Auditados.', $e);
    }
  }
  /*
    protected function desativarControlado($arrObjRelRegraAuditoriaRecursoDTO){
      try {

        //Valida Permissao
        SessaoSip::getInstance()->validarAuditarPermissao('rel_regra_auditoria_recurso_desativar',__METHOD__,$arrObjRelRegraAuditoriaRecursoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelRegraAuditoriaRecursoBD = new RelRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelRegraAuditoriaRecursoDTO);$i++){
          $objRelRegraAuditoriaRecursoBD->desativar($arrObjRelRegraAuditoriaRecursoDTO[$i]);
        }

        //RegraAuditoria

      }catch(Exception $e){
        throw new InfraException('Erro desativando Recurso Auditado.',$e);
      }
    }

    protected function reativarControlado($arrObjRelRegraAuditoriaRecursoDTO){
      try {

        //Valida Permissao
        SessaoSip::getInstance()->validarAuditarPermissao('rel_regra_auditoria_recurso_reativar',__METHOD__,$arrObjRelRegraAuditoriaRecursoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelRegraAuditoriaRecursoBD = new RelRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelRegraAuditoriaRecursoDTO);$i++){
          $objRelRegraAuditoriaRecursoBD->reativar($arrObjRelRegraAuditoriaRecursoDTO[$i]);
        }

        //RegraAuditoria

      }catch(Exception $e){
        throw new InfraException('Erro reativando Recurso Auditado.',$e);
      }
    }

    protected function bloquearControlado(RelRegraAuditoriaRecursoDTO $objRelRegraAuditoriaRecursoDTO){
      try {

        //Valida Permissao
        SessaoSip::getInstance()->validarAuditarPermissao('rel_regra_auditoria_recurso_consultar',__METHOD__,$objRelRegraAuditoriaRecursoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelRegraAuditoriaRecursoBD = new RelRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
        $ret = $objRelRegraAuditoriaRecursoBD->bloquear($objRelRegraAuditoriaRecursoDTO);

        //RegraAuditoria

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando Recurso Auditado.',$e);
      }
    }

   */
}

?>