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

class TratamentoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0315Controlado(TratamentoDTO $objTratamentoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tratamento_cadastrar',__METHOD__,$objTratamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrExpressaoRN0320($objTratamentoDTO, $objInfraException);
      $this->validarStrSinAtivoRN0350($objTratamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objTratamentoBD = new TratamentoBD($this->getObjInfraIBanco());
      $ret = $objTratamentoBD->cadastrar($objTratamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tratamento.',$e);
    }
  }

  protected function alterarRN0316Controlado(TratamentoDTO $objTratamentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('tratamento_alterar',__METHOD__,$objTratamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objTratamentoDTO->isSetStrExpressao()){
        $this->validarStrExpressaoRN0320($objTratamentoDTO, $objInfraException);
      }
      if ($objTratamentoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivoRN0350($objTratamentoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objTratamentoBD = new TratamentoBD($this->getObjInfraIBanco());
      $objTratamentoBD->alterar($objTratamentoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tratamento.',$e);
    }
  }

  protected function excluirRN0319Controlado($arrObjTratamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tratamento_excluir',__METHOD__,$arrObjTratamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

			$objCargoRN = new CargoRN();
      $objCargoDTO = new CargoDTO();
      $objCargoDTO->setBolExclusaoLogica(false);
      $objCargoDTO->retNumIdCargo();
      $objCargoDTO->setNumMaxRegistrosRetorno(1);

      for ($i=0;$i<count($arrObjTratamentoDTO);$i++){
        $objCargoDTO->setNumIdTratamento($arrObjTratamentoDTO[$i]->getNumIdTratamento());

        $objCargoDTO->setStrSinAtivo('S');
      	if ($objCargoRN->consultarRN0301($objCargoDTO)!=null){
      		$objInfraException->lancarValidacao('Existem cargos utilizando este tratamento.');
      	}

        $objCargoDTO->setStrSinAtivo('N');
 	      if ($objCargoRN->consultarRN0301($objCargoDTO)!=null){
          $objInfraException->lancarValidacao('Existem cargos inativos utilizando este tratamento.');
 	      }
      }

      $objTratamentoBD = new TratamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTratamentoDTO);$i++){
        $objTratamentoBD->excluir($arrObjTratamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tratamento.',$e);
    }
  }

  protected function consultarRN0317Conectado(TratamentoDTO $objTratamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tratamento_consultar',__METHOD__,$objTratamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTratamentoBD = new TratamentoBD($this->getObjInfraIBanco());
      $ret = $objTratamentoBD->consultar($objTratamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tratamento.',$e);
    }
  }

  protected function listarRN0318Conectado(TratamentoDTO $objTratamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tratamento_listar',__METHOD__,$objTratamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTratamentoBD = new TratamentoBD($this->getObjInfraIBanco());
      $ret = $objTratamentoBD->listar($objTratamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tratamentos.',$e);
    }
  }

  protected function contarRN0328Conectado(TratamentoDTO $objTratamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tratamento_listar',__METHOD__,$objTratamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTratamentoBD = new TratamentoBD($this->getObjInfraIBanco());
      $ret = $objTratamentoBD->contar($objTratamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tratamentos.',$e);
    }
  }


  protected function desativarRN0345Controlado($arrObjTratamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tratamento_desativar',__METHOD__,$arrObjTratamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTratamentoBD = new TratamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTratamentoDTO);$i++){
        $objTratamentoBD->desativar($arrObjTratamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Tratamento.',$e);
    }
  }

  protected function reativarRN0346Controlado($arrObjTratamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tratamento_reativar',__METHOD__,$arrObjTratamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTratamentoBD = new TratamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTratamentoDTO);$i++){
        $objTratamentoBD->reativar($arrObjTratamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Tratamento.',$e);
    }
  }


  private function validarStrExpressaoRN0320(TratamentoDTO $objTratamentoDTO, InfraException $objInfraException){
  	if (InfraString::isBolVazia($objTratamentoDTO->getStrExpressao())){
      $objInfraException->adicionarValidacao('Expressão não informada.');
    }else{
    	$objTratamentoDTO->setStrExpressao(trim($objTratamentoDTO->getStrExpressao()));
      
      if (strlen($objTratamentoDTO->getStrExpressao())>100){
        $objInfraException->adicionarValidacao('Expressão possui tamanho superior a 100 caracteres.');
      }
      
      $dto = new TratamentoDTO();
      $dto->retStrSinAtivo();
      $dto->setNumIdTratamento($objTratamentoDTO->getNumIdTratamento(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrExpressao($objTratamentoDTO->getStrExpressao(),InfraDTO::$OPER_IGUAL);
      $dto->setBolExclusaoLogica(false);
          
      $dto = $this->consultarRN0317($dto);
      if ($dto != NULL){
        if ($dto->getStrSinAtivo() == 'S')
          $objInfraException->adicionarValidacao('Existe outra ocorrência de Tratamento que utiliza a mesma Expressão.');    	
        else
          $objInfraException->adicionarValidacao('Existe ocorrência inativa de Tratamento que utiliza a mesma Expressão.');    	
      }
    }
  }

  private function validarStrSinAtivoRN0350(TratamentoDTO $objTratamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTratamentoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objTratamentoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }
}
?>