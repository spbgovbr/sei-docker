<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CpadVersaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdCpad(CpadVersaoDTO $objCpadVersaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadVersaoDTO->getNumIdCpad())){
      $objInfraException->adicionarValidacao('Comissão não informada.');
    }
  }

  private function validarStrSigla(CpadVersaoDTO $objCpadVersaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadVersaoDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }else{
      $objCpadVersaoDTO->setStrSigla(trim($objCpadVersaoDTO->getStrSigla()));

      if (strlen($objCpadVersaoDTO->getStrSigla())>30){
        $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrDescricao(CpadVersaoDTO $objCpadVersaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadVersaoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objCpadVersaoDTO->setStrDescricao(trim($objCpadVersaoDTO->getStrDescricao()));

      if (strlen($objCpadVersaoDTO->getStrDescricao())>100){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarDthVersao(CpadVersaoDTO $objCpadVersaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadVersaoDTO->getDthVersao())){
      $objInfraException->adicionarValidacao('Data não informada.');
    }else{
      if (!InfraData::validarDataHora($objCpadVersaoDTO->getDthVersao())){
        $objInfraException->adicionarValidacao('Data inválida.');
      }
    }
  }

  private function validarStrSinEditavel(CpadVersaoDTO $objCpadVersaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadVersaoDTO->getStrSinEditavel())){
      $objInfraException->adicionarValidacao('Sinalizador de Edição não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objCpadVersaoDTO->getStrSinEditavel())){
        $objInfraException->adicionarValidacao('Sinalizador de Edição inválida.');
      }
    }
  }

  private function validarNumIdUsuario(CpadVersaoDTO $objCpadVersaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadVersaoDTO->getNumIdUsuario())){
      $objCpadVersaoDTO->setNumIdUsuario(null);
    }
  }

  private function validarNumIdUnidade(CpadVersaoDTO $objCpadVersaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadVersaoDTO->getNumIdUnidade())){
      $objCpadVersaoDTO->setNumIdUnidade(null);
    }
  }

  protected function cadastrarControlado(CpadVersaoDTO $objCpadVersaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_versao_cadastrar',__METHOD__,$objCpadVersaoDTO);

      $objInfraException = new InfraException();

      $this->validarNumIdCpad($objCpadVersaoDTO, $objInfraException);
      $this->validarStrSigla($objCpadVersaoDTO, $objInfraException);
      $this->validarStrDescricao($objCpadVersaoDTO, $objInfraException);
      $this->validarDthVersao($objCpadVersaoDTO, $objInfraException);
      $this->validarStrSinEditavel($objCpadVersaoDTO, $objInfraException);
      $this->validarNumIdUsuario($objCpadVersaoDTO, $objInfraException);
      $this->validarNumIdUnidade($objCpadVersaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objCpadVersaoBD = new CpadVersaoBD($this->getObjInfraIBanco());

      //ao cadastrar uma nova versao, deve desativar a ultima (que é a ativa)
      $objCpadVersaoDTO_Ativa = new CpadVersaoDTO();
      $objCpadVersaoDTO_Ativa->retNumIdCpadVersao();
      $objCpadVersaoDTO_Ativa->setNumIdCpad($objCpadVersaoDTO->getNumIdCpad());
      $objCpadVersaoDTO_Ativa  = $objCpadVersaoBD->consultar($objCpadVersaoDTO_Ativa);
      if($objCpadVersaoDTO_Ativa != null) {
        $objCpadVersaoBD->desativar($objCpadVersaoDTO_Ativa);
      }

      $ret = $objCpadVersaoBD->cadastrar($objCpadVersaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Versão da Composição da Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  protected function alterarControlado(CpadVersaoDTO $objCpadVersaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_versao_alterar',__METHOD__,$objCpadVersaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objCpadVersaoDTO->isSetNumIdCpad()){
        $this->validarNumIdCpad($objCpadVersaoDTO, $objInfraException);
      }
      if ($objCpadVersaoDTO->isSetStrSigla()){
        $this->validarStrSigla($objCpadVersaoDTO, $objInfraException);
      }
      if ($objCpadVersaoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objCpadVersaoDTO, $objInfraException);
      }
      if ($objCpadVersaoDTO->isSetDthVersao()){
        $this->validarDthVersao($objCpadVersaoDTO, $objInfraException);
      }
      if ($objCpadVersaoDTO->isSetStrSinEditavel()){
        $this->validarStrSinEditavel($objCpadVersaoDTO, $objInfraException);
      }
      if ($objCpadVersaoDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objCpadVersaoDTO, $objInfraException);
      }
      if ($objCpadVersaoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objCpadVersaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objCpadVersaoBD = new CpadVersaoBD($this->getObjInfraIBanco());
      $objCpadVersaoBD->alterar($objCpadVersaoDTO);

      return $objCpadVersaoDTO;
    }catch(Exception $e){
      throw new InfraException('Erro alterando Versão da Composição da Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  protected function excluirControlado($arrObjCpadVersaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_versao_excluir',__METHOD__,$arrObjCpadVersaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      //ao excluir uma versao, deve excluir a composicao antes
      $arrIdCpadVersao = InfraArray::converterArrInfraDTO($arrObjCpadVersaoDTO,"IdCpadVersao");
      $objCpadComposicaoDTO = new CpadComposicaoDTO();
      $objCpadComposicaoDTO->retNumIdCpadComposicao();
      $objCpadComposicaoDTO->setNumIdCpadVersao($arrIdCpadVersao,InfraDTO::$OPER_IN);
      $objCpadComposicaoRN = new CpadComposicaoRN();
      $objCpadComposicaoRN->excluir($objCpadComposicaoRN->listar($objCpadComposicaoDTO));

      $objCpadVersaoBD = new CpadVersaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCpadVersaoDTO);$i++){
        $objCpadVersaoBD->excluir($arrObjCpadVersaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Versão da Composição da Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  protected function consultarConectado(CpadVersaoDTO $objCpadVersaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_versao_consultar',__METHOD__,$objCpadVersaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCpadVersaoBD = new CpadVersaoBD($this->getObjInfraIBanco());
      $ret = $objCpadVersaoBD->consultar($objCpadVersaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Versão da Composição da Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  protected function listarConectado(CpadVersaoDTO $objCpadVersaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_versao_listar',__METHOD__,$objCpadVersaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCpadVersaoBD = new CpadVersaoBD($this->getObjInfraIBanco());
      $ret = $objCpadVersaoBD->listar($objCpadVersaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Versões das Composições da Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  protected function contarConectado(CpadVersaoDTO $objCpadVersaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_versao_listar',__METHOD__,$objCpadVersaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCpadVersaoBD = new CpadVersaoBD($this->getObjInfraIBanco());
      $ret = $objCpadVersaoBD->contar($objCpadVersaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Versões das Composições da Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjCpadVersaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_versao_desativar',__METHOD__,$arrObjCpadVersaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCpadVersaoBD = new CpadVersaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCpadVersaoDTO);$i++){
        $objCpadVersaoBD->desativar($arrObjCpadVersaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Versão da Composição da Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  protected function reativarControlado($arrObjCpadVersaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_versao_reativar',__METHOD__,$arrObjCpadVersaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCpadVersaoBD = new CpadVersaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCpadVersaoDTO);$i++){
        $objCpadVersaoBD->reativar($arrObjCpadVersaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Versão da Composição da Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  protected function bloquearControlado(CpadVersaoDTO $objCpadVersaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_versao_consultar',__METHOD__,$objCpadVersaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCpadVersaoBD = new CpadVersaoBD($this->getObjInfraIBanco());
      $ret = $objCpadVersaoBD->bloquear($objCpadVersaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Versão da Composição da Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

 */
}
