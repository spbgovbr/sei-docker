<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/08/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EmailUtilizadoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUnidade(EmailUtilizadoDTO $objEmailUtilizadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEmailUtilizadoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('unidade não informada.');
    }
  }

  private function validarStrEmail(EmailUtilizadoDTO $objEmailUtilizadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEmailUtilizadoDTO->getStrEmail())){
      $objInfraException->adicionarValidacao('email não informado.');
    }else{
      $objEmailUtilizadoDTO->setStrEmail(trim($objEmailUtilizadoDTO->getStrEmail()));

      if (strlen($objEmailUtilizadoDTO->getStrEmail())>300){
        $objInfraException->adicionarValidacao('email possui tamanho superior a 300 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(EmailUtilizadoDTO $objEmailUtilizadoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_utilizado_cadastrar',__METHOD__,$objEmailUtilizadoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidade($objEmailUtilizadoDTO, $objInfraException);
      $this->validarStrEmail($objEmailUtilizadoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objEmailUtilizadoBD = new EmailUtilizadoBD($this->getObjInfraIBanco());
      $ret = $objEmailUtilizadoBD->cadastrar($objEmailUtilizadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Email Utilizado.',$e);
    }
  }

  protected function alterarControlado(EmailUtilizadoDTO $objEmailUtilizadoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('email_utilizado_alterar',__METHOD__,$objEmailUtilizadoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objEmailUtilizadoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objEmailUtilizadoDTO, $objInfraException);
      }
      if ($objEmailUtilizadoDTO->isSetStrEmail()){
        $this->validarStrEmail($objEmailUtilizadoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objEmailUtilizadoBD = new EmailUtilizadoBD($this->getObjInfraIBanco());
      $objEmailUtilizadoBD->alterar($objEmailUtilizadoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Email Utilizado.',$e);
    }
  }

  protected function excluirControlado($arrObjEmailUtilizadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_utilizado_excluir',__METHOD__,$arrObjEmailUtilizadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUtilizadoBD = new EmailUtilizadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEmailUtilizadoDTO);$i++){
        $objEmailUtilizadoBD->excluir($arrObjEmailUtilizadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Email Utilizado.',$e);
    }
  }

  protected function consultarConectado(EmailUtilizadoDTO $objEmailUtilizadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_utilizado_consultar',__METHOD__,$objEmailUtilizadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUtilizadoBD = new EmailUtilizadoBD($this->getObjInfraIBanco());
      $ret = $objEmailUtilizadoBD->consultar($objEmailUtilizadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Email Utilizado.',$e);
    }
  }

  protected function listarConectado(EmailUtilizadoDTO $objEmailUtilizadoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_utilizado_listar',__METHOD__,$objEmailUtilizadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUtilizadoBD = new EmailUtilizadoBD($this->getObjInfraIBanco());
      $ret = $objEmailUtilizadoBD->listar($objEmailUtilizadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Emails Utilizados.',$e);
    }
  }

  protected function contarConectado(EmailUtilizadoDTO $objEmailUtilizadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_utilizado_listar',__METHOD__,$objEmailUtilizadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUtilizadoBD = new EmailUtilizadoBD($this->getObjInfraIBanco());
      $ret = $objEmailUtilizadoBD->contar($objEmailUtilizadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Emails Utilizados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjEmailUtilizadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_utilizado_desativar',__METHOD__,$arrObjEmailUtilizadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUtilizadoBD = new EmailUtilizadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEmailUtilizadoDTO);$i++){
        $objEmailUtilizadoBD->desativar($arrObjEmailUtilizadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Email Utilizado.',$e);
    }
  }

  protected function reativarControlado($arrObjEmailUtilizadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_utilizado_reativar',__METHOD__,$arrObjEmailUtilizadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUtilizadoBD = new EmailUtilizadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEmailUtilizadoDTO);$i++){
        $objEmailUtilizadoBD->reativar($arrObjEmailUtilizadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Email Utilizado.',$e);
    }
  }

  protected function bloquearControlado(EmailUtilizadoDTO $objEmailUtilizadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_utilizado_consultar',__METHOD__,$objEmailUtilizadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUtilizadoBD = new EmailUtilizadoBD($this->getObjInfraIBanco());
      $ret = $objEmailUtilizadoBD->bloquear($objEmailUtilizadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Email Utilizado.',$e);
    }
  }

 */
}
?>