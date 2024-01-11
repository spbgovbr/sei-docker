<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/09/2010 - criado por alexandre_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EmailGrupoEmailRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdGrupoEmail(EmailGrupoEmailDTO $objEmailGrupoEmailDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEmailGrupoEmailDTO->getNumIdGrupoEmail())){
      $objInfraException->adicionarValidacao('Grupo não informado.');
    }
  }

  private function validarStrEmail(EmailGrupoEmailDTO $objEmailGrupoEmailDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEmailGrupoEmailDTO->getStrEmail())){
      $objInfraException->adicionarValidacao('E-mail não informado.');
    }else{
      $objEmailGrupoEmailDTO->setStrEmail(trim($objEmailGrupoEmailDTO->getStrEmail()));

      if (strlen($objEmailGrupoEmailDTO->getStrEmail())>50){
        $objInfraException->adicionarValidacao('E-mail possui tamanho superior a 50 caracteres.');
      }
      
      if (!InfraUtil::validarEmail($objEmailGrupoEmailDTO->getStrEmail())){
        $objInfraException->adicionarValidacao('E-mail '.$objEmailGrupoEmailDTO->getStrEmail().' inválido.');
      }
      
      $objEmailGrupoEmailDTOBanco = new EmailGrupoEmailDTO();
      $objEmailGrupoEmailDTOBanco->setNumIdGrupoEmail($objEmailGrupoEmailDTO->getNumIdGrupoEmail());
      $objEmailGrupoEmailDTOBanco->setStrEmail($objEmailGrupoEmailDTO->getStrEmail());
      
      $objEmailGrupoEmailRN = new EmailGrupoEmailRN();
      
      if($objEmailGrupoEmailRN->contar($objEmailGrupoEmailDTOBanco) > 0){
      	$objInfraException->adicionarValidacao('E-mail '.$objEmailGrupoEmailDTO->getStrEmail().' duplicado.');
      }      
      
    }
  }

  private function validarStrDescricao(EmailGrupoEmailDTO $objEmailGrupoEmailDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEmailGrupoEmailDTO->getStrDescricao())){
      $objEmailGrupoEmailDTO->setStrDescricao(null);
    }else{
      $objEmailGrupoEmailDTO->setStrDescricao(trim($objEmailGrupoEmailDTO->getStrDescricao()));

      if (strlen($objEmailGrupoEmailDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrIdxEmailGrupoEmail(EmailGrupoEmailDTO $objEmailGrupoEmailDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEmailGrupoEmailDTO->getStrIdxEmailGrupoEmail())){
      $objEmailGrupoEmailDTO->setStrIdxEmailGrupoEmail(null);
    }else{
      $objEmailGrupoEmailDTO->setStrIdxEmailGrupoEmail(trim($objEmailGrupoEmailDTO->getStrIdxEmailGrupoEmail()));
      if (strlen($objEmailGrupoEmailDTO->getStrIdxEmailGrupoEmail()) > 500){
        $objInfraException->adicionarValidacao('Indexação possui tamanho superior a 500 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(EmailGrupoEmailDTO $objEmailGrupoEmailDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_cadastrar',__METHOD__,$objEmailGrupoEmailDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdGrupoEmail($objEmailGrupoEmailDTO, $objInfraException);
      $this->validarStrEmail($objEmailGrupoEmailDTO, $objInfraException);
      $this->validarStrDescricao($objEmailGrupoEmailDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objEmailGrupoEmailDTO->setStrIdxEmailGrupoEmail(null);

      $objEmailGrupoEmailBD = new EmailGrupoEmailBD($this->getObjInfraIBanco());
      $ret = $objEmailGrupoEmailBD->cadastrar($objEmailGrupoEmailDTO);

      $this->montarIndexacao($ret);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando E-mail no Grupo.',$e);
    }
  }

  protected function alterarControlado(EmailGrupoEmailDTO $objEmailGrupoEmailDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_alterar',__METHOD__,$objEmailGrupoEmailDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objEmailGrupoEmailDTO->isSetNumIdGrupoEmail()){
        $this->validarNumIdGrupoEmail($objEmailGrupoEmailDTO, $objInfraException);
      }
      if ($objEmailGrupoEmailDTO->isSetStrEmail()){
        $this->validarStrEmail($objEmailGrupoEmailDTO, $objInfraException);
      }
      if ($objEmailGrupoEmailDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objEmailGrupoEmailDTO, $objInfraException);
      }
      if ($objEmailGrupoEmailDTO->isSetStrIdxEmailGrupoEmail()) {
        $this->validarStrIdxEmailGrupoEmail($objEmailGrupoEmailDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objEmailGrupoEmailBD = new EmailGrupoEmailBD($this->getObjInfraIBanco());
      $objEmailGrupoEmailBD->alterar($objEmailGrupoEmailDTO);

      $this->montarIndexacao($objEmailGrupoEmailDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando E-mail do Grupo.',$e);
    }
  }

  protected function excluirControlado($arrObjEmailGrupoEmailDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_excluir',__METHOD__,$arrObjEmailGrupoEmailDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailGrupoEmailBD = new EmailGrupoEmailBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEmailGrupoEmailDTO);$i++){
        $objEmailGrupoEmailBD->excluir($arrObjEmailGrupoEmailDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo E-mail do Grupo.',$e);
    }
  }

  protected function consultarConectado(EmailGrupoEmailDTO $objEmailGrupoEmailDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_consultar',__METHOD__,$objEmailGrupoEmailDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailGrupoEmailBD = new EmailGrupoEmailBD($this->getObjInfraIBanco());
      $ret = $objEmailGrupoEmailBD->consultar($objEmailGrupoEmailDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando E-mail do Grupo.',$e);
    }
  }

  protected function listarConectado(EmailGrupoEmailDTO $objEmailGrupoEmailDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_listar',__METHOD__,$objEmailGrupoEmailDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailGrupoEmailBD = new EmailGrupoEmailBD($this->getObjInfraIBanco());
      $ret = $objEmailGrupoEmailBD->listar($objEmailGrupoEmailDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando E-mails do Grupo.',$e);
    }
  }

  protected function contarConectado(EmailGrupoEmailDTO $objEmailGrupoEmailDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_listar',__METHOD__,$objEmailGrupoEmailDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailGrupoEmailBD = new EmailGrupoEmailBD($this->getObjInfraIBanco());
      $ret = $objEmailGrupoEmailBD->contar($objEmailGrupoEmailDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando E-mails do Grupo.',$e);
    }
  }

  protected function montarIndexacaoControlado(EmailGrupoEmailDTO $objEmailGrupoEmailDTO){
    try{

      $dto = new EmailGrupoEmailDTO();
      $dto->retNumIdEmailGrupoEmail();
      $dto->retStrEmail();
      $dto->retStrDescricao();

      if (is_array($objEmailGrupoEmailDTO->getNumIdEmailGrupoEmail())){
        $dto->setNumIdEmailGrupoEmail($objEmailGrupoEmailDTO->getNumIdEmailGrupoEmail(),InfraDTO::$OPER_IN);
      }else{
        $dto->setNumIdEmailGrupoEmail($objEmailGrupoEmailDTO->getNumIdEmailGrupoEmail());
      }

      $objEmailGrupoEmailDTOIdx = new EmailGrupoEmailDTO();
      $objInfraException = new InfraException();
      $objEmailGrupoEmailBD = new EmailGrupoEmailBD($this->getObjInfraIBanco());

      $arrObjEmailGrupoEmailDTO = $this->listar($dto);

      foreach($arrObjEmailGrupoEmailDTO as $dto) {

        $objEmailGrupoEmailDTOIdx->setNumIdEmailGrupoEmail($dto->getNumIdEmailGrupoEmail());
        $objEmailGrupoEmailDTOIdx->setStrIdxEmailGrupoEmail(InfraString::prepararIndexacao($dto->getStrEmail().' '.$dto->getStrDescricao()));

        $this->validarStrIdxEmailGrupoEmail($objEmailGrupoEmailDTOIdx, $objInfraException);
        $objInfraException->lancarValidacoes();

        $objEmailGrupoEmailBD->alterar($objEmailGrupoEmailDTOIdx);
      }

    }catch(Exception $e){
      throw new InfraException('Erro montando indexação de bloco.',$e);
    }
  }

  protected function pesquisarConectado(EmailGrupoEmailDTO $objEmailGrupoEmailDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_listar',__METHOD__,$objEmailGrupoEmailDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEmailGrupoEmailDTO = InfraString::prepararPesquisaDTO($objEmailGrupoEmailDTO,"PalavrasPesquisa", "IdxEmailGrupoEmail");

      $ret = $this->listar($objEmailGrupoEmailDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando Grupos de E-mail.',$e);
    }
  }

}
?>