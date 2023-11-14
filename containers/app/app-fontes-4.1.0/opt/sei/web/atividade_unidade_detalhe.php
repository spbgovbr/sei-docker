<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/03/2023 - criado por mgb29
 *
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);

  $strParametros = '';

  if (isset($_GET['id_unidade'])){
    $strParametros .= '&id_unidade='.$_GET['id_unidade'];
  }

  if (isset($_GET['id_usuario'])){
    $strParametros .= '&id_usuario='.$_GET['id_usuario'];
  }

  if (isset($_GET['dta_inicio'])){
    $strParametros .= '&dta_inicio='.$_GET['dta_inicio'];
  }

  if (isset($_GET['dta_fim'])){
    $strParametros .= '&dta_fim='.$_GET['dta_fim'];
  }

  if (isset($_GET['id_tarefa'])){
    $strParametros .= '&id_tarefa='.$_GET['id_tarefa'];
  }

  switch($_GET['acao']){

    case 'atividade_unidade_detalhe':
      $strTitulo = 'Detalhe de Atividades';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $objAtividadeUnidadeDTO = new AtividadeUnidadeDTO();
  $objAtividadeUnidadeDTO->setNumIdUnidade($_GET['id_unidade']);
  $objAtividadeUnidadeDTO->setNumIdUsuario($_GET['id_usuario']);
  $objAtividadeUnidadeDTO->setDtaInicio($_GET['dta_inicio']);
  $objAtividadeUnidadeDTO->setDtaFim($_GET['dta_fim']);
  $objAtividadeUnidadeDTO->setNumIdTarefa($_GET['id_tarefa']);
  $objAtividadeUnidadeDTO->setStrStaTipo(AtividadeUnidadeRN::$T_DETALHADO);

  PaginaSEI::getInstance()->prepararPaginacao($objAtividadeUnidadeDTO);

  $objAtividadeUnidadeRN = new AtividadeUnidadeRN();
  $arrObjAtividadeUnidadeDTO = $objAtividadeUnidadeRN->pesquisar($objAtividadeUnidadeDTO);

  PaginaSEI::getInstance()->processarPaginacao($objAtividadeUnidadeDTO);
  $numRegistros = count($arrObjAtividadeUnidadeDTO);

  $strResultado = '';

  if ($numRegistros > 0){

    $arrComandos[] = '<button type="button" accesskey="T" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton">Imprimir</button>';

    $strResultado .= '<table id="tblAtividade" width="99%" class="infraTable" summary="Histórico de Atividades">' . "\n";

    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('Atividades', $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">Processo</th>';
    $strResultado .= '<th class="infraTh" width="15%">Data/Hora</th>';
    $strResultado .= '<th class="infraTh" width="15%">Unidade</th>';
    $strResultado .= '<th class="infraTh" width="10%">Usuário</th>';
    $strResultado .= '<th class="infraTh">Descrição</th>';
    $strResultado .= '</tr>' . "\n";
    $strCssTr='';

    $i = 0;
    foreach ($arrObjAtividadeUnidadeDTO as $dto) {

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i++, $dto->getNumIdAtividade(), $dto->getDthAbertura()) . '</td>';

      $strResultado .= "\n" . '<td align="center"  valign="top">';
      $strResultado .= '<a target="_blank" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $dto->getDblIdProcedimento()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" alt="' . PaginaSEI::tratarHTML($dto->getStrNomeTipoProcedimento()) . '" title="' . PaginaSEI::tratarHTML($dto->getStrNomeTipoProcedimento()) . '">' . PaginaSEI::tratarHTML($dto->getStrProtocoloFormatadoProcedimento()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= "\n" . '<td align="center" valign="top">';
      $strResultado .= substr($dto->getDthAbertura(), 0, 16);
      $strResultado .= '</td>';

      $strResultado .= "\n" . '<td align="center"  valign="top">';
      $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($dto->getStrDescricaoUnidade()) . '" title="' . PaginaSEI::tratarHTML($dto->getStrDescricaoUnidade()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($dto->getStrSiglaUnidade()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= "\n" . '<td align="center"  valign="top">';
      $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($dto->getStrNomeUsuario()) . '" title="' . PaginaSEI::tratarHTML($dto->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($dto->getStrSiglaUsuario()) . '</a>';
      $strResultado .= '</td>';
      $strResultado .= "\n" . '<td valign="top">';
      $strResultado .= $dto->getStrNomeTarefa();
      $strResultado .= '</td>';

      $strResultado .= '</tr>';
    }
    $strResultado .= '</table>';
  }

  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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

function inicializar(){
  document.getElementById('btnFecharSelecao').focus();
  infraEfeitoTabelas();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmAtividadeUnidadeDetalhe" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->abrirAreaDados('5em');
    //PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>