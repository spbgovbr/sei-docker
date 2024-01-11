<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/08/2014 - criado por mga
*
*/

class EntradaAtribuirProcessoAPI {
  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $IdUsuario;
  private $SinReabrir;

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
  public function getSinReabrir()
  {
    return $this->SinReabrir;
  }

  /**
   * @param mixed $SinReabrir
   */
  public function setSinReabrir($SinReabrir)
  {
    $this->SinReabrir = $SinReabrir;
  }

}
?>