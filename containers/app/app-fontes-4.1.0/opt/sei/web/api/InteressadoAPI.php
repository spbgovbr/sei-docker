<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class InteressadoAPI {
  private $IdContato;
  private $Cpf;
  private $Cnpj;
  private $Sigla;
  private $Nome;

  /**
   * @return mixed
   */
  public function getIdContato()
  {
    return $this->IdContato;
  }

  /**
   * @param mixed $IdContato
   */
  public function setIdContato($IdContato): void
  {
    $this->IdContato = $IdContato;
  }

  /**
   * @return mixed
   */
  public function getCpf()
  {
    return $this->Cpf;
  }

  /**
   * @param mixed $Cpf
   */
  public function setCpf($Cpf): void
  {
    $this->Cpf = $Cpf;
  }

  /**
   * @return mixed
   */
  public function getCnpj()
  {
    return $this->Cnpj;
  }

  /**
   * @param mixed $Cnpj
   */
  public function setCnpj($Cnpj): void
  {
    $this->Cnpj = $Cnpj;
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

}
?>