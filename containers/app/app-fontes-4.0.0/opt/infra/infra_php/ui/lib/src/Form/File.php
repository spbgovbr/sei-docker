<?php
/**
 * Created by PhpStorm.
 * User: bxo
 * Date: 01/02/2019
 * Time: 15:49
 */

namespace TRF4\UI\Form;


class File
{

	/** @var string|null Uma descrição sobre o arquivo, opcional */
	public $description;
	public $blob;
	/** @var string o tamanho, em bytes */
	public $size;
	public $path;
	public $mimeType;
	public $name;
	public $idDocumento;

	public function __construct(
	    string $name,
	    string $path,
        string $size,
        string $mimeType,
        string $blob,
        ?string $description,
        string $idDocumento = null
    ) {
		$this->name = $name;
		$this->path = $path;
		$this->size = $size;
		$this->mimeType = $mimeType;
		$this->blob = $blob;
		$this->description = $description ?: '';
		$this->idDocumento = $idDocumento;
	}

}