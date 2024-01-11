<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 25/10/2019 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class EntradaRegistrarOuvidoriaAPI {
  private $IdOrgao;
  private $Nome;
  private $NomeSocial;
  private $Email;
  private $Cpf;
  private $Rg;
  private $OrgaoExpedidor;
  private $Telefone;
  private $Estado;
  private $Cidade;
  private $IdTipoProcedimento;
  private $Processos;
  private $SinRetorno;
  private $Mensagem;
  private $AtributosAdicionais;

  /**
   * @return mixed
   */
  public function getIdOrgao()
  {
    return $this->IdOrgao;
  }

  /**
   * @param mixed $IdOrgao
   */
  public function setIdOrgao($IdOrgao): void
  {
    $this->IdOrgao = $IdOrgao;
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
  public function setNome($Nome): void
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
  public function setNomeSocial($NomeSocial): void
  {
    $this->NomeSocial = $NomeSocial;
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
  public function setEmail($Email): void
  {
    $this->Email = $Email;
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
  public function setCpf($Cpf): void
  {
    $this->Cpf = $Cpf;
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
  public function setRg($Rg): void
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
  public function setOrgaoExpedidor($OrgaoExpedidor): void
  {
    $this->OrgaoExpedidor = $OrgaoExpedidor;
  }

  /**
   * @return mixed
   */
  public function getTelefone()
  {
    return $this->Telefone;
  }

  /**
   * @param mixed $Telefone
   */
  public function setTelefone($Telefone): void
  {
    $this->Telefone = $Telefone;
  }

  /**
   * @return mixed
   */
  public function getEstado()
  {
    return $this->Estado;
  }

  /**
   * @param mixed $Estado
   */
  public function setEstado($Estado): void
  {
    $this->Estado = $Estado;
  }

  /**
   * @return mixed
   */
  public function getCidade()
  {
    return $this->Cidade;
  }

  /**
   * @param mixed $Cidade
   */
  public function setCidade($Cidade): void
  {
    $this->Cidade = $Cidade;
  }

  /**
   * @return mixed
   */
  public function getIdTipoProcedimento()
  {
    return $this->IdTipoProcedimento;
  }

  /**
   * @param mixed $IdTipoProcedimento
   */
  public function setIdTipoProcedimento($IdTipoProcedimento): void
  {
    $this->IdTipoProcedimento = $IdTipoProcedimento;
  }

  /**
   * @return mixed
   */
  public function getProcessos()
  {
    return $this->Processos;
  }

  /**
   * @param mixed $Processos
   */
  public function setProcessos($Processos): void
  {
    $this->Processos = $Processos;
  }

  /**
   * @return mixed
   */
  public function getSinRetorno()
  {
    return $this->SinRetorno;
  }

  /**
   * @param mixed $SinRetorno
   */
  public function setSinRetorno($SinRetorno): void
  {
    $this->SinRetorno = $SinRetorno;
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
  public function getAtributosAdicionais()
  {
    return $this->AtributosAdicionais;
  }

  /**
   * @param mixed $AtributosAdicionais
   */
  public function setAtributosAdicionais($AtributosAdicionais): void
  {
    $this->AtributosAdicionais = $AtributosAdicionais;
  }
}
?>