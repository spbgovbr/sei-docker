<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/02/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelProtocoloAssuntoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0171Controlado(RelProtocoloAssuntoDTO $objRelProtocoloAssuntoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_assunto_cadastrar',__METHOD__,$objRelProtocoloAssuntoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProtocoloRN0242($objRelProtocoloAssuntoDTO, $objInfraException);
      $this->validarNumIdAssuntoRN0243($objRelProtocoloAssuntoDTO, $objInfraException);
      $this->validarNumIdUnidadeRN0885($objRelProtocoloAssuntoDTO, $objInfraException);
      $this->validarNumSequenciaRN1176($objRelProtocoloAssuntoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAssuntoProxyDTO = new AssuntoProxyDTO();
      $objAssuntoProxyDTO->retNumIdAssuntoProxy();
      $objAssuntoProxyDTO->setNumIdAssunto($objRelProtocoloAssuntoDTO->getNumIdAssunto());
      $objAssuntoProxyDTO->setNumMaxRegistrosRetorno(1);
      $objAssuntoProxyDTO->setOrdNumIdAssuntoProxy(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objAssuntoProxyRN = new AssuntoProxyRN();
      $objAssuntoProxyDTO = $objAssuntoProxyRN->consultar($objAssuntoProxyDTO);

      if ($objAssuntoProxyDTO == null){
        throw new InfraException('Assunto não consta na tabela de utilização.');
      }

      $objRelProtocoloAssuntoDTO->setNumIdAssuntoProxy($objAssuntoProxyDTO->getNumIdAssuntoProxy());

      $objRelProtocoloAssuntoBD = new RelProtocoloAssuntoBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloAssuntoBD->cadastrar($objRelProtocoloAssuntoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando associação entre Protocolo e Assunto .',$e);
    }
  }

  protected function alterarRN1177Controlado(RelProtocoloAssuntoDTO $objRelProtocoloAssuntoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_assunto_alterar',__METHOD__,$objRelProtocoloAssuntoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelProtocoloAssuntoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidadeRN0885($objRelProtocoloAssuntoDTO, $objInfraException);
      }
      
      if ($objRelProtocoloAssuntoDTO->isSetNumSequencia()){
        $this->validarNumSequenciaRN1176($objRelProtocoloAssuntoDTO, $objInfraException);
      }
      
      $objInfraException->lancarValidacoes();


      $objRelProtocoloAssuntoBD = new RelProtocoloAssuntoBD($this->getObjInfraIBanco());
      $objRelProtocoloAssuntoBD->alterar($objRelProtocoloAssuntoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando associação entre Protocolo e Assunto.',$e);
    }
  }

  protected function excluirRN0224Controlado($arrObjRelProtocoloAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_assunto_excluir',__METHOD__,$arrObjRelProtocoloAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAssuntoBD = new RelProtocoloAssuntoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelProtocoloAssuntoDTO);$i++){
        $objRelProtocoloAssuntoBD->excluir($arrObjRelProtocoloAssuntoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }


  protected function consultarConectado(RelProtocoloAssuntoDTO $objRelProtocoloAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_assunto_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAssuntoBD = new RelProtocoloAssuntoBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloAssuntoBD->consultar($objRelProtocoloAssuntoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando associação entre Protocolo e Assunto.',$e);
    }
  }

   
  protected function listarRN0188Conectado(RelProtocoloAssuntoDTO $objRelProtocoloAssuntoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_assunto_listar',__METHOD__,$objRelProtocoloAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAssuntoBD = new RelProtocoloAssuntoBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloAssuntoBD->listar($objRelProtocoloAssuntoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando associações entre Protocolo e Assunto.',$e);
    }
  }

  protected function contarRN0257Conectado(RelProtocoloAssuntoDTO $objRelProtocoloAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_assunto_listar',__METHOD__,$objRelProtocoloAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAssuntoBD = new RelProtocoloAssuntoBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloAssuntoBD->contar($objRelProtocoloAssuntoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando associações entre Protocolo e Assunto.',$e);
    }
  }

/* 
  protected function desativarControlado($arrObjRelProtocoloAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_assunto_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAssuntoBD = new RelProtocoloAssuntoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelProtocoloAssuntoDTO);$i++){
        $objRelProtocoloAssuntoBD->desativar($arrObjRelProtocoloAssuntoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando associação entre Protocolo e Assunto.',$e);
    }
  }

  protected function reativarControlado($arrObjRelProtocoloAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_assunto_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAssuntoBD = new RelProtocoloAssuntoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelProtocoloAssuntoDTO);$i++){
        $objRelProtocoloAssuntoBD->reativar($arrObjRelProtocoloAssuntoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando associação entre Protocolo e Assunto.',$e);
    }
  }

 */
  private function validarDblIdProtocoloRN0242(RelProtocoloAssuntoDTO $objRelProtocoloAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloAssuntoDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado na associação com Assunto.');
    }
  }

  private function validarNumIdAssuntoRN0243(RelProtocoloAssuntoDTO $objRelProtocoloAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloAssuntoDTO->getNumIdAssunto())){
      $objInfraException->adicionarValidacao('Assunto não informado na associação com Protocolo.');
    }
  }

  private function validarNumIdUnidadeRN0885(RelProtocoloAssuntoDTO $objRelProtocoloAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloAssuntoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada na associação entre Protocolo e Assunto.');
    }
  }
  
  private function validarNumSequenciaRN1176(RelProtocoloAssuntoDTO $objRelProtocoloAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloAssuntoDTO->getNumSequencia())){
      $objInfraException->adicionarValidacao('Sequência não informada na associação entre Protocolo e Assunto.');
    }
  }
  
}
?>