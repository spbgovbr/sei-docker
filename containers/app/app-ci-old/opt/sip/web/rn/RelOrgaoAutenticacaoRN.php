<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/06/2014 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../Sip.php';

class RelOrgaoAutenticacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  private function validarNumIdOrgao(RelOrgaoAutenticacaoDTO $objRelOrgaoAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelOrgaoAutenticacaoDTO->getNumIdOrgao())){
      $objInfraException->adicionarValidacao('Órgão não informado.');
    }
  }

  private function validarNumIdServidorAutenticacao(RelOrgaoAutenticacaoDTO $objRelOrgaoAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelOrgaoAutenticacaoDTO->getNumIdServidorAutenticacao())){
      $objInfraException->adicionarValidacao('Servidor de Autenticação não informado.');
    }
  }

  private function validarNumSequencia(RelOrgaoAutenticacaoDTO $objRelOrgaoAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelOrgaoAutenticacaoDTO->getNumSequencia())){
      $objInfraException->adicionarValidacao('Sequência não informada.');
    }
  }

  protected function cadastrarControlado(RelOrgaoAutenticacaoDTO $objRelOrgaoAutenticacaoDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_orgao_autenticacao_cadastrar',__METHOD__,$objRelOrgaoAutenticacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdOrgao($objRelOrgaoAutenticacaoDTO, $objInfraException);
      $this->validarNumIdServidorAutenticacao($objRelOrgaoAutenticacaoDTO, $objInfraException);
      $this->validarNumSequencia($objRelOrgaoAutenticacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD($this->getObjInfraIBanco());
      $ret = $objRelOrgaoAutenticacaoBD->cadastrar($objRelOrgaoAutenticacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Servidor de Autenticação do Órgão.',$e);
    }
  }

  protected function alterarControlado(RelOrgaoAutenticacaoDTO $objRelOrgaoAutenticacaoDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('rel_orgao_autenticacao_alterar',__METHOD__,$objRelOrgaoAutenticacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelOrgaoAutenticacaoDTO->isSetNumIdOrgao()){
        $this->validarNumIdOrgao($objRelOrgaoAutenticacaoDTO, $objInfraException);
      }
      if ($objRelOrgaoAutenticacaoDTO->isSetNumIdServidorAutenticacao()){
        $this->validarNumIdServidorAutenticacao($objRelOrgaoAutenticacaoDTO, $objInfraException);
      }
      if ($objRelOrgaoAutenticacaoDTO->isSetNumSequencia()){
        $this->validarNumSequencia($objRelOrgaoAutenticacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD($this->getObjInfraIBanco());
      $objRelOrgaoAutenticacaoBD->alterar($objRelOrgaoAutenticacaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Servidor de Autenticação do Órgão.',$e);
    }
  }

  protected function excluirControlado($arrObjRelOrgaoAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_orgao_autenticacao_excluir',__METHOD__,$arrObjRelOrgaoAutenticacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelOrgaoAutenticacaoDTO);$i++){
        $objRelOrgaoAutenticacaoBD->excluir($arrObjRelOrgaoAutenticacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Servidor de Autenticação do Órgão.',$e);
    }
  }

  protected function consultarConectado(RelOrgaoAutenticacaoDTO $objRelOrgaoAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_orgao_autenticacao_consultar',__METHOD__,$objRelOrgaoAutenticacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD($this->getObjInfraIBanco());
      $ret = $objRelOrgaoAutenticacaoBD->consultar($objRelOrgaoAutenticacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Servidor de Autenticação do Órgão.',$e);
    }
  }

  protected function listarConectado(RelOrgaoAutenticacaoDTO $objRelOrgaoAutenticacaoDTO) {
    try {

      /////////////////////////////////////////////////////////////////////////////////////
      //Valida Permissao
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_orgao_autenticacao_listar',__METHOD__,$objRelOrgaoAutenticacaoDTO);
      /////////////////////////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD($this->getObjInfraIBanco());
      $ret = $objRelOrgaoAutenticacaoBD->listar($objRelOrgaoAutenticacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Servidores de Autenticação do Órgão.',$e);
    }
  }

  protected function contarConectado(RelOrgaoAutenticacaoDTO $objRelOrgaoAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_orgao_autenticacao_listar',__METHOD__,$objRelOrgaoAutenticacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD($this->getObjInfraIBanco());
      $ret = $objRelOrgaoAutenticacaoBD->contar($objRelOrgaoAutenticacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Servidores de Autenticação do Órgão.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelOrgaoAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_orgao_autenticacao_desativar',__METHOD__,$arrObjRelOrgaoAutenticacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelOrgaoAutenticacaoDTO);$i++){
        $objRelOrgaoAutenticacaoBD->desativar($arrObjRelOrgaoAutenticacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Servidor de Autenticação do Órgão.',$e);
    }
  }

  protected function reativarControlado($arrObjRelOrgaoAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_orgao_autenticacao_reativar',__METHOD__,$arrObjRelOrgaoAutenticacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelOrgaoAutenticacaoDTO);$i++){
        $objRelOrgaoAutenticacaoBD->reativar($arrObjRelOrgaoAutenticacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Servidor de Autenticação do Órgão.',$e);
    }
  }

  protected function bloquearControlado(RelOrgaoAutenticacaoDTO $objRelOrgaoAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_orgao_autenticacao_consultar',__METHOD__,$objRelOrgaoAutenticacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD($this->getObjInfraIBanco());
      $ret = $objRelOrgaoAutenticacaoBD->bloquear($objRelOrgaoAutenticacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Servidor de Autenticação do Órgão.',$e);
    }
  }

 */
}
?>