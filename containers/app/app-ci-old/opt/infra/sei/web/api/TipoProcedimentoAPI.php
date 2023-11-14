<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class TipoProcedimentoAPI {
  private $IdTipoProcedimento;
  private $Nome;

  /**
   * @return mixed
   */
  public function getIdTipoProcedimento()
  {
    return $this->IdTipoProcedimento;
  }

  /**
   * @param mixed $IdTipoProcedimento
   */
  public function setIdTipoProcedimento($IdTipoProcedimento)
  {
    $this->IdTipoProcedimento = $IdTipoProcedimento;
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