<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/05/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class OrgaoFederacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrIdOrgaoFederacao(OrgaoFederacaoDTO $objOrgaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoFederacaoDTO->getStrIdOrgaoFederacao())){
      $objInfraException->adicionarValidacao('Identificador do SEI Federação não informado.');
    }else {

      if (!InfraULID::validar($objOrgaoFederacaoDTO->getStrIdOrgaoFederacao())){
        $objInfraException->lancarValidacao('Identificador do SEI Federação '.$objOrgaoFederacaoDTO->getStrIdOrgaoFederacao().' inválido.');
      }

      $dto = new OrgaoFederacaoDTO();
      $dto->retStrIdOrgaoFederacao();
      $dto->setNumMaxRegistrosRetorno(1);
      $dto->setBolExclusaoLogica(false);
      $dto->setStrIdOrgaoFederacao($objOrgaoFederacaoDTO->getStrIdOrgaoFederacao());
      if ($this->consultar($dto) != null) {
        $objInfraException->adicionarValidacao('Já existe um Órgão cadastrado nesta instalação com o identificador '.$objOrgaoFederacaoDTO->getStrIdOrgaoFederacao().' do SEI Federação.');
      }
    }
  }

  private function validarStrIdInstalacaoFederacao(OrgaoFederacaoDTO $objOrgaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoFederacaoDTO->getStrIdInstalacaoFederacao())){
      $objInfraException->adicionarValidacao('Instalação não informada.');
    }
  }

  private function validarStrSigla(OrgaoFederacaoDTO $objOrgaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoFederacaoDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }else{
      $objOrgaoFederacaoDTO->setStrSigla(trim($objOrgaoFederacaoDTO->getStrSigla()));

      if (strlen($objOrgaoFederacaoDTO->getStrSigla())>30){
        $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrDescricao(OrgaoFederacaoDTO $objOrgaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoFederacaoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objOrgaoFederacaoDTO->setStrDescricao(trim($objOrgaoFederacaoDTO->getStrDescricao()));

      if (strlen($objOrgaoFederacaoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(OrgaoFederacaoDTO $objOrgaoFederacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_federacao_cadastrar', __METHOD__, $objOrgaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrIdOrgaoFederacao($objOrgaoFederacaoDTO, $objInfraException);
      $this->validarStrIdInstalacaoFederacao($objOrgaoFederacaoDTO, $objInfraException);
      $this->validarStrSigla($objOrgaoFederacaoDTO, $objInfraException);
      $this->validarStrDescricao($objOrgaoFederacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objOrgaoFederacaoBD = new OrgaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoFederacaoBD->cadastrar($objOrgaoFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Órgão do SEI Federação.',$e);
    }
  }

  protected function alterarControlado(OrgaoFederacaoDTO $objOrgaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_federacao_alterar', __METHOD__, $objOrgaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objOrgaoFederacaoDTO->isSetStrIdInstalacaoFederacao()){
        $this->validarStrIdInstalacaoFederacao($objOrgaoFederacaoDTO, $objInfraException);
      }
      if ($objOrgaoFederacaoDTO->isSetStrSigla()){
        $this->validarStrSigla($objOrgaoFederacaoDTO, $objInfraException);
      }
      if ($objOrgaoFederacaoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objOrgaoFederacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objOrgaoFederacaoBD = new OrgaoFederacaoBD($this->getObjInfraIBanco());
      $objOrgaoFederacaoBD->alterar($objOrgaoFederacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Órgão do SEI Federação.',$e);
    }
  }

  protected function excluirControlado($arrObjOrgaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_federacao_excluir', __METHOD__, $arrObjOrgaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoFederacaoBD = new OrgaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOrgaoFederacaoDTO);$i++){
        $objOrgaoFederacaoBD->excluir($arrObjOrgaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Órgão do SEI Federação.',$e);
    }
  }

  protected function consultarConectado(OrgaoFederacaoDTO $objOrgaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_federacao_consultar', __METHOD__, $objOrgaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoFederacaoBD = new OrgaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoFederacaoBD->consultar($objOrgaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Órgão do SEI Federação.',$e);
    }
  }

  protected function listarConectado(OrgaoFederacaoDTO $objOrgaoFederacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_federacao_listar', __METHOD__, $objOrgaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoFederacaoBD = new OrgaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoFederacaoBD->listar($objOrgaoFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Órgãos do SEI Federacao.',$e);
    }
  }

  protected function contarConectado(OrgaoFederacaoDTO $objOrgaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_federacao_listar', __METHOD__, $objOrgaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoFederacaoBD = new OrgaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoFederacaoBD->contar($objOrgaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Órgãos do SEI Federacao.',$e);
    }
  }

  protected function desativarControlado($arrObjOrgaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_federacao_desativar', __METHOD__, $arrObjOrgaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoFederacaoBD = new OrgaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOrgaoFederacaoDTO);$i++){
        $objOrgaoFederacaoBD->desativar($arrObjOrgaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Órgão do SEI Federação.',$e);
    }
  }

  protected function reativarControlado($arrObjOrgaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_federacao_reativar', __METHOD__, $arrObjOrgaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoFederacaoBD = new OrgaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOrgaoFederacaoDTO);$i++){
        $objOrgaoFederacaoBD->reativar($arrObjOrgaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Órgão do SEI Federação.',$e);
    }
  }

  protected function bloquearControlado(OrgaoFederacaoDTO $objOrgaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_federacao_consultar', __METHOD__, $objOrgaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoFederacaoBD = new OrgaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoFederacaoBD->bloquear($objOrgaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Órgão do SEI Federação.',$e);
    }
  }

  protected function sincronizarControlado(OrgaoFederacaoDTO $parObjOrgaoFederacaoDTO){
    try{

      $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
      $objOrgaoFederacaoDTO->setBolExclusaoLogica(false);
      $objOrgaoFederacaoDTO->retStrIdOrgaoFederacao();
      $objOrgaoFederacaoDTO->retStrSigla();
      $objOrgaoFederacaoDTO->retStrDescricao();
      $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($parObjOrgaoFederacaoDTO->getStrIdOrgaoFederacao());

      $objOrgaoFederacaoDTO = $this->consultar($objOrgaoFederacaoDTO);

      if ($objOrgaoFederacaoDTO == null){

        $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
        $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($parObjOrgaoFederacaoDTO->getStrIdOrgaoFederacao());
        $objOrgaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjOrgaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $objOrgaoFederacaoDTO->setStrSigla($parObjOrgaoFederacaoDTO->getStrSigla());
        $objOrgaoFederacaoDTO->setStrDescricao($parObjOrgaoFederacaoDTO->getStrDescricao());
        $this->cadastrar($objOrgaoFederacaoDTO);

      }else{

        if ($objOrgaoFederacaoDTO->getStrSigla()!=$parObjOrgaoFederacaoDTO->getStrSigla() || $objOrgaoFederacaoDTO->getStrDescricao()!=$parObjOrgaoFederacaoDTO->getStrDescricao()){
          $objOrgaoFederacaoDTO->setStrSigla($parObjOrgaoFederacaoDTO->getStrSigla());
          $objOrgaoFederacaoDTO->setStrDescricao($parObjOrgaoFederacaoDTO->getStrDescricao());
          $this->alterar($objOrgaoFederacaoDTO);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro sincronizando órgão do SEI Federação.',$e);
    }
  }

}
