<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class AssuntoAPI {
  private $CodigoEstruturado;
  private $Descricao;

  /**
   * @return mixed
   */
  public function getCodigoEstruturado()
  {
    return $this->CodigoEstruturado;
  }

  /**
   * @param mixed $CodigoEstruturado
   */
  public function setCodigoEstruturado($CodigoEstruturado)
  {
    $this->CodigoEstruturado = $CodigoEstruturado;
  }

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

}
?>