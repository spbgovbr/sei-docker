<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/07/2018 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/Sip.php';
		
  session_start(); 

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  foreach($_POST as $item){
    if (is_array($item)){
      die('Link inválido.');
    }
  }

  if (count($_GET) > 1){
    die('Link inválido.');
  }

  foreach($_GET as $chave => $valor){
    if (!in_array($chave, array('chave_ativacao','chave_desativacao','chave_bloqueio'))){
      die('Link inválido.');
    }

    if ($valor == '' || strlen($valor) > 154 || preg_match("/[^0-9a-zA-Z]/", $valor)) {
      die('Link inválido.');
    }
  }

  SessaoSip::getInstance(false)->simularLogin();

  $strMsg = '';

  if (isset($_GET['chave_ativacao'])) {

    $objCodigoAcessoDTO = new CodigoAcessoDTO();
    $objCodigoAcessoDTO->setStrChaveAtivacaoExterna($_GET['chave_ativacao']);

    $objCodigoAcessoRN = new CodigoAcessoRN();
    $objCodigoAcessoDTO = $objCodigoAcessoRN->confirmarAtivacao($objCodigoAcessoDTO);

    $strMsg = 'A autenticação em 2 fatores foi ativada.';

  }else if (isset($_GET['chave_desativacao'])){

    $objCodigoAcessoDTO = new CodigoAcessoDTO();
    $objCodigoAcessoDTO->setStrChaveDesativacaoExterna($_GET['chave_desativacao']);

    $objCodigoAcessoRN = new CodigoAcessoRN();
    $objCodigoAcessoDTO = $objCodigoAcessoRN->confirmarDesativacao($objCodigoAcessoDTO);

    $strMsg = 'A autenticação em 2 fatores foi desativada.';

  }else if (isset($_GET['chave_bloqueio'])){

    $objCodigoAcessoDTO = new CodigoAcessoDTO();
    $objCodigoAcessoDTO->setStrChaveBloqueioExterna($_GET['chave_bloqueio']);

    $objCodigoAcessoRN = new CodigoAcessoRN();
    $objCodigoAcessoDTO = $objCodigoAcessoRN->confirmarBloqueioUsuario($objCodigoAcessoDTO);

    $strMsg = 'Conta de usuário bloqueada no sistema.';
  }

  PaginaLogin::getInstance()->setObjSistemaDTO(LoginINT::obterSistema($objCodigoAcessoDTO->getStrSiglaSistema(), $objCodigoAcessoDTO->getStrSiglaOrgaoSistema()));

}catch(Exception $e){
  if ($e instanceof InfraException && $e->contemValidacoes()){
    $strMsg = $e->__toString();
  }else {
    try {
      LogSip::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e2) {}
    PaginaLogin::getInstance()->processarExcecao($e);
  }
}
PaginaLogin::getInstance()->montarDocType();
PaginaLogin::getInstance()->abrirHtml();
PaginaLogin::getInstance()->abrirHead();
PaginaLogin::getInstance()->montarMeta();
PaginaLogin::getInstance()->montarTitle('Autenticação em 2 Fatores');
PaginaLogin::getInstance()->montarStyle();
PaginaLogin::getInstance()->montarJavaScript();
PaginaLogin::getInstance()->fecharHead();
PaginaLogin::getInstance()->abrirBody();
?>
<div class="infraAreaValidacao"><?=$strMsg?><div>
<?
PaginaLogin::getInstance()->montarAreaDebug();
PaginaLogin::getInstance()->fecharBody();
PaginaLogin::getInstance()->fecharHtml();
?>
