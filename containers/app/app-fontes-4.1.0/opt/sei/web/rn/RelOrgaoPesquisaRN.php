<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/09/2022 - criado por cas84
*
* Versão do Gerador de Código: 1.43.1
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelOrgaoPesquisaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarControlado(RelOrgaoPesquisaDTO $objRelOrgaoPesquisaDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_orgao_pesquisa_cadastrar',__METHOD__,$objRelOrgaoPesquisaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();


      $objInfraException->lancarValidacoes();

      $objRelOrgaoPesquisaBD = new RelOrgaoPesquisaBD($this->getObjInfraIBanco());
      $ret = $objRelOrgaoPesquisaBD->cadastrar($objRelOrgaoPesquisaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(RelOrgaoPesquisaDTO $objRelOrgaoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_orgao_pesquisa_alterar',__METHOD__,$objRelOrgaoPesquisaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();


      $objInfraException->lancarValidacoes();

      $objRelOrgaoPesquisaBD = new RelOrgaoPesquisaBD($this->getObjInfraIBanco());
      $objRelOrgaoPesquisaBD->alterar($objRelOrgaoPesquisaDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjRelOrgaoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_orgao_pesquisa_excluir',__METHOD__,$arrObjRelOrgaoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoPesquisaBD = new RelOrgaoPesquisaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelOrgaoPesquisaDTO);$i++){
        $objRelOrgaoPesquisaBD->excluir($arrObjRelOrgaoPesquisaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(RelOrgaoPesquisaDTO $objRelOrgaoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_orgao_pesquisa_consultar',__METHOD__,$objRelOrgaoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoPesquisaBD = new RelOrgaoPesquisaBD($this->getObjInfraIBanco());
      $ret = $objRelOrgaoPesquisaBD->consultar($objRelOrgaoPesquisaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(RelOrgaoPesquisaDTO $objRelOrgaoPesquisaDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_orgao_pesquisa_listar',__METHOD__,$objRelOrgaoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoPesquisaBD = new RelOrgaoPesquisaBD($this->getObjInfraIBanco());
      $ret = $objRelOrgaoPesquisaBD->listar($objRelOrgaoPesquisaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(RelOrgaoPesquisaDTO $objRelOrgaoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_orgao_pesquisa_listar',__METHOD__,$objRelOrgaoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoPesquisaBD = new RelOrgaoPesquisaBD($this->getObjInfraIBanco());
      $ret = $objRelOrgaoPesquisaBD->contar($objRelOrgaoPesquisaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelOrgaoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_orgao_pesquisa_desativar',__METHOD__,$arrObjRelOrgaoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoPesquisaBD = new RelOrgaoPesquisaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelOrgaoPesquisaDTO);$i++){
        $objRelOrgaoPesquisaBD->desativar($arrObjRelOrgaoPesquisaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjRelOrgaoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_orgao_pesquisa_reativar',__METHOD__,$arrObjRelOrgaoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoPesquisaBD = new RelOrgaoPesquisaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelOrgaoPesquisaDTO);$i++){
        $objRelOrgaoPesquisaBD->reativar($arrObjRelOrgaoPesquisaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(RelOrgaoPesquisaDTO $objRelOrgaoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_orgao_pesquisa_consultar',__METHOD__,$objRelOrgaoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoPesquisaBD = new RelOrgaoPesquisaBD($this->getObjInfraIBanco());
      $ret = $objRelOrgaoPesquisaBD->bloquear($objRelOrgaoPesquisaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
