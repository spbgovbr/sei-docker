<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/10/2022 - criado por mgb29
*
*
*/

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSEI::getInstance()->validarSessao();
  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  if (isset($_GET['id_plano_trabalho'])) {
    PaginaSEI::getInstance()->salvarCampo('selPlanoTrabalho', $_GET['id_plano_trabalho']);
  } else {
    PaginaSEI::getInstance()->salvarCamposPost(array('selPlanoTrabalho'));
  }

  $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'plano_trabalho_clonar':

      $strTitulo = 'Clonar Plano de Trabalho';

      $arrComandos[] = '<input type="submit" name="sbmSalvar" value="Clonar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . PaginaSEI::getInstance()->montarAncora($_GET['id_plano_trabalho'])) . '\';" class="infraButton" />';

      $numIdPlanoTrabalho = PaginaSEI::getInstance()->recuperarCampo('selPlanoTrabalho');
      if ($numIdPlanoTrabalho !== '') {
        $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($numIdPlanoTrabalho);
      } else {
        $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho(null);
      }

      $objPlanoTrabalhoDTO->setStrNome($_POST['txtNome']);

      if (isset($_POST['sbmSalvar'])) {
        try {
          $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
          $objPlanoTrabalhoDTO = $objPlanoTrabalhoRN->clonar($objPlanoTrabalhoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Plano de Trabalho "' . $objPlanoTrabalhoDTO->getStrNome() . '" clonado com sucesso.');
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_listar&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho())));
          die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelPlanoTrabalho = PlanoTrabalhoINT::montarSelectNome('null', '&nbsp;', $numIdPlanoTrabalho);
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

#lblPlanoTrabalho {position:absolute;left:0%;top:0%;width:50%;}
#selPlanoTrabalho {position:absolute;left:0%;top:40%;width:50%;}

#lblNome {position:absolute;left:0%;top:0%;width:50%;}
#txtNome {position:absolute;left:0%;top:40%;width:50%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
document.getElementById('txtNome').focus();
}

function OnSubmitForm() {
return validarForm();
}

function validarForm() {

if (!infraSelectSelecionado(document.getElementById('selPlanoTrabalho'))) {
alert('Selecione PlanoTrabalho Origem.');
document.getElementById('selPlanoTrabalho').focus();
return false;
}

if (infraTrim(document.getElementById('txtNome').value)=='') {
alert('Informe Nome de .');
document.getElementById('txtNome').focus();
return false;
}

return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmPlanoTrabalhoClonar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao('Clonar PlanoTrabalho');
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>

  <label id="lblPlanoTrabalho" for="selPlanoTrabalho" accesskey="" class="infraLabelObrigatorio">Plano de Trabalho Origem:</label>
  <select id="selPlanoTrabalho" name="selPlanoTrabalho" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelPlanoTrabalho?>
  </select>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome Destino:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objPlanoTrabalhoDTO->getStrNome());?>" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
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
