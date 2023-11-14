<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/03/2012 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AlertaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUnidade(AlertaDTO $objAlertaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAlertaDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarStrSinBlocoAssinatura(AlertaDTO $objAlertaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAlertaDTO->getStrSinBlocoAssinatura())){
      $objInfraException->adicionarValidacao('Sinalizador de Bloco de Assinatura não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objAlertaDTO->getStrSinBlocoAssinatura())){
        $objInfraException->adicionarValidacao('Sinalizador de Bloco de Assinatura inválido.');
      }
    }
  }

  private function validarStrSinBlocoReuniao(AlertaDTO $objAlertaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAlertaDTO->getStrSinBlocoReuniao())){
      $objInfraException->adicionarValidacao('Sinalizador de Bloco de Reunião não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objAlertaDTO->getStrSinBlocoReuniao())){
        $objInfraException->adicionarValidacao('Sinalizador de Bloco de Reunião inválido.');
      }
    }
  }

  private function validarStrSinControleProcessos(AlertaDTO $objAlertaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAlertaDTO->getStrSinControleProcessos())){
      $objInfraException->adicionarValidacao('Sinalizador de Controle de Processos não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objAlertaDTO->getStrSinControleProcessos())){
        $objInfraException->adicionarValidacao('Sinalizador de Controle de Processos inválido.');
      }
    }
  }

  protected function cadastrarControlado(AlertaDTO $objAlertaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('alerta_cadastrar',__METHOD__,$objAlertaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
    
      if (!$objAlertaDTO->isSetStrSinBlocoAssinatura()){
        $objAlertaDTO->setStrSinBlocoAssinatura('N');
      }
      if (!$objAlertaDTO->isSetStrSinBlocoReuniao()){
        $objAlertaDTO->setStrSinBlocoReuniao('N');
      }
      if (!$objAlertaDTO->isSetStrSinControleProcessos()){
        $objAlertaDTO->setStrSinControleProcessos('N');
      }

      $this->validarNumIdUnidade($objAlertaDTO, $objInfraException);
      $this->validarStrSinBlocoAssinatura($objAlertaDTO, $objInfraException);
      $this->validarStrSinBlocoReuniao($objAlertaDTO, $objInfraException);
      $this->validarStrSinControleProcessos($objAlertaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAlertaBD = new AlertaBD($this->getObjInfraIBanco());
      $ret = $objAlertaBD->cadastrar($objAlertaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Alerta.',$e);
    }
  }

  protected function alterarControlado(AlertaDTO $objAlertaDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('alerta_alterar',__METHOD__,$objAlertaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAlertaDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objAlertaDTO, $objInfraException);
      }
      if ($objAlertaDTO->isSetStrSinBlocoAssinatura()){
        $this->validarStrSinBlocoAssinatura($objAlertaDTO, $objInfraException);
      }
      if ($objAlertaDTO->isSetStrSinBlocoReuniao()){
        $this->validarStrSinBlocoReuniao($objAlertaDTO, $objInfraException);
      }
      if ($objAlertaDTO->isSetStrSinControleProcessos()){
        $this->validarStrSinControleProcessos($objAlertaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAlertaBD = new AlertaBD($this->getObjInfraIBanco());
      $objAlertaBD->alterar($objAlertaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Alerta.',$e);
    }
  }

  protected function excluirControlado($arrObjAlertaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('alerta_excluir',__METHOD__,$arrObjAlertaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAlertaBD = new AlertaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAlertaDTO);$i++){
        $objAlertaBD->excluir($arrObjAlertaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Alerta.',$e);
    }
  }

  protected function consultarConectado(AlertaDTO $objAlertaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('alerta_consultar',__METHOD__,$objAlertaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAlertaBD = new AlertaBD($this->getObjInfraIBanco());
      $ret = $objAlertaBD->consultar($objAlertaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Alerta.',$e);
    }
  }

  protected function listarConectado(AlertaDTO $objAlertaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('alerta_listar',__METHOD__,$objAlertaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAlertaBD = new AlertaBD($this->getObjInfraIBanco());
      $ret = $objAlertaBD->listar($objAlertaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Alertas.',$e);
    }
  }

  protected function contarConectado(AlertaDTO $objAlertaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('alerta_listar',__METHOD__,$objAlertaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAlertaBD = new AlertaBD($this->getObjInfraIBanco());
      $ret = $objAlertaBD->contar($objAlertaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Alertas.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjAlertaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('alerta_desativar',__METHOD__,$arrObjAlertaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAlertaBD = new AlertaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAlertaDTO);$i++){
        $objAlertaBD->desativar($arrObjAlertaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Alerta.',$e);
    }
  }

  protected function reativarControlado($arrObjAlertaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('alerta_reativar',__METHOD__,$arrObjAlertaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAlertaBD = new AlertaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAlertaDTO);$i++){
        $objAlertaBD->reativar($arrObjAlertaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Alerta.',$e);
    }
  }

  protected function bloquearControlado(AlertaDTO $objAlertaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('alerta_consultar',__METHOD__,$objAlertaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAlertaBD = new AlertaBD($this->getObjInfraIBanco());
      $ret = $objAlertaBD->bloquear($objAlertaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Alerta.',$e);
    }
  }

 */
}
?>