<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/07/2008 - criado por mga
*
* Versão do Gerador de Código: 1.21.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelProtocoloProtocoloRN extends InfraRN {
	
	public static $TA_DOCUMENTO_ASSOCIADO = '1';
	public static $TA_PROCEDIMENTO_ANEXADO = '2';
	public static $TA_PROCEDIMENTO_RELACIONADO = '3';
	public static $TA_PROCEDIMENTO_SOBRESTADO = '4';
	public static $TA_PROCEDIMENTO_DESANEXADO = '5';
	public static $TA_DOCUMENTO_MOVIDO = '6';
  public static $TA_DOCUMENTO_CIRCULAR = '7';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0839Controlado(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_protocolo_cadastrar',__METHOD__,$objRelProtocoloProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProtocolo1RN0844($objRelProtocoloProtocoloDTO, $objInfraException);
      $this->validarDblIdProtocolo2RN0845($objRelProtocoloProtocoloDTO, $objInfraException);
      $this->validarNumIdUsuarioRN0846($objRelProtocoloProtocoloDTO, $objInfraException);
      $this->validarNumIdUnidadeRN0870($objRelProtocoloProtocoloDTO, $objInfraException);
      $this->validarStrStaAssociacaoRN0847($objRelProtocoloProtocoloDTO, $objInfraException);
      $this->validarNumSequencia($objRelProtocoloProtocoloDTO, $objInfraException);
      $this->validarDthAssociacaoRN0865($objRelProtocoloProtocoloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelProtocoloProtocoloDTO->setStrSinCiencia('N');
      
      $objRelProtocoloProtocoloBD = new RelProtocoloProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloProtocoloBD->cadastrar($objRelProtocoloProtocoloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Associação de Protocolo.',$e);
    }
  }

  protected function alterarControlado(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_protocolo_alterar',__METHOD__,$objRelProtocoloProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelProtocoloProtocoloDTO->isSetDblIdProtocolo1()){
        $this->validarDblIdProtocolo1RN0844($objRelProtocoloProtocoloDTO, $objInfraException);
      }
      if ($objRelProtocoloProtocoloDTO->isSetDblIdProtocolo2()){
        $this->validarDblIdProtocolo2RN0845($objRelProtocoloProtocoloDTO, $objInfraException);
      }
      if ($objRelProtocoloProtocoloDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuarioRN0846($objRelProtocoloProtocoloDTO, $objInfraException);
      }
      if ($objRelProtocoloProtocoloDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidadeRN0870($objRelProtocoloProtocoloDTO, $objInfraException);
      }
      if ($objRelProtocoloProtocoloDTO->isSetStrStaAssociacao()){
        $this->validarStrStaAssociacaoRN0847($objRelProtocoloProtocoloDTO, $objInfraException);
      }
      if ($objRelProtocoloProtocoloDTO->isSetStrSinCiencia()){
        $this->validarStrSinCiencia($objRelProtocoloProtocoloDTO, $objInfraException);
      }
      if ($objRelProtocoloProtocoloDTO->isSetNumSequencia()){
        $this->validarNumSequencia($objRelProtocoloProtocoloDTO, $objInfraException);
      }
      if ($objRelProtocoloProtocoloDTO->isSetDthAssociacao()){
        $this->validarDthAssociacaoRN0865($objRelProtocoloProtocoloDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelProtocoloProtocoloBD = new RelProtocoloProtocoloBD($this->getObjInfraIBanco());
      $objRelProtocoloProtocoloBD->alterar($objRelProtocoloProtocoloDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Associação de Protocolo.',$e);
    }
  }

  protected function excluirRN0842Controlado($arrObjRelProtocoloProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_protocolo_excluir',__METHOD__,$arrObjRelProtocoloProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objComentarioRN = new ComentarioRN();

      $objRelProtocoloProtocoloBD = new RelProtocoloProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelProtocoloProtocoloDTO);$i++){

        $objComentarioDTO = new ComentarioDTO();
        $objComentarioDTO->retNumIdComentario();
        $objComentarioDTO->setDblIdRelProtocoloProtocolo($arrObjRelProtocoloProtocoloDTO[$i]->getDblIdRelProtocoloProtocolo());
        $objComentarioRN->excluir($objComentarioRN->listar($objComentarioDTO));

        $objRelProtocoloProtocoloBD->excluir($arrObjRelProtocoloProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Associação de Protocolo.',$e);
    }
  }

  protected function consultarRN0841Conectado(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_protocolo_consultar',__METHOD__,$objRelProtocoloProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloProtocoloBD = new RelProtocoloProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloProtocoloBD->consultar($objRelProtocoloProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Associação de Protocolo.',$e);
    }
  }

  protected function listarRN0187Conectado(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_protocolo_listar',__METHOD__,$objRelProtocoloProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloProtocoloBD = new RelProtocoloProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloProtocoloBD->listar($objRelProtocoloProtocoloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Associações de Protocolos.',$e);
    }
  }

  protected function contarRN0843Conectado(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_protocolo_listar',__METHOD__,$objRelProtocoloProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloProtocoloBD = new RelProtocoloProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloProtocoloBD->contar($objRelProtocoloProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Associações de Protocolos.',$e);
    }
  }

  private function validarDblIdProtocolo1RN0844(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloProtocoloDTO->getDblIdProtocolo1())){
      $objInfraException->adicionarValidacao('Primeiro protocolo da associação entre protocolos não informado.');
    }
  }

  private function validarDblIdProtocolo2RN0845(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloProtocoloDTO->getDblIdProtocolo2())){
      $objInfraException->adicionarValidacao('Segundo protocolo da associação entre protocolos não informado.');
    }
  }

  private function validarNumIdUsuarioRN0846(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloProtocoloDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário da associação entre protocolos não informado.');
    }
  }

  private function validarNumIdUnidadeRN0870(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloProtocoloDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade da associação entre protocolos não informada.');
    }
  }

  private function validarNumSequencia(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloProtocoloDTO->getNumSequencia())){
      $objInfraException->adicionarValidacao('Sequência da associação entre protocolos não informada.');
    }
  }
  
  private function validarStrStaAssociacaoRN0847(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO, InfraException $objInfraException){  	  	
    if (InfraString::isBolVazia($objRelProtocoloProtocoloDTO->getStrStaAssociacao())){
      $objInfraException->adicionarValidacao('Tipo de associação entre protocolos não informada.');
    }else{
		 	$arr = $this->tiposAssociacaoProtocoloRN0869();
			if (!in_array($objRelProtocoloProtocoloDTO->getStrStaAssociacao(),InfraArray::converterArrInfraDTO($arr,'StaTipo'))){
				$objInfraException->adicionarValidacao('Tipo de associação entre protocolos inválida.');
			}
    }
  }

  private function validarStrSinCiencia(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloProtocoloDTO->getStrSinCiencia())){
      $objInfraException->adicionarValidacao('Sinalizador de Ciência não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objRelProtocoloProtocoloDTO->getStrSinCiencia())){
        $objInfraException->adicionarValidacao('Sinalizador de Ciência inválido.');
      }
    }
  }
  
  private function validarDthAssociacaoRN0865(RelProtocoloProtocoloDTO $objRelProtocoloProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloProtocoloDTO->getDthAssociacao())){
      $objInfraException->adicionarValidacao('Data de associação de protocolos não informada.');
    }else{
      if (!InfraData::validarDataHora($objRelProtocoloProtocoloDTO->getDthAssociacao())){
        $objInfraException->adicionarValidacao('Data de associação de protocolos inválida.');
      }
    }
  }

  public function tiposAssociacaoProtocoloRN0869(){
  	$arr = array();

  	$objTipo = new TipoDTO();
  	$objTipo->setStrStaTipo(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
  	$objTipo->setStrDescricao('Documento Associado');
  	$arr[] = $objTipo;
  	
  	$objTipo = new TipoDTO();
  	$objTipo->setStrStaTipo(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
  	$objTipo->setStrDescricao('Processo Anexado');
  	$arr[] = $objTipo;
  	
  	$objTipo = new TipoDTO();
  	$objTipo->setStrStaTipo(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);
  	$objTipo->setStrDescricao('Processo Relacionado');
  	$arr[] = $objTipo;

  	$objTipo = new TipoDTO();
  	$objTipo->setStrStaTipo(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_SOBRESTADO);
  	$objTipo->setStrDescricao('Processo Sobrestado');
  	$arr[] = $objTipo;

  	$objTipo = new TipoDTO();
  	$objTipo->setStrStaTipo(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_DESANEXADO);
  	$objTipo->setStrDescricao('Processo Desanexado');
  	$arr[] = $objTipo;

  	$objTipo = new TipoDTO();
  	$objTipo->setStrStaTipo(RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO);
  	$objTipo->setStrDescricao('Documento Movido');
  	$arr[] = $objTipo;

    $objTipo = new TipoDTO();
    $objTipo->setStrStaTipo(RelProtocoloProtocoloRN::$TA_DOCUMENTO_CIRCULAR);
    $objTipo->setStrDescricao('Documento Circular');
    $arr[] = $objTipo;

  	return $arr;  	
  }

}
?>