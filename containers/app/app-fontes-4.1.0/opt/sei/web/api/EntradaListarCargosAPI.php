<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/08/2014 - criado por mga
 *
 */

class EntradaListarCargosAPI {
  private $IdCargo;

  /**
   * @return mixed
   */
  public function getIdCargo()
  {
    return $this->IdCargo;
  }

  /**
   * @param mixed $IdCargo
   */
  public function setIdCargo($IdCargo)
  {
    $this->IdCargo = $IdCargo;
  }
}
?>