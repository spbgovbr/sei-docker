<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.12.0
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

  PaginaSEI::getInstance()->salvarCamposPost(array('selTipoProcedimento', 'selSituacao', 'hdnTipo'));

  if (isset($_POST['hdnTipo'])) {
    PaginaSEI::getInstance()->salvarCampo('chkSinSituacoesDesativadas', $_POST['chkSinSituacoesDesativadas']);
  }

  switch($_GET['acao']){

    case 'controle_unidade_gerar':
    case 'controle_unidade_gerar_grafico':
      $strTitulo = 'Pontos de Controle';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
    
  if (SessaoSEI::getInstance()->verificarPermissao('controle_unidade_gerar')){
    $arrComandos[] = '<button type="button" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" onclick="processar(\'P\',\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_unidade_gerar&acao_origem='.$_GET['acao']).'\');" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar Processos</button>';
  }
  
  if (SessaoSEI::getInstance()->verificarPermissao('controle_unidade_gerar_grafico')){
    $arrComandos[] = '<button type="button" accesskey="G" id="sbmGerarGrafico" name="sbmGerarGrafico" onclick="processar(\'G\',\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_unidade_gerar_grafico&acao_origem='.$_GET['acao']).'\');" value="Gerar Gráficos" class="infraButton"><span class="infraTeclaAtalho">G</span>erar Gráficos</button>';
  }
  
  $arrComandos[] = '<button type="button" accesskey="L" id="btnLimpar" name="btnLimpar" onclick="limpar();" value="Limpar" class="infraButton"><span class="infraTeclaAtalho">L</span>impar Critérios</button>';          	

  $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();
  $objAndamentoSituacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objAndamentoSituacaoDTO->setNumIdTipoProcedimentoProcedimento(PaginaSEI::getInstance()->recuperarCampo('selTipoProcedimento'));
  $objAndamentoSituacaoDTO->setNumIdSituacao(PaginaSEI::getInstance()->recuperarCampo('selSituacao'));
  $objAndamentoSituacaoDTO->setStrSinSituacoesDesativadas(PaginaSEI::getInstance()->getCheckbox(PaginaSEI::getInstance()->recuperarCampo('chkSinSituacoesDesativadas')));

  $numRegistros = 0;

  $objControleUnidadeRN = new ControleUnidadeRN();

  $strTipo = PaginaSEI::getInstance()->recuperarCampo('hdnTipo');

  if ($strTipo=='P'){

    PaginaSEI::getInstance()->prepararOrdenacao($objAndamentoSituacaoDTO, 'IdProcedimento', InfraDTO::$TIPO_ORDENACAO_DESC);
    PaginaSEI::getInstance()->prepararPaginacao($objAndamentoSituacaoDTO);

    try {
      $arrObjAndamentoSituacaoDTO = $objControleUnidadeRN->gerar($objAndamentoSituacaoDTO);
    }catch(Exception $e){
      PaginaSEI::getInstance()->processarExcecao($e);
    }

    PaginaSEI::getInstance()->processarPaginacao($objAndamentoSituacaoDTO);

    $numRegistros = count($arrObjAndamentoSituacaoDTO);

    $bolAcaoAndamentoSituacaoGerenciar = SessaoSEI::getInstance()->verificarPermissao('andamento_situacao_gerenciar');

    if ($numRegistros >0){

      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

      $strResultado = '';

      $strSumarioTabela = 'Tabela de Processos.';
      $strCaptionTabela = 'Processos';

      $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
      $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
      $strResultado .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objAndamentoSituacaoDTO,'Processo','IdProcedimento',$arrObjAndamentoSituacaoDTO).'</th>'."\n";
      $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAndamentoSituacaoDTO,'Tipo','NomeTipoProcedimento',$arrObjAndamentoSituacaoDTO).'</th>'."\n";
      $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objAndamentoSituacaoDTO,'Ponto de Controle','NomeSituacao',$arrObjAndamentoSituacaoDTO).'</th>'."\n";
      $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objAndamentoSituacaoDTO,'Usuário','SiglaUsuario',$arrObjAndamentoSituacaoDTO).'</th>'."\n";
      $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objAndamentoSituacaoDTO,'Data/Hora','Execucao',$arrObjAndamentoSituacaoDTO).'</th>'."\n";
      $strResultado .= '<th class="infraTh" width="5%" >Ações</th>'."\n";

      $strResultado .= '</tr>'."\n";
      $strCssTr='';
      for($i = 0;$i < $numRegistros; $i++){

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        $strResultado .= '<td>'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjAndamentoSituacaoDTO[$i]->getDblIdProcedimento(),$arrObjAndamentoSituacaoDTO[$i]->getStrProtocoloFormatadoProtocolo()).'</td>';
        $strResultado .= '<td align="center"><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjAndamentoSituacaoDTO[$i]->getDblIdProcedimento()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.$arrObjAndamentoSituacaoDTO[$i]->getStrNomeTipoProcedimento().'" title="'.$arrObjAndamentoSituacaoDTO[$i]->getStrNomeTipoProcedimento().'" class="protocoloNormal">'.$arrObjAndamentoSituacaoDTO[$i]->getStrProtocoloFormatadoProtocolo().'</a></td>';
        $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAndamentoSituacaoDTO[$i]->getStrNomeTipoProcedimento()).'</td>';
        $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAndamentoSituacaoDTO[$i]->getStrNomeSituacao()).'</td>';
        $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjAndamentoSituacaoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjAndamentoSituacaoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjAndamentoSituacaoDTO[$i]->getStrSiglaUsuario()).'</a></td>';
        $strResultado .= '<td align="center">'.$arrObjAndamentoSituacaoDTO[$i]->getDthExecucao().'</a></td>';
        $strResultado .= '<td align="center">&nbsp;';

        if ($bolAcaoAndamentoSituacaoGerenciar) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_situacao_gerenciar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $arrObjAndamentoSituacaoDTO[$i]->getDblIdProcedimento()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' .Icone::SITUACAO .'" title="Gerenciar Ponto de Controle" alt="Gerenciar Ponto de Controle" class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '</td>'."\n";

        $strResultado .= '</tr>'."\n";
      }
      $strResultado .= '</table>';
    }
  }else if ($strTipo=='G'){


    try{
      $objAndamentoSituacaoDTORet = $objControleUnidadeRN->gerarGrafico($objAndamentoSituacaoDTO);
    }catch(Exception $e){
      PaginaSEI::getInstance()->processarExcecao($e);
    }

    $strResultadoGraficoGeral = '';
    $strResultadoGraficoPorSituacao = '';
    $numGrafico = 0;

    $objEstatisticasRN = new EstatisticasRN();

    $arrGraficoGeral = array();
    $numTotalGraficoGeral = 0;

    foreach ($objAndamentoSituacaoDTORet->getArrGraficoGeral() as $numIdSituacao => $arr) {

      $numTotal = $arr[0];
      $strNomeSituacao = $arr[1];

      $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_unidade_detalhar&id_controle_unidade=' . $objAndamentoSituacaoDTORet->getDblIdControleUnidade() . '&id_situacao=' . $numIdSituacao);
      $arrGraficoGeral[] = array($strNomeSituacao, $numTotal, $numTotal, $strLink);
      $numTotalGraficoGeral += $numTotal;
    }

    if ($numTotalGraficoGeral) {
      $strResultadoGraficoGeral .= '<div id="divGrf' . (++$numGrafico) . '" class="divAreaGrafico">';
      $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_unidade_detalhar&id_controle_unidade=' . $objAndamentoSituacaoDTORet->getDblIdControleUnidade());
      $strResultadoGraficoGeral .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico, 'Geral (' . $numTotalGraficoGeral . ')', $strLink, $arrGraficoGeral, 150, null);
      $strResultadoGraficoGeral .= '</div>';
    }else{
      $strResultadoGraficoGeral = '<span style="font-size: 1.2em">Nenhum registro encontrado.</span>';
    }

    $arrGraficoPorSituacao = array();
    $arrTotalGraficoPorSituacao = array();
    foreach($objAndamentoSituacaoDTORet->getArrGraficoPorSituacao() as $numIdSituacao => $arr1){

      $arrTotalGraficoPorSituacao[$numIdSituacao] = 0;

      foreach($arr1 as $numIdTipoProcedimento => $arr2){

        $numTotal = $arr2[0];
        $strNomeSituacao = $arr2[1];
        $strNomeTipoProcedimento = $arr2[2];

        $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_unidade_detalhar&id_controle_unidade='.$objAndamentoSituacaoDTORet->getDblIdControleUnidade().'&id_situacao='.$numIdSituacao.'&id_tipo_procedimento='.$numIdTipoProcedimento);
        $arrGraficoPorSituacao[$numIdSituacao.'#'.$strNomeSituacao][] = array($strNomeTipoProcedimento,$numTotal,$numTotal,$strLink);
        $arrTotalGraficoPorSituacao[$numIdSituacao] += $numTotal;
      }
    }

    foreach($arrGraficoPorSituacao as $strSituacao => $arrGraficoSituacao){
      $arrSituacao = explode('#',$strSituacao);
      $strResultadoGraficoPorSituacao .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
      $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_unidade_detalhar&id_controle_unidade='.$objAndamentoSituacaoDTORet->getDblIdControleUnidade().'&id_situacao='.$arrSituacao[0]);
      $strResultadoGraficoPorSituacao .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,$arrSituacao[1].' ('.$arrTotalGraficoPorSituacao[$arrSituacao[0]].')',$strLink, $arrGraficoSituacao, 150, null);
      $strResultadoGraficoPorSituacao .= '</div>';
    }

  }

  $strLinkAjaxSituacao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=situacao_montar_select_nome');
  $strItensSelTipoProcedimento = TipoProcedimentoINT::montarSelectNome('null','Todos', $objAndamentoSituacaoDTO->getNumIdTipoProcedimentoProcedimento());
  $strItensSelSituacao = SituacaoINT::montarSelectNomeCompleto('null', 'Todos', $objAndamentoSituacaoDTO->getNumIdSituacao(),$objAndamentoSituacaoDTO->getStrSinSituacoesDesativadas());

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
 
#lblTipoProcedimento {position:absolute;left:0%;top:2%;width:70%;}
#selTipoProcedimento {position:absolute;left:15%;top:0%;width:70%;}

#lblSituacao {position:absolute;left:0%;top:52%;width:50%;}
#selSituacao {position:absolute;left:15%;top:50%;width:50%;}

#divSinSituacoesDesativadas {position:absolute;left:66%;top:55%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
?>
<script type="text/javascript" src="/infra_js/raphaeljs/raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.bar-min.js"></script>
<?
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAjaxSituacao = null;

function inicializar(){

  objAjaxSituacao = new infraAjaxMontarSelect('selSituacao','<?=$strLinkAjaxSituacao?>');
  objAjaxSituacao.mostrarAviso = false;
  objAjaxSituacao.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','Todos','null') + '&sinInativos=' + (document.getElementById('chkSinSituacoesDesativadas').checked ? 'S' : 'N');
  }

  infraEfeitoTabelas();
}

function limpar() {
  document.getElementById('selTipoProcedimento').value = 'null';
  document.getElementById('selSituacao').value='null';
  document.getElementById('chkSinSituacoesDesativadas').checked = false;
}


function onSubmitForm(){
  infraExibirAviso(true);
  return true;
}

function processar(tipo, link){
  
  document.getElementById('hdnTipo').value = tipo;
  
  if (onSubmitForm()){
    document.getElementById('frmAcompanhamentoUnidade').action = link;
    document.getElementById('frmAcompanhamentoUnidade').submit();
  }
}

function abrirDetalhe(link){
 infraAbrirJanelaModal(link,850,550);
}


<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcompanhamentoUnidade"  method="post" onsubmit="return onSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('7em','style="overflow:visible;"');
  ?>
    <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelOpcional">Tipo do Processo:</label>
    <select id="selTipoProcedimento" name="selTipoProcedimento" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelTipoProcedimento?>
    </select>

    <label id="lblSituacao" for="selSituacao" accesskey="" class="infraLabelOpcional">Ponto de Controle:</label>
    <select id="selSituacao" name="selSituacao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelSituacao?>
    </select>

    <div id="divSinSituacoesDesativadas" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinSituacoesDesativadas" name="chkSinSituacoesDesativadas" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objAndamentoSituacaoDTO->getStrSinSituacoesDesativadas())?> onchange="objAjaxSituacao.executar()" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinSituacoesDesativadas" for="chkSinSituacoesDesativadas" accesskey="" class="infraLabelCheckbox" >Incluir desativados</label>
    </div>

    <input type="hidden" id="hdnTipo" name="hdnTipo" value="<?=$strTipo?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();

  if ($strTipo=='P'){

    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);

  }else if ($strTipo=='G'){

    if ($strResultadoGraficoGeral!='') {
      EstatisticasINT::montarGrafico('Geral', $strResultadoGraficoGeral, false);
    }

    if ($strResultadoGraficoPorSituacao!='') {
      EstatisticasINT::montarGrafico('Situacoes', $strResultadoGraficoPorSituacao, false);
    }

  }

  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>