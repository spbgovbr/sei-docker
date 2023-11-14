<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/04/2014 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ImagemFormatoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }
 
  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrFormato(ImagemFormatoDTO $objImagemFormatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objImagemFormatoDTO->getStrFormato())){
      $objInfraException->adicionarValidacao('Formato não informado.');
    }else{
      $objImagemFormatoDTO->setStrFormato(trim($objImagemFormatoDTO->getStrFormato()));

      if (strlen($objImagemFormatoDTO->getStrFormato())>10){
        $objInfraException->adicionarValidacao('Formato possui tamanho superior a 10 caracteres.');
      }

      $arrExc = array('\\','/','?',':','*','"','>','<','|');
      foreach($arrExc as $strExc){
        if (strpos($objImagemFormatoDTO->getStrFormato(),$strExc)!==false){
          $objInfraException->lancarValidacao('Formato possui caractere inválido [ \\\\\\'.implode(' ',$arrExc).' ].');
        }
      }


      $dto = new ImagemFormatoDTO();
      $dto->retStrSinAtivo();
      $dto->setNumIdImagemFormato($objImagemFormatoDTO->getNumIdImagemFormato(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrFormato($objImagemFormatoDTO->getStrFormato(),InfraDTO::$OPER_IGUAL);
      $dto->setBolExclusaoLogica(false);
      
      $dto = $this->consultar($dto);
      if ($dto != NULL){
        if ($dto->getStrSinAtivo() == 'S')
          $objInfraException->adicionarValidacao('Existe outra ocorrência cadastrada com o mesmo Formato.');
        else
          $objInfraException->adicionarValidacao('Existe ocorrência inativa cadastrada com o mesmo Formato.');
      }
    }
  }

  private function validarStrDescricao(ImagemFormatoDTO $objImagemFormatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objImagemFormatoDTO->getStrDescricao())){
      $objImagemFormatoDTO->setStrDescricao(null);
    }else{
      $objImagemFormatoDTO->setStrDescricao(trim($objImagemFormatoDTO->getStrDescricao()));

      if (strlen($objImagemFormatoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(ImagemFormatoDTO $objImagemFormatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objImagemFormatoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objImagemFormatoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(ImagemFormatoDTO $objImagemFormatoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('imagem_formato_cadastrar', __METHOD__, $objImagemFormatoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrFormato($objImagemFormatoDTO, $objInfraException);
      $this->validarStrDescricao($objImagemFormatoDTO, $objInfraException);
      $this->validarStrSinAtivo($objImagemFormatoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objImagemFormatoBD = new ImagemFormatoBD($this->getObjInfraIBanco());
      $ret = $objImagemFormatoBD->cadastrar($objImagemFormatoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Formato de Imagem Permitido.',$e);
    }
  }

  protected function alterarControlado(ImagemFormatoDTO $objImagemFormatoDTO){
    try {

      //Valida Permissao
  	  SessaoSEI::getInstance()->validarAuditarPermissao('imagem_formato_alterar', __METHOD__, $objImagemFormatoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objImagemFormatoDTO->isSetStrFormato()){
        $this->validarStrFormato($objImagemFormatoDTO, $objInfraException);
      }
      if ($objImagemFormatoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objImagemFormatoDTO, $objInfraException);
      }
      if ($objImagemFormatoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objImagemFormatoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objImagemFormatoBD = new ImagemFormatoBD($this->getObjInfraIBanco());
      $objImagemFormatoBD->alterar($objImagemFormatoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Formato de Imagem Permitido.',$e);
    }
  }

  protected function excluirControlado($arrObjImagemFormatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('imagem_formato_excluir', __METHOD__, $arrObjImagemFormatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objImagemFormatoBD = new ImagemFormatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjImagemFormatoDTO);$i++){
        $objImagemFormatoBD->excluir($arrObjImagemFormatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Formato de Imagem Permitido.',$e);
    }
  }

  protected function consultarConectado(ImagemFormatoDTO $objImagemFormatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('imagem_formato_consultar', __METHOD__, $objImagemFormatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objImagemFormatoBD = new ImagemFormatoBD($this->getObjInfraIBanco());
      $ret = $objImagemFormatoBD->consultar($objImagemFormatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Formato de Imagem Permitido.',$e);
    }
  }

  protected function listarConectado(ImagemFormatoDTO $objImagemFormatoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('imagem_formato_listar', __METHOD__, $objImagemFormatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objImagemFormatoBD = new ImagemFormatoBD($this->getObjInfraIBanco());
      $ret = $objImagemFormatoBD->listar($objImagemFormatoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Formatos de Imagem Permitidos.',$e);
    }
  }

  protected function contarConectado(ImagemFormatoDTO $objImagemFormatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('imagem_formato_listar', __METHOD__, $objImagemFormatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objImagemFormatoBD = new ImagemFormatoBD($this->getObjInfraIBanco());
      $ret = $objImagemFormatoBD->contar($objImagemFormatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Formatos de Imagem Permitidos.',$e);
    }
  }

  protected function desativarControlado($arrObjImagemFormatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('imagem_formato_desativar', __METHOD__, $arrObjImagemFormatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objImagemFormatoBD = new ImagemFormatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjImagemFormatoDTO);$i++){
        $objImagemFormatoBD->desativar($arrObjImagemFormatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Formato de Imagem Permitido.',$e);
    }
  }

  protected function reativarControlado($arrObjImagemFormatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('imagem_formato_reativar', __METHOD__, $arrObjImagemFormatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objImagemFormatoBD = new ImagemFormatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjImagemFormatoDTO);$i++){
        $objImagemFormatoBD->reativar($arrObjImagemFormatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Formato de Imagem Permitido.',$e);
    }
  }

  protected function bloquearControlado(ImagemFormatoDTO $objImagemFormatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('imagem_formato_consultar', __METHOD__, $objImagemFormatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objImagemFormatoBD = new ImagemFormatoBD($this->getObjInfraIBanco());
      $ret = $objImagemFormatoBD->bloquear($objImagemFormatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Formato de Imagem Permitido.',$e);
    }
  }


}
?>