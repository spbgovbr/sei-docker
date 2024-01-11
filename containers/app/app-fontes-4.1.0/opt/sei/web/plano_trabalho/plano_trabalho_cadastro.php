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

  PaginaSEI::getInstance()->verificarSelecao('plano_trabalho_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'id_procedimento'));

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'plano_trabalho_cadastrar':
      $strTitulo = 'Novo Plano de Trabalho';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarPlanoTrabalho" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho(null);
      $objPlanoTrabalhoDTO->setStrNome($_POST['txtNome']);
      $objPlanoTrabalhoDTO->setStrDescricao($_POST['txaDescricao']);
      $objPlanoTrabalhoDTO->setStrSinAtivo('S');

      $arrObjTipoProcedimentoDTO = array();
      $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoProcedimento']);
      foreach ($arrOpcoes as $opcao) {
        $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        $objTipoProcedimentoDTO->setNumIdTipoProcedimento($opcao);
        $arrObjTipoProcedimentoDTO[] = $objTipoProcedimentoDTO;
      }
      $objPlanoTrabalhoDTO->setArrObjTipoProcedimentoDTO($arrObjTipoProcedimentoDTO);

      $arrObjRelSeriePlanoTrabalhoDTO = array();
      $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSerie']);
      foreach ($arrOpcoes as $opcao) {
        $objRelSeriePlanoTrabalhoDTO = new RelSeriePlanoTrabalhoDTO();
        $objRelSeriePlanoTrabalhoDTO->setNumIdSerie($opcao);
        $arrObjRelSeriePlanoTrabalhoDTO[] = $objRelSeriePlanoTrabalhoDTO;
      }
      $objPlanoTrabalhoDTO->setArrObjRelSeriePlanoTrabalhoDTO($arrObjRelSeriePlanoTrabalhoDTO);

      if (isset($_POST['sbmCadastrarPlanoTrabalho'])) {
        try {
          $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
          $objPlanoTrabalhoDTO = $objPlanoTrabalhoRN->cadastrar($objPlanoTrabalhoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Plano de Trabalho "' . $objPlanoTrabalhoDTO->getStrNome() . '" cadastrado com sucesso.');
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho() . PaginaSEI::getInstance()->montarAncora($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho())));
          die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'plano_trabalho_alterar':
      $strTitulo = 'Alterar Plano de Trabalho';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarPlanoTrabalho" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_plano_trabalho'])) {
        $objPlanoTrabalhoDTO->setBolExclusaoLogica(false);
        $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($_GET['id_plano_trabalho']);
        $objPlanoTrabalhoDTO->retTodos();
        $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
        $objPlanoTrabalhoDTO = $objPlanoTrabalhoRN->consultar($objPlanoTrabalhoDTO);
        if ($objPlanoTrabalhoDTO == null) {
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($_POST['hdnIdPlanoTrabalho']);
        $objPlanoTrabalhoDTO->setStrNome($_POST['txtNome']);
        $objPlanoTrabalhoDTO->setStrDescricao($_POST['txaDescricao']);
        //$objPlanoTrabalhoDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $arrObjTipoProcedimentoDTO = array();
      $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoProcedimento']);
      foreach ($arrOpcoes as $opcao) {
        $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        $objTipoProcedimentoDTO->setNumIdTipoProcedimento($opcao);
        $arrObjTipoProcedimentoDTO[] = $objTipoProcedimentoDTO;
      }
      $objPlanoTrabalhoDTO->setArrObjTipoProcedimentoDTO($arrObjTipoProcedimentoDTO);

      $arrObjRelSeriePlanoTrabalhoDTO = array();
      $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSerie']);
      foreach ($arrOpcoes as $opcao) {
        $objRelSeriePlanoTrabalhoDTO = new RelSeriePlanoTrabalhoDTO();
        $objRelSeriePlanoTrabalhoDTO->setNumIdSerie($opcao);
        $arrObjRelSeriePlanoTrabalhoDTO[] = $objRelSeriePlanoTrabalhoDTO;
      }
      $objPlanoTrabalhoDTO->setArrObjRelSeriePlanoTrabalhoDTO($arrObjRelSeriePlanoTrabalhoDTO);

      if (isset($_POST['sbmAlterarPlanoTrabalho'])) {
        try {
          $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
          $objPlanoTrabalhoRN->alterar($objPlanoTrabalhoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Plano de Trabalho "' . $objPlanoTrabalhoDTO->getStrNome() . '" alterado com sucesso.');
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho())));
          die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'plano_trabalho_consultar':
      $strTitulo = 'Consultar Plano de Trabalho';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_plano_trabalho'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($_GET['id_plano_trabalho']);
      $objPlanoTrabalhoDTO->setBolExclusaoLogica(false);
      $objPlanoTrabalhoDTO->retTodos();
      $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
      $objPlanoTrabalhoDTO = $objPlanoTrabalhoRN->consultar($objPlanoTrabalhoDTO);
      if ($objPlanoTrabalhoDTO === null) {
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strLinkTipoProcedimentoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTipoProcedimento');
  $strItensSelTipoProcedimento = TipoProcedimentoINT::montarSelectPlanoTrabalho($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());

  $strLinkSerieSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=serie_selecionar&tipo_selecao=2&id_object=objLupaSerie');
  $strItensSelSerie = RelSeriePlanoTrabalhoINT::montarSelectSerie($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
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
<?
if (0){ ?>
  <style><?}?>
    #lblNome {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 60%;
    }

    #txtNome {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 60%;
    }

    #lblDescricao {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 80%;
    }

    #txaDescricao {
      position: absolute;
      left: 0%;
      top: 14%;
      width: 80%;
    }

    #lblTipoProcedimento {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 80%;
    }

    #selTipoProcedimento {
      position: absolute;
      left: 0%;
      top: 12%;
      width: 80%;
    }

    #imgLupaTipoProcedimento {
      position: absolute;
      left: 81%;
      top: 12%;
    }

    #imgExcluirTipoProcedimento {
      position: absolute;
      left: 81%;
      top: 27%;
    }

    #lblSerie {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 80%;
    }

    #selSerie {
      position: absolute;
      left: 0%;
      top: 12%;
      width: 80%;
    }

    #imgLupaSerie {
      position: absolute;
      left: 81%;
      top: 12%;
    }

    #imgExcluirSerie {
      position: absolute;
      left: 81%;
      top: 27%;
    }


    <?
    if (0){ ?></style><?
} ?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?
if (0){ ?>
  <script type="text/javascript"><?}?>

    var objLupaTipoProcedimento = null;
    var objLupaSerie = null;

    function inicializar() {
      if ('<?=$_GET['acao']?>' == 'plano_trabalho_cadastrar') {
        document.getElementById('txtNome').focus();
      } else if ('<?=$_GET['acao']?>' == 'plano_trabalho_consultar') {
        infraDesabilitarCamposAreaDados();
      } else {
        document.getElementById('btnCancelar').focus();
      }

      objLupaTipoProcedimento = new infraLupaSelect('selTipoProcedimento', 'hdnTipoProcedimento', '<?=$strLinkTipoProcedimentoSelecao?>');
      objLupaSerie = new infraLupaSelect('selSerie', 'hdnSerie', '<?=$strLinkSerieSelecao?>');

      infraEfeitoTabelas(true);
    }

    function validarCadastro() {
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

    <?
    if (0){ ?></script><?
} ?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmPlanoTrabalhoCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objPlanoTrabalhoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);"
           maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('14em');
    ?>
    <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
    <textarea id="txaDescricao" name="txaDescricao" rows="6" class="infraTextarea" maxlength="4000" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objPlanoTrabalhoDTO->getStrDescricao())?></textarea>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('15em');
    ?>
    <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelOpcional">Padrão para os tipos de processo:</label>
    <select id="selTipoProcedimento" name="selTipoProcedimento" size="7" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelTipoProcedimento?>
    </select>
    <img id="imgLupaTipoProcedimento" onclick="objLupaTipoProcedimento.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Localizar Tipo de Processo" title="Localizar Tipo de Processo" class="infraImg"
         tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <img id="imgExcluirTipoProcedimento" onclick="objLupaTipoProcedimento.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Tipo de Processo" title="Remover Tipo de Processo" class="infraImg"
         tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <input type="hidden" id="hdnTipoProcedimento" name="hdnTipoProcedimento" value="<?=$_POST['hdnTipoProcedimento']?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('15em');
    ?>
    <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelOpcional">Não permitir os tipos de documento:</label>
    <select id="selSerie" name="selSerie" size="7" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelSerie?>
    </select>
    <img id="imgLupaSerie" onclick="objLupaSerie.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Localizar Tipo de Documento" title="Localizar Tipo de Documento" class="infraImg"
         tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <img id="imgExcluirSerie" onclick="objLupaSerie.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Tipo de Documento" title="Remover Tipo de Documento" class="infraImg"
         tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <input type="hidden" id="hdnSerie" name="hdnSerie" value="<?=$_POST['hdnSerie']?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>
    <input type="hidden" id="hdnIdPlanoTrabalho" name="hdnIdPlanoTrabalho" value="<?=$objPlanoTrabalhoDTO->getNumIdPlanoTrabalho();?>"/>
    <?
    //PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
