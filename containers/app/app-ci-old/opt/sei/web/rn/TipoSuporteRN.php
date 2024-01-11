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

class TipoSuporteRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0631Controlado(TipoSuporteDTO $objTipoSuporteDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_suporte_cadastrar',__METHOD__,$objTipoSuporteDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNomeRN0639($objTipoSuporteDTO, $objInfraException);
      $this->validarStrSinAtivoRN0640($objTipoSuporteDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objTipoSuporteBD = new TipoSuporteBD($this->getObjInfraIBanco());
      $ret = $objTipoSuporteBD->cadastrar($objTipoSuporteDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tipo de Suporte.',$e);
    }
  }

  protected function alterarRN0632Controlado(TipoSuporteDTO $objTipoSuporteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_suporte_alterar',__METHOD__,$objTipoSuporteDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objTipoSuporteDTO->isSetStrNome()){
        $this->validarStrNomeRN0639($objTipoSuporteDTO, $objInfraException);
      }
      if ($objTipoSuporteDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivoRN0640($objTipoSuporteDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objTipoSuporteBD = new TipoSuporteBD($this->getObjInfraIBanco());
      $objTipoSuporteBD->alterar($objTipoSuporteDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tipo de Suporte.',$e);
    }
  }

  protected function excluirRN0635Controlado($arrObjTipoSuporteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_suporte_excluir',__METHOD__,$arrObjTipoSuporteDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $dtoRN = new LocalizadorRN();
      $dto = new LocalizadorDTO();

      for ($i=0;$i<count($arrObjTipoSuporteDTO);$i++){
        $dto->setNumIdTipoSuporte($arrObjTipoSuporteDTO[$i]->getNumIdTipoSuporte());
        if ($dtoRN->contarRN0621($dto)>0){
          $objInfraException->adicionarValidacao('Existem arquivos utilizando este tipo de suporte.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objTipoSuporteBD = new TipoSuporteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoSuporteDTO);$i++){
        $objTipoSuporteBD->excluir($arrObjTipoSuporteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tipo de Suporte.',$e);
    }
  }

  protected function consultarRN0633Conectado(TipoSuporteDTO $objTipoSuporteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_suporte_consultar',__METHOD__,$objTipoSuporteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoSuporteBD = new TipoSuporteBD($this->getObjInfraIBanco());
      $ret = $objTipoSuporteBD->consultar($objTipoSuporteDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tipo de Suporte.',$e);
    }
  }

  protected function listarRN0634Conectado(TipoSuporteDTO $objTipoSuporteDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_suporte_listar',__METHOD__,$objTipoSuporteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoSuporteBD = new TipoSuporteBD($this->getObjInfraIBanco());
      $ret = $objTipoSuporteBD->listar($objTipoSuporteDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tipos de Suporte.',$e);
    }
  }

  protected function contarRN0636Conectado(TipoSuporteDTO $objTipoSuporteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_suporte_listar',__METHOD__,$objTipoSuporteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoSuporteBD = new TipoSuporteBD($this->getObjInfraIBanco());
      $ret = $objTipoSuporteBD->contar($objTipoSuporteDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tipos de Suporte.',$e);
    }
  }


  protected function desativarRN0637Controlado($arrObjTipoSuporteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_suporte_desativar',__METHOD__,$arrObjTipoSuporteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoSuporteBD = new TipoSuporteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoSuporteDTO);$i++){
        $objTipoSuporteBD->desativar($arrObjTipoSuporteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Tipo de Suporte.',$e);
    }
  }

  protected function reativarRN0638Controlado($arrObjTipoSuporteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_suporte_reativar',__METHOD__,$arrObjTipoSuporteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoSuporteBD = new TipoSuporteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoSuporteDTO);$i++){
        $objTipoSuporteBD->reativar($arrObjTipoSuporteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Tipo de Suporte.',$e);
    }
  }


  private function validarStrNomeRN0639(TipoSuporteDTO $objTipoSuporteDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoSuporteDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objTipoSuporteDTO->setStrNome(trim($objTipoSuporteDTO->getStrNome()));
      
      if (strlen($objTipoSuporteDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
  
      $dto = new TipoSuporteDTO();
      $dto->retStrSinAtivo();
      $dto->setNumIdTipoSuporte($objTipoSuporteDTO->getNumIdTipoSuporte(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrNome($objTipoSuporteDTO->getStrNome(),InfraDTO::$OPER_IGUAL);
      $dto->setBolExclusaoLogica(false);
  
      $dto = $this->consultarRN0633($dto);
      if ($dto != NULL){
        if ($dto->getStrSinAtivo() == 'S')
        $objInfraException->adicionarValidacao('Existe outra ocorrência de Tipo de Suporte que utiliza o mesmo Nome.');
        else
        $objInfraException->adicionarValidacao('Existe ocorrência inativa de Tipo de Suporte que utiliza o mesmo Nome.');
      }
    }
  }

  private function validarStrSinAtivoRN0640(TipoSuporteDTO $objTipoSuporteDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoSuporteDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objTipoSuporteDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }
}
?>