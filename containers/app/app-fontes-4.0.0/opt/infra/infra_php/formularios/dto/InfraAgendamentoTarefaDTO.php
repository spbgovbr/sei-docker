<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/12/2011 - criado por tamir_db
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

//require_once dirname(__FILE__).'/../Infra.php';

class InfraAgendamentoTarefaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'infra_agendamento_tarefa';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdInfraAgendamentoTarefa',
                                   'id_infra_agendamento_tarefa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Comando',
                                   'comando');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaPeriodicidadeExecucao',
                                   'sta_periodicidade_execucao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'PeriodicidadeComplemento',
                                   'periodicidade_complemento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Parametro',
                                   'parametro');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'UltimaExecucao',
                                   'dth_ultima_execucao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'UltimaConclusao',
                                   'dth_ultima_conclusao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinSucesso',
                                   'sin_sucesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'EmailErro',
                                   'email_erro');

    /*$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdOrgao',
                                   'id_orgao');*/

    $this->configurarPK('IdInfraAgendamentoTarefa',InfraDTO::$TIPO_PK_SEQUENCIAL);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>