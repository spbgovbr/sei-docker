<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class MarcadorAPI{
  private $IdMarcador;
  private $Nome;
  private $Icone;
  private $SinAtivo;

  /**
   * @return mixed
   */
  public function getIdMarcador()
  {
    return $this->IdMarcador;
  }

  /**
   * @param mixed $IdMarcador
   */
  public function setIdMarcador($IdMarcador)
  {
    $this->IdMarcador = $IdMarcador;
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
  public function getIcone()
  {
    return $this->Icone;
  }

  /**
   * @param mixed $Icone
   */
  public function setIcone($Icone)
  {
    $this->Icone = $Icone;
  }

  /**
   * @return mixed
   */
  public function getSinAtivo()
  {
    return $this->SinAtivo;
  }

  /**
   * @param mixed $SinAtivo
   */
  public function setSinAtivo($SinAtivo)
  {
    $this->SinAtivo = $SinAtivo;
  }
}