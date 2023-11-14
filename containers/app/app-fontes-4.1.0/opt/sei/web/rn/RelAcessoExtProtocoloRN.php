<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/08/2016 - criado por mga
*
* Versão do Gerador de Código: 1.38.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelAcessoExtProtocoloRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdAcessoExterno(RelAcessoExtProtocoloDTO $objRelAcessoExtProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelAcessoExtProtocoloDTO->getNumIdAcessoExterno())){
      $objInfraException->adicionarValidacao('Acesso Externo não informado.');
    }
  }

  private function validarDblIdProtocolo(RelAcessoExtProtocoloDTO $objRelAcessoExtProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelAcessoExtProtocoloDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }
  }

  protected function cadastrarControlado(RelAcessoExtProtocoloDTO $objRelAcessoExtProtocoloDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_protocolo_cadastrar',__METHOD__,$objRelAcessoExtProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdAcessoExterno($objRelAcessoExtProtocoloDTO, $objInfraException);
      $this->validarDblIdProtocolo($objRelAcessoExtProtocoloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelAcessoExtProtocoloBD = new RelAcessoExtProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelAcessoExtProtocoloBD->cadastrar($objRelAcessoExtProtocoloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Protocolo Liberado no Acesso Externo.',$e);
    }
  }

  protected function alterarControlado(RelAcessoExtProtocoloDTO $objRelAcessoExtProtocoloDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_protocolo_alterar',__METHOD__,$objRelAcessoExtProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelAcessoExtProtocoloDTO->isSetNumIdAcessoExterno()){
        $this->validarNumIdAcessoExterno($objRelAcessoExtProtocoloDTO, $objInfraException);
      }
      if ($objRelAcessoExtProtocoloDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocolo($objRelAcessoExtProtocoloDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelAcessoExtProtocoloBD = new RelAcessoExtProtocoloBD($this->getObjInfraIBanco());
      $objRelAcessoExtProtocoloBD->alterar($objRelAcessoExtProtocoloDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Protocolo Liberado no Acesso Externo.',$e);
    }
  }

  protected function excluirControlado($arrObjRelAcessoExtProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_protocolo_excluir',__METHOD__,$arrObjRelAcessoExtProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtProtocoloBD = new RelAcessoExtProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAcessoExtProtocoloDTO);$i++){
        $objRelAcessoExtProtocoloBD->excluir($arrObjRelAcessoExtProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Protocolo Liberado no Acesso Externo.',$e);
    }
  }

  protected function consultarConectado(RelAcessoExtProtocoloDTO $objRelAcessoExtProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_protocolo_consultar',__METHOD__,$objRelAcessoExtProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtProtocoloBD = new RelAcessoExtProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelAcessoExtProtocoloBD->consultar($objRelAcessoExtProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Protocolo Liberado no Acesso Externo.',$e);
    }
  }

  protected function listarConectado(RelAcessoExtProtocoloDTO $objRelAcessoExtProtocoloDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_protocolo_listar',__METHOD__,$objRelAcessoExtProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtProtocoloBD = new RelAcessoExtProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelAcessoExtProtocoloBD->listar($objRelAcessoExtProtocoloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Protocolos Liberados no Acesso Externo.',$e);
    }
  }

  protected function contarConectado(RelAcessoExtProtocoloDTO $objRelAcessoExtProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_protocolo_listar',__METHOD__,$objRelAcessoExtProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtProtocoloBD = new RelAcessoExtProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelAcessoExtProtocoloBD->contar($objRelAcessoExtProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Protocolos Liberados no Acesso Externo.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelAcessoExtProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_protocolo_desativar',__METHOD__,$arrObjRelAcessoExtProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtProtocoloBD = new RelAcessoExtProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAcessoExtProtocoloDTO);$i++){
        $objRelAcessoExtProtocoloBD->desativar($arrObjRelAcessoExtProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Protocolo Liberado no Acesso Externo.',$e);
    }
  }

  protected function reativarControlado($arrObjRelAcessoExtProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_protocolo_reativar',__METHOD__,$arrObjRelAcessoExtProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtProtocoloBD = new RelAcessoExtProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAcessoExtProtocoloDTO);$i++){
        $objRelAcessoExtProtocoloBD->reativar($arrObjRelAcessoExtProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Protocolo Liberado no Acesso Externo.',$e);
    }
  }

  protected function bloquearControlado(RelAcessoExtProtocoloDTO $objRelAcessoExtProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_protocolo_consultar',__METHOD__,$objRelAcessoExtProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtProtocoloBD = new RelAcessoExtProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelAcessoExtProtocoloBD->bloquear($objRelAcessoExtProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Protocolo Liberado no Acesso Externo.',$e);
    }
  }

 */
}
?>