<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/05/2009 - criado por mga
 *
 * Versão do Gerador de Código: 1.26.0
 *
 * Versão no CVS: $Id$
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

  PaginaSip::getInstance()->verificarSelecao('unidade_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgao'));

  $objUnidadeDTO = new UnidadeDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'unidade_cadastrar':
      $strTitulo = 'Nova Unidade';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarUnidade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objUnidadeDTO->setNumIdUnidade(null);
      $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgao');
      if ($numIdOrgao !== '') {
        $objUnidadeDTO->setNumIdOrgao($numIdOrgao);
      } else {
        $objUnidadeDTO->setNumIdOrgao(null);
      }

      $objUnidadeDTO->setStrIdOrigem($_POST['txtIdOrigem']);
      $objUnidadeDTO->setStrSigla($_POST['txtSigla']);
      $objUnidadeDTO->setStrDescricao($_POST['txtDescricao']);
      $objUnidadeDTO->setStrSinGlobal('N');
      $objUnidadeDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarUnidade'])) {
        try {
          $objUnidadeRN = new UnidadeRN();
          $objUnidadeDTO = $objUnidadeRN->cadastrar($objUnidadeDTO);
          PaginaSip::getInstance()->setStrMensagem('Unidade "' . $objUnidadeDTO->getStrSigla() . '" cadastrada com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_unidade=' . $objUnidadeDTO->getNumIdUnidade() . '#ID-' . $objUnidadeDTO->getNumIdUnidade()));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'unidade_alterar':
      $strTitulo = 'Alterar Unidade';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarUnidade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_unidade'])) {
        $objUnidadeDTO->setNumIdUnidade($_GET['id_unidade']);
        $objUnidadeDTO->retTodos();
        $objUnidadeRN = new UnidadeRN();
        $objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);
        if ($objUnidadeDTO == null) {
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objUnidadeDTO->setNumIdUnidade($_POST['hdnIdUnidade']);
        $objUnidadeDTO->setNumIdOrgao($_POST['selOrgao']);
        $objUnidadeDTO->setStrIdOrigem($_POST['txtIdOrigem']);
        $objUnidadeDTO->setStrSigla($_POST['txtSigla']);
        $objUnidadeDTO->setStrDescricao($_POST['txtDescricao']);
        $objUnidadeDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '#ID-' . $objUnidadeDTO->getNumIdUnidade() . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarUnidade'])) {
        try {
          $objUnidadeRN = new UnidadeRN();
          $objUnidadeRN->alterar($objUnidadeDTO);
          PaginaSip::getInstance()->setStrMensagem('Unidade "' . $objUnidadeDTO->getStrSigla() . '" alterada com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '#ID-' . $objUnidadeDTO->getNumIdUnidade()));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'unidade_consultar':
      $strTitulo = 'Consultar Unidade';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '#ID-' . $_GET['id_unidade'] . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objUnidadeDTO->setNumIdUnidade($_GET['id_unidade']);
      $objUnidadeDTO->setBolExclusaoLogica(false);
      $objUnidadeDTO->retTodos();
      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);
      if ($objUnidadeDTO === null) {
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaTodos('null', '&nbsp;', $objUnidadeDTO->getNumIdOrgao());
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
  #lblOrgao {position:absolute;left:0%;top:0%;width:25%;}
  #selOrgao {position:absolute;left:0%;top:6%;width:25%;}

  #lblSigla {position:absolute;left:0%;top:16%;width:30%;}
  #txtSigla {position:absolute;left:0%;top:22%;width:30%;}

  #lblDescricao {position:absolute;left:0%;top:32%;width:95%;}
  #txtDescricao {position:absolute;left:0%;top:38%;width:95%;}

  #lblIdOrigem {position:absolute;left:0%;top:48%;width:20%;}
  #txtIdOrigem {position:absolute;left:0%;top:54%;width:20%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
  function inicializar(){
  if ('<?=$_GET['acao']?>'=='unidade_cadastrar'){
  document.getElementById('selOrgao').focus();
  } else if ('<?=$_GET['acao']?>'=='unidade_consultar'){
  infraDesabilitarCamposAreaDados();
  }else{
  document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
  }

  function validarCadastro() {
  if (!infraSelectSelecionado('selOrgao')) {
  alert('Selecione um Órgão.');
  document.getElementById('selOrgao').focus();
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

  return true;
  }

  function OnSubmitForm() {
  return validarCadastro();
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmUnidadeCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('30em');
    ?>
    <label id="lblOrgao" for="selOrgao" accesskey="o" class="infraLabelObrigatorio">Órgã<span
        class="infraTeclaAtalho">o</span>:</label>
    <select id="selOrgao" name="selOrgao" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgao?>
    </select>

    <label id="lblSigla" for="txtSigla" accesskey="a" class="infraLabelObrigatorio">Sigl<span
        class="infraTeclaAtalho">a</span>:</label>
    <input type="text" id="txtSigla" name="txtSigla" class="infraText"
           value="<?=PaginaSip::tratarHTML($objUnidadeDTO->getStrSigla());?>"
           onkeypress="return infraMascaraTexto(this,event,30);" maxlength="30"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblDescricao" for="txtDescricao" accesskey="D" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">D</span>escrição:</label>
    <input type="text" id="txtDescricao" name="txtDescricao" class="infraText"
           value="<?=PaginaSip::tratarHTML($objUnidadeDTO->getStrDescricao());?>"
           onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblIdOrigem" for="txtIdOrigem" accesskey="" class="infraLabelOpcional">ID Origem:</label>
    <input type="text" id="txtIdOrigem" name="txtIdOrigem" class="infraText"
           value="<?=PaginaSip::tratarHTML($objUnidadeDTO->getStrIdOrigem());?>" maxlength="50"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value="<?=$objUnidadeDTO->getNumIdUnidade();?>"/>
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