<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
*/


class EntradaConfirmarDisponibilizacaoPublicacaoAPI {
  private $IdVeiculoPublicacao;
  private $DataDisponibilizacao;
  private $DataPublicacao;
  private $Numero;
  private $IdDocumentos;

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
  public function getDataPublicacao()
  {
    return $this->DataPublicacao;
  }

  /**
   * @param mixed $DataPublicacao
   */
  public function setDataPublicacao($DataPublicacao)
  {
    $this->DataPublicacao = $DataPublicacao;
  }

  /**
   * @return mixed
   */
  public function getNumero()
  {
    return $this->Numero;
  }

  /**
   * @param mixed $Numero
   */
  public function setNumero($Numero)
  {
    $this->Numero = $Numero;
  }

  /**
   * @return mixed
   */
  public function getIdDocumentos()
  {
    return $this->IdDocumentos;
  }

  /**
   * @param mixed $IdDocumentos
   */
  public function setIdDocumentos($IdDocumentos)
  {
    $this->IdDocumentos = $IdDocumentos;
  }
}
?>