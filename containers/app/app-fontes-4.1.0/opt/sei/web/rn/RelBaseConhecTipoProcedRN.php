<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/10/2010 - criado por mga
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelBaseConhecTipoProcedRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdTipoProcedimento(RelBaseConhecTipoProcedDTO $objRelBaseConhecTipoProcedDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBaseConhecTipoProcedDTO->getNumIdTipoProcedimento())){
      $objInfraException->adicionarValidacao('Tipo de Processo não informado.');
    }
  }

  private function validarNumIdBaseConhecimento(RelBaseConhecTipoProcedDTO $objRelBaseConhecTipoProcedDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBaseConhecTipoProcedDTO->getNumIdBaseConhecimento())){
      $objInfraException->adicionarValidacao('Base de Conhecimento não informada.');
    }
  }

  protected function cadastrarControlado(RelBaseConhecTipoProcedDTO $objRelBaseConhecTipoProcedDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_base_conhec_tipo_proced_cadastrar',__METHOD__,$objRelBaseConhecTipoProcedDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdTipoProcedimento($objRelBaseConhecTipoProcedDTO, $objInfraException);
      $this->validarNumIdBaseConhecimento($objRelBaseConhecTipoProcedDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelBaseConhecTipoProcedBD = new RelBaseConhecTipoProcedBD($this->getObjInfraIBanco());
      $ret = $objRelBaseConhecTipoProcedBD->cadastrar($objRelBaseConhecTipoProcedDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tipo de Processo Associado.',$e);
    }
  }

  protected function alterarControlado(RelBaseConhecTipoProcedDTO $objRelBaseConhecTipoProcedDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_base_conhec_tipo_proced_alterar',__METHOD__,$objRelBaseConhecTipoProcedDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelBaseConhecTipoProcedDTO->isSetNumIdTipoProcedimento()){
        $this->validarNumIdTipoProcedimento($objRelBaseConhecTipoProcedDTO, $objInfraException);
      }
      if ($objRelBaseConhecTipoProcedDTO->isSetNumIdBaseConhecimento()){
        $this->validarNumIdBaseConhecimento($objRelBaseConhecTipoProcedDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelBaseConhecTipoProcedBD = new RelBaseConhecTipoProcedBD($this->getObjInfraIBanco());
      $objRelBaseConhecTipoProcedBD->alterar($objRelBaseConhecTipoProcedDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tipo de Processo Associado.',$e);
    }
  }

  protected function excluirControlado($arrObjRelBaseConhecTipoProcedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_base_conhec_tipo_proced_excluir',__METHOD__,$arrObjRelBaseConhecTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBaseConhecTipoProcedBD = new RelBaseConhecTipoProcedBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelBaseConhecTipoProcedDTO);$i++){
        $objRelBaseConhecTipoProcedBD->excluir($arrObjRelBaseConhecTipoProcedDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tipo de Processo Associado.',$e);
    }
  }

  protected function consultarConectado(RelBaseConhecTipoProcedDTO $objRelBaseConhecTipoProcedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_base_conhec_tipo_proced_consultar',__METHOD__,$objRelBaseConhecTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBaseConhecTipoProcedBD = new RelBaseConhecTipoProcedBD($this->getObjInfraIBanco());
      $ret = $objRelBaseConhecTipoProcedBD->consultar($objRelBaseConhecTipoProcedDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tipo de Processo Associado.',$e);
    }
  }

  protected function listarConectado(RelBaseConhecTipoProcedDTO $objRelBaseConhecTipoProcedDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_base_conhec_tipo_proced_listar',__METHOD__,$objRelBaseConhecTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBaseConhecTipoProcedBD = new RelBaseConhecTipoProcedBD($this->getObjInfraIBanco());
      $ret = $objRelBaseConhecTipoProcedBD->listar($objRelBaseConhecTipoProcedDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tipos de Processo Associados.',$e);
    }
  }

  protected function contarConectado(RelBaseConhecTipoProcedDTO $objRelBaseConhecTipoProcedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_base_conhec_tipo_proced_listar',__METHOD__,$objRelBaseConhecTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBaseConhecTipoProcedBD = new RelBaseConhecTipoProcedBD($this->getObjInfraIBanco());
      $ret = $objRelBaseConhecTipoProcedBD->contar($objRelBaseConhecTipoProcedDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tipos de Processo Associados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelBaseConhecTipoProcedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_base_conhec_tipo_proced_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBaseConhecTipoProcedBD = new RelBaseConhecTipoProcedBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelBaseConhecTipoProcedDTO);$i++){
        $objRelBaseConhecTipoProcedBD->desativar($arrObjRelBaseConhecTipoProcedDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Tipo de Processo Associado.',$e);
    }
  }

  protected function reativarControlado($arrObjRelBaseConhecTipoProcedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_base_conhec_tipo_proced_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBaseConhecTipoProcedBD = new RelBaseConhecTipoProcedBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelBaseConhecTipoProcedDTO);$i++){
        $objRelBaseConhecTipoProcedBD->reativar($arrObjRelBaseConhecTipoProcedDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Tipo de Processo Associado.',$e);
    }
  }

  protected function bloquearControlado(RelBaseConhecTipoProcedDTO $objRelBaseConhecTipoProcedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_base_conhec_tipo_proced_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBaseConhecTipoProcedBD = new RelBaseConhecTipoProcedBD($this->getObjInfraIBanco());
      $ret = $objRelBaseConhecTipoProcedBD->bloquear($objRelBaseConhecTipoProcedDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Tipo de Processo Associado.',$e);
    }
  }

 */
}
?>