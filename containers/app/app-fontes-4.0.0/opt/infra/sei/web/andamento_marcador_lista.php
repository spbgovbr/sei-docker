<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 09/08/2017 - criado por mga
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

  $numIdSituacao = null;

  $strDesabilitar = '';

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples', 'id_acompanhamento', 'id_usuario_atribuicao', 'id_marcador', 'id_procedimento'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  if (isset($_GET['pagina_simples'])){
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $bolMultiplo = false;

  $arrComandos = array();

  $objAndamentoMarcadorRN = new AndamentoMarcadorRN();

  switch ($_GET['acao']) {

    case 'andamento_marcador_listar':
      $strTitulo = 'Histórico de Marcadores do Processo';

      $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();

      $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
  $objAndamentoMarcadorDTO->setBolExclusaoLogica(false);
  $objAndamentoMarcadorDTO->retNumIdMarcador();
  $objAndamentoMarcadorDTO->retStrNomeMarcador();
  $objAndamentoMarcadorDTO->retStrStaIconeMarcador();
  $objAndamentoMarcadorDTO->retStrSinAtivoMarcador();
  $objAndamentoMarcadorDTO->retStrTexto();
  $objAndamentoMarcadorDTO->retDthExecucao();
  $objAndamentoMarcadorDTO->retNumIdUsuario();
  $objAndamentoMarcadorDTO->retStrSiglaUsuario();
  $objAndamentoMarcadorDTO->retStrNomeUsuario();
  $objAndamentoMarcadorDTO->retStrStaOperacao();
  $objAndamentoMarcadorDTO->retNumIdAndamentoMarcador();
  $objAndamentoMarcadorDTO->setDblIdProcedimento($_GET['id_procedimento']);
  $objAndamentoMarcadorDTO->retStrSinAtivo();

  if (isset($_POST['hdnIdMarcador']) && $_POST['hdnIdMarcador']!=''){
    $objAndamentoMarcadorDTO->setNumIdMarcador($_POST['hdnIdMarcador']);
  }

  $objAndamentoMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objAndamentoMarcadorDTO->setOrdDthExecucao(InfraDTO::$TIPO_ORDENACAO_DESC);

  PaginaSEI::getInstance()->prepararPaginacao($objAndamentoMarcadorDTO, 100);

  $objAndamentoMarcadorRN = new AndamentoMarcadorRN();
  $arrObjAndamentoMarcadorDTO = $objAndamentoMarcadorRN->listar($objAndamentoMarcadorDTO);

  PaginaSEI::getInstance()->processarPaginacao($objAndamentoMarcadorDTO);

  $numRegistrosAndamento = count($arrObjAndamentoMarcadorDTO);

  if ($numRegistrosAndamento > 0) {

    $objMarcadorRN = new MarcadorRN();
    $arrObjIconeMarcadorDTO = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(),'StaIcone');
    $arrObjOperacaoAndamentoMarcadorDTO = InfraArray::indexarArrInfraDTO($objAndamentoMarcadorRN->listarValoresOperacao(),'StaOperacao');

    $strResultado = '';

    $strResultado .= '<table id="tblHistorico" width="99%" class="infraTable" summary="Histórico de Marcadores">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('Histórico de Marcadores', $numRegistrosAndamento, '') . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="15%">Data/Hora</th>';
    $strResultado .= '<th class="infraTh" width="10%">Usuário</th>';
    $strResultado .= '<th class="infraTh" width="10%">Operação</th>';
    $strResultado .= '<th class="infraTh" width="25%">Marcador</th>';
    $strResultado .= '<th class="infraTh">Texto</th>';
    $strResultado .= '</tr>' . "\n";

    $strQuebraLinha = '<span style="line-height:.5em"><br /></span>';

    foreach ($arrObjAndamentoMarcadorDTO as $objAndamentoMarcadorDTO) {

      if ($objAndamentoMarcadorDTO->getStrSinAtivo()=='S'){
        $strResultado .= '<tr class="infraTrClara">';
      }else{
        $strResultado .= '<tr class="trVermelha">';
      }

      $strResultado .= '<td align="center" valign="top">'.substr($objAndamentoMarcadorDTO->getDthExecucao(), 0, 16).'</td>'."\n";

      $strResultado .= '<td align="center"  valign="top">';
      $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrNomeUsuario()) . '" title="' . PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrSiglaUsuario()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td align="center"  valign="top">';
      $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($arrObjOperacaoAndamentoMarcadorDTO[$objAndamentoMarcadorDTO->getStrStaOperacao()]->getStrDescricao()) . '" title="' . PaginaSEI::tratarHTML($arrObjOperacaoAndamentoMarcadorDTO[$objAndamentoMarcadorDTO->getStrStaOperacao()]->getStrDescricao()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrStaOperacao()) . '</a>';
      $strResultado .= '</td>';


      $strResultado .= '<td align="left" valign="top">';

      if ($objAndamentoMarcadorDTO->getNumIdMarcador()!=null) {
        $strResultado .= '<a href="#" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.$arrObjIconeMarcadorDTO[$objAndamentoMarcadorDTO->getStrStaIconeMarcador()]->getStrArquivo().'" title="'.PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrNomeMarcador()).'" alt="'.PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrNomeMarcador()).'" class="infraImg" /></a>&nbsp;';
        $strResultado .= PaginaSEI::tratarHTML(MarcadorINT::formatarMarcadorDesativado($objAndamentoMarcadorDTO->getStrNomeMarcador(),$objAndamentoMarcadorDTO->getStrSinAtivoMarcador()));
      }else{
        $strResultado .= '[REMOVIDO]';
      }
      $strResultado .= '</td>'."\n";

      $strResultado .= '<td valign="top">'.PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrTexto()).'</td>'."\n";

      $strResultado .= '</tr>';
    }
    $strResultado .= '</table>';
  }

  $strItensSelMarcador = MarcadorINT::montarSelectProcedimento('','Todos',$_POST['hdnIdMarcador'],$_GET['id_procedimento']);

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

#divDados {height:5em;overflow:visible !important;}

#lblMarcador {position:absolute;left:0%;top:0%;}
#selMarcador {position:absolute;left:0%;top:40%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script type="javascript">

var bolInicializando = true;

function inicializar(){

  $('#selMarcador').ddslick({width: 400,
    onSelected: function(data){
     if (!bolInicializando) {
       document.getElementById('hdnIdMarcador').value = data.selectedData.value;
       document.getElementById('frmAndamentoMarcadorLista').submit();
     }
   }
  });

  infraEfeitoTabelas();

  bolInicializando = false;
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmAndamentoMarcadorLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>
    <div id="divDados" class="infraAreaDados">

      <label id="lblMarcador" for="selMarcador" accesskey="" class="infraLabelOpcional">Marcador:</label>
      <select id="selMarcador" name="selMarcador" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensSelMarcador?>
      </select>

      <input type="hidden" id="hdnIdMarcador" name="hdnIdMarcador" value="<?=$numIdMarcador?>" />
    </div>

    <?
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistrosAndamento);
    PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>