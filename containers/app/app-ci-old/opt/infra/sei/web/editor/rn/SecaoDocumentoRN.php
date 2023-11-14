<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/12/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: SecaoDocumentoRN.php 7876 2013-08-20 14:59:25Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class SecaoDocumentoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumOrdem(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getNumOrdem())){
      $objInfraException->adicionarValidacao('Ordem não informada.');
    }
  }

  private function validarStrSinCabecalho(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getStrSinCabecalho())){
      $objInfraException->adicionarValidacao('Sinalizador de seção de cabeçalho não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objSecaoDocumentoDTO->getStrSinCabecalho())){
        $objInfraException->adicionarValidacao('Sinalizador de seção de cabeçalho inválido.');
      }
    }
  }
  
  private function validarStrSinRodape(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getStrSinRodape())){
      $objInfraException->adicionarValidacao('Sinalizador de seção de rodapé não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objSecaoDocumentoDTO->getStrSinRodape())){
        $objInfraException->adicionarValidacao('Sinalizador de seção de rodapé inválido.');
      }
    }
  }
  
  private function validarStrSinSomenteLeitura(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getStrSinSomenteLeitura())){
      $objInfraException->adicionarValidacao('Sinalizador de seção somente leitura não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objSecaoDocumentoDTO->getStrSinSomenteLeitura())){
        $objInfraException->adicionarValidacao('Sinalizador de seção somente leitura inválido.');
      }
    }
  }
  
  private function validarStrSinAssinatura(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getStrSinAssinatura())){
      $objInfraException->adicionarValidacao('Sinalizador de seção de assinatura não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objSecaoDocumentoDTO->getStrSinAssinatura())){
        $objInfraException->adicionarValidacao('Sinalizador de seção de assinatura inválido.');
      }
    }
  }

  private function validarStrSinPrincipal(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getStrSinPrincipal())){
      $objInfraException->adicionarValidacao('Sinalizador de seção principal não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objSecaoDocumentoDTO->getStrSinPrincipal())){
        $objInfraException->adicionarValidacao('Sinalizador de seção principal inválido.');
      }
    }
  }

  private function validarStrSinDinamica(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getStrSinDinamica())){
      $objInfraException->adicionarValidacao('Sinalizador de seção dinâmica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objSecaoDocumentoDTO->getStrSinDinamica())){
        $objInfraException->adicionarValidacao('Sinalizador de seção dinâmica inválido.');
      }
    }
  }

  private function validarStrSinHtml(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getStrSinHtml())){
      $objInfraException->adicionarValidacao('Sinalizador de seção com conteúdo inicial em HTML não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objSecaoDocumentoDTO->getStrSinHtml())){
        $objInfraException->adicionarValidacao('Sinalizador de seção com conteúdo inicial em HTML inválido.');
      }
    }
  }
  
  private function validarStrConteudo(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getStrConteudo())){
      $objSecaoDocumentoDTO->setStrConteudo(null);
    }
  }
  
  private function validarNumIdSecaoModelo(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getNumIdSecaoModelo())){
      $objInfraException->adicionarValidacao('Seção Modelo não informada.');
    }
  }

  private function validarDblIdDocumento(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getDblIdDocumento())){
      $objSecaoDocumentoDTO->setDblIdDocumento(null);
    }
  }

  private function validarNumIdBaseConhecimento(SecaoDocumentoDTO $objSecaoDocumentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoDocumentoDTO->getNumIdBaseConhecimento())){
      $objSecaoDocumentoDTO->setNumIdBaseConhecimento(null);
    }
  }
  
  protected function cadastrarControlado(SecaoDocumentoDTO $objSecaoDocumentoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_documento_cadastrar',__METHOD__,$objSecaoDocumentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumOrdem($objSecaoDocumentoDTO, $objInfraException);
      $this->validarStrSinSomenteLeitura($objSecaoDocumentoDTO, $objInfraException);
      $this->validarNumIdSecaoModelo($objSecaoDocumentoDTO, $objInfraException);
      $this->validarDblIdDocumento($objSecaoDocumentoDTO, $objInfraException);
      $this->validarNumIdBaseConhecimento($objSecaoDocumentoDTO, $objInfraException);
      $this->validarStrSinCabecalho($objSecaoDocumentoDTO, $objInfraException);
      $this->validarStrSinRodape($objSecaoDocumentoDTO, $objInfraException);
      $this->validarStrSinAssinatura($objSecaoDocumentoDTO, $objInfraException);
      $this->validarStrSinPrincipal($objSecaoDocumentoDTO, $objInfraException);
      $this->validarStrSinDinamica($objSecaoDocumentoDTO, $objInfraException);
      $this->validarStrSinHtml($objSecaoDocumentoDTO, $objInfraException);
      $this->validarStrConteudo($objSecaoDocumentoDTO, $objInfraException);

      if ($objSecaoDocumentoDTO->getDblIdDocumento()==null && $objSecaoDocumentoDTO->getNumIdBaseConhecimento()==null){
        throw new InfraException('Documento e Base de Conhecimento não informados no cadastramento da seção.');
      }
      
      $objInfraException->lancarValidacoes();

      $objSecaoDocumentoDTO->setStrConteudo(EditorRN::converterHTML($objSecaoDocumentoDTO->getStrConteudo()));
      
      $objSecaoDocumentoBD = new SecaoDocumentoBD($this->getObjInfraIBanco());
      $ret = $objSecaoDocumentoBD->cadastrar($objSecaoDocumentoDTO);
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando seção do documento.',$e);
    }
  }

  /*
  protected function alterarControlado(SecaoDocumentoDTO $objSecaoDocumentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('secao_documento_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objSecaoDocumentoDTO->isSetNumOrdem()){
        $this->validarNumOrdem($objSecaoDocumentoDTO, $objInfraException);
      }
      if ($objSecaoDocumentoDTO->isSetStrSinCabecalho()){
        $this->validarStrSinCabecalho($objSecaoDocumentoDTO, $objInfraException);
      }
      if ($objSecaoDocumentoDTO->isSetStrSinRodape()){
        $this->validarStrSinRodape($objSecaoDocumentoDTO, $objInfraException);
      }
      if ($objSecaoDocumentoDTO->isSetStrSinSomenteLeitura()){
        $this->validarStrSinSomenteLeitura($objSecaoDocumentoDTO, $objInfraException);
      }
      if ($objSecaoDocumentoDTO->isSetStrSinAssinatura()){
        $this->validarStrSinAssinatura($objSecaoDocumentoDTO, $objInfraException);
      }
      if ($objSecaoDocumentoDTO->isSetStrSinPrincipal()){
        $this->validarStrSinPrincipal($objSecaoDocumentoDTO, $objInfraException);
      }
      if ($objSecaoDocumentoDTO->isSetStrSinDinamica()){
        $this->validarStrSinDinamica($objSecaoDocumentoDTO, $objInfraException);
      }
      if ($objSecaoDocumentoDTO->isSetStrSinHtml()){
        $this->validarStrSinHtml($objSecaoDocumentoDTO, $objInfraException);
      }
      if ($objSecaoDocumentoDTO->isSetStrConteudo()){
        $this->validarStrConteudo($objSecaoDocumentoDTO, $objInfraException);
      }
      if ($objSecaoDocumentoDTO->isSetNumIdSecaoModelo()){
        $this->validarNumIdSecaoModelo($objSecaoDocumentoDTO, $objInfraException);
      }
      if ($objSecaoDocumentoDTO->isSetDblIdDocumento()){
        $this->validarDblIdDocumento($objSecaoDocumentoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objSecaoDocumentoBD = new SecaoDocumentoBD($this->getObjInfraIBanco());
      $objSecaoDocumentoBD->alterar($objSecaoDocumentoDTO);
      //busca última versão da seção para alterar
      $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
      $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();     
      $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento($objSecaoDocumentoDTO->getNumIdSecaoDocumento());
      $objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_DESC);
      $objVersaoSecaoDocumentoDTO->retVersao();
      $objVersaoSecaoDocumentoDTO->retConteudo();
      $arrObjVersaoSecaoDocumentoDTO=$objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);
      if(count($arrObjVersaoSecaoDocumentoDTO)<1) {
      	throw new InfraException("Registro de versão não encontrado.");
      }
      if ($objSecaoDocumentoDTO->getStrConteudo() != $arrObjVersaoSecaoDocumentoDTO[0]->getStrConteudo()) {
      	$objDocumentoDTO = new DocumentoDTO();
      	$objDocumentoRN = new DocumentoRN();
      	$objDocumentoDTO->setDblIdDocumento($objSecaoDocumentoDTO->getDblIdDocumento());
      	$objDocumentoDTO->retDblIdDocumento();
      	$objDocumentoDTO->retVersao();
      	$objDocumentoDTO=$objDocumentoRN->consultar($objDocumentoDTO);
      	if (is_null($objDocumentoDTO)) {
      		throw new InfraException("Erro buscando Documento.");;
      	}
      	$objDocumentoDTO->setDthModificado(InfraData::getStrDataHoraAtual());
      }
      
      
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Seção.',$e);
    }
  }
  */

  protected function excluirControlado($arrObjSecaoDocumentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_documento_excluir',__METHOD__,$arrObjSecaoDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoDocumentoBD = new SecaoDocumentoBD($this->getObjInfraIBanco());
      $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
      $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
      
      for($i=0;$i<count($arrObjSecaoDocumentoDTO);$i++){
      	
        $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento($arrObjSecaoDocumentoDTO[$i]->getNumIdSecaoDocumento());
      	$objVersaoSecaoDocumentoDTO->retDblIdVersaoSecaoDocumento();
     	  $objVersaoSecaoDocumentoRN->excluir($objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO));
     	  
        $objSecaoDocumentoBD->excluir($arrObjSecaoDocumentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Seção.',$e);
    }
  }

  protected function consultarConectado(SecaoDocumentoDTO $objSecaoDocumentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_documento_consultar',__METHOD__,$objSecaoDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoDocumentoBD = new SecaoDocumentoBD($this->getObjInfraIBanco());
      $ret = $objSecaoDocumentoBD->consultar($objSecaoDocumentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Seção.',$e);
    }
  }

  protected function listarConectado(SecaoDocumentoDTO $objSecaoDocumentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_documento_listar',__METHOD__,$objSecaoDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoDocumentoBD = new SecaoDocumentoBD($this->getObjInfraIBanco());
      $ret = $objSecaoDocumentoBD->listar($objSecaoDocumentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Seções.',$e);
    }
  }

  protected function contarConectado(SecaoDocumentoDTO $objSecaoDocumentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_documento_listar',__METHOD__,$objSecaoDocumentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoDocumentoBD = new SecaoDocumentoBD($this->getObjInfraIBanco());
      $ret = $objSecaoDocumentoBD->contar($objSecaoDocumentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Seções.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjSecaoDocumentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_documento_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoDocumentoBD = new SecaoDocumentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSecaoDocumentoDTO);$i++){
        $objSecaoDocumentoBD->desativar($arrObjSecaoDocumentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Seção.',$e);
    }
  }

  protected function reativarControlado($arrObjSecaoDocumentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_documento_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoDocumentoBD = new SecaoDocumentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSecaoDocumentoDTO);$i++){
        $objSecaoDocumentoBD->reativar($arrObjSecaoDocumentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Seção.',$e);
    }
  }
  
  protected function bloquearControlado(SecaoDocumentoDTO $objSecaoDocumentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_documento_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoDocumentoBD = new SecaoDocumentoBD($this->getObjInfraIBanco());
      $ret = $objSecaoDocumentoBD->bloquear($objSecaoDocumentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Seção.',$e);
    }
  }
  */
}
?>