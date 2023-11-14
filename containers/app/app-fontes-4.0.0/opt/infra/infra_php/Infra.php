<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 16/05/2006 - criado por MGA
 * 
 * @package infra_php
 * 
 * Este arquivo deve ser adicionado em TODAS as páginas do sistema, fornecendo:
 * - tratamento automático para instanciação de classes (__autoload)
 * - tratamento de erros do PHP (gerando um objeto Exception)
 * - tamanho da memória disponível para execução do script
 * - tempo máximo de execução de script
 * - desabilita o cache para Web-Services
 * - desabilita a conversão de datas para o SQL Server
 * - seta o timezone
 */
 
/**
 * Constante de controle de versão da infra-estrutura. Este valor é adicionado como  
 * parâmetro nos arquivos css e javascript enviados ao browser para evitar a cache local.
 *
 */

define('VERSAO_INFRA','1.583.4');

define('PORTABLE_UTF8__DISABLE_AUTO_FILTER', 1);

global $INFRA_PATHS;

$INFRA_PATHS = array(
  dirname(__FILE__).'/infrapagina'
);

/**
 * Adiciona um caminho de busca para classes a ser utilizado pela função __autoload
 *
 * @param String $strPath Caminho para busca
 */
function infraAdicionarPath($strPath){
  global $INFRA_PATHS;
  foreach($INFRA_PATHS as $p){
    if ($p==$strPath){
      return;
    }
  }
  $INFRA_PATHS[] = $strPath;
}

spl_autoload_register('infraAutoLoad');

if (version_compare(PHP_VERSION, '5.6.0') >= 0) {
  require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
}

/**
 * Procura pelas classes quando estas são instanciadas através do operador new.
 * <br />
 * Ordem de busca:<br />
 * 1) se a classe começar com 'Infra' procurará no diretório da infra-estrutura, que consiste
 * no mesmo diretório deste arquivo (configurado no include_path do php.ini)
 * <br />
 * 2) busca em cada um dos diretórios adicionados com infraAdicionarPath, verificando pelo sufixo
 * da classe (RN, DTO, BD, INT, LST) concatenando o sub-diretório correspondente. Se a classe não 
 * contiver nenhum destes sufixos procura diretamente no diretório. 
 * 
 *
 * @param String $strClasse Classe instanciada através do operador new
 */
function infraAutoLoad($strClasse) {
  global $INFRA_PATHS;

  //não tenta carregar classes do PHPUnit
  if ($strClasse==='ClassLoader'){
    return;
  }

  if (substr($strClasse,0,5) == 'Infra'){

    $strArquivo = dirname(__FILE__).DIRECTORY_SEPARATOR.$strClasse.'.php';
    if (file_exists($strArquivo)){
      require_once $strArquivo;
      return;
    }

    $strArquivo = dirname(__FILE__).DIRECTORY_SEPARATOR.'CAS'.DIRECTORY_SEPARATOR.$strClasse.'.php';
    if (file_exists($strArquivo)){
      require_once $strArquivo;
      return;
    }


  }
  
  $j = strlen($strClasse);
  
  $strSufixo2 = '';
  $strSufixo3 = '';
  if ($j > 3){
  	$strSufixo2 = substr($strClasse,$j-2);
  	$strSufixo3 = substr($strClasse,$j-3);
  }else if ($j > 2){
  	$strSufixo2 = substr($strClasse,$j-2);
  }

  $strSubDir = '';
  if ($strSufixo2 == 'RN'){
    $strSubDir = 'rn'.DIRECTORY_SEPARATOR;
  } else if ($strSufixo3 == 'DTO'){
    $strSubDir = 'dto'.DIRECTORY_SEPARATOR;
  } else if ($strSufixo2 == 'BD'){
    $strSubDir = 'bd'.DIRECTORY_SEPARATOR;
  } else if ($strSufixo3 == 'INT'){
    $strSubDir = 'int'.DIRECTORY_SEPARATOR;
  } else if ($strSufixo2 == 'WS'){
    $strSubDir = 'ws'.DIRECTORY_SEPARATOR;
  } else if ($strSufixo3 == 'LST'){
    $strSubDir = 'lst'.DIRECTORY_SEPARATOR;
  }

  foreach($INFRA_PATHS as $strPath){

    $strArquivo = $strPath.DIRECTORY_SEPARATOR.$strSubDir.$strClasse.'.php';
    if (file_exists($strArquivo)){
      require_once $strArquivo;
      return;
    }
  }

  //formularios da Infra
  $strArquivo = dirname(__FILE__).DIRECTORY_SEPARATOR.'formularios'.DIRECTORY_SEPARATOR.$strSubDir.$strClasse.'.php';
  if (file_exists($strArquivo)){
    require_once $strArquivo;
    return;
  }


  //Namespaces
  $strClasse = ltrim($strClasse, '\\');
  $strArquivo  = dirname(__FILE__).DIRECTORY_SEPARATOR;
  $strNameSpace = '';
  if ($lastNsPos = strripos($strClasse, '\\')) {
    $strNameSpace = substr($strClasse, 0, $lastNsPos);
    $strClasse = substr($strClasse, $lastNsPos + 1);

    if (substr($strNameSpace,0,7) == 'TRF4\UI'){
      $strSufixoUI = substr($strNameSpace,7);
      $strNameSpace = 'ui'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'src'.($strSufixoUI!=''?DIRECTORY_SEPARATOR.$strSufixoUI:'');
    }

    $strArquivo .= str_replace('\\', DIRECTORY_SEPARATOR, $strNameSpace).DIRECTORY_SEPARATOR;

  }
  $strArquivo .= str_replace('_', DIRECTORY_SEPARATOR, $strClasse).'.php';

  if (file_exists($strArquivo)){
    require_once $strArquivo;
    return;
  }

  if (!defined('PHPEXCEL_ROOT')) {
    define('PHPEXCEL_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
    require_once(PHPEXCEL_ROOT . 'PHPExcel'.DIRECTORY_SEPARATOR.'Autoloader.php');
  }

  PHPExcel_Autoloader::Load($strClasse);
}

/**
 * Captura os erros do PHP lançando um objeto Exception.
 * Obs: Erros do tipo E_NOTICE não são transformados em uma exceção
 *
 * @param int $errno Tipo do erro
 * @param string $errmsg Mensagem de erro
 * @param string $filename Nome do arquivo que originou o erro
 * @param int $linenum Número da linha que originou o erro
 * @throws Exception
 */
function infraGerarExcecao($errno, $errmsg, $filename, $linenum) {
  
  $strTipoErro = array (
                        E_ERROR           => "Error",
                        E_WARNING         => "Warning",
                        E_PARSE           => "Parsing Error",
                        E_NOTICE          => "Notice",
                        E_CORE_ERROR      => "Core Error",
                        E_CORE_WARNING    => "Core Warning",
                        E_COMPILE_ERROR   => "Compile Error",
                        E_COMPILE_WARNING => "Compile Warning",
                        E_USER_ERROR      => "User Error",
                        E_USER_WARNING    => "User Warning",
                        E_USER_NOTICE     => "User Notice",
                        E_STRICT          => "Runtime Notice",
                        E_RECOVERABLE_ERROR  => "Fatal Error",
                        8192 => 'Deprecated',
                        16384 => 'User Deprecated'
                       );
									 
	$msg = $strTipoErro[$errno].': '.$filename.' linha:'.$linenum.'.'."\n".$errmsg."\n";
	
	if ($errno != E_NOTICE && $errno != 8192 && $errno != 16384){

    if (InfraDebug::isBolProcessar()) {
      InfraDebug::getInstance()->gravarInfra('[Infra->infraGerarExcecao] 10: ' . $msg);
    }

    throw new Exception('\''.$msg.'\'');

	} else {

    if (InfraDebug::isBolProcessar()) {
      InfraDebug::getInstance()->gravarInfra('[Infra->infraGerarExcecao] 20: ' . $msg);
    }

	}
}

function infraTratarErroFatal($objInfraSessao, $strLink='controlador.php?acao=infra_erro_fatal_logar'){
  ini_set('error_prepend_string', '<html><head><title>Erro Fatal</title><script type="text/javascript" charset="iso-8859-1" >function inicializar(){document.getElementById(\'frmInfraErroFatal\').submit();}</script></head><body onload="inicializar();"><form id="frmInfraErroFatal" action="'.$objInfraSessao->assinarLink($strLink).'" method="post"><textarea id="txaInfraErroFatal" name="txaInfraErroFatal" style="visibility:hidden;">');
  ini_set('error_append_string', '</textarea></form></body></html>');
}

error_reporting(E_ALL);

set_error_handler('infraGerarExcecao', E_ALL);

ini_set('memory_limit','128M');

ini_set('max_execution_time','180'); 

ini_set('default_socket_timeout', '60');

ini_set('mssql.datetimeconvert', '0');

ini_set('soap.wsdl_cache_enabled', '0');

//evitar acesso ao cookie de sessão via javascript
ini_set('session.cookie_httponly', '1');

//nao permite o uso de id de sessao na URL
ini_set('session.use_only_cookies', '1');

date_default_timezone_set('America/Sao_Paulo');

//session_name('INFRA_PHP');

define('INFRA_TAM_MAX_LOG_SQL','4096');

if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION >= 7) { //todo remover isso  quando houver suporte a php >= 7
   //InfraUI::config(new \TRF4\UI\Renderer\Infra);
   require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'ui_setup.php');
}