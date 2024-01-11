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

  PaginaSEI::getInstance()->verificarSelecao('usuario_sistema_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objUsuarioDTO = new UsuarioDTO();


  $arrComandos = array();

  switch($_GET['acao']){

    case 'usuario_sistema_cadastrar':
      $strTitulo = 'Novo Sistema';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarSistema" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objUsuarioDTO->setNumIdUsuario(null);
      $objUsuarioDTO->setNumIdOrgao($_POST['selOrgao']);
      $objUsuarioDTO->setStrIdOrigem(null);
      $objUsuarioDTO->setStrSigla($_POST['txtSigla']);
      $objUsuarioDTO->setStrNome($_POST['txtNome']);
      $objUsuarioDTO->setNumIdContato(null);
      $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SISTEMA);
      $objUsuarioDTO->setStrSenha(null);
      $objUsuarioDTO->setStrSinAtivo('S');
      
      if (isset($_POST['sbmCadastrarSistema'])) {
        try{
          $objUsuarioRN = new UsuarioRN();
          $objUsuarioDTO = $objUsuarioRN->cadastrarRN0487($objUsuarioDTO);
          PaginaSEI::getInstance()->setStrMensagem('Sistema "'.$objUsuarioDTO->getStrSigla().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_Usuario='.$objUsuarioDTO->getNumIdUsuario().'#ID-'.$objUsuarioDTO->getNumIdUsuario()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;
  	
    case 'usuario_sistema_alterar':
      $strTitulo = 'Alterar Sistema';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarUsuario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      

      if (isset($_GET['id_usuario'])){
        $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);
        $objUsuarioDTO->retTodos(true);
        $objUsuarioRN = new UsuarioRN();
        $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);
        if ($objUsuarioDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objUsuarioDTO->setNumIdOrgao($_POST['hdnIdOrgao']);
        $objUsuarioDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
        $objUsuarioDTO->setStrIdOrigem($_POST['hdnIdOrigem']);
        $objUsuarioDTO->setStrSigla($_POST['txtSigla']);
        $objUsuarioDTO->setStrNome($_POST['txtNome']);
        $objUsuarioDTO->setStrNomeContato($_POST['txtContato']);        
        $objUsuarioDTO->setNumIdContato($_POST['hdnIdContato']);
        $objUsuarioDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objUsuarioDTO->getNumIdUsuario().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarUsuario'])) {
        try{
          $objUsuarioRN = new UsuarioRN();
          $objUsuarioRN->alterarRN0488($objUsuarioDTO);
          PaginaSEI::getInstance()->setStrMensagem('Sistema "'.$objUsuarioDTO->getStrSigla().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objUsuarioDTO->getNumIdUsuario()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'usuario_sistema_consultar':
      $strTitulo = "Consultar Sistema";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_usuario'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);
      $objUsuarioDTO->retTodos(true);
      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);
      if ($objUsuarioDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('null','&nbsp;',$objUsuarioDTO->getNumIdOrgao());
  
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

#lblOrgao {position:absolute;left:0%;top:0%;width:20%;}
#selOrgao {position:absolute;left:0%;top:6%;width:20%;}

#lblSigla {position:absolute;left:0%;top:16%;width:30%;}
#txtSigla {position:absolute;left:0%;top:22%;width:30%;}

#lblNome {position:absolute;left:0%;top:32%;width:80%;}
#txtNome {position:absolute;left:0%;top:38%;width:80%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>


function inicializar(){

  if ('<?=$_GET['acao']?>'=='usuario_sistema_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('selOrgao').focus();
  }
  
  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarFormRI0699();
}

function validarFormRI0699() {

  if (!infraSelectSelecionado('selOrgao')) {
    alert('Selecione o Órgão.');
    document.getElementById('selOrgao').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtSigla').value)=='') {
    alert('Informe a Sigla.');
    document.getElementById('txtSigla').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  return true;
}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmUsuarioCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblOrgao" for="selOrgao" accesskey="g" class="infraLabelObrigatorio">Or<span class="infraTeclaAtalho">g</span>ão:</label>
  <select id="selOrgao" name="selOrgao" onkeypress="return infraMascaraNumero(this, event);" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgao?>
  </select>
  
  <label id="lblSigla" for="txtSigla" accesskey="" class="infraLabelObrigatorio">Sigla:</label>
  <input type="text" id="txtSigla" name="txtSigla" class="infraText" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" value="<?=PaginaSEI::tratarHTML($objUsuarioDTO->getStrSigla());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" value="<?=PaginaSEI::tratarHTML($objUsuarioDTO->getStrNome());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdContato" name="hdnIdContato" value="<?=$objUsuarioDTO->getNumIdContato();?>" />  
  <input type="hidden" id="hdnIdOrgao" name="hdnIdOrgao" value="<?=$objUsuarioDTO->getNumIdOrgao();?>" />
  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$objUsuarioDTO->getNumIdUsuario();?>" />
  <input type="hidden" id="hdnIdOrigem" name="hdnIdOrigem" value="<?=$objUsuarioDTO->getStrIdOrigem();?>" />
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