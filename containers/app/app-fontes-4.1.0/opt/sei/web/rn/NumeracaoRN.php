<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/09/2012 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class NumeracaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumSequencial(NumeracaoDTO $objNumeracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objNumeracaoDTO->getNumSequencial())){
      $objInfraException->adicionarValidacao('Sequencial não informado.');
    }
  }

  private function validarNumAno(NumeracaoDTO $objNumeracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objNumeracaoDTO->getNumAno())){
      $objNumeracaoDTO->setNumAno(null);
    }
  }

  private function validarNumIdSerie(NumeracaoDTO $objNumeracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objNumeracaoDTO->getNumIdSerie())){
      $objInfraException->adicionarValidacao('Tipo do documento não informado.');
    }
  }

  private function validarNumIdOrgao(NumeracaoDTO $objNumeracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objNumeracaoDTO->getNumIdOrgao())){
      $objNumeracaoDTO->setNumIdOrgao(null);
    }
  }

  private function validarNumIdUnidade(NumeracaoDTO $objNumeracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objNumeracaoDTO->getNumIdUnidade())){
      $objNumeracaoDTO->setNumIdUnidade(null);
    }
  }

  protected function ajustarControlado(NumeracaoDTO $parObjNumeracaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('numeracao_ajustar',__METHOD__,$parObjNumeracaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objNumeracaoDTO = new NumeracaoDTO();
      $objNumeracaoDTO->setNumIdNumeracao($parObjNumeracaoDTO->getNumIdNumeracao());
      $objNumeracaoDTO = $this->bloquear($objNumeracaoDTO);
      
      if ($objNumeracaoDTO->getNumSequencial()!=$parObjNumeracaoDTO->getNumSequencialOriginal()){
        $objInfraException->lancarValidacao('Sequencial foi alterado desde a última visualização de '.$parObjNumeracaoDTO->getNumSequencialOriginal().' para '.$objNumeracaoDTO->getNumSequencial().'.');
      }

      if ($objNumeracaoDTO->getNumSequencial()!=$parObjNumeracaoDTO->getNumSequencial()){
        $objNumeracaoDTO = new NumeracaoDTO();
        $objNumeracaoDTO->setNumSequencial($parObjNumeracaoDTO->getNumSequencial());
        $objNumeracaoDTO->setNumIdNumeracao($parObjNumeracaoDTO->getNumIdNumeracao());
        
        $objNumeracaoBD = new NumeracaoBD($this->getObjInfraIBanco());
        $objNumeracaoBD->alterar($objNumeracaoDTO);
      }
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro ajustando Numeração.',$e);
    }
  }
  
  protected function cadastrarControlado(NumeracaoDTO $objNumeracaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('numeracao_cadastrar',__METHOD__,$objNumeracaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumSequencial($objNumeracaoDTO, $objInfraException);
      $this->validarNumAno($objNumeracaoDTO, $objInfraException);
      $this->validarNumIdSerie($objNumeracaoDTO, $objInfraException);
      $this->validarNumIdOrgao($objNumeracaoDTO, $objInfraException);
      $this->validarNumIdUnidade($objNumeracaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objNumeracaoBD = new NumeracaoBD($this->getObjInfraIBanco());
      $ret = $objNumeracaoBD->cadastrar($objNumeracaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Numeração.',$e);
    }
  }

  protected function alterarControlado(NumeracaoDTO $objNumeracaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('numeracao_alterar',__METHOD__,$objNumeracaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objNumeracaoDTO->isSetNumSequencial()){
        $this->validarNumSequencial($objNumeracaoDTO, $objInfraException);
      }
      
      if ($objNumeracaoDTO->isSetNumAno()){
        $this->validarNumAno($objNumeracaoDTO, $objInfraException);
      }
      
      if ($objNumeracaoDTO->isSetNumIdSerie()){
        $this->validarNumIdSerie($objNumeracaoDTO, $objInfraException);
      }
      
      if ($objNumeracaoDTO->isSetNumIdOrgao()){
        $this->validarNumIdOrgao($objNumeracaoDTO, $objInfraException);
      }
      
      if ($objNumeracaoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objNumeracaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objNumeracaoBD = new NumeracaoBD($this->getObjInfraIBanco());
      $objNumeracaoBD->alterar($objNumeracaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Numeração.',$e);
    }
  }
  
  protected function excluirControlado($arrObjNumeracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('numeracao_excluir',__METHOD__,$arrObjNumeracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNumeracaoBD = new NumeracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjNumeracaoDTO);$i++){
        $objNumeracaoBD->excluir($arrObjNumeracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Numeração.',$e);
    }
  }

  protected function consultarConectado(NumeracaoDTO $objNumeracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('numeracao_consultar',__METHOD__,$objNumeracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNumeracaoBD = new NumeracaoBD($this->getObjInfraIBanco());
      $ret = $objNumeracaoBD->consultar($objNumeracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Numeração.',$e);
    }
  }

  protected function listarConectado(NumeracaoDTO $objNumeracaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('numeracao_listar',__METHOD__,$objNumeracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNumeracaoBD = new NumeracaoBD($this->getObjInfraIBanco());
      $ret = $objNumeracaoBD->listar($objNumeracaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Numerações.',$e);
    }
  }

  protected function contarConectado(NumeracaoDTO $objNumeracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('numeracao_listar',__METHOD__,$objNumeracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNumeracaoBD = new NumeracaoBD($this->getObjInfraIBanco());
      $ret = $objNumeracaoBD->contar($objNumeracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Numerações.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjNumeracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('numeracao_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNumeracaoBD = new NumeracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjNumeracaoDTO);$i++){
        $objNumeracaoBD->desativar($arrObjNumeracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Numeração.',$e);
    }
  }

  protected function reativarControlado($arrObjNumeracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('numeracao_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNumeracaoBD = new NumeracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjNumeracaoDTO);$i++){
        $objNumeracaoBD->reativar($arrObjNumeracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Numeração.',$e);
    }
  }
*/
  protected function bloquearControlado(NumeracaoDTO $objNumeracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('numeracao_consultar',__METHOD__,$objNumeracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNumeracaoBD = new NumeracaoBD($this->getObjInfraIBanco());
      $ret = $objNumeracaoBD->bloquear($objNumeracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Numeração.',$e);
    }
  }
}
?>