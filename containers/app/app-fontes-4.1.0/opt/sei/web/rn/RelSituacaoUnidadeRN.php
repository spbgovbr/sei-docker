<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/03/2015 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelSituacaoUnidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUnidade(RelSituacaoUnidadeDTO $objRelSituacaoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelSituacaoUnidadeDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdSituacao(RelSituacaoUnidadeDTO $objRelSituacaoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelSituacaoUnidadeDTO->getNumIdSituacao())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  protected function cadastrarControlado(RelSituacaoUnidadeDTO $objRelSituacaoUnidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_situacao_unidade_cadastrar',__METHOD__,$objRelSituacaoUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidade($objRelSituacaoUnidadeDTO, $objInfraException);
      $this->validarNumIdSituacao($objRelSituacaoUnidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelSituacaoUnidadeBD = new RelSituacaoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelSituacaoUnidadeBD->cadastrar($objRelSituacaoUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Ponto de Controle na Unidade.',$e);
    }
  }

  protected function alterarControlado(RelSituacaoUnidadeDTO $objRelSituacaoUnidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_situacao_unidade_alterar',__METHOD__,$objRelSituacaoUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelSituacaoUnidadeDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objRelSituacaoUnidadeDTO, $objInfraException);
      }
      if ($objRelSituacaoUnidadeDTO->isSetNumIdSituacao()){
        $this->validarNumIdSituacao($objRelSituacaoUnidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelSituacaoUnidadeBD = new RelSituacaoUnidadeBD($this->getObjInfraIBanco());
      $objRelSituacaoUnidadeBD->alterar($objRelSituacaoUnidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Ponto de Controle na Unidade.',$e);
    }
  }

  protected function excluirControlado($arrObjRelSituacaoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_situacao_unidade_excluir',__METHOD__,$arrObjRelSituacaoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSituacaoUnidadeBD = new RelSituacaoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSituacaoUnidadeDTO);$i++){
        $objRelSituacaoUnidadeBD->excluir($arrObjRelSituacaoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Ponto de Controle na Unidade.',$e);
    }
  }

  protected function consultarConectado(RelSituacaoUnidadeDTO $objRelSituacaoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_situacao_unidade_consultar',__METHOD__,$objRelSituacaoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSituacaoUnidadeBD = new RelSituacaoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelSituacaoUnidadeBD->consultar($objRelSituacaoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Ponto de Controle na Unidade.',$e);
    }
  }

  protected function listarConectado(RelSituacaoUnidadeDTO $objRelSituacaoUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_situacao_unidade_listar',__METHOD__,$objRelSituacaoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSituacaoUnidadeBD = new RelSituacaoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelSituacaoUnidadeBD->listar($objRelSituacaoUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Pontos de Controle na Unidade.',$e);
    }
  }

  protected function contarConectado(RelSituacaoUnidadeDTO $objRelSituacaoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_situacao_unidade_listar',__METHOD__,$objRelSituacaoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSituacaoUnidadeBD = new RelSituacaoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelSituacaoUnidadeBD->contar($objRelSituacaoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Pontos de Controle na Unidade.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelSituacaoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_situacao_unidade_desativar',__METHOD__,$arrObjRelSituacaoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSituacaoUnidadeBD = new RelSituacaoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSituacaoUnidadeDTO);$i++){
        $objRelSituacaoUnidadeBD->desativar($arrObjRelSituacaoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Ponto de Controle na Unidade.',$e);
    }
  }

  protected function reativarControlado($arrObjRelSituacaoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_situacao_unidade_reativar',__METHOD__,$arrObjRelSituacaoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSituacaoUnidadeBD = new RelSituacaoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSituacaoUnidadeDTO);$i++){
        $objRelSituacaoUnidadeBD->reativar($arrObjRelSituacaoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Ponto de Controle na Unidade.',$e);
    }
  }

  protected function bloquearControlado(RelSituacaoUnidadeDTO $objRelSituacaoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_situacao_unidade_consultar',__METHOD__,$objRelSituacaoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSituacaoUnidadeBD = new RelSituacaoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelSituacaoUnidadeBD->bloquear($objRelSituacaoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Ponto de Controle na Unidade.',$e);
    }
  }

 */
}
?>