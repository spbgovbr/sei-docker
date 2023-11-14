<?php

use TRF4\UI\UI;
use TRF4\UI\Form\FileUtils;
use TRF4\UI\Form\FileFactory;
use TRF4\UI\Form\File;

#inicializa um array para arquivos iniciais para cada campo de upload
# Array montado para fins de demonstração, nas Interfaces, pode ser montado a partir do banco de dados
$filesInit_1 = array(
					'url' => 
                        array(
                            "uploads/images/giphy.gif",
                        ), 
                    'config' => 
                        array(
                            array(
                                "caption" => "giphy.gif", 
                                "size" => 329892, 
                                "width" => "120px", 
                                "key" => 0,
                                "previewAsData" => true,
                                "url" => "/bootstrap4/deletefile"
                     		)
              			),
					'path' => 
						array(
							"uploads/images/giphy.gif"
						)
					);

$filesInit_2 = array();

if(!FileUtils::isEmpty($_FILES)){

	$k = 0;
	echo "<code>";
	# Tratamento para UPLOAD de arquivos da máquina local do usuário
	foreach ($_FILES as $fk => $file) {
		$k++;
		$ret = FileUtils::montaArray($file);
		$filesArr = $ret['data'];	
		$fileSize = $ret['size'];

		# campo file vazio
		if($fileSize == 0){
			continue;	
		}
		
		if($fileSize > 1){
			$filesFactoryArr = FileFactory::instanciarVarios($filesArr);
		} else {
			$filesFactoryArr = FileFactory::instanciar($filesArr);
		}

		// # Printa os objetos files criados a partir dos envios do formulário
		echo "<br>";
			var_dump(FileUtils::print($filesFactoryArr));
		echo "<br>";

		# Inclui os arquivos recém subidos aos arquivos de exibição iniciais
		$fileInitArr = FileUtils::uploadFiles($filesFactoryArr);

		if(isset(${'filesInit_'.$k}['url'])){
			${'filesInit_'.$k}['url'][] = $fileInitArr['url'];
		}
		if(isset($fileInitArr['path'])){
			${'filesInit_'.$k}['path'][] = $fileInitArr['path'];
		}  
		if(isset(${'filesInit_'.$k}['config'])){

			foreach ($fileInitArr['config'] as $config) {
				${'filesInit_'.$k}['config'][] = $config;
			}		
		}
		if(!isset(${'filesInit_'.$k}['config']) && !isset(${'filesInit_'.$k}['url'])){
			${'filesInit_'.$k} = $fileInitArr;
		}
	}	
	echo "</code>";
} 	
?>

<form action="/outros" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>

<input type="hidden" name="_token" id="csrf-token" value="<?php echo csrf_token() ?>" />

<?php 

	echo UI::fileUpload('Subir Arquivo', 'exemploform')
	->initFiles($filesInit_1)
	->multiple()
	->maxFiles(3)
	->required()
	;

	echo UI::fileUpload('Subir Arquivo', 'exemploform2')
	->initFiles($filesInit_2)
	->multiple()
	->maxFiles(3)
	;

	echo UI::button('Enviar')
    ->primary()
    ->type('submit');
?>
</form>