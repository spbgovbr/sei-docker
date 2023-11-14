<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/06/2010 - criado por fazenda_db
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EmailUnidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUnidade(EmailUnidadeDTO $objEmailUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEmailUnidadeDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Id da Unidade não informado.');
    }
  }

  private function validarStrEmail(EmailUnidadeDTO $objEmailUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEmailUnidadeDTO->getStrEmail())){
      $objInfraException->adicionarValidacao('E-mail não informado.');
    }else{
      $objEmailUnidadeDTO->setStrEmail(trim($objEmailUnidadeDTO->getStrEmail()));

      if (strlen($objEmailUnidadeDTO->getStrEmail())>50){
        $objInfraException->adicionarValidacao('E-mail possui tamanho superior a 50 caracteres.');
      }
      
      if (!InfraUtil::validarEmail($objEmailUnidadeDTO->getStrEmail())){
        $objInfraException->adicionarValidacao('E-mail '.$objEmailUnidadeDTO->getStrEmail().' inválido.');
      }
      
      $objEmailUnidadeDTOBanco = new EmailUnidadeDTO();
      $objEmailUnidadeDTOBanco->setNumIdUnidade($objEmailUnidadeDTO->getNumIdUnidade());
      $objEmailUnidadeDTOBanco->setStrEmail($objEmailUnidadeDTO->getStrEmail());
      
      $objEmailUnidadeRN = new EmailUnidadeRN();
      
      if($objEmailUnidadeRN->contar($objEmailUnidadeDTOBanco) > 0){
      	$objInfraException->adicionarValidacao('E-mail '.$objEmailUnidadeDTO->getStrEmail().' duplicado.');
      }      
      
    }
  }

  private function validarStrDescricao(EmailUnidadeDTO $objEmailUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEmailUnidadeDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objEmailUnidadeDTO->setStrDescricao(trim($objEmailUnidadeDTO->getStrDescricao()));

      if (strlen($objEmailUnidadeDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(EmailUnidadeDTO $objEmailUnidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_unidade_cadastrar',__METHOD__,$objEmailUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidade($objEmailUnidadeDTO, $objInfraException);
      $this->validarStrEmail($objEmailUnidadeDTO, $objInfraException);
      $this->validarStrDescricao($objEmailUnidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objEmailUnidadeBD = new EmailUnidadeBD($this->getObjInfraIBanco());
      $ret = $objEmailUnidadeBD->cadastrar($objEmailUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Endereco Eletronico.',$e);
    }
  }

  protected function alterarControlado(EmailUnidadeDTO $objEmailUnidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('email_unidade_alterar',__METHOD__,$objEmailUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objEmailUnidadeDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objEmailUnidadeDTO, $objInfraException);
      }
      if ($objEmailUnidadeDTO->isSetStrEmail()){
        $this->validarStrEmail($objEmailUnidadeDTO, $objInfraException);
      }
      if ($objEmailUnidadeDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objEmailUnidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objEmailUnidadeBD = new EmailUnidadeBD($this->getObjInfraIBanco());
      $objEmailUnidadeBD->alterar($objEmailUnidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Endereco Eletronico.',$e);
    }
  }

  protected function excluirControlado($arrObjEmailUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_unidade_excluir',__METHOD__,$arrObjEmailUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUnidadeBD = new EmailUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEmailUnidadeDTO);$i++){
        $objEmailUnidadeBD->excluir($arrObjEmailUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Endereco Eletronico.',$e);
    }
  }

  protected function consultarConectado(EmailUnidadeDTO $objEmailUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_unidade_consultar',__METHOD__,$objEmailUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUnidadeBD = new EmailUnidadeBD($this->getObjInfraIBanco());
      $ret = $objEmailUnidadeBD->consultar($objEmailUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Endereco Eletronico.',$e);
    }
  }

  protected function listarConectado(EmailUnidadeDTO $objEmailUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_unidade_listar',__METHOD__,$objEmailUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUnidadeBD = new EmailUnidadeBD($this->getObjInfraIBanco());
      $ret = $objEmailUnidadeBD->listar($objEmailUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Endereços Eletronicos.',$e);
    }
  }

  protected function contarConectado(EmailUnidadeDTO $objEmailUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_unidade_listar',__METHOD__,$objEmailUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUnidadeBD = new EmailUnidadeBD($this->getObjInfraIBanco());
      $ret = $objEmailUnidadeBD->contar($objEmailUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Endereços Eletronicos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjEmailUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_unidade_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUnidadeBD = new EmailUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEmailUnidadeDTO);$i++){
        $objEmailUnidadeBD->desativar($arrObjEmailUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Endereco Eletronico.',$e);
    }
  }

  protected function reativarControlado($arrObjEmailUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_unidade_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUnidadeBD = new EmailUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEmailUnidadeDTO);$i++){
        $objEmailUnidadeBD->reativar($arrObjEmailUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Endereco Eletronico.',$e);
    }
  }

  protected function bloquearControlado(EmailUnidadeDTO $objEmailUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('email_unidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailUnidadeBD = new EmailUnidadeBD($this->getObjInfraIBanco());
      $ret = $objEmailUnidadeBD->bloquear($objEmailUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Endereco Eletronico.',$e);
    }
  }

 */
}
?>