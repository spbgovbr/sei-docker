<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class ProcedimentoResumidoAPI {
  private $IdProcedimento;
  private $ProcedimentoFormatado;
  private $TipoProcedimento;

  /**
   * @return mixed
   */
  public function getIdProcedimento()
  {
    return $this->IdProcedimento;
  }

  /**
   * @param mixed $IdProcedimento
   */
  public function setIdProcedimento($IdProcedimento)
  {
    $this->IdProcedimento = $IdProcedimento;
  }

  /**
   * @return mixed
   */
  public function getProcedimentoFormatado()
  {
    return $this->ProcedimentoFormatado;
  }

  /**
   * @param mixed $ProcedimentoFormatado
   */
  public function setProcedimentoFormatado($ProcedimentoFormatado)
  {
    $this->ProcedimentoFormatado = $ProcedimentoFormatado;
  }

  /**
   * @return mixed
   */
  public function getTipoProcedimento()
  {
    return $this->TipoProcedimento;
  }

  /**
   * @param mixed $TipoProcedimento
   */
  public function setTipoProcedimento($TipoProcedimento)
  {
    $this->TipoProcedimento = $TipoProcedimento;
  }
  
}
?>