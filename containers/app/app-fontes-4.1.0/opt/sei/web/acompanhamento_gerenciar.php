<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/09/2017 - criado por mga
 * 15/06/2018 - cjy - ícone de acompanhamento no controle de processos
 *
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

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples', 'id_procedimento', 'id_acompanhamento', 'id_usuario_atribuicao'));

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  if (isset($_GET['pagina_simples'])) {
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $bolMultiplo = false;

  $arrComandos = array();

  $objAndamentoMarcadorRN = new AndamentoMarcadorRN();

  switch ($_GET['acao']) {

    case 'acompanhamento_gerenciar':
      $strTitulo = 'Acompanhamentos Especiais do Processo';

      if ($_GET['acao_origem'] == 'arvore_visualizar' && SessaoSEI::getInstance()->verificarPermissao('acompanhamento_cadastrar')) {

        $dto = new AcompanhamentoDTO();
        $dto->setNumMaxRegistrosRetorno(1);
        $dto->retNumIdAcompanhamento();
        $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $dto->setDblIdProtocolo($_GET['id_procedimento']);

        $objAcompanhamentoRN = new AcompanhamentoRN();
        if ($objAcompanhamentoRN->consultar($dto) == null) {
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']));
          die;
        }
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $bolAcaoListar = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_listar');
  $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_cadastrar');
  if ($bolAcaoCadastrar) {
    $arrComandos[] = '<button type="button" id="btnAdicionar" value="Adicionar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton">Adicionar</button>';
  }

  $objProtocoloDTO = new ProtocoloDTO();
  $objProtocoloDTO->retStrProtocoloFormatado();
  $objProtocoloDTO->setDblIdProtocolo($_GET['id_procedimento']);

  $objProtocoloRN = new ProtocoloRN();
  $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

  if ($objProtocoloDTO == null) {
    throw new InfraException("Processo não encontrado.");
  }

  $strTitulo .= ' ' . $objProtocoloDTO->getStrProtocoloFormatado();


  $objAcompanhamentoDTO = new AcompanhamentoDTO();
  $objAcompanhamentoDTO->setDblIdProtocolo($_GET['id_procedimento']);

  PaginaSEI::getInstance()->prepararPaginacao($objAcompanhamentoDTO);

  $objAcompanhamentoRN = new AcompanhamentoRN();
  $arrObjAcompanhamentoDTO = $objAcompanhamentoRN->listarAcompanhamentosUnidade($objAcompanhamentoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objAcompanhamentoDTO);
  $numRegistros = count($arrObjAcompanhamentoDTO);


  if ($numRegistros > 0) {

    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_alterar');
    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_excluir');

    if ($bolAcaoExcluir) {
      $arrComandos[] = '<button type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton">Excluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_excluir&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);
    }

    $bolCheck = false;

    $strResultado = '';

    /* if ($_GET['acao']!='acompanhamento_reativar'){ */
    $strSumarioTabela = 'Tabela de Acompanhamentos.';
    $strCaptionTabela = 'Acompanhamentos';
    /* }else{
      $strSumarioTabela = 'Tabela de Acompanhamentos Inativos.';
      $strCaptionTabela = 'Acompanhamentos Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck('', 'Infra', '', false) . '</th>' . "\n";
    //$strResultado .= '<th class="infraTh" width="6%">&nbsp;</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Grupo</th>' . "\n";
    $strResultado .= '<th class="infraTh">Observação</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Usuário</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Data</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {

      $objProcedimentoDTO = $arrObjAcompanhamentoDTO[$i]->getObjProcedimentoDTO();

      $strCssTr = ($strCssTr == 'class="infraTrClara"') ? 'class="infraTrEscura"' : 'class="infraTrClara"';
      $strResultado .= '<tr ' . $strCssTr . '>';

      $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento(), ProcedimentoINT::formatarProtocoloTipoRI0200($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado(), $objProcedimentoDTO->getStrNomeTipoProcedimento()), 'N', 'Infra', '', false) . '</td>';
      //$strResultado .= '<td align="center" valign="top">';
      //$strResultado .= AnotacaoINT::montarIconeAnotacao($objProcedimentoDTO->getObjAnotacaoDTO(),$bolAcaoRegistrarAnotacao,$arrObjAcompanhamentoDTO[$i]->getDblIdProtocolo(),'&id_acompanhamento='.$arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento());
      //$strResultado .= ProcedimentoINT::montarIconeVisualizacao($arrObjAcompanhamentoDTO[$i]->getNumTipoVisualizacao(), $objProcedimentoDTO, $arrRetIconeIntegracao,$bolAcaoAndamentoSituacaoGerenciar,$bolAcaoAndamentoMarcadorGerenciar,'&id_acompanhamento='.$arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento());
      //$strResultado .= '</td>';

      $strResultado .= '<td align="center" valign="top">' . PaginaSEI::tratarHTML($arrObjAcompanhamentoDTO[$i]->getStrNomeGrupo()) . '</td>';

      $strResultado .= '<td valign="top">';
      $strObservacao = PaginaSEI::tratarHTML($arrObjAcompanhamentoDTO[$i]->getStrObservacao());
      $strObservacao = str_replace('&lt;b&gt;', '<b>', $strObservacao);
      $strObservacao = str_replace('&lt;/b&gt;', '</b>', $strObservacao);
      $strResultado .= $strObservacao;
      $strResultado .= '</td>';

      $strResultado .= '<td align="center" valign="top"><a alt="' . PaginaSEI::tratarHTML($arrObjAcompanhamentoDTO[$i]->getStrNomeUsuario()) . '" title="' . PaginaSEI::tratarHTML($arrObjAcompanhamentoDTO[$i]->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($arrObjAcompanhamentoDTO[$i]->getStrSiglaUsuario()) . '</a></td>';
      $strResultado .= '<td align="center" valign="top">' . $arrObjAcompanhamentoDTO[$i]->getDthAlteracao() . '</td>';

      $strResultado .= '<td align="center" valign="top">';

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_acompanhamento=' . $arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento()) . '" ><img src="' . PaginaSEI::getInstance()->getIconeAlterar() . '" title="Alterar Acompanhamento" alt="Alterar Acompanhamento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir) {
        $strId = $arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjAcompanhamentoDTO[$i]->getStrObservacao());
      }

      if ($bolAcaoExcluir) {
        $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" ><img src="' . PaginaSEI::getInstance()->getIconeExcluir() . '" title="Excluir Acompanhamento" alt="Excluir Acompanhamento" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }

  if (PaginaSEI::getInstance()->getAcaoRetorno() == "procedimento_controlar") {
    $strAncora = $_GET['id_procedimento'];
    $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
  }

  $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&montar_visualizacao=0');

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script type="javascript">

var objAjaxMarcadores = null;

function inicializar(){

  //atualiza árvore para mostrar o relacionamento
  <?if (($_GET['acao_origem']=='acompanhamento_cadastrar' || $_GET['acao_origem']=='acompanhamento_excluir') && $_GET['resultado']=='1') { ?>
  parent.parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
  <?}?>

  infraEfeitoTabelas();

}

function OnSubmitForm() {
  return true;
}

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Acompanhamento Especial do processo?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGerenciarAcompanhamento').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGerenciarAcompanhamento').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Acompanhamento Especial selecionado.');
    return;
  }
  if (confirm("Confirma remoção dos Acompanhamentos Especiais selecionados do processo?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGerenciarAcompanhamento').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGerenciarAcompanhamento').submit();
  }
}
<? } ?>

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmGerenciarAcompanhamento" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>


  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>