<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/09/2022 - criado por mgb29
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

  PaginaSEI::getInstance()->verificarSelecao('etapa_trabalho_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_plano_trabalho', 'arvore', 'id_procedimento'));

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $objEtapaTrabalhoDTO = new EtapaTrabalhoDTO();

  $strDesabilitar = '';
  $strOcultar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'etapa_trabalho_cadastrar':
      $strTitulo = 'Nova Etapa de Trabalho';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarEtapaTrabalho" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objEtapaTrabalhoDTO->setNumIdEtapaTrabalho(null);
      $objEtapaTrabalhoDTO->setNumIdPlanoTrabalho($_GET['id_plano_trabalho']);
      $objEtapaTrabalhoDTO->setStrNome($_POST['txtNome']);
      $objEtapaTrabalhoDTO->setStrDescricao($_POST['txaDescricao']);

      if (!isset($_POST['txtOrdem'])) {
        $objEtapaTrabalhoDTOOrdem = new EtapaTrabalhoDTO();
        $objEtapaTrabalhoDTOOrdem->setNumMaxRegistrosRetorno(1);
        $objEtapaTrabalhoDTOOrdem->retNumOrdem();
        $objEtapaTrabalhoDTOOrdem->setNumIdPlanoTrabalho($_GET['id_plano_trabalho']);
        $objEtapaTrabalhoDTOOrdem->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_DESC);

        $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
        $objEtapaTrabalhoDTOOrdem = $objEtapaTrabalhoRN->consultar($objEtapaTrabalhoDTOOrdem);

        if ($objEtapaTrabalhoDTOOrdem == null) {
          $objEtapaTrabalhoDTO->setNumOrdem(10);
        } else {
          if ($objEtapaTrabalhoDTOOrdem->getNumOrdem() >= 10 && $objEtapaTrabalhoDTOOrdem->getNumOrdem() % 10 == 0) {
            $objEtapaTrabalhoDTO->setNumOrdem($objEtapaTrabalhoDTOOrdem->getNumOrdem() + 10);
          } else {
            $objEtapaTrabalhoDTO->setNumOrdem($objEtapaTrabalhoDTOOrdem->getNumOrdem() + 1);
          }
        }
      } else {
        $objEtapaTrabalhoDTO->setNumOrdem($_POST['txtOrdem']);
      }


      $objEtapaTrabalhoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarEtapaTrabalho'])) {
        try {
          $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
          $objEtapaTrabalhoDTO = $objEtapaTrabalhoRN->cadastrar($objEtapaTrabalhoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Etapa de Trabalho "' . $objEtapaTrabalhoDTO->getStrNome() . '" cadastrada com sucesso.');
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . PaginaSEI::getInstance()->montarAncora($objEtapaTrabalhoDTO->getNumIdEtapaTrabalho())));
          die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'etapa_trabalho_alterar':
      $strTitulo = 'Alterar Etapa de Trabalho';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarEtapaTrabalho" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_etapa_trabalho'])) {
        $objEtapaTrabalhoDTO->setBolExclusaoLogica(false);
        $objEtapaTrabalhoDTO->setNumIdEtapaTrabalho($_GET['id_etapa_trabalho']);
        $objEtapaTrabalhoDTO->retTodos(true);
        $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
        $objEtapaTrabalhoDTO = $objEtapaTrabalhoRN->consultar($objEtapaTrabalhoDTO);
        if ($objEtapaTrabalhoDTO == null) {
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objEtapaTrabalhoDTO->setNumIdEtapaTrabalho($_POST['hdnIdEtapaTrabalho']);
        $objEtapaTrabalhoDTO->setNumIdPlanoTrabalho($_GET['id_plano_trabalho']);
        $objEtapaTrabalhoDTO->setStrNome($_POST['txtNome']);
        $objEtapaTrabalhoDTO->setStrDescricao($_POST['txaDescricao']);
        $objEtapaTrabalhoDTO->setNumOrdem($_POST['txtOrdem']);
        //$objEtapaTrabalhoDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objEtapaTrabalhoDTO->getNumIdEtapaTrabalho())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarEtapaTrabalho'])) {
        try {
          $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
          $objEtapaTrabalhoRN->alterar($objEtapaTrabalhoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Etapa de Trabalho "' . $objEtapaTrabalhoDTO->getStrNome() . '" alterada com sucesso.');
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objEtapaTrabalhoDTO->getNumIdEtapaTrabalho())));
          die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'etapa_trabalho_consultar':
      $strTitulo = 'Consultar Etapa de Trabalho';

      if (PaginaSEI::getInstance()->getAcaoRetorno() == 'plano_trabalho_detalhar') {
        $strOcultar = 'display:none;';
      }

      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_etapa_trabalho'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objEtapaTrabalhoDTO->setNumIdEtapaTrabalho($_GET['id_etapa_trabalho']);
      $objEtapaTrabalhoDTO->setBolExclusaoLogica(false);
      $objEtapaTrabalhoDTO->retTodos(true);
      $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
      $objEtapaTrabalhoDTO = $objEtapaTrabalhoRN->consultar($objEtapaTrabalhoDTO);
      if ($objEtapaTrabalhoDTO === null) {
        throw new InfraException("Registro não encontrado.");
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelPlanoTrabalho = PlanoTrabalhoINT::montarSelectNome('null', '&nbsp;', $objEtapaTrabalhoDTO->getNumIdPlanoTrabalho());
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

    #lblNome {position: absolute;left: 0%;top: 0%;width: 60%;}

    #txtNome {position: absolute;left: 0%;top: 40%;width: 60%;}

    #lblDescricao {position: absolute;left: 0%;top: 0%;width: 80%;}

    #txaDescricao {position: absolute;left: 0%;top: 16%;width: 80%;}

    #lblOrdem {position: absolute;left: 0%;top: 0%;width: 10%;<?=$strOcultar?>}

    #txtOrdem {position: absolute;left: 0%;top: 40%;width: 10%;<?=$strOcultar?>}

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
      if ('<?=$_GET['acao']?>' == 'etapa_trabalho_cadastrar') {
        document.getElementById('txtNome').focus();
      } else if ('<?=$_GET['acao']?>' == 'etapa_trabalho_consultar') {
        infraDesabilitarCamposAreaDados();
      } else {
        document.getElementById('btnCancelar').focus();
      }

      infraEfeitoTabelas(true);
    }

    function validarCadastro() {

      if (infraTrim(document.getElementById('txtNome').value) == '') {
        alert('Informe o Nome.');
        document.getElementById('txtNome').focus();
        return false;
      }

      if (infraTrim(document.getElementById('txtOrdem').value) == '') {
        alert('Informe a Ordem.');
        document.getElementById('txtOrdem').focus();
        return false;
      }

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
  <form id="frmEtapaTrabalhoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblPlanoTrabalho" for="selPlanoTrabalho" accesskey="" class="infraLabelObrigatorio">Plano de Trabalho:</label>
    <select id="selPlanoTrabalho" name="selPlanoTrabalho" disabled="disabled" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelPlanoTrabalho?>
    </select>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objEtapaTrabalhoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('11em');
    ?>
    <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
    <textarea id="txaDescricao" name="txaDescricao" rows="4" class="infraTextarea" maxlength="4000" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objEtapaTrabalhoDTO->getStrDescricao())?></textarea>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblOrdem" for="txtOrdem" accesskey="" class="infraLabelObrigatorio">Ordem:</label>
    <input type="text" id="txtOrdem" name="txtOrdem" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objEtapaTrabalhoDTO->getNumOrdem());?>"
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>
    <input type="hidden" id="hdnIdEtapaTrabalho" name="hdnIdEtapaTrabalho" value="<?=$objEtapaTrabalhoDTO->getNumIdEtapaTrabalho();?>"/>
    <?
    //PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
