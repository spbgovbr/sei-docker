@extends($template)


@section('content')
    <div class="container-fluid" id="wrapper">
        <div class="row">

            <div id="sidebar-wrapper">
                @include("docs.sidebar.main")
            </div>


            <div id="page-content-wrapper" class="col container-fluid">
                @include('docs.content.main')
                 <div class="pt-2 text-center">
                    <code>
                        <small> Documentação gerada a partir de casos de teste, afinal #cleancodematters :)
                            <br> Mais informações <a href="https://git.trf4.jus.br/infra_php/infra_php_fontes/blob/desenv/infra_php/ui/README.md#showcase">no README</a>
                         </small>
                    </code>
                </div>
            </div>
        </div>
    </div>
@endsection