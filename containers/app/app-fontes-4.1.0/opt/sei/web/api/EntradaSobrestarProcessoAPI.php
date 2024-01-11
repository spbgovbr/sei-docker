<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaSobrestarProcessoAPI {
  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $IdProcedimentoVinculado;
  private $ProtocoloProcedimentoVinculado;
  private $Motivo;

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
  public function getProtocoloProcedimento()
  {
    return $this->ProtocoloProcedimento;
  }

  /**
   * @param mixed $ProtocoloProcedimento
   */
  public function setProtocoloProcedimento($ProtocoloProcedimento)
  {
    $this->ProtocoloProcedimento = $ProtocoloProcedimento;
  }

  /**
   * @return mixed
   */
  public function getIdProcedimentoVinculado()
  {
    return $this->IdProcedimentoVinculado;
  }

  /**
   * @param mixed $IdProcedimentoVinculado
   */
  public function setIdProcedimentoVinculado($IdProcedimentoVinculado)
  {
    $this->IdProcedimentoVinculado = $IdProcedimentoVinculado;
  }

  /**
   * @return mixed
   */
  public function getProtocoloProcedimentoVinculado()
  {
    return $this->ProtocoloProcedimentoVinculado;
  }

  /**
   * @param mixed $ProtocoloProcedimentoVinculado
   */
  public function setProtocoloProcedimentoVinculado($ProtocoloProcedimentoVinculado)
  {
    $this->ProtocoloProcedimentoVinculado = $ProtocoloProcedimentoVinculado;
  }

  /**
   * @return mixed
   */
  public function getMotivo()
  {
    return $this->Motivo;
  }

  /**
   * @param mixed $Motivo
   */
  public function setMotivo($Motivo)
  {
    $this->Motivo = $Motivo;
  }
}
?>