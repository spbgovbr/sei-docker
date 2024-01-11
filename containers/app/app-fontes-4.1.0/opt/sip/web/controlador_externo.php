<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/04/2022 - criado por MGA
*
*/

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  SessaoSip::getInstance(false);

  switch ($_GET['acao']) {
    case 'instrucoes_2fa':
      require_once 'ajuda/instrucoes_2fa.php';
      break;
  }
} catch (Throwable $e) {
  try {
    LogSip::getInstance()->gravar(InfraException::inspecionar($e));
  } catch (Exception $e) {
  }
}
?>