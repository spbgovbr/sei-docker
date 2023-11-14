<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/08/2014 - criado por mga
*
*/

class EntradaListarExtensoesPermitidasAPI{

  private $IdArquivoExtensao;

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

}
?>