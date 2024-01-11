<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class PaisAPI{
  private $IdPais;
  private $Nome;

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