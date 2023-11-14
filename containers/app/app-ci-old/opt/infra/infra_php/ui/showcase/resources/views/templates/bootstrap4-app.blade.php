@extends('templates.app')

@section('includes')
    <script src="/js/bootstrap4-app.js"></script>
    <link href="/css/bootstrap4-app.css" rel="stylesheet">
@endsection

@section('body-content')
    <nav class="navbar navbar-expand-lg navbar-light sticky-top d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <a class="navbar-brand" href="#"> TRF4\UI <small class="text-muted pl-2">{{ config('app.version') }}</small>
            </a>
        </div>


        <ul class="nav">
            @foreach([
                '/' => 'Features',
                '/outros'=>'Outros exemplos',
                '/releases'=>'Releases',
            ] as $view => $label)
                <li class="nav-item">
                    <a class="nav-link {{ Request::path() == $view ? 'disabled active' : '' }}" href="{{ Request::path() == $view ? '' : $view }}">{{ $label }}</a>
                </li>
            @endforeach
        </ul>

        @include('_renderersForm')

        <div class="custom-control custom-switch d-flex align-items-center">
            <input type="checkbox" class="custom-control-input" id="darkSwitch" title="Dark mode">
            <label title="Dark Mode" class="custom-control-label" for="darkSwitch"><i class="material-icons">nights_stay</i></label>

            <a class="gitlab-logo ml-4"
               target="_blank"
               title="Ver no GitLab"
               href="https://git.trf4.jus.br/infra_php/infra_php_fontes/blob/desenv/infra_php/ui"></a>
        </div>

    </nav>

    <div class="container-fluid">
        @yield('content')
    </div>


    @include('footer-bs4')
@endsection


