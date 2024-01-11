<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: RelSecaoModeloEstiloRN.php 9153 2014-08-12 20:34:24Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class RelSecaoModeloEstiloRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdSecaoModelo(RelSecaoModeloEstiloDTO $objRelSecaoModeloEstiloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelSecaoModeloEstiloDTO->getNumIdSecaoModelo())){
      $objInfraException->adicionarValidacao('Modelo não informado.');
    }
  }

  private function validarNumIdEstilo(RelSecaoModeloEstiloDTO $objRelSecaoModeloEstiloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelSecaoModeloEstiloDTO->getNumIdEstilo())){
      $objInfraException->adicionarValidacao('Estilo não informado.');
    }
  }

  private function validarStrSinPadrao(RelSecaoModeloEstiloDTO $objRelSecaoModeloEstiloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelSecaoModeloEstiloDTO->getStrSinPadrao())){
      $objInfraException->adicionarValidacao('Sinalizador de Padrão não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objRelSecaoModeloEstiloDTO->getStrSinPadrao())){
        $objInfraException->adicionarValidacao('Sinalizador de Padrão inválido.');
      }
    }
  }

  protected function cadastrarControlado(RelSecaoModeloEstiloDTO $objRelSecaoModeloEstiloDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_modelo_estilo_cadastrar',__METHOD__,$objRelSecaoModeloEstiloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSecaoModelo($objRelSecaoModeloEstiloDTO, $objInfraException);
      $this->validarNumIdEstilo($objRelSecaoModeloEstiloDTO, $objInfraException);
      $this->validarStrSinPadrao($objRelSecaoModeloEstiloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelSecaoModeloEstiloBD = new RelSecaoModeloEstiloBD($this->getObjInfraIBanco());
      $ret = $objRelSecaoModeloEstiloBD->cadastrar($objRelSecaoModeloEstiloDTO);

      //Auditoria
      //cadastra na rel_secao_mod_cj_estilos_item
      $objEstiloDTO=new EstiloDTO();
      $objEstiloRN=new EstiloRN();
      $objEstiloDTO->setNumIdEstilo($objRelSecaoModeloEstiloDTO->getNumIdEstilo());
      $objEstiloDTO->retStrNome();
      $objEstiloDTO=$objEstiloRN->consultar($objEstiloDTO);

      $objConjuntoEstilosItemDTO=new ConjuntoEstilosItemDTO();
      $objConjuntoEstilosItemRN=new ConjuntoEstilosItemRN();
      $objConjuntoEstilosItemDTO->setStrNome($objEstiloDTO->getStrNome());
      $objConjuntoEstilosItemDTO->setOrdNumIdConjuntoEstilos(InfraDTO::$TIPO_ORDENACAO_DESC);
      $objConjuntoEstilosItemDTO->setNumMaxRegistrosRetorno(1);
      $objConjuntoEstilosItemDTO->retNumIdConjuntoEstilosItem();
      $objConjuntoEstilosItemDTO=$objConjuntoEstilosItemRN->consultar($objConjuntoEstilosItemDTO);

      $objRelSecaoModCjEstilosItemDTO= new RelSecaoModCjEstilosItemDTO();
      $objRelSecaoModCjEstilosItemRN= new RelSecaoModCjEstilosItemRN();
      $objRelSecaoModCjEstilosItemDTO->setNumIdSecaoModelo($objRelSecaoModeloEstiloDTO->getNumIdSecaoModelo());
      $objRelSecaoModCjEstilosItemDTO->setNumIdConjuntoEstilosItem($objConjuntoEstilosItemDTO->getNumIdConjuntoEstilosItem());
      $objRelSecaoModCjEstilosItemDTO->setStrSinPadrao($objRelSecaoModeloEstiloDTO->getStrSinPadrao());
      $objRelSecaoModCjEstilosItemRN->cadastrar($objRelSecaoModCjEstilosItemDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Estilo da Seção.',$e);
    }
  }

  protected function alterarControlado(RelSecaoModeloEstiloDTO $objRelSecaoModeloEstiloDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_modelo_estilo_alterar',__METHOD__,$objRelSecaoModeloEstiloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSecaoModelo($objRelSecaoModeloEstiloDTO, $objInfraException);
      $this->validarNumIdEstilo($objRelSecaoModeloEstiloDTO, $objInfraException);
      $this->validarStrSinPadrao($objRelSecaoModeloEstiloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelSecaoModeloEstiloBD = new RelSecaoModeloEstiloBD($this->getObjInfraIBanco());
      $objRelSecaoModeloEstiloBD->alterar($objRelSecaoModeloEstiloDTO);

      //altera rel_secao_mod_cj_estilos_item
      $objEstiloDTO=new EstiloDTO();
      $objEstiloRN=new EstiloRN();
      $objEstiloDTO->setNumIdEstilo($objRelSecaoModeloEstiloDTO->getNumIdEstilo());
      $objEstiloDTO->retStrNome();
      $objEstiloDTO=$objEstiloRN->consultar($objEstiloDTO);

      $objConjuntoEstilosItemDTO=new ConjuntoEstilosItemDTO();
      $objConjuntoEstilosItemRN=new ConjuntoEstilosItemRN();
      $objConjuntoEstilosItemDTO->setStrNome($objEstiloDTO->getStrNome());
      $objConjuntoEstilosItemDTO->setOrdNumIdConjuntoEstilos(InfraDTO::$TIPO_ORDENACAO_DESC);
      $objConjuntoEstilosItemDTO->setNumMaxRegistrosRetorno(1);
      $objConjuntoEstilosItemDTO->retNumIdConjuntoEstilosItem();
      $objConjuntoEstilosItemDTO=$objConjuntoEstilosItemRN->consultar($objConjuntoEstilosItemDTO);

      $objRelSecaoModCjEstilosItemDTO= new RelSecaoModCjEstilosItemDTO();
      $objRelSecaoModCjEstilosItemRN= new RelSecaoModCjEstilosItemRN();
      $objRelSecaoModCjEstilosItemDTO->setNumIdSecaoModelo($objRelSecaoModeloEstiloDTO->getNumIdSecaoModelo());
      $objRelSecaoModCjEstilosItemDTO->setNumIdConjuntoEstilosItem($objConjuntoEstilosItemDTO->getNumIdConjuntoEstilosItem());
      $objRelSecaoModCjEstilosItemDTO->setStrSinPadrao($objRelSecaoModeloEstiloDTO->getStrSinPadrao());
      $objRelSecaoModCjEstilosItemRN->alterar($objRelSecaoModCjEstilosItemDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Estilo da Seção.',$e);
    }
  }

  protected function excluirControlado($arrObjRelSecaoModeloEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_modelo_estilo_excluir',__METHOD__,$arrObjRelSecaoModeloEstiloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModeloEstiloBD = new RelSecaoModeloEstiloBD($this->getObjInfraIBanco());

      $objEstiloRN=new EstiloRN();
      $objConjuntoEstilosItemRN=new ConjuntoEstilosItemRN();
      $objRelSecaoModCjEstilosItemRN= new RelSecaoModCjEstilosItemRN();

      for($i=0;$i<count($arrObjRelSecaoModeloEstiloDTO);$i++){
        $objRelSecaoModeloEstiloBD->excluir($arrObjRelSecaoModeloEstiloDTO[$i]);
        //exclui rel_secao_mod_cj_estilos_item
        $objEstiloDTO=new EstiloDTO();
        $objEstiloDTO->setNumIdEstilo($arrObjRelSecaoModeloEstiloDTO[$i]->getNumIdEstilo());
        $objEstiloDTO->retStrNome();
        $objEstiloDTO=$objEstiloRN->consultar($objEstiloDTO);

        $objConjuntoEstilosItemDTO=new ConjuntoEstilosItemDTO();
        $objConjuntoEstilosItemDTO->setStrNome($objEstiloDTO->getStrNome());
        $objConjuntoEstilosItemDTO->setOrdNumIdConjuntoEstilos(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objConjuntoEstilosItemDTO->setNumMaxRegistrosRetorno(1);
        $objConjuntoEstilosItemDTO->retNumIdConjuntoEstilosItem();
        $objConjuntoEstilosItemDTO=$objConjuntoEstilosItemRN->consultar($objConjuntoEstilosItemDTO);

        $objRelSecaoModCjEstilosItemDTO= new RelSecaoModCjEstilosItemDTO();
        $objRelSecaoModCjEstilosItemDTO->setNumIdSecaoModelo($arrObjRelSecaoModeloEstiloDTO[$i]->getNumIdSecaoModelo());
        $objRelSecaoModCjEstilosItemDTO->setNumIdConjuntoEstilosItem($objConjuntoEstilosItemDTO->getNumIdConjuntoEstilosItem());
        $objRelSecaoModCjEstilosItemRN->excluir(array($objRelSecaoModCjEstilosItemDTO));
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Estilo da Seção.',$e);
    }
  }

  protected function consultarConectado(RelSecaoModeloEstiloDTO $objRelSecaoModeloEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_modelo_estilo_consultar',__METHOD__,$objRelSecaoModeloEstiloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModeloEstiloBD = new RelSecaoModeloEstiloBD($this->getObjInfraIBanco());
      $ret = $objRelSecaoModeloEstiloBD->consultar($objRelSecaoModeloEstiloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Estilo da Seção.',$e);
    }
  }

  protected function listarConectado(RelSecaoModeloEstiloDTO $objRelSecaoModeloEstiloDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_modelo_estilo_listar',__METHOD__,$objRelSecaoModeloEstiloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModeloEstiloBD = new RelSecaoModeloEstiloBD($this->getObjInfraIBanco());
      $ret = $objRelSecaoModeloEstiloBD->listar($objRelSecaoModeloEstiloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Estilos da Seção.',$e);
    }
  }

  protected function contarConectado(RelSecaoModeloEstiloDTO $objRelSecaoModeloEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_modelo_estilo_listar',__METHOD__,$objRelSecaoModeloEstiloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModeloEstiloBD = new RelSecaoModeloEstiloBD($this->getObjInfraIBanco());
      $ret = $objRelSecaoModeloEstiloBD->contar($objRelSecaoModeloEstiloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Estilos da Seção.',$e);
    }
  }
/*
  protected function desativarControlado($arrObjRelSecaoModeloEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_modelo_estilo_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModeloEstiloBD = new RelSecaoModeloEstiloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSecaoModeloEstiloDTO);$i++){
        $objRelSecaoModeloEstiloBD->desativar($arrObjRelSecaoModeloEstiloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Estilo da Seção.',$e);
    }
  }

  protected function reativarControlado($arrObjRelSecaoModeloEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_modelo_estilo_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModeloEstiloBD = new RelSecaoModeloEstiloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSecaoModeloEstiloDTO);$i++){
        $objRelSecaoModeloEstiloBD->reativar($arrObjRelSecaoModeloEstiloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Estilo da Seção.',$e);
    }
  }

  protected function bloquearControlado(RelSecaoModeloEstiloDTO $objRelSecaoModeloEstiloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_secao_modelo_estilo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSecaoModeloEstiloBD = new RelSecaoModeloEstiloBD($this->getObjInfraIBanco());
      $ret = $objRelSecaoModeloEstiloBD->bloquear($objRelSecaoModeloEstiloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Estilo da Seção.',$e);
    }
  }

 */
}
?>