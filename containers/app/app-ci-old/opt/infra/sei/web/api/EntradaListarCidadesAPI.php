<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/04/2016 - criado por mga
*
*/

class EntradaListarCidadesAPI {
  private $IdPais;
  private $IdEstado;

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

  /**
   * @return mixed
   */
  public function getIdEstado()
  {
    return $this->IdEstado;
  }

  /**
   * @param mixed $IdEstado
   */
  public function setIdEstado($IdEstado)
  {
    $this->IdEstado = $IdEstado;
  }

}
?>