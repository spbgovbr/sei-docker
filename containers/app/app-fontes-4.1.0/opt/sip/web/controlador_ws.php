<?php

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  $strServico = $_GET['servico'];

  switch ($strServico) {
    case 'sip':
      $strArq = 'ws/sip.wsdl';
      break;

    default:

      foreach ($SIP_MODULOS as $objModulo) {
        if (($strArq = $objModulo->processarControladorWebServices($_GET['servico'])) != null) {
          break;
        }
      }

      if ($strArq == null) {
        die('Serviço [' . $_GET['servico'] . '] inválido.');
      }
  }

  $strServidor = ConfiguracaoSip::getInstance()->getValor('Sip', 'URL');

  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $strServidor = str_replace('http://', 'https://', $strServidor);
  } else {
    $strServidor = str_replace('https://', 'http://', $strServidor);
  }

  InfraPagina::montarHeaderDownload($strArq);
  $strWsdl = file_get_contents($strArq);
  $strWsdl = str_replace('[servidor]', $strServidor, $strWsdl);

  die(trim($strWsdl));
} catch (Throwable $e) {
  try {
    LogSip::getInstance()->gravar(InfraException::inspecionar($e));
  } catch (Throwable $e2) {
  }
}
?>