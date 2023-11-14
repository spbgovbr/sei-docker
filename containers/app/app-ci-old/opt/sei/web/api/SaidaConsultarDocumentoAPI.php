<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2011 - criado por mga
*
*/

class SaidaConsultarDocumentoAPI {
  private $IdProcedimento;
  private $ProcedimentoFormatado;
  private $IdDocumento;
  private $DocumentoFormatado;
  private $LinkAcesso;
  private $NivelAcessoLocal;
  private $NivelAcessoGlobal;
  private $Serie;
  private $Numero;
  private $NomeArvore;
  private $Descricao;
  private $Data;
  private $UnidadeElaboradora;
  private $AndamentoGeracao;
  private $Assinaturas;
  private $Publicacao;
  private $Campos;

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
  public function getIdDocumento()
  {
    return $this->IdDocumento;
  }

  /**
   * @param mixed $IdDocumento
   */
  public function setIdDocumento($IdDocumento)
  {
    $this->IdDocumento = $IdDocumento;
  }

  /**
   * @return mixed
   */
  public function getDocumentoFormatado()
  {
    return $this->DocumentoFormatado;
  }

  /**
   * @param mixed $DocumentoFormatado
   */
  public function setDocumentoFormatado($DocumentoFormatado)
  {
    $this->DocumentoFormatado = $DocumentoFormatado;
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
  public function getSerie()
  {
    return $this->Serie;
  }

  /**
   * @param mixed $Serie
   */
  public function setSerie($Serie)
  {
    $this->Serie = $Serie;
  }

  /**
   * @return mixed
   */
  public function getNumero()
  {
    return $this->Numero;
  }

  /**
   * @param mixed $Numero
   */
  public function setNumero($Numero)
  {
    $this->Numero = $Numero;
  }

  /**
   * @return mixed
   */
  public function getDescricao()
  {
    return $this->Descricao;
  }

  /**
   * @param mixed $Descricao
   */
  public function setDescricao($Descricao)
  {
    $this->Descricao = $Descricao;
  }

  /**
   * @return mixed
   */
  public function getData()
  {
    return $this->Data;
  }

  /**
   * @param mixed $Data
   */
  public function setData($Data)
  {
    $this->Data = $Data;
  }

  /**
   * @return mixed
   */
  public function getUnidadeElaboradora()
  {
    return $this->UnidadeElaboradora;
  }

  /**
   * @param mixed $UnidadeElaboradora
   */
  public function setUnidadeElaboradora($UnidadeElaboradora)
  {
    $this->UnidadeElaboradora = $UnidadeElaboradora;
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
  public function getAssinaturas()
  {
    return $this->Assinaturas;
  }

  /**
   * @param mixed $Assinaturas
   */
  public function setAssinaturas($Assinaturas)
  {
    $this->Assinaturas = $Assinaturas;
  }

  /**
   * @return mixed
   */
  public function getPublicacao()
  {
    return $this->Publicacao;
  }

  /**
   * @param mixed $Publicacao
   */
  public function setPublicacao($Publicacao)
  {
    $this->Publicacao = $Publicacao;
  }

  /**
   * @return mixed
   */
  public function getCampos()
  {
    return $this->Campos;
  }

  /**
   * @param mixed $Campos
   */
  public function setCampos($Campos)
  {
    $this->Campos = $Campos;
  }

  /**
   * @return mixed
   */
  public function getNomeArvore()
  {
    return $this->NomeArvore;
  }

  /**
   * @param mixed $NomeArvore
   */
  public function setNomeArvore($NomeArvore)
  {
    $this->NomeArvore = $NomeArvore;
  }

}
?>