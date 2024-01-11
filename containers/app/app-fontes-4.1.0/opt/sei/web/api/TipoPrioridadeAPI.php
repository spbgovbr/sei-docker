<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class TipoPrioridadeAPI {
  private $IdTipoPrioridade;
  private $Nome;

  /**
   * @return mixed
   */
  public function getIdTipoPrioridade()
  {
    return $this->IdTipoPrioridade;
  }

  /**
   * @param mixed $IdTipoPrioridade
   */
  public function setIdTipoPrioridade($IdTipoPrioridade)
  {
    $this->IdTipoPrioridade = $IdTipoPrioridade;
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