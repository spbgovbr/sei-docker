<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/01/2011 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelControleInternoSerieRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdSerie(RelControleInternoSerieDTO $objRelControleInternoSerieDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelControleInternoSerieDTO->getNumIdSerie())){
      $objInfraException->adicionarValidacao('Tipo do documento não informado.');
    }
  }

  private function validarNumIdControleInterno(RelControleInternoSerieDTO $objRelControleInternoSerieDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelControleInternoSerieDTO->getNumIdControleInterno())){
      $objInfraException->adicionarValidacao('Controle Interno não informado.');
    }
  }

  protected function cadastrarControlado(RelControleInternoSerieDTO $objRelControleInternoSerieDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_serie_cadastrar',__METHOD__,$objRelControleInternoSerieDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSerie($objRelControleInternoSerieDTO, $objInfraException);
      $this->validarNumIdControleInterno($objRelControleInternoSerieDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelControleInternoSerieBD = new RelControleInternoSerieBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoSerieBD->cadastrar($objRelControleInternoSerieDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Controle Interno.',$e);
    }
  }

  protected function alterarControlado(RelControleInternoSerieDTO $objRelControleInternoSerieDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_serie_alterar',__METHOD__,$objRelControleInternoSerieDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelControleInternoSerieDTO->isSetNumIdSerie()){
        $this->validarNumIdSerie($objRelControleInternoSerieDTO, $objInfraException);
      }
      if ($objRelControleInternoSerieDTO->isSetNumIdControleInterno()){
        $this->validarNumIdControleInterno($objRelControleInternoSerieDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelControleInternoSerieBD = new RelControleInternoSerieBD($this->getObjInfraIBanco());
      $objRelControleInternoSerieBD->alterar($objRelControleInternoSerieDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Controle Interno.',$e);
    }
  }

  protected function excluirControlado($arrObjRelControleInternoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_serie_excluir',__METHOD__,$arrObjRelControleInternoSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoSerieBD = new RelControleInternoSerieBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelControleInternoSerieDTO);$i++){
        $objRelControleInternoSerieBD->excluir($arrObjRelControleInternoSerieDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Controle Interno.',$e);
    }
  }

  protected function consultarConectado(RelControleInternoSerieDTO $objRelControleInternoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_serie_consultar',__METHOD__,$objRelControleInternoSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoSerieBD = new RelControleInternoSerieBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoSerieBD->consultar($objRelControleInternoSerieDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Controle Interno.',$e);
    }
  }

  protected function listarConectado(RelControleInternoSerieDTO $objRelControleInternoSerieDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_serie_listar',__METHOD__,$objRelControleInternoSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoSerieBD = new RelControleInternoSerieBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoSerieBD->listar($objRelControleInternoSerieDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Controles Internos.',$e);
    }
  }

  protected function contarConectado(RelControleInternoSerieDTO $objRelControleInternoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_serie_listar',__METHOD__,$objRelControleInternoSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoSerieBD = new RelControleInternoSerieBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoSerieBD->contar($objRelControleInternoSerieDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Controles Internos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelControleInternoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_serie_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoSerieBD = new RelControleInternoSerieBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelControleInternoSerieDTO);$i++){
        $objRelControleInternoSerieBD->desativar($arrObjRelControleInternoSerieDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Controle Interno.',$e);
    }
  }

  protected function reativarControlado($arrObjRelControleInternoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_serie_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoSerieBD = new RelControleInternoSerieBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelControleInternoSerieDTO);$i++){
        $objRelControleInternoSerieBD->reativar($arrObjRelControleInternoSerieDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Controle Interno.',$e);
    }
  }

  protected function bloquearControlado(RelControleInternoSerieDTO $objRelControleInternoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_controle_interno_serie_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelControleInternoSerieBD = new RelControleInternoSerieBD($this->getObjInfraIBanco());
      $ret = $objRelControleInternoSerieBD->bloquear($objRelControleInternoSerieDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Controle Interno.',$e);
    }
  }

 */
}
?>