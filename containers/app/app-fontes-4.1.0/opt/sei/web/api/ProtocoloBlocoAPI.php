<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class ProtocoloBlocoAPI {
  private $ProtocoloFormatado;
  private $Identificacao;
  private $Assinaturas;

  /**
   * @return mixed
   */
  public function getProtocoloFormatado()
  {
    return $this->ProtocoloFormatado;
  }

  /**
   * @param mixed $ProtocoloFormatado
   */
  public function setProtocoloFormatado($ProtocoloFormatado)
  {
    $this->ProtocoloFormatado = $ProtocoloFormatado;
  }

  /**
   * @return mixed
   */
  public function getIdentificacao()
  {
    return $this->Identificacao;
  }

  /**
   * @param mixed $Identificacao
   */
  public function setIdentificacao($Identificacao)
  {
    $this->Identificacao = $Identificacao;
  }

  /**
   * @return mixed
   */
  public function getAssinaturas()
  {
    return $this->Assinaturas;
  }

  /**
   * @param mixed $Assinaturas
   */
  public function setAssinaturas($Assinaturas)
  {
    $this->Assinaturas = $Assinaturas;
  }
}
?>