<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
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

  PaginaSEI::getInstance()->verificarSelecao('cpad_composicao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selCpadVersao','selUsuario','selCargo'));

  $objCpadComposicaoDTO = new CpadComposicaoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'cpad_composicao_cadastrar':
      $strTitulo = 'Nova Composição da Comissão Permanente de Avaliação de Documentos';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarCpadComposicao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objCpadComposicaoDTO->setNumIdCpadComposicao(null);
      $numIdCpadVersao = PaginaSEI::getInstance()->recuperarCampo('selCpadVersao');
      if ($numIdCpadVersao!==''){
        $objCpadComposicaoDTO->setNumIdCpadVersao($numIdCpadVersao);
      }else{
        $objCpadComposicaoDTO->setNumIdCpadVersao(null);
      }

      $numIdUsuario = PaginaSEI::getInstance()->recuperarCampo('selUsuario');
      if ($numIdUsuario!==''){
        $objCpadComposicaoDTO->setNumIdUsuario($numIdUsuario);
      }else{
        $objCpadComposicaoDTO->setNumIdUsuario(null);
      }

      $numIdCargo = PaginaSEI::getInstance()->recuperarCampo('selCargo');
      if ($numIdCargo!==''){
        $objCpadComposicaoDTO->setNumIdCargo($numIdCargo);
      }else{
        $objCpadComposicaoDTO->setNumIdCargo(null);
      }

      $objCpadComposicaoDTO->setStrSinPresidente(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPresidente']));

      if (isset($_POST['sbmCadastrarCpadComposicao'])) {
        try{
          $objCpadComposicaoRN = new CpadComposicaoRN();
          $objCpadComposicaoDTO = $objCpadComposicaoRN->cadastrar($objCpadComposicaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Composição da Comissão Permanente de Avaliação de Documentos "'.$objCpadComposicaoDTO->getNumIdUsuario().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_cpad_composicao='.$objCpadComposicaoDTO->getNumIdCpadComposicao().PaginaSEI::getInstance()->montarAncora($objCpadComposicaoDTO->getNumIdCpadComposicao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'cpad_composicao_alterar':
      $strTitulo = 'Alterar Composição da Comissão Permanente de Avaliação de Documentos';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarCpadComposicao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_cpad_composicao'])){
        $objCpadComposicaoDTO->setNumIdCpadComposicao($_GET['id_cpad_composicao']);
        $objCpadComposicaoDTO->retTodos();
        $objCpadComposicaoRN = new CpadComposicaoRN();
        $objCpadComposicaoDTO = $objCpadComposicaoRN->consultar($objCpadComposicaoDTO);
        if ($objCpadComposicaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objCpadComposicaoDTO->setNumIdCpadComposicao($_POST['hdnIdCpadComposicao']);
        $objCpadComposicaoDTO->setNumIdCpadVersao($_POST['selCpadVersao']);
        $objCpadComposicaoDTO->setNumIdUsuario($_POST['selUsuario']);
        $objCpadComposicaoDTO->setNumIdCargo($_POST['selCargo']);
        $objCpadComposicaoDTO->setStrSinPresidente(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPresidente']));
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objCpadComposicaoDTO->getNumIdCpadComposicao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarCpadComposicao'])) {
        try{
          $objCpadComposicaoRN = new CpadComposicaoRN();
          $objCpadComposicaoRN->alterar($objCpadComposicaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Composição da Comissão Permanente de Avaliação de Documentos "'.$objCpadComposicaoDTO->getNumIdUsuario().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objCpadComposicaoDTO->getNumIdCpadComposicao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'cpad_composicao_consultar':
      $strTitulo = 'Consultar Composição da Comissão Permanente de Avaliação de Documentos';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_cpad_composicao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objCpadComposicaoDTO->setNumIdCpadComposicao($_GET['id_cpad_composicao']);
      $objCpadComposicaoDTO->setBolExclusaoLogica(false);
      $objCpadComposicaoDTO->retTodos();
      $objCpadComposicaoRN = new CpadComposicaoRN();
      $objCpadComposicaoDTO = $objCpadComposicaoRN->consultar($objCpadComposicaoDTO);
      if ($objCpadComposicaoDTO===null){
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
<?if(0){?><style><?}?>
#lblCpadVersao {position:absolute;left:0%;top:0%;width:25%;}
#selCpadVersao {position:absolute;left:0%;top:40%;width:25%;}

#lblUsuario {position:absolute;left:0%;top:0%;width:25%;}
#selUsuario {position:absolute;left:0%;top:40%;width:25%;}

#lblCargo {position:absolute;left:0%;top:0%;width:25%;}
#selCargo {position:absolute;left:0%;top:40%;width:25%;}

#divSinPresidente {position:absolute;left:0%;top:20%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='cpad_composicao_cadastrar'){
    document.getElementById('selCpadVersao').focus();
  } else if ('<?=$_GET['acao']?>'=='cpad_composicao_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}

function validarCadastro() {
  if (!infraSelectSelecionado('selCpadVersao')) {
    alert('Selecione uma Versão.');
    document.getElementById('selCpadVersao').focus();
    return false;
  }

  if (!infraSelectSelecionado('selUsuario')) {
    alert('Selecione um Usuário.');
    document.getElementById('selUsuario').focus();
    return false;
  }

  if (!infraSelectSelecionado('selCargo')) {
    alert('Selecione um Cargo.');
    document.getElementById('selCargo').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmCpadComposicaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblCpadVersao" for="selCpadVersao" accesskey="" class="infraLabelObrigatorio">Versão:</label>
  <select id="selCpadVersao" name="selCpadVersao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelCpadVersao?>
  </select>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblUsuario" for="selUsuario" accesskey="" class="infraLabelObrigatorio">Usuário:</label>
  <select id="selUsuario" name="selUsuario" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelUsuario?>
  </select>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblCargo" for="selCargo" accesskey="" class="infraLabelObrigatorio">Cargo:</label>
  <select id="selCargo" name="selCargo" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelCargo?>
  </select>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <div id="divSinPresidente" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinPresidente" name="chkSinPresidente" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objCpadComposicaoDTO->getStrSinPresidente())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <label id="lblSinPresidente" for="chkSinPresidente" accesskey="" class="infraLabelCheckbox">Presidente</label>
  </div>
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdCpadComposicao" name="hdnIdCpadComposicao" value="<?=$objCpadComposicaoDTO->getNumIdCpadComposicao();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
