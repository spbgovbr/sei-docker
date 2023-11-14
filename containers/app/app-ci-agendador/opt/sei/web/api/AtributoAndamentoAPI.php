<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class AtributoAndamentoAPI {
  private $Nome;
  private $Valor;
  private $IdOrigem;

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
  public function getValor()
  {
    return $this->Valor;
  }

  /**
   * @param mixed $Valor
   */
  public function setValor($Valor)
  {
    $this->Valor = $Valor;
  }

  /**
   * @return mixed
   */
  public function getIdOrigem()
  {
    return $this->IdOrigem;
  }

  /**
   * @param mixed $IdOrigem
   */
  public function setIdOrigem($IdOrigem)
  {
    $this->IdOrigem = $IdOrigem;
  }
}