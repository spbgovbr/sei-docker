<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/09/2016 - criado por mga
*/

class ArvoreAcaoItemAPI{
  private $Tipo;
  private $Id;
  private $IdPai;
  private $Href;
  private $Target;
  private $Title;
  private $Icone;
  private $SinHabilitado;

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
  public function getId()
  {
    return $this->Id;
  }

  /**
   * @param mixed $Id
   */
  public function setId($Id)
  {
    $this->Id = $Id;
  }

  /**
   * @return mixed
   */
  public function getIdPai()
  {
    return $this->IdPai;
  }

  /**
   * @param mixed $IdPai
   */
  public function setIdPai($IdPai)
  {
    $this->IdPai = $IdPai;
  }

  /**
   * @return mixed
   */
  public function getHref()
  {
    return $this->Href;
  }

  /**
   * @param mixed $Href
   */
  public function setHref($Href)
  {
    $this->Href = $Href;
  }

  /**
   * @return mixed
   */
  public function getTarget()
  {
    return $this->Target;
  }

  /**
   * @param mixed $Target
   */
  public function setTarget($Target)
  {
    $this->Target = $Target;
  }

  /**
   * @return mixed
   */
  public function getTitle()
  {
    return $this->Title;
  }

  /**
   * @param mixed $Title
   */
  public function setTitle($Title)
  {
    $this->Title = $Title;
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
  public function getSinHabilitado()
  {
    return $this->SinHabilitado;
  }

  /**
   * @param mixed $SinHabilitado
   */
  public function setSinHabilitado($SinHabilitado)
  {
    $this->SinHabilitado = $SinHabilitado;
  }
}
?>