<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class UsuarioAPI {
  private $IdUsuario;
  private $Sigla;
  private $Nome;

  /**
   * @return mixed
   */
  public function getIdUsuario()
  {
    return $this->IdUsuario;
  }

  /**
   * @param mixed $IdUsuario
   */
  public function setIdUsuario($IdUsuario)
  {
    $this->IdUsuario = $IdUsuario;
  }

  /**
   * @return mixed
   */
  public function getSigla()
  {
    return $this->Sigla;
  }

  /**
   * @param mixed $Sigla
   */
  public function setSigla($Sigla)
  {
    $this->Sigla = $Sigla;
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