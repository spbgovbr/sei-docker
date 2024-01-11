<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/07/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class FeriadoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdOrgao(FeriadoDTO $objFeriadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objFeriadoDTO->getNumIdOrgao())){
      $objFeriadoDTO->setNumIdOrgao(null);
    }
  }

  private function validarStrDescricao(FeriadoDTO $objFeriadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objFeriadoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objFeriadoDTO->setStrDescricao(trim($objFeriadoDTO->getStrDescricao()));

      if (strlen($objFeriadoDTO->getStrDescricao())>100){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 100 caracteres.');
      }
      
      $objFeriadoDTOBanco = new FeriadoDTO();
      $objFeriadoDTOBanco->setStrDescricao($objFeriadoDTO->getStrDescricao());
      $objFeriadoDTOBanco->setNumIdOrgao($objFeriadoDTO->getNumIdOrgao());
      $objFeriadoDTOBanco->setNumIdFeriado($objFeriadoDTO->getNumIdFeriado(),InfraDTO::$OPER_DIFERENTE);
      
      if ($this->contar($objFeriadoDTOBanco)){
        if ($objFeriadoDTO->getNumIdOrgao()==null){
          $objInfraException->adicionarValidacao('Já existe um feriado cadastrado com este nome para todos os órgãos.');
        }else{
          $objInfraException->adicionarValidacao('Já existe um feriado cadastrado com este nome para este órgão.');
        }
      }
      
    }
  }

  private function validarDtaFeriado(FeriadoDTO $objFeriadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objFeriadoDTO->getDtaFeriado())){
      $objInfraException->adicionarValidacao('Data do Feriado não informada.');
    }else{
      if (!InfraData::validarData($objFeriadoDTO->getDtaFeriado())){
        $objInfraException->adicionarValidacao('Data do Feriado inválida.');
      }
      
      $objFeriadoDTOBanco = new FeriadoDTO();
      $objFeriadoDTOBanco->setDtaFeriado($objFeriadoDTO->getDtaFeriado());
      $objFeriadoDTOBanco->setNumIdOrgao($objFeriadoDTO->getNumIdOrgao());
      $objFeriadoDTOBanco->setNumIdFeriado($objFeriadoDTO->getNumIdFeriado(),InfraDTO::$OPER_DIFERENTE);
      
      if ($this->contar($objFeriadoDTOBanco)){
        if ($objFeriadoDTO->getNumIdOrgao()==null){
          $objInfraException->adicionarValidacao('Já existe um feriado cadastrado com esta data para todos os órgãos.');
        }else{
          $objInfraException->adicionarValidacao('Já existe um feriado cadastrado com esta data para este órgão.');
        }
      }
      
      if ($objFeriadoDTO->getNumIdOrgao()==null){

        $objFeriadoDTOBanco = new FeriadoDTO();
        $objFeriadoDTOBanco->retStrDescricao();
        $objFeriadoDTOBanco->retStrSiglaOrgao();
        $objFeriadoDTOBanco->setDtaFeriado($objFeriadoDTO->getDtaFeriado());
        $objFeriadoDTOBanco->setNumIdOrgao(null,InfraDTO::$OPER_DIFERENTE);
        $objFeriadoDTOBanco->setNumIdFeriado($objFeriadoDTO->getNumIdFeriado(),InfraDTO::$OPER_DIFERENTE);
        
        $arrObjFeriadoDTO = $this->listar($objFeriadoDTOBanco);
        
        foreach($arrObjFeriadoDTO as $objFeriadoDTOBanco){
          $objInfraException->adicionarValidacao('Já existe um feriado cadastrado com esta data para o órgão '.$objFeriadoDTOBanco->getStrSiglaOrgao().'.');
        }
        
      }else{
        $objFeriadoDTOBanco = new FeriadoDTO();
        $objFeriadoDTOBanco->setDtaFeriado($objFeriadoDTO->getDtaFeriado());
        $objFeriadoDTOBanco->setNumIdOrgao(null);
        $objFeriadoDTOBanco->setNumIdFeriado($objFeriadoDTO->getNumIdFeriado(),InfraDTO::$OPER_DIFERENTE);
        
        if ($this->contar($objFeriadoDTOBanco)){
          $objInfraException->adicionarValidacao('Já existe um feriado cadastrado com esta data para todos os órgãos.');
        }
      }
    }
  }

  protected function cadastrarControlado(FeriadoDTO $objFeriadoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feriado_cadastrar',__METHOD__,$objFeriadoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdOrgao($objFeriadoDTO, $objInfraException);
      $this->validarStrDescricao($objFeriadoDTO, $objInfraException);
      $this->validarDtaFeriado($objFeriadoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objFeriadoBD = new FeriadoBD($this->getObjInfraIBanco());
      $ret = $objFeriadoBD->cadastrar($objFeriadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Feriado.',$e);
    }
  }

  protected function alterarControlado(FeriadoDTO $objFeriadoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('feriado_alterar',__METHOD__,$objFeriadoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objFeriadoDTO->isSetNumIdOrgao()){
        $this->validarNumIdOrgao($objFeriadoDTO, $objInfraException);
      }
      if ($objFeriadoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objFeriadoDTO, $objInfraException);
      }
      if ($objFeriadoDTO->isSetDtaFeriado()){
        $this->validarDtaFeriado($objFeriadoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objFeriadoBD = new FeriadoBD($this->getObjInfraIBanco());
      $objFeriadoBD->alterar($objFeriadoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Feriado.',$e);
    }
  }

  protected function excluirControlado($arrObjFeriadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feriado_excluir',__METHOD__,$arrObjFeriadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeriadoBD = new FeriadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjFeriadoDTO);$i++){
        $objFeriadoBD->excluir($arrObjFeriadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Feriado.',$e);
    }
  }

  protected function consultarConectado(FeriadoDTO $objFeriadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feriado_consultar',__METHOD__,$objFeriadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeriadoBD = new FeriadoBD($this->getObjInfraIBanco());
      $ret = $objFeriadoBD->consultar($objFeriadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Feriado.',$e);
    }
  }

  protected function listarConectado(FeriadoDTO $objFeriadoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feriado_listar',__METHOD__,$objFeriadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeriadoBD = new FeriadoBD($this->getObjInfraIBanco());
      $ret = $objFeriadoBD->listar($objFeriadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Feriados.',$e);
    }
  }

  protected function contarConectado(FeriadoDTO $objFeriadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feriado_listar',__METHOD__,$objFeriadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeriadoBD = new FeriadoBD($this->getObjInfraIBanco());
      $ret = $objFeriadoBD->contar($objFeriadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Feriados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjFeriadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feriado_desativar',__METHOD__,$arrObjFeriadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeriadoBD = new FeriadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjFeriadoDTO);$i++){
        $objFeriadoBD->desativar($arrObjFeriadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Feriado.',$e);
    }
  }

  protected function reativarControlado($arrObjFeriadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feriado_reativar',__METHOD__,$arrObjFeriadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeriadoBD = new FeriadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjFeriadoDTO);$i++){
        $objFeriadoBD->reativar($arrObjFeriadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Feriado.',$e);
    }
  }

  protected function bloquearControlado(FeriadoDTO $objFeriadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('feriado_consultar',__METHOD__,$objFeriadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objFeriadoBD = new FeriadoBD($this->getObjInfraIBanco());
      $ret = $objFeriadoBD->bloquear($objFeriadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Feriado.',$e);
    }
  }

 */
}
?>