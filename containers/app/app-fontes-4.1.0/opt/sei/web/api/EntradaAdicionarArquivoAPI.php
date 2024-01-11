<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/12/2015 - criado por mga
*
*/

class EntradaAdicionarArquivoAPI {
  private $Nome;
  private $Tamanho;
  private $Hash;
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
  public function getTamanho()
  {
    return $this->Tamanho;
  }

  /**
   * @param mixed $Tamanho
   */
  public function setTamanho($Tamanho)
  {
    $this->Tamanho = $Tamanho;
  }

  /**
   * @return mixed
   */
  public function getHash()
  {
    return $this->Hash;
  }

  /**
   * @param mixed $Hash
   */
  public function setHash($Hash)
  {
    $this->Hash = $Hash;
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