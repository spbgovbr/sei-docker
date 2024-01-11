<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/01/2022 - criado por mgb29
 *
 */

class BlocoAPI {
  private $IdBloco;
  private $Descricao;
  private $Tipo;
  private $Estado;
  private $Unidade;
  private $Usuario;
  private $UnidadesDisponibilizacao;
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
  public function setIdBloco($IdBloco): void
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
  public function setDescricao($Descricao): void
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
  public function setTipo($Tipo): void
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
  public function setEstado($Estado): void
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
  public function setUnidade($Unidade): void
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
  public function setUsuario($Usuario): void
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
  public function setUnidadesDisponibilizacao($UnidadesDisponibilizacao): void
  {
    $this->UnidadesDisponibilizacao = $UnidadesDisponibilizacao;
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
  public function setSinRevisao($SinRevisao): void
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
  public function setUsuarioAtribuicao($UsuarioAtribuicao): void
  {
    $this->UsuarioAtribuicao = $UsuarioAtribuicao;
  }

}
?>