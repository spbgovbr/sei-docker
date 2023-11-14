<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/05/2014 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class VelocidadeTransferenciaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUsuario(VelocidadeTransferenciaDTO $objVelocidadeTransferenciaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objVelocidadeTransferenciaDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarNumIdUnidade(VelocidadeTransferenciaDTO $objVelocidadeTransferenciaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objVelocidadeTransferenciaDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Última Unidade não informada.');
    }
  }

  private function validarDblVelocidade(VelocidadeTransferenciaDTO $objVelocidadeTransferenciaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objVelocidadeTransferenciaDTO->getDblVelocidade())){
      $objInfraException->adicionarValidacao('Velocidade não informada.');
    }
  }

  protected function contabilizarControlado(VelocidadeTransferenciaDTO $parObjVelocidadeTransferenciaDTO) {
    try{
  
      $objVelocidadeTransferenciaDTO = new VelocidadeTransferenciaDTO();
      $objVelocidadeTransferenciaDTO->retDblVelocidade();
      $objVelocidadeTransferenciaDTO->setNumIdUsuario($parObjVelocidadeTransferenciaDTO->getNumIdUsuario());
      
      $objVelocidadeTransferenciaDTO = $this->consultar($objVelocidadeTransferenciaDTO);
      
      if ($objVelocidadeTransferenciaDTO==null){
        $this->cadastrar($parObjVelocidadeTransferenciaDTO);
      }else{
        $parObjVelocidadeTransferenciaDTO->setDblVelocidade(round(($parObjVelocidadeTransferenciaDTO->getDblVelocidade()+$objVelocidadeTransferenciaDTO->getDblVelocidade())/2));
        $this->alterar($parObjVelocidadeTransferenciaDTO);
      }
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro contabilizando Velocidade de Transferência de Dados.',$e);
    }
  }

  protected function cadastrarControlado(VelocidadeTransferenciaDTO $objVelocidadeTransferenciaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('velocidade_transferencia_cadastrar',__METHOD__,$objVelocidadeTransferenciaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUsuario($objVelocidadeTransferenciaDTO, $objInfraException);
      $this->validarNumIdUnidade($objVelocidadeTransferenciaDTO, $objInfraException);
      $this->validarDblVelocidade($objVelocidadeTransferenciaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objVelocidadeTransferenciaBD = new VelocidadeTransferenciaBD($this->getObjInfraIBanco());
      $ret = $objVelocidadeTransferenciaBD->cadastrar($objVelocidadeTransferenciaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Velocidade de Transferência de Dados.',$e);
    }
  }

  protected function alterarControlado(VelocidadeTransferenciaDTO $objVelocidadeTransferenciaDTO){
    try {

      //Valida Permissao
  	  SessaoSEI::getInstance()->validarAuditarPermissao('velocidade_transferencia_alterar',__METHOD__,$objVelocidadeTransferenciaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objVelocidadeTransferenciaDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objVelocidadeTransferenciaDTO, $objInfraException);
      }
      if ($objVelocidadeTransferenciaDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objVelocidadeTransferenciaDTO, $objInfraException);
      }
      if ($objVelocidadeTransferenciaDTO->isSetDblVelocidade()){
        $this->validarDblVelocidade($objVelocidadeTransferenciaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objVelocidadeTransferenciaBD = new VelocidadeTransferenciaBD($this->getObjInfraIBanco());
      $objVelocidadeTransferenciaBD->alterar($objVelocidadeTransferenciaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Velocidade de Transferência de Dados.',$e);
    }
  }

  protected function excluirControlado($arrObjVelocidadeTransferenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('velocidade_transferencia_excluir',__METHOD__,$arrObjVelocidadeTransferenciaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVelocidadeTransferenciaBD = new VelocidadeTransferenciaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjVelocidadeTransferenciaDTO);$i++){
        $objVelocidadeTransferenciaBD->excluir($arrObjVelocidadeTransferenciaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Velocidade de Transferência de Dados.',$e);
    }
  }

  protected function consultarConectado(VelocidadeTransferenciaDTO $objVelocidadeTransferenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('velocidade_transferencia_consultar',__METHOD__,$objVelocidadeTransferenciaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVelocidadeTransferenciaBD = new VelocidadeTransferenciaBD($this->getObjInfraIBanco());
      $ret = $objVelocidadeTransferenciaBD->consultar($objVelocidadeTransferenciaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Velocidade de Transferência de Dados.',$e);
    }
  }

  protected function listarConectado(VelocidadeTransferenciaDTO $objVelocidadeTransferenciaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('velocidade_transferencia_listar',__METHOD__,$objVelocidadeTransferenciaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVelocidadeTransferenciaBD = new VelocidadeTransferenciaBD($this->getObjInfraIBanco());
      $ret = $objVelocidadeTransferenciaBD->listar($objVelocidadeTransferenciaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Velocidades de Transferência de Dados.',$e);
    }
  }

  protected function contarConectado(VelocidadeTransferenciaDTO $objVelocidadeTransferenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('velocidade_transferencia_listar',__METHOD__,$objVelocidadeTransferenciaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVelocidadeTransferenciaBD = new VelocidadeTransferenciaBD($this->getObjInfraIBanco());
      $ret = $objVelocidadeTransferenciaBD->contar($objVelocidadeTransferenciaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Velocidades de Transferência de Dados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjVelocidadeTransferenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('velocidade_transferencia_desativar',__METHOD__,$arrObjVelocidadeTransferenciaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVelocidadeTransferenciaBD = new VelocidadeTransferenciaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjVelocidadeTransferenciaDTO);$i++){
        $objVelocidadeTransferenciaBD->desativar($arrObjVelocidadeTransferenciaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Velocidade de Transferência de Dados.',$e);
    }
  }

  protected function reativarControlado($arrObjVelocidadeTransferenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('velocidade_transferencia_reativar',__METHOD__,$arrObjVelocidadeTransferenciaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVelocidadeTransferenciaBD = new VelocidadeTransferenciaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjVelocidadeTransferenciaDTO);$i++){
        $objVelocidadeTransferenciaBD->reativar($arrObjVelocidadeTransferenciaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Velocidade de Transferência de Dados.',$e);
    }
  }

  protected function bloquearControlado(VelocidadeTransferenciaDTO $objVelocidadeTransferenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('velocidade_transferencia_consultar',__METHOD__,$objVelocidadeTransferenciaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVelocidadeTransferenciaBD = new VelocidadeTransferenciaBD($this->getObjInfraIBanco());
      $ret = $objVelocidadeTransferenciaBD->bloquear($objVelocidadeTransferenciaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Velocidade de Transferência de Dados.',$e);
    }
  }

 */
}
?>