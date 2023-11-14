function infraAjaxGetElementsByTagName(xml, tag) {
    try {
        var arr = xml.getElementsByTagName(tag);
    } catch (exc) {
        arr = new Array();
    }
    return arr;
}

function infraAjaxCriarRequest() {
    request = null;
    try {
        request = new XMLHttpRequest();
    } catch (trymicrosoft) {
        try {
            request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (othermicrosoft) {
            try {
                request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (failed) {
                request = false;
            }
        }
    }

    if (!request)
        alert('Este navegador não possui recursos para uso do AJAX.');
    else
        return request;
}


function infraAjaxMontarPostPadraoSelect(primeiroItemValor, primeiroItemDescricao, valorItemSelecionado) {
    var post = 'primeiroItemValor=' + primeiroItemValor;
    if (infraTrim(primeiroItemDescricao) == '') {
        primeiroItemDescricao = ' ';
    }
    post += '&primeiroItemDescricao=' + primeiroItemDescricao;
    post += '&valorItemSelecionado=' + valorItemSelecionado;
    return post;
}

function infraAjaxImagemVerificado(obj) {
    var imgId = 'imgInfraAjaxVerificado' + obj.id;
    imgObj = document.getElementById(imgId);
    if (imgObj != null) return imgObj;
    imgObj = document.createElement('img');
    imgObj.id = imgId;
    imgObj.style.position = 'absolute';
    imgObj.src = INFRA_PATH_IMAGENS + '/verificado.gif';
    imgObj.style.visibility = 'hidden';

    document.body.appendChild(imgObj);

    return imgObj
}

function infraAjaxMostrarImg(obj, img, offsetx, offsety) {

    var el = obj;
    var x = el.offsetWidth + offsetx;
    var y = offsety;

    //Walk up the DOM and add up all of the offset positions.
    while (el.offsetParent && el.id != 'divInfraAreaTelaD' && el.tagName.toUpperCase() != 'BODY') {
        x += el.offsetLeft;
        y += el.offsetTop;
        el = el.offsetParent;
    }
    if (el.id != 'divInfraAreaTelaD') {
        x += el.offsetLeft;
        y += el.offsetTop;
    }

    img.style.left = x + 'px';
    img.style.top = y + 'px';
    img.style.visibility = 'visible';


    el = null;
    obj = null;
    img = null;
}


function infraAjaxOcultarImg(img) {
    img.style.visibility = 'hidden';
}

function infraAjaxMarcarSelecao(obj) {
    if (obj.className.indexOf('infraAjaxMarcarSelecao') == -1) {
        obj.className += ' infraAjaxMarcarSelecao';
    }
}

function infraAjaxDesmarcarSelecao(obj) {
    if (obj.className == 'infraAjaxMarcarSelecao') {
        obj.className = '';
    } else {
        obj.className = obj.className.replace('infraAjaxMarcarSelecao', '');
    }
}

function infraAjaxPost(obj) {

    obj.executou = false;

    if (obj.ajaxReq != undefined) {
        //Cancelar requisicao antiga
        obj.ajaxReq.abort();
        while (1) {
            if (obj.ajaxReq.readyState == 0) {

                var post = null;

                if (typeof (obj.prepararExecucao) == 'function') {
                    post = obj.prepararExecucao();
                }

                //Execucao cancelada
                if (post == false) {
                    break;
                }

                if (post != null) {
                    //A comunicação é feita via UTF-8, para tratar corretamente a acentuação
                    //usar encodeURIComponent e no lado do servidor utf8_decode
                    post = encodeURIComponent(post);
                    post = post.replace(/%26/g, '&');
                    post = post.replace(/%3D/g, '=');
                    post = post.replace(/%20/g, ' ');
                }

                /*
                alert('URL: '+url)
                */
                var metodo = "POST";
                if (post == null) {
                    metodo = "GET"
                }
                /*
                else{
                  alert('POST: '+post);
                }
                */

                obj.iniciarExecucao();

                if (obj.mostrarAviso) {
                    infraExibirAviso();
                }

                async = ((obj.async == undefined) ? true : obj.async);

                try {
                    obj.ajaxReq.open(metodo, obj.ajaxTarget, async);
                    obj.ajaxReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    obj.ajaxReq.onreadystatechange = obj.processarAjax;
                    obj.ajaxReq.send(post);
                } catch (e) {

                    if (!navigator.onLine){
                        alert('Falha na conexão com o servidor.');
                    }else{
                        alert('Sessão expirada.');
                    }

                    if (obj.mostrarAviso) {
                        self.setTimeout('infraOcultarAviso()', obj.tempoAviso);
                    }

                    break;
                }

                obj.executou = true;

                if (!async && (INFRA_FF > 0 && INFRA_FF < 4)) {
                    obj.processarAjax();
                }

                if (obj.mostrarAviso) {
                    self.setTimeout('infraOcultarAviso()', obj.tempoAviso);
                }

                obj.finalizarExecucao();

                break;
            }
        }
    }

    return obj.executou;
}


function infraAjaxProcessarXML(obj) {

    if (obj.ajaxReq.readyState == 4) {

        bolErro = true;
        try {
            if (obj.ajaxReq.status == 200) {
                bolErro = false;
            }
        } catch (componentfailure) {
        }

        if (bolErro) {
            if (obj.mostrarAviso) {
                infraOcultarAviso();
            }
            return false;
        }

        //alert(obj.ajaxReq.responseText); //DEBUG

        var texto = obj.ajaxReq.responseText;


        //Erro que não retornou dentro de um XML
        //Ocorre quando o PHP aborta
        if (texto.substring(1, 5) != '?xml') {
            if (obj.mostrarAviso) {
                infraOcultarAviso();
            }
            //alert(texto);
            return false;
        }


        //Verifica se o XML contém erros
        var nroErros = 0;
        var xml = obj.ajaxReq.responseXML;


        //var itens = xml.getElementsByTagName('erro');
        var itens = infraAjaxGetElementsByTagName(xml, 'erro');
        var nroItens = 0;

        try {
            nroItens = itens.length;
        } catch (failed) {
        }

        if (nroItens) {

            if (obj.mostrarAviso) {
                infraOcultarAviso();
            }

            itErros = nroItens;
            for (i = 0; i < itErros; i++) {
                var desc = itens[i].getAttribute("descricao");
                desc = desc.infraReplaceAll("\\n", "\n");
                alert(desc);
                nroErros = nroErros + 1;
            }
            obj.processarErro();
        }

        itens = null;

        if (nroErros > 0) {
            xml = null;
            return false;
        }

        return xml;
    }

    return false;
}

function getArrPosicaoTagsNegrito(strHtml) {
    //expressão regular que pega os blocos <b ... > ... </b>
    var indices = new Array();
    var myregexp = /<b[^>]*>([\s\S]*?)<\/b>/ig;
    var arrResult = myregexp.exec(strHtml);
    while (arrResult != null) {
        indices[indices.length] = arrResult.index;
        indices[indices.length] = myregexp.lastIndex - 1;
        arrResult = myregexp.exec(strHtml);
    }
    return indices;
}

function infraAjaxAutoCompletar(hdnId, txtDesc, url) {
    var me = this;
    //Define que o texto selecionado não deve ser removido do campo
    this.hdn = infraGetElementById(hdnId);
    this.elem = infraGetElementById(txtDesc);
    this.highlighted = null;
    this.arrItens = new Array();
    this.ajaxTarget = url;
    this.div = null;
    this.prepararExecucao = null;
    this.processarResultado = null;
    this.carregarResultado = null;
    this.marcarSelecao = true;
    this.mostrarComplemento = true;
    this.tamanhoMinimo = 1;
    this.limparCampo = true;
    this.mostrarAviso = false;
    this.tempoAviso = 0;
    this.executou = false;
    this.maiusculas = false;
    this.permitirSelecaoGrupo = false;
    this.permitirSelecaoFilho = true;
    this.bolExecucaoAutomatica = true;
    this.offsetX = 0;
    this.offsetY = 0;
    this.tempoAtraso = 500;
    this.timeout = false;


    //Keycodes que devem ser monitorados
    var TAB = 9;
    var ESC = 27;
    var KEYUP = 38;
    var KEYDN = 40;
    var ENTER = 13;

    var HOME = 36;
    var END = 35;
    var SHIFT = 16;

    //Desabilitar autocomplete IE
    this.elem.setAttribute("autocomplete", "off");

    //Crate AJAX Request
    this.ajaxReq = infraAjaxCriarRequest();

    if (me.elem.className.indexOf('infraAutoCompletar') == -1) {
        me.elem.className += ' infraAutoCompletar';
    }

    this.inicializarDiv = function () {
        var divId = 'divInfraAjax' + me.elem.id;
        me.div = document.getElementById(divId);
        if (me.div == null) {
            divObj = document.createElement('div');
            divObj.id = divId;
            divObj.className = 'infraAjaxAutoCompletar';
            //Tamanho da div igual ao tamanho do campo
            divObj.style.width = me.elem.style.width;
            divInfraAreaTelaD = document.getElementById("divInfraAreaTelaD");
            if (divInfraAreaTelaD) {
                document.getElementById("divInfraAreaTelaD").appendChild(divObj);
                if (divInfraAreaTelaD.style.position == null || divInfraAreaTelaD.style.position != "relative") {
                    divInfraAreaTelaD.style.position = "relative";
                }
            } else {
                document.body.appendChild(divObj);
            }
            me.div = divObj;
        }
        divId = null;
        divObj = null;
    }

    me.inicializarDiv();

    this.setElemValue = function () {
        var a = me.highlighted.firstChild;
        me.elem.value = a.innerTEXT;
        a = null;
    }

    //marca lista pelo uso do mouse
    this.highlightThis = function (obj, yn) {
        if (yn = 'y' && obj.indice != undefined) {

            if (me.highlighted != null) {
                me.highlighted.className = '';
            }

            me.highlighted = obj;
            me.highlighted.className = 'selected';
            me.setElemValue();

        } else {
            obj.className = '';
            me.highlighted = null;
        }
    }

    //marca lista pelo uso do teclado
    this.changeHighlight = function (way) {

        if (me.highlighted != null) {
            me.highlighted.className = '';
            switch (way) {
                case 'up':
                    if (me.highlighted.parentNode.firstChild == me.highlighted) {
                        me.highlighted = me.highlighted.parentNode.lastChild;
                    } else {
                        me.highlighted = me.highlighted.previousSibling;
                    }
                    break;
                case 'down':
                    if (me.highlighted.parentNode.lastChild == me.highlighted) {
                        me.highlighted = me.highlighted.parentNode.firstChild;
                    } else {
                        me.highlighted = me.highlighted.nextSibling;
                    }
                    break;

            }
        } else {
            switch (way) {
                case 'up':
                    if (me.div.firstChild != null) {
                        me.highlighted = me.div.firstChild.lastChild;
                    }
                    break;
                case 'down':
                    if (me.div.firstChild != null) {
                        me.highlighted = me.div.firstChild.firstChild;
                    }
                    break;
            }
        }


        //grupo não selecionável
        if (me.highlighted != null) {
            if (me.highlighted.indice == undefined) {

                //verifica se tem algum item passível de marcação
                var lis = me.div.getElementsByTagName('li');
                for (var i = 0; i < lis.length; i++) {
                    if (lis[i].indice != undefined) {
                        me.changeHighlight(way);
                        break;
                    }
                }
            } else {
                me.highlighted.className = 'selected';
                me.setElemValue();
            }
        }
    }

    //Ação a ser executada no KEYDOWN (funções de navegação)
    this.elem.onkeydown = function (ev) {

        var key = infraGetCodigoTecla(ev);

        switch (key) {
            case ENTER:
                //if (me.highlighted != null && me.highlighted.indice != undefined){
                //  me.escolher(me.highlighted.indice);
                //}
                //me.hideDiv();
                return false;

            case ESC:
                me.hideDiv();
                return false;

            case KEYUP:
                me.changeHighlight('up');
                return false;

            case KEYDN:
                me.changeHighlight('down');
                return false;
        }

    };

    //Rotina no KEYUP (pegar input)
    this.elem.onkeyup = function (ev) {

        if (me.elem.readOnly) {
            return false;
        }

        var key = infraGetCodigoTecla(ev);

        switch (key) {
            //The control keys were already handled by onkeydown, so do nothing.
            case TAB:
                //evita que ao passar com o TAB perca os dados
                me.elem.value = me.elem.value;
                break;

            case ESC:
            case KEYUP:
            case KEYDN:
            case HOME:
            case END:
            case SHIFT:
                return;

            case ENTER:

                if (me.highlighted == null) {
                    if (!me.procurar()) {
                        if (!me.bolExecucaoAutomatica && me.elem.value.length >= me.tamanhoMinimo) {
                            me.executar();
                            me.procurar();
                        }
                    }
                } else {
                    me.escolher(me.highlighted.indice);
                }

                me.hideDiv();

                return false;

            default:

                //limpa tudo menos campo texto
                me.limpar(false);

                //Verificar tamanho mínimo
                if (me.bolExecucaoAutomatica && me.elem.value.length >= me.tamanhoMinimo) {

                    if (me.timeout) {
                        clearTimeout(me.timeout);
                    }

                    me.timeout = setTimeout(function () {
                        me.executar()
                    }, me.tempoAtraso);

                } else {
                    me.hideDiv();
                    return false;
                }
                //Remover elementos highlighted
                me.highlighted = null;
        }
    };

    this.procurar = function () {
        for (var i = 0; i < me.arrItens.length; i++) {
            if (infraRetirarAcentos(me.arrItens[i]['descricao'].toUpperCase()) == infraRetirarAcentos(me.elem.value.toUpperCase())) {
                me.escolher(i);
                me.highlighted = null;
                return true;
            }
        }
        return false;
    }

    this.iniciarExecucao = function () {
    }
    this.processarErro = function () {
    }
    this.finalizarExecucao = function () {
    }

    this.verificarExecucao = function () {
        return me.executou;
    }

    this.executar = function () {

        if (!me.mostrarAviso) {
            me.elem.className += ' infraProcessando';
            me.hideDiv();
        }

        var ret = infraAjaxPost(me);

        if (!me.executou) {
            me.ocultarProcessando();
        }

        return ret;

    }

    //Sumir com autosuggest
    this.elem.onblur = function () {

        if (me.elem.value == '' || me.hdn.value == '') {
            //limpa tudo
            me.limpar();
        }
        me.hideDiv();
    }

    //Ajax return function
    this.processarAjax = function () {

        //xml = infraAjaxProcessarXML(me.ajaxReq,me.processarErro);
        xml = infraAjaxProcessarXML(me);

        if (typeof (xml) == 'object') {

            //me.showDiv();

            //var itens = xml.getElementsByTagName('item');
            var itens = infraAjaxGetElementsByTagName(xml, 'item');

            var itCnt = 0;

            try {
                itCnt = itens.length;
            } catch (failed) {
            }

            if (me.carregarResultado != null) {
                me.carregarResultado(itCnt);
            }

            //Pegar primeiro filho
            me.div.innerHTML = '';
            var ul = document.createElement('ul');
            me.div.appendChild(ul);

            var strNegritoIni = '<b>';
            var strNegritoFim = '</b>';

            if (itCnt > 0) {

                if (itCnt != 1 || itens[0].getAttribute("id") != me.hdn.value) {

                    me.showDiv();

                    var arrGrupo = new Array();
                    var tam = null;
                    var a = null;
                    var li = null;
                    var fmtGrupoIni = null;
                    var fmtGrupoFim = null;
                    var fmtIdentacao = null;

                    for (i = 0; i < itCnt; i++) {

                        fmtGrupoIni = '';
                        fmtGrupoFim = '';
                        fmtIdentacao = '';


                        var descricao = itens[i].getAttribute('descricao');
                        var complemento = itens[i].getAttribute('complemento');
                        var grupo = itens[i].getAttribute('grupo');

                        if (me.maiusculas) {
                            descricao = descricao.toUpperCase();

                            if (complemento != null) {
                                complemento = complemento.toUpperCase();
                            }

                            if (grupo != null) {
                                grupo = grupo.toUpperCase();
                            }
                        }

                        tam = me.arrItens.length;
                        li = document.createElement('li');


                        //lendo grupo
                        if (grupo != null && arrGrupo[grupo] == undefined) {

                            arrGrupo[grupo] = grupo;

                            if (me.permitirSelecaoGrupo) {
                                li.indice = tam;
                                me.arrItens[tam] = new Array();
                                me.arrItens[tam]['id'] = itens[i].getAttribute("id");
                                me.arrItens[tam]['descricao'] = descricao;
                                me.arrItens[tam]['complemento'] = complemento;
                                me.arrItens[tam]['grupo'] = grupo;

                                li.onmouseover = function () {
                                    this.className = 'selected';
                                    /*me.highlightThis(this,'y') */
                                }
                                li.onmouseout = function () {
                                    this.className = '';
                                    /*me.highlightThis(this,'n') */
                                }
                                li.onmousedown = function () {
                                    me.escolher(this.indice);
                                    me.hideDiv();
                                    return false;
                                }

                            }

                            a = document.createElement('a');
                            a.href = '#';
                            a.onclick = function () {
                                return false;
                            }

                            fmtGrupoIni = '<span class="infraAjaxAutoCompletarGrupo">';
                            fmtGrupoFim = '</span>';

                        } else {

                            if (me.permitirSelecaoFilho) {
                                li.indice = tam;

                                me.arrItens[tam] = new Array();
                                me.arrItens[tam]['id'] = itens[i].getAttribute("id");
                                me.arrItens[tam]['descricao'] = descricao;
                                me.arrItens[tam]['complemento'] = complemento;
                                me.arrItens[tam]['grupo'] = grupo;

                                li.onmouseover = function () {
                                    this.className = 'selected';
                                    /*me.highlightThis(this,'y')*/
                                }
                                li.onmouseout = function () {
                                    this.className = '';
                                    /*me.highlightThis(this,'n')*/
                                }
                                li.onmousedown = function () {
                                    me.escolher(this.indice);
                                    me.hideDiv();
                                    return false;
                                }
                            }

                            a = document.createElement('a');
                            a.href = '#';
                            a.onclick = function () {
                                return false;
                            }

                            var tab = '';
                            if (grupo != null) {
                                fmtIdentacao = '<pre style="display:inline">    </pre>';
                            }

                        }

                        if (complemento != null && me.mostrarComplemento) {
                            a.innerHTML = unescape(descricao) + ' - ' + unescape(complemento);
                        } else {
                            a.innerHTML = unescape(descricao);
                        }

                        //digitou algo e o item é selecionável
                        if (me.elem.value.length > 0 && li.indice != undefined) {

                            var palavras = infraRetirarAcentos(me.elem.value).toUpperCase().split(' ');
                            var indices = getArrPosicaoTagsNegrito(a.innerHTML);
                            for (var j = 0; j < palavras.length; j++) {

                                var valor = palavras[j];

                                if (infraTrim(valor) != '') {

                                    var valor_regex = valor.replace(/[\(\)\[\]\{\}\^\\\.\?\!\|\=\+\*\$\:]/g, '\\\0');
                                    var regex1 = new RegExp(valor_regex, "g");
                                    var strSemHTML = infraRemoverFormatacaoXML(a.innerHTML);
                                    var result = regex1.exec(infraRetirarAcentos(strSemHTML.toUpperCase()));
                                    while (result != null) {
                                        var pos = result.index;
                                        for (var k = 0; k < indices.length; k += 2) {
                                            if (pos < indices[k]) {
                                                result = null;
                                                break;
                                            } else if (result.index <= indices[k + 1]) {
                                                result = regex1.exec(RegExp.input);
                                                pos = -1;
                                                break;
                                            }
                                        }
                                        if (pos > -1) {

                                            var strPrefixo = infraFormatarXML(strSemHTML.substr(0, pos));
                                            strPrefixo = strPrefixo.replace(/&lt;(\/)*b&gt;/g, '<$1b>');
                                            var strTermoNegrito = infraFormatarXML(strSemHTML.substr(pos, valor.length));
                                            var strSufixo = infraFormatarXML(strSemHTML.substr(pos + valor.length));
                                            strSufixo = strSufixo.replace(/&lt;(\/)*b&gt;/g, '<$1b>');
                                            a.innerHTML = strPrefixo + strNegritoIni + strTermoNegrito + strNegritoFim + strSufixo;
                                            indices = getArrPosicaoTagsNegrito(infraRemoverFormatacaoXML(a.innerHTML));
                                            var strSemHTML = infraRemoverFormatacaoXML(a.innerHTML);
                                            var result = regex1.exec(infraRetirarAcentos(strSemHTML.toUpperCase()));
                                        }
                                    }
                                }
                            }
                        }

                        a.innerHTML = fmtGrupoIni + fmtIdentacao + a.innerHTML + fmtGrupoFim;

                        a.innerTEXT = unescape(descricao);
                        li.appendChild(a);
                        ul.appendChild(li);

                        //alert(a.innerHTML);

                        a = null;
                        li = null;
                    }
                }
            } else {
                me.hideDiv();
            }

            if (!me.mostrarAviso) {
                me.ocultarProcessando();
            }

            xml = null;
            itens = null;
            ul = null;
        }
    }

    this.ocultarProcessando = function () {
        me.elem.className = me.elem.className.replace(/(?:^|\s)infraProcessando(?!\S)/g, '');
    }

    this.escolher = function (indice) {
        me.selecionar(unescape(me.arrItens[indice]['id']), unescape(me.arrItens[indice]['descricao']), unescape(me.arrItens[indice]['complemento']));
        me.hideDiv();
    }

    this.selecionar = function (id, descricao, complemento) {
        if (complemento == undefined) {
            complemento = null;
        }

        if (id != '' && descricao != '') {
            me.hdn.value = id;
            me.elem.value = descricao;

            if (me.marcarSelecao) {
                infraAjaxMarcarSelecao(me.elem);
            }

        }

        if (me.processarResultado != null) {
            me.processarResultado(id, descricao, complemento);
        }

    }

    this.limpar = function (bolTexto) {
        me.hdn.value = '';
        if (bolTexto == undefined || bolTexto == true) {
            if (me.limparCampo) {
                me.elem.value = '';
            }
        }

        if (me.marcarSelecao) {
            infraAjaxDesmarcarSelecao(me.elem);
        }

        if (me.processarResultado != null) {
            me.processarResultado('', '', '');
        }
    }

    this.posicionar = function () {
        var el = me.elem;
        var x = me.offsetX;
        var y = me.offsetY + el.offsetHeight;


        //Walk up the DOM and add up all of the offset positions.
        while (el.offsetParent && el.id != 'divInfraAreaTelaD' && el.tagName.toUpperCase() != 'BODY') {
            x += el.offsetLeft;
            y += el.offsetTop;

            if (INFRA_CHROME || INFRA_EDGE >= 83) {
                if (el.nodeName == 'FIELDSET') {
                    var legends = el.getElementsByTagName('legend');
                    if (legends.length == 1) {
                        y += legends[0].offsetHeight;
                    }
                }
            }

            el = el.offsetParent;

        }
        if (el.id != 'divInfraAreaTelaD') {
            x += el.offsetLeft;
            y += el.offsetTop;
        }

        me.div.style.left = x + 'px';
        me.div.style.top = y + 'px';

        el = null;
    };

    this.hideDiv = function () {
        me.highlighted = null;
        me.div.style.display = 'none';
        me.handleSelects('');
    }

    this.showDiv = function () {
        me.highlighted = null;
        me.posicionar();
        me.handleSelects('none');
        me.div.style.display = 'block';
        me.div.style.zIndex = 1000;

    }

    this.handleSelects = function (state) {
        if (INFRA_IE > 0 && INFRA_IE < 7) {
            var selects = document.getElementsByTagName('SELECT');
            for (var i = 0; i < selects.length; i++) {
                selects[i].style.display = state;
            }
            selects = null;
        }
    }

    if (window.attachEvent) { //Limpar as referências do IE
        window.attachEvent("onunload", function () {
            me.hdn = null;
            me.elem = null;
            me.highlighted = null;
            me.arrItens = null;
            me.ajaxReq = null;
            me.div = null;
            me = null;
        });
    }

}

function infraAjaxMontarSelectDependente(selPai, selFilho, url) {
    var me = this;
    this.objSelPai = infraGetElementById(selPai);
    this.objSelFilho = infraGetElementById(selFilho);
    this.ajaxTarget = url;
    this.prepararExecucao = null;
    this.processarResultado = null;
    this.mostrarAviso = false;
    this.tempoAviso = 0;
    this.executou = false;


    this.ajaxReq = infraAjaxCriarRequest();

    /*
    this.objSelPai.onchange = function(){
      me.executar();
    }
    */

    this.iniciarExecucao = function () {
    }
    this.processarErro = function () {
    }
    this.finalizarExecucao = function () {
    }
    this.verificarExecucao = function () {
        return me.executou;
    }


    this.executar = function () {
        infraSelectLimpar(me.objSelFilho);
        return infraAjaxPost(me);
    }

    infraAdicionarEvento(this.objSelPai, "change", this.executar);

    this.processarAjax = function () {

        //xml = infraAjaxProcessarXML(me.ajaxReq,me.processarErro);
        xml = infraAjaxProcessarXML(me);

        if (typeof (xml) == 'object') {

            var itens = infraAjaxGetElementsByTagName(xml, 'option');
            //var itens = xml.getElementsByTagName('option');
            var itCnt = itens.length;

            //infraSelectLimpar(me.objSelFilho);

            if (itCnt > 0) {
                for (i = 0; i < itCnt; i++) {
                    if (itens[i].firstChild != null) {

                        id = itens[i].getAttribute("value");

                        if (INFRA_FF) {
                            texto = itens[i].textContent;
                        } else {
                            texto = itens[i].firstChild.nodeValue;
                        }

                        //No IE tags com valor em branco retornam firstChild nulo
                        //Na InfraAjax.php as tags com branco foram substituitas por %20
                        if (texto == '%20') {
                            texto = ' ';
                        }
                        infraSelectAdicionarOption(me.objSelFilho, texto, id);
                        if (itens[i].getAttribute("selected") == "selected") {
                            infraSelectSelecionarItem(me.objSelFilho, id);
                        }
                    }
                }
            }
            itens = null;
            xml = null;

            if (me.processarResultado != null) {
                me.processarResultado();
            }
        }
    }

    if (window.attachEvent) { //Limpar as referências do IE
        window.attachEvent("onunload", function () {
            me.objSelPai = null;
            me.objSelFilho = null;
            me.ajaxReq = null;
            me = null;
        });
    }
}

function infraAjaxMontarSelect(sel, url) {
    var me = this;
    this.objSel = infraGetElementById(sel);
    this.ajaxTarget = url;
    this.prepararExecucao = null;
    this.processarResultado = null;
    this.ajaxReq = infraAjaxCriarRequest();
    this.mostrarAviso = false;
    this.tempoAviso = 0;
    this.executou = false;
    this.limparSelect = true;

    this.iniciarExecucao = function () {
    }
    this.processarErro = function () {
    }
    this.finalizarExecucao = function () {
    }
    this.verificarExecucao = function () {
        return me.executou;
    }


    this.executar = function () {
        if (me.limparSelect) {
            infraSelectLimpar(me.objSel);
        }
        return infraAjaxPost(me);
    }


    this.processarAjax = function () {
        var i, j;

        //var xml = infraAjaxProcessarXML(me.ajaxReq,me.processarErro);
        xml = infraAjaxProcessarXML(me);

        if (typeof (xml) == 'object') {
            var itens = infraAjaxGetElementsByTagName(xml, 'option');

            var itCnt = itens.length;

            if (itCnt > 0) {

                var arrSel = new Array();
                for (j = 0; j < me.objSel.length; j++) {
                    arrSel[me.objSel.options[j].value] = true;
                }

                for (i = 0; i < itCnt; i++) {
                    if (itens[i].firstChild != null) {
                        id = itens[i].getAttribute("value");

                        if (INFRA_FF) {
                            texto = itens[i].textContent;
                        } else {
                            texto = itens[i].firstChild.nodeValue;
                        }

                        //No IE tags com valor em branco retornam firstChild nulo
                        //Na InfraAjax.php as tags com branco foram substituitas por %20
                        if (texto == '%20') {
                            texto = ' ';
                        }

                        if (arrSel[id] == undefined) {
                            infraSelectAdicionarOption(me.objSel, texto, id);
                            if (itens[i].getAttribute("selected") == "selected") {
                                infraSelectSelecionarItem(me.objSel, id);
                            }
                        }
                    }
                }
            }
            itens = null;
            xml = null;

            if (me.processarResultado != null) {
                me.processarResultado(itCnt);
            }
        }
    }

    if (window.attachEvent) { //Limpar as referências do IE
        window.attachEvent("onunload", function () {
            me.objSel = null;
            me.ajaxReq = null;
            me = null;
        });
    }
}

/////////////////////////////////////////////////

function infraAjaxComplementar(obj, url) {

    var me = this;
    this.prepararExecucao = null;
    this.processarResultado = null;
    this.complementos = null;
    this.ajaxTarget = url;
    this.marcarSelecao = true;
    this.mostrarAviso = false;
    this.tempoAviso = 0;
    this.executou = false;
    this.limparCampo = true;
    this.ultimaExecucao = null;
    this.tamanhoMinimo = 1;
    this.offsetX = 0;
    this.offsetY = 0;

    //Crate AJAX Request
    this.ajaxReq = infraAjaxCriarRequest();

    if (obj == null) {
        this.elem = null;
    } else {
        this.elem = infraGetElementById(obj);

        //Keycodes que devem ser monitorados
        var TAB = 9;
        var ESC = 27;
        var KEYUP = 38;
        var KEYDN = 40;
        var KEYLEFT = 37;
        var KEYRIGHT = 39;
        var ENTER = 13;
        var KEY_V = 86


        //Desabilitar autocomplete IE

        if (this.elem.type == 'text') {
            this.elem.setAttribute("autocomplete", "off");
        }

        //Rotina no KEYUP (pegar input)
        //this.elem.onkeyup = function(ev)
        if (this.elem.type == 'text') {

            this.elem.onkeydown = function (ev) {
                me.executou = false;

                var key = infraGetCodigoTecla(ev);

                switch (key) {
                    case ESC:
                    case KEYUP:
                    case KEYDN:
                    case KEYLEFT:
                    case KEYRIGHT:
                        return;

                    case ENTER:
                    case TAB:
                        /*
                        case KEY_V:


                          if (key==KEY_V){
                            if (INFRA_IE > 0){
                              if (!window.event.ctrlKey){
                                return true;
                              }
                            }else{
                              return true;
                            }
                          }
                        */
                        if (me.elem.value.length < me.tamanhoMinimo) {
                            return true;
                        }

                        var novaExecucao = null;
                        if (typeof (me.prepararExecucao) == 'function') {
                            novaExecucao = me.prepararExecucao();
                            if (!novaExecucao || novaExecucao == me.ultimaExecucao) {
                                if (key == TAB) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }

                        //Limpa tudo menos texto
                        me.limpar(false);

                        //Verificar tamanho mínimo
                        if (me.elem.value.length >= 1) {
                            me.executar();
                        }

                        me.ultimaExecucao = novaExecucao;

                        if (key == TAB) {
                            return true;
                        } else {
                            return false;
                        }

                        break;

                    default:
                        //limpa tudo menos texto
                        me.limpar(false);
                }
            };
        } else {

            this.mudouValor = function () {

                me.executou = false;
                var novaExecucao = null;
                if (typeof (me.prepararExecucao) == 'function') {
                    novaExecucao = me.prepararExecucao();
                    if (!novaExecucao || novaExecucao == me.ultimaExecucao) {
                        return;
                    }
                }
                me.limpar(false);
                me.executar();
                me.ultimaExecucao = novaExecucao;
            }

            infraAdicionarEvento(this.elem, "change", this.mudouValor);
        }
    }

    this.iniciarExecucao = function () {
    }
    this.processarErro = function () {
    }
    this.finalizarExecucao = function () {
    }
    this.verificarExecucao = function () {
        return me.executou;
    }

    this.executar = function () {
        return infraAjaxPost(me);
    }
    /*
    if (this.elem.type=='text'){
      this.elem.onblur = function() {
        if (me.complementos==null){
          //limpa tudo
          me.limpar();
        }
      }
    }
    */
    //Ajax return function
    this.processarAjax = function () {

        var texto = null;

        //xml = infraAjaxProcessarXML(me.ajaxReq,me.processarErro);
        xml = infraAjaxProcessarXML(me);

        if (typeof (xml) == 'object') {

            me.complementos = null;
            var itens = infraAjaxGetElementsByTagName(xml, 'complemento');
            //var itens = xml.getElementsByTagName('complemento');
            var itCnt = itens.length;
            if (itCnt > 0) {
                me.complementos = new Array();
                for (i = 0; i < itCnt; i++) {
                    if (itens[i].firstChild != null) {

                        if (INFRA_FF) {
                            texto = itens[i].textContent;
                        } else {
                            texto = itens[i].firstChild.nodeValue;
                        }

                        me.complementos[itens[i].getAttribute("nome")] = infraRemoverFormatacaoXML(texto);
                    }
                }
            }
            me.selecionar(me.complementos);
            xml = null;
        }
    }

    this.limpar = function (bolTexto) {

        if (bolTexto == undefined || bolTexto == true) {
            if (me.limparCampo && me.elem != null) {
                me.elem.value = '';
            }
        }
        me.complementos = null;

        if (me.marcarSelecao && me.elem != null && me.elem.type == 'text') {
            infraAjaxDesmarcarSelecao(me.elem);
        }

        me.ultimaExecucao = null;
        if (typeof (me.processarResultado) == 'function') {
            me.processarResultado(null, false);
        }
    }

    this.selecionar = function (complementos) {

        if (complementos != null) {
            me.complementos = complementos;

            if (me.marcarSelecao && me.elem != null && me.elem.type == 'text') {
                infraAjaxMarcarSelecao(me.elem);
            }

        }
        if (typeof (me.processarResultado) == 'function') {
            me.processarResultado(complementos, true);
        }
    }


    if (window.attachEvent) { //Limpar as referências do IE
        window.attachEvent("onunload", function () {
            me.elem = null;
            me.complementos = null;
            me.ajaxTarget = null;
            me.ajaxReq = null;
            me = null;
        });
    }
}

function infraAjaxMontarCheckboxGrupo(div, url) {
    // div, elemento pai dos checkboxes
    var me = this;
    this.objDiv = infraGetElementById(div);
    this.ajaxTarget = url;
    this.prepararExecucao = null;
    this.processarResultado = null;
    this.ajaxReq = infraAjaxCriarRequest();
    this.mostrarAviso = false;
    this.tempoAviso = 0;
    this.executou = false;
    this.limparCheckbox = true;
    this.async = false;

    this.iniciarExecucao = function () {
    }
    this.processarErro = function () {
    }
    this.finalizarExecucao = function () {
    }
    this.verificarExecucao = function () {
        return me.executou;
    }

    this.executar = function () {
        if (me.limparCheckbox) {
            infraCheckboxLimpar(me.objDiv);
        }
        return infraAjaxPost(me);
    }

    this.processarAjax = function () {

        var i, j;

        //var xml = infraAjaxProcessarXML(me.ajaxReq,me.processarErro);
        xml = infraAjaxProcessarXML(me);

        if (typeof (xml) == 'object') {

            var itens = infraAjaxGetElementsByTagName(xml, 'item');
            var itCnt = itens.length;
            if (itCnt > 0) {
                for (i = 0; i < itCnt; i++) {
                    if (itens[i].firstChild != null) {
                        // <item checked="checked" name="name" value="value">text</item> // modelo XML
                        valor = itens[i].getAttribute("value");
                        nome = itens[i].getAttribute("name");

                        if (INFRA_FF) {
                            texto = itens[i].textContent;
                        } else {
                            texto = itens[i].firstChild.nodeValue;
                        }

                        //No IE tags com valor em branco retornam firstChild nulo
                        //Na InfraAjax.php as tags com branco foram substituitas por %20
                        if (texto == '%20') {
                            texto = ' ';
                        }

                        infraCheckboxAdicionarItem(me.objDiv, texto, valor, nome);
                        if (itens[i].getAttribute("checked") == "checked") {
                            infraCheckboxSelecionarItem(me.objDiv, valor);
                        }
                    }
                }
            }
            itens = null;
            xml = null;

            if (me.processarResultado != null) {
                me.processarResultado(itCnt);
            }

        }

    }

    if (window.attachEvent) { //Limpar as referências do IE
        window.attachEvent("onunload", function () {
            me.objDiv = null;
            me.ajaxReq = null;
            me = null;
        });
    }
}

function infraCheckboxLimpar(obj) {
    area = infraGetElementById(obj);
    area.innerHTML = '';
}

function infraCheckboxAdicionarItem(obj, text, value, name) {
    area = infraGetElementById(obj);
    div = document.createElement('div');

    input = document.createElement('input');
    input.type = "checkbox";
    input.className = "infraCheckbox";
    input.name = name;
    input.value = value;

    label = document.createElement('label');
    label.className = "infraLabelCheckbox";
    label.setAttribute('for', name);
    label.innerHTML = text;

    div.appendChild(input);
    div.appendChild(label);
    area.appendChild(div);
}

function infraCheckboxSelecionarItem(obj, valor) {
    var checks = infraGetElementById(obj).getElementsByTagName('input');
    for (var i = 0; i < checks.length; i++) {
        if (checks[i].value == valor) {
            checks[i].checked = 'checked';
        }
    }
}

function infraAjaxMontarPostPadraoCheckbox(valorItensSelecionados) {
    // valores dos checkboxes selecionados separados por vírgula (,)
    var post = '';
    if (valorItensSelecionados) {
        post += 'valorItensSelecionados=' + valorItensSelecionados;
    }
    return post;
}

function infraCheckboxSelecionado(checkbox) {
    objInput = document.getElementsByTagName('input');
    selecionados = 0;
    if (objInput.length > 0) {
        for (i = 0; i < objInput.length; i++) {
            if (objInput[i].type == 'checkbox' && objInput[i].name == checkbox) {
                if (objInput[i].checked) selecionados++;
            }
        }
    }
    if (selecionados > 0) return true;
    return false;
}
