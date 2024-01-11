var INFRA_ICONE_AGUARDAR = INFRA_PATH_IMAGENS + '/aguarde.gif';
var INFRA_ICONE_ALTERAR = INFRA_PATH_IMAGENS + '/alterar.gif';
var INFRA_ICONE_REMOVER = INFRA_PATH_IMAGENS + '/remover.gif';
var INFRA_ICONE_MOVER_ACIMA = INFRA_PATH_IMAGENS + '/seta_acima_select.gif';
var INFRA_ICONE_MOVER_ABAIXO = INFRA_PATH_IMAGENS + '/seta_abaixo_select.gif';

if (typeof jQuery != "undefined") {
    $(document).ready(function () {
        if (INFRA_LUPA_TIPO_JANELA == 2) {
            btnFecharSelecao = document.getElementById("btnFecharSelecao");
            if (btnFecharSelecao != null) {
                btnFecharSelecao.onclick = function () {
                    infraFecharJanelaSelecao()
                };
            }
        }
    });
}