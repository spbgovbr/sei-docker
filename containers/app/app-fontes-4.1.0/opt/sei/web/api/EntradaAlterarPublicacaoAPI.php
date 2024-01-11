<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
*/

class EntradaAlterarPublicacaoAPI {
  private $IdPublicacao;
  private $IdDocumento;
  private $ProtocoloDocumento;
  private $StaMotivo;
  private $IdVeiculoPublicacao;
  private $DataDisponibilizacao;
  private $Resumo;
  private $ImprensaNacional;

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
  public function getStaMotivo()
  {
    return $this->StaMotivo;
  }

  /**
   * @param mixed $StaMotivo
   */
  public function setStaMotivo($StaMotivo)
  {
    $this->StaMotivo = $StaMotivo;
  }

  /**
   * @return mixed
   */
  public function getIdVeiculoPublicacao()
  {
    return $this->IdVeiculoPublicacao;
  }

  /**
   * @param mixed $IdVeiculoPublicacao
   */
  public function setIdVeiculoPublicacao($IdVeiculoPublicacao)
  {
    $this->IdVeiculoPublicacao = $IdVeiculoPublicacao;
  }

  /**
   * @return mixed
   */
  public function getDataDisponibilizacao()
  {
    return $this->DataDisponibilizacao;
  }

  /**
   * @param mixed $DataDisponibilizacao
   */
  public function setDataDisponibilizacao($DataDisponibilizacao)
  {
    $this->DataDisponibilizacao = $DataDisponibilizacao;
  }

  /**
   * @return mixed
   */
  public function getResumo()
  {
    return $this->Resumo;
  }

  /**
   * @param mixed $Resumo
   */
  public function setResumo($Resumo)
  {
    $this->Resumo = $Resumo;
  }

  /**
   * @return mixed
   */
  public function getImprensaNacional()
  {
    return $this->ImprensaNacional;
  }

  /**
   * @param mixed $ImprensaNacional
   */
  public function setImprensaNacional($ImprensaNacional)
  {
    $this->ImprensaNacional = $ImprensaNacional;
  }

}
?>