<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/04/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AndamentoInstalacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'andamento_instalacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAndamentoInstalacao', 'id_andamento_instalacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacao', 'id_instalacao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTarefaInstalacao', 'id_tarefa_instalacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaEstado', 'sta_estado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Estado', 'dth_estado');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaInstalacaoFederacao', 'sigla', 'instalacao_federacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeTarefaInstalacao', 'ti.nome', 'tarefa_instalacao ti');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade', 'sigla', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade', 'descricao', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario', 'sigla', 'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario', 'nome', 'usuario');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'DescricaoEstado');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAtributoInstalacaoDTO');

    $this->configurarPK('IdAndamentoInstalacao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdInstalacaoFederacao', 'instalacao_federacao', 'id_instalacao_federacao');

    $this->configurarFK('IdTarefaInstalacao', 'tarefa_instalacao ti', 'ti.id_tarefa_instalacao');

    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');

    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
  }
}
