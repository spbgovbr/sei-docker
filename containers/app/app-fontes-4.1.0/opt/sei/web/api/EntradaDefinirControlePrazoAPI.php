<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaDefinirControlePrazoAPI
{
  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $DataPrazo;
  private $Dias;
  private $SinDiasUteis;

  /**
   * @return mixed
   */
  public function getIdProcedimento()
  {
    return $this->IdProcedimento;
  }

  /**
   * @param mixed $IdProcedimento
   */
  public function setIdProcedimento($IdProcedimento)
  {
    $this->IdProcedimento = $IdProcedimento;
  }


  /**
   * @return mixed
   */
  public function getDias()
  {
    return $this->Dias;
  }

  /**
   * @param mixed $Dias
   */
  public function setDias($Dias)
  {
    $this->Dias = $Dias;
  }

  /**
   * @return mixed
   */
  public function getSinDiasUteis()
  {
    return $this->SinDiasUteis;
  }

  /**
   * @param mixed $SinDiasUteis
   */
  public function setSinDiasUteis($SinDiasUteis)
  {
    $this->SinDiasUteis = $SinDiasUteis;
  }

  /**
   * @return mixed
   */
  public function getProtocoloProcedimento()
  {
    return $this->ProtocoloProcedimento;
  }

  /**
   * @param mixed $ProtocoloProcedimento
   */
  public function setProtocoloProcedimento($ProtocoloProcedimento)
  {
    $this->ProtocoloProcedimento = $ProtocoloProcedimento;
  }

  /**
   * @return mixed
   */
  public function getDataPrazo()
  {
    return $this->DataPrazo;
  }

  /**
   * @param mixed $DataPrazo
   */
  public function setDataPrazo($DataPrazo)
  {
    $this->DataPrazo = $DataPrazo;
  }

}
?>