<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaExcluirBlocoAPI {
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