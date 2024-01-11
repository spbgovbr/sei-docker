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

  $strParametros = '';
  if (isset($_GET['acesso'])){
    $strParametros .= '&acesso='.$_GET['acesso'];
  }

  $bolExecutouOK = false;

  switch($_GET['acao']){

    case 'procedimento_credencial_ativar':

      $strTitulo = 'Ativação de Credencial na Unidade';

      if ($_GET['acao_origem']=='procedimento_acervo_sigilosos_unidade'){
        $arrIdProcedimento = PaginaSEI::getInstance()->getArrStrItensSelecionados();
      }else{
        $arrIdProcedimento = explode(',', $_POST['hdnIdProcedimentos']);
      }

      $objAtivarCredencialDTO = new AtivarCredencialDTO();
      $objAtivarCredencialDTO->setNumIdUsuario($_POST['selUsuario']);
      $objAtivarCredencialDTO->setArrObjProcedimentoDTO(InfraArray::gerarArrInfraDTO('ProcedimentoDTO','IdProcedimento',$arrIdProcedimento));


      if ($_POST['sbmSalvar']=='Salvar') {
        try {
          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->ativarCredencial($objAtivarCredencialDTO);
          $bolExecutouOK = true;
          $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_acervo_sigilosos_unidade&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($arrIdProcedimento));

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

  $strItensSelUsuario = UsuarioINT::montarSelectPorUnidadeRI0811('null','&nbsp;',$objAtivarCredencialDTO->getNumIdUsuario(),SessaoSEI::getInstance()->getNumIdUnidadeAtual());

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
#selUsuario {position:absolute;left:0%;top:50%;width:60%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){

<?if($bolExecutouOK){?>
  var frmOpener = window.parent.document.getElementById('frmProcedimentoAcervoSigilososUnidade');
  window.parent.document.getElementById('hdnInfraItensSelecionados').value = '';
  frmOpener.action = '<?=$strLinkRetorno?>';
  frmOpener.submit();
  infraFecharJanelaModal();
  return;
<?}?>

  document.getElementById('selUsuario').focus();
}

function OnSubmitForm() {
  return validarAtivacao();
}

function validarAtivacao(){

  if (!infraSelectSelecionado('selUsuario')) {
    alert('Usuário não informado.');
    document.getElementById('selUsuario').focus();
    return false;
  }

  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmProcedimentoCredencialAtivar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblUsuario" for="selUsuario" class="infraLabelOpcional">Ativar credencial nesta unidade para:</label>
    <select id="selUsuario" name="selUsuario" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelUsuario?>
    </select>

    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

    <input type="hidden" id="hdnIdProcedimentos" name="hdnIdProcedimentos" value="<?=implode(',', $arrIdProcedimento)?>" />

  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>