<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/06/2021 - criado por mga
*
*/

class EntradaDevolverBlocoAPI {
  private $IdBloco;

  /**
   * @return mixed
   */
  public function getIdBloco()
  {
    return $this->IdBloco;
  }

  /**
   * @param mixed $IdBloco
   */
  public function setIdBloco($IdBloco)
  {
    $this->IdBloco = $IdBloco;
  }

}
?>