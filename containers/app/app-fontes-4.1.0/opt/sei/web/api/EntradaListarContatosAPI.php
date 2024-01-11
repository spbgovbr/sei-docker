<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/04/2016 - criado por mga
*
*/

class EntradaListarContatosAPI {
  private $IdContatos;
  private $IdTipoContato;
  private $PaginaRegistros;
  private $PaginaAtual;
  private $Sigla;
  private $Nome;
  private $Cpf;
  private $Cnpj;
  private $Matricula;

  /**
   * @return mixed
   */
  public function getIdContatos()
  {
    return $this->IdContatos;
  }

  /**
   * @param mixed $IdContatos
   */
  public function setIdContatos($IdContatos)
  {
    $this->IdContatos = $IdContatos;
  }

  /**
   * @return mixed
   */
  public function getIdTipoContato()
  {
    return $this->IdTipoContato;
  }

  /**
   * @param mixed $IdTipoContato
   */
  public function setIdTipoContato($IdTipoContato)
  {
    $this->IdTipoContato = $IdTipoContato;
  }

  /**
   * @return mixed
   */
  public function getPaginaRegistros()
  {
    return $this->PaginaRegistros;
  }

  /**
   * @param mixed $PaginaRegistros
   */
  public function setPaginaRegistros($PaginaRegistros)
  {
    $this->PaginaRegistros = $PaginaRegistros;
  }


  /**
   * @return mixed
   */
  public function getPaginaAtual()
  {
    return $this->PaginaAtual;
  }

  /**
   * @param mixed $PaginaAtual
   */
  public function setPaginaAtual($PaginaAtual)
  {
    $this->PaginaAtual = $PaginaAtual;
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
  public function setCpf($Cpf)
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
  public function setCnpj($Cnpj)
  {
    $this->Cnpj = $Cnpj;
  }

  /**
   * @return mixed
   */
  public function getMatricula()
  {
    return $this->Matricula;
  }

  /**
   * @param mixed $Matricula
   */
  public function setMatricula($Matricula)
  {
    $this->Matricula = $Matricula;
  }

}
?>