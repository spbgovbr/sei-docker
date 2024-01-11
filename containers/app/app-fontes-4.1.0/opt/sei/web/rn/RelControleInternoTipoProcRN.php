<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/06/2012 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelControleInternoTipoProcRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdTipoProcedimento(RelControleInternoTipoProcDTO $objRelControleInternoTipoProcDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelControleInternoTipoProcDTO->getNumIdTipoProcedimento())){
      $objInfraException->adicionarValidacao('Tipo do Processo não informado.');
    }
  }

  private function validarNumIdControleInterno(RelControleInternoTipoProcDTO $objRelControleInternoTipoProcDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelControleInternoTipoProcDTO->getNumIdControleInterno())){
      $objInfraException->adicionarValidacao('Controle Interno não informado.');
    }
  }

  protected function cadastrarControlado(RelControleInternoTipoProcDTO $objRelControleInternoTipoProcDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_tipo_proc_cadastrar',__METHOD__,$objRelControleInternoTipoProcDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdTipoProcedimento($objRelControleInternoTipoProcDTO, $objInfraException);
      $this->validarNumIdControleInterno($objRelControleInternoTipoProcDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelControleInternoTipoProcBD = new RelControleInternoTipoProcBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoTipoProcBD->cadastrar($objRelControleInternoTipoProcDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Controle Interno.',$e);
    }
  }

  protected function alterarControlado(RelControleInternoTipoProcDTO $objRelControleInternoTipoProcDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_tipo_proc_alterar',__METHOD__,$objRelControleInternoTipoProcDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelControleInternoTipoProcDTO->isSetNumIdTipoProcedimento()){
        $this->validarNumIdTipoProcedimento($objRelControleInternoTipoProcDTO, $objInfraException);
      }
      if ($objRelControleInternoTipoProcDTO->isSetNumIdControleInterno()){
        $this->validarNumIdControleInterno($objRelControleInternoTipoProcDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelControleInternoTipoProcBD = new RelControleInternoTipoProcBD($this->getObjInfraIBanco());
      $objRelControleInternoTipoProcBD->alterar($objRelControleInternoTipoProcDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Controle Interno.',$e);
    }
  }

  protected function excluirControlado($arrObjRelControleInternoTipoProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_tipo_proc_excluir',__METHOD__,$arrObjRelControleInternoTipoProcDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoTipoProcBD = new RelControleInternoTipoProcBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelControleInternoTipoProcDTO);$i++){
        $objRelControleInternoTipoProcBD->excluir($arrObjRelControleInternoTipoProcDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Controle Interno.',$e);
    }
  }

  protected function consultarConectado(RelControleInternoTipoProcDTO $objRelControleInternoTipoProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_tipo_proc_consultar',__METHOD__,$objRelControleInternoTipoProcDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoTipoProcBD = new RelControleInternoTipoProcBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoTipoProcBD->consultar($objRelControleInternoTipoProcDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Controle Interno.',$e);
    }
  }

  protected function listarConectado(RelControleInternoTipoProcDTO $objRelControleInternoTipoProcDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_tipo_proc_listar',__METHOD__,$objRelControleInternoTipoProcDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoTipoProcBD = new RelControleInternoTipoProcBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoTipoProcBD->listar($objRelControleInternoTipoProcDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Controles Internos.',$e);
    }
  }

  protected function contarConectado(RelControleInternoTipoProcDTO $objRelControleInternoTipoProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_tipo_proc_listar',__METHOD__,$objRelControleInternoTipoProcDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoTipoProcBD = new RelControleInternoTipoProcBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoTipoProcBD->contar($objRelControleInternoTipoProcDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Controles Internos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelControleInternoTipoProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_tipo_proc_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoTipoProcBD = new RelControleInternoTipoProcBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelControleInternoTipoProcDTO);$i++){
        $objRelControleInternoTipoProcBD->desativar($arrObjRelControleInternoTipoProcDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Controle Interno.',$e);
    }
  }

  protected function reativarControlado($arrObjRelControleInternoTipoProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_tipo_proc_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoTipoProcBD = new RelControleInternoTipoProcBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelControleInternoTipoProcDTO);$i++){
        $objRelControleInternoTipoProcBD->reativar($arrObjRelControleInternoTipoProcDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Controle Interno.',$e);
    }
  }

  protected function bloquearControlado(RelControleInternoTipoProcDTO $objRelControleInternoTipoProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_tipo_proc_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoTipoProcBD = new RelControleInternoTipoProcBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoTipoProcBD->bloquear($objRelControleInternoTipoProcDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Controle Interno.',$e);
    }
  }

 */
}
?>