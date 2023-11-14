<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class AcessoExternoAPI {
  private $IdAcessoExterno;
  private $DataValidade;
  private $Procedimento;
  private $Documento;
  private $SinAcessoProcesso;

  /**
   * @return mixed
   */
  public function getIdAcessoExterno()
  {
    return $this->IdAcessoExterno;
  }

  /**
   * @param mixed $IdAcessoExterno
   */
  public function setIdAcessoExterno($IdAcessoExterno)
  {
    $this->IdAcessoExterno = $IdAcessoExterno;
  }

  /**
   * @return mixed
   */
  public function getDataValidade()
  {
    return $this->DataValidade;
  }

  /**
   * @param mixed $DataValidade
   */
  public function setDataValidade($DataValidade)
  {
    $this->DataValidade = $DataValidade;
  }

  /**
   * @return mixed
   */
  public function getProcedimento()
  {
    return $this->Procedimento;
  }

  /**
   * @param mixed $Procedimento
   */
  public function setProcedimento($Procedimento)
  {
    $this->Procedimento = $Procedimento;
  }

  /**
   * @return mixed
   */
  public function getDocumento()
  {
    return $this->Documento;
  }

  /**
   * @param mixed $Documento
   */
  public function setDocumento($Documento)
  {
    $this->Documento = $Documento;
  }

  /**
   * @return mixed
   */
  public function getSinAcessoProcesso()
  {
    return $this->SinAcessoProcesso;
  }

  /**
   * @param mixed $SinAcessoProcesso
   */
  public function setSinAcessoProcesso($SinAcessoProcesso)
  {
    $this->SinAcessoProcesso = $SinAcessoProcesso;
  }
}
?>