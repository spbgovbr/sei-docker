<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/04/2016 - criado por mga
*
*/

class EntradaListarEstadosAPI {
  private $IdPais;

  /**
   * @return mixed
   */
  public function getIdPais()
  {
    return $this->IdPais;
  }

  /**
   * @param mixed $IdPais
   */
  public function setIdPais($IdPais)
  {
    $this->IdPais = $IdPais;
  }
}
?>