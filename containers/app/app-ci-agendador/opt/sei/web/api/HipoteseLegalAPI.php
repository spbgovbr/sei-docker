<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class HipoteseLegalAPI {
  private $IdHipoteseLegal;
  private $Nome;
  private $BaseLegal;
  private $NivelAcesso;

  /**
   * @return mixed
   */
  public function getIdHipoteseLegal()
  {
    return $this->IdHipoteseLegal;
  }

  /**
   * @param mixed $IdHipoteseLegal
   */
  public function setIdHipoteseLegal($IdHipoteseLegal)
  {
    $this->IdHipoteseLegal = $IdHipoteseLegal;
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

  /**
   * @return mixed
   */
  public function getBaseLegal()
  {
    return $this->BaseLegal;
  }

  /**
   * @param mixed $BaseLegal
   */
  public function setBaseLegal($BaseLegal)
  {
    $this->BaseLegal = $BaseLegal;
  }

  /**
   * @return mixed
   */
  public function getNivelAcesso()
  {
    return $this->NivelAcesso;
  }

  /**
   * @param mixed $NivelAcesso
   */
  public function setNivelAcesso($NivelAcesso)
  {
    $this->NivelAcesso = $NivelAcesso;
  }
}
?>