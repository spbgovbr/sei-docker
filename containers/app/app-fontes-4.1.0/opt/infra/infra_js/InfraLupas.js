//var INFRA_LUPA_POPUP = 1;
//var INFRA_LUPA_MODAL = 2;

function infraLupaText(idText, idHidden, link) {
    var me = this;
    this.Type = 'infraLupaText';
    this.txt = infraGetElementById(idText);
    this.hdn = infraGetElementById(idHidden);
    this.url = link;
    this.tipoJanela = INFRA_LUPA_TIPO_JANELA;

    this.selecionar = function (numLargura, numAltura) {

        if (numLargura == undefined) {
            numLargura = 600;
        }

        if (numAltura == undefined) {
            numAltura = 400;
        }

        if (me.tipoJanela == INFRA_JANELA_POPUP) {
            return infraAbrirJanela(me.url, 'infraJanelaSelecao' + Math.floor((Math.random() * 999999)), numLargura, numAltura, 'location=0,status=1,resizable=1,scrollbars=1');
        } else {
            return infraAbrirJanelaModal(me.url, numLargura, numAltura);
        }

    }

    this.remover = function () {
        if (typeof (me.processarRemocao) == 'function') {
            if (me.processarRemocao()) {
                me.txt.value = '';
                me.hdn.value = '';
            }
        }
    }

    this.alterar = function () {
        if (me.hdn.value == '') {
            alert('Nenhum item selecionado.');
            return;
        }
        if (typeof (me.processarAlteracao) == 'function') {
            me.processarAlteracao(me.hdn.value, me.txt.value);
        }
    }

    me.processarSelecao = function (item) {
        return true;
    }
    me.processarRemocao = function () {
        return true;
    }
    me.finalizarSelecao = function () {
    }
}

function infraSelect(idSelect, idHidden, link, rotacionar) {
    return new infraLupaSelect(idSelect, idHidden, link, rotacionar);
}

function infraLupaSelect(idSelect, idHidden, link, rotacionar) {
    var me = this;
    this.Type = 'infraSelect';
    this.sel = infraGetElementById(idSelect);
    this.hdn = infraGetElementById(idHidden);
    this.url = link;

    if (rotacionar == undefined) {
        this.rotacionar = false;
    } else {
        this.rotacionar = rotacionar;
    }

    this.tipoJanela = INFRA_LUPA_TIPO_JANELA;

    this.inicializar = function () {
        if (me.hdn.value != '') {
            me.montar();
        } else if (me.sel.options.length > 0) {
            me.atualizar();
        }

        infraAdicionarEvento(me.sel, "keydown", me.deleteTeclado);
    }

    this.selecionar = function (numLargura, numAltura) {

        if (numLargura == undefined) {
            numLargura = 600;
        }

        if (numAltura == undefined) {
            numAltura = 400;
        }

        if (typeof (me.validarSelecionar) == 'function') {
            if (!me.validarSelecionar()) {
                return;
            }
        }

        if (me.tipoJanela == INFRA_JANELA_POPUP) {
            return infraAbrirJanela(me.url, 'infraJanelaSelecao' + Math.floor((Math.random() * 999999)), numLargura, numAltura, 'location=0,status=1,resizable=1,scrollbars=1');
        } else {
            return infraAbrirJanelaModal(me.url, numLargura, numAltura);
        }

    }

    this.limpar = function () {
        me.hdn.value = '';
        me.sel.options.length = 0;
    }

    this.montar = function () {
        me.sel.options.length = 0;
        if (infraTrim(me.hdn.value) != '') {
            var arrItens = me.hdn.value.split('¥');
            for (var i = 0; i < arrItens.length; i++) {
                var arrItem = arrItens[i].split('±');
                infraSelectAdicionarOption(me.sel, arrItem[1], arrItem[0]);
            }
        }
    }

    this.atualizar = function () {
        me.hdn.value = '';
        for (var i = 0; i < me.sel.length; i++) {
            if (me.hdn.value != '') {
                me.hdn.value = me.hdn.value + '¥';
            }
            me.hdn.value = me.hdn.value + me.sel.options[i].value + '±' + me.sel.options[i].text;
            /*
            me.hdn.value = me.hdn.value.infraReplaceAll('&','&amp;');
            me.hdn.value = me.hdn.value.infraReplaceAll('<','&lt;');
            me.hdn.value = me.hdn.value.infraReplaceAll('>','&gt;');
            me.hdn.value = me.hdn.value.infraReplaceAll('"','&quot;');
            */
        }
    }

    this.remover = function () {
        var i;

        if (me.sel.length == 0) {
            alert('Não existem itens para esta ação.');
            return;
        }

        var temp = new Array();
        var j = 0;
        for (i = 0; i < me.sel.length; i++) {
            if (me.sel.options[i].selected) {
                temp[j++] = me.sel.options[i];
            }
        }

        if (temp.length == 0) {
            alert('Nenhum item selecionado.');
            return;
        }

        if (typeof (me.processarRemocao) == 'function') {
            if (me.processarRemocao(temp)) {
                var flagRemoveuItem;
                do {
                    flagRemoveuItem = false;
                    for (i = 0; i < me.sel.length; i++) {
                        if (me.sel.options[i].selected) {
                            me.sel.options[i] = null;
                            flagRemoveuItem = true;
                            break;
                        }
                    }
                } while (flagRemoveuItem);

                me.atualizar();

                if (typeof (me.finalizarRemocao) == 'function') {
                    me.finalizarRemocao(temp);
                }
            }
        }
    }

    this.moverAcima = function () {

        if (me.sel.length == 0) {
            alert('Não existem itens para esta ação.');
            return;
        }

        var item = null;
        var v = null;
        var t = null;
        var s = null;

        for (var i = 0; i < me.sel.length; i++) {
            if (me.sel.options[i].selected) {

                item = i;

                if (item > 0) {

                    v = me.sel.options[item - 1].value;
                    t = me.sel.options[item - 1].text;
                    s = me.sel.options[item - 1].selected;

                    me.sel.options[item - 1].value = me.sel.options[item].value;
                    me.sel.options[item - 1].text = me.sel.options[item].text;
                    me.sel.options[item - 1].selected = true;

                    me.sel.options[item].value = v;
                    me.sel.options[item].text = t;
                    me.sel.options[item].selected = s;

                } else {

                    if (me.rotacionar && me.sel.length > 1) {

                        v = me.sel.options[0].value;
                        t = me.sel.options[0].text;
                        s = me.sel.options[0].selected;

                        for (var j = 1; j < me.sel.length; j++) {
                            me.sel.options[j - 1].value = me.sel.options[j].value;
                            me.sel.options[j - 1].text = me.sel.options[j].text;
                            me.sel.options[j - 1].selected = me.sel.options[j].selected;
                        }

                        me.sel.options[me.sel.length - 1].value = v;
                        me.sel.options[me.sel.length - 1].text = t;
                        me.sel.options[me.sel.length - 1].selected = s;
                    }

                    break;
                }
            }
        }

        if (item == null) {
            alert('Nenhum item selecionado.');
        } else {
            me.atualizar();
        }

    }

    this.moverAbaixo = function () {

        if (me.sel.length == 0) {
            alert('Não existem itens para esta ação.');
            return;
        }

        var item = null;
        var v = null;
        var t = null;

        for (var i = (me.sel.length - 1); i >= 0; i--) {
            if (me.sel.options[i].selected) {

                item = i;

                if (item < (me.sel.length - 1)) {

                    v = me.sel.options[item + 1].value;
                    t = me.sel.options[item + 1].text;
                    s = me.sel.options[item + 1].selected;

                    me.sel.options[item + 1].value = me.sel.options[item].value;
                    me.sel.options[item + 1].text = me.sel.options[item].text;
                    me.sel.options[item + 1].selected = true;

                    me.sel.options[item].value = v;
                    me.sel.options[item].text = t;
                    me.sel.options[item].selected = s;

                } else {

                    if (me.rotacionar && me.sel.length > 1) {

                        v = me.sel.options[me.sel.length - 1].value;
                        t = me.sel.options[me.sel.length - 1].text;
                        s = me.sel.options[me.sel.length - 1].selected;

                        for (var j = (me.sel.length - 2); j >= 0; j--) {
                            me.sel.options[j + 1].value = me.sel.options[j].value;
                            me.sel.options[j + 1].text = me.sel.options[j].text;
                            me.sel.options[j + 1].selected = me.sel.options[j].selected;
                        }

                        me.sel.options[0].value = v;
                        me.sel.options[0].text = t;
                        me.sel.options[0].selected = s;
                    }

                    break;
                }
            }
        }

        if (item == null) {
            alert('Nenhum item selecionado.');
        } else {
            me.atualizar();
        }
    }

    this.alterar = function () {
        if (me.sel.length == 0) {
            alert('Não existem itens para esta ação.');
            return;
        }

        var item = null;
        for (var i = 0; i < me.sel.length; i++) {
            if (me.sel.options[i].selected) {

                if (item != null) {
                    alert('Mais de um item selecionado.');
                    return;
                }

                item = i;
            }
        }

        if (item == null) {
            alert('Nenhum item selecionado.');
            return;
        }

        if (typeof (me.processarAlteracao) == 'function') {
            me.processarAlteracao(item, me.sel.options[item].text, me.sel.options[item].value);
        }
    }

    this.deleteTeclado = function (ev) {
        // keyCode 46 = Delete Teclado
        if (infraGetCodigoTecla(ev) == 46) {
            me.remover();
        }
    }

    this.adicionar = function (id, descricao, txt) {
        var options = me.sel.options;

        for (var i = 0; i < options.length; i++) {
            if (options[i].value == id) {
                options[i].selected = true;
            } else {
                options[i].selected = false;
            }
        }

        if (me.sel.selectedIndex != -1) {
            self.setTimeout('alert(\'Item já consta na lista.\')', 100);
        } else {
            opt = infraSelectAdicionarOption(me.sel, descricao, id);
            me.atualizar();
            opt.selected = true;
        }

        if (txt != undefined) {
            txt.value = '';
            txt.focus();
        }
    }

    me.processarSelecao = function (itens) {
        return true;
    }
    me.finalizarSelecao = function () {
    }
    me.processarRemocao = function (itens) {
        return true;
    }
    me.finalizarRemocao = function (itens) {
    }

    me.inicializar();
}

function infraLupaTable(idTable, idHidden, link, bolRemover) {
    var me = this;
    this.Type = 'infraLupaTable';
    this.tbl = infraGetElementById(idTable);
    this.hdn = infraGetElementById(idHidden);
    this.url = link;
    this.objInfraTabelaDinamica = null;
    this.bolRemover = bolRemover;
    this.tipoJanela = INFRA_LUPA_TIPO_JANELA;

    this.inicializar = function () {
        me.objInfraTabelaDinamica = new infraTabelaDinamica(me.tbl.id, me.hdn.id, false, me.bolRemover);
        me.objInfraTabelaDinamica.inserirNoInicio = false;
    }

    this.selecionar = function (numLargura, numAltura) {

        if (numLargura == undefined) {
            numLargura = 600;
        }

        if (numAltura == undefined) {
            numAltura = 400;
        }

        if (me.tipoJanela == INFRA_JANELA_POPUP) {
            return infraAbrirJanela(me.url, 'infraJanelaSelecao' + Math.floor((Math.random() * 999999)), numLargura, numAltura, 'location=0,status=1,resizable=1,scrollbars=1');
        } else {
            return infraAbrirJanelaModal(me.url, numLargura, numAltura);
        }
    }

    this.limpar = function () {
        me.objInfraTabelaDinamica.limpar();
    }

    this.montar = function () {
    }

    this.atualizar = function () {
        me.objInfraTabelaDinamica.recarregar();
    }

    this.remover = function () {
    }

    this.moverAcima = function () {
    }

    this.moverAbaixo = function () {
    }

    this.deleteTeclado = function (ev) {
    }

    me.processarSelecao = function (itens) {
        return true;
    }
    me.processarRemocao = function (itens) {
        return true;
    }
    me.finalizarSelecao = function () {
    }

    me.inicializar();
}
	