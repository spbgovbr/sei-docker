<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/06/2021 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelAvisoOrgaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdAviso(RelAvisoOrgaoDTO $objRelAvisoOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelAvisoOrgaoDTO->getNumIdAviso())){
      $objInfraException->adicionarValidacao('Aviso não informado.');
    }
  }

  private function validarNumIdOrgao(RelAvisoOrgaoDTO $objRelAvisoOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelAvisoOrgaoDTO->getNumIdOrgao())){
      $objInfraException->adicionarValidacao('Órgão não informado.');
    }
  }

  protected function cadastrarControlado(RelAvisoOrgaoDTO $objRelAvisoOrgaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_aviso_orgao_cadastrar', __METHOD__, $objRelAvisoOrgaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdAviso($objRelAvisoOrgaoDTO, $objInfraException);
      $this->validarNumIdOrgao($objRelAvisoOrgaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelAvisoOrgaoBD = new RelAvisoOrgaoBD($this->getObjInfraIBanco());
      $ret = $objRelAvisoOrgaoBD->cadastrar($objRelAvisoOrgaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Órgão do Aviso.',$e);
    }
  }

  protected function alterarControlado(RelAvisoOrgaoDTO $objRelAvisoOrgaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_aviso_orgao_alterar', __METHOD__, $objRelAvisoOrgaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelAvisoOrgaoDTO->isSetNumIdAviso()){
        $this->validarNumIdAviso($objRelAvisoOrgaoDTO, $objInfraException);
      }
      if ($objRelAvisoOrgaoDTO->isSetNumIdOrgao()){
        $this->validarNumIdOrgao($objRelAvisoOrgaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelAvisoOrgaoBD = new RelAvisoOrgaoBD($this->getObjInfraIBanco());
      $objRelAvisoOrgaoBD->alterar($objRelAvisoOrgaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Órgão do Aviso.',$e);
    }
  }

  protected function excluirControlado($arrObjRelAvisoOrgaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_aviso_orgao_excluir', __METHOD__, $arrObjRelAvisoOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAvisoOrgaoBD = new RelAvisoOrgaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAvisoOrgaoDTO);$i++){
        $objRelAvisoOrgaoBD->excluir($arrObjRelAvisoOrgaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Órgão do Aviso.',$e);
    }
  }

  protected function consultarConectado(RelAvisoOrgaoDTO $objRelAvisoOrgaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_aviso_orgao_consultar', __METHOD__, $objRelAvisoOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAvisoOrgaoBD = new RelAvisoOrgaoBD($this->getObjInfraIBanco());
      $ret = $objRelAvisoOrgaoBD->consultar($objRelAvisoOrgaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Órgão do Aviso.',$e);
    }
  }

  protected function listarConectado(RelAvisoOrgaoDTO $objRelAvisoOrgaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_aviso_orgao_listar', __METHOD__, $objRelAvisoOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAvisoOrgaoBD = new RelAvisoOrgaoBD($this->getObjInfraIBanco());
      $ret = $objRelAvisoOrgaoBD->listar($objRelAvisoOrgaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Órgãos do Aviso.',$e);
    }
  }

  protected function contarConectado(RelAvisoOrgaoDTO $objRelAvisoOrgaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_aviso_orgao_listar', __METHOD__, $objRelAvisoOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAvisoOrgaoBD = new RelAvisoOrgaoBD($this->getObjInfraIBanco());
      $ret = $objRelAvisoOrgaoBD->contar($objRelAvisoOrgaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Órgãos do Aviso.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelAvisoOrgaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_aviso_orgao_desativar', __METHOD__, $arrObjRelAvisoOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAvisoOrgaoBD = new RelAvisoOrgaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAvisoOrgaoDTO);$i++){
        $objRelAvisoOrgaoBD->desativar($arrObjRelAvisoOrgaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Órgão do Aviso.',$e);
    }
  }

  protected function reativarControlado($arrObjRelAvisoOrgaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_aviso_orgao_reativar', __METHOD__, $arrObjRelAvisoOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAvisoOrgaoBD = new RelAvisoOrgaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAvisoOrgaoDTO);$i++){
        $objRelAvisoOrgaoBD->reativar($arrObjRelAvisoOrgaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Órgão do Aviso.',$e);
    }
  }

  protected function bloquearControlado(RelAvisoOrgaoDTO $objRelAvisoOrgaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_aviso_orgao_consultar', __METHOD__, $objRelAvisoOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAvisoOrgaoBD = new RelAvisoOrgaoBD($this->getObjInfraIBanco());
      $ret = $objRelAvisoOrgaoBD->bloquear($objRelAvisoOrgaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Órgão do Aviso.',$e);
    }
  }

 */
}
