<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/07/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ParametroAcaoFederacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrIdAcaoFederacao(ParametroAcaoFederacaoDTO $objParametroAcaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objParametroAcaoFederacaoDTO->getStrIdAcaoFederacao())){
      $objInfraException->adicionarValidacao('Ação não informada.');
    }else{
      $objParametroAcaoFederacaoDTO->setStrIdAcaoFederacao(trim($objParametroAcaoFederacaoDTO->getStrIdAcaoFederacao()));

      if (!InfraULID::validar($objParametroAcaoFederacaoDTO->getStrIdAcaoFederacao())){
        $objInfraException->adicionarValidacao('Identificador da Ação do SEI Federação inválido.');
      }
    }
  }

  private function validarStrValor(ParametroAcaoFederacaoDTO $objParametroAcaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objParametroAcaoFederacaoDTO->getStrValor())){
      $objInfraException->adicionarValidacao('Valor não informado.');
    }else{
      $objParametroAcaoFederacaoDTO->setStrValor(trim($objParametroAcaoFederacaoDTO->getStrValor()));
    }
  }

  protected function cadastrarControlado(ParametroAcaoFederacaoDTO $objParametroAcaoFederacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('parametro_acao_federacao_cadastrar', __METHOD__, $objParametroAcaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrIdAcaoFederacao($objParametroAcaoFederacaoDTO, $objInfraException);
      $this->validarStrValor($objParametroAcaoFederacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objParametroAcaoFederacaoBD = new ParametroAcaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objParametroAcaoFederacaoBD->cadastrar($objParametroAcaoFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Parâmetro da Ação do SEI Federação.',$e);
    }
  }

  protected function alterarControlado(ParametroAcaoFederacaoDTO $objParametroAcaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('parametro_acao_federacao_alterar', __METHOD__, $objParametroAcaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objParametroAcaoFederacaoDTO->isSetStrIdAcaoFederacao()){
        $this->validarStrIdAcaoFederacao($objParametroAcaoFederacaoDTO, $objInfraException);
      }
      if ($objParametroAcaoFederacaoDTO->isSetStrValor()){
        $this->validarStrValor($objParametroAcaoFederacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objParametroAcaoFederacaoBD = new ParametroAcaoFederacaoBD($this->getObjInfraIBanco());
      $objParametroAcaoFederacaoBD->alterar($objParametroAcaoFederacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Parâmetro da Ação do SEI Federação.',$e);
    }
  }

  protected function excluirControlado($arrObjParametroAcaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('parametro_acao_federacao_excluir', __METHOD__, $arrObjParametroAcaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParametroAcaoFederacaoBD = new ParametroAcaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjParametroAcaoFederacaoDTO);$i++){
        $objParametroAcaoFederacaoBD->excluir($arrObjParametroAcaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Parâmetro da Ação do SEI Federação.',$e);
    }
  }

  protected function consultarConectado(ParametroAcaoFederacaoDTO $objParametroAcaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('parametro_acao_federacao_consultar', __METHOD__, $objParametroAcaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParametroAcaoFederacaoBD = new ParametroAcaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objParametroAcaoFederacaoBD->consultar($objParametroAcaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Parâmetro da Ação do SEI Federação.',$e);
    }
  }

  protected function listarConectado(ParametroAcaoFederacaoDTO $objParametroAcaoFederacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('parametro_acao_federacao_listar', __METHOD__, $objParametroAcaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParametroAcaoFederacaoBD = new ParametroAcaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objParametroAcaoFederacaoBD->listar($objParametroAcaoFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Parâmetros da Ação do SEI Federação.',$e);
    }
  }

  protected function contarConectado(ParametroAcaoFederacaoDTO $objParametroAcaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('parametro_acao_federacao_listar', __METHOD__, $objParametroAcaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParametroAcaoFederacaoBD = new ParametroAcaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objParametroAcaoFederacaoBD->contar($objParametroAcaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Parâmetros da Ação do SEI Federação.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjParametroAcaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('parametro_acao_federacao_desativar', __METHOD__, $arrObjParametroAcaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParametroAcaoFederacaoBD = new ParametroAcaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjParametroAcaoFederacaoDTO);$i++){
        $objParametroAcaoFederacaoBD->desativar($arrObjParametroAcaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Parâmetro da Ação do SEI Federação.',$e);
    }
  }

  protected function reativarControlado($arrObjParametroAcaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('parametro_acao_federacao_reativar', __METHOD__, $arrObjParametroAcaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParametroAcaoFederacaoBD = new ParametroAcaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjParametroAcaoFederacaoDTO);$i++){
        $objParametroAcaoFederacaoBD->reativar($arrObjParametroAcaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Parâmetro da Ação do SEI Federação.',$e);
    }
  }

  protected function bloquearControlado(ParametroAcaoFederacaoDTO $objParametroAcaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('parametro_acao_federacao_consultar', __METHOD__, $objParametroAcaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParametroAcaoFederacaoBD = new ParametroAcaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objParametroAcaoFederacaoBD->bloquear($objParametroAcaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Parâmetro da Ação do SEI Federação.',$e);
    }
  }

 */
}
