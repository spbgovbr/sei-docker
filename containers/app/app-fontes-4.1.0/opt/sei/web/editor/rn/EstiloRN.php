<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: EstiloRN.php 7876 2013-08-20 14:59:25Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class EstiloRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(EstiloDTO $objEstiloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstiloDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objEstiloDTO->setStrNome(trim($objEstiloDTO->getStrNome()));

      if (strlen($objEstiloDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
      
      $dto = new EstiloDTO();
      $dto->setNumIdEstilo($objEstiloDTO->getNumIdEstilo(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrNome($objEstiloDTO->getStrNome(),InfraDTO::$OPER_IGUAL);
          
      if ($this->contar($dto)){
        $objInfraException->adicionarValidacao('Existe outra ocorrência de Estilo que utiliza o mesmo Nome.');
      }
    }
  }

  private function validarStrFormatacao(EstiloDTO $objEstiloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstiloDTO->getStrFormatacao())){
      $objInfraException->adicionarValidacao('Formatação não informada.');
    }else{
      $objEstiloDTO->setStrFormatacao(trim($objEstiloDTO->getStrFormatacao()));
    }
  }

  protected function cadastrarControlado(EstiloDTO $objEstiloDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estilo_cadastrar',__METHOD__,$objEstiloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objEstiloDTO, $objInfraException);
      $this->validarStrFormatacao($objEstiloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objEstiloBD = new EstiloBD($this->getObjInfraIBanco());
      $ret = $objEstiloBD->cadastrar($objEstiloDTO);

      $objConjuntoEstilosRN = new ConjuntoEstilosRN();
      $objConjuntoEstilosRN->sincronizar();
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Estilo.',$e);
    }
  }

  protected function alterarControlado(EstiloDTO $objEstiloDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('estilo_alterar',__METHOD__,$objEstiloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objEstiloDTO->isSetStrNome()){
        $this->validarStrNome($objEstiloDTO, $objInfraException);
      }
      if ($objEstiloDTO->isSetStrFormatacao()){
        $this->validarStrFormatacao($objEstiloDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objEstiloBD = new EstiloBD($this->getObjInfraIBanco());
      $objEstiloBD->alterar($objEstiloDTO);

      $objConjuntoEstilosRN = new ConjuntoEstilosRN();
      $objConjuntoEstilosRN->sincronizar();
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Estilo.',$e);
    }
  }

  protected function excluirControlado($arrObjEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estilo_excluir',__METHOD__,$arrObjEstiloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      if (count($arrObjEstiloDTO)){
        $dto = new EstiloDTO();
        $dto->retNumIdEstilo();
        $dto->retStrNome();
        $dto->setNumIdEstilo(InfraArray::converterArrInfraDTO($arrObjEstiloDTO,'IdEstilo'),InfraDTO::$OPER_IN);
        $arrObjEstiloDTOBanco = InfraArray::indexarArrInfraDTO($this->listar($dto),'IdEstilo');
      }

      $objRelSecaoModeloEstiloRN = new RelSecaoModeloEstiloRN();
      for($i=0;$i<count($arrObjEstiloDTO);$i++){

        $objRelSecaoModeloEstiloDTO = new RelSecaoModeloEstiloDTO();
        $objRelSecaoModeloEstiloDTO->setNumIdEstilo($arrObjEstiloDTO[$i]->getNumIdEstilo());
        
        if ($objRelSecaoModeloEstiloRN->contar($objRelSecaoModeloEstiloDTO)){
          $objInfraException->adicionarValidacao('Existem modelos utilizando o estilo "'.$arrObjEstiloDTOBanco[$arrObjEstiloDTO[$i]->getNumIdEstilo()]->getStrNome().'".');
        }
      }
      
      $objInfraException->lancarValidacoes();

      $objEstiloBD = new EstiloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEstiloDTO);$i++){
        $objEstiloBD->excluir($arrObjEstiloDTO[$i]);
      }
      
      $objConjuntoEstilosRN = new ConjuntoEstilosRN();
      $objConjuntoEstilosRN->sincronizar();
      

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Estilo.',$e);
    }
  }

  protected function consultarConectado(EstiloDTO $objEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estilo_consultar',__METHOD__,$objEstiloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstiloBD = new EstiloBD($this->getObjInfraIBanco());
      $ret = $objEstiloBD->consultar($objEstiloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Estilo.',$e);
    }
  }

  protected function listarConectado(EstiloDTO $objEstiloDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estilo_listar',__METHOD__,$objEstiloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstiloBD = new EstiloBD($this->getObjInfraIBanco());
      $ret = $objEstiloBD->listar($objEstiloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Estilos.',$e);
    }
  }

  protected function contarConectado(EstiloDTO $objEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estilo_listar',__METHOD__,$objEstiloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstiloBD = new EstiloBD($this->getObjInfraIBanco());
      $ret = $objEstiloBD->contar($objEstiloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Estilos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estilo_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstiloBD = new EstiloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEstiloDTO);$i++){
        $objEstiloBD->desativar($arrObjEstiloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Estilo.',$e);
    }
  }

  protected function reativarControlado($arrObjEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estilo_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstiloBD = new EstiloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEstiloDTO);$i++){
        $objEstiloBD->reativar($arrObjEstiloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Estilo.',$e);
    }
  }

  protected function bloquearControlado(EstiloDTO $objEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estilo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstiloBD = new EstiloBD($this->getObjInfraIBanco());
      $ret = $objEstiloBD->bloquear($objEstiloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Estilo.',$e);
    }
  }

 */
}
?>