<?php

use TRF4\UI\UI;

// BÁSICO
echo UI::fileUpload('Subir Arquivo', 'example1');

# Múltiplo
echo UI::fileUpload('Subir Arquivo', 'example2')->multiple()->maxFiles(3)->required();

# UPLOAD POR AJAX - (URL, ASSÍNCRONO (TRUE, FALSE))
echo UI::fileUpload('Subir Arquivo', 'example4')->urlAjax('teste.php', true);

# definir formatos permitidos - exemplo: imagens
echo UI::fileUpload('Subir Arquivo', 'example5')->allowedFileExtensions(array('jpg', 'png','gif'));

?>
