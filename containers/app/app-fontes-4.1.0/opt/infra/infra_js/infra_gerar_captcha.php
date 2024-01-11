<?php

require_once '../infra_php/Infra.php';
//require_once '/var/www/html/[sigla-usuario]/infra/infra_php/Infra.php';

session_start();

$strChave = hash('sha256', 'b9e97b3c17266a68c19682f2c96ca' . date("H-Y-d-m") . 'c723e70345d6a5af253cf30a4500f886696');

if (($_GET['c'] == $strChave) && ($_GET['r'] == 's')) {
    $_SESSION['INFRA_CAPTCHA_CODIGO_'.$_GET['i']] = InfraCaptcha::obterCodigoV2();
    $_SESSION['INFRA_CAPTCHA_V2_'.$_GET['i']] = InfraCaptcha::gerarV2($_SESSION['INFRA_CAPTCHA_CODIGO_'.$_GET['i']]);
    header('Content-type: image/png');
    echo 'data:image/png;base64,' . base64_encode(InfraCaptcha::gerarImagemV2($_SESSION['INFRA_CAPTCHA_CODIGO_'.$_GET['i']]));
} else {
    header('Content-type: image/png');
    echo InfraCaptcha::gerarImagem($_GET['codetorandom']);
}
