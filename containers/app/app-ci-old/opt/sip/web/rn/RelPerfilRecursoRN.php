<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class RelPerfilRecursoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  protected function cadastrarControlado(RelPerfilRecursoDTO $objRelPerfilRecursoDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_recurso_cadastrar',__METHOD__,$objRelPerfilRecursoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      //$this->validarCadastro($objRelPerfilRecursoDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelPerfilRecursoBD = new RelPerfilRecursoBD($this->getObjInfraIBanco());
      $ret = $objRelPerfilRecursoBD->cadastrar($objRelPerfilRecursoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando recurso do perfil.',$e);
    }
  }

  protected function alterarControlado(RelPerfilRecursoDTO $objRelPerfilRecursoDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_recurso_alterar',__METHOD__,$objRelPerfilRecursoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      //$this->validarCadastro($objRelPerfilRecursoDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelPerfilRecursoBD = new RelPerfilRecursoBD($this->getObjInfraIBanco());
      $objRelPerfilRecursoBD->alterar($objRelPerfilRecursoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando recurso do perfil.',$e);
    }
  }

  protected function excluirControlado($arrObjRelPerfilRecursoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_recurso_excluir',__METHOD__,$arrObjRelPerfilRecursoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
      
      $objRelPerfilRecursoBD = new RelPerfilRecursoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelPerfilRecursoDTO);$i++){
        
        $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
        
        $objRelPerfilItemMenuDTO->retTodos();
        $objRelPerfilItemMenuDTO->setNumIdPerfil($arrObjRelPerfilRecursoDTO[$i]->getNumIdPerfil());
        $objRelPerfilItemMenuDTO->setNumIdSistema($arrObjRelPerfilRecursoDTO[$i]->getNumIdSistema());
        $objRelPerfilItemMenuDTO->setNumIdRecurso($arrObjRelPerfilRecursoDTO[$i]->getNumIdRecurso());
        $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
        
        $objRelPerfilRecursoBD->excluir($arrObjRelPerfilRecursoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo recursos do perfil.',$e);
    }
  }

  protected function consultarConectado(RelPerfilRecursoDTO $objRelPerfilRecursoDTO){
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_recurso_consultar',__METHOD__,$objRelPerfilRecursoDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelPerfilRecursoBD = new RelPerfilRecursoBD($this->getObjInfraIBanco());
      $ret = $objRelPerfilRecursoBD->consultar($objRelPerfilRecursoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando recurso do perfil.',$e);
    }
  }

  protected function listarConectado(RelPerfilRecursoDTO $objRelPerfilRecursoDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_recurso_listar',__METHOD__,$objRelPerfilRecursoDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelPerfilRecursoBD = new RelPerfilRecursoBD($this->getObjInfraIBanco());
      $ret = $objRelPerfilRecursoBD->listar($objRelPerfilRecursoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando recursos do perfil.',$e);
    }
  }

  protected function contarConectado(RelPerfilRecursoDTO $objRelPerfilRecursoDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_recurso_listar',__METHOD__,$objRelPerfilRecursoDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelPerfilRecursoBD = new RelPerfilRecursoBD($this->getObjInfraIBanco());
      $ret = $objRelPerfilRecursoBD->contar($objRelPerfilRecursoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro contando recursos do perfil.',$e);
    }
  }
}
?>