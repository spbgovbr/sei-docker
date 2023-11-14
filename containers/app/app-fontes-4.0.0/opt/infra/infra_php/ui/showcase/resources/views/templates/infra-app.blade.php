@extends('templates.app')

@section('includes')
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="robots" content="noindex">
    <link href="/infra_css/infra-global-esquema.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_css/infra-global-esquema-2.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_css/esquemas/azul_celeste/infra-esquema.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_css/esquemas/azul_celeste/infra-esquema-2.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_css/infra-tooltip.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_css/infra-barra-progresso.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_css/infra-impressao-global.css" rel="stylesheet" type="text/css" media="print">
    <link href="/infra_css/infra-ajax.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_js/calendario/v1/infra-calendario.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_js/arvore/infra-arvore.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_js/mapa/infra-mapa.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_js/jquery/jquery-ui-1.11.1/jquery-ui.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_js/jquery/jquery-ui-1.11.1/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_js/jquery/jquery-ui-1.11.1/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_js/multiple-select/multiple-select.css" rel="stylesheet" type="text/css" media="all">
    <link href="/infra_js/modal/jquery.modalLink-1.0.0.css" rel="stylesheet" type="text/css" media="all">
    <script type="text/javascript">
        var INFRA_PATH_IMAGENS = '/infra_css/imagens',
            INFRA_PATH_JS = '/infra_js',
            INFRA_PATH_CSS = '/infra_css';
    </script>
    <script type="text/javascript" charset="utf-8" src="/infra_js/jquery/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/infra_js/jquery/jquery-ui-1.11.1/jquery-ui.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/infra_js/multiple-select/multiple-select.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/infra_js/ddslick/jquery.ddslick.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/infra_js/modal/jquery.modalLink-1.0.0.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraUtil.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraCookie.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraUpload.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraMenu.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraBotaoMenu.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraAcaoMenu.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraTabelaDinamica.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraLupas.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraSelectEditavel.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraAjax.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraTooltip.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/calendario/v1/InfraCalendario.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/arvore/InfraArvore.js"></script>
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/maskedpwd/MaskedPassword.min.js"></script>

    <!-- AVISO: ISSO É TEMPORÁRIO PARA PERMITIR QUE AS ABAS FUNCIONEM. VER ISSUE #38 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <!-- ....  -->
    <link href="/infra_css/infra-global-esquema.css" rel="stylesheet" type="text/css" media="all">
    <!-- ....  -->
    <script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraUtil.js"></script>
    <!-- ....  -->

    <link href="/css/infra-app.css" rel="stylesheet">
@endsection



@section('body-content')
    <div id="divInfraAreaGlobal" class="infraAreaGlobal">
        <div id="divInfraBarraSuperior" class="infraBarraSuperior">
        </div>
        <div id="divInfraBarraSistema" class="infraBarraSistema">
            <div id="divInfraBarraSistemaE" class="infraBarraSistemaE">
                <a class="navbar-brand" href="#">UI</a>
            </div>
            <div id="divInfraBarraSistemaD" class="infraBarraSistemaD">
                @include('_renderersForm')
            </div>
        </div>

        <div class="infraAreaTela">
            <div class="divInfraAreaTelaE">
            </div>
            <div class="divInfraAreaTelaD">
                @yield('content')
            </div>
        </div>
    </div>
@endsection



