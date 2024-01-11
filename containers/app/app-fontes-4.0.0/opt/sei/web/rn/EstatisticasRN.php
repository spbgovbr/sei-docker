<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EstatisticasRN extends InfraRN {

  //estatísticas
  public static $TIPO_ESTATISTICAS_GERADOS 		= 1;
  public static $TIPO_ESTATISTICAS_TRAMITACAO	= 2;
  public static $TIPO_ESTATISTICAS_FECHADOS 	= 3;
  public static $TIPO_ESTATISTICAS_ABERTOS 		= 4;
  public static $TIPO_ESTATISTICAS_TEMPO 			=	5;
  public static $TIPO_ESTATISTICAS_DOCUMENTOS_GERADOS = 6;
  public static $TIPO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS = 7;

  //desempenho de processos
  public static $TIPO_DESEMPENHO 	= 8;
  public static $TIPO_DESEMPENHO_PROCESSO = 9;

  //inspeção
  public static $TIPO_INSPECAO_ORGAOS_GERADOS = 10;
  public static $TIPO_INSPECAO_UNIDADES_GERADOS = 11;
  public static $TIPO_INSPECAO_TIPOS_GERADOS = 12;
  public static $TIPO_INSPECAO_ORGAOS_TRAMITACAO = 13;
  public static $TIPO_INSPECAO_UNIDADES_TRAMITACAO = 14;
  public static $TIPO_INSPECAO_ORGAOS_DOCUMENTOS = 15;
  public static $TIPO_INSPECAO_UNIDADES_DOCUMENTOS = 16;
  public static $TIPO_INSPECAO_TIPOS_DOCUMENTOS = 17;
  public static $TIPO_INSPECAO_MOVIMENTACAO = 18;

  //estatísticas
  public static $TITULO_ESTATISTICAS_GERADOS 		= 'Processos gerados no período';
  public static $TITULO_ESTATISTICAS_TRAMITACAO	= 'Processos com tramitação no período';
  public static $TITULO_ESTATISTICAS_FECHADOS 	= 'Processos com andamento fechado na unidade ao final do período';
  public static $TITULO_ESTATISTICAS_ABERTOS 		= 'Processos com andamento aberto na unidade ao final do período';
  public static $TITULO_ESTATISTICAS_TEMPO 			=	'Tempos médios de tramitação no período';
  public static $TITULO_ESTATISTICAS_DOCUMENTOS_GERADOS = 'Documentos gerados no período';
  public static $TITULO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS = 'Documentos externos no período';
  public static $TITULO_ESTATISTICAS_ARQUIVADOS = 'Documentos arquivados no período';
  public static $TITULO_ESTATISTICAS_RECEBIDOS  = 'Documentos recebidos no período';

  //desempenho de processos
  public static $TITULO_DESEMPENHO = 'Desempenho de processos no período';
  public static $TITULO_DESEMPENHO_PROCESSO = 'Desempenho do processo no período';

  //inspeção administrativa
  public static $TITULO_INSPECAO_ORGAOS_GERADOS 		= 'Processos gerados por órgão';
  public static $TITULO_INSPECAO_UNIDADES_GERADOS 		= 'Processos gerados por órgão e unidade';
  public static $TITULO_INSPECAO_TIPOS_GERADOS 		= 'Tipos de processos gerados por órgão';
  public static $TITULO_INSPECAO_ORGAOS_TRAMITACAO 		= 'Processos em tramitação por órgão';
  public static $TITULO_INSPECAO_UNIDADES_TRAMITACAO 		= 'Processos em tramitação por órgão e unidade';
  public static $TITULO_INSPECAO_ORGAOS_DOCUMENTOS 		= 'Documentos gerados e recebidos por órgão';
  public static $TITULO_INSPECAO_UNIDADES_DOCUMENTOS 		= 'Documentos gerados e recebidos por órgão e unidade';
  public static $TITULO_INSPECAO_TIPOS_DOCUMENTOS 		= 'Tipos de documentos gerados e recebidos por órgão';
  public static $TITULO_INSPECAO_MOVIMENTACAO 		= 'Última movimentação de processos no órgão';

  private static $HORA_FINAL = ' 23:59:59';
  private $numRegistros=0;
  private $arrDTO;

  public function __construct(){
    parent::__construct();
    $this->arrDTO=array();
  }
 
  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }
  
 
  public function getArrCores(){
    return array('#3399CC', '#999966', '#FFCC66', '#CC3333', '#CCCC99', '#99CCCC', '#666633', '#CC9933', '#996600',
                 '#FF9900', '#FF6600', '#FF9966', '#CC6633', '#993300', '#FF9999', '#FFCCCC', '#CC9999', '#996666',
                 '#0066CC', '#990000', '#808080', '#FFFF00', '#6666FF', '#990099', '#00FF80', '#FF00FF', '#FF9933',
                 '#FCBA8C', '#808000', '#E6E6E6', '#CCCCCC', '#666666', '#66CCFF', '#006699', '#669999', '#336666',
                 '#008000');
  }

  private function validarDblIdProcedimento(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getDblIdProcedimento())){
      $objEstatisticasDTO->setDblIdProcedimento(null);
      //$objInfraException->adicionarValidacao('Processo não informado.');
    }
  }
  
  private function validarNumIdTipoProcedimento(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getNumIdTipoProcedimento())){
      $objEstatisticasDTO->setNumIdTipoProcedimento(null);
      //$objInfraException->adicionarValidacao('Tipo do Processo não informado.');
    }
  }
  
  private function validarDblIdDocumento(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getDblIdDocumento())){
      $objEstatisticasDTO->setDblIdDocumento(null);
    }
  }
  
  private function validarNumIdUnidade(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getNumIdUnidade())){
      //$objInfraException->adicionarValidacao('Unidade não informada.');
      $objEstatisticasDTO->setNumIdUnidade(null);
    }
  }
  
  private function validarNumIdUsuario(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarNumAno(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getNumAno())){
      //$objInfraException->adicionarValidacao('Ano não informado.');
      $objEstatisticasDTO->setNumAno(null);
    }
  }

  private function validarNumMes(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getNumMes())){
      //$objInfraException->adicionarValidacao('Mês não informado.');
      $objEstatisticasDTO->setNumMes(null);
    }
  }

  private function validarDblTempoAberto(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getDblTempoAberto())){
      $objEstatisticasDTO->setDblTempoAberto(null);
    }
  }

  private function validarDthAbertura(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getDthAbertura())){
      $objEstatisticasDTO->setDthAbertura(null);
    }else{
      if (!InfraData::validarDataHora($objEstatisticasDTO->getDthAbertura())){
        $objInfraException->adicionarValidacao('Data/hora da abertura inválida.');
      }
    }
  }

  private function validarDthConclusao(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getDthConclusao())){
      $objEstatisticasDTO->setDthConclusao(null);
    }else{
      if (!InfraData::validarDataHora($objEstatisticasDTO->getDthConclusao())){
        $objInfraException->adicionarValidacao('Data/hora da conclusão inválida.');
      }
    }
  }
  
  private function validarDthSnapshot(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getDthSnapshot())){
      $objInfraException->adicionarValidacao('Data/hora da estatística não informada.');
    }else{
      if (!InfraData::validarDataHora($objEstatisticasDTO->getDthSnapshot())){
        $objInfraException->adicionarValidacao('Data/hora da estatística inválida.');
      }
    }
  }

  private function validarDblQuantidade(EstatisticasDTO $objEstatisticasDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEstatisticasDTO->getDblQuantidade())){
      $objEstatisticasDTO->setDblQuantidade(null);
    }
  }

  protected function cadastrarConectado(EstatisticasDTO $objEstatisticasDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estatisticas_cadastrar',__METHOD__,$objEstatisticasDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProcedimento($objEstatisticasDTO, $objInfraException);
      $this->validarNumIdTipoProcedimento($objEstatisticasDTO, $objInfraException);
      $this->validarDblIdDocumento($objEstatisticasDTO, $objInfraException);
      $this->validarNumIdUnidade($objEstatisticasDTO, $objInfraException);
      $this->validarNumIdUsuario($objEstatisticasDTO, $objInfraException);
      $this->validarNumAno($objEstatisticasDTO, $objInfraException);
      $this->validarNumMes($objEstatisticasDTO, $objInfraException);
      $this->validarDblTempoAberto($objEstatisticasDTO, $objInfraException);
      $this->validarDthAbertura($objEstatisticasDTO, $objInfraException);
      $this->validarDthConclusao($objEstatisticasDTO, $objInfraException);
      $this->validarDthSnapshot($objEstatisticasDTO, $objInfraException);
      $this->validarDblQuantidade($objEstatisticasDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
      $ret = $objEstatisticasBD->cadastrar($objEstatisticasDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando registro de Estatística.',$e);
    }
  }

  protected function excluirControlado($arrObjEstatisticasDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estatisticas_excluir',__METHOD__,$arrObjEstatisticasDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjEstatisticasDTO);$i++){
        $objEstatisticasBD->excluir($arrObjEstatisticasDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo registros de Estatísticas.',$e);
    }
  }

  protected function consultarConectado(EstatisticasDTO $objEstatisticasDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estatisticas_consultar',__METHOD__,$objEstatisticasDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
      $ret = $objEstatisticasBD->consultar($objEstatisticasDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando registro de Estatística.',$e);
    }
  }

  protected function listarConectado(EstatisticasDTO $objEstatisticasDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estatisticas_listar',__METHOD__,$objEstatisticasDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
      $ret = $objEstatisticasBD->listar($objEstatisticasDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando registros de Estatísticas.',$e);
    }
  }
  protected function listarArquivamentoConectado(EstatisticasArquivamentoDTO $objEstatisticasArquivamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estatisticas_listar',__METHOD__,$objEstatisticasArquivamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
      $ret = $objEstatisticasBD->listar($objEstatisticasArquivamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando registros de Estatísticas.',$e);
    }
  }

  protected function contarConectado(EstatisticasDTO $objEstatisticasDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('estatisticas_listar',__METHOD__,$objEstatisticasDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
      $ret = $objEstatisticasBD->contar($objEstatisticasDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando registros de Estatísticas.',$e);
    }
  }
  
  protected function gerarUnidadeConectado(EstatisticasDTO $parObjEstatisticasDTO) {
    
    SessaoSEI::getInstance()->validarAuditarPermissao('gerar_estatisticas_unidade',__METHOD__,$parObjEstatisticasDTO);
    
    return $this->gerar($parObjEstatisticasDTO);
  }
  
  protected function gerarOuvidoriaConectado(EstatisticasDTO $parObjEstatisticasDTO) {
    
    SessaoSEI::getInstance()->validarAuditarPermissao('gerar_estatisticas_ouvidoria',__METHOD__,$parObjEstatisticasDTO);

    $objInfraException = new InfraException();

    if (InfraArray::contar($parObjEstatisticasDTO->getArrObjUnidadeDTO())==0){
      $objInfraException->lancarValidacao('Nenhuma unidade de ouvidoria encontrada.');
    }
     
    if (InfraArray::contar($parObjEstatisticasDTO->getArrObjTipoProcedimentoDTO())==0){
      $objInfraException->lancarValidacao('Nenhum tipo de processo de ouvidoria encontrado.');
    }
    
    return $this->gerar($parObjEstatisticasDTO);
  }
  
  private function gerar(EstatisticasDTO $parObjEstatisticasDTO) {
		try{

      LimiteSEI::getInstance()->configurarNivel2();

//      $t = InfraUtil::verificarTempoProcessamento();

      $objInfraException = new InfraException();
    	
    	InfraData::validarPeriodo($parObjEstatisticasDTO->getDtaInicio(),$parObjEstatisticasDTO->getDtaFim(),$objInfraException);

      $dtaFimMaximo = InfraData::calcularData(1, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, $parObjEstatisticasDTO->getDtaInicio());
      if ((InfraData::compararDatas($parObjEstatisticasDTO->getDtaFim(),$dtaFimMaximo)-1) < 0){
        $objInfraException->adicionarValidacao('Período não pode ser superior a 1 ano.');
      }

    	$objInfraException->lancarValidacoes();
    	
      $arrTarefas = array(TarefaRN::$TI_GERACAO_PROCEDIMENTO,
                          TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE,
                          TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE,
                          TarefaRN::$TI_CONCLUSAO_PROCESSO_UNIDADE,
                          TarefaRN::$TI_CONCLUSAO_AUTOMATICA_UNIDADE);
      
			//busca todos os processos que tramitaram na unidade no período
		  $objAtividadeRN	= new AtividadeRN();

			$objEstatisticasAtividadeDTO = new EstatisticasAtividadeDTO();
			$objEstatisticasAtividadeDTO->setDistinct(true);

			$objEstatisticasAtividadeDTO->retDblIdProtocolo();
      $objEstatisticasAtividadeDTO->retDtaInclusaoProtocolo();
      $objEstatisticasAtividadeDTO->retStrSiglaOrgaoUnidade();
      $objEstatisticasAtividadeDTO->retStrSiglaUnidade();
      $objEstatisticasAtividadeDTO->retStrNomeTipoProcedimento();
      $objEstatisticasAtividadeDTO->retNumIdTipoProcedimentoProcedimento();
      $objEstatisticasAtividadeDTO->retNumIdOrgaoUnidade();
      $objEstatisticasAtividadeDTO->retNumIdUnidade();
      $objEstatisticasAtividadeDTO->retNumIdUnidadeGeradoraProtocolo();
        
			$objEstatisticasAtividadeDTO->setNumIdUnidade(InfraArray::converterArrInfraDTO($parObjEstatisticasDTO->getArrObjUnidadeDTO(),'IdUnidade'),InfraDTO::$OPER_IN);
			
			$objEstatisticasAtividadeDTO->setDthAbertura($parObjEstatisticasDTO->getDtaFim().' 23:59:59',InfraDTO::$OPER_MENOR_IGUAL);
			
      if ($parObjEstatisticasDTO->isSetNumIdOrgaoUnidade()){
        $objEstatisticasAtividadeDTO->setNumIdOrgaoUnidade($parObjEstatisticasDTO->getNumIdOrgaoUnidade());	
      }
																					
      if ($parObjEstatisticasDTO->isSetArrObjTipoProcedimentoDTO()){
			  $objEstatisticasAtividadeDTO->setNumIdTipoProcedimentoProcedimento(InfraArray::converterArrInfraDTO($parObjEstatisticasDTO->getArrObjTipoProcedimentoDTO(),'IdTipoProcedimento'),InfraDTO::$OPER_IN);
      }

			$objEstatisticasAtividadeDTO->setNumIdTarefa($arrTarefas, InfraDTO::$OPER_IN);
			
			$objEstatisticasAtividadeDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO,InfraDTO::$OPER_DIFERENTE);      
      
      $objEstatisticasAtividadeDTO->setOrdStrSiglaOrgaoUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objEstatisticasAtividadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objEstatisticasAtividadeDTO->setOrdStrNomeTipoProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);
      
      $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
      $arrObjEstatisticasAtividadeDTO = $objEstatisticasBD->listar($objEstatisticasAtividadeDTO);

      $objEstatisticasDTO = null;

			if (count($arrObjEstatisticasAtividadeDTO)){

				$objEstatisticasDTO = new EstatisticasDTO();
				
				$dto = new EstatisticasAtividadeDTO();
											
				$dto->retDblIdProtocolo();
				$dto->retDthAbertura();						
				$dto->retDthConclusao();	
				$dto->retNumIdTarefa();					
				$dto->setNumIdUnidade(InfraArray::converterArrInfraDTO($parObjEstatisticasDTO->getArrObjUnidadeDTO(),'IdUnidade'),InfraDTO::$OPER_IN);
				$dto->setNumIdTarefa($arrTarefas, InfraDTO::$OPER_IN);      
				$dto->setDthAbertura($parObjEstatisticasDTO->getDtaFim().' 23:59:59',InfraDTO::$OPER_MENOR_IGUAL);
				$dto->setOrdNumIdAtividade(InfraDTO::$TIPO_ORDENACAO_ASC);
				                                       
				$arrAndamentos = InfraArray::indexarArrInfraDTO($objEstatisticasBD->listar($dto),'IdProtocolo', true);

		    $arrGERADOS = array();
		    $arrTRAMITACAO = array();
		    $arrFECHADOS = array();
		    $arrABERTOS = array();
		    $arrTEMPO = array();
		    
		    		  
				$objEstatisticasDTO->setDblIdEstatisticasGerados(BancoSEI::getInstance()->getValorSequencia('seq_estatisticas'));
				$objEstatisticasDTO->setDblIdEstatisticasTramitacao(BancoSEI::getInstance()->getValorSequencia('seq_estatisticas')); 
				$objEstatisticasDTO->setDblIdEstatisticasFechados(BancoSEI::getInstance()->getValorSequencia('seq_estatisticas'));
				$objEstatisticasDTO->setDblIdEstatisticasAbertos(BancoSEI::getInstance()->getValorSequencia('seq_estatisticas'));
				$objEstatisticasDTO->setDblIdEstatisticasTempo(BancoSEI::getInstance()->getValorSequencia('seq_estatisticas'));
				
				$dthInicioEstatisticas = $parObjEstatisticasDTO->getDtaInicio().' 00:00:00';
				
				if ($parObjEstatisticasDTO->getDtaFim()==InfraData::getStrDataAtual()){
				  $dthFinalEstatisticas = $parObjEstatisticasDTO->getDtaFim().' '.InfraData::getStrHoraAtual();
				}else{
				  $dthFinalEstatisticas = $parObjEstatisticasDTO->getDtaFim().' 23:59:59';
				}
				
				$dthNaoConcluido = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $parObjEstatisticasDTO->getDtaFim()).' 00:00:00';
				
				$arrTipoProcedimentoTempo = array();
				
				$dthSnapshot = InfraData::getStrDataHoraAtual();

        $dtoBase = new EstatisticasDTO();
        $dtoBase->setDblIdEstatisticas(null);
        $dtoBase->setDblIdProcedimento(null);
        $dtoBase->setNumIdTipoProcedimento(null);
        $dtoBase->setDblIdDocumento(null);
        $dtoBase->setNumIdUnidade(null);
        $dtoBase->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $dtoBase->setNumMes(null);
        $dtoBase->setNumAno(null);
        $dtoBase->setDblTempoAberto(null);
        $dtoBase->setDthAbertura(null);
        $dtoBase->setDthConclusao(null);
        $dtoBase->setDthSnapshot(null);
        $dtoBase->setDblQuantidade(null);

				foreach($arrObjEstatisticasAtividadeDTO as $objEstatisticaAtividadeDTO){
					
					$numIdOrgaoUnidade = $objEstatisticaAtividadeDTO->getNumIdOrgaoUnidade();
					$numIdUnidade = $objEstatisticaAtividadeDTO->getNumIdUnidade();
					$strMes = substr($objEstatisticaAtividadeDTO->getDtaInclusaoProtocolo(),3,2);
					$strAno = substr($objEstatisticaAtividadeDTO->getDtaInclusaoProtocolo(),6,4);
					$strMesAno = substr($objEstatisticaAtividadeDTO->getDtaInclusaoProtocolo(),3,7);
					$numIdTipoProcedimento = $objEstatisticaAtividadeDTO->getNumIdTipoProcedimentoProcedimento();
					

					//Gerados no período ano
					if (InfraData::compararDatas($parObjEstatisticasDTO->getDtaInicio(),$objEstatisticaAtividadeDTO->getDtaInclusaoProtocolo())>=0 &&
					    InfraData::compararDatas($objEstatisticaAtividadeDTO->getDtaInclusaoProtocolo(),$parObjEstatisticasDTO->getDtaFim())>=0 &&
					    $objEstatisticaAtividadeDTO->getNumIdUnidadeGeradoraProtocolo()==$objEstatisticaAtividadeDTO->getNumIdUnidade()){
					    
					  
						//gerados no período ano/mês/tipo do processo
						if (isset($arrGERADOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento][$strAno][$strMes])){
							$arrGERADOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento][$strAno][$strMes]++;
						}else{
							$arrGERADOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento][$strAno][$strMes] = 1;
						}
						
						//snapshot dos gerados
        	  $dto = clone($dtoBase);
        	  $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasGerados());
        	  $dto->setDblIdProcedimento($objEstatisticaAtividadeDTO->getDblIdProtocolo());
        	  $dto->setNumIdTipoProcedimento($numIdTipoProcedimento);
        	  $dto->setNumIdUnidade($objEstatisticaAtividadeDTO->getNumIdUnidade());
        	  $dto->setNumMes($strMes);
        	  $dto->setNumAno($strAno);
        	  $dto->setDthSnapshot($dthSnapshot);
            $this->acumular($dto);
					}

					$arrTarefaPeriodo = array();
					
					if (isset($arrAndamentos[$objEstatisticaAtividadeDTO->getDblIdProtocolo()])){
						$arrAndamentosProtocolo = $arrAndamentos[$objEstatisticaAtividadeDTO->getDblIdProtocolo()];
						$numAndamentos = InfraArray::contar($arrAndamentosProtocolo);
						for($i=0;$i<$numAndamentos;$i++){
						  
							if (($i+1)<$numAndamentos){
								
								$arrTarefaPeriodo[] = array($arrAndamentosProtocolo[$i]->getNumIdTarefa(), $arrAndamentosProtocolo[$i]->getDthAbertura(), $arrAndamentosProtocolo[$i+1]->getDthAbertura());
								
							}else{
								
								if ($arrAndamentosProtocolo[$i]->getDthConclusao()==null){
								  $arrTarefaPeriodo[] = array($arrAndamentosProtocolo[$i]->getNumIdTarefa(), $arrAndamentosProtocolo[$i]->getDthAbertura(), $dthNaoConcluido);
								}else{
									
									if ($arrAndamentosProtocolo[$i]->getNumIdTarefa()==TarefaRN::$TI_GERACAO_PROCEDIMENTO || 
									    $arrAndamentosProtocolo[$i]->getNumIdTarefa()==TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE || 
									    $arrAndamentosProtocolo[$i]->getNumIdTarefa()==TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE){
									
										//se data de conclusão é igual ou anterior ao período final de busca então assume após o período senão deixa data original 
										if (InfraData::compararDataHora($dthFinalEstatisticas, $arrAndamentosProtocolo[$i]->getDthConclusao())<=0){
											$arrTarefaPeriodo[] = array($arrAndamentosProtocolo[$i]->getNumIdTarefa(), $arrAndamentosProtocolo[$i]->getDthAbertura(), $dthNaoConcluido);
										}else{
											$arrTarefaPeriodo[] = array($arrAndamentosProtocolo[$i]->getNumIdTarefa(), $arrAndamentosProtocolo[$i]->getDthAbertura(), $arrAndamentosProtocolo[$i]->getDthConclusao());
										}
									}else{
										$arrTarefaPeriodo[] = array($arrAndamentosProtocolo[$i]->getNumIdTarefa(), $arrAndamentosProtocolo[$i]->getDthAbertura(), $arrAndamentosProtocolo[$i]->getDthConclusao());
									}
								}
							}
						}
					}					
					
					
					$bolTramitacao = false;
					$bolConclusao = false;
					$bolPendente = false;
					$dblTempo = 0;
					
					
					foreach($arrTarefaPeriodo as $arrPeriodo){
						
						$numIdTarefa = $arrPeriodo[0];
						
						if ($numIdTarefa==TarefaRN::$TI_GERACAO_PROCEDIMENTO || 
						    $numIdTarefa==TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE || 
						    $numIdTarefa==TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE){

						  //engloba todo o período de busca  	
							if (InfraData::compararDataHora($arrPeriodo[1],$dthInicioEstatisticas)>0 && 
							    InfraData::compararDataHora($dthFinalEstatisticas,$arrPeriodo[2])>0){
							    	
							    	
								$bolTramitacao = true;
								$bolConclusao = false;
								$bolPendente = true;
								$dblTempo = InfraData::compararDataHora($dthInicioEstatisticas,$dthFinalEstatisticas);
								break;
							
							//inicio fora do período e final dentro	
							}else if (InfraData::compararDataHora($arrPeriodo[1],$dthInicioEstatisticas)>0 && 
							          InfraData::compararDataHora($dthInicioEstatisticas,$arrPeriodo[2])>=0 && 
							          InfraData::compararDataHora($arrPeriodo[2],$dthFinalEstatisticas)>=0){
							          	
							          	
								$bolTramitacao = true;
								$bolConclusao = true;
								$bolPendente = false;
								$dblTempo = $dblTempo + InfraData::compararDataHora($dthInicioEstatisticas,$arrPeriodo[2]);
							          	
							//inicio e final dentro do período
							}else if (InfraData::compararDataHora($dthInicioEstatisticas, $arrPeriodo[1])>=0 && 
							          InfraData::compararDataHora($arrPeriodo[1],$dthFinalEstatisticas)>=0 && 
							          InfraData::compararDataHora($dthInicioEstatisticas, $arrPeriodo[2])>=0 &&
							          InfraData::compararDataHora($arrPeriodo[2],$dthFinalEstatisticas)>=0){

							          	
								$bolTramitacao = true;
								$bolConclusao = true;
								$bolPendente = false;
								$dblTempo = $dblTempo + InfraData::compararDataHora($arrPeriodo[1],$arrPeriodo[2]);

 								
							//inicio dentro do período e final fora     	
							}else if (InfraData::compararDataHora($dthInicioEstatisticas, $arrPeriodo[1])>=0 && 
							          InfraData::compararDataHora($arrPeriodo[1],$dthFinalEstatisticas)>=0 && 
							          InfraData::compararDataHora($dthFinalEstatisticas, $arrPeriodo[2])>0){

							  
								$bolTramitacao = true;
								$bolConclusao = false;
								$bolPendente = true;
								$dblTempo = $dblTempo + InfraData::compararDataHora($arrPeriodo[1],$dthFinalEstatisticas);
								
							          	
							}
						}
					}
					
				  if ($bolTramitacao){
					
     			  if (!isset($arrTRAMITACAO[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento])){
							$arrTRAMITACAO[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento]= 1;	
						}else{
							$arrTRAMITACAO[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento]++;
						}
						
        	  $dto = clone($dtoBase);
        	  $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasTramitacao());
        	  $dto->setDblIdProcedimento($objEstatisticaAtividadeDTO->getDblIdProtocolo());
        	  $dto->setNumIdTipoProcedimento($numIdTipoProcedimento);
        	  $dto->setNumIdUnidade($objEstatisticaAtividadeDTO->getNumIdUnidade());
        	  $dto->setNumMes($strMes);
        	  $dto->setNumAno($strAno);
        	  $dto->setDthSnapshot($dthSnapshot);
            $this->acumular($dto);

				  }

					if ($bolConclusao){
            if (!isset($arrFECHADOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento])){
							$arrFECHADOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento]= 1;	
						}else{
							$arrFECHADOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento]++;
						}
						
        	  $dto = clone($dtoBase);
        	  $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasFechados());
        	  $dto->setDblIdProcedimento($objEstatisticaAtividadeDTO->getDblIdProtocolo());
        	  $dto->setNumIdTipoProcedimento($numIdTipoProcedimento);
        	  $dto->setNumIdUnidade($objEstatisticaAtividadeDTO->getNumIdUnidade());
        	  $dto->setNumMes($strMes);
        	  $dto->setNumAno($strAno);
        	  $dto->setDthSnapshot($dthSnapshot);
            $this->acumular($dto);
					}					
					
					if ($bolPendente){
						if (!isset($arrABERTOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento])){
							$arrABERTOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento]= 1;	
						}else{
							$arrABERTOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento]++;
						}

        	  $dto = clone($dtoBase);
        	  $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasAbertos());
        	  $dto->setDblIdProcedimento($objEstatisticaAtividadeDTO->getDblIdProtocolo());
        	  $dto->setNumIdTipoProcedimento($numIdTipoProcedimento);
        	  $dto->setNumIdUnidade($objEstatisticaAtividadeDTO->getNumIdUnidade());
        	  $dto->setNumMes($strMes);
        	  $dto->setNumAno($strAno);
        	  $dto->setDthSnapshot($dthSnapshot);
            $this->acumular($dto);
					}
					
					
					if ($dblTempo > 0){
						if (!isset($arrTipoProcedimentoTempo[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento])){
							$arrTipoProcedimentoTempo[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento] = 1;
						}else{
							$arrTipoProcedimentoTempo[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento]++;
						}
						
						if (!isset($arrTEMPO[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento])){
							$arrTEMPO[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento] = $dblTempo;	
						}else{
							$arrTEMPO[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento] = bcadd($arrTEMPO[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento],$dblTempo);
						}
						
        	  $dto = clone($dtoBase);
        	  $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasTempo());
        	  $dto->setDblIdProcedimento($objEstatisticaAtividadeDTO->getDblIdProtocolo());
        	  $dto->setNumIdTipoProcedimento($numIdTipoProcedimento);
        	  $dto->setDblIdDocumento(null);
        	  $dto->setNumIdUnidade($objEstatisticaAtividadeDTO->getNumIdUnidade());
        	  $dto->setNumMes($strMes);
        	  $dto->setNumAno($strAno);
        	  $dto->setDblTempoAberto($dblTempo);
        	  $dto->setDthSnapshot($dthSnapshot);
            $this->acumular($dto);
					}
				}

				//print_r($arrTEMPO);die;
				
				if (is_array($arrTipoProcedimentoTempo)){
					foreach ($arrTipoProcedimentoTempo as $numIdOrgaoUnidade => $arrUnidades){
						foreach ($arrUnidades as $numIdUnidade => $arrTiposProcedimento){
							foreach ($arrTiposProcedimento as $numIdTipoProcedimento => $numProcedimentos){
								$arrTEMPO[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento] = bcdiv($arrTEMPO[$numIdOrgaoUnidade][$numIdUnidade][$numIdTipoProcedimento],$numProcedimentos,0);
							}
						}
					}
				}

				//ESTATISTICAS DOCUMENTOS GERADOS
				$arrDOCUMENTOSGERADOS = array();
				$objEstatisticasDTO->setDblIdEstatisticasDocumentosGerados(BancoSEI::getInstance()->getValorSequencia('seq_estatisticas'));
				
				$objEstatisticasDocumentoDTO = new EstatisticasDocumentoDTO; 
				$objEstatisticasDocumentoDTO->retDblIdDocumento();
				$objEstatisticasDocumentoDTO->retDblIdProcedimento();
				$objEstatisticasDocumentoDTO->retNumIdTipoProcedimentoProcedimento();
				$objEstatisticasDocumentoDTO->retDtaInclusaoProtocolo();
				$objEstatisticasDocumentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
				$objEstatisticasDocumentoDTO->retStrSiglaOrgaoUnidadeGeradoraProtocolo();
				$objEstatisticasDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
				//$objEstatisticasDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
				$objEstatisticasDocumentoDTO->retNumIdSerie();
				$objEstatisticasDocumentoDTO->retStrNomeSerie();
				
				$objEstatisticasDocumentoDTO->setStrStaProtocoloProtocolo(ProtocoloRN::$TP_DOCUMENTO_GERADO);
				
				
				$objEstatisticasDocumentoDTO->adicionarCriterio(array('InclusaoProtocolo','InclusaoProtocolo'),
				    array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
				    array($parObjEstatisticasDTO->getDtaInicio(),$parObjEstatisticasDTO->getDtaFim()),
				    array(InfraDTO::$OPER_LOGICO_AND));
				
				$objEstatisticasDocumentoDTO->setNumIdUnidadeGeradoraProtocolo(InfraArray::converterArrInfraDTO($parObjEstatisticasDTO->getArrObjUnidadeDTO(),'IdUnidade'),InfraDTO::$OPER_IN);


				if ($parObjEstatisticasDTO->isSetNumIdOrgaoUnidade()){
				  $objEstatisticasDocumentoDTO->setNumIdOrgaoUnidadeGeradoraProtocolo($parObjEstatisticasDTO->getNumIdOrgaoUnidade());	
				}
				      
				$objEstatisticasDocumentoDTO->setOrdStrSiglaOrgaoUnidadeGeradoraProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
				$objEstatisticasDocumentoDTO->setOrdStrSiglaUnidadeGeradoraProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
				$objEstatisticasDocumentoDTO->setOrdStrNomeSerie(InfraDTO::$TIPO_ORDENACAO_ASC);
				
				$objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
				$arrObjEstatisticasDocumentoDTO = $objEstatisticasBD->listar($objEstatisticasDocumentoDTO);

				foreach ($arrObjEstatisticasDocumentoDTO as $objEstatisticasDocumentoDTO){
					
					$numIdOrgaoUnidade = $objEstatisticasDocumentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo();
					$numIdUnidade = $objEstatisticasDocumentoDTO->getNumIdUnidadeGeradoraProtocolo();
					
					$numIdSerie = $objEstatisticasDocumentoDTO->getNumIdSerie();
					$strMes = substr($objEstatisticasDocumentoDTO->getDtaInclusaoProtocolo(),3,2);
					$strAno = substr($objEstatisticasDocumentoDTO->getDtaInclusaoProtocolo(),6,4);
					
					if (!isset($arrDOCUMENTOSGERADOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdSerie][$strAno][$strMes])){
				     $arrDOCUMENTOSGERADOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdSerie][$strAno][$strMes] = 1;
				  }else{
				     $arrDOCUMENTOSGERADOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdSerie][$strAno][$strMes]++;
				  }
				  
      	  $dto = clone($dtoBase);
      	  $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasDocumentosGerados());
      	  $dto->setDblIdProcedimento($objEstatisticasDocumentoDTO->getDblIdProcedimento());
      	  $dto->setNumIdTipoProcedimento($objEstatisticasDocumentoDTO->getNumIdTipoProcedimentoProcedimento());
      	  $dto->setDblIdDocumento($objEstatisticasDocumentoDTO->getDblIdDocumento());
      	  $dto->setNumIdUnidade($objEstatisticasDocumentoDTO->getNumIdUnidadeGeradoraProtocolo());
      	  $dto->setNumMes($strMes);
      	  $dto->setNumAno($strAno);
      	  $dto->setDthSnapshot($dthSnapshot);
          $this->acumular($dto);
				}

				//ESTATISTICAS DOCUMENTOS EXTERNOS
				$arrDOCUMENTOSRECEBIDOS = array();
				$objEstatisticasDTO->setDblIdEstatisticasDocumentosRecebidos(BancoSEI::getInstance()->getValorSequencia('seq_estatisticas'));
				
				$objEstatisticasDocumentoDTO = new EstatisticasDocumentoDTO;
				$objEstatisticasDocumentoDTO->retDblIdDocumento();
				$objEstatisticasDocumentoDTO->retDblIdProcedimento();
				$objEstatisticasDocumentoDTO->retNumIdTipoProcedimentoProcedimento();
				$objEstatisticasDocumentoDTO->retDtaInclusaoProtocolo();
				$objEstatisticasDocumentoDTO->retNumIdOrgaoUnidadeGeradoraProtocolo();
				$objEstatisticasDocumentoDTO->retStrSiglaOrgaoUnidadeGeradoraProtocolo();
				$objEstatisticasDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
				//$objEstatisticasDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
				$objEstatisticasDocumentoDTO->retNumIdSerie();
				$objEstatisticasDocumentoDTO->retStrNomeSerie();
				
				$objEstatisticasDocumentoDTO->setStrStaProtocoloProtocolo(ProtocoloRN::$TP_DOCUMENTO_RECEBIDO);
				
				
				$objEstatisticasDocumentoDTO->adicionarCriterio(array('InclusaoProtocolo','InclusaoProtocolo'),
				    array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
				    array($parObjEstatisticasDTO->getDtaInicio(),$parObjEstatisticasDTO->getDtaFim()),
				    array(InfraDTO::$OPER_LOGICO_AND));
				
				$objEstatisticasDocumentoDTO->setNumIdUnidadeGeradoraProtocolo(InfraArray::converterArrInfraDTO($parObjEstatisticasDTO->getArrObjUnidadeDTO(),'IdUnidade'),InfraDTO::$OPER_IN);

				if ($parObjEstatisticasDTO->isSetNumIdOrgaoUnidade()){
				  $objEstatisticasDocumentoDTO->setNumIdOrgaoUnidadeGeradoraProtocolo($parObjEstatisticasDTO->getNumIdOrgaoUnidade());
				}
				
				$objEstatisticasDocumentoDTO->setOrdStrSiglaOrgaoUnidadeGeradoraProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
				$objEstatisticasDocumentoDTO->setOrdStrSiglaUnidadeGeradoraProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
				$objEstatisticasDocumentoDTO->setOrdStrNomeSerie(InfraDTO::$TIPO_ORDENACAO_ASC);
				
				$objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
				$arrObjEstatisticasDocumentoDTO = $objEstatisticasBD->listar($objEstatisticasDocumentoDTO);

				foreach ($arrObjEstatisticasDocumentoDTO as $objEstatisticasDocumentoDTO){
				  	
				  $numIdOrgaoUnidade = $objEstatisticasDocumentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo();
				  $numIdUnidade = $objEstatisticasDocumentoDTO->getNumIdUnidadeGeradoraProtocolo();
				  	
				  $numIdSerie = $objEstatisticasDocumentoDTO->getNumIdSerie();
				  $strMes = substr($objEstatisticasDocumentoDTO->getDtaInclusaoProtocolo(),3,2);
				  $strAno = substr($objEstatisticasDocumentoDTO->getDtaInclusaoProtocolo(),6,4);
				  	
				  if (!isset($arrDOCUMENTOSRECEBIDOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdSerie][$strAno][$strMes])){
				    $arrDOCUMENTOSRECEBIDOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdSerie][$strAno][$strMes] = 1;
				  }else{
				    $arrDOCUMENTOSRECEBIDOS[$numIdOrgaoUnidade][$numIdUnidade][$numIdSerie][$strAno][$strMes]++;
				  }
				
				  $dto = clone($dtoBase);
				  $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasDocumentosRecebidos());
				  $dto->setDblIdProcedimento($objEstatisticasDocumentoDTO->getDblIdProcedimento());
				  $dto->setNumIdTipoProcedimento($objEstatisticasDocumentoDTO->getNumIdTipoProcedimentoProcedimento());
				  $dto->setDblIdDocumento($objEstatisticasDocumentoDTO->getDblIdDocumento());
				  $dto->setNumIdUnidade($objEstatisticasDocumentoDTO->getNumIdUnidadeGeradoraProtocolo());
				  $dto->setNumMes($strMes);
				  $dto->setNumAno($strAno);
				  $dto->setDthSnapshot($dthSnapshot);
          $this->acumular($dto);
				}

		    $objEstatisticasDTO->setArrEstatisticasGERADOS($arrGERADOS);
		    $objEstatisticasDTO->setArrEstatisticasTRAMITACAO($arrTRAMITACAO);
		    $objEstatisticasDTO->setArrEstatisticasFECHADOS($arrFECHADOS);
		    $objEstatisticasDTO->setArrEstatisticasABERTOS($arrABERTOS);
		    $objEstatisticasDTO->setArrEstatisticasTEMPO($arrTEMPO);
				$objEstatisticasDTO->setArrEstatisticasDOCUMENTOSGERADOS($arrDOCUMENTOSGERADOS);
				$objEstatisticasDTO->setArrEstatisticasDOCUMENTOSRECEBIDOS($arrDOCUMENTOSRECEBIDOS);
			}
			$this->acumular(null);
//      $t = InfraUtil::verificarTempoProcessamento($t);
//      InfraDebug::getInstance()->gravar('Tempo execução EstatisticasRN->gerar: '.$t.' s');

      return $objEstatisticasDTO;
        
    }catch(Exception $e){
    	throw new InfraException('Erro gerando estatísticas.',$e);
    }  	
  }

  public function acumular($objEstatisticasDTO){
    try{

      if ($objEstatisticasDTO!=null){
        $this->arrDTO[]=$objEstatisticasDTO;
        $this->numRegistros++;
      }

      if ($this->numRegistros>49 || ($this->numRegistros>0 && $objEstatisticasDTO==null)){
        $objEstatisticasBD=new EstatisticasBD(self::getObjInfraIBanco());
        $objEstatisticasBD->cadastrar($this->arrDTO);
        $this->numRegistros=0;
        $this->arrDTO=array();
      }

    }catch(Exception $e){
      throw new InfraException('Erro acumulando estatísticas para gravação.',$e);
    }
  }

  protected function gerarInspecaoAdministrativaConectado(EstatisticasInspecaoDTO $parObjEstatisticasInspecaoDTO){
    try{

      LimiteSEI::getInstance()->configurarNivel2();

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('inspecao_administrativa_gerar',__METHOD__,$parObjEstatisticasInspecaoDTO);
      
      $objInfraException = new InfraException();
      
      InfraData::validarPeriodo($parObjEstatisticasInspecaoDTO->getDtaInicio(), $parObjEstatisticasInspecaoDTO->getDtaFim(), $objInfraException);

      $objInfraException->lancarValidacoes();
      
			$objEstatisticasInspecaoDTO = new EstatisticasInspecaoDTO();
				
			$objEstatisticasInspecaoDTO->setDblIdInspecao(BancoSEI::getInstance()->getValorSequencia('seq_estatisticas'));

			$dthSnapshot = InfraData::getStrDataHoraAtual();
      
  		$arrProcessosGeradosOrgao = array();
  		$arrProcessosGeradosUnidade = array();
  		$arrProcessosGeradosTipo = array();
  		
  		$arrDocumentosOrgao = array();
  		$arrDocumentosUnidade = array();
  		$arrDocumentosTipo = array();

  		$arrTramitacaoOrgao = array();
  		$arrTramitacaoUnidade = array();
  		$arrMovimentacao = array();
  		
  		$objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
  		
  		if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_ORGAOS_GERADOS ||
  		    $parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_UNIDADES_GERADOS ||
  		    $parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_TIPOS_GERADOS){
  		      
		    $objEstatisticasBD = new EstatisticasBD(BancoSEI::getInstance());
		    $arrRet = $objEstatisticasBD->gerarInspecaoProcessosGerados($parObjEstatisticasInspecaoDTO);
		    
			  if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_ORGAOS_GERADOS){
 				  $arrProcessosGeradosOrgao = $arrRet;
			  }else if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_UNIDADES_GERADOS){
 				  $arrProcessosGeradosUnidade = $arrRet;
				}else if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_TIPOS_GERADOS){
 					$arrProcessosGeradosTipo = $arrRet;
				}

  		}else if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_ORGAOS_DOCUMENTOS ||
  		          $parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_UNIDADES_DOCUMENTOS ||
  		          $parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_TIPOS_DOCUMENTOS){
			  
  		            
		    $objEstatisticasBD = new EstatisticasBD(BancoSEI::getInstance());
		    $arrRet = $objEstatisticasBD->gerarInspecaoDocumentosGeradosRecebidos($parObjEstatisticasInspecaoDTO);
		    
			  if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_ORGAOS_DOCUMENTOS){
   				$arrDocumentosOrgao = $arrRet;
			  }else if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_UNIDADES_DOCUMENTOS){
 			    $arrDocumentosUnidade = $arrRet;
			  }else if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_TIPOS_DOCUMENTOS){
 					$arrDocumentosTipo = $arrRet;
			  }
			  

			}else if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_ORGAOS_TRAMITACAO ||
 		            $parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_UNIDADES_TRAMITACAO){

			  if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_ORGAOS_TRAMITACAO){
			    $objEstatisticasBD=new EstatisticasBD($this->getObjInfraIBanco());
			    $objEstatisticasInspecaoDTO->setNumTotalTramitacao($objEstatisticasBD->obterTotalProcessosEmTramitacao());
			  }
 		    //busca todos os processos que tramitaram na unidade no período
  			$objEstatisticasAtividadeDTO = new EstatisticasAtividadeDTO();
  			$objEstatisticasAtividadeDTO->setDistinct(true);
  			$objEstatisticasAtividadeDTO->retDblIdProtocolo();
        $objEstatisticasAtividadeDTO->retNumIdOrgaoUnidade();
        $objEstatisticasAtividadeDTO->retNumIdUnidade();
        $objEstatisticasAtividadeDTO->retStrSiglaOrgaoUnidade();
        $objEstatisticasAtividadeDTO->retStrSiglaUnidade();

          
        if (!InfraString::isBolVazia($parObjEstatisticasInspecaoDTO->getNumIdOrgao())){
          $objEstatisticasAtividadeDTO->setNumIdOrgaoUnidade($parObjEstatisticasInspecaoDTO->getNumIdOrgao());
        }
        
        if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_UNIDADES_TRAMITACAO && !InfraString::isBolVazia($parObjEstatisticasInspecaoDTO->getNumIdUnidade())){
          $objEstatisticasAtividadeDTO->setNumIdUnidade($parObjEstatisticasInspecaoDTO->getNumIdUnidade());
        }
  			
      	$objEstatisticasAtividadeDTO->setDthConclusao(null);
      	
  			$objEstatisticasAtividadeDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO,InfraDTO::$OPER_DIFERENTE);      
        
  			
        if ($parObjEstatisticasInspecaoDTO->isOrdStrSiglaOrgao()){
          $objEstatisticasAtividadeDTO->setOrdStrSiglaOrgaoUnidade($parObjEstatisticasInspecaoDTO->getOrdStrSiglaOrgao());  
        }
      
        if ($parObjEstatisticasInspecaoDTO->isOrdStrSiglaUnidade()){
          $objEstatisticasAtividadeDTO->setOrdStrSiglaUnidade($parObjEstatisticasInspecaoDTO->getOrdStrSiglaUnidade());
        }
      
        $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
        $arrObjEstatisticasAtividadeDTO = $objEstatisticasBD->listar($objEstatisticasAtividadeDTO);
  
  			foreach($arrObjEstatisticasAtividadeDTO as $objEstatisticaAtividadeDTO){
  				
  				//gerados no período ano/mês/tipo do processo
  				if (!isset($arrTramitacaoOrgao[$objEstatisticaAtividadeDTO->getNumIdOrgaoUnidade()])){
  				  $arrTramitacaoOrgao[$objEstatisticaAtividadeDTO->getNumIdOrgaoUnidade()] = 1;
  				}else{
  					$arrTramitacaoOrgao[$objEstatisticaAtividadeDTO->getNumIdOrgaoUnidade()]++;
  				}
  				
  				if (!isset($arrTramitacaoUnidade[$objEstatisticaAtividadeDTO->getNumIdUnidade()])){
  				  $arrTramitacaoUnidade[$objEstatisticaAtividadeDTO->getNumIdUnidade()] = 1;
  				}else{
  					$arrTramitacaoUnidade[$objEstatisticaAtividadeDTO->getNumIdUnidade()]++;
  				}
  				
  			}
  			
        if ($parObjEstatisticasInspecaoDTO->isOrdNumQuantidade()){
          if ($parObjEstatisticasInspecaoDTO->getOrdNumQuantidade()==InfraDTO::$TIPO_ORDENACAO_ASC){
            asort($arrTramitacaoOrgao, SORT_NUMERIC);
            asort($arrTramitacaoUnidade, SORT_NUMERIC);
          }else{
            arsort($arrTramitacaoOrgao, SORT_NUMERIC);
            arsort($arrTramitacaoUnidade, SORT_NUMERIC);
          }
        }
  			
 		  }else if ($parObjEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_MOVIMENTACAO){
 		    
  			$objEstatisticasAtividadeDTO = new EstatisticasAtividadeDTO();
  			$objEstatisticasAtividadeDTO->setDistinct(true);
  			$objEstatisticasAtividadeDTO->retDblIdProtocolo();
  			$objEstatisticasAtividadeDTO->retStrProtocoloFormatadoProtocolo();
  			$objEstatisticasAtividadeDTO->retStrSiglaUnidade();
  			$objEstatisticasAtividadeDTO->retStrDescricaoUnidade();
  			$objEstatisticasAtividadeDTO->retDthAbertura();
  			$objEstatisticasAtividadeDTO->retStrNomeTipoProcedimento();
  			
        $objEstatisticasAtividadeDTO->setDthConclusao(null);
        $objEstatisticasAtividadeDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO,InfraDTO::$OPER_DIFERENTE); 
        
        if ($parObjEstatisticasInspecaoDTO->isOrdStrSiglaUnidade()){
          $objEstatisticasAtividadeDTO->setOrdStrSiglaUnidade($parObjEstatisticasInspecaoDTO->getOrdStrSiglaUnidade());  
        }
        
        if ($parObjEstatisticasInspecaoDTO->isOrdDthAbertura()){
          $objEstatisticasAtividadeDTO->setOrdDthAbertura($parObjEstatisticasInspecaoDTO->getOrdDthAbertura());  
        }
  
        if ($parObjEstatisticasInspecaoDTO->isOrdStrProtocoloFormatadoProtocolo()){
          $objEstatisticasAtividadeDTO->setOrdStrProtocoloFormatadoProtocolo($parObjEstatisticasInspecaoDTO->getOrdStrProtocoloFormatadoProtocolo());  
        }
  
        if (!InfraString::isBolVazia($parObjEstatisticasInspecaoDTO->getNumIdOrgao())){
          $objEstatisticasAtividadeDTO->setNumIdOrgaoUnidade($parObjEstatisticasInspecaoDTO->getNumIdOrgao());
        }
        
        if (!InfraString::isBolVazia($parObjEstatisticasInspecaoDTO->getNumIdUnidade())){
          $objEstatisticasAtividadeDTO->setNumIdUnidade($parObjEstatisticasInspecaoDTO->getNumIdUnidade());
        }
        
        //if ($parObjEstatisticasInspecaoDTO->getStrSinProcessosNaoRecebidos()=="S"){
        //	$objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE);
        //}
        
        //if (!InfraString::isBolVazia($parObjEstatisticasInspecaoDTO->getNumDias())){
        //  $objAtividadeDTO->setDthAbertura(InfraData::calcularData($parObjEstatisticasInspecaoDTO->getNumDias(),InfraData::$UNIDADE_DIAS,InfraData::$SENTIDO_ATRAS).' 23:59:59',InfraDTO::$OPER_MENOR_IGUAL);
        //}
        
        //paginação 
     		$objEstatisticasAtividadeDTO->setNumMaxRegistrosRetorno($parObjEstatisticasInspecaoDTO->getNumMaxRegistrosRetorno());
    		$objEstatisticasAtividadeDTO->setNumPaginaAtual($parObjEstatisticasInspecaoDTO->getNumPaginaAtual());
        
        $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
        $arrMovimentacao = $objEstatisticasBD->listar($objEstatisticasAtividadeDTO);
    		        
        
  			//paginação
  			$parObjEstatisticasInspecaoDTO->setNumTotalRegistros($objEstatisticasAtividadeDTO->getNumTotalRegistros());
        $parObjEstatisticasInspecaoDTO->setNumRegistrosPaginaAtual($objEstatisticasAtividadeDTO->getNumRegistrosPaginaAtual());
        
        
        foreach($arrMovimentacao as $objEstatisticasAtividadeDTO){
          $objEstatisticasAtividadeDTO->setNumDias((int)InfraData::compararDatas($objEstatisticasAtividadeDTO->getDthAbertura(),InfraData::getStrDataHoraAtual()));
        }
	    }		    
	    
 		  $objEstatisticasInspecaoDTO->setArrOrgaosProcessosGerados($arrProcessosGeradosOrgao);
 		  $objEstatisticasInspecaoDTO->setArrUnidadesProcessosGerados($arrProcessosGeradosUnidade);
 		  $objEstatisticasInspecaoDTO->setArrTiposProcessosGerados($arrProcessosGeradosTipo);
 		  $objEstatisticasInspecaoDTO->setArrOrgaosDocumentos($arrDocumentosOrgao);
 		  $objEstatisticasInspecaoDTO->setArrUnidadesDocumentos($arrDocumentosUnidade);
 		  $objEstatisticasInspecaoDTO->setArrTiposDocumentos($arrDocumentosTipo);
	    $objEstatisticasInspecaoDTO->setArrOrgaosTramitacao($arrTramitacaoOrgao);
	    $objEstatisticasInspecaoDTO->setArrUnidadesTramitacao($arrTramitacaoUnidade);
 		  
	    $objEstatisticasInspecaoDTO->setArrMovimentacao($arrMovimentacao);
			
			return $objEstatisticasInspecaoDTO;
      
			
    }catch(Exception $e){
      throw new InfraException('Erro gerando estatísticas de Inspeção Administrativa.',$e);
    }
  }

  public function gerarGraficoBarrasDuplas($numGrafico, $strTitulo, $arrDados, $numAlturaGrafico, $strCor1, $strLegenda1, $strCor2, $strLegenda2) {
  	
    $arrCoresUsadas = array();
    $largura=750;
    $altura=$numAlturaGrafico+50;
    $qtdItens=InfraArray::contar($arrDados);
    $larguraGrafico=0;
     
    if ($qtdItens<12) {
      $larguraGrafico=$qtdItens*60;
    }	else if ($qtdItens>25) {
      $larguraGrafico=$qtdItens*30;
    } else $larguraGrafico=700;
         
    $jsonCores='["'.$strCor1.'","'.$strCor2.'"]';    
     
    if($larguraGrafico>($largura-80)) $largura=$larguraGrafico+80;
    $margem=($largura-$larguraGrafico)/2;
    $strJS="\n".'<script type="text/javascript" charset="iso-8859-1" ><!--//--><![CDATA[//><!--'."\n";
    $strJS.='var paper'.$numGrafico.' = Raphael("divGrf'.$numGrafico.'",'.$largura.','.$altura.');';
    $strJS.="\nvar ret=paper".$numGrafico.".rect(5,5,". ($largura-10) .",". ($altura-10) .",10); \n";
    $strJS.="ret.attr({ \"fill\": \"90-#ccf:5-#fff:95\", \"fill-opacity\": 0.5 });\n";
    $strJS.="var txt=paper".$numGrafico.".text(".$largura/2 .",30,'".str_replace("'","\\'",$strTitulo)."').attr({";

    $strJS.='"font-size":12});';
    $strJS.="\nif (INFRA_WEBKIT>0) txt.node.firstChild.attributes.removeNamedItem(\"dy\");\n";
    $strJS.="var fin = function () {\n  this.flag = paper".$numGrafico.".popup(this.bar.x, this.bar.y-4, label".$numGrafico."[this.bar.id-this.paper.bottom.id-2]+\"\\n\"+";
    $strJS.='(this.bar.value || "0")).insertBefore(this);';
    $strJS.="\n  this.gl=this.bar.glow({width:5,color:\"#004cff\"});\n this.bar.lighter(2);\n	};";
    $strJS.="var fout = function () {\n  this.gl.remove();\n this.bar.resetBrightness();\n this.flag.animate({ opacity: 0}, 300, function () { this.remove();  });\n 	};\n";
    $dataJS='var data'.$numGrafico.'=[';

    $labelJS='var label'.$numGrafico.'=[';
    $refJS='var ref'.$numGrafico.'=[';
    	
    $primeiro=true;
    $data1="[";
    $data2="[";
    foreach($arrDados as $dado){
    
      if ($primeiro) {
        $primeiro=false;
      } else {
        $data1.=',';
        $data2.=',';
        $labelJS.=',';
        $refJS.=',';
      }
    
      $data1.= str_replace('.', '', $dado[1]);
      $data2.= str_replace('.', '', $dado[3]);
      $labelJS.="'".str_replace("'","\\'",$dado[0])."','".str_replace("'","\\'",$dado[0])."'";
      $refJS.="'".InfraString::removerFormatacaoXML($dado[2])."','".InfraString::removerFormatacaoXML($dado[4])."'";
    }

    $dataJS.=$data1."],".$data2."]];\n";
    $labelJS.="];\n";
    $refJS.="];\n";
    
    $strJS.=$dataJS.$labelJS.$refJS;
    $strJS.='var bar=paper'.$numGrafico.'.barchart('.$margem.', 50, '.$larguraGrafico.', '.($altura-50).', data'.$numGrafico;
    if($jsonCores!="") $strJS.=',{"colors":'.$jsonCores.'}';
    $strJS.='); bar.hover(fin,fout); bar.click(function(){	var href=ref'.$numGrafico.'[this.bar.id-this.paper.bottom.id-2]; if (href!="") abrirDetalhe(href);	});';
    $strJS.="\n //--><!]]> </script>\n";
    
    $strJS .= '<table border="0" valign="bottom" style="width:700px">'."\n";;
   
    $strJS .= '<tr>'."\n";
    $strJS .= '<td style="font-size:.8em;padding:0 .2em 0 .2em;">';
    $strJS .= '<div style="margin:0;display:inline-table;width:8px;height:8px;background-color:'.$strCor1.';border:1px solid black"></div>&nbsp;'.$strLegenda1.'&nbsp;&nbsp;';
    $strJS .= '</td>'."\n";
    $strJS .= '</tr>'."\n";
    $strJS .= '<tr>'."\n";
    $strJS .= '<td style="font-size:.8em;padding:0 .2em 0 .2em;">';
    $strJS .= '<div style="margin:0;display:inline-table;width:8px;height:8px;background-color:'.$strCor2.';border:1px solid black"></div>&nbsp;'.$strLegenda2.'&nbsp;&nbsp;';
    $strJS .= '</td>'."\n";
    $strJS .= '</tr>'."\n";
          
    $strJS .= '</table>'."\n";;
    
    
    return $strJS;
    
	}
	
  public function gerarGraficoPizza($numGrafico, $strTitulo,$strNome, $arrDados, $arrLinks, $numAlturaGrafico, $numLarguraGrafico, $varCores,$total='') {
    $arrCoresUsadas = array();
    $largura=750;
    $altura=$numAlturaGrafico+50;
    $qtdItens=InfraArray::contar($arrDados);
     
    if (is_array($varCores)){
      $jsonCores='[';
    } else $jsonCores="";
     
    //if($larguraGrafico>($largura-80)) $largura=$larguraGrafico+80;
    
    $strJS="\n".'<script type="text/javascript" charset="iso-8859-1" ><!--//--><![CDATA[//><!--'."\n";
    $strJS.='var paper'.$numGrafico.' = Raphael("divGrf'.$numGrafico.'",'.$largura.','.$altura.');';
    $strJS.="\nvar ret=paper".$numGrafico.".rect(5,5,". ($largura-10) .",". ($altura-10) .",10); \n";
    $strJS.="ret.attr({ \"fill\": \"90-#ccf:5-#fff:95\", \"fill-opacity\": 0.5 });\n";
    $strJS.="var txt=paper".$numGrafico.".text(".$largura/2 .",30,'".str_replace("'","\\'",$strTitulo)."').attr({";
    $strJS.='"font-size":12});';
    //$strJS.="\nif (INFRA_WEBKIT>0) txt.node.firstChild.attributes.removeNamedItem(\"dy\");\n";
    $strJS.="var piein = function () {\n  this.sector.stop();\n  this.sector.scale(1.1,1.1,this.cx,this.cy);\n";
    $strJS.="\n  if(this.label) {this.label[0].stop(); this.label[0].attr({r:7.5});this.label[1].attr({\"font-weight\":800});}\n	};\n";
    $strJS.="var pieout = function () {\n  this.sector.animate({transform:'s1 1'+this.cx+' '+this.cy},500,\"bounce\");\n";
   	$strJS.="  if(this.label) {this.label[0].animate({r:5},500,\"bounce\"); this.label[1].attr({\"font-weight\":400});}};\n";
    $dataJS='var data'.$numGrafico.'=[';
    $labelJS='var label'.$numGrafico.'=[';
    $refJS='var ref'.$numGrafico.'=[';
    	
    $primeiro=true;
    foreach($arrDados as $dado){
    
      if ($primeiro) {
        $primeiro=false;
      } else {
        $dataJS.=',';
        $labelJS.=',';
        $refJS.=',';
      }
    
      if (is_array($varCores)){
        if (!in_array($dado[0],$arrCoresUsadas)){
          $arrCoresUsadas[] = $dado[0];
        }
        $jsonCores.='"'.$varCores[$dado[0]].'",';
      }
      $dataJS.= str_replace('.', '', $dado[1]);
      $labelJS.="'".str_replace("'","\\'",$dado[0])."'";
      $refJS.="'".InfraString::removerFormatacaoXML($dado[2])."'";
    }
    if (is_array($varCores)){
      $jsonCores=substr($jsonCores, 0,-1).']';
    }
    
    $dataJS.="];\n";
    $labelJS.="];\n";
    $refJS.="];\n";
    $pos_x=$largura/2-80;
    $pos_y=$altura/2+15;
    $raio=$altura/2-45;
    $strJS.=$dataJS.$labelJS.$refJS;
    $strJS.='var pie=paper'.$numGrafico.'.piechart('.$pos_x.', '.$pos_y.', '.$raio.', data'.$numGrafico;
    if($jsonCores!="") $strJS.=',{"colors":'.$jsonCores.',legend:label'.$numGrafico.',legendpos:"east"}';
    $strJS.=");\n pie.hover(piein,pieout);\n //bar.click(function(){	var href=ref".$numGrafico.'[this.bar.id-this.paper.bottom.id-2]; if (href!="") abrirDetalhe(href);	});';
    if ($total!=''){
    
      $strJS.="\n var circ".$numGrafico."=paper".$numGrafico.".circle(".$pos_x.",".$pos_y.",50).attr({'fill':'#fff','stroke':'#fff'});";  
      $strJS.="\n circ".$numGrafico.".hover(function(){this.stop();this.scale(1.1,1.1,this.cx,this.cy);},function(){this.animate({transform:'s1 1'+this.cx+' '+this.cy},500,\"bounce\");});";  
      $strJS.="\n var txtcirc".$numGrafico."=paper".$numGrafico.".text(".$pos_x.",".$pos_y.",'".$total."').attr({'font-size':'12','fill':'#000'});";
      
    }
    $strJS.="\n //--><!]]> </script>\n";
    
    if (is_array($varCores)){
      $strJS .= '<table border="0" valign="bottom" style="width:700px">'."\n";;
    
      foreach($arrCoresUsadas as $corUsada){
        if (isset($varCores[$corUsada])){
          $strJS .= '<tr>'."\n";
          $strJS .= '<td style="font-size:.8em;padding:0 .2em 0 .2em;">';
          $strJS .= '<div style="margin:0;display:inline-table;width:8px;height:8px;background-color:'.$varCores[$corUsada].';border:1px solid black"></div>&nbsp;'.$corUsada.'&nbsp;&nbsp;';
          $strJS .= '</td>'."\n";
          $strJS .= '</tr>'."\n";
        }
      }
    
      $strJS .= '</table>'."\n";;
    }
    
    return $strJS;
    /*---------------------------------------
  	$arrCoresUsadas = array();
  	
		$strGrafico = '';
		$strGrafico .= '<table align="center" style="border:.1em solid black;" width="100%">'."\n";
		$strGrafico .= '<tr>'."\n";
		$strGrafico .= '<td>'."\n";
  	$strGrafico.= '<div id="divGrafico'.$strNome.'" style="position:relative;height:'.$numAlturaGrafico.'px;width:'.$numLarguraGrafico.'px;">';
  	$strJS = '<script>var p = new pie();';
    foreach ($arrDados as $chave => $arrResultado) {
    	$strJS .= 'p.add("'.$arrResultado[0].'",'.$arrResultado[1].',\''.$arrCores[$chave].'\');';
    }
    $strJS .= 'p.render(\'divGrafico'.$strNome.'\',\'\')</script></div>';
    $strGrafico.= $strJS; 	
      
		$strGrafico .= '</td>'."\n";
		$strGrafico .= '</tr>'."\n";
		$strGrafico .= '</table>'."\n";
		
		//$strGrafico .= '<br />'."\n";
    
    /*if (is_array($varCores)){
			$strGrafico .= '<table border="0" align="left" valign="bottom">'."\n";;
	    $strGrafico .= '<tr>'."\n";
	    foreach($arrCoresUsadas as $corUsada){
			  if (isset($varCores[$corUsada])){
					$strGrafico .= '<td style="font-size:.8em;padding:0 .2em 0 .2em;">';
					$strGrafico .= '<img src="'.$varCores[$corUsada].'" width="8" height="8" style="border:1px solid black"/>&nbsp;'.$corUsada; 
					$strGrafico .= '</td>'."\n";
				}
			}
			$strGrafico .= '</tr>'."\n";				
	    $strGrafico .= '</table>'."\n";;
    }	
    
		return $strGrafico;*/
	}
	 
  private function calcularFator($arrDados, $numAlturaGrafico) {
  	
 		$numMaiorItem = 0;
 		
		foreach($arrDados as $dado){

		  $d1 = str_replace('.','',$dado[1]);
		  
			if ($d1 > $numMaiorItem) {
				$numMaiorItem = $d1;
			}
			if (isset($dado[3])) {
			  
			  $d3 = str_replace('.','',$dado[3]);
			  
				if ($d3 > $numMaiorItem) {
					$numMaiorItem = $d3;
				}
			}
		}
		
		//RETORNA O FATOR E A AÇÃO (DIVIDIR OU MULTIPLICAR)
		if ($numMaiorItem > $numAlturaGrafico) {
			return array($numMaiorItem/$numAlturaGrafico, "D");
		} else {
			if ($numMaiorItem > 0){
			  return array($numAlturaGrafico/$numMaiorItem, "M");
			}else{
				return array($numAlturaGrafico, "M");
			}
		}
	}   
	
	public function gerarGraficoBarrasSimples($numGrafico,$strTitulo,$strLinkTitulo,$arrDados, $numAlturaGrafico, $varCores, $bolLegenda = true) {
	   
	  $arrCoresUsadas = array();
	  $largura=750;
	  $altura=$numAlturaGrafico+50;
	  $qtdItens=InfraArray::contar($arrDados);
	  $larguraGrafico=0;
	   
	  if ($qtdItens<25) {
	    $larguraGrafico=$qtdItens*30;
	  }	else if ($qtdItens>50) {
	    $larguraGrafico=$qtdItens*15;
	  } else $larguraGrafico=700;
	   
	  if (is_array($varCores)){
	    $jsonCores='[';
	  } else $jsonCores="";
	   
	  if($larguraGrafico>($largura-80)) $largura=$larguraGrafico+80;
	  $margem=($largura-$larguraGrafico)/2;
	  $strJS="\n".'<script type="text/javascript" charset="iso-8859-1" ><!--//--><![CDATA[//><!--'."\n";
	  $strJS.='var paper'.$numGrafico.' = Raphael("divGrf'.$numGrafico.'",'.$largura.','.$altura.');';
	  $strJS.="\nvar ret=paper".$numGrafico.".rect(5,5,". ($largura-10) .",". ($altura-10) .",10); \n";
	  $strJS.="ret.attr({ \"fill\": \"90-#ccf:5-#fff:95\", \"fill-opacity\": 0.5 });\n";
    $strJS.="var txt=paper".$numGrafico.".text(".$largura/2 .",30,'".str_replace("'","\\'",$strTitulo)."').attr({";

	  $strJS.='"font-size":12});';
	  
	  if ($strLinkTitulo!=null){
	    $strJS.='txt.click(function(){abrirDetalhe(\''.$strLinkTitulo.'\');});'."\n";
	    $strJS.='txt.node.style.cursor = \'pointer\';'."\n";
	    $strJS.='txt.hover(function(){this.attr({"text-decoration":"underline"})});'."\n";
	  }
	  
	  $strJS.="\nif (INFRA_WEBKIT>0) txt.node.firstChild.attributes.removeNamedItem(\"dy\");\n";
	  $strJS.="var fin = function () {\n  this.flag = paper".$numGrafico.".popup(this.bar.x, this.bar.y-4, label".$numGrafico."[this.bar.id-this.paper.bottom.id-2]+\"\\n\"+";
	  $strJS.='dataShow'.$numGrafico.'[this.bar.id-this.paper.bottom.id-2]).insertBefore(this);';
	  $strJS.="\n  this.gl=this.bar.glow({width:5,color:\"#004cff\"});\n this.bar.lighter(2);\n	};";
	  $strJS.="var fout = function () {\n  this.gl.remove();\n this.bar.resetBrightness();\n this.flag.animate({ opacity: 0}, 300, function () { this.remove();  });\n 	};\n";
	  $dataJS='var data'.$numGrafico.'=[';
	  if($jsonCores=="") {
	    $dataJS.='[';
	  }
	  $dataShow='var dataShow'.$numGrafico.'=[';
	  $labelJS='var label'.$numGrafico.'=[';
	  $refJS='var ref'.$numGrafico.'=[';
	  	
	  $primeiro=true;
	  foreach($arrDados as $dado){
	
	    if ($primeiro) {
	      $primeiro=false;
	    } else {
	      $dataJS.=',';
	      $dataShow.=',';
	      $labelJS.=',';
	      $refJS.=',';
	    }
	
	    
	    if (is_array($varCores)){
	      if (!in_array($dado[0],$arrCoresUsadas)){
	        $arrCoresUsadas[] = $dado[0];
	      }
	      $jsonCores.='"'.$varCores[$dado[0]].'",';
	    }
	    $labelJS.="'".str_replace("'","\\'",$dado[0])."'";
	    $dataShow.="'".$dado[1]."'";
	    $dataJS.= str_replace('.', '', $dado[2]);
	    $refJS.="'".InfraString::removerFormatacaoXML($dado[3])."'";
	  }
	  
	  if (is_array($varCores)){
	    $jsonCores = substr($jsonCores, 0,-1).']';
	  }
	  
	  if($jsonCores=="") {
	    $dataJS.=']';
	    $jsonCores='["'.$varCores.'"]';
	  }
	  $dataJS.="];\n";
	  $dataShow.="];\n";
	  $labelJS.="];\n";
	  $refJS.="];\n";
	
	  $strJS.=$dataJS.$dataShow.$labelJS.$refJS;
	  $strJS.='var bar=paper'.$numGrafico.'.barchart('.$margem.', 50, '.$larguraGrafico.', '.($altura-50).', data'.$numGrafico;
	  if($jsonCores!='[""]') $strJS.=',{"colors":'.$jsonCores.'}';
	  $strJS.='); bar.hover(fin,fout); bar.click(function(){	var href=ref'.$numGrafico.'[this.bar.id-this.paper.bottom.id-2]; if (href!="") abrirDetalhe(href);	});';
	  $strJS.="\n //--><!]]> </script>\n";
	
	  if (is_array($varCores) && $bolLegenda){
	    $strJS .= '<table border="0" valign="bottom" style="width:700px">'."\n";;
	
	    foreach($arrCoresUsadas as $corUsada){
	      if (isset($varCores[$corUsada])){
	        $strJS .= '<tr>'."\n";
	        $strJS .= '<td style="font-size:.8em;padding:0 .2em 0 .2em;">';
	        $strJS .= '<div style="margin:0;display:inline-table;width:8px;height:8px;background-color:'.$varCores[$corUsada].';border:1px solid black"></div>&nbsp;'.$corUsada.'&nbsp;&nbsp;';
	        $strJS .= '</td>'."\n";
	        $strJS .= '</tr>'."\n";
	      }
	    }
	
	    $strJS .= '</table>'."\n";;
	  }
	
	  return $strJS;
	}
	
	protected function gerarDesempenhoProcessosConectado(EstatisticasAtividadeDTO $parObjEstatisticasAtividadeDTO) {
	  try{

      LimiteSEI::getInstance()->configurarNivel2();

//      $t = InfraUtil::verificarTempoProcessamento();
	    //InfraDebug::getInstance()->gravar(InfraUtil::formatarTamanhoBytes(memory_get_usage()));
	    //$numSeg = InfraUtil::verificarTempoProcessamento();
	    
	     
	    $objAtividadeRN	= new AtividadeRN();
	    
	    $objInfraException = new InfraException();

	    if (!InfraString::isBolVazia($parObjEstatisticasAtividadeDTO->getDtaInicio()) || !InfraString::isBolVazia($parObjEstatisticasAtividadeDTO->getDtaFim())){
	      InfraData::validarPeriodo($parObjEstatisticasAtividadeDTO->getDtaInicio(),$parObjEstatisticasAtividadeDTO->getDtaFim(),$objInfraException);
	    }

      /*
      $dtaFimMaximo = InfraData::calcularData(1, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, $parObjEstatisticasAtividadeDTO->getDtaInicio());
      if ((InfraData::compararDatas($parObjEstatisticasAtividadeDTO->getDtaFim(),$dtaFimMaximo)-1) < 0){
        $objInfraException->adicionarValidacao('Período não pode ser superior a 1 ano.');
      }
      */

	    $objInfraException->lancarValidacoes();
	     
	    if (InfraString::isBolVazia($parObjEstatisticasAtividadeDTO->getDtaInicio()) && InfraString::isBolVazia($parObjEstatisticasAtividadeDTO->getDtaFim())){

	      //pega o primeiro andamento válido
	      $objAtividadeDTO = new AtividadeDTO();
	      $objAtividadeDTO->retDthAbertura();
	      $objAtividadeDTO->setNumIdTarefa(array(TarefaRN::$TI_GERACAO_PROCEDIMENTO,
	                                             TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE,
	                                             TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE), InfraDTO::$OPER_IN);	      
	      $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
	      $objAtividadeDTO->setOrdNumIdAtividade(InfraDTO::$TIPO_ORDENACAO_ASC);
	      
	      $objAtividadeDTO =  $objAtividadeRN->consultarRN0033($objAtividadeDTO);

	      if ($objAtividadeDTO!=null){
	        $parObjEstatisticasAtividadeDTO->setDtaInicio(substr($objAtividadeDTO->getDthAbertura(),0,10));
	      }else{
	        $parObjEstatisticasAtividadeDTO->setDtaInicio(InfraData::getStrDataAtual());
	      }
	      
	      $parObjEstatisticasAtividadeDTO->setDtaFim(InfraData::getStrDataAtual());
	      
	    }
	      
  	  $dthInicioEstatisticas = $parObjEstatisticasAtividadeDTO->getDtaInicio().' 00:00:00';
  	    
  	  if ($parObjEstatisticasAtividadeDTO->getDtaFim()==InfraData::getStrDataAtual()){
  	    $dthFinalEstatisticas = $parObjEstatisticasAtividadeDTO->getDtaFim().' '.InfraData::getStrHoraAtual();
  	  }else{
  	    $dthFinalEstatisticas = $parObjEstatisticasAtividadeDTO->getDtaFim().' 23:59:59';
  	  }
	    
	
	    $objEstatisticasAtividadeDTO = new EstatisticasAtividadeDTO();
	    $objEstatisticasAtividadeDTO->retDblIdProtocolo();
	    $objEstatisticasAtividadeDTO->retNumIdAtividade();
	    $objEstatisticasAtividadeDTO->retNumIdUnidade();
	    $objEstatisticasAtividadeDTO->retNumIdTipoProcedimentoProcedimento();
	    $objEstatisticasAtividadeDTO->retStrNomeTipoProcedimento();
	    $objEstatisticasAtividadeDTO->retDthAbertura();
	    $objEstatisticasAtividadeDTO->retDthConclusao();
	    $objEstatisticasAtividadeDTO->retNumIdTarefa();
	    $objEstatisticasAtividadeDTO->retStrStaNivelAcessoGlobalProtocolo();
	    	
	    $objEstatisticasAtividadeDTO->setDthAbertura($parObjEstatisticasAtividadeDTO->getDtaFim().' 23:59:59',InfraDTO::$OPER_MENOR_IGUAL);

	    if ($parObjEstatisticasAtividadeDTO->isSetNumIdOrgaoUnidadeGeradoraProtocolo()){
	      $objEstatisticasAtividadeDTO->setNumIdOrgaoUnidadeGeradoraProtocolo($parObjEstatisticasAtividadeDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
	    }
	    	
	    if ($parObjEstatisticasAtividadeDTO->isSetNumIdTipoProcedimentoProcedimento()){
	      $objEstatisticasAtividadeDTO->setNumIdTipoProcedimentoProcedimento($parObjEstatisticasAtividadeDTO->getNumIdTipoProcedimentoProcedimento(),InfraDTO::$OPER_IN);
	    }
	
	    $objEstatisticasAtividadeDTO->setNumIdTarefa(array(TarefaRN::$TI_GERACAO_PROCEDIMENTO,
                                              	         TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE,
                                              	         TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE,
                                              	         TarefaRN::$TI_CONCLUSAO_PROCESSO_UNIDADE,
                                              	         TarefaRN::$TI_CONCLUSAO_AUTOMATICA_UNIDADE), InfraDTO::$OPER_IN);
	    	
	    $objEstatisticasAtividadeDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO,InfraDTO::$OPER_DIFERENTE);
	
	    $objEstatisticasAtividadeDTO->adicionarCriterio(array('InclusaoProtocolo','InclusaoProtocolo'),
	                                                    array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
	                                                    array($dthInicioEstatisticas, $dthFinalEstatisticas),
	                                                    InfraDTO::$OPER_LOGICO_AND);

	    $objEstatisticasAtividadeDTO->setOrdStrNomeTipoProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);
	    $objEstatisticasAtividadeDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
	    $objEstatisticasAtividadeDTO->setOrdNumIdAtividade(InfraDTO::$TIPO_ORDENACAO_ASC);
	
	    $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());
	    $arrObjEstatisticasAtividadeDTO = $objEstatisticasBD->listar($objEstatisticasAtividadeDTO);
	
	    $objEstatisticasDTO = null;
      $dtoBase = new EstatisticasDTO();
      $dtoBase->setDblIdEstatisticas(null);
      $dtoBase->setDblIdProcedimento(null);
      $dtoBase->setNumIdTipoProcedimento(null);
      $dtoBase->setDblIdDocumento(null);
      $dtoBase->setNumIdUnidade(null);
      $dtoBase->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $dtoBase->setNumMes(null);
      $dtoBase->setNumAno(null);
      $dtoBase->setDblTempoAberto(null);
      $dtoBase->setDthAbertura(null);
      $dtoBase->setDthConclusao(null);
      $dtoBase->setDthSnapshot(null);
      $dtoBase->setDblQuantidade(null);

      if (count($arrObjEstatisticasAtividadeDTO)){

	      $objEstatisticasDTO = new EstatisticasDTO();
	      
	      $arrAndamentosPorProtocolo = InfraArray::indexarArrInfraDTO($arrObjEstatisticasAtividadeDTO, 'IdProtocolo', true);
	      
	      $arrDESEMPENHO = array();
	      $arrDesempenhoPorUnidade = array();
	      
	      $objEstatisticasDTO->setDblIdEstatisticasDesempenho(BancoSEI::getInstance()->getValorSequencia('seq_estatisticas'));

	      $dthNaoConcluido = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $parObjEstatisticasAtividadeDTO->getDtaFim()).' 00:00:00';
	       
	      $dthSnapshot = InfraData::getStrDataHoraAtual();
	
	      foreach($arrAndamentosPorProtocolo as $dblIdProtocolo => $arrAndamentosProtocolo){
	        
	        $numAndamentosProtocolo = InfraArray::contar($arrAndamentosProtocolo);
	        
	        $bolAberto = false;
	        
	        $arrAndamentosPorUnidade = InfraArray::indexarArrInfraDTO($arrAndamentosProtocolo, 'IdUnidade', true);

	        foreach($arrAndamentosPorUnidade as $numIdUnidade => $arrAndamentos){
	        
	          $objEstatisticasAtividadeDTOUltimo = $arrAndamentos[InfraArray::contar($arrAndamentos)-1];
	        
	          if ($objEstatisticasAtividadeDTOUltimo->getDthConclusao()==null ||
    	          ($objEstatisticasAtividadeDTOUltimo->getNumIdTarefa()==TarefaRN::$TI_GERACAO_PROCEDIMENTO ||
	               $objEstatisticasAtividadeDTOUltimo->getNumIdTarefa()==TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE ||
	               $objEstatisticasAtividadeDTOUltimo->getNumIdTarefa()==TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE)){
	            $bolAberto = true;
	            break;
	          }
	        }
	         
	        if ($bolAberto && $parObjEstatisticasAtividadeDTO->getStrSinConcluidos()=='S'){
	          continue;
	        }
	        
	        	
	        $numIdTipoProcedimento = $arrAndamentosProtocolo[0]->getNumIdTipoProcedimentoProcedimento();
	        
	        foreach($arrAndamentosPorUnidade as $numIdUnidade => $arrAndamentos){
	          
  	        $arrTarefaPeriodo = array();
  	        	
            $numAndamentos = InfraArray::contar($arrAndamentos);
            
            for($i=0;$i<$numAndamentos;$i++){
              
              //enquanto não chegar no último andamento faz encadeamento por DthAbertura
              if (($i+1)<$numAndamentos){
                $arrTarefaPeriodo[] = array($arrAndamentos[$i]->getNumIdTarefa(), $arrAndamentos[$i]->getDthAbertura(), $arrAndamentos[$i+1]->getDthAbertura());
  
              //faz análise do último andamento  
              }else{
  
                if ($arrAndamentos[$i]->getDthConclusao()==null){
                  $arrTarefaPeriodo[] = array($arrAndamentos[$i]->getNumIdTarefa(), $arrAndamentos[$i]->getDthAbertura(), $dthNaoConcluido);
                }else{
                  	
                  if ($arrAndamentos[$i]->getNumIdTarefa()==TarefaRN::$TI_GERACAO_PROCEDIMENTO ||
                      $arrAndamentos[$i]->getNumIdTarefa()==TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE ||
                      $arrAndamentos[$i]->getNumIdTarefa()==TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE){
                    	
                    //se data de conclusão é igual ou anterior ao período final de busca então assume após o período senão deixa data original
                    if (InfraData::compararDataHora($dthFinalEstatisticas, $arrAndamentos[$i]->getDthConclusao())<=0){
                      $arrTarefaPeriodo[] = array($arrAndamentos[$i]->getNumIdTarefa(), $arrAndamentos[$i]->getDthAbertura(), $dthNaoConcluido);
                    }else{
                      $arrTarefaPeriodo[] = array($arrAndamentos[$i]->getNumIdTarefa(), $arrAndamentos[$i]->getDthAbertura(), $arrAndamentos[$i]->getDthConclusao());
                    }
                  }else{
                    $arrTarefaPeriodo[] = array($arrAndamentos[$i]->getNumIdTarefa(), $arrAndamentos[$i]->getDthAbertura(), $arrAndamentos[$i]->getDthConclusao());
                  }
                }
              }
            }

            //faz cálculo de tempo por unidade
            
            $dblTempo = 0;
            
            foreach($arrTarefaPeriodo as $arrPeriodo){
              
              $numIdTarefa = $arrPeriodo[0];
            
              if ($numIdTarefa==TarefaRN::$TI_GERACAO_PROCEDIMENTO ||
                  $numIdTarefa==TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE ||
                  $numIdTarefa==TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE){
            
                //engloba todo o período de busca
                if (InfraData::compararDataHora($arrPeriodo[1],$dthInicioEstatisticas)>0 &&
                    InfraData::compararDataHora($dthFinalEstatisticas,$arrPeriodo[2])>0){
            
                  $dblTempo = InfraData::compararDataHora($dthInicioEstatisticas,$dthFinalEstatisticas);
                  break;
            
                  //inicio fora do período e final dentro
                }else if (InfraData::compararDataHora($arrPeriodo[1],$dthInicioEstatisticas)>0 &&
                          InfraData::compararDataHora($dthInicioEstatisticas,$arrPeriodo[2])>=0 &&
                          InfraData::compararDataHora($arrPeriodo[2],$dthFinalEstatisticas)>=0){
            
                  $dblTempo = $dblTempo + InfraData::compararDataHora($dthInicioEstatisticas,$arrPeriodo[2]);
            
                  //inicio e final dentro do período
                }else if (InfraData::compararDataHora($dthInicioEstatisticas, $arrPeriodo[1])>=0 &&
                          InfraData::compararDataHora($arrPeriodo[1],$dthFinalEstatisticas)>=0 &&
                          InfraData::compararDataHora($dthInicioEstatisticas, $arrPeriodo[2])>=0 &&
                          InfraData::compararDataHora($arrPeriodo[2],$dthFinalEstatisticas)>=0){
            
            
                  $dblTempo = $dblTempo + InfraData::compararDataHora($arrPeriodo[1],$arrPeriodo[2]);
            
                  //inicio dentro do período e final fora
                }else if (InfraData::compararDataHora($dthInicioEstatisticas, $arrPeriodo[1])>=0 &&
                          InfraData::compararDataHora($arrPeriodo[1],$dthFinalEstatisticas)>=0 &&
                          InfraData::compararDataHora($dthFinalEstatisticas, $arrPeriodo[2])>0){
            
                  $dblTempo = $dblTempo + InfraData::compararDataHora($arrPeriodo[1],$dthFinalEstatisticas);
                }
              }
            }
                
            if ($dblTempo > 0){
              $dto = clone($dtoBase);
              $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasDesempenho());
              $dto->setDblIdProcedimento($dblIdProtocolo);
              $dto->setNumIdTipoProcedimento($numIdTipoProcedimento);
              $dto->setNumIdUnidade($numIdUnidade);
              $dto->setDblTempoAberto($dblTempo);
              $dto->setDthSnapshot($dthSnapshot);
              $this->acumular($dto);
              
              if (!isset($arrDesempenhoPorUnidade[$numIdTipoProcedimento][$numIdUnidade])){
                $arrDesempenhoPorUnidade[$numIdTipoProcedimento][$numIdUnidade][0] = $dblTempo;
                $arrDesempenhoPorUnidade[$numIdTipoProcedimento][$numIdUnidade][1] = array($dblIdProtocolo);
              }else{
                $arrDesempenhoPorUnidade[$numIdTipoProcedimento][$numIdUnidade][0] = $arrDesempenhoPorUnidade[$numIdTipoProcedimento][$numIdUnidade][0] + $dblTempo;
                
                if (!in_array($dblIdProtocolo,$arrDesempenhoPorUnidade[$numIdTipoProcedimento][$numIdUnidade][1])){
                  $arrDesempenhoPorUnidade[$numIdTipoProcedimento][$numIdUnidade][1][] = $dblIdProtocolo;
                }
              }
            }
            
            $numTarefas = InfraArray::contar($arrTarefaPeriodo);
          }
          
          unset($arrAndamentosPorUnidade);
          
          // Faz cálculo para o processo inteiro unificando períodos de tramitação paralela nas unidades
          
          for($i=0;$i<$numAndamentosProtocolo;$i++){
             
            if ($arrAndamentosProtocolo[$i] == null){
              continue;
            } 
              
            if ($arrAndamentosProtocolo[$i]->getNumIdTarefa()==TarefaRN::$TI_GERACAO_PROCEDIMENTO ||
                $arrAndamentosProtocolo[$i]->getNumIdTarefa()==TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE ||
                $arrAndamentosProtocolo[$i]->getNumIdTarefa()==TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE){

              for($j = ($i + 1); $j < $numAndamentosProtocolo; $j++){

                if ($arrAndamentosProtocolo[$j]==null){
                  continue;
                }
                  
                if ($arrAndamentosProtocolo[$j]->getNumIdTarefa()==TarefaRN::$TI_GERACAO_PROCEDIMENTO ||
                    $arrAndamentosProtocolo[$j]->getNumIdTarefa()==TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE ||
                    $arrAndamentosProtocolo[$j]->getNumIdTarefa()==TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE){
                  
                  //data de abertura ocorre antes do final do andamento
                  if (InfraData::compararDataHora($arrAndamentosProtocolo[$i]->getDthConclusao(), $arrAndamentosProtocolo[$j]->getDthAbertura())<=0){
                      
                    //se a data final ocorre após o final
                    if (InfraData::compararDataHora($arrAndamentosProtocolo[$i]->getDthConclusao(), $arrAndamentosProtocolo[$j]->getDthConclusao())>0){
                      $arrAndamentosProtocolo[$i]->setDthConclusao($arrAndamentosProtocolo[$j]->getDthConclusao());
                    }
                      
                    $arrAndamentosProtocolo[$j] = null;
                  }
                }
              }              
            }
          }

          $arrAndamentos = array();
          
          for($i=0;$i<$numAndamentosProtocolo;$i++){
            if ($arrAndamentosProtocolo[$i]!=null){
              $arrAndamentos[] = $arrAndamentosProtocolo[$i];
            }
          }
          
          $numAndamentos = InfraArray::contar($arrAndamentos);
          
          $arrTarefaPeriodo = array();
          
          for($i=0;$i<$numAndamentos;$i++){
          
            //enquanto não chegar no último andamento faz encadeamento por DthAbertura
            if (($i+1)<$numAndamentos){
              
              $arrTarefaPeriodo[] = array($arrAndamentos[$i]->getNumIdTarefa(), $arrAndamentos[$i]->getDthAbertura(), $arrAndamentos[$i+1]->getDthAbertura());
          
              //faz análise do último andamento
            }else{
          
              if ($arrAndamentos[$i]->getDthConclusao()==null || $bolAberto){
                $arrTarefaPeriodo[] = array($arrAndamentos[$i]->getNumIdTarefa(), $arrAndamentos[$i]->getDthAbertura(), $dthNaoConcluido);
              }else{
                 
                if ($arrAndamentos[$i]->getNumIdTarefa()==TarefaRN::$TI_GERACAO_PROCEDIMENTO ||
                    $arrAndamentos[$i]->getNumIdTarefa()==TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE ||
                    $arrAndamentos[$i]->getNumIdTarefa()==TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE){
                   
                  //se data de conclusão é igual ou anterior ao período final de busca então assume após o período senão deixa data original
                  if (InfraData::compararDataHora($dthFinalEstatisticas, $arrAndamentos[$i]->getDthConclusao())<=0){
                    $arrTarefaPeriodo[] = array($arrAndamentos[$i]->getNumIdTarefa(), $arrAndamentos[$i]->getDthAbertura(), $dthNaoConcluido);
                  }else{
                    $arrTarefaPeriodo[] = array($arrAndamentos[$i]->getNumIdTarefa(), $arrAndamentos[$i]->getDthAbertura(), $arrAndamentos[$i]->getDthConclusao());
                  }
                }else{
                  $arrTarefaPeriodo[] = array($arrAndamentos[$i]->getNumIdTarefa(), $arrAndamentos[$i]->getDthAbertura(), $arrAndamentos[$i]->getDthConclusao());
                }
              }
            }
          }

          $dblTempo = 0;
          
          foreach($arrTarefaPeriodo as $arrPeriodo){
          
            $numIdTarefa = $arrPeriodo[0];
          
            //engloba todo o período de busca
            if (InfraData::compararDataHora($arrPeriodo[1],$dthInicioEstatisticas)>0 &&
                InfraData::compararDataHora($dthFinalEstatisticas,$arrPeriodo[2])>0){
        
              $dblTempo = InfraData::compararDataHora($dthInicioEstatisticas,$dthFinalEstatisticas);
              break;
        
              //inicio fora do período e final dentro
            }else if (InfraData::compararDataHora($arrPeriodo[1],$dthInicioEstatisticas)>0 &&
                      InfraData::compararDataHora($dthInicioEstatisticas,$arrPeriodo[2])>=0 &&
                      InfraData::compararDataHora($arrPeriodo[2],$dthFinalEstatisticas)>=0){
        
              $dblTempo = $dblTempo + InfraData::compararDataHora($dthInicioEstatisticas,$arrPeriodo[2]);
        
              //inicio e final dentro do período
            }else if (InfraData::compararDataHora($dthInicioEstatisticas, $arrPeriodo[1])>=0 &&
                      InfraData::compararDataHora($arrPeriodo[1],$dthFinalEstatisticas)>=0 &&
                      InfraData::compararDataHora($dthInicioEstatisticas, $arrPeriodo[2])>=0 &&
                      InfraData::compararDataHora($arrPeriodo[2],$dthFinalEstatisticas)>=0){
        
        
              $dblTempo = $dblTempo + InfraData::compararDataHora($arrPeriodo[1],$arrPeriodo[2]);
        
              //inicio dentro do período e final fora
            }else if (InfraData::compararDataHora($dthInicioEstatisticas, $arrPeriodo[1])>=0 &&
                      InfraData::compararDataHora($arrPeriodo[1],$dthFinalEstatisticas)>=0 &&
                      InfraData::compararDataHora($dthFinalEstatisticas, $arrPeriodo[2])>0){
        
              $dblTempo = $dblTempo + InfraData::compararDataHora($arrPeriodo[1],$dthFinalEstatisticas);
            }
          }
          
          if ($dblTempo > 0){
          
            if (!isset($arrDESEMPENHO[$numIdTipoProcedimento])){
              $arrDESEMPENHO[$numIdTipoProcedimento][0] = $dblTempo;
              $arrDESEMPENHO[$numIdTipoProcedimento][1] = 1;
            }else{
              $arrDESEMPENHO[$numIdTipoProcedimento][0] = bcadd($arrDESEMPENHO[$numIdTipoProcedimento][0],$dblTempo);
              $arrDESEMPENHO[$numIdTipoProcedimento][1] = $arrDESEMPENHO[$numIdTipoProcedimento][1] + 1;
            }
          
            if ($dblTempo > 0){
              $dto = clone($dtoBase);
              $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasDesempenho());
              $dto->setDblIdProcedimento($dblIdProtocolo);
              $dto->setNumIdTipoProcedimento($numIdTipoProcedimento);
              $dto->setDblTempoAberto($dblTempo);
              
              $dto->setDthAbertura($arrTarefaPeriodo[0][1]);
              
              $numTarefas = InfraArray::contar($arrTarefaPeriodo);
              
              //conclusao do último andamento dentro do período
              if (!$bolAberto){
                $dto->setDthConclusao($arrAndamentosProtocolo[$numAndamentosProtocolo-1]->getDthConclusao());
              }else{
                $dto->setDthConclusao(null);
              }
              
              $dto->setDthSnapshot($dthSnapshot);
              $dto->setDblQuantidade(null);
              $this->acumular($dto);
            }
          }
	      }
	      	
	      //print_r($arrDesempenhoPorUnidade);die;
	      
	      $arrTempoPorUnidade = array();

	      foreach($arrDesempenhoPorUnidade as $numIdTipoProcedimento => $arrTemp){
  	      foreach($arrTemp as $numIdUnidade => $arr){
  	        
  	        $numTempoTipoUnidade = bcdiv($arr[0],InfraArray::contar($arr[1]),0);
  	        
  	        $dto = clone($dtoBase);
  	        $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasDesempenho());
  	        $dto->setNumIdTipoProcedimento($numIdTipoProcedimento);
  	        $dto->setNumIdUnidade($numIdUnidade);
  	        $dto->setDblTempoAberto($numTempoTipoUnidade);
  	        $dto->setDthSnapshot($dthSnapshot);
  	        $dto->setDblQuantidade(InfraArray::contar($arr[1]));
  	        $this->acumular($dto);
  	        
  	        if (!isset($arrTempoPorUnidade[$numIdUnidade])){
  	          $arrTempoPorUnidade[$numIdUnidade][0] = $arr[0];
  	          $arrTempoPorUnidade[$numIdUnidade][1] = InfraArray::contar($arr[1]);
  	        }else{
  	          $arrTempoPorUnidade[$numIdUnidade][0] = $arrTempoPorUnidade[$numIdUnidade][0] + $arr[0];
  	          $arrTempoPorUnidade[$numIdUnidade][1] = $arrTempoPorUnidade[$numIdUnidade][1] + InfraArray::contar($arr[1]);
  	        }
  	      }
	      }

	      foreach($arrTempoPorUnidade as $numIdUnidade => $arr){

          $dto = clone($dtoBase);
  	      $dto->setDblIdEstatisticas($objEstatisticasDTO->getDblIdEstatisticasDesempenho());
  	      $dto->setNumIdUnidade($numIdUnidade);
  	      $dto->setDblTempoAberto(bcdiv($arr[0],$arr[1],0));
  	      $dto->setDthSnapshot($dthSnapshot);
  	      $dto->setDblQuantidade($arr[1]);
          $this->acumular($dto);
	      }
	      	       
	      
	      if (is_array($arrDESEMPENHO)){
	        foreach ($arrDESEMPENHO as $numIdTipoProcedimento => $dblTempo){
             $arrDESEMPENHO[$numIdTipoProcedimento][0] = bcdiv($arrDESEMPENHO[$numIdTipoProcedimento][0],$arrDESEMPENHO[$numIdTipoProcedimento][1],0);
	        }
	      }
	      
	      $objEstatisticasDTO->setArrEstatisticasDESEMPENHO($arrDESEMPENHO);
      }

      $this->acumular(null);

	    //InfraDebug::getInstance()->gravar(InfraUtil::formatarTamanhoBytes(memory_get_usage()));
	    //InfraDebug::getInstance()->gravar(InfraUtil::verificarTempoProcessamento($numSeg).' s');

//      $t = InfraUtil::verificarTempoProcessamento($t);
//      InfraDebug::getInstance()->gravar('Tempo execução EstatisticasRN->gerar: '.$t.' s');
	    return $objEstatisticasDTO;
	
	  }catch(Exception $e){
	    throw new InfraException('Erro gerando estatísticas de desempenho de processos.',$e);
	  }
	}

  protected function gerarArquivamentoConectado(EstatisticasArquivamentoDTO $parObjEstatisticasArquivamentoDTO)
  {

    try {

      LimiteSEI::getInstance()->configurarNivel2();

//      $t = InfraUtil::verificarTempoProcessamento();

      $objInfraException = new InfraException();

      InfraData::validarPeriodo($parObjEstatisticasArquivamentoDTO->getDtaInicio(), $parObjEstatisticasArquivamentoDTO->getDtaFim(), $objInfraException);

      $dtaFimMaximo = InfraData::calcularData(1, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, $parObjEstatisticasArquivamentoDTO->getDtaInicio());
      if ((InfraData::compararDatas($parObjEstatisticasArquivamentoDTO->getDtaFim(), $dtaFimMaximo) - 1) < 0) {
        $objInfraException->adicionarValidacao('Período não pode ser superior a 1 ano.');
      }

      $objInfraException->lancarValidacoes();

      $objEstatisticasBD = new EstatisticasBD($this->getObjInfraIBanco());

     //documentos arquivados no período

      $objEstatisticasArquivamentoDTO=new EstatisticasArquivamentoDTO();

      $objEstatisticasArquivamentoDTO->setNumIdUnidadeAtividade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objEstatisticasArquivamentoDTO->adicionarCriterio(array('AberturaAtividade', 'AberturaAtividade'),
          array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
          array($parObjEstatisticasArquivamentoDTO->getDtaInicio(), $parObjEstatisticasArquivamentoDTO->getDtaFim()),
          array(InfraDTO::$OPER_LOGICO_AND));
      $objEstatisticasArquivamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_ARQUIVAMENTO);
      $objEstatisticasArquivamentoDTO->setStrNome('DOCUMENTO');

      $objEstatisticasArquivamentoDTO->retStrNomeSerie();
      $objEstatisticasArquivamentoDTO->retDblIdDocumento();
      $objEstatisticasArquivamentoDTO->retDthAberturaAtividade();
      $objEstatisticasArquivamentoDTO->retNumIdSerieDocumento();
      $objEstatisticasArquivamentoDTO->setDistinct(true);
      $objEstatisticasArquivamentoDTO->setOrdStrNomeSerie(InfraDTO::$TIPO_ORDENACAO_ASC);


      $ret = $objEstatisticasBD->listar($objEstatisticasArquivamentoDTO);


      $arrARQUIVADOS=array();

      if (count($ret)) {
        foreach ($ret as $objEstatisticasArquivamentoDTO2) {
          $strMes = substr($objEstatisticasArquivamentoDTO2->getDthAberturaAtividade(), 3, 2);
          $strAno = substr($objEstatisticasArquivamentoDTO2->getDthAberturaAtividade(), 6, 4);
          $numIdSerieDocumento = $objEstatisticasArquivamentoDTO2->getNumIdSerieDocumento();

          //arquivados no período ano/mês/serie documento
          if (isset($arrARQUIVADOS[$numIdSerieDocumento][$strAno][$strMes])) {
            $arrARQUIVADOS[$numIdSerieDocumento][$strAno][$strMes]++;
          } else {
            $arrARQUIVADOS[$numIdSerieDocumento][$strAno][$strMes] = 1;
          }
        }
      }

      //documentos desarquivados no período
      $objEstatisticasArquivamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_DESARQUIVAMENTO);
      $ret = $objEstatisticasBD->listar($objEstatisticasArquivamentoDTO);

      $arrDESARQUIVADOS=array();

      if (count($ret)) {
        foreach ($ret as $objEstatisticasArquivamentoDTO2) {
          $strMes = substr($objEstatisticasArquivamentoDTO2->getDthAberturaAtividade(), 3, 2);
          $strAno = substr($objEstatisticasArquivamentoDTO2->getDthAberturaAtividade(), 6, 4);
          $numIdSerieDocumento = $objEstatisticasArquivamentoDTO2->getNumIdSerieDocumento();

          //arquivados no período ano/mês/serie documento
          if (isset($arrDESARQUIVADOS[$numIdSerieDocumento][$strAno][$strMes])) {
            $arrDESARQUIVADOS[$numIdSerieDocumento][$strAno][$strMes]++;
          } else {
            $arrDESARQUIVADOS[$numIdSerieDocumento][$strAno][$strMes] = 1;
          }
        }
      }

      //documentos recebidos no período
      $objEstatisticasArquivamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_RECEBIMENTO_ARQUIVO);
      $ret = $objEstatisticasBD->listar($objEstatisticasArquivamentoDTO);

      $arrRECEBIDOS=array();

      if (count($ret)) {
        foreach ($ret as $objEstatisticasArquivamentoDTO2) {
          $strMes = substr($objEstatisticasArquivamentoDTO2->getDthAberturaAtividade(), 3, 2);
          $strAno = substr($objEstatisticasArquivamentoDTO2->getDthAberturaAtividade(), 6, 4);
          $numIdSerieDocumento = $objEstatisticasArquivamentoDTO2->getNumIdSerieDocumento();

          //arquivados no período ano/mês/serie documento
          if (isset($arrRECEBIDOS[$numIdSerieDocumento][$strAno][$strMes])) {
            $arrRECEBIDOS[$numIdSerieDocumento][$strAno][$strMes]++;
          } else {
            $arrRECEBIDOS[$numIdSerieDocumento][$strAno][$strMes] = 1;
          }
        }
      }


      //Localizadores utilizados no período
      $objEstatisticasArquivamentoDTO=new EstatisticasArquivamentoDTO();

      $objEstatisticasArquivamentoDTO->setNumIdUnidadeAtividade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objEstatisticasArquivamentoDTO->adicionarCriterio(array('AberturaAtividade', 'AberturaAtividade'),
          array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
          array($parObjEstatisticasArquivamentoDTO->getDtaInicio(), $parObjEstatisticasArquivamentoDTO->getDtaFim()),
          array(InfraDTO::$OPER_LOGICO_AND));
      $objEstatisticasArquivamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_ARQUIVAMENTO);
      $objEstatisticasArquivamentoDTO->setStrNome('LOCALIZADOR');
      $objEstatisticasArquivamentoDTO->retNumIdTipoLocalizadorAndamento();
      $objEstatisticasArquivamentoDTO->retStrNomeTipoLocalizadorAndamento();
      $objEstatisticasArquivamentoDTO->retStrStaEstadoLocalizadorAndamento();
      $objEstatisticasArquivamentoDTO->retStrIdOrigem();
      $objEstatisticasArquivamentoDTO->setDistinct(true);
      $objEstatisticasArquivamentoDTO->setOrdStrNomeTipoLocalizadorAndamento(InfraDTO::$TIPO_ORDENACAO_ASC);


      $ret = $objEstatisticasBD->listar($objEstatisticasArquivamentoDTO);


      $arrLOCALIZADORES=array();
      if (count($ret)) {
        foreach ($ret as $objEstatisticasArquivamentoDTO2) {
          $numIdTipoLocalizador = $objEstatisticasArquivamentoDTO2->getNumIdTipoLocalizadorAndamento();
          $strStaEstado=$objEstatisticasArquivamentoDTO2->getStrStaEstadoLocalizadorAndamento();

          if (isset($arrLOCALIZADORES[$numIdTipoLocalizador][$strStaEstado])) {
            $arrLOCALIZADORES[$numIdTipoLocalizador][$strStaEstado]++;
          } else {
            $arrLOCALIZADORES[$numIdTipoLocalizador][$strStaEstado] = 1;
          }
        }
      }

      $parObjEstatisticasArquivamentoDTO->setArrArquivados($arrARQUIVADOS);
      $parObjEstatisticasArquivamentoDTO->setArrRecebidos($arrRECEBIDOS);
      $parObjEstatisticasArquivamentoDTO->setArrDesarquivados($arrDESARQUIVADOS);
      $parObjEstatisticasArquivamentoDTO->setArrLocalizadores($arrLOCALIZADORES);


//      $t = InfraUtil::verificarTempoProcessamento($t);
//      InfraDebug::getInstance()->gravar('Tempo execução EstatisticasRN->gerar: '.$t.' s');

      return $parObjEstatisticasArquivamentoDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro gerando estatísticas.', $e);
    }

  }

  protected function gerarArquivamentoAcervoConectado()
  {
    try {

      LimiteSEI::getInstance()->configurarNivel2();

      //documentos arquivados
      $objArquivamentoRN=new ArquivamentoRN();
      $objArquivamentoDTO=new ArquivamentoDTO();
      $objArquivamentoDTO->setNumTipoFkArquivamento(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objArquivamentoDTO->setNumIdUnidadeArquivamento(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objArquivamentoDTO->setStrStaArquivamento(array(ArquivamentoRN::$TA_ARQUIVADO,ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO),InfraDTO::$OPER_IN);
      $objArquivamentoDTO->retNumIdSerieDocumento();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->setOrdStrNomeSerieDocumento(InfraDTO::$TIPO_ORDENACAO_ASC);
      $arrObjArquivamentoDTO=$objArquivamentoRN->listar($objArquivamentoDTO);

      $arrARQUIVADOS=array();
      if (count($arrObjArquivamentoDTO)) {
        foreach ($arrObjArquivamentoDTO as $objArquivamentoDTO) {
          $numIdSerieDocumento = $objArquivamentoDTO->getNumIdSerieDocumento();
          if (isset($arrARQUIVADOS[$numIdSerieDocumento])) {
            $arrARQUIVADOS[$numIdSerieDocumento]++;
          } else {
            $arrARQUIVADOS[$numIdSerieDocumento] = 1;
          }
        }
      }

      //documentos desarquivados
      $objArquivamentoRN=new ArquivamentoRN();
      $objArquivamentoDTO=new ArquivamentoDTO();
      $objArquivamentoDTO->setNumTipoFkDesarquivamento(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objArquivamentoDTO->setNumIdUnidadeDesarquivamento(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_DESARQUIVADO);
      $objArquivamentoDTO->retNumIdSerieDocumento();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->setOrdStrNomeSerieDocumento(InfraDTO::$TIPO_ORDENACAO_ASC);
      $arrObjArquivamentoDTO=$objArquivamentoRN->listar($objArquivamentoDTO);

      $arrDESARQUIVADOS=array();
      if (count($arrObjArquivamentoDTO)) {
        foreach ($arrObjArquivamentoDTO as $objArquivamentoDTO) {
          $numIdSerieDocumento = $objArquivamentoDTO->getNumIdSerieDocumento();
          if (isset($arrDESARQUIVADOS[$numIdSerieDocumento])) {
            $arrDESARQUIVADOS[$numIdSerieDocumento]++;
          } else {
            $arrDESARQUIVADOS[$numIdSerieDocumento] = 1;
          }
        }
      }

      //documentos recebidos
      $objArquivamentoDTO=new ArquivamentoDTO();
      $objArquivamentoDTO->setNumTipoFkRecebimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objArquivamentoDTO->setNumIdUnidadeRecebimento(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_RECEBIDO);
      $objArquivamentoDTO->retNumIdSerieDocumento();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->setOrdStrNomeSerieDocumento(InfraDTO::$TIPO_ORDENACAO_ASC);
      $arrObjArquivamentoDTO=$objArquivamentoRN->listar($objArquivamentoDTO);

      $arrRECEBIDOS=array();
      if (count($arrObjArquivamentoDTO)) {
        foreach ($arrObjArquivamentoDTO as $objArquivamentoDTO) {
          $numIdSerieDocumento = $objArquivamentoDTO->getNumIdSerieDocumento();
          if (isset($arrRECEBIDOS[$numIdSerieDocumento])) {
            $arrRECEBIDOS[$numIdSerieDocumento]++;
          } else {
            $arrRECEBIDOS[$numIdSerieDocumento] = 1;
          }
        }
      }

      //Localizadores
      $objLocalizadorRN=new LocalizadorRN();
      $objLocalizadorDTO=new LocalizadorDTO();
      $objLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objLocalizadorDTO->retNumIdTipoLocalizador();
      $objLocalizadorDTO->retNumIdLocalizador();
      $objLocalizadorDTO->retStrStaEstado();
      $objLocalizadorDTO->setOrdStrNomeTipoLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
      $arrObjLocalizadorDTO=$objLocalizadorRN->listarRN0622($objLocalizadorDTO);

      $arrLOCALIZADORES=array();
      if (count($arrObjLocalizadorDTO)) {
        foreach ($arrObjLocalizadorDTO as $objLocalizadorDTO) {
          $numIdTipoLocalizador = $objLocalizadorDTO->getNumIdTipoLocalizador();
          $strStaEstado=$objLocalizadorDTO->getStrStaEstado();
          if (isset($arrLOCALIZADORES[$numIdTipoLocalizador][$strStaEstado])) {
            $arrLOCALIZADORES[$numIdTipoLocalizador][$strStaEstado]++;
          } else {
            $arrLOCALIZADORES[$numIdTipoLocalizador][$strStaEstado] = 1;
          }
        }
      }

      $ret=new EstatisticasArquivamentoDTO();
      $ret->setArrArquivados($arrARQUIVADOS);
      $ret->setArrDesarquivados($arrDESARQUIVADOS);
      $ret->setArrRecebidos($arrRECEBIDOS);
      $ret->setArrLocalizadores($arrLOCALIZADORES);

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro gerando estatísticas de acervo completo.', $e);
    }

  }

}
?>