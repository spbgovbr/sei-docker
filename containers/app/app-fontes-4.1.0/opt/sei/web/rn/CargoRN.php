<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class CargoRN extends InfraRN {
  
  public static $TG_MASCULINO = 'M';
  public static $TG_FEMININO = 'F';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0299Controlado(CargoDTO $objCargoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cargo_cadastrar',__METHOD__,$objCargoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrExpressaoRN0306($objCargoDTO, $objInfraException);
      $this->validarNumIdTratamento($objCargoDTO, $objInfraException);
      $this->validarNumIdTitulo($objCargoDTO, $objInfraException);
      $this->validarNumIdVocativo($objCargoDTO, $objInfraException);
      $this->validarStrStaGenero($objCargoDTO, $objInfraException);
      $this->validarStrSinAtivoRN0340($objCargoDTO, $objInfraException);
      $this->validarCargoUnico($objCargoDTO, $objInfraException);
      $objInfraException->lancarValidacoes();

      $objCargoBD = new CargoBD($this->getObjInfraIBanco());
      $ret = $objCargoBD->cadastrar($objCargoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Cargo.',$e);
    }
  }

  protected function alterarRN0300Controlado(CargoDTO $objCargoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('cargo_alterar',__METHOD__,$objCargoDTO);

      $objCargoDTOBanco = new CargoDTO();
      $objCargoDTOBanco->retStrExpressao();
      $objCargoDTOBanco->retStrStaGenero();
      $objCargoDTOBanco->retNumIdVocativo();
      $objCargoDTOBanco->retNumIdTratamento();
      $objCargoDTOBanco->retNumIdTitulo();
      $objCargoDTOBanco->setNumIdCargo($objCargoDTO->getNumIdCargo());

      $objCargoDTOBanco = $this->consultarRN0301($objCargoDTOBanco);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objCargoDTO->isSetStrExpressao()){
        $this->validarStrExpressaoRN0306($objCargoDTO, $objInfraException);
      }else{
        $objCargoDTO->setStrExpressao($objCargoDTOBanco->getStrExpressao());
      }

      if ($objCargoDTO->isSetNumIdTratamento()){
        $this->validarNumIdTratamento($objCargoDTO, $objInfraException);
      }else{
        $objCargoDTO->setNumIdTratamento($objCargoDTOBanco->getNumIdTratamento());
      }

      if ($objCargoDTO->isSetNumIdTitulo()){
        $this->validarNumIdTitulo($objCargoDTO, $objInfraException);
      }else{
        $objCargoDTO->setNumIdTitulo($objCargoDTOBanco->getNumIdTitulo());
      }

      if ($objCargoDTO->isSetNumIdVocativo()){
        $this->validarNumIdVocativo($objCargoDTO, $objInfraException);
      }else{
        $objCargoDTO->setNumIdVocativo($objCargoDTOBanco->getNumIdVocativo());
      }

      if ($objCargoDTO->isSetStrStaGenero()) {
        $this->validarStrStaGenero($objCargoDTO, $objInfraException);
      }else{
        $objCargoDTO->setStrStaGenero($objCargoDTOBanco->getStrStaGenero());
      }

      if ($objCargoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivoRN0340($objCargoDTO, $objInfraException);
      }

      $this->validarCargoUnico($objCargoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objCargoBD = new CargoBD($this->getObjInfraIBanco());
      $objCargoBD->alterar($objCargoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Cargo.',$e);
    }
  }

  protected function excluirRN0303Controlado($arrObjCargoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cargo_excluir',__METHOD__,$arrObjCargoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
			$objContatoRN = new ContatoRN();
      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setBolExclusaoLogica(false);
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->setNumMaxRegistrosRetorno(1);

      $objCpadComposicaoRN = new CpadComposicaoRN();

      for ($i=0;$i<count($arrObjCargoDTO);$i++){

      	$objContatoDTO->setNumIdCargo($arrObjCargoDTO[$i]->getNumIdCargo());
      	
      	$objContatoDTO->setStrSinAtivo('S');
      	if ($objContatoRN->consultarRN0324($objContatoDTO)!=null){
      		$objInfraException->lancarValidacao('Existem contatos utilizando este cargo.');
      	}
     	
 	      $objContatoDTO->setStrSinAtivo('N');
 	      if ($objContatoRN->consultarRN0324($objContatoDTO)!=null){
          $objInfraException->lancarValidacao('Existem contatos inativos utilizando este cargo.');
 	      }

        $objCpadComposicaoDTO = new CpadComposicaoDTO();
        $objCpadComposicaoDTO->setBolExclusaoLogica(false);
        $objCpadComposicaoDTO->setNumIdCargo($arrObjCargoDTO[$i]->getNumIdCargo());
        if($objCpadComposicaoRN->contar($objCpadComposicaoDTO) > 0){
          $objInfraException->lancarValidacao('Existem composições CPAD utilizando este cargo.');
        }

      }

      $objCargoBD = new CargoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCargoDTO);$i++){
        $objCargoBD->excluir($arrObjCargoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Cargo.',$e);
    }
  }

  protected function consultarRN0301Conectado(CargoDTO $objCargoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cargo_consultar',__METHOD__,$objCargoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCargoBD = new CargoBD($this->getObjInfraIBanco());
      $ret = $objCargoBD->consultar($objCargoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Cargo.',$e);
    }
  }

  protected function listarRN0302Conectado(CargoDTO $objCargoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cargo_listar',__METHOD__,$objCargoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCargoBD = new CargoBD($this->getObjInfraIBanco());
      $ret = $objCargoBD->listar($objCargoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Cargos.',$e);
    }
  }

  protected function contarRN0304Conectado(CargoDTO $objCargoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cargo_listar',__METHOD__,$objCargoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCargoBD = new CargoBD($this->getObjInfraIBanco());
      $ret = $objCargoBD->contar($objCargoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Cargos.',$e);
    }
  }
  
  protected function desativarRN0341Controlado($arrObjCargoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cargo_desativar',__METHOD__,$arrObjCargoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCargoBD = new CargoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCargoDTO);$i++){
        $objCargoBD->desativar($arrObjCargoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Cargo.',$e);
    }
  }

  protected function reativarRN0342Controlado($arrObjCargoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cargo_reativar',__METHOD__,$arrObjCargoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCargoBD = new CargoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCargoDTO);$i++){
        $objCargoBD->reativar($arrObjCargoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Cargo.',$e);
    }
  }

  private function validarStrExpressaoRN0306(CargoDTO $objCargoDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objCargoDTO->getStrExpressao())){
      $objInfraException->adicionarValidacao('Expressão não informada.');
    }else {

      //remover espaços em branco
      $objCargoDTO->setStrExpressao(trim($objCargoDTO->getStrExpressao()));


      if (strlen($objCargoDTO->getStrExpressao()) > 100) {
        $objInfraException->adicionarValidacao('Expressão possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarNumIdTratamento(CargoDTO $objCargoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCargoDTO->getNumIdTratamento())) {
      $objCargoDTO->setNumIdTratamento(null);
    }
  }

  private function validarNumIdTitulo(CargoDTO $objCargoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCargoDTO->getNumIdTitulo())) {
      $objCargoDTO->setNumIdTitulo(null);
    }
  }

  private function validarNumIdVocativo(CargoDTO $objCargoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCargoDTO->getNumIdVocativo())) {
      $objCargoDTO->setNumIdVocativo(null);
    }
  }

  private function validarStrSinAtivoRN0340(CargoDTO $objCargoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCargoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objCargoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  private function validarStrStaGenero(CargoDTO $objCargoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCargoDTO->getStrStaGenero())){
      $objCargoDTO->setStrStaGenero(null);
    }else{
      if ($objCargoDTO->getStrStaGenero()!=self::$TG_MASCULINO && $objCargoDTO->getStrStaGenero()!=self::$TG_FEMININO){
        $objInfraException->adicionarValidacao('Gênero inválido.');
      }
    }
  }

  private function validarCargoUnico(CargoDTO $objCargoDTO, InfraException $objInfraException){

    $dto = new CargoDTO();
    $dto->setBolExclusaoLogica(false);
    $dto->retStrSinAtivo();
    $dto->setNumIdCargo($objCargoDTO->getNumIdCargo(),InfraDTO::$OPER_DIFERENTE);
    $dto->setStrExpressao($objCargoDTO->getStrExpressao());
    $dto->setStrStaGenero($objCargoDTO->getStrStaGenero());

    $dto = $this->consultarRN0301($dto);
    if ($dto != NULL){
      if ($dto->getStrSinAtivo() == 'S')
        $objInfraException->adicionarValidacao('Existe outra ocorrência de Cargo para este Gênero que utiliza a mesma Expressão.');
      else
        $objInfraException->adicionarValidacao('Existe ocorrência inativa de Cargo para este Gênero que utiliza a mesma Expressão.');
    }else {

      $dto = new CargoDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSinAtivo();
      $dto->setNumIdCargo($objCargoDTO->getNumIdCargo(), InfraDTO::$OPER_DIFERENTE);
      $dto->setStrExpressao($objCargoDTO->getStrExpressao());
      $dto->setNumIdVocativo($objCargoDTO->getNumIdVocativo());
      $dto->setNumIdTratamento($objCargoDTO->getNumIdTratamento());
      $dto->setNumIdTitulo($objCargoDTO->getNumIdTratamento());

      $dto = $this->consultarRN0301($dto);
      if ($dto != NULL) {
        if ($dto->getStrSinAtivo() == 'S')
          $objInfraException->adicionarValidacao('Existe outra ocorrência de Cargo com a mesma Expressão, o mesmo Tratamento e Vocativo.');
        else
          $objInfraException->adicionarValidacao('Existe ocorrência inativa de Cargo com a mesma Expressão, o mesmo Tratamento e Vocativo.');
      }
    }
  }
}
?>