<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AssuntoRN extends InfraRN {

  public static $TD_GUARDA_PERMANENTE = 'G';
  public static $TD_ELIMINACAO = 'E';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0259Controlado(AssuntoDTO $objAssuntoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_cadastrar',__METHOD__,$objAssuntoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdTabelaAssuntos($objAssuntoDTO, $objInfraException);
      $this->validarStrCodigoEstruturadoRN0250($objAssuntoDTO, $objInfraException);
      $this->validarStrDescricaoRN0251($objAssuntoDTO, $objInfraException);
      $this->validarStrSinEstruturalRN0502($objAssuntoDTO, $objInfraException);
      $this->validarNumPrazoCorrenteRN0496($objAssuntoDTO, $objInfraException);
      $this->validarNumPrazoIntermediarioRN0499($objAssuntoDTO, $objInfraException);
      $this->validarStrStaDestinacao($objAssuntoDTO, $objInfraException);
      $this->validarStrObservacaoRN0252($objAssuntoDTO, $objInfraException);
      $this->validarStrSinAtivoRN0255($objAssuntoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAssuntoDTO->setStrIdxAssunto(null);
      
      $objAssuntoBD = new AssuntoBD($this->getObjInfraIBanco());
      $ret = $objAssuntoBD->cadastrar($objAssuntoDTO);
      
      $this->montarIndexacao($ret);

      $objTabelaAssuntosDTO = new TabelaAssuntosDTO();
      $objTabelaAssuntosDTO->retStrSinAtual();
      $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($objAssuntoDTO->getNumIdTabelaAssuntos());

      $objTabelaAssuntosRN = new TabelaAssuntosRN();
      $objTabelaAssuntosDTO = $objTabelaAssuntosRN->consultar($objTabelaAssuntosDTO);

      if ($objTabelaAssuntosDTO->getStrSinAtual()=='S' && $objAssuntoDTO->getStrSinEstrutural()=='N') {

        $objAssuntoProxyDTO = new AssuntoProxyDTO();
        $objAssuntoProxyDTO->setNumIdAssuntoProxy(null);
        $objAssuntoProxyDTO->setNumIdAssunto($ret->getNumIdAssunto());

        $objAssuntoProxyRN = new AssuntoProxyRN();
        $objAssuntoProxyRN->cadastrar($objAssuntoProxyDTO);

      }

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Assunto.',$e);
    }
  }

  protected function alterarRN0260Controlado(AssuntoDTO $objAssuntoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('assunto_alterar',__METHOD__,$objAssuntoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objAssuntoDTOBanco = new AssuntoDTO();
      $objAssuntoDTOBanco->setBolExclusaoLogica(false);
      $objAssuntoDTOBanco->retNumIdTabelaAssuntos();
      $objAssuntoDTOBanco->retStrSinEstrutural();
      $objAssuntoDTOBanco->setNumIdAssunto($objAssuntoDTO->getNumIdAssunto());
      $objAssuntoDTOBanco = $this->consultarRN0256($objAssuntoDTOBanco);

      if ($objAssuntoDTOBanco==null){
        throw new InfraException('Assunto não encontrado ['.$objAssuntoDTO->getNumIdAssunto().'].');
      }

      if ($objAssuntoDTO->isSetNumIdTabelaAssuntos() && $objAssuntoDTO->getNumIdTabelaAssuntos()!=$objAssuntoDTOBanco->getNumIdTabelaAssuntos()){
        $objInfraException->lancarValidacao('Não é possível alterar a tabela associada com o assunto.');
      }else{
        $objAssuntoDTO->setNumIdTabelaAssuntos($objAssuntoDTOBanco->getNumIdTabelaAssuntos());
      }

      if ($objAssuntoDTO->isSetStrCodigoEstruturado()){
        $this->validarStrCodigoEstruturadoRN0250($objAssuntoDTO, $objInfraException);
      }

      if ($objAssuntoDTO->isSetStrDescricao()){
        $this->validarStrDescricaoRN0251($objAssuntoDTO, $objInfraException);
      }

      if ($objAssuntoDTO->isSetStrSinEstrutural() && $objAssuntoDTO->getStrSinEstrutural()!=$objAssuntoDTOBanco->getStrSinEstrutural()){
        $this->validarStrSinEstruturalRN0502($objAssuntoDTO, $objInfraException);

        $objAssuntoProxyDTO = new AssuntoProxyDTO();
        $objAssuntoProxyDTO->retNumIdAssuntoProxy();
        $objAssuntoProxyDTO->setNumIdAssunto($objAssuntoDTO->getNumIdAssunto());

        $objAssuntoProxyRN = new AssuntoProxyRN();
        $arrObjAssuntoProxyDTO = $objAssuntoProxyRN->listar($objAssuntoProxyDTO);

        if ($objAssuntoDTO->getStrSinEstrutural()=='N'){

          if (count($arrObjAssuntoProxyDTO)==0) {

            $objAssuntoProxyDTO = new AssuntoProxyDTO();
            $objAssuntoProxyDTO->setNumIdAssuntoProxy(null);
            $objAssuntoProxyDTO->setNumIdAssunto($objAssuntoDTO->getNumIdAssunto());

            $objAssuntoProxyRN = new AssuntoProxyRN();
            $objAssuntoProxyRN->cadastrar($objAssuntoProxyDTO);

          }
          
        }else{

          if (count($arrObjAssuntoProxyDTO)) {

            $arrIdAssuntoProxy = InfraArray::converterArrInfraDTO($arrObjAssuntoProxyDTO,'IdAssuntoProxy');

            $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
            $objRelProtocoloAssuntoDTO->setNumIdAssuntoProxy($arrIdAssuntoProxy, InfraDTO::$OPER_IN);

            $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
            $numRegistrosProtocolos = $objRelProtocoloAssuntoRN->contarRN0257($objRelProtocoloAssuntoDTO);
            if ($numRegistrosProtocolos) {
              $strMsgProtocolos = $numRegistrosProtocolos . ' ' . (($numRegistrosProtocolos == 1) ? 'protocolo associado' : 'protocolos associados').'\n';
            }

            $objRelTipoProcedimentoAssuntoDTO = new RelTipoProcedimentoAssuntoDTO();
            $objRelTipoProcedimentoAssuntoDTO->setNumIdAssuntoProxy($arrIdAssuntoProxy, InfraDTO::$OPER_IN);

            $objRelTipoProcedimentoAssuntoRN = new RelTipoProcedimentoAssuntoRN();
            $numRegistrosTiposProcedimento = $objRelTipoProcedimentoAssuntoRN->contarRN0287($objRelTipoProcedimentoAssuntoDTO);
            if ($numRegistrosTiposProcedimento) {
              $strMsgTiposProcedimento = $numRegistrosTiposProcedimento . ' ' . (($numRegistrosTiposProcedimento == 1) ? 'tipo de processo associado' : 'tipos de processo associados').'\n';
            }

            $objRelSerieAssuntoDTO = new RelSerieAssuntoDTO();
            $objRelSerieAssuntoDTO->setNumIdAssuntoProxy($arrIdAssuntoProxy, InfraDTO::$OPER_IN);

            $objRelSerieAssuntoRN = new RelSerieAssuntoRN();
            $numRegistrosTiposDocumento = $objRelSerieAssuntoRN->contar($objRelSerieAssuntoDTO);
            if ($numRegistrosTiposDocumento) {
              $strMsgTiposDocumento = $numRegistrosTiposDocumento . ' ' . (($numRegistrosTiposDocumento == 1) ? 'tipo de documento associado' : 'tipos de documento associados').'\n';
            }

            if ($numRegistrosProtocolos || $numRegistrosTiposProcedimento || $numRegistrosTiposDocumento) {
              $objInfraException->lancarValidacao('Não é possível tranformar o assunto em um item estrutural porque existe(m):\n' . $strMsgProtocolos . $strMsgTiposProcedimento . $strMsgTiposDocumento);
            }

            $objAssuntoProxyRN->excluir($arrObjAssuntoProxyDTO);
          }
        }

      }else{
        $objAssuntoDTO->setStrSinEstrutural($objAssuntoDTOBanco->getStrSinEstrutural());
      }

      if ($objAssuntoDTO->isSetNumPrazoCorrente()) {
        $this->validarNumPrazoCorrenteRN0496($objAssuntoDTO, $objInfraException);
      }

      if ($objAssuntoDTO->isSetNumPrazoIntermediario()) {
        $this->validarNumPrazoIntermediarioRN0499($objAssuntoDTO, $objInfraException);
      }

      if ($objAssuntoDTO->isSetStrStaDestinacao()){
        $this->validarStrStaDestinacao($objAssuntoDTO, $objInfraException);
      }

      if ($objAssuntoDTO->isSetStrObservacao()){
        $this->validarStrObservacaoRN0252($objAssuntoDTO, $objInfraException);
      }

      if ($objAssuntoDTO->isSetStrIdxAssunto()){
        $this->validarStrIdxAssuntoRN0504($objAssuntoDTO, $objInfraException);
      }

      if ($objAssuntoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivoRN0255($objAssuntoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAssuntoBD = new AssuntoBD($this->getObjInfraIBanco());
      $objAssuntoBD->alterar($objAssuntoDTO);
      
      $this->montarIndexacao($objAssuntoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Assunto.',$e);
    }
  }

  protected function excluirRN0248Controlado($arrObjAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_excluir',__METHOD__,$arrObjAssuntoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
      $objRelTipoProcedimentoAssuntoRN = new RelTipoProcedimentoAssuntoRN();
      $objRelSerieAssuntoRN = new RelSerieAssuntoRN();
      
      for ($i=0;$i<count($arrObjAssuntoDTO);$i++){
        
      	$dto = new AssuntoDTO();
      	$dto->setBolExclusaoLogica(false);
      	$dto->retStrCodigoEstruturado();
      	$dto->setNumIdAssunto($arrObjAssuntoDTO[$i]->getNumIdAssunto());
      	$dto = $this->consultarRN0256($dto);     	
      	
      	$strCodigoEstruturado = $dto->getStrCodigoEstruturado();
      	
        $dto = new RelProtocoloAssuntoDTO();
        $dto->retDblIdProtocolo();
        $dto->setNumIdAssunto($arrObjAssuntoDTO[$i]->getNumIdAssunto());
        $dto->setNumMaxRegistrosRetorno(1);
      	if ($objRelProtocoloAssuntoRN->consultar($dto)!=null){
      		$objInfraException->adicionarValidacao('Existem protocolos utilizando o assunto "'.$strCodigoEstruturado.'".');
      	}

        $dto = new RelTipoProcedimentoAssuntoDTO();
        $dto->retNumIdTipoProcedimento();
        $dto->setNumIdAssunto($arrObjAssuntoDTO[$i]->getNumIdAssunto());
        $dto->setNumMaxRegistrosRetorno(1);
      	if ($objRelTipoProcedimentoAssuntoRN->consultar($dto)!=null){
      		$objInfraException->adicionarValidacao('Existem tipos de processo utilizando o assunto "'.$strCodigoEstruturado.'".');
      	}

        $dto = new RelSerieAssuntoDTO();
        $dto->retNumIdSerie();
        $dto->setNumIdAssunto($arrObjAssuntoDTO[$i]->getNumIdAssunto());
        $dto->setNumMaxRegistrosRetorno(1);
        if ($objRelSerieAssuntoRN->consultar($dto)!=null){
          $objInfraException->adicionarValidacao('Existem tipos de documento utilizando o assunto "'.$strCodigoEstruturado.'".');
        }

      }
      
      $objInfraException->lancarValidacoes();

      $objAssuntoProxyRN = new AssuntoProxyRN();
      $objMapeamentoAssuntoRN = new MapeamentoAssuntoRN();
      $objAssuntoBD = new AssuntoBD($this->getObjInfraIBanco());

      for($i=0;$i<count($arrObjAssuntoDTO);$i++){

        $objAssuntoProxyDTO = new AssuntoProxyDTO();
        $objAssuntoProxyDTO->retNumIdAssuntoProxy();
        $objAssuntoProxyDTO->setNumIdAssunto($arrObjAssuntoDTO[$i]->getNumIdAssunto());
        $objAssuntoProxyRN->excluir($objAssuntoProxyRN->listar($objAssuntoProxyDTO));

        $objMapeamentoAssuntoDTO = new MapeamentoAssuntoDTO();
        $objMapeamentoAssuntoDTO->retNumIdAssuntoOrigem();
        $objMapeamentoAssuntoDTO->retNumIdAssuntoDestino();
        $objMapeamentoAssuntoDTO->adicionarCriterio(array('IdAssuntoOrigem','IdAssuntoDestino'),
                                                    array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
                                                    array($arrObjAssuntoDTO[$i]->getNumIdAssunto(),$arrObjAssuntoDTO[$i]->getNumIdAssunto()),
                                                    InfraDTO::$OPER_LOGICO_OR);

        $objMapeamentoAssuntoRN->excluir($objMapeamentoAssuntoRN->listar($objMapeamentoAssuntoDTO));
        
        $objAssuntoBD->excluir($arrObjAssuntoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Assunto.',$e);
    }
  }

  protected function consultarRN0256Conectado(AssuntoDTO $objAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_consultar',__METHOD__,$objAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoBD = new AssuntoBD($this->getObjInfraIBanco());
      $ret = $objAssuntoBD->consultar($objAssuntoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Assunto.',$e);
    }
  }

  protected function pesquisarRN0246Conectado(AssuntoDTO $objAssuntoDTO){
    try {

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoDTO = InfraString::prepararPesquisaDTO($objAssuntoDTO,"PalavrasPesquisa" ,"IdxAssunto",false);
  		//$objAssuntoDTO->setBolExclusaoLogica(false);
  		$arrAssuntoDTO =  $this->listarRN0247($objAssuntoDTO);

  		return $arrAssuntoDTO;
  		
      //Auditoria
    }catch(Exception $e){
      throw new InfraException('Erro pesquisando Assunto.',$e);
    }
  }  
  
  protected function listarRN0247Conectado(AssuntoDTO $objAssuntoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_listar',__METHOD__,$objAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoBD = new AssuntoBD($this->getObjInfraIBanco());
      $ret = $objAssuntoBD->listar($objAssuntoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Assuntos.',$e);
    }
  }

  protected function contarRN0249Conectado(AssuntoDTO $objAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_listar',__METHOD__,$objAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoBD = new AssuntoBD($this->getObjInfraIBanco());
      $ret = $objAssuntoBD->contar($objAssuntoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Assuntos.',$e);
    }
  }

  protected function desativarRN0258Controlado($arrObjAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_desativar',__METHOD__,$arrObjAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();
      
      //$objInfraException->lancarValidacoes();

      $objAssuntoBD = new AssuntoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAssuntoDTO);$i++){
        $objAssuntoBD->desativar($arrObjAssuntoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Assunto.',$e);
    }
  }

  protected function reativarRN0522Controlado($arrObjAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assunto_reativar',__METHOD__,$arrObjAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();
      
      //$objInfraException->lancarValidacoes();

      $objAssuntoBD = new AssuntoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAssuntoDTO);$i++){
        $objAssuntoBD->reativar($arrObjAssuntoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Assunto.',$e);
    }
  }

  private function validarNumIdTabelaAssuntos(AssuntoDTO $objAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssuntoDTO->getNumIdTabelaAssuntos())) {
      $objInfraException->lancarValidacao('Tabela de Assuntos não informada.');
    }
  }

  private function validarStrCodigoEstruturadoRN0250(AssuntoDTO $objAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssuntoDTO->getStrCodigoEstruturado())){
      $objInfraException->lancarValidacao('Código não informado.');
    }else{

      $objAssuntoDTO->setStrCodigoEstruturado(trim($objAssuntoDTO->getStrCodigoEstruturado()));
      
      if (strlen($objAssuntoDTO->getStrCodigoEstruturado())>50){
        $objInfraException->adicionarValidacao('Código possui tamanho superior a 50 caracteres.');
      }
      
      $dto = new AssuntoDTO();
      $dto->setNumIdTabelaAssuntos($objAssuntoDTO->getNumIdTabelaAssuntos());
      $dto->setNumIdAssunto($objAssuntoDTO->getNumIdAssunto(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrCodigoEstruturado($objAssuntoDTO->getStrCodigoEstruturado());
      if($this->contarRN0249($dto)>0){
      	$objInfraException->adicionarValidacao('Código já está sendo utilizado por outro assunto.');
      }

      $dto->setBolExclusaoLogica(false);
      $dto->setStrSinAtivo('N');
      if($this->contarRN0249($dto)>0){
      	$objInfraException->adicionarValidacao('Código já está sendo utilizado por outro assunto inativo.');
      }

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $strMascara = $objInfraParametro->getValor('SEI_MASCARA_ASSUNTO');

      if (!InfraString::isBolVazia($strMascara) && !InfraUtil::validarMascara($objAssuntoDTO->getStrCodigoEstruturado(),$strMascara,true)) {
        $objInfraException->adicionarValidacao('Código de assunto informado inválido ['.$objAssuntoDTO->getStrCodigoEstruturado().'].');
      }
    }
  }

  private function validarStrDescricaoRN0251(AssuntoDTO $objAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssuntoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objAssuntoDTO->setStrDescricao(trim($objAssuntoDTO->getStrDescricao()));
  
      if (strlen($objAssuntoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinEstruturalRN0502(AssuntoDTO $objAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssuntoDTO->getStrSinEstrutural())){
      $objInfraException->adicionarValidacao('Sinalizador de item apenas estrutural não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objAssuntoDTO->getStrSinEstrutural())){
        $objInfraException->adicionarValidacao('Sinalizador de item apenas estrutural inválido.');
      }
    }
  }

  private function validarNumPrazoCorrenteRN0496(AssuntoDTO $objAssuntoDTO, InfraException $objInfraException){
    if ($objAssuntoDTO->getStrSinEstrutural()=='N') {
      if (InfraString::isBolVazia($objAssuntoDTO->getNumPrazoCorrente())) {
        $objInfraException->adicionarValidacao('Prazo de guarda corrente não informado.');
      } else {
        if (!is_numeric($objAssuntoDTO->getNumPrazoCorrente())) {
          $objInfraException->adicionarValidacao('Prazo de guarda corrente inválido.');
        }
      }
    }
  }

  private function validarNumPrazoIntermediarioRN0499(AssuntoDTO $objAssuntoDTO, InfraException $objInfraException){
    if ($objAssuntoDTO->getStrSinEstrutural()=='N') {
      if (InfraString::isBolVazia($objAssuntoDTO->getNumPrazoIntermediario())) {
        $objInfraException->adicionarValidacao('Prazo de guarda intermediário não informado.');
      } else {
        if (!is_numeric($objAssuntoDTO->getNumPrazoIntermediario())) {
          $objInfraException->adicionarValidacao('Prazo de guarda intermediário inválido.');
        }
      }
    }
  }

  private function validarStrStaDestinacao(AssuntoDTO $objAssuntoDTO, InfraException $objInfraException){
    if ($objAssuntoDTO->getStrSinEstrutural()=='N') {
      if (InfraString::isBolVazia($objAssuntoDTO->getStrStaDestinacao())) {
        $objInfraException->adicionarValidacao('Destinação final não informada.');
      } else {
        if ($objAssuntoDTO->getStrStaDestinacao() != self::$TD_GUARDA_PERMANENTE && $objAssuntoDTO->getStrStaDestinacao() != self::$TD_ELIMINACAO) {
          $objInfraException->adicionarValidacao('Destinação final inválida.');
        }
      }
    }
  }

  private function validarStrObservacaoRN0252(AssuntoDTO $objAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssuntoDTO->getStrObservacao())){
      $objAssuntoDTO->setStrObservacao(null);
    }else {
      $objAssuntoDTO->setStrObservacao(trim($objAssuntoDTO->getStrObservacao()));

      if (strlen($objAssuntoDTO->getStrObservacao()) > 500) {
        $objInfraException->adicionarValidacao('Observação possui tamanho superior a 500 caracteres.');
      }
    }
  }

  private function validarStrIdxAssuntoRN0504(AssuntoDTO $objAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssuntoDTO->getStrIdxAssunto())){
      $objAssuntoDTO->setStrIdxAssunto(null);
    }else{
      $objAssuntoDTO->setStrIdxAssunto(trim($objAssuntoDTO->getStrIdxAssunto()));

      if (strlen($objAssuntoDTO->getStrIdxAssunto()) > 1000) {
        $objInfraException->adicionarValidacao('Indexação possui tamanho superior a 1000 caracteres.');
      }
    }
  }

  private function validarStrSinAtivoRN0255(AssuntoDTO $objAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssuntoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objAssuntoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }
  
  protected function montarIndexacaoControlado(AssuntoDTO $objAssuntoDTO){

    $dto = new AssuntoDTO();
    $dto->setBolExclusaoLogica(false);
    $dto->retNumIdAssunto();
  	$dto->retStrCodigoEstruturado();
  	$dto->retStrDescricao();
  	$dto->retStrObservacao();

  	if (is_array($objAssuntoDTO->getNumIdAssunto())) {
      $dto->setNumIdAssunto($objAssuntoDTO->getNumIdAssunto(), InfraDTO::$OPER_IN);
    }else{
      $dto->setNumIdAssunto($objAssuntoDTO->getNumIdAssunto());
    }

    $objInfraException = new InfraException();
    $objAssuntoDTOIdx = new AssuntoDTO();
    $objAssuntoBD = new AssuntoBD($this->getObjInfraIBanco());

    $arrObjAssuntoDTO = $this->listarRN0247($dto);

  	foreach($arrObjAssuntoDTO as $dto) {

      $objAssuntoDTOIdx->setNumIdAssunto($dto->getNumIdAssunto());
      $objAssuntoDTOIdx->setStrIdxAssunto($dto->getStrCodigoEstruturado().' '.InfraString::prepararIndexacao($dto->getStrCodigoEstruturado().' '.$dto->getStrDescricao().' '.$dto->getStrObservacao()));

      $this->validarStrIdxAssuntoRN0504($objAssuntoDTOIdx, $objInfraException);
      $objInfraException->lancarValidacoes();

      $objAssuntoBD->alterar($objAssuntoDTOIdx);
    }
  }
}
?>