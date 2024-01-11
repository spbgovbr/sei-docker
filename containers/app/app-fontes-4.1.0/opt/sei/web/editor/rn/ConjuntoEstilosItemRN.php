<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/11/2012 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id: ConjuntoEstilosItemRN.php 7876 2013-08-20 14:59:25Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ConjuntoEstilosItemRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdConjuntoEstilos(ConjuntoEstilosItemDTO $objConjuntoEstilosItemDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objConjuntoEstilosItemDTO->getNumIdConjuntoEstilos())){
      $objInfraException->adicionarValidacao('Conjunto de Estilos não informado.');
    }
  }

  private function validarStrNome(ConjuntoEstilosItemDTO $objConjuntoEstilosItemDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objConjuntoEstilosItemDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objConjuntoEstilosItemDTO->setStrNome(trim($objConjuntoEstilosItemDTO->getStrNome()));

      if (strlen($objConjuntoEstilosItemDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrFormatacao(ConjuntoEstilosItemDTO $objConjuntoEstilosItemDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objConjuntoEstilosItemDTO->getStrFormatacao())){
      $objInfraException->adicionarValidacao('Formatação não informada.');
    }else{
      $objConjuntoEstilosItemDTO->setStrFormatacao(trim($objConjuntoEstilosItemDTO->getStrFormatacao()));
    }
  }

  protected function cadastrarControlado(ConjuntoEstilosItemDTO $objConjuntoEstilosItemDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_item_cadastrar',__METHOD__,$objConjuntoEstilosItemDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdConjuntoEstilos($objConjuntoEstilosItemDTO, $objInfraException);
      $this->validarStrNome($objConjuntoEstilosItemDTO, $objInfraException);
      $this->validarStrFormatacao($objConjuntoEstilosItemDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objConjuntoEstilosItemBD = new ConjuntoEstilosItemBD($this->getObjInfraIBanco());
      $ret = $objConjuntoEstilosItemBD->cadastrar($objConjuntoEstilosItemDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Item do Conjunto de Estilos.',$e);
    }
  }

  protected function alterarControlado(ConjuntoEstilosItemDTO $objConjuntoEstilosItemDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_item_alterar',__METHOD__,$objConjuntoEstilosItemDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objConjuntoEstilosItemDTO->isSetNumIdConjuntoEstilos()){
        $this->validarNumIdConjuntoEstilos($objConjuntoEstilosItemDTO, $objInfraException);
      }
      if ($objConjuntoEstilosItemDTO->isSetStrNome()){
        $this->validarStrNome($objConjuntoEstilosItemDTO, $objInfraException);
      }
      if ($objConjuntoEstilosItemDTO->isSetStrFormatacao()){
        $this->validarStrFormatacao($objConjuntoEstilosItemDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objConjuntoEstilosItemBD = new ConjuntoEstilosItemBD($this->getObjInfraIBanco());
      $objConjuntoEstilosItemBD->alterar($objConjuntoEstilosItemDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Item do Conjunto de Estilos.',$e);
    }
  }

  protected function excluirControlado($arrObjConjuntoEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_item_excluir',__METHOD__,$arrObjConjuntoEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosItemBD = new ConjuntoEstilosItemBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjConjuntoEstilosItemDTO);$i++){
        $objConjuntoEstilosItemBD->excluir($arrObjConjuntoEstilosItemDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Item do Conjunto de Estilos.',$e);
    }
  }

  protected function consultarConectado(ConjuntoEstilosItemDTO $objConjuntoEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_item_consultar',__METHOD__,$objConjuntoEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosItemBD = new ConjuntoEstilosItemBD($this->getObjInfraIBanco());
      $ret = $objConjuntoEstilosItemBD->consultar($objConjuntoEstilosItemDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Item do Conjunto de Estilos.',$e);
    }
  }

  protected function listarConectado(ConjuntoEstilosItemDTO $objConjuntoEstilosItemDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_item_listar',__METHOD__,$objConjuntoEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosItemBD = new ConjuntoEstilosItemBD($this->getObjInfraIBanco());
      $ret = $objConjuntoEstilosItemBD->listar($objConjuntoEstilosItemDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Itens do Conjuntos de Estilos.',$e);
    }
  }

  protected function contarConectado(ConjuntoEstilosItemDTO $objConjuntoEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_item_listar',__METHOD__,$objConjuntoEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosItemBD = new ConjuntoEstilosItemBD($this->getObjInfraIBanco());
      $ret = $objConjuntoEstilosItemBD->contar($objConjuntoEstilosItemDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Itens do Conjuntos de Estilos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjConjuntoEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_item_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosItemBD = new ConjuntoEstilosItemBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjConjuntoEstilosItemDTO);$i++){
        $objConjuntoEstilosItemBD->desativar($arrObjConjuntoEstilosItemDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Item do Conjunto de Estilos.',$e);
    }
  }

  protected function reativarControlado($arrObjConjuntoEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_item_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosItemBD = new ConjuntoEstilosItemBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjConjuntoEstilosItemDTO);$i++){
        $objConjuntoEstilosItemBD->reativar($arrObjConjuntoEstilosItemDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Item do Conjunto de Estilos.',$e);
    }
  }

  protected function bloquearControlado(ConjuntoEstilosItemDTO $objConjuntoEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_item_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosItemBD = new ConjuntoEstilosItemBD($this->getObjInfraIBanco());
      $ret = $objConjuntoEstilosItemBD->bloquear($objConjuntoEstilosItemDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Item do Conjunto de Estilos.',$e);
    }
  }

 */
}
?>