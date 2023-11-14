<?php











namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => '5bd0e8b9053fa6bb061442dab0cfb970ad31dac4',
    'name' => '__root__',
  ),
  'versions' => 
  array (
    '__root__' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '5bd0e8b9053fa6bb061442dab0cfb970ad31dac4',
    ),
    'beberlei/assert' => 
    array (
      'pretty_version' => 'v3.2.2',
      'version' => '3.2.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '5547e7d03f8c6be121b8b9db6d6ed5a22ffdcb01',
    ),
    'ircmaxell/password-compat' => 
    array (
      'pretty_version' => 'v1.0.4',
      'version' => '1.0.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '5c5cde8822a69545767f7c7f3058cb15ff84614c',
    ),
    'paragonie/constant_time_encoding' => 
    array (
      'pretty_version' => 'v2.2.3',
      'version' => '2.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '55af0dc01992b4d0da7f6372e2eac097bbbaffdb',
    ),
    'paragonie/random_compat' => 
    array (
      'pretty_version' => 'v2.0.12',
      'version' => '2.0.12.0',
      'aliases' => 
      array (
      ),
      'reference' => '258c89a6b97de7dfaf5b8c7607d0478e236b04fb',
    ),
    'sebastian/diff' => 
    array (
      'pretty_version' => '1.4.3',
      'version' => '1.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '7f066a26a962dbe58ddea9f72a4e82874a3975a4',
    ),
    'spomky-labs/otphp' => 
    array (
      'pretty_version' => 'v10.0.3',
      'version' => '10.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '9784d9f7c790eed26e102d6c78f12c754036c366',
    ),
    'symfony/intl' => 
    array (
      'pretty_version' => 'v4.0.8',
      'version' => '4.0.8.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd58df88e3cfdb2702f5fd8cd67c33961c2539e0c',
    ),
    'symfony/polyfill' => 
    array (
      'pretty_version' => 'v1.7.0',
      'version' => '1.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '86e27771f290e6af6d95c1c256f6d6d15ac9e22e',
    ),
    'symfony/polyfill-apcu' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-iconv' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-intl-grapheme' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-intl-icu' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-intl-normalizer' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-php54' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-php55' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-php56' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-php70' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-php71' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-php72' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-util' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'symfony/polyfill-xml' => 
    array (
      'replaced' => 
      array (
        0 => 'v1.7.0',
      ),
    ),
    'thecodingmachine/safe' => 
    array (
      'pretty_version' => 'v1.3.3',
      'version' => '1.3.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a8ab0876305a4cdaef31b2350fcb9811b5608dbc',
    ),
    'tuupola/base32' => 
    array (
      'pretty_version' => '0.2.0',
      'version' => '0.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '43a783c6eb3f125a5efdc9e22a97f6cc604f345e',
    ),
    'voku/anti-xss' => 
    array (
      'pretty_version' => '2.2.1',
      'version' => '2.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'cde2346113c3d84495ddb2d1fe511ca9c7c0db3c',
    ),
    'voku/portable-utf8' => 
    array (
      'pretty_version' => '3.1.30',
      'version' => '3.1.30.0',
      'aliases' => 
      array (
      ),
      'reference' => '162de9c40d8a7dff84697296f375b424aef53df8',
    ),
  ),
);
private static $canGetVendors;
private static $installedByVendor = array();







public static function getInstalledPackages()
{
$packages = array();
foreach (self::getInstalled() as $installed) {
$packages[] = array_keys($installed['versions']);
}


if (1 === \count($packages)) {
return $packages[0];
}

return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
}









public static function isInstalled($packageName)
{
foreach (self::getInstalled() as $installed) {
if (isset($installed['versions'][$packageName])) {
return true;
}
}

return false;
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

$ranges = array();
if (isset($installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = $installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['version'])) {
return null;
}

return $installed['versions'][$packageName]['version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getPrettyVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return $installed['versions'][$packageName]['pretty_version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getReference($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['reference'])) {
return null;
}

return $installed['versions'][$packageName]['reference'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getRootPackage()
{
$installed = self::getInstalled();

return $installed[0]['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
self::$installedByVendor = array();
}




private static function getInstalled()
{
if (null === self::$canGetVendors) {
self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
}

$installed = array();

if (self::$canGetVendors) {

 foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
if (isset(self::$installedByVendor[$vendorDir])) {
$installed[] = self::$installedByVendor[$vendorDir];
} elseif (is_file($vendorDir.'/composer/installed.php')) {
$installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
}
}
}

$installed[] = self::$installed;

return $installed;
}
}
