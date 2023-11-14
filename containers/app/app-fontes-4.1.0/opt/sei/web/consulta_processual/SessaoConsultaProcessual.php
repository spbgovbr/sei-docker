<?

/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*/

require_once dirname(__FILE__) . '/../SEI.php';

class SessaoConsultaProcessual extends InfraSessao {

  private static $instance = null;
  private static $objOrgaoDTO = null;

  public static function getInstance() {
    if (self::$instance == null) {

      BancoSEI::setBolReplica(true);

      SessaoSEI::getInstance(false, false);

      self::$instance = new SessaoConsultaProcessual(false, false);
    }
    return self::$instance;
  }

  private function gerarHash($chave) {
    return hash('SHA256', $chave.'#['.$this->getAtributo('CONSULTA_PROCESSUAL_HASH').']');
  }

  public function salvarDadosSessao(ConsultaProcessualDTO $objConsultaProcessualDTO) {
    $this->setAtributo('CONSULTA_PROCESSUAL_CRITERIO_TIPO', $objConsultaProcessualDTO->getStrStaCriterioPesquisa());
    $this->setAtributo('CONSULTA_PROCESSUAL_CRITERIO_VALOR', $objConsultaProcessualDTO->getStrValorPesquisa());
    $this->setAtributo('CONSULTA_PROCESSUAL_ORGAOS', $objConsultaProcessualDTO->getNumIdOrgaoUnidadeGeradora());
    $this->setAtributo('CONSULTA_PROCESSUAL_HASH', hash('SHA512', uniqid(mt_rand(), true) .'/'.$objConsultaProcessualDTO->__toString() .'/'. uniqid(mt_rand(), true)));
  }

  public function removerDadosSessao() {
    $this->removerAtributo('CONSULTA_PROCESSUAL_CRITERIO_TIPO');
    $this->removerAtributo('CONSULTA_PROCESSUAL_CRITERIO_VALOR');
    $this->removerAtributo('CONSULTA_PROCESSUAL_ORGAOS');
    $this->removerAtributo('CONSULTA_PROCESSUAL_HASH');
  }

  public function getStrSiglaOrgao() {
    return  self::$objOrgaoDTO!=null ? self::$objOrgaoDTO->getStrSigla() : $this->getStrSiglaOrgaoSistema();
  }

  public function getStrDescricaoOrgao() {
    return  self::$objOrgaoDTO!=null ? self::$objOrgaoDTO->getStrDescricao() : $this->getStrSiglaOrgaoSistema();
  }

  public function getStrSiglaOrgaoSistema() {
    return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI', 'SiglaOrgaoSistema');
  }

  public function getStrSiglaSistema() {
    return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI', 'SiglaSistema');
  }

  public function getStrPaginaLogin() {
    return null;
  }

  public function validarLink($strLink = null) {

    if (ConfiguracaoSEI::getInstance()->getValor('PaginaSEI','ConsultaProcessual',false,true)!==true){
      die (SeiINT::$MSG_PAGINA_DESABILITADA);
    }

    foreach ($_GET as $key => $item) {
      if ($item != '') {
        if (preg_match("/[^a-zA-Z0-9\-_,\/]/", $item)) {
          $this->lancarErro(__LINE__, 'Link da consulta processual inválido.', false);
        }
      }
    }

    if (trim($_GET['id_orgao']) == '') {
      $this->lancarErro(__LINE__, 'Link da consulta processual inválido.', false);
    }

    if (!is_numeric($_GET['id_orgao'])) {
      $this->lancarErro(__LINE__, 'Link da consulta processual inválido.', false);
    }

    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->retNumIdOrgao();
    $objOrgaoDTO->retStrSigla();
    $objOrgaoDTO->retStrDescricao();
    $objOrgaoDTO->setNumIdOrgao($_GET['id_orgao']);

    $objOrgaoRN = new OrgaoRN();
    if (($objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO)) == null) {
      $this->lancarErro(__LINE__, 'Link da consulta processual inválido.', false);
    }

    self::$objOrgaoDTO = $objOrgaoDTO;

    $strLink = $_SERVER['REQUEST_URI'];
    $strLink = urldecode($strLink);
    if (trim($strLink) == '') {
      $this->lancarErro(__LINE__, 'Link da consulta processual inválido.', false);
    }

    $numPosParam = strpos($strLink, '?');
    if ($numPosParam !== false) {
      $strParam = substr($strLink, $numPosParam + 1);
    }

    $numPosHash = strpos($strParam, '&hash=');
    if ($numPosHash === false) {
      $this->removerDadosSessao();
      header('Location: ' . $this->assinarLink($strLink, true));
      die;
    }

    $strHashLink = substr($strParam, $numPosHash + strlen('&hash='));
    $strParamSemHash = substr($strParam, 0, $numPosHash);

    $numTamHash = strlen($strHashLink);
    if ($numTamHash != 64) {
      $this->lancarErro(__LINE__, 'Link da consulta processual inválido.', false);
    }
    $strHashParamHashConsulta = self::gerarHash($strParamSemHash);

    if ($strHashParamHashConsulta != $strHashLink) {
      $this->removerDadosSessao();
      header('Location: ' . $this->assinarLink($strLink, true));
      die;
    }

    return true;
  }

  public function assinarLink($strLink, $bolMontarLinkBasico = false) {

    if ($bolMontarLinkBasico) {
      $strLink = 'controlador_consulta_processual.php?acao=consulta_processual_pesquisar&id_orgao=' . $_GET['id_orgao'];
    }

    $numPosParam = strpos($strLink, '?');

    if (strpos($strLink,'id_orgao=')===false){
      if (isset($_GET['id_orgao']) && $_GET['id_orgao']!=''){
        if (strpos($strLink,'?')===false){
          $strLink .= '?';
        }else{
          $strLink .= '&';
        }
        $strLink .= 'id_orgao='.$_GET['id_orgao'];
      }
    }

    if ($numPosParam !== false) {
      $strParam = substr($strLink, $numPosParam + 1);
      $strLink .= '&hash=' . self::gerarHash($strParam);
    }

    return $strLink;
  }

  private function lancarErro($numLinha, $strErro, $bolGravar){
    $this->removerDadosSessao();
    throw new InfraException($strErro, null, basename(__FILE__).' ['.$numLinha.']: '.$_SERVER['REQUEST_URI'], $bolGravar);
  }
}
