<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  if (isset($_GET['id_hierarquia_origem'])) {
    PaginaSip::getInstance()->salvarCampo('selHierarquiaOrigem', $_GET['id_hierarquia_origem']);
  } else {
    PaginaSip::getInstance()->salvarCamposPost(array('selHierarquiaOrigem'));
  }

  $objClonarHierarquiaDTO = new ClonarHierarquiaDTO();

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'hierarquia_clonar':

      $strTitulo = 'Clonar Hierarquia';
      $arrComandos[] = '<input type="submit" name="sbmClonarHierarquia" value="Clonar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . PaginaSip::getInstance()->montarAncora($_GET['id_hierarquia_origem'])) . '\';" class="infraButton" />';

      $numIdHierarquia = PaginaSip::getInstance()->recuperarCampo('selHierarquiaOrigem');
      if ($numIdHierarquia !== '') {
        $objClonarHierarquiaDTO->setNumIdHierarquiaOrigem($numIdHierarquia);
      } else {
        $objClonarHierarquiaDTO->setNumIdHierarquiaOrigem(null);
      }

      $objClonarHierarquiaDTO->setStrNomeDestino($_POST['txtNomeDestino']);

      if (isset($_POST['sbmClonarHierarquia'])) {
        try {
          $objHierarquiaRN = new HierarquiaRN();
          $objHierarquiaDTO = $objHierarquiaRN->clonar($objClonarHierarquiaDTO);
          PaginaSip::getInstance()->setStrMensagem('Hierarquia "' . $objClonarHierarquiaDTO->getStrNomeDestino() . '" clonada com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=hierarquia_listar&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($objHierarquiaDTO->getNumIdHierarquia())));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelHierarquiaOrigem = HierarquiaINT::montarSelectNome('null', '&nbsp;', $numIdHierarquia);
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Hierarquias');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>

  #lblHierarquiaOrigem {position:absolute;left:0%;top:0%;width:20%;}
  #selHierarquiaOrigem {position:absolute;left:0%;top:6%;width:20%;}

  #lblNomeDestino {position:absolute;left:0%;top:16%;width:15%;}
  #txtNomeDestino {position:absolute;left:0%;top:22%;width:15%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>


  function OnSubmitForm() {
  return validarForm();
  }

  function validarForm() {

  if (!infraSelectSelecionado(document.getElementById('selHierarquiaOrigem'))) {
  alert('Selecione uma hierarquia de origem.');
  document.getElementById('selHierarquiaOrigem').focus();
  return false;
  }

  if (infraTrim(document.getElementById('txtNomeDestino').value)=='') {
  alert('Informe Nome de destino.');
  document.getElementById('txtNomeDestino').focus();
  return false;
  }

  return true;
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody('Clonar Hierarquia');
?>
  <form id="frmRecursoLista" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('hierarquia_clonar.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao('Clonar Hierarquia');
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados('30em');
    ?>

    <label id="lblHierarquiaOrigem" for="selHierarquiaOrigem" accesskey="H" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">H</span>ierarquia Origem:</label>
    <select id="selHierarquiaOrigem" name="selHierarquiaOrigem" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelHierarquiaOrigem?>
    </select>

    <label id="lblNomeDestino" for="txtNomeDestino" accesskey="N" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">N</span>ome Destino:</label>
    <input type="text" id="txtNomeDestino" name="txtNomeDestino" class="infraText"
           value="<?=PaginaSip::tratarHTML($objClonarHierarquiaDTO->getStrNomeDestino());?>" maxlength="50"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>


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