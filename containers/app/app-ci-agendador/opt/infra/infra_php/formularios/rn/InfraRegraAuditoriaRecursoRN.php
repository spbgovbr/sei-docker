<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/11/2011 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

//require_once 'Infra.php';

class InfraRegraAuditoriaRecursoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoInfra::getInstance();
  }

  private function validarNumIdInfraRegraAuditoria(InfraRegraAuditoriaRecursoDTO $objInfraRegraAuditoriaRecursoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraRegraAuditoriaRecursoDTO->getNumIdInfraRegraAuditoria())){
      $objInfraException->adicionarValidacao('Regra de Auditoria não informada.');
    }
  }

  protected function cadastrarControlado(InfraRegraAuditoriaRecursoDTO $objInfraRegraAuditoriaRecursoDTO) {
    try{

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_recurso_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdInfraRegraAuditoria($objInfraRegraAuditoriaRecursoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaRecursoBD = new InfraRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $ret = $objInfraRegraAuditoriaRecursoBD->cadastrar($objInfraRegraAuditoriaRecursoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Recurso Auditado.',$e);
    }
  }

  protected function alterarControlado(InfraRegraAuditoriaRecursoDTO $objInfraRegraAuditoriaRecursoDTO){
    try {

      //Valida Permissao
  	   SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_recurso_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objInfraRegraAuditoriaRecursoDTO->isSetNumIdInfraRegraAuditoria()){
        $this->validarNumIdInfraRegraAuditoria($objInfraRegraAuditoriaRecursoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaRecursoBD = new InfraRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $objInfraRegraAuditoriaRecursoBD->alterar($objInfraRegraAuditoriaRecursoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Recurso Auditado.',$e);
    }
  }

  protected function excluirControlado($arrObjInfraRegraAuditoriaRecursoDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_recurso_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaRecursoBD = new InfraRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraRegraAuditoriaRecursoDTO);$i++){
        $objInfraRegraAuditoriaRecursoBD->excluir($arrObjInfraRegraAuditoriaRecursoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Recurso Auditado.',$e);
    }
  }

  protected function consultarConectado(InfraRegraAuditoriaRecursoDTO $objInfraRegraAuditoriaRecursoDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_recurso_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaRecursoBD = new InfraRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $ret = $objInfraRegraAuditoriaRecursoBD->consultar($objInfraRegraAuditoriaRecursoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Recurso Auditado.',$e);
    }
  }

  protected function listarConectado(InfraRegraAuditoriaRecursoDTO $objInfraRegraAuditoriaRecursoDTO) {
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_recurso_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaRecursoBD = new InfraRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $ret = $objInfraRegraAuditoriaRecursoBD->listar($objInfraRegraAuditoriaRecursoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Recursos Auditados.',$e);
    }
  }

  protected function contarConectado(InfraRegraAuditoriaRecursoDTO $objInfraRegraAuditoriaRecursoDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_recurso_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaRecursoBD = new InfraRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $ret = $objInfraRegraAuditoriaRecursoBD->contar($objInfraRegraAuditoriaRecursoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Recursos Auditados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjInfraRegraAuditoriaRecursoDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_recurso_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaRecursoBD = new InfraRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraRegraAuditoriaRecursoDTO);$i++){
        $objInfraRegraAuditoriaRecursoBD->desativar($arrObjInfraRegraAuditoriaRecursoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Recurso Auditado.',$e);
    }
  }

  protected function reativarControlado($arrObjInfraRegraAuditoriaRecursoDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_recurso_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaRecursoBD = new InfraRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraRegraAuditoriaRecursoDTO);$i++){
        $objInfraRegraAuditoriaRecursoBD->reativar($arrObjInfraRegraAuditoriaRecursoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Recurso Auditado.',$e);
    }
  }

  protected function bloquearControlado(InfraRegraAuditoriaRecursoDTO $objInfraRegraAuditoriaRecursoDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_recurso_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaRecursoBD = new InfraRegraAuditoriaRecursoBD($this->getObjInfraIBanco());
      $ret = $objInfraRegraAuditoriaRecursoBD->bloquear($objInfraRegraAuditoriaRecursoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Recurso Auditado.',$e);
    }
  }

 */
}
?>