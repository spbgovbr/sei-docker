<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 12/11/2007 - criado por MGA
 *
 */
 
require_once dirname(__FILE__).'/SEI.php';
 
 class SessaoSEIExterna extends InfraSessao {
 
 	private static $instance = null;
  private $numIdAcessoExterno = null;
  private $objAcessoExternoDTO = null;

 	public static function getInstance($numIdAcessoExterno = null) { 
	    if (self::$instance == null) {
	    	SessaoSEI::getInstance(false, false);
	    	self::$instance = new SessaoSEIExterna($numIdAcessoExterno);
      } 
	    return self::$instance; 
	}

	public function __construct($numIdAcessoExterno){
    parent::__construct(false, false);
    $this->configurarAcessoExterno($numIdAcessoExterno);
	}
	
  public function getStrSiglaOrgaoSistema(){
		return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','SiglaOrgaoSistema');
	}
	
	public function getStrSiglaSistema(){
		return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','SiglaSistema');
	}
	
	public function getStrPaginaLogin(){
		return ConfiguracaoSEI::getInstance()->getValor('SEI','URL').'/controlador_externo.php?acao=usuario_externo_logar';
	}
	
  public function getStrSipWsdl(){
		return null;
  }

  public function getNumIdAcessoExterno(){
  	return $this->numIdAcessoExterno;
  }

  public function configurarAcessoExterno($numIdAcessoExterno){

    $this->numIdAcessoExterno = null;
    $this->objAcessoExternoDTO = null;

    if ($numIdAcessoExterno!=null) {

      $this->setAtributo('EMAIL_ACESSO_EXTERNO', null);
      $this->setAtributo('ID_ORGAO_USUARIO_EXTERNO', null);
      $this->setAtributo('SIGLA_ORGAO_USUARIO_EXTERNO', null);
      $this->setAtributo('DESCRICAO_ORGAO_USUARIO_EXTERNO', null);

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->setBolExclusaoLogica(false);
      $objAcessoExternoDTO->retStrHashInterno();
      $objAcessoExternoDTO->retDtaValidade();
      $objAcessoExternoDTO->retStrStaTipo();
      $objAcessoExternoDTO->retStrEmailDestinatario();
      $objAcessoExternoDTO->retNumIdOrgaoUnidade();
      $objAcessoExternoDTO->retStrSiglaOrgaoUnidade();
      $objAcessoExternoDTO->retStrDescricaoOrgaoUnidade();
      $objAcessoExternoDTO->retStrSinAtivo();
      $objAcessoExternoDTO->setNumIdAcessoExterno($numIdAcessoExterno);

      $objAcessoExternoRN = new AcessoExternoRN();
      $objAcessoExternoDTO = $objAcessoExternoRN->consultar($objAcessoExternoDTO);

      if ($objAcessoExternoDTO == null) {
        $this->lancarErro(__LINE__, 'Acesso Externo inválido.', false);
      }

      if ($this->getNumIdUsuarioExterno()==null && ($objAcessoExternoDTO->getStrStaTipo()==AcessoExternoRN::$TA_USUARIO_EXTERNO || $objAcessoExternoDTO->getStrStaTipo()==AcessoExternoRN::$TA_ASSINATURA_EXTERNA)){
        $this->sair();
      }

      $this->numIdAcessoExterno = $numIdAcessoExterno;
      $this->objAcessoExternoDTO = $objAcessoExternoDTO;

      $this->setAtributo('EMAIL_ACESSO_EXTERNO', $objAcessoExternoDTO->getStrEmailDestinatario());
      $this->setAtributo('ID_ORGAO_USUARIO_EXTERNO', $objAcessoExternoDTO->getNumIdOrgaoUnidade());
      $this->setAtributo('SIGLA_ORGAO_USUARIO_EXTERNO', $objAcessoExternoDTO->getStrSiglaOrgaoUnidade());
      $this->setAtributo('DESCRICAO_ORGAO_USUARIO_EXTERNO', $objAcessoExternoDTO->getStrDescricaoOrgaoUnidade());
    }

    AuditoriaSEI::getInstance()->setStrComplemento($this->getStrAuditoria());
  }

  private function getObjAcessoExternoDTO(){
    return $this->objAcessoExternoDTO;
  }
  
  public function logar($parObjUsuarioDTO){
    try{

      $this->configurarAcessoExterno(null);

      $objInfraException = new InfraException();
      
      $objUsuarioDTO = new UsuarioDTO();
  		$objUsuarioDTO->retNumIdUsuario();
  		$objUsuarioDTO->retStrSigla();
  		$objUsuarioDTO->retStrNome();
  		$objUsuarioDTO->retNumIdOrgao();
  		$objUsuarioDTO->retStrSiglaOrgao();
  		$objUsuarioDTO->retStrDescricaoOrgao();
  		$objUsuarioDTO->retStrStaTipo();
  		$objUsuarioDTO->retStrSenha();
    	$objUsuarioDTO->setStrSigla($parObjUsuarioDTO->getStrSigla());
    	$objUsuarioDTO->setStrStaTipo(array(UsuarioRN::$TU_EXTERNO, UsuarioRN::$TU_EXTERNO_PENDENTE), InfraDTO::$OPER_IN);
		
		  $objUsuarioRN = new UsuarioRN();
		  $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);
		  
		  if ($objUsuarioDTO==null) {
			  //usuário incorreto ou não cadastrado			
			  $objInfraException->lancarValidacao(InfraLDAP::$MSG_USUARIO_SENHA_INVALIDA);
		  }

      $bcrypt=new InfraBcrypt();
      $senhaBanco=$objUsuarioDTO->getStrSenha();
      $senhaInformada=md5($_POST['pwdSenha']);
		  if (!$bcrypt->verificar($senhaInformada,$senhaBanco)) {
		    $objInfraException->lancarValidacao(InfraLDAP::$MSG_USUARIO_SENHA_INVALIDA);
		  }

      if ($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_EXTERNO_PENDENTE){
        $objInfraException->lancarValidacao('Usuário ainda não foi liberado.');
      }

      $this->setAtributo('EMAIL_ACESSO_EXTERNO', null);
      $this->setAtributo('ID_USUARIO_EXTERNO', $objUsuarioDTO->getNumIdUsuario());
      $this->setAtributo('SIGLA_USUARIO_EXTERNO', $objUsuarioDTO->getStrSigla());
      $this->setAtributo('NOME_USUARIO_EXTERNO', $objUsuarioDTO->getStrNome());
      $this->setAtributo('ID_ORGAO_USUARIO_EXTERNO', $objUsuarioDTO->getNumIdOrgao());
      $this->setAtributo('SIGLA_ORGAO_USUARIO_EXTERNO', $objUsuarioDTO->getStrSiglaOrgao());
      $this->setAtributo('DESCRICAO_ORGAO_USUARIO_EXTERNO', $objUsuarioDTO->getStrDescricaoOrgao());
      $this->setAtributo('RAND_USUARIO_EXTERNO', uniqid(mt_rand(), true));
      
      $objUsuarioDTOAuditoria = clone($objUsuarioDTO);
      $objUsuarioDTOAuditoria->unSetStrSenha();

      AuditoriaSEI::getInstance()->auditar('usuario_externo_logar',__METHOD__,$objUsuarioDTOAuditoria);

      AuditoriaSEI::getInstance()->setStrComplemento($this->getStrAuditoria());

      //session_regenerate_id();
      
    }catch(Exception $e){
      throw new InfraException('Erro realizando login externo.', $e);
    }
  }
  
  public function sair($strLink=null, $strMensagem=null){
    
    if ($strMensagem!=null){
	    PaginaSEIExterna::getInstance()->setStrMensagem($strMensagem);
    }

    if ($strLink == null) {

      $strLink = $this->getStrPaginaLogin();

      $strParamIdOrgaoExterno = '';

      if (strpos($strLink, 'id_orgao_acesso_externo=') === false && $_GET['id_orgao_acesso_externo'] != '') {

        if (strpos($strLink, '?') === false) {
          $strParamIdOrgaoExterno = '?';
        } else {
          $strParamIdOrgaoExterno = '&';
        }

        $strParamIdOrgaoExterno .= 'id_orgao_acesso_externo=' . $_GET['id_orgao_acesso_externo'];

        $strLink .= $strParamIdOrgaoExterno;
      }
    }
	  
	  if ($this->getAtributo('ID_USUARIO_EXTERNO')!=null){
	    AuditoriaSEI::getInstance()->auditar('usuario_externo_sair',__METHOD__, 'id_usuario_externo='.$this->getAtributo('ID_USUARIO_EXTERNO').'&sigla_usuario_externo='.$this->getAtributo('SIGLA_USUARIO_EXTERNO'));
	  }

    $this->removerDadosSessao();

	  header('Location: '.$strLink);
		die;
  }

  public function removerDadosSessao(){
 	  $this->setAtributo('EMAIL_ACESSO_EXTERNO', null);
    $this->setAtributo('ID_USUARIO_EXTERNO', null);
    $this->setAtributo('SIGLA_USUARIO_EXTERNO', null);
    $this->setAtributo('NOME_USUARIO_EXTERNO', null);
    $this->setAtributo('ID_ORGAO_USUARIO_EXTERNO', null);
    $this->setAtributo('SIGLA_ORGAO_USUARIO_EXTERNO', null);
    $this->setAtributo('DESCRICAO_ORGAO_USUARIO_EXTERNO', null);
    $this->setAtributo('RAND_USUARIO_EXTERNO', null);
    $this->configurarAcessoExterno(null);
  }

  public function getStrEmailAcessoExterno(){
   return $this->getAtributo('EMAIL_ACESSO_EXTERNO');
  }

  public function getNumIdUsuarioExterno(){
  	return $this->getAtributo('ID_USUARIO_EXTERNO');
  }

  public function getStrSiglaUsuarioExterno(){
  	return $this->getAtributo('SIGLA_USUARIO_EXTERNO');
  }
  
  public function getStrNomeUsuarioExterno(){
  	return $this->getAtributo('NOME_USUARIO_EXTERNO');
  }
  
  public function getNumIdOrgaoUsuarioExterno(){
  	return $this->getAtributo('ID_ORGAO_USUARIO_EXTERNO') === '' ? null : $this->getAtributo('ID_ORGAO_USUARIO_EXTERNO');
  }

  public function getStrSiglaOrgaoUsuarioExterno(){
  	return $this->getAtributo('SIGLA_ORGAO_USUARIO_EXTERNO');
  }
  
  public function getStrDescricaoOrgaoUsuarioExterno(){
  	return $this->getAtributo('DESCRICAO_ORGAO_USUARIO_EXTERNO');
  }
  
  public function validarPermissao($strNomeRecurso){
  	return true;
  }
  
  public function gerarHashExterno($strParam){
  
    $strHash = null;

    if ($this->getNumIdAcessoExterno()!=null){
      
       $strHash = md5($strParam.$this->getObjAcessoExternoDTO()->getStrHashInterno());

    }else if ($this->getNumIdUsuarioExterno()!=null){

       $strHash = md5($strParam.'#'.$this->getNumIdUsuarioExterno().'@'.$this->getAtributo('RAND_USUARIO_EXTERNO'));
       
    }
   
    return $strHash;
  }  
  
  public function assinarLink($strLink){

    $strLink = urldecode($strLink);

    if ($this->getNumIdAcessoExterno()==null && $this->getNumIdUsuarioExterno()==null){

      if (!isset($_GET['id_orgao_acesso_externo'])){
        $this->lancarErro(__LINE__, 'Link externo inválido.', false);
      }
      
      if (strpos($strLink,'?')===false){
        $strLink .= '?';
      }else{
        $strLink .= '&';
      }
      
      $strLink .= 'id_orgao_acesso_externo='.$_GET['id_orgao_acesso_externo'];

      
    }else{
  
      //retira ancora do link
      $strAncora ='';
      $numPosAncora = strpos($strLink,'#');
      if ($numPosAncora!==false){
  			$strNovoLink = substr($strLink,0,$numPosAncora);
  			$strAncora = substr($strLink,$numPosAncora);
  			$strLink = $strNovoLink;
      }
  
      $arrRemover = array('?infra_sistema=', 
                          '&infra_sistema=', 
                          '?infra_unidade_atual=', 
                          '&infra_unidade_atual=', 
                          '&infra_hash=');
      
      foreach($arrRemover as $parametro){
  	    //Remove hash, se existir
  	    $numPosParametro = strpos($strLink, $parametro);
  	    if ($numPosParametro!==false){
  				$strNovoLink = substr($strLink,0,$numPosParametro);
  				$strLink = substr($strLink,$numPosParametro+1);
  				$posMais = strpos($strLink,'&');
  				//Se tem algo após o parâmetro
  				if ($posMais!==false){
  					//Atribui o resto do link
  	        $strNovoLink .= substr($strLink,$posMais);
  				}
  				$strLink = $strNovoLink;
  	    }
      }
      
      
      if (strpos($strLink,'id_orgao_acesso_externo=')===false){
        if (isset($_GET['id_orgao_acesso_externo']) && $_GET['id_orgao_acesso_externo']!=''){  
          if (strpos($strLink,'?')===false){
            $strLink .= '?';
          }else{
            $strLink .= '&';
          }
          $strLink .= 'id_orgao_acesso_externo='.$_GET['id_orgao_acesso_externo'];
        }
      }
      
      //Gera hash se tiver parametros no link
      $numPosParam = strpos($strLink,'?');
      if ($numPosParam!==false){
        $strParam = substr($strLink, $numPosParam+1);
        $strLink .= '&infra_hash='.$this->gerarHashExterno($strParam);
      }
      
      //Se tem ancora coloca no final (não entrou no calculo do hash)
      if ($strAncora!=''){
        $strLink .= $strAncora;
      }
    }
    return $strLink;
  }
  
  public function validarLink($strLink=null){

    foreach($_GET as $key => $item){
      if(!is_array($item)){
        $item=[$item];
      }
      foreach ($item as $valor) {
        if ($valor!='') {
          if (preg_match("/[^a-zA-Z0-9\-_]/", $valor)) {
            $this->lancarErro(__LINE__, 'Link externo inválido.', false);
          }
        }
      }
    }

    if ($strLink == null){
      $strLink = $_SERVER['REQUEST_URI'];
    }

    $strLink = urldecode($strLink);

    if (trim($strLink)==''){
      return;
    }

    $arrParametros = array('id_acesso_externo','id_orgao_acesso_externo', 'id_procedimento', 'id_procedimento_anexado', 'id_documento', 'id_anexo');

    foreach($arrParametros as $strParametro){
      if (isset($_GET[$strParametro])) {
        $item=$_GET[$strParametro];
        if(!is_array($item)){
          $item=[$item];
        }
        foreach ($item as $valor) {
          if (trim($valor)!='' && !is_numeric($valor)) {
            $this->lancarErro(__LINE__, 'Link externo inválido.', false);
          }
        }
      }
    }

    if (isset($_GET['id_orgao_acesso_externo'])){

      if ($this->getNumIdOrgaoUsuarioExterno()===null){

        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->setBolExclusaoLogica(false);
        $objOrgaoDTO->retNumIdOrgao();
        $objOrgaoDTO->retStrSigla();
        $objOrgaoDTO->retStrDescricao();
        $objOrgaoDTO->setNumIdOrgao($_GET['id_orgao_acesso_externo']);

        $objOrgaoRN = new OrgaoRN();
        $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

        if ($objOrgaoDTO==null){
          $this->lancarErro(__LINE__, 'Link externo inválido.', false);
        }

        $this->setAtributo('ID_ORGAO_USUARIO_EXTERNO', $objOrgaoDTO->getNumIdOrgao());
        $this->setAtributo('SIGLA_ORGAO_USUARIO_EXTERNO', $objOrgaoDTO->getStrSigla());
        $this->setAtributo('DESCRICAO_ORGAO_USUARIO_EXTERNO', $objOrgaoDTO->getStrDescricao());
      }
    }

    if (basename($_SERVER['SCRIPT_FILENAME']) == 'controlador_externo.php'){

      if (!isset($_GET['acao']) || trim($_GET['acao'])==''){
        $this->lancarErro(__LINE__, 'Link externo inválido.', false);
      }

      if (!isset($_GET['id_orgao_acesso_externo'])){
        $this->lancarErro(__LINE__, 'Link externo inválido.', false);
      }

      if ($this->isBolAcaoSemLogin()){
        return;
      }

      if ($this->getNumIdUsuarioExterno()==null){
        $this->sair();
      }
    }

    if (basename($_SERVER['SCRIPT_FILENAME']) == 'controlador_ajax_externo.php'){

      if (!isset($_GET['acao_ajax']) || trim($_GET['acao_ajax'])==''){
        $this->lancarErro(__LINE__, 'Link externo inválido.', false);
      }

      if ($this->isBolAcaoAjaxSemLogin()){
        return;
      }
    }

    if (isset($_GET['id_acesso_externo'])){
      $this->configurarAcessoExterno($_GET['id_acesso_externo']);
    }else{
      $this->configurarAcessoExterno(null);
    }

    if (basename($_SERVER['SCRIPT_FILENAME']) == 'processo_acesso_externo_consulta.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'documento_consulta_externa.php'){

      if ($this->getNumIdAcessoExterno() != null) {

        $objAcessoExternoDTO = $this->getObjAcessoExternoDTO();

        if ($objAcessoExternoDTO->getStrSinAtivo() == 'N') {
          $this->lancarErro(__LINE__, 'Esta disponibilização de acesso externo foi cancelada.', false);
        } else if (!InfraString::isBolVazia($objAcessoExternoDTO->getDtaValidade()) && InfraData::compararDatas(date('d/m/Y'), $objAcessoExternoDTO->getDtaValidade()) < 0) {
          $this->lancarErro(__LINE__, 'Esta disponibilização de acesso externo expirou em '.$objAcessoExternoDTO->getDtaValidade().'.', false);
        }

      }else if (!isset($_GET['id_procedimento'])){

        $this->lancarErro(__LINE__, 'Link externo inválido.', false);

      }
    }


    $strParamHash = '&infra_hash=';
    $numPosHash = strpos($strLink,$strParamHash);
    if ($numPosHash===false){
      $strParamHash = '&hash=';
      $numPosHash = strpos($strLink, $strParamHash);
      if ($numPosHash===false){
        $this->lancarErro(__LINE__, 'Link externo inválido.', false);
      }
    }

    $strHashLink = substr($strLink, $numPosHash + strlen($strParamHash));

    if (strlen($strHashLink)!=32){
      $this->lancarErro(__LINE__, 'Link externo inválido.', false);
    }

    $numPosParam = strpos($strLink,'?');
    if ($numPosParam===false){
      $this->lancarErro(__LINE__, 'Link externo inválido.', false);
    }

    $strParam = substr($strLink, $numPosParam+1, $numPosHash-$numPosParam-1);

    if ($strHashLink != $this->gerarHashExterno($strParam)){
      $this->lancarErro(__LINE__, 'Link externo inválido.', false);
    }
  }

  private function lancarErro($numLinha, $strErro, $bolGravar){
    $this->removerDadosSessao();
    throw new InfraException($strErro, null, basename(__FILE__).' ['.$numLinha.']: '.$_SERVER['REQUEST_URI'], $bolGravar);
  }

  public function isBolAcaoSemLogin(){

    global $SEI_MODULOS;

    $arrAcoes = array('usuario_externo_logar',
                      'usuario_externo_sair',
                      'usuario_externo_avisar_cadastro',
                      'usuario_externo_enviar_cadastro',
                      'usuario_externo_gerar_senha',
                      'documento_conferir',
                      'ouvidoria',
                      'infra_erro_fatal_logar');

    foreach ($SEI_MODULOS as $seiModulo){
      if (($arrAcoesModulo = $seiModulo->executar('obterAcoesExternasSemLogin')) != null) {
        $arrAcoes = array_merge($arrAcoes, $arrAcoesModulo);
      }
    }

    return in_array($_GET['acao'], $arrAcoes);
  }

   public function isBolAcaoAjaxSemLogin(){

     global $SEI_MODULOS;

     $arrAcoes = array('cidade_montar_select_id_cidade_nome');

     foreach ($SEI_MODULOS as $seiModulo){
       if (($arrAcoesModulo = $seiModulo->executar('obterAcoesAjaxExternasSemLogin')) != null) {
         $arrAcoes = array_merge($arrAcoes, $arrAcoesModulo);
       }
     }

     return in_array($_GET['acao_ajax'], $arrAcoes);
   }

   private function getStrAuditoria() {
 	  $ret = '';

 	  if ($this->getStrEmailAcessoExterno()!=null){
 	    $ret .= 'EmailAcessoExterno = '.$this->getStrEmailAcessoExterno()."\n";
    }

 	  if ($this->getNumIdUsuarioExterno()!=null){
 	    $ret .= 'IdUsuarioExterno = '.$this->getNumIdUsuarioExterno()."\n";
    }

 	  if ($this->getStrSiglaUsuarioExterno()!=null && $this->getStrSiglaUsuarioExterno()!=$this->getStrEmailAcessoExterno()){
      $ret .= 'SiglaUsuarioExterno = '.$this->getStrSiglaUsuarioExterno()."\n";
    }

 	  if ($ret != ''){
 	    $ret = "\n\n".__CLASS__.":\n".$ret;
    }

 	  return $ret;
   }
}
?>