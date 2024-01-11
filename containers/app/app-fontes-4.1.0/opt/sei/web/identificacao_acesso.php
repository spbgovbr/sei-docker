<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/04/2011 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();
  
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
	
  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $arrParam = array();
  foreach($_GET as $key => $val){
    if (substr($key,0,3) == 'id_'){
      $arrParam[] = $key;
    }
  }

  SessaoSEI::getInstance()->setArrParametrosRepasseLink($arrParam);

  $bolAcesso = true;
  $bolValidado = false;
  
  $strLinkDestino = '';
  
  switch($_GET['acao']){
    
    case 'usuario_validar_acesso':

      $strTitulo = 'Identificação de Acesso';

      if ($_GET['acao_destino']=='procedimento_trabalhar') {

        if (!isset($_POST['pwdSenha'])) {

          //verifica permissão de acesso ao documento
          $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
          $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
          $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
          $objPesquisaProtocoloDTO->setDblIdProtocolo($_GET['id_procedimento']);

          $objProtocoloRN = new ProtocoloRN();
          $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

          if (count($arrObjProtocoloDTO) == 0) {
            $bolAcesso = false;
            $strTitulo = '';
          }
        }
      }

      if (isset($_POST['pwdSenha'])){

        try{

          $objInfraSip = new InfraSip(SessaoSEI::getInstance());
          $objInfraSip->autenticar(SessaoSEI::getInstance()->getNumIdOrgaoUsuario(), null, SessaoSEI::getInstance()->getStrSiglaUsuario(), $_POST['pwdSenha']);
        	
          AuditoriaSEI::getInstance()->auditar($_GET['acao']);

          $bolValidado = true;
          $strTitulo = '';
          
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e, true);
        }
      }

      $strLinkDestino = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_destino'].'&acao_origem='.$_GET['acao'].'&acesso=1');
      $strLinkNegado = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_negado'].'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_procedimento']));
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


}catch(Exception $e){ 
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

#divIdentificacao{<?=($bolValidado || !$bolAcesso)?'display:none;':''?>}

#lblUsuario {position:absolute;left:0%;top:0%;}
#txtUsuario {position:absolute;left:0%;top:13%;width:90%;}

#lblSenha {position:absolute;left:0%;top:35%;}
#pwdSenha {position:absolute;left:0%;top:48%;width:30%;}

#btnAcessar {position:absolute;left:0%;top:72%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var bolProcessando = false;

$(document).ready(function(){
  new MaskedPassword(document.getElementById("pwdSenha"), '\u25CF');
});

function inicializar(){
<?if (!$bolAcesso){ ?>
  infraFecharJanelaModal();
<?}else if ($bolValidado){ ?>
  window.parent.location = '<?=$strLinkDestino?>';
<?}else{?>
  self.setTimeout('document.getElementById(\'pwdSenha\').focus()',500);
<?}?>
}

function OnSubmitForm() {

  if (infraTrim(document.getElementById('pwdSenha').value)==''){
    alert('Senha não informada.');
    document.getElementById('pwdSenha').focus();
    return false;
  }

  return true;
}

function tratarSenha(obj, ev){
  if (infraGetCodigoTecla(ev)==13){
    acessar();
  }
}

function acessar(){
  if (OnSubmitForm()){
    bolProcessando = true;
    document.getElementById('frmIdentificacaoAcesso').submit();
  }
}

function finalizar(){
  if (!bolProcessando && '<?=$bolValidado?>'!='1'){
    window.parent.location = '<?=$strLinkNegado?>';
  }
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmIdentificacaoAcesso" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao_destino'].'&acao_negado='.$_GET['acao_negado'])?>">
  
	<?
	//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
	PaginaSEI::getInstance()->montarBarraComandosSuperior(array());
	//PaginaSEI::getInstance()->montarAreaValidacao();
  ?>
  <div id="divIdentificacao" class="infraAreaDados" style="height:14em">
    <label id="lblUsuario" for="txtUsuario" accesskey="" class="infraLabelObrigatorio">Usuário:</label>
    <input type="text" id="txtUsuario" name="txtUsuario" class="infraText infraReadOnly" readonly="readonly"
           value="<?= PaginaSEI::tratarHTML(SessaoSEI::getInstance()->getStrNomeUsuario()) ?>"
           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

    <label id="lblSenha" for="pwdSenha" accesskey="" class="infraLabelObrigatorio">Senha:</label>
    <?= InfraINT::montarInputPassword('pwdSenha', '', 'onkeypress="return tratarSenha(this,event);" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"') ?>

    <button type="button" accesskey="A" onclick="acessar();" id="btnAcessar" name="btnAcessar" value="Acessar"
            class="infraButton">&nbsp;<span class="infraTeclaAtalho">A</span>cessar&nbsp;
    </button>
  </div>
  <?
	PaginaSEI::getInstance()->montarAreaDebug();
	//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>