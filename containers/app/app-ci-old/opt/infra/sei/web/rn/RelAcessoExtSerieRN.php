<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/08/2019 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelAcessoExtSerieRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdAcessoExterno(RelAcessoExtSerieDTO $objRelAcessoExtSerieDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelAcessoExtSerieDTO->getNumIdAcessoExterno())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdSerie(RelAcessoExtSerieDTO $objRelAcessoExtSerieDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelAcessoExtSerieDTO->getNumIdSerie())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  protected function cadastrarControlado(RelAcessoExtSerieDTO $objRelAcessoExtSerieDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_serie_cadastrar',__METHOD__,$objRelAcessoExtSerieDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdAcessoExterno($objRelAcessoExtSerieDTO, $objInfraException);
      $this->validarNumIdSerie($objRelAcessoExtSerieDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelAcessoExtSerieBD = new RelAcessoExtSerieBD($this->getObjInfraIBanco());
      $ret = $objRelAcessoExtSerieBD->cadastrar($objRelAcessoExtSerieDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(RelAcessoExtSerieDTO $objRelAcessoExtSerieDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_serie_alterar',__METHOD__,$objRelAcessoExtSerieDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelAcessoExtSerieDTO->isSetNumIdAcessoExterno()){
        $this->validarNumIdAcessoExterno($objRelAcessoExtSerieDTO, $objInfraException);
      }
      if ($objRelAcessoExtSerieDTO->isSetNumIdSerie()){
        $this->validarNumIdSerie($objRelAcessoExtSerieDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelAcessoExtSerieBD = new RelAcessoExtSerieBD($this->getObjInfraIBanco());
      $objRelAcessoExtSerieBD->alterar($objRelAcessoExtSerieDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjRelAcessoExtSerieDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_serie_excluir',__METHOD__,$arrObjRelAcessoExtSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtSerieBD = new RelAcessoExtSerieBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAcessoExtSerieDTO);$i++){
        $objRelAcessoExtSerieBD->excluir($arrObjRelAcessoExtSerieDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(RelAcessoExtSerieDTO $objRelAcessoExtSerieDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_serie_consultar',__METHOD__,$objRelAcessoExtSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtSerieBD = new RelAcessoExtSerieBD($this->getObjInfraIBanco());
      $ret = $objRelAcessoExtSerieBD->consultar($objRelAcessoExtSerieDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(RelAcessoExtSerieDTO $objRelAcessoExtSerieDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_serie_listar',__METHOD__,$objRelAcessoExtSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtSerieBD = new RelAcessoExtSerieBD($this->getObjInfraIBanco());
      $ret = $objRelAcessoExtSerieBD->listar($objRelAcessoExtSerieDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(RelAcessoExtSerieDTO $objRelAcessoExtSerieDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_serie_listar',__METHOD__,$objRelAcessoExtSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtSerieBD = new RelAcessoExtSerieBD($this->getObjInfraIBanco());
      $ret = $objRelAcessoExtSerieBD->contar($objRelAcessoExtSerieDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelAcessoExtSerieDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_serie_desativar',__METHOD__,$arrObjRelAcessoExtSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtSerieBD = new RelAcessoExtSerieBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAcessoExtSerieDTO);$i++){
        $objRelAcessoExtSerieBD->desativar($arrObjRelAcessoExtSerieDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjRelAcessoExtSerieDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_serie_reativar',__METHOD__,$arrObjRelAcessoExtSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtSerieBD = new RelAcessoExtSerieBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAcessoExtSerieDTO);$i++){
        $objRelAcessoExtSerieBD->reativar($arrObjRelAcessoExtSerieDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(RelAcessoExtSerieDTO $objRelAcessoExtSerieDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_acesso_ext_serie_consultar',__METHOD__,$objRelAcessoExtSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAcessoExtSerieBD = new RelAcessoExtSerieBD($this->getObjInfraIBanco());
      $ret = $objRelAcessoExtSerieBD->bloquear($objRelAcessoExtSerieDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
