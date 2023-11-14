<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class ObservacaoAPI {
  private $Descricao;
  private $Unidade;

  /**
   * @return mixed
   */
  public function getDescricao()
  {
    return $this->Descricao;
  }

  /**
   * @param mixed $Descricao
   */
  public function setDescricao($Descricao)
  {
    $this->Descricao = $Descricao;
  }

  /**
   * @return mixed
   */
  public function getUnidade()
  {
    return $this->Unidade;
  }

  /**
   * @param mixed $Unidade
   */
  public function setUnidade($Unidade)
  {
    $this->Unidade = $Unidade;
  }

}
?>