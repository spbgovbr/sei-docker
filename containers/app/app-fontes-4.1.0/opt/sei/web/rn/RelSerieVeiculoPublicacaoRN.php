<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/07/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelSerieVeiculoPublicacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdSerie(RelSerieVeiculoPublicacaoDTO $objRelSerieVeiculoPublicacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelSerieVeiculoPublicacaoDTO->getNumIdSerie())){
      $objInfraException->adicionarValidacao('Tipo do documento não informado.');
    }
  }

  private function validarNumIdVeiculoPublicacao(RelSerieVeiculoPublicacaoDTO $objRelSerieVeiculoPublicacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelSerieVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao())){
      $objInfraException->adicionarValidacao('Veículo de Publicação não informado.');
    }
  }

  protected function cadastrarControlado(RelSerieVeiculoPublicacaoDTO $objRelSerieVeiculoPublicacaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_veiculo_publicacao_cadastrar',__METHOD__,$objRelSerieVeiculoPublicacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSerie($objRelSerieVeiculoPublicacaoDTO, $objInfraException);
      $this->validarNumIdVeiculoPublicacao($objRelSerieVeiculoPublicacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelSerieVeiculoPublicacaoBD = new RelSerieVeiculoPublicacaoBD($this->getObjInfraIBanco());
      $ret = $objRelSerieVeiculoPublicacaoBD->cadastrar($objRelSerieVeiculoPublicacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro associando Tipo de Documento com Veículo de Publicação.',$e);
    }
  }

  protected function alterarControlado(RelSerieVeiculoPublicacaoDTO $objRelSerieVeiculoPublicacaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_veiculo_publicacao_alterar',__METHOD__,$objRelSerieVeiculoPublicacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelSerieVeiculoPublicacaoDTO->isSetNumIdSerie()){
        $this->validarNumIdSerie($objRelSerieVeiculoPublicacaoDTO, $objInfraException);
      }
      if ($objRelSerieVeiculoPublicacaoDTO->isSetNumIdVeiculoPublicacao()){
        $this->validarNumIdVeiculoPublicacao($objRelSerieVeiculoPublicacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelSerieVeiculoPublicacaoBD = new RelSerieVeiculoPublicacaoBD($this->getObjInfraIBanco());
      $objRelSerieVeiculoPublicacaoBD->alterar($objRelSerieVeiculoPublicacaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando associação de Tipo de Documento com Veículo de Publicação.',$e);
    }
  }

  protected function excluirControlado($arrObjRelSerieVeiculoPublicacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_veiculo_publicacao_excluir',__METHOD__,$arrObjRelSerieVeiculoPublicacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSerieVeiculoPublicacaoBD = new RelSerieVeiculoPublicacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSerieVeiculoPublicacaoDTO);$i++){
        $objRelSerieVeiculoPublicacaoBD->excluir($arrObjRelSerieVeiculoPublicacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo associação de Tipo de Documento com Veículo de Publicação.',$e);
    }
  }

  protected function consultarConectado(RelSerieVeiculoPublicacaoDTO $objRelSerieVeiculoPublicacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_veiculo_publicacao_consultar',__METHOD__,$objRelSerieVeiculoPublicacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSerieVeiculoPublicacaoBD = new RelSerieVeiculoPublicacaoBD($this->getObjInfraIBanco());
      $ret = $objRelSerieVeiculoPublicacaoBD->consultar($objRelSerieVeiculoPublicacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando associação de Tipo de Documento com Veículo de Publicação.',$e);
    }
  }

  protected function listarConectado(RelSerieVeiculoPublicacaoDTO $objRelSerieVeiculoPublicacaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_veiculo_publicacao_listar',__METHOD__,$objRelSerieVeiculoPublicacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSerieVeiculoPublicacaoBD = new RelSerieVeiculoPublicacaoBD($this->getObjInfraIBanco());
      $ret = $objRelSerieVeiculoPublicacaoBD->listar($objRelSerieVeiculoPublicacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando associações de Tipos de Documento com Veículos de Publicação.',$e);
    }
  }

  protected function contarConectado(RelSerieVeiculoPublicacaoDTO $objRelSerieVeiculoPublicacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_veiculo_publicacao_listar',__METHOD__,$objRelSerieVeiculoPublicacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSerieVeiculoPublicacaoBD = new RelSerieVeiculoPublicacaoBD($this->getObjInfraIBanco());
      $ret = $objRelSerieVeiculoPublicacaoBD->contar($objRelSerieVeiculoPublicacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando associações de Tipos de Documento com Veículos de Publicação.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelSerieVeiculoPublicacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_veiculo_publicacao_desativar',__METHOD__,$arrObjRelSerieVeiculoPublicacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSerieVeiculoPublicacaoBD = new RelSerieVeiculoPublicacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSerieVeiculoPublicacaoDTO);$i++){
        $objRelSerieVeiculoPublicacaoBD->desativar($arrObjRelSerieVeiculoPublicacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando associação de Tipo de Documento com Veículo de Publicação.',$e);
    }
  }

  protected function reativarControlado($arrObjRelSerieVeiculoPublicacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_veiculo_publicacao_reativar',__METHOD__,$arrObjRelSerieVeiculoPublicacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSerieVeiculoPublicacaoBD = new RelSerieVeiculoPublicacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelSerieVeiculoPublicacaoDTO);$i++){
        $objRelSerieVeiculoPublicacaoBD->reativar($arrObjRelSerieVeiculoPublicacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando associação de Tipo de Documento com Veículo de Publicação.',$e);
    }
  }

  protected function bloquearControlado(RelSerieVeiculoPublicacaoDTO $objRelSerieVeiculoPublicacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_veiculo_publicacao_consultar',__METHOD__,$objRelSerieVeiculoPublicacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSerieVeiculoPublicacaoBD = new RelSerieVeiculoPublicacaoBD($this->getObjInfraIBanco());
      $ret = $objRelSerieVeiculoPublicacaoBD->bloquear($objRelSerieVeiculoPublicacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando associação de Tipo de Documento com Veículo de Publicação.',$e);
    }
  }

 */
}
?>