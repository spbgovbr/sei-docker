<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/05/2008 - criado por mga
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TarefaDTO extends InfraDTO {
	
  public function getStrNomeTabela() {
  	 return 'tarefa';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTarefa', 'id_tarefa');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdTarefaModulo', 'id_tarefa_modulo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinHistoricoResumido', 'sin_historico_resumido');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinHistoricoCompleto', 'sin_historico_completo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinConsultaProcessual', 'sin_consulta_processual');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinFecharAndamentosAbertos', 'sin_fechar_andamentos_abertos');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinLancarAndamentoFechado', 'sin_lancar_andamento_fechado');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinPermiteProcessoFechado', 'sin_permite_processo_fechado');
    $this->configurarPK('IdTarefa',InfraDTO::$TIPO_PK_INFORMADO);
  }
}
?>