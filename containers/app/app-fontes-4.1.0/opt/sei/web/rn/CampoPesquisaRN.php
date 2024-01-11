<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/03/2020 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CampoPesquisaRN extends InfraRN {


  PUBLIC STATIC $CP_DOCUMENTOS_GERADOS = 1;
  PUBLIC STATIC $CP_DOCUMENTOS_RECEBIDOS = 2;
  PUBLIC STATIC $CP_SIN_TRAMITACAO = 3;
  PUBLIC STATIC $CP_ID_ORGAO = 4;
  PUBLIC STATIC $CP_ID_CONTATO = 5;
  PUBLIC STATIC $CP_SIN_INTERESSADO = 6;
  PUBLIC STATIC $CP_SIN_REMETENTE = 7;
  PUBLIC STATIC $CP_SIN_DESTINATARIO = 8;
  PUBLIC STATIC $CP_ID_ASSINANTE = 9;
  PUBLIC STATIC $CP_DESCRICAO_PESQUISA = 10;
  PUBLIC STATIC $CP_OBSERVACAO_PESQUISA = 11;
  PUBLIC STATIC $CP_ID_ASSUNTO = 12;
  PUBLIC STATIC $CP_ID_UNIDADE = 13;
  PUBLIC STATIC $CP_PROTOCOLO_PESQUISA = 14;
  PUBLIC STATIC $CP_ID_SERIE_PESQUISA = 15;
  PUBLIC STATIC $CP_NUMERO_DOCUMENTO_PESQUISA  = 16;
  PUBLIC STATIC $CP_NOME_ARVORE_DOCUMENTO_PESQUISA = 17;
  PUBLIC STATIC $CP_SIN_DATA = 18;
  PUBLIC STATIC $CP_DATA_INICIO = 19;
  PUBLIC STATIC $CP_DATA_FIM = 20;
  PUBLIC STATIC $CP_ID_USUARIO_GERADOR1 = 21;
  PUBLIC STATIC $CP_ID_USUARIO_GERADOR2 = 22;
  PUBLIC STATIC $CP_ID_USUARIO_GERADOR3 = 23;
  PUBLIC STATIC $CP_PESQUISAR_EM = 24;
  PUBLIC STATIC $CP_TEXTO_PESQUISA = 25;
  PUBLIC STATIC $CP_ID_TIPO_PROCEDIMENTO_PESQUISA = 26;
  PUBLIC STATIC $CP_TXT_USUARIO_GERADOR1 = 27;
  PUBLIC STATIC $CP_TXT_USUARIO_GERADOR2 = 28;
  PUBLIC STATIC $CP_TXT_USUARIO_GERADOR3 = 29;
  PUBLIC STATIC $CP_TXT_ASSUNTO = 30;
  PUBLIC STATIC $CP_TXT_UNIDADE = 31;
  PUBLIC STATIC $CP_TXT_ASSINANTE = 32;
  PUBLIC STATIC $CP_TXT_CONTATO = 33;
  PUBLIC STATIC $CP_SIN_RESTRINGIR_ORGAO = 34;


  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumChave(CampoPesquisaDTO $objCampoPesquisaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCampoPesquisaDTO->getNumChave())){
      $objCampoPesquisaDTO->setNumChave(null);
    }
  }

  private function validarStrValor(CampoPesquisaDTO $objCampoPesquisaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCampoPesquisaDTO->getStrValor())){
      $objCampoPesquisaDTO->setStrValor(null);
    }else{
      $objCampoPesquisaDTO->setStrValor(trim($objCampoPesquisaDTO->getStrValor()));

      if (strlen($objCampoPesquisaDTO->getStrValor())>4000){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 4000 caracteres.');
      }
    }
  }

  private function validarNumIdPesquisa(CampoPesquisaDTO $objCampoPesquisaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCampoPesquisaDTO->getNumIdPesquisa())){
      $objCampoPesquisaDTO->setNumIdPesquisa(null);
    }
  }

  protected function cadastrarControlado(CampoPesquisaDTO $objCampoPesquisaDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('campo_pesquisa_cadastrar',__METHOD__,$objCampoPesquisaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumChave($objCampoPesquisaDTO, $objInfraException);
      $this->validarStrValor($objCampoPesquisaDTO, $objInfraException);
      $this->validarNumIdPesquisa($objCampoPesquisaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objCampoPesquisaBD = new CampoPesquisaBD($this->getObjInfraIBanco());
      $ret = $objCampoPesquisaBD->cadastrar($objCampoPesquisaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(CampoPesquisaDTO $objCampoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('campo_pesquisa_alterar',__METHOD__,$objCampoPesquisaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objCampoPesquisaDTO->isSetNumChave()){
        $this->validarNumChave($objCampoPesquisaDTO, $objInfraException);
      }
      if ($objCampoPesquisaDTO->isSetStrValor()){
        $this->validarStrValor($objCampoPesquisaDTO, $objInfraException);
      }
      if ($objCampoPesquisaDTO->isSetNumIdPesquisa()){
        $this->validarNumIdPesquisa($objCampoPesquisaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objCampoPesquisaBD = new CampoPesquisaBD($this->getObjInfraIBanco());
      $objCampoPesquisaBD->alterar($objCampoPesquisaDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjCampoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('campo_pesquisa_excluir',__METHOD__,$arrObjCampoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCampoPesquisaBD = new CampoPesquisaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCampoPesquisaDTO);$i++){
        $objCampoPesquisaBD->excluir($arrObjCampoPesquisaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(CampoPesquisaDTO $objCampoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('campo_pesquisa_consultar',__METHOD__,$objCampoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCampoPesquisaBD = new CampoPesquisaBD($this->getObjInfraIBanco());
      $ret = $objCampoPesquisaBD->consultar($objCampoPesquisaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(CampoPesquisaDTO $objCampoPesquisaDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('campo_pesquisa_listar',__METHOD__,$objCampoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCampoPesquisaBD = new CampoPesquisaBD($this->getObjInfraIBanco());
      $ret = $objCampoPesquisaBD->listar($objCampoPesquisaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(CampoPesquisaDTO $objCampoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('campo_pesquisa_listar',__METHOD__,$objCampoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCampoPesquisaBD = new CampoPesquisaBD($this->getObjInfraIBanco());
      $ret = $objCampoPesquisaBD->contar($objCampoPesquisaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjCampoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('campo_pesquisa_desativar',__METHOD__,$arrObjCampoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCampoPesquisaBD = new CampoPesquisaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCampoPesquisaDTO);$i++){
        $objCampoPesquisaBD->desativar($arrObjCampoPesquisaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjCampoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('campo_pesquisa_reativar',__METHOD__,$arrObjCampoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCampoPesquisaBD = new CampoPesquisaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCampoPesquisaDTO);$i++){
        $objCampoPesquisaBD->reativar($arrObjCampoPesquisaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(CampoPesquisaDTO $objCampoPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('campo_pesquisa_consultar',__METHOD__,$objCampoPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCampoPesquisaBD = new CampoPesquisaBD($this->getObjInfraIBanco());
      $ret = $objCampoPesquisaBD->bloquear($objCampoPesquisaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
