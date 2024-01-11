<?
require_once 'Infra.php';

infraAdicionarPath(__DIR__);
infraAdicionarPath(__DIR__.'/api');
infraAdicionarPath(__DIR__.'/editor');
infraAdicionarPath(__DIR__.'/solr');
infraAdicionarPath(__DIR__.'/publicacoes');
infraAdicionarPath(__DIR__.'/plano_trabalho');
infraAdicionarPath(__DIR__.'/consulta_processual');

ini_set('session.gc_maxlifetime','28800');

const SEI_VERSAO = '4.1.0';
const ASSINADOR_VERSAO = '1.1.0';
const SEI_FEDERACAO_VERSAO = '1.1.0';
const CACHE_VERSAO = '';

const DIR_SEI_CONFIG = __DIR__.'/../config';
const DIR_SEI_TEMP = __DIR__.'/../temp';
const DIR_SEI_BIN = __DIR__.'/../bin';
const DIR_SEI_SVG = 'svg';

const TAM_SENHA_USUARIO_EXTERNO = 8;
const TAM_BLOCO_LEITURA_ARQUIVO = 10485760;

const ID_BRASIL = 76; //Codigo do Brasil (ISO 3166-1)

require_once DIR_SEI_CONFIG.'/ConfiguracaoSEI.php';

$objConfiguracaoSEI = ConfiguracaoSEI::getInstance();

define('DIGITOS_DOCUMENTO', $objConfiguracaoSEI->getValor('SEI', 'DigitosDocumento',false, 7));
//ini_set('session.cookie_secure', $objConfiguracaoSEI->getValor('SessaoSEI', 'https'));

LimiteSEI::getInstance()->configurarNivel1();

$SEI_MODULOS = array();

if ($objConfiguracaoSEI->isSetValor('SEI','Modulos')){

  foreach($objConfiguracaoSEI->getValor('SEI','Modulos') as $strModulo => $strPathModulo){

    infraAdicionarPath(__DIR__.'/modulos/'.$strPathModulo);
    infraAdicionarPath(__DIR__.'/modulos/'.$strPathModulo.'/api');

    if (!file_exists(__DIR__.'/modulos/'.$strPathModulo . '/' . $strModulo .'.php')) {
      die('Classe de Integraзгo do mуdulo "'.$strModulo.'" nгo encontrada.');
    }

    require_once __DIR__.'/modulos/'.$strPathModulo . '/' . $strModulo .'.php';

    $reflectionClass = new ReflectionClass($strModulo);
    $SEI_MODULOS[$strModulo] = $reflectionClass->newInstance();

  }

  foreach($SEI_MODULOS as $strModulo => $objModulo){

    if (trim($objModulo->getNome())==''){
      die('Nome do mуdulo "'.$strModulo.'" nгo informado.');
    }

    if (trim($objModulo->getVersao())==''){
      die('Versгo do mуdulo "'.$strModulo.'" nгo informada.');
    }

    if (trim($objModulo->getInstituicao())==''){
      die('Instituiзгo do mуdulo "'.$strModulo.'" nгo informada.');
    }

    $objModulo->executar('inicializar', SEI_VERSAO);
  }
}
?>