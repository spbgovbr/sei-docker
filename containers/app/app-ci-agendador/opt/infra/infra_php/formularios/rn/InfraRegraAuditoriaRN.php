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

class InfraRegraAuditoriaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoInfra::getInstance();
  }

  private function validarStrDescricao(InfraRegraAuditoriaDTO $objInfraRegraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraRegraAuditoriaDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objInfraRegraAuditoriaDTO->setStrDescricao(trim($objInfraRegraAuditoriaDTO->getStrDescricao()));

      if (strlen($objInfraRegraAuditoriaDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(InfraRegraAuditoriaDTO $objInfraRegraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraRegraAuditoriaDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objInfraRegraAuditoriaDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(InfraRegraAuditoriaDTO $objInfraRegraAuditoriaDTO) {
    try{

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrDescricao($objInfraRegraAuditoriaDTO, $objInfraException);
      $this->validarStrSinAtivo($objInfraRegraAuditoriaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaBD = new InfraRegraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objInfraRegraAuditoriaBD->cadastrar($objInfraRegraAuditoriaDTO);

      if ($objInfraRegraAuditoriaDTO->isSetArrObjInfraRegraAuditoriaRecursoDTO()){
        
        $objInfraRegraAuditoriaRecursoRN = new InfraRegraAuditoriaRecursoRN();
        
        $arrObjInfraRegraAuditoriaRecursoDTO = $objInfraRegraAuditoriaDTO->getArrObjInfraRegraAuditoriaRecursoDTO();
        foreach($arrObjInfraRegraAuditoriaRecursoDTO as $objInfraRegraAuditoriaRecursoDTO){
          $objInfraRegraAuditoriaRecursoDTO->setNumIdInfraRegraAuditoria($ret->getNumIdInfraRegraAuditoria());
          $objInfraRegraAuditoriaRecursoRN->cadastrar($objInfraRegraAuditoriaRecursoDTO);
        }
      }
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Regra de Auditoria.',$e);
    }
  }

  protected function alterarControlado(InfraRegraAuditoriaDTO $objInfraRegraAuditoriaDTO){
    try {

      //Valida Permissao
  	   SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objInfraRegraAuditoriaDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objInfraRegraAuditoriaDTO, $objInfraException);
      }
      if ($objInfraRegraAuditoriaDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objInfraRegraAuditoriaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      if ($objInfraRegraAuditoriaDTO->isSetArrObjInfraRegraAuditoriaRecursoDTO()){
        
        $objInfraRegraAuditoriaRecursoDTO = new InfraRegraAuditoriaRecursoDTO();
        $objInfraRegraAuditoriaRecursoDTO->retNumIdInfraRegraAuditoria();
        $objInfraRegraAuditoriaRecursoDTO->retStrRecurso();
        $objInfraRegraAuditoriaRecursoDTO->setNumIdInfraRegraAuditoria($objInfraRegraAuditoriaDTO->getNumIdInfraRegraAuditoria());
        
        $objInfraRegraAuditoriaRecursoRN = new InfraRegraAuditoriaRecursoRN();
        $objInfraRegraAuditoriaRecursoRN->excluir($objInfraRegraAuditoriaRecursoRN->listar($objInfraRegraAuditoriaRecursoDTO));
        
        $arrObjInfraRegraAuditoriaRecursoDTO = $objInfraRegraAuditoriaDTO->getArrObjInfraRegraAuditoriaRecursoDTO();
        foreach($arrObjInfraRegraAuditoriaRecursoDTO as $objInfraRegraAuditoriaRecursoDTO){
          $objInfraRegraAuditoriaRecursoDTO->setNumIdInfraRegraAuditoria($objInfraRegraAuditoriaDTO->getNumIdInfraRegraAuditoria());
          $objInfraRegraAuditoriaRecursoRN->cadastrar($objInfraRegraAuditoriaRecursoDTO);
        }
      }
      
      
      $objInfraRegraAuditoriaBD = new InfraRegraAuditoriaBD($this->getObjInfraIBanco());
      $objInfraRegraAuditoriaBD->alterar($objInfraRegraAuditoriaDTO);

      
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Regra de Auditoria.',$e);
    }
  }

  protected function excluirControlado($arrObjInfraRegraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
      for($i=0;$i<count($arrObjInfraRegraAuditoriaDTO);$i++){
        $objInfraRegraAuditoriaRecursoDTO = new InfraRegraAuditoriaRecursoDTO();
        $objInfraRegraAuditoriaRecursoDTO->retNumIdInfraRegraAuditoria();
        $objInfraRegraAuditoriaRecursoDTO->retStrRecurso();
        $objInfraRegraAuditoriaRecursoDTO->setNumIdInfraRegraAuditoria($arrObjInfraRegraAuditoriaDTO[$i]->getNumIdInfraRegraAuditoria());
        
        $objInfraRegraAuditoriaRecursoRN = new InfraRegraAuditoriaRecursoRN();
        $objInfraRegraAuditoriaRecursoRN->excluir($objInfraRegraAuditoriaRecursoRN->listar($objInfraRegraAuditoriaRecursoDTO));
      } 
      
      $objInfraRegraAuditoriaBD = new InfraRegraAuditoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraRegraAuditoriaDTO);$i++){
        $objInfraRegraAuditoriaBD->excluir($arrObjInfraRegraAuditoriaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Regra de Auditoria.',$e);
    }
  }

  protected function consultarConectado(InfraRegraAuditoriaDTO $objInfraRegraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaBD = new InfraRegraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objInfraRegraAuditoriaBD->consultar($objInfraRegraAuditoriaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Regra de Auditoria.',$e);
    }
  }

  protected function listarConectado(InfraRegraAuditoriaDTO $objInfraRegraAuditoriaDTO) {
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaBD = new InfraRegraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objInfraRegraAuditoriaBD->listar($objInfraRegraAuditoriaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Regras de Auditoria.',$e);
    }
  }

  protected function contarConectado(InfraRegraAuditoriaDTO $objInfraRegraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaBD = new InfraRegraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objInfraRegraAuditoriaBD->contar($objInfraRegraAuditoriaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Regras de Auditoria.',$e);
    }
  }

  protected function desativarControlado($arrObjInfraRegraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaBD = new InfraRegraAuditoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraRegraAuditoriaDTO);$i++){
        $objInfraRegraAuditoriaBD->desativar($arrObjInfraRegraAuditoriaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Regra de Auditoria.',$e);
    }
  }

  protected function reativarControlado($arrObjInfraRegraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaBD = new InfraRegraAuditoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraRegraAuditoriaDTO);$i++){
        $objInfraRegraAuditoriaBD->reativar($arrObjInfraRegraAuditoriaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Regra de Auditoria.',$e);
    }
  }

  protected function bloquearControlado(InfraRegraAuditoriaDTO $objInfraRegraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_regra_auditoria_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraRegraAuditoriaBD = new InfraRegraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objInfraRegraAuditoriaBD->bloquear($objInfraRegraAuditoriaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Regra de Auditoria.',$e);
    }
  }


}
?>