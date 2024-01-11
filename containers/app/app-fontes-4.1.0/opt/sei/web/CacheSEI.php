<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 11/10/2012 - criado por MGA
 *
 */

require_once dirname(__FILE__).'/SEI.php';

class CacheSEI extends InfraCache {

  private static $instance = null;

  public static function getInstance(){
    if (self::$instance == null) {
      self::$instance = new CacheSEI();
    }
    return self::$instance;
  }

  public function getStrServidor(){
    return ConfiguracaoSEI::getInstance()->getValor('CacheSEI', 'Servidor');
  }
  
	public function getNumPorta(){
    return ConfiguracaoSEI::getInstance()->getValor('CacheSEI', 'Porta');
	}

  public function getNumTimeout(){
    return ConfiguracaoSEI::getInstance()->getValor('CacheSEI', 'Timeout', false, 1);
  }

  public function getNumTempo(){
    return ConfiguracaoSEI::getInstance()->getValor('CacheSEI', 'Tempo', false, 3600);
  }
	  
  public function getObjInfraSessao(){
    return SessaoSEI::getInstance();
  }

  public function getObjInfraLog(){
    return LogSEI::getInstance();
  }

  public function getAtributoVersao($strChave){

    $numVersao = $this->getAtributo($strChave.'_VERSAO');

    if ($numVersao == null) {
      $numVersao = $this->setAtributoVersao($strChave);
    }

    return $numVersao;
  }

  public function setAtributoVersao($strChave){
    $numVersao = time();
    $this->setAtributo($strChave.'_VERSAO', $numVersao, $this->getNumTempo());
    return $numVersao;
  }
}
?>