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

class SerieEscolhaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdSerie(SerieEscolhaDTO $objSerieEscolhaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSerieEscolhaDTO->getNumIdSerie())){
      $objInfraException->adicionarValidacao('Tipo do documento não informado.');
    }
  }

  private function validarNumIdUnidade(SerieEscolhaDTO $objSerieEscolhaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSerieEscolhaDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  protected function cadastrarControlado(SerieEscolhaDTO $objSerieEscolhaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('serie_escolha_cadastrar',__METHOD__,$objSerieEscolhaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSerie($objSerieEscolhaDTO, $objInfraException);
      $this->validarNumIdUnidade($objSerieEscolhaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objSerieEscolhaBD = new SerieEscolhaBD($this->getObjInfraIBanco());
      $ret = $objSerieEscolhaBD->cadastrar($objSerieEscolhaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Escolha de Tipo de Documento.',$e);
    }
  }

  protected function alterarControlado(SerieEscolhaDTO $objSerieEscolhaDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('serie_escolha_alterar',__METHOD__,$objSerieEscolhaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objSerieEscolhaDTO->isSetNumIdSerie()){
        $this->validarNumIdSerie($objSerieEscolhaDTO, $objInfraException);
      }
      if ($objSerieEscolhaDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objSerieEscolhaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objSerieEscolhaBD = new SerieEscolhaBD($this->getObjInfraIBanco());
      $objSerieEscolhaBD->alterar($objSerieEscolhaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Escolha de Tipo de Documento.',$e);
    }
  }

  protected function excluirControlado($arrObjSerieEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('serie_escolha_excluir',__METHOD__,$arrObjSerieEscolhaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSerieEscolhaBD = new SerieEscolhaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSerieEscolhaDTO);$i++){
        $objSerieEscolhaBD->excluir($arrObjSerieEscolhaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Escolha de Tipo de Documento.',$e);
    }
  }

  protected function consultarConectado(SerieEscolhaDTO $objSerieEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('serie_escolha_consultar',__METHOD__,$objSerieEscolhaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSerieEscolhaBD = new SerieEscolhaBD($this->getObjInfraIBanco());
      $ret = $objSerieEscolhaBD->consultar($objSerieEscolhaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Escolha de Tipo de Documento.',$e);
    }
  }

  protected function listarConectado(SerieEscolhaDTO $objSerieEscolhaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('serie_escolha_listar',__METHOD__,$objSerieEscolhaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSerieEscolhaBD = new SerieEscolhaBD($this->getObjInfraIBanco());
      $ret = $objSerieEscolhaBD->listar($objSerieEscolhaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Escolha de Tipos de Documento.',$e);
    }
  }

  protected function contarConectado(SerieEscolhaDTO $objSerieEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('serie_escolha_listar',__METHOD__,$objSerieEscolhaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSerieEscolhaBD = new SerieEscolhaBD($this->getObjInfraIBanco());
      $ret = $objSerieEscolhaBD->contar($objSerieEscolhaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Escolha de Tipos de Documento.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjSerieEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('serie_escolha_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSerieEscolhaBD = new SerieEscolhaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSerieEscolhaDTO);$i++){
        $objSerieEscolhaBD->desativar($arrObjSerieEscolhaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Escolha de Tipo de Documento.',$e);
    }
  }

  protected function reativarControlado($arrObjSerieEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('serie_escolha_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSerieEscolhaBD = new SerieEscolhaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSerieEscolhaDTO);$i++){
        $objSerieEscolhaBD->reativar($arrObjSerieEscolhaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Escolha de Tipo de Documento.',$e);
    }
  }

  protected function bloquearControlado(SerieEscolhaDTO $objSerieEscolhaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('serie_escolha_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSerieEscolhaBD = new SerieEscolhaBD($this->getObjInfraIBanco());
      $ret = $objSerieEscolhaBD->bloquear($objSerieEscolhaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Escolha de Tipo de Documento.',$e);
    }
  }

 */
}
?>