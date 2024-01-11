<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/01/2011 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelControleInternoUnidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdControleInterno(RelControleInternoUnidadeDTO $objRelControleInternoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelControleInternoUnidadeDTO->getNumIdControleInterno())){
      $objInfraException->adicionarValidacao('Controle Interno não informado.');
    }
  }

  private function validarNumIdUnidade(RelControleInternoUnidadeDTO $objRelControleInternoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelControleInternoUnidadeDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  protected function cadastrarControlado(RelControleInternoUnidadeDTO $objRelControleInternoUnidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_unidade_cadastrar',__METHOD__,$objRelControleInternoUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdControleInterno($objRelControleInternoUnidadeDTO, $objInfraException);
      $this->validarNumIdUnidade($objRelControleInternoUnidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelControleInternoUnidadeBD = new RelControleInternoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoUnidadeBD->cadastrar($objRelControleInternoUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Controle Interno da Unidade.',$e);
    }
  }

  protected function alterarControlado(RelControleInternoUnidadeDTO $objRelControleInternoUnidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_unidade_alterar',__METHOD__,$objRelControleInternoUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelControleInternoUnidadeDTO->isSetNumIdControleInterno()){
        $this->validarNumIdControleInterno($objRelControleInternoUnidadeDTO, $objInfraException);
      }
      if ($objRelControleInternoUnidadeDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objRelControleInternoUnidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelControleInternoUnidadeBD = new RelControleInternoUnidadeBD($this->getObjInfraIBanco());
      $objRelControleInternoUnidadeBD->alterar($objRelControleInternoUnidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Controle Interno da Unidade.',$e);
    }
  }

  protected function excluirControlado($arrObjRelControleInternoUnidadeDTO){
    try {
    	
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_unidade_excluir',__METHOD__,$arrObjRelControleInternoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoUnidadeBD = new RelControleInternoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelControleInternoUnidadeDTO);$i++){
        $objRelControleInternoUnidadeBD->excluir($arrObjRelControleInternoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Controle Interno da Unidade.',$e);
    }
  }

  protected function consultarConectado(RelControleInternoUnidadeDTO $objRelControleInternoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_unidade_consultar',__METHOD__,$objRelControleInternoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoUnidadeBD = new RelControleInternoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoUnidadeBD->consultar($objRelControleInternoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Controle Interno da Unidade.',$e);
    }
  }

  protected function listarConectado(RelControleInternoUnidadeDTO $objRelControleInternoUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_unidade_listar',__METHOD__,$objRelControleInternoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoUnidadeBD = new RelControleInternoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoUnidadeBD->listar($objRelControleInternoUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Controles Internos das Unidades.',$e);
    }
  }

  protected function contarConectado(RelControleInternoUnidadeDTO $objRelControleInternoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_unidade_listar',__METHOD__,$objRelControleInternoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoUnidadeBD = new RelControleInternoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoUnidadeBD->contar($objRelControleInternoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Controles Internos das Unidades.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelControleInternoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_unidade_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoUnidadeBD = new RelControleInternoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelControleInternoUnidadeDTO);$i++){
        $objRelControleInternoUnidadeBD->desativar($arrObjRelControleInternoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Controle Interno da Unidade.',$e);
    }
  }

  protected function reativarControlado($arrObjRelControleInternoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_unidade_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoUnidadeBD = new RelControleInternoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelControleInternoUnidadeDTO);$i++){
        $objRelControleInternoUnidadeBD->reativar($arrObjRelControleInternoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Controle Interno da Unidade.',$e);
    }
  }

  protected function bloquearControlado(RelControleInternoUnidadeDTO $objRelControleInternoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_unidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoUnidadeBD = new RelControleInternoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoUnidadeBD->bloquear($objRelControleInternoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Controle Interno da Unidade.',$e);
    }
  }

 */
}
?>