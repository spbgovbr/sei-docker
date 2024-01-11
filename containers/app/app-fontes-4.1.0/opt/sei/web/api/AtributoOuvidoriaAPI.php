<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 25/10/2019 - criado por mga
 *
 */

class AtributoOuvidoriaAPI {
  private $Id;
  private $Nome;
  private $Titulo;
  private $Valor;

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->Id;
  }

  /**
   * @param mixed $Id
   */
  public function setId($Id): void
  {
    $this->Id = $Id;
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
  public function setNome($Nome): void
  {
    $this->Nome = $Nome;
  }

  /**
   * @return mixed
   */
  public function getTitulo()
  {
    return $this->Titulo;
  }

  /**
   * @param mixed $Titulo
   */
  public function setTitulo($Titulo): void
  {
    $this->Titulo = $Titulo;
  }

  /**
   * @return mixed
   */
  public function getValor()
  {
    return $this->Valor;
  }

  /**
   * @param mixed $Valor
   */
  public function setValor($Valor): void
  {
    $this->Valor = $Valor;
  }
}