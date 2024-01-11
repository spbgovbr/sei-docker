<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/08/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TipoProcedimentoEscolhaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdTipoProcedimento(TipoProcedimentoEscolhaDTO $objTipoProcedimentoEscolhaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoProcedimentoEscolhaDTO->getNumIdTipoProcedimento())){
      $objInfraException->adicionarValidacao('Tipo de Processo não informado.');
    }
  }

  private function validarNumIdUnidade(TipoProcedimentoEscolhaDTO $objTipoProcedimentoEscolhaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoProcedimentoEscolhaDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Tipo de Unidade não informado.');
    }
  }

  protected function cadastrarControlado(TipoProcedimentoEscolhaDTO $objTipoProcedimentoEscolhaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_procedimento_escolha_cadastrar',__METHOD__,$objTipoProcedimentoEscolhaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdTipoProcedimento($objTipoProcedimentoEscolhaDTO, $objInfraException);
      $this->validarNumIdUnidade($objTipoProcedimentoEscolhaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objTipoProcedimentoEscolhaBD = new TipoProcedimentoEscolhaBD($this->getObjInfraIBanco());
      $ret = $objTipoProcedimentoEscolhaBD->cadastrar($objTipoProcedimentoEscolhaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Escolha de Tipo de Processo.',$e);
    }
  }

  protected function alterarControlado(TipoProcedimentoEscolhaDTO $objTipoProcedimentoEscolhaDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('tipo_procedimento_escolha_alterar',__METHOD__,$objTipoProcedimentoEscolhaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objTipoProcedimentoEscolhaDTO->isSetNumIdTipoProcedimento()){
        $this->validarNumIdTipoProcedimento($objTipoProcedimentoEscolhaDTO, $objInfraException);
      }
      if ($objTipoProcedimentoEscolhaDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objTipoProcedimentoEscolhaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objTipoProcedimentoEscolhaBD = new TipoProcedimentoEscolhaBD($this->getObjInfraIBanco());
      $objTipoProcedimentoEscolhaBD->alterar($objTipoProcedimentoEscolhaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Escolha de Tipo de Processo.',$e);
    }
  }

  protected function excluirControlado($arrObjTipoProcedimentoEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_procedimento_escolha_excluir',__METHOD__,$arrObjTipoProcedimentoEscolhaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoProcedimentoEscolhaBD = new TipoProcedimentoEscolhaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoProcedimentoEscolhaDTO);$i++){
        $objTipoProcedimentoEscolhaBD->excluir($arrObjTipoProcedimentoEscolhaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Escolha de Tipo de Processo.',$e);
    }
  }

  protected function consultarConectado(TipoProcedimentoEscolhaDTO $objTipoProcedimentoEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_procedimento_escolha_consultar',__METHOD__,$objTipoProcedimentoEscolhaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoProcedimentoEscolhaBD = new TipoProcedimentoEscolhaBD($this->getObjInfraIBanco());
      $ret = $objTipoProcedimentoEscolhaBD->consultar($objTipoProcedimentoEscolhaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Escolha de Tipo de Processo.',$e);
    }
  }

  protected function listarConectado(TipoProcedimentoEscolhaDTO $objTipoProcedimentoEscolhaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_procedimento_escolha_listar',__METHOD__,$objTipoProcedimentoEscolhaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoProcedimentoEscolhaBD = new TipoProcedimentoEscolhaBD($this->getObjInfraIBanco());
      $ret = $objTipoProcedimentoEscolhaBD->listar($objTipoProcedimentoEscolhaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Escolha de Tipos de Processo.',$e);
    }
  }

  protected function contarConectado(TipoProcedimentoEscolhaDTO $objTipoProcedimentoEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_procedimento_escolha_listar',__METHOD__,$objTipoProcedimentoEscolhaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoProcedimentoEscolhaBD = new TipoProcedimentoEscolhaBD($this->getObjInfraIBanco());
      $ret = $objTipoProcedimentoEscolhaBD->contar($objTipoProcedimentoEscolhaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Escolha de Tipos de Processo.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjTipoProcedimentoEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_procedimento_escolha_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoProcedimentoEscolhaBD = new TipoProcedimentoEscolhaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoProcedimentoEscolhaDTO);$i++){
        $objTipoProcedimentoEscolhaBD->desativar($arrObjTipoProcedimentoEscolhaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Escolha de Tipo de Processo.',$e);
    }
  }

  protected function reativarControlado($arrObjTipoProcedimentoEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_procedimento_escolha_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoProcedimentoEscolhaBD = new TipoProcedimentoEscolhaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoProcedimentoEscolhaDTO);$i++){
        $objTipoProcedimentoEscolhaBD->reativar($arrObjTipoProcedimentoEscolhaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Escolha de Tipo de Processo.',$e);
    }
  }

  protected function bloquearControlado(TipoProcedimentoEscolhaDTO $objTipoProcedimentoEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_procedimento_escolha_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoProcedimentoEscolhaBD = new TipoProcedimentoEscolhaBD($this->getObjInfraIBanco());
      $ret = $objTipoProcedimentoEscolhaBD->bloquear($objTipoProcedimentoEscolhaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Escolha de Tipo de Processo.',$e);
    }
  }

 */
}
?>