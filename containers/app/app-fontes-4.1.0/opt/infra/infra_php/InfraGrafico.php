<?php
/*
*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/05/2008 - criado por cle@trf4.gov.br
*
*/

require_once dirname(__FILE__) . '/baachart/baaChart.php';

class InfraGrafico
{
    private static $numTotalItens = 0;
    private static $instance = null;

    public function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new InfraGrafico();
        }
        return self::$instance;
    }

    //CALCULA POR QUANTO PRECISA DIVIDIR OU MULTIPLICAR OS RESULTADOS PARA TER O GRÁFICO DE BARRAS NO TAMANHO PADRÃO
    private function calcularFator($arrResultado, $numAlturaGrafico)
    {
        $numMaiorItem = 1;
        $numResultados = count($arrResultado);
        for ($i = 0; $i < $numResultados; $i++) {
            if ($arrResultado[$i][1] > $numMaiorItem) {
                $numMaiorItem = $arrResultado[$i][1];
            }
            //A COLUNA 2 SÓ EXISTE PARA GERAÇÃO DE BARRAS DUPLAS
            if (isset($arrResultado[$i][1])) {
                if ($arrResultado[$i][2] > $numMaiorItem) {
                    $numMaiorItem = $arrResultado[$i][2];
                }
            }
        }
        //RETORNA O FATOR E A AÇÃO (DIVIDIR OU MULTIPLICAR)
        if ($numMaiorItem > $numAlturaGrafico) {
            return array($numMaiorItem / $numAlturaGrafico, "D");
        } else {
            return array($numAlturaGrafico / $numMaiorItem, "M");
        }
    }

    //RETORNA O TOTAL DE ITENS DO GRÁFICO
    public function getNumTotalItens()
    {
        return self::$numTotalItens;
    }

    //MONTA O HTML DO GRÁFICO DE BARRAS SIMPLES
    public function gerarGraficoBarrasSimples(
        $arrResultado,
        $numAlturaGrafico,
        $strImagem = "/infra_css/imagens/barra_area_tabela.gif",
        $bolEcho = true,
        $strIdTabela = ''
    ) {
        $numTotal = 0;
        $arrFator = self::calcularFator($arrResultado, $numAlturaGrafico);
        $strGrafico = "<table id=\"" . $strIdTabela . "\" cellpadding=\"1\" cellspacing=\"0\" style=\"border:1px solid #000000;\" align=\"center\">" .
            "<tr><td width=\"4\"></td>";
        $numResultados = count($arrResultado);

        for ($i = 0; $i < $numResultados; $i++) {
            $numTotal += $arrResultado[$i][1];
            if ($arrFator[1] == "D") {
                $numTamanhoBarra = $arrResultado[$i][1] / $arrFator[0];
            } else {
                $numTamanhoBarra = $arrResultado[$i][1] * $arrFator[0];
            }
            $strGrafico .= "<td align=\"center\" valign=\"bottom\">" . $arrResultado[$i][1] . "<br/>" .
                "<img src=\"" . $strImagem . "\" width=\"20\" height=\"" . (int)$numTamanhoBarra . "\"/></td>" .
                "<td width=\"4\"></td>";
        }
        $strGrafico .= "</tr><tr bgcolor=\"#f0f0f0\"><td bgcolor=\"#ffffff\" width=\"4\"></td>";

        for ($i = 0; $i < $numResultados; $i++) {
            $strGrafico .= "<td align=\"center\" valign=\"top\">" . $arrResultado[$i][0] . "</td>" .
                "<td bgcolor=\"#ffffff\" width=\"4\"></td>";
        }
        $strGrafico .= "</tr></table>";
        self::$numTotalItens = $numTotal;

        if ($bolEcho) {
            echo $strGrafico;
        } else {
            return $strGrafico;
        }
    }

    //MONTA O HTML DO GRÁFICO DE BARRAS SIMPLES HORIZONTAL
    public function gerarGraficoBarrasSimplesHorizontal(
        $arrResultado,
        $numLarguraGrafico,
        $strImagem = "/infra_css/imagens/barra_horizontal_area_tabela.png",
        $bolEcho = true,
        $strIdTabela = ''
    ) {
        $numTotal = 0;
        $arrFator = self::calcularFator($arrResultado, $numLarguraGrafico);
        $strGrafico = "<table id=\"" . $strIdTabela . "\" cellpadding=\"1\" cellspacing=\"0\" style=\"border:1px solid #000000;\" align=\"center\">" .
            "<tr></tr>";
        $numResultados = count($arrResultado);

        for ($i = 0; $i < $numResultados; $i++) {
            $numTotal += $arrResultado[$i][1];
            if ($arrFator[1] == "D") {
                $numTamanhoBarra = $arrResultado[$i][1] / $arrFator[0];
            } else {
                $numTamanhoBarra = $arrResultado[$i][1] * $arrFator[0];
            }
            $strGrafico .= "<tr>";
            $strGrafico .= "<td align=\"right\" valign=\"bottom\" bgcolor=\"#f0f0f0\">" . $arrResultado[$i][0] . "</td>";
            $strGrafico .= "<td align=\"left\" valign=\"bottom\">" .
                "<img src=\"" . $strImagem . "\" height=\"20\" width=\"" . (int)$numTamanhoBarra . "\"/>" .
                $arrResultado[$i][1] . "</td>";
            $strGrafico .= "</tr>";
            $strGrafico .= "<tr><td></td><td></td></tr>";
        }
        $strGrafico .= "</table>";
        self::$numTotalItens = $numTotal;

        if ($bolEcho) {
            echo $strGrafico;
        } else {
            return $strGrafico;
        }
    }

    //MONTA O HTML DO GRÁFICO DE BARRAS DUPLAS
    public function gerarGraficoBarrasDuplas(
        $arrResultado,
        $numAlturaGrafico,
        $strImagem1 = "/infra_css/imagens/bg_grafico_t.gif",
        $strImagem2 = "/infra_css/imagens/bg_grafico_c.gif",
        $bolEcho = true,
        $valor1 = null,
        $valor2 = null
    ) {
        $numTotal = 0;
        $arrFator = self::calcularFator($arrResultado, $numAlturaGrafico);

        $strGrafico = "<table cellpadding=\"1\" cellspacing=\"0\" align=\"center\" style=\"border:1px solid #ACACAC;\">" .
            "<tr><td width=\"4\"></td>";

        $numResultados = count($arrResultado);
        for ($i = 0; $i < $numResultados; $i++) {
            $numTotal += $arrResultado[$i][1] + $arrResultado[$i][2];
            if ($arrFator[1] == "D") {
                $numTamanhoBarra1 = $arrResultado[$i][1] / $arrFator[0];
                $numTamanhoBarra2 = $arrResultado[$i][2] / $arrFator[0];
            } else {
                $numTamanhoBarra1 = $arrResultado[$i][1] * $arrFator[0];
                $numTamanhoBarra2 = $arrResultado[$i][2] * $arrFator[0];
            }
            $strGrafico .= '<td align="center" valign="bottom">' .
                '<img onmouseover="return infraTooltipMostrar(\'' . $arrResultado[$i][1] . '\',\'\',150);" onmouseout="return infraTooltipOcultar();" src="' . $strImagem1 . '" width="10" height="' . (int)$numTamanhoBarra1 . '"/></td>' .
                '<td align="center" valign="bottom">' .
                '<img onmouseover="return infraTooltipMostrar(\'' . $arrResultado[$i][2] . '\',\'\',150);" onmouseout="return infraTooltipOcultar();" src="' . $strImagem2 . '" width="10" height="' . (int)$numTamanhoBarra2 . '"/></td>' .
                '<td width="4"></td>';
        }
        $strGrafico .= "</tr><tr bgcolor=\"#f0f0f0\"><td bgcolor=\"#ffffff\" width=\"4\"></td>";
        for ($i = 0; $i < $numResultados; $i++) {
            $strGrafico .= "<td align=\"center\" valign=\"top\" colspan=\"2\">" . $arrResultado[$i][0] . "</td>" .
                "<td bgcolor=\"#ffffff\" width=\"4\"></td>";
        }
        $strGrafico .= "</tr></table>";
        if ($valor1 != null && $valor2 != null) {
            $strGrafico .= '<br />';
            $strGrafico .= "<table cellpadding=\"1\" cellspacing=\"0\" align=\"center\"><tr>";
            $strGrafico .= "<td><img src='" . $strImagem1 . "' style=\"width:10px\" /> " . $valor1 . " &nbsp;&nbsp;&nbsp; <img src='" . $strImagem2 . "' style=\"width:10px\" /> " . $valor2 . "</td>";
            $strGrafico .= "</tr></table>";
            $strGrafico .= '<br />';
            $strGrafico .= '<br />';
        }

        self::$numTotalItens = $numTotal;

        if ($bolEcho) {
            echo $strGrafico;
        } else {
            return $strGrafico;
        }
    }

    //MONTA O HTML DO GRÁFICO DE PIZZA
    public function gerarGraficoPizza($arrResultado, $bolEcho = true)
    {
        $strResultado = '';

        $strResultado .= "<div id=\"DIVGrafico\" style=\"position:relative;height:300px;width:450px;border:1px solid;\">";
        $strResultado .= "<script type=\"text/javascript\" src=\"/infra_js/wz_jsgraphics.js\"></script>";
        $strResultado .= "<script type=\"text/javascript\" src=\"/infra_js/pie.js\"></script>";
        $strJS = "<script>var p = new pie();";
        $numTotal = 0;
        $numResultados = count($arrResultado);
        for ($i = 0; $i < $numResultados; $i++) {
            $numTotal += $arrResultado[$i][1];
            $strJS .= "p.add(\"" . $arrResultado[$i][0] . "\"," . $arrResultado[$i][1] . ");";
        }
        $strJS .= "p.render(\"DIVGrafico\", \"\")</script></div>";
        $strResultado .= $strJS;
        self::$numTotalItens = $numTotal;

        if ($bolEcho) {
            echo $strResultado;
        } else {
            return $strResultado;
        }
    }

    //MONTA O HTML DO GRÁFICO DE LINHAS

    /**
     * Monta o HTML do gráfico de linhas
     * @param array $arrResultado Array bidimensional com os valores a serem plotados no gráfico. A 1ª dimensão contém o nome da linha como índice; já a 2ª contém o nome da coluna como índice e o valor respectivo. É o único parâmetro obrigatório.
     * @param string $tituloGrafico
     * @param string $subTituloGrafico
     * @param string $nomeEixoX
     * @param string $nomeEixoY
     * @param int $numLarguraGrafico
     * @param int $numAlturaGrafico
     * @param string $diretorioArquivoSalvo Nome do diretório da imagem do gráfico a ser salvo caso o $bolEcho seja false.
     * @param bool $bolEcho Se true, simplesmente manda o HTML do gráfico para a tela. Se false, salva o gráfico como uma imagem PNG no $diretorioArquivoSalvo
     *
     * Exemplo de uso:
     *
     * $arrResultado["Magistrado A"]["JAN"] = 25;
     * $arrResultado["Magistrado A"]["FEV"] = 15;
     * $arrResultado["Magistrado A"]["MAR"] = 25;
     * $arrResultado["Magistrado A"]["ABR"] = 30;
     *
     * $arrResultado["Magistrado B"]["JAN"] = 65;
     * $arrResultado["Magistrado B"]["FEV"] = 20;
     * $arrResultado["Magistrado B"]["MAR"] = 10;
     * $arrResultado["Magistrado B"]["ABR"] = 50;
     *
     * $arrResultado["Magistrado C"]["JAN"] = 40;
     * $arrResultado["Magistrado C"]["FEV"] = 30;
     * $arrResultado["Magistrado C"]["MAR"] = 40;
     * $arrResultado["Magistrado C"]["ABR"] = 20;
     *
     * echo '<img src="'.InfraGrafico::getInstance()->gerarGraficoLinhas($arrResultadoGraficoLinhas, 'Sentenças Proferidas por Magistrado', 'Janeiro a Abril', 'Mês', 'Sentenças Proferidas',770,'','diretorio_de_imagens').'" title="Sentenças Proferidas por Magistrado" />';
     */
    public function gerarGraficoLinhas(
        $arrResultado,
        $tituloGrafico = '',
        $subTituloGrafico = '',
        $nomeEixoX = '',
        $nomeEixoY = '',
        $numLarguraGrafico = '',
        $numAlturaGrafico = '',
        $diretorioArquivoSalvo = '',
        $bolEcho = false
    ) {
        $numTotal = 0;

        if (empty($numLarguraGrafico) && empty($numAlturaGrafico)) {
            $numLarguraGrafico = 770; // largura default
        } elseif (empty($numLarguraGrafico)) {
            $numLarguraGrafico = $numAlturaGrafico * 1.616; // largura baseada na altura informada
        }

        if (empty($numAlturaGrafico)) {
            $graficoLinhas = new baaChart($numLarguraGrafico);
        } else {
            $graficoLinhas = new baaChart($numLarguraGrafico, $numAlturaGrafico);
        }

        $graficoLinhas->setTitle($tituloGrafico, $subTituloGrafico);
        $graficoLinhas->setXAxis($nomeEixoX, 1);
        $graficoLinhas->setBgColor(255, 255, 255, 1); //Transparent

        $primeiraLinha = true;
        $min = 0;
        $max = 0;
        $ultimoTipoLinha = LINE_MARK_NONE;

        foreach ($arrResultado as $nomeLinha => $arrValor) {
            $strValoresLinha = '';

            if ($primeiraLinha) {
                $strColunas = '';

                foreach ($arrValor as $nomeColuna => $valor) {
                    $strColunas .= $nomeColuna . ',';
                    $strValoresLinha .= $valor . ',';
                    $numTotal += $valor;

                    if ($valor > $max) {
                        $max = $valor;
                    }
                }

                $graficoLinhas->setXLabels(substr($strColunas, 0, strlen($strColunas) - 1));
            } else {
                foreach ($arrValor as $valor) {
                    $strValoresLinha .= $valor . ',';
                    $numTotal += $valor;

                    if ($valor > $max) {
                        $max = $valor;
                    }
                }
            }

            switch ($ultimoTipoLinha) {
                case LINE_MARK_NONE:
                    $ultimoTipoLinha = LINE_MARK_X;
                    break;
                case LINE_MARK_X:
                    $ultimoTipoLinha = LINE_MARK_SQUARE;
                    break;
                case LINE_MARK_SQUARE:
                    $ultimoTipoLinha = LINE_MARK_DIAMOND;
                    break;
                case LINE_MARK_DIAMOND:
                    $ultimoTipoLinha = LINE_MARK_PLUS;
                    break;
                case LINE_MARK_PLUS:
                    $ultimoTipoLinha = LINE_MARK_NONE;
                    break;
            }

            $graficoLinhas->addDataSeries(
                'L',
                $ultimoTipoLinha,
                substr($strValoresLinha, 0, strlen($strValoresLinha) - 1),
                $nomeLinha
            );
        }

        // as cinco primeiras linhas, por default, são: vermelho, azul, verde, roxo, laranja
        $graficoLinhas->setSeriesColor(6, 255, 255, 0); // amarelo
        $graficoLinhas->setSeriesColor(7, 168, 168, 168); // cinza
        $graficoLinhas->setSeriesColor(8, 255, 105, 180); // rosa
        $graficoLinhas->setSeriesColor(9, 245, 222, 179); // trigo
        $graficoLinhas->setSeriesColor(10, 92, 51, 23); // Baker's chocolate

        $divisao = (int)($max / 20);
        if ($divisao < 3) {
            $divisao = 3;
        }
        $max = ((int)($max / $divisao) + 1) * $divisao;

        $graficoLinhas->setYAxis($nomeEixoY, $min, $max, $divisao, 0);

        self::$numTotalItens = $numTotal;

        if ($bolEcho) {
            $graficoLinhas->drawGraph();
        } else {
            $nomeArquivoSalvo = $diretorioArquivoSalvo . '/' . session_id() . '_' . date("Ymd_His") . '_' . rand(
                ) . '.png';
            $graficoLinhas->drawGraph($nomeArquivoSalvo);
            return $nomeArquivoSalvo;
        }
    }
}

