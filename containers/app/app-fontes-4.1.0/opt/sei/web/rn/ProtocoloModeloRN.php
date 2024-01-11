<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/08/2012 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ProtocoloModeloRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdGrupoProtocoloModelo(ProtocoloModeloDTO $objProtocoloModeloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objProtocoloModeloDTO->getNumIdGrupoProtocoloModelo())){
      $objProtocoloModeloDTO->setNumIdGrupoProtocoloModelo(null);
    }
  }

  private function validarNumIdUnidade(ProtocoloModeloDTO $objProtocoloModeloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objProtocoloModeloDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarNumIdUsuario(ProtocoloModeloDTO $objProtocoloModeloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objProtocoloModeloDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarDblIdProtocolo(ProtocoloModeloDTO $objProtocoloModeloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objProtocoloModeloDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }
  }

  private function validarStrDescricao(ProtocoloModeloDTO $objProtocoloModeloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objProtocoloModeloDTO->getStrDescricao())){
      $objProtocoloModeloDTO->setStrDescricao(null);
    }else{
      $objProtocoloModeloDTO->setStrDescricao(trim($objProtocoloModeloDTO->getStrDescricao()));

      if (strlen($objProtocoloModeloDTO->getStrDescricao())>$this->getNumMaxTamanhoDescricao()){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a '.$this->getNumMaxTamanhoDescricao().' caracteres.');
      }
    }
  }

  private function validarStrIdxProtocoloModelo(ProtocoloModeloDTO $objProtocoloModeloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objProtocoloModeloDTO->getStrIdxProtocoloModelo())){
      $objProtocoloModeloDTO->setStrIdxProtocoloModelo(null);
    }else{
      $objProtocoloModeloDTO->setStrIdxProtocoloModelo(trim($objProtocoloModeloDTO->getStrIdxProtocoloModelo()));

      if (strlen($objProtocoloModeloDTO->getStrIdxProtocoloModelo()) > 4000){
        $objInfraException->adicionarValidacao('Indexação possui tamanho superior a 4000 caracteres.');
      }
    }
  }

  private function validarDuplicado(ProtocoloModeloDTO $objProtocoloModeloDTO, InfraException $objInfraException){
    $dto = new ProtocoloModeloDTO();
    $dto->retDblIdProtocoloModelo();
    $dto->setDblIdProtocoloModelo($objProtocoloModeloDTO->getDblIdProtocoloModelo(),InfraDTO::$OPER_DIFERENTE);
    $dto->setDblIdProtocolo($objProtocoloModeloDTO->getDblIdProtocolo());
    $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $dto->setNumIdGrupoProtocoloModelo($objProtocoloModeloDTO->getNumIdGrupoProtocoloModelo());
    $dto->setNumMaxRegistrosRetorno(1);
    if ($this->consultar($dto) != null){
      if ($objProtocoloModeloDTO->getNumIdGrupoProtocoloModelo()==null){
        $objInfraException->lancarValidacao('Já existe um Favorito para o protocolo '.$objProtocoloModeloDTO->getStrProtocoloFormatado().' sem grupo definido.');
      }else{
        $objInfraException->lancarValidacao('Já existe um Favorito para o protocolo '.$objProtocoloModeloDTO->getStrProtocoloFormatado().' com este grupo.');
      }
    }
  }

  public function getNumMaxTamanhoDescricao(){
    return 1000;
  }

  protected function cadastrarControlado(ProtocoloModeloDTO $objProtocoloModeloDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_modelo_cadastrar',__METHOD__,$objProtocoloModeloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdGrupoProtocoloModelo($objProtocoloModeloDTO, $objInfraException);
      $this->validarNumIdUnidade($objProtocoloModeloDTO, $objInfraException);
      $this->validarNumIdUsuario($objProtocoloModeloDTO, $objInfraException);
      $this->validarDblIdProtocolo($objProtocoloModeloDTO, $objInfraException);
      $this->validarStrDescricao($objProtocoloModeloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

///
	    $objProtocoloDTO = new ProtocoloDTO();
	    $objProtocoloDTO->retStrStaProtocolo();
	    $objProtocoloDTO->retStrProtocoloFormatado();
      $objProtocoloDTO->retStrStaNivelAcessoGlobal();
	    $objProtocoloDTO->setDblIdProtocolo($objProtocoloModeloDTO->getDblIdProtocolo());
	      
	    $objProtocoloRN = new ProtocoloRN();
	    $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

      $objProtocoloModeloDTO->setStrProtocoloFormatado($objProtocoloDTO->getStrProtocoloFormatado());

	    if ($objProtocoloDTO==null){
	    	$objInfraException->lancarValidacao('Protocolo não encontrado.');
	    }

	    //if ($objProtocoloDTO->getStrStaProtocolo()!=ProtocoloRN::$TP_DOCUMENTO_GERADO && $objProtocoloDTO->getStrStaProtocolo()!=ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){
	    //  $objInfraException->lancarValidacao('Protocolo '.$objProtocoloDTO->getStrProtocoloFormatado().' não é um documento.');
	    //}
	    
     	if ($objProtocoloDTO->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_SIGILOSO){
     		$objInfraException->lancarValidacao('Protocolo sigiloso '.$objProtocoloDTO->getStrProtocoloFormatado().' não pode ser adicionado como favorito.');
     	}
      
     	//$objDocumentoDTO = new DocumentoDTO();
     	//$objDocumentoDTO->retStrStaDocumento();
     	//$objDocumentoDTO->setDblIdDocumento($objProtocoloModeloDTO->getDblIdProtocolo());
     	
     	//$objDocumentoRN = new DocumentoRN();
     	//$objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
     	
     	//if ($objDocumentoDTO->getStrStaDocumento()!=DocumentoRN::$TD_EDITOR_INTERNO){
     	//	$objInfraException->adicionarValidacao('Documento '.$objProtocoloDTO->getStrProtocoloFormatado().' não foi gerado com o editor interno.');
     	//}

      $this->validarDuplicado($objProtocoloModeloDTO, $objInfraException);

     	$objInfraException->lancarValidacoes();
      
     	$objProtocoloModeloDTO->setDthAlteracao(InfraData::getStrDataHoraAtual());
     	
      $objProtocoloModeloBD = new ProtocoloModeloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloModeloBD->cadastrar($objProtocoloModeloDTO);

      $this->montarIndexacao($ret);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Favorito.',$e);
    }
  }

  protected function alterarControlado(ProtocoloModeloDTO $objProtocoloModeloDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_modelo_alterar',__METHOD__,$objProtocoloModeloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objProtocoloModeloDTOBanco = new ProtocoloModeloDTO();
      $objProtocoloModeloDTOBanco->retNumIdGrupoProtocoloModelo();
      $objProtocoloModeloDTOBanco->retStrDescricao();
      $objProtocoloModeloDTOBanco->retNumIdUnidade();
      $objProtocoloModeloDTOBanco->retDblIdProtocolo();
      $objProtocoloModeloDTOBanco->retStrProtocoloFormatado();
      $objProtocoloModeloDTOBanco->setDblIdProtocoloModelo($objProtocoloModeloDTO->getDblIdProtocoloModelo());
      $objProtocoloModeloDTOBanco = $this->consultar($objProtocoloModeloDTOBanco);

      $objProtocoloModeloDTO->setStrProtocoloFormatado($objProtocoloModeloDTOBanco->getStrProtocoloFormatado());

      if ($objProtocoloModeloDTO->isSetNumIdGrupoProtocoloModelo()){
        $this->validarNumIdGrupoProtocoloModelo($objProtocoloModeloDTO, $objInfraException);
      }else{
        $objProtocoloModeloDTO->setNumIdGrupoProtocoloModelo($objProtocoloModeloDTOBanco->getNumIdGrupoProtocoloModelo());
      }

      if ($objProtocoloModeloDTO->isSetNumIdUnidade() && $objProtocoloModeloDTO->getNumIdUnidade()!=$objProtocoloModeloDTOBanco->getNumIdUnidade()) {
        $objInfraException->lancarValidacao('Não é possível alterar a unidade de um Favorito.');
      }else{
        $objProtocoloModeloDTO->setNumIdUnidade($objProtocoloModeloDTOBanco->getNumIdUnidade());
      }

      if ($objProtocoloModeloDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objProtocoloModeloDTO, $objInfraException);
      }

      if ($objProtocoloModeloDTO->isSetDblIdProtocolo() && $objProtocoloModeloDTO->getDblIdProtocolo()!=$objProtocoloModeloDTOBanco->getDblIdProtocolo()){
        $objInfraException->lancarValidacao('Não é possível alterar o protocolo de um Favorito.');
      }else{
        $objProtocoloModeloDTO->setDblIdProtocolo($objProtocoloModeloDTOBanco->getDblIdProtocolo());
      }

      if ($objProtocoloModeloDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objProtocoloModeloDTO, $objInfraException);
      }else{
        $objProtocoloModeloDTO->setStrDescricao($objProtocoloModeloDTOBanco->getStrDescricao());
      }

      $this->validarDuplicado($objProtocoloModeloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      if ($objProtocoloModeloDTO->getNumIdGrupoProtocoloModelo()!=$objProtocoloModeloDTOBanco->getNumIdGrupoProtocoloModelo() || $objProtocoloModeloDTO->getStrDescricao()!=$objProtocoloModeloDTOBanco->getStrDescricao()){
        $objProtocoloModeloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objProtocoloModeloDTO->setDthAlteracao(InfraData::getStrDataHoraAtual());
      }


      $objProtocoloModeloBD = new ProtocoloModeloBD($this->getObjInfraIBanco());
      $objProtocoloModeloBD->alterar($objProtocoloModeloDTO);

      $this->montarIndexacao($objProtocoloModeloDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Favorito.',$e);
    }
  }

  protected function excluirControlado($arrObjProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_modelo_excluir',__METHOD__,$arrObjProtocoloModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloModeloBD = new ProtocoloModeloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjProtocoloModeloDTO);$i++){
        $objProtocoloModeloBD->excluir($arrObjProtocoloModeloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Favorito.',$e);
    }
  }

  protected function consultarConectado(ProtocoloModeloDTO $objProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_modelo_consultar',__METHOD__,$objProtocoloModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloModeloBD = new ProtocoloModeloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloModeloBD->consultar($objProtocoloModeloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Favorito.',$e);
    }
  }

  protected function listarConectado(ProtocoloModeloDTO $objProtocoloModeloDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_modelo_listar',__METHOD__,$objProtocoloModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloModeloBD = new ProtocoloModeloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloModeloBD->listar($objProtocoloModeloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Favoritos.',$e);
    }
  }
  
  protected function listarModelosUnidadeControlado(ProtocoloModeloDTO $objProtocoloModeloDTO){
    try {
  
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_modelo_listar',__METHOD__,$objProtocoloModeloDTO);
  
      //Regras de Negocio
      //$objInfraException = new InfraException();
  
      //$objInfraException->lancarValidacoes();
  
      $objProtocoloModeloDTO->retDblIdProtocoloModelo();
      $objProtocoloModeloDTO->retNumIdUnidade();
      $objProtocoloModeloDTO->retNumIdGrupoProtocoloModelo();
      $objProtocoloModeloDTO->retDblIdProtocolo();
      $objProtocoloModeloDTO->retNumIdUsuario();      
      $objProtocoloModeloDTO->retStrDescricao();      
      $objProtocoloModeloDTO->retStrNomeGrupoProtocoloModelo();
      $objProtocoloModeloDTO->retStrNomeUsuario();
      $objProtocoloModeloDTO->retStrSiglaUsuario();
      $objProtocoloModeloDTO->retStrProtocoloFormatado();
      $objProtocoloModeloDTO->retStrStaNivelAcessoGlobalProtocolo();
      $objProtocoloModeloDTO->retStrStaProtocoloProtocolo();
      $objProtocoloModeloDTO->retStrNomeTipoProcedimento();
      $objProtocoloModeloDTO->retStrNomeSerie();
      $objProtocoloModeloDTO->retDthAlteracao();
  
      $objProtocoloModeloDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  
      $objProtocoloModeloDTO = InfraString::prepararPesquisaDTO($objProtocoloModeloDTO, 'PalavrasPesquisa', 'IdxProtocoloModelo');

      $objProtocoloModeloRN = new ProtocoloModeloRN();
      $arrObjProtocoloModeloDTO = $objProtocoloModeloRN->listar($objProtocoloModeloDTO);

      if (count($arrObjProtocoloModeloDTO)>0){
  
        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
        $objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjProtocoloModeloDTO,'IdProtocolo'));
  
        $objProtocoloRN = new ProtocoloRN();
        $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO),'IdProtocolo');
      }
       
      $arrRet = array();
      foreach($arrObjProtocoloModeloDTO as $dto){
        //se tem acesso
        if (isset($arrObjProtocoloDTO[$dto->getDblIdProtocolo()]) && $arrObjProtocoloDTO[$dto->getDblIdProtocolo()]->getStrStaNivelAcessoGlobal()!=ProtocoloRN::$NA_SIGILOSO){
          $arrRet[] = $dto;
        }
      }
      
      return $arrRet;
  
  
    }catch(Exception $e){
      throw new InfraException('Erro listando Favoritos da unidade.',$e);
    }
  }

  protected function contarConectado(ProtocoloModeloDTO $objProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_modelo_listar',__METHOD__,$objProtocoloModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloModeloBD = new ProtocoloModeloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloModeloBD->contar($objProtocoloModeloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Favoritos.',$e);
    }
  }

  protected function montarIndexacaoControlado(ProtocoloModeloDTO $parObjProtocoloModeloDTO){
    try{

      $objProtocoloModeloDTO = new ProtocoloModeloDTO();
      $objProtocoloModeloDTO->retDblIdProtocoloModelo();
      $objProtocoloModeloDTO->retStrProtocoloFormatado();
      $objProtocoloModeloDTO->retStrDescricao();
      $objProtocoloModeloDTO->retDthAlteracao();

      if (is_array($parObjProtocoloModeloDTO->getDblIdProtocoloModelo())){
        $objProtocoloModeloDTO->setDblIdProtocoloModelo($parObjProtocoloModeloDTO->getDblIdProtocoloModelo(),InfraDTO::$OPER_IN);
      }else{
        $objProtocoloModeloDTO->setDblIdProtocoloModelo($parObjProtocoloModeloDTO->getDblIdProtocoloModelo());
      }

      $objInfraException = new InfraException();
      $objProtocoloModeloDTOIdx = new ProtocoloModeloDTO();
      $objProtocoloModeloBD = new ProtocoloModeloBD($this->getObjInfraIBanco());

      $arrObjProtocoloModeloDTO = $this->listar($objProtocoloModeloDTO);

      foreach($arrObjProtocoloModeloDTO as $objProtocoloModeloDTO) {

        $objProtocoloModeloDTOIdx->setStrIdxProtocoloModelo(InfraString::prepararIndexacao($objProtocoloModeloDTO->getStrProtocoloFormatado().' '.
            $objProtocoloModeloDTO->getStrDescricao().' '.
            $objProtocoloModeloDTO->getDthAlteracao()));
        $objProtocoloModeloDTOIdx->setDblIdProtocoloModelo($objProtocoloModeloDTO->getDblIdProtocoloModelo());

        $this->validarStrIdxProtocoloModelo($objProtocoloModeloDTOIdx, $objInfraException);
        $objInfraException->lancarValidacoes();

        $objProtocoloModeloBD->alterar($objProtocoloModeloDTOIdx);
      }

    }catch(Exception $e){
      throw new InfraException('Erro montando indexação de Favorito.',$e);
    }
  }

/* 
  protected function desativarControlado($arrObjProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_modelo_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloModeloBD = new ProtocoloModeloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjProtocoloModeloDTO);$i++){
        $objProtocoloModeloBD->desativar($arrObjProtocoloModeloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Favorito.',$e);
    }
  }

  protected function reativarControlado($arrObjProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_modelo_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloModeloBD = new ProtocoloModeloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjProtocoloModeloDTO);$i++){
        $objProtocoloModeloBD->reativar($arrObjProtocoloModeloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Favorito.',$e);
    }
  }

  protected function bloquearControlado(ProtocoloModeloDTO $objProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_modelo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloModeloBD = new ProtocoloModeloBD($this->getObjInfraIBanco());
      $ret = $objProtocoloModeloBD->bloquear($objProtocoloModeloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Favorito.',$e);
    }
  }

 */
}
?>