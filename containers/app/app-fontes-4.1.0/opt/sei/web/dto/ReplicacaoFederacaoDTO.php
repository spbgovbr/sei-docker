<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ReplicacaoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'replicacao_federacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdReplicacaoFederacao', 'id_replicacao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacao', 'id_instalacao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdProtocoloFederacao', 'id_protocolo_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'StaTipo', 'sta_tipo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Cadastro', 'dth_cadastro');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Replicacao', 'dth_replicacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Tentativa', 'tentativa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Erro', 'erro');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaInstalacaoFederacao', 'sigla', 'instalacao_federacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoInstalacaoFederacao', 'descricao', 'instalacao_federacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoFederacao', 'protocolo_formatado', 'protocolo_federacao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProtocolo');

    $this->configurarPK('IdReplicacaoFederacao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdInstalacaoFederacao', 'instalacao_federacao', 'id_instalacao_federacao');

    $this->configurarFK('IdProtocoloFederacao', 'protocolo_federacao', 'id_protocolo_federacao');

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}
