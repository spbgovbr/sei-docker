<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/05/2013 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AuditoriaProtocoloRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarDblIdProtocolo(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAuditoriaProtocoloDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }
  }

  private function validarNumIdUsuario(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAuditoriaProtocoloDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarNumIdAnexo(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAuditoriaProtocoloDTO->getNumIdAnexo())){
      $objAuditoriaProtocoloDTO->setNumIdAnexo(null);
    }
  }
  
  private function validarNumVersao(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAuditoriaProtocoloDTO->getNumVersao())){
      $objAuditoriaProtocoloDTO->setNumVersao(null);
    }
  }

  private function validarDtaAuditoria(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAuditoriaProtocoloDTO->getDtaAuditoria())){
      $objInfraException->adicionarValidacao('Data de auditoria não informada.');
    }else{
      if (!InfraData::validarData($objAuditoriaProtocoloDTO->getDtaAuditoria())){
        $objInfraException->adicionarValidacao('Data inválida.');
      }
    }
  }

  public function auditarVisualizacao(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO) {
    try{
      
      if (AuditoriaSEI::getInstance()->verificar($objAuditoriaProtocoloDTO->getStrRecurso())){
        
        $objAuditoriaProtocoloDTO->retDblIdAuditoriaProtocolo();
        $objAuditoriaProtocoloDTO->setNumMaxRegistrosRetorno(1);
        
        if ($this->consultar($objAuditoriaProtocoloDTO)==null){
          
          $this->cadastrar($objAuditoriaProtocoloDTO);
          
          AuditoriaSEI::getInstance()->auditar($objAuditoriaProtocoloDTO->getStrRecurso(),__METHOD__,$objAuditoriaProtocoloDTO);
          
        }
      }
      
    }catch(Exception $e){
      throw new InfraException('Erro auditando visualização do protocolo.',$e);
    }
      
  }
  
  protected function cadastrarControlado(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('auditoria_protocolo_cadastrar',__METHOD__,$objAuditoriaProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProtocolo($objAuditoriaProtocoloDTO, $objInfraException);
      $this->validarNumIdUsuario($objAuditoriaProtocoloDTO, $objInfraException);
      $this->validarNumIdAnexo($objAuditoriaProtocoloDTO, $objInfraException);
      $this->validarNumVersao($objAuditoriaProtocoloDTO, $objInfraException);
      $this->validarDtaAuditoria($objAuditoriaProtocoloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAuditoriaProtocoloBD = new AuditoriaProtocoloBD($this->getObjInfraIBanco());
      $ret = $objAuditoriaProtocoloBD->cadastrar($objAuditoriaProtocoloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Auditoria de Protocolo.',$e);
    }
  }

  protected function alterarControlado(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO){
    try {

      //Valida Permissao
       SessaoSEI::getInstance()->validarAuditarPermissao('auditoria_protocolo_alterar',__METHOD__,$objAuditoriaProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAuditoriaProtocoloDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocolo($objAuditoriaProtocoloDTO, $objInfraException);
      }
      if ($objAuditoriaProtocoloDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objAuditoriaProtocoloDTO, $objInfraException);
      }
      if ($objAuditoriaProtocoloDTO->isSetNumIdAnexo()){
        $this->validarNumIdAnexo($objAuditoriaProtocoloDTO, $objInfraException);
      }
      if ($objAuditoriaProtocoloDTO->isSetNumVersao()){
        $this->validarNumVersao($objAuditoriaProtocoloDTO, $objInfraException);
      }
      if ($objAuditoriaProtocoloDTO->isSetDtaAuditoria()){
        $this->validarDtaAuditoria($objAuditoriaProtocoloDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAuditoriaProtocoloBD = new AuditoriaProtocoloBD($this->getObjInfraIBanco());
      $objAuditoriaProtocoloBD->alterar($objAuditoriaProtocoloDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Auditoria de Protocolo.',$e);
    }
  }

  protected function excluirControlado($arrObjAuditoriaProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('auditoria_protocolo_excluir',__METHOD__,$arrObjAuditoriaProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAuditoriaProtocoloBD = new AuditoriaProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAuditoriaProtocoloDTO);$i++){
        $objAuditoriaProtocoloBD->excluir($arrObjAuditoriaProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Auditoria de Protocolo.',$e);
    }
  }

  protected function consultarConectado(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('auditoria_protocolo_consultar',__METHOD__,$objAuditoriaProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAuditoriaProtocoloBD = new AuditoriaProtocoloBD($this->getObjInfraIBanco());
      $ret = $objAuditoriaProtocoloBD->consultar($objAuditoriaProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Auditoria de Protocolo.',$e);
    }
  }

  protected function listarConectado(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('auditoria_protocolo_listar',__METHOD__,$objAuditoriaProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAuditoriaProtocoloBD = new AuditoriaProtocoloBD($this->getObjInfraIBanco());
      $ret = $objAuditoriaProtocoloBD->listar($objAuditoriaProtocoloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Auditorias de Protocolos.',$e);
    }
  }

  protected function contarConectado(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('auditoria_protocolo_listar',__METHOD__,$objAuditoriaProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAuditoriaProtocoloBD = new AuditoriaProtocoloBD($this->getObjInfraIBanco());
      $ret = $objAuditoriaProtocoloBD->contar($objAuditoriaProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Auditorias de Protocolos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjAuditoriaProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('auditoria_protocolo_desativar',__METHOD__,$arrObjAuditoriaProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAuditoriaProtocoloBD = new AuditoriaProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAuditoriaProtocoloDTO);$i++){
        $objAuditoriaProtocoloBD->desativar($arrObjAuditoriaProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Auditoria de Protocolo.',$e);
    }
  }

  protected function reativarControlado($arrObjAuditoriaProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('auditoria_protocolo_reativar',__METHOD__,$arrObjAuditoriaProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAuditoriaProtocoloBD = new AuditoriaProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAuditoriaProtocoloDTO);$i++){
        $objAuditoriaProtocoloBD->reativar($arrObjAuditoriaProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Auditoria de Protocolo.',$e);
    }
  }

  protected function bloquearControlado(AuditoriaProtocoloDTO $objAuditoriaProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('auditoria_protocolo_consultar',__METHOD__,$objAuditoriaProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAuditoriaProtocoloBD = new AuditoriaProtocoloBD($this->getObjInfraIBanco());
      $ret = $objAuditoriaProtocoloBD->bloquear($objAuditoriaProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Auditoria de Protocolo.',$e);
    }
  }

 */
}
?>