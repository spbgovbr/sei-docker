<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2011 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ControleInternoRN extends InfraRN {

  public static $TO_GERAR_PROCEDIMENTO = '1';
  public static $TO_ALTERAR_PROCEDIMENTO = '2';
  public static $TO_GERAR_DOCUMENTO = '3';
  public static $TO_ALTERAR_DOCUMENTO = '4';
  public static $TO_EXCLUIR_DOCUMENTO = '5';
  public static $TO_MUDANCA_NIVEL_ACESSO = '6';
  public static $TO_ANEXAR_PROCEDIMENTO = '7';
  public static $TO_DESANEXAR_PROCEDIMENTO = '8';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }
  
  private function validarStrDescricao(ControleInternoDTO $objControleInternoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objControleInternoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objControleInternoDTO->setStrDescricao(trim($objControleInternoDTO->getStrDescricao()));

      if (strlen($objControleInternoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição do controle possui tamanho superior a 250 caracteres.');
      }
    }
  }
  
  private function validarArrObjRelControleInternoUnidade(ControleInternoDTO $objControleInternoDTO, InfraException $objInfraException){
  	if (InfraArray::contar($objControleInternoDTO->getArrObjRelControleInternoUnidade())==0){
  		$objInfraException->adicionarValidacao('Nenhuma unidade de controle informada.');
  	}
  }

  private function validarTiposProcedimentoSeries(ControleInternoDTO $objControleInternoDTO, InfraException $objInfraException){
  	if (InfraArray::contar($objControleInternoDTO->getArrObjRelControleInternoTipoProc())==0 && InfraArray::contar($objControleInternoDTO->getArrObjRelControleInternoSerie())==0){
  		$objInfraException->adicionarValidacao('Nenhum tipo de procedimento ou documento informados para controle.');
  	}
  }

  private function validarArrObjRelControleInternoOrgao(ControleInternoDTO $objControleInternoDTO, InfraException $objInfraException){
  	if (InfraArray::contar($objControleInternoDTO->getArrObjRelControleInternoOrgao())==0){
  		$objInfraException->adicionarValidacao('Nenhum órgão para controle informado.');
  	}
  }
  
  public function cadastrar(ControleInternoDTO $objControleInternoDTO) {

    LimiteSEI::getInstance()->configurarNivel3();

    $ret = $this->cadastrarInterno($objControleInternoDTO);

    $this->aplicarCriterio($objControleInternoDTO);

    $arrCorBarraProgresso=array('cor_fundo'=>'#5c9ccc','cor_borda'=>'#4297d7');
    $prb = InfraBarraProgresso2::newInstance('Controle Interno',$arrCorBarraProgresso);
    $prb->setStrRotulo('Indexando processos...');

    $objAcessoDTO = new AcessoDTO();
    $objAcessoDTO->setDistinct(true);
    $objAcessoDTO->retDblIdProtocolo();
    $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
    $objAcessoDTO->setNumIdControleInterno($ret->getNumIdControleInterno());

    $objAcessoRN = new AcessoRN();
    $arrIdProtocolosIndexacao = InfraArray::converterArrInfraDTO($objAcessoRN->listar($objAcessoDTO),'IdProtocolo');

    $numRegistros	=	count($arrIdProtocolosIndexacao);
    $numRegistrosPagina = 50;
    $numPaginas = ceil($numRegistros/$numRegistrosPagina);

    $prb->setNumMin(0);
    $prb->setNumMax($numPaginas);

    $objIndexacaoRN = new IndexacaoRN();

    for ($numPaginaAtual = $prb->getNumMin(); $numPaginaAtual < $prb->getNumMax(); $numPaginaAtual++){

      if ($numPaginaAtual ==  ($prb->getNumMax()-1)){
        $numAtual = $numRegistros;
      }else{
        $numAtual = ($numPaginaAtual+1)*$numRegistrosPagina;
      }

      $prb->setStrRotulo('Indexando processos '.$numAtual.' de '.$numRegistros.'...');
      $prb->moverProximo();

      $objIndexacaoDTO = new IndexacaoDTO();
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROCESSO_COM_DOCUMENTOS_ACESSO);
      $objIndexacaoDTO->setArrIdProtocolos(array_slice($arrIdProtocolosIndexacao, ($numPaginaAtual*$numRegistrosPagina), $numRegistrosPagina));
      $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);
    }

    $prb->setStrRotulo('Indexando processos '.$numAtual.' de '.$numRegistros.'...finalizado.');
    sleep(1);

    return $ret;
  }
  
  protected function cadastrarInternoControlado(ControleInternoDTO $objControleInternoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_interno_cadastrar',__METHOD__,$objControleInternoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      $this->validarStrDescricao($objControleInternoDTO, $objInfraException);
      $this->validarArrObjRelControleInternoUnidade($objControleInternoDTO, $objInfraException);
      $this->validarArrObjRelControleInternoOrgao($objControleInternoDTO, $objInfraException);
      $this->validarTiposProcedimentoSeries($objControleInternoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objControleInternoBD = new ControleInternoBD($this->getObjInfraIBanco());
      $ret = $objControleInternoBD->cadastrar($objControleInternoDTO);

    	$arrUnidades = $objControleInternoDTO->getArrObjRelControleInternoUnidade();
    	
    	foreach ($arrUnidades as $numIdUnidade) {
      	$objRelControleInternoUnidadeRN = new RelControleInternoUnidadeRN();
				$objRelControleInternoUnidadeDTO = new RelControleInternoUnidadeDTO();
				$objRelControleInternoUnidadeDTO->setNumIdUnidade($numIdUnidade);
				$objRelControleInternoUnidadeDTO->setNumIdControleInterno($ret->getNumIdControleInterno());
				$objRelControleInternoUnidadeRN->cadastrar($objRelControleInternoUnidadeDTO);
    	}
      
    	$arrOrgaos = $objControleInternoDTO->getArrObjRelControleInternoOrgao();
    	
    	foreach ($arrOrgaos as $numIdOrgao) {
      	$objRelControleInternoOrgaoRN = new RelControleInternoOrgaoRN();
				$objRelControleInternoOrgaoDTO = new RelControleInternoOrgaoDTO();
				$objRelControleInternoOrgaoDTO->setNumIdOrgao($numIdOrgao);
				$objRelControleInternoOrgaoDTO->setNumIdControleInterno($ret->getNumIdControleInterno());
				$objRelControleInternoOrgaoRN->cadastrar($objRelControleInternoOrgaoDTO);
    	}

    	$arrTiposProcedimento = $objControleInternoDTO->getArrObjRelControleInternoTipoProc();
    	
    	foreach ($arrTiposProcedimento as $numIdTipoProcedimento) {
      	$objRelControleInternoTipoProcRN = new RelControleInternoTipoProcRN();
				$objRelControleInternoTipoProcDTO = new RelControleInternoTipoProcDTO();
				$objRelControleInternoTipoProcDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
				$objRelControleInternoTipoProcDTO->setNumIdControleInterno($ret->getNumIdControleInterno());
				$objRelControleInternoTipoProcRN->cadastrar($objRelControleInternoTipoProcDTO);
    	}
      	
    	$arrSeries = $objControleInternoDTO->getArrObjRelControleInternoSerie();
    	
    	foreach ($arrSeries as $numIdSerie) {
      	$objRelControleInternoSerieRN = new RelControleInternoSerieRN();
				$objRelControleInternoSerieDTO = new RelControleInternoSerieDTO();
				$objRelControleInternoSerieDTO->setNumIdSerie($numIdSerie);
				$objRelControleInternoSerieDTO->setNumIdControleInterno($ret->getNumIdControleInterno());
				$objRelControleInternoSerieRN->cadastrar($objRelControleInternoSerieDTO);
    	}

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Controle Interno.',$e);
    }
  }

  public function alterar(ControleInternoDTO $objControleInternoDTO){

    LimiteSEI::getInstance()->configurarNivel3();

    $objAcessoRN = new AcessoRN();

    $objAcessoDTO = new AcessoDTO();
    $objAcessoDTO->setDistinct(true);
    $objAcessoDTO->retDblIdProtocolo();
    $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
    $objAcessoDTO->setNumIdControleInterno($objControleInternoDTO->getNumIdControleInterno());
    $arrObjAcessoDTO = $objAcessoRN->listar($objAcessoDTO);

    $arrIdProtocolosIndexacao = array();
    foreach($arrObjAcessoDTO as $objAcessoDTO){
      $arrIdProtocolosIndexacao[$objAcessoDTO->getDblIdProtocolo()] = 0;
    }

    unset($arrObjAcessoDTO);

    $this->alterarInterno($objControleInternoDTO);

    $this->aplicarCriterio($objControleInternoDTO);

    $objAcessoDTO = new AcessoDTO();
    $objAcessoDTO->setDistinct(true);
    $objAcessoDTO->retDblIdProtocolo();
    $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
    $objAcessoDTO->setNumIdControleInterno($objControleInternoDTO->getNumIdControleInterno());
    $arrObjAcessoDTO = $objAcessoRN->listar($objAcessoDTO);

    foreach($arrObjAcessoDTO as $objAcessoDTO){
      $arrIdProtocolosIndexacao[$objAcessoDTO->getDblIdProtocolo()] = 0;
    }

    unset($arrObjAcessoDTO);

    $arrCorBarraProgresso=array('cor_fundo'=>'#5c9ccc','cor_borda'=>'#4297d7');
    $prb = InfraBarraProgresso2::newInstance('Controle Interno',$arrCorBarraProgresso);
    $prb->setStrRotulo('Indexando processos...');

    $arrIdProtocolosIndexacao = array_keys($arrIdProtocolosIndexacao);

    $numRegistros	=	InfraArray::contar($arrIdProtocolosIndexacao);
    $numRegistrosPagina = 50;
    $numPaginas = ceil($numRegistros/$numRegistrosPagina);

    $prb->setNumMin(0);
    $prb->setNumMax($numPaginas);

    $objIndexacaoRN = new IndexacaoRN();

    for ($numPaginaAtual = $prb->getNumMin(); $numPaginaAtual < $prb->getNumMax(); $numPaginaAtual++){

      if ($numPaginaAtual ==  ($prb->getNumMax()-1)){
        $numAtual = $numRegistros;
      }else{
        $numAtual = ($numPaginaAtual+1)*$numRegistrosPagina;
      }

      $prb->setStrRotulo('Indexando processos '.$numAtual.' de '.$numRegistros.'...');
      $prb->moverProximo();

      $objIndexacaoDTO = new IndexacaoDTO();
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROCESSO_COM_DOCUMENTOS_ACESSO);
      $objIndexacaoDTO->setArrIdProtocolos(array_slice($arrIdProtocolosIndexacao, ($numPaginaAtual*$numRegistrosPagina), $numRegistrosPagina));
      $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);
    }

    $prb->setStrRotulo('Indexando processos '.$numAtual.' de '.$numRegistros.'...finalizado.');
    sleep(1);
  }

  protected function alterarInternoControlado(ControleInternoDTO $objControleInternoDTO){
    try {

      //Valida Permissao
  	  SessaoSEI::getInstance()->validarAuditarPermissao('controle_interno_alterar',__METHOD__,$objControleInternoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrDescricao($objControleInternoDTO, $objInfraException);
      $this->validarArrObjRelControleInternoUnidade($objControleInternoDTO, $objInfraException);
      $this->validarArrObjRelControleInternoOrgao($objControleInternoDTO, $objInfraException);
      $this->validarTiposProcedimentoSeries($objControleInternoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objControleInternoBD = new ControleInternoBD($this->getObjInfraIBanco());
      $objControleInternoBD->alterar($objControleInternoDTO);

      $objRelControleInternoUnidadeDTO = new RelControleInternoUnidadeDTO();
      $objRelControleInternoUnidadeDTO->retNumIdControleInterno();
      $objRelControleInternoUnidadeDTO->retNumIdUnidade();
      $objRelControleInternoUnidadeDTO->setNumIdControleInterno($objControleInternoDTO->getNumIdControleInterno());

      $objRelControleInternoUnidadeRN = new RelControleInternoUnidadeRN();
      $objRelControleInternoUnidadeRN->excluir($objRelControleInternoUnidadeRN->listar($objRelControleInternoUnidadeDTO));

      $arrUnidades = $objControleInternoDTO->getArrObjRelControleInternoUnidade();

      foreach ($arrUnidades as $numIdUnidade) {
        $objRelControleInternoUnidadeDTO = new RelControleInternoUnidadeDTO();
        $objRelControleInternoUnidadeDTO->setNumIdUnidade($numIdUnidade);
        $objRelControleInternoUnidadeDTO->setNumIdControleInterno($objControleInternoDTO->getNumIdControleInterno());
        $objRelControleInternoUnidadeRN->cadastrar($objRelControleInternoUnidadeDTO);
      }


      $objRelControleInternoOrgaoDTO = new RelControleInternoOrgaoDTO();
      $objRelControleInternoOrgaoDTO->retNumIdControleInterno();
      $objRelControleInternoOrgaoDTO->retNumIdOrgao();
      $objRelControleInternoOrgaoDTO->setNumIdControleInterno($objControleInternoDTO->getNumIdControleInterno());

      $objRelControleInternoOrgaoRN = new RelControleInternoOrgaoRN();
      $objRelControleInternoOrgaoRN->excluir($objRelControleInternoOrgaoRN->listar($objRelControleInternoOrgaoDTO));

      $arrOrgaos = $objControleInternoDTO->getArrObjRelControleInternoOrgao();

      foreach ($arrOrgaos as $numIdOrgao) {
        $objRelControleInternoOrgaoDTO = new RelControleInternoOrgaoDTO();
        $objRelControleInternoOrgaoDTO->setNumIdOrgao($numIdOrgao);
        $objRelControleInternoOrgaoDTO->setNumIdControleInterno($objControleInternoDTO->getNumIdControleInterno());
        $objRelControleInternoOrgaoRN->cadastrar($objRelControleInternoOrgaoDTO);
      }

      $objRelControleInternoTipoProcDTO = new RelControleInternoTipoProcDTO();
      $objRelControleInternoTipoProcDTO->retNumIdControleInterno();
      $objRelControleInternoTipoProcDTO->retNumIdTipoProcedimento();
      $objRelControleInternoTipoProcDTO->setNumIdControleInterno($objControleInternoDTO->getNumIdControleInterno());

      $objRelControleInternoTipoProcRN = new RelControleInternoTipoProcRN();
      $objRelControleInternoTipoProcRN->excluir($objRelControleInternoTipoProcRN->listar($objRelControleInternoTipoProcDTO));

      $arrTiposProcedimento = $objControleInternoDTO->getArrObjRelControleInternoTipoProc();

      foreach ($arrTiposProcedimento as $numIdTipoProcedimento) {
        $objRelControleInternoTipoProcDTO = new RelControleInternoTipoProcDTO();
        $objRelControleInternoTipoProcDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
        $objRelControleInternoTipoProcDTO->setNumIdControleInterno($objControleInternoDTO->getNumIdControleInterno());
        $objRelControleInternoTipoProcRN->cadastrar($objRelControleInternoTipoProcDTO);
      }

      $objRelControleInternoSerieDTO = new RelControleInternoSerieDTO();
      $objRelControleInternoSerieDTO->retNumIdControleInterno();
      $objRelControleInternoSerieDTO->retNumIdSerie();
      $objRelControleInternoSerieDTO->setNumIdControleInterno($objControleInternoDTO->getNumIdControleInterno());

      $objRelControleInternoSerieRN = new RelControleInternoSerieRN();
      $objRelControleInternoSerieRN->excluir($objRelControleInternoSerieRN->listar($objRelControleInternoSerieDTO));

      $arrSeries = $objControleInternoDTO->getArrObjRelControleInternoSerie();

      foreach ($arrSeries as $numIdSerie) {
        $objRelControleInternoSerieDTO = new RelControleInternoSerieDTO();
        $objRelControleInternoSerieDTO->setNumIdSerie($numIdSerie);
        $objRelControleInternoSerieDTO->setNumIdControleInterno($objControleInternoDTO->getNumIdControleInterno());
        $objRelControleInternoSerieRN->cadastrar($objRelControleInternoSerieDTO);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Controle Interno.',$e);
    }
  }

  public function excluir($arrObjControleInternoDTO){

    LimiteSEI::getInstance()->configurarNivel3();

    $arrCorBarraProgresso=array('cor_fundo'=>'#5c9ccc','cor_borda'=>'#4297d7');
    $prb = InfraBarraProgresso2::newInstance('Controle Interno',$arrCorBarraProgresso);
    $prb->setStrRotulo('Removendo critério...');

    if (count($arrObjControleInternoDTO)) {

      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->setDistinct(true);
      $objAcessoDTO->retDblIdProtocolo();
      $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
      $objAcessoDTO->setNumIdControleInterno(InfraArray::converterArrInfraDTO($arrObjControleInternoDTO,'IdControleInterno'),InfraDTO::$OPER_IN);

      $objAcessoRN = new AcessoRN();
      $arrIdProtocolosIndexacao = InfraArray::converterArrInfraDTO($objAcessoRN->listar($objAcessoDTO),'IdProtocolo');

      $this->excluirInterno($arrObjControleInternoDTO);

      $numRegistros	=	count($arrIdProtocolosIndexacao);
      $numRegistrosPagina = 50;
      $numPaginas = ceil($numRegistros/$numRegistrosPagina);

      $prb->setNumMin(0);
      $prb->setNumMax($numPaginas);

      $objIndexacaoRN = new IndexacaoRN();

      for ($numPaginaAtual = $prb->getNumMin(); $numPaginaAtual < $prb->getNumMax(); $numPaginaAtual++){

        if ($numPaginaAtual ==  ($prb->getNumMax()-1)){
          $numAtual = $numRegistros;
        }else{
          $numAtual = ($numPaginaAtual+1)*$numRegistrosPagina;
        }

        $prb->setStrRotulo('Indexando processos '.$numAtual.' de '.$numRegistros.'...');
        $prb->moverProximo();

        $objIndexacaoDTO = new IndexacaoDTO();
        $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROCESSO_COM_DOCUMENTOS_ACESSO);
        $objIndexacaoDTO->setArrIdProtocolos(array_slice($arrIdProtocolosIndexacao, ($numPaginaAtual*$numRegistrosPagina), $numRegistrosPagina));
        $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);
      }

      $prb->setStrRotulo('Indexando processos '.$numAtual.' de '.$numRegistros.'...finalizado.');
      sleep(1);
    }
  }

  protected function excluirInternoControlado($arrObjControleInternoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_interno_excluir',__METHOD__,$arrObjControleInternoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();
      
      //$objInfraException->lancarValidacoes();

      $objRelControleInternoUnidadeRN = new RelControleInternoUnidadeRN();
      $objRelControleInternoOrgaoRN = new RelControleInternoOrgaoRN();
      $objRelControleInternoTipoProcRN = new RelControleInternoTipoProcRN();
      $objRelControleInternoSerieRN = new RelControleInternoSerieRN();
      $objAcessoRN = new AcessoRN();

      $objControleInternoBD = new ControleInternoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjControleInternoDTO);$i++){
      	
				$objRelControleInternoUnidadeDTO = new RelControleInternoUnidadeDTO();
				$objRelControleInternoUnidadeDTO->setNumIdControleInterno($arrObjControleInternoDTO[$i]->getNumIdControleInterno());
				$objRelControleInternoUnidadeDTO->retNumIdControleInterno();
				$objRelControleInternoUnidadeDTO->retNumIdUnidade();
				$objRelControleInternoUnidadeRN->excluir($objRelControleInternoUnidadeRN->listar($objRelControleInternoUnidadeDTO));
				
				$objRelControleInternoOrgaoDTO = new RelControleInternoOrgaoDTO();
				$objRelControleInternoOrgaoDTO->setNumIdControleInterno($arrObjControleInternoDTO[$i]->getNumIdControleInterno());
				$objRelControleInternoOrgaoDTO->retNumIdControleInterno();
				$objRelControleInternoOrgaoDTO->retNumIdOrgao();
				$objRelControleInternoOrgaoRN->excluir($objRelControleInternoOrgaoRN->listar($objRelControleInternoOrgaoDTO));

				$objRelControleInternoTipoProcDTO = new RelControleInternoTipoProcDTO();
				$objRelControleInternoTipoProcDTO->setNumIdControleInterno($arrObjControleInternoDTO[$i]->getNumIdControleInterno());
				$objRelControleInternoTipoProcDTO->retNumIdControleInterno();
				$objRelControleInternoTipoProcDTO->retNumIdTipoProcedimento();
				$objRelControleInternoTipoProcRN->excluir($objRelControleInternoTipoProcRN->listar($objRelControleInternoTipoProcDTO));
				
				$objRelControleInternoSerieDTO = new RelControleInternoSerieDTO();
				$objRelControleInternoSerieDTO->setNumIdControleInterno($arrObjControleInternoDTO[$i]->getNumIdControleInterno());
				$objRelControleInternoSerieDTO->retNumIdControleInterno();
				$objRelControleInternoSerieDTO->retNumIdSerie();
				$objRelControleInternoSerieRN->excluir($objRelControleInternoSerieRN->listar($objRelControleInternoSerieDTO));

        $objAcessoDTO = new AcessoDTO();
        $objAcessoDTO->setNumIdControleInterno($arrObjControleInternoDTO[$i]->getNumIdControleInterno());
        $objAcessoRN->excluirControleInterno($objAcessoDTO);

        $objControleInternoBD->excluir($arrObjControleInternoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Controle Interno.',$e);
    }
  }

  protected function consultarConectado(ControleInternoDTO $objControleInternoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_interno_consultar',__METHOD__,$objControleInternoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControleInternoBD = new ControleInternoBD($this->getObjInfraIBanco());
      $ret = $objControleInternoBD->consultar($objControleInternoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Controle Interno.',$e);
    }
  }

  protected function listarConectado(ControleInternoDTO $objControleInternoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_interno_listar',__METHOD__,$objControleInternoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControleInternoBD = new ControleInternoBD($this->getObjInfraIBanco());
      $ret = $objControleInternoBD->listar($objControleInternoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Controles Internos.',$e);
    }
  }

  protected function contarConectado(ControleInternoDTO $objControleInternoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_interno_listar',__METHOD__,$objControleInternoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControleInternoBD = new ControleInternoBD($this->getObjInfraIBanco());
      $ret = $objControleInternoBD->contar($objControleInternoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Controles Internos.',$e);
    }
  }

/* 
  protected function desativarControlado($arrObjControleInternoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_interno_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControleInternoBD = new ControleInternoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjControleInternoDTO);$i++){
        $objControleInternoBD->desativar($arrObjControleInternoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Controle Interno.',$e);
    }
  }

  protected function reativarControlado($arrObjControleInternoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_interno_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControleInternoBD = new ControleInternoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjControleInternoDTO);$i++){
        $objControleInternoBD->reativar($arrObjControleInternoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Controle Interno.',$e);
    }
  }

  protected function bloquearControlado(ControleInternoDTO $objControleInternoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_interno_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControleInternoBD = new ControleInternoBD($this->getObjInfraIBanco());
      $ret = $objControleInternoBD->bloquear($objControleInternoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Controle Interno.',$e);
    }
  }
 */

  protected function aplicarCriterioConectado(ControleInternoDTO $parObjControleInternoDTO){

    try{

      $arrCorBarraProgresso = array('cor_fundo'=>'#ff0000','cor_borda'=>'#4297d7');

      $objAcessoRN = new AcessoRN();
      $objProcedimentoRN = new ProcedimentoRN();
      $objDocumentoRN = new DocumentoRN();
      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();

      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->retNumIdAcesso();
      $objAcessoDTO->setNumIdControleInterno($parObjControleInternoDTO->getNumIdControleInterno());

      $arrIdAcessoReutilizacao = InfraArray::converterArrInfraDTO($objAcessoRN->listar($objAcessoDTO),'IdAcesso');

      $objAcessoRN->excluirControleInterno($objAcessoDTO);

      $objControleInternoDTO = new ControleInternoDTO();
      $objControleInternoDTO->retNumIdUnidadeControle();
      $objControleInternoDTO->retNumIdOrgaoControlado();
      $objControleInternoDTO->retNumIdTipoProcedimentoControlado();
      $objControleInternoDTO->setNumIdControleInterno($parObjControleInternoDTO->getNumIdControleInterno());
      $objControleInternoDTO->setNumIdTipoProcedimentoControlado(null, InfraDTO::$OPER_DIFERENTE);

      $objControleInternoRN = new ControleInternoRN();
      $arrObjControleInternoDTO = $objControleInternoRN->listar($objControleInternoDTO);

      $arrProcessosControleInterno = array();

      if (count($arrObjControleInternoDTO)) {

        $prb = InfraBarraProgresso2::newInstance('Controle Interno Processos',$arrCorBarraProgresso);
        $prb->setStrRotulo('Verificando tipos de processo...');
        $prb->setNumMin(0);
        $prb->setNumMax(count($arrObjControleInternoDTO));

        $arrObjControleInternoDTO = InfraArray::indexarArrInfraDTO($arrObjControleInternoDTO, 'IdUnidadeControle', true);

        foreach ($arrObjControleInternoDTO as $numIdUnidade => $arrObjControleInternoDTOUnidade) {

          foreach ($arrObjControleInternoDTOUnidade as $objControleInternoDTO) {
            sleep(1);
            $prb->moverProximo();

            $objProcedimentoDTO = new ProcedimentoDTO();
            $objProcedimentoDTO->retDblIdProcedimento();
            $objProcedimentoDTO->setNumIdTipoProcedimento($objControleInternoDTO->getNumIdTipoProcedimentoControlado());
            $objProcedimentoDTO->setNumIdOrgaoUnidadeGeradoraProtocolo($objControleInternoDTO->getNumIdOrgaoControlado());
            $objProcedimentoDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_RESTRITO);
            $arrIdProcedimentoPartes = array_chunk(InfraArray::converterArrInfraDTO($objProcedimentoRN->listarRN0278($objProcedimentoDTO), 'IdProcedimento'), 100);

            foreach ($arrIdProcedimentoPartes as $arrIdProcedimento) {
              $arrObjAcessoDTO = array();
              foreach ($arrIdProcedimento as $dblIdProcedimento) {
                $objAcessoDTO = new AcessoDTO();
                $objAcessoDTO->setNumIdAcesso(array_pop($arrIdAcessoReutilizacao));
                $objAcessoDTO->setNumIdUnidade($numIdUnidade);
                $objAcessoDTO->setNumIdUsuario(null);
                $objAcessoDTO->setDblIdProtocolo($dblIdProcedimento);
                $objAcessoDTO->setNumIdControleInterno($parObjControleInternoDTO->getNumIdControleInterno());
                $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
                $arrObjAcessoDTO[] = $objAcessoDTO;

                $arrProcessosControleInterno[$dblIdProcedimento][$numIdUnidade] = 0;
              }
              $objAcessoRN->cadastrarMultiplo($arrObjAcessoDTO);
            }
          }
        }
        $prb->setStrRotulo('Verificando tipos de processo...finalizado.');
        sleep(1);
      }

      $objControleInternoDTO = new ControleInternoDTO();
      $objControleInternoDTO->retNumIdUnidadeControle();
      $objControleInternoDTO->retNumIdOrgaoControlado();
      $objControleInternoDTO->retNumIdSerieControlada();
      $objControleInternoDTO->setNumIdControleInterno($parObjControleInternoDTO->getNumIdControleInterno());
      $objControleInternoDTO->setNumIdSerieControlada(null, InfraDTO::$OPER_DIFERENTE);

      $objControleInternoRN = new ControleInternoRN();
      $arrObjControleInternoDTO = $objControleInternoRN->listar($objControleInternoDTO);

      if (count($arrObjControleInternoDTO)) {

        $prb = InfraBarraProgresso2::newInstance('Controle Interno Documentos',$arrCorBarraProgresso);
        $prb->setStrRotulo('Verificando tipos de documento...');
        $prb->setNumMin(0);
        $prb->setNumMax(count($arrObjControleInternoDTO));

        $arrObjControleInternoDTO = InfraArray::indexarArrInfraDTO($arrObjControleInternoDTO, 'IdUnidadeControle', true);

        foreach ($arrObjControleInternoDTO as $numIdUnidade => $arrObjControleInternoDTOUnidade) {

          foreach ($arrObjControleInternoDTOUnidade as $objControleInternoDTO) {

            $prb->moverProximo();

            $objDocumentoDTO = new DocumentoDTO();
            $objDocumentoDTO->setDistinct(true);
            $objDocumentoDTO->retDblIdProcedimento();
            $objDocumentoDTO->setNumIdSerie($objControleInternoDTO->getNumIdSerieControlada());
            $objDocumentoDTO->setNumIdOrgaoUnidadeGeradoraProtocolo($objControleInternoDTO->getNumIdOrgaoControlado());
            $objDocumentoDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_RESTRITO);
            $arrIdProcedimentoPartes = array_chunk(InfraArray::converterArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO), 'IdProcedimento'), 100);

            foreach ($arrIdProcedimentoPartes as $arrIdProcedimento) {
              $arrObjAcessoDTO = array();
              foreach ($arrIdProcedimento as $dblIdProcedimento) {
                if (!isset($arrProcessosControleInterno[$dblIdProcedimento][$numIdUnidade])) {
                  $objAcessoDTO = new AcessoDTO();
                  $objAcessoDTO->setNumIdAcesso(array_pop($arrIdAcessoReutilizacao));
                  $objAcessoDTO->setNumIdUnidade($numIdUnidade);
                  $objAcessoDTO->setNumIdUsuario(null);
                  $objAcessoDTO->setDblIdProtocolo($dblIdProcedimento);
                  $objAcessoDTO->setNumIdControleInterno($parObjControleInternoDTO->getNumIdControleInterno());
                  $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
                  $arrObjAcessoDTO[] = $objAcessoDTO;

                  $arrProcessosControleInterno[$dblIdProcedimento][$numIdUnidade] = 0;
                }
              }
              $objAcessoRN->cadastrarMultiplo($arrObjAcessoDTO);
            }
          }
        }
        $prb->setStrRotulo('Verificando tipos de documento...finalizado.');
        sleep(1);
      }

      if (count($arrProcessosControleInterno)) {

        $prb = InfraBarraProgresso2::newInstance('Controle Interno Anexados',$arrCorBarraProgresso);
        $prb->setStrRotulo('Verificando processos anexados...');
        $prb->setNumMin(0);
        $prb->setNumMax(count($arrProcessosControleInterno));

        foreach ($arrProcessosControleInterno as $dblIdProcedimento => $arrIdUnidades) {

          $prb->moverProximo();

          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
          $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($dblIdProcedimento);
          $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

          if ($objRelProtocoloProtocoloDTO != null) {
            $dblIdProcessoPai = $objRelProtocoloProtocoloDTO->getDblIdProtocolo1();
          } else {
            $dblIdProcessoPai = $dblIdProcedimento;
          }

          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
          $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($dblIdProcessoPai);

          $arrIdProcessos = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO), 'IdProtocolo2');
          $arrIdProcessos[] = $dblIdProcessoPai;

          $arrObjAcessoDTO = array();
          foreach ($arrIdProcessos as $dblIdProcessosAnexosOuAnexados) {
            foreach (array_keys($arrIdUnidades) as $numIdUnidade) {
              if (!isset($arrProcessosControleInterno[$dblIdProcessosAnexosOuAnexados][$numIdUnidade])) {
                $objAcessoDTO = new AcessoDTO();
                $objAcessoDTO->setNumIdAcesso(array_pop($arrIdAcessoReutilizacao));
                $objAcessoDTO->setNumIdUnidade($numIdUnidade);
                $objAcessoDTO->setNumIdUsuario(null);
                $objAcessoDTO->setDblIdProtocolo($dblIdProcessosAnexosOuAnexados);
                $objAcessoDTO->setNumIdControleInterno($parObjControleInternoDTO->getNumIdControleInterno());
                $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
                $arrObjAcessoDTO[] = $objAcessoDTO;
                $arrProcessosControleInterno[$dblIdProcessosAnexosOuAnexados][$numIdUnidade] = 0;
              }
            }
          }
          $objAcessoRN->cadastrarMultiplo($arrObjAcessoDTO);
        }
        $prb->setStrRotulo('Verificando processos anexados...finalizado.');
        sleep(1);
      }

    }catch(Exception $e){
      throw new InfraException('Erro aplicando critério de controle interno.',$e);
    }
  }

  protected function processarControlado(ControleInternoDTO $parObjControleInternoDTO){
    try{

      $bolIndexacao = false;
      $arrObjProcedimentoDTO = null;
      $arrObjDocumentoDTO = null;

      if (!$parObjControleInternoDTO->isSetStrStaNivelAcessoGlobal()) {
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retStrStaNivelAcessoGlobal();
        $objProtocoloDTO->setDblIdProtocolo($parObjControleInternoDTO->getDblIdProcedimento());

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
        $parObjControleInternoDTO->setStrStaNivelAcessoGlobal($objProtocoloDTO->getStrStaNivelAcessoGlobal());
      }

      if ($parObjControleInternoDTO->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_RESTRITO) {

        $objAcessoRN = new AcessoRN();

        //verificar se o acesso era devido ao tipo de processo ou tipo de documento que foi alterado/excluido e neste caso remover registros de acesso
        if ($parObjControleInternoDTO->getStrStaOperacao() == self::$TO_ALTERAR_PROCEDIMENTO ||
            $parObjControleInternoDTO->getStrStaOperacao() == self::$TO_ALTERAR_DOCUMENTO ||
            $parObjControleInternoDTO->getStrStaOperacao() == self::$TO_EXCLUIR_DOCUMENTO ||
            $parObjControleInternoDTO->getStrStaOperacao() == self::$TO_DESANEXAR_PROCEDIMENTO) {

          $bolProcessarExclusao = true;

          if ($parObjControleInternoDTO->getStrStaOperacao() == self::$TO_ALTERAR_PROCEDIMENTO) {
            $objControleInternoDTO = new ControleInternoDTO();
            $objControleInternoDTO->retNumIdControleInterno();
            $objControleInternoDTO->setNumIdTipoProcedimentoControlado($parObjControleInternoDTO->getNumIdTipoProcedimentoAnterior());
            $objControleInternoDTO->setNumIdOrgaoControlado($parObjControleInternoDTO->getNumIdOrgao());
            $objControleInternoDTO->setNumMaxRegistrosRetorno(1);

            if ($this->consultar($objControleInternoDTO) == null) {
              $bolProcessarExclusao = false;
            }

          }else if ($parObjControleInternoDTO->getStrStaOperacao() == self::$TO_ALTERAR_DOCUMENTO) {

            $objControleInternoDTO = new ControleInternoDTO();
            $objControleInternoDTO->retNumIdControleInterno();
            $objControleInternoDTO->setNumIdSerieControlada($parObjControleInternoDTO->getNumIdSerieAnterior());
            $objControleInternoDTO->setNumIdOrgaoControlado($parObjControleInternoDTO->getNumIdOrgao());
            $objControleInternoDTO->setNumMaxRegistrosRetorno(1);

            if ($this->consultar($objControleInternoDTO) == null) {
              $bolProcessarExclusao = false;
            }

          }else if ($parObjControleInternoDTO->getStrStaOperacao() == self::$TO_EXCLUIR_DOCUMENTO) {

            $objControleInternoDTO = new ControleInternoDTO();
            $objControleInternoDTO->retNumIdControleInterno();
            $objControleInternoDTO->setNumIdSerieControlada($parObjControleInternoDTO->getNumIdSerie());
            $objControleInternoDTO->setNumIdOrgaoControlado($parObjControleInternoDTO->getNumIdOrgao());
            $objControleInternoDTO->setNumMaxRegistrosRetorno(1);

            if ($this->consultar($objControleInternoDTO) == null) {
              $bolProcessarExclusao = false;
            }
          }

          if ($bolProcessarExclusao) {

            $objAcessoDTO = new AcessoDTO();
            $objAcessoDTO->setDistinct(true);
            $objAcessoDTO->retNumIdControleInterno();
            $objAcessoDTO->retNumIdUnidade();
            $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
            $objAcessoDTO->setDblIdProtocolo($parObjControleInternoDTO->getDblIdProcedimento());
            $arrObjAcessoDTO = $objAcessoRN->listar($objAcessoDTO);

            $arrIdUnidadeComAcesso = array();
            foreach ($arrObjAcessoDTO as $objAcessoDTO) {
              $arrIdUnidadeComAcesso[$objAcessoDTO->getNumIdControleInterno()][$objAcessoDTO->getNumIdUnidade()] = 0;
            }

            if (count($arrIdUnidadeComAcesso)) {

              $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
              $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
              $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
              $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjControleInternoDTO->getDblIdProcedimento());

              $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
              $arrIdProcessos = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO), 'IdProtocolo2');
              $arrIdProcessos[] = $parObjControleInternoDTO->getDblIdProcedimento();

              $objProcedimentoDTO = new ProcedimentoDTO();
              $objProcedimentoDTO->retDblIdProcedimento();
              $objProcedimentoDTO->retNumIdTipoProcedimento();
              $objProcedimentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
              $objProcedimentoDTO->retNumIdUnidadeGeradoraProtocolo();
              $objProcedimentoDTO->setDblIdProcedimento($arrIdProcessos, InfraDTO::$OPER_IN);

              $objProcedimentoRN = new ProcedimentoRN();
              $arrObjProcedimentoDTO = $objProcedimentoRN->listarRN0278($objProcedimentoDTO);

              $arrIdUnidadesControle = array();

              foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
                $objControleInternoDTO = new ControleInternoDTO();
                $objControleInternoDTO->retNumIdControleInterno();
                $objControleInternoDTO->retNumIdUnidadeControle();
                $objControleInternoDTO->setNumIdTipoProcedimentoControlado($objProcedimentoDTO->getNumIdTipoProcedimento());
                $objControleInternoDTO->setNumIdOrgaoControlado($objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
                $objControleInternoDTO->setNumIdUnidadeControle($objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo(), InfraDTO::$OPER_DIFERENTE);
                $arrObjControleInternoDTO = $this->listar($objControleInternoDTO);
                foreach ($arrObjControleInternoDTO as $objControleInternoDTO) {
                  $arrIdUnidadesControle[$objControleInternoDTO->getNumIdControleInterno()][$objControleInternoDTO->getNumIdUnidadeControle()] = 0;
                }
              }

              $objDocumentoDTO = new DocumentoDTO();
              $objDocumentoDTO->setDistinct(true);
              $objDocumentoDTO->retNumIdSerie();
              $objDocumentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
              $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
              $objDocumentoDTO->setDblIdProcedimento($arrIdProcessos, InfraDTO::$OPER_IN);

              $objDocumentoRN = new DocumentoRN();
              $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);

              if (count($arrObjDocumentoDTO)) {

                foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
                  $objControleInternoDTO = new ControleInternoDTO();
                  $objControleInternoDTO->retNumIdControleInterno();
                  $objControleInternoDTO->retNumIdUnidadeControle();
                  $objControleInternoDTO->setNumIdSerieControlada($objDocumentoDTO->getNumIdSerie());
                  $objControleInternoDTO->setNumIdOrgaoControlado($objDocumentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
                  $objControleInternoDTO->setNumIdUnidadeControle($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo(), InfraDTO::$OPER_DIFERENTE);
                  $arrObjControleInternoDTO = $this->listar($objControleInternoDTO);
                  foreach ($arrObjControleInternoDTO as $objControleInternoDTO) {
                    $arrIdUnidadesControle[$objControleInternoDTO->getNumIdControleInterno()][$objControleInternoDTO->getNumIdUnidadeControle()] = 0;
                  }
                }
              }

              foreach (array_keys($arrIdUnidadeComAcesso) as $numIdControleInterno) {
                foreach (array_keys($arrIdUnidadeComAcesso[$numIdControleInterno]) as $numIdUnidade) {
                  if (!isset($arrIdUnidadesControle[$numIdControleInterno][$numIdUnidade])) {
                    $objAcessoDTO = new AcessoDTO();
                    $objAcessoDTO->retNumIdAcesso();
                    $objAcessoDTO->setNumIdControleInterno($numIdControleInterno);
                    $objAcessoDTO->setNumIdUnidade($numIdUnidade);
                    $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
                    $objAcessoDTO->setDblIdProtocolo($arrIdProcessos,InfraDTO::$OPER_IN);
                    $objAcessoRN->excluir($objAcessoRN->listar($objAcessoDTO));
                    $bolIndexacao = true;
                  }
                }
              }
            }
          }
        }

        $arrIdUnidadesControle = array();
        $arrIdProtocolos = array();

        if ($parObjControleInternoDTO->getStrStaOperacao() == self::$TO_GERAR_PROCEDIMENTO) {

          $objControleInternoDTO = new ControleInternoDTO();
          $objControleInternoDTO->retNumIdControleInterno();
          $objControleInternoDTO->retNumIdUnidadeControle();
          $objControleInternoDTO->setNumIdTipoProcedimentoControlado($parObjControleInternoDTO->getNumIdTipoProcedimento());
          $objControleInternoDTO->setNumIdOrgaoControlado($parObjControleInternoDTO->getNumIdOrgao());
          $objControleInternoDTO->setNumIdUnidadeControle($parObjControleInternoDTO->getNumIdUnidade(), InfraDTO::$OPER_DIFERENTE);
          $arrObjControleInternoDTO = $this->listar($objControleInternoDTO);
          foreach ($arrObjControleInternoDTO as $objControleInternoDTO) {
            $arrIdUnidadesControle[$objControleInternoDTO->getNumIdControleInterno()][$objControleInternoDTO->getNumIdUnidadeControle()] = 0;
          }

          if (count($arrIdUnidadesControle)) {
            $arrIdProtocolos = array($parObjControleInternoDTO->getDblIdProcedimento());
          }

        }else if ($parObjControleInternoDTO->getStrStaOperacao() == self::$TO_GERAR_DOCUMENTO) {

          $objControleInternoDTO = new ControleInternoDTO();
          $objControleInternoDTO->retNumIdControleInterno();
          $objControleInternoDTO->retNumIdUnidadeControle();
          $objControleInternoDTO->setNumIdSerieControlada($parObjControleInternoDTO->getNumIdSerie());
          $objControleInternoDTO->setNumIdOrgaoControlado($parObjControleInternoDTO->getNumIdOrgao());
          $objControleInternoDTO->setNumIdUnidadeControle($parObjControleInternoDTO->getNumIdUnidade(), InfraDTO::$OPER_DIFERENTE);
          $arrObjControleInternoDTO = $this->listar($objControleInternoDTO);
          foreach ($arrObjControleInternoDTO as $objControleInternoDTO) {
            $arrIdUnidadesControle[$objControleInternoDTO->getNumIdControleInterno()][$objControleInternoDTO->getNumIdUnidadeControle()] = 0;
          }

          if (count($arrIdUnidadesControle)) {
            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
            $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjControleInternoDTO->getDblIdProcedimento());

            $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
            $arrIdProtocolos = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO), 'IdProtocolo2');
            $arrIdProtocolos[] = $parObjControleInternoDTO->getDblIdProcedimento();
          }

        }else if ($parObjControleInternoDTO->getStrStaOperacao() == self::$TO_MUDANCA_NIVEL_ACESSO) {

          $objProtocoloDTO = new ProtocoloDTO();
          $objProtocoloDTO->setDistinct(true);
          $objProtocoloDTO->retNumIdOrgaoUnidadeGeradora();
          $objProtocoloDTO->retNumIdUnidadeGeradora();
          $objProtocoloDTO->retNumIdSerieDocumento();
          $objProtocoloDTO->retNumIdTipoProcedimentoProcedimento();
          $objProtocoloDTO->setDblIdProtocolo($parObjControleInternoDTO->getArrIdProtocolos(), InfraDTO::$OPER_IN);

          $objProtocoloRN = new ProtocoloRN();
          $arrObjProtocoloDTO = $objProtocoloRN->listarRN0668($objProtocoloDTO);

          foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {

            if ($objProtocoloDTO->getNumIdTipoProcedimentoProcedimento() != null) {
              $objControleInternoDTO = new ControleInternoDTO();
              $objControleInternoDTO->retNumIdControleInterno();
              $objControleInternoDTO->retNumIdUnidadeControle();
              $objControleInternoDTO->setNumIdTipoProcedimentoControlado($objProtocoloDTO->getNumIdTipoProcedimentoProcedimento());
              $objControleInternoDTO->setNumIdOrgaoControlado($objProtocoloDTO->getNumIdOrgaoUnidadeGeradora());
              $objControleInternoDTO->setNumIdUnidadeControle($objProtocoloDTO->getNumIdUnidadeGeradora(),InfraDTO::$OPER_DIFERENTE);
              $arrObjControleInternoDTO = $this->listar($objControleInternoDTO);
              foreach ($arrObjControleInternoDTO as $objControleInternoDTO) {
                $arrIdUnidadesControle[$objControleInternoDTO->getNumIdControleInterno()][$objControleInternoDTO->getNumIdUnidadeControle()] = 0;
              }
            }

            if ($objProtocoloDTO->getNumIdSerieDocumento() != null) {
              $objControleInternoDTO = new ControleInternoDTO();
              $objControleInternoDTO->retNumIdControleInterno();
              $objControleInternoDTO->retNumIdUnidadeControle();
              $objControleInternoDTO->setNumIdSerieControlada($objProtocoloDTO->getNumIdSerieDocumento());
              $objControleInternoDTO->setNumIdOrgaoControlado($objProtocoloDTO->getNumIdOrgaoUnidadeGeradora());
              $objControleInternoDTO->setNumIdUnidadeControle($objProtocoloDTO->getNumIdUnidadeGeradora(),InfraDTO::$OPER_DIFERENTE);
              $arrObjControleInternoDTO = $this->listar($objControleInternoDTO);
              foreach ($arrObjControleInternoDTO as $objControleInternoDTO) {
                $arrIdUnidadesControle[$objControleInternoDTO->getNumIdControleInterno()][$objControleInternoDTO->getNumIdUnidadeControle()] = 0;
              }
            }
          }

          if (count($arrIdUnidadesControle)){
            $arrIdProtocolos = $parObjControleInternoDTO->getArrIdProcessos();
          }

        }elseif ($parObjControleInternoDTO->getStrStaOperacao() == self::$TO_ALTERAR_PROCEDIMENTO ||
                 $parObjControleInternoDTO->getStrStaOperacao() == self::$TO_ALTERAR_DOCUMENTO ||
                 $parObjControleInternoDTO->getStrStaOperacao() == self::$TO_ANEXAR_PROCEDIMENTO ||
                 $parObjControleInternoDTO->getStrStaOperacao() == self::$TO_DESANEXAR_PROCEDIMENTO) {

          if ($arrObjProcedimentoDTO == null) {
            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
            $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($parObjControleInternoDTO->getDblIdProcedimento());

            $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
            $arrIdProcessos = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO), 'IdProtocolo2');
            $arrIdProcessos[] = $parObjControleInternoDTO->getDblIdProcedimento();

            $objProcedimentoDTO = new ProcedimentoDTO();
            $objProcedimentoDTO->retDblIdProcedimento();
            $objProcedimentoDTO->retNumIdTipoProcedimento();
            $objProcedimentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
            $objProcedimentoDTO->retNumIdUnidadeGeradoraProtocolo();
            $objProcedimentoDTO->setDblIdProcedimento($arrIdProcessos, InfraDTO::$OPER_IN);

            $objProcedimentoRN = new ProcedimentoRN();
            $arrObjProcedimentoDTO = $objProcedimentoRN->listarRN0278($objProcedimentoDTO);
          }

          foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
            $objControleInternoDTO = new ControleInternoDTO();
            $objControleInternoDTO->retNumIdControleInterno();
            $objControleInternoDTO->retNumIdUnidadeControle();
            $objControleInternoDTO->setNumIdTipoProcedimentoControlado($objProcedimentoDTO->getNumIdTipoProcedimento());
            $objControleInternoDTO->setNumIdOrgaoControlado($objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
            $objControleInternoDTO->setNumIdUnidadeControle($objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo(), InfraDTO::$OPER_DIFERENTE);
            $arrObjControleInternoDTO = $this->listar($objControleInternoDTO);
            foreach ($arrObjControleInternoDTO as $objControleInternoDTO) {
              $arrIdUnidadesControle[$objControleInternoDTO->getNumIdControleInterno()][$objControleInternoDTO->getNumIdUnidadeControle()] = 0;
            }
          }

          if ($arrObjDocumentoDTO == null) {
            $objDocumentoDTO = new DocumentoDTO();
            $objDocumentoDTO->setDistinct(true);
            $objDocumentoDTO->retNumIdSerie();
            $objDocumentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
            $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
            $objDocumentoDTO->setDblIdProcedimento($arrIdProcessos, InfraDTO::$OPER_IN);

            $objDocumentoRN = new DocumentoRN();
            $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);
          }

          if (InfraArray::contar($arrObjDocumentoDTO)) {

            foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
              $objControleInternoDTO = new ControleInternoDTO();
              $objControleInternoDTO->retNumIdControleInterno();
              $objControleInternoDTO->retNumIdUnidadeControle();
              $objControleInternoDTO->setNumIdSerieControlada($objDocumentoDTO->getNumIdSerie());
              $objControleInternoDTO->setNumIdOrgaoControlado($objDocumentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
              $objControleInternoDTO->setNumIdUnidadeControle($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo(), InfraDTO::$OPER_DIFERENTE);
              $arrObjControleInternoDTO = $this->listar($objControleInternoDTO);
              foreach ($arrObjControleInternoDTO as $objControleInternoDTO) {
                $arrIdUnidadesControle[$objControleInternoDTO->getNumIdControleInterno()][$objControleInternoDTO->getNumIdUnidadeControle()] = 0;
              }
            }
          }

          if (InfraArray::contar($arrIdUnidadesControle)) {
            $arrIdProtocolos = InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento');
          }
        }

        if (InfraArray::contar($arrIdUnidadesControle)) {

          foreach ($arrIdProtocolos as $dblIdProtocolo) {

            $objAcessoDTO = new AcessoDTO();
            $objAcessoDTO->setDistinct(true);
            $objAcessoDTO->retNumIdControleInterno();
            $objAcessoDTO->retNumIdUnidade();
            $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
            $objAcessoDTO->setDblIdProtocolo($dblIdProtocolo);
            $arrObjAcessoDTO = $objAcessoRN->listar($objAcessoDTO);
            $arrIdUnidadeComAcesso = array();
            foreach($arrObjAcessoDTO as $objAcessoDTO){
              $arrIdUnidadeComAcesso[$objAcessoDTO->getNumIdControleInterno()][$objAcessoDTO->getNumIdUnidade()] = 0;
            }

            $arrObjAcessoDTO = array();
            foreach (array_keys($arrIdUnidadesControle) as $numIdControleInterno) {
              foreach (array_keys($arrIdUnidadesControle[$numIdControleInterno]) as $numIdUnidadeControle) {
                if (!isset($arrIdUnidadeComAcesso[$numIdControleInterno][$numIdUnidadeControle])) {
                  $objAcessoDTO = new AcessoDTO();
                  $objAcessoDTO->setNumIdAcesso(null);
                  $objAcessoDTO->setNumIdUnidade($numIdUnidadeControle);
                  $objAcessoDTO->setNumIdUsuario(null);
                  $objAcessoDTO->setDblIdProtocolo($dblIdProtocolo);
                  $objAcessoDTO->setNumIdControleInterno($numIdControleInterno);
                  $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
                  $arrObjAcessoDTO[] = $objAcessoDTO;
                  $arrIdUnidadeComAcesso[$numIdControleInterno][$numIdUnidadeControle] = 0;
                }
              }
            }
            $objAcessoRN->cadastrarMultiplo($arrObjAcessoDTO);
            $bolIndexacao = true;
          }
        }
      }

      if ($bolIndexacao && $parObjControleInternoDTO->getStrStaOperacao()!=self::$TO_MUDANCA_NIVEL_ACESSO) {
        $objIndexacaoDTO = new IndexacaoDTO();
        $objIndexacaoDTO->setArrIdProtocolos(array($parObjControleInternoDTO->getDblIdProcedimento()));
        $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROCESSO_COM_DOCUMENTOS_ACESSO);

        $objIndexacaoRN = new IndexacaoRN();
        $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro processando acesso para o Controle Interno.',$e);
    }
  }
}
?>