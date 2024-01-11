<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaRelacionarProcessoAPI {
  private $IdProcedimento1;
  private $ProtocoloProcedimento1;
  private $IdProcedimento2;
  private $ProtocoloProcedimento2;

  /**
   * @return mixed
   */
  public function getIdProcedimento1()
  {
    return $this->IdProcedimento1;
  }

  /**
   * @param mixed $IdProcedimento1
   */
  public function setIdProcedimento1($IdProcedimento1)
  {
    $this->IdProcedimento1 = $IdProcedimento1;
  }

  /**
   * @return mixed
   */
  public function getProtocoloProcedimento1()
  {
    return $this->ProtocoloProcedimento1;
  }

  /**
   * @param mixed $ProtocoloProcedimento1
   */
  public function setProtocoloProcedimento1($ProtocoloProcedimento1)
  {
    $this->ProtocoloProcedimento1 = $ProtocoloProcedimento1;
  }

  /**
   * @return mixed
   */
  public function getIdProcedimento2()
  {
    return $this->IdProcedimento2;
  }

  /**
   * @param mixed $IdProcedimento2
   */
  public function setIdProcedimento2($IdProcedimento2)
  {
    $this->IdProcedimento2 = $IdProcedimento2;
  }

  /**
   * @return mixed
   */
  public function getProtocoloProcedimento2()
  {
    return $this->ProtocoloProcedimento2;
  }

  /**
   * @param mixed $ProtocoloProcedimento2
   */
  public function setProtocoloProcedimento2($ProtocoloProcedimento2)
  {
    $this->ProtocoloProcedimento2 = $ProtocoloProcedimento2;
  }
}
?>