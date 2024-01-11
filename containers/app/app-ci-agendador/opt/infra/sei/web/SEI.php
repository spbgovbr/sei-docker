<?
require_once 'Infra.php';

ini_set('session.gc_maxlifetime','28800');

define('SEI_VERSAO','4.0.1');

define('ASSINADOR_VERSAO', '1.1.0');

define('SEI_FEDERACAO_VERSAO', '1.0.0');

define('DIR_SEI_CONFIG', __DIR__.'/../config');
define('DIR_SEI_TEMP', __DIR__.'/../temp');
define('DIR_SEI_BIN',__DIR__.'/../bin');
define('DIR_SEI_SVG', 'svg');

define('TAM_SENHA_USUARIO_EXTERNO', 8);
define('TAM_BLOCO_LEITURA_ARQUIVO', 10485760);

define('ID_BRASIL', 76); //Codigo do Brasil (ISO 3166-1)

require_once DIR_SEI_CONFIG.'/ConfiguracaoSEI.php';

$objConfiguracaoSEI = ConfiguracaoSEI::getInstance();

define('DIGITOS_DOCUMENTO', $objConfiguracaoSEI->getValor('SEI', 'DigitosDocumento',false, 7));
//ini_set('session.cookie_secure', $objConfiguracaoSEI->getValor('SessaoSEI', 'https'));

$INFRA_PATHS[] = __DIR__;
$INFRA_PATHS[] = __DIR__.'/api';
$INFRA_PATHS[] = __DIR__.'/editor';
$INFRA_PATHS[] = __DIR__.'/solr';
$INFRA_PATHS[] = __DIR__.'/publicacoes';

LimiteSEI::getInstance()->configurarNivel1();

$SEI_MODULOS = array();

if ($objConfiguracaoSEI->isSetValor('SEI','Modulos')){

  foreach($objConfiguracaoSEI->getValor('SEI','Modulos') as $strModulo => $strPathModulo){

    infraAdicionarPath(__DIR__.'/modulos/'.$strPathModulo);

    if (!file_exists(__DIR__.'/modulos/'.$strPathModulo . '/' . $strModulo .'.php')) {
      die('Classe de Integra��o do m�dulo "'.$strModulo.'" n�o encontrada.');
    }

    require_once __DIR__.'/modulos/'.$strPathModulo . '/' . $strModulo .'.php';

    $reflectionClass = new ReflectionClass($strModulo);
    $SEI_MODULOS[$strModulo] = $reflectionClass->newInstance();

  }

  foreach($SEI_MODULOS as $strModulo => $objModulo){

    if (trim($objModulo->getNome())==''){
      die('Nome do m�dulo "'.$strModulo.'" n�o informado.');
    }

    if (trim($objModulo->getVersao())==''){
      die('Vers�o do m�dulo "'.$strModulo.'" n�o informada.');
    }

    if (trim($objModulo->getInstituicao())==''){
      die('Institui��o do m�dulo "'.$strModulo.'" n�o informada.');
    }

    $objModulo->executar('inicializar', SEI_VERSAO);
  }
}
?>