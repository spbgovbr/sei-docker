function infraTabelaDinamica(idTabela, idHidden, bolAlterar, bolRemover, bolOrdenar, bolFormatarXml) {

    var me = this;
    this.tbl = infraGetElementById(idTabela);
    this.hdn = infraGetElementById(idHidden);
    this.ths = this.tbl.getElementsByTagName("th");
    this.alterar = null;
    this.remover = null;
    this.flagRemover = bolRemover;
    this.flagAlterar = bolAlterar;
    this.flagOrdenar = bolOrdenar;
    this.flagFormatarXml = bolFormatarXml;
    this.gerarEfeitoTabela = false;
    this.colunaAcoes = 0;
    this.exibirMensagens = false;
    this.corTr = '';
    this.inserirNoInicio = true;
    this.ready = false;
    this.dirImg = INFRA_PATH_IMAGENS;

    var dadosIniciais = this.hdn.value;
    this.hdn.value = '';

    this.lerCelula = function (celula) {
        var ret = null;
        var div = celula.getElementsByTagName('div');
        if (div.length == 0) {
            ret = celula.innerHTML;
        } else {
            ret = div[0].innerHTML;
        }
        return ret.infraReplaceAll('<br>', '<br />');
    };

    this.adicionar = function (arrColunas, bolMarcarLinha) {
        var i, j, strLinha, objLinha, numColunas, arrCol, strDados, alinhamento;

        if (bolMarcarLinha == undefined) {
            bolMarcarLinha = true;
        }

        numColunas = me.ths.length;

        for (i = 0; i < arrColunas.length; i++) {
            if (arrColunas[i] == null) {
                arrColunas[i] = 'null';
            }
        }

        var strCssTr = '';
        var bolFlagAlterou = false;
        // Se tem dados sobre outras linhas e a linha atual tem ID
        if (me.hdn.value != '' && arrColunas[0] != 'null') {

            // Procura por uma linha com o mesmo ID
            for (i = 0; i < me.tbl.rows.length; ++i) {
                strCssTr = (strCssTr == 'infraTrEscura') ? 'infraTrClara' : 'infraTrEscura';
                if (String(infraTrim(me.lerCelula(me.tbl.rows[i].cells[0]))) == String(infraTrim(arrColunas[0]))) {

                    // Se não é permitida alteração
                    if (!me.flagAlterar) {
                        if (me.exibirMensagens) {
                            alert('Este item já foi adicionado.');
                        }
                        return;
                    }

                    // Monta dados do item para localizar no hidden
                    strLinha = '';
                    for (j = 0; j < me.tbl.rows[i].cells.length - me.colunaAcoes; j++) {
                        if (j > 0) {
                            strLinha = strLinha.concat('±');
                        }
                        var celula = me.lerCelula(me.tbl.rows[i].cells[j]);
                        strLinha = strLinha.concat(celula);
                    }

                    // Monta nova linha hidden
                    var strLinhaNova = '';
                    for (j = 0; j < arrColunas.length; j++) {
                        if (j > 0) {
                            strLinhaNova = strLinhaNova.concat('±');
                        }
                        strLinhaNova = strLinhaNova.concat(arrColunas[j]);
                    }
                    me.alterarLinhaHidden(strLinha, strLinhaNova);

                    // Atualiza tabela
                    for (j = 0; j < me.tbl.rows[i].cells.length - me.colunaAcoes; j++) {

                        alinhamento = '';

                        if (me.ths[j].align != '') {
                            alinhamento = 'text-align:' + me.ths[j].align;
                        }

                        if (arrColunas[j] == 'null') {
                            me.tbl.rows[i].cells[j].innerHTML = '<div style="visibility:hidden;' + alinhamento + '">null</div>';
                        } else {
                            me.tbl.rows[i].cells[j].innerHTML = '<div style="' + alinhamento + '">' + infraFormatarXML(infraRemoverFormatacaoXML(arrColunas[j])) + '</div>';
                        }
                    }

                    if (me.exibirMensagens) {
                        alert('Item alterado.');
                    }

                    me.tbl.rows[i].className = 'infraTrAcessada';
                    bolFlagAlterou = true;
                } else {
                    me.tbl.rows[i].className = strCssTr;
                }
            }
        }

        if (bolFlagAlterou) {
            return;
        }

        var tableBody = me.tbl.getElementsByTagName('tbody')[0];

        if (tableBody == null) {
            me.tbl.createTBody();
            tableBody = me.tbl.getElementsByTagName('tbody')[0];
        }

        if (me.inserirNoInicio) {
            objLinha = tableBody.insertRow(0);
        } else {
            objLinha = tableBody.insertRow(me.tbl.rows.length - 1);
        }

        arrCol = [];
        strDados = '';

        // INCLUI LINHA
        if (me.gerarEfeitoTabela) {
            objLinha.onmouseover = function () {
                if (infraVersaoIE() > 0 && infraVersaoIE() < 9) {
                    this.style.removeAttribute("backgroundColor");
                } else {
                    this.style.removeProperty("background-color");
                }
                this.className = 'infraTrSelecionada';
            };
        }

        var trs = me.tbl.getElementsByTagName('tr');
        var trClass = 'infraTrClara';

        for (i = 1; i < trs.length; i++) {
            trs[i].className = trClass;

            if (trClass == 'infraTrEscura') {
                trClass = 'infraTrClara';
                if (me.gerarEfeitoTabela) {
                    trs[i].onmouseout = function () {
                        this.className = 'infraTrEscura';
                        if (me.corTr != '') {
                            this.style.backgroundColor = me.corTr;
                        }
                    };
                }
            } else {
                trClass = 'infraTrEscura';
                if (me.gerarEfeitoTabela) {
                    trs[i].onmouseout = function () {
                        this.className = 'infraTrClara';
                        if (me.corTr != '') {
                            this.style.backgroundColor = me.corTr;
                        }
                    };
                }
            }

            if (me.corTr != '') {
                trs[i].style.backgroundColor = me.corTr;
            }
        }

        if (bolMarcarLinha) {
            objLinha.className = 'infraTrAcessada';
        }

        // insere demais dados
        for (i = 0; i < numColunas - me.colunaAcoes; i++) {
            arrCol[i] = objLinha.insertCell(i);
            arrCol[i].style.display = me.ths[i].style.display;
            arrCol[i].className = 'infraTd';
            if (arrColunas[i] != 'null') {
                alinhamento = '';
                if (me.ths[i].align != '') {
                    alinhamento = 'style="text-align:' + me.ths[i].align + ';"';
                }

                if (me.flagFormatarXml) {
                    arrCol[i].innerHTML = '<div ' + alinhamento + '>' + infraFormatarXML(infraRemoverFormatacaoXML(arrColunas[i])) + '</div>';
                } else {
                    arrCol[i].innerHTML = '<div ' + alinhamento + '>' + arrColunas[i] + '</div>';
                }

            } else {
                arrCol[i].innerHTML = '<div style="visibility:hidden">null</div>';
            }

            if (i > 0) {
                strDados += '±';
            }
            strDados += arrColunas[i];
        }

        if (me.colunaAcoes == 1) {
            var colAcoes = objLinha.insertCell(numColunas - 1);
            colAcoes.align = 'center';
            colAcoes.vAlign = 'center';
        }

        // insere coluna de ações
        if (me.flagAlterar || me.flagRemover || me.flagOrdenar) {
            if (me.colunaAcoes == 1) {
                if (me.flagOrdenar) {
                    me.adicionarAcoesSubirDescer(colAcoes);
                }
                if (me.flagAlterar && arrColunas[0] != null) {
                    me.adicionarAcaoAlterar(colAcoes);
                    me.adicionarEspaco(colAcoes);
                }

                if (me.flagRemover) {
                    me.adicionarAcaoRemover(colAcoes);
                }
            }
        }
        me.adicionarLinhaHidden(strDados);
        if (me.ready) me.atualizarSetas();
        infraAtualizarCaption(me.tbl);
    };

    this.recarregar = function () {
        var dados = me.hdn.value;
        me.limpar();
        me.inicializar(dados);
    };

    this.limpar = function () {
        me.hdn.value = '';

        var numLinhas = me.tbl.rows.length - 1;
        var numLinhaInicial = 1;

        for (var i = 0; i < numLinhas; i++) {
            me.tbl.deleteRow(numLinhaInicial);
        }
    };

    this.inicializar = function (dados) {

        var numColunas = me.ths.length;

        var thead = me.tbl.tHead;
        if (thead == null) {
            thead = me.tbl.createTHead();
            thead.appendChild(me.tbl.rows[0]);
        }

        if (numColunas == 0) {
            alert('Cabeçalho da tabela não encontrado. [' + me.tbl.id + ']');
            return;
        }

        if (infraTrim(me.ths[numColunas - 1].innerHTML.toLowerCase()) == 'ações') {
            me.colunaAcoes = 1;
        } else {
            me.colunaAcoes = 0;
        }

        if (dados != '') {
            var arrLinhas = dados.split('¥');
            for (var j = 0; j < arrLinhas.length; j++) {
                var arrColunas = arrLinhas[j].split('±');
                me.adicionar(arrColunas, false);
            }
        }
        if (me.colunaAcoes && me.flagOrdenar) {
            me.atualizarSetas();
        }
        me.ready = true;
    };

    if (window.attachEvent) { // Limpar as referências do IE
        window.attachEvent("onunload", function () {
            me.tbl = null;
            me.hdn = null;
            me.ths = null;
            me.alterar = null;
            me = null;

        });

    }

    this.atualizarSetas = function () {
        var acoes, numAcoes;
        if (!me.colunaAcoes || !me.flagOrdenar) return;
        var numColAcoes = me.ths.length - 1;
        var numRows = me.tbl.rows.length;
        for (var i = 1; i < numRows; i++) {
            acoes = me.tbl.rows[i].cells[numColAcoes].getElementsByTagName('img');
            numAcoes = acoes.length;
            for (var j = 0; j < numAcoes; j++) {
                if (acoes[j].classList) {
                    if (acoes[j].classList.contains('infraSubir')) {
                        acoes[j].style.display = (i == 1 ? 'none' : '');
                    } else if (acoes[j].classList.contains('infraDescer')) {
                        acoes[j].style.display = (i == numRows - 1 ? 'none' : '');
                    }
                } else {
                    if (acoes[j].className.indexOf('infraSubir') > -1) {
                        acoes[j].style.display = (i == 1 ? 'none' : '');
                    } else if (acoes[j].className.indexOf('infraDescer') > -1) {
                        acoes[j].style.display = (i == numRows - 1 ? 'none' : '');
                    }
                }

            }
        }
    };
    this.adicionarAcoesSubirDescer = function (coluna) {
        var imgSubir = document.createElement('img');
        imgSubir.src = INFRA_ICONE_MOVER_ACIMA;
        imgSubir.title = 'Subir Item';
        imgSubir.caption = 'Subir Item';
        imgSubir.className = 'infraImg infraSubir';
        imgSubir.onclick = function () {
            me.subirLinha(this);
        };
        coluna.appendChild(imgSubir);

        var imgDescer = document.createElement('img');
        imgDescer.src = INFRA_ICONE_MOVER_ABAIXO;
        imgDescer.title = 'Descer Item';
        imgDescer.caption = 'Descer Item';
        imgDescer.className = 'infraImg infraDescer';
        imgDescer.onclick = function () {
            me.descerLinha(this);
        };
        coluna.appendChild(imgDescer);
        me.adicionarEspaco(coluna);

    };
    this.adicionarAcaoAlterar = function (coluna) {
        var imgAlterar = document.createElement('img');
        imgAlterar.src = INFRA_ICONE_ALTERAR;
        imgAlterar.title = 'Alterar Item';
        imgAlterar.caption = 'Alterar Item';
        imgAlterar.className = 'infraImg';
        imgAlterar.onclick = function () {
            me.alterarLinha(this.parentNode.parentNode.rowIndex);
        };
        coluna.appendChild(imgAlterar);
    };

    this.adicionarEspaco = function (coluna) {
        var imgEspaco = document.createElement('img');
        imgEspaco.src = me.dirImg + '/espaco.gif';
        coluna.appendChild(imgEspaco);
    };

    this.adicionarAcaoRemover = function (coluna) {
        var imgRemover = document.createElement('img');
        imgRemover.src = INFRA_ICONE_REMOVER;
        imgRemover.title = 'Remover Item';
        imgRemover.caption = 'Remover Item';
        imgRemover.className = 'infraImg';
        imgRemover.onclick = function () {
            me.removerLinha(this.parentNode.parentNode.rowIndex);
        };
        coluna.appendChild(imgRemover);
    };

    this.removerLinhaHidden = function (linha) {
        // Remove dados anteriores
        var temp = infraRemoverFormatacaoXML(linha);

        var arrDados = me.hdn.value.split('¥');
        var tmp = '';
        for (var i = 0; i < arrDados.length; i++) {
            if (infraRemoverFormatacaoXML(arrDados[i]) != temp) {
                if (tmp != '') {
                    tmp += '¥';
                }
                tmp += arrDados[i];
            }
        }
        me.hdn.value = tmp;
    };
    this.alterarLinhaHidden = function (linhaAntiga, linhaNova) {
        // Remove dados anteriores
        var temp = infraRemoverFormatacaoXML(linhaAntiga);
        var bolAlterou = false;
        var arrDados = me.hdn.value.split('¥');
        var tmp = '';
        for (var i = 0; i < arrDados.length; i++) {
            if (infraRemoverFormatacaoXML(arrDados[i]).toString() != temp.toString()) {
                if (tmp != '') {
                    tmp += '¥';
                }
                tmp += arrDados[i];
            } else {
                if (tmp != '') {
                    tmp += '¥';
                }
                tmp += infraFormatarXML(infraRemoverFormatacaoXML(linhaNova));
                bolAlterou = true;
            }
        }

        if (!bolAlterou) {
            alert('Erro atualizando tabela.');
        } else {
            me.hdn.value = tmp;
        }
    };

    this.adicionarLinhaHidden = function (linha) {
        linha = infraFormatarXML(infraRemoverFormatacaoXML(linha));
        var dados = me.hdn.value;
        if (me.inserirNoInicio) {
            if (dados.length > 0) {
                dados = dados.concat('¥');
            }
            me.hdn.value = dados.concat(linha);
        } else {
            if (dados.length > 0) {
                linha = linha.concat('¥');
            }
            me.hdn.value = linha.concat(dados);
        }
    };
    this.obterItens = function () {

        var ret = Array();
        var arrLinhas = null;
        var arrColunas = null;
        var i;
        var s = infraTrim(me.hdn.value);

        if (s != '') {
            arrLinhas = s.split('¥');
            for (i = 0; i < arrLinhas.length; i++) {
                arrColunas = arrLinhas[i].split('±');
                ret[i] = arrColunas;
            }
        }
        return ret;
    };

    this.alterarLinha = function (rowIndex) {
        var i;
        var arrLinha = Array();
        var numColunas = me.tbl.rows[rowIndex].cells.length - 1;
        for (i = 0; i < numColunas; i++) {
            arrLinha[i] = infraRemoverFormatacaoXML(me.lerCelula(me.tbl.rows[rowIndex].cells[i]));
        }
        if (me.alterar != null) {
            me.alterar(arrLinha);
        }
    };

    this.removerLinha = function (rowIndex) {
        var numColunas = 0;

        if (me.remover != null) {

            var arrLinha = Array();
            numColunas = me.tbl.rows[rowIndex].cells.length - 1;
            for (i = 0; i < numColunas; i++) {
                arrLinha[i] = me.lerCelula(me.tbl.rows[rowIndex].cells[i]);
            }

            if (!me.remover(arrLinha)) {
                return;
            }
        }

        var i;
        var strLinha = '';
        numColunas = me.tbl.rows[rowIndex].cells.length - 1;
        for (i = 0; i < numColunas; i++) {
            if (i > 0) {
                strLinha = strLinha.concat('±');
            }
            var celula = me.lerCelula(me.tbl.rows[rowIndex].cells[i]);
            strLinha = strLinha.concat(celula);
        }

        me.removerLinhaHidden(strLinha);
        me.tbl.deleteRow(rowIndex);
        for (i = rowIndex; i < me.tbl.rows.length; i++) {
            if (me.tbl.rows[i].className == 'infraTrClara') {
                me.tbl.rows[i].className = 'infraTrEscura';
                if (me.gerarEfeitoTabela) {
                    me.tbl.rows[i].onmouseout = function () {
                        this.className = 'infraTrEscura'
                    };
                }
            } else {
                me.tbl.rows[i].className = 'infraTrClara';
                if (me.gerarEfeitoTabela) {
                    me.tbl.rows[i].onmouseout = function () {
                        this.className = 'infraTrClara'
                    };
                }
            }
        }
        infraAtualizarCaption(me.tbl);
        me.atualizarSetas();
        if (me.renumerar != null) renumerar.call(this);
    };

    this.adicionarAcoes = function (id, acoes, bolAlterar, bolRemover) {

        var i;
        if (me.colunaAcoes == 0) {
            alert('Coluna de ações não encontrada.');
            return;
        }

        var numLinhaInicial = 1;

        var linha = null;
        for (i = numLinhaInicial; i < me.tbl.rows.length; i++) {

            if (String(me.lerCelula(me.tbl.rows[i].cells[0])) == String(id)) {
                linha = i;
                break;
            }
        }

        if (linha == null) {
            // alert('ID '+id+' não encontrado.');
            return;
        }

        var numColunaAcoes = me.tbl.rows[linha].cells.length - 1;
        var colAcoes = me.tbl.rows[linha].cells[numColunaAcoes];

        if (acoes != undefined && acoes != '') {
            var imgs = colAcoes.getElementsByTagName("img");

            var div = document.createElement('div');
            div.style.display = 'inline';
            div.innerHTML = acoes;

            if (imgs.length > 0) {
                colAcoes.insertBefore(div, imgs[0]);
            } else {
                colAcoes.appendChild(div);
            }

            imgs = colAcoes.getElementsByTagName("img");

            for (i = 0; i < imgs.length; i++) {
                imgs[i].className = '';
                imgs[i].setAttribute("border", "0");
            }
        }

        if (bolAlterar != undefined && bolAlterar) {
            me.adicionarAcaoAlterar(colAcoes);
        }

        if (bolRemover != undefined && bolRemover) {
            me.adicionarAcaoRemover(colAcoes);
        }
    };
    this.inverterLinhas = function (linha1, linha2) {
        var trLinha1 = me.tbl.rows[linha1];
        var trLinha2 = me.tbl.rows[linha2];
        var cellsLinha1 = trLinha1.getElementsByTagName('div');
        var cellsLinha2 = trLinha2.getElementsByTagName('div');

        var tmp;

        for (var i = cellsLinha1.length - me.colunaAcoes; i >= 0; i--) {
            if (cellsLinha1[i].textContent) {
                tmp = cellsLinha1[i].textContent;
                cellsLinha1[i].textContent = cellsLinha2[i].textContent;
                cellsLinha2[i].textContent = tmp;
            } else {
                tmp = cellsLinha1[i].innerText;
                cellsLinha1[i].innerText = cellsLinha2[i].innerText;
                cellsLinha2[i].innerText = tmp;
            }
        }
        me.atualizaHdn();
    };
    this.subirLinha = function (obj) {
        var trAtual = obj.parentNode.parentNode;
        var numLinhaAtual;
        numLinhaAtual = trAtual.rowIndex;
        if (numLinhaAtual <= 1) return;
        me.inverterLinhas(numLinhaAtual, numLinhaAtual - 1);
    };
    this.descerLinha = function (obj) {
        var numLinhas = me.tbl.rows.length - 1;
        var trAtual = obj.parentNode.parentNode;
        var numLinhaAtual;
        numLinhaAtual = trAtual.rowIndex;
        if (numLinhaAtual >= numLinhas) return;
        me.inverterLinhas(numLinhaAtual, numLinhaAtual + 1);
    };

    this.atualizaHdn = function () {
        var numRows = me.tbl.rows.length;
        var cells, str = [], strRow, i, i2, numColunas = me.tbl.rows[0].cells.length - me.colunaAcoes;
        for (i = 1; i < numRows; i++) {
            cells = me.tbl.rows[i].getElementsByTagName('div');
            strRow = [];
            for (i2 = 0; i2 < numColunas; i2++) {
                strRow.push(cells[i2].textContent || cells[i2].innerText);
            }
            strRow = strRow.join('±');
            str.push(strRow);
        }
        me.hdn.value = str.reverse().join('¥');
    };

    this.inicializar(dadosIniciais);
}