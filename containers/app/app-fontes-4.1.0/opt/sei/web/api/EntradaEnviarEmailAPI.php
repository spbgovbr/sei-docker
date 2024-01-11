<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/05/2019 - criado por mga
*
*/

class EntradaEnviarEmailAPI {

  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $De;
  private $Para;
  private $CCO;
  private $Assunto;
  private $Mensagem;
  private $Arquivos;
  private $IdDocumentos;

  /**
   * @return mixed
   */
  public function getIdProcedimento()
  {
    return $this->IdProcedimento;
  }

  /**
   * @param mixed $IdProcedimento
   */
  public function setIdProcedimento($IdProcedimento): void
  {
    $this->IdProcedimento = $IdProcedimento;
  }

  /**
   * @return mixed
   */
  public function getProtocoloProcedimento()
  {
    return $this->ProtocoloProcedimento;
  }

  /**
   * @param mixed $ProtocoloProcedimento
   */
  public function setProtocoloProcedimento($ProtocoloProcedimento): void
  {
    $this->ProtocoloProcedimento = $ProtocoloProcedimento;
  }

  /**
   * @return mixed
   */
  public function getDe()
  {
    return $this->De;
  }

  /**
   * @param mixed $De
   */
  public function setDe($De): void
  {
    $this->De = $De;
  }

  /**
   * @return mixed
   */
  public function getPara()
  {
    return $this->Para;
  }

  /**
   * @param mixed $Para
   */
  public function setPara($Para): void
  {
    $this->Para = $Para;
  }

  /**
   * @return mixed
   */
  public function getCCO()
  {
    return $this->CCO;
  }

  /**
   * @param mixed $CCO
   */
  public function setCCO($CCO): void
  {
    $this->CCO = $CCO;
  }

  /**
   * @return mixed
   */
  public function getAssunto()
  {
    return $this->Assunto;
  }

  /**
   * @param mixed $Assunto
   */
  public function setAssunto($Assunto): void
  {
    $this->Assunto = $Assunto;
  }

  /**
   * @return mixed
   */
  public function getMensagem()
  {
    return $this->Mensagem;
  }

  /**
   * @param mixed $Mensagem
   */
  public function setMensagem($Mensagem): void
  {
    $this->Mensagem = $Mensagem;
  }

  /**
   * @return mixed
   */
  public function getArquivos()
  {
    return $this->Arquivos;
  }

  /**
   * @param mixed $Arquivos
   */
  public function setArquivos($Arquivos): void
  {
    $this->Arquivos = $Arquivos;
  }

  /**
   * @return mixed
   */
  public function getIdDocumentos()
  {
    return $this->IdDocumentos;
  }

  /**
   * @param mixed $IdDocumentos
   */
  public function setIdDocumentos($IdDocumentos): void
  {
    $this->IdDocumentos = $IdDocumentos;
  }
}
?>