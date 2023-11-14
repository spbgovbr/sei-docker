<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaListarAndamentosAPI {
  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $SinRetornarAtributos;
  private $Andamentos;
  private $Tarefas;
  private $TarefasModulos;

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
  public function getSinRetornarAtributos()
  {
    return $this->SinRetornarAtributos;
  }

  /**
   * @param mixed $SinRetornarAtributos
   */
  public function setSinRetornarAtributos($SinRetornarAtributos)
  {
    $this->SinRetornarAtributos = $SinRetornarAtributos;
  }

  /**
   * @return mixed
   */
  public function getAndamentos()
  {
    return $this->Andamentos;
  }

  /**
   * @param mixed $Andamentos
   */
  public function setAndamentos($Andamentos)
  {
    $this->Andamentos = $Andamentos;
  }

  /**
   * @return mixed
   */
  public function getTarefas()
  {
    return $this->Tarefas;
  }

  /**
   * @param mixed $Tarefas
   */
  public function setTarefas($Tarefas)
  {
    $this->Tarefas = $Tarefas;
  }

  /**
   * @return mixed
   */
  public function getTarefasModulos()
  {
    return $this->TarefasModulos;
  }

  /**
   * @param mixed $TarefasModulos
   */
  public function setTarefasModulos($TarefasModulos)
  {
    $this->TarefasModulos = $TarefasModulos;
  }
}
?>