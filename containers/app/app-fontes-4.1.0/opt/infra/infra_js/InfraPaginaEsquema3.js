var INFRA_PAGINA_ESQUEMA3 = true;
var INFRA_ICONE_AGUARDAR = INFRA_PATH_SVG + '/aguarde.svg';
var INFRA_ICONE_ALTERAR = INFRA_PATH_SVG + '/alterar.svg';
var INFRA_ICONE_REMOVER = INFRA_PATH_SVG + '/remover.svg';
var INFRA_ICONE_MOVER_ACIMA = INFRA_PATH_SVG + '/mover_acima.svg';
var INFRA_ICONE_MOVER_ABAIXO = INFRA_PATH_SVG + '/mover_abaixo.svg';

var btnInfraTopo;
var divInfraMoverTopo;
var infraExibirMoverScroll;
var infraOcultarMoverScroll;

$(document).ready(function () {

    $(".infraCheckbox").each(function (index) {
        if ($(this).css("display") != "none") {
            var div = $('<div class="infraCheckboxDiv " ></div>');

            var id = $(this).attr("id");
            var label = $('<label class="infraCheckboxLabel " for="' + id + '"></label>');

            $(this).removeClass("infraCheckbox");
            $(this).addClass("infraCheckboxInput");

            $(this).wrap(div);
            label.insertAfter($(this));
        }
    });

    $(".infraRadio").each(function (index) {
        if ($(this).css("display") != "none") {
            var div = $('<div class="infraRadioDiv " ></div>');

            var id = $(this).attr("id");
            var label = $('<label class="infraRadioLabel" for="' + id + '"></label>');

            $(this).removeClass("infraRadio");
            $(this).addClass("infraRadioInput");

            $(this).wrap(div);
            label.insertAfter($(this));
        }
    });

    $(document).bind('keydown', function (e) {

        var tecla = infraGetCodigoTecla(e);

        if (typeof (infraSistemaTeclasAtalho) == 'function') {
            infraSistemaTeclasAtalho(tecla, e);
        } else {
            infraPadraoTeclasAtalho(tecla, e);
        }
    });

    //botao topo
    btnInfraTopo = $(document.getElementById("btnInfraTopo"));

    if (!divInfraMoverTopo) {
        if (document.getElementById("divInfraAreaTelaD")) {
            divInfraMoverTopo = document.getElementById("divInfraAreaTelaD");
        }
    }

    if (divInfraMoverTopo) {
        divInfraMoverTopo.onscroll = function () {
            infraTestarScrollTopo();
        };
    } else {
        window.onscroll = function () {
            infraTestarScrollTopo()
        };
    }

    if (INFRA_LUPA_TIPO_JANELA == 2) {
        var botoes = document.querySelectorAll("[id=btnFecharSelecao]");
        for (var i = 0; i < botoes.length; i++) {
            botoes[i].onclick = function () {
                infraFecharJanelaSelecao()
            };
        }
    }
});

function infraTestarScrollTopo() {

    show = false;

    if (divInfraMoverTopo) {
        if (divInfraMoverTopo.scrollTop > 20) {
            show = true;
        }
    } else {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            show = true;
        }
    }

    if (show) {

        btnInfraTopo.show('fast');

        if ($("#divInfraBtnTopo").length == 0) {
            var div = $('<div id="divInfraBtnTopo" style="height: 60px;"  ></div>');
            if (divInfraMoverTopo) {
                $(divInfraMoverTopo).append(div);
            } else {
                $(document.body).append(div);
            }
        }

        if (infraExibirMoverScroll != null) {
            infraExibirMoverScroll();
        }
    } else {

        btnInfraTopo.hide('fast');

        $("#divInfraBtnTopo").remove();

        if (infraOcultarMoverScroll != null) {
            infraOcultarMoverScroll();
        }
    }
}

// When the user clicks on the button, scroll to the top of the document
function infraMoverParaTopo() {
    if (divInfraMoverTopo) {
        $(divInfraMoverTopo).animate({scrollTop: 0}, 600);
    } else {
        $(document.body).animate({scrollTop: 0}, 600);
        $(document.documentElement).animate({scrollTop: 0}, 600);
    }
}

function infraIsBreakpointBootstrap(alias) {
    return $('#infraDivBootstrap-' + alias).is(':visible');
}

function infraPadraoTeclasAtalho(tecla, e) {

    if (e.altKey) {

        switch (tecla) {

            case 112: //F1
                if (document.getElementById('lnkInfraAcessibilidadeSistema') != null) {
                    $('#lnkInfraAcessibilidadeSistema').click();
                    return true;
                }
                break;

            case 77: //M
                if (infraFocarPesquisaMenu()) {
                    return true;
                }
                break;

            case 120: //F9
                if (infraClicarMenuBootstrap()) {
                    return true;
                }
                break;

            case 122: //F11
                if (document.getElementById('lnkInfraUnidade') != null) {
                    $('#lnkInfraUnidade').click();
                    return true;
                }
                break;

            case 123: //F12
                if (document.getElementById('lnkInfraSairSistema') != null) {
                    document.getElementById('lnkInfraSairSistema').focus();
                    return true;
                }
                break;

            case 84: //T
                if (document.getElementById('divInfraBarraLocalizacao') != null) {
                    document.getElementById('divInfraBarraLocalizacao').focus();
                    return true;
                }
                break;

            case 66: //B = Barra de comandos superior
                var divBarraComandos = document.getElementById('divInfraBarraComandosSuperior');
                if (divBarraComandos != null) {
                    var arrBotoes = divBarraComandos.getElementsByClassName('infraButton');
                    if (arrBotoes.length) {
                        arrBotoes[0].focus();
                        return true;
                    }
                }
                break;

            case 38: //SETA ACIMA
                var elem = document.activeElement;
                if (elem != null) {
                    var indice = null;
                    while (elem) {
                        if (elem instanceof HTMLTableRowElement) {
                            indice = elem.rowIndex;
                        } else if (elem instanceof HTMLTableElement) {
                            if (indice != null) {
                                if (elem.rows[indice - 1] != undefined && elem.rows[indice - 1].cells[0] != undefined) {
                                    box = elem.rows[indice - 1].cells[0].getElementsByTagName("input");
                                    if (box.length > 0 && !box[0].disabled) {
                                        box[0].focus();
                                        return true;
                                    }
                                }
                            }
                            break;
                        }
                        elem = elem.parentNode;
                    }
                }
                break;

            case 40: //SETA ABAIXO
                var elem = document.activeElement;
                if (elem != null) {
                    var indice = null;
                    while (elem) {
                        if (elem instanceof HTMLTableRowElement) {
                            indice = elem.rowIndex;
                        } else if (elem instanceof HTMLTableElement) {
                            if (indice != null) {
                                if (elem.rows[indice + 1] != undefined && elem.rows[indice + 1].cells[0] != undefined) {
                                    box = elem.rows[indice + 1].cells[0].getElementsByTagName("input");
                                    if (box.length > 0 && !box[0].disabled) {
                                        box[0].focus();
                                        return true;
                                    }
                                }
                            }
                            break;
                        }
                        elem = elem.parentNode;
                    }
                }
                break;
        }
    }

    if (tecla == 27) { //ESC
        infraFecharJanelaModal();
        return true;
    }

    if (parent.window != window && typeof (parent.infraPadraoTeclasAtalho) == 'function') {
        return parent.infraPadraoTeclasAtalho(tecla, e);
    }

    return false;
}

function infraCalcularVH() {
    $('#divInfraAreaGlobal').innerHeight($(window).innerHeight());
}

