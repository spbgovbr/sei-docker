<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/05/2009 - criado por mga
*
* Versão do Gerador de Código: 1.26.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  PaginaSip::getInstance()->verificarSelecao('usuario_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgao'));

  $objUsuarioDTO = new UsuarioDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'usuario_cadastrar':
      $strTitulo = 'Novo Usuário';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarUsuario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objUsuarioDTO->setNumIdUsuario(null);
      $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgao');
      if ($numIdOrgao!==''){
        $objUsuarioDTO->setNumIdOrgao($numIdOrgao);
      }else{
        $objUsuarioDTO->setNumIdOrgao(null);
      }

      $objUsuarioDTO->setStrSigla($_POST['txtSigla']);
      $objUsuarioDTO->setStrNome($_POST['txtNome']);
      $objUsuarioDTO->setStrNomeSocial($_POST['txtNomeSocial']);
      $objUsuarioDTO->setStrIdOrigem($_POST['txtIdOrigem']);
      $objUsuarioDTO->setDblCpf($_POST['txtCpf']);
      $objUsuarioDTO->setStrEmail($_POST['txtEmail']);
      $objUsuarioDTO->setStrSinBloqueado('N');
      $objUsuarioDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarUsuario'])) {
        try{
          $objUsuarioRN = new UsuarioRN();
          $objUsuarioDTO = $objUsuarioRN->cadastrar($objUsuarioDTO);
          PaginaSip::getInstance()->setStrMensagem('Usuário "'.$objUsuarioDTO->getStrSigla().'" cadastrado com sucesso.');
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_usuario='.$objUsuarioDTO->getNumIdUsuario().'#ID-'.$objUsuarioDTO->getNumIdUsuario()));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'usuario_alterar':
      $strTitulo = 'Alterar Usuário';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarUsuario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_usuario'])){
        $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);
        $objUsuarioDTO->retTodos();
        $objUsuarioRN = new UsuarioRN();
        $objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);
        if ($objUsuarioDTO==null){
          throw new InfraException("Registro não encontrado.");
        }

        $objUsuarioDTO->setStrNome($objUsuarioDTO->getStrNomeRegistroCivil());

      } else {
        $objUsuarioDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
        $objUsuarioDTO->setNumIdOrgao($_POST['selOrgao']);
        $objUsuarioDTO->setStrSigla($_POST['txtSigla']);
        $objUsuarioDTO->setStrNome($_POST['txtNome']);
        $objUsuarioDTO->setStrNomeSocial($_POST['txtNomeSocial']);
        $objUsuarioDTO->setStrIdOrigem($_POST['txtIdOrigem']);
        $objUsuarioDTO->setDblCpf($_POST['txtCpf']);
        $objUsuarioDTO->setStrEmail($_POST['txtEmail']);
        $objUsuarioDTO->setStrSinAtivo('S');
      }

      $objUsuarioDTO->setDblCpf(InfraUtil::formatarCpf($objUsuarioDTO->getDblCpf()));

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objUsuarioDTO->getNumIdUsuario().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarUsuario'])) {
        try{
          $objUsuarioRN = new UsuarioRN();
          $objUsuarioRN->alterar($objUsuarioDTO);
          PaginaSip::getInstance()->setStrMensagem('Usuário "'.$objUsuarioDTO->getStrSigla().'" alterado com sucesso.');
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objUsuarioDTO->getNumIdUsuario()));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'usuario_consultar':
      $strTitulo = 'Consultar Usuário';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_usuario'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retTodos();
      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);
      if ($objUsuarioDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      $objUsuarioDTO->setStrNome($objUsuarioDTO->getStrNomeRegistroCivil());
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;',$objUsuarioDTO->getNumIdOrgao());

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgao {position:absolute;left:0%;top:0%;width:25%;}
#selOrgao {position:absolute;left:0%;top:5%;width:25%;}

#lblSigla {position:absolute;left:0%;top:13%;width:40%;}
#txtSigla {position:absolute;left:0%;top:18%;width:40%;}

#lblNome {position:absolute;left:0%;top:26%;width:70%;}
#txtNome {position:absolute;left:0%;top:31%;width:70%;}

#lblNomeSocial {position:absolute;left:0%;top:39%;width:70%;}
#txtNomeSocial {position:absolute;left:0%;top:44%;width:70%;}

#lblIdOrigem {position:absolute;left:0%;top:52%;width:20%;}
#txtIdOrigem {position:absolute;left:0%;top:57%;width:20%;}

#lblCpf {position:absolute;left:0%;top:65%;width:20%;}
#txtCpf {position:absolute;left:0%;top:70%;width:20%;}

#lblEmail {position:absolute;left:0%;top:78%;width:40%;}
#txtEmail {position:absolute;left:0%;top:83%;width:40%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='usuario_cadastrar'){
    document.getElementById('selOrgao').focus();
  } else if ('<?=$_GET['acao']?>'=='usuario_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (!infraSelectSelecionado('selOrgao')) {
    alert('Selecione um Órgão.');
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

  if (infraTrim(document.getElementById('txtCpf').value)!='') {
    if (!infraValidarCpf(document.getElementById('txtCpf').value)) {
      alert('CPF inválido.');
      document.getElementById('txtCpf').focus();
      return false;
    }
  }

  if (infraTrim(document.getElementById('txtEmail').value)!='') {
    if (!infraValidarEmail(infraTrim(document.getElementById('txtEmail').value))){
      alert('E-mail inválido.');
      document.getElementById('txtEmail').focus();
      return false;
    }
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmUsuarioCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('38em');
?>
  <label id="lblOrgao" for="selOrgao" accesskey="o" class="infraLabelObrigatorio">Órgã<span class="infraTeclaAtalho">o</span>:</label>
  <select id="selOrgao" name="selOrgao" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
  <?=$strItensSelOrgao?>
  </select>
  <label id="lblSigla" for="txtSigla" accesskey="a" class="infraLabelObrigatorio">Sigl<span class="infraTeclaAtalho">a</span>:</label>
  <input type="text" id="txtSigla" name="txtSigla" class="infraText" value="<?=PaginaSip::tratarHTML($objUsuarioDTO->getStrSigla());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSip::tratarHTML($objUsuarioDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblNomeSocial" for="txtNomeSocial" class="infraLabelOpcional">Nome Social:</label>
  <input type="text" id="txtNomeSocial" name="txtNomeSocial" class="infraText" value="<?=PaginaSip::tratarHTML($objUsuarioDTO->getStrNomeSocial());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblIdOrigem" for="txtIdOrigem" accesskey="" class="infraLabelOpcional">ID Origem:</label>
  <input type="text" id="txtIdOrigem" name="txtIdOrigem" class="infraText" value="<?=PaginaSip::tratarHTML($objUsuarioDTO->getStrIdOrigem());?>" maxlength="50" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblCpf" for="txtCpf" class="infraLabelOpcional">CPF:</label>
  <input type="text" id="txtCpf" name="txtCpf" onkeypress="return infraMascaraCpf(this, event)" class="infraText" value="<?=PaginaSip::tratarHTML(InfraUtil::formatarCpf($objUsuarioDTO->getDblCpf()));?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblEmail" for="txtEmail" class="infraLabelOpcional">E-mail Institucional:</label>
  <input type="text" id="txtEmail" name="txtEmail" class="infraText" value="<?=PaginaSip::tratarHTML($objUsuarioDTO->getStrEmail());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$objUsuarioDTO->getNumIdUsuario();?>" />
  <?
  PaginaSip::getInstance()->fecharAreaDados();

  //PaginaSip::getInstance()->montarAreaDebug();
  //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>