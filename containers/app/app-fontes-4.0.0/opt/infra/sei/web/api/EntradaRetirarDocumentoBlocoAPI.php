<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaRetirarDocumentoBlocoAPI {
  private $IdBloco;
  private $IdDocumento;
  private $ProtocoloDocumento;

  /**
   * @return mixed
   */
  public function getIdBloco()
  {
    return $this->IdBloco;
  }

  /**
   * @param mixed $IdBloco
   */
  public function setIdBloco($IdBloco)
  {
    $this->IdBloco = $IdBloco;
  }

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
}
?>