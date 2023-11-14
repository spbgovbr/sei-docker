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

class TipoLocalizadorRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0605Controlado(TipoLocalizadorDTO $objTipoLocalizadorDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_localizador_cadastrar',__METHOD__,$objTipoLocalizadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidadeRN0613($objTipoLocalizadorDTO, $objInfraException);
      $this->validarStrSiglaRN0615($objTipoLocalizadorDTO, $objInfraException);
      $this->validarStrNomeRN0614($objTipoLocalizadorDTO, $objInfraException);
      $this->validarStrDescricao($objTipoLocalizadorDTO, $objInfraException);
      $this->validarStrSinAtivoRN0616($objTipoLocalizadorDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objTipoLocalizadorBD = new TipoLocalizadorBD($this->getObjInfraIBanco());
      $ret = $objTipoLocalizadorBD->cadastrar($objTipoLocalizadorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tipo de Localizador.',$e);
    }
  }

  protected function alterarRN0606Controlado(TipoLocalizadorDTO $objTipoLocalizadorDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('tipo_localizador_alterar',__METHOD__,$objTipoLocalizadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objTipoLocalizadorDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidadeRN0613($objTipoLocalizadorDTO, $objInfraException);
      }
      
      if ($objTipoLocalizadorDTO->isSetStrSigla()){
        $this->validarStrSiglaRN0615($objTipoLocalizadorDTO, $objInfraException);
      }
      if ($objTipoLocalizadorDTO->isSetStrNome()){
        $this->validarStrNomeRN0614($objTipoLocalizadorDTO, $objInfraException);
      }
      if ($objTipoLocalizadorDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objTipoLocalizadorDTO, $objInfraException);
      }
      if ($objTipoLocalizadorDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivoRN0616($objTipoLocalizadorDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objTipoLocalizadorDTOBanco = new TipoLocalizadorDTO();
      $objTipoLocalizadorDTOBanco->retStrSigla();
      
      
      $objTipoLocalizadorBD = new TipoLocalizadorBD($this->getObjInfraIBanco());
      $objTipoLocalizadorBD->alterar($objTipoLocalizadorDTO);

      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tipo de Localizador.',$e);
    }
  }

  protected function excluirRN0608Controlado($arrObjTipoLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_localizador_excluir',__METHOD__,$arrObjTipoLocalizadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      $dtoRN = new LocalizadorRN();
      $dto = new LocalizadorDTO();
      
      for ($i=0;$i<count($arrObjTipoLocalizadorDTO);$i++){
      	$dto->setNumIdTipoLocalizador($arrObjTipoLocalizadorDTO[$i]->getNumIdTipoLocalizador());
      	if ($dtoRN->contarRN0621($dto)>0){
      		$objInfraException->lancarValidacao('Existem localizadores utilizando este tipo de localizador.');
      	}      	
      }

      $objTipoLocalizadorBD = new TipoLocalizadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoLocalizadorDTO);$i++){
        $objTipoLocalizadorBD->excluir($arrObjTipoLocalizadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tipo de Localizador.',$e);
    }
  }

  protected function consultarRN0607Conectado(TipoLocalizadorDTO $objTipoLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_localizador_consultar',__METHOD__,$objTipoLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoLocalizadorBD = new TipoLocalizadorBD($this->getObjInfraIBanco());
      $ret = $objTipoLocalizadorBD->consultar($objTipoLocalizadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tipo de Localizador.',$e);
    }
  }

  protected function listarRN0610Conectado(TipoLocalizadorDTO $objTipoLocalizadorDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_localizador_listar',__METHOD__,$objTipoLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoLocalizadorBD = new TipoLocalizadorBD($this->getObjInfraIBanco());
      $ret = $objTipoLocalizadorBD->listar($objTipoLocalizadorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tipos de Localizador.',$e);
    }
  }

  protected function contarRN0609Conectado(TipoLocalizadorDTO $objTipoLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_localizador_listar',__METHOD__,$objTipoLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoLocalizadorBD = new TipoLocalizadorBD($this->getObjInfraIBanco());
      $ret = $objTipoLocalizadorBD->contar($objTipoLocalizadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tipos de Localizador.',$e);
    }
  }

  protected function desativarRN0611Controlado($arrObjTipoLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_localizador_desativar',__METHOD__,$arrObjTipoLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoLocalizadorBD = new TipoLocalizadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoLocalizadorDTO);$i++){
        $objTipoLocalizadorBD->desativar($arrObjTipoLocalizadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Tipo de Localizador.',$e);
    }
  }

  protected function reativarRN0612Controlado($arrObjTipoLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_localizador_reativar',__METHOD__,$arrObjTipoLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoLocalizadorBD = new TipoLocalizadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoLocalizadorDTO);$i++){
        $objTipoLocalizadorBD->reativar($arrObjTipoLocalizadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Tipo de Localizador.',$e);
    }
  }

  private function validarNumIdUnidadeRN0613(TipoLocalizadorDTO $objTipoLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoLocalizadorDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarStrSiglaRN0615(TipoLocalizadorDTO $objTipoLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoLocalizadorDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }else{
      $objTipoLocalizadorDTO->setStrSigla(trim($objTipoLocalizadorDTO->getStrSigla()));
      
      if (strlen($objTipoLocalizadorDTO->getStrSigla())>20){
          $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 20 caracteres.');
      }
      
      $dto = new TipoLocalizadorDTO();
      $dto->retStrSinAtivo();
      $dto->setNumIdTipoLocalizador($objTipoLocalizadorDTO->getNumIdTipoLocalizador(),InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(),InfraDTO::$OPER_IGUAL);
      $dto->setStrSigla($objTipoLocalizadorDTO->getStrSigla(),InfraDTO::$OPER_IGUAL);
      $dto->setBolExclusaoLogica(false);
          
      $dto = $this->consultarRN0607($dto);
      if ($dto != NULL){
        if ($dto->getStrSinAtivo() == 'S')
          $objInfraException->adicionarValidacao('Existe outra ocorrência de Tipo de Localizador que utiliza a mesma Sigla.');    	
        else
          $objInfraException->adicionarValidacao('Existe ocorrência inativa de Tipo de Localizador que utiliza a mesma Sigla.');
      }
    }    
  }

  private function validarStrNomeRN0614(TipoLocalizadorDTO $objTipoLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoLocalizadorDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objTipoLocalizadorDTO->setStrNome(trim($objTipoLocalizadorDTO->getStrNome()));
      
      if (strlen($objTipoLocalizadorDTO->getStrNome())>50){
          $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
      
      $dto = new TipoLocalizadorDTO();
      $dto->retStrSinAtivo();
      $dto->setNumIdTipoLocalizador($objTipoLocalizadorDTO->getNumIdTipoLocalizador(),InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(),InfraDTO::$OPER_IGUAL);
      $dto->setStrNome($objTipoLocalizadorDTO->getStrNome(),InfraDTO::$OPER_IGUAL);
      $dto->setBolExclusaoLogica(false);
          
      $dto = $this->consultarRN0607($dto);
      if ($dto != NULL){
        if ($dto->getStrSinAtivo() == 'S')
          $objInfraException->adicionarValidacao('Existe outra ocorrência de Tipo de Localizador que utiliza o mesmo Nome.');    	
        else
          $objInfraException->adicionarValidacao('Existe ocorrência inativa de Tipo de Localizador que utiliza o mesmo Nome.');    	
      }
    }
  }

  private function validarStrDescricao(TipoLocalizadorDTO $objTipoLocalizadorDTO, InfraException $objInfraException){
  
    if (InfraString::isBolVazia($objTipoLocalizadorDTO->getStrDescricao())){
      $objTipoLocalizadorDTO->setStrDescricao(null);
    }else{
      $objTipoLocalizadorDTO->setStrDescricao(trim($objTipoLocalizadorDTO->getStrDescricao()));
  
      if (strlen($objTipoLocalizadorDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }
  
  private function validarStrSinAtivoRN0616(TipoLocalizadorDTO $objTipoLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoLocalizadorDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objTipoLocalizadorDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }
}
?>