<?
require_once 'Infra.php';

define('SIP_VERSAO','3.0.0');

define('DIR_SIP_CONFIG', __DIR__.'/../config');
define('DIR_SIP_TEMP', __DIR__.'/../temp');
define('DIR_SIP_BIN',__DIR__.'/../bin');

ini_set('session.gc_maxlifetime','28800');
ini_set('memory_limit','256M');

require_once DIR_SIP_CONFIG.'/ConfiguracaoSip.php';

//ini_set('session.cookie_secure', ConfiguracaoSip::getInstance()->getValor('SessaoSip', 'https'));

infraAdicionarPath(__DIR__);

$SIP_MODULOS = array();

if (ConfiguracaoSip::getInstance()->isSetValor('Sip','Modulos')) {

  foreach(ConfiguracaoSip::getInstance()->getValor('Sip','Modulos') as $strModulo => $strPathModulo){

    infraAdicionarPath(__DIR__.'/modulos/'.$strPathModulo);

    if (!file_exists(__DIR__.'/modulos/'.$strPathModulo . '/' . $strModulo .'.php')) {
      die('Classe de Integraзгo do mуdulo "'.$strModulo.'" nгo encontrada.');
    }

    $reflectionClass = new ReflectionClass($strModulo);
    $SIP_MODULOS[$strModulo] = $reflectionClass->newInstance();
  }

  foreach($SIP_MODULOS as $strModulo => $objModulo){

    if (trim($objModulo->getNome())==''){
      die('Nome do mуdulo "'.$strModulo.'" nгo informado.');
    }

    if (trim($objModulo->getVersao())==''){
      die('Versгo do mуdulo "'.$strModulo.'" nгo informada.');
    }

    if (trim($objModulo->getInstituicao())==''){
      die('Instituiзгo do mуdulo "'.$strModulo.'" nгo informada.');
    }

    $objModulo->inicializar(SIP_VERSAO);
  }

}
?>