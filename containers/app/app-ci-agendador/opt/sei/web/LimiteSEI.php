<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 28/09/2020 - criado por MGA
 *
 */

require_once dirname(__FILE__).'/SEI.php';

class LimiteSEI {
  private static $instance = null;
  private $numMemoria = null;
  private $numTempo = null;


  public static function getInstance()
  {
    if (LimiteSEI::$instance == null) {
      LimiteSEI::$instance = new LimiteSEI();
    }
    return LimiteSEI::$instance;
  }


  public function configurarNivel1(){
    $this->lerMemoria('Nivel1MemoriaMb', '256');
    $this->lerTempo('Nivel1TempoSeg', '60');
  }

  public function configurarNivel2(){
    $this->lerMemoria('Nivel2MemoriaMb', '2048');
    $this->lerTempo('Nivel2TempoSeg', '600');
  }

  public function configurarNivel3(){
    $this->lerMemoria('Nivel3MemoriaMb', '4096');
    $this->lerTempo('Nivel3TempoSeg', '0');
  }

  private function lerMemoria($strAtributo, $numDefault){

    $v = ConfiguracaoSEI::getInstance()->getValor('Limites', $strAtributo, false, $numDefault);

    if (!is_numeric($v) || $v == 0 || $v < -1 || ($v == -1 && $strAtributo != 'Nivel3MemoriaMb')){
      die('Valor inválido na configuração do atributo '.$strAtributo.': '.$v);
    }

    $v = (int) $v;

    if ($this->numMemoria !== -1 && ($this->numMemoria === null || $v > $this->numMemoria || $v === -1)) {
      $this->numMemoria = $v;
      ini_set('memory_limit', $this->numMemoria.($this->numMemoria !== -1 ? 'M' : ''));
    }
  }

  private function lerTempo($strAtributo, $numDefault){

    $v = ConfiguracaoSEI::getInstance()->getValor('Limites', $strAtributo, false, $numDefault);

    if (!is_numeric($v) || $v < 0 || ($v == 0 && $strAtributo != 'Nivel3TempoSeg')){
      die('Valor inválido na configuração do atributo '.$strAtributo.': '.$v);
    }

    $v = (int) $v;

    if ($this->numTempo !== 0 && ($this->numTempo === null || $v > $this->numTempo || $v === 0)) {
      $this->numTempo = $v;
      ini_set('max_execution_time', $this->numTempo);
    }
  }

  public function getNumMemoria(){
    return $this->numMemoria;
  }

  public function getNumTempo(){
    return $this->numTempo;
  }
}
?>