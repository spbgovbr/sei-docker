<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaConsultarDocumentoAPI {
  private $IdDocumento;
  private $ProtocoloDocumento;
  private $SinRetornarAndamentoGeracao;
  private $SinRetornarAssinaturas;
  private $SinRetornarPublicacao;
  private $SinRetornarCampos;

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
  public function getSinRetornarAndamentoGeracao()
  {
    return $this->SinRetornarAndamentoGeracao;
  }

  /**
   * @param mixed $SinRetornarAndamentoGeracao
   */
  public function setSinRetornarAndamentoGeracao($SinRetornarAndamentoGeracao)
  {
    $this->SinRetornarAndamentoGeracao = $SinRetornarAndamentoGeracao;
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

  /**
   * @return mixed
   */
  public function getSinRetornarPublicacao()
  {
    return $this->SinRetornarPublicacao;
  }

  /**
   * @param mixed $SinRetornarPublicacao
   */
  public function setSinRetornarPublicacao($SinRetornarPublicacao)
  {
    $this->SinRetornarPublicacao = $SinRetornarPublicacao;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarCampos()
  {
    return $this->SinRetornarCampos;
  }

  /**
   * @param mixed $SinRetornarCampos
   */
  public function setSinRetornarCampos($SinRetornarCampos)
  {
    $this->SinRetornarCampos = $SinRetornarCampos;
  }

}
?>