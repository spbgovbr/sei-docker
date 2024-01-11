<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/

class EntradaGerarProcedimentoAPI {
  private $Procedimento;
  private $Documentos;
  private $ProcedimentosRelacionados;
  private $UnidadesEnvio;
  private $SinManterAbertoUnidade;
  private $SinEnviarEmailNotificacao;
  private $DataRetornoProgramado;
  private $DiasRetornoProgramado;
  private $SinDiasUteisRetornoProgramado;
  private $IdMarcador;
  private $TextoMarcador;
  private $DataControlePrazo;
  private $DiasControlePrazo;
  private $SinDiasUteisControlePrazo;

  /**
   * @return mixed
   */
  public function getProcedimento()
  {
    return $this->Procedimento;
  }

  /**
   * @param mixed $Procedimento
   */
  public function setProcedimento($Procedimento)
  {
    $this->Procedimento = $Procedimento;
  }

  /**
   * @return mixed
   */
  public function getDocumentos()
  {
    return $this->Documentos;
  }

  /**
   * @param mixed $Documentos
   */
  public function setDocumentos($Documentos)
  {
    $this->Documentos = $Documentos;
  }

  /**
   * @return mixed
   */
  public function getProcedimentosRelacionados()
  {
    return $this->ProcedimentosRelacionados;
  }

  /**
   * @param mixed $ProcedimentosRelacionados
   */
  public function setProcedimentosRelacionados($ProcedimentosRelacionados)
  {
    $this->ProcedimentosRelacionados = $ProcedimentosRelacionados;
  }

  /**
   * @return mixed
   */
  public function getUnidadesEnvio()
  {
    return $this->UnidadesEnvio;
  }

  /**
   * @param mixed $UnidadesEnvio
   */
  public function setUnidadesEnvio($UnidadesEnvio)
  {
    $this->UnidadesEnvio = $UnidadesEnvio;
  }

  /**
   * @return mixed
   */
  public function getSinManterAbertoUnidade()
  {
    return $this->SinManterAbertoUnidade;
  }

  /**
   * @param mixed $SinManterAbertoUnidade
   */
  public function setSinManterAbertoUnidade($SinManterAbertoUnidade)
  {
    $this->SinManterAbertoUnidade = $SinManterAbertoUnidade;
  }

  /**
   * @return mixed
   */
  public function getSinEnviarEmailNotificacao()
  {
    return $this->SinEnviarEmailNotificacao;
  }

  /**
   * @param mixed $SinEnviarEmailNotificacao
   */
  public function setSinEnviarEmailNotificacao($SinEnviarEmailNotificacao)
  {
    $this->SinEnviarEmailNotificacao = $SinEnviarEmailNotificacao;
  }

  /**
   * @return mixed
   */
  public function getDataRetornoProgramado()
  {
    return $this->DataRetornoProgramado;
  }

  /**
   * @param mixed $DataRetornoProgramado
   */
  public function setDataRetornoProgramado($DataRetornoProgramado)
  {
    $this->DataRetornoProgramado = $DataRetornoProgramado;
  }

  /**
   * @return mixed
   */
  public function getDiasRetornoProgramado()
  {
    return $this->DiasRetornoProgramado;
  }

  /**
   * @param mixed $DiasRetornoProgramado
   */
  public function setDiasRetornoProgramado($DiasRetornoProgramado)
  {
    $this->DiasRetornoProgramado = $DiasRetornoProgramado;
  }

  /**
   * @return mixed
   */
  public function getSinDiasUteisRetornoProgramado()
  {
    return $this->SinDiasUteisRetornoProgramado;
  }

  /**
   * @param mixed $SinDiasUteisRetornoProgramado
   */
  public function setSinDiasUteisRetornoProgramado($SinDiasUteisRetornoProgramado)
  {
    $this->SinDiasUteisRetornoProgramado = $SinDiasUteisRetornoProgramado;
  }

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
  public function getTextoMarcador()
  {
    return $this->TextoMarcador;
  }

  /**
   * @param mixed $TextoMarcador
   */
  public function setTextoMarcador($TextoMarcador)
  {
    $this->TextoMarcador = $TextoMarcador;
  }

  /**
   * @return mixed
   */
  public function getDataControlePrazo()
  {
    return $this->DataControlePrazo;
  }

  /**
   * @param mixed $DataControlePrazo
   */
  public function setDataControlePrazo($DataControlePrazo)
  {
    $this->DataControlePrazo = $DataControlePrazo;
  }

  /**
   * @return mixed
   */
  public function getDiasControlePrazo()
  {
    return $this->DiasControlePrazo;
  }

  /**
   * @param mixed $DiasControlePrazo
   */
  public function setDiasControlePrazo($DiasControlePrazo)
  {
    $this->DiasControlePrazo = $DiasControlePrazo;
  }

  /**
   * @return mixed
   */
  public function getSinDiasUteisControlePrazo()
  {
    return $this->SinDiasUteisControlePrazo;
  }

  /**
   * @param mixed $SinDiasUteisControlePrazo
   */
  public function setSinDiasUteisControlePrazo($SinDiasUteisControlePrazo)
  {
    $this->SinDiasUteisControlePrazo = $SinDiasUteisControlePrazo;
  }


}

?>