<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/05/2008 - criado por mga
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class DominioRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0581Controlado(DominioDTO $objDominioDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('dominio_cadastrar',__METHOD__,$objDominioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdAtributoRN0587($objDominioDTO, $objInfraException);
      $this->validarStrValorRN0588($objDominioDTO, $objInfraException);
      $this->validarStrRotulo($objDominioDTO, $objInfraException);
      $this->validarNumOrdem($objDominioDTO, $objInfraException);
      $this->validarStrSinPadrao($objDominioDTO, $objInfraException);
      $this->validarStrSinAtivoRN0589($objDominioDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objDominioBD = new DominioBD($this->getObjInfraIBanco());
      $ret = $objDominioBD->cadastrar($objDominioDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Valor.',$e);
    }
  }

  protected function alterarRN0582Controlado(DominioDTO $objDominioDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('dominio_alterar',__METHOD__,$objDominioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objDominioDTOBanco = new DominioDTO();
      $objDominioDTOBanco->setBolExclusaoLogica(false);
      $objDominioDTOBanco->retNumIdAtributo();
      $objDominioDTOBanco->retStrValor();
      //$objDominioDTOBanco->retStrSinAtivo();
      $objDominioDTOBanco->setNumIdDominio($objDominioDTO->getNumIdDominio());
      $objDominioDTOBanco = $this->consultarRN0583($objDominioDTOBanco);

      if ($objDominioDTO->isSetNumIdAtributo() && $objDominioDTO->getNumIdAtributo()!=$objDominioDTOBanco->getNumIdAtributo()){
        $objInfraException->lancarValidacao('Não é possível alterar o atributo associado com um valor.');
      }else{
        $objDominioDTO->setNumIdAtributo($objDominioDTOBanco->getNumIdAtributo());
      }

      //if ($objDominioDTO->isSetStrSinAtivo() && $objDominioDTO->getStrSinAtivo()!=$objDominioDTOBanco->getStrSinAtivo()){

      //}

      if ($objDominioDTO->isSetStrValor()){

        $this->validarStrValorRN0588($objDominioDTO, $objInfraException);

        if ($objDominioDTO->getStrValor()!=$objDominioDTOBanco->getStrValor()){

          $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
          $objRelProtocoloAtributoDTO->setNumIdAtributo($objDominioDTOBanco->getNumIdAtributo());
          $objRelProtocoloAtributoDTO->setStrValor($objDominioDTOBanco->getStrValor());

          $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
          if ($objRelProtocoloAtributoRN->contar($objRelProtocoloAtributoDTO)){
            $objInfraException->adicionarValidacao('Existem documentos associados com o valor "'.$objDominioDTOBanco->getStrValor().'".');
          }
        }
      }

      if ($objDominioDTO->isSetStrRotulo()){
        $this->validarStrRotulo($objDominioDTO, $objInfraException);
      }

      if ($objDominioDTO->isSetNumOrdem()){
        $this->validarNumOrdem($objDominioDTO, $objInfraException);
      }

      if ($objDominioDTO->isSetStrSinPadrao()){
        $this->validarStrSinPadrao($objDominioDTO, $objInfraException);
      }

      if ($objDominioDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivoRN0589($objDominioDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objDominioBD = new DominioBD($this->getObjInfraIBanco());
      $objDominioBD->alterar($objDominioDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Valor.',$e);
    }
  }

  protected function excluirRN0595Controlado($arrObjDominioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('dominio_excluir',__METHOD__,$arrObjDominioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();

      foreach ($arrObjDominioDTO as $objDominioDTO) {

        $dto = new DominioDTO();
        $dto->setBolExclusaoLogica(false);
        $dto->retNumIdAtributo();
        $dto->retStrValor();
        $dto->setNumIdDominio($objDominioDTO->getNumIdDominio());
        $dto = $this->consultarRN0583($dto);

        $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
        $objRelProtocoloAtributoDTO->setNumIdAtributo($dto->getNumIdAtributo());
        $objRelProtocoloAtributoDTO->setStrValor($dto->getStrValor());

        if ($objRelProtocoloAtributoRN->contar($objRelProtocoloAtributoDTO) > 0) {
          $objInfraException->adicionarValidacao('Existem protocolos utilizando o valor "' . $dto->getStrValor() . '".');
        }
      }

      $objInfraException->lancarValidacoes();

      $objDominioBD = new DominioBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjDominioDTO); $i++) {
        $objDominioBD->excluir($arrObjDominioDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Valor.',$e);
    }
  }

  protected function consultarRN0583Conectado(DominioDTO $objDominioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('dominio_consultar',__METHOD__,$objDominioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDominioBD = new DominioBD($this->getObjInfraIBanco());
      $ret = $objDominioBD->consultar($objDominioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Valor.',$e);
    }
  }

  protected function listarRN0199Conectado(DominioDTO $objDominioDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('dominio_listar',__METHOD__,$objDominioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDominioBD = new DominioBD($this->getObjInfraIBanco());
      $ret = $objDominioBD->listar($objDominioDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Valores.',$e);
    }
  }

  protected function contarRN0584Conectado(DominioDTO $objDominioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('dominio_listar',__METHOD__,$objDominioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDominioBD = new DominioBD($this->getObjInfraIBanco());
      $ret = $objDominioBD->contar($objDominioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Valores.',$e);
    }
  }

  protected function desativarControlado($arrObjDominioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('dominio_desativar',__METHOD__,$arrObjDominioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDominioBD = new DominioBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjDominioDTO);$i++){
        $objDominioBD->desativar($arrObjDominioDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Valor.',$e);
    }
  }

  protected function reativarRN0586Controlado($arrObjDominioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('dominio_reativar',__METHOD__,$arrObjDominioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDominioBD = new DominioBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjDominioDTO);$i++){
        $objDominioBD->reativar($arrObjDominioDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Valor.',$e);
    }
  }

  private function validarNumIdAtributoRN0587(DominioDTO $objDominioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDominioDTO->getNumIdAtributo())){
      $objInfraException->adicionarValidacao('Atributo não informado.');
    }

    $objAtributoDTO = new AtributoDTO();
    $objAtributoDTO->setBolExclusaoLogica(false);
    $objAtributoDTO->retStrStaTipo();
    $objAtributoDTO->setNumIdAtributo($objDominioDTO->getNumIdAtributo());

    $objAtributoRN = new AtributoRN();
    $objAtributoDTO = $objAtributoRN->consultarRN0115($objAtributoDTO);
    if ($objAtributoDTO->getStrStaTipo()!=AtributoRN::$TA_LISTA && $objAtributoDTO->getStrStaTipo()!=AtributoRN::$TA_OPCOES){
      $objInfraException->adicionarValidacao('Atributo não permite valores associados.');
    }
  }

  private function validarStrValorRN0588(DominioDTO $objDominioDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objDominioDTO->getStrValor())){
      $objInfraException->adicionarValidacao('Valor não informado.');
    }else{
      $objDominioDTO->setStrValor(trim($objDominioDTO->getStrValor()));
  
      if (strlen($objDominioDTO->getStrValor())>50){
        $objInfraException->adicionarValidacao('Valor possui tamanho superior a 50 caracteres.');
      }

      $dto = new DominioDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retNumIdAtributo();
      $dto->retStrSinAtivo();
      $dto->setNumIdDominio($objDominioDTO->getNumIdDominio(),InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdAtributo($objDominioDTO->getNumIdAtributo());
      $dto->setStrValor($objDominioDTO->getStrValor());

      $dto = $this->consultarRN0583($dto);
      
  		if ($dto!=null){
      	if($dto->getStrSinAtivo()=='S'){
      		$objInfraException->adicionarValidacao('Existe outra ocorrência que utiliza o mesmo Valor.');
      	}else{
      		$objInfraException->adicionarValidacao('Existe ocorrência inativa que utiliza o mesmo Valor.');
      	}
      }    
    }
  }

  private function validarStrRotulo(DominioDTO $objDominioDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objDominioDTO->getStrRotulo())){
      $objInfraException->adicionarValidacao('Rótulo não informado.');
    }else{
      $objDominioDTO->setStrRotulo(trim($objDominioDTO->getStrRotulo()));

      if (strlen($objDominioDTO->getStrRotulo())>100){
        $objInfraException->adicionarValidacao('Rótulo possui tamanho superior a 100 caracteres.');
      }

      $dto = new DominioDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retNumIdAtributo();
      $dto->retStrSinAtivo();
      $dto->setNumIdDominio($objDominioDTO->getNumIdDominio(),InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdAtributo($objDominioDTO->getNumIdAtributo());
      $dto->setStrRotulo($objDominioDTO->getStrRotulo());

      $dto = $this->consultarRN0583($dto);

      if ($dto!=null){
        if($dto->getStrSinAtivo()=='S'){
          $objInfraException->adicionarValidacao('Existe outro valor utilizando o mesmo rótulo.');
        }else{
          $objInfraException->adicionarValidacao('Existe valor inativo que utiliza o mesmo rótulo.');
        }
      }
    }
  }

  private function validarNumOrdem(DominioDTO $objDominioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDominioDTO->getNumOrdem())){
      $objInfraException->adicionarValidacao('Ordem não informada.');
    }
  }

  private function validarStrSinAtivoRN0589(DominioDTO $objDominioDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objDominioDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objDominioDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  private function validarStrSinPadrao(DominioDTO $objDominioDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objDominioDTO->getStrSinPadrao())){
      $objInfraException->adicionarValidacao('Sinalizador de Valor Padrão não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objDominioDTO->getStrSinPadrao())){
        $objInfraException->adicionarValidacao('Sinalizador de Valor Padrão inválido.');
      }
    }
  }
}
?>