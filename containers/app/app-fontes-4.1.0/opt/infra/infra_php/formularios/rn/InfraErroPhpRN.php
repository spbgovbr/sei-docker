<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/03/2023 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.2
*/

//require_once dirname(__FILE__).'/../Infra.php';

class InfraErroPhpRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoInfra::getInstance();
  }

  public function listarValoresTipo(){
    try {

      $arrObjInfraErroPhpTipoDTO = array();

        //Attempting to read an undefined variable.
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_UNDEFINED_VARIABLE);
        $objInfraErroPhpTipoDTO->setStrDescricao('Lendo variável não definida');
        $objInfraErroPhpTipoDTO->setStrErro('Undefined variable');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;

        //Attempting to read an undefined property.
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_UNDEFINED_PROPERTY);
        $objInfraErroPhpTipoDTO->setStrDescricao('Lendo propriedade não definida');
        $objInfraErroPhpTipoDTO->setStrErro('Undefined property');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;

        //Attempting to read an undefined array key.
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_UNDEFINED_ARRAY_KEY);
        $objInfraErroPhpTipoDTO->setStrDescricao('Lendo posição de array não definida');
        $objInfraErroPhpTipoDTO->setStrErro('Undefined array key');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;

        //Attempting to read a property of a non-object.
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_ATTEMPT_TO_READ_PROPERTY);
        $objInfraErroPhpTipoDTO->setStrDescricao('Lendo propriedade em variável que não é um objeto');
        $objInfraErroPhpTipoDTO->setStrErro('Attempt to read property');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;

        //Attempting to access an array index of a non-array.
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_TRYING_TO_ACCESS_ARRAY_OFFSET);
        $objInfraErroPhpTipoDTO->setStrDescricao('Acessando posição em variável que não é um array');
        $objInfraErroPhpTipoDTO->setStrErro('Trying to access array offset');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;

        //Attempting to convert an array to string.
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_ARRAY_TO_STRING_CONVERSION);
        $objInfraErroPhpTipoDTO->setStrDescricao('Conversão de array para string');
        $objInfraErroPhpTipoDTO->setStrErro('Array to string conversion');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;

        //Attempting to use a resource as an array key.
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_RESOURCE_USED_AS_OFFSET_CASTING_TO_INTEGER);
        $objInfraErroPhpTipoDTO->setStrDescricao('Usando recurso como posição para um array');
        $objInfraErroPhpTipoDTO->setStrErro('used as offset, casting to integer');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;
        //Resource ID#157 used as offset, casting to integer

        //Attempting to use null, a boolean, or a float as a string offset.
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_STRING_OFFSET_CAST_OCCURRED);
        $objInfraErroPhpTipoDTO->setStrDescricao('Usando null, boolean ou float como posição para um array');
        $objInfraErroPhpTipoDTO->setStrErro('String offset cast occurred');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;

        //Attempting to read an out-of-bounds string offset.
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_UNINITIALIZED_STRING_OFFSET);
        $objInfraErroPhpTipoDTO->setStrDescricao('Lendo posição não definida em string');
        $objInfraErroPhpTipoDTO->setStrErro('Uninitialized string offset');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;

        //Attempting to assign an empty string to a string offset.
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_CANNOT_ACCESS_OFFSET_OF_TYPE_STRING_ON_STRING);
        $objInfraErroPhpTipoDTO->setStrDescricao('Acessando posição vazia ou não numérica em string');
        $objInfraErroPhpTipoDTO->setStrErro('Cannot access offset of type string on string');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;

        //must be passed by reference
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_ARGUMENT_MUST_BE_PASSED_BY_REFERENCE);
        $objInfraErroPhpTipoDTO->setStrDescricao('Passando parâmetro por valor no lugar de referência');
        $objInfraErroPhpTipoDTO->setStrErro('must be passed by reference, value given');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;

        //A non-numeric value encountered
        $objInfraErroPhpTipoDTO = new InfraErroPhpTipoDTO();
        $objInfraErroPhpTipoDTO->setNumStaTipo(InfraErroPHP::$W_A_NON_NUMERIC_VALUE_ENCOUNTERED);
        $objInfraErroPhpTipoDTO->setStrDescricao('Encontrado valor não numérico');
        $objInfraErroPhpTipoDTO->setStrErro('A non-numeric value encountered');
        $arrObjInfraErroPhpTipoDTO[] = $objInfraErroPhpTipoDTO;


      return $arrObjInfraErroPhpTipoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Tipo.',$e);
    }
  }

  private function validarNumStaTipo(InfraErroPhpDTO $objInfraErroPhpDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraErroPhpDTO->getNumStaTipo())){
      $objInfraException->adicionarValidacao('Tipo não informado.');
    }else{
      if (!in_array($objInfraErroPhpDTO->getNumStaTipo(),InfraArray::converterArrInfraDTO($this->listarValoresTipo(),'StaTipo'))){
        $objInfraException->adicionarValidacao('Tipo inválido.');
      }
    }
  }

  private function validarStrArquivo(InfraErroPhpDTO $objInfraErroPhpDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraErroPhpDTO->getStrArquivo())){
      $objInfraException->adicionarValidacao('Arquivo não informado.');
    }else{
      $objInfraErroPhpDTO->setStrArquivo(trim($objInfraErroPhpDTO->getStrArquivo()));

      if (strlen($objInfraErroPhpDTO->getStrArquivo())>255){
        $objInfraException->adicionarValidacao('Arquivo possui tamanho superior a 255 caracteres.');
      }
    }
  }

  private function validarNumLinha(InfraErroPhpDTO $objInfraErroPhpDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraErroPhpDTO->getNumLinha())){
      $objInfraException->adicionarValidacao('Linha não informada.');
    }
  }

  private function validarStrErro(InfraErroPhpDTO $objInfraErroPhpDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraErroPhpDTO->getStrErro())){
      $objInfraException->adicionarValidacao('Erro não informado.');
    }else{
      $objInfraErroPhpDTO->setStrErro(substr(trim($objInfraErroPhpDTO->getStrErro()),0,4000));
    }
  }

  private function validarDthCadastro(InfraErroPhpDTO $objInfraErroPhpDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraErroPhpDTO->getDthCadastro())){
      $objInfraException->adicionarValidacao('Data/Hora não informada.');
    }else{
      if (!InfraData::validarDataHora($objInfraErroPhpDTO->getDthCadastro())){
        $objInfraException->adicionarValidacao('Data/Hora inválida.');
      }
    }
  }

    protected function registrarControlado(InfraErroPhpDTO $parObjInfraErroPhpDTO)
    {
        try {
            //SessaoInfra::getInstance()->validarAuditarPermissao('infra_erro_php_registrar', __METHOD__, $objInfraCaptchaDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();
            $this->validarNumStaTipo($parObjInfraErroPhpDTO, $objInfraException);
            $this->validarStrArquivo($parObjInfraErroPhpDTO, $objInfraException);
            $this->validarNumLinha($parObjInfraErroPhpDTO, $objInfraException);
            $this->validarStrErro($parObjInfraErroPhpDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $strId = md5($parObjInfraErroPhpDTO->getNumStaTipo().'-'.$parObjInfraErroPhpDTO->getStrArquivo().'-'.$parObjInfraErroPhpDTO->getNumLinha());

            $objInfraErroPhpDTO = new InfraErroPhpDTO();
            $objInfraErroPhpDTO->retStrIdInfraErroPhp();
            $objInfraErroPhpDTO->setStrIdInfraErroPhp($strId);

            if ($this->consultar($objInfraErroPhpDTO)==null){
                $parObjInfraErroPhpDTO->setStrIdInfraErroPhp($strId);
                $parObjInfraErroPhpDTO->setDthCadastro(InfraData::getStrDataHoraAtual());
                $this->cadastrar($parObjInfraErroPhpDTO);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro registrando Acesso Captcha.', $e);
        }
    }

  protected function cadastrarControlado(InfraErroPhpDTO $objInfraErroPhpDTO) {
    try{

      //SessaoInfra::getInstance()->validarPermissao('infra_erro_php_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumStaTipo($objInfraErroPhpDTO, $objInfraException);
      $this->validarStrArquivo($objInfraErroPhpDTO, $objInfraException);
      $this->validarNumLinha($objInfraErroPhpDTO, $objInfraException);
      $this->validarStrErro($objInfraErroPhpDTO, $objInfraException);
      $this->validarDthCadastro($objInfraErroPhpDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objInfraErroPhpBD = new InfraErroPhpBD($this->getObjInfraIBanco());
      $ret = $objInfraErroPhpBD->cadastrar($objInfraErroPhpDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Erro do PHP.',$e);
    }
  }

  protected function alterarControlado(InfraErroPhpDTO $objInfraErroPhpDTO){
    try {

      SessaoInfra::getInstance()->validarPermissao('infra_erro_php_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objInfraErroPhpDTO->isSetNumStaTipo()){
        $this->validarNumStaTipo($objInfraErroPhpDTO, $objInfraException);
      }
      if ($objInfraErroPhpDTO->isSetStrArquivo()){
        $this->validarStrArquivo($objInfraErroPhpDTO, $objInfraException);
      }
      if ($objInfraErroPhpDTO->isSetNumLinha()){
        $this->validarNumLinha($objInfraErroPhpDTO, $objInfraException);
      }
      if ($objInfraErroPhpDTO->isSetStrErro()){
        $this->validarStrErro($objInfraErroPhpDTO, $objInfraException);
      }
      if ($objInfraErroPhpDTO->isSetDthCadastro()){
        $this->validarDthCadastro($objInfraErroPhpDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objInfraErroPhpBD = new InfraErroPhpBD($this->getObjInfraIBanco());
      $objInfraErroPhpBD->alterar($objInfraErroPhpDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Erro do PHP.',$e);
    }
  }

  protected function excluirControlado($arrObjInfraErroPhpDTO){
    try {

      SessaoInfra::getInstance()->validarPermissao('infra_erro_php_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraErroPhpBD = new InfraErroPhpBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraErroPhpDTO);$i++){
        $objInfraErroPhpBD->excluir($arrObjInfraErroPhpDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Erro do PHP.',$e);
    }
  }

  protected function consultarConectado(InfraErroPhpDTO $objInfraErroPhpDTO){
    try {

      //SessaoInfra::getInstance()->validarPermissao('infra_erro_php_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraErroPhpBD = new InfraErroPhpBD($this->getObjInfraIBanco());

      /** @var InfraErroPhpDTO $ret */
      $ret = $objInfraErroPhpBD->consultar($objInfraErroPhpDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Erro do PHP.',$e);
    }
  }

  protected function listarConectado(InfraErroPhpDTO $objInfraErroPhpDTO) {
    try {

      //SessaoInfra::getInstance()->validarPermissao('infra_erro_php_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraErroPhpBD = new InfraErroPhpBD($this->getObjInfraIBanco());

      /** @var InfraErroPhpDTO[] $ret */
      $ret = $objInfraErroPhpBD->listar($objInfraErroPhpDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Erros do PHP.',$e);
    }
  }

  protected function contarConectado(InfraErroPhpDTO $objInfraErroPhpDTO){
    try {

      SessaoInfra::getInstance()->validarPermissao('infra_erro_php_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraErroPhpBD = new InfraErroPhpBD($this->getObjInfraIBanco());
      $ret = $objInfraErroPhpBD->contar($objInfraErroPhpDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Erros do PHP.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjInfraErroPhpDTO){
    try {

      SessaoInfra::getInstance()->validarPermissao('infra_erro_php_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraErroPhpBD = new InfraErroPhpBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraErroPhpDTO);$i++){
        $objInfraErroPhpBD->desativar($arrObjInfraErroPhpDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Erro do PHP.',$e);
    }
  }

  protected function reativarControlado($arrObjInfraErroPhpDTO){
    try {

      SessaoInfra::getInstance()->validarPermissao('infra_erro_php_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraErroPhpBD = new InfraErroPhpBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraErroPhpDTO);$i++){
        $objInfraErroPhpBD->reativar($arrObjInfraErroPhpDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Erro do PHP.',$e);
    }
  }

  protected function bloquearControlado(InfraErroPhpDTO $objInfraErroPhpDTO){
    try {

      SessaoInfra::getInstance()->validarPermissao('infra_erro_php_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraErroPhpBD = new InfraErroPhpBD($this->getObjInfraIBanco());
      $ret = $objInfraErroPhpBD->bloquear($objInfraErroPhpDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Erro do PHP.',$e);
    }
  }

 */
}
