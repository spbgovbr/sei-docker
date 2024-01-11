<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/09/2013 - criado por MGA
*
* @package infra_php
*/

class InfraAtributoDTO {
  private $strNomeClasse;
  private $arrAtributo;

  public function setStrNomeClasse($strNomeClasse){
    $this->strNomeClasse = $strNomeClasse;
  }
  
  public function getStrNomeClasse(){
    return $this->strNomeClasse;
  }
  
  public function setArrAtributo($arrAtributo){
    $this->arrAtributo = $arrAtributo;
  }
  
  public function getArrAtributo(){
    return $this->arrAtributo;
  }

  public function __toString() {
    return $this->strNomeClasse.':'.print_r($this->arrAtributo,true);
  }
}
?>