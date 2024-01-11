<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 27/09/2022 - criado por mgb29
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selStaSituacao'));

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'id_procedimento', 'id_plano_trabalho', 'id_etapa_trabalho', 'id_item_etapa'));

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $objAndamentoPlanoTrabalhoDTO = new AndamentoPlanoTrabalhoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'item_etapa_atualizar_andamento':
      $strTitulo = 'Atualizar Item da Etapa';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::montarAncora($_GET['id_etapa_trabalho'] . '-' . $_GET['id_item_etapa'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objAndamentoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($_GET['id_plano_trabalho']);
      $objAndamentoPlanoTrabalhoDTO->setNumIdEtapaTrabalho($_GET['id_etapa_trabalho']);
      $objAndamentoPlanoTrabalhoDTO->setNumIdItemEtapa($_GET['id_item_etapa']);
      $objAndamentoPlanoTrabalhoDTO->setDblIdProcedimento($_GET['id_procedimento']);
      $objAndamentoPlanoTrabalhoDTO->setStrStaSituacao($_POST['selStaSituacao']);
      $objAndamentoPlanoTrabalhoDTO->setStrDescricao($_POST['txaDescricao']);

      $arrObjRelItemEtapaDocumentoDTO = array();
      $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDocumento']);
      foreach ($arrOpcoes as $opcao) {
        $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
        $objRelItemEtapaDocumentoDTO->setDblIdDocumento($opcao);
        $arrObjRelItemEtapaDocumentoDTO[] = $objRelItemEtapaDocumentoDTO;
      }
      $objAndamentoPlanoTrabalhoDTO->setArrObjRelItemEtapaDocumentoDTO($arrObjRelItemEtapaDocumentoDTO);


      if (isset($_POST['sbmSalvar'])) {
        try {
          $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
          $objAndamentoPlanoTrabalhoRN->atualizarItem($objAndamentoPlanoTrabalhoDTO);
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::montarAncora($_GET['id_etapa_trabalho'] . '-' . $_GET['id_item_etapa'])));
          die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'item_etapa_consultar_andamento':
      $strTitulo = 'Consultar Item da Etapa';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::montarAncora($_GET['id_etapa_trabalho'] . '-' . $_GET['id_item_etapa'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  if ($_GET['acao'] == 'item_etapa_atualizar_andamento') {
    $strItensSelStaSituacao = AndamentoPlanoTrabalhoINT::montarSelectStaSituacao('null', '&nbsp;', $objAndamentoPlanoTrabalhoDTO->getStrStaSituacao());
    $strLinkDocumentoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_item_etapa_documento_selecionar&tipo_selecao=2&id_object=objLupaDocumento');
    $strItensSelDocumento = RelItemEtapaDocumentoINT::montarSelectDocumento($objAndamentoPlanoTrabalhoDTO->getNumIdItemEtapa(), $_GET['id_procedimento']);
  }


  $objHistoricoPlanoTrabalhoDTO = new HistoricoPlanoTrabalhoDTO();
  $objHistoricoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($_GET['id_plano_trabalho']);
  $objHistoricoPlanoTrabalhoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  $objHistoricoPlanoTrabalhoDTO->setStrStaHistorico(PlanoTrabalhoRN::$TH_ANDAMENTO_ITEM_ETAPA);
  $objHistoricoPlanoTrabalhoDTO->setNumIdItemEtapa($_GET['id_item_etapa']);

  $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
  $objPlanoTrabalhoDTOHistorico = $objPlanoTrabalhoRN->consultarHistorico($objHistoricoPlanoTrabalhoDTO);

  $arrObjAndamentoPlanoTrabalhoDTO = $objPlanoTrabalhoDTOHistorico->getArrObjAndamentoPlanoTrabalhoDTO();

  $numRegistros = count($arrObjAndamentoPlanoTrabalhoDTO);

  $strResultado = '';

  if ($numRegistros > 0) {
    $strSumarioTabela = 'Tabela de Andamentos do Item.';
    $strCaptionTabela = 'Andamentos do Item';

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="10%">Situação</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Data/Hora</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Unidade</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Usuário</th>' . "\n";
    $strResultado .= '<th class="infraTh">Descrição</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';

    $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
    $arrObjSituacaoAndamentoPlanoTrabalhoDTO = InfraArray::indexarArrInfraDTO($objAndamentoPlanoTrabalhoRN->listarValoresSituacao(), 'StaSituacao');

    for ($i = 0; $i < $numRegistros; $i++) {
      $objAndamentoPlanoTrabalhoDTOHistorico = $arrObjAndamentoPlanoTrabalhoDTO[$i];

      $strResultado .= '<tr class="infraTrClara">';

      if ($objAndamentoPlanoTrabalhoDTOHistorico->getObjSituacaoAndamentoPlanoTrabalhoDTO() != null) {
        $objSituacaoAndamentoPlanoTrabalhoDTO = $objAndamentoPlanoTrabalhoDTOHistorico->getObjSituacaoAndamentoPlanoTrabalhoDTO();
        $strResultado .= '<td align="center"><a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip($objSituacaoAndamentoPlanoTrabalhoDTO->getStrDescricao()) . '><img src="' . $objSituacaoAndamentoPlanoTrabalhoDTO->getStrIcone() . '" class="imagemStatus" /></a></td>';
      } else {
        $strResultado .= '<td>&nbsp;</td>';
      }

      $strResultado .= "\n" . '<td align="center" valign="top">';
      $strResultado .= substr($objAndamentoPlanoTrabalhoDTOHistorico->getDthExecucao(), 0, 16);

      $strResultado .= '</td>';
      $strResultado .= "\n" . '<td align="center"  valign="top">';
      $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTOHistorico->getStrDescricaoUnidadeOrigem()) . '" title="' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTOHistorico->getStrDescricaoUnidadeOrigem()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTOHistorico->getStrSiglaUnidadeOrigem()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= "\n" . '<td align="center"  valign="top">';
      $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTOHistorico->getStrNomeUsuarioOrigem()) . '" title="' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTOHistorico->getStrNomeUsuarioOrigem()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTOHistorico->getStrSiglaUsuarioOrigem()) . '</a>';
      $strResultado .= '</td>';
      $strResultado .= "\n" . '<td valign="top">';

      if (!InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTOHistorico->getStrNomeTarefaPlanoTrabalho())) {
        $strResultado .= nl2br($objAndamentoPlanoTrabalhoDTOHistorico->getStrNomeTarefaPlanoTrabalho());
      } else {
        $strResultado .= '&nbsp;';
      }

      $strResultado .= '</td>';

      $strResultado .= '</tr>';
    }
    $strResultado .= '</table>';
  }
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

    #lblStaSituacao {position: absolute;left: 0%;top: 0%;width: 25%;}

    #selStaSituacao {position: absolute;left: 0%;top: 40%;width: 25%;}

    #lblDescricao {position: absolute;left: 0%;top: 5%;width: 80%;}

    #txaDescricao {position: absolute;left: 0%;top: 25%;width: 80%;}

    #lblDocumento {position: absolute;left: 0%;top: 5%;width: 80%;}

    #selDocumento {position: absolute;left: 0%;top: 23%;width: 80%;}

    #imgLupaDocumento {position: absolute;left: 81%;top: 23%;}

    #imgExcluirDocumento {position: absolute;left: 81%;top: 43%;}

    <? if (0){ ?></style><?
} ?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<? if (0){ ?>
  <script type="text/javascript"><?}?>

    var objLupaDocumento = null;

    function inicializar() {
      <? if ($_GET['acao'] == 'item_etapa_atualizar_andamento'){ ?>
      objLupaDocumento = new infraLupaSelect('selDocumento', 'hdnDocumento', '<?=$strLinkDocumentoSelecao?>');
      document.getElementById('selStaSituacao').focus();
      <? }else{ ?>
      infraDesabilitarCamposAreaDados();
      <? } ?>
      infraEfeitoTabelas(true);
    }

    function validarCadastro() {

      if (!infraSelectSelecionado('selStaSituacao')) {
        alert('Selecione uma Situação.');
        document.getElementById('selStaSituacao').focus();
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
  <form id="frmItemEtapaAndamento" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    if ($_GET['acao'] == 'item_etapa_atualizar_andamento') {
      PaginaSEI::getInstance()->abrirAreaDados('5em');
      ?>
      <label id="lblStaSituacao" for="selStaSituacao" accesskey="" class="infraLabelObrigatorio">Situação:</label>
      <select id="selStaSituacao" name="selStaSituacao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensSelStaSituacao?>
      </select>
      <?
      PaginaSEI::getInstance()->fecharAreaDados();
      PaginaSEI::getInstance()->abrirAreaDados('10em');
      ?>
      <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
      <textarea id="txaDescricao" name="txaDescricao" rows="3" class="infraTextarea" maxlength="4000"
                tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objAndamentoPlanoTrabalhoDTO->getStrDescricao())?></textarea>
      <?
      PaginaSEI::getInstance()->fecharAreaDados();
      PaginaSEI::getInstance()->abrirAreaDados('10em');
      ?>
      <label id="lblDocumento" for="selDocumento" accesskey="" class="infraLabelOpcional">Documentos da unidade associados:</label>
      <select id="selDocumento" name="selDocumento" size="4" multiple="multiple" class="infraSelect">
        <?=$strItensSelDocumento?>
      </select>
      <img id="imgLupaDocumento" onclick="objLupaDocumento.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Localizar Documento" title="Localizar Documento" class="infraImg"/>
      <img id="imgExcluirDocumento" onclick="objLupaDocumento.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Documento" title="Remover Documento" class="infraImg"/>
      <input type="hidden" id="hdnDocumento" name="hdnDocumento" value="<?=$_POST['hdnDocumento']?>"/>
      <?
      PaginaSEI::getInstance()->fecharAreaDados();
    }
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    //PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
