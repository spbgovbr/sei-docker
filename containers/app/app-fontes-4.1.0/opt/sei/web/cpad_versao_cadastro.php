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

  PaginaSEI::getInstance()->verificarSelecao('cpad_versao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selCpad'));

  $objCpadVersaoDTO = new CpadVersaoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'cpad_versao_cadastrar':
      $strTitulo = 'Nova Versão da Composição da Comissão Permanente de Avaliação de Documentos';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarCpadVersao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objCpadVersaoDTO->setNumIdCpadVersao(null);
      $numIdCpad = PaginaSEI::getInstance()->recuperarCampo('selCpad');
      if ($numIdCpad!==''){
        $objCpadVersaoDTO->setNumIdCpad($numIdCpad);
      }else{
        $objCpadVersaoDTO->setNumIdCpad(null);
      }

      $objCpadVersaoDTO->setStrSigla($_POST['txtSigla']);
      $objCpadVersaoDTO->setStrDescricao($_POST['txtDescricao']);
      $objCpadVersaoDTO->setDthVersao($_POST['txtVersao']);
      $objCpadVersaoDTO->setStrSinEditavel(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEditavel']));

      if (isset($_POST['sbmCadastrarCpadVersao'])) {
        try{
          $objCpadVersaoRN = new CpadVersaoRN();
          $objCpadVersaoDTO->setStrSinAtivo("S");
          $objCpadVersaoDTO = $objCpadVersaoRN->cadastrar($objCpadVersaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Versão da Composição da Comissão Permanente de Avaliação de Documentos "'.$objCpadVersaoDTO->getNumIdCpadVersao().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_cpad_versao='.$objCpadVersaoDTO->getNumIdCpadVersao().PaginaSEI::getInstance()->montarAncora($objCpadVersaoDTO->getNumIdCpadVersao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'cpad_versao_alterar':
      $strTitulo = 'Alterar Versão da Composição da Comissão Permanente de Avaliação de Documentos';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarCpadVersao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_cpad_versao'])){
        $objCpadVersaoDTO->setNumIdCpadVersao($_GET['id_cpad_versao']);
        $objCpadVersaoDTO->retTodos();
        $objCpadVersaoRN = new CpadVersaoRN();
        $objCpadVersaoDTO = $objCpadVersaoRN->consultar($objCpadVersaoDTO);
        if ($objCpadVersaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objCpadVersaoDTO->setNumIdCpadVersao($_POST['hdnIdCpadVersao']);
        $objCpadVersaoDTO->setNumIdCpad($_POST['selCpad']);
        $objCpadVersaoDTO->setStrSigla($_POST['txtSigla']);
        $objCpadVersaoDTO->setStrDescricao($_POST['txtDescricao']);
        $objCpadVersaoDTO->setDthVersao($_POST['txtVersao']);
        $objCpadVersaoDTO->setStrSinEditavel(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEditavel']));
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objCpadVersaoDTO->getNumIdCpadVersao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarCpadVersao'])) {
        try{
          $objCpadVersaoRN = new CpadVersaoRN();
          $objCpadVersaoRN->alterar($objCpadVersaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Versão da Composição da Comissão Permanente de Avaliação de Documentos "'.$objCpadVersaoDTO->getNumIdCpadVersao().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objCpadVersaoDTO->getNumIdCpadVersao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'cpad_versao_consultar':
      $strTitulo = 'Consultar Versão da Composição da Comissão Permanente de Avaliação de Documentos';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_cpad_versao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objCpadVersaoDTO->setNumIdCpadVersao($_GET['id_cpad_versao']);
      $objCpadVersaoDTO->setBolExclusaoLogica(false);
      $objCpadVersaoDTO->retTodos();
      $objCpadVersaoRN = new CpadVersaoRN();
      $objCpadVersaoDTO = $objCpadVersaoRN->consultar($objCpadVersaoDTO);
      if ($objCpadVersaoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelCpad = CpadINT::montarSelectSigla('null','&nbsp;',$objCpadVersaoDTO->getNumIdCpad());

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
#lblCpad {position:absolute;left:0%;top:0%;width:25%;}
#selCpad {position:absolute;left:0%;top:40%;width:25%;}

#lblSigla {position:absolute;left:0%;top:0%;width:30%;}
#txtSigla {position:absolute;left:0%;top:40%;width:30%;}

#lblDescricao {position:absolute;left:0%;top:0%;width:95%;}
#txtDescricao {position:absolute;left:0%;top:40%;width:95%;}

#lblVersao {position:absolute;left:0%;top:0%;width:25%;}
#txtVersao {position:absolute;left:0%;top:40%;width:25%;}
#imgCalVersao {position:absolute;left:26%;top:40%;}

#divSinEditavel {position:absolute;left:0%;top:20%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='cpad_versao_cadastrar'){
    document.getElementById('selCpad').focus();
  } else if ('<?=$_GET['acao']?>'=='cpad_versao_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}

function validarCadastro() {
  if (!infraSelectSelecionado('selCpad')) {
    alert('Selecione uma Comissão.');
    document.getElementById('selCpad').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtSigla').value)=='') {
    alert('Informe a Sigla.');
    document.getElementById('txtSigla').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txtDescricao').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtVersao').value)=='') {
    alert('Informe a Data.');
    document.getElementById('txtVersao').focus();
    return false;
  }

  if (!infraValidarDataHora(document.getElementById('txtVersao'))){
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
<form id="frmCpadVersaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblCpad" for="selCpad" accesskey="" class="infraLabelObrigatorio">Comissão:</label>
  <select id="selCpad" name="selCpad" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelCpad?>
  </select>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblSigla" for="txtSigla" accesskey="" class="infraLabelObrigatorio">Sigla:</label>
  <input type="text" id="txtSigla" name="txtSigla" class="infraText" value="<?=PaginaSEI::tratarHTML($objCpadVersaoDTO->getStrSigla());?>" onkeypress="return infraMascaraTexto(this,event,30);" maxlength="30" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objCpadVersaoDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblVersao" for="txtVersao" accesskey="" class="infraLabelObrigatorio">Data:</label>
  <input type="text" id="txtVersao" name="txtVersao" onkeypress="return infraMascaraDataHora(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objCpadVersaoDTO->getDthVersao());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalVersao" title="Selecionar Data" alt="Selecionar Data" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtVersao',this);" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <div id="divSinEditavel" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinEditavel" name="chkSinEditavel" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objCpadVersaoDTO->getStrSinEditavel())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <label id="lblSinEditavel" for="chkSinEditavel" accesskey="" class="infraLabelCheckbox">Edição</label>
  </div>
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdCpadVersao" name="hdnIdCpadVersao" value="<?=$objCpadVersaoDTO->getNumIdCpadVersao();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
