<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaIncluirDocumentoBlocoAPI {
  private $IdBloco;
  private $IdDocumento;
  private $ProtocoloDocumento;
  private $Anotacao;

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

  /**
   * @return mixed
   */
  public function getAnotacao()
  {
    return $this->Anotacao;
  }

  /**
   * @param mixed $Anotacao
   */
  public function setAnotacao($Anotacao)
  {
    $this->Anotacao = $Anotacao;
  }
}
?>