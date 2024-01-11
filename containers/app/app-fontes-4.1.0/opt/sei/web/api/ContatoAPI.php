<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 * 04/06/2018 - cjy - adicao dos campos numero_passaporte e id_pais_passaporte
 * 12/06/2018 - cjy - insercao de estado e cidade textualmente, para paises estrangeiros
 *
 */

class ContatoAPI {
  private $StaOperacao;
  private $IdContato;
  private $IdTipoContato;
  private $NomeTipoContato;
  private $Sigla;
  private $Nome;
  private $NomeSocial;
  private $StaNatureza;
  private $IdContatoAssociado;
  private $NomeContatoAssociado;
  private $SinEnderecoAssociado;
  private $CnpjAssociado;
  private $Endereco;
  private $Complemento;
  private $Bairro;
  private $IdCidade;
  private $NomeCidade;
  private $IdEstado;
  private $SiglaEstado;
  private $IdPais;
  private $NomePais;
  private $Cep;
  private $StaGenero;
  private $IdCargo;
  private $IdTitulo;
  private $ExpressaoCargo;
  private $ExpressaoTratamento;
  private $ExpressaoVocativo;
  private $ExpressaoTitulo;
  private $AbreviaturaTitulo;
  private $Cpf;
  private $Cnpj;
  private $Rg;
  private $OrgaoExpedidor;
  private $Matricula;
  private $MatriculaOab;
  private $TelefoneComercial;
  private $TelefoneResidencial;
  private $TelefoneCelular;
  private $DataNascimento;
  private $Email;
  private $SitioInternet;
  private $Observacao;
  private $SinAtivo;
  private $IdPaisPassaporte;
  private $NomePaisPassaporte;
  private $NumeroPassaporte;
  private $NomeEstado;
  private $Conjuge;
  private $Funcao;
  private $IdCategoria;
  private $NomeCategoria;


  /**
   * @return mixed
   */
  public function getStaOperacao()
  {
    return $this->StaOperacao;
  }

  /**
   * @param mixed $StaOperacao
   */
  public function setStaOperacao($StaOperacao)
  {
    $this->StaOperacao = $StaOperacao;
  }

  /**
   * @return mixed
   */
  public function getIdContato()
  {
    return $this->IdContato;
  }

  /**
   * @param mixed $IdContato
   */
  public function setIdContato($IdContato)
  {
    $this->IdContato = $IdContato;
  }

  /**
   * @return mixed
   */
  public function getIdTipoContato()
  {
    return $this->IdTipoContato;
  }

  /**
   * @param mixed $IdTipoContato
   */
  public function setIdTipoContato($IdTipoContato)
  {
    $this->IdTipoContato = $IdTipoContato;
  }

  /**
   * @return mixed
   */
  public function getNomeTipoContato()
  {
    return $this->NomeTipoContato;
  }

  /**
   * @param mixed $NomeTipoContato
   */
  public function setNomeTipoContato($NomeTipoContato)
  {
    $this->NomeTipoContato = $NomeTipoContato;
  }

  /**
   * @return mixed
   */
  public function getSigla()
  {
    return $this->Sigla;
  }

  /**
   * @param mixed $Sigla
   */
  public function setSigla($Sigla)
  {
    $this->Sigla = $Sigla;
  }

  /**
   * @return mixed
   */
  public function getNome()
  {
    return $this->Nome;
  }

  /**
   * @param mixed $Nome
   */
  public function setNome($Nome)
  {
    $this->Nome = $Nome;
  }

  /**
   * @return mixed
   */
  public function getNomeSocial()
  {
    return $this->NomeSocial;
  }

  /**
   * @param mixed $NomeSocial
   */
  public function setNomeSocial($NomeSocial)
  {
    $this->NomeSocial = $NomeSocial;
  }

  /**
   * @return mixed
   */
  public function getStaNatureza()
  {
    return $this->StaNatureza;
  }

  /**
   * @param mixed $StaNatureza
   */
  public function setStaNatureza($StaNatureza)
  {
    $this->StaNatureza = $StaNatureza;
  }

  /**
   * @return mixed
   */
  public function getIdContatoAssociado()
  {
    return $this->IdContatoAssociado;
  }

  /**
   * @param mixed $IdContatoAssociado
   */
  public function setIdContatoAssociado($IdContatoAssociado)
  {
    $this->IdContatoAssociado = $IdContatoAssociado;
  }

  /**
   * @return mixed
   */
  public function getNomeContatoAssociado()
  {
    return $this->NomeContatoAssociado;
  }

  /**
   * @param mixed $NomeContatoAssociado
   */
  public function setNomeContatoAssociado($NomeContatoAssociado)
  {
    $this->NomeContatoAssociado = $NomeContatoAssociado;
  }

  /**
   * @return mixed
   */
  public function getSinEnderecoAssociado()
  {
    return $this->SinEnderecoAssociado;
  }

  /**
   * @param mixed $SinEnderecoAssociado
   */
  public function setSinEnderecoAssociado($SinEnderecoAssociado)
  {
    $this->SinEnderecoAssociado = $SinEnderecoAssociado;
  }

  /**
   * @return mixed
   */
  public function getCnpjAssociado()
  {
    return $this->CnpjAssociado;
  }

  /**
   * @param mixed $CnpjAssociado
   */
  public function setCnpjAssociado($CnpjAssociado)
  {
    $this->CnpjAssociado = $CnpjAssociado;
  }

  /**
   * @return mixed
   */
  public function getEndereco()
  {
    return $this->Endereco;
  }

  /**
   * @param mixed $Endereco
   */
  public function setEndereco($Endereco)
  {
    $this->Endereco = $Endereco;
  }

  /**
   * @return mixed
   */
  public function getComplemento()
  {
    return $this->Complemento;
  }

  /**
   * @param mixed $Complemento
   */
  public function setComplemento($Complemento)
  {
    $this->Complemento = $Complemento;
  }

  /**
   * @return mixed
   */
  public function getBairro()
  {
    return $this->Bairro;
  }

  /**
   * @param mixed $Bairro
   */
  public function setBairro($Bairro)
  {
    $this->Bairro = $Bairro;
  }

  /**
   * @return mixed
   */
  public function getIdCidade()
  {
    return $this->IdCidade;
  }

  /**
   * @param mixed $IdCidade
   */
  public function setIdCidade($IdCidade)
  {
    $this->IdCidade = $IdCidade;
  }


  /**
   * @return mixed
   */
  public function getNomeCidade()
  {
    return $this->NomeCidade;
  }

  /**
   * @param mixed $NomeCidade
   */
  public function setNomeCidade($NomeCidade)
  {
    $this->NomeCidade = $NomeCidade;
  }

  /**
   * @return mixed
   */
  public function getIdEstado()
  {
    return $this->IdEstado;
  }

  /**
   * @param mixed $IdEstado
   */
  public function setIdEstado($IdEstado)
  {
    $this->IdEstado = $IdEstado;
  }

    /**
   * @return mixed
   */
  public function getSiglaEstado()
  {
    return $this->SiglaEstado;
  }

  /**
   * @param mixed $SiglaEstado
   */
  public function setSiglaEstado($SiglaEstado)
  {
    $this->SiglaEstado = $SiglaEstado;
  }

  /**
   * @return mixed
   */
  public function getIdPais()
  {
    return $this->IdPais;
  }

  /**
   * @param mixed $IdPais
   */
  public function setIdPais($IdPais)
  {
    $this->IdPais = $IdPais;
  }

  /**
   * @return mixed
   */
  public function getNomePais()
  {
    return $this->NomePais;
  }

  /**
   * @param mixed $NomePais
   */
  public function setNomePais($NomePais)
  {
    $this->NomePais = $NomePais;
  }

  /**
   * @return mixed
   */
  public function getCep()
  {
    return $this->Cep;
  }

  /**
   * @param mixed $Cep
   */
  public function setCep($Cep)
  {
    $this->Cep = $Cep;
  }

  /**
   * @return mixed
   */
  public function getStaGenero()
  {
    return $this->StaGenero;
  }

  /**
   * @param mixed $StaGenero
   */
  public function setStaGenero($StaGenero)
  {
    $this->StaGenero = $StaGenero;
  }

  /**
   * @return mixed
   */
  public function getIdCargo()
  {
    return $this->IdCargo;
  }

  /**
   * @param mixed $IdCargo
   */
  public function setIdCargo($IdCargo)
  {
    $this->IdCargo = $IdCargo;
  }

  /**
   * @return mixed
   */
  public function getExpressaoCargo()
  {
    return $this->ExpressaoCargo;
  }

  /**
   * @param mixed $ExpressaoCargo
   */
  public function setExpressaoCargo($ExpressaoCargo)
  {
    $this->ExpressaoCargo = $ExpressaoCargo;
  }

  /**
   * @return mixed
   */
  public function getExpressaoTratamento()
  {
    return $this->ExpressaoTratamento;
  }

  /**
   * @param mixed $ExpressaoTratamento
   */
  public function setExpressaoTratamento($ExpressaoTratamento)
  {
    $this->ExpressaoTratamento = $ExpressaoTratamento;
  }

  /**
   * @return mixed
   */
  public function getExpressaoVocativo()
  {
    return $this->ExpressaoVocativo;
  }

  /**
   * @param mixed $ExpressaoVocativo
   */
  public function setExpressaoVocativo($ExpressaoVocativo)
  {
    $this->ExpressaoVocativo = $ExpressaoVocativo;
  }

  /**
   * @return mixed
   */
  public function getCpf()
  {
    return $this->Cpf;
  }

  /**
   * @param mixed $Cpf
   */
  public function setCpf($Cpf)
  {
    $this->Cpf = $Cpf;
  }

  /**
   * @return mixed
   */
  public function getCnpj()
  {
    return $this->Cnpj;
  }

  /**
   * @param mixed $Cnpj
   */
  public function setCnpj($Cnpj)
  {
    $this->Cnpj = $Cnpj;
  }

  /**
   * @return mixed
   */
  public function getRg()
  {
    return $this->Rg;
  }

  /**
   * @param mixed $Rg
   */
  public function setRg($Rg)
  {
    $this->Rg = $Rg;
  }

  /**
   * @return mixed
   */
  public function getOrgaoExpedidor()
  {
    return $this->OrgaoExpedidor;
  }

  /**
   * @param mixed $OrgaoExpedidor
   */
  public function setOrgaoExpedidor($OrgaoExpedidor)
  {
    $this->OrgaoExpedidor = $OrgaoExpedidor;
  }

  /**
   * @return mixed
   */
  public function getMatricula()
  {
    return $this->Matricula;
  }

  /**
   * @param mixed $Matricula
   */
  public function setMatricula($Matricula)
  {
    $this->Matricula = $Matricula;
  }

  /**
   * @return mixed
   */
  public function getMatriculaOab()
  {
    return $this->MatriculaOab;
  }

  /**
   * @param mixed $MatriculaOab
   */
  public function setMatriculaOab($MatriculaOab)
  {
    $this->MatriculaOab = $MatriculaOab;
  }

  /**
   * @return mixed
   */
  public function getTelefoneComercial()
  {
    return $this->TelefoneComercial;
  }

  /**
   * @param mixed $TelefoneFixo
   */
  public function setTelefoneComercial($TelefoneComercial)
  {
    $this->TelefoneComercial = $TelefoneComercial;
  }

  /**
   * @return mixed
   */
  public function getTelefoneCelular()
  {
    return $this->TelefoneCelular;
  }

  /**
   * @param mixed $TelefoneCelular
   */
  public function setTelefoneCelular($TelefoneCelular)
  {
    $this->TelefoneCelular = $TelefoneCelular;
  }

  /**
   * @return mixed
   */
  public function getDataNascimento()
  {
    return $this->DataNascimento;
  }

  /**
   * @param mixed $DataNascimento
   */
  public function setDataNascimento($DataNascimento)
  {
    $this->DataNascimento = $DataNascimento;
  }

  /**
   * @return mixed
   */
  public function getEmail()
  {
    return $this->Email;
  }

  /**
   * @param mixed $Email
   */
  public function setEmail($Email)
  {
    $this->Email = $Email;
  }

  /**
   * @return mixed
   */
  public function getSitioInternet()
  {
    return $this->SitioInternet;
  }

  /**
   * @param mixed $SitioInternet
   */
  public function setSitioInternet($SitioInternet)
  {
    $this->SitioInternet = $SitioInternet;
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
  public function getSinAtivo()
  {
    return $this->SinAtivo;
  }

  /**
   * @param mixed $SinAtivo
   */
  public function setSinAtivo($SinAtivo)
  {
    $this->SinAtivo = $SinAtivo;
  }

  /**
   * @return mixed
   */
  public function getIdPaisPassaporte()
  {
    return $this->IdPaisPassaporte;
  }

  /**
   * @param mixed $IdPaisPassaporte
   */
  public function setIdPaisPassaporte($IdPaisPassaporte)
  {
    $this->IdPaisPassaporte = $IdPaisPassaporte;
  }

  /**
   * @return mixed
   */
  public function getNumeroPassaporte()
  {
    return $this->NumeroPassaporte;
  }

  /**
   * @param mixed $NumeroPassaporte
   */
  public function setNumeroPassaporte($NumeroPassaporte)
  {
    $this->NumeroPassaporte = $NumeroPassaporte;
  }

  /**
   * @return mixed
   */
  public function getNomePaisPassaporte()
  {
    return $this->NomePaisPassaporte;
  }

  /**
   * @param mixed $NomePaisPassaporte
   */
  public function setNomePaisPassaporte($NomePaisPassaporte)
  {
    $this->NomePaisPassaporte = $NomePaisPassaporte;
  }

  /**
   * @return mixed
   */
  public function getNomeEstado()
  {
    return $this->NomeEstado;
  }

  /**
   * @param mixed $NomeEstado
   */
  public function setNomeEstado($NomeEstado)
  {
    $this->NomeEstado = $NomeEstado;
  }

  /**
   * @return mixed
   */
  public function getTelefoneResidencial()
  {
    return $this->TelefoneResidencial;
  }

  /**
   * @param mixed $TelefoneResidencial
   */
  public function setTelefoneResidencial($TelefoneResidencial)
  {
    $this->TelefoneResidencial = $TelefoneResidencial;
  }

  /**
   * @return mixed
   */
  public function getConjuge()
  {
    return $this->Conjuge;
  }

  /**
   * @param mixed $Conjuge
   */
  public function setConjuge($Conjuge)
  {
    $this->Conjuge = $Conjuge;
  }

  /**
   * @return mixed
   */
  public function getFuncao()
  {
    return $this->Funcao;
  }

  /**
   * @param mixed $Funcao
   */
  public function setFuncao($Funcao)
  {
    $this->Funcao = $Funcao;
  }

  /**
   * @return mixed
   */
  public function getExpressaoTitulo()
  {
    return $this->ExpressaoTitulo;
  }

  /**
   * @param mixed $ExpressaoTitulo
   */
  public function setExpressaoTitulo($ExpressaoTitulo)
  {
    $this->ExpressaoTitulo = $ExpressaoTitulo;
  }

  /**
   * @return mixed
   */
  public function getAbreviaturaTitulo()
  {
    return $this->AbreviaturaTitulo;
  }

  /**
   * @param mixed $AbreviaturaTitulo
   */
  public function setAbreviaturaTitulo($AbreviaturaTitulo)
  {
    $this->AbreviaturaTitulo = $AbreviaturaTitulo;
  }

  /**
   * @return mixed
   */
  public function getIdTitulo()
  {
    return $this->IdTitulo;
  }

  /**
   * @param mixed $IdTitulo
   */
  public function setIdTitulo($IdTitulo)
  {
    $this->IdTitulo = $IdTitulo;
  }

  /**
   * @return mixed
   */
  public function getIdCategoria()
  {
    return $this->IdCategoria;
  }

  /**
   * @param mixed $IdCategoria
   */
  public function setIdCategoria($IdCategoria)
  {
    $this->IdCategoria = $IdCategoria;
  }

  /**
   * @return mixed
   */
  public function getNomeCategoria()
  {
    return $this->NomeCategoria;
  }

  /**
   * @param mixed $NomeCategoria
   */
  public function setNomeCategoria($NomeCategoria)
  {
    $this->NomeCategoria = $NomeCategoria;
  }



}
?>