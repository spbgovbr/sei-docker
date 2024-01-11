<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/04/2012 - criado por bcu
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();
 
  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////
	
  SessaoSEIExterna::getInstance()->validarLink();
  
  PaginaSEIExterna::getInstance()->setTipoPagina(PaginaSEIExterna::$TIPO_PAGINA_SEM_MENU);
  
  switch($_GET['acao']){
    
    case 'usuario_externo_gerar_senha':
  
      $strTitulo = 'Geração de Senha para Usuário Externo';
      
      //SessaoSEIExterna::getInstance()->validarPermissao($_GET['acao']);
      if (isset($_POST['sbmNovaSenha'])) {

      	$objUsuarioDTO = new UsuarioDTO();
      	$objUsuarioDTO->setStrSigla($_POST['txtEmail']);
      	
    		$objUsuarioRN = new UsuarioRN();
      	$objUsuarioRN->gerarSenha($objUsuarioDTO);
    		
    		PaginaSEIExterna::getInstance()->setStrMensagem('Uma nova senha foi gerada e enviada para o e-mail informado.');
    		header('Location: '.SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_logar&acao_origem='.$_GET['acao']));
    		die;
      }
      break;
      
    default:
       throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

}catch(Exception $e){
  PaginaSEIExterna::getInstance()->processarExcecao($e);
} 


PaginaSEIExterna::getInstance()->montarDocType();
PaginaSEIExterna::getInstance()->abrirHtml();
PaginaSEIExterna::getInstance()->abrirHead();
PaginaSEIExterna::getInstance()->montarMeta();
PaginaSEIExterna::getInstance()->montarTitle(PaginaSEIExterna::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEIExterna::getInstance()->montarStyle();
PaginaSEIExterna::getInstance()->abrirStyle();
?>

div.infraBarraSistemaE {width:80%;}
div.infraBarraSistemaD {width:15%;}

#lblEmail {position:absolute;top:40%;width:40%;}
#txtEmail {position:absolute;top:60%;width:40%;}

#sbmNovaSenha {width:12em;}
#btnVoltar {width:7em;}

<?
PaginaSEIExterna::getInstance()->fecharStyle();
PaginaSEIExterna::getInstance()->montarJavaScript();
PaginaSEIExterna::getInstance()->abrirJavaScript();
?>

function inicializar(){
  document.getElementById('txtEmail').focus();
  infraEfeitoTabelas();
}
function OnSubmitForm() {
  return validarForm();
}

function validarForm() {

  if (infraTrim(document.getElementById('txtEmail').value)=='') {
    alert('Informe o E-mail.');
    document.getElementById('txtEmail').focus();
    return false;
  }
  
  if (!infraValidarEmail(infraTrim(document.getElementById('txtEmail').value))){
	
		alert('E-mail Inválido.');
		document.getElementById('txtEmail').focus();
		return false;
	
	}

  return true;
}
<?
PaginaSEIExterna::getInstance()->fecharJavaScript();
PaginaSEIExterna::getInstance()->fecharHead();
PaginaSEIExterna::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGeracaoSenha" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao='.$_GET['acao'])?>">
<?
PaginaSEIExterna::getInstance()->abrirAreaDados('10em');
?>	
  <label id="lblEmail" for="txtEmail" accesskey="" class="infraLabelObrigatorio">E-mail:</label>
  <input type="email" required="true" id="txtEmail" name="txtEmail" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtEmail'])?>" maxlength="100" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />
<?
PaginaSEIExterna::getInstance()->fecharAreaDados();

?>
<button type="submit" id="sbmNovaSenha" name="sbmNovaSenha" accesskey="G" class="infraButton" value="Gerar nova senha" title="Gerar nova senha"><span class="infraTeclaAtalho">G</span>erar nova senha</button>&nbsp;&nbsp;
<button type="button" accesskey="V" id="btnVoltar" name="btnVoltar" value="Voltar" onclick="location.href='<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_logar&acao_origem='.$_GET['acao'])?>';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>
</form>
<?
PaginaSEIExterna::getInstance()->montarAreaDebug();
PaginaSEIExterna::getInstance()->fecharBody();
PaginaSEIExterna::getInstance()->fecharHtml();
?>