<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class SerieAPI {
  private $IdSerie;
  private $Nome;
  private $Aplicabilidade;

  /**
   * @return mixed
   */
  public function getIdSerie()
  {
    return $this->IdSerie;
  }

  /**
   * @param mixed $IdSerie
   */
  public function setIdSerie($IdSerie)
  {
    $this->IdSerie = $IdSerie;
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
  public function getAplicabilidade()
  {
    return $this->Aplicabilidade;
  }

  /**
   * @param mixed $Aplicabilidade
   */
  public function setAplicabilidade($Aplicabilidade)
  {
    $this->Aplicabilidade = $Aplicabilidade;
  }
}
?>