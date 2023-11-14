<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/08/2014 - criado por mga
*
*/

class EntradaEnviarProcessoAPI {
  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $UnidadesDestino;
  private $SinManterAbertoUnidade;
  private $SinRemoverAnotacao;
  private $SinEnviarEmailNotificacao;
  private $DataRetornoProgramado;
  private $DiasRetornoProgramado;
  private $SinDiasUteisRetornoProgramado;
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
  public function getUnidadesDestino()
  {
    return $this->UnidadesDestino;
  }

  /**
   * @param mixed $UnidadesDestino
   */
  public function setUnidadesDestino($UnidadesDestino)
  {
    $this->UnidadesDestino = $UnidadesDestino;
  }

  /**
   * @return mixed
   */
  public function getSinManterAbertoUnidade()
  {
    return $this->SinManterAbertoUnidade;
  }

  /**
   * @param mixed $SinManterAbertoUnidade
   */
  public function setSinManterAbertoUnidade($SinManterAbertoUnidade)
  {
    $this->SinManterAbertoUnidade = $SinManterAbertoUnidade;
  }

  /**
   * @return mixed
   */
  public function getSinRemoverAnotacao()
  {
    return $this->SinRemoverAnotacao;
  }

  /**
   * @param mixed $SinRemoverAnotacao
   */
  public function setSinRemoverAnotacao($SinRemoverAnotacao)
  {
    $this->SinRemoverAnotacao = $SinRemoverAnotacao;
  }

  /**
   * @return mixed
   */
  public function getSinEnviarEmailNotificacao()
  {
    return $this->SinEnviarEmailNotificacao;
  }

  /**
   * @param mixed $SinEnviarEmailNotificacao
   */
  public function setSinEnviarEmailNotificacao($SinEnviarEmailNotificacao)
  {
    $this->SinEnviarEmailNotificacao = $SinEnviarEmailNotificacao;
  }

  /**
   * @return mixed
   */
  public function getDataRetornoProgramado()
  {
    return $this->DataRetornoProgramado;
  }

  /**
   * @param mixed $DataRetornoProgramado
   */
  public function setDataRetornoProgramado($DataRetornoProgramado)
  {
    $this->DataRetornoProgramado = $DataRetornoProgramado;
  }

  /**
   * @return mixed
   */
  public function getDiasRetornoProgramado()
  {
    return $this->DiasRetornoProgramado;
  }

  /**
   * @param mixed $DiasRetornoProgramado
   */
  public function setDiasRetornoProgramado($DiasRetornoProgramado)
  {
    $this->DiasRetornoProgramado = $DiasRetornoProgramado;
  }

  /**
   * @return mixed
   */
  public function getSinDiasUteisRetornoProgramado()
  {
    return $this->SinDiasUteisRetornoProgramado;
  }

  /**
   * @param mixed $SinDiasUteisRetornoProgramado
   */
  public function setSinDiasUteisRetornoProgramado($SinDiasUteisRetornoProgramado)
  {
    $this->SinDiasUteisRetornoProgramado = $SinDiasUteisRetornoProgramado;
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