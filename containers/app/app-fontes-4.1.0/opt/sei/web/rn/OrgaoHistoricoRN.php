<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class OrgaoHistoricoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdOrgao(OrgaoHistoricoDTO $objOrgaoHistoricoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoHistoricoDTO->getNumIdOrgao())){
      $objInfraException->adicionarValidacao('Órgão não informado.');
    }
  }

  private function validarStrSigla(OrgaoHistoricoDTO $objOrgaoHistoricoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoHistoricoDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }else{
      $objOrgaoHistoricoDTO->setStrSigla(trim($objOrgaoHistoricoDTO->getStrSigla()));

      if (strlen($objOrgaoHistoricoDTO->getStrSigla())>30){
        $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrDescricao(OrgaoHistoricoDTO $objOrgaoHistoricoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoHistoricoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objOrgaoHistoricoDTO->setStrDescricao(trim($objOrgaoHistoricoDTO->getStrDescricao()));

      if (strlen($objOrgaoHistoricoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarDtaInicio(OrgaoHistoricoDTO $objOrgaoHistoricoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoHistoricoDTO->getDtaInicio())){
      $objInfraException->adicionarValidacao('Data Inicial não informada.');
    }else{
      if (!InfraData::validarData($objOrgaoHistoricoDTO->getDtaInicio())){
        $objInfraException->adicionarValidacao('Data Inicial inválida.');
      }
    }
  }

  private function validarDtaFim(OrgaoHistoricoDTO $objOrgaoHistoricoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoHistoricoDTO->getDtaFim())){
      if($objOrgaoHistoricoDTO->isSetBolOrigemSIP() && $objOrgaoHistoricoDTO->getBolOrigemSIP()){
        $objOrgaoHistoricoDTO->setDtaFim(null);
      }else{
        $objInfraException->adicionarValidacao('Data Final não informada.');
      }
    }else if (!InfraString::isBolVazia($objOrgaoHistoricoDTO->getDtaInicio()) && InfraData::compararDatasSimples($objOrgaoHistoricoDTO->getDtaInicio(), $objOrgaoHistoricoDTO->getDtaFim()) == -1){
      $objInfraException->adicionarValidacao('Data Final deve ser anterior à Data Inicial.');
    }else if (InfraData::compararDatasSimples($objOrgaoHistoricoDTO->getDtaFim(), InfraData::getStrDataAtual()) <= 0){
      $objInfraException->adicionarValidacao('Data Final deve ser anterior à hoje.');
    }else{
      if (!InfraData::validarData($objOrgaoHistoricoDTO->getDtaFim())){
        $objInfraException->adicionarValidacao('Data Final inválida.');
      }
    }
  }


  protected function cadastrarControlado(OrgaoHistoricoDTO $objOrgaoHistoricoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_historico_cadastrar',__METHOD__,$objOrgaoHistoricoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdOrgao($objOrgaoHistoricoDTO, $objInfraException);
      $this->validarStrSigla($objOrgaoHistoricoDTO, $objInfraException);
      $this->validarStrDescricao($objOrgaoHistoricoDTO, $objInfraException);
      $this->validarDtaInicio($objOrgaoHistoricoDTO, $objInfraException);
      $this->validarDtaFim($objOrgaoHistoricoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objHistoricoRN = new HistoricoRN();
      $ret = $objHistoricoRN->tratarHistoricoInclusao($objOrgaoHistoricoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Histórico do Órgão.',$e);
    }
  }

  protected function alterarControlado(OrgaoHistoricoDTO $objOrgaoHistoricoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_historico_alterar',__METHOD__,$objOrgaoHistoricoDTO);


      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objOrgaoHistoricoDTO->isSetNumIdOrgao()){
        $this->validarNumIdOrgao($objOrgaoHistoricoDTO, $objInfraException);
      }
      if ($objOrgaoHistoricoDTO->isSetStrSigla()){
        $this->validarStrSigla($objOrgaoHistoricoDTO, $objInfraException);
      }
      if ($objOrgaoHistoricoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objOrgaoHistoricoDTO, $objInfraException);
      }
      if ($objOrgaoHistoricoDTO->isSetDtaInicio()){
        $this->validarDtaInicio($objOrgaoHistoricoDTO, $objInfraException);
      }
      if ($objOrgaoHistoricoDTO->isSetDtaFim()){
        $this->validarDtaFim($objOrgaoHistoricoDTO, $objInfraException);
      }
      $objInfraException->lancarValidacoes();

      $objHistoricoRN = new HistoricoRN();
      $objHistoricoRN->tratarHistoricoAlteracao($objOrgaoHistoricoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Histórico do Órgão.',$e);
    }
  }

  protected function excluirControlado($arrObjOrgaoHistoricoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_historico_excluir',__METHOD__,$arrObjOrgaoHistoricoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoHistoricoBD = new OrgaoHistoricoBD($this->getObjInfraIBanco());
      $objHistoricoRN = new HistoricoRN();
      for($i=0;$i<count($arrObjOrgaoHistoricoDTO);$i++){
        $objHistoricoRN->tratarHistoricoExclusao($arrObjOrgaoHistoricoDTO[$i]);
        $objOrgaoHistoricoBD->excluir($arrObjOrgaoHistoricoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Histórico do Órgão.',$e);
    }
  }

  protected function consultarConectado(OrgaoHistoricoDTO $objOrgaoHistoricoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_historico_consultar',__METHOD__,$objOrgaoHistoricoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoHistoricoBD = new OrgaoHistoricoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoHistoricoBD->consultar($objOrgaoHistoricoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Histórico do Órgão.',$e);
    }
  }

  protected function listarConectado(OrgaoHistoricoDTO $objOrgaoHistoricoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_historico_listar',__METHOD__,$objOrgaoHistoricoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoHistoricoBD = new OrgaoHistoricoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoHistoricoBD->listar($objOrgaoHistoricoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Históricos dos Órgãos.',$e);
    }
  }

  protected function contarConectado(OrgaoHistoricoDTO $objOrgaoHistoricoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_historico_listar',__METHOD__,$objOrgaoHistoricoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoHistoricoBD = new OrgaoHistoricoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoHistoricoBD->contar($objOrgaoHistoricoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Históricos dos Órgãos.',$e);
    }
  }
/*
  protected function desativarControlado($arrObjOrgaoHistoricoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_historico_desativar',__METHOD__,$arrObjOrgaoHistoricoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoHistoricoBD = new OrgaoHistoricoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOrgaoHistoricoDTO);$i++){
        $objOrgaoHistoricoBD->desativar($arrObjOrgaoHistoricoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Histórico do Órgão.',$e);
    }
  }

  protected function reativarControlado($arrObjOrgaoHistoricoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_historico_reativar',__METHOD__,$arrObjOrgaoHistoricoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoHistoricoBD = new OrgaoHistoricoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOrgaoHistoricoDTO);$i++){
        $objOrgaoHistoricoBD->reativar($arrObjOrgaoHistoricoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Histórico do Órgão.',$e);
    }
  }

  protected function bloquearControlado(OrgaoHistoricoDTO $objOrgaoHistoricoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_historico_consultar',__METHOD__,$objOrgaoHistoricoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoHistoricoBD = new OrgaoHistoricoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoHistoricoBD->bloquear($objOrgaoHistoricoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Histórico do Órgão.',$e);
    }
  }

 */
}
