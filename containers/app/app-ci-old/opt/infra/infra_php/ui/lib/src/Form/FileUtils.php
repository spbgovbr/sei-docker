<?php

/**
 * User: eduardot_dna
 * Date: 02/10/2020
 * Time: 16:12
 */

namespace TRF4\UI\Form;

class FileUtils
{
	/**
	 * @param array $data
	 * @return array
	 */

	public static function isImage($type){

		if($type=="image/png"){
			return true;
		} else if($type=="image/bmp") {
			return true;
		} else if ($type=="image/gif") {
			return true;
		} else if ($type=="image/x-icon") {
			return true;
		} else if ($type=="image/jpeg"){
			return true;
		} else if ($type=="image/svg+xml"){
			return true;
		} else {
			return false;
		}
	}

	public static function createFile($file){
		
		$image = (self::isImage($file->mimeType))? true : false;

		if($image){
			$path = realpath("./../"."/public/uploads/images");
		} else {
			$path = realpath("./../"."/public/uploads");
		}

		if(move_uploaded_file($file->path, $path."/".$file->name)){
			return $path."/".$file->name;
		} else {
			return "Erro ao mover arquivo";
		}

	}

	public static function isEmpty(array $files){
		$vazio = true;
		foreach ($_FILES as $f => $file) {
			if(is_array($file['name'])){
				foreach ($file['name'] as $n => $name) {
					if($name){
						$vazio = false;
					}
				}
			} else {
				if($file['name']){
					$vazio = false;
				}
			}
		}
		return $vazio;
	} 

	# Formata array para ser processado pelo FileFactory
	public static function montaArray(array $file, $type = "file"): Array {
		
		$files_arr = array();
		$i = 0;

		$qtd = 0;	
		
		if($type == "file") {
			# campos múltiplos
			if(is_array($file['tmp_name'])){

				// #percorre cada arquivo pelo tmp_name
				foreach ($file['tmp_name'] as $k2 => $tmp_name) {
				
					if(!is_file($tmp_name)){
						continue;
					} 
					$qtd++;
					$fileContent = file_get_contents($tmp_name);
					$base64File = base64_encode($fileContent);
					$files_arr[$i."_".$k2]['content_base64'] 	= $base64File;	
					$files_arr[$i."_".$k2]['filename'] 			= $file['name'][$i];
					$files_arr[$i."_".$k2]['size'] 				= $file['size'][$i];
					$files_arr[$i."_".$k2]['type'] 				= $file['type'][$i]; 
					$files_arr[$i."_".$k2]['tmp_name'] 			= $tmp_name; 
					$files_arr[$i."_".$k2]['idDocumento'] 		= $i."_".$k2; 
					$i++;		
				}

			} else {
				if(is_file($file['tmp_name'])){
					$qtd++;
					$fileContent = file_get_contents($file['tmp_name']);
					$base64File = base64_encode($fileContent);
					$files_arr['content_base64'] 	= $base64File;	
					$files_arr['filename'] 			= $file['name']; 
					$files_arr['size'] 				= $file['size'];
					$files_arr['type'] 				= $file['type']; 
					$files_arr['tmp_name'] 			= $file['tmp_name'];
					$files_arr['idDocumento'] 		= 0; 
				}
			}	
		} else {
			
			foreach ($file as $k2 => $postfile) {
				if(is_file($postfile)){
					$qtd++;
					# retira apenas o nome do arquivo do path
					$fileNameArr = explode("/", $postfile);
					$fileName = $fileNameArr[sizeof($fileNameArr)-1];

					$type = mime_content_type($postfile);
					$size = filesize($postfile);
					$fileContent = file_get_contents($postfile);
					$base64File = base64_encode($fileContent);
					$files_arr[$i."_".$k2]['content_base64'] 	= $base64File;	
					$files_arr[$i."_".$k2]['filename'] 			= $fileName;
					$files_arr[$i."_".$k2]['size'] 				= $size;
					$files_arr[$i."_".$k2]['type'] 				= $type; 
					$files_arr[$i."_".$k2]['tmp_name'] 			= $postfile; 
					$files_arr[$i."_".$k2]['idDocumento'] 		= $i."_".$k2; 
					$i++;
				}
			}
		}
		
		return array("data"=>$files_arr, "size"=> $qtd);
	}


	protected static function formatArrayConfig($file, $i = 0) {

		$arr = array();
		$fpath = self::createFile($file);
		$previewAsData = false;
		$downloadUrl = false;

		#retorna array formatado
		if($file->mimeType == "text/plain"){
			$contentFile = file_get_contents($fpath);
			$url = "<textarea class=\"kv-preview-data file-preview-text\" readonly>".$contentFile."</textarea>";
			$downloadUrl = "/uploads/".$file->name;
		}  else if( $file->mimeType == "application/pdf" || $file->mimeType == "application/octet-stream" || $file->mimeType == "video/mp4" ) {
			$url = "/uploads/".$file->name;
			// $url = "https://kartik-v.github.io/bootstrap-fileinput-samples/samples/SampleDOCFile_100kb.doc";
			$downloadUrl = "/uploads/".$file->name;
		} else {
			$url = '<img src="uploads/images/'.$file->name.'" class="kv-preview-data file-preview-image">';
			$downloadUrl = "/uploads/images/".$file->name;
		}

		$mimetypeArr = explode("/", $file->mimeType);
		$type = ($mimetypeArr[0] == "application")? $mimetypeArr[1] : $mimetypeArr[0];
		
		$filetype = $file->mimeType;

		# docs, pdf, pdf
		if($mimetypeArr[0] == "application"){
			$previewAsData = true;
			if($mimetypeArr[1]=="octet-stream"){
				// $type = "office";
			}
		} else {
			if($file->mimeType == "video/mp4"){
				$previewAsData = true;
				$type = "video";
			}
		}

		$arr['config'] = array("size"=> filesize($fpath), "type"=> $type, "filetype"=> $filetype, "previewAsData"=> $previewAsData, "caption"=> $file->name, "width"=> "120px", "key"=>$i, "downloadUrl"=> $downloadUrl, "url" => "/bootstrap4/deletefile");
		$arr['url'] = $url;
		$arr['path'] = $fpath;
		
		return $arr;

	}	

	public static function uploadFiles($files) {

		$config = array();
		$urls 	= array();
		$path 	= array();

		if(is_array($files)){
			$i = 0;
			foreach ($files as $file) {
				$ret = self::formatArrayConfig($file, $i);
				$config[] = $ret['config'];
 				$urls[$i] 	= $ret['url']; 
 				$path[$i] 	= $ret['path']; 
 				$i++;
			}			
		} else {
			$ret = self::formatArrayConfig($files);

			$config[] = $ret['config'];
 			$urls 	= $ret['url']; 
 			$path 	= $ret['path']; 
 		}
		return array("url"=> $urls, "config"=> $config, "path" => $path);
	}

	protected static function removeBlob($file) {
		$data['size'] = $file->size;
		$data['type'] = $file->mimeType;
		$data['descricao'] = $file->description;
		$data['filename'] = $file->name;
		$data['tmp_name'] = $file->path;
		$data['idDocumento'] = $file->idDocumento;
		# formata blob para exibição
		$data['content_base64'] = substr($file->blob, 0, 50)."...";
		$file = FileFactory::instanciar($data); 
		return $file;
	}

	public static function print($files) {
		$arr = array();
		if(is_array($files)){
			foreach ($files as $file) {
				$arr[] = self::removeBlob($file);
			}
		} else {
			$arr[] = self::removeBlob($files);
		}
		
		return $arr;
	}

}

// $k = 0;
// echo "<code>";
# tratamento de arquivos já salvos
// foreach ($_POST as $p => $input) {
// 	if(strpos($p, "_File") && is_array($input)) {
// 		$ret = FileUtils::montaArray($input, "post");
// 		$filesArr = $ret['data'];	
// 		$fileSize = $ret['size']; 	

// 		if($fileSize > 1){
// 			$filesFactoryArr = FileFactory::instanciarVarios($filesArr);
// 		} else {
// 			$filesFactoryArr = FileFactory::instanciar($filesArr);
// 		}
// 		echo "<br>";
// 		var_dump(FileUtils::print($filesFactoryArr));
// 		echo "<br>";
// 		${'filesInit_'.$k} = FileUtils::uploadFiles($filesFactoryArr);
// 	}
// }
// echo "</code>";



// FileUpload.php       
# adiciona hidden com os campos para serem reprocessados pelo POST após envio do formulário
        // if( isset($this->initFiles['path'])) {
            
        //     $initFiles = $this->getInitFiles();
        //     foreach ( $initFiles['path'] as $initFile) {
        //         $hiddenInput .= "<input type=\"hidden\" value=\"".$initFile."\" name=\"".$this->getAttrId()."_File[]\">";    
        //     }   
        // }