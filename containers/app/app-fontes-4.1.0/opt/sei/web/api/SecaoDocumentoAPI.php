<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 05/01/2022 - criado por mga
 *
 */

class SecaoDocumentoAPI {
  private $Nome;
  private $Conteudo;

  /**
   * @return mixed
   */
  public function getNome()
  {
    return $this->Nome;
  }

  /**
   * @param mixed $Nome
   */
  public function setNome($Nome)
  {
    $this->Nome = $Nome;
  }

  /**
   * @return mixed
   */
  public function getConteudo()
  {
    return $this->Conteudo;
  }

  /**
   * @param mixed $Conteudo
   */
  public function setConteudo($Conteudo)
  {
    $this->Conteudo = $Conteudo;
  }
  
}
?>