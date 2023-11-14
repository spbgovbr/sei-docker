<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2014 - criado por mga
*
*/

class EntradaListarUnidadesAPI {
  private $IdUnidade;
  private $IdOrgao;
  private $PalavrasPesquisa;

  /**
   * @return mixed
   */
  public function getIdUnidade()
  {
    return $this->IdUnidade;
  }

  /**
   * @param mixed $IdUnidade
   */
  public function setIdUnidade($IdUnidade)
  {
    $this->IdUnidade = $IdUnidade;
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
  public function getPalavrasPesquisa()
  {
    return $this->PalavrasPesquisa;
  }

  /**
   * @param mixed $PalavrasPesquisa
   */
  public function setPalavrasPesquisa($PalavrasPesquisa)
  {
    $this->PalavrasPesquisa = $PalavrasPesquisa;
  }
}
?>