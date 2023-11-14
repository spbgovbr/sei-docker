<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/09/2018 - criado por mga
*
*/

class EntradaListarFeriadosAPI
{
  private $IdOrgao;
  private $DataInicial;
  private $DataFinal;

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
  public function getDataInicial()
  {
    return $this->DataInicial;
  }

  /**
   * @param mixed $DataInicial
   */
  public function setDataInicial($DataInicial)
  {
    $this->DataInicial = $DataInicial;
  }

  /**
   * @return mixed
   */
  public function getDataFinal()
  {
    return $this->DataFinal;
  }

  /**
   * @param mixed $DataFinal
   */
  public function setDataFinal($DataFinal)
  {
    $this->DataFinal = $DataFinal;
  }
}
?>