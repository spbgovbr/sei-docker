<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/07/2015 - criado por mga
*
* Versão do Gerador de Código: 1.35.0
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

  PaginaSEI::getInstance()->verificarSelecao('tipo_formulario_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objTipoFormularioDTO = new TipoFormularioDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'tipo_formulario_cadastrar':
      $strTitulo = 'Novo Tipo de Formulário';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoFormulario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objTipoFormularioDTO->setNumIdTipoFormulario(null);
      $objTipoFormularioDTO->setStrNome($_POST['txtNome']);
      $objTipoFormularioDTO->setStrDescricao($_POST['txaDescricao']);
      $objTipoFormularioDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarTipoFormulario'])) {
        try{
          $objTipoFormularioRN = new TipoFormularioRN();
          $objTipoFormularioDTO = $objTipoFormularioRN->cadastrar($objTipoFormularioDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tipo de Formulário "'.$objTipoFormularioDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_formulario='.$objTipoFormularioDTO->getNumIdTipoFormulario().PaginaSEI::getInstance()->montarAncora($objTipoFormularioDTO->getNumIdTipoFormulario())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_formulario_alterar':
      $strTitulo = 'Alterar Tipo de Formulário';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoFormulario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_tipo_formulario'])){
        $objTipoFormularioDTO->setNumIdTipoFormulario($_GET['id_tipo_formulario']);
        $objTipoFormularioDTO->retTodos();
        $objTipoFormularioRN = new TipoFormularioRN();
        $objTipoFormularioDTO = $objTipoFormularioRN->consultar($objTipoFormularioDTO);
        if ($objTipoFormularioDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objTipoFormularioDTO->setNumIdTipoFormulario($_POST['hdnIdTipoFormulario']);
        $objTipoFormularioDTO->setStrNome($_POST['txtNome']);
        $objTipoFormularioDTO->setStrDescricao($_POST['txaDescricao']);
        $objTipoFormularioDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTipoFormularioDTO->getNumIdTipoFormulario())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTipoFormulario'])) {
        try{
          $objTipoFormularioRN = new TipoFormularioRN();
          $objTipoFormularioRN->alterar($objTipoFormularioDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tipo de Formulário "'.$objTipoFormularioDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTipoFormularioDTO->getNumIdTipoFormulario())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_formulario_consultar':
      $strTitulo = 'Consultar Tipo de Formulário';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_tipo_formulario'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTipoFormularioDTO->setNumIdTipoFormulario($_GET['id_tipo_formulario']);
      $objTipoFormularioDTO->setBolExclusaoLogica(false);
      $objTipoFormularioDTO->retTodos();
      $objTipoFormularioRN = new TipoFormularioRN();
      $objTipoFormularioDTO = $objTipoFormularioRN->consultar($objTipoFormularioDTO);
      if ($objTipoFormularioDTO===null){
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
#lblNome {position:absolute;left:0%;top:0%;width:50%;}
#txtNome {position:absolute;left:0%;top:6%;width:50%;}

#lblDescricao {position:absolute;left:0%;top:16%;width:70%;}
#txaDescricao {position:absolute;left:0%;top:22%;width:70%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='formulario_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='tipo_formulario_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTipoFormularioCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objTipoFormularioDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'3':'4'?>" onkeypress="return infraLimitarTexto(this,event,250);" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objTipoFormularioDTO->getStrDescricao());?></textarea>

  <input type="hidden" id="hdnIdTipoFormulario" name="hdnIdTipoFormulario" value="<?=$objTipoFormularioDTO->getNumIdTipoFormulario();?>" />
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