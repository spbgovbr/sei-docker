<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/05/2017 - criado por mga
 *
 */

class EntradaConsultarProcedimentoIndividualAPI {
  private $IdOrgaoProcedimento;
  private $IdTipoProcedimento;
  private $IdOrgaoUsuario;
  private $SiglaUsuario;

  /**
   * @return mixed
   */
  public function getIdOrgaoProcedimento()
  {
    return $this->IdOrgaoProcedimento;
  }

  /**
   * @param mixed $IdOrgaoProcedimento
   */
  public function setIdOrgaoProcedimento($IdOrgaoProcedimento)
  {
    $this->IdOrgaoProcedimento = $IdOrgaoProcedimento;
  }

  /**
   * @return mixed
   */
  public function getIdTipoProcedimento()
  {
    return $this->IdTipoProcedimento;
  }

  /**
   * @param mixed $IdTipoProcedimento
   */
  public function setIdTipoProcedimento($IdTipoProcedimento)
  {
    $this->IdTipoProcedimento = $IdTipoProcedimento;
  }

  /**
   * @return mixed
   */
  public function getIdOrgaoUsuario()
  {
    return $this->IdOrgaoUsuario;
  }

  /**
   * @param mixed $IdOrgaoUsuario
   */
  public function setIdOrgaoUsuario($IdOrgaoUsuario)
  {
    $this->IdOrgaoUsuario = $IdOrgaoUsuario;
  }

  /**
   * @return mixed
   */
  public function getSiglaUsuario()
  {
    return $this->SiglaUsuario;
  }

  /**
   * @param mixed $SiglaUsuario
   */
  public function setSiglaUsuario($SiglaUsuario)
  {
    $this->SiglaUsuario = $SiglaUsuario;
  }
}
?>