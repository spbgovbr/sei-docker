<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/05/2022 - criado por mga
*
*/

class EntradaRegistrarAnotacaoAPI
{
  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $Descricao;
  private $SinPrioridade;

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
  public function setIdProcedimento($IdProcedimento): void
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
  public function setProtocoloProcedimento($ProtocoloProcedimento): void
  {
    $this->ProtocoloProcedimento = $ProtocoloProcedimento;
  }

  /**
   * @return mixed
   */
  public function getDescricao()
  {
    return $this->Descricao;
  }

  /**
   * @param mixed $Descricao
   */
  public function setDescricao($Descricao): void
  {
    $this->Descricao = $Descricao;
  }

  /**
   * @return mixed
   */
  public function getSinPrioridade()
  {
    return $this->SinPrioridade;
  }

  /**
   * @param mixed $SinPrioridade
   */
  public function setSinPrioridade($SinPrioridade): void
  {
    $this->SinPrioridade = $SinPrioridade;
  }
}
?>