<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class CargoAPI{
  private $IdCargo;
  private $ExpressaoCargo;
  private $ExpressaoTratamento;
  private $ExpressaoVocativo;
  private $ExpressaoTitulo;
  private $AbreviaturaTitulo;

  /**
   * @return mixed
   */
  public function getIdCargo()
  {
    return $this->IdCargo;
  }

  /**
   * @param mixed $IdCargo
   */
  public function setIdCargo($IdCargo)
  {
    $this->IdCargo = $IdCargo;
  }

  /**
   * @return mixed
   */
  public function getExpressaoCargo()
  {
    return $this->ExpressaoCargo;
  }

  /**
   * @param mixed $ExpressaoCargo
   */
  public function setExpressaoCargo($ExpressaoCargo)
  {
    $this->ExpressaoCargo = $ExpressaoCargo;
  }

  /**
   * @return mixed
   */
  public function getExpressaoTratamento()
  {
    return $this->ExpressaoTratamento;
  }

  /**
   * @param mixed $ExpressaoTratamento
   */
  public function setExpressaoTratamento($ExpressaoTratamento)
  {
    $this->ExpressaoTratamento = $ExpressaoTratamento;
  }

  /**
   * @return mixed
   */
  public function getExpressaoVocativo()
  {
    return $this->ExpressaoVocativo;
  }

  /**
   * @param mixed $ExpressaoVocativo
   */
  public function setExpressaoVocativo($ExpressaoVocativo)
  {
    $this->ExpressaoVocativo = $ExpressaoVocativo;
  }

  /**
   * @return mixed
   */
  public function getExpressaoTitulo()
  {
    return $this->ExpressaoTitulo;
  }

  /**
   * @param mixed $ExpressaoTitulo
   */
  public function setExpressaoTitulo($ExpressaoTitulo)
  {
    $this->ExpressaoTitulo = $ExpressaoTitulo;
  }

  /**
   * @return mixed
   */
  public function getAbreviaturaTitulo()
  {
    return $this->AbreviaturaTitulo;
  }

  /**
   * @param mixed $AbreviaturaTitulo
   */
  public function setAbreviaturaTitulo($AbreviaturaTitulo)
  {
    $this->AbreviaturaTitulo = $AbreviaturaTitulo;
  }

}