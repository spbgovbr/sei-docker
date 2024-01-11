<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/11/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelAssinanteUnidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUnidadeRN1374(RelAssinanteUnidadeDTO $objRelAssinanteUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelAssinanteUnidadeDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarNumIdAssinanteRN1375(RelAssinanteUnidadeDTO $objRelAssinanteUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelAssinanteUnidadeDTO->getNumIdAssinante())){
      $objInfraException->adicionarValidacao('Assinante não informado.');
    }
  }

  protected function cadastrarRN1376Controlado(RelAssinanteUnidadeDTO $objRelAssinanteUnidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_assinante_unidade_cadastrar',__METHOD__,$objRelAssinanteUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidadeRN1374($objRelAssinanteUnidadeDTO, $objInfraException);
      $this->validarNumIdAssinanteRN1375($objRelAssinanteUnidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelAssinanteUnidadeBD = new RelAssinanteUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelAssinanteUnidadeBD->cadastrar($objRelAssinanteUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Unidade de Assinatura.',$e);
    }
  }

  protected function alterarRN1377Controlado(RelAssinanteUnidadeDTO $objRelAssinanteUnidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_assinante_unidade_alterar',__METHOD__,$objRelAssinanteUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelAssinanteUnidadeDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidadeRN1374($objRelAssinanteUnidadeDTO, $objInfraException);
      }
      if ($objRelAssinanteUnidadeDTO->isSetNumIdAssinante()){
        $this->validarNumIdAssinanteRN1375($objRelAssinanteUnidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelAssinanteUnidadeBD = new RelAssinanteUnidadeBD($this->getObjInfraIBanco());
      $objRelAssinanteUnidadeBD->alterar($objRelAssinanteUnidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Unidade de Assinatura.',$e);
    }
  }

  protected function excluirRN1378Controlado($arrObjRelAssinanteUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_assinante_unidade_excluir',__METHOD__,$arrObjRelAssinanteUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAssinanteUnidadeBD = new RelAssinanteUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAssinanteUnidadeDTO);$i++){
        $objRelAssinanteUnidadeBD->excluir($arrObjRelAssinanteUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Unidade de Assinatura.',$e);
    }
  }

  protected function consultarRN1379Conectado(RelAssinanteUnidadeDTO $objRelAssinanteUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_assinante_unidade_consultar',__METHOD__,$objRelAssinanteUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAssinanteUnidadeBD = new RelAssinanteUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelAssinanteUnidadeBD->consultar($objRelAssinanteUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Unidade de Assinatura.',$e);
    }
  }

  protected function listarRN1380Conectado(RelAssinanteUnidadeDTO $objRelAssinanteUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_assinante_unidade_listar',__METHOD__,$objRelAssinanteUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAssinanteUnidadeBD = new RelAssinanteUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelAssinanteUnidadeBD->listar($objRelAssinanteUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Unidades de Assinatura.',$e);
    }
  }

  protected function contarRN1381Conectado(RelAssinanteUnidadeDTO $objRelAssinanteUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_assinante_unidade_listar',__METHOD__,$objRelAssinanteUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAssinanteUnidadeBD = new RelAssinanteUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelAssinanteUnidadeBD->contar($objRelAssinanteUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Unidades de Assinatura.',$e);
    }
  }
/* 
  protected function desativarRN1382Controlado($arrObjRelAssinanteUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_assinante_unidade_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAssinanteUnidadeBD = new RelAssinanteUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAssinanteUnidadeDTO);$i++){
        $objRelAssinanteUnidadeBD->desativar($arrObjRelAssinanteUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Unidade de Assinatura.',$e);
    }
  }

  protected function reativarRN1383Controlado($arrObjRelAssinanteUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_assinante_unidade_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAssinanteUnidadeBD = new RelAssinanteUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelAssinanteUnidadeDTO);$i++){
        $objRelAssinanteUnidadeBD->reativar($arrObjRelAssinanteUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Unidade de Assinatura.',$e);
    }
  }

  protected function bloquearRN1384Controlado(RelAssinanteUnidadeDTO $objRelAssinanteUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_assinante_unidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelAssinanteUnidadeBD = new RelAssinanteUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelAssinanteUnidadeBD->bloquear($objRelAssinanteUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Unidade de Assinatura.',$e);
    }
  }

 */
}
?>