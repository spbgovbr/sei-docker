<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class CidadeAPI{
  private $IdCidade;
  private $IdEstado;
  private $IdPais;
  private $Nome;
  private $CodigoIbge;
  private $SinCapital;
  private $Latitude;
  private $Longitude;

  /**
   * @return mixed
   */
  public function getIdCidade()
  {
    return $this->IdCidade;
  }

  /**
   * @param mixed $IdCidade
   */
  public function setIdCidade($IdCidade)
  {
    $this->IdCidade = $IdCidade;
  }

  /**
   * @return mixed
   */
  public function getIdEstado()
  {
    return $this->IdEstado;
  }

  /**
   * @param mixed $IdEstado
   */
  public function setIdEstado($IdEstado)
  {
    $this->IdEstado = $IdEstado;
  }

  /**
   * @return mixed
   */
  public function getIdPais()
  {
    return $this->IdPais;
  }

  /**
   * @param mixed $IdPais
   */
  public function setIdPais($IdPais)
  {
    $this->IdPais = $IdPais;
  }

  /**
   * @return mixed
   */
  public function getNome()
  {
    return $this->Nome;
  }

  /**
   * @param mixed $Nome
   */
  public function setNome($Nome)
  {
    $this->Nome = $Nome;
  }

  /**
   * @return mixed
   */
  public function getCodigoIbge()
  {
    return $this->CodigoIbge;
  }

  /**
   * @param mixed $CodigoIbge
   */
  public function setCodigoIbge($CodigoIbge)
  {
    $this->CodigoIbge = $CodigoIbge;
  }

  /**
   * @return mixed
   */
  public function getSinCapital()
  {
    return $this->SinCapital;
  }

  /**
   * @param mixed $SinCapital
   */
  public function setSinCapital($SinCapital)
  {
    $this->SinCapital = $SinCapital;
  }

  /**
   * @return mixed
   */
  public function getLatitude()
  {
    return $this->Latitude;
  }

  /**
   * @param mixed $Latitude
   */
  public function setLatitude($Latitude)
  {
    $this->Latitude = $Latitude;
  }

  /**
   * @return mixed
   */
  public function getLongitude()
  {
    return $this->Longitude;
  }

  /**
   * @param mixed $Longitude
   */
  public function setLongitude($Longitude)
  {
    $this->Longitude = $Longitude;
  }

}