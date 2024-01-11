<?php

use TRF4\UI\UI;

echo UI::iconButton()->search()->onclick('alert(1);');

/*
\TRF4\UI\Bootstrap4\IconButton::register('eye', 'eye');
\TRF4\UI\Infra\IconButton::register('eye', '/infra_css/imagem/olho.gif');


// ou ->icon('img') caso n tenha registrado

UI::iconButton()->custom('eye');
UI::iconButton()->eye()
UI::iconButton()->search();

todo talvez deva haver um modo de registrar com antecedência os tipos de ícone custom
	ex.: colocando, para cada renderer, um array de tipos custom (definidos pelo usuário no início)
	OBS.: talvez seja necessário um fallback: ex.: imagem genérica de "img n encontrada"
todo permitir setar a cor do botão
UI::iconButton()->typeSearch();
UI::iconButton(IconButton::T_SEARCH)

*/