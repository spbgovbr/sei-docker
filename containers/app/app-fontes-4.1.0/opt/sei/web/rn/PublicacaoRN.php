<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 25/11/2008 - criado por mga
 *
 * Versão do Gerador de Código: 1.25.0
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__).'/../SEI.php';

class PublicacaoRN extends InfraRN {
		
	public static $TM_PUBLICACAO = '1';
	public static $TM_RETIFICACAO = '2';
	public static $TM_REPUBLICACAO = '3';
	public static $TM_APOSTILAMENTO = '4';

	public static $TE_AGENDADO = 'A';
	public static $TE_PUBLICADO = 'P';

	public function __construct(){
		parent::__construct();
	}

	protected function inicializarObjInfraIBanco(){
		return BancoSEI::getInstance();
	}
		
	public function listarValoresMotivoRN1056(){
		try {

			$arr = array();

			$objMotivoPublicacaoDTO = new MotivoPublicacaoDTO();
			$objMotivoPublicacaoDTO->setStrStaMotivo(PublicacaoRN::$TM_PUBLICACAO);
			$objMotivoPublicacaoDTO->setStrDescricao('Publicação');
			$arr[] = $objMotivoPublicacaoDTO;
			 
			$objMotivoPublicacaoDTO = new MotivoPublicacaoDTO();
			$objMotivoPublicacaoDTO->setStrStaMotivo(PublicacaoRN::$TM_RETIFICACAO);
			$objMotivoPublicacaoDTO->setStrDescricao('Retificação');
			$arr[] = $objMotivoPublicacaoDTO;

			$objMotivoPublicacaoDTO = new MotivoPublicacaoDTO();
			$objMotivoPublicacaoDTO->setStrStaMotivo(PublicacaoRN::$TM_REPUBLICACAO);
			$objMotivoPublicacaoDTO->setStrDescricao('Republicação');
			$arr[] = $objMotivoPublicacaoDTO;
			 
			$objMotivoPublicacaoDTO = new MotivoPublicacaoDTO();
			$objMotivoPublicacaoDTO->setStrStaMotivo(PublicacaoRN::$TM_APOSTILAMENTO);
			$objMotivoPublicacaoDTO->setStrDescricao('Apostilamento');
			$arr[] = $objMotivoPublicacaoDTO;
			 
			return $arr;

		}catch(Exception $e){
			throw new InfraException('Erro listando valores de Motivo.',$e);
		}
	}

	private function validarDblIdDocumentoRN1029(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objPublicacaoDTO->getDblIdDocumento())){
			$objInfraException->adicionarValidacao('Documento não informado.');
		}
	}

	private function validarNumIdUnidadeRN1216(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objPublicacaoDTO->getNumIdUnidade())){
			$objInfraException->adicionarValidacao('Unidade não informada.');
		}
	}

	private function validarNumNumeroRN1217(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objPublicacaoDTO->getNumNumero())){
			$objPublicacaoDTO->setNumNumero(null);
		}
	}
	
	protected function gravarCamposPesquisaControlado($arrObjPublicacaoDTO){
		
		$arrIdUnidade = array_unique(InfraArray::converterArrInfraDTO($arrObjPublicacaoDTO,'IdUnidadeResponsavelDocumento'));

    $objUnidadePublicacaoRN = new UnidadePublicacaoRN();
	  foreach($arrIdUnidade as $numIdUnidade){
      $objUnidadePublicacaoDTO = new UnidadePublicacaoDTO();
      $objUnidadePublicacaoDTO->retNumIdUnidadePublicacao();
		  $objUnidadePublicacaoDTO->setNumIdUnidade($numIdUnidade);
      $objUnidadePublicacaoDTO->setNumMaxRegistrosRetorno(1);

		  if ($objUnidadePublicacaoRN->consultar($objUnidadePublicacaoDTO) == null){
		    $objUnidadePublicacaoRN->cadastrar($objUnidadePublicacaoDTO);
		  }
	  }
	  
	  $arrSerieOrgao = array();
	  foreach($arrObjPublicacaoDTO as $objPublicacaoDTO){
	  	$strChave = $objPublicacaoDTO->getNumIdSerieDocumento().'#'.$objPublicacaoDTO->getNumIdOrgaoUnidadeResponsavelDocumento();
	  	if (!in_array($strChave,$arrSerieOrgao)){
	  	  $arrSerieOrgao[] = $strChave;
	  	}
	  }

    $objSeriePublicacaoRN = new SeriePublicacaoRN();
	  foreach($arrSerieOrgao as $strSerieOrgao){
	  	$arrSerieOrgao = explode('#',$strSerieOrgao);

	  	$objSeriePublicacaoDTO = new SeriePublicacaoDTO();
      $objSeriePublicacaoDTO->retNumIdSeriePublicacao();
	  	$objSeriePublicacaoDTO->setNumIdSerie($arrSerieOrgao[0]);
	  	$objSeriePublicacaoDTO->setNumIdOrgao($arrSerieOrgao[1]);
      $objSeriePublicacaoDTO->setNumMaxRegistrosRetorno(1);

	  	if ($objSeriePublicacaoRN->consultar($objSeriePublicacaoDTO) == null){
	  		$objSeriePublicacaoRN->cadastrar($objSeriePublicacaoDTO);
	  	}
	  }
	}

	private function validarSeEhPublicavelVeiculo(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
	  //Recuperar dados do documento
	  $objDocumentoDTO = new DocumentoDTO();
	  $objDocumentoDTO->retNumIdSerie();
	  $objDocumentoDTO->retNumIdUnidadeResponsavel();
	  $objDocumentoDTO->retDblIdProtocoloAgrupadorProtocolo();
	  $objDocumentoDTO->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());
	  $objDocumentoRN = new DocumentoRN();	  
	  $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
	  
	  $objRelSerieVeiculoPublicacaoRN = new RelSerieVeiculoPublicacaoRN();
	  $objRelSerieVeiculoPublicacaoDTO = new RelSerieVeiculoPublicacaoDTO();
	  $objRelSerieVeiculoPublicacaoDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
    $objRelSerieVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());

	  if ($objRelSerieVeiculoPublicacaoRN->contar($objRelSerieVeiculoPublicacaoDTO) == 0){
	    $objInfraException->lancarValidacao('Tipo do documento não é publicável no veículo escolhido.');	    
	  }	  	  	  	  	  	 
	}
	
	private function validarStaMotivoRN1033(PublicacaoDTO $objPublicacaoDTO, $arrPublicacoes, InfraException $objInfraException){
	  
	  //Recuperar dados do documento
	  $objDocumentoDTO = new DocumentoDTO();
	  $objDocumentoDTO->retNumIdSerie();
	  $objDocumentoDTO->retNumIdUnidadeResponsavel();
	  $objDocumentoDTO->retDblIdProtocoloAgrupadorProtocolo();
	  $objDocumentoDTO->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());
	  $objDocumentoRN = new DocumentoRN();
	  $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
		 
		if (InfraString::isBolVazia($objPublicacaoDTO->getStrStaMotivo())){
			$objInfraException->adicionarValidacao('Motivo não informado.');
		}else{
			if (!in_array($objPublicacaoDTO->getStrStaMotivo(),InfraArray::converterArrInfraDTO($this->listarValoresMotivoRN1056(),'StaMotivo'))){
				$objInfraException->adicionarValidacao('Motivo inválido.');
			}
		}
		
		$numRegistros = InfraArray::contar($arrPublicacoes);

		if($objPublicacaoDTO->getStrStaMotivo() == PublicacaoRN::$TM_PUBLICACAO){
			 
			for($i=0;$i<$numRegistros;$i++){
				if($arrPublicacoes[$i]->getStrStaMotivo() == PublicacaoRN::$TM_RETIFICACAO){
					$objInfraException->lancarValidacao('Motivo publicação não pode ser utilizado porque o documento foi retificado.');
				}

				if($arrPublicacoes[$i]->getStrStaMotivo() == PublicacaoRN::$TM_REPUBLICACAO){
					$objInfraException->lancarValidacao('Motivo publicação não pode ser utilizado porque o documento foi republicado.');
				}

				if($arrPublicacoes[$i]->getStrStaMotivo() == PublicacaoRN::$TM_APOSTILAMENTO){
					$objInfraException->lancarValidacao('Motivo publicação não pode ser utilizado porque o documento teve apostilamento.');
				}
			}
		}else{

			if ($numRegistros==0 || $objPublicacaoDTO->getDblIdDocumento() == $objDocumentoDTO->getDblIdProtocoloAgrupadorProtocolo()){
				$objInfraException->lancarValidacao('O motivo escolhido não pode ser utilizado porque o documento ainda não foi publicado.');
			}
		}
	}

	protected function listarFeriadosConectado(FeriadoDTO $parObjFeriadoDTO) {
	  try{

	  	global $SEI_MODULOS;

      $numIdOrgao = $parObjFeriadoDTO->getNumIdOrgao();
      $strDataInicial = $parObjFeriadoDTO->getDtaInicial();
      $strDataFinal = $parObjFeriadoDTO->getDtaFinal();

      $arrDataFeriado = array();

			$objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
			$objVeiculoPublicacaoDTO->setBolExclusaoLogica(false);
			$objVeiculoPublicacaoDTO->retNumIdVeiculoPublicacao();
			$objVeiculoPublicacaoDTO->retStrStaTipo();
			$objVeiculoPublicacaoDTO->setStrSinFonteFeriados('S');
      $objVeiculoPublicacaoDTO->setStrStaTipo(VeiculoPublicacaoRN::$TV_EXTERNO);

			$objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
			$objVeiculoPublicacaoDTO_FonteFeriados = $objVeiculoPublicacaoRN->consultar($objVeiculoPublicacaoDTO);

			if ($objVeiculoPublicacaoDTO_FonteFeriados==null){

				// webservice nulo, buscar nas tabelas
				$objFeriadoRN = new FeriadoRN();
				$objFeriadoDTO = new FeriadoDTO();
				$objFeriadoDTO->retDtaFeriado();
				$objFeriadoDTO->retStrDescricao();

				$objFeriadoDTO->adicionarCriterio(array('IdOrgao','IdOrgao'),
																					array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
																					array(null,$numIdOrgao),
																					array(InfraDTO::$OPER_LOGICO_OR));

				$objFeriadoDTO->adicionarCriterio(array('Feriado', 'Feriado'),
						array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
						array($strDataInicial, $strDataFinal),
						array(InfraDTO::$OPER_LOGICO_AND));

				$objFeriadoDTO->setOrdDtaFeriado(InfraDTO::$TIPO_ORDENACAO_ASC);
				$arrObjFeriadoDTO = $objFeriadoRN->listar($objFeriadoDTO);

				foreach($arrObjFeriadoDTO as $objFeriadoDTO) {
					$arrDataFeriado[] = array('Data' => $objFeriadoDTO->getDtaFeriado(),'Descricao' => $objFeriadoDTO->getStrDescricao());
				}
			}else if ($objVeiculoPublicacaoDTO_FonteFeriados->getStrStaTipo()==VeiculoPublicacaoRN::$TV_EXTERNO){

				$objWS = $objVeiculoPublicacaoRN->getWebService($objVeiculoPublicacaoDTO_FonteFeriados);
				$ret = $objWS->listarFeriados($numIdOrgao, $strDataInicial, $strDataFinal);

				if ($ret->Feriados != null){
					if (!is_array($ret->Feriados)){
						$ret->Feriados = array($ret->Feriados);
					}
					foreach($ret->Feriados as $feriado){
						$arrDataFeriado[] = array('Data'=> $feriado->Data, 'Descricao' => $feriado->Descricao);
					}
				}
			}

			return $arrDataFeriado;

		}catch(Exception $e){
			throw new InfraException('Erro listando feriados.',$e);
		}
	}
	
	private function validarSeDataDisponibilizacaoEhMaiorQueUmAno(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
    if (InfraData::compararDatas(InfraData::calcularData(1, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, InfraData::getStrDataAtual()),$objPublicacaoDTO->getDtaDisponibilizacao())>0){
      $objInfraException->lancarValidacao('A data de disponibilização não pode ser maior que um ano.');
    }
  }

  private function validarDtaDisponibilizacaoRN1035(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objPublicacaoDTO->getDtaDisponibilizacao())){
      $objInfraException->adicionarValidacao('Data de disponibilização não informada.');
    }else{
      if (!InfraData::validarData($objPublicacaoDTO->getDtaDisponibilizacao())){
        $objInfraException->adicionarValidacao('Data de disponibilização inválida.');
      }
    }

    $strDataAtual = InfraData::getStrDataAtual();

    if (InfraData::compararDatas($strDataAtual,$objPublicacaoDTO->getDtaDisponibilizacao())<0){
      $objInfraException->lancarValidacao('Data de disponibilização não pode estar no passado.');
    }

    if (InfraData::obterDescricaoDiaSemana($objPublicacaoDTO->getDtaDisponibilizacao())=='sábado'){
      $objInfraException->lancarValidacao('A data de disponibilização informada é um sábado.');
    }

    if (InfraData::obterDescricaoDiaSemana($objPublicacaoDTO->getDtaDisponibilizacao())=='domingo'){
      $objInfraException->lancarValidacao('A data de disponibilização informada é um domingo.');
    }
    	
    $this->validarSeDataDisponibilizacaoEhMaiorQueUmAno($objPublicacaoDTO,$objInfraException);

		$objFeriadoDTO = new FeriadoDTO();
		$objFeriadoDTO->setNumIdOrgao($objPublicacaoDTO->getNumIdOrgaoUnidadeResponsavelDocumento());
		$objFeriadoDTO->setDtaInicial($objPublicacaoDTO->getDtaDisponibilizacao());
		$objFeriadoDTO->setDtaFinal($objPublicacaoDTO->getDtaDisponibilizacao());

    //Recuperar feriados do Diário
    $arrDataFeriado = $this->listarFeriados($objFeriadoDTO);
    if(count($arrDataFeriado)>0){
      $objInfraException->lancarValidacao('A data de disponibilização '.$objPublicacaoDTO->getDtaDisponibilizacao(). ' é um feriado ('.$arrDataFeriado[0]['Descricao'].').');
    }
  }

	private function validarDadosImprensaOficialRN1106(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
		 
		$this->validarNumIdVeiculoIORN1060($objPublicacaoDTO, $objInfraException);
		$this->validarDtaPublicacaoIORN1036($objPublicacaoDTO, $objInfraException);
		$this->validarNumIdSecaoIORN1037($objPublicacaoDTO, $objInfraException);
		$this->validarStrPaginaIORN1038($objPublicacaoDTO, $objInfraException);

		if((InfraString::isBolVazia($objPublicacaoDTO->getNumIdVeiculoIO())) &&
		(!InfraString::isBolVazia($objPublicacaoDTO->getDtaPublicacaoIO()) ||
		!InfraString::isBolVazia($objPublicacaoDTO->getNumIdSecaoIO()) ||
		!InfraString::isBolVazia($objPublicacaoDTO->getStrPaginaIO()))){
			$objInfraException->lancarValidacao('Veículo da Imprensa Nacional não informado.');
		}

		if((InfraString::isBolVazia($objPublicacaoDTO->getNumIdSecaoIO())) &&
		(!InfraString::isBolVazia($objPublicacaoDTO->getNumIdVeiculoIO()) ||
		!InfraString::isBolVazia($objPublicacaoDTO->getDtaPublicacaoIO()) ||
		!InfraString::isBolVazia($objPublicacaoDTO->getStrPaginaIO()))){
		  
		  $bolVeiculoSemSecao = false;
		  if (!InfraString::isBolVazia($objPublicacaoDTO->getNumIdVeiculoIO())){
		    $objSecaoImprensaNacionalDTO = new SecaoImprensaNacionalDTO();
		    $objSecaoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($objPublicacaoDTO->getNumIdVeiculoIO());
		    
		    $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
		    if ($objSecaoImprensaNacionalRN->contar($objSecaoImprensaNacionalDTO)==0){
		      $bolVeiculoSemSecao = true;
		    }
		  }

		  if (!$bolVeiculoSemSecao){
			  $objInfraException->lancarValidacao('Seção do veículo da Imprensa Nacional não informada.');
		  }
		}
		
		if((InfraString::isBolVazia($objPublicacaoDTO->getStrPaginaIO())) &&
		(!InfraString::isBolVazia($objPublicacaoDTO->getNumIdVeiculoIO()) ||
		!InfraString::isBolVazia($objPublicacaoDTO->getDtaPublicacaoIO()) ||
		!InfraString::isBolVazia($objPublicacaoDTO->getNumIdSecaoIO()))){
			$objInfraException->lancarValidacao('Página do veículo da Imprensa Nacional não informada.');
		}
		
		if((InfraString::isBolVazia($objPublicacaoDTO->getDtaPublicacaoIO())) &&
		(!InfraString::isBolVazia($objPublicacaoDTO->getNumIdVeiculoIO()) ||
		    !InfraString::isBolVazia($objPublicacaoDTO->getNumIdSecaoIO()) ||
		    !InfraString::isBolVazia($objPublicacaoDTO->getStrPaginaIO()))){
		  $objInfraException->lancarValidacao('Data do veículo da Imprensa Nacional não informada.');
		}
		
	}

	private function validarAssinaturasRN1107(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){

		$objDocumentoDTO = new DocumentoDTO();
		$objDocumentoDTO->retStrSinAssinaturaPublicacaoSerie();
		$objDocumentoDTO->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());

		$objDocumentoRN = new DocumentoRN();
		$objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);


		if ($objDocumentoDTO->getStrSinAssinaturaPublicacaoSerie()=='S'){
			//Recuperar assinaturas pendentes e já confirmadas
			$objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->retNumIdAssinatura();
			$objAssinaturaDTO->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());
      $objAssinaturaDTO->setNumMaxRegistrosRetorno(1);
			 
			$objAssinaturaRN = new AssinaturaRN();
			if ($objAssinaturaRN->consultarRN1322($objAssinaturaDTO) == null){
				$objInfraException->lancarValidacao('Documento não foi assinado.');
			}
		}
	}

	private function validarDtaPublicacaoIORN1036(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objPublicacaoDTO->getDtaPublicacaoIO())){
			$objPublicacaoDTO->setDtaPublicacaoIO(null);
		}else{
			if (!InfraData::validarData($objPublicacaoDTO->getDtaPublicacaoIO())){
				$objInfraException->adicionarValidacao('Data inválida.');
			}
		}
	}

	private function validarNumIdSecaoIORN1037(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objPublicacaoDTO->getNumIdSecaoIO())){
			$objPublicacaoDTO->setNumIdSecaoIO(null);
		}else{
			$objPublicacaoDTO->setNumIdSecaoIO(trim($objPublicacaoDTO->getNumIdSecaoIO()));			
		}
	}

	private function validarStrPaginaIORN1038(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objPublicacaoDTO->getStrPaginaIO())){
			$objPublicacaoDTO->setStrPaginaIO(null);
		}else{
			$objPublicacaoDTO->setStrPaginaIO(trim($objPublicacaoDTO->getStrPaginaIO()));

			if (strlen($objPublicacaoDTO->getStrPaginaIO())>50){
				$objInfraException->adicionarValidacao('Página possui tamanho superior a 50 caracteres.');
			}
		}
	}

	private function validarStrResumoRN1039(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objPublicacaoDTO->getStrResumo())){
			$objPublicacaoDTO->setStrResumo(null);
		}else{
			$objPublicacaoDTO->setStrResumo(trim($objPublicacaoDTO->getStrResumo()));
		}
	}

	private function validarNumIdVeiculoIORN1060(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException){
		 
		if (InfraString::isBolVazia($objPublicacaoDTO->getNumIdVeiculoIO())){
			$objPublicacaoDTO->setNumIdVeiculoIO(null);
		}
	}

	protected function validarAgendamentoPublicacaoConectado(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException) {

		$this->validarDblIdDocumentoRN1029($objPublicacaoDTO, $objInfraException);

		$objDocumentoDTO = new DocumentoDTO();
		$objDocumentoDTO->retDblIdDocumento();
		$objDocumentoDTO->retDblIdProcedimento();
		$objDocumentoDTO->retDblIdDocumentoEdoc();
  	$objDocumentoDTO->retNumIdTipoFormulario();
		$objDocumentoDTO->retNumIdOrgaoUnidadeResponsavel();
		$objDocumentoDTO->retStrProtocoloDocumentoFormatado();
		$objDocumentoDTO->retStrStaEstadoProcedimento();
    $objDocumentoDTO->retStrSinEliminadoProcedimento();
		$objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
		$objDocumentoDTO->retStrStaDocumento();
		$objDocumentoDTO->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());
		$objDocumentoRN = new DocumentoRN();
		$objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

		$objDocumentoRN->validarDocumentoPublicadoRN1211($objDocumentoDTO);
		$objInfraException->lancarValidacoes();

		$objPublicacaoDTO->setNumIdOrgaoUnidadeResponsavelDocumento($objDocumentoDTO->getNumIdOrgaoUnidadeResponsavel());

		if (!$objDocumentoRN->verificarConteudoGerado($objDocumentoDTO)){
			$objInfraException->lancarValidacao('Documento sem conteúdo.');
		}

		$objProcedimentoRN = new ProcedimentoRN();
		$objProcedimentoRN->verificarEstadoProcedimento($objDocumentoDTO);

		$objPublicacaoBancoDTO = new PublicacaoDTO();
		$objPublicacaoBancoDTO->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());

		$arrPublicacoes = $this->listarPublicacoesDocumentoRN1101($objPublicacaoBancoDTO);
		$numRegistros = count($arrPublicacoes);
		for($i=0;$i<$numRegistros;$i++){
			if($arrPublicacoes[$i]->getStrStaEstado() == PublicacaoRN::$TE_AGENDADO){
				$objInfraException->lancarValidacao('Já existe agendamento para o documento em '.$arrPublicacoes[$i]->getDtaDisponibilizacao().'.');
			}
		}

		$dto = new PublicacaoDTO();
  		$dto->retNumIdPublicacao();
		$dto->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());
  		$dto->setNumMaxRegistrosRetorno(1);

		if ($this->consultarRN1044($dto) != null){
			$objInfraException->lancarValidacao('Protocolo já possui registro de publicação associado.');
		}

		$this->validarStaMotivoRN1033($objPublicacaoDTO, $arrPublicacoes, $objInfraException);
		$this->validarSeEhPublicavelVeiculo($objPublicacaoDTO, $objInfraException);
		$this->validarDtaDisponibilizacaoRN1035($objPublicacaoDTO, $objInfraException);
		$this->validarDadosImprensaOficialRN1106($objPublicacaoDTO, $objInfraException);
		$this->validarStrResumoRN1039($objPublicacaoDTO, $objInfraException);
		$this->validarAssinaturasRN1107($objPublicacaoDTO, $objInfraException);
		$objInfraException->lancarValidacoes();
	}

	protected function agendarRN1041Controlado(PublicacaoDTO $objPublicacaoDTO) {
		try{
			global $SEI_MODULOS;

			//Valida Permissao
			SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_agendar',__METHOD__,$objPublicacaoDTO);

			//Regras de Negocio
			$objInfraException = new InfraException();

			$this->validarAgendamentoPublicacao($objPublicacaoDTO, $objInfraException);

			$objDocumentoDTO = new DocumentoDTO();
			$objDocumentoDTO->retDblIdDocumento();
			$objDocumentoDTO->retDblIdProcedimento();
			$objDocumentoDTO->retDblIdDocumentoEdoc();
	  		$objDocumentoDTO->retNumIdTipoFormulario();
			$objDocumentoDTO->retNumIdOrgaoUnidadeResponsavel();
			$objDocumentoDTO->retStrProtocoloDocumentoFormatado();
			$objDocumentoDTO->retStrStaEstadoProcedimento();
			$objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
			$objDocumentoDTO->retStrStaDocumento();
			$objDocumentoDTO->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());
			$objDocumentoRN = new DocumentoRN();
			$objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

			$objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
			$objVeiculoPublicacaoDTO->retStrNome();
			$objVeiculoPublicacaoDTO->retStrStaTipo();
			$objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
			
			$objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
			$objVeiculoPublicacaoDTO = $objVeiculoPublicacaoRN->consultar($objVeiculoPublicacaoDTO);
			
			$objPublicacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
			$objPublicacaoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
			$objPublicacaoDTO->setDthAgendamento(InfraData::getStrDataHoraAtual());
			$objPublicacaoDTO->setNumNumero(null);
			if ($objVeiculoPublicacaoDTO->getStrStaTipo() == VeiculoPublicacaoRN::$TV_INTERNO){
				$objPublicacaoDTO->setDtaPublicacao($objPublicacaoDTO->getDtaDisponibilizacao());
			}else{
				$objPublicacaoDTO->setDtaPublicacao(null);
			}
      $objPublicacaoDTO->setStrStaTipoVeiculoPublicacao($objVeiculoPublicacaoDTO->getStrStaTipo());

			//Gerar atividade de publicação do documento
			$arrObjAtributoAndamentoDTO = array();
			
			$arrObjVeiculoPublicacaoDTO = InfraArray::indexarArrInfraDTO($this->listarValoresMotivoRN1056(),'StaMotivo');
			$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
			$objAtributoAndamentoDTO->setStrNome('MOTIVO');
			$objAtributoAndamentoDTO->setStrValor($arrObjVeiculoPublicacaoDTO[$objPublicacaoDTO->getStrStaMotivo()]->getStrDescricao());
			$objAtributoAndamentoDTO->setStrIdOrigem($objPublicacaoDTO->getStrStaMotivo());
			$arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
			
			$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
			$objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
			$objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
			$objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
			$arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

			$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
			$objAtributoAndamentoDTO->setStrNome('VEICULO');
			$objAtributoAndamentoDTO->setStrValor($objVeiculoPublicacaoDTO->getStrNome());
			$objAtributoAndamentoDTO->setStrIdOrigem($objVeiculoPublicacaoDTO->getStrStaTipo());
			$arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

			$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
			$objAtributoAndamentoDTO->setStrNome('DATA');
			if ($objVeiculoPublicacaoDTO->getStrStaTipo() == VeiculoPublicacaoRN::$TV_INTERNO){
				//se publicando no mesmo dia
				if (substr($objPublicacaoDTO->getDthAgendamento(),0,10)==$objPublicacaoDTO->getDtaDisponibilizacao()){
					//andamento com a hora do dia
					$objAtributoAndamentoDTO->setStrValor($objPublicacaoDTO->getDthAgendamento());
				}else{
					//andamento apenas com o dia (agendamento de PI)
					$objAtributoAndamentoDTO->setStrValor($objPublicacaoDTO->getDtaDisponibilizacao());
				}
			}else{
				$objAtributoAndamentoDTO->setStrValor($objPublicacaoDTO->getDtaDisponibilizacao());
			}
			$objAtributoAndamentoDTO->setStrIdOrigem(null);
			$arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

			$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
			$objAtributoAndamentoDTO->setStrNome('TIPO');
			if ($objVeiculoPublicacaoDTO->getStrStaTipo() == VeiculoPublicacaoRN::$TV_INTERNO){
				$objAtributoAndamentoDTO->setStrValor(null);
			}else{
				$objAtributoAndamentoDTO->setStrValor('(Data de Disponibilização)');
			}
			$objAtributoAndamentoDTO->setStrIdOrigem(null);
			$arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

			$objAtividadeDTO = new AtividadeDTO();
			$objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
			$objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
			$objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PUBLICACAO);
			$objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

			$objAtividadeRN = new AtividadeRN();
			$objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

			$objPublicacaoDTO->setNumIdAtividade($objAtividadeDTO->getNumIdAtividade());

			$objPublicacaoBD = new PublicacaoBD($this->getObjInfraIBanco());
			$ret = $objPublicacaoBD->cadastrar($objPublicacaoDTO);

			 
			if ($objVeiculoPublicacaoDTO->getStrStaTipo() == VeiculoPublicacaoRN::$TV_INTERNO){

				//atualiza visualização do processo nas unidades (ícone no controle de processos)
				$objAtividadeDTO = new AtividadeDTO();
				$objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
				$objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
				$objAtividadeDTO->setNumTipoVisualizacao(AtividadeRN::$TV_PUBLICACAO);

				$objAtividadeRN = new AtividadeRN();
				$objAtividadeRN->atualizarVisualizacao($objAtividadeDTO);

				//mesmo dia
				if (substr($objPublicacaoDTO->getDthAgendamento(),0,10)==$objPublicacaoDTO->getDtaDisponibilizacao()){
					$this->confirmarPublicacaoInterna($objPublicacaoDTO);
				}
				 
			}else if ($objVeiculoPublicacaoDTO->getStrStaTipo() == VeiculoPublicacaoRN::$TV_EXTERNO){
				
			  $this->agendarVeiculoExternoRN1111($objPublicacaoDTO);
			  
			}

			$objPublicacaoAPI = new PublicacaoAPI();
      $objPublicacaoAPI->setIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
      $objPublicacaoAPI->setIdDocumento($objPublicacaoDTO->getDblIdDocumento());
      $objPublicacaoAPI->setIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
      $objPublicacaoAPI->setStaTipoVeiculo($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao());
      $objPublicacaoAPI->setDataDisponibilizacao($objPublicacaoDTO->getDtaDisponibilizacao());

			foreach ($SEI_MODULOS as $seiModulo) {
				$seiModulo->executar('agendarPublicacao', $objPublicacaoAPI);
			}

			//Auditoria

			return $ret;

		}catch(Exception $e){
			throw new InfraException('Erro agendando publicação.',$e);
		}
	}
			
	protected function retornarPublicacoesRelacionadasConectado($parArrObjPublicacaoDTO) {
	  try{
	    
	    $ret = array();

	    if (InfraArray::contar($parArrObjPublicacaoDTO)){
	      
  	    $arrIdDocumento = InfraArray::converterArrInfraDTO($parArrObjPublicacaoDTO,'IdDocumento');

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retDblIdProtocoloAgrupador();
        $objProtocoloDTO->setDblIdProtocolo($arrIdDocumento,InfraDTO::$OPER_IN);
        
        $objProtocoloRN = new ProtocoloRN();
        $arrIdProtocoloAgrupador = InfraArray::converterArrInfraDTO($objProtocoloRN->listarRN0668($objProtocoloDTO),'IdProtocoloAgrupador');
        
  	    $objPublicacaoDTO = new PublicacaoDTO();
  	    $objPublicacaoDTO->retNumIdPublicacao();
  	    $objPublicacaoDTO->retStrStaEstado();
  	    $objPublicacaoDTO->retDblIdProtocoloAgrupadorProtocolo();
  	    $objPublicacaoDTO->setDblIdProtocoloAgrupadorProtocolo($arrIdProtocoloAgrupador, InfraDTO::$OPER_IN);
  	    $arr = $this->listarRN1045($objPublicacaoDTO);
  	     
  	    $arr2 = array();
  	    foreach($arr as $objPublicacaoDTO){
  	      if ($objPublicacaoDTO->getStrStaEstado()==PublicacaoRN::$TE_PUBLICADO){
  	        $arr2[] = $objPublicacaoDTO;
  	      } 
  	    }
  	    
        $arr = InfraArray::indexarArrInfraDTO($arr2,'IdProtocoloAgrupadorProtocolo',true);
        foreach($arr as $dblIdProtocoloAgrupador => $arr2){
          if (InfraArray::contar($arr2)>1){
            foreach($arr2 as $objPublicacaoDTO){
              if (!in_array($objPublicacaoDTO->getNumIdPublicacao(),$ret)){
                $ret[] = $objPublicacaoDTO->getNumIdPublicacao();
              }
            }
          }
        }
	    }
	    
	    return InfraArray::gerarArrInfraDTO('PublicacaoDTO','IdPublicacao',$ret);
	    	    
		}catch(Exception $e){
			throw new InfraException('Erro verificando publicações relacionadas.',$e);
		}
	}

	protected function listarPublicacoesRelacionadasConectado(PublicacaoDTO $parObjPublicacaoDTO) {
	  try{

	    //Valida Permissao
	    SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_listar',__METHOD__,$parObjPublicacaoDTO);

	    //Regras de Negocio
	    //$objInfraException = new InfraException();

	    //obter as publicações associadas
	    $objPublicacaoDTO = new PublicacaoDTO();
	    $objPublicacaoDTO->retNumIdPublicacao();
	    $objPublicacaoDTO->retDblIdDocumento();
	    $objPublicacaoDTO->retDtaGeracaoProtocolo();
	    $objPublicacaoDTO->retStrNomeSerieDocumento();
	    $objPublicacaoDTO->retStrNumeroDocumento();
	    $objPublicacaoDTO->retStrSiglaOrgaoUnidadeResponsavelDocumento();
	    $objPublicacaoDTO->retStrDescricaoOrgaoUnidadeResponsavelDocumento();
	    $objPublicacaoDTO->retNumIdSerieDocumento();
	    $objPublicacaoDTO->retStrSiglaUnidadeResponsavelDocumento();
	    $objPublicacaoDTO->retStrDescricaoUnidadeResponsavelDocumento();
	    $objPublicacaoDTO->retStrProtocoloFormatadoProtocolo();
	    $objPublicacaoDTO->retDtaDisponibilizacao();
	    $objPublicacaoDTO->retStrStaTipoVeiculoPublicacao();
	    $objPublicacaoDTO->retStrStaMotivo();
	    $objPublicacaoDTO->retNumIdVeiculoIO();
	    $objPublicacaoDTO->retStrSiglaVeiculoImprensaNacional();
	    $objPublicacaoDTO->retStrDescricaoVeiculoImprensaNacional();
	    $objPublicacaoDTO->retDtaPublicacaoIO();
	    $objPublicacaoDTO->retNumIdSecaoIO();
	    $objPublicacaoDTO->retStrNomeSecaoImprensaNacional();
	    $objPublicacaoDTO->retStrPaginaIO();
	    $objPublicacaoDTO->retStrResumo();
	    $objPublicacaoDTO->retDtaPublicacao();
	    $objPublicacaoDTO->retNumNumero();
	    $objPublicacaoDTO->retStrNomeVeiculoPublicacao();
	    $objPublicacaoDTO->retNumIdVeiculoPublicacao();

	    $objPublicacaoDTO->setNumIdPublicacao(InfraArray::converterArrInfraDTO($this->retornarPublicacoesRelacionadas(array($parObjPublicacaoDTO)),'IdPublicacao'), InfraDTO::$OPER_IN);

	    $objPublicacaoDTO->setOrdDtaPublicacao(InfraDTO::$TIPO_ORDENACAO_DESC);

	    $arrObjPublicacaoDTO = $this->listarRN1045($objPublicacaoDTO);

	    if (count($arrObjPublicacaoDTO)>0){
	      $arrMotivoPublicacao = InfraArray::mapearArrInfraDTO($this->listarValoresMotivoRN1056(),'StaMotivo', 'Descricao');
	      foreach($arrObjPublicacaoDTO as $objPublicacaoDTO){
	        $objPublicacaoDTO->setNumIdPublicacaoLegado(null);
	        $objPublicacaoDTO->setStrDescricaoMotivo($arrMotivoPublicacao[$objPublicacaoDTO->getStrStaMotivo()]);
	      }
	    }

	    return $arrObjPublicacaoDTO;

	    //Auditoria
	  }catch(Exception $e){
	    throw new InfraException('Erro listando publicações relacionadas.',$e);
	  }
	}

	protected function listarPublicacoesDocumentoRN1101Conectado(PublicacaoDTO $objPublicacaoRecebidoDTO) {
		try{

			//Valida Permissao
			SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_listar',__METHOD__,$objPublicacaoRecebidoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//Obter o protocolo agrupador do documento
			$objProtocoloDTO = new ProtocoloDTO();
			$objProtocoloDTO->retDblIdProtocoloAgrupador();
			$objProtocoloDTO->setDblIdProtocolo($objPublicacaoRecebidoDTO->getDblIdDocumento());
			$objProtocoloRN = new ProtocoloRN();
			$objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

			//obter todos os protocolos que usam o agrupador do documento
			$dto = new ProtocoloDTO();
			$dto->retDblIdProtocolo();
			$dto->setDblIdProtocoloAgrupador($objProtocoloDTO->getDblIdProtocoloAgrupador());
			$arrObjProtocoloDTO = $objProtocoloRN->listarRN0668($dto);

			//obter as publicações associadas
			$objPublicacaoDTO = new PublicacaoDTO();
			$objPublicacaoDTO->retNumIdPublicacao();
			$objPublicacaoDTO->retDblIdDocumento();
			$objPublicacaoDTO->retStrProtocoloFormatadoProtocolo();
			$objPublicacaoDTO->retDtaDisponibilizacao();
			$objPublicacaoDTO->retStrStaTipoVeiculoPublicacao();
			$objPublicacaoDTO->retStrStaMotivo();
			$objPublicacaoDTO->retNumIdVeiculoIO();
			$objPublicacaoDTO->retDtaPublicacaoIO();
			$objPublicacaoDTO->retNumIdSecaoIO();
			$objPublicacaoDTO->retStrPaginaIO();
			$objPublicacaoDTO->retStrResumo();
			$objPublicacaoDTO->retDtaPublicacao();
			$objPublicacaoDTO->retNumNumero();
      $objPublicacaoDTO->retNumIdVeiculoPublicacao();
			$objPublicacaoDTO->retStrNomeVeiculoPublicacao();
			$objPublicacaoDTO->retStrStaEstado();
			$objPublicacaoDTO->retStrSiglaVeiculoImprensaNacional();
			$objPublicacaoDTO->retStrDescricaoVeiculoImprensaNacional();
			$objPublicacaoDTO->retStrNomeSecaoImprensaNacional();

			$objPublicacaoDTO->setDblIdDocumento(InfraArray::converterArrInfraDTO($arrObjProtocoloDTO,'IdProtocolo'), InfraDTO::$OPER_IN);

			$objPublicacaoDTO->setOrdNumIdPublicacao(InfraDTO::$TIPO_ORDENACAO_DESC);

			$arrObjPublicacaoDTO = $this->listarRN1045($objPublicacaoDTO);

			if (count($arrObjPublicacaoDTO)>0){


				$objDocumentoDTO = new DocumentoDTO();
				$objDocumentoDTO->retDblIdDocumento();
				$objDocumentoDTO->retStrNomeSerie();
        		$objDocumentoDTO->retStrNumero();
				$objDocumentoDTO->retDblIdDocumentoEdoc();
				$objDocumentoDTO->setDblIdDocumento(InfraArray::converterArrInfraDTO($arrObjPublicacaoDTO,'IdDocumento'), InfraDTO::$OPER_IN);

				$objDocumentoRN = new DocumentoRN();
				$arrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO),'IdDocumento');

				$numRegistrosPublicacao = count($arrObjPublicacaoDTO);
												
				$arrMotivoPublicacao = InfraArray::mapearArrInfraDTO($this->listarValoresMotivoRN1056(),'StaMotivo', 'Descricao');

				for($i=0;$i<$numRegistrosPublicacao;$i++){					
					$arrObjPublicacaoDTO[$i]->setStrDescricaoMotivo($arrMotivoPublicacao[$arrObjPublicacaoDTO[$i]->getStrStaMotivo()]);
					$arrObjPublicacaoDTO[$i]->setObjDocumentoDTO($arrObjDocumentoDTO[$arrObjPublicacaoDTO[$i]->getDblIdDocumento()]);
				}
			}
				
			return $arrObjPublicacaoDTO;

		}catch(Exception $e){
			throw new InfraException('Erro listando publicações documento.',$e);
		}
	}

	private function obterEstadoRN1102($arrObjPublicacaoDTO) {
		try{

			$strDataAtual = InfraData::getStrDataAtual();

			foreach($arrObjPublicacaoDTO as $objPublicacaoDTO){

				//Publicação Interna
				if($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao() == VeiculoPublicacaoRN::$TV_INTERNO){
					 
					//data de disponibilização hoje ou anterior
					if (InfraData::compararDatas($strDataAtual, $objPublicacaoDTO->getDtaDisponibilizacao())<=0){
						$objPublicacaoDTO->setStrStaEstado(PublicacaoRN::$TE_PUBLICADO);
					}else{
						$objPublicacaoDTO->setStrStaEstado(PublicacaoRN::$TE_AGENDADO);
					}

				}else if(in_array($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao(), array(VeiculoPublicacaoRN::$TV_EXTERNO, VeiculoPublicacaoRN::$TV_MODULO))){
					if ($objPublicacaoDTO->getNumNumero() != null){
						$objPublicacaoDTO->setStrStaEstado(PublicacaoRN::$TE_PUBLICADO);
					}else{
						$objPublicacaoDTO->setStrStaEstado(PublicacaoRN::$TE_AGENDADO);
					}
				}else{
					throw new InfraException('Veículo de publicação inválido ['.$objPublicacaoDTO->getStrStaTipoVeiculoPublicacao().'].');
				}
			}

			//Auditoria
		}catch(Exception $e){
			throw new InfraException('Erro obtendo estado da Publicação.',$e);
		}
	}

	protected function alterarAgendamentoRN1042Controlado(PublicacaoDTO $objPublicacaoDTO){
		try {
			global $SEI_MODULOS;

			//Valida Permissao
			SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_alterar_agendamento',__METHOD__,$objPublicacaoDTO);

			//Regras de Negocio
			$objInfraException = new InfraException();

			$objPublicacaoBancoDTO = new PublicacaoDTO();
			$objPublicacaoBancoDTO->retDblIdDocumento();
			$objPublicacaoBancoDTO->retStrStaMotivo();
			$objPublicacaoBancoDTO->retDtaDisponibilizacao();
			$objPublicacaoBancoDTO->retNumIdVeiculoIO();
			$objPublicacaoBancoDTO->retDtaPublicacaoIO();
			$objPublicacaoBancoDTO->retNumIdSecaoIO();
			$objPublicacaoBancoDTO->retStrPaginaIO();
			$objPublicacaoBancoDTO->retStrResumo();
			$objPublicacaoBancoDTO->retDtaPublicacao();
			$objPublicacaoBancoDTO->retNumIdAtividade();
			$objPublicacaoBancoDTO->retNumIdOrgaoUnidadeResponsavelDocumento();
			$objPublicacaoBancoDTO->retNumIdVeiculoPublicacao();
			$objPublicacaoBancoDTO->retStrStaTipoVeiculoPublicacao();
			$objPublicacaoBancoDTO->retStrNomeVeiculoPublicacao();
	    $objPublicacaoBancoDTO->retStrStaEstado();
			$objPublicacaoBancoDTO->setNumIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());

			$objPublicacaoBancoDTO = $this->consultarRN1044($objPublicacaoBancoDTO);

			if ($objPublicacaoDTO->isSetDblIdDocumento() && $objPublicacaoDTO->getDblIdDocumento() != $objPublicacaoBancoDTO->getDblIdDocumento()){
				$objInfraException->lancarValidacao('Não é possível alterar o documento da publicação.');
			}else{
				$objPublicacaoDTO->setDblIdDocumento($objPublicacaoBancoDTO->getDblIdDocumento());
			}

			if ($objPublicacaoDTO->isSetStrStaMotivo() && $objPublicacaoDTO->getStrStaMotivo()!= $objPublicacaoBancoDTO->getStrStaMotivo()){
				if ($objPublicacaoBancoDTO->getStrStaEstado()==PublicacaoRN::$TE_PUBLICADO) {
          $objInfraException->adicionarValidacao('Não é possível alterar o motivo após a publicação.');
        }
			}else{
				$objPublicacaoDTO->setStrStaMotivo($objPublicacaoBancoDTO->getStrStaMotivo());
			}

			if ($objPublicacaoDTO->isSetDtaDisponibilizacao() && $objPublicacaoDTO->getDtaDisponibilizacao()!=$objPublicacaoBancoDTO->getDtaDisponibilizacao()){
        if ($objPublicacaoBancoDTO->getStrStaEstado()==PublicacaoRN::$TE_PUBLICADO) {
          $objInfraException->adicionarValidacao('Não é possível alterar a data de disponibilização após a publicação.');
        }
			}else{
        $objPublicacaoDTO->setDtaDisponibilizacao($objPublicacaoBancoDTO->getDtaDisponibilizacao());
			}

			if ($objPublicacaoDTO->isSetDtaPublicacao() && $objPublicacaoDTO->getDtaPublicacao() != $objPublicacaoBancoDTO->getDtaPublicacao()){
				$objInfraException->adicionarValidacao('Não é possível alterar a data de publicação.');
			}else{
				$objPublicacaoDTO->setDtaPublicacao($objPublicacaoBancoDTO->getDtaPublicacao());
			}

			if ($objPublicacaoDTO->isSetNumIdVeiculoPublicacao() && $objPublicacaoDTO->getNumIdVeiculoPublicacao()!=$objPublicacaoBancoDTO->getNumIdVeiculoPublicacao()){
        if ($objPublicacaoBancoDTO->getStrStaEstado()==PublicacaoRN::$TE_PUBLICADO) {
          $objInfraException->adicionarValidacao('Não é possível alterar o veículo após a publicação.');
        }
        $this->cancelarAgendamentoRN1043($objPublicacaoDTO);
        $this->agendarRN1041($objPublicacaoDTO);
        return;
      }else{
        $objPublicacaoDTO->setNumIdVeiculoPublicacao($objPublicacaoBancoDTO->getNumIdVeiculoPublicacao());
        $objPublicacaoDTO->setStrStaTipoVeiculoPublicacao($objPublicacaoBancoDTO->getStrStaTipoVeiculoPublicacao());
        $objPublicacaoDTO->setStrNomeVeiculoPublicacao($objPublicacaoBancoDTO->getStrNomeVeiculoPublicacao());
			}

			if ($objPublicacaoDTO->isSetStrResumo() && $objPublicacaoDTO->getStrResumo()!=$objPublicacaoBancoDTO->getStrResumo()){
				if ($objPublicacaoBancoDTO->getStrStaTipoVeiculoPublicacao()!=VeiculoPublicacaoRN::$TV_INTERNO && $objPublicacaoBancoDTO->getStrStaEstado()==PublicacaoRN::$TE_PUBLICADO) {
					$objInfraException->adicionarValidacao('Não é possível alterar o resumo após a publicação.');
				}
			}else{
				$objPublicacaoDTO->setStrResumo($objPublicacaoBancoDTO->getStrResumo());
			}
			$this->validarStrResumoRN1039($objPublicacaoDTO, $objInfraException);

			if (!$objPublicacaoDTO->isSetNumIdVeiculoIO()){
				$objPublicacaoDTO->setNumIdVeiculoIO($objPublicacaoBancoDTO->getNumIdVeiculoIO());
			}

			if (!$objPublicacaoDTO->isSetDtaPublicacaoIO()){
				$objPublicacaoDTO->setDtaPublicacaoIO($objPublicacaoBancoDTO->getDtaPublicacaoIO());
			}

			if (!$objPublicacaoDTO->isSetNumIdSecaoIO()){
				$objPublicacaoDTO->setNumIdSecaoIO($objPublicacaoBancoDTO->getNumIdSecaoIO());
			}

			if (!$objPublicacaoDTO->isSetStrPaginaIO()){
				$objPublicacaoDTO->setStrPaginaIO($objPublicacaoBancoDTO->getStrPaginaIO());
			}

			$this->validarDadosImprensaOficialRN1106($objPublicacaoDTO, $objInfraException);

			$objPublicacaoDTO->setNumIdOrgaoUnidadeResponsavelDocumento($objPublicacaoBancoDTO->getNumIdOrgaoUnidadeResponsavelDocumento());

			if ($objPublicacaoBancoDTO->getStrStaEstado()==PublicacaoRN::$TE_AGENDADO) {

				$dto = new PublicacaoDTO();
				$dto->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());
				$arrPublicacoes = $this->listarPublicacoesDocumentoRN1101($dto);
				foreach ($arrPublicacoes as $dto) {
					if ($dto->getNumIdPublicacao() != $objPublicacaoDTO->getNumIdPublicacao() && $dto->getStrStaEstado() == PublicacaoRN::$TE_AGENDADO) {
						$objInfraException->adicionarValidacao('Existe outro agendamento para o documento em '.$dto->getDtaDisponibilizacao().'.');
					}
				}

				$this->validarStaMotivoRN1033($objPublicacaoDTO, $arrPublicacoes, $objInfraException);
				$this->validarDtaDisponibilizacaoRN1035($objPublicacaoDTO, $objInfraException);
				$this->validarSeEhPublicavelVeiculo($objPublicacaoDTO, $objInfraException);
				$this->validarAssinaturasRN1107($objPublicacaoDTO, $objInfraException);

				$objPublicacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
				$objPublicacaoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
				$objPublicacaoDTO->setDthAgendamento(InfraData::getStrDataHoraAtual());
				$objPublicacaoDTO->setNumNumero(null);

				if ($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao() == VeiculoPublicacaoRN::$TV_INTERNO) {
					$objPublicacaoDTO->setDtaPublicacao($objPublicacaoDTO->getDtaDisponibilizacao());
				} else {
					$objPublicacaoDTO->setDtaPublicacao(null);
				}

				$objAtributoAndamentoRN = new AtributoAndamentoRN();

				if ($objPublicacaoBancoDTO->getStrStaMotivo() != $objPublicacaoDTO->getStrStaMotivo()) {
					$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
					$objAtributoAndamentoDTO->retNumIdAtributoAndamento();
					$objAtributoAndamentoDTO->setStrNome('MOTIVO');
					$objAtributoAndamentoDTO->setNumIdAtividade($objPublicacaoBancoDTO->getNumIdAtividade());
					$objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);

					$arrObjVeiculoPublicacaoDTO = InfraArray::indexarArrInfraDTO($this->listarValoresMotivoRN1056(), 'StaMotivo');
					$objAtributoAndamentoDTO->setStrValor($arrObjVeiculoPublicacaoDTO[$objPublicacaoDTO->getStrStaMotivo()]->getStrDescricao());
					$objAtributoAndamentoDTO->setStrIdOrigem($objPublicacaoDTO->getStrStaMotivo());
					$objAtributoAndamentoRN->alterarRN1364($objAtributoAndamentoDTO);
				}

				if ($objPublicacaoBancoDTO->getNumIdVeiculoPublicacao() != $objPublicacaoDTO->getNumIdVeiculoPublicacao()) {

					$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
					$objAtributoAndamentoDTO->retNumIdAtributoAndamento();
					$objAtributoAndamentoDTO->setStrNome('VEICULO');
					$objAtributoAndamentoDTO->setNumIdAtividade($objPublicacaoBancoDTO->getNumIdAtividade());
					$objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);

					$objAtributoAndamentoDTO->setStrValor($objPublicacaoDTO->getStrNomeVeiculoPublicacao());
					$objAtributoAndamentoDTO->setStrIdOrigem($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao());
					$objAtributoAndamentoRN->alterarRN1364($objAtributoAndamentoDTO);
				}

				if ($objPublicacaoBancoDTO->getDtaDisponibilizacao() != $objPublicacaoDTO->getDtaDisponibilizacao()) {

					$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
					$objAtributoAndamentoDTO->retNumIdAtributoAndamento();
					$objAtributoAndamentoDTO->setStrNome('DATA');
					$objAtributoAndamentoDTO->setNumIdAtividade($objPublicacaoBancoDTO->getNumIdAtividade());
					$objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);

					if ($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao() == VeiculoPublicacaoRN::$TV_INTERNO) {
						//se publicando no mesmo dia
						if (substr($objPublicacaoDTO->getDthAgendamento(), 0, 10) == $objPublicacaoDTO->getDtaDisponibilizacao()) {
							//andamento com a hora do dia
							$objAtributoAndamentoDTO->setStrValor($objPublicacaoDTO->getDthAgendamento());
						} else {
							//andamento apenas com o dia (agendamento de PI)
							$objAtributoAndamentoDTO->setStrValor($objPublicacaoDTO->getDtaDisponibilizacao());
						}
					} else {
						$objAtributoAndamentoDTO->setStrValor($objPublicacaoDTO->getDtaDisponibilizacao());
					}

					$objAtributoAndamentoRN->alterarRN1364($objAtributoAndamentoDTO);
				}

				$objPublicacaoDTOAlteracao = clone($objPublicacaoDTO);

			}else{

				$objPublicacaoDTOAlteracao = new PublicacaoDTO();
			  $objPublicacaoDTOAlteracao->setStrResumo($objPublicacaoDTO->getStrResumo());
				$objPublicacaoDTOAlteracao->setNumIdVeiculoIO($objPublicacaoDTO->getNumIdVeiculoIO());
				$objPublicacaoDTOAlteracao->setDtaPublicacaoIO($objPublicacaoDTO->getDtaPublicacaoIO());
				$objPublicacaoDTOAlteracao->setNumIdSecaoIO($objPublicacaoDTO->getNumIdSecaoIO());
				$objPublicacaoDTOAlteracao->setStrPaginaIO($objPublicacaoDTO->getStrPaginaIO());
				$objPublicacaoDTOAlteracao->setNumIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());

			}

			$objInfraException->lancarValidacoes();

			$objPublicacaoBD = new PublicacaoBD($this->getObjInfraIBanco());
			$objPublicacaoBD->alterar($objPublicacaoDTOAlteracao);

			if ($objPublicacaoBancoDTO->getStrStaEstado()==PublicacaoRN::$TE_AGENDADO) {

				if ($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao() == VeiculoPublicacaoRN::$TV_INTERNO &&
						substr($objPublicacaoDTO->getDthAgendamento(), 0, 10) == $objPublicacaoDTO->getDtaDisponibilizacao()) {
					$this->confirmarPublicacaoInterna($objPublicacaoDTO);
				}

				if ($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao() == VeiculoPublicacaoRN::$TV_EXTERNO) {
					$this->agendarVeiculoExternoRN1111($objPublicacaoDTO);
				}

			}

      $objPublicacaoAPI = new PublicacaoAPI();
      $objPublicacaoAPI->setIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
      $objPublicacaoAPI->setIdDocumento($objPublicacaoDTO->getDblIdDocumento());
      $objPublicacaoAPI->setIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
      $objPublicacaoAPI->setStaTipoVeiculo($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao());
      $objPublicacaoAPI->setDataDisponibilizacao($objPublicacaoDTO->getDtaDisponibilizacao());

			foreach ($SEI_MODULOS as $seiModulo) {
				$seiModulo->executar('alterarPublicacao', $objPublicacaoAPI);
			}


			if ($objPublicacaoBancoDTO->getStrStaEstado()==PublicacaoRN::$TE_PUBLICADO) {

				$objPublicacaoDTOIdx = new PublicacaoDTO();
				$objPublicacaoDTOIdx->retTodos(true);
				$objPublicacaoDTOIdx->setNumIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
				$objPublicacaoDTOIdx = $this->consultarRN1044($objPublicacaoDTOIdx);

				$objIndexacaoDTO = new IndexacaoDTO();
				$objIndexacaoDTO->setArrObjPublicacaoDTO(array($objPublicacaoDTOIdx));
				$objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PUBLICACAO);

				$objIndexacaoRN = new IndexacaoRN();
				$objIndexacaoRN->indexarPublicacao($objIndexacaoDTO);
			}

			//Auditoria

		}catch(Exception $e){
			throw new InfraException('Erro alterando Publicação.',$e);
		}
	}

	public function validarCancelamentoPublicacao(PublicacaoDTO $objPublicacaoDTO, InfraException $objInfraException, PublicacaoDTO $objPublicacaoBancoDTO = null) {

		if(!isset($objPublicacaoBancoDTO)){
			$objPublicacaoBancoDTO = new PublicacaoDTO();
			$objPublicacaoBancoDTO->retNumIdPublicacao();
			$objPublicacaoBancoDTO->retNumIdVeiculoPublicacao();
			$objPublicacaoBancoDTO->retStrNomeVeiculoPublicacao();
			$objPublicacaoBancoDTO->retDblIdDocumento();
			$objPublicacaoBancoDTO->retDblIdProcedimentoDocumento();
			$objPublicacaoBancoDTO->retStrProtocoloFormatadoProtocolo();
			$objPublicacaoBancoDTO->retStrStaMotivo();
			$objPublicacaoBancoDTO->retDtaDisponibilizacao();
			$objPublicacaoBancoDTO->retStrStaTipoVeiculoPublicacao();
			$objPublicacaoBancoDTO->retDtaPublicacao();
			$objPublicacaoBancoDTO->retNumIdAtividade();
			$objPublicacaoBancoDTO->retNumNumero();
			$objPublicacaoBancoDTO->retStrStaEstado();
			$objPublicacaoBancoDTO->setNumIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
			$objPublicacaoBancoDTO = $this->consultarRN1044($objPublicacaoBancoDTO);
		}

		if ($objPublicacaoBancoDTO->getStrStaEstado() == PublicacaoRN::$TE_PUBLICADO){
			$objInfraException->adicionarValidacao('O agendamento não pode ser cancelado porque já é considerado publicado.');
		}

		$objInfraException->lancarValidacoes();

	}

	protected function cancelarAgendamentoRN1043Controlado(PublicacaoDTO $objPublicacaoDTO){
		try {
			global $SEI_MODULOS;

			//Valida Permissao
			SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_cancelar_agendamento',__METHOD__,$objPublicacaoDTO);

			//Regras de Negocio
			$objInfraException = new InfraException();

			$objPublicacaoBancoDTO = new PublicacaoDTO();
			$objPublicacaoBancoDTO->retNumIdPublicacao();
			$objPublicacaoBancoDTO->retNumIdVeiculoPublicacao();
			$objPublicacaoBancoDTO->retStrNomeVeiculoPublicacao();
			$objPublicacaoBancoDTO->retDblIdDocumento();
			$objPublicacaoBancoDTO->retDblIdProcedimentoDocumento();
			$objPublicacaoBancoDTO->retStrProtocoloFormatadoProtocolo();
			$objPublicacaoBancoDTO->retStrStaMotivo();
			$objPublicacaoBancoDTO->retDtaDisponibilizacao();
			$objPublicacaoBancoDTO->retStrStaTipoVeiculoPublicacao();
			$objPublicacaoBancoDTO->retDtaPublicacao();
			$objPublicacaoBancoDTO->retNumIdAtividade();
			$objPublicacaoBancoDTO->retNumNumero();	
			$objPublicacaoBancoDTO->retStrStaEstado();		
			$objPublicacaoBancoDTO->setNumIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
			$objPublicacaoBancoDTO = $this->consultarRN1044($objPublicacaoBancoDTO);

			$this->validarCancelamentoPublicacao($objPublicacaoDTO, $objInfraException, $objPublicacaoBancoDTO);

			//exclui  publicacao (que aponta para atividade)
			$objPublicacaoBD = new PublicacaoBD($this->getObjInfraIBanco());
			$objPublicacaoBD->excluir($objPublicacaoBancoDTO);

			//////////////////////
			//Gerar atividade de cancelamento de publicação do documento
			
			$arrObjAtributoAndamentoDTO = array();
			
			$arrObjVeiculoPublicacaoDTO = InfraArray::indexarArrInfraDTO($this->listarValoresMotivoRN1056(),'StaMotivo');
			$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
			$objAtributoAndamentoDTO->setStrNome('MOTIVO');
			$objAtributoAndamentoDTO->setStrValor($arrObjVeiculoPublicacaoDTO[$objPublicacaoBancoDTO->getStrStaMotivo()]->getStrDescricao());
			$objAtributoAndamentoDTO->setStrIdOrigem($objPublicacaoBancoDTO->getStrStaMotivo());
			$arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
			
			$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
			$objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
			$objAtributoAndamentoDTO->setStrValor($objPublicacaoBancoDTO->getStrProtocoloFormatadoProtocolo());
			$objAtributoAndamentoDTO->setStrIdOrigem($objPublicacaoBancoDTO->getDblIdDocumento());
			$arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
				
			$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
			$objAtributoAndamentoDTO->setStrNome('VEICULO');
			$objAtributoAndamentoDTO->setStrValor($objPublicacaoBancoDTO->getStrNomeVeiculoPublicacao());
			$objAtributoAndamentoDTO->setStrIdOrigem($objPublicacaoBancoDTO->getStrStaTipoVeiculoPublicacao());
			$arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

			$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
			$objAtributoAndamentoDTO->setStrNome('DATA');
			$objAtributoAndamentoDTO->setStrValor($objPublicacaoBancoDTO->getDtaDisponibilizacao());
			$objAtributoAndamentoDTO->setStrIdOrigem(null);
			$arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

			$objAtividadeDTO = new AtividadeDTO();
			$objAtividadeDTO->setDblIdProtocolo($objPublicacaoBancoDTO->getDblIdProcedimentoDocumento());
			$objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
			$objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_CANCELAMENTO_AGENDAMENTO);
			$objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

			$objAtividadeRN = new AtividadeRN();
			$objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

			//exclui a atividade de agendamento para evitar confusao na busca pela publicacao efetiva
			//DEVE excluir APOS lançar o cancelamento de agendamento
			$objAtividadeDTO = new AtividadeDTO();
			$objAtividadeDTO->setNumIdAtividade($objPublicacaoBancoDTO->getNumIdAtividade());
			$objAtividadeRN = new AtividadeRN();
			$objAtividadeRN->excluirRN0034(array($objAtividadeDTO));

			if ($objPublicacaoBancoDTO->getStrStaTipoVeiculoPublicacao() == VeiculoPublicacaoRN::$TV_EXTERNO){
			  $this->cancelarAgendamentoVeiculoExternoRN1112($objPublicacaoBancoDTO);
			}

      $objPublicacaoAPI = new PublicacaoAPI();
      $objPublicacaoAPI->setIdPublicacao($objPublicacaoBancoDTO->getNumIdPublicacao());
      $objPublicacaoAPI->setIdDocumento($objPublicacaoBancoDTO->getDblIdDocumento());
      $objPublicacaoAPI->setIdVeiculoPublicacao($objPublicacaoBancoDTO->getNumIdVeiculoPublicacao());
      $objPublicacaoAPI->setStaTipoVeiculo($objPublicacaoBancoDTO->getStrStaTipoVeiculoPublicacao());
      $objPublicacaoAPI->setDataDisponibilizacao($objPublicacaoBancoDTO->getDtaDisponibilizacao());

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('cancelarAgendamentoPublicacao', $objPublicacaoAPI);
      }

			//Auditoria

		}catch(Exception $e){
			throw new InfraException('Erro cancelando Publicação.',$e);
		}
	}

	protected function consultarRN1044Conectado(PublicacaoDTO $objPublicacaoDTO){
		try {

			//Valida Permissao
			SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_consultar_agendamento',__METHOD__,$objPublicacaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();
			
			if ($objPublicacaoDTO->isRetStrStaEstado()){
			  $objPublicacaoDTO->retStrStaTipoVeiculoPublicacao();
			  $objPublicacaoDTO->retDtaDisponibilizacao();
			  $objPublicacaoDTO->retNumNumero();			  
			}

			$objPublicacaoBD = new PublicacaoBD($this->getObjInfraIBanco());
			$ret = $objPublicacaoBD->consultar($objPublicacaoDTO);
			
			if ($ret != null){
			  if ($objPublicacaoDTO->isRetStrStaEstado()){
			    $this->obterEstadoRN1102(array($ret));
			  }
			}

			//Auditoria

			return $ret;
		}catch(Exception $e){
			throw new InfraException('Erro consultando Publicação.',$e);
		}
	}

	protected function listarRN1045Conectado(PublicacaoDTO $objPublicacaoDTO) {
		try {

			//Valida Permissao
			SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_listar',__METHOD__,$objPublicacaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			if ($objPublicacaoDTO->isRetStrStaEstado()){
			  $objPublicacaoDTO->retStrStaTipoVeiculoPublicacao();
			  $objPublicacaoDTO->retDtaDisponibilizacao();
			  $objPublicacaoDTO->retNumNumero();
			}
				
			
			$objPublicacaoBD = new PublicacaoBD($this->getObjInfraIBanco());
			$ret = $objPublicacaoBD->listar($objPublicacaoDTO);

			if (count($ret)){
			  if ($objPublicacaoDTO->isRetStrStaEstado()){
			    $this->obterEstadoRN1102($ret);
			  }
			}
			
			//Auditoria

			return $ret;

		}catch(Exception $e){
			throw new InfraException('Erro listando Publicações.',$e);
		}
	}

	protected function alterarControlado(PublicacaoDTO $objPublicacaoDTO){
		try {

			//Valida Permissao
			//SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_alterar');

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objPublicacaoBD = new PublicacaoBD($this->getObjInfraIBanco());
			$objPublicacaoBD->alterar($objPublicacaoDTO);

			//Auditoria

		}catch(Exception $e){
			throw new InfraException('Erro alterando publicação.',$e);
		}
	}

	protected function contarRN1046Conectado(PublicacaoDTO $objPublicacaoDTO){
		try {

			//Valida Permissao
			SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_listar',__METHOD__,$objPublicacaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objPublicacaoBD = new PublicacaoBD($this->getObjInfraIBanco());
			$ret = $objPublicacaoBD->contar($objPublicacaoDTO);

			//Auditoria

			return $ret;
		}catch(Exception $e){
			throw new InfraException('Erro contando Publicações.',$e);
		}
	}

	protected function obterProximaDataRN1055Conectado(PublicacaoDTO $objPublicacaoDTO){
	  try {
	  	global $SEI_MODULOS;

	    // recebeu orgao da unidade responsável pelo documento como parametro para feriados deste órgao (obterProximaData e listarFeriados diretamente)	  	    	    	    	    	   
	    $objPublicacaoDTO->setDtaDisponibilizacao(null);

      $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
      $objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
      $objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
      $objVeiculoPublicacaoDTO->retNumIdVeiculoPublicacao();
      $objVeiculoPublicacaoDTO->retStrStaTipo();
      $objVeiculoPublicacaoDTO = $objVeiculoPublicacaoRN->consultar($objVeiculoPublicacaoDTO);

      $objPublicacaoDTO->setStrStaTipoVeiculoPublicacao($objVeiculoPublicacaoDTO->getStrStaTipo());

      $objPublicacaoAPI = new PublicacaoAPI();
      $objPublicacaoAPI->setIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
      $objPublicacaoAPI->setStaTipoVeiculo($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao());

      foreach ($SEI_MODULOS as $seiModulo) {
        if (($dtaDataPublicacao = $seiModulo->executar('obterProximaDataPublicacao', $objPublicacaoAPI)) != null){
          $objPublicacaoDTO->setDtaDisponibilizacao($dtaDataPublicacao);
          break;
        }
      }

      if ($objPublicacaoDTO->getDtaDisponibilizacao()==null) {


        if ($objVeiculoPublicacaoDTO->getStrStaTipo() == VeiculoPublicacaoRN::$TV_INTERNO || $objVeiculoPublicacaoDTO->getStrStaTipo() == VeiculoPublicacaoRN::$TV_MODULO) {
          $strDataInicial = InfraData::getStrDataAtual();
          $strDataFinal = InfraData::calcularData(1, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, $strDataInicial);

          $objFeriadoDTO = new FeriadoDTO();
          $objFeriadoDTO->setNumIdOrgao($objPublicacaoDTO->getNumIdOrgaoUnidadeResponsavelDocumento());
          $objFeriadoDTO->setDtaInicial($strDataInicial);
          $objFeriadoDTO->setDtaFinal($strDataFinal);

          $arrDataFeriado = InfraArray::simplificarArr($this->listarFeriados($objFeriadoDTO), 'Data');

          $objPublicacaoDTO->setDtaDisponibilizacao($strDataInicial);

          //Enquanto DtaDisponibilizacao for sábado, domingo ou feriado
          while (InfraData::obterDescricaoDiaSemana($objPublicacaoDTO->getDtaDisponibilizacao()) == 'sábado' ||
              InfraData::obterDescricaoDiaSemana($objPublicacaoDTO->getDtaDisponibilizacao()) == 'domingo' ||
              in_array($objPublicacaoDTO->getDtaDisponibilizacao(), $arrDataFeriado)) {

            $objPublicacaoDTO->setDtaDisponibilizacao(InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $objPublicacaoDTO->getDtaDisponibilizacao()));
          }
        } else if ($objVeiculoPublicacaoDTO->getStrStaTipo() == VeiculoPublicacaoRN::$TV_EXTERNO) {
          $objWS = $objVeiculoPublicacaoRN->getWebService($objVeiculoPublicacaoDTO);
          $objPublicacaoDTO->setDtaDisponibilizacao($objWS->obterProximaData($objPublicacaoDTO->getNumIdOrgaoUnidadeResponsavelDocumento()));
        }

      }

	    return $objPublicacaoDTO;
	     
	  }catch(Exception $e){
	    throw new InfraException('Erro obtendo próxima data.',$e);
	  }
	}
	 
	protected function obterSugestaoPublicacaoRN1053Conectado(DocumentoDTO $objDocumentoRecebidoDTO){
		try {

			//Valida Permissao
			//SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_consultar');

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//Recuperar dados do documento
			$objDocumentoDTO = new DocumentoDTO();
			$objDocumentoDTO->retNumIdSerie();
			$objDocumentoDTO->retNumIdOrgaoUnidadeResponsavel();
			$objDocumentoDTO->setDblIdDocumento($objDocumentoRecebidoDTO->getDblIdDocumento());

			$objDocumentoRN = new DocumentoRN();
			$objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
			
			$objRelSerieVeiculoPublicacaoRN = new RelSerieVeiculoPublicacaoRN();
			$objRelSerieVeiculoPublicacaoDTO = new RelSerieVeiculoPublicacaoDTO();
			$objRelSerieVeiculoPublicacaoDTO->retNumIdVeiculoPublicacao();
			$objRelSerieVeiculoPublicacaoDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
			
			if ($objRelSerieVeiculoPublicacaoRN->contar($objRelSerieVeiculoPublicacaoDTO) == 1){
			  // sugere apenas se tiver um só veículo associado à série
			  $objPublicacaoDTO = new PublicacaoDTO();
			  $objRelSerieVeiculoPublicacaoDTO = $objRelSerieVeiculoPublicacaoRN->consultar($objRelSerieVeiculoPublicacaoDTO);
			  $objPublicacaoDTO->setNumIdOrgaoUnidadeResponsavelDocumento($objDocumentoDTO->getNumIdOrgaoUnidadeResponsavel());
			  $objPublicacaoDTO->setNumIdVeiculoPublicacao($objRelSerieVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao());
			  
			  return $this->obterProximaDataRN1055($objPublicacaoDTO);			  			  			  			
			}else{
			  return null;
			}
						
			//Auditoria

		}catch(Exception $e){
			throw new InfraException('Erro consultando Publicação.',$e);
		}
	}

	protected function desativarRN1047Controlado($arrObjPublicacaoDTO){
		try {

			//Valida Permissao
			SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_desativar',__METHOD__,$arrObjPublicacaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objPublicacaoBD = new PublicacaoBD($this->getObjInfraIBanco());
			for($i=0;$i<count($arrObjPublicacaoDTO);$i++){
				$objPublicacaoBD->desativar($arrObjPublicacaoDTO[$i]);
			}

			//Auditoria

		}catch(Exception $e){
			throw new InfraException('Erro desativando Publicação.',$e);
		}
	}

	protected function reativarRN1048Controlado($arrObjPublicacaoDTO){
		try {

			//Valida Permissao
			SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_reativar',__METHOD__,$arrObjPublicacaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objPublicacaoBD = new PublicacaoBD($this->getObjInfraIBanco());
			for($i=0;$i<count($arrObjPublicacaoDTO);$i++){
				$objPublicacaoBD->reativar($arrObjPublicacaoDTO[$i]);
			}

			//Auditoria

		}catch(Exception $e){
			throw new InfraException('Erro reativando Publicação.',$e);
		}
	}

	private function prepararVeiculoExternoRN1219(PublicacaoDTO $objPublicacaoRecebidoDTO){
		try{
		  
		 
			$objDocumentoDTO = new DocumentoDTO();
			$objDocumentoDTO->retStrNumero();
			$objDocumentoDTO->retNumIdSerie();
			$objDocumentoDTO->retStrNomeSerie();
			$objDocumentoDTO->retDblIdDocumentoEdoc();
			$objDocumentoDTO->retNumIdUnidadeResponsavel();
			$objDocumentoDTO->retNumIdOrgaoUnidadeResponsavel();			
			$objDocumentoDTO->retStrSiglaUnidadeResponsavel();
			$objDocumentoDTO->retStrDescricaoUnidadeResponsavel();
			$objDocumentoDTO->retDblIdDocumento();
			$objDocumentoDTO->retStrStaDocumento();
			$objDocumentoDTO->setDblIdDocumento($objPublicacaoRecebidoDTO->getDblIdDocumento());
			$objDocumentoDTO->retDblIdProtocoloAgrupadorProtocolo();

			$objDocumentoRN = new DocumentoRN();
			$objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

			$objPublicacaoVeiculoExternoDTO = new PublicacaoVeiculoExternoDTO();
			$objPublicacaoVeiculoExternoDTO->setNumIdOrgao($objDocumentoDTO->getNumIdOrgaoUnidadeResponsavel());
			
			$dto = new OrgaoDTO();
			$rn = new OrgaoRN();
			$dto->retStrSigla();
			$dto->retStrDescricao();
			$dto->setNumIdOrgao($objDocumentoDTO->getNumIdOrgaoUnidadeResponsavel());
			$dto = $rn->consultarRN1352($dto);
						
			$objPublicacaoVeiculoExternoDTO->setStrSiglaOrgao($dto->getStrSigla());
			$objPublicacaoVeiculoExternoDTO->setStrDescricaoOrgao($dto->getStrDescricao());
			
			$objPublicacaoVeiculoExternoDTO->setNumIdUnidade($objDocumentoDTO->getNumIdUnidadeResponsavel());
			$objPublicacaoVeiculoExternoDTO->setStrSiglaUnidade($objDocumentoDTO->getStrSiglaUnidadeResponsavel());
			$objPublicacaoVeiculoExternoDTO->setStrDescricaoUnidade($objDocumentoDTO->getStrDescricaoUnidadeResponsavel());
			
			$objPublicacaoVeiculoExternoDTO->setNumIdTipoDocumento($objDocumentoDTO->getNumIdSerie());
			
			$dto = new SerieDTO();
			$rn = new SerieRN();
			$dto->setBolExclusaoLogica(false);
			$dto->retStrNome();
			$dto->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
			$dto = $rn->consultarRN0644($dto);
			
			$objPublicacaoVeiculoExternoDTO->setStrNomeTipoDocumento($dto->getStrNome());
			
			$objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
			$objVeiculoPublicacaoDTO->retStrNome();
			$objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($objPublicacaoRecebidoDTO->getNumIdVeiculoPublicacao());
				
			$objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
			$objVeiculoPublicacaoDTO = $objVeiculoPublicacaoRN->consultar($objVeiculoPublicacaoDTO);

			$objPublicacaoVeiculoExternoDTO->setNumIdVeiculoPublicacao($objPublicacaoRecebidoDTO->getNumIdVeiculoPublicacao());
			$objPublicacaoVeiculoExternoDTO->setStrNomeVeiculoPublicacao($objVeiculoPublicacaoDTO->getStrNome());
				
							
			$objPublicacaoVeiculoExternoDTO->setStrNumeroDocumento($objDocumentoDTO->getStrNumero());

			if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC){
			  $objEdocRN = new EDocRN();
			  $objPublicacaoVeiculoExternoDTO->setStrConteudoDocumento($objEdocRN->consultarHTMLDocumentoRN1204($objDocumentoDTO));
			}else if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){
			  $objEditorDTO = new EditorDTO();
			  $objEditorDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
			  $objEditorDTO->setNumIdBaseConhecimento(null);
			  $objEditorDTO->setStrSinCabecalho('N');
			  $objEditorDTO->setStrSinRodape('N');
        $objEditorDTO->setStrSinCarimboPublicacao('N');
			  $objEditorDTO->setStrSinIdentificacaoVersao('N');
			  $objEditorRN = new EditorRN();
			  $objPublicacaoVeiculoExternoDTO->setStrConteudoDocumento($objEditorRN->consultarHtmlVersao($objEditorDTO));
			}else{
				throw new InfraException('Sinalizador interno do documento inválido para publicação.');
			}
									
			$objPublicacaoVeiculoExternoDTO->setNumIdDocumento($objPublicacaoRecebidoDTO->getDblIdDocumento());
			
			if ($objDocumentoDTO->getDblIdProtocoloAgrupadorProtocolo() != null) {
			  $objPublicacaoVeiculoExternoDTO->setNumIdDocumentoPai($objDocumentoDTO->getDblIdProtocoloAgrupadorProtocolo());
			}
			else {
			  // se não tem publicação de documento relacionada, grava o próprio IdProtocolo
			  $objPublicacaoVeiculoExternoDTO->setNumIdDocumentoPai($objDocumentoDTO->getDblIdDocumento());
			}
			

			$objPublicacaoVeiculoExternoDTO->setDtaDataDisponibilizacao($objPublicacaoRecebidoDTO->getDtaDisponibilizacao());
				
			if (!InfraString::isBolVazia($objPublicacaoRecebidoDTO->getNumIdVeiculoIO())){
			  
			  $objVeiculoImprensaNacionalDTO = new VeiculoImprensaNacionalDTO();
			  $objVeiculoImprensaNacionalDTO->retStrSigla();
			  $objVeiculoImprensaNacionalDTO->retStrDescricao();
			  $objVeiculoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($objPublicacaoRecebidoDTO->getNumIdVeiculoIO());
			  
			  $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
			  $objVeiculoImprensaNacionalDTO = $objVeiculoImprensaNacionalRN->consultar($objVeiculoImprensaNacionalDTO); 

			  $objSecaoImprensaNacionalDTO = new SecaoImprensaNacionalDTO();
			  $objSecaoImprensaNacionalDTO->retStrNome();
			  $objSecaoImprensaNacionalDTO->setNumIdSecaoImprensaNacional($objPublicacaoRecebidoDTO->getNumIdSecaoIO());
			  	
			  $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
			  $objSecaoImprensaNacionalDTO = $objSecaoImprensaNacionalRN->consultar($objSecaoImprensaNacionalDTO);
			  	
			  
			  $objPublicacaoVeiculoExternoDTO->setNumIdVeiculoImprensaOficial($objPublicacaoRecebidoDTO->getNumIdVeiculoIO());
			  $objPublicacaoVeiculoExternoDTO->setStrSiglaVeiculoImprensaOficial($objVeiculoImprensaNacionalDTO->getStrSigla());
			  $objPublicacaoVeiculoExternoDTO->setStrDescricaoVeiculoImprensaOficial($objVeiculoImprensaNacionalDTO->getStrDescricao());			  			  			
				$objPublicacaoVeiculoExternoDTO->setDtaDataPublicacaoVeiculoImprensaOficial($objPublicacaoRecebidoDTO->getDtaPublicacaoIO());
				$objPublicacaoVeiculoExternoDTO->setNumIdSecaoPublicacaoVeiculoImprensaOficial($objPublicacaoRecebidoDTO->getNumIdSecaoIO());
				$objPublicacaoVeiculoExternoDTO->setStrNomeSecaoPublicacaoVeiculoImprensaOficial($objSecaoImprensaNacionalDTO->getStrNome());
				$objPublicacaoVeiculoExternoDTO->setStrPaginaPublicacaoVeiculoImprensaOficial($objPublicacaoRecebidoDTO->getStrPaginaIO());
				
			}else{
				$objPublicacaoVeiculoExternoDTO->setNumIdVeiculoImprensaOficial(null);
			  $objPublicacaoVeiculoExternoDTO->setStrSiglaVeiculoImprensaOficial(null);
			  $objPublicacaoVeiculoExternoDTO->setStrDescricaoVeiculoImprensaOficial(null);			  			  			
				$objPublicacaoVeiculoExternoDTO->setDtaDataPublicacaoVeiculoImprensaOficial(null);
				$objPublicacaoVeiculoExternoDTO->setNumIdSecaoPublicacaoVeiculoImprensaOficial(null);
				$objPublicacaoVeiculoExternoDTO->setStrNomeSecaoPublicacaoVeiculoImprensaOficial(null);
				$objPublicacaoVeiculoExternoDTO->setStrPaginaPublicacaoVeiculoImprensaOficial(null);
			}
		
			return $objPublicacaoVeiculoExternoDTO;

		}catch(Exception $e){
			throw new InfraException('Erro preparando dados para veículo externo.',$e);
		}

	}

	protected function agendarVeiculoExternoRN1111Controlado(PublicacaoDTO $objPublicacaoDTO){
		try {

			//Valida Permissao
			//SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_consultar');

			//Regras de Negocio
			//$objInfraException = new InfraException();

			$objPublicacaoVeiculoExternoDTO = $this->prepararVeiculoExternoRN1219($objPublicacaoDTO);
			
			$objPublicacaoVeiculoExternoDTO->setStrConteudoDocumento(str_replace(array(chr(2), chr(3), chr(28),chr(29),chr(30),chr(31)),'',$objPublicacaoVeiculoExternoDTO->getStrConteudoDocumento()));

			$objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
			$objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
			
			$objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
			
			$objWS = $objVeiculoPublicacaoRN->getWebService($objVeiculoPublicacaoDTO);
																		
			$ret = $objWS->agendarPublicacao($objPublicacaoVeiculoExternoDTO->getNumIdOrgao(),						
                                			$objPublicacaoVeiculoExternoDTO->getStrSiglaOrgao(),
                                			$objPublicacaoVeiculoExternoDTO->getStrDescricaoOrgao(),				
                                			$objPublicacaoVeiculoExternoDTO->getNumIdUnidade(),
                                			$objPublicacaoVeiculoExternoDTO->getStrSiglaUnidade(),
                                			$objPublicacaoVeiculoExternoDTO->getStrDescricaoUnidade(),				
                                			$objPublicacaoVeiculoExternoDTO->getNumIdTipoDocumento(),											
                                			$objPublicacaoVeiculoExternoDTO->getStrNomeTipoDocumento(),				
                                			$objPublicacaoVeiculoExternoDTO->getNumIdVeiculoPublicacao(),
                                			$objPublicacaoVeiculoExternoDTO->getStrNomeVeiculoPublicacao(),				
                                			$objPublicacaoVeiculoExternoDTO->getStrNumeroDocumento(),					
                                			$objPublicacaoVeiculoExternoDTO->getStrConteudoDocumento(),							
                                			$objPublicacaoVeiculoExternoDTO->getNumIdDocumento(),						
                                			$objPublicacaoVeiculoExternoDTO->getNumIdDocumentoPai(),										
                                			$objPublicacaoVeiculoExternoDTO->getDtaDataDisponibilizacao(),									  
                                		  $objPublicacaoVeiculoExternoDTO->getNumIdVeiculoImprensaOficial(),
                                			$objPublicacaoVeiculoExternoDTO->getStrSiglaVeiculoImprensaOficial(),
                                			$objPublicacaoVeiculoExternoDTO->getStrDescricaoVeiculoImprensaOficial(),
                                			$objPublicacaoVeiculoExternoDTO->getDtaDataPublicacaoVeiculoImprensaOficial(),
                                			$objPublicacaoVeiculoExternoDTO->getNumIdSecaoPublicacaoVeiculoImprensaOficial(),
			                                $objPublicacaoVeiculoExternoDTO->getStrNomeSecaoPublicacaoVeiculoImprensaOficial(),
                                			$objPublicacaoVeiculoExternoDTO->getStrPaginaPublicacaoVeiculoImprensaOficial());

			return $ret;
			 
		}catch(Exception $e){
			throw new InfraException('Erro agendando veículo externo.',$e);
		}
	}

	protected function cancelarAgendamentoVeiculoExternoRN1112Controlado($objPublicacaoDTO){
		try {

			//Regras de Negocio
			//$objInfraException = new InfraException();


			//Ativar serviço do Diário Eletrônico para cancelamento de agendamento						

			$objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
			$objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
			
			$objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
			
			$objWS = $objVeiculoPublicacaoRN->getWebService($objVeiculoPublicacaoDTO);
			$objWS->cancelarAgendamentoPublicacao($objPublicacaoDTO->getDblIdDocumento());
				
			//Auditoria

		}catch(Exception $e){
			throw new InfraException('Erro cancelando veículo externo.',$e);
		}
	}
	
	public function confirmarPublicacaoInterna(PublicacaoDTO $objPublicacaoRecebidoDTO) {
	  try{

      FeedSEIPublicacoes::getInstance()->setBolAcumularFeeds(true);
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

      $arrObjPublicacaoDTO = $this->confirmarPublicacaoInternaInterno($objPublicacaoRecebidoDTO);
	    
	    if (InfraArray::contar($arrObjPublicacaoDTO)){
	    
  	    $objIndexacaoDTO = new IndexacaoDTO();
  	    $objIndexacaoDTO->setArrObjPublicacaoDTO($arrObjPublicacaoDTO);
  	    $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PUBLICACAO);
  	    
  	    $objIndexacaoRN = new IndexacaoRN();
  	    $objIndexacaoRN->indexarPublicacao($objIndexacaoDTO);
	    }

      FeedSEIPublicacoes::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);

      FeedSEIPublicacoes::getInstance()->indexarFeeds();
      FeedSEIProtocolos::getInstance()->indexarFeeds();

    }catch(Exception $e){
	    throw new InfraException('Erro confirmando publicação interna.',$e);
	  }
	}

	protected function confirmarPublicacaoInternaInternoControlado(PublicacaoDTO $objPublicacaoRecebidoDTO) {
		try{

			global $SEI_MODULOS;

  		if ($objPublicacaoRecebidoDTO->isSetDblIdDocumento()){
  
  			$objPublicacaoDTO = new PublicacaoDTO();
  			$objPublicacaoDTO->retTodos(true);
  			$objPublicacaoDTO->setDblIdDocumento($objPublicacaoRecebidoDTO->getDblIdDocumento());
  			$arrObjPublicacaoDTO = array($this->consultarRN1044($objPublicacaoDTO));
  
  		}else{
  
  			$objPublicacaoDTO = new PublicacaoDTO();
  			$objPublicacaoDTO->retTodos(true);
  			$objPublicacaoDTO->setStrStaTipoVeiculoPublicacao(VeiculoPublicacaoRN::$TV_INTERNO);
  			$objPublicacaoDTO->setDtaPublicacao($objPublicacaoRecebidoDTO->getDtaPublicacao());
  			$arrObjPublicacaoDTO = $this->listarRN1045($objPublicacaoDTO);
  
  		}
  
  		$objProtocoloRN = new ProtocoloRN();
  		$objDocumentoRN = new DocumentoRN();

  		$arrObjPublicacaoAPI = array();
  		foreach($arrObjPublicacaoDTO as $objPublicacaoDTO){
  			if ($objPublicacaoDTO->getStrStaNivelAcessoLocalProtocolo()!=ProtocoloRN::$NA_PUBLICO){
  					
  				$objProtocoloDTO = new ProtocoloDTO();
  				$objProtocoloDTO->setStrStaNivelAcessoLocal(ProtocoloRN::$NA_PUBLICO);
  				$objProtocoloDTO->setDblIdProtocolo($objPublicacaoDTO->getDblIdDocumento());
  				$objProtocoloDTO->setStrSinLancarAndamento('N');
  				 
  				$objProtocoloRN->alterarRN0203($objProtocoloDTO);
  			}
  			
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());
        $objDocumentoRN->bloquearPublicado($objDocumentoDTO);

				$objPublicacaoAPI = new PublicacaoAPI();
				$objPublicacaoAPI->setIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
				$objPublicacaoAPI->setIdDocumento($objPublicacaoDTO->getDblIdDocumento());
				$objPublicacaoAPI->setIdSerieDocumento($objPublicacaoDTO->getNumIdSerieDocumento());
				$objPublicacaoAPI->setIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
				$objPublicacaoAPI->setStaTipoVeiculo($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao());
				$objPublicacaoAPI->setDataDisponibilizacao($objPublicacaoDTO->getDtaDisponibilizacao());
				$objPublicacaoAPI->setDataPublicacao($objPublicacaoDTO->getDtaPublicacao());
				$arrObjPublicacaoAPI[] = $objPublicacaoAPI;
  		}

			foreach ($SEI_MODULOS as $seiModulo) {
				$seiModulo->executar('confirmarPublicacao', $arrObjPublicacaoAPI);
			}

  		return $arrObjPublicacaoDTO;  		  	
   		
		}catch(Exception $e){
		  throw new InfraException('Erro confirmando publicação interna.',$e);
		}
	}

	public function confirmarDisponibilizacaoRN1115(VeiculoPublicacaoDTO $objVeiculoPublicacaoDTO) {
	  try{

      FeedSEIPublicacoes::getInstance()->setBolAcumularFeeds(true);
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

	    $arrObjPublicacaoDTO = $this->confirmarDisponibilizacaoRN1115Interno($objVeiculoPublicacaoDTO);
	    
	    if (count($arrObjPublicacaoDTO)){
	      $objIndexacaoDTO = new IndexacaoDTO();
	      $objIndexacaoDTO->setArrObjPublicacaoDTO($arrObjPublicacaoDTO);
	      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PUBLICACAO);
	      
	      $objIndexacaoRN = new IndexacaoRN();
	      $objIndexacaoRN->indexarPublicacao($objIndexacaoDTO);
	    }

      FeedSEIPublicacoes::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);

      FeedSEIPublicacoes::getInstance()->indexarFeeds();
      FeedSEIProtocolos::getInstance()->indexarFeeds();

 		}catch(Exception $e){
		  throw new InfraException('Erro confirmando disponibilização.',$e);
		}
	}
	
	protected function confirmarDisponibilizacaoRN1115InternoControlado(VeiculoPublicacaoDTO $parObjVeiculoPublicacaoDTO) {
		try{

			global $SEI_MODULOS;

			//Valida Permissao
			//SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_agendar');

			//Regras de Negocio
			$objInfraException = new InfraException();

			$objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
			$objVeiculoPublicacaoDTO->retNumIdVeiculoPublicacao();
			$objVeiculoPublicacaoDTO->retStrStaTipo();
			$objVeiculoPublicacaoDTO->retStrSinExibirPesquisaInterna();
			$objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($parObjVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao());
			
			$objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
			$objVeiculoPublicacaoDTO = $objVeiculoPublicacaoRN->consultar($objVeiculoPublicacaoDTO);
			
			if ($objVeiculoPublicacaoDTO==null){
			  throw new InfraException('Veículo de publicação não encontrado.');
			}
			
			$objProtocoloRN = new ProtocoloRN();
			$objDocumentoRN = new DocumentoRN();
			
			$arrObjPublicacaoDTOIndexacao = array();

			$arrObjPublicacaoAPI = array();

			foreach($parObjVeiculoPublicacaoDTO->getArrObjPublicacaoDTO() as $objPublicacaoRecebidoDTO){

				//Obter dados da publicação através da Publicacao
				$objPublicacaoDTO = new PublicacaoDTO();
				$objPublicacaoDTO->retNumIdPublicacao();
				$objPublicacaoDTO->retDblIdProcedimentoDocumento();
				$objPublicacaoDTO->retNumIdAtividade();
				$objPublicacaoDTO->retStrNomeVeiculoPublicacao();
				$objPublicacaoDTO->retStrStaNivelAcessoLocalProtocolo();
				$objPublicacaoDTO->retNumIdUnidadeResponsavelDocumento();
				$objPublicacaoDTO->retNumIdOrgaoUnidadeResponsavelDocumento();
				$objPublicacaoDTO->retNumIdSerieDocumento();
				$objPublicacaoDTO->retDblIdDocumento();
				$objPublicacaoDTO->setDblIdDocumento($objPublicacaoRecebidoDTO->getDblIdDocumento());
				$objPublicacaoDTO->setNumIdVeiculoPublicacao($parObjVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao());

				$objPublicacaoDTO = $this->consultarRN1044($objPublicacaoDTO);

				if ($objPublicacaoDTO==null){
					$objInfraException->adicionarValidacao('Registro de publicação deste veículo não encontrado para o protocolo '.$objPublicacaoRecebidoDTO->getDblIdDocumento().'.');
				}else{

					$dto = new PublicacaoDTO();
					$dto->setDtaDisponibilizacao($objPublicacaoRecebidoDTO->getDtaDisponibilizacao());
					$dto->setDtaPublicacao($objPublicacaoRecebidoDTO->getDtaPublicacao());
					$dto->setNumNumero($objPublicacaoRecebidoDTO->getNumNumero());
					$dto->setNumIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
					$this->alterar($dto);

					//atualiza atividades onde o processo esta aberto para flag de publicação
					$objAtividadeDTO = new AtividadeDTO();
					$objAtividadeDTO->setDblIdProtocolo($objPublicacaoDTO->getDblIdProcedimentoDocumento());
					$objAtividadeDTO->setNumTipoVisualizacao(AtividadeRN::$TV_PUBLICACAO);

					$objAtividadeRN = new AtividadeRN();
					$objAtividadeRN->atualizarVisualizacao($objAtividadeDTO);

					$objAtributoAndamentoRN = new AtributoAndamentoRN();

					$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
					$objAtributoAndamentoDTO->retNumIdAtributoAndamento();
					$objAtributoAndamentoDTO->setNumIdAtividade($objPublicacaoDTO->getNumIdAtividade());
					$objAtributoAndamentoDTO->setStrNome('VEICULO');
					 
					$objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);
					$objAtributoAndamentoDTO->setStrValor($objPublicacaoDTO->getStrNomeVeiculoPublicacao().' Nº '.$objPublicacaoRecebidoDTO->getNumNumero());
					$objAtributoAndamentoRN->alterarRN1364($objAtributoAndamentoDTO);
					 
					$objAtributoAndamentoDTO = new AtributoAndamentoDTO();
					$objAtributoAndamentoDTO->retNumIdAtributoAndamento();
					$objAtributoAndamentoDTO->setNumIdAtividade($objPublicacaoDTO->getNumIdAtividade());
					$objAtributoAndamentoDTO->setStrNome('DATA');
					 
					$objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);
					$objAtributoAndamentoDTO->setStrValor($objPublicacaoRecebidoDTO->getDtaDisponibilizacao());
					$objAtributoAndamentoRN->alterarRN1364($objAtributoAndamentoDTO);
					 
					/*
					 $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
					 $objAtributoAndamentoDTO->retNumIdAtributoAndamento();
					 $objAtributoAndamentoDTO->setNumIdAtividade($objPublicacaoDTO->getNumIdAtividade());
					 $objAtributoAndamentoDTO->setStrNome('TIPO');
					  
					 $objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);
					 $objAtributoAndamentoDTO->setStrValor('(Data de Publicação)');
					 $objAtributoAndamentoRN->alterarRN1364($objAtributoAndamentoDTO);
					 */
					 
					if ($objPublicacaoDTO->getStrStaNivelAcessoLocalProtocolo()!=ProtocoloRN::$NA_PUBLICO){
						 
						$objProtocoloDTO = new ProtocoloDTO();
						$objProtocoloDTO->setStrStaNivelAcessoLocal(ProtocoloRN::$NA_PUBLICO);
						$objProtocoloDTO->setDblIdProtocolo($objPublicacaoDTO->getDblIdDocumento());
						$objProtocoloDTO->setStrSinLancarAndamento('N');
						
						$objProtocoloRN->alterarRN0203($objProtocoloDTO);
					}
					
          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->setDblIdDocumento($objPublicacaoDTO->getDblIdDocumento());
          $objDocumentoRN->bloquearPublicado($objDocumentoDTO);

          if ($objVeiculoPublicacaoDTO->getStrSinExibirPesquisaInterna()=='S'){

            $objPublicacaoDTOIndexacao = new PublicacaoDTO();
            $objPublicacaoDTOIndexacao->retTodos(true);
            $objPublicacaoDTOIndexacao->setNumIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
            $arrObjPublicacaoDTOIndexacao[] = $this->consultarRN1044($objPublicacaoDTOIndexacao);

          }

					$objPublicacaoAPI = new PublicacaoAPI();
					$objPublicacaoAPI->setIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
					$objPublicacaoAPI->setIdDocumento($objPublicacaoDTO->getDblIdDocumento());
					$objPublicacaoAPI->setIdSerieDocumento($objPublicacaoDTO->getNumIdSerieDocumento());
					$objPublicacaoAPI->setIdVeiculoPublicacao($objVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao());
					$objPublicacaoAPI->setStaTipoVeiculo($objVeiculoPublicacaoDTO->getStrStaTipo());
					$objPublicacaoAPI->setDataDisponibilizacao($objPublicacaoRecebidoDTO->getDtaDisponibilizacao());
					$objPublicacaoAPI->setDataPublicacao($objPublicacaoRecebidoDTO->getDtaPublicacao());
					$arrObjPublicacaoAPI[] = $objPublicacaoAPI;
				}
			}

			$objInfraException->lancarValidacoes();

			foreach ($SEI_MODULOS as $seiModulo) {
				$seiModulo->executar('confirmarPublicacao', $arrObjPublicacaoAPI);
			}

			return $arrObjPublicacaoDTOIndexacao;

		}catch(Exception $e){
			throw new InfraException('Erro confirmando disponibilização.',$e);
		}
	}
}
?>
