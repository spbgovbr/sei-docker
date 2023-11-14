<?php
/**
 * Created by PhpStorm.
 * User: bxo
 * Date: 01/02/2019
 * Time: 15:49
 */

namespace TRF4\UI\Form;


class FileFactory
{
	/**
	 * @param array $data
	 * @return File
	 */
	public static function instanciar(array $data): File {
		if(!array_key_exists("size", $data)){
			$data = $data['0_0'];
		}
		$size = $data['size'];
		$mimeType = $data['type'];
		$descricao = $data['descricao'] ?? null;
		$base64Content = $data['content_base64'];
		$blob = base64_decode($base64Content);
		unset($base64Content);
		$name = $data['filename'];
		$path = $data['tmp_name'];
		$idDocumento = $data['idDocumento'];
		$file = new File($name, $path, $size, $mimeType, $blob, $descricao, $idDocumento);
		return $file;
	}

	/**
	 * @param array $arrDadosArquivos
	 * @return File[]
	 */
	public static function instanciarVarios(array $arrDadosArquivos): array {
		$files = array();
		foreach ($arrDadosArquivos as $d) {
			$files[] = self::instanciar($d);
		}
		return $files;
	}


}