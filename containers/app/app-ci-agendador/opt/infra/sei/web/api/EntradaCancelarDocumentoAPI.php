<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/03/2015 - criado por mga
*
*/

class EntradaCancelarDocumentoAPI {
  private $IdDocumento;
  private $ProtocoloDocumento;
  private $Motivo;

  /**
   * @return mixed
   */
  public function getIdDocumento()
  {
    return $this->IdDocumento;
  }

  /**
   * @param mixed $IdDocumento
   */
  public function setIdDocumento($IdDocumento)
  {
    $this->IdDocumento = $IdDocumento;
  }

  /**
   * @return mixed
   */
  public function getProtocoloDocumento()
  {
    return $this->ProtocoloDocumento;
  }

  /**
   * @param mixed $ProtocoloDocumento
   */
  public function setProtocoloDocumento($ProtocoloDocumento)
  {
    $this->ProtocoloDocumento = $ProtocoloDocumento;
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