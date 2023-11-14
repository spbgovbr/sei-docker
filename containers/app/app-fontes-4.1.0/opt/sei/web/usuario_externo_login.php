<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/06/2010 - criado por fazenda_db
*
*
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();
 
  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  if (ConfiguracaoSEI::getInstance()->getValor('PaginaSEI','UsuariosExternos',false,true)!==true){
    die (SeiINT::$MSG_PAGINA_DESABILITADA);
  }

  SessaoSEIExterna::getInstance()->validarLink();

  global $SEI_MODULOS;

  PaginaSEIExterna::getInstance()->setTipoPagina(PaginaSEIExterna::$TIPO_PAGINA_SEM_MENU);

  $numLoginSemCaptcha = ConfiguracaoSEI::getInstance()->getValor('SEI', 'NumLoginUsuarioExternoSemCaptcha', false, 3);

  if (!isset($_SESSION['EXTERNO_NUM_FALHA_LOGIN'])){
    $_SESSION['EXTERNO_NUM_FALHA_LOGIN'] = 0;
  }

  switch($_GET['acao']){
    
      case 'usuario_externo_logar':
  
        $strTitulo = 'Acesso Externo';

        CaptchaSEI::getInstance()->configurarCaptcha('Login de Usuário Externo');

        if (isset($_POST['sbmLogin']) || (isset($_POST['hdnInfraCaptcha']) && $_POST['hdnInfraCaptcha']=='1')) {
          try {

            $objInfraException = new InfraException();

            if ($_SESSION['EXTERNO_NUM_FALHA_LOGIN'] >= $numLoginSemCaptcha && !CaptchaSEI::getInstance()->verificar()) {
              $objInfraException->lancarValidacao('Desafio não foi resolvido.');
            } else {

              $objUsuarioDTO = new UsuarioDTO();
              $objUsuarioDTO->setStrSigla($_POST['txtEmail']);
              SessaoSEIExterna::getInstance()->logar($objUsuarioDTO);
              $_SESSION['EXTERNO_NUM_FALHA_LOGIN'] = 0;
              header('Location: ' . SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_controle_acessos&acao_origem=' . $_GET['acao']));
              die;
            }
          } catch (Exception $e) {
            if (strpos($e->__toString(), InfraLDAP::$MSG_USUARIO_SENHA_INVALIDA) !== false) {
              $_SESSION['EXTERNO_NUM_FALHA_LOGIN'] = $_SESSION['EXTERNO_NUM_FALHA_LOGIN'] + 1;
            }
             PaginaSEIExterna::getInstance()->processarExcecao($e, true);
          }
        }
        break;
        
     default:
       throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  global $SEI_MODULOS;

  $strBotoesModulos = '';
  foreach($SEI_MODULOS as $seiModulo){
    if (($strBotaoModulo=$seiModulo->executar('montarBotaoLoginExterno'))!=null){
      $strBotoesModulos .= $strBotaoModulo;
    }
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
CaptchaSEI::getInstance()->montarStyle();
PaginaSEIExterna::getInstance()->abrirStyle();

?>

body{
  background-color: #f0f0f0;
}

.divInfraAreaTelaD{
  padding-left: 0px !important;
}

#divAvisoExternos{
  font-weight:bold;
  font-size:1.4em;
  padding-top:10px;
}

a.linkLogin{
  font-size:1.1em !important;
  color:#0099e5;
  padding-top:2px;
  line-height:1.6em;
}

a.linkLogin:hover{
  color:#006699;
}

a.linkLogin:focus{
outline:1px dotted #006699;
}

div.md-form button{
  margin-bottom: .35rem;
}

#lblInfraCaptchaAjuda{
  display:none;
}

#lblInfraCaptcha img {width:100px;height:50px;}
#txtInfraCaptcha {max-width:100px;}

@media screen and (min-width: 1366px) {
  #lblInfraCaptcha img {width:130px;}
  #txtInfraCaptcha {max-width:130px;}
}


<?
PaginaSEIExterna::getInstance()->fecharStyle();
PaginaSEIExterna::getInstance()->montarJavaScript();
CaptchaSEI::getInstance()->montarJavascript();
PaginaSEIExterna::getInstance()->abrirJavaScript();
?>

$(document).ready(function () {
new MaskedPassword(document.getElementById("pwdSenha"), '\u25CF', true, 'input-group');
});

function inicializar(){
  if (infraTrim(document.getElementById('txtEmail').value)==''){
    document.getElementById('txtEmail').focus();
  }else{
    document.getElementById('pwdSenha').focus();
  }
  
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

  if (infraTrim(document.getElementById('pwdSenha').value)=='') {
    alert('Informe a Senha.');
    document.getElementById('pwdSenha').focus();
    return false;
  }

<? if ($_SESSION['EXTERNO_NUM_FALHA_LOGIN'] >= $numLoginSemCaptcha){
  CaptchaSEI::getInstance()->validarOnSubmit('frmLogin');
}else{ ?>
  return true;
<? } ?>
}
<?
PaginaSEIExterna::getInstance()->fecharJavaScript();
PaginaSEIExterna::getInstance()->fecharHead();
PaginaSEIExterna::getInstance()->abrirBody('','onload="inicializar();"');
?>
<form id="frmLogin" method="post" class="h-100" onsubmit="return OnSubmitForm();" action="<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">

          <div class="d-flex  justify-content-center align-items-center h-100">
            <div id="area-cards-login" class="col-xs-9 col-sm-8 col-md-6 col-lg-5 col-xl-4" style="max-width:500px">
              <div class="card">
                <div class="card-body">
                  <div class="row justify-content-center align-items-center">
                    <div class="pt-4" style="width:82%;">

                      <div class="text-center">
                        <img src="imagens/sei_login_externo.png"  title="logo" alt="Logo">
                      </div>

                      <div id="divAvisoExternos" class="text-center">
                        Acesso para Usuários Externos
                      </div>

                      <div class="pt-4">

                          <div id="frmUsuario" class="input-group mb-3 d-flex">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><img src="svg/usuario_externo_login.svg" /></span>
                                            </span>
                            <input type="text" autofocus="" id="txtEmail" name="txtEmail" placeholder="E-mail" class="form-control"    value="<?=PaginaSEIExterna::tratarHTML($_POST['txtEmail'])?>"  maxlength="100" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">

                          </div>

                          <div id="frmSenha" class="mb-3 d-flex">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><img src="svg/usuario_externo_senha.svg" /></span>
                                            </span>
                            <input type="password" autofocus="" id="pwdSenha" name="pwdSenha" placeholder="Senha" class="form-control masked"   value="" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" autocomplete="off">
                          </div>

                        <?
                        $numTabCaptcha = PaginaSEIExterna::getInstance()->getProxTabDados();
                        ?>

                          <div class="md-form">
                            <button type="submit" id="sbmLogin" name="sbmLogin" class="btn text-white infraCorBarraSuperior w-100" style="border: none;" accesskey="n" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">
                              ENTRAR
                            </button>
                            <div class="text-right py-1">
                              <a title="Clique aqui para se cadastrar" href="<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_avisar_cadastro')?>" title="Clique aqui se você esqueceu sua senha e quer receber um e-mail para redefini-la" class="linkLogin" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">
                                Clique aqui para se cadastrar
                              </a>
                              <br>
                              <a title="Esqueci minha senha" href="<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_gerar_senha')?>" title="Clique aqui se você esqueceu sua senha e quer receber um e-mail para redefini-la" class="linkLogin" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">
                                Esqueci minha senha
                              </a>
                            </div>

                            <?=$strBotoesModulos?>

                          </div>

                          <? if ($_SESSION['EXTERNO_NUM_FALHA_LOGIN'] >= $numLoginSemCaptcha){ ?>
                            <div class="pt-3 d-flex justify-content-center">
                              <? CaptchaSEI::getInstance()->montarHtml($numTabCaptcha); ?>
                            </div>
                         <? } ?>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
</form>
<?
PaginaSEIExterna::getInstance()->montarAreaDebug();
PaginaSEIExterna::getInstance()->fecharBody();
PaginaSEIExterna::getInstance()->fecharHtml();
?>