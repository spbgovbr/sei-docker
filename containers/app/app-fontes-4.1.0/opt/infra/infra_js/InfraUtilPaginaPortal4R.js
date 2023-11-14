function inicializar(strAviso) {
    var numTopoBotao = 184;
    var numDescontoUltimo = 128;
    var numLarguraTela = infraClientWidth();
    if (numLarguraTela < 900) {
        numMargemBotao1 = 5;
    } else {
        var numMargemBotao1 = (numLarguraTela - 1000 - 16) / 2 + 110;
    }
    if (infraNavegador() == 'IE') {
        numMargemBotao1 = numMargemBotao1 + 0;
    }
    if (infraNavegador() == 'FF') {
        numTopoBotao = 184;
        numDescontoUltimo = 138;
    }
    var numMargemBotao2 = numMargemBotao1 + 113;
    var numMargemBotao3 = numMargemBotao1 + 255;
    var numMargemBotao4 = numMargemBotao1 + 440;
    var numMargemBotao5 = numMargemBotao1 + 613;
    var numMargemBotao6 = numMargemBotao1 + 800;
    document.getElementById('divBotaoMenu1').style.left = numMargemBotao1 + 'px';
    document.getElementById('divBotaoMenu2').style.left = numMargemBotao2 + 'px';
    document.getElementById('divBotaoMenu3').style.left = numMargemBotao3 + 'px';
    document.getElementById('divBotaoMenu4').style.left = numMargemBotao4 + 'px';
    document.getElementById('divBotaoMenu5').style.left = numMargemBotao5 + 'px';
    document.getElementById('divBotaoMenu6').style.left = numMargemBotao6 + 'px';
    document.getElementById('divDadosMenu1').style.left = numMargemBotao1 + 'px';
    document.getElementById('divDadosMenu1').style.top = numTopoBotao + 'px';
    document.getElementById('divDadosMenu2').style.left = numMargemBotao2 - 97 + 'px';
    document.getElementById('divDadosMenu2').style.top = numTopoBotao + 'px';
    document.getElementById('divDadosMenu3').style.left = numMargemBotao3 + 'px';
    document.getElementById('divDadosMenu3').style.top = numTopoBotao + 'px';
    document.getElementById('divDadosMenu4').style.left = numMargemBotao4 + 'px';
    document.getElementById('divDadosMenu4').style.top = numTopoBotao + 'px';
    document.getElementById('divDadosMenu5').style.left = numMargemBotao5 + 'px';
    document.getElementById('divDadosMenu5').style.top = numTopoBotao + 'px';
    document.getElementById('divDadosMenu6').style.left = numMargemBotao6 - numDescontoUltimo + 'px';
    document.getElementById('divDadosMenu6').style.top = numTopoBotao + 'px';
    if (typeof strAviso != "undefined") {
        alert(strAviso);
    }
}

function ampliarBotaoMenu(numId) {
    if (numId == 1) {
        document.getElementById('divCantoDS' + numId).style.backgroundImage = 'url(https://www2.trf4.jus.br/trf4/imagens/canto_ds_verde_botao_menu.gif)';
    }
    if ((numId == 2) || (numId == 4) || (numId == 6)) {
        document.getElementById('divCantoDI' + numId).style.visibility = 'hidden';
        document.getElementById('divCantoES' + numId).style.backgroundImage = 'url(https://www2.trf4.jus.br/trf4/imagens/canto_es_verde_botao_menu.gif)';
    }
    if ((numId == 3) || (numId == 5)) {
        document.getElementById('divCantoEI' + numId).style.visibility = 'hidden';
        document.getElementById('divCantoDS' + numId).style.backgroundImage = 'url(https://www2.trf4.jus.br/trf4/imagens/canto_ds_verde_botao_menu.gif)';
    }
    document.getElementById('divBotaoMenu' + numId).style.height = '29px';
    document.getElementById('divBotaoMenu' + numId).style.backgroundColor = '#E1E9DE';
}

function reduzirBotaoMenu(numId) {
    if (numId == 1) {
        document.getElementById('divCantoDS' + numId).style.backgroundImage = 'url(https://www2.trf4.jus.br/trf4/imagens/canto_ds_botao_menu.gif)';
    }
    if ((numId == 2) || (numId == 4) || (numId == 6)) {
        document.getElementById('divCantoDI' + numId).style.visibility = 'visible';
        document.getElementById('divCantoES' + numId).style.backgroundImage = 'url(https://www2.trf4.jus.br/trf4/imagens/canto_es_botao_menu.gif)';
    }
    if ((numId == 3) || (numId == 5)) {
        document.getElementById('divCantoEI' + numId).style.visibility = 'visible';
        document.getElementById('divCantoDS' + numId).style.backgroundImage = 'url(https://www2.trf4.jus.br/trf4/imagens/canto_ds_botao_menu.gif)';
    }
    document.getElementById('divBotaoMenu' + numId).style.height = '25px';
    document.getElementById('divBotaoMenu' + numId).style.backgroundColor = '#DBDBDB';
}

function mostrarDivDadosMenu(numId) {
    ampliarBotaoMenu(numId);
    document.getElementById('divDadosMenu' + numId).style.visibility = 'visible';
}

function esconderDivDadosMenu(numId) {
    reduzirBotaoMenu(numId);
    document.getElementById('divDadosMenu' + numId).style.visibility = 'hidden';
}

function infraClientWidth() {
    return window.innerWidth ? window.innerWidth :
        document.documentElement ? document.documentElement.clientWidth :
            document.body ? document.body.clientWidth :
                window.screen.width;
}

function infraAdicionarEvento(obj, evento, funcao) {
    if (obj.attachEvent) {
        obj.attachEvent('on' + evento, funcao);
    } else if (obj.addEventListener) {
        obj.addEventListener(evento, funcao, false);
    }
}

function infraProcessarResize() {
    infraResize();
    infraAdicionarEvento(window, 'resize', infraResize);
}

function infraResize() {
    inicializar();
}

function infraNavegador() {
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf('msie') != -1) {
        return 'IE';
    } else if (ua.indexOf('firefox') != -1) {
        return 'FF';
    } else {
        return '';
    }
}

var tamanhos = new Array(12, 13, 14, 15, 16, 17, 18);
if (isNaN(contador)) {
    var contador = 0;
}

function alterarTamanhoFonte(zoom) {
    var bolAlterar = false;
    if (zoom == '+') {
        if (contador < tamanhos.length - 1) {
            contador++;
            bolAlterar = true;
        }
    }
    if (zoom == '-') {
        if (contador > 0) {
            contador--;
            bolAlterar = true;
        }
    }
    if (zoom == 'p') {
        contador = 0;
        bolAlterar = true;
    }
    if (bolAlterar) {
        document.getElementById('divAreaGlobalConteudo').style.fontSize = tamanhos[contador] + 'px';
        document.getElementById('divColunaDireita').style.fontSize = (tamanhos[contador] - 2) + 'px';
        for (i = 1; i <= 6; i++) {
            document.getElementById('divRodape' + i).style.fontSize = (tamanhos[contador] - 2) + 'px';
        }
        ajustarSessaoTamanhoFonte(contador);
    }
}

var xmlHttp;

function ajustarSessaoTamanhoFonte(intTamanho) {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert('Este browser não suporta HTTPRequest');
        return;
    }
    xmlHttp.onreadystatechange = stateChanged;
    xmlHttp.open('GET', 'infra/InfraAJAX.php?acao=alterar_tamanho_fonte&tamanho=' + intTamanho, true);
    xmlHttp.send(null);
}

function stateChanged() {
    if ((xmlHttp.readyState == 4) || (xmlHttp.readyState == 'complete')) {
        /*try {
          if(xmlHttp.status == 200) {
            alert(xmlHttp.responseText);
          }
        } catch(componentfailure) {}*/
    }
}

function GetXmlHttpObject() {
    var objXMLHttp = null;
    if (window.XMLHttpRequest) {
        objXMLHttp = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        objXMLHttp = new ActiveXObject('Microsoft.XMLHTTP');
    }
    return objXMLHttp;
}

function validarTeclaAcessibilidade(evt, strAcao) {
    if (INFRA_IE || (INFRA_EDGE > 0 && INFRA_EDGE < 83)) {
        var numTecla = window.event.keyCode;
    } else if (evt) {
        var numTecla = ((ev.which) ? ev.which : ev.keyCode);
    }
    if (numTecla == 13) {
        alterarTamanhoFonte(strAcao);
    }
}

var novaJanela = null;

function abrirJanela(arquivo, largura, altura) {
    novaJanela = window.open(arquivo, 'novaJanela', 'location=0,status=0,resizable=1,scrollbars=1,toolbar=1,width=' + largura + ',height=' + altura);
    novaJanela.focus();
    centralizarJanela(largura, altura);
}

function abrirJanelaComControles(arquivo, largura, altura) {
    novaJanela = window.open(arquivo, 'novaJanela', 'location=0,status=0,resizable=1,scrollbars=1,toolbar=1,width=' + largura + ',height=' + altura);
    novaJanela.focus();
    centralizarJanela(largura, altura);
}

function centralizarJanela(largura, altura) {
    var esquerda = (screen.availWidth / 2) - (largura / 2);
    var topo = (screen.availHeight / 2) - (altura / 2);
    try {
        novaJanela.moveTo(esquerda - 10, topo - 10);
    } catch (exc) {
    }
}

function montarLayerDetalhes() {
    var divDetalhes = document.getElementById('divDetalhes');
    var strHtml = divDetalhes.innerHTML;
    var divFundo = document.createElement('div');
    divFundo.id = 'divInfraAvisoFundo';
    divFundo.className = 'infraFundoTransparente';
    divFundo.onclick = function () {
        esconderDetalhes()
    };
    var div = document.createElement('div');
    div.id = 'divInfraAviso';
    div.className = 'infraAviso';
    div.innerHTML = strHtml;
    divFundo.appendChild(div);
    if (INFRA_IE > 0 && INFRA_IE < 7) {
        var ifr = document.createElement('iframe');
        ifr.className = 'infraFundoIE';
        divFundo.appendChild(ifr);
    }
    document.body.appendChild(divFundo);

    var divFundo = document.getElementById('divInfraAvisoFundo');
    if (INFRA_IE == 0 || INFRA_IE >= 7) {
        divFundo.style.position = 'fixed';
    }

    //divFundo.style.width = (screen.width - 21) + 'px';
    divFundo.style.width = infraClientWidth() + 'px';
    //divFundo.style.height = screen.height + 'px';
    divFundo.style.height = infraClientHeight() + 'px';
    divFundo.style.visibility = 'visible';

    var divAviso = document.getElementById('divInfraAviso');
    divAviso.style.top = Math.floor(infraClientHeight() / 3) + 'px';
    divAviso.style.left = Math.floor((infraClientWidth() - 490) / 2) + 'px';
    divAviso.style.width = '610px';
}

function esconderDetalhes() {
    document.getElementById('divInfraAvisoFundo').style.visibility = 'hidden';
}

function imprimirDIV(strIdDiv) {
    abrirJanelaComControles('infra/InfraImpressao.php?div=' + strIdDiv, 750, 500);
}