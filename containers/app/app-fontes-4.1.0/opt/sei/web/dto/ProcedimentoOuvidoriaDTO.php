<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/09/2008 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class ProcedimentoOuvidoriaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH,'Envio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Nome');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeSocial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Email');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'Cpf');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'Rg');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'OrgaoExpedidor');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Telefone');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Estado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Cidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdTipoProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Processos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinRetorno');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Mensagem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjAtributoOuvidoriaDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimentoOrigem');
  }
}
?>