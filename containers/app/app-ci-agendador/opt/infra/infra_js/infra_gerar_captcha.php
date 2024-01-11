<?php
require_once 'Infra.php';
header('Content-type: image/png');
echo InfraCaptcha::gerarImagem($_GET['codetorandom']);
?>