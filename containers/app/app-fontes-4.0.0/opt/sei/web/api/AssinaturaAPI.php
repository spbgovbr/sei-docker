<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class AssinaturaAPI {
  private $Nome;
  private $CargoFuncao;
  private $DataHora;
  private $IdUsuario;
  private $IdOrigem;
  private $IdOrgao;
  private $Sigla;

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
  public function getCargoFuncao()
  {
    return $this->CargoFuncao;
  }

  /**
   * @param mixed $CargoFuncao
   */
  public function setCargoFuncao($CargoFuncao)
  {
    $this->CargoFuncao = $CargoFuncao;
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
  public function getIdUsuario()
  {
    return $this->IdUsuario;
  }

  /**
   * @param mixed $IdUsuario
   */
  public function setIdUsuario($IdUsuario)
  {
    $this->IdUsuario = $IdUsuario;
  }

  /**
   * @return mixed
   */
  public function getIdOrigem()
  {
    return $this->IdOrigem;
  }

  /**
   * @param mixed $IdOrigem
   */
  public function setIdOrigem($IdOrigem)
  {
    $this->IdOrigem = $IdOrigem;
  }

  /**
   * @return mixed
   */
  public function getIdOrgao()
  {
    return $this->IdOrgao;
  }

  /**
   * @param mixed $IdOrgao
   */
  public function setIdOrgao($IdOrgao)
  {
    $this->IdOrgao = $IdOrgao;
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
}
?>