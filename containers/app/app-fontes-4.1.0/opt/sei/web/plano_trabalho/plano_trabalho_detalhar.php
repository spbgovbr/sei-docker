<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 26/09/2022 - criado por mgb29
 *
 */

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'id_procedimento'));

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  switch ($_GET['acao']) {
    case 'plano_trabalho_detalhar':
      $strTitulo = 'Plano de Trabalho';


      if ($_GET['acao_origem']=='arvore_visualizar' && isset($_GET['id_documento']) && $_GET['id_documento']!='') {

        $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
        $objRelItemEtapaDocumentoDTO->retNumIdItemEtapa();
        $objRelItemEtapaDocumentoDTO->retNumIdEtapaTrabalhoItemEtapa();
        $objRelItemEtapaDocumentoDTO->setDblIdDocumento($_GET['id_documento']);

        $objRelItemEtapaDocumentoRN = new RelItemEtapaDocumentoRN();
        $arrObjRelItemEtapaDocumentoDTO = $objRelItemEtapaDocumentoRN->listar($objRelItemEtapaDocumentoDTO);

        if (count($arrObjRelItemEtapaDocumentoDTO)) {

          $arrAncora = array();
          foreach ($arrObjRelItemEtapaDocumentoDTO as $objRelItemEtapaDocumentoDTO) {
            $arrAncora[] = $objRelItemEtapaDocumentoDTO->getNumIdEtapaTrabalhoItemEtapa() . '-' . $objRelItemEtapaDocumentoDTO->getNumIdItemEtapa();
          }

          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_detalhar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar') . PaginaSEI::montarAncora($arrAncora));
          die;
        }
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  //somente para administradores
  if (SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_cadastrar') && SessaoSEI::getInstance()->verificarPermissao('procedimento_plano_associar')) {
    $arrComandos[] = '<button type="button" id="btnAssociar" value="Associar Plano" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_plano_associar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton">Associar Plano</button>';
  }

  $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
  $objPesquisaPendenciaDTO->setDblIdProtocolo($_GET['id_procedimento']);
  $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
  $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  $objAtividadeRN = new AtividadeRN();
  $arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);

  $bolAberto = false;
  if (count($arrObjProcedimentoDTO)) {
    $bolAberto = true;
  }

  $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
  $objPlanoTrabalhoDTO->setDblIdProcedimento($_GET['id_procedimento']);

  $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
  $objPlanoTrabalhoDTO = $objPlanoTrabalhoRN->detalhar($objPlanoTrabalhoDTO);

  $numRegistrosEtapas = 0;
  $numRegistrosItens = 0;

  $numIdPlanoTrabalho = '';

  if ($objPlanoTrabalhoDTO != null) {
    if (SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_consultar_historico')) {
      $arrComandos[] = '<button type="button" accesskey="H" name="btnHistorico" id="btnHistorico" value="Histórico" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_consultar_historico&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho() . '&id_procedimento=' . $_GET['id_procedimento']) . '\';" class="infraButton"><span class="infraTeclaAtalho">H</span>istórico</button>';
    }


    $numIdPlanoTrabalho = $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho();

    $arrObjEtapaTrabalhoDTO = $objPlanoTrabalhoDTO->getArrObjEtapaTrabalhoDTO();

    $numRegistrosEtapas = count($arrObjEtapaTrabalhoDTO);

    if ($numRegistrosEtapas) {
      foreach ($arrObjEtapaTrabalhoDTO as $objEtapaTrabalhoDTO) {
        $numRegistrosItens += count($objEtapaTrabalhoDTO->getArrObjItemEtapaDTO());
      }

      if (!SessaoSEI::getInstance()->isSetAtributo('ETAPA_TRABALHO_VISUALIZACAO_' . $numIdPlanoTrabalho)) {
        $strEtapasTrabalho = '';
        foreach ($arrObjEtapaTrabalhoDTO as $objEtapaTrabalhoDTO) {
          $strEtapasTrabalho .= '[' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . ']';
        }
        SessaoSEI::getInstance()->setAtributo('ETAPA_TRABALHO_VISUALIZACAO_' . $numIdPlanoTrabalho, $strEtapasTrabalho);
      }

      $bolCheck = false;

      $bolAcaoItemEtapaIncluirDocumento = SessaoSEI::getInstance()->verificarPermissao('item_etapa_incluir_documento');
      $bolAcaoItemEtapaAtualizarAndamento = SessaoSEI::getInstance()->verificarPermissao('item_etapa_atualizar_andamento');
      $bolAcaoItemEtapaConsultarAndamento = SessaoSEI::getInstance()->verificarPermissao('item_etapa_consultar_andamento');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');

      $strResultado = '';

      $strSumarioTabela = 'Tabela de Itens.';
      $strCaptionTabela = 'Itens';

      $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '" style="background-color:white;">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistrosItens) . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%" style="display:none">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      $strResultado .= '<th class="tituloTabela" width="1%">&nbsp;</th>' . "\n";
      $strResultado .= '<th class="tituloTabela" width="1%">&nbsp;</th>' . "\n";
      $strResultado .= '<th class="tituloTabela" width="1%">&nbsp;</th>' . "\n";
      $strResultado .= '<th class="tituloTabela">Nome</th>' . "\n";
      $strResultado .= '<th class="tituloTabela">Documento</th>' . "\n";
      $strResultado .= '<th class="tituloTabela">Unidade</th>' . "\n";
      $strResultado .= '<th class="tituloTabela" width="12%">Ações</th>' . "\n";
      $strResultado .= '</tr>' . "\n";

      $strLinkVisualizacaoAcoes = '';
      $strLinkVisualizacaoAcoes .= '<a id="ancVerTodosAcoes" href="javascript:void(0);" onclick="verItens(\'V\');" class="ancoraPadraoPreta" tabindex="[TABINDEX]">Ver todos os itens</a>';
      $strLinkVisualizacaoAcoes .= '&nbsp;&nbsp;';
      $strLinkVisualizacaoAcoes .= '<a id="ancOcultarTodosAcoes" href="javascript:void(0);" onclick="verItens(\'O\');" class="ancoraPadraoPreta" tabindex="[TABINDEX]">Ocultar todos os itens</a>';

      $n = 0;


      foreach ($arrObjEtapaTrabalhoDTO as $objEtapaTrabalhoDTO) {
        $arrObjItemEtapaDTO = $objEtapaTrabalhoDTO->getArrObjItemEtapaDTO();

        $strResultado .= '<tr class="infraTrEscura" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '">' . "\n";

        $strResultado .= '<td style="display:none">' . PaginaSEI::getInstance()->getTrCheck($n++, $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho(), $objEtapaTrabalhoDTO->getStrNome()) . '</td>' . "\n";
        $strResultado .= '<td width="1%">';

        if (count($arrObjItemEtapaDTO)) {
          if (strpos(SessaoSEI::getInstance()->getAtributo('ETAPA_TRABALHO_VISUALIZACAO_' . $numIdPlanoTrabalho), '[' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . ']') !== false) {
            $strDisplayOcultar = '';
            $strDisplayExibir = 'display:none;';
          } else {
            $strDisplayOcultar = 'display:none;';
            $strDisplayExibir = '';
          }
          $strResultado .= '<img src="' . PaginaSEI::getInstance()->getIconeOcultar() . '" id="imgOcultarEtapa' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '" onclick="exibirOcultarEtapa(' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . ')" title="Ocultar Itens da Etapa" style="filter: invert(66%);' . $strDisplayOcultar . '" />';
          $strResultado .= '<img src="' . PaginaSEI::getInstance()->getIconeExibir() . '" id="imgExibirEtapa' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '" onclick="exibirOcultarEtapa(' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . ')" title="Exibir Itens da Etapa" style="filter: invert(66%);' . $strDisplayExibir . '" />';
        } else {
          $strResultado .= '&nbsp;';
        }

        $strResultado .= '</td>' . "\n";

        $strResultado .= '<td width="1%">&nbsp;</td>' . "\n";
        $strResultado .= '<td width="1%">&nbsp;</td>' . "\n";
        $strResultado .= '<td colspan="2"><a title="Consultar Etapa de Trabalho" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=etapa_trabalho_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="linkDetalhamentoPlanoTrabalho"><span style="font-weight:bold;font-size:.9em;">' . PaginaSEI::tratarHTML($objEtapaTrabalhoDTO->getStrNome()) . '</span></a></td>' . "\n";
        $strResultado .= '<td>&nbsp;</td>' . "\n";

        $strResultado .= '<td align="center">';

        $strResultado .= '</td>' . "\n";
        $strResultado .= '</tr>' . "\n";

        /** @var ItemEtapaDTO $objItemEtapaDTO */

        $k = 0;
        foreach ($arrObjItemEtapaDTO as $objItemEtapaDTO) {
          $k++;

          $arrIdSeriesEtapa = InfraArray::converterArrInfraDTO($objItemEtapaDTO->getArrObjRelItemEtapaSerieDTO(), 'IdSerie');
          $arrObjRelItemEtapaDocumentoDTO = $objItemEtapaDTO->getArrObjRelItemEtapaDocumentoDTO();

          $objSituacaoAndamentoPlanoTrabalhoDTO = null;
          foreach ($objItemEtapaDTO->getArrObjAndamentoPlanoTrabalhoDTO() as $objAndamentoPlanoTrabalhoDTO) {
            $objSituacaoAndamentoPlanoTrabalhoDTO = $objAndamentoPlanoTrabalhoDTO->getObjSituacaoAndamentoPlanoTrabalhoDTO();
            break;
          }

          if (strpos(SessaoSEI::getInstance()->getAtributo('ETAPA_TRABALHO_VISUALIZACAO_' . $numIdPlanoTrabalho), '[' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . ']') !== false) {
            $strDisplayTr = '';
          } else {
            $strDisplayTr = 'display:none;';
          }

          $strResultado .= '<tr class="infraTrClara" id="trItemEtapa' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '_' . $k . '" style="' . $strDisplayTr . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '">' . "\n";
          $strResultado .= '<td style="display:none">' . PaginaSEI::getInstance()->getTrCheck($n++, $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '-' . $objItemEtapaDTO->getNumIdItemEtapa(),
              $objItemEtapaDTO->getStrNome()) . '</td>' . "\n";
          $strResultado .= '<td width="1%">&nbsp;</td>' . "\n";

          if ($objSituacaoAndamentoPlanoTrabalhoDTO != null) {
            $strResultado .= '<td width="1%"><a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip($objSituacaoAndamentoPlanoTrabalhoDTO->getStrDescricao()) . '><img src="' . $objSituacaoAndamentoPlanoTrabalhoDTO->getStrIcone() . '" class="imagemStatus" /></a></td>';
          } else {
            $strResultado .= '<td width="1%">&nbsp;</td>';
          }

          $strResultado .= '<td width="1%">&nbsp;</td>' . "\n";

          $strResultado .= '<td><a title="Consultar Item da Etapa" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=item_etapa_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_item_etapa=' . $objItemEtapaDTO->getNumIdItemEtapa() . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '&id_plano_trabalho=' . $numIdPlanoTrabalho) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="linkDetalhamentoPlanoTrabalho">' . PaginaSEI::tratarHTML($objItemEtapaDTO->getStrNome()) . '</a></td>' . "\n";

          $strResultado .= '<td align="center">' . "\n";

          $strResultadoSiglaUnidade = '';

          /** @var RelItemEtapaDocumentoDTO $objRelItemEtapaDocumentoDTO */
          foreach ($arrObjRelItemEtapaDocumentoDTO as $objRelItemEtapaDocumentoDTO) {
            $objProtocoloDTO = $objRelItemEtapaDocumentoDTO->getObjProtocoloDTO();

            if ($objProtocoloDTO != null) {
              $strResultado .= '<div class="linhaTabela">' . "\n";
              $strResultado .= '<div class="colunaTabela">' . "\n";
              //$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$objProtocoloDTO->getDblIdProtocolo()).'" target="_blank" class="ancoraPadraoAzul" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeSerieDocumento()).'">'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrProtocoloFormatado()).'</a>';

              $strLinkDocumento = '<a ';
              if ($objProtocoloDTO->getNumCodigoAcesso() > 0) {
                $strLinkDocumento .= ' class="ancoraPadraoAzul" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_documento=' . $objProtocoloDTO->getDblIdProtocolo()) . '" target="_blank"';
              } else {
                $strLinkDocumento .= ' href="javascript:void(0);" class="ancoraPadraoPreta"';
              }
              $strLinkDocumento .= ' alt="' . PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeSerieDocumento()) . '" title="' . PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeSerieDocumento()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '">' . PaginaSEI::tratarHTML($objProtocoloDTO->getStrProtocoloFormatado()) . '</a>';

              $strResultado .= $strLinkDocumento;

              $strResultado .= '</div>' . "\n";
              $strResultado .= '</div>' . "\n";

              $strResultadoSiglaUnidade .= '<div class="linhaTabela">' . "\n";
              $strResultadoSiglaUnidade .= '<div class="colunaTabela">' . "\n";
              $strResultadoSiglaUnidade .= '<a alt="' . PaginaSEI::tratarHTML($objProtocoloDTO->getStrDescricaoUnidadeGeradora()) . '" title="' . PaginaSEI::tratarHTML($objProtocoloDTO->getStrDescricaoUnidadeGeradora()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objProtocoloDTO->getStrSiglaUnidadeGeradora()) . '</a>';
              $strResultadoSiglaUnidade .= '</div>' . "\n";
              $strResultadoSiglaUnidade .= '</div>' . "\n";
            }
          }

          $strResultado .= '</td>' . "\n";

          $strResultado .= '<td align="center">' . $strResultadoSiglaUnidade . '</td>' . "\n";

          $strResultado .= '<td align="center">' . "\n";

          if ($bolAcaoItemEtapaIncluirDocumento && $bolAberto && count($arrIdSeriesEtapa) && $objItemEtapaDTO->getStrSinUnidadeAcesso() == 'S') {
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=item_etapa_incluir_documento&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho() . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '&id_item_etapa=' . $objItemEtapaDTO->getNumIdItemEtapa()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . Icone::ITEM_ETAPA_INCLUIR_DOCUMENTO . '" title="Incluir Documento" alt="Incluir Documento" class="infraImg" /></a>&nbsp;';
          }

          if ($bolAcaoItemEtapaAtualizarAndamento && $bolAberto && $objItemEtapaDTO->getStrSinUnidadeAcesso() == 'S') {
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=item_etapa_atualizar_andamento&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho() . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '&id_item_etapa=' . $objItemEtapaDTO->getNumIdItemEtapa()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeAlterar() . '" title="Atualizar Item" alt="Atualizar Item" class="infraImg" /></a>&nbsp;';
          } else {
            if ($bolAcaoItemEtapaConsultarAndamento && count($objItemEtapaDTO->getArrObjAndamentoPlanoTrabalhoDTO())) {
              $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=item_etapa_consultar_andamento&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho() . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '&id_item_etapa=' . $objItemEtapaDTO->getNumIdItemEtapa()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeConsultar() . '" title="Consultar Item" alt="Consultar Item" class="infraImg" /></a>&nbsp;';
            }
          }

          $strResultado .= '&nbsp;</td>';
          $strResultado .= '</tr>' . "\n";
        }
      }
      $strResultado .= '</table>';
    }
  }

  if ($_GET['acao_retorno'] != '') {

    $strAncora = '';
    if ($_GET['acao_retorno']=='documento_escolher_tipo'){
      $strAncora = PaginaSEI::montarAncora($_GET['id_serie']);
    }

    $arrComandos[] = '<button type="button" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'].$strAncora) . '\';" class="infraButton">Voltar</button>';
  }
  //$strPlanoTrabalho = '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_plano_trabalho='.$objPlanoTrabalhoDTO->getNumIdPlanoTrabalho()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><span style="font-weight:bold;font-size:.9em;">'.PaginaSEI::tratarHTML($objPlanoTrabalhoDTO->getStrNome()).'</span></a></td>';

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

#txtPlanoTrabalho {position:absolute;left:0%;top:0%;width:60%;}
#divOpcoesPlanoTrabalho {position:absolute;left:61%;top:10%;}

a.linkDetalhamentoPlanoTrabalho:hover{text-decoration:none !important;}

table{
border-spacing: 0px !important;
}

table.infraTable td {
line-height: 24px;
}

td a img.infraImg{
vertical-align:top;
}

th.tituloTabela{
font-size:1em;
font-weight: bold;
text-align: center;
color: #000;
border-spacing: 0;
padding:.2em;
}

div.linhaTabela{
display:table-row;
}

div.colunaTabela{
display:table-cell;
vertical-align: middle;
text-align: center;
height: 24px;
}

<? if (PaginaSEI::getInstance()->getStrEsquemaCores()==InfraPaginaEsquema3::$ESQUEMA_PRETO){?>
img[src*="item_etapa_incluir_documento.svg"]
{
filter: invert(100%);
}

a:focus img[src*="item_etapa_incluir_documento.svg"]{
outline: 1px dotted black !important;
}
<? }?>

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>
  var arrEtapas = [<?=is_array($arrObjEtapaTrabalhoDTO) ? implode(',', InfraArray::converterArrInfraDTO($arrObjEtapaTrabalhoDTO, 'IdEtapaTrabalho')) : ''?>];
  var etapasTrabalhoVisualizacao = '<?=SessaoSEI::getInstance()->getAtributo('ETAPA_TRABALHO_VISUALIZACAO_' . $numIdPlanoTrabalho)?>';
  var objAjaxEtapaTrabalhoVisualizacao = null;
  var bolCarregando = true;

  function inicializar() {

    bolCarregando = true;

    objAjaxEtapaTrabalhoVisualizacao = new infraAjaxComplementar(null, '<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=etapa_trabalho_salvar_visualizacao')?>');

    objAjaxEtapaTrabalhoVisualizacao.prepararExecucao = function () {
      return 'id_plano_trabalho=<?=$numIdPlanoTrabalho?>&etapas_trabalho=' + etapasTrabalhoVisualizacao;
    };

    objAjaxEtapaTrabalhoVisualizacao.processarResultado = function (arr) {
    };

    infraEfeitoTabelas();

    bolCarregando = false;
  }

  function verItens(tipoVisualizacao) {
    for (var i = 0; i < arrEtapas.length; i++) {
      exibirOcultarEtapa(arrEtapas[i], tipoVisualizacao);
    }
  }

  function exibirOcultarEtapa(idEtapaTrabalho, tipoVisualizacao) {
    if (document.getElementById('imgExibirEtapa' + idEtapaTrabalho) != null) {
      var k = 1;

      if (!tipoVisualizacao) {
        var mostrar = document.getElementById('imgExibirEtapa' + idEtapaTrabalho).style.display;
        document.getElementById('imgExibirEtapa' + idEtapaTrabalho).style.display = document.getElementById('imgOcultarEtapa' + idEtapaTrabalho).style.display;
      } else if (tipoVisualizacao == 'V') {
        var mostrar = "";
        document.getElementById('imgExibirEtapa' + idEtapaTrabalho).style.display = "none";
      } else {
        var mostrar = "none";
        document.getElementById('imgExibirEtapa' + idEtapaTrabalho).style.display = "";
      }

      document.getElementById('imgOcultarEtapa' + idEtapaTrabalho).style.display = mostrar;
      var tr = document.getElementById('trItemEtapa' + idEtapaTrabalho + '_' + k);
      while (tr) {
        tr.style.display = mostrar;
        k++;
        tr = document.getElementById('trItemEtapa' + idEtapaTrabalho + '_' + k);
      }

      if (mostrar == '') {
        if (etapasTrabalhoVisualizacao.indexOf('[' + idEtapaTrabalho + ']') == -1) {
          etapasTrabalhoVisualizacao = etapasTrabalhoVisualizacao.concat('[' + idEtapaTrabalho + ']');
        }
      } else {
        etapasTrabalhoVisualizacao = etapasTrabalhoVisualizacao.replace('[' + idEtapaTrabalho + ']', '');
      }

      objAjaxEtapaTrabalhoVisualizacao.executar();

      infraProcessarResize();

    }
  }


  //</script>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmPlanoTrabalhoDetalhar" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  if ($objPlanoTrabalhoDTO != null) {
    PaginaSEI::getInstance()->abrirAreaDados('2em');
    ?>
    <input type="text" id="txtPlanoTrabalho" name="txtPlanoTrabalho" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($objPlanoTrabalhoDTO->getStrNome());?>" readonly="readonly"
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <div id="divOpcoesPlanoTrabalho">
      <?
      if (SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_consultar')) {
        ?>
        <a id="lnkConsultarPlanoTrabalho"
           onclick="location.href='<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho())?>'"
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeConsultar()?>" title="Consultar Plano de Trabalho" alt="Consultar Plano de Trabalho" class="infraImg"/></a>
        <?
      }
      ?>
    </div>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
  }
  ?>
  <br/>
  <div id="divLinksVisualizacaoAcoes" class="infraAreaDados" style="height:2em;">
    <?=str_replace('[TABINDEX]', PaginaSEI::getInstance()->getProxTabDados(), $strLinkVisualizacaoAcoes)?>
  </div>
  <?php
  PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistrosEtapas + $numRegistrosItens);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
