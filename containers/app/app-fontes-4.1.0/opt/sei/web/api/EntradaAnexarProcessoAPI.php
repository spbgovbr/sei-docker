<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaAnexarProcessoAPI {
  private $IdProcedimentoPrincipal;
  private $ProtocoloProcedimentoPrincipal;
  private $IdProcedimentoAnexado;
  private $ProtocoloProcedimentoAnexado;

  /**
   * @return mixed
   */
  public function getIdProcedimentoPrincipal()
  {
    return $this->IdProcedimentoPrincipal;
  }

  /**
   * @param mixed $IdProcedimentoPrincipal
   */
  public function setIdProcedimentoPrincipal($IdProcedimentoPrincipal)
  {
    $this->IdProcedimentoPrincipal = $IdProcedimentoPrincipal;
  }

  /**
   * @return mixed
   */
  public function getProtocoloProcedimentoPrincipal()
  {
    return $this->ProtocoloProcedimentoPrincipal;
  }

  /**
   * @param mixed $ProtocoloProcedimentoPrincipal
   */
  public function setProtocoloProcedimentoPrincipal($ProtocoloProcedimentoPrincipal)
  {
    $this->ProtocoloProcedimentoPrincipal = $ProtocoloProcedimentoPrincipal;
  }

  /**
   * @return mixed
   */
  public function getIdProcedimentoAnexado()
  {
    return $this->IdProcedimentoAnexado;
  }

  /**
   * @param mixed $IdProcedimentoAnexado
   */
  public function setIdProcedimentoAnexado($IdProcedimentoAnexado)
  {
    $this->IdProcedimentoAnexado = $IdProcedimentoAnexado;
  }

  /**
   * @return mixed
   */
  public function getProtocoloProcedimentoAnexado()
  {
    return $this->ProtocoloProcedimentoAnexado;
  }

  /**
   * @param mixed $ProtocoloProcedimentoAnexado
   */
  public function setProtocoloProcedimentoAnexado($ProtocoloProcedimentoAnexado)
  {
    $this->ProtocoloProcedimentoAnexado = $ProtocoloProcedimentoAnexado;
  }
}
?>