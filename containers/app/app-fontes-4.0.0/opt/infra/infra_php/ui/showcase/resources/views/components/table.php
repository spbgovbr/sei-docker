<?php

use TRF4\UI\UI;

$rows = [
	[
		'Nome' => 'Amêndoas',
		'Porção' => '100g',
		'Proteínas (g)' => '21,1',
	], [
		'Nome' => 'Salmão',
		'Porção' => '100g',
		'Proteínas (g)' => 20.2
	], [
		'Nome' => 'Ovos',
		'Porção' => '2 (médios)',
		'Proteínas (g)' => 16,
	]
];

echo UI::table('Tabela', $rows)
	->addColumn('Ações', function ($row) {
		return "<a href='#'
                    onclick='alert(\"$row[Nome]\");' 
                    title='Consultar $row[Nome]'>
                    <i class='material-icons'>search</i>
                </a>";


	}/*, function ($td) {
		$td->class('text-center');
	}*/);
/*

echo UI::table('Tabela', $rows)
	->addColumn(UI::column('Ações')
		->content(function ($row) {
			return UI
				::el('a', '<i class="material-icons">search</i>')
				->onclick("alert('$row[Nome]')");
		})
		->_bodyCells('class', 'text-center')
*/