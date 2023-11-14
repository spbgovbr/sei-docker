<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/04/2012 - criado por bcu
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('usuario_externo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objUsuarioDTO = new UsuarioDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){

    case 'usuario_externo_alterar':
      $strTitulo = 'Alterar Usuário Externo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarUsuario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_usuario'])){

        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO->retNumIdOrgao();
        $objUsuarioDTO->retNumIdContato();
        $objUsuarioDTO->retStrSigla();
        $objUsuarioDTO->retStrNomeRegistroCivil();
        $objUsuarioDTO->retStrNomeSocial();
        $objUsuarioDTO->retStrStaTipo();

        $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);

        $objUsuarioRN = new UsuarioRN();
        $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);
        if ($objUsuarioDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario_alteracao']);
        $objUsuarioDTO->setNumIdOrgao($_GET['id_orgao']);
        $objUsuarioDTO->setNumIdContato($_GET['id_contato']);
        $objUsuarioDTO->setStrSigla($_POST['txtSiglaContatoAssociado']);
        $objUsuarioDTO->setStrNome($_POST['txtNomeContatoAssociado']);
        $objUsuarioDTO->setStrNomeRegistroCivil($_POST['txtNomeContatoAssociado']);
        $objUsuarioDTO->setStrNomeSocial($_POST['txtNomeSocialContatoAssociado']);
        $objUsuarioDTO->setStrStaTipo($_POST['rdoStatus']);
        $objUsuarioDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objUsuarioDTO->getNumIdUsuario().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarUsuario'])) {
        try{
          $objUsuarioRN = new UsuarioRN();
          $objUsuarioRN->alterarRN0488($objUsuarioDTO);
          PaginaSEI::getInstance()->setStrMensagem('Usuário Externo "'.$objUsuarioDTO->getStrSigla().'" alterado com sucesso.');          
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objUsuarioDTO->getNumIdUsuario()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'usuario_externo_consultar':
      $strTitulo = "Consultar Usuário Externo";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_usuario'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retNumIdOrgao();
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNomeRegistroCivil();
      $objUsuarioDTO->retStrNomeSocial();
      $objUsuarioDTO->retStrStaTipo();

      $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);

      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);
      if ($objUsuarioDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
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

#fldStatus {height: 60%;left: 0;position: absolute;top:0%;width: 70%;}
#divOptPendente {left: 17%;position: absolute;top: 45%;}
#divOptLiberado {left: 65%;position: absolute;top: 45%;}

<?
if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){
?>
#divOptPendente {top:25%;}
#divOptLiberado {top:25%;}
<?
}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){

  if ('<?=$_GET['acao']?>'=='usuario_externo_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
      
  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarForm();
}

function validarForm() {
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmUsuarioCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_usuario_alteracao='.$objUsuarioDTO->getNumIdUsuario().'&id_contato='.$objUsuarioDTO->getNumIdContato().'&id_orgao='.$objUsuarioDTO->getNumIdOrgao())?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
ContatoINT::montarContatoAssociado(false, null, false, null, false, null, true, $objUsuarioDTO->getNumIdContato(), $objUsuarioDTO->getStrSigla(), $objUsuarioDTO->getStrNomeRegistroCivil(), $objUsuarioDTO->getStrNomeSocial(), false,'frmUsuarioCadastro');
PaginaSEI::getInstance()->abrirAreaDados('10em');
?>

  <fieldset id="fldStatus" class="infraFieldset">
  <legend class="infraLegend">Situação</legend>
  	
  	  <div id="divOptPendente" class="infraDivRadio">
			<input type="radio" name="rdoStatus" id="optPendente" value="<?=UsuarioRN::$TU_EXTERNO_PENDENTE?>" <?=($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_EXTERNO_PENDENTE?'checked="checked"':'')?> class="infraRadio"/>
	    <span id="spnPendente"><label id="lblPendente" for="optPendente" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Pendente</label></span>
	    </div>
  	
  	  <div id="divOptLiberado" class="infraDivRadio">
			<input type="radio" name="rdoStatus" id="optLiberado" value="<?=UsuarioRN::$TU_EXTERNO?>" <?=($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_EXTERNO?'checked="checked"':'')?> class="infraRadio"/>
	    <span id="spnLiberado"><label id="lblLiberado" for="optLiberado" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Liberado</label></span>
	    </div>
	    
  </fieldset>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>