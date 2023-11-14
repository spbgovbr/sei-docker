<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/09/2010 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class OuvidoriaRN extends InfraRN {

  public static $FORM_DTH_ENVIO = 'DTH_ENVIO';
	public static $FORM_NOME = 'NOME';
  public static $FORM_NOME_SOCIAL = 'NOME_SOCIAL';
	public static $FORM_EMAIL = 'EMAIL';
	public static $FORM_CPF = 'CPF';
	public static $FORM_RG = 'RG';
	public static $FORM_ORGAO_EXPEDIDOR = 'ORGAO_EXPEDIDOR';
	public static $FORM_TELEFONE = 'TELEFONE';
	public static $FORM_ESTADO = 'ESTADO';
	public static $FORM_CIDADE = 'CIDADE';
	public static $FORM_PROCESSOS = 'PROCESSOS';
	public static $FORM_RETORNO = 'RETORNO';
	public static $FORM_MENSAGEM = 'MENSAGEM';
	
  public function __construct(){
    parent::__construct();
  }
 
  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function registrarOuvidoriaRN1148(ProcedimentoOuvidoriaDTO $objProcedimentoOuvidoriaDTO){
    try{

      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

      MailSEI::getInstance()->limpar();

      $ret = $this->registrarOuvidoriaRN1148Interno($objProcedimentoOuvidoriaDTO);

      MailSEI::getInstance()->enviar();

      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro registrando contato com ouvidoria.',$e);
    }
  }

  protected function registrarOuvidoriaRN1148InternoControlado(ProcedimentoOuvidoriaDTO $objProcedimentoOuvidoriaDTO){
    try{

    	$objInfraException = new InfraException();

    	if (!$objProcedimentoOuvidoriaDTO->isSetDblIdProcedimentoOrigem()){
        $objProcedimentoOuvidoriaDTO->setDblIdProcedimentoOrigem(null);
      }

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->retNumIdUnidade();
      $objUnidadeDTO->retNumIdOrgao();
      $objUnidadeDTO->retStrSigla();
      $objUnidadeDTO->retStrSiglaOrgao();
      $objUnidadeDTO->setNumIdOrgao($objProcedimentoOuvidoriaDTO->getNumIdOrgao());
      $objUnidadeDTO->setStrSinOuvidoria('S');

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

      if ($objUnidadeDTO==null){
        throw new InfraException('Unidade para geração do processo de ouvidoria não encontrada.');
      }

      $numIdUnidadeAtual = null;
      $numIdUsuarioAtual = null;
      if (SessaoSEI::getInstance()->isBolHabilitada()){
        $numIdUnidadeAtual = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
        $numIdUsuarioAtual = SessaoSEI::getInstance()->getNumIdUsuario();
      }

      //SessaoSEI::getInstance()->setBolHabilitada(false);
      SessaoSEI::getInstance()->simularLogin(SessaoSEI::$USUARIO_INTERNET, null, null, $objUnidadeDTO->getNumIdUnidade());


      //Carrega parâmetros para registro da Unidade
      $objInfraParametro = new InfraParametro($this->getObjInfraIBanco());
			
      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->retStrSitioInternetContato();
      $objOrgaoDTO->setNumIdOrgao($objProcedimentoOuvidoriaDTO->getNumIdOrgao());
      
      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);
      
      if ($objOrgaoDTO==null){
      	$objInfraException->lancarValidacao('Órgão não encontrado.');
      }

      $numIdUf = null;
      $numIdPais = null;
      if (!InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getStrEstado())){

        $objUfDTO = new UfDTO();
				$objUfDTO->retNumIdUf();
				$objUfDTO->retStrSigla();
				$objUfDTO->retNumIdPais();
        $objUfDTO->setStrSigla($objProcedimentoOuvidoriaDTO->getStrEstado());

	      $objUfRN = new UfRN();
        $arrObjUfDTO = $objUfRN->listarRN0401($objUfDTO);

				$strSiglaUf = trim(InfraString::transformarCaixaAlta($objProcedimentoOuvidoriaDTO->getStrEstado()));
				foreach($arrObjUfDTO as $objUfDTO){
					if ($strSiglaUf == InfraString::transformarCaixaAlta($objUfDTO->getStrSigla())){
						$numIdUf = $objUfDTO->getNumIdUf();
						$numIdPais = $objUfDTO->getNumIdPais();
						break;
					}
				}

				//if ($numIdUf==null){
				//	$objInfraException->lancarValidacao('Estado ['.$objProcedimentoOuvidoriaDTO->getStrEstado().'] inválido.');
				//}
      }

      $numIdCidade = null;
      if ($numIdUf!=null && !InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getStrCidade())){

        $objCidadeDTO = new CidadeDTO();
        $objCidadeDTO->retNumIdCidade();
				$objCidadeDTO->retStrNome();
        $objCidadeDTO->setNumIdUf($numIdUf);

        $objCidadeRN = new CidadeRN();
        $arrObjCidadeDTO = $objCidadeRN->listarRN0410($objCidadeDTO);

				$strNomeCidade = trim(InfraString::transformarCaixaAlta($objProcedimentoOuvidoriaDTO->getStrCidade()));
				foreach($arrObjCidadeDTO as $objCidadeDTO){
					if ($strNomeCidade == InfraString::transformarCaixaAlta($objCidadeDTO->getStrNome())){
						$numIdCidade = $objCidadeDTO->getNumIdCidade();
						break;
					}
				}

        //if ($numIdCidade==null){
        //  $objInfraException->lancarValidacao('Cidade ['.$objProcedimentoOuvidoriaDTO->getStrCidade().'] inválida.');
        //}
      }

			if (InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getNumIdTipoProcedimento())){
      	$objInfraException->lancarValidacao('Tipo não informado.');
			}


      $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
      $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
      $objTipoProcedimentoDTO->retStrStaNivelAcessoSugestao();
      $objTipoProcedimentoDTO->retStrStaGrauSigiloSugestao();
      $objTipoProcedimentoDTO->retNumIdHipoteseLegalSugestao();
      $objTipoProcedimentoDTO->retStrNome();
      $objTipoProcedimentoDTO->retStrSinOuvidoria();
      $objTipoProcedimentoDTO->setNumIdTipoProcedimento($objProcedimentoOuvidoriaDTO->getNumIdTipoProcedimento());

      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);


      if ($objTipoProcedimentoDTO==null){
      	$objInfraException->lancarValidacao('Tipo do processo ['.$objProcedimentoOuvidoriaDTO->getNumIdTipoProcedimento().'] não encontrado.');
      }

      if ($objTipoProcedimentoDTO->getStrSinOuvidoria()=='N'){
      	$objInfraException->lancarValidacao('Tipo do processo ['.$objProcedimentoOuvidoriaDTO->getNumIdTipoProcedimento().'] não é de Ouvidoria.');
      }


      if (!$objInfraParametro->isSetValor('ID_TIPO_CONTATO_OUVIDORIA')){
        
        $objTipoContatoDTO = new TipoContatoDTO();
        $objTipoContatoDTO->setNumIdTipoContato(null);
        $objTipoContatoDTO->setStrNome('Ouvidoria');
        $objTipoContatoDTO->setStrDescricao('Usuários cadastrados através do formulário da ouvidoria.');
        $objTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_RESUMIDA);
				$objTipoContatoDTO->setStrSinSistema('N');
        $objTipoContatoDTO->setStrSinAtivo('S');

        $objTipoContatoRN = new TipoContatoRN();
        $objTipoContatoDTO = $objTipoContatoRN->cadastrarRN0334($objTipoContatoDTO);

        $objInfraParametro->setValor('ID_TIPO_CONTATO_OUVIDORIA',$objTipoContatoDTO->getNumIdTipoContato());
      }

      $numIdTipoContato = $objInfraParametro->getValor('ID_TIPO_CONTATO_OUVIDORIA');

      $objContatoRN = new ContatoRN();
      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retNumIdTipoContato();
			$objContatoDTO->setNumIdTipoContato($numIdTipoContato);

      $arr = array();

			if (!InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getDblCpf())){

			  if (!InfraUtil::validarCpf($objProcedimentoOuvidoriaDTO->getDblCpf())){
          $objInfraException->lancarValidacao('CPF inválido.');
        }
			  
				$objContatoDTO->setDblCpf(InfraUtil::retirarFormatacao($objProcedimentoOuvidoriaDTO->getDblCpf()));

				$arr = $objContatoRN->listarRN0325($objContatoDTO);
				if (count($arr)==0){
				   $objContatoDTO->unSetDblCpf();
				}
			}
			
			if ($objProcedimentoOuvidoriaDTO->getDblRg()=='999999999999999' && $objProcedimentoOuvidoriaDTO->getStrOrgaoExpedidor()=='888888'){
				$objProcedimentoOuvidoriaDTO->setDblRg(null);
				$objProcedimentoOuvidoriaDTO->setStrOrgaoExpedidor(null);
			}
			
			if (count($arr) == 0  && !InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getDblRg()) && !InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getStrOrgaoExpedidor())) {
			  
			  $strRg = $objProcedimentoOuvidoriaDTO->getDblRg();
        for($i=0;$i<strlen($strRg);$i++){
          if (!is_numeric($strRg[$i])){
            $objInfraException->lancarValidacao('RG inválido.');
          }
        }
			  
				$objContatoDTO->setDblRg(InfraUtil::retirarFormatacao($objProcedimentoOuvidoriaDTO->getDblRg()));
				$objContatoDTO->setStrOrgaoExpedidor($objProcedimentoOuvidoriaDTO->getStrOrgaoExpedidor());
				$arr = $objContatoRN->listarRN0325($objContatoDTO);
				if (count($arr) == 0){
				  $objContatoDTO->unSetDblRg();
				  $objContatoDTO->unSetStrOrgaoExpedidor();
				}
			}

      if ( count($arr)==0 && InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getDblCpf()) && InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getDblRg())){
        $objContatoDTO->setStrEmail($objProcedimentoOuvidoriaDTO->getStrEmail());
        $arr = $objContatoRN->listarRN0325($objContatoDTO);
      }

      $arrProcessos = array();

      //Se já tem pega primeira ocorrência
      if (count($arr)>0){

      	//atualiza dados
      	$objContatoDTO->retNumIdContato();
        $objContatoDTO->setNumIdContato($arr[0]->getNumIdContato());
        $objContatoDTO->setStrNome($objProcedimentoOuvidoriaDTO->getStrNome());
        $objContatoDTO->setStrNomeSocial($objProcedimentoOuvidoriaDTO->getStrNomeSocial());
        $objContatoDTO->setStrTelefoneResidencial(InfraUtil::retirarFormatacao($objProcedimentoOuvidoriaDTO->getStrTelefone()));
        $objContatoDTO->setNumIdUf($numIdUf);
        $objContatoDTO->setNumIdPais($numIdPais);
				$objContatoDTO->setNumIdCidade($numIdCidade);
 				$objContatoDTO->setDblCpf(InfraUtil::retirarFormatacao($objProcedimentoOuvidoriaDTO->getDblCpf()));
				$objContatoDTO->setDblRg(InfraUtil::retirarFormatacao($objProcedimentoOuvidoriaDTO->getDblRg()));
				$objContatoDTO->setStrOrgaoExpedidor($objProcedimentoOuvidoriaDTO->getStrOrgaoExpedidor());

        $objContatoRN->alterarRN0323($objContatoDTO);
        
        //busca os processos onde ele é interessado na Unidade
        $objParticipanteRN 	= new ParticipanteRN();
        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retDblIdProtocolo();
        $objParticipanteDTO->setNumIdContato($objContatoDTO->getNumIdContato());
        $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
        $objParticipanteDTO->setStrStaProtocoloProtocolo(ProtocoloRN::$TP_PROCEDIMENTO);
        $objParticipanteDTO->setStrSinOuvidoriaUnidadeGeradoraProtocolo('S');
        $arrProcessos = InfraArray::converterArrInfraDTO($objParticipanteRN->listarRN0189($objParticipanteDTO),'IdProtocolo');
        
      }else{


        //cadastra contato
        $objContatoDTO->retNumIdContato();
        $objContatoDTO->setNumIdContato(null);
      	$objContatoDTO->setNumIdTipoContato($numIdTipoContato);
				$objContatoDTO->setNumIdContatoAssociado(null);
				$objContatoDTO->setStrStaNatureza(ContatoRN::$TN_PESSOA_FISICA);
        $objContatoDTO->setStrNome($objProcedimentoOuvidoriaDTO->getStrNome());
        $objContatoDTO->setStrNomeSocial($objProcedimentoOuvidoriaDTO->getStrNomeSocial());
        $objContatoDTO->setDblCpf(InfraUtil::retirarFormatacao($objProcedimentoOuvidoriaDTO->getDblCpf()));
        $objContatoDTO->setDblRg(InfraUtil::retirarFormatacao($objProcedimentoOuvidoriaDTO->getDblRg()));
        $objContatoDTO->setStrOrgaoExpedidor($objProcedimentoOuvidoriaDTO->getStrOrgaoExpedidor());
        $objContatoDTO->setStrTelefoneResidencial($objProcedimentoOuvidoriaDTO->getStrTelefone());
        $objContatoDTO->setStrEmail($objProcedimentoOuvidoriaDTO->getStrEmail());
        $objContatoDTO->setNumIdUf($numIdUf);
        $objContatoDTO->setNumIdCidade($numIdCidade);
        $objContatoDTO->setNumIdPais($numIdPais);
        $objContatoDTO->setStrSinEnderecoAssociado('N');
        $objContatoDTO->setStrSinAtivo('S');

        $objContatoDTO = $objContatoRN->cadastrarRN0322($objContatoDTO);
      }
      
      //procedimento
    	$objProcedimentoDTO = new ProcedimentoDTO();      	
    	$objProcedimentoDTO->setDblIdProcedimento(null);
    	$objProcedimentoDTO->setNumIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
    	$objProcedimentoDTO->setStrSinGerarPendencia('S');

    	//protocolo
    	$objProtocoloDTO = new ProtocoloDTO();
    	$objProtocoloDTO->setStrDescricao(null);
   	 	$objProtocoloDTO->setNumIdUnidadeGeradora(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    	$objProtocoloDTO->setNumIdUsuarioGerador(SessaoSEI::getInstance()->getNumIdUsuario());
    	$objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());
    	$objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_PROCEDIMENTO);
    	$objProtocoloDTO->setStrStaNivelAcessoLocal($objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao());
    	$objProtocoloDTO->setStrStaGrauSigilo($objTipoProcedimentoDTO->getStrStaGrauSigiloSugestao());
    	$objProtocoloDTO->setNumIdHipoteseLegal($objTipoProcedimentoDTO->getNumIdHipoteseLegalSugestao());

    	//Busca e adiciona os assuntos sugeridos para o tipo da Unidade  	
  	 	$objRelTipoProcedimentoAssuntoDTO = new RelTipoProcedimentoAssuntoDTO();
  	 	$objRelTipoProcedimentoAssuntoDTO->retNumIdAssunto();
  	 	$objRelTipoProcedimentoAssuntoDTO->retNumSequencia();
   	 	$objRelTipoProcedimentoAssuntoDTO->setNumIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());  
  
  	 	$objRelTipoProcedimentoAssuntoRN = new RelTipoProcedimentoAssuntoRN();
  	 	$arrObjRelTipoProcedimentoAssuntoDTO = $objRelTipoProcedimentoAssuntoRN->listarRN0192($objRelTipoProcedimentoAssuntoDTO);
  	 	$arrObjAssuntoDTO = array();
      foreach($arrObjRelTipoProcedimentoAssuntoDTO as $objRelTipoProcedimentoAssuntoDTO){
      	$objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
      	$objRelProtocoloAssuntoDTO->setNumIdAssunto($objRelTipoProcedimentoAssuntoDTO->getNumIdAssunto());
      	$objRelProtocoloAssuntoDTO->setNumSequencia($objRelTipoProcedimentoAssuntoDTO->getNumSequencia());
      	$arrObjAssuntoDTO[] = $objRelProtocoloAssuntoDTO;
      }
    	$objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO($arrObjAssuntoDTO);    	 	    	 
  
    	//adiciona o contato cadastrado como interessado
    	$objParticipanteDTO = new ParticipanteDTO();
    	$objParticipanteDTO->setNumIdContato($objContatoDTO->getNumIdContato());
    	$objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
    	$objParticipanteDTO->setNumSequencia(0);
    	$objProtocoloDTO->setArrObjParticipanteDTO(array($objParticipanteDTO));
      $objProtocoloDTO->setArrObjObservacaoDTO(array());
      $objProcedimentoDTO->setObjProtocoloDTO($objProtocoloDTO);
      
      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTO = $objProcedimentoRN->gerarRN0156($objProcedimentoDTO);

      $objAtividadeRN = new AtividadeRN();
	 		
      $objAtividadeDTOGeracao = new AtividadeDTO();
      $objAtividadeDTOGeracao->retDthAbertura();
      $objAtividadeDTOGeracao->retNumIdAtividade();
      $objAtividadeDTOGeracao->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTOGeracao->setNumIdTarefa(TarefaRN::$TI_GERACAO_PROCEDIMENTO);
      
      $objAtividadeDTOGeracao = $objAtividadeRN->consultarRN0033($objAtividadeDTOGeracao);
      $objAtividadeDTOGeracao->setStrSinInicial('N');
      
      $objAtividadeRN->alterarCondicaoGeradoRecebido($objAtividadeDTOGeracao);
      
	 		
      //se tem outros processos faz associacao entre eles
      if (count($arrProcessos) > 0){
      	foreach ($arrProcessos as $numProcesso)	{
      		
      	$objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
		  	$objRelProtocoloProtocoloDTO->setDblIdProtocolo1($numProcesso);
				$objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProcedimentoDTO->getDblIdProcedimento());
		  	$objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);
				
		  	$objProcedimentoRN->relacionarProcedimentoRN1020($objRelProtocoloProtocoloDTO);		  	
      	}  	
      }
      
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setDblIdDocumento(null);
      $objDocumentoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
   	  $objDocumentoDTO->setNumIdSerie($objInfraParametro->getValor('ID_SERIE_OUVIDORIA'));
   	  
   	  if ($objProcedimentoOuvidoriaDTO->getStrSinRetorno() == 'N'){
   	  	$retorno = 'Não';
   	  }else {
   	  	$retorno = 'Sim';
   	  }
   	  
   	  $strXmlFormulario = '';
   	  $strXmlFormulario .= '<?xml version="1.0" encoding="iso-8859-1"?>';
   	  $strXmlFormulario .= '<documento>';
   	  $strXmlFormulario .= '<atributo nome="'.self::$FORM_DTH_ENVIO.'" titulo="Data de Envio">';
      if (InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getDblIdProcedimentoOrigem())){
        $strXmlFormulario .= InfraString::formatarXML($objAtividadeDTOGeracao->getDthAbertura());
      }else{

        $objAtividadeDTOGeracaoOrigem = new AtividadeDTO();
        $objAtividadeDTOGeracaoOrigem->retDthAbertura();
        $objAtividadeDTOGeracaoOrigem->setDblIdProtocolo($objProcedimentoOuvidoriaDTO->getDblIdProcedimentoOrigem());
        $objAtividadeDTOGeracaoOrigem->setNumIdTarefa(TarefaRN::$TI_GERACAO_PROCEDIMENTO);
        $objAtividadeDTOGeracaoOrigem = $objAtividadeRN->consultarRN0033($objAtividadeDTOGeracaoOrigem);

        $strXmlFormulario .= InfraString::formatarXML($objAtividadeDTOGeracaoOrigem->getDthAbertura());
      }
      $strXmlFormulario .= '</atributo>'."\n";

      $strXmlFormulario .= '<atributo nome="'.self::$FORM_NOME.'" titulo="Nome">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getStrNome()).'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_NOME_SOCIAL.'" titulo="Nome Social">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getStrNomeSocial()).'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_EMAIL.'" titulo="E-mail">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getStrEmail()).'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_CPF.'" titulo="CPF">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getDblCpf()).'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_RG.'" titulo="RG">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getDblRg()).'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_ORGAO_EXPEDIDOR.'" titulo="Orgão Expedidor">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getStrOrgaoExpedidor()).'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_TELEFONE.'" titulo="Telefone">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getStrTelefone()).'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_ESTADO.'" titulo="Estado">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getStrEstado()).'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_CIDADE.'" titulo="Cidade">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getStrCidade()).'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_PROCESSOS.'" titulo="Processos Relacionados">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getStrProcessos()).'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_RETORNO.'" titulo="Deseja Retorno">'.$retorno.'</atributo>'."\n";
      $strXmlFormulario .= '<atributo nome="'.self::$FORM_MENSAGEM.'" titulo="Mensagem">'.InfraString::formatarXML($objProcedimentoOuvidoriaDTO->getStrMensagem()).'</atributo>'."\n";

      $arrObjAtributoOuvidoriaDTO = $objProcedimentoOuvidoriaDTO->getArrObjAtributoOuvidoriaDTO();
      foreach($arrObjAtributoOuvidoriaDTO as $objAtributoOuvidoriaDTO){
        $strXmlFormulario .= '<atributo nome="A_'.InfraString::formatarXML($objAtributoOuvidoriaDTO->getStrNome()).'" id="'.InfraString::formatarXML($objAtributoOuvidoriaDTO->getStrId()).'" titulo="'.InfraString::formatarXML($objAtributoOuvidoriaDTO->getStrTitulo()).'">'.InfraString::formatarXML($objAtributoOuvidoriaDTO->getStrValor()).'</atributo>'."\n";
      }

    	$strXmlFormulario .= '</documento>';
    	
    	$objDocumentoDTO->setStrConteudo(InfraUtil::filtrarISO88591($strXmlFormulario));
   	  
    	$objDocumentoDTO->setDblIdDocumentoEdoc(null);
    	$objDocumentoDTO->setDblIdDocumentoEdocBase(null);
   	  $objDocumentoDTO->setNumIdUnidadeResponsavel(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    	$objDocumentoDTO->setStrNumero(null);
    	$objDocumentoDTO->setStrNomeArvore(null);
    	$objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_FORMULARIO_AUTOMATICO);

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProtocolo(null);
      $objProtocoloDTO->setStrStaNivelAcessoLocal(ProtocoloRN::$NA_PUBLICO);
      $objProtocoloDTO->setNumIdHipoteseLegal(null);
      $objProtocoloDTO->setStrDescricao(null);
  	  $objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());
			$objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO(array());							
      $objProtocoloDTO->setArrObjParticipanteDTO(array($objParticipanteDTO));						
			$objProtocoloDTO->setArrObjObservacaoDTO(array());

	 		$objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO); 		
	 		
	 		$objDocumentoRN = new DocumentoRN();
	 		$objDocumentoRN->cadastrarRN0003($objDocumentoDTO);

	 		//muda para vermelha a visualizacao
      $objAtividadeDTOVisualizacao = new AtividadeDTO();
      $objAtividadeDTOVisualizacao->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTOVisualizacao->setNumTipoVisualizacao(AtividadeRN::$TV_NAO_VISUALIZADO);
      
      $objAtividadeRN = new AtividadeRN();
	 		$objAtividadeRN->atualizarVisualizacao($objAtividadeDTOVisualizacao);
	 		
	 		//só manda email se nao estiver repassando
	 		if (InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getDblIdProcedimentoOrigem())){
		 		
  			$objEmailSistemaDTO = new EmailSistemaDTO();
  			$objEmailSistemaDTO->retStrDe();
  			$objEmailSistemaDTO->retStrPara();
  			$objEmailSistemaDTO->retStrAssunto();
  			$objEmailSistemaDTO->retStrConteudo();
  			$objEmailSistemaDTO->setNumIdEmailSistema(EmailSistemaRN::$ES_CONTATO_OUVIDORIA);
  			
  			$objEmailSistemaRN = new EmailSistemaRN();
  			$objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);
  			
  			if ($objEmailSistemaDTO!=null){

          if (InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getStrNomeSocial())) {
            $strNome = $objProcedimentoOuvidoriaDTO->getStrNome();
          }else{
            $strNome = $objProcedimentoOuvidoriaDTO->getStrNomeSocial();
          }

          $strDe = $objEmailSistemaDTO->getStrDe();
          $strDe = str_replace('@sigla_sistema@', SessaoSEI::getInstance()->getStrSiglaSistema(), $strDe);
          $strDe = str_replace('@email_sistema@', $objInfraParametro->getValor('SEI_EMAIL_SISTEMA'), $strDe);
          $strDe = str_replace('@sigla_orgao@', $objOrgaoDTO->getStrSigla(), $strDe);
          $strDe = str_replace('@sigla_orgao_minusculas@', InfraString::transformarCaixaBaixa($objOrgaoDTO->getStrSigla()), $strDe);
          $strDe = str_replace('@sufixo_email@', $objInfraParametro->getValor('SEI_SUFIXO_EMAIL'), $strDe);

    		  $strPara = $objEmailSistemaDTO->getStrPara();
          $strPara = str_replace('@nome_contato@',$strNome,$strPara);
    		  $strPara = str_replace('@email_contato@',$objProcedimentoOuvidoriaDTO->getStrEmail(),$strPara);
    				  
    		  $strAssunto = $objEmailSistemaDTO->getStrAssunto();
    		  $strAssunto = str_replace('@sigla_orgao@',$objOrgaoDTO->getStrSigla(),$strAssunto);
    				  
    		  $strConteudo = $objEmailSistemaDTO->getStrConteudo();
    		  $strConteudo = str_replace('@processo@',$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado(),$strConteudo);
    		  $strConteudo = str_replace('@tipo_processo@',$objTipoProcedimentoDTO->getStrNome(),$strConteudo);
          $strConteudo = str_replace('@nome_contato@', $strNome, $strConteudo);
    		  $strConteudo = str_replace('@email_contato@',$objProcedimentoOuvidoriaDTO->getStrEmail(),$strConteudo);
          $strConteudo = str_replace('@sigla_orgao@',$objOrgaoDTO->getStrSigla(),$strConteudo);
          $strConteudo = str_replace('@descricao_orgao@',$objOrgaoDTO->getStrDescricao(),$strConteudo);
          $strConteudo = str_replace('@sitio_internet_orgao@',$objOrgaoDTO->getStrSitioInternetContato(),$strConteudo);
  
   		 		$strConteudoFormulario = '';
  			  $strConteudoFormulario .= 'Formulário de Ouvidoria'."\n";
  			  $strConteudoFormulario .= DocumentoINT::formatarExibicaoConteudo(DocumentoINT::$TV_TEXTO, $strXmlFormulario);
  			  
  			  $arrConteudoFormulario = explode("\n",$strConteudoFormulario);
  			  $strConteudoFormulario = '';
  			  foreach($arrConteudoFormulario as $linha){
  			  	$strConteudoFormulario .= '>  '.$linha."\n";
  			  }
    		  $strConteudo = str_replace('@conteudo_formulario_ouvidoria@',$strConteudoFormulario,$strConteudo);

          $objEmailDTO = new EmailDTO();
          $objEmailDTO->setStrDe($strDe);
          $objEmailDTO->setStrPara($strPara);
          $objEmailDTO->setStrAssunto($strAssunto);
          $objEmailDTO->setStrMensagem($strConteudo);

          MailSEI::getInstance()->adicionar($objEmailDTO);
  			}
	 		}

	 		if ($numIdUsuarioAtual!=null && $numIdUnidadeAtual!=null) {
        SessaoSEI::getInstance()->simularLogin(null, null, $numIdUsuarioAtual, $numIdUnidadeAtual);
        SessaoSEI::getInstance()->setBolHabilitada(true);
      }

      return $objProcedimentoDTO;
	 		
    }catch(Exception $e){
      throw new InfraException('Erro registrando contato com ouvidoria.',$e);
    }
  }

  public function reencaminhar(ProtocoloDTO $parObjProtocoloDTO){
    try{

      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(true);

      MailSEI::getInstance()->limpar();

      $this->reencaminharInterno($parObjProtocoloDTO);

      MailSEI::getInstance()->enviar();

      FeedSEIProtocolos::getInstance()->setBolAcumularFeeds(false);
      FeedSEIProtocolos::getInstance()->indexarFeeds();

    }catch(Exception $e){
      throw new InfraException('Erro reencaminhando Ouvidoria.',$e);
    }
  }

	protected function reencaminharInternoControlado(ProtocoloDTO $parObjProtocoloDTO){
	  try{
		  
		  SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_reencaminhar_ouvidoria', __METHOD__, $parObjProtocoloDTO);
			
			$objInfraParametro = new InfraParametro(BancoSEI::getInstance());

			$objProcedimentoDTOOrigem = new ProcedimentoDTO();
      $objProcedimentoDTOOrigem->retDblIdProcedimento();
      $objProcedimentoDTOOrigem->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTOOrigem->retNumIdTipoProcedimento();
      $objProcedimentoDTOOrigem->retStrNomeTipoProcedimento();
      $objProcedimentoDTOOrigem->retNumIdOrgaoUnidadeGeradoraProtocolo();
      $objProcedimentoDTOOrigem->setDblIdProcedimento($parObjProtocoloDTO->getDblIdProtocolo());
			
			$objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTOOrigem = $objProcedimentoRN->consultarRN0201($objProcedimentoDTOOrigem);

			$objDocumentoDTO = new DocumentoDTO();
			$objDocumentoDTO->retStrConteudo();
			$objDocumentoDTO->setDblIdProcedimento($objProcedimentoDTOOrigem->getDblIdProcedimento());
			$objDocumentoDTO->setNumIdSerie($objInfraParametro->getValor('ID_SERIE_OUVIDORIA'));
			$objDocumentoDTO->setDblIdDocumentoEdoc(null);
	
			$objDocumentoRN = new DocumentoRN();
			$objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
			
			if ($objDocumentoDTO==null){
				$objInfraException = new InfraException(); 
				$objInfraException->lancarValidacao('Formulário da ouvidoria não encontrado no processo');
			}
			
      $objXml = new DomDocument('1.0','iso-8859-1');

      $objXml->loadXML($objDocumentoDTO->getStrConteudo());

      $arrAtributos = $objXml->getElementsByTagName('atributo');


      $objProcedimentoOuvidoriaDTO = new ProcedimentoOuvidoriaDTO();
      $objProcedimentoOuvidoriaDTO->setNumIdOrgao($parObjProtocoloDTO->getNumIdOrgaoUnidadeGeradora());
      $objProcedimentoOuvidoriaDTO->setDblIdProcedimentoOrigem($objProcedimentoDTOOrigem->getDblIdProcedimento());
      $objProcedimentoOuvidoriaDTO->setNumIdTipoProcedimento($objProcedimentoDTOOrigem->getNumIdTipoProcedimento());

      $arrObjAtributoOuvidoriaDTO = array();

      foreach($arrAtributos as $atributo){
        if ($atributo->getAttribute('nome') == self::$FORM_NOME) {
          $objProcedimentoOuvidoriaDTO->setStrNome(utf8_decode($atributo->nodeValue));
        }else if ($atributo->getAttribute('nome') == self::$FORM_NOME_SOCIAL){
          $objProcedimentoOuvidoriaDTO->setStrNomeSocial(utf8_decode($atributo->nodeValue));
        }else if ($atributo->getAttribute('nome') == self::$FORM_EMAIL){
          $objProcedimentoOuvidoriaDTO->setStrEmail(utf8_decode($atributo->nodeValue));
        }else if ($atributo->getAttribute('nome') == self::$FORM_CPF){
          $objProcedimentoOuvidoriaDTO->setDblCpf(utf8_decode($atributo->nodeValue));
        }else if ($atributo->getAttribute('nome') == self::$FORM_RG){
          $objProcedimentoOuvidoriaDTO->setDblRg(utf8_decode($atributo->nodeValue));
        }else if ($atributo->getAttribute('nome') == self::$FORM_ORGAO_EXPEDIDOR){
          $objProcedimentoOuvidoriaDTO->setStrOrgaoExpedidor(utf8_decode($atributo->nodeValue));
        }else if ($atributo->getAttribute('nome') == self::$FORM_TELEFONE){
          $objProcedimentoOuvidoriaDTO->setStrTelefone(utf8_decode($atributo->nodeValue));
        }else if ($atributo->getAttribute('nome') == self::$FORM_ESTADO){
          $objProcedimentoOuvidoriaDTO->setStrEstado(utf8_decode($atributo->nodeValue));
        }else if ($atributo->getAttribute('nome') == self::$FORM_CIDADE){
          $objProcedimentoOuvidoriaDTO->setStrCidade(utf8_decode($atributo->nodeValue));
        }else if ($atributo->getAttribute('nome') == self::$FORM_PROCESSOS){
          $objProcedimentoOuvidoriaDTO->setStrProcessos(utf8_decode($atributo->nodeValue));
        }else if ($atributo->getAttribute('nome') == self::$FORM_RETORNO){
          $objProcedimentoOuvidoriaDTO->setStrSinRetorno((InfraString::transformarCaixaAlta(trim(utf8_decode($atributo->nodeValue)))=='SIM'?'S':'N'));
        }else if ($atributo->getAttribute('nome') == self::$FORM_MENSAGEM){
          $objProcedimentoOuvidoriaDTO->setStrMensagem(utf8_decode($atributo->nodeValue));
        }else if (substr($atributo->getAttribute('nome'),0,2) == 'A_'){
          $objAtributoOuvidoriaDTO = new AtributoOuvidoriaDTO();
          $objAtributoOuvidoriaDTO->setStrId(utf8_decode($atributo->getAttribute('id')));
          $objAtributoOuvidoriaDTO->setStrNome(utf8_decode(substr($atributo->getAttribute('nome'),2)));
          $objAtributoOuvidoriaDTO->setStrTitulo(utf8_decode($atributo->getAttribute('titulo')));
          $objAtributoOuvidoriaDTO->setStrValor(utf8_decode($atributo->nodeValue));
          $arrObjAtributoOuvidoriaDTO[] = $objAtributoOuvidoriaDTO;
        }
      }
      $objProcedimentoOuvidoriaDTO->setArrObjAtributoOuvidoriaDTO($arrObjAtributoOuvidoriaDTO);

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->retNumIdUnidade();
      $objUnidadeDTO->setNumIdOrgao($parObjProtocoloDTO->getNumIdOrgaoUnidadeGeradora());
      $objUnidadeDTO->setStrSinOuvidoria('S');

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

      if ($objUnidadeDTO==null){
        throw new InfraException('Unidade para geração do processo de ouvidoria não encontrada.');
      }

      $numIdUnidadeAtual = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
      $numIdUsuarioAtual = SessaoSEI::getInstance()->getNumIdUsuario();

      SessaoSEI::getInstance()->setBolHabilitada(false);
      SessaoSEI::getInstance()->simularLogin(SessaoSEI::$USUARIO_INTERNET, null, null, $objUnidadeDTO->getNumIdUnidade());

      $objProcedimentoDTODestino = $this->registrarOuvidoriaRN1148($objProcedimentoOuvidoriaDTO);

      SessaoSEI::getInstance()->simularLogin(null, null, $numIdUsuarioAtual, $numIdUnidadeAtual);
      SessaoSEI::getInstance()->setBolHabilitada(true);

      $arrObjAtributoAndamentoDTO = array();
      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
      $objAtributoAndamentoDTO->setStrValor($objProcedimentoDTODestino->getStrProtocoloProcedimentoFormatado());
      $objAtributoAndamentoDTO->setStrIdOrigem($objProcedimentoDTODestino->getDblIdProcedimento());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objOrgaoDTODestino = new OrgaoDTO();
      $objOrgaoDTODestino->setBolExclusaoLogica(false);
      $objOrgaoDTODestino->retNumIdOrgao();
      $objOrgaoDTODestino->retStrSigla();
      $objOrgaoDTODestino->retStrDescricao();
      $objOrgaoDTODestino->retStrSitioInternetContato();
      $objOrgaoDTODestino->setNumIdOrgao($parObjProtocoloDTO->getNumIdOrgaoUnidadeGeradora());

      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTODestino = $objOrgaoRN->consultarRN1352($objOrgaoDTODestino);

      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setStrNome('ORGAO');
      $objAtributoAndamentoDTO->setStrValor($objOrgaoDTODestino->getStrSigla().'¥'.$objOrgaoDTODestino->getStrDescricao());
      $objAtributoAndamentoDTO->setStrIdOrigem($objOrgaoDTODestino->getNumIdOrgao());
      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTOOrigem->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_OUVIDORIA_CORRECAO_ENCAMINHAMENTO);
      $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

      $objAtividadeRN = new AtividadeRN();
      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

			$objProcedimentoDTO = new ProcedimentoDTO();
			$objProcedimentoDTO->setNumIdTipoProcedimento($objInfraParametro->getValor('ID_TIPO_PROCEDIMENTO_OUVIDORIA_EQUIVOCO'));
			$objProcedimentoDTO->setDblIdProcedimento($objProcedimentoDTOOrigem->getDblIdProcedimento());

			$objProcedimentoRN->alterarRN0202($objProcedimentoDTO);

			$objProcedimentoRN->concluir(array($objProcedimentoDTO));

      $objEmailSistemaDTO = new EmailSistemaDTO();
      $objEmailSistemaDTO->retStrDe();
      $objEmailSistemaDTO->retStrPara();
      $objEmailSistemaDTO->retStrAssunto();
      $objEmailSistemaDTO->retStrConteudo();
      $objEmailSistemaDTO->setNumIdEmailSistema(EmailSistemaRN::$ES_CORRECAO_ENCAMINHAMENTO_OUVIDORIA);

      $objEmailSistemaRN = new EmailSistemaRN();
      $objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);

      if ($objEmailSistemaDTO!=null){

        if (InfraString::isBolVazia($objProcedimentoOuvidoriaDTO->getStrNomeSocial())){
          $strNome = $objProcedimentoOuvidoriaDTO->getStrNome();
        }else{
          $strNome = $objProcedimentoOuvidoriaDTO->getStrNomeSocial();
        }


        $objOrgaoDTOOrigem = new OrgaoDTO();
        $objOrgaoDTOOrigem->setBolExclusaoLogica(false);
        $objOrgaoDTOOrigem->retNumIdOrgao();
        $objOrgaoDTOOrigem->retStrSigla();
        $objOrgaoDTOOrigem->retStrDescricao();
        $objOrgaoDTOOrigem->retStrSitioInternetContato();
        $objOrgaoDTOOrigem->setNumIdOrgao($objProcedimentoDTOOrigem->getNumIdOrgaoUnidadeGeradoraProtocolo());
        $objOrgaoDTOOrigem = $objOrgaoRN->consultarRN1352($objOrgaoDTOOrigem);


        $strDe = $objEmailSistemaDTO->getStrDe();
        $strDe = str_replace('@sigla_sistema@',SessaoSEI::getInstance()->getStrSiglaSistema(),$strDe);
        $strDe = str_replace('@email_sistema@',$objInfraParametro->getValor('SEI_EMAIL_SISTEMA'),$strDe);
        $strDe = str_replace('@sigla_orgao_origem@',$objOrgaoDTOOrigem->getStrSigla(),$strDe);
        $strDe = str_replace('@sigla_orgao_destino@',$objOrgaoDTODestino->getStrSigla(),$strDe);
        $strDe = str_replace('@sigla_orgao_origem_minusculas@',InfraString::transformarCaixaBaixa($objOrgaoDTOOrigem->getStrSigla()),$strDe);
        $strDe = str_replace('@sigla_orgao_destino_minusculas@',InfraString::transformarCaixaBaixa($objOrgaoDTODestino->getStrSigla()),$strDe);
        $strDe = str_replace('@sufixo_email@',$objInfraParametro->getValor('SEI_SUFIXO_EMAIL'),$strDe);

        $strPara = $objEmailSistemaDTO->getStrPara();
        $strPara = str_replace('@nome_contato@',$strNome,$strPara);
        $strPara = str_replace('@email_contato@',$objProcedimentoOuvidoriaDTO->getStrEmail(),$strPara);

        $strAssunto = $objEmailSistemaDTO->getStrAssunto();
        $strAssunto = str_replace('@sigla_orgao_origem@',$objOrgaoDTOOrigem->getStrSigla(),$strAssunto);
        $strAssunto = str_replace('@sigla_orgao_destino@',$objOrgaoDTODestino->getStrSigla(),$strAssunto);

        $strConteudo = $objEmailSistemaDTO->getStrConteudo();
        $strConteudo = str_replace('@processo_origem@',$objProcedimentoDTOOrigem->getStrProtocoloProcedimentoFormatado(),$strConteudo);
        $strConteudo = str_replace('@processo_destino@',$objProcedimentoDTODestino->getStrProtocoloProcedimentoFormatado(),$strConteudo);
        $strConteudo = str_replace('@tipo_processo@',$objProcedimentoDTOOrigem->getStrNomeTipoProcedimento(),$strConteudo);
        $strConteudo = str_replace('@nome_contato@',$strNome,$strConteudo);
        $strConteudo = str_replace('@email_contato@',$objProcedimentoOuvidoriaDTO->getStrEmail(),$strConteudo);
        $strConteudo = str_replace('@sigla_orgao_origem@',$objOrgaoDTOOrigem->getStrSigla(),$strConteudo);
        $strConteudo = str_replace('@sigla_orgao_destino@',$objOrgaoDTODestino->getStrSigla(),$strConteudo);
        $strConteudo = str_replace('@descricao_orgao_origem@',$objOrgaoDTOOrigem->getStrDescricao(),$strConteudo);
        $strConteudo = str_replace('@descricao_orgao_destino@',$objOrgaoDTODestino->getStrDescricao(),$strConteudo);
        $strConteudo = str_replace('@sitio_internet_orgao_origem@',$objOrgaoDTOOrigem->getStrSitioInternetContato(),$strConteudo);
        $strConteudo = str_replace('@sitio_internet_orgao_destino@',$objOrgaoDTODestino->getStrSitioInternetContato(),$strConteudo);

        $strConteudoFormulario = '';
        $strConteudoFormulario .= 'Formulário de Ouvidoria'."\n";
        $strConteudoFormulario .= DocumentoINT::formatarExibicaoConteudo(DocumentoINT::$TV_TEXTO, $objDocumentoDTO->getStrConteudo());

        $arrConteudoFormulario = explode("\n",$strConteudoFormulario);
        $strConteudoFormulario = '';
        foreach($arrConteudoFormulario as $linha){
          $strConteudoFormulario .= '>  '.$linha."\n";
        }
        $strConteudo = str_replace('@conteudo_formulario_ouvidoria@',$strConteudoFormulario,$strConteudo);

        $objEmailDTO = new EmailDTO();
        $objEmailDTO->setStrDe($strDe);
        $objEmailDTO->setStrPara($strPara);
        $objEmailDTO->setStrAssunto($strAssunto);
        $objEmailDTO->setStrMensagem($strConteudo);

        MailSEI::getInstance()->adicionar($objEmailDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reencaminhando Ouvidoria.',$e);
    }
	}

  protected function finalizarControlado(ProcedimentoDTO $parObjProcedimentoDTO){
	
	  try{
	    
	    SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_finalizar_ouvidoria', __METHOD__, $parObjProcedimentoDTO);
	    
	    $objProcedimentoRN = new ProcedimentoRN();
	    
	    $objProcedimentoDTOBanco = new ProcedimentoDTO();
	    $objProcedimentoDTOBanco->retStrStaOuvidoria();
	    $objProcedimentoDTOBanco->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
	    
	    $objProcedimentoDTOBanco = $objProcedimentoRN->consultarRN0201($objProcedimentoDTOBanco);
	     
	    if ($objProcedimentoDTOBanco->getStrStaOuvidoria()!=$parObjProcedimentoDTO->getStrStaOuvidoria()){
	    
  	    $objProcedimentoDTO = new ProcedimentoDTO();
  	    $objProcedimentoDTO->setStrStaOuvidoria($parObjProcedimentoDTO->getStrStaOuvidoria());
  	    $objProcedimentoDTO->setDblIdProcedimento($parObjProcedimentoDTO->getDblIdProcedimento());
  	    
  	    $objProcedimentoRN->alterarRN0202($objProcedimentoDTO);
  	    
  	    $objAtividadeRN = new AtividadeRN();
  	    
  	    $objAtividadeDTO = new AtividadeDTO();
  	    $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
  	    $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  	    
  	    if ($parObjProcedimentoDTO->getStrStaOuvidoria()==ProcedimentoRN::$TFO_SIM){
  	      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_OUVIDORIA_SOLICITACAO_ATENDIDA);
  	    }else if ($parObjProcedimentoDTO->getStrStaOuvidoria()==ProcedimentoRN::$TFO_NAO){
  	      $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_OUVIDORIA_SOLICITACAO_NAO_ATENDIDA);
	      }else if ($parObjProcedimentoDTO->getStrStaOuvidoria()==ProcedimentoRN::$TFO_NENHUM){
	        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_OUVIDORIA_CANCELADA_SINALIZACAO_ATENDIMENTO);
	      }
	      
	      $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
	       
	    }

	  }catch(Exception $e){
	    throw new InfraException('Erro finalizando Ouvidoria.',$e);
	  }
	}

	protected function listarAcompanhamentoConectado(AcompanhamentoOuvidoriaDTO $parObjAcompanhamentoOuvidoriaDTO){
	  try{

      LimiteSEI::getInstance()->configurarNivel2();

      //Valida Permissao
	    SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_listar_ouvidoria',__METHOD__,$parObjAcompanhamentoOuvidoriaDTO);
	
	    $objInfraException = new InfraException();

	    InfraData::validarPeriodo($parObjAcompanhamentoOuvidoriaDTO->getDtaInicio(), $parObjAcompanhamentoOuvidoriaDTO->getDtaFim(), $objInfraException);
	    
	    if (count($parObjAcompanhamentoOuvidoriaDTO->getArrObjTipoProcedimentoDTO())==0){
	      $objInfraException->lancarValidacao('Nenhum tipo de processo informado.');
	    }
	    
	    $objInfraException->lancarValidacoes();
	     
	    $objAcompanhamentoOuvidoriaDTO = new AcompanhamentoOuvidoriaDTO();
	    $objAcompanhamentoOuvidoriaDTO->setDistinct(true);
	    $objAcompanhamentoOuvidoriaDTO->retDblIdProtocolo();
	    $objAcompanhamentoOuvidoriaDTO->retStrProtocoloFormatadoProtocolo();
	    //$objAcompanhamentoOuvidoriaDTO->retNumIdUnidade();
	    //$objAcompanhamentoOuvidoriaDTO->retStrSiglaUnidade();
	    //$objAcompanhamentoOuvidoriaDTO->retStrDescricaoUnidade();
	    $objAcompanhamentoOuvidoriaDTO->retNumIdUnidadeOrigem();
	    $objAcompanhamentoOuvidoriaDTO->retStrSiglaUnidadeOrigem();
	    $objAcompanhamentoOuvidoriaDTO->retStrDescricaoUnidadeOrigem();
	    $objAcompanhamentoOuvidoriaDTO->retStrNomeTipoProcedimento();
	    $objAcompanhamentoOuvidoriaDTO->retStrStaOuvidoriaProcedimento();

      $this->montarCriteriosAcompanhamento($parObjAcompanhamentoOuvidoriaDTO,$objAcompanhamentoOuvidoriaDTO);

	    if ($parObjAcompanhamentoOuvidoriaDTO->isOrdDblIdProtocolo()){
	      $objAcompanhamentoOuvidoriaDTO->setOrdDblIdProtocolo($parObjAcompanhamentoOuvidoriaDTO->getOrdDblIdProtocolo());
	    }

	    if ($parObjAcompanhamentoOuvidoriaDTO->isOrdStrNomeTipoProcedimento()){
	      $objAcompanhamentoOuvidoriaDTO->setOrdStrNomeTipoProcedimento($parObjAcompanhamentoOuvidoriaDTO->getOrdStrNomeTipoProcedimento());
	    }

	    if ($parObjAcompanhamentoOuvidoriaDTO->isOrdStrSiglaUnidade()){
	      $objAcompanhamentoOuvidoriaDTO->setOrdStrSiglaUnidade($parObjAcompanhamentoOuvidoriaDTO->getOrdStrSiglaUnidade());
	    }

	    if ($parObjAcompanhamentoOuvidoriaDTO->isOrdStrStaOuvidoriaProcedimento()){
	      $objAcompanhamentoOuvidoriaDTO->setOrdStrStaOuvidoriaProcedimento($parObjAcompanhamentoOuvidoriaDTO->getOrdStrStaOuvidoriaProcedimento());
	    }
	     
	    //$objAcompanhamentoOuvidoriaDTO->setOrdDthAbertura(InfraDTO::$TIPO_ORDENACAO_DESC);
	    
	    //paginação
	    $objAcompanhamentoOuvidoriaDTO->setNumMaxRegistrosRetorno($parObjAcompanhamentoOuvidoriaDTO->getNumMaxRegistrosRetorno());
  		$objAcompanhamentoOuvidoriaDTO->setNumPaginaAtual($parObjAcompanhamentoOuvidoriaDTO->getNumPaginaAtual());
	
  		$objOuvidoriaBD = new OuvidoriaBD($this->getObjInfraIBanco());
  		$arrObjAcompanhamentoOuvidoriaDTO = $objOuvidoriaBD->listar($objAcompanhamentoOuvidoriaDTO);
	
	
  		//paginação
  		$parObjAcompanhamentoOuvidoriaDTO->setNumTotalRegistros($objAcompanhamentoOuvidoriaDTO->getNumTotalRegistros());
  		$parObjAcompanhamentoOuvidoriaDTO->setNumRegistrosPaginaAtual($objAcompanhamentoOuvidoriaDTO->getNumRegistrosPaginaAtual());
	
  		$objAtividadeRN = new AtividadeRN();
  		
  		foreach($arrObjAcompanhamentoOuvidoriaDTO as $objAcompanhamentoOuvidoriaDTO){
  		  $objAcompanhamentoOuvidoriaDTO->setStrNomeTipoProcedimento($objAcompanhamentoOuvidoriaDTO->getStrNomeTipoProcedimento());

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->retNumIdUnidade();
        $objAtividadeDTO->retStrSiglaUnidade();
        $objAtividadeDTO->retStrDescricaoUnidade();
        $objAtividadeDTO->retDthAbertura();
        $objAtividadeDTO->setDblIdProtocolo($objAcompanhamentoOuvidoriaDTO->getDblIdProtocolo());
        $objAtividadeDTO->setNumIdUnidadeOrigem($objAcompanhamentoOuvidoriaDTO->getNumIdUnidadeOrigem());

        if ($parObjAcompanhamentoOuvidoriaDTO->getStrSinTramitacaoOuvidoria()=='N') {

          if ($parObjAcompanhamentoOuvidoriaDTO->getNumIdUnidade()!=null) {
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE);
            $objAtividadeDTO->setNumIdUnidade($parObjAcompanhamentoOuvidoriaDTO->getNumIdUnidade());
          }else{
            $objAtividadeDTO->setNumIdTarefa(array(TarefaRN::$TI_GERACAO_PROCEDIMENTO, TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE),InfraDTO::$OPER_IN);
          }

        }else{
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_GERACAO_PROCEDIMENTO);
        }

        $objAtividadeDTO->setNumMaxRegistrosRetorno(2);
        $objAtividadeDTO->setOrdDthAbertura(InfraDTO::$TIPO_ORDENACAO_ASC);

        $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

        if (count($arrObjAtividadeDTO)==1){
          $objAtividadeDTO2 = $arrObjAtividadeDTO[0];
        }else{
          $objAtividadeDTO2 = $arrObjAtividadeDTO[1];
        }

        $objAcompanhamentoOuvidoriaDTO->setNumIdUnidade($objAtividadeDTO2->getNumIdUnidade());
        $objAcompanhamentoOuvidoriaDTO->setStrSiglaUnidade($objAtividadeDTO2->getStrSiglaUnidade());
        $objAcompanhamentoOuvidoriaDTO->setStrDescricaoUnidade($objAtividadeDTO2->getStrDescricaoUnidade());
        $objAcompanhamentoOuvidoriaDTO->setDthAbertura($objAtividadeDTO2->getDthAbertura());
  		}
  		
  		return $arrObjAcompanhamentoOuvidoriaDTO;
	    
	  }catch(Exception $e){
	    throw new InfraException('Erro listando acompanhamento da ouvidoria.',$e);
	  }
	}
	
	protected function gerarGraficoAcompanhamentoConectado(AcompanhamentoOuvidoriaDTO $parObjAcompanhamentoOuvidoriaDTO){
	  try{

      LimiteSEI::getInstance()->configurarNivel2();
	
	    //Valida Permissao
	    SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_gerar_grafico_ouvidoria',__METHOD__,$parObjAcompanhamentoOuvidoriaDTO);
	
	    $objInfraException = new InfraException();
	
	    InfraData::validarPeriodo($parObjAcompanhamentoOuvidoriaDTO->getDtaInicio(), $parObjAcompanhamentoOuvidoriaDTO->getDtaFim(), $objInfraException);
	     
	    if (count($parObjAcompanhamentoOuvidoriaDTO->getArrObjTipoProcedimentoDTO())==0){
	      $objInfraException->lancarValidacao('Nenhum tipo de processo informado.');
	    }
	     
	    $objInfraException->lancarValidacoes();
	
	    $objAcompanhamentoOuvidoriaDTO = new AcompanhamentoOuvidoriaDTO();
	    $objAcompanhamentoOuvidoriaDTO->setDistinct(true);
	    $objAcompanhamentoOuvidoriaDTO->retDblIdProtocolo();
	    $objAcompanhamentoOuvidoriaDTO->retStrStaOuvidoriaProcedimento();
	    $objAcompanhamentoOuvidoriaDTO->retNumIdUnidade();
	    $objAcompanhamentoOuvidoriaDTO->retNumIdUnidadeOrigem();
	    $objAcompanhamentoOuvidoriaDTO->retStrNomeTipoProcedimento();
	    $objAcompanhamentoOuvidoriaDTO->retNumIdTipoProcedimento();

      $this->montarCriteriosAcompanhamento($parObjAcompanhamentoOuvidoriaDTO,$objAcompanhamentoOuvidoriaDTO);

  		$objOuvidoriaBD = new OuvidoriaBD($this->getObjInfraIBanco());
  		$arrObjAcompanhamentoOuvidoriaDTO = $objOuvidoriaBD->listar($objAcompanhamentoOuvidoriaDTO);
	
  		$arrGraficoAcompanhamentoGeral = array();
  		$arrGraficoAcompanhamentoPorTipo = array();
  		
  		$dblIdEstatisticas = BancoSEI::getInstance()->getValorSequencia('seq_estatisticas');
  		$dthSnapshot = InfraData::getStrDataHoraAtual();
  		$objEstatisticasRN = new EstatisticasRN();

      $dtoBase = new EstatisticasDTO();
      $dtoBase->setDblIdEstatisticas($dblIdEstatisticas);
      $dtoBase->setDblIdProcedimento(null);
      $dtoBase->setNumIdTipoProcedimento(null);
      $dtoBase->setDblIdDocumento(null);
      $dtoBase->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $dtoBase->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $dtoBase->setNumAno(null);
      $dtoBase->setNumMes(null);
      $dtoBase->setDblTempoAberto(null);
      $dtoBase->setDthAbertura(null);
      $dtoBase->setDthConclusao(null);
      $dtoBase->setDthSnapshot($dthSnapshot);
      $dtoBase->setDblQuantidade(null);
      
  		foreach($arrObjAcompanhamentoOuvidoriaDTO as $objAcompanhamentoOuvidoriaDTO){
  		  
  		  $strStaOuvidoria = $objAcompanhamentoOuvidoriaDTO->getStrStaOuvidoriaProcedimento();
  		  $numIdTipoProcedimento = $objAcompanhamentoOuvidoriaDTO->getNumIdTipoProcedimento();
  		  $strNomeTipoProcedimento = $objAcompanhamentoOuvidoriaDTO->getStrNomeTipoProcedimento();
  		  
  		  
  		  if (isset($arrGraficoAcompanhamentoGeral[$strStaOuvidoria])){
  		    $arrGraficoAcompanhamentoGeral[$strStaOuvidoria]++;
  		  }else{
  		    $arrGraficoAcompanhamentoGeral[$strStaOuvidoria] = 1;
  		  }
  		  
  		  //retStrNomeTipoProcedimento
  		  if (isset($arrGraficoAcompanhamentoPorTipo[$numIdTipoProcedimento.'#'.$strNomeTipoProcedimento][$strStaOuvidoria])){
  		    $arrGraficoAcompanhamentoPorTipo[$numIdTipoProcedimento.'#'.$strNomeTipoProcedimento][$strStaOuvidoria]++;
  		  }else{
  		    $arrGraficoAcompanhamentoPorTipo[$numIdTipoProcedimento.'#'.$strNomeTipoProcedimento][$strStaOuvidoria] = 1;
  		  }
  		  
  		  $dto = clone($dtoBase);
  		  $dto->setDblIdProcedimento($objAcompanhamentoOuvidoriaDTO->getDblIdProtocolo());
  		  $dto->setNumIdTipoProcedimento($numIdTipoProcedimento);

  		  $objEstatisticasRN->acumular($dto);
  		}
      $objEstatisticasRN->acumular(null);
  		$objAcompanhamentoOuvidoriaDTO = new AcompanhamentoOuvidoriaDTO();
  		$objAcompanhamentoOuvidoriaDTO->setDblIdEstatisticas($dblIdEstatisticas);
  		$objAcompanhamentoOuvidoriaDTO->setArrGraficoGeral($arrGraficoAcompanhamentoGeral);
  		$objAcompanhamentoOuvidoriaDTO->setArrGraficoPorTipo($arrGraficoAcompanhamentoPorTipo);
  		
 		  return $objAcompanhamentoOuvidoriaDTO;
	  		 
	  }catch(Exception $e){
	    throw new InfraException('Erro gerando gráficos do acompanhamento de ouvidoria.',$e);
	  }
	}

	private function montarCriteriosAcompanhamento(AcompanhamentoOuvidoriaDTO $parObjAcompanhamentoOuvidoriaDTO, AcompanhamentoOuvidoriaDTO $objAcompanhamentoOuvidoriaDTO){

    $objAcompanhamentoOuvidoriaDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO,InfraDTO::$OPER_DIFERENTE);
    $objAcompanhamentoOuvidoriaDTO->setStrSinOuvidoriaTipoProcedimento('S');

    $objAcompanhamentoOuvidoriaDTO->setNumIdTipoProcedimentoProcedimento(InfraArray::converterArrInfraDTO($parObjAcompanhamentoOuvidoriaDTO->getArrObjTipoProcedimentoDTO(),'IdTipoProcedimento'),InfraDTO::$OPER_IN);

    $objUnidadeDTO = new UnidadeDTO();
    $objUnidadeDTO->retNumIdUnidade();
    $objUnidadeDTO->setStrSinOuvidoria('S');

    if ($parObjAcompanhamentoOuvidoriaDTO->getNumIdOrgaoUnidadeOrigem()!=null){
      $objUnidadeDTO->setNumIdOrgao($parObjAcompanhamentoOuvidoriaDTO->getNumIdOrgaoUnidadeOrigem());
    }

    $objUnidadeRN = new UnidadeRN();
    $arrObjUnidadeDTO = $objUnidadeRN->listarRN0127($objUnidadeDTO);

    if (count($arrObjUnidadeDTO)==0){
      throw new InfraException('Nenhuma unidade de ouvidoria encontrada.');
    }

    $objAcompanhamentoOuvidoriaDTO->setNumIdUnidadeOrigem(InfraArray::converterArrInfraDTO($arrObjUnidadeDTO, 'IdUnidade'), InfraDTO::$OPER_IN);

    $objAcompanhamentoOuvidoriaDTO->setNumIdTarefa(TarefaRN::$TI_GERACAO_PROCEDIMENTO);

    if ($parObjAcompanhamentoOuvidoriaDTO->getStrSinTramitacaoOuvidoria()=='N') {
      if ($parObjAcompanhamentoOuvidoriaDTO->getNumIdUnidade()!=null) {
        $objAcompanhamentoOuvidoriaDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE);
        $objAcompanhamentoOuvidoriaDTO->setNumIdUnidade($parObjAcompanhamentoOuvidoriaDTO->getNumIdUnidade());
      }
    }else{
      $objAcompanhamentoOuvidoriaDTO->setStrCriterioSqlNativo('not exists (select a.id_atividade from atividade a where a.id_protocolo=atividade.id_protocolo and a.id_tarefa='.TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE.')');
    }

    if (!InfraString::isBolVazia($parObjAcompanhamentoOuvidoriaDTO->getDtaInicio()) && !InfraString::isBolVazia($parObjAcompanhamentoOuvidoriaDTO->getDtaFim())) {
      $objAcompanhamentoOuvidoriaDTO->adicionarCriterio(array('Abertura','Abertura'),
          array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
          array($parObjAcompanhamentoOuvidoriaDTO->getDtaInicio().' 00:00:00', $parObjAcompanhamentoOuvidoriaDTO->getDtaFim().' 23:59:59'),
          InfraDTO::$OPER_LOGICO_AND);
    }

    if ($parObjAcompanhamentoOuvidoriaDTO->getStrStaOuvidoriaProcedimento()!=null){
      $objAcompanhamentoOuvidoriaDTO->setStrStaOuvidoriaProcedimento($parObjAcompanhamentoOuvidoriaDTO->getStrStaOuvidoriaProcedimento());
    }
  }

  public static function verificarAcessoInteressado(ProcedimentoDTO $objProcedimentoDTO){
     if ($objProcedimentoDTO->getStrSinOuvidoriaTipoProcedimento() == 'S' && $objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
       $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
       return ($objInfraParametro->getValor('SEI_ACESSO_FORMULARIO_OUVIDORIA')!='1');
     }
     return true;
  }

  protected function obterEmailRemetenteConectado(OrgaoDTO $objOrgaoDTO){
    try{

      $strDe = '';

      $objEmailSistemaDTO = new EmailSistemaDTO();
      $objEmailSistemaDTO->retStrDe();
      $objEmailSistemaDTO->setNumIdEmailSistema(EmailSistemaRN::$ES_CONTATO_OUVIDORIA);

      $objEmailSistemaRN = new EmailSistemaRN();
      $objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);

      if ($objEmailSistemaDTO!=null) {

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

        $strDe = $objEmailSistemaDTO->getStrDe();
        $strDe = str_replace('@sigla_sistema@', SessaoSEI::getInstance()->getStrSiglaSistema(), $strDe);
        $strDe = str_replace('@email_sistema@', $objInfraParametro->getValor('SEI_EMAIL_SISTEMA'), $strDe);
        $strDe = str_replace('@sigla_orgao@', $objOrgaoDTO->getStrSigla(), $strDe);
        $strDe = str_replace('@sigla_orgao_minusculas@', InfraString::transformarCaixaBaixa($objOrgaoDTO->getStrSigla()), $strDe);
        $strDe = str_replace('@sufixo_email@', $objInfraParametro->getValor('SEI_SUFIXO_EMAIL'), $strDe);
      }

      return $strDe;

      }catch(Exception $e){
      throw new InfraException('Erro obtendo email remetente da ouvidoria.', $e);
    }
  }
}
?>