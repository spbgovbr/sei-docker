<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class DocumentoAPI {
  private $IdDocumento;
  private $Tipo;
  private $IdProcedimento;
  private $ProtocoloProcedimento;
  private $IdSerie;
  private $NomeSerie;
  private $Numero;
  private $NomeArvore;
  private $Data;
  private $Descricao;
  private $IdTipoConferencia;
  private $SinArquivamento;
  private $Remetente;
  private $Interessados;
  private $Destinatarios;
  private $Observacao;
  private $NomeArquivo;
  private $NivelAcesso;
  private $IdHipoteseLegal;
  private $Conteudo;
  private $ConteudoMTOM;
  private $SinBloqueado;
  private $IdArquivo;
  private $Campos;

  private $IdUnidadeGeradora;
  private $NumeroProtocolo;
  private $SinAssinado;
  private $SinPublicado;
  private $CodigoAcesso;
  private $SubTipo;
  private $IdOrgaoUnidadeGeradora;
  private $IdUsuarioGerador;


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
  public function getIdSerie()
  {
    return $this->IdSerie;
  }

  /**
   * @param mixed $IdSerie
   */
  public function setIdSerie($IdSerie)
  {
    $this->IdSerie = $IdSerie;
  }

  /**
   * @return mixed
   */
  public function getNomeSerie()
  {
    return $this->NomeSerie;
  }

  /**
   * @param mixed $NomeSerie
   */
  public function setNomeSerie($NomeSerie)
  {
    $this->NomeSerie = $NomeSerie;
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
  public function getIdTipoConferencia()
  {
    return $this->IdTipoConferencia;
  }

  /**
   * @param mixed $IdTipoConferencia
   */
  public function setIdTipoConferencia($IdTipoConferencia)
  {
    $this->IdTipoConferencia = $IdTipoConferencia;
  }

  /**
   * @return mixed
   */
  public function getSinArquivamento()
  {
    return $this->SinArquivamento;
  }

  /**
   * @param mixed $SinArquivamento
   */
  public function setSinArquivamento($SinArquivamento)
  {
    $this->SinArquivamento = $SinArquivamento;
  }

  /**
   * @return mixed
   */
  public function getRemetente()
  {
    return $this->Remetente;
  }

  /**
   * @param mixed $Remetente
   */
  public function setRemetente($Remetente)
  {
    $this->Remetente = $Remetente;
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
  public function getDestinatarios()
  {
    return $this->Destinatarios;
  }

  /**
   * @param mixed $Destinatarios
   */
  public function setDestinatarios($Destinatarios)
  {
    $this->Destinatarios = $Destinatarios;
  }

  /**
   * @return mixed
   */
  public function getObservacao()
  {
    return $this->Observacao;
  }

  /**
   * @param mixed $Observacao
   */
  public function setObservacao($Observacao)
  {
    $this->Observacao = $Observacao;
  }

  /**
   * @return mixed
   */
  public function getNomeArquivo()
  {
    return $this->NomeArquivo;
  }

  /**
   * @param mixed $NomeArquivo
   */
  public function setNomeArquivo($NomeArquivo)
  {
    $this->NomeArquivo = $NomeArquivo;
  }

  /**
   * @return mixed
   */
  public function getNivelAcesso()
  {
    return $this->NivelAcesso;
  }

  /**
   * @param mixed $NivelAcesso
   */
  public function setNivelAcesso($NivelAcesso)
  {
    $this->NivelAcesso = $NivelAcesso;
  }

  /**
   * @return mixed
   */
  public function getIdHipoteseLegal()
  {
    return $this->IdHipoteseLegal;
  }

  /**
   * @param mixed $IdHipoteseLegal
   */
  public function setIdHipoteseLegal($IdHipoteseLegal)
  {
    $this->IdHipoteseLegal = $IdHipoteseLegal;
  }

  /**
   * @return mixed
   */
  public function getConteudo()
  {
    return $this->Conteudo;
  }

  /**
   * @param mixed $Conteudo
   */
  public function setConteudo($Conteudo)
  {
    $this->Conteudo = $Conteudo;
  }

  /**
   * @return mixed
   */
  public function getConteudoMTOM()
  {
    return $this->ConteudoMTOM;
  }

  /**
   * @param mixed $ConteudoMTOM
   */
  public function setConteudoMTOM($ConteudoMTOM)
  {
    $this->ConteudoMTOM = $ConteudoMTOM;
  }

  /**
   * @return mixed
   */
  public function getSinBloqueado()
  {
    return $this->SinBloqueado;
  }

  /**
   * @param mixed $SinBloqueado
   */
  public function setSinBloqueado($SinBloqueado)
  {
    $this->SinBloqueado = $SinBloqueado;
  }

  /**
   * @return mixed
   */
  public function getIdArquivo()
  {
    return $this->IdArquivo;
  }

  /**
   * @param mixed $IdArquivo
   */
  public function setIdArquivo($IdArquivo)
  {
    $this->IdArquivo = $IdArquivo;
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
  public function getIdUnidadeGeradora()
  {
    return $this->IdUnidadeGeradora;
  }

  /**
   * @param mixed $IdUnidadeGeradora
   */
  public function setIdUnidadeGeradora($IdUnidadeGeradora)
  {
    $this->IdUnidadeGeradora = $IdUnidadeGeradora;
  }

  /**
   * @return mixed
   */
  public function getNumeroProtocolo()
  {
    return $this->NumeroProtocolo;
  }

  /**
   * @param mixed $NumeroProtocolo
   */
  public function setNumeroProtocolo($NumeroProtocolo)
  {
    $this->NumeroProtocolo = $NumeroProtocolo;
  }

  /**
   * @return mixed
   */
  public function getSinAssinado()
  {
    return $this->SinAssinado;
  }

  /**
   * @param mixed $SinAssinado
   */
  public function setSinAssinado($SinAssinado)
  {
    $this->SinAssinado = $SinAssinado;
  }

  /**
   * @return mixed
   */
  public function getSinPublicado()
  {
    return $this->SinPublicado;
  }

  /**
   * @param mixed $SinPublicado
   */
  public function setSinPublicado($SinPublicado)
  {
    $this->SinPublicado = $SinPublicado;
  }

  /**
   * @return mixed
   */
  public function getCodigoAcesso()
  {
    return $this->CodigoAcesso;
  }

  /**
   * @param mixed $CodigoAcesso
   */
  public function setCodigoAcesso($CodigoAcesso)
  {
    $this->CodigoAcesso = $CodigoAcesso;
  }

  /**
   * @return mixed
   */
  public function getSubTipo()
  {
    return $this->SubTipo;
  }

  /**
   * @param mixed $SubTipo
   */
  public function setSubTipo($SubTipo)
  {
    $this->SubTipo = $SubTipo;
  }

  /**
   * @return mixed
   */
  public function getIdOrgaoUnidadeGeradora()
  {
    return $this->IdOrgaoUnidadeGeradora;
  }

  /**
   * @param mixed $IdOrgaoUnidadeGeradora
   */
  public function setIdOrgaoUnidadeGeradora($IdOrgaoUnidadeGeradora)
  {
    $this->IdOrgaoUnidadeGeradora = $IdOrgaoUnidadeGeradora;
  }

  /**
   * @return mixed
   */
  public function getIdUsuarioGerador()
  {
    return $this->IdUsuarioGerador;
  }

  /**
   * @param mixed $IdUsuarioGerador
   */
  public function setIdUsuarioGerador($IdUsuarioGerador)
  {
    $this->IdUsuarioGerador = $IdUsuarioGerador;
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