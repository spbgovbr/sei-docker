function infraOcultarMenuSistema() {
    infraMenuSistema(false, 'Ocultar');
}

function infraExibirMenuSistema() {
    infraMenuSistema(false, 'Exibir');
}

function infraMenuSistema(bolInicializar, tipo) {
    var mostrarMenu = null;
    var tamanhoDados = null;
    var title = '';

    if (bolInicializar == undefined) {
        bolInicializar = false;
    }

    var lnkMenu = document.getElementById('lnkMenuSistema');
    if (lnkMenu == null) {
        return;
    }

    var hdnCookie = document.getElementById('hdnInfraPrefixoCookie');
    if (hdnCookie == null) {
        return;
    }

    var prefixoCookie = hdnCookie.value;

    infraTooltipOcultar();

    if (bolInicializar) {

        //le do cookie
        if (infraLerCookie(prefixoCookie + '_menu_mostrar') != 'N') {
            tamanhoDados = document.getElementById("divInfraAreaTelaD").offsetWidth / document.getElementById("divInfraAreaTela").offsetWidth;
            tamanhoDados = Math.floor(tamanhoDados * Math.pow(10, 2));
            infraCriarCookie(prefixoCookie + '_menu_tamanho_dados', tamanhoDados, 1);
            title = 'Ocultar';
        } else {
            title = 'Exibir';
        }

    } else {

        if (tipo == undefined) {
            if (document.getElementById('divInfraAreaTelaE').style.display == '') {
                tipo = 'Ocultar';
            } else {
                tipo = 'Exibir';
            }
        }

        //verifica div atual
        if (tipo == 'Ocultar') {
            document.getElementById('divInfraAreaTelaE').style.display = 'none';
            document.getElementById('divInfraAreaTelaD').style.width = '99%';
            infraCriarCookie(prefixoCookie + '_menu_mostrar', 'N', 1);
            title = 'Exibir';
        } else {
            tamanhoDados = infraLerCookie(prefixoCookie + '_menu_tamanho_dados');
            document.getElementById('divInfraAreaTelaE').style.display = '';
            document.getElementById('divInfraAreaTelaD').style.width = tamanhoDados + '%';
            infraCriarCookie(prefixoCookie + '_menu_mostrar', 'S', 1);
            title = 'Ocultar';
        }

    }

    //lnkMenu.onmouseover = function() {return infraTooltipMostrar(title + ' Menu do Sistema','',100); }
    lnkMenu.title = title + ' Menu do Sistema';

}

function infraOcultarMenuSistemaEsquema() {
    infraMenuSistemaEsquema(false, 'Ocultar');
}

function infraExibirMenuSistemaEsquema() {
    infraMenuSistemaEsquema(false, 'Exibir');
}

function infraMenuSistemaEsquema(bolInicializar, tipo) {

    var mostrarMenu = null;
    var tamanhoDados = null;
    var title = '';

    if (bolInicializar == undefined) {
        bolInicializar = false;
    }


    var lnkMenu = document.getElementById('lnkInfraMenuSistema');
    if (lnkMenu == null) {
        return;
    }

    var hdnCookie = document.getElementById('hdnInfraPrefixoCookie');
    if (hdnCookie == null) {
        return;
    }

    var prefixoCookie = hdnCookie.value;

    infraTooltipOcultar();

    if (bolInicializar) {

        //le do cookie
        if (infraLerCookie(prefixoCookie + '_menu_mostrar') != 'N') {
            tamanhoDados = document.getElementById("divInfraAreaTelaD").offsetWidth / document.getElementById("divInfraAreaTela").offsetWidth;
            tamanhoDados = Math.floor(tamanhoDados * Math.pow(10, 2));
            infraCriarCookie(prefixoCookie + '_menu_tamanho_dados', tamanhoDados, 1);
            title = 'Ocultar';
        } else {
            title = 'Exibir';
        }

    } else {

        if (tipo == undefined || tipo == null) {
            if (document.getElementById('divInfraAreaTelaE').style.display == '') {
                tipo = 'Ocultar';
            } else {
                tipo = 'Exibir';
            }
        }

        if (tipo == 'Ocultar') {
            document.getElementById('divInfraAreaTelaE').style.display = 'none';
            document.getElementById('divInfraAreaTelaD').style.width = '99%';
            infraCriarCookie(prefixoCookie + '_menu_mostrar', 'N', 1);
            title = 'Exibir';
        } else {
            tamanhoDados = infraLerCookie(prefixoCookie + '_menu_tamanho_dados');
            document.getElementById('divInfraAreaTelaE').style.display = '';

            if (tamanhoDados == null) tamanhoDados = infraClientWidth() * 0.80;

            document.getElementById('divInfraAreaTelaD').style.width = tamanhoDados + '%';
            infraCriarCookie(prefixoCookie + '_menu_mostrar', 'S', 1);
            title = 'Ocultar';
        }
        infraResize();
    }

    var imgMenu = document.getElementById('imgInfraMenuSistema');
    if (imgMenu == null) {
        //lnkMenu.onmouseover = function() {return infraTooltipMostrar(title + ' Menu do Sistema','',100); }
        lnkMenu.title = title + ' Menu do Sistema';
    } else {
        imgMenu.title = title + ' Menu do Sistema';
        if (title == 'Exibir') {
            imgMenu.src = INFRA_PATH_IMAGENS + '/botao_menu_abrir.gif';
        } else {
            imgMenu.src = INFRA_PATH_IMAGENS + '/botao_menu_fechar.gif';
        }
    }
}


function infraConfigurarMenu() {

    var n = '';
    var obj = null;
    while ((obj = document.getElementById('divInfraMenu' + n)) != null) {

        if (typeof (obj) == 'object') {
            var itens = obj.getElementsByTagName("ul");
            for (i = 0; i < itens.length; i++) {

                itens[i].onmouseout = function () {
                    this.style.display = 'block';
                }

                itens[i].onmouseover = function () {
                    infraApagarMenu(this.id);
                }
            }

            var subitens = obj.getElementsByTagName("li");
            for (i = 0; i < subitens.length; i++) {

                subitens[i].onmouseover = function () {

                    var el = this;
                    var y = 0;
                    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {
                        y += el.offsetTop;
                        el = el.offsetParent;
                    }
                    y += el.offsetTop;

                    var deslocamento = (y + 40) - (infraClientHeight() + infraScrollTop())
                    if (deslocamento > 0) {
                        window.scrollBy(0, 10);
                    }
                }
            }
        }

        if (n == '') {
            n = 1;
        } else {
            n++;
        }
    }
}