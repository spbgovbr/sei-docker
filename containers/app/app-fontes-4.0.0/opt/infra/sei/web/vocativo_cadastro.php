<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
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

  PaginaSEI::getInstance()->verificarSelecao('vocativo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if (isset($_GET['cargo'])){
    $strParametros .= '&cargo='.$_GET['cargo'];
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $objVocativoDTO = new VocativoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'vocativo_cadastrar':
      $strTitulo = 'Novo Vocativo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarVocativo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if (PaginaSEI::getInstance()->getTipoPagina()!=InfraPagina::$TIPO_PAGINA_SIMPLES){
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }

      $objVocativoDTO->setNumIdVocativo(null);
      $objVocativoDTO->setStrExpressao($_POST['txtExpressao']);
      $objVocativoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarVocativo'])) {
        try{
          $objVocativoRN = new VocativoRN();
          $objVocativoDTO = $objVocativoRN->cadastrarRN0307($objVocativoDTO);

          if (isset($_GET['cargo'])){
            $bolOk = true;
          }else {
            PaginaSEI::getInstance()->setStrMensagem('Vocativo "' . $objVocativoDTO->getStrExpressao() . '" cadastrado com sucesso.');
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_vocativo=' . $objVocativoDTO->getNumIdVocativo() . '#ID-' . $objVocativoDTO->getNumIdVocativo()));
            die;
          }
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'vocativo_alterar':
      $strTitulo = 'Alterar Vocativo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarVocativo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_vocativo'])){
        $objVocativoDTO->setNumIdVocativo($_GET['id_vocativo']);
        $objVocativoDTO->retTodos();
        $objVocativoRN = new VocativoRN();
        $objVocativoDTO = $objVocativoRN->consultarRN0309($objVocativoDTO);
        if ($objVocativoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objVocativoDTO->setNumIdVocativo($_POST['hdnIdVocativo']);
        $objVocativoDTO->setStrExpressao($_POST['txtExpressao']);
        $objVocativoDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objVocativoDTO->getNumIdVocativo().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarVocativo'])) {
        try{
          $objVocativoRN = new VocativoRN();
          $objVocativoRN->alterarRN0308($objVocativoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Vocativo "'.$objVocativoDTO->getStrExpressao().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objVocativoDTO->getNumIdVocativo()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'vocativo_consultar':
      $strTitulo = "Consultar Vocativo";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_vocativo'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objVocativoDTO->setNumIdVocativo($_GET['id_vocativo']);
      $objVocativoDTO->retTodos();
      $objVocativoRN = new VocativoRN();
      $objVocativoDTO = $objVocativoRN->consultarRN0309($objVocativoDTO);
      if ($objVocativoDTO===null){
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
#lblExpressao {position:absolute;left:0%;top:0%;width:60%;}
#txtExpressao {position:absolute;left:0%;top:6%;width:60%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){

  <?if ($bolOk){?>

    var sel = window.parent.document.getElementById('selVocativo');
    infraSelectAdicionarOption(sel,'<?=PaginaSEI::formatarParametrosJavaScript($objVocativoDTO->getStrExpressao())?>','<?=$objVocativoDTO->getNumIdVocativo()?>');
    infraSelectSelecionarItem(sel,'<?=$objVocativoDTO->getNumIdVocativo()?>');
    self.setTimeout('infraFecharJanelaModal()',200);

  <?}else{?>

  if ('<?=$_GET['acao']?>'=='vocativo_cadastrar'){
    document.getElementById('txtExpressao').focus();
  } else if ('<?=$_GET['acao']?>'=='vocativo_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
  	document.getElementById('btnCancelar').focus();
  }

  infraEfeitoTabelas();
  <?}?>
}

function OnSubmitForm() {
  return validarFormRI0333();
}

function validarFormRI0333() {
  if (infraTrim(document.getElementById('txtExpressao').value)=='') {
    alert('Informe a Expressão.');
    document.getElementById('txtExpressao').focus();
    return false;
  }

  return true;
}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmVocativoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblExpressao" for="txtExpressao" accesskey="E" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">E</span>xpressão:</label>
  <input type="text" id="txtExpressao" name="txtExpressao" class="infraText" value="<?=PaginaSEI::tratarHTML($objVocativoDTO->getStrExpressao());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdVocativo" name="hdnIdVocativo" value="<?=$objVocativoDTO->getNumIdVocativo();?>" />
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