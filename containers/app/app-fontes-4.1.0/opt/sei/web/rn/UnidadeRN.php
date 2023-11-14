<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/04/2008 - criado por mga
*
* Versão do Gerador de Código: 1.14.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class UnidadeRN extends InfraRN {
  
  public static $POS_UNIDADE_SIGLA = 0;
  public static $POS_UNIDADE_DESCRICAO = 1;
  public static $POS_UNIDADE_SUBUNIDADES = 2;
  public static $POS_UNIDADE_UNIDADES_SUPERIORES = 3;
  public static $POS_UNIDADE_ORGAO_ID = 4;

  public static $TS_ENVIO_PROCESSOS = 1;
  public static $TS_ENVIAR_EMAIL = 2;
  public static $TS_ARQUIVAMENTO = 3;
  public static $TS_PROTOCOLO = 4;
  public static $TS_OUVIDORIA = 5;

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public static function listarValoresSinalizacao(){
    try {

      $arrObjSinalizacaoDTO = array();

      $objSinalizacaoDTO = new SinalizacaoDTO();
      $objSinalizacaoDTO->setStrStaSinalizacao(self::$TS_ENVIO_PROCESSOS);
      $objSinalizacaoDTO->setStrDescricao('Disponível para envio de processos');
      $arrObjSinalizacaoDTO[] = $objSinalizacaoDTO;

      $objSinalizacaoDTO = new SinalizacaoDTO();
      $objSinalizacaoDTO->setStrStaSinalizacao(self::$TS_ENVIAR_EMAIL);
      $objSinalizacaoDTO->setStrDescricao('Enviar e-mail de aviso quando um processo for remetido para a unidade');
      $arrObjSinalizacaoDTO[] = $objSinalizacaoDTO;

      $objSinalizacaoDTO = new SinalizacaoDTO();
      $objSinalizacaoDTO->setStrStaSinalizacao(self::$TS_ARQUIVAMENTO);
      $objSinalizacaoDTO->setStrDescricao('Arquivamento');
      $arrObjSinalizacaoDTO[] = $objSinalizacaoDTO;

      $objSinalizacaoDTO = new SinalizacaoDTO();
      $objSinalizacaoDTO->setStrStaSinalizacao(self::$TS_PROTOCOLO);
      $objSinalizacaoDTO->setStrDescricao('Protocolo');
      $arrObjSinalizacaoDTO[] = $objSinalizacaoDTO;

      $objSinalizacaoDTO = new SinalizacaoDTO();
      $objSinalizacaoDTO->setStrStaSinalizacao(self::$TS_OUVIDORIA);
      $objSinalizacaoDTO->setStrDescricao('Ouvidoria');
      $arrObjSinalizacaoDTO[] = $objSinalizacaoDTO;

      return $arrObjSinalizacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Sinalização.',$e);
    }
  }

  protected function cadastrarRN0078Controlado(UnidadeDTO $objUnidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_cadastrar',__METHOD__,$objUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdOrgao($objUnidadeDTO, $objInfraException);
      $this->validarStrIdOrigem($objUnidadeDTO, $objInfraException);
      $this->validarStrSiglaRN0957($objUnidadeDTO, $objInfraException);
      $this->validarStrDescricaoRN0958($objUnidadeDTO, $objInfraException);
      $this->validarStrSinAtivoRN0538($objUnidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retNumIdContato();
      $objOrgaoDTO->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());

      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

      if ($objOrgaoDTO == null){
        throw new InfraException('Órgão ['.$objUnidadeDTO->getNumIdOrgao().'] não encontrado.');
      }

      $numIdTipoContato = $this->obterTipoContatoUnidades($objOrgaoDTO);

      $objContatoRN = new ContatoRN();

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setBolExclusaoLogica(false);
      $objContatoDTO->retStrSinAtivo();
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->setStrSigla($objUnidadeDTO->getStrSigla());
      $objContatoDTO->setStrNome($objUnidadeDTO->getStrDescricao());
      $objContatoDTO->setNumIdTipoContato($numIdTipoContato);
      $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);

      if ($objContatoDTO == null) {

        $objContatoDTO = new ContatoDTO();

        $objContatoDTO->setNumIdContato(null);
        $objContatoDTO->setNumIdTipoContato($numIdTipoContato);
        $objContatoDTO->setNumIdContatoAssociado($objOrgaoDTO->getNumIdContato());
        $objContatoDTO->setStrStaNatureza(ContatoRN::$TN_PESSOA_JURIDICA);
        $objContatoDTO->setStrSigla($objUnidadeDTO->getStrSigla());
        $objContatoDTO->setStrNome($objUnidadeDTO->getStrDescricao());
        $objContatoDTO->setStrSinEnderecoAssociado('N');
        $objContatoDTO->setStrSinAtivo('S');
        $objContatoDTO->setStrStaOperacao('REPLICACAO');
        $objContatoDTO = $objContatoRN->cadastrarRN0322($objContatoDTO);
      }else{
        if ($objContatoDTO->getStrSinAtivo()=='N'){
          $objContatoRN->reativarRN0452(array($objContatoDTO));
        }
      }

      $objUnidadeDTO->setNumIdContato($objContatoDTO->getNumIdContato());

      $objUnidadeDTO->setStrSinMailPendencia('N');
      $objUnidadeDTO->setStrSinArquivamento('N');
      $objUnidadeDTO->setStrSinOuvidoria('N');
      $objUnidadeDTO->setStrSinProtocolo('N');
      $objUnidadeDTO->setStrIdUnidadeFederacao(null);
      $objUnidadeDTO->setStrSinEnvioProcesso('S');
      $objUnidadeDTO->setStrCodigoSei(null);
      $objUnidadeDTO->setStrIdxUnidade(null);

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->cadastrar($objUnidadeDTO);

      $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
      $objUnidadeHistoricoDTO = new UnidadeHistoricoDTO();
      $objUnidadeHistoricoDTO->setNumIdUnidade($ret->getNumIdUnidade());
      $objUnidadeHistoricoDTO->setNumIdOrgao($ret->getNumIdOrgao());
      $objUnidadeHistoricoDTO->setStrSigla($ret->getStrSigla());
      $objUnidadeHistoricoDTO->setStrDescricao($ret->getStrDescricao());
      $objUnidadeHistoricoDTO->setDtaInicio(InfraData::getStrDataHoraAtual());
      $objUnidadeHistoricoDTO->setDtaFim(null);
      $objUnidadeHistoricoDTO->setBolOrigemSIP(true);
      $objUnidadeHistoricoRN->cadastrar($objUnidadeHistoricoDTO);

      $this->montarIndexacao($ret);

      /*
      //Auditoria
      $objAlertaRN = new AlertaRN();
      $objAlertaDTO= new AlertaDTO();
      $objAlertaDTO->setNumIdUnidade($ret->getNumIdUnidade());
      $objAlertaRN->cadastrar($objAlertaDTO);
      */

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Unidade.',$e);
    }
  }

  protected function alterarRN0132Controlado(UnidadeDTO $objUnidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('unidade_alterar',__METHOD__,$objUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objUnidadeDTOBanco = new UnidadeDTO();
      $objUnidadeDTOBanco->setBolExclusaoLogica(false);
      $objUnidadeDTOBanco->retStrIdUnidadeFederacao();
      $objUnidadeDTOBanco->retNumIdContato();
      $objUnidadeDTOBanco->retNumIdOrgao();
      $objUnidadeDTOBanco->retStrSigla();
      $objUnidadeDTOBanco->retStrDescricao();
      $objUnidadeDTOBanco->retStrSiglaOrgao();
      $objUnidadeDTOBanco->retStrSinOuvidoria();
      $objUnidadeDTOBanco->retStrIdUnidadeFederacao();
      $objUnidadeDTOBanco->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
      
      $objUnidadeDTOBanco = $this->consultarRN0125($objUnidadeDTOBanco);

      if($objUnidadeDTO->isSetNumIdContato() && $objUnidadeDTOBanco->getNumIdContato()!=$objUnidadeDTO->getNumIdContato()){
        $objInfraException->lancarValidacao('Não é possível alterar o contato associado.');
      }else{
        $objUnidadeDTO->setNumIdContato($objUnidadeDTOBanco->getNumIdContato());
      }

      if ($objUnidadeDTO->isSetStrIdUnidadeFederacao() && $objUnidadeDTOBanco->getStrIdUnidadeFederacao()!=$objUnidadeDTO->getStrIdUnidadeFederacao()){
        $objInfraException->lancarValidacao('Não é possível alterar o identificador da unidade no SEI Federação.');
      }else{
        $objUnidadeDTO->setStrIdUnidadeFederacao($objUnidadeDTOBanco->getStrIdUnidadeFederacao());
      }

      if ($objUnidadeDTO->isSetNumIdOrgao()){
        $this->validarNumIdOrgao($objUnidadeDTO, $objInfraException);
      }else{
        $objUnidadeDTO->setNumIdOrgao($objUnidadeDTOBanco->getNumIdOrgao());
      }

      if ($objUnidadeDTO->isSetStrSigla()){
        $this->validarStrSiglaRN0957($objUnidadeDTO, $objInfraException);
      }else{
        $objUnidadeDTO->setStrSigla($objUnidadeDTOBanco->getStrSigla());
      }
      
      if ($objUnidadeDTO->isSetStrDescricao()){  
        $this->validarStrDescricaoRN0958($objUnidadeDTO, $objInfraException);
      }else{
        $objUnidadeDTO->setStrDescricao($objUnidadeDTOBanco->getStrDescricao());
      }

      if ($objUnidadeDTO->isSetStrIdOrigem()) {
        $this->validarStrIdOrigem($objUnidadeDTO, $objInfraException);
      }

      if ($objUnidadeDTO->isSetStrSinMailPendencia()){
        $this->validarStrSinMailPendenciaRN1199($objUnidadeDTO, $objInfraException);
      }

      if ($objUnidadeDTO->isSetStrSinArquivamento()){
        $this->validarStrSinArquivamento($objUnidadeDTO, $objInfraException);
      }
      
      if ($objUnidadeDTO->isSetStrSinOuvidoria()){
        $this->validarStrSinOuvidoria($objUnidadeDTO, $objInfraException);
      }

      if ($objUnidadeDTO->isSetStrSinProtocolo()){
        $this->validarStrSinProtocolo($objUnidadeDTO, $objInfraException);
      }

      if ($objUnidadeDTO->isSetStrSinEnvioProcesso()){
        $this->validarStrSinEnvioProcesso($objUnidadeDTO, $objInfraException);
      }
      
      if ($objUnidadeDTO->isSetStrSinAtivo()){
        $objUnidadeDTO->unSetStrSinAtivo();
      }

      if ($objUnidadeDTO->isSetStrCodigoSei()){
        $this->validarStrCodigoSei($objUnidadeDTO, $objInfraException);
      }
      
      $objInfraException->lancarValidacoes();

      if ($objUnidadeDTO->isSetArrObjEmailUnidadeDTO()){
        
        //apagar os emails da unidade
        $objEmailUnidadeDTO = new EmailUnidadeDTO();
        $objEmailUnidadeDTO->retNumIdEmailUnidade();
        $objEmailUnidadeDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
        
        $objEmailUnidadeRN = new EmailUnidadeRN();
        $objEmailUnidadeRN->excluir($objEmailUnidadeRN->listar($objEmailUnidadeDTO));
        
        //cadastrar os novos
        $arrObjEmailUnidadeDTO = $objUnidadeDTO->getArrObjEmailUnidadeDTO();
        foreach($arrObjEmailUnidadeDTO as $objEmailUnidadeDTO){
          $objEmailUnidadeDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
          $objEmailUnidadeRN->cadastrar($objEmailUnidadeDTO);
        }
      }

      if ($objUnidadeDTO->isSetStrIdxUnidade()){
        $objUnidadeDTO->unSetStrIdxUnidade();
      }

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $objUnidadeBD->alterar($objUnidadeDTO);

      $this->montarIndexacao($objUnidadeDTO);

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setStrSigla($objUnidadeDTO->getStrSigla());
      $objContatoDTO->setStrNome($objUnidadeDTO->getStrDescricao());
      $objContatoDTO->setNumIdContato($objUnidadeDTO->getNumIdContato());

      if ($objUnidadeDTO->getNumIdOrgao()!=$objUnidadeDTOBanco->getNumIdOrgao()) {

        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->setBolExclusaoLogica(false);
        $objOrgaoDTO->retStrSigla();
        $objOrgaoDTO->retNumIdContato();
        $objOrgaoDTO->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());

        $objOrgaoRN = new OrgaoRN();
        $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

        if ($objOrgaoDTO == null) {
          throw new InfraException('Órgão não encontrado [' . $objUnidadeDTO->getNumIdOrgao() . '].');
        }

        $objContatoDTO->setNumIdContatoAssociado($objOrgaoDTO->getNumIdContato());
        $objContatoDTO->setNumIdTipoContato($this->obterTipoContatoUnidades($objOrgaoDTO));
      }

      $objContatoDTO->setStrStaOperacao('REPLICACAO');

      $objContatoRN = new ContatoRN();
      $objContatoRN->alterarRN0323($objContatoDTO);

      $this->tratarHistoricoUnidade($objUnidadeDTO, $objUnidadeDTOBanco);

      if ($objUnidadeDTO->getStrIdUnidadeFederacao()!=null){
        $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
        $objUnidadeFederacaoDTO->setStrSigla($objUnidadeDTO->getStrSigla());
        $objUnidadeFederacaoDTO->setStrDescricao($objUnidadeDTO->getStrDescricao());
        $objUnidadeFederacaoDTO->setStrIdUnidadeFederacao($objUnidadeDTO->getStrIdUnidadeFederacao());

        $objUnidadeFederacaoRN = new UnidadeFederacaoRN();
        $objUnidadeFederacaoRN->alterar($objUnidadeFederacaoDTO);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Unidade.',$e);
    }
  }

  protected function excluirRN0126Controlado($arrObjUnidadeDTO){
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_excluir',__METHOD__,$arrObjUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objGrupoContatoRN = new GrupoContatoRN();
      $objGrupoContatoDTO = new GrupoContatoDTO();
      for ($i=0;$i<count($arrObjUnidadeDTO);$i++){
      	$objGrupoContatoDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
      	if ($objGrupoContatoRN->contarRN0145($objGrupoContatoDTO)>0){
      		$objInfraException->lancarValidacao('Existem grupos utilizando esta unidade.');
      	}
      }

      $objAtividadeRN = new AtividadeRN();
      $objBlocoRN = new BlocoRN();
      $objRelBlocoUnidadeRN = new RelBlocoUnidadeRN();
      $objRelControleInternoUnidadeRN = new RelControleInternoUnidadeRN();
      $objPublicacaoLegadoRN = new PublicacaoLegadoRN();
      
      foreach($arrObjUnidadeDTO as $objUnidadeDTO){

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
        $objAtividadeDTO->retNumIdAtividade();
        $objAtividadeDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
        if ($objAtividadeRN->consultarRN0033($objAtividadeDTO)!=null){
          $objInfraException->lancarValidacao('Unidade já participou na tramitação de processos.');
        }

        $objRelControleInternoUnidadeDTO = new RelControleInternoUnidadeDTO();
        $objRelControleInternoUnidadeDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
        
        if ($objRelControleInternoUnidadeRN->contar($objRelControleInternoUnidadeDTO)){
          $objInfraException->lancarValidacao('Unidade faz parte de um Critério de Controle Interno.');
        }
        
        $objPublicacaoLegadoDTO = new PublicacaoLegadoDTO();
        $objPublicacaoLegadoDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
        
        if ($objPublicacaoLegadoRN->contar($objPublicacaoLegadoDTO)){
          $objInfraException->lancarValidacao('Existem publicações legadas associadas.');
        }
        
      }
      
      
      $objInfraException->lancarValidacoes();

      $objOperacaoServicoRN = new OperacaoServicoRN();
      $objAlertaRN = new AlertaRN();
      $objNumeracaoRN = new NumeracaoRN();
      $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
      $objEmailUnidadeRN = new EmailUnidadeRN();

      for($i=0;$i<count($arrObjUnidadeDTO);$i++){
      	
	      $objOperacaoServicoDTO = new OperacaoServicoDTO();
	      $objOperacaoServicoDTO->retNumIdOperacaoServico();
	      $objOperacaoServicoDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
	      $objOperacaoServicoRN->excluir($objOperacaoServicoRN->listar($objOperacaoServicoDTO));
      
	      /*
	      $objAlertaDTO = new AlertaDTO();
			  $objAlertaDTO->retNumIdAlerta();
			  $objAlertaDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
			  $objAlertaRN->excluir($objAlertaRN->listar($objAlertaDTO));
			  */
			  
	      $objNumeracaoDTO = new NumeracaoDTO();
	      $objNumeracaoDTO->retNumIdNumeracao();
	      $objNumeracaoDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
	      $objNumeracaoRN->excluir($objNumeracaoRN->listar($objNumeracaoDTO));

        $objUnidadeHistoricoDTO = new UnidadeHistoricoDTO();
        $objUnidadeHistoricoDTO->retNumIdUnidadeHistorico();
        $objUnidadeHistoricoDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
        $arrObjUnidadeHistoricoDTO = $objUnidadeHistoricoRN->listar($objUnidadeHistoricoDTO);
        foreach ($arrObjUnidadeHistoricoDTO as $objUnidadeHistoricoDTO_Lista){
          $objUnidadeHistoricoDTO_Lista->setBolOrigemSIP(true);
        }
        $objUnidadeHistoricoRN->excluir($arrObjUnidadeHistoricoDTO);

        $objEmailUnidadeDTO = new EmailUnidadeDTO();
        $objEmailUnidadeDTO->retNumIdEmailUnidade();
        $objEmailUnidadeDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
        $objEmailUnidadeRN->excluir($objEmailUnidadeRN->listar($objEmailUnidadeDTO));

      }

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->setBolExclusaoLogica(false);
      $objUnidadeDTO->retNumIdUnidade();
      $objUnidadeDTO->retNumIdContato();
      $objUnidadeDTO->retStrSigla();
      $objUnidadeDTO->retStrDescricao();
      $objUnidadeDTO->retStrSinAtivo();
      $objUnidadeDTO->setNumIdUnidade(InfraArray::converterArrInfraDTO($arrObjUnidadeDTO,'IdUnidade'),InfraDTO::$OPER_IN);

      $arrObjUnidadeDTOConsulta = $this->listarRN0127($objUnidadeDTO);

      $arrObjUnidadeAPI = array();
      foreach($arrObjUnidadeDTOConsulta as $objUnidadeDTO){
        $objUnidadeAPI = new UnidadeAPI();
        $objUnidadeAPI->setIdUnidade($objUnidadeDTO->getNumIdUnidade());
        $objUnidadeAPI->setSigla($objUnidadeDTO->getStrSigla());
        $objUnidadeAPI->setDescricao($objUnidadeDTO->getStrDescricao());
        $arrObjUnidadeAPI[] = $objUnidadeAPI;
      }

      foreach($SEI_MODULOS as $seiModulo){
        $seiModulo->executar('excluirUnidade', $arrObjUnidadeAPI);
      }

      $arrNumIdContato = InfraArray::converterArrInfraDTO($arrObjUnidadeDTOConsulta,'IdContato');

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      
      for($i=0;$i<count($arrObjUnidadeDTO);$i++){
        $objUnidadeBD->excluir($arrObjUnidadeDTO[$i]);
      }

      $objContatoRN = new ContatoRN();
      foreach($arrNumIdContato as $numIdContato){
        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->setNumIdContato($numIdContato);
        try{
          $objContatoRN->excluirRN0326(array($objContatoDTO));
        }catch(Exception $e){
          $objContatoRN->desativarRN0451(array($objContatoDTO));
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Unidade.',$e);
    }
  }

  protected function consultarRN0125Conectado(UnidadeDTO $objUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_consultar',__METHOD__,$objUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->consultar($objUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Unidade.',$e);
    }
  }
  
  protected function listarRN0127Conectado(UnidadeDTO $objUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_listar',__METHOD__,$objUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
      
      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->listar($objUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Unidades.',$e);
    }
  }

  protected function contarRN0128Conectado(UnidadeDTO $objUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_listar',__METHOD__,$objUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->contar($objUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Unidades.',$e);
    }
  }

  protected function desativarRN0484Controlado($arrObjUnidadeDTO){
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_desativar',__METHOD__,$arrObjUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $arrIdUnidade = InfraArray::converterArrInfraDTO($arrObjUnidadeDTO,'IdUnidade');

      if (count($arrIdUnidade)) {
        //remove retornos programados pendentes
        $objRetornoProgramadoDTO = new RetornoProgramadoDTO();
        $objRetornoProgramadoDTO->retNumIdRetornoProgramado();
        $objRetornoProgramadoDTO->setNumIdUnidadeEnvio($arrIdUnidade, InfraDTO::$OPER_IN);
        $objRetornoProgramadoDTO->setNumIdAtividadeRetorno(null);

        $objRetornoProgramadoRN = new RetornoProgramadoRN();
        $objRetornoProgramadoRN->excluir($objRetornoProgramadoRN->listar($objRetornoProgramadoDTO));

        //remove unidade dos gruopos de envio
        $objRelGrupoUnidadeUnidadeDTO = new RelGrupoUnidadeUnidadeDTO();
        $objRelGrupoUnidadeUnidadeDTO->retNumIdGrupoUnidade();
        $objRelGrupoUnidadeUnidadeDTO->retNumIdUnidade();
        $objRelGrupoUnidadeUnidadeDTO->setNumIdUnidade($arrIdUnidade, InfraDTO::$OPER_IN);;

        $objRelGrupoUnidadeUnidadeRN = new RelGrupoUnidadeUnidadeRN();
        $objRelGrupoUnidadeUnidadeRN->excluir($objRelGrupoUnidadeUnidadeRN->listar($objRelGrupoUnidadeUnidadeDTO));

        $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjUnidadeDTO); $i++) {
          $objUnidadeBD->desativar($arrObjUnidadeDTO[$i]);
        }

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->retNumIdContato();
        $objUnidadeDTO->setNumIdUnidade($arrIdUnidade, InfraDTO::$OPER_IN);
        $objContatoRN = new ContatoRN();
        $objContatoRN->desativarRN0451(InfraArray::gerarArrInfraDTO('ContatoDTO','IdContato',InfraArray::converterArrInfraDTO($this->listarRN0127($objUnidadeDTO),'IdContato')));

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->retNumIdContato();
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrDescricao();
        $objUnidadeDTO->retStrSinAtivo();
        $objUnidadeDTO->setNumIdUnidade($arrIdUnidade,InfraDTO::$OPER_IN);

        $arrObjUnidadeDTOConsulta = $this->listarRN0127($objUnidadeDTO);

        $arrObjUnidadeAPI = array();
        foreach($arrObjUnidadeDTOConsulta as $objUnidadeDTO){
          $objUnidadeAPI = new UnidadeAPI();
          $objUnidadeAPI->setIdUnidade($objUnidadeDTO->getNumIdUnidade());
          $objUnidadeAPI->setSigla($objUnidadeDTO->getStrSigla());
          $objUnidadeAPI->setDescricao($objUnidadeDTO->getStrDescricao());
          $arrObjUnidadeAPI[] = $objUnidadeAPI;
        }

        foreach($SEI_MODULOS as $seiModulo){
          $seiModulo->executar('desativarUnidade', $arrObjUnidadeAPI);
        }
      }
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Unidade.',$e);
    }
  }

  protected function reativarRN0485Controlado($arrObjUnidadeDTO){
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_reativar',__METHOD__,$arrObjUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $arrIdUnidade = InfraArray::converterArrInfraDTO($arrObjUnidadeDTO,'IdUnidade');

      if (count($arrIdUnidade)) {

        $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjUnidadeDTO); $i++) {
          $objUnidadeBD->reativar($arrObjUnidadeDTO[$i]);
        }

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->retNumIdContato();
        $objUnidadeDTO->setNumIdUnidade($arrIdUnidade, InfraDTO::$OPER_IN);
        $objContatoRN = new ContatoRN();
        $objContatoRN->reativarRN0452(InfraArray::gerarArrInfraDTO('ContatoDTO', 'IdContato', InfraArray::converterArrInfraDTO($this->listarRN0127($objUnidadeDTO), 'IdContato')));

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->retNumIdContato();
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrDescricao();
        $objUnidadeDTO->retStrSinAtivo();
        $objUnidadeDTO->setNumIdUnidade($arrIdUnidade, InfraDTO::$OPER_IN);

        $arrObjUnidadeDTOConsulta = $this->listarRN0127($objUnidadeDTO);

        $arrObjUnidadeAPI = array();
        foreach ($arrObjUnidadeDTOConsulta as $objUnidadeDTO) {
          $objUnidadeAPI = new UnidadeAPI();
          $objUnidadeAPI->setIdUnidade($objUnidadeDTO->getNumIdUnidade());
          $objUnidadeAPI->setSigla($objUnidadeDTO->getStrSigla());
          $objUnidadeAPI->setDescricao($objUnidadeDTO->getStrDescricao());
          $arrObjUnidadeAPI[] = $objUnidadeAPI;
        }

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('reativarUnidade', $arrObjUnidadeAPI);
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Unidade.',$e);
    }
  }

  private function validarNumIdOrgao(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeDTO->getNumIdOrgao())){    
      $objInfraException->adicionarValidacao('Órgão da unidade não informado.');
    }
  }

  private function validarStrSinMailPendenciaRN1199(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objUnidadeDTO->getStrSinMailPendencia())){
    		$objInfraException->adicionarValidacao('Sinalizador de envio de email no recebimento de pendência não informado.');
  	}else{
      if (!InfraUtil::isBolSinalizadorValido($objUnidadeDTO->getStrSinMailPendencia())){
        $objInfraException->adicionarValidacao('Sinalizador de envio de email no recebimento de pendência inválido.');
      }
      
      if($objUnidadeDTO->getStrSinMailPendencia()=='S' && InfraArray::contar($objUnidadeDTO->getArrObjEmailUnidadeDTO()) < 1){
      	$objInfraException->adicionarValidacao('Não é possível realizar o envio automático de email porque nenhum endereço eletrônico foi cadastrado para a unidade.');      	
      }
  	}
  	/*
  	if ($objUnidadeDTO->getStrSinMailPendencia()=='S'){
    	$objContatoDTO = new Unidade();
    	$objUnidadeDTO->retStrEmail();
    	//$objContatoDTO->setNumIdContato($objUnidadeDTO->getNumIdContato());
    	
    	//$objContatoRN = new ContatoRN();
    	//$objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);
    	
    	if (InfraString::isBolVazia($objContatoDTO->getStrEmail())){
    	  $objInfraException->adicionarValidacao('Contexto da unidade não possui email cadastrado.');
    	}else if (!InfraUtil::validarEmail($objContatoDTO->getStrEmail())){
    	  $objInfraException->adicionarValidacao('E-mail do contexto da unidade inválido.');
    	}
  	}*/
  }

  private function validarStrSinArquivamento(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objUnidadeDTO->getStrSinArquivamento())){
    		$objInfraException->adicionarValidacao('Sinalizador de unidade de arquivamento não informado.');
  	}else{
      if (!InfraUtil::isBolSinalizadorValido($objUnidadeDTO->getStrSinArquivamento())){
        $objInfraException->adicionarValidacao('Sinalizador de unidade de arquivamento inválido.');
      }
  	}
  	
  	/*
  	//so permitir uma unidade de arquivamento no orgao
    if ($objUnidadeDTO->getStrSinArquivamento()=='S'){
    	$unidade=new UnidadeDTO();
	    $unidade->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade(),InfraDTO::$OPER_DIFERENTE);
	    $unidade->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());
	    $unidade->setStrSinArquivamento('S');
	    $unidade->retStrDescricao();
	    $unidade->retStrSigla();
	    $unidade=$this->consultarRN0125Conectado($unidade);
	    if (!is_null($unidade)) {
	    	$strUnidade=$unidade->getStrSigla().' - '.$unidade->getStrDescricao();
	    	$objInfraException->adicionarValidacao('Já existe uma unidade de arquivamento configurada neste órgão ('.$strUnidade.').');
	    }
	  }
	  */
  }
  
  private function validarStrSinOuvidoria(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objUnidadeDTO->getStrSinOuvidoria())){
    		$objInfraException->adicionarValidacao('Sinalizador de unidade de ouvidoria não informado.');
  	}else{
      if (!InfraUtil::isBolSinalizadorValido($objUnidadeDTO->getStrSinOuvidoria())){
        $objInfraException->adicionarValidacao('Sinalizador de unidade de ouvidoria inválido.');
      }
  	}
  	//so permitir uma unidade de ouvidoria no orgao
    if ($objUnidadeDTO->getStrSinOuvidoria()=='S'){
      $objUnidadeDTOBanco = new UnidadeDTO();
      $objUnidadeDTOBanco->setBolExclusaoLogica(false);
      $objUnidadeDTOBanco->retStrSigla();
      $objUnidadeDTOBanco->retStrSiglaOrgao();
      $objUnidadeDTOBanco->retStrSinAtivo();
	    $objUnidadeDTOBanco->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade(),InfraDTO::$OPER_DIFERENTE);
	    $objUnidadeDTOBanco->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());
	    $objUnidadeDTOBanco->setStrSinOuvidoria('S');

      $arrObjUnidadeDTOBanco = $this->listarRN0127($objUnidadeDTOBanco);

      foreach($arrObjUnidadeDTOBanco as $objUnidadeDTOBanco) {
         $objInfraException->adicionarValidacao('A unidade '.($objUnidadeDTOBanco->getStrSinAtivo()=='N'?'desativada':'').' '.$objUnidadeDTOBanco->getStrSigla().' já está configurada como ouvidoria no órgão '.$objUnidadeDTOBanco->getStrSiglaOrgao().'.');
      }
	  }
  }

  private function validarStrSinProtocolo(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeDTO->getStrSinProtocolo())){
      $objInfraException->adicionarValidacao('Sinalizador de unidade de protocolo não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objUnidadeDTO->getStrSinProtocolo())){
        $objInfraException->adicionarValidacao('Sinalizador de unidade de protocolo inválido.');
      }
    }
  }

  private function validarStrSinEnvioProcesso(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objUnidadeDTO->getStrSinEnvioProcesso())){
    		$objInfraException->adicionarValidacao('Sinalizador de unidade disponível para envio de processo não informado.');
  	}else{
      if (!InfraUtil::isBolSinalizadorValido($objUnidadeDTO->getStrSinEnvioProcesso())){
        $objInfraException->adicionarValidacao('Sinalizador de unidade disponível para envio de processo inválido.');
      }
  	}
  }
  
  private function validarStrSinAtivoRN0538(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objUnidadeDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  private function validarStrSiglaRN0957(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }else{
      $objUnidadeDTO->setStrSigla(trim($objUnidadeDTO->getStrSigla()));
  
      if (strlen($objUnidadeDTO->getStrSigla())>30){
        $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 30 caracteres.');
        return;
      }
      
      $dto = new UnidadeDTO();
      $dto->retNumIdUnidade();
      $dto->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrSigla($objUnidadeDTO->getStrSigla());
      $dto = $this->consultarRN0125($dto);
      if ($dto!=null){
      	$objInfraException->adicionarValidacao('Existe outra unidade que utiliza a mesma Sigla.');
      	return;
      }    
    }
  }

  private function validarStrDescricaoRN0958(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objUnidadeDTO->setStrDescricao(trim($objUnidadeDTO->getStrDescricao()));
  
      if (strlen($objUnidadeDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }
  
  private function validarStrCodigoSei(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeDTO->getStrCodigoSei())){
      $objUnidadeDTO->setStrCodigoSei(null);
    }else{
      $objUnidadeDTO->setStrCodigoSei(trim($objUnidadeDTO->getStrCodigoSei()));
  
      if (strlen($objUnidadeDTO->getStrCodigoSei())>10){
        $objInfraException->adicionarValidacao('Código SEI possui tamanho superior a 10 caracteres.');
      }
    }
  }

  private function validarStrIdxUnidade(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUnidadeDTO->getStrIdxUnidade())){
      $objUnidadeDTO->setStrIdxUnidade(null);
    }else{
      $objUnidadeDTO->setStrIdxUnidade(trim($objUnidadeDTO->getStrIdxUnidade()));

      if (strlen($objUnidadeDTO->getStrIdxUnidade())>500){
        $objInfraException->adicionarValidacao('Indexação possui tamanho superior a 500 caracteres.');
      }
    }
  }

  private function validarStrIdOrigem(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException){
    //
  }

  protected function listarTodasComFiltroConectado(UnidadeDTO $objUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_listar',__METHOD__,$objUnidadeDTO);

      $objUnidadeDTO = InfraString::tratarPalavrasPesquisaDTO($objUnidadeDTO,'Sigla');
      $objUnidadeDTO = InfraString::tratarPalavrasPesquisaDTO($objUnidadeDTO,'Descricao');
      $objUnidadeDTO = InfraString::prepararPesquisaDTO($objUnidadeDTO,'PalavrasPesquisa','IdxUnidade');

      if(!$objUnidadeDTO->isSetNumIdOrgao()){
  		  $objUnidadeDTO->setStrSinEnvioProcessoOrgao('S');  		  
      }

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->listar($objUnidadeDTO);
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando todas as Unidades com filtro.',$e);
    }
  }

  protected function listarOutrasComFiltroConectado(UnidadeDTO $objUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_listar',__METHOD__,$objUnidadeDTO);

      $objUnidadeDTO = InfraString::tratarPalavrasPesquisaDTO($objUnidadeDTO,'Sigla');
      $objUnidadeDTO = InfraString::tratarPalavrasPesquisaDTO($objUnidadeDTO,'Descricao');
      $objUnidadeDTO = InfraString::prepararPesquisaDTO($objUnidadeDTO,'PalavrasPesquisa','IdxUnidade');

      if(!$objUnidadeDTO->isSetNumIdOrgao()){
      	$objUnidadeDTO->setStrSinEnvioProcessoOrgao('S'); 
      }

      $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), InfraDTO::$OPER_DIFERENTE);
                                            
      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->listar($objUnidadeDTO);
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando outras Unidades com filtro.',$e);
    }
  }

  protected function listarEnvioProcessoConectado(UnidadeDTO $objUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_listar',__METHOD__,$objUnidadeDTO);

      $objUnidadeDTO = InfraString::tratarPalavrasPesquisaDTO($objUnidadeDTO,'Sigla');
      $objUnidadeDTO = InfraString::tratarPalavrasPesquisaDTO($objUnidadeDTO,'Descricao');
      $objUnidadeDTO = InfraString::prepararPesquisaDTO($objUnidadeDTO,'PalavrasPesquisa','IdxUnidade');

    	$objUnidadeDTO->setStrSinEnvioProcessoOrgao('S'); 
      $objUnidadeDTO->setStrSinEnvioProcesso('S');

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->listar($objUnidadeDTO);
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando unidades para envio de processo.',$e);
    }
  }

  protected function listarReaberturaProcessoConectado(ProcedimentoDTO $objProcedimentoDTO) {
    try {
  
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_listar',__METHOD__,$objProcedimentoDTO);
  
      //Regras de Negocio
      //$objInfraException = new InfraException();
  
      //$objInfraException->lancarValidacoes();

      $ret = array();
      
      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setDistinct(true);
      $objAtividadeDTO->retNumIdUnidade();
      $objAtividadeDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO, InfraDTO::$OPER_DIFERENTE);
      $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
      $objAtividadeDTO->setNumIdTarefa(array(TarefaRN::$TI_GERACAO_PROCEDIMENTO, TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE), InfraDTO::$OPER_IN);
      
      $objAtividadeRN = new AtividadeRN();
      $arrIdUnidadeTramitacao = InfraArray::converterArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO),'IdUnidade');
      
      if (count($arrIdUnidadeTramitacao)){
      
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrDescricao();
        $objUnidadeDTO->retStrSiglaOrgao();
        $objUnidadeDTO->retStrDescricaoOrgao();
        $objUnidadeDTO->setNumIdUnidade($arrIdUnidadeTramitacao,InfraDTO::$OPER_IN);
        $objUnidadeDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

        $ret = $this->listarRN0127($objUnidadeDTO);
        
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDistinct(true);
        $objAtividadeDTO->retNumIdUnidade();
        $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade($arrIdUnidadeTramitacao, InfraDTO::$OPER_IN);
        $objAtividadeDTO->setDthConclusao(null);
        
        $arrIdUnidadeAberto = InfraArray::converterArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO),'IdUnidade');
        
        foreach($ret as $objUnidadeDTO){
          if (in_array($objUnidadeDTO->getNumIdUnidade(),$arrIdUnidadeAberto)){
            $objUnidadeDTO->setStrSinProcessoAberto('S');
          }else{
            $objUnidadeDTO->setStrSinProcessoAberto('N');
          }
        }
      }

  		//Auditoria

  		return $ret;
  
    }catch(Exception $e){
      throw new InfraException('Erro listando unidades para reabertura de processo.',$e);
    }
  }

  protected function bloquearControlado(UnidadeDTO $objUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_consultar',__METHOD__,$objUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->bloquear($objUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Unidade.',$e);
    }
  }

  protected function pesquisarConectado(UnidadeDTO $objUnidadeDTO) {
    try {

       //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_listar',__METHOD__,$objUnidadeDTO);

      $objUnidadeDTO = InfraString::tratarPalavrasPesquisaDTO($objUnidadeDTO,"Sigla");
      $objUnidadeDTO = InfraString::tratarPalavrasPesquisaDTO($objUnidadeDTO,"Descricao");
      $objUnidadeDTO = InfraString::prepararPesquisaDTO($objUnidadeDTO,"PalavrasPesquisa","IdxUnidade");

  		return $this->listarRN0127($objUnidadeDTO);

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando Unidades.',$e);
    }
  }

  public function obterDadosHierarquia(UnidadeDTO $objUnidadeDTO){
    try {

      $strChaveHierarquia = 'SEI_H_'.CacheSEI::getInstance()->getAtributoVersao('SEI_H').'_'.$objUnidadeDTO->getNumIdUnidade();

      $arrDadosHierarquia = CacheSEI::getInstance()->getAtributo($strChaveHierarquia);

      if ($arrDadosHierarquia == null) {

        $objInfraSip = new InfraSip(SessaoSEI::getInstance());
        $ret = $objInfraSip->carregarUnidades(SessaoSEI::getInstance()->getNumIdSistema(),null,$objUnidadeDTO->getNumIdUnidade());

        $arrUnidadesSip = array();

        foreach ($ret as $uni) {
          $numIdUnidade = $uni[InfraSip::$WS_UNIDADE_ID];
          $arrUnidadesSip[$numIdUnidade] = array();
          //$arrUnidadesSip[$numIdUnidade][self::$POS_UNIDADE_ORGAO_ID] = $uni[InfraSip::$WS_UNIDADE_ORGAO_ID];
          $arrUnidadesSip[$numIdUnidade][self::$POS_UNIDADE_SIGLA] = $uni[InfraSip::$WS_UNIDADE_SIGLA];
          $arrUnidadesSip[$numIdUnidade][self::$POS_UNIDADE_DESCRICAO] = $uni[InfraSip::$WS_UNIDADE_DESCRICAO];
          //$arrUnidadesSip[$numIdUnidade][self::$POS_UNIDADE_SUBUNIDADES] = $uni[InfraSip::$WS_UNIDADE_SUBUNIDADES];
          $arrUnidadesSip[$numIdUnidade][self::$POS_UNIDADE_UNIDADES_SUPERIORES] = $uni[InfraSip::$WS_UNIDADE_UNIDADES_SUPERIORES];
        }

        if (!isset($arrUnidadesSip[$objUnidadeDTO->getNumIdUnidade()])) {
          throw new InfraException('Unidade não encontrada no Sistema de Permissões.');
        }

        $arrUnidadesSuperiores = $arrUnidadesSip[$objUnidadeDTO->getNumIdUnidade()][self::$POS_UNIDADE_UNIDADES_SUPERIORES];
        $numUnidadesSuperiores = InfraArray::contar($arrUnidadesSuperiores);

        if ($numUnidadesSuperiores) {
          $strHierarquiaUnidadeSuperiorSigla = $arrUnidadesSip[$arrUnidadesSuperiores[$numUnidadesSuperiores - 1]][self::$POS_UNIDADE_SIGLA];
          $strHierarquiaUnidadeSuperiorDescricao = $arrUnidadesSip[$arrUnidadesSuperiores[$numUnidadesSuperiores - 1]][self::$POS_UNIDADE_DESCRICAO];
        }else{
          $strHierarquiaUnidadeSuperiorSigla = '';
          $strHierarquiaUnidadeSuperiorDescricao = '';
        }

        $arrUnidadesSuperiores[] = $objUnidadeDTO->getNumIdUnidade();

        $strHierarquiaUnidadeRaizSigla = $arrUnidadesSip[$arrUnidadesSuperiores[0]][self::$POS_UNIDADE_SIGLA];
        $strHierarquiaUnidadeRaizDescricao = $arrUnidadesSip[$arrUnidadesSuperiores[0]][self::$POS_UNIDADE_DESCRICAO];

        $strHierarquiaUnidade = '';
        $strHierarquiaDescricao = '';
        foreach ($arrUnidadesSuperiores as $numIdUnidadeSuperior) {
          if ($strHierarquiaUnidade != '') {
            $strHierarquiaUnidade .= '/';
          }
          $strHierarquiaUnidade .= $arrUnidadesSip[$numIdUnidadeSuperior][UnidadeRN::$POS_UNIDADE_SIGLA];

          if ($strHierarquiaDescricao != '') {
            $strHierarquiaDescricao .= '<br />';
          }
          $strHierarquiaDescricao .= $arrUnidadesSip[$numIdUnidadeSuperior][UnidadeRN::$POS_UNIDADE_DESCRICAO];
        }

        $arrDadosHierarquia = array();
        $arrDadosHierarquia['RAMIFICACAO'] = $strHierarquiaUnidade;
        $arrDadosHierarquia['DESCRICAO'] = $strHierarquiaDescricao;
        $arrDadosHierarquia['RAIZ_SIGLA'] = $strHierarquiaUnidadeRaizSigla;
        $arrDadosHierarquia['RAIZ_DESCRICAO'] = $strHierarquiaUnidadeRaizDescricao;
        $arrDadosHierarquia['SUPERIOR_SIGLA'] = $strHierarquiaUnidadeSuperiorSigla;
        $arrDadosHierarquia['SUPERIOR_DESCRICAO'] = $strHierarquiaUnidadeSuperiorDescricao;

        CacheSEI::getInstance()->setAtributo($strChaveHierarquia, $arrDadosHierarquia, CacheSEI::getInstance()->getNumTempo());
      }

      return $arrDadosHierarquia;

    }catch(Exception $e){
      throw new InfraException('Erro obtendo hierarquia da unidade.',$e);
    }
  }

  protected function migrarControlado(MigracaoUnidadeDTO $objMigracaoUnidadeDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('unidade_migrar', __METHOD__, $objMigracaoUnidadeDTO);

      LimiteSEI::getInstance()->configurarNivel2();

      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem())){
        $objInfraException->lancarValidacao('Unidade de Origem não informada.');
      }

      if (InfraString::isBolVazia($objMigracaoUnidadeDTO->getNumIdUnidadeDestino())){
        $objInfraException->lancarValidacao('Unidade de Destino não informada.');
      }

      $objMigracaoUnidadeDTO->setStrPrefixoMigracao(trim($objMigracaoUnidadeDTO->getStrPrefixoMigracao()));

      if (strlen($objMigracaoUnidadeDTO->getStrPrefixoMigracao())>50){
        $objInfraException->lancarValidacao('Prefixo de Migração possui tamanho superior a 50 caracteres.');
      }

      if ($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem() == $objMigracaoUnidadeDTO->getNumIdUnidadeDestino()){
        $objInfraException->lancarValidacao('Unidade de Destino deve ser diferente da Unidade de Origem.');
      }

      if (!InfraUtil::isBolSinalizadorValido($objMigracaoUnidadeDTO->getStrSinAcompanhamentoEspecial())){
        $objInfraException->lancarValidacao('Sinalizador de migração de Acompanhamentos Especiais inválido.');
      }

      if (!InfraUtil::isBolSinalizadorValido($objMigracaoUnidadeDTO->getStrSinAssinatura())){
        $objInfraException->lancarValidacao('Sinalizador de migração de Assinaturas da Unidade inválido.');
      }

      if (!InfraUtil::isBolSinalizadorValido($objMigracaoUnidadeDTO->getStrSinBlocoInterno())){
        $objInfraException->lancarValidacao('Sinalizador de migração de Blocos Internos inválido.');
      }

      if (!InfraUtil::isBolSinalizadorValido($objMigracaoUnidadeDTO->getStrSinGrupoBloco())){
        $objInfraException->lancarValidacao('Sinalizador de migração de Grupos de Blocos inválido.');
      }

      if (!InfraUtil::isBolSinalizadorValido($objMigracaoUnidadeDTO->getStrSinGrupoEmail())){
        $objInfraException->lancarValidacao('Sinalizador de migração de Grupos de E-mail inválido.');
      }

      if (!InfraUtil::isBolSinalizadorValido($objMigracaoUnidadeDTO->getStrSinGrupoContato())){
        $objInfraException->lancarValidacao('Sinalizador de migração de Grupos de Contatos inválido.');
      }

      if (!InfraUtil::isBolSinalizadorValido($objMigracaoUnidadeDTO->getStrSinGrupoUnidade())){
        $objInfraException->lancarValidacao('Sinalizador de migração de Grupos de Unidades inválido.');
      }

      if (!InfraUtil::isBolSinalizadorValido($objMigracaoUnidadeDTO->getStrSinMarcadores())){
        $objInfraException->lancarValidacao('Sinalizador de migração de Marcadores inválido.');
      }

      if (!InfraUtil::isBolSinalizadorValido($objMigracaoUnidadeDTO->getStrSinModelo())){
        $objInfraException->lancarValidacao('Sinalizador de migração de Favoritos inválido.');
      }

      if (!InfraUtil::isBolSinalizadorValido($objMigracaoUnidadeDTO->getStrSinTextoPadrao())){
        $objInfraException->lancarValidacao('Sinalizador de migração de Textos Padrão inválido.');
      }

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $objUnidadeBD->migrar($objMigracaoUnidadeDTO);

    }catch(Exception $e){
      throw new InfraException('Erro migrando unidade.',$e);
    }
  }

  protected function montarIndexacaoControlado(UnidadeDTO $parObjUnidadeDTO){
    try{

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->retNumIdUnidade();
      $objUnidadeDTO->retStrSigla();
      $objUnidadeDTO->retStrDescricao();
      $objUnidadeDTO->setBolExclusaoLogica(false);

      if (is_array($parObjUnidadeDTO->getNumIdUnidade())){
        $objUnidadeDTO->setNumIdUnidade($parObjUnidadeDTO->getNumIdUnidade(),InfraDTO::$OPER_IN);
      }else{
        $objUnidadeDTO->setNumIdUnidade($parObjUnidadeDTO->getNumIdUnidade());
      }

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $objUnidadeDTOIdx = new UnidadeDTO();
      $objInfraException = new InfraException();

      $arrObjUnidadeDTO = $this->listarRN0127($objUnidadeDTO);

      foreach($arrObjUnidadeDTO as $objUnidadeDTO) {

        $objUnidadeDTOIdx->setStrIdxUnidade(InfraString::prepararIndexacao($objUnidadeDTO->getStrSigla().' '.$objUnidadeDTO->getStrDescricao()));
        $objUnidadeDTOIdx->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());

        $this->validarStrIdxUnidade($objUnidadeDTOIdx, $objInfraException);
        $objInfraException->lancarValidacoes();

        $objUnidadeBD->alterar($objUnidadeDTOIdx);
      }

    }catch(Exception $e){
      throw new InfraException('Erro montando indexação de Unidade.',$e);
    }
  }

  private function obterTipoContatoUnidades(OrgaoDTO $objOrgaoDTO){
    try{

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $numIdTipoContato = $objInfraParametro->getValor($objOrgaoDTO->getStrSigla().'_ID_TIPO_CONTATO_UNIDADES',false);

      if (InfraString::isBolVazia($numIdTipoContato)){
        $objTipoContatoDTO = new TipoContatoDTO();
        $objTipoContatoDTO->setNumIdTipoContato(null);
        $objTipoContatoDTO->setStrNome('Unidades '.$objOrgaoDTO->getStrSigla());
        $objTipoContatoDTO->setStrDescricao(null);
        $objTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_COMPLETA);
        $objTipoContatoDTO->setStrSinSistema('S');
        $objTipoContatoDTO->setStrSinAtivo('S');

        $objTipoContatoRN = new TipoContatoRN();
        $objTipoContatoDTO = $objTipoContatoRN->cadastrarRN0334($objTipoContatoDTO);

        $numIdTipoContato = $objTipoContatoDTO->getNumIdTipoContato();

        $objRelUnidadeTipoContatoDTO = new RelUnidadeTipoContatoDTO();
        $objRelUnidadeTipoContatoDTO->setNumIdTipoContato($numIdTipoContato);
        $objRelUnidadeTipoContatoDTO->setNumIdUnidade($objInfraParametro->getValor('ID_UNIDADE_TESTE'));
        $objRelUnidadeTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_ALTERACAO);

        $objRelUnidadeTipoContatoRN = new RelUnidadeTipoContatoRN();
        $objRelUnidadeTipoContatoRN->cadastrarRN0545($objRelUnidadeTipoContatoDTO);

        $objInfraParametro->setValor($objOrgaoDTO->getStrSigla().'_ID_TIPO_CONTATO_UNIDADES',$numIdTipoContato);
      }

      return $numIdTipoContato;

    }catch(Exception $e){
      throw new InfraException('Erro obtendo tipo do contato associado com a unidade.', $e);
    }
  }
  private function tratarHistoricoUnidade(UnidadeDTO $objUnidadeDTO, UnidadeDTO $objUnidadeDTOBanco){
    //testa se alterou a sigla ou descrição do unidade, o que ocasiona cadastro no historico
    if($objUnidadeDTOBanco->getStrSigla() != $objUnidadeDTO->getStrSigla() || $objUnidadeDTOBanco->getStrDescricao() != $objUnidadeDTO->getStrDescricao() || $objUnidadeDTOBanco->getNumIdOrgao() != $objUnidadeDTO->getNumIdOrgao()){
      $objUnidadeHistoricoRN = new UnidadeHistoricoRN();

      $objUnidadeHistoricoDTO_Atual = new UnidadeHistoricoDTO();
      $objUnidadeHistoricoDTO_Atual->retNumIdUnidadeHistorico();
      $objUnidadeHistoricoDTO_Atual->retNumIdUnidade();
      $objUnidadeHistoricoDTO_Atual->retStrSigla();
      $objUnidadeHistoricoDTO_Atual->retStrDescricao();
      $objUnidadeHistoricoDTO_Atual->retDtaInicio();
      $objUnidadeHistoricoDTO_Atual->retDtaFim();
      $objUnidadeHistoricoDTO_Atual->retNumIdOrgao();
      $objUnidadeHistoricoDTO_Atual->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
      $objUnidadeHistoricoDTO_Atual->setDtaFim(null);
      //busca o historico vigente (com data final nula) do unidade
      $objUnidadeHistoricoDTO_Atual = $objUnidadeHistoricoRN->consultar($objUnidadeHistoricoDTO_Atual);
      //flag que indica que o historico é cadastrado a partir do cadastro/alteracao de um unidade em si
      $objUnidadeHistoricoDTO_Atual->setBolOrigemSIP(true);
      //se a data inicial do historico vigente for hoje, nao será cadastrado um novo registro de historico; apenas as informacoes de sigla e descricao serao atualizadas no historico do banco
      if(InfraData::compararDatasSimples($objUnidadeHistoricoDTO_Atual->getDtaInicio(),InfraData::getStrDataHoraAtual()) == 0){
        $objUnidadeHistoricoDTO_Atual->setStrSigla($objUnidadeDTO->getStrSigla());
        $objUnidadeHistoricoDTO_Atual->setStrDescricao($objUnidadeDTO->getStrDescricao());
        $objUnidadeHistoricoDTO_Atual->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());
        $objUnidadeHistoricoRN->alterar($objUnidadeHistoricoDTO_Atual);
        //senao, o historico vigente terá a data final setada como ontem, e um novo historico, que será o vigente (com data final nula), será cadastrado
      }else{
        $objUnidadeHistoricoDTO_Atual->setDtaFim(InfraData::calcularData(1,InfraData::$UNIDADE_DIAS,InfraData::$SENTIDO_ATRAS,InfraData::getStrDataHoraAtual()));
        $objUnidadeHistoricoRN->alterar($objUnidadeHistoricoDTO_Atual);

        $objUnidadeHistoricoDTO_Novo = new UnidadeHistoricoDTO();
        $objUnidadeHistoricoDTO_Novo->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
        $objUnidadeHistoricoDTO_Novo->setStrSigla($objUnidadeDTO->getStrSigla());
        $objUnidadeHistoricoDTO_Novo->setStrDescricao($objUnidadeDTO->getStrDescricao());
        $objUnidadeHistoricoDTO_Novo->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());
        $objUnidadeHistoricoDTO_Novo->setDtaInicio(InfraData::getStrDataHoraAtual());
        $objUnidadeHistoricoDTO_Novo->setDtaFim(null);
        $objUnidadeHistoricoDTO_Novo->setBolOrigemSIP(true);
        $objUnidadeHistoricoRN->cadastrar($objUnidadeHistoricoDTO_Novo);
      }
    }
  }

  protected function gerarIdentificadorFederacaoControlado(UnidadeDTO $parObjUnidadeDTO) {
    try {

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->setBolExclusaoLogica(false);
      $objUnidadeDTO->setNumIdUnidade($parObjUnidadeDTO->getNumIdUnidade());
      $objUnidadeDTO = $this->bloquear($objUnidadeDTO);

      if ($objUnidadeDTO->getStrIdUnidadeFederacao()==null){

        $strIdUnidadeFederacao = InfraULID::gerar();

        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();

        $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
        $objUnidadeFederacaoDTO->setStrIdUnidadeFederacao($strIdUnidadeFederacao);
        $objUnidadeFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal());
        $objUnidadeFederacaoDTO->setStrSigla($objUnidadeDTO->getStrSigla());
        $objUnidadeFederacaoDTO->setStrDescricao($objUnidadeDTO->getStrDescricao());

        $objUnidadeFederacaoRN = new UnidadeFederacaoRN();
        $objUnidadeFederacaoRN->cadastrar($objUnidadeFederacaoDTO);

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setStrIdUnidadeFederacao($strIdUnidadeFederacao);
        $objUnidadeDTO->setNumIdUnidade($parObjUnidadeDTO->getNumIdUnidade());

        $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
        $objUnidadeBD->alterar($objUnidadeDTO);
      }

      $parObjUnidadeDTO->setStrIdUnidadeFederacao($objUnidadeDTO->getStrIdUnidadeFederacao());

    }catch(Exception $e){
      throw new InfraException('Erro gerando identificador do SEI Federação para a Unidade.',$e);
    }
  }
}
?>