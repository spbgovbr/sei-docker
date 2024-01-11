<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/09/2022 - criado por mgb29
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtividadeUnidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdAtividade');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloFormatadoProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeTipoProcedimento');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SiglaUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'DescricaoUnidade');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SiglaUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeUsuario');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH,'Abertura');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Inicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Fim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaTipo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdTarefa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeTarefa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'TotalTarefas');
  }
}
