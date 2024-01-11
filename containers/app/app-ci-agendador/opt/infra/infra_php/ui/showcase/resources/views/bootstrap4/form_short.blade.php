@extends('templates.app')

@section('includes')
    <link href="/css/bootstrap4-app.css" rel="stylesheet">
@endsection

@section('body-content')
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">UI</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        </div>
    </nav>

    <div class="container">

        <p>
            Aqui é possível ver um exemplo de um formulário completo, fazendo uso de personalizações específicas do
            renderer Bootstrap4.
        </p>


        <form action="" class="needs-validation" novalidate>

            <?php
            use TRF4\UI\UI;
            ?>

            <div class="form-row">


                <?php

                echo UI::inputText('Nome', 'nome_n')
                    ->placeholder('José')
                    ->required()
                    ->_wrapper('class', 'col-md-6');

                echo UI::inputText('Sobrenome', 'sobrenome')
                    ->placeholder('da Silva')
                    ->required()
                    ->_wrapper('class', 'col-md-6');
                ?>

            </div>

            <?= UI::inputText('Endereço', 'input_address');?>
            <div class="form-row">

                <?php
                echo UI::inputText('Cidade', 'cidade')
                    ->_wrapper('class', 'col-md-6');

                echo UI::select('Estado', 'uf', ['RS', 'PR', 'SC'])
                    ->placeholder('Escolha...')
                    ->required()
                    ->class('w-100')
                    ->_wrapper('class', 'col-md-4');

                echo UI::inputText('CEP', 'cep')
                    ->_wrapper('class', 'col-md-2');
                ?>
            </div>

            <?php

            echo UI::inputText('Email', 'email')->required();

            echo UI::inputText('Password (todo)', 'password')->required();

            echo UI::radioGroup('Radios', 'radios', [
                UI::radio('Desmarcado', '1', 'id_opt1_1'),
                UI::radio('Marcado', '2', 'id_opt1_2')->checked(),
                UI::radio('Desabilitado', '3', 'id_opt1_3')->disabled()
            ]);

            echo UI::checkboxGroup('Checkboxes', [
                UI::checkbox('Desmarcado', null, '1'),
                UI::checkbox('Marcado', null, '2')->checked(),
                UI::checkbox('Desabilitado', null, '3')->disabled()
            ], 'my_check');

            echo UI::button('Enviar')
                ->primary()
                ->type('submit');
            ?>

        </form>
    </div>
@endsection
