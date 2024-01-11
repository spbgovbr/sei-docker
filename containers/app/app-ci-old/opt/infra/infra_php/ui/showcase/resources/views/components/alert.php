<?php

use TRF4\UI\UI;

echo UI::alert('<span><strong>Alerta </strong></span><span>de sucesso.</span>')->success();

echo UI::alert('<span><strong>Alerta </strong></span><span>de perigo.</span>')->danger();

echo UI::alert('<span><strong>Alerta </strong></span><span>de informação.</span>')->info();

echo UI::alert('<span><strong>Alerta </strong></span><span>de aviso/atenção.</span>')->warning();

echo UI::alert('<span><strong>Alerta </strong></span><span>secundário.</span>')->secondary();

echo UI::alert('<span><strong>Alerta </strong></span><span>secundário.</span>');
