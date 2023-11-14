<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2011 - criado por mga
*
*/

class SaidaConsultarProcedimentoAPI {
  private $IdProcedimento;
  private $ProcedimentoFormatado;
  private $Especificacao;
  private $DataAutuacao;
  private $LinkAcesso;
  private $NivelAcessoLocal;
  private $NivelAcessoGlobal;
  private $TipoProcedimento;
  private $AndamentoGeracao;
  private $AndamentoConclusao;
  private $UltimoAndamento;
  private $UnidadesProcedimentoAberto;
  private $Assuntos;
  private $Interessados;
  private $Observacoes;
  private $ProcedimentosRelacionados;
  private $ProcedimentosAnexados;

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
  public function getProcedimentoFormatado()
  {
    return $this->ProcedimentoFormatado;
  }

  /**
   * @param mixed $ProcedimentoFormatado
   */
  public function setProcedimentoFormatado($ProcedimentoFormatado)
  {
    $this->ProcedimentoFormatado = $ProcedimentoFormatado;
  }

  /**
   * @return mixed
   */
  public function getEspecificacao()
  {
    return $this->Especificacao;
  }

  /**
   * @param mixed $Especificacao
   */
  public function setEspecificacao($Especificacao)
  {
    $this->Especificacao = $Especificacao;
  }

  /**
   * @return mixed
   */
  public function getDataAutuacao()
  {
    return $this->DataAutuacao;
  }

  /**
   * @param mixed $DataAutuacao
   */
  public function setDataAutuacao($DataAutuacao)
  {
    $this->DataAutuacao = $DataAutuacao;
  }

  /**
   * @return mixed
   */
  public function getLinkAcesso()
  {
    return $this->LinkAcesso;
  }

  /**
   * @param mixed $LinkAcesso
   */
  public function setLinkAcesso($LinkAcesso)
  {
    $this->LinkAcesso = $LinkAcesso;
  }

  /**
   * @return mixed
   */
  public function getNivelAcessoLocal()
  {
    return $this->NivelAcessoLocal;
  }

  /**
   * @param mixed $NivelAcessoLocal
   */
  public function setNivelAcessoLocal($NivelAcessoLocal)
  {
    $this->NivelAcessoLocal = $NivelAcessoLocal;
  }

  /**
   * @return mixed
   */
  public function getNivelAcessoGlobal()
  {
    return $this->NivelAcessoGlobal;
  }

  /**
   * @param mixed $NivelAcessoGlobal
   */
  public function setNivelAcessoGlobal($NivelAcessoGlobal)
  {
    $this->NivelAcessoGlobal = $NivelAcessoGlobal;
  }

  /**
   * @return mixed
   */
  public function getTipoProcedimento()
  {
    return $this->TipoProcedimento;
  }

  /**
   * @param mixed $TipoProcedimento
   */
  public function setTipoProcedimento($TipoProcedimento)
  {
    $this->TipoProcedimento = $TipoProcedimento;
  }

  /**
   * @return mixed
   */
  public function getAndamentoGeracao()
  {
    return $this->AndamentoGeracao;
  }

  /**
   * @param mixed $AndamentoGeracao
   */
  public function setAndamentoGeracao($AndamentoGeracao)
  {
    $this->AndamentoGeracao = $AndamentoGeracao;
  }

  /**
   * @return mixed
   */
  public function getAndamentoConclusao()
  {
    return $this->AndamentoConclusao;
  }

  /**
   * @param mixed $AndamentoConclusao
   */
  public function setAndamentoConclusao($AndamentoConclusao)
  {
    $this->AndamentoConclusao = $AndamentoConclusao;
  }

  /**
   * @return mixed
   */
  public function getUltimoAndamento()
  {
    return $this->UltimoAndamento;
  }

  /**
   * @param mixed $UltimoAndamento
   */
  public function setUltimoAndamento($UltimoAndamento)
  {
    $this->UltimoAndamento = $UltimoAndamento;
  }

  /**
   * @return mixed
   */
  public function getUnidadesProcedimentoAberto()
  {
    return $this->UnidadesProcedimentoAberto;
  }

  /**
   * @param mixed $UnidadesProcedimentoAberto
   */
  public function setUnidadesProcedimentoAberto($UnidadesProcedimentoAberto)
  {
    $this->UnidadesProcedimentoAberto = $UnidadesProcedimentoAberto;
  }

  /**
   * @return mixed
   */
  public function getAssuntos()
  {
    return $this->Assuntos;
  }

  /**
   * @param mixed $Assuntos
   */
  public function setAssuntos($Assuntos)
  {
    $this->Assuntos = $Assuntos;
  }

  /**
   * @return mixed
   */
  public function getInteressados()
  {
    return $this->Interessados;
  }

  /**
   * @param mixed $Interessados
   */
  public function setInteressados($Interessados)
  {
    $this->Interessados = $Interessados;
  }

  /**
   * @return mixed
   */
  public function getObservacoes()
  {
    return $this->Observacoes;
  }

  /**
   * @param mixed $Observacoes
   */
  public function setObservacoes($Observacoes)
  {
    $this->Observacoes = $Observacoes;
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
  public function getProcedimentosAnexados()
  {
    return $this->ProcedimentosAnexados;
  }

  /**
   * @param mixed $ProcedimentosAnexados
   */
  public function setProcedimentosAnexados($ProcedimentosAnexados)
  {
    $this->ProcedimentosAnexados = $ProcedimentosAnexados;
  }

}
?>