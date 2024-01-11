<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/04/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class ArquivamentoINT extends InfraINT {

  public static function montarTabelaEstatisticasArquivamento($arrDados, $arrObjSerieDTO, $bolAcervo, $mesInicial, $anoInicial, $numMeses, $numGrafico, $numTamanhoTabela, $numTamanhoColuna, $strHeaderMesAno, $strParametros, $strTitulo, $strTipo, &$arrJs, &$strResultado, &$numContadorTabela, &$arrayGrafico){

    $strCssTr = '';
    $numContadorTabela = 0;
    $total = 0;
    $contadorMes = array();
    $idArrayGrafico = 0;
    $arrJs = array();
    $arrJs['titulo'] = utf8_encode(PaginaSEI::tratarHTML($strTitulo));
    $arrJs['dados'] = array();
    $arrJs['maximos'] = array();
    $arrJs['rotulos'] = array();

    $strResultadoTabela = '';
    foreach ($arrDados as $keySerie => $arrMeses) {

      if ($arrObjSerieDTO[$keySerie] != null) {
        $strNomeSerie = $arrObjSerieDTO[$keySerie]->getStrNome();
      } else {
        $strNomeSerie = '';
      }

      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultadoTabela .= $strCssTr;
      $strResultadoTabela .= '<td align="left">' . PaginaSEI::tratarHTML($strNomeSerie) . '</td>';
      $arrJs['rotulos'][] = utf8_encode(PaginaSEI::tratarHTML($strNomeSerie));
      $arrDadosTemp = array();
      if (!$bolAcervo) {
        $mes = $mesInicial;
        $ano = $anoInicial;
        $n = 0;
        $maximo = 0;

        $totalTipo = 0;

        while ($n < $numMeses) {

          $strResultadoTabela .= '<td align="center" width="' . $numTamanhoColuna . '%">';

          if (isset($arrMeses[$ano][$mes])) {

            $strResultadoTabela .= '<a href="javascript:void(0);" onclick="abrirDetalhe(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_arquivamento&tipo='.$strTipo.'&id_serie=' . $keySerie . '&ano=' . $ano . '&mes=' . substr($mes, 0, 2) . $strParametros) . '\');" class="ancoraPadraoAzul">' . $arrMeses[$ano][$mes] . '</a>';

            $valor = $arrMeses[$ano][$mes];
            if ($valor > $maximo) {
              $maximo = $valor;
            }
            $total += $valor;
            $totalTipo += $valor;

            if (!isset($contadorMes[$ano])){
              $contadorMes[$ano] = array();
            }

            if (!isset($contadorMes[$ano][$mes])){
              $contadorMes[$ano][$mes] = 0;
            }

            $contadorMes[$ano][$mes] += $valor;
            $arrDadosTemp[] = $valor;

          } else {
            $strResultadoTabela .= '&nbsp;';
            $arrDadosTemp[] = 0;
          }

          $strResultadoTabela .= '</td>';

          $mes++;
          if ($mes == 13) {
            $mes = 1;
            $ano++;
          }
          $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);

          $n++;
        }
        $arrJs['dados'][] = $arrDadosTemp;
        $arrJs['maximos'][] = $maximo;
      } else {
        $totalTipo = $arrMeses;
        $total += $totalTipo;
      }

      $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_arquivamento&tipo='.$strTipo.'&id_serie=' . $keySerie . $strParametros);

      $strResultadoTabela .= '<td align="center" width="' . $numTamanhoColuna . '%" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\'' . $strLink . '\');" class="ancoraPadraoAzul">' . InfraUtil::formatarMilhares($totalTipo) . '</a></td>';
      if (!$bolAcervo && $numMeses>1) {
        $strLinkGrafico=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_grafico_exibir&num_grafico='.$numGrafico.'&num_linha='.$idArrayGrafico++);
        $strResultadoTabela .= '<td align="center"><a href="#divGrf'.$numGrafico.'" onclick="atualizarGrafico(\''.$strLinkGrafico.'\');"><img src="'.Icone::ARQUIVO_GRAFICO.'" title="Gráficos" alt="Gráficos" class="infraImg"></a></td>';
      }
      $strResultadoTabela .= '</tr>';

      $strTituloGrafico = $bolAcervo?$strTitulo:$strTitulo.' no período';
      $arrayGrafico[] = array($strNomeSerie, InfraUtil::formatarMilhares($totalTipo), $totalTipo, $strLink);

      $numContadorTabela++;


    }
    $strResultado = '';
    $strResultado .= '<table id="tbl'.$strTipo.'" width="' . $numTamanhoTabela . '%" class="infraTable" summary="Tabela de ' . $strTituloGrafico . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . $strTituloGrafico . ':</caption>';
    $strResultado .= '<thead><tr>';
    if ($bolAcervo) {
      $strResultado .= '<th class="infraTh" width="70%">Tipo do Documento</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="30%">Quantidade</th>' . "\n";
    } else {
      $strResultado .= '<th class="infraTh" rowspan="2" width="20%">Tipo do Documento</th>' . "\n";
      $strResultado .= $strHeaderMesAno;
    }
    $strResultado .= '</thead><tbody>';
    $strResultado .= $strResultadoTabela;
    $strResultado .= '<tr>';
    $strResultado .= '<td align="right" class="totalEstatisticas"><b>TOTAL:</b></td>';

    $mes = $mesInicial;
    $ano = $anoInicial;
    $n = 0;
    $maximo = 0;
    $arrDadosTemp = array();
    $arrJs['rotulos'][] = 'Total';
    while ($n < $numMeses) {
      $strResultado .= '<td align="center" class="totalEstatisticas">';

      if (isset($contadorMes[$ano][$mes])){
        $valor = $contadorMes[$ano][$mes];
      }else{
        $valor = 0;
      }

      if ($valor>0){
        $strResultado .= '<a href="javascript:void(0);" onclick="abrirDetalhe(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_arquivamento&tipo='.$strTipo.'&ano=' . $ano . '&mes=' . substr($mes, 0, 2) . $strParametros) . '\');" class="ancoraPadraoAzul">' . InfraUtil::formatarMilhares($valor) . '</a>';
      }
      $strResultado .='</td>';
      if ($valor > $maximo) {
        $maximo = $valor;
      }
      $arrDadosTemp[] = $valor == null ? 0 : $valor;
      $mes++;
      if ($mes == 13) {
        $mes = 1;
        $ano++;
      }
      $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
      $n++;
    }
    $arrJs['dados'][] = $arrDadosTemp;
    $arrJs['maximos'][] = $maximo;
    $strResultado .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_arquivamento&tipo='.$strTipo . $strParametros) . '\');" class="ancoraPadraoAzul">' . InfraUtil::formatarMilhares($total) . '</a></td>';
    if (!$bolAcervo && $numMeses>1) {
      $strLinkGrafico=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_grafico_exibir&num_grafico='.$numGrafico.'&num_linha='.$idArrayGrafico++);
      $strResultado .= '<td align="center" class="totalEstatisticas"><a href="#divGrf'.$numGrafico.'" onclick="atualizarGrafico(\''.$strLinkGrafico.'\');"><img src="'.Icone::ARQUIVO_GRAFICO.'" title="Gráficos" alt="Gráficos" class="infraImg"></a></td>';
    }
    $strResultado .= '</tr></tbody>';
    $strResultado .= '</table>';
  }

  public static function montarSelectTiposProcedimentoParaEliminacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objArquivamentoRN = new ArquivamentoRN();
    $arrObjTipoProcedimentoDTO = $objArquivamentoRN->listarTiposProcedimentoParaEliminacao();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoProcedimentoDTO, 'IdTipoProcedimento', 'Nome');
  }

  public static function montarSelectSeriesParaEliminacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objArquivamentoRN = new ArquivamentoRN();
    $arrObjSerieDTO = $objArquivamentoRN->listarSeriesParaEliminacao();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSerieDTO, 'IdSerie', 'Nome');
  }

  public static function montarSelectLocalizadoresParaEliminacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objArquivamentoRN = new ArquivamentoRN();
    $arrObjLocalizadorDTO = $objArquivamentoRN->listarLocalizadoresParaEliminacao();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjLocalizadorDTO, 'IdLocalizador', 'Identificacao');
  }
}
?>