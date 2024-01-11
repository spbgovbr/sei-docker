<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/11/2018 - criado por cjy
 *
 * Versão do Gerador de Código: 1.42.0
 */

try {
  require_once dirname(__FILE__) . '/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array("txaMotivo", "rdaAvaliacao"));

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_avaliacao_documental', 'id_procedimento'));

  $objCpadAvaliacaoDTO = new CpadAvaliacaoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  //uma avaliacao cpad nunca é alterada, apenas criada, mesmo que na tela de cadastro traga avaliacoes anteriores (nao ativas), para historico e exibição de justificativas
  switch ($_GET['acao']) {
    case 'cpad_avaliacao_cadastrar':
      $strTitulo = 'Avaliação CPAD';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarCpadAvaliacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_avaliacao_documental'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      //inicializa dto de nova avaliacao cpad. buscando dados do formulario
      $objCpadAvaliacaoDTO->setNumIdCpadAvaliacao(null);
      //id da avaliacao documental que está sendo avaliacada pelo cpad
      $objCpadAvaliacaoDTO->setNumIdAvaliacaoDocumental($_GET['id_avaliacao_documental']);
      //se concorda ou discorda da avaliacao documental
      $objCpadAvaliacaoDTO->setStrStaCpadAvaliacao($_POST['rdoAvaliacao']);
      //se discorda, busca o textarea de motivo
      if ($_POST['rdoAvaliacao'] == CpadAvaliacaoRN::$TA_CPAD_NEGADO) {
        $objCpadAvaliacaoDTO->setStrMotivo($_POST['txaMotivo']);
      } else {
        $objCpadAvaliacaoDTO->setStrMotivo(null);
      }
      //data atual
      $objCpadAvaliacaoDTO->setDthAvaliacao(InfraData::getStrDataHoraAtual());
      //cadastrada como ativa
      $objCpadAvaliacaoDTO->setStrSinAtivo('S');
      //a justificativa é preenchida na avaliacao documental, quando a avaliacao cpad for negada
      $objCpadAvaliacaoDTO->setStrJustificativa(null);

      //busca historico de avaliacoes negativas
      $objCpadAvaliacaoRN = new CpadAvaliacaoRN();
      $objCpadAvaliacaoDTO_Banco = new CpadAvaliacaoDTO();
      //campos de retorno
      $objCpadAvaliacaoDTO_Banco->retNumIdCpadAvaliacao();
      $objCpadAvaliacaoDTO_Banco->retDthAvaliacao();
      $objCpadAvaliacaoDTO_Banco->retNumIdUsuario();
      $objCpadAvaliacaoDTO_Banco->retStrSiglaUsuario();
      $objCpadAvaliacaoDTO_Banco->retStrNomeUsuario();
      $objCpadAvaliacaoDTO_Banco->retStrSinAtivo();
      $objCpadAvaliacaoDTO_Banco->retStrStaCpadAvaliacao();
      $objCpadAvaliacaoDTO_Banco->retStrMotivo();
      $objCpadAvaliacaoDTO_Banco->retStrJustificativa();
      //avaliacoes cpad dessa avaliacao documental
      $objCpadAvaliacaoDTO_Banco->setNumIdAvaliacaoDocumental($_GET['id_avaliacao_documental']);
      //apenas as negadas
      $objCpadAvaliacaoDTO_Banco->setStrStaCpadAvaliacao(CpadAvaliacaoRN::$TA_CPAD_NEGADO);
      //mesmo as nao ativas, para buscar o historico
      $objCpadAvaliacaoDTO_Banco->setBolExclusaoLogica(false);
      $objCpadAvaliacaoDTO_Banco->setOrdDthAvaliacao(InfraDTO::$TIPO_ORDENACAO_DESC);

      //busca
      $arrObjCpadAvaliacaoDTO = $objCpadAvaliacaoRN->listar($objCpadAvaliacaoDTO_Banco);

      //submicao
      if (isset($_POST['sbmCadastrarCpadAvaliacao'])) {
        try {
          //cadastro
          $objCpadAvaliacaoDTO = $objCpadAvaliacaoRN->cadastrar($objCpadAvaliacaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Avaliação "' . $objCpadAvaliacaoDTO->getNumIdCpadAvaliacao() . '" cadastrada com sucesso.');
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_cpad_avaliacao=' . $objCpadAvaliacaoDTO->getNumIdCpadAvaliacao() . PaginaSEI::getInstance()->montarAncora($_GET['id_avaliacao_documental'])));
          die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;


    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }
  //orgao atual
  $idOrgaoAtual = SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual();

  //busca a avaliacao documental, para marcar o assunto que foi escolhido
  $objAvaliacaoDocumentalDTO = new AvaliacaoDocumentalDTO();
  //campos de retorno
  $objAvaliacaoDocumentalDTO->retNumIdAvaliacaoDocumental();
  $objAvaliacaoDocumentalDTO->retDblIdProcedimento();
  $objAvaliacaoDocumentalDTO->retStrProtocoloFormatado();
  $objAvaliacaoDocumentalDTO->retStrSiglaUsuario();
  $objAvaliacaoDocumentalDTO->retStrNomeUsuario();
  $objAvaliacaoDocumentalDTO->retDtaAvaliacao();
  $objAvaliacaoDocumentalDTO->retNumIdAssunto();
  $objAvaliacaoDocumentalDTO->retStrDescricaoAssunto();
  $objAvaliacaoDocumentalDTO->retStrNomeTipoProcedimento();
  //filtra pela avaliacao documental em questao
  $objAvaliacaoDocumentalDTO->setNumIdAvaliacaoDocumental($_GET['id_avaliacao_documental']);

  //consulta
  $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();
  $objAvaliacaoDocumentalDTO = $objAvaliacaoDocumentalRN->consultar($objAvaliacaoDocumentalDTO);

  //busca os assuntos
  $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
  $objRelProtocoloAssuntoDTO->setDblIdProtocolo($objAvaliacaoDocumentalDTO->getDblIdProcedimento());
  $arrObjRelProtocoloAssuntoDTO = $objAvaliacaoDocumentalRN->listarAssuntosProcesso($objRelProtocoloAssuntoDTO);

  //tabela de assuntos
  $numRegistrosAssuntos = count($arrObjRelProtocoloAssuntoDTO);
  if ($numRegistrosAssuntos) {

    $strResultadoAssuntos = '';

    $strSumarioTabela = 'Tabela de Assuntos.';
    $strCaptionTabela = 'Assuntos';

    $strResultadoAssuntos .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultadoAssuntos .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistrosAssuntos) . '</caption>';
    $strResultadoAssuntos .= '<tr>';
    $strResultadoAssuntos .= '<th class="infraTh" width="1%"></th>' . "\n";
    $strResultadoAssuntos .= '<th class="infraTh" width="15%">Origem</th>' . "\n";
    $strResultadoAssuntos .= '<th class="infraTh" width="10%">Código</th>' . "\n";
    $strResultadoAssuntos .= '<th class="infraTh" width="30%" >Descrição</th>' . "\n";
    $strResultadoAssuntos .= '<th class="infraTh" width="6%">Prazo Corrente</th>' . "\n";
    $strResultadoAssuntos .= '<th class="infraTh" width="8%">Prazo Intermediário</th>' . "\n";
    $strResultadoAssuntos .= '<th class="infraTh" width="10%">Destinação Final</th>' . "\n";
    $strResultadoAssuntos .= '<th class="infraTh" >Observações</th>' . "\n";
    $strResultadoAssuntos .= '</tr>' . "\n";
    $strCssTr = '';

    for ($i = 0; $i < $numRegistrosAssuntos; $i++) {

      $strId = $arrObjRelProtocoloAssuntoDTO[$i]->getNumIdAssunto();

      //se o assunto do laço for o assunto selecionado na avaliacao documental, marca a linha em amarelo
      $strLinhaAmarela = "";
      if ($objAvaliacaoDocumentalDTO->getNumIdAssunto() == $strId) {
        $strLinhaAmarela = " infraTrAcessada";
      }
      //intercala cores de linhas
      if ($i % 2 == 0) {
        $strCssTr = '<tr class="infraTrEscura' . $strLinhaAmarela . '">';
      } else {
        $strCssTr = '<tr class="infraTrClara' . $strLinhaAmarela . '">';
      }
      $strResultadoAssuntos .= $strCssTr;

      //testa destinação
      if ($arrObjRelProtocoloAssuntoDTO[$i]->getStrStaDestinacaoAssunto() == AssuntoRN::$TD_ELIMINACAO) {
        $strDestinacao = 'Eliminação';

      } else if ($arrObjRelProtocoloAssuntoDTO[$i]->getStrStaDestinacaoAssunto() == AssuntoRN::$TD_GUARDA_PERMANENTE) {
        $strDestinacao = 'Guarda Permanente';
      }
      $strResultadoAssuntos .= '<td align="center"><input disabled type="radio" ' . ($objAvaliacaoDocumentalDTO->getNumIdAssunto() == $strId ? "checked='checked'" : '') . ' onchange="mudarAssunto(this.value,this.getAttribute(\'desc\'))" name="rdoIdAssunto" value="' . $strId . '" desc="' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrDescricaoAssunto()) . '" /></td>' . "\n";
      if($arrObjRelProtocoloAssuntoDTO[$i]->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
        $strResultadoAssuntos .= '<td align="center"><a target="_blank" title="'.PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrTipoProtocolo()).'" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $arrObjRelProtocoloAssuntoDTO[$i]->getDblIdProtocolo()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="protocoloNormal" style="font-size:1em !important;">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrProtocoloFormatadoProtocolo()) . '</a></td>' . "\n";
      }else{
        $strResultadoAssuntos .= '<td align="center"><a target="_blank" title="'.PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrTipoProtocolo()).'" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_documento=' . $arrObjRelProtocoloAssuntoDTO[$i]->getDblIdProtocolo()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="protocoloNormal" style="font-size:1em !important;">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrProtocoloFormatadoProtocolo()) . '</a></td>' . "\n";
      }
      $strResultadoAssuntos .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrCodigoEstruturadoAssunto()) . '</td>' . "\n";
      $strResultadoAssuntos .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrDescricaoAssunto()) . '</td>' . "\n";
      $strResultadoAssuntos .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getNumPrazoCorrenteAssunto()) . '</td>' . "\n";
      $strResultadoAssuntos .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getNumPrazoIntermediarioAssunto()) . '</td>' . "\n";
      $strResultadoAssuntos .= '<td align="center">' . PaginaSEI::tratarHTML($strDestinacao) . '</td>' . "\n";
      $strResultadoAssuntos .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrObservacoesAssunto()) . '</td>' . "\n";
      $strResultadoAssuntos .= '</tr>' . "\n";
    }
    $strResultadoAssuntos .= '</table>' . "\n";
  }

  //tabela de avaliacoes cpad
  $numRegistros = InfraArray::contar($arrObjCpadAvaliacaoDTO);
  if ($numRegistros) {

    $strResultado = '';

    $strSumarioTabela = 'Lista de Discordâncias CPAD.';
    $strCaptionTabela = 'Discordâncias';

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" style="display: none;">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Data</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="20%">Usuário</th>' . "\n";
    $strResultado .= '<th class="infraTh" >Motivo</th>' . "\n";
    $strResultado .= '<th class="infraTh" >Justificativa</th>' . "\n";
    $strResultado .= '</tr>' . "\n";

    $strArrJustificativas = "";
    $strCssTr = "";
    $virgula = "";
    for ($i = 0; $i < $numRegistros; $i++) {

      if ($i % 2 == 0) {
        $strCssTr = '<tr class="infraTrEscura">';
      } else {
        $strCssTr = '<tr class="infraTrClara">';
      }
      $strResultado .= $strCssTr;

      $strId = $arrObjCpadAvaliacaoDTO[$i]->getNumIdCpadAvaliacao();
      $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjCpadAvaliacaoDTO[$i]->getStrMotivo());
      $strResultado .= '<td valign="top" style="display: none;">' . PaginaSEI::getInstance()->getTrCheck($i, $strId, $strDescricao) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getDthAvaliacao()) . '</td>' . "\n";
      $strResultado .= '<td align="center">    <a alt="' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getStrNomeUsuario()) . '" title="' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getStrSiglaUsuario()) . '</a></td>';
      $strResultado .= '<td align="left">' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getStrMotivo()) . '</td>' . "\n";
        $strResultado .= '<td align="left">' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getStrJustificativa()) . '</td>' . "\n";
      $strResultado .= '</tr>' . "\n";
    }
    $strResultado .= '</table>' . "\n";
  }

  //bools para marcar ou o radio de 'concordo' ou o de 'discordo'
  $bolMarcarAvaliacaoConcordo = false;
  $bolMarcarAvaliacaoDiscordo = false;
  if (isset($_POST['rdoAvaliacao'])) {
    if ($_POST['rdoAvaliacao'] == CpadAvaliacaoRN::$TA_CPAD_AVALIADO) {
      $bolMarcarAvaliacaoConcordo = true;
    } else if ($_POST['rdoAvaliacao'] == CpadAvaliacaoRN::$TA_CPAD_NEGADO) {
      $bolMarcarAvaliacaoDiscordo = true;
    }
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
<?
if (0){ ?>
  <style><?}?>

    #lblAvaliacao {position:absolute;left:0%;top:0%;width:60%;}
    #divOptConcordo {position:absolute;left:1%;top:50%;}
    #divOptDiscordo {position:absolute;left:15%;top:50%;}

    #lblMotivo {position:absolute;left:0%;top:0%;width:82%;}
    #txaMotivo {position:absolute;left:0%;top:19%;width:82%;}

    #lblData {position:absolute;left:0%;top:0%;width:30%;}
    #txtData {position:absolute;left:0%;top:19%;width:30%;}

    #lblAvaliador {position:absolute;left:32%;top:0%;width:50%;}
    #txtAvaliador {position:absolute;left:32%;top:19%;width:50%;}

    #lblProcesso {position:absolute;left:0%;top:50%;width:30%;}
    #ancProcesso {position:absolute;left:0%;top:69%;width:30%;font-size:.875rem;}

    #lblTipoProcedimento {position:absolute;left:32%;top:50%;width:50%;}
    #txtTipoProcedimento {position:absolute;left:32%;top:69%;width:50%;}


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

    function inicializar() {
      mudarAvaliacao();
      infraEfeitoTabelas(true);
    }

    function validarCadastro() {
      if ($("#rdoAvaliacaoDiscordo:checked").length == 0 && $("#rdoAvaliacaoConcordo:checked").length == 0) {
        alert("Avaliação não informada");
        return false;
      }

      if ($("#rdoAvaliacaoDiscordo:checked").length == 1 && $("#txaMotivo").val().trim() == "") {
        alert("O motivo deve ser informado.");
        return false;
      }
      return true;
    }

    function OnSubmitForm() {
      return validarCadastro();
    }

    //se for marcado o radio de 'discordo', mostra o textarea para motivo
    function mudarAvaliacao() {
      rdoAvaliacaoConcordo = $("#rdoAvaliacaoConcordo:checked").length;
      rdoAvaliacaoDiscordo = $("#rdoAvaliacaoDiscordo:checked").length;
      if (rdoAvaliacaoDiscordo == 1) {
        $("#txaMotivo").prop('readonly', false);
        $("#divMotivo").show();
      } else if (rdoAvaliacaoConcordo == 1 || (rdoAvaliacaoConcordo == 0 && rdoAvaliacaoDiscordo == 0)) {
        $("#txaMotivo").prop('readonly', true);
        $("#txaMotivo").val("");
        $("#divMotivo").hide();
      }
    }

    <?
    if (0){ ?></script><?
} ?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmCpadAvaliacaoCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>
    <div id="divAvaliacao" class="infraAreaDados" style="height:5em;">
      <label id="lblAvaliacao" class="infraLabelOpcional">A avaliação está correta?</label><br/>

      <div id="divOptConcordo" class="infraDivRadio">
        <input type="radio" onchange="mudarAvaliacao()" name="rdoAvaliacao" id="rdoAvaliacaoConcordo" <?= ($bolMarcarAvaliacaoConcordo ? 'checked="checked"' : '') ?> value="<?= CpadAvaliacaoRN::$TA_CPAD_AVALIADO ?>" class="infraRadio"/>
        <label id="lblAvaliacaoConcordo" for="rdoAvaliacaoConcordo" class="infraLabelRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Concordo</label>
      </div>

      <div id="divOptDiscordo" class="infraDivRadio">
        <input type="radio" onchange="mudarAvaliacao()" name="rdoAvaliacao" id="rdoAvaliacaoDiscordo" <?= ($bolMarcarAvaliacaoDiscordo ? 'checked="checked"' : '') ?> value="<?= CpadAvaliacaoRN::$TA_CPAD_NEGADO ?>" class="infraRadio"/>
        <label id="lblAvaliacaoDiscordo" for="rdoAvaliacaoDiscordo" class="infraLabelRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Discordo</label>
      </div>
    </div>

    <div id="divMotivo" class="infraAreaDados" style="height: 10em;">
      <label id="lblMotivo" for="txaMotivo"  class="infraLabelObrigatorio">Motivo:</label>
      <textarea id="txaMotivo" name="txaMotivo" rows="4" class="infraTextarea"
                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?= PaginaSEI::tratarHTML($_POST['txaMotivo']) ?></textarea>
    </div>
    <?
    PaginaSEI::getInstance()->abrirAreaDados('10em');
    ?>
      <label id="lblData" for="txtData" accesskey="" class="infraLabelOpcional">Data:</label>
      <input type="text" readonly id="txtData" name="txtData" class="infraText infraReadOnly"
             value="<?= PaginaSEI::tratarHTML($objAvaliacaoDocumentalDTO->getDtaAvaliacao()) ?>"
             tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

      <label id="lblAvaliador" for="selAvaliador" accesskey="" class="infraLabelOpcional">Avaliador:</label>
      <input type="text" readonly id="txtAvaliador" name="txtAvaliador" class="infraText infraReadOnly"
             value="<?= PaginaSEI::tratarHTML($objAvaliacaoDocumentalDTO->getStrSiglaUsuario() . " - " . $objAvaliacaoDocumentalDTO->getStrNomeUsuario()) ?>"
             tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

      <label id="lblProcesso" for="selProcesso" accesskey="" class="infraLabelOpcional">Processo:</label>
      <a id="ancProcesso" target="_blank" href="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $_GET['id_procedimento']) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() ?>"><?= PaginaSEI::tratarHTML($objAvaliacaoDocumentalDTO->getStrProtocoloFormatado()) ?></a>
      <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelOpcional">Tipo do Processo:</label><br/>
      <input readonly type="text" readonly id="txtTipoProcedimento" name="txtTipoProcedimento" class="infraText infraReadOnly"
             value="<?= PaginaSEI::tratarHTML($objAvaliacaoDocumentalDTO->getStrNomeTipoProcedimento()) ?>"
             tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();

    PaginaSEI::getInstance()->montarAreaTabela($strResultadoAssuntos, $numRegistrosAssuntos);
    ?>
    <br/>
    <?
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);

    PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
