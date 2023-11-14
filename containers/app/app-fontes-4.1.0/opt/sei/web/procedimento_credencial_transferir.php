<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/06/2016 - criado por bcu
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

  PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){

    case 'procedimento_credencial_transferir':

      $strTitulo = 'Transferência de Credencial';

      if ($_GET['acao_origem']=='procedimento_credencial_listar'){
        $arrStrIdProtocolo = PaginaSEI::getInstance()->getArrStrItensSelecionados();
      }else{
        $arrStrIdProtocolo = explode(',',$_POST['hdnIdProtocolos']);
      }

      $objTransferirCredencialDTO = new TransferirCredencialDTO();
      $objTransferirCredencialDTO->setArrObjProtocoloDTO(InfraArray::gerarArrInfraDTO('ProtocoloDTO','IdProtocolo',$arrStrIdProtocolo));
      $objTransferirCredencialDTO->setNumIdUsuario($_POST['hdnIdUsuario']);

      if ($_POST['sbmSalvar']=='Salvar') {
        try {
          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->transferirCredencial($objTransferirCredencialDTO);
          $bolExecutouOK=true;
          $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_credencial_listar&acao_origem='.$_GET['acao'].PaginaSEI::montarAncora($arrStrIdProtocolo));

        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
    break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[]='<button type="submit" accesskey="S" id="sbmSalvar" name="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar </button>';
//  $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="window.close()" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
  $strLinkAjaxUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_outros');

  $strIdProtocolos = implode(',',$arrStrIdProtocolo);

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
#lblUsuario {position:absolute;left:0%;top:10%;}
#txtUsuario {position:absolute;left:0%;top:50%;width:60%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAutoCompletarUsuario = null;

function inicializar(){

  <?if($bolExecutouOK){?>
    var frmOpener = window.parent.document.getElementById('frmProcedimentoCredencialLista');
    window.parent.document.getElementById('hdnInfraItensSelecionados').value = '';
    frmOpener.action = '<?=$strLinkRetorno?>';
    frmOpener.submit();
    infraFecharJanelaModal();
    return;
  <?}?>

  objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuario?>');
  //objAutoCompletarUsuario.maiusculas = true;
  //objAutoCompletarUsuario.mostrarAviso = true;
  //objAutoCompletarUsuario.tempoAviso = 1000;
  //objAutoCompletarUsuario.tamanhoMinimo = 3;
  objAutoCompletarUsuario.limparCampo = true;
  //objAutoCompletarUsuario.bolExecucaoAutomatica = false;

  objAutoCompletarUsuario.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUsuario').value;
  };
  
  objAutoCompletarUsuario.processarResultado = function(id,descricao,complemento){
    if (id!=''){
    //
    }
  };
  
  objAutoCompletarUsuario.selecionar('<?=$_POST['hdnIdUsuario']?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($_POST['txtUsuario'])?>');
  
  document.getElementById('txtUsuario').focus();

}

function OnSubmitForm() {
  return validarTransferencia();
}

function validarTransferencia(){
  
	if (infraTrim(document.getElementById('hdnIdUsuario').value)=='') {
		alert('Usuário não informado.');
		document.getElementById('txtUsuario').focus();
		return false;
	}
	
  if (document.getElementById('hdnIdProtocolos').value==''){
    alert('Nenhum processo selecionado.');
    return false;
  }

  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProcedimentoCredencialTransferir" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>

  <label id="lblUsuario" for="txtUsuario" class="infraLabelOpcional">Transferir credencial nesta unidade para:</label>
  <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" class="infraText" value=""/>

  <input type="hidden" id="hdnIdProtocolos" name="hdnIdProtocolos" value="<?=$strIdProtocolos;?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>