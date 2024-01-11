<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AssuntoProxyRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdAssunto(AssuntoProxyDTO $objAssuntoProxyDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssuntoProxyDTO->getNumIdAssunto())){
      $objInfraException->adicionarValidacao('Assunto não informado.');
    }
  }

  protected function cadastrarControlado(AssuntoProxyDTO $objAssuntoProxyDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_proxy_cadastrar',__METHOD__,$objAssuntoProxyDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdAssunto($objAssuntoProxyDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAssuntoProxyBD = new AssuntoProxyBD($this->getObjInfraIBanco());
      $ret = $objAssuntoProxyBD->cadastrar($objAssuntoProxyDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Assunto Utilizado.',$e);
    }
  }

  protected function alterarControlado(AssuntoProxyDTO $objAssuntoProxyDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('assunto_proxy_alterar',__METHOD__,$objAssuntoProxyDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAssuntoProxyDTO->isSetNumIdAssunto()){
        $this->validarNumIdAssunto($objAssuntoProxyDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAssuntoProxyBD = new AssuntoProxyBD($this->getObjInfraIBanco());
      $objAssuntoProxyBD->alterar($objAssuntoProxyDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Assunto Utilizado.',$e);
    }
  }

  protected function excluirControlado($arrObjAssuntoProxyDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_proxy_excluir',__METHOD__,$arrObjAssuntoProxyDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoProxyBD = new AssuntoProxyBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAssuntoProxyDTO);$i++){
        $objAssuntoProxyBD->excluir($arrObjAssuntoProxyDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Assunto Utilizado.',$e);
    }
  }

  protected function consultarConectado(AssuntoProxyDTO $objAssuntoProxyDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_proxy_consultar',__METHOD__,$objAssuntoProxyDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoProxyBD = new AssuntoProxyBD($this->getObjInfraIBanco());
      $ret = $objAssuntoProxyBD->consultar($objAssuntoProxyDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Assunto Utilizado.',$e);
    }
  }

  protected function listarConectado(AssuntoProxyDTO $objAssuntoProxyDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_proxy_listar',__METHOD__,$objAssuntoProxyDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoProxyBD = new AssuntoProxyBD($this->getObjInfraIBanco());
      $ret = $objAssuntoProxyBD->listar($objAssuntoProxyDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Assuntos Utilizados.',$e);
    }
  }

  protected function contarConectado(AssuntoProxyDTO $objAssuntoProxyDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_proxy_listar',__METHOD__,$objAssuntoProxyDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoProxyBD = new AssuntoProxyBD($this->getObjInfraIBanco());
      $ret = $objAssuntoProxyBD->contar($objAssuntoProxyDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Assuntos Utilizados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjAssuntoProxyDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_proxy_desativar',__METHOD__,$arrObjAssuntoProxyDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoProxyBD = new AssuntoProxyBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAssuntoProxyDTO);$i++){
        $objAssuntoProxyBD->desativar($arrObjAssuntoProxyDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Assunto Utilizado.',$e);
    }
  }

  protected function reativarControlado($arrObjAssuntoProxyDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_proxy_reativar',__METHOD__,$arrObjAssuntoProxyDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoProxyBD = new AssuntoProxyBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAssuntoProxyDTO);$i++){
        $objAssuntoProxyBD->reativar($arrObjAssuntoProxyDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Assunto Utilizado.',$e);
    }
  }

  protected function bloquearControlado(AssuntoProxyDTO $objAssuntoProxyDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_proxy_consultar',__METHOD__,$objAssuntoProxyDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoProxyBD = new AssuntoProxyBD($this->getObjInfraIBanco());
      $ret = $objAssuntoProxyBD->bloquear($objAssuntoProxyDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Assunto Utilizado.',$e);
    }
  }

 */
  protected function obterControlado(AssuntoDTO $objAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_proxy_consultar',__METHOD__,$objAssuntoDTO);

      $objAssuntoProxyDTO = new AssuntoProxyDTO();
      $objAssuntoProxyDTO->setNumMaxRegistrosRetorno(1);
      $objAssuntoProxyDTO->retNumIdAssuntoProxy();
      $objAssuntoProxyDTO->setNumIdAssunto($objAssuntoDTO->getNumIdAssunto());
      $objAssuntoProxyDTO->setOrdNumIdAssuntoProxy(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objAssuntoProxyDTO = $this->consultar($objAssuntoProxyDTO);

      if ($objAssuntoProxyDTO == null){

        $objAssuntoProxyDTO = new AssuntoProxyDTO();
        $objAssuntoProxyDTO->setNumIdAssuntoProxy(null);
        $objAssuntoProxyDTO->setNumIdAssunto($objAssuntoDTO->getNumIdAssunto());
        $objAssuntoProxyDTO = $this->cadastrar($objAssuntoProxyDTO);

      }

      return $objAssuntoProxyDTO;

    }catch(Exception $e){
      throw new InfraException('Erro obtendo Assunto Proxy.',$e);
    }
  }
}
?>