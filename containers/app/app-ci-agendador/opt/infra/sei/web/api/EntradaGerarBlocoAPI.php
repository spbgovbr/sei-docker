<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaGerarBlocoAPI {
    private $Tipo;
    private $Descricao;
    private $UnidadesDisponibilizacao;
    private $Documentos;
    private $IdDocumentos;
    private $SinDisponibilizar;


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
  public function getDocumentos()
  {
    return $this->Documentos;
  }

  /**
   * @param mixed $Documentos
   */
  public function setDocumentos($Documentos)
  {
    $this->Documentos = $Documentos;
  }

  /**
   * @return mixed
   */
  public function getIdDocumentos()
  {
    return $this->IdDocumentos;
  }

  /**
   * @param mixed $IdDocumentos
   */
  public function setIdDocumentos($IdDocumentos)
  {
    $this->IdDocumentos = $IdDocumentos;
  }

  /**
   * @return mixed
   */
  public function getSinDisponibilizar()
  {
    return $this->SinDisponibilizar;
  }

  /**
   * @param mixed $SinDisponibilizar
   */
  public function setSinDisponibilizar($SinDisponibilizar)
  {
    $this->SinDisponibilizar = $SinDisponibilizar;
  }

}
?>