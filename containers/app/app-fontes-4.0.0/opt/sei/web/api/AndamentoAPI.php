<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class AndamentoAPI {
  private $IdAndamento;
  private $IdTarefa;
  private $IdTarefaModulo;
  private $Descricao;
  private $DataHora;
  private $Usuario;
  private $Unidade;
  private $Atributos;
  private $IdProtocolo;

  /**
   * @return mixed
   */
  public function getIdAndamento()
  {
    return $this->IdAndamento;
  }

  /**
   * @param mixed $IdAndamento
   */
  public function setIdAndamento($IdAndamento)
  {
    $this->IdAndamento = $IdAndamento;
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
  public function getDescricao()
  {
    return $this->Descricao;
  }

  /**
   * @param mixed $Descricao
   */
  public function setDescricao($Descricao)
  {
    $this->Descricao = $Descricao;
  }

  /**
   * @return mixed
   */
  public function getDataHora()
  {
    return $this->DataHora;
  }

  /**
   * @param mixed $DataHora
   */
  public function setDataHora($DataHora)
  {
    $this->DataHora = $DataHora;
  }

  /**
   * @return mixed
   */
  public function getUsuario()
  {
    return $this->Usuario;
  }

  /**
   * @param mixed $Usuario
   */
  public function setUsuario($Usuario)
  {
    $this->Usuario = $Usuario;
  }

  /**
   * @return mixed
   */
  public function getUnidade()
  {
    return $this->Unidade;
  }

  /**
   * @param mixed $Unidade
   */
  public function setUnidade($Unidade)
  {
    $this->Unidade = $Unidade;
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

  /**
   * @return mixed
   */
  public function getIdProtocolo()
  {
    return $this->IdProtocolo;
  }

  /**
   * @param mixed $IdProtocolo
   */
  public function setIdProtocolo($IdProtocolo)
  {
    $this->IdProtocolo = $IdProtocolo;
  }
}
?>