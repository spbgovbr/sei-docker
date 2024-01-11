<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtributoAndamentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'atributo_andamento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAtributoAndamento',
                                   'id_atributo_andamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAtividade',
                                   'id_atividade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Valor',
                                   'valor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'IdOrigem',
                                   'id_origem');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                             'IdTarefaAtividade',
                                             'id_tarefa',
                                             'atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                             'IdProtocoloAtividade',
                                             'id_protocolo',
                                             'atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                             'IdUnidadeOrigemAtividade',
                                             'id_unidade_origem',
                                             'atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                             'IdUnidadeAtividade',
                                             'id_unidade',
                                             'atividade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
                                             'AberturaAtividade',
                                             'dth_abertura',
                                             'atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                             'IdUsuarioAtividade',
                                             'id_usuario',
                                             'atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaUsuarioAtividade',
                                             'u.sigla',
                                             'usuario u');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomeUsuarioAtividade',
                                             'u.nome',
                                             'usuario u');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                             'IdUsuarioOrigemAtividade',
                                             'id_usuario_origem',
                                             'atividade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaUsuarioOrigemAtividade',
                                             'uo.sigla',
                                             'usuario uo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomeUsuarioOrigemAtividade',
                                             'uo.nome',
                                             'usuario uo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaUnidadeOrigemAtividade',
                                             'sigla',
                                             'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'DescricaoUnidadeOrigemAtividade',
                                             'descricao',
                                             'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'IdTarefaModuloTarefa',
                                              'id_tarefa_modulo',
                                              'tarefa');

    $this->configurarPK('IdAtributoAndamento', InfraDTO::$TIPO_PK_NATIVA );
    
    
    $this->configurarFK('IdAtividade', 'atividade', 'id_atividade');
    $this->configurarFK('IdUsuarioAtividade', 'usuario u', 'u.id_usuario',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUsuarioOrigemAtividade', 'usuario uo', 'uo.id_usuario');
    $this->configurarFK('IdUnidadeOrigemAtividade', 'unidade', 'id_unidade');
    $this->configurarFK('IdTarefaAtividade', 'tarefa', 'id_tarefa');
  }
}
?>