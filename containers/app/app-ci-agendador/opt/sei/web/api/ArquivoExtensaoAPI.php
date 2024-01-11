<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class ArquivoExtensaoAPI {

  private $IdArquivoExtensao;
  private $Extensao;
  private $Descricao;

  /**
   * @return mixed
   */
  public function getIdArquivoExtensao()
  {
    return $this->IdArquivoExtensao;
  }

  /**
   * @param mixed $IdArquivoExtensao
   */
  public function setIdArquivoExtensao($IdArquivoExtensao)
  {
    $this->IdArquivoExtensao = $IdArquivoExtensao;
  }

  /**
   * @return mixed
   */
  public function getExtensao()
  {
    return $this->Extensao;
  }

  /**
   * @param mixed $Extensao
   */
  public function setExtensao($Extensao)
  {
    $this->Extensao = $Extensao;
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



}
?>