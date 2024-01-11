<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/07/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  PaginaSip::getInstance()->verificarSelecao('grupo_perfil_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  SessaoSip::getInstance()->setArrParametrosRepasseLink(array('id_sistema'));

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema'));

  $objGrupoPerfilDTO = new GrupoPerfilDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'grupo_perfil_cadastrar':
      $strTitulo = 'Novo Grupo de Perfil';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarGrupoPerfil" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objGrupoPerfilDTO->setNumIdGrupoPerfil(null);

      //ORGAO
      $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
      if ($numIdOrgao !== '') {
        $objGrupoPerfilDTO->setNumIdOrgaoSistema($numIdOrgao);
      } else {
        $objGrupoPerfilDTO->setNumIdOrgaoSistema(null);
      }

      $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
      if ($numIdSistema !== '') {
        $objGrupoPerfilDTO->setNumIdSistema($numIdSistema);
      } else {
        $objGrupoPerfilDTO->setNumIdSistema(null);
      }

      $objGrupoPerfilDTO->setStrNome($_POST['txtNome']);
      $objGrupoPerfilDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarGrupoPerfil'])) {
        try {
          $objGrupoPerfilRN = new GrupoPerfilRN();
          $objGrupoPerfilDTO = $objGrupoPerfilRN->cadastrar($objGrupoPerfilDTO);
          PaginaSip::getInstance()->adicionarMensagem('Grupo de Perfil "' . $objGrupoPerfilDTO->getStrNome() . '" cadastrado com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_grupo_perfil=' . $objGrupoPerfilDTO->getNumIdGrupoPerfil() . '&id_sistema=' . $objGrupoPerfilDTO->getNumIdSistema() . PaginaSip::getInstance()->montarAncora($objGrupoPerfilDTO->getNumIdGrupoPerfil() . '-' . $objGrupoPerfilDTO->getNumIdSistema())));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'grupo_perfil_alterar':
      $strTitulo = 'Alterar Grupo de Perfil';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarGrupoPerfil" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_grupo_perfil']) && isset($_GET['id_sistema'])) {
        $objGrupoPerfilDTO->retTodos(true);
        $objGrupoPerfilDTO->setBolExclusaoLogica(false);
        $objGrupoPerfilDTO->setNumIdGrupoPerfil($_GET['id_grupo_perfil']);
        $objGrupoPerfilDTO->setNumIdSistema($_GET['id_sistema']);

        $objGrupoPerfilRN = new GrupoPerfilRN();
        $objGrupoPerfilDTO = $objGrupoPerfilRN->consultar($objGrupoPerfilDTO);
        if ($objGrupoPerfilDTO == null) {
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objGrupoPerfilDTO->setNumIdGrupoPerfil($_POST['hdnIdGrupoPerfil']);
        $objGrupoPerfilDTO->setNumIdOrgaoSistema($_POST['hdnIdOrgaoSistema']);
        $objGrupoPerfilDTO->setNumIdSistema($_POST['hdnIdSistema']);
        $objGrupoPerfilDTO->setStrNome($_POST['txtNome']);
        //$objGrupoPerfilDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($objGrupoPerfilDTO->getNumIdGrupoPerfil() . '-' . $objGrupoPerfilDTO->getNumIdSistema())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarGrupoPerfil'])) {
        try {
          $objGrupoPerfilRN = new GrupoPerfilRN();
          $objGrupoPerfilRN->alterar($objGrupoPerfilDTO);
          PaginaSip::getInstance()->adicionarMensagem('Grupo de Perfil "' . $objGrupoPerfilDTO->getStrNome() . '" alterado com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($objGrupoPerfilDTO->getNumIdGrupoPerfil() . '-' . $objGrupoPerfilDTO->getNumIdSistema())));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'grupo_perfil_consultar':
      $strTitulo = 'Consultar Grupo de Perfil';
      $arrComandos[] = '<button type="button" name="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($_GET['id_grupo_perfil'] . '-' . $_GET['id_sistema'])) . '\';" class="infraButton">Voltar</button>';
      $objGrupoPerfilDTO->setNumIdGrupoPerfil($_GET['id_grupo_perfil']);
      $objGrupoPerfilDTO->setNumIdSistema($_GET['id_sistema']);
      $objGrupoPerfilDTO->setBolExclusaoLogica(false);
      $objGrupoPerfilDTO->retTodos(true);
      $objGrupoPerfilRN = new GrupoPerfilRN();
      $objGrupoPerfilDTO = $objGrupoPerfilRN->consultar($objGrupoPerfilDTO);
      if ($objGrupoPerfilDTO === null) {
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null', '&nbsp;', $objGrupoPerfilDTO->getNumIdOrgaoSistema());
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null', '&nbsp;', $objGrupoPerfilDTO->getNumIdSistema(), $objGrupoPerfilDTO->getNumIdOrgaoSistema());
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
<?
if (0){ ?>
  <style><?}?>

    #lblOrgaoSistema {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 20%;
    }

    #selOrgaoSistema {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 20%;
    }

    #lblSistema {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 20%;
    }

    #selSistema {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 20%;
    }

    #lblNome {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 80%;
    }

    #txtNome {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 80%;
    }


    <?
    if (0){ ?></style><?
} ?>
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
<?
if (0){ ?>
  <script type="text/javascript"><?}?>

    function inicializar() {
      if ('<?=$_GET['acao']?>' == 'grupo_perfil_cadastrar') {
        if (!infraSelectSelecionado('selSistema')) {
          document.getElementById('selOrgaoSistema').focus();
        } else {
          document.getElementById('txtNome').focus();
        }
      } else if ('<?=$_GET['acao']?>' == 'grupo_perfil_consultar') {
        infraDesabilitarCamposAreaDados();
      } else {
        document.getElementById('btnCancelar').focus();
      }
      infraEfeitoTabelas(true);
    }

    function validarCadastro() {

      if (!infraSelectSelecionado(document.getElementById('selOrgaoSistema'))) {
        alert('Selecione um Órgão do Sistema.');
        document.getElementById('selOrgaoSistema').focus();
        return false;
      }

      if (!infraSelectSelecionado(document.getElementById('selSistema'))) {
        alert('Selecione um Sistema.');
        document.getElementById('selSistema').focus();
        return false;
      }

      if (infraTrim(document.getElementById('txtNome').value) == '') {
        alert('Informe o Nome.');
        document.getElementById('txtNome').focus();
        return false;
      }

      return true;
    }

    function OnSubmitForm() {
      return validarCadastro();
    }

    function trocarOrgaoSistema(obj) {
      document.getElementById('selSistema').value = 'null';
      obj.form.submit();
    }


    <?
    if (0){ ?></script><?
} ?>
<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmGrupoPerfilCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span
        class="infraTeclaAtalho">r</span>gão do Sistema:</label>
    <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelOrgaoSistema?>
    </select>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblSistema" for="selSistema" accesskey="" class="infraLabelObrigatorio">Sistema:</label>
    <select id="selSistema" name="selSistema" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelSistema?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText"
           value="<?=PaginaSip::tratarHTML($objGrupoPerfilDTO->getStrNome());?>"
           onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    ?>
    <input type="hidden" id="hdnIdGrupoPerfil" name="hdnIdGrupoPerfil"
           value="<?=$objGrupoPerfilDTO->getNumIdGrupoPerfil();?>"/>
    <input type="hidden" name="hdnIdOrgaoSistema" value="<?=$objGrupoPerfilDTO->getNumIdOrgaoSistema();?>"/>
    <input type="hidden" id="hdnIdSistema" name="hdnIdSistema" value="<?=$objGrupoPerfilDTO->getNumIdSistema();?>"/>
    <?
    //PaginaSip::getInstance()->montarAreaDebug();
    //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
