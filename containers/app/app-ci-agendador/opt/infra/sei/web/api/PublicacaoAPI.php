<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class PublicacaoAPI {
  private $IdPublicacao;
  private $IdDocumento;
  private $IdSerieDocumento;
  private $IdVeiculoPublicacao;
  private $NomeVeiculo;
  private $StaTipoVeiculo;
  private $StaMotivo;
  private $Numero;
  private $DataDisponibilizacao;
  private $DataPublicacao;
  private $Resumo;
  private $Estado;
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
  public function getNomeVeiculo()
  {
    return $this->NomeVeiculo;
  }

  /**
   * @param mixed $NomeVeiculo
   */
  public function setNomeVeiculo($NomeVeiculo)
  {
    $this->NomeVeiculo = $NomeVeiculo;
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
  public function getEstado()
  {
    return $this->Estado;
  }

  /**
   * @param mixed $Estado
   */
  public function setEstado($Estado)
  {
    $this->Estado = $Estado;
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
  public function getIdSerieDocumento()
  {
    return $this->IdSerieDocumento;
  }

  /**
   * @param mixed $IdSerieDocumento
   */
  public function setIdSerieDocumento($IdSerieDocumento)
  {
    $this->IdSerieDocumento = $IdSerieDocumento;
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


  /**
   * @return mixed
   */
  public function getStaTipoVeiculo()
  {
    return $this->StaTipoVeiculo;
  }

  /**
   * @param mixed $ImprensaNacional
   */
  public function setStaTipoVeiculo($StaTipoVeiculo)
  {
    $this->StaTipoVeiculo = $StaTipoVeiculo;
  }
}
?>