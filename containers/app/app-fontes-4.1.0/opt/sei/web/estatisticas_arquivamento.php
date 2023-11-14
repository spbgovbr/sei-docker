<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/09/2010 - criado por jonatas_db
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  
	//PaginaSEI::getInstance()->setBolAutoRedimensionar(false);
  //////////////////////////////////////////////////////////////////////////////
  
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();

  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $arrComandos = array();
  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  
  $strTitulo = 'Estatísticas de Arquivamento da Unidade';
  
  $bolAcervo= $_POST['hdnAcervo']=='acervo';
  switch($_GET['acao']) {

    case 'gerar_estatisticas_arquivamento':

      if (isset($_POST['txtPeriodoDe']) && isset($_POST['txtPeriodoA'])) {

        $objEstatisticasArquivamentoDTO = new EstatisticasArquivamentoDTO();

        $dtaPeriodoDe = $_POST['txtPeriodoDe'];
        $objEstatisticasArquivamentoDTO->setDtaInicio($dtaPeriodoDe);

        $dtaPeriodoA = $_POST['txtPeriodoA'];
        $objEstatisticasArquivamentoDTO->setDtaFim($dtaPeriodoA.' 23:59:59');
        $strParametros='&de='.$dtaPeriodoDe.'&ate='.$dtaPeriodoA;

        try {

          $objEstatisticasRN = new EstatisticasRN();
          if ($bolAcervo){
            $objEstatisticasArquivamentoDTOret = $objEstatisticasRN->gerarArquivamentoAcervo($objEstatisticasArquivamentoDTO);
          } else {
            $objEstatisticasArquivamentoDTOret = $objEstatisticasRN->gerarArquivamento($objEstatisticasArquivamentoDTO);
          }


        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }

        if ($objEstatisticasArquivamentoDTOret != null) {
          $arrCores = $objEstatisticasRN->getArrCores();
          $numCores = count($arrCores);
          $numCorAtual = 0;
          $arrCoresTipoProcedimento = $arrCores[0];
        }
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }


  if ($objEstatisticasArquivamentoDTOret != null) {


    //Tipos de Documento
    $objSerieDTO = new SerieDTO();
    $objSerieDTO->setBolExclusaoLogica(false);
    $objSerieDTO->retNumIdSerie();
    $objSerieDTO->retStrNome();

    $objSerieRN = new SerieRN();
    $arrObjSerieDTO = InfraArray::indexarArrInfraDTO($objSerieRN->listarRN0646($objSerieDTO), 'IdSerie');

    //Tipos de Localizadores
    $objTipoLocalizadorDTO = new TipoLocalizadorDTO();
    $objTipoLocalizadorDTO->setBolExclusaoLogica(false);
    $objTipoLocalizadorDTO->retNumIdTipoLocalizador();
    $objTipoLocalizadorDTO->retStrNome();

    $objTipoLocalizadorRN = new TipoLocalizadorRN();
    $arrObjTipoLocalizadorDTO = InfraArray::indexarArrInfraDTO($objTipoLocalizadorRN->listarRN0610($objTipoLocalizadorDTO), 'IdTipoLocalizador');


    $bolAcaoImprimir = false;

    if ($bolAcaoImprimir) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirDiv(\'divTabelas\');" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }


    $arrRotuloEixoX = array();
    $numTamanhoTabela = 55;
    if (!$bolAcervo) {
      // Arquivados Tipo X Mes
      $mesInicial = substr($objEstatisticasArquivamentoDTO->getDtaInicio(), 3, 2);
      $anoInicial = substr($objEstatisticasArquivamentoDTO->getDtaInicio(), 6, 4);
      $mesFinal = substr($objEstatisticasArquivamentoDTO->getDtaFim(), 3, 2);
      $anoFinal = substr($objEstatisticasArquivamentoDTO->getDtaFim(), 6, 4);

      $numMeses = ($anoFinal * 12 + $mesFinal - 1) - ($anoInicial * 12 + $mesInicial - 1) + 1;
      $numTamanhoColuna = floor(75 / ($numMeses + 1));
      $numTamanhoTabela = floor(($numMeses + 1) * 100 / 13);
      if ($numTamanhoTabela > 100) $numTamanhoTabela = 100;

      if ($numTamanhoTabela < 55) {
        $numTamanhoTabela = 55;
      }

      $colSpanAnoInicial = 0;
      $colSpanAnoFinal = 0;

      if ($anoFinal - $anoInicial > 0) {
        $colSpanAnoInicial = 13 - $mesInicial;
        $colSpanAnoFinal = $mesFinal;
      } else {
        $colSpanAnoInicial = $mesFinal - $mesInicial + 1;
      }

      if ($colSpanAnoInicial) {
        $strHeaderMesAno = '<th class="infraTh" colspan="' . $colSpanAnoInicial . '">' . $anoInicial . '</th>'. "\n";
      }
      for ($i = $anoInicial + 1; $i < $anoFinal; $i++) {
        $strHeaderMesAno .= '<th class="infraTh" colspan="12">' . $i . '</th>'. "\n";
      }
      if ($colSpanAnoFinal) {
        $strHeaderMesAno .= '<th class="infraTh" colspan="' . ($colSpanAnoFinal) . '">' . $anoFinal . '</th>'. "\n";
      }
      $strHeaderMesAno .= '<th class="infraTh" rowspan="2">Total</th>' . "\n";
      if ($numMeses>1) $strHeaderMesAno .= '<th class="infraTh" rowspan="2" width="5%">Ações</th>' . "\n";
      $strHeaderMesAno .= '</tr>'. "\n";
      $strHeaderMesAno .= '<tr>'. "\n";

      $mes = $mesInicial;
      $ano = $anoInicial;
      $n = 0;
      while ($n < $numMeses) {
        $strHeaderMesAno .= '<th class="infraTh">' . InfraData::obterMesSiglaBR($mes) . '</th>'. "\n";
        $arrRotuloEixoX[] = InfraData::obterMesSiglaBR($mes) . '/' . $ano;
        $mes++;
        if ($mes == 13) {
          $mes = 1;
          $ano++;
        }
        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $n++;
      }
      $strHeaderMesAno .= '</tr>' . "\n";

    }

    $numGrafico=0;

    $arrRecebidos = $objEstatisticasArquivamentoDTOret->getArrRecebidos();
    if (InfraArray::contar($arrRecebidos)>0) {
      $numGrafico++;
      $strTituloRecebidos = 'Documentos recebidos';
      ArquivamentoINT::montarTabelaEstatisticasArquivamento($arrRecebidos,
                                                            $arrObjSerieDTO,
                                                            $bolAcervo,
                                                            $mesInicial,
                                                            $anoInicial,
                                                            $numMeses,
                                                            $numGrafico,
                                                            $numTamanhoTabela,
                                                            $numTamanhoColuna,
                                                            $strHeaderMesAno,
                                                            $strParametros,
                                                            $strTituloRecebidos,
                                                            'recebidos',
                                                            $arrJsRecebidos,
                                                            $strResultadoRecebidos,
                                                            $contadorTabelaRecebidos,
                                                            $arrayGraficoRecebidos);
    }

    $arrArquivados = $objEstatisticasArquivamentoDTOret->getArrArquivados();
    if (InfraArray::contar($arrArquivados)>0) {
      $numGrafico++;
      $strTituloArquivados = 'Documentos arquivados';
      ArquivamentoINT::montarTabelaEstatisticasArquivamento($arrArquivados,
                                                            $arrObjSerieDTO,
                                                            $bolAcervo,
                                                            $mesInicial,
                                                            $anoInicial,
                                                            $numMeses,
                                                            $numGrafico,
                                                            $numTamanhoTabela,
                                                            $numTamanhoColuna,
                                                            $strHeaderMesAno,
                                                            $strParametros,
                                                            $strTituloArquivados,
                                                            'arquivados',
                                                            $arrJsArquivados,
                                                            $strResultadoArquivados,
                                                            $contadorTabelaArquivados,
                                                            $arrayGraficoArquivados);
    }


    $arrDesarquivados = $objEstatisticasArquivamentoDTOret->getArrDesarquivados();
    if (InfraArray::contar($arrDesarquivados)>0) {
      $numGrafico++;
      $strTituloDesarquivados = 'Documentos desarquivados';
      ArquivamentoINT::montarTabelaEstatisticasArquivamento($arrDesarquivados,
                                                            $arrObjSerieDTO,
                                                            $bolAcervo,
                                                            $mesInicial,
                                                            $anoInicial,
                                                            $numMeses,
                                                            $numGrafico,
                                                            $numTamanhoTabela,
                                                            $numTamanhoColuna,
                                                            $strHeaderMesAno,
                                                            $strParametros,
                                                            $strTituloDesarquivados,
                                                            'desarquivados',
                                                            $arrJsDesarquivados,
                                                            $strResultadoDesarquivados,
                                                            $contadorTabelaDesarquivados,
                                                            $arrayGraficoDesarquivados);
    }


    $arrEliminadosFisicos = $objEstatisticasArquivamentoDTOret->getArrEliminadosFisicos();
    if (InfraArray::contar($arrEliminadosFisicos)>0) {
      $numGrafico++;
      $strTituloEliminadosFisicos = 'Documentos físicos eliminados';
      ArquivamentoINT::montarTabelaEstatisticasArquivamento($arrEliminadosFisicos,
                                                            $arrObjSerieDTO,
                                                            $bolAcervo,
                                                            $mesInicial,
                                                            $anoInicial,
                                                            $numMeses,
                                                            $numGrafico,
                                                            $numTamanhoTabela,
                                                            $numTamanhoColuna,
                                                            $strHeaderMesAno,
                                                            $strParametros,
                                                            $strTituloEliminadosFisicos,
                                                            'eliminados_fisicos',
                                                            $arrJsEliminadosFisicos,
                                                            $strResultadoEliminadosFisicos,
                                                            $contadorTabelaEliminadosFisicos,
                                                            $arrayGraficoEliminadosFisicos);
    }


    //----------- Localizadores utilizados no período
    $arrLocalizadores = $objEstatisticasArquivamentoDTOret->getArrLocalizadores();
    $contadorTabelaLocalizadores = 0;
    $strCssTr = '';
    $totalAbertos = 0;
    $totalFechados = 0;
    $total = 0;
    $strResultadoTabelaLocalizadores = '';
    foreach ($arrLocalizadores as $keyTipoLocalizador => $arrStaEstado) {

      if ($arrObjTipoLocalizadorDTO[$keyTipoLocalizador] != null) {
        $strNomeTipoLocalizador = $arrObjTipoLocalizadorDTO[$keyTipoLocalizador]->getStrNome();
      } else {
        $strNomeTipoLocalizador = '';
      }

      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultadoTabelaLocalizadores .= $strCssTr;
      $strResultadoTabelaLocalizadores .= '<td align="left">' . PaginaSEI::tratarHTML($strNomeTipoLocalizador) . '</td>';

      $totalTipo = 0;


      $strResultadoTabelaLocalizadores .= '<td align="center">'. "\n";
      if (isset($arrStaEstado[LocalizadorRN::$EA_ABERTO])) {
        $strResultadoTabelaLocalizadores .= '<a href="javascript:void(0);" onclick="abrirDetalhe(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_arquivamento&tipo=localizadores&id_tipo_localizador=' . $keyTipoLocalizador . '&sta_estado=' . LocalizadorRN::$EA_ABERTO . $strParametros) . '\');" class="ancoraPadraoAzul">' . $arrStaEstado[LocalizadorRN::$EA_ABERTO] . '</a>';
        $totalTipo += $arrStaEstado[LocalizadorRN::$EA_ABERTO];
        $totalAbertos += $arrStaEstado[LocalizadorRN::$EA_ABERTO];
      } else {
        $strResultadoTabelaLocalizadores .= '&nbsp;';
      }
      $strResultadoTabelaLocalizadores .= '</td>'. "\n";

      $strResultadoTabelaLocalizadores .= '<td align="center">'. "\n";
      if (isset($arrStaEstado[LocalizadorRN::$EA_FECHADO])) {
        $strResultadoTabelaLocalizadores .= '<a href="javascript:void(0);" onclick="abrirDetalhe(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_arquivamento&tipo=localizadores&id_tipo_localizador=' . $keyTipoLocalizador . '&sta_estado=' . LocalizadorRN::$EA_FECHADO . $strParametros) . '\');" class="ancoraPadraoAzul">' . $arrStaEstado[LocalizadorRN::$EA_FECHADO] . '</a>';
        $totalTipo += $arrStaEstado[LocalizadorRN::$EA_FECHADO];
        $totalFechados += $arrStaEstado[LocalizadorRN::$EA_FECHADO];
      } else {
        $strResultadoTabelaLocalizadores .= '&nbsp;';
      }
      $strResultadoTabelaLocalizadores .= '</td>'. "\n";

      $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_arquivamento&tipo=localizadores&id_tipo_localizador=' . $keyTipoLocalizador . $strParametros);

      $strResultadoTabelaLocalizadores .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\'' . $strLink . '\');" class="ancoraPadraoAzul">' . InfraUtil::formatarMilhares($totalTipo) . '</a></td>'. "\n";
      $strResultadoTabelaLocalizadores .= '</tr>'. "\n";

      $arrayGraficoLocalizadores[] = array($strNomeTipoLocalizador, InfraUtil::formatarMilhares($totalTipo), $totalTipo, $strLink);

      $contadorTabelaLocalizadores++;
    }
    $strResultadoLocalzadores = '';
    $strResultadoLocalzadores .= '<table width="55%" class="infraTable" summary="Tabela de Localizadores utilizados">' . "\n";
    $strResultadoLocalzadores .= '<caption class="infraCaption">Localizadores utilizados:</caption>';
    $strResultadoLocalzadores .= '<thead><tr>'. "\n";
    $strResultadoLocalzadores .= '<th width="70%" class="infraTh">Tipo de Localizador</th>' . "\n";
    $strResultadoLocalzadores .= '<th width="10%" class="infraTh">Abertos</th>' . "\n";
    $strResultadoLocalzadores .= '<th width="10%" class="infraTh">Fechados</th>' . "\n";
    $strResultadoLocalzadores .= '<th class="infraTh"></th>' . "\n";
    $strResultadoLocalzadores .= '</tr></thead><tbody>';
    $strResultadoLocalzadores .= $strResultadoTabelaLocalizadores;
    $strResultadoLocalzadores .= '<tr>'. "\n";
    $strResultadoLocalzadores .= '<td align="right" class="totalEstatisticas"><b>TOTAL:</b></td>'. "\n";

    if ($totalAbertos>0){
      $strResultadoLocalzadores .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_arquivamento&tipo=localizadores' . '&sta_estado=' . LocalizadorRN::$EA_ABERTO . $strParametros) . '\');" class="ancoraPadraoAzul">' . InfraUtil::formatarMilhares($totalAbertos) . '</a></td>';
    } else {
      $strResultadoLocalzadores .= '<td align="center" class="totalEstatisticas"></td>'. "\n";
    }
    if ($totalFechados>0) {
      $strResultadoLocalzadores .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_arquivamento&tipo=localizadores' . '&sta_estado=' . LocalizadorRN::$EA_FECHADO . $strParametros) . '\');" class="ancoraPadraoAzul">' . InfraUtil::formatarMilhares($totalFechados) . '</a></td>';
    } else {
      $strResultadoLocalzadores .= '<td align="center" class="totalEstatisticas"></td>'. "\n";
    }
    if (($totalAbertos+$totalFechados)>0) {
      $strResultadoLocalzadores .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_arquivamento&tipo=localizadores' . $strParametros) . '\');" class="ancoraPadraoAzul">' . InfraUtil::formatarMilhares($totalAbertos + $totalFechados) . '</a></td>';
    } else {
      $strResultadoLocalzadores .= '<td align="center" class="totalEstatisticas"></td>'. "\n";
    }

    $strResultadoLocalzadores .= '</tr></tbody>'. "\n";
    $strResultadoLocalzadores .= '</table>'. "\n";

  }

  $numGrafico=0;
  $objEstatisticasRN = new EstatisticasRN();

  $strResultadoGraficoRecebidos = '';
  if ($contadorTabelaRecebidos > 0){
    $strResultadoGraficoRecebidos .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
    if ($bolAcervo) {
      $strResultadoGraficoRecebidos .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,$strTituloRecebidos,null,$arrayGraficoRecebidos, 150);
    }
    $strResultadoGraficoRecebidos .= '</div>';
  }

  $strResultadoGraficoArquivados = '';
  if ($contadorTabelaArquivados > 0){
    $strResultadoGraficoArquivados .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
    if ($bolAcervo) {
      $strResultadoGraficoArquivados .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,$strTituloArquivados,null,$arrayGraficoArquivados, 150);
    }
    $strResultadoGraficoArquivados .= '</div>';
  }

  $strResultadoGraficoDesarquivados = '';
  if ($contadorTabelaDesarquivados > 0){
    $strResultadoGraficoDesarquivados .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
    if ($bolAcervo) {
      $strResultadoGraficoDesarquivados .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,$strTituloDesarquivados,null,$arrayGraficoDesarquivados, 150);
    }
    $strResultadoGraficoDesarquivados .= '</div>';
  }

  $strResultadoGraficoEliminadosFisicos = '';
  if ($contadorTabelaEliminadosFisicos > 0){
    $strResultadoGraficoEliminadosFisicos .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
    if ($bolAcervo) {
      $strResultadoGraficoEliminadosFisicos .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,$strTituloEliminadosFisicos,null,$arrayGraficoEliminadosFisicos, 150);
    }
    $strResultadoGraficoEliminadosFisicos .= '</div>';
  }

  $strResultadoGraficoLocalizadores = '';
  if ($contadorTabelaLocalizadores > 0){
    $strResultadoGraficoLocalizadores .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
    $strResultadoGraficoLocalizadores .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,'Localizadores utilizados',null,$arrayGraficoLocalizadores, 150);
    $strResultadoGraficoLocalizadores .= '</div>';
  }



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
.divAreaGrafico DIV { margin:0px; }

#lblPeriodoDe {position:absolute;left:0%;top:55%;width:9%;}
#txtPeriodoDe {position:absolute;left:7%;top:50%;width:9%;}
#imgCalPeriodoD {position:absolute;left:16.5%;top:55%;}

#lblPeriodoA 	{position:relative;left:19%;top:55%;width:9%;}
#txtPeriodoA 	{position:absolute;left:20.5%;top:50%;width:9%;}
#imgCalPeriodoA {position:absolute;left:30%;top:55%;}

#ancAcervo {position:absolute;left:35%;top:55%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
?>

<script type="text/javascript" src="/infra_js/raphaeljs/raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.bar-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.line-min.js"></script>
<?
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

  function labelBarChart(r, bc, labels, attrs) {
    // Label a bar chart bc that is part of a Raphael object r
    // Labels is an array of strings. Attrs is a dictionary
    // that provides attributes such as fill (text color)
    // and font (text font, font-size, font-weight, etc) for the
    // label text.

    for (var i = 0; i< bc.bars[0].length; i++) {
        var bar = bc.bars[0][i];
        var gutter_y = bar.w * 0.4;
        var label_x = bar.x;
        var label_y = bar.y  - gutter_y;
        var label_text = bar.value;
        var label_attr = { fill:  "#2f69bf", font: "11px sans-serif" };

        r.text(label_x, label_y, label_text).attr(label_attr);
    }

}

var r,lines;
var arrGraficos = [];
  <?
  if (!$bolAcervo){
  ?>
  var arrEixo =<?=json_encode($arrRotuloEixoX);?>;

  <?

    if (InfraArray::contar($arrRecebidos) > 0) {
      echo 'arrGraficos.push(' . json_encode($arrJsRecebidos) . ');' . "\n";
    }

    if (InfraArray::contar($arrArquivados) > 0) {
      echo 'arrGraficos.push(' . json_encode($arrJsArquivados) . ');' . "\n";
    }

    if (InfraArray::contar($arrDesarquivados) > 0) {
      echo 'arrGraficos.push(' . json_encode($arrJsDesarquivados) . ');' . "\n";
    }

    if (InfraArray::contar($arrEliminadosFisicos) > 0) {
      echo 'arrGraficos.push(' . json_encode($arrJsEliminadosFisicos) . ');' . "\n";
    }

  }
  ?>
  var grafico=[];

  function atualizarGrafico(link){
    infraAbrirJanelaModal(link,830,600);
  }


  function inicializar(){

  if ('<?=$_GET['acao_origem']?>'!='gerar_estatisticas_unidade' && '<?=$_GET['acao_origem']?>'!='gerar_estatisticas_ouvidoria'){
    infraOcultarMenuSistemaEsquema();
  }

  infraAdicionarEvento(window,'resize',seiRedimensionarGraficos);
  infraProcessarResize();
  infraEfeitoTabelas();
  seiRedimensionarGraficos();
  $('[id^="btnOcultar"]').click();
}

function abrirDetalhe(link){
 infraAbrirJanelaModal(link,750,550);
}
function verAcervo(){
  var hdn=$('#hdnAcervo');
  hdn.val('acervo');
  $('#txtPeriodoDe').val('');
  $('#txtPeriodoA').val('');
  $('#frmEstatisticas').attr('onsubmit','').submit();
  hdn.val('');

}
function validarFormulario(){
  if ($('#hdnAcervo').val()=='acervo'){
    return true;
  }

	if(infraTrim(document.getElementById('txtPeriodoDe').value) == "" || infraTrim(document.getElementById('txtPeriodoA').value)=="") {
		
		alert("Informe o período de datas.");
	  
	  if(infraTrim(document.getElementById('txtPeriodoDe').value) == ""){
	    document.getElementById('txtPeriodoDe').focus();
	  }else{
	    document.getElementById('txtPeriodoA').focus();
	  }
		
		return false;
	}
	
	infraExibirAviso();
	return true;
}



//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEstatisticas" onsubmit="return validarFormulario();" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>

    <label id="lblPeriodoDe" for="txtPeriodoDe" accesskey="" class="infraLabelObrigatorio">Período:</label>
    <input type="text" id="txtPeriodoDe" name="txtPeriodoDe" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaPeriodoDe)?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
		<img id="imgCalPeriodoD" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtPeriodoDe',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
    <label id="lblPeriodoA" for="txtPeriodoA" accesskey="" class="infraLabelObrigatorio">a</label>
    <input type="text" id="txtPeriodoA" name="txtPeriodoA" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaPeriodoA)?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
    <img id="imgCalPeriodoA" title="Selecionar Data Final" alt="Selecionar Data Final" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtPeriodoA',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <a id="ancAcervo" href="javascript:void(0);" onclick="verAcervo();" class="ancoraPadraoPreta" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Ver acervo completo</a>
    <input type="hidden" id="hdnAcervo" name="hdnAcervo" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  
  ?>
  <div id="divTabelas">
  <?

  echo '<div id="divSeparador" style="float:left;padding:1em"></div>';

  if ($contadorTabelaRecebidos > 0) {
    echo '<br /><br />';
    PaginaSEI::getInstance()->montarAreaTabela($strResultadoRecebidos,$contadorTabelaRecebidos);
    if($bolAcervo) EstatisticasINT::montarGrafico('Recebidos',$strResultadoGraficoRecebidos);
  }

  if ($contadorTabelaArquivados > 0) {
  	echo '<br /><br />';
		PaginaSEI::getInstance()->montarAreaTabela($strResultadoArquivados,$contadorTabelaArquivados);
    if($bolAcervo) EstatisticasINT::montarGrafico('Arquivados',$strResultadoGraficoArquivados);
  }

  if ($contadorTabelaDesarquivados > 0) {
    echo '<br /><br />';
    PaginaSEI::getInstance()->montarAreaTabela($strResultadoDesarquivados,$contadorTabelaDesarquivados);
    if($bolAcervo) EstatisticasINT::montarGrafico('Desarquivados',$strResultadoGraficoDesarquivados);
  }

  if ($contadorTabelaEliminadosFisicos > 0) {
    echo '<br /><br />';
    PaginaSEI::getInstance()->montarAreaTabela($strResultadoEliminadosFisicos,$contadorTabelaEliminadosFisicos);
    if($bolAcervo) EstatisticasINT::montarGrafico('EliminadosFisicos',$strResultadoGraficoEliminadosFisicos);
  }

  if (InfraArray::contar($arrLocalizadores) > 0) {
  	echo '<br /><br />';
		PaginaSEI::getInstance()->montarAreaTabela($strResultadoLocalzadores,InfraArray::contar($arrLocalizadores));
		EstatisticasINT::montarGrafico('Localizadores',$strResultadoGraficoLocalizadores);
  }

  ?>
  </div>
  <?
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>