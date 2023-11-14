<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/07/2014 - criado por mga
*
*/

class SaidaIncluirDocumentoAPI {
  private $IdDocumento;
  private $DocumentoFormatado;
  private $LinkAcesso;

  /**
   * @return mixed
   */
  public function getIdDocumento()
  {
    return $this->IdDocumento;
  }

  /**
   * @param mixed $IdDocumento
   */
  public function setIdDocumento($IdDocumento)
  {
    $this->IdDocumento = $IdDocumento;
  }

  /**
   * @return mixed
   */
  public function getDocumentoFormatado()
  {
    return $this->DocumentoFormatado;
  }

  /**
   * @param mixed $DocumentoFormatado
   */
  public function setDocumentoFormatado($DocumentoFormatado)
  {
    $this->DocumentoFormatado = $DocumentoFormatado;
  }

  /**
   * @return mixed
   */
  public function getLinkAcesso()
  {
    return $this->LinkAcesso;
  }

  /**
   * @param mixed $LinkAcesso
   */
  public function setLinkAcesso($LinkAcesso)
  {
    $this->LinkAcesso = $LinkAcesso;
  }

}
?>