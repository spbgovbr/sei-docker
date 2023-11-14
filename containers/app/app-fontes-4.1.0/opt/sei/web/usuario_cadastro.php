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

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('usuario_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objUsuarioDTO = new UsuarioDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){

    case 'usuario_alterar':
      $strTitulo = 'Alterar Usuário';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarUsuario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_usuario'])){
        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO->retNumIdOrgao();
        $objUsuarioDTO->retStrIdOrigem();
        $objUsuarioDTO->retNumIdContato();
        $objUsuarioDTO->retStrSigla();
        $objUsuarioDTO->retStrNomeRegistroCivil();
        $objUsuarioDTO->retStrNomeSocial();

        $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);

        $objUsuarioRN = new UsuarioRN();
        $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);
        if ($objUsuarioDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario_alteracao']);
        $objUsuarioDTO->setNumIdOrgao($_GET['id_orgao']);
        $objUsuarioDTO->setStrIdOrigem($_GET['id_origem']);
        $objUsuarioDTO->setNumIdContato($_GET['id_contato']);
        $objUsuarioDTO->setStrSigla($_POST['txtSiglaContatoAssociado']);
        $objUsuarioDTO->setStrNome($_POST['txtNomeContatoAssociado']);
        $objUsuarioDTO->setStrNomeSocial($_POST['txtNomeSocialContatoAssociado']);
        $objUsuarioDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objUsuarioDTO->getNumIdUsuario().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarUsuario'])) {
        try{
          $objUsuarioRN = new UsuarioRN();
          $objUsuarioRN->alterarRN0488($objUsuarioDTO);
          PaginaSEI::getInstance()->setStrMensagem('Usuário "'.$objUsuarioDTO->getStrSigla().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objUsuarioDTO->getNumIdUsuario()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'usuario_consultar':
      $strTitulo = "Consultar Usuário";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_usuario'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retNumIdOrgao();
      $objUsuarioDTO->retStrIdOrigem();
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNomeRegistroCivil();
      $objUsuarioDTO->retStrNomeSocial();

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

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='usuario_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
}

function OnSubmitForm() {
  return validarFormRI0699();
}

function validarFormRI0699() {
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmUsuarioCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_usuario_alteracao='.$objUsuarioDTO->getNumIdUsuario().'&id_orgao='.$objUsuarioDTO->getNumIdOrgao().'&id_contato='.$objUsuarioDTO->getNumIdContato().'&id_origem='.$objUsuarioDTO->getStrIdOrigem())?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
ContatoINT::montarContatoAssociado(true, $objUsuarioDTO->getNumIdUsuario(), false,  null, true, $objUsuarioDTO->getStrIdOrigem(), true, $objUsuarioDTO->getNumIdContato(), $objUsuarioDTO->getStrSigla(), $objUsuarioDTO->getStrNomeRegistroCivil(), $objUsuarioDTO->getStrNomeSocial(), true,'frmUsuarioCadastro');
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>

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