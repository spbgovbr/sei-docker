<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/05/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class LugarLocalizadorRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0651Controlado(LugarLocalizadorDTO $objLugarLocalizadorDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('lugar_localizador_cadastrar',__METHOD__,$objLugarLocalizadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidadeRN0659($objLugarLocalizadorDTO, $objInfraException);
      $this->validarStrNomeRN0660($objLugarLocalizadorDTO, $objInfraException);
      $this->validarStrSinAtivoRN0661($objLugarLocalizadorDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objLugarLocalizadorBD = new LugarLocalizadorBD($this->getObjInfraIBanco());
      $ret = $objLugarLocalizadorBD->cadastrar($objLugarLocalizadorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Lugar de Localizador.',$e);
    }
  }

  protected function alterarRN0652Controlado(LugarLocalizadorDTO $objLugarLocalizadorDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('lugar_localizador_alterar',__METHOD__,$objLugarLocalizadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objLugarLocalizadorDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidadeRN0659($objLugarLocalizadorDTO, $objInfraException);
      }
      if ($objLugarLocalizadorDTO->isSetStrNome()){
        $this->validarStrNomeRN0660($objLugarLocalizadorDTO, $objInfraException);
      }
      if ($objLugarLocalizadorDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivoRN0661($objLugarLocalizadorDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objLugarLocalizadorBD = new LugarLocalizadorBD($this->getObjInfraIBanco());
      $objLugarLocalizadorBD->alterar($objLugarLocalizadorDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Lugar de Localizador.',$e);
    }
  }

  protected function excluirRN0654Controlado($arrObjLugarLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('lugar_localizador_excluir',__METHOD__,$arrObjLugarLocalizadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      $dtoRN = new LocalizadorRN();
      $dto = new LocalizadorDTO();
      $dto->retNumIdLocalizador();
      $dto->setNumMaxRegistrosRetorno(1);
      
      for ($i=0;$i<count($arrObjLugarLocalizadorDTO);$i++){
      	$dto->setNumIdLugarLocalizador($arrObjLugarLocalizadorDTO[$i]->getNumIdLugarLocalizador());
      	if ($dtoRN->consultarRN0619($dto) != null){
      		$objInfraException->adicionarValidacao('Existem localizadores utilizando este lugar.');
      	}      	
      }

      $objInfraException->lancarValidacoes();

      $objLugarLocalizadorBD = new LugarLocalizadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjLugarLocalizadorDTO);$i++){
        $objLugarLocalizadorBD->excluir($arrObjLugarLocalizadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Lugar de Localizador.',$e);
    }
  }

  protected function consultarRN0653Conectado(LugarLocalizadorDTO $objLugarLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('lugar_localizador_consultar',__METHOD__,$objLugarLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLugarLocalizadorBD = new LugarLocalizadorBD($this->getObjInfraIBanco());
      $ret = $objLugarLocalizadorBD->consultar($objLugarLocalizadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Lugar de Localizador.',$e);
    }
  }

  protected function listarRN0655Conectado(LugarLocalizadorDTO $objLugarLocalizadorDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('lugar_localizador_listar',__METHOD__,$objLugarLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLugarLocalizadorBD = new LugarLocalizadorBD($this->getObjInfraIBanco());
      $ret = $objLugarLocalizadorBD->listar($objLugarLocalizadorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Lugares de Localizador.',$e);
    }
  }

  protected function contarRN0656Conectado(LugarLocalizadorDTO $objLugarLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('lugar_localizador_listar',__METHOD__,$objLugarLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLugarLocalizadorBD = new LugarLocalizadorBD($this->getObjInfraIBanco());
      $ret = $objLugarLocalizadorBD->contar($objLugarLocalizadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Lugares de Localizador.',$e);
    }
  }

  protected function desativarRN0657Controlado($arrObjLugarLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('lugar_localizador_desativar',__METHOD__,$arrObjLugarLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLugarLocalizadorBD = new LugarLocalizadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjLugarLocalizadorDTO);$i++){
        $objLugarLocalizadorBD->desativar($arrObjLugarLocalizadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Lugar de Localizador.',$e);
    }
  }

  protected function reativarRN0658Controlado($arrObjLugarLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('lugar_localizador_reativar',__METHOD__,$arrObjLugarLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLugarLocalizadorBD = new LugarLocalizadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjLugarLocalizadorDTO);$i++){
        $objLugarLocalizadorBD->reativar($arrObjLugarLocalizadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Lugar de Localizador.',$e);
    }
  }

  private function validarNumIdUnidadeRN0659(LugarLocalizadorDTO $objLugarLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objLugarLocalizadorDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarStrNomeRN0660(LugarLocalizadorDTO $objLugarLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objLugarLocalizadorDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objLugarLocalizadorDTO->setStrNome(trim($objLugarLocalizadorDTO->getStrNome()));
      
      if (strlen($objLugarLocalizadorDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
      
      $dto = new LugarLocalizadorDTO();
      $dto->retStrSinAtivo();
      $dto->setNumIdLugarLocalizador($objLugarLocalizadorDTO->getNumIdLugarLocalizador(),InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdUnidade($objLugarLocalizadorDTO->getNumIdUnidade());
      $dto->setStrNome($objLugarLocalizadorDTO->getStrNome());
      $dto->setBolExclusaoLogica(false);
          
      $dto = $this->consultarRN0653($dto);
      if ($dto != NULL){
        if ($dto->getStrSinAtivo() == 'S')
          $objInfraException->adicionarValidacao('Existe outra ocorrência de Lugar de Localizador que utiliza o mesmo Nome.');    	
        else
          $objInfraException->adicionarValidacao('Existe ocorrência inativa de Lugar de Localizador que utiliza o mesmo Nome.');    	
      }
    }
  }

  private function validarStrSinAtivoRN0661(LugarLocalizadorDTO $objLugarLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objLugarLocalizadorDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objLugarLocalizadorDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }
}
?>