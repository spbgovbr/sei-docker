<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class SaidaConsultarBlocoAPI {
  private $IdBloco;
  private $Descricao;
  private $Tipo;
  private $Estado;
  private $Unidade;
  private $Usuario;
  private $UnidadesDisponibilizacao;
  private $Protocolos;
  private $SinPrioridade;
  private $SinRevisao;
  private $UsuarioAtribuicao;

  /**
   * @return mixed
   */
  public function getIdBloco()
  {
    return $this->IdBloco;
  }

  /**
   * @param mixed $IdBloco
   */
  public function setIdBloco($IdBloco)
  {
    $this->IdBloco = $IdBloco;
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
  public function getTipo()
  {
    return $this->Tipo;
  }

  /**
   * @param mixed $Tipo
   */
  public function setTipo($Tipo)
  {
    $this->Tipo = $Tipo;
  }

  /**
   * @return mixed
   */
  public function getEstado()
  {
    return $this->Estado;
  }

  /**
   * @param mixed $Estado
   */
  public function setEstado($Estado)
  {
    $this->Estado = $Estado;
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
  public function getUnidadesDisponibilizacao()
  {
    return $this->UnidadesDisponibilizacao;
  }

  /**
   * @param mixed $UnidadesDisponibilizacao
   */
  public function setUnidadesDisponibilizacao($UnidadesDisponibilizacao)
  {
    $this->UnidadesDisponibilizacao = $UnidadesDisponibilizacao;
  }

  /**
   * @return mixed
   */
  public function getProtocolos()
  {
    return $this->Protocolos;
  }

  /**
   * @param mixed $Protocolos
   */
  public function setProtocolos($Protocolos)
  {
    $this->Protocolos = $Protocolos;
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
  public function setSinPrioridade($SinPrioridade)
  {
    $this->SinPrioridade = $SinPrioridade;
  }

  /**
   * @return mixed
   */
  public function getSinRevisao()
  {
    return $this->SinRevisao;
  }

  /**
   * @param mixed $SinRevisao
   */
  public function setSinRevisao($SinRevisao)
  {
    $this->SinRevisao = $SinRevisao;
  }

  /**
   * @return mixed
   */
  public function getUsuarioAtribuicao()
  {
    return $this->UsuarioAtribuicao;
  }

  /**
   * @param mixed $UsuarioAtribuicao
   */
  public function setUsuarioAtribuicao($UsuarioAtribuicao)
  {
    $this->UsuarioAtribuicao = $UsuarioAtribuicao;
  }

}
?>