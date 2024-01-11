<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/09/2022 - criado por mgb29
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

  PaginaSEI::getInstance()->verificarSelecao('item_etapa_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_plano_trabalho', 'id_etapa_trabalho', 'arvore', 'id_procedimento'));

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $objItemEtapaDTO = new ItemEtapaDTO();

  $strDesabilitar = '';
  $strOcultar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'item_etapa_cadastrar':
      $strTitulo = 'Novo Item da Etapa';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarItemEtapa" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objItemEtapaDTO->setNumIdItemEtapa(null);
      $objItemEtapaDTO->setNumIdEtapaTrabalho($_GET['id_etapa_trabalho']);
      $objItemEtapaDTO->setStrNome($_POST['txtNome']);
      $objItemEtapaDTO->setStrDescricao($_POST['txaDescricao']);

      if (!isset($_POST['txtOrdem'])) {
        $objItemEtapaDTOOrdem = new ItemEtapaDTO();
        $objItemEtapaDTOOrdem->setNumMaxRegistrosRetorno(1);
        $objItemEtapaDTOOrdem->retNumOrdem();
        $objItemEtapaDTOOrdem->setNumIdEtapaTrabalho($_GET['id_etapa_trabalho']);
        $objItemEtapaDTOOrdem->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_DESC);

        $objItemEtapaRN = new ItemEtapaRN();
        $objItemEtapaDTOOrdem = $objItemEtapaRN->consultar($objItemEtapaDTOOrdem);

        if ($objItemEtapaDTOOrdem == null) {
          $objItemEtapaDTO->setNumOrdem(10);
        } else {
          if ($objItemEtapaDTOOrdem->getNumOrdem() >= 10 && $objItemEtapaDTOOrdem->getNumOrdem() % 10 == 0) {
            $objItemEtapaDTO->setNumOrdem($objItemEtapaDTOOrdem->getNumOrdem() + 10);
          } else {
            $objItemEtapaDTO->setNumOrdem($objItemEtapaDTOOrdem->getNumOrdem() + 1);
          }
        }
      } else {
        $objItemEtapaDTO->setNumOrdem($_POST['txtOrdem']);
      }

      $objItemEtapaDTO->setStrSinAtivo('S');

      $arrObjRelItemEtapaUnidadeDTO = array();
      $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidade']);
      foreach ($arrOpcoes as $opcao) {
        $objRelItemEtapaUnidadeDTO = new RelItemEtapaUnidadeDTO();
        $objRelItemEtapaUnidadeDTO->setNumIdUnidade($opcao);
        $arrObjRelItemEtapaUnidadeDTO[] = $objRelItemEtapaUnidadeDTO;
      }
      $objItemEtapaDTO->setArrObjRelItemEtapaUnidadeDTO($arrObjRelItemEtapaUnidadeDTO);

      $arrObjRelItemEtapaSerieDTO = array();
      $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSerie']);
      foreach ($arrOpcoes as $opcao) {
        $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
        $objRelItemEtapaSerieDTO->setNumIdSerie($opcao);
        $arrObjRelItemEtapaSerieDTO[] = $objRelItemEtapaSerieDTO;
      }
      $objItemEtapaDTO->setArrObjRelItemEtapaSerieDTO($arrObjRelItemEtapaSerieDTO);

      if (isset($_POST['sbmCadastrarItemEtapa'])) {
        try {
          $objItemEtapaRN = new ItemEtapaRN();
          $objItemEtapaDTO = $objItemEtapaRN->cadastrar($objItemEtapaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Item da Etapa "' . $objItemEtapaDTO->getStrNome() . '" cadastrado com sucesso.');

          if (PaginaSEI::getInstance()->getAcaoRetorno() == 'plano_trabalho_configurar') {
            $strAncora = $objItemEtapaDTO->getNumIdEtapaTrabalho() . '-' . $objItemEtapaDTO->getNumIdItemEtapa();
          } else {
            $strAncora = $objItemEtapaDTO->getNumIdItemEtapa();
          }

          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_item_etapa=' . $objItemEtapaDTO->getNumIdItemEtapa() . PaginaSEI::getInstance()->montarAncora($strAncora)));
          die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'item_etapa_alterar':
      $strTitulo = 'Alterar Item da Etapa';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarItemEtapa" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_item_etapa'])) {
        $objItemEtapaDTO->setNumIdItemEtapa($_GET['id_item_etapa']);
        $objItemEtapaDTO->setBolExclusaoLogica(false);
        $objItemEtapaDTO->retTodos(true);
        $objItemEtapaRN = new ItemEtapaRN();
        $objItemEtapaDTO = $objItemEtapaRN->consultar($objItemEtapaDTO);
        if ($objItemEtapaDTO == null) {
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objItemEtapaDTO->setNumIdItemEtapa($_POST['hdnIdItemEtapa']);
        $objItemEtapaDTO->setNumIdEtapaTrabalho($_GET['id_etapa_trabalho']);
        $objItemEtapaDTO->setStrNome($_POST['txtNome']);
        $objItemEtapaDTO->setStrDescricao($_POST['txaDescricao']);
        $objItemEtapaDTO->setNumOrdem($_POST['txtOrdem']);
        //$objItemEtapaDTO->setStrSinAtivo('S');
      }

      if (PaginaSEI::getInstance()->getAcaoRetorno() == 'plano_trabalho_configurar') {
        $strAncora = $objItemEtapaDTO->getNumIdEtapaTrabalho() . '-' . $objItemEtapaDTO->getNumIdItemEtapa();
      } else {
        $strAncora = $objItemEtapaDTO->getNumIdItemEtapa();
      }


      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($strAncora)) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $arrObjRelItemEtapaUnidadeDTO = array();
      $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidade']);
      foreach ($arrOpcoes as $opcao) {
        $objRelItemEtapaUnidadeDTO = new RelItemEtapaUnidadeDTO();
        $objRelItemEtapaUnidadeDTO->setNumIdUnidade($opcao);
        $arrObjRelItemEtapaUnidadeDTO[] = $objRelItemEtapaUnidadeDTO;
      }
      $objItemEtapaDTO->setArrObjRelItemEtapaUnidadeDTO($arrObjRelItemEtapaUnidadeDTO);

      $arrObjRelItemEtapaSerieDTO = array();
      $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSerie']);
      foreach ($arrOpcoes as $opcao) {
        $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
        $objRelItemEtapaSerieDTO->setNumIdSerie($opcao);
        $arrObjRelItemEtapaSerieDTO[] = $objRelItemEtapaSerieDTO;
      }
      $objItemEtapaDTO->setArrObjRelItemEtapaSerieDTO($arrObjRelItemEtapaSerieDTO);

      if (isset($_POST['sbmAlterarItemEtapa'])) {
        try {
          $objItemEtapaRN = new ItemEtapaRN();
          $objItemEtapaRN->alterar($objItemEtapaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Item da Etapa "' . $objItemEtapaDTO->getStrNome() . '" alterado com sucesso.');
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($strAncora)));
          die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'item_etapa_consultar':
      $strTitulo = 'Consultar Item da Etapa';

      if (PaginaSEI::getInstance()->getAcaoRetorno() == 'plano_trabalho_detalhar') {
        $strOcultar = 'display:none;';
        $strAncora = $_GET['id_etapa_trabalho'] . '-' . $_GET['id_item_etapa'];
      } else {
        if (PaginaSEI::getInstance()->getAcaoRetorno() == 'plano_trabalho_configurar') {
          $strAncora = $_GET['id_etapa_trabalho'] . '-' . $_GET['id_item_etapa'];
        } else {
          $strAncora = $_GET['id_item_etapa'];
        }
      }

      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($strAncora)) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objItemEtapaDTO->setNumIdItemEtapa($_GET['id_item_etapa']);
      $objItemEtapaDTO->setBolExclusaoLogica(false);
      $objItemEtapaDTO->retTodos(true);
      $objItemEtapaRN = new ItemEtapaRN();
      $objItemEtapaDTO = $objItemEtapaRN->consultar($objItemEtapaDTO);
      if ($objItemEtapaDTO === null) {
        throw new InfraException("Registro não encontrado.");
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelPlanoTrabalho = PlanoTrabalhoINT::montarSelectNome('null', '&nbsp;', $_GET['id_plano_trabalho']);
  $strItensSelEtapaTrabalho = EtapaTrabalhoINT::montarSelectNome('null', '&nbsp;', $objItemEtapaDTO->getNumIdEtapaTrabalho());

  $strLinkUnidadeSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidade');
  $strItensSelUnidade = RelItemEtapaUnidadeINT::montarSelectUnidade($objItemEtapaDTO->getNumIdItemEtapa());

  $strLinkSerieSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=serie_selecionar&tipo_selecao=2&id_object=objLupaSerie');
  $strItensSelSerie = RelItemEtapaSerieINT::montarSelectSerie($objItemEtapaDTO->getNumIdItemEtapa());
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

    #lblEtapaTrabalho {position: absolute;left: 0%;top: 0%;width: 60%;}

    #selEtapaTrabalho {position: absolute;left: 0%;top: 40%;width: 60%;}

    #lblNome {position: absolute;left: 0%;top: 0%;width: 60%;}

    #txtNome {position: absolute;left: 0%;top: 40%;width: 60%;}

    #lblDescricao {position: absolute;left: 0%;top: 0%;width: 80%;}

    #txaDescricao {position: absolute;left: 0%;top: 16%;width: 80%;}

    #lblUnidade {position: absolute;left: 0%;top: 0%;width: 80%;}

    #selUnidade {position: absolute;left: 0%;top: 16%;width: 80%;}

    #imgLupaUnidade {position: absolute;left: 81%;top: 16%;}

    #imgExcluirUnidade {position: absolute;left: 81%;top: 40%;}

    #lblSerie {position: absolute;left: 0%;top: 0%;width: 80%;}

    #selSerie {position: absolute;left: 0%;top: 16%;width: 80%;}

    #imgLupaSerie {position: absolute;left: 81%;top: 16%;}

    #imgExcluirSerie {position: absolute;left: 81%;top: 40%;}

    #lblOrdem {position: absolute;left: 0%;top: 0%;width: 15%;<?=$strOcultar?>}

    #txtOrdem {position: absolute;left: 0%;top: 40%;width: 15%;<?=$strOcultar?>}

    <? if (0){ ?></style><?
} ?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<? if (0){ ?>
  <script type="text/javascript"><?}?>

    var objLupaUnidade = null;
    var objLupaSerie = null;

    function inicializar() {
      if ('<?=$_GET['acao']?>' == 'item_etapa_cadastrar') {
        document.getElementById('txtNome').focus();
      } else if ('<?=$_GET['acao']?>' == 'item_etapa_consultar') {
        infraDesabilitarCamposAreaDados();
      } else {
        document.getElementById('btnCancelar').focus();
      }

      objLupaUnidade = new infraLupaSelect('selUnidade', 'hdnUnidade', '<?=$strLinkUnidadeSelecao?>');
      objLupaSerie = new infraLupaSelect('selSerie', 'hdnSerie', '<?=$strLinkSerieSelecao?>');

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
  <form id="frmItemEtapaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblPlanoTrabalho" for="selPlanoTrabalho" accesskey="" class="infraLabelOpcional">Plano de Trabalho:</label>
    <select id="selPlanoTrabalho" name="selPlanoTrabalho" disabled="disabled" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelPlanoTrabalho?>
    </select>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblEtapaTrabalho" for="selEtapaTrabalho" accesskey="" class="infraLabelOpcional">Etapa de Trabalho:</label>
    <select id="selEtapaTrabalho" name="selEtapaTrabalho" disabled="disabled" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelEtapaTrabalho?>
    </select>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objItemEtapaDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('11em');
    ?>
    <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
    <textarea id="txaDescricao" name="txaDescricao" rows="4" class="infraTextarea" maxlength="4000" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objItemEtapaDTO->getStrDescricao())?></textarea>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('10em');
    ?>
    <label id="lblUnidade" for="selUnidade" accesskey="" class="infraLabelOpcional">Unidades associadas:</label>
    <select id="selUnidade" name="selUnidade" size="4" multiple="multiple" class="infraSelect">
      <?=$strItensSelUnidade?>
    </select>
    <img id="imgLupaUnidade" onclick="objLupaUnidade.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Localizar Unidade" title="Localizar Unidade" class="infraImg"/>
    <img id="imgExcluirUnidade" onclick="objLupaUnidade.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Unidade" title="Remover Unidade" class="infraImg"/>
    <input type="hidden" id="hdnUnidade" name="hdnUnidade" value="<?=$_POST['hdnUnidade']?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('10em');
    ?>
    <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelOpcional">Tipos de documentos associados:</label>
    <select id="selSerie" name="selSerie" size="4" multiple="multiple" class="infraSelect">
      <?=$strItensSelSerie?>
    </select>
    <img id="imgLupaSerie" onclick="objLupaSerie.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Localizar Série" title="Localizar Série" class="infraImg"/>
    <img id="imgExcluirSerie" onclick="objLupaSerie.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Série" title="Remover Série" class="infraImg"/>
    <input type="hidden" id="hdnSerie" name="hdnSerie" value="<?=$_POST['hdnSerie']?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblOrdem" for="txtOrdem" accesskey="" class="infraLabelObrigatorio">Ordem:</label>
    <input type="text" id="txtOrdem" name="txtOrdem" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objItemEtapaDTO->getNumOrdem());?>"
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>
    <input type="hidden" id="hdnIdItemEtapa" name="hdnIdItemEtapa" value="<?=$objItemEtapaDTO->getNumIdItemEtapa();?>"/>
    <?
    //PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
