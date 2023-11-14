<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class VocativoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0307Controlado(VocativoDTO $objVocativoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('vocativo_cadastrar',__METHOD__,$objVocativoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrExpressaoRN0314($objVocativoDTO, $objInfraException);
      $this->validarStrSinAtivoRN0351($objVocativoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objVocativoBD = new VocativoBD($this->getObjInfraIBanco());
      $ret = $objVocativoBD->cadastrar($objVocativoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Vocativo.',$e);
    }
  }

  protected function alterarRN0308Controlado(VocativoDTO $objVocativoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('vocativo_alterar',__METHOD__,$objVocativoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objVocativoDTO->isSetStrExpressao()){
        $this->validarStrExpressaoRN0314($objVocativoDTO, $objInfraException);
      }
      if ($objVocativoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivoRN0351($objVocativoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objVocativoBD = new VocativoBD($this->getObjInfraIBanco());
      $objVocativoBD->alterar($objVocativoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Vocativo.',$e);
    }
  }

  protected function excluirRN0311Controlado($arrObjVocativoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('vocativo_excluir',__METHOD__,$arrObjVocativoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

			$objCargoRN = new CargoRN();
      $objCargoDTO = new CargoDTO();
      $objCargoDTO->setBolExclusaoLogica(false);
      $objCargoDTO->retNumIdCargo();
      $objCargoDTO->setNumMaxRegistrosRetorno(1);

      for ($i=0;$i<count($arrObjVocativoDTO);$i++){
        $objCargoDTO->setNumIdVocativo($arrObjVocativoDTO[$i]->getNumIdVocativo());

        $objCargoDTO->setStrSinAtivo('S');
      	if ($objCargoRN->consultarRN0301($objCargoDTO)!=null){
      		$objInfraException->lancarValidacao('Existem cargos utilizando este vocativo.');
      	}

        $objCargoDTO->setStrSinAtivo('N');
 	      if ($objCargoRN->consultarRN0301($objCargoDTO)!=null){
          $objInfraException->lancarValidacao('Existem cargos inativos utilizando este vocativo.');
 	      }
      }

      $objVocativoBD = new VocativoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjVocativoDTO);$i++){
        $objVocativoBD->excluir($arrObjVocativoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Vocativo.',$e);
    }
  }

  protected function consultarRN0309Conectado(VocativoDTO $objVocativoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('vocativo_consultar',__METHOD__,$objVocativoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVocativoBD = new VocativoBD($this->getObjInfraIBanco());
      $ret = $objVocativoBD->consultar($objVocativoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Vocativo.',$e);
    }
  }

  protected function listarRN0310Conectado(VocativoDTO $objVocativoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('vocativo_listar',__METHOD__,$objVocativoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVocativoBD = new VocativoBD($this->getObjInfraIBanco());
      $ret = $objVocativoBD->listar($objVocativoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Vocativos.',$e);
    }
  }

  protected function contarRN0312Conectado(VocativoDTO $objVocativoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('vocativo_listar',__METHOD__,$objVocativoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVocativoBD = new VocativoBD($this->getObjInfraIBanco());
      $ret = $objVocativoBD->contar($objVocativoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Vocativos.',$e);
    }
  }

  protected function desativarRN0347Controlado($arrObjVocativoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('vocativo_desativar',__METHOD__,$arrObjVocativoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVocativoBD = new VocativoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjVocativoDTO);$i++){
        $objVocativoBD->desativar($arrObjVocativoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Vocativo.',$e);
    }
  }

  protected function reativarRN0348Controlado($arrObjVocativoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('vocativo_reativar',__METHOD__,$arrObjVocativoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objVocativoBD = new VocativoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjVocativoDTO);$i++){
        $objVocativoBD->reativar($arrObjVocativoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Vocativo.',$e);
    }
  }

  private function validarStrExpressaoRN0314(VocativoDTO $objVocativoDTO, InfraException $objInfraException){
    
  	if (InfraString::isBolVazia($objVocativoDTO->getStrExpressao())){
      $objInfraException->adicionarValidacao('Expressão não informada.');
    }else{
    	$objVocativoDTO->setStrExpressao(trim($objVocativoDTO->getStrExpressao()));
      
      if (strlen($objVocativoDTO->getStrExpressao())>100){
        $objInfraException->adicionarValidacao('Expressão possui tamanho superior a 100 caracteres.');
      }
      
      $dto = new VocativoDTO();
      $dto->retStrSinAtivo();
      $dto->setNumIdVocativo($objVocativoDTO->getNumIdVocativo(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrExpressao($objVocativoDTO->getStrExpressao(),InfraDTO::$OPER_IGUAL);
      $dto->setBolExclusaoLogica(false);
          
      $dto = $this->consultarRN0309($dto);
      if ($dto != NULL){
        if ($dto->getStrSinAtivo() == 'S')
          $objInfraException->adicionarValidacao('Existe outra ocorrência de Vocativo que utiliza a mesma Expressão.');    	
        else
          $objInfraException->adicionarValidacao('Existe ocorrência inativa de Vocativo que utiliza a mesma Expressão.');    	
      }
    }
  }

  private function validarStrSinAtivoRN0351(VocativoDTO $objVocativoDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objVocativoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
    
      if (!InfraUtil::isBolSinalizadorValido($objVocativoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }
}
?>