<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class UnidadeAPI {
  private $IdUnidade;
  private $Sigla;
  private $Descricao;
  private $Orgao;
  private $SinProtocolo;
  private $SinArquivamento;
  private $SinOuvidoria;

  /**
   * @return mixed
   */
  public function getIdUnidade()
  {
    return $this->IdUnidade;
  }

  /**
   * @param mixed $IdUnidade
   */
  public function setIdUnidade($IdUnidade)
  {
    $this->IdUnidade = $IdUnidade;
  }

  /**
   * @return mixed
   */
  public function getSigla()
  {
    return $this->Sigla;
  }

  /**
   * @param mixed $Sigla
   */
  public function setSigla($Sigla)
  {
    $this->Sigla = $Sigla;
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
  public function getOrgao()
  {
    return $this->Orgao;
  }

  /**
   * @param mixed $Orgao
   */
  public function setOrgao($Orgao)
  {
    $this->Orgao = $Orgao;
  }

  /**
   * @return mixed
   */
  public function getSinProtocolo()
  {
    return $this->SinProtocolo;
  }

  /**
   * @param mixed $SinProtocolo
   */
  public function setSinProtocolo($SinProtocolo)
  {
    $this->SinProtocolo = $SinProtocolo;
  }

  /**
   * @return mixed
   */
  public function getSinArquivamento()
  {
    return $this->SinArquivamento;
  }

  /**
   * @param mixed $SinArquivamento
   */
  public function setSinArquivamento($SinArquivamento)
  {
    $this->SinArquivamento = $SinArquivamento;
  }

  /**
   * @return mixed
   */
  public function getSinOuvidoria()
  {
    return $this->SinOuvidoria;
  }

  /**
   * @param mixed $SinOuvidoria
   */
  public function setSinOuvidoria($SinOuvidoria)
  {
    $this->SinOuvidoria = $SinOuvidoria;
  }

}
?>