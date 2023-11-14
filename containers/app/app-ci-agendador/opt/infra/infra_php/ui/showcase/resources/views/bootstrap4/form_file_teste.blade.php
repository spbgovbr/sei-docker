@extends('templates.app')
<?php 
use TRF4\UI\UI;
use TRF4\UI\Form\FileUtils;
use TRF4\UI\Form\FileFactory;

# envia os campos FILE do formulário para a classe utils e retorna formatado para instanciar com file factory
$files_arr = FileUtils::montaArray($_FILES);

if(sizeof($files_arr)>1){
	$file = FileFactory::instanciarVarios($files_arr);
} else {
	# pega a primeira posição, pois a estrutura segue a mesma, independente da quantidade de arquivos
	$file = FileFactory::instanciar($files_arr['0_0']);
}

var_dump($file);

?>
