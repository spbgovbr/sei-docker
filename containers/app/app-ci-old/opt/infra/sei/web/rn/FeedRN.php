<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/08/2010 - criado por mga
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class FeedRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrConteudo(FeedDTO $objFeedDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objFeedDTO->getStrConteudo())){
      $objFeedDTO->setStrConteudo(null);
    }else{
    	/*
      $strFeed = $objFeedDTO->getStrConteudo();
	    while(($numPosContentIni = strpos($strFeed,'<content encoding="base64binary">'))!==false && ($numPosContentFim = strpos($strFeed,'</content>'))!==false){
	      $strFeed = substr($strFeed,0,$numPosContentIni+strlen('<content encoding="base64binary">')).'['.strlen($strFeed).' bytes]'.substr($strFeed,$numPosContentFim+strlen('</content>'));
	    }      
	    
	    $objFeedDTO->setStrConteudo($strFeed);
	    */
    }
  }

  protected function cadastrarControlado(FeedDTO $objFeedDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feed_cadastrar',__METHOD__,$objFeedDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrConteudo($objFeedDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objFeedBD = new FeedBD($this->getObjInfraIBanco());
      $ret = $objFeedBD->cadastrar($objFeedDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Feed.',$e);
    }
  }

  protected function alterarControlado(FeedDTO $objFeedDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('feed_alterar',__METHOD__,$objFeedDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objFeedDTO->isSetStrConteudo()){
        $this->validarStrConteudo($objFeedDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objFeedBD = new FeedBD($this->getObjInfraIBanco());
      $objFeedBD->alterar($objFeedDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Feed.',$e);
    }
  }

  protected function excluirControlado($arrObjFeedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feed_excluir',__METHOD__,$arrObjFeedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeedBD = new FeedBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjFeedDTO);$i++){
        $objFeedBD->excluir($arrObjFeedDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Feed.',$e);
    }
  }

  protected function consultarConectado(FeedDTO $objFeedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feed_consultar',__METHOD__,$objFeedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeedBD = new FeedBD($this->getObjInfraIBanco());
      $ret = $objFeedBD->consultar($objFeedDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Feed.',$e);
    }
  }

  protected function listarConectado(FeedDTO $objFeedDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feed_listar',__METHOD__,$objFeedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeedBD = new FeedBD($this->getObjInfraIBanco());
      $ret = $objFeedBD->listar($objFeedDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Feeds.',$e);
    }
  }

  protected function contarConectado(FeedDTO $objFeedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feed_listar',__METHOD__,$objFeedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeedBD = new FeedBD($this->getObjInfraIBanco());
      $ret = $objFeedBD->contar($objFeedDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Feeds.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjFeedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feed_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeedBD = new FeedBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjFeedDTO);$i++){
        $objFeedBD->desativar($arrObjFeedDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Feed.',$e);
    }
  }

  protected function reativarControlado($arrObjFeedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feed_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeedBD = new FeedBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjFeedDTO);$i++){
        $objFeedBD->reativar($arrObjFeedDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Feed.',$e);
    }
  }

  protected function bloquearControlado(FeedDTO $objFeedDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feed_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeedBD = new FeedBD($this->getObjInfraIBanco());
      $ret = $objFeedBD->bloquear($objFeedDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Feed.',$e);
    }
  }

 */
}
?>