<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class AndamentoMarcadorAPI{
  private $IdAndamentoMarcador;
  private $Texto;
  private $DataHora;
  private $Usuario;
  private $Marcador;

  /**
   * @return mixed
   */
  public function getIdAndamentoMarcador()
  {
    return $this->IdAndamentoMarcador;
  }

  /**
   * @param mixed $IdAndamentoMarcador
   */
  public function setIdAndamentoMarcador($IdAndamentoMarcador)
  {
    $this->IdAndamentoMarcador = $IdAndamentoMarcador;
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

  /**
   * @return mixed
   */
  public function getDataHora()
  {
    return $this->DataHora;
  }

  /**
   * @param mixed $DataHora
   */
  public function setDataHora($DataHora)
  {
    $this->DataHora = $DataHora;
  }

  /**
   * @return mixed
   */
  public function getUsuario()
  {
    return $this->Usuario;
  }

  /**
   * @param mixed $Usuario
   */
  public function setUsuario($Usuario)
  {
    $this->Usuario = $Usuario;
  }

  /**
   * @return mixed
   */
  public function getMarcador()
  {
    return $this->Marcador;
  }

  /**
   * @param mixed $Marcador
   */
  public function setMarcador($Marcador)
  {
    $this->Marcador = $Marcador;
  }
}
?>