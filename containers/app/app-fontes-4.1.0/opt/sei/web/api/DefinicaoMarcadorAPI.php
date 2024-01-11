<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class DefinicaoMarcadorAPI {
  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $IdMarcador;
  private $Texto;

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
  public function getIdMarcador()
  {
    return $this->IdMarcador;
  }

  /**
   * @param mixed $IdMarcador
   */
  public function setIdMarcador($IdMarcador)
  {
    $this->IdMarcador = $IdMarcador;
  }

  /**
   * @return mixed
   */
  public function getTexto()
  {
    return $this->Texto;
  }

  /**
   * @param mixed $Texto
   */
  public function setTexto($Texto)
  {
    $this->Texto = $Texto;
  }
}
?>