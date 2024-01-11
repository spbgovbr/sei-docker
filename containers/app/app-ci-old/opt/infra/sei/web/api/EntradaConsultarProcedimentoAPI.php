<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/07/2014 - criado por mga
*
*/


class EntradaConsultarProcedimentoAPI {
  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $SinRetornarAssuntos;
  private $SinRetornarInteressados;
  private $SinRetornarObservacoes;
  private $SinRetornarAndamentoGeracao;
  private $SinRetornarAndamentoConclusao;
  private $SinRetornarUltimoAndamento;
  private $SinRetornarUnidadesProcedimentoAberto;
  private $SinRetornarProcedimentosRelacionados;
  private $SinRetornarProcedimentosAnexados;

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
  public function setIdProcedimento($IdProcedimento)
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
  public function setProtocoloProcedimento($ProtocoloProcedimento)
  {
    $this->ProtocoloProcedimento = $ProtocoloProcedimento;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarAssuntos()
  {
    return $this->SinRetornarAssuntos;
  }

  /**
   * @param mixed $SinRetornarAssuntos
   */
  public function setSinRetornarAssuntos($SinRetornarAssuntos)
  {
    $this->SinRetornarAssuntos = $SinRetornarAssuntos;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarInteressados()
  {
    return $this->SinRetornarInteressados;
  }

  /**
   * @param mixed $SinRetornarInteressados
   */
  public function setSinRetornarInteressados($SinRetornarInteressados)
  {
    $this->SinRetornarInteressados = $SinRetornarInteressados;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarObservacoes()
  {
    return $this->SinRetornarObservacoes;
  }

  /**
   * @param mixed $SinRetornarObservacoes
   */
  public function setSinRetornarObservacoes($SinRetornarObservacoes)
  {
    $this->SinRetornarObservacoes = $SinRetornarObservacoes;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarAndamentoGeracao()
  {
    return $this->SinRetornarAndamentoGeracao;
  }

  /**
   * @param mixed $SinRetornarAndamentoGeracao
   */
  public function setSinRetornarAndamentoGeracao($SinRetornarAndamentoGeracao)
  {
    $this->SinRetornarAndamentoGeracao = $SinRetornarAndamentoGeracao;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarAndamentoConclusao()
  {
    return $this->SinRetornarAndamentoConclusao;
  }

  /**
   * @param mixed $SinRetornarAndamentoConclusao
   */
  public function setSinRetornarAndamentoConclusao($SinRetornarAndamentoConclusao)
  {
    $this->SinRetornarAndamentoConclusao = $SinRetornarAndamentoConclusao;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarUltimoAndamento()
  {
    return $this->SinRetornarUltimoAndamento;
  }

  /**
   * @param mixed $SinRetornarUltimoAndamento
   */
  public function setSinRetornarUltimoAndamento($SinRetornarUltimoAndamento)
  {
    $this->SinRetornarUltimoAndamento = $SinRetornarUltimoAndamento;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarUnidadesProcedimentoAberto()
  {
    return $this->SinRetornarUnidadesProcedimentoAberto;
  }

  /**
   * @param mixed $SinRetornarUnidadesProcedimentoAberto
   */
  public function setSinRetornarUnidadesProcedimentoAberto($SinRetornarUnidadesProcedimentoAberto)
  {
    $this->SinRetornarUnidadesProcedimentoAberto = $SinRetornarUnidadesProcedimentoAberto;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarProcedimentosRelacionados()
  {
    return $this->SinRetornarProcedimentosRelacionados;
  }

  /**
   * @param mixed $SinRetornarProcedimentosRelacionados
   */
  public function setSinRetornarProcedimentosRelacionados($SinRetornarProcedimentosRelacionados)
  {
    $this->SinRetornarProcedimentosRelacionados = $SinRetornarProcedimentosRelacionados;
  }

  /**
   * @return mixed
   */
  public function getSinRetornarProcedimentosAnexados()
  {
    return $this->SinRetornarProcedimentosAnexados;
  }

  /**
   * @param mixed $SinRetornarProcedimentosAnexados
   */
  public function setSinRetornarProcedimentosAnexados($SinRetornarProcedimentosAnexados)
  {
    $this->SinRetornarProcedimentosAnexados = $SinRetornarProcedimentosAnexados;
  }

}
?>