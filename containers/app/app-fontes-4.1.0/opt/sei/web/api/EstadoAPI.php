<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class EstadoAPI {
  private $IdEstado;
  private $IdPais;
  private $Sigla;
  private $Nome;
  private $CodigoIbge;

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
  public function getSigla()
  {
    return $this->Sigla;
  }

  /**
   * @param mixed $Sigla
   */
  public function setSigla($Sigla)
  {
    $this->Sigla = $Sigla;
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
}