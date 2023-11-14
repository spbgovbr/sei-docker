<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 26/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('procedimento_plano_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'id_procedimento'));

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $objProcedimentoDTO = new ProcedimentoDTO();

  $arrComandos = array();

  $bolExecutouOK = false;

  switch ($_GET['acao']) {
    case 'procedimento_plano_associar':
      $strTitulo = 'Associar Plano de Trabalho';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
      $objProcedimentoDTO->setNumIdPlanoTrabalho($_POST['selPlanoTrabalho']);

      if (isset($_POST['sbmSalvar'])) {
        try {
          $objProcedimentoRN = new ProcedimentoRN();
          $objProcedimentoDTO = $objProcedimentoRN->associarPlanoTrabalho($objProcedimentoDTO);
          $bolExecutouOK = true;
          //header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']));
          //die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $numIdPlanoTrabalho = null;
  if (!isset($_POST['selPlanoTrabalho'])) {
    $objProcedimentoDTO = new ProcedimentoDTO();
    $objProcedimentoDTO->retNumIdPlanoTrabalho();
    $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);

    $objProcedimentoRN = new ProcedimentoRN();
    $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

    if ($objProcedimentoDTO != null) {
      $numIdPlanoTrabalho = $objProcedimentoDTO->getNumIdPlanoTrabalho();
    }
  } else {
    $numIdPlanoTrabalho = $_POST['selPlanoTrabalho'];
  }

  $strItensSelPlanoTrabalho = PlanoTrabalhoINT::montarSelectNome('null', '&nbsp;', $numIdPlanoTrabalho);

  $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=' . $_GET['acao_origem']);
} catch (Exception $e) {
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<? if (0){ ?>
  <style><?}?>

    #lblPlanoTrabalho {position: absolute;left: 0%;top: 0%;width: 60%;}

    #selPlanoTrabalho {position: absolute;left: 0%;top: 40%;width: 60%;}

    <? if (0){ ?></style><?
} ?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<? if (0){ ?>
  <script type="text/javascript"><?}?>

    function inicializar() {

      <? if ($bolExecutouOK){ ?>
      parent.parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
      <? } ?>

      document.getElementById('btnCancelar').focus();
    }

    function validarCadastro() {

      /*
      if (!infraSelectSelecionado('selPlanoTrabalho')) {
        alert('Selecione o Plano de Trabalho.');
        document.getElementById('selPlanoTrabalho').focus();
        return false;
      }
      */

      return true;
    }

    function OnSubmitForm() {
      return validarCadastro();
    }

    <? if (0){ ?></script><?
} ?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmProcedimentoPlanoAssociacao" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblPlanoTrabalho" for="selPlanoTrabalho" accesskey="" class="infraLabelObrigatorio">Plano de Trabalho:</label>
    <select id="selPlanoTrabalho" name="selPlanoTrabalho" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelPlanoTrabalho?>
    </select>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    //PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
