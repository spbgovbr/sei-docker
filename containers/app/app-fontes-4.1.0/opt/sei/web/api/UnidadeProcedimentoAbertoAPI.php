<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class UnidadeProcedimentoAbertoAPI {
  private $Unidade;
  private $UsuarioAtribuicao;

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