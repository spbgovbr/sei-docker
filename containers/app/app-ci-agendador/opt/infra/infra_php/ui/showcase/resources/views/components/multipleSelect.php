<?php

use TRF4\UI\UI;

$data = [];
for ($i = 0; $i < 40; $i++) {
    $data[] = str_pad(0, 3, rand(0, 100));
}

echo UI
    ::select('Select múltiplo / com campo de busca / max width', 'mult_select', $data)
    ->width('300px')
    ->searchable()
    ->multiple();
