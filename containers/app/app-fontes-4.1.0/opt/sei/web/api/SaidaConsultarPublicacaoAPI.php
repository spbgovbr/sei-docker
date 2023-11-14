<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/09/2018 - criado por mga
*
*/

class SaidaConsultarPublicacaoAPI {
  private $Publicacao;
  private $Andamento;
  private $Assinaturas;

  /**
   * @return mixed
   */
  public function getPublicacao()
  {
    return $this->Publicacao;
  }

  /**
   * @param mixed $Publicacao
   */
  public function setPublicacao($Publicacao)
  {
    $this->Publicacao = $Publicacao;
  }

  /**
   * @return mixed
   */
  public function getAndamento()
  {
    return $this->Andamento;
  }

  /**
   * @param mixed $Andamento
   */
  public function setAndamento($Andamento)
  {
    $this->Andamento = $Andamento;
  }

  /**
   * @return mixed
   */
  public function getAssinaturas()
  {
    return $this->Assinaturas;
  }

  /**
   * @param mixed $Assinaturas
   */
  public function setAssinaturas($Assinaturas)
  {
    $this->Assinaturas = $Assinaturas;
  }
}
?>