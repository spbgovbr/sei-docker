<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/05/2023 - criado por mga
 *
 */

class PaginaComplementoAPI{

  private $Css;
  private $JavascriptGlobal;
  private $JavascriptInicializacao;
  private $JavascriptValidacao;
  private $Html;

  /**
   * @return mixed
   */
  public function getCss() {
    return $this->Css;
  }

  /**
   * @param mixed $Css
   */
  public function setCss($Css): void {
    $this->Css = $Css;
  }

  /**
   * @return mixed
   */
  public function getJavascriptGlobal() {
    return $this->JavascriptGlobal;
  }

  /**
   * @param mixed $JavascriptGlobal
   */
  public function setJavascriptGlobal($JavascriptGlobal): void {
    $this->JavascriptGlobal = $JavascriptGlobal;
  }

  /**
   * @return mixed
   */
  public function getJavascriptInicializacao() {
    return $this->JavascriptInicializacao;
  }

  /**
   * @param mixed $JavascriptInicializacao
   */
  public function setJavascriptInicializacao($JavascriptInicializacao): void {
    $this->JavascriptInicializacao = $JavascriptInicializacao;
  }

  /**
   * @return mixed
   */
  public function getJavascriptValidacao() {
    return $this->JavascriptValidacao;
  }

  /**
   * @param mixed $JavascriptValidacao
   */
  public function setJavascriptValidacao($JavascriptValidacao): void {
    $this->JavascriptValidacao = $JavascriptValidacao;
  }

  /**
   * @return mixed
   */
  public function getHtml() {
    return $this->Html;
  }

  /**
   * @param mixed $Html
   */
  public function setHtml($Html): void {
    $this->Html = $Html;
  }
}