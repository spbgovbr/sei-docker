<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/12/2015 - criado por mga
*
*/

class EntradaAdicionarConteudoArquivoAPI {
  private $IdArquivo;
  private $Conteudo;

  /**
   * @return mixed
   */
  public function getIdArquivo()
  {
    return $this->IdArquivo;
  }

  /**
   * @param mixed $IdArquivo
   */
  public function setIdArquivo($IdArquivo)
  {
    $this->IdArquivo = $IdArquivo;
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