<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/07/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../../SEI.php';

class RelSecaoModCjEstilosItemRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdSecaoModelo(RelSecaoModCjEstilosItemDTO $objRelSecaoModCjEstilosItemDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelSecaoModCjEstilosItemDTO->getNumIdSecaoModelo())){
      $objInfraException->adicionarValidacao('seção-modelo não informada.');
    }
  }

  private function validarNumIdConjuntoEstilosItem(RelSecaoModCjEstilosItemDTO $objRelSecaoModCjEstilosItemDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelSecaoModCjEstilosItemDTO->getNumIdConjuntoEstilosItem())){
      $objInfraException->adicionarValidacao('item do conjunto de estilos não informado.');
    }
  }

  private function validarStrSinPadrao(RelSecaoModCjEstilosItemDTO $objRelSecaoModCjEstilosItemDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelSecaoModCjEstilosItemDTO->getStrSinPadrao())){
      $objInfraException->adicionarValidacao('Sinalizador de padrão não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objRelSecaoModCjEstilosItemDTO->getStrSinPadrao())){
        $objInfraException->adicionarValidacao('Sinalizador de padrão inválid.');
      }
    }
  }

  protected function cadastrarControlado(RelSecaoModCjEstilosItemDTO $objRelSecaoModCjEstilosItemDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_mod_cj_estilos_item_cadastrar',__METHOD__,$objRelSecaoModCjEstilosItemDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSecaoModelo($objRelSecaoModCjEstilosItemDTO, $objInfraException);
      $this->validarNumIdConjuntoEstilosItem($objRelSecaoModCjEstilosItemDTO, $objInfraException);
      $this->validarStrSinPadrao($objRelSecaoModCjEstilosItemDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelSecaoModCjEstilosItemBD = new RelSecaoModCjEstilosItemBD($this->getObjInfraIBanco());
      $ret = $objRelSecaoModCjEstilosItemBD->cadastrar($objRelSecaoModCjEstilosItemDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(RelSecaoModCjEstilosItemDTO $objRelSecaoModCjEstilosItemDTO){
    try {

      //Valida Permissao
  	  SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_mod_cj_estilos_item_alterar',__METHOD__,$objRelSecaoModCjEstilosItemDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelSecaoModCjEstilosItemDTO->isSetNumIdSecaoModelo()){
        $this->validarNumIdSecaoModelo($objRelSecaoModCjEstilosItemDTO, $objInfraException);
      }
      if ($objRelSecaoModCjEstilosItemDTO->isSetNumIdConjuntoEstilosItem()){
        $this->validarNumIdConjuntoEstilosItem($objRelSecaoModCjEstilosItemDTO, $objInfraException);
      }
      if ($objRelSecaoModCjEstilosItemDTO->isSetStrSinPadrao()){
        $this->validarStrSinPadrao($objRelSecaoModCjEstilosItemDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelSecaoModCjEstilosItemBD = new RelSecaoModCjEstilosItemBD($this->getObjInfraIBanco());
      $objRelSecaoModCjEstilosItemBD->alterar($objRelSecaoModCjEstilosItemDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjRelSecaoModCjEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_mod_cj_estilos_item_excluir',__METHOD__,$arrObjRelSecaoModCjEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModCjEstilosItemBD = new RelSecaoModCjEstilosItemBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSecaoModCjEstilosItemDTO);$i++){
        $objRelSecaoModCjEstilosItemBD->excluir($arrObjRelSecaoModCjEstilosItemDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(RelSecaoModCjEstilosItemDTO $objRelSecaoModCjEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_mod_cj_estilos_item_consultar',__METHOD__,$objRelSecaoModCjEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModCjEstilosItemBD = new RelSecaoModCjEstilosItemBD($this->getObjInfraIBanco());
      $ret = $objRelSecaoModCjEstilosItemBD->consultar($objRelSecaoModCjEstilosItemDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(RelSecaoModCjEstilosItemDTO $objRelSecaoModCjEstilosItemDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_mod_cj_estilos_item_listar',__METHOD__,$objRelSecaoModCjEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModCjEstilosItemBD = new RelSecaoModCjEstilosItemBD($this->getObjInfraIBanco());
      $ret = $objRelSecaoModCjEstilosItemBD->listar($objRelSecaoModCjEstilosItemDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(RelSecaoModCjEstilosItemDTO $objRelSecaoModCjEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_mod_cj_estilos_item_listar',__METHOD__,$objRelSecaoModCjEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModCjEstilosItemBD = new RelSecaoModCjEstilosItemBD($this->getObjInfraIBanco());
      $ret = $objRelSecaoModCjEstilosItemBD->contar($objRelSecaoModCjEstilosItemDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/*
  protected function desativarControlado($arrObjRelSecaoModCjEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_mod_cj_estilos_item_desativar',__METHOD__,$arrObjRelSecaoModCjEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModCjEstilosItemBD = new RelSecaoModCjEstilosItemBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSecaoModCjEstilosItemDTO);$i++){
        $objRelSecaoModCjEstilosItemBD->desativar($arrObjRelSecaoModCjEstilosItemDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjRelSecaoModCjEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_mod_cj_estilos_item_reativar',__METHOD__,$arrObjRelSecaoModCjEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModCjEstilosItemBD = new RelSecaoModCjEstilosItemBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSecaoModCjEstilosItemDTO);$i++){
        $objRelSecaoModCjEstilosItemBD->reativar($arrObjRelSecaoModCjEstilosItemDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(RelSecaoModCjEstilosItemDTO $objRelSecaoModCjEstilosItemDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_mod_cj_estilos_item_consultar',__METHOD__,$objRelSecaoModCjEstilosItemDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModCjEstilosItemBD = new RelSecaoModCjEstilosItemBD($this->getObjInfraIBanco());
      $ret = $objRelSecaoModCjEstilosItemBD->bloquear($objRelSecaoModCjEstilosItemDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
?>