<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/06/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class UnidadeFederacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrIdUnidadeFederacao(UnidadeFederacaoDTO $objUnidadeFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeFederacaoDTO->getStrIdUnidadeFederacao())){
      $objInfraException->adicionarValidacao('Identificador do SEI Federação não informado.');
    }else {

      if (!InfraULID::validar($objUnidadeFederacaoDTO->getStrIdUnidadeFederacao())){
        $objInfraException->lancarValidacao('Identificador do SEI Federação '.$objUnidadeFederacaoDTO->getStrIdUnidadeFederacao().' inválido.');
      }

      $dto = new UnidadeFederacaoDTO();
      $dto->retStrIdUnidadeFederacao();
      $dto->setNumMaxRegistrosRetorno(1);
      $dto->setBolExclusaoLogica(false);
      $dto->setStrIdUnidadeFederacao($objUnidadeFederacaoDTO->getStrIdUnidadeFederacao());
      if ($this->consultar($dto) != null) {
        $objInfraException->adicionarValidacao('Já existe uma Unidade cadastrada nesta instalação com o identificador '.$objUnidadeFederacaoDTO->getStrIdUnidadeFederacao().' do SEI Federação.');
      }
    }
  }
  
  private function validarStrIdInstalacaoFederacao(UnidadeFederacaoDTO $objUnidadeFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeFederacaoDTO->getStrIdInstalacaoFederacao())){
      $objInfraException->adicionarValidacao('Instalação não informada.');
    }
  }

  private function validarStrSigla(UnidadeFederacaoDTO $objUnidadeFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeFederacaoDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }else{
      $objUnidadeFederacaoDTO->setStrSigla(trim($objUnidadeFederacaoDTO->getStrSigla()));

      if (strlen($objUnidadeFederacaoDTO->getStrSigla())>30){
        $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrDescricao(UnidadeFederacaoDTO $objUnidadeFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeFederacaoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objUnidadeFederacaoDTO->setStrDescricao(trim($objUnidadeFederacaoDTO->getStrDescricao()));

      if (strlen($objUnidadeFederacaoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(UnidadeFederacaoDTO $objUnidadeFederacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_federacao_cadastrar', __METHOD__, $objUnidadeFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrIdUnidadeFederacao($objUnidadeFederacaoDTO, $objInfraException);
      $this->validarStrIdInstalacaoFederacao($objUnidadeFederacaoDTO, $objInfraException);
      $this->validarStrSigla($objUnidadeFederacaoDTO, $objInfraException);
      $this->validarStrDescricao($objUnidadeFederacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objUnidadeFederacaoBD = new UnidadeFederacaoBD($this->getObjInfraIBanco());
      $ret = $objUnidadeFederacaoBD->cadastrar($objUnidadeFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Unidade do SEI Federação.',$e);
    }
  }

  protected function alterarControlado(UnidadeFederacaoDTO $objUnidadeFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_federacao_alterar', __METHOD__, $objUnidadeFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objUnidadeFederacaoDTO->isSetStrIdInstalacaoFederacao()){
        $this->validarStrIdInstalacaoFederacao($objUnidadeFederacaoDTO, $objInfraException);
      }
      if ($objUnidadeFederacaoDTO->isSetStrSigla()){
        $this->validarStrSigla($objUnidadeFederacaoDTO, $objInfraException);
      }
      if ($objUnidadeFederacaoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objUnidadeFederacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objUnidadeFederacaoBD = new UnidadeFederacaoBD($this->getObjInfraIBanco());
      $objUnidadeFederacaoBD->alterar($objUnidadeFederacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Unidade do SEI Federação.',$e);
    }
  }

  protected function excluirControlado($arrObjUnidadeFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_federacao_excluir', __METHOD__, $arrObjUnidadeFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeFederacaoBD = new UnidadeFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjUnidadeFederacaoDTO);$i++){
        $objUnidadeFederacaoBD->excluir($arrObjUnidadeFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Unidade do SEI Federação.',$e);
    }
  }

  protected function consultarConectado(UnidadeFederacaoDTO $objUnidadeFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_federacao_consultar', __METHOD__, $objUnidadeFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeFederacaoBD = new UnidadeFederacaoBD($this->getObjInfraIBanco());
      $ret = $objUnidadeFederacaoBD->consultar($objUnidadeFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Unidade do SEI Federação.',$e);
    }
  }

  protected function listarConectado(UnidadeFederacaoDTO $objUnidadeFederacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_federacao_listar', __METHOD__, $objUnidadeFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeFederacaoBD = new UnidadeFederacaoBD($this->getObjInfraIBanco());
      $ret = $objUnidadeFederacaoBD->listar($objUnidadeFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Unidades do SEI Federação.',$e);
    }
  }

  protected function contarConectado(UnidadeFederacaoDTO $objUnidadeFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_federacao_listar', __METHOD__, $objUnidadeFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeFederacaoBD = new UnidadeFederacaoBD($this->getObjInfraIBanco());
      $ret = $objUnidadeFederacaoBD->contar($objUnidadeFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Unidades do SEI Federação.',$e);
    }
  }

  protected function desativarControlado($arrObjUnidadeFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_federacao_desativar', __METHOD__, $arrObjUnidadeFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeFederacaoBD = new UnidadeFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjUnidadeFederacaoDTO);$i++){
        $objUnidadeFederacaoBD->desativar($arrObjUnidadeFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Unidade do SEI Federação.',$e);
    }
  }

  protected function reativarControlado($arrObjUnidadeFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_federacao_reativar', __METHOD__, $arrObjUnidadeFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeFederacaoBD = new UnidadeFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjUnidadeFederacaoDTO);$i++){
        $objUnidadeFederacaoBD->reativar($arrObjUnidadeFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Unidade do SEI Federação.',$e);
    }
  }

  protected function bloquearControlado(UnidadeFederacaoDTO $objUnidadeFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_federacao_consultar', __METHOD__, $objUnidadeFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeFederacaoBD = new UnidadeFederacaoBD($this->getObjInfraIBanco());
      $ret = $objUnidadeFederacaoBD->bloquear($objUnidadeFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Unidade do SEI Federação.',$e);
    }
  }

  protected function sincronizarControlado(UnidadeFederacaoDTO $parObjUnidadeFederacaoDTO){
    try{

      $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
      $objUnidadeFederacaoDTO->setBolExclusaoLogica(false);
      $objUnidadeFederacaoDTO->retStrIdUnidadeFederacao();
      $objUnidadeFederacaoDTO->retStrSigla();
      $objUnidadeFederacaoDTO->retStrDescricao();
      $objUnidadeFederacaoDTO->setStrIdUnidadeFederacao($parObjUnidadeFederacaoDTO->getStrIdUnidadeFederacao());

      $objUnidadeFederacaoDTO = $this->consultar($objUnidadeFederacaoDTO);

      if ($objUnidadeFederacaoDTO == null){

        $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
        $objUnidadeFederacaoDTO->setStrIdUnidadeFederacao($parObjUnidadeFederacaoDTO->getStrIdUnidadeFederacao());
        $objUnidadeFederacaoDTO->setStrIdInstalacaoFederacao($parObjUnidadeFederacaoDTO->getStrIdInstalacaoFederacao());
        $objUnidadeFederacaoDTO->setStrSigla($parObjUnidadeFederacaoDTO->getStrSigla());
        $objUnidadeFederacaoDTO->setStrDescricao($parObjUnidadeFederacaoDTO->getStrDescricao());
        $this->cadastrar($objUnidadeFederacaoDTO);

      }else{

        if ($objUnidadeFederacaoDTO->getStrSigla()!=$parObjUnidadeFederacaoDTO->getStrSigla() || $objUnidadeFederacaoDTO->getStrDescricao()!=$parObjUnidadeFederacaoDTO->getStrDescricao()){
          $objUnidadeFederacaoDTO->setStrSigla($parObjUnidadeFederacaoDTO->getStrSigla());
          $objUnidadeFederacaoDTO->setStrDescricao($parObjUnidadeFederacaoDTO->getStrDescricao());
          $this->alterar($objUnidadeFederacaoDTO);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro sincronizando unidade do SEI Federação.',$e);
    }
  }
}
