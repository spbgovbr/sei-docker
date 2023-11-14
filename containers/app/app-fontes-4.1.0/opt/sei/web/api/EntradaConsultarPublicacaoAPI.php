<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/09/2018 - criado por mga
 *
 */

class EntradaConsultarPublicacaoAPI {
  private $IdPublicacao;
  private $IdDocumento;
  private $ProtocoloDocumento;
  private $SinRetornarAndamento;
  private $SinRetornarAssinaturas;

  /**
   * @return mixed
   */
  public function getIdPublicacao()
  {
    return $this->IdPublicacao;
  }

  /**
   * @param mixed $IdPublicacao
   */
  public function setIdPublicacao($IdPublicacao)
  {
    $this->IdPublicacao = $IdPublicacao;
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
  public function getSinRetornarAndamento()
  {
    return $this->SinRetornarAndamento;
  }

  /**
   * @param mixed $SinRetornarAndamento
   */
  public function setSinRetornarAndamento($SinRetornarAndamento)
  {
    $this->SinRetornarAndamento = $SinRetornarAndamento;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarAssinaturas()
  {
    return $this->SinRetornarAssinaturas;
  }

  /**
   * @param mixed $SinRetornarAssinaturas
   */
  public function setSinRetornarAssinaturas($SinRetornarAssinaturas)
  {
    $this->SinRetornarAssinaturas = $SinRetornarAssinaturas;
  }
}
?>