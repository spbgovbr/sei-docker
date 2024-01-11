<?php

use TRF4\UI\UI;

?>

<form action="/" class="needs-validation" novalidate>

    <?php

    echo UI::inputText('Nome', 'nome_n')->required();

    echo UI::date('Data de nascimento', 'data_nascimento')->required('Você deve informar uma data válida');

    echo UI::inputText('Endereço', 'endereco')
        ->required('Preencha com pelo menos 4 caracteres alfanuméricos');

    echo UI::select('UF', 'my_uf', ['RS', 'PR', 'SC'])
        ->placeholder('Escolha um estado')
        ->required();

    echo UI::textarea('Observações (opcional)', 'my_textarea');

    echo UI::button('Enviar')
        ->primary()
        ->type('submit');

    ?>

</form>
