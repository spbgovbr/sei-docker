<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 12/11/2007 - criado por MGA
 *
 */
 
require_once dirname(__FILE__).'/SEI.php';
 
 class SessaoSEIFederacao extends InfraSessao {

  private $objInstalacaoFederacaoDTO = null;
  private $objUsuarioFederacaoDTO = null;
  private $objOrgaoFederacaoDTO = null;
  private $objUnidadeFederacaoDTO = null;

 	private static $instance = null;

 	public static function getInstance() {
	    if (self::$instance == null) {
	    	SessaoSEI::getInstance(false, false);
	    	self::$instance = new SessaoSEIFederacao();
      } 
	    return self::$instance; 
	}

	public function __construct(){
    parent::__construct(false, false);
	}
	
  public function getStrSiglaOrgaoSistema(){
		return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','SiglaOrgaoSistema');
	}
	
	public function getStrSiglaSistema(){
		return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','SiglaSistema');
	}
	
	public function getStrPaginaLogin(){
		die;
	}
	
  public function getStrSipWsdl(){
		return null;
  }
  
  public function logar(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO, OrgaoFederacaoDTO $objOrgaoFederacaoDTO, UnidadeFederacaoDTO $objUnidadeFederacaoDTO, UsuarioFederacaoDTO $objUsuarioFederacaoDTO){
    $this->objInstalacaoFederacaoDTO = $objInstalacaoFederacaoDTO;
 	  $this->objOrgaoFederacaoDTO = $objOrgaoFederacaoDTO;
    $this->objUnidadeFederacaoDTO = $objUnidadeFederacaoDTO;
    $this->objUsuarioFederacaoDTO = $objUsuarioFederacaoDTO;

    AuditoriaSEI::getInstance()->setStrComplemento($this->getStrAuditoria());
  }

  public function getStrSiglaInstalacaoFederacaoLocal(){
    $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
    return $objInstalacaoFederacaoRN->obterSiglaInstalacaoLocal();
  }

  public function getStrIdInstalacaoFederacao(){
   return $this->objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao();
  }
  
  public function getStrSiglaInstalacaoFederacao(){
   return $this->objInstalacaoFederacaoDTO->getStrSigla();
  }
  
  public function getStrDescricaoInstalacaoFederacao(){
   return $this->objInstalacaoFederacaoDTO->getStrDescricao();
  }

  public function getStrIdOrgaoFederacao(){
   return $this->objOrgaoFederacaoDTO->getStrIdOrgaoFederacao();
  }
  
  public function getStrSiglaOrgaoFederacao(){
   return $this->objOrgaoFederacaoDTO->getStrSigla();
  }
  
  public function getStrDescricaoOrgaoFederacao(){
   return $this->objOrgaoFederacaoDTO->getStrDescricao();
  }

  public function getStrIdUnidadeFederacao(){
   return $this->objUnidadeFederacaoDTO->getStrIdUnidadeFederacao();
  }
  
  public function getStrSiglaUnidadeFederacao(){
   return $this->objUnidadeFederacaoDTO->getStrSigla();
  }
  
  public function getStrDescricaoUnidadeFederacao(){
   return $this->objUnidadeFederacaoDTO->getStrDescricao();
  }

  public function getStrIdUsuarioFederacao(){
  	return $this->objUsuarioFederacaoDTO->getStrIdUsuarioFederacao();
  }

  public function getStrSiglaUsuarioFederacao(){
   return $this->objUsuarioFederacaoDTO->getStrSigla();
  }
  
  public function getStrNomeUsuarioFederacao(){
    return $this->objUsuarioFederacaoDTO->getStrNome();
  }

  public function validarPermissao($strNomeRecurso){
    return true;
  }

  public function validarLink($bolProcessandoAcao = true){

    if (count($_POST) > 0 || count($_GET) != 1){
      $this->lancarErro(__LINE__, 'Link federação inválido.', false);
    }

    foreach($_GET as $key => $item){
      if (is_array($item) || $key!='acao' || strlen($item) != 26 || preg_match("/[^0-9a-zA-Z]/", $item)){
        $this->lancarErro(__LINE__, 'Link federação inválido.', false);
      }
    }

    $objAcaoFederacaoDTO = new AcaoFederacaoDTO();
    $objAcaoFederacaoDTO->retNumStaTipo();
    $objAcaoFederacaoDTO->retDthGeracao();

    if ($bolProcessandoAcao) {
      $objAcaoFederacaoDTO->retStrIdAcaoFederacao();
      $objAcaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objAcaoFederacaoDTO->retStrIdOrgaoFederacao();
      $objAcaoFederacaoDTO->retStrIdUnidadeFederacao();
      $objAcaoFederacaoDTO->retStrIdUsuarioFederacao();
      $objAcaoFederacaoDTO->retStrIdProcedimentoFederacao();
      $objAcaoFederacaoDTO->retStrIdDocumentoFederacao();
      $objAcaoFederacaoDTO->retArrObjParametroAcaoFederacaoDTO();
    }

    $objAcaoFederacaoDTO->setStrIdAcaoFederacao($_GET['acao']);

    $objAcaoFederacaoRN = new AcaoFederacaoRN();
    $objAcaoFederacaoDTO = $objAcaoFederacaoRN->consultar($objAcaoFederacaoDTO);

    if ($objAcaoFederacaoDTO==null){
      // $this->lancarErro(__LINE__, 'Registro de Acesso ao SEI Federação não encontrado.', false);
      die;
    }

    $numSegundosAcaoRemota = ConfiguracaoSEI::getInstance()->getValor('Federacao', 'NumSegundosAcaoRemota', false, 10);

    if (!is_numeric($numSegundosAcaoRemota) || $numSegundosAcaoRemota < 0) {
      $numSegundosAcaoRemota = 10;
    }

    if (InfraData::compararDataHora($objAcaoFederacaoDTO->getDthGeracao(),InfraData::getStrDataHoraAtual()) > $numSegundosAcaoRemota){
      //$this->lancarErro(__LINE__, 'Registro de Acesso ao SEI Federação expirado.', false);
      die;
    }
    
    if ($bolProcessandoAcao){

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retStrSigla();
      $objInstalacaoFederacaoDTO->retStrDescricao();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($objAcaoFederacaoDTO->getStrIdInstalacaoFederacao());

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoDTO = $objInstalacaoFederacaoRN->consultar($objInstalacaoFederacaoDTO);

      if ($objInstalacaoFederacaoDTO==null){
        $this->lancarErro(__LINE__, 'Instalação do Acesso ao SEI Federação não encontrada.', false);
      }

      $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
      $objOrgaoFederacaoDTO->retStrIdOrgaoFederacao();
      $objOrgaoFederacaoDTO->retStrSigla();
      $objOrgaoFederacaoDTO->retStrDescricao();
      $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($objAcaoFederacaoDTO->getStrIdOrgaoFederacao());

      $objOrgaoFederacaoRN = new OrgaoFederacaoRN();
      $objOrgaoFederacaoDTO = $objOrgaoFederacaoRN->consultar($objOrgaoFederacaoDTO);

      if ($objOrgaoFederacaoDTO==null){
        $this->lancarErro(__LINE__, 'Órgão do Acesso ao SEI Federação não encontrado.', false);
      }

      $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
      $objUnidadeFederacaoDTO->retStrIdUnidadeFederacao();
      $objUnidadeFederacaoDTO->retStrSigla();
      $objUnidadeFederacaoDTO->retStrDescricao();
      $objUnidadeFederacaoDTO->setStrIdUnidadeFederacao($objAcaoFederacaoDTO->getStrIdUnidadeFederacao());

      $objUnidadeFederacaoRN = new UnidadeFederacaoRN();
      $objUnidadeFederacaoDTO = $objUnidadeFederacaoRN->consultar($objUnidadeFederacaoDTO);

      if ($objUnidadeFederacaoDTO==null){
        $this->lancarErro(__LINE__, 'Unidade do Acesso ao SEI Federação não encontrada.', false);
      }

      $objUsuarioFederacaoDTO = new UsuarioFederacaoDTO();
      $objUsuarioFederacaoDTO->retStrIdUsuarioFederacao();
      $objUsuarioFederacaoDTO->retStrSigla();
      $objUsuarioFederacaoDTO->retStrNome();
      $objUsuarioFederacaoDTO->setStrIdUsuarioFederacao($objAcaoFederacaoDTO->getStrIdUsuarioFederacao());

      $objUsuarioFederacaoRN = new UsuarioFederacaoRN();
      $objUsuarioFederacaoDTO = $objUsuarioFederacaoRN->consultar($objUsuarioFederacaoDTO);

      if ($objUsuarioFederacaoDTO==null){
        $this->lancarErro(__LINE__, 'Usuário do Acesso ao SEI Federação não encontrado.', false);
      }

      self::logar($objInstalacaoFederacaoDTO, $objOrgaoFederacaoDTO, $objUnidadeFederacaoDTO, $objUsuarioFederacaoDTO);

      $objAcaoFederacaoDTOAlteracao = new AcaoFederacaoDTO();
      $objAcaoFederacaoDTOAlteracao->setDthAcesso(InfraData::getStrDataHoraAtual());
      $objAcaoFederacaoDTOAlteracao->setStrSinAtivo('N');
      $objAcaoFederacaoDTOAlteracao->setStrIdAcaoFederacao($objAcaoFederacaoDTO->getStrIdAcaoFederacao());
      $objAcaoFederacaoRN->alterar($objAcaoFederacaoDTOAlteracao);
    }

    return $objAcaoFederacaoDTO;
  }

  private function lancarErro($numLinha, $strErro, $bolGravar){
    throw new InfraException($strErro, null, basename(__FILE__).' ['.$numLinha.']: '.$_SERVER['REQUEST_URI'], $bolGravar);
  }

  private function getStrAuditoria() {
    return "\n\n".__CLASS__.":\n".
          'IdInstalacaoFederacao = '.$this->getStrIdInstalacaoFederacao()."\n".
          'SiglaInstalacaoFederacao = '.$this->getStrSiglaInstalacaoFederacao()."\n".
          'IdOrgaoFederacao = '.$this->getStrIdOrgaoFederacao()."\n".
          'SiglaOrgaoFederacao = '.$this->getStrSiglaOrgaoFederacao()."\n".
          'IdUnidadeFederacao = '.$this->getStrIdUnidadeFederacao()."\n".
          'SiglaUnidadeFederacao = '.$this->getStrSiglaUnidadeFederacao()."\n".
          'IdUsuarioFederacao = '.$this->getStrIdUsuarioFederacao()."\n".
          'SiglaUsuarioFederacao = '.$this->getStrSiglaUsuarioFederacao()."\n";
  }
}
?>