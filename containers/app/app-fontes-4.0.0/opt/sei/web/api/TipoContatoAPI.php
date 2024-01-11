<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 05/02/2021 - criado por mga
 *
 */

class TipoContatoAPI {
  private $IdTipoContato;
  private $Nome;

  /**
   * @return mixed
   */
  public function getIdTipoContato()
  {
    return $this->IdTipoContato;
  }

  /**
   * @param mixed $IdTipoContato
   */
  public function setIdTipoContato($IdTipoContato)
  {
    $this->IdTipoContato = $IdTipoContato;
  }

  /**
   * @return mixed
   */
  public function getNome()
  {
    return $this->Nome;
  }

  /**
   * @param mixed $Nome
   */
  public function setNome($Nome)
  {
    $this->Nome = $Nome;
  }
}
?>