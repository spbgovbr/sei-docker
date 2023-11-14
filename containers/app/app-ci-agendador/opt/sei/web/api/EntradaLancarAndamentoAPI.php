<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaLancarAndamentoAPI {
  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $IdTarefa;
  private $IdTarefaModulo;
  private $Atributos;

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
  public function getIdTarefa()
  {
    return $this->IdTarefa;
  }

  /**
   * @param mixed $IdTarefa
   */
  public function setIdTarefa($IdTarefa)
  {
    $this->IdTarefa = $IdTarefa;
  }

  /**
   * @return mixed
   */
  public function getIdTarefaModulo()
  {
    return $this->IdTarefaModulo;
  }

  /**
   * @param mixed $IdTarefaModulo
   */
  public function setIdTarefaModulo($IdTarefaModulo)
  {
    $this->IdTarefaModulo = $IdTarefaModulo;
  }

  /**
   * @return mixed
   */
  public function getAtributos()
  {
    return $this->Atributos;
  }

  /**
   * @param mixed $Atributos
   */
  public function setAtributos($Atributos)
  {
    $this->Atributos = $Atributos;
  }
}
?>