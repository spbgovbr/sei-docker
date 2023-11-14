<?php
/*
 *
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/09/2012 - criado por mkr@trf4.jus.br
*
*/

class InfraCronograma
{
    private static $numTotalItens = 0;
    private static $instance = null;

    public function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new InfraCronograma();
        }
        return self::$instance;
    }

    //CALCULA POR QUANTO PRECISA DIVIDIR OU MULTIPLICAR OS RESULTADOS PARA TER O GRÁFICO DE BARRAS NO TAMANHO PADRÃO
    private function calcularFator($numDiferencaEntreMaiorEMenorData, $numComprimentoTabela)
    {
        //RETORNA O FATOR E A AÇÃO (DIVIDIR OU MULTIPLICAR)
        if ($numDiferencaEntreMaiorEMenorData < 365) {
            // só ajusta para tamanho de uma folha paisagem s emenor que um ano
            if ($numDiferencaEntreMaiorEMenorData > $numComprimentoTabela) {
                return array($numDiferencaEntreMaiorEMenorData / $numComprimentoTabela, "D");
            } else {
                return array($numComprimentoTabela / $numDiferencaEntreMaiorEMenorData, "M");
            }
        } else {
            return array(1, "D");
        }
    }

    //RETORNA O TOTAL DE ITENS DO GRÁFICO
    public function getNumTotalItens()
    {
        return self::$numTotalItens;
    }

    public function getArrCores()
    {
        return array(
            '#3399CC',
            '#999966',
            '#FFCC66',
            '#CC3333',
            '#CCCC99',
            '#99CCCC',
            '#666633',
            '#CC9933',
            '#996600',
            '#FF9900',
            '#FF6600',
            '#FF9966',
            '#CC6633',
            '#993300',
            '#FF9999',
            '#FFCCCC',
            '#CC9999',
            '#996666',
            '#0066CC',
            '#990000',
            '#808080',
            '#FFFF00',
            '#6666FF',
            '#990099',
            '#00FF80',
            '#FF00FF',
            '#FF9933',
            '#FCBA8C',
            '#808000',
            '#E6E6E6',
            '#CCCCCC',
            '#666666',
            '#66CCFF',
            '#006699',
            '#669999',
            '#336666',
            '#008000',
        );
    }

    public function gerarCronogramaSimples($arrDados, $varCores = null)
    {
        if ($varCores == null) {
            $varCores = InfraCronograma::getArrCores();
        }

        $numAlturaBarras = 50;

        $arrCoresUsadas = array();

        // calcular intervalo de datas
        $strMenorData = false;
        $strMaiorData = false;
        foreach ($arrDados as $i => $dado) {
            if (!$strMenorData) {
                $strMenorData = $dado['dataInicial'];
            } elseif (InfraData::compararDatasSimples($dado['dataInicial'], $strMenorData) > 0) {
                $strMenorData = $dado['dataInicial'];
            }

            if (!$strMaiorData) {
                $strMaiorData = $dado['dataFinal'];
            } elseif (InfraData::compararDatasSimples($dado['dataFinal'], $strMaiorData) < 0) {
                $strMaiorData = $dado['dataFinal'];
            }

            $arrDados[$i][1] = InfraData::compararDatas($dado['dataInicial'], $dado['dataFinal']);
        }

        $numDiferencaAlturaRotuloPontas = 30;
        $numEspacoNoTopoParaRotuloMeses = 70;
        $numTantoQueMarcacaoRotuloSaiPraForaDaPrimeiraBarra = 50;
        $numTamanhoUmaPaginaPaisagem = 1500;
        $numDiasAno = 365;
        $numEspacoFinalTabela = 60;
        $numAlturaTabela = count($arrDados) * 40;
        $numExtensaoTabela = $numTamanhoUmaPaginaPaisagem * (InfraData::compararDatas(
                    $strMenorData,
                    $strMaiorData
                ) / $numDiasAno);
        $numExtensaoUmMes = $numTamanhoUmaPaginaPaisagem / 12;
        $numTamanhoMedioMes = ($numDiasAno / 12);
        $numExtensaoDataBRPixels = 53;


        // SE FOR UM ANO, dá 1024 px, que é uma pagina em paisagem. Se for maior que um ano, a tabela vai ser multiplo de 1024, e na impressao cada ano tem que ficar em uma pagina0

        // alterar o que está em procentagem?

        //$strGrafico = '<div id="divGrafico" class="infraAreaDados" style="width: ' .$numExtensaoTabela .'px; height:' .(((count($arrDados))*$numAlturaBarras)+$numEspacoFinalTabela+$numDiferencaAlturaRotuloPontas).'px">';
        $strGrafico = '<div id="divGrafico" class="infraAreaDados" style="height:' . (((count(
                        $arrDados
                    )) * $numAlturaBarras) + $numEspacoFinalTabela + $numDiferencaAlturaRotuloPontas) . 'px">';
        $strGrafico .= '<div class="divAreaGrafico">';
        $strGrafico .= '<div style="left: 0px; top: 0px; position:absolute;">' . $strMenorData . '</div>';
        $strGrafico .= '<div style="left: ' . ($numExtensaoTabela - $numExtensaoDataBRPixels) . 'px; top: 0px; position:absolute;">' . $strMaiorData . '</div>';

        $arrFator = self::calcularFator(InfraData::compararDatas($strMenorData, $strMaiorData), 100);

        //$strDataInicialEscala = InfraData::calcularData(1,InfraData::$UNIDADE_MESES,InfraData::$SENTIDO_ADIANTE,$strMenorData);
        //$strDataFinalEscala = InfraData::calcularData(1,InfraData::$UNIDADE_MESES,InfraData::$SENTIDO_ATRAS,$strMaiorData);


        $arrMenorData = InfraData::decomporData($strMenorData);
        $arrMaiorData = InfraData::decomporData($strMaiorData);


        $strValorInicialEscala = $numExtensaoUmMes * ((InfraData::obterUltimoDiaMes(
                        $arrMenorData[InfraData::$MES],
                        $arrMenorData[InfraData::$ANO]
                    ) - $arrMenorData[InfraData::$DIA]) / $numTamanhoMedioMes);
        $strValorFinalEscala = $numExtensaoTabela + ($numExtensaoUmMes * (($arrMaiorData[InfraData::$DIA] - 1) / $numTamanhoMedioMes));

        foreach ($arrDados as $i => $dado) {
            $strGrafico .= '<div style="left: 0px; top: ' . (($numAlturaBarras * ($i)) + $numEspacoNoTopoParaRotuloMeses - $numTantoQueMarcacaoRotuloSaiPraForaDaPrimeiraBarra) . 'px; height: ' . ($i < (count(
                        $arrDados
                    ) - 1) ? $numAlturaBarras - 2 : $numAlturaBarras + $numTantoQueMarcacaoRotuloSaiPraForaDaPrimeiraBarra) . 'px; border-right: 2px; border-right-style: dotted; position:absolute; z-index:1;"></div>';
            $strGrafico .= '<div style="left: ' . ($numExtensaoTabela - 1) . 'px; top: ' . (($numAlturaBarras * ($i)) + $numEspacoNoTopoParaRotuloMeses - $numTantoQueMarcacaoRotuloSaiPraForaDaPrimeiraBarra) . 'px; height: ' . ($i < (count(
                        $arrDados
                    ) - 1) ? $numAlturaBarras - 2 : $numAlturaBarras + $numTantoQueMarcacaoRotuloSaiPraForaDaPrimeiraBarra) . 'px; border-right: 2px; border-right-style: dotted; position:absolute; z-index:1;"></div>';

            $strValorAtualEscala = $strValorInicialEscala;
            $strDataAtualEscala = InfraData::calcularData(
                1,
                InfraData::$UNIDADE_MESES,
                InfraData::$SENTIDO_ADIANTE,
                $strMenorData
            );
            $arrDataAtualEscala = InfraData::decomporData($strDataAtualEscala);

            while ($strValorAtualEscala < $strValorFinalEscala) {
                if ($arrFator[1] == "D") {
                    $strValorAtualEscala = $strValorAtualEscala / $arrFator[0];
                } else {
                    $strValorAtualEscala = $strValorAtualEscala * $arrFator[0];
                }

                $strGrafico .= '<div style="left: ' . (($strValorAtualEscala) - (0.008 * $numExtensaoTabela)) . 'px; top: ' . $numDiferencaAlturaRotuloPontas . 'px; position:absolute;">' . InfraData::obterMesSiglaBR(
                        $arrDataAtualEscala[InfraData::$MES]
                    ) . '/' . $arrDataAtualEscala[InfraData::$ANO] . '</div>';
                $strGrafico .= '<div style="left: ' . ($strValorAtualEscala) . 'px; top: ' . (($numAlturaBarras * ($i)) + $numEspacoNoTopoParaRotuloMeses + $numDiferencaAlturaRotuloPontas - $numTantoQueMarcacaoRotuloSaiPraForaDaPrimeiraBarra) . 'px; height: ' . ($i < (count(
                            $arrDados
                        ) - 1) ? $numAlturaBarras - 2 : $numAlturaBarras + $numTantoQueMarcacaoRotuloSaiPraForaDaPrimeiraBarra - $numDiferencaAlturaRotuloPontas) . 'px; border-right: 1px; border-right-style: dashed; position:absolute; z-index:1"></div>';

                $strValorAtualEscala += $numExtensaoUmMes;
                $strDataAtualEscala = InfraData::calcularData(
                    1,
                    InfraData::$UNIDADE_MESES,
                    InfraData::$SENTIDO_ADIANTE,
                    $strDataAtualEscala
                );
                $arrDataAtualEscala = InfraData::decomporData($strDataAtualEscala);
            }

            if ($arrFator[1] == "D") {
                if ($strMenorData == $dado['dataInicial']) {
                    $numInicioBarra = 0;
                } else {
                    $numInicioBarra = ($numExtensaoUmMes * (InfraData::compararDatas(
                                    $strMenorData,
                                    $dado['dataInicial']
                                ) / $numTamanhoMedioMes)) / $arrFator[0];
                }
                $numTamanhoBarra = $dado[1] / $arrFator[0];
            } else {
                if ($strMenorData == $dado['dataInicial']) {
                    $numInicioBarra = 0;
                } else {
                    $numInicioBarra = ($numExtensaoUmMes * (InfraData::compararDatas(
                                    $strMenorData,
                                    $dado['dataInicial']
                                ) / $numTamanhoMedioMes)) * $arrFator[0];
                }
                $numTamanhoBarra = $dado[1] * $arrFator[0];
            }


            if (!is_array($varCores)) {
                $strCor = $varCores;
            } else {
                $strCor = $varCores[$i];
                if (!in_array($i, $arrCoresUsadas)) {
                    $arrCoresUsadas[] = $i;
                }
            }
            $strGrafico .= '<a href="javascript:void(0);" ';
            if (isset($dado[2]) && $dado[2] != null) {
                $strGrafico .= 'onclick="abrirDetalhe(\'' . $dado[2] . '\');" ';
            }

            $strGrafico .= 'class="linkFuncionalidade" style="border:0; text-decoration: none;">' . "\n";
            $strGrafico .= '<div onmouseover="return infraTooltipMostrar(\'' . $dado['dataInicial'] . ' a ' . $dado['dataFinal'] . ' (' . InfraData::formatarTimestamp(
                    $dado[1] * 86400
                ) . ') ' . $dado[2] . '\',null,200)"  onmouseout="return infraTooltipOcultar();" 
      style="left:' . ($numInicioBarra) . 'px; top:' . ((($i) * $numAlturaBarras) + $numEspacoNoTopoParaRotuloMeses) . 'px; position:absolute; height:' . ($numAlturaBarras - 1) . 'px; width:' . $numExtensaoUmMes * ($numTamanhoBarra / $numTamanhoMedioMes) . 'px; background-color:' . $strCor . ';font-weight:600;">' . "\n";
            $strGrafico .= $arrDados[$i][0] . '</div>' . "\n";
            $strGrafico .= '</a>' . "\n";
        }

        $strGrafico .= '<br />' . "\n";

        /* if (is_array($varCores)){
           foreach($arrCoresUsadas as $i => $corUsada){
             if (isset($varCores[$corUsada])){
               $strGrafico .= '<div style="position:absolute; float:left; top: '.(count($arrDados)+1)*25 .'px ;width:8%;height:8px;background-color:'.$varCores[$corUsada].';border:1px solid black">'.$arrDados[$i][0].'</div>';

             }
           }
         }*/
        $strGrafico .= '</div>';
        $strGrafico .= '</div>';

        return $strGrafico;
    }

}

