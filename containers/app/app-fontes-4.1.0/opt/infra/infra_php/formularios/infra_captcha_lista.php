<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/04/2021 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.0
 */

try {
    //require_once dirname(__FILE__).'/Infra.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoInfra::getInstance()->validarLink();

    PaginaInfra::getInstance()->prepararSelecao('infra_captcha_selecionar');

    SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

    PaginaInfra::getInstance()->salvarCamposPost(array('selAno', 'selIdentificacao', 'selStaEscala'));

    PaginaInfra::getInstance()->salvarCampo('chkSinMostrarGrafico', $_POST['chkSinMostrarGrafico']);

    switch ($_GET['acao']) {
        case 'infra_captcha_listar':
            $strTitulo = 'Acessos Captcha';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos = array();
    if ($_GET['acao'] == 'infra_captcha_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    $objInfraCaptchaDTO = new InfraCaptchaDTO();
    $objInfraCaptchaDTO->retNumDia();
    $objInfraCaptchaDTO->retNumMes();
    $objInfraCaptchaDTO->retNumAno();
    $objInfraCaptchaDTO->retStrIdentificacao();
    $objInfraCaptchaDTO->retDblAcertos();
    $objInfraCaptchaDTO->retDblErros();

    $chkSinMostrarGrafico = PaginaInfra::getInstance()->getCheckbox(
        PaginaInfra::getInstance()->recuperarCampo('chkSinMostrarGrafico', 'N')
    );

    $numAno = PaginaInfra::getInstance()->recuperarCampo('selAno');
    if ($numAno != '') {
        $objInfraCaptchaDTO->setNumAno($numAno);
    }

    $strIdentificacao = PaginaInfra::getInstance()->recuperarCampo('selIdentificacao');
    if ($strIdentificacao != '') {
        $objInfraCaptchaDTO->setStrIdentificacao($strIdentificacao);
    }

    $objInfraCaptchaDTO->setOrdNumAno(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objInfraCaptchaDTO->setOrdNumMes(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objInfraCaptchaDTO->setOrdNumDia(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objInfraCaptchaDTO->setOrdStrIdentificacao(InfraDTO::$TIPO_ORDENACAO_ASC);

    //PaginaInfra::getInstance()->prepararPaginacao($objInfraCaptchaDTO, 100);

    $objInfraCaptchaRN = new InfraCaptchaRN();
    $arrObjInfraCaptchaDTO = $objInfraCaptchaRN->listar($objInfraCaptchaDTO);

    $strStaEscala = PaginaInfra::getInstance()->recuperarCampo('selStaEscala', InfraCaptchaINT::$ESCALA_ANUAL);

    if (!$objInfraCaptchaDTO->isSetNumAno()) {
        if ($strStaEscala == InfraCaptchaINT::$ESCALA_DIARIO) {
            $strStaEscala = InfraCaptchaINT::$ESCALA_ANUAL;
        }
    } else {
        if ($strStaEscala == InfraCaptchaINT::$ESCALA_ANUAL) {
            $strStaEscala = InfraCaptchaINT::$ESCALA_MENSAL;
        }
    }

    if ($strStaEscala == InfraCaptchaINT::$ESCALA_ANUAL) {
        $arrTemp = array();
        foreach ($arrObjInfraCaptchaDTO as $dto) {
            $strChave = $dto->getNumAno() . '_' . $dto->getStrIdentificacao();
            if (!isset($arrTemp[$strChave])) {
                $dto->setStrData($dto->getNumAno());
                $arrTemp[$strChave] = $dto;
            } else {
                $dtoTemp = $arrTemp[$strChave];
                $dtoTemp->setDblAcertos($dtoTemp->getDblAcertos() + $dto->getDblAcertos());
                $dtoTemp->setDblErros($dtoTemp->getDblErros() + $dto->getDblErros());
            }
        }
        $arrObjInfraCaptchaDTO = array_values($arrTemp);
    } elseif ($strStaEscala == InfraCaptchaINT::$ESCALA_MENSAL) {
        $arrTemp = array();
        foreach ($arrObjInfraCaptchaDTO as $dto) {
            $strChave = $dto->getNumAno() . '_' . $dto->getNumMes() . '_' . $dto->getStrIdentificacao();
            if (!isset($arrTemp[$strChave])) {
                $dto->setStrData(str_pad($dto->getNumMes(), 2, '0', STR_PAD_LEFT) . '/' . $dto->getNumAno());
                $arrTemp[$strChave] = $dto;
            } else {
                $dtoTemp = $arrTemp[$strChave];
                $dtoTemp->setDblAcertos($dtoTemp->getDblAcertos() + $dto->getDblAcertos());
                $dtoTemp->setDblErros($dtoTemp->getDblErros() + $dto->getDblErros());
            }
        }
        $arrObjInfraCaptchaDTO = array_values($arrTemp);
    } else {
        foreach ($arrObjInfraCaptchaDTO as $dto) {
            $dto->setStrData(
                str_pad($dto->getNumDia(), 2, '0', STR_PAD_LEFT) . '/' . str_pad(
                    $dto->getNumMes(),
                    2,
                    '0',
                    STR_PAD_LEFT
                ) . '/' . $dto->getNumAno()
            );
        }
    }


    //PaginaInfra::getInstance()->processarPaginacao($objInfraCaptchaDTO);
    $numRegistros = count($arrObjInfraCaptchaDTO);
    $dblTotalAcertos = 0;
    $dblTotalErros = 0;
    if ($numRegistros > 0) {
        $strResultado = '';

        $strSumarioTabela = 'Tabela de Acessos Captcha.';
        $strCaptionTabela = 'Acessos Captcha';

        $strResultado .= '<table width="99%" class="infraTable" style="display:none;" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaInfra::getInstance()->gerarCaptionTabela(
                $strCaptionTabela,
                $numRegistros
            ) . '</caption>';
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh">Data</th>' . "\n";
        $strResultado .= '<th class="infraTh">Identificação</th>' . "\n";
        $strResultado .= '<th class="infraTh">Acertos</th>' . "\n";
        $strResultado .= '<th class="infraTh">Erros</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {
            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            $strResultado .= $strCssTr;
//str_pad($_SESSION['INFRA_DEBUG'][self::$POS_CONTADOR], 5, '0', STR_PAD_LEFT)
            $strResultado .= '<td align="center">' . InfraPagina::tratarHTML(
                    $arrObjInfraCaptchaDTO[$i]->getStrData()
                ) . '</td>';
            $strResultado .= '<td align="center">' . InfraPagina::tratarHTML(
                    $arrObjInfraCaptchaDTO[$i]->getStrIdentificacao()
                ) . '</td>';
            $strResultado .= '<td align="center">' . InfraUtil::formatarMilhares(
                    $arrObjInfraCaptchaDTO[$i]->getDblAcertos()
                ) . '</td>';
            $strResultado .= '<td align="center">' . InfraUtil::formatarMilhares(
                    $arrObjInfraCaptchaDTO[$i]->getDblErros()
                ) . '</td>';
            $strResultado .= '</tr>' . "\n";

            $dblTotalAcertos += $arrObjInfraCaptchaDTO[$i]->getDblAcertos();
            $dblTotalErros += $arrObjInfraCaptchaDTO[$i]->getDblErros();
        }

        $strResultado .= '<tr class="infraTrClara">';
        $strResultado .= '<td colspan="2" align="right"><b>TOTAL:</b></td>';
        $strResultado .= '<td align="center">' . InfraUtil::formatarMilhares($dblTotalAcertos) . '</td>';
        $strResultado .= '<td align="center">' . InfraUtil::formatarMilhares($dblTotalErros) . '</td>';
        $strResultado .= '</tr>' . "\n";

        $strResultado .= '</table>';
    }

    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoInfra::getInstance(
        )->assinarLink(
            'controlador.php?acao=' . PaginaInfra::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']
        ) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

    $strSelectAnos = InfraCaptchaINT::montarSelectAnos($numAno);
    $strSelectAcoes = InfraCaptchaINT::montarSelectIdentificacao($strIdentificacao);
    $strSelectStaEscala = InfraCaptchaINT::montarSelectStaEscala($strStaEscala, $objInfraCaptchaDTO);
    $arrFormatoData = array(
        InfraCaptchaINT::$ESCALA_ANUAL => '%Y',
        InfraCaptchaINT::$ESCALA_MENSAL => '%m/%Y',
        InfraCaptchaINT::$ESCALA_DIARIO => '%d/%m/%Y'
    );
} catch (Exception $e) {
    PaginaInfra::getInstance()->processarExcecao($e);
}

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>
<? if (0){ ?>
    <style><?}?>

        #lblSelAno {
            position: absolute;
            left: 0%;
            top: 0%;
        }

        #selAno {
            position: absolute;
            left: 0%;
            top: 40%;
            width: 10%;
        }

        #lblSelStaEscala {
            position: absolute;
            left: 15%;
            top: 0%;
        }

        #selStaEscala {
            position: absolute;
            left: 15%;
            top: 40%;
            width: 15%;
        }

        #lblSelIdentificacao {
            position: absolute;
            left: 35%;
            top: 0%;
        }

        #selIdentificacao {
            position: absolute;
            left: 35%;
            top: 40%;
            width: 30%;
        }

        #divSinMostrarGrafico {
            position: absolute;
            left: 67%;
            top: 47%;
        }

        path {
            stroke-width: 2;
            fill: none;
        }

        path.acertos {
            stroke: steelblue;
        }

        path.erros {
            stroke: #ee1d1d;
        }

        .axis path,
        .axis line {
            fill: none;
            stroke: grey;
            stroke-width: 1;
            shape-rendering: crispEdges;
        }

        <? if (0){ ?></style><?
} ?>
<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->adicionarJavaScript(
    PaginaInfra::getInstance()->getDiretorioJavaScriptGlobal() . '/D3/d3.v3.min.js'
);
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>
<? if (0){ ?>
    <script type="text/javascript"><?}?>

        function inicializar() {
            document.getElementById('btnFechar').focus();
            infraEfeitoTabelas(true);
        }

        function atualizarVisualizacao() {
            var grafico = document.querySelector('#divInfraAreaTabela svg');
            var tabela = document.querySelector('table');
            if (document.getElementById('chkSinMostrarGrafico').checked) {
                grafico.style.display = 'block';
                tabela.style.display = 'none';
            } else {
                grafico.style.display = 'none';
                tabela.style.display = '';
            }
        }

        $(function () {
            var data = Array.from(document.querySelectorAll('#frmInfraCaptchaLista tr.infraTrClara,#frmInfraCaptchaLista tr.infraTrEscura'))
                .map(function (row) {
                    return row.children.length === 4 ? {
                        data: row.children[0].innerText,
                        id: row.children[1].innerText,
                        acertos: Number(row.children[2].innerText.replace('.', '')),
                        erros: Number(row.children[3].innerText.replace('.', ''))
                    } : null;
                }).filter(function (row) {
                    return !!row;
                })

            data = data.reduce(function (rv, x) {
                (rv[x.data] = rv[x.data] || {acertos: 0, erros: 0, data: x.data}).acertos += x.acertos;
                (rv[x.data] = rv[x.data] || {acertos: 0, erros: 0, data: x.data}).erros += x.erros;
                return rv;
            }, {});
            data = Object.entries(data).map(function (entry) {
                return {data: entry[1].data, acertos: entry[1].acertos, erros: entry[1].erros}
            });

            var margin = {top: 30, right: 20, bottom: 30, left: 60},
                width = (infraClientWidth() * 0.775) - margin.left - margin.right,
                height = (width * 3 / 8) - margin.top - margin.bottom;

            var parseDate = d3.time.format("<?= $arrFormatoData[$strStaEscala]?>").parse;

            var x = d3.time.scale().range([0, width]);
            var y = d3.scale.linear().range([height, 0]);

            var xAxis = d3.svg.axis().scale(x)
                .orient("bottom").ticks(5);

            var yAxis = d3.svg.axis().scale(y)
                .orient("left").ticks(5);

            var svg = d3.select("#divInfraAreaTabela")
                .append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .style("display", "none")
                .style("margin", "auto")
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

            atualizarVisualizacao();

            data.forEach(function (d) {
                d.data = parseDate(d.data);
            });

            x.domain(d3.extent(data, function (d) {
                return d.data;
            }));
            y.domain([0, Math.max(d3.max(data, function (d) {
                return d.acertos;
            }), d3.max(data, function (d) {
                return d.erros;
            }))]);

            svg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);

            svg.append("g")
                .attr("class", "y axis")
                .call(yAxis);

            if (data.length > 1) {
                // Define the line
                var valueline = d3.svg.line()
                    .x(function (d) {
                        return x(d.data);
                    })
                    .y(function (d) {
                        return y(d.acertos);
                    });
                var valueline2 = d3.svg.line()
                    .x(function (d) {
                        return x(d.data);
                    })
                    .y(function (d) {
                        return y(d.erros);
                    });
                svg.append("path")
                    .attr("class", "line acertos")
                    .attr("d", valueline(data));
                svg.append("path")
                    .attr("class", "line erros")
                    .attr("d", valueline2(data));
            } else {
                svg.selectAll(".acertos")
                    .data(data)
                    .enter().append("circle")
                    .attr("class", "acertos")
                    .attr("r", 3.5)
                    .attr("cx", function (d) {
                        return x(d.data) + 10;
                    })
                    .attr("cy", function (d) {
                        return y(d.acertos);
                    })
                    .style("fill", function (d) {
                        return 'steelblue';
                    });
                svg.selectAll(".erros")
                    .data(data)
                    .enter().append("circle")
                    .attr("class", "erros")
                    .attr("r", 3.5)
                    .attr("cx", function (d) {
                        return x(d.data) + 10;
                    })
                    .attr("cy", function (d) {
                        return y(d.erros);
                    })
                    .style("fill", function (d) {
                        return '#ee1d1d';
                    })

            }
        });

        <? if (0){ ?></script><?
} ?>
<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmInfraCaptchaLista" method="post" action="<?= SessaoInfra::getInstance()->assinarLink(
        'controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']
    ) ?>">
        <?
        PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaInfra::getInstance()->abrirAreaDados('5em');
        ?>
        <label id="lblSelAno" for="selAno" accesskey="" class="infraLabelObrigatorio">Ano:</label>
        <select id="selAno" name="selAno" class="infraSelect" onchange="this.form.submit();"
                tabindex="<?= PaginaInfra::getInstance()->getProxTabDados() ?>">
            <?= $strSelectAnos ?>
        </select>

        <label id="lblSelStaEscala" for="selStaEscala" accesskey="" class="infraLabelObrigatorio">Escala:</label>
        <select id="selStaEscala" name="selStaEscala" class="infraSelect" onchange="this.form.submit()"
                tabindex="<?= PaginaInfra::getInstance()->getProxTabDados() ?>">
            <?= $strSelectStaEscala ?>
        </select>

        <label id="lblSelIdentificacao" for="selIdentificacao" accesskey=""
               class="infraLabelOpcional">Identificação:</label>
        <select id="selIdentificacao" name="selIdentificacao" class="infraSelect" onchange="this.form.submit()"
                tabindex="<?= PaginaInfra::getInstance()->getProxTabDados() ?>">
            <?= $strSelectAcoes ?>
        </select>

        <div id="divSinMostrarGrafico" class="infraDivCheckbox">
            <input type="checkbox" id="chkSinMostrarGrafico" name="chkSinMostrarGrafico" class="infraCheckbox"
                   onchange="this.form.submit();" <?= PaginaInfra::getInstance()->setCheckbox($chkSinMostrarGrafico) ?>
                   tabindex="<?= PaginaInfra::getInstance()->getProxTabDados() ?>"/>
            <label id="lblSinMostrarGrafico" for="chkSinMostrarGrafico" accesskey="" class="infraLabelCheckbox">Mostrar
                gráfico</label>
        </div>

        <?
        PaginaInfra::getInstance()->fecharAreaDados();
        echo '<br>';
        PaginaInfra::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        //PaginaInfra::getInstance()->montarAreaDebug();
        PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
