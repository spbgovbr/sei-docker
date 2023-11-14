<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/07/2021 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class EditalEliminacaoErroRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdEditalEliminacaoConteudo(EditalEliminacaoErroDTO $objEditalEliminacaoErroDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoErroDTO->getNumIdEditalEliminacaoConteudo())){
      $objInfraException->adicionarValidacao('Processo não informado.');
    }
  }

  private function validarDthErro(EditalEliminacaoErroDTO $objEditalEliminacaoErroDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoErroDTO->getDthErro())){
      $objInfraException->adicionarValidacao('Data/Hora não informada.');
    }else{
      if (!InfraData::validarDataHora($objEditalEliminacaoErroDTO->getDthErro())){
        $objInfraException->adicionarValidacao('Data/Hora inválida.');
      }
    }
  }

  private function validarStrTextoErro(EditalEliminacaoErroDTO $objEditalEliminacaoErroDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoErroDTO->getStrTextoErro())){
      $objInfraException->adicionarValidacao('Texto não informado.');
    }else{
      $objEditalEliminacaoErroDTO->setStrTextoErro(trim($objEditalEliminacaoErroDTO->getStrTextoErro()));

      if (strlen($objEditalEliminacaoErroDTO->getStrTextoErro())>4000){
        $objInfraException->adicionarValidacao('Texto possui tamanho superior a 4000 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(EditalEliminacaoErroDTO $objEditalEliminacaoErroDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_erro_cadastrar', __METHOD__, $objEditalEliminacaoErroDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdEditalEliminacaoConteudo($objEditalEliminacaoErroDTO, $objInfraException);
      $this->validarDthErro($objEditalEliminacaoErroDTO, $objInfraException);
      $this->validarStrTextoErro($objEditalEliminacaoErroDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objEditalEliminacaoErroBD = new EditalEliminacaoErroBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoErroBD->cadastrar($objEditalEliminacaoErroDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Erro de Eliminação.',$e);
    }
  }

  protected function alterarControlado(EditalEliminacaoErroDTO $objEditalEliminacaoErroDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_erro_alterar', __METHOD__, $objEditalEliminacaoErroDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objEditalEliminacaoErroDTO->isSetNumIdEditalEliminacaoConteudo()){
        $this->validarNumIdEditalEliminacaoConteudo($objEditalEliminacaoErroDTO, $objInfraException);
      }
      if ($objEditalEliminacaoErroDTO->isSetDthErro()){
        $this->validarDthErro($objEditalEliminacaoErroDTO, $objInfraException);
      }
      if ($objEditalEliminacaoErroDTO->isSetStrTextoErro()){
        $this->validarStrTextoErro($objEditalEliminacaoErroDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objEditalEliminacaoErroBD = new EditalEliminacaoErroBD($this->getObjInfraIBanco());
      $objEditalEliminacaoErroBD->alterar($objEditalEliminacaoErroDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Erro de Eliminação.',$e);
    }
  }

  protected function excluirControlado($arrObjEditalEliminacaoErroDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_erro_excluir', __METHOD__, $arrObjEditalEliminacaoErroDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoErroBD = new EditalEliminacaoErroBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEditalEliminacaoErroDTO);$i++){
        $objEditalEliminacaoErroBD->excluir($arrObjEditalEliminacaoErroDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Erro de Eliminação.',$e);
    }
  }

  protected function consultarConectado(EditalEliminacaoErroDTO $objEditalEliminacaoErroDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_erro_consultar', __METHOD__, $objEditalEliminacaoErroDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoErroBD = new EditalEliminacaoErroBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoErroBD->consultar($objEditalEliminacaoErroDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Erro de Eliminação.',$e);
    }
  }

  protected function listarConectado(EditalEliminacaoErroDTO $objEditalEliminacaoErroDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_erro_listar', __METHOD__, $objEditalEliminacaoErroDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoErroBD = new EditalEliminacaoErroBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoErroBD->listar($objEditalEliminacaoErroDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Erros de Eliminação.',$e);
    }
  }

  protected function contarConectado(EditalEliminacaoErroDTO $objEditalEliminacaoErroDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_erro_listar', __METHOD__, $objEditalEliminacaoErroDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoErroBD = new EditalEliminacaoErroBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoErroBD->contar($objEditalEliminacaoErroDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Erros de Eliminação.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjEditalEliminacaoErroDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_erro_desativar', __METHOD__, $arrObjEditalEliminacaoErroDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoErroBD = new EditalEliminacaoErroBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEditalEliminacaoErroDTO);$i++){
        $objEditalEliminacaoErroBD->desativar($arrObjEditalEliminacaoErroDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Erro de Eliminação.',$e);
    }
  }

  protected function reativarControlado($arrObjEditalEliminacaoErroDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_erro_reativar', __METHOD__, $arrObjEditalEliminacaoErroDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoErroBD = new EditalEliminacaoErroBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEditalEliminacaoErroDTO);$i++){
        $objEditalEliminacaoErroBD->reativar($arrObjEditalEliminacaoErroDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Erro de Eliminação.',$e);
    }
  }

  protected function bloquearControlado(EditalEliminacaoErroDTO $objEditalEliminacaoErroDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_erro_consultar', __METHOD__, $objEditalEliminacaoErroDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoErroBD = new EditalEliminacaoErroBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoErroBD->bloquear($objEditalEliminacaoErroDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Erro de Eliminação.',$e);
    }
  }

 */
}
