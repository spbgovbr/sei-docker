<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class PublicacaoImprensaNacionalAPI {
  private $IdVeiculo;
  private $SiglaVeiculo;
  private $DescricaoVeiculo;
  private $Pagina;
  private $IdSecao;
  private $Secao;
  private $Data;

  /**
   * @return mixed
   */
  public function getIdVeiculo()
  {
    return $this->IdVeiculo;
  }

  /**
   * @param mixed $IdVeiculo
   */
  public function setIdVeiculo($IdVeiculo)
  {
    $this->IdVeiculo = $IdVeiculo;
  }

  /**
   * @return mixed
   */
  public function getSiglaVeiculo()
  {
    return $this->SiglaVeiculo;
  }

  /**
   * @param mixed $SiglaVeiculo
   */
  public function setSiglaVeiculo($SiglaVeiculo)
  {
    $this->SiglaVeiculo = $SiglaVeiculo;
  }

  /**
   * @return mixed
   */
  public function getDescricaoVeiculo()
  {
    return $this->DescricaoVeiculo;
  }

  /**
   * @param mixed $DescricaoVeiculo
   */
  public function setDescricaoVeiculo($DescricaoVeiculo)
  {
    $this->DescricaoVeiculo = $DescricaoVeiculo;
  }

  /**
   * @return mixed
   */
  public function getPagina()
  {
    return $this->Pagina;
  }

  /**
   * @param mixed $Pagina
   */
  public function setPagina($Pagina)
  {
    $this->Pagina = $Pagina;
  }

  /**
   * @return mixed
   */
  public function getIdSecao()
  {
    return $this->IdSecao;
  }

  /**
   * @param mixed $IdSecao
   */
  public function setIdSecao($IdSecao)
  {
    $this->IdSecao = $IdSecao;
  }

  /**
   * @return mixed
   */
  public function getSecao()
  {
    return $this->Secao;
  }

  /**
   * @param mixed $Secao
   */
  public function setSecao($Secao)
  {
    $this->Secao = $Secao;
  }

  /**
   * @return mixed
   */
  public function getData()
  {
    return $this->Data;
  }

  /**
   * @param mixed $Data
   */
  public function setData($Data)
  {
    $this->Data = $Data;
  }

}
?>