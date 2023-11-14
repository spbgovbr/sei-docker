<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/01/2008 - criado por marcio_db
* 06/06/2018 - cjy - adicao dos campos numero_passaporte e id_pais_passaporte
* 13/06/2018 - cjy - adicao dos campos pais e estado e cidade estrangeiros

 * Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class UsuarioRN extends InfraRN {

  public static $POS_USUARIO_SIGLA = 0;
  public static $POS_USUARIO_NOME = 1;
  public static $POS_USUARIO_UNIDADES = 2;

  public static $TU_SIP = '0';
  public static $TU_SISTEMA = '1';
  public static $TU_EXTERNO_PENDENTE = '2';
  public static $TU_EXTERNO = '3';

  private $locale;
  private $arrIdiomas;

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarExternoControlado(UsuarioDTO $objUsuarioDTO) {
    try{
      SeiINT::definirIdioma("usuario_externo",$this->arrIdiomas,$this->locale);

      $objInfraException = new InfraException();

      $this->validarStrSinEstrangeiro($objUsuarioDTO, $objInfraException );
      if($objUsuarioDTO->getStrSinEstrangeiro() == "S") {
        $this->validarStrNumeroPassaporte($objUsuarioDTO, $objInfraException);
        $this->validarNumIdPaisPassaporte($objUsuarioDTO, $objInfraException);
      }else{
        $this->validarDblCpf($objUsuarioDTO, $objInfraException);;
        $this->validarDblRg($objUsuarioDTO, $objInfraException);;
        $this->validarStrOrgaoExpedidor($objUsuarioDTO, $objInfraException);;
      }
      $objInfraException->lancarValidacoes();

      //Valida Permissao
      $objUsuarioDTOAuditoria = clone($objUsuarioDTO);
      $objUsuarioDTOAuditoria->unSetStrSenha();
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_externo_enviar_cadastro', __METHOD__, $objUsuarioDTOAuditoria);

      $this->cadastrarRN0487($objUsuarioDTO);
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException(_('Erro cadastrando Usuário Externo.'),$e);
    }
  }

  public function cadastrarRN0487(UsuarioDTO $objUsuarioDTO) {
    try{

      MailSEI::getInstance()->limpar();

      $ret = $this->cadastrarRN0487Interno($objUsuarioDTO);

      MailSEI::getInstance()->enviar();

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Usuário.',$e);
    }
  }

  protected function cadastrarRN0487InternoControlado(UsuarioDTO $objUsuarioDTO) {
    try{

      //Valida Permissao
      $objUsuarioDTOAuditoria = clone($objUsuarioDTO);
      $objUsuarioDTOAuditoria->unSetStrSenha();
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_cadastrar', __METHOD__, $objUsuarioDTOAuditoria);

      $objUsuarioDTO = clone($objUsuarioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $this->validarNumIdOrgao($objUsuarioDTO, $objInfraException);
      $this->validarStrIdOrigem($objUsuarioDTO, $objInfraException);
      $this->validarStrSiglaRN0955($objUsuarioDTO, $objInfraException);
      $this->validarStrNome($objUsuarioDTO,$objInfraException);

      if ($objUsuarioDTO->isSetStrNomeSocial()) {
        $this->validarStrNomeSocial($objUsuarioDTO, $objInfraException);
      }else{
        $objUsuarioDTO->setStrNomeSocial(null);
      }

      $this->validarStrStaTipo($objUsuarioDTO, $objInfraException);
      $this->validarStrSinAcessibilidade($objUsuarioDTO, $objInfraException);
      $this->validarStrSinAtivoRN0694($objUsuarioDTO, $objInfraException);
      $objInfraException->lancarValidacoes();

      if (!$objUsuarioDTO->isSetNumIdUfContato()){
        $objUsuarioDTO->setNumIdUfContato(null);
      }

      if (!$objUsuarioDTO->isSetNumIdCidadeContato()){
        $objUsuarioDTO->setNumIdCidadeContato(null);
      }

      if (!$objUsuarioDTO->isSetStrSiglaUfContato()){
        $objUsuarioDTO->setStrSiglaUfContato(null);
      }

      if (!$objUsuarioDTO->isSetStrNomeCidadeContato()){
        $objUsuarioDTO->setStrNomeCidadeContato(null);
      }

      if (!$objUsuarioDTO->isSetStrEnderecoContato()){
        $objUsuarioDTO->setStrEnderecoContato(null);
      }

      if (!$objUsuarioDTO->isSetStrComplementoContato()){
        $objUsuarioDTO->setStrComplementoContato(null);
      }

      if (!$objUsuarioDTO->isSetStrBairroContato()){
        $objUsuarioDTO->setStrBairroContato(null);
      }

      if (!$objUsuarioDTO->isSetNumIdCidadeContato()){
        $objUsuarioDTO->setNumIdCidadeContato(null);
      }

      if (!$objUsuarioDTO->isSetNumIdUfContato()){
        $objUsuarioDTO->setNumIdUfContato(null);
      }

      if (!$objUsuarioDTO->isSetNumIdPaisContato()){
        $objUsuarioDTO->setNumIdPaisContato(null);
      }

      if (!$objUsuarioDTO->isSetStrCepContato()){
        $objUsuarioDTO->setStrCepContato(null);
      }

      if (!$objUsuarioDTO->isSetStrTelefoneComercialContato()){
        $objUsuarioDTO->setStrTelefoneComercialContato(null);
      }

      if (!$objUsuarioDTO->isSetStrTelefoneResidencialContato()){
        $objUsuarioDTO->setStrTelefoneResidencialContato(null);
      }

      if (!$objUsuarioDTO->isSetStrTelefoneCelularContato()){
        $objUsuarioDTO->setStrTelefoneCelularContato(null);
      }

      if (!$objUsuarioDTO->isSetDblCpfContato()){
        $objUsuarioDTO->setDblCpfContato(null);
      }

      if (!$objUsuarioDTO->isSetDblRgContato()){
        $objUsuarioDTO->setDblRgContato(null);
      }

      if (!$objUsuarioDTO->isSetStrOrgaoExpedidorContato()){
        $objUsuarioDTO->setStrOrgaoExpedidorContato(null);
      }

      if (!$objUsuarioDTO->isSetNumIdPaisPassaporte()){
        $objUsuarioDTO->setNumIdPaisPassaporte(null);
      }

      if (!$objUsuarioDTO->isSetStrNumeroPassaporte()){
        $objUsuarioDTO->setStrNumeroPassaporte(null);
      }

      if (!$objUsuarioDTO->isSetStrEmailContato()){
        $objUsuarioDTO->setStrEmailContato(null);
      }


      if ($objUsuarioDTO->getStrStaTipo()==self::$TU_EXTERNO_PENDENTE) {

        $this->validarSenha($objUsuarioDTO->getStrSenha());

        $bcrypt = new InfraBcrypt();
        $objUsuarioDTO->setStrSenha($bcrypt->hash(md5($objUsuarioDTO->getStrSenha())));

      }else{
        $objUsuarioDTO->setStrSenha(null);
      }

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retNumIdContato();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->retStrSitioInternetContato();
      $objOrgaoDTO->setNumIdOrgao($objUsuarioDTO->getNumIdOrgao());
      
      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);
      
      if ($objOrgaoDTO==null){
      	throw new InfraException('Órgão não encontrado ['.$objUsuarioDTO->getNumIdOrgao().'].');
      }
      
      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $objInfraSequencia = new InfraSequencia(BancoSEI::getInstance());
      
      if ($objUsuarioDTO->getStrStaTipo()==self::$TU_SISTEMA){
        
        $objUsuarioDTO->setNumIdUsuario($objInfraSequencia->obterProximaSequencia('usuario_sistema'));
      	$numIdTipoContato = $objInfraParametro->getValor('ID_TIPO_CONTATO_SISTEMAS');
      	
      }else if  ($objUsuarioDTO->getStrStaTipo()==self::$TU_EXTERNO_PENDENTE){
        
        $objUsuarioDTO->setNumIdUsuario($objInfraSequencia->obterProximaSequencia('usuario_externo'));

        if (!$objInfraParametro->isSetValor($objOrgaoDTO->getStrSigla().'_ID_TIPO_CONTATO_USUARIOS_EXTERNOS')){
          $objTipoContatoDTO = new TipoContatoDTO();
          $objTipoContatoDTO->setNumIdTipoContato(null);
          $objTipoContatoDTO->setStrNome('Usuários Externos '.$objOrgaoDTO->getStrSigla());
          $objTipoContatoDTO->setStrDescricao(null);
          $objTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_RESUMIDA);
          $objTipoContatoDTO->setStrSinSistema('S');
          $objTipoContatoDTO->setStrSinAtivo('S');

          $objTipoContatoRN = new TipoContatoRN();
          $objTipoContatoDTO = $objTipoContatoRN->cadastrarRN0334($objTipoContatoDTO);
          
          $objRelUnidadeTipoContatoDTO = new RelUnidadeTipoContatoDTO(); 
          $objRelUnidadeTipoContatoDTO->setNumIdTipoContato($objTipoContatoDTO->getNumIdTipoContato());
          $objRelUnidadeTipoContatoDTO->setNumIdUnidade($objInfraParametro->getValor('ID_UNIDADE_TESTE'));
          $objRelUnidadeTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_ALTERACAO);
          
          $objRelUnidadeTipoContatoRN = new RelUnidadeTipoContatoRN();
          $objRelUnidadeTipoContatoRN->cadastrarRN0545($objRelUnidadeTipoContatoDTO);
          
          $objInfraParametro->setValor($objOrgaoDTO->getStrSigla().'_ID_TIPO_CONTATO_USUARIOS_EXTERNOS',$objTipoContatoDTO->getNumIdTipoContato());
        }
        
      	$numIdTipoContato = $objInfraParametro->getValor($objOrgaoDTO->getStrSigla().'_ID_TIPO_CONTATO_USUARIOS_EXTERNOS');
          
      }else{
        $numIdTipoContato = $this->obterTipoContatoUsuarios($objOrgaoDTO);
      }

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setBolExclusaoLogica(false);
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retStrSinAtivo();
      $objContatoDTO->setStrSigla($objUsuarioDTO->getStrSigla());

      if  ($objUsuarioDTO->getStrStaTipo() != self::$TU_EXTERNO_PENDENTE){
        if ($objUsuarioDTO->getStrNomeSocial()==null){
          $objContatoDTO->setStrNome($objUsuarioDTO->getStrNome());
        }else{
          $objContatoDTO->adicionarCriterio(array('Nome','Nome'),
                                            array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
                                            array($objUsuarioDTO->getStrNome(), $objUsuarioDTO->getStrNomeSocial()),
                                            InfraDTO::$OPER_LOGICO_OR);
        }
      }

      $objContatoDTO->setNumIdTipoContato($numIdTipoContato);
      $objContatoDTO->setOrdNumIdContato(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objContatoRN = new ContatoRN();
      $arrObjContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);

      if (count($arrObjContatoDTO) == 0){

        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->setNumIdContato(null);
        $objContatoDTO->setNumIdTipoContato($numIdTipoContato);

        if  ($objUsuarioDTO->getStrStaTipo()==self::$TU_SIP) {
          $objContatoDTO->setNumIdContatoAssociado($objOrgaoDTO->getNumIdContato());
          $objContatoDTO->setStrSinEnderecoAssociado('S');
        }else{
          $objContatoDTO->setNumIdContatoAssociado(null);
          $objContatoDTO->setStrSinEnderecoAssociado('N');
        }

        $objContatoDTO->setStrStaNatureza(ContatoRN::$TN_PESSOA_FISICA);
        $objContatoDTO->setStrSigla($objUsuarioDTO->getStrSigla());
        $objContatoDTO->setStrNome($objUsuarioDTO->getStrNome());
        $objContatoDTO->setStrNomeSocial($objUsuarioDTO->getStrNomeSocial());
        $objContatoDTO->setDblCpf($objUsuarioDTO->getDblCpfContato());
        $objContatoDTO->setDblRg($objUsuarioDTO->getDblRgContato());
        $objContatoDTO->setStrOrgaoExpedidor($objUsuarioDTO->getStrOrgaoExpedidorContato());
        $objContatoDTO->setStrEndereco($objUsuarioDTO->getStrEnderecoContato());
        $objContatoDTO->setStrComplemento($objUsuarioDTO->getStrComplementoContato());

        if ($objUsuarioDTO->getStrStaTipo() == self::$TU_SIP) {
          $objContatoDTO->setStrEmail($objUsuarioDTO->getStrEmailContato());
        }else if ($objUsuarioDTO->getStrStaTipo() == self::$TU_EXTERNO_PENDENTE) {
          $objContatoDTO->setStrEmail($objUsuarioDTO->getStrSigla());
        }

        $objContatoDTO->setStrTelefoneComercial($objUsuarioDTO->getStrTelefoneComercialContato());
        $objContatoDTO->setStrTelefoneResidencial($objUsuarioDTO->getStrTelefoneResidencialContato());
        $objContatoDTO->setStrTelefoneCelular($objUsuarioDTO->getStrTelefoneCelularContato());
        $objContatoDTO->setStrBairro($objUsuarioDTO->getStrBairroContato());
        $objContatoDTO->setNumIdUf($objUsuarioDTO->getNumIdUfContato());
        $objContatoDTO->setNumIdCidade($objUsuarioDTO->getNumIdCidadeContato());
        $objContatoDTO->setNumIdPais($objUsuarioDTO->getNumIdPaisContato());
        $objContatoDTO->setStrCep($objUsuarioDTO->getStrCepContato());
        $objContatoDTO->setStrSinAtivo('S');
        $objContatoDTO->setStrStaOperacao('REPLICACAO');
        $objContatoDTO->setNumIdPaisPassaporte($objUsuarioDTO->getNumIdPaisPassaporte());
        $objContatoDTO->setStrNumeroPassaporte($objUsuarioDTO->getStrNumeroPassaporte());
        $objContatoDTO->setStrNomeUf($objUsuarioDTO->getStrSiglaUfContato());
        $objContatoDTO->setStrNomeCidade($objUsuarioDTO->getStrNomeCidadeContato());

        $objContatoDTO = $objContatoRN->cadastrarRN0322($objContatoDTO);

      }else{

        $objContatoDTO = $arrObjContatoDTO[0];

        if ($objContatoDTO->getStrSinAtivo()=='N'){
          $objContatoRN->reativarRN0452(array($objContatoDTO));
        }

        if ($objUsuarioDTO->getStrStaTipo() == self::$TU_EXTERNO_PENDENTE) {
          $objContatoDTO->setNumIdContatoAssociado(null);
          $objContatoDTO->setStrSinEnderecoAssociado('N');
          $objContatoDTO->setStrStaNatureza(ContatoRN::$TN_PESSOA_FISICA);
          $objContatoDTO->setStrSigla($objUsuarioDTO->getStrSigla());
          $objContatoDTO->setStrNome($objUsuarioDTO->getStrNome());
          $objContatoDTO->setStrNomeSocial($objUsuarioDTO->getStrNomeSocial());
          $objContatoDTO->setDblCpf($objUsuarioDTO->getDblCpfContato());
          $objContatoDTO->setDblRg($objUsuarioDTO->getDblRgContato());
          $objContatoDTO->setStrOrgaoExpedidor($objUsuarioDTO->getStrOrgaoExpedidorContato());
          $objContatoDTO->setStrEndereco($objUsuarioDTO->getStrEnderecoContato());
          $objContatoDTO->setStrComplemento($objUsuarioDTO->getStrComplementoContato());
          $objContatoDTO->setStrEmail($objUsuarioDTO->getStrSigla());
          $objContatoDTO->setStrTelefoneComercial($objUsuarioDTO->getStrTelefoneComercialContato());
          $objContatoDTO->setStrTelefoneResidencial($objUsuarioDTO->getStrTelefoneResidencialContato());
          $objContatoDTO->setStrTelefoneCelular($objUsuarioDTO->getStrTelefoneCelularContato());
          $objContatoDTO->setStrBairro($objUsuarioDTO->getStrBairroContato());
          $objContatoDTO->setNumIdUf($objUsuarioDTO->getNumIdUfContato());
          $objContatoDTO->setNumIdCidade($objUsuarioDTO->getNumIdCidadeContato());
          $objContatoDTO->setNumIdPais($objUsuarioDTO->getNumIdPaisContato());
          $objContatoDTO->setStrCep($objUsuarioDTO->getStrCepContato());
          $objContatoDTO->setStrSinAtivo('S');
          $objContatoDTO->setStrStaOperacao('REPLICACAO');
          $objContatoDTO->setNumIdPaisPassaporte($objUsuarioDTO->getNumIdPaisPassaporte());
          $objContatoDTO->setStrNumeroPassaporte($objUsuarioDTO->getStrNumeroPassaporte());
          $objContatoDTO->setStrNomeUf($objUsuarioDTO->getStrSiglaUfContato());
          $objContatoDTO->setStrNomeCidade($objUsuarioDTO->getStrNomeCidadeContato());

          $objContatoRN->alterarRN0323($objContatoDTO);
        }
      }

      $objUsuarioDTO->setNumIdContato($objContatoDTO->getNumIdContato());
      $objUsuarioDTO->setStrIdUsuarioFederacao(null);
      $objUsuarioDTO->setStrIdxUsuario(null);

      $objUsuarioDTO->setStrNomeRegistroCivil($objUsuarioDTO->getStrNome());

      if ($objUsuarioDTO->getStrNomeSocial()!=null){
        $objUsuarioDTO->setStrNome($objUsuarioDTO->getStrNomeSocial());
      }

      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      $ret = $objUsuarioBD->cadastrar($objUsuarioDTO);
      
      $this->montarIndexacao($ret);
      
      
      if  ($objUsuarioDTO->getStrStaTipo()==self::$TU_EXTERNO_PENDENTE){
        
  			$objEmailSistemaDTO = new EmailSistemaDTO();
  			$objEmailSistemaDTO->retStrDe();
  			$objEmailSistemaDTO->retStrPara();
  			$objEmailSistemaDTO->retStrAssunto();
  			$objEmailSistemaDTO->retStrConteudo();
  			$objEmailSistemaDTO->setNumIdEmailSistema(EmailSistemaRN::$ES_CADASTRO_USUARIO_EXTERNO);
  			
  			$objEmailSistemaRN = new EmailSistemaRN();
  			$objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);
        
  			if ($objEmailSistemaDTO!=null){

  			  $strEmailSistema = $objInfraParametro->getValor('SEI_EMAIL_SISTEMA');
          if (InfraString::isBolVazia($strEmailSistema)){
            throw new InfraException('Parâmetro SEI_EMAIL_SISTEMA não foi configurado.');
          }

          $strDe = $objEmailSistemaDTO->getStrDe();
  			  $strDe = str_replace('@sigla_sistema@',SessaoSEI::getInstance()->getStrSiglaSistema(),$strDe);
  			  $strDe = str_replace('@email_sistema@',$strEmailSistema,$strDe);
  			  
  			  $strPara = $objEmailSistemaDTO->getStrPara();
  			  $strPara = str_replace('@email_usuario_externo@',$objUsuarioDTO->getStrSigla(),$strPara);
  					  
  			  $strAssunto = $objEmailSistemaDTO->getStrAssunto();
          $strAssunto = str_replace('@sigla_sistema@',SessaoSEI::getInstance()->getStrSiglaSistema(),$strAssunto);
          $strAssunto = str_replace('@sigla_orgao@',$objOrgaoDTO->getStrSigla(),$strAssunto);
  					  
  			  $strConteudo = $objEmailSistemaDTO->getStrConteudo();

  			  if (InfraString::isBolVazia($objUsuarioDTO->getStrNomeSocial())) {
            $strNome = $objUsuarioDTO->getStrNome();
          }else{
            $strNome = $objUsuarioDTO->getStrNomeSocial();
          }

          $strConteudo = str_replace('@nome_usuario_externo@', $strNome, $strConteudo);
  			  $strConteudo = str_replace('@email_usuario_externo@',$objUsuarioDTO->getStrSigla(),$strConteudo);
  			  $strConteudo = str_replace('@link_login_usuario_externo@',ConfiguracaoSEI::getInstance()->getValor('SEI','URL').'/controlador_externo.php?acao=usuario_externo_logar&id_orgao_acesso_externo='.$objOrgaoDTO->getNumIdOrgao(),$strConteudo);
          $strConteudo = str_replace('@sigla_orgao@',$objOrgaoDTO->getStrSigla(),$strConteudo);
          $strConteudo = str_replace('@descricao_orgao@',$objOrgaoDTO->getStrDescricao(),$strConteudo);
          $strConteudo = str_replace('@sitio_internet_orgao@',$objOrgaoDTO->getStrSitioInternetContato(),$strConteudo);

          $objEmailDTO = new EmailDTO();
          $objEmailDTO->setStrDe($strDe);
          $objEmailDTO->setStrPara($strPara);
          $objEmailDTO->setStrAssunto($strAssunto);
          $objEmailDTO->setStrMensagem($strConteudo);

          MailSEI::getInstance()->adicionar($objEmailDTO);
  			}
      }
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Usuário.',$e);
    }
  }

  protected function alterarRN0488Controlado(UsuarioDTO $objUsuarioDTO){
    try {

      $objUsuarioDTO = clone($objUsuarioDTO);

      $objInfraException = new InfraException();

      $objUsuarioDTOBanco = new UsuarioDTO();
      $objUsuarioDTOBanco->setBolExclusaoLogica(false);
      $objUsuarioDTOBanco->retStrIdUsuarioFederacao();
      $objUsuarioDTOBanco->retNumIdContato();
      $objUsuarioDTOBanco->retNumIdOrgao();
      $objUsuarioDTOBanco->retStrSigla();
      $objUsuarioDTOBanco->retStrNome();
      $objUsuarioDTOBanco->retStrNomeRegistroCivil();
      $objUsuarioDTOBanco->retStrNomeSocial();
      $objUsuarioDTOBanco->retDblCpfContato();
      $objUsuarioDTOBanco->retStrEmailContato();
      $objUsuarioDTOBanco->retStrStaTipo();
      $objUsuarioDTOBanco->retStrSenha();
      $objUsuarioDTOBanco->retStrIdUsuarioFederacao();
      $objUsuarioDTOBanco->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
      $objUsuarioDTOBanco = $this->consultarRN0489($objUsuarioDTOBanco);
      
      if ($objUsuarioDTOBanco->getStrStaTipo()==self::$TU_EXTERNO_PENDENTE || $objUsuarioDTOBanco->getStrStaTipo()==self::$TU_EXTERNO) {
        SessaoSEI::getInstance()->validarAuditarPermissao('usuario_externo_alterar',__METHOD__,$objUsuarioDTO); 
      }else{
        SessaoSEI::getInstance()->validarAuditarPermissao('usuario_alterar',__METHOD__,$objUsuarioDTO);
      }

      if($objUsuarioDTO->isSetNumIdContato() && $objUsuarioDTOBanco->getNumIdContato()!=$objUsuarioDTO->getNumIdContato()){
        $objInfraException->lancarValidacao('Não é possível alterar o contato associado.');
      }

      if ($objUsuarioDTO->isSetNumIdOrgao()){
          $this->validarNumIdOrgao($objUsuarioDTO, $objInfraException);
      }
      
      if ($objUsuarioDTO->isSetStrIdOrigem()){
        $this->validarStrIdOrigem($objUsuarioDTO, $objInfraException);  
      }

      if ($objUsuarioDTO->isSetStrIdUsuarioFederacao() && $objUsuarioDTOBanco->getStrIdUsuarioFederacao()!=$objUsuarioDTO->getStrIdUsuarioFederacao()){
        $objInfraException->lancarValidacao('Não é possível alterar o identificador do usuário no SEI Federação.');
      }else{
        $objUsuarioDTO->setStrIdUsuarioFederacao($objUsuarioDTOBanco->getStrIdUsuarioFederacao());
      }

      if ($objUsuarioDTO->isSetStrSigla()){
        $this->validarStrSiglaRN0955($objUsuarioDTO, $objInfraException);
      }else{
        $objUsuarioDTO->setStrSigla($objUsuarioDTOBanco->getStrSigla());
      }
      
      if ($objUsuarioDTO->isSetStrNome()){
        $this->validarStrNome($objUsuarioDTO, $objInfraException);
      }else{
        $objUsuarioDTO->setStrNome($objUsuarioDTOBanco->getStrNome());
      }

      if ($objUsuarioDTO->isSetStrNomeSocial()){
        $this->validarStrNomeSocial($objUsuarioDTO, $objInfraException);
      }else{
        $objUsuarioDTO->setStrNomeSocial($objUsuarioDTOBanco->getStrNomeSocial());
      }

      if ($objUsuarioDTO->isSetStrStaTipo() && $objUsuarioDTO->getStrStaTipo()!=$objUsuarioDTOBanco->getStrStaTipo()){
        if (($objUsuarioDTO->isSetStrStaTipo()!=self::$TU_EXTERNO_PENDENTE && $objUsuarioDTO->isSetStrStaTipo()!=self::$TU_EXTERNO) ||
             $objUsuarioDTOBanco->getStrStaTipo()!=self::$TU_EXTERNO_PENDENTE && $objUsuarioDTOBanco->getStrStaTipo()!=self::$TU_EXTERNO) {
          $objInfraException->adicionarValidacao('Não é possível alterar o tipo do usuário.');
        } 
      }else{
        $objUsuarioDTO->setStrStaTipo($objUsuarioDTOBanco->getStrStaTipo());
      }

      if ($objUsuarioDTO->isSetStrSenha() && $objUsuarioDTO->getStrSenha()!=$objUsuarioDTOBanco->getStrSenha()){
        $objInfraException->adicionarValidacao('Não é possível alterar a senha do usuário.');
      }

      if ($objUsuarioDTO->isSetStrSinAcessibilidade()){
        $this->validarStrSinAcessibilidade($objUsuarioDTO, $objInfraException);
      }

      if ($objUsuarioDTO->isSetStrSinAtivo()){
        $objUsuarioDTO->unSetStrSinAtivo();
      }

      if ($objUsuarioDTO->isSetStrIdxUsuario()){
        $objUsuarioDTO->unSetStrIdxUsuario();
      }

      $objInfraException->lancarValidacoes();

      $strNome = $objUsuarioDTO->getStrNome();

      $objUsuarioDTO->setStrNomeRegistroCivil($strNome);

      if ($objUsuarioDTO->getStrNomeSocial()!=null){
        $objUsuarioDTO->setStrNome($objUsuarioDTO->getStrNomeSocial());
      }

      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      $objUsuarioBD->alterar($objUsuarioDTO);

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setStrSigla($objUsuarioDTO->getStrSigla());

      if ($objUsuarioDTO->getStrStaTipo()==self::$TU_EXTERNO_PENDENTE || $objUsuarioDTO->getStrStaTipo()==self::$TU_EXTERNO) {
        $objContatoDTO->setStrEmail($objUsuarioDTO->getStrSigla());
      }

      $objContatoDTO->setStrNome($strNome);
      $objContatoDTO->setStrNomeSocial($objUsuarioDTO->getStrNomeSocial());

      if ($objUsuarioDTO->isSetDblCpfContato()){
        $objContatoDTO->setDblCpf($objUsuarioDTO->getDblCpfContato());
      }

      if ($objUsuarioDTO->getStrStaTipo()==self::$TU_SIP && $objUsuarioDTO->isSetStrEmailContato()){
        $objContatoDTO->setStrEmail($objUsuarioDTO->getStrEmailContato());
      }

      if ($objUsuarioDTO->getNumIdOrgao()!=$objUsuarioDTOBanco->getNumIdOrgao() && $objUsuarioDTOBanco->getStrStaTipo()==UsuarioRN::$TU_SIP) {

        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->setBolExclusaoLogica(false);
        $objOrgaoDTO->retStrSigla();
        $objOrgaoDTO->retNumIdContato();
        $objOrgaoDTO->setNumIdOrgao($objUsuarioDTO->getNumIdOrgao());

        $objOrgaoRN = new OrgaoRN();
        $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

        if ($objOrgaoDTO == null) {
          throw new InfraException('Órgão não encontrado [' . $objUsuarioDTO->getNumIdOrgao() . '].');
        }

        $objContatoDTO->setNumIdContatoAssociado($objOrgaoDTO->getNumIdContato());
        $objContatoDTO->setNumIdTipoContato($this->obterTipoContatoUsuarios($objOrgaoDTO));
      }

      $objContatoDTO->setStrStaOperacao('REPLICACAO');

      $objContatoDTO->setNumIdContato($objUsuarioDTOBanco->getNumIdContato());

      $objContatoRN = new ContatoRN();
      $objContatoRN->alterarRN0323($objContatoDTO);

      //Auditoria
      
       $this->montarIndexacao($objUsuarioDTO);

      if ($objUsuarioDTO->getStrIdUsuarioFederacao()!=null){
        $objUsuarioFederacaoDTO = new UsuarioFederacaoDTO();
        $objUsuarioFederacaoDTO->setStrSigla($objUsuarioDTO->getStrSigla());
        $objUsuarioFederacaoDTO->setStrNome($objUsuarioDTO->getStrNome());
        $objUsuarioFederacaoDTO->setStrIdUsuarioFederacao($objUsuarioDTO->getStrIdUsuarioFederacao());

        $objUsuarioFederacaoRN = new UsuarioFederacaoRN();
        $objUsuarioFederacaoRN->alterar($objUsuarioFederacaoDTO);
      }
       

    }catch(Exception $e){
      throw new InfraException('Erro alterando Usuário.',$e);
    }
  }
  
  protected function montarIndexacaoControlado(UsuarioDTO $parObjUsuarioDTO){
  	try{
  	  
	  	$objUsuarioDTO = new UsuarioDTO();
	  	$objUsuarioDTO->retNumIdUsuario();
	  	$objUsuarioDTO->retStrSigla();
	  	$objUsuarioDTO->retStrNomeRegistroCivil();
      $objUsuarioDTO->retStrNomeSocial();
      $objUsuarioDTO->setBolExclusaoLogica(false);

      if (is_array($parObjUsuarioDTO->getNumIdUsuario())){
        $objUsuarioDTO->setNumIdUsuario($parObjUsuarioDTO->getNumIdUsuario(),InfraDTO::$OPER_IN);
      }else{
        $objUsuarioDTO->setNumIdUsuario($parObjUsuarioDTO->getNumIdUsuario());
      }

      $objInfraException = new InfraException();
      $objUsuarioDTOIdx = new UsuarioDTO();
      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());

      $arrObjUsuarioDTO = $this->listarRN0490($objUsuarioDTO);

      foreach($arrObjUsuarioDTO as $objUsuarioDTO) {

        $objUsuarioDTOIdx->setStrIdxUsuario(InfraString::prepararIndexacao($objUsuarioDTO->getStrSigla().' '.$objUsuarioDTO->getStrNomeRegistroCivil().' '.$objUsuarioDTO->getStrNomeSocial()));
        $objUsuarioDTOIdx->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());

        $this->validarStrIdxUsuario($objUsuarioDTOIdx, $objInfraException);
        $objInfraException->lancarValidacoes();

        $objUsuarioBD->alterar($objUsuarioDTOIdx);
      }

    }catch(Exception $e){
      throw new InfraException('Erro montando indexação de Usuário.',$e);
    }	  	
  }

  protected function excluirRN0491Controlado($arrObjUsuarioDTO){
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_externo_excluir',__METHOD__,$arrObjUsuarioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objAtividadeRN = new AtividadeRN();
      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retNumIdAtividade();
      $objAtividadeDTO->retStrSiglaUsuarioOrigem();
      $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

      $objServicoRN = new ServicoRN();
      $objServicoDTO = new ServicoDTO();
      $objServicoDTO->retNumIdServico();
      $objServicoDTO->retStrSiglaUsuario();
      $objServicoDTO->setNumMaxRegistrosRetorno(1);

      for ($i=0;$i<count($arrObjUsuarioDTO);$i++){
        $objAtividadeDTO->setNumIdUsuarioOrigem($arrObjUsuarioDTO[$i]->getNumIdUsuario());
        if (($objAtividadeDTORet = $objAtividadeRN->consultarRN0033($objAtividadeDTO))!=null){
          $objInfraException->lancarValidacao('Existem andamentos associados com o usuário '.$objAtividadeDTORet->getStrSiglaUsuarioOrigem().'.');
        }

        $objServicoDTO->setNumIdUsuario($arrObjUsuarioDTO[$i]->getNumIdUsuario());
        if (($objServicoDTORet = $objServicoRN->consultar($objServicoDTO))!=null){
          $objInfraException->lancarValidacao('Existem serviços associados com o sistema '.$objServicoDTORet->getStrSiglaUsuario().'.');
        }
      }
      
      $objInfraException->lancarValidacoes();

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->retStrSinAtivo();
      $objUsuarioDTO->setNumIdUsuario(InfraArray::converterArrInfraDTO($arrObjUsuarioDTO,'IdUsuario'),InfraDTO::$OPER_IN);

      $arrObjUsuarioDTOConsulta = $this->listarRN0490($objUsuarioDTO);

      $arrObjUsuarioAPI = array();
      foreach($arrObjUsuarioDTOConsulta as $objUsuarioDTO){
        $objUsuarioAPI = new UsuarioAPI();
        $objUsuarioAPI->setIdUsuario($objUsuarioDTO->getNumIdUsuario());
        $objUsuarioAPI->setSigla($objUsuarioDTO->getStrSigla());
        $objUsuarioAPI->setNome($objUsuarioDTO->getStrNome());
        $arrObjUsuarioAPI[] = $objUsuarioAPI;
      }

      foreach($SEI_MODULOS as $seiModulo){
        $seiModulo->executar('excluirUsuario', $arrObjUsuarioAPI);
      }

      $arrNumIdContato = InfraArray::converterArrInfraDTO($arrObjUsuarioDTOConsulta,'IdContato');

      $objInfraDadoUsuario = new InfraDadoUsuario(SessaoSEI::getInstance());
      for($i=0;$i<count($arrObjUsuarioDTO);$i++){
        $objInfraDadoUsuario->removerValoresUsuario($arrObjUsuarioDTO[$i]->getNumIdUsuario());
      }

      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjUsuarioDTO);$i++){
        $objUsuarioBD->excluir($arrObjUsuarioDTO[$i]);
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
      throw new InfraException('Erro excluindo Usuário.',$e);
    }
  }

  protected function consultarRN0489Conectado(UsuarioDTO $objUsuarioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_consultar',__METHOD__,$objUsuarioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      $ret = $objUsuarioBD->consultar($objUsuarioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Usuário.',$e);
    }
  }

  protected function listarRN0490Conectado(UsuarioDTO $objUsuarioDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_listar',__METHOD__,$objUsuarioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      $ret = $objUsuarioBD->listar($objUsuarioDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Usuários.',$e);
    }
  }

  protected function bloquearControlado(UsuarioDTO $objUsuarioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_consultar',__METHOD__,$objUsuarioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      $ret = $objUsuarioBD->bloquear($objUsuarioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Usuário.',$e);
    }
  }
  
  protected function pesquisarConectado(UsuarioDTO $objUsuarioDTO) {
    try {

      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_listar',__METHOD__,$objUsuarioDTO);
      /////////////////////////////////////////////////////////////////

      $objUsuarioDTO = InfraString::prepararPesquisaDTO($objUsuarioDTO,"Sigla");
      $objUsuarioDTO = InfraString::prepararPesquisaDTO($objUsuarioDTO,"Nome");
      $objUsuarioDTO = InfraString::prepararPesquisaDTO($objUsuarioDTO,"NomeRegistroCivil");
      $objUsuarioDTO = InfraString::prepararPesquisaDTO($objUsuarioDTO,"NomeSocial");
      $objUsuarioDTO = InfraString::prepararPesquisaDTO($objUsuarioDTO,"PalavrasPesquisa","IdxUsuario");

      return $this->listarRN0490($objUsuarioDTO);

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando Usuários.',$e);
    }
  }

  protected function contarRN0492Conectado(UsuarioDTO $objUsuarioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_listar',__METHOD__,$objUsuarioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      $ret = $objUsuarioBD->contar($objUsuarioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Usuários.',$e);
    }
  }

  protected function desativarRN0695Controlado($arrObjUsuarioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_desativar',__METHOD__,$arrObjUsuarioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraDadoUsuario = new InfraDadoUsuario(SessaoSEI::getInstance());
      for($i=0;$i<count($arrObjUsuarioDTO);$i++){
        $objInfraDadoUsuario->removerValoresUsuario($arrObjUsuarioDTO[$i]->getNumIdUsuario());
      }

      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjUsuarioDTO); $i++) {
        $objUsuarioBD->desativar($arrObjUsuarioDTO[$i]);
      }

      /*

      //nao desativar contatos associados (deixar disponível para escolha como interessados)

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->setNumIdUsuario(InfraArray::converterArrInfraDTO($arrObjUsuarioDTO,'IdUsuario'), InfraDTO::$OPER_IN);
      $objContatoRN = new ContatoRN();
      $objContatoRN->desativarRN0451(InfraArray::gerarArrInfraDTO('ContatoDTO', 'IdContato', InfraArray::converterArrInfraDTO($this->listarRN0490($objUsuarioDTO), 'IdContato')));
      */

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Usuário.',$e);
    }
  }

  protected function reativarRN0696Controlado($arrObjUsuarioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_reativar',__METHOD__,$arrObjUsuarioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjUsuarioDTO);$i++){
        $objUsuarioBD->reativar($arrObjUsuarioDTO[$i]);
      }

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->setNumIdUsuario(InfraArray::converterArrInfraDTO($arrObjUsuarioDTO,'IdUsuario'), InfraDTO::$OPER_IN);
      $objContatoRN = new ContatoRN();
      $objContatoRN->reativarRN0452(InfraArray::gerarArrInfraDTO('ContatoDTO', 'IdContato', InfraArray::converterArrInfraDTO($this->listarRN0490($objUsuarioDTO), 'IdContato')));

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Usuário.',$e);
    }
  }

  private function validarStrStaTipo(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getStrStaTipo())){
      $objInfraException->adicionarValidacao('Tipo do usuário não informado.');
    }else{
      if (!in_array($objUsuarioDTO->getStrStaTipo(), array(self::$TU_SIP, self::$TU_SISTEMA, self::$TU_EXTERNO_PENDENTE, self::$TU_EXTERNO))){
        $objInfraException->adicionarValidacao('Tipo do usuário inválido.');
      }
    }
  }

  private function validarStrSinAcessibilidade(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getStrSinAcessibilidade())){
      $objInfraException->adicionarValidacao('Sinalizador de Acessibilidade não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objUsuarioDTO->getStrSinAcessibilidade())){
        $objInfraException->adicionarValidacao('Sinalizador de Acessibilidade inválido.');
      }
    }
  }

  private function validarStrSinAtivoRN0694(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objUsuarioDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  public function listarPorUnidadeRN0812($parObjUnidadeDTO){

    $ret = array();

    $strChave = 'SEI_U_'.CacheSEI::getInstance()->getAtributoVersao('SEI_U').'_'.$parObjUnidadeDTO->getNumIdUnidade();

    $arrCache = CacheSEI::getInstance()->getAtributo($strChave);

    if ($arrCache == null) {

      $objInfraSip = new InfraSip(SessaoSEI::getInstance());
      $arr = $objInfraSip->carregarUsuarios(SessaoSEI::getInstance()->getNumIdSistema(), $parObjUnidadeDTO->getNumIdUnidade(), 'procedimento_trabalhar');

      $temp = array_values($arr);

      InfraArray::ordenarArray($temp, InfraSip::$WS_USUARIO_SIGLA, InfraArray::$TIPO_ORDENACAO_ASC);

      $arrCache = array();
      foreach($temp as $usu){
        if ($usu[InfraSip::$WS_USUARIO_SIN_ATIVO] == 'S') {
          $arrCache[] = array($usu[InfraSip::$WS_USUARIO_ID], $usu[InfraSip::$WS_USUARIO_SIGLA], $usu[InfraSip::$WS_USUARIO_NOME]);
        }
      }

      CacheSEI::getInstance()->setAtributo($strChave, $arrCache, CacheSEI::getInstance()->getNumTempo());
    }

    foreach ($arrCache as $dados) {
      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setNumIdUsuario($dados[0]);
      $objUsuarioDTO->setStrSigla($dados[1]);
      $objUsuarioDTO->setStrNome($dados[2]);
      $ret[] = $objUsuarioDTO;
    }

    return $ret;
  }

  private function validarStrSiglaRN0955(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objUsuarioDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }else{
      $objUsuarioDTO->setStrSigla(trim($objUsuarioDTO->getStrSigla()));
  
      if (strlen($objUsuarioDTO->getStrSigla())>100){
        $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 100 caracteres.');
        return;
      }
      
      $dto = new UsuarioDTO();
      $dto->retNumIdUsuario();
      $dto->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario(),InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdOrgao($objUsuarioDTO->getNumIdOrgao());
      $dto->setStrSigla($objUsuarioDTO->getStrSigla());
      $dto = $this->consultarRN0489($dto);
      if ($dto!=null){
      	$objInfraException->adicionarValidacao('Já existe registro para o usuário "'.$objUsuarioDTO->getStrSigla().'".');
      	return;
      }    
    }
    
  }
  
  private function validarNumIdOrgao(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getNumIdOrgao())){    
      $objInfraException->adicionarValidacao('Órgão do usuário não informado.');
    }
  }
  
  private function validarStrNome(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }

    if (strlen($objUsuarioDTO->getStrNome())>100){
      $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
    }
  }

  private function validarStrNomeSocial(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getStrNomeSocial())){
      $objUsuarioDTO->setStrNomeSocial(null);
    }

    if (strlen($objUsuarioDTO->getStrNomeSocial())>100){
      $objInfraException->adicionarValidacao('Nome Social possui tamanho superior a 100 caracteres.');
    }

    if ($objUsuarioDTO->getStrNomeSocial()==$objUsuarioDTO->getStrNome()){
      $objInfraException->adicionarValidacao('Nome Social igual ao Nome do usuário.');
    }
  }

  private function validarStrIdxUsuario(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getStrIdxUsuario())){
      $objUsuarioDTO->setStrIdxUsuario(null);
    }else{
      $objUsuarioDTO->setStrIdxUsuario(trim($objUsuarioDTO->getStrIdxUsuario()));

      if (strlen($objUsuarioDTO->getStrIdxUsuario())>500){
        $objInfraException->adicionarValidacao('Indexação possui tamanho superior a 500 caracteres.');
      }
    }
  }

  private function validarSenha($strSenha){

    $objInfraException = new InfraException();

    if (InfraString::isBolVazia($strSenha)){
      $objInfraException->lancarValidacao('Senha não informada.');
    }

    $numTamSenhaUsuarioExterno = ConfiguracaoSEI::getInstance()->getValor('SEI', 'TamSenhaUsuarioExterno', false, TAM_SENHA_USUARIO_EXTERNO);

    if (strlen($strSenha) < $numTamSenhaUsuarioExterno){
      $objInfraException->lancarValidacao('Senha deve ser maior que '.$numTamSenhaUsuarioExterno.' caracteres.');
    }

    if(!preg_match("#[0-9]+#", $strSenha)) {
      $objInfraException->lancarValidacao("Senha deve conter pelo menos um número.");
    }

    if(is_numeric($strSenha)) {
      $objInfraException->lancarValidacao("Senha deve conter pelo menos uma letra.");
    }

  }
  
  private function validarStrIdOrigem(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    //
  }

  public function gerarSenha(UsuarioDTO $parObjUsuarioDTO){
    try{

      MailSEI::getInstance()->limpar();

      $this->gerarSenhaInterno($parObjUsuarioDTO);

      MailSEI::getInstance()->enviar();

    } catch(Exception $e){
      throw new InfraException('Erro gerando nova senha para usuário externo.',$e);
    }
  }

  protected function gerarSenhaInternoControlado(UsuarioDTO $parObjUsuarioDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_externo_gerar_senha',__METHOD__,$parObjUsuarioDTO);
      
      //Valida Permissao
      $objInfraException = new InfraException();  
      
    	$objUsuarioDTO = new UsuarioDTO();
  		$objUsuarioDTO->retNumIdUsuario();
  		$objUsuarioDTO->retStrSigla();
  		$objUsuarioDTO->retStrNome();
  		$objUsuarioDTO->retStrStaTipo();
  		$objUsuarioDTO->retNumIdOrgao();
  		$objUsuarioDTO->retStrSiglaOrgao();
  		$objUsuarioDTO->retStrDescricaoOrgao();
  		$objUsuarioDTO->retStrSitioInternetOrgaoContato();
    	$objUsuarioDTO->setStrSigla($parObjUsuarioDTO->getStrSigla());
    	$objUsuarioDTO->setStrStaTipo(array(self::$TU_EXTERNO_PENDENTE,self::$TU_EXTERNO),InfraDTO::$OPER_IN);
    	
  		$objUsuarioDTO = $this->consultarRN0489($objUsuarioDTO);
  		
  		if ($objUsuarioDTO==null) {
  			$objInfraException->lancarValidacao('Usuário não encontrado.');
  		}
  		
  		if ($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_EXTERNO_PENDENTE){
  			$objInfraException->lancarValidacao('Cadastro do usuário pendente.');
  		}
  		
  		if ($objUsuarioDTO->getStrStaTipo()!=UsuarioRN::$TU_EXTERNO){
  			$objInfraException->lancarValidacao('Usuário não é externo.');
  		}
  		
  		$numCaracteresNovaSenha = ConfiguracaoSEI::getInstance()->getValor('SEI', 'TamSenhaUsuarioExterno', false, TAM_SENHA_USUARIO_EXTERNO);
  		$strNovaSenha = '';
  		for($i=0;$i<$numCaracteresNovaSenha;$i++){
  		  $num = rand(0,61);
  		  if ($num < 10) { 
  		    $strNovaSenha .= $num;//caracter numerico
  		  }else if ($num < 36) {
  		    $strNovaSenha .= chr($num+55); //caracter maiusculo
  		  } else {
  		    $strNovaSenha .= chr($num+61); //caracter minusculo
  		  }		  
      }

      $bcrypt=new InfraBcrypt();

      $dto = new UsuarioDTO();
      $dto->setStrSenha($bcrypt->hash(md5($strNovaSenha)));
      $dto->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
		  
      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      $objUsuarioBD->alterar($dto);		  
		
      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
		  
			$objEmailSistemaDTO = new EmailSistemaDTO();
			$objEmailSistemaDTO->retStrDe();
			$objEmailSistemaDTO->retStrPara();
			$objEmailSistemaDTO->retStrAssunto();
			$objEmailSistemaDTO->retStrConteudo();
			$objEmailSistemaDTO->setNumIdEmailSistema(EmailSistemaRN::$ES_GERACAO_SENHA_USUARIO_EXTERNO);
			
			$objEmailSistemaRN = new EmailSistemaRN();
			$objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);
      
			if ($objEmailSistemaDTO!=null){
  		  $strDe = $objEmailSistemaDTO->getStrDe();
  		  $strDe = str_replace('@sigla_sistema@',SessaoSEI::getInstance()->getStrSiglaSistema(),$strDe);
  		  $strDe = str_replace('@email_sistema@',$objInfraParametro->getValor('SEI_EMAIL_SISTEMA'),$strDe);
  		  
  		  $strPara = $objEmailSistemaDTO->getStrPara();
  		  $strPara = str_replace('@email_usuario_externo@',$objUsuarioDTO->getStrSigla(),$strPara);
  				  
  		  $strAssunto = $objEmailSistemaDTO->getStrAssunto();
        $strAssunto = str_replace('@sigla_sistema@',SessaoSEI::getInstance()->getStrSiglaSistema(),$strAssunto);
        $strAssunto = str_replace('@sigla_orgao@',$objUsuarioDTO->getStrSiglaOrgao(),$strAssunto);

  		  $strConteudo = $objEmailSistemaDTO->getStrConteudo();
  		  $strConteudo = str_replace('@nome_usuario_externo@',$objUsuarioDTO->getStrNome(),$strConteudo);
  		  $strConteudo = str_replace('@email_usuario_externo@',$objUsuarioDTO->getStrSigla(),$strConteudo);
  		  $strConteudo = str_replace('@link_login_usuario_externo@',ConfiguracaoSEI::getInstance()->getValor('SEI','URL').'/controlador_externo.php?acao=usuario_externo_logar&id_orgao_acesso_externo='.$objUsuarioDTO->getNumIdOrgao(),$strConteudo);
        $strConteudo = str_replace('@sigla_orgao@',$objUsuarioDTO->getStrSiglaOrgao(),$strConteudo);
        $strConteudo = str_replace('@descricao_orgao@',$objUsuarioDTO->getStrDescricaoOrgao(),$strConteudo);
        $strConteudo = str_replace('@sitio_internet_orgao@',$objUsuarioDTO->getStrSitioInternetOrgaoContato(),$strConteudo);
        $strConteudo = str_replace('@nova_senha_usuario_externo@',$strNovaSenha,$strConteudo);

        $objEmailDTO = new EmailDTO();
        $objEmailDTO->setStrDe($strDe);
        $objEmailDTO->setStrPara($strPara);
        $objEmailDTO->setStrAssunto($strAssunto);
        $objEmailDTO->setStrMensagem($strConteudo);

        MailSEI::getInstance()->adicionar($objEmailDTO);
			}
			      
    } catch(Exception $e){
      throw new InfraException('Erro gerando nova senha para usuário externo.',$e);
    }
  }

  protected function alterarSenhaControlado(UsuarioDTO $parObjUsuarioDTO){
    try {

      //Valida Permissao
      $parObjUsuarioDTOAuditoria = clone($parObjUsuarioDTO);
      $parObjUsuarioDTOAuditoria->unSetStrSenha();
      $parObjUsuarioDTOAuditoria->unSetStrSenhaNova();
      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_externo_alterar_senha',__METHOD__,$parObjUsuarioDTOAuditoria);
      
      $objInfraException = new InfraException();  

    	$objUsuarioDTO = new UsuarioDTO();
  		$objUsuarioDTO->retNumIdUsuario();
  		$objUsuarioDTO->retStrStaTipo();
  		$objUsuarioDTO->retStrSenha();
      $objUsuarioDTO->setNumIdUsuario($parObjUsuarioDTO->getNumIdUsuario());

  		$objUsuarioDTO = $this->consultarRN0489($objUsuarioDTO);
  		
  		if ($objUsuarioDTO==null) {
  			$objInfraException->lancarValidacao('Usuário não encontrado.');
  		}
  		
  		if ($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_EXTERNO_PENDENTE){
  			$objInfraException->lancarValidacao('Cadastro do usuário pendente.');
  		}
  		
  		if ($objUsuarioDTO->getStrStaTipo()!=UsuarioRN::$TU_EXTERNO){
  			$objInfraException->lancarValidacao('Usuário não é externo.');
  		}
      $bcrypt = new InfraBcrypt();

      $senhaBanco=$objUsuarioDTO->getStrSenha();
      $senhaInformada=md5($parObjUsuarioDTO->getStrSenha());
      if (!$bcrypt->verificar($senhaInformada,$senhaBanco)) {
        $objInfraException->lancarValidacao('Senha Atual inválida.');
      }

      $this->validarSenha($parObjUsuarioDTO->getStrSenhaNova());

      $dto = new UsuarioDTO();
      $dto->setStrSenha($bcrypt->hash(md5($parObjUsuarioDTO->getStrSenhaNova())));
      $dto->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
		  
      $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
      $objUsuarioBD->alterar($dto);		  
      
    } catch(Exception $e){
      throw new InfraException('Erro alterando senha de usuário externo.',$e);
    }
  }

  private function obterTipoContatoUsuarios(OrgaoDTO $objOrgaoDTO){
    try {

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $numIdTipoContato = $objInfraParametro->getValor($objOrgaoDTO->getStrSigla() . '_ID_TIPO_CONTATO_USUARIOS',false);

      if (InfraString::isBolVazia($numIdTipoContato)) {

        $objTipoContatoDTO = new TipoContatoDTO();
        $objTipoContatoDTO->setNumIdTipoContato(null);
        $objTipoContatoDTO->setStrNome('Usuários ' . $objOrgaoDTO->getStrSigla());
        $objTipoContatoDTO->setStrDescricao(null);
        $objTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_RESUMIDA);
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

        $objInfraParametro->setValor($objOrgaoDTO->getStrSigla() . '_ID_TIPO_CONTATO_USUARIOS', $numIdTipoContato);
      }

      return $numIdTipoContato;

    } catch (Exception $e) {
      throw new InfraException('Erro obtendo tipo do contato associado com o usuário.', $e);
    }
  }

  protected function obterUsuariosRelacionadosConectado(UsuarioDTO $parObjUsuarioDTO){

    try{

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retStrIdOrigem();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->retStrStaTipo();
      $objUsuarioDTO->retDblCpfContato();
      $objUsuarioDTO->setNumIdContato($parObjUsuarioDTO->getNumIdContato());
      $objUsuarioDTO->setStrStaTipo(array(UsuarioRN::$TU_SIP, UsuarioRN::$TU_EXTERNO), InfraDTO::$OPER_IN);

      $objUsuarioRN = new UsuarioRN();
      $arrObjUsuarioDTO = $objUsuarioRN->listarRN0490($objUsuarioDTO);

      if (count($arrObjUsuarioDTO)){

        $arrIdOrigem = array();
        $arrCpfContato = array();
        $arrStaTipoContato = array();
        foreach($arrObjUsuarioDTO as $objUsuarioDTO){

          $arrStaTipoContato[$objUsuarioDTO->getStrStaTipo()] = true;

          if ($objUsuarioDTO->getStrIdOrigem()!=null){
            $arrIdOrigem[$objUsuarioDTO->getStrIdOrigem()] = true;
          }

          if ($objUsuarioDTO->getDblCpfContato()!=null){
            $arrCpfContato[$objUsuarioDTO->getDblCpfContato()] = true;
          }
        }

        $arrStaTipoContato = array_keys($arrStaTipoContato);
        $arrIdOrigem = array_keys($arrIdOrigem);
        $arrCpfContato = array_keys($arrCpfContato);

        $objUsuarioDTO2 = new UsuarioDTO();
        $objUsuarioDTO2->setBolExclusaoLogica(false);
        $objUsuarioDTO2->retNumIdUsuario();
        $objUsuarioDTO2->retNumIdContato();
        $objUsuarioDTO2->retStrSigla();
        $objUsuarioDTO2->retStrNome();
        $objUsuarioDTO2->retStrIdOrigem();
        $objUsuarioDTO2->retDblCpfContato();

        $objUsuarioDTO2->setStrStaTipo($arrStaTipoContato, InfraDTO::$OPER_IN);

        if (count($arrIdOrigem) && count($arrCpfContato)) {
          $objUsuarioDTO2->adicionarCriterio(array('IdOrigem','CpfContato'),
                                             array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IN),
                                             array($arrIdOrigem, $arrCpfContato),
                                             InfraDTO::$OPER_LOGICO_OR);
        } else if (count($arrIdOrigem)){
          $objUsuarioDTO2->setStrIdOrigem($arrIdOrigem, InfraDTO::$OPER_IN);
        } else if (count($arrCpfContato)) {
          $objUsuarioDTO2->setDblCpfContato($arrCpfContato, InfraDTO::$OPER_IN);
        } else {
          $objUsuarioDTO2->setNumIdContato($parObjUsuarioDTO->getNumIdContato());
        }

        $arrObjUsuarioDTO = $objUsuarioRN->listarRN0490($objUsuarioDTO2);
      }

      return $arrObjUsuarioDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro obtendo usuários relacionados.');
    }
  }

  protected function listarCargoFuncaoConectado(UsuarioDTO $parObjUsuarioDTO){

    $objUsuarioDTO = new UsuarioDTO();
    $objUsuarioDTO->setBolExclusaoLogica(false);
    $objUsuarioDTO->retNumIdUsuario();
    $objUsuarioDTO->retNumIdOrgao();
    $objUsuarioDTO->retStrSigla();
    $objUsuarioDTO->retStrIdOrigem();
    $objUsuarioDTO->retStrStaTipo();
    $objUsuarioDTO->retStrExpressaoCargoContato();
    $objUsuarioDTO->setNumIdUsuario($parObjUsuarioDTO->getNumIdUsuario());

    $objUsuarioDTO = $this->consultarRN0489($objUsuarioDTO);

    $arrCargoFuncao = array();

    if ($objUsuarioDTO!=null) {

      if ($objUsuarioDTO->getStrStaTipo() == UsuarioRN::$TU_SIP) {

        $objRelAssinanteUnidadeDTO = new RelAssinanteUnidadeDTO();
        $objRelAssinanteUnidadeDTO->retNumIdAssinante();
        $objRelAssinanteUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objRelAssinanteUnidadeRN = new RelAssinanteUnidadeRN();
        $arrObjRelAssinanteUnidadeDTO = $objRelAssinanteUnidadeRN->listarRN1380($objRelAssinanteUnidadeDTO);

        if (count($arrObjRelAssinanteUnidadeDTO) > 0) {
          $objAssinanteDTO = new AssinanteDTO();
          $objAssinanteDTO->retStrCargoFuncao();
          $objAssinanteDTO->setNumIdAssinante(InfraArray::converterArrInfraDTO($arrObjRelAssinanteUnidadeDTO, 'IdAssinante'), InfraDTO::$OPER_IN);

          $objAssinanteRN = new AssinanteRN();
          $arrCargoFuncao = InfraArray::converterArrInfraDTO($objAssinanteRN->listarRN1339($objAssinanteDTO),'CargoFuncao');
        }

        if ($objUsuarioDTO->getStrExpressaoCargoContato() != null) {
          $arrCargoFuncao[] = $objUsuarioDTO->getStrExpressaoCargoContato();
        }

        if (ConfiguracaoSEI::getInstance()->isSetValor('RH', 'CargoFuncao') && !InfraString::isBolVazia(ConfiguracaoSEI::getInstance()->getValor('RH', 'CargoFuncao'))) {

          if ($objUsuarioDTO->getStrIdOrigem() != null) {

            $objWS = null;

            try {

              $strWSDL = ConfiguracaoSEI::getInstance()->getValor('RH', 'CargoFuncao');

              if (!@file_get_contents($strWSDL)) {
                throw new InfraException('Falha na leitura do arquivo WSDL (' . $strWSDL . ')');
              }

              $objWS = new SoapClient($strWSDL, array('encoding' => 'ISO-8859-1'));

            } catch (Exception $e) {
              throw new InfraException('Falha na conexão com o sistema de RH.', $e);
            }

            try {
              $ret = $objWS->listarCargoFuncao($objUsuarioDTO->getNumIdOrgao(), $objUsuarioDTO->getStrIdOrigem(), $objUsuarioDTO->getStrSigla());
            } catch (Exception $e) {
              throw new InfraException('Erro obtendo dados de cargo/função do sistema de RH.', $e);
            }

            if ($ret != null && $ret->CargoFuncao != null) {

              if (!is_array($ret->CargoFuncao)) {
                $ret->CargoFuncao = array($ret->CargoFuncao);
              }

              foreach ($ret->CargoFuncao as $strCargoFuncao) {

                if (!in_array($strCargoFuncao, $arrCargoFuncao)) {
                  $arrCargoFuncao[] = $strCargoFuncao;
                }
              }
            }
          }
        }

        $arrCargoFuncao = array_unique($arrCargoFuncao);

        sort($arrCargoFuncao);

      } else if ($objUsuarioDTO->getStrStaTipo() == UsuarioRN::$TU_EXTERNO) {

        if ($objUsuarioDTO->getStrExpressaoCargoContato() != null) {
          $arrCargoFuncao[] = $objUsuarioDTO->getStrExpressaoCargoContato();
        }

        $arrCargoFuncao[] = 'Usuário Externo';
      }
    }

    return InfraArray::gerarArrInfraDTO('AssinanteDTO', 'CargoFuncao', $arrCargoFuncao);
  }

  private function validarStrNumeroPassaporte(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if(InfraString::isBolVazia($objUsuarioDTO->getStrNumeroPassaporte())){
      $objInfraException->adicionarValidacao(_('Número do Passaporte não informado.'));
    }else if (preg_match("/[^0-9A-Z-\s]/",$objUsuarioDTO->getStrNumeroPassaporte())){
      $objInfraException->adicionarValidacao(_('Número do Passaporte possui caracteres inválidos.'));
    }
  }

  private function validarStrSinEstrangeiro(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getStrSinEstrangeiro())){
      $objInfraException->adicionarValidacao(_('Sinalizador de usuário estrangeiro não informado.'));
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objUsuarioDTO->getStrSinEstrangeiro())){
        $objInfraException->adicionarValidacao(_('Sinalizador de usuário estrangeiro inválido.'));
      }
    }
  }

  private function validarNumIdPaisPassaporte(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getNumIdPaisPassaporte())){
      $objInfraException->adicionarValidacao(_('País de Emissão não selecionado.'));
    }
  }

  private function validarDblCpf(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if(!InfraUtil::validarCpf($objUsuarioDTO->getDblCpfContato())){
      $objInfraException->adicionarValidacao(_('Número de CPF inválido.'));
    }
  }

  private function validarDblRg(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getDblRgContato())){
      $objInfraException->adicionarValidacao(_('RG não informado.'));
    }
  }

  private function validarStrOrgaoExpedidor(UsuarioDTO $objUsuarioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUsuarioDTO->getStrOrgaoExpedidorContato())){
      $objInfraException->adicionarValidacao(_('Órgão Expedidor não informado.'));
    }else if (strlen($objUsuarioDTO->getStrOrgaoExpedidorContato()) > 50) {
      $objInfraException->adicionarValidacao(_('Órgão Expedidor possui tamanho superior a 50 caracteres.'));
    }
  }

  protected function gerarIdentificadorFederacaoControlado(UsuarioDTO $parObjUsuarioDTO) {
    try {

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->setNumIdUsuario($parObjUsuarioDTO->getNumIdUsuario());
      $objUsuarioDTO = $this->bloquear($objUsuarioDTO);

      if ($objUsuarioDTO->getStrIdUsuarioFederacao()==null){

        $strIdUsuarioFederacao = InfraULID::gerar();

        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();

        $objUsuarioFederacaoDTO = new UsuarioFederacaoDTO();
        $objUsuarioFederacaoDTO->setStrIdUsuarioFederacao($strIdUsuarioFederacao);
        $objUsuarioFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal());
        $objUsuarioFederacaoDTO->setStrSigla($objUsuarioDTO->getStrSigla());
        $objUsuarioFederacaoDTO->setStrNome($objUsuarioDTO->getStrNome());

        $objUsuarioFederacaoRN = new UsuarioFederacaoRN();
        $objUsuarioFederacaoRN->cadastrar($objUsuarioFederacaoDTO);
        
        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->setStrIdUsuarioFederacao($strIdUsuarioFederacao);
        $objUsuarioDTO->setNumIdUsuario($parObjUsuarioDTO->getNumIdUsuario());

        $objUsuarioBD = new UsuarioBD($this->getObjInfraIBanco());
        $objUsuarioBD->alterar($objUsuarioDTO);
      }

      $parObjUsuarioDTO->setStrIdUsuarioFederacao($objUsuarioDTO->getStrIdUsuarioFederacao());

    }catch(Exception $e){
      throw new InfraException('Erro gerando identificador do SEI Federação para o Usuário.',$e);
    }
  }

}
?>